<?php
/**
 * Rental agreement email template.
 *
 * @package Easy_Reservations
 * @subpackage Easy_Reservations/admin/templates/emails
 */

defined( 'ABSPATH' ) || exit;

/* translators: 1: %s: order ID, 2: order date, 3: admin email */
$opening_paragraph = sprintf( __( 'This email is regarding the order #%1$d which was placed on %2$s. Please find a rental agreement in the attachment. You need to revert back to the email %3$s after digitally signing it or you can bring a copy of the same when you arrive for boarding your reservtaion. Please note than a signed agreement before onboarding is a MUST.', 'easy-reservations' ), $item_data->order_id, $item_data->order_date, $item_data->admin_email );

/**
 * This hook runs on the custom email headers.
 *
 * This hook helps in customizing email header text.
 *
 * @param string $email_heading Email heading.
 * @since 1.0.0
 */
do_action( 'woocommerce_email_header', $email_heading );
?>
<p><?php echo esc_html( $opening_paragraph ); ?></p>
<p><?php esc_html_e( 'This is a system generated email. Please DO NOT respond to it.', 'easy-reservations' ); ?></p>
<p>
	<?php
	/* translators: 1: %s: order view URL */
	echo wp_kses_post( make_clickable( sprintf( __( 'You can view the order details in the dashboard here: %s', 'easy-reservations' ), $item_data->order_view_url ) ) );
	?>
</p>
<?php
/**
 * This hook runs on the custom email footers.
 *
 * This hook helps in customizing email footer text.
 *
 * @since 1.0.0
 */
do_action( 'woocommerce_email_footer' );
