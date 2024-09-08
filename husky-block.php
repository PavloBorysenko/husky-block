<?php
/**
 * Plugin Name:       Husky Block
 * Description:       Block for husky filter plugin.
 * Requires at least: 6.1
 * Requires PHP:      7.0
 * Version:           0.2.1
 * Author:            Pablo
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       husky-block
 *
 * @package CreateBlock
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

require 'vendor/autoload.php';

define( 'HUSKY_BLOCK_PATH', plugin_dir_path( __FILE__ ) );

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function create_block_husky_block_init() {
	if ( class_exists( 'WOOF' ) ) {
		new Husky\Block\Shortcode\WOOFSimulation( woof(), new \Husky\Block\Shortcode\Template\WOOFShortcode( woof(), '\WOOF_HELPER' ) );
	}

	register_block_type( __DIR__ . '/build' );

}
add_action( 'init', 'create_block_husky_block_init' );


function init_husky_block_ajax() {
	if ( class_exists( 'WOOF' ) ) {
		new Husky\Block\Editor\Ajax( new \WOOF() );
	}
}
add_action( 'admin_init', 'init_husky_block_ajax' );

