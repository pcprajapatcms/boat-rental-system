<?php
/**
 * This file is used for templating the reservable item contact owner modal.
 *
 * @since 1.0.0
 * @package Easy_Reservations
 * @subpackage Easy_Reservations/public/templates/modals
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.
?>
<div id="ersrv-contact-owner-modal" class="ersrv-modal">
	<div class="ersrv-modal-content modal-content modal-lg m-auto p-3">
		<h3><?php esc_html_e( 'Contact Owner', 'easy-reservations' ); ?></h3>
		<span class="ersrv-close-modal quick-close close">×</span>
		<div class="modal-body">
			<form method="post">
				<div class="row form-row">
					<div class="col-12 col-md-6">
						<div class="form-group">
							<input type="text" id="contact-owner-customer-name" class="form-control" placeholder="<?php esc_html_e( 'Your Name', 'easy-reservations' ); ?>" />
							<span class="ersrv-reservation-error contact-owner-customer-name"></span>
						</div>
					</div>
					<div class="col-12 col-md-6">
						<div class="form-group">
							<input type="email" id="contact-owner-customer-email" class="form-control" placeholder="<?php esc_html_e( 'Your Email', 'easy-reservations' ); ?>" />
							<span class="ersrv-reservation-error contact-owner-customer-email"></span>
						</div>
					</div>
					<div class="col-12 col-md-6">
						<div class="form-group">
							<input type="text" id="contact-owner-customer-phone" class="form-control" placeholder="<?php esc_html_e( 'Your Phone Number', 'easy-reservations' ); ?>" />
							<span class="ersrv-reservation-error contact-owner-customer-phone"></span>
						</div>
					</div>
					<div class="col-12 col-md-6">
						<div class="form-group">
							<input type="text" id="contact-owner-customer-query-subject" class="form-control" placeholder="<?php esc_html_e( 'Query Subject', 'easy-reservations' ); ?>" />
							<span class="ersrv-reservation-error contact-owner-customer-query-subject"></span>
						</div>
					</div>
					<div class="col-12">
						<div class="form-group">
							<textarea id="contact-owner-customer-message" class="form-control" placeholder="<?php esc_html_e( 'Your Message', 'easy-reservations' ); ?>" style="width: 100%; height: 100px;"></textarea>
							<span class="ersrv-reservation-error contact-owner-customer-message"></span>
						</div>
						<div class="form-group text-right">
							<button class="ersrv-submit-contact-owner-request btn btn-accent" type="button"><?php esc_html_e( 'Send Message', 'easy-reservations' ); ?></button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>