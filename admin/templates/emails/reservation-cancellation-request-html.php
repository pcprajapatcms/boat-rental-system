<?php
/**
 * Reservation cancellation request email.
 *
 * @package Easy_Reservations
 * @subpackage Easy_Reservations/admin/templates/emails
 */

defined( 'ABSPATH' ) || exit;

$customer_name = "{$item_data->customer['billing_first_name']} {$item_data->customer['billing_last_name']}";
/* translators: 1: %s: order ID, 2: order date, 3: customer name */
$opening_paragraph = sprintf( __( 'This is to update you about a cancellation request that is received from %3$s on order #%1$d that was placed on %2$s. The details about the reservation item are as follows:', 'easy-reservations' ), $item_data->order_id, $item_data->order_date, $customer_name );
$edit_order_url    = $item_data->order_edit_url;
$order_item        = $item_data->item;

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
			<th scope="row" style="text-align:left; border: 1px solid #eee;"><?php echo esc_html( $order_item['item'] ); ?></th>
			<td style="text-align:left; border: 1px solid #eee;">
				<?php
				// Print the item subtotal.
				if ( ! empty( $order_item['subtotal'] ) ) {
					/* translators: 1: %s: reservation item subtotal */
					echo wp_kses_post( '<p>' . sprintf( __( 'Subtotal: %1$s', 'easy-reservations' ), wc_price( $order_item['subtotal'] ) ) . '</p>' );
				}
				?>
			</td>
		</tr>
	</tbody>
</table>
<p><?php esc_html_e( 'This is a system generated email. Please DO NOT respond to it.', 'easy-reservations' ); ?></p>
<p>
	<?php
	/* translators: 1: %s: order view URL */
	echo wp_kses_post( make_clickable( sprintf( __( 'You can view this order in the dashboard here: %s', 'easy-reservations' ), $edit_order_url ) ) );
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
