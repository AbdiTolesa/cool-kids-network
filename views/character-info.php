<?php
/**
 * Displays character data.
 *
 * @since 1.0
 *
 * @var \WP_User $user
 * @var string  $role_name
 */
?>
<ul>
	<li><?php echo esc_html( $user->display_name ); ?></li>
	<li><?php echo esc_html( $user->user_email ); ?></li>
	<li><?php echo esc_html( get_user_meta( $user->ID, 'country', true ) ); ?></li>
	<li><?php echo esc_html( $role_name ); ?></li>
</ul>
