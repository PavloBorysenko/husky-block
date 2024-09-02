<div data-css-class="woof_container_<?php echo esc_attr( $tax_slug ) ?>"
	class="woof_container woof_container_<?php echo esc_attr( isset( $woof_settings['tax_type'][ $tax_slug ] ) ? $woof_settings['tax_type'][ $tax_slug ] : '' ) ?> woof_container_<?php echo esc_attr( $tax_slug ) ?> woof_container_<?php echo esc_attr( 1 ) ?> woof_container_<?php echo esc_attr( $primax_class ) ?> <?php echo esc_attr( WOOF_HELPER::generate_container_css_classes( $tax_slug ) ) ?>">
	<div class="woof_container_overlay_item"></div>
	<div class="woof_container_inner woof_container_inner_<?php echo esc_attr( $primax_class ) ?>">
		<?php
		if ( $this->woof->settings['show_title_label'][ $tax_slug ] ) {
			$this->print_title( $tax_slug, $title, $show_toggle, $block_is_closed );
		}
		?>

		<div class="<?php echo esc_attr( $css_classes ) ?>">
			<div class="woof_mock_label">
				<?php esc_html_e( 'Filter items: ', 'husky-block' ) ?>
				[<?php echo $this->args['woof_settings']['tax_type'][ $tax_slug ] ?>]
			</div>
		</div>

	</div>
</div>