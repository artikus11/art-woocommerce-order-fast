<?php

namespace AWOF;

class Front {

	/**
	 *
	 * @return void
	 *
	 * @todo в теме Каденс проблемы, так как тема подменяет фрагмент миникорзины,
	 * как вариант принудительно отключать хук из темы или подменять файлы mini-cart.php и cart-totals.php
	 *
	 */
	public function init(): void {

		if ( 'replace' === get_option( 'woocommerce_awof_mode' ) ) {
			add_action( 'after_setup_theme', [ $this, 'remove' ] );
		}

		add_action( 'woocommerce_proceed_to_checkout', [ $this, 'render' ], 30 );
		add_action( 'woocommerce_widget_shopping_cart_after_buttons', [ $this, 'render' ], 30 );

	}


	public function remove(): void {

		remove_action( 'woocommerce_proceed_to_checkout', 'woocommerce_button_proceed_to_checkout', 20 );
		remove_action( 'woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_proceed_to_checkout', 20 );
	}


	public function render( $args = [] ): void {

		load_template(
			awof()->get_template( 'form.php' ),
			true,
			$args
		);

	}

}