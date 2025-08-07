<?php
if (!defined('ABSPATH')) {
    exit;
}

class Simple_Shipping_Method extends WC_Shipping_Method {
    
    public function __construct($instance_id = 0) {
        $this->id = 'simple_shipping_method';
        $this->instance_id = absint($instance_id);
        $this->method_title = 'Giao hàng ẩn';
        $this->method_description = 'Shipping method ẩn - không hiển thị';
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
                'default' => 'no' // Mặc định tắt
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
        // Không thêm rates nào để ẩn shipping methods
        return;
    }
    
    public function is_available($package) {
        return false; // Luôn ẩn
    }
}