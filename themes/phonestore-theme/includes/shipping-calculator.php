<?php
if (!defined('ABSPATH')) {
    exit;
}

class PhoneStore_Shipping_Calculator {
    
    private $store_location = 'Đại học Cần Thơ, Cần Thơ';
    
    // Danh sách các quận/huyện nội thành Cần Thơ
    private $cantho_districts = [
        'ninh kiều', 'cái răng', 'bình thủy', 'ô môn', 'thốt nốt',
        'phong điền', 'cờ đỏ', 'vĩnh thạnh', 'thới lai'
    ];
    
    public function calculate_shipping_fee($province, $district, $shipping_type = 'economy') {
        $province = strtolower(trim($province));
        $district = strtolower(trim($district));
        
        // Kiểm tra nội thành Cần Thơ - MIỄN PHÍ
        if ($this->is_cantho_city($province, $district)) {
            return 0;
        }
        
        // Ngoài Cần Thơ nhưng cùng tỉnh - +25,000
        if ($this->is_cantho_province($province)) {
            return 25000;
        }
        
        // Khác tỉnh - tính theo gói
        $base_fee = 25000; // Phí cơ bản cho khác tỉnh
        
        if ($shipping_type === 'economy') {
            return $base_fee + 5000; // +5,000 cho gói tiết kiệm
        } else {
            return $base_fee + 10000; // +10,000 cho gói nhanh
        }
    }
    
    private function is_cantho_city($province, $district) {
        // Kiểm tra có phải thành phố Cần Thơ không
        if (strpos($province, 'cần thơ') === false && strpos($province, 'can tho') === false) {
            return false;
        }
        
        // Kiểm tra quận/huyện nội thành
        foreach ($this->cantho_districts as $cantho_district) {
            if (strpos($district, $cantho_district) !== false) {
                return true;
            }
        }
        
        return false;
    }
    
    private function is_cantho_province($province) {
        return (strpos($province, 'cần thơ') !== false || strpos($province, 'can tho') !== false);
    }
    
    public function get_shipping_description($province, $district, $shipping_type = 'economy') {
        $fee = $this->calculate_shipping_fee($province, $district, $shipping_type);
        
        if ($fee === 0) {
            return 'Miễn phí giao hàng nội thành';
        } elseif ($fee === 25000) {
            return 'Giao hàng ngoại thành Cần Thơ';
        } else {
            $type_text = $shipping_type === 'economy' ? 'tiết kiệm' : 'nhanh';
            return "Giao hàng khác tỉnh - gói {$type_text}";
        }
    }
}