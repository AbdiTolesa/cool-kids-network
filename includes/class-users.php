<?php

namespace CoolKidsNetwork;

class Users {
	/**
	 * Number of users to display per page.
	 */
	const PER_PAGE = 10;

	/**
	 * Get users data.
	 *
	 * @since 1.0
	 *
	 * @param string $role
	 *
	 * @return array {
	 *   @type array $users       Array of users data.
	 *   @type int   $total_pages Total number of pages.
	 * }
	 */
	private static function get_users_data( $role ) {
		$paged  = max( 1, get_query_var( 'paged' ) );
		$offset = ( $paged - 1 ) * self::PER_PAGE;

		$user_query = new \WP_User_Query(
			array(
				'fields' => 'all',
				'number' => self::PER_PAGE,
				'offset' => $offset,
			)
		);

		$users           = $user_query->get_results();
		$users_with_meta = array();
		foreach ( $users as $user ) {
			$user_data = array(
				'name'    => $user->display_name,
				'country' => get_user_meta( $user->ID, 'country', true )
			);
			if ( 'coolest_kid' === $role ) {
				global $wp_roles;
				$role_name = isset( $wp_roles->roles[ $role ] ) ? $wp_roles->roles[ $role ]['name'] : '';
				$user_data = array_merge(
					$user_data,
					array(
						'email' => $user->user_email,
						'role'  => $role_name,
					)
				);
			}
			$users_with_meta[] = $user_data;
		}
		$total_users = $user_query->get_total();
		$total_pages = ceil( $total_users / self::PER_PAGE );
		return array(
			'users'       => $users_with_meta,
			'total_pages' => $user_query->get_total(),
		);
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
	 * Callback for [ckn-show-character-info] shortcode.
	 *
	 * @since 1.0
	 *
	 * @return string
	 */
	public static function show_character_info() {
		$user        = wp_get_current_user();
		$valid_roles = array_intersect( $user->roles, Roles::COOL_KIDS_NETWORK_ROLES );
		if ( ! $valid_roles ) {
			return '';
		}
		$role = reset( $valid_roles );
		global $wp_roles;
		$role_name = isset( $wp_roles->roles[ $role ] ) ? $wp_roles->roles[ $role ]['name'] : '';
		ob_start();
		include CKN_VIEWS_DIR . '/character-info.php';
		return ob_get_clean();
	}

	/**
	 * Display a paginated list of users in a table format.
	 *
	 * @since 1.0
	 *
	 * @param string $role
	 *
	 * @return void
	 */
	public static function display_users_with_pagination( $role ) {
		$users_data = self::get_users_data( $role );
		$users      = $users_data['users'];
		if ( empty( $users ) ) {
			printf( '<p>%s</p>', esc_html__( 'No users found.', 'cool-kids-network' ) );
			return;
		}
		$paged = max( 1, get_query_var( 'paged' ) );

		// Get the total number of users and calculate total pages.
		$total_pages = $users_data['total_pages'];

		$user_fields = array(
			'name'    => __( 'Name', 'cool-kids-network' ),
			'country' => __( 'Country', 'cool-kids-network' ),
		);

		if ( current_user_can( 'view_other_users_email' ) ) {
			$user_fields['email'] = __( 'Email', 'cool-kids-network' );
		}
		if ( current_user_can( 'view_other_users_role' ) ) {
			$user_fields['role'] = __( 'Role', 'cool-kids-network' );
		}
		include CKN_VIEWS_DIR . '/users-list.php';
	}

	/**
	 * Callback for the [ckn-list-users] shortcode.
	 *
	 * @since 1.0
	 *
	 * @return string
	 */
	public static function list_users() {
		$user        = wp_get_current_user();
		$valid_roles = array_intersect( $user->roles, array( 'cooler_kid', 'coolest_kid' ) );
		if ( ! $valid_roles ) {
			return '';
		}
		ob_start();
		self::display_users_with_pagination( reset( $valid_roles ) );
		return ob_get_clean();
	}
}
