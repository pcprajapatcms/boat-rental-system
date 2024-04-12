<?php
/**
 * HTML email content to be sent to the reservatio item owner.
 *
 * @package Easy_Reservations
 * @subpackage Easy_Reservations/admin/templates/emails
 */

defined( 'ABSPATH' ) || exit;

$item_id = (int) filter_input( INPUT_POST, 'item_id', FILTER_SANITIZE_NUMBER_INT );
$name    = filter_input( INPUT_POST, 'name', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );
$email   = filter_input( INPUT_POST, 'email', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );
$phone   = filter_input( INPUT_POST, 'phone', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );
$subject = filter_input( INPUT_POST, 'subject', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );
$message = filter_input( INPUT_POST, 'message', FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH );
/* translators: 1: %s: reservation item title */
$opening_paragraph = sprintf( __( 'There has been a contact request for the item: %1$s. The details of the item are as follows:', 'easy-reservations' ), get_the_title( $item_id ) );

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
<table cellspacing="0" cellpadding="6" style="width: 100%; border: 1px solid #eee;" bordercolor="#eee">
	<tbody>
		<tr>
			<th scope="row" style="text-align:left; border: 1px solid #eee;"><?php esc_html_e( 'Name', 'easy-reservations' ); ?></th>
			<td style="text-align:left; border: 1px solid #eee;"><?php echo esc_html( $name ); ?></td>
		</tr>
		<tr>
			<th scope="row" style="text-align:left; border: 1px solid #eee;"><?php esc_html_e( 'Email', 'easy-reservations' ); ?></th>
			<td style="text-align:left; border: 1px solid #eee;"><?php echo esc_html( $email ); ?></td>
		</tr>
		<tr>
			<th scope="row" style="text-align:left; border: 1px solid #eee;"><?php esc_html_e( 'Phone', 'easy-reservations' ); ?></th>
			<td style="text-align:left; border: 1px solid #eee;"><?php echo esc_html( $phone ); ?></td>
		</tr>
		<tr>
			<th scope="row" style="text-align:left; border: 1px solid #eee;"><?php esc_html_e( 'Subject', 'easy-reservations' ); ?></th>
			<td style="text-align:left; border: 1px solid #eee;"><?php echo esc_html( $subject ); ?></td>
		</tr>
		<tr>
			<th scope="row" style="text-align:left; border: 1px solid #eee;"><?php esc_html_e( 'Message', 'easy-reservations' ); ?></th>
			<td style="text-align:left; border: 1px solid #eee;"><?php echo esc_html( $message ); ?></td>
		</tr>
	</tbody>
</table>
<?php
/**
 * This hook runs on the custom email footers.
 *
 * This hook helps in customizing email footer text.
 *
 * @since 1.0.0
 */
do_action( 'woocommerce_email_footer' );
