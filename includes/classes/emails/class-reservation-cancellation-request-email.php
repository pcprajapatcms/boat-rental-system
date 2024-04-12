<?php
/**
 * Reservation cancellation request email class.
 *
 * @link       https://www.cmsminds.com/
 * @since      1.0.0
 *
 * @package    Easy_Reservations
 * @subpackage Easy_Reservations/includes/classes/emails
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Reservation cancellation request email class.
 *
 * @package    Easy_Reservations
 * @subpackage Easy_Reservations/includes/classes/emails
 * @author     cmsMinds <info@cmsminds.com>
 * @since      1.0.0
 * @extends \WC_Email
 */
class Reservation_Cancellation_Request_Email extends WC_Email {
	/**
	 * Set email defaults.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		// Email slug we can use to filter other data.
		$this->id          = 'reservation_cancellation_request_email';
		$this->title       = __( 'Easy Reservations: Reservation Cancellation Request Email', 'easy-reservations' );
		$this->description = __( 'An email sent to the site administrator when any customer raises cancellation request.', 'easy-reservations' );

		// For admin area to let the user know we are sending this email to customers.
		$this->customer_email = false;
		$this->heading        = __( 'Reservation Cancellation Request', 'easy-reservations' );

		// translators: placeholder is {blogname}, a variable that will be substituted when email is sent out.
		$this->subject = sprintf( _x( '[%s] Reservation Cancellation Request', 'default email subject for reservation cancellation requests being sent to the adminstrator', 'easy-reservations' ), '{blogname}' );

		// Template paths.
		$this->template_html  = 'reservation-cancellation-request-html.php';
		$this->template_plain = 'plain/reservation-cancellation-request-plain.php';

		add_action( 'ersrv_send_reservation_cancellation_request_notification', array( $this, 'ersrv_ersrv_send_reservation_cancellation_request_notification_callback' ), 10, 2 );

		// Call parent constructor.
		parent::__construct();

		// Template base path.
		$this->template_base = ERSRV_CUSTOM_EMAIL_TEMPLATE_PATH;

		// Recipient.
		$this->recipient = $this->get_option( 'recipient' );
	}

	/**
	 * This callback helps fire the email notification.
	 *
	 * @param object $line_item_id WooCommerce line item ID.
	 * @param int    $order_id WooCommerce order ID.
	 * @since 1.0.0
	 */
	public function ersrv_ersrv_send_reservation_cancellation_request_notification_callback( $line_item_id, $order_id ) {
		// Get the order.
		$wc_order = wc_get_order( $order_id );

		// Email data object.
		$this->object = $this->create_object( $line_item_id, $order_id );

		// Fire the notification now.
		$this->send(
			$this->get_recipient(),
			$this->get_subject(),
			$this->get_content(),
			$this->get_headers(),
			array()
		);
	}

	/**
	 * Create the data object that will be used in the template.
	 *
	 * @param object $line_item_id WooCommerce line item ID.
	 * @param int    $order_id WooCommerce order ID.
	 * @return stdClass
	 * @since 1.0.0
	 */
	public static function create_object( $line_item_id, $order_id ) {
		global $wpdb;
		$item_object = new stdClass();

		// WooCommerce Order ID.
		$item_object->order_id = $order_id;

		// WooCommerce order.
		$wc_order = wc_get_order( $order_id );

		// Order Date.
		$date_created            = $wc_order->get_date_created();
		$date_created_formatted  = gmdate( 'F j, Y, g:i A', strtotime( $date_created ) );
		$item_object->order_date = $date_created_formatted;

		// Customer billing data.
		$item_object->customer = array(
			'billing_first_name' => $wc_order->get_billing_first_name(),
			'billing_last_name'  => $wc_order->get_billing_last_name(),
			'billing_email'      => $wc_order->get_billing_email(),
			'billing_phone'      => $wc_order->get_billing_phone(),
		);

		// Order view URL.
		$item_object->order_edit_url = get_edit_post_link( $order_id );

		// Line item.
		$product_id = wc_get_order_item_meta( $line_item_id, '_product_id', true );

		// Prepare the item object.
		$item_object->item = array(
			'item'     => get_the_title( $product_id ),
			'subtotal' => (float) wc_get_order_item_meta( $line_item_id, '_line_subtotal', true ),
		);

		/**
		 * This filter is fired when sending cancellation requests email on customer request.
		 *
		 * This filter helps managing the item data in the cancellation request email template.
		 *
		 * @param stdClass $item_object Data object.
		 * @return stdClass
		 * @since 1.0.0
		 */
		return apply_filters( 'ersrv_reservation_cancellation_request_email_order_object', $item_object );
	}

	/**
	 * Get the html content of the email.
	 *
	 * @return string
	 */
	public function get_content_html() {
		ob_start();

		wc_get_template(
			$this->template_html,
			array(
				'item_data'     => $this->object,
				'email_heading' => $this->get_heading()
			),
			'',
			$this->template_base
		);

		return ob_get_clean();
	}

	/**
	 * Get the plain text content of the email.
	 *
	 * @return string
	 */
	public function get_content_plain() {
		ob_start();

		wc_get_template(
			$this->template_plain,
			array(
				'item_data'     => $this->object,
				'email_heading' => $this->get_heading()
			),
			'',
			$this->template_base
		);

		return ob_get_clean();
	}

	/**
	 * Get the email subject line.
	 *
	 * @return string
	 */
	public function get_subject() {
		return apply_filters( 'woocommerce_email_subject_' . $this->id, $this->format_string( $this->subject ), $this->object );
	}

	/**
	 * Get the email main heading line.
	 *
	 * @return string
	 */
	public function get_heading() {

		return apply_filters( 'woocommerce_email_heading_' . $this->id, $this->format_string( $this->heading ), $this->object );
	}

	/**
	 * Get the email settings.
	 *
	 * @return string
	 */
	public function init_form_fields() {
		$this->form_fields = array(
			'enabled' => array(
				'title'   => __( 'Enable/Disable', 'easy-reservations' ),
				'type'    => 'checkbox',
				'label'   => __( 'Enable this email notification', 'easy-reservations' ),
				'default' => 'yes'
			),
			'recipient' => array(
				'title'       => __( 'Recipient', 'easy-reservations' ),
				'type'        => 'text',
				'description' => sprintf( __( 'Enter recipients (comma separated) for this email. Defaults to %s', 'easy-reservations' ), get_option( 'admin_email' ) ),
				'default'     => get_option( 'admin_email' )
			),
			'subject' => array(
				'title'       => __( 'Subject', 'easy-reservations' ),
				'type'        => 'text',
				'description' => sprintf( __( 'This controls the email subject line. Leave blank to use the default subject: <code>%s</code>.', 'easy-reservations' ), $this->subject ),
				'placeholder' => '',
				'default'     => ''
			),
			'heading' => array(
				'title'       => __( 'Email Heading', 'easy-reservations' ),
				'type'        => 'text',
				'description' => sprintf( __( 'This controls the main heading contained within the email notification. Leave blank to use the default heading: <code>%s</code>.', 'easy-reservations' ), $this->heading ),
				'placeholder' => '',
				'default'     => ''
			),
			'email_type' => array(
				'title'       => __( 'Email type', 'easy-reservations' ),
				'type'        => 'select',
				'description' => __( 'Choose which format of email to send.', 'easy-reservations' ),
				'default'     => 'html',
				'class'       => 'email_type',
				'options'		=> array(
					'html' => __( 'HTML', 'easy-reservations' ),
				)
			)
		);
	}
} // end \Reservation_Reminder_Email class
