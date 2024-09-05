<?php
/**
 * Husky\Block\Editor.
 */

namespace Husky\Block\Editor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Ajax {

	/**
	 * __construct
	 *
	 * @param  \WOOF $woof object of the main filter class to get data.
	 * @return void
	 */
	public function __construct( private \WOOF $woof ) {
		if ( ! $woof ) {
			return;
		}
		// init ajax actions.
		add_action( 'wp_ajax_get_all_by_items', array( $this, 'send_by_items' ), 20 );

		add_action( 'wp_ajax_get_all_tax_items', array( $this, 'send_tax_items' ), 20 );
	}
	public function send_by_items(): void {
		wp_die( json_encode( $this->prepare_simply_data( $this->get_by_items() ) ) );
		//wp_send_json( $this->prepare_simply_data( $this->get_by_items() ) );
	}
	public function send_tax_items(): void {
		wp_die( json_encode( $this->prepare_taxanomy_data( $this->get_tax_items() ) ) );
		//wp_send_json( $this->prepare_taxanomy_data( $this->get_tax_items() ) );
	}
	private function get_by_items(): array {

		return isset( $this->woof->items_keys ) ? $this->woof->items_keys : array();
	}
	private function get_tax_items(): array {

		return method_exists( $this->woof, 'get_taxonomies' ) ? $this->woof->get_taxonomies() : array();
	}
	private function prepare_simply_data( array $data ): array {

		$prepared_data = array();
		foreach ( $data as $item ) {
			$prepared_data[] = array(
				'label' => $item,
				'value' => $item
			);
		}

		return $prepared_data;
	}
	private function prepare_taxanomy_data( array $taxonomies ): array {

		$prepared_taxonomies = array();
		foreach ( $taxonomies as $tax_key => $tax_obj ) {
			$prepared_taxonomies[] = array(
				'label' => $tax_obj->label,
				'value' => $tax_key

			);
		}

		return $prepared_taxonomies;
	}
}