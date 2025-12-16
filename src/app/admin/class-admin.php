<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/misfist/site-functionality
 * @since      1.0.0
 *
 * @package    site-functionality
 */

namespace Site_Functionality\App\Admin;

use Site_Functionality\Common\Abstracts\Base;
use Site_Functionality\App\Admin\Admin_Assets;
use Site_Functionality\App\Admin\Admin_Settings;

/**
 * The admin-specific functionality of the plugin.
 *
 */
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Admin extends Base {

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
		$admin_settings = new Admin_Settings( $this->settings );

		// $admin_assets = new Admin_Assets( $this->settings );
	}
}
