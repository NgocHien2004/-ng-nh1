<?php
if (!defined('ABSPATH')) {
    exit;
}

class Multi_Shipping_Options extends WC_Shipping_Method {
    
    public function __construct($instance_id = 0) {
        $this->id = 'multi_shipping_options';
        $this->instance_id = absint($instance_id);
        $this->method_title = 'Tùy chọn giao hàng';
        $this->method_description = 'Nhiều tùy chọn giao hàng với phí khác nhau';
        $this->supports = array(
            'shipping-zones',
            'instance-settings',
        );
        
        $this->init();
    }
    
    public function calculate_shipping($package = array()) {
        $store_address = $this->get_option('store_address', 'Cái Răng, Cần Thơ, Việt Nam');
        $customer_address = $this->get_customer_address($package);
        
        if (empty($customer_address)) {
            return;
        }
        
        $distance = $this->calculate_distance($store_address, $customer_address);
        
        if ($distance === false || $distance <= 30) {
            // Giao hàng gần - tự giao
            $this->add_local_shipping_rates($distance);
        } else {
            // Giao hàng xa - Viettel Post
            $this->add_viettel_post_rates();
        }
    }
    
    private function add_local_shipping_rates($distance) {
        if ($distance === false) {
            $distance = 15; // Giả sử khoảng cách trung bình
        }
        
        if ($distance <= 10) {
            $this->add_rate(array(
                'id' => $this->get_rate_id() . '_free',
                'label' => '🚚 Giao hàng miễn phí (0-10km)',
                'cost' => 0,
                'meta_data' => array(
                    'delivery_time' => 'Trong ngày',
                    'distance' => $distance
                )
            ));
        }
        
        if ($distance > 10 && $distance <= 20) {
            $this->add_rate(array(
                'id' => $this->get_rate_id() . '_near',
                'label' => '🚚 Giao hàng gần (10-20km)',
                'cost' => 15000,
                'meta_data' => array(
                    'delivery_time' => 'Trong ngày',
                    'distance' => $distance
                )
            ));
        }
        
        if ($distance > 20 && $distance <= 30) {
            $this->add_rate(array(
                'id' => $this->get_rate_id() . '_medium',
                'label' => '🚚 Giao hàng xa (20-30km)',
                'cost' => 25000,
                'meta_data' => array(
                    'delivery_time' => '1-2 ngày',
                    'distance' => $distance
                )
            ));
        }
    }
    
    private function add_viettel_post_rates() {
        // Gói tiết kiệm - Cùng vùng miền
        $this->add_rate(array(
            'id' => $this->get_rate_id() . '_economy_same',
            'label' => '📦 Viettel Post - Tiết kiệm (Cùng vùng)',
            'cost' => 25000,
            'meta_data' => array(
                'delivery_time' => '3-5 ngày',
                'service_type' => 'economy'
            )
        ));
        
        // Gói tiết kiệm - Khác vùng miền
        $this->add_rate(array(
            'id' => $this->get_rate_id() . '_economy_diff',
            'label' => '📦 Viettel Post - Tiết kiệm (Khác vùng)',
            'cost' => 35000,
            'meta_data' => array(
                'delivery_time' => '3-5 ngày',
                'service_type' => 'economy'
            )
        ));
        
        // Gói giao nhanh - Cùng vùng miền
        $this->add_rate(array(
            'id' => $this->get_rate_id() . '_express_same',
            'label' => '⚡ Viettel Post - Giao nhanh (Cùng vùng)',
            'cost' => 40000,
            'meta_data' => array(
                'delivery_time' => '1-2 ngày',
                'service_type' => 'express'
            )
        ));
        
        // Gói giao nhanh - Khác vùng miền
        $this->add_rate(array(
            'id' => $this->get_rate_id() . '_express_diff',
            'label' => '⚡ Viettel Post - Giao nhanh (Khác vùng)',
            'cost' => 50000,
            'meta_data' => array(
                'delivery_time' => '1-2 ngày',
                'service_type' => 'express'
            )
        ));
    }
}