<?php

namespace AWOF;

class Front {

	public function init(): void {

		if ( 'replace' === get_option( 'woocommerce_awof_mode' ) ) {
			remove_action( 'woocommerce_proceed_to_checkout', 'woocommerce_button_proceed_to_checkout', 20 );
			remove_action( 'woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_proceed_to_checkout', 20 );
		}

		add_action( 'woocommerce_proceed_to_checkout', [ $this, 'in_cart_page' ], 30 );
		add_action( 'woocommerce_widget_shopping_cart_after_buttons', [ $this, 'in_mini_cart' ], 30 );

	}


	public function in_cart_page(): void {

		$this->render();

	}


	public function in_mini_cart(): void {

		$this->render();
	}


	public function render( $args = [] ): void {

		load_template(
			awof()->get_template( 'form.php' ),
			true,
			$args
		);

	}

}