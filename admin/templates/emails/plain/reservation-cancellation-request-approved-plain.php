<?php
/**
 * Reservation cancellation request approved email.
 *
 * @package Easy_Reservations
 * @subpackage Easy_Reservations/admin/templates/emails/plain
 */

defined( 'ABSPATH' ) || exit;

/* translators: 1: %s: order item name, 2: order ID, 3: order date */
$opening_paragraph = sprintf( __( 'This is to update you that your request for cancelling reservation for %1$s in order #%2$s that you placed on %3$s has been approved. The details about the reservation item are as follows:', 'easy-reservations' ), $item_data->item['item'], $item_data->order_id, $item_data->order_date );

echo esc_html( "=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n" );
echo esc_html( wp_strip_all_tags( $email_heading ) );
echo esc_html( "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n" );
echo esc_html( wp_strip_all_tags( $opening_paragraph ) );
