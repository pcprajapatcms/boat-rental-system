<?php
/**
 * This file is used for templating the new reservation from admin.
 *
 * @since 1.0.0
 * @package Easy_Reservations
 * @subpackage Easy_Reservations/admin/templates/pages
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

// Get the reservable items.
$items_query = ersrv_get_posts( 'product', 1, -1 );
$items       = $items_query->posts;

// Get the customer.
$customers = get_users();
?>
<div class="wrap">
	<h1><?php esc_html_e( 'New Reservation', 'easy-reservations' ); ?></h1>
	<h4><?php esc_html_e( 'Fill in the details below to add a new customer reservation.', 'easy-reservations' ); ?></h4>
	<table class="form-table">
		<tbody>
			<!-- FIELD: ITEM ID -->
			<tr>
				<th scope="row"><label for="item-id"><?php esc_html_e( 'Reservation Item', 'easy-reservations' ); ?></label></th>
				<td>
					<select id="item-id">
						<option value=""><?php esc_html_e( 'Select item...', 'easy-reservations' ); ?></option>
						<?php
						if ( ! empty( $items ) && is_array( $items ) ) {
							foreach ( $items as $item_id ) {
								$item_title = get_the_title( $item_id );
								$item_title = "#{$item_id} - {$item_title}";
								echo wp_kses(
									'<option value="' . $item_id . '">' . $item_title . '</option>',
									array(
										'option' => array(
											'value' => array(),
										),
									)
								);
							}
						}
						?>
					</select>
					<p class="ersrv-form-description-text"><?php esc_html_e( 'Select the item to be reserved here.', 'easy-reservations' ); ?></p>
				</td>
			</tr>

			<!-- AVAILABILITY -->
			<tr class="ersrv-new-reservation-item-availability-row non-clickable">
				<th scope="row"><label for="availability"><?php esc_html_e( 'Availability', 'easy-reservations' ); ?></label></th>
				<td><div class="ersrv-item-availability-calendar"></div></td>
			</tr>

			<!-- CHECKIN/CHECKOUT DATE -->
			<tr class="ersrv-new-reservation-checkin-checkout-row non-clickable">
				<th scope="row">
					<label for="checkin-checkout-date"><?php esc_html_e( 'Checkin/checkout Date', 'easy-reservations' ); ?></label>
				</th>
				<td>
					<input type="text" class="regular-text" id="ersrv-checkin-date" placeholder="<?php esc_html_e( 'Select the reservation checkin date.', 'easy-reservations' ); ?>">
					<input type="text" class="regular-text" id="ersrv-checkout-date" placeholder="<?php esc_html_e( 'Select the reservation checkout date.', 'easy-reservations' ); ?>">
					<p class="ersrv-reservation-error checkin-checkout-dates-error"></p>
				</td>
			</tr>

			<!-- CUSTOMER -->
			<tr class="ersrv-new-reservation-customer-row non-clickable">
				<th scope="row"><label for="customer-id"><?php esc_html_e( 'Customer', 'easy-reservations' ); ?></label></th>
				<td>
					<select id="customer-id">
						<option value=""><?php esc_html_e( 'Select customer...', 'easy-reservations' ); ?></option>
						<?php
						if ( ! empty( $customers ) && is_array( $customers ) ) {
							foreach ( $customers as $customer_data ) {
								$customer_name  = $customer_data->data->display_name;
								$customer_email = $customer_data->data->user_email;
								$customer_name  = "#{$customer_data->ID} [{$customer_email}] - {$customer_name}";
								echo wp_kses(
									'<option value="' . $customer_data->ID . '">' . $customer_name . '</option>',
									array(
										'option' => array(
											'value' => array(),
										),
									)
								);
							}
						}
						?>
					</select>
					<a class="ersrv-create-new-customer-link" href="javascript:void(0);"><?php esc_html_e( 'Not listed here? Create new from here.', 'easy-reservations' ); ?></a>
					<p class="ersrv-form-description-text"><?php esc_html_e( 'Select the customer whom the reservation would be assigned.', 'easy-reservations' ); ?></p>
					<p class="ersrv-reservation-error customer-error"></p>
				</td>
			</tr>

			<!-- ACCOMODATION -->
			<tr class="ersrv-new-reservation-accomodation-row non-clickable">
				<th scope="row">
					<label for="accomodation"><?php esc_html_e( 'Accomodation', 'easy-reservations' ); ?></label>
					<small class="ersrv-new-reservation-limit-text"><?php esc_html_e( 'Limit: --', 'easy-reservations' ); ?></small>
				</th>
				<td>
					<input type="number" id="adult-accomodation-count" min="1" step="1" class="regular-text" placeholder="<?php esc_html_e( 'No. of adults.', 'easy-reservations' ); ?>">
					<input type="number" id="kid-accomodation-count" min="1" step="1" class="regular-text" placeholder="<?php esc_html_e( 'No. of kids.', 'easy-reservations' ); ?>">
					<p class="ersrv-reservation-error accomodation-error"></p>
				</td>
			</tr>

			<!-- AMENITIES -->
			<tr class="ersrv-new-reservation-amenities-row non-clickable">
				<th scope="row">
					<label for="reservation-amenities"><?php esc_html_e( 'Amenities', 'easy-reservations' ); ?></label>
				</th>
				<td>
					<div class="ersrv-new-reservation-single-amenity">
						<label class="ersrv-switch">
							<input type="checkbox" class="ersrv-switch-input">
							<span class="slider ersrv-switch-slider"></span>
						</label>
						<span></span>
					</div>
				</td>
			</tr>

			<!-- CUSTOM NOTE -->
			<tr class="ersrv-new-reservation-customer-note-row non-clickable">
				<th scope="row">
					<label for="accomodation"><?php esc_html_e( 'Customer Notes (if any)', 'easy-reservations' ); ?></label>
				</th>
				<td>
					<textarea rows="4" class="large-text"></textarea>
					<p class="ersrv-form-description-text"><?php esc_html_e( 'Any special request that the customer may have during/before the reservation.', 'easy-reservations' ); ?></p>
				</td>
			</tr>
		</tbody>
	</table>
	<div class="new-reservation-summary non-clickable">
		<h3><?php esc_html_e( 'Summary', 'easy-reservations' ); ?></h3>
		<table class="form-table">
			<tbody>
				<tr class="item-price-summary">
					<th scope="row"><?php esc_html_e( 'Item (Adult) Subtotal', 'easy-reservations' ); ?></th>
					<td><span data-cost="" class="ersrv-cost">--</span></td>
				</tr>
				<tr class="kids-charge-summary">
					<th scope="row"><?php esc_html_e( 'Kid(s) Subtotal', 'easy-reservations' ); ?></th>
					<td><span data-cost="" class="ersrv-cost">--</span></td>
				</tr>
				<tr class="security-amount-summary">
					<th scope="row"><?php esc_html_e( 'Security Fees Subtotal', 'easy-reservations' ); ?></th>
					<td><span data-cost="" class="ersrv-cost">--</span></td>
				</tr>
				<tr class="amenities-summary">
					<th scope="row"><?php esc_html_e( 'Amenities Subtotal', 'easy-reservations' ); ?></th>
					<td><span data-cost="" class="ersrv-cost">--</span></td>
				</tr>
			</tbody>
			<tfoot>
				<tr class="new-reservation-total-cost">
					<th scope="row"><?php esc_html_e( 'Total', 'easy-reservations' ); ?></th>
					<td><span data-cost="" class="ersrv-cost">--</span></td>
				</tr>
			</tfoot>
		</table>
	</div>
	<input type="hidden" id="accomodation-limit" value="" />
	<input type="hidden" id="min-reservation-period" value="" />
	<input type="hidden" id="max-reservation-period" value="" />
	<input type="hidden" id="adult-charge" value="" />
	<input type="hidden" id="kid-charge" value="" />
	<input type="hidden" id="security-amount" value="" />
	<button type="button" class="button ersrv-add-new-reservation non-clickable"><?php esc_html_e( 'Add New Reservation', 'easy-reservations' ); ?></button>
</div>
