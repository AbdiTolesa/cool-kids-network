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
				printf( '<th class="border-b border-gray-200 p-4 pt-0 pb-3 pl-8 text-left font-medium text-gray-400 dark:border-gray-600 dark:text-gray-200">%s</th>', esc_html( $field ) );
			}
			?>
		</tr>
	</thead>
	<tbody>
		<?php
		foreach ( $users as $user ) {
			echo '<tr>';
			foreach ( $user_fields as $field => $label ) {
				printf( '<td class="border-b border-gray-100 dark:border-gray-700 dark:text-gray-400 p-4 pl-8 text-gray-500">%s</td>', esc_html( $user[ $field ] ) );
			}
			echo '</tr>';
		}
		?>
	</tbody>
</table>
<div class="text-center">
	<?php
	echo paginate_links(
		array(
			'base'      => '?pg=%#%',
			'current'   => $paged,
			'total'     => $total_pages,
		)
	);
	?>
</div>
