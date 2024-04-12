<?php // phpcs:ignore Generic.Files.LineEndings.InvalidEOLChar -- Can't change the file EOL character.
/**
 * This file is used for writing the custom class for PDF generation.
 *
 * @since 1.0.0
 * @package Easy_Reservations
 * @subpackage Easy_Reservations/includes/lib
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

// Return, if the class already exists.
if ( class_exists( 'Easy_Reservations_TCPDF_Receipt' ) ) {
	return;
}

/**
 * TCPDF custom class for generating header.
 *
 * @package    Easy_Reservations_TCPDF_Receipt
 * @subpackage Easy_Reservations_TCPDF_Receipt/includes/lib
 * @since      1.0.0
 */
class Easy_Reservations_TCPDF_Receipt extends TCPDF {
	/**
	 * Define the header funcion for the TCPDF class.
	 * Holds the complete header HTML.
	 */
	public function Header() {
		$order_id = (int) filter_input( INPUT_GET, 'atts', FILTER_SANITIZE_NUMBER_INT );

		// Return if the order ID is not valid.
		if ( empty( $order_id ) ) {
			return;
		}

		// Get the order.
		$wc_order = wc_get_order( $order_id );

		// Return if the order does not exist with the order ID.
		if ( false === wc_get_order( $order_id ) ) {
			return;
		}

		$html                   = '';
		$date_created           = $wc_order->get_date_created();
		$date_created_formatted = gmdate( 'F j, Y, g:i A', strtotime( $date_created ) );
		$date_paid              = $wc_order->get_date_paid();
		$date_paid_formatted    = ( ! empty( $date_paid ) ) ? gmdate( 'F j, Y, g:i A', strtotime( $date_paid ) ) : __( 'Payment pending', 'easy-reservations' );
		$logo_image_id          = (int) ersrv_get_plugin_settings( 'ersrv_reservation_receipt_store_logo_media_id' );
		$logo_image_url         = ersrv_get_attachment_url_from_attachment_id( $logo_image_id );
		$logo_file_info         = pathinfo( $logo_image_url );
		$logo_file_extension    = ( ! empty( $logo_file_info['extension'] ) ) ? ucfirst( $logo_file_info['extension'] ) : '';

		// Store Information.
		$store_contact = ersrv_get_plugin_settings( 'ersrv_reservation_receipt_store_contact_number' );
		$store_website = site_url();
		$store_email   = get_option( 'admin_email' );

		$this->SetFont( 'robotocondensed', 'M', 12 );
		$this->SetXY( 140, 14 );
		$this->writeHTMLCell( 0, 0, '', '', $html, 0, 1, 0, true, 'top', true );
		$this->Image( $logo_image_url, 10, 5, 40, '', $logo_file_extension, '', 'T', false, 300, 'L', false, false, 0, false, false, false );

		$html = '<table cellspacing="0" cellpadding="0" width="60%" border="0">
					<tr width="100%">
						<td style="line-height:14px;font-size:11px;">
							<a style="color:black; text-decoration: none;" href="' . $store_website . '" >' . $store_website . '</a>
						</td>
					</tr>
					<tr width="100%">
						<td style="line-height:14px;font-size:11px;">Ph: ' . $store_contact . '</td>
					</tr>
					<tr width="100%">
						<td style="line-height:14px;font-size:11px;">
							<a style="color:black; text-decoration: none;" href="mailto:' . $store_email . '" >Email: ' . $store_email . '</a>
						</td>
					</tr>
				</table>';

		$this->SetXY( 50, 5 );
		$this->writeHTMLCell( 0, 0, '', '', $html, 0, 1, 0, true, 'top', true );
		$style = array(
			'width' => 0.25,
			'cap'   => 'butt',
			'join'  => 'miter',
			'dash'  => 0,
			'color' => array( 222, 219, 219 ),
		);
		$this->Line( 0, 25, 210, 25, $style );
	}

	/**
	 * Define the footer funcion for the TCPDF class.
	 * Holds the complete footer HTML.
	 */
	public function Footer() {
		// Store Information.
		$store_contact = ersrv_get_plugin_settings( 'ersrv_reservation_receipt_store_contact_number' );
		$store_website = site_url();
		$store_email   = get_option( 'admin_email' );
		$this->SetY( - 15 );
		$this->SetFont( 'robotocondensed', '', 11 );

		// Footer text.
		$footer_text = ersrv_get_plugin_settings( 'ersrv_easy_reservations_receipt_footer_text' );
		if ( ! empty( $footer_text ) ) {
			$this->Cell( 0, 0, $footer_text, 0, false, 'C', 0, '', 0, false, 'M', 'M' );
		}

		$this->SetFont( 'robotocondensed', '', 9 );
		$this->writeHTML( '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>India: </b>' . $store_contact . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Web:</b> ' . $store_website . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Email:</b> ' . $store_email . ' ', true, false, false, false, '' );
	}
}
