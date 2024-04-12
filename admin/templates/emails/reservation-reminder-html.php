<?php
/**
 * Reservation reminder email.
 *
 * @package Easy_Reservations
 * @subpackage Easy_Reservations/admin/templates/emails
 */

defined( 'ABSPATH' ) || exit;

/* translators: 1: %s: order ID, 2: order date */
$opening_paragraph = sprintf( __( 'This is a kind reminder about your reservation order #%1$d that was placed on %2$s. The details about the reservation item are as follows:', 'easy-reservations' ), $item_data->order_id, $item_data->order_date );
$view_order_url    = $item_data->order_view_url;
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
					/* translators: 1: %s: order item subtotal */
					echo wp_kses_post( '<p>' . sprintf( __( 'Subtotal: %1$s', 'easy-reservations' ), wc_price( $order_item['subtotal'] ) ) . '</p>' );
				}

				// Print the item checkin date.
				if ( ! empty( $order_item['checkin_date'] ) ) {
					/* translators: 1: %s: reservation checkin date */
					echo wp_kses_post( '<p>' . sprintf( __( 'Checkin Date: %1$s', 'easy-reservations' ), $order_item['checkin_date'] ) . '</p>' );
				}

				// Print the item checkout date.
				if ( ! empty( $order_item['checkout_date'] ) ) {
					/* translators: 1: %s: reservation checkout date */
					echo wp_kses_post( '<p>' . sprintf( __( 'Checkout Date: %1$s', 'easy-reservations' ), $order_item['checkout_date'] ) . '</p>' );
				}

				// Print the adult count.
				if ( ! empty( $order_item['adult_count'] ) ) {
					/* translators: 1: %s: reservation adult count */
					echo wp_kses_post( '<p>' . sprintf( __( 'Adult Count: %1$s', 'easy-reservations' ), $order_item['adult_count'] ) . '</p>' );
				}

				// Print the adult subtotal.
				if ( ! empty( $order_item['adult_subtotal'] ) ) {
					/* translators: 1: %s: reservation adult subtotal */
					echo wp_kses_post( '<p>' . sprintf( __( 'Adult Subtotal: %1$s', 'easy-reservations' ), wc_price( $order_item['adult_subtotal'] ) ) . '</p>' );
				}

				// Print the kids count.
				if ( ! empty( $order_item['kids_count'] ) ) {
					/* translators: 1: %s: reservation kids count */
					echo wp_kses_post( '<p>' . sprintf( __( 'Kids Count: %1$s', 'easy-reservations' ), $order_item['kids_count'] ) . '</p>' );
				}

				// Print the kids subtotal.
				if ( ! empty( $order_item['kids_subtotal'] ) ) {
					/* translators: 1: %s: reservation kids subtotal */
					echo wp_kses_post( '<p>' . sprintf( __( 'Kids Subtotal: %1$s', 'easy-reservations' ), wc_price( $order_item['kids_subtotal'] ) ) . '</p>' );
				}

				// Print the security amount.
				if ( ! empty( $order_item['security'] ) ) {
					/* translators: 1: %s: reservation security subtotal */
					echo wp_kses_post( '<p>' . sprintf( __( 'Security: %1$s', 'easy-reservations' ), wc_price( $order_item['security'] ) ) . '</p>' );
				}

				// Print the amenities subtotal.
				if ( ! empty( $order_item['amenities_subtotal'] ) ) {
					/* translators: 1: %s: reservation amenities subtotal */
					echo wp_kses_post( '<p>' . sprintf( __( 'Amenities Subtotal: %1$s', 'easy-reservations' ), wc_price( $order_item['amenities_subtotal'] ) ) . '</p>' );
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
	echo wp_kses_post( make_clickable( sprintf( __( 'You can view this order in the dashboard here: %s', 'easy-reservations' ), $view_order_url ) ) );
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
