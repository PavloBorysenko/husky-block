<?php
/**
 *  !!!! ONLY FOR AJAX CALL !!!!
 *  
 * Tests for the Ajax calls to save and get filter items name. 
 * 
 * @group ajax
 */
require_once HUSKY_BLOCK_TEST_PATH . '/Fakes/WoofFake.php';
class Test_Ajax_Request extends WP_Ajax_UnitTestCase {

	public $ajax;

	public function setup(): void {
		parent::setup();

		$this->ajax = new Husky\Block\Editor\Ajax( new WoofFake() );

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