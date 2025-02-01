<div class="w-full bg-white rounded-lg shadow md:mt-0 xl:p-0">
	<div class="p-6 space-y-4 md:space-y-6 sm:p-8">	
	<h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl">
			<?php esc_html_e( 'Sign in to your account', 'cool-kids-network' ); ?>
		</h1>	
		<?php
		wp_login_form();
		?>
		<p class="text-sm font-medium text-gray-700">
			<?php esc_html_e( 'Don’t have an account yet?', 'cool-kids-network' ); ?> 
			<a href="<?php echo esc_url( home_url( self::get_signup_page_slug() ) ); ?>" class="text-base font-semibold underline text-gray-800">
				<?php esc_html_e( 'Sign up', 'cool-kids-network' ); ?>
			</a>
		</p>
	</div>
</div>
