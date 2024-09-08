<?php
/**
 * Test_Husky_Front.
 *
 * @package Husky_Block
 */

/**
 * Test_Husky_Front.
 */
class Test_Husky_Front extends WP_UnitTestCase {
	public function test_shortcode_name() {
		$shortcode_front = new \Husky\Block\Render\Shortcodes\HuskyFront( array() );

		$shortcode_front_reflected = new \ReflectionClass( $shortcode_front );
		$shortcode_name_reflection = $shortcode_front_reflected->getProperty( 'shortcode_name' );
		$shortcode_name_reflection->setAccessible( true );
		$shortcode_name = $shortcode_name_reflection->getValue( $shortcode_front );

		$this->assertSame( 'woof', $shortcode_name );
	}

}