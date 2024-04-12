<?php
/**
 * This file is used for writing all the re-usable custom functions.
 *
 * @since 1.0.0
 * @package Easy_Reservations
 * @subpackage Easy_Reservations/includes
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

/**
 * Get plugin setting by setting index.
 *
 * @param string $setting Holds the setting index.
 * @return boolean|string|array|int
 * @since 1.0.0
 */
function ersrv_get_plugin_settings( $setting ) {
	switch ( $setting ) {
		case 'ersrv_item_availability_calendar_color':
			$data = get_option( $setting );
			$data = ( ! empty( $data ) && ! is_bool( $data ) ) ? $data : 'green';
			break;

		case 'ersrv_archive_page_add_to_cart_button_text':
			$data = get_option( $setting );
			$data = ( ! empty( $data ) && ! is_bool( $data ) ) ? $data : __( 'Reserve It', 'easy-reservations' );
			break;

		case 'ersrv_product_single_page_add_to_cart_button_text':
			$data = get_option( $setting );
			$data = ( ! empty( $data ) && ! is_bool( $data ) ) ? $data : __( 'Reserve It', 'easy-reservations' );
			break;

		case 'ersrv_reservation_receipt_store_name':
			$data = get_option( $setting );
			$data = ( ! empty( $data ) && ! is_bool( $data ) ) ? $data : '';
			break;

		case 'ersrv_reservation_receipt_store_contact_number':
			$data = get_option( $setting );
			$data = ( ! empty( $data ) && ! is_bool( $data ) ) ? $data : '';
			break;

		case 'ersrv_reservation_receipt_store_logo_media_id':
			$data = get_option( $setting );
			$data = ( ! empty( $data ) && ! is_bool( $data ) ) ? $data : '';
			break;

		case 'ersrv_easy_reservations_receipt_for_order_statuses':
			$data = get_option( $setting );
			$data = ( ! empty( $data ) && ! is_bool( $data ) ) ? $data : array();
			break;

		case 'ersrv_easy_reservations_receipt_button_text':
			$data = get_option( $setting );
			$data = ( ! empty( $data ) && ! is_bool( $data ) ) ? $data : __( 'Download Reservation Receipt', 'easy-reservations' );
			break;

		case 'ersrv_easy_reservations_reservation_thanks_note':
			$data = get_option( $setting );
			$data = ( ! empty( $data ) && ! is_bool( $data ) ) ? $data : '';
			break;

		case 'ersrv_easy_reservations_receipt_footer_text':
			$data = get_option( $setting );
			$data = ( ! empty( $data ) && ! is_bool( $data ) ) ? $data : '';
			break;

		case 'ersrv_google_maps_api_key':
			$data = get_option( $setting );
			$data = ( ! empty( $data ) && ! is_bool( $data ) ) ? $data : '';
			break;

		case 'ersrv_datepicker_date_format':
			$data = get_option( $setting );
			$data = ( ! empty( $data ) && ! is_bool( $data ) ) ? $data : 'mm/dd/yy';
			break;

		case 'ersrv_driving_license_validation':
			$data = get_option( $setting );
			$data = ( ! empty( $data ) && ! is_bool( $data ) ) ? $data : 'no';
			break;

		case 'ersrv_reminder_email_send_before_days':
			$data = get_option( $setting );
			$data = ( ! empty( $data ) && ! is_bool( $data ) ) ? (int) $data : 0;
			break;

		case 'ersrv_reservation_onboarding_time':
			$data = get_option( $setting );
			$data = ( ! empty( $data ) && ! is_bool( $data ) ) ? gmdate( 'h:iA', strtotime( $data ) ) : '09:00AM';
			break;

		case 'ersrv_reservation_offboarding_time':
			$data = get_option( $setting );
			$data = ( ! empty( $data ) && ! is_bool( $data ) ) ? gmdate( 'h:iA', strtotime( $data ) ) : '10:00AM';
			break;

		case 'ersrv_enable_reservation_cancellation':
			$data = get_option( $setting );
			$data = ( ! empty( $data ) && ! is_bool( $data ) ) ? $data : 'no';
			break;

		case 'ersrv_cancel_reservations_button_text':
			$data = get_option( $setting );
			$data = ( ! empty( $data ) && ! is_bool( $data ) ) ? $data : __( 'Request Cancellation', 'easy-reservations' );
			break;

		case 'ersrv_cancel_reservation_request_before_days':
			$data = get_option( $setting );
			$data = ( ! empty( $data ) && ! is_bool( $data ) ) ? (int) $data : -1;
			break;

		case 'ersrv_enable_reservation_rental_agreement':
			$data = get_option( $setting );
			$data = ( ! empty( $data ) && ! is_bool( $data ) ) ? $data : 'no';
			break;

		case 'ersrv_rental_agreement_file_id':
			$data = get_option( $setting );
			$data = ( ! empty( $data ) && ! is_bool( $data ) ) ? (int) $data : -1;
			break;

		case 'ersrv_enable_reservation_edit':
			$data = get_option( $setting );
			$data = ( ! empty( $data ) && ! is_bool( $data ) ) ? $data : 'no';
			break;

		case 'ersrv_edit_reservation_button_text':
			$data = get_option( $setting );
			$data = ( ! empty( $data ) && ! is_bool( $data ) ) ? $data : __( 'Edit Reservation', 'easy-reservations' );
			break;

		case 'ersrv_enable_time_with_date':
			$data = get_option( $setting );
			$data = ( ! empty( $data ) && ! is_bool( $data ) ) ? $data : 'no';
			break;

		case 'ersrv_trim_zeros_from_price':
			$data = get_option( $setting );
			$data = ( ! empty( $data ) && ! is_bool( $data ) ) ? $data : 'no';
			break;

		case 'ersrv_enable_receipt_button_my_account_orders_list':
			$data = get_option( $setting );
			$data = ( ! empty( $data ) && ! is_bool( $data ) ) ? $data : 'no';
			break;

		default:
			$data = -1;
	}

	return $data;
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_get_custom_product_type_slug' ) ) {
	/**
	 * Get the custom product type slug.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	function ersrv_get_custom_product_type_slug() {

		return 'reservation';
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_get_custom_product_type_label' ) ) {
	/**
	 * Get the custom product type label.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	function ersrv_get_custom_product_type_label() {
		$product_type_label = __( 'Reservation', 'easy-reservations' );

		/**
		 * This hook fires in admin panel on the item settings page.
		 *
		 * This filter will help in modifying the product type label.
		 *
		 * @param string $product_type_label Holds the product type label.
		 * @return string
		 */
		return apply_filters( 'ersrv_product_type_label', $product_type_label );
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_get_posts' ) ) {
	/**
	 * Get the posts.
	 *
	 * @param string $post_type Post type.
	 * @param int    $paged Paged value.
	 * @param int    $posts_per_page Posts per page.
	 * @return object
	 * @since 1.0.0
	 */
	function ersrv_get_posts( $post_type = 'post', $paged = 1, $posts_per_page = '' ) {
		// Prepare the arguments array.
		$args = array(
			'post_type'      => $post_type,
			'paged'          => $paged,
			'posts_per_page' => ( ! empty( $posts_per_page ) ) ? $posts_per_page : get_option( 'posts_per_page' ),
			'post_status'    => 'publish',
			'fields'         => 'ids',
			'orderby'        => 'date',
			'order'          => 'DESC',
		);

		/**
		 * Posts/custom posts listing arguments filter.
		 *
		 * This filter helps to modify the arguments for retreiving posts of default/custom post types.
		 *
		 * @param array $args Holds the post arguments.
		 * @return array
		 */
		$args = apply_filters( 'ersrv_posts_args', $args );

		return new WP_Query( $args );
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_get_active_stylesheet' ) ) {
	/**
	 * Get the active stysheet URL.
	 *
	 * @param string $current_theme Holds the current theme slug.
	 * @return string
	 * @since 1.0.0
	 */
	function ersrv_get_active_stylesheet( $current_theme ) {
		switch ( $current_theme ) {
			case 'twentysixteen':
				return array(
					'url'  => ERSRV_PLUGIN_URL . 'public/css/core/easy-reservations-twentysixteen.css',
					'path' => ERSRV_PLUGIN_PATH . 'public/css/core/easy-reservations-twentysixteen.css',
				);

			case 'twentyseventeen':
				return array(
					'url'  => ERSRV_PLUGIN_URL . 'public/css/core/easy-reservations-twentyseventeen.css',
					'path' => ERSRV_PLUGIN_PATH . 'public/css/core/easy-reservations-twentyseventeen.css',
				);

			case 'twentynineteen':
				return array(
					'url'  => ERSRV_PLUGIN_URL . 'public/css/core/easy-reservations-twentynineteen.css',
					'path' => ERSRV_PLUGIN_PATH . 'public/css/core/easy-reservations-twentynineteen.css',
				);

			case 'twentytwenty':
				return array(
					'url'  => ERSRV_PLUGIN_URL . 'public/css/core/easy-reservations-twentytwenty.css',
					'path' => ERSRV_PLUGIN_PATH . 'public/css/core/easy-reservations-twentytwenty.css',
				);

			case 'twentytwentyone':
				return array(
					'url'  => ERSRV_PLUGIN_URL . 'public/css/core/easy-reservations-twentytwentyone.css',
					'path' => ERSRV_PLUGIN_PATH . 'public/css/core/easy-reservations-twentytwentyone.css',
				);

			case 'storefront':
				return array(
					'url'  => ERSRV_PLUGIN_URL . 'public/css/core/easy-reservations-storefront.css',
					'path' => ERSRV_PLUGIN_PATH . 'public/css/core/easy-reservations-storefront.css',
				);

			default:
				return array(
					'url'  => ERSRV_PLUGIN_URL . 'public/css/core/easy-reservations-other.css',
					'path' => ERSRV_PLUGIN_PATH . 'public/css/core/easy-reservations-other.css',
				);
		}
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_get_icalendar_formatted_date' ) ) {
	/**
	 * Get the iCal formatted datetime from timestamp.
	 *
	 * @param string  $timestamp Holds the linux timestamp.
	 * @param boolean $include_time Whether to include time in the formatted datetime.
	 * @return string
	 * @since 1.0.0
	 */
	function ersrv_get_icalendar_formatted_date( $timestamp = '', $include_time = true ) {

		return gmdate( 'Ymd' . ( $include_time ? '\THis' : '' ), $timestamp );
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_get_wc_product_type' ) ) {
	/**
	 * Get the product type from ID.
	 *
	 * @param int $product_id Holds the WooCommerce product type.
	 * @return boolean|string
	 * @since 1.0.0
	 */
	function ersrv_get_wc_product_type( $product_id = 0 ) {
		// Return false, if the item ID is 0.
		if ( 0 === $product_id || ! is_int( $product_id ) ) {
			return false;
		}

		$wc_product = wc_get_product( $product_id );

		return ( false === $wc_product ) ? false : $wc_product->get_type();
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_order_is_reservation' ) ) {
	/**
	 * Check if the order is reservation.
	 *
	 * @param WC_Order $wc_order Order data.
	 * @return boolean
	 * @since 1.0.0
	 */
	function ersrv_order_is_reservation( $wc_order ) {
		// Get the order line items.
		$line_items = $wc_order->get_items();

		// Return, if there are no order items.
		if ( empty( $line_items ) || ! is_array( $line_items ) ) {
			return false;
		}

		$custom_product_type = ersrv_get_custom_product_type_slug();

		// Iterate through the items to check if any reservation has been booked.
		foreach ( $line_items as $line_item ) {
			$line_item_product_id = $line_item->get_product_id();

			// If the item ID is available.
			$line_item_type = ersrv_get_wc_product_type( $line_item_product_id );

			// Check if the product is of reservation type.
			if ( false !== $line_item_type && $custom_product_type === $line_item_type ) {
				return true;
			}
		}

		return false;
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_get_amenity_html' ) ) {
	/**
	 * Get the amenity HTML.
	 *
	 * @param string $amenity_data Holds the amenity data.
	 * @return string
	 * @since 1.0.0
	 */
	function ersrv_get_amenity_html( $amenity_data ) {
		$title     = ( ! empty( $amenity_data['title'] ) ) ? $amenity_data['title'] : '';
		$cost      = ( ! empty( $amenity_data['cost'] ) ) ? $amenity_data['cost'] : '';
		$cost_type = ( ! empty( $amenity_data['cost_type'] ) ) ? $amenity_data['cost_type'] : '';
		ob_start();
		?>
		<p class="form-field reservation_amenity_field amenities-row">
			<input type="text" value="<?php echo esc_html( $title ); ?>" required name="amenity_title[]" class="short addTitle-field" placeholder="<?php esc_html_e( 'Amenity Title', 'easy-reservations' ); ?>">
			<input type="number" value="<?php echo esc_html( $cost ); ?>" required name="amenity_cost[]" class="short addNumber-field" placeholder="0.0" step="0.01" min="0.01">
			<select class="ersrv-amenity-charge-type" name="amenity_cost_type[]">
				<?php if("yes" === ersrv_get_plugin_settings("ersrv_enable_time_with_date")) { ?>
					<option <?php echo esc_attr( ( ! empty( $cost_type ) && 'per_hour' === $cost_type ) ? 'selected' : '' ); ?> value="per_hour"><?php esc_html_e( 'Per Hour Cost', 'easy-reservations' ); ?></option>
				<?php }else { ?>
					<option <?php echo esc_attr( ( ! empty( $cost_type ) && 'per_day' === $cost_type ) ? 'selected' : '' ); ?> value="per_day"><?php esc_html_e( 'Per Day Cost', 'easy-reservations' ); ?></option>
					<option <?php echo esc_attr( ( ! empty( $cost_type ) && 'one_time' === $cost_type ) ? 'selected' : '' ); ?> value="one_time"><?php esc_html_e( 'One Time Cost', 'easy-reservations' ); ?></option>
				<?php } ?>
				</select>
			<button type="button" class="button button-secondary btn-submit ersrv-remove-amenity-html"></button>
		</p>
		<?php
		return ob_get_clean();
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_get_calendar_widget_base_id' ) ) {
	/**
	 * Get the base ID of calendar widget.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	function ersrv_get_calendar_widget_base_id() {

		return 'easy-reservations-calendar-widget';
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_get_widget_settings' ) ) {
	/**
	 * Get the widget settings based on widget base ID.
	 *
	 * @param string $widget_base_id Holds the eidget base ID.
	 * @return array
	 * @since 1.0.0
	 */
	function ersrv_get_widget_settings( $widget_base_id ) {
		global $wp_registered_widgets;
		$widget_settings = array();

		// Check if there are registered widgets.
		if ( empty( $wp_registered_widgets ) || ! is_array( $wp_registered_widgets ) ) {
			return $widget_settings;
		}

		// Iterate through the registered widgets to get the settings of the requested widget ID.
		foreach ( $wp_registered_widgets as $base_id => $wp_widget ) {
			if ( false === stripos( $base_id, $widget_base_id ) ) {
				continue;
			}

			// Get the instance ID.
			$instance_id = ( ! empty( $wp_widget['callback'][0]->number ) ) ? (int) $wp_widget['callback'][0]->number : false;

			// Get the widget settings now.
			$settings        = get_option( "widget_{$widget_base_id}" );
			$widget_settings = ( ! empty( $settings[ $instance_id ] ) ) ? $settings[ $instance_id ] : array();

			break; // Terminate the loop, because the requested settings have been achieved.
		}

		return $widget_settings;
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_get_blockout_date_html' ) ) {
	/**
	 * Get the amenity HTML.
	 *
	 * @param string $amenity_title Holds the amenity title.
	 * @param string $amenity_cost Holds the amenity cost.
	 * @return string
	 * @since 1.0.0
	 */
	function ersrv_get_blockout_date_html( $date = '', $message = '' ) {
		ob_start();
		?>
		<p class="form-field reservation_blockout_date_field blockout-dates-row">
			<input type="text" value="<?php echo esc_html( $date ); ?>" required name="blockout_date[]" class="short addTitle-field ersrv-has-datepicker" placeholder="YYYY-MM-DD">
			<input type="text" value="<?php echo esc_html( $message ); ?>" required name="blockout_date_message[]" class="short addTitle-field">
			<button type="button" class="button button-secondary btn-submit ersrv-remove-blockout-date-html"></button>
		</p>
		<?php
		return ob_get_clean();
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_get_dates_within_2_dates' ) ) {
	/**
	 * Get dates that fall between 2 dates.
	 *
	 * @param string $from Start date.
	 * @param string $to End date.
	 * @return boolean|DatePeriod
	 * @since 1.0.0
	 */
	function ersrv_get_dates_within_2_dates( $from, $to, $exclude_from_date = false ) {
		// Return if either of the date is not provided.
		if ( empty( $from ) || empty( $to ) ) {
			return false;
		}

		// Get the dates array.
		$from     = new DateTime( $from );
		$from     = ( true === $exclude_from_date ) ? $from->modify( '+1 day' ) : $from; // Add 1 day to the from date.
		$to       = new DateTime( $to );
		$to       = $to->modify( '+1 day' );
		$interval = new DateInterval( 'P1D' );

		return new DatePeriod( $from, $interval, $to );
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_product_is_reservation' ) ) {
	/**
	 * Check if the page is reservation single page.
	 *
	 * @param int $product_id Holds the product ID.
	 * @return boolean
	 * @since 1.0.0
	 */
	function ersrv_product_is_reservation( $product_id ) {
		// Return, if its the shop or any archive page.
		if ( is_archive() || is_search() || is_shop() ) {
			return false;
		}

		// Get woocommerce product.
		$wc_product = wc_get_product( $product_id );

		// Return false, if it's not the valid WC product.
		if ( false === $wc_product ) {
			return false;
		}

		// Return false, if the product type is not reservation.
		if ( ersrv_get_custom_product_type_slug() !== $wc_product->get_type() ) {
			return false;
		}

		return true;
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_get_driving_license_allowed_file_types' ) ) {
	/**
	 * Get the allowed file types for driving license.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	function ersrv_get_driving_license_allowed_file_types() {
		$file_types = array( '.jpeg', '.jpg', '.pdf', '.png' );

		/**
		 * This hook runs on the checkout page and the order edit page.
		 *
		 * This filter helps in managing the allowed file types for the driving license.
		 *
		 * @param array $file_types File types array.
		 * @return array
		 * @since 1.0.0
		 */
		return apply_filters( 'ersrv_allowed_file_types_driving_license', $file_types );
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_get_admin_script_vars' ) ) {
	/**
	 * Return the array of script variables.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	function ersrv_get_admin_script_vars() {
		$page      = filter_input( INPUT_GET, 'page', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );
		$post_type = filter_input( INPUT_GET, 'post_type', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );
		$post      = (int) filter_input( INPUT_GET, 'post', FILTER_SANITIZE_NUMBER_INT );
		$vars      = array(
			'ajaxurl'               => admin_url( 'admin-ajax.php' ),
			'same_as_adult'         => __( 'Same as Adult!', 'easy-reservations' ),
			'export_reservations'   => __( 'Export Reservations', 'easy-reservations' ),
			'toast_success_heading' => __( 'Woohhoooo! Success..', 'easy-reservations' ),
			'toast_error_heading'   => __( 'Ooops! Error..', 'easy-reservations' ),
			'toast_notice_heading'  => __( 'Notice.', 'easy-reservations' ),
		);

		// If it's the order's listing page.
		if ( ! is_null( $post_type ) && 'shop_order' === $post_type ) {
			$vars['new_reservation_button_text'] = __( 'New Reservation', 'easy-reservations' );
			$vars['new_reservation_url']         = admin_url( 'admin.php?page=new-reservation' );
		}

		// Add some script variables on product edit page.
		if ( ! is_null( $post ) ) {
			// Product.
			if ( 'product' === get_post_type( $post ) ) {
				$blocked_dates = get_post_meta( $post, '_ersrv_reservation_blockout_dates', true );
				$blocked_dates = ( empty( $blocked_dates ) || ! is_array( $blocked_dates ) ) ? array() : $blocked_dates;
				$vars['ersrv_product_type'] = ersrv_get_custom_product_type_slug();
				$vars['blocked_dates']      = $blocked_dates;
			} elseif ( 'shop_order' === get_post_type( $post ) ) { // Shop order.
				$driving_license_allowed_extensions         = ersrv_get_driving_license_allowed_file_types();
				$vars['driving_license_allowed_extensions'] = $driving_license_allowed_extensions;
				$vars['driving_license_invalid_file_error'] = sprintf( __( 'Invalid file selected. Allowed extensions are: %1$s', 'easy-reservations' ), implode( ', ', $driving_license_allowed_extensions ) );
				$vars['driving_license_empty_file_error']   = __( 'Please provide a driving license to upload.', 'easy-reservations' );
			}
		}

		// Add the error message to the array on new reservation page.
		if ( ! is_null( $page ) && 'new-reservation' === $page ) {
			$vars['accomodation_limit_text']                      = __( 'Limit: --', 'easy-reservations' );
			$vars['start_of_week']                                = get_option( 'start_of_week' );
			$vars['woo_currency']                                 = get_woocommerce_currency_symbol();
			$vars['reservation_customer_err_msg']                 = __( 'Please select a customer for this reservation.', 'easy-reservations' );
			$vars['reservation_guests_err_msg']                   = __( 'Please provide the count of guests for the reservation.', 'easy-reservations' );
			$vars['reservation_only_kids_guests_err_msg']         = __( 'We cannot proceed with only the kids in the reservation.', 'easy-reservations' );
			$vars['reservation_guests_count_exceeded_err_msg']    = __( 'The guests count is more than the accomodation limit.', 'easy-reservations' );
			$vars['reservation_checkin_checkout_missing_err_msg'] = __( 'Please provide checkin and checkout dates.', 'easy-reservations' );
			$vars['reservation_checkin_missing_err_msg']          = __( 'Please provide checkin dates.', 'easy-reservations' );
			$vars['reservation_checkout_missing_err_msg']         = __( 'Please provide checkout dates.', 'easy-reservations' );
			$vars['reservation_lesser_reservation_days_err_msg']  = __( 'The item can be reserved for a min. of XX days.', 'easy-reservations' );
			$vars['reservation_greater_reservation_days_err_msg'] = __( 'The item can be reserved for a max. of XX days.', 'easy-reservations' );
			$vars['reservation_blocked_dates_err_msg']            = __( 'The dates you have selected for reservation contain the dates that are already reserved. Kindly check the availability on the left hand side and then proceed with the reservation.', 'easy-reservations' );
			$vars['reservation_customer_first_name_err_msg']      = __( 'First name is required.', 'easy-reservations' );
			$vars['reservation_customer_last_name_err_msg']       = __( 'Last name is required.', 'easy-reservations' );
			$vars['reservation_customer_email_err_msg']           = __( 'Email address is required.', 'easy-reservations' );
			$vars['reservation_customer_email_invalid_err_msg']   = __( 'Email address is invalid.', 'easy-reservations' );
			$vars['reservation_customer_password_err_msg']        = __( 'Password is required.', 'easy-reservations' );
			$vars['reservation_customer_phone_err_msg']           = __( 'Phone number is required.', 'easy-reservations' );
			$vars['reservation_customer_address_err_msg']         = __( 'Address line is required.', 'easy-reservations' );
			$vars['reservation_customer_country_err_msg']         = __( 'Country is required.', 'easy-reservations' );
			$vars['reservation_customer_city_err_msg']            = __( 'City is required.', 'easy-reservations' );
			$vars['reservation_customer_postcode_err_msg']        = __( 'Postcode is required.', 'easy-reservations' );
			$vars['enable_time_with_date']                        = ersrv_get_plugin_settings( 'ersrv_enable_time_with_date' );
			$vars['trim_zeros_from_price']                        = ersrv_get_plugin_settings( 'ersrv_trim_zeros_from_price' );
			$vars['datepicker_next_month_button_text']            = __( 'Next', 'easy-reservations' );
			$vars['datepicker_prev_month_button_text']            = __( 'Prev', 'easy-reservations' );
		}

		// Add the custom message to the array on cancellation requests page.
		if ( ! is_null( $page ) && 'reservation-cancellation-requests' === $page ) {
			$vars['decline_reservation_cancellation_cnf_message'] = __( 'Click OK to confirm cancellation request declination. This action won\'t be undone.', 'easy-reservations' );
			$vars['approve_reservation_cancellation_cnf_message'] = __( 'Click OK to confirm cancellation request approval. This action won\'t be undone.', 'easy-reservations' );
		}

		// Date format.
		$vars['date_format'] = ersrv_get_plugin_settings( 'ersrv_datepicker_date_format' );

		// Reservation items error messages.
		$vars['duplicate_amenities_error_message']     = __( 'There are duplicate amenities added. Please remove either of them and then update.', 'easy-reservations' );
		$vars['min_reservation_invalid_error_message'] = __( 'Minimum reservation period cannot be more than the maximum reservation period.', 'easy-reservations' );

		/**
		 * This hook fires in admin panel.
		 *
		 * This filter helps in modifying the script variables in admin.
		 *
		 * @param array $vars Script variables.
		 * @return array
		 * @since 1.0.0
		 */
		$vars = apply_filters( 'ersrv_admin_script_vars', $vars );

		return $vars;
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_create_new_user' ) ) {
	/**
	 * Create the new wp user.
	 *
	 * @param string $username Holds the username.
	 * @param string $email Holds the email.
	 * @param string $password Holds the password.
	 * @param string $first_name Holds the first name.
	 * @param string $last_name Holds the last name.
	 * @return int
	 * @since 1.0.0
	 */
	function ersrv_create_new_user( $username, $email, $password, $first_name, $last_name ) {
		$user_id  = wp_create_user( $username, $password, $email ); // Create the user.

		// Update the first name.
		if ( ! empty( $first_name ) ) {
			update_user_meta( $user_id, 'first_name', $first_name );
		}

		// Update the last name.
		if ( ! empty( $last_name ) ) {
			update_user_meta( $last_name, 'last_name', $last_name );
		}

		return $user_id;
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_should_display_receipt_button' ) ) {
	/**
	 * Check if the receipt button text is generatable for the receiving order ID.
	 *
	 * @param int $order_id Holds the order ID.
	 * @return boolean
	 * @since 1.0.0
	 */
	function ersrv_should_display_receipt_button( $order_id ) {
		// Check if order ID is valid.
		if ( empty( $order_id ) || ! is_int( $order_id ) ) {
			return false;
		}

		// Get order.
		$wc_order = wc_get_order( $order_id );

		// Return if the order is not available.
		if ( false === $wc_order ) {
			return false;
		}

		$order_statuses        = ersrv_get_plugin_settings( 'ersrv_easy_reservations_receipt_for_order_statuses' );
		$order_status          = 'wc-' . $wc_order->get_status();
		$display_order_receipt = ( ! empty( $order_statuses ) && in_array( $order_status, $order_statuses, true ) ) ? true : false;

		/**
		 * Display receipt button filter.
		 *
		 * This filter help modifying the condition under which the receipt button should be displayed or not.
		 *
		 * @param boolean $display_order_receipt Holds the boolean value to display the button.
		 * @param int     $order_id Holds the order ID.
		 * @return boolean
		 * @since 1.0.0
		 */
		$display_order_receipt = apply_filters( 'ersrv_display_receipt_button', $display_order_receipt, $order_id );

		return $display_order_receipt;
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_is_dokan_active' ) ) {
	/**
	 * Check if dokan plugin is active.
	 *
	 * @return boolean
	 * @since 1.0.0
	 */
	function ersrv_is_dokan_active() {

		return ( false === in_array( 'dokan-lite/dokan.php', get_option( 'active_plugins' ), true ) ) ? false : true;
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_download_reservation_receipt_url' ) ) {
	/**
	 * Download reservation receipt URL.
	 *
	 * @param int $order_id WooCommerce order ID.
	 * @return boolean
	 * @since 1.0.0
	 */
	function ersrv_download_reservation_receipt_url( $order_id ) {

		return home_url( "/?action=ersrv-download-reservation-receipt&atts={$order_id}" );
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_download_reservation_button_title' ) ) {
	/**
	 * Download reservation button title.
	 *
	 * @param int $order_id WooCommerce order ID.
	 * @return boolean
	 * @since 1.0.0
	 */
	function ersrv_download_reservation_receipt_button_title( $order_id ) {
		/* translators: 1: %d: order ID. */
		$button_title = sprintf( __( 'Download reservation receipt for order #%1$d', 'easy-reservations' ), $order_id );

		/**
		 * This filter fires on the download receipt button.
		 *
		 * This filter helps in modifying the download receipt button title tag.
		 *
		 * @param string $button_title Download reservation receipt button title.
		 * @param int    $order_id WooCommerce order ID.
		 * @return string
		 * @since 1.0.0
		 */
		$button_title = apply_filters( 'ersrv_download_reservation_receipt_button_title_attr', $button_title, $order_id );

		return $button_title;
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_email_reservation_receipt_order_status_change' ) ) {
	/**
	 * Email the reservation receipt on order status change.
	 *
	 * @param int $order_id WooCommerce order ID.
	 * @since 1.0.0
	 */
	function ersrv_email_reservation_receipt_order_status_change( $order_id ) {
		// Check if the order has reservation items.
		$wc_order              = wc_get_order( $order_id );
		$is_reservation_order  = ersrv_order_is_reservation( $wc_order );

		// Return, if the receipt should not be emailed.
		if ( ! $is_reservation_order ) {
			return;
		}

		// Check if the order status is allowed for receipts.
		$email_reservation_receipt = ersrv_should_display_receipt_button( $order_id );

		// Return, if the receipt should not be emailed.
		if ( false === $email_reservation_receipt ) {
			return;
		}

		// Email the order receipt.
		ersrv_email_reservation_receipt( $order_id );
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_email_reservation_receipt' ) ) {
	/**
	 * Function to send reservation receipt as email attachment.
	 *
	 * @param int $order_id Holds the order ID.
	 */
	function ersrv_email_reservation_receipt( $order_id ) {
		ersrv_download_reservation_receipt_callback( $order_id, 'email' );
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_get_store_formatted_address' ) ) {
	/**
	 * Get store formatted address.
	 *
	 * @return string
	 */
	function ersrv_get_store_formatted_address() {
		$store_name       = ersrv_get_plugin_settings( 'ersrv_reservation_receipt_store_name' );
		$wc_countries_obj = new WC_Countries();
		$address          = $wc_countries_obj->get_base_address();
		$address_2        = $wc_countries_obj->get_base_address_2();
		$city             = $wc_countries_obj->get_base_city();
		$state            = $wc_countries_obj->get_base_state();
		$country          = $wc_countries_obj->get_base_country();
		$postcode         = $wc_countries_obj->get_base_postcode();
		$store_country    = WC()->countries->countries[ $country ];
		$store_states     = $wc_countries_obj->get_states( $country );
		$store_state      = ( ! empty( $store_states ) && ! empty( $state ) ) ? $store_states[ $state ] : '';
		$store_address    = "{$store_name},<br />{$address},<br />{$address_2},<br />{$city} - {$postcode},<br />{$store_state} - {$store_country}<br />";

		/**
		 * Store address filter.
		 *
		 * This filter helps in modifying the store formatted address.
		 *
		 * @param string $store_address Holds the formatted store address.
		 * @return string
		 */
		return apply_filters( 'ersrv_store_address', $store_address );
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_get_receipt_watermarks' ) ) {
	/**
	 * Return the receipt pdf watermarks.
	 *
	 * @return array
	 */
	function ersrv_get_receipt_watermarks() {
		$watermarks = array(
			'pending'    => array(
				'text'  => __( 'PAYMENT PENDING', 'easy-reservations' ),
				'color' => '',
			),
			'processing' => array(
				'text'  => __( 'YET TO ONBOARD', 'easy-reservations' ),
				'color' => '',
			),
			'on-hold'    => array(
				'text'  => __( 'ON HOLD', 'easy-reservations' ),
				'color' => '',
			),
			'completed'  => array(
				'text'  => __( 'OFFBOARDED', 'easy-reservations' ),
				'color' => '',
			),
			'cancelled'  => array(
				'text'  => __( 'CANCELLED', 'easy-reservations' ),
				'color' => '',
			),
			'refunded'   => array(
				'text'  => __( 'REFUNDED', 'easy-reservations' ),
				'color' => '',
			),
			'failed'     => array(
				'text'  => __( 'FAILED', 'easy-reservations' ),
				'color' => '',
			),
		);

		/**
		 * Watermarks filter.
		 *
		 * This filter helps in modifying the receipt pdf watermarks.
		 *
		 * @param array $watermarks Holds the array of watermarks.
		 * @return array
		 */
		return apply_filters( 'ersrv_reservation_receipt_watermarks', $watermarks );
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_get_order_coupon_data' ) ) {
	/**
	 * Return the order coupon data.
	 *
	 * @param object $wc_order Holds the WooCommerce order object.
	 * @return array
	 */
	function ersrv_get_order_coupon_data( $wc_order ) {
		$coupon_code = '';
		$coupon_cost = 0.00;

		// Loop into the coupon items to get the coupon data.
		foreach ( $wc_order->get_items( 'coupon' ) as $item_id => $item_obj ) {
			$coupon_code = $item_obj->get_code();
			$coupon_cost = $item_obj->get_discount();
		}

		return array(
			'code'           => $coupon_code,
			'cost'           => (float) $coupon_cost,
			'formatted_cost' => wc_price( $coupon_cost ),
		);
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_get_coupon_string' ) ) {
	/**
	 * Return the coupon string.
	 *
	 * @param array $coupon_data Holds the coupon data array.
	 * @return string
	 */
	function ersrv_get_coupon_string( $coupon_data ) {
		$code = ( ! empty( $coupon_data['code'] ) ) ? $coupon_data['code'] : '';
		$cost = ( ! empty( $coupon_data['formatted_cost'] ) ) ? $coupon_data['formatted_cost'] : '';

		return "{$code} ({$cost})";
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_product_id' ) ) {
	/**
	 * Function to decide, which of the product IDs to be considered.
	 *
	 * @param int $product_id Holds the product ID.
	 * @param int $variation_id Holds the variation ID.
	 * @return int
	 */
	function ersrv_product_id( $product_id, $variation_id ) {

		return ( 0 !== $variation_id ) ? $variation_id : $product_id;
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_download_reservation_receipt_callback' ) ) {
	/**
	 * Function hooked to generate the Receipt PDF from order id.
	 *
	 * @param int    $order_id Holds the order ID.
	 * @param string $action Holds the receipt action.
	 */
	function ersrv_download_reservation_receipt_callback( $order_id, $action = '' ) {
		// Include the main TCF classes.
		include_once ERSRV_PLUGIN_PATH . 'includes/lib/tcpdf/tcpdf.php';
		include_once ERSRV_PLUGIN_PATH . 'includes/lib/class-easy-reservations-tcpdf-receipt.php';

		// PDF title.
		/* translators: 1: %s: site title, 2: %d: order ID */
		$pdf_title = sprintf( __( '%1$s - Order Receipt #%2$d', 'easy-reservations' ), get_bloginfo( 'title' ), $order_id );

		// Start PDF generation.
		$pdf = new Easy_Reservations_TCPDF_Receipt( PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false );
		$pdf->SetCreator( PDF_CREATOR );
		$pdf->SetAuthor( 'Nicola Asuni' );
		$pdf->SetTitle( $pdf_title );
		$pdf->SetSubject( 'Order Receipt' );
		$pdf->SetKeywords( 'TCPDF, PDF, example, test, guide' );
		$pdf->setHeaderFont( array( PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN ) );
		$pdf->setFooterFont( array( PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA ) );
		$pdf->SetDefaultMonospacedFont( PDF_FONT_MONOSPACED );
		$pdf->SetMargins( 6, 37, 6 );
		$pdf->SetHeaderMargin( 6 );
		$pdf->SetFooterMargin( PDF_MARGIN_FOOTER );
		$pdf->SetAutoPageBreak( true, 23 );
		$pdf->setImageScale( PDF_IMAGE_SCALE_RATIO );
		if ( file_exists( dirname( __FILE__ ) . '/lang/eng.php' ) ) {
			require_once dirname( __FILE__ ) . '/lang/eng.php';
			$pdf->setLanguageArray( $l );
		}
		$pdf->setFontSubsetting( true );
		$pdf->SetFont( 'robotocondensed', '', 12, '', true );
		$pdf->AddPage();

		// Order details.
		$wc_order             = wc_get_order( $order_id );
		$billing_address      = $wc_order->get_formatted_billing_address();
		$shipping_address     = $wc_order->get_formatted_shipping_address();
		$raw_billing_address  = $wc_order->get_address( 'billing' );
		$raw_shipping_address = $wc_order->get_address( 'shipping' );
		$order_status         = $wc_order->get_status();
		$line_items           = $wc_order->get_items();
		$store_thanks_note    = ersrv_get_plugin_settings( 'ersrv_easy_reservations_reservation_thanks_note' );

		// Store info.
		$store_address          = ersrv_get_store_formatted_address();
		$date_created           = $wc_order->get_date_created();
		$date_created_formatted = gmdate( 'F j, Y, g:i A', strtotime( $date_created ) );

		// Payment details.
		$payment_method       = $wc_order->get_payment_method();
		$payment_method_title = $wc_order->get_payment_method_title();

		// Watermark.
		$watermarks = ersrv_get_receipt_watermarks();
		$watermark  = $watermarks[ $order_status ]['text'];

		// Coupons usage.
		$used_coupons = $wc_order->get_items( 'coupon' );
		$coupon_data  = ( ! empty( $used_coupons ) ) ? ersrv_get_order_coupon_data( $wc_order ) : array();
		$coupon_str   = ( ! empty( $coupon_data ) ) ? ersrv_get_coupon_string( $coupon_data ) : '';

		// Shipment tracking details.
		$date_shipped_formatted    = __( 'Yet to ship!', 'easy-reservations' );
		$tracking_number           = '--';
		$tracking_id               = '--';
		$shipment_tracking_details = get_post_meta( $order_id, '_wc_shipment_tracking_items', true );

		if ( ! empty( $shipment_tracking_details[0] ) ) {
			$shipment_tracking_details = $shipment_tracking_details[0];
			$tracking_number           = $shipment_tracking_details['tracking_number'];
			$tracking_id               = $shipment_tracking_details['tracking_id'];
			$date_shipped              = $shipment_tracking_details['date_shipped'];
			$date_shipped_formatted    = gmdate( 'F j, Y', $date_shipped );
		}

		$order_totals = 0.00;
		ob_start();
		?>
		<table cellspacing="0" cellpadding="0" width="100%" border="0">
			<tr width="100%">
				<td colspan="2">
					<table cellspacing="0" cellpadding="0" width="100%" border="0">
						<tr width="100%">
							<td style="width:35%">
								<table cellspacing="0" cellpadding="0" width="100%" border="0">
									<tr width="100%">
										<td style="line-height:28px;font-size:14px;">
											<b><?php esc_html_e( 'BILL ADDRESS:', 'easy-reservations' ); ?></b>
										</td>
									</tr>
									<tr width="100%">
										<td style="line-height:14px;font-size:12px;"><?php echo wp_kses_post( $billing_address ); ?></td>
									</tr>
								</table>
							</td>
							<td style="width:35%">
								<table cellspacing="0" cellpadding="0" width="100%" border="0">
									<tr width="100%">
										<td style="line-height:28px;font-size:14px;vertical-align:middle;" colspan="2">
											<b><?php esc_html_e( 'ORDER:', 'easy-reservations' ); ?></b>
										</td>
									</tr>
									<tr width="100%">
										<td style="line-height:14px;font-size:12px;"><?php echo esc_html( "#{$order_id}" ); ?></td>
									</tr>
									<tr><td style="height:3px"></td></tr>
									<tr width="100%">
										<td style="line-height:28px;font-size:14px;vertical-align:middle;" colspan="2">
											<b><?php esc_html_e( 'DATE:', 'easy-reservations' ); ?></b>
										</td>
									</tr>
									<tr width="100%">
										<td style="line-height:14px;font-size:12px;"><?php echo esc_html( $date_created_formatted ); ?></td>
									</tr>
									<tr><td style="height:3px"></td></tr>
								</table>
								<?php if ( ! empty( $store_thanks_note ) ) { ?>
									<table cellspacing="0" cellpadding="0" width="95%" border="0">
										<tr width="100%">
											<td style="line-height:16px;font-size:12px;" colspan="2"><?php echo esc_html( $store_thanks_note ); ?></td>
										</tr>
										<tr><td style="height:20px"></td></tr>
									</table>
								<?php } ?>
							</td>
							<td style="width:30%;">
								<table cellspacing="0" cellpadding="0" width="100%" border="0">
									<?php
									if ( 'bacs' === $payment_method ) {
										$bacs = get_option( 'woocommerce_bacs_settings' );
										if ( ! empty( $bacs['enabled'] ) && 'yes' === $bacs['enabled'] ) {
											$accounts = get_option( 'woocommerce_bacs_accounts' );
											if ( ! empty( $accounts[0] ) ) {
												$account = $accounts[0];
												?>
												<tr>
													<td style="line-height:16px;font-size:12px;">
														<b style="text-transform:uppercase;"><?php esc_html_e( 'Store address', 'easy-reservations' ); ?></b>
														<br/><?php echo wp_kses_post( $store_address ); ?>
													</td>
												</tr>
												<tr><td style="height:5px"></td>
												</tr>

												<tr>
													<td style="line-height:16px;font-size:12px;">
														<b style="text-transform:uppercase;line-height:28px;font-size:14px;">
															<?php echo esc_html( $payment_method_title ); ?></b>
														<br/>
														<span>
															<?php
															/* translators 1: %s: br tag, 2: %s: bank name, 3: %s: account name, 4: %s: sort code, 5: %s: account number, 6: %s: bic */
															echo wp_kses_post( sprintf( __( 'Bank Name: %2$s%1$sAccount Name: %3$s%1$sRouting Name: %4$s%1$sAccount: %5$s%1$sBIC: %6$s%1$s', 'easy-reservations' ), '<br />', esc_html( $account['bank_name'] ), esc_html( $account['account_name'] ), esc_html( $account['sort_code'] ), esc_html( $account['account_number'] ), esc_html( $account['bic'] ) ) );
															?>
														</span>
													</td>
												</tr>
												<?php
											}
										}
									} elseif ( 'cheque' === $payment_method ) {
										?>
										<tr>
											<td style="line-height:16px;font-size:12px;">
												<b style="text-transform:uppercase;"><?php esc_html_e( 'Pay by cheque:', 'easy-reservations' ); ?></b>
												<br/>
												<?php
												/* translators: 1: %s: br tag, 2: %s: store address */
												echo wp_kses_post( sprintf( __( 'Mail your cheque to:%1$s%2$s', 'easy-reservations' ), '<br />', $store_address ) );
												?>
											</td>
										</tr>
										<tr><td style="height:5px"></td></tr>
										<?php
									} else {
										?>
										<tr>
											<td style="line-height:16px;font-size:12px;">
												<b style="text-transform:uppercase;"><?php esc_html_e( 'Store address', 'easy-reservations' ); ?></b>
												<br/><?php echo wp_kses_post( $store_address ); ?>
											</td>
										</tr>
										<tr><td style="height:5px"></td></tr>
										<tr><td style="line-height:16px;font-size:12px;"><b style="text-transform:uppercase;"><?php esc_html_e( 'Payment mode:', 'wc-print-invoice-receipt' ); ?></b><br/><?php echo esc_html( $payment_method_title ); ?></td></tr>
										<tr><td style="height:5px"></td></tr>
									<?php } ?>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr width="100%">
				<td colspan="2">
					<table cellspacing="0px" cellpadding="2px" width="100%" border="0">
						<tr style="background-color:#ccc">
							<td style="line-height:24px;font-size:12px;padding:5px" width="10%"></td>
							<td style="line-height:24px;font-size:12px;padding:5px"
								width="25%"><?php esc_html_e( 'ITEM', 'easy-reservations' ); ?></td>
							<td style="line-height:24px;font-size:12px;padding:5px"
								width="35%"><?php esc_html_e( 'DESCRIPTION', 'easy-reservations' ); ?></td>
							<td style="line-height:24px;font-size:12px;padding:5px"
								width="5%"><?php esc_html_e( 'QTY', 'easy-reservations' ); ?></td>
							<td style="line-height:24px;font-size:12px;text-align:right;padding:5px"
								width="10%"><?php esc_html_e( 'COST', 'easy-reservations' ); ?></td>
							<td style="line-height:24px;font-size:12px;text-align:right;padding:5px"
								width="15%"><?php esc_html_e( 'TOTAL', 'easy-reservations' ); ?></td>
						</tr>
						<?php
						if ( ! empty( $line_items ) && is_array( $line_items ) ) {
							foreach ( $line_items as $item ) {
								$item_id           = $item->get_id();
								$quantity          = $item->get_quantity();
								$prod_id           = ersrv_product_id( $item->get_product_id(), $item->get_variation_id() );
								$wc_product        = $item->get_product();
								$sku               = $wc_product->get_sku();
								$item_subtotal     = (float) $item->get_subtotal();
								$item_total        = (float) $item->get_total();
								$item_cost         = $item_total / $quantity;
								$order_totals     += $item_total;
								$product_image_id  = get_post_thumbnail_id( $prod_id );
								$product_image_url = ersrv_get_attachment_url_from_attachment_id( $product_image_id );

								// Prepare the item total cost with the cost difference, in case coupon is applied.
								$item_discount = $item_subtotal - $item_total;

								// Add the discount to the item total.
								if ( 0 < $item_discount ) {
									$item_discount_formatted = ( 0 !== $item_discount ) ? wc_price( $item_discount ) : '';
									/* translators: 1: %s: br tag, 2: %s: discount amount */
									$item_total = wc_price( $item_total ) . sprintf( __( '%1$s%2$s discount', 'easy-reservations' ), '<br />', $item_discount_formatted );
								}

								// Get the item meta details.
								$checkin_date       = wc_get_order_item_meta( $item_id, 'Checkin Date', true );
								$checkout_date      = wc_get_order_item_meta( $item_id, 'Checkout Date', true );
								$adult_count        = wc_get_order_item_meta( $item_id, 'Adult Count', true );
								$adult_subtotal     = wc_get_order_item_meta( $item_id, 'Adult Subtotal', true );
								$kids_count         = wc_get_order_item_meta( $item_id, 'Kids Count', true );
								$kids_subtotal      = wc_get_order_item_meta( $item_id, 'Kids Subtotal', true );
								$security_amount    = wc_get_order_item_meta( $item_id, 'Security Amount', true );
								$amenities_subtotal = wc_get_order_item_meta( $item_id, 'Amenities Subtotal', true );
								?>
								<tr valign="middle">
									<td style="padding:5px" width="10%">
										<img src="<?php echo esc_url( $product_image_url ); ?>" height="50px" width="50px"/>
									</td>
									<td style="line-height:16px;font-size:12px;padding:15px" width="25%"><?php echo esc_html( $sku ); ?></td>
									<td style="line-height:16px;font-size:12px;padding:15px" width="35%">
										<p style="line-height:10px;"><?php echo esc_html( $wc_product->get_title() ); ?></p>
										<p style="line-height:2px;"><?php echo wp_kses_post( sprintf( __( 'Checkin Date: %1$s', 'easy-reservations' ), $checkin_date ) ); ?></p>
										<p style="line-height:2px;"><?php echo wp_kses_post( sprintf( __( 'Checkout Date: %1$s', 'easy-reservations' ), $checkout_date ) ); ?></p>
										<p style="line-height:2px;"><?php echo wp_kses_post( sprintf( __( 'Adult Count: %1$s', 'easy-reservations' ), $adult_count ) ); ?></p>
										<p style="line-height:2px;"><?php echo wp_kses_post( sprintf( __( 'Adult Subtotal: %1$s', 'easy-reservations' ), wc_price( $adult_subtotal ) ) ); ?></p>
										<p style="line-height:2px;"><?php echo wp_kses_post( sprintf( __( 'Kids Count: %1$s', 'easy-reservations' ), $kids_count ) ); ?></p>
										<p style="line-height:2px;"><?php echo wp_kses_post( sprintf( __( 'Kids Subtotal: %1$s', 'easy-reservations' ), wc_price( $kids_subtotal ) ) ); ?></p>
										<p style="line-height:2px;"><?php echo wp_kses_post( sprintf( __( 'Security Amount: %1$s', 'easy-reservations' ), wc_price( $security_amount ) ) ); ?></p>
										<p style="line-height:2px;"><?php echo wp_kses_post( sprintf( __( 'Amenities Subtotal: %1$s', 'easy-reservations' ), wc_price( $amenities_subtotal ) ) ); ?></p>
									</td>
									<td style="line-height:16px;font-size:12px;padding:15px" width="5%"><?php echo esc_html( $quantity ); ?></td>
									<td style="line-height:16px;font-size:12px;text-align:right;padding:15px" width="10%"><?php echo wp_kses_post( wc_price( $item_cost ) ); ?></td>
									<td style="line-height:16px;font-size:12px;text-align:right;padding:15px" width="15%"><?php echo wp_kses_post( wc_price( $item_total ) ); ?></td>
								</tr>
								<?php
							}
							$subtotal = wc_price( $order_totals );
							?>
							<tr>
								<td style="line-height:16px;font-size:12px;" width="20%"></td>
								<td style="line-height:16px;font-size:12px;" width="50%"></td>
								<td style="line-height:16px;font-size:12px;" width="5%"></td>
								<td style="line-height:16px;font-size:12px;text-align:right" width="10%"></td>
								<td style="line-height:16px;font-size:12px;text-align:right" width="15%"></td>
							</tr>
							<tr>
								<td style="line-height:16px;font-size:12px;" width="20%"></td>
								<td style="line-height:16px;font-size:12px;" width="50%"></td>
								<td style="line-height:16px;font-size:12px;" width="5%"></td>
								<td style="line-height:16px;font-size:12px;text-align:right" width="10%"><?php esc_html_e( 'SUBTOTAL:', 'easy-reservations' ); ?></td>
								<td style="line-height:16px;font-size:12px;text-align:right" width="15%"><?php echo wp_kses_post( $subtotal ); ?></td>
							</tr>
							<?php
							if ( ! empty( $coupon_str ) ) {
								?>
								<tr>
									<td style="line-height:20px;font-size:12px;" width="20%"></td>
									<td style="line-height:20px;font-size:12px;"
										width="50%"></td>
									<td style="line-height:20px;font-size:12px;" width="5%"></td>
									<td style="line-height:20px;font-size:12px;text-align:right"
										width="10%"><?php esc_html_e( 'COUPON:', 'easy-reservations' ); ?></td>
									<td style="line-height:20px;font-size:12px;text-align:right"
										width="15%"><?php echo wp_kses_post( $coupon_str ); ?></td>
								</tr>
								<?php
							}
							?>

							<!-- TAXES -->
							<?php
							$taxes = $wc_order->get_tax_totals();
							if ( ! empty( $taxes ) ) {
								$tax_label         = '';
								$tax_amt_formatted = '';
								$tax_amount        = 0.00;
								foreach ( $taxes as $tax ) {
									$tax_label         = $tax->label;
									$tax_amt_formatted = $tax->formatted_amount;
									$tax_amount        = (float) $tax->amount;
									break;
								}
								$order_totals += $tax_amount;
								$tax_label     = empty( $tax_label ) ? __( 'TAX', 'easy-reservations' ) : $tax_label;

								?>
								<tr>
									<td style="line-height:16px;font-size:12px;" width="20%"></td>
									<td style="line-height:16px;font-size:12px;" width="50%"></td>
									<td style="line-height:16px;font-size:12px;" width="5%"></td>
									<td style="line-height:16px;font-size:12px;text-align:right" width="10%"><?php echo esc_html( "{$tax_label}:" ); ?></td>
									<td style="line-height:16px;font-size:12px;text-align:right" width="15%"><?php echo wp_kses_post( $tax_amt_formatted ); ?></td>
								</tr>
								<?php
							}
							?>
							<?php
						} else {
							?>
							<tr>
								<td colspan="5" style="line-height:16px;font-size:12px;"
									width="20%"><?php esc_html_e( 'No items found !!', 'easy-reservations' ); ?></td>
							</tr>
							<?php
						}
						?>
					</table>
				</td>
			</tr>
			<tr width="100%">
				<td colspan="2" style="border-bottom:1px dashed #919191;" height="0px">
				</td>
			</tr>
			<tr width="100%">
				<td colspan="2">
					<table cellspacing="0" cellpadding="0" width="100%" border="0">
						<tr width="100%">
							<td style="width:70%"></td>
							<td style="width:10%; line-height:28px;font-size:14px;text-align:right;"><?php esc_html_e( 'TOTAL', 'easy-reservations' ); ?></td>
							<td style="width:20%; line-height:28px;font-size:14px;text-align:right;font-weight:bold"><?php echo wp_kses_post( wc_price( $order_totals ) ); ?></td>
						</tr>
					</table>
				</td>
			</tr>

			<!-- REFUNDS -->
			<?php
			$order_refunds = $wc_order->get_refunds();
			if ( 'refunded' === $order_status || ! empty( $order_refunds ) ) {
				?>
				<tr width="100%">
					<td colspan="2">
						<table width="100%" cellpadding="2px">
							<tr width="100%">
								<td colspan="3">
									<h5 style="text-transform:uppercase;"><?php esc_html_e( 'Refunds', 'easy-reservations' ); ?></h5>
								</td>
							</tr>
							<tr width="100%" style="background-color:#ccc;">
								<td style="width:10%;font-size:12px;line-height:24px;padding:5px"><?php esc_html_e( 'SR.NO.', 'easy-reservations' ); ?></td>
								<td style="width:80%;font-size:12px;line-height:24px;padding:5px"><?php esc_html_e( 'REASON', 'easy-reservations' ); ?></td>
								<td style="width:10%;font-size:12px;line-height:24px;text-align:right;padding:5px"><?php esc_html_e( 'AMOUNT', 'easy-reservations' ); ?></td>
							</tr>
							<?php
							foreach ( $order_refunds as $key => $order_refund ) {
								$refund_amount = (float) $order_refund->amount;
								$order_totals -= $refund_amount;
								$refund_reason = $order_refund->refund_reason;
								if ( ! empty( $refund_amount ) ) {
									$index = $key + 1;
									?>
									<tr width="100%">
										<td style="width:10%;font-size:12px;line-height:28px;"><?php echo esc_html( "{$index}." ); ?></td>
										<td style="width:80%;font-size:12px;line-height:28px;"><?php echo esc_html( ( empty( $refund_reason ) ) ? __( 'N/A', 'easy-reservations' ) : $refund_reason ); ?></td>
										<td style="width:10%;color:red;font-size:12px;line-height:28px;text-align:right;"><?php echo wp_kses_post( '- ' . wc_price( $refund_amount ) ); ?></td>
									</tr>
									<?php
								}
							}
							?>
						</table>
					</td>
				</tr>
				<tr width="100%">
					<td colspan="2" style="border-bottom:1px dashed #919191;" height="0px"></td>
				</tr>
				<tr width="100%">
					<td colspan="2">
						<table cellspacing="0" cellpadding="0" width="100%" border="0">
							<tr width="100%">
								<td style="width:70%"></td>
								<td style="width:10%; line-height:28px;font-size:14px;text-align:right;"><?php esc_html_e( 'TOTAL', 'easy-reservations' ); ?></td>
								<td style="width:20%; line-height:28px;font-size:16px;text-align:right;font-weight:bold"><?php echo wp_kses_post( wc_price( $order_totals ) ); ?></td>
							</tr>
						</table>
					</td>
				</tr>

			<?php } ?>

			<!-- CUSTOMER NOTES -->
			<?php
			$customer_note = $wc_order->get_customer_note();
			if ( ! empty( $customer_note ) ) {
				?>
				<tr width="100%">
					<td colspan="2">
						<table cellspacing="0" cellpadding="0" width="100%" border="0">
							<tr width="100%">
								<td width="100%" style="line-height:16px;font-size:12px;">
									<span style="text-transform:uppercase;"><?php esc_html_e( 'Customer Note:', 'easy-reservations' ); ?></span><br/>
									<span><?php echo esc_html( $customer_note ); ?></span>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<?php
			}
			?>
		</table>
		<?php
		$pdf->StartTransform();
		$pdf->Rotate( 35 );
		$pdf->SetXY( 98, 177 );
		$paid_invoice_watermark = '<p stroke="0.4" fill="true" strokecolor="#b0f5b0" color="#fff" style="font-family:helvetica;font-weight:bold;font-size:36pt;z-index:9999">' . $watermark . '</p>';
		$pdf->writeHTML( $paid_invoice_watermark, true, false, false, false, '' );
		$pdf->StopTransform();
		$html = ob_get_clean();

		$search = array(
			'/\>[^\S ]+/s',     // strip whitespaces after tags, except space.
			'/[^\S ]+\</s',     // strip whitespaces before tags, except space.
			'/(\s)+/s',         // shorten multiple whitespace sequences.
			'/<!--(.|\s)*?-->/', // Remove HTML comments.
		);

		$replace = array(
			'>',
			'<',
			'\\1',
			'',
		);

		$html = preg_replace( $search, $replace, $html );
		$pdf->SetXY( 3, 37 );
		$pdf->writeHTMLCell( 0, 0, '', '', $html, 0, 1, 0, true, '', true );
		$timestamp      = time();
		$pdf_file_title = "order-receipt-{$order_id}-{$timestamp}.pdf";

		// Check the action requested.
		if ( '' !== $action ) {
			$customer_email = $wc_order->get_billing_email();
			$wp_upload_dir  = wp_upload_dir();
			$attach_path    = $wp_upload_dir['basedir'] . '/wc-logs/' . $pdf_file_title;
			$pdf->Output( $attach_path, 'F' );
			$admin_email = get_option( 'admin_email' );
			$site_title  = get_option( 'blogname' );
			$subject     = "Receipt Email - Order #{$order_id}";
			$headers     = 'From:' . $site_title . '<' . $admin_email . "> \r\n";
			$headers    .= 'Reply-To:' . $admin_email . "\r\n";
			$headers    .= "X-Priority: 1\r\n";
			$headers    .= 'MIME-Version: 1.0' . "\n";
			$headers    .= 'Content-type: text/html; charset=utf-8' . "\r\n";
			$attachments = array( $attach_path );
			$body        = 'Hello, please find the attached receipt.';
			wp_mail( $customer_email, $subject, $body, $headers, $attachments );
		} else {
			$pdf->Output( $pdf_file_title, 'D' ); // Download PDF. Use "I" for viewing the PDF.
		}
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_get_reservation_item_block_html' ) ) {
	/**
	 * Get the block HTML for reservation item.
	 *
	 * @param int $item_id Reservation item ID.
	 */
	function ersrv_get_reservation_item_block_html( $item_id, $page ) {
		$user_id                 = get_current_user_id();
		$featured_image_id       = get_post_thumbnail_id( $item_id );
		$item_featured_image     = ersrv_get_attachment_url_from_attachment_id( $featured_image_id );
		$item_featured_image     = ( empty( $item_featured_image ) ) ? wc_placeholder_img_src() : $item_featured_image;
		$item_link               = get_permalink( $item_id );
		$item_details            = ersrv_get_item_details( $item_id );
		$adult_charge            = ( ! empty( $item_details['adult_charge'] ) ) ? $item_details['adult_charge'] : 0;
		$location                = ( ! empty( $item_details['location'] ) ) ? $item_details['location'] : '';
		$capacity                = ( ! empty( $item_details['accomodation_limit'] ) ) ? $item_details['accomodation_limit'] : '';
		$security_amt            = ( ! empty( $item_details['security_amount'] ) ) ? $item_details['security_amount'] : '';
		$min_reservation         = ( ! empty( $item_details['min_reservation_period'] ) ) ? $item_details['min_reservation_period'] : '';
		$max_reservation         = ( ! empty( $item_details['max_reservation_period'] ) ) ? $item_details['max_reservation_period'] : '';
		$is_favourite            = ersrv_is_favourite_item( $user_id, $item_id );
		$item_class              = ( $is_favourite ) ? 'selected' : '';
		$reservation_period      = '';
		$item_title              = get_the_title( $item_id );
		$item_title              = ( 46 <= strlen( $item_title ) ) ? substr( $item_title, 0, 45 ) . '...' : $item_title;
		$item_type_str_with_link = ( ! empty( $item_details['item_type_str_with_link'] ) ) ? $item_details['item_type_str_with_link'] : '';
		$reservation_period_str  = ( ! empty( $item_details['reservation_period_str'] ) ) ? $item_details['reservation_period_str'] : '';

		// Prepare the block html now.
		ob_start();
		?>
		<div class="col-12 col-md-6 col-lg-4 ersrv-reservation-item-block" data-item="<?php echo esc_attr( $item_id ); ?>">
			<div class="card">
				<div class="media">
					<a href="<?php echo esc_url( $item_link ); ?>">
						<img src="<?php echo esc_url( $item_featured_image ); ?>" alt="img" class="card-img" />
					</a>
				</div>
				<?php if ( is_user_logged_in() ) { ?>
					<div class="favorite">
						<a href="javascript:void(0);" class="favorite-link ersrv-mark-reservation-favourite <?php echo esc_attr( $item_class ); ?>">
							<span class="sr-only"><?php esc_html_e( 'Favorite', 'easy-reservations' ); ?></span>
							<span class="icon-heart">&nbsp;</span>
						</a>
					</div>
				<?php } ?>
				<div class="price-info">
					<div class="inner-wrapper color-black font-size-12 font-weight-semibold">
						<span class="color-accent font-size-18 font-Poppins">
							<?php echo wp_kses(
								wc_price( $adult_charge ),
								array(
									'span' => array(
										'class' => array(),
									),
								)
							); ?>
						</span><?php esc_html_e( ' - per day', 'easy-reservations' ); ?>
					</div>
				</div>
				<div class="card-body">
					<h3 class="card-title">
						<a href="<?php echo esc_url( $item_link ); ?>"><?php echo wp_kses_post( $item_title ); ?></a>
					</h3>

					<?php
					/**
					 * This hook runs anywhere the reservation block html is displayed.
					 *
					 * This hook helps adding any html after the title is displayed.
					 *
					 * @param int    $item_id Reservation item ID.
					 * @param string $page Page.
					 * @since 1.0.0
					 */
					do_action( 'ersrv_single_reservation_block_after_title', $item_id, $page );
					?>

					<div class="amenities mb-3">
						<div class="d-flex flex-column">
							<!-- LOCATION -->
							<?php if ( $location ) {?>
								<div class="location">
									<span class="icon"><i class="fas fa-map-marker"></i></span>
									<span><?php echo esc_html( $location ); ?></span>
								</div>
							<?php } ?>

							<!-- RESERVATION PERIOD -->
							<div class="map-loaction">
								<span class="icon"><i class="fas fa-calendar-alt"></i></span>
								<span class=""><?php echo esc_html( $reservation_period_str ); ?></span>
							</div>

							<!-- CAPACITY -->
							<?php if ( $capacity ) { ?>
								<div class="capacity">
									<span class="icon"><i class="fas fa-users"></i></span>
									<span class="font-weight-bold"><?php esc_html_e( 'Capacity:', 'easy-reservations' ); ?></span>
									<span class=""><?php echo esc_html( $capacity ); ?></span>
								</div>
							<?php } ?>

							<!-- SECURITY AMOUNT -->
							<div class="cabins">
								<span class="icon"><i class="fas fa-money-bill-alt"></i></span>
								<span class="font-weight-bold"><?php esc_html_e( 'Security Amt:', 'easy-reservations' ); ?></span>
								<span class="">
									<?php echo wp_kses(
										wc_price( $security_amt ),
										array(
											'span' => array(
												'class' => array(),
											),
										)
									); ?>
								</span>
							</div>

							<!-- TYPES -->
							<?php if ( $item_type_str_with_link ) { ?>
								<div class="ersrv-item-type">
									<span class="icon"><i class="fas fa-th-large"></i></span>
									<span class="font-weight-bold"><?php esc_html_e( 'Type:', 'easy-reservations' ); ?></span>
									<span class=""><?php echo wp_kses_post( $item_type_str_with_link ); ?></span>
								</div>
							<?php } ?>
						</div>
					</div>
					<div class="btns-group">
						<?php if ( 'search-reservations-page' === $page ) {
								$get_book_btn_text = ersrv_get_plugin_settings('ersrv_archive_page_add_to_cart_button_text');
								if(!empty($get_book_btn_text)){
									$book_button_text = $get_book_btn_text;
								}else{
									$book_button_text = 'Book Now';
								}
							?>
							<a href="<?php echo esc_url( $item_link ); ?>" class="btn btn-accent mr-2"><?php esc_html_e( $book_button_text, 'easy-reservations' ); ?></a>
							<a href="javascript:void(0);" class="btn btn-primary ersrv-quick-view-item"><?php esc_html_e( 'Quick View', 'easy-reservations' ); ?></a>
						<?php } else  { ?>
							<a href="<?php echo esc_url( $item_link ); ?>" class="btn btn-accent mr-2"><?php esc_html_e( 'View Details', 'easy-reservations' ); ?></a>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
		<?php

		return ob_get_clean();
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_is_favourite_item' ) ) {
	/**
	 * Returns the image URL by attachment ID.
	 *
	 * @param int $image_id Holds the attachment ID.
	 * @return string
	 */
	function ersrv_is_favourite_item( $user_id, $item_id ) {
		$favourite_items = get_user_meta( $user_id, 'ersrv_favourite_items', true );

		return ( ! empty( $favourite_items ) && in_array( $item_id, $favourite_items, true ) ) ? true : false;
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_get_attachment_url_from_attachment_id' ) ) {
	/**
	 * Returns the image URL by attachment ID.
	 *
	 * @param int $image_id Holds the attachment ID.
	 * @return string
	 */
	function ersrv_get_attachment_url_from_attachment_id( $image_id ) {

		return ( empty( $image_id ) ) ? '' : wp_get_attachment_url( $image_id );
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_get_item_details' ) ) {
	/**
	 * Return all the details about the reservable item.
	 *
	 * @param int $item_id Holds the item ID.
	 * @return array
	 */
	function ersrv_get_item_details( $item_id ) {
		// Accomodation limit.
		$accomodation_limit = get_post_meta( $item_id, '_ersrv_accomodation_limit', true );

		// Reserved dates.
		$reserved_dates = get_post_meta( $item_id, '_ersrv_reservation_blockout_dates', true );

		// Amenities.
		$amenities = get_post_meta( $item_id, '_ersrv_reservation_amenities', true );
		// Prepare the amenities HTML.
		ob_start();
		if ( ! empty( $amenities ) && is_array( $amenities ) ) {
			foreach ( $amenities as $index => $amenity ) {
				$title          = ( ! empty( $amenity['title'] ) ) ? $amenity['title'] : '';
				$cost           = ( ! empty( $amenity['cost'] ) ) ? $amenity['cost'] : '';
				$cost_type      = ( ! empty( $amenity['cost_type'] ) ) ? $amenity['cost_type'] : '';
				$formatted_cost = wc_price( $cost );

				// Skip the HTML is either the title or the cost is missing.
				if ( empty( $title ) || empty( $cost ) ) {
					continue;
				}
				?>
				<div data-amenity="<?php echo esc_attr( $title ); ?>" data-cost_type="<?php echo esc_attr( $cost_type ); ?>" data-cost="<?php echo esc_attr( $cost ); ?>" class="ersrv-new-reservation-single-amenity <?php echo esc_attr( ( 2 < $index ) ? 'mtop' : '' ); ?>">
					<label class="ersrv-switch">
						<input type="checkbox" class="ersrv-switch-input">
						<span class="slider ersrv-switch-slider"></span>
					</label>
					<span><?php echo wp_kses_post( "{$title} [{$formatted_cost}]" ); ?></span>
				</div>
				<?php
			}
		}
		$amenity_html = ob_get_clean();

		// Unavailable weekdays.
		$unavailable_weekdays = get_post_meta( $item_id, '_ersrv_item_unavailable_weekdays', true );
		$unavailable_weekdays = ( ! empty( $unavailable_weekdays ) && is_array( $unavailable_weekdays ) ) ? $unavailable_weekdays : array();

		// Reservation period restrictions.
		$min_reservation = get_post_meta( $item_id, '_ersrv_reservation_min_period', true );
		$max_reservation = get_post_meta( $item_id, '_ersrv_reservation_max_period', true );

		// Generate the booking period restrictions.
		$reservation_period_str = '';
		if ( ! empty( $min_reservation ) && ! empty( $max_reservation ) ) {
			// If min and max reservation period days are same.
			if ( $min_reservation === $max_reservation ) {
				$reservation_period_str = sprintf( _n( 'Booking for min. %1$s day.', 'Booking for min. %1$s days.', $min_reservation, 'easy-reservations' ), $min_reservation );
			} else {
				$reservation_period_str = sprintf( __( 'Booking for min. %1$s to %2$s days.', 'easy-reservations' ), $min_reservation, $max_reservation );
			}
		} elseif ( ! empty( $min_reservation ) ) {
			$reservation_period_str = sprintf( _n( 'Booking for min. %1$s day.', 'Booking for min. %1$s days.', $min_reservation, 'easy-reservations' ), $min_reservation );
		}

		// Item types.
		$item_types              = wp_get_object_terms( $item_id, 'reservation-item-type' );
		$item_type_str           = '';
		$item_type_str_with_link = '';

		if ( ! empty( $item_types ) && is_array( $item_types ) ) {
			foreach ( $item_types as $type_obj ) {
				$item_type_links[] = '<a href="' . get_category_link( $type_obj->term_id ) . '">' . $type_obj->name . '</a>';
				$item_type_texts[] = $type_obj->name;
			}

			$item_type_str_with_link = implode( ', ', $item_type_links );
			$item_type_str           = implode( ', ', $item_type_texts );
		}

		// Put the details in an array.
		$item_details = array(
			'accomodation_limit'      => $accomodation_limit,
			'reserved_dates'          => $reserved_dates,
			'min_reservation_period'  => $min_reservation,
			'max_reservation_period'  => $max_reservation,
			'reservation_period_str'  => $reservation_period_str,
			'amenities'               => $amenities,
			'amenity_html'            => $amenity_html,
			'adult_charge'            => get_post_meta( $item_id, '_ersrv_accomodation_adult_charge', true ),
			'kid_charge'              => get_post_meta( $item_id, '_ersrv_accomodation_kid_charge', true ),
			'security_amount'         => get_post_meta( $item_id, '_ersrv_security_amt', true ),
			'location'                => get_post_meta( $item_id, '_ersrv_item_location', true ),
			'currency'                => get_woocommerce_currency_symbol(),
			'has_captain'             => get_post_meta( $item_id, '_ersrv_has_captain', true ),
			'has_captain_text'        => get_post_meta( $item_id, '_ersrv_has_captain_text', true ),
			'captain_id'              => get_post_meta( $item_id, '_ersrv_item_captain', true ),
			'total_reservations'      => get_post_meta( $item_id, 'total_sales', true ),
			'total_reservations_icon' => ERSRV_PLUGIN_URL . 'public/images/3d-box.png',
			'unavailable_weekdays'    => $unavailable_weekdays,
			'item_type_str_with_link' => $item_type_str_with_link,
			'item_type_str'           => $item_type_str,
		);

		/**
		 * This hooks runs when the item details are demanded.
		 *
		 * This hook helps modify the reservation item details.
		 *
		 * @param array $item_details Reservation item details.
		 * @param int   $item_id Reservation item ID.
		 * @return array
		 */
		return apply_filters( 'ersrv_reservation_item_details', $item_details, $item_id );
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_get_account_endpoint_favourite_reservations' ) ) {
	/**
	 * Get the endpoint slug for customer account - favourite reservable items.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	function ersrv_get_account_endpoint_favourite_reservations() {
		$endpoint = 'favourite-reservable-items';
		/**
		 * This hook fires on customer's account page.
		 *
		 * This filter will help in modifying the favourite reservable items endpoint slug.
		 *
		 * @param string $endpoint Custom account endpoint slug.
		 * @return string
		 */
		return apply_filters( 'ersrv_account_endpoint_favourite_reservations_slug', $endpoint );
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_get_account_endpoint_label_favourite_reservations' ) ) {
	/**
	 * Get the endpoint label for customer account - favourite reservable items.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	function ersrv_get_account_endpoint_label_favourite_reservations() {
		$endpoint_label = __( 'Favourite Reservable Items', 'easy-reservations' );
		/**
		 * This hook fires on customer's account page.
		 *
		 * This filter will help in modifying the favourite reservable items endpoint label.
		 *
		 * @param string $endpoint_label Custom account endpoint label.
		 * @return string
		 */
		return apply_filters( 'ersrv_account_endpoint_favourite_reservations_label', $endpoint_label );
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_get_page_id' ) ) {
	/**
	 * Get the page ID.
	 *
	 * @param string $page_slug Holds the page slug.
	 * @return int
	 * @since 1.0.0
	 */
	function ersrv_get_page_id( $page_slug ) {
		$page = apply_filters( 'ersrv_get_' . $page_slug . '_page_id', get_option( 'ersrv_' . $page_slug . '_page_id' ) );

		return $page ? absint( $page ) : -1;
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_register_reservation_type_taxonomy' ) ) {
	/**
	 * Register custom taxonomy - reservation item type.
	 *
	 * @since 1.0.0
	 */
	function ersrv_register_reservation_type_taxonomy() {
		// Taxonomy arguments.
		$args = array(
			'labels'            => array(
				'name'              => _x( 'Reservation Item Types', 'taxonomy general name', 'easy-reservations' ),
				'singular_name'     => _x( 'Reservation Item Type', 'taxonomy singular name', 'easy-reservations' ),
				'search_items'      => __( 'Search Reservation Item Types', 'easy-reservations' ),
				'all_items'         => __( 'All Reservation Item Types', 'easy-reservations' ),
				'parent_item'       => __( 'Parent Reservation Item Type', 'easy-reservations' ),
				'parent_item_colon' => __( 'Parent Reservation Item Type:', 'easy-reservations' ),
				'edit_item'         => __( 'Edit Reservation Item Type', 'easy-reservations' ),
				'update_item'       => __( 'Update Reservation Item Type', 'easy-reservations' ),
				'add_new_item'      => __( 'Add New Reservation Item Type', 'easy-reservations' ),
				'new_item_name'     => __( 'New Reservation Item Type Name', 'easy-reservations' ),
				'menu_name'         => __( 'Reservation Item Types', 'easy-reservations' ),
			),
			'hierarchical'      => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'reservation-item-type' ),
		);

		/**
		 * This hook fires in WordPress admin panel.
		 *
		 * This hook helps in modifying the reservation item type taxonomy arguments.
		 *
		 * @param array $args Taxonomy arguments array.
		 * @return array
		 * @since 1.0.0
		 */
		$args = apply_filters( 'ersrv_reservation_type_taxonomy_args', $args );

		// Register the taxonomy.
		register_taxonomy( 'reservation-item-type', array( 'product' ), $args );
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_get_current_date' ) ) {
	/**
	 * Return the current date/time according to local server time.
	 *
	 * @param string $format Date format.
	 * @return string
	 * @since 1.0.0
	 */
	function ersrv_get_current_date( $format = 'Y-m-d H:i:s' ) {

		return date_i18n( _x( $format, 'timezone date format' ) );
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_get_php_date_format' ) ) {
	/**
	 * Return the date format.
	 *
	 * @param string $js_date_format Date format.
	 * @return string
	 * @since 1.0.0
	 */
	function ersrv_get_php_date_format( $js_date_format = '' ) {
		$datepicker_format = ( ! empty( $js_date_format ) ) ? $js_date_format : ersrv_get_plugin_settings( 'ersrv_datepicker_date_format' );
		$datepicker_format = str_replace( 'mm', 'm', $datepicker_format );
		$datepicker_format = str_replace( 'dd', 'd', $datepicker_format );
		$datepicker_format = str_replace( 'yy', 'Y', $datepicker_format );
		/**
		 * This filters fires when a date is printed.
		 *
		 * This filter helps in modifying the date format.
		 *
		 * @param string $datepicker_format Datepicker format.
		 * @return string
		 * @since 1.0.0
		 */
		$datepicker_format = apply_filters( 'ersrv_php_date_format', $datepicker_format );

		return $datepicker_format;
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_block_dates_after_reservation_thankyou' ) ) {
	/**
	 * Block the reservation dates of the order items.
	 *
	 * @param WC_Order $wc_order WooCommerce order.
	 * @since 1.0.0
	 */
	function ersrv_block_dates_after_reservation_thankyou( $wc_order ) {
		$order_id = $wc_order->get_id();
		// Block the dates of the items so they show as reserved.
		$dates_blocked = get_post_meta( $order_id, 'ersrv_blocked_dates_of_reservation_items', true );

		// Return, if the dates are already blocked.
		if ( ! empty( $dates_blocked ) ) {
			return;
		}

		// Get the items.
		$order_line_items = $wc_order->get_items();

		// If there are line items.
		if ( ! empty( $order_line_items ) && is_array( $order_line_items ) ) {
			foreach ( $order_line_items as $order_line_item ) {
				$line_item_id = $order_line_item->get_id();
				$product_id   = $order_line_item->get_product_id();
				
				// If this product is a reservation product.
				$is_reservation_product = ersrv_product_is_reservation( $product_id );

				// Skip the loop, if the product is not reservation type.
				if ( ! $is_reservation_product ) {
					continue;
				}

				// Get the blocked dates.
				$blocked_dates = get_post_meta( $product_id, '_ersrv_reservation_blockout_dates', true );
				$blocked_dates = ( ! empty( $blocked_dates ) && is_array( $blocked_dates ) ) ? $blocked_dates : array();

				// Gather the reservation dates.
				$order_checkin_date    = wc_get_order_item_meta( $line_item_id, 'Checkin Date', true );
				$order_checkout_date   = wc_get_order_item_meta( $line_item_id, 'Checkout Date', true );
				$reservation_dates     = ersrv_get_dates_within_2_dates( $order_checkin_date, $order_checkout_date );
				$new_reservation_dates = array();
				$block_date_message    = sprintf( __( 'Reserved with order #%1$d', 'easy-reservations' ), $wc_order->get_id() );

				/**
				 * This filter fires on the checkout page.
				 *
				 * This filter helps in mpdifying the block date message.
				 *
				 * @param string $block_date_message Block message.
				 * @return string
				 * @since 1.0.0
				 */
				$block_date_message = apply_filters( 'ersrv_reservation_block_date_message', $block_date_message );

				if ( ! empty( $reservation_dates ) ) {
					foreach ( $reservation_dates as $date ) {
						$new_reservation_dates[] = array(
							'date'    => $date->format( ersrv_get_php_date_format() ),
							'message' => $block_date_message,
						);
					}
				}

				// Merge the dates now.
				$blocked_dates = array_merge( $blocked_dates, $new_reservation_dates );

				// Update the database finally.
				update_post_meta( $product_id, '_ersrv_reservation_blockout_dates', $blocked_dates );
			}
		}

		// Update the database, so this is not fired again.
		update_post_meta( $order_id, 'ersrv_blocked_dates_of_reservation_items', 1 );

		/**
		 * This hook runs after the reservation order is placed.
		 *
		 * @param int      $order_id WooCommerce order ID.
		 * @param WC_Order $wc_order WooCommerce order.
		 */
		do_action( 'ersrv_after_blocking_reservation_dates', $order_id, $wc_order );
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_get_export_reservation_orders_data' ) ) {
	/**
	 * Return the reservation orders data.
	 *
	 * @param array $wc_order_ids WooCommerce orders array.
	 * @return array
	 * @since 1.0.0
	 */
	function ersrv_get_export_reservation_orders_data( $wc_order_ids ) {
		$export_data = array();

		// Return, if the order IDs array is blank.
		if ( empty( $wc_order_ids ) || ! is_array( $wc_order_ids ) ) {
			return $export_data;
		}

		// Iterate through the order ids.
		foreach ( $wc_order_ids as $wc_order_id ) {
			// Get the WooCommerce order.
			$wc_order = wc_get_order( $wc_order_id );
			// Get the items in the reservation.
			$order_items = $wc_order->get_items();

			// Skip, if there is no item.
			if ( 0 === count( $order_items ) ) {
				continue;
			}

			// Iterate through the order items.
			foreach ( $order_items as $order_item ) {
				$item_id    = $order_item->get_id();
				$product_id = $order_item->get_product_id();

				// Gather the data in array.
				$export_data[] = array(
					'id'                 => $wc_order_id,
					'customer_id'        => get_post_meta( $wc_order_id, '_customer_user', true ),
					'item_id'            => $product_id,
					'item_name'          => get_the_title( $product_id ),
					'checkin_date'       => wc_get_order_item_meta( $item_id, 'Checkin Date', true ),
					'checkout_date'      => wc_get_order_item_meta( $item_id, 'Checkout Date', true ),
					'adult_count'        => wc_get_order_item_meta( $item_id, 'Adult Count', true ),
					'adult_subtotal'     => wc_get_order_item_meta( $item_id, 'Adult Subtotal', true ),
					'kids_count'         => wc_get_order_item_meta( $item_id, 'Kids Count', true ),
					'kids_subtotal'      => wc_get_order_item_meta( $item_id, 'Kids Subtotal', true ),
					'security'           => wc_get_order_item_meta( $item_id, 'Security Amount', true ),
					'amenities_subtotal' => wc_get_order_item_meta( $item_id, 'Amenities Subtotal', true ),
				);
			}
		}

		/**
		 * This hook fires on the AJAX call when the reservation orders are exported.
		 *
		 * This hook helps in managing the data that is being exported in the data.
		 *
		 * @param array $export_data Reservation export data.
		 * @param array $wc_order_ids Reservation order IDs.
		 * @return array
		 * @since 1.0.0
		 */
		return apply_filters( 'ersrv_reservations_export_data', $export_data, $wc_order_ids );
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_email_reservation_data_to_google_calendar' ) ) {
	/**
	 * Email the google calendar data to customer's email address.
	 *
	 * @param int $order_id WooCommerce order ID.
	 * @since 1.0.0
	 */
	function ersrv_email_reservation_data_to_google_calendar( $order_id ) {
		// Exit, if this order ID is invalid.
		$wc_order  = wc_get_order( $order_id );

		// Return, if the order doesn't exist anymore.
		if ( false === $wc_order ) {
			return;
		}

		// Get the line items.
		$line_items = $wc_order->get_items();

		// Check if there are reservation items.
		if ( empty( $line_items ) || ! is_array( $line_items ) ) {
			return;
		}

		/**
		 * This hook fires before google calendar invitation is downloaded.
		 *
		 * This hook helps in executing anything before the reservation google calendar invite is downloaded.
		 *
		 * @param int $order_id Holds the WooCommerce order ID.
		 */
		do_action( 'ersrv_add_reservation_to_gcal_before', $order_id );

		// Iterate through the line items to prepare the google cal link.
		foreach ( $line_items as $line_item ) {
			$item_id       = $line_item->get_id();
			$product_id    = $line_item->get_product_id();

			// Skip, if this is not a reservation item.
			if ( ! ersrv_product_is_reservation( $product_id ) ) {
				continue;
			}

			// Reservation item data.
			$checkin_date    = wc_get_order_item_meta( $item_id, 'Checkin Date', true );
			$checkin         = $checkin_date . ' ' .ersrv_get_plugin_settings( 'ersrv_reservation_onboarding_time' );
			$checkin         = ersrv_get_icalendar_formatted_date( strtotime( $checkin ), true );
			$checkout_date   = wc_get_order_item_meta( $item_id, 'Checkout Date', true );
			$checkout        = $checkout_date . ' ' .ersrv_get_plugin_settings( 'ersrv_reservation_offboarding_time' );
			$checkout        = ersrv_get_icalendar_formatted_date( strtotime( $checkout ), true );
			$view_order      = $wc_order->get_view_order_url();
			$billing_country = $wc_order->get_billing_country();
			$billing_state   = $wc_order->get_billing_state();

			// Google calendar base URL.
			$gcal_url = 'https://calendar.google.com/calendar/u/0/r/eventedit';

			// Query parameters.
			$gcal_params = array(
				'text'     => sprintf( __( 'Reservation with %1$s', 'easy-reservations' ), get_the_title( $product_id ) ),
				'dates'    => "{$checkin}/{$checkout}",
				'trp'      => false,
				'sprop'    => "website:{$view_order}",
				'pli'      => 1,
				'sf'       => true,
				'location' => get_post_meta( $product_id, '_ersrv_item_location', true ),
				'details'  => sprintf( __( 'This refers to the reservation order #%1$d. You can check more details here: %1$s', 'easy-reservations' ), $order_id, $view_order ),
				'ctz'      => 'Asia/Kolkata',
			);

			/**
			 * This hook fires when the request to save gcal file is processed.
			 *
			 * This filter helps to modify the gcal invitation details.
			 *
			 * @param array  $gcal_params Google Cal details.
			 * @param string $order_id WooCommerce Order ID.
			 * @return array
			 * @since 1.0.0
			 */
			$gcal_params = apply_filters( 'ersrv_google_calendar_invitation_details', $gcal_params, $order_id );

			// Add the details to compose a URL.
			$gcal_url = add_query_arg( $gcal_params, $gcal_url );

			// Email recipient.
			$recipient     = apply_filters( 'ersrv_gcalendar_invite_recipient_email', $wc_order->get_billing_email(), $wc_order );
			$subject       = apply_filters( 'ersrv_gcalendar_invite_email_subject', sprintf( __( 'Easy Reservations: Google Invitation for Reservation #%1$d', 'easy-reservations' ), $order_id ), $wc_order );
			$customer_name = $wc_order->get_billing_first_name() . ' ' . $wc_order->get_billing_last_name();

			// Email body.
			$blog_name   = get_bloginfo( 'name' );
			$email_body  = "<p>Hello {$customer_name},</p>";
			$email_body .= "<p>Please click the link below to add the reservation #{$order_id} to your Google Clendar.</p>";
			$email_body .= "<p><a href='{$gcal_url}'>Click here!</a></p>";
			$email_body .= '<p>Regards,</p>';
			$email_body .= "<p>Team {$blog_name}</p>";
			$email_body  = apply_filters( 'ersrv_gcalendar_invite_email_body', $email_body, $wc_order );

			// Email headers.
			$headers = array( 'Content-Type: text/html; charset=UTF-8' );

			// Shoot the email now.
			wp_mail( $recipient, $subject, $email_body, $headers );
		}

		/**
		 * This hook fires after google calendar invitation is downloaded.
		 *
		 * This hook helps in executing anything after the reservation google calendar invite is downloaded.
		 *
		 * @param int $order_id Holds the WooCommerce order ID.
		 */
		do_action( 'ersrv_add_reservation_to_gcal_after', $order_id );
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_email_reservation_data_to_icalendar' ) ) {
	/**
	 * Email the google calendar data to customer's email address.
	 *
	 * @param int $order_id WooCommerce order ID.
	 * @since 1.0.0
	 */
	function ersrv_email_reservation_data_to_icalendar( $order_id ) {
		// Exit, if this order ID is invalid.
		$wc_order  = wc_get_order( $order_id );
		$this_time = time();

		// Get the default onboarding and offboarding time.
		$default_onboarding_time  = ersrv_get_plugin_settings( 'ersrv_reservation_onboarding_time' );
		$default_offboarding_time = ersrv_get_plugin_settings( 'ersrv_reservation_offboarding_time' );

		// Return, if the order doesn't exist anymore.
		if ( false === $wc_order ) {
			return;
		}

		// Get the line items.
		$line_items = $wc_order->get_items();

		// Check if there are reservation items.
		if ( empty( $line_items ) || ! is_array( $line_items ) ) {
			return;
		}

		// Include the ical library file.
		require_once ERSRV_PLUGIN_PATH . 'includes/lib/WP_ICS.php';

		/**
		 * This hook fires before icalendar invitation is downloaded.
		 *
		 * This hook helps in executing anything before the reservation icalendar invite is downloaded.
		 *
		 * @param int $order_id Holds the WooCommerce order ID.
		 */
		do_action( 'ersrv_add_reservation_to_ical_before', $order_id );

		// Iterate through the line items to prepare the ical file.
		foreach ( $line_items as $line_item ) {
			$item_id       = $line_item->get_id();
			$product_id    = $line_item->get_product_id();

			// Skip, if this is not a reservation item.
			if ( ! ersrv_product_is_reservation( $product_id ) ) {
				continue;
			}

			// Checkin date & time.
			$checkin_date      = wc_get_order_item_meta( $item_id, 'Checkin Date', true );
			$checkin_date      = gmdate( 'Y-m-d', strtotime( $checkin_date ) );
			$checkin_date_time = "{$checkin_date} {$default_onboarding_time}";

			// Checkout date & time.
			$checkout_date      = wc_get_order_item_meta( $item_id, 'Checkout Date', true );
			$checkout_date      = gmdate( 'Y-m-d', strtotime( $checkout_date ) );
			$checkout_date_time = "{$checkout_date} {$default_offboarding_time}";

			$nvite_file_name = "ersrv-reservation-#{$order_id}-{$item_id}-{$this_time}.ics";
			/**
			 * This hook fires when the request to download ical file is processed.
			 *
			 * This filter helps to modify the ical file name.
			 *
			 * @param string $nvite_file_name iCal file name.
			 * @param string $order_id WooCommerce Order ID.
			 * @return string
			 * @since 1.0.0
			 */
			$nvite_file_name = apply_filters( 'ersrv_icalendar_invitation_filename', $nvite_file_name, $order_id );

			// Generate the ics file now.
			$invitation_details = array(
				'location'    => get_post_meta( $product_id, '_ersrv_item_location', true ),
				'description' => sprintf( __( 'Reservation for item, %1$s.', 'easy-reservations' ), get_the_title( $product_id ) ),
				'dtstart'     => ersrv_get_icalendar_formatted_date( strtotime( $checkin_date_time ), true ),
				'dtend'       => ersrv_get_icalendar_formatted_date( strtotime( $checkout_date_time ), true ),
				'summary'     => sprintf( __( 'Reservation for item, %1$s.', 'easy-reservations' ), get_the_title( $product_id ) ),
				'url'         => $wc_order->get_view_order_url(),
			);

			/**
			 * This hook fires when the request to download ical file is processed.
			 *
			 * This filter helps to modify the ical invitation details.
			 *
			 * @param array  $invitation_details iCal details.
			 * @param string $order_id WooCommerce Order ID.
			 * @return array
			 * @since 1.0.0
			 */
			$invitation_details = apply_filters( 'ersrv_icalendar_invitation_details', $invitation_details, $order_id );

			// Generate the invitation now.
			$ics = new WP_ICS( $invitation_details );

			// Define the uploads path.
			$uploads_dir = wp_upload_dir();
			$upload_path = $uploads_dir['path'];
			$ics_file    = $upload_path . "/{$nvite_file_name}";

			// Download the invitation file in the path.
			file_put_contents( $ics_file, $ics->to_string() );

			// Email recipient.
			$recipient     = apply_filters( 'ersrv_icalendar_invite_recipient_email', $wc_order->get_billing_email(), $wc_order );
			$subject       = apply_filters( 'ersrv_icalendar_invite_email_subject', sprintf( __( 'Easy Reservations: iCalendar Invitation for Reservation #%1$d', 'easy-reservations' ), $order_id ), $wc_order );
			$customer_name = $wc_order->get_billing_first_name() . ' ' . $wc_order->get_billing_last_name();

			// Email body.
			$blog_name   = get_bloginfo( 'name' );
			$email_body  = "<p>Hello {$customer_name},</p>";
			$email_body .= "<p>Please find the attached iClendar invitation file for the reservation: #{$order_id}</p>";
			$email_body .= '<p>Download the file and import in your device.</p>';
			$email_body .= '<p>Regards,</p>';
			$email_body .= "<p>Team {$blog_name}</p>";
			$email_body  = apply_filters( 'ersrv_icalendar_invite_email_body', $email_body, $wc_order );

			// Email headers.
			$headers = array( 'Content-Type: text/html; charset=UTF-8' );

			// Email attachments.
			$attachments = array( $ics_file );

			// Shoot the email now.
			wp_mail( $recipient, $subject, $email_body, $headers, $attachments );

			// Unlink the attachment now.
			unlink( $ics_file );
		}

		/**
		 * This hook fires after icalendar invitation is downloaded.
		 *
		 * This hook helps in executing anything after the reservation icalendar invite is downloaded.
		 *
		 * @param int $order_id Holds the WooCommerce order ID.
		 */
		do_action( 'ersrv_add_reservation_to_ical_after', $order_id );
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_is_reservation_in_cart' ) ) {
	/**
	 * Check if there is any reservation item in the cart.
	 *
	 * @return bool
	 * @since 1.0.0
	 */
	function ersrv_is_reservation_in_cart() {
		// Get cart.
		$cart            = WC()->cart->get_cart();
		$has_reservation = false;

		// Iterate through the cart items to set the price.
		foreach ( $cart as $cart_item ) {
			// Return.
			if ( ! empty( $cart_item['reservation_data'] ) ) {
				$has_reservation = true;
				break;
			}
		}

		/**
		 * This filter runs on the checkout page.
		 *
		 * This filter helps in detecting whether the cart has any reservation item or not.
		 *
		 * @param bool $has_reservation Cart has reservation item or not.
		 * @return bool
		 * @since 1.0.0
		 */
		return apply_filters( 'ersrv_cart_has_reservation', $has_reservation ); // Return the decision now.
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_print_calendar_buttons' ) ) {
	/**
	 * Print the calendar buttons for the woocommerce order.
	 *
	 * @param int      $order_id WooCommerce order ID.
	 * @param WC_Order $wc_order WooCommerce order object.
	 * @since 1.0.0
	 */
	function ersrv_print_calendar_buttons( $order_id, $wc_order ) {
		$google_calendar_button_text = __( 'Add to my Google Calendar', 'easy-reservations' );
		/**
		 * This hook fires on checkout page to add reservation to calendar.
		 *
		 * This filter helps to modify the google calendar button text, which adds the reservation to google calendar.
		 *
		 * @param string   $google_calendar_button_text Google calendar button text.
		 * @param WC_Order $wc_order WooCommerce Order data.
		 * @return string
		 * @since 1.0.0
		 */
		$google_calendar_button_text = apply_filters( 'ersrv_add_reservation_to_google_calendar_button_text', $google_calendar_button_text, $wc_order );

		$icalendar_button_text = __( 'Add to my iCalendar', 'easy-reservations' );
		/**
		 * This hook fires on checkout page to add reservation to calendar.
		 *
		 * This filter helps to modify the icalendar button text, which adds the reservation to icalendar.
		 *
		 * @param string   $icalendar_button_text Google calendar button text.
		 * @param WC_Order $wc_order WooCommerce Order data.
		 * @return string
		 * @since 1.0.0
		 */
		$icalendar_button_text = apply_filters( 'ersrv_add_reservation_to_icalendar_button_text', $icalendar_button_text, $wc_order );

		// Prepare the html now.
		ob_start();
		?>
		<div class="ersrv-reservation-calendars-container" data-oid="<?php echo esc_attr( $order_id ); ?>">
			<a href="#" class="btn btn-accent add-to-gcal" title="<?php echo wp_kses_post( $google_calendar_button_text ); ?>"><?php echo wp_kses_post( $google_calendar_button_text ); ?></a>
			<a href="#" class="btn btn-accent add-to-ical" title="<?php echo wp_kses_post( $icalendar_button_text ); ?>"><?php echo wp_kses_post( $icalendar_button_text ); ?></a>
		</div>
		<?php
		$reservations_calendar_container = ob_get_clean();

		/**
		 * This hook fires on checkout page.
		 *
		 * This hook helps to modify the reservations calendar html container.
		 *
		 * @param string   $reservations_calendar_container Holds the reservations calendar html.
		 * @param string   $google_calendar_button_text Holds the google calendar button text.
		 * @param string   $icalendar_button_text Holds the icalendar button text.
		 * @param WC_Order $wc_order WooCommerce Order data.
		 * @return string
		 * @since 1.0.0
		 */
		echo wp_kses(
			apply_filters(
				'ersrv_reservations_calendar_container_html',
				$reservations_calendar_container,
				$google_calendar_button_text,
				$icalendar_button_text,
				$wc_order
			),
			array(
				'div'    => array(
					'class'    => array(),
					'data-oid' => array(),
				),
				'a' => array(
					'href'  => array(),
					'class' => array(),
					'title' => array(),
				),
			)
		);
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_print_receipt_button' ) ) {
	/**
	 * Print the receipt button for the woocommerce order.
	 *
	 * @param int      $order_id WooCommerce order ID.
	 * @param WC_Order $wc_order WooCommerce order object.
	 * @since 1.0.0
	 */
	function ersrv_print_receipt_button( $order_id, $wc_order ) {
		// Check if the order status is allowed for receipts.
		$display_order_receipt = ersrv_should_display_receipt_button( $order_id );

		// Return the actions if the receipt button should not be displayed.
		if ( false === $display_order_receipt ) {
			return;
		}

		$button_text  = ersrv_get_plugin_settings( 'ersrv_easy_reservations_receipt_button_text' );
		$button_url   = ersrv_download_reservation_receipt_url( $order_id );
		$button_title = ersrv_download_reservation_receipt_button_title( $order_id );
		?>
		<div class="ersrv-reservation-receipt-container">
			<a href="<?php echo esc_url( $button_url ); ?>" class="btn btn-accent" title="<?php echo esc_html( $button_title ); ?>"><?php echo esc_html( $button_text ); ?></a>
		</div>
		<?php
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_print_reservation_cancel_button' ) ) {
	/**
	 * Print the receipt button for the woocommerce order.
	 *
	 * @param int $item_id WooCommerce order item ID.
	 * @param int $order_id WooCommerce order ID.
	 * @since 1.0.0
	 */
	function ersrv_print_reservation_cancel_button( $item_id, $order_id ) {
		// Check if the request is already raised.
		$request_status = wc_get_order_item_meta( $item_id, 'ersrv_cancellation_request_status' );
		$button_text    = ersrv_get_plugin_settings( 'ersrv_cancel_reservations_button_text' );
		$tooltip_text   = __( 'Click on this button to raise a cancellation request for this reservation.', 'easy-reservations' );

		// Get the tooltip text.
		if ( ! empty( $request_status ) ) {
			if ( 'pending' === $request_status ) {
				$tooltip_text = __( 'Cancellation request pending.', 'easy-reservations' );
			} elseif ( 'approved' === $request_status ) {
				$tooltip_text = __( 'Cancellation request approved.', 'easy-reservations' );
			} elseif ( 'declined' === $request_status ) {
				$tooltip_text = __( 'Cancellation request declined.', 'easy-reservations' );
			}
		}

		
		/**
		 * This hook runs on the view woocommerce order page.
		 *
		 * This filter helps in changing the tooltip text for the reservation cancellation request button.
		 *
		 * @param string $tooltip_text Tooltip text.
		 * @param string $request_status If the request has already been raised.
		 * @return string
		 * @since 1.0.0
		 */
		$tooltip_text = apply_filters( 'ersrv_reservation_cancellation_request_button_tooltip_text', $tooltip_text, $request_status );
		?>
		<div data-tooltip="<?php echo esc_html( $tooltip_text ); ?>" class="tooltip ersrv-reservation-cancellation-container" data-order="<?php echo esc_attr( $order_id ); ?>" data-item="<?php echo esc_attr( $item_id ); ?>">
			<button type="button" class="btn btn-accent <?php echo esc_attr( ( ! empty( $request_status ) ) ? 'non-clickable' : '' ); ?>" title="<?php echo esc_html( $button_text ); ?>"><?php echo esc_html( $button_text ); ?></a>
		</div>
		<?php
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_print_edit_reservation_button' ) ) {
	/**
	 * Print the edit reservation button for the woocommerce order.
	 *
	 * @param int      $order_id WooCommerce order ID.
	 * @param WC_Order $wc_order WooCommerce order object.
	 * @since 1.0.0
	 */
	function ersrv_print_edit_reservation_button( $order_id, $wc_order ) {
		// Check if the edit reservation is allowed.
		$allowed_to_edit_reservation = ersrv_get_plugin_settings( 'ersrv_enable_reservation_edit' );

		// Return if editing reservation is not allowed.
		if ( empty( $allowed_to_edit_reservation ) || 'no' === $allowed_to_edit_reservation ) {
			return;
		}

		// Check for the reservation item dates.

		$is_updated                = get_post_meta( $order_id, 'ersrv_reservation_update', true );
		$disabled_button_class     = ( ! empty( $is_updated ) && '1' === $is_updated ) ? 'non-clickable' : '';
		$button_text               = ersrv_get_plugin_settings( 'ersrv_edit_reservation_button_text' );
		$edit_reservation_page_id  = ersrv_get_page_id( 'edit-reservation' );
		$edit_reservation_page_url = get_permalink( $edit_reservation_page_id );
		$query_params              = array(
			'action' => 'edit-reservation',
			'id'    => $order_id,
		);
		$edit_reservation_page_url = add_query_arg( $query_params, $edit_reservation_page_url );

		if ( ! empty( $is_updated ) && '1' === $is_updated ) {
			?><div data-tooltip="<?php esc_html_e( 'The reservation has already been updated once. Cannot update it more.', 'easy-reservations' ); ?>" class="tooltip ersrv-edit-reservation-container"><?php
		} else {
			?><div class="ersrv-edit-reservation-container"><?php
		}
		?>
			<a href="<?php echo esc_url( $edit_reservation_page_url ); ?>" class="btn btn-accent <?php echo esc_attr( $disabled_button_class ); ?>" title="<?php echo esc_html( $button_text ); ?>"><?php echo esc_html( $button_text ); ?></a>
		</div>
		<?php
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_reservation_eligible_for_cancellation' ) ) {
	/**
	 * Check if the particular reservation item is eligible for cancellation.
	 *
	 * @param int $item_id WooCommerce order item ID.
	 * @return bool
	 * @since 1.0.0
	 */
	function ersrv_reservation_eligible_for_cancellation( $item_id ) {
		$eligibility_days = ersrv_get_plugin_settings( 'ersrv_cancel_reservation_request_before_days' );

		// Return true, if this is -1.
		if ( -1 === $eligibility_days ) {
			return true;
		}

		// Get the checkin date.
		$checkin_date          = wc_get_order_item_meta( $item_id, 'Checkin Date', true );
		$days_difference_count = ersrv_get_days_count_until_checkin( $checkin_date );

		if ( $days_difference_count <= $eligibility_days ) {
			return false;
		}

		return true;
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_get_days_count_until_checkin' ) ) {
	/**
	 * Get the number of days from today until the checkin date
	 */
	function ersrv_get_days_count_until_checkin( $checkin_date, $date_from = '' ) {
		// Get the days yet to the reservation.
		$date_from   = ( empty( $date_from ) ) ? gmdate( ersrv_get_php_date_format() ) : $date_from;
		$dates_range = ersrv_get_dates_within_2_dates( $date_from, $checkin_date, true );
		$dates       = array();

		// Iterate through the dates.
		if ( ! empty( $dates_range ) ) {
			foreach ( $dates_range as $date ) {
				$dates[] = $date->format( ersrv_get_php_date_format() );
			}
		}
		
		// Dates count.
		return count( $dates );
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_get_readable_order_status' ) ) {
	/**
	 * Get the human readable order status string.
	 *
	 * @param string $status WooCommerce order status.
	 * @return string
	 * @since 1.0.0
	 */
	function ersrv_get_readable_order_status( $status ) {
		$valid_stuses = wc_get_order_statuses();

		return ( ! empty( $valid_stuses[ "wc-{$status}" ] ) ) ? $valid_stuses[ "wc-{$status}" ] : '';
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_print_updated_reservation_cost_difference' ) ) {
	/**
	 * Print the html for the updated reservation cost difference.
	 *
	 * @param int $order_id WooCommerce order ID.
	 * @since 1.0.0
	 */
	function ersrv_print_updated_reservation_cost_difference( $order_id ) {
		// Check if the order is updated.
		$is_order_updated = get_post_meta( $order_id, 'ersrv_reservation_update', true );

		// Return, if the reservation is not updated.
		if ( empty( $is_order_updated ) || '1' !== $is_order_updated ) {
			return;
		}

		$cost_difference     = (float) get_post_meta( $order_id, 'ersrv_cost_difference', true ); // Get the cost difference.
		$cost_difference_key = get_post_meta( $order_id, 'ersrv_cost_difference_key', true ); // Get the cost difference key.
		$tagline             = '';

		// Return, if there is no cost difference.
		if ( empty( $cost_difference ) ) {
			return;
		}

		// Tagline.
		if ( ! empty( $cost_difference_key ) ) {
			if ( 'cost_difference_customer_payable' === $cost_difference_key ) {
				$tagline = __( 'Since the reservation order was updated, you need to pay this before you onboard:', 'easy-reservations' );
			} elseif ( 'cost_difference_admin_payable' === $cost_difference_key ) {
				$tagline = __( 'Since the reservation order was updated, the administrator shall refund this after you complete your reservation:', 'easy-reservations' );
			}
		}

		/**
		 * Hook for the tagline.
		 * This hook runs on the view order endpoint.
		 *
		 * This filter helps modify the the tagline where the cost difference is displayed.
		 *
		 * @param string $tagline Cost difference tagline.
		 * @param float  $cost_difference Cost difference amount.
		 * @param string $cost_difference_key Cost difference key.
		 * @param int    $order_id WooCommerce order ID.
		 */
		$tagline = apply_filters( 'ersrv_cost_difference_tagline', $tagline, $cost_difference, $cost_difference_key, $order_id );

		// Prepare the HTML now.
		ob_start();
		?>
		<p><?php echo esc_html( $tagline ); ?></p>
		<table class="woocommerce-table woocommerce-table--order-details cost_difference_details">
			<tbody>
				<tr>
					<th scope="row"><?php esc_html_e( 'Updated Reservation: Cost Difference', 'easy-reservations' ); ?></th>
					<td>
						<strong>
							<?php
							echo wp_kses(
								wc_price( $cost_difference ),
								array(
									'span' => array(
										'class' => array(),
									),
								)
							);
							?>
						</strong>
					</td>
				</tr>
			</tbody>
		</table>
		<?php
		$cost_difference_html = ob_get_clean();

		/**
		 * This hook executes when there is order details printed.
		 *
		 * This filters helps you modify the cost difference html.
		 *
		 * @param string $cost_difference_html Cost difference HTML.
		 * @param int    $order_id WooCommerce order ID.
		 * @return string
		 * @since 1.0.0
		 */
		echo apply_filters(
			'ersrv_updated_reservation_cost_difference_html',
			wp_kses(
				$cost_difference_html,
				array(
					'p' => array(),
					'table' => array(
						'class' => array(),
					),
					'table' => array(),
					'tr' => array(),
					'th' => array(
						'scope' => array(),
					),
					'td' => array(),
					'strong' => array(),
					'span' => array(
						'class' => array(),
					),
				)
			),
			$order_id
		);
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_no_reservation_item_found_html' ) ) {
	/**
	 * Return the html when no reservation item is found.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	function ersrv_no_reservation_item_found_html( $include_reset = false ) {
		ob_start();
		?>
		<div class="ersrv-no-reservation-post-found">
			<p><?php esc_html_e( 'No reservation items found !!', 'easy-reservations' ); ?></p>
			<?php if ( $include_reset ) {
				$search_page_id = ersrv_get_page_id( 'search-reservations' );
				$search_page    = get_permalink( $search_page_id );
				?>
				<a class="btn btn-accent" href="<?php echo esc_url( $search_page ); ?>"><?php esc_html_e( 'Reset Search', 'easy-reservations' ); ?></a>
			<?php } ?>
		</div>
		<?php

		return ob_get_clean();
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_shorten_filename' ) ) {
	/**
	 * Return the shortened filename from a very long filename.
	 *
	 * @param string $long_filename Long filename.
	 * @return string
	 * @since 1.0.0
	 */
	function ersrv_shorten_filename( $long_filename ) {
		// Return, if the filename is empty.
		if ( empty( $long_filename ) ) {
			return $long_filename;
		}

		
		$left_part  = substr( $long_filename, 0, 18 ); // Get the left part of the filename.
		$right_part = substr( $long_filename, -18, 18 ); // Get the right part of the filename.
		$filename   = "{$left_part}...{$right_part}";

		/**
		 * This hooks runs on the checkout page basically where the license files are uploaded.
		 *
		 * This hooks helps in modifying the shortened filename.
		 *
		 * @param string $filename File name.
		 * @return string
		 * @since 1.0.0
		 */
		return apply_filters( 'ersrv_shortened_filename', $filename );
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_is_amenity_reserved' ) ) {
	/**
	 * Check if the amenity is reserved.
	 *
	 * @param string $amenity_title Amenity title.
	 * @param array  $reserved_amenities Reserved amenities.
	 * @return boolean
	 * @since 1.0.0
	 */
	function ersrv_is_amenity_reserved( $amenity_title, $reserved_amenities ) {
		// Return false, if the reserved amenities array is empty.
		if ( empty( $reserved_amenities ) || ! is_array( $reserved_amenities ) ) {
			return false;
		}

		// Reserved amenities titles.
		$reserved_amenities_titles = array_column( $reserved_amenities, 'amenity' );
		$is_reserved               = ( ! empty( $reserved_amenities_titles ) && in_array( $amenity_title, $reserved_amenities_titles, true ) ) ? true : false;

		/**
		 * This hook executes on the edit reservation page.
		 *
		 * This hook helps in modifying the value whether the amenity is reserved or not.
		 *
		 * @param boolean $is_reserved Is the amenity reserved.
		 * @param string $amenity_title Amenity title.
		 * @param array  $reserved_amenities Reserved amenities.
		 * @return boolean
		 * @since 1.0.0
		 */
		return apply_filters( 'ersrv_is_amenity_reserved', $is_reserved, $amenity_title, $reserved_amenities );
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_flush_out_reserved_dates' ) ) {
	/**
	 * Flush out the reserved dates.
	 *
	 * @param int $order_id WooCommerce order ID.
	 * @return void
	 * @since 1.0.0
	 */
	function ersrv_flush_out_reserved_dates( $order_id, $item_id = 0 ) {
		$wc_order = wc_get_order( $order_id );

		// Return false, in case the order ID is invalid.
		if ( false === $wc_order ) {
			return;
		}

		// See if the item ID is provided, directly call the item ID function to flush out the reserved dates.
		if ( 0 !== $item_id ) {
			ersrv_flush_out_reserved_dates_reservation_item( $item_id );
			return;
		}

		/**
		 * If we're here, this means, we're requested to delete the reservation dates for all the items.
		 * Get the reservation items now.
		 */
		$line_items = $wc_order->get_items();

		// Return, if there are no items.
		if ( empty( $line_items ) || ! is_array( $line_items ) ) {
			return;
		}

		// Iterate through the items.
		foreach ( $line_items as $line_item ) {
			$item_id    = $line_item->get_id();
			$product_id = $line_item->get_product_id();

			// Skip, if this is not a reservation item.
			if ( ! ersrv_product_is_reservation( $product_id ) ) {
				continue;
			}

			// Request to flis out the dates now.
			ersrv_flush_out_reserved_dates_reservation_item( $item_id );
		}
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_flush_out_reserved_dates_reservation_item' ) ) {
	/**
	 * Flush out the reserved dates.
	 *
	 * @param int $order_id WooCommerce order ID.
	 * @return void
	 * @since 1.0.0
	 */
	function ersrv_flush_out_reserved_dates_reservation_item( $item_id = 0 ) {
		// Return, if the item ID is zero.
		if ( 0 === $item_id ) {
			return;
		}

		// Get the product ID.
		$product_id = (int) wc_get_order_item_meta( $item_id, '_product_id', true );

		// Check if the product exists.
		$wc_product = wc_get_product( $product_id );

		// Return, if the product doesn't exist anymore.
		if ( false === $wc_product ) {
			return;
		}

		// Get the item reserved dates.
		$checkin_date  = wc_get_order_item_meta( $item_id, 'Checkin Date', true );
		$checkout_date = wc_get_order_item_meta( $item_id, 'Checkout Date', true );

		// Get the dates between the checkin and checkout dates.
		$reserved_dates_obj = ersrv_get_dates_within_2_dates( $checkin_date, $checkout_date );
		$reserved_dates_arr = array();

		if ( ! empty( $reserved_dates_obj ) ) {
			foreach ( $reserved_dates_obj as $date ) {
				$reserved_dates_arr[] = $date->format( ersrv_get_php_date_format() );
			}
		}

		// Get the reserved dates from the database.
		$product_reserved_dates = get_post_meta( $product_id, '_ersrv_reservation_blockout_dates', true );

		// Return, if there are no dates in the database.
		if ( empty( $product_reserved_dates ) || ! is_array( $product_reserved_dates ) ) {
			return;
		}

		// Iterate through the dates to flush them.
		foreach ( $product_reserved_dates as $key => $product_reserved_date ) {
			$date = ( ! empty( $product_reserved_date['date'] ) ) ? $product_reserved_date['date'] : '';

			// Skip, if the date is empty.
			if ( empty( $date ) ) {
				continue;
			}

			// See, if the date is the one of the reserved in this reservation item.
			if ( in_array( $date, $reserved_dates_arr, true ) ) {
				unset( $product_reserved_dates[ $key ] );
			}
		}

		// If there are no reserved dates remaining, delete the meta index from db.
		if ( empty( $product_reserved_dates ) ) {
			delete_post_meta( $product_id, '_ersrv_reservation_blockout_dates' );
			return;
		}

		// Reindex the remaining dates.
		$product_reserved_dates = array_values( $product_reserved_dates );

		// Update the remaining dates in the database.
		update_post_meta( $product_id, '_ersrv_reservation_blockout_dates', $product_reserved_dates );
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_is_reservation_item_already_in_cart' ) ) {
	/**
	 * Check if the reservation item is already in the cart.
	 *
	 * @param int $item_id WooCommerce reservation product ID.
	 * @return boolean|string
	 * @since 1.0.0
	 */
	function ersrv_is_reservation_item_already_in_cart( $item_id ) {
		// Get cart.
		$cart = WC()->cart->get_cart();

		if ( empty( $cart ) || ! is_array( $cart ) ) {
			return false;
		}

		// Loop in the cart items to check for offer available for each one.
		foreach ( $cart as $cart_key => $cart_item ) {
			$product_id = $cart_item['product_id'];

			// If the item ID matches, return the cart item key.
			if ( $product_id === $item_id ) {
				return $cart_key;
			}
		}

		return false;
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_in_cart_item_reserved_dates' ) ) {
	/**
	 * Get the reservation dates of the item already in the cart.
	 *
	 * @param int $cart_item_key WooCommerce cart item key.
	 * @return array
	 * @since 1.0.0
	 */
	function ersrv_in_cart_item_reserved_dates( $cart_item_key ) {
		// Get cart.
		$cart = WC()->cart->get_cart();

		if ( empty( $cart ) || ! is_array( $cart ) ) {
			return array();
		}

		// Get the cart item reservation data.
		$cart_item_reservation_data = ( ! empty( $cart[ $cart_item_key ]['reservation_data'] ) ) ? $cart[ $cart_item_key ]['reservation_data'] : array();

		// Return, if the cart item reservation data is blank.
		if ( empty( $cart_item_reservation_data ) ) {
			return array();
		}

		// Checkin and checkout dates.
		$checkin_date  = ( ! empty( $cart_item_reservation_data['checkin_date'] ) ) ? $cart_item_reservation_data['checkin_date'] : '';
		$checkout_date = ( ! empty( $cart_item_reservation_data['checkout_date'] ) ) ? $cart_item_reservation_data['checkout_date'] : '';

		// Return blank, if either of the date is empty.
		if ( empty( $checkin_date ) || empty( $checkout_date ) ) {
			return array();
		}

		// Get the reservation dates now.
		$reservation_dates_obj = ersrv_get_dates_within_2_dates( $checkin_date, $checkout_date );
		$reservation_dates_arr = array();

		if ( ! empty( $reservation_dates_obj ) ) {
			foreach ( $reservation_dates_obj as $date ) {
				$reservation_dates_arr[] = $date->format( ersrv_get_php_date_format() );
			}
		}

		return $reservation_dates_arr;
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_approve_reservation_cancellation_request' ) ) {
	/**
	 * Approve the reservation cancellation request.
	 *
	 * @param int $order_id WooCommerce order ID.
	 * @param int $line_item_id WooCommerce order line item ID.
	 * @since 1.0.0
	 */
	function ersrv_approve_reservation_cancellation_request( $order_id, $line_item_id ) {
		ersrv_flush_out_reserved_dates( $order_id, $line_item_id ); // Flush out the dates.
		wc_update_order_item_meta( $line_item_id, 'ersrv_cancellation_request_status', 'approved' ); // Update the request.

		/**
		 * This action runs on the admin listing page of reservation cancellation requests.
		 *
		 * This hook help adding custom actions after the reservation cancellation request has been approved.
		 * An email is sent to the customer at this action.
		 *
		 * @param int $line_item_id WooCommerce order line item id.
		 *
		 * @since 1.0.0
		 */
		do_action( 'ersrv_after_reservation_cancellation_request_approved', $line_item_id );
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_decline_reservation_cancellation_request' ) ) {
	/**
	 * Decline the reservation cancellation request.
	 *
	 * @param int $line_item_id WooCommerce order line item ID.
	 * @since 1.0.0
	 */
	function ersrv_decline_reservation_cancellation_request( $line_item_id ) {
		wc_update_order_item_meta( $line_item_id, 'ersrv_cancellation_request_status', 'declined' ); // Update the request.

		/**
		 * This action runs on the admin listing page of reservation cancellation requests.
		 *
		 * This hook help adding custom actions after the reservation cancellation request has been declined.
		 * An email is sent to the customer at this action.
		 *
		 * @param int $line_item_id WooCommerce order line item id.
		 *
		 * @since 1.0.0
		 */
		do_action( 'ersrv_after_reservation_cancellation_request_declined', $line_item_id );
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_add_custom_user_roles' ) ) {
	/**
	 * Register custom user roles.
	 *
	 * @since 1.0.0
	 */
	function ersrv_add_custom_user_roles() {
		$custom_user_roles = array(
			'reservation_item_captain' => array(
				'display_name' => __( 'Captain', 'easy-reservations' ),
				'capabilities' => array(
					'read' => true,
				),
			),
		);
		/**
		 * This hook runs on WordPress init.
		 *
		 * This hook helps in managing the custom user roles.
		 *
		 * @param array $custom_user_roles Custom user roles.
		 * @return array
		 * @since 1.0.0
		 */
		$custom_user_roles = apply_filters( 'ersrv_custom_user_roles', $custom_user_roles );

		// Return, if the user roles array is empty.
		if ( empty( $custom_user_roles ) || ! is_array( $custom_user_roles ) ) {
			return;
		}

		// Iterate through the user roles.
		foreach ( $custom_user_roles as $role_name => $role_data ) {
			$role_exists = $GLOBALS['wp_roles']->is_role( $role_name );

			// Skip, if the role already exists.
			if ( $role_exists ) {
				continue;
			}

			// Role data.
			$display_name = ( ! empty( $role_data['display_name'] ) ) ? $role_data['display_name'] : $role_name;
			$capabilities = ( ! empty( $role_data['capabilities'] ) ) ? $role_data['capabilities'] : array( 'read' => true );

			// Add the role now.
			add_role( $role_name, $display_name, $capabilities );
		}
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_get_amenity_single_fee_text' ) ) {
	/**
	 * Amenity - single fee text.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	function ersrv_get_amenity_single_fee_text() {
		if("yes" === get_option("ersrv_enable_time_with_date")){
			$single_fee_text = __( 'per hour cost', 'easy-reservations' );
		}else{
			$single_fee_text = __( 'one time cost', 'easy-reservations' );
		}
		/**
		 * This filter runs on the public end on the reservation pages.
		 *
		 * This hook helps in modifying the single fee text for the amenities cost type.
		 *
		 * @param string $single_fee_text Amenity single fee text.
		 * @return string
		 * @since 1.0.0
		 */
		return apply_filters( 'ersrv_amenity_single_fee_text', $single_fee_text );
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_get_amenity_daily_fee_text' ) ) {
	/**
	 * Amenity - daily fee text.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	function ersrv_get_amenity_daily_fee_text() {
		if("yes" == get_option("ersrv_enable_time_with_date")){
			$daily_fee_text = __( 'per hour cost', 'easy-reservations' );
		}else{
			$daily_fee_text = __( 'per day cost', 'easy-reservations' );
		}
		/**
		 * This filter runs on the public end on the reservation pages.
		 *
		 * This hook helps in modifying the daily fee text for the amenities cost type.
		 *
		 * @param string $daily_fee_text Amenity daily fee text.
		 * @return string
		 * @since 1.0.0
		 */
		return apply_filters( 'ersrv_amenity_daily_fee_text', $daily_fee_text );
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_get_reservation_item_cost_type_text' ) ) {
	/**
	 * Amenity - daily fee text.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	function ersrv_get_reservation_item_cost_type_text() {
		$text = __( 'per day', 'easy-reservations' );
		/**
		 * This filter runs on the public end on the reservation pages.
		 *
		 * This hook helps in modifying the reservation item fee text.
		 *
		 * @param string $text Amenity daily fee text.
		 * @return string
		 * @since 1.0.0
		 */
		return apply_filters( 'ersrv_reservation_item_cost_type_text', $text );
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_send_reservarion_reminder_emails' ) ) {
	/**
	 * Send reservation reminder emails.
	 *
	 * @param int     $order ID WooCommerce order ID.
	 * @param boolean $force Forcefully send the reminder.
	 * @since 1.0.0
	 */
	function ersrv_send_reservarion_reminder_emails( $order_id, $force = false ) {
		$wc_order                        = wc_get_order( $order_id );
		$line_items                      = $wc_order->get_items();
		$reminder_to_be_sent_before_days = ersrv_get_plugin_settings( 'ersrv_reminder_email_send_before_days' );

		// Skip, if there are no items.
		if ( empty( $line_items ) || ! is_array( $line_items ) ) {
			return;
		}

		// Iterate through the items to check if the reminder email can be sent to the customers.
		foreach ( $line_items as $line_item ) {
			$product_id = $line_item->get_product_id();
			$item_id    = $line_item->get_id();

			// Skip, if this is not a reservation item.
			if ( ! ersrv_product_is_reservation( $product_id ) ) {
				continue;
			}

			// Get the checkin date.
			$checkin_date = wc_get_order_item_meta( $item_id, 'Checkin Date', true );
			$date_today   = gmdate( ersrv_get_php_date_format() );

			// Get the days yet to the reservation.
			$dates_range = ersrv_get_dates_within_2_dates( $date_today, $checkin_date );
			$dates       = array();

			// Iterate through the dates.
			if ( ! empty( $dates_range ) ) {
				foreach ( $dates_range as $date ) {
					$dates[] = $date->format( ersrv_get_php_date_format() );
				}
			}
			$days_difference_count = count( $dates );

			// Skip, if this doesn't match with the admin settings.
			if ( false === $force && $reminder_to_be_sent_before_days !== $days_difference_count ) { // Check against force to apply request from admin.
				continue;
			}

			/**
			 * Send the email now.
			 * This action is fired by the cron.
			 *
			 * This action helps in sending the reminder emails to the customers about their reservation.
			 *
			 * @param object $line_item WooCommerce line item object.
			 * @param int    $order_id WooCommerce order ID.
			 */
			do_action( 'ersrv_send_reservation_reminder_email', $line_item, $order_id );
		}
	}
}
