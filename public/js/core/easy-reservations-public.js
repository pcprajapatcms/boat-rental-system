jQuery(document).ready(function ($) {
  "use strict";
  var hrsForBoat = 0;
  // Localized variables.
  var ajaxurl = ERSRV_Public_Script_Vars.ajaxurl;
  var is_product = ERSRV_Public_Script_Vars.is_product;
  var is_checkout = ERSRV_Public_Script_Vars.is_checkout;
  var is_search_page = ERSRV_Public_Script_Vars.is_search_page;
  var reservation_item_details =
    ERSRV_Public_Script_Vars.reservation_item_details;
  var woo_currency = ERSRV_Public_Script_Vars.woo_currency;
  var reservation_guests_err_msg =
    ERSRV_Public_Script_Vars.reservation_guests_err_msg;
  var reservation_only_kids_guests_err_msg =
    ERSRV_Public_Script_Vars.reservation_only_kids_guests_err_msg;
  var reservation_guests_count_exceeded_err_msg =
    ERSRV_Public_Script_Vars.reservation_guests_count_exceeded_err_msg;
  var reservation_checkin_checkout_missing_err_msg =
    ERSRV_Public_Script_Vars.reservation_checkin_checkout_missing_err_msg;
  var reservation_checkin_missing_err_msg =
    ERSRV_Public_Script_Vars.reservation_checkin_missing_err_msg;
  var reservation_checkout_missing_err_msg =
    ERSRV_Public_Script_Vars.reservation_checkout_missing_err_msg;
  var reservation_lesser_reservation_days_err_msg =
    ERSRV_Public_Script_Vars.reservation_lesser_reservation_days_err_msg;
  var reservation_greater_reservation_days_err_msg =
    ERSRV_Public_Script_Vars.reservation_greater_reservation_days_err_msg;
  var reservation_blocked_dates_err_msg =
    ERSRV_Public_Script_Vars.reservation_blocked_dates_err_msg;
  var search_reservations_page_url =
    ERSRV_Public_Script_Vars.search_reservations_page_url;
  var date_format = ERSRV_Public_Script_Vars.date_format;
  var toast_success_heading = ERSRV_Public_Script_Vars.toast_success_heading;
  var toast_error_heading = ERSRV_Public_Script_Vars.toast_error_heading;
  var toast_notice_heading = ERSRV_Public_Script_Vars.toast_notice_heading;
  var invalid_reservation_item_is_error_text =
    ERSRV_Public_Script_Vars.invalid_reservation_item_is_error_text;
  var reservation_add_to_cart_error_message =
    ERSRV_Public_Script_Vars.reservation_add_to_cart_error_message;
  var reservation_item_contact_owner_error_message =
    ERSRV_Public_Script_Vars.reservation_item_contact_owner_error_message;
  var driving_license_allowed_extensions =
    ERSRV_Public_Script_Vars.driving_license_allowed_extensions;
  var driving_license_invalid_file_error =
    ERSRV_Public_Script_Vars.driving_license_invalid_file_error;
  var cancel_reservation_confirmation_message =
    ERSRV_Public_Script_Vars.cancel_reservation_confirmation_message;
  var checkin_provided_checkout_not =
    ERSRV_Public_Script_Vars.checkin_provided_checkout_not;
  var checkout_provided_checkin_not =
    ERSRV_Public_Script_Vars.checkout_provided_checkin_not;
  var trim_zeros_from_price = ERSRV_Public_Script_Vars.trim_zeros_from_price;
  var enable_time_with_date = ERSRV_Public_Script_Vars.enable_time_with_date;
  var current_theme = ERSRV_Public_Script_Vars.current_theme;
  var datepicker_next_month_button_text =
    ERSRV_Public_Script_Vars.datepicker_next_month_button_text;
  var datepicker_prev_month_button_text =
    ERSRV_Public_Script_Vars.datepicker_prev_month_button_text;
  // Custom vars.
  var quick_view_reserved_dates = [];
  var quick_view_unavailable_weekdays = [];
  // If sidebar is to be removed on reservation single page.
  if ("yes" === is_product || "yes" === is_search_page) {
    $("#secondary").remove();
    $("#content-bottom-widgets").remove();
    $("#primary").css("width", "100%");
  }

  // For easy-storefront theme.
  if (
    "easy-storefront" === current_theme ||
    "new-york-business" === current_theme ||
    "storefront" === current_theme
  ) {
    if ("yes" === is_search_page) {
      $(".content-area")
        .removeClass("col-sm-8 col-lg-8")
        .addClass("col-sm-12 col-lg-12");
      $("#content .container.background")
        .addClass("container-fluid px-0")
        .removeClass("container");
    }
  }

  // Search page checkin and checkout dates.
  if ($("#ersrv-search-checkin").length) {
    $("#ersrv-search-checkin, #ersrv-search-checkout").datepicker({
      onSelect: function (selected_date, instance) {
        if ("ersrv-search-checkin" === instance.id) {
          // Min date for checkout should be on/after the checkin date.
          $("#ersrv-search-checkout").datepicker(
            "option",
            "minDate",
            selected_date
          );
          setTimeout(function () {
            $("#ersrv-search-checkout").datepicker("show");
          }, 16);
        }
      },
      dateFormat: date_format,
      minDate: 0,
      nextText: datepicker_next_month_button_text,
      prevText: datepicker_prev_month_button_text,
    });
  }

  // If it's the product page.
  if ("yes" === is_product) {
    var reserved_dates = reservation_item_details.reserved_dates;
    var unavailable_weekdays = reservation_item_details.unavailable_weekdays;
    var unavailable_weekdays_arr = [];
    $.map(unavailable_weekdays, function (val) {
      unavailable_weekdays_arr.push(parseInt(val));
    });

    var date_today = new Date();
    var blocked_dates = [];

    // Prepare the blocked out dates in a separate array.
    if (0 < reserved_dates.length) {
      for (var i in reserved_dates) {
        blocked_dates.push(reserved_dates[i].date);
      }
    }

    // Availability calendar 2 months.
    $(".ersrv-item-availability-calendar").datepicker({
      beforeShowDay: function (date) {
        var loop_date_formatted = ersrv_get_formatted_date(date);
        var date_enabled = true;
        var date_class = "";
        var date_message = "";

        // If not the past date.
        if (date_today <= date) {
          // Add custom class to the active dates of the current month.
          var key = $.map(blocked_dates, function (val, i) {
            if (val === loop_date_formatted) {
              return i;
            }
          });

          // If the loop date is a blocked date.
          if (0 < key.length) {
            var index = key[0];
            date_message = reserved_dates[index].message;
            date_class = "ersrv-date-disabled non-clickable";
          } else {
            date_class = "ersrv-date-active";
          }

          // Check for the unavailable weekdays.
          if (0 < unavailable_weekdays_arr.length) {
            var weekday = date.getDay();
            if (-1 !== $.inArray(weekday, unavailable_weekdays_arr)) {
              date_class = "ersrv-date-disabled non-clickable";
            }
          }
        } else {
          date_class = "ersrv-date-disabled non-clickable";
        }

        // Return the datepicker day object.
        return [date_enabled, date_class, date_message];
      },
      numberOfMonths: 2,
      dateFormat: date_format,
      minDate: 0,
      nextText: datepicker_next_month_button_text,
      prevText: datepicker_prev_month_button_text,
    });

    // Checkin and checkout datepicker.
    if ("yes" === enable_time_with_date) {
      function datePickerWithTime() {
        jQuery(function () {
          // Get date and time format from the backend
          var dateAndTimeFormat_DT = "Y/m/d H:i";

          // Get the current date
          var currentDate_DT = new Date();
          var currentYear_DT = currentDate_DT.getFullYear();
          var currentMonth_DT = currentDate_DT.getMonth() + 1;
          var currentDay_DT = currentDate_DT.getDate();

          // Define the array of disabled dates
          var disabledDates_DT = [];
          // disabledDates_DT = ["2023/11/16", "2023/11/15", "2023/11/20"];

          var originalDates = blocked_dates;

          var newDates = originalDates.map(function (date) {
            var parts = date.split("/");
            if (parts.length === 3) {
              return parts[2] + "/" + parts[0] + "/" + parts[1];
            } else {
              return date; // If the date format is invalid, return the original date
            }
          });
          disabledDates_DT = newDates;
          // Initialize the start and end date variables
          var startDate_DTs = null;
          var endDate_DTs = null;
          // Show the check-in datepicker
          jQuery("#ersrv-single-reservation-checkin-datepicker").datetimepicker(
            {
              format: dateAndTimeFormat_DT,
              // inline: true,
              onShow: function (ct) {
                this.setOptions({
                  minDate:
                    currentYear_DT +
                    "-" +
                    currentMonth_DT +
                    "-" +
                    currentDay_DT,
                  disabledDates: disabledDates_DT,
                  dayOfWeekStart: 1,
                  onGenerate: function (ct) {
                    validateWeekends();
                    startDate_DTs = ct;
                    calculateTimeDifference(startDate_DTs, endDate_DTs);
                  },
                });
                jQuery(
                  "#ersrv-single-reservation-checkout-datepicker"
                ).datetimepicker("hide");
              },
              timepicker: true,
              onSelectTime: function (ct) {
                validateEndDate();
              },
              onSelectTime: function () {
                console.log("closed");
                jQuery(
                  "#ersrv-single-reservation-checkout-datepicker"
                ).datetimepicker("show");
              },
            }
          );
          // Show the checkout datepicker
          jQuery(
            "#ersrv-single-reservation-checkout-datepicker"
          ).datetimepicker({
            format: dateAndTimeFormat_DT,
            minDate:
              currentYear_DT + "-" + currentMonth_DT + "-" + currentDay_DT,
            onShow: function (ct) {
              this.setOptions({
                minDate: jQuery(
                  "#ersrv-single-reservation-checkin-datepicker"
                ).val(),
                disabledDates: disabledDates_DT,
                dayOfWeekStart: 1,
                onGenerate: function (ct) {
                  validateWeekends();
                },
              });
            },
            timepicker: true,
            onSelectTime: function (ct) {
              validateEndDate();
              endDate_DTs = ct;
              calculateTimeDifference(startDate_DTs, endDate_DTs);
            },
          });

          // Validation for date and time range
          function validateEndDate() {
            var startDate_DT = jQuery(
              "#ersrv-single-reservation-checkin-datepicker"
            ).val();
            var endDate_DT = jQuery(
              "#ersrv-single-reservation-checkout-datepicker"
            ).val();
            if (startDate_DT && endDate_DT) {
              if (startDate_DT >= endDate_DT) {
                ersrv_show_notification(
                  "bg-danger",
                  "fa-skull-crossbones",
                  "Ooops! Error..",
                  "<strong>End date and time cannot be before the start date and time.</strong>"
                );
                jQuery("#ersrv-single-reservation-checkout-datepicker").val("");
                jQuery(".get-total-hrs").hide();
                jQuery(".get-total-hrs span").text("");
              }
            }
          }

          // Validation for weekends

          function validateWeekends() {
            const dayIndices = unavailable_weekdays;
            const dayMapping = {
              0: "Sunday",
              1: "Monday",
              2: "Tuesday",
              3: "Wednesday",
              4: "Thursday",
              5: "Friday",
              6: "Saturday",
            };
            const dayNames = dayIndices.map((index) => dayMapping[index]);

            var dayNames_DT = [
              "Sunday",
              "Monday",
              "Tuesday",
              "Wednesday",
              "Thursday",
              "Friday",
              "Saturday",
            ];

            const tdElements_DT = document.querySelectorAll("td.xdsoft_date");
            tdElements_DT.forEach((td) => {
              const dayOfWeek_DT =
                td.classList[1].split("xdsoft_day_of_week")[1];
              td.classList.add(`${dayNames_DT[dayOfWeek_DT]}`);
            });

            const daysToMatch = dayNames;

            tdElements_DT.forEach((td) => {
              const dayOfWeek_DT =
                td.classList[1].split("xdsoft_day_of_week")[1];
              const dayName_DT = dayNames_DT[dayOfWeek_DT];
              if (daysToMatch.includes(dayName_DT)) {
                td.classList.add("xdsoft_disabled");
              }
            });
          }

          // Calculate the time difference in hours
          function calculateTimeDifference(start, end) {
            if (start && end) {
              var startDate = new Date(start);
              var endDate = new Date(end);
              var timeDifference = (endDate - startDate) / (1000 * 60 * 60); // Difference in hours
              var totalTimeForBoat = Math.floor(timeDifference);
              hrsForBoat = totalTimeForBoat;
              var getTotalHrs = $(".get-total-hrs");
              var hrsString;
              if (totalTimeForBoat > 1) {
                hrsString = "Hrs";
              } else {
                hrsString = "Hr";
              }
              if (totalTimeForBoat > 0) {
                getTotalHrs.html(
                  "<strong>Total Duration is : <span>" +
                    totalTimeForBoat +
                    "</span>" +
                    hrsString +
                    "</strong>"
                );
                if (getTotalHrs.html() !== "") {
                  getTotalHrs.show();
                }
              }
            }
          }
        });
      }
      datePickerWithTime();
    } else {
      $(
        "#ersrv-single-reservation-checkin-datepicker, #ersrv-single-reservation-checkout-datepicker"
      ).datepicker({
        beforeShowDay: function (date) {
          var loop_date_formatted = ersrv_get_formatted_date(date);
          var date_enabled = true;
          var date_class = "";
          var date_message = "";

          // If not the past date.
          if (date_today <= date) {
            // Add custom class to the active dates of the current month.
            var key = $.map(blocked_dates, function (val, i) {
              if (val === loop_date_formatted) {
                return i;
              }
            });

            // If the loop date is a blocked date.
            if (0 < key.length) {
              var index = key[0];
              date_message = reserved_dates[index].message;
              date_class = "ersrv-date-disabled non-clickable";
            } else {
              date_class = "ersrv-date-active";
            }

            // Check for the unavailable weekdays.
            if (0 < unavailable_weekdays_arr.length) {
              var weekday = date.getDay();
              if (-1 !== $.inArray(weekday, unavailable_weekdays_arr)) {
                date_class = "ersrv-date-disabled non-clickable";
              }
            }
          } else {
            date_class = "ersrv-date-disabled non-clickable";
          }

          // Return the datepicker day object.
          return [date_enabled, date_class, date_message];
        },
        onSelect: function (selected_date, instance) {
          if ("ersrv-single-reservation-checkin-datepicker" === instance.id) {
            // Min date for checkout should be on/after the checkin date.
            $("#ersrv-single-reservation-checkout-datepicker").datepicker(
              "option",
              "minDate",
              selected_date
            );
            setTimeout(function () {
              $("#ersrv-single-reservation-checkout-datepicker").datepicker(
                "show"
              );
            }, 16);
          }

          var checkin_date = $(
            "#ersrv-single-reservation-checkin-datepicker"
          ).val();
          var checkout_date = $(
            "#ersrv-single-reservation-checkout-datepicker"
          ).val();

          // Calculate the totals, if both the dates are available.
          if (
            1 === is_valid_string(checkin_date) &&
            1 === is_valid_string(checkout_date)
          ) {
            // Recalculate the product summary.
            ersrv_recalculate_reservation_details_item_summary();
          }
        },
        dateFormat: date_format,
        minDate: 0,
        nextText: datepicker_next_month_button_text,
        prevText: datepicker_prev_month_button_text,
      });
    }
  }

  /**
   * Accomodation adult charge.
   */
  $(document).on("keyup click", "#adult-accomodation-count", function () {
    // Recalculate the reservation item details cost summary.
    if (
      $("#ersrv-single-reservation-checkin-datepicker").val() !== "" &&
      $("#ersrv-single-reservation-checkout-datepicker").val() !== ""
    ) {
      ersrv_recalculate_reservation_details_item_summary();
    }
  });

  /**
   * Accomodation adult charge - quick view modal.
   */
  $(document).on(
    "keyup click",
    "#quick-view-adult-accomodation-count",
    function () {
      // Recalculate quick view item summary.
      ersrv_recalculate_reservation_quick_view_item_summary();
    }
  );

  /**
   * Accomodation kids charge.
   */
  $(document).on("keyup click", "#kid-accomodation-count", function () {
    // Recalculate the reservation item details cost summary.
    if (
      $("#ersrv-single-reservation-checkin-datepicker").val() !== "" &&
      $("#ersrv-single-reservation-checkout-datepicker").val() !== ""
    ) {
      ersrv_recalculate_reservation_details_item_summary();
    }
  });

  /**
   * Accomodation kids charge - quick view modal.
   */
  $(document).on(
    "keyup click",
    "#quick-view-kid-accomodation-count",
    function () {
      // Recalculate quick view item summary.
      ersrv_recalculate_reservation_quick_view_item_summary();
    }
  );

  /**
   * Amenities charge summary.
   */
  $(document).on("click", ".ersrv-new-reservation-single-amenity", function () {
    // Recalculate the reservation item details cost summary.
    ersrv_recalculate_reservation_details_item_summary();
  });

  /**
   * Amenities charge summary - quick view modal.
   */
  $(document).on(
    "click",
    ".ersrv-quick-view-reservation-single-amenity",
    function () {
      // Recalculate quick view item summary.
      ersrv_recalculate_reservation_quick_view_item_summary();
    }
  );

  /**
   * Add the reservation to google calendar.
   */
  $(document).on("click", ".add-to-gcal", function (evt) {
    evt.preventDefault();
    var this_button = $(this);
    var order_id = this_button
      .parents(".ersrv-reservation-calendars-container")
      .data("oid");

    // Return false, if the order id is invalid.
    if (-1 === is_valid_number(order_id)) {
      return false;
    }

    // Send the AJAX now.
    block_element(this_button);

    // Send the AJAX now.
    $.ajax({
      dataType: "JSON",
      url: ajaxurl,
      type: "POST",
      data: {
        action: "add_reservation_to_gcal",
        order_id: order_id,
      },
      success: function (response) {
        // Check for invalid ajax request.
        if (0 === response) {
          console.warn("easy reservations: invalid ajax request");
          return false;
        }

        if ("google-calendar-email-sent" === response.data.code) {
          unblock_element(this_button); // Unblock the element.
          ersrv_show_notification(
            "bg-success",
            "fa-check-circle",
            toast_success_heading,
            response.data.toast_message
          ); // Show the notification.
        }
      },
    });
  });

  /**
   * Add the reservation to icalendar.
   */
  $(document).on("click", ".add-to-ical", function (evt) {
    evt.preventDefault();
    var this_button = $(this);
    var order_id = this_button
      .parents(".ersrv-reservation-calendars-container")
      .data("oid");

    // Return false, if the order id is invalid.
    if (-1 === is_valid_number(order_id)) {
      return false;
    }

    // Send the AJAX now.
    block_element(this_button);

    // Send the AJAX now.
    $.ajax({
      dataType: "JSON",
      url: ajaxurl,
      type: "POST",
      data: {
        action: "add_reservation_to_ical",
        order_id: order_id,
      },
      success: function (response) {
        // Check for invalid ajax request.
        if (0 === response) {
          console.warn("easy reservations: invalid ajax request");
          return false;
        }

        if ("icalendar-email-sent" === response.data.code) {
          // Unblock the element.
          unblock_element(this_button);

          // Show the notification.
          ersrv_show_notification(
            "bg-success",
            "fa-check-circle",
            toast_success_heading,
            response.data.toast_message
          );
        }
      },
    });
  });

  /**
   * Fire the AJAX to load the reservation items on search page.
   */
  if ("yes" === is_search_page) {
    var type = parseInt(ersrv_get_query_string_parameter_value("boat_type"));
    var location = ersrv_get_query_string_parameter_value("location");
    var checkin = ersrv_get_query_string_parameter_value("checkin");
    var checkout = ersrv_get_query_string_parameter_value("checkout");
    var accomodation = ersrv_get_query_string_parameter_value("accomodation");
    var checkin_checkout_dates = [];
    var reservation_weekdays = [];

    if (1 === is_valid_string(checkin) && 1 === is_valid_string(checkout)) {
      // Get the dates array between the checkin and checkout dates.
      var checkin_checkout_dates_obj = ersrv_get_dates_between_2_dates(
        checkin,
        checkout
      );
      for (var m in checkin_checkout_dates_obj) {
        checkin_checkout_dates.push(
          ersrv_get_formatted_date(checkin_checkout_dates_obj[m])
        );
        reservation_weekdays.push(checkin_checkout_dates_obj[m].getDay());
      }
    }

    // AJAX arguments.
    var ajax_params = {
      action: "search_reservations",
      location: 1 === is_valid_string(location) ? location : "",
      type: 1 === is_valid_number(type) ? type : "",
      accomodation: 1 === is_valid_number(accomodation) ? accomodation : "",
      checkin_checkout_dates: checkin_checkout_dates,
      reservation_weekdays: reservation_weekdays,
      search_performed: "no",
    };

    // Submit the ajax search now.
    ersrv_submit_search_reservations(ajax_params, false, false);

    /**
     * Load more reservation items.
     */
    $(document).on("click", ".ersrv-loadmore-container a", function () {
      var this_button = $(this);
      var type = parseInt(
        parseInt($("select.ersrv-reservation-item-type").val())
      );
      var location = $(".ersrv-item-search-location").val();
      var checkin = $("#ersrv-search-checkin").val();
      var checkout = $("#ersrv-search-checkout").val();
      var accomodation = parseInt($(".ersrv-item-search-accomodation").val());
      var checkin_checkout_dates = [];
      var reservation_weekdays = [];
      var current_page = parseInt($("#ersrv-posts-page").val());
      var next_page = current_page + 1;

      if (1 === is_valid_string(checkin) && 1 === is_valid_string(checkout)) {
        // Get the dates array between the checkin and checkout dates.
        var checkin_checkout_dates_obj = ersrv_get_dates_between_2_dates(
          checkin,
          checkout
        );
        for (var m in checkin_checkout_dates_obj) {
          checkin_checkout_dates.push(
            ersrv_get_formatted_date(checkin_checkout_dates_obj[m])
          );
          reservation_weekdays.push(checkin_checkout_dates_obj[m].getDay());
        }
      }

      // AJAX arguments.
      var ajax_params = {
        action: "loadmore_reservation_items",
        location: 1 === is_valid_string(location) ? location : "",
        type: 1 === is_valid_number(type) ? type : "",
        accomodation: 1 === is_valid_number(accomodation) ? accomodation : "",
        checkin_checkout_dates: checkin_checkout_dates,
        reservation_weekdays: reservation_weekdays,
        page: next_page,
      };

      // Block the element now.
      block_element(this_button);

      // Submit the ajax search now.
      ersrv_submit_search_reservations(ajax_params, true, true);

      // Unblock the element now.
      unblock_element(this_button);
    });
  }

  /**
   * Mark any reservation item as favourite item.
   */
  $(document).on("click", ".ersrv-mark-reservation-favourite", function () {
    var this_button = $(this);
    var item_id = this_button
      .parents(".ersrv-reservation-item-block")
      .data("item");
    var action = "mark_fav";

    // Check, if the item is already marked as favoutite.
    if (this_button.hasClass("selected")) {
      action = "unmark_fav";
    }

    // Exit, if the item id is not a valid number.
    if (-1 === is_valid_number(item_id)) {
      console.warn(
        "easy reservations: invalid item id, cannot mark item as favourite"
      );
      return false;
    }

    // Block the element now.
    block_element(this_button);

    // Send the AJAX now.
    $.ajax({
      dataType: "JSON",
      url: ajaxurl,
      type: "POST",
      data: {
        action: "item_favourite",
        do: action,
        item_id: item_id,
      },
      success: function (response) {
        // Check for invalid ajax request.
        if (0 === response) {
          console.warn("easy reservations: invalid ajax request");
          return false;
        } else if ("item-favourite-done" === response.data.code) {
          // If items are found.
          // Unblock the element.
          unblock_element(this_button);

          // If the action was to unmark fav, remove the selected class from the button.
          if ("unmark_fav" === action) {
            this_button.removeClass("selected");
          } else if ("mark_fav" === action) {
            this_button.addClass("selected");
          }

          // Show the toast now.
          ersrv_show_toast(
            "bg-success",
            "fa-check-circle",
            toast_success_heading,
            response.data.toast_message
          );
        }
      },
    });
  });

  /**
   * Open the item quick view.
   */
  $(document).on("click", ".ersrv-quick-view-item", function () {
    var this_button = $(this);
    var this_button_text = this_button.text();
    var item_id = parseInt(
      this_button.parents(".ersrv-reservation-item-block").data("item")
    );

    // Check if the item ID is valid.
    if (-1 === is_valid_number(item_id)) {
      ersrv_show_toast(
        "bg-danger",
        "fa-skull-crossbones",
        toast_error_heading,
        invalid_reservation_item_is_error_text
      );
    }

    // Change the button text.
    this_button.html(
      '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'
    );

    // Block the element now.
    block_element(this_button);

    // Proceed with fetching the modal content.
    $.ajax({
      dataType: "JSON",
      url: ajaxurl,
      type: "POST",
      data: {
        action: "quick_view_item_data",
        item_id: item_id,
      },
      success: function (response) {
        // Check for invalid ajax request.
        if (0 === response) {
          console.warn("easy reservations: invalid ajax request");
          return false;
        } else if ("quick-view-modal-fetched" === response.data.code) {
          // If items are found.
          // Unblock the element.
          unblock_element(this_button);

          // Undo the button text.
          this_button.text(this_button_text);

          $("#ersrv-item-quick-view-modal .modal-body .quickbuymodal").html(
            response.data.html
          );
          $("#ersrv-item-quick-view-modal").fadeIn("slow");

          // Checkin and checkout datepicker.
          var reserved_dates = response.data.reserved_dates;
          var unavailable_weekdays = response.data.unavailable_weekdays;
          var unavailable_weekdays_arr = [];
          $.map(unavailable_weekdays, function (val) {
            unavailable_weekdays_arr.push(parseInt(val));
          });

          quick_view_reserved_dates = reserved_dates;
          quick_view_unavailable_weekdays = unavailable_weekdays_arr;
          var date_today = new Date();
          var blocked_dates = [];

          // Prepare the blocked out dates in a separate array.
          if (0 < reserved_dates.length) {
            for (var i in reserved_dates) {
              blocked_dates.push(reserved_dates[i].date);
            }
          }

          // Availability calendar 2 months. [BKP]
          if ("yes" === enable_time_with_date) {
            function ersrvQuickViewDatePickerWithTime() {
              jQuery(function () {
                // Get date and time format from the backend
                var dateAndTimeFormat_DT = "Y/m/d H:i";
                // Get the current date
                var currentDate_DT = new Date();
                var currentYear_DT = currentDate_DT.getFullYear();
                var currentMonth_DT = currentDate_DT.getMonth() + 1;
                var currentDay_DT = currentDate_DT.getDate();
                // Define the array of disabled dates
                var disabledDates_DT = [];
                var originalDates = blocked_dates;
                var newDates = originalDates.map(function (date) {
                  var parts = date.split("/");
                  if (parts.length === 3) {
                    return parts[2] + "/" + parts[0] + "/" + parts[1];
                  } else {
                    return date; // If the date format is invalid, return the original date
                  }
                });
                disabledDates_DT = newDates;
                // Initialize the start and end date variables
                var startDate_DTs = null;
                var endDate_DTs = null;
                // Show the check-in datepicker
                jQuery("#ersrv-quick-view-item-checkin-date").datetimepicker({
                  format: dateAndTimeFormat_DT,
                  // inline: true,
                  onShow: function (ct) {
                    this.setOptions({
                      minDate:
                        currentYear_DT +
                        "-" +
                        currentMonth_DT +
                        "-" +
                        currentDay_DT,
                      disabledDates: disabledDates_DT,
                      dayOfWeekStart: 1,
                      onGenerate: function (ct) {
                        validateWeekends();
                        startDate_DTs = ct;
                        calculateTimeDifference(startDate_DTs, endDate_DTs);
                      },
                    });
                    jQuery(
                      "#ersrv-quick-view-item-checkout-date"
                    ).datetimepicker("hide");
                  },
                  timepicker: true,
                  onSelectTime: function (ct) {
                    validateEndDate();
                  },
                  onSelectTime: function () {
                    console.log("closed");
                    jQuery(
                      "#ersrv-quick-view-item-checkout-date"
                    ).datetimepicker("show");
                  },
                });
                // Show the checkout datepicker
                jQuery("#ersrv-quick-view-item-checkout-date").datetimepicker({
                  format: dateAndTimeFormat_DT,
                  minDate:
                    currentYear_DT +
                    "-" +
                    currentMonth_DT +
                    "-" +
                    currentDay_DT,
                  onShow: function (ct) {
                    this.setOptions({
                      minDate: jQuery(
                        "#ersrv-quick-view-item-checkin-date"
                      ).val(),
                      disabledDates: disabledDates_DT,
                      dayOfWeekStart: 1,
                      onGenerate: function (ct) {
                        validateWeekends();
                      },
                    });
                  },
                  timepicker: true,
                  onSelectTime: function (ct) {
                    validateEndDate();
                    endDate_DTs = ct;
                    calculateTimeDifference(startDate_DTs, endDate_DTs);
                  },
                });

                // Validation for date and time range
                function validateEndDate() {
                  var startDate_DT = jQuery(
                    "#ersrv-quick-view-item-checkin-date"
                  ).val();
                  var endDate_DT = jQuery(
                    "#ersrv-quick-view-item-checkout-date"
                  ).val();
                  if (startDate_DT && endDate_DT) {
                    if (startDate_DT >= endDate_DT) {
                      ersrv_show_notification(
                        "bg-danger",
                        "fa-skull-crossbones",
                        "Ooops! Error..",
                        "<strong>End date and time cannot be before the start date and time.</strong>"
                      );
                      jQuery("#ersrv-quick-view-item-checkout-date").val("");
                      jQuery(".get-total-hrs_for_quick_view").hide();
                      jQuery(".get-total-hrs_for_quick_view span").text("");
                    }
                  }
                }

                // Validation for weekends
                function validateWeekends() {
                  const dayIndices = unavailable_weekdays;
                  const dayMapping = {
                    0: "Sunday",
                    1: "Monday",
                    2: "Tuesday",
                    3: "Wednesday",
                    4: "Thursday",
                    5: "Friday",
                    6: "Saturday",
                  };
                  const dayNames = dayIndices.map((index) => dayMapping[index]);

                  var dayNames_DT = [
                    "Sunday",
                    "Monday",
                    "Tuesday",
                    "Wednesday",
                    "Thursday",
                    "Friday",
                    "Saturday",
                  ];

                  const tdElements_DT =
                    document.querySelectorAll("td.xdsoft_date");
                  tdElements_DT.forEach((td) => {
                    const dayOfWeek_DT =
                      td.classList[1].split("xdsoft_day_of_week")[1];
                    td.classList.add(`${dayNames_DT[dayOfWeek_DT]}`);
                  });

                  const daysToMatch = dayNames;

                  tdElements_DT.forEach((td) => {
                    const dayOfWeek_DT =
                      td.classList[1].split("xdsoft_day_of_week")[1];
                    const dayName_DT = dayNames_DT[dayOfWeek_DT];
                    if (daysToMatch.includes(dayName_DT)) {
                      td.classList.add("xdsoft_disabled");
                    }
                  });
                }

                // Calculate the time difference in hours
                function calculateTimeDifference(start, end) {
                  if (start && end) {
                    var startDate = new Date(start);
                    var endDate = new Date(end);
                    var timeDifference =
                      (endDate - startDate) / (1000 * 60 * 60); // Difference in hours
                    var totalTimeForBoat = Math.floor(timeDifference);
                    hrsForBoat = totalTimeForBoat;
                    var getTotalHrs = $(".get-total-hrs_for_quick_view");
                    var hrsString;
                    if (totalTimeForBoat > 1) {
                      hrsString = "Hrs";
                    } else {
                      hrsString = "Hr";
                    }
                    if (totalTimeForBoat > 0) {
                      getTotalHrs.html(
                        "<strong>Total Duration is : <span>" +
                          totalTimeForBoat +
                          "</span>" +
                          hrsString +
                          "</strong>"
                      );
                      if (getTotalHrs.html() !== "") {
                        getTotalHrs.show();
                      }
                    }
                  }
                }
              });
            }
            ersrvQuickViewDatePickerWithTime();
          } else {
            $(
              "#ersrv-quick-view-item-checkin-date, #ersrv-quick-view-item-checkout-date"
            ).datepicker({
              beforeShowDay: function (date) {
                var loop_date_formatted = ersrv_get_formatted_date(date);
                var date_enabled = true;
                var date_class = "";
                var date_message = "";

                // If not the past date.
                if (date_today <= date) {
                  // Add custom class to the active dates of the current month.
                  var key = $.map(blocked_dates, function (val, i) {
                    if (val === loop_date_formatted) {
                      return i;
                    }
                  });

                  // If the loop date is a blocked date.
                  if (0 < key.length) {
                    var index = key[0];
                    date_message = reserved_dates[index].message;
                    date_class = "ersrv-date-disabled non-clickable";
                  } else {
                    date_class = "ersrv-date-active";
                  }

                  // Check for the unavailable weekdays.
                  if (0 < unavailable_weekdays_arr.length) {
                    var weekday = date.getDay();
                    if (-1 !== $.inArray(weekday, unavailable_weekdays_arr)) {
                      date_class = "ersrv-date-disabled non-clickable";
                      date_enabled = false;
                    }
                  }
                } else {
                  date_class = "ersrv-date-disabled non-clickable";
                }

                // Return the datepicker day object.
                return [date_enabled, date_class, date_message];
              },
              onSelect: function (selected_date, instance) {
                if ("ersrv-quick-view-item-checkin-date" === instance.id) {
                  // Min date for checkout should be on/after the checkin date.
                  $("#ersrv-quick-view-item-checkout-date").datepicker(
                    "option",
                    "minDate",
                    selected_date
                  );
                  setTimeout(function () {
                    $("#ersrv-quick-view-item-checkout-date").datepicker(
                      "show"
                    );
                  }, 16);
                } else if (
                  "ersrv-quick-view-item-checkout-date" === instance.id
                ) {
                  // Recalculate quick view item summary.
                  ersrv_recalculate_reservation_quick_view_item_summary();
                }
              },
              dateFormat: date_format,
              minDate: 0,
              nextText: datepicker_next_month_button_text,
              prevText: datepicker_prev_month_button_text,
            });
          }
        }
      },
    });
  });

  /**
   * Change the quick view modal main image.
   */
  $(document).on("click", ".product-preview-thumb", function () {
    var this_thumbnail = $(this);
    var thumbnail_img = this_thumbnail.find("img").attr("src");
    $(".product-preview-main img").attr("src", thumbnail_img);
  });

  /**
   * Close modal.
   */
  $(document).on("click", ".ersrv-close-modal", function () {
    $(".ersrv-modal").fadeOut("slow");
  });

  /**
   * Close the modal when clicked outside the window.
   */
  $("body").click(function (evt) {
    if ("ersrv-item-quick-view-modal" === evt.target.id) {
      $(".ersrv-modal").fadeOut("slow");
    }
  });

  /**
   * Proceed with reservation details and add the details to the cart.
   */
  $(document).on(
    "click",
    ".ersrv-proceed-to-checkout-single-reservation-item",
    function () {
      var this_button = $(this);
      var item_id = $(".single-reserve-page").data("item");
      var accomodation_limit = parseInt($("#accomodation-limit").val());
      var checkin_date = $(
        "#ersrv-single-reservation-checkin-datepicker"
      ).val();
      var checkout_date = $(
        "#ersrv-single-reservation-checkout-datepicker"
      ).val();
      var adult_count = parseInt($("#adult-accomodation-count").val());
      adult_count = -1 !== is_valid_number(adult_count) ? adult_count : 0;
      var kid_count = parseInt($("#kid-accomodation-count").val());
      kid_count = -1 !== is_valid_number(kid_count) ? kid_count : 0;
      var guests = adult_count + kid_count;
      var process_reservation = true;
      var amenities = [];
      var min_reservation_period = parseInt($("#min-reservation-period").val());
      var max_reservation_period = parseInt($("#max-reservation-period").val());

      // Vacate the error message.
      $(".ersrv-reservation-error").text("");

      // Guests count.
      if (
        -1 === is_valid_number(adult_count) &&
        -1 === is_valid_number(kid_count)
      ) {
        process_reservation = false;
        $(".ersrv-reservation-error.accomodation-error").text(
          reservation_guests_err_msg
        );
        $("html, body").animate(
          {
            scrollTop: $(".ersrv-single-reservation-item-accomodation").offset()
              .top,
          },
          "slow"
        );
      } else if (
        -1 === is_valid_number(adult_count) &&
        -1 !== is_valid_number(kid_count)
      ) {
        process_reservation = false;
        $(".ersrv-reservation-error.accomodation-error").text(
          reservation_only_kids_guests_err_msg
        );
        $("html, body").animate(
          {
            scrollTop: $(".ersrv-single-reservation-item-accomodation").offset()
              .top,
          },
          "slow"
        );
      } else if (accomodation_limit < guests) {
        process_reservation = false;
        $(".ersrv-reservation-error.accomodation-error").text(
          reservation_guests_count_exceeded_err_msg
        );
        $("html, body").animate(
          {
            scrollTop: $(".ersrv-single-reservation-item-accomodation").offset()
              .top,
          },
          "slow"
        );
      }

      // If the checkin and checkout dates are not available.
      if ("" === checkin_date && "" === checkout_date) {
        process_reservation = false;
        $(".ersrv-reservation-error.checkin-checkout-dates-error").text(
          reservation_checkin_checkout_missing_err_msg
        );
        $("html, body").animate(
          {
            scrollTop: $(
              ".ersrv-single-reservation-item-checkin-checkout"
            ).offset().top,
          },
          "slow"
        );
      } else if ("" === checkin_date) {
        process_reservation = false;
        $(".ersrv-reservation-error.checkin-checkout-dates-error").text(
          reservation_checkin_missing_err_msg
        );
        $("html, body").animate(
          {
            scrollTop: $(
              ".ersrv-single-reservation-item-checkin-checkout"
            ).offset().top,
          },
          "slow"
        );
      } else if ("" === checkout_date) {
        process_reservation = false;
        $(".ersrv-reservation-error.checkin-checkout-dates-error").text(
          reservation_checkout_missing_err_msg
        );
        $("html, body").animate(
          {
            scrollTop: $(
              ".ersrv-single-reservation-item-checkin-checkout"
            ).offset().top,
          },
          "slow"
        );
      } else {
        /**
         * If the reservation period is more than allowed.
         * Get the dates between checkin and checkout dates.
         */
        var reservation_dates = ersrv_get_dates_between_2_dates(
          checkin_date,
          checkout_date
        );
        var reservation_days = reservation_dates.length;
        if (min_reservation_period > reservation_days) {
          process_reservation = false;
          $(".ersrv-reservation-error.checkin-checkout-dates-error").text(
            reservation_lesser_reservation_days_err_msg.replace(
              "XX",
              min_reservation_period
            )
          );
        } else if (max_reservation_period < reservation_days) {
          process_reservation = false;
          $(".ersrv-reservation-error.checkin-checkout-dates-error").text(
            reservation_greater_reservation_days_err_msg.replace(
              "XX",
              max_reservation_period
            )
          );
        } else {
          // Iterate through the reservation dates to collect the readable dates.
          var readable_reservation_dates = [];
          var readable_reservation_weekdays = [];
          for (var i in reservation_dates) {
            var reservation_date_formatted = ersrv_get_formatted_date(
              reservation_dates[i]
            );
            readable_reservation_dates.push(reservation_date_formatted);
            readable_reservation_weekdays.push(reservation_dates[i].getDay());
          }

          // Check here, if the dates selected by the customer contains dates that are already reserved.
          var reserved_dates = reservation_item_details.reserved_dates;
          var unavailable_weekdays =
            reservation_item_details.unavailable_weekdays;
          var unavailable_weekdays_arr = [];
          $.map(unavailable_weekdays, function (val) {
            unavailable_weekdays_arr.push(parseInt(val));
          });
          var blocked_dates = [];

          // Prepare the blocked out dates in a separate array.
          if (0 < reserved_dates.length) {
            for (var l in reserved_dates) {
              blocked_dates.push(reserved_dates[l].date);
            }
          }

          // If there are common dates between reservation dates and blocked dates, display an error.
          var common_dates = $.grep(
            readable_reservation_dates,
            function (element) {
              return $.inArray(element, blocked_dates) !== -1;
            }
          );

          // If there are common weekdays that the reservation item is unavailable.
          var common_weekdays = $.grep(
            readable_reservation_weekdays,
            function (element) {
              return $.inArray(element, unavailable_weekdays_arr) !== -1;
            }
          );

          // If there are common dates.
          if (0 < common_dates.length || 0 < common_weekdays.length) {
            process_reservation = false;
            $(".ersrv-reservation-error.checkin-checkout-dates-error").text(
              reservation_blocked_dates_err_msg
            );
            $("html, body").animate(
              {
                scrollTop: $(
                  ".ersrv-single-reservation-item-checkin-checkout"
                ).offset().top,
              },
              "slow"
            );
          }
        }
      }

      // Collect the amenities and their charges.
      $(".ersrv-new-reservation-single-amenity").each(function () {
        var this_element = $(this);
        var is_checked = this_element.is(":checked");
        if (true === is_checked) {
          var amenity_cost = parseFloat(
            this_element.parents(".ersrv-single-amenity-block").data("cost")
          );
          var cost_type = this_element
            .parents(".ersrv-single-amenity-block")
            .data("cost_type");
          amenity_cost =
            "per_day" === cost_type
              ? amenity_cost * reservation_days
              : amenity_cost;

          // Push the amenities cost into an array.
          amenities.push({
            amenity: this_element
              .parents(".ersrv-single-amenity-block")
              .data("amenity"),
            cost: amenity_cost,
          });
        }
      });

      // Exit, if we cannot process the reservation.
      if (false === process_reservation) {
        ersrv_show_toast(
          "bg-danger",
          "fa-skull-crossbones",
          toast_error_heading,
          reservation_add_to_cart_error_message
        );
        return false;
      }

      // Block element.
      block_element(this_button);

      // Send AJAX creating a reservation.
      var data = {
        action: "add_reservation_to_cart",
        item_id: item_id,
        checkin_date: checkin_date,
        checkout_date: checkout_date,
        adult_count: adult_count,
        kid_count: kid_count,
        amenities: amenities,
        item_subtotal: $(".adults-subtotal td span.ersrv-cost").data("cost"),
        kids_subtotal: $(".kids-subtotal td span.ersrv-cost").data("cost"),
        security_subtotal: $(".security-subtotal td span.ersrv-cost").data(
          "cost"
        ),
        amenities_subtotal: $(".amenities-subtotal td span.ersrv-cost").data(
          "cost"
        ),
        item_total: $(".reservation-item-subtotal td span.ersrv-cost").data(
          "cost"
        ),
      };

      // Add reservation to the cart.
      ersrv_add_reservation_to_cart(this_button, data);
    }
  );

  /**
   * Proceed with reservation details and add the details to the cart from quick view modal.
   */
  $(document).on(
    "click",
    ".ersrv-add-quick-view-reservation-to-cart",
    function () {
      var this_button = $(this);
      var item_id = parseInt($("#quick-view-item-id").val());
      var accomodation_limit = parseInt(
        $("#quick-view-accomodation-limit").val()
      );
      var checkin_date = $("#ersrv-quick-view-item-checkin-date").val();
      var checkout_date = $("#ersrv-quick-view-item-checkout-date").val();
      var adult_count = parseInt(
        $("#quick-view-adult-accomodation-count").val()
      );
      adult_count = -1 !== is_valid_number(adult_count) ? adult_count : 0;
      var kid_count = parseInt($("#quick-view-kid-accomodation-count").val());
      kid_count = -1 !== is_valid_number(kid_count) ? kid_count : 0;
      var guests = adult_count + kid_count;
      var process_reservation = true;
      var amenities = [];
      var min_reservation_period = parseInt(
        $("#quick-view-min-reservation-period").val()
      );
      var max_reservation_period = parseInt(
        $("#quick-view-max-reservation-period").val()
      );
      var modal_container = $("#ersrv-item-quick-view-modal");

      // Vacate the error message.
      $(".ersrv-reservation-error").text("");

      // Guests count.
      if (
        -1 === is_valid_number(adult_count) &&
        -1 === is_valid_number(kid_count)
      ) {
        process_reservation = false;
        $(".ersrv-reservation-error.accomodation-error").text(
          reservation_guests_err_msg
        );
      } else if (
        -1 === is_valid_number(adult_count) &&
        -1 !== is_valid_number(kid_count)
      ) {
        process_reservation = false;
        $(".ersrv-reservation-error.accomodation-error").text(
          reservation_only_kids_guests_err_msg
        );
      } else if (accomodation_limit < guests) {
        process_reservation = false;
        $(".ersrv-reservation-error.accomodation-error").text(
          reservation_guests_count_exceeded_err_msg
        );
      }

      // If the checkin and checkout dates are not available.
      if ("" === checkin_date && "" === checkout_date) {
        process_reservation = false;
        $(".ersrv-reservation-error.checkin-checkout-dates-error").text(
          reservation_checkin_checkout_missing_err_msg
        );
      } else if ("" === checkin_date) {
        process_reservation = false;
        $(".ersrv-reservation-error.checkin-checkout-dates-error").text(
          reservation_checkin_missing_err_msg
        );
      } else if ("" === checkout_date) {
        process_reservation = false;
        $(".ersrv-reservation-error.checkin-checkout-dates-error").text(
          reservation_checkout_missing_err_msg
        );
      } else {
        /**
         * If the reservation period is more than allowed.
         * Get the dates between checkin and checkout dates.
         */
        var reservation_dates = ersrv_get_dates_between_2_dates(
          checkin_date,
          checkout_date
        );
        var reservation_days = reservation_dates.length;
        if (min_reservation_period > reservation_days) {
          process_reservation = false;
          $(".ersrv-reservation-error.checkin-checkout-dates-error").text(
            reservation_lesser_reservation_days_err_msg.replace(
              "XX",
              min_reservation_period
            )
          );
        } else if (max_reservation_period < reservation_days) {
          process_reservation = false;
          $(".ersrv-reservation-error.checkin-checkout-dates-error").text(
            reservation_greater_reservation_days_err_msg.replace(
              "XX",
              max_reservation_period
            )
          );
        } else {
          // Iterate through the reservation dates to collect the readable dates.
          var readable_reservation_dates = [];
          var readable_reservation_weekdays = [];

          for (var i in reservation_dates) {
            var reservation_date_formatted = ersrv_get_formatted_date(
              reservation_dates[i]
            );
            readable_reservation_dates.push(reservation_date_formatted);
            readable_reservation_weekdays.push(reservation_dates[i].getDay());
          }

          // Check here, if the dates selected by the customer contains dates that are already reserved.
          var blocked_dates = [];

          // Prepare the blocked out dates in a separate array.
          if (0 < quick_view_reserved_dates.length) {
            for (var l in quick_view_reserved_dates) {
              blocked_dates.push(quick_view_reserved_dates[l].date);
            }
          }

          // If there are common dates between reservation dates and blocked dates, display an error.
          var common_dates = $.grep(
            readable_reservation_dates,
            function (element) {
              return $.inArray(element, blocked_dates) !== -1;
            }
          );

          // If there are common weekdays that the reservation item is unavailable.
          var common_weekdays = $.grep(
            readable_reservation_weekdays,
            function (element) {
              return $.inArray(element, quick_view_unavailable_weekdays) !== -1;
            }
          );

          // If there are common dates.
          if (0 < common_dates.length || 0 < common_weekdays.length) {
            process_reservation = false;
            $(".ersrv-reservation-error.checkin-checkout-dates-error").text(
              reservation_blocked_dates_err_msg
            );
          }
        }
      }

      // Collect the amenities and their charges.
      $(".ersrv-quick-view-reservation-single-amenity").each(function () {
        var this_element = $(this);
        var is_checked = this_element.is(":checked");
        if (true === is_checked) {
          var amenity_cost = parseFloat(
            this_element.parents(".ersrv-single-amenity-block").data("cost")
          );
          var cost_type = this_element
            .parents(".ersrv-single-amenity-block")
            .data("cost_type");
          amenity_cost =
            "per_day" === cost_type
              ? amenity_cost * reservation_days
              : amenity_cost;

          // Push the amenities cost into an array.
          amenities.push({
            amenity: this_element
              .parents(".ersrv-single-amenity-block")
              .data("amenity"),
            cost: amenity_cost,
          });
        }
      });

      // Exit, if we cannot process the reservation.
      if (false === process_reservation) {
        ersrv_show_toast(
          "bg-danger",
          "fa-skull-crossbones",
          toast_error_heading,
          reservation_add_to_cart_error_message
        );
        return false;
      }

      // Block element.
      block_element(this_button);

      // Send AJAX creating a reservation.
      var data = {
        action: "add_reservation_to_cart",
        item_id: item_id,
        checkin_date: checkin_date,
        checkout_date: checkout_date,
        adult_count: adult_count,
        kid_count: kid_count,
        amenities: amenities,
        item_subtotal: $(".adults-subtotal td span.ersrv-cost").data("cost"),
        kids_subtotal: $(".kids-subtotal td span.ersrv-cost").data("cost"),
        security_subtotal: $(".security-subtotal td span.ersrv-cost").data(
          "cost"
        ),
        amenities_subtotal: $(".amenities-subtotal td span.ersrv-cost").data(
          "cost"
        ),
        item_total: $(".reservation-item-subtotal td span.ersrv-cost").data(
          "cost"
        ),
      };

      // Add reservation to the cart.
      ersrv_add_reservation_to_cart(this_button, data);
    }
  );

  /**
   * Open the contact owner modal.
   */
  $(document).on("click", ".ersrv-contact-owner-button", function () {
    $("#ersrv-contact-owner-modal").show();
  });

  /**
   * Submit search request from single item page.
   */
  $(document).on("click", ".ersrv-submit-search-request", function () {
    var checkin_date = $("#ersrv-search-checkin").val();
    var checkout_date = $("#ersrv-search-checkout").val();
    var location = $(".ersrv-item-search-location").val();
    var accomodation = $(".ersrv-item-search-accomodation").val();
    var boat_type = parseInt($("#boat-types").val());
    var is_error = false;
    var error_message = "";

    // If checkin date is available and checkout date not.
    if (
      1 === is_valid_string(checkin_date) &&
      -1 === is_valid_string(checkout_date)
    ) {
      is_error = true;
      error_message = checkin_provided_checkout_not;
    } else if (
      -1 === is_valid_string(checkin_date) &&
      1 === is_valid_string(checkout_date)
    ) {
      is_error = true;
      error_message = checkout_provided_checkin_not;
    }

    // Check if there is error.
    if (true === is_error) {
      ersrv_show_toast(
        "bg-danger",
        "fa-skull-crossbones",
        toast_error_heading,
        error_message
      );
      return false;
    }

    // Take an empty array.
    var query_params_array = {};
    var search_url = search_reservations_page_url;

    // Checkin date.
    if (1 === is_valid_string(checkin_date)) {
      query_params_array.checkin = checkin_date;
    }

    // Checkout date.
    if (1 === is_valid_string(checkout_date)) {
      query_params_array.checkout = checkout_date;
    }

    // Location.
    if (1 === is_valid_string(location)) {
      query_params_array.location = location;
    }

    // Accomodation.
    if (1 === is_valid_number(accomodation)) {
      query_params_array.accomodation = accomodation;
    }

    // Boat types.
    if (1 === is_valid_number(boat_type)) {
      query_params_array.boat_type = boat_type;
    }

    // Iterate through the items in the object to create query parameters.
    var index = 0;
    $.each(query_params_array, function (key, value) {
      if (0 === index) {
        search_url += "?" + key + "=" + value;
      } else {
        search_url += "&" + key + "=" + value;
      }

      index++;
    });

    // Redirect now.
    window.location.href = search_url;
  });

  /**
   * Submit contact owner request.
   */
  $(document).on("click", ".ersrv-submit-contact-owner-request", function () {
    var this_button = $(this);
    var this_button_text = this_button.text();
    var name = $("#contact-owner-customer-name").val();
    var email = $("#contact-owner-customer-email").val();
    var phone = $("#contact-owner-customer-phone").val();
    var subject = $("#contact-owner-customer-query-subject").val();
    var message = $("#contact-owner-customer-message").val();
    var submit_contact_req = true;
    var item_id = $(".single-reserve-page").data("item");

    // Vacate the errors.
    $(".ersrv-reservation-error").text("");

    // Validate the first name.
    if (-1 === is_valid_string(name)) {
      $(".ersrv-reservation-error.contact-owner-customer-name").text(
        "Name is required."
      );
      submit_contact_req = false;
    }

    // Validate email.
    if (-1 === is_valid_string(email)) {
      $(".ersrv-reservation-error.contact-owner-customer-email").text(
        "Email is required."
      );
      submit_contact_req = false;
    } else if (-1 === is_valid_email(email)) {
      $(".ersrv-reservation-error.contact-owner-customer-email").text(
        "Email is invalid."
      );
      submit_contact_req = false;
    }

    // Validate the phone.
    if ("" === phone) {
      $(".ersrv-reservation-error.contact-owner-customer-phone").text(
        "Phone is required."
      );
      submit_contact_req = false;
    }

    // Validate the subject.
    if ("" === subject) {
      $(".ersrv-reservation-error.contact-owner-customer-query-subject").text(
        "Subject is required."
      );
      submit_contact_req = false;
    }

    // Validate the message.
    if ("" === message) {
      $(".ersrv-reservation-error.contact-owner-customer-message").text(
        "Message is required."
      );
      submit_contact_req = false;
    }

    // Exit, if user registration is set to false.
    if (false === submit_contact_req) {
      ersrv_show_toast(
        "bg-danger",
        "fa-skull-crossbones",
        toast_error_heading,
        reservation_item_contact_owner_error_message
      );
      return false;
    }

    // Block the button.
    block_element(this_button);

    // Activate loader.
    this_button.html(
      '<span class="ajax-request-in-process"><i class="fa fa-refresh fa-spin"></i></span> Please wait...'
    );

    // Send the AJAX now.
    var data = {
      action: "submit_contact_owner_request",
      name: name,
      email: email,
      phone: phone,
      subject: subject,
      message: message,
      item_id: item_id,
    };

    $.ajax({
      dataType: "JSON",
      url: ajaxurl,
      type: "POST",
      data: data,
      success: function (response) {
        // In case of invalid AJAX call.
        if (0 === response) {
          console.warn("easy reservations: invalid AJAX call");
          return false;
        }

        // If user already exists.
        if ("contact-owner-request-saved" === response.data.code) {
          // Unblock the button.
          unblock_element(this_button);

          // Activate loader.
          this_button.html(this_button_text);

          // Show the success toast.
          ersrv_show_toast(
            "bg-success",
            "fa-check-circle",
            toast_success_heading,
            response.data.toast_message
          );

          // Vacate all the values in the modal.
          $("#contact-owner-customer-name").val("");
          $("#contact-owner-customer-email").val("");
          $("#contact-owner-customer-phone").val("");
          $("#contact-owner-customer-query-subject").val("");
          $("#contact-owner-customer-message").val("");

          // Close the modal.
          setTimeout(function () {
            $("#ersrv-contact-owner-modal").hide();
          }, 2000);
        }
      },
    });
  });

  /**
   * Validate the driving license file.
   */
  $(document).on(
    "change",
    'input[name="reservation-driving-license"]',
    function () {
      var file = $(this).val();
      var ext = file.split(".").pop();
      ext = "." + ext;

      // Check if this extension is among the extensions allowed.
      if (
        0 < driving_license_allowed_extensions.length &&
        -1 === $.inArray(ext, driving_license_allowed_extensions)
      ) {
        ersrv_show_notification(
          "bg-danger",
          "fa-skull-crossbones",
          toast_error_heading,
          driving_license_invalid_file_error
        );

        // Reset the file input type.
        var driving_license = $('input[name="reservation-driving-license"]');
        driving_license.wrap("<form>").closest("form").get(0).reset();
        driving_license.unwrap();

        return false;
      }

      // Upload the file as it is uploaded.
      var oFReader = new FileReader();
      var driving_license_field = document.getElementById(
        "reservation-driving-license"
      );
      oFReader.readAsDataURL(driving_license_field.files[0]);

      // Prepare the form data.
      var fd = new FormData();
      fd.append("driving_license_file", driving_license_field.files[0]);

      // AJAX action.
      fd.append("action", "upload_driving_license_checkout");

      // Block the element.
      block_element($(".ersrv-driving-license"));

      // Shoot the AJAX now.
      $.ajax({
        type: "POST",
        url: ajaxurl,
        data: fd,
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function (response) {
          // Return, if the response is not proper.
          if (0 === response) {
            console.warn("easy-reservations: invalid ajax call");
            return false;
          }

          // If the driving license is uploaded.
          if ("driving-license-uploaded" === response.data.code) {
            unblock_element($(".ersrv-driving-license")); // Unblock the element.
            $(".ersrv-uploaded-checkout-license-file").html(
              response.data.view_license_html
            );
            ersrv_show_notification(
              "bg-success",
              "fa-check-circle",
              toast_success_heading,
              response.data.toast_message
            ); // Show toast.

            // Reset the file input type.
            var driving_license = $(
              'input[name="reservation-driving-license"]'
            );
            driving_license.wrap("<form>").closest("form").get(0).reset();
            driving_license.unwrap();
          }
        },
      });
    }
  );

  /**
   * Remove the uploaded license file.
   */
  $(document).on(
    "click",
    ".ersrv-uploaded-checkout-license-file button.remove",
    function () {
      var this_button = $(this);
      var file_id = parseInt(this_button.data("file"));

      // Return, if the file ID is not available.
      if (-1 === is_valid_number(file_id)) {
        return false;
      }

      // Block the element.
      block_element(this_button);

      // Shoot the AJAX to delete the uploaded driving license file.
      $.ajax({
        dataType: "JSON",
        url: ajaxurl,
        type: "POST",
        data: {
          action: "remove_uploaded_driving_license",
          file_id: file_id,
        },
        success: function (response) {
          // Return, if the response is not proper.
          if (0 === response) {
            console.warn("easy-reservations: invalid ajax call");
            return false;
          }

          // If the reservation is added.
          if ("driving-license-removed" === response.data.code) {
            unblock_element(this_button); // Unblock the element.
            ersrv_show_notification(
              "bg-success",
              "fa-check-circle",
              toast_success_heading,
              response.data.toast_message
            ); // Show toast.
            $(".ersrv-uploaded-checkout-license-file").html(""); // Remove the html.
            block_element($(".ersrv-driving-license button.view")); // Block the upload button.
          }
        },
      });
    }
  );

  /**
   * Scroll the user to driving license, in case of error.
   */
  $(document).on("click", ".scroll-to-driving-license", function (evt) {
    evt.preventDefault();
    $("html, body").animate(
      {
        scrollTop: $("#order_comments_field").offset().top,
      },
      2000
    );
  });

  /**
   * Raise cancellation request.
   */
  $(document).on(
    "click",
    ".ersrv-reservation-cancellation-container button",
    function () {
      var this_button = $(this);
      var parent_div = this_button.parent(
        ".ersrv-reservation-cancellation-container"
      );
      var item_id = parseInt(parent_div.data("item"));
      var order_id = parseInt(parent_div.data("order"));
      var cancel_cnf = confirm(cancel_reservation_confirmation_message);

      // Exit, if the item ID or the order ID is invalid.
      if (-1 === is_valid_number(item_id) || -1 === is_valid_number(order_id)) {
        return false;
      }

      // Exit, if the customer refuses to cancel the idea of cancelling the reservation.
      if (false === cancel_cnf) {
        return false;
      }

      // Block the element.
      block_element(parent_div);

      // Shoot the AJAX to raise cancellation request.
      $.ajax({
        dataType: "JSON",
        url: ajaxurl,
        type: "POST",
        data: {
          action: "request_reservation_cancel",
          item_id: item_id,
          order_id: order_id,
        },
        success: function (response) {
          // Return, if the response is not proper.
          if (0 === response) {
            console.warn("easy-reservations: invalid ajax call");
            return false;
          }

          // If the reservation is added.
          if ("cancellation-request-saved" === response.data.code) {
            unblock_element(parent_div); // Unblock the element.
            block_element(this_button); // Block the button.
            ersrv_show_notification(
              "bg-success",
              "fa-check-circle",
              toast_success_heading,
              response.data.toast_message
            ); // Show toast.
          }
        },
      });
    }
  );

  /**
   * Close the notification.
   */
  $(document).on("click", ".ersrv-notification .close", function () {
    $(".ersrv-notification-wrapper .toast")
      .removeClass("show")
      .addClass("hide");
  });

  /**
   * Check if any value is changed, so the user should confirm availability.
   */
  $(document).on("keyup", ".ersrv-search-parameter", function (evt) {
    var key_code = evt.keyCode || evt.which;

    // If enter key is pressed.
    if (1 === is_valid_number(key_code) && 13 === key_code) {
      $(".ersrv-submit-reservation-search").click();
    }
  });

  /**
   * Submit the search.
   */
  $(document).on("click", ".ersrv-submit-reservation-search", function () {
    var location = $(".ersrv-item-search-location").val();
    var type = parseInt($("select.ersrv-reservation-item-type").val());
    var checkin = $("#ersrv-search-checkin").val();
    var checkout = $("#ersrv-search-checkout").val();
    var accomodation = parseInt($(".ersrv-item-search-accomodation").val());
    var checkin_checkout_dates = [];
    var reservation_weekdays = [];
    var is_error = false;
    var error_message = "";

    // If checkin date is available and checkout date not.
    if (1 === is_valid_string(checkin) && -1 === is_valid_string(checkout)) {
      is_error = true;
      error_message = checkin_provided_checkout_not;
    } else if (
      -1 === is_valid_string(checkin) &&
      1 === is_valid_string(checkout)
    ) {
      is_error = true;
      error_message = checkout_provided_checkin_not;
    }

    // Check if there is error.
    if (true === is_error) {
      ersrv_show_toast(
        "bg-danger",
        "fa-skull-crossbones",
        toast_error_heading,
        error_message
      );
      return false;
    }

    // Get the dates array between the checkin and checkout dates.
    var checkin_checkout_dates_obj = ersrv_get_dates_between_2_dates(
      checkin,
      checkout
    );
    for (var i in checkin_checkout_dates_obj) {
      checkin_checkout_dates.push(
        ersrv_get_formatted_date(checkin_checkout_dates_obj[i])
      );
      reservation_weekdays.push(checkin_checkout_dates_obj[i].getDay());
    }

    // Check if search is actually performed.
    var search_performed =
      1 === is_valid_string(location) ||
      1 === is_valid_number(type) ||
      1 === is_valid_string(checkin) ||
      1 === is_valid_string(checkout) ||
      1 === is_valid_number(accomodation)
        ? "yes"
        : "no";

    // Block the wrapper.
    block_element($(".ersrv-form-wrapper"));

    // AJAX arguments.
    var ajax_params = {
      action: "search_reservations",
      location: location,
      type: type,
      checkin_checkout_dates: checkin_checkout_dates,
      reservation_weekdays: reservation_weekdays,
      accomodation: accomodation,
      search_performed: search_performed,
    };

    // Submit the ajax search now.
    ersrv_submit_search_reservations(ajax_params, false, true);
  });

  /**
   * Show/hide the reservation splitted cost.
   */
  $(document).on("click", ".ersrv-split-reservation-cost", function () {
    var this_anchor = $(this);
    $(".ersrv-reservation-details-item-summary").toggleClass("show");

    // Check if the click is from modal.
    if (!this_anchor.hasClass("is-modal")) {
      // Add a body class if the summary is visible.
      $("body").removeClass("ersrv-reservation-cost-details-active");
      if ($(".ersrv-reservation-details-item-summary").hasClass("show")) {
        $("body").addClass("ersrv-reservation-cost-details-active");
      }
    }
  });

  /**
   * Prevent negative values in accomodation input boxes.
   */
  $(document).on("keyup", ".ersrv-accomodation-count", function () {
    $(this).val(Math.abs($(this).val()));
  });

  /**
   * Prevent "+", "-", and "e" on input type number field.
   */
  $(document).on("keydown", 'input[type="number"]', function (evt) {
    var restricted_chars = [69, 187, 189]; // 69 for "e", 187 for "+", and 189 for "-"

    if (-1 !== $.inArray(evt.which, restricted_chars)) {
      return false;
    }
  });

  /**
   * Add select2 to the timezone dropdown.
   */
  if ($("#billing_timezone").length) {
    $("#billing_timezone").select2();
  }

  /**
   * Close the summary box if the click was made outside it.
   */
  /*document.addEventListener( 'click', function( event ) {
		// For the reservation details page.
		var reservation_summary_container = document.getElementsByClassName( 'ersrv-reservation-details-item-summary' )[0];
		var reservation_summary_link      = document.getElementsByClassName( 'ersrv-reservation-item-subtotal' )[0];
		var wc_price_span                 = document.getElementsByClassName( 'woocommerce-Price-amount' )[0];
		var wc_price_currency_span        = document.getElementsByClassName( 'woocommerce-Price-currencySymbol' )[0];

		if (
			( undefined !== reservation_summary_link && reservation_summary_link === event.target ) ||
			( undefined !== wc_price_span && wc_price_span === event.target ) ||
			( undefined !== wc_price_currency_span && wc_price_currency_span === event.target )
		) {
			if (
				undefined !== reservation_summary_container &&
				reservation_summary_container !== event.target &&
				! reservation_summary_container.contains( event.target )
			) {
				// Check if the summary modal is open.
				if ( $( '.ersrv-reservation-details-item-summary' ).hasClass( 'show' ) ) {
					$( '.ersrv-reservation-details-item-summary' ).removeClass( 'show' );
					$( 'body' ).removeClass( 'ersrv-reservation-cost-details-active' );
				}
			}
		}

		// For the quick view modal.
		var quick_view_reservation_summary_link = document.getElementsByClassName( 'ersrv-quick-view-item-subtotal' )[0];
		if ( undefined !== quick_view_reservation_summary_link && quick_view_reservation_summary_link !== event.target ) {
			if (
				undefined !== reservation_summary_container &&
				reservation_summary_container !== event.target &&
				! reservation_summary_container.contains( event.target )
			) {
				// Check if the summary modal is open.
				if ( $( '.ersrv-reservation-details-item-summary' ).hasClass( 'show' ) ) {
					$( '.ersrv-reservation-details-item-summary' ).removeClass( 'show' );
				}
			}
		}

		// For the edit reservation item summary.
		var edit_reservation_summary_container = document.getElementsByClassName( 'ersrv-edit-reservation-item-summary' )[0];
		var edit_reservation_summary_link      = document.getElementsByClassName( 'ersrv-split-reservation-cost' )[0];
		if ( undefined !== edit_reservation_summary_link && edit_reservation_summary_link !== event.target ) {
			if (
				undefined !== edit_reservation_summary_container &&
				edit_reservation_summary_container !== event.target &&
				! edit_reservation_summary_container.contains( event.target )
			) {
				// Check if the summary modal is open.
				if ( $( '.ersrv-edit-reservation-item-summary' ).hasClass( 'show' ) ) {
					$( '.ersrv-edit-reservation-item-summary' ).removeClass( 'show' );
					$( 'body' ).removeClass( 'ersrv-reservation-cost-details-active' );
				}
			}
		}
	} );*/

  /**
   * Submit the search AJAX.
   */
  function ersrv_submit_search_reservations(args, is_load_more, enable_reset) {
    // Send the AJAX now.
    $.ajax({
      dataType: "JSON",
      url: ajaxurl,
      type: "POST",
      data: args,
      success: function (response) {
        // Check for invalid ajax request.
        if (0 === response) {
          console.warn("easy reservations: invalid ajax request");
          return false;
        }

        var code = response.data.code; // Response code.

        // Check if the request was load more.
        if (true === is_load_more) {
          // If there is a valid response.
          if ("items-found" === code) {
            // If items are found.
            $(".ersrv-search-reservations-items-container").append(
              response.data.html
            ); // Add the HTML now.
            $("#ersrv-posts-page").val(args.page); // Update the posts page number.
          } else if ("no-items-found" === code) {
            // If items are found.
            $(".ersrv-load-more-reservation-items").hide(); // Hide the load more button.
          }
        } else {
          if (
            "reservation-posts-found" === code ||
            "reservation-posts-not-found" === code
          ) {
            // Unblock the wrapper.
            unblock_element($(".ersrv-form-wrapper"));
            // Response html.
            $(".ersrv-search-reservations-items-container").html(
              response.data.html
            );
            // Items count.
            $(".ersrv-reservation-items-count").text(response.data.items_count);
          }

          // Add the "form-row" class, if there are search items.
          if ("reservation-posts-found" === code) {
            $(".ersrv-search-reservations-items-container").addClass(
              "form-row"
            );
          }

          // Remove the "form-row" class, if there are no search items.
          if ("reservation-posts-not-found" === code) {
            $(".ersrv-search-reservations-items-container").removeClass(
              "form-row"
            );
            $(".ersrv-load-more-reservation-items").hide(); // Hide the load more button.
          }

          // Scroll to the listing section.
          $("html, body").animate(
            { scrollTop: $(".search-results-wrapper").offset().top },
            "slow"
          );
        }

        // If the reset button is to be enabled.
        if (true === enable_reset) {
          unblock_element($(".ersrv-submit-reservation-reset"));
        }
      },
    });
  }

  /**
   * Add reservation to cart.
   */
  function ersrv_add_reservation_to_cart(add_to_cart_button, cart_data) {
    $.ajax({
      dataType: "JSON",
      url: ajaxurl,
      type: "POST",
      data: cart_data,
      success: function (response) {
        // Return, if the response is not proper.
        if (0 === response) {
          console.warn("easy-reservations: invalid ajax call");
          return false;
        }

        // Unblock the element.
        unblock_element(add_to_cart_button);

        // If the reservation is added.
        if ("reservation-added-to-cart" === response.data.code) {
          // Show toast.
          ersrv_show_toast(
            "bg-success",
            "fa-check-circle",
            toast_success_heading,
            response.data.toast_message
          );

          // Hide the modal.
          $("#ersrv-item-quick-view-modal").fadeOut("slow");
        } else if ("reservation-not-added-to-cart" === response.data.code) {
          // Show toast.
          ersrv_show_toast(
            "bg-danger",
            "fa-skull-crossbones",
            toast_error_heading,
            response.data.toast_message
          );
        }
      },
    });
  }

  /**
   * Return the formatted date based on the global date format.
   */
  function ersrv_get_formatted_date(date_obj) {
    var month = ("0" + (date_obj.getMonth() + 1)).slice(-2);
    var date = ("0" + date_obj.getDate()).slice(-2);
    var year = date_obj.getFullYear();

    // Replace the variables now.
    var formatted_date = date_format.replace("dd", date);
    formatted_date = formatted_date.replace("mm", month);
    formatted_date = formatted_date.replace("yy", year);

    return formatted_date;
  }

  /**
   * Check if a email is valid.
   *
   * @param {string} email
   */
  function is_valid_email(email) {
    var regex =
      /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

    return !regex.test(email) ? -1 : 1;
  }

  /**
   * Get the dates that faal between 2 dates.
   *
   * @param {*} from
   * @param {*} to
   * @returns
   */
  function ersrv_get_dates_between_2_dates(from, to) {
    var dates = [];

    // Return, if either of the date is blank.
    if ("" === from || "" === to) {
      return dates;
    }

    // Get the date time javascript object.
    from = new Date(from);
    to = new Date(to);

    // Iterate through the end date to get the array of between dates.
    while (from <= to) {
      dates.push(new Date(from));
      from.setDate(from.getDate() + 1);
    }

    return dates;
  }

  /**
   * Block element.
   *
   * @param {string} element
   */
  function block_element(element) {
    element.addClass("non-clickable");
  }

  /**
   * Unblock element.
   *
   * @param {string} element
   */
  function unblock_element(element) {
    element.removeClass("non-clickable");
  }

  /**
   * Check if a number is valid.
   *
   * @param {number} data
   */
  function is_valid_number(data) {
    if ("" === data || undefined === data || isNaN(data) || 0 === data) {
      return -1;
    }

    return 1;
  }

  /**
   * Check if a string is valid.
   *
   * @param {string} data
   */
  function is_valid_string(data) {
    if ("" === data || undefined === data || !isNaN(data) || 0 === data) {
      return -1;
    }

    return 1;
  }

  /**
   * Show the notification text.
   *
   * @param {string} bg_color Holds the toast background color.
   * @param {string} icon Holds the toast icon.
   * @param {string} heading Holds the toast heading.
   * @param {string} message Holds the toast body message.
   */
  function ersrv_show_toast(bg_color, icon, heading, message) {
    $(".ersrv-notification").removeClass("bg-success bg-warning bg-danger");
    $(".ersrv-notification").addClass(bg_color).toast("show");
    $(".ersrv-notification .ersrv-notification-icon").removeClass(
      "fa-skull-crossbones fa-check-circle fa-exclamation-circle"
    );
    $(".ersrv-notification .ersrv-notification-icon").addClass(icon);
    $(".ersrv-notification .ersrv-notification-heading").text(heading);
    $(".ersrv-notification .ersrv-notification-message").html(message);
  }

  /**
   * Return the formatted price.
   *
   * @param {*} cost
   * @returns
   */
  function ersrv_get_formatted_price(cost) {
    // Upto 2 decimal places.
    cost = cost.toFixed(2);

    // Remove the extra zeros from the price.
    if ("yes" === trim_zeros_from_price) {
      cost = cost.replace(/\.00$/, "");
    }

    // Let's first comma format the price.
    var cost_parts = cost.toString().split(".");
    cost_parts[0] = cost_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    cost = cost_parts.join(".");

    // Prepare the cost html now.
    var cost_html = '<span class="woocommerce-Price-amount amount">';
    cost_html +=
      '<span class="woocommerce-Price-currencySymbol">' +
      woo_currency +
      "</span>";
    cost_html += cost;
    cost_html += "</span>";

    return cost_html;
  }

  /**
   * Get query string parameter value.
   *
   * @param {string} string
   * @return {string} string
   */
  function ersrv_get_query_string_parameter_value(param_name) {
    var url_string = window.location.href;
    var url = new URL(url_string);
    var val = url.searchParams.get(param_name);

    return val;
  }

  /**
   * Recalculate the item summary on the reservation details page.
   */
  function ersrv_recalculate_reservation_details_item_summary() {
    var selected_dates_count = 0;
    if ("yes" === enable_time_with_date) {
      var checkin_date = $(
        "#ersrv-single-reservation-checkin-datepicker"
      ).val();
      var checkout_date = $(
        "#ersrv-single-reservation-checkout-datepicker"
      ).val();
      var selected_dates = [];
      var amenities_total = 0.0;

      // Get the checkin and checkout dates.
      var selected_dates_obj = ersrv_get_dates_between_2_dates(
        checkin_date,
        checkout_date
      );
      for (var m in selected_dates_obj) {
        selected_dates.push(ersrv_get_formatted_date(selected_dates_obj[m]));
      }

      // Get the count of the selected days.
      selected_dates_count = parseInt($(".get-total-hrs span").text());
    } else {
      var checkin_date = $(
        "#ersrv-single-reservation-checkin-datepicker"
      ).val();
      var checkout_date = $(
        "#ersrv-single-reservation-checkout-datepicker"
      ).val();
      var selected_dates = [];
      var amenities_total = 0.0;

      // Get the checkin and checkout dates.
      var selected_dates_obj = ersrv_get_dates_between_2_dates(
        checkin_date,
        checkout_date
      );
      for (var m in selected_dates_obj) {
        selected_dates.push(ersrv_get_formatted_date(selected_dates_obj[m]));
      }

      // Get the count of the selected days.
      selected_dates_count = selected_dates.length;
    }

    // Accomodation.
    var adult_count = parseInt($("#adult-accomodation-count").val());
    adult_count = -1 === is_valid_number(adult_count) ? 0 : adult_count;
    var kids_count = parseInt($("#kid-accomodation-count").val());
    kids_count = -1 === is_valid_number(kids_count) ? 0 : kids_count;

    // Accomodation charges.
    var adult_charge = parseFloat($("#adult-charge").val());
    adult_charge = selected_dates_count * adult_count * adult_charge;
    var formatted_adult_charge = ersrv_get_formatted_price(adult_charge);
    var kids_charge = parseFloat($("#kid-charge").val());
    kids_charge = selected_dates_count * kids_count * kids_charge;
    var formatted_kids_charge = ersrv_get_formatted_price(kids_charge);

    // Amenities charges.
    $(".ersrv-new-reservation-single-amenity").each(function () {
      var this_element = $(this);
      var is_checked = this_element.is(":checked");
      if (true === is_checked) {
        var amenity_cost = parseFloat(
          this_element.parents(".ersrv-single-amenity-block").data("cost")
        );
        var cost_type = this_element
          .parents(".ersrv-single-amenity-block")
          .data("cost_type");
        amenity_cost =
          "per_day" === cost_type
            ? amenity_cost * selected_dates_count
            : amenity_cost;
        amenities_total += parseFloat(amenity_cost);
      }
    });

    // Formatted amenities cost.
    var formatted_amenities_total = ersrv_get_formatted_price(amenities_total);

    // Security charge.
    var security_total = parseFloat($("#security-amount").val());
    var formatted_security_total = ersrv_get_formatted_price(security_total);

    // Calculate the total cost now.
    var total_cost =
      adult_charge + kids_charge + amenities_total + security_total;
    var formatted_total_cost = ersrv_get_formatted_price(total_cost);

    // Exit, if the charges are all not present.
    if (0 === adult_charge && 0 === kids_charge && 0 === amenities_total) {
      return false;
    }

    // Put in all the totals now.
    $(".adults-subtotal td span.ersrv-cost")
      .html(formatted_adult_charge)
      .data("cost", adult_charge);
    $(".kids-subtotal td span.ersrv-cost")
      .html(formatted_kids_charge)
      .data("cost", kids_charge);
    $(".amenities-subtotal td span.ersrv-cost")
      .html(formatted_amenities_total)
      .data("cost", amenities_total);
    $(".security-subtotal td span.ersrv-cost")
      .html(formatted_security_total)
      .data("cost", security_total);
    $(".reservation-item-subtotal td span.ersrv-cost")
      .html(formatted_total_cost)
      .data("cost", total_cost);
    $(".ersrv-reservation-item-subtotal.ersrv-cost").html(formatted_total_cost);
  }

  /**
   * Recalculate the item summary on the reservation quick view page.
   */
  function ersrv_recalculate_reservation_quick_view_item_summary() {
    var selected_dates_count = 0;
    if ("yes" === enable_time_with_date) {
      var checkin_date = $("#ersrv-quick-view-item-checkin-date").val();
      var checkout_date = $("#ersrv-quick-view-item-checkout-date").val();
      var selected_dates = [];
      var amenities_total = 0.0;

      // Get the checkin and checkout dates.
      var selected_dates_obj = ersrv_get_dates_between_2_dates(
        checkin_date,
        checkout_date
      );
      for (var m in selected_dates_obj) {
        selected_dates.push(ersrv_get_formatted_date(selected_dates_obj[m]));
      }

      // Get the count of the selected days.
      selected_dates_count = parseInt(
        $(".get-total-hrs_for_quick_view span").text()
      );
    } else {
      var checkin_date = $("#ersrv-quick-view-item-checkin-date").val();
      var checkout_date = $("#ersrv-quick-view-item-checkout-date").val();
      var selected_dates = [];
      var amenities_total = 0.0;

      // Get the checkin and checkout dates.
      var selected_dates_obj = ersrv_get_dates_between_2_dates(
        checkin_date,
        checkout_date
      );
      for (var m in selected_dates_obj) {
        selected_dates.push(ersrv_get_formatted_date(selected_dates_obj[m]));
      }

      // Get the count of the selected days.
      var selected_dates_count = selected_dates.length;
    }

    // Accomodation.
    var adult_count = parseInt($("#quick-view-adult-accomodation-count").val());
    adult_count = -1 === is_valid_number(adult_count) ? 0 : adult_count;
    var kids_count = parseInt($("#quick-view-kid-accomodation-count").val());
    kids_count = -1 === is_valid_number(kids_count) ? 0 : kids_count;

    // Accomodation charges.
    var adult_charge = parseFloat($("#quick-view-adult-charge").val());
    adult_charge = selected_dates_count * adult_count * adult_charge;
    var formatted_adult_charge = ersrv_get_formatted_price(adult_charge);
    var kids_charge = parseFloat($("#quick-view-kid-charge").val());
    kids_charge = selected_dates_count * kids_count * kids_charge;
    var formatted_kids_charge = ersrv_get_formatted_price(kids_charge);

    // Amenities charges.
    $(".ersrv-quick-view-reservation-single-amenity").each(function () {
      var this_element = $(this);
      var is_checked = this_element.is(":checked");
      if (true === is_checked) {
        var amenity_cost = parseFloat(
          this_element.parents(".ersrv-single-amenity-block").data("cost")
        );
        var cost_type = this_element
          .parents(".ersrv-single-amenity-block")
          .data("cost_type");
        amenity_cost =
          "per_day" === cost_type
            ? amenity_cost * selected_dates_count
            : amenity_cost;
        amenities_total += parseFloat(amenity_cost);
      }
    });

    // Formatted amenities cost.
    var formatted_amenities_total = ersrv_get_formatted_price(amenities_total);

    // Security charge.
    var security_total = parseFloat($("#quick-view-security-amount").val());
    var formatted_security_total = ersrv_get_formatted_price(security_total);

    // Calculate the total cost now.
    var total_cost =
      adult_charge + kids_charge + amenities_total + security_total;
    var formatted_total_cost = ersrv_get_formatted_price(total_cost);

    // Exit, if the charges are all not present.
    if (0 === adult_charge && 0 === kids_charge && 0 === amenities_total) {
      return false;
    }

    // Put in all the totals now.
    $(".adults-subtotal td span.ersrv-cost")
      .html(formatted_adult_charge)
      .data("cost", adult_charge);
    $(".kids-subtotal td span.ersrv-cost")
      .html(formatted_kids_charge)
      .data("cost", kids_charge);
    $(".amenities-subtotal td span.ersrv-cost")
      .html(formatted_amenities_total)
      .data("cost", amenities_total);
    $(".security-subtotal td span.ersrv-cost")
      .html(formatted_security_total)
      .data("cost", security_total);
    $(".reservation-item-subtotal td span.ersrv-cost")
      .html(formatted_total_cost)
      .data("cost", total_cost);
    $(".ersrv-quick-view-item-subtotal.ersrv-cost").html(formatted_total_cost);
  }

  /**
   * Show the notification text.
   *
   * @param {string} bg_color Holds the toast background color.
   * @param {string} icon Holds the toast icon.
   * @param {string} heading Holds the toast heading.
   * @param {string} message Holds the toast body message.
   */
  function ersrv_show_notification(bg_color, icon, heading, message) {
    $(".ersrv-notification-wrapper .toast").removeClass(
      "bg-success bg-warning bg-danger"
    );
    $(".ersrv-notification-wrapper .toast").addClass(bg_color);
    $(
      ".ersrv-notification-wrapper .toast .ersrv-notification-icon"
    ).removeClass("fa-skull-crossbones fa-check-circle fa-exclamation-circle");
    $(".ersrv-notification-wrapper .toast .ersrv-notification-icon").addClass(
      icon
    );
    $(".ersrv-notification-wrapper .toast .ersrv-notification-heading").text(
      heading
    );
    $(".ersrv-notification-wrapper .toast .ersrv-notification-message").html(
      message
    );
    $(".ersrv-notification-wrapper .toast")
      .removeClass("hide")
      .addClass("show");

    setTimeout(function () {
      $(".ersrv-notification-wrapper .toast")
        .removeClass("show")
        .addClass("hide");
    }, 5000);
  }

  // Show toast.
  // ersrv_show_toast( 'bg-success', 'fa-check-circle', toast_success_heading, 'Success text.' );
  // ersrv_show_toast( 'bg-warning', 'fa-exclamation-circle', toast_notice_heading, 'Notice text.' );
  // ersrv_show_toast( 'bg-danger', 'fa-skull-crossbones', toast_error_heading, 'Error text.' );
});
