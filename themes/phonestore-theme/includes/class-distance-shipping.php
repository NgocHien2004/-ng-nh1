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
                'description' => 'Địa chỉ đầy đủ của cửa hàng để tính khoảng cách',
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
            // Giao hàng gần
            $this->add_local_shipping_rate($distance);
        } else {
            // Giao hàng xa - Viettel Post
            $this->add_viettel_post_rates($customer_state);
        }
    }
    
    private function add_local_shipping_rate($distance) {
        if ($distance <= 10) {
            $cost = 0;
            $label = sprintf('🚚 Giao hàng miễn phí (%.1f km)', $distance);
        } elseif ($distance <= 20) {
            $cost = 15000;
            $label = sprintf('🚚 Giao hàng gần (%.1f km) - 15,000đ', $distance);
        } elseif ($distance <= 30) {
            $cost = 25000;
            $label = sprintf('🚚 Giao hàng trung (%.1f km) - 25,000đ', $distance);
        } else {
            $cost = 35000;
            $label = sprintf('🚚 Giao hàng xa (%.1f km) - 35,000đ', $distance);
        }
        
        $this->add_rate(array(
            'id' => $this->get_rate_id(),
            'label' => $label,
            'cost' => $cost,
            'meta_data' => array(
                'distance' => $distance,
                'delivery_time' => $distance <= 10 ? 'Trong ngày' : '1-2 ngày'
            )
        ));
    }
    
    private function add_viettel_post_rates($customer_state) {
        $is_same_region = $this->is_same_region($customer_state);
        
        // Gói tiết kiệm
        $economy_cost = $is_same_region ? 25000 : 35000;
        $region_text = $is_same_region ? 'Cùng vùng' : 'Khác vùng';
        
        $this->add_rate(array(
            'id' => $this->get_rate_id() . '_economy',
            'label' => "📦 Viettel Post Tiết Kiệm ({$region_text}) - " . number_format($economy_cost) . 'đ',
            'cost' => $economy_cost,
            'meta_data' => array('delivery_time' => '3-5 ngày')
        ));
        
        // Gói giao nhanh  
        $express_cost = $is_same_region ? 40000 : 50000;
        
        $this->add_rate(array(
            'id' => $this->get_rate_id() . '_express',
            'label' => "⚡ Viettel Post Giao Nhanh ({$region_text}) - " . number_format($express_cost) . 'đ',
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
        // Thử OpenRouteService trước
        $distance = $this->calculate_distance_ors($from, $to);
        
        if ($distance !== false) {
            return $distance;
        }
        
        // Fallback về tính toán theo tọa độ
        return $this->calculate_distance_coordinates($from, $to);
    }
    
    private function calculate_distance_ors($from, $to) {
        $api_key = defined('OPENROUTE_API_KEY') ? OPENROUTE_API_KEY : '';
        
        if (empty($api_key)) {
            return false;
        }
        
        // Lấy tọa độ từ địa chỉ
        $from_coords = $this->geocode_address($from, $api_key);
        $to_coords = $this->geocode_address($to, $api_key);
        
        if (!$from_coords || !$to_coords) {
            return false;
        }
        
        // Tính khoảng cách bằng Matrix API
        $url = "https://api.openrouteservice.org/v2/matrix/driving-car";
        
        $data = array(
            'locations' => array(
                array($from_coords['lng'], $from_coords['lat']),
                array($to_coords['lng'], $to_coords['lat'])
            ),
            'metrics' => array('distance'),
            'units' => 'km'
        );
        
        $response = wp_remote_post($url, array(
            'timeout' => 15,
            'headers' => array(
                'Authorization' => $api_key,
                'Content-Type' => 'application/json'
            ),
            'body' => json_encode($data)
        ));
        
        if (is_wp_error($response)) {
            return false;
        }
        
        $body = json_decode(wp_remote_retrieve_body($response), true);
        
        if (isset($body['distances'][0][1])) {
            return floatval($body['distances'][0][1]);
        }
        
        return false;
    }
    
    private function geocode_address($address, $api_key) {
        $url = "https://api.openrouteservice.org/geocode/search";
        
        $params = array(
            'api_key' => $api_key,
            'text' => $address,
            'size' => 1,
            'layers' => 'address'
        );
        
        $url_with_params = $url . '?' . http_build_query($params);
        
        $response = wp_remote_get($url_with_params, array('timeout' => 10));
        
        if (is_wp_error($response)) {
            return false;
        }
        
        $data = json_decode(wp_remote_retrieve_body($response), true);
        
        if (isset($data['features'][0]['geometry']['coordinates'])) {
            $coords = $data['features'][0]['geometry']['coordinates'];
            return array(
                'lng' => $coords[0],
                'lat' => $coords[1]
            );
        }
        
        return false;
    }
    
    private function calculate_distance_coordinates($from, $to) {
        // Tọa độ cố định cho một số địa điểm ở Việt Nam
        $coordinates = array(
            'cần thơ' => array('lat' => 10.0452, 'lng' => 105.7469),
            'hồ chí minh' => array('lat' => 10.8231, 'lng' => 106.6297),
            'hà nội' => array('lat' => 21.0285, 'lng' => 105.8542),
            'đà nẵng' => array('lat' => 16.0471, 'lng' => 108.2068),
            'nha trang' => array('lat' => 12.2585, 'lng' => 109.0526),
            'cái răng' => array('lat' => 10.0333, 'lng' => 105.7831)
        );
        
        $from_lower = strtolower($from);
        $to_lower = strtolower($to);
        
        $from_coords = null;
        $to_coords = null;
        
        foreach ($coordinates as $place => $coords) {
            if (strpos($from_lower, $place) !== false) {
                $from_coords = $coords;
            }
            if (strpos($to_lower, $place) !== false) {
                $to_coords = $coords;
            }
        }
        
        if (!$from_coords || !$to_coords) {
            return false;
        }
        
        // Tính khoảng cách theo công thức Haversine
        $earth_radius = 6371;
        
        $lat_diff = deg2rad($to_coords['lat'] - $from_coords['lat']);
        $lng_diff = deg2rad($to_coords['lng'] - $from_coords['lng']);
        
        $a = sin($lat_diff/2) * sin($lat_diff/2) + 
             cos(deg2rad($from_coords['lat'])) * cos(deg2rad($to_coords['lat'])) * 
             sin($lng_diff/2) * sin($lng_diff/2);
             
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        
        return $earth_radius * $c;
    }
}