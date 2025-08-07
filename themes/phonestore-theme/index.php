<?php get_header(); ?>

<div class="container">
    <!-- Hero Section -->
    <section class="hero-section">
        <h1>🏪 Cửa Hàng Điện Thoại Chính Hãng</h1>
        <p>Tìm kiếm và so sánh điện thoại tốt nhất với giá ưu đãi</p>
    </section>
    
    <!-- Featured Products -->
    <?php if (class_exists('WooCommerce')): ?>
    <section class="featured-products">
        <h2>📱 Sản phẩm nổi bật</h2>
        <div class="products-grid">
            <?php
            $featured_products = wc_get_products([
                'limit' => 8,
                'orderby' => 'popularity',
                'status' => 'publish'
            ]);
            
            if ($featured_products):
                foreach ($featured_products as $product):
            ?>
                <div class="product-item">
                    <a href="<?php echo $product->get_permalink(); ?>" class="product-link">
                        <div class="product-image">
                            <?php echo $product->get_image('medium'); ?>
                        </div>
                        <div class="product-info">
                            <h3><?php echo $product->get_name(); ?></h3>
                            <div class="price"><?php echo $product->get_price_html(); ?></div>
                            <div class="product-specs">
                                <?php
                                $ram = get_field('ram', $product->get_id());
                                $storage = get_field('storage', $product->get_id());
                                if ($ram) echo '<span>💾 ' . strtoupper($ram) . '</span>';
                                if ($storage) echo '<span>💿 ' . strtoupper($storage) . '</span>';
                                ?>
                            </div>
                        </div>
                    </a>
                    <div class="product-actions">
                        <button class="add-to-cart-btn" onclick="window.location.href='?add-to-cart=<?php echo $product->get_id(); ?>'">
                            🛒
                        </button>
                        <button class="compare-btn" data-product-id="<?php echo $product->get_id(); ?>">
                            ⚖️
                        </button>
                    </div>
                </div>
            <?php 
                endforeach;
            else:
                echo '<p>Chưa có sản phẩm nào.</p>';
            endif;
            ?>
        </div>
    </section>
    
    <section class="latest-products">
        <h2>🆕 Sản phẩm mới nhất</h2>
        <div class="products-grid">
            <?php
            $latest_products = wc_get_products([
                'limit' => 4,
                'orderby' => 'date',
                'order' => 'DESC',
                'status' => 'publish'
            ]);
            
            foreach ($latest_products as $product):
            ?>
                <div class="product-item">
                    <a href="<?php echo $product->get_permalink(); ?>" class="product-link">
                        <div class="product-image">
                            <?php echo $product->get_image('medium'); ?>
                        </div>
                        <div class="product-info">
                            <h3><?php echo $product->get_name(); ?></h3>
                            <div class="price"><?php echo $product->get_price_html(); ?></div>
                            <div class="product-specs">
                                <?php
                                $ram = get_field('ram', $product->get_id());
                                $storage = get_field('storage', $product->get_id());
                                if ($ram) echo '<span>💾 ' . strtoupper($ram) . '</span>';
                                if ($storage) echo '<span>💿 ' . strtoupper($storage) . '</span>';
                                ?>
                            </div>
                        </div>
                    </a>
                    <div class="product-actions">
                        <button class="add-to-cart-btn" onclick="window.location.href='?add-to-cart=<?php echo $product->get_id(); ?>'">
                            🛒
                        </button>
                        <button class="compare-btn" data-product-id="<?php echo $product->get_id(); ?>">
                            ⚖️
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>
</div>

<style>
/* Hero Section */
.hero-section {
    text-align: center;
    padding: 60px 0;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 20px;
    margin: 20px 0 50px 0;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
}

.hero-section h1 {
    font-size: 3rem;
    margin-bottom: 15px;
    font-weight: 800;
    text-shadow: 0 2px 4px rgba(0,0,0,0.3);
}

.hero-section p {
    font-size: 1.3rem;
    margin-bottom: 0;
    opacity: 0.95;
    max-width: 600px;
    margin: 0 auto;
    line-height: 1.6;
}

/* Products Sections */
.featured-products, .latest-products {
    margin: 60px 0;
}

.featured-products h2, .latest-products h2 {
    text-align: center;
    font-size: 2.2rem;
    margin-bottom: 40px;
    color: #2d3748;
    font-weight: 800;
    position: relative;
}

.featured-products h2:after, .latest-products h2:after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 80px;
    height: 4px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 2px;
}

/* Products Grid - 4 items per row */
.products-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 30px;
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 20px;
}

.product-item {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    flex-direction: column;
    height: 100%;
    border: 1px solid #f1f5f9;
}

.product-item:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    border-color: #e2e8f0;
}

.product-link {
    text-decoration: none;
    color: inherit;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.product-image {
    position: relative;
    overflow: hidden;
    height: 280px;
    background: #f8fafc;
    display: flex;
    align-items: center;
    justify-content: center;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    object-position: center;
    padding: 20px;
    transition: transform 0.4s ease;
    max-width: 240px;
    max-height: 240px;
}

.product-item:hover .product-image img {
    transform: scale(1.05);
}

.product-info {
    padding: 25px 20px 20px 20px;
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.product-info h3 {
    color: #2d3748;
    font-size: 16px;
    font-weight: 700;
    margin: 0;
    line-height: 1.4;
    height: 44px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.product-link:hover .product-info h3 {
    color: #667eea;
}

.price {
    font-size: 20px;
    font-weight: 800;
    color: #e53e3e;
    margin: 0;
}

.product-specs {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin: 0;
    min-height: 28px;
    align-items: flex-start;
}

.product-specs span {
    background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
    color: #4a5568;
    padding: 6px 10px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 600;
    border: 1px solid #e2e8f0;
}

/* Product Actions - Equal sized buttons */
.product-actions {
    padding: 15px 20px 20px 20px;
    display: flex;
    gap: 10px;
    margin-top: auto;
    border-top: 1px solid #f7fafc;
    background: #fafbfc;
}

.add-to-cart-btn,
.compare-btn {
    flex: 1;
    height: 44px;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    font-size: 16px;
    font-weight: 700;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    white-space: nowrap;
}

.add-to-cart-btn {
    background: linear-gradient(135deg, #38a169 0%, #2f855a 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(56, 161, 105, 0.3);
}

.add-to-cart-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(56, 161, 105, 0.4);
    background: linear-gradient(135deg, #2f855a 0%, #276749 100%);
}

.compare-btn {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.compare-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%);
}

/* Container Improvements */
.container {
    max-width: 1600px;
    margin: 0 auto;
    padding: 0 20px;
}

/* Loading State */
.products-grid:empty:before {
    content: "⏳ Đang tải sản phẩm...";
    grid-column: 1 / -1;
    text-align: center;
    padding: 60px 20px;
    color: #718096;
    font-size: 18px;
}

/* Responsive Design */
@media (max-width: 1400px) {
    .products-grid {
        grid-template-columns: repeat(4, 1fr);
        gap: 25px;
        padding: 0 15px;
    }
}

@media (max-width: 1200px) {
    .products-grid {
        grid-template-columns: repeat(3, 1fr);
        gap: 25px;
    }
}

@media (max-width: 900px) {
    .products-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }
    
    .product-image {
        height: 240px;
    }
    
    .product-info {
        padding: 20px 15px 15px 15px;
    }
    
    .product-info h3 {
        font-size: 15px;
        height: 40px;
    }
    
    .price {
        font-size: 18px;
    }
    
    .product-actions {
        padding: 12px 15px 15px 15px;
        gap: 8px;
    }
    
    .add-to-cart-btn,
    .compare-btn {
        height: 40px;
        font-size: 14px;
    }
}

@media (max-width: 768px) {
    .hero-section {
        padding: 40px 20px;
        margin: 15px 0 40px 0;
    }
    
    .hero-section h1 {
        font-size: 2.2rem;
    }
    
    .hero-section p {
        font-size: 1.1rem;
    }
    
    .featured-products h2, .latest-products h2 {
        font-size: 1.8rem;
        margin-bottom: 30px;
    }
}

@media (max-width: 600px) {
    .products-grid {
        grid-template-columns: 1fr;
        gap: 20px;
        padding: 0 10px;
    }
    
    .container {
        padding: 0 15px;
    }
    
    .hero-section {
        padding: 30px 15px;
        border-radius: 15px;
    }
    
    .hero-section h1 {
        font-size: 1.8rem;
    }
    
    .product-image {
        height: 220px;
    }
    
    .product-specs span {
        font-size: 10px;
        padding: 4px 8px;
    }
    
    .add-to-cart-btn,
    .compare-btn {
        height: 36px;
        font-size: 13px;
    }
}

/* Animation for product cards */
.product-item {
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

/* Stagger animation for grid items */
.product-item:nth-child(1) { animation-delay: 0.1s; }
.product-item:nth-child(2) { animation-delay: 0.2s; }
.product-item:nth-child(3) { animation-delay: 0.3s; }
.product-item:nth-child(4) { animation-delay: 0.4s; }
.product-item:nth-child(5) { animation-delay: 0.5s; }
.product-item:nth-child(6) { animation-delay: 0.6s; }
.product-item:nth-child(7) { animation-delay: 0.7s; }
.product-item:nth-child(8) { animation-delay: 0.8s; }

/* Focus states for accessibility */
.add-to-cart-btn:focus,
.compare-btn:focus {
    outline: 3px solid rgba(66, 153, 225, 0.6);
    outline-offset: 2px;
}

.product-link:focus {
    outline: 2px solid #667eea;
    outline-offset: 4px;
    border-radius: 20px;
}

/* Special styling for latest products section */
.latest-products .product-item {
    animation-delay: 0.2s;
}

.latest-products .product-item:nth-child(1) { animation-delay: 0.2s; }
.latest-products .product-item:nth-child(2) { animation-delay: 0.3s; }
.latest-products .product-item:nth-child(3) { animation-delay: 0.4s; }
.latest-products .product-item:nth-child(4) { animation-delay: 0.5s; }
</style>

<?php get_footer(); ?>