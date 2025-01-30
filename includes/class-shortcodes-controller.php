<?php

namespace CoolKidsNetwork;

class Shortcodes_Controller {
	/**
	 * Registers hooks.
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public static function register_shortcodes() {
		add_shortcode( 'ckn-signup-form', array( 'CoolKidsNetwork\Forms_Controller', 'show_signup_form' ) );
	}
}
