<?php
/**
 * Reservation item - contact owner email class.
 *
 * @link       https://www.cmsminds.com/
 * @since      1.0.0
 *
 * @package    Easy_Reservations
 * @subpackage Easy_Reservations/includes/classes/emails
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Reservation item - contact owner email class.
 *
 * @package    Easy_Reservations
 * @subpackage Easy_Reservations/includes/classes/emails
 * @author     cmsMinds <info@cmsminds.com>
 * @since      1.0.0
 * @extends \WC_Email
 */
class Reservation_Contact_Owner_Email extends WC_Email {
	/**
	 * Set email defaults.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		// Email slug we can use to filter other data.
		$this->id          = 'reservation_item_contact_owner_email';
		$this->title       = __( 'Easy Reservations: Reservation Item Contact Request', 'easy-reservations' );
		$this->description = __( 'An email sent to the item owner when the customer requests more info regarding the reservation item.', 'easy-reservations' );

		// For admin area to let the user know we are sending this email to administrators.
		$this->customer_email = false;
		$this->heading        = __( 'Reservation Item Contact Owner Request', 'easy-reservations' );

		// translators: placeholder is {blogname}, a variable that will be substituted when email is sent out.
		$this->subject = sprintf( _x( '[%s] Contact Owner Request', 'default email subject for contact requests emails sent to the item owner', 'easy-reservations' ), '{blogname}' );

		// Template paths.
		$this->template_html  = 'reservation-item-contact-owner-html.php';
		$this->template_plain = 'plain/reservation-item-contact-owner-plain.php';

		add_action( 'ersrv_send_reservation_item_contact_request_notification', array( $this, 'ersrv_ersrv_send_reservation_item_contact_request_notification_callback' ) );

		// Call parent constructor.
		parent::__construct();

		// Template base path.
		$this->template_base = ERSRV_CUSTOM_EMAIL_TEMPLATE_PATH;

		// Recipient.
		$this->recipient = $this->get_option( 'recipient', get_option( 'admin_email' ) );
	}

	/**
	 * This callback helps fire the email notification.
	 *
	 * @param string $item_author_email Author email address.
	 * @since 1.0.0
	 */
	public function ersrv_ersrv_send_reservation_item_contact_request_notification_callback( $item_author_email ) {
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
	 * Get the html content of the email.
	 *
	 * @return string
	 */
	public function get_content_html() {
		ob_start();

		wc_get_template(
			$this->template_html,
			array(
				'item_data'     => array(),
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
				'item_data'     => array(),
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
} // end \Reservation_Contact_Owner_Email class
