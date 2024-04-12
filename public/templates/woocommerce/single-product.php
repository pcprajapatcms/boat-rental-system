<?php
/**
 * This file is used for templating the single reservation item.
 *
 * @since 1.0.0
 * @package Easy_Reservations
 * @subpackage Easy_Reservations/public/templates/woocommerce
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

// Header.
get_header();

$item_post    = get_post( get_the_ID() );
$wc_item      = wc_get_product();
$item_details = ersrv_get_item_details( $item_post->ID );

// Cost details.
$adult_charge = ( ! empty( $item_details['adult_charge'] ) ) ? $item_details['adult_charge'] : 0;
$kid_charge   = ( ! empty( $item_details['kid_charge'] ) ) ? $item_details['kid_charge'] : 0;

// Amenities.
$amenities = ( ! empty( $item_details['amenities'] ) ) ? $item_details['amenities'] : array();

// Location.
$location = ( ! empty( $item_details['location'] ) ) ? $item_details['location'] : '';

// Google maps API key.
$api_key = ersrv_get_plugin_settings( 'ersrv_google_maps_api_key' );

// Security amount.
$security_amount = ( ! empty( $item_details['security_amount'] ) ) ? $item_details['security_amount'] : 0;

// Accomodation Limit.
$accomodation_limit = ( ! empty( $item_details['accomodation_limit'] ) ) ? $item_details['accomodation_limit'] : '';

// Reservation Limits.
$min_reservation_period = ( ! empty( $item_details['min_reservation_period'] ) ) ? $item_details['min_reservation_period'] : '';
$max_reservation_period = ( ! empty( $item_details['max_reservation_period'] ) ) ? $item_details['max_reservation_period'] : '';
$reservation_period_str = ( ! empty( $item_details['reservation_period_str'] ) ) ? $item_details['reservation_period_str'] : '';

// Captain details.
$has_captain      = ( ! empty( $item_details['has_captain'] ) ) ? $item_details['has_captain'] : 'no';
$has_captain_text = ( ! empty( $item_details['has_captain_text'] ) ) ? $item_details['has_captain_text'] : '';
$captain_id       = ( ! empty( $item_details['captain_id'] ) ) ? $item_details['captain_id'] : '';

// Total reservations.
$total_reservations      = ( ! empty( $item_details['total_reservations'] ) ) ? $item_details['total_reservations'] : 0;
$total_reservations_icon = ( ! empty( $item_details['total_reservations_icon'] ) ) ? $item_details['total_reservations_icon'] : '';

// Reservation Item Type.
$item_type_str_with_link = ( ! empty( $item_details['item_type_str_with_link'] ) ) ? $item_details['item_type_str_with_link'] : '';

// Reservation item types.
$reservation_item_types = get_terms(
	array(
		'taxonomy' => 'reservation-item-type',
		'hide_empty' => true,
	)
);

// WooCommerce currency.
$woo_currency = get_woocommerce_currency_symbol();

// Social share URLs.
$social_share_urls = array(
	'facebook' => array(
		'icon'   => 'fab fa-facebook-f',
		'link'   => 'https://facebook.com/sharer.php?u=' . get_permalink( $item_post->ID ),
		'class'  => 'icon facebook',
		'target' => '_blank',
	),
	'twitter'  => array(
		'icon'   => 'fab fa-twitter',
		'link'   => 'https://twitter.com/intent/tweet?text=' . $item_post->post_title . '&url=' . get_permalink( $item_post->ID ),
		'class'  => 'icon twitter',
		'target' => '_blank',
	),
);
/**
 * This hook is fired on the reservation item single page.
 *
 * This filter help in managing the social platforms for sharing the reservation item.
 *
 * @param array $social_share_urls Array of social media platforms.
 * @return array
 * @since 1.0.0
 */
$social_share_urls = apply_filters( 'ersrv_reservation_item_socia_share_platforms', $social_share_urls );

// Banner image.
$banner_image_id  = get_post_meta( $item_post->ID, 'ersrv_banner_image_id', true );
$banner_image_url = ersrv_get_attachment_url_from_attachment_id( $banner_image_id );
$banner_image_url = ( ! empty( $banner_image_url ) ) ? $banner_image_url : ERSRV_PLUGIN_URL . 'public/images/banner-bg.jpg';

// Gallery images.
$featured_image_id = $wc_item->get_image_id();
$gallery_image_ids = $wc_item->get_gallery_image_ids();
$gallery_image_ids = ( ! empty( $gallery_image_ids ) ) ? array_merge( array( $featured_image_id ), $gallery_image_ids ) : array( $featured_image_id );
$gallery_images    = array();

// Prepare the array of gallery image URLs to make them unique.
if ( ! empty( $gallery_image_ids ) && is_array( $gallery_image_ids ) ) {
	// Iterate through the image IDs to collect the image URL.
	foreach ( $gallery_image_ids as $gallery_image_id ) {
		$gallery_images[] = ersrv_get_attachment_url_from_attachment_id( $gallery_image_id );
	}
}

// Remove the duplicate images.
$gallery_images = ( ! empty( $gallery_images ) && is_array( $gallery_images ) ) ? array_values( array_unique( array_filter( $gallery_images ) ) ) : array();

// For lengthy product titles.
$product_title_class = ( 90 <= strlen( $item_post->post_title ) ) ? 'font-Poppins font-size-26 font-weight-semibold color-white' : 'font-Poppins font-size-40 font-weight-semibold color-white';
$product_title_class = apply_filters( 'ersrv_reservation_item_title_attribute_class', $product_title_class );
?>
<section class="wrapper single-reserve-page" id="wrapper" data-item="<?php echo esc_attr( $item_post->ID ); ?>">
	<div class="banner text-center" style="background-image: url( '<?php echo esc_url( $banner_image_url ); ?>' );">
		<div class="container">
			<div class="details mx-auto font-lato">
				<div class="page-title mb-3">
					<h1 class="<?php echo esc_attr( $product_title_class ); ?>"><?php echo wp_kses_post( $item_post->post_title ); ?></h1>
				</div>
				<?php
				/**
				 * This hook executes on the reservation details page.
				 *
				 * This hook helps in displaying custom content under the item title.
				 *
				 * @param int $item_post->ID Reservation item ID.
				 */
				do_action( 'ersrv_after_reservation_item_title', $item_post->ID );
				?>
				<div class="boat-options d-flex justify-content-center align-items-center color-white font-size-16 flex-column flex-md-row">
					<!-- ITEM TYPES -->
					<?php if ( ! empty( $item_type_str_with_link ) ) { ?>
						<div class="d-flex align-items-center first mb-2 mb-md-0 mr-3 pr-1">
							<img src="<?php echo esc_url ( ERSRV_PLUGIN_URL . 'public/images/Ship-icon.png' ); ?>" alt="">
							<span class="ml-2 font-weight-medium"><?php echo wp_kses_post( $item_type_str_with_link ); ?></span>
						</div>
					<?php } ?>

					<!-- ITEM CAPTAIN -->
					<?php if ( ! empty( $has_captain ) && 'yes' === $has_captain ) { ?>
						<div class="d-flex align-items-center second mb-2 mb-md-0 mr-3 pr-1">
							<img src="<?php echo esc_url ( ERSRV_PLUGIN_URL . 'public/images/user-icon.png' ); ?>" alt="">
							<?php if ( ! empty( $has_captain_text ) ) { ?>
								<span class="ml-2 font-weight-medium"><?php echo esc_html( $has_captain_text ); ?></span>
							<?php } ?>
						</div>
					<?php } ?>

					<!-- PREVIOUS RESERVATIONS -->
					<?php if ( ! empty( $total_reservations ) && 0 !== $total_reservations ) { ?>
						<div class="d-flex align-items-center third mb-2 mb-md-0">
							<?php if ( ! empty( $total_reservations_icon ) ) { ?>
								<img src="<?php echo esc_url ( $total_reservations_icon ); ?>" alt="">
							<?php } ?>
							<span class="ml-2 font-weight-medium"><?php echo esc_html( $total_reservations ); ?></span>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
	<div class="content-part">
		<div class="container">
			<div class="row">
				<div class="col-12 col-lg-7 col-xl-8">
					<div class="ship-features info-box">
						<h3 class="section-title font-Poppins font-size-24 font-weight-bold d-block color-black text-decoration-none show-on-load">
							<span class=""><?php esc_html_e( 'Availability', 'easy-reservations' ); ?></span>
						</h3>
						<div class="ship-inner-features show show-on-load" id="ship-features-collapse">
							<div class="dropdown-divider"></div>
							<div class="card">
								<div class="datepicker datepicker-inline ersrv-item-availability-calendar"></div>
								<div class="d-flex flex-wrap flex-column">
									<div class="ersrv-available-dates-notifier"><span><?php esc_html_e( 'Available Dates', 'easy-reservations' ); ?></span></div>
									<div class="ersrv-unavailable-dates-notifier"><span><?php esc_html_e( 'Unvailable Dates', 'easy-reservations' ); ?></span></div>
								</div>
							</div>
						</div>
					</div>
					<div class="ship-description info-box">
						<a class="section-title font-Poppins font-size-24 font-weight-bold d-block color-black text-decoration-none" data-toggle="collapse" href="#ship-description-collapse" role="button" aria-expanded="true" aria-controls="ship-description-collapse">
							<span class=""><?php esc_html_e( 'Description', 'easy-reservations' ); ?></span>
						</a>
						<div class="collapse show" id="ship-description-collapse">
							<div class="dropdown-divider"></div>
							<?php the_content(); ?>
							<div class="dropdown-divider"></div>
							<!-- GALLERY IMAGES -->
							<?php if ( ! empty( $gallery_images ) && ! empty( $gallery_images ) ) {
								// Get the last index of the array.
								$gallery_images_last_index = count( $gallery_images ) - 1;
								?>
								<div class="gallery-images ersrv_count_images_<?php echo esc_attr( $gallery_images_last_index ); ?>">
									<?php foreach ( $gallery_images as $index => $gallery_image_url ) {
										// Get the filename.
										$image_filename = basename( $gallery_image_url );

										/**
										 * Last image custom class.
										 * And, this should work only when the images are more than 5.
										 */
										$last_gallery_image_custom_class = '';
										$last_gallery_image_custom_text  = '';
										if ( 6 < count( $gallery_images ) && 5 === $index ) {
											$last_gallery_image_custom_class = 'gallery-last-image-overlay';
											$last_gallery_image_custom_text  = sprintf( __( '+%1$d images', 'easy-reservations' ), ( count( $gallery_image_ids ) - 6 ) );
										}

										// Hide the images after 6 images.
										$display_none_image_class = ( 5 < $index ) ? 'd-none' : '';
										?>
										<div data-text="<?php echo esc_html( $last_gallery_image_custom_text ); ?>" class="gallery-image-item <?php echo esc_attr( "{$last_gallery_image_custom_class} {$display_none_image_class}" ); ?>">
											<img src="<?php echo esc_url( $gallery_image_url ); ?>" alt="<?php echo esc_attr( $image_filename ); ?>" />
										</div>
									<?php } ?>
								</div>
							<?php } ?>
						</div>
					</div>
					<div class="ship-location info-box">
						<a class="section-title font-Poppins font-size-24 font-weight-bold d-block color-black text-decoration-none" data-toggle="collapse" href="#ship-location-collapse" role="button" aria-expanded="false" aria-controls="ship-location-collapse">
							<span class=""><?php esc_html_e( 'Location', 'easy-reservations' ); ?></span>
						</a>
						<div class="collapse" id="ship-location-collapse">
							<div class="dropdown-divider"></div>
							<?php if ( ! empty( $api_key ) ) { ?>
								<iframe width="100%" height="400px" src="https://www.google.com/maps/embed/v1/place?key=<?php echo esc_html( $api_key ); ?>&q=<?php echo esc_html( $location ); ?>" style="border:0" loading="lazy" allowfullscreen></iframe>
							<?php } else { ?>
								<p><?php echo wp_kses_post( $location ); ?></p>
							<?php } ?>
						</div>
					</div>
					<?php
					/**
					 * This hook runs on the reservation item details page after all the details on the left side.
					 *
					 * This hook helps adding any custom information on the reservation details page.
					 *
					 * @param int $item_post->ID Item ID.
					 */
					do_action( 'ersrv_after_item_details', $item_post->ID );
					?>
				</div>
				<div class="col-12 col-lg-5 col-xl-4">
					<div class="sidebar-wrapper">
						<div class="price-box bgcolor-accent rounded-xl py-2">
							<div class="d-flex align-items-center justify-content-center py-1">
								<?php
								echo wp_kses(
									wc_price( $adult_charge ),
									array(
										'span' => array(
											'class' => array(),
										),
									)
								);
								?>
								<span class="price-info font-size-20 font-lato font-weight-medium color-white">(<?php echo esc_html( ersrv_get_reservation_item_cost_type_text() ); ?>)</span>
							</div>
						</div>
						<div class="book-tour bgcolor-white rounded-xl text-center">
							<div class="title mb-4">
								<h3 class="font-Poppins font-size-24 font-weight-bold color-black"><?php esc_html_e( 'Book The Tour', 'easy-reservations' ); ?></h3>
								<span><?php echo wp_kses_post( $reservation_period_str ); ?></span>
							</div>
							<div class="details text-left">
								<form action="">
									<div class="input-daterange d-flex flex-column flex-fill mb-3 pb-2 ersrv-single-reservation-item-checkin-checkout">
										<input placeholder="<?php esc_html_e( 'Checkin', 'easy-reservations' ); ?>" type="text" id="ersrv-single-reservation-checkin-datepicker" class="form-control date-control text-left rounded-lg" readonly>
										<div class="input-group-addon font-Poppins font-size-18 font-weight-light color-black-400 py-2 my-1 text-center"><?php esc_html_e( 'to', 'easy-reservations' ); ?></div>
										<input placeholder="<?php esc_html_e( 'Checkout', 'easy-reservations' ); ?>" type="text" id="ersrv-single-reservation-checkout-datepicker" class="form-control date-control text-left rounded-lg" readonly>
										<p class="ersrv-reservation-error checkin-checkout-dates-error"></p>
										<p class="get-total-hrs" style="display:none;"></p>
									</div>
									<div class="book-items-wrapper mb-2 pb-3 ersrv-single-reservation-item-accomodation">
										<label for="book-items" class="font-Poppins font-size-16 color-black"><?php echo esc_html( sprintf( __( 'Guests (Limit: %1$d)', 'easy-reservations' ), $accomodation_limit ) ); ?><span class="required">*</span></label>
										<div class="inputs-with-label">
											<input min="1" id="adult-accomodation-count" placeholder="<?php esc_html_e( 'No. of adults', 'easy-reservations' ); ?>" type="number" class="ersrv-accomodation-count form-control mb-3" />
											<label for="adult-accomodation-count"><?php echo wp_kses_post( sprintf( __( 'per adult: %1$s%3$s%2$s', 'easy-reservations' ), '<span>', '</span>', wc_price( $adult_charge ) ) ); ?></label>
										</div>
										<div class="inputs-with-label">
											<input min="0" id="kid-accomodation-count" placeholder="<?php esc_html_e( 'No. of kids', 'easy-reservations' ); ?>" type="number" class="ersrv-accomodation-count form-control" />
											<label for="kid-accomodation-count"><?php echo wp_kses_post( sprintf( __( 'per kid: %1$s%3$s%2$s', 'easy-reservations' ), '<span>', '</span>', wc_price( $kid_charge ) ) ); ?></label>
										</div>
										<p class="ersrv-reservation-error accomodation-error"></p>
									</div>
									<?php if ( ! empty( $amenities ) && is_array( $amenities ) ) { ?>
										<div class="ersrv-item-amenities-wrapper checkbox-wrapper mb-4 pb-3">
											<label for="amenities" class="font-Poppins font-size-16 color-black"><?php esc_html_e( 'Amenities', 'easy-reservations' ); ?></label>
											<?php foreach ( $amenities as $amenity_data ) {
												$amenity_title     = ( ! empty( $amenity_data['title'] ) ) ? $amenity_data['title'] : '';
												$amenity_cost      = ( ! empty( $amenity_data['cost'] ) ) ? (float) $amenity_data['cost'] : 0.00;
												$amenity_slug      = ( ! empty( $amenity_title ) ) ? sanitize_title( $amenity_title ) : '';
												$amenity_cost_type = ( ! empty( $amenity_data['cost_type'] ) ) ? $amenity_data['cost_type'] : 'one_time';
												$cost_type_text    = ( 'one_time' === $amenity_cost_type ) ? ersrv_get_amenity_single_fee_text() : ersrv_get_amenity_daily_fee_text();
												?>
												<div class="custom-control custom-switch ersrv-single-amenity-block mb-2" data-cost_type="<?php echo esc_attr( $amenity_cost_type ); ?>" data-cost="<?php echo esc_attr( $amenity_cost ); ?>" data-amenity="<?php echo esc_attr( $amenity_title ); ?>">
													<input type="checkbox" class="custom-control-input ersrv-new-reservation-single-amenity" id="amenity-<?php echo esc_html( $amenity_slug ); ?>">
													<label class="custom-control-label font-size-15" for="amenity-<?php echo esc_html( $amenity_slug ); ?>">
														<span class="d-block font-lato font-weight-bold color-black pb-2"><?php echo esc_html( $amenity_title ); ?> </span>
														<span>
															<span class="font-lato font-weight-bold color-accent">
																<?php
																echo wp_kses(
																	wc_price( $amenity_cost ),
																	array(
																		'span' => array(
																			'class' => array(),
																		),
																	)
																);
																?>
															</span> | <span class="font-lato font-weight-normal color-black-500"><?php echo esc_html( $cost_type_text ); ?></span>
														</span>
													</label>
												</div>
											<?php } ?>
										</div>
									<?php } ?>
									<div class="calc-wrapper mb-3">
										<!-- SECURITY AMOUNT -->
										<h4 class="ersrv-item-details-security-amount font-Poppins font-size-16 color-black font-weight-bold mb-3"><?php echo wp_kses_post( sprintf( __( 'Security: %1$s', 'easy-reservations' ), wc_price( $security_amount ) ) ); ?>
										<!-- RESERVATION ITEM SUBTOTAL -->
										<h4 class="ersrv-item-details-reservation-subtotal-amount font-Poppins font-size-16 color-black font-weight-bold mb-0"><?php echo wp_kses_post( sprintf( __( 'Total: %1$s', 'easy-reservations' ), '<a class="text-decoration-none ersrv-split-reservation-cost" href="javascript:void(0);"><span class="ersrv-reservation-item-subtotal ersrv-cost font-weight-bold color-accent ">--</span></a>' ) ); ?></h4>
										<div class="ersrv-reservation-details-item-summary">
											<div class="ersrv-reservation-details-item-summary-wrapper p-3">
												<table class="table table-borderless">
													<tbody>
														<tr class="adults-subtotal">
															<th><?php esc_html_e( 'Adults:', 'easy-reservations' ); ?></th>
															<td><span data-cost="" class="ersrv-cost font-lato font-weight-bold color-accent">--</span></td>
														</tr>
														<tr class="kids-subtotal">
															<th><?php esc_html_e( 'Kids:', 'easy-reservations' ); ?></th>
															<td><span data-cost="" class="ersrv-cost font-lato font-weight-bold color-accent">--</span></td>
														</tr>
														<tr class="amenities-subtotal">
															<th><?php esc_html_e( 'Amenities:', 'easy-reservations' ); ?></th>
															<td><span data-cost="" class="ersrv-cost font-lato font-weight-bold color-accent">--</span></td>
														</tr>
														<tr class="security-subtotal">
															<th><?php esc_html_e( 'Security:', 'easy-reservations' ); ?></th>
															<td><span data-cost="" class="ersrv-cost font-lato font-weight-bold color-accent">--</span></td>
														</tr>
														<tr class="reservation-item-subtotal">
															<th><?php esc_html_e( 'Total:', 'easy-reservations' ); ?></th>
															<td><span data-cost="" class="ersrv-cost font-lato font-weight-bold color-accent">--</span></td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
										<input type="hidden" id="accomodation-limit" value="<?php echo esc_html( $accomodation_limit ); ?>" />
										<input type="hidden" id="min-reservation-period" value="<?php echo esc_html( $min_reservation_period ); ?>" />
										<input type="hidden" id="max-reservation-period" value="<?php echo esc_html( $max_reservation_period ); ?>" />
										<input type="hidden" id="adult-charge" value="<?php echo esc_html( $adult_charge ); ?>" />
										<input type="hidden" id="kid-charge" value="<?php echo esc_html( $kid_charge ); ?>" />
										<input type="hidden" id="security-amount" value="<?php echo esc_html( $security_amount ); ?>" />
									</div>
									<div class="instant-booking">
										<button type="button" class="ersrv-proceed-to-checkout-single-reservation-item btn btn-primary btn-block btn-xl font-lato font-size-18 font-weight-bold">
											<span class="mr-3"><img src="<?php echo esc_url( ERSRV_PLUGIN_URL . 'public/images/Instant-booking.png' ); ?>" alt="instant-booking"></span>
											<?php
												$get_booking_btn_text = ersrv_get_plugin_settings( 'ersrv_product_single_page_add_to_cart_button_text' );
												if(!empty($get_booking_btn_text)){
													$booking_button_text = $get_booking_btn_text;
												}else{
													$booking_button_text = 'Instant Booking';
												}
											?>
											<span><?php esc_html_e( $booking_button_text, 'easy-reservations' ); ?></span>
										</button>
										<div class="ersrv-powered-by-cmsminds" role="complementary">
											<a class="ersrv-cmsminds-logo" href="https://cmsminds.com" title="<?php esc_html_e( 'Powered by cmsMinds opens in a new window', 'easy-reservations' ); ?>" target="_blank"><?php esc_html_e( 'Powered by cmsMinds', 'easy-reservations' ); ?></a>
										</div>
									</div>
									<div class="dropdown-divider my-4 py-2"></div>
									<div class="contact-owner mb-3 pb-2">
										<button type="button" class="ersrv-contact-owner-button btn btn-outline-fill-primary btn-block btn-xl font-lato font-size-18 font-weight-bold">
											<span><?php esc_html_e( 'Contact Owner', 'easy-reservations' ); ?></span>
										</button>
									</div>
									<div class="social">
										<div class="d-flex align-items-center justify-content-center">
											<?php
											if ( ! empty( $social_share_urls ) && is_array( $social_share_urls ) ) {
												foreach ( $social_share_urls as $social_share_url ) {
													?>
													<a target="<?php echo esc_attr( $social_share_url['target'] ); ?>" href="<?php echo esc_url( $social_share_url['link'] ); ?>" class="<?php echo esc_attr( $social_share_url['class'] ); ?>">
														<span><i class="<?php echo esc_attr( $social_share_url['icon'] ); ?>"></i></span>
													</a>
													<?php
												}
											}
											?>
										</div>
									</div>
								</form>
							</div>
						</div>
						<div class="advanced-search bgcolor-white rounded-xl text-center">
							<div class="title pb-2">
								<h3 class="font-Poppins font-size-24 font-weight-bold color-black"><?php esc_html_e( 'Advanced Search', 'easy-reservations' ); ?></h3>
							</div>
							<div class="details text-left">
								<form action="">
									<div class="mb-2">
										<input type="text" class="ersrv-item-search-location form-control text-left rounded-lg" placeholder="<?php esc_html_e( 'Desired location', 'easy-reservations' ); ?>">
									</div>
									<div class="mb-2">
										<input type="number" class="ersrv-item-search-accomodation form-control ship-icon-field text-left rounded-lg" placeholder="<?php esc_html_e( 'Capacity', 'easy-reservations' ); ?>">
									</div>
									<div class="input-daterange d-flex flex-column flex-fill pb-2">
										<input id="ersrv-search-checkin" type="text" class="form-control date-control text-left rounded-lg mb-2" placeholder="<?php esc_html_e( 'Checkin', 'easy-reservations' ); ?>" readonly>
										<input id="ersrv-search-checkout" type="text" class="form-control date-control text-left rounded-lg" placeholder="<?php esc_html_e( 'Checkout', 'easy-reservations' ); ?>" readonly>
									</div>
									<?php if ( ! empty( $reservation_item_types ) && is_array( $reservation_item_types ) ) { ?>
										<div class="book-items-wrapper pb-2">
											<select class="selectpicker form-control Boat-Types" id="boat-types" data-size="5" data-style="btn-outline-secondary focus-none" title="<?php esc_html_e( 'Item type', 'easy-reservations' ); ?>">
												<option value=""><?php esc_html_e( 'Item type', 'easy-reservations' ); ?></option>
												<?php foreach ( $reservation_item_types as $item_type ) { ?>
													<option value="<?php echo esc_attr( $item_type->term_id ); ?>"><?php echo esc_html( $item_type->name . ' (' . $item_type->count . ')' ); ?></option>
												<?php } ?>
											</select>
										</div>
									<?php } ?>
									<div class="search-box">
										<button type="button" class="ersrv-submit-search-request btn btn-primary btn-block btn-xl font-lato font-size-18 font-weight-bold">
											<span class="mr-3"><img src="<?php echo esc_url( ERSRV_PLUGIN_URL . 'public/images/Search.png' ); ?>" alt="Search"></span>
											<span><?php esc_html_e( 'Search', 'easy-reservations' ); ?></span>
										</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- LIGHTBOX -->
	<div class="lightbox">
		<div class="title"></div>
		<div class="filter"></div>
		<div class="arrowr"></div>
		<div class="arrowl"></div>
		<div class="close"></div>
	</div>
</section>
<?php
get_footer();
