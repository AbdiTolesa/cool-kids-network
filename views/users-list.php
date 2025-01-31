<?php
/**
 * Display users list.
 *
 * @since 1.0
 *
 * @var array $user_fields
 * @var array $users
 * @var int   $paged
 * @var int   $total_pages
 */
?>
<table class="table-auto text-base w-full">
	<thead>
		<tr>
			<?php
			foreach ( $user_fields as $field ) {
				printf( '<th class="border-b border-gray-200 p-4 pt-0 pb-3 pl-8 text-left font-medium">%s</th>', esc_html( $field ) );
			}
			?>
		</tr>
	</thead>
	<tbody>
		<?php
		foreach ( $users as $user ) {
			echo '<tr class="hover:bg-gray-50">';
			foreach ( $user_fields as $field => $label ) {
				printf( '<td class="whitespace-nowrap border-b border-gray-300 p-4 pl-8">%s</td>', esc_html( $user[ $field ] ) );
			}
			echo '</tr>';
		}
		?>
	</tbody>
</table>
<div class="text-center">
	<?php
	echo paginate_links( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		array(
			'base'    => '?pg=%#%',
			'current' => $paged,
			'total'   => $total_pages,
		)
	);
	?>
</div>
