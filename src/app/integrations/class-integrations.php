<?php
/**
 * Content Integrations
 *
 * @since   1.0.0
 * @package Site_Functionality
 */
namespace Site_Functionality\App\Integrations;

use Site_Functionality\Common\Abstracts\Base;
use Site_Functionality\App\Integrations\Publication_Checklist;
use Site_Functionality\App\Integrations\Publish_To_Apple_News;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Integrations extends Base {

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
		new Publication_Checklist( $this->settings );
		new Publish_To_Apple_News( $this->settings );
	}
}
