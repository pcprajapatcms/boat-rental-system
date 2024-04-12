/**
 * Lightbox jQuery file for images on reservation details page.
 */
jQuery( document ).ready( function( $ ) {
	/**
	 * When the image is clicked.
	 */
	$( document ).on( 'click', '.gallery-image-item img', function() {
		var current_img     = $( this );
		var current_img_div = current_img.parent( 'div' );
		var current_img_src = current_img.attr( 'src' );
		var current_img_alt = current_img.attr( 'alt' );

		// Open the lightbox.
		$( '.lightbox' ).fadeIn(300);
		$( '.lightbox' ).append( '<img src="' + current_img_src + '" alt="' + current_img_alt + '" />' );
		$( '.filter' ).css( 'background-image', 'url(' + current_img_src + ')' );
		$( 'html' ).css( 'overflow', 'hidden' );

		// Display both the arrows.
		$( '.arrowr, .arrowl' ).css( 'display', 'block' );

		// Hide the arrows based on the image clicked.
		if ( current_img_div.is( ':last-child' ) ) {
			$( '.arrowr' ).css( 'display', 'none' );
		} else if ( current_img_div.is( ':first-child' ) ) {
			$( '.arrowl' ).css( 'display', 'none' );
		}
	} );

	/**
	 * Close the lightbox.
	 */
	$( document ).on( 'click', '.close', function() {
		$( '.lightbox' ).fadeOut(300);
		$( '.lightbox img' ).remove();
		$( 'html' ).css( 'overflow', 'auto' );
	} );

	/**
	 * Close the lightbox on when Esc. key is pressed.
	 */
	$( document ).on( 'keyup', function( evt ) {
		// Check if the Esc. key is pressed.
		if ( 27 === evt.keyCode ) {
			$( '.lightbox' ).fadeOut(300);
			$( '.lightbox img' ).remove();
			$( 'html' ).css( 'overflow', 'auto' );
		}
	} );

	/**
	 * When the right hand arrow is clicked.
	 */
	$( document ).on( 'click', '.arrowr', function() {
		// Get the currently showing image.
		var current_image_src = $( '.lightbox img' ).attr( 'src' );

		// Search this image in the grid.
		var grid_image_div_ele = $( '.gallery-image-item' ).find( 'img[src="' + current_image_src + '"]' ).parent( 'div' );
		var new_image_src      = grid_image_div_ele.next().find( 'img' ).attr( 'src' );

		// Set the new image as the current display.
		$( '.lightbox img' ).attr( 'src', new_image_src );
		$( '.filter' ).css( 'background-image', 'url(' + new_image_src + ')' );

		// Display the left arrow.
		$( '.arrowl' ).css( 'display', 'block' );

		// Hide the right arrow, if the currently displaying picture is the last in the grid.
		if ( grid_image_div_ele.next().is( ':last-child' ) ) {
			$( '.arrowr' ).css( 'display', 'none' );
		}
	} );

	/**
	 * When the left hand arrow is clicked.
	 */
	$( document ).on( 'click', '.arrowl', function() {
		// Get the currently showing image.
		var current_image_src = $( '.lightbox img' ).attr( 'src' );

		// Search this image in the grid.
		var grid_image_div_ele = $( '.gallery-image-item' ).find( 'img[src="' + current_image_src + '"]' ).parent( 'div' );
		var new_image_src      = grid_image_div_ele.prev().find( 'img' ).attr( 'src' );

		// Set the new image as the current display.
		$( '.lightbox img' ).attr( 'src', new_image_src );
		$( '.filter' ).css( 'background-image', 'url(' + new_image_src + ')' );

		// Display the right arrow.
		$( '.arrowr' ).css( 'display', 'block' );

		// Hide the left arrow, if the currently displaying picture is the first in the grid.
		if ( grid_image_div_ele.prev().is( ':first-child' ) ) {
			$( '.arrowl' ).css( 'display', 'none' );
		}
	} );
} );
