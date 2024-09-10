<?php

namespace Husky\Block\Shortcode;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class WOOFSimulation {
	public function __construct( private \WOOF $woof, private \Husky\Block\Shortcode\Template\WOOFShortcode $template ) {
		add_shortcode( 'woof_simulation', array( $this, 'woof_simulation' ) );
	}

	public function woof_simulation( $atts ) {
		$atts = wc_clean( $atts );
		$args = shortcode_atts( array(
			'mobile_mode' => 0,
			'sid' => 'simulation',
			'autohide' => 0,
			'redirect' => '',
			'start_filtering_btn' => 0,
			'woof_start_filtering_btn_txt' => apply_filters( 'woof_start_filtering_btn_txt',
				esc_html__( 'Show products filter form', 'woocommerce-products-filter' ) ),
			'ajax_redraw' => 0,
			'btn_position' => 'b',
			'by_step' => '',
			'tax_exclude' => '',
			'by_only' => '',
			'autosubmit' => -1
		), $atts );


		if ( $args['autosubmit'] == -1 ) {
			//takes data from global settings.
			$args['autosubmit'] = get_option( 'woof_autosubmit', 0 );
		}

		if ( empty( $args['by_step'] ) ) {
			unset( $args['by_step'] );
		}

		$args['tax_exclude'] = $this->string_attr_to_array( $args['tax_exclude'] );
		$args['by_only'] = $this->string_attr_to_array( $args['by_only'] );
		$args['tax_only'] = $this->get_tax_only( $atts );

		$this->override_tax_display( $args['tax_only'] );
		$this->override_by_only_display( $args['by_only'] );


		$args['woof_settings'] = $this->woof->settings;

		$args = array_merge( $args, $this->get_taxonomies( $args['woof_settings']['tax'] ) );

		if ( isset( $atts['items_order'] ) ) {
			$items_order = explode( ',', $atts['items_order'] );
			add_filter( 'woof_custom_filter_items_order', function ($order) use ($items_order) {
				return $items_order;
			} );
		}

		$args['shortcode_atts'] = $atts;

		$html = $this->template->render( apply_filters( 'woof_filter_shortcode_args', $args ) );
		return $html;

	}

	private function get_tax_only( array $atts ): array {

		$tax_only = array();

		if ( isset( $atts['tax_only'] ) ) {
			$tax_only = array_map( 'trim', explode( ',', $atts['tax_only'] ) );
		}

		return $tax_only;
	}

	private function override_tax_display( array $tax_only ): void {
		//overrides taxonomy display with shortcode attributes.
		foreach ( $tax_only as $tax_filter_key ) {
			if ( isset( $this->woof->settings['tax'][ $tax_filter_key ] ) ) {
				$this->woof->settings['tax'][ $tax_filter_key ] = 1;
			}
		}
	}
	private function override_by_only_display( array $by_only ): void {
		//overrides taxonomy display with shortcode attributes.
		foreach ( $by_only as $by_only_key ) {
			if ( ! isset( $this->woof->settings[ $by_only_key ] ) ) {
				continue;
			}
			$val = 1;
			//exclusive exception for price.
			if ( $by_only_key === 'by_price' ) {
				$val = intval( $this->woof->settings[ $by_only_key ]['show'] );
			}
			//hook woof_regulate_by_only_show is need here only for price filter because its view depends of this value: 1,2,3,4,5.
			$this->woof->settings[ $by_only_key ]['show'] = apply_filters( 'woof_regulate_by_only_show', $val, $by_only_key );
		}

	}
	private function get_taxonomies( array $allow_taxonomies ): array {
		$taxonomies = array();
		$all_taxonomies = $this->woof->get_taxonomies();
		if ( ! empty( $all_taxonomies ) ) {
			foreach ( $all_taxonomies as $tax_key => $tax ) {
				if ( ! in_array( $tax_key, array_keys( $allow_taxonomies ) ) ) {
					continue;
				}
				//+++
				$taxonomies['taxonomies_info'][ $tax_key ] = $tax;
				$taxonomies['taxonomies'][ $tax_key ] = $tax->label;
			}
		} else {
			$args['taxonomies'] = array();
		}
		return $taxonomies;
	}

	private function string_attr_to_array( string $attr ): array {

		if ( empty( $attr ) ) {
			return array();
		}

		$attr_array = explode( ',', $attr );
		$attr_array = array_map( 'trim', $attr_array );

		return $attr_array;
	}

}