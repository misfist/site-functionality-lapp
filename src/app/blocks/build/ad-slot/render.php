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

$option_name   = Admin_Settings::$ad_pattern_option;
$ad_pattern_id = get_option( 'options_' . $option_name );
if ( ! $ad_pattern_id ) {
	return '';
}

$ad = get_post( (int) $ad_pattern_id );
if ( ! $ad || is_wp_error( $ad ) ) {
	return '';
}

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'id' => $attributes['anchor'],
	)
);

$ad_content = $ad->post_content;
?>

<div <?php echo $wrapper_attributes; ?>>
	<?php echo do_blocks( $ad_content ); ?>
</div>
