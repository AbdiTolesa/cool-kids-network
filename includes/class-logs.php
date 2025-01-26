<?php
namespace CoolKidsNetwork;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class Logs extends \WP_List_Table {

	const PER_PAGE = 20;

	/**
	 * Initialize the log table list.
	 *
	 * @since 1.0
	 */
	public function __construct() {
		parent::__construct(
			array(
				'singular' => 'log',
				'plural'   => 'logs',
				'ajax'     => false,
			)
		);
	}

	/**
	 * Prepare table list items.
	 *
	 * @since 1.0
	 *
	 * @global wpdb $wpdb
	 */
	public function prepare_items() {
		global $wpdb;

		$this->prepare_column_headers();

		$where  = $this->get_items_query_where();
		$order  = $this->get_items_query_order();
		$limit  = $this->get_items_query_limit();
		$offset = $this->get_items_query_offset();

		$query_items = 'SELECT * FROM ' . STLN_LOGS_TABLE . " {$where} {$order} {$limit} {$offset}";

		$this->items = $wpdb->get_results( $query_items, ARRAY_A ); // phpcs:ignore WordPress.DB

		$query_count = 'SELECT COUNT(log_id) FROM ' . STLN_LOGS_TABLE . " {$where}";
		$total_items = $wpdb->get_var( $query_count ); // phpcs:ignore WordPress.DB

		$this->set_pagination_args(
			array(
				'total_items' => $total_items,
				'per_page'    => self::PER_PAGE,
				'total_pages' => ceil( $total_items / self::PER_PAGE ),
			)
		);
	}

	/**
	 * Get prepared ORDER BY clause for items query
	 *
	 * @since 1.0
	 *
	 * @return string Prepared ORDER BY clause for items query.
	 */
	protected function get_items_query_order() {
		$valid_orders = array( 'logged_by', 'message', 'datetime' );
		if ( ! empty( $_REQUEST['orderby'] ) && in_array( wp_unslash( $_REQUEST['orderby'] ), $valid_orders, true ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput, WordPress.DB
			$by = wc_clean( $_REQUEST['orderby'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput
		} else {
			$by = 'datetime';
		}
		$by = esc_sql( $by );

		if ( ! empty( $_REQUEST['order'] ) && 'asc' === strtolower( sanitize_text_field( wp_unslash( $_REQUEST['order'] ) ) ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput
			$order = 'ASC';
		} else {
			$order = 'DESC';
		}

		return "ORDER BY {$by} {$order}, log_id {$order}";
	}

	protected function get_items_query_limit() {
		global $wpdb;

		$per_page = self::PER_PAGE;

		return $wpdb->prepare( 'LIMIT %d', $per_page );
	}


	/**
	 * Get prepared OFFSET clause for items query
	 *
	 * @since 1.0
	 *
	 * @global wpdb $wpdb
	 *
	 * @return string Prepared OFFSET clause for items query.
	 */
	protected function get_items_query_offset() {
		global $wpdb;

		$per_page     = self::PER_PAGE;
		$current_page = $this->get_pagenum();
		if ( 1 < $current_page ) {
			$offset = $per_page * ( $current_page - 1 );
		} else {
			$offset = 0;
		}

		return $wpdb->prepare( 'OFFSET %d', $offset );
	}

	/**
	 * Get list columns.
	 *
	 * @since 1.0
	 *
	 * @return array
	 */
	public function get_columns() {
		return array(
			'cb'        => '<input type="checkbox" />',
			'datetime'  => __( 'Date', 'cool-kids-network' ),
			'logged_by' => __( 'Logged by', 'cool-kids-network' ),
			'message'   => __( 'Message', 'cool-kids-network' ),
			'data'      => __( 'Data', 'cool-kids-network' ),
		);
	}

	/**
	 * Set _column_headers property for table list
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	protected function prepare_column_headers() {
		$this->_column_headers = array(
			$this->get_columns(),
			array(),
			$this->get_sortable_columns(),
		);
	}

	/**
	 * Column cb.
	 *
	 * @since 1.0
	 *
	 * @param  array $log
	 * @return string
	 */
	public function column_cb( $log ) {
		return sprintf( '<input type="checkbox" name="log[]" value="%1$s" />', esc_attr( $log['log_id'] ) );
	}

	/**
	 * Datetime column.
	 *
	 * @since 1.0
	 *
	 * @param  array $log
	 * @return string
	 */
	public function column_datetime( $log ) {
		return esc_html(
			mysql2date(
				'Y-m-d H:i:s',
				$log['datetime']
			)
		);
	}

	/**
	 * 'Logged by' column.
	 *
	 * @since 1.0
	 *
	 * @param  array $log
	 * @return string
	 */
	public function column_logged_by( $log ) {
		return sprintf(
			'<pre>%s</pre>',
			esc_html( $log['logged_by'] )
		);
	}

	/**
	 * Message column.
	 *
	 * @since 1.0
	 *
	 * @param  array $log
	 * @return string
	 */
	public function column_message( $log ) {
		return sprintf(
			'<pre>%s</pre>',
			esc_html( $log['message'] )
		);
	}

	/**
	 * Data column.
	 *
	 * @since 1.0
	 *
	 * @param  array $log
	 * @return string
	 */
	public function column_data( $log ) {
		return esc_html( $log['data'] );
	}

	/**
	 * Get a list of sortable columns.
	 *
	 * @return array
	 */
	protected function get_sortable_columns() {
		return array(
			'datetime'  => array( 'datetime', true ),
			'logged_by' => array( 'logged_by', true ),
			'message'   => array( 'message', true ),
		);
	}

	/**
	 * Get bulk actions.
	 *
	 * @since 1.0
	 *
	 * @return array
	 */
	protected function get_bulk_actions() {
		return array(
			'delete' => __( 'Delete', 'cool-kids-network' ),
		);
	}

	/**
	 * Adds a log entry.
	 *
	 * @since 1.0
	 *
	 * @param string $logged_by The function that is logging the stuff.
	 * @param string $message   The log message.
	 * @param array  $data      An array that holds information about the variables involved.
	 */
	public static function add( $logged_by, $message, $data = array() ) {
		global $wpdb;
		$wpdb->insert( // phpcs:ignore WordPress.DB
			STLN_LOGS_TABLE,
			array(
				'logged_by' => $logged_by,
				'message'   => $message,
				'data'      => wp_json_encode( $data ),
			),
		);
	}

	/**
	 * Clears the logs table.
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public static function clear_logs() {
		global $wpdb;
		$wpdb->query( 'DELETE FROM ' . STLN_LOGS_TABLE ); // phpcs:ignore WordPress.DB
	}

	/**
	 * Process bulk actions initiated from the logs list table.
	 *
	 * @since 1.0
	 *
	 * @global wpdb $wpdb
	 *
	 * @return void
	 */
	public static function handle_list_table_bulk_actions() {
		$log_ids = (array) filter_input( INPUT_POST, 'log', FILTER_CALLBACK, array( 'options' => 'absint' ) );
		if ( empty( $log_ids ) ) {
			return;
		}
		global $wpdb;
		$sql = 'DELETE FROM ' . STLN_LOGS_TABLE . ' WHERE log_id IN (' . implode( ',', $log_ids ) . ')';

		$wpdb->query( $sql ); // phpcs:ignore WordPress.DB
	}
}
