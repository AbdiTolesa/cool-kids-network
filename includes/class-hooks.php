<?php

namespace CoolKidsNetwork;

class Hooks {
	/**
	 * Registers hooks.
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public static function register_hooks() {
		add_action( 'rest_api_init', array( new Rest_Controller(), 'register_routes' ) );
		add_shortcode( 'ckn-signup-button', array( 'CoolKidsNetwork\Forms_Controller', 'show_signup_button' ) ); // TODO: shortcode to show signup button + login form.
		add_filter( 'the_content', array( 'CoolKidsNetwork\Forms_Controller', 'show_signup_form' ) );
		add_action( 'init', array( 'CoolKidsNetwork\Forms_Controller', 'process_signup_form' ) );
		add_shortcode( 'ckn-list-users', array( 'CoolKidsNetwork\Users', 'list_users' ) );
		add_shortcode( 'ckn-show-character-info', array( 'CoolKidsNetwork\Users', 'show_character_info' ) );
	}
}
