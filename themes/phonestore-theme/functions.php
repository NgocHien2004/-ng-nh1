<?php
/**
 * PhoneStore Theme Functions - Products Fix
 * Version: 2.1
 */

// Enqueue styles và scripts
function phonestore_enqueue_styles() {
    wp_enqueue_style('phonestore-style', get_stylesheet_uri(), array(), '2.1.0');
    wp_enqueue_script('phonestore-script', get_template_directory_uri() . '/js/phonestore.js', array('jquery'), '2.1.0', true);
    
    // Localize script cho AJAX
    wp_localize_script('phonestore-script', 'phonestore_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('phonestore_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'phonestore_enqueue_styles', 20);

// FORCE disable ALL WooCommerce styles
add_filter('woocommerce_enqueue_styles', '__return_empty_array');

// Theme setup
function phonestore_setup_theme() {
    add_theme_support('menus');
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
    
    // Register navigation menus
    register_nav_menus(array(
        'primary' => 'Primary Menu',
        'footer' => 'Footer Menu'
    ));
}
add_action('after_setup_theme', 'phonestore_setup_theme');

// WooCommerce support
function phonestore_add_woocommerce_support() {
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
    
    // Force show products on shop page
    add_theme_support('wc-product-gallery-slider');
}
add_action('after_setup_theme', 'phonestore_add_woocommerce_support');

// FORCE products per page
function phonestore_products_per_page() {
    return 12;
}
add_filter('loop_shop_per_page', 'phonestore_products_per_page', 999);

// FORCE product columns
function phonestore_woocommerce_product_columns() {
    return 4;
}
add_filter('loop_shop_columns', 'phonestore_woocommerce_product_columns', 999);

// FORCE show all products - override WooCommerce visibility
function phonestore_force_show_products($query) {
    if (!is_admin() && $query->is_main_query()) {
        if (is_shop() || is_product_category() || is_product_tag()) {
            // Force query to show products
            $query->set('post_type', 'product');
            $query->set('post_status', 'publish');
            $query->set('posts_per_page', 12);
            
            // Remove all meta queries that might hide products
            $query->set('meta_query', array());
            
            // Force include all products regardless of stock status
            remove_action('woocommerce_product_query', 'wc_product_visibility_meta_query');
        }
    }
}
add_action('pre_get_posts', 'phonestore_force_show_products', 999);

// Remove WooCommerce product visibility restrictions
function phonestore_remove_product_visibility() {
    remove_action('woocommerce_product_query', 'wc_product_visibility_meta_query');
    remove_action('pre_get_posts', 'wc_product_visibility_meta_query');
}
add_action('init', 'phonestore_remove_product_visibility');

// Force all products to be visible
function phonestore_make_all_products_visible() {
    $products = get_posts(array(
        'post_type' => 'product',
        'post_status' => 'publish', 
        'posts_per_page' => -1
    ));
    
    foreach ($products as $product) {
        // Update product visibility
        update_post_meta($product->ID, '_visibility', 'visible');
        update_post_meta($product->ID, '_stock_status', 'instock');
        
        // Update WooCommerce product object
        $wc_product = wc_get_product($product->ID);
        if ($wc_product) {
            $wc_product->set_catalog_visibility('visible');
            $wc_product->set_stock_status('instock');
            $wc_product->save();
        }
    }
}

// Run once to fix existing products (comment out after running)
// add_action('wp_loaded', 'phonestore_make_all_products_visible');

// Add custom body class
function phonestore_woocommerce_body_class($classes) {
    if (is_woocommerce() || is_cart() || is_checkout() || is_account_page()) {
        $classes[] = 'phonestore-woocommerce';
    }
    if (is_shop()) {
        $classes[] = 'phonestore-shop';
    }
    return $classes;
}
add_filter('body_class', 'phonestore_woocommerce_body_class');

// FORCE WooCommerce to use our templates
function phonestore_force_woocommerce_templates($template) {
    if (is_shop() && file_exists(get_template_directory() . '/woocommerce.php')) {
        return get_template_directory() . '/woocommerce.php';
    }
    if (is_product_category() && file_exists(get_template_directory() . '/woocommerce.php')) {
        return get_template_directory() . '/woocommerce.php';
    }
    return $template;
}
add_filter('template_include', 'phonestore_force_woocommerce_templates', 999);

// Remove sidebar from shop pages
function phonestore_remove_shop_sidebar() {
    if (is_shop() || is_product_category() || is_product_tag()) {
        remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);
    }
}
add_action('wp', 'phonestore_remove_shop_sidebar');

// Custom WooCommerce wrapper
function phonestore_woocommerce_wrapper_start() {
    echo '<div class="phonestore-main-content">';
}

function phonestore_woocommerce_wrapper_end() {
    echo '</div>';
}

remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

add_action('woocommerce_before_main_content', 'phonestore_woocommerce_wrapper_start', 10);
add_action('woocommerce_after_main_content', 'phonestore_woocommerce_wrapper_end', 10);

// Remove WooCommerce breadcrumbs
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);

// Customize add to cart text
function phonestore_custom_add_to_cart_text() {
    return '🛒 Thêm vào giỏ';
}
add_filter('woocommerce_product_add_to_cart_text', 'phonestore_custom_add_to_cart_text');

// AJAX product search
function phonestore_ajax_product_search() {
    if (!wp_verify_nonce($_POST['nonce'], 'phonestore_nonce')) {
        wp_die('Security check failed');
    }
    
    $search_term = sanitize_text_field($_POST['term']);
    
    $args = array(
        'post_type' => 'product',
        'post_status' => 'publish',
        's' => $search_term,
        'posts_per_page' => 5
    );
    
    $products = get_posts($args);
    $suggestions = array();
    
    foreach ($products as $product) {
        $wc_product = wc_get_product($product->ID);
        $suggestions[] = array(
            'title' => $product->post_title,
            'url' => get_permalink($product->ID),
            'price' => $wc_product ? $wc_product->get_price_html() : 'Liên hệ',
            'image' => get_the_post_thumbnail_url($product->ID, 'thumbnail') ?: 'https://via.placeholder.com/150'
        );
    }
    
    wp_send_json_success($suggestions);
}
add_action('wp_ajax_phonestore_ajax_product_search', 'phonestore_ajax_product_search');
add_action('wp_ajax_nopriv_phonestore_ajax_product_search', 'phonestore_ajax_product_search');

// Add inline styles to fix any remaining issues
function phonestore_inline_fixes() {
    if (is_shop() || is_product_category()) {
    ?>
    <style>
    /* Force show products */
    .woocommerce .products {
        display: grid !important;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)) !important;
        gap: 30px !important;
    }
    
    /* Hide sidebar completely */
    .woocommerce-sidebar,
    .sidebar,
    .widget-area,
    .secondary {
        display: none !important;
    }
    
    /* Full width content */
    .woocommerce,
    .woocommerce-page,
    .content-area,
    .site-main {
        width: 100% !important;
        max-width: 1200px !important;
        margin: 0 auto !important;
    }
    
    /* Fix any float issues */
    .woocommerce .products::after,
    .woocommerce .products::before {
        display: none !important;
    }
    
    .woocommerce .products li.product {
        float: none !important;
        width: auto !important;
        margin: 0 !important;
    }
    
    /* Force product visibility */
    .woocommerce .products li.product {
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
    }
    </style>
    <?php
    }
}
add_action('wp_head', 'phonestore_inline_fixes', 999);

// Create sample products if none exist
function phonestore_create_sample_products() {
    // Only run if no products exist
    $existing_products = get_posts(array(
        'post_type' => 'product',
        'posts_per_page' => 1
    ));
    
    if (empty($existing_products)) {
        // Create sample products
        $sample_products = array(
            array(
                'name' => 'iPhone 15 Pro',
                'price' => '29990000',
                'description' => 'iPhone mới nhất với chip A17 Pro',
                'image' => 'https://via.placeholder.com/400x400/007cba/ffffff?text=iPhone+15+Pro'
            ),
            array(
                'name' => 'Samsung Galaxy S24',
                'price' => '22990000', 
                'description' => 'Galaxy S24 với AI Galaxy',
                'image' => 'https://via.placeholder.com/400x400/1f4788/ffffff?text=Galaxy+S24'
            ),
            array(
                'name' => 'Xiaomi 14',
                'price' => '15990000',
                'description' => 'Xiaomi 14 với Snapdragon 8 Gen 3',
                'image' => 'https://via.placeholder.com/400x400/ff6900/ffffff?text=Xiaomi+14'
            )
        );
        
        foreach ($sample_products as $product_data) {
            $product = new WC_Product_Simple();
            $product->set_name($product_data['name']);
            $product->set_status('publish');
            $product->set_featured(false);
            $product->set_catalog_visibility('visible');
            $product->set_description($product_data['description']);
            $product->set_sku('');
            $product->set_price($product_data['price']);
            $product->set_regular_price($product_data['price']);
            $product->set_stock_status('instock');
            $product->set_manage_stock(false);
            $product->set_sold_individually(false);
            $product->save();
        }
    }
}

// Uncomment this line to create sample products
// add_action('wp_loaded', 'phonestore_create_sample_products');

// Security and performance optimizations
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
wp_deregister_script('wp-embed');

// Fix for "No products found" issue
function phonestore_debug_shop_query() {
    if (is_shop() && current_user_can('administrator') && isset($_GET['debug'])) {
        global $wp_query;
        echo '<div style="background: #fff; padding: 20px; margin: 20px 0; border-radius: 10px; border: 2px solid #4ecdc4;">';
        echo '<h3>🔧 Debug Info (Admin Only)</h3>';
        echo '<p><strong>Query:</strong> ' . $wp_query->request . '</p>';
        echo '<p><strong>Found Posts:</strong> ' . $wp_query->found_posts . '</p>';
        echo '<p><strong>Post Count:</strong> ' . $wp_query->post_count . '</p>';
        echo '<p><strong>Is Shop:</strong> ' . (is_shop() ? 'Yes' : 'No') . '</p>';
        echo '<p><strong>Post Type:</strong> ' . $wp_query->get('post_type') . '</p>';
        
        // Check direct product count
        $direct_count = wp_count_posts('product');
        echo '<p><strong>Direct Product Count:</strong> ' . $direct_count->publish . '</p>';
        
        echo '<p><em>Add ?debug=1 to URL to see this info</em></p>';
        echo '</div>';
    }
}
add_action('woocommerce_before_shop_loop', 'phonestore_debug_shop_query');
function phonestore_search_products_compare() {
    if (!wp_verify_nonce($_POST['nonce'], 'phonestore_nonce')) {
        wp_die('Security check failed');
    }
    
    $search_term = sanitize_text_field($_POST['term']);
    
    $args = array(
        'post_type' => 'product',
        'post_status' => 'publish',
        's' => $search_term,
        'posts_per_page' => 8
    );
    
    $products = get_posts($args);
    $results = array();
    
    foreach ($products as $product) {
        $wc_product = wc_get_product($product->ID);
        $results[] = array(
            'id' => $product->ID,
            'title' => $product->post_title,
            'price' => $wc_product ? $wc_product->get_price_html() : 'Liên hệ',
            'image' => get_the_post_thumbnail_url($product->ID, 'thumbnail') ?: 'https://via.placeholder.com/100x100?text=No+Image'
        );
    }
    
    wp_send_json_success($results);
}
add_action('wp_ajax_phonestore_search_products_compare', 'phonestore_search_products_compare');
add_action('wp_ajax_nopriv_phonestore_search_products_compare', 'phonestore_search_products_compare');

// AJAX handler - Get product for compare
function phonestore_get_product_compare() {
    if (!wp_verify_nonce($_POST['nonce'], 'phonestore_nonce')) {
        wp_die('Security check failed');
    }
    
    $product_id = intval($_POST['product_id']);
    $product = get_post($product_id);
    
    if (!$product || $product->post_type !== 'product') {
        wp_send_json_error('Product not found');
        return;
    }
    
    $wc_product = wc_get_product($product_id);
    
    $product_data = array(
        'id' => $product_id,
        'title' => $product->post_title,
        'price' => $wc_product ? $wc_product->get_price_html() : 'Liên hệ',
        'image' => get_the_post_thumbnail_url($product_id, 'medium') ?: 'https://via.placeholder.com/300x300?text=No+Image'
    );
    
    wp_send_json_success($product_data);
}
add_action('wp_ajax_phonestore_get_product_compare', 'phonestore_get_product_compare');
add_action('wp_ajax_nopriv_phonestore_get_product_compare', 'phonestore_get_product_compare');

// AJAX handler - Load compare table
function phonestore_load_compare_table() {
    if (!wp_verify_nonce($_POST['nonce'], 'phonestore_nonce')) {
        wp_die('Security check failed');
    }
    
    $product_ids = array_map('intval', $_POST['product_ids']);
    
    if (empty($product_ids) || count($product_ids) < 2) {
        wp_send_json_error('Need at least 2 products to compare');
        return;
    }
    
    $products = array();
    $specs = array();
    
    // Get products data
    foreach ($product_ids as $product_id) {
        $product = get_post($product_id);
        $wc_product = wc_get_product($product_id);
        
        if (!$product) continue;
        
        $products[$product_id] = array(
            'id' => $product_id,
            'title' => $product->post_title,
            'price' => $wc_product ? $wc_product->get_price_html() : 'Liên hệ',
            'image' => get_the_post_thumbnail_url($product_id, 'medium') ?: 'https://via.placeholder.com/250x250?text=No+Image',
            'url' => get_permalink($product_id)
        );
        
        // Get specs (using ACF or custom fields)
        $product_specs = array();
        
        // Thông số cơ bản
        if (function_exists('get_field')) {
            $product_specs['brand'] = get_field('brand', $product_id) ?: 'Không có thông tin';
            $product_specs['screen'] = get_field('screen', $product_id) ?: 'Không có thông tin';
            $product_specs['cpu'] = get_field('cpu', $product_id) ?: 'Không có thông tin';
            $product_specs['ram'] = get_field('ram', $product_id) ?: 'Không có thông tin';
            $product_specs['storage'] = get_field('storage', $product_id) ?: 'Không có thông tin';
            $product_specs['camera'] = get_field('camera', $product_id) ?: 'Không có thông tin';
            $product_specs['battery'] = get_field('battery', $product_id) ?: 'Không có thông tin';
            $product_specs['os'] = get_field('os', $product_id) ?: 'Không có thông tin';
            $product_specs['weight'] = get_field('weight', $product_id) ?: 'Không có thông tin';
            $product_specs['dimensions'] = get_field('dimensions', $product_id) ?: 'Không có thông tin';
        } else {
            // Fallback to post meta
            $product_specs['brand'] = get_post_meta($product_id, 'brand', true) ?: 'Không có thông tin';
            $product_specs['screen'] = get_post_meta($product_id, 'screen', true) ?: 'Không có thông tin';
            $product_specs['cpu'] = get_post_meta($product_id, 'cpu', true) ?: 'Không có thông tin';
            $product_specs['ram'] = get_post_meta($product_id, 'ram', true) ?: 'Không có thông tin';
            $product_specs['storage'] = get_post_meta($product_id, 'storage', true) ?: 'Không có thông tin';
            $product_specs['camera'] = get_post_meta($product_id, 'camera', true) ?: 'Không có thông tin';
            $product_specs['battery'] = get_post_meta($product_id, 'battery', true) ?: 'Không có thông tin';
            $product_specs['os'] = get_post_meta($product_id, 'os', true) ?: 'Không có thông tin';
            $product_specs['weight'] = get_post_meta($product_id, 'weight', true) ?: 'Không có thông tin';
            $product_specs['dimensions'] = get_post_meta($product_id, 'dimensions', true) ?: 'Không có thông tin';
        }
        
        $specs[$product_id] = $product_specs;
    }
    
    // Build compare table HTML
    $html = '<thead><tr><th class="spec-label">Thông số</th>';
    
    foreach ($products as $product) {
        $html .= '<th class="product-column">';
        $html .= '<div class="product-header">';
        $html .= '<img src="' . $product['image'] . '" alt="' . esc_attr($product['title']) . '">';
        $html .= '<h4><a href="' . $product['url'] . '">' . esc_html($product['title']) . '</a></h4>';
        $html .= '<div class="price">' . $product['price'] . '</div>';
        $html .= '<button class="remove-from-compare" data-product-id="' . $product['id'] . '">Xóa</button>';
        $html .= '</div>';
        $html .= '</th>';
    }
    
    $html .= '</tr></thead><tbody>';
    
    // Spec rows
    $spec_labels = array(
        'brand' => '📱 Thương hiệu',
        'screen' => '📺 Màn hình',
        'cpu' => '⚡ Vi xử lý',
        'ram' => '💾 RAM',
        'storage' => '💿 Bộ nhớ',
        'camera' => '📷 Camera',
        'battery' => '🔋 Pin',
        'os' => '🖥️ Hệ điều hành',
        'weight' => '⚖️ Trọng lượng',
        'dimensions' => '📏 Kích thước'
    );
    
    foreach ($spec_labels as $spec_key => $spec_label) {
        $html .= '<tr class="spec-row">';
        $html .= '<td class="spec-label">' . $spec_label . '</td>';
        
        foreach ($products as $product_id => $product) {
            $value = $specs[$product_id][$spec_key] ?? 'Không có thông tin';
            $html .= '<td class="spec-value">' . esc_html($value) . '</td>';
        }
        
        $html .= '</tr>';
    }
    
    $html .= '</tbody>';
    
    wp_send_json_success($html);
}
add_action('wp_ajax_phonestore_load_compare_table', 'phonestore_load_compare_table');
add_action('wp_ajax_nopriv_phonestore_load_compare_table', 'phonestore_load_compare_table');

function phonestore_contact_form() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'phonestore_nonce')) {
        wp_send_json_error(array('message' => 'Lỗi bảo mật. Vui lòng tải lại trang và thử lại.'));
        return;
    }
    
    // Sanitize and validate input
    $name = sanitize_text_field($_POST['name']);
    $phone = sanitize_text_field($_POST['phone']);
    $email = sanitize_email($_POST['email']);
    $subject = sanitize_text_field($_POST['subject']);
    $message = sanitize_textarea_field($_POST['message']);
    $privacy = isset($_POST['privacy']) && $_POST['privacy'] == 'true';
    
    // Validation
    $errors = array();
    
    if (empty($name)) {
        $errors[] = 'Vui lòng nhập họ và tên';
    }
    
    if (empty($phone)) {
        $errors[] = 'Vui lòng nhập số điện thoại';
    } elseif (!preg_match('/^[0-9\.\-\+\(\)\s]+$/', $phone)) {
        $errors[] = 'Số điện thoại không hợp lệ';
    }
    
    if (empty($email)) {
        $errors[] = 'Vui lòng nhập địa chỉ email';
    } elseif (!is_email($email)) {
        $errors[] = 'Email không hợp lệ';
    }
    
    if (empty($message)) {
        $errors[] = 'Vui lòng nhập tin nhắn';
    }
    
    if (!$privacy) {
        $errors[] = 'Vui lòng đồng ý với chính sách bảo mật';
    }
    
    if (!empty($errors)) {
        wp_send_json_error(array('message' => implode('<br>', $errors)));
        return;
    }
    
    // Subject mapping
    $subject_labels = array(
        'product-inquiry' => 'Hỏi về sản phẩm',
        'warranty' => 'Bảo hành',
        'return-exchange' => 'Đổi trả',
        'technical-support' => 'Hỗ trợ kỹ thuật',
        'complaint' => 'Khiếu nại',
        'partnership' => 'Hợp tác kinh doanh',
        'other' => 'Khác'
    );
    
    $subject_text = !empty($subject) ? $subject_labels[$subject] : 'Liên hệ từ website';
    
    // Store in database (custom table or post meta)
    $contact_data = array(
        'post_title' => 'Liên hệ từ ' . $name . ' - ' . date('d/m/Y H:i'),
        'post_content' => $message,
        'post_status' => 'private',
        'post_type' => 'contact',
        'meta_input' => array(
            'contact_name' => $name,
            'contact_phone' => $phone,
            'contact_email' => $email,
            'contact_subject' => $subject_text,
            'contact_ip' => $_SERVER['REMOTE_ADDR'],
            'contact_user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'contact_date' => current_time('mysql')
        )
    );
    
    $contact_id = wp_insert_post($contact_data);
    
    if (is_wp_error($contact_id)) {
        wp_send_json_error(array('message' => 'Có lỗi xảy ra khi lưu thông tin. Vui lòng thử lại.'));
        return;
    }
    
    // Send email notification to admin
    $admin_email = get_option('admin_email');
    $site_name = get_bloginfo('name');
    
    $email_subject = '[' . $site_name . '] Liên hệ mới từ ' . $name;
    
    $email_message = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .header { background: #1a365d; color: white; padding: 20px; text-align: center; }
            .content { padding: 20px; background: #f8f9fa; }
            .info-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
            .info-table th, .info-table td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
            .info-table th { background: #e2e8f0; font-weight: bold; }
            .message-box { background: white; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #38a169; }
            .footer { background: #2d3748; color: white; padding: 15px; text-align: center; font-size: 12px; }
        </style>
    </head>
    <body>
        <div class='header'>
            <h2>📞 Liên Hệ Mới Từ Website</h2>
        </div>
        
        <div class='content'>
            <h3>Thông tin khách hàng:</h3>
            <table class='info-table'>
                <tr><th>👤 Họ và tên:</th><td>{$name}</td></tr>
                <tr><th>📱 Số điện thoại:</th><td>{$phone}</td></tr>
                <tr><th>✉️ Email:</th><td>{$email}</td></tr>
                <tr><th>📋 Chủ đề:</th><td>{$subject_text}</td></tr>
                <tr><th>🕐 Thời gian:</th><td>" . current_time('d/m/Y H:i:s') . "</td></tr>
            </table>
            
            <div class='message-box'>
                <h4>💭 Tin nhắn:</h4>
                <p>" . nl2br($message) . "</p>
            </div>
        </div>
        
        <div class='footer'>
            <p>Email này được gửi tự động từ website {$site_name}</p>
        </div>
    </body>
    </html>
    ";
    
    $headers = array(
        'Content-Type: text/html; charset=UTF-8',
        'From: ' . $site_name . ' <noreply@' . $_SERVER['HTTP_HOST'] . '>',
        'Reply-To: ' . $name . ' <' . $email . '>'
    );
    
    wp_mail($admin_email, $email_subject, $email_message, $headers);
    
    // Send auto-reply to customer
    $customer_subject = 'Cảm ơn bạn đã liên hệ với ' . $site_name;
    $customer_message = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .header { background: linear-gradient(135deg, #1a365d 0%, #2d3748 100%); color: white; padding: 30px; text-align: center; }
            .content { padding: 30px; background: #f8f9fa; }
            .info-box { background: white; padding: 20px; border-radius: 12px; margin: 20px 0; border: 2px solid #e2e8f0; }
            .contact-info { background: #38a169; color: white; padding: 20px; border-radius: 12px; margin: 20px 0; }
            .footer { background: #2d3748; color: white; padding: 20px; text-align: center; }
        </style>
    </head>
    <body>
        <div class='header'>
            <h2>📱 Cảm ơn bạn đã liên hệ!</h2>
        </div>
        
        <div class='content'>
            <p>Chào <strong>{$name}</strong>,</p>
            
            <div class='info-box'>
                <p>Cảm ơn bạn đã liên hệ với <strong>Cửa Hàng Điện Thoại</strong>. Chúng tôi đã nhận được tin nhắn của bạn và sẽ phản hồi trong thời gian sớm nhất.</p>
                
                <h4>📋 Thông tin bạn đã gửi:</h4>
                <ul>
                    <li><strong>Chủ đề:</strong> {$subject_text}</li>
                    <li><strong>Thời gian:</strong> " . current_time('d/m/Y H:i:s') . "</li>
                </ul>
            </div>
            
            <div class='contact-info'>
                <h4>📞 Thông tin liên hệ trực tiếp:</h4>
                <ul>
                    <li><strong>📍 Địa chỉ:</strong> Purple House, Ninh Kiều, Cần Thơ</li>
                    <li><strong>📱 Hotline:</strong> 0123.456.789</li>
                    <li><strong>✉️ Email:</strong> info@phonestore.com</li>
                    <li><strong>🕐 Giờ mở cửa:</strong> 8:00 - 21:00 (T2-T6), 8:00 - 22:00 (T7-CN)</li>
                </ul>
            </div>
            
            <p>Nếu có vấn đề gấp, bạn có thể liên hệ trực tiếp qua hotline hoặc đến cửa hàng.</p>
        </div>
        
        <div class='footer'>
            <p>Trân trọng,<br><strong>Đội ngũ Cửa Hàng Điện Thoại</strong></p>
        </div>
    </body>
    </html>
    ";
    
    $customer_headers = array(
        'Content-Type: text/html; charset=UTF-8',
        'From: ' . $site_name . ' <noreply@' . $_SERVER['HTTP_HOST'] . '>'
    );
    
    wp_mail($email, $customer_subject, $customer_message, $customer_headers);
    
    // Success response
    wp_send_json_success(array(
        'message' => 'Cảm ơn bạn đã liên hệ với chúng tôi! Chúng tôi sẽ phản hồi trong thời gian sớm nhất.',
        'contact_id' => $contact_id
    ));
}
add_action('wp_ajax_phonestore_contact_form', 'phonestore_contact_form');
add_action('wp_ajax_nopriv_phonestore_contact_form', 'phonestore_contact_form');

// Register custom post type for contacts
function phonestore_register_contact_post_type() {
    $args = array(
        'label' => 'Liên Hệ',
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_icon' => 'dashicons-email-alt',
        'capability_type' => 'post',
        'hierarchical' => false,
        'supports' => array('title', 'editor'),
        'labels' => array(
            'name' => 'Liên Hệ',
            'singular_name' => 'Liên Hệ',
            'menu_name' => 'Liên Hệ',
            'add_new' => 'Thêm Mới',
            'add_new_item' => 'Thêm Liên Hệ Mới',
            'edit_item' => 'Sửa Liên Hệ',
            'new_item' => 'Liên Hệ Mới',
            'view_item' => 'Xem Liên Hệ',
            'search_items' => 'Tìm Liên Hệ',
            'not_found' => 'Không tìm thấy',
            'not_found_in_trash' => 'Không có trong thùng rác'
        )
    );
    register_post_type('contact', $args);
}
add_action('init', 'phonestore_register_contact_post_type');

// Add custom columns to contact list
function phonestore_contact_columns($columns) {
    $new_columns = array();
    $new_columns['cb'] = $columns['cb'];
    $new_columns['title'] = 'Tiêu đề';
    $new_columns['contact_name'] = 'Tên';
    $new_columns['contact_email'] = 'Email';
    $new_columns['contact_phone'] = 'Điện thoại';
    $new_columns['contact_subject'] = 'Chủ đề';
    $new_columns['date'] = 'Ngày gửi';
    return $new_columns;
}
add_filter('manage_contact_posts_columns', 'phonestore_contact_columns');

// Populate custom columns
function phonestore_contact_column_content($column, $post_id) {
    switch ($column) {
        case 'contact_name':
            echo get_post_meta($post_id, 'contact_name', true);
            break;
        case 'contact_email':
            $email = get_post_meta($post_id, 'contact_email', true);
            echo '<a href="mailto:' . $email . '">' . $email . '</a>';
            break;
        case 'contact_phone':
            $phone = get_post_meta($post_id, 'contact_phone', true);
            echo '<a href="tel:' . $phone . '">' . $phone . '</a>';
            break;
        case 'contact_subject':
            echo get_post_meta($post_id, 'contact_subject', true);
            break;
    }
}
add_action('manage_contact_posts_custom_column', 'phonestore_contact_column_content', 10, 2);

// Make columns sortable
function phonestore_contact_sortable_columns($columns) {
    $columns['contact_name'] = 'contact_name';
    $columns['contact_email'] = 'contact_email';
    $columns['contact_subject'] = 'contact_subject';
    return $columns;
}
add_filter('manage_edit-contact_sortable_columns', 'phonestore_contact_sortable_columns');

// Add meta boxes for contact details
function phonestore_contact_meta_boxes() {
    add_meta_box(
        'contact-details',
        'Chi Tiết Liên Hệ',
        'phonestore_contact_details_callback',
        'contact',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'phonestore_contact_meta_boxes');

// Contact details meta box callback
function phonestore_contact_details_callback($post) {
    $name = get_post_meta($post->ID, 'contact_name', true);
    $phone = get_post_meta($post->ID, 'contact_phone', true);
    $email = get_post_meta($post->ID, 'contact_email', true);
    $subject = get_post_meta($post->ID, 'contact_subject', true);
    $ip = get_post_meta($post->ID, 'contact_ip', true);
    $user_agent = get_post_meta($post->ID, 'contact_user_agent', true);
    $contact_date = get_post_meta($post->ID, 'contact_date', true);
    
    echo '<table class="form-table">';
    echo '<tr><th><strong>👤 Họ và tên:</strong></th><td>' . esc_html($name) . '</td></tr>';
    echo '<tr><th><strong>📱 Số điện thoại:</strong></th><td><a href="tel:' . esc_attr($phone) . '">' . esc_html($phone) . '</a></td></tr>';
    echo '<tr><th><strong>✉️ Email:</strong></th><td><a href="mailto:' . esc_attr($email) . '">' . esc_html($email) . '</a></td></tr>';
    echo '<tr><th><strong>📋 Chủ đề:</strong></th><td>' . esc_html($subject) . '</td></tr>';
    echo '<tr><th><strong>🕐 Thời gian gửi:</strong></th><td>' . esc_html($contact_date) . '</td></tr>';
    echo '<tr><th><strong>🌐 IP Address:</strong></th><td>' . esc_html($ip) . '</td></tr>';
    echo '<tr><th><strong>🖥️ User Agent:</strong></th><td><small>' . esc_html($user_agent) . '</small></td></tr>';
    echo '</table>';
    
    echo '<h3>💭 Tin nhắn:</h3>';
    echo '<div style="background: #f9f9f9; padding: 15px; border-radius: 5px; border-left: 4px solid #0073aa;">';
    echo wpautop(esc_html($post->post_content));
    echo '</div>';
    
    echo '<h3>📧 Hành động:</h3>';
    echo '<p>';
    echo '<a href="mailto:' . esc_attr($email) . '?subject=Re: ' . esc_attr($subject) . '" class="button button-primary">Trả lời Email</a> ';
    echo '<a href="tel:' . esc_attr($phone) . '" class="button">Gọi điện</a>';
    echo '</p>';
}

// Disable editing of contact posts
function phonestore_contact_readonly($post) {
    if ($post->post_type == 'contact') {
        echo '<style>#edit-slug-box, #minor-publishing-actions, #misc-publishing-actions { display: none; }</style>';
        echo '<script>jQuery(document).ready(function($){ $("#post").find("input, textarea, select").not("#contact-reply").attr("readonly", true).attr("disabled", true); });</script>';
    }
}
add_action('edit_form_after_title', 'phonestore_contact_readonly');

// Add notification count to admin menu
function phonestore_contact_menu_count() {
    global $menu;
    
    $count = wp_count_posts('contact');
    $unread_count = $count->private;
    
    if ($unread_count > 0) {
        foreach ($menu as $key => $val) {
            if ($val[2] == 'edit.php?post_type=contact') {
                $menu[$key][0] .= ' <span class="awaiting-mod count-' . $unread_count . '"><span class="pending-count">' . $unread_count . '</span></span>';
                break;
            }
        }
    }
}
add_action('admin_menu', 'phonestore_contact_menu_count');
function create_phone_specs_fields() {
    if (function_exists('acf_add_local_field_group')) {
        acf_add_local_field_group(array(
            'key' => 'group_phone_specs',
            'title' => 'Thông số kỹ thuật điện thoại',
            'fields' => array(
                array(
                    'key' => 'field_brand',
                    'label' => 'Thương hiệu',
                    'name' => 'brand',
                    'type' => 'select',
                    'choices' => array(
                        'iphone' => 'iPhone',
                        'samsung' => 'Samsung',
                        'xiaomi' => 'Xiaomi',
                        'oppo' => 'OPPO',
                        'vivo' => 'Vivo',
                        'huawei' => 'Huawei',
                        'realme' => 'Realme'
                    ),
                ),
                array(
                    'key' => 'field_display_size',
                    'label' => 'Kích thước màn hình',
                    'name' => 'display_size',
                    'type' => 'text',
                    'placeholder' => 'VD: 6.1 inch'
                ),
                array(
                    'key' => 'field_display_resolution',
                    'label' => 'Độ phân giải',
                    'name' => 'display_resolution',
                    'type' => 'text',
                    'placeholder' => 'VD: 1170 x 2532 pixels'
                ),
                array(
                    'key' => 'field_display_type',
                    'label' => 'Loại màn hình',
                    'name' => 'display_type',
                    'type' => 'text',
                    'placeholder' => 'VD: Super Retina XDR OLED'
                ),
                array(
                    'key' => 'field_cpu',
                    'label' => 'Vi xử lý',
                    'name' => 'cpu',
                    'type' => 'text',
                    'placeholder' => 'VD: Apple A15 Bionic'
                ),
                array(
                    'key' => 'field_ram',
                    'label' => 'RAM',
                    'name' => 'ram',
                    'type' => 'select',
                    'choices' => array(
                        '3gb' => '3GB',
                        '4gb' => '4GB',
                        '6gb' => '6GB',
                        '8gb' => '8GB',
                        '12gb' => '12GB',
                        '16gb' => '16GB'
                    ),
                ),
                array(
                    'key' => 'field_storage',
                    'label' => 'Bộ nhớ trong',
                    'name' => 'storage',
                    'type' => 'select',
                    'choices' => array(
                        '64gb' => '64GB',
                        '128gb' => '128GB',
                        '256gb' => '256GB',
                        '512gb' => '512GB',
                        '1tb' => '1TB'
                    ),
                ),
                array(
                    'key' => 'field_rear_camera',
                    'label' => 'Camera sau',
                    'name' => 'rear_camera',
                    'type' => 'text',
                    'placeholder' => 'VD: 12MP + 12MP + 12MP'
                ),
                array(
                    'key' => 'field_front_camera',
                    'label' => 'Camera trước',
                    'name' => 'front_camera',
                    'type' => 'text',
                    'placeholder' => 'VD: 12MP TrueDepth'
                ),
                array(
                    'key' => 'field_battery',
                    'label' => 'Pin',
                    'name' => 'battery',
                    'type' => 'text',
                    'placeholder' => 'VD: 3095 mAh'
                ),
                array(
                    'key' => 'field_os',
                    'label' => 'Hệ điều hành',
                    'name' => 'os',
                    'type' => 'text',
                    'placeholder' => 'VD: iOS 15'
                ),
                array(
                    'key' => 'field_dimensions',
                    'label' => 'Kích thước',
                    'name' => 'dimensions',
                    'type' => 'text',
                    'placeholder' => 'VD: 146.7 x 71.5 x 7.65 mm'
                ),
                array(
                    'key' => 'field_weight',
                    'label' => 'Trọng lượng',
                    'name' => 'weight',
                    'type' => 'text',
                    'placeholder' => 'VD: 173g'
                ),
                array(
                    'key' => 'field_colors',
                    'label' => 'Màu sắc',
                    'name' => 'colors',
                    'type' => 'text',
                    'placeholder' => 'VD: Xanh, Hồng, Xám, Đen'
                ),
                array(
                    'key' => 'field_sim',
                    'label' => 'Loại SIM',
                    'name' => 'sim',
                    'type' => 'text',
                    'placeholder' => 'VD: Nano-SIM và eSIM'
                ),
                array(
                    'key' => 'field_network',
                    'label' => 'Mạng hỗ trợ',
                    'name' => 'network',
                    'type' => 'text',
                    'placeholder' => 'VD: 5G, 4G LTE, 3G, 2G'
                )
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'product',
                    ),
                ),
            ),
        ));
    }
}
add_action('acf/init', 'create_phone_specs_fields');
// Thêm vào functions.php
function display_phone_specifications() {
    global $product;
    
    $specs = array(
        'brand' => 'Thương hiệu',
        'display_size' => 'Kích thước màn hình',
        'display_resolution' => 'Độ phân giải',
        'display_type' => 'Loại màn hình',
        'cpu' => 'Vi xử lý',
        'ram' => 'RAM',
        'storage' => 'Bộ nhớ trong',
        'rear_camera' => 'Camera sau',
        'front_camera' => 'Camera trước',
        'battery' => 'Pin',
        'os' => 'Hệ điều hành',
        'dimensions' => 'Kích thước',
        'weight' => 'Trọng lượng',
        'colors' => 'Màu sắc',
        'sim' => 'Loại SIM',
        'network' => 'Mạng hỗ trợ'
    );
    
    echo '<div class="phone-specifications">';
    echo '<h3>Thông số kỹ thuật</h3>';
    echo '<table class="product-specs-table">';
    
    foreach ($specs as $field => $label) {
        $value = get_field($field, $product->get_id());
        if ($value) {
            echo '<tr>';
            echo '<th>' . $label . '</th>';
            echo '<td>' . $value . '</td>';
            echo '</tr>';
        }
    }
    
    echo '</table>';
    echo '</div>';
}
add_action('woocommerce_single_product_summary', 'display_phone_specifications', 25);

// Thêm vào functions.php
function phone_search_autocomplete() {
    // Kiểm tra nonce
    if (!wp_verify_nonce($_POST['nonce'], 'phonestore_nonce')) {
        wp_die('Security check failed');
    }
    
    $search_term = sanitize_text_field($_POST['term']);
    
    $args = array(
        'post_type' => 'product',
        'post_status' => 'publish',
        's' => $search_term,
        'posts_per_page' => 5,
        'meta_query' => array(
            array(
                'key' => '_stock_status',
                'value' => 'instock'
            )
        )
    );
    
    $products = get_posts($args);
    $suggestions = array();
    
    foreach ($products as $product) {
        $wc_product = wc_get_product($product->ID);
        $suggestions[] = array(
            'title' => $product->post_title,
            'url' => get_permalink($product->ID),
            'price' => $wc_product->get_price_html(),
            'image' => get_the_post_thumbnail_url($product->ID, 'thumbnail')
        );
    }
    
    wp_send_json_success($suggestions);
}
add_action('wp_ajax_phone_search_autocomplete', 'phone_search_autocomplete');
add_action('wp_ajax_nopriv_phone_search_autocomplete', 'phone_search_autocomplete');

// Thêm vào functions.php
function filter_products() {
    if (!wp_verify_nonce($_POST['nonce'], 'phonestore_nonce')) {
        wp_die('Security check failed');
    }
    
    parse_str($_POST['filters'], $filters);
    
    $args = array(
        'post_type' => 'product',
        'post_status' => 'publish',
        'posts_per_page' => 12,
        'meta_query' => array('relation' => 'AND')
    );
    
    // Filter theo thương hiệu
    if (!empty($filters['filter_brand'])) {
        $args['meta_query'][] = array(
            'key' => 'brand',
            'value' => $filters['filter_brand'],
            'compare' => '='
        );
    }
    
    // Filter theo RAM
    if (!empty($filters['filter_ram'])) {
        $args['meta_query'][] = array(
            'key' => 'ram',
            'value' => $filters['filter_ram'],
            'compare' => '='
        );
    }
    
    // Filter theo Storage
    if (!empty($filters['filter_storage'])) {
        $args['meta_query'][] = array(
            'key' => 'storage',
            'value' => $filters['filter_storage'],
            'compare' => '='
        );
    }
    
    // Filter theo giá
    if (!empty($filters['filter_price'])) {
        $price_range = explode('-', $filters['filter_price']);
        $min_price = $price_range[0] * 1000000; // Convert to VND
        $max_price = $price_range[1] * 1000000;
        
        $args['meta_query'][] = array(
            'key' => '_price',
            'value' => array($min_price, $max_price),
            'type' => 'NUMERIC',
            'compare' => 'BETWEEN'
        );
    }
    
    $products = get_posts($args);
    $html = '';
    
    if ($products) {
        $html .= '<div class="products columns-4">';
        foreach ($products as $product) {
            $wc_product = wc_get_product($product->ID);
            $html .= '<div class="product">';
            $html .= '<a href="' . get_permalink($product->ID) . '">';
            $html .= get_the_post_thumbnail($product->ID, 'woocommerce_thumbnail');
            $html .= '<h3>' . $product->post_title . '</h3>';
            $html .= '<span class="price">' . $wc_product->get_price_html() . '</span>';
            
            // Hiển thị specs ngắn gọn
            $ram = get_field('ram', $product->ID);
            $storage = get_field('storage', $product->ID);
            if ($ram || $storage) {
                $html .= '<div class="quick-specs">';
                if ($ram) $html .= '<span>RAM: ' . strtoupper($ram) . '</span>';
                if ($storage) $html .= '<span>Bộ nhớ: ' . strtoupper($storage) . '</span>';
                $html .= '</div>';
            }
            
            $html .= '</a>';
            $html .= '<a href="?add-to-cart=' . $product->ID . '" class="button add_to_cart_button">Thêm vào giỏ</a>';
            $html .= '</div>';
        }
        $html .= '</div>';
    } else {
        $html = '<p>Không tìm thấy sản phẩm phù hợp.</p>';
    }
    
    wp_send_json_success($html);
}
add_action('wp_ajax_filter_products', 'filter_products');
add_action('wp_ajax_nopriv_filter_products', 'filter_products');

// Thêm vào functions.php
function search_products_for_compare() {
    if (!wp_verify_nonce($_POST['nonce'], 'phonestore_nonce')) {
        wp_die('Security check failed');
    }
    
    $search_term = sanitize_text_field($_POST['term']);
    
    $args = array(
        'post_type' => 'product',
        'post_status' => 'publish',
        's' => $search_term,
        'posts_per_page' => 8,
        'meta_query' => array(
            array(
                'key' => '_stock_status',
                'value' => 'instock'
            )
        )
    );
    
    $products = get_posts($args);
    $results = array();
    
    foreach ($products as $product) {
        $wc_product = wc_get_product($product->ID);
        $results[] = array(
            'id' => $product->ID,
            'title' => $product->post_title,
            'price' => $wc_product->get_price_html(),
            'image' => get_the_post_thumbnail_url($product->ID, 'thumbnail') ?: wc_placeholder_img_src()
        );
    }
    
    wp_send_json_success($results);
}
add_action('wp_ajax_search_products_for_compare', 'search_products_for_compare');
add_action('wp_ajax_nopriv_search_products_for_compare', 'search_products_for_compare');

function load_compare_table() {
    if (!wp_verify_nonce($_POST['nonce'], 'phonestore_nonce')) {
        wp_die('Security check failed');
    }
    
    $product_ids = array_map('intval', $_POST['products']);
    
    if (empty($product_ids)) {
        wp_send_json_error('No products selected');
    }
    
    $products = array();
    foreach ($product_ids as $id) {
        $product = wc_get_product($id);
        if ($product) {
            $products[] = $product;
        }
    }
    
    if (empty($products)) {
        wp_send_json_error('No valid products found');
    }
    
    // Tạo bảng so sánh
    ob_start();
    ?>
    <div class="compare-table-wrapper">
        <table class="compare-table">
            <thead>
                <tr>
                    <td class="spec-label">Sản phẩm</td>
                    <?php foreach ($products as $product): ?>
                        <td class="product-column">
                            <div class="product-compare-header">
                                <button class="remove-from-compare" data-product-id="<?php echo $product->get_id(); ?>">&times;</button>
                                <img src="<?php echo wp_get_attachment_image_url($product->get_image_id(), 'thumbnail'); ?>" 
                                     alt="<?php echo $product->get_name(); ?>">
                                <h4><a href="<?php echo $product->get_permalink(); ?>"><?php echo $product->get_name(); ?></a></h4>
                                <div class="price"><?php echo $product->get_price_html(); ?></div>
                                <div class="rating">
                                    <?php echo wc_get_rating_html($product->get_average_rating()); ?>
                                    <span class="review-count">(<?php echo $product->get_review_count(); ?> đánh giá)</span>
                                </div>
                                <a href="<?php echo $product->add_to_cart_url(); ?>" class="button add-to-cart">
                                    Thêm vào giỏ
                                </a>
                            </div>
                        </td>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php
                $specs = array(
                    'brand' => 'Thương hiệu',
                    'display_size' => 'Kích thước màn hình',
                    'display_resolution' => 'Độ phân giải',
                    'display_type' => 'Loại màn hình',
                    'cpu' => 'Vi xử lý',
                    'ram' => 'RAM',
                    'storage' => 'Bộ nhớ trong',
                    'rear_camera' => 'Camera sau',
                    'front_camera' => 'Camera trước',
                    'battery' => 'Pin',
                    'os' => 'Hệ điều hành',
                    'dimensions' => 'Kích thước',
                    'weight' => 'Trọng lượng',
                    'colors' => 'Màu sắc',
                    'sim' => 'Loại SIM',
                    'network' => 'Mạng hỗ trợ'
                );
                
                foreach ($specs as $field => $label):
                    // Kiểm tra xem có ít nhất 1 sản phẩm có field này không
                    $has_data = false;
                    foreach ($products as $product) {
                        if (get_field($field, $product->get_id())) {
                            $has_data = true;
                            break;
                        }
                    }
                    
                    if (!$has_data) continue;
                ?>
                    <tr>
                        <td class="spec-label"><?php echo $label; ?></td>
                        <?php foreach ($products as $product): ?>
                            <td class="spec-value">
                                <?php 
                                $value = get_field($field, $product->get_id());
                                echo $value ? $value : '—';
                                ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <style>
    .compare-table-wrapper {
        overflow-x: auto;
        margin: 20px 0;
    }
    
    .compare-table {
        width: 100%;
        min-width: 800px;
        border-collapse: collapse;
        background: white;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .compare-table td {
        border: 1px solid #ddd;
        padding: 15px;
        text-align: center;
        vertical-align: top;
    }
    
    .spec-label {
        background: #f8f9fa;
        font-weight: bold;
        text-align: left !important;
        min-width: 150px;
    }
    
    .product-column {
        position: relative;
        min-width: 200px;
    }
    
    .product-compare-header {
        position: relative;
    }
    
    .remove-from-compare {
        position: absolute;
        top: 5px;
        right: 5px;
        background: #ff4444;
        color: white;
        border: none;
        border-radius: 50%;
        width: 25px;
        height: 25px;
        cursor: pointer;
        font-size: 16px;
        line-height: 1;
    }
    
    .product-compare-header img {
        width: 120px;
        height: 120px;
        object-fit: cover;
        margin-bottom: 10px;
    }
    
    .product-compare-header h4 {
        margin: 10px 0;
        font-size: 16px;
    }
    
    .product-compare-header h4 a {
        text-decoration: none;
        color: #333;
    }
    
    .price {
        font-size: 18px;
        font-weight: bold;
        color: #e74c3c;
        margin: 10px 0;
    }
    
    .rating {
        margin: 10px 0;
    }
    
    .review-count {
        font-size: 12px;
        color: #666;
    }
    
    .add-to-cart {
        background: #007cba;
        color: white;
        padding: 8px 16px;
        text-decoration: none;
        border-radius: 4px;
        display: inline-block;
        margin-top: 10px;
    }
    
    .spec-value {
        font-size: 14px;
        line-height: 1.4;
    }
    
    @media (max-width: 768px) {
        .compare-table {
            font-size: 12px;
        }
        
        .product-compare-header img {
            width: 80px;
            height: 80px;
        }
        
        .product-compare-header h4 {
            font-size: 14px;
        }
    }
    </style>
    <?php
    
    $html = ob_get_clean();
    wp_send_json_success($html);
}
add_action('wp_ajax_load_compare_table', 'load_compare_table');
add_action('wp_ajax_nopriv_load_compare_table', 'load_compare_table');

// Thêm vào functions.php
// Include custom shipping class
require_once get_template_directory() . '/includes/class-distance-shipping.php';

function add_distance_based_shipping($methods) {
    $methods['distance_based_shipping'] = 'Distance_Based_Shipping';
    return $methods;
}
add_filter('woocommerce_shipping_methods', 'add_distance_based_shipping');

// Tạo thư mục includes nếu chưa có
function create_includes_directory() {
    $includes_dir = get_template_directory() . '/includes';
    if (!file_exists($includes_dir)) {
        wp_mkdir_p($includes_dir);
    }
}
add_action('after_setup_theme', 'create_includes_directory');

// Thêm vào functions.php
function custom_bank_transfer_instructions($order_id) {
    $order = wc_get_order($order_id);
    
    if ($order->get_payment_method() === 'bacs') {
        echo '<div class="bank-transfer-info">';
        echo '<h3>Thông tin chuyển khoản:</h3>';
        echo '<div class="bank-details">';
        echo '<p><strong>Ngân hàng:</strong> Vietcombank</p>';
        echo '<p><strong>Số tài khoản:</strong> 1234567890</p>';
        echo '<p><strong>Chủ tài khoản:</strong> CONG TY TNHH ABC</p>';
        echo '<p><strong>Số tiền:</strong> ' . wc_price($order->get_total()) . '</p>';
        echo '<p><strong>Nội dung chuyển khoản:</strong> DH' . $order->get_order_number() . '</p>';
        echo '</div>';
        echo '<p><em>Vui lòng chuyển khoản và gửi ảnh chụp biên lai về Zalo: 0123456789</em></p>';
        echo '</div>';
    }
}
add_action('woocommerce_thankyou', 'custom_bank_transfer_instructions');


?>