<?php
/**
 * This file is used for templating the favourite items on my account page.
 *
 * @since 1.0.0
 * @package Easy_Reservations
 * @subpackage Easy_Reservations/public/templates/woocommerce
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

// Get the favourite items.
$user_id         = get_current_user_id();
$favourite_items = get_user_meta( $user_id, 'ersrv_favourite_items', true );
$favourite_items = ( empty( $favourite_items ) || ! is_array( $favourite_items ) ) ? array() : $favourite_items;

if ( ! empty( $favourite_items ) && is_array( $favourite_items ) ) {
	echo wp_kses_post( '<div class="search-result-inner form-row">' );
	// Iterate through each item to display it.
	foreach ( $favourite_items as $item_id ) {
		// Skip, if the product doesn't exist anymore.
		if ( false === wc_get_product( $item_id ) ) {
			continue;
		}

		// Print the HTML now.
		echo wp_kses_post( ersrv_get_reservation_item_block_html( $item_id, 'favourite-items-page' ) );
	}
	echo wp_kses_post( '</div>' );
} else {
	$search_page_id = ersrv_get_page_id( 'search-reservations' );
	$search_page    = get_permalink( $search_page_id );
	?>
	<div class="woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info">
		<a class="woocommerce-Button button" href="<?php echo esc_url( $search_page ); ?>"><?php esc_html_e( 'Browse items', 'woocommerce' ); ?></a>
		<?php esc_html_e( 'There are no favourite items.', 'easy-reservations' ); ?>
	</div>
	<?php
}
