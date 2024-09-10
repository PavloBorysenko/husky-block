<?php
/**
 * Test_WOOF_Simulation.
 *
 * @package Husky_Block
 */

/**
 * Test_WOOF_Simulation.
 */
class Test_WOOF_Simulation extends WP_UnitTestCase {

	public static $woof_simulation;

	public static function wpSetUpBeforeClass( $factory ) {
		require_once HUSKY_BLOCK_TEST_PATH . '/Fakes/WoofFake.php';
		$WoofFake = new WoofFake();
		$woof_template = new \Husky\Block\Shortcode\Template\WOOFShortcode( $WoofFake, '\HELPER_FAKE' );

		self::$woof_simulation = new Husky\Block\Shortcode\WOOFSimulation( $WoofFake, $woof_template );
	}
	public static function wpTearDownAfterClass() {
	}

	/**
	 * @dataProvider get_tax_only_attr_provider
	 */
	public function test_get_tax_only( $attrs, $expected_tax ) {
		$method = $this->make_private_to_public( 'get_tax_only' );
		$actual_tax = $method->invoke( self::$woof_simulation, $attrs );

		$this->assertSameSets( $expected_tax, $actual_tax );
	}

	/**
	 * @dataProvider get_override_tax_provider
	 */
	public function test_override_tax_display( $tax_only, $expected_tax ) {
		$method = $this->make_private_to_public( 'override_tax_display' );
		$method->invoke( self::$woof_simulation, $tax_only );

		$property_woof = $this->make_private_property_to_public( 'woof' );

		$show = $property_woof->settings['tax']['test_tax_2'];

		$this->assertSame( $expected_tax, $show );
		//reset property
		$property_woof->settings['tax']['test_tax_2'] = 0;

	}

	/**
	 * @dataProvider get_override_by_only_provider
	 */
	public function test_override_by_only_display( $by_only, $expected_val ) {

		$method = $this->make_private_to_public( 'override_by_only_display' );

		$method->invoke( self::$woof_simulation, $by_only );

		$property_woof = $this->make_private_property_to_public( 'woof' );

		$show = $property_woof->settings['test_slug_2']['show'];

		$this->assertSame( $expected_val, $show );
		//reset property
		$property_woof->settings['test_slug_2']['show'] = 0;
	}

	/**
	 * @dataProvider get_string_attr_provider
	 */
	public function test_string_attr_to_array( $string, $expected_array ) {

		$method = $this->make_private_to_public( 'string_attr_to_array' );

		$actual_array = $method->invoke( self::$woof_simulation, $string );
		$this->assertSameSets( $expected_array, $actual_array );
	}

	public function get_override_tax_provider(): array {
		return array(
			'empty' => array( array(), 0 ),
			'normal' => array( array( 'test_tax_2' ), 1 ),
			'wrong' => array( array( 'some_slug', 'another slag' ), 0 )
		);
	}

	public function get_tax_only_attr_provider(): array {
		return array(
			'empty' => array( array(), array() ),
			'normal' => array( array( 'tax_only' => 'test_tax, test_tas_2' ), array( 'test_tax', 'test_tas_2' ) ),
			'wrong' => array( array( 'tax_only' => 'querty!@#$%' ), array( 'querty!@#$%' ) )
		);
	}

	public function get_override_by_only_provider(): array {
		return array(
			'empty' => array( array(), 0 ),
			'normal' => array( array( 'test_slug_2' ), 1 ),
			'wrong' => array( array( 'some_slug', 'another slag' ), 0 )
		);
	}

	public function get_string_attr_provider(): array {

		return array(
			'normal' => array( 'test, test1, test2', array( 'test', 'test1', 'test2' ) ),
			'empty' => array( '', array() ),
			'wrong' => array( 'test_!&*^', array( 'test_!&*^' ) ),
		);
	}

	public function make_private_to_public( string $method_name ) {
		$woof_simulation_reflection = new ReflectionClass( self::$woof_simulation );
		$method = $woof_simulation_reflection->getMethod( $method_name );
		$method->setAccessible( true );

		return $method;
	}
	public function make_private_property_to_public( string $property_name ) {
		$woof_simulation_reflection = new ReflectionClass( self::$woof_simulation );
		$property = $woof_simulation_reflection->getProperty( $property_name );
		$property->setAccessible( true );
		return $property->getValue( self::$woof_simulation );
	}
}
