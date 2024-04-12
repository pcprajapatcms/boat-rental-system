<?php
/**
 * This file is used for templating the block out reservation dates modal.
 *
 * @since 1.0.0
 * @package Easy_Reservations
 * @subpackage Easy_Reservations/admin/templates/modals
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.
?>
<div id="ersrv-blockout-reservation-calendar-dates-modal" class="ersrv-modal">
	<div class="ersrv-modal-content">
		<span class="ersrv-close-modal">&times;</span>
		<h3><?php esc_html_e( 'Blockout Dates', 'easy-reservations' ); ?></h3>
		<div class="ersrv-date-ranges">
			<div class="from">
				<label for="ersrv-blockout-date-from"><?php esc_html_e( 'From', 'easy-reservations' ); ?></label>
				<input type="text" id="ersrv-blockout-date-from" class="ersrv-has-datepicker" />
			</div>
			<div class="to">
				<label for="ersrv-blockout-date-to"><?php esc_html_e( 'To', 'easy-reservations' ); ?></label>
				<input type="text" id="ersrv-blockout-date-to" class="ersrv-has-datepicker" />
			</div>
		</div>
		<div class="ersrv-blockout-dates-message">
			<label><?php esc_html_e( 'Message', 'easy-reservations' ); ?></label>
			<textarea rows="4"></textarea>
		</div>
		<div class="submit-export">
			<button class="button submit-blockout-calendar-dates" type="button"><?php esc_html_e( 'Submit', 'easy-reservations' ); ?></button>
		</div>
	</div>
</div>
