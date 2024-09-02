<!--- here is possible to drop html code which is never redraws by AJAX ---->
<?php echo wp_kses_post( wp_unslash( apply_filters( 'woof_print_content_before_redraw_zone', '' ) ) ) ?>

<div class="<?php echo esc_attr( $woof_filter_class ) ?>" data-woof-ver="<?php echo esc_attr( WOOF_VERSION ) ?>"
	data-icheck-skin="<?php echo esc_attr( $checkbox_skin ) ?>">
	<?php echo wp_kses_post( wp_unslash( apply_filters( 'woof_print_content_before_search_form', '' ) ) ) ?>