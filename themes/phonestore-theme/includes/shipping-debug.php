<?php
// File debug để kiểm tra shipping
if (!defined('ABSPATH')) {
    exit;
}

// Thêm logging cho shipping
function phonestore_debug_shipping($package) {
    if (defined('WP_DEBUG') && WP_DEBUG) {
        error_log('=== SHIPPING DEBUG ===');
        error_log('Province: ' . ($package['destination']['state'] ?? 'empty'));
        error_log('City: ' . ($package['destination']['city'] ?? 'empty'));
        error_log('Address: ' . ($package['destination']['address_1'] ?? 'empty'));
        error_log('Package: ' . print_r($package['destination'], true));
    }
}
add_action('woocommerce_cart_calculate_fees', function() {
    if (!empty(WC()->cart->get_shipping_packages())) {
        foreach (WC()->cart->get_shipping_packages() as $package) {
            phonestore_debug_shipping($package);
        }
    }
});