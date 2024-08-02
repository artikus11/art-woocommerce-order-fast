<?php
/**
 * Настройки
 *
 * @see     https://wpruse.ru
 * @package art-woocommerce-fast-order/classes
 * @version 1.0.0
 */

namespace AWOF;

use WC_Admin_Settings;
use WC_Settings_Page;

/**
 * Class Settings
 *
 * @author Artem Abramovich
 * @since  1.8.0
 *
 */
class Settings extends WC_Settings_Page {

	/**
	 * Constructor.
	 *
	 * @since 1.8.0
	 */
	public function __construct() {

		$this->id    = 'awof_settings';
		$this->label = __( 'Fast Order', 'art-woocommerce-order-fast' );

		parent::__construct();
	}


	/**
	 * Обработка селекта Да/Нет
	 *
	 * @return array
	 *
	 * @since 2.0.0
	 */
	public static function select_on_off(): array {

		return [
			'off' => __( 'Off', 'art-woocommerce-order-fast' ),
			'on'  => __( 'On', 'art-woocommerce-order-fast' ),
		];
	}


	/**
	 * Отдельная секция во вкладке
	 *
	 * @return array|mixed|void
	 */
	public function get_sections() {

		$sections = apply_filters(
			'awof_settings_sections',
			[
				'' => __( 'General', 'art-woocommerce-order-fast' ),
			]
		);

		return apply_filters( 'woocommerce_get_sections_' . $this->id, $sections );
	}


	/**
	 * Output the settings.
	 */
	public function output() {

		global $current_section;

		$settings = $this->get_settings( $current_section );

		WC_Admin_Settings::output_fields( $settings );
	}


	/**
	 * Настройки
	 *
	 * @param  string $current_section название входящей секции.
	 *
	 * @return array|mixed|void
	 *
	 * @since 1.0.0
	 * @since 1.2.2
	 */
	public function get_settings( string $current_section = '' ) {

		$settings = apply_filters(
			'awof_settings_section_main',
			[
				[
					'name' => __( 'General settings', 'art-woocommerce-order-fast' ),
					'type' => 'title',
					'id'   => 'woocommerce_awof_settings',
				],

				[
					'title'    => __( 'Operating mode', 'art-woocommerce-order-fast' ),
					'desc'     => __( 'Select the mode of operation and display the Buy button', 'art-woocommerce-order-fast' ),
					'id'       => 'woocommerce_awof_mode',
					'default'  => 'add',
					'type'     => 'radio',
					'desc_tip' => true,
					'options'  => self::select_mode(),
					'autoload' => false,
				],

				[
					'type' => 'sectionend',
					'id'   => 'woocommerce_awof_settings',
				],

				[
					'name' => __( 'Form settings', 'art-woocommerce-order-fast' ),
					'type' => 'title',
					'id'   => 'woocommerce_awof_settings',
				],
				[
					'title'    => __( 'Form fields', 'art-woocommerce-order-fast' ),
					'desc'     => __( 'Select the required fields', 'art-woocommerce-order-fast' ),
					'id'       => 'woocommerce_awof_form_fields',
					'css'      => 'min-width:350px;',
					'class'    => 'wc-enhanced-select',
					'type'     => 'multiselect',
					'default'  => $this->default_form_fields(),
					'options'  => $this->select_form_fields(),
					'desc_tip' => true,
					'autoload' => false,
				],
				[
					'title'    => __( 'Show field labels', 'art-woocommerce-order-fast' ),
					'desc'     => '',
					'id'       => 'woocommerce_awof_show_labels',
					'type'     => 'checkbox',
					'default'  => 'off',
					'desc_tip' => true,
					'autoload' => false,
				],
				[
					'title'    => __( 'Button Label', 'art-woocommerce-order-fast' ),
					'desc'     => __( 'Specify the desired label on the button', 'art-woocommerce-order-fast' ),
					'id'       => 'woocommerce_awof_button',
					'css'      => 'min-width:350px;',
					'default'  => esc_html__( 'Quick Order', 'art-woocommerce-order-fast' ),
					'type'     => 'text',
					'desc_tip' => true,
					'autoload' => false,
				],
				[
					'title'    => __( 'Delay time success, ms', 'art-woocommerce-order-fast' ),
					'desc'     => __( 'Success message display delay time', 'art-woocommerce-order-fast' ),
					'id'       => 'woocommerce_awof_delay_success',
					'css'      => 'min-width:150px;',
					'default'  => 2000,
					'type'     => 'number',
					'desc_tip' => true,
					'autoload' => false,
				],

				[
					'title'    => __( 'Delay time error, ms', 'art-woocommerce-order-fast' ),
					'desc'     => __( 'Error message display delay time', 'art-woocommerce-order-fast' ),
					'id'       => 'woocommerce_awof_delay_error',
					'css'      => 'min-width:150px;',
					'default'  => 5000,
					'type'     => 'number',
					'desc_tip' => true,
					'autoload' => false,
				],

				[
					'title'    => __( 'Phone mask', 'art-woocommerce-order-fast' ),
					'desc'     => __( 'Specify the required phone mask. The script responds to the number "9"', 'art-woocommerce-order-fast' ),
					'id'       => 'woocommerce_awof_phone_mask',
					'css'      => 'min-width:350px;',
					'default'  => '+7 (r00) 000-00-00',
					'type'     => 'text',
					'desc_tip' => true,
					'autoload' => false,
				],

				[
					'title'    => __( 'Phone placeholder', 'art-woocommerce-order-fast' ),
					'desc'     => __( 'Specify a placeholder for the phone field', 'art-woocommerce-order-fast' ),
					'id'       => 'woocommerce_awof_phone_placeholder',
					'css'      => 'min-width:350px;',
					'default'  => '+7 (___) ___-__-__',
					'type'     => 'text',
					'desc_tip' => true,
					'autoload' => false,
				],

				[
					'type' => 'sectionend',
					'id'   => 'woocommerce_awof_settings',
				],

			]
		);

		return apply_filters( 'woocommerce_get_settings_' . $this->id, $settings, $current_section );
	}


	/**
	 * @return array
	 * @since 1.2.2
	 */
	public function select_form_fields(): array {

		return apply_filters(
			'awof_setting_form_fields',
			[
				'awof-name'  => __( 'Name', 'art-woocommerce-order-fast' ),
				'awof-email' => __( 'Email', 'art-woocommerce-order-fast' ),
				'awof-phone' => __( 'Phone', 'art-woocommerce-order-fast' ),
			]
		);
	}


	/**
	 * @return string[]
	 *
	 * @since 1.3.0
	 */
	public function default_form_fields(): array {

		return [
			'awof-phone',
		];
	}


	/**
	 *
	 * @return array
	 *
	 * @since 1.0.0
	 */
	public static function select_mode(): array {

		return apply_filters(
			'awof_select_mode',
			[
				'add'     => __( 'Add fast order button near checkout button', 'art-woocommerce-order-fast' ),
				'replace' => __( 'Replace checkout button with fast order button', 'art-woocommerce-order-fast' ),
			]
		);
	}


	/**
	 * Save settings.
	 */
	public function save() {

		global $current_section;

		$settings = $this->get_settings( $current_section );

		WC_Admin_Settings::save_fields( $settings );

		if ( $current_section ) {
			do_action( 'woocommerce_update_options_' . $this->id . '_' . $current_section );
		}
	}

}


new Settings();
