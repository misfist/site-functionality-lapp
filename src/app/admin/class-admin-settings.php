<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/misfist/site-functionality
 * @since      1.0.0
 *
 * @package    site-functionality
 */

namespace Site_Functionality\App\Admin;

use Site_Functionality\Common\Abstracts\Base;
use Site_Functionality\Common\WP_Includes\I18n;

/**
 * The admin-specific functionality of the plugin.
 */
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Admin_Settings extends Base {

	/**
	 * Option name
	 *
	 * @var string
	 */
	public static $ad_pattern_option = 'ad_pattern';

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct( $settings ) {
		parent::__construct( $settings );
		$this->init();
	}

	/**
	 * Init
	 *
	 * @return void
	 */
	public function init(): void {
		\add_action( 'acf/init', array( $this, 'acf_settings' ) );
		\add_action( 'acf/include_fields', array( $this, 'register_fields' ) );
		\add_action( 'acf/init', array( $this, 'register_options_page' ) );
	}

	/**
	 * Add fields
	 *
	 * @return void
	 */
	public function register_fields(): void {
		if ( ! function_exists( '\acf_add_local_field_group' ) ) {
			return;
		}

		\acf_add_local_field_group(
			array(
				'key'                   => 'group_6940a282e41b9',
				'title'                 => __( 'Ad Slot Settings', 'site-functionality' ),
				'fields'                => array(
					array(
						'key'                  => 'field_6940a28420a00',
						'label'                => __( 'Select Ad', 'site-functionality' ),
						'name'                 => 'ad_pattern',
						'aria-label'           => '',
						'type'                 => 'post_object',
						'instructions'         => __( 'This ad will be configured for all ad slots.', 'site-functionality' ),
						'required'             => 0,
						'conditional_logic'    => 0,
						'wrapper'              => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'post_type'            => array(
							'wp_block',
						),
						'post_status'          => '',
						'taxonomy'             => '',
						'return_format'        => 'object',
						'multiple'             => 0,
						'allow_null'           => 0,
						'allow_in_bindings'    => 1,
						'bidirectional'        => 0,
						'ui'                   => 1,
						'bidirectional_target' => array(),
					),
					array(
						'key'               => 'field_6940a2de3ee1d',
						'label'             => __( 'Position', 'site-functionality' ),
						'name'              => 'position',
						'aria-label'        => '',
						'type'              => 'range',
						'instructions'      => __( 'Number of paragraphs from top.', 'site-functionality' ),
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'default_value'     => '',
						'min'               => '',
						'max'               => '',
						'allow_in_bindings' => 0,
						'step'              => '',
						'prepend'           => '',
						'append'            => '',
					),
				),
				'location'              => array(
					array(
						array(
							'param'    => 'options_page',
							'operator' => '==',
							'value'    => 'ad-settings',
						),
					),
				),
				'menu_order'            => 0,
				'position'              => 'normal',
				'style'                 => 'seamless',
				'label_placement'       => 'top',
				'instruction_placement' => 'label',
				'hide_on_screen'        => '',
				'active'                => true,
				'description'           => '',
				'show_in_rest'          => 1,
				'display_title'         => '',
			)
		);
	}

	/**
	 * Add Page
	 *
	 * @return void
	 */
	public function register_options_page(): void {
		if ( ! function_exists( '\acf_add_options_page' ) ) {
			return;
		}

		\acf_add_options_page(
			array(
				'page_title'      => esc_html__( 'Ad Settings', 'site-functionality' ),
				'menu_slug'       => 'ad-settings',
				'parent_slug'     => 'options-general.php',
				'menu_title'      => esc_html__( 'Ad Settings', 'site-functionality' ),
				'position'        => 7,
				'redirect'        => false,
				'updated_message' => esc_attr__( 'Settings Updated', 'site-functionality' ),
				'capability'      => 'manage_options',
			)
		);
	}

	/**
	 * Add ACF settings
	 *
	 * @link https://www.advancedcustomfields.com/resources/acf-settings/
	 *
	 * @return void
	 */
	public function acf_settings(): void {
		if ( ! function_exists( '\acf_update_setting' ) ) {
			return;
		}

		\acf_update_setting( 'l10n_textdomain', 'site-functionality' );
	}
}
