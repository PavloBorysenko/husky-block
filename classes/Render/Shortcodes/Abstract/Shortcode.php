<?php

namespace Husky\Block\Render\Shortcodes\Abstract;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The Shortcode interface declares the main execution method.
 */
interface Shortcode {
	public function draw(): void;

}