<?php
/**
 * Файл обработки скриптов и стилей
 *
 * @see     https://wpruse.ru
 * @package art-woocommerce-fast-order/classes
 * @version 1.0.0
 */

namespace AWOF;

class Enqueue {

	private string $suffix;


	public function __construct() {

		$this->suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	}


	public function init(): void {

		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_script_style' ], 100 );

	}


	/**
	 * Подключаем нужные стили и скрипты
	 */
	public function enqueue_script_style(): void {

		wp_register_script(
			'awof-input-mask',
			AWOF_PLUGIN_URI . 'assets/js/jquery.mask' . $this->get_suffix() . '.js',
			[ 'jquery' ],
			AWOF_PLUGIN_VER,
			true
		);
		wp_register_script(
			'awof-scripts',
			AWOF_PLUGIN_URI . 'assets/js/awof-scripts' . $this->get_suffix() . '.js',
			[ 'jquery', 'woocommerce', 'jquery-blockui', 'awof-input-mask' ],
			AWOF_PLUGIN_VER,
			true
		);

		wp_register_style(
			'awof-styles',
			AWOF_PLUGIN_URI . 'assets/css/awof-styles' . $this->get_suffix() . '.css',
			[],
			AWOF_PLUGIN_VER
		);

		wp_localize_script(
			'awof-scripts',
			'awof_scripts',
			[
				'url'       => admin_url( 'admin-ajax.php' ),
				'setting'   => apply_filters( 'awof_localize_settings', [
					'timeout_success' => get_option( 'woocommerce_awof_delay_success', 2000 ),
					'timeout_error'   => get_option( 'woocommerce_awof_delay_error', 5000 ),
					'mask'            => get_option( 'woocommerce_awof_phone_mask', '+7 (999) 999-99-99' ),
				] ),
				'translate' => [
					'empty_field' => __( 'Empty field', 'art-woocommerce-order-fast' ),
				],
			]
		);

		wp_enqueue_script( 'awof-scripts' );
		wp_enqueue_script( 'awof-input-mask' );
		wp_enqueue_style( 'awof-styles' );
	}


	/**
	 * @return string
	 */
	public function get_suffix(): string {

		return $this->suffix;
	}

}
