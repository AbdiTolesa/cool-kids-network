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
<table class="wp-list-table widefat fixed striped">
	<thead>
		<tr>
			<?php
			foreach ( $user_fields as $field ) {
				printf( '<th>%s</th>', esc_html( $field ) );
			}
			?>
		</tr>
	</thead>
	<tbody>
		<?php
		foreach ( $users as $user ) {
			echo '<tr>';
			foreach ( $user_fields as $field => $label ) {
				printf( '<td>%s</td>', esc_html( $user[ $field ] ) );
			}
			echo '</tr>';
		}
		?>
	</tbody>
</table>
<div class="pagination">
	<?php
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
	?>
</div>
