<?php
/**
 * Fired during plugin deactivation
 *
 * @link       https://www.cmsminds.com/
 * @since      1.0.0
 *
 * @package    Easy_Reservations
 * @subpackage Easy_Reservations/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Easy_Reservations
 * @subpackage Easy_Reservations/includes
 * @author     cmsMinds <info@cmsminds.com>
 */
class Easy_Reservations_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		// Clear the scheduled pdf deletion cron now.
		if ( wp_next_scheduled( 'ersrv_delete_reservation_pdf_receipts' ) ) {
			wp_clear_scheduled_hook( 'ersrv_delete_reservation_pdf_receipts' );
		}

		// Clear the scheduled pdf deletion cron now.
		if ( wp_next_scheduled( 'ersrv_reservation_reminder_email_notifications' ) ) {
			wp_clear_scheduled_hook( 'ersrv_reservation_reminder_email_notifications' );
		}
	}

}
