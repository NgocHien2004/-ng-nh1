<?php
if (!defined('ABSPATH')) {
    exit;
}

class Can_Tho_Distance_Shipping extends WC_Shipping_Method {
    
    private $store_coordinates;
    private $shipping_rates;
    private $can_tho_provinces;
    private $south_region_provinces;
    
    public function __construct($instance_id = 0) {
        $this->id = 'can_tho_distance_shipping';
        $this->instance_id = absint($instance_id);
        $this->method_title = 'Giao hàng Cần Thơ';
        $this->method_description = 'Tính phí ship từ ĐH Cần Thơ - Giao gần miễn phí, giao xa theo Viettel Post';
        $this->supports = array(
            'shipping-zones',
            'instance-settings',
        );
        
        // Tọa độ Đại học Cần Thơ
        $this->store_coordinates = array(
            'lat' => 10.029934,
            'lng' => 105.770200
        );
        
        // Bảng giá giao hàng gần (cửa hàng tự giao)
        $this->shipping_rates = array(
            array('max_distance' => 10, 'price' => 0, 'label' => '🚴‍♂️ Giao hàng miễn phí (0-10km)', 'method' => 'store_delivery'),
            array('max_distance' => 20, 'price' => 15000, 'label' => '🚗 Giao hàng gần (10-20km)', 'method' => 'store_delivery'),
            array('max_distance' => 30, 'price' => 25000, 'label' => '🚛 Giao hàng xa (20-30km)', 'method' => 'store_delivery')
        );
        
        // Danh sách tỉnh/thành phố Cần Thơ và lân cận (cùng vùng miền Tây Nam Bộ)
        $this->can_tho_provinces = array(
            'can tho', 'cần thơ', 'cantho'
        );
        
        // Vùng miền Nam (cùng vùng miền)
        $this->south_region_provinces = array(
            'an giang', 'bac lieu', 'bạc liêu', 'ben tre', 'bến tre', 'ca mau', 'cà mau',
            'dong thap', 'đồng tháp', 'hau giang', 'hậu giang', 'kien giang', 'kiên giang',
            'long an', 'soc trang', 'sóc trăng', 'tay ninh', 'tây ninh', 'tien giang', 'tiền giang',
            'tra vinh', 'trà vinh', 'vinh long', 'vĩnh long', 'ho chi minh', 'hồ chí minh',
            'tp ho chi minh', 'tp hồ chí minh', 'ho chi minh city', 'saigon', 'sài gòn',
            'binh duong', 'bình dương', 'binh phuoc', 'bình phước', 'dong nai', 'đồng nai',
            'ba ria vung tau', 'bà rịa vũng tàu'
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
                'description' => 'Kích hoạt phương thức giao hàng Cần Thơ',
                'default' => 'yes'
            ),
            'title' => array(
                'title' => 'Tiêu đề',
                'type' => 'text',
                'description' => 'Tiêu đề hiển thị cho khách hàng',
                'default' => 'Giao hàng từ Cần Thơ'
            )
        );
    }
    
    public function calculate_shipping($package = array()) {
    if ($this->enabled !== 'yes') {
        return;
    }
    
    $customer_province = strtolower($package['destination']['state'] ?? '');
    $customer_city = strtolower($package['destination']['city'] ?? '');
    $customer_address = $package['destination']['address_1'] ?? '';
    
    // Luôn hiển thị ít nhất một option để user có thể place order
    if (empty($customer_province)) {
        $this->add_default_shipping_options();
        return;
    }
    
    // Kiểm tra xem có phải giao hàng trong vùng Cần Thơ không
    if ($this->is_can_tho_province($customer_province)) {
        // Nếu có đầy đủ thông tin địa chỉ, tính khoảng cách
        if (!empty($customer_city) && !empty($customer_address)) {
            $full_address = $this->get_customer_full_address($package);
            $distance = $this->calculate_distance_to_store($full_address);
            
            if ($distance !== false && $distance <= 30) {
                $this->add_local_delivery_options($distance);
                return;
            }
        }
        
        // Fallback cho Cần Thơ khi thiếu thông tin hoặc khoảng cách > 30km
        $this->add_can_tho_fallback_options();
    } else {
        // Giao hàng ngoài tỉnh - dùng Viettel Post
        $this->add_viettel_post_options($customer_province);
    }
}
private function add_can_tho_fallback_options() {
    // Options dự phòng cho Cần Thơ khi không tính được khoảng cách chính xác
    $this->add_rate(array(
        'id' => $this->get_rate_id() . '_canthofallback_free',
        'label' => '🚴‍♂️ Giao hàng nội thành Cần Thơ - Miễn phí',
        'cost' => 0,
        'meta_data' => array(
            'delivery_method' => 'store_delivery',
            'delivery_time' => 'Giao trong ngày'
        )
    ));
    
    $this->add_rate(array(
        'id' => $this->get_rate_id() . '_canthofallback_near',
        'label' => '🚗 Giao hàng ven thành Cần Thơ',
        'cost' => 15000,
        'meta_data' => array(
            'delivery_method' => 'store_delivery',
            'delivery_time' => '1-2 ngày'
        )
    ));
    
    $this->add_rate(array(
        'id' => $this->get_rate_id() . '_canthofallback_far',
        'label' => '🚛 Giao hàng xa Cần Thơ',
        'cost' => 25000,
        'meta_data' => array(
            'delivery_method' => 'store_delivery',
            'delivery_time' => '1-2 ngày'
        )
    ));
}    

private function add_default_shipping_options() {
    // Hiển thị option mặc định khi chưa có thông tin tỉnh/thành phố
    $this->add_rate(array(
        'id' => $this->get_rate_id() . '_default',
        'label' => '🚚 Phí vận chuyển (sẽ cập nhật khi nhập địa chỉ)',
        'cost' => 25000,
        'meta_data' => array(
            'delivery_method' => 'pending',
            'note' => 'Phí sẽ được tính lại khi có địa chỉ đầy đủ'
        )
    ));
}
    private function is_can_tho_province($province) {
        $province = $this->normalize_province_name($province);
        return in_array($province, $this->can_tho_provinces);
    }
    
    private function is_same_region($province) {
        $province = $this->normalize_province_name($province);
        return in_array($province, $this->south_region_provinces);
    }
    
    private function normalize_province_name($province) {
        $province = strtolower(trim($province));
        $province = str_replace(array('tỉnh', 'thành phố', 'tp.', 'tp'), '', $province);
        $province = trim($province);
        
        // Chuẩn hóa một số tên đặc biệt
        $normalize_map = array(
            'hcm' => 'ho chi minh',
            'tphcm' => 'ho chi minh',
            'sài gòn' => 'ho chi minh',
            'saigon' => 'ho chi minh'
        );
        
        return isset($normalize_map[$province]) ? $normalize_map[$province] : $province;
    }
    
    private function get_customer_full_address($package) {
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
        
        $address = implode(', ', array_filter($address_parts));
        
        if (!empty($address)) {
            $address .= ', Việt Nam';
        }
        
        return $address;
    }
    
    private function calculate_distance_to_store($customer_address) {
        // Trước tiên thử dùng dữ liệu districts có sẵn
        $district_name = $this->extract_district_from_address($customer_address);
        if ($district_name) {
            require_once get_template_directory() . '/includes/vietnam-districts-data.php';
            $distance = Vietnam_Districts_Data::get_distance_to_can_tho_university($district_name);
            if ($distance !== false) {
                return $distance;
            }
        }
        
        // Nếu không tìm thấy trong dữ liệu có sẵn, dùng API
        return $this->calculate_distance_via_api($customer_address);
    }
    
    private function extract_district_from_address($address) {
        // Tách tên quận/huyện từ địa chỉ
        $parts = explode(',', $address);
        if (count($parts) >= 2) {
            return trim($parts[1]); // Phần thứ 2 thường là quận/huyện
        }
        return null;
    }
    
    private function calculate_distance_via_api($customer_address) {
        $api_key = defined('OPENROUTE_API_KEY') ? OPENROUTE_API_KEY : '';
        
        if (empty($api_key)) {
            error_log('OpenRoute API key not found');
            return false;
        }
        
        // Geocoding địa chỉ khách hàng
        $customer_coords = $this->geocode_address($customer_address, $api_key);
        
        if (!$customer_coords) {
            return false;
        }
        
        // Tính khoảng cách
        $distance = $this->calculate_haversine_distance(
            $this->store_coordinates['lat'],
            $this->store_coordinates['lng'],
            $customer_coords['lat'],
            $customer_coords['lng']
        );
        
        return $distance;
    }
    
    private function geocode_address($address, $api_key) {
        $url = 'https://api.openrouteservice.org/geocode/search';
        $params = array(
            'api_key' => $api_key,
            'text' => $address,
            'boundary.country' => 'VN',
            'size' => 1
        );
        
        $url .= '?' . http_build_query($params);
        
        $response = wp_remote_get($url, array(
            'timeout' => 10,
            'headers' => array(
                'User-Agent' => 'WordPress/CanTho-Shipping'
            )
        ));
        
        if (is_wp_error($response)) {
            error_log('Geocoding error: ' . $response->get_error_message());
            return false;
        }
        
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        
        if (isset($data['features'][0]['geometry']['coordinates'])) {
            $coords = $data['features'][0]['geometry']['coordinates'];
            return array(
                'lng' => $coords[0],
                'lat' => $coords[1]
            );
        }
        
        return false;
    }
    
    private function calculate_haversine_distance($lat1, $lng1, $lat2, $lng2) {
        $earth_radius = 6371; // km
        
        $dlat = deg2rad($lat2 - $lat1);
        $dlng = deg2rad($lng2 - $lng1);
        
        $a = sin($dlat/2) * sin($dlat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dlng/2) * sin($dlng/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        
        return $earth_radius * $c;
    }
    
    private function add_local_delivery_options($distance) {
        foreach ($this->shipping_rates as $rate) {
            if ($distance <= $rate['max_distance']) {
                $label = $rate['label'];
                if ($distance > 0) {
                    $label .= ' - ' . round($distance, 1) . 'km';
                }
                
                $this->add_rate(array(
                    'id' => $this->get_rate_id() . '_local_' . $rate['max_distance'],
                    'label' => $label,
                    'cost' => $rate['price'],
                    'meta_data' => array(
                        'distance' => $distance,
                        'delivery_method' => 'store_delivery',
                        'delivery_time' => $rate['price'] == 0 ? 'Giao trong ngày' : '1-2 ngày'
                    )
                ));
                break;
            }
        }
    }
    
    private function add_viettel_post_options($customer_province) {
        $is_same_region = $this->is_same_region($customer_province);
        
        // Gói tiết kiệm (Economy)
        $economy_price = $is_same_region ? 25000 : 35000;
        $region_text = $is_same_region ? 'cùng vùng miền' : 'khác vùng miền';
        
        $this->add_rate(array(
            'id' => $this->get_rate_id() . '_viettel_economy',
            'label' => '📦 Viettel Post Tiết kiệm (' . $region_text . ') - 3-5 ngày',
            'cost' => $economy_price,
            'meta_data' => array(
                'delivery_method' => 'viettel_post',
                'service_type' => 'economy',
                'delivery_time' => '3-5 ngày',
                'region_type' => $is_same_region ? 'same_region' : 'different_region'
            )
        ));
        
        // Gói giao nhanh (Express)
        $express_price = $is_same_region ? 40000 : 50000;
        
        $this->add_rate(array(
            'id' => $this->get_rate_id() . '_viettel_express',
            'label' => '⚡ Viettel Post Nhanh (' . $region_text . ') - 1-2 ngày',
            'cost' => $express_price,
            'meta_data' => array(
                'delivery_method' => 'viettel_post',
                'service_type' => 'express',
                'delivery_time' => '1-2 ngày',
                'region_type' => $is_same_region ? 'same_region' : 'different_region'
            )
        ));
    }
    
    private function add_all_shipping_options() {
        // Hiển thị tất cả options khi chưa chọn địa chỉ
        
        // Giao hàng gần
        foreach ($this->shipping_rates as $rate) {
            $this->add_rate(array(
                'id' => $this->get_rate_id() . '_local_' . $rate['max_distance'],
                'label' => $rate['label'],
                'cost' => $rate['price'],
                'meta_data' => array(
                    'delivery_method' => 'store_delivery'
                )
            ));
        }
        
        // Viettel Post - cùng vùng
        $this->add_rate(array(
            'id' => $this->get_rate_id() . '_viettel_economy_same',
            'label' => '📦 Viettel Post Tiết kiệm (cùng vùng) - 3-5 ngày',
            'cost' => 25000,
            'meta_data' => array('delivery_method' => 'viettel_post', 'service_type' => 'economy')
        ));
        
        $this->add_rate(array(
            'id' => $this->get_rate_id() . '_viettel_express_same',
            'label' => '⚡ Viettel Post Nhanh (cùng vùng) - 1-2 ngày',
            'cost' => 40000,
            'meta_data' => array('delivery_method' => 'viettel_post', 'service_type' => 'express')
        ));
        
        // Viettel Post - khác vùng
        $this->add_rate(array(
            'id' => $this->get_rate_id() . '_viettel_economy_diff',
            'label' => '📦 Viettel Post Tiết kiệm (khác vùng) - 3-5 ngày',
            'cost' => 35000,
            'meta_data' => array('delivery_method' => 'viettel_post', 'service_type' => 'economy')
        ));
        
        $this->add_rate(array(
            'id' => $this->get_rate_id() . '_viettel_express_diff',
            'label' => '⚡ Viettel Post Nhanh (khác vùng) - 1-2 ngày',
            'cost' => 50000,
            'meta_data' => array('delivery_method' => 'viettel_post', 'service_type' => 'express')
        ));
    }
    // Thêm method này vào class Can_Tho_Distance_Shipping

public function is_available($package) {
    // Luôn available để tránh lỗi "no shipping methods"
    return $this->enabled === 'yes';
}

private function add_emergency_fallback() {
    // Fallback cuối cùng nếu tất cả đều fail
    $this->add_rate(array(
        'id' => $this->get_rate_id() . '_emergency',
        'label' => '🚚 Giao hàng tiêu chuẩn',
        'cost' => 25000,
        'meta_data' => array(
            'delivery_method' => 'standard',
            'note' => 'Phí chuẩn cho mọi đơn hàng'
        )
    ));
}
}