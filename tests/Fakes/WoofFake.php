<?php

interface WOOF {
	public function get_taxonomies(): array;

}

class WoofFake implements WOOF {
	public $settings = array(
		'woof_auto_hide_button_txt' => 'Hide button text',
		'tooltip_text' => array(
			'test_slug' => 'test tooltip text'
		),
		'show_toggle_button' => array(
			'test_slug' => 1,
			'test_slug_2' => 0,
		),
	);
	public $items_keys = array( 'by_price', 'by_text', 'by_author' );
	public function get_taxonomies(): array {
		$test_taxonomies['product_cat'] = new stdClass();

		$test_taxonomies['product_cat']->label = "Category";

		$test_taxonomies['pa_color'] = new stdClass();

		$test_taxonomies['pa_color']->label = "Color";

		return $test_taxonomies;
	}
}