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
		add_filter( 'apple_news_component_text_styles', array( $this, 'component_text_styles' ) );

		add_filter( 'apple_news_component_styles', array( $this, 'component_styles' ) );

		add_filter( 'apple_news_component_layouts', array( $this, 'component_layouts' ) );

		add_filter( 'apple_news_in_article_json', array( $this, 'modify_in_article_component' ) );

		add_filter( 'apple_news_end_of_article_json', array( $this, 'modify_end_of_article_component' ) );

		add_filter( 'apple_news_exporter_content_pre', array( $this, 'filter_apple_news_content_pre' ), 10, 2 );
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
		$heading     = get_option( 'in_article_heading', __( '', 'lapp' ) );
		$text        = get_option( 'in_article_text', __( '', 'lapp' ) );
		$button_text = get_option( 'in_article_button_text', esc_html__( 'Support Our Work', 'lapp' ) );
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
		$heading     = get_option( 'end_of_article_heading', __( 'Subscribe to LA Public Press', 'lapp' ) );
		$text        = get_option( 'end_of_article_text', __( 'The best way to keep up with Los Angeles Public Press is to subscribe to our weekly newsletter.', 'lapp' ) );
		$button_text = get_option( 'end_of_article_button_text', esc_html__( 'Sign Up', 'lapp' ) );
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
	 * @param int $post_id
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
