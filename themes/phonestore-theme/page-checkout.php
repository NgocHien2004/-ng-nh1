<?php
/*
Template Name: Checkout Page
*/

get_header();
?>

<div class="container">
    <main class="main-content">
        <?php
        if (class_exists('WooCommerce')) {
            // Include WooCommerce checkout template
            if (file_exists(get_template_directory() . '/woocommerce/checkout/form-checkout.php')) {
                // Check if cart is empty
                if (WC()->cart->is_empty()) {
                    echo '<div class="woocommerce-info">🛒 Giỏ hàng của bạn hiện tại đang trống. <a href="' . wc_get_page_permalink('shop') . '">Quay lại cửa hàng</a></div>';
                } else {
                    $checkout = WC()->checkout();
                    include get_template_directory() . '/woocommerce/checkout/form-checkout.php';
                }
            } else {
                echo do_shortcode('[woocommerce_checkout]');
            }
        } else {
            echo '<p>WooCommerce is not installed or activated.</p>';
        }
        ?>
    </main>
</div>

<?php get_footer(); ?>