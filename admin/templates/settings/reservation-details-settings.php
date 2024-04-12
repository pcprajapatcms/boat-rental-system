<?php
/**
 * This file is used for templating the product reservation details settings.
 *
 * @since 1.0.0
 * @package Easy_Reservations
 * @subpackage Easy_Reservations/admin/templates/settings
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

$product_type_slug = ersrv_get_custom_product_type_slug();
$product_id        = (int) filter_input( INPUT_GET, 'post', FILTER_SANITIZE_NUMBER_INT );
$amenities         = get_post_meta( $product_id, '_ersrv_reservation_amenities', true );
?>
<div id="reservation_details_product_options" class="panel woocommerce_options_panel">
	<div class="options_group">
		<h4 class="reservation-tab-setting-heading"><?php esc_html_e( 'Location Details', 'easy-reservations' ); ?></h4>
		<?php
		// Security amount.
		woocommerce_wp_textarea_input(
			array(
				'id'                => 'location',
				'label'             => __( 'Item Location', 'easy-reservations' ),
				'placeholder'       => __( 'Provide the item physical location.', 'easy-reservations' ),
				'desc_tip'          => 'true',
				'description'       => __( 'Reservation item location.', 'easy-reservations' ),
				'value'             => get_post_meta( $post->ID, '_ersrv_item_location', true ),
				'custom_attributes' => array(
					'rows' => 2,
				),
			)
		);

		/**
		 * Hook that fires after the security deposit item settings.
		 *
		 * This hook helps in adding custom settings to the reservable item, after the security deposit settings.
		 *
		 * @param string $product_type_slug Holds the product type slug.
		 */
		do_action( 'ersrv_after_security_deposit_item_settings', $product_type_slug );
		?>
	</div>
	<div class="options_group">
		<h4 class="reservation-tab-setting-heading"><?php esc_html_e( 'Security Deposit', 'easy-reservations' ); ?></h4>
		<?php
		// Security amount.
		woocommerce_wp_text_input(
			array(
				'id'                => 'security_amount',
				'label'             => __( 'Security Amt.', 'easy-reservations' ),
				'placeholder'       => 0.00,
				'desc_tip'          => 'true',
				'description'       => __( 'Reservation security amount which will be incharge of any destruction done to the reservable item.', 'easy-reservations' ),
				'type'              => 'number',
				'value'             => get_post_meta( $post->ID, '_ersrv_security_amt', true ),
				'custom_attributes' => array(
					'step'      => 0.01,
					'min'       => 0,
					'oninput'   => 'javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);',
					'maxlength' => 10,
				),
			)
		);

		/**
		 * Hook that fires after the security deposit item settings.
		 *
		 * This hook helps in adding custom settings to the reservable item, after the security deposit settings.
		 *
		 * @param string $product_type_slug Holds the product type slug.
		 */
		do_action( 'ersrv_after_security_deposit_item_settings', $product_type_slug );
		?>
	</div>
	<div class="options_group">
		<h4 class="reservation-tab-setting-heading"><?php esc_html_e( 'Accomodation', 'easy-reservations' ); ?></h4>
		<?php
		// Accomodation limit.
		woocommerce_wp_text_input(
			array(
				'id'                => 'accomodation_limit',
				'label'             => __( 'Accomodation Limit', 'easy-reservations' ),
				'placeholder'       => 0.00,
				'desc_tip'          => 'true',
				'description'       => __( 'This will set the limit to number of people who cab be a part of the reservation.', 'easy-reservations' ),
				'type'              => 'number',
				'value'             => get_post_meta( $post->ID, '_ersrv_accomodation_limit', true ),
				'custom_attributes' => array(
					'step'      => 1,
					'min'       => 1,
					'oninput'   => 'javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);',
					'maxlength' => 10,
				),
			)
		);

		// Adult charge.
		woocommerce_wp_text_input(
			array(
				'id'                => 'accomodation_adult_charge',
				'label'             => __( 'Adult Charge', 'easy-reservations' ),
				'placeholder'       => 0.00,
				'desc_tip'          => 'true',
				'description'       => __( 'This will set the per adult cost in the reservation.', 'easy-reservations' ),
				'type'              => 'number',
				'value'             => get_post_meta( $post->ID, '_ersrv_accomodation_adult_charge', true ),
				'custom_attributes' => array(
					'step'      => 0.01,
					'min'       => 0,
					'oninput'   => 'javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);',
					'maxlength' => 10,
				),
			)
		);

		// Kid's charge.
		woocommerce_wp_text_input(
			array(
				'id'                => 'accomodation_kid_charge',
				'label'             => __( 'Kid\'s Charge', 'easy-reservations' ),
				'placeholder'       => 0.00,
				'desc_tip'          => 'true',
				'description'       => __( 'This will set the per kid cost in the reservation.', 'easy-reservations' ),
				'type'              => 'number',
				'value'             => get_post_meta( $post->ID, '_ersrv_accomodation_kid_charge', true ),
				'custom_attributes' => array(
					'step'      => 0.01,
					'min'       => 0,
					'oninput'   => 'javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);',
					'maxlength' => 10,
				),
			)
		);

		/**
		 * Hook that fires after the accomodation item settings.
		 *
		 * This hook helps in adding custom settings to the reservable item, after the accomodation settings.
		 *
		 * @param string $product_type_slug Holds the product type slug.
		 */
		do_action( 'ersrv_after_accomodation_item_settings', $product_type_slug );
		?>
	</div>
	<div class="options_group">
		<h4 class="reservation-tab-setting-heading"><?php esc_html_e( 'Reservation Limit', 'easy-reservations' ); ?></h4>
		<?php
		// Minimum time period limit of the reservation.
		woocommerce_wp_text_input(
			array(
				'id'                => 'reservation_min_period',
				'label'             => __( 'Minimum Period', 'easy-reservations' ),
				'placeholder'       => 0.00,
				'desc_tip'          => 'true',
				'description'       => __( 'This sets the minimum reservation period for this item.', 'easy-reservations' ),
				'type'              => 'number',
				'value'             => get_post_meta( $post->ID, '_ersrv_reservation_min_period', true ),
				'custom_attributes' => array(
					'step'      => 1,
					'min'       => 0,
					'oninput'   => 'javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);',
					'maxlength' => 10,
				),
			)
		);

		// Maximum time period limit of the reservation.
		woocommerce_wp_text_input(
			array(
				'id'                => 'reservation_max_period',
				'label'             => __( 'Maximum Period', 'easy-reservations' ),
				'placeholder'       => 0.00,
				'desc_tip'          => 'true',
				'description'       => __( 'This sets the maximum reservation period for this item.', 'easy-reservations' ),
				'type'              => 'number',
				'value'             => get_post_meta( $post->ID, '_ersrv_reservation_max_period', true ),
				'custom_attributes' => array(
					'step'      => 1,
					'min'       => 0,
					'oninput'   => 'javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);',
					'maxlength' => 10,
				),
			)
		);

		/**
		 * Hook that fires after the reservation limit item settings.
		 *
		 * This hook helps in adding custom settings to the reservable item, after the reservation limit settings.
		 *
		 * @param string $product_type_slug Holds the product type slug.
		 */
		do_action( 'ersrv_after_reservation_limit_item_settings', $product_type_slug );
		?>
	</div>
	<div class="options_group reservations-amenities">
		<div class="reservations-amenities-header">
			<h4 class="reservation-tab-setting-heading"><?php esc_html_e( 'Amenities', 'easy-reservations' ); ?></h4>
			<button type="button" class="button button-secondary btn-submit ersrv-add-amenity-html">
				<?php esc_html_e( 'Add Amenity', 'easy-reservations' ); ?>
			</button>
		</div>
		<div class="amenities-list">
			<?php
			// Check if amenities are available. Print them.
			if ( ! empty( $amenities ) && is_array( $amenities ) ) {
				foreach ( $amenities as $amenity_data ) {
					echo wp_kses(
						ersrv_get_amenity_html( $amenity_data ),
						array(
							'p'      => array(
								'class' => array(),
							),
							'input'  => array(
								'type'        => array(),
								'value'       => array(),
								'required'    => array(),
								'name'        => array(),
								'class'       => array(),
								'placeholder' => array(),
								'step'        => array(),
								'min'         => array(),
							),
							'button' => array(
								'type'  => array(),
								'class' => array(),
							),
							'select' => array(
								'class' => array(),
								'name'  => array(),
							),
							'option' => array(
								'value'    => array(),
								'selected' => array(),
							),
						)
					);
				}
			}
			?>
		</div>
		<?php
		/**
		 * Hook that fires after the amenities item settings.
		 *
		 * This hook helps in adding custom settings to the reservable item, after the amenities settings.
		 *
		 * @param string $product_type_slug Holds the product type slug.
		 */
		do_action( 'ersrv_after_amenities_item_settings', $product_type_slug );
		?>
	</div>
	<div class="options_group">
		<h4 class="reservation-tab-setting-heading"><?php esc_html_e( 'Promotions', 'easy-reservations' ); ?></h4>
		<?php
		// Promotions text.
		woocommerce_wp_textarea_input(
			array(
				'id'          => 'promotion_text',
				'label'       => __( 'Promotion Text', 'easy-reservations' ),
				'placeholder' => __( 'Goes the text to promote this reservable item.', 'easy-reservations' ),
				'desc_tip'    => 'true',
				'description' => __( 'Any text to promote this reservable item.', 'easy-reservations' ),
				'value'       => get_post_meta( $post->ID, '_ersrv_promotion_text', true ),
				'rows'        => 6,
			)
		);

		/**
		 * Hook that fires after the promotion text item settings.
		 *
		 * This hook helps in adding custom settings to the reservable item, after the promotion text settings.
		 *
		 * @param string $product_type_slug Holds the product type slug.
		 */
		do_action( 'ersrv_after_promotion_text_item_settings', $product_type_slug );
		?>
	</div>
</div>
