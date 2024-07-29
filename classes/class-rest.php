<?php

namespace AWOF;

use WP_Error;
use WP_REST_Request;

class Rest {

	public function init(): void {

		add_action( 'init', [ $this, 'rest_init' ] );
	}


	public function rest_init(): void {

		add_action( 'wp_loaded', [ $this, 'rest_api_includes' ], 5 );
		add_action( 'rest_api_init', [ $this, 'route' ], 100 );
	}


	public function rest_api_includes(): void {

		if ( class_exists( 'Woocommerce' ) && ! defined( 'WP_CLI' ) && empty( WC()->cart ) ) {
			WC()->frontend_includes();
			wc_load_cart();
		}
	}


	public function route(): void {

		register_rest_route(
			'awof/v1',
			'/processing',
			[
				'methods'       => [ 'POST' ],
				'show_in_index' => false,
				'callback'      => [ $this, 'processing' ],
				'args'          => $this->get_args_route(),

				'permission_callback' => '__return_true',
			]
		);
	}


	/**
	 * @param  WP_REST_Request $request
	 *
	 * @return array|\WP_ERROR
	 * @throws \Exception
	 */
	public function processing( WP_REST_Request $request ) {

		$order_id = $this->get_order_id( $request );

		if ( is_wp_error( $order_id ) ) {
			return new WP_Error( 'error'
				, $order_id->get_error_message(),
				[ 'status' => 412 ]
			);
		}

		$order = wc_get_order( $order_id );

		$order->add_order_note( __( 'Created a quick order from the cart', 'art-woocommerce-order-fast' ) );
		$order->update_meta_data( '_awof_quick_order', true );

		$order->calculate_totals();
		$order->update_status( 'on-hold' );

		do_action( 'awof_created_order', $order );

		WC()->cart->empty_cart();

		return [
			'status'    => 200,
			'message'   => apply_filters( 'awof_message_success', __( 'Order successfully created', 'art-woocommerce-order-fast' ) ),
			'url'       => $order->get_checkout_order_received_url(),
			'form_data' => $request->get_params(),
		];
	}


	/**
	 * @param  \WP_REST_Request $request
	 *
	 * @return int|\WP_ERROR
	 * @throws \Exception
	 */
	protected function get_order_id( WP_REST_Request $request ) {

		return WC()->checkout()->create_order(
			apply_filters(
				'awof_order_data',
				[
					'billing_email'      => $request->get_param( 'awof-email' ),
					'billing_first_name' => $request->get_param( 'awof-name' ),
					'payment_method'     => '',
					'billing_phone'      => $request->get_param( 'awof-phone' ),
				]
			)
		);
	}


	/**
	 * @return array
	 */
	protected function get_args_route(): array {

		$fields_options = get_option( 'woocommerce_awof_form_fields', [ 'awof-phone' ] );

		$args = [];

		foreach ( $fields_options as $value ) {
			$args[ $value ] = [
				'default'           => null,
				'type'              => 'string',
				'required'          => true,
				'validate_callback' => function ( $value, $request, $param ) {

					if ( empty( trim( $value ) ) ) {
						return new WP_Error( 'rest_invalid_param', __( 'Empty field', 'art-woocommerce-order-fast' ) );
					}

					return true;
				},
				'sanitize_callback' => 'sanitize_text_field',
			];
		}

		return $args;
	}

}
