<form class="awof-form">

	<?php woocommerce_form_field( 'awof_phone', [
		'type'        => 'tel',
		'label'       => '',
		'placeholder' => '+7 (999) 999-99-99',
		'class'       => [ 'awof-phone', 'awof-phone-cart' ],
		'input_class'       => [ 'awof-phone-input'],
	] ); ?>

	<button
		type="button"
		class="checkout-button button alt wc-forward awof-button"><?php echo esc_html(get_option( 'woocommerce_awof_button', __( 'Quick Order', 'art-woocommerce-order-fast' ) ) ) ?></button>
	<?php wp_nonce_field( 'awof-cart-action', 'awof-cart-nonce' ); ?>
</form>
