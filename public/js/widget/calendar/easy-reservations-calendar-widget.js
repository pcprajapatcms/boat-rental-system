jQuery( document ).ready( function( $ ) {
	'use strict';

	// Localized variables.
	var ajaxurl                           = ERSRV_Calendar_Widget_Script_Vars.ajaxurl;
	var start_of_week                     = parseInt( ERSRV_Calendar_Widget_Script_Vars.start_of_week );
	var date_format                       = ERSRV_Calendar_Widget_Script_Vars.date_format;
	var datepicker_next_month_button_text = ERSRV_Calendar_Widget_Script_Vars.datepicker_next_month_button_text;
	var datepicker_prev_month_button_text = ERSRV_Calendar_Widget_Script_Vars.datepicker_prev_month_button_text;

	/**
	 * Display the calendar widget when the reservable item is selected.
	 */
	$( document ).on( 'change', '#ersrv-widget-reservable-items', function() {
		// Item ID.
		var item_id = parseInt( $( this ).val() );

		// Hide the calendar.
		$( '.ersrv-widget-calendar, .ersrv-book-item-from-widget' ).hide();

		// Destroy the datepickers.
		$( '.ersrv-widget-calendar' ).datepicker( 'destroy' );

		// If no item id is selected.
		if ( -1 === item_id ) {
			return false;
		}

		// Send the AJAX to fetch the unavailability dates.
		block_element( $( '.ersrv-reservation-widget-container' ) );

		// Send the AJAX now.
		$.ajax( {
			dataType: 'JSON',
			url: ajaxurl,
			type: 'POST',
			data: {
				action: 'get_item_unavailable_dates',
				item_id: item_id,
			},
			success: function ( response ) {
				// Check for invalid ajax request.
				if ( 0 === response ) {
					console.log( 'easy reservations: invalid ajax request' );
					return false;
				}

				if ( 'unavailability-dates-fetched' !== response.data.code ) {
					return false;
				}

				// Unblock the element.
				unblock_element( $( '.ersrv-reservation-widget-container' ) );

				// Set the reserve button link.
				$( '.ersrv-book-item-from-widget' ).show();
				$( '.ersrv-book-item-from-widget a' ).attr( 'href', response.data.item_link );

				// Dates to disable. These are actually unavailability dates.
				var blocked_dates   = response.data.dates;
				var date_today      = new Date();
				var today_formatted = ersrv_get_formatted_date( date_today );
				var reserved_dates  = [];

				// Prepare the blocked out dates in a separate array.
				if ( 0 < blocked_dates.length ) {
					for ( var i in blocked_dates ) {
						reserved_dates.push( blocked_dates[i].date );
					}
				}

				// Display the calendar.
				$( '.ersrv-widget-calendar' ).show();
				$( '.ersrv-widget-calendar' ).datepicker( {
					beforeShowDay: function( date ) {
						var loop_date_formatted = ersrv_get_formatted_date( date );
						var date_enabled        = true;
						var date_class          = '';

						// If not the past date.
						if ( date_today <= date ) {
							// Add custom class to the active dates of the current month.
							var key = $.map( reserved_dates, function( val, i ) {
								if ( val === loop_date_formatted ) {
									return i;
								}
							} );
		
							// If the loop date is a blocked date.
							if ( 0 < key.length ) {
								date_class = 'ui-datepicker-unselectable ui-state-disabled ersrv-date-disabled';
							} else {
								date_class = 'ersrv-date-active';
							}
						} else {
							date_class = 'ersrv-date-disabled';
						}
		
						// Return the datepicker day object.
						return [ date_enabled, date_class ];
					},
					minDate: 0,
					weekStart: start_of_week,
					changeMonth: true,
					dateFormat: date_format,
					nextText: datepicker_next_month_button_text,
					prevText: datepicker_prev_month_button_text,
				} );
			},
		} );
	} );

	// Select the first reservable item on load.
	$( '#ersrv-widget-reservable-items option:eq(1)' ).prop( 'selected', true );
	$( '#ersrv-widget-reservable-items' ).trigger( 'change' );

	/**
	 * Return the formatted date based on the global date format.
	 */
	 function ersrv_get_formatted_date( date_obj ) {
		var month = ( ( '0' + ( date_obj.getMonth() + 1 ) ).slice( -2 ) );
		var date  = ( ( '0' + ( date_obj.getDate() ) ).slice( -2 ) );
		var year  = date_obj.getFullYear();

		// Replace the variables now.
		var formatted_date = date_format.replace( 'dd', date );
		formatted_date     = formatted_date.replace( 'mm', month );
		formatted_date     = formatted_date.replace( 'yy', year );

		return formatted_date;
	}

	/**
	 * Block element.
	 *
	 * @param {string} element
	 */
	 function block_element( element ) {
		element.addClass( 'non-clickable' );
	}

	/**
	 * Unblock element.
	 *
	 * @param {string} element
	 */
	function unblock_element( element ) {
		element.removeClass( 'non-clickable' );
	}
} );
