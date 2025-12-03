<?php
/**
 * Taxonomy
 *
 * @since   1.0.0
 *
 * @package   Site_Functionality
 */
namespace Site_Functionality\App\Taxonomies;

use Site_Functionality\Common\Abstracts\Taxonomy;

/**
 * Class Taxonomies
 *
 * @package Site_Functionality\App\Taxonomies
 * @since 1.0.0
 */
class Topic extends Taxonomy {

	/**
	 * Taxonomy data
	 */
	public static $taxonomy = array(
		'id'          => 'topic',
		'title'       => 'Topics',
		'singular'    => 'Topic',
		'menu'        => 'Topics',
		'post_types'  => array(
			'post',
		),
		'has_archive' => true,
		'archive'     => 'topic',
		'with_front'  => false,
		'rest'        => 'topics',
	);

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct( $settings ) {
		parent::__construct( $settings );

		\add_action( 'init', array( $this, 'rewrite_rules' ), 10, 0 );
	}

	/**
	 * Add rewrite rules
	 *
	 * @link https://developer.wordpress.org/reference/functions/add_rewrite_rule/
	 *
	 * @return void
	 */
	public function rewrite_rules(): void {}
}
