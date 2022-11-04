<?php
/**
 * Block Name: Site Screenshot
 * Description: Display a screenshot of the site.
 *
 * @package wporg
 */

namespace WordPressdotorg\Theme\Showcase_2022\Site_Screenshot;

use function WordPressdotorg\Theme\Showcase_2022\site_screenshot_src;

add_action( 'init', __NAMESPACE__ . '\init' );

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function init() {
	register_block_type(
		dirname( dirname( __DIR__ ) ) . '/build/site-screenshot',
		array(
			'render_callback' => __NAMESPACE__ . '\render',
		)
	);
}

/**
 * Render the block content.
 *
 * @param array    $attributes Block attributes.
 * @param string   $content    Block default content.
 * @param WP_Block $block      Block instance.
 *
 * @return string Returns the block markup.
 */
function render( $attributes, $content, $block ) {
	if ( ! isset( $block->context['postId'] ) ) {
		return '';
	}

	$post_ID = $block->context['postId'];
	$post = get_post( $post_ID );
	$width = 1320;
	$height = 670;

	$screenshot = site_screenshot_src( $post );
	$screenshot = add_query_arg( 'vpw', $width, $screenshot );
	$screenshot = add_query_arg( 'vph', $height, $screenshot );
	$srcset = add_query_arg( 'vpw', $width * 2, $screenshot );
	$srcset = add_query_arg( 'vph', $height * 2, $srcset );

	$img_content = "<img src='{$screenshot}' srcset='$srcset 2x' alt='" . the_title_attribute( array( 'echo' => false ) ) . "' />";

	$wrapper_attributes = get_block_wrapper_attributes();
	return sprintf(
		'<div %s>%s</div>',
		$wrapper_attributes,
		$img_content
	);
}
