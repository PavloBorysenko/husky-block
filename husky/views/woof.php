<?php
global $WOOF;
//Sort logic  for shortcode [woof] attr tax_only
if ( ! function_exists( 'get_order_by_tax_only' ) ) {

	function get_order_by_tax_only( $t_order, $t_only ) {
		$temp_array = array_intersect( $t_order, $t_only );
		$i = 0;
		foreach ( $temp_array as $key => $val ) {
			$t_order[ $key ] = $t_only[ $i ];
			$i++;
		}
		return $t_order;
	}

}
if ( ! function_exists( 'woof_print_item_by_key' ) ) {

	function woof_print_item_by_key( $key, $woof_settings ) {
		$title = "";
		$standard_filters = array(
			'by_price' => esc_html__( "Search by Price", 'woocommerce-products-filter' ),
			'by_rating' => esc_html__( "By rating drop-down", 'woocommerce-products-filter' ),
			'by_sku' => esc_html__( "Search by SKU", 'woocommerce-products-filter' ),
			'by_text' => esc_html__( "Search by Text", 'woocommerce-products-filter' ),
			'by_author' => esc_html__( "Search by Author", 'woocommerce-products-filter' ),
			'by_backorder' => esc_html__( "Exclude products on backorder", 'woocommerce-products-filter' ),
			'by_featured' => esc_html__( "Featured", 'woocommerce-products-filter' ),
			'by_instock' => esc_html__( "In stock", 'woocommerce-products-filter' ),
			'by_onsales' => esc_html__( "On sale", 'woocommerce-products-filter' ),
			'products_messenger' => esc_html__( "Products Messenger", 'woocommerce-products-filter' ),
			'query_save' => esc_html__( "Save search query", 'woocommerce-products-filter' ),
		);
		if ( isset( $woof_settings['meta_filter'] ) and isset( $woof_settings['meta_filter'][ $key ] ) ) {
			if ( isset( $woof_settings[ $key ]['show'] ) && $woof_settings[ $key ]['show'] != 0 ) {
				$title = $woof_settings['meta_filter'][ $key ]['title'];
			}
		} elseif ( isset( $standard_filters[ $key ] ) ) {
			$title = $standard_filters[ $key ];
		}
		if ( isset( woof()->settings[ $key ] ) and woof()->settings[ $key ]['show'] ) {
			?>
			<div
				class="woof_search_container woof_container  <?php echo esc_attr( WOOF_HELPER::generate_container_css_classes( $key ) ) ?>">
				<div class="woof_container_overlay_item"></div>
				<div class="woof_container_inner">
					<div class="woof_block_html_items">
						<div class="woof_mock_label">
							<?php echo esc_html( $title ) ?>
							[<?php echo esc_html( $key ) ?>]
						</div>
					</div>
				</div>
			</div>
			<?php
		}

	}

}
if ( ! function_exists( 'woof_print_tax' ) ) {

	function woof_print_tax( $tax_slug, $taxonomies_info, $woof_settings ) {
		$woof_container_styles = "";
		if ( isset( $woof_settings['tax_type'] ) && isset( $woof_settings['tax_type'][ $tax_slug ] ) && ( in_array( $woof_settings['tax_type'][ $tax_slug ], [ 'radio', 'checkbox', 'image', 'color', 'label' ] ) or substr( $woof_settings['tax_type'][ $tax_slug ], 0, 8 ) === 'woof_sd_' ) ) {
			if ( isset( woof()->settings['tax_block_height'] ) && isset( woof()->settings['tax_block_height'][ $tax_slug ] ) && intval( woof()->settings['tax_block_height'][ $tax_slug ] ) > 0 ) {
				$woof_container_styles = "max-height:" . sanitize_text_field( woof()->settings['tax_block_height'][ $tax_slug ] ) . "px; overflow-y: auto;";
			}
		}
		//***
		//https://wordpress.org/support/topic/adding-classes-woof_container-div
		$primax_class = sanitize_key( WOOF_HELPER::wpml_translate( $taxonomies_info[ $tax_slug ] ?? '' ) );
		?>
		<div data-css-class="woof_container_<?php echo esc_attr( $tax_slug ) ?>"
			class="woof_container woof_container_<?php echo esc_attr( isset( $woof_settings['tax_type'][ $tax_slug ] ) ? $woof_settings['tax_type'][ $tax_slug ] : '' ) ?> woof_container_<?php echo esc_attr( $tax_slug ) ?> woof_container_<?php echo esc_attr( 1 ) ?> woof_container_<?php echo esc_attr( $primax_class ) ?> <?php echo esc_attr( WOOF_HELPER::generate_container_css_classes( $tax_slug ) ) ?>">
			<div class="woof_container_overlay_item"></div>
			<div class="woof_container_inner woof_container_inner_<?php echo esc_attr( $primax_class ) ?>">
				<?php
				$css_classes = "woof_block_html_items";
				$show_toggle = 0;
				if ( isset( woof()->settings['show_toggle_button'][ $tax_slug ] ) ) {
					$show_toggle = (int) woof()->settings['show_toggle_button'][ $tax_slug ];
				}
				$tooltip_text = "";
				if ( isset( woof()->settings['tooltip_text'][ $tax_slug ] ) ) {
					$tooltip_text = woof()->settings['tooltip_text'][ $tax_slug ];
				}
				//***
				$block_is_closed = true;

				if ( $show_toggle === 1 ) {
					$css_classes .= " woof_closed_block";
				}

				if ( $show_toggle === 2 ) {
					$block_is_closed = false;
				}

				if ( in_array( $show_toggle, array( 1, 2 ) ) ) {
					$block_is_closed = apply_filters( 'woof_block_toggle_state', $block_is_closed );
					if ( $block_is_closed ) {
						$css_classes .= " woof_closed_block";
					} else {
						$css_classes = str_replace( 'woof_closed_block', '', $css_classes );
					}
				}

				if ( woof()->settings['show_title_label'][ $tax_slug ] ) {
					?>
					<<?php echo esc_html( apply_filters( 'woof_title_tag', 'h4' ) ); ?>>
						<?php echo esc_html( WOOF_HELPER::wpml_translate( $taxonomies_info[ $tax_slug ] ) ) ?>
						<?php WOOF_HELPER::draw_tooltipe( WOOF_HELPER::wpml_translate( $taxonomies_info[ $tax_slug ] ), $tooltip_text ) ?>
						<?php WOOF_HELPER::draw_title_toggle( $show_toggle, $block_is_closed ); ?>
					</<?php echo esc_html( apply_filters( 'woof_title_tag', 'h4' ) ); ?>>
					<?php
				}

				if ( ! empty( $woof_container_styles ) ) {
					$css_classes .= " woof_section_scrolled";
				}

				?>

				<div class="<?php echo esc_attr( $css_classes ) ?>" <?php if ( ! empty( $woof_container_styles ) ) : ?>style="<?php echo wp_kses_post( wp_unslash( $woof_container_styles ) ) ?>" <?php endif; ?>>
					<div class="woof_mock_label">
						<?php esc_html_e( 'Filter items: ', 'husky-block' ) ?>
						[<?php echo $woof_settings['tax_type'][ $tax_slug ] ?>]
					</div>
				</div>

			</div>
		</div>
		<?php
	}
}


if ( ! function_exists( 'woof_show_btn' ) ) {

	function woof_show_btn( $autosubmit = 1, $ajax_redraw = 0 ) {
		?>
		<div class="woof_submit_search_form_container">
			<?php
			if ( ! $autosubmit || $ajax_redraw ) {
				$woof_filter_btn_txt = get_option( 'woof_filter_btn_txt', '' );
				if ( empty( $woof_filter_btn_txt ) or woof()->show_notes ) {
					$woof_filter_btn_txt = esc_html__( 'Filter', 'woocommerce-products-filter' );
				}

				$woof_filter_btn_txt = WOOF_HELPER::wpml_translate( null, $woof_filter_btn_txt );
				?>
				<button class="button woof_submit_search_form"><?php esc_html_e( $woof_filter_btn_txt ) ?></button>
			<?php } ?>
		</div>
		<?php
	}

}
if ( $autohide ) { ?>
	<div class='woof_autohide_wrapper'>
		<?php
		//***
		$woof_auto_hide_button_txt = '';
		if ( isset( $WOOF->settings['woof_auto_hide_button_txt'] ) ) {
			$woof_auto_hide_button_txt = WOOF_HELPER::wpml_translate( null, $WOOF->settings['woof_auto_hide_button_txt'] );
		}
		?>
		<a href="javascript:void(0);" class="woof_show_auto_form woof_btn_default <?php if ( isset( $WOOF->settings['woof_auto_hide_button_img'] ) and $WOOF->settings['woof_auto_hide_button_img'] == 'none' )
			echo esc_attr( 'woof_show_auto_form_txt' ); ?>"><?php esc_html_e( $woof_auto_hide_button_txt ) ?></a><br />
		<!-------------------- inline css for js anim ----------------------->
	<div class="woof_auto_show woof_overflow_hidden" style="opacity: 0; height: 1px;">
		<div class="woof_auto_show_indent woof_overflow_hidden">
			<?php
}
;

$woof_class = "";
if ( wp_is_mobile() && ( isset( $mobile_mode ) && $mobile_mode == 1 ) && isset( $sid ) ) {
	$woof_class = 'woof_hide_filter';
}
?>

			<div class="woof woof_mock <?php if ( ! empty( $sid ) ) : ?>woof_sid woof_sid_<?php echo esc_attr( $sid ) ?><?php endif; ?> <?php echo esc_attr( $woof_class ) ?>"
				<?php if ( ! empty( $sid ) ) : ?>
				data-sid="
				<?php echo esc_attr( $sid ); ?>"
				<?php endif; ?>
				data-shortcode="
				<?php echo esc_html( WOOF_REQUEST::isset( 'woof_shortcode_txt' ) ? WOOF_REQUEST::get( 'woof_shortcode_txt' ) : 'woof' ) ?>"
				data-redirect="
				<?php echo esc_url( $redirect ) ?>"
				data-autosubmit="
				<?php echo esc_attr( $autosubmit ) ?>"
				data-ajax-redraw="
				<?php echo esc_attr( $ajax_redraw ) ?>"
				>
				<?php
				if ( wp_is_mobile() && ( isset( $mobile_mode ) && $mobile_mode ) && isset( $sid ) ) {
					$image_mb_open = ( isset( $WOOF->settings['image_mobile_behavior_open'] ) ) ? $WOOF->settings['image_mobile_behavior_open'] : '';
					$image_mb_close = ( isset( $WOOF->settings['image_mobile_behavior_close'] ) ) ? $WOOF->settings['image_mobile_behavior_close'] : '';
					if ( $image_mb_open != -1 && empty( $image_mb_open ) ) {
						$image_mb_open = WOOF_LINK . "img/open_filter.png";
					}
					if ( $image_mb_close != -1 && empty( $image_mb_close ) ) {
						$image_mb_close = WOOF_LINK . "img/close_filter.png";
					}
					$text_mb_open = ( isset( $WOOF->settings['text_mobile_behavior_open'] ) ) ? $WOOF->settings['text_mobile_behavior_open'] : esc_html__( 'Open filter', 'woocommerce-products-filter' );
					$text_mb_close = ( isset( $WOOF->settings['text_mobile_behavior_close'] ) ) ? $WOOF->settings['text_mobile_behavior_close'] : esc_html__( 'Close filter', 'woocommerce-products-filter' );
					?>
				<div class="woof_show_mobile_filter" data-sid="<?php echo esc_attr( $sid ); ?>">
					<?php if ( $image_mb_open != -1 ) : ?>
					<img src="<?php echo esc_url( $image_mb_open ); ?>" alt="">
					<?php endif; ?>
					<?php if ( $text_mb_open != -1 ) : ?>
					<span>
						<?php echo esc_html( WOOF_HELPER::wpml_translate( null, $text_mb_open ) ); ?>
					</span>
					<?php endif; ?>
				</div>
				<div class="woof_hide_mobile_filter">
					<?php if ( $image_mb_close != -1 ) : ?>
					<img src="<?php echo esc_url( $image_mb_close ); ?>" alt="">
					<?php endif; ?>
					<?php if ( $text_mb_close != -1 ) : ?>
					<span>
						<?php echo esc_html( WOOF_HELPER::wpml_translate( null, $text_mb_close ) ); ?>
					</span>
					<?php endif; ?>
				</div>
				<?php
				}
				?>


				<?php
				$woof_filter_class = "woof_redraw_zone";

				if ( isset( $filter_blur ) && intval( $filter_blur ) === 1 ) {
					$woof_filter_class .= " woof_blur_redraw_zone";
				}
				?>
				<!--- here is possible to drop html code which is never redraws by AJAX ---->
				<?php echo wp_kses_post( wp_unslash( apply_filters( 'woof_print_content_before_redraw_zone', '' ) ) ) ?>

				<div class="<?php echo esc_attr( $woof_filter_class ) ?>"
					data-woof-ver="<?php echo esc_attr( WOOF_VERSION ) ?>"
					data-icheck-skin="<?php echo esc_attr( woof()->settings['icheck_skin'] ) ?>">
					<?php echo wp_kses_post( wp_unslash( apply_filters( 'woof_print_content_before_search_form', '' ) ) ) ?>
					<?php
					if ( isset( $start_filtering_btn ) and (int) $start_filtering_btn == 1 ) {
						$start_filtering_btn = true;
					} else {
						$start_filtering_btn = false;
					}
					?>

					<?php if ( $start_filtering_btn ) { ?>
						<a href="#"
							class="<?php echo esc_attr( apply_filters( 'woof_button_css_classes', 'woof_button' ) ) ?> woof_start_filtering_btn"><?php echo wp_kses_post( $woof_start_filtering_btn_txt ) ?></a>
					<?php } else { ?>
						<?php
						if ( $btn_position == 't' or $btn_position == 'tb' or $btn_position == 'bt' ) {
							woof_show_btn( $autosubmit, $ajax_redraw );
						}
						//+++
						{


							//***
					
							$items_order = array();

							$taxonomies_keys = array_keys( $taxonomies );
							if ( isset( $woof_settings['items_order'] ) and ! empty( $woof_settings['items_order'] ) ) {
								$items_order = explode( ',', $woof_settings['items_order'] );
							} else {
								$items_order = array_merge( woof()->items_keys, $taxonomies_keys );
							}

							//*** lets check if we have new taxonomies added in woocommerce or new item
							foreach ( array_merge( woof()->items_keys, $taxonomies_keys ) as $key ) {
								if ( ! in_array( $key, $items_order ) ) {
									$items_order[] = $key;
								}
							}

							//lets print our items and taxonomies
							$counter = 0;

							if ( count( $tax_only ) > 0 ) {
								$items_order = get_order_by_tax_only( $items_order, $tax_only );
							}

							if ( isset( $by_step ) ) {
								$new_items_order = explode( ',', $by_step );
								$items_order = array_map( 'trim', $new_items_order );
							}

							$items_order = apply_filters( 'woof_custom_filter_items_order', $items_order, ( isset( $shortcode_atts['id'] ) ? $shortcode_atts['id'] : '' ) );

							$tax_show = array();
							if ( isset( $shortcode_atts['tax_only'] ) ) {
								$tax_show = explode( ',', $shortcode_atts['tax_only'] );
							}

							foreach ( $items_order as $key ) {

								do_action( 'woof_before_draw_filter', $key, $shortcode_atts );

								if ( in_array( $key, woof()->items_keys ) ) {
									if ( ( ! empty( $by_only ) && ! in_array( $key, $by_only ) ) || ( ! empty( $tax_exclude ) && in_array( $key, $tax_exclude ) ) ) {
										continue;
									}
									woof_print_item_by_key( $key, $woof_settings );
								} else {
									if ( ! isset( $woof_settings['tax'][ $key ] ) ) {
										continue;
									}

									if ( $key && isset( $taxonomies[ $key ] ) ) {

										if ( ( ! empty( $tax_only ) && ! in_array( $key, $tax_only ) ) || ( ! empty( $tax_exclude ) && in_array( $key, $tax_exclude ) ) ) {
											continue;
										}
										woof_print_tax( $key, $taxonomies_info, $woof_settings );
									}
								}
								do_action( 'woof_after_draw_filter', $key, $shortcode_atts );
								$counter++;
							}
						}
						?>


						<?php
						//submit form
						if ( $btn_position == 'b' or $btn_position == 'tb' or $btn_position == 'bt' ) {
							woof_show_btn( $autosubmit, $ajax_redraw );
						}
						?>

					<?php } ?>
				</div>

			</div>



			<?php if ( $autohide ) { ?>
			</div>
		</div>

	</div>
<?php } ?>