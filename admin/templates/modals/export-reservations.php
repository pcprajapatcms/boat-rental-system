<?php
/**
 * This file is used for templating the export reservations modal.
 *
 * @since 1.0.0
 * @package Easy_Reservations
 * @subpackage Easy_Reservations/admin/templates/modals
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.
?>
<div id="ersrv-export-reservations-modal" class="ersrv-modal">
	<div class="ersrv-modal-content">
		<span class="ersrv-close-modal">&times;</span>
		<h3><?php esc_html_e( 'Export Reservations', 'easy-reservations' ); ?></h3>
		<div class="ersrv-date-ranges">
			<div class="from">
				<label for="ersrv-date-from"><?php esc_html_e( 'From', 'easy-reservations' ); ?></label>
				<input type="text" id="ersrv-date-from" class="ersrv-export-reservation-date-field" />
			</div>
			<div class="to">
				<label for="ersrv-date-to"><?php esc_html_e( 'To', 'easy-reservations' ); ?></label>
				<input type="text" id="ersrv-date-to" class="ersrv-export-reservation-date-field" />
			</div>
		</div>
		<div class="submit-export">
			<button class="button export-reservations" type="button"><?php esc_html_e( 'Export CSV', 'easy-reservations' ); ?></button>
		</div>
	</div>
</div>
