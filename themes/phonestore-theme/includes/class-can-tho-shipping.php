<?php
if (!defined('ABSPATH')) {
    exit;
}

class Simple_Shipping_Method extends WC_Shipping_Method {
    
    private $store_coordinates = array(
        'lat' => 10.045162, // Đại học Cần Thơ
        'lng' => 105.746857
    );
    
    public function __construct($instance_id = 0) {
        $this->id = 'simple_shipping_method';
        $this->instance_id = absint($instance_id);
        $this->method_title = 'Tính phí ship theo khoảng cách';
        $this->method_description = 'Tính phí ship tự động dựa trên khoảng cách từ Đại học Cần Thơ';
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
                'default' => 'Giao hàng'
            )
        );
    }
    
    public function calculate_shipping($package = array()) {
        // Không add shipping rates nữa, sẽ dùng cart fees
        return;
    }
    
    public function is_available($package) {
        return $this->enabled === 'yes';
    }
    
    // Method tính khoảng cách
    public function calculate_distance_to_customer($customer_address) {
        // Sử dụng dữ liệu có sẵn cho các quận/huyện Cần Thơ
        $distance = $this->get_predefined_distance($customer_address);
        
        if ($distance !== false) {
            return $distance;
        }
        
        // Fallback: tính theo API hoặc ước lượng
        return $this->estimate_distance_by_province($customer_address);
    }
    
    private function get_predefined_distance($address) {
        $address_lower = strtolower($address);
        
        // Dữ liệu khoảng cách từ Đại học Cần Thơ
        $distances = array(
            'ninh kiều' => 5,
            'cái răng' => 8,
            'bình thủy' => 12,
            'ô môn' => 15,
            'thốt nốt' => 18,
            'vĩnh thạnh' => 22,
            'cờ đỏ' => 25,
            'phong điền' => 28,
            'thới lai' => 30,
            // Thêm các quận/huyện khác
            'chợ mới' => 35, // An Giang
            'long xuyên' => 40,
            'châu đốc' => 45,
            'tân châu' => 38,
            'an phú' => 42,
            'bến tre' => 35,
            'mỏ cày nam' => 40,
            'giồng trôm' => 45,
            'cà mau' => 50,
            'u minh' => 48,
            'ngọc hiển' => 55,
        );
        
        foreach ($distances as $location => $distance) {
            if (strpos($address_lower, $location) !== false) {
                return $distance;
            }
        }
        
        return false;
    }
    
    private function estimate_distance_by_province($address) {
        $address_lower = strtolower($address);
        
        // Ước lượng theo tỉnh/thành phố
        if (strpos($address_lower, 'cần thơ') !== false) {
            return 15; // Trung bình trong thành phố
        } elseif (strpos($address_lower, 'an giang') !== false || 
                  strpos($address_lower, 'kiên giang') !== false ||
                  strpos($address_lower, 'đồng tháp') !== false) {
            return 45; // Tỉnh lân cận
        } else {
            return 60; // Khác tỉnh
        }
    }
    
    // Method tính phí ship
    public function calculate_shipping_fee($distance, $is_economy = true) {
        if ($distance <= 10) {
            return 0; // Miễn phí
        } elseif ($distance <= 20) {
            return 15000;
        } elseif ($distance <= 30) {
            return 25000;
        } else {
            // Giao hàng xa - Viettel Post
            $base_fee = $is_economy ? 30000 : 40000; // Base fee cho Viettel
            $extra_fee = $is_economy ? 5000 : 10000; // Thêm phí
            return $base_fee + $extra_fee;
        }
    }
}