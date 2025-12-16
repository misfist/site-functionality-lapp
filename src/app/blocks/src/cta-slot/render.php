<?php
/**
 * PHP file to use when rendering the block type on the server to show on the front end.
 *
 * The following variables are exposed to the file:
 *     $attributes (array): The block attributes.
 *     $content (string): The block default content.
 *     $block (WP_Block): The block instance.
 *
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */
use Site_Functionality\App\Admin\Admin_Settings;

$option_name   = Admin_Settings::$cta_pattern_option;
$cta_pattern_id = get_option( 'options_' . $option_name );
if ( ! $cta_pattern_id ) {
	return '';
}

$cta = get_post( (int) $cta_pattern_id );
if ( ! $cta || is_wp_error( $cta ) ) {
	return '';
}

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'id' => $attributes['anchor'],
	)
);

$cta_content = $cta->post_content;
?>

<div <?php echo $wrapper_attributes; ?>>
	<?php echo do_blocks( $cta_content ); ?>
</div>
