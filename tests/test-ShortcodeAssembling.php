<?php
/**
 * Test_Shortcode_Assembling.
 *
 * @package Husky_Block
 */

/**
 * Test_Shortcode_Assembling.
 */
class Test_Shortcode_Assembling extends WP_UnitTestCase {


	public function test_shortcode_draw() {
		$attrs = array(
			'autosubmit' => 1,
			'max_height' => '300',
			'tax_exclude' => 'pa_color,pa_test'
		);

		$shortcode_mock = $this->getMockForAbstractClass(
			'\Husky\Block\Render\Shortcodes\Abstract\WOOF\Assembling',
			array( $attrs )
		);

		ob_start();
		$shortcode_mock->draw();
		$shortcode_string = ob_get_clean();
		$expected_string = '[  autosubmit=1 tax_exclude=pa_color,pa_test  sid="husky-block max_height_300" ]';

		$this->assertSame( $expected_string, $shortcode_string );
	}

	/**
	 * @dataProvider get_attrs_data_provider
	 */
	public function test_allowed_atts_with_string( $attrs, $expected_attrs, $expected_string ) {

		$shortcode_mock = $this->getMockForAbstractClass(
			'\Husky\Block\Render\Shortcodes\Abstract\WOOF\Assembling',
			array( $attrs )
		);

		$shortcode_mock_reflection = new ReflectionClass( $shortcode_mock );

		$method_get_allowed_atts = $shortcode_mock_reflection->getMethod( 'get_allowed_atts' );
		$method_get_allowed_atts->setAccessible( true );
		$actual_attrs = $method_get_allowed_atts->invoke( $shortcode_mock );

		$method_get_atts_sting = $shortcode_mock_reflection->getMethod( 'get_atts_sting' );
		$method_get_atts_sting->setAccessible( true );
		$actual_string = $method_get_atts_sting->invoke( $shortcode_mock );

		$this->assertSameSets( $expected_attrs, $actual_attrs );
		$this->assertSame( $expected_string, $actual_string );

	}
	/**
	 * @dataProvider get_sid_data_provider
	 */
	public function test_allowed_sid_with_string( $attrs, $expected_sid, $expected_string ) {

		$shortcode_mock = $this->getMockForAbstractClass(
			'\Husky\Block\Render\Shortcodes\Abstract\WOOF\Assembling',
			array( $attrs )
		);

		$shortcode_mock_reflection = new ReflectionClass( $shortcode_mock );

		$method_get_allowed_sid = $shortcode_mock_reflection->getMethod( 'get_allowed_sid' );
		$method_get_allowed_sid->setAccessible( true );
		$actual_sid = $method_get_allowed_sid->invoke( $shortcode_mock );

		$method_get_sid_string = $shortcode_mock_reflection->getMethod( 'get_sid_string' );
		$method_get_sid_string->setAccessible( true );
		$actual_string = $method_get_sid_string->invoke( $shortcode_mock );

		$this->assertSameSets( $expected_sid, $actual_sid );
		$this->assertSame( $expected_string, $actual_string );

	}


	public function get_attrs_data_provider(): array {
		return array(
			'empty' => array(
				array(),
				array(),
				'  '
			),
			'wrong attrs' => array(
				array( 1, 'test' => 'test', null, new stdClass() ),
				array(),
				'  '
			),
			'normal' => array(
				array( 'autosubmit' => 1, 'btn_position' => 'tb', 'max_height' => '300', 'tax_exclude' => 'pa_color,pa_test' ),
				array( 'autosubmit' => 1, 'btn_position' => 'tb', 'tax_exclude' => 'pa_color,pa_test' ),
				' autosubmit=1 tax_exclude=pa_color,pa_test btn_position=tb  '
			)
		);
	}
	public function get_sid_data_provider(): array {
		return array(
			'empty' => array(
				array(),
				array(),
				'husky-block'
			),
			'wrong attrs' => array(
				array( 1, 'test' => 'test', null, new stdClass() ),
				array(),
				'husky-block'
			),
			'normal' => array(
				array( 'columns_lg' => 1, 'btn_position' => 'tb', 'max_height' => '300', 'tax_exclude' => 'pa_color' ),
				array( 'columns_lg' => 1, 'max_height' => '300' ),
				'husky-block columns_lg_1 max_height_300'
			)
		);
	}
}