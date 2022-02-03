<?php
/**
 * Plugin Name: Art WooCommerce Fast Order
 * Plugin URI: wpruse.ru
 * Text Domain: art-woocommerce-order-fast
 * Domain Path: /languages
 * Description: Plugin for WooCommerce. Quick order of products which are currently in the cart
 * Version: 1.0.0
 * Author: Artem Abramovich
 * Author URI: https://wpruse.ru/
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * RequiresWP: 5.5
 * RequiresPHP: 7.4
 * WC requires at least: 5.2.0
 * WC tested up to: 6.1
 *
 * Copyright Artem Abramovich
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$plugin_data = get_file_data(
	__FILE__,
	array(
		'ver'  => 'Version',
		'name' => 'Plugin Name',
	)
);

const AWOF_PLUGIN_DIR = __DIR__;
define( 'AWOF_PLUGIN_URI', plugin_dir_url( __FILE__ ) );
define( 'AWOF_PLUGIN_FILE', plugin_basename( __FILE__ ) );

define( 'AWOF_PLUGIN_VER', $plugin_data['ver'] );
define( 'AWOF_PLUGIN_NAME', $plugin_data['name'] );

require AWOF_PLUGIN_DIR . '/vendor/autoload.php';

register_uninstall_hook( __FILE__, array( 'AWOOC', 'uninstall' ) );

if ( ! function_exists( 'awof' ) ) {
	/**
	 *
	 * @return object AWOF class object.
	 * @since 1.0.0
	 */
	function awof(): object {

		return AWOF\Main::instance();
	}
}

awof();
