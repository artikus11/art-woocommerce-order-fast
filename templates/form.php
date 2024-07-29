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
 * @var $args
 */

$fields = $args['fields'];

?>

<form action="<?php echo rest_url( 'awof/v1/processing' ); ?>" id="awof-form" class="awof-form">

	<?php
	foreach ( $fields as $field_key => $field_value ):
		woocommerce_form_field( $field_key, $field_value );
	endforeach;
	?>

	<button
		type="submit"
		class="checkout-button button alt wc-forward awof-button">
		<?php echo esc_html(
			get_option( 'woocommerce_awof_button', __( 'Quick Order', 'art-woocommerce-order-fast' ) )
		) ?></button>

	<?php wp_nonce_field( 'wp_rest' ); ?>
</form>
