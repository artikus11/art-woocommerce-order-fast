<?php
/**
 * @see     https://wpruse.ru
 * @package art-woocommerce-fast-order/classes
 * @version 1.0.0
 */

namespace AWOF;

class Main {

	/**
	 *
	 * @since  1.0.0
	 * @access private
	 */
	private static ?Main $instance = null;

	/**
	 * @var \AWOF\Templater
	 */
	private Templater $template;


	private function __construct() {

		$this->init();
		$this->hooks();
		$this->load_textdomain();

	}


	public function init(): void {

		( new Requirements( $this ) )->init();
		( new Enqueue() )->init();
		( new Front() )->init();
		( new Rest() )->init();

		$this->template = new Templater();

	}


	public function hooks(): void {

		add_filter( 'plugin_action_links_' . AWOF_PLUGIN_FILE, [ $this, 'add_plugin_action_links' ], 10, 1 );
		add_filter( 'woocommerce_get_settings_pages', [ $this, 'add_settings' ], 15 );

	}


	public function add_settings( $settings ) {

		$settings[] = include __DIR__ . '/class-settings.php';

		return $settings;
	}


	public function add_plugin_action_links( $links ): array {

		$plugin_links = [
			'settings' => sprintf(
				'<a href="%s">%s</a>',
				esc_url( admin_url( 'admin.php?page=wc-settings&tab=awof_settings' ) ),
				esc_html__( 'Settings', 'art-woocommerce-order-fast' )
			),
		];

		return array_merge( $plugin_links, $links );

	}
	/**
	 * Textdomain.
	 * Load the textdomain based on WP language.
	 *
	 * @since 1.0.0
	 */
	public function load_textdomain(): void {

		load_plugin_textdomain(
			'art-woocommerce-dadata-integrations',
			false,
			dirname( AWDI_PLUGIN_FILE ) . '/languages/'
		);

	}

	/**
	 * Instance.
	 * A global instance of the class. Used to retrieve the instance
	 * to use on other files/plugins/themes.
	 *
	 * @return object Instance of the class.
	 * @since 1.0.0
	 */
	public static function instance() {

		if ( is_null( self::$instance ) ) :
			self::$instance = new self();
		endif;

		return self::$instance;

	}


	/**
	 * @param $template_name
	 *
	 * @return string
	 */
	public function get_template( $template_name ): string {

		return $this->template->get_template( $template_name );
	}

}