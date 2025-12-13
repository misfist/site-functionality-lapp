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
		'id'           => 'topic',
		'title'        => 'Topics',
		'singular'     => 'Topic',
		'menu'         => 'Topics',
		'post_types'   => array(
			'post',
		),
		'has_archive'  => true,
		'archive'      => 'topic',
		'with_front'   => false,
		'rest'         => 'topics',
		'hierarchical' => true,
	);

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct( $settings ) {
		parent::__construct( $settings );

		\add_action( 'init', array( $this, 'rewrite_rules' ), 10, 0 );

		\add_action( 'acf/include_fields', array( $this, 'register_term_meta' ) );
	}

	public function register_term_meta() {
		if ( ! function_exists( 'acf_add_local_field_group' ) ) {
			return;
		}

		\acf_add_local_field_group(
			array(
				'key'                   => 'group_topic_settings',
				'title'                 => 'Topic Settings',
				'fields'                => array(
					array(
						'key'               => 'field_template_type',
						'label'             => esc_html__( 'Template Type', 'site-functionality' ),
						'name'              => 'template_type',
						'aria-label'        => '',
						'type'              => 'radio',
						'instructions'      => '',
						'required'          => 1,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'choices'           => array(
							'hub'    => 'Hub',
							'magnet' => 'Magnet',
						),
						'default_value'     => 'hub',
						'return_format'     => 'value',
						'allow_null'        => 0,
						'other_choice'      => 0,
						'allow_in_bindings' => 1,
						'layout'            => 'vertical',
						'save_other_choice' => 0,
					),
				),
				'location'              => array(
					array(
						array(
							'param'    => 'taxonomy',
							'operator' => '==',
							'value'    => 'topic',
						),
					),
				),
				'menu_order'            => 0,
				'position'              => 'normal',
				'style'                 => 'seamless',
				'label_placement'       => 'top',
				'instruction_placement' => 'label',
				'hide_on_screen'        => '',
				'active'                => true,
				'description'           => '',
				'show_in_rest'          => 1,
				'display_title'         => esc_html__( 'Additional Settings', 'site-functionality' ),
			)
		);
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
