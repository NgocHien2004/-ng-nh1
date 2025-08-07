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
        $customer_city = strtolower($package['destination']['city'] ?? '');
        $customer_state = strtolower($package['destination']['state'] ?? '');
        $customer_address = $this->get_customer_address($package);
        
        // Tính khoảng cách
        $store_address = $this->get_option('store_address', 'Cái Răng, Cần Thơ, Việt Nam');
        $distance = $this->calculate_distance($store_address, $customer_address);
        
        if ($distance !== false && $distance <= 30) {
            $this->add_local_shipping_options($distance);
        } else {
            $this->add_viettel_post_options($customer_state);
        }
    }
    
    private function add_local_shipping_options($distance) {
        // Giao hàng gần (0-30km)
        if ($distance <= 10) {
            $this->add_rate(array(
                'id' => $this->get_rate_id() . '_free',
                'label' => sprintf('🚚 Giao hàng miễn phí (%.1f km)', $distance),
                'cost' => 0,
                'meta_data' => array('delivery_time' => 'Trong ngày')
            ));
        } elseif ($distance <= 20) {
            $this->add_rate(array(
                'id' => $this->get_rate_id() . '_near',
                'label' => sprintf('🚚 Giao hàng gần (%.1f km)', $distance),
                'cost' => 15000,
                'meta_data' => array('delivery_time' => 'Trong ngày')
            ));
        } elseif ($distance <= 30) {
            $this->add_rate(array(
                'id' => $this->get_rate_id() . '_medium',
                'label' => sprintf('🚚 Giao hàng trung (%.1f km)', $distance),
                'cost' => 25000,
                'meta_data' => array('delivery_time' => '1-2 ngày')
            ));
        }
    }
    
    private function add_viettel_post_options($customer_state) {
        $is_same_region = $this->is_same_region($customer_state);
        
        // Gói tiết kiệm
        $economy_cost = $is_same_region ? 25000 : 35000;
        $region_text = $is_same_region ? 'Cùng vùng' : 'Khác vùng';
        
        $this->add_rate(array(
            'id' => $this->get_rate_id() . '_economy',
            'label' => "📦 Viettel Post Tiết Kiệm ({$region_text})",
            'cost' => $economy_cost,
            'meta_data' => array('delivery_time' => '3-5 ngày')
        ));
        
        // Gói giao nhanh  
        $express_cost = $is_same_region ? 40000 : 50000;
        
        $this->add_rate(array(
            'id' => $this->get_rate_id() . '_express',
            'label' => "⚡ Viettel Post Giao Nhanh ({$region_text})",
            'cost' => $express_cost,
            'meta_data' => array('delivery_time' => '1-2 ngày')
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
        // Copy từ class Distance_Based_Shipping
    }
}