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
		if ( class_exists( '\Newspack\Patches' ) ) {
			remove_action( 'admin_menu', array( \Newspack\Patches::class, 'remove_core_appearance_menu_links' ) );

			remove_action( 'admin_menu', array( \Newspack\Patches::class, 'add_patterns_menu_link' ) );

			remove_action( 'admin_menu', array( \Newspack\Patches::class, 'add_pattern_categories_menu_link' ) );
		}

		add_action( 'admin_menu', array( self::class, 'add_patterns_menu_link' ) );

		add_action( 'admin_menu', array( self::class, 'add_pattern_categories_menu_link' ) );
	}

	/**
	 * Add a menu link in WP Admin to easily edit and manage patterns.
	 */
	public static function add_patterns_menu_link() {
		add_submenu_page(
			'themes.php',
			'manage_patterns',
			__( 'Patterns' ),
			'edit_posts',
			'edit.php?post_type=wp_block',
			'',
			2
		);
	}

	/**
	 * Add a menu link in WP Admin to easily edit and manage pattern categories.
	 */
	public static function add_pattern_categories_menu_link() {
		add_submenu_page(
			'themes.php',
			'manage_pattern_categories',
			__( 'Pattern Categories', 'site-functionality' ),
			'edit_posts',
			'edit-tags.php?taxonomy=wp_pattern_category',
			'',
			3
		);
	}
}
