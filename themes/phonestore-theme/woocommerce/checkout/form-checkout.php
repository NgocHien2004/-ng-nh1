<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout.
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
	return;
}

?>

<div class="checkout-page-container">
	<div class="checkout-header">
		<h1>🚀 Thanh Toán</h1>
		<p>Hoàn tất đơn hàng của bạn</p>
	</div>

	<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

		<div class="checkout-main-content">
			<!-- Left Column: Billing & Shipping -->
			<div class="checkout-billing-section">
				<?php if ( $checkout->get_checkout_fields() ) : ?>

					<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

					<div class="col2-set" id="customer_details">
						<div class="col-1">
							<?php do_action( 'woocommerce_checkout_billing' ); ?>
						</div>

						<div class="col-2">
							<?php do_action( 'woocommerce_checkout_shipping' ); ?>
						</div>
					</div>

					<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

				<?php endif; ?>
			</div>

			<!-- Right Column: Order Review -->
			<div class="checkout-order-section">
				<?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>
				
				<h3 id="order_review_heading">📋 Đơn Hàng Của Bạn</h3>
				
				<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

				<div id="order_review" class="woocommerce-checkout-review-order">
					<?php do_action( 'woocommerce_checkout_order_review' ); ?>
				</div>

				<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
			</div>
		</div>

	</form>
</div>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>

<style>
/* Checkout Page Styles */
.checkout-page-container {
	max-width: 1400px;
	margin: 0 auto;
	padding: 20px;
}

.checkout-header {
    text-align: center;
    margin-bottom: 40px;
    padding: 30px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white; /* Sửa từ "cthemes..." thành "white" */
    border-radius: 15px;
    box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
}

.checkout-header h1 {
	font-size: 2.5rem;
	margin: 0 0 10px 0;
	font-weight: 700;
}

.checkout-header p {
	font-size: 1.1rem;
	margin: 0;
	opacity: 0.9;
}

.checkout-main-content {
	display: grid;
	grid-template-columns: 1fr 400px;
	gap: 40px;
	margin-top: 30px;
}

.checkout-billing-section {
	background: white;
	padding: 30px;
	border-radius: 15px;
	box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.checkout-order-section {
	background: white;
	padding: 30px;
	border-radius: 15px;
	box-shadow: 0 5px 15px rgba(0,0,0,0.1);
	height: fit-content;
	position: sticky;
	top: 20px;
}

#order_review_heading {
	color: #333;
	font-size: 1.5rem;
	margin-bottom: 25px;
	padding-bottom: 15px;
	border-bottom: 2px solid #f1f5f9;
}

/* Form Fields */
.woocommerce-checkout .form-row {
	margin-bottom: 20px;
}

.woocommerce-checkout .form-row label {
	font-weight: 600;
	color: #4a5568;
	margin-bottom: 8px;
	display: block;
}

.woocommerce-checkout .form-row input[type="text"],
.woocommerce-checkout .form-row input[type="email"],
.woocommerce-checkout .form-row input[type="tel"],
.woocommerce-checkout .form-row select,
.woocommerce-checkout .form-row textarea {
	width: 100%;
	padding: 12px 15px;
	border: 2px solid #e2e8f0;
	border-radius: 8px;
	font-size: 16px;
	transition: border-color 0.3s;
}

.woocommerce-checkout .form-row input:focus,
.woocommerce-checkout .form-row select:focus,
.woocommerce-checkout .form-row textarea:focus {
	border-color: #667eea;
	outline: none;
	box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.woocommerce-checkout .form-row-first,
.woocommerce-checkout .form-row-last {
	width: 48%;
}

.woocommerce-checkout .form-row-first {
	float: left;
	margin-right: 4%;
}

.woocommerce-checkout .form-row-last {
	float: right;
}

/* Section Headers */
.checkout-billing-section h3,
.checkout-order-section h3 {
	color: #2d3748;
	font-size: 1.4rem;
	margin-bottom: 25px;
	padding-bottom: 15px;
	border-bottom: 2px solid #f1f5f9;
}

/* Order Review Table */
.woocommerce-checkout-review-order-table {
	width: 100%;
	border-collapse: collapse;
	margin-bottom: 25px;
}

.woocommerce-checkout-review-order-table th,
.woocommerce-checkout-review-order-table td {
	padding: 15px;
	text-align: left;
	border-bottom: 1px solid #f1f5f9;
}

.woocommerce-checkout-review-order-table th {
	background: #f8fafc;
	font-weight: 600;
	color: #4a5568;
}

.woocommerce-checkout-review-order-table .cart_item td {
	vertical-align: top;
}

.woocommerce-checkout-review-order-table .product-name {
	font-weight: 600;
}

.woocommerce-checkout-review-order-table .product-total {
	font-weight: 700;
	color: #e74c3c;
	text-align: right;
}

.woocommerce-checkout-review-order-table .order-total th,
.woocommerce-checkout-review-order-table .order-total td {
	font-size: 1.2rem;
	font-weight: 700;
	color: #2d3748;
	border-top: 2px solid #e2e8f0;
	border-bottom: none;
}

.woocommerce-checkout-review-order-table .order-total td {
	color: #e74c3c;
}

/* Payment Methods */
.wc_payment_methods {
	list-style: none;
	padding: 0;
	margin: 25px 0;
}

.wc_payment_method {
	border: 2px solid #e2e8f0;
	border-radius: 10px;
	margin-bottom: 15px;
	overflow: hidden;
	transition: all 0.3s;
}

.wc_payment_method:hover {
	border-color: #667eea;
	box-shadow: 0 4px 12px rgba(102, 126, 234, 0.1);
}

.wc_payment_method input[type="radio"] {
	position: absolute;
	opacity: 0;
	pointer-events: none;
}

.wc_payment_method label {
	display: block;
	padding: 20px;
	cursor: pointer;
	font-weight: 600;
	color: #4a5568;
	transition: all 0.3s;
	position: relative;
}

.wc_payment_method label::before {
	content: '';
	width: 20px;
	height: 20px;
	border: 2px solid #cbd5e1;
	border-radius: 50%;
	position: absolute;
	right: 20px;
	top: 50%;
	transform: translateY(-50%);
	transition: all 0.3s;
}

.wc_payment_method input[type="radio"]:checked + label {
	background: #f0f4ff;
	color: #667eea;
}

.wc_payment_method input[type="radio"]:checked + label::before {
	border-color: #667eea;
	background: #667eea;
	box-shadow: inset 0 0 0 4px white;
}

.payment_box {
	padding: 20px;
	background: #f8fafc;
	border-top: 1px solid #e2e8f0;
	font-size: 14px;
	color: #64748b;
}

/* Place Order Button */
#place_order {
	width: 100%;
	padding: 18px 30px;
	background: linear-gradient(135deg, #10b981 0%, #059669 100%);
	color: white;
	border: none;
	border-radius: 12px;
	font-size: 18px;
	font-weight: 700;
	text-transform: uppercase;
	letter-spacing: 1px;
	cursor: pointer;
	transition: all 0.3s;
	box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
	margin-top: 25px;
}

#place_order:hover {
	transform: translateY(-2px);
	box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
	background: linear-gradient(135deg, #059669 0%, #047857 100%);
}

#place_order:disabled {
	background: #9ca3af;
	cursor: not-allowed;
	transform: none;
	box-shadow: none;
}

/* Terms and Conditions */
.woocommerce-terms-and-conditions-wrapper {
	margin: 25px 0;
	padding: 20px;
	background: #f8fafc;
	border-radius: 10px;
	border: 1px solid #e2e8f0;
}

.woocommerce-terms-and-conditions-checkbox-text {
	display: flex;
	align-items: center;
	gap: 10px;
	font-size: 14px;
	color: #4a5568;
}

.woocommerce-terms-and-conditions-checkbox-text input[type="checkbox"] {
	width: 18px;
	height: 18px;
	accent-color: #667eea;
}

/* Responsive */
@media (max-width: 1024px) {
	.checkout-main-content {
		grid-template-columns: 1fr;
		gap: 30px;
	}
	
	.checkout-order-section {
		position: static;
	}
}

@media (max-width: 768px) {
	.checkout-page-container {
		padding: 15px;
	}
	
	.checkout-header {
		padding: 20px;
	}
	
	.checkout-header h1 {
		font-size: 2rem;
	}
	
	.checkout-billing-section,
	.checkout-order-section {
		padding: 20px;
	}
	
	.woocommerce-checkout .form-row-first,
	.woocommerce-checkout .form-row-last {
		width: 100%;
		float: none;
		margin-right: 0;
	}
	
	.woocommerce-checkout-review-order-table th,
	.woocommerce-checkout-review-order-table td {
		padding: 10px 8px;
		font-size: 14px;
	}
	
	#place_order {
		font-size: 16px;
		padding: 15px 25px;
	}
}

/* Loading States */
.processing {
	opacity: 0.6;
	pointer-events: none;
}

.blockUI.blockOverlay {
	background: rgba(255, 255, 255, 0.8);
	display: flex !important;
	align-items: center;
	justify-content: center;
}

.blockUI.blockOverlay::before {
	content: "⏳ Đang xử lý...";
	font-size: 18px;
	font-weight: 600;
	color: #667eea;
}

/* Error Messages */
.woocommerce-error,
.woocommerce-message,
.woocommerce-info {
	padding: 15px 20px;
	margin-bottom: 20px;
	border-radius: 10px;
	font-weight: 500;
}

.woocommerce-error {
	background: #fee2e2;
	color: #dc2626;
	border: 1px solid #fecaca;
}

.woocommerce-message {
	background: #dcfce7;
	color: #16a34a;
	border: 1px solid #bbf7d0;
}

.woocommerce-info {
	background: #dbeafe;
	color: #2563eb;
	border: 1px solid #bfdbfe;
}
</style>

<script>
jQuery(document).ready(function($) {
	// Update checkout on field change
	$('body').on('change', '.checkout .input-text, .checkout select, .checkout input[type="radio"]', function() {
		$('body').trigger('update_checkout');
	});
	
	// Smooth scroll to error
	if ($('.woocommerce-error').length) {
		$('html, body').animate({
			scrollTop: $('.woocommerce-error').offset().top - 100
		}, 1000);
	}
	
	// Place order button loading state
	$('#place_order').on('click', function() {
		$(this).text('⏳ Đang xử lý...');
	});
});
</script>