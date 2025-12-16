<?php
/**
 * Functions to register client-side assets (scripts and stylesheets) for the
 * Gutenberg block.
 *
 * @package site-functionality
 */
namespace Site_Functionality\App\Blocks;

use Site_Functionality\Common\Abstracts\Base;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Blocks extends Base {

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
		add_action( 'init', array( $this, 'register_blocks' ) );

		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_blocks_scripts' ) );

		add_action( 'init', array( $this, 'register_pattern_category' ) );

		add_filter( 'block_categories_all', array( $this, 'register_block_category' ), 10, 2 );

	}

	/**
	 * Registers blocks using metadata from `block.json`.
	 *
	 * @return void
	 */
	public function register_blocks(): void {
		register_block_type_from_metadata( __DIR__ . '/build/block' );
	}

	/**
	 * Register block patterns
	 *
	 * @return void
	 */
	public function register_block_patterns(): void {}

	/**
	 * Set script translations
	 *
	 * @return void
	 */
	public function set_script_translations(): void {
		wp_set_script_translations( 'site-functionality', 'site-functionality' );
	}

	/**
	 * Register block category
	 *
	 * @param array  $block_categories
	 * @param object $block_editor_context instance of WP_Block_Editor_Context
	 * @return array $block_categories
	 */
	public function register_block_category( array $block_categories, object $block_editor_context ): array {
		array_unshift(
			$block_categories,
			array(
				'slug'  => 'lapp',
				'title' => esc_html__( 'LA Public Press', 'site-functionality' ),
				'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2" viewBox="0 0 270 270"><path d="M0 0h270v270H0z" style="fill:#1b1411"/><text x="-103.869" style="font-family:&quot;Georgia-BoldItalic&quot;,&quot;Georgia&quot;,serif;font-weight:700;font-style:italic;font-size:140px;fill:#fff" transform="translate(132.2 155)">L<tspan x="-2.293" y="0">A</tspan></text><path d="M0 220h45v50H0z" style="fill:#c7df23"/><path d="M45 220h45v50H45z" style="fill:#00b2a9"/><path d="M90 220h45v50H90z" style="fill:#00915a"/><path d="M135 220h45v50h-45z" style="fill:#ffc300"/><path d="M180 220h45v50h-45z" style="fill:#ff6a2e"/><path d="M225 220h45v50h-45z" style="fill:#e6360a"/></svg>',
			)
		);
		return $block_categories;
	}

	/**
	 * Register Pattern Category
	 *
	 * @return void
	 */
	public function register_pattern_category(): void {
		register_block_pattern_category(
			'ads',
			array(
				'label'       => __( 'Ads', 'site-functionality' ),
				'description' => __( 'Ad patterns', 'site-functionality' ),
			)
		);
	}
	/**
	 * Enqueue blocks scripts
	 *
	 * @return void
	 */
	public function enqueue_blocks_scripts(): void {
		$asset_file_path = \plugin_dir_path( __FILE__ ) . 'build/index.asset.php';
		$version         = $this->settings->get_plugin_version();

		if ( is_readable( $asset_file_path ) ) {
			$asset_file = include $asset_file_path;
		} else {
			$asset_file = array(
				'version'      => $version,
				'dependencies' => array(
					'wp-blocks',
					'wp-components',
					'wp-data',
					'wp-edit-blocks',
					'wp-editor',
					'wp-element',
					'wp-i18n',
					'wp-plugins',
				),
			);
		}

		\wp_enqueue_script(
			'site-functionality',
			\plugins_url( '/build/index.js', __FILE__ ),
			$asset_file['dependencies'],
			$asset_file['version'],
			false
		);
		wp_enqueue_style(
			'site-functionality',
			\plugins_url( '/build/index.css', __FILE__ ),
			array(),
			$asset_file['version'],
			'screen'
		);
	}
}
