<?php
/*
File: wp-content/themes/phonestore-theme/header.php
Cập nhật header với navigation đầy đủ
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
    <ul class="nav-menu">
        <li class="<?php echo (is_home() || is_front_page()) ? 'current-menu-item' : ''; ?>">
            <a href="<?php echo home_url(); ?>">🏠 Trang chủ</a>
        </li>
        
        <li class="<?php echo (is_page('san-pham') || is_shop() || is_product_category() || is_product()) ? 'current-menu-item' : ''; ?>">
            <a href="<?php 
                $san_pham_page = get_page_by_path('san-pham');
                if ($san_pham_page) {
                    echo get_permalink($san_pham_page->ID);
                } elseif (class_exists('WooCommerce')) {
                    echo wc_get_page_permalink('shop');
                } else {
                    echo home_url('/san-pham/');
                }
            ?>">🛒 Sản phẩm</a>
        </li>
        
        <li class="<?php echo (is_page('so-sanh-san-pham')) ? 'current-menu-item' : ''; ?>">
            <a href="<?php 
                $so_sanh_page = get_page_by_path('so-sanh-san-pham');
                echo $so_sanh_page ? get_permalink($so_sanh_page->ID) : home_url('/so-sanh-san-pham/');
            ?>">⚖️ So sánh</a>
        </li>
        
        <li class="<?php echo (is_page('lien-he')) ? 'current-menu-item' : ''; ?>">
            <a href="<?php 
                $lien_he_page = get_page_by_path('lien-he');
                echo $lien_he_page ? get_permalink($lien_he_page->ID) : home_url('/lien-he/');
            ?>">📞 Liên hệ</a>
        </li>
    </ul>
</nav>
        
        <div class="header-actions">
            <?php if (class_exists('WooCommerce')): ?>
            <a href="<?php echo wc_get_cart_url(); ?>" class="cart-link">
                🛒 Giỏ hàng (<?php echo WC()->cart->get_cart_contents_count(); ?>)
            </a>
            <a href="<?php echo wc_get_page_permalink('myaccount'); ?>" class="account-link">
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
            <li><a href="<?php 
                $san_pham_page = get_page_by_path('san-pham');
                echo $san_pham_page ? get_permalink($san_pham_page->ID) : home_url('/san-pham/');
            ?>">🛒 Sản phẩm</a></li>
            <li class="mobile-submenu">
                <a href="#" class="submenu-toggle">📱 Thương hiệu</a>
                <ul class="mobile-sub-menu">
                    <li><a href="<?php echo home_url('/san-pham/?brand=iphone'); ?>">iPhone</a></li>
                    <li><a href="<?php echo home_url('/san-pham/?brand=samsung'); ?>">Samsung</a></li>
                    <li><a href="<?php echo home_url('/san-pham/?brand=xiaomi'); ?>">Xiaomi</a></li>
                    <li><a href="<?php echo home_url('/san-pham/?brand=oppo'); ?>">OPPO</a></li>
                    <li><a href="<?php echo home_url('/san-pham/?brand=vivo'); ?>">Vivo</a></li>
                </ul>
            </li>
            <li><a href="<?php echo home_url('/so-sanh-san-pham/'); ?>">⚖️ So sánh</a></li>
            <li><a href="<?php echo home_url('/lien-he/'); ?>">📞 Liên hệ</a></li>
            <?php if (class_exists('WooCommerce')): ?>
            <li><a href="<?php echo wc_get_cart_url(); ?>">🛒 Giỏ hàng</a></li>
            <li><a href="<?php echo wc_get_account_endpoint_url('dashboard'); ?>">👤 Tài khoản</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<style>
.site-header {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  box-shadow: 0 8px 32px rgba(0,0,0,0.15);
  padding: 15px 0;
  position: sticky;
  top: 0;
  z-index: 1000;
}

.header-container {
  display: flex;
  justify-content: space-between;
  align-items: center;
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 20px;
}

/* Logo/Site Title */
.site-title a {
  color: white;
  text-decoration: none;
  font-size: 24px;
  font-weight: 900;
  letter-spacing: -0.5px;
  text-shadow: 0 2px 4px rgba(0,0,0,0.2);
  transition: all 0.3s ease;
}

.site-title a:hover {
  transform: scale(1.05);
}

/* Main Navigation */
.main-navigation .nav-menu {
  display: flex;
  list-style: none;
  gap: 5px;
  margin: 0;
  padding: 0;
}

.nav-menu > li {
  position: relative;
}

.nav-menu a {
  color: white;
  text-decoration: none;
  padding: 12px 18px;
  display: block;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  font-weight: 600;
  font-size: 14px;
  border-radius: 25px;
  white-space: nowrap;
  background: rgba(255,255,255,0.1);
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255,255,255,0.2);
}

.nav-menu a:hover,
.nav-menu .current-menu-item a {
  background: rgba(255,255,255,0.25);
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(0,0,0,0.2);
  border-color: rgba(255,255,255,0.4);
}

/* Header Actions */
.header-actions {
  display: flex;
  align-items: center;
  gap: 10px;
}

.cart-link, .account-link {
  color: white;
  text-decoration: none;
  padding: 10px 16px;
  border-radius: 20px;
  font-weight: 600;
  font-size: 13px;
  transition: all 0.3s ease;
  background: rgba(255,255,255,0.15);
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255,255,255,0.2);
  white-space: nowrap;
}

.cart-link:hover, .account-link:hover {
  background: rgba(255,255,255,0.25);
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(0,0,0,0.2);
}

/* Mobile Menu Toggle */
.mobile-menu-toggle {
  display: none;
  flex-direction: column;
  background: rgba(255,255,255,0.15);
  border: 1px solid rgba(255,255,255,0.2);
  cursor: pointer;
  padding: 8px;
  gap: 4px;
  border-radius: 8px;
  backdrop-filter: blur(10px);
}

.mobile-menu-toggle span {
  width: 22px;
  height: 2px;
  background: white;
  border-radius: 1px;
  transition: all 0.3s ease;
}

.mobile-menu-toggle:hover {
  background: rgba(255,255,255,0.25);
}

/* Mobile Navigation */
.mobile-navigation {
  display: none;
  background: rgba(255,255,255,0.95);
  backdrop-filter: blur(20px);
  border-top: 1px solid rgba(255,255,255,0.3);
  padding: 20px;
  margin-top: 10px;
  border-radius: 15px;
  margin: 10px 20px 0;
  box-shadow: 0 8px 32px rgba(0,0,0,0.1);
}

.mobile-nav-menu {
  list-style: none;
  padding: 0;
  margin: 0;
}

.mobile-nav-menu li {
  margin-bottom: 8px;
}

.mobile-nav-menu a {
  display: block;
  padding: 12px 15px;
  color: #2d3748;
  text-decoration: none;
  font-weight: 600;
  font-size: 15px;
  border-radius: 10px;
  transition: all 0.3s ease;
  background: rgba(103, 58, 183, 0.05);
}

.mobile-nav-menu a:hover {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  transform: translateX(5px);
}

/* === RESPONSIVE === */
@media (max-width: 768px) {
  .main-navigation {
    display: none;
  }
  
  .mobile-menu-toggle {
    display: flex;
  }
  
  .mobile-navigation.active {
    display: block;
  }
  
  .header-actions .cart-link,
  .header-actions .account-link {
    font-size: 12px;
    padding: 8px 12px;
  }
  
  .header-container {
    flex-wrap: wrap;
  }
  
  .site-title a {
    font-size: 20px;
  }
}

@media (max-width: 480px) {
  .header-container {
    padding: 0 15px;
  }
  
  .site-title a {
    font-size: 18px;
  }
  
  .header-actions .cart-link,
  .header-actions .account-link {
    display: none;
  }
}

/* Animation for mobile menu */
.mobile-menu-toggle.active span:nth-child(1) {
  transform: rotate(45deg) translate(5px, 5px);
}

.mobile-menu-toggle.active span:nth-child(2) {
  opacity: 0;
}

.mobile-menu-toggle.active span:nth-child(3) {
  transform: rotate(-45deg) translate(5px, -5px);
}

</style>

<script>
// Mobile menu toggle
document.addEventListener('DOMContentLoaded', function() {
    const mobileToggle = document.querySelector('.mobile-menu-toggle');
    const mobileNav = document.querySelector('.mobile-navigation');
    const submenuToggles = document.querySelectorAll('.submenu-toggle');
    
    if (mobileToggle && mobileNav) {
        mobileToggle.addEventListener('click', function() {
            mobileNav.classList.toggle('active');
            this.classList.toggle('active');
        });
    }
    
    // Submenu toggles
    submenuToggles.forEach(function(toggle) {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            const parent = this.closest('.mobile-submenu');
            parent.classList.toggle('active');
        });
    });
    
    // Close mobile menu when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.site-header') && mobileNav) {
            mobileNav.classList.remove('active');
            mobileToggle.classList.remove('active');
        }
    });
});
</script>

<?php
// Add current page indicators
if (is_page('lien-he')) {
    echo '<script>document.body.classList.add("page-lien-he");</script>';
}
if (is_page('so-sanh-san-pham')) {
    echo '<script>document.body.classList.add("page-so-sanh");</script>';
}
if (is_page('san-pham')) {
    echo '<script>document.body.classList.add("page-san-pham");</script>';
}
?>