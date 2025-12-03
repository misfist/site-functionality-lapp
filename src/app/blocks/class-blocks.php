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

		// add_filter( 'block_categories_all', array( $this, 'register_block_category' ), 10, 2 );
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
			)
		);
		return $block_categories;
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
