<?php

class User_List {
	public static function get_user_data( $role ) {
		$wp_user_search  = new WP_User_Query( array( 'fields' => 'all' ) );
		$users           = $wp_user_search->get_results();
		$meta_key        = 'country';
		$users_with_meta = array();
		foreach ( $users as $user ) {
			$user_meta_value = get_user_meta( $user->ID, $meta_key, true );
			$user_data       = array(
				'name'    => $user->display_name,
				$meta_key => $user_meta_value,
			);
			if ( 'coolest_kid' === $role ) {
				$user_data = array_merge(
					$user_data,
					array(
						'email' => $user->user_email,
						'role'  => $user->roles,
					)
				);
			}
			$users_with_meta[] = $user_data;
		}
		return $users_with_meta;
	}

	/**
	 * Display a paginated list of users in a table format.
	 *
	 * @return void
	 */
	function display_users_with_pagination() {
		// Number of users per page.
		$users_per_page = 10;

		// Get the current page number.
		$paged = max( 1, get_query_var( 'paged' ) );

		// Calculate the offset for pagination.
		$offset = ( $paged - 1 ) * $users_per_page;

		// Query users with pagination.
		$user_query = new WP_User_Query(
			array(
				'number' => $users_per_page,
				'offset' => $offset,
			)
		);

		// Get the total number of users and calculate total pages.
		$total_users = $user_query->get_total();
		$total_pages = ceil( $total_users / $users_per_page );

		// Check if users are found.
		if ( ! empty( $user_query->get_results() ) ) {
			// Start the table.
			echo '<table class="wp-list-table widefat fixed striped">';
			echo '<thead>';
			echo '<tr>';
			echo '<th>' . esc_html__( 'Username', 'cool-kids-network' ) . '</th>';
			echo '<th>' . esc_html__( 'Email', 'cool-kids-network' ) . '</th>';
			echo '<th>' . esc_html__( 'Display Name', 'cool-kids-network' ) . '</th>';
			echo '</tr>';
			echo '</thead>';
			echo '<tbody>';

			// Loop through each user and display their details.
			foreach ( $user_query->get_results() as $user ) {
				echo '<tr>';
				echo '<td>' . esc_html( $user->user_login ) . '</td>';
				echo '<td>' . esc_html( $user->user_email ) . '</td>';
				echo '<td>' . esc_html( $user->display_name ) . '</td>';
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
		} else {
			// No users found.
			echo '<p>' . esc_html__( 'No users found.', 'cool-kids-network' ) . '</p>';
		}
	}

	// Shortcode to display users with pagination
	function users_with_pagination_shortcode() {
		ob_start();
		display_users_with_pagination();
		return ob_get_clean();
	}
	// add_shortcode('list_users', 'users_with_pagination_shortcode');
}
