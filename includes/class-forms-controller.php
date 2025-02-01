<?php

namespace CoolKidsNetwork;

class Forms_Controller {
	/**
	 * Outputs a form that has Signup button and a login form for anonymous users.
	 *
	 * @since 1.0
	 *
	 * @param string $html
	 *
	 * @return string
	 */
	public static function filter_page_content( $html ) {
		if ( ! is_front_page() && ! is_home() ) {
			return $html;
		}
		if ( ! is_user_logged_in() ) {
			return self::get_login_form() . $html;
		}
		return do_shortcode( '[ckn-show-character-info]' ) . do_shortcode( '[ckn-list-users]' ) . $html;
	}

	/**
	 * Outputs a Signup button and login form for anonymous users.
	 *
	 * @since 1.0
	 *
	 * @return string
	 */
	private static function get_login_form() {
		ob_start();
		include CKN_VIEWS_DIR . '/login-form.php';
		return (string) ob_get_clean();
	}

	/**
	 * Outputs a sign up form.
	 *
	 * @since 1.0
	 *
	 * @return string
	 */
	public static function show_signup_form() {
		if ( is_user_logged_in() ) {
			return '';
		}
		ob_start();
		include CKN_VIEWS_DIR . '/signup-form.php';
		return (string) ob_get_clean();
	}

	/**
	 * Creates user and its character.
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public static function process_signup_form() {
		if ( ! isset( $_POST['ckn-email'] ) || ! isset( $_POST['_wpnonce'] ) ) {
			return;
		}
		if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ), 'ckn_signup_action' ) ) {
			wp_die();
		}

		$email   = sanitize_email( wp_unslash( $_POST['ckn-email'] ) );
		$user_id = email_exists( $email );

		if ( $user_id ) {
			$url = add_query_arg(
				array( 'error' => 'email_exists' ),
				home_url( '/cool-kids-network-signup' )
			);
			wp_safe_redirect( $url );
			exit;
		}

		$args     = array(
			'timeout' => 30,
		);
		$response = wp_remote_get( 'https://randomuser.me/api/?inc=name,location', $args );
		if ( is_wp_error( $response ) ) {
			wp_die( esc_html( $response->get_error_message() ) );
		}
		$response = json_decode( wp_remote_retrieve_body( $response ) );

		if ( ! is_object( $response ) || ! is_array( $response->results ) ) {
			return;
		}
		$character_data = $response->results[0];
		if ( ! is_object( $character_data ) ) {
			return;
		}
		$first = $character_data->name->first;
		$last  = $character_data->name->last;

		$user_data = array(
			'user_email' => $email,
			'user_login' => Users::generate_username_from_name( $first, $last ), // @phpstan-ignore-line
			'first_name' => $first,
			'last_name'  => $last,
			'user_pass'  => 'test',
			'role'       => 'cool_kid',
		);

		$user = wp_insert_user( $user_data );
		if ( is_wp_error( $user ) ) {
			wp_die( esc_html( $user->get_error_message() ) );
		}
		if ( ! $user ) {
			return;
		}
		update_user_meta( $user, 'country', $character_data->location->country );
		wp_set_current_user( $user );
		wp_set_auth_cookie( $user );
		wp_safe_redirect( home_url() );
		exit;
	}
}
