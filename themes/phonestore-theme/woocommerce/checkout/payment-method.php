<?php
/**
 * Output a single payment method
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/payment-method.php.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Check if $gateway is set and is an object
if ( ! isset( $gateway ) || ! is_object( $gateway ) ) {
    return;
}
?>
<li class="wc_payment_method payment_method_<?php echo esc_attr( $gateway->id ); ?>">
	<input id="payment_method_<?php echo esc_attr( $gateway->id ); ?>" type="radio" class="input-radio" name="payment_method" value="<?php echo esc_attr( $gateway->id ); ?>" <?php checked( $gateway->chosen, true ); ?> data-order_button_text="<?php echo esc_attr( $gateway->order_button_text ); ?>" />

	<label for="payment_method_<?php echo esc_attr( $gateway->id ); ?>">
		<?php 
		// Thêm icon cho các payment method phổ biến
		$payment_icons = array(
			'bacs' => '🏦',
			'cod' => '💵',
			'cheque' => '📝',
			'paypal' => '🔷',
			'stripe' => '💳',
			'momo' => '📱',
			'zalopay' => '🔵'
		);
		
		$icon = isset($payment_icons[$gateway->id]) ? $payment_icons[$gateway->id] . ' ' : '💳 ';
		echo esc_html( $icon );
		?>
		
		<?php echo wp_kses_post( $gateway->get_title() ); ?> 
		<?php echo wp_kses_post( $gateway->get_icon() ); ?>
	</label>
	<?php if ( $gateway->has_fields() || $gateway->get_description() ) : ?>
		<div class="payment_box payment_method_<?php echo esc_attr( $gateway->id ); ?>" <?php if ( ! $gateway->chosen ) : ?>style="display:none;"<?php endif; ?>>
			<?php $gateway->payment_fields(); ?>
		</div>
	<?php endif; ?>
</li>