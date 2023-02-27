<?php

namespace AWOF;

class Orders {

	public function init(): void {

		add_filter( 'woocommerce_admin_order_buyer_name', [ $this, 'add_name' ], 10, 2 );

		//TODO - сделать фильтр по быстрым заказам в админке
		//add_filter( 'views_edit-shop_order', array( $this, 'shop_order_views' ) );

	}


	/**
	 * @param            $buyer
	 * @param  \WC_Order $order
	 *
	 * @return mixed|string
	 */
	public function add_name( $buyer, \WC_Order $order ) {

		if ( $order->get_meta( '_awof_quick_order' ) ) {
			$buyer = __( 'Quick Order: ', 'art-woocommerce-order-fast' ) . $order->get_billing_phone();
		}

		return $buyer;
	}


	public function shop_order_views( $views ) {


		$views['awof-quick-order'] = '<a href="edit.php?post_status=wc-on-hold&post_type=shop_order">Быстрый заказ <span class="count">(8)</span></a>';

		return $views;
	}

}