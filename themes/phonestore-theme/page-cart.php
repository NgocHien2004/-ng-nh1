<?php
/*
Template Name: Cart Page
*/

get_header();
?>

<div class="container">
    <main class="main-content">
        <?php
        if (class_exists('WooCommerce')) {
            // Include WooCommerce cart template
            if (file_exists(get_template_directory() . '/woocommerce/cart/cart.php')) {
                include get_template_directory() . '/woocommerce/cart/cart.php';
            } else {
                wc_get_template('cart/cart.php');
            }
        } else {
            echo '<p>WooCommerce is not installed or activated.</p>';
        }
        ?>
    </main>
</div>

<?php get_footer(); ?>