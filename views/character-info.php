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
<div class="flex flex-col items-center bg-white border border-gray-200 rounded-lg shadow-sm md:flex-row md:max-w-xl hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700">
	<img class="object-cover rounded-full h-96 md:h-auto" src="<?php echo get_avatar_url( get_current_user_id( ) ); ?>" alt="">
	<div class="flex-1 min-w-0 ms-4 grid grid-cols-1 gap-6 sm:grid-cols-2">
		<div class="content-center">
			<p class="text-sm font-medium text-gray-900 truncate dark:text-white"><?php echo esc_html( $user->display_name ); ?></p>
			<p class="text-sm text-gray-500 truncate dark:text-gray-400"><?php echo esc_html( $user->user_email ); ?></p>
		</div>
		<div class="flex flex-col py-3">
			<dt class="mb-1 text-gray-500 dark:text-gray-400 text-sm"><?php esc_html_e( 'Country', 'cool-kids-network' ); ?></dt>
			<dd class="font-semibold dark:text-white text-sm"><?php echo esc_html( get_user_meta( $user->ID, 'country', true ) ); ?></dd>
			<dt class="mb-1 text-gray-500 dark:text-gray-400 text-sm"><?php esc_html_e( 'Role', 'cool-kids-network' ); ?></dt>
			<dd class="font-semibold dark:text-white text-sm"><?php echo esc_html( $role_name ); ?></dd>
		</div>
	</div>
</div>
