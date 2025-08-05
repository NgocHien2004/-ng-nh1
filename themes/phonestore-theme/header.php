<?php
/*
File: wp-content/themes/phonestore-theme/header.php
Updated header with proper navigation
*/
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php bloginfo('name'); ?> <?php wp_title(); ?></title>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<header class="site-header">
    <div class="header-container">
        <div class="site-branding">
            <h1 class="site-title">
                <a href="<?php echo home_url(); ?>">
                    📱 Cửa Hàng Điện Thoại
                </a>
            </h1>
        </div>
        
        <nav class="main-navigation">
            <?php
            // Try to use WordPress menu first
            if (has_nav_menu('primary')) {
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'menu_class' => 'nav-menu',
                    'container' => false,
                    'fallback_cb' => 'phonestore_fallback_menu'
                ));
            } else {
                // Fallback menu
                phonestore_fallback_menu();
            }
            ?>
        </nav>
        
        <div class="header-actions">
            <?php if (class_exists('WooCommerce')): ?>
            <a href="<?php echo wc_get_cart_url(); ?>" class="cart-link">
                🛒 Giỏ hàng (<?php echo WC()->cart->get_cart_contents_count(); ?>)
            </a>
            <a href="<?php echo wc_get_account_endpoint_url('dashboard'); ?>" class="account-link">
                👤 Tài khoản
            </a>
            <?php endif; ?>
            
            <!-- Mobile Menu Toggle -->
            <button class="mobile-menu-toggle" aria-label="Toggle Menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </div>
    
    <!-- Mobile Navigation -->
    <nav class="mobile-navigation">
        <ul class="mobile-nav-menu">
            <li><a href="<?php echo home_url(); ?>">🏠 Trang chủ</a></li>
            <li><a href="<?php echo home_url('/shop/'); ?>">🛒 Sản phẩm</a></li>
            <li class="mobile-submenu">
                <a href="#" class="submenu-toggle">📱 Thương hiệu</a>
                <ul class="mobile-sub-menu">
                    <li><a href="<?php echo home_url('/shop/?filter_brand=iphone'); ?>">iPhone</a></li>
                    <li><a href="<?php echo home_url('/shop/?filter_brand=samsung'); ?>">Samsung</a></li>
                    <li><a href="<?php echo home_url('/shop/?filter_brand=xiaomi'); ?>">Xiaomi</a></li>
                    <li><a href="<?php echo home_url('/shop/?filter_brand=oppo'); ?>">OPPO</a></li>
                    <li><a href="<?php echo home_url('/shop/?filter_brand=vivo'); ?>">Vivo</a></li>
                </ul>
            </li>
            <li><a href="<?php 
                $page = get_page_by_path('so-sanh-san-pham');
                echo $page ? get_permalink($page->ID) : home_url('/so-sanh-san-pham/');
            ?>">⚖️ So sánh</a></li>
            <li><a href="<?php 
                $page = get_page_by_path('lien-he');
                echo $page ? get_permalink($page->ID) : home_url('/lien-he/');
            ?>">📞 Liên hệ</a></li>
            <?php if (class_exists('WooCommerce')): ?>
            <li><a href="<?php echo wc_get_cart_url(); ?>">🛒 Giỏ hàng</a></li>
            <li><a href="<?php echo wc_get_account_endpoint_url('dashboard'); ?>">👤 Tài khoản</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<?php
// Fallback menu function
function phonestore_fallback_menu() {
    $contact_page = get_page_by_path('lien-he');
    $compare_page = get_page_by_path('so-sanh-san-pham');
    
    echo '<ul class="nav-menu">';
    
    // Home
    $home_class = (is_home() || is_front_page()) ? ' class="current-menu-item"' : '';
    echo '<li' . $home_class . '><a href="' . home_url() . '">🏠 Trang chủ</a></li>';
    
    // Shop
    $shop_class = (is_shop() || is_product_category() || is_product()) ? ' class="current-menu-item"' : '';
    echo '<li' . $shop_class . '><a href="' . home_url('/shop/') . '">🛒 Sản phẩm</a></li>';
    
    // Brands dropdown
    echo '<li class="has-submenu menu-item-has-children">';
    echo '<a href="#">📱 Thương hiệu ▼</a>';
    echo '<ul class="sub-menu">';
    echo '<li><a href="' . home_url('/shop/?filter_brand=iphone') . '">📱 iPhone</a></li>';
    echo '<li><a href="' . home_url('/shop/?filter_brand=samsung') . '">📱 Samsung</a></li>';
    echo '<li><a href="' . home_url('/shop/?filter_brand=xiaomi') . '">📱 Xiaomi</a></li>';
    echo '<li><a href="' . home_url('/shop/?filter_brand=oppo') . '">📱 OPPO</a></li>';
    echo '<li><a href="' . home_url('/shop/?filter_brand=vivo') . '">📱 Vivo</a></li>';
    echo '</ul>';
    echo '</li>';
    
    // Compare page
    if ($compare_page) {
        $compare_class = (is_page($compare_page->ID)) ? ' class="current-menu-item"' : '';
        echo '<li' . $compare_class . '><a href="' . get_permalink($compare_page->ID) . '">⚖️ So sánh</a></li>';
    } else {
        echo '<li><a href="' . home_url('/so-sanh-san-pham/') . '">⚖️ So sánh</a></li>';
    }
    
    // Contact page
    if ($contact_page) {
        $contact_class = (is_page($contact_page->ID)) ? ' class="current-menu-item"' : '';
        echo '<li' . $contact_class . '><a href="' . get_permalink($contact_page->ID) . '">📞 Liên hệ</a></li>';
    } else {
        echo '<li><a href="' . home_url('/lien-he/') . '">📞 Liên hệ</a></li>';
    }
    
    echo '</ul>';
}
?>

<style>
/* === HEADER STYLES === */
.site-header {
    background: rgba(255, 255, 255, 0.98);
    backdrop-filter: blur(20px);
    box-shadow: 0 8px 32px rgba(0,0,0,0.12);
    padding: 20px 0;
    position: sticky;
    top: 0;
    z-index: 1000;
    border-bottom: 1px solid rgba(255,255,255,0.3);
}

.header-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.site-title a {
    background: linear-gradient(135deg, #e53e3e 0%, #fd9644 50%, #38a169 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    text-decoration: none;
    font-size: 28px;
    font-weight: 900;
    letter-spacing: -0.5px;
}

/* Navigation */
.main-navigation .nav-menu {
    display: flex;
    list-style: none;
    gap: 8px;
    margin: 0;
    padding: 0;
}

.nav-menu > li {
    position: relative;
}

.nav-menu a {
    color: #2d3748;
    text-decoration: none;
    padding: 12px 20px;
    display: block;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    font-weight: 600;
    font-size: 15px;
    border-radius: 12px;
    position: relative;
}

.nav-menu a:hover,
.nav-menu .current-menu-item > a,
.nav-menu .current_page_item > a {
    background: linear-gradient(135deg, #38a169 0%, #2f855a 100%);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(56, 161, 105, 0.3);
}

/* Dropdown menu */
.nav-menu .sub-menu {
    position: absolute;
    top: 100%;
    left: 0;
    background: white;
    box-shadow: 0 20px 60px rgba(0,0,0,0.15);
    min-width: 220px;
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    list-style: none;
    padding: 15px 0;
    margin: 0;
    border-radius: 16px;
    border: 1px solid rgba(0,0,0,0.06);
    z-index: 1001;
}

.nav-menu .has-submenu:hover .sub-menu,
.nav-menu .menu-item-has-children:hover .sub-menu {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.sub-menu li a {
    padding: 12px 25px;
    font-size: 14px;
    border-radius: 0;
    margin: 0 10px;
    border-radius: 8px;
}

.sub-menu li a:hover {
    background: #f8fafc;
    color: #38a169;
    transform: translateX(5px);
    box-shadow: none;
}

/* Header actions */
.header-actions {
    display: flex;
    align-items: center;
    gap: 15px;
}

.cart-link, .account-link {
    color: #2d3748;
    text-decoration: none;
    padding: 10px 16px;
    border-radius: 10px;
    font-weight: 600;
    font-size: 14px;
    transition: all 0.3s ease;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
}

.cart-link:hover, .account-link:hover {
    background: linear-gradient(135deg, #38a169 0%, #2f855a 100%);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(56, 161, 105, 0.3);
}

/* Mobile Menu Toggle */
.mobile-menu-toggle {
    display: none;
    flex-direction: column;
    background: none;
    border: none;
    cursor: pointer;
    padding: 8px;
    gap: 4px;
}

.mobile-menu-toggle span {
    width: 25px;
    height: 3px;
    background: #2d3748;
    border-radius: 2px;
    transition: all 0.3s ease;
}

.mobile-menu-toggle:hover span {
    background: #38a169;
}

.mobile-menu-toggle.active span:nth-child(1) {
    transform: rotate(45deg) translate(6px, 6px);
}

.mobile-menu-toggle.active span:nth-child(2) {
    opacity: 0;
}

.mobile-menu-toggle.active span:nth-child(3) {
    transform: rotate(-45deg) translate(6px, -6px);
}

/* Mobile Navigation */
.mobile-navigation {
    display: none;
    background: white;
    border-top: 1px solid #e2e8f0;
    padding: 20px;
}

.mobile-navigation.active {
    display: block;
    animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.mobile-nav-menu {
    list-style: none;
    padding: 0;
    margin: 0;
}

.mobile-nav-menu li {
    margin-bottom: 10px;
}

.mobile-nav-menu a {
    display: block;
    padding: 12px 0;
    color: #2d3748;
    text-decoration: none;
    font-weight: 600;
    font-size: 16px;
    border-bottom: 1px solid #f1f5f9;
    transition: color 0.3s ease;
}

.mobile-nav-menu a:hover {
    color: #38a169;
}

.mobile-sub-menu {
    list-style: none;
    padding: 0;
    margin: 10px 0 0 20px;
    display: none;
}

.mobile-sub-menu li {
    margin-bottom: 5px;
}

.mobile-sub-menu a {
    font-size: 14px;
    padding: 8px 0;
    color: #4a5568;
}

.mobile-submenu.active .mobile-sub-menu {
    display: block;
}

.submenu-toggle::after {
    content: ' ▼';
    font-size: 12px;
    transition: transform 0.3s ease;
    display: inline-block;
}

.mobile-submenu.active .submenu-toggle::after {
    transform: rotate(180deg);
}

/* Current page highlighting */
.nav-menu .current-menu-item > a,
.nav-menu .current_page_item > a,
.nav-menu .current-page-ancestor > a {
    background: linear-gradient(135deg, #38a169 0%, #2f855a 100%);
    color: white;
    box-shadow: 0 4px 15px rgba(56, 161, 105, 0.3);
}

/* === RESPONSIVE === */
@media (max-width: 768px) {
    .main-navigation {
        display: none;
    }
    
    .mobile-menu-toggle {
        display: flex;
    }
    
    .header-actions .cart-link,
    .header-actions .account-link {
        display: none;
    }
    
    .header-container {
        flex-wrap: wrap;
    }
    
    .site-title a {
        font-size: 24px;
    }
}

@media (max-width: 480px) {
    .header-container {
        padding: 0 15px;
    }
    
    .site-title a {
        font-size: 20px;
    }
    
    .mobile-menu-toggle {
        padding: 5px;
    }

    .mobile-navigation {
        padding: 15px;
    }
}

/* Fix for dropdown z-index issues */
.nav-menu .sub-menu {
    z-index: 9999;
}

/* Ensure submenu works on touch devices */
@media (hover: none) {
    .nav-menu .has-submenu > a::after,
    .nav-menu .menu-item-has-children > a::after {
        content: ' ▼';
        font-size: 12px;
    }
}
</style>

<script>
// Enhanced mobile menu functionality
document.addEventListener('DOMContentLoaded', function() {
    const mobileToggle = document.querySelector('.mobile-menu-toggle');
    const mobileNav = document.querySelector('.mobile-navigation');
    const submenuToggles = document.querySelectorAll('.submenu-toggle');
    
    // Mobile menu toggle
    if (mobileToggle && mobileNav) {
        mobileToggle.addEventListener('click', function() {
            mobileNav.classList.toggle('active');
            this.classList.toggle('active');
            
            // Prevent body scroll when menu is open
            if (mobileNav.classList.contains('active')) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        });
    }
    
    // Submenu toggles
    submenuToggles.forEach(function(toggle) {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            const parent = this.closest('.mobile-submenu');
            
            // Close other submenus
            document.querySelectorAll('.mobile-submenu.active').forEach(function(item) {
                if (item !== parent) {
                    item.classList.remove('active');
                }
            });
            
            // Toggle current submenu
            parent.classList.toggle('active');
        });
    });
    
    // Close mobile menu when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.site-header') && mobileNav && mobileNav.classList.contains('active')) {
            mobileNav.classList.remove('active');
            if (mobileToggle) mobileToggle.classList.remove('active');
            document.body.style.overflow = '';
        }
    });
    
    // Close mobile menu when clicking on a link
    const mobileLinks = document.querySelectorAll('.mobile-nav-menu a:not(.submenu-toggle)');
    mobileLinks.forEach(function(link) {
        link.addEventListener('click', function() {
            if (mobileNav) mobileNav.classList.remove('active');
            if (mobileToggle) mobileToggle.classList.remove('active');
            document.body.style.overflow = '';
        });
    });
    
    // Handle window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            if (mobileNav) mobileNav.classList.remove('active');
            if (mobileToggle) mobileToggle.classList.remove('active');
            document.body.style.overflow = '';
        }
    });
    
    // Add current page highlighting
    const currentUrl = window.location.pathname;
    const menuLinks = document.querySelectorAll('.nav-menu a, .mobile-nav-menu a');
    
    menuLinks.forEach(function(link) {
        const linkPath = new URL(link.href).pathname;
        if (linkPath === currentUrl) {
            link.closest('li').classList.add('current-menu-item');
        }
    });
});
</script>

<?php
// Add current page indicators for JavaScript enhancement
$current_page_id = get_queried_object_id();
if ($current_page_id) {
    echo '<script>document.body.setAttribute("data-page-id", "' . $current_page_id . '");</script>';
}

// Add page-specific classes
if (is_page('lien-he')) {
    echo '<script>document.body.classList.add("page-lien-he");</script>';
}
if (is_page('so-sanh-san-pham')) {  
    echo '<script>document.body.classList.add("page-so-sanh");</script>';
}
?>