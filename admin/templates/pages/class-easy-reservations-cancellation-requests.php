<?php
/**
 * This file is used for rendering the saved cancellation requests for reservations.
 *
 * @since      1.0.0
 * @package    Easy_Reservations
 * @subpackage Easy_Reservations/admin/templates/pages
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Include the class file if previously it does not exist.
if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * Class that will list all the cancellation requests for the reservations.
 */
class Easy_Reservations_Cancellation_Requests extends WP_List_Table {

	/**
	 * Set up a constructor that references the parent constructor.
	 * We use the parent reference to set some default configs.
	 */
	public function __construct() {
		global $status, $page;

		parent::__construct(
			array(
				'singular' => __( 'reservation-cancellation record', 'easy-reservations' ),
				'plural'   => __( 'reservation-cancellation records', 'easy-reservations' ),
				'ajax'     => false,
			)
		);

		add_action( 'admin_head', array( $this, 'ersrv_admin_header_callback' ) );
	}

	/**
	 * Check for the current page in admin header.
	 */
	public function ersrv_admin_header_callback() {
		$page = filter_input( INPUT_GET, 'page', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );

		// Return, if it's not the cancellation requests page.
		if ( is_null( $page ) || 'reservation-cancellation-requests' !== $page ) {
			return;
		}
	}

	/**
	 * Text to be displayed when no items are found.
	 */
	public function no_items() {
		esc_html_e( 'No cancellation requests found.', 'easy-reservations' );
	}

	/**
	 * Get the table data
	 *
	 * @return array
	 */
	private function table_data() {
		global $wpdb;
		$data                      = array();
		$wc_order_items_meta_table = "{$wpdb->prefix}woocommerce_order_itemmeta";
		$cancellation_requests     = $wpdb->get_results( "SELECT `order_item_id` FROM `{$wc_order_items_meta_table}` WHERE `meta_key` = 'ersrv_cancellation_request'" );

		// Return blank data, if there is no cancellation request.
		if ( empty( $cancellation_requests ) || ! is_array( $cancellation_requests ) ) {
			return array();
		}

		// Iterate through the requests to prepare the data array.
		foreach ( $cancellation_requests as $cancellation_request_item_id ) {
			$line_item_id = $cancellation_request_item_id->order_item_id;
			$order_id     = wc_get_order_id_by_order_item_id( $line_item_id );
			$wc_order     = wc_get_order( $order_id );

			// Checkbox column.
			$temp['cb'] = '<input type="checkbox" />';

			// Get the date time of cancellation request.
			$cancellation_request_datetime = wc_get_order_item_meta( $line_item_id, 'ersrv_cancellation_request_time', true );
			$temp['date_time']             = gmdate( ersrv_get_php_date_format() . ' H:i', $cancellation_request_datetime );

			// Get the item details now.
			$product_id      = wc_get_order_item_meta( $line_item_id, '_product_id', true );
			$product_name    = get_the_title( $product_id );
			$product_string  = "#{$product_id} {$product_name}";
			$temp['item']    = '<a href="' . get_edit_post_link( $product_id ) . '" title="' . $product_string . '">' . $product_string . '</a>';
			$temp['item_id'] = $line_item_id;

			// Get the item subtotal.
			$item_subtotal         = (float) wc_get_order_item_meta( $line_item_id, '_line_subtotal', true );
			$temp['item_subtotal'] = wc_price( $item_subtotal );

			// Get the order data.
			$customer_first_name = get_post_meta( $order_id, '_billing_first_name', true );
			$customer_last_name  = get_post_meta( $order_id, '_billing_last_name', true );
			$order_string        = "#{$order_id} {$customer_first_name} {$customer_last_name}";
			$temp['order']       = '<a href="' . get_edit_post_link( $order_id ) . '" title="' . $order_string . '">' . $order_string . '</a>';
			$temp['order_id']    = $order_id;

			// Get the order status.
			$order_status         = $wc_order->get_status();
			$status_string        = ersrv_get_readable_order_status( $order_status );
			$temp['order_status'] = '<mark class="order-status status-' . $order_status . ' tips"><span>' . $status_string . '</span></mark>';

			// Get the cancellation status.
			$cancellation_status         = wc_get_order_item_meta( $line_item_id, 'ersrv_cancellation_request_status', true );
			$cancellation_status         = ( ! empty( $cancellation_status ) ) ? ucfirst( $cancellation_status ) : __( 'Pending', 'easy-reservations' );
			$temp['cancellation_status'] = $cancellation_status;

			// Push all the data into an array.
			$data[] = $temp;
		}

		return $data;
	}

	/**
	 * Define what data to show on each column of the table
	 *
	 * @param array  $item        Data.
	 * @param string $column_name - Current column name.
	 *
	 * @return mixed
	 */
	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'cb':
			case 'date_time':
			case 'item':
			case 'item_subtotal':
			case 'order':
			case 'order_status':
			case 'cancellation_status':
				return $item[ $column_name ];

			default:
				return '--';
		}
	}

	/**
	 * Return the sortable columns.
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
		$sortable_columns = array(
			'date_time' => array( 'date_time', false ),
		);

		return $sortable_columns;
	}

	/**
	 * Override the parent columns method. Defines the columns to use in your listing table
	 *
	 * @return array
	 */
	public function get_columns() {
		$columns = array(
			'cb'                  => '<input type="checkbox" />',
			'item'                => __( 'Item', 'easy-reservations' ),
			'date_time'           => __( 'DateTime', 'easy-reservations' ),
			'item_subtotal'       => __( 'Item Subtotal', 'easy-reservations' ),
			'order'               => __( 'Order', 'easy-reservations' ),
			'order_status'        => __( 'Order Status', 'easy-reservations' ),
			'cancellation_status' => __( 'Cancellation Status', 'easy-reservations' ),
		);

		return $columns;
	}

	/**
	 * The bulk actions array.
	 *
	 * @return array
	 */
	public function get_bulk_actions() {
		$actions = array(
			'approve' => __( 'Approve', 'easy-reservations' ),
			'decline' => __( 'Decline', 'easy-reservations' ),
		);
		/**
		 * The hook to manage the bulk actions.
		 * Displayed on the cancellation requests page.
		 *
		 * @param array $actions Holds the actions.
		 * @return array
		 * @since 1.0.0
		 */
		$actions = apply_filters( 'ersrv_cancellation_requests_bulk_actions', $actions );

		return $actions;
	}


	/**
	 * Prepare the items for the table to process
	 *
	 * @return void
	 */
	public function prepare_items() {
		// Check if a search was performed.
		$searched_keyword = filter_input( INPUT_GET, 's', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );
		$searched_keyword = ( ! is_null( $searched_keyword ) ) ? wp_unslash( trim( $searched_keyword ) ) : '';

		// Column headers.
		$this->_column_headers = $this->get_column_info();

		// Fetch table data.
		$data = $this->table_data();

		// Check for bulk action.
		$bulk_action = $this->current_action();
		if ( false !== $bulk_action ) {
			$this->process_bulk_action( $data, $bulk_action );
		}

		// Filter the table data in case of a search.
		if ( $searched_keyword ) {
			$data = $this->filter_table_data( $data, $searched_keyword );
		}

		$per_page     = $this->get_items_per_page( 'ersrv_cancellation_requests_per_page', 20 );
		$current_page = $this->get_pagenum();

		// Slice the data based on pagination.
		$found_data = array_slice( $data, ( ( $current_page - 1 ) * $per_page ), $per_page );

		// Set the pagination data.
		$total_items = count( $data );
		$this->set_pagination_args(
			array(
				'total_items' => $total_items,
				'per_page'    => $per_page,
				'total_pages' => ceil( $total_items / $per_page ),
			)
		);

		$this->items = $found_data;
	}

	/**
	 * Filter the table data for search.
	 *
	 * @param array  $table_data List table data.
	 * @param string $search_key Search keyword.
	 *
	 * @return array
	 */
	public function filter_table_data( $table_data, $search_key ) {
		$filtered_table_data = array_values(
			array_filter(
				$table_data,
				function ( $row ) use ( $search_key ) {
					foreach ( $row as $row_val ) {
						if ( stripos( $row_val, $search_key ) !== false ) {
							return true;
						}
					}
				}
			)
		);

		return $filtered_table_data;
	}

	/**
	 * The callback where the bulk actions are processed.
	 *
	 * @param array  $data Item data.
	 * @param string $bulk_action Bulk action name.
	 */
	public function process_bulk_action( $data, $bulk_action ) {
		$posted_array   = filter_input_array( INPUT_GET );
		$selected_items = ( ! empty( $posted_array['bulk_selected_item'] ) ) ? $posted_array['bulk_selected_item'] : array();

		// If there is no item selected.
		if ( empty( $selected_items ) || ! is_array( $selected_items ) ) {
			?>
			<div class="error">
				<p><?php echo wp_kses_post( __( 'There is no or invalid item selected to proceed with the bulk action.', 'easy-reservations' ) ); ?></p>
			</div>
			<?php
		} else {
			// Iterate through the selected items.
			foreach ( $selected_items as $selected_item ) {
				$splitted_selected_item = explode( '|', $selected_item );
				$line_item_id           = ( ! empty( $splitted_selected_item[0] ) ) ? $splitted_selected_item[0] : '';
				$order_id               = ( ! empty( $splitted_selected_item[1] ) ) ? $splitted_selected_item[1] : '';

				// Skip, if the line item ID or the order ID is unavailable.
				if ( empty( $line_item_id ) || empty( $order_id ) ) {
					continue;
				}

				// If the approve action is requested.
				if ( 'approve' === $bulk_action ) {
					ersrv_approve_reservation_cancellation_request( $order_id, $line_item_id );
				} elseif ( 'decline' === $bulk_action ) {
					ersrv_decline_reservation_cancellation_request( $line_item_id );
				}

				/**
				 * This hook executes on the cancellation requests page.
				 *
				 * This hook helps in processing the custom bulk actions.
				 *
				 * @param array  $data Table data.
				 * @param string $bulk_action Bulk action name.
				 * @param int    $order_id WooCommerce order ID.
				 * @param int    $line_item_id WooCommerce order line item ID.
				 * @since 1.0.0
				 */
				do_action( 'ersrv_processed_reservation_cancellation_requests_bulk_action', $data, $bulk_action, $order_id, $line_item_id );
			}
		}
	}

	/**
	 * The column item to have row actions.
	 *
	 * @param array $item Item data.
	 * @return string
	 */
	public function column_item( $item ) {
		$item_id        = ( ! empty( $item['item_id'] ) ) ? $item['item_id'] : '';
		$order_id       = ( ! empty( $item['order_id'] ) ) ? $item['order_id'] : '';
		$current_status = ( ! empty( $item['cancellation_status'] ) ) ? $item['cancellation_status'] : '';

		// Build row actions.
		$actions = array(
			'approve_request' => $this->ersrv_approve_cancellation_request_button(),
			'decline_request' => $this->ersrv_decline_cancellation_request_button(),
		);

		// If the current status is approved, enable the decline action.
		if ( 'Approved' === $current_status ) {
			unset( $actions['approve_request'] );
		}

		// If the current status is declined, enable the approve action.
		if ( 'Declined' === $current_status ) {
			unset( $actions['decline_request'] );
		}

		/**
		 * This hook runs on the admin page where all the cancellation requests are listed.
		 *
		 * This filter helps manage the row actions per the cancellation requested item.
		 *
		 * @param array $actions Row actions.
		 * @param array $item Reservation item details.
		 * @return array
		 * @since 1.0.0
		 */
		$actions = apply_filters( 'ersrv_cancellation_requested_reservation_item_row_actions', $actions, $item );

		// Return the title contents.
		/* translators: 1: %s: item name, 2: %s: row actions, 3: %s: div tag open, 4: %s: div tag closed */
		return sprintf(
			'%1$s%3$s%2$s%4$s',
			$item['item'],
			$this->row_actions( $actions ),
			'<div class="ersrv-cancellation-request-actions" data-item="' . $item_id . '" data-order="' . $order_id . '">',
			'</div>'
		);
	}

	/**
	 * Return the approve button for cancellation requested reservation item.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function ersrv_approve_cancellation_request_button() {
		/* translators: 1: %s: anchor tag open, 2: %s: anchor tag closed */
		return sprintf( __( '%1$sApprove%2$s', 'easy-reservations' ), '<a href="javascript:void(0);" class="approve-request" title="' . __( 'Approve this cancellation request.', 'easy-reservations' ) . '">', '</a>' );
	}

	/**
	 * Return the decline button for cancellation requested reservation item.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function ersrv_decline_cancellation_request_button() {
		/* translators: 1: %s: anchor tag open, 2: %s: anchor tag closed */
		return sprintf( __( '%1$sDecline%2$s', 'easy-reservations' ), '<a href="javascript:void(0);" class="decline" title="' . esc_html__( 'Decline this cancellation request.', 'easy-reservations' ) . '">', '</a>' );
	}

	/**
	 * The column item to have row actions.
	 *
	 * @param array $item Item data.
	 * @return string
	 */
	public function column_cb( $item ) {
		$item_id  = ( ! empty( $item['item_id'] ) ) ? $item['item_id'] : '';
		$order_id = ( ! empty( $item['order_id'] ) ) ? $item['order_id'] : '';

		return sprintf(
			'<input type="checkbox" name="%1$s" value="%2$s" />',
			'bulk_selected_item[]',
			"{$item_id}|{$order_id}"
		);
	}
}
