<?php
/**
 * My Account page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/my-account.php.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

defined( 'ABSPATH' ) || exit;

// Include header
get_header();
?>

<div class="container">
    <div class="woocommerce-account-wrapper">
        <?php if ( ! is_user_logged_in() ) : ?>
            <div class="account-login-wrapper">
                <div class="login-header">
                    <h1>🔐 Đăng nhập tài khoản</h1>
                    <p>Vui lòng đăng nhập để xem thông tin tài khoản của bạn</p>
                </div>
                <?php wc_get_template( 'myaccount/form-login.php' ); ?>
            </div>
        <?php else : ?>
            <div class="account-page-header">
                <h1>👤 Tài Khoản Của Tôi</h1>
                <p>Xin chào <strong><?php echo wp_get_current_user()->display_name; ?></strong>, quản lý thông tin tài khoản và đơn hàng của bạn</p>
            </div>
            
            <div class="account-content-wrapper">
                <?php
                /**
                 * My Account navigation.
                 */
                do_action( 'woocommerce_account_navigation' );
                ?>

                <div class="woocommerce-MyAccount-content">
                    <?php
                        /**
                         * My Account content.
                         */
                        do_action( 'woocommerce_account_content' );
                    ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
/* Account Page Wrapper */
.woocommerce-account-wrapper {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

/* Login Header */
.login-header {
    text-align: center;
    padding: 40px 0;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 15px;
    margin-bottom: 30px;
}

.login-header h1 {
    font-size: 2.2rem;
    margin-bottom: 10px;
}

.login-header p {
    font-size: 1.1rem;
    opacity: 0.9;
}

/* Account Page Header */
.account-page-header {
    text-align: center;
    padding: 40px 0;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 15px;
    margin-bottom: 30px;
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

/* Account Content */
.account-content-wrapper {
    display: grid;
    grid-template-columns: 250px 1fr;
    gap: 30px;
    align-items: start;
}

/* Navigation Styles */
.woocommerce-MyAccount-navigation {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    overflow: hidden;
}

.woocommerce-MyAccount-navigation ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.woocommerce-MyAccount-navigation ul li {
    border-bottom: 1px solid #f1f5f9;
}

.woocommerce-MyAccount-navigation ul li:last-child {
    border-bottom: none;
}

.woocommerce-MyAccount-navigation ul li a {
    display: block;
    padding: 15px 20px;
    color: #4a5568;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s;
    font-size: 14px;
}

.woocommerce-MyAccount-navigation ul li.is-active a,
.woocommerce-MyAccount-navigation ul li a:hover {
    background: linear-gradient(135deg, #38a169 0%, #2f855a 100%);
    color: white;
    transform: translateX(5px);
}

/* Content Area */
.woocommerce-MyAccount-content {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    min-height: 500px;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .account-page-header h1 {
        font-size: 2rem;
    }
    
    .account-content-wrapper {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .woocommerce-MyAccount-navigation ul {
        display: flex;
        overflow-x: auto;
        white-space: nowrap;
    }
    
    .woocommerce-MyAccount-navigation ul li {
        border-bottom: none;
        border-right: 1px solid #f1f5f9;
        flex-shrink: 0;
    }
    
    .woocommerce-MyAccount-navigation ul li:last-child {
        border-right: none;
    }
    
    .woocommerce-MyAccount-navigation ul li a {
        padding: 12px 16px;
        font-size: 13px;
    }
}
</style>

<?php
// Include footer
get_footer();
?>