<?php
namespace Husky\Block\Render\Shortcodes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class HuskySimulation extends \Husky\Block\Render\Shortcodes\Abstract\WOOF\Assembling {
	protected string $shortcode_name = 'woof_simulation';

	public function draw(): void {
		?>
		<p class="husky_block_nitice">
			<i>[<?php esc_html_e( 'This is a schematic representation of the filter template.', 'husky-block' ) ?>]</i>
		</p>
		<?php
		parent::draw();
	}
}