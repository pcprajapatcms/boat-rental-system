<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.cmsminds.com/
 * @since      1.0.0
 *
 * @package    Easy_Reservations
 * @subpackage Easy_Reservations/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Easy_Reservations
 * @subpackage Easy_Reservations/admin
 * @author     cmsMinds <info@cmsminds.com>
 */
class Easy_Reservations_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Reservation - Custom product type.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string $custom_product_type Reservation - Custom product type.
	 */
	private $custom_product_type;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version     The version of this plugin.
	 *
	 * @since 1.0.0
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		// Custom product type.
		$this->custom_product_type = ersrv_get_custom_product_type_slug();
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function ersrv_admin_enqueue_scripts_callback() {
		$post_type                = filter_input( INPUT_GET, 'post_type', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );
		$post_id                  = (int) filter_input( INPUT_GET, 'post', FILTER_SANITIZE_NUMBER_INT );
		$page                     = filter_input( INPUT_GET, 'page', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );
		$include_modal_style      = false;
		$include_datepicker_style = false;

		// Include the blocked out reservation dates modal only on orders page.
		if ( ! is_null( $post_id ) && 'product' === get_post_type( $post_id ) ) {
			$include_modal_style      = true;
			$include_datepicker_style = true;
		} elseif ( ! is_null( $post_type ) && 'shop_order' === $post_type ) { // Include the modal style only on orders page.
			$include_modal_style = true;
		} elseif ( ! is_null( $page ) && 'new-reservation' === $page ) {
			$include_modal_style      = true;
			$include_datepicker_style = true;
		}

		// Enqueue bootstrap datepicker on new reservation page.
		if ( $include_datepicker_style ) {
			wp_enqueue_style(
				$this->plugin_name . '-jquery-ui-style',
				ERSRV_PLUGIN_URL . 'admin/css/ui/jquery-ui.min.css',
				array(),
				filemtime( ERSRV_PLUGIN_PATH . 'admin/css/ui/jquery-ui.min.css' )
			);
		}

		// Include modal style.
		if ( $include_modal_style ) {
			wp_enqueue_style(
				$this->plugin_name . '-modal',
				ERSRV_PLUGIN_URL . 'admin/css/easy-reservations-modal.css',
				array(),
				filemtime( ERSRV_PLUGIN_PATH . 'admin/css/easy-reservations-modal.css' )
			);
		}

		// Custom admin style.
		wp_enqueue_style(
			$this->plugin_name,
			ERSRV_PLUGIN_URL . 'admin/css/easy-reservations-admin.css',
			array(),
			filemtime( ERSRV_PLUGIN_PATH . 'admin/css/easy-reservations-admin.css' )
		);

		// Custom admin script.
		wp_enqueue_script(
			$this->plugin_name,
			ERSRV_PLUGIN_URL . 'admin/js/easy-reservations-admin.js',
			array( 'jquery', 'jquery-ui-datepicker' ),
			filemtime( ERSRV_PLUGIN_PATH . 'admin/js/easy-reservations-admin.js' ),
			true
		);

		// Localize script.
		wp_localize_script( $this->plugin_name, 'ERSRV_Admin_Script_Vars', ersrv_get_admin_script_vars() );
	}

	/**
	 * Register a new product type in WooCommerce Products.
	 *
	 * @param array $product_types Holds the list of registered product types.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function ersrv_product_type_selector_callback( $product_types ) {
		$product_type_label = ersrv_get_custom_product_type_label();

		// Check if the reservation product type already exists. Return, if it already exists.
		if ( in_array( $this->custom_product_type, $product_types, true ) ) {
			return $product_types;
		}

		// Add the new product type.
		$product_types[ $this->custom_product_type ] = $product_type_label;

		return $product_types;
	}

	/**
	 * Register product setting tabs in WooCommerce Products.
	 *
	 * @param array $tabs Holds the list of registered product settings tabs.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function ersrv_woocommerce_product_data_tabs_callback( $tabs ) {
		// Reservation details tab.
		$reservation_details_tab_title = __( 'General', 'easy-reservations' );

		/**
		 * This hook fires in admin panel on the item settings page.
		 *
		 * This filter will help in modifying the product type tab title - easy reservations.
		 *
		 * @param string $reservation_details_tab_title Holds the product type tab title.
		 *
		 * @return string
		 */
		$reservation_details_tab_title = apply_filters( 'ersrv_product_general_settings_tab_label', $reservation_details_tab_title );

		// Add the new tab - reservation details.
		$tabs['reservation_details'] = array(
			'label'    => $reservation_details_tab_title,
			'target'   => 'reservation_details_product_options',
			'class'    => array(
				"show_if_{$this->custom_product_type}",
				'hide_if_simple',
				'hide_if_grouped',
				'hide_if_external',
				'hide_if_variable',
			),
			'priority' => 65,
		);

		// Reservation blockout dates tab.
		$reservation_blockout_dates_tab_title = __( 'Blockout Dates', 'easy-reservations' );

		/**
		 * This hook fires in admin panel on the item settings page.
		 *
		 * This filter will help in modifying the product type tab title - blockout dates.
		 *
		 * @param string $reservation_blockout_dates_tab_title Holds the product type tab title.
		 *
		 * @return string
		 */
		$reservation_blockout_dates_tab_title = apply_filters( 'ersrv_product_blockout_dates_settings_tab_label', $reservation_blockout_dates_tab_title );

		// Add the new tab - reservation blockout dates.
		$tabs['reservation_blockout_dates'] = array(
			'label'    => $reservation_blockout_dates_tab_title,
			'target'   => 'reservation_blockout_dates_product_options',
			'class'    => array(
				"show_if_{$this->custom_product_type}",
				'hide_if_simple',
				'hide_if_grouped',
				'hide_if_external',
				'hide_if_variable',
			),
			'priority' => 68,
		);

		// Hide the general tab.
		if ( ! empty( $tabs['general'] ) ) {
			$tabs['general']['class'][] = "hide_if_{$this->custom_product_type}";
		}

		// Hide the inventory tab.
		if ( ! empty( $tabs['inventory'] ) ) {
			$tabs['inventory']['class'][] = "hide_if_{$this->custom_product_type}";
		}

		// Hide the shipping tab.
		if ( ! empty( $tabs['shipping'] ) ) {
			$tabs['shipping']['class'][] = "hide_if_{$this->custom_product_type}";
		}

		// Hide the linked products tab.
		if ( ! empty( $tabs['linked_product'] ) ) {
			$tabs['linked_product']['class'][] = "hide_if_{$this->custom_product_type}";
		}

		// Hide the attributes tab.
		if ( ! empty( $tabs['attribute'] ) ) {
			$tabs['attribute']['class'][] = "hide_if_{$this->custom_product_type}";
		}

		// Hide the variations tab.
		if ( ! empty( $tabs['variations'] ) ) {
			$tabs['variations']['class'][] = "hide_if_{$this->custom_product_type}";
		}

		return $tabs;
	}

	/**
	 * Create the settings template for the reservation type.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_woocommerce_product_data_panels_callback() {
		global $post;

		if ( empty( $post->ID ) ) {
			return;
		}

		// Reservation details.
		require_once ERSRV_PLUGIN_PATH . 'admin/templates/settings/reservation-details-settings.php';

		// Reservation blockout dates.
		require_once ERSRV_PLUGIN_PATH . 'admin/templates/settings/reservation-blockout-dates.php';
	}

	/**
	 * Update product custom meta details.
	 *
	 * @param int $post_id Holds the product ID.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_woocommerce_process_product_meta_callback( $post_id ) {
		$location                  = filter_input( INPUT_POST, 'location', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );
		$security_amt              = (float) filter_input( INPUT_POST, 'security_amount', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );
		$accomodation_limit        = (int) filter_input( INPUT_POST, 'accomodation_limit', FILTER_SANITIZE_NUMBER_INT );
		$accomodation_adult_charge = (float) filter_input( INPUT_POST, 'accomodation_adult_charge', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );
		$accomodation_kid_charge   = (float) filter_input( INPUT_POST, 'accomodation_kid_charge', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );
		$reservation_min_period    = (int) filter_input( INPUT_POST, 'reservation_min_period', FILTER_SANITIZE_NUMBER_INT );
		$reservation_max_period    = (int) filter_input( INPUT_POST, 'reservation_max_period', FILTER_SANITIZE_NUMBER_INT );
		$promotion_text            = filter_input( INPUT_POST, 'promotion_text', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );
		$posted_array              = filter_input_array( INPUT_POST );
		$amenities_titles          = isset( $posted_array['amenity_title'] ) ? $posted_array['amenity_title'] : array();
		$amenities_costs           = isset( $posted_array['amenity_cost'] ) ? $posted_array['amenity_cost'] : array();
		$amenity_cost_types        = isset( $posted_array['amenity_cost_type'] ) ? $posted_array['amenity_cost_type'] : array();
		$amenities                 = array();
		$blockout_dates            = isset( $posted_array['blockout_date'] ) ? $posted_array['blockout_date'] : array();
		$blockout_dates_messages   = isset( $posted_array['blockout_date_message'] ) ? $posted_array['blockout_date_message'] : array();
		$has_captain               = filter_input( INPUT_POST, 'has_captain', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );
		$has_captain               = ( ! empty( $has_captain ) && 'yes' === $has_captain ) ? 'yes' : 'no';
		$has_captain_text          = filter_input( INPUT_POST, 'has_captain_text', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );
		$captain_id                = (int) filter_input( INPUT_POST, 'reservation_item_captain', FILTER_SANITIZE_NUMBER_INT );
		$unavailable_weekdays      = isset( $posted_array['reservation_item_weekdays_unavailability'] ) ? $posted_array['reservation_item_weekdays_unavailability'] : array();

		// Prepare the amenities array.
		if ( ! empty( $amenities_titles ) && is_array( $amenities_titles ) ) {
			foreach ( $amenities_titles as $index => $amenity_title ) {
				$amenities[] = array(
					'title'     => $amenity_title,
					'cost'      => $amenities_costs[ $index ],
					'cost_type' => $amenity_cost_types[ $index ],
				);
			}

			// Update the amenities to the database.
			update_post_meta( $post_id, '_ersrv_reservation_amenities', $amenities );
		} else {
			delete_post_meta( $post_id, '_ersrv_reservation_amenities' );
		}

		// Prepare the blockout calendar dates array.
		if ( ! empty( $blockout_dates ) && is_array( $blockout_dates ) ) {
			// Iterate through the blockout dates to add them to database.
			foreach ( $blockout_dates as $index => $blockout_date ) {
				$blockedout_dates[] = array(
					'date'    => $blockout_date,
					'message' => $blockout_dates_messages[ $index ],
				);
			}

			// Update the blocked out dates to the database.
			update_post_meta( $post_id, '_ersrv_reservation_blockout_dates', $blockedout_dates );
		} else {
			delete_post_meta( $post_id, '_ersrv_reservation_blockout_dates' );
		}

		// If item location is available.
		if ( ! empty( $location ) ) {
			update_post_meta( $post_id, '_ersrv_item_location', $location );
		} else {
			delete_post_meta( $post_id, '_ersrv_item_location' );
		}

		// If security amount is available.
		if ( ! empty( $security_amt ) ) {
			update_post_meta( $post_id, '_ersrv_security_amt', $security_amt );
		} else {
			delete_post_meta( $post_id, '_ersrv_security_amt' );
		}

		// If accomodation limit is available.
		if ( ! empty( $accomodation_limit ) ) {
			update_post_meta( $post_id, '_ersrv_accomodation_limit', $accomodation_limit );
		} else {
			delete_post_meta( $post_id, '_ersrv_accomodation_limit' );
		}

		// If accomodation adult charge is available.
		if ( ! empty( $accomodation_adult_charge ) ) {
			update_post_meta( $post_id, '_ersrv_accomodation_adult_charge', $accomodation_adult_charge );
		} else {
			delete_post_meta( $post_id, '_ersrv_accomodation_adult_charge' );
		}

		// If accomodation kid's charge is available.
		if ( ! empty( $accomodation_kid_charge ) ) {
			update_post_meta( $post_id, '_ersrv_accomodation_kid_charge', $accomodation_kid_charge );
		} else {
			delete_post_meta( $post_id, '_ersrv_accomodation_kid_charge' );
		}

		// If accomodation minimum period is available.
		if ( ! empty( $reservation_min_period ) ) {
			update_post_meta( $post_id, '_ersrv_reservation_min_period', $reservation_min_period );
		} else {
			delete_post_meta( $post_id, '_ersrv_reservation_min_period' );
		}

		// If accomodation maximum period is available.
		if ( ! empty( $reservation_max_period ) ) {
			update_post_meta( $post_id, '_ersrv_reservation_max_period', $reservation_max_period );
		} else {
			delete_post_meta( $post_id, '_ersrv_reservation_max_period' );
		}

		// If promotion text is available.
		if ( ! empty( $promotion_text ) ) {
			update_post_meta( $post_id, '_ersrv_promotion_text', $promotion_text );
		} else {
			delete_post_meta( $post_id, '_ersrv_promotion_text' );
		}

		// If the reservation item comes with a captain.
		if ( ! empty( $has_captain ) && 'yes' === $has_captain ) {
			update_post_meta( $post_id, '_ersrv_has_captain', $has_captain );
		} else {
			delete_post_meta( $post_id, '_ersrv_has_captain' );
		}

		// Captain text.
		if ( ! empty( $has_captain_text ) ) {
			update_post_meta( $post_id, '_ersrv_has_captain_text', $has_captain_text );
		} else {
			delete_post_meta( $post_id, '_ersrv_has_captain_text' );
		}

		// Captain user ID.
		if ( ! empty( $captain_id ) ) {
			update_post_meta( $post_id, '_ersrv_item_captain', $captain_id );
		} else {
			delete_post_meta( $post_id, '_ersrv_item_captain' );
		}

		// Unavailable weekdays.
		if ( ! empty( $unavailable_weekdays ) ) {
			update_post_meta( $post_id, '_ersrv_item_unavailable_weekdays', $unavailable_weekdays );
		} else {
			delete_post_meta( $post_id, '_ersrv_item_unavailable_weekdays' );
		}
	}

	/**
	 * Add custom assets to WordPress admin footer section.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_admin_footer_callback() {
		$page       = filter_input( INPUT_GET, 'page', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );
		$post_type  = filter_input( INPUT_GET, 'post_type', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );
		$product_id = (int) filter_input( INPUT_GET, 'post', FILTER_SANITIZE_NUMBER_INT );

		// Include the export reservations modal only on orders page.
		if ( ! is_null( $post_type ) && 'shop_order' === $post_type ) {
			require_once ERSRV_PLUGIN_PATH . 'admin/templates/modals/export-reservations.php';
		}

		// Include the blocked out reservation dates modal only on orders page.
		if ( ! is_null( $product_id ) && 'product' === get_post_type( $product_id ) ) {
			require_once ERSRV_PLUGIN_PATH . 'admin/templates/modals/block-out-reservation-calendar-dates.php';
		}

		// Include the new customer modal on new reservation page only on product page.
		if ( ! is_null( $page ) && 'new-reservation' === $page ) {
			require_once ERSRV_PLUGIN_PATH . 'admin/templates/modals/new-customer.php';
		}

		// Include the notification html.
		require_once ERSRV_PLUGIN_PATH . 'public/templates/notifications/notification.php';
	}

	/**
	 * AJAX to export reservations.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_export_reservations_callback() {
		$action = filter_input( INPUT_POST, 'action', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );

		// Exit, if the action mismatches.
		if ( empty( $action ) || 'export_reservations' !== $action ) {
			echo esc_html( 0 );
			wp_die();
		}

		// Posted data.
		$from_date = filter_input( INPUT_POST, 'from_date', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );
		$to_date   = filter_input( INPUT_POST, 'to_date', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );

		$wc_orders_query = ersrv_get_posts( 'shop_order', 1, - 1 );
		$wc_order_ids    = $wc_orders_query->posts;

		// Return back, if there are no orders available.
		if ( empty( $wc_order_ids ) || ! is_array( $wc_order_ids ) ) {
			return;
		}

		/**
		 * This filter is fired by the AJAX call to export the reservation orders.
		 *
		 * This filter helps in managing the array of order ids that are considered for exporting them into various firmats.
		 *
		 * @param array $wc_order_ids Array of WooCommerce order IDs.
		 *
		 * @return array
		 * @since 1.0.0
		 */
		$wc_order_ids = apply_filters( 'ersrv_reservation_reminder_email_order_ids', $wc_order_ids );

		// Prepare the data now.
		$wc_orders_data = ersrv_get_export_reservation_orders_data( $wc_order_ids );

		$this->ersrv_download_reservation_orders_csv( $wc_orders_data );
	}

	/**
	 * Download the reservation orders data.
	 *
	 * @param array $wc_orders_data Reservation orders export data.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_download_reservation_orders_csv( $wc_orders_data ) {
		// Exit, if the reservations orders data is empty.
		if ( empty( $wc_orders_data ) || ! is_array( $wc_orders_data ) ) {
			exit();
		}

		// Create the CSV now.
		$fp = fopen( 'php://output', 'w' );
		fputcsv( $fp, array_keys( reset( $wc_orders_data ) ) );

		// Iterate through the clubs to download them.
		foreach ( $wc_orders_data as $wc_order_data ) {
			fputcsv( $fp, $wc_order_data );
		}

		fclose( $fp );
		exit();
	}

	/**
	 * Admin settings for managing reservations.
	 *
	 * @param array $settings Array of WC settings.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function ersrv_woocommerce_get_settings_pages_callback( $settings ) {
		$settings[] = include ERSRV_PLUGIN_PATH . 'includes/classes/class-easy-reservations-settings.php';

		return $settings;
	}

	/**
	 * Add custom plugin row meta actions.
	 *
	 * @param array  $links Holds the links array.
	 * @param string $file  Holds this plugin file.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function ersrv_plugin_row_meta_callback( $links, $file ) {
		// Return the links, if the file doesn't match this plugin main file.
		if ( 'easy-reservations/easy-reservations.php' !== $file ) {
			return $links;
		}

		// New row meta - community support.
		$links[] = '<a href="javascript:void(0);" target="_blank" title="' . __( 'Community support', 'easy-reservations' ) . '">' . __( 'Community support', 'easy-reservations' ) . '</a>';

		// New row meta - developer docs.
		$links[] = '<a href="javascript:void(0);" target="_blank" title="' . __( 'Developer docs', 'easy-reservations' ) . '">' . __( 'Developer docs', 'easy-reservations' ) . '</a>';

		return $links;
	}

	/**
	 * AJAX to fetch the amenity HTML.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_get_amenity_html_callback() {
		$action = filter_input( INPUT_POST, 'action', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );

		// Exit, if the action mismatches.
		if ( empty( $action ) || 'get_amenity_html' !== $action ) {
			echo esc_html( 0 );
			wp_die();
		}

		// Return the amenity html response.
		$response = array(
			'code' => 'amenity-html-fetched',
			'html' => ersrv_get_amenity_html( array() ),
		);
		wp_send_json_success( $response );
		wp_die();
	}

	/**
	 * Register a widget for showing the calendar.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_widgets_init_callback() {
		require_once ERSRV_PLUGIN_PATH . 'includes/classes/class-easy-reservations-calendar-widget.php';
		register_widget( 'Easy_Reservations_Calendar_Widget' );
	}

	/**
	 * AJAX to fetch the blockout date HTML.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_get_blockout_date_html_callback() {
		$action = filter_input( INPUT_POST, 'action', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );

		// Exit, if the action mismatches.
		if ( empty( $action ) || 'get_blockout_date_html' !== $action ) {
			echo esc_html( 0 );
			wp_die();
		}

		// Posted data.
		$from    = filter_input( INPUT_POST, 'date_from', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );
		$to      = filter_input( INPUT_POST, 'date_to', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );
		$message = filter_input( INPUT_POST, 'message', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );

		// Dates array.
		$dates = array();

		// Gather dates.
		if ( empty( $to ) && ! empty( $from ) ) { // If only start date is available.
			$dates[] = array(
				'date'    => $from,
				'message' => $message,
			);
		} elseif ( empty( $from ) && ! empty( $to ) ) { // If only end date is available.
			$dates[] = array(
				'date'    => $to,
				'message' => $message,
			);
		} else { // If start and end dates are available.
			// Get the dates between 2 dates.
			$dates_range = ersrv_get_dates_within_2_dates( $from, $to );

			if ( ! empty( $dates_range ) ) {
				foreach ( $dates_range as $date ) {
					$dates[] = array(
						'date'    => $date->format( ersrv_get_php_date_format() ),
						'message' => $message,
					);
				}
			}
		}

		// Prepare the HTML now.
		$html = '';
		if ( ! empty( $dates ) && is_array( $dates ) ) {
			foreach ( $dates as $date_data ) {
				$html .= ersrv_get_blockout_date_html( $date_data['date'], $date_data['message'] );
			}
		}

		// Return the blockout date html response.
		$response = array(
			'code' => 'blockout-date-html-fetched',
			'html' => $html,
		);
		wp_send_json_success( $response );
		wp_die();
	}

	/**
	 * Add custom admin pages.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_admin_menu_callback() {
		// Submenu to add reservation from admin panel.
		add_submenu_page(
			'woocommerce',
			__( 'New Reservation', 'easy-reservations' ),
			__( 'New Reservation', 'easy-reservations' ),
			'manage_options',
			'new-reservation',
			array( $this, 'ersrv_new_admin_reservation' ),
			15
		);

		$reservation_cancellation_requests_hook = add_submenu_page(
			'woocommerce',
			__( 'Reservation Cancellation Requests', 'easy-reservations' ),
			__( 'Reservation Cancellation Requests', 'easy-reservations' ),
			'manage_options',
			'reservation-cancellation-requests',
			array( $this, 'ersrv_reservation_cancellation_requests' )
		);
		include plugin_dir_path( __FILE__ ) . 'templates/pages/class-easy-reservations-cancellation-requests.php';
		add_action( "load-$reservation_cancellation_requests_hook", array( $this, 'ersrv_load_reservation_cancellation_requests_menu_page_screen_options_callback' ) );
	}

	/**
	 * Reservation cancelation requests admin page screen options.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_load_reservation_cancellation_requests_menu_page_screen_options_callback() {
		global $reservation_cancellation_requests_menu_page_data;
		$option = 'per_page';
		$args   = array(
			'label'   => __( 'Requests Per Page', 'easy-reservations' ),
			'default' => 10,
			'option'  => 'ersrv_cancellation_requests_per_page',
		);
		add_screen_option( $option, $args );
		$reservation_cancellation_requests_menu_page_data = new Easy_Reservations_Cancellation_Requests();
	}

	/**
	 * Reservation cancellation requests template from at panel.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_reservation_cancellation_requests() {
		global $reservation_cancellation_requests_menu_page_data;
		$reservation_cancellation_requests_menu_page_data->prepare_items();

		// Current page.
		$page = filter_input( INPUT_GET, 'page', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );
		$page = ( ! is_null( $page ) ) ? $page : '';
		?>
		<div class="wrap">
			<h2><?php esc_html_e( 'Reservation Cancellation Requests', 'easy-reservations' ); ?></h2>
			<p><?php esc_html_e( 'Following is the list of all the reservation cancellation requests.', 'easy-reservations' ); ?></p>
			<div id="nds-wp-list-table-demo">
				<div id="nds-post-body">
					<form id="nds-user-list-form" method="get">
						<input type="hidden" name="page" value="<?php echo esc_html( $page ); ?>" />
						<?php
						$reservation_cancellation_requests_menu_page_data->search_box( __( 'Search Requests', 'easy-reservations' ), 'search-reservation-cancellation-requests' );
						$reservation_cancellation_requests_menu_page_data->display();
						?>
					</form>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * New reservation template from admin panel.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_new_admin_reservation() {
		require_once ERSRV_PLUGIN_PATH . 'admin/templates/pages/new-reservation.php';
	}

	/**
	 * Set the screen options values.
	 *
	 * @param boolean $status The value to save instead of the option value.
	 * @param string  $option Screen option slug.
	 * @param string  $value  Screen option new value.
	 */
	public function ersrv_set_screen_option_callback( $status, $option, $value ) {

		return $value;
	}

	/**
	 * AJAX to create new user.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_register_new_customer_callback() {
		$action = filter_input( INPUT_POST, 'action', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );

		// Exit, if the action mismatches.
		if ( empty( $action ) || 'register_new_customer' !== $action ) {
			echo esc_html( 0 );
			wp_die();
		}

		// Posted data.
		$first_name     = filter_input( INPUT_POST, 'first_name', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );
		$last_name      = filter_input( INPUT_POST, 'last_name', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );
		$email          = filter_input( INPUT_POST, 'email', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );
		$phone          = filter_input( INPUT_POST, 'phone', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );
		$password       = filter_input( INPUT_POST, 'password', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );
		$email_exploded = explode( '@', $email );
		$username       = ( ! empty( $email_exploded[0] ) ) ? $email_exploded[0] : $email;
		$address_line   = filter_input( INPUT_POST, 'address_line', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );
		$address_line_2 = filter_input( INPUT_POST, 'address_line_2', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );
		$country        = filter_input( INPUT_POST, 'country', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );
		$state          = filter_input( INPUT_POST, 'state', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );
		$city           = filter_input( INPUT_POST, 'city', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );
		$postcode       = filter_input( INPUT_POST, 'postcode', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );

		// Return the error if the customer exists.
		if ( email_exists( $email ) || username_exists( $username ) ) {
			wp_send_json_error(
				array(
					'code'          => 'ersrv-user-exists',
					/* translators: 1: %s: customer email address */
					'error_message' => sprintf( __( 'User with the requested email, %1$s, already exists. Please try with a different email address.', 'easy-reservations' ), $email ),
				)
			);
			wp_die();
		}

		// Save the user in database.
		$user_id           = ersrv_create_new_user( $username, $email, $password, $first_name, $last_name );
		$user_data         = get_userdata( $user_id );
		$user_display_name = $user_data->data->display_name;
		$user_email        = $user_data->data->user_email;
		$user_option_text  = "#{$user_id} [{$user_email}] - {$user_display_name}";

		// Update the customer's billing details.
		update_user_meta( $user_id, 'billing_first_name', $first_name );
		update_user_meta( $user_id, 'billing_last_name', $last_name );
		update_user_meta( $user_id, 'billing_address_1', $address_line );
		update_user_meta( $user_id, 'billing_address_2', $address_line_2 );
		update_user_meta( $user_id, 'billing_city', $city );
		update_user_meta( $user_id, 'billing_state', $state );
		update_user_meta( $user_id, 'billing_postcode', $postcode );
		update_user_meta( $user_id, 'billing_country', $country );
		update_user_meta( $user_id, 'billing_email', $email );
		update_user_meta( $user_id, 'billing_phone', $phone );

		$response = array(
			'code'            => 'ersrv-user-registered',
			'user_id'         => $user_id,
			'success_message' => __( 'User created.', 'easy-reservations' ),
			'user_html'       => '<option value="' . $user_id . '">' . $user_option_text . '</option>',
		);
		wp_send_json_success( $response );
		wp_die();
	}

	/**
	 * AJAX to generate new password.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_generate_new_password_callback() {
		$action = filter_input( INPUT_POST, 'action', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );

		// Exit, if the action mismatches.
		if ( empty( $action ) || 'generate_new_password' !== $action ) {
			echo esc_html( 0 );
			wp_die();
		}

		$response = array(
			'code'     => 'password-generated',
			'password' => wp_generate_password( 12, true, true ),
		);
		wp_send_json_success( $response );
		wp_die();
	}

	/**
	 * AJAX to get the reservable item details.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_get_reservable_item_details_callback() {
		$action = filter_input( INPUT_POST, 'action', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );

		// Exit, if the action mismatches.
		if ( empty( $action ) || 'get_reservable_item_details' !== $action ) {
			echo esc_html( 0 );
			wp_die();
		}

		// Posted data.
		$item_id = (int) filter_input( INPUT_POST, 'item_id', FILTER_SANITIZE_NUMBER_INT );

		// Send the AJAX response.
		$response = array(
			'code'    => 'item-details-fetched',
			'details' => ersrv_get_item_details( $item_id ),
		);
		wp_send_json_success( $response );
		wp_die();
	}

	/**
	 * AJAX to create new reservation.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_create_reservation_callback() {
		$action = filter_input( INPUT_POST, 'action', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );

		// Exit, if the action mismatches.
		if ( empty( $action ) || 'create_reservation' !== $action ) {
			echo esc_html( 0 );
			wp_die();
		}

		// Posted data.
		$item_id            = filter_input( INPUT_POST, 'item_id', FILTER_SANITIZE_NUMBER_INT );
		$customer_id        = filter_input( INPUT_POST, 'customer_id', FILTER_SANITIZE_NUMBER_INT );
		$checkin_date       = filter_input( INPUT_POST, 'checkin_date', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );
		$checkout_date      = filter_input( INPUT_POST, 'checkout_date', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );
		$adult_count        = filter_input( INPUT_POST, 'adult_count', FILTER_SANITIZE_NUMBER_INT );
		$kid_count          = filter_input( INPUT_POST, 'kid_count', FILTER_SANITIZE_NUMBER_INT );
		$customer_notes     = filter_input( INPUT_POST, 'customer_notes', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );
		$posted_array       = filter_input_array( INPUT_POST );
		$amenities          = ( ! empty( $posted_array['amenities'] ) ) ? $posted_array['amenities'] : array();
		$item_subtotal      = (float) filter_input( INPUT_POST, 'item_subtotal', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );
		$kids_subtotal      = (float) filter_input( INPUT_POST, 'kids_subtotal', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );
		$security_subtotal  = (float) filter_input( INPUT_POST, 'security_subtotal', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );
		$amenities_subtotal = (float) filter_input( INPUT_POST, 'amenities_subtotal', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );
		$item_total         = (float) filter_input( INPUT_POST, 'item_total', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );

		// Customer IP address.
		$server_data         = wp_unslash( $_SERVER );
		$customer_ip_address = sanitize_text_field( $server_data['REMOTE_ADDR'] );

		// Prepare the billing address.
		$billing_address = array(
			'first_name' => get_user_meta( $customer_id, 'billing_first_name', true ),
			'last_name'  => get_user_meta( $customer_id, 'billing_last_name', true ),
			'company'    => get_user_meta( $customer_id, 'billing_company', true ),
			'address_1'  => get_user_meta( $customer_id, 'billing_address_1', true ),
			'address_2'  => get_user_meta( $customer_id, 'billing_address_2', true ),
			'city'       => get_user_meta( $customer_id, 'billing_city', true ),
			'state'      => get_user_meta( $customer_id, 'billing_state', true ),
			'postcode'   => get_user_meta( $customer_id, 'billing_postcode', true ),
			'country'    => get_user_meta( $customer_id, 'billing_country', true ),
			'email'      => get_user_meta( $customer_id, 'billing_email', true ),
			'phone'      => get_user_meta( $customer_id, 'billing_phone', true ),
		);

		// Order arguments.
		$order_args = array(
			'status'              => 'pending',
			'customer_ip_address' => $customer_ip_address,
		);

		/**
		 * This hook fires before reservation is created as WooCommerce order.
		 *
		 * This hook helps in executing anything before the reservation is created from admin panel.
		 */
		do_action( 'ersrv_create_reservation_from_admin_before' );

		// Create the woocommerce order now.
		$wc_order = wc_create_order( $order_args );
		$wc_order->set_customer_id( $customer_id );
		$wc_order->set_customer_note( $customer_notes );
		$wc_order->set_currency( get_woocommerce_currency() );
		$wc_order->set_prices_include_tax( 'yes' === get_option( 'woocommerce_prices_include_tax' ) );

		// Set the array for tax calculations.
		$calculate_tax_for = array(
			'country'  => ( ! empty( $billing_address['country'] ) ) ? $billing_address['country'] : '',
			'state'    => ( ! empty( $billing_address['state'] ) ) ? $billing_address['state'] : '',
			'postcode' => ( ! empty( $billing_address['postcode'] ) ) ? $billing_address['postcode'] : '',
			'city'     => ( ! empty( $billing_address['city'] ) ) ? $billing_address['city'] : '',
		);

		$wc_product = wc_get_product( $item_id );
		$item_id    = $wc_order->add_product(
			$wc_product,
			1,
			array(
				'total' => $item_total,
			)
		);

		$line_item = $wc_order->get_item( $item_id, false );
		$line_item->calculate_taxes( $calculate_tax_for );
		$line_item->update_meta_data( 'Checkin Date', $checkin_date ); // Update the checkin date.
		$line_item->update_meta_data( 'Checkout Date', $checkout_date ); // Update the checkout date.
		$line_item->update_meta_data( 'Adult Count', $adult_count ); // Update the adult count.
		$line_item->update_meta_data( 'Adult Subtotal', $item_subtotal ); // Update the adult subtotal.
		$line_item->update_meta_data( 'Kids Count', $kid_count ); // Update the kids count.
		$line_item->update_meta_data( 'Kids Subtotal', $kids_subtotal ); // Update the kids subtotal.
		$line_item->update_meta_data( 'Security Amount', $security_subtotal ); // Update the security subtotal.

		// Update the amenities to order item meta.
		if ( ! empty( $amenities ) && is_array( $amenities ) ) {
			$line_item->update_meta_data( 'Amenities', $amenities ); // Update the amenity data.
			$line_item->update_meta_data( 'Amenities Subtotal', $amenities_subtotal ); // Update the amenities subtotal.
		}

		// Save the line item.
		$line_item->save();

		$wc_order->set_address( $billing_address, 'billing' );
		$wc_order->calculate_totals();
		$wc_order->save();

		// Update order meta to be a reservation order.
		update_post_meta( $wc_order->get_id(), 'ersrv_reservation_order', 1 );

		// Block the dates after reservation is successfully filed by the customer.
		ersrv_block_dates_after_reservation_thankyou( $wc_order );

		/**
		 * This hook fires after reservation is created as WooCommerce order.
		 *
		 * This hook helps in executing anything after the reservation is created from admin panel.
		 */
		do_action( 'ersrv_create_reservation_from_admin_after' );

		/**
		 * Get the order link.
		 * If the driving license is mandatory, the user will be redirected to the order edit page.
		 * And the admin should upload the driving license there.
		 */
		$allowed_to_upload_license = ersrv_get_plugin_settings( 'ersrv_driving_license_validation' );
		$order_edit_link           = get_edit_post_link( $wc_order->get_id(), '&' );
		$order_edit_link           = ( empty( $allowed_to_upload_license ) || 'no' === $allowed_to_upload_license ) ? $order_edit_link : $order_edit_link . '#ersrv-reservation-order-driving-license-file';

		// Prepare the response.
		wp_send_json_success(
			array(
				'code'          => 'reservation-created',
				'toast_message' => __( 'Reservation is created. You\'ll be redirected to order edit page in a few seconds.', 'easy-reservations' ),
				'redirect_to'   => $order_edit_link,
			)
		);
		wp_die();
	}

	/**
	 * Get the states from country code.
	 *
	 * @param string $country_code Holds the country code.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function ersrv_get_states_callback( $country_code ) {
		// If doing AJAX.
		if ( DOING_AJAX ) {
			$action = filter_input( INPUT_POST, 'action', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );

			// Exit, if the action mismatches.
			if ( empty( $action ) || 'get_states' !== $action ) {
				echo esc_html( 0 );
				wp_die();
			}

			// Posted data.
			$country_code = filter_input( INPUT_POST, 'country_code', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );
		}

		// Get the states now.
		$woo_countries = new WC_Countries();
		$states        = $woo_countries->get_states( $country_code );
		$states        = ( ! empty( $states ) && is_array( $states ) ) ? $states : array();

		// Send the AJAX response.
		if ( DOING_AJAX ) {
			$response = array(
				'code'   => 'states-fetched',
				'states' => $states,
			);
			wp_send_json_success( $response );
			wp_die();
		}

		return $states;
	}

	/**
	 * Update reservation item meta details.
	 *
	 * @param int $post_id WordPress Post ID.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_save_post_callback( $post_id ) {
		// If it's the product page.
		if ( 'product' === get_post_type( $post_id ) ) {
			$wc_product                = wc_get_product( $post_id );
			$accomodation_adult_charge = (float) filter_input( INPUT_POST, 'accomodation_adult_charge', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );
			$banner_image_id           = filter_input( INPUT_POST, 'banner_image_id', FILTER_SANITIZE_NUMBER_INT );

			// Check if the updating product is of reservation type.
			if ( $this->custom_product_type === $wc_product->get_type() ) {
				// If accomodation adult charge is available.
				if ( ! empty( $accomodation_adult_charge ) ) {
					update_post_meta( $post_id, '_regular_price', $accomodation_adult_charge );
					delete_post_meta( $post_id, '_sale_price' );
					update_post_meta( $post_id, '_price', $accomodation_adult_charge );
				} else {
					delete_post_meta( $post_id, '_regular_price' );
					delete_post_meta( $post_id, '_price' );
				}

				// Manage the stock data.
				update_post_meta( $post_id, '_stock', 99999 );
				update_post_meta( $post_id, '_stock_status', 'instock' );
			}
		} elseif ( 'page' === get_post_type( $post_id ) ) {
			$banner_image_id = filter_input( INPUT_POST, 'banner_image_id', FILTER_SANITIZE_NUMBER_INT );
		}

		// If we have the banner image.
		if ( ! empty( $banner_image_id ) ) {
			update_post_meta( $post_id, 'ersrv_banner_image_id', $banner_image_id );
		} else {
			delete_post_meta( $post_id, 'ersrv_banner_image_id' );
		}
	}

	/**
	 * Add custom metaboxes on order page.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_add_meta_boxes_callback() {
		// Get the post ID.
		$post_id = filter_input( INPUT_GET, 'post', FILTER_SANITIZE_NUMBER_INT );

		// Add the metabox on only the post edit page.
		if ( is_null( $post_id ) ) {
			return;
		}

		$wc_order = wc_get_order( $post_id );

		// If this is a valid order.
		if ( false !== $wc_order ) {
			$is_reservation = ersrv_order_is_reservation( $wc_order );
			// Add meta box for reservations order.
			if ( $is_reservation ) {
				// Metabox for calendar invitations.
				add_meta_box(
					'ersrv-reservation-order-email-calendar-invites',
					__( 'Easy Reservations: Calendar Invites', 'easy-reservations' ),
					array( $this, 'ersrv_calendar_invites_reservation_order' ),
					'shop_order',
					'side',
					'high'
				);

				// Metabox for driving license.
				add_meta_box(
					'ersrv-reservation-order-driving-license-file',
					__( 'Easy Reservations: Driving License', 'easy-reservations' ),
					array( $this, 'ersrv_reservation_order_dricing_license_file' ),
					'shop_order',
					'side',
					'high'
				);

				// Check if the order is updated.
				$is_order_updated = get_post_meta( $post_id, 'ersrv_reservation_update', true );
				if ( ! empty( $is_order_updated ) && '1' === $is_order_updated ) {
					// Get the cost difference.
					$cost_difference = (float) get_post_meta( $post_id, 'ersrv_cost_difference', true );

					if ( ! empty( $cost_difference ) && 0 < $cost_difference ) {
						// Metabox for driving license.
						add_meta_box(
							'ersrv-updated-reservation-order-cost-difference',
							__( 'Easy Reservations: Updated Order Cost Difference', 'easy-reservations' ),
							array( $this, 'ersrv_updated_reservation_order_cost_difference' ),
							'shop_order',
							'side',
							'high',
							array(
								'cost_difference'     => $cost_difference,
								'cost_difference_key' => get_post_meta( $post_id, 'ersrv_cost_difference_key', true ),
							)
						);
					}
				}
			}
		}

		// Add metabox for custom settings.
		add_meta_box(
			'ersrv-banner-image',
			__( 'Easy Reservations: Custom Settings', 'easy-reservations' ),
			array( $this, 'ersrv_posts_custom_fields_metabox' ),
			array( 'page', 'product' ),
			'normal',
			'high'
		);
	}

	/**
	 * Add the calendar invites button for the reservation orders.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_calendar_invites_reservation_order() {
		$post = (int) filter_input( INPUT_GET, 'post', FILTER_SANITIZE_NUMBER_INT );

		ob_start();
		?>
		<div class="ersrv-calendar-invites-container">
			<p><?php esc_html_e( 'Click on the buttons below to email the calendar invites to the customer\'s billing email address.', 'easy-reservations' ); ?></p>
			<p>
				<button type="button" class="button add-to-ical"><?php esc_html_e( 'Email iCalendar Invite', 'easy-reservations' ); ?></button>
			</p>
			<p>
				<button type="button" class="button add-to-gcal"><?php esc_html_e( 'Email Google Calendar Invite', 'easy-reservations' ); ?></button>
			</p>
		</div>
		<?php

		// Print the content now.
		echo wp_kses(
			ob_get_clean(),
			array(
				'div'    => array(
					'class' => array(),
				),
				'p'      => array(),
				'button' => array(
					'type'  => array(),
					'class' => array(),
				),
			),
		);
	}

	/**
	 * Add the download driving license button for the reservation orders.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_reservation_order_dricing_license_file() {
		$post = (int) filter_input( INPUT_GET, 'post', FILTER_SANITIZE_NUMBER_INT );

		// Get the license attachment ID.
		$license_id = get_post_meta( $post, 'reservation_driving_license_attachment_id', true );

		ob_start();

		// If the license is available.
		if ( ! empty( $license_id ) ) {
			// Get the license URL.
			$license_url = ersrv_get_attachment_url_from_attachment_id( $license_id );
			?>
			<div class="ersrv-driving-license-container edit-order">
				<p><?php esc_html_e( 'Click on the buttons below to download & view customer\'s driving license.', 'easy-reservations' ); ?></p>
				<p>
					<a href="<?php echo esc_url( $license_url ); ?>" class="button download" download><?php esc_html_e( 'Download', 'easy-reservations' ); ?><span class="dashicons dashicons-download"></span></a>
					<a href="<?php echo esc_url( $license_url ); ?>" rel="noopener noreferrer" class="button view" target="_blank"><?php esc_html_e( 'View', 'easy-reservations' ); ?><span class="dashicons dashicons-visibility"></span></a>
				</p>
			</div>
			<?php
		} else {
			// Allowed file types.
			$driving_license_allowed_extensions = ersrv_get_driving_license_allowed_file_types();
			$allowed_extensions_string          = ( ! empty( $driving_license_allowed_extensions ) && is_array( $driving_license_allowed_extensions ) ) ? implode( ',', $driving_license_allowed_extensions ) : '';
			?>
			<div class="ersrv-driving-license-container edit-order">
				<p><?php esc_html_e( 'There is no driving license uploaded. Please click the button below to upload one such.', 'easy-reservations' ); ?></p>
				<p>
					<input type="file" accept="<?php echo esc_attr( $allowed_extensions_string ); ?>" name="reservation-driving-license" id="reservation-driving-license" />
					<button type="button" class="ersrv-upload-license button upload"><?php esc_html_e( 'Upload', 'easy-reservations' ); ?><span
								class="dashicons dashicons-upload"></span></button>
				</p>
			</div>
			<?php
		}

		// Print the content now.
		echo wp_kses(
			ob_get_clean(),
			array(
				'div'    => array(
					'class' => array(),
				),
				'span'   => array(
					'class' => array(),
				),
				'p'      => array(),
				'a'      => array(
					'href'     => array(),
					'class'    => array(),
					'download' => array(),
				),
				'button' => array(
					'type'  => array(),
					'class' => array(),
				),
				'input'  => array(
					'type'   => array(),
					'name'   => array(),
					'id'     => array(),
					'accept' => array(),
				),
			),
		);
	}

	/**
	 * Metabox to show the cost dofference after any reservation is updated by the customer.
	 *
	 * @param WP_Post $post         WordPress post object.
	 * @param array   $metabox_data Metabox arguments.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_updated_reservation_order_cost_difference( $post, $metabox_data ) {
		$cost_difference     = ( ! empty( $metabox_data['args']['cost_difference'] ) ) ? $metabox_data['args']['cost_difference'] : '';
		$cost_difference_key = ( ! empty( $metabox_data['args']['cost_difference_key'] ) ) ? $metabox_data['args']['cost_difference_key'] : '';

		if ( ! empty( $cost_difference ) ) {
			if ( 'cost_difference_customer_payable' === $cost_difference_key ) {
				echo wp_kses(
					/* translators: 1: %s: strong tag open, 2: %s: strong tag closed, 3: %s: cost difference */
					sprintf( __( 'The customer needs to pay %1$s%3$s%2$s before onboarding.', 'easy-reservations' ), '<strong>', '</strong>', wc_price( $cost_difference ) ),
					array(
						'strong' => array(),
						'span'   => array(
							'class' => array(),
						),
					)
				);
			} elseif ( 'cost_difference_admin_payable' === $cost_difference_key ) {
				echo wp_kses(
					/* translators: 1: %s: strong tag open, 2: %s: strong tag closed, 3: %s: cost difference */
					sprintf( __( 'The admin shall refund %1$s%3$s%2$s after the reservation is complete.', 'easy-reservations' ), '<strong>', '</strong>', wc_price( $cost_difference ) ),
					array(
						'strong' => array(),
						'span'   => array(
							'class' => array(),
						),
					)
				);
			}
		}
	}

	/**
	 * Metabox for managing custom fields, for example, banner image.
	 */
	public function ersrv_posts_custom_fields_metabox() {
		require_once ERSRV_PLUGIN_PATH . 'admin/templates/metaboxes/custom-fields.php';
	}

	/**
	 * Hook the receipt option in order preview modal box.
	 *
	 * @param array  $actions  Holds the actions array.
	 * @param object $wc_order Holds the WooCommerce order object.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function ersrv_woocommerce_admin_order_preview_actions_callback( $actions, $wc_order ) {
		$order_id             = $wc_order->get_id();
		$is_reservation_order = ersrv_order_is_reservation( $wc_order ); // Check if the order has reservation items.

		// Return the actions if the order is not reservation order.
		if ( ! $is_reservation_order ) {
			return $actions;
		}

		// Check if the order status is allowed for receipts.
		$display_order_receipt = ersrv_should_display_receipt_button( $order_id );

		// Return the actions if the receipt button should not be displayed.
		if ( false === $display_order_receipt ) {
			return $actions;
		}

		// Add this action when the order is marked already complete.
		if ( empty( $actions ) ) {
			$actions['status']['group'] = __( 'Change status:', 'easy-reservations' );
		}

		$actions['status']['actions']['ersrv-reservation-receipt'] = array(
			'name'   => ersrv_get_plugin_settings( 'ersrv_easy_reservations_receipt_button_text' ),
			'url'    => ersrv_download_reservation_receipt_url( $order_id ),
			'title'  => ersrv_download_reservation_receipt_button_title( $order_id ),
			'action' => 'ersrv-reservation-receipt',
		);

		return $actions;
	}

	/**
	 * Hook the receipt option in order listing page in admin.
	 *
	 * @param array  $actions  Holds the actions array.
	 * @param object $wc_order Holds the WooCommerce order object.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function ersrv_woocommerce_admin_order_actions_callback( $actions, $wc_order ) {
		$order_id             = $wc_order->get_id();
		$is_reservation_order = ersrv_order_is_reservation( $wc_order ); // Check if the order has reservation items.

		// Return the actions if the order is not reservation order.
		if ( ! $is_reservation_order ) {
			return $actions;
		}

		// Display the edit action.
		if ( is_admin() ) {
			$edit_reservation_page_id          = ersrv_get_page_id( 'edit-reservation' );
			$edit_reservation_page_url         = get_permalink( $edit_reservation_page_id );
			$query_params                      = array(
				'action' => 'edit-reservation',
				'id'     => $order_id,
			);
			$actions['ersrv-reservation-edit'] = array(
				'url'    => add_query_arg( $query_params, $edit_reservation_page_url ),
				'name'   => '',
				/* translators: 1: %s: order id */
				'title'  => sprintf( __( 'Edit Reservation #%1$d', 'easy-reservations' ), $order_id ),
				'action' => 'ersrv-reservation-edit',
			);
		}

		// Check if the order status is allowed for receipts.
		$display_order_receipt = ersrv_should_display_receipt_button( $order_id );

		// Return the actions if the receipt button should not be displayed.
		if ( false === $display_order_receipt ) {
			return $actions;
		}

		// If it's the admin panel.
		if ( is_admin() ) {
			$actions['ersrv-reservation-receipt'] = array(
				'url'    => ersrv_download_reservation_receipt_url( $order_id ),
				'name'   => '',
				'title'  => ersrv_download_reservation_receipt_button_title( $order_id ),
				'action' => 'ersrv-reservation-receipt',
			);
		}

		return $actions;
	}

	/**
	 * Generate the button on order edit page.
	 *
	 * @param int $order_id Holds the order ID.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function ersrv_woocommerce_order_actions_end_callback( $order_id ) {
		$order_id = (int) $order_id;

		// Check if the order has reservation items.
		$wc_order             = wc_get_order( $order_id );
		$is_reservation_order = ersrv_order_is_reservation( $wc_order );

		// Return the actions if the order is not reservation order.
		if ( ! $is_reservation_order ) {
			return;
		}

		// Check if the order status is allowed for receipts.
		$display_order_receipt = ersrv_should_display_receipt_button( $order_id );

		// Generate the button.
		ob_start();
		?>
		<li class="wide ersrv-edit-order-actions">
			<h4><?php esc_html_e( 'Easy Reservations:', 'easy-reservations' ); ?></h4>
			<?php
			// Return the actions if the receipt button should not be displayed.
			if ( false !== $display_order_receipt ) {
				?>
				<a class="button" href="<?php echo esc_url( ersrv_download_reservation_receipt_url( $order_id ) ); ?>">
					<?php echo esc_html( ersrv_get_plugin_settings( 'ersrv_easy_reservations_receipt_button_text' ) ); ?>
				</a>
				<?php
			}
			?>

			<!-- SEND REMINDER -->
			<a class="button ersrv-send-reservation-reminder-single" href="#"><?php esc_html_e( 'Send Reminder', 'easy-reservations' ); ?></a>
		</li>
		<?php

		echo wp_kses_post( ob_get_clean() );
	}

	/**
	 * Actions to be performed when order is marked as completed.
	 *
	 * @param int $order_id Holds the order ID.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_woocommerce_order_status_completed_callback( $order_id ) {
		ersrv_email_reservation_receipt_order_status_change( $order_id );
	}

	/**
	 * Actions to be performed when order is marked as prcessing.
	 *
	 * @param int $order_id Holds the order ID.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_woocommerce_order_status_processing_callback( $order_id ) {
		ersrv_email_reservation_receipt_order_status_change( $order_id );
	}

	/**
	 * Actions to be performed when order is marked as refunded.
	 *
	 * @param int $order_id Holds the order ID.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_woocommerce_order_status_refunded_callback( $order_id ) {
		ersrv_email_reservation_receipt_order_status_change( $order_id );
	}

	/**
	 * Actions to be performed when order is marked as on-hold.
	 *
	 * @param int $order_id Holds the order ID.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_woocommerce_order_status_on_hold_callback( $order_id ) {
		ersrv_email_reservation_receipt_order_status_change( $order_id );
	}

	/**
	 * Actions to be performed when order is marked as pending payment.
	 *
	 * @param int $order_id Holds the order ID.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_woocommerce_order_status_pending_callback( $order_id ) {
		ersrv_email_reservation_receipt_order_status_change( $order_id );
	}

	/**
	 * Actions to be performed when order is marked as cancelled.
	 *
	 * @param int $order_id Holds the order ID.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_woocommerce_order_status_cancelled_callback( $order_id ) {
		ersrv_email_reservation_receipt_order_status_change( $order_id );
	}

	/**
	 * Display post states for the pages generated by this plugin.
	 *
	 * @param array   $post_states Post states array.
	 * @param WP_Post $post        Post object.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function ersrv_display_post_states_callback( $post_states, $post ) {
		if ( ersrv_get_page_id( 'search-reservations' ) === $post->ID ) {
			$post_states['ersrv_search_page'] = __( 'Easy Reservations: Search Page', 'easy-reservations' );
		}

		if ( ersrv_get_page_id( 'edit-reservation' ) === $post->ID ) {
			$post_states['ersrv_edit_reservation_page'] = __( 'Easy Reservations: Edit Reservation Page', 'easy-reservations' );
		}

		return $post_states;
	}

	/**
	 * Update the blocked dates format for all the reservation items when the option is updated.
	 *
	 * @param array $option Holds the WooCommerce setting data.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_woocommerce_update_option_callback( $option ) {
		// Check for the datepicker date format option ID.
		if ( ! empty( $option['id'] ) && 'ersrv_datepicker_date_format' === $option['id'] ) {
			// Change the date format of the reserved dates of all the reservation items.
			$reservation_items_query = ersrv_get_posts( 'product', 1, - 1 );
			$reservation_items       = $reservation_items_query->posts;

			// Return, if there are no reservation items.
			if ( empty( $reservation_items ) || ! is_array( $reservation_items ) ) {
				return;
			}

			// New date format.
			$new_date_format = filter_input( INPUT_POST, 'ersrv_datepicker_date_format', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );
			$old_date_format = ersrv_get_plugin_settings( 'ersrv_datepicker_date_format' );

			// If there is no change with the format, return.
			if ( $new_date_format === $old_date_format ) {
				return;
			}

			// Get the PHP date format.
			$php_date_format = ersrv_get_php_date_format( $new_date_format );

			// New reserved dates.
			$new_reserved_dates = array();

			// Iterate through the reservation items to update their reservation dates.
			foreach ( $reservation_items as $reservation_item_id ) {
				$reserved_dates = get_post_meta( $reservation_item_id, '_ersrv_reservation_blockout_dates', true );

				// Skip, if there are no reserved dates.
				if ( empty( $reserved_dates ) || ! is_array( $reserved_dates ) ) {
					continue;
				}

				// Iterate through the reserved dates.
				foreach ( $reserved_dates as $reserved_date ) {
					$new_reserved_dates[] = array(
						'date'    => gmdate( $php_date_format, strtotime( $reserved_date['date'] ) ),
						'message' => ( ! empty( $reserved_date['message'] ) ) ? $reserved_date['message'] : '',
					);
				}

				// Update the new data.
				update_post_meta( $reservation_item_id, '_ersrv_reservation_blockout_dates', $new_reserved_dates );
			}
		}
	}

	/**
	 * AJAX to add reservation to customer's google calendar.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_add_reservation_to_gcal_callback() {
		$action = filter_input( INPUT_POST, 'action', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );

		// Exit, if the action mismatches.
		if ( empty( $action ) || 'add_reservation_to_gcal' !== $action ) {
			echo esc_html( 0 );
			wp_die();
		}

		// Posted data.
		$order_id = (int) filter_input( INPUT_POST, 'order_id', FILTER_SANITIZE_NUMBER_INT );

		// Email the google candar invitation to customer's email address.
		ersrv_email_reservation_data_to_google_calendar( $order_id );

		// Send the response.
		$response = array(
			'code'          => 'google-calendar-email-sent',
			'toast_message' => __( 'Google calendar details have been emailed to the respective customer.', 'easy-reservations' ),
		);
		wp_send_json_success( $response );
		wp_die();
	}

	/**
	 * AJAX to add reservation to customer's icalendar.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_add_reservation_to_ical_callback() {
		$action = filter_input( INPUT_POST, 'action', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );

		// Exit, if the action mismatches.
		if ( empty( $action ) || 'add_reservation_to_ical' !== $action ) {
			echo esc_html( 0 );
			wp_die();
		}

		// Posted data.
		$order_id = (int) filter_input( INPUT_POST, 'order_id', FILTER_SANITIZE_NUMBER_INT );

		// Email the google candar invitation to customer's email address.
		ersrv_email_reservation_data_to_icalendar( $order_id );

		// Send the response.
		$response = array(
			'code'          => 'icalendar-email-sent',
			'toast_message' => __( 'iCalendar details have been emailed to the respective customer.', 'easy-reservations' ),
		);
		wp_send_json_success( $response );
		wp_die();
	}

	/**
	 * AJAX to decline reservation cancellation request.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_decline_reservation_cancellation_request_callback() {
		$action = filter_input( INPUT_POST, 'action', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );

		// Exit, if the action mismatches.
		if ( empty( $action ) || 'decline_reservation_cancellation_request' !== $action ) {
			echo esc_html( 0 );
			wp_die();
		}

		// Posted data.
		$line_item_id = (int) filter_input( INPUT_POST, 'line_item_id', FILTER_SANITIZE_NUMBER_INT );

		// Shoot the declinal request now.
		ersrv_decline_reservation_cancellation_request( $line_item_id );

		// Send the response.
		$response = array(
			'code'          => 'reservation-cancellation-request-declined',
			/* translators: 1: %s: anchor tag open, 2: %s: anchor tag closed */
			'toast_message' => sprintf( __( 'Reservation cancellation request declined. Click %1$shere%2$s to refresh the page.', 'easy-reservations' ), '<a href="' . admin_url( 'admin.php?page=reservation-cancellation-requests' ) . '">', '</a>' ),
		);
		wp_send_json_success( $response );
		wp_die();
	}

	/**
	 * AJAX to approve reservation cancellation request.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_approve_reservation_cancellation_request_callback() {
		$action = filter_input( INPUT_POST, 'action', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );

		// Exit, if the action mismatches.
		if ( empty( $action ) || 'approve_reservation_cancellation_request' !== $action ) {
			echo esc_html( 0 );
			wp_die();
		}

		// Posted data.
		$line_item_id = (int) filter_input( INPUT_POST, 'line_item_id', FILTER_SANITIZE_NUMBER_INT );
		$order_id     = (int) filter_input( INPUT_POST, 'order_id', FILTER_SANITIZE_NUMBER_INT );

		// Shoot the approval request now.
		ersrv_approve_reservation_cancellation_request( $order_id, $line_item_id );

		// Send the response.
		$response = array(
			'code'          => 'reservation-cancellation-request-approved',
			/* translators: 1: %s: anchor tag open, 2: %s: anchor tag closed */
			'toast_message' => sprintf( __( 'Reservation cancellation request approved. Click %1$shere%2$s to refresh the page.', 'easy-reservations' ), '<a href="' . admin_url( 'admin.php?page=reservation-cancellation-requests' ) . '">', '</a>' ),
		);
		wp_send_json_success( $response );
		wp_die();
	}

	/**
	 * AJAX to upload the driving license file on checkout.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_upload_driving_license_callback() {
		$action = filter_input( INPUT_POST, 'action', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );

		// Exit, if the action mismatches.
		if ( empty( $action ) || 'upload_driving_license' !== $action ) {
			echo esc_html( 0 );
			wp_die();
		}

		// Upload the file now.
		$driving_license_file_name = ( ! empty( $_FILES['driving_license_file']['name'] ) ) ? $_FILES['driving_license_file']['name'] : '';
		$driving_license_file_temp = ( ! empty( $_FILES['driving_license_file']['tmp_name'] ) ) ? $_FILES['driving_license_file']['tmp_name'] : '';
		$file_data                 = file_get_contents( $driving_license_file_temp );
		$filename                  = basename( $driving_license_file_name );
		$upload_dir                = wp_upload_dir();
		$file_path                 = ( ! empty( $upload_dir['path'] ) ) ? $upload_dir['path'] . $filename : $upload_dir['basedir'] . $filename;
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

		// Attach this attachment ID with the order ID.
		$order_id = filter_input( INPUT_POST, 'order_id', FILTER_SANITIZE_NUMBER_INT );
		update_post_meta( $order_id, 'reservation_driving_license_attachment_id', $attach_id );

		// Prepare the response.
		$response = array(
			'code'          => 'driving-license-uploaded',
			'toast_message' => __( 'Driving license is uploaded successfully. Reloading...', 'easy-reservations' ),
		);
		wp_send_json_success( $response );
		wp_die();
	}

	/**
	 * Delete the reserved dates before the reservation order is permanently deleted.
	 *
	 * @param int $post_id Holds the deleting post ID.
	 */
	public function ersrv_woocommerce_delete_order_callback( $post_id ) {
		// Flush out the reserved dates upon order deletion.
		ersrv_flush_out_reserved_dates( $post_id );
	}

	/**
	 * Add custom action to the bulk actions row.
	 *
	 * @param WC_Order $wc_order WooCommerce order object.
	 */
	public function ersrv_woocommerce_order_item_add_action_buttons_callback( $wc_order ) {
		// Add the edit button now.
		$edit_reservation_page_url = get_permalink( ersrv_get_page_id( 'edit-reservation' ) );
		$query_params              = array(
			'action' => 'edit-reservation',
			'id'     => $wc_order->get_id(),
		);
		?>
		<a class="button" title="<?php esc_html_e( 'Edit Reservation', 'easy-reservations' ); ?>" href="<?php echo esc_url( add_query_arg( $query_params, $edit_reservation_page_url ) ); ?>"><?php esc_html_e( 'Edit Reservation', 'easy-reservations' ); ?></a>
		<?php
	}

	/**
	 * AJAX to send reservation reminder emails.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_send_reservation_reminder_callback() {
		$action = filter_input( INPUT_POST, 'action', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );

		// Exit, if the action mismatches.
		if ( empty( $action ) || 'send_reservation_reminder' !== $action ) {
			echo esc_html( 0 );
			wp_die();
		}

		$order_id = filter_input( INPUT_POST, 'order_id', FILTER_SANITIZE_NUMBER_INT ); // Posted data.
		ersrv_send_reservarion_reminder_emails( $order_id, true ); // Send the reminder email.

		// Prepare the response.
		$response = array(
			'code'          => 'reminder-email-sent',
			'toast_message' => __( 'Reminder email has been sent successfully.', 'easy-reservations' ),
		);
		wp_send_json_success( $response );
		wp_die();
	}
}
