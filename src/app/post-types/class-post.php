<?php
/**
 * Content Post_Types
 *
 * @since   1.0.0
 * @package Site_Functionality
 */
namespace Site_Functionality\App\Post_Types;

use Site_Functionality\Common\Abstracts\Base;
use Site_Functionality\App\Admin\Admin_Settings;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Post extends Base {

	/**
	 * Post_Type data
	 */
	public const POST_TYPE = array(
		'id' => 'post',
	);

	/**
	 * CTA block name.
	 *
	 * @var string
	 */
	public static $cta_block_name = 'site-functionality/cta-slot';

	/**
	 * Option name
	 *
	 * @var string
	 */
	public static $cta_option_id = 'options_cta_pattern';

	/**
	 * Option name
	 *
	 * @var string
	 */
	public static $cta_position_id = 'options_cta_position';

	/**
	 * Block names
	 *
	 * @var array
	 */
	public static $qualifying_blocks = array(
		'core/paragraph',
		'core/group',
		'core/list',
		'core/image',
	);

	/**
	 * Init
	 *
	 * @return void
	 */
	public function init(): void {
		parent::init();

		add_action( 'init', array( $this, 'register_template' ), 20 );

		add_filter( 'the_content', array( $this, 'cta_before_content' ), 1 );

		add_filter( 'the_content', array( $this, 'cta_after_content' ), 1 );

		add_filter( 'the_content', array( $this, 'cta_inline_content' ), 8 );
	}

	/**
	 * Add default block template for posts.
	 *
	 * @return void
	 */
	public function register_template(): void {
		$post_type = get_post_type_object( self::POST_TYPE['id'] );

		if ( ! $post_type ) {
			return;
		}

		$template = array(
			array(
				'core/paragraph',
				array(
					'placeholder' => esc_html__( 'Add content', 'site-functionality' ),
				),
			),
			array(
				'site-functionality/cta-slot',
				array(
					'lock' => array(
						'move'   => false,
						'remove' => true,
					),
				),
			),
		);

		$post_type->template = $template;
	}

	/**
	 * Add CTA
	 *
	 * @param string $content
	 *
	 * @return string $content
	 */
	public function cta_before_content( $content ) {
		if ( ! is_admin() &&
			! wp_is_json_request() &&
			! is_feed() &&
			is_singular( self::POST_TYPE['id'] ) &&
			in_the_loop() &&
			is_main_query()
		) {
			static $did_run = false;

			if ( $did_run ) {
				return $content;
			}

			$option_name    = self::$cta_option_id;
			$cta_pattern_id = get_option( $option_name . '_before_content' );

			if ( ! $cta_pattern_id ) {
				return $content;
			}

			$cta = get_post( (int) $cta_pattern_id );
			if ( ! $cta || is_wp_error( $cta ) ) {
				return $content;
			}

			$cta_content = $cta->post_content;

			$block_content = sprintf(
				'
				<div class="wp-block-site-functionality-cta-slot%s" id="cta-slot-%s">%s</div>',
				' before-content alignfull',
				uniqid(),
				do_blocks( $cta_content )
			);

			$did_run = true;

			return $block_content . $content;
		}
		return $content;
	}

	/**
	 * Add CTA
	 *
	 * @param string $content
	 *
	 * @return string $content
	 */
	public function cta_after_content( $content ): string {
		if ( ! is_admin() &&
			! wp_is_json_request() &&
			! is_feed() &&
			is_singular( self::POST_TYPE['id'] ) &&
			in_the_loop() &&
			is_main_query()
		) {
			static $did_run = false;

			if ( $did_run ) {
				return $content;
			}

			$option_name    = self::$cta_option_id;
			$cta_pattern_id = get_option( $option_name . '_after_content' );

			if ( ! $cta_pattern_id ) {
				return $content;
			}

			$cta = get_post( (int) $cta_pattern_id );
			if ( ! $cta || is_wp_error( $cta ) ) {
				return $content;
			}

			$cta_content = $cta->post_content;

			$block_content = sprintf(
				'
				<div class="wp-block-site-functionality-cta-slot%s" id="cta-slot-%s">%s</div>',
				' after-content alignfull',
				uniqid(),
				do_blocks( $cta_content )
			);

			$did_run = true;

			return $content . $block_content;
		}
		return $content;
	}

	/**
	 * Inject the selected synced pattern after N qualifying top-level blocks.
	 *
	 * NOTE: This runs on the `the_content` filter *before* `do_blocks` (priority < 9).
	 *
	 * At this point, `$content` contains raw serialized block markup
	 * (`<!-- wp:... -->`), not rendered HTML. `WP_Block_Processor` operates
	 * on this markup to calculate an exact byte offset for insertion.
	 *
	 * The injected `core/block` reference is rendered later when `do_blocks`
	 * runs at priority 9.
	 *
	 * @param string $content Post content.
	 * @return string $content Post content.
	 */
	public function cta_inline_content( string $content ): string {
		static $did_run = false;

		if ( ! is_singular( self::POST_TYPE['id'] ) || ! in_the_loop() || ! is_main_query() ) {
			return $content;
		}

		if ( $did_run ) {
			return $content;
		}

		if ( has_block( self::$cta_block_name, $content ) ) {
			return $content;
		}

		if ( '' === trim( $content ) ) {
			return $content;
		}

		$pattern_id = absint( get_option( self::$cta_option_id ) );
		if ( 0 === $pattern_id ) {
			return $content;
		}

		$insert_after = absint( get_option( self::$cta_position_id ) );
		if ( $insert_after < 1 ) {
			return $content;
		}

		$pattern_serialized = $this->generate_serialized_content( $pattern_id );

		$processor = new \WP_Block_Processor( $content );
		$count     = 0;

		while ( $processor->next_block( '*' ) ) {
			if ( 1 !== $processor->get_depth() ) {
				continue;
			}

			$block_type = $processor->get_block_type();
			if ( empty( $block_type ) || ! in_array( $block_type, self::$qualifying_blocks, true ) ) {
				continue;
			}

			++$count;

			if ( $count !== $insert_after ) {
				continue;
			}

			$processor->extract_full_block_and_advance();
			$span   = $processor->get_span();
			$offset = $span->start ?? strlen( $content );

			$did_run = true;

			return substr( $content, 0, $offset )
				. "\n"
				. $pattern_serialized
				. "\n"
				. substr( $content, $offset );
		}

		return $content;
	}

	/**
	 * Generate the serialized Group + synced-pattern reference markup.
	 *
	 * @param int $pattern_id Synced pattern (wp_block) post ID.
	 * @return string Serialized block markup.
	 */
	public function generate_serialized_content( int $pattern_id ): string {
		$block_align     = get_post_meta( $pattern_id, 'pattern_align', true );
		$block_justify   = get_post_meta( $pattern_id, 'pattern_justify', true );
		$block_className = 'wp-block-' . str_replace( '/', '-', self::$cta_block_name );

		$wrapper_attributes = array(
			'className' => sprintf(
				'%s%s',
				$block_className,
				( $block_align ) ? ' align' . $block_align : ''
			),
			'align'     => $block_align ?? '',
			'layout'    => array(
				'justifyContent' => $block_justify ?? '',
			),
		);

		$wrapper_class = $wrapper_attributes['className'];

		$pattern_serialized = sprintf(
			'<!-- wp:group %1$s -->' . "\n" .
			'<div class="wp-block-group %2$s">' . "\n" .
			'<!-- wp:block {"ref":%3$d} /-->' . "\n" .
			'</div>' . "\n" .
			'<!-- /wp:group -->',
			wp_json_encode( $wrapper_attributes ),
			esc_attr( $wrapper_class ),
			$pattern_id
		);

		return $pattern_serialized;
	}
}
