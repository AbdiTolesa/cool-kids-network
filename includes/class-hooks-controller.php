<?php

namespace CoolKidsNetwork;

class Hooks_Controller {
	/**
	 * Registers hooks.
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public static function register_hooks() {
		add_action( 'rest_api_init', array( new Rest_Controller(), 'register_routes' ) );
		add_filter( 'the_content', array( 'CoolKidsNetwork\Forms_Controller', 'filter_page_content' ) );
		add_action( 'init', array( 'CoolKidsNetwork\Forms_Controller', 'process_signup_form' ) );
		add_action( 'wp_enqueue_scripts', array( 'CoolKidsNetwork\Plugin_Controller', 'enqueue_styles' ) );
	}
}
