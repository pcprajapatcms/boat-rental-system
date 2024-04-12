<?php
/**
 * This file is used for templating the new customer modal on new reservation page.
 *
 * @since 1.0.0
 * @package Easy_Reservations
 * @subpackage Easy_Reservations/admin/templates/modals
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

$password = wp_generate_password( 12, true, true );

// Get the countries list.
$countries_obj = new WC_Countries();
$countries     = $countries_obj->__get( 'countries' );
?>
<div id="ersrv-new-customer-modal" class="ersrv-modal">
	<div class="ersrv-modal-content">
		<span class="ersrv-close-modal">&times;</span>
		<h3><?php esc_html_e( 'New Customer', 'easy-reservations' ); ?></h3>
		<div class="ersrv-new-customer-details">
			<div class="ersrv-form-messages">
				<p class="ersrv-form-error"></p>
				<p class="ersrv-form-success"></p>
			</div>
			<div class="ersrv-customer-field first-name">
				<label for="ersrv-customer-first-name"><?php esc_html_e( 'First Name*', 'easy-reservations' ); ?></label>
				<input type="text" id="ersrv-customer-first-name" />
				<span class="ersrv-form-field-error first-name-error"></span>
			</div>
			<div class="ersrv-customer-field last-name">
				<label for="ersrv-customer-last-name"><?php esc_html_e( 'Last Name*', 'easy-reservations' ); ?></label>
				<input type="text" id="ersrv-customer-last-name" />
				<span class="ersrv-form-field-error last-name-error"></span>
			</div>
			<div class="ersrv-customer-field email">
				<label for="ersrv-customer-email"><?php esc_html_e( 'Email*', 'easy-reservations' ); ?></label>
				<input type="text" id="ersrv-customer-email" />
				<span class="ersrv-form-field-error email-error"></span>
			</div>
			<div class="ersrv-customer-field phone">
				<label for="ersrv-customer-phone"><?php esc_html_e( 'Phone*', 'easy-reservations' ); ?></label>
				<input type="text" id="ersrv-customer-phone" />
				<span class="ersrv-form-field-error phone-error"></span>
			</div>
			<div class="ersrv-customer-field password">
				<label for="ersrv-customer-password"><?php esc_html_e( 'Password*', 'easy-reservations' ); ?></label>
				<input type="text" id="ersrv-customer-password" value="<?php echo esc_html( $password ); ?>" />
				<a class="ersrv-generate-password" href="javascript:void(0);"><?php esc_html_e( 'Get new password', 'easy-reservations' ); ?></a>
				<span class="ersrv-form-field-error password-error"></span>
			</div>
			<hr />
			<div class="ersrv-customer-field address-line">
				<label for="ersrv-customer-address-line"><?php esc_html_e( 'Address Line*', 'easy-reservations' ); ?></label>
				<input type="text" id="ersrv-customer-address-line" />
				<span class="ersrv-form-field-error address-line-error"></span>
			</div>
			<div class="ersrv-customer-field address-line-2">
				<label for="ersrv-customer-address-line-2"><?php esc_html_e( 'Address Line 2 (optional)', 'easy-reservations' ); ?></label>
				<input type="text" id="ersrv-customer-address-line-2" />
			</div>
			<div class="ersrv-customer-field country">
				<label for="ersrv-customer-country"><?php esc_html_e( 'Country*', 'easy-reservations' ); ?></label>
				<select id="ersrv-customer-country">
					<option value=""><?php esc_html_e( 'Select country', 'easy-reservations' ); ?></option>
					<?php
					if ( ! empty( $countries ) && is_array( $countries ) ) {
						foreach ( $countries as $country_code => $country_name ) {
							echo wp_kses(
								'<option value="' . $country_code . '">' . $country_name . '</option>',
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
				<span class="ersrv-form-field-error country-error"></span>
			</div>
			<div class="ersrv-customer-field state">
				<label for="ersrv-customer-state"><?php esc_html_e( 'State', 'easy-reservations' ); ?></label>
				<select id="ersrv-customer-state"></select>
			</div>
			<div class="ersrv-customer-field city">
				<label for="ersrv-customer-city"><?php esc_html_e( 'City*', 'easy-reservations' ); ?></label>
				<input type="text" id="ersrv-customer-city" />
				<span class="ersrv-form-field-error city-error"></span>
			</div>
			<div class="ersrv-customer-field postcode">
				<label for="ersrv-customer-postcode"><?php esc_html_e( 'Postcode/ZIP*', 'easy-reservations' ); ?></label>
				<input type="text" id="ersrv-customer-postcode" />
				<span class="ersrv-form-field-error postcode-error"></span>
			</div>
		</div>
		<div class="submit-customer">
			<button class="button" type="button"><?php esc_html_e( 'Add New Customer', 'easy-reservations' ); ?></button>
		</div>
	</div>
</div>
