<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.9.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' ); ?>

<div class="cart-page-container">
    <div class="cart-header">
        <h1>🛒 Giỏ Hàng Của Bạn</h1>
        <p>Xem lại sản phẩm và tiến hành thanh toán</p>
    </div>

    <form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
        <?php do_action( 'woocommerce_before_cart_table' ); ?>

        <?php if ( WC()->cart->get_cart_contents_count() > 0 ) : ?>
            
            <!-- Cart Items -->
            <div class="cart-items-section">
                <h2>📦 Sản phẩm trong giỏ (<?php echo WC()->cart->get_cart_contents_count(); ?> sản phẩm)</h2>
                
                <div class="cart-table-wrapper">
                    <table class="shop_table shop_table_responsive cart woocommerce-cart-table__table" cellspacing="0">
                        <thead>
                            <tr>
                                <th class="product-thumbnail">Hình ảnh</th>
                                <th class="product-name">Sản phẩm</th>
                                <th class="product-price">Đơn giá</th>
                                <th class="product-quantity">Số lượng</th>
                                <th class="product-subtotal">Thành tiền</th>
                                <th class="product-remove">Xóa</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php do_action( 'woocommerce_before_cart_contents' ); ?>

                            <?php
                            foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                                $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                                $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

                                if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
                                    $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
                                    ?>
                                    <tr class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>" data-cart-item-key="<?php echo esc_attr( $cart_item_key ); ?>">

                                        <!-- Product Image -->
                                        <td class="product-thumbnail">
                                            <div class="product-image-wrapper">
                                                <?php
                                                $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

                                                if ( ! $product_permalink ) {
                                                    echo $thumbnail; // PHPCS: XSS ok.
                                                } else {
                                                    printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // PHPCS: XSS ok.
                                                }
                                                ?>
                                            </div>
                                        </td>

                                        <!-- Product Name -->
                                        <td class="product-name" data-title="<?php esc_attr_e( 'Product', 'woocommerce' ); ?>">
                                            <div class="product-info">
                                                <?php
                                                if ( ! $product_permalink ) {
                                                    echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
                                                } else {
                                                    echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s" class="product-title">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
                                                }

                                                do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

                                                // Meta data.
                                                echo wc_get_formatted_cart_item_data( $cart_item ); // PHPCS: XSS ok.

                                                // Backorder notification.
                                                if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
                                                    echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'woocommerce' ) . '</p>', $product_id ) );
                                                }
                                                ?>
                                                
                                                <!-- Product Specs -->
                                                <div class="product-specs-mini">
                                                    <?php
                                                    if (function_exists('get_field')) {
                                                        $ram = get_field('ram', $product_id);
                                                        $storage = get_field('storage', $product_id);
                                                        if ($ram) echo '<span class="spec">💾 ' . strtoupper($ram) . '</span>';
                                                        if ($storage) echo '<span class="spec">💿 ' . strtoupper($storage) . '</span>';
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Product Price -->
                                        <td class="product-price" data-title="<?php esc_attr_e( 'Price', 'woocommerce' ); ?>">
                                            <div class="price-wrapper">
                                                <?php
                                                    echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
                                                ?>
                                            </div>
                                        </td>

                                        <!-- Product Quantity -->
                                        <td class="product-quantity" data-title="<?php esc_attr_e( 'Quantity', 'woocommerce' ); ?>">
                                            <div class="quantity-wrapper">
                                                <?php
                                                if ( $_product->is_sold_individually() ) {
                                                    $product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
                                                } else {
                                                    $product_quantity = woocommerce_quantity_input(
                                                        array(
                                                            'input_name'   => "cart[{$cart_item_key}][qty]",
                                                            'input_value'  => $cart_item['quantity'],
                                                            'max_value'    => $_product->get_max_purchase_quantity(),
                                                            'min_value'    => '1',
                                                            'product_name' => $_product->get_name(),
                                                            'classes'      => array( 'input-text', 'qty', 'text', 'cart-quantity-input' ),
                                                        ),
                                                        $_product,
                                                        false
                                                    );
                                                }

                                                echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // PHPCS: XSS ok.
                                                ?>
                                                
                                                <!-- Quantity buttons -->
                                                <div class="quantity-buttons">
                                                    <button type="button" class="qty-btn qty-minus" data-cart-key="<?php echo esc_attr( $cart_item_key ); ?>">−</button>
                                                    <button type="button" class="qty-btn qty-plus" data-cart-key="<?php echo esc_attr( $cart_item_key ); ?>">+</button>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Product Subtotal -->
                                        <td class="product-subtotal" data-title="<?php esc_attr_e( 'Subtotal', 'woocommerce' ); ?>">
                                            <div class="subtotal-wrapper">
                                                <?php
                                                    echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
                                                ?>
                                            </div>
                                        </td>

                                        <!-- Remove Product -->
                                        <td class="product-remove">
                                            <div class="remove-wrapper">
                                                <?php
                                                    echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                                        'woocommerce_cart_item_remove_link',
                                                        sprintf(
                                                            '<a href="%s" class="remove confirm-remove" aria-label="%s" data-product_id="%s" data-product_sku="%s" data-product-name="%s">🗑️</a>',
                                                            esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                                                            esc_html__( 'Remove this item', 'woocommerce' ),
                                                            esc_attr( $product_id ),
                                                            esc_attr( $_product->get_sku() ),
                                                            esc_attr( $_product->get_name() )
                                                        ),
                                                        $cart_item_key
                                                    );
                                                ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>

                            <?php do_action( 'woocommerce_cart_contents' ); ?>

                            <!-- Cart Actions Row -->
                            <tr class="cart-actions-row">
                                <td colspan="6" class="actions">
                                    <div class="cart-actions">
                                        <?php if ( wc_coupons_enabled() ) { ?>
                                        <div class="coupon">
                                            <label for="coupon_code">🎟️ Mã giảm giá:</label>
                                            <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="Nhập mã giảm giá" />
                                            <button type="submit" class="button coupon-btn" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>">Áp dụng</button>
                                        </div>
                                        <?php } ?>

                                        <div class="cart-buttons">
                                            <button type="submit" class="button update-cart" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>" disabled>
                                                🔄 Cập nhật giỏ hàng
                                            </button>
                                            
                                            <a href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>" class="button continue-shopping">
                                                🛒 Tiếp tục mua sắm
                                            </a>
                                        </div>
                                    </div>

                                    <?php do_action( 'woocommerce_cart_actions' ); ?>
                                    <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
                                </td>
                            </tr>

                            <?php do_action( 'woocommerce_after_cart_contents' ); ?>
                        </tbody>
                    </table>
                </div>
            </div>

        <?php else : ?>
            
            <!-- Empty Cart -->
            <div class="cart-empty">
                <div class="empty-cart-icon">🛒</div>
                <h2>Giỏ hàng của bạn đang trống</h2>
                <p>Hãy thêm sản phẩm vào giỏ hàng để tiếp tục mua sắm</p>
                
                <div class="empty-cart-actions">
                    <a href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>" class="button shop-now">
                        🛒 Mua sắm ngay
                    </a>
                </div>

                <!-- Featured Products -->
                <div class="suggested-products">
                    <h3>🌟 Sản phẩm gợi ý</h3>
                    <div class="suggested-grid">
                        <?php
                        if (class_exists('WooCommerce')) {
                            $featured_products = wc_get_products([
                                'limit' => 4,
                                'orderby' => 'popularity',
                                'status' => 'publish'
                            ]);

                            foreach ($featured_products as $product) :
                            ?>
                                <div class="suggested-item">
                                    <a href="<?php echo get_permalink($product->get_id()); ?>">
                                        <?php echo $product->get_image('thumbnail'); ?>
                                        <h4><?php echo $product->get_name(); ?></h4>
                                        <div class="price"><?php echo $product->get_price_html(); ?></div>
                                    </a>
                                </div>
                            <?php endforeach;
                        }
                        ?>
                    </div>
                </div>
            </div>

        <?php endif; ?>

        <?php do_action( 'woocommerce_after_cart_table' ); ?>
    </form>

    <?php if ( WC()->cart->get_cart_contents_count() > 0 ) : ?>
        <!-- Cart Totals -->
        <div class="cart-totals-section">
            <div class="cart-collaterals">
                <?php
                    /**
                     * Cart collaterals hook.
                     *
                     * @hooked woocommerce_cross_sell_display
                     * @hooked woocommerce_cart_totals - 10
                     */
                    do_action( 'woocommerce_cart_collaterals' );
                ?>
            </div>
        </div>

        <!-- Security & Trust -->
        <div class="trust-section">
            <h3>🔒 Mua sắm an toàn</h3>
            <div class="trust-items">
                <div class="trust-item">
                    <div class="trust-icon">🛡️</div>
                    <div class="trust-info">
                        <h4>Bảo mật SSL</h4>
                        <p>Thông tin thanh toán được mã hóa</p>
                    </div>
                </div>
                <div class="trust-item">
                    <div class="trust-icon">🔄</div>
                    <div class="trust-info">
                        <h4>Đổi trả 7 ngày</h4>
                        <p>Miễn phí đổi trả trong 7 ngày</p>
                    </div>
                </div>
                <div class="trust-item">
                    <div class="trust-icon">🚚</div>
                    <div class="trust-info">
                        <h4>Giao hàng nhanh</h4>
                        <p>Giao hàng trong 24h tại TP.HCM</p>
                    </div>
                </div>
                <div class="trust-item">
                    <div class="trust-icon">💬</div>
                    <div class="trust-info">
                        <h4>Hỗ trợ 24/7</h4>
                        <p>Luôn sẵn sàng hỗ trợ khách hàng</p>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Loading Overlay -->
<div id="cart-loading-overlay" style="display: none;">
    <div class="loading-spinner">
        <div class="spinner"></div>
        <p>Đang cập nhật giỏ hàng...</p>
    </div>
</div>

<!-- Confirmation Modal -->
<div id="remove-confirmation-modal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>🗑️ Xác nhận xóa sản phẩm</h3>
        </div>
        <div class="modal-body">
            <p>Bạn có chắc chắn muốn xóa "<span id="product-name-to-remove"></span>" khỏi giỏ hàng?</p>
        </div>
        <div class="modal-footer">
            <button id="confirm-remove-btn" class="button button-primary">Xóa</button>
            <button id="cancel-remove-btn" class="button button-secondary">Hủy</button>
        </div>
    </div>
</div>

<style>
/* Cart Page Styles */
.cart-page-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.cart-header {
    text-align: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 40px 20px;
    border-radius: 15px;
    margin-bottom: 40px;
}

.cart-header h1 {
    font-size: 2.5rem;
    margin-bottom: 10px;
}

.cart-header p {
    font-size: 1.2rem;
    opacity: 0.9;
}

.cart-items-section h2 {
    color: #333;
    margin-bottom: 25px;
    font-size: 1.8rem;
    text-align: center;
}

.cart-table-wrapper {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    margin-bottom: 30px;
}

.shop_table {
    width: 100%;
    border-collapse: collapse;
}

.shop_table th,
.shop_table td {
    padding: 20px 15px;
    text-align: left;
    border-bottom: 1px solid #eee;
}

.shop_table th {
    background: #f8f9fa;
    font-weight: 700;
    color: #333;
    text-transform: uppercase;
    font-size: 12px;
    letter-spacing: 1px;
}

.product-thumbnail img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 10px;
}

.product-info {
    min-width: 250px;
}

.product-title {
    color: #333;
    text-decoration: none;
    font-weight: 600;
    font-size: 16px;
    line-height: 1.4;
}

.product-title:hover {
    color: #007cba;
}

.product-specs-mini {
    margin-top: 8px;
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.product-specs-mini .spec {
    background: #f1f5f9;
    color: #64748b;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 600;
}

.price-wrapper,
.subtotal-wrapper {
    font-size: 18px;
    font-weight: 700;
    color: #e74c3c;
}

.quantity-wrapper {
    display: flex;
    align-items: center;
    gap: 10px;
}

.quantity-wrapper input {
    width: 80px;
    padding: 8px 12px;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    text-align: center;
    font-size: 16px;
    font-weight: 600;
}

.quantity-wrapper input:focus {
    outline: none;
    border-color: #007cba;
}

.quantity-buttons {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.qty-btn {
    width: 25px;
    height: 25px;
    border: 1px solid #e2e8f0;
    background: #f8fafc;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    font-weight: bold;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s;
}

.qty-btn:hover {
    background: #007cba;
    color: white;
    border-color: #007cba;
}

.qty-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.remove a {
    font-size: 20px;
    text-decoration: none;
    padding: 8px;
    border-radius: 50%;
    background: #fee2e2;
    color: #dc2626;
    transition: all 0.3s;
    display: inline-block;
}

.remove a:hover {
    background: #dc2626;
    color: white;
    transform: scale(1.1);
}

.cart-actions-row td {
    padding: 30px 20px;
    background: #f8fafc;
}

.cart-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
}

.coupon {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

.coupon label {
    font-weight: 600;
    color: #333;
}

.coupon input {
    padding: 10px 15px;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    font-size: 14px;
    min-width: 200px;
}

.coupon input:focus {
    outline: none;
    border-color: #007cba;
}

.coupon-btn {
    background: #f59e0b;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.3s;
}

.coupon-btn:hover {
    background: #d97706;
}

.cart-buttons {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

.update-cart {
    background: #6366f1;
    color: white;
    padding: 12px 24px;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.update-cart:hover:not(:disabled) {
    background: #4f46e5;
}

.update-cart:disabled {
    background: #9ca3af;
    cursor: not-allowed;
}

.continue-shopping {
    background: #10b981;
    color: white !important;
    padding: 12px 24px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    transition: background 0.3s;
}

.continue-shopping:hover {
    background: #059669;
}

/* Loading Overlay */
#cart-loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
}

.loading-spinner {
    background: white;
    padding: 30px;
    border-radius: 15px;
    text-align: center;
    box-shadow: 0 10px 25px rgba(0,0,0,0.3);
}

.spinner {
    width: 40px;
    height: 40px;
    border: 4px solid #f3f3f3;
    border-top: 4px solid #007cba;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 0 auto 15px auto;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.loading-spinner p {
    color: #333;
    font-weight: 600;
    margin: 0;
}

/* Modal Styles */
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    z-index: 10000;
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-content {
    background: white;
    border-radius: 15px;
    max-width: 500px;
    width: 90%;
    box-shadow: 0 10px 25px rgba(0,0,0,0.3);
    animation: modalSlideIn 0.3s ease-out;
}

@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: translateY(-50px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.modal-header {
    padding: 20px 30px;
    border-bottom: 1px solid #e2e8f0;
}

.modal-header h3 {
    margin: 0;
    color: #333;
    font-size: 1.5rem;
}

.modal-body {
    padding: 30px;
}

.modal-body p {
    margin: 0;
    color: #666;
    font-size: 16px;
    line-height: 1.6;
}

.modal-footer {
    padding: 20px 30px;
    border-top: 1px solid #e2e8f0;
    display: flex;
    gap: 15px;
    justify-content: flex-end;
}

.button-primary {
    background: #dc2626;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.3s;
}

.button-primary:hover {
    background: #b91c1c;
}

.button-secondary {
    background: #6b7280;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.3s;
}

.button-secondary:hover {
    background: #4b5563;
}

/* Empty Cart */
.cart-empty {
    text-align: center;
    padding: 60px 20px;
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.empty-cart-icon {
    font-size: 80px;
    margin-bottom: 20px;
    opacity: 0.5;
}

.cart-empty h2 {
    color: #333;
    margin-bottom: 10px;
    font-size: 2rem;
}

.cart-empty p {
    color: #666;
    font-size: 1.1rem;
    margin-bottom: 30px;
}

.shop-now {
    background: #10b981;
    color: white !important;
    padding: 15px 30px;
    border-radius: 10px;
    text-decoration: none;
    font-size: 18px;
    font-weight: 600;
    display: inline-block;
    transition: all 0.3s;
}

.shop-now:hover {
    background: #059669;
    transform: translateY(-2px);
}

.suggested-products {
    margin-top: 50px;
}

.suggested-products h3 {
    color: #333;
    margin-bottom: 25px;
    font-size: 1.5rem;
}

.suggested-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}

.suggested-item {
    background: #f8fafc;
    border-radius: 12px;
    padding: 20px;
    text-align: center;
    transition: transform 0.3s, box-shadow 0.3s;
}

.suggested-item:hover {
   transform: translateY(-3px);
   box-shadow: 0 6px 12px rgba(0,0,0,0.1);
}

.suggested-item a {
   text-decoration: none;
   color: inherit;
}

.suggested-item img {
   width: 80px;
   height: 80px;
   object-fit: cover;
   border-radius: 8px;
   margin-bottom: 10px;
}

.suggested-item h4 {
   color: #333;
   margin: 10px 0;
   font-size: 14px;
   line-height: 1.3;
}

.suggested-item .price {
   color: #e74c3c;
   font-weight: bold;
   font-size: 14px;
}

/* Cart Totals */
.cart-totals-section {
   margin: 40px 0;
}

/* Trust Section */
.trust-section {
   background: white;
   border-radius: 15px;
   padding: 30px;
   margin: 40px 0;
   box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.trust-section h3 {
   text-align: center;
   color: #333;
   margin-bottom: 30px;
   font-size: 1.5rem;
}

.trust-items {
   display: grid;
   grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
   gap: 25px;
}

.trust-item {
   display: flex;
   align-items: center;
   gap: 15px;
   padding: 20px;
   background: #f8fafc;
   border-radius: 12px;
   transition: transform 0.3s;
}

.trust-item:hover {
   transform: translateY(-2px);
}

.trust-icon {
   font-size: 32px;
   min-width: 50px;
}

.trust-info h4 {
   color: #333;
   margin-bottom: 5px;
   font-size: 16px;
}

.trust-info p {
   color: #666;
   font-size: 14px;
   margin: 0;
}

/* Responsive */
@media (max-width: 768px) {
   .cart-page-container {
       padding: 15px;
   }
   
   .cart-header h1 {
       font-size: 2rem;
   }
   
   .cart-table-wrapper {
       overflow-x: auto;
   }
   
   .shop_table {
       min-width: 600px;
   }
   
   .shop_table th,
   .shop_table td {
       padding: 15px 10px;
       font-size: 14px;
   }
   
   .product-thumbnail img {
       width: 60px;
       height: 60px;
   }
   
   .product-info {
       min-width: 200px;
   }
   
   .cart-actions {
       flex-direction: column;
       align-items: stretch;
   }
   
   .coupon {
       justify-content: center;
   }
   
   .cart-buttons {
       justify-content: center;
   }
   
   .trust-items {
       grid-template-columns: 1fr;
   }
   
   .suggested-grid {
       grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
   }
   
   .modal-content {
       width: 95%;
   }
   
   .modal-header,
   .modal-body,
   .modal-footer {
       padding: 20px;
   }
   
   .modal-footer {
       flex-direction: column;
   }
}

@media (max-width: 480px) {
   .cart-header {
       padding: 30px 15px;
   }
   
   .cart-header h1 {
       font-size: 1.8rem;
   }
   
   .coupon input {
       min-width: 150px;
   }
   
   .trust-item {
       flex-direction: column;
       text-align: center;
   }
   
   .quantity-wrapper {
       flex-direction: column;
       gap: 5px;
   }
   
   .quantity-buttons {
       flex-direction: row;
       justify-content: center;
   }
}

/* Cart changed indicator */
.cart-changed {
   background-color: #fef3c7 !important;
   transition: background-color 0.3s ease;
}

.cart-changed .update-cart {
   background: #f59e0b !important;
   animation: pulse 1s infinite;
}

@keyframes pulse {
   0%, 100% { opacity: 1; }
   50% { opacity: 0.7; }
}
</style>

<script>
jQuery(document).ready(function($) {
   let cartUpdateTimeout;
   let removeUrl = '';
   let productName = '';
   
   // Quantity change handlers
   $('.cart-quantity-input').on('input change', function() {
       handleQuantityChange($(this));
   });
   
   // Plus/Minus button handlers
   $('.qty-plus').on('click', function() {
       const cartKey = $(this).data('cart-key');
       const input = $(`input[name="cart[${cartKey}][qty]"]`);
       const currentVal = parseInt(input.val()) || 1;
       const maxVal = parseInt(input.attr('max')) || 999;
       
       if (currentVal < maxVal) {
           input.val(currentVal + 1);
           handleQuantityChange(input);
       }
   });
   
   $('.qty-minus').on('click', function() {
       const cartKey = $(this).data('cart-key');
       const input = $(`input[name="cart[${cartKey}][qty]"]`);
       const currentVal = parseInt(input.val()) || 1;
       const minVal = parseInt(input.attr('min')) || 1;
       
       if (currentVal > minVal) {
           input.val(currentVal - 1);
           handleQuantityChange(input);
       }
   });
   
   function handleQuantityChange($input) {
       // Validate quantity
       const val = parseInt($input.val());
       const min = parseInt($input.attr('min')) || 1;
       const max = parseInt($input.attr('max')) || 999;
       
       if (val < min) $input.val(min);
       if (val > max) $input.val(max);
       
       // Mark cart as changed
       const $row = $input.closest('tr');
       $row.addClass('cart-changed');
       
       // Enable update button
       $('.update-cart').prop('disabled', false);
       
       // Auto-update after 2 seconds of no changes
       clearTimeout(cartUpdateTimeout);
       cartUpdateTimeout = setTimeout(function() {
           updateCartAjax();
       }, 2000);
   }
   
   // Manual update cart
   $('.update-cart').on('click', function(e) {
       e.preventDefault();
       updateCartAjax();
   });
   
   function updateCartAjax() {
       showLoading();
       
       // Get all quantities
       const cartData = {};
       $('.cart-quantity-input').each(function() {
           const name = $(this).attr('name');
           const value = $(this).val();
           if (name && value) {
               cartData[name] = value;
           }
       });
       
       // Add other form data
       cartData['update_cart'] = '1';
       cartData['woocommerce-cart-nonce'] = $('input[name="woocommerce-cart-nonce"]').val();
       
       $.ajax({
           url: wc_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'update_cart'),
           type: 'POST',
           data: cartData,
           success: function(response) {
               if (response && !response.error) {
                   // Reload page to show updated cart
                   window.location.reload();
               } else {
                   hideLoading();
                   alert('Có lỗi xảy ra khi cập nhật giỏ hàng. Vui lòng thử lại.');
               }
           },
           error: function() {
               hideLoading();
               alert('Có lỗi xảy ra khi cập nhật giỏ hàng. Vui lòng thử lại.');
           }
       });
   }
   
   // Remove item confirmation
   $('.confirm-remove').on('click', function(e) {
       e.preventDefault();
       
       removeUrl = $(this).attr('href');
       productName = $(this).data('product-name') || 'sản phẩm này';
       
       $('#product-name-to-remove').text(productName);
       $('#remove-confirmation-modal').show();
   });
   
   // Confirm remove
   $('#confirm-remove-btn').on('click', function() {
       $('#remove-confirmation-modal').hide();
       showLoading();
       window.location.href = removeUrl;
   });
   
   // Cancel remove
   $('#cancel-remove-btn').on('click', function() {
       $('#remove-confirmation-modal').hide();
       removeUrl = '';
       productName = '';
   });
   
   // Close modal when clicking outside
   $('#remove-confirmation-modal').on('click', function(e) {
       if (e.target === this) {
           $(this).hide();
       }
   });
   
   // Coupon code functionality
   $('#coupon_code').on('keypress', function(e) {
       if (e.which == 13) { // Enter key
           e.preventDefault();
           $(this).siblings('.coupon-btn').click();
       }
   });
   
   $('.coupon-btn').on('click', function() {
       const couponCode = $('#coupon_code').val().trim();
       if (!couponCode) {
           alert('Vui lòng nhập mã giảm giá');
           return false;
       }
       
       showLoading();
       return true;
   });
   
   // Loading functions
   function showLoading() {
       $('#cart-loading-overlay').show();
   }
   
   function hideLoading() {
       $('#cart-loading-overlay').hide();
   }
   
   // Keyboard navigation for quantity inputs
   $('.cart-quantity-input').on('keydown', function(e) {
       const $input = $(this);
       const currentVal = parseInt($input.val()) || 1;
       
       // Arrow up = increase quantity
       if (e.keyCode === 38) {
           e.preventDefault();
           const maxVal = parseInt($input.attr('max')) || 999;
           if (currentVal < maxVal) {
               $input.val(currentVal + 1);
               handleQuantityChange($input);
           }
       }
       
       // Arrow down = decrease quantity  
       if (e.keyCode === 40) {
           e.preventDefault();
           const minVal = parseInt($input.attr('min')) || 1;
           if (currentVal > minVal) {
               $input.val(currentVal - 1);
               handleQuantityChange($input);
           }
       }
       
       // Enter = update cart
       if (e.keyCode === 13) {
           e.preventDefault();
           updateCartAjax();
       }
   });
   
   // Prevent form submission on Enter in quantity inputs
   $('.cart-quantity-input').on('keypress', function(e) {
       if (e.keyCode === 13) {
           e.preventDefault();
       }
   });
   
   // Initialize quantity input values
   $('.cart-quantity-input').each(function() {
       const $input = $(this);
       const val = parseInt($input.val());
       const min = parseInt($input.attr('min')) || 1;
       
       if (val < min) {
           $input.val(min);
       }
   });
   
   // Auto-save cart state
   function saveCartState() {
       const cartState = {};
       $('.cart-quantity-input').each(function() {
           const cartKey = $(this).attr('name').match(/cart\[([^\]]+)\]/)[1];
           cartState[cartKey] = $(this).val();
       });
       sessionStorage.setItem('phonestore_cart_state', JSON.stringify(cartState));
   }
   
   // Save state on quantity change
   $('.cart-quantity-input').on('change', saveCartState);
   
   // Check for cart messages and scroll to them
   if ($('.woocommerce-message, .woocommerce-error, .woocommerce-info').length > 0) {
       $('html, body').animate({
           scrollTop: $('.woocommerce-message, .woocommerce-error, .woocommerce-info').first().offset().top - 100
       }, 800);
   }
   
   // Enhanced error handling
   $(document).ajaxError(function(event, xhr, settings) {
       hideLoading();
       if (settings.url && settings.url.includes('update_cart')) {
           alert('Không thể cập nhật giỏ hàng. Vui lòng kiểm tra kết nối internet và thử lại.');
       }
   });
   
   // Disable submit button during processing
   $('form.woocommerce-cart-form').on('submit', function() {
       showLoading();
       $('.update-cart, .coupon-btn').prop('disabled', true);
   });
   
   // Re-enable buttons if form submission fails
   setTimeout(function() {
       hideLoading();
       $('.update-cart, .coupon-btn').prop('disabled', false);
   }, 30000); // 30 second timeout
});

// Add cart functionality when WooCommerce is loaded
document.addEventListener('DOMContentLoaded', function() {
   // Ensure WooCommerce cart fragments work
   if (typeof wc_cart_fragments_params !== 'undefined') {
       jQuery(document.body).trigger('wc_fragment_refresh');
   }
});
</script>

<?php do_action( 'woocommerce_after_cart' ); ?>