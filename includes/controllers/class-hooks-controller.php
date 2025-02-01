<?php

namespace CoolKidsNetwork\Controllers;

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
		add_filter( 'the_content', array( 'CoolKidsNetwork\Controllers\Forms_Controller', 'filter_page_content' ) );
		add_action( 'init', array( 'CoolKidsNetwork\Controllers\Forms_Controller', 'process_signup_form' ) );
		add_action( 'wp_enqueue_scripts', array( 'CoolKidsNetwork\Controllers\Plugin_Controller', 'enqueue_styles' ) );
		add_action( 'pre_get_posts', array( 'CoolKidsNetwork\Models\Users', 'set_paged_query_var' ) );
		add_filter( 'render_block', array( 'CoolKidsNetwork\Controllers\Forms_Controller', 'filter_blog_index_html' ), 10, 2 );
	}
}
