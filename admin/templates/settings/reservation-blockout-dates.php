<?php
/**
 * This file is used for templating the reservation product blockout dates settings.
 *
 * @since 1.0.0
 * @package Easy_Reservations
 * @subpackage Easy_Reservations/admin/templates/settings
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

$product_type_slug = ersrv_get_custom_product_type_slug();
$product_id        = (int) filter_input( INPUT_GET, 'post', FILTER_SANITIZE_NUMBER_INT );
$blockedout_dates  = get_post_meta( $product_id, '_ersrv_reservation_blockout_dates', true );
$weekdays          = array(
	__( 'Sunday', 'easy-reservations' ),
	__( 'Monday', 'easy-reservations' ),
	__( 'Tuesday', 'easy-reservations' ),
	__( 'Wednesday', 'easy-reservations' ),
	__( 'Thursday', 'easy-reservations' ),
	__( 'Friday', 'easy-reservations' ),
	__( 'Saturday', 'easy-reservations' ),
);
?>
<div id="reservation_blockout_dates_product_options" class="panel woocommerce_options_panel">
	<div class="options_group reservations-blockout-dates">
		<div class="reservations-blockout-dates-header">
			<h4 class="reservation-tab-setting-heading"><?php esc_html_e( 'Blocked Out Dates', 'easy-reservations' ); ?></h4>
			<button type="button" class="button button-secondary btn-submit ersrv-add-blockedout-date-html">
				<?php esc_html_e( 'Block Date on Reservation Calendar', 'easy-reservations' ); ?>
			</button>
		</div>
		<div class="blockout-dates-list">
			<?php
			// Check if blockout dates are available. Print them.
			if ( ! empty( $blockedout_dates ) && is_array( $blockedout_dates ) ) {
				foreach ( $blockedout_dates as $blockedout_date ) {
					echo wp_kses(
						ersrv_get_blockout_date_html( $blockedout_date['date'], $blockedout_date['message'] ),
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
							),
							'button' => array(
								'type'  => array(),
								'class' => array(),
							),
						)
					);
				}
			}
			?>
		</div>

		<h4 class="reservation-tab-setting-heading"><?php esc_html_e( 'Weekdays Unavailability', 'easy-reservations' ); ?></h4>
		<?php

		// Weekdays unavailability selection.
		woocommerce_wp_select(
			array(
				'id'                => 'reservation_item_weekdays_unavailability',
				'name'              => 'reservation_item_weekdays_unavailability[]',
				'label'             => __( 'Weekdays Unavailability', 'easy-reservations' ),
				'class'             => 'select wc-enhanced-select',
				'options'           => $weekdays,
				'value'             => get_post_meta( $post->ID, '_ersrv_item_unavailable_weekdays', true ),
				'style'             => 'width: 90%',
				'desc_tip'          => true,
				'description'       => __( 'This sets the unavailable weekdays of this reservation item.', 'easy-reservations' ),
				'custom_attributes' => array(
					'multiple' => 'multiple',
				),
			)
		);

		/**
		 * Hook that fires after the blockout dates item settings.
		 *
		 * This hook helps in adding custom settings to the reservable item, after the blockout dates settings.
		 *
		 * @param string $product_type_slug Holds the product type slug.
		 */
		do_action( 'ersrv_after_blockout_item_calendar_dates_settings', $product_type_slug );
		?>
	</div>
</div>
