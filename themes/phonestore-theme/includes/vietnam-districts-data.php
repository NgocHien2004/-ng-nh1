<?php
if (!defined('ABSPATH')) {
    exit;
}

class Vietnam_Districts_Data {
    
    // Dữ liệu tọa độ các quận/huyện chính ở miền Tây
    public static function get_districts_coordinates() {
        return array(
            // Cần Thơ
            'ninh kieu' => array('lat' => 10.034036, 'lng' => 105.782202),
            'cai rang' => array('lat' => 10.006667, 'lng' => 105.770833),
            'binh thuy' => array('lat' => 10.086667, 'lng' => 105.756667),
            'o mon' => array('lat' => 10.135000, 'lng' => 105.640000),
            'thot not' => array('lat' => 10.290000, 'lng' => 105.850000),
            'co do' => array('lat' => 9.830000, 'lng' => 105.760000),
            'phong dien' => array('lat' => 10.020000, 'lng' => 105.620000),
            'thoi lai' => array('lat' => 10.200000, 'lng' => 105.700000),
            'vinh thanh' => array('lat' => 10.150000, 'lng' => 105.950000),
            
            // An Giang
            'long xuyen' => array('lat' => 10.386667, 'lng' => 105.435000),
            'chau doc' => array('lat' => 10.700000, 'lng' => 105.116667),
            'tan chau' => array('lat' => 10.797778, 'lng' => 105.164167),
            'phu tan' => array('lat' => 10.700000, 'lng' => 105.400000),
            'an phu' => array('lat' => 10.800000, 'lng' => 105.200000),
            'thoai son' => array('lat' => 10.230000, 'lng' => 105.290000),
            'tri ton' => array('lat' => 10.400000, 'lng' => 104.900000),
            
            // Đồng Tháp
            'cao lanh' => array('lat' => 10.466667, 'lng' => 105.633333),
            'sa dec' => array('lat' => 10.293056, 'lng' => 105.757500),
            'hong ngu' => array('lat' => 10.206667, 'lng' => 105.433333),
            'tam nong' => array('lat' => 10.650000, 'lng' => 105.550000),
            'thanh binh' => array('lat' => 10.430000, 'lng' => 105.770000),
            'lap vo' => array('lat' => 10.190000, 'lng' => 105.570000),
            
            // Kiên Giang
            'rach gia' => array('lat' => 10.012500, 'lng' => 105.080833),
            'ha tien' => array('lat' => 10.381944, 'lng' => 104.488889),
            'phu quoc' => array('lat' => 10.216667, 'lng' => 103.966667),
            'kien luong' => array('lat' => 10.250000, 'lng' => 104.600000),
            'an bien' => array('lat' => 10.100000, 'lng' => 104.850000),
            'an minh' => array('lat' => 9.870000, 'lng' => 104.970000),
            'chau thanh' => array('lat' => 10.040000, 'lng' => 105.170000),
            
            // Hậu Giang
            'vi thanh' => array('lat' => 9.780556, 'lng' => 105.470833),
            'nga bay' => array('lat' => 9.796111, 'lng' => 105.628056),
            'chau thanh a' => array('lat' => 9.650000, 'lng' => 105.520000),
            'long my' => array('lat' => 9.670000, 'lng' => 105.730000),
            'phung hiep' => array('lat' => 9.780000, 'lng' => 105.750000),
            
            // Sóc Trăng
            'soc trang' => array('lat' => 9.603056, 'lng' => 105.980000),
            'ke sach' => array('lat' => 9.800000, 'lng' => 105.830000),
            'my tu' => array('lat' => 9.530000, 'lng' => 105.950000),
            'cu lao dung' => array('lat' => 9.400000, 'lng' => 106.100000),
            
            // Bạc Liêu
            'bac lieu' => array('lat' => 9.285000, 'lng' => 105.724167),
            'gia rai' => array('lat' => 9.250000, 'lng' => 105.520000),
            'vinh loi' => array('lat' => 9.320000, 'lng' => 105.620000),
            'dong hai' => array('lat' => 9.100000, 'lng' => 105.350000),
            
            // Cà Mau
            'ca mau' => array('lat' => 9.177222, 'lng' => 105.150000),
            'u minh' => array('lat' => 9.000000, 'lng' => 105.050000),
            'thoi binh' => array('lat' => 9.200000, 'lng' => 105.000000),
            'tran van thoi' => array('lat' => 8.850000, 'lng' => 104.950000),
            'cai nuoc' => array('lat' => 9.000000, 'lng' => 105.150000),
            'dam doi' => array('lat' => 8.900000, 'lng' => 105.200000),
            'nam can' => array('lat' => 8.750000, 'lng' => 105.050000),
            'phu tan ca mau' => array('lat' => 9.100000, 'lng' => 105.350000),
            
            // Tiền Giang
            'my tho' => array('lat' => 10.360000, 'lng' => 106.360000),
            'go cong' => array('lat' => 10.370000, 'lng' => 106.670000),
            'cai lay' => array('lat' => 10.420000, 'lng' => 106.130000),
            'cai be' => array('lat' => 10.450000, 'lng' => 106.050000),
            'chau thanh tien giang' => array('lat' => 10.300000, 'lng' => 106.250000),
            
            // Vĩnh Long
            'vinh long' => array('lat' => 10.253889, 'lng' => 105.957222),
            'binh minh' => array('lat' => 10.070000, 'lng' => 106.050000),
            'long ho' => array('lat' => 10.150000, 'lng' => 105.900000),
            'mang thit' => array('lat' => 10.170000, 'lng' => 106.120000),
            'tam binh' => array('lat' => 10.230000, 'lng' => 106.200000),
            'tra on' => array('lat' => 9.950000, 'lng' => 106.030000),
            'binh tan' => array('lat' => 10.200000, 'lng' => 106.300000),
            
            // Bến Tre
            'ben tre' => array('lat' => 10.243056, 'lng' => 106.375278),
            'chau thanh ben tre' => array('lat' => 10.300000, 'lng' => 106.450000),
            'cho lach' => array('lat' => 10.230000, 'lng' => 106.500000),
            'mo cay nam' => array('lat' => 10.150000, 'lng' => 106.650000),
            'giong trom' => array('lat' => 10.270000, 'lng' => 106.300000),
            'binh dai' => array('lat' => 10.180000, 'lng' => 106.680000),
            'ba tri' => array('lat' => 10.050000, 'lng' => 106.600000),
            'thanh phu' => array('lat' => 10.200000, 'lng' => 106.400000),
            'mo cay bac' => array('lat' => 10.200000, 'lng' => 106.550000),
        );
    }
    
    // Tính khoảng cách nhanh dựa trên dữ liệu có sẵn
    public static function get_distance_to_can_tho_university($district_name) {
        $can_tho_uni_coords = array('lat' => 10.029934, 'lng' => 105.770200);
        $districts = self::get_districts_coordinates();
        
        $district_key = strtolower(trim($district_name));
        $district_key = self::normalize_district_name($district_key);
        
        if (isset($districts[$district_key])) {
            $district_coords = $districts[$district_key];
            return self::calculate_haversine_distance(
                $can_tho_uni_coords['lat'], 
                $can_tho_uni_coords['lng'],
                $district_coords['lat'], 
                $district_coords['lng']
            );
        }
        
        return false;
    }
    
    private static function normalize_district_name($name) {
        // Loại bỏ các từ không cần thiết
        $remove_words = array('quan', 'huyen', 'thi xa', 'thanh pho', 'thi tran');
        $name = str_replace($remove_words, '', $name);
        $name = trim($name);
        
        // Chuẩn hóa một số tên đặc biệt
        $normalize_map = array(
            'tp can tho' => 'ninh kieu',
            'can tho' => 'ninh kieu',
            'tp' => 'ninh kieu',
            'tp.can tho' => 'ninh kieu',
            'thanh pho can tho' => 'ninh kieu'
        );
        
        if (isset($normalize_map[$name])) {
            return $normalize_map[$name];
        }
        
        return $name;
    }
    
    private static function calculate_haversine_distance($lat1, $lng1, $lat2, $lng2) {
        $earth_radius = 6371; // km
        
        $dlat = deg2rad($lat2 - $lat1);
        $dlng = deg2rad($lng2 - $lng1);
        
        $a = sin($dlat/2) * sin($dlat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dlng/2) * sin($dlng/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        
        return $earth_radius * $c;
    }
}