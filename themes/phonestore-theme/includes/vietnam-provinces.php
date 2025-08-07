<?php
if (!defined('ABSPATH')) {
    exit;
}

function get_vietnam_provinces_districts() {
    return array(
        'An Giang' => array(
            'Long Xuyên', 'Châu Đốc', 'An Phú', 'Tân Châu', 'Phú Tân', 'Châu Phú',
            'Tịnh Biên', 'Tri Tôn', 'Châu Thành', 'Chợ Mới', 'Thoại Sơn'
        ),
        'Bà Rịa - Vũng Tàu' => array(
            'Vũng Tàu', 'Bà Rịa', 'Châu Đức', 'Xuyên Mộc', 'Long Điền', 'Đất Đỏ', 'Côn Đảo'
        ),
        'Bạc Liêu' => array(
            'Bạc Liêu', 'Hồng Dân', 'Phước Long', 'Vĩnh Lợi', 'Giá Rai', 'Đông Hải', 'Hòa Bình'
        ),
        'Bắc Giang' => array(
            'Bắc Giang', 'Việt Yên', 'Tân Yên', 'Lạng Giang', 'Lục Nam', 'Lục Ngạn',
            'Sơn Động', 'Yên Thế', 'Hiệp Hòa', 'Yên Dũng'
        ),
        'Bắc Kạn' => array(
            'Bắc Kạn', 'Pác Nặm', 'Ba Bể', 'Ngân Sơn', 'Bạch Thông', 'Chợ Đồn', 'Chợ Mới', 'Na Rì'
        ),
        'Bắc Ninh' => array(
            'Bắc Ninh', 'Yên Phong', 'Quế Võ', 'Tiên Du', 'Từ Sơn', 'Thuận Thành', 'Gia Bình', 'Lương Tài'
        ),
        'Bến Tre' => array(
            'Bến Tre', 'Châu Thành', 'Chợ Lách', 'Mỏ Cày Nam', 'Giồng Trôm', 'Bình Đại', 'Ba Tri', 'Thạnh Phú', 'Mỏ Cày Bắc'
        ),
        'Bình Định' => array(
            'Quy Nhon', 'An Lão', 'Kỳ Sơn', 'Tuy Phước', 'Tây Sơn', 'Phù Cát', 'Phù Mỹ',
            'Vĩnh Thạnh', 'Hoài Nhon', 'Hoài Ân', 'Vân Canh'
        ),
        'Bình Dương' => array(
            'Thủ Dầu Một', 'Bến Cát', 'Tân Uyên', 'Dĩ An', 'Thuận An', 'Phú Giáo', 'Dầu Tiếng', 'Bàu Bàng', 'Bắc Tân Uyên'
        ),
        'Bình Phước' => array(
            'Đồng Xoài', 'Bình Long', 'Bù Gia Mập', 'Lộc Ninh', 'Bù Đốp', 'Hớn Quản', 'Đồng Phú', 'Bù Đăng', 'Chơn Thành', 'Phước Long'
        ),
        'Bình Thuận' => array(
            'Phan Thiết', 'La Gi', 'Tuy Phong', 'Bắc Bình', 'Hàm Thuận Bắc', 'Hàm Thuận Nam', 'Tánh Linh', 'Đức Linh', 'Hàm Tân', 'Phú Quí'
        ),
        'Cà Mau' => array(
            'Cà Mau', 'U Minh', 'Thới Bình', 'Trần Văn Thời', 'Cái Nước', 'Đầm Dơi', 'Ngọc Hiển', 'Năm Căn', 'Phú Tân'
        ),
        'Cần Thơ' => array(
            'Ninh Kiều', 'Ô Môn', 'Bình Thuỷ', 'Cái Răng', 'Thốt Nốt', 'Vĩnh Thạnh', 'Cờ Đỏ', 'Phong Điền', 'Thới Lai'
        ),
        'Cao Bằng' => array(
            'Cao Bằng', 'Bảo Lâm', 'Bảo Lạc', 'Thông Nông', 'Hà Quảng', 'Trà Lĩnh', 'Trùng Khánh', 'Nguyên Bình', 'Hoà An', 'Quảng Uyên', 'Phục Hoà', 'Hạ Lang'
        ),
        'Đà Nẵng' => array(
            'Liên Chiểu', 'Thanh Khê', 'Hải Châu', 'Sơn Trà', 'Ngũ Hành Sơn', 'Cẩm Lệ', 'Hoà Vang', 'Hoàng Sa'
        ),
        'Đắk Lắk' => array(
            'Buôn Ma Thuột', 'Buôn Hồ', 'Ea H\'leo', 'Ea Súp', 'Buôn Đôn', 'Cư M\'gar', 'Krông Búk', 'Krông Năng', 'Ea Kar', 'M\'Đrắk', 'Krông Bông', 'Krông Pắc', 'Krông A Na', 'Lắk', 'Cư Kuin'
        ),
        'Đắk Nông' => array(
            'Gia Nghĩa', 'Đăk Mil', 'Cư Jút', 'Đăk Song', 'Đăk R\'Lấp', 'Krông Nô', 'Tuy Đức'
        ),
        'Điện Biên' => array(
            'Điện Biên Phủ', 'Mường Lay', 'Mường Nhé', 'Mường Chà', 'Tủa Chùa', 'Tuần Giáo', 'Điện Biên', 'Điện Biên Đông', 'Mường Ảng', 'Nậm Pồ'
        ),
        'Đồng Nai' => array(
            'Biên Hòa', 'Long Khánh', 'Tân Phú', 'Vĩnh Cửu', 'Định Quán', 'Trảng Bom', 'Thống Nhất', 'Cẩm Mỹ', 'Long Thành', 'Xuân Lộc', 'Nhon Trạch'
        ),
        'Đồng Tháp' => array(
            'Cao Lãnh', 'Sa Đéc', 'Hồng Ngự', 'Tân Hồng', 'Hồng Ngự', 'Tam Nông', 'Tháp Mười', 'Cao Lãnh', 'Thanh Bình', 'Lấp Vò', 'Lai Vung', 'Châu Thành'
        ),
        'Gia Lai' => array(
            'Pleiku', 'An Khê', 'Ayun Pa', 'Kông Chro', 'Đăk Đoa', 'Chư Păh', 'Ia Grai', 'Mang Yang', 'Kbang', 'Krông Pa', 'Ia Pa', 'Chư Prông', 'Chư Sê', 'Đức Cơ', 'Chư Pưh', 'Krông Chro', 'Phú Thiện'
        ),
        'Hà Giang' => array(
            'Hà Giang', 'Đồng Văn', 'Mèo Vạc', 'Yên Minh', 'Quản Bạ', 'Vị Xuyên', 'Bắc Mê', 'Hoàng Su Phì', 'Xín Mần', 'Bắc Quang', 'Quang Bình'
        ),
        'Hà Nam' => array(
            'Phủ Lý', 'Duy Tiên', 'Kim Bảng', 'Thanh Liêm', 'Bình Lục', 'Lý Nhân'
        ),
        'Hà Nội' => array(
            'Ba Đình', 'Hoàn Kiếm', 'Tây Hồ', 'Long Biên', 'Cầu Giấy', 'Đống Đa', 'Hai Bà Trưng',
            'Hoàng Mai', 'Thanh Xuân', 'Sóc Sơn', 'Đông Anh', 'Gia Lâm', 'Nam Từ Liêm', 'Bắc Từ Liêm',
            'Mê Linh', 'Hà Đông', 'Sơn Tây', 'Ba Vì', 'Phúc Thọ', 'Đan Phượng', 'Hoài Đức',
            'Quốc Oai', 'Thạch Thất', 'Chương Mỹ', 'Thanh Oai', 'Thường Tín', 'Phú Xuyên',
            'Ứng Hòa', 'Mỹ Đức'
        ),
        'Hà Tĩnh' => array(
            'Hà Tĩnh', 'Hồng Lĩnh', 'Hương Sơn', 'Đức Thọ', 'Vũ Quang', 'Nghi Xuân', 'Can Lộc', 'Hương Khê', 'Thạch Hà', 'Cẩm Xuyên', 'Kỳ Anh', 'Lộc Hà', 'Thạch Hà'
        ),
        'Hải Dương' => array(
            'Hải Dương', 'Chí Linh', 'Nam Sách', 'Kinh Môn', 'Kim Thành', 'Thanh Hà', 'Cẩm Giàng', 'Bình Giang', 'Gia Lộc', 'Tứ Kỳ', 'Ninh Giang', 'Thanh Miện'
        ),
        'Hải Phòng' => array(
            'Hồng Bàng', 'Ngô Quyền', 'Lê Chân', 'Hải An', 'Kiến An', 'Đồ Sơn', 'Dương Kinh',
            'Thuỷ Nguyên', 'An Dương', 'An Lão', 'Kiến Thuỵ', 'Tiên Lãng', 'Vĩnh Bảo', 'Cát Hải', 'Bạch Long Vĩ'
        ),
        'Hậu Giang' => array(
            'Vị Thanh', 'Ngã Bảy', 'Châu Thành A', 'Châu Thành', 'Phụng Hiệp', 'Vị Thuỷ', 'Long Mỹ'
        ),
        'Hòa Bình' => array(
            'Hòa Bình', 'Đà Bắc', 'Kỳ Sơn', 'Lương Sơn', 'Kim Bôi', 'Cao Phong', 'Tân Lạc', 'Lạc Sơn', 'Yên Thủy', 'Lạc Thuỷ', 'Mai Châu'
        ),
        'Hưng Yên' => array(
            'Hưng Yên', 'Văn Lâm', 'Văn Giang', 'Yên Mỹ', 'Mỹ Hào', 'Ân Thi', 'Khoái Châu', 'Kim Động', 'Tiên Lữ', 'Phù Cừ'
        ),
        'Khánh Hòa' => array(
            'Nha Trang', 'Cam Ranh', 'Ninh Hòa', 'Vạn Ninh', 'Khánh Vĩnh', 'Diên Khánh', 'Khánh Sơn', 'Cam Lâm', 'Trường Sa'
        ),
        'Kiên Giang' => array(
            'Rạch Giá', 'Hà Tiên', 'Kiên Lương', 'Hòn Đất', 'Tân Hiệp', 'Châu Thành', 'Giồng Riềng', 'Gò Quao', 'An Biên', 'An Minh', 'Vĩnh Thuận', 'Phú Quốc', 'Kiên Hải', 'U Minh Thượng', 'Giang Thành'
        ),
        'Kon Tum' => array(
            'Kon Tum', 'Đăk Glei', 'Ngọc Hồi', 'Đăk Tô', 'Kon Plông', 'Kon Rẫy', 'Đăk Hà', 'Sa Thầy', 'Tu Mơ Rông', 'Ia H\' Drai'
        ),
        'Lai Châu' => array(
            'Lai Châu', 'Tam Đường', 'Mường Tè', 'Sìn Hồ', 'Phong Thổ', 'Than Uyên', 'Tân Uyên', 'Nậm Nhùn'
        ),
        'Lâm Đồng' => array(
            'Đà Lạt', 'Bảo Lộc', 'Đam Rông', 'Lạc Dương', 'Lâm Hà', 'Đơn Dương', 'Đức Trọng', 'Di Linh', 'Bảo Lâm', 'Đạ Huoai', 'Đạ Tẻh', 'Cát Tiên'
        ),
        'Lạng Sơn' => array(
            'Lạng Sơn', 'Tràng Định', 'Bình Gia', 'Văn Lãng', 'Cao Lộc', 'Văn Quan', 'Bắc Sơn', 'Hữu Lũng', 'Chi Lăng', 'Lộc Bình', 'Đình Lập'
        ),
        'Lào Cai' => array(
            'Lào Cai', 'Sa Pa', 'Văn Bàn', 'Bát Xát', 'Mường Khương', 'Si Ma Cai', 'Bắc Hà', 'Bảo Thắng', 'Bảo Yên'
        ),
        'Long An' => array(
            'Tân An', 'Kiến Tường', 'Tân Hưng', 'Vĩnh Hưng', 'Mộc Hóa', 'Tân Thạnh', 'Thạnh Hóa', 'Đức Huệ', 'Đức Hòa', 'Bến Lức', 'Thủ Thừa', 'Tân Trụ', 'Cần Đước', 'Cần Giuộc', 'Châu Thành'
        ),
        'Nam Định' => array(
            'Nam Định', 'Mỹ Lộc', 'Vụ Bản', 'Ý Yên', 'Nghĩa Hưng', 'Nam Trực', 'Trực Ninh', 'Xuân Trường', 'Giao Thủy', 'Hải Hậu'
        ),
        'Nghệ An' => array(
            'Vinh', 'Cửa Lò', 'Thái Hoà', 'Quế Phong', 'Quỳ Châu', 'Kỳ Sơn', 'Tương Dương', 'Nghĩa Đàn', 'Quỳ Hợp', 'Quỳnh Lưu', 'Con Cuông', 'Tân Kỳ', 'Anh Sơn', 'Diễn Châu', 'Yên Thành', 'Đô Lương', 'Thanh Chương', 'Nghi Lộc', 'Nam Đàn', 'Hưng Nguyên'
        ),
        'Ninh Bình' => array(
            'Ninh Bình', 'Tam Điệp', 'Nho Quan', 'Gia Viễn', 'Hoa Lư', 'Yên Khánh', 'Kim Sơn', 'Yên Mô'
        ),
        'Ninh Thuận' => array(
            'Phan Rang - Tháp Chàm', 'Bác Ái', 'Ninh Sơn', 'Ninh Hải', 'Ninh Phước', 'Thuận Bắc', 'Thuận Nam'
        ),
        'Phú Thọ' => array(
            'Việt Trì', 'Phú Thọ', 'Đoan Hùng', 'Hạ Hoà', 'Thanh Ba', 'Phù Ninh', 'Yên Lập', 'Cẩm Khê', 'Tam Nông', 'Lâm Thao', 'Thanh Sơn', 'Thanh Thuỷ', 'Tân Sơn'
        ),
        'Phú Yên' => array(
            'Tuy Hoà', 'Sông Cầu', 'Đồng Xuân', 'Tuy An', 'Sơn Hòa', 'Sông Hinh', 'Tây Hoà', 'Phú Hoà', 'Đông Hòa'
        ),
        'Quảng Bình' => array(
            'Đồng Hới', 'Ba Đồn', 'Quảng Trạch', 'Bố Trạch', 'Quảng Ninh', 'Lệ Thủy', 'Tuyên Hóa', 'Minh Hóa'
        ),
        'Quảng Nam' => array(
            'Tam Kỳ', 'Hội An', 'Thăng Bình', 'Châu Đức', 'Duy Xuyên', 'Dai Lộc', 'Điện Bàn', 'Que Sơn', 'Hiệp Đức', 'Thăng Bình', 'Tiên Phước', 'Bắc Trà My', 'Nam Trà My', 'Núi Thành', 'Phú Ninh', 'Nam Giang', 'Đông Giang', 'Tây Giang'
        ),
        'Quảng Ngãi' => array(
            'Quảng Ngãi', 'Ba Tơ', 'Bình Sơn', 'Trà Bồng', 'Sơn Tịnh', 'Tư Nghĩa', 'Sơn Hà', 'Sơn Tây', 'Minh Long', 'Nghĩa Hành', 'Mộ Đức', 'Đức Phổ', 'Lý Sơn'
        ),
        'Quảng Ninh' => array(
            'Hạ Long', 'Móng Cái', 'Cẩm Phả', 'Uông Bí', 'Đông Triều', 'Quảng Yên', 'Cô Tô', 'Tiên Yên', 'Đầm Hà', 'Hải Hà', 'Ba Chẽ', 'Vân Đồn', 'Hoành Bồ'
        ),
        'Quảng Trị' => array(
            'Đông Hà', 'Quảng Trị', 'Vĩnh Linh', 'Hướng Hóa', 'Gio Linh', 'Đa Krông', 'Cam Lộ', 'Triệu Phong', 'Hải Lăng', 'Cồn Cỏ'
        ),
        'Sóc Trăng' => array(
            'Sóc Trăng', 'Châu Thành', 'Kế Sách', 'Mỹ Tú', 'Cù Lao Dung', 'Long Phú', 'Mỹ Xuyên', 'Ngã Năm', 'Thạnh Trị', 'Vĩnh Châu', 'Trần Đề'
        ),
        'Sơn La' => array(
            'Sơn La', 'Quỳnh Nhai', 'Thuận Châu', 'Mường La', 'Bắc Yên', 'Phù Yên', 'Mộc Châu', 'Yên Châu', 'Mai Sơn', 'Song Mã', 'Sông Mã', 'Sốp Cộp', 'Vân Hồ'
        ),
        'Tây Ninh' => array(
            'Tây Ninh', 'Trảng Bàng', 'Tân Biên', 'Tân Châu', 'Dương Minh Châu', 'Châu Thành', 'Hòa Thành', 'Gò Dầu', 'Bến Cầu'
        ),
        'Thái Bình' => array(
            'Thái Bình', 'Quỳnh Phụ', 'Hưng Hà', 'Đông Hưng', 'Thái Thụy', 'Tiền Hải', 'Kiến Xương', 'Vũ Thư'
        ),
        'Thái Nguyên' => array(
            'Thái Nguyên', 'Sông Công', 'Định Hóa', 'Phú Lương', 'Đồng Hỷ', 'Võ Nhai', 'Đại Từ', 'Phú Bình', 'Phổ Yên'
        ),
        'Thanh Hóa' => array(
            'Thanh Hóa', 'Bỉm Sơn', 'Sầm Sơn', 'Mường Lát', 'Quan Hóa', 'Bá Thước', 'Quan Sơn', 'Lang Chánh', 'Ngọc Lặc', 'Cẩm Thủy', 'Thạch Thành', 'Hà Trung', 'Vĩnh Lộc', 'Yên Định', 'Thọ Xuân', 'Thường Xuân', 'Triệu Sơn', 'Thiệu Hóa', 'Hoằng Hóa', 'Hậu Lộc', 'Nga Sơn', 'Like', 'Nông Cống', 'Đông Sơn', 'Quảng Xương', 'Tĩnh Gia'
        ),
        'Thừa Thiên Huế' => array(
            'Huế', 'Phong Điền', 'Quảng Điền', 'Phú Vang', 'Hương Thủy', 'Hương Trà', 'A Lưới', 'Phú Lộc', 'Nam Đông'
        ),
        'Tiền Giang' => array(
            'Mỹ Tho', 'Gò Công', 'Cai Lậy', 'Tân Phước', 'Châu Thành', 'Chợ Gạo', 'Gò Công Tây', 'Gò Công Đông', 'Tân Phú Đông', 'Cái Bè'
        ),
        'TP Hồ Chí Minh' => array(
            'Quận 1', 'Quận 3', 'Quận 4', 'Quận 5', 'Quận 6', 'Quận 7', 'Quận 8', 'Quận 10', 'Quận 11', 'Quận 12',
            'Quận Bình Thạnh', 'Quận Gò Vấp', 'Quận Phú Nhuận', 'Quận Tân Bình', 'Quận Tân Phú', 'Quận Bình Tân',
            'Thủ Đức', 'Hóc Môn', 'Củ Chi', 'Bình Chánh', 'Nhà Bè', 'Cần Giờ'
        ),
        'Trà Vinh' => array(
            'Trà Vinh', 'Càng Long', 'Cầu Kè', 'Tiểu Cần', 'Châu Thành', 'Cầu Ngang', 'Trà Cú', 'Duyên Hải', 'Duyên Hải'
        ),
        'Tuyên Quang' => array(
            'Tuyên Quang', 'Nà Hang', 'Chiêm Hóa', 'Hàm Yên', 'Yên Sơn', 'Sơn Dương'
        ),
        'Vĩnh Long' => array(
            'Vĩnh Long', 'Long Hồ', 'Mang Thít', 'Vũng Liêm', 'Tam Bình', 'Trà Ôn', 'Bình Minh', 'Bình Tân'
        ),
        'Vĩnh Phúc' => array(
            'Vĩnh Yên', 'Phúc Yên', 'Lập Thạch', 'Tam Dương', 'Tam Đảo', 'Bình Xuyên', 'Yên Lạc', 'Vĩnh Tường', 'Sông Lô'
        ),
        'Yên Bái' => array(
            'Yên Bái', 'Nghĩa Lộ', 'Lục Yên', 'Văn Yên', 'Mù Căng Chải', 'Trấn Yên', 'Trạm Tấu', 'Văn Chấn', 'Yên Bình'
        )
    );
}