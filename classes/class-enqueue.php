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
			'awof-inputmask',
			AWOF_PLUGIN_URI . 'assets/js/jquery.inputmask.min.js',
			[ 'jquery'],
			AWOF_PLUGIN_VER,
			false
		);
		wp_register_script(
			'awof-scripts',
			AWOF_PLUGIN_URI . 'assets/js/scripts' . $this->get_suffix() . '.js',
			[ 'jquery', 'woocommerce', 'jquery-blockui', 'awof-inputmask' ],
			AWOF_PLUGIN_VER,
			false
		);

		wp_register_style(
			'awof-styles',
			AWOF_PLUGIN_URI . 'assets/css/styles' . $this->get_suffix() . '.css',
			[],
			AWOF_PLUGIN_VER
		);

		wp_localize_script(
			'awof-scripts',
			'awof_scripts',
			[
				'url'       => admin_url( 'admin-ajax.php' ),
				'setting'   => [
					'timeout_success' => 2000,
					'timeout_error' => 5000,
					'mask' => '+7 (999) 999-99-99',
				],
				'translate' => [
					'empty_field' => __( 'Empty field', 'art-woocommerce-order-fast' ),
				],
			]
		);

		wp_enqueue_script( 'awof-scripts' );
		wp_enqueue_script( 'awof-inputmask' );
		wp_enqueue_style( 'awof-styles' );
	}


	/**
	 * @return string
	 */
	public function get_suffix(): string {

		return $this->suffix;
	}

}
