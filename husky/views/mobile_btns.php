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