<?php
namespace Husky\Block\Render;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class ShortcodeRendering {
	/**
	 * __construct
	 *
	 * @param array $attributes data that is transmitted by wp block editor.
	 * @return void
	 */
	public function __construct( private \Husky\Block\Render\Shortcodes\Abstract\Shortcode $shortcode, private array $attributes ) {

	}

	public function render(): void {
		?>
		<div class="husky_block_wrapper" style="
			<?php echo esc_attr( $this->get_styles() ); ?>
		">
			<?php
			$this->shortcode->draw();
			?>
		</div>
		<?php
	}

	protected function get_styles(): string {
		$style = '';
		$style .= $this->get_padding();
		return $style;
	}

	private function get_padding(): string {
		$style_string = sprintf( 'padding: %dpx;', isset( $this->attributes['padding'] ) ? $this->attributes['padding'] : '0' );
		return $style_string;
	}
}