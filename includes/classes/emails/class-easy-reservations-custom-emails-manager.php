<?php
/**
 * Custom email templates manager class.
 *
 * @link       https://www.cmsminds.com/
 * @since      1.0.0
 *
 * @package    Easy_Reservations
 * @subpackage Easy_Reservations/includes/classes/emails
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Custom email templates manager class.
 *
 * Defines the custom email templates and notifications.
 *
 * @package    Easy_Reservations
 * @subpackage Easy_Reservations/includes/classes/emails
 * @author     cmsMinds <info@cmsminds.com>
 */
class Easy_Reservations_Custom_Email_Manager {
	/**
	 * Constructor to help define actions.
	 */
	public function __construct() {
		define( 'ERSRV_CUSTOM_EMAIL_TEMPLATE_PATH', ERSRV_PLUGIN_PATH . 'admin/templates/emails/' );
		add_action( 'ersrv_email_contact_owner_request', array( &$this, 'ersrv_ersrv_email_contact_owner_request_callback' ) );
		add_action( 'ersrv_send_reservation_reminder_email', array( &$this, 'ersrv_ersrv_send_reservation_reminder_email_callback' ), 10, 2 );
		add_action( 'ersrv_email_after_reservation_cancellation_request', array( &$this, 'ersrv_ersrv_email_after_reservation_cancellation_request_callback' ), 10, 2 );
		add_action( 'ersrv_after_reservation_cancellation_request_approved', array( &$this, 'ersrv_ersrv_after_reservation_cancellation_request_approved_callback' ) );
		add_action( 'ersrv_after_reservation_cancellation_request_declined', array( &$this, 'ersrv_ersrv_after_reservation_cancellation_request_declined_callback' ) );
		add_action( 'ersrv_after_blocking_reservation_dates', array( $this, 'ersrv_ersrv_after_blocking_reservation_dates_callback' ), 20, 2 );
		add_action( 'ersrv_update_reservation', array( $this, 'ersrv_ersrv_update_reservation_callback' ), 10, 2 );
		add_filter( 'woocommerce_email_classes', array( &$this, 'ersrv_woocommerce_email_classes_callback' ) );
	}

	/**
	 * Send notification as soon someone contact reservation item via contact form.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_ersrv_email_contact_owner_request_callback( $item_author_email ) {
		new WC_Emails();
		/**
		 * This action fires when someone submits contact request on some reservation item.
		 *
		 * @param string $item_author_email Item author email.
		 * @since 1.0.0
		 */
		do_action( 'ersrv_send_reservation_item_contact_request_notification', $item_author_email );
	}

	/**
	 * Send notification for the reservation reminder to the customers.
	 *
	 * @param object $line_item WooCommerce line item object.
	 * @param int    $order_id WooCommerce order ID.
	 * @since 1.0.0
	 */
	public function ersrv_ersrv_send_reservation_reminder_email_callback( $line_item, $order_id ) {
		new WC_Emails();
		/**
		 * This hook fires on the reminder emails cron.
		 *
		 * This hook is helpful in managing actions while sending emails.
		 *
		 * @param object $line_item WooCommerce line item object.
		 * @param int $order_id WooCommerce order ID.
		 * @since 1.0.0 
		 */
		do_action( 'ersrv_send_reservation_reminder_notification', $line_item, $order_id );
	}

	/**
	 * Send notification for the reservation cancellation request to the site administrator.
	 *
	 * @param object $line_item_id WooCommerce line item id.
	 * @param int    $order_id WooCommerce order ID.
	 * @since 1.0.0
	 */
	public function ersrv_ersrv_email_after_reservation_cancellation_request_callback( $line_item_id, $order_id ) {
		new WC_Emails();
		/**
		 * This hook fires when there is new cancellation request for any reservation.
		 *
		 * This hook is helpful in managing actions while sending emails.
		 *
		 * @param int $line_item_id WooCommerce line item id.
		 * @param int $order_id WooCommerce order ID.
		 * @since 1.0.0 
		 */
		do_action( 'ersrv_send_reservation_cancellation_request_notification', $line_item_id, $order_id );
	}

	/**
	 * Send notification for the reservation cancellation request approval to the customer.
	 *
	 * @param object $line_item_id WooCommerce line item id.
	 * @since 1.0.0
	 */
	public function ersrv_ersrv_after_reservation_cancellation_request_approved_callback( $line_item_id ) {
		new WC_Emails();
		/**
		 * This hook fires when there is cancellation request approval for any reservation.
		 *
		 * This hook is helpful in managing actions while sending emails.
		 *
		 * @param int $line_item_id WooCommerce line item id.
		 * @since 1.0.0 
		 */
		do_action( 'ersrv_send_reservation_cancellation_request_approved_notification', $line_item_id );
	}

	/**
	 * Send notification for the reservation cancellation request declinal to the customer.
	 *
	 * @param object $line_item_id WooCommerce line item id.
	 * @since 1.0.0
	 */
	public function ersrv_ersrv_after_reservation_cancellation_request_declined_callback( $line_item_id ) {
		new WC_Emails();
		/**
		 * This hook fires when there is cancellation request declinal for any reservation.
		 *
		 * This hook is helpful in managing actions while sending emails.
		 *
		 * @param int $line_item_id WooCommerce line item id.
		 * @since 1.0.0 
		 */
		do_action( 'ersrv_send_reservation_cancellation_request_declined_notification', $line_item_id );
	}

	/**
	 * Send notificaiton for the rental agreement signature to the customer.
	 *
	 * @param int      $order_id WooCommerce order id.
	 * @param WC_Order $wc_order WooCommerce order.
	 * @since 1.0.0
	 */
	public function ersrv_ersrv_after_blocking_reservation_dates_callback( $order_id, $wc_order ) {
		// Check if the rental agreement emails are configured to be sent.
		$enable_reservation_rental_agreement = ersrv_get_plugin_settings( 'ersrv_enable_reservation_rental_agreement' );

		// Return, if the email is not to be sent.
		if ( empty( $enable_reservation_rental_agreement ) || 'no' === $enable_reservation_rental_agreement ) {
			return;
		}

		// Check if the agreement file is provided.
		$agreement_file = ersrv_get_plugin_settings( 'ersrv_rental_agreement_file_id' );

		// Return, if there is no agreement file configured.
		if ( -1 === $agreement_file ) {
			return;
		}

		new WC_Emails();
		/**
		 * This hook fires when there is a new reservation order placed.
		 *
		 * This hook is helpful in managing actions while sending emails.
		 *
		 * @param int      $order_id WooCommerce order id.
		 * @param WC_Order $wc_order WooCommerce order.
		 * @since 1.0.0 
		 */
		do_action( 'ersrv_send_rental_agreement_notification', $order_id, $wc_order );
	}

	/**
	 * Send notificaiton when the reservation is updated.
	 *
	 * @param int      $order_id WooCommerce order id.
	 * @param WC_Order $wc_order WooCommerce order.
	 * @since 1.0.0
	 */
	public function ersrv_ersrv_update_reservation_callback( $order_id, $wc_order ) {
		new WC_Emails();
		/**
		 * This hook fires when there is a new reservation order placed.
		 *
		 * This hook is helpful in managing actions while sending emails.
		 *
		 * @param int      $order_id WooCommerce order id.
		 * @param WC_Order $wc_order WooCommerce order.
		 * @since 1.0.0 
		 */
		do_action( 'ersrv_send_update_reservation_notification', $order_id, $wc_order );
	}

	/**
	 * Add custom class to send reservation emails.
	 *
	 * @param array $email_classes Email classes array.
	 * @return array
	 * @since 1.0.0
	 */
	public function ersrv_woocommerce_email_classes_callback( $email_classes ) {
		// Contact owner email.
		require_once 'class-reservation-contact-owner-email.php'; // Require the class file.
		$email_classes['Reservation_Contact_Owner_Email'] = new Reservation_Contact_Owner_Email(); // Put in the classes into existing classes.

		// Reservation remminder email.
		require_once 'class-reservation-reminder-email.php'; // Require the class file.
		$email_classes['Reservation_Reminder_Email'] = new Reservation_Reminder_Email(); // Put in the classes into existing classes.

		// Reservation cancellation request email.
		require_once 'class-reservation-cancellation-request-email.php'; // Require the class file.
		$email_classes['Reservation_Cancellation_Request_Email'] = new Reservation_Cancellation_Request_Email(); // Put in the classes into existing classes.

		// Reservation cancellation request declined email.
		require_once 'class-reservation-cancellation-request-declined-email.php'; // Require the class file.
		$email_classes['Reservation_Cancellation_Request_Declined_Email'] = new Reservation_Cancellation_Request_Declined_Email(); // Put in the classes into existing classes.

		// Reservation cancellation request approved email.
		require_once 'class-reservation-cancellation-request-approved-email.php'; // Require the class file.
		$email_classes['Reservation_Cancellation_Request_Approved_Email'] = new Reservation_Cancellation_Request_Approved_Email(); // Put in the classes into existing classes.

		// Rental agreement email.
		require_once 'class-rental-agreement-email.php'; // Require the class file.
		$email_classes['Rental_Agreement_Email'] = new Rental_Agreement_Email(); // Put in the classes into existing classes.

		// Update reservation email.
		require_once 'class-update-reservation-email.php'; // Require the class file.
		$email_classes['Update_Reservation_Email'] = new Update_Reservation_Email(); // Put in the classes into existing classes.

		return $email_classes;
	}
}

new Easy_Reservations_Custom_Email_Manager();
