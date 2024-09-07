<?php

namespace Husky\Block\Render\Shortcodes\Abstract\WOOF;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

abstract class Assembling implements \Husky\Block\Render\Shortcodes\Abstract\Shortcode {

	protected string $shortcode_name = '';

	private array $allowed_attributs = array();

	private array $allowed_sid = array();

	public function __construct( private array $attributes ) {

		$this->allowed_attributs = array(
			'autosubmit',
			'dynamic_recount',
			'by_only',
			'tax_only',
			'tax_exclude',
			'taxonomies',
			'is_ajax',
			'ajax_redraw',
			'hide_terms_count_txt',
			'redirect',
			'autohide',
			'start_filtering_btn',
			'btn_position',
			'mobile_mode',
			'conditionals'
		);

		$this->allowed_sid = array(
			'columns_lg',
			'columns_md',
			'columns_sm',
			'max_height'
		);


	}
	public function draw(): void {
		echo do_shortcode( '[' . esc_attr( $this->shortcode_name ) . ' ' .
			esc_html( $this->get_atts_sting() ) .
			'sid="' . esc_attr( $this->get_sid_string() ) .
			'" ]' );
	}

	protected function get_atts_sting(): string {

		$atts = $this->get_allowed_atts();

		$shortcode_data = " ";

		foreach ( $atts as $key => $value ) {

			if ( is_array( $value ) && count( $value ) ) {
				$shortcode_data .= $key . '=' . implode( ',', $value ) . ' ';
			} elseif ( $value === "0" || ! empty( $value ) ) {
				$shortcode_data .= $key . '=' . $value . ' ';
			}

		}

		return $shortcode_data . " ";
	}

	protected function get_sid_string(): string {

		$sids = array( 'husky-block' );

		$allowed_sid = $this->get_allowed_sid();

		foreach ( $allowed_sid as $key => $value ) {
			if ( ! empty( $value ) ) {
				$sids[] = $key . "_" . $value;
			}
		}
		$sid_string = implode( ' ', $sids );
		return $sid_string;
	}

	protected function get_allowed_atts(): array {
		return $this->get_allowed_data( $this->allowed_attributs );
	}
	protected function get_allowed_sid(): array {
		return $this->get_allowed_data( $this->allowed_sid );
	}
	private function get_allowed_data( array $allowed ): array {
		$data = array();
		foreach ( $allowed as $key ) {
			if ( isset( $this->attributes[ $key ] ) ) {
				$data[ $key ] = $this->attributes[ $key ];
			}
		}
		return $data;
	}
}