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
	 * Init
	 *
	 * @return void
	 */
	public function init(): void {
		parent::init();

		add_action( 'init', array( $this, 'register_template' ), 20 );

		add_filter( 'the_content', array( $this, 'cta_before_content' ), 1 );

		add_filter( 'the_content', array( $this, 'cta_after_content' ), 1 );
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
		if ( 
			! is_admin() &&
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

			$option_name    = Admin_Settings::$cta_pattern_option;
			$cta_pattern_id = get_option( 'options_' . $option_name . '_before_content' );

			if ( ! $cta_pattern_id ) {
				return $content;
			}

			$cta = get_post( (int) $cta_pattern_id );
			if ( ! $cta || is_wp_error( $cta ) ) {
				return $content;
			}

			$cta_content = $cta->post_content;

			$did_run       = true;

			$block_content = sprintf( '
				<div class="wp-block-site-functionality-cta-slot%s" id="cta-slot-%s">%s</div>',
				' before-content alignfull',
				uniqid(),
				do_blocks( $cta_content )
			);

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
		if ( 
			! is_admin() &&
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

			$option_name    = Admin_Settings::$cta_pattern_option;
			$cta_pattern_id = get_option( 'options_' . $option_name . '_after_content' );

			if ( ! $cta_pattern_id ) {
				return $content;
			}

			$cta = get_post( (int) $cta_pattern_id );
			if ( ! $cta || is_wp_error( $cta ) ) {
				return $content;
			}

			$cta_content = $cta->post_content;

			$did_run       = true;
			$block_content = sprintf( '
				<div class="wp-block-site-functionality-cta-slot%s" id="cta-slot-%s">%s</div>',
				' after-content alignfull',
				uniqid(),
				do_blocks( $cta_content )
			);

			return $content . $block_content;
		}
		return $content;
	}
}
