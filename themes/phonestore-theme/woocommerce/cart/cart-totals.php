<?php
/**
 * Cart totals
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-totals.php.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 2.3.6
 */

defined( 'ABSPATH' ) || exit;

?>
<div class="cart_totals <?php echo ( WC()->customer->has_calculated_shipping() ) ? 'calculated_shipping' : ''; ?>">

	<?php do_action( 'woocommerce_before_cart_totals' ); ?>

	<h2>💰 Tổng Tiền Giỏ Hàng</h2>

	<table cellspacing="0" class="shop_table shop_table_responsive">

		<tr class="cart-subtotal">
			<th>Tạm tính</th>
			<td data-title="<?php esc_attr_e( 'Subtotal', 'woocommerce' ); ?>"><?php wc_cart_totals_subtotal_html(); ?></td>
		</tr>

		<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
			<tr class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
				<th>🎟️ Mã giảm giá: <?php echo esc_html( wc_cart_totals_coupon_label( $coupon ) ); ?></th>
				<td data-title="<?php echo esc_attr( wc_cart_totals_coupon_label( $coupon ) ); ?>"><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
			</tr>
		<?php endforeach; ?>

		<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

			<?php do_action( 'woocommerce_cart_totals_before_shipping' ); ?>

			<?php wc_cart_totals_shipping_html(); ?>

			<?php do_action( 'woocommerce_cart_totals_after_shipping' ); ?>

		<?php elseif ( WC()->cart->needs_shipping() && 'yes' === get_option( 'woocommerce_enable_shipping_calc' ) ) : ?>

			<tr class="shipping">
				<th>🚚 Vận chuyển</th>
				<td data-title="<?php esc_attr_e( 'Shipping', 'woocommerce' ); ?>"><?php woocommerce_shipping_calculator(); ?></td>
			</tr>

		<?php endif; ?>

		<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
			<tr class="fee">
				<th><?php echo esc_html( $fee->name ); ?></th>
				<td data-title="<?php echo esc_attr( $fee->name ); ?>"><?php wc_cart_totals_fee_html( $fee ); ?></td>
			</tr>
		<?php endforeach; ?>

		<?php
		if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) {
			$taxable_address = WC()->customer->get_taxable_address();
			$estimated_text  = '';

			if ( WC()->customer->is_customer_outside_base() && ! WC()->customer->has_calculated_shipping() ) {
				/* translators: %s location. */
				$estimated_text = sprintf( ' <small>' . esc_html__( '(estimated for %s)', 'woocommerce' ) . '</small>', WC()->countries->estimated_for_prefix( $taxable_address[0] ) . WC()->countries->countries[ $taxable_address[0] ] );
			}

			if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) {
				foreach ( WC()->cart->get_tax_totals() as $code => $tax ) { ?>
					<tr class="tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
						<th>💵 <?php echo esc_html( $tax->label ) . $estimated_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></th>
						<td data-title="<?php echo esc_attr( $tax->label ); ?>"><?php echo wp_kses_post( $tax->formatted_amount ); ?></td>
					</tr>
					<?php
				}
			} else { ?>
				<tr class="tax-total">
					<th>💵 <?php echo esc_html( WC()->countries->tax_or_vat() ) . $estimated_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></th>
					<td data-title="<?php echo esc_attr( WC()->countries->tax_or_vat() ); ?>"><?php wc_cart_totals_taxes_total_html(); ?></td>
				</tr>
				<?php
			}
		}
		?>

		<?php do_action( 'woocommerce_cart_totals_before_order_total' ); ?>

		<tr class="order-total">
			<th>🏆 Tổng cộng</th>
			<td data-title="<?php esc_attr_e( 'Total', 'woocommerce' ); ?>"><?php wc_cart_totals_order_total_html(); ?></td>
		</tr>

		<?php do_action( 'woocommerce_cart_totals_after_order_total' ); ?>

	</table>

   <!-- User Info Display -->
   <?php if (is_user_logged_in()): 
   	$user = wp_get_current_user();
   	$billing_first_name = get_user_meta($user->ID, 'billing_first_name', true) ?: $user->first_name;
   	$billing_last_name = get_user_meta($user->ID, 'billing_last_name', true) ?: $user->last_name;
   	$billing_email = get_user_meta($user->ID, 'billing_email', true) ?: $user->user_email;
   	$billing_phone = get_user_meta($user->ID, 'billing_phone', true);
   	$billing_address = get_user_meta($user->ID, 'billing_address_1', true);
   	$billing_city = get_user_meta($user->ID, 'billing_city', true);
   ?>
   <div class="customer-info-preview">
   	<h4>👤 Thông tin nhận hóa đơn</h4>
   	<div class="customer-details">
   		<div class="customer-name">
   			<strong><?php echo esc_html(trim($billing_first_name . ' ' . $billing_last_name)); ?></strong>
   		</div>
   		<div class="customer-email">
   			📧 <?php echo esc_html($billing_email); ?>
   		</div>
   		<?php if ($billing_phone): ?>
   		<div class="customer-phone">
   			📱 <?php echo esc_html($billing_phone); ?>
   		</div>
   		<?php endif; ?>
   		<?php if ($billing_address || $billing_city): ?>
   		<div class="customer-address">
   			📍 <?php echo esc_html(trim($billing_address . ', ' . $billing_city)); ?>
   		</div>
   		<?php endif; ?>
   		
   		<div class="edit-info-link">
   			<a href="<?php echo wc_get_account_endpoint_url('edit-address', 'billing'); ?>" target="_blank">
   				✏️ Cập nhật thông tin
   			</a>
   		</div>
   	</div>
   </div>
   <?php endif; ?>

   <!-- Payment Methods Info -->
   <div class="payment-methods-info">
   	<h4>💳 Phương thức thanh toán</h4>
   	<div class="payment-methods">
   		<div class="payment-method">
   			<span class="payment-icon">💵</span>
   			<span>Tiền mặt khi nhận hàng</span>
   		</div>
   		<div class="payment-method">
   			<span class="payment-icon">🏦</span>
   			<span>Chuyển khoản ngân hàng</span>
   		</div>
   		<div class="payment-method">
   			<span class="payment-icon">📱</span>
   			<span>Ví điện tử (MoMo, ZaloPay)</span>
   		</div>
   		<div class="payment-method">
   			<span class="payment-icon">💳</span>
   			<span>Thẻ tín dụng/ghi nợ</span>
   		</div>
   	</div>
   </div>


<!-- Checkout Buttons -->
<?php if (is_user_logged_in()): ?>
<div class="checkout-buttons-section">
    <h4>🚀 Tiếp tục thanh toán</h4>
    <div class="checkout-buttons-wrapper">
        <form class="checkout-form-with-invoice" method="post">
            <?php wp_nonce_field('proceed_to_checkout', 'checkout_nonce'); ?>
            <input type="hidden" name="proceed_to_checkout" value="1">
            
            <button type="submit" name="proceed_to_checkout" class="checkout-btn invoice-btn">
                <div class="btn-icon">📧</div>
                <div class="btn-content">
                    <div class="btn-title">Gửi hóa đơn</div>
                    <div class="btn-subtitle">Xem trước qua email</div>
                </div>
            </button>
        </form>
        
        <a href="<?php echo wc_get_checkout_url(); ?>" class="checkout-btn direct-btn">
            <div class="btn-icon">🚀</div>
            <div class="btn-content">
                <div class="btn-title">Thanh toán ngay</div>
                <div class="btn-subtitle">Đi tới trang checkout</div>
            </div>
        </a>
    </div>
    
    <div class="checkout-notice">
        💡 Bạn có thể gửi hóa đơn qua email để xem trước hoặc thanh toán trực tiếp
    </div>
</div>
<?php endif; ?>

   <!-- Invoice Preview -->
   <?php if (WC()->session->get('pending_invoice')): 
   	$pending_invoice = WC()->session->get('pending_invoice');
   ?>
   <div class="pending-invoice-notice">
   	<div class="invoice-success">
   		<h4>✅ Hóa đơn đã được gửi thành công!</h4>
   		<div class="invoice-details">
   			<p><strong>Số hóa đơn:</strong> <?php echo esc_html($pending_invoice['invoice_number']); ?></p>
   			<p><strong>Gửi đến:</strong> <?php echo esc_html($pending_invoice['customer_email']); ?></p>
   			<p><strong>Thời gian:</strong> <?php echo date('d/m/Y H:i', strtotime($pending_invoice['sent_time'])); ?></p>
   		</div>

   	</div>
   </div>
   <?php endif; ?>

   <?php do_action( 'woocommerce_after_cart_totals' ); ?>

</div>

<style>
/* Cart Totals Styles */
.cart_totals {
   background: white;
   border-radius: 15px;
   padding: 30px;
   box-shadow: 0 5px 15px rgba(0,0,0,0.1);
   margin: 40px 0;
}

.cart_totals h2 {
   color: #333;
   font-size: 1.8rem;
   margin-bottom: 25px;
   text-align: center;
   border-bottom: 2px solid #f1f5f9;
   padding-bottom: 15px;
}

.cart_totals .shop_table {
   width: 100%;
   border-collapse: collapse;
   margin-bottom: 30px;
}

.cart_totals .shop_table th,
.cart_totals .shop_table td {
   padding: 15px 20px;
   border-bottom: 1px solid #f1f5f9;
   text-align: left;
}

.cart_totals .shop_table th {
   font-weight: 600;
   color: #4a5568;
   width: 60%;
}

.cart_totals .shop_table td {
   font-weight: 700;
   color: #e74c3c;
   font-size: 16px;
   text-align: right;
}

.cart_totals .order-total th,
.cart_totals .order-total td {
   font-size: 20px;
   font-weight: 700;
   color: #2d3748;
   border-top: 2px solid #e2e8f0;
   border-bottom: none;
   padding: 20px;
}

.cart_totals .order-total td {
   color: #e74c3c;
}

/* Custom Checkout Form */
.checkout-form-with-invoice {
   text-align: center;
}

.checkout-button {
   background: linear-gradient(135deg, #10b981 0%, #059669 100%);
   color: white !important;
   padding: 18px 40px;
   border-radius: 12px;
   text-decoration: none;
   font-size: 18px;
   font-weight: 700;
   text-transform: uppercase;
   letter-spacing: 1px;
   display: inline-block;
   transition: all 0.3s;
   border: none;
   cursor: pointer;
   box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
   position: relative;
   overflow: hidden;
}

.checkout-button:hover {
   transform: translateY(-2px);
   box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
   background: linear-gradient(135deg, #059669 0%, #047857 100%);
}

.checkout-button:disabled {
   background: #9ca3af;
   cursor: not-allowed;
   transform: none;
   box-shadow: none;
}

.checkout-notice {
   margin-top: 15px;
   color: #059669;
   font-style: italic;
}

/* Login Required Notice */
.login-required-notice {
   text-align: center;
   background: #fef3c7;
   padding: 25px;
   border-radius: 12px;
   border: 2px solid #f59e0b;
   margin: 20px 0;
}

.login-required-notice p {
   margin-bottom: 20px;
   color: #92400e;
   font-size: 16px;
}

.login-button,
.register-button {
   background: #3b82f6;
   color: white !important;
   padding: 12px 24px;
   border-radius: 8px;
   text-decoration: none;
   font-weight: 600;
   margin: 0 10px;
   display: inline-block;
   transition: background 0.3s;
}

.login-button:hover {
   background: #2563eb;
}

.register-button {
   background: #059669;
}

.register-button:hover {
   background: #047857;
}

/* Customer Info Preview */
.customer-info-preview {
   background: #f0f9ff;
   padding: 20px;
   border-radius: 12px;
   margin: 25px 0;
   border: 2px solid #0ea5e9;
}

.customer-info-preview h4 {
   color: #0c4a6e;
   margin-bottom: 15px;
   font-size: 16px;
}

.customer-details {
   line-height: 1.8;
}

.customer-name {
   font-size: 16px;
   margin-bottom: 8px;
}

.customer-email,
.customer-phone,
.customer-address {
   color: #0369a1;
   font-size: 14px;
   margin-bottom: 5px;
}

.edit-info-link {
   margin-top: 15px;
   text-align: center;
}

.edit-info-link a {
   color: #0ea5e9;
   text-decoration: none;
   font-size: 13px;
   padding: 6px 12px;
   background: #e0f2fe;
   border-radius: 6px;
   transition: all 0.3s;
}

.edit-info-link a:hover {
   background: #0ea5e9;
   color: white;
}

/* Payment Methods Info */
.payment-methods-info {
   margin-top: 30px;
   padding: 25px;
   background: #f8fafc;
   border-radius: 12px;
   border: 2px solid #e2e8f0;
}

.payment-methods-info h4 {
   color: #1a202c;
   font-size: 16px;
   font-weight: 700;
   margin-bottom: 20px;
   text-align: center;
}

.payment-methods {
   display: grid;
   grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
   gap: 15px;
}

.payment-method {
   display: flex;
   align-items: center;
   gap: 10px;
   padding: 12px 15px;
   background: white;
   border-radius: 8px;
   font-size: 14px;
   font-weight: 500;
   color: #4a5568;
   border: 1px solid #e2e8f0;
   transition: all 0.3s;
}

.payment-method:hover {
   border-color: #10b981;
   background: #f0fdf4;
   color: #065f46;
}

.payment-icon {
   font-size: 18px;
   min-width: 20px;
}

/* Pending Invoice Notice */
.pending-invoice-notice {
   background: #d1fae5;
   border: 2px solid #10b981;
   border-radius: 12px;
   padding: 25px;
   margin: 25px 0;
   text-align: center;
}

.invoice-success h4 {
   color: #065f46;
   margin-bottom: 15px;
   font-size: 18px;
}

.invoice-details {
   background: white;
   padding: 15px;
   border-radius: 8px;
   margin: 15px 0;
   text-align: left;
}

.invoice-details p {
   margin: 5px 0;
   color: #374151;
}

.invoice-actions {
   display: flex;
   gap: 15px;
   justify-content: center;
   margin-top: 20px;
   flex-wrap: wrap;
}

.checkout-button-final {
   background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
   color: white !important;
   padding: 15px 30px;
   border-radius: 10px;
   text-decoration: none;
   font-weight: 700;
   transition: all 0.3s;
   box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
}

.checkout-button-final:hover {
   transform: translateY(-2px);
   box-shadow: 0 8px 25px rgba(245, 158, 11, 0.4);
}

.resend-invoice {
   background: #6366f1;
   color: white;
   padding: 15px 25px;
   border: none;
   border-radius: 10px;
   font-weight: 600;
   cursor: pointer;
   transition: background 0.3s;
}

.resend-invoice:hover {
   background: #4f46e5;
}

/* Coupon styles */
.cart-discount td {
   color: #10b981 !important;
}

.cart-discount .woocommerce-remove-coupon {
   background: #fee2e2;
   color: #dc2626;
   padding: 4px 8px;
   border-radius: 4px;
   text-decoration: none;
   font-size: 12px;
   margin-left: 10px;
   transition: all 0.3s;
}

.cart-discount .woocommerce-remove-coupon:hover {
   background: #dc2626;
   color: white;
}

/* Loading States */
.checkout-loading {
   display: none;
}

.checkout-form-with-invoice.loading .checkout-text {
   display: none;
}

.checkout-form-with-invoice.loading .checkout-loading {
   display: inline;
}

.checkout-form-with-invoice.loading .checkout-button {
   background: #9ca3af;
   cursor: not-allowed;
   transform: none;
   box-shadow: none;
}

/* CHECKOUT BUTTONS SECTION - TOÀN BỘ VỚI !IMPORTANT */
.checkout-buttons-section {
    margin: 30px 0 !important;
    padding: 25px !important;
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%) !important;
    border-radius: 15px !important;
    border: 2px solid #e2e8f0 !important;
    text-align: center !important;
    box-shadow: 0 4px 20px rgba(0,0,0,0.06) !important;
}

.checkout-buttons-section h4 {
    color: #1a202c !important;
    font-size: 18px !important;
    font-weight: 700 !important;
    margin-bottom: 20px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 8px !important;
}

.checkout-buttons-wrapper {
    display: flex !important;
    gap: 20px !important;
    justify-content: center !important;
    align-items: stretch !important;
    margin-bottom: 15px !important;
}

.checkout-form-with-invoice {
    flex: 1 !important;
    max-width: 280px !important;
}

.checkout-btn {
    width: 100% !important;
    height: 90px !important;
    padding: 0 !important;
    border: none !important;
    border-radius: 16px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: flex-start !important;
    gap: 20px !important;
    text-decoration: none !important;
    font-weight: 600 !important;
    cursor: pointer !important;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08) !important;
    position: relative !important;
    overflow: hidden !important;
    padding-left: 25px !important;
    padding-right: 20px !important;
    box-sizing: border-box !important;
    min-height: 90px !important;
    max-height: 90px !important;
}

.checkout-btn:hover {
    transform: translateY(-2px) !important;
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}

.checkout-btn:active {
    transform: translateY(0) !important;
}

.invoice-btn {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
    color: white !important;
}

.invoice-btn:hover {
    background: linear-gradient(135deg, #059669 0%, #047857 100%) !important;
}

.direct-btn {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;
    color: white !important;
    max-width: 280px !important;
    flex: 1 !important;
}

.direct-btn:hover {
    background: linear-gradient(135deg, #d97706 0%, #b45309 100%) !important;
}

.btn-icon {
    font-size: 28px !important;
    min-width: 45px !important;
    width: 45px !important;
    height: 45px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    background: rgba(255,255,255,0.15) !important;
    border-radius: 12px !important;
    flex-shrink: 0 !important;
}

.btn-content {
    flex: 1 !important;
    display: flex !important;
    flex-direction: column !important;
    align-items: flex-start !important;
    text-align: left !important;
    gap: 2px !important;
}

.btn-text {
    flex: 1 !important;
    display: flex !important;
    flex-direction: column !important;
    align-items: flex-start !important;
    text-align: left !important;
    gap: 2px !important;
}

.btn-title {
    font-size: 17px !important;
    font-weight: 700 !important;
    line-height: 1.2 !important;
    margin: 0 !important;
    margin-bottom: 2px !important;
}

.btn-subtitle {
    font-size: 13px !important;
    opacity: 0.9 !important;
    font-weight: 500 !important;
    line-height: 1.2 !important;
    margin: 0 !important;
}

.checkout-notice {
    color: #64748b !important;
    font-size: 14px !important;
    font-style: italic !important;
    margin-top: 15px !important;
}

/* Loading States */
.checkout-form-with-invoice.loading .invoice-btn {
    background: #9ca3af !important;
    cursor: not-allowed !important;
    transform: none !important;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
}

.checkout-form-with-invoice.loading .btn-title::after {
    content: " ⏳" !important;
}

/* Responsive */
@media (max-width: 768px) {
   .cart_totals {
       padding: 20px;
       margin: 30px 0;
   }
   
   .cart_totals h2 {
       font-size: 1.5rem;
   }
   
   .cart_totals .shop_table th,
   .cart_totals .shop_table td {
       padding: 12px 15px;
       font-size: 14px;
   }
   
   .cart_totals .order-total th,
   .cart_totals .order-total td {
       font-size: 18px;
       padding: 15px;
   }
   
   .checkout-button {
       padding: 15px 30px;
       font-size: 16px;
       width: 100%;
       max-width: 300px;
   }
   
   .payment-methods {
       grid-template-columns: 1fr;
   }
   
   .invoice-actions {
       flex-direction: column;
       align-items: center;
   }
   
   .checkout-button-final,
   .resend-invoice {
       width: 100%;
       max-width: 250px;
   }
   
   .login-button,
   .register-button {
       display: block;
       margin: 10px auto;
       width: 100%;
       max-width: 200px;
   }
   
   .checkout-buttons-wrapper {
        flex-direction: column !important;
        gap: 15px !important;
        align-items: center !important;
    }
    
    .checkout-form-with-invoice,
    .direct-btn {
        max-width: 100% !important;
        width: 100% !important;
    }
    
    .checkout-btn {
        height: 85px !important;
        min-height: 85px !important;
        max-height: 85px !important;
        gap: 18px !important;
        padding-left: 20px !important;
    }
}

@media (max-width: 480px) {
   .cart_totals .shop_table th {
       width: 50%;
   }
   
   .payment-method {
       justify-content: center;
       flex-direction: column;
       text-align: center;
       gap: 5px;
   }
   
   .customer-info-preview {
       padding: 15px;
   }
   
   .invoice-details {
       text-align: center;
   }
   
   .checkout-btn {
        height: 80px !important;
        min-height: 80px !important;
        max-height: 80px !important;
        gap: 15px !important;
        padding-left: 18px !important;
    }
    
    .btn-icon {
        font-size: 24px !important;
        min-width: 38px !important;
        width: 38px !important;
        height: 38px !important;
    }
    
    .btn-title {
        font-size: 15px !important;
    }
    
    .btn-subtitle {
        font-size: 11px !important;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
   // Handle checkout form submission
   $('.checkout-form-with-invoice').on('submit', function(e) {
       const $form = $(this);
       const $button = $form.find('.checkout-button');
       
       // Show loading state
       $form.addClass('loading');
       $button.prop('disabled', true);
       
       // Re-enable after 10 seconds to prevent permanent disable
       setTimeout(function() {
           $form.removeClass('loading');
           $button.prop('disabled', false);
       }, 10000);
   });
   
   // Handle resend invoice
   $('.resend-invoice').on('click', function(e) {
       e.preventDefault();
       
       const $btn = $(this);
       const originalText = $btn.text();
       const invoiceNumber = $btn.data('invoice');
       
       $btn.prop('disabled', true).text('⏳ Đang gửi...');
       
       $.ajax({
           url: phonestore_ajax.ajax_url,
           type: 'POST',
           data: {
               action: 'phonestore_resend_invoice',
               invoice_number: invoiceNumber,
               nonce: phonestore_ajax.nonce
           },
           success: function(response) {
               if (response.success) {
                   alert('✅ Đã gửi lại hóa đơn thành công!');
               } else {
                   alert('❌ Có lỗi xảy ra: ' + (response.data || 'Vui lòng thử lại'));
               }
           },
           error: function() {
               alert('❌ Có lỗi kết nối. Vui lòng thử lại.');
           },
           complete: function() {
               $btn.prop('disabled', false).text(originalText);
           }
       });
   });
   
   // Validate user info before checkout
   $('.checkout-form-with-invoice').on('submit', function(e) {
       const customerEmail = $('.customer-email').text().trim();
       
       if (!customerEmail || customerEmail === '📧') {
           e.preventDefault();
           alert('❌ Vui lòng cập nhật thông tin email trong tài khoản của bạn trước khi tiếp tục.');
           
           // Open account page in new tab
           window.open($('.edit-info-link a').attr('href'), '_blank');
           return false;
       }
   });
   
   // Auto-clear pending invoice after 30 minutes
   if ($('.pending-invoice-notice').length > 0) {
       setTimeout(function() {
           $('.pending-invoice-notice').fadeOut(500, function() {
               $(this).remove();
           });
       }, 30 * 60 * 1000); // 30 minutes
   }
});
</script>

<?php
// Clear pending invoice session after display
if (WC()->session->get('pending_invoice')) {
   // Keep it for 30 minutes then clear
   $pending_invoice = WC()->session->get('pending_invoice');
   if (isset($pending_invoice['sent_time'])) {
       $sent_time = strtotime($pending_invoice['sent_time']);
       if (time() - $sent_time > 1800) { // 30 minutes
           WC()->session->__unset('pending_invoice');
       }
   }
}
?>