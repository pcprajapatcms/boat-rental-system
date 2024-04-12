<?php
/**
 * The admin-settings of the plugin.
 *
 * @link       https://www.cmsminds.com/
 * @since      1.0.0
 *
 * @package    Easy_Reservations
 * @subpackage Easy_Reservations/includes/classes
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

if ( class_exists( 'Easy_Reservations_Settings', false ) ) {
	return new Easy_Reservations_Settings();
}

/**
 * Class to manage the admin settings for the reservations.
 */
class Easy_Reservations_Settings extends WC_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'easy-reservations';
		$this->label = __( 'Easy Reservations', 'easy-reservations' );

		parent::__construct();
	}

	/**
	 * Get sections.
	 *
	 * @return array
	 */
	public function get_sections() {
		$sections = array_merge(
			array( '' => __( 'General', 'easy-reservations' ) ),
			array( 'invoice_receipts' => __( 'Invoice Receipts', 'easy-reservations' ) ),
			array( 'cancel_reservations' => __( 'Cancel Reservations', 'easy-reservations' ) ),
			array( 'edit_reservation' => __( 'Edit Reservation', 'easy-reservations' ) ),
		);

		return apply_filters( 'woocommerce_get_sections_' . $this->id, $sections );
	}

	/**
	 * Output the settings.
	 */
	public function output() {
		global $current_section;

		$settings = $this->get_settings( $current_section );
		WC_Admin_Settings::output_fields( $settings );
	}

	/**
	 * Save settings.
	 */
	public function save() {
		global $current_section;

		$settings = $this->get_settings( $current_section );
		WC_Admin_Settings::save_fields( $settings );

		if ( $current_section ) {
			do_action( 'woocommerce_update_options_' . $this->id . '_' . $current_section );
		}
	}

	/**
	 * Get settings array.
	 *
	 * @param string $current_section Current section name.
	 * @return array
	 */
	public function get_settings( $current_section = '' ) {
		switch ( $current_section ) {
			case 'invoice_receipts':
				$settings = $this->ersrv_invoice_receipts_settings_fields();
				break;

			case 'cancel_reservations':
				$settings = $this->ersrv_cancel_reservations_settings_fields();
				break;

			case 'edit_reservation':
				$settings = $this->ersrv_edit_reservation_settings_fields();
				break;

			default:
				$settings = $this->ersrv_general_settings_fields(); // Fields for the general section.
		}

		return apply_filters( 'woocommerce_get_settings_' . $this->id, $settings, $current_section );
	}

	/**
	 * Return the fields for general settings.
	 *
	 * @return array
	 */
	public function ersrv_general_settings_fields() {
		$fields = array(
			array(
				'title' => __( 'General', 'easy-reservations' ),
				'type'  => 'title',
				'desc'  => __( 'This section includes the reservation general settings.', 'easy-reservations' ),
				'id'    => 'ersrv_reservation_general_settings',
			),
			array(
				'title' => __( 'Driving License Validation', 'easy-reservations' ),
				'desc'  => __( 'This sets whether the driver needs to submit the driving license of the reservation or not.', 'easy-reservations' ),
				'id'    => 'ersrv_driving_license_validation',
				'type'  => 'checkbox',
			),
			array(
				'title' => __( 'Enable Calender Date With Time (For Hours)', 'easy-reservations' ),
				'desc'  => __( 'This option enable time with dates in calendar.', 'easy-reservations' ),
				'id'    => 'ersrv_enable_time_with_date',
				'type'  => 'checkbox',
			),
			array(
				'title' => __( 'Remove ".00" From Price', 'easy-reservations' ),
				'desc'  => __( 'This sets whether the extra zeros from the price should be removed or not.', 'easy-reservations' ),
				'id'    => 'ersrv_trim_zeros_from_price',
				'type'  => 'checkbox',
			),
			array(
				'title'       => __( 'Google Maps API Key', 'easy-reservations' ),
				'desc'        => __( 'This holds the google maps API key.', 'easy-reservations' ),
				'desc_tip'    => true,
				'id'          => 'ersrv_google_maps_api_key',
				'placeholder' => __( 'Alz.........', 'easy-reservations' ),
				'type'        => 'text',
			),
			array(
				'title'       => __( 'Reservation Reminder Email "X" Days Before', 'easy-reservations' ),
				'desc'        => __( 'This will decide that the reminder emails will be sent the number of days before the customer\'s reservation. If left blank, the system won\'t send any reminder email.', 'easy-reservations' ),
				'desc_tip'    => true,
				'id'          => 'ersrv_reminder_email_send_before_days',
				'placeholder' => __( 'E.g.: 2', 'easy-reservations' ),
				'type'        => 'number',
			),
			array(
				'name'     => __( 'Datepicker Date Fomat', 'easy-reservations' ),
				'type'     => 'select',
				'options'  => array(
					''           => __( 'Select a format', 'easy-reservations' ),
					'dd/mm/yy' => 'dd/mm/yy',
					'dd/yy/mm' => 'dd/yy/mm' ,
					'mm/dd/yy' => 'mm/dd/yy',
					'mm/yy/dd' => 'mm/yy/dd',
					'yy/dd/mm' => 'yy/dd/mm',
					'yy/mm/dd' => 'yy/mm/dd',
				),
				'class'    => 'wc-enhanced-select',
				'desc'     => __( 'Holds the datepicker date format.', 'easy-reservations' ),
				'desc_tip' => true,
				'default'  => '',
				'id'       => 'ersrv_datepicker_date_format',
			),
			array(
				'title'       => __( 'Reservation Onboarding Time', 'easy-reservations' ),
				'desc'        => __( 'This will be the normal onboarding time. Default is 09:00AM.', 'easy-reservations' ),
				'desc_tip'    => true,
				'id'          => 'ersrv_reservation_onboarding_time',
				'type'        => 'text',
				'placeholder' => __( 'hours:minutes', 'easy-reservations' ),
			),
			array(
				'title'       => __( 'Reservation Offboarding Time', 'easy-reservations' ),
				'desc'        => __( 'This will be the normal offboarding time. Default is 10:00AM.', 'easy-reservations' ),
				'desc_tip'    => true,
				'id'          => 'ersrv_reservation_offboarding_time',
				'type'        => 'text',
				'placeholder' => __( 'hours:minutes', 'easy-reservations' ),
			),
			array(
				'type' => 'sectionend',
				'id'   => 'ersrv_reservation_general_settings',
			),
			array(
				'title' => __( 'Archive Page', 'easy-reservations' ),
				'type'  => 'title',
				'desc'  => __( 'This section includes the settings related to woocommerce archive pages which include shop page, category and tags pages.', 'easy-reservations' ),
				'id'    => 'ersrv_wc_archive_page_settings',
			),
			array(
				'title'       => __( 'Add To Cart Button Text', 'easy-reservations' ),
				'desc'        => __( 'This holds the add to cart button text. Default: Reserve It', 'easy-reservations' ),
				'desc_tip'    => true,
				'id'          => 'ersrv_archive_page_add_to_cart_button_text',
				'placeholder' => __( 'E.g.: Reserve It', 'easy-reservations' ),
				'type'        => 'text',
			),
			array(
				'type' => 'sectionend',
				'id'   => 'ersrv_wc_archive_page_settings',
			),
			array(
				'title' => __( 'Product Single', 'easy-reservations' ),
				'type'  => 'title',
				'desc'  => __( 'This section includes the settings related to woocommerce product single pages.', 'easy-reservations' ),
				'id'    => 'ersrv_wc_product_single_page_settings',
			),
			array(
				'title'       => __( 'Add To Cart Button Text', 'easy-reservations' ),
				'desc'        => __( 'This holds the add to cart button text. Default: Reserve It', 'easy-reservations' ),
				'desc_tip'    => true,
				'id'          => 'ersrv_product_single_page_add_to_cart_button_text',
				'placeholder' => __( 'E.g.: Reserve It', 'easy-reservations' ),
				'type'        => 'text',
			),
			array(
				'type' => 'sectionend',
				'id'   => 'ersrv_wc_product_single_page_settings',
			),
			array(
				'title' => __( 'Rental Agreement', 'easy-reservations' ),
				'type'  => 'title',
				'desc'  => __( 'This section includes the settings related to rental agreement of the reservations.', 'easy-reservations' ),
				'id'    => 'ersrv_rental_agreement_settings',
			),
			array(
				'name' => __( 'Enable', 'easy-reservations' ),
				'type' => 'checkbox',
				'desc' => __( 'This will decide whether the rental agreement emails would be sent to the customers when they place their reservation orders. Default is no.', 'easy-reservations' ),
				'id'   => 'ersrv_enable_reservation_rental_agreement',
			),
			array(
				'title'       => __( 'Agreement File Attachment ID', 'easy-reservations' ),
				'desc'        => __( 'This holds the media ID of the agreement file uploaded to WordPress media page. This file would be sent along the agreement mail that will be sent to the customers as soon they place a reservation order.', 'easy-reservations' ),
				'desc_tip'    => true,
				'id'          => 'ersrv_rental_agreement_file_id',
				'placeholder' => __( 'E.g.: 99', 'easy-reservations' ),
				'type'        => 'number',
			),
			array(
				'type' => 'sectionend',
				'id'   => 'ersrv_rental_agreement_settings',
			),
		);

		/**
		 * This hook fires on the admin settings page - general section.
		 *
		 * This account help in managing general section plugin settings fields.
		 *
		 * @param array $fields Holds the fields array.
		 * @return array
		 */
		return apply_filters( 'ersrv_general_section_plugin_settings', $fields );
	}

	/**
	 * Return the fields for reservations receipts settings.
	 *
	 * @return array
	 */
	public function ersrv_invoice_receipts_settings_fields() {
		$fields = array(
			array(
				'title' => __( 'Store Details', 'easy-reservations' ),
				'type'  => 'title',
				'desc'  => '',
				'id'    => 'ersrv_invoice_receipts_store_details_settings',
			),
			array(
				'name'        => __( 'Name', 'easy-reservations' ),
				'type'        => 'text',
				/* translators: 1: %s: site title */
				'desc'        => sprintf( __( 'The store name that will be printed in the header section. Default is the site title (%1$s).', 'easy-reservations' ), get_bloginfo( 'title' ) ),
				'desc_tip'    => true,
				'id'          => 'ersrv_reservation_receipt_store_name',
				/* translators: 1: %s: site title */
				'placeholder' => sprintf( __( 'E.g.: %1$s', 'easy-reservations' ), get_bloginfo( 'title' ) ),
			),
			array(
				'name'        => __( 'Contact Number', 'easy-reservations' ),
				'type'        => 'text',
				'desc'        => __( 'Store\'s contact number that will be printed in the header.', 'easy-reservations' ),
				'desc_tip'    => true,
				'id'          => 'ersrv_reservation_receipt_store_contact_number',
				'placeholder' => __( 'E.g.: 9988776655', 'easy-reservations' ),
			),
			array(
				'name'        => __( 'Logo Media ID', 'easy-reservations' ),
				'type'        => 'number',
				'desc'        => __( 'This holds the store logo media ID from the media section. The logo will be printed in the header section.', 'easy-reservations' ),
				'desc_tip'    => true,
				'id'          => 'ersrv_reservation_receipt_store_logo_media_id',
				'placeholder' => __( 'E.g.: 99', 'easy-reservations' ),
			),
			array(
				'type' => 'sectionend',
				'id'   => 'ersrv_invoice_receipts_store_details_settings',
			),
			array(
				'title' => __( 'Reservation Receipt', 'easy-reservations' ),
				'type'  => 'title',
				'desc'  => '',
				'id'    => 'ersrv_reservation_invoice_receipts_settings',
			),
			array(
				'name'     => __( 'Enable Receipt For Order Statuses', 'easy-reservations' ),
				'type'     => 'multiselect',
				'options'  => wc_get_order_statuses(),
				'class'    => 'wc-enhanced-select',
				'desc'     => __( 'The order status on which the receipt will be available for download. Leave blank to allow for all statusses.', 'easy-reservations' ),
				'desc_tip' => true,
				'default'  => '',
				'id'       => 'ersrv_easy_reservations_receipt_for_order_statuses',
			),
			array(
				'title' => __( 'Display Button on My Account Order Listing Page', 'easy-reservations' ),
				'desc'  => __( 'This sets whether the receipt button is to be displayed on the order listing page on the customers portal.', 'easy-reservations' ),
				'id'    => 'ersrv_enable_receipt_button_my_account_orders_list',
				'type'  => 'checkbox',
			),
			array(
				'name'        => __( 'Receipt Button Text', 'easy-reservations' ),
				'type'        => 'text',
				'desc'        => __( 'This holds the receipt button text. Default: Download Reservation Receipt', 'easy-reservations' ),
				'desc_tip'    => true,
				'id'          => 'ersrv_easy_reservations_receipt_button_text',
				'placeholder' => __( 'E.g.: Download Reservation Receipt', 'easy-reservations' ),
			),
			array(
				'name'              => __( 'Thanks Note By Store', 'easy-reservations' ),
				'type'              => 'textarea',
				'desc'              => __( 'This holds the thanks note by the store printed on the receipt. Something like, Thanks for the reservation with us.', 'easy-reservations' ),
				'desc_tip'          => true,
				'id'                => 'ersrv_easy_reservations_reservation_thanks_note',
				'placeholder'       => __( 'E.g.: Thanks for the reservation with us.', 'easy-reservations' ),
				'custom_attributes' => array(
					'rows' => 4,
				),
			),
			array(
				'name'              => __( 'Receipt Footer Text', 'easy-reservations' ),
				'type'              => 'textarea',
				'desc'              => __( 'This holds the footer text printed on the receipt.', 'easy-reservations' ),
				'desc_tip'          => true,
				'id'                => 'ersrv_easy_reservations_receipt_footer_text',
				'placeholder'       => __( 'E.g.: Visit us online to....', 'easy-reservations' ),
				'custom_attributes' => array(
					'rows' => 4,
				),
			),
			array(
				'type' => 'sectionend',
				'id'   => 'ersrv_reservation_invoice_receipts_settings',
			),
		);

		/**
		 * This hook fires on the admin settings page - general section.
		 *
		 * This account help in managing general section plugin settings fields.
		 *
		 * @param array $fields Holds the fields array.
		 * @return array
		 */
		return apply_filters( 'ersrv_invoice_receipts_section_plugin_settings', $fields );
	}

	/**
	 * Return the fields for cancel reservations settings.
	 *
	 * @return array
	 */
	public function ersrv_cancel_reservations_settings_fields() {
		$fields = array(
			array(
				'title' => __( 'Cancel Reservations Settings', 'easy-reservations' ),
				'type'  => 'title',
				'desc'  => '',
				'id'    => 'ersrv_cancel_reservations_settings',
			),
			array(
				'name' => __( 'Enable', 'easy-reservations' ),
				'type' => 'checkbox',
				'desc' => __( 'This will decide whether the customers can apply for cancelling their reservations. Default is no.', 'easy-reservations' ),
				'id'   => 'ersrv_enable_reservation_cancellation',
			),
			array(
				'name'        => __( 'Button Text', 'easy-reservations' ),
				'type'        => 'text',
				'desc'        => __( 'This holds the request cancellation button text. Default: Request Cancellation', 'easy-reservations' ),
				'desc_tip'    => true,
				'id'          => 'ersrv_cancel_reservations_button_text',
				'placeholder' => __( 'E.g.: Request Cancellation', 'easy-reservations' ),
			),
			array(
				'title'       => __( 'Reservations Eligibility for Cancellation Until "X" Days', 'easy-reservations' ),
				'desc'        => __( 'The number of days until which the customers can raise cancel request towards their reservations. Leaving it blank would mean that customers would be allowed to cancel their reservation anytime.', 'easy-reservations' ),
				'desc_tip'    => true,
				'id'          => 'ersrv_cancel_reservation_request_before_days',
				'placeholder' => __( 'E.g.: 2', 'easy-reservations' ),
				'type'        => 'number',
			),
			array(
				'type' => 'sectionend',
				'id'   => 'ersrv_cancel_reservations_settings',
			),
		);

		/**
		 * This hook fires on the admin settings page - cancel reservations section.
		 *
		 * This account help in managing cancel reservations section plugin settings fields.
		 *
		 * @param array $fields Holds the fields array.
		 * @return array
		 */
		return apply_filters( 'ersrv_cancel_reservations_section_plugin_settings', $fields );
	}

	/**
	 * Return the fields for edit reservations settings.
	 *
	 * @return array
	 */
	public function ersrv_edit_reservation_settings_fields() {
		$fields = array(
			array(
				'title' => __( 'Edit Reservation Settings', 'easy-reservations' ),
				'type'  => 'title',
				'desc'  => '',
				'id'    => 'ersrv_edit_reservation_settings',
			),
			array(
				'name' => __( 'Enable', 'easy-reservations' ),
				'type' => 'checkbox',
				'desc' => __( 'This will decide whether the customers can edit their reservations. Default is no.', 'easy-reservations' ),
				'id'   => 'ersrv_enable_reservation_edit',
			),
			array(
				'name'        => __( 'Button Text', 'easy-reservations' ),
				'type'        => 'text',
				'desc'        => __( 'This holds the edit reservation button text. Default: Edit Reservation', 'easy-reservations' ),
				'desc_tip'    => true,
				'id'          => 'ersrv_edit_reservation_button_text',
				'placeholder' => __( 'E.g.: Edit Reservation', 'easy-reservations' ),
			),
			array(
				'type' => 'sectionend',
				'id'   => 'ersrv_edit_reservation_settings',
			),
		);

		/**
		 * This hook fires on the admin settings page - edit reservation section.
		 *
		 * This account help in managing edit reservation section plugin settings fields.
		 *
		 * @param array $fields Holds the fields array.
		 * @return array
		 */
		return apply_filters( 'ersrv_edit_reservation_section_plugin_settings', $fields );
	}
}

return new Easy_Reservations_Settings();
