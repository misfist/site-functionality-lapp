<?php
/**
 * Publication Checklist
 *
 * @since   1.0.0
 * @package Site_Functionality
 */
namespace Site_Functionality\App\Integrations;

use Site_Functionality\Common\Abstracts\Base;
use function Altis\Workflow\PublicationChecklist\register_prepublish_check;
use Altis\Workflow\PublicationChecklist as Checklist;
use Altis\Workflow\PublicationChecklist\Status;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Publication_Checklist extends Base {

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
		add_action( 'altis.publication-checklist.register_prepublish_checks', array( $this, 'register_cta' ) );
		add_action( 'altis.publication-checklist.register_prepublish_checks', array( $this, 'register_featured_image' ) );
		add_action( 'altis.publication-checklist.register_prepublish_checks', array( $this, 'register_categories' ) );
		add_action( 'altis.publication-checklist.register_prepublish_checks', array( $this, 'register_tags' ) );
		add_filter(
			'altis.publication-checklist.block_on_failing',
			array( $this, 'enforce_blocking' )
		);
	}

	/**
	 * Enforce Publication Checklist blocking on failing checks.
	 *
	 * @return bool
	 */
	public function enforce_blocking() {
		return false;
	}

	/**
	 * Register Prepublish Checks
	 *
	 * @link https://github.com/humanmade/publication-checklist
	 *
	 * @return void
	 */
	public function register_categories() {
		/**
		 * Check category
		 */
		register_prepublish_check(
			'categories',
			array(
				'type'      => array(
					'post',
				),
				'run_check' => function ( array $post, array $meta, array $terms ): Status {
					if ( empty( $terms['category'] ) ) {
						return new Status( Status::INCOMPLETE, __( 'Add at least one category to the post', 'site-functionality' ) );
					}

					return new Status( Status::COMPLETE, __( 'Category added to post', 'site-functionality' ) );
				},
			)
		);
	}

	/**
	 * Register Feature Image
	 *
	 * @return void
	 */
	public function register_featured_image() {
		register_prepublish_check(
			'featured-image',
			array(
				'type'      => array( 'post' ),
				'run_check' => function ( array $post, array $meta ): Status {
					if ( isset( $meta['_thumbnail_id'] ) && $meta['_thumbnail_id'] ) {
						return new Status( Status::COMPLETE, __( 'Featured Image is set', 'site-functionality' ) );
					}

					return new Status( STATUS::INCOMPLETE, __( 'Add Featured Image', 'site-functionality' ) );
				},
			)
		);
	}


	/**
	 * Register Prepublish Checks
	 *
	 * @link https://github.com/humanmade/publication-checklist
	 *
	 * @return void
	 */
	public function register_tags() {

		/**
		 * Check category
		 */
		register_prepublish_check(
			'tags',
			array(
				'type'      => array(
					'post',
				),
				'run_check' => function ( array $post, array $meta, array $terms ): Status {
					if ( ! empty( $terms['post_tag'] ) ) {
						return new Status( Status::COMPLETE, __( 'Tags added to post', 'site-functionality' ) );
					}

					return new Status( STATUS::INFO, __( 'Consider adding tags to post', 'site-functionality' ) );
				},
			)
		);
	}

	/**
	 * Register CTA
	 *
	 * @return void
	 */
	public function register_cta() {
		$block_names = array(
			'site-functionality/cta-slot',
		);

		register_prepublish_check(
			'cta',
			array(
				'type'      => array(
					'post',
				),
				'run_check' => function ( array $post ) use ( $block_names ): Status {
					$blocks       = parse_blocks( $post['post_content'] );
					$cta_blocks = array_filter(
						$blocks,
						function ( $block ) use ( $block_names ) {
							return in_array( $block['blockName'], $block_names, true );
						}
					);

					if ( count( $cta_blocks ) > 0 ) {
						return new Status( Status::COMPLETE, __( 'CTA added to post', 'site-functionality' ) );
					}

					return new Status( STATUS::INCOMPLETE, __( 'Add a CTA block to the post', 'site-functionality' ) );
				},
			)
		);
	}
}
