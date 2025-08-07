<?php
/*
File: wp-content/themes/phonestore-theme/woocommerce/myaccount/my-account.php
Custom My Account Page Template
*/

defined( 'ABSPATH' ) || exit;

// Check if user is logged in
if (!is_user_logged_in()) {
    echo do_shortcode('[woocommerce_my_account]');
    return;
}

$current_user = wp_get_current_user();
$customer = new WC_Customer($current_user->ID);
?>

<div class="container">
    <div class="account-page-header">
        <h1>👤 Tài Khoản Của Tôi</h1>
        <p>Xin chào <strong><?php echo $current_user->display_name; ?></strong>, quản lý thông tin tài khoản và đơn hàng của bạn</p>
    </div>
    
    <div class="account-content">
        <!-- Quick Stats -->
        <div class="account-stats">
            <div class="stat-item">
                <div class="stat-icon">📦</div>
                <div class="stat-info">
                    <h3><?php echo wc_get_customer_order_count($current_user->ID); ?></h3>
                    <p>Đơn hàng</p>
                </div>
            </div>
            
            <div class="stat-item">
                <div class="stat-icon">💰</div>
                <div class="stat-info">
                    <h3><?php echo wc_price(wc_get_customer_total_spent($current_user->ID)); ?></h3>
                    <p>Tổng chi tiêu</p>
                </div>
            </div>
            
            <div class="stat-item">
                <div class="stat-icon">📅</div>
                <div class="stat-info">
                    <h3><?php echo date('d/m/Y', strtotime($current_user->user_registered)); ?></h3>
                    <p>Ngày tham gia</p>
                </div>
            </div>
            
            <div class="stat-item">
                <div class="stat-icon">🏆</div>
                <div class="stat-info">
                    <?php 
                    $total_spent = wc_get_customer_total_spent($current_user->ID);
                    $level = 'Thành viên mới';
                    if ($total_spent >= 100000000) $level = 'Kim cương';
                    elseif ($total_spent >= 50000000) $level = 'Vàng';
                    elseif ($total_spent >= 10000000) $level = 'Bạc';
                    elseif ($total_spent >= 1000000) $level = 'Đồng';
                    ?>
                    <h3><?php echo $level; ?></h3>
                    <p>Hạng thành viên</p>
                </div>
            </div>
        </div>
        
        <!-- Main Account Info -->
        <div class="account-main">
            <!-- Personal Information -->
            <div class="account-section">
                <div class="section-header">
                    <h2>👤 Thông Tin Cá Nhân</h2>
                    <a href="<?php echo wc_get_account_endpoint_url('edit-account'); ?>" class="edit-btn">✏️ Chỉnh sửa</a>
                </div>
                
                <div class="info-grid">
                    <div class="info-item">
                        <label>📧 Email:</label>
                        <span><?php echo $current_user->user_email; ?></span>
                    </div>
                    
                    <div class="info-item">
                        <label>👤 Tên hiển thị:</label>
                        <span><?php echo $current_user->display_name ?: 'Chưa cập nhật'; ?></span>
                    </div>
                    
                    <div class="info-item">
                        <label>🏷️ Tên đăng nhập:</label>
                        <span><?php echo $current_user->user_login; ?></span>
                    </div>
                    
                    <div class="info-item">
                        <label>👤 Họ:</label>
                        <span><?php echo get_user_meta($current_user->ID, 'last_name', true) ?: 'Chưa cập nhật'; ?></span>
                    </div>
                    
                    <div class="info-item">
                        <label>👤 Tên:</label>
                        <span><?php echo get_user_meta($current_user->ID, 'first_name', true) ?: 'Chưa cập nhật'; ?></span>
                    </div>
                    
                    <div class="info-item">
                        <label>📱 Số điện thoại:</label>
                        <span><?php echo $customer->get_billing_phone() ?: 'Chưa cập nhật'; ?></span>
                    </div>
                    
                    <div class="info-item">
                        <label>🎂 Ngày sinh:</label>
                        <span><?php echo get_user_meta($current_user->ID, 'birth_date', true) ?: 'Chưa cập nhật'; ?></span>
                    </div>
                    
                    <div class="info-item">
                        <label>⚥ Giới tính:</label>
                        <span><?php echo get_user_meta($current_user->ID, 'gender', true) ?: 'Chưa cập nhật'; ?></span>
                    </div>
                    
                    <div class="info-item">
                        <label>🌐 Website:</label>
                        <span><?php echo $current_user->user_url ?: 'Chưa cập nhật'; ?></span>
                    </div>
                    
                    <div class="info-item">
                        <label>📝 Mô tả:</label>
                        <span><?php echo get_user_meta($current_user->ID, 'description', true) ?: 'Chưa cập nhật'; ?></span>
                    </div>
                    
                    <div class="info-item">
                        <label>📅 Ngày đăng ký:</label>
                        <span><?php echo date('d/m/Y H:i:s', strtotime($current_user->user_registered)); ?></span>
                    </div>
                    
                    <div class="info-item">
                        <label>🔐 Quyền:</label>
                        <span><?php echo implode(', ', $current_user->roles); ?></span>
                    </div>
                </div>
            </div>
            
            <!-- Billing Address -->
            <div class="account-section">
                <div class="section-header">
                    <h2>🏠 Địa Chỉ Thanh Toán</h2>
                    <a href="<?php echo wc_get_account_endpoint_url('edit-address'); ?>/billing" class="edit-btn">✏️ Chỉnh sửa</a>
                </div>
                
                <div class="address-info">
                    <?php
                    $billing_address = array(
                        'Họ tên' => $customer->get_billing_first_name() . ' ' . $customer->get_billing_last_name(),
                        'Công ty' => $customer->get_billing_company(),
                        'Địa chỉ 1' => $customer->get_billing_address_1(),
                        'Địa chỉ 2' => $customer->get_billing_address_2(),
                        'Thành phố' => $customer->get_billing_city(),
                        'Tỉnh/Thành' => $customer->get_billing_state(),
                        'Mã bưu điện' => $customer->get_billing_postcode(),
                        'Quốc gia' => WC()->countries->countries[$customer->get_billing_country()] ?? $customer->get_billing_country(),
                        'Email' => $customer->get_billing_email(),
                        'Điện thoại' => $customer->get_billing_phone()
                    );
                    
                    $has_billing = false;
                    foreach ($billing_address as $key => $value) {
                        if (!empty($value)) {
                            $has_billing = true;
                            echo '<div class="address-item">';
                            echo '<label>' . $key . ':</label>';
                            echo '<span>' . esc_html($value) . '</span>';
                            echo '</div>';
                        }
                    }
                    
                    if (!$has_billing) {
                        echo '<p class="no-address">Chưa có địa chỉ thanh toán. <a href="' . wc_get_account_endpoint_url('edit-address') . '/billing">Thêm địa chỉ</a></p>';
                    }
                    ?>
                </div>
            </div>
            
            <!-- Shipping Address -->
            <div class="account-section">
                <div class="section-header">
                    <h2>🚚 Địa Chỉ Giao Hàng</h2>
                    <a href="<?php echo wc_get_account_endpoint_url('edit-address'); ?>/shipping" class="edit-btn">✏️ Chỉnh sửa</a>
                </div>
                
                <div class="address-info">
                    <?php
                    $shipping_address = array(
                        'Họ tên' => $customer->get_shipping_first_name() . ' ' . $customer->get_shipping_last_name(),
                        'Công ty' => $customer->get_shipping_company(),
                        'Địa chỉ 1' => $customer->get_shipping_address_1(),
                        'Địa chỉ 2' => $customer->get_shipping_address_2(),
                        'Thành phố' => $customer->get_shipping_city(),
                        'Tỉnh/Thành' => $customer->get_shipping_state(),
                        'Mã bưu điện' => $customer->get_shipping_postcode(),
                        'Quốc gia' => WC()->countries->countries[$customer->get_shipping_country()] ?? $customer->get_shipping_country()
                    );
                    
                    $has_shipping = false;
                    foreach ($shipping_address as $key => $value) {
                        if (!empty($value)) {
                            $has_shipping = true;
                            echo '<div class="address-item">';
                            echo '<label>' . $key . ':</label>';
                            echo '<span>' . esc_html($value) . '</span>';
                            echo '</div>';
                        }
                    }
                    
                    if (!$has_shipping) {
                        echo '<p class="no-address">Chưa có địa chỉ giao hàng. <a href="' . wc_get_account_endpoint_url('edit-address') . '/shipping">Thêm địa chỉ</a></p>';
                    }
                    ?>
                </div>
            </div>
            
            <!-- Recent Orders -->
            <div class="account-section">
                <div class="section-header">
                    <h2>📦 Đơn Hàng Gần Đây</h2>
                    <a href="<?php echo wc_get_account_endpoint_url('orders'); ?>" class="view-all-btn">👀 Xem tất cả</a>
                </div>
                
                <div class="recent-orders">
                    <?php
                    $recent_orders = wc_get_orders(array(
                        'customer' => $current_user->ID,
                        'limit' => 5,
                        'orderby' => 'date',
                        'order' => 'DESC'
                    ));
                    
                    if ($recent_orders) {
                        echo '<div class="orders-table">';
                        echo '<div class="orders-header">';
                        echo '<span>Đơn hàng</span>';
                        echo '<span>Ngày</span>';
                        echo '<span>Trạng thái</span>';
                        echo '<span>Tổng tiền</span>';
                        echo '<span>Hành động</span>';
                        echo '</div>';
                        
                        foreach ($recent_orders as $order) {
                            $order_id = $order->get_id();
                            $order_date = $order->get_date_created();
                            $order_status = $order->get_status();
                            $order_total = $order->get_total();
                            
                            $status_labels = array(
                                'pending' => 'Chờ thanh toán',
                                'processing' => 'Đang xử lý',
                                'on-hold' => 'Tạm giữ',
                                'completed' => 'Hoàn thành',
                                'cancelled' => 'Đã hủy',
                                'refunded' => 'Đã hoàn tiền',
                                'failed' => 'Thất bại'
                            );
                            
                            echo '<div class="order-row">';
                            echo '<span class="order-number">#' . $order_id . '</span>';
                            echo '<span class="order-date">' . $order_date->date('d/m/Y') . '</span>';
                            echo '<span class="order-status status-' . $order_status . '">' . ($status_labels[$order_status] ?? $order_status) . '</span>';
                            echo '<span class="order-total">' . wc_price($order_total) . '</span>';
                            echo '<span class="order-actions">';
                            echo '<a href="' . $order->get_view_order_url() . '" class="view-order">👁️ Xem</a>';
                            if (in_array($order_status, array('pending', 'failed'))) {
                                echo '<a href="' . $order->get_checkout_payment_url() . '" class="pay-order">💳 Thanh toán</a>';
                            }
                            echo '</span>';
                            echo '</div>';
                        }
                        echo '</div>';
                    } else {
                        echo '<p class="no-orders">Bạn chưa có đơn hàng nào. <a href="' . get_permalink(wc_get_page_id('shop')) . '">Mua sắm ngay</a></p>';
                    }
                    ?>
                </div>
            </div>
            
            <!-- Account Activity -->
            <div class="account-section">
                <div class="section-header">
                    <h2>📊 Hoạt Động Tài Khoản</h2>
                </div>
                
                <div class="activity-info">
                    <div class="activity-item">
                        <label>🔑 Lần đăng nhập cuối:</label>
                        <span><?php echo get_user_meta($current_user->ID, 'last_login', true) ?: 'Không có thông tin'; ?></span>
                    </div>
                    
                    <div class="activity-item">
                        <label>🌐 IP đăng ký:</label>
                        <span><?php echo get_user_meta($current_user->ID, 'registration_ip', true) ?: 'Không có thông tin'; ?></span>
                    </div>
                    
                    <div class="activity-item">
                        <label>💻 Trình duyệt:</label>
                        <span><?php echo $_SERVER['HTTP_USER_AGENT'] ?? 'Không có thông tin'; ?></span>
                    </div>
                    
                    <div class="activity-item">
                        <label>🛒 Sản phẩm trong giỏ:</label>
                        <span><?php echo WC()->cart->get_cart_contents_count(); ?> sản phẩm</span>
                    </div>
                    
                    <div class="activity-item">
                        <label>❤️ Sản phẩm yêu thích:</label>
                        <span><?php echo count(get_user_meta($current_user->ID, '_wishlist', true) ?: array()); ?> sản phẩm</span>
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="account-section">
                <div class="section-header">
                    <h2>⚡ Thao Tác Nhanh</h2>
                </div>
                
                <div class="quick-actions">
                    <a href="<?php echo wc_get_account_endpoint_url('edit-account'); ?>" class="quick-action-btn">
                        <div class="action-icon">✏️</div>
                        <span>Sửa thông tin</span>
                    </a>
                    
                    <a href="<?php echo wc_get_account_endpoint_url('edit-address'); ?>" class="quick-action-btn">
                        <div class="action-icon">🏠</div>
                        <span>Sửa địa chỉ</span>
                    </a>
                    
                    <a href="<?php echo wc_get_account_endpoint_url('orders'); ?>" class="quick-action-btn">
                        <div class="action-icon">📦</div>
                        <span>Đơn hàng</span>
                    </a>
                    
                    <a href="<?php echo wc_get_cart_url(); ?>" class="quick-action-btn">
                        <div class="action-icon">🛒</div>
                        <span>Giỏ hàng</span>
                    </a>
                    
                    <a href="<?php echo get_permalink(wc_get_page_id('shop')); ?>" class="quick-action-btn">
                        <div class="action-icon">🛍️</div>
                        <span>Mua sắm</span>
                    </a>
                    
                    <a href="<?php echo wp_logout_url(home_url()); ?>" class="quick-action-btn logout">
                        <div class="action-icon">🚪</div>
                        <span>Đăng xuất</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* === ACCOUNT PAGE STYLES === */
.account-page-header {
    text-align: center;
    padding: 40px 0;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 15px;
    margin: 20px 0 30px 0;
}

.account-page-header h1 {
    font-size: 2.5rem;
    margin-bottom: 10px;
    font-weight: 800;
}

.account-page-header p {
    font-size: 1.2rem;
    opacity: 0.9;
}

/* Account Stats */
.account-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin: 30px 0;
}

.stat-item {
    background: white;
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    gap: 15px;
    transition: all 0.3s;
}

.stat-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 15px rgba(0,0,0,0.15);
}

.stat-icon {
    font-size: 32px;
    width: 60px;
    height: 60px;
    background: #f8fafc;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.stat-info h3 {
    color: #2d3748;
    font-size: 1.5rem;
    font-weight: 800;
    margin: 0 0 5px 0;
}

.stat-info p {
    color: #718096;
    margin: 0;
    font-size: 14px;
}

/* Account Sections */
.account-section {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    margin: 25px 0;
    overflow: hidden;
}

.section-header {
    background: #f8fafc;
    padding: 20px 25px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #e2e8f0;
}

.section-header h2 {
    color: #2d3748;
    font-size: 1.3rem;
    font-weight: 700;
    margin: 0;
}

.edit-btn, .view-all-btn {
    background: #4299e1;
    color: white;
    text-decoration: none;
    padding: 8px 16px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 600;
    transition: all 0.3s;
}

.edit-btn:hover, .view-all-btn:hover {
    background: #3182ce;
    transform: translateY(-1px);
}

/* Info Grid */
.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    padding: 25px;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 15px;
    background: #f8fafc;
    border-radius: 8px;
    border-left: 4px solid #e2e8f0;
}

.info-item label {
    font-weight: 600;
    color: #4a5568;
    flex: 0 0 140px;
    font-size: 14px;
}

.info-item span {
    flex: 1;
    color: #2d3748;
    text-align: right;
    word-break: break-all;
}

/* Address Info */
.address-info {
    padding: 25px;
}

.address-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid #f1f5f9;
}

.address-item:last-child {
    border-bottom: none;
}

.address-item label {
    font-weight: 600;
    color: #4a5568;
    font-size: 14px;
}

.address-item span {
    color: #2d3748;
    font-weight: 500;
}

.no-address, .no-orders {
    text-align: center;
    color: #718096;
    font-style: italic;
    padding: 40px 20px;
}

.no-address a, .no-orders a {
    color: #4299e1;
    text-decoration: none;
    font-weight: 600;
}

.no-address a:hover, .no-orders a:hover {
    text-decoration: underline;
}

/* Orders Table */
.orders-table {
    padding: 25px;
}

.orders-header {
    display: grid;
    grid-template-columns: 120px 100px 120px 120px 1fr;
    gap: 15px;
    padding: 15px 0;
    border-bottom: 2px solid #e2e8f0;
    font-weight: 700;
    color: #4a5568;
    font-size: 14px;
}

.order-row {
    display: grid;
    grid-template-columns: 120px 100px 120px 120px 1fr;
    gap: 15px;
    padding: 15px 0;
    border-bottom: 1px solid #f1f5f9;
    align-items: center;
}

.order-row:last-child {
    border-bottom: none;
}

.order-number {
    font-weight: 700;
    color: #2d3748;
}

.order-date {
    color: #718096;
    font-size: 14px;
}

.order-status {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
    text-align: center;
}

.status-pending { background: #fef5e7; color: #744210; }
.status-processing { background: #e6fffa; color: #234e52; }
.status-completed { background: #f0fff4; color: #22543d; }
.status-cancelled { background: #fed7e2; color: #702459; }
.status-on-hold { background: #edf2f7; color: #2d3748; }

.order-total {
    font-weight: 800;
    color: #e53e3e;
}

.order-actions {
    display: flex;
    gap: 8px;
}

.view-order, .pay-order {
    padding: 6px 12px;
    border-radius: 4px;
    text-decoration: none;
    font-size: 12px;
    font-weight: 600;
    transition: all 0.3s;
}

.view-order {
    background: #e6fffa;
    color: #234e52;
}

.view-order:hover {
    background: #b2f5ea;
}

.pay-order {
    background: #fef5e7;
    color: #744210;
}

.pay-order:hover {
    background: #f6e05e;
}

/* Activity Info */
.activity-info {
    padding: 25px;
}

.activity-item {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 12px 0;
    border-bottom: 1px solid #f1f5f9;
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-item label {
    font-weight: 600;
    color: #4a5568;
    font-size: 14px;
    flex: 0 0 200px;
}

.activity-item span {
    flex: 1;
    color: #2d3748;
    text-align: right;
    word-break: break-all;
    font-size: 14px;
}

/* Quick Actions */
.quick-actions {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 15px;
    padding: 25px;
}

.quick-action-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 20px;
    background: #f8fafc;
    border-radius: 12px;
    text-decoration: none;
    color: #4a5568;
    transition: all 0.3s;
    border: 2px solid transparent;
}

.quick-action-btn:hover {
    background: #e2e8f0;
    transform: translateY(-2px);
    border-color: #cbd5e0;
}

.quick-action-btn.logout:hover {
    background: #fed7e2;
    border-color: #f687b3;
    color: #702459;
}

.action-icon {
    font-size: 24px;
    margin-bottom: 8px;
}

.quick-action-btn span {
    font-size: 14px;
    font-weight: 600;
}

/* Responsive */
@media (max-width: 768px) {
    .account-page-header h1 {
        font-size: 2rem;
    }
    
    .account-stats {
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 15px;
    }
    
    .stat-item {
        padding: 20px;
        flex-direction: column;
        text-align: center;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
        gap: 15px;
        padding: 20px;
    }
    
    .info-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }
    
    .info-item label {
        flex: none;
    }
    
    .info-item span {
        text-align: left;
    }
    
    .orders-header,
    .order-row {
        grid-template-columns: 1fr;
        gap: 8px;
    }
    
    .orders-header span,
    .order-row span {
        display: flex;
        justify-content: space-between;
    }
    
    .orders-header span:before,
    .order-row span:before {
        content: attr(data-label);
        font-weight: 600;
        color: #4a5568;
    }
    
    .quick-actions {
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 10px;
    }
    
    .quick-action-btn {
        padding: 15px 10px;
    }
}

@media (max-width: 480px) {
    .section-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
    
    .activity-item,
    .address-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 5px;
    }
    
    .activity-item span,
    .address-item span {
        text-align: left;
    }
}
</style>

<?php
// Track last login
update_user_meta($current_user->ID, 'last_login', current_time('d/m/Y H:i:s'));
?>