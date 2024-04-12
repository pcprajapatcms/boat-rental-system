<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.cmsminds.com/
 * @since      1.0.0
 *
 * @package    Easy_Reservations
 * @subpackage Easy_Reservations/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Easy_Reservations
 * @subpackage Easy_Reservations/public
 * @author     cmsMinds <info@cmsminds.com>
 */
class Easy_Reservations_Public {
	/**
	 * The ID of this plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Whether the calendar widget is active or not.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    boolean $is_calendar_widget_active Whether the calendar widget is active or not.
	 */
	private $is_calendar_widget_active;

	/**
	 * Reservation - Custom product type.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string $custom_product_type Reservation - Custom product type.
	 */
	private $custom_product_type;

	/**
	 * My account custom endpoint slug - favourite reservation items.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string $favourite_reservation_items_endpoint_slug Favourite reservations items endpoint slug.
	 */
	private $favourite_reservation_items_endpoint_slug;

	/**
	 * My account custom endpoint label - favourite reservation items.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string $favourite_reservation_items_endpoint_label Favourite reservations items endpoint label.
	 */
	private $favourite_reservation_items_endpoint_label;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		// Check, if the calendar widget is active or not.
		$calendar_widget_base_id         = ersrv_get_calendar_widget_base_id(); // Calendar widget base id.
		$this->is_calendar_widget_active = is_active_widget( false, false, $calendar_widget_base_id );

		// Custom product type.
		$this->custom_product_type = ersrv_get_custom_product_type_slug();

		// Favourite reservation items endpoint slug - woocommerce my account page.
		$this->favourite_reservation_items_endpoint_slug = ersrv_get_account_endpoint_favourite_reservations();

		// Favourite reservation items endpoint label - woocommerce my account page.
		$this->favourite_reservation_items_endpoint_label = ersrv_get_account_endpoint_label_favourite_reservations();
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function ersrv_wp_enqueue_scripts_callback() {
		global $wp_registered_widgets, $post, $wp_query;
		// Active style file based on the active theme.
		$current_theme            = get_option( 'stylesheet' );
		$active_style             = ersrv_get_active_stylesheet( $current_theme );
		$active_style_url         = ( ! empty( $active_style['url'] ) ) ? $active_style['url'] : '';
		$active_style_path        = ( ! empty( $active_style['path'] ) ) ? $active_style['path'] : '';
		$is_search_page           = ( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'ersrv_search_reservations' ) );
		$is_edit_reservation_page = ( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'ersrv_edit_reservation' ) );
		$is_reservation_page      = ersrv_product_is_reservation( get_the_ID() );
		$enqueue_extra_css        = false;
		$is_fav_items_endpoint    = isset( $wp_query->query_vars[ $this->favourite_reservation_items_endpoint_slug ] );
		$is_view_order_endpoint   = isset( $wp_query->query_vars['view-order'] );
		$is_orders_endpoint       = isset( $wp_query->query_vars['orders'] );
		$is_track_order_page      = ( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'woocommerce_order_tracking' ) );

		// Conditions to enqueue the extra css file.
		if (
			is_cart() ||
			is_checkout() ||
			$is_fav_items_endpoint ||
			$is_view_order_endpoint ||
			$is_edit_reservation_page ||
			$is_track_order_page ||
			$is_orders_endpoint
		) {
			$enqueue_extra_css = true;
		}

		/* ---------------------------------------STYLES--------------------------------------- */

		// Enqueue the free font-awesome style.
		wp_enqueue_style(
			$this->plugin_name . '-font-awesome-style',
			ERSRV_PLUGIN_URL . 'public/css/fontawesome/all.min.css',
			array(),
			filemtime( ERSRV_PLUGIN_PATH . 'public/css/fontawesome/all.min.css' )
		);

		// Add the UI style.
		if (
			false !== $this->is_calendar_widget_active ||
			$is_reservation_page ||
			$is_search_page ||
			$is_edit_reservation_page
		) {
			wp_enqueue_style(
				$this->plugin_name . '-jquery-ui-style',
				ERSRV_PLUGIN_URL . 'public/css/ui/jquery-ui.min.css',
				array(),
				filemtime( ERSRV_PLUGIN_PATH . 'public/css/ui/jquery-ui.min.css' )
			);
		}

		// Add the bootstrap css.
		if ( $is_reservation_page || $is_search_page || $is_edit_reservation_page ) {
			wp_enqueue_style(
				$this->plugin_name . '-bootstrap-style',
				ERSRV_PLUGIN_URL . 'public/css/bootstrap/bootstrap.min.css',
				array(),
				filemtime( ERSRV_PLUGIN_PATH . 'public/css/bootstrap/bootstrap.min.css' )
			);
		}

		// Add the bootstrap select css.
		if ( $is_reservation_page || $is_search_page || $is_edit_reservation_page ) {
			wp_enqueue_style(
				$this->plugin_name . '-bootstrap-select-style',
				ERSRV_PLUGIN_URL . 'public/css/bootstrap/bootstrap-select.min.css',
				array(),
				filemtime( ERSRV_PLUGIN_PATH . 'public/css/bootstrap/bootstrap-select.min.css' )
			);
		}

		// Add the bootstrap select css.
		if ( $is_reservation_page || $is_search_page || $is_edit_reservation_page ) {
			if("yes" === get_option("ersrv_enable_time_with_date")){
				wp_enqueue_style(
					$this->plugin_name . '-datetimepicker-style',
					ERSRV_PLUGIN_URL . 'public/js/widget/datetime-picker/jquery.datetimepicker.min.css',
					array(),
					filemtime( ERSRV_PLUGIN_PATH . 'public/js/widget/datetime-picker/jquery.datetimepicker.min.css' )
				);
			}
		}

		// Add the UI style only when the widget is active.
		if ( false !== $this->is_calendar_widget_active ) {
			wp_enqueue_style(
				$this->plugin_name . '-calendar-widget-style',
				ERSRV_PLUGIN_URL . 'public/css/widget/calendar/easy-reservations-calendar-widget.css',
				array(),
				filemtime( ERSRV_PLUGIN_PATH . 'public/css/widget/calendar/easy-reservations-calendar-widget.css' ),
			);
		}

		// Add the plugin core css.
		if (
			$is_reservation_page ||
			$is_search_page ||
			$is_edit_reservation_page
		) {
			if ( ! empty( $active_style_url ) && ! empty( $active_style_path ) ) {
				wp_enqueue_style(
					$this->plugin_name,
					$active_style_url,
					array(),
					filemtime( $active_style_path ),
				);
			}

			// Enqueue the modal public style.
			wp_enqueue_style(
				$this->plugin_name . '-modal',
				ERSRV_PLUGIN_URL . 'public/css/core/easy-reservations-modal.css',
				array(),
				filemtime( ERSRV_PLUGIN_PATH . 'public/css/core/easy-reservations-modal.css' )
			);

			// Enqueue the common public style.
			wp_enqueue_style(
				$this->plugin_name . '-common',
				ERSRV_PLUGIN_URL . 'public/css/core/easy-reservations-common.css',
				array(),
				filemtime( ERSRV_PLUGIN_PATH . 'public/css/core/easy-reservations-common.css' )
			);
		}

		// Check, if the extra css file is to be enqueued.
		if ( $enqueue_extra_css ) {
			// Enqueue the common public style.
			wp_enqueue_style(
				$this->plugin_name . '-extra',
				ERSRV_PLUGIN_URL . 'public/css/core/easy-reservations-extra.css',
				array(),
				filemtime( ERSRV_PLUGIN_PATH . 'public/css/core/easy-reservations-extra.css' )
			);
		}

		/* ---------------------------------------SCRIPTS--------------------------------------- */

		// If it's the single reservation page or the search page.
		if ( $is_reservation_page || $is_search_page || $is_edit_reservation_page ) {
			// Bootstrap bundle script.
			wp_enqueue_script(
				$this->plugin_name . '-bootstrap-bundle-script',
				ERSRV_PLUGIN_URL . 'public/js/bootstrap/bootstrap.bundle.min.js',
				array( 'jquery' ),
				filemtime( ERSRV_PLUGIN_PATH . 'public/js/bootstrap/bootstrap.bundle.min.js' ),
				true
			);

			// Bootstrap select script.
			wp_enqueue_script(
				$this->plugin_name . '-bootstrap-select-script',
				ERSRV_PLUGIN_URL . 'public/js/bootstrap/bootstrap-select.min.js',
				array( 'jquery' ),
				filemtime( ERSRV_PLUGIN_PATH . 'public/js/bootstrap/bootstrap-select.min.js' ),
				true
			);

			if("yes" === get_option("ersrv_enable_time_with_date")){
				// DateTime Picker Script
				wp_enqueue_script(
					$this->plugin_name . '-datetimepicker-lib-script',
					ERSRV_PLUGIN_URL . 'public/js/widget/datetime-picker/jquery.datetimepicker.full.min.js',
					array( 'jquery' ),
					filemtime( ERSRV_PLUGIN_PATH . 'public/js/widget/datetime-picker/jquery.datetimepicker.full.min.js' ),
					true
				);
			}
		}

		// Include the core JS file.
		if (
			$is_reservation_page ||
			$is_search_page ||
			$is_edit_reservation_page ||
			is_checkout() ||
			$is_view_order_endpoint ||
			$is_track_order_page
		) {
			self::ersrv_enqueue_plugin_core_js( $this->plugin_name );
		}

		// Include the lightbox jquery.
		if ( $is_reservation_page ) {
			wp_enqueue_script(
				$this->plugin_name . '-lightbox',
				ERSRV_PLUGIN_URL . 'public/js/lightbox/lightbox.js',
				array( 'jquery' ),
				filemtime( ERSRV_PLUGIN_PATH . 'public/js/lightbox/lightbox.js' ),
				true
			);
		}

		// Add a custom separate JS for edit reservation page.
		if ( $is_edit_reservation_page ) {
			wp_enqueue_script(
				$this->plugin_name . '-edit-reservation',
				ERSRV_PLUGIN_URL . 'public/js/core/easy-reservations-edit-reservation.js',
				array( 'jquery', 'jquery-ui-datepicker' ),
				filemtime( ERSRV_PLUGIN_PATH . 'public/js/core/easy-reservations-edit-reservation.js' ),
				true
			);
			
			// Localize script.
			wp_localize_script(
				$this->plugin_name . '-edit-reservation',
				'ERSRV_Edit_Reservation_Script_Vars',
				array(
					'ajaxurl'                                      => admin_url( 'admin-ajax.php' ),
					'date_format'                                  => ersrv_get_plugin_settings( 'ersrv_datepicker_date_format' ),
					'woo_currency'                                 => get_woocommerce_currency_symbol(),
					'toast_success_heading'                        => __( 'Woohhoooo! Success..', 'easy-reservations' ),
					'toast_error_heading'                          => __( 'Ooops! Error..', 'easy-reservations' ),
					'toast_notice_heading'                         => __( 'Notice.', 'easy-reservations' ),
					'reservation_guests_err_msg'                   => __( 'Please provide the count of guests for the reservation.', 'easy-reservations' ),
					'reservation_only_kids_guests_err_msg'         => __( 'We cannot proceed with only the kids in the reservation.', 'easy-reservations' ),
					'reservation_guests_count_exceeded_err_msg'    => __( 'The guests count is more than the accomodation limit.', 'easy-reservations' ),
					'reservation_checkin_checkout_missing_err_msg' => __( 'Please provide checkin and checkout dates.', 'easy-reservations' ),
					'reservation_checkin_missing_err_msg'          => __( 'Please provide checkin dates.', 'easy-reservations' ),
					'reservation_checkout_missing_err_msg'         => __( 'Please provide checkout dates.', 'easy-reservations' ),
					'reservation_lesser_reservation_days_err_msg'  => __( 'The item can be reserved for a min. of XX days.', 'easy-reservations' ),
					'reservation_greater_reservation_days_err_msg' => __( 'The item can be reserved for a max. of XX days.', 'easy-reservations' ),
					'reservation_item_changes_invalidated'         => __( 'Reservation changes did not validate for XX. Please check the values and try again.', 'easy-reservations' ),
					'cannot_update_reservation_no_change_done'     => __( 'The reservation cannot be updated since there is no change made.', 'easy-reservations' ),
					'customer_payable_cost_difference_message'     => sprintf( __( 'The customer shall pay %4$s%2$s%1$s%3$s%5$s before onboarding.', 'easy-reservations' ), '--', '<span class="ersrv-edit-reservation-cost-difference">', '</span>', '<strong>', '</strong>' ),
					'admin_payable_cost_difference_message'        => sprintf( __( 'The administrator shall refund %4$s%2$s%1$s%3$s%5$s after the reservation is complete.', 'easy-reservations' ), '--', '<span class="ersrv-edit-reservation-cost-difference">', '</span>', '<strong>', '</strong>' ),
					'trim_zeros_from_price'                        => ersrv_get_plugin_settings( 'ersrv_trim_zeros_from_price' ),
					'enable_time_with_date'                        => ersrv_get_plugin_settings( 'ersrv_ersrv_enable_time_with_date' ),
					'reservation_blocked_dates_err_msg_per_item'   => __( 'The dates selected for reserving XX contain the dates that are already reserved. Kindly check the availability on the left hand side and then proceed with the reservation.', 'easy-reservations' ),
					'update_reservation_confirmation_message'      => __( 'Since there are no issues found with your changes, we are proceeding to update your reservation now. This alert is just take your consent because you won\'t be able to edit this reservation another time.', 'easy-reservations' ),
					'datepicker_next_month_button_text'            => __( 'Next', 'easy-reservations' ),
					'datepicker_prev_month_button_text'            => __( 'Prev', 'easy-reservations' ),
				)
			);
		}

		// Add the datepicker and custom script only when the widget is active.
		if ( false !== $this->is_calendar_widget_active ) {
			// Calendar widget public script.
			wp_enqueue_script(
				$this->plugin_name . '-calendar-widget',
				ERSRV_PLUGIN_URL . 'public/js/widget/calendar/easy-reservations-calendar-widget.js',
				array( 'jquery', 'jquery-ui-datepicker' ),
				filemtime( ERSRV_PLUGIN_PATH . 'public/js/widget/calendar/easy-reservations-calendar-widget.js' ),
				true
			);

			// Localize script.
			wp_localize_script(
				$this->plugin_name . '-calendar-widget',
				'ERSRV_Calendar_Widget_Script_Vars',
				array(
					'ajaxurl'                           => admin_url( 'admin-ajax.php' ),
					'start_of_week'                     => get_option( 'start_of_week' ),
					'date_format'                       => ersrv_get_plugin_settings( 'ersrv_datepicker_date_format' ),
					'datepicker_next_month_button_text' => __( 'Next', 'easy-reservations' ),
					'datepicker_prev_month_button_text' => __( 'Prev', 'easy-reservations' ),
				)
			);
		}

		// Dequeue the bootstrap js file.
		if ( 'easy-storefront' === $current_theme || 'new-york-business' === $current_theme  || 'storefront' === $current_theme ) {
			// If it's the search page.
			if ( $is_search_page || $is_reservation_page ) {
				wp_dequeue_script( 'boostrap' );
				wp_dequeue_style( 'boostrap' );
			}
		}




	}

	/**
	 * Enqueue the plugin core JS file.
	 *
	 * @param string $plugin_name Plugin folder name.
	 * @since 1.0.0
	 */
	public static function ersrv_enqueue_plugin_core_js( $plugin_name ) {
		global $wp_registered_widgets, $post, $wp_query;
		$is_search_page           = ( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'ersrv_search_reservations' ) );
		$is_reservation_page      = ersrv_product_is_reservation( get_the_ID() );
		$is_view_order_endpoint   = isset( $wp_query->query_vars['view-order'] );
		$reservation_item_details = ( $is_reservation_page ) ? ersrv_get_item_details( get_the_ID() ) : array();
		$search_reservations_page = ersrv_get_page_id( 'search-reservations' );
		// Custom public script.
		wp_enqueue_script(
			$plugin_name,
			ERSRV_PLUGIN_URL . 'public/js/core/easy-reservations-public.js',
			array( 'jquery', 'jquery-ui-datepicker' ),
			filemtime( ERSRV_PLUGIN_PATH . 'public/js/core/easy-reservations-public.js' ),
			true
		);

		// Driving license file allowed extensions.
		$driving_license_allowed_extensions = ersrv_get_driving_license_allowed_file_types();

		// Localized variables.
		$localized_vars = array(
			'ajaxurl'                                      => admin_url( 'admin-ajax.php' ),
			'is_product'                                   => ( is_product() ) ? 'yes' : 'no',
			'is_checkout'                                  => ( is_checkout() ) ? 'yes' : 'no',
			'is_search_page'                               => ( $is_search_page ) ? 'yes' : 'no',
			'reservation_item_details'                     => $reservation_item_details,
			'woo_currency'                                 => get_woocommerce_currency_symbol(),
			'reservation_guests_err_msg'                   => __( 'Please provide the count of guests for the reservation.', 'easy-reservations' ),
			'reservation_only_kids_guests_err_msg'         => __( 'We cannot proceed with only the kids in the reservation.', 'easy-reservations' ),
			'reservation_guests_count_exceeded_err_msg'    => __( 'The guests count is more than the accomodation limit.', 'easy-reservations' ),
			'reservation_checkin_checkout_missing_err_msg' => __( 'Please provide checkin and checkout dates.', 'easy-reservations' ),
			'reservation_checkin_missing_err_msg'          => __( 'Please provide checkin dates.', 'easy-reservations' ),
			'reservation_checkout_missing_err_msg'         => __( 'Please provide checkout dates.', 'easy-reservations' ),
			'reservation_lesser_reservation_days_err_msg'  => __( 'The item can be reserved for a min. of XX days.', 'easy-reservations' ),
			'reservation_greater_reservation_days_err_msg' => __( 'The item can be reserved for a max. of XX days.', 'easy-reservations' ),
			'reservation_blocked_dates_err_msg'            => __( 'The dates selected for reservation contain the dates that are already reserved. Kindly recheck the availability and proceed with the reservation.', 'easy-reservations' ),
			'search_reservations_page_url'                 => get_permalink( $search_reservations_page ),
			'date_format'                                  => ersrv_get_plugin_settings( 'ersrv_datepicker_date_format' ),
			'toast_success_heading'                        => __( 'Ohhoooo! Success..', 'easy-reservations' ),
			'toast_error_heading'                          => __( 'Ooops! Error..', 'easy-reservations' ),
			'toast_notice_heading'                         => __( 'Notice.', 'easy-reservations' ),
			'invalid_reservation_item_is_error_text'       => __( 'Invalid item ID.', 'easy-reservations' ),
			'reservation_add_to_cart_error_message'        => __( 'There are a few errors that need to be addressed.', 'easy-reservations' ),
			'reservation_item_contact_owner_error_message' => __( 'There is some issue contacting the owner. Please see the errors above and try again.', 'easy-reservations' ),
			'driving_license_allowed_extensions'           => $driving_license_allowed_extensions,
			'driving_license_invalid_file_error'           => sprintf( __( 'Invalid file selected. Allowed extensions are: %1$s', 'easy-reservations' ), implode( ', ', $driving_license_allowed_extensions ) ),
			'cancel_reservation_confirmation_message'      => __( 'Click OK to confirm your cancellation. This action won\'t be undone.', 'easy-reservations' ),
			'checkin_provided_checkout_not'                => __( 'Since you provided the checkin date, checkout date is mandatory.', 'easy-reservations' ),
			'checkout_provided_checkin_not'                => __( 'Since you provided the checkout date, checkin date is mandatory.', 'easy-reservations' ),
			'enable_time_with_date'                        => ersrv_get_plugin_settings( 'ersrv_enable_time_with_date' ),
			'trim_zeros_from_price'                        => ersrv_get_plugin_settings( 'ersrv_trim_zeros_from_price' ),
			'current_theme'                                => get_option( 'stylesheet' ),
			'datepicker_next_month_button_text'            => __( 'Next', 'easy-reservations' ),
			'datepicker_prev_month_button_text'            => __( 'Prev', 'easy-reservations' ),
		);

		/**
		 * This hook fires in public panel.
		 *
		 * This filter helps in modifying the script variables in public.
		 *
		 * @param array $localized_vars Script variables.
		 * @return array
		 * @since 1.0.0
		 */
		$localized_vars = apply_filters( 'ersrv_public_script_vars', $localized_vars );

		// Localize script.
		wp_localize_script( $plugin_name, 'ERSRV_Public_Script_Vars', $localized_vars );
	}

	/**
	 * Do the following when WordPress initiates.
	 * 1. Register custom product type in WooCommerce Products.
	 *
	 * @since    1.0.0
	 */
	public function ersrv_init_callback() {
		// Check if the action is required to download iCalendar invite.
		$action = filter_input( INPUT_GET, 'action', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );
		// Include the product type custom class.
		require ERSRV_PLUGIN_PATH . 'includes/classes/class-wc-product-reservation.php';

		// Add the custom rewrite for my account endpoints.
		add_rewrite_endpoint( $this->favourite_reservation_items_endpoint_slug, EP_ROOT | EP_PAGES );
		$rewrite_fav_items_endpoint = get_option( 'ersrv_rewrite_fav_items_endpoint_permalink' );
		if ( 'yes' !== $rewrite_fav_items_endpoint ) {
			flush_rewrite_rules( false );
			update_option( 'ersrv_rewrite_fav_items_endpoint_permalink', 'yes', false );
		}

		// Register reservation item type taxonomy.
		ersrv_register_reservation_type_taxonomy();

		// If it's the download reservation receipt request.
		if ( ! is_null( $action ) && 'ersrv-download-reservation-receipt' === $action ) {
			$order_id = (int) filter_input( INPUT_GET, 'atts', FILTER_SANITIZE_NUMBER_INT );
			if ( ! is_null( $order_id ) || 0 !== $order_id ) {
				ersrv_download_reservation_receipt_callback( $order_id );
			}
		}

		// Register custom user roles.
		ersrv_add_custom_user_roles();
	}

	/**
	 * Customizations on thank you page.
	 * Send the email to multiple administrators set in plugin settings.
	 *
	 * @param int $order_id WooCommerce Order ID.
	 * @since 1.0.0
	 */
	public function ersrv_woocommerce_thankyou_callback( $order_id ) {
		/**
		 * Generate the download reservation receipt button.
		 * Check if the order has reservation items.
		 */
		$wc_order             = wc_get_order( $order_id );
		$is_reservation_order = ersrv_order_is_reservation( $wc_order );

		// Return the actions if the order is not reservation order.
		if ( ! $is_reservation_order ) {
			return;
		}

		// Update order meta to be a reservation order.
		update_post_meta( $order_id, 'ersrv_reservation_order', 1 );

		// Block the dates after reservation is successfully filed by the customer.
		ersrv_block_dates_after_reservation_thankyou( $wc_order );
	}

	/**
	 * Modify the post query arguments.
	 *
	 * @param array $args Holds the post arguments.
	 * @return array
	 * @since 1.0.0
	 */
	public function ersrv_ersrv_posts_args_callback( $args = array() ) {
		// If the product posts are requested, modify the query to set product type of 'reservation'.
		$post_type = ( ! empty( $args['post_type'] ) ) ? $args['post_type'] : '';
		$page      = (int) filter_input( INPUT_POST, 'page', FILTER_SANITIZE_NUMBER_INT );
		$page      = ( ! empty( $page ) ) ? $page : 1;

		// Search requests.
		$location     = filter_input( INPUT_POST, 'location', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );
		$type         = filter_input( INPUT_POST, 'type', FILTER_SANITIZE_NUMBER_INT );
		$accomodation = (int) filter_input( INPUT_POST, 'accomodation', FILTER_SANITIZE_NUMBER_INT );

		// If the location is set.
		if ( ! empty( $location ) ) {
			$args['meta_query']['relation'] = 'AND';
			$args['meta_query'][]           = array(
				'key'     => '_ersrv_item_location',
				'value'   => $location,
				'compare' => 'LIKE',
			);
		}

		// If the reservation item type is set.
		if ( ! empty( $type ) ) {
			$args['tax_query']['relation'] = 'AND';
			$args['tax_query'][]           = array(
				'taxonomy'         => 'reservation-item-type',
				'field'            => 'term_id',
				'terms'            => $type,
				'include_children' => false,
			);
		}

		// If the accomodation is set.
		if ( ! empty( $accomodation ) ) {
			$args['meta_query']['relation'] = 'AND';
			$args['meta_query'][]           = array(
				'key'     => '_ersrv_accomodation_limit',
				'value'   => $accomodation,
				'compare' => '>=',
				'type'    => 'NUMERIC',
			);
		}

		// If the post type is available.
		if ( ! empty( $post_type ) ) {
			// Set the taxonomy args for woocommerce products.
			if ( 'product' === $post_type ) {
				$args['tax_query'][] = array(
					'taxonomy' => 'product_type',
					'field'    => 'slug',
					'terms'    => $this->custom_product_type,
				);
			} elseif ( 'shop_order' === $post_type ) {
				$args['meta_query'][] = array(
					'key'     => 'ersrv_reservation_order',
					'value'   => '1',
					'compare' => '=',
				);

				// Update the post status.
				$args['post_status'] = array(
					'wc-processing',
					'wc-pending',
				);
			}
		}

		// If the page is available.
		if ( ! empty( $page ) ) {
			$args['paged'] = $page;
		}

		// Remove the relation parameter from the meta query arguments if there is only 1 request.
		if ( ! empty( $args['meta_query'] ) && 2 === count( $args['meta_query'] ) ) {
			unset( $args['meta_query']['relation'] );
		}

		return $args;
	}

	/**
	 * Get the unavailability dates of particular item.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_get_item_unavailable_dates_callback() {
		$action = filter_input( INPUT_POST, 'action', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );

		// Check if action mismatches.
		if ( empty( $action ) || 'get_item_unavailable_dates' !== $action ) {
			echo esc_html( 0 );
			wp_die();
		}

		// Posted data.
		$item_id = (int) filter_input( INPUT_POST, 'item_id', FILTER_SANITIZE_NUMBER_INT );

		// Now that we have item ID, get the unavailability dates.
		$reserved_dates = get_post_meta( $item_id, '_ersrv_reservation_blockout_dates', true );
		$reserved_dates = ( ! empty( $reserved_dates ) && is_array( $reserved_dates ) ) ? $reserved_dates : array();

		// Return the AJAX response.
		$response = array(
			'code'      => 'unavailability-dates-fetched',
			'dates'     => $reserved_dates,
			'item_link' => get_permalink( $item_id ),
		);
		wp_send_json_success( $response );
		wp_die();
	}

	/**
	 * Add custom assets in header.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_wp_head_callback() {
	}

	/**
	 * Change the add to cart button text for reservations on shop page.
	 *
	 * @param string     $button_text Holds the button text.
	 * @param WC_Product $wc_product Holds the product object.
	 * @return string
	 * @since 1.0.0
	 */
	public function ersrv_woocommerce_product_add_to_cart_text_callback( $button_text, $wc_product ) {
		// Return the button text if the product is not of reservation type.
		if ( $this->custom_product_type !== $wc_product->get_type() ) {
			return $button_text;
		}

		return ersrv_get_plugin_settings( 'ersrv_archive_page_add_to_cart_button_text' );
	}

	/**
	 * Alter the related products on reservation product type.
	 *
	 * @param array $related_post_ids Holds the related posts IDs.
	 * @param int   $post_id Holds the current post ID.
	 * @return array
	 * @since 1.0.0
	 */
	public function ersrv_woocommerce_related_products_callback( $related_post_ids, $post_id ) {
		global $product;

		// Return, if it's not product single page.
		if ( ! is_product() ) {
			return $related_post_ids;
		}

		// Return, if the current product is not of reservation type.
		if ( $this->custom_product_type !== $product->get_type() ) {
			return $related_post_ids;
		}

		// Get the reservation type products now.
		$reservation_posts_query = ersrv_get_posts( 'product', 1, -1 );
		$related_post_ids        = $reservation_posts_query->posts;

		// Unset the current reservation item.
		$item_to_exclude_key = array_search( $post_id, $related_post_ids, true );
		if ( false !== $item_to_exclude_key ) {
			unset( $related_post_ids[ $item_to_exclude_key ] );
		}

		return $related_post_ids;
	}

	/**
	 * Override the WooCommerce single product page.
	 *
	 * @param string $template Holds the template path.
	 * @return string
	 * @since 1.0.0
	 */
	public function ersrv_template_include_callback( $template ) {
		// Override the product single page.
		if ( is_product() && ersrv_product_is_reservation( get_the_ID() ) ) {
			$template = ERSRV_PLUGIN_PATH . 'public/templates/woocommerce/single-product.php';
		}

		return $template;
	}

	/**
	 * Search reservations callback.
	 *
	 * @param array $args Holds the shortcode arguments.
	 * @return string
	 * @since 1.0.0
	 */
	public function ersrv_ersrv_search_reservations_callback( $args = array() ) {
		// Return, if it's admin panel.
		if ( is_admin() ) {
			return;
		}

		ob_start();
		require_once ERSRV_PLUGIN_PATH . 'public/templates/shortcodes/search-reservations.php';
		return ob_get_clean();
	}

	/**
	 * Setup the cron to delete the pdf files from the uploads folder.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_ersrv_delete_reservation_pdf_receipts_callback() {
		$wp_upload_dir = wp_upload_dir();
		$attach_path   = $wp_upload_dir['basedir'] . '/wc-logs/';
		$pdfs          = glob( $wp_upload_dir['basedir'] . '/wc-logs/ersrv-reservation-receipt-*.pdf' );

		// Return, if there are no PDFs generated.
		if ( empty( $pdfs ) ) {
			return;
		}

		// Loop in through the files to unlink each of them.
		foreach ( $pdfs as $pdf ) {
			unlink( $pdf );
		}
	}

	/**
	 * Setup the cron to send reservation reminder notifications to the customers.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_ersrv_reservation_reminder_email_notifications_callback() {
		// Send reminder emails to the customers.
		$this->ersrv_send_reminder_emails();
	}

	/**
	 * Hook the receipt option in order listing page on customer's my account.
	 *
	 * @param array    $actions Holds the array of order actions.
	 * @param WC_Order $wc_order Holds the WooCommerce order object.
	 * @return array
	 * @since 1.0.0
	 */
	public function ersrv_woocommerce_my_account_my_orders_actions_callback( $actions, $wc_order ) {
		$order_id             = $wc_order->get_id();
		$is_reservation_order = ersrv_order_is_reservation( $wc_order ); // Check if the order has reservation items.

		// Return the actions if the order is not reservation order.
		if ( ! $is_reservation_order ) {
			return $actions;
		}

		/**
		 * Remove the cancel action.
		 * This is because there is a cancelation functionality for the reservations.
		 */
		if ( array_key_exists( 'cancel', $actions ) ) {
			unset( $actions['cancel'] );
		}

		// Check if the order status is allowed for receipts.
		$display_order_receipt = ersrv_should_display_receipt_button( $order_id );

		// Return the actions if the receipt button should not be displayed.
		if ( false === $display_order_receipt ) {
			return $actions;
		}

		// Check if it's enabled to display on the listing page.
		$display_on_order_listing = ersrv_get_plugin_settings( 'ersrv_enable_receipt_button_my_account_orders_list' );

		// Return, if it's not allowed.
		if ( empty( $display_on_order_listing ) || 'no' === $display_on_order_listing ) {
			return $actions;
		}

		// Add the action.
		$actions['ersrv-reservation-receipt'] = array(
			'url'  => ersrv_download_reservation_receipt_url( $order_id ),
			'name' => ersrv_get_plugin_settings( 'ersrv_easy_reservations_receipt_button_text' ),
		);

		return $actions;
	}

	/**
	 * Add custom action after the order details table.
	 *
	 * @param WC_Order $wc_order Holds the WC order object.
	 * @return void
	 * @since 1.0.0
	 */
	public function ersrv_woocommerce_order_details_after_order_table_callback( $wc_order ) {
		// Return, if this is order received endpoint.
		if ( is_wc_endpoint_url( 'order-received' ) ) {
			return;
		}

		$order_id             = $wc_order->get_id(); // Get the WooCommerce order ID.
		$is_reservation_order = ersrv_order_is_reservation( $wc_order ); // Should the calendar buttons be added.

		// Return, if the order is not reservation.
		if ( ! $is_reservation_order ) {
			return;
		}

		$future_reservations_order_items = array(); // Future reservation items in the order.
		$date_today                      = gmdate( ersrv_get_php_date_format() );

		// Get the items and their checkin dates.
		$line_items = $wc_order->get_items();
		if ( ! empty( $line_items ) && is_array( $line_items ) ) {
			foreach ( $line_items as $line_item ) {
				$line_item_id          = $line_item->get_id();
				$checkin_date          = wc_get_order_item_meta( $line_item_id, 'Checkin Date', true );
				$days_difference_count = ersrv_get_days_count_until_checkin( $checkin_date );

				// Count the item, if there are days more than 0.
				if ( 0 < $days_difference_count ) {
					$future_reservations_order_items[] = $line_item_id;
				}
			}
		}

		// Print the cost difference if it's there, so the customer knows about the payment.
		ersrv_print_updated_reservation_cost_difference( $order_id );

		// Print the reservation custom actions now.
		?>
		<div class="ersrv-reservation-actions-container">
			<h2 class="woocommerce-column__title"><?php esc_html_e( 'Easy Reservations: Actions', 'easy-reservations' ); ?></h2>
			<div class="actions">
				<?php
				if ( ! empty( $future_reservations_order_items ) ) {
					ersrv_print_calendar_buttons( $order_id, $wc_order ); // Print the calendar button.
					ersrv_print_edit_reservation_button( $order_id, $wc_order ); // Print the edit reservation button.
				}

				ersrv_print_receipt_button( $order_id, $wc_order ); // Print the receipt button.
				?>
			</div>
		</div>
		<?php
	}

	/**
	 * Add metabox on the dokan view order page so get the receipt.
	 *
	 * @param WC_Order $wc_order Holds the WooCommerce order object.
	 */
	public function ersrv_dokan_order_detail_after_order_items_callback( $wc_order ) {
		$order_id             = $wc_order->get_id();
		$is_reservation_order = ersrv_order_is_reservation( $wc_order ); // Check if the order has reservation items.

		// Return the actions if the order is not reservation order.
		if ( ! $is_reservation_order ) {
			return;
		}

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
		<div class="dokan-order-receipt">
			<div class="dokan-panel dokan-panel-default">
				<div class="dokan-panel-heading"><strong><?php esc_html_e( 'Receipt', 'easy-reservations' ); ?></strong></div>
				<div class="dokan-panel-body">
				<a href="<?php echo esc_url( $button_url ); ?>" class="button dokan-btn" title="<?php echo esc_html( $button_title ); ?>"><?php echo esc_html( $button_text ); ?></a>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Add custom assets to footer section.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_wp_footer_callback() {
		global $post, $wp_query;
		$is_search_page           = ( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'ersrv_search_reservations' ) );
		$is_reservation_page      = ( ! is_null( $post ) ) ? ersrv_product_is_reservation( $post->ID ) : false;
		$is_view_order_endpoint   = isset( $wp_query->query_vars['view-order'] );
		$is_edit_reservation_page = ( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'ersrv_edit_reservation' ) );
		$is_track_order_page      = ( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'woocommerce_order_tracking' ) );

		// If it's the single reservation page or the search page.
		if ( $is_search_page ) {
			// Include the quick view modal.
			require_once ERSRV_PLUGIN_PATH . 'public/templates/modals/item-quick-view.php';
		}

		// If it's the reservation page.
		if ( $is_reservation_page && is_product() ) {
			// Include the quick view modal.
			require_once ERSRV_PLUGIN_PATH . 'public/templates/modals/contact-owner.php';
		}

		// If it's the view order page.
		if (
			( ! is_shop() ) &&
			(
				$is_search_page ||
				$is_view_order_endpoint ||
				$is_reservation_page ||
				is_checkout() ||
				$is_edit_reservation_page ||
				$is_track_order_page
			)
		) {
			// Include the notification html.
			require_once ERSRV_PLUGIN_PATH . 'public/templates/notifications/notification.php';
		}
	}

	/**
	 * AJAX to mark the item as favourite.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_item_favourite_callback() {
		$action = filter_input( INPUT_POST, 'action', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );

		// Exit, if the action mismatches.
		if ( empty( $action ) || 'item_favourite' !== $action ) {
			echo esc_html( 0 );
			wp_die();
		}

		// Posted data.
		$item_id         = (int) filter_input( INPUT_POST, 'item_id', FILTER_SANITIZE_NUMBER_INT );
		$do_what         = filter_input( INPUT_POST, 'do', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );
		$user_id         = get_current_user_id();
		$favourite_items = get_user_meta( $user_id, 'ersrv_favourite_items', true );
		$favourite_items = ( empty( $favourite_items ) || ! is_array( $favourite_items ) ) ? array() : $favourite_items;

		if ( 'mark_fav' === $do_what ) {
			$myaccount_page_id  = get_option( 'woocommerce_myaccount_page_id' );
			$myaccount_page_url = ( $myaccount_page_id ) ? get_permalink( $myaccount_page_id ) : '';
			$fav_items_endpoint = ( ! empty( $myaccount_page_url ) ) ? $myaccount_page_url . 'favourite-reservable-items/' : '';

			// Create the toast message.
			if ( ! empty( $fav_items_endpoint ) ) {
				/* translators: 1: %s: anchor tag open, 2: %s: anchor tag closed */
				$toast_message = sprintf( __( 'Item has been marked favourite. %1$sView favourites.%2$s', 'easy-reservations' ), '<a href="' . $fav_items_endpoint . '" title="' . __( 'Favourite Items', 'easy-reservations' ) . '">', '</a>' );
			} else {
				$toast_message = __( 'Item has been marked favourite.', 'easy-reservations' );
			}

			// Push in the item now.
			$favourite_items[] = $item_id;
		} elseif ( 'unmark_fav' === $do_what ) {
			// Remove the item from favourite list.
			$item_index = array_search( $item_id, $favourite_items, true );

			if ( false !== $item_index ) {
				unset( $favourite_items[ $item_index ] );
			}

			$toast_message = __( 'Item has been unmarked favourite.', 'easy-reservations' );
		}

		// Update the database.
		update_user_meta( $user_id, 'ersrv_favourite_items', $favourite_items );

		// Send the response.
		wp_send_json_success(
			array(
				'code'          => 'item-favourite-done',
				'toast_message' => $toast_message,
			)
		);
		wp_die();
	}

	/**
	 * Add custom endpoint on custmer's account page for managing favourite reservatin items.
	 *
	 * @param array $endpoints Endpoints array.
	 * @return array
	 * @since 1.0.0
	 */
	public function ersrv_woocommerce_account_menu_items_callback( $endpoints ) {
		// Check if the custom endpoint already exists.
		if ( array_key_exists( $this->favourite_reservation_items_endpoint_slug, $endpoints ) ) {
			return $endpoints;
		}

		// Add the custom endpoint now.
		$endpoints[ $this->favourite_reservation_items_endpoint_slug ] = $this->favourite_reservation_items_endpoint_label;

		return $endpoints;
	}

	/**
	 * Favourite reservation items content on my account.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_woocommerce_account_fav_items_endpoint_endpoint_callback() {
		// Include the file to manage the endpoint content.
		require ERSRV_PLUGIN_PATH . 'public/templates/woocommerce/favourite-reservation-items.php';
	}

	/**
	 * Add query vars for the favourite items listing page.
	 *
	 * @param array $vars Query variables array.
	 * @return array
	 * @since 1.0.0
	 */
	public function ersrv_query_vars_callback( $vars ) {
		$vars[] = $this->favourite_reservation_items_endpoint_slug;

		return $vars;
	}

	/**
	 * AJAX to load more reservation items on search page.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_loadmore_reservation_items_callback() {
		$action = filter_input( INPUT_POST, 'action', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );

		// Exit, if the action mismatches.
		if ( empty( $action ) || 'loadmore_reservation_items' !== $action ) {
			echo esc_html( 0 );
			wp_die();
		}

		// Get the items now.
		$reservation_items_query = ersrv_get_posts( 'product' );
		$reservation_item_ids    = $reservation_items_query->posts;

		// Return the response if there are no items available.
		if ( empty( $reservation_item_ids ) || ! is_array( $reservation_item_ids ) ) {
			wp_send_json_success(
				array(
					'code' => 'no-items-found',
				)
			);
			wp_die();
		}

		// Iterate through the item IDs to generate the HTML.
		$html = '';
		foreach ( $reservation_item_ids as $item_id ) {
			$html .= ersrv_get_reservation_item_block_html( $item_id, 'search-reservations-page' );
		}

		// Send the response.
		wp_send_json_success(
			array(
				'code' => 'items-found',
				'html' => $html,
			)
		);
		wp_die();
	}

	/**
	 * AJAX to create new reservation.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_add_reservation_to_cart_callback() {
		$action = filter_input( INPUT_POST, 'action', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );

		// Exit, if the action mismatches.
		if ( empty( $action ) || 'add_reservation_to_cart' !== $action ) {
			echo esc_html( 0 );
			wp_die();
		}

		// Posted data.
		$item_id            = filter_input( INPUT_POST, 'item_id', FILTER_SANITIZE_NUMBER_INT );
		$checkin_date       = filter_input( INPUT_POST, 'checkin_date', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );
		$checkout_date      = filter_input( INPUT_POST, 'checkout_date', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );
		$adult_count        = filter_input( INPUT_POST, 'adult_count', FILTER_SANITIZE_NUMBER_INT );
		$kid_count          = filter_input( INPUT_POST, 'kid_count', FILTER_SANITIZE_NUMBER_INT );
		$posted_array       = filter_input_array( INPUT_POST );
		$amenities          = ( ! empty( $posted_array['amenities'] ) ) ? $posted_array['amenities'] : array();
		$item_subtotal      = (float) filter_input( INPUT_POST, 'item_subtotal', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );
		$kids_subtotal      = (float) filter_input( INPUT_POST, 'kids_subtotal', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );
		$security_subtotal  = (float) filter_input( INPUT_POST, 'security_subtotal', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );
		$amenities_subtotal = (float) filter_input( INPUT_POST, 'amenities_subtotal', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );
		$item_total         = (float) filter_input( INPUT_POST, 'item_total', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );

		/**
		 * This hook fires before the reservation item is added to the cart.
		 *
		 * This hook helps in adding actions before any reservation item is added to the cart.
		 */
		do_action( 'ersrv_add_reservation_to_cart_before' );

		// Prepare an array of all the posted data.
		$reservation_data = array(
			'item_id'         => $item_id,
			'checkin_date'    => $checkin_date,
			'checkout_date'   => $checkout_date,
			'adult_count'     => $adult_count,
			'adult_subtotal'  => $item_subtotal,
			'kid_count'       => $kid_count,
			'kid_subtotal'    => $kids_subtotal,
			'security_amount' => $security_subtotal,
			'item_total'      => $item_total,
		);

		// Iterate through the amenities array to add them to session.
		if ( ! empty( $amenities ) && is_array( $amenities ) ) {
			$reservation_data['amenities']          = $amenities;
			$reservation_data['amenities_subtotal'] = $amenities_subtotal;
		}

		// Add all the posted data in the session.
		WC()->session->set( 'reservation_data', $reservation_data );

		// Add the reservation item to the cart now.
		WC()->cart->add_to_cart( $item_id, 1 );

		/**
		 * This hook fires after the reservation item is added to the cart.
		 *
		 * This hook helps in adding actions after any reservation item is added to the cart.
		 */
		do_action( 'ersrv_add_reservation_to_cart_after' );

		// Prepare the response.
		$response = array(
			'code'          => 'reservation-added-to-cart',
			/* translators: 1: %s: anchor tag open, 2: %s: anchor tag closed */
			'toast_message' => sprintf( __( 'Reservation has been added to the cart. %1$sView Cart%2$s', 'easy-reservations' ), '<a title="' . __( 'View Cart', 'easy-reservations' ) . '" href="' . wc_get_cart_url() . '">', '</a>' ),
		);
		wp_send_json_success( $response );
		wp_die();
	}

	/**
	 * AJAX request to submit the request for contacting owner.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_submit_contact_owner_request_callback() {
		$action = filter_input( INPUT_POST, 'action', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );

		// Exit, if the action mismatches.
		if ( empty( $action ) || 'submit_contact_owner_request' !== $action ) {
			echo esc_html( 0 );
			wp_die();
		}

		// Posted data.
		$item_id = (int) filter_input( INPUT_POST, 'item_id', FILTER_SANITIZE_NUMBER_INT );

		// Get the item author.
		$item_author_id = (int) get_post_field( 'post_author', $item_id );

		// Get the author object.
		$item_author = get_userdata( $item_author_id );

		// Get the author email.
		$item_author_email = $item_author->data->user_email;

		/**
		 * This hook fires for sending email for reservation item contact requests.
		 *
		 * This hook helps in adding actions during any contact owner request is saved.
		 *
		 * @param string $item_author_email Author email address.
		 * @since 1.0.0
		 */
		do_action( 'ersrv_email_contact_owner_request', $item_author_email );

		// Prepare the response.
		$response = array(
			'code'          => 'contact-owner-request-saved',
			'toast_message' => __( 'Contact request is saved successfully. One of our teammates will get back to you soon.', 'easy-reservations' ),
		);
		wp_send_json_success( $response );
		wp_die();
	}

	/**
	 * Initiate the woocommerce customer session when the user is a non-loggedin user.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_woocommerce_init_callback() {
		// Return, if it's admin.
		if ( is_admin() ) {
			return;
		}

		// Set the session, if there is no session initiated.
		if ( isset( WC()->session ) && ! WC()->session->has_session() ) {
			WC()->session->set_customer_session_cookie( true );
		}
	}

	/**
	 * Add the reservation data to the woocommerce cart item data.
	 *
	 * @param array $cart_item_data WooCommerce cart item data.
	 * @param int   $product_id WooCommerce product ID.
	 * @return array
	 * @since 1.0.0
	 */
	public function ersrv_woocommerce_add_cart_item_data_callback( $cart_item_data, $product_id ) {
		$session_reservation_data = WC()->session->get( 'reservation_data' );
		$session_reservation_item = ( ! empty( $session_reservation_data['item_id'] ) ) ? (int) $session_reservation_data['item_id'] : '';

		// Return, if the session item ID is empty.
		if ( empty( $session_reservation_item ) ) {
			return $cart_item_data;
		}

		// Check if the item ID matches with the product ID that is being added to the cart.
		if ( $session_reservation_item === $product_id ) {
			$cart_item_data['reservation_data'] = $session_reservation_data;
		}

		return $cart_item_data;
	}

	/**
	 * Calculate the cart item subtotal for reservation items.
	 *
	 * @param array $cart_obj Holds the cart contents.
	 * @return void
	 * @since 1.0.0
	 */
	public function ersrv_woocommerce_before_calculate_totals_callback( $cart_obj ) {
		// Iterate through the cart items to set the price.
		foreach ( $cart_obj->get_cart() as $cart_item ) {
			$reservation_data = ( ! empty( $cart_item['reservation_data'] ) ) ? $cart_item['reservation_data'] : array();

			// Skip, if the cart item has this data.
			if ( empty( $reservation_data ) ) {
				continue;
			}

			// Item total cost.
			$reservation_item_total = ( ! empty( $reservation_data['item_total'] ) ) ? (float) $reservation_data['item_total'] : 0;
			$product_id             = $cart_item['product_id'];
			$reservation_data_item  = ( ! empty( $reservation_data['item_id'] ) ) ? (int) $reservation_data['item_id'] : 0;

			if ( ! empty( $reservation_data_item ) && $reservation_data_item === $product_id ) {
				$cart_item['data']->set_price( $reservation_data['item_total'] );
			}
		}
	}

	/**
	 * Add custom data to the cart item data.
	 *
	 * @param array $item_data Holds the item data.
	 * @param array $cart_item_data Holds the cart item data.
	 * @return array
	 * @since 1.0.0
	 */
	public function ersrv_woocommerce_get_item_data_callback( $item_data, $cart_item_data ) {
		// Return, if the reservation data is not set in the cart.
		if ( ! isset( $cart_item_data['reservation_data'] ) || empty( $cart_item_data['reservation_data'] ) ) {
			return $item_data;
		}

		// Add the checkin date to the cart item.
		$item_data[] = array(
			'key'   => __( 'Checkin Date', 'easy-reservations' ),
			'value' => $cart_item_data['reservation_data']['checkin_date'],
		);

		// Add the checkin date to the cart item.
		$item_data[] = array(
			'key'   => __( 'Checkout Date', 'easy-reservations' ),
			'value' => $cart_item_data['reservation_data']['checkout_date'],
		);

		// Add the adult count to the cart item.
		$item_data[] = array(
			'key'   => __( 'Adult Count', 'easy-reservations' ),
			'value' => $cart_item_data['reservation_data']['adult_count'],
		);

		// Add the adult subtotal to the cart item.
		$item_data[] = array(
			'key'   => __( 'Adult Subtotal', 'easy-reservations' ),
			'value' => wc_price( $cart_item_data['reservation_data']['adult_subtotal'] ),
		);

		// Add the kids count to the cart item.
		$item_data[] = array(
			'key'   => __( 'Kids Count', 'easy-reservations' ),
			'value' => $cart_item_data['reservation_data']['kid_count'],
		);

		// Add the adult subtotal to the cart item.
		$item_data[] = array(
			'key'   => __( 'Kids Subtotal', 'easy-reservations' ),
			'value' => wc_price( $cart_item_data['reservation_data']['kid_subtotal'] ),
		);

		// Add the security subtotal to the cart item.
		$item_data[] = array(
			'key'   => __( 'Security Amount', 'easy-reservations' ),
			'value' => wc_price( $cart_item_data['reservation_data']['security_amount'] ),
		);

		// Check if there are amenities.
		if ( ! empty( $cart_item_data['reservation_data']['amenities'] ) && is_array( $cart_item_data['reservation_data']['amenities'] ) ) {
			foreach ( $cart_item_data['reservation_data']['amenities'] as $amenity_data ) {
				$item_data[] = array(
					'key'   => __( 'Amenity', 'easy-reservations' ),
					'value' => $amenity_data['amenity'] . ' - ' . wc_price( $amenity_data['cost'] ),
				);
			}

			// Add the amenities subtotal to the cart item.
			$item_data[] = array(
				'key'   => __( 'Amenities Subtotal', 'easy-reservations' ),
				'value' => wc_price( $cart_item_data['reservation_data']['amenities_subtotal'] ),
			);
		}

		return apply_filters( 'ersrv_reservation_item_data', $item_data, $cart_item_data );
	}

	/**
	 * Save the reservation data from the cart item as order item meta data.
	 *
	 * @param object   $item WooCommerce order item.
	 * @param string   $cart_item_key WooCommerce cart item key.
	 * @param array    $cart_item_data WooCommerce cart item data.
	 * @param WC_Order $wc_order WooCommerce order.
	 * @since 1.0.0
	 */
	public function ersrv_woocommerce_checkout_create_order_line_item_callback( $item, $cart_item_key, $cart_item_data, $wc_order ) {
		// Return, if the reservation data is not set in the cart.
		if ( ! isset( $cart_item_data['reservation_data'] ) || empty( $cart_item_data['reservation_data'] ) ) {
			return;
		}

		// Iterate through the amenities array to add them to session.
		if ( ! empty( $amenities ) && is_array( $amenities ) ) {
			$reservation_data['amenities']          = $amenities;
			$reservation_data['amenities_subtotal'] = $amenities_subtotal;
		}

		// Update the other reservation data to order item meta.
		$item->update_meta_data( 'Checkin Date', $cart_item_data['reservation_data']['checkin_date'] ); // Update the checkin date.
		$item->update_meta_data( 'Checkout Date', $cart_item_data['reservation_data']['checkout_date'] ); // Update the checkout date.
		$item->update_meta_data( 'Adult Count', $cart_item_data['reservation_data']['adult_count'] ); // Update the adult count.
		$item->update_meta_data( 'Adult Subtotal', $cart_item_data['reservation_data']['adult_subtotal'] ); // Update the adult subtotal.
		$item->update_meta_data( 'Kids Count', $cart_item_data['reservation_data']['kid_count'] ); // Update the kids count.
		$item->update_meta_data( 'Kids Subtotal', $cart_item_data['reservation_data']['kid_subtotal'] ); // Update the kids subtotal.
		$item->update_meta_data( 'Security Amount', $cart_item_data['reservation_data']['security_amount'] ); // Update the security subtotal.

		// Check if there are amenities.
		if ( ! empty( $cart_item_data['reservation_data']['amenities'] ) && is_array( $cart_item_data['reservation_data']['amenities'] ) ) {
			$item->update_meta_data( 'Amenities', $cart_item_data['reservation_data']['amenities'] ); // Update the amenities data.
			$item->update_meta_data( 'Amenities Subtotal', $cart_item_data['reservation_data']['amenities_subtotal'] ); // Update the amenities subtotal.
		}
	}

	/**
	 * Send reminder emails to the customer's about their reservation.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_send_reminder_emails() {
		// Get the woocommerce orders.
		$wc_orders_query                 = ersrv_get_posts( 'shop_order', 1, -1 );
		$wc_order_ids                    = $wc_orders_query->posts;
		$reminder_to_be_sent_before_days = ersrv_get_plugin_settings( 'ersrv_reminder_email_send_before_days' );

		// Return, if the setting is not saved.
		if ( 0 === $reminder_to_be_sent_before_days ) {
			return;
		}

		// Return back, if there are no orders available.
		if ( empty( $wc_order_ids ) || ! is_array( $wc_order_ids ) ) {
			return;
		}

		/**
		 * This filter is fired by the cron.
		 *
		 * This filter helps in managing the array of order ids that are considered for sending reservation reminders.
		 *
		 * @param array $wc_order_ids Array of WooCommerce order IDs.
		 * @return array
		 * @since 1.0.0
		 */
		$wc_order_ids = apply_filters( 'ersrv_reservation_reminder_email_order_ids', $wc_order_ids );

		// Iterate through the orders to send the customers the remonder about their reservation.
		foreach ( $wc_order_ids as $order_id ) {
			ersrv_send_reservarion_reminder_emails( $order_id, false );
		}
	}

	/**
	 * AJAX request to fetch the quick view modal content.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_quick_view_item_data_callback() {
		$action = filter_input( INPUT_POST, 'action', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );

		// Exit, if the action mismatches.
		if ( empty( $action ) || 'quick_view_item_data' !== $action ) {
			echo esc_html( 0 );
			wp_die();
		}

		// Posted data.
		$item_id                = filter_input( INPUT_POST, 'item_id', FILTER_SANITIZE_NUMBER_INT );
		$item                   = wc_get_product( $item_id );
		$featured_image_id      = $item->get_image_id();
		$featured_image_src     = ersrv_get_attachment_url_from_attachment_id( $featured_image_id );
		$featured_image_src     = ( empty( $featured_image_src ) ) ? wc_placeholder_img_src() : $featured_image_src;
		$gallery_image_ids      = $item->get_gallery_image_ids();
		$gallery_image_ids      = ( ! empty( $gallery_image_ids ) ) ? array_merge( array( $featured_image_id ), $gallery_image_ids ) : array( $featured_image_id );
		$item_permalink         = get_permalink( $item_id );
		$item_details           = ersrv_get_item_details( $item_id );
		$adult_charge           = ( ! empty( $item_details['adult_charge'] ) ) ? $item_details['adult_charge'] : 0;
		$kid_charge             = ( ! empty( $item_details['kid_charge'] ) ) ? $item_details['kid_charge'] : 0;
		$amenities              = ( ! empty( $item_details['amenities'] ) ) ? $item_details['amenities'] : array();
		$security_amount        = ( ! empty( $item_details['security_amount'] ) ) ? $item_details['security_amount'] : 0;
		$accomodation_limit     = ( ! empty( $item_details['accomodation_limit'] ) ) ? $item_details['accomodation_limit'] : '';
		$min_reservation_period = ( ! empty( $item_details['min_reservation_period'] ) ) ? $item_details['min_reservation_period'] : '';
		$max_reservation_period = ( ! empty( $item_details['max_reservation_period'] ) ) ? $item_details['max_reservation_period'] : '';
		$reserved_dates         = ( ! empty( $item_details['reserved_dates'] ) ) ? $item_details['reserved_dates'] : '';
		$unavailable_weekdays   = ( ! empty( $item_details['unavailable_weekdays'] ) ) ? $item_details['unavailable_weekdays'] : array();

		// Prepare the HTML.
		?>
		<div class="quick-row align-items-center">
			<div class="col-12 col-md-6  col-preview">
				<div class="product-preview">
					<div class="product-preview-main">
						<img src="<?php echo esc_url( $featured_image_src ); ?>" alt="featured-image" class="product-preview-image">
					</div>
					<!-- GALLERY IMAGES -->
					<div id="preview-list" class="product-preview-menu">
						<?php if ( ! empty( $gallery_image_ids ) && is_array( $gallery_image_ids ) ) { ?>
							<?php
							foreach ( $gallery_image_ids as $image_id ) {
								$image_src = ersrv_get_attachment_url_from_attachment_id( $image_id );
								$image_src = ( empty( $image_src ) ) ? wc_placeholder_img_src() : $image_src;
								?>
								<div class="product-preview-thumb">
									<img src="<?php echo esc_url( $image_src ); ?>" alt="gallery-image" class="product-preview-thumb-image">
								</div>
							<?php } ?>
						<?php } ?>
					</div>
				</div>
			</div>
			<div class="col-12 col-md-6  col-product">
				<div class="product-details">
					<h2 class="product-title font-weight-semibold font-size-30"><?php echo wp_kses_post( $item->get_title() ); ?></h2>
					<div class="product-price-meta mb-1">
						<h4 class="font-size-30 price">
							<?php
							echo wp_kses(
								wc_price( $adult_charge ),
								array(
									'span' => array(
										'class' => array(),
									),
								)
							);
							?>
							<span class="font-size-20 price-text"><?php echo esc_html( ersrv_get_reservation_item_cost_type_text() ); ?></span>
						</h4>
					</div>
					<div class="product-details-values mb-2">
						<div class="check-in-out-values d-flex flex-column mb-3 ersrv-quick-view-reservation-item-checkin-checkout">
							<div class="values">
								<div class="row form-row input-daterange">
									<div class="col-6">
										<h4 class="font-size-16"><?php esc_html_e( 'Checkin', 'easy-reservations' ); ?></h4>
										<div><input type="text" id="ersrv-quick-view-item-checkin-date" class="form-control date-control text-left rounded-lg" placeholder="<?php esc_html_e( 'Checkin', 'easy-reservations' ); ?>"></div>
									</div>
									<div class="col-6">
										<h4 class="font-size-16"><?php esc_html_e( 'Checkout', 'easy-reservations' ); ?></h4>
										<div><input type="text" id="ersrv-quick-view-item-checkout-date" class="form-control date-control text-left rounded-lg" placeholder="<?php esc_html_e( 'Checkout', 'easy-reservations' ); ?>"></div>
									</div>
									<label class="ersrv-reservation-error checkin-checkout-dates-error"></label>
									<p class="get-total-hrs_for_quick_view" style="display:none;"></p>
								</div>
							</div>
						</div>
						<div class="accomodation-values d-flex flex-column mb-3 ersrv-quick-view-reservation-item-accomodation">
							<h4 class="font-size-16">
								<?php esc_html_e( 'Guests', 'easy-reservations' ); ?>
								<small class="font-size-10 ml-1">(
									<?php
									/* translators: 1: %s: accomodation limit */
									echo esc_html( sprintf( __( 'Limit: %1$d', 'easy-reservations' ), $accomodation_limit ) );
									?>
									)<span class="required">*</span>
								</small>
							</h4>
							<div class="values">
								<div class="row form-row">
									<div class="col-6">
										<input type="number" id="quick-view-adult-accomodation-count" class="ersrv-accomodation-count form-contol" placeholder="<?php esc_html_e( 'No. of adults', 'easy-reservations' ); ?>" min="1" />
										<label for="quick-view-adult-accomodation-count" class="">
											<?php
											/* translators: 1: %s: span tag open, 2: %s: span tag closed, 3: %s: adult charge */
											echo wp_kses_post( sprintf( __( 'per adult: %1$s%3$s%2$s', 'easy-reservations' ), '<span>', '</span>', wc_price( $adult_charge ) ) );
											?>
										</label>
									</div>
									<div class="col-6">
										<input type="number" id="quick-view-kid-accomodation-count" class="ersrv-accomodation-count form-contol" placeholder="<?php esc_html_e( 'No. of kids', 'easy-reservations' ); ?>" min="0" />
										<label for="quick-view-kid-accomodation-count" class="">
											<?php
											/* translators: 1: %s: span tag open, 2: %s: span tag closed, 3: %s: kid charge */
											echo wp_kses_post( sprintf( __( 'per kid: %1$s%3$s%2$s', 'easy-reservations' ), '<span>', '</span>', wc_price( $kid_charge ) ) );
											?>
										</label>
									</div>
									<label class="ersrv-reservation-error accomodation-error"></label>
								</div>
							</div>
						</div>
						<?php if ( ! empty( $amenities ) && is_array( $amenities ) ) { ?>
							<div class="amenities-values d-flex flex-column mb-3">
								<h4 class="font-size-16"><?php esc_html_e( 'Amenities', 'easy-reservations' ); ?></h4>
								<div class="values ersrv-item-amenities-wrapper">
									<div class="row form-row">
										<?php
										foreach ( $amenities as $amenity_data ) {
											$amenity_title     = ( ! empty( $amenity_data['title'] ) ) ? $amenity_data['title'] : '';
											$amenity_cost      = ( ! empty( $amenity_data['cost'] ) ) ? $amenity_data['cost'] : 0.00;
											$amenity_slug      = ( ! empty( $amenity_title ) ) ? sanitize_title( $amenity_title ) : '';
											$amenity_cost_type = ( ! empty( $amenity_data['cost_type'] ) ) ? $amenity_data['cost_type'] : 'one_time';
											$cost_type_text    = ( 'one_time' === $amenity_cost_type ) ? ersrv_get_amenity_single_fee_text() : ersrv_get_amenity_daily_fee_text();
											?>
											<div class="col-6">
												<div class="custom-control custom-switch ersrv-single-amenity-block mb-2" data-cost_type="<?php echo esc_attr( $amenity_cost_type ); ?>" data-cost="<?php echo esc_attr( $amenity_cost ); ?>" data-amenity="<?php echo esc_attr( $amenity_title ); ?>">
													<input type="checkbox" class="ersrv-quick-view-reservation-single-amenity custom-control-input" id="amenity-<?php echo esc_html( $amenity_slug ); ?>">
													<label class="custom-control-label font-size-15" for="amenity-<?php echo esc_html( $amenity_slug ); ?>">
														<span class="d-block font-lato font-weight-bold color-black pb-2"><?php echo esc_html( $amenity_title ); ?> </span>
														<span>
															<span class="font-lato font-weight-bold color-accent">
																<?php
																echo wp_kses(
																	wc_price( $amenity_cost ),
																	array(
																		'span' => array(
																			'class' => array(),
																		),
																	)
																);
																?>
															</span> | <span class="font-lato font-weight-normal color-black-500"><?php echo esc_html( $cost_type_text ); ?></span>
														</span>
													</label>
												</div>
											</div>
										<?php } ?>
									</div>
								</div>
							</div>
						<?php } ?>
						<div class="summary d-flex flex-column">
							<input type="hidden" id="quick-view-adult-subtotal" value="" />
							<input type="hidden" id="quick-view-kid-subtotal" value="" />
							<input type="hidden" id="quick-view-amenities-subtotal" value="" />
							<input type="hidden" id="quick-view-security-subtotal" value="<?php echo esc_html( $security_amount ); ?>" />
							<label class="ersrv-item-details-security-amount font-Poppins font-size-16 color-black mb-3">
								<?php
								/* translators: 1: %s: quickview reservation security subtotal */
								echo wp_kses_post( sprintf( __( 'Security: %1$s', 'easy-reservations' ), wc_price( $security_amount ) ) );
								?>
							</label>
							<h4 class="font-size-16 font-weight-bold">
								<?php
								/* translators: 1: %s: quickview reservation subtotal */
								echo wp_kses_post( sprintf( __( 'Total: %1$s', 'easy-reservations' ), '<a href="javascript:void(0);" class="text-decoration-none ersrv-split-reservation-cost is-modal"><span class="font-lato font-weight-bold color-accent ersrv-quick-view-item-subtotal ersrv-cost">--</span></a>' ) );
								?>
							</h4>
							<div class="ersrv-reservation-details-item-summary" id="ersrv-split-reservation-cost-content">
								<div class="ersrv-reservation-details-item-summary-wrapper p-3">
									<table class="table table-borderless">
										<tbody>
											<tr class="adults-subtotal">
												<th><?php esc_html_e( 'Adults:', 'easy-reservations' ); ?></th>
												<td><span data-cost="" class="ersrv-cost font-lato font-weight-bold color-accent">--</span></td>
											</tr>
											<tr class="kids-subtotal">
												<th><?php esc_html_e( 'Kids:', 'easy-reservations' ); ?></th>
												<td><span data-cost="" class="ersrv-cost font-lato font-weight-bold color-accent">--</span></td>
											</tr>
											<tr class="amenities-subtotal">
												<th><?php esc_html_e( 'Amenities:', 'easy-reservations' ); ?></th>
												<td><span data-cost="" class="ersrv-cost font-lato font-weight-bold color-accent">--</span></td>
											</tr>
											<tr class="security-subtotal">
												<th><?php esc_html_e( 'Security:', 'easy-reservations' ); ?></th>
												<td><span data-cost="" class="ersrv-cost font-lato font-weight-bold color-accent">--</span></td>
											</tr>
											<tr class="reservation-item-subtotal">
												<th><?php esc_html_e( 'Total:', 'easy-reservations' ); ?></th>
												<td><span data-cost="" class="ersrv-cost font-lato font-weight-bold color-accent">--</span></td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					<div class="product-action-link">
						<input type="hidden" id="quick-view-accomodation-limit" value="<?php echo esc_html( $accomodation_limit ); ?>" />
						<input type="hidden" id="quick-view-min-reservation-period" value="<?php echo esc_html( $min_reservation_period ); ?>" />
						<input type="hidden" id="quick-view-max-reservation-period" value="<?php echo esc_html( $max_reservation_period ); ?>" />
						<input type="hidden" id="quick-view-adult-charge" value="<?php echo esc_html( $adult_charge ); ?>" />
						<input type="hidden" id="quick-view-kid-charge" value="<?php echo esc_html( $kid_charge ); ?>" />
						<input type="hidden" id="quick-view-security-amount" value="<?php echo esc_html( $security_amount ); ?>" />
						<input type="hidden" id="quick-view-item-id" value="<?php echo esc_html( $item_id ); ?>" />
						<button type="button" class="ersrv-add-quick-view-reservation-to-cart product-button add-to-cart btn-block"><?php esc_html_e( 'Add to cart', 'easy-reservations' ); ?></button>
						<a href="<?php echo esc_url( $item_permalink ); ?>" class="readmore-link btn btn-link"><?php esc_html_e( 'View full details', 'easy-reservations' ); ?></a>
					</div>
				</div>
			</div>
		</div>
		<?php
		$html = ob_get_clean();

		// Prepare the response.
		$response = array(
			'code'                 => 'quick-view-modal-fetched',
			'html'                 => $html,
			'reserved_dates'       => $reserved_dates,
			'unavailable_weekdays' => $unavailable_weekdays,
		);
		wp_send_json_success( $response );
		wp_die();
	}

	/**
	 * Remove the cart item quantity input for all the reservation products in cart.
	 *
	 * @param string $quantity_html Holds the cart item quantity input html.
	 * @param string $cart_item_key Holds the cart item key.
	 * @param string $cart_item Holds the cart item.
	 * @return int
	 */
	public function ersrv_woocommerce_cart_item_quantity_callback( $quantity_html, $cart_item_key, $cart_item ) {
		// Return, if the cart item is unavailable.
		if ( ! $cart_item ) {
			return $quantity_html;
		}

		$is_reservation = ( false !== $cart_item && ! empty( $cart_item['reservation_data'] ) ) ? true : false;

		if ( $is_reservation ) {
			// Remove the link to remove cart item for free product.
			$quantity_html = $cart_item['quantity'];
		}

		return $quantity_html;
	}

	/**
	 * Add the driving license upload code on the checkout page.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_woocommerce_after_order_notes_callback() {
		// Get the plugin setting.
		$allowed_to_upload_license = ersrv_get_plugin_settings( 'ersrv_driving_license_validation' );

		// Return, if it's not allowed to upload driving license.
		if ( empty( $allowed_to_upload_license ) || 'no' === $allowed_to_upload_license ) {
			return;
		}

		// Check if there are reservation items in the cart.
		$is_reservation_in_cart = ersrv_is_reservation_in_cart();

		// Return, if there are no reservation items in cart.
		if ( ! $is_reservation_in_cart ) {
			return;
		}

		// Allowed file types.
		$driving_license_allowed_extensions = ersrv_get_driving_license_allowed_file_types();
		$allowed_extensions_string          = ( ! empty( $driving_license_allowed_extensions ) && is_array( $driving_license_allowed_extensions ) ) ? implode( ',', $driving_license_allowed_extensions ) : '';

		// Get the attachment ID, if already uploaded.
		$attachment_id      = WC()->session->get( 'reservation_driving_license_attachment_id' );
		$attachment_url     = ersrv_get_attachment_url_from_attachment_id( $attachment_id );
		$view_license_class = ( ! is_null( $attachment_id ) ) ? '' : 'non-clickable';

		// Get the max upload file size.
		$max_upload_size = wp_max_upload_size();
		$max_upload_size = ( ! $max_upload_size ) ? 0 : $max_upload_size;

		// Prepare the HTML now.
		?>
		<div class="woocommerce-additional-fields__field-wrapper">
			<div class="form-row ersrv-driving-license" id="ersrv_driving_license_field">
				<label for="reservation-driving-license"><?php esc_html_e( 'Driving License', 'easy-reservations' ); ?> <span class="required">*</span></label>
				<span class="ersrv-upload-filesize-notice">
					<?php
					/* translators: 1: %s: max. upload size allowed */
					echo esc_html( sprintf( __( 'Maximum upload file size: %1$s.', 'easy-reservations' ), size_format( $max_upload_size ) ) );
					?>
				</span>
				<div class="ersrv-driving-license-file-upload-wrapper">
					<span class="woocommerce-input-wrapper">
						<input type="file" accept="<?php echo esc_attr( $allowed_extensions_string ); ?>" name="reservation-driving-license" id="reservation-driving-license" />
					</span>
				</div>
				<div class="ersrv-uploaded-checkout-license-file">
				<?php
				if ( ! is_null( $attachment_id ) ) {
					$filename = basename( $attachment_url );
					$filename = ( 43 <= strlen( $filename ) ) ? ersrv_shorten_filename( $filename ) : $filename;
					?>
					<span>
						<?php
						/* translators: 1: %s: filename, 2: %s: anchor tag open, 3: %s: anchor tag closed */
						echo wp_kses_post( sprintf( __( 'Uploaded: %2$s%1$s%3$s', 'easy-reservations' ), $filename, '<a target="_blank" href="' . $attachment_url . '">', '</a>' ) );
						?>
					</span>
					<button type="button" data-file="<?php echo esc_attr( $attachment_id ); ?>" class="remove btn btn-accent"><span class="sr-only"><?php esc_html_e( 'Remove', 'easy-reservations' ); ?></span><span class="fa fa-trash"></span></button>
				<?php } ?>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * AJAX to upload the driving license file on checkout.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_upload_driving_license_checkout_callback() {
		$action = filter_input( INPUT_POST, 'action', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );

		// Exit, if the action mismatches.
		if ( empty( $action ) || 'upload_driving_license_checkout' !== $action ) {
			echo esc_html( 0 );
			wp_die();
		}

		// Upload the file now.
		$driving_license_file_name = ( ! empty( $_FILES['driving_license_file']['name'] ) ) ? $_FILES['driving_license_file']['name'] : '';
		$driving_license_file_temp = ( ! empty( $_FILES['driving_license_file']['tmp_name'] ) ) ? $_FILES['driving_license_file']['tmp_name'] : '';
		$file_data                 = file_get_contents( $driving_license_file_temp );
		$filename                  = basename( $driving_license_file_name );
		$upload_dir                = wp_upload_dir();
		$file_path                 = ( ! empty( $upload_dir['path'] ) ) ? $upload_dir['path'] . '/' . $filename : $upload_dir['basedir'] . '/' . $filename;

		file_put_contents( $file_path, $file_data );

		// Upload it as WP attachment.
		$wp_filetype = wp_check_filetype( $filename, null );
		$attachment  = array(
			'post_mime_type' => $wp_filetype['type'],
			'post_title'     => sanitize_file_name( $filename ),
			'post_content'   => '',
			'post_status'    => 'inherit',
		);
		$attach_id   = wp_insert_attachment( $attachment, $file_path );

		// Update the attachment ID in woocommerce session.
		WC()->session->set( 'reservation_driving_license_attachment_id', $attach_id );

		$attachment_id = WC()->session->get( 'reservation_driving_license_attachment_id' );

		// Return with the on click attribute.
		$attachment_url = ersrv_get_attachment_url_from_attachment_id( $attach_id );
		$filename       = basename( $attachment_url );
		$filename       = ( 43 <= strlen( $filename ) ) ? ersrv_shorten_filename( $filename ) : $filename;

		// View license html.
		ob_start();
		?>
		<span>
			<?php
			/* translators: 1: %s: filename, 2: %s: anchor tag open, 3: %s: anchor tag closed */
			echo wp_kses_post( sprintf( __( 'Uploaded: %2$s%1$s%3$s', 'easy-reservations' ), $filename, '<a target="_blank" href="' . $attachment_url . '">', '</a>' ) );
			?>
		</span>
		<button type="button" data-file="<?php echo esc_attr( $attachment_id ); ?>" class="remove btn btn-accent"><span class="sr-only"><?php esc_html_e( 'Remove', 'easy-reservations' ); ?></span><span class="fa fa-trash"></span></button>
		<?php
		$view_license_html = ob_get_clean();

		// Prepare the response.
		$response = array(
			'code'              => 'driving-license-uploaded',
			'view_license_html' => $view_license_html,
			'toast_message'     => __( 'Driving license is uploaded successfully. Place order to get this attached with your order.', 'easy-reservations' ),
		);
		wp_send_json_success( $response );
		wp_die();
	}

	/**
	 * AJAX to remove the driving license file on checkout.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_remove_uploaded_driving_license_callback() {
		$action = filter_input( INPUT_POST, 'action', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );

		// Exit, if the action mismatches.
		if ( empty( $action ) || 'remove_uploaded_driving_license' !== $action ) {
			echo esc_html( 0 );
			wp_die();
		}

		// Posted data.
		$file_id = filter_input( INPUT_POST, 'file_id', FILTER_SANITIZE_NUMBER_INT );

		// Delete the attachment file.
		wp_delete_attachment( $file_id, true );

		// Unset the session as well.
		$attachment_id = WC()->session->__unset( 'reservation_driving_license_attachment_id' );

		// Return the response.
		$response = array(
			'code'          => 'driving-license-removed',
			'toast_message' => __( 'Driving license is deleted successfully.', 'easy-reservations' ),
		);
		wp_send_json_success( $response );
		wp_die();
	}

	/**
	 * Throw checkout error in the case driving license is not uploaded.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_woocommerce_checkout_process_callback() {
		// Get the plugin setting.
		$allowed_to_upload_license = ersrv_get_plugin_settings( 'ersrv_driving_license_validation' );

		// Check if there are reservation items in the cart.
		$is_reservation_in_cart = ersrv_is_reservation_in_cart();

		// Return if there are no reservation items in the cart.
		if ( false === $is_reservation_in_cart ) {
			return;
		}

		// Return error if driving license is not uploaded.
		if ( ! empty( $allowed_to_upload_license ) && 'yes' === $allowed_to_upload_license ) {
			// Get the attachment ID, if already uploaded.
			$attachment_id = WC()->session->get( 'reservation_driving_license_attachment_id' );

			// Throw the error if the attachment is either null or empty.
			if ( empty( $attachment_id ) || is_null( $attachment_id ) ) {
				/* translators: 1: %s: anchor tag open, 2: %s: anchor tag closed */
				$error_message = sprintf( __( 'Since you\'re doing a reservation, we require you to upload a valid driving license. Click %1$shere%2$s to upload.', 'easy-reservations' ), '<a class="scroll-to-driving-license" href="#">', '</a>' );
				/**
				 * This filter fires on checkout page.
				 *
				 * This filter helps in modifying the checkout error that is thrown in case the driving license file is not provided.
				 *
				 * @param string $error_message Error message.
				 * @return string
				 * @since 1.0.0
				 */
				$error_message = apply_filters( 'ersrv_driving_license_validation_checkout_error', $error_message );

				// Shoot the error now.
				wc_add_notice( $error_message, 'error' );
			}
		}

		// Validate the checkin and checkout dates of the reservation items.
		$cart_items = WC()->cart->get_cart();
		if ( ! empty( $cart_items ) && is_array( $cart_items ) ) {
			// Iterate through the cart items.
			foreach ( $cart_items as $cart_item ) {
				$reservation_data = ( ! empty( $cart_item['reservation_data'] ) ) ? $cart_item['reservation_data'] : false;

				// Skip, if there is non-reservation product in te cart.
				if ( false === $reservation_data ) {
					continue;
				}

				// Get the checkin and checkout dates.
				$checkin_date  = ( ! empty( $reservation_data['checkin_date'] ) ) ? $reservation_data['checkin_date'] : false;
				$checkout_date = ( ! empty( $reservation_data['checkout_date'] ) ) ? $reservation_data['checkout_date'] : false;
				$item_id       = ( ! empty( $reservation_data['item_id'] ) ) ? $reservation_data['item_id'] : false;

				// Skip, if either of the date is unavailable.
				if ( false === $item_id || false === $checkin_date || false === $checkout_date ) {
					continue;
				}

				// Get the cart reserved dates.
				$requesting_reservation_dates_obj = ersrv_get_dates_within_2_dates( $checkin_date, $checkout_date );
				$requesting_reservation_dates_arr = array();

				if ( ! empty( $requesting_reservation_dates_obj ) ) {
					foreach ( $requesting_reservation_dates_obj as $date ) {
						$requesting_reservation_dates_arr[] = $date->format( ersrv_get_php_date_format() );
					}
				}

				// Get the item reserved dates.
				$item_reserved_dates_arr = get_post_meta( $item_id, '_ersrv_reservation_blockout_dates', true );
				$item_reserved_dates     = ( ! empty( $item_reserved_dates_arr ) && is_array( $item_reserved_dates_arr ) ) ? array_column( $item_reserved_dates_arr, 'date' ) : array();

				// Get the intersecting dates.
				$intersecting_dates = array_intersect( $item_reserved_dates, $requesting_reservation_dates_arr );

				// Throw error if the dates match.
				if ( ! empty( $intersecting_dates ) ) {
					/* translators: 1: %s: item title, 2: %s: strong tag open, 3: %s: strong tag closed, 4: %s: intersecting dates */
					$error_message = sprintf( __( 'You cannot proceed with the reservation of %2$s%1$s%3$s as the dates %2$s%4$s%3$s are already reserved.', 'easy-reservations' ), get_the_title( $item_id ), '<strong>', '</strong>', implode( ', ', $intersecting_dates ) );
					/**
					 * This filter fires on checkout page.
					 *
					 * This filter helps in modifying the checkout error that is thrown in case the reservation dates mismatch.
					 *
					 * @param string $error_message Error message.
					 * @return string
					 * @since 1.0.0
					 */
					$error_message = apply_filters( 'ersrv_reservation_dates_mismatch_validation_checkout_error', $error_message );

					// Shoot the error now.
					wc_add_notice( $error_message, 'error' );
				} else {
					// Check if there are reservation items of past date.
					$today = gmdate( ersrv_get_php_date_format() );

					if ( strtotime( $checkin_date ) < time() ) {
						/* translators: 1: %s: item title, 2: %s: strong tag open, 3: %s: strong tag closed, 4: %s: checkin-checkout dates */
						$error_message = sprintf( __( 'Selected dates, %2$s%4$s%3$s for the item, %2$s%1$s%3$s are not available anymore. Please select another dates.', 'easy-reservations' ), get_the_title( $item_id ), '<strong>', '</strong>', "{$checkin_date} - {$checkout_date}" );
						/**
						 * This filter fires on checkout page.
						 *
						 * This filter helps in modifying the checkout error that is thrown in case the reservation dates have already passed.
						 *
						 * @param string $error_message Error message.
						 * @return string
						 * @since 1.0.0
						 */
						$error_message = apply_filters( 'ersrv_past_reservation_dates_validation_checkout_error', $error_message );

						// Shoot the error now.
						wc_add_notice( $error_message, 'error' );
					}
				}
			}
		}
	}

	/**
	 * Update the driving license attachment ID to order meta.
	 *
	 * @param int $order_id WooCommerce order ID.
	 * @since 1.0.0
	 */
	public function ersrv_woocommerce_checkout_update_order_meta_callback( $order_id ) {
		// Get the attachment ID.
		$attachment_id = WC()->session->get( 'reservation_driving_license_attachment_id' );

		// Update the order meta if the attachment ID is available.
		if ( ! empty( $attachment_id ) ) {
			update_post_meta( $order_id, 'reservation_driving_license_attachment_id', sanitize_text_field( $attachment_id ) );

			// Unset the session.
			WC()->session->__unset( 'reservation_driving_license_attachment_id' );
		}
	}

	/**
	 * Add a cancellation button after every reservation item in order.
	 *
	 * @param int      $item_id WooCommerce order item ID.
	 * @param object   $item WooCommerce order item.
	 * @param WC_Order $wc_order WooCommerce order.
	 * @param boolean  $plain_text Whether a plain text is requested.
	 * @since 1.0.0
	 */
	public function ersrv_woocommerce_order_item_meta_end_callback( $item_id, $item, $wc_order, $plain_text ) {
		// Return, if this is order received endpoint.
		if ( is_wc_endpoint_url( 'order-received' ) ) {
			return;
		}

		// Return, if this is called from email.
		if ( false !== $plain_text ) {
			return;
		}

		// See if cancellation is enabled.
		$cancellation_enabled = ersrv_get_plugin_settings( 'ersrv_enable_reservation_cancellation' );

		// Return, if the cancellation is not enabled.
		if ( ! empty( $cancellation_enabled ) && 'no' === $cancellation_enabled ) {
			return;
		}

		// Get the product ID.
		$product_id = $item->get_product_id();

		// If this product is a reservation product.
		$is_reservation_product = ersrv_product_is_reservation( $product_id );

		// Return, if the item is not a reservation product.
		if ( ! $is_reservation_product ) {
			return;
		}

		// Check if the reservation can be cancelled.
		$can_cancel = ersrv_reservation_eligible_for_cancellation( $item_id );

		// Return the actions if the order cannot be cancelled.
		if ( false === $can_cancel ) {
			return;
		}

		// Print the reservation order cancellation button.
		ersrv_print_reservation_cancel_button( $item_id, $wc_order->get_id() );
	}

	/**
	 * AJAX to raise cancellation request for the reservation.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_request_reservation_cancel_callback() {
		$action = filter_input( INPUT_POST, 'action', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );

		// Exit, if the action mismatches.
		if ( empty( $action ) || 'request_reservation_cancel' !== $action ) {
			echo esc_html( 0 );
			wp_die();
		}

		// Posted data.
		$item_id  = (int) filter_input( INPUT_POST, 'item_id', FILTER_SANITIZE_NUMBER_INT );
		$order_id = (int) filter_input( INPUT_POST, 'order_id', FILTER_SANITIZE_NUMBER_INT );

		// Update item meta data.
		wc_update_order_item_meta( $item_id, 'ersrv_cancellation_request', 1 );
		wc_update_order_item_meta( $item_id, 'ersrv_cancellation_request_time', time() );
		wc_update_order_item_meta( $item_id, 'ersrv_cancellation_request_status', 'pending' );

		/**
		 * This hook fires on the my account view order page.
		 *
		 * This hooks helps in firing the email to the administrator when there is any cancellation request from any customer.
		 *
		 * @param int $item_id WooCommerce order item ID.
		 * @param int $order_id WooCommerce order ID.
		 * @since 1.0.0
		 */
		do_action( 'ersrv_email_after_reservation_cancellation_request', $item_id, $order_id );

		// Prepare the response.
		$response = array(
			'code'          => 'cancellation-request-saved',
			'toast_message' => __( 'Cancellation request for this reservation has been saved successfully. You will receive an email when admin accepts/rejects the cancellation.', 'easy-reservations' ),
		);
		wp_send_json_success( $response );
		wp_die();
	}

	/**
	 * Edit reservation callback.
	 *
	 * @param array $args Holds the shortcode arguments.
	 * @return string
	 * @since 1.0.0
	 */
	public function ersrv_ersrv_edit_reservation_callback( $args = array() ) {
		// Return, if it's admin panel.
		if ( is_admin() ) {
			return;
		}

		ob_start();
		require_once ERSRV_PLUGIN_PATH . 'public/templates/shortcodes/edit-reservation.php';
		return ob_get_clean();
	}

	/**
	 * Add custom field to billing fields on checkout page.
	 *
	 * @param array $fields Billing fields.
	 * @return array
	 * @since 1.0.0
	 */
	public function ersrv_woocommerce_billing_fields_callback( $fields ) {
		$timezones = array( '' => __( 'Select timezone', 'easy-reservations' ) );

		// Iterate through the timezone identifiers list.
		foreach ( timezone_identifiers_list() as $timezone_label ) {
			$timezones[ $timezone_label ] = $timezone_label;
		}

		// Add a custom field for customer timezone.
		$fields['billing_timezone'] = array(
			'type'     => 'select',
			'label'    => __( 'Timezone', 'easy-reservations' ),
			'class'    => array( 'billing-timezone' ),
			'options'  => $timezones,
			'required' => true,
			'priority' => 85,
		);

		return $fields;
	}

	/**
	 * Add custom class to body tag on edit reservation page.
	 *
	 * @param array $classes Classes array.
	 * @return array
	 */
	public function ersrv_body_class_callback( $classes ) {
		global $post;
		$is_edit_reservation_page = ( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'ersrv_edit_reservation' ) );
		$is_search_page           = ( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'ersrv_search_reservations' ) );
		$is_reservation_page      = ( ! is_null( $post ) ) ? ersrv_product_is_reservation( $post->ID ) : false;

		// If it's the edit reservation page.
		if ( $is_edit_reservation_page ) {
			$classes = array_merge( $classes, array( 'ersrv-edit-reservation-template', 'ersrv-reservation-template' ) );

			// Remove the no-sidebar class.
			$no_sidebar_class_index = array_search( 'no-sidebar', $classes, true );
			if ( false !== $no_sidebar_class_index ) {
				unset( $classes[ $no_sidebar_class_index ] );
			}
		}

		// If it's the search reservation page.
		if ( $is_search_page ) {
			$classes = array_merge( $classes, array( 'ersrv-search-reservations-template', 'ersrv-reservation-template' ) );

			// Remove the no-sidebar class.
			$no_sidebar_class_index = array_search( 'no-sidebar', $classes, true );
			if ( false !== $no_sidebar_class_index ) {
				unset( $classes[ $no_sidebar_class_index ] );
			}
		}

		// If it's the reservation details page.
		if ( $is_reservation_page ) {
			$classes = array_merge( $classes, array( 'ersrv-single-reservation-template', 'ersrv-reservation-template' ) );

			// Remove the no-sidebar class.
			$no_sidebar_class_index = array_search( 'no-sidebar', $classes, true );
			if ( false !== $no_sidebar_class_index ) {
				unset( $classes[ $no_sidebar_class_index ] );
			}
		}

		// Rearrange the class indexes.
		$classes = array_values( $classes );

		return $classes;
	}

	/**
	 * Add custom text on edit reservation edit page.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_ersrv_edit_reservation_after_main_title_callback() {
		// Header message.
		$header_message = __( 'Make your changes below and validate them before updating the reservation.', 'easy-reservations' );
		/**
		 * This hook executes on the edit reservation page.
		 *
		 * This hook helps in modifying the tagline that appears after the main title.
		 *
		 * @param string $header_message Header message.
		 * @return string
		 * @since 1.0.0
		 */
		$header_message = apply_filters( 'ersrv_edit_reservation_tagline_after_main_title', $header_message );

		// Header message class.
		$header_message_class = 'text-center font-lato font-weight-bold font-size-16 color-black mb-3';
		/**
		 * This hook executes on the edit reservation page.
		 *
		 * This hook helps in modifying the tagline tag class that appears after the main title.
		 *
		 * @param string $header_message_class Header message tag class attribute.
		 * @return string
		 * @since 1.0.0
		 */
		$header_message_class = apply_filters( 'ersrv_edit_reservation_tagline_class_after_main_title', $header_message_class );
		?>
		<p class="<?php echo esc_attr( $header_message_class ); ?>"><?php echo wp_kses_post( $header_message ); ?></p>
		<?php
	}

	/**
	 * Intiate the datepicker on the checkin and checkout fields on edit reservation page.
	 */
	public function ersrv_edit_reservation_initiate_datepicker_callback() {
		$action = filter_input( INPUT_POST, 'action', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );

		// Check if action mismatches.
		if ( empty( $action ) || 'edit_reservation_initiate_datepicker' !== $action ) {
			echo esc_html( 0 );
			wp_die();
		}

		// Posted data.
		$product_id    = (int) filter_input( INPUT_POST, 'product_id', FILTER_SANITIZE_NUMBER_INT );
		$checkin_date  = filter_input( INPUT_POST, 'checkin_date', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );
		$checkout_date = filter_input( INPUT_POST, 'checkout_date', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );

		// Get the reserved dates.
		$reserved_dates = get_post_meta( $product_id, '_ersrv_reservation_blockout_dates', true );
		$reserved_dates = ( ! empty( $reserved_dates ) && is_array( $reserved_dates ) ) ? $reserved_dates : array();

		// Return back the response with no reserved dates.
		if ( empty( $reserved_dates ) || ! is_array( $reserved_dates ) ) {
			$response = array(
				'code'           => 'datepicker-initiated',
				'reserved_dates' => array(),
			);
			wp_send_json_success( $response );
			wp_die();
		}

		// Get the dates between the checkin and checkout dates.
		$order_reserved_dates_obj = ersrv_get_dates_within_2_dates( $checkin_date, $checkout_date );
		$order_reserved_dates     = array();
		if ( ! empty( $order_reserved_dates_obj ) ) {
			foreach ( $order_reserved_dates_obj as $date ) {
				$order_reserved_dates[] = $date->format( ersrv_get_php_date_format() );
			}
		}

		// Make the reserved dates from associative array to indexed array to remove the order reserved dates.
		$reserved_dates_col_array = array_column( $reserved_dates, 'date' );

		// Remove this order dates from the total reserved dates.
		foreach ( $order_reserved_dates as $order_reserved_date ) {
			$reserved_date_key = array_search( $order_reserved_date, $reserved_dates_col_array, true );
			unset( $reserved_dates[ $reserved_date_key ] );
		}

		// If there are reserved dates still, reset their indexes.
		if ( ! empty( $reserved_dates ) ) {
			$reserved_dates = array_values( $reserved_dates );
		}

		// Unavailable weekdays.
		$unavailable_weekdays = get_post_meta( $product_id, '_ersrv_item_unavailable_weekdays', true );
		$unavailable_weekdays = ( ! empty( $unavailable_weekdays ) && is_array( $unavailable_weekdays ) ) ? $unavailable_weekdays : array();

		// Return the AJAX response.
		$response = array(
			'code'                 => 'datepicker-initiated',
			'reserved_dates'       => $reserved_dates,
			'order_reserved_dates' => $order_reserved_dates,
			'unavailable_weekdays' => $unavailable_weekdays,
		);
		wp_send_json_success( $response );
		wp_die();
	}

	/**
	 * Update the reservation.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_update_reservation_callback() {
		$action = filter_input( INPUT_POST, 'action', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );

		// Check if action mismatches.
		if ( empty( $action ) || 'update_reservation' !== $action ) {
			echo esc_html( 0 );
			wp_die();
		}

		// Posted data.
		$order_id            = (int) filter_input( INPUT_POST, 'order_id', FILTER_SANITIZE_NUMBER_INT );
		$cost_difference     = (float) filter_input( INPUT_POST, 'cost_difference', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );
		$order_total         = (float) filter_input( INPUT_POST, 'order_total', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );
		$cost_difference_key = filter_input( INPUT_POST, 'cost_difference_key', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );
		$posted_array        = filter_input_array( INPUT_POST );
		$items_data          = ( ! empty( $posted_array['items_data'] ) ) ? $posted_array['items_data'] : array();

		// Update the order meta.
		update_post_meta( $order_id, 'ersrv_reservation_update', 1 );
		update_post_meta( $order_id, 'ersrv_cost_difference', $cost_difference );
		update_post_meta( $order_id, 'ersrv_cost_difference_key', $cost_difference_key );
		update_post_meta( $order_id, '_order_total', $order_total );

		// If there are items, update the item meta.
		if ( ! empty( $items_data ) && is_array( $items_data ) ) {
			// Iterate through the items.
			foreach ( $items_data as $item_data ) {
				$item_id = ( ! empty( $item_data['item_id'] ) ) ? $item_data['item_id'] : '';

				// Skip, if the item id is unavailable.
				if ( empty( $item_id ) ) {
					continue;
				}

				// Get the other data.
				$item_total         = ( ! empty( $item_data['item_total'] ) ) ? $item_data['item_total'] : 0;
				$adult_subtotal     = ( ! empty( $item_data['adult_subtotal'] ) ) ? $item_data['adult_subtotal'] : 0;
				$kids_subtotal      = ( ! empty( $item_data['kids_subtotal'] ) ) ? $item_data['kids_subtotal'] : 0;
				$amenities_subtotal = ( ! empty( $item_data['amenities_subtotal'] ) ) ? $item_data['amenities_subtotal'] : 0;
				$security_subtotal  = ( ! empty( $item_data['security_subtotal'] ) ) ? $item_data['security_subtotal'] : 0;
				$checkin            = ( ! empty( $item_data['checkin'] ) ) ? $item_data['checkin'] : '';
				$checkout           = ( ! empty( $item_data['checkout'] ) ) ? $item_data['checkout'] : '';
				$adult_count        = ( ! empty( $item_data['adult_count'] ) ) ? $item_data['adult_count'] : 0;
				$kids_count         = ( ! empty( $item_data['kids_count'] ) ) ? $item_data['kids_count'] : 0;
				$amenities          = ( ! empty( $item_data['amenities'] ) ) ? $item_data['amenities'] : array();

				// Update all the data now.
				wc_update_order_item_meta( $item_id, '_line_subtotal', $item_total );
				wc_update_order_item_meta( $item_id, '_line_total', $item_total );
				wc_update_order_item_meta( $item_id, 'Checkin Date', $checkin );
				wc_update_order_item_meta( $item_id, 'Checkout Date', $checkout );
				wc_update_order_item_meta( $item_id, 'Adult Count', $adult_count );
				wc_update_order_item_meta( $item_id, 'Kids Count', $kids_count );
				wc_update_order_item_meta( $item_id, 'Adult Subtotal', $adult_subtotal );
				wc_update_order_item_meta( $item_id, 'Kids Subtotal', $kids_subtotal );
				wc_update_order_item_meta( $item_id, 'Amenities', $amenities );
				wc_update_order_item_meta( $item_id, 'Amenities Subtotal', $amenities_subtotal );
				wc_update_order_item_meta( $item_id, 'Security Amount', $security_subtotal );
			}
		}

		// WC Order.
		$wc_order = wc_get_order( $order_id );

		/**
		 * This hook runs during the AJAX call for updating the reservation order.
		 *
		 * This action executes after the reservation is successfully updated.
		 *
		 * @param int      $order_id WooCommerce order ID.
		 * @param WC_Order $wc_order WooCommerce order object.
		 * @since 1.0.0
		 */
		do_action( 'ersrv_update_reservation', $order_id, $wc_order );

		// Return the AJAX response.
		$response = array(
			'code'            => 'reservation-updated',
			'view_order_link' => $wc_order->get_view_order_url(),
			'toast_message'   => __( 'Your reservation has been updated successfully. Redirecting to the order details now.', 'easy-reservations' ),
		);
		wp_send_json_success( $response );
		wp_die();
	}

	/**
	 * Whether the extra zeros should be removed from price.
	 *
	 * @param boolean $trim_zeros Remove zeros.
	 * @return boolean
	 * @since 1.0.0
	 */
	public function ersrv_woocommerce_price_trim_zeros_callback( $trim_zeros ) {
		$trim_extra_zeros = ersrv_get_plugin_settings( 'ersrv_trim_zeros_from_price' );
		$trim_zeros       = ( ! empty( $trim_extra_zeros ) && 'no' === $trim_extra_zeros ) ? false : true;

		return $trim_zeros;
	}

	/**
	 * AJAX to submit the search and return matching reservations.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_search_reservations_callback() {
		$action = filter_input( INPUT_POST, 'action', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );

		// Check if action mismatches.
		if ( empty( $action ) || 'search_reservations' !== $action ) {
			echo esc_html( 0 );
			wp_die();
		}

		// Posted data.
		$search_performed = filter_input( INPUT_POST, 'search_performed', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );

		// Get the reservation items.
		$reservation_posts_query    = ersrv_get_posts( 'product' );
		$reservation_post_ids       = $reservation_posts_query->posts;
		$reservation_post_ids_found = $reservation_posts_query->found_posts;

		// Return the response, if the reservation posts are not found.
		if ( empty( $reservation_post_ids ) || ! is_array( $reservation_post_ids ) ) {
			$response = array(
				'code'        => 'reservation-posts-not-found',
				'html'        => ersrv_no_reservation_item_found_html( true ),
				'items_count' => __( '0 items', 'easy-reservations' ),
			);
			wp_send_json_success( $response );
			wp_die();
		}

		// Reservation post IDs that qualify to the.
		$final_reservation_ids = $reservation_post_ids;

		// Check through the requested checkin and checkout dates.
		$posted_array           = filter_input_array( INPUT_POST );
		$checkin_checkout_dates = ( ! empty( $posted_array['checkin_checkout_dates'] ) ) ? $posted_array['checkin_checkout_dates'] : array();
		$reservation_weekdays   = ( ! empty( $posted_array['reservation_weekdays'] ) ) ? $posted_array['reservation_weekdays'] : array();

		if ( ! empty( $checkin_checkout_dates ) && is_array( $checkin_checkout_dates ) ) {
			$final_reservation_ids = array();
			// Iterate through the found items to check if the requested checkin and checkout dates don't overlap.
			foreach ( $reservation_post_ids as $reservation_post_id ) {
				$item_reserved_dates  = get_post_meta( $reservation_post_id, '_ersrv_reservation_blockout_dates', true );
				$unavailable_weekdays = get_post_meta( $reservation_post_id, '_ersrv_item_unavailable_weekdays', true );

				// Check for no reserved date and all available weekdays.
				$no_reserved_dates = ( empty( $item_reserved_dates ) || ! is_array( $item_reserved_dates ) );
				$available_allweek = ( empty( $unavailable_weekdays ) || ! is_array( $unavailable_weekdays ) );

				// If there is no reserved date, this item qualifies to the search result.
				if ( $no_reserved_dates && $available_allweek ) {
					$final_reservation_ids[] = $reservation_post_id;
					continue;
				}

				// Final reserved dates.
				$reserved_dates = array();

				// Get the reserved dates in an array.
				foreach ( $item_reserved_dates as $item_reserved_date ) {
					$reserved_date = ( ! empty( $item_reserved_date['date'] ) ) ? $item_reserved_date['date'] : '';

					// Skip, if the date is unavailable.
					if ( empty( $reserved_date ) ) {
						continue;
					}

					$reserved_dates[] = $reserved_date;
				}

				// Find the intersecting dates between the requested dates and the already reserved ones.
				$intersecting_dates       = array_intersect( $checkin_checkout_dates, $reserved_dates );
				$intersecting_weekdays    = array_intersect( $reservation_weekdays, $unavailable_weekdays );
				$qualified_search_queries = ( empty( $intersecting_dates ) && empty( $intersecting_weekdays ) );
				/**
				 * This filter executes during the search query.
				 *
				 * This filter helps in modifying the decision whether any reservation item qualified the search queries.
				 *
				 * @param boolean $qualified_search_queries Whether qualified by search queries.
				 * @param int     $reservation_post_id Reservation post ID.
				 * @return boolean
				 * @since 1.0.0
				 */
				$qualified_search_queries = apply_filters( 'ersrv_reservation_item_qualified_search_parameters', $qualified_search_queries, $reservation_post_id );

				// If there is no intersecting date, the item qualifies for the result.
				if ( $qualified_search_queries ) {
					$final_reservation_ids[] = $reservation_post_id;
				}
			}
		}

		// Return the response, if the reservation posts are not found according to the checkin and checkout dates.
		if ( empty( $final_reservation_ids ) || ! is_array( $final_reservation_ids ) ) {
			$response = array(
				'code'        => 'reservation-posts-not-found',
				'html'        => ersrv_no_reservation_item_found_html( true ),
				'items_count' => __( '0 items', 'easy-reservations' ),
			);
			wp_send_json_success( $response );
			wp_die();
		}

		// Prepare the html now.
		$html = '';

		// Iterate through the qualified reservation items.
		foreach ( $final_reservation_ids as $reservation_post_id ) {
			$html .= ersrv_get_reservation_item_block_html( $reservation_post_id, 'search-reservations-page' );
		}

		// Count of qualifying reservation posts.
		$qualified_reservation_posts = count( $final_reservation_ids );

		// Items count.
		$items_count = ( ! empty( $search_performed ) && 'yes' === $search_performed ) ? $qualified_reservation_posts : $reservation_post_ids_found;

		// Return the AJAX response.
		$response = array(
			'code'        => 'reservation-posts-found',
			'html'        => $html,
			/* translators: 1: %d: items count */
			'items_count' => sprintf( _n( '%d item', '%d items', $items_count, 'easy-reservations' ), number_format_i18n( $items_count ) ),
		);
		wp_send_json_success( $response );
		wp_die();
	}

	/**
	 * Display the receipt button if there is no order status set in the admin.
	 *
	 * @param boolean $should_display Should display the button or not.
	 * @return boolean
	 * @since 1.0.0
	 */
	public function ersrv_ersrv_display_receipt_button_callback( $should_display ) {
		$order_statuses = ersrv_get_plugin_settings( 'ersrv_easy_reservations_receipt_for_order_statuses' );
		$should_display = ( empty( $order_statuses ) || ! is_array( $order_statuses ) ) ? true : $should_display;

		return $should_display;
	}

	/**
	 * Validate the item before adding the reservation to the cart.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_ersrv_add_reservation_to_cart_before_callback() {
		// Posted data.
		$item_id       = (int) filter_input( INPUT_POST, 'item_id', FILTER_SANITIZE_NUMBER_INT );
		$checkin_date  = filter_input( INPUT_POST, 'checkin_date', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );
		$checkout_date = filter_input( INPUT_POST, 'checkout_date', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );

		// Check if we need to validate for the duplicate reservation item in the cart.
		$cart_item_key = ersrv_is_reservation_item_already_in_cart( $item_id );

		// Return back, if the requesting item ID is not already in the cart.
		if ( false === $cart_item_key ) {
			return;
		}

		// Need to dig in further if the clashing reservation dates.
		$requesting_reservation_dates_obj = ersrv_get_dates_within_2_dates( $checkin_date, $checkout_date );
		$requesting_reservation_dates_arr = array();

		if ( ! empty( $requesting_reservation_dates_obj ) ) {
			foreach ( $requesting_reservation_dates_obj as $date ) {
				$requesting_reservation_dates_arr[] = $date->format( ersrv_get_php_date_format() );
			}
		}

		// Get the reservation dates of the item already in the cart.
		$in_cart_item_reserved_dates = ersrv_in_cart_item_reserved_dates( $cart_item_key );

		// Check for mismatching intersecting dates now.
		$intersecting_dates = array_intersect( $requesting_reservation_dates_arr, $in_cart_item_reserved_dates );

		// If there are intersecting dates, return the error.
		if ( ! empty( $intersecting_dates ) ) {
			// Prepare the response.
			$response = array(
				'code'          => 'reservation-not-added-to-cart',
				'toast_message' => sprintf( __( 'Cannot add a duplicate reservation with the same checkin and checkout dates. Please try again.', 'easy-reservations' ), '<a title="' . __( 'View Cart', 'easy-reservations' ) . '" href="' . wc_get_cart_url() . '">', '</a>' ),
			);
			wp_send_json_success( $response );
			wp_die();
		}
	}

	/**
	 * Prevent the access to the edit reservation page.
	 */
	public function ersrv_wp_callback() {
		global $post;

		// Prevent the site visitors from accessing the edit reservation page.
		if ( ! is_user_logged_in() ) {
			$is_edit_reservation_page = ( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'ersrv_edit_reservation' ) );

			// if it's the edit reservation page.
			if ( $is_edit_reservation_page ) {
				$my_account = wc_get_page_permalink( 'myaccount' );
				wp_safe_redirect( $my_account );
				exit( 0 );
			}
		}
	}

	/**
	 * Manage the pre get posts.
	 *
	 * @param WP_Query $wp_query WP Query.
	 */
	public function ersrv_pre_get_posts_callback( $wp_query ) {
		// Check if it's a tax query and we're on the main query.
		if ( $wp_query->is_main_query() && $wp_query->is_tax( 'reservation-item-type' ) ) {
			// Get the current queried term ID.
			$current_term_id = get_queried_object()->term_id;
	
			// Set the tax query now.
			$item_type_tax_query = array(
				array(
					'taxonomy'         => 'reservation-item-type',
					'field'            => 'term_id',
					'terms'            => $current_term_id,
					'include_children' => false,
				),
			);
	
			// Set the item type taxonomy query.
			$wp_query->set( 'tax_query', $item_type_tax_query );
		}
	
		// Exclude the reservation pages from search results.
		if ( $wp_query->is_search() ) {
			$edit_reservation_page_id    = ersrv_get_page_id( 'edit-reservation' );
			$search_reservations_page_id = ersrv_get_page_id( 'search-reservations' );
			$exclude_posts               = array( $edit_reservation_page_id, $search_reservations_page_id );
			$exclude_posts               = apply_filters( 'ersrv_exclude_posts_pre_get_posts', $exclude_posts );
	
			// Exclude the above 2 pages.
			$wp_query->set( 'post__not_in', $exclude_posts );
		}
	}
	
}
