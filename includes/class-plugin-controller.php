<?php
namespace CoolKidsNetwork;

class Plugin_Controller {
	public static function boot_plugin() {
		Hooks_Controller::register_hooks();
		Shortcodes_Controller::register_shortcodes();
	}

	public static function enqueue_styles() {
		wp_enqueue_style( 'ckn-styles', CKN_PLUGIN_URL . 'css/output.css', array() );
	}
}
