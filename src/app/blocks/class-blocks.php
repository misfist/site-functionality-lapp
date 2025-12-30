<?php
/**
 * Functions to register client-side assets (scripts and stylesheets) for the
 * Gutenberg block.
 *
 * @package site-functionality
 */
namespace Site_Functionality\App\Blocks;

use Site_Functionality\Common\Abstracts\Base;
use Site_Functionality\App\Admin\Admin_Settings;

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
		$this->data['namespace'] = 'site-functionality/v1';
		$this->data['route']     = '/cta-slot';

		add_action( 'rest_api_init', array( $this, 'register_cta_slot_route' ), );

		add_action( 'init', array( $this, 'register_blocks' ) );

		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_block_blackend_scripts' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_block_frontend_scripts' ) );

		add_filter( 'block_categories_all', array( $this, 'register_block_category' ), 10, 2 );
	}

	/**
	 * Register blocks
	 *
	 * @return void
	 */
	public function register_blocks(): void {
		/**
		 * Registers the block(s) metadata from the `blocks-manifest.php` and registers the block type(s)
		 * based on the registered block metadata.
		 * Added in WordPress 6.8 to simplify the block metadata registration process added in WordPress 6.7.
		 *
		 * @see https://make.wordpress.org/core/2025/03/13/more-efficient-block-type-registration-in-6-8/
		 */
		if ( function_exists( 'wp_register_block_types_from_metadata_collection' ) ) {
			wp_register_block_types_from_metadata_collection( __DIR__ . '/build', __DIR__ . '/build/blocks-manifest.php' );
			return;
		}

		/**
		 * Registers the block(s) metadata from the `blocks-manifest.php` file.
		 * Added to WordPress 6.7 to improve the performance of block type registration.
		 *
		 * @see https://make.wordpress.org/core/2024/10/17/new-block-type-registration-apis-to-improve-performance-in-wordpress-6-7/
		 */
		if ( function_exists( 'wp_register_block_metadata_collection' ) ) {
			wp_register_block_metadata_collection( __DIR__ . '/build', __DIR__ . '/build/blocks-manifest.php' );
		}
		/**
		 * Registers the block type(s) in the `blocks-manifest.php` file.
		 *
		 * @see https://developer.wordpress.org/reference/functions/register_block_type/
		 */
		$manifest_data = require __DIR__ . '/build/blocks-manifest.php';
		foreach ( array_keys( $manifest_data ) as $block_type ) {
			register_block_type( __DIR__ . "/build/{$block_type}" );
		}
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
	 * Register Ad Route
	 *
	 * @return void
	 */
	public function register_cta_slot_route(): void {
		register_rest_route(
			$this->data['namespace'],
			$this->data['route'],
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'render_cta_slot' ),
				'permission_callback' => function () {
					return current_user_can( 'edit_posts' );
				},
			)
		);
	}

	/**
	 * Get rendered HTML for the selected ad pattern.
	 *
	 * @return obj \WP_REST_Response
	 */
	public function render_cta_slot(): \WP_REST_Response {
		$option_name    = Admin_Settings::$cta_pattern_option;
		$cta_pattern_id = (int) get_option( 'options_' . $option_name );

		if ( ! $cta_pattern_id ) {
			return rest_ensure_response(
				array(
					'html' => '',
				)
			);
		}

		$cta = get_post( $cta_pattern_id );
		if ( ! $cta || is_wp_error( $cta ) ) {
			return rest_ensure_response(
				array(
					'html' => '',
				)
			);
		}

		return rest_ensure_response(
			array(
				'html' => do_blocks( $cta->post_content ),
			)
		);
	}

	/**
	 * Get sponsors block.
	 *
	 * @param array  $attributes Block attributes.
	 * @param string $content    Block content.
	 * @param object $block      Block instance.
	 * @return string
	 */
	public static function get_sponsors_block( array $attributes, string $content, $block ): string {
		if (
		! function_exists( '\newspack_get_all_sponsors' )
		|| ! function_exists( '\newspack_get_native_sponsors' )
		|| ! function_exists( '\newspack_sponsor_logo_list' )
		|| ! function_exists( '\newspack_sponsor_byline' )
		) {
			return '';
		}

		$object_id = ! empty( $attributes['objectId'] ) ? absint( $attributes['objectId'] ) : 0;
		$context   = ! empty( $attributes['objectType'] ) ? sanitize_key( $attributes['objectType'] ) : 'term';

		if ( ! $object_id ) {
			$object_id = ( 'post' === $context ) ? get_the_ID() : get_queried_object_id();
		}

		if ( ! $object_id ) {
			return '';
		}

		$all_sponsors = \newspack_get_all_sponsors( $object_id );
		$sponsors     = \newspack_get_native_sponsors( $all_sponsors );

		if ( empty( $sponsors ) || ! is_array( $sponsors ) ) {
			return '';
		}

		ob_start();
		?>
			<div class="entry-meta entry-sponsor">
				<?php \newspack_sponsor_logo_list( $sponsors ); ?>
				<span class="sponsor-byline"><?php \newspack_sponsor_byline( $sponsors ); ?></span>
			</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Render sponsor block
	 *
	 * @param array  $attributes Block attributes.
	 * @param string $content    Block content.
	 * @param object $block      Block instance.
	 * @return string
	 */
	public static function render_sponsors_block( array $attributes, string $content, $block ): void {
		$html = self::get_sponsors_block( $attributes, $content, $block );
		echo $html;
	}

	/**
	 * Enqueue blocks scripts
	 *
	 * @return void
	 */
	public function enqueue_block_blackend_scripts(): void {
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

		wp_add_inline_style(
			'site-functionality',
			'.is-hierarchical-post-tags .editor-post-taxonomies__hierarchical-terms-add{display:none;pointer-events:none;}'
		);
	}

	/**
	 * Enqueue blocks scripts
	 *
	 * @return void
	 */
	public function enqueue_block_frontend_scripts(): void {
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

		wp_enqueue_style(
			'site-functionality-styles',
			\plugins_url( '/build/style-index.css', __FILE__ ),
			array(),
			$asset_file['version'],
			'screen'
		);
	}
}
