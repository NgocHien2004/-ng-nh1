<?php
if (!defined('ABSPATH')) {
    exit;
}

class Vietnam_Shipping_Calculator extends WC_Shipping_Method {
    
    public function __construct($instance_id = 0) {
        $this->id = 'vietnam_shipping_calculator';
        $this->instance_id = absint($instance_id);
        $this->method_title = 'Tính phí ship Việt Nam';
        $this->method_description = 'Tính phí ship theo quy tắc giao hàng Việt Nam';
        $this->supports = array(
            'shipping-zones',
            'instance-settings',
        );
        
        $this->init();
    }
    
    public function init() {
        $this->init_form_fields();
        $this->init_settings();
        
        $this->title = $this->get_option('title');
        $this->enabled = $this->get_option('enabled');
        
        add_action('woocommerce_update_options_shipping_' . $this->id, array($this, 'process_admin_options'));
    }
    
    public function init_form_fields() {
        $this->form_fields = array(
            'enabled' => array(
                'title' => 'Kích hoạt',
                'type' => 'checkbox',
                'description' => 'Kích hoạt phương thức giao hàng này',
                'default' => 'yes'
            ),
            'title' => array(
                'title' => 'Tiêu đề',
                'type' => 'text',
                'description' => 'Tiêu đề hiển thị cho khách hàng',
                'default' => 'Giao hàng tại Việt Nam'
            ),
            'store_address' => array(
                'title' => 'Địa chỉ cửa hàng',
                'type' => 'textarea',
                'description' => 'Địa chỉ đầy đủ của cửa hàng',
                'default' => 'Cái Răng, Cần Thơ, Việt Nam'
            )
        );
    }
    
    public function calculate_shipping($package = array()) {
    $province = $package['destination']['state'] ?? '';
    $district = $package['destination']['city'] ?? '';
    $detail = $package['destination']['address_1'] ?? '';
    
    error_log("Shipping calculation - Province: $province, District: $district, Detail: $detail");
    
    if (empty($province) || empty($district)) {
        // Hiển thị tất cả options nếu chưa chọn đủ thông tin
        $this->add_all_shipping_options();
        return;
    }
    
    // Tính khoảng cách với địa chỉ đầy đủ
    $store_address = $this->get_option('store_address', 'Cái Răng, Cần Thơ, Việt Nam');
    $customer_address = $this->get_customer_address($package);
    $distance = $this->calculate_distance($store_address, $customer_address);
    
    error_log("Distance calculated: " . ($distance !== false ? $distance . "km" : "failed"));
    
    if ($distance !== false && $distance <= 30) {
        $this->add_local_shipping_options($distance);
    } else {
        $this->add_viettel_post_options($province);
    }
}

private function add_all_shipping_options() {
    // Hiển thị tất cả các option khi chưa chọn địa chỉ
    $this->add_rate(array(
        'id' => $this->get_rate_id() . '_free',
        'label' => '🚚 Ship nội thành (0-10km) - MIỄN PHÍ',
        'cost' => 0,
        'meta_data' => array('delivery_time' => 'Trong ngày')
    ));
    
    $this->add_rate(array(
        'id' => $this->get_rate_id() . '_medium',
        'label' => '🚚 Ship ngoài thành (10-30km)',
        'cost' => 25000,
        'meta_data' => array('delivery_time' => '1-2 ngày')
    ));
    
    $this->add_rate(array(
        'id' => $this->get_rate_id() . '_far',
        'label' => '🚚 Ship khác vùng miền (>30km)',
        'cost' => 45000,
        'meta_data' => array('delivery_time' => '3-5 ngày')
    ));
}
    
    private function add_local_shipping_options($distance) {
    // Ship nội thành (0-10km) - MIỄN PHÍ
    if ($distance <= 10) {
        $this->add_rate(array(
            'id' => $this->get_rate_id() . '_free',
            'label' => sprintf('🚚 Ship nội thành (%.1f km) - MIỄN PHÍ', $distance),
            'cost' => 0,
            'meta_data' => array('delivery_time' => 'Trong ngày')
        ));
    }
    
    // Ship ngoài thành (10-30km) - 25,000đ
    if ($distance > 10 && $distance <= 30) {
        $this->add_rate(array(
            'id' => $this->get_rate_id() . '_medium',
            'label' => sprintf('🚚 Ship ngoài thành (%.1f km)', $distance),
            'cost' => 25000,
            'meta_data' => array('delivery_time' => '1-2 ngày')
        ));
    }
}

private function add_viettel_post_options($customer_state) {
    // Ship khác vùng miền (>30km) - 45,000đ
    $this->add_rate(array(
        'id' => $this->get_rate_id() . '_far',
        'label' => '🚚 Ship khác vùng miền (>30km)',
        'cost' => 45000,
        'meta_data' => array('delivery_time' => '3-5 ngày')
    ));
}
    
    private function is_same_region($customer_state) {
        $customer_state = strtolower($customer_state);
        
        // Miền Nam (cùng vùng với Cần Thơ)
        $south_provinces = array(
            'cần thơ', 'an giang', 'bạc liêu', 'bến tre', 'cà mau', 
            'đồng tháp', 'hậu giang', 'kiên giang', 'long an', 
            'sóc trăng', 'tiền giang', 'trà vinh', 'vĩnh long',
            'hồ chí minh', 'bình dương', 'đồng nai', 'bà rịa vũng tàu',
            'tây ninh', 'bình phước', 'bình thuận', 'ninh thuận'
        );
        
        foreach ($south_provinces as $province) {
            if (strpos($customer_state, $province) !== false) {
                return true;
            }
        }
        
        return false;
    }
    
    // Include các method calculate_distance từ class trước
    private function calculate_distance($from, $to) {
        // Copy từ class Distance_Based_Shipping ở trên
    }
    
    private function get_customer_address($package) {
    $address_parts = array();
    
    // Lấy thông tin từ dropdown và detail
    $province = $package['destination']['state'] ?? '';     // Tỉnh/Thành phố
    $district = $package['destination']['city'] ?? '';      // Quận/Huyện  
    $detail = $package['destination']['address_1'] ?? '';   // Địa chỉ chi tiết
    
    // Xây dựng địa chỉ đầy đủ cho API
    if (!empty($detail)) {
        $address_parts[] = $detail;
    }
    
    if (!empty($district)) {
        $address_parts[] = $district;
    }
    
    if (!empty($province)) {
        $address_parts[] = $province;
    }
    
    $address_parts[] = 'Việt Nam';
    
    $full_address = implode(', ', $address_parts);
    
    // Log để debug
    error_log("Customer address built: $full_address");
    
    return $full_address;
}

}