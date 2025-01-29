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
		add_shortcode( 'ckn-signup-button', array( __CLASS__, 'show_signup_button' ) ); // TODO: shortcode to show signup button + login form.
		add_filter( 'the_content', array( __CLASS__, 'show_signup_form' ) );
		add_action( 'init', array( __CLASS__, 'process_signup_form' ) );
		add_shortcode( 'ckn-list-users', array( 'CoolKidsNetwork\Users_List', 'list_users' ) );
		add_shortcode( 'ckn-show-character-info', array( 'CoolKidsNetwork\Users_List', 'show_character_info' ) );
	}

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
		if ( isset( $_REQUEST['error'] ) && 'email_exists' === $_REQUEST['error'] ) {
			?>
			<p class="error"><?php esc_html_e( 'User with that email already exists.', 'cool-kids-network' ); ?></p>
			<?php
		}
		?>
		<form action="" method="post">
			<?php wp_nonce_field( 'ckn_signup_action' ); ?>
			<label for="ckn-email"><?php esc_html_e( 'Email:', 'cool-kids-network' ); ?></label>
			<input type="email" name="ckn-email" id="ckn-email" required>
			<input type="submit" value="<?php esc_html_e( 'Sign Up', 'cool-kids-network' ); ?>">
		</form>
		<?php
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

		$args = array(
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
			'user_login' => self::generate_username_from_name( $first, $last ),
			'first_name' => $first,
			'last_name'  => $last,
			'user_pass'  => 'test',
			'role'       => 'cool_kid',
		);
		$user_id = wp_insert_user( $user_data );
		update_user_meta( $user_id, 'country', $response->results[0]->location->country );
		wp_set_current_user( $user_id );
		wp_set_auth_cookie( $user_id );
		wp_redirect( home_url( '/cool-kids-network' ) );
		exit;
	}

	/**
	 * Returns a unique username based on first and last name.
	 *
	 * @since 1.0
	 *
	 * @param string $first_name
	 * @param string $last_name
	 *
	 * @return string
	 */
	public static function generate_username_from_name( $first_name, $last_name ) {
		$username          = sanitize_user( strtolower( $first_name . '.' . $last_name ) );
		$original_username = $username;
		$counter           = 1;
		while ( username_exists( $username ) ) {
			$username = $original_username . $counter;
			$counter++;
		}
		return $username;
	}

	/**
	 * Callback for plugin activation.
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public static function on_activation() {
		$capabilities = array(
			'read' => true,
		);
		add_role(
			'cool_kid',
			'Cool Kid'
		);
		add_role(
			'cooler_kid',
			'Cooler Kid',
			array(
				'view_other_users_name'    => true,
				'view_other_users_country' => true,
			)
		);
		add_role(
			'coolest_kid',
			'Coolest Kid',
			array(
				'view_other_users_name'    => true,
				'view_other_users_country' => true,
				'view_other_users_email'   => true,
				'view_other_users_role'    => true,
			)
		);
	}
}
