<?php

namespace AWOF;

class Front {

	private Main $main;


	public function __construct( Main $main ) {

		$this->main = $main;
	}


	/**
	 *
	 * @return void
	 *
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

		remove_action(
			'woocommerce_proceed_to_checkout',
			'woocommerce_button_proceed_to_checkout',
			20
		);
		remove_action(
			'woocommerce_widget_shopping_cart_buttons',
			'woocommerce_widget_shopping_cart_proceed_to_checkout',
			20
		);
	}


	/**
	 * @return void
	 *
	 * @sicne 1.0.0
	 * @sicne 1.3.0
	 */
	public function render(): void {

		load_template(
			$this->main->get_template( 'form.php' ),
			true,
			[
				'fields' => $this->get_fields(),
			]
		);
	}


	/**
	 * @return array
	 *
	 * @sicne 1.3.0
	 */
	protected function get_fields(): array {

		$fields_options = get_option( 'woocommerce_awof_form_fields', [ 'awof-phone' ] );
		$fields_label   = get_option( 'woocommerce_awof_show_labels' );

		$fields_default = [
			'awof-name'  => [
				'type'        => 'text',
				'label'       => $fields_label ? __( 'Name', 'art-woocommerce-order-fast' ) : '',
				'required'    => true,
				'placeholder' => __( 'Name', 'art-woocommerce-order-fast' ),
				'class'       => [ 'awof-name', 'awof-name-cart' ],
				'input_class' => [ 'awof-name-input' ],
			],
			'awof-email' => [
				'type'        => 'email',
				'label'       => $fields_label ? __( 'Email', 'art-woocommerce-order-fast' ) : '',
				'required'    => true,
				'placeholder' => __( 'Email', 'art-woocommerce-order-fast' ),
				'class'       => [ 'awof-email', 'awof-email-cart' ],
				'input_class' => [ 'awof-email-input' ],
			],
			'awof-phone' => [
				'type'        => 'tel',
				'label'       => $fields_label ? __( 'Phone', 'art-woocommerce-order-fast' ) : '',
				'required'    => true,
				'placeholder' => get_option( 'woocommerce_awof_phone_placeholder', '+7 (___) ___-__-__' ),
				'class'       => [ 'awof-phone', 'awof-phone-cart' ],
				'input_class' => [ 'awof-phone-input' ],
			],
		];

		$fields = [];

		foreach ( $fields_default as $field_key => $field_args ) {
			if ( in_array( $field_key, $fields_options, true ) ) {
				$fields[ $field_key ] = $field_args;
			}
		}

		return $fields;
	}

}