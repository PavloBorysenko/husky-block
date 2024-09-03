<?php
/**
 * Test_Husky_Block
 *
 * @package Husky_Block
 */

/**
 * Test_Husky_Block.
 */
class Test_Husky_Block extends WP_UnitTestCase {

	public function test_path_constant() {
		$path = str_replace( DIRECTORY_SEPARATOR .
			'tests', '',
			plugin_dir_path( __FILE__ ) );
		$this->assertSame( $path, HUSKY_BLOCK_PATH,
			"HUSKY_BLOCK_PATH constant does not match the actual plugin path." );
	}

	public function test_class_included() {

		$this->assertTrue( class_exists( 'Husky\Block\Shortcode\WOOFSimulation' ) );

		$this->assertTrue( class_exists( 'Husky\Block\Shortcode\Template\WOOFShortcode' ) );

		$this->assertTrue( class_exists( 'Husky\Block\Editor\Ajax' ) );

		$this->assertTrue( class_exists( 'Husky\Block\Render\Shortcodes\HuskyFront' ) );

		$this->assertTrue( class_exists( 'Husky\Block\Render\Shortcodes\HuskySimulation' ) );

		$this->assertTrue( class_exists( 'Husky\Block\Render\ShortcodeRendering' ) );
	}

	public function test_init_admin_action() {
		$this->assertTrue(
			has_action( 'admin_init', 'init_husky_block_ajax' ) == true,
			"The object Husky\Block\Editor\Ajax was not created in the init hook." );
	}

	public function test_init_block_action() {
		$this->assertTrue(
			has_action( 'init', 'create_block_husky_block_init' ) == true,
			"Block not registered" );
	}
}