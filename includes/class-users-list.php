<?php

namespace CoolKidsNetwork;

class Users_List {
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
		$users_per_page = 10;

		$paged = max( 1, get_query_var( 'paged' ) );

		$offset = ( $paged - 1 ) * $users_per_page;

		$user_query  = new \WP_User_Query(
			array(
				'fields' => 'all',
				'number' => $users_per_page,
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
		$total_pages = ceil( $total_users / $users_per_page );
		return array(
			'users'       => $users_with_meta,
			'total_pages' => $user_query->get_total(),
		);
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
		$valid_roles = array_intersect( $user->roles, array( 'cool_kid', 'cooler_kid', 'coolest_kid' ) );
		if ( ! $valid_roles ) {
			return '';
		}
		$role = reset( $valid_roles );
		global $wp_roles;
		$role_name = isset( $wp_roles->roles[ $role ] ) ? $wp_roles->roles[ $role ]['name'] : '';
		ob_start();
		?>
		<ul>
			<li><?php echo esc_html( $user->display_name ); ?></li>
			<li><?php echo esc_html( $user->user_email ); ?></li>
			<li><?php echo esc_html( get_user_meta( $user->ID, 'country', true ) ); ?></li>
			<li><?php echo esc_html( $role_name ); ?></li>
		</ul>
		<?php
		return ob_get_clean();
	}

	/**
	 * Display a paginated list of users in a table format.
	 *
	 * @return void
	 */
	public static function display_users_with_pagination( $role ) {
		$users_data = self::get_users_data( $role );
		$users      = $users_data['users'];
		if ( empty( $users ) ) {
			echo '<p>' . esc_html__( 'No users found.', 'cool-kids-network' ) . '</p>';
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
			$user_fields['role']  = __( 'Role', 'cool-kids-network' );
		}
		// Start the table.
		echo '<table class="wp-list-table widefat fixed striped">';
		echo '<thead>';
		echo '<tr>';
		foreach ( $user_fields as $field ) {
			printf( '<th>%s</th>', esc_html( $field ) );
		}
		echo '</tr>';
		echo '</thead>';
		echo '<tbody>';

		foreach ( $users as $user ) {
			echo '<tr>';
			foreach ( $user_fields as $field => $label ) {
				echo '<td>' . esc_html( $user[ $field ] ) . '</td>';
			}
			echo '</tr>';
		}

		echo '</tbody>';
		echo '</table>';

		// Display pagination.
		echo '<div class="pagination">';
		echo paginate_links(
			array(
				'base'      => get_pagenum_link( 1 ) . '%_%',
				'format'    => 'page/%#%',
				'current'   => $paged,
				'total'     => $total_pages,
				'prev_text' => __( '« Previous', 'cool-kids-network' ),
				'next_text' => __( 'Next »', 'cool-kids-network' ),
			)
		);
		echo '</div>';
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
