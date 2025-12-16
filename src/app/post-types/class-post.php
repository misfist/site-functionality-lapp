<?php
/**
 * Content Post_Types
 *
 * @since   1.0.0
 * @package Site_Functionality
 */
namespace Site_Functionality\App\Post_Types;

use Site_Functionality\Common\Abstracts\Base;

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
				'site-functionality/ad-slot',
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
	
}
