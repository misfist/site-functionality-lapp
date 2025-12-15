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
	 * Template Field Name
	 *
	 * @var string
	 */
	public static $template_field = 'template_type';

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct( $settings ) {
		parent::__construct( $settings );

		\add_action( 'init', array( $this, 'rewrite_rules' ), 10, 0 );

		\add_action( 'acf/include_fields', array( $this, 'register_term_meta' ) );

		\add_filter( 'manage_edit-' . self::$taxonomy['id'] . '_columns', array( $this, 'register_column' ) );

		\add_filter( 'manage_' . self::$taxonomy['id'] . '_custom_column', array( $this, 'render_column' ), 10, 3 );

		\add_filter( 'manage_edit-' . self::$taxonomy['id'] . '_sortable_columns', array( $this, 'sort_column' ) );

		\add_action( 'pre_get_terms', array( $this, 'sort_by_meta' ) );
	}

	/**
	 * Register Meta
	 *
	 * @return void
	 */
	public function register_term_meta(): void {
		if ( ! function_exists( 'acf_add_local_field_group' ) ) {
			return;
		}

		\acf_add_local_field_group(
			array(
				'key'                   => 'group_topic_settings',
				'title'                 => esc_html__( 'Topic Settings', 'site-functionality' ),
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
							'value'    => self::$taxonomy['id'],
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
	 * Register custom columns.
	 *
	 * @param array $columns Existing columns.
	 * @return array
	 */
	public function register_column( array $columns ): array {
		self::$template_field             = 'template_type';
		$columns[ self::$template_field ] = __( 'Template', 'site-functionality' );
		return $columns;
	}

	/**
	 * Modify Taxonomy Term Admin List
	 *
	 * @param string $content
	 * @param string $column_name
	 * @param int    $term_id
	 *
	 * @return string
	 */
	public function render_column( string $content, string $column_name, int $term_id ): string {
		$term          = get_term( $term_id, self::$taxonomy['id'] );
		$template_type = get_field( self::$template_field, $term );

		switch ( $column_name ) {
			case self::$template_field:
				$content = ucfirst( $template_type );
				break;
			default:
				break;
		}
		return esc_html( $content );
	}

	/**
	 * Make Column Sortable
	 *
	 * @param array $sortable_columns
	 *
	 * @return array
	 */
	function sort_column( array $sortable_columns ): array {
		$sortable_columns[ self::$template_field ] = self::$template_field;
		return $sortable_columns;
	}

	/**
	 * Adjust term query for sorting.
	 *
	 * @param \WP_Term_Query $query The term query.
	 */
	public function sort_by_meta( \WP_Term_Query $query ) {
		if ( ! is_admin() ) {
			return;
		}

		$orderby = isset( $_GET['orderby'] ) ? sanitize_text_field( wp_unslash( $_GET['orderby'] ) ) : '';
		$order   = isset( $_GET['order'] ) ? sanitize_text_field( wp_unslash( $_GET['order'] ) ) : 'asc';

		if ( self::$template_field !== $orderby ) {
			return;
		}

		$query->query_vars['meta_key'] = self::$template_field;
		$query->query_vars['orderby']  = 'meta_value';
		$query->query_vars['order']    = ( 'desc' === strtolower( $order ) ) ? 'DESC' : 'ASC';
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
