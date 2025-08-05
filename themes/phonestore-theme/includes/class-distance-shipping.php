<?php
if (!defined('ABSPATH')) {
    exit;
}

class Distance_Based_Shipping extends WC_Shipping_Method {
    
    public function __construct($instance_id = 0) {
        $this->id = 'distance_based_shipping';
        $this->instance_id = absint($instance_id);
        $this->method_title = 'Giao hàng theo khoảng cách';
        $this->method_description = 'Tính phí ship dựa trên khoảng cách từ cửa hàng đến khách hàng';
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
                'default' => 'Giao hàng tại nhà'
            ),
            'store_address' => array(
                'title' => 'Địa chỉ cửa hàng',
                'type' => 'textarea',
                'description' => 'Địa chỉ đầy đủ của cửa hàng',
                'default' => ''
            ),
            'free_distance' => array(
                'title' => 'Khoảng cách miễn phí (km)',
                'type' => 'number',
                'description' => 'Miễn phí ship trong bán kính này',
                'default' => '10'
            ),
            'rate_per_km' => array(
                'title' => 'Giá mỗi km (VNĐ)',
                'type' => 'number',
                'description' => 'Phí ship mỗi km sau khoảng cách miễn phí',
                'default' => '2000'
            )
        );
    }
    
    public function calculate_shipping($package = array()) {
        $store_address = $this->get_option('store_address');
        $customer_address = $this->get_customer_address($package);
        
        if (empty($store_address) || empty($customer_address)) {
            return;
        }
        
        $distance = $this->calculate_distance($store_address, $customer_address);
        
        if ($distance === false) {
            // Nếu không tính được khoảng cách, sử dụng phí cố định
            $cost = 30000; // 30k VNĐ
            $label = $this->title . ' (Phí cố định)';
        } else {
            $cost = $this->calculate_shipping_cost($distance);
            $label = $this->title . sprintf(' (%.1f km)', $distance);
        }
        
        $rate = array(
            'id' => $this->get_rate_id(),
            'label' => $label,
            'cost' => $cost,
            'meta_data' => array(
                'distance' => $distance
            )
        );
        
        $this->add_rate($rate);
    }
    
    private function get_customer_address($package) {
        $address_parts = array();
        
        if (!empty($package['destination']['address_1'])) {
            $address_parts[] = $package['destination']['address_1'];
        }
        
        if (!empty($package['destination']['city'])) {
            $address_parts[] = $package['destination']['city'];
        }
        
        if (!empty($package['destination']['state'])) {
            $address_parts[] = $package['destination']['state'];
        }
        
        if (!empty($package['destination']['country'])) {
            $address_parts[] = WC()->countries->countries[$package['destination']['country']];
        }
        
        return implode(', ', $address_parts);
    }
    
    private function calculate_distance($from, $to) {
        $api_key = defined('GOOGLE_MAPS_API_KEY') ? GOOGLE_MAPS_API_KEY : '';
        
        if (empty($api_key)) {
            return false;
        }
        
        $from_encoded = urlencode($from);
        $to_encoded = urlencode($to);
        
        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins={$from_encoded}&destinations={$to_encoded}&key={$api_key}&units=metric";
        
        $response = wp_remote_get($url, array('timeout' => 10));
        
        if (is_wp_error($response)) {
            return false;
        }
        
        $data = json_decode(wp_remote_retrieve_body($response), true);
        
        if ($data['status'] !== 'OK' || 
            !isset($data['rows'][0]['elements'][0]) || 
            $data['rows'][0]['elements'][0]['status'] !== 'OK') {
            return false;
        }
        
        $distance_meters = $data['rows'][0]['elements'][0]['distance']['value'];
        return $distance_meters / 1000; // Convert to km
    }
    
    private function calculate_shipping_cost($distance) {
        $free_distance = floatval($this->get_option('free_distance', 10));
        $rate_per_km = floatval($this->get_option('rate_per_km', 2000));
        
        if ($distance <= $free_distance) {
            return 0; // Miễn phí
        }
        
        $chargeable_distance = $distance - $free_distance;
        
        // Áp dụng bảng giá theo tài liệu
        if ($distance <= 20) {
            return 15000; // 10-20km: 15,000đ
        } elseif ($distance <= 30) {
            return 25000; // 20-30km: 25,000đ
        } else {
            // Trên 30km: Viettel Post (tạm tính 35k)
            return 35000;
        }
    }
}