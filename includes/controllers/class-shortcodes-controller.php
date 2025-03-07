<?php

namespace CoolKidsNetwork\Controllers;

class Shortcodes_Controller {
	/**
	 * Registers hooks.
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public static function register_shortcodes() {
		add_shortcode( 'ckn-list-users', array( 'CoolKidsNetwork\Models\Users', 'list_users' ) );
		add_shortcode( 'ckn-show-character-info', array( 'CoolKidsNetwork\Models\Users', 'show_character_info' ) );
		add_shortcode( 'ckn-signup-form', array( 'CoolKidsNetwork\Controllers\Forms_Controller', 'show_signup_form' ) );
	}
}
