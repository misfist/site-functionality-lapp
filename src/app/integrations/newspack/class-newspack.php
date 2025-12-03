<?php
/**
 * Newspack
 *
 * @since   1.0.0
 * @package Site_Functionality
 */
namespace Site_Functionality\App\Integrations;

use Site_Functionality\Common\Abstracts\Base;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Newspack extends Base {

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
		if( class_exists( '\Newspack\Patches' ) ) {
			remove_action( 'admin_menu', array( \Newspack\Patches::class, 'remove_core_appearance_menu_links' ) );
		}
	}
}
