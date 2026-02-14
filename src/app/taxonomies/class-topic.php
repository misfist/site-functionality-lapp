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
			'newspack_spnsrs_cpt',
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

		\add_filter( 'acf/fields/taxonomy/query', array( $this, 'exlude_current_term' ), 10, 3 );

		\add_filter( 'acf/fields/relationship/query/key=field_featured_posts', array( $this, 'featured_posts' ), 10, 3 );

		\add_action( self::$taxonomy['id'] . '_edit_form', array( $this, 'hide_description' ) );
		\add_action( self::$taxonomy['id'] . '_add_form', array( $this, 'hide_description' ) );

		\add_action( 'acf/save_post', array( $this, 'save_description' ), 20 );

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

		$width      = 1200;
		$height     = 900;
		$image_size = 'newspack-archive-image-large';
		$sizes      = wp_get_additional_image_sizes();
		if ( isset( $sizes[ $image_size ] ) ) {
			$width  = $sizes[ $image_size ]['width'];
			$height = $sizes[ $image_size ]['height'];
		}

		$fields = array(
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
					'hub'    => esc_attr__( 'Hub', 'site-functionality' ),
					'magnet' => esc_attr__( 'Magnet', 'site-functionality' ),
				),
				'default_value'     => 'hub',
				'return_format'     => 'value',
				'allow_null'        => 0,
				'other_choice'      => 0,
				'allow_in_bindings' => 1,
				'layout'            => 'vertical',
				'save_other_choice' => 0,
			),
			array(
				'key'               => 'field_term_image',
				'label'             => esc_html__( 'Image', 'site-functionality' ),
				'name'              => 'image',
				'aria-label'        => '',
				'type'              => 'image',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => array(
					'width' => '',
					'class' => '',
					'id'    => '',
				),
				'return_format'     => 'array',
				'library'           => 'all',
				'min_width'         => $width,
				'min_height'        => $height,
				'min_size'          => '',
				'max_width'         => '',
				'max_height'        => '',
				'max_size'          => '',
				'mime_types'        => '',
				'allow_in_bindings' => 1,
				'preview_size'      => 'medium',
			),
			array(
				'key'               => 'field_term_description',
				'label'             => esc_html__( 'Description', 'site-functionality' ),
				'name'              => 'term_description',
				'aria-label'        => '',
				'type'              => 'wysiwyg',
				'instructions'      => esc_html__( 'Displayed as the intro paragraph on Magnet and Hub pages.', 'site-functionality' ),
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => array(
					'width' => '',
					'class' => '',
					'id'    => '',
				),
				'default_value'     => '',
				'allow_in_bindings' => 1,
				'tabs'              => 'all',
				'toolbar'           => 'basic',
				'media_upload'      => 1,
				'delay'             => 0,
			),
			array(
				'key'                  => 'field_featured_posts',
				'label'                => esc_html__( 'Featured', 'site-functionality' ),
				'name'                 => 'featured_posts',
				'aria-label'           => '',
				'type'                 => 'relationship',
				'instructions'         => '',
				'required'             => 0,
				'conditional_logic'    => 0,
				'wrapper'              => array(
					'width' => '',
					'class' => '',
					'id'    => '',
				),
				'post_type'            => array(
					0 => 'post',
				),
				'post_status'          => '',
				'taxonomy'             => '',
				'filters'              => array(
					0 => 'search',
				),
				'return_format'        => 'id',
				'min'                  => 3,
				'max'                  => 6,
				'allow_in_bindings'    => 1,
				'elements'             => array(
					0 => 'featured_image',
				),
				'bidirectional'        => 0,
				'bidirectional_target' => array(),
			),
			array(
				'key'                  => 'field_related_topic_terms',
				'label'                => esc_html__( 'Related Topics', 'site-functionality' ),
				'name'                 => 'related_terms',
				'aria-label'           => '',
				'type'                 => 'taxonomy',
				'instructions'         => '',
				'required'             => 0,
				'conditional_logic'    => 0,
				'wrapper'              => array(
					'width' => '',
					'class' => '',
					'id'    => '',
				),
				'taxonomy'             => 'topic',
				'add_term'             => 0,
				'save_terms'           => 0,
				'load_terms'           => 0,
				'return_format'        => 'id',
				'field_type'           => 'multi_select',
				'allow_null'           => 1,
				'allow_in_bindings'    => 1,
				'bidirectional'        => 1,
				'bidirectional_target' => array(
					0 => 'field_related_topic_terms',
				),
				'multiple'             => 0,
			),
			array(
				'key'               => 'field_resources',
				'label'             => esc_html__( 'Resources', 'site-functionality' ),
				'name'              => 'resources',
				'aria-label'        => '',
				'type'              => 'repeater',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => array(
					'width' => '',
					'class' => '',
					'id'    => '',
				),
				'layout'            => 'row',
				'pagination'        => 0,
				'min'               => 0,
				'max'               => 0,
				'collapsed'         => '',
				'button_label'      => esc_attr__( 'Add Resource', 'site-functionality' ),
				'rows_per_page'     => 20,
				'sub_fields'        => array(
					array(
						'key'               => 'field_resource_item',
						'label'             => esc_html__( 'Resource', 'site-functionality' ),
						'name'              => 'resource',
						'aria-label'        => '',
						'type'              => 'link',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'return_format'     => 'array',
						'allow_in_bindings' => 1,
						'parent_repeater'   => 'field_resources',
					),
				),
			),
			// array(
			// 	'key'               => 'field_sponsor_information',
			// 	'label'             => esc_html__( 'Sponsor Information', 'site-functionality' ),
			// 	'name'              => 'sponsor_information',
			// 	'aria-label'        => '',
			// 	'type'              => 'wysiwyg',
			// 	'instructions'      => '',
			// 	'required'          => 0,
			// 	'conditional_logic' => 0,
			// 	'wrapper'           => array(
			// 		'width' => '',
			// 		'class' => '',
			// 		'id'    => '',
			// 	),
			// 	'default_value'     => '',
			// 	'allow_in_bindings' => 1,
			// 	'tabs'              => 'all',
			// 	'toolbar'           => 'basic',
			// 	'media_upload'      => 1,
			// 	'delay'             => 0,
			// ),
		);

		\acf_add_local_field_group(
			array(
				'key'                   => 'group_topic_settings',
				'title'                 => esc_html__( 'Topic Settings', 'site-functionality' ),
				'fields'                => $fields,
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
	 * Exclude the current term
	 *
	 * @param array $args
	 * @param array $field
	 * @param mixed $post_id
	 *
	 * @return array $args
	 */
	public function exlude_current_term( array $args, array $field, $post_id ): array {
		$term_id = str_replace( 'term_', '', $post_id );
		if ( term_exists( (int) $term_id, self::$taxonomy['id'] ) ) {
			$args['exclude'] = (int) $term_id;
		}
		return $args;
	}

	/**
	 * Limit posts to current taxonomy
	 * 
	 * @link https://www.advancedcustomfields.com/resources/acf-fields-relationship-query/
	 *
	 * @param array              $args
	 * @param array              $field
	 * @param mixed (int|string) $post_id
	 *
	 * @return array
	 */
	function featured_posts( array $args, array $field, $post_id ): array {
		$term_id = str_replace( 'term_', '', $post_id );
		$tax_query = array(
			array(
				'taxonomy' => self::$taxonomy['id'],
				'field'    => 'term_id',
				'terms'    => array( (int) $term_id ),
			),
		);

		$args['tax_query'] = $tax_query;
		return $args;
	}

	/**
	 * Save description
	 *
	 * @param string $post_id
	 *
	 * @return void
	 */
	public function save_description( string $post_id ) {
		if ( ! preg_match( '/^term_([0-9]+)$/', $post_id, $matches ) ) {
			return;
		}

		$term_id = absint( $matches[1] );

		if ( ! $term_id ) {
			return;
		}

		$taxonomy = self::$taxonomy['id'];

		$term = get_term( $term_id );

		if ( ! $term || is_wp_error( $term ) || $taxonomy !== $term->taxonomy ) {
			return;
		}

		$description = get_term_meta( $term_id, 'term_description', true );

		wp_update_term(
			$term_id,
			$taxonomy,
			array(
				'description' => wp_kses_post( $description ),
			)
		);
	}

	/**
	 * Visually hide term description
	 *
	 * @return void
	 */
	public function hide_description(): void {
		echo '<style> .term-description-wrap { display:none; } </style>';
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
