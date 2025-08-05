<?php
/*
Template Name: WooCommerce
File: wp-content/themes/phonestore-theme/woocommerce.php
*/

get_header();
?>

<div class="container">
    <main class="main-content">
        <?php woocommerce_content(); ?>
    </main>
</div>

<style>
/* WooCommerce Container Styles */
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.main-content {
    margin: 40px 0;
}

/* Shop Page Styles */
.woocommerce {
    font-family: inherit;
}

.woocommerce .woocommerce-result-count,
.woocommerce .woocommerce-ordering {
    margin-bottom: 20px;
}

.woocommerce .products {
    display: grid !important;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)) !important;
    gap: 30px !important;
    margin: 0 !important;
    padding: 0 !important;
    list-style: none !important;
}

.woocommerce ul.products li.product {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s, box-shadow 0.3s;
    margin: 0 !important;
    padding: 0 !important;
    width: auto !important;
    float: none !important;
}

.woocommerce ul.products li.product:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.woocommerce ul.products li.product a img {
    width: 100%;
    height: 220px;
    object-fit: cover;
    border-bottom: 1px solid #eee;
}

.woocommerce ul.products li.product .woocommerce-loop-product__title {
    padding: 15px 20px 10px 20px;
    font-size: 16px;
    line-height: 1.4;
    margin: 0;
    color: #333;
}

.woocommerce ul.products li.product .price {
    padding: 0 20px 15px 20px;
    font-size: 18px;
    font-weight: bold;
    color: #e74c3c;
    margin: 0;
}

.woocommerce ul.products li.product .button {
    margin: 0 20px 20px 20px;
    background: #28a745;
    color: white;
    padding: 12px 20px;
    border-radius: 8px;
    text-decoration: none;
    display: block;
    text-align: center;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: background 0.3s;
}

.woocommerce ul.products li.product .button:hover {
    background: #218838;
}

/* Pagination */
.woocommerce nav.woocommerce-pagination {
    margin-top: 40px;
    text-align: center;
}

.woocommerce nav.woocommerce-pagination ul {
    display: inline-flex;
    gap: 10px;
    list-style: none;
    margin: 0;
    padding: 0;
}

.woocommerce nav.woocommerce-pagination ul li {
    margin: 0;
}

.woocommerce nav.woocommerce-pagination ul li a,
.woocommerce nav.woocommerce-pagination ul li span {
    display: block;
    padding: 10px 15px;
    background: white;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    color: #4a5568;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s;
}

.woocommerce nav.woocommerce-pagination ul li a:hover,
.woocommerce nav.woocommerce-pagination ul li span.current {
    background: #38a169;
    color: white;
    border-color: #38a169;
}

/* Single Product Page */
.woocommerce div.product {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 40px;
    margin: 40px 0;
}

.woocommerce div.product .woocommerce-product-gallery {
    margin-bottom: 0;
}

.woocommerce div.product .woocommerce-product-gallery img {
    border-radius: 15px;
}

.woocommerce div.product .entry-summary {
    padding: 20px;
}

.woocommerce div.product .entry-summary h1 {
    font-size: 28px;
    margin-bottom: 15px;
    color: #333;
}

.woocommerce div.product .entry-summary .price {
    font-size: 24px;
    font-weight: bold;
    color: #e74c3c;
    margin-bottom: 20px;
}

.woocommerce div.product .entry-summary .woocommerce-product-details__short-description {
    margin-bottom: 25px;
    line-height: 1.6;
    color: #666;
}

.woocommerce div.product form.cart {
    margin-bottom: 25px;
}

.woocommerce div.product form.cart .button {
    background: #28a745;
    color: white;
    padding: 15px 30px;
    border-radius: 10px;
    font-size: 16px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: background 0.3s;
}

.woocommerce div.product form.cart .button:hover {
    background: #218838;
}

/* Responsive */
@media (max-width: 768px) {
    .woocommerce .products {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)) !important;
        gap: 20px !important;
    }
    
    .woocommerce div.product {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .container {
        padding: 0 15px;
    }
}

/* Hide unwanted elements */
.woocommerce-sidebar,
.sidebar,
.widget-area {
    display: none !important;
}

/* Breadcrumbs */
.woocommerce .woocommerce-breadcrumb {
    margin-bottom: 20px;
    font-size: 14px;
    color: #666;
}

.woocommerce .woocommerce-breadcrumb a {
    color: #38a169;
    text-decoration: none;
}

.woocommerce .woocommerce-breadcrumb a:hover {
    text-decoration: underline;
}

/* Shop header */
.woocommerce .woocommerce-products-header {
    margin-bottom: 30px;
    text-align: center;
    padding: 30px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 15px;
}

.woocommerce .woocommerce-products-header h1 {
    font-size: 2.5rem;
    margin-bottom: 10px;
}

/* No products found */
.woocommerce .woocommerce-info {
    background: #f8f9fa;
    padding: 30px;
    border-radius: 15px;
    text-align: center;
    margin: 40px 0;
    border-left: 4px solid #38a169;
}

.woocommerce .woocommerce-info::before {
    content: "🔍";
    font-size: 48px;
    display: block;
    margin-bottom: 15px;
}
</style>

<?php get_footer(); ?>