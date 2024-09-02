<?php

namespace Husky\Block\Shortcode\Template;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class WOOFShortcode implements \Husky\Block\Shortcode\Template\Abstract\Shortcode {

	private array $standard_filters = array();
	private string $views_path = '';

	private array $args = array();
	public function __construct( private \WOOF $woof ) {
		$this->standard_filters = array(
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

		$this->views_path = HUSKY_BLOCK_PATH . 'husky/views/';
	}
	public function set_args( array $args ): void {
		$this->args = $args;
	}

	public function render( $args ): string {

		$this->set_args( $args );

		ob_start();

		$this->draw_filter_form();

		return ob_get_clean();
	}
	private function draw_filter_form(): void {
		$this->print_autohide_wrapper_start();

		$this->draw_filter_container();

		$this->print_autohide_wrapper_end();
	}

	private function draw_filter_container(): void {
		$this->print_filter_countainer_start();

		$this->print_mobile_btns();

		$this->draw_redraw_zone();

		$this->print_filter_countainer_end();
	}

	private function draw_redraw_zone(): void {

		$this->print_redraw_zone_start();

		$this->draw_filters();

		$this->print_redraw_zone_end();

	}

	private function draw_filters(): void {
		if ( isset( $this->args['start_filtering_btn'] ) and (int) $this->args['start_filtering_btn'] == 1 ) {
			$start_filtering_btn = true;
		} else {
			$start_filtering_btn = false;
		}

		if ( $start_filtering_btn ) {
			$this->print_start_filtering_btn();
		} else {
			$this->draw_filter_items();
		}
	}
	private function draw_filter_items(): void {

		$this->draw_submit_btn( [ 't', 'tb', 'bt' ] );


		$items_order = $this->get_all_items();

		foreach ( $items_order as $key ) {

			do_action( 'woof_before_draw_filter', $key, $this->args['shortcode_atts'] );

			$this->draw_filter_item( $key );

			do_action( 'woof_after_draw_filter', $key, $this->args['shortcode_atts'] );

		}

		$this->draw_submit_btn( [ 'b', 'tb', 'bt' ] );
	}
	private function draw_filter_item( $key ): void {

		if ( in_array( $key, $this->woof->items_keys ) ) {

			if ( $this->is_allowed_item( $key, $this->args['by_only'] ) ) {
				$this->print_item_by_key( $key );
			}
		} elseif ( $key && isset( $this->args['woof_settings']['tax'][ $key ] ) && isset( $this->args['taxonomies'][ $key ] ) ) {

			if ( $this->is_allowed_item( $key, $this->args['tax_only'] ) ) {
				$this->print_taxonomy_by_key( $key );
			}
		}

	}
	private function draw_submit_btn( array $positions ) {
		if ( in_array( $this->args['btn_position'], $positions ) ) {
			$this->print_btn();
		}
	}
	private function print_start_filtering_btn(): void {
		$woof_start_filtering_btn_txt = isset( $this->args['woof_start_filtering_btn_txt'] ) ? $this->args['woof_start_filtering_btn_txt'] : '';
		include $this->views_path . 'start_filtering_btn.php';
	}
	private function print_filter_countainer_start(): void {

		if ( isset( $this->args['sid'] ) ) {
			$sid = $this->args['sid'];
		}
		$woof_class = "";
		if ( $this->check_mobile_device() && ( isset( $this->args['mobile_mode'] ) && $this->args['mobile_mode'] == 1 ) && isset( $sid ) ) {
			$woof_class = 'woof_hide_filter';
		}

		$redirect = isset( $this->args['redirect'] ) ? $this->args['redirect'] : '';
		$autosubmit = isset( $this->args['autosubmit'] ) ? $this->args['autosubmit'] : '';
		$ajax_redraw = isset( $this->args['ajax_redraw'] ) ? $this->args['ajax_redraw'] : '';

		include $this->views_path . 'filter_countainer_start.php';
	}
	private function print_filter_countainer_end(): void {

		include $this->views_path . 'filter_countainer_end.php';
	}
	private function print_redraw_zone_start(): void {

		$woof_filter_class = "woof_redraw_zone";
		$checkbox_skin = $this->woof->settings['icheck_skin'];

		include $this->views_path . 'redraw_zone_start.php';
	}
	private function print_redraw_zone_end(): void {
		include $this->views_path . 'redraw_zone_end.php';
	}
	private function print_taxonomy_by_key( $tax_slug ): void {

		$primax_class = sanitize_key( \WOOF_HELPER::wpml_translate( $this->args['taxonomies_info'][ $tax_slug ] ?? '' ) );

		$css_classes = "woof_block_html_items";

		$show_toggle = $this->get_toggle_option( $tax_slug );

		$block_is_closed = $this->get_toggle_state( $show_toggle );

		$css_classes .= $this->get_toggle_class( $show_toggle, $block_is_closed );

		$title = $this->get_tax_title( $this->args['taxonomies_info'][ $tax_slug ] );

		include $this->views_path . 'taxonomy.php';
	}
	private function print_item_by_key( string $key ): void {

		$title = $this->get_item_title_by_key( $key );
		if ( isset( $this->woof->settings[ $key ] ) and $this->woof->settings[ $key ]['show'] ) {
			include $this->views_path . 'by_item.php';
		}

	}
	private function print_btn(): void {

		$autosubmit = isset( $this->args['autosubmit'] ) ? $this->args['autosubmit'] : 1;
		$ajax_redraw = isset( $this->args['ajax_redraw'] ) ? $this->args['ajax_redraw'] : 0;
		$show_btn = ! $autosubmit || $ajax_redraw;
		$woof_filter_btn_txt = $this->get_filter_btn_text();
		include $this->views_path . 'filter_btn.php';
	}
	private function print_title( string $key, string $title, int $show_toggle, bool $block_is_closed ): void {
		$tooltip_text = $this->get_tooltip_text( $key );
		?>
		<<?php echo esc_html( apply_filters( 'woof_title_tag', 'h4' ) ); ?>>
			<?php echo esc_html( $title ) ?>
			<?php \WOOF_HELPER::draw_tooltipe( $title, $tooltip_text ) ?>
			<?php \WOOF_HELPER::draw_title_toggle( $show_toggle, $block_is_closed ); ?>
		</<?php echo esc_html( apply_filters( 'woof_title_tag', 'h4' ) ); ?>>
		<?php

	}
	private function print_autohide_wrapper_start(): void {
		if ( isset( $this->args['autohide'] ) && $this->args['autohide'] ) {
			$woof_auto_hide_button_txt = $this->get_autohide_btn_txt();
			$show_text = isset( $this->woof->settings['woof_auto_hide_button_img'] ) && $this->woof->settings['woof_auto_hide_button_img'] == 'none';
			include $this->views_path . 'autohide_wrapper_start.php';
		}
	}
	private function print_autohide_wrapper_end(): void {
		if ( isset( $this->args['autohide'] ) && $this->args['autohide'] ) {
			include $this->views_path . 'autohide_wrapper_end.php';
		}
	}

	private function print_mobile_btns() {

		if ( $this->check_mobile_device()
			&& ( isset( $this->args['mobile_mode'] ) && $this->args['mobile_mode'] )
			&& isset( $this->args['sid'] ) ) {
			$sid = $this->args['sid'];
			$image_mb_open = ( isset( $this->woof->settings['image_mobile_behavior_open'] ) ) ? $this->woof->settings['image_mobile_behavior_open'] : '';
			$image_mb_close = ( isset( $this->woof->settings['image_mobile_behavior_close'] ) ) ? $this->woof->settings['image_mobile_behavior_close'] : '';
			if ( $image_mb_open != -1 && empty( $image_mb_open ) ) {
				$image_mb_open = WOOF_LINK . "img/open_filter.png";
			}
			if ( $image_mb_close != -1 && empty( $image_mb_close ) ) {
				$image_mb_close = WOOF_LINK . "img/close_filter.png";
			}
			$text_mb_open = ( isset( $this->woof->settings['text_mobile_behavior_open'] ) ) ? $this->woof->settings['text_mobile_behavior_open'] : esc_html__( 'Open filter', 'woocommerce-products-filter' );
			$text_mb_close = ( isset( $this->woof->settings['text_mobile_behavior_close'] ) ) ? $this->woof->settings['text_mobile_behavior_close'] : esc_html__( 'Close filter', 'woocommerce-products-filter' );
			include $this->views_path . 'mobile_btns.php';
		}

	}
	private function get_all_items(): array {
		$items_order = array();

		$taxonomies_keys = array_keys( $this->args['taxonomies'] );

		if ( isset( $this->args['woof_settings']['items_order'] ) and ! empty( $this->args['woof_settings']['items_order'] ) ) {
			$items_order = explode( ',', $this->args['woof_settings']['items_order'] );
		} else {
			$items_order = array_merge( $this->woof->items_keys, $taxonomies_keys );
		}

		//*** lets check if we have new taxonomies added in woocommerce or new item.
		foreach ( array_merge( $this->woof->items_keys, $taxonomies_keys ) as $key ) {
			if ( ! in_array( $key, $items_order ) ) {
				$items_order[] = $key;
			}
		}

		//lets print our items and taxonomies.


		$items_order = $this->get_order_by_tax_only( $items_order );

		$items_order = $this->get_order_by_step( $items_order );


		return apply_filters( 'woof_custom_filter_items_order', $items_order, ( isset( $this->args['shortcode_atts']['id'] ) ? $this->args['shortcode_atts']['id'] : '' ) );

	}
	private function get_tax_title( \WP_Taxonomy $taxonomy_info ): string {

		$title = \WOOF_HELPER::wpml_translate( $taxonomy_info );

		return $title;
	}
	private function get_toggle_option( string $key ): int {
		$show_toggle = 0;
		if ( isset( $this->woof->settings['show_toggle_button'][ $key ] ) ) {
			$show_toggle = (int) $this->woof->settings['show_toggle_button'][ $key ];
		}
		return $show_toggle;
	}
	private function get_toggle_state( int $toggle_option ): bool {

		$block_is_closed = true;

		if ( $toggle_option ) {
			$block_is_closed = false;
		}

		if ( in_array( $toggle_option, array( 1, 2 ) ) ) {
			$block_is_closed = apply_filters( 'woof_block_toggle_state', $block_is_closed );
		}
		return $block_is_closed;
	}

	private function get_toggle_class( int $toggle_option, bool $toggle_state ): string {
		$css_classes = '';
		if ( $toggle_option === 1 ) {
			$css_classes .= " woof_closed_block";
		}

		if ( in_array( $toggle_option, array( 1, 2 ) ) ) {
			if ( $toggle_state ) {
				$css_classes .= " woof_closed_block";
			} else {
				$css_classes = str_replace( 'woof_closed_block', '', $css_classes );
			}
		}
		return $css_classes;
	}
	private function get_tooltip_text( string $key ): string {
		$tooltip_text = "";
		if ( isset( $this->woof->settings['tooltip_text'][ $key ] ) ) {
			$tooltip_text = $this->woof->settings['tooltip_text'][ $key ];
		}

		return $tooltip_text;
	}
	private function get_order_by_tax_only( $t_order ): array {
		if ( ! count( $this->args['tax_only'] ) ) {
			return $t_order;
		}
		$temp_array = array_intersect( $t_order, $this->args['tax_only'] );
		$i = 0;
		foreach ( $temp_array as $key => $val ) {
			$t_order[ $key ] = $this->args['tax_only'][ $i ];
			$i++;
		}
		return $t_order;
	}
	private function get_order_by_step( $items_order ): array {
		if ( isset( $this->args['by_step'] ) ) {
			$new_items_order = explode( ',', $this->args['by_step'] );
			$items_order = array_map( 'trim', $new_items_order );
		}
		return $items_order;
	}
	private function get_item_title_by_key( string $key ): string {
		$title = "";
		if ( isset( $this->args['settings']['meta_filter'] ) and isset( $this->args['settings']['meta_filter'][ $key ] ) ) {
			if ( isset( $this->args['settings'][ $key ]['show'] ) && $this->args['settings'][ $key ]['show'] != 0 ) {
				$title = $this->args['settings']['meta_filter'][ $key ]['title'];
			}
		} elseif ( isset( $this->standard_filters[ $key ] ) ) {
			$title = $this->standard_filters[ $key ];
		}
		return $title;
	}
	private function get_filter_btn_text(): string {
		$woof_filter_btn_txt = get_option( 'woof_filter_btn_txt', '' );
		if ( empty( $woof_filter_btn_txt ) or $this->woof->show_notes ) {
			$woof_filter_btn_txt = esc_html__( 'Filter', 'woocommerce-products-filter' );
		}

		$woof_filter_btn_txt = \WOOF_HELPER::wpml_translate( null, $woof_filter_btn_txt );

		return $woof_filter_btn_txt;
	}
	private function get_autohide_btn_txt(): string {
		$woof_auto_hide_button_txt = '';
		if ( isset( $this->woof->settings['woof_auto_hide_button_txt'] ) ) {
			$woof_auto_hide_button_txt = \WOOF_HELPER::wpml_translate( null, $this->woof->settings['woof_auto_hide_button_txt'] );
		}

		return $woof_auto_hide_button_txt;
	}
	private function is_allowed_item( string $key, array $only ): bool {
		if ( ( ! empty( $only ) && ! in_array( $key, $only ) ) || ( ! empty( $this->args['tax_exclude'] ) && in_array( $key, $this->args['tax_exclude'] ) ) ) {
			return false;
		}
		return true;
	}
	private function check_mobile_device() {
		return wp_is_mobile();
	}
}