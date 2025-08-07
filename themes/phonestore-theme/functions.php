<?php
/**
 * PhoneStore Theme Functions - Complete Version
 * Version: 3.0
 */

// Enqueue styles và scripts
function phonestore_enqueue_styles() {
    wp_enqueue_style('phonestore-style', get_stylesheet_uri(), array(), '3.0.0');
    wp_enqueue_script('phonestore-script', get_template_directory_uri() . '/js/phonestore.js', array('jquery'), '3.0.0', true);
    
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
}
add_action('after_setup_theme', 'phonestore_add_woocommerce_support');

// Tạo các pages cần thiết khi theme được kích hoạt
function phonestore_create_required_pages() {
    // Tạo trang Liên Hệ
    $contact_page = get_page_by_path('lien-he');
    if (!$contact_page) {
        wp_insert_post(array(
            'post_title' => 'Liên Hệ',
            'post_name' => 'lien-he',
            'post_content' => '[contact_page_content]',
            'post_status' => 'publish',
            'post_type' => 'page',
            'page_template' => 'page-lien-he.php'
        ));
    }
    
    // Tạo trang So Sánh Sản Phẩm
    $compare_page = get_page_by_path('so-sanh-san-pham');
    if (!$compare_page) {
        wp_insert_post(array(
            'post_title' => 'So Sánh Sản Phẩm',
            'post_name' => 'so-sanh-san-pham',
            'post_content' => '[compare_page_content]',
            'post_status' => 'publish',
            'post_type' => 'page',
            'page_template' => 'page-so-sanh.php'
        ));
    }
    
    // Tạo menu chính nếu chưa có
    if (!wp_get_nav_menu_object('Main Menu')) {
        $menu_id = wp_create_nav_menu('Main Menu');
        
        // Thêm items vào menu
        wp_update_nav_menu_item($menu_id, 0, array(
            'menu-item-title' => 'Trang chủ',
            'menu-item-url' => home_url('/'),
            'menu-item-status' => 'publish'
        ));
        
        wp_update_nav_menu_item($menu_id, 0, array(
            'menu-item-title' => 'Sản phẩm',
            'menu-item-url' => home_url('/shop/'),
            'menu-item-status' => 'publish'
        ));
        
        // Thêm trang Liên Hệ vào menu
        $contact_page = get_page_by_path('lien-he');
        if ($contact_page) {
            wp_update_nav_menu_item($menu_id, 0, array(
                'menu-item-title' => 'Liên hệ',
                'menu-item-object' => 'page',
                'menu-item-object-id' => $contact_page->ID,
                'menu-item-type' => 'post_type',
                'menu-item-status' => 'publish'
            ));
        }
        
        // Thêm trang So Sánh vào menu
        $compare_page = get_page_by_path('so-sanh-san-pham');
        if ($compare_page) {
            wp_update_nav_menu_item($menu_id, 0, array(
                'menu-item-title' => 'So sánh',
                'menu-item-object' => 'page',
                'menu-item-object-id' => $compare_page->ID,
                'menu-item-type' => 'post_type',
                'menu-item-status' => 'publish'
            ));
        }
        
        // Gán menu vào location
        $locations = get_theme_mod('nav_menu_locations');
        $locations['primary'] = $menu_id;
        set_theme_mod('nav_menu_locations', $locations);
    }
}
add_action('after_switch_theme', 'phonestore_create_required_pages');

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
            'id' => $product->ID,
            'title' => $product->post_title,
            'url' => get_permalink($product->ID),
            'price' => $wc_product ? $wc_product->get_price_html() : 'Liên hệ',
            'image' => get_the_post_thumbnail_url($product->ID, 'thumbnail') ?: wc_placeholder_img_src()
        );
    }
    
    wp_send_json_success($suggestions);
}
add_action('wp_ajax_phonestore_ajax_product_search', 'phonestore_ajax_product_search');
add_action('wp_ajax_nopriv_phonestore_ajax_product_search', 'phonestore_ajax_product_search');

// AJAX search for compare
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
            'image' => get_the_post_thumbnail_url($product->ID, 'thumbnail') ?: wc_placeholder_img_src()
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
    $wc_product = wc_get_product($product_id);
    
    if (!$wc_product) {
        wp_send_json_error('Product not found');
        return;
    }
    
    // COPY CHÍNH XÁC LOGIC TỪ SINGLE-PRODUCT.PHP
    $all_specs = array();
    
    // Lấy WooCommerce attributes
    $wc_attributes = $wc_product->get_attributes();
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
    
    // Lấy custom meta fields - CHÍNH XÁC NHƯ SINGLE-PRODUCT.PHP
    $meta_keys = get_post_meta($product_id);
    foreach ($meta_keys as $key => $values) {
        if (strpos($key, '_') !== 0 && !empty($values[0])) {
            $all_specs[$key] = $values[0];
        }
    }
    
    $product_data = array(
        'id' => $product_id,
        'title' => get_the_title($product_id),
        'attributes' => $all_specs,
        'price' => $wc_product ? $wc_product->get_price_html() : 'Liên hệ',
        'image' => get_the_post_thumbnail_url($product_id, 'medium') ?: wc_placeholder_img_src()
    );
    
    wp_send_json_success($product_data);
}

// AJAX handler - Load compare table với đầy đủ attributes
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
    $all_specs = array();
    $all_spec_keys = array(); // Để lưu tất cả các spec keys có thể có
    
    // Get products data
    foreach ($product_ids as $product_id) {
        $product = get_post($product_id);
        $wc_product = wc_get_product($product_id);
        
        if (!$product) continue;
        
        $products[$product_id] = array(
            'id' => $product_id,
            'title' => $product->post_title,
            'price' => $wc_product ? $wc_product->get_price_html() : 'Liên hệ',
            'image' => get_the_post_thumbnail_url($product_id, 'medium') ?: wc_placeholder_img_src(),
            'url' => get_permalink($product_id)
        );
        
        // Get product attributes from WooCommerce
        $product_specs = array();
        
        if ($wc_product) {
            $attributes = $wc_product->get_attributes();
            
            foreach ($attributes as $attribute) {
                $attribute_name = $attribute->get_name();
                $attribute_key = str_replace('pa_', '', $attribute_name);
                $attribute_label = wc_attribute_label($attribute_name);
                
                if ($attribute->is_taxonomy()) {
                    $values = wc_get_product_terms($product_id, $attribute_name, array('fields' => 'names'));
                    $value = implode(', ', $values);
                } else {
                    $value = implode(', ', $attribute->get_options());
                }
                
                if (!empty($value)) {
                    $product_specs[$attribute_key] = $value;
                    $all_spec_keys[$attribute_key] = $attribute_label; // Lưu label cho spec này
                }
            }
        }
        
        // Fallback: Lấy từ post meta với mapping đầy đủ
        $meta_mapping = array(
            'thuong-hieu-brand' => 'Thương hiệu',
            'man-hinh-display' => 'Màn hình',
            'camera' => 'Camera chính',
            'camera-truoc' => 'Camera trước',
            'ram' => 'RAM',
            'bo-nho-storage' => 'Bộ nhớ trong',
            'pin' => 'Pin',
            'he-dieu-hanh' => 'Hệ điều hành',
            'chip-xu-ly-cpu' => 'CPU',
            'chip-do-hoa-gpu' => 'GPU', 
            'bluetooth' => 'Bluetooth',
            'wifi' => 'Wi-Fi',
            'chat-lieu' => 'Chất liệu',
            'cong-ket-noi-sac' => 'Cổng sạc',
            'nghe-nhac' => 'Nghe nhạc',
            'xem-phim' => 'Xem phim',
            'choi-game' => 'Chơi game',
            'khoang-gia' => 'Khoảng giá',
            'vi-xu-ly' => 'Vi xử lý',
            'quay-phim-camera-sau' => 'Quay phim camera sau',
            'thiet-ke' => 'Thiết kế',
            'thoi-diem-ra-mat' => 'Thời điểm ra mắt',
            'tinh-nang-camera-sau' => 'Tính năng camera sau',
            'tinh-nang-camera-truoc' => 'Tính năng camera trước',
            'tinh-nang-dac-biet' => 'Tính năng đặc biệt',
            'toc-do-cpu' => 'Tốc độ CPU',
            'kich-thuoc' => 'Kích thước',
            'trong-luong' => 'Trọng lượng',
            'mau-sac' => 'Màu sắc',
            'sim' => 'SIM',
            'mang' => 'Mạng',
            'gps' => 'GPS',
            'cam-bien' => 'Cảm biến',
            'jack-tai-nghe' => 'Jack tai nghe',
            'loa' => 'Loa',
            'chong-nuoc' => 'Chống nước',
            'bao-mat' => 'Bảo mật'
        );
        
        $all_meta = get_post_meta($product_id);
        foreach ($meta_mapping as $meta_key => $label) {
            if (isset($all_meta[$meta_key]) && !empty($all_meta[$meta_key][0])) {
                $product_specs[$meta_key] = $all_meta[$meta_key][0];
                $all_spec_keys[$meta_key] = $label;
            }
        }
        
        // Nếu vẫn không có thông số, thử lấy tất cả meta fields không bắt đầu với _
        if (empty($product_specs)) {
            foreach ($all_meta as $key => $values) {
                if (strpos($key, '_') !== 0 && !empty($values[0])) {
                    $clean_key = str_replace('-', '_', $key);
                    $label = ucwords(str_replace(array('-', '_'), ' ', $key));
                    $product_specs[$clean_key] = $values[0];
                    $all_spec_keys[$clean_key] = $label;
                }
            }
        }
        
        $all_specs[$product_id] = $product_specs;
    }
    
    // Sắp xếp specs theo thứ tự ưu tiên
    $priority_specs = array(
        'thuong-hieu-brand' => 'Thương hiệu',
        'man-hinh-display' => 'Màn hình', 
        'chip-xu-ly-cpu' => 'CPU',
        'ram' => 'RAM',
        'bo-nho-storage' => 'Bộ nhớ trong',
        'camera' => 'Camera chính',
        'camera-truoc' => 'Camera trước',
        'pin' => 'Pin',
        'he-dieu-hanh' => 'Hệ điều hành'
    );
    
    // Merge priority specs với các specs khác
    $ordered_specs = array_merge($priority_specs, array_diff($all_spec_keys, $priority_specs));
    
    // Build compare table HTML
    $html = '<thead><tr><th class="spec-label">Thông số</th>';
    
    foreach ($products as $product) {
        $html .= '<th class="product-column">';
        $html .= '<div class="product-header">';
        $html .= '<img src="' . esc_url($product['image']) . '" alt="' . esc_attr($product['title']) . '" style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px;">';
        $html .= '<h4 style="margin: 10px 0 5px 0; font-size: 14px;"><a href="' . esc_url($product['url']) . '">' . esc_html($product['title']) . '</a></h4>';
        $html .= '<div class="price" style="font-weight: bold; color: #e74c3c;">' . $product['price'] . '</div>';
        $html .= '<button class="remove-from-compare" data-product-id="' . $product['id'] . '" style="background: #dc3545; color: white; border: none; padding: 4px 8px; border-radius: 4px; font-size: 12px; margin-top: 5px; cursor: pointer;">✕ Xóa</button>';
        $html .= '</div>';
        $html .= '</th>';
    }
    $html .= '</tr></thead><tbody>';
    
    // Add specs rows
    foreach ($ordered_specs as $spec_key => $spec_label) {
        // Chỉ hiển thị spec nếu ít nhất 1 sản phẩm có thông tin này
        $has_data = false;
        foreach ($products as $product_id => $product) {
            if (isset($all_specs[$product_id][$spec_key]) && !empty($all_specs[$product_id][$spec_key])) {
                $has_data = true;
                break;
            }
        }
        
        if ($has_data) {
            $html .= '<tr>';
            $html .= '<td class="spec-label" style="background: #f8f9fa; font-weight: bold; padding: 12px;">' . esc_html($spec_label) . '</td>';
            
            foreach ($products as $product_id => $product) {
                $value = isset($all_specs[$product_id][$spec_key]) && !empty($all_specs[$product_id][$spec_key]) 
                    ? $all_specs[$product_id][$spec_key] 
                    : '<span style="color: #6c757d; font-style: italic;">Không có thông tin</span>';
                $html .= '<td class="spec-value" style="padding: 12px; vertical-align: top;">' . $value . '</td>';
            }
            
            $html .= '</tr>';
        }
    }
    
    $html .= '</tbody>';
    
    wp_send_json_success($html);
}

// Đảm bảo action được đăng ký
add_action('wp_ajax_phonestore_load_compare_table', 'phonestore_load_compare_table');
add_action('wp_ajax_nopriv_phonestore_load_compare_table', 'phonestore_load_compare_table');

// Hàm helper để debug attributes của sản phẩm
function phonestore_debug_product_attributes_detailed($product_id) {
    if (!current_user_can('administrator')) return;
    
    $product = wc_get_product($product_id);
    if (!$product) return;
    
    echo '<div style="background: #f1f1f1; padding: 20px; margin: 20px 0; border: 1px solid #ddd;">';
    echo '<h3>Debug Attributes for Product ID: ' . $product_id . '</h3>';
    
    // WooCommerce Attributes
    echo '<h4>WooCommerce Attributes:</h4>';
    $wc_attributes = $product->get_attributes();
    if (!empty($wc_attributes)) {
        foreach ($wc_attributes as $attribute) {
            $name = $attribute->get_name();
            $label = wc_attribute_label($name);
            echo '<strong>' . $label . ' (' . $name . ')</strong>: ';
            
            if ($attribute->is_taxonomy()) {
                $values = wc_get_product_terms($product_id, $name, array('fields' => 'names'));
                echo implode(', ', $values);
            } else {
                echo implode(', ', $attribute->get_options());
            }
            echo '<br>';
        }
    } else {
        echo 'Không có WooCommerce attributes<br>';
    }
    
    // Post Meta
    echo '<h4>Post Meta Fields:</h4>';
    $all_meta = get_post_meta($product_id);
    foreach ($all_meta as $key => $values) {
        if (strpos($key, '_') !== 0) { // Chỉ hiển thị meta không bắt đầu với _
            echo '<strong>' . $key . '</strong>: ' . (is_array($values) ? implode(', ', $values) : $values) . '<br>';
        }
    }
    
    echo '</div>';
}

// Thêm vào footer của single product để admin có thể debug
function phonestore_admin_debug_footer() {
    if (is_product() && current_user_can('administrator') && isset($_GET['debug_attrs'])) {
        global $post;
        phonestore_debug_product_attributes_detailed($post->ID);
    }
}
add_action('wp_footer', 'phonestore_admin_debug_footer');

// Contact form handler
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
    
    // Store in database
    $contact_data = array(
        'post_title' => 'Liên hệ từ ' . $name . ' - ' . date('d/m/Y H:i'),
        'post_content' => $message,
        'post_status' => 'private',
        'post_type' => 'contact',
        'meta_input' => array(
            'contact_name' => $name,
            'contact_phone' => $phone,
            'contact_email' => $email,
            'contact_subject' => $subject,
            'contact_ip' => $_SERVER['REMOTE_ADDR'],
            'contact_date' => current_time('mysql')
        )
    );
    
    $contact_id = wp_insert_post($contact_data);
    
    if (is_wp_error($contact_id)) {
        wp_send_json_error(array('message' => 'Có lỗi xảy ra khi lưu thông tin. Vui lòng thử lại.'));
        return;
    }
    
    // Send email notification
    $admin_email = get_option('admin_email');
    $site_name = get_bloginfo('name');
    
    $email_subject = '[' . $site_name . '] Liên hệ mới từ ' . $name;
    $email_message = "Tên: $name\nĐiện thoại: $phone\nEmail: $email\nChủ đề: $subject\n\nTin nhắn:\n$message";
    
    wp_mail($admin_email, $email_subject, $email_message);
    
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
        'supports' => array('title', 'editor')
    );
    register_post_type('contact', $args);
}
add_action('init', 'phonestore_register_contact_post_type');

// Custom navigation walker to check if page exists
function phonestore_get_nav_menu_item_url($item_url, $item, $args, $depth) {
    // Check if this is our custom pages
    if (strpos($item_url, 'lien-he') !== false) {
        $page = get_page_by_path('lien-he');
        if ($page) {
            return get_permalink($page->ID);
        }
    }
    
    if (strpos($item_url, 'so-sanh-san-pham') !== false) {
        $page = get_page_by_path('so-sanh-san-pham');
        if ($page) {
            return get_permalink($page->ID);
        }
    }
    
    return $item_url;
}
add_filter('nav_menu_link_attributes', 'phonestore_get_nav_menu_item_url', 10, 4);

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

// Security and performance optimizations
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
wp_deregister_script('wp-embed');

// Check WooCommerce installation
function phonestore_check_woocommerce() {
    if (is_admin() && current_user_can('administrator')) {
        if (!class_exists('WooCommerce')) {
            echo '<div class="notice notice-warning"><p><strong>PhoneStore Theme:</strong> Vui lòng cài đặt và kích hoạt WooCommerce plugin để theme hoạt động đầy đủ.</p></div>';
        }
    }
}
add_action('admin_notices', 'phonestore_check_woocommerce');

// Create sample products function (run once)
function phonestore_create_sample_products() {
    // Only run if no products exist
    $existing_products = get_posts(array(
        'post_type' => 'product',
        'posts_per_page' => 1
    ));
    
    if (empty($existing_products) && class_exists('WC_Product_Simple')) {
        // Create sample products
        $sample_products = array(
            array(
                'name' => 'iPhone 15 Pro',
                'price' => '29990000',
                'description' => 'iPhone mới nhất với chip A17 Pro',
                'specs' => array(
                    'brand' => 'iphone',
                    'display_size' => '6.1 inch',
                    'cpu' => 'Apple A17 Pro',
                    'ram' => '8gb',
                    'storage' => '256gb',
                    'rear_camera' => '48MP + 12MP + 12MP',
                    'battery' => '3274 mAh',
                    'os' => 'iOS 17'
                )
            ),
            array(
                'name' => 'Samsung Galaxy S24',
                'price' => '22990000', 
                'description' => 'Galaxy S24 với AI Galaxy',
                'specs' => array(
                    'brand' => 'samsung',
                    'display_size' => '6.2 inch',
                    'cpu' => 'Snapdragon 8 Gen 3',
                    'ram' => '8gb',
                    'storage' => '256gb',
                    'rear_camera' => '50MP + 12MP + 10MP',
                    'battery' => '4000 mAh',
                    'os' => 'Android 14'
                )
            ),
            array(
               'name' => 'Xiaomi 14',
               'price' => '15990000',
               'description' => 'Xiaomi 14 với Snapdragon 8 Gen 3',
               'specs' => array(
                   'brand' => 'xiaomi',
                   'display_size' => '6.36 inch',
                   'cpu' => 'Snapdragon 8 Gen 3',
                   'ram' => '12gb',
                   'storage' => '256gb',
                   'rear_camera' => '50MP + 50MP + 50MP',
                   'battery' => '4610 mAh',
                   'os' => 'Android 14'
               )
           )
       );
       
       foreach ($sample_products as $product_data) {
           $product = new WC_Product_Simple();
           $product->set_name($product_data['name']);
           $product->set_status('publish');
           $product->set_featured(false);
           $product->set_catalog_visibility('visible');
           $product->set_description($product_data['description']);
           $product->set_price($product_data['price']);
           $product->set_regular_price($product_data['price']);
           $product->set_stock_status('instock');
           $product->set_manage_stock(false);
           $product->set_sold_individually(false);
           $product->save();
           
           // Add specs as meta data
           foreach ($product_data['specs'] as $key => $value) {
               update_post_meta($product->get_id(), $key, $value);
           }
       }
   }
}

// Enqueue WooCommerce cart scripts
function phonestore_enqueue_cart_scripts() {
    if (is_cart()) {
        // Enqueue WooCommerce cart scripts
        wp_enqueue_script('wc-cart');
        wp_enqueue_script('woocommerce');
        
        // Add cart update parameters
        wp_localize_script('phonestore-script', 'wc_cart_params', array(
            'wc_ajax_url' => WC_AJAX::get_endpoint('%%endpoint%%'),
            'update_cart_nonce' => wp_create_nonce('update-cart'),
            'apply_coupon_nonce' => wp_create_nonce('apply-coupon'),
            'remove_coupon_nonce' => wp_create_nonce('remove-coupon')
        ));
    }
}
add_action('wp_enqueue_scripts', 'phonestore_enqueue_cart_scripts');

// Handle AJAX cart updates
function phonestore_ajax_update_cart() {
    if (!wp_verify_nonce($_POST['woocommerce-cart-nonce'], 'woocommerce-cart')) {
        wp_die('Security check failed');
    }
    
    $cart_updated = false;
    $cart = WC()->cart->get_cart();
    
    if (isset($_POST['cart']) && is_array($_POST['cart'])) {
        foreach ($_POST['cart'] as $cart_item_key => $values) {
            if (isset($cart[$cart_item_key])) {
                $quantity = (int) $values['qty'];
                if ($quantity <= 0) {
                    WC()->cart->remove_cart_item($cart_item_key);
                } else {
                    WC()->cart->set_quantity($cart_item_key, $quantity);
                }
                $cart_updated = true;
            }
        }
    }
    
    if ($cart_updated) {
        WC()->cart->calculate_totals();
        wc_add_notice('Giỏ hàng đã được cập nhật.', 'success');
    }
    
    wp_send_json_success(array(
        'fragments' => apply_filters('woocommerce_add_to_cart_fragments', array()),
        'cart_hash' => WC()->cart->get_cart_hash()
    ));
}
add_action('wp_ajax_phonestore_update_cart', 'phonestore_ajax_update_cart');
add_action('wp_ajax_nopriv_phonestore_update_cart', 'phonestore_ajax_update_cart');

// Improve cart notices
// Sửa function improve cart notices
function phonestore_improve_cart_notices($message, $notice_type = 'notice') {
    if (is_cart() || is_checkout()) {
        $icons = array(
            'success' => '✅',
            'error' => '❌', 
            'notice' => 'ℹ️'
        );
        
        $icon = isset($icons[$notice_type]) ? $icons[$notice_type] . ' ' : '';
        return $icon . $message;
    }
    return $message;
}
add_filter('woocommerce_add_message', 'phonestore_improve_cart_notices', 10, 2);
add_filter('woocommerce_add_success_notice', 'phonestore_improve_cart_notices', 10, 2);
add_filter('woocommerce_add_error_notice', 'phonestore_improve_cart_notices', 10, 2);
add_filter('woocommerce_add_notice', 'phonestore_improve_cart_notices', 10, 2);

// Add cart update button state management
function phonestore_cart_update_script() {
    if (is_cart()) {
        ?>
        <script>
        jQuery(document).ready(function($) {
            // Monitor form changes
            let formChanged = false;
            
            $('.woocommerce-cart-form').on('change', 'input', function() {
                formChanged = true;
                $('.update-cart').prop('disabled', false);
            });
            
            // Reset after successful update
            $('.woocommerce-cart-form').on('submit', function() {
                formChanged = false;
            });
            
            // Warning when leaving page with unsaved changes
            $(window).on('beforeunload', function() {
                if (formChanged) {
                    return 'Bạn có thay đổi chưa được lưu. Bạn có chắc muốn rời khỏi trang?';
                }
            });
        });
        </script>
        <?php
    }
}

add_action('wp_footer', 'phonestore_cart_update_script');

// Add custom cart validation
function phonestore_validate_cart_quantities($passed, $product_id, $quantity) {
    $product = wc_get_product($product_id);
    
    if (!$product) {
        return false;
    }
    
    // Check stock
    if (!$product->has_enough_stock($quantity)) {
        wc_add_notice(sprintf(
            'Xin lỗi, chúng tôi chỉ còn %d sản phẩm "%s" trong kho.',
            $product->get_stock_quantity(),
            $product->get_name()
        ), 'error');
        return false;
    }
    
    // Check maximum quantity
    $max_quantity = $product->get_max_purchase_quantity();
    if ($max_quantity > 0 && $quantity > $max_quantity) {
        wc_add_notice(sprintf(
            'Bạn chỉ có thể mua tối đa %d sản phẩm "%s".',
            $max_quantity,
            $product->get_name()
        ), 'error');
        return false;
    }
    
    return $passed;
}
add_filter('woocommerce_add_to_cart_validation', 'phonestore_validate_cart_quantities', 10, 3);

// Customize remove from cart messages
function phonestore_customize_remove_cart_message($message, $product_name) {
    return sprintf('🗑️ Đã xóa "%s" khỏi giỏ hàng.', $product_name);
}
add_filter('wc_add_to_cart_message_html', 'phonestore_customize_remove_cart_message', 10, 2);

// Uncomment this line to create sample products (run once)
// add_action('wp_loaded', 'phonestore_create_sample_products');

// Handle proceed to checkout with invoice email
function phonestore_handle_proceed_to_checkout() {
    // Check if this is a proceed to checkout request
    if (isset($_POST['proceed_to_checkout']) && wp_verify_nonce($_POST['checkout_nonce'], 'proceed_to_checkout')) {
        
        // Check if user is logged in
        if (!is_user_logged_in()) {
            wc_add_notice('Vui lòng đăng nhập để tiếp tục thanh toán.', 'error');
            return;
        }
        
        $user = wp_get_current_user();
        $cart = WC()->cart;
        
        if ($cart->is_empty()) {
            wc_add_notice('Giỏ hàng của bạn đang trống.', 'error');
            return;
        }
        
        // Get user billing information
        $billing_info = array(
            'first_name' => get_user_meta($user->ID, 'billing_first_name', true) ?: $user->first_name,
            'last_name' => get_user_meta($user->ID, 'billing_last_name', true) ?: $user->last_name,
            'email' => get_user_meta($user->ID, 'billing_email', true) ?: $user->user_email,
            'phone' => get_user_meta($user->ID, 'billing_phone', true),
            'address_1' => get_user_meta($user->ID, 'billing_address_1', true),
            'address_2' => get_user_meta($user->ID, 'billing_address_2', true),
            'city' => get_user_meta($user->ID, 'billing_city', true),
            'state' => get_user_meta($user->ID, 'billing_state', true),
            'postcode' => get_user_meta($user->ID, 'billing_postcode', true),
            'country' => get_user_meta($user->ID, 'billing_country', true)
        );
        
        // Generate invoice number
        $invoice_number = 'INV-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        
        // Send invoice email
        if (phonestore_send_invoice_email($billing_info, $cart, $invoice_number)) {
             wc_add_notice('📧 Hóa đơn đã được gửi đến email của bạn. Vui lòng kiểm tra để xác nhận đơn hàng.', 'success');
            
            // Store invoice info in session for checkout page
            WC()->session->set('pending_invoice', array(
                'invoice_number' => $invoice_number,
                'sent_time' => current_time('mysql'),
                'customer_email' => $billing_info['email']
            ));
        } else {
            wc_add_notice('❌ Có lỗi xảy ra khi gửi hóa đơn. Vui lòng thử lại.', 'error');
        }
    }
}
add_action('template_redirect', 'phonestore_handle_proceed_to_checkout');

// Function to send invoice email
function phonestore_send_invoice_email($billing_info, $cart, $invoice_number) {
    $site_name = get_bloginfo('name');
    $full_name = trim($billing_info['first_name'] . ' ' . $billing_info['last_name']);
    $customer_email = $billing_info['email'];
    
    if (empty($customer_email)) {
        return false;
    }
    
    // Build full address
    $address_parts = array_filter(array(
        $billing_info['address_1'],
        $billing_info['address_2'],
        $billing_info['city'],
        $billing_info['state'],
        $billing_info['postcode']
    ));
    $full_address = implode(', ', $address_parts);
    
    // Email subject
    $subject = '📧 Hóa đơn tạm thời - ' . $invoice_number . ' từ ' . $site_name;
    
    // Build email content
    $message = phonestore_build_invoice_email_content($billing_info, $cart, $invoice_number, $full_name, $full_address);
    
    // Email headers
    $headers = array(
        'Content-Type: text/html; charset=UTF-8',
        'From: ' . $site_name . ' <noreply@' . $_SERVER['HTTP_HOST'] . '>',
        'Reply-To: ' . get_option('admin_email')
    );
    
    // Send email
    $sent = wp_mail($customer_email, $subject, $message, $headers);
    
    // Also send copy to admin
    if ($sent) {
        $admin_subject = '[ADMIN] Hóa đơn mới - ' . $invoice_number;
        $admin_message = phonestore_build_admin_invoice_notification($billing_info, $cart, $invoice_number, $full_name);
        wp_mail(get_option('admin_email'), $admin_subject, $admin_message, $headers);
        
        // Log invoice
        phonestore_log_invoice($invoice_number, $billing_info, $cart);
    }
    
    return $sent;
}

// Build customer invoice email content
// Build customer invoice email content
function phonestore_build_invoice_email_content($billing_info, $cart, $invoice_number, $full_name, $full_address) {
    $site_name = get_bloginfo('name');
    $currency_symbol = get_woocommerce_currency_symbol();
    
    ob_start();
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Hóa đơn - <?php echo $invoice_number; ?></title>
        <style>
            body { 
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
                line-height: 1.6; 
                color: #333; 
                margin: 0; 
                padding: 0;
                background-color: #f8f9fa;
            }
            .container { 
                max-width: 800px; 
                margin: 0 auto; 
                background: white;
                box-shadow: 0 0 20px rgba(0,0,0,0.1);
            }
            .header { 
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white; 
                padding: 40px 30px; 
                text-align: center;
            }
            .header h1 {
                margin: 0 0 10px 0;
                font-size: 28px;
                font-weight: 700;
            }
            .header p {
                margin: 0;
                font-size: 16px;
                opacity: 0.9;
            }
            .content { 
                padding: 40px 30px;
            }
            .invoice-info {
                background: #f8f9fa;
                padding: 25px;
                border-radius: 12px;
                margin-bottom: 30px;
                border-left: 4px solid #667eea;
            }
            .invoice-info h2 {
                color: #667eea;
                margin-top: 0;
                font-size: 20px;
            }
            .info-row {
                display: flex;
                justify-content: space-between;
                margin-bottom: 12px;
                flex-wrap: wrap;
            }
            .info-label {
                font-weight: 600;
                color: #495057;
                min-width: 120px;
            }
            .info-value {
                color: #212529;
                flex: 1;
                text-align: right;
            }
            .customer-info {
                background: #e3f2fd;
                padding: 25px;
                border-radius: 12px;
                margin-bottom: 30px;
            }
            .customer-info h3 {
                color: #1976d2;
                margin-top: 0;
                font-size: 18px;
            }
            .items-table { 
                width: 100%; 
                border-collapse: collapse; 
                margin: 25px 0;
                background: white;
                border-radius: 8px;
                overflow: hidden;
                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            }
            .items-table th, .items-table td { 
                padding: 15px; 
                text-align: left; 
                border-bottom: 1px solid #dee2e6;
            }
            .items-table th { 
                background: #495057; 
                color: white; 
                font-weight: 600;
                text-transform: uppercase;
                font-size: 12px;
                letter-spacing: 1px;
            }
            .items-table tr:nth-child(even) {
                background: #f8f9fa;
            }
            .items-table tr:hover {
                background: #e9ecef;
            }
            .text-right { text-align: right; }
            .text-center { text-align: center; }
            .subtotal-row { background: #f1f3f4; }
            .total-row { 
                background: #667eea; 
                color: white; 
                font-weight: bold;
            }
            .total-row td { 
                font-size: 18px; 
                padding: 20px 15px;
            }
            .footer {
                background: #2c3e50;
                color: white;
                padding: 30px;
                text-align: center;
            }
            .next-steps {
                background: #fff3cd;
                border: 1px solid #ffeaa7;
                border-radius: 8px;
                padding: 20px;
                margin: 20px 0;
            }
            .next-steps h4 {
                color: #856404;
                margin-top: 0;
            }
            .next-steps ol {
                color: #856404;
                margin: 0;
                padding-left: 20px;
            }
            @media (max-width: 600px) {
                .container { margin: 0; border-radius: 0; }
                .content { padding: 20px; }
                .info-row { flex-direction: column; }
                .info-value { text-align: left; margin-top: 5px; }
                .items-table { font-size: 14px; }
                .items-table th, .items-table td { padding: 10px 8px; }
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <h1>📧 HÓA ĐƠN TẠM THỜI</h1>
                <p>Cảm ơn bạn đã chọn <?php echo $site_name; ?></p>
            </div>
            
            <div class="content">
                <div class="invoice-info">
                    <h2>📋 Thông tin hóa đơn</h2>
                    <div class="info-row">
                        <span class="info-label">Số hóa đơn:</span>
                        <span class="info-value"><strong><?php echo $invoice_number; ?></strong></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Ngày tạo:</span>
                        <span class="info-value"><?php echo date('d/m/Y H:i:s'); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Trạng thái:</span>
                        <span class="info-value"><strong style="color: #ffc107;">⏳ Chờ xác nhận</strong></span>
                    </div>
                </div>

                <div class="customer-info">
                    <h3>👤 Thông tin khách hàng</h3>
                    <div class="info-row">
                        <span class="info-label">Họ và tên:</span>
                        <span class="info-value"><?php echo esc_html($full_name); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Email:</span>
                        <span class="info-value"><?php echo esc_html($billing_info['email']); ?></span>
                    </div>
                    <?php if ($billing_info['phone']): ?>
                    <div class="info-row">
                        <span class="info-label">Điện thoại:</span>
                        <span class="info-value"><?php echo esc_html($billing_info['phone']); ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if ($full_address): ?>
                    <div class="info-row">
                        <span class="info-label">Địa chỉ:</span>
                        <span class="info-value"><?php echo esc_html($full_address); ?></span>
                    </div>
                    <?php endif; ?>
                </div>

                <h3>🛒 Chi tiết đơn hàng</h3>
                <table class="items-table">
                    <thead>
                        <tr>
                            <th style="width: 60%">Sản phẩm</th>
                            <th class="text-center" style="width: 15%">Số lượng</th>
                            <th class="text-right" style="width: 25%">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart->get_cart() as $cart_item): 
                            $product = $cart_item['data'];
                            $quantity = $cart_item['quantity'];
                            $subtotal = $cart->get_product_subtotal($product, $quantity);
                        ?>
                        <tr>
                            <td>
                                <strong><?php echo esc_html($product->get_name()); ?></strong>
                                <?php
                                // Show product specs if available
                                if (function_exists('get_field')) {
                                    $ram = get_field('ram', $product->get_id());
                                    $storage = get_field('storage', $product->get_id());
                                    if ($ram || $storage) {
                                        echo '<br><small style="color: #666;">';
                                        if ($ram) echo '💾 RAM: ' . strtoupper($ram) . ' ';
                                        if ($storage) echo '💿 Bộ nhớ: ' . strtoupper($storage);
                                        echo '</small>';
                                    }
                                }
                                ?>
                                <br><small style="color: #888;">Đơn giá: <?php echo wc_price($product->get_price()); ?></small>
                            </td>
                            <td class="text-center">
                                <strong><?php echo $quantity; ?></strong>
                            </td>
                            <td class="text-right">
                                <strong><?php echo $subtotal; ?></strong>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        
                        <tr class="subtotal-row">
                            <td colspan="2"><strong>Tạm tính</strong></td>
                            <td class="text-right"><strong><?php echo wc_price($cart->get_cart_contents_total()); ?></strong></td>
                        </tr>
                        
                        <?php 
                        $fee_total = $cart->get_fee_total();
                        if ($fee_total > 0): ?>
                        <tr>
                            <td colspan="2">🚚 Phí vận chuyển</td>
                            <td class="text-right"><?php echo wc_price($fee_total); ?></td>
                        </tr>
                        <?php endif; ?>
                        
                        <tr class="total-row">
                            <td colspan="2"><strong>🏆 TỔNG CỘNG</strong></td>
                            <td class="text-right"><strong><?php echo wc_price($cart->get_total('edit')); ?></strong></td>
                        </tr>
                    </tbody>
                </table>

                <div class="next-steps">
                    <h4>📋 Các bước tiếp theo:</h4>
                    <ol>
                        <li><strong>Xác nhận đơn hàng:</strong> Vui lòng kiểm tra lại thông tin đơn hàng ở trên</li>
                        <li><strong>Liên hệ xác nhận:</strong> Chúng tôi sẽ gọi điện xác nhận trong vòng 24h</li>
                        <li><strong>Thanh toán:</strong> Thanh toán khi nhận hàng hoặc chuyển khoản trước</li>
                        <li><strong>Giao hàng:</strong> Sản phẩm sẽ được giao trong 1-3 ngày làm việc</li>
                    </ol>
                </div>

                <div style="background: #e8f5e8; padding: 20px; border-radius: 8px; margin: 20px 0;">
                    <h4 style="color: #2d5016; margin-top: 0;">📝 Lưu ý quan trọng:</h4>
                    <ul style="color: #2d5016; margin: 0; padding-left: 20px;">
                        <li>Đây là hóa đơn tạm thời, chưa phải hóa đơn chính thức</li>
                        <li>Vui lòng giữ lại email này để theo dõi đơn hàng</li>
                        <li>Mọi thắc mắc vui lòng liên hệ hotline: 0123.456.789</li>
                        <li>Sản phẩm có thể thay đổi tùy theo tình trạng kho</li>
                    </ul>
                </div>
            </div>
            
            <div class="footer">
                <h3>📞 Thông tin liên hệ</h3>
                <p>
                    <strong><?php echo $site_name; ?></strong><br>
                    📍 Địa chỉ: Cái Răng, Cần Thơ, Việt Nam<br>
                    📞 Hotline: 0123.456.789<br>
                    📧 Email: info@<?php echo $_SERVER['HTTP_HOST']; ?><br>
                    🌐 Website: <?php echo home_url(); ?>
                </p>
                <p style="margin-top: 20px; font-size: 12px; opacity: 0.8;">
                    <small>© <?php echo date('Y'); ?> <?php echo $site_name; ?>. 
                    Tất cả các quyền được bảo lưu.</small>
                </p>
            </div>
        </div>
    </body>
    </html>
    <?php
    return ob_get_clean();
}

// Build admin notification email
function phonestore_build_admin_invoice_notification($billing_info, $cart, $invoice_number, $full_name) {
    $site_name = get_bloginfo('name');
    
    ob_start();
    ?>
    <h2>🔔 Hóa đơn mới từ website</h2>
    
    <h3>📋 Thông tin hóa đơn:</h3>
    <ul>
        <li><strong>Số hóa đơn:</strong> <?php echo $invoice_number; ?></li>
        <li><strong>Thời gian:</strong> <?php echo date('d/m/Y H:i:s'); ?></li>
        <li><strong>Tổng tiền:</strong> <?php echo wc_price($cart->get_total('edit')); ?></li>
    </ul>
    
    <h3>👤 Thông tin khách hàng:</h3>
    <ul>
        <li><strong>Tên:</strong> <?php echo esc_html($full_name); ?></li>
        <li><strong>Email:</strong> <?php echo esc_html($billing_info['email']); ?></li>
        <li><strong>Điện thoại:</strong> <?php echo esc_html($billing_info['phone']); ?></li>
    </ul>
    
    <h3>🛒 Sản phẩm:</h3>
    <ul>
    <?php foreach ($cart->get_cart() as $cart_item): 
        $product = $cart_item['data'];
        $quantity = $cart_item['quantity'];
    ?>
        <li><?php echo esc_html($product->get_name()); ?> x <?php echo $quantity; ?> = <?php echo $cart->get_product_subtotal($product, $quantity); ?></li>
    <?php endforeach; ?>
    </ul>
    
    <p><strong>⚠️ Vui lòng liên hệ khách hàng để xác nhận đơn hàng trong 24h.</strong></p>
    <?php
    return ob_get_clean();
}

// Log invoice for tracking
function phonestore_log_invoice($invoice_number, $billing_info, $cart) {
    $log_data = array(
        'invoice_number' => $invoice_number,
        'customer_email' => $billing_info['email'],
        'customer_name' => trim($billing_info['first_name'] . ' ' . $billing_info['last_name']),
        'total_amount' => $cart->get_total('edit'),
        'items_count' => $cart->get_cart_contents_count(),
        'created_at' => current_time('mysql'),
        'status' => 'pending'
    );
    
    // Store in database or log file
    error_log('Invoice created: ' . json_encode($log_data));
    
    // You can also store in custom table if needed
    // global $wpdb;
    // $wpdb->insert('wp_phonestore_invoices', $log_data);
}

// Handle resend invoice AJAX
function phonestore_resend_invoice() {
    if (!wp_verify_nonce($_POST['nonce'], 'phonestore_nonce')) {
        wp_send_json_error('Security check failed');
        return;
    }
    
    if (!is_user_logged_in()) {
        wp_send_json_error('User not logged in');
        return;
    }
    
    $invoice_number = sanitize_text_field($_POST['invoice_number']);
    
    if (empty($invoice_number)) {
        wp_send_json_error('Invalid invoice number');
        return;
    }
    
    $user = wp_get_current_user();
    $cart = WC()->cart;
    
    if ($cart->is_empty()) {
        wp_send_json_error('Cart is empty');
        return;
    }
    
    // Get user billing information
    $billing_info = array(
        'first_name' => get_user_meta($user->ID, 'billing_first_name', true) ?: $user->first_name,
        'last_name' => get_user_meta($user->ID, 'billing_last_name', true) ?: $user->last_name,
        'email' => get_user_meta($user->ID, 'billing_email', true) ?: $user->user_email,
        'phone' => get_user_meta($user->ID, 'billing_phone', true),
        'address_1' => get_user_meta($user->ID, 'billing_address_1', true),
        'address_2' => get_user_meta($user->ID, 'billing_address_2', true),
        'city' => get_user_meta($user->ID, 'billing_city', true),
        'state' => get_user_meta($user->ID, 'billing_state', true),
        'postcode' => get_user_meta($user->ID, 'billing_postcode', true),
        'country' => get_user_meta($user->ID, 'billing_country', true)
    );
    
    // Resend invoice email
    if (phonestore_send_invoice_email($billing_info, $cart, $invoice_number)) {
        wp_send_json_success('Invoice resent successfully');
    } else {
        wp_send_json_error('Failed to resend invoice');
    }
}
add_action('wp_ajax_phonestore_resend_invoice', 'phonestore_resend_invoice');
add_action('wp_ajax_nopriv_phonestore_resend_invoice', 'phonestore_resend_invoice');

// Debug function to check if pages exist and are properly linked
function phonestore_debug_pages() {
    if (current_user_can('administrator') && isset($_GET['debug_pages'])) {
        echo '<div style="background: white; padding: 20px; margin: 20px; border: 2px solid #007cba; border-radius: 10px;">';
        echo '<h3>🔧 Debug Pages Info</h3>';
        
        // Check contact page
        $contact_page = get_page_by_path('lien-he');
        echo '<p><strong>Contact Page (lien-he):</strong> ';
        if ($contact_page) {
            echo '✅ Found - ID: ' . $contact_page->ID . ' - URL: <a href="' . get_permalink($contact_page->ID) . '">' . get_permalink($contact_page->ID) . '</a>';
        } else {
            echo '❌ Not found';
        }
        echo '</p>';
        
        // Check compare page
        $compare_page = get_page_by_path('so-sanh-san-pham');
        echo '<p><strong>Compare Page (so-sanh-san-pham):</strong> ';
        if ($compare_page) {
            echo '✅ Found - ID: ' . $compare_page->ID . ' - URL: <a href="' . get_permalink($compare_page->ID) . '">' . get_permalink($compare_page->ID) . '</a>';
        } else {
            echo '❌ Not found';
        }
        echo '</p>';
        
        // Check current page
        echo '<p><strong>Current Page:</strong> ' . get_the_title() . ' (ID: ' . get_the_ID() . ')</p>';
        echo '<p><strong>Current URL:</strong> ' . home_url($_SERVER['REQUEST_URI']) . '</p>';
        
        // Check menu
        $menu = wp_get_nav_menu_object('Main Menu');
        echo '<p><strong>Main Menu:</strong> ';
        if ($menu) {
            echo '✅ Found - ID: ' . $menu->term_id;
        } else {
            echo '❌ Not found';
        }
        echo '</p>';
        
        echo '<p><em>Add ?debug_pages=1 to any URL to see this debug info</em></p>';
        echo '</div>';
    }
}
add_action('wp_footer', 'phonestore_debug_pages');

// Force create pages if they don't exist
function phonestore_ensure_pages_exist() {
    // Create contact page if it doesn't exist
    $contact_page = get_page_by_path('lien-he');
    if (!$contact_page) {
        $contact_id = wp_insert_post(array(
            'post_title' => 'Liên Hệ',
            'post_name' => 'lien-he',
            'post_content' => '<!-- This content will be overridden by the template -->',
            'post_status' => 'publish',
            'post_type' => 'page',
            'page_template' => 'page-lien-he.php'
        ));
        
        if ($contact_id) {
            update_post_meta($contact_id, '_wp_page_template', 'page-lien-he.php');
        }
    }
    
    // Create compare page if it doesn't exist
    $compare_page = get_page_by_path('so-sanh-san-pham');
    if (!$compare_page) {
        $compare_id = wp_insert_post(array(
            'post_title' => 'So Sánh Sản Phẩm',
            'post_name' => 'so-sanh-san-pham',
            'post_content' => '<!-- This content will be overridden by the template -->',
            'post_status' => 'publish',
            'post_type' => 'page',
            'page_template' => 'page-so-sanh-san-pham.php'
        ));
        
        if ($compare_id) {
            update_post_meta($compare_id, '_wp_page_template', 'page-so-sanh-san-pham.php');
        }
    }
}

// Run when admin visits any page
add_action('admin_init', 'phonestore_ensure_pages_exist');

// Also run on theme activation
add_action('after_switch_theme', 'phonestore_ensure_pages_exist');

// Fix menu creation to include proper page links
function phonestore_recreate_menu_with_pages() {
    // Delete existing menu
    $existing_menu = wp_get_nav_menu_object('Main Menu');
    if ($existing_menu) {
        wp_delete_nav_menu($existing_menu->term_id);
    }
    
    // Create new menu
    $menu_id = wp_create_nav_menu('Main Menu');
    
    if (!is_wp_error($menu_id)) {
        // Add Home
        wp_update_nav_menu_item($menu_id, 0, array(
            'menu-item-title' => '🏠 Trang chủ',
            'menu-item-url' => home_url('/'),
            'menu-item-status' => 'publish',
            'menu-item-type' => 'custom'
        ));
        
        // Add Shop
        wp_update_nav_menu_item($menu_id, 0, array(
            'menu-item-title' => '🛒 Sản phẩm',
            'menu-item-url' => home_url('/shop/'),
            'menu-item-status' => 'publish',
            'menu-item-type' => 'custom'
        ));
        
        // Add Compare page
        $compare_page = get_page_by_path('so-sanh-san-pham');
        if ($compare_page) {
            wp_update_nav_menu_item($menu_id, 0, array(
                'menu-item-title' => '⚖️ So sánh',
                'menu-item-object' => 'page',
                'menu-item-object-id' => $compare_page->ID,
                'menu-item-type' => 'post_type',
                'menu-item-status' => 'publish'
            ));
        }
        
        // Add Contact page
        $contact_page = get_page_by_path('lien-he');
        if ($contact_page) {
            wp_update_nav_menu_item($menu_id, 0, array(
                'menu-item-title' => '📞 Liên hệ',
                'menu-item-object' => 'page',
                'menu-item-object-id' => $contact_page->ID,
                'menu-item-type' => 'post_type',
                'menu-item-status' => 'publish'
            ));
        }
        
        // Assign menu to location
        $locations = get_theme_mod('nav_menu_locations');
        $locations['primary'] = $menu_id;
        set_theme_mod('nav_menu_locations', $locations);
    }
}

// Add admin notice with button to recreate menu
function phonestore_admin_menu_notice() {
    if (current_user_can('administrator')) {
        $contact_page = get_page_by_path('lien-he');
        $compare_page = get_page_by_path('so-sanh-san-pham');
        
        if (!$contact_page || !$compare_page) {
            echo '<div class="notice notice-warning is-dismissible">';
            echo '<p><strong>PhoneStore Theme:</strong> Một số trang chưa được tạo. ';
            echo '<a href="' . admin_url('?phonestore_create_pages=1') . '" class="button button-primary">Tạo trang ngay</a>';
            echo '</p>';
            echo '</div>';
        }
    }
}
add_action('admin_notices', 'phonestore_admin_menu_notice');

// Handle create pages action
function phonestore_handle_create_pages() {
    if (isset($_GET['phonestore_create_pages']) && current_user_can('administrator')) {
        phonestore_ensure_pages_exist();
        phonestore_recreate_menu_with_pages();
        
        wp_redirect(admin_url('nav-menus.php?created=1'));
        exit;
    }
}
add_action('admin_init', 'phonestore_handle_create_pages');

// AJAX handler for loading products
function phonestore_load_products() {
    if (!wp_verify_nonce($_POST['nonce'], 'phonestore_nonce')) {
        wp_die('Security check failed');
    }
    
    $filters = $_POST['filters'];
    $page = intval($filters['page']) ?: 1;
    $per_page = 12;
    
    $args = array(
        'post_type' => 'product',
        'post_status' => 'publish',
        'posts_per_page' => $per_page,
        'paged' => $page,
        'meta_query' => array('relation' => 'AND')
    );
    
    // Brand filter
    if (!empty($filters['brand'])) {
        $args['meta_query'][] = array(
            'key' => 'brand',
            'value' => $filters['brand'],
            'compare' => '='
        );
    }
    
    // RAM filter
    if (!empty($filters['ram'])) {
        $args['meta_query'][] = array(
            'key' => 'ram',
            'value' => $filters['ram'],
            'compare' => '='
        );
    }
    
    // Storage filter
    if (!empty($filters['storage'])) {
        $args['meta_query'][] = array(
            'key' => 'storage',
            'value' => $filters['storage'],
            'compare' => '='
        );
    }
    
    // Price filter
    if (!empty($filters['price'])) {
        $price_range = explode('-', $filters['price']);
        $min_price = intval($price_range[0]) * 1000000;
        $max_price = intval($price_range[1]) * 1000000;
        
        $args['meta_query'][] = array(
            'key' => '_price',
            'value' => array($min_price, $max_price),
            'type' => 'NUMERIC',
            'compare' => 'BETWEEN'
        );
    }
    
    // Search filter
    if (!empty($filters['search'])) {
        $args['s'] = sanitize_text_field($filters['search']);
    }
    
    // Sorting
    if (!empty($filters['sort'])) {
        switch ($filters['sort']) {
            case 'price-asc':
                $args['meta_key'] = '_price';
                $args['orderby'] = 'meta_value_num';
                $args['order'] = 'ASC';
                break;
            case 'price-desc':
                $args['meta_key'] = '_price';
                $args['orderby'] = 'meta_value_num';
                $args['order'] = 'DESC';
                break;
            case 'name-asc':
                $args['orderby'] = 'title';
                $args['order'] = 'ASC';
                break;
            case 'name-desc':
                $args['orderby'] = 'title';
                $args['order'] = 'DESC';
                break;
            case 'newest':
                $args['orderby'] = 'date';
                $args['order'] = 'DESC';
                break;
        }
    }
    
    $query = new WP_Query($args);
    $products = array();
    
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $product_id = get_the_ID();
            $wc_product = wc_get_product($product_id);
            
            $specs = array();
            if (function_exists('get_field')) {
                $ram = get_field('ram', $product_id);
                $storage = get_field('storage', $product_id);
                $camera = get_field('rear_camera', $product_id);
                
                if ($ram) $specs[] = '💾 ' . strtoupper($ram);
                if ($storage) $specs[] = '💿 ' . strtoupper($storage);
                if ($camera) $specs[] = '📷 ' . $camera;
            }
            
            $products[] = array(
                'id' => $product_id,
                'name' => get_the_title(),
                'price' => $wc_product ? $wc_product->get_price_html() : 'Liên hệ',
                'image' => get_the_post_thumbnail_url($product_id, 'medium') ?: 'https://via.placeholder.com/300x300?text=No+Image',
                'specs' => $specs,
                'url' => get_permalink($product_id)
            );
        }
        wp_reset_postdata();
    }
    
    wp_send_json_success(array(
        'products' => $products,
        'total' => $query->found_posts,
        'pages' => $query->max_num_pages
    ));
}
add_action('wp_ajax_phonestore_load_products', 'phonestore_load_products');
add_action('wp_ajax_nopriv_phonestore_load_products', 'phonestore_load_products');

// Handle shop page filters
function phonestore_handle_shop_filters($query) {
    if (!is_admin() && $query->is_main_query()) {
        if (is_shop() || is_product_category() || is_product_tag()) {
            // Brand filter
            if (isset($_GET['filter_brand']) && !empty($_GET['filter_brand'])) {
                $meta_query = $query->get('meta_query') ?: array();
                $meta_query[] = array(
                    'key' => 'brand',
                    'value' => sanitize_text_field($_GET['filter_brand']),
                    'compare' => '='
                );
                $query->set('meta_query', $meta_query);
            }
            
            // RAM filter
            if (isset($_GET['filter_ram']) && !empty($_GET['filter_ram'])) {
                $meta_query = $query->get('meta_query') ?: array();
                $meta_query[] = array(
                    'key' => 'ram',
                    'value' => sanitize_text_field($_GET['filter_ram']),
                    'compare' => '='
                );
                $query->set('meta_query', $meta_query);
            }
            
            // Storage filter
            if (isset($_GET['filter_storage']) && !empty($_GET['filter_storage'])) {
                $meta_query = $query->get('meta_query') ?: array();
                $meta_query[] = array(
                    'key' => 'storage',
                    'value' => sanitize_text_field($_GET['filter_storage']),
                    'compare' => '='
                );
                $query->set('meta_query', $meta_query);
            }
            
            // Price filter
            if (isset($_GET['filter_price']) && !empty($_GET['filter_price'])) {
                $price_range = explode('-', $_GET['filter_price']);
                if (count($price_range) == 2) {
                    $min_price = intval($price_range[0]) * 1000000;
                    $max_price = intval($price_range[1]) * 1000000;
                    
                    $meta_query = $query->get('meta_query') ?: array();
                    $meta_query[] = array(
                        'key' => '_price',
                        'value' => array($min_price, $max_price),
                        'type' => 'NUMERIC',
                        'compare' => 'BETWEEN'
                    );
                    $query->set('meta_query', $meta_query);
                }
            }
        }
    }
}
add_action('pre_get_posts', 'phonestore_handle_shop_filters');

// Track user registration IP and login activity
function phonestore_track_user_activity() {
   // Track registration IP
   add_action('user_register', function($user_id) {
       update_user_meta($user_id, 'registration_ip', $_SERVER['REMOTE_ADDR']);
       update_user_meta($user_id, 'registration_date', current_time('d/m/Y H:i:s'));
   });
   
   // Track login activity
   add_action('wp_login', function($user_login, $user) {
       update_user_meta($user->ID, 'last_login', current_time('d/m/Y H:i:s'));
       update_user_meta($user->ID, 'last_login_ip', $_SERVER['REMOTE_ADDR']);
   }, 10, 2);
}
add_action('init', 'phonestore_track_user_activity');

// Add custom user fields
function phonestore_add_custom_user_fields($user) {
   ?>
   <h3>Thông tin bổ sung</h3>
   <table class="form-table">
       <tr>
           <th><label for="birth_date">Ngày sinh</label></th>
           <td>
               <input type="date" name="birth_date" id="birth_date" value="<?php echo esc_attr(get_user_meta($user->ID, 'birth_date', true)); ?>" class="regular-text" />
           </td>
       </tr>
       <tr>
           <th><label for="gender">Giới tính</label></th>
           <td>
               <select name="gender" id="gender">
                   <option value="">Chọn giới tính</option>
                   <option value="Nam" <?php selected(get_user_meta($user->ID, 'gender', true), 'Nam'); ?>>Nam</option>
                   <option value="Nữ" <?php selected(get_user_meta($user->ID, 'gender', true), 'Nữ'); ?>>Nữ</option>
                   <option value="Khác" <?php selected(get_user_meta($user->ID, 'gender', true), 'Khác'); ?>>Khác</option>
               </select>
           </td>
       </tr>
   </table>
   <?php
}
add_action('show_user_profile', 'phonestore_add_custom_user_fields');
add_action('edit_user_profile', 'phonestore_add_custom_user_fields');

// Save custom user fields
function phonestore_save_custom_user_fields($user_id) {
   if (!current_user_can('edit_user', $user_id)) {
       return false;
   }
   
   update_user_meta($user_id, 'birth_date', $_POST['birth_date']);
   update_user_meta($user_id, 'gender', $_POST['gender']);
}
add_action('personal_options_update', 'phonestore_save_custom_user_fields');
add_action('edit_user_profile_update', 'phonestore_save_custom_user_fields');

// Add wishlist functionality
function phonestore_add_to_wishlist() {
   if (!wp_verify_nonce($_POST['nonce'], 'phonestore_nonce')) {
       wp_die('Security check failed');
   }
   
   if (!is_user_logged_in()) {
       wp_send_json_error('Vui lòng đăng nhập để thêm vào wishlist');
       return;
   }
   
   $product_id = intval($_POST['product_id']);
   $user_id = get_current_user_id();
   
   $wishlist = get_user_meta($user_id, '_wishlist', true) ?: array();
   
   if (!in_array($product_id, $wishlist)) {
       $wishlist[] = $product_id;
       update_user_meta($user_id, '_wishlist', $wishlist);
       wp_send_json_success('Đã thêm vào wishlist');
   } else {
       wp_send_json_error('Sản phẩm đã có trong wishlist');
   }
}
add_action('wp_ajax_phonestore_add_to_wishlist', 'phonestore_add_to_wishlist');

// Remove from wishlist
function phonestore_remove_from_wishlist() {
   if (!wp_verify_nonce($_POST['nonce'], 'phonestore_nonce')) {
       wp_die('Security check failed');
   }
   
   if (!is_user_logged_in()) {
       wp_send_json_error('Vui lòng đăng nhập');
       return;
   }
   
   $product_id = intval($_POST['product_id']);
   $user_id = get_current_user_id();
   
   $wishlist = get_user_meta($user_id, '_wishlist', true) ?: array();
   $key = array_search($product_id, $wishlist);
   
   if ($key !== false) {
       unset($wishlist[$key]);
       update_user_meta($user_id, '_wishlist', array_values($wishlist));
       wp_send_json_success('Đã xóa khỏi wishlist');
   } else {
       wp_send_json_error('Sản phẩm không có trong wishlist');
   }
}
add_action('wp_ajax_phonestore_remove_from_wishlist', 'phonestore_remove_from_wishlist');

// Custom dashboard widget for admin
function phonestore_add_dashboard_widgets() {
   wp_add_dashboard_widget(
       'phonestore_stats',
       'Thống kê cửa hàng',
       'phonestore_dashboard_widget_function'
   );
}
add_action('wp_dashboard_setup', 'phonestore_add_dashboard_widgets');

function phonestore_dashboard_widget_function() {
   // Get statistics
   $total_users = count_users();
   $total_orders = wp_count_posts('shop_order');
   $total_products = wp_count_posts('product');
   
   echo '<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 15px; text-align: center;">';
   
   echo '<div style="background: #f0f9ff; padding: 15px; border-radius: 8px; border-left: 4px solid #0ea5e9;">';
   echo '<h3 style="margin: 0; color: #0ea5e9;">' . $total_users['total_users'] . '</h3>';
   echo '<p style="margin: 5px 0 0 0; font-size: 12px;">Khách hàng</p>';
   echo '</div>';
   
   echo '<div style="background: #f0fdf4; padding: 15px; border-radius: 8px; border-left: 4px solid #22c55e;">';
   echo '<h3 style="margin: 0; color: #22c55e;">' . ($total_orders->publish ?? 0) . '</h3>';
   echo '<p style="margin: 5px 0 0 0; font-size: 12px;">Đơn hàng</p>';
   echo '</div>';
   
   echo '<div style="background: #fefce8; padding: 15px; border-radius: 8px; border-left: 4px solid #eab308;">';
   echo '<h3 style="margin: 0; color: #eab308;">' . ($total_products->publish ?? 0) . '</h3>';
   echo '<p style="margin: 5px 0 0 0; font-size: 12px;">Sản phẩm</p>';
   echo '</div>';
   
   echo '</div>';
   
   // Recent activity
   echo '<h4 style="margin: 20px 0 10px 0;">Hoạt động gần đây:</h4>';
   
   $recent_users = get_users(array(
       'number' => 5,
       'orderby' => 'registered',
       'order' => 'DESC'
   ));
   
   if ($recent_users) {
       echo '<ul style="margin: 0; padding-left: 20px;">';
       foreach ($recent_users as $user) {
           echo '<li style="margin-bottom: 5px; font-size: 13px;">';
           echo '<strong>' . $user->display_name . '</strong> đã đăng ký ';
           echo human_time_diff(strtotime($user->user_registered)) . ' trước';
           echo '</li>';
       }
       echo '</ul>';
   } else {
       echo '<p style="font-style: italic; color: #666;">Chưa có hoạt động nào.</p>';
   }
}

// Add account menu item to admin bar
function phonestore_admin_bar_account_menu($wp_admin_bar) {
   if (is_user_logged_in() && !is_admin()) {
       $wp_admin_bar->add_node(array(
           'id' => 'phonestore-account',
           'title' => '👤 Tài khoản',
           'href' => wc_get_account_endpoint_url('dashboard'),
       ));
       
       $wp_admin_bar->add_node(array(
           'id' => 'phonestore-orders',
           'parent' => 'phonestore-account',
           'title' => 'Đơn hàng của tôi',
           'href' => wc_get_account_endpoint_url('orders'),
       ));
       
       $wp_admin_bar->add_node(array(
           'id' => 'phonestore-account-details',
           'parent' => 'phonestore-account',
           'title' => 'Thông tin tài khoản',
           'href' => wc_get_account_endpoint_url('edit-account'),
       ));
       
       $wp_admin_bar->add_node(array(
           'id' => 'phonestore-logout',
           'parent' => 'phonestore-account',
           'title' => 'Đăng xuất',
           'href' => wp_logout_url(home_url()),
       ));
   }
}
add_action('admin_bar_menu', 'phonestore_admin_bar_account_menu', 100);

// Customize WooCommerce account menu
function phonestore_custom_my_account_menu_items($items) {
   // Reorder menu items
   $new_items = array();
   $new_items['dashboard'] = 'Tổng quan';
   $new_items['orders'] = 'Đơn hàng';
   $new_items['downloads'] = 'Tải xuống';
   $new_items['edit-address'] = 'Địa chỉ';
   $new_items['edit-account'] = 'Chi tiết tài khoản';
   $new_items['customer-logout'] = 'Đăng xuất';
   
   return $new_items;
}
add_filter('woocommerce_account_menu_items', 'phonestore_custom_my_account_menu_items');

// Add custom CSS for WooCommerce pages
function phonestore_woocommerce_custom_styles() {
   if (is_account_page()) {
       ?>
       <style>
       /* Hide default WooCommerce navigation on dashboard */
       .woocommerce-account .woocommerce-MyAccount-navigation {
           background: white;
           border-radius: 10px;
           box-shadow: 0 2px 4px rgba(0,0,0,0.1);
           margin-bottom: 20px;
       }
       
       .woocommerce-account .woocommerce-MyAccount-navigation ul {
           list-style: none;
           padding: 0;
           margin: 0;
       }
       
       .woocommerce-account .woocommerce-MyAccount-navigation ul li {
           border-bottom: 1px solid #f1f5f9;
       }
       
       .woocommerce-account .woocommerce-MyAccount-navigation ul li:last-child {
           border-bottom: none;
       }
       
       .woocommerce-account .woocommerce-MyAccount-navigation ul li a {
           display: block;
           padding: 15px 20px;
           color: #4a5568;
           text-decoration: none;
           font-weight: 600;
           transition: all 0.3s;
       }
       
       .woocommerce-account .woocommerce-MyAccount-navigation ul li.is-active a,
       .woocommerce-account .woocommerce-MyAccount-navigation ul li a:hover {
           background: #e6fffa;
           color: #234e52;
           transform: translateX(5px);
       }
       
       .woocommerce-account .woocommerce-MyAccount-content {
           background: white;
           border-radius: 10px;
           box-shadow: 0 2px 4px rgba(0,0,0,0.1);
           padding: 30px;
       }
       
       /* Mobile responsive */
       @media (max-width: 768px) {
           .woocommerce-account .woocommerce-MyAccount-navigation ul {
               display: flex;
               overflow-x: auto;
               white-space: nowrap;
           }
           
           .woocommerce-account .woocommerce-MyAccount-navigation ul li {
               border-bottom: none;
               border-right: 1px solid #f1f5f9;
               flex-shrink: 0;
           }
           
           .woocommerce-account .woocommerce-MyAccount-navigation ul li:last-child {
               border-right: none;
           }
           
           .woocommerce-account .woocommerce-MyAccount-navigation ul li a {
               padding: 15px;
               font-size: 14px;
           }
       }
       </style>
       <?php
   }
}
add_action('wp_head', 'phonestore_woocommerce_custom_styles');

// Custom My Account Page Title
function phonestore_account_page_title() {
    if (is_account_page() && is_user_logged_in()) {
        $current_user = wp_get_current_user();
        echo '<div class="account-page-header">';
        echo '<h1>👤 Tài Khoản Của Tôi</h1>';
        echo '<p>Xin chào <strong>' . $current_user->display_name . '</strong>, quản lý thông tin tài khoản và đơn hàng của bạn</p>';
        echo '</div>';
    }
}
add_action('woocommerce_account_dashboard', 'phonestore_account_page_title', 5);

// Override WooCommerce account navigation
function phonestore_account_navigation_styles() {
    if (is_account_page()) {
        ?>
        <style>
        .woocommerce-account .woocommerce-MyAccount-navigation {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            overflow: hidden;
        }
        
        .woocommerce-account .woocommerce-MyAccount-navigation ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-wrap: wrap;
        }
        
        .woocommerce-account .woocommerce-MyAccount-navigation ul li {
            flex: 1;
            min-width: 150px;
            border-right: 1px solid #f1f5f9;
        }
        
        .woocommerce-account .woocommerce-MyAccount-navigation ul li:last-child {
            border-right: none;
        }
        
        .woocommerce-account .woocommerce-MyAccount-navigation ul li a {
            display: block;
            padding: 20px 15px;
            color: #4a5568;
            text-decoration: none;
            font-weight: 600;
           transition: all 0.3s;
           text-align: center;
           font-size: 14px;
       }
       
       .woocommerce-account .woocommerce-MyAccount-navigation ul li.is-active a,
       .woocommerce-account .woocommerce-MyAccount-navigation ul li a:hover {
           background: linear-gradient(135deg, #38a169 0%, #2f855a 100%);
           color: white;
           transform: translateY(-2px);
       }
       
       .woocommerce-account .woocommerce-MyAccount-content {
           background: white;
           border-radius: 15px;
           box-shadow: 0 4px 6px rgba(0,0,0,0.1);
           padding: 0;
           overflow: hidden;
       }
       
       /* Account Page Header */
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
           margin: 0;
       }
       
       /* Mobile responsive */
       @media (max-width: 768px) {
           .woocommerce-account .woocommerce-MyAccount-navigation ul {
               flex-direction: column;
           }
           
           .woocommerce-account .woocommerce-MyAccount-navigation ul li {
               border-right: none;
               border-bottom: 1px solid #f1f5f9;
           }
           
           .woocommerce-account .woocommerce-MyAccount-navigation ul li:last-child {
               border-bottom: none;
           }
           
           .account-page-header h1 {
               font-size: 2rem;
           }
           
           .account-page-header {
               padding: 30px 20px;
           }
       }
       </style>
       <?php
   }
}
add_action('wp_head', 'phonestore_account_navigation_styles');

// Remove default dashboard content
remove_action('woocommerce_account_dashboard', 'woocommerce_account_dashboard', 10);

// Customize account menu items with icons
function phonestore_account_menu_items($items) {
   $new_items = array();
   $new_items['dashboard'] = '🏠 Tổng quan';
   $new_items['orders'] = '📦 Đơn hàng';
   $new_items['downloads'] = '⬇️ Tải xuống';
   $new_items['edit-address'] = '🏠 Địa chỉ';
   $new_items['edit-account'] = '👤 Tài khoản';
   $new_items['customer-logout'] = '🚪 Đăng xuất';
   
   return $new_items;
}
add_filter('woocommerce_account_menu_items', 'phonestore_account_menu_items');

// Force WooCommerce account page to work properly
function phonestore_fix_account_page() {
    // Remove default WooCommerce dashboard content
    remove_action( 'woocommerce_account_dashboard', 'woocommerce_account_dashboard', 10 );
    
    // Add our custom dashboard content
    add_action( 'woocommerce_account_dashboard', 'phonestore_custom_account_dashboard', 10 );
}
add_action( 'init', 'phonestore_fix_account_page' );

// Custom account dashboard content
function phonestore_custom_account_dashboard() {
    // Include our custom dashboard template
    $template_path = get_template_directory() . '/woocommerce/myaccount/dashboard.php';
    if ( file_exists( $template_path ) ) {
        include $template_path;
    }
}

// Ensure WooCommerce account endpoints work
function phonestore_setup_account_endpoints() {
    // Make sure account page exists
    if ( ! get_option( 'woocommerce_myaccount_page_id' ) ) {
        $account_page = get_page_by_path( 'my-account' );
        if ( ! $account_page ) {
            // Create account page if it doesn't exist
            $account_page_id = wp_insert_post( array(
                'post_title'   => 'My Account',
                'post_name'    => 'my-account',
                'post_content' => '[woocommerce_my_account]',
                'post_status'  => 'publish',
                'post_type'    => 'page'
            ) );
            
            if ( $account_page_id ) {
                update_option( 'woocommerce_myaccount_page_id', $account_page_id );
            }
        } else {
            update_option( 'woocommerce_myaccount_page_id', $account_page->ID );
        }
    }
}
add_action( 'after_switch_theme', 'phonestore_setup_account_endpoints' );

// Fix account page template
function phonestore_account_page_template( $template ) {
    if ( is_account_page() ) {
        $account_template = get_template_directory() . '/woocommerce/myaccount/my-account.php';
        if ( file_exists( $account_template ) ) {
            return $account_template;
        }
    }
    return $template;
}
add_filter( 'template_include', 'phonestore_account_page_template', 99 );

// Customize WooCommerce account menu items with proper icons
function phonestore_account_menu_items_fixed( $items ) {
    $new_items = array();
    $new_items['dashboard'] = '🏠 Tổng quan';
    $new_items['orders'] = '📦 Đơn hàng';
    
    // Only show downloads if there are downloadable products
    if ( wc_get_customer_download_permissions( get_current_user_id() ) ) {
        $new_items['downloads'] = '⬇️ Tải xuống';
    }
    
    $new_items['edit-address'] = '🏠 Địa chỉ';
    $new_items['edit-account'] = '👤 Tài khoản';
    $new_items['customer-logout'] = '🚪 Đăng xuất';
    
    return $new_items;
}
add_filter( 'woocommerce_account_menu_items', 'phonestore_account_menu_items_fixed', 99 );

// Ensure account page redirects work properly
function phonestore_fix_account_redirects() {
    if ( is_admin() || ! is_account_page() ) {
        return;
    }
    
    // If user is not logged in and trying to access account endpoints, redirect to login
    if ( ! is_user_logged_in() && ! is_wc_endpoint_url( 'lost-password' ) ) {
        $current_url = wc_get_account_endpoint_url( 'dashboard' );
        if ( ! is_wc_endpoint_url( 'lost-password' ) && ! is_wc_endpoint_url( 'register' ) ) {
            wp_redirect( wc_get_account_endpoint_url( 'dashboard' ) );
            exit;
        }
    }
}
add_action( 'template_redirect', 'phonestore_fix_account_redirects' );

// Add account styles
function phonestore_account_styles() {
    if ( is_account_page() ) {
        wp_add_inline_style( 'phonestore-style', '
        .woocommerce-account {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .woocommerce-MyAccount-navigation {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            overflow: hidden;
        }
        
        .woocommerce-MyAccount-navigation ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-wrap: wrap;
        }
        
        .woocommerce-MyAccount-navigation ul li {
            flex: 1;
            min-width: 150px;
            border-right: 1px solid #f1f5f9;
        }
        
        .woocommerce-MyAccount-navigation ul li:last-child {
            border-right: none;
        }
        
        .woocommerce-MyAccount-navigation ul li a {
            display: block;
            padding: 20px 15px;
            color: #4a5568;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            text-align: center;
            font-size: 14px;
        }
        
        .woocommerce-MyAccount-navigation ul li.is-active a,
        .woocommerce-MyAccount-navigation ul li a:hover {
            background: linear-gradient(135deg, #38a169 0%, #2f855a 100%);
            color: white;
            transform: translateY(-2px);
        }
        
        .woocommerce-MyAccount-content {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        @media (max-width: 768px) {
            .woocommerce-MyAccount-navigation ul {
                flex-direction: column;
            }
            
            .woocommerce-MyAccount-navigation ul li {
                border-right: none;
                border-bottom: 1px solid #f1f5f9;
            }
            
            .woocommerce-MyAccount-navigation ul li:last-child {
                border-bottom: none;
            }
        }
        ');
    }
}
add_action( 'wp_enqueue_scripts', 'phonestore_account_styles' );

// Remove old account functions if they exist
remove_action( 'init', 'phonestore_fix_account_page' );
remove_filter( 'template_include', 'phonestore_account_page_template', 99 );

// New improved account page handling
function phonestore_setup_account_page() {
    // Make sure WooCommerce account page exists
    if ( class_exists( 'WooCommerce' ) ) {
        $account_page_id = get_option( 'woocommerce_myaccount_page_id' );
        
        if ( ! $account_page_id || ! get_post( $account_page_id ) ) {
            // Try to find existing my-account page
            $account_page = get_page_by_path( 'my-account' );
            
            if ( ! $account_page ) {
                // Create new account page
                $account_page_id = wp_insert_post( array(
                    'post_title'   => 'My Account',
                    'post_name'    => 'my-account',
                    'post_content' => '[woocommerce_my_account]',
                    'post_status'  => 'publish',
                    'post_type'    => 'page'
                ) );
            } else {
                $account_page_id = $account_page->ID;
            }
            
            if ( $account_page_id ) {
                update_option( 'woocommerce_myaccount_page_id', $account_page_id );
            }
        }
    }
}
add_action( 'after_switch_theme', 'phonestore_setup_account_page' );

// Force correct template for account page
function phonestore_account_template_include( $template ) {
    if ( is_account_page() ) {
        // Check if we have custom my-account template
        $custom_template = get_template_directory() . '/woocommerce/myaccount/my-account.php';
        if ( file_exists( $custom_template ) ) {
            return $custom_template;
        }
    }
    return $template;
}
add_filter( 'template_include', 'phonestore_account_template_include', 99 );

// Ensure WooCommerce templates work with our theme
function phonestore_woocommerce_template_path( $template, $template_name, $template_path ) {
    if ( $template_name === 'myaccount/my-account.php' ) {
        $custom_template = get_template_directory() . '/woocommerce/myaccount/my-account.php';
        if ( file_exists( $custom_template ) ) {
            return $custom_template;
        }
    }
    return $template;
}
add_filter( 'woocommerce_locate_template', 'phonestore_woocommerce_template_path', 10, 3 );

// Fix account page body class
function phonestore_account_body_class( $classes ) {
    if ( is_account_page() ) {
        $classes[] = 'woocommerce-account';
        $classes[] = 'phonestore-account-page';
    }
    return $classes;
}
add_filter( 'body_class', 'phonestore_account_body_class' );

// Customize account menu items
function phonestore_account_menu_items_with_icons( $items ) {
    $new_items = array();
    $new_items['dashboard'] = '🏠 Tổng quan';
    $new_items['orders'] = '📦 Đơn hàng';
    
    // Only add downloads if there are any
    if ( wc_get_customer_download_permissions( get_current_user_id() ) ) {
        $new_items['downloads'] = '⬇️ Tải xuống';
    }
    
    $new_items['edit-address'] = '🏠 Địa chỉ';
    $new_items['edit-account'] = '👤 Tài khoản';
    $new_items['customer-logout'] = '🚪 Đăng xuất';
    
    return $new_items;
}
add_filter( 'woocommerce_account_menu_items', 'phonestore_account_menu_items_with_icons', 20 );

// Add account page specific styles
function phonestore_account_page_styles() {
    if ( is_account_page() ) {
        wp_add_inline_style( 'phonestore-style', '
        body.phonestore-account-page {
            background: #f8fafc;
        }
        
        .phonestore-account-page .site-header {
            position: relative;
        }
        
        .woocommerce-account {
            margin: 0;
            padding: 0;
        }
        
        .woocommerce-notices-wrapper {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .woocommerce-message,
        .woocommerce-error,
        .woocommerce-info {
            background: white;
            border-radius: 8px;
            padding: 15px 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .woocommerce-message {
            border-left: 4px solid #38a169;
            color: #2f855a;
        }
        
        .woocommerce-error {
            border-left: 4px solid #e53e3e;
            color: #c53030;
        }
        
        .woocommerce-info {
            border-left: 4px solid #4299e1;
            color: #3182ce;
        }
        ' );
    }
}
add_action( 'wp_enqueue_scripts', 'phonestore_account_page_styles' );

// Ensure account endpoints work properly
function phonestore_flush_rewrite_rules_on_activation() {
    // Flush rewrite rules to ensure account endpoints work
    flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'phonestore_flush_rewrite_rules_on_activation' );

// Fix account page redirects
function phonestore_account_redirect_fix() {
    if ( is_account_page() && ! is_user_logged_in() ) {
        // Allow access to login/register pages
        global $wp;
        $current_endpoint = isset( $wp->query_vars ) ? key( $wp->query_vars ) : '';
        
        if ( ! in_array( $current_endpoint, array( 'lost-password', 'register' ) ) && ! empty( $current_endpoint ) ) {
            wp_redirect( wc_get_page_permalink( 'myaccount' ) );
            exit;
        }
    }
}
add_action( 'template_redirect', 'phonestore_account_redirect_fix' );

// Force WooCommerce to use custom single product template
function phonestore_single_product_template($template) {
    global $post;
    
    if (is_product() && $post->post_type == 'product') {
        $custom_template = get_template_directory() . '/woocommerce/single-product.php';
        if (file_exists($custom_template)) {
            return $custom_template;
        }
    }
    return $template;
}
add_filter('template_include', 'phonestore_single_product_template', 99);

// Đảm bảo WooCommerce hoạt động đúng
function phonestore_fix_woocommerce_pages() {
    // Flush rewrite rules khi cần
    if (get_option('phonestore_flush_rules') !== 'done') {
        flush_rewrite_rules();
        update_option('phonestore_flush_rules', 'done');
    }
}
add_action('after_switch_theme', 'phonestore_fix_woocommerce_pages');

// Đảm bảo shop page hoạt động
function phonestore_fix_shop_page($template) {
    if (is_shop() && file_exists(get_template_directory() . '/woocommerce.php')) {
        return get_template_directory() . '/woocommerce.php';
    }
    return $template;
}
add_filter('template_include', 'phonestore_fix_shop_page', 999);

// Sửa lỗi permalink và rewrite rules
function phonestore_fix_permalink_issues() {
    // Force flush rewrite rules khi cần
    if (!get_option('phonestore_permalinks_fixed')) {
        flush_rewrite_rules(false);
        update_option('phonestore_permalinks_fixed', true);
    }
}
add_action('init', 'phonestore_fix_permalink_issues');

// Đảm bảo WooCommerce product links hoạt động đúng
function phonestore_fix_woocommerce_permalinks() {
    global $wp_rewrite;
    
    // Đảm bảo WooCommerce rewrite rules được load
    if (class_exists('WooCommerce')) {
        WC()->query->init_query_vars();
        WC()->query->add_endpoints();
    }
}
add_action('init', 'phonestore_fix_woocommerce_permalinks', 999);

// Xóa index.php khỏi URL
function phonestore_remove_index_php($permalink) {
    $permalink = str_replace('/index.php/', '/', $permalink);
    $permalink = str_replace('index.php/', '', $permalink);
    return $permalink;
}
add_filter('user_trailingslashit', 'phonestore_remove_index_php');
add_filter('home_url', 'phonestore_remove_index_php');
add_filter('site_url', 'phonestore_remove_index_php');

// Force WooCommerce sử dụng clean URLs
function phonestore_clean_product_urls($permalink, $post) {
    // Kiểm tra an toàn trước khi truy cập thuộc tính
    if (is_object($post) && isset($post->post_type) && $post->post_type == 'product') {
        $permalink = str_replace('index.php/', '', $permalink);
        $permalink = str_replace('//', '/', $permalink);
    }
    return $permalink;
}
add_filter('post_link', 'phonestore_clean_product_urls', 10, 2);
add_filter('page_link', 'phonestore_clean_product_urls', 10, 2);

// Đảm bảo single product template được load đúng
function phonestore_force_single_product_template($template) {
    global $post;
    
    // Kiểm tra an toàn
    if (is_single() && is_object($post) && isset($post->post_type) && $post->post_type == 'product') {
        $single_template = locate_template('woocommerce/single-product.php');
        if ($single_template) {
            return $single_template;
        }
    }
    
    return $template;
}
add_filter('single_template', 'phonestore_force_single_product_template', 999);

// Debug permalinks
add_action('wp_footer', function() {
    if (current_user_can('administrator') && isset($_GET['debug_urls'])) {
        echo '<div style="position:fixed;bottom:0;left:0;background:white;padding:10px;border:2px solid red;z-index:99999;">';
        echo '<strong>Debug URLs:</strong><br>';
        echo 'Home: ' . home_url() . '<br>';
        echo 'Shop: ' . (class_exists('WooCommerce') ? wc_get_page_permalink('shop') : 'No WC') . '<br>';
        
        if (is_product()) {
            global $product;
            echo 'Product URL: ' . get_permalink($product->get_id()) . '<br>';
            echo 'Product ID: ' . $product->get_id() . '<br>';
        }
        echo '</div>';
    }
});

// Reset permalinks hoàn toàn - chỉ chạy 1 lần
add_action('init', function() {
    if (isset($_GET['reset_permalinks']) && current_user_can('administrator')) {
        delete_option('rewrite_rules');
        global $wp_rewrite;
        $wp_rewrite->set_permalink_structure('/%postname%/');
        $wp_rewrite->flush_rules(true);
        
        // Reset WooCommerce
        if (class_exists('WooCommerce')) {
            delete_option('woocommerce_permalinks');
            WC_Install::create_pages();
        }
        
        echo '<h1>Permalinks đã được reset! <a href="' . home_url() . '">Quay lại trang chủ</a></h1>';
        exit;
    }
});

// Add compare button to product loop
function phonestore_add_compare_button() {
    global $product;
    
    if (!$product) return;
    
    $product_id = $product->get_id();
    
    echo '<button class="compare-btn" data-product-id="' . $product_id . '" title="Thêm vào so sánh">⚖️</button>';
}

// Hook compare button after add to cart button
add_action('woocommerce_after_shop_loop_item', 'phonestore_add_compare_button', 15);

// Wrap shop loop item buttons in container
function phonestore_wrap_shop_buttons_start() {
    echo '<div class="product-actions">';
}
add_action('woocommerce_after_shop_loop_item', 'phonestore_wrap_shop_buttons_start', 5);

function phonestore_wrap_shop_buttons_end() {
    echo '</div>';
}
add_action('woocommerce_after_shop_loop_item', 'phonestore_wrap_shop_buttons_end', 20);

// Helper function để tạo product attributes nhanh
function phonestore_create_product_attributes() {
    // Tạo các taxonomy attributes cho WooCommerce
    $attributes = array(
        'pa_brand' => 'Thương hiệu',
        'pa_screen_size' => 'Kích thước màn hình',
        'pa_processor' => 'Vi xử lý',
        'pa_ram' => 'RAM',
        'pa_storage' => 'Bộ nhớ trong',
        'pa_camera' => 'Camera',
        'pa_battery' => 'Pin',
        'pa_os' => 'Hệ điều hành'
    );
    
    foreach ($attributes as $slug => $label) {
        if (!taxonomy_exists($slug)) {
            wc_create_attribute(array(
                'name' => $label,
                'slug' => $slug,
                'type' => 'select',
                'order_by' => 'menu_order',
                'has_archives' => false,
            ));
        }
    }
}
add_action('init', 'phonestore_create_product_attributes');

// Debug function để kiểm tra attributes của product
function phonestore_debug_product_attributes($product_id = null) {
    if (!$product_id) {
        global $post;
        $product_id = $post->ID;
    }
    
    $product = wc_get_product($product_id);
    if (!$product) return;
    
    echo '<pre style="background:#f0f0f0;padding:10px;margin:10px 0;border-left:5px solid #0073aa;">';
    echo '<strong>Product Attributes Debug for Product ID: ' . $product_id . '</strong><br>';
    
    // WooCommerce attributes
    $attributes = $product->get_attributes();
    echo '<strong>WooCommerce Attributes:</strong><br>';
    if (empty($attributes)) {
        echo '- No WooCommerce attributes found<br>';
    } else {
        foreach ($attributes as $attribute) {
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
            
            echo '- ' . $attribute_label . ': ' . $attribute_value . '<br>';
        }
    }
    
    // Custom meta fields
    echo '<br><strong>Custom Meta Fields:</strong><br>';
    $meta_keys = array('brand', 'display_size', 'cpu', 'ram', 'storage', 'rear_camera', 'battery', 'os');
    foreach ($meta_keys as $key) {
        $value = get_post_meta($product_id, $key, true);
        echo '- ' . $key . ': ' . ($value ? $value : 'Not set') . '<br>';
    }
    
    echo '</pre>';
}

// Hiển thị debug info cho admin
function phonestore_show_debug_info() {
    if (is_product() && current_user_can('administrator') && isset($_GET['debug'])) {
        global $post;
        phonestore_debug_product_attributes($post->ID);
    }
}
add_action('wp_footer', 'phonestore_show_debug_info');

// Đảm bảo tất cả product attributes được hiển thị
function phonestore_get_all_product_attributes($product_id) {
    $attributes = array();
    
    // Lấy WooCommerce attributes
    $product = wc_get_product($product_id);
    if ($product) {
        $wc_attributes = $product->get_attributes();
        foreach ($wc_attributes as $attribute) {
            $name = $attribute->get_name();
            $name = str_replace('pa_', '', $name);
            
            if ($attribute->is_taxonomy()) {
                $values = wc_get_product_terms($product_id, $attribute->get_name(), array('fields' => 'names'));
                $value = implode(', ', $values);
            } else {
                $value = implode(', ', $attribute->get_options());
            }
            
            if (!empty($value)) {
                $attributes[$name] = $value;
            }
        }
    }
    
    // Lấy custom fields
    $meta_keys = get_post_meta($product_id);
    foreach ($meta_keys as $key => $values) {
        if (strpos($key, '_') !== 0 && !empty($values[0])) {
            $attributes[$key] = $values[0];
        }
    }
    
    return $attributes;
}

// Fix footer cho single product page
function phonestore_single_product_footer_fix() {
    if (is_product()) {
        ?>
        <style>
        body.single-product .site-footer {
            width: 100vw !important;
            position: relative !important;
            left: 50% !important;
            right: 50% !important;
            margin-left: -50vw !important;
            margin-right: -50vw !important;
            margin-top: 50px !important;
            margin-bottom: 0 !important;
        }
        
        body.single-product .footer-container {
            max-width: none !important;
            margin: 0 !important;
        }
        </style>
        <?php
    }
}
add_action('wp_head', 'phonestore_single_product_footer_fix');

// Đăng ký shipping methods
function phonestore_register_shipping_methods() {
    // Include class shipping hiện có
    require_once get_template_directory() . '/includes/class-distance-shipping.php';
}

function phonestore_add_shipping_methods($methods) {
    $methods['distance_based_shipping'] = 'Distance_Based_Shipping';
    return $methods;
}
add_action('woocommerce_shipping_init', 'phonestore_register_shipping_methods');
add_filter('woocommerce_shipping_methods', 'phonestore_add_shipping_methods');

add_action('woocommerce_shipping_init', 'phonestore_register_vietnam_shipping');
add_filter('woocommerce_shipping_methods', 'phonestore_add_vietnam_shipping');


// Include Vietnam provinces data
require_once get_template_directory() . '/includes/vietnam-provinces.php';

// Customize checkout fields với dropdown Tỉnh-Huyện
function phonestore_customize_checkout_fields_dropdown($fields) {
    // Get Vietnam provinces data
    $provinces_data = get_vietnam_provinces_districts();
    
    // Tạo options cho tỉnh/thành phố
    $province_options = array('' => 'Chọn Tỉnh/Thành phố');
    foreach ($provinces_data as $province => $districts) {
        $province_options[$province] = $province;
    }
    
    // Billing fields labels
    $fields['billing']['billing_first_name']['label'] = 'Họ *';
    $fields['billing']['billing_last_name']['label'] = 'Tên *';
    $fields['billing']['billing_email']['label'] = 'Email *';
    $fields['billing']['billing_phone']['label'] = 'Số điện thoại *';
    
    // Thay đổi billing_state thành dropdown tỉnh/thành phố
    $fields['billing']['billing_state'] = array(
        'type' => 'select',
        'label' => 'Tỉnh/Thành phố *',
        'required' => true,
        'class' => array('form-row-wide', 'address-field', 'update_totals_on_change'),
        'options' => $province_options,
        'priority' => 60
    );
    
    // Thay đổi billing_city thành dropdown huyện/quận
    $fields['billing']['billing_city'] = array(
        'type' => 'select',
        'label' => 'Quận/Huyện *',
        'required' => true,
        'class' => array('form-row-wide', 'address-field', 'update_totals_on_change'),
        'options' => array('' => 'Chọn Quận/Huyện'),
        'priority' => 70
    );
    
    // Địa chỉ chi tiết với placeholder thông minh
    $fields['billing']['billing_address_1'] = array(
        'label' => 'Địa chỉ chi tiết *',
        'placeholder' => 'Số nhà, tên đường, phường/xã...',
        'required' => true,
        'class' => array('form-row-wide', 'address-field'),
        'priority' => 80
    );
    
    // Hide các trường không cần thiết
    unset($fields['billing']['billing_postcode']);
    unset($fields['billing']['billing_country']);
    unset($fields['billing']['billing_company']);
    unset($fields['billing']['billing_address_2']);
    
    // Order notes
    if (isset($fields['order']['order_comments'])) {
        $fields['order']['order_comments']['label'] = 'Ghi chú đơn hàng (tùy chọn)';
        $fields['order']['order_comments']['placeholder'] = 'Ghi chú về đơn hàng, ví dụ: ghi chú đặc biệt cho việc giao hàng...';
    }
    
    return $fields;
}
add_filter('woocommerce_checkout_fields', 'phonestore_customize_checkout_fields_dropdown', 20);

// Smart Address Autocomplete với OpenRouteService API
function phonestore_smart_address_autocomplete() {
    if (is_checkout()) {
        ?>
        <script type="text/javascript">
        jQuery(document).ready(function($) {
            var provinces_data = <?php echo json_encode(get_vietnam_provinces_districts()); ?>;
            
            // Update districts dropdown
            function updateDistricts() {
                var selectedProvince = $('#billing_state').val();
                var $districtSelect = $('#billing_city');
                
                $districtSelect.empty().append('<option value="">Chọn Quận/Huyện</option>');
                
                if (selectedProvince && provinces_data[selectedProvince]) {
                    var districts = provinces_data[selectedProvince];
                    $.each(districts, function(index, district) {
                        $districtSelect.append('<option value="' + district + '">' + district + '</option>');
                    });
                }
                
                $('#billing_address_1').val('');
                $('#address-suggestions').hide();
            }
            
            // Setup address autocomplete
            function setupAddressAutocomplete() {
                let searchTimeout;
                
                $(document).on('input', '#billing_address_1', function() {
                    const query = $(this).val();
                    const $suggestions = $('#address-suggestions');
                    const province = $('#billing_state').val();
                    const district = $('#billing_city').val();
                    
                    if (query.length < 2) {
                        $suggestions.hide();
                        return;
                    }
                    
                    if (!province || !district) {
                        $suggestions.html('<div class="suggestion-notice">⚠️ Vui lòng chọn Tỉnh/Thành phố và Quận/Huyện trước</div>').show();
                        return;
                    }
                    
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(function() {
                        const contextualQuery = query + ', ' + district + ', ' + province + ', Việt Nam';
                        
                        $suggestions.html('<div class="suggestion-loading">🔍 Đang tìm kiếm...</div>').show();
                        
                        $.ajax({
                            url: 'https://api.openrouteservice.org/geocode/search',
                            method: 'GET',
                            data: {
                                api_key: '<?php echo defined('OPENROUTE_API_KEY') ? OPENROUTE_API_KEY : ''; ?>',
                                text: contextualQuery,
                                size: 6,
                                layers: 'address,venue,street',
                                'boundary.country': 'VN'
                            },
                            success: function(data) {
                                let html = '<ul class="address-suggestions">';
                                
                                if (data.features && data.features.length > 0) {
                                    data.features.forEach(function(feature) {
                                        const label = feature.properties.label || feature.properties.name;
                                        if (label && label.toLowerCase().includes(query.toLowerCase())) {
                                            const shortAddress = extractShortAddress(label, province, district);
                                            html += '<li class="suggestion-item" data-address="' + shortAddress + '">';
                                            html += '<div class="suggestion-main">' + shortAddress + '</div>';
                                            html += '<div class="suggestion-detail">' + label + '</div>';
                                            html += '</li>';
                                        }
                                    });
                                }
                                
                                // Option nhập thủ công
                                html += '<li class="suggestion-item suggestion-manual" data-address="' + query + '">';
                                html += '<div class="suggestion-main">✏️ Nhập thủ công: "' + query + '"</div>';
                                html += '</li>';
                                
                                html += '</ul>';
                                $suggestions.html(html).show();
                            },
                            error: function() {
                                $suggestions.html(
                                    '<div class="suggestion-error">' +
                                    '<div class="suggestion-item suggestion-manual" data-address="' + query + '">✏️ Nhập: "' + query + '"</div>' +
                                    '</div>'
                                ).show();
                            }
                        });
                    }, 400);
                });
            }
            
            // Extract short address
            function extractShortAddress(fullAddress, province, district) {
                let short = fullAddress;
                if (district && short.includes(district)) {
                    short = short.split(district)[0].trim();
                }
                if (province && short.includes(province)) {
                    short = short.split(province)[0].trim();
                }
                return short.replace(/,\s*$/, '').trim() || fullAddress;
            }
            
            // Event handlers
            $(document).on('change', '#billing_state', function() {
                updateDistricts();
                $('body').trigger('update_checkout');
            });
            
            $(document).on('change', '#billing_city', function() {
                const province = $('#billing_state').val();
                const district = $(this).val();
                if (province && district) {
                    $('#billing_address_1').attr('placeholder', 'Nhập địa chỉ tại ' + district + ', ' + province);
                }
                $('#billing_address_1').val('');
                $('body').trigger('update_checkout');
            });
            
            $(document).on('click', '.suggestion-item', function() {
                const address = $(this).data('address');
                $('#billing_address_1').val(address);
                $('#address-suggestions').hide();
                $('body').trigger('update_checkout');
            });
            
            $(document).on('click', function(e) {
                if (!$(e.target).closest('#billing_address_1, #address-suggestions').length) {
                    $('#address-suggestions').hide();
                }
            });
            
            // Initialize
            setupAddressAutocomplete();
            if (!$('#address-suggestions').length) {
                $('#billing_address_1').after('<div id="address-suggestions"></div>');
            }
        });
        </script>
        
        <style>
        #address-suggestions {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 1000;
            max-height: 300px;
            overflow-y: auto;
            margin-top: 2px;
        }
        
        .address-suggestions {
            list-style: none;
            margin: 0;
            padding: 0;
        }
        
        .suggestion-item {
            padding: 12px 15px;
            cursor: pointer;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .suggestion-item:hover {
            background: #f8f9fa;
        }
        
        .suggestion-main {
            font-weight: 600;
            color: #333;
        }
        
        .suggestion-detail {
            font-size: 12px;
            color: #666;
            margin-top: 2px;
        }
        
        .suggestion-manual {
            background: #f0f8ff;
            border-left: 3px solid #007cba;
        }
        
        .suggestion-loading,
        .suggestion-notice,
        .suggestion-error {
            padding: 12px 15px;
            text-align: center;
            color: #666;
        }
        
        .woocommerce-checkout .form-row {
            position: relative;
        }
        </style>
        <?php
    }
}
add_action('wp_footer', 'phonestore_smart_address_autocomplete');

// Đăng ký Vietnam shipping method
function phonestore_register_vietnam_shipping() {
    require_once get_template_directory() . '/includes/class-vietnam-shipping.php';
}

function phonestore_add_vietnam_shipping($methods) {
    $methods['vietnam_shipping_calculator'] = 'Vietnam_Shipping_Calculator';
    return $methods;
}

add_action('woocommerce_shipping_init', 'phonestore_register_vietnam_shipping');
add_filter('woocommerce_shipping_methods', 'phonestore_add_vietnam_shipping');

// Add shipping fee to cart
// Add shipping fee to cart
function phonestore_add_shipping_fee() {
    if (is_admin() && !defined('DOING_AJAX')) return;
    
    // Only add fee in cart and checkout pages
    if (!is_cart() && !is_checkout()) return;
    
    $shipping_fee = 25000; // 25,000 VNĐ phí ship cố định
    
    // Check if fee already exists
    $fees = WC()->cart->get_fees();
    foreach ($fees as $fee) {
        if ($fee->name === 'Phí vận chuyển') {
            return; // Fee already added
        }
    }
    
    // Add shipping fee
    WC()->cart->add_fee('Phí vận chuyển', $shipping_fee);
}
add_action('woocommerce_cart_calculate_fees', 'phonestore_add_shipping_fee');

// Display shipping fee with icon
function phonestore_cart_fee_html($cart_fee_html, $fee) {
    if ($fee->name === 'Phí vận chuyển') {
        return '🚚 ' . $cart_fee_html;
    }
    return $cart_fee_html;
}
add_filter('woocommerce_cart_totals_fee_html', 'phonestore_cart_fee_html', 10, 2);

// Create checkout page if it doesn't exist
function phonestore_create_checkout_page() {
    // Check if checkout page exists
    $checkout_page_id = wc_get_page_id('checkout');
    
    if ($checkout_page_id == -1 || !get_post($checkout_page_id)) {
        // Create checkout page
        $checkout_page = wp_insert_post(array(
            'post_title' => 'Thanh Toán',
            'post_name' => 'thanh-toan',
            'post_content' => '[woocommerce_checkout]',
            'post_status' => 'publish',
            'post_type' => 'page',
            'page_template' => 'page-checkout.php'
        ));
        
        if ($checkout_page && !is_wp_error($checkout_page)) {
            // Update WooCommerce settings
            update_option('woocommerce_checkout_page_id', $checkout_page);
        }
    }
}
add_action('after_switch_theme', 'phonestore_create_checkout_page');

// Customize checkout fields
function phonestore_customize_checkout_fields($fields) {
    // Billing fields
    $fields['billing']['billing_first_name']['label'] = 'Họ *';
    $fields['billing']['billing_last_name']['label'] = 'Tên *';
    $fields['billing']['billing_email']['label'] = 'Email *';
    $fields['billing']['billing_phone']['label'] = 'Số điện thoại *';
    $fields['billing']['billing_address_1']['label'] = 'Địa chỉ *';
    $fields['billing']['billing_city']['label'] = 'Thành phố *';
    $fields['billing']['billing_postcode']['label'] = 'Mã bưu điện';
    $fields['billing']['billing_country']['label'] = 'Quốc gia *';
    $fields['billing']['billing_state']['label'] = 'Tỉnh/Thành phố *';
    $fields['billing']['billing_company']['label'] = 'Tên công ty (tùy chọn)';
    
    // Shipping fields
    if (isset($fields['shipping'])) {
        $fields['shipping']['shipping_first_name']['label'] = 'Họ *';
        $fields['shipping']['shipping_last_name']['label'] = 'Tên *';
        $fields['shipping']['shipping_address_1']['label'] = 'Địa chỉ giao hàng *';
        $fields['shipping']['shipping_city']['label'] = 'Thành phố *';
        $fields['shipping']['shipping_postcode']['label'] = 'Mã bưu điện';
        $fields['shipping']['shipping_country']['label'] = 'Quốc gia *';
        $fields['shipping']['shipping_state']['label'] = 'Tỉnh/Thành phố *';
        $fields['shipping']['shipping_company']['label'] = 'Tên công ty (tùy chọn)';
    }
    
    // Order notes
    if (isset($fields['order']['order_comments'])) {
        $fields['order']['order_comments']['label'] = 'Ghi chú đơn hàng (tùy chọn)';
        $fields['order']['order_comments']['placeholder'] = 'Ghi chú về đơn hàng, ví dụ: ghi chú đặc biệt cho việc giao hàng.';
    }
    
    return $fields;
}
add_filter('woocommerce_checkout_fields', 'phonestore_customize_checkout_fields');

// Add custom checkout validation
function phonestore_checkout_field_validation() {
    if (empty($_POST['billing_phone'])) {
        wc_add_notice('Số điện thoại là bắt buộc.', 'error');
    } elseif (!preg_match('/^[0-9\-\+\s\(\)]+$/', $_POST['billing_phone'])) {
        wc_add_notice('Số điện thoại không hợp lệ.', 'error');
    }
}
add_action('woocommerce_checkout_process', 'phonestore_checkout_field_validation', 10, 0);

// Add checkout success message customization
function phonestore_checkout_order_processed($order_id) {
    // Custom actions after order is processed
    $order = wc_get_order($order_id);
    
    if ($order) {
        // Add custom note
        $order->add_order_note('Đơn hàng được tạo từ PhoneStore Theme');
        
        // Custom email notification can be added here
    }
}
add_action('woocommerce_checkout_order_processed', 'phonestore_checkout_order_processed');

// Customize order button text
function phonestore_order_button_text($button_text) {
    return 'Hoàn Tất Đặt Hàng';
}
add_filter('woocommerce_order_button_text', 'phonestore_order_button_text');

// Add checkout page to WooCommerce endpoints
function phonestore_add_checkout_endpoint() {
    add_rewrite_endpoint('checkout', EP_ROOT | EP_PAGES);
}
add_action('init', 'phonestore_add_checkout_endpoint');

// Handle checkout page redirect
function phonestore_checkout_redirect() {
    global $woocommerce;
    
    if (is_admin()) return;
    
    // Redirect to cart if trying to checkout with empty cart
    if (is_page(wc_get_page_id('checkout')) && WC()->cart->is_empty() && !isset($_GET['order-received'])) {
        wp_redirect(wc_get_page_permalink('cart'));
        exit;
    }
}
add_action('template_redirect', 'phonestore_checkout_redirect');

// Thay vì gọi apply_filters trực tiếp, sử dung WooCommerce function:
function phonestore_cart_coupon_html($coupon_html, $coupon, $discount_amount_html) {
    if ($coupon && is_object($coupon)) {
        return $coupon_html;
    }
    return $coupon_html;
}
add_filter('woocommerce_cart_totals_coupon_html', 'phonestore_cart_coupon_html', 10, 3);

// Fix WooCommerce checkout errors
function phonestore_fix_checkout_errors() {
    if (is_admin()) return;
    
    // Ensure WooCommerce is loaded
    if (!function_exists('WC')) return;
    
    // Fix cart calculations
    if (WC()->cart) {
        WC()->cart->calculate_totals();
    }
}
add_action('wp_loaded', 'phonestore_fix_checkout_errors');

// Remove problematic hooks that cause argument count errors
remove_all_actions('woocommerce_checkout_process');
add_action('woocommerce_checkout_process', 'phonestore_checkout_field_validation');

// Fix checkout payment methods
function phonestore_fix_payment_methods() {
    // Ensure payment gateways are loaded
    if ( class_exists( 'WC_Payment_Gateways' ) ) {
        WC()->payment_gateways();
    }
}
add_action( 'init', 'phonestore_fix_payment_methods', 5 );

// Remove all existing checkout validation hooks that cause errors
remove_all_actions( 'woocommerce_checkout_process' );

// Add proper checkout validation
function phonestore_safe_checkout_validation() {
    if ( ! isset( $_POST['billing_phone'] ) || empty( $_POST['billing_phone'] ) ) {
        wc_add_notice( 'Số điện thoại là bắt buộc.', 'error' );
    } elseif ( ! preg_match( '/^[0-9\-\+\s\(\)]+$/', $_POST['billing_phone'] ) ) {
        wc_add_notice( 'Số điện thoại không hợp lệ.', 'error' );
    }
}
add_action( 'woocommerce_checkout_process', 'phonestore_safe_checkout_validation' );

// Ensure WooCommerce templates load properly
function phonestore_ensure_woocommerce_templates() {
    if ( ! function_exists( 'WC' ) ) {
        return;
    }
    
    // Force template loading
    if ( is_checkout() && ! is_order_received_page() ) {
        // Ensure cart is loaded
        if ( ! WC()->cart ) {
            wc_load_cart();
        }
        
        // Ensure session is started
        if ( ! WC()->session ) {
            WC()->session = new WC_Session_Handler();
            WC()->session->init();
        }
    }
}
add_action( 'template_redirect', 'phonestore_ensure_woocommerce_templates', 5 );

// Tự động tính phí ship khi thay đổi địa chỉ
function phonestore_checkout_shipping_calculator() {
    if (is_checkout()) {
        ?>
        <script type="text/javascript">
        jQuery(document).ready(function($) {
            // Tính lại phí ship khi thay đổi địa chỉ
            var addressFields = [
                '#billing_address_1',
                '#billing_city', 
                '#billing_state',
                '#shipping_address_1',
                '#shipping_city',
                '#shipping_state'
            ];
            
            $.each(addressFields, function(index, field) {
                $(document).on('change blur', field, function() {
                    // Delay để user có thể gõ xong
                    clearTimeout(window.shippingUpdateTimeout);
                    window.shippingUpdateTimeout = setTimeout(function() {
                        $('body').trigger('update_checkout');
                    }, 1000);
                });
            });
            
            // Loading indicator khi tính phí ship
            $(document).on('checkout_error', function() {
                console.log('Đang tính phí ship...');
            });
        });
        </script>
        <?php
    }
}
add_action('wp_footer', 'phonestore_checkout_shipping_calculator');

// Autofill địa chỉ bằng OpenRouteService API
function phonestore_address_autofill_script() {
    if (is_checkout()) {
        ?>
        <script type="text/javascript">
        jQuery(document).ready(function($) {
            // Autocomplete cho địa chỉ
            function setupAddressAutocomplete(inputSelector, suggestionContainerSelector) {
                let searchTimeout;
                
                $(document).on('input', inputSelector, function() {
                    const query = $(this).val();
                    const $suggestions = $(suggestionContainerSelector);
                    
                    if (query.length < 3) {
                        $suggestions.hide();
                        return;
                    }
                    
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(function() {
                        // Call OpenRouteService Geocoding API
                        $.ajax({
                            url: 'https://api.openrouteservice.org/geocode/search',
                            method: 'GET',
                            data: {
                                api_key: '<?php echo OPENROUTE_API_KEY; ?>',
                                text: query + ', Việt Nam',
                                size: 5,
                                layers: 'address'
                            },
                            success: function(data) {
                                if (data.features && data.features.length > 0) {
                                    let html = '<ul class="address-suggestions">';
                                    data.features.forEach(function(feature) {
                                        html += '<li class="suggestion-item" data-address="' + 
                                               feature.properties.label + '">' + 
                                               feature.properties.label + '</li>';
                                    });
                                    html += '</ul>';
                                    
                                    $suggestions.html(html).show();
                                }
                            }
                        });
                    }, 300);
                });
                
                // Chọn địa chỉ từ gợi ý
                $(document).on('click', '.suggestion-item', function() {
                    const address = $(this).data('address');
                    $(inputSelector).val(address);
                    $(suggestionContainerSelector).hide();
                    
                    // Trigger update checkout
                    $('body').trigger('update_checkout');
                });
            }
            
            // Thêm container cho suggestions
            if (!$('#address-suggestions').length) {
                $('#billing_address_1').after('<div id="address-suggestions" style="position:relative;z-index:999;"></div>');
            }
            
            // Setup autocomplete
            setupAddressAutocomplete('#billing_address_1', '#address-suggestions');
        });
        </script>
        
        <style>
        .address-suggestions {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            z-index: 1000;
            max-height: 200px;
            overflow-y: auto;
        }
        .suggestion-item {
            padding: 10px 15px;
            cursor: pointer;
            border-bottom: 1px solid #eee;
        }
        .suggestion-item:hover {
            background: #f5f5f5;
        }
        </style>
        <?php
    }
}
add_action('wp_footer', 'phonestore_address_autofill_script');

// Đăng ký Can Tho shipping method
function phonestore_register_can_tho_shipping() {
    require_once get_template_directory() . '/includes/class-can-tho-shipping.php';
    require_once get_template_directory() . '/includes/vietnam-districts-data.php';
}

function phonestore_add_can_tho_shipping($methods) {
    $methods['can_tho_distance_shipping'] = 'Can_Tho_Distance_Shipping';
    return $methods;
}

add_action('woocommerce_shipping_init', 'phonestore_register_can_tho_shipping');
add_filter('woocommerce_shipping_methods', 'phonestore_add_can_tho_shipping');

// Xóa phí ship cố định cũ và thay thế bằng phí động
remove_action('woocommerce_cart_calculate_fees', 'phonestore_add_shipping_fee');

// Thêm JavaScript để tính phí ship real-time
function phonestore_can_tho_shipping_script() {
    if (is_checkout() || is_cart()) {
        ?>
        <script type="text/javascript">
        jQuery(document).ready(function($) {
            // Function để tính phí ship dựa trên địa chỉ
            function calculateCanThoShipping() {
                var city = $('#billing_city').val() || $('#shipping_city').val();
                var state = $('#billing_state').val() || $('#shipping_state').val();
                var address = $('#billing_address_1').val() || $('#shipping_address_1').val();
                
                if (city && state) {
                    // Trigger update checkout để tính lại phí
                    $('body').trigger('update_checkout');
                }
            }
            
            // Lắng nghe sự thay đổi địa chỉ
            $(document).on('change', '#billing_city, #shipping_city, #billing_state, #shipping_state, #billing_address_1, #shipping_address_1', function() {
                clearTimeout(window.shippingCalculateTimeout);
                window.shippingCalculateTimeout = setTimeout(calculateCanThoShipping, 1500);
            });
            
            // Hiển thị thông tin delivery method
            $(document).on('updated_checkout', function() {
                var shippingMethods = $('input[name^="shipping_method"]');
                shippingMethods.each(function() {
                    var $this = $(this);
                    var label = $this.next('label');
                    var method = $this.val();
                    
                    // Thêm class để styling
                    if (method.includes('local')) {
                        label.addClass('local-delivery');
                    } else if (method.includes('viettel')) {
                        label.addClass('viettel-delivery');
                        if (method.includes('economy')) {
                            label.addClass('economy-service');
                        } else if (method.includes('express')) {
                            label.addClass('express-service');
                        }
                    }
                });
                
                // Highlight miễn phí
                $('label:contains("Miễn phí")').addClass('free-shipping-highlight');
            });
            
            // Hiển thị thông báo khi chọn giao hàng miễn phí
            $(document).on('change', 'input[name^="shipping_method"]', function() {
                var selectedMethod = $(this).val();
                var label = $(this).next('label').text();
                
                if (label.includes('Miễn phí')) {
                    if (!$('.free-shipping-notice').length) {
                        $(this).closest('tr').after('<tr class="free-shipping-notice"><td colspan="2"><div class="notice notice-success"><p>🎉 <strong>Chúc mừng!</strong> Bạn được giao hàng miễn phí vì ở gần cửa hàng!</p></div></td></tr>');
                    }
                } else {
                    $('.free-shipping-notice').remove();
                }
            });
        });
        </script>
        
        <style>
        .local-delivery {
            border-left: 3px solid #28a745;
            padding-left: 10px;
            background: #f8fff9;
        }
        
        .viettel-delivery {
            border-left: 3px solid #007cba;
            padding-left: 10px;
            background: #f0f8ff;
        }
        
        .free-shipping-highlight {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white !important;
            padding: 8px 12px;
            border-radius: 5px;
            font-weight: bold;
        }
        
        .express-service {
            position: relative;
        }
        
        .express-service:after {
            content: "NHANH";
            position: absolute;
            top: -5px;
            right: 5px;
            background: #dc3545;
            color: white;
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 10px;
            font-weight: bold;
        }
        
        .economy-service {
            position: relative;
        }
        
        .economy-service:after {
            content: "TIẾT KIỆM";
            position: absolute;
            top: -5px;
            right: 5px;
            background: #17a2b8;
            color: white;
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 10px;
            font-weight: bold;
        }
        
        .free-shipping-notice .notice {
            margin: 10px 0;
            padding: 10px;
        }
        </style>
        <?php
    }
}
add_action('wp_footer', 'phonestore_can_tho_shipping_script');

// Thêm thông tin khoảng cách vào order meta
function phonestore_save_shipping_distance($order_id) {
    if (!$order_id) return;
    
    $order = wc_get_order($order_id);
    $shipping_methods = $order->get_shipping_methods();
    
    foreach ($shipping_methods as $shipping_method) {
        $meta_data = $shipping_method->get_meta_data();
        foreach ($meta_data as $meta) {
            if ($meta->key === 'distance') {
                $order->update_meta_data('_shipping_distance', $meta->value);
                $order->save();
                break;
            }
        }
    }
}
add_action('woocommerce_checkout_order_processed', 'phonestore_save_shipping_distance');

// Hiển thị khoảng cách trong admin order
function phonestore_display_shipping_distance_in_admin($order) {
    $distance = $order->get_meta('_shipping_distance');
    if ($distance) {
        echo '<p><strong>Khoảng cách giao hàng:</strong> ' . round($distance, 1) . ' km</p>';
    }
}
add_action('woocommerce_admin_order_data_after_shipping_address', 'phonestore_display_shipping_distance_in_admin');
?>