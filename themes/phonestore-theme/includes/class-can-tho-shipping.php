<?php
if (!defined('ABSPATH')) {
    exit;
}

class Can_Tho_Distance_Shipping extends WC_Shipping_Method {
    
    public function __construct($instance_id = 0) {
        $this->id = 'can_tho_distance_shipping';
        $this->instance_id = absint($instance_id);
        $this->method_title = 'Tùy chọn giao hàng';
        $this->method_description = 'Cho phép khách hàng chọn loại giao hàng (nhanh/tiết kiệm)';
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
                'description' => 'Kích hoạt tùy chọn loại giao hàng',
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
        if ($this->enabled !== 'yes') {
            return;
        }
        
        $customer_province = strtolower($package['destination']['state'] ?? '');
        $customer_city = strtolower($package['destination']['city'] ?? '');
        
        if (empty($customer_province)) {
            // Hiển thị options mặc định khi chưa có địa chỉ
            $this->add_default_options();
            return;
        }
        
        // Chuẩn hóa tên tỉnh
        $province_normalized = str_replace(array('tỉnh', 'thành phố', 'tp.', 'tp'), '', $customer_province);
        $province_normalized = trim($province_normalized);
        
        $can_tho_provinces = array('can tho', 'cần thơ', 'cantho');
        
        if (in_array($province_normalized, $can_tho_provinces)) {
            // Trong Cần Thơ - chỉ có giao hàng tiêu chuẩn
            $this->add_local_delivery_option();
        } else {
            // Ngoài tỉnh - có 2 options (tiết kiệm/nhanh)
            $this->add_viettel_post_options();
        }
    }
    
    private function add_local_delivery_option() {
        $this->add_rate(array(
            'id' => $this->get_rate_id() . '_local_standard',
            'label' => '🚚 Giao hàng tiêu chuẩn (1-2 ngày)',
            'cost' => 0, // Phí đã được tính ở cart fee
            'meta_data' => array(
                'delivery_method' => 'store_delivery',
                'service_type' => 'standard'
            )
        ));
    }
    
    private function add_viettel_post_options() {
        // Gói tiết kiệm
        $this->add_rate(array(
            'id' => $this->get_rate_id() . '_viettel_economy',
            'label' => '📦 Giao hàng tiết kiệm (3-5 ngày)',
            'cost' => 5000, // Phí bổ sung +5k
            'meta_data' => array(
                'delivery_method' => 'viettel_post',
                'service_type' => 'economy',
                'extra_fee' => 5000
            )
        ));
        
        // Gói nhanh
        $this->add_rate(array(
            'id' => $this->get_rate_id() . '_viettel_express',
            'label' => '⚡ Giao hàng nhanh (1-2 ngày)',
            'cost' => 10000, // Phí bổ sung +10k
            'meta_data' => array(
                'delivery_method' => 'viettel_post',
                'service_type' => 'express',
                'extra_fee' => 10000
            )
        ));
    }
    
    private function add_default_options() {
        // Hiển thị khi chưa có địa chỉ
        $this->add_rate(array(
            'id' => $this->get_rate_id() . '_default_standard',
            'label' => '🚚 Giao hàng tiêu chuẩn',
            'cost' => 0
        ));
        
        $this->add_rate(array(
            'id' => $this->get_rate_id() . '_default_economy',
            'label' => '📦 Giao hàng tiết kiệm (+5,000đ)',
            'cost' => 5000
        ));
        
        $this->add_rate(array(
            'id' => $this->get_rate_id() . '_default_express',
            'label' => '⚡ Giao hàng nhanh (+10,000đ)',
            'cost' => 10000
        ));
    }
    
    public function is_available($package) {
        return $this->enabled === 'yes';
    }
}