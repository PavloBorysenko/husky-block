<?php

namespace Husky\Block\Shortcode\Template\Abstract;

/**
 * The Shortcode interface declares the main execution method.
 */
interface Shortcode {
	public function render( array $args ): string;

}