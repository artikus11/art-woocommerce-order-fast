<?php
/**
 * Удаление настроек и меты при удалении плагина
 *
 * @see     https://wpruse.ru/my-plugins/art-woocommerce-order-one-click/
 * @package art-woocommerce-order-one-click/classes
 * @version 3.0.0
 */

namespace AWOF;

class Uninstall {

	/**
	 * Deleting settings when uninstalling the plugin
	 *
	 * @since 2.0.0
	 */
	public static function uninstall(): void {

		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		self::remove_options();
		self::remove_post_meta();
	}


	protected static function remove_options(): void {

		global $wpdb;

		$wpdb->query(
			$wpdb->prepare(
				"DELETE FROM $wpdb->options WHERE option_name LIKE %s",
				sprintf( '%s%s', $wpdb->esc_like( 'woocommerce_awof_' ), '%' )
			)
		);
	}


	public static function remove_post_meta(): void {

		global $wpdb;

		$wpdb->query(
			$wpdb->prepare(
				"DELETE FROM $wpdb->postmeta WHERE meta_key LIKE %s",
				sprintf( '%s%s', $wpdb->esc_like( '_awof_' ), '%' )
			)
		);
	}

}