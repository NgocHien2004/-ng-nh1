<?php
if (!defined('ABSPATH')) {
    exit;
}

class Simple_Shipping_Method extends WC_Shipping_Method {
    
    public function __construct($instance_id = 0) {
        $this->id = 'simple_shipping_method';
        $this->instance_id = absint($instance_id);
        $this->method_title = 'Giao hàng đơn giản';
        $this->method_description = 'Chỉ 2 tùy chọn: Ship nhanh và Ship tiết kiệm';
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
                'default' => 'Tùy chọn giao hàng'
            )
        );
    }
    
    public function calculate_shipping($package = array()) {
        // Ship Tiết kiệm - giá rẻ, giao chậm
        $this->add_rate(array(
            'id' => $this->get_rate_id() . '_economy',
            'label' => '📦 Ship Tiết kiệm - 3-5 ngày',
            'cost' => 25000,
            'meta_data' => array(
                'delivery_method' => 'economy',
                'delivery_time' => '3-5 ngày'
            )
        ));
        
        // Ship Nhanh - giá cao, giao nhanh
        $this->add_rate(array(
            'id' => $this->get_rate_id() . '_express',
            'label' => '⚡ Ship Nhanh - 1-2 ngày',
            'cost' => 45000,
            'meta_data' => array(
                'delivery_method' => 'express',
                'delivery_time' => '1-2 ngày'
            )
        ));
    }
    
    public function is_available($package) {
        return $this->enabled === 'yes';
    }
}