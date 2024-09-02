<?php
/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<div <?php echo get_block_wrapper_attributes(); ?>>
	<?php
	if ( isset( $_GET['context'] ) && 'edit' == $_GET['context'] ) {
		$shortcode = new \Husky\Block\Render\Shortcodes\HuskySimulation( $attributes );
	} else {
		$shortcode = new \Husky\Block\Render\Shortcodes\HuskyFront( $attributes );
	}

	$shortcode_rendering = new \Husky\Block\Render\ShortcodeRendering( $shortcode, $attributes );

	$shortcode_rendering->render();

	?>
</div>