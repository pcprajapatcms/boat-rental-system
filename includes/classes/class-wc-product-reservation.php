<?php
/**
 * Custom product type - easy reservations class.
 *
 * @link       https://www.cmsminds.com/
 * @since      1.0.0
 *
 * @package    Easy_Reservations
 * @subpackage Easy_Reservations/includes/classes
 */

/**
 * Custom product type - easy reservations class.
 *
 * Defines the product type and it's properties.
 *
 * @package    Easy_Reservations
 * @subpackage Easy_Reservations/includes/classes
 * @author     cmsMinds <info@cmsminds.com>
 */
class WC_Product_Reservation extends WC_Product {
	/**
	 * Initialize the class and set its properties.
	 *
	 * @param WC_Product $product WooCommerce product object.
	 * @since 1.0.0
	 */
	public function __construct( $product ) {
		$this->product_type = 'reservation';
		parent::__construct( $product );
	}
}
