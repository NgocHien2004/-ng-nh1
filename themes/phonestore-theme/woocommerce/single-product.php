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
                </div>
                
                <!-- Product Info -->
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
                    
                    <!-- Product Specs -->
<div class="product-specs">
    <h3>📋 Thông số kỹ thuật</h3>
    <div class="specs-grid">
        <?php
        // Lấy WooCommerce attributes trước
        $attributes = $product->get_attributes();
        
        if (!empty($attributes)) {
            foreach ($attributes as $attribute) {
                if ($attribute->get_variation()) {
                    continue; // Skip variation attributes
                }
                
                $attribute_name = $attribute->get_name();
                $attribute_label = wc_attribute_label($attribute_name);
                
                if ($attribute->is_taxonomy()) {
                    $values = wc_get_product_terms($product_id, $attribute_name, array('fields' => 'names'));
                    $attribute_value = implode(', ', $values);
                } else {
                    $attribute_value = $attribute->get_options();
                    if (is_array($attribute_value)) {
                        $attribute_value = implode(', ', $attribute_value);
                    }
                }
                
                if (!empty($attribute_value)) {
                    echo '<div class="spec-item">';
                    echo '<span class="spec-label">' . esc_html($attribute_label) . ':</span>';
                    echo '<span class="spec-value">' . esc_html($attribute_value) . '</span>';
                    echo '</div>';
                }
            }
        }
        
        // Fallback: Sử dụng ACF hoặc custom meta fields nếu có
        $specs = array(
            'brand' => '📱 Thương hiệu',
            'display_size' => '📺 Màn hình', 
            'cpu' => '⚡ Vi xử lý',
            'ram' => '💾 RAM',
            'storage' => '💿 Bộ nhớ trong',
            'rear_camera' => '📷 Camera sau',
            'front_camera' => '🤳 Camera trước',
            'battery' => '🔋 Pin',
            'os' => '🖥️ Hệ điều hành',
            'weight' => '⚖️ Trọng lượng',
            'dimensions' => '📏 Kích thước',
            'colors' => '🎨 Màu sắc'
        );
        
        // Chỉ hiển thị specs từ ACF/meta nếu WC attributes trống
        if (empty($attributes)) {
            foreach ($specs as $key => $label) {
                // Thử ACF trước
                $value = function_exists('get_field') ? get_field($key, $product_id) : '';
                
                // Nếu không có ACF, thử custom meta
                if (empty($value)) {
                    $value = get_post_meta($product_id, $key, true);
                }
                
                // Nếu vẫn không có, thử meta với prefix '_'
                if (empty($value)) {
                    $value = get_post_meta($product_id, '_' . $key, true);
                }
                
                if (!empty($value)) {
                    echo '<div class="spec-item">';
                    echo '<span class="spec-label">' . esc_html($label) . ':</span>';
                    echo '<span class="spec-value">' . esc_html($value) . '</span>';
                    echo '</div>';
                }
            }
        }
        
        // Nếu không có spec nào, hiển thị thông báo
        if (empty($attributes) && !array_filter($specs, function($key) use ($product_id) {
            $value = function_exists('get_field') ? get_field($key, $product_id) : '';
            if (empty($value)) {
                $value = get_post_meta($product_id, $key, true);
            }
            if (empty($value)) {
                $value = get_post_meta($product_id, '_' . $key, true);
            }
            return !empty($value);
        }, ARRAY_FILTER_USE_KEY)) {
        ?>
            <div class="spec-item">
                <span class="spec-label">📋 Thông số:</span>
                <span class="spec-value">Đang cập nhật thông tin chi tiết</span>
            </div>
        <?php } ?>
    </div>
</div>
                    
                    <!-- Product Actions -->
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
                                <small>Bảo hành 12 tháng</small>
                            </div>
                        </div>
                        <div class="info-item">
                            <span class="icon">💬</span>
                            <div class="info-text">
                                <strong>Hỗ trợ 24/7</strong>
                                <small>Tư vấn mua hàng miễn phí</small>
                            </div>
                        </div>
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
            
            <!-- Related Products -->
            <div class="related-products">
                <h3>📱 Sản phẩm liên quan</h3>
                <div class="related-grid">
                    <?php
                    $related_products = wc_get_products(array(
                        'limit' => 4,
                        'exclude' => array($product_id),
                        'orderby' => 'rand'
                    ));
                    
                    foreach ($related_products as $related_product):
                    ?>
                        <div class="related-item">
                            <a href="<?php echo get_permalink($related_product->get_id()); ?>">
                                <?php echo $related_product->get_image('medium'); ?>
                                <h4><?php echo $related_product->get_name(); ?></h4>
                                <div class="price"><?php echo $related_product->get_price_html(); ?></div>
                            </a>
                        </div>
                    <?php endforeach; ?>
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
</style>

<script>
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
</script>

<?php get_footer(); ?>