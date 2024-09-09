<?php
/**
 * Test_WOOF_Shortcode.
 *
 * @package Husky_Block
 */

/**
 * Test_WOOF_Shortcode.
 */
class Test_WOOF_Shortcode extends WP_UnitTestCase {
	public static $woof_shortcode;
	public static function wpSetUpBeforeClass( $factory ) {
		require_once HUSKY_BLOCK_TEST_PATH . '/Fakes/WoofFake.php';
		require_once HUSKY_BLOCK_TEST_PATH . '/Fakes/HelperFake.php';
		self::$woof_shortcode = new Husky\Block\Shortcode\Template\WOOFShortcode( new WoofFake(), '\HELPER_FAKE' );
	}
	public static function wpTearDownAfterClass() {
	}

	/**
	 * @dataProvider get_args_provider
	 */
	public function test_get_all_items( $args, $expected_items ) {

		self::$woof_shortcode->set_args( $args );

		$method = $this->make_private_to_public( 'get_all_items' );

		$actual_items = $method->invoke( self::$woof_shortcode );

		$this->assertSameSets( $expected_items, $actual_items );
	}

	/**
	 * @dataProvider get_toggle_key_provider
	 */
	public function test_get_toggle_option( $key, $expected_option ) {
		$method = $this->make_private_to_public( 'get_toggle_option' );

		$actual_option = $method->invoke( self::$woof_shortcode, $key );

		$this->assertSame( $expected_option, $actual_option );
	}

	/**
	 * @dataProvider get_toggle_option_provider
	 */
	public function test_get_toggle_state( $toggle_option, $expected_state ) {
		$method = $this->make_private_to_public( 'get_toggle_state' );

		$actual_state = $method->invoke( self::$woof_shortcode, $toggle_option );

		$this->assertSame( $expected_state, $actual_state );
	}

	/**
	 * @dataProvider get_toggle_state_provider
	 */
	public function test_get_toggle_class( $toggle_option, $toggle_state, $expected_class ) {
		$method = $this->make_private_to_public( 'get_toggle_class' );

		$actual_class = $method->invoke( self::$woof_shortcode, $toggle_option, $toggle_state );

		$this->assertSame( $expected_class, $actual_class );
	}

	public function test_get_tooltip_text() {

		$method = $this->make_private_to_public( 'get_tooltip_text' );

		$actual_text = $method->invoke( self::$woof_shortcode, 'test_slug' );
		$this->assertSame( 'test tooltip text', $actual_text,
			"The function takes incorrect data from the settings." );

		$actual_text = $method->invoke( self::$woof_shortcode, 'non-existent' );
		$this->assertSame( '', $actual_text,
			"With a non-existent key an empty string should be returned." );
	}

	/**
	 * @dataProvider get_order_by_tax_provider
	 */
	public function test_get_order_by_tax_only( $tax_order, $tax_only_order, $expected_order ) {

		self::$woof_shortcode->set_args( array( 'tax_only' => $tax_only_order ) );

		$method = $this->make_private_to_public( 'get_order_by_tax_only' );

		$actual_order = $method->invoke( self::$woof_shortcode, $tax_order );

		$this->assertSameSets( $expected_order, $actual_order );
	}

	/**
	 * @dataProvider get_order_by_step_provider
	 */
	public function test_get_order_by_step( $intems_order, $by_step_order, $expected_order ) {
		if ( empty( $by_step_order ) ) {
			self::$woof_shortcode->set_args( array() );
		} else {
			self::$woof_shortcode->set_args( array( 'by_step' => $by_step_order ) );
		}

		$method = $this->make_private_to_public( 'get_order_by_step' );

		$actual_order = $method->invoke( self::$woof_shortcode, $intems_order );

		$this->assertSameSets( $expected_order, $actual_order );
	}

	/**
	 * @dataProvider get_item_titles_provider
	 */
	public function test_get_item_title_by_key( $key, $expected_title ) {

		$args = array(
			'settings' => array(
				'meta_filter' => array(
					'show_title' => array( 'title' => 'Show Title' ),
					'hide_title' => array( 'title' => 'Hide Title' ),
					'hide_title_2' => array( 'title' => 'Hide Title 2' ),
				),
				'show_title' => array(
					'show' => 1
				),
				'hide_title' => array(
					'show' => 0
				)
			)
		);
		self::$woof_shortcode->set_args( $args );

		$method = $this->make_private_to_public( 'get_item_title_by_key' );

		$title = $method->invoke( self::$woof_shortcode, $key );
		$this->assertSame( $expected_title, $title );
	}
	public function test_get_filter_btn_text() {
		$method = $this->make_private_to_public( 'get_filter_btn_text' );

		$btn_txt = $method->invoke( self::$woof_shortcode );
		$this->assertSame( 'Filter', $btn_txt );
	}

	public function test_get_autohide_btn_txt() {
		$method = $this->make_private_to_public( 'get_autohide_btn_txt' );

		$btn_txt = $method->invoke( self::$woof_shortcode );

		$this->assertSame( 'Hide button text', $btn_txt );
	}

	/**
	 * @dataProvider get_allowed_item_data_provider
	 */
	public function test_is_allowed_item( $key, $only, $expected_allowed ) {

		$args = array(
			'tax_exclude' => array( 'test_hide', 'some_slug' )
		);
		self::$woof_shortcode->set_args( $args );

		$method = $this->make_private_to_public( 'is_allowed_item' );

		$result_allowed = $method->invoke( self::$woof_shortcode, $key, $only );
		$this->assertSame( $expected_allowed, $result_allowed );
	}

	public function make_private_to_public( string $method_name ) {
		$woof_shortcode_reflection = new ReflectionClass( self::$woof_shortcode );
		$method = $woof_shortcode_reflection->getMethod( $method_name );
		$method->setAccessible( true );

		return $method;
	}
	public function get_args_provider(): array {
		$normal_args = array(
			'woof_settings' => array(
				'items_order' => 'by_price,by_text,pa_color,by_author'
			)
		);

		$witn_taxomomies = $normal_args;
		$witn_taxomomies['taxonomies'] = array( 'product_cat' => 111, 'product_tag' => 222 );

		return array(
			'normal' => array( $normal_args, array( 'by_price', 'by_text', 'pa_color', 'by_author' ) ),
			'empty' => array( array(), array( 'by_price', 'by_text', 'by_author' ) ),
			'witn taxomomies' => array( $witn_taxomomies, array( 'by_price', 'by_text', 'pa_color', 'by_author', 'product_cat', 'product_tag' ) ),
		);
	}
	public function get_toggle_key_provider(): array {
		return array(
			'inited' => array( 'test_slug', 1 ),
			'disabled' => array( 'test_slug_2', 0 ),
			'non-existent key' => array( 'qwrty', 0 ),
		);
	}

	public function get_toggle_option_provider(): array {
		return array(
			'empty' => array( 0, true ),
			'init and toggled' => array( 1, false ),
			'init as open' => array( 2, false )
		);
	}
	public function get_toggle_state_provider(): array {
		return array(
			'empty' => array( 0, false, '' ),
			'disabled' => array( 0, true, '' ),
			'init and toggled' => array( 1, true, ' woof_closed_block' ),
			'init' => array( 1, false, ' ' ),
			'init as open' => array( 2, false, '' ),
		);
	}
	public function get_order_by_tax_provider(): array {
		return array(
			'all fields are empty' => array( array(), array(), array() ),
			'initial order is empty' => array(
				array(),
				array( 'tax_filter1', 'tax_filter2' ),
				array()
			),
			'step order is empty' => array(
				array( 'filter1', 'filter2' ),
				array(),
				array( 'filter1', 'filter2' )
			),
			'all fields are not empty' => array(
				array( 'filter1', 'filter2', 'filter3' ),
				array( 'filter3', 'filter1' ),
				array( 'filter3', 'filter1' )
			),
			'tax_only has a non-existent filter' => array(
				array( 'filter1', 'filter2', 'filter3', 'filter4', 'filter5' ),
				array( 'filter5', 'filter3', 'filter1', 'filter6' ),
				array( 'filter5', 'filter3', 'filter1' )
			),
		);
	}
	public function get_order_by_step_provider(): array {
		return array(
			'all fields are empty' => array( array(), '', array() ),
			'initial order is empty' => array(
				array(),
				'step_filter1, step_filter2',
				array( 'step_filter1', 'step_filter2' )
			),
			'step order is empty' => array(
				array( 'filter1', 'filter2' ),
				'',
				array( 'filter1', 'filter2' )
			),
			'all fields are not empty' => array(
				array( 'filter1', 'filter2' ),
				'step_filter1, step_filter2',
				array( 'step_filter1', 'step_filter2' )
			),
		);
	}

	public function get_item_titles_provider(): array {
		return array(
			'empty' => array( '', '' ),
			'non_existent_key' => array( 'qwerty', '' ),
			'meta_key' => array( 'show_title', 'Show Title' ),
			'hidden_meta_key' => array( 'hide_title', '' ),
			'meta_key_without_show' => array( 'hide_title_2', '' ),
			'standard_filter' => array( 'by_price', 'Search by Price' ),
		);
	}

	public function get_allowed_item_data_provider(): array {

		return array(
			'show if $only is empty' => array( 'test', array(), true ),
			'show if match in $only' => array( 'test', array( 'any_slug', 'test' ), true ),
			'hide if no match in $only' => array( 'test', array( 'any_slug', 'another_slug' ), false ),
			'hide if match in tax_exclude' => array( 'test_hide', array( 'any_slug', 'another_slug' ), false ),
		);
	}
}