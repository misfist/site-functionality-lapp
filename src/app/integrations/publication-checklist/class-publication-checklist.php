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
// use Altis\Workflow\PublicationChecklist as Checklist;
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
		add_action( 'altis.publication-checklist.register_prepublish_checks', array( $this, 'register_checks' ) );
	}

	/**
	 * Register Prepublish Checks
	 *
	 * @link https://github.com/humanmade/publication-checklist
	 *
	 * @return void
	 */
	public function register_checks() {
		/**
		 * Check category
		 */
		register_prepublish_check(
			'categories',
			array(
				'run_check' => function ( array $post, array $meta, array $terms ): Status {
					if ( empty( $terms['category'] ) ) {
						return new Status( Status::INCOMPLETE, __( 'Add at least one category to the post', 'site-functionality' ) );
					}

					return new Status( Status::COMPLETE, __( 'Category added to post', 'site-functionality' ) );
				},
			)
		);
	}
}
