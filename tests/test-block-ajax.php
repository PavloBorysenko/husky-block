<?php
/**
 *  !!!! ONLY FOR AJAX CALL !!!!
 *  
 * Tests for the Ajax calls to save and get filter items name. 
 * 
 * @group ajax
 */
class Test_block_Ajax extends WP_Ajax_UnitTestCase {


	public $ajax;

	public function setup(): void {
		parent::setup();


		$WOOFMock = $this->getMockBuilder( 'WOOF' )
			->setMethods( [ 'get_taxonomies' ] )
			->disableOriginalConstructor()
			->getMock();
		$WOOFMock->items_keys = array( 'by_price', 'by_text', 'by_author' );

		$test_taxonomies['product_cat'] = new stdClass();

		$test_taxonomies['product_cat']->label = "Category";

		$test_taxonomies['pa_color'] = new stdClass();

		$test_taxonomies['pa_color']->label = "Color";

		$WOOFMock->method( 'get_taxonomies' )->willReturn( $test_taxonomies );

		$this->ajax = new Husky\Block\Editor\Ajax( $WOOFMock );

		wp_set_current_user( 1 );
	}

	public function teardown(): void {
		parent::teardown();
	}

	public function test_send_by_items() {
		try {
			$this->_handleAjax( 'get_all_by_items' );
		} catch (WPAjaxDieStopException $e) {
		}
		$actual_array = json_decode( $e->getMessage(), true );
		$expected_array = array(
			array(
				'label' => 'by_price',
				'value' => 'by_price'
			),
			array(
				'label' => 'by_text',
				'value' => 'by_text'
			),
			array(
				'label' => 'by_author',
				'value' => 'by_author'
			)
		);
		$this->assertEqualSets( $expected_array, $actual_array );

	}
	public function test_send_tax_items() {
		try {
			$this->_handleAjax( 'get_all_tax_items' );
		} catch (WPAjaxDieStopException $e) {
		}
		$actual_array = json_decode( $e->getMessage(), true );
		$expected_array = array(
			array(
				'label' => 'Category',
				'value' => 'product_cat'
			),
			array(
				'label' => 'Color',
				'value' => 'pa_color'
			),
		);
		$this->assertEqualSets( $expected_array, $actual_array );

	}
}