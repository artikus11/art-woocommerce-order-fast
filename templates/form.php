<?php
/**
 * Form Template
 *
 * This template can be overridden by copying it to yourtheme/art-woocommerce-order-fast/form.php.
 *
 * @see     https://wpruse.ru/my-plugins/art-woocommerce-order-fast/
 * @package art-woocommerce-order-fast/templates
 * @version 1.0.0
 *
 * @global $args
 */

?>

<form action="<?php echo rest_url( 'awof/v1/processing' ); ?>" id="awof-form" class="awof-form">

	<?php woocommerce_form_field( 'awof_phone', [
		'type'        => 'tel',
		'label'       => '',
		'required'    => true,
		'placeholder' => get_option( 'woocommerce_awof_phone_placeholder', '+7 (___) ___-__-__' ),
		'class'       => [ 'awof-phone', 'awof-phone-cart' ],
		'input_class' => [ 'awof-phone-input' ],
	] ); ?>

	<button
		type="submit"
		class="checkout-button button alt wc-forward awof-button">
		<?php echo esc_html(
			get_option( 'woocommerce_awof_button', __( 'Quick Order', 'art-woocommerce-order-fast' ) )
		) ?></button>

	<?php wp_nonce_field( 'wp_rest' ); ?>
</form>
