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
			'awof-scripts',
			AWOF_PLUGIN_URI . 'assets/js/scripts' . $this->get_suffix() . '.js',
			[ 'jquery' ],
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
			'awof_scripts_ajax',
			[
				'url' => admin_url( 'admin-ajax.php' ),
			]
		);

		wp_enqueue_script( 'awooc-scripts' );
		wp_enqueue_style( 'awooc-styles' );
	}


	/**
	 * @return string
	 */
	public function get_suffix(): string {

		return $this->suffix;
	}

}
