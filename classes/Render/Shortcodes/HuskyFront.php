<?php
namespace Husky\Block\Render\Shortcodes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class HuskyFront extends \Husky\Block\Render\Shortcodes\Abstract\WOOF\Assembling {
	protected string $shortcode_name = 'woof';
}