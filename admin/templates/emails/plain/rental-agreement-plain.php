<?php
/**
 * Rental agreement email template (plain text).
 *
 * @package Easy_Reservations
 * @subpackage Easy_Reservations/admin/templates/emails/plain
 */

defined( 'ABSPATH' ) || exit;

/* translators: 1: %s: order ID, 2: order date, 3: admin email */
$opening_paragraph = sprintf( __( 'This email is regarding the order #%1$d which was placed on %2$s. Please find a rental agreement in the attachment. You need to revert back to the email %3$s after digitally signing it or you can bring a copy of the same when you arrive for boarding your reservtaion. Please note than a signed agreement before onboarding is a MUST.', 'easy-reservations' ), $item_data->order_id, $item_data->order_date, $item_data->admin_email );

echo esc_html( "=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n" );
echo esc_html( wp_strip_all_tags( $email_heading ) );
echo esc_html( "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n" );
echo esc_html( wp_strip_all_tags( $opening_paragraph ) );
echo esc_html( "\n----------------------------------------\n\n" );
echo esc_html( wp_strip_all_tags( __( 'This is a system generated email. Please DO NOT respond to it.', 'easy-reservations' ) ) );
echo esc_html( "\n----------------------------------------\n\n" );
/* translators: 1: %s: order view URL */
echo esc_html( wp_strip_all_tags( sprintf( __( 'You can view the order details in the dashboard here: %s', 'easy-reservations' ), $item_data->order_view_url ) ) );
