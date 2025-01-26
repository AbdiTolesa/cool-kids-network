<?php

namespace CoolKidsNetwork;

class Helper_Functions {
	/**
	 * @since 1.0
	 *
	 * @param string $name
	 * @return int
	 */
	public static function stln_wc_get_attribute_id_from_name( $name ) {
		global $wpdb;
		$attribute_id = $wpdb->get_col( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching,WordPress.DB.PreparedSQL.NotPrepared
			"SELECT attribute_id
			FROM {$wpdb->prefix}woocommerce_attribute_taxonomies
			WHERE attribute_name LIKE '$name'" // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		);

		return reset( $attribute_id );
	}

	/**
	 * Register CoolKidsNetwork Logs submenu in the main WooCommerce Logs.
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public static function register_log_submenu_page() {
		add_submenu_page(
			'woocommerce',
			__( 'Cool Kids Network', 'cool-kids-network' ),
			__( 'Cool Kids Network', 'cool-kids-network' ),
			'manage_options',
			'cool-kids-network',
			array( __CLASS__, 'logs_list_callback' )
		);
	}

	/**
	 * The HML for logs list table.
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public static function logs_list_callback() {
	}

	/**
	 * Enqueues admin scripts.
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public static function enqueue_scripts() {
		$current_screen = get_current_screen();

		if ( ! $current_screen ) {
			return;
		}

		if ( $current_screen->id === 'woocommerce_page_stoneline-logs' ) {
			wp_enqueue_style( 'stln-admin', plugins_url( 'css/cool_kids_admin.css', __DIR__ ), array(), '1.0' );
		}
	}

	/**
	 * Checks required permission check for custom endpoints defined in this plugin.
	 *
	 * @since 1.0
	 *
	 * @return bool
	 */
	public static function check_required_role() {
		return current_user_can( 'manage_options' );
	}

	public static function show_product_logs( $post ) {
	}
}
