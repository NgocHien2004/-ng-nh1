<?php
/**
 * Single Product Template
 *
 * @package PhoneStore
 */

defined( 'ABSPATH' ) || exit;

get_header(); ?>

<div class="container">
    <?php while ( have_posts() ) : ?>
        <?php the_post(); ?>
        
        <?php
        global $product;
        $product_id = get_the_ID();
        ?>
        
        <div class="single-product-container">
            <!-- Breadcrumb -->
            <nav class="product-breadcrumb">
                <a href="<?php echo home_url(); ?>">🏠 Trang chủ</a>
                <span>/</span>
                <a href="<?php echo get_permalink(wc_get_page_id('shop')); ?>">📱 Sản phẩm</a>
                <span>/</span>
                <span><?php the_title(); ?></span>
            </nav>
            
<div class="product-main">
    <!-- Product Gallery -->
    <div class="product-gallery">
        <div class="main-image-container">
            <div class="main-image">
                <?php
                $attachment_ids = $product->get_gallery_image_ids();
                $main_image = get_post_thumbnail_id();
                
                if ($main_image) {
                    echo '<img id="main-product-image" src="' . wp_get_attachment_image_url($main_image, 'large') . '" alt="' . get_the_title() . '">';
                } else {
                    echo '<img id="main-product-image" src="' . wc_placeholder_img_src('large') . '" alt="No Image">';
                }
                ?>
                
                <?php if (count($attachment_ids) > 0 || $main_image): ?>
                <button class="gallery-nav prev-btn" id="prev-image">❮</button>
                <button class="gallery-nav next-btn" id="next-image">❯</button>
                <?php endif; ?>
            </div>
            
            <!-- Thumbnail Gallery -->
            <?php if (count($attachment_ids) > 0 || $main_image): ?>
            <div class="thumbnail-gallery">
                <?php if ($main_image): ?>
                    <img class="thumbnail active" 
                         src="<?php echo wp_get_attachment_image_url($main_image, 'thumbnail'); ?>" 
                         data-large="<?php echo wp_get_attachment_image_url($main_image, 'large'); ?>"
                         alt="<?php echo get_the_title(); ?>">
                <?php endif; ?>
                
                <?php foreach ($attachment_ids as $attachment_id): ?>
                    <img class="thumbnail" 
                         src="<?php echo wp_get_attachment_image_url($attachment_id, 'thumbnail'); ?>" 
                         data-large="<?php echo wp_get_attachment_image_url($attachment_id, 'large'); ?>"
                         alt="<?php echo get_the_title(); ?>">
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Product Actions - Moved here under gallery -->
        <div class="product-actions">
            <div class="quantity-selector">
                <label>Số lượng:</label>
                <div class="quantity-input">
                    <button type="button" class="qty-btn minus">-</button>
                    <input type="number" name="quantity" value="1" min="1" max="10">
                    <button type="button" class="qty-btn plus">+</button>
                </div>
            </div>
            
            <div class="action-buttons">
                <button class="add-to-cart-btn" data-product-id="<?php echo $product_id; ?>">
                    🛒 Thêm vào giỏ hàng
                </button>
                <button class="buy-now-btn" data-product-id="<?php echo $product_id; ?>">
                    ⚡ Mua ngay
                </button>
                <button class="compare-btn" data-product-id="<?php echo $product_id; ?>">
                    ⚖️ So sánh
                </button>
                <button class="wishlist-btn" data-product-id="<?php echo $product_id; ?>">
                    ❤️ Yêu thích
                </button>
            </div>
        </div>
        
        <!-- Additional Info -->
        <div class="additional-info">
            <div class="info-item">
                <span class="icon">🚚</span>
                <div class="info-text">
                    <strong>Miễn phí giao hàng</strong>
                    <small>Giao hàng miễn phí trong nội thành</small>
                </div>
            </div>
            <div class="info-item">
                <span class="icon">🔄</span>
                <div class="info-text">
                    <strong>Đổi trả 7 ngày</strong>
                    <small>Miễn phí đổi trả trong 7 ngày</small>
                </div>
            </div>
            <div class="info-item">
                <span class="icon">🛡️</span>
                <div class="info-text">
                    <strong>Bảo hành chính hãng</strong>
                    <small><?php echo $product->is_virtual() ? 'Bảo hành phần mềm' : 'Bảo hành 12-24 tháng'; ?></small>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Product Info - Right side -->
    <div class="product-info">
        <h1 class="product-title"><?php the_title(); ?></h1>
        
        <div class="product-price">
            <?php echo $product->get_price_html(); ?>
        </div>
        
        <div class="product-rating">
            <div class="stars">⭐⭐⭐⭐⭐</div>
            <span class="review-count">(<?php echo rand(10, 100); ?> đánh giá)</span>
        </div>
        
        <div class="product-short-description">
            <?php echo apply_filters('woocommerce_short_description', $product->get_short_description()); ?>
        </div>
        
<!-- Product Specs với nhóm attributes đầy đủ -->
        <div class="product-specs">
            <h3>📋 Thông số kỹ thuật</h3>
            
            <!-- Hiển thị specs rút gọn ban đầu -->
            <div class="specs-container specs-preview">
                <?php
                // Chỉ hiển thị 3 nhóm quan trọng nhất
                $preview_groups = array(
                    'basic' => '🏷️ Thông tin cơ bản',
                    'display' => '📺 Màn hình & Hiển thị', 
                    'performance' => '⚡ Hiệu năng & Hệ thống'
                );
                
                // Định nghĩa mapping cho preview
                $preview_attribute_mapping = array(
                    // Thông tin cơ bản
                    'thuong-hieu-brand' => array('group' => 'basic', 'label' => '🏷️ Thương hiệu'),
                    'hang' => array('group' => 'basic', 'label' => '🏭 Hãng'),
                    'thiet-ke' => array('group' => 'basic', 'label' => '🎨 Thiết kế'),
                    'thoi-diem-ra-mat' => array('group' => 'basic', 'label' => '📅 Thời điểm ra mắt'),
                    'xem-phim' => array('group' => 'basic', 'label' => '🎬 Xem phim'),
                    
                    // Màn hình & Hiển thị
                    'kich-thuoc-man-hinh' => array('group' => 'display', 'label' => '📺 Kích thước màn hình'),
                    'cong-nghe-man-hinh' => array('group' => 'display', 'label' => '💻 Công nghệ màn hình'),
                    'do-phan-giai-man-hinh' => array('group' => 'display', 'label' => '🔍 Độ phân giải màn hình'),
                    'do-sang-toi-da' => array('group' => 'display', 'label' => '☀️ Độ sáng tối đa'),
                    'mat-kinh-cam-ung' => array('group' => 'display', 'label' => '👆 Mặt kính cảm ứng'),
                    
                    // Hiệu năng & Hệ thống
                    'chip-xu-ly-cpu' => array('group' => 'performance', 'label' => '⚡ Chip xử lý (CPU)'),
                    'chip-do-hoa-gpu' => array('group' => 'performance', 'label' => '🎮 Chip đồ họa (GPU)'),
                    'ram' => array('group' => 'performance', 'label' => '💾 RAM'),
                    'bo-nho-storage' => array('group' => 'performance', 'label' => '💿 Bộ nhớ trong'),
                    'he-dieu-hanh' => array('group' => 'performance', 'label' => '🖥️ Hệ điều hành'),
                    'toc-do-cpu' => array('group' => 'performance', 'label' => '🚀 Tốc độ CPU')
                );
                
                // Lấy tất cả specs
                $all_specs = array();
                
                // Lấy WooCommerce attributes
                $wc_attributes = $product->get_attributes();
                foreach ($wc_attributes as $attribute) {
                    $attribute_name = $attribute->get_name();
                    $clean_name = str_replace('pa_', '', $attribute_name);
                    
                    if ($attribute->is_taxonomy()) {
                        $values = wc_get_product_terms($product_id, $attribute->get_name(), array('fields' => 'names'));
                        $value = implode(', ', $values);
                    } else {
                        $value = $attribute->get_options();
                        if (is_array($value)) {
                            $value = implode(', ', $value);
                        }
                    }
                    if (!empty($value)) {
                        $all_specs[$clean_name] = $value;
                    }
                }
                
                // Lấy custom meta fields
                $meta_keys = get_post_meta($product_id);
                foreach ($meta_keys as $key => $values) {
                    if (strpos($key, '_') !== 0 && !empty($values[0])) {
                        $all_specs[$key] = $values[0];
                    }
                }
                
                // Nhóm specs theo group cho preview
                $preview_grouped_specs = array();
                foreach ($all_specs as $key => $value) {
                    if (isset($preview_attribute_mapping[$key])) {
                        $group = $preview_attribute_mapping[$key]['group'];
                        $label = $preview_attribute_mapping[$key]['label'];
                        $preview_grouped_specs[$group][] = array('label' => $label, 'value' => $value);
                    }
                }
                
                // Hiển thị 3 nhóm preview
                $has_any_specs = false;
                foreach ($preview_groups as $group_key => $group_title) {
                    if (isset($preview_grouped_specs[$group_key]) && !empty($preview_grouped_specs[$group_key])) {
                        $has_any_specs = true;
                        echo '<div class="spec-group">';
                        echo '<h4 class="spec-group-title">' . esc_html($group_title) . '</h4>';
                        echo '<div class="spec-group-content">';
                        
                        // Hiển thị tối đa 4 specs mỗi nhóm trong preview
                        $count = 0;
                        foreach ($preview_grouped_specs[$group_key] as $spec) {
                            if ($count >= 4) break;
                            
                            echo '<div class="spec-item">';
                            echo '<span class="spec-label">' . esc_html($spec['label']) . ':</span>';
                            echo '<span class="spec-value">' . esc_html($spec['value']) . '</span>';
                            echo '</div>';
                            $count++;
                        }
                        
                        echo '</div></div>';
                    }
                }
                
                // Nếu không có specs, hiển thị thông báo
                if (!$has_any_specs) {
                    echo '<div class="spec-group">';
                    echo '<div class="spec-item">';
                    echo '<span class="spec-label">📋 Thông số:</span>';
                    echo '<span class="spec-value">Đang cập nhật thông tin chi tiết</span>';
                    echo '</div>';
                    echo '</div>';
                }
                ?>
            </div>
            
            <!-- Nút xem chi tiết -->
            <div class="specs-toggle">
                <button id="toggle-specs-btn" class="toggle-specs-btn">
                    📖 Xem thông tin chi tiết
                </button>
            </div>
        </div>

        <!-- Modal hiển thị đầy đủ specs -->
        <div id="specs-modal" class="specs-modal" style="display: none;">
            <div class="specs-modal-content">
                <div class="specs-modal-header">
                    <h2>📋 Thông số kỹ thuật chi tiết - <?php echo get_the_title(); ?></h2>
                    <button class="specs-modal-close">&times;</button>
                </div>
                
                <div class="specs-modal-body">
                    <div class="specs-table-container">
                        <?php
                        // Định nghĩa mapping đầy đủ cho modal
                        $full_attribute_mapping = array(
                            // Thông tin cơ bản
                            'thuong-hieu-brand' => array('group' => 'basic', 'label' => 'Thương hiệu'),
                            'hang' => array('group' => 'basic', 'label' => 'Hãng'),
                            'thiet-ke' => array('group' => 'basic', 'label' => 'Thiết kế'),
                            'khoang-gia' => array('group' => 'basic', 'label' => 'Khoảng giá'),
                            'thoi-diem-ra-mat' => array('group' => 'basic', 'label' => 'Thời điểm ra mắt'),
                            'xem-phim' => array('group' => 'basic', 'label' => 'Xem phim'),
                            
                            // Màn hình & Hiển thị
                            'kich-thuoc-man-hinh' => array('group' => 'display', 'label' => 'Kích thước màn hình'),
                            'do-phan-giai-man-hinh' => array('group' => 'display', 'label' => 'Độ phân giải màn hình'),
                            'cong-nghe-man-hinh' => array('group' => 'display', 'label' => 'Công nghệ màn hình'),
                            'do-sang-toi-da' => array('group' => 'display', 'label' => 'Độ sáng tối đa'),
                            'mat-kinh-cam-ung' => array('group' => 'display', 'label' => 'Mặt kính cảm ứng'),
                            
                            // Hiệu năng & Hệ thống
                            'chip-xu-ly-cpu' => array('group' => 'performance', 'label' => 'Chip xử lý (CPU)'),
                            'chip-do-hoa-gpu' => array('group' => 'performance', 'label' => 'Chip đồ họa (GPU)'),
                            'ram' => array('group' => 'performance', 'label' => 'RAM'),
                            'bo-nho-storage' => array('group' => 'performance', 'label' => 'Bộ nhớ trong'),
                            'he-dieu-hanh' => array('group' => 'performance', 'label' => 'Hệ điều hành'),
                            'toc-do-cpu' => array('group' => 'performance', 'label' => 'Tốc độ CPU'),
                            'vi-xu-ly' => array('group' => 'performance', 'label' => 'Vi xử lý'),
                            
                            // Camera & Chụp ảnh
                            'camera' => array('group' => 'camera', 'label' => 'Camera chính'),
                            'do-phan-giai-camera-sau' => array('group' => 'camera', 'label' => 'Độ phân giải camera sau'),
                            'do-phan-giai-camera-truoc' => array('group' => 'camera', 'label' => 'Độ phân giải camera trước'),
                            'tinh-nang-camera-sau' => array('group' => 'camera', 'label' => 'Tính năng camera sau'),
                            'tinh-nang-camera-truoc' => array('group' => 'camera', 'label' => 'Tính năng camera trước'),
                            'quay-phim-camera-sau' => array('group' => 'camera', 'label' => 'Quay phim camera sau'),
                            'tinh-nang-dac-biet' => array('group' => 'camera', 'label' => 'Tính năng đặc biệt'),
                            
                            // Kết nối & Mạng
                            'sim' => array('group' => 'connectivity', 'label' => 'SIM'),
                            'bluetooth' => array('group' => 'connectivity', 'label' => 'Bluetooth'),
                            'wifi' => array('group' => 'connectivity', 'label' => 'Wi-Fi'),
                            'gps' => array('group' => 'connectivity', 'label' => 'GPS'),
                            'ket-noi-khac' => array('group' => 'connectivity', 'label' => 'Kết nối khác'),
                            'mang-di-dong' => array('group' => 'connectivity', 'label' => 'Mạng di động'),
                            'jack-tai-nghe' => array('group' => 'connectivity', 'label' => 'Jack tai nghe'),
                            'cong-ket-noi-sac' => array('group' => 'connectivity', 'label' => 'Cổng kết nối/sạc'),
                            
                            // Pin & Sạc
                            'pin' => array('group' => 'battery', 'label' => 'Pin'),
                            'dung-luong-pin' => array('group' => 'battery', 'label' => 'Dung lượng pin'),
                            'loai-pin' => array('group' => 'battery', 'label' => 'Loại pin'),
                            'cong-nghe-pin' => array('group' => 'battery', 'label' => 'Công nghệ pin'),
                            'ho-tro-sac-toi-da' => array('group' => 'battery', 'label' => 'Hỗ trợ sạc tối đa'),
                            
                            // Thiết kế & Vật liệu
                            'kich-thuoc-khoi-luong' => array('group' => 'design', 'label' => 'Kích thước, khối lượng'),
                            'chat-lieu' => array('group' => 'design', 'label' => 'Chất liệu'),
                            'kháng-nuoc-bui' => array('group' => 'design', 'label' => 'Kháng nước, bụi'),
                            'bao-mat-nang-cao' => array('group' => 'design', 'label' => 'Bảo mật nâng cao'),
                            
                            // Âm thanh & Giải trí
                            'nghe-nhac' => array('group' => 'multimedia', 'label' => 'Nghe nhạc')
                        );
                        
                        // Nhóm hiển thị trong modal
                        $modal_display_groups = array(
                            'basic' => '🏷️ Thông tin cơ bản',
                            'display' => '📺 Màn hình & Hiển thị', 
                            'performance' => '⚡ Hiệu năng & Hệ thống',
                            'camera' => '📷 Camera & Chụp ảnh',
                            'connectivity' => '🌐 Kết nối & Mạng',
                            'battery' => '🔋 Pin & Sạc',
                            'design' => '🎨 Thiết kế & Vật liệu',
                            'multimedia' => '🎵 Âm thanh & Giải trí'
                        );
                        
                        // Nhóm specs theo group cho modal
                        $modal_grouped_specs = array();
                        $ungrouped_specs = array();
                        
                        foreach ($all_specs as $key => $value) {
                            if (isset($full_attribute_mapping[$key])) {
                                $group = $full_attribute_mapping[$key]['group'];
                                $label = $full_attribute_mapping[$key]['label'];
                                $modal_grouped_specs[$group][] = array('label' => $label, 'value' => $value);
                            } else {
                                // Specs không có trong mapping
                                $label = ucwords(str_replace(array('-', '_'), ' ', $key));
                                $ungrouped_specs[] = array('label' => $label, 'value' => $value);
                            }
                        }
                        
                        // Hiển thị từng nhóm trong modal dưới dạng bảng
                        foreach ($modal_display_groups as $group_key => $group_title) {
                            if (isset($modal_grouped_specs[$group_key]) && !empty($modal_grouped_specs[$group_key])) {
                                echo '<div class="specs-group-section">';
                                echo '<h3 class="specs-group-header">' . esc_html($group_title) . '</h3>';
                                echo '<table class="specs-table">';
                                
                                foreach ($modal_grouped_specs[$group_key] as $spec) {
                                    // Xử lý xuống dòng cho các giá trị có dấu phẩy
                                    $formatted_value = $spec['value'];
                                    if (strpos($formatted_value, ',') !== false) {
                                        $parts = array_map('trim', explode(',', $formatted_value));
                                        $formatted_value = '<ul class="spec-list">';
                                        foreach ($parts as $part) {
                                            if (!empty($part)) {
                                                $formatted_value .= '<li>' . esc_html($part) . '</li>';
                                            }
                                        }
                                        $formatted_value .= '</ul>';
                                    } else {
                                        $formatted_value = esc_html($formatted_value);
                                    }
                                    
                                    echo '<tr>';
                                    echo '<td class="spec-label-col">' . esc_html($spec['label']) . '</td>';
                                    echo '<td class="spec-value-col">' . $formatted_value . '</td>';
                                    echo '</tr>';
                                }
                                
                                echo '</table>';
                                echo '</div>';
                            }
                        }
                        
                        // Hiển thị specs không có nhóm
                        if (!empty($ungrouped_specs)) {
                            echo '<div class="specs-group-section">';
                            echo '<h3 class="specs-group-header">📋 Thông số khác</h3>';
                            echo '<table class="specs-table">';
                            
                            foreach ($ungrouped_specs as $spec) {
                                $formatted_value = $spec['value'];
                                if (strpos($formatted_value, ',') !== false) {
                                    $parts = array_map('trim', explode(',', $formatted_value));
                                    $formatted_value = '<ul class="spec-list">';
                                    foreach ($parts as $part) {
                                        if (!empty($part)) {
                                            $formatted_value .= '<li>' . esc_html($part) . '</li>';
                                        }
                                    }
                                    $formatted_value .= '</ul>';
                                } else {
                                    $formatted_value = esc_html($formatted_value);
                                }
                                
                                echo '<tr>';
                                echo '<td class="spec-label-col">' . esc_html($spec['label']) . '</td>';
                                echo '<td class="spec-value-col">' . $formatted_value . '</td>';
                                echo '</tr>';
                            }
                            
                            echo '</table>';
                            echo '</div>';
                        }
                        ?>
                    </div>
                </div>
                
                <div class="specs-modal-footer">
                    <button class="specs-modal-close-btn">📕 Đóng</button>
                </div>
            </div>
        </div>
            
            <!-- Product Description -->
            <div class="product-description">
                <h3>📝 Mô tả sản phẩm</h3>
                <div class="description-content">
                    <?php 
                    $description = $product->get_description();
                    if ($description) {
                        echo apply_filters('the_content', $description);
                    } else {
                        echo '<p>Đây là một sản phẩm điện thoại chính hãng với chất lượng cao và tính năng vượt trội. Sản phẩm được bảo hành chính thức và có đầy đủ phụ kiện theo hộp.</p>';
                    }
                    ?>
                </div>
            </div>
            
              <div class="related-products-section">
            <div class="related-products">
                <h3>📱 Sản phẩm liên quan</h3>
                <div class="related-slider-container">
                    <button class="related-nav related-prev" id="related-prev">❮</button>
                    <div class="related-slider" id="related-slider">
                        <?php
                        $related_products = wc_get_products(array(
                            'limit' => 8,
                            'exclude' => array($product_id),
                            'orderby' => 'rand'
                        ));
                        
                        foreach ($related_products as $related_product):
                        ?>
                            <div class="related-item">
                                <a href="<?php echo get_permalink($related_product->get_id()); ?>">
                                    <div class="related-image">
                                        <?php echo $related_product->get_image('medium'); ?>
                                    </div>
                                    <div class="related-info">
                                        <h4><?php echo $related_product->get_name(); ?></h4>
                                        <div class="related-price"><?php echo $related_product->get_price_html(); ?></div>
                                        <div class="related-rating">
                                            <div class="stars">⭐⭐⭐⭐⭐</div>
                                            <span>(<?php echo rand(10, 50); ?>)</span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button class="related-nav related-next" id="related-next">❯</button>
                </div>
            </div>
        </div>
        
    <?php endwhile; ?>
</div>

<style>
/* Single Product Styles */
.single-product-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 20px;
}

.product-breadcrumb {
    background: #f8fafc;
    padding: 15px 20px;
    border-radius: 10px;
    margin-bottom: 30px;
    font-size: 14px;
}

.product-breadcrumb a {
    color: #667eea;
    text-decoration: none;
}

.product-breadcrumb a:hover {
    text-decoration: underline;
}

.product-breadcrumb span {
    margin: 0 10px;
    color: #718096;
}

.product-main {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 50px;
    margin-bottom: 50px;
}

/* Product Gallery */
.product-gallery {
    position: sticky;
    top: 100px;
}

.main-image-container {
    background: white;
    border-radius: 20px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    overflow: hidden;
}

.main-image {
    position: relative;
    height: 500px;
    background: #f8fafc;
    display: flex;
    align-items: center;
    justify-content: center;
}

.main-image img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    transition: transform 0.3s;
    padding: 20px;
}

.gallery-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(0,0,0,0.5);
    color: white;
    border: none;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    cursor: pointer;
    font-size: 18px;
    font-weight: bold;
    transition: all 0.3s;
    z-index: 10;
}

.gallery-nav:hover {
    background: rgba(0,0,0,0.8);
    transform: translateY(-50%) scale(1.1);
}

.prev-btn {
    left: 20px;
}

.next-btn {
    right: 20px;
}

.thumbnail-gallery {
    display: flex;
    gap: 10px;
    padding: 20px;
    overflow-x: auto;
}

.thumbnail {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 10px;
    cursor: pointer;
    opacity: 0.6;
    transition: all 0.3s;
    border: 2px solid transparent;
}

.thumbnail:hover,
.thumbnail.active {
    opacity: 1;
    border-color: #667eea;
    transform: scale(1.05);
}

/* Product Info */
.product-info {
    background: white;
    padding: 40px;
    border-radius: 20px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.product-title {
    font-size: 2.5rem;
    color: #2d3748;
    margin-bottom: 20px;
    font-weight: 800;
    line-height: 1.2;
}

.product-price {
    font-size: 2rem;
    color: #e53e3e;
    font-weight: 800;
    margin-bottom: 20px;
}

.product-rating {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 25px;
}

.stars {
    color: #fbbf24;
    font-size: 18px;
}

.review-count {
    color: #718096;
    font-size: 14px;
}

.product-short-description {
    color: #4a5568;
    line-height: 1.6;
    margin-bottom: 30px;
    font-size: 16px;
}

.product-specs h3 {
    color: #2d3748;
    margin-bottom: 20px;
    font-size: 1.3rem;
}

.specs-grid {
    display: grid;
    gap: 15px;
    margin-bottom: 30px;
}

.spec-item {
    display: flex;
    justify-content: space-between;
    padding: 12px 15px;
    background: #f8fafc;
    border-radius: 8px;
    border-left: 4px solid #e2e8f0;
}

.spec-label {
    font-weight: 600;
    color: #4a5568;
}

.spec-value {
    color: #2d3748;
    font-weight: 500;
}

/* Product Actions */
.product-actions {
    margin-bottom: 30px;
}

.quantity-selector {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 20px;
}

.quantity-selector label {
    font-weight: 600;
    color: #4a5568;
}

.quantity-input {
    display: flex;
    align-items: center;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    overflow: hidden;
}

.qty-btn {
    background: #f8fafc;
    border: none;
    width: 40px;
    height: 40px;
    cursor: pointer;
    font-size: 16px;
    font-weight: bold;
    transition: background 0.3s;
}

.qty-btn:hover {
    background: #e2e8f0;
}

.quantity-input input {
    width: 60px;
    height: 40px;
    text-align: center;
    border: none;
    font-size: 16px;
    font-weight: 600;
}

.action-buttons {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

.add-to-cart-btn,
.buy-now-btn,
.compare-btn,
.wishlist-btn {
    padding: 15px 20px;
    border: none;
    border-radius: 10px;
    font-size: 16px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.add-to-cart-btn {
    background: linear-gradient(135deg, #38a169 0%, #2f855a 100%);
    color: white;
    box-shadow: 0 4px 15px rgba(56, 161, 105, 0.3);
}

.add-to-cart-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(56, 161, 105, 0.4);
}

.buy-now-btn {
    background: linear-gradient(135deg, #e53e3e 0%, #c53030 100%);
    color: white;
    box-shadow: 0 4px 15px rgba(229, 62, 62, 0.3);
}

.buy-now-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(229, 62, 62, 0.4);
}

.compare-btn {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.compare-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
}

.wishlist-btn {
    background: linear-gradient(135deg, #ed64a6 0%, #d53f8c 100%);
    color: white;
    box-shadow: 0 4px 15px rgba(237, 100, 166, 0.3);
}

.wishlist-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(237, 100, 166, 0.4);
}

/* Additional Info */
.additional-info {
    display: grid;
    gap: 15px;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px;
    background: #f8fafc;
    border-radius: 10px;
    border-left: 4px solid #38a169;
}

.info-item .icon {
    font-size: 24px;
    min-width: 30px;
}

.info-text strong {
    display: block;
    color: #2d3748;
    margin-bottom: 2px;
}

.info-text small {
    color: #718096;
    font-size: 12px;
}

/* Product Description */
.product-description {
    background: white;
    padding: 40px;
    border-radius: 20px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    margin-bottom: 50px;
}

.product-description h3 {
    color: #2d3748;
    margin-bottom: 25px;
    font-size: 1.5rem;
}

.description-content {
    color: #4a5568;
    line-height: 1.8;
    font-size: 16px;
}

/* Related Products */
.related-products {
    background: white;
    padding: 40px;
    border-radius: 20px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.related-products h3 {
    color: #2d3748;
    margin-bottom: 30px;
    font-size: 1.5rem;
    text-align: center;
}

.related-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 25px;
}

.related-item {
    background: #f8fafc;
    border-radius: 15px;
    padding: 20px;
    text-align: center;
    transition: transform 0.3s;
}

.related-item:hover {
    transform: translateY(-5px);
}

.related-item a {
    text-decoration: none;
    color: inherit;
}

.related-item img {
    width: 100%;
    height: 150px;
    object-fit: contain;
    margin-bottom: 15px;
    border-radius: 10px;
}

.related-item h4 {
    font-size: 14px;
    color: #2d3748;
    margin-bottom: 10px;
    line-height: 1.3;
}

.related-item .price {
    color: #e53e3e;
    font-weight: 700;
    font-size: 16px;
}

/* Compare notification */
.compare-notification {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 15px 20px;
    border-radius: 25px;
    font-weight: 600;
    z-index: 1000;
    cursor: pointer;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    transition: all 0.3s;
}

.compare-notification:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
}

/* Responsive Design */
@media (max-width: 1200px) {
    .product-main {
        grid-template-columns: 1fr;
        gap: 30px;
    }
    
    .product-gallery {
        position: static;
    }
    
    .related-grid {
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
    }
}

@media (max-width: 768px) {
    .single-product-container {
        padding: 15px;
    }
    
    .product-info {
        padding: 25px;
    }
    
    .product-title {
        font-size: 2rem;
    }
    
    .product-price {
        font-size: 1.5rem;
    }
    
    .main-image {
        height: 350px;
    }
    
    .action-buttons {
        grid-template-columns: 1fr;
        gap: 10px;
    }
    
    .quantity-selector {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
    
    .related-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .gallery-nav {
        width: 40px;
        height: 40px;
        font-size: 14px;
    }
    
    .prev-btn {
        left: 10px;
    }
    
    .next-btn {
        right: 10px;
    }
}

@media (max-width: 480px) {
    .product-main {
        gap: 20px;
    }
    
    .product-info {
        padding: 20px;
    }
    
    .product-title {
        font-size: 1.5rem;
    }
    
    .main-image {
        height: 280px;
    }
    
    .thumbnail {
        width: 60px;
        height: 60px;
    }
    
    .related-grid {
        grid-template-columns: 1fr;
    }
    
    .specs-grid {
        gap: 10px;
    }
    
    .spec-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 5px;
    }
}

/* Animation */
.single-product-container {
    animation: fadeInUp 0.6s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Spec Groups */
.specs-container {
    display: grid;
    gap: 25px;
}

.spec-group {
    background: #f8fafc;
    border-radius: 12px;
    padding: 20px;
    border-left: 4px solid #667eea;
}

.spec-group-title {
    color: #2d3748;
    font-size: 1.1rem;
    font-weight: 700;
    margin: 0 0 15px 0;
    padding-bottom: 8px;
    border-bottom: 2px solid #e2e8f0;
}

.spec-group-content {
    display: grid;
    gap: 10px;
}

.spec-item {
    display: grid;
    grid-template-columns: 1fr 2fr;
    gap: 15px;
    padding: 8px 0;
    border-bottom: 1px solid #e2e8f0;
}

.spec-item:last-child {
    border-bottom: none;
}

.spec-label {
    font-weight: 600;
    color: #4a5568;
    font-size: 14px;
}

.spec-value {
    color: #2d3748;
    font-weight: 500;
    font-size: 14px;
    word-wrap: break-word;
}

/* Responsive cho spec groups */
@media (max-width: 768px) {
    .spec-item {
        grid-template-columns: 1fr;
        gap: 5px;
    }
    
    .spec-label {
        font-weight: 700;
    }
}

/* Layout chính */
.product-main {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 50px;
    margin-bottom: 50px;
}

/* Gallery bên trái */
.product-gallery {
    position: sticky;
    top: 100px;
}

/* Actions moved under gallery */
.product-actions {
    margin-top: 30px;
    background: white;
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

.quantity-selector {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 20px;
    justify-content: center;
}

.quantity-selector label {
    font-weight: 600;
    color: #4a5568;
    font-size: 16px;
}

.quantity-input {
    display: flex;
    align-items: center;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    overflow: hidden;
}

.qty-btn {
    background: #f8fafc;
    border: none;
    width: 40px;
    height: 40px;
    cursor: pointer;
    font-size: 16px;
    font-weight: bold;
    transition: background 0.3s;
}

.qty-btn:hover {
    background: #e2e8f0;
}

.quantity-input input {
    width: 60px;
    height: 40px;
    text-align: center;
    border: none;
    font-size: 16px;
    font-weight: 600;
}

.action-buttons {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
}

.add-to-cart-btn,
.buy-now-btn,
.compare-btn,
.wishlist-btn {
    padding: 12px 16px;
    border: none;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    min-height: 44px;
}

.add-to-cart-btn {
    background: linear-gradient(135deg, #38a169 0%, #2f855a 100%);
    color: white;
    box-shadow: 0 4px 15px rgba(56, 161, 105, 0.3);
}

.add-to-cart-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(56, 161, 105, 0.4);
}

.buy-now-btn {
    background: linear-gradient(135deg, #e53e3e 0%, #c53030 100%);
    color: white;
    box-shadow: 0 4px 15px rgba(229, 62, 62, 0.3);
}

.buy-now-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(229, 62, 62, 0.4);
}

.compare-btn {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.compare-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
}

.wishlist-btn {
    background: linear-gradient(135deg, #ed64a6 0%, #d53f8c 100%);
    color: white;
    box-shadow: 0 4px 15px rgba(237, 100, 166, 0.3);
}

.wishlist-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(237, 100, 166, 0.4);
}

/* Additional Info under actions */
.additional-info {
    margin-top: 20px;
    display: grid;
    gap: 12px;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    background: #f8fafc;
    border-radius: 8px;
    border-left: 4px solid #38a169;
}

.info-item .icon {
    font-size: 20px;
    min-width: 24px;
}

.info-text strong {
    display: block;
    color: #2d3748;
    font-size: 14px;
    margin-bottom: 2px;
}

.info-text small {
    color: #718096;
    font-size: 12px;
}

/* Product Info bên phải */
.product-info {
    background: white;
    padding: 40px;
    border-radius: 20px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

/* Spec Groups */
.specs-container {
    display: grid;
    gap: 20px;
    max-height: 600px;
    overflow-y: auto;
    padding-right: 10px;
}

.specs-container::-webkit-scrollbar {
    width: 6px;
}

.specs-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.specs-container::-webkit-scrollbar-thumb {
    background: #667eea;
    border-radius: 3px;
}

.spec-group {
    background: #f8fafc;
    border-radius: 12px;
    padding: 16px;
    border-left: 4px solid #667eea;
}

.spec-group-title {
    color: #2d3748;
    font-size: 1rem;
    font-weight: 700;
    margin: 0 0 12px 0;
    padding-bottom: 6px;
    border-bottom: 2px solid #e2e8f0;
}

.spec-group-content {
    display: grid;
    gap: 8px;
}

.spec-item {
    display: grid;
    grid-template-columns: 1fr 1.5fr;
    gap: 12px;
    padding: 6px 0;
    border-bottom: 1px solid #e2e8f0;
    align-items: start;
}

.spec-item:last-child {
    border-bottom: none;
}

.spec-label {
    font-weight: 600;
    color: #4a5568;
    font-size: 13px;
}

.spec-value {
    color: #2d3748;
    font-weight: 500;
    font-size: 13px;
    word-wrap: break-word;
    line-height: 1.4;
}

/* Responsive */
@media (max-width: 1200px) {
    .product-main {
        grid-template-columns: 1fr;
        gap: 30px;
    }
    
    .product-gallery {
        position: static;
    }
}

@media (max-width: 768px) {
    .action-buttons {
        grid-template-columns: 1fr;
        gap: 10px;
    }
    
    .quantity-selector {
        flex-direction: column;
        align-items: center;
        gap: 10px;
    }
    
    .spec-item {
        grid-template-columns: 1fr;
        gap: 4px;
    }
    
    .specs-container {
        max-height: 400px;
    }
}

/* Spec lines for multi-value attributes */
.spec-line {
    margin: 2px 0;
    padding: 2px 0;
    line-height: 1.4;
}

.spec-line:not(:last-child) {
    border-bottom: 1px dotted #e2e8f0;
    padding-bottom: 4px;
    margin-bottom: 4px;
}

/* Improved spacing for spec values */
.spec-value {
    color: #2d3748;
    font-weight: 500;
    font-size: 14px;
    word-wrap: break-word;
    line-height: 1.5;
}

/* Better spacing between specs */
.spec-item {
    display: grid;
    grid-template-columns: 1fr 2fr;
    gap: 15px;
    padding: 12px 0;
    border-bottom: 1px solid #e2e8f0;
    align-items: start; /* Align to top for multi-line content */
}
/* Single Product Styles */
.single-product-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 20px;
}

.product-breadcrumb {
    background: #f8fafc;
    padding: 15px 20px;
    border-radius: 10px;
    margin-bottom: 30px;
    font-size: 14px;
}

.product-breadcrumb a {
    color: #667eea;
    text-decoration: none;
}

.product-breadcrumb a:hover {
    text-decoration: underline;
}

.product-breadcrumb span {
    margin: 0 10px;
    color: #718096;
}

.product-main {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 50px;
    margin-bottom: 50px;
}

/* Product Gallery */
.product-gallery {
    position: sticky;
    top: 100px;
}

.main-image-container {
    background: white;
    border-radius: 20px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    overflow: hidden;
}

.main-image {
    position: relative;
    height: 500px;
    background: #f8fafc;
    display: flex;
    align-items: center;
    justify-content: center;
}

.main-image img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    transition: transform 0.3s;
    padding: 20px;
}

.gallery-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(255,255,255,0.9);
    border: none;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    cursor: pointer;
    font-size: 20px;
    color: #4a5568;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    transition: all 0.3s;
}

.gallery-nav:hover {
    background: white;
    transform: translateY(-50%) scale(1.1);
}

.prev-btn {
    left: 20px;
}

.next-btn {
    right: 20px;
}

.thumbnail-gallery {
    display: flex;
    gap: 10px;
    padding: 20px;
    overflow-x: auto;
    justify-content: center;
}

.thumbnail {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 10px;
    cursor: pointer;
    opacity: 0.7;
    transition: all 0.3s;
    border: 2px solid transparent;
}

.thumbnail:hover,
.thumbnail.active {
    opacity: 1;
    border-color: #667eea;
    transform: scale(1.05);
}

/* Product Info */
.product-info {
    background: white;
    padding: 40px;
    border-radius: 20px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    height: fit-content;
}

.product-title {
    font-size: 2rem;
    color: #2d3748;
    margin-bottom: 20px;
    font-weight: 800;
}

.product-price {
    font-size: 1.8rem;
    color: #e53e3e;
    font-weight: 700;
    margin-bottom: 20px;
}

.product-rating {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 25px;
}

.stars {
    color: #f6e05e;
    font-size: 1.2rem;
}

.review-count {
    color: #718096;
    font-size: 14px;
}

.product-short-description {
    color: #4a5568;
    line-height: 1.6;
    margin-bottom: 30px;
    font-size: 16px;
}

/* Specs Styles */
.product-specs {
    margin-bottom: 30px;
}

.product-specs h3 {
    color: #2d3748;
    margin-bottom: 20px;
    font-size: 1.3rem;
    font-weight: 700;
}

.specs-container {
    display: grid;
    gap: 20px;
}

.spec-group {
    background: #f8fafc;
    border-radius: 12px;
    padding: 20px;
    border-left: 4px solid #667eea;
}

.spec-group-title {
    color: #2d3748;
    font-size: 1.1rem;
    font-weight: 700;
    margin: 0 0 15px 0;
    padding-bottom: 8px;
    border-bottom: 2px solid #e2e8f0;
}

.spec-group-content {
    display: grid;
    gap: 10px;
}

.spec-item {
    display: grid;
    grid-template-columns: 1fr 2fr;
    gap: 15px;
    padding: 8px 0;
    border-bottom: 1px solid #e2e8f0;
    align-items: start;
}

.spec-item:last-child {
    border-bottom: none;
}

.spec-label {
    font-weight: 600;
    color: #4a5568;
    font-size: 14px;
}

.spec-value {
    color: #2d3748;
    font-weight: 500;
    font-size: 14px;
    word-wrap: break-word;
    line-height: 1.5;
}

/* Specs Toggle Button */
.specs-toggle {
    text-align: center;
    margin-top: 25px;
    padding-top: 20px;
    border-top: 2px solid #e2e8f0;
}

.toggle-specs-btn {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 15px 30px;
    border-radius: 30px;
    font-size: 16px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.3);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.toggle-specs-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
    background: linear-gradient(135deg, #5a67d8 0%, #667eea 100%);
}

.toggle-specs-btn:active {
    transform: translateY(-1px);
}

/* Modal Styles */
.specs-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.8);
    z-index: 10000;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    backdrop-filter: blur(5px);
}

.specs-modal-content {
    background: white;
    border-radius: 20px;
    max-width: 90vw;
    max-height: 90vh;
    width: 1200px;
    display: flex;
    flex-direction: column;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
    animation: modalSlideIn 0.3s ease-out;
}

@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: scale(0.9) translateY(-20px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

.specs-modal-header {
    padding: 25px 30px;
    border-bottom: 3px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
    border-radius: 20px 20px 0 0;
}

.specs-modal-header h2 {
    margin: 0;
    color: #2d3748;
    font-size: 1.5rem;
    font-weight: 800;
}

.specs-modal-close {
    background: none;
    border: none;
    font-size: 30px;
    cursor: pointer;
    color: #718096;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s;
}

.specs-modal-close:hover {
    background: #fed7d7;
    color: #e53e3e;
    transform: scale(1.1);
}

.specs-modal-body {
    flex: 1;
    overflow-y: auto;
    padding: 0;
}

.specs-table-container {
    padding: 30px;
}

.specs-group-section {
    margin-bottom: 40px;
}

.specs-group-header {
    font-size: 1.3rem;
    font-weight: 800;
    color: #2d3748;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 3px solid #667eea;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    background-clip: text;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.specs-table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.specs-table tr {
    border-bottom: 1px solid #e2e8f0;
    transition: background-color 0.2s;
}

.specs-table tr:hover {
    background-color: #f7fafc;
}

.specs-table tr:last-child {
    border-bottom: none;
}

.spec-label-col {
    background: #f8fafc;
    font-weight: 700;
    color: #4a5568;
    padding: 15px 20px;
   width: 30%;
   vertical-align: top;
   border-right: 2px solid #e2e8f0;
}

.spec-value-col {
   padding: 15px 20px;
   color: #2d3748;
   vertical-align: top;
   line-height: 1.6;
}

.spec-list {
   margin: 0;
   padding-left: 20px;
   list-style-type: none;
}

.spec-list li {
   margin-bottom: 8px;
   padding: 5px 0;
   border-bottom: 1px dotted #e2e8f0;
   position: relative;
}

.spec-list li:last-child {
   border-bottom: none;
   margin-bottom: 0;
}

.spec-list li:before {
   content: "▸";
   color: #667eea;
   font-weight: bold;
   position: absolute;
   left: -15px;
}

.specs-modal-footer {
   padding: 20px 30px;
   border-top: 2px solid #e2e8f0;
   text-align: center;
   background: #f7fafc;
   border-radius: 0 0 20px 20px;
}

.specs-modal-close-btn {
   background: linear-gradient(135deg, #e53e3e 0%, #c53030 100%);
   color: white;
   border: none;
   padding: 12px 25px;
   border-radius: 25px;
   font-size: 16px;
   font-weight: 700;
   cursor: pointer;
   transition: all 0.3s ease;
   box-shadow: 0 4px 15px rgba(229, 62, 62, 0.3);
   text-transform: uppercase;
   letter-spacing: 0.5px;
}

.specs-modal-close-btn:hover {
   transform: translateY(-2px);
   box-shadow: 0 8px 25px rgba(229, 62, 62, 0.4);
   background: linear-gradient(135deg, #c53030 0%, #9c1a1c 100%);
}

/* Actions moved under gallery */
.product-actions {
   margin-top: 30px;
   background: white;
   padding: 25px;
   border-radius: 15px;
   box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

.quantity-selector {
   display: flex;
   align-items: center;
   gap: 15px;
   margin-bottom: 20px;
   justify-content: center;
}

.quantity-selector label {
   font-weight: 600;
   color: #4a5568;
   font-size: 16px;
}

.quantity-input {
   display: flex;
   align-items: center;
   border: 2px solid #e2e8f0;
   border-radius: 8px;
   overflow: hidden;
}

.qty-btn {
   background: #f8fafc;
   border: none;
   width: 40px;
   height: 40px;
   cursor: pointer;
   font-size: 16px;
   font-weight: bold;
   transition: background 0.3s;
}

.qty-btn:hover {
   background: #e2e8f0;
}

.quantity-input input {
   width: 60px;
   height: 40px;
   text-align: center;
   border: none;
   font-size: 16px;
   font-weight: 600;
}

.action-buttons {
   display: grid;
   grid-template-columns: 1fr 1fr;
   gap: 12px;
}

.add-to-cart-btn,
.buy-now-btn,
.compare-btn,
.wishlist-btn {
   padding: 12px 16px;
   border: none;
   border-radius: 10px;
   font-size: 14px;
   font-weight: 700;
   cursor: pointer;
   transition: all 0.3s;
   display: flex;
   align-items: center;
   justify-content: center;
   gap: 6px;
   min-height: 44px;
}

.add-to-cart-btn {
   background: linear-gradient(135deg, #38a169 0%, #2f855a 100%);
   color: white;
   box-shadow: 0 4px 15px rgba(56, 161, 105, 0.3);
}

.add-to-cart-btn:hover {
   transform: translateY(-2px);
   box-shadow: 0 8px 25px rgba(56, 161, 105, 0.4);
}

.buy-now-btn {
   background: linear-gradient(135deg, #e53e3e 0%, #c53030 100%);
   color: white;
   box-shadow: 0 4px 15px rgba(229, 62, 62, 0.3);
}

.buy-now-btn:hover {
   transform: translateY(-2px);
   box-shadow: 0 8px 25px rgba(229, 62, 62, 0.4);
}

.compare-btn {
   background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
   color: white;
   box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.compare-btn:hover {
   transform: translateY(-2px);
   box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
}

.wishlist-btn {
   background: linear-gradient(135deg, #ed64a6 0%, #d53f8c 100%);
   color: white;
   box-shadow: 0 4px 15px rgba(237, 100, 166, 0.3);
}

.wishlist-btn:hover {
   transform: translateY(-2px);
   box-shadow: 0 8px 25px rgba(237, 100, 166, 0.4);
}

/* Additional Info under actions */
.additional-info {
   margin-top: 20px;
   display: grid;
   gap: 12px;
}

.info-item {
   display: flex;
   align-items: center;
   gap: 12px;
   padding: 12px;
   background: #f8fafc;
   border-radius: 8px;
   border-left: 4px solid #38a169;
}

.info-item .icon {
   font-size: 20px;
   min-width: 24px;
}

.info-text strong {
   display: block;
   color: #2d3748;
   font-size: 14px;
   margin-bottom: 2px;
}

.info-text small {
   color: #718096;
   font-size: 12px;
}

/* Product Description */
.product-description {
   background: white;
   padding: 40px;
   border-radius: 20px;
   box-shadow: 0 8px 25px rgba(0,0,0,0.1);
   margin-bottom: 50px;
}

.product-description h3 {
   color: #2d3748;
   margin-bottom: 25px;
   font-size: 1.5rem;
}

.description-content {
   color: #4a5568;
   line-height: 1.8;
   font-size: 16px;
}

/* Related Products */
.related-products {
   background: white;
   padding: 40px;
   border-radius: 20px;
   box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.related-products h3 {
   color: #2d3748;
   margin-bottom: 30px;
   font-size: 1.5rem;
   text-align: center;
}

.related-grid {
   display: grid;
   grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
   gap: 25px;
}

.related-item {
   background: #f8fafc;
   border-radius: 15px;
   padding: 20px;
   text-align: center;
   transition: transform 0.3s;
   border: 2px solid transparent;
}

.related-item:hover {
   transform: translateY(-5px);
   border-color: #667eea;
   box-shadow: 0 8px 25px rgba(102, 126, 234, 0.2);
}

.related-item img {
   width: 100%;
   height: 200px;
   object-fit: cover;
   border-radius: 10px;
   margin-bottom: 15px;
}

.related-item h4 {
   color: #2d3748;
   margin-bottom: 10px;
   font-size: 1rem;
}

.related-item .price {
   color: #e53e3e;
   font-weight: 700;
   font-size: 1.1rem;
}

.related-item a {
   text-decoration: none;
   color: inherit;
}

/* Responsive Design */
@media (max-width: 1024px) {
   .product-main {
       grid-template-columns: 1fr;
       gap: 30px;
   }
   
   .product-gallery {
       position: static;
   }
   
   .specs-modal-content {
       max-width: 95vw;
       margin: 10px;
   }
   
   .specs-table {
       font-size: 14px;
   }
   
   .spec-label-col {
       width: 35%;
   }
}

@media (max-width: 768px) {
   .single-product-container {
       padding: 10px;
   }
   
   .product-info {
       padding: 20px;
   }
   
   .product-title {
       font-size: 1.5rem;
   }
   
   .main-image {
       height: 300px;
   }
   
   .thumbnail {
       width: 60px;
       height: 60px;
   }
   
   .action-buttons {
       grid-template-columns: 1fr;
       gap: 10px;
   }
   
   .specs-modal-header {
       padding: 20px;
   }
   
   .specs-modal-header h2 {
       font-size: 1.2rem;
   }
   
   .specs-table-container {
       padding: 15px;
   }
   
   .spec-label-col,
   .spec-value-col {
       padding: 10px;
   }
   
   .spec-label-col {
       width: 40%;
   }
}

@media (max-width: 480px) {
   .product-main {
       gap: 20px;
   }
   
   .main-image {
       height: 250px;
   }
   
   .specs-modal-content {
       margin: 5px;
       border-radius: 10px;
   }
   
   .specs-table {
       font-size: 12px;
   }
   
   .spec-label-col {
       width: 45%;
       padding: 8px;
   }
   
   .spec-value-col {
       padding: 8px;
   }
   
   .specs-group-header {
       font-size: 1.1rem;
   }
}

/* Animation */
.single-product-container {
   animation: fadeInUp 0.6s ease-out;
}

@keyframes fadeInUp {
   from {
       opacity: 0;
       transform: translateY(30px);
   }
   to {
       opacity: 1;
       transform: translateY(0);
   }
}

body {
    margin: 0;
    padding: 0;
}

.site-footer {
    margin: 0;
    padding: 40px 0 20px;
    width: 100vw; /* viewport width - chiếm hết màn hình */
    position: relative;
    left: 50%;
    right: 50%;
    margin-left: -50vw;
    margin-right: -50vw;
}


</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Modal functionality
    const toggleBtn = document.getElementById('toggle-specs-btn');
    const modal = document.getElementById('specs-modal');
    const closeButtons = document.querySelectorAll('.specs-modal-close, .specs-modal-close-btn');
    
    // Open modal
    if (toggleBtn) {
        toggleBtn.addEventListener('click', function() {
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden'; // Prevent body scroll
        });
    }
    
    // Close modal
    closeButtons.forEach(button => {
        button.addEventListener('click', function() {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto'; // Restore body scroll
        });
    });
    
    // Close modal when clicking outside
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    });
    
    // Close modal with ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal.style.display === 'flex') {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    });
    
    // Gallery functionality
    const mainImage = document.getElementById('main-product-image');
    const thumbnails = document.querySelectorAll('.thumbnail');
    const prevBtn = document.getElementById('prev-image');
    const nextBtn = document.getElementById('next-image');
    
    let currentImageIndex = 0;
    const imageUrls = Array.from(thumbnails).map(thumb => thumb.dataset.large);
    
    // Thumbnail click
    thumbnails.forEach((thumb, index) => {
        thumb.addEventListener('click', function() {
            mainImage.src = this.dataset.large;
            currentImageIndex = index;
            updateActiveThumbnail(index);
        });
    });
    
    // Previous/Next buttons
    if (prevBtn && nextBtn && imageUrls.length > 1) {
        prevBtn.addEventListener('click', function() {
            currentImageIndex = (currentImageIndex - 1 + imageUrls.length) % imageUrls.length;
            mainImage.src = imageUrls[currentImageIndex];
            updateActiveThumbnail(currentImageIndex);
        });
        
        nextBtn.addEventListener('click', function() {
            currentImageIndex = (currentImageIndex + 1) % imageUrls.length;
            mainImage.src = imageUrls[currentImageIndex];
            updateActiveThumbnail(currentImageIndex);
        });
    }
    
    function updateActiveThumbnail(index) {
        thumbnails.forEach((thumb, i) => {
            thumb.classList.toggle('active', i === index);
        });
    }
    
    // Quantity controls
    const qtyMinus = document.querySelector('.qty-btn.minus');
    const qtyPlus = document.querySelector('.qty-btn.plus');
    const qtyInput = document.querySelector('.quantity-input input[name="quantity"]');
    
    if (qtyMinus && qtyPlus && qtyInput) {
        qtyMinus.addEventListener('click', function() {
            const currentValue = parseInt(qtyInput.value);
            if (currentValue > 1) {
                qtyInput.value = currentValue - 1;
            }
        });
        
        qtyPlus.addEventListener('click', function() {
            const currentValue = parseInt(qtyInput.value);
            const maxValue = parseInt(qtyInput.max) || 10;
            if (currentValue < maxValue) {
                qtyInput.value = currentValue + 1;
            }
        });
    }
});

jQuery(document).ready(function($) {
    let currentImageIndex = 0;
    const images = [];
    
    // Collect all gallery images
    $('.thumbnail').each(function(index) {
        images.push({
            thumb: $(this).attr('src'),
            large: $(this).data('large')
        });
    });
    
    // Image gallery navigation
    function showImage(index) {
        if (images.length === 0) return;
        
        currentImageIndex = index;
        if (currentImageIndex < 0) currentImageIndex = images.length - 1;
        if (currentImageIndex >= images.length) currentImageIndex = 0;
        
        $('#main-product-image').attr('src', images[currentImageIndex].large);
        $('.thumbnail').removeClass('active');
        $('.thumbnail').eq(currentImageIndex).addClass('active');
    }
    
    // Thumbnail clicks
    $('.thumbnail').on('click', function() {
        const index = $('.thumbnail').index(this);
        showImage(index);
    });
    
    // Navigation buttons
    $('#prev-image').on('click', function() {
        showImage(currentImageIndex - 1);
    });
    
    $('#next-image').on('click', function() {
        showImage(currentImageIndex + 1);
    });
    
    // Keyboard navigation
    $(document).on('keydown', function(e) {
        if (e.keyCode === 37) { // Left arrow
            showImage(currentImageIndex - 1);
        } else if (e.keyCode === 39) { // Right arrow
            showImage(currentImageIndex + 1);
        }
    });
    
    // Quantity controls
    $('.qty-btn.plus').on('click', function() {
        const input = $(this).siblings('input');
        const max = parseInt(input.attr('max')) || 999;
        const current = parseInt(input.val()) || 1;
        if (current < max) {
            input.val(current + 1);
        }
    });
    
    $('.qty-btn.minus').on('click', function() {
        const input = $(this).siblings('input');
        const min = parseInt(input.attr('min')) || 1;
        const current = parseInt(input.val()) || 1;
        if (current > min) {
            input.val(current - 1);
        }
    });
    
    // Add to cart
    $('.add-to-cart-btn').on('click', function() {
        const productId = $(this).data('product-id');
        const quantity = $('.quantity-input input').val() || 1;
        const $btn = $(this);
        
        $btn.prop('disabled', true).html('⏳ Đang thêm...');
        
        // Add to cart via URL redirect (simple method)
        window.location.href = '?add-to-cart=' + productId + '&quantity=' + quantity;
    });
    
    // Buy now
    $('.buy-now-btn').on('click', function() {
        const productId = $(this).data('product-id');
        const quantity = $('.quantity-input input').val() || 1;
        
        // Add to cart and redirect to checkout
        window.location.href = '<?php echo wc_get_cart_url(); ?>?add-to-cart=' + productId + '&quantity=' + quantity;
    });
    
    // Compare button
    $('.compare-btn').on('click', function() {
        const productId = $(this).data('product-id');
        const $btn = $(this);
        
        // Get current compare list
        let compareProducts = JSON.parse(localStorage.getItem('phonestore_compare') || '[]');
        
        if (compareProducts.length >= 4) {
            alert('Chỉ có thể so sánh tối đa 4 sản phẩm!');
            return;
        }
        
        if (!compareProducts.includes(productId)) {
            compareProducts.push(productId);
            localStorage.setItem('phonestore_compare', JSON.stringify(compareProducts));
            
            $btn.css('background', 'linear-gradient(135deg, #28a745 0%, #20c997 100%)')
                .html('✅ Đã thêm so sánh');
            
            updateCompareNotification();
            
            alert('Đã thêm sản phẩm vào danh sách so sánh!');
        } else {
            alert('Sản phẩm đã có trong danh sách so sánh!');
        }
    });
    
    // Wishlist button
    $('.wishlist-btn').on('click', function() {
        const productId = $(this).data('product-id');
        const $btn = $(this);
        
        if (!$btn.hasClass('added')) {
            $btn.addClass('added')
                .css('background', 'linear-gradient(135deg, #dc2626 0%, #b91c1c 100%)')
                .html('❤️ Đã yêu thích');
            
            alert('Đã thêm vào danh sách yêu thích!');
        } else {
            $btn.removeClass('added')
                .css('background', 'linear-gradient(135deg, #ed64a6 0%, #d53f8c 100%)')
                .html('❤️ Yêu thích');
            
            alert('Đã xóa khỏi danh sách yêu thích!');
        }
    });
    
    // Update compare notification
    function updateCompareNotification() {
        const compareProducts = JSON.parse(localStorage.getItem('phonestore_compare') || '[]');
        const count = compareProducts.length;
        
        if (count > 0) {
           if (!$('.compare-notification').length) {
               $('body').append('<div class="compare-notification">So sánh (' + count + ')</div>');
           } else {
               $('.compare-notification').text('So sánh (' + count + ')');
           }
           
           $('.compare-notification').off('click').on('click', function() {
               window.location.href = '<?php echo home_url('/so-sanh-san-pham/'); ?>';
           });
       }
   }
   
   // Initialize compare notification
   updateCompareNotification();
   
   // Check if product is already in compare list
   const compareProducts = JSON.parse(localStorage.getItem('phonestore_compare') || '[]');
   const currentProductId = $('.compare-btn').data('product-id');
   
   if (compareProducts.includes(currentProductId)) {
       $('.compare-btn').css('background', 'linear-gradient(135deg, #28a745 0%, #20c997 100%)')
                        .html('✅ Đã thêm so sánh');
   }
   
   // Image zoom on hover
   $('#main-product-image').on('mouseenter', function() {
       $(this).css('transform', 'scale(1.2)');
   }).on('mouseleave', function() {
       $(this).css('transform', 'scale(1)');
   });
   
   // Touch/swipe support for mobile
   let startX = 0;
   let endX = 0;
   
   $('.main-image').on('touchstart', function(e) {
       startX = e.originalEvent.touches[0].clientX;
   });
   
   $('.main-image').on('touchend', function(e) {
       endX = e.originalEvent.changedTouches[0].changedTouches[0].clientX;
       const diffX = startX - endX;
       
       if (Math.abs(diffX) > 50) { // Minimum swipe distance
           if (diffX > 0) {
               // Swipe left - next image
               showImage(currentImageIndex + 1);
           } else {
               // Swipe right - previous image
               showImage(currentImageIndex - 1);
           }
       }
   });
});

document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('toggle-specs-btn');
    const specsPreview = document.querySelector('.specs-preview');
    const specsFull = document.querySelector('.specs-full');
    const showText = document.querySelector('.show-text');
    const hideText = document.querySelector('.hide-text');
    
    if (toggleBtn) {
        toggleBtn.addEventListener('click', function() {
            if (specsFull.style.display === 'none') {
                // Hiển thị đầy đủ
                specsPreview.style.display = 'none';
                specsFull.style.display = 'block';
                showText.style.display = 'none';
                hideText.style.display = 'inline';
            } else {
                // Thu gọn
                specsPreview.style.display = 'block';
                specsFull.style.display = 'none';
                showText.style.display = 'inline';
                hideText.style.display = 'none';
            }
        });
    }
});
</script>
<?php get_footer(); ?>