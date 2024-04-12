<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.cmsminds.com/
 * @since      1.0.0
 *
 * @package    Easy_Reservations
 * @subpackage Easy_Reservations/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Easy_Reservations
 * @subpackage Easy_Reservations/includes
 * @author     cmsMinds <info@cmsminds.com>
 */
class Easy_Reservations {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Easy_Reservations_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		$this->version     = ( defined( 'ERSRV_PLUGIN_VERSION' ) ) ? ERSRV_PLUGIN_VERSION : '1.0.0';
		$this->plugin_name = 'easy-reservations';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Easy_Reservations_Loader. Orchestrates the hooks of the plugin.
	 * - Easy_Reservations_I18n. Defines internationalization functionality.
	 * - Easy_Reservations_Admin. Defines all hooks for the admin area.
	 * - Easy_Reservations_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		// The class responsible for orchestrating the actions and filters of the core plugin.
		require_once ERSRV_PLUGIN_PATH . 'includes/class-easy-reservations-loader.php';

		// The class responsible for defining internationalization functionality of the plugin.
		require_once ERSRV_PLUGIN_PATH . 'includes/class-easy-reservations-i18n.php';

		// The file is responsible for defining all custom functions.
		require_once ERSRV_PLUGIN_PATH . 'includes/easy-reservations-functions.php';

		// The file is responsible for defining custom email notification for contacting owner of the reservation item.
		require_once ERSRV_PLUGIN_PATH . 'includes/classes/emails/class-easy-reservations-custom-emails-manager.php';

		// The class responsible for defining all actions that occur in the admin area.
		require_once ERSRV_PLUGIN_PATH . 'admin/class-easy-reservations-admin.php';

		// The class responsible for defining all actions that occur in the public-facing side of the site.
		require_once ERSRV_PLUGIN_PATH . 'public/class-easy-reservations-public.php';

		$this->loader = new Easy_Reservations_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Easy_Reservations_I18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {
		$plugin_i18n = new Easy_Reservations_I18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		$plugin_admin = new Easy_Reservations_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'ersrv_admin_enqueue_scripts_callback' );
		$this->loader->add_filter( 'product_type_selector', $plugin_admin, 'ersrv_product_type_selector_callback' );
		$this->loader->add_filter( 'woocommerce_product_data_tabs', $plugin_admin, 'ersrv_woocommerce_product_data_tabs_callback' );
		$this->loader->add_action( 'woocommerce_product_data_panels', $plugin_admin, 'ersrv_woocommerce_product_data_panels_callback' );
		$this->loader->add_action( 'woocommerce_process_product_meta', $plugin_admin, 'ersrv_woocommerce_process_product_meta_callback' );
		$this->loader->add_action( 'admin_footer', $plugin_admin, 'ersrv_admin_footer_callback' );
		$this->loader->add_action( 'wp_ajax_export_reservations', $plugin_admin, 'ersrv_export_reservations_callback' );
		$this->loader->add_filter( 'woocommerce_get_settings_pages', $plugin_admin, 'ersrv_woocommerce_get_settings_pages_callback' );
		$this->loader->add_filter( 'plugin_row_meta', $plugin_admin, 'ersrv_plugin_row_meta_callback', 10, 2 );
		$this->loader->add_action( 'wp_ajax_get_amenity_html', $plugin_admin, 'ersrv_get_amenity_html_callback' );
		$this->loader->add_action( 'widgets_init', $plugin_admin, 'ersrv_widgets_init_callback' );
		$this->loader->add_action( 'wp_ajax_get_blockout_date_html', $plugin_admin, 'ersrv_get_blockout_date_html_callback' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'ersrv_admin_menu_callback' );
		$this->loader->add_filter( 'set-screen-option', $plugin_admin, 'ersrv_set_screen_option_callback', 10, 3 );
		$this->loader->add_action( 'wp_ajax_register_new_customer', $plugin_admin, 'ersrv_register_new_customer_callback' );
		$this->loader->add_action( 'wp_ajax_generate_new_password', $plugin_admin, 'ersrv_generate_new_password_callback' );
		$this->loader->add_action( 'wp_ajax_get_reservable_item_details', $plugin_admin, 'ersrv_get_reservable_item_details_callback' );
		$this->loader->add_action( 'wp_ajax_create_reservation', $plugin_admin, 'ersrv_create_reservation_callback' );
		$this->loader->add_action( 'wp_ajax_get_states', $plugin_admin, 'ersrv_get_states_callback' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'ersrv_save_post_callback' );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'ersrv_add_meta_boxes_callback' );
		$this->loader->add_filter( 'woocommerce_admin_order_preview_actions', $plugin_admin, 'ersrv_woocommerce_admin_order_preview_actions_callback', 20, 2 );
		$this->loader->add_filter( 'woocommerce_admin_order_actions', $plugin_admin, 'ersrv_woocommerce_admin_order_actions_callback', 10, 2 );
		$this->loader->add_action( 'woocommerce_order_actions_end', $plugin_admin, 'ersrv_woocommerce_order_actions_end_callback' );
		$this->loader->add_action( 'woocommerce_order_status_completed', $plugin_admin, 'ersrv_woocommerce_order_status_completed_callback' );
		$this->loader->add_action( 'woocommerce_order_status_processing', $plugin_admin, 'ersrv_woocommerce_order_status_processing_callback' );
		$this->loader->add_action( 'woocommerce_order_status_refunded', $plugin_admin, 'ersrv_woocommerce_order_status_refunded_callback' );
		$this->loader->add_action( 'woocommerce_order_status_on-hold', $plugin_admin, 'ersrv_woocommerce_order_status_on_hold_callback' );
		$this->loader->add_action( 'woocommerce_order_status_pending', $plugin_admin, 'ersrv_woocommerce_order_status_pending_callback' );
		$this->loader->add_action( 'woocommerce_order_status_cancelled', $plugin_admin, 'ersrv_woocommerce_order_status_cancelled_callback' );
		$this->loader->add_filter( 'display_post_states', $plugin_admin, 'ersrv_display_post_states_callback', 20, 2 );
		$this->loader->add_action( 'woocommerce_update_option', $plugin_admin, 'ersrv_woocommerce_update_option_callback' );
		$this->loader->add_action( 'wp_ajax_add_reservation_to_gcal', $plugin_admin, 'ersrv_add_reservation_to_gcal_callback' );
		$this->loader->add_action( 'wp_ajax_add_reservation_to_ical', $plugin_admin, 'ersrv_add_reservation_to_ical_callback' );
		$this->loader->add_action( 'wp_ajax_decline_reservation_cancellation_request', $plugin_admin, 'ersrv_decline_reservation_cancellation_request_callback' );
		$this->loader->add_action( 'wp_ajax_approve_reservation_cancellation_request', $plugin_admin, 'ersrv_approve_reservation_cancellation_request_callback' );
		$this->loader->add_action( 'wp_ajax_upload_driving_license', $plugin_admin, 'ersrv_upload_driving_license_callback' );
		$this->loader->add_action( 'woocommerce_delete_order', $plugin_admin, 'ersrv_woocommerce_delete_order_callback' );
		$this->loader->add_action( 'woocommerce_order_item_add_action_buttons', $plugin_admin, 'ersrv_woocommerce_order_item_add_action_buttons_callback', 20 );
		$this->loader->add_action( 'wp_ajax_send_reservation_reminder', $plugin_admin, 'ersrv_send_reservation_reminder_callback' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
		$plugin_public      = new Easy_Reservations_Public( $this->get_plugin_name(), $this->get_version() );
		$fav_items_endpoint = ersrv_get_account_endpoint_favourite_reservations();

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'ersrv_wp_enqueue_scripts_callback', 99 );
		$this->loader->add_action( 'init', $plugin_public, 'ersrv_init_callback' );
		$this->loader->add_action( 'woocommerce_thankyou', $plugin_public, 'ersrv_woocommerce_thankyou_callback' );
		$this->loader->add_filter( 'ersrv_posts_args', $plugin_public, 'ersrv_ersrv_posts_args_callback' );
		$this->loader->add_action( 'wp_ajax_get_item_unavailable_dates', $plugin_public, 'ersrv_get_item_unavailable_dates_callback' );
		$this->loader->add_action( 'wp_ajax_nopriv_get_item_unavailable_dates', $plugin_public, 'ersrv_get_item_unavailable_dates_callback' );
		$this->loader->add_action( 'wp_head', $plugin_public, 'ersrv_wp_head_callback' );
		$this->loader->add_filter( 'woocommerce_product_add_to_cart_text', $plugin_public, 'ersrv_woocommerce_product_add_to_cart_text_callback', 10, 2 );
		$this->loader->add_filter( 'woocommerce_related_products', $plugin_public, 'ersrv_woocommerce_related_products_callback', 20, 2 );
		$this->loader->add_filter( 'template_include', $plugin_public, 'ersrv_template_include_callback', 99 );
		$this->loader->add_shortcode( 'ersrv_search_reservations', $plugin_public, 'ersrv_ersrv_search_reservations_callback' );
		$this->loader->add_action( 'ersrv_delete_reservation_pdf_receipts', $plugin_public, 'ersrv_ersrv_delete_reservation_pdf_receipts_callback' );
		$this->loader->add_action( 'ersrv_reservation_reminder_email_notifications', $plugin_public, 'ersrv_ersrv_reservation_reminder_email_notifications_callback' );
		$this->loader->add_filter( 'woocommerce_my_account_my_orders_actions', $plugin_public, 'ersrv_woocommerce_my_account_my_orders_actions_callback', 10, 2 );
		$this->loader->add_action( 'woocommerce_order_details_after_order_table', $plugin_public, 'ersrv_woocommerce_order_details_after_order_table_callback' );
		$this->loader->add_action( 'dokan_order_detail_after_order_items', $plugin_public, 'ersrv_dokan_order_detail_after_order_items_callback' );
		$this->loader->add_action( 'wp_ajax_item_favourite', $plugin_public, 'ersrv_item_favourite_callback' );
		$this->loader->add_action( 'wp_footer', $plugin_public, 'ersrv_wp_footer_callback' );
		$this->loader->add_filter( 'woocommerce_account_menu_items', $plugin_public, 'ersrv_woocommerce_account_menu_items_callback' );
		$this->loader->add_action( "woocommerce_account_{$fav_items_endpoint}_endpoint", $plugin_public, 'ersrv_woocommerce_account_fav_items_endpoint_endpoint_callback' );
		$this->loader->add_filter( 'query_vars', $plugin_public, 'ersrv_query_vars_callback' );
		$this->loader->add_action( 'wp_ajax_loadmore_reservation_items', $plugin_public, 'ersrv_loadmore_reservation_items_callback' );
		$this->loader->add_action( 'wp_ajax_nopriv_loadmore_reservation_items', $plugin_public, 'ersrv_loadmore_reservation_items_callback' );
		$this->loader->add_action( 'wp_ajax_add_reservation_to_cart', $plugin_public, 'ersrv_add_reservation_to_cart_callback' );
		$this->loader->add_action( 'wp_ajax_nopriv_add_reservation_to_cart', $plugin_public, 'ersrv_add_reservation_to_cart_callback' );
		$this->loader->add_action( 'wp_ajax_submit_contact_owner_request', $plugin_public, 'ersrv_submit_contact_owner_request_callback' );
		$this->loader->add_action( 'wp_ajax_nopriv_submit_contact_owner_request', $plugin_public, 'ersrv_submit_contact_owner_request_callback' );
		$this->loader->add_action( 'woocommerce_init', $plugin_public, 'ersrv_woocommerce_init_callback' );
		$this->loader->add_filter( 'woocommerce_add_cart_item_data', $plugin_public, 'ersrv_woocommerce_add_cart_item_data_callback', 20, 2 );
		$this->loader->add_action( 'woocommerce_before_calculate_totals', $plugin_public, 'ersrv_woocommerce_before_calculate_totals_callback' );
		$this->loader->add_filter( 'woocommerce_get_item_data', $plugin_public, 'ersrv_woocommerce_get_item_data_callback', 20, 2 );
		$this->loader->add_action( 'woocommerce_checkout_create_order_line_item', $plugin_public, 'ersrv_woocommerce_checkout_create_order_line_item_callback', 20, 4 );
		$this->loader->add_action( 'wp_ajax_quick_view_item_data', $plugin_public, 'ersrv_quick_view_item_data_callback' );
		$this->loader->add_action( 'wp_ajax_nopriv_quick_view_item_data', $plugin_public, 'ersrv_quick_view_item_data_callback' );
		$this->loader->add_filter( 'woocommerce_cart_item_quantity', $plugin_public, 'ersrv_woocommerce_cart_item_quantity_callback', 20, 3 );
		$this->loader->add_action( 'wp_ajax_add_reservation_to_gcal', $plugin_public, 'ersrv_add_reservation_to_gcal_callback' );
		$this->loader->add_action( 'wp_ajax_nopriv_add_reservation_to_gcal', $plugin_public, 'ersrv_add_reservation_to_gcal_callback' );
		$this->loader->add_action( 'wp_ajax_add_reservation_to_ical', $plugin_public, 'ersrv_add_reservation_to_ical_callback' );
		$this->loader->add_action( 'wp_ajax_nopriv_add_reservation_to_ical', $plugin_public, 'ersrv_add_reservation_to_ical_callback' );
		$this->loader->add_action( 'woocommerce_after_order_notes', $plugin_public, 'ersrv_woocommerce_after_order_notes_callback' );
		$this->loader->add_action( 'wp_ajax_upload_driving_license_checkout', $plugin_public, 'ersrv_upload_driving_license_checkout_callback' );
		$this->loader->add_action( 'wp_ajax_nopriv_upload_driving_license_checkout', $plugin_public, 'ersrv_upload_driving_license_checkout_callback' );
		$this->loader->add_action( 'woocommerce_checkout_process', $plugin_public, 'ersrv_woocommerce_checkout_process_callback' );
		$this->loader->add_action( 'woocommerce_checkout_update_order_meta', $plugin_public, 'ersrv_woocommerce_checkout_update_order_meta_callback' );
		$this->loader->add_action( 'woocommerce_order_item_meta_end', $plugin_public, 'ersrv_woocommerce_order_item_meta_end_callback', 20, 4 );
		$this->loader->add_action( 'wp_ajax_request_reservation_cancel', $plugin_public, 'ersrv_request_reservation_cancel_callback' );
		$this->loader->add_action( 'wp_ajax_nopriv_request_reservation_cancel', $plugin_public, 'ersrv_request_reservation_cancel_callback' );
		$this->loader->add_shortcode( 'ersrv_edit_reservation', $plugin_public, 'ersrv_ersrv_edit_reservation_callback' );
		$this->loader->add_filter( 'woocommerce_billing_fields', $plugin_public, 'ersrv_woocommerce_billing_fields_callback' );
		$this->loader->add_filter( 'body_class', $plugin_public, 'ersrv_body_class_callback', 20 );
		$this->loader->add_action( 'ersrv_edit_reservation_after_main_title', $plugin_public, 'ersrv_ersrv_edit_reservation_after_main_title_callback' );
		$this->loader->add_action( 'wp_ajax_edit_reservation_initiate_datepicker', $plugin_public, 'ersrv_edit_reservation_initiate_datepicker_callback' );
		$this->loader->add_action( 'wp_ajax_nopriv_edit_reservation_initiate_datepicker', $plugin_public, 'ersrv_edit_reservation_initiate_datepicker_callback' );
		$this->loader->add_action( 'wp_ajax_update_reservation', $plugin_public, 'ersrv_update_reservation_callback' );
		$this->loader->add_action( 'wp_ajax_nopriv_update_reservation', $plugin_public, 'ersrv_update_reservation_callback' );
		$this->loader->add_filter( 'woocommerce_price_trim_zeros', $plugin_public, 'ersrv_woocommerce_price_trim_zeros_callback' );
		$this->loader->add_action( 'wp_ajax_search_reservations', $plugin_public, 'ersrv_search_reservations_callback' );
		$this->loader->add_action( 'wp_ajax_nopriv_search_reservations', $plugin_public, 'ersrv_search_reservations_callback' );
		$this->loader->add_filter( 'ersrv_display_receipt_button', $plugin_public, 'ersrv_ersrv_display_receipt_button_callback' );
		$this->loader->add_action( 'ersrv_add_reservation_to_cart_before', $plugin_public, 'ersrv_ersrv_add_reservation_to_cart_before_callback' );
		$this->loader->add_action( 'wp', $plugin_public, 'ersrv_wp_callback' );
		$this->loader->add_action( 'wp_ajax_remove_uploaded_driving_license', $plugin_public, 'ersrv_remove_uploaded_driving_license_callback' );
		$this->loader->add_action( 'wp_ajax_nopriv_remove_uploaded_driving_license', $plugin_public, 'ersrv_remove_uploaded_driving_license_callback' );
		$this->loader->add_filter( 'pre_get_posts', $plugin_public, 'ersrv_pre_get_posts_callback' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Easy_Reservations_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
