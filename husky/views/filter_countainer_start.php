<div class="woof woof_mock <?php if ( ! empty( $sid ) ) : ?>woof_sid woof_sid_<?php echo esc_attr( $sid ) ?><?php endif; ?> <?php echo esc_attr( $woof_class ) ?>"
	<?php if ( ! empty( $sid ) ) : ?> data-sid="<?php echo esc_attr( $sid ); ?>" <?php endif; ?>
	data-shortcode="<?php echo esc_html( WOOF_REQUEST::isset( 'woof_shortcode_txt' ) ? WOOF_REQUEST::get( 'woof_shortcode_txt' ) : 'woof' ) ?>"
	data-redirect="<?php echo esc_url( $redirect ) ?>" data-autosubmit="<?php echo esc_attr( $autosubmit ) ?>"
	data-ajax-redraw="<?php echo esc_attr( $ajax_redraw ) ?>">