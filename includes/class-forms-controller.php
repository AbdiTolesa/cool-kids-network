<?php

namespace CoolKidsNetwork;

class Forms_Controller {
	/**
	 * Outputs Sign up button or login form.
	 *
	 * @since 1.0
	 *
	 * @return string
	 */
	public static function show_signup_button() {
		if ( is_user_logged_in() ) {
			return '';
		}
		ob_start();
		?>
		<a href="<?php echo esc_url( home_url( '/cool-kids-network-signup' ) ); ?>" class="signup-button"><?php esc_html_e( 'Sign Up', 'cool-kids-network' ); ?></a>
		<?php esc_html_e( ' for Cool Kids Network, or use the following form to login if you already have an account.', 'cool-kids-network' ); ?>
		<?php
		wp_login_form();
		return ob_get_clean();
	}

	/**
	 * Outputs a sign up form.
	 *
	 * @since 1.0
	 *
	 * @param string $html
	 *
	 * @return void
	 */
	public static function show_signup_form( $html ) {
		if ( ! is_page( 'cool-kids-network-signup' ) ) {
			return $html;
		}
		ob_start();
		include CKN_VIEWS_DIR . '/signup-form.php';
		return ob_get_clean() . $html;
	}

	/**
	 * Creates user and its character.
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public static function process_signup_form() {
		if ( ! isset( $_POST['ckn-email'] ) ) {
			return;
		}
		if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'ckn_signup_action' ) ) {
			wp_die();
		}

		$email   = sanitize_email( $_POST['ckn-email'] );
		$user_id = email_exists( $email );

		if ( $user_id ) {
			$url = add_query_arg(
				array( 'error' => 'email_exists' ),
				home_url( '/cool-kids-network-signup' )
			);
			wp_redirect( $url );
			exit;
		}

		$args     = array(
			'timeout' => 30,
		);
		$response = wp_remote_get( 'https://randomuser.me/api/?inc=name,location', $args );
		if ( is_wp_error( $response ) ) {
			wp_send_json_error( $response ); // TODO: Handle it better!
		}
		$response = json_decode( wp_remote_retrieve_body( $response ) );

		$first = $response->results[0]->name->first;
		$last  = $response->results[0]->name->last;

		$user_data = array(
			'user_email' => $email,
			'user_login' => Users::generate_username_from_name( $first, $last ),
			'first_name' => $first,
			'last_name'  => $last,
			'user_pass'  => 'test',
			'role'       => 'cool_kid',
		);
		$user_id   = wp_insert_user( $user_data );
		update_user_meta( $user_id, 'country', $response->results[0]->location->country );
		wp_set_current_user( $user_id );
		wp_set_auth_cookie( $user_id );
		wp_redirect( home_url( '/cool-kids-network' ) );
		exit;
	}
}
