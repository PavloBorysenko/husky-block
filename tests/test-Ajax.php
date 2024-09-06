<?php
/**
 * Test_Ajax
 *
 * @package Husky_Block
 */

require_once HUSKY_BLOCK_TEST_PATH . '/Fakes/WoofFake.php';
/**
 * Test_Ajax.
 */
class Test_Ajax extends WP_UnitTestCase {
	public $ajax;

	public $ajax_reflection;

	public function setup(): void {
		parent::setup();

		$this->ajax = new Husky\Block\Editor\Ajax( new WoofFake() );
		$this->ajax_reflection = new ReflectionClass( $this->ajax );

	}

	public function teardown(): void {
		parent::teardown();
	}


	public function test_get_by_items() {

		$method = $this->make_private_to_public( 'get_by_items' );

		$result = $method->invoke( $this->ajax );

		$expected_array = array( 'by_price', 'by_text', 'by_author' );

		$this->assertEqualSets( $expected_array, $result );
	}
	public function test_get_tax_items() {

		$method = $this->make_private_to_public( 'get_tax_items' );
		$result = $method->invoke( $this->ajax );

		$expected_array['product_cat'] = new stdClass();
		$expected_array['product_cat']->label = "Category";

		$expected_array['pa_color'] = new stdClass();
		$expected_array['pa_color']->label = "Color";

		$this->assertEqualSets( $expected_array, $result );
	}
	/**
	 * @dataProvider get_simply_data_provider
	 */
	public function test_prepare_simply_data( $data, $expected_data ) {

		$method = $this->make_private_to_public( 'prepare_simply_data' );

		$result = $method->invoke( $this->ajax, $data );

		$this->assertEqualSets( $expected_data, $result,
			'The function does not handle items correctly.' );
	}

	/**
	 * @dataProvider get_taxonomy_data_provider
	 */
	public function test_prepare_taxonomy_data( $data, $expected_data ) {

		$method = $this->make_private_to_public( 'prepare_taxonomy_data' );

		$result = $method->invoke( $this->ajax, $data );

		$this->assertEqualSets( $expected_data, $result,
			'The function does not handle taxonomy objects correctly.' );
	}

	public function make_private_to_public( string $method_name ) {
		$method = $this->ajax_reflection->getMethod( $method_name );
		$method->setAccessible( true );

		return $method;
	}

	public function get_taxonomy_data_provider(): array {

		$product_cat_fake = new stdClass();
		$product_cat_fake->label = "Category";

		$pa_color_fake = new stdClass();
		$pa_color_fake->label = "Color";

		$test_normal_data = array(
			array(
				'label' => 'Category',
				'value' => 'product_cat'
			),
			array(
				'label' => 'Color',
				'value' => 'pa_color'
			)
		);

		return array(
			'empty' => array(
				array(),
				array()
			),
			'normal' => array(
				array(
					'product_cat' => $product_cat_fake,
					'pa_color' => $pa_color_fake
				),
				$test_normal_data,
			),
			'wrong data' => array(
				array(
					'product_cat' => $product_cat_fake,
					null,
					'test' => new stdClass()
				),
				array(
					array(
						'label' => 'Category',
						'value' => 'product_cat'
					)
				)
			),
		);

	}

	public function get_simply_data_provider(): array {

		$test_data = array();
		$test_data['label'] = 'by_price';
		$test_data['value'] = 'by_price';

		return array(
			'empty' => array(
				array(),
				array()
			),
			'normal' => array(
				array(
					'by_price'
				),
				array(
					$test_data
				)
			),
			'wrong data' => array(
				array(
					'by_price',
					null,
				),
				array(
					$test_data
				)
			),
		);
	}
}