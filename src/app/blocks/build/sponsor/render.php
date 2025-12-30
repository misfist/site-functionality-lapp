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
// use function Lapp_Classic\render_sponsors_block;
use function Site_Functionality\App\Blocks\render_sponsors_block;

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'className' => 'entry-meta entry-sponsor'
	)
);

?>
<div <?php echo $wrapper_attributes; ?>>
	<?php Lapp_Classic\render_sponsors_block( $attributes, $content, $block ); ?>
</div>