<?php
/**
 * Publication Checklist
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

class Publish_To_Apple_News extends Base {

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
		add_action( 'acf/include_fields', array( $this, 'register_settings_fields' ) );

		add_action( 'acf/init', array( $this, 'register_options_page' ) );

		// add_filter( 'apple_news_component_text_styles', array( $this, 'component_text_styles' ) );

		// add_filter( 'apple_news_component_styles', array( $this, 'component_styles' ) );

		// add_filter( 'apple_news_component_layouts', array( $this, 'component_layouts' ) );

		// add_filter( 'apple_news_in_article_json', array( $this, 'modify_in_article_component' ) );

		// add_filter( 'apple_news_end_of_article_json', array( $this, 'modify_end_of_article_component' ) );

		add_filter( 'apple_news_exporter_content_pre', array( $this, 'filter_apple_news_content_pre' ), 10, 2 );

		add_filter( 'acf/get_post_types', array( $this, 'enable_block_post_types' ), 10, 1 );
	}

	/**
	 * Modify ACF post_object query to include wp_block.
	 *
	 * @param array $post_types
	 * @return array $post_types
	 */
	public function enable_block_post_types( $post_types ) {
		if ( ! in_array( 'wp_block', $post_types ) ) {
			$post_types[] = 'wp_block';
		}
		return $post_types;
	}

	/**
	 * Register the ACF field group for Apple News CTA positions.
	 *
	 * @return void
	 */
	public function register_settings_fields() {
		if ( ! function_exists( 'acf_add_local_field_group' ) ) {
			return;
		}

		acf_add_local_field_group(
			array(
				'key'                   => 'group_apple_news_before',
				'title'                 => __( 'Before Article', 'site-functionality' ),
				'fields'                => array(
					array(
						'key'               => 'field_before_article_heading',
						'label'             => __( 'Heading', 'site-functionality' ),
						'name'              => 'before_article_heading',
						'type'              => 'text',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(),
						'default_value'     => '',
						'maxlength'         => '',
						'allow_in_bindings' => 0,
						'placeholder'       => '',
						'prepend'           => '',
						'append'            => '',
					),
					array(
						'key'               => 'field_before_article_text',
						'label'             => __( 'Content', 'site-functionality' ),
						'name'              => 'before_article_text',
						'type'              => 'wysiwyg',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(),
						'default_value'     => '',
						'allow_in_bindings' => 0,
						'tabs'              => 'all',
						'toolbar'           => 'full',
						'media_upload'      => 1,
						'delay'             => 0,
					),
					array(
						'key'               => 'field_before_article_button_text',
						'label'             => __( 'Button Text', 'site-functionality' ),
						'name'              => 'before_article_button_text',
						'type'              => 'text',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(),
						'default_value'     => '',
						'maxlength'         => '',
						'allow_in_bindings' => 0,
						'placeholder'       => '',
						'prepend'           => '',
						'append'            => '',
					),
					array(
						'key'               => 'field_before_article_button_url',
						'label'             => __( 'Button URL', 'site-functionality' ),
						'name'              => 'before_article_button_url',
						'type'              => 'url',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(),
						'default_value'     => '',
						'allow_in_bindings' => 0,
						'placeholder'       => '',
					),
				),
				'location'              => array(
					array(
						array(
							'param'    => 'options_page',
							'operator' => '==',
							'value'    => 'component-slots',
						),
					),
				),
				'menu_order'            => 10,
				'position'              => 'normal',
				'style'                 => 'default',
				'label_placement'       => 'top',
				'instruction_placement' => 'label',
				'hide_on_screen'        => '',
				'active'                => true,
				'description'           => __( 'Content to display before the article content.', 'site-functionality' ),
				'show_in_rest'          => 1,
			)
		);

		acf_add_local_field_group(
			array(
				'key'                   => 'group_apple_news_after',
				'title'                 => __( 'After Article', 'site-functionality' ),
				'fields'                => array(
					array(
						'key'               => 'field_end_of_article_heading',
						'label'             => __( 'Heading', 'site-functionality' ),
						'name'              => 'end_of_article_heading',
						'type'              => 'text',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(),
						'default_value'     => '',
						'maxlength'         => '',
						'allow_in_bindings' => 0,
						'placeholder'       => '',
						'prepend'           => '',
						'append'            => '',
					),
					array(
						'key'               => 'field_end_of_article_text',
						'label'             => __( 'Content', 'site-functionality' ),
						'name'              => 'end_of_article_text',
						'type'              => 'wysiwyg',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(),
						'default_value'     => '',
						'allow_in_bindings' => 0,
						'tabs'              => 'all',
						'toolbar'           => 'full',
						'media_upload'      => 1,
						'delay'             => 0,
					),
					array(
						'key'               => 'field_end_of_article_button_text',
						'label'             => __( 'Button Text', 'site-functionality' ),
						'name'              => 'end_of_article_button_text',
						'type'              => 'text',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(),
						'default_value'     => '',
						'maxlength'         => '',
						'allow_in_bindings' => 0,
						'placeholder'       => '',
						'prepend'           => '',
						'append'            => '',
					),
					array(
						'key'               => 'field_end_of_article_button_url',
						'label'             => __( 'Button URL', 'site-functionality' ),
						'name'              => 'end_of_article_button_url',
						'type'              => 'url',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(),
						'default_value'     => '',
						'allow_in_bindings' => 0,
						'placeholder'       => '',
					),
				),
				'location'              => array(
					array(
						array(
							'param'    => 'options_page',
							'operator' => '==',
							'value'    => 'component-slots',
						),
					),
				),
				'menu_order'            => 20,
				'position'              => 'normal',
				'style'                 => 'default',
				'label_placement'       => 'top',
				'instruction_placement' => 'label',
				'hide_on_screen'        => '',
				'active'                => true,
				'description'           => __( 'Content to display after the article content.', 'site-functionality' ),
				'show_in_rest'          => 1,
			)
		);

		acf_add_local_field_group(
			array(
				'key'                   => 'group_apple_news_settings',
				'title'                 => __( 'Apple News Settings', 'site-functionality' ),
				'fields'                => array(
					array(
						'key'                  => 'field_apple_news_before_content',
						'label'                => __( 'Before Content', 'site-functionality' ),
						'name'                 => 'before_content',
						'type'                 => 'post_object',
						'instructions'         => __( 'Select blocks to display before article content.', 'site-functionality' ),
						'required'             => 0,
						'conditional_logic'    => 0,
						'wrapper'              => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'post_type'            => array( 'wp_block' ),
						'return_format'        => 'object',
						'multiple'             => 1,
						'allow_null'           => 1,
						'allow_in_bindings'    => 1,
						'bidirectional'        => 0,
						'ui'                   => 1,
						'bidirectional_target' => array(),
					),
					array(
						'key'                  => 'field_apple_news_after_content',
						'label'                => __( 'After Content', 'site-functionality' ),
						'name'                 => 'after_content',
						'type'                 => 'post_object',
						'instructions'         => __( 'Select blocks to display after article content.', 'site-functionality' ),
						'required'             => 0,
						'conditional_logic'    => 0,
						'wrapper'              => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'post_type'            => array( 'wp_block' ),
						'return_format'        => 'object',
						'multiple'             => 1,
						'allow_null'           => 1,
						'allow_in_bindings'    => 1,
						'bidirectional'        => 0,
						'ui'                   => 1,
						'bidirectional_target' => array(),
					),
				),
				'location'              => array(
					array(
						array(
							'param'    => 'options_page',
							'operator' => '==',
							'value'    => 'component-slots',
						),
					),
				),
				'menu_order'            => 0,
				'position'              => 'normal',
				'style'                 => 'default',
				'label_placement'       => 'top',
				'instruction_placement' => 'label',
				'hide_on_screen'        => '',
				'active'                => true,
				'description'           => '',
				'show_in_rest'          => 0,
			)
		);
	}

	/**
	 * Register the ACF options page for Component Settings.
	 *
	 * @return void
	 */
	public function register_options_page() {
		if ( ! function_exists( 'acf_add_options_page' ) ) {
			return;
		}

		acf_add_options_page(
			array(
				'page_title'      => __( 'Component Slots', 'site-functionality' ),
				'menu_slug'       => 'component-slots',
				'parent_slug'     => 'apple_news_index',
				'menu_title'      => __( 'Component Settings', 'site-functionality' ),
				'position'        => 2,
				'redirect'        => false,
				'updated_message' => __( 'Component Settings Updated', 'site-functionality' ),
				'autoload'        => true,
			)
		);
	}

	/**
	 * Add style
	 *
	 * @param array $styles
	 *
	 * @return array $styles
	 */
	public function component_text_styles( array $styles ): array {
		$styles['default-heading-3-center'] = array(
			'fontName'      => 'AvenirNext-Bold',
			'fontSize'      => 48,
			'lineHeight'    => 46,
			'textColor'     => '#000000',
			'textAlignment' => 'center',
			'tracking'      => -0.04,
		);

		error_log( __METHOD__ . '  $styles = ' . print_r( $styles, true ) );
		return $styles;
	}

	/**
	 * Modify Component Style
	 *
	 * @param array $styles
	 *
	 * @return array $styles
	 */
	public function component_styles( array $styles ): array {
		$primary_color   = '#0daba4';
		$secondary_color = '#e8266b';
		$gray_light      = '#f6f7f7';
		$gray            = '#cccccc';

		$styles['end-of-article-style'] = array(
			'margin'          => array(
				'top'    => 20,
				'bottom' => 20,
			),
			'padding'         => 20,
			'backgroundColor' => $gray_light,
			'opacity'         => 1,
			'border'          => array(
				'all' => array(
					'width' => 2,
					'color' => $gray,
				),
			),
		);
		$styles['in-article-style']     = array(
			'margin'          => array(
				'top'    => 20,
				'bottom' => 20,
			),
			'padding'         => 20,
			'backgroundColor' => $gray_light,
			'opacity'         => 1,
			'border'          => array(
				'all' => array(
					'width' => 2,
					'color' => $gray,
				),
			),
		);

		error_log( __METHOD__ . '  $styles = ' . print_r( $styles, true ) );
		return $styles;
	}

	/**
	 * Modify Layout
	 *
	 * @param array $layouts
	 *
	 * @return array $layouts
	 */
	public function component_layouts( array $layouts ): array {
		$layouts['end-of-article-layout'] = array(
			'columnStart' => 0,
			'columnSpan'  => 8,
			'margin'      => array(
				'top'    => 20,
				'bottom' => 20,
			),
			'padding'     => 20,
		);

		$layouts['in-article-layout'] = array(
			'columnStart' => 0,
			'columnSpan'  => 8,
			'margin'      => array(
				'top'    => 20,
				'bottom' => 20,
			),
			'padding'     => 20,
		);

		error_log( __METHOD__ . '  $layouts = ' . print_r( $layouts, true ) );
		return $layouts;
	}

	/**
	 * Modify Component
	 *
	 * @param array $component
	 *
	 * @return array $component
	 */
	public function modify_in_article_component( $component ) {
		$heading     = get_option( 'in_article_heading', __( '', 'site-functionality' ) );
		$text        = get_option( 'in_article_text', __( '', 'site-functionality' ) );
		$button_text = get_option( 'in_article_button_text', esc_html__( 'Support Our Work', 'site-functionality' ) );
		$url         = get_option( 'in_article_button_url', esc_url( 'https://givebutter.com/joinlapublicpress' ) );

		$component = array(
			'role'       => 'container',
			'layout'     => 'in-article-layout',
			'style'      => 'in-article-style',
			'components' => array(
				array(
					'role'      => 'link_button',
					'text'      => $button_text,
					'URL'       => $url,
					'style'     => 'default-link-button',
					'layout'    => 'link-button-layout',
					'textStyle' => 'default-link-button-text-style',
				),
			),
		);

		if ( $heading ) {
			$component['components'][] = array(
				'role'      => 'heading3',
				'text'      => $heading,
				'textStyle' => 'default-heading-3-center',
				'layout'    => 'body-layout',
			);
		}

		if ( $text ) {
			$component['components'][] =
			array(
				'role'      => 'body',
				'text'      => wp_kses_post( $text ),
				'format'    => 'html',
				'textStyle' => 'default-body-center',
				'layout'    => 'body-layout',
			);
		}
		error_log( __METHOD__ . ' $component ' . print_r( $component, true ) );

		return $component;
	}

	/**
	 * Modify Component
	 *
	 * @param array $component
	 *
	 * @return array $component
	 */
	public function modify_end_of_article_component( $component ) {
		$heading     = get_option( 'end_of_article_heading', __( 'Subscribe to LA Public Press', 'site-functionality' ) );
		$text        = get_option( 'end_of_article_text', __( 'The best way to keep up with Los Angeles Public Press is to subscribe to our weekly newsletter.', 'site-functionality' ) );
		$button_text = get_option( 'end_of_article_button_text', esc_html__( 'Sign Up', 'site-functionality' ) );
		$url         = get_option( 'end_of_article_button_url', esc_url( get_home_url( null, '/newsletter/' ) ) );

		$component = array(
			'role'       => 'container',
			'layout'     => 'end-of-article-layout',
			'style'      => 'end-of-article-style',
			'components' => array(
				array(
					'role'      => 'heading3',
					'text'      => $heading,
					'textStyle' => 'default-heading-3-center',
					'layout'    => 'body-layout',
				),
				array(
					'role'      => 'body',
					'text'      => wp_kses_post( $text ),
					'format'    => 'html',
					'textStyle' => 'default-body-center',
					'layout'    => 'body-layout',
				),
				array(
					'role'      => 'link_button',
					'text'      => $button_text,
					'URL'       => $url,
					'style'     => 'default-link-button',
					'layout'    => 'link-button-layout',
					'textStyle' => 'default-link-button-text-style',
				),
			),
		);

		error_log( __METHOD__ . ' $component ' . print_r( $component, true ) );

		return $component;
	}

	/**
	 * Filter Before
	 *
	 * @param string $content
	 * @param int    $post_id
	 *
	 * @return void
	 */
	public function filter_apple_news_content_pre( $content, $post_id ) {
		$before_ids = get_option( 'options_before_content' );
		$after_ids  = get_option( 'options_after_content' );

		error_log( __METHOD__ . ' SNIPPET $before_ids = ' . print_r( $before_ids, true ) );
		error_log( __METHOD__ . ' SNIPPET $after_ids = ' . print_r( $after_ids, true ) );

		if ( empty( $before_ids ) && empty( $after_ids ) ) {
			return $content;
		}

		if ( is_array( $before_ids ) && ! empty( $before_ids ) ) {
			$before_html = self::render_block_patterns( $before_ids );
			$content     = '<p style="text-align:center">' . $before_html . '</p>' . $content;
		}

		if ( is_array( $after_ids ) && ! empty( $after_ids ) ) {
			$after_html = self::render_block_patterns( $after_ids );
			$content   .= '<p style="text-align:center">' . $after_html . '</p>';
		}

		return $content;
	}

	/**
	 * Get rendered HTML from block pattern IDs.
	 *
	 * @param array $ids Array of post IDs for block patterns.
	 * @return string Rendered HTML from all valid patterns.
	 */
	public static function render_block_patterns( array $ids ) {
		$html = '';

		foreach ( $ids as $id ) {
			$block = get_post( (int) $id );
			if ( $block && $block->post_type === 'wp_block' ) {
				$html .= do_blocks( $block->post_content );
			}
		}

		return $html;
	}
}
