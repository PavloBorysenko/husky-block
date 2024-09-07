<?php
/**
 * Test_Shortcode_Rendering
 *
 * @package Husky_Block
 */

/**
 * Test_Shortcode_Rendering.
 */
class Test_Shortcode_Rendering extends WP_UnitTestCase {

	public function test_shortcode_is_drawn() {

		$shortcode = $this->createMock( '\Husky\Block\Render\Shortcodes\Abstract\Shortcode' );

		$shortcode->expects( $this->once() )->method( 'draw' );

		$shortcode_rendering = new \Husky\Block\Render\ShortcodeRendering( $shortcode, array( 'padding' => 10 ) );
		ob_start();
		$shortcode_rendering->render();
		$shortcode_html = ob_end_clean();
	}
	public function test_get_padding_with_empty_args() {

		$shortcode = $this->createMock( '\Husky\Block\Render\Shortcodes\Abstract\Shortcode' );
		$shortcode_rendering = new \Husky\Block\Render\ShortcodeRendering( $shortcode, array() );

		$shortcode_reflection = new ReflectionClass( $shortcode_rendering );
		$method = $shortcode_reflection->getMethod( 'get_padding' );
		$method->setAccessible( true );

		$actual_padding = $method->invoke( $shortcode_rendering );
		$expected_padding = 'padding: 0px;';

		$this->assertSame( $expected_padding, $actual_padding );
	}
	public function test_get_style() {

		$shortcode = $this->createMock( '\Husky\Block\Render\Shortcodes\Abstract\Shortcode' );
		$shortcode_rendering = new \Husky\Block\Render\ShortcodeRendering( $shortcode, array( 'padding' => 10 ) );

		$shortcode_reflection = new ReflectionClass( $shortcode_rendering );
		$method = $shortcode_reflection->getMethod( 'get_styles' );
		$method->setAccessible( true );

		$actual_style = $method->invoke( $shortcode_rendering );
		$expected_style = 'padding: 10px;';

		$this->assertSame( $expected_style, $actual_style );
	}
}