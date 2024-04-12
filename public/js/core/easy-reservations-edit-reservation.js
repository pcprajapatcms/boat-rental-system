jQuery(document).ready(function ($) {
  "use strict";

  // Localized variables.
  var ajaxurl = ERSRV_Edit_Reservation_Script_Vars.ajaxurl;
  var woo_currency = ERSRV_Edit_Reservation_Script_Vars.woo_currency;
  var date_format = ERSRV_Edit_Reservation_Script_Vars.date_format;
  var toast_success_heading =
    ERSRV_Edit_Reservation_Script_Vars.toast_success_heading;
  var toast_error_heading =
    ERSRV_Edit_Reservation_Script_Vars.toast_error_heading;
  var toast_notice_heading =
    ERSRV_Edit_Reservation_Script_Vars.toast_notice_heading;
  var reservation_guests_err_msg =
    ERSRV_Edit_Reservation_Script_Vars.reservation_guests_err_msg;
  var reservation_only_kids_guests_err_msg =
    ERSRV_Edit_Reservation_Script_Vars.reservation_only_kids_guests_err_msg;
  var reservation_guests_count_exceeded_err_msg =
    ERSRV_Edit_Reservation_Script_Vars.reservation_guests_count_exceeded_err_msg;
  var reservation_checkin_checkout_missing_err_msg =
    ERSRV_Edit_Reservation_Script_Vars.reservation_checkin_checkout_missing_err_msg;
  var reservation_checkin_missing_err_msg =
    ERSRV_Edit_Reservation_Script_Vars.reservation_checkin_missing_err_msg;
  var reservation_checkout_missing_err_msg =
    ERSRV_Edit_Reservation_Script_Vars.reservation_checkout_missing_err_msg;
  var reservation_lesser_reservation_days_err_msg =
    ERSRV_Edit_Reservation_Script_Vars.reservation_lesser_reservation_days_err_msg;
  var reservation_greater_reservation_days_err_msg =
    ERSRV_Edit_Reservation_Script_Vars.reservation_greater_reservation_days_err_msg;
  var reservation_item_changes_invalidated =
    ERSRV_Edit_Reservation_Script_Vars.reservation_item_changes_invalidated;
  var cannot_update_reservation_no_change_done =
    ERSRV_Edit_Reservation_Script_Vars.cannot_update_reservation_no_change_done;
  var customer_payable_cost_difference_message =
    ERSRV_Edit_Reservation_Script_Vars.customer_payable_cost_difference_message;
  var admin_payable_cost_difference_message =
    ERSRV_Edit_Reservation_Script_Vars.admin_payable_cost_difference_message;
  var trim_zeros_from_price =
    ERSRV_Edit_Reservation_Script_Vars.trim_zeros_from_price;
  var enable_time_with_date =
    ERSRV_Edit_Reservation_Script_Vars.enable_time_with_date;
  var reservation_blocked_dates_err_msg_per_item =
    ERSRV_Edit_Reservation_Script_Vars.reservation_blocked_dates_err_msg_per_item;
  var update_reservation_confirmation_message =
    ERSRV_Edit_Reservation_Script_Vars.update_reservation_confirmation_message;
  var datepicker_next_month_button_text =
    ERSRV_Edit_Reservation_Script_Vars.datepicker_next_month_button_text;
  var datepicker_prev_month_button_text =
    ERSRV_Edit_Reservation_Script_Vars.datepicker_prev_month_button_text;
  // If sidebar is to be removed on reservation single page.
  $("#secondary").remove();
  $("#content-bottom-widgets").remove();
  $("#primary").css("width", "100%");

  // Check if back button is clicked in the browser, reload.
  var perf_entries = performance.getEntriesByType("navigation");
  if (0 < perf_entries.length && "back_forward" === perf_entries[0].type) {
    location.reload();
  }

  /**
   * Click on the checkin and checkout date to fetch the dates available while editing the reservation.
   */
  $(document).on(
    "click",
    ".ersrv-edit-reservation-item-checkin-date, .ersrv-edit-reservation-item-checkout-date",
    function () {
      var this_input = $(this);
      var this_input_id = this_input.attr("id");
      var item_id = this_input
        .parents(".ersrv-edit-reservation-item-card")
        .data("itemid");
      var product_id = this_input
        .parents(".ersrv-edit-reservation-item-card")
        .data("productid");
      var checkin_date = $(
        "#ersrv-edit-reservation-item-checkin-date-" + item_id
      ).val();
      var checkout_date = $(
        "#ersrv-edit-reservation-item-checkout-date-" + item_id
      ).val();

      // Check if the datepicker is already initiated.
      var is_datepicker_initiated = parseInt(
        $("#datepicker-initiated-" + item_id).val()
      );

      // Exit the initiation, if the datepicker has been initiated once.
      if (
        1 === is_valid_number(is_datepicker_initiated) &&
        1 === is_datepicker_initiated
      ) {
        return false;
      }

      // Block the element.
      block_element(this_input.parents(".input-daterange"));

      // Process the AJAX.
      $.ajax({
        dataType: "JSON",
        url: ajaxurl,
        type: "POST",
        data: {
          action: "edit_reservation_initiate_datepicker",
          product_id: product_id,
          checkin_date: checkin_date,
          checkout_date: checkout_date,
        },
        success: function (response) {
          // Return, if the response is not proper.
          if (0 === response) {
            console.warn("easy-reservations: invalid ajax call");
            return false;
          }

          // If the reservation is added.
          if ("datepicker-initiated" === response.data.code) {
            // Unblock the button.
            unblock_element(this_input.parents(".input-daterange"));

            // Reserved dates in response.
            var reserved_dates = response.data.reserved_dates;
            var order_reserved_dates = response.data.order_reserved_dates;
            var unavailable_weekdays = response.data.unavailable_weekdays;
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

            // Store the blocked dates in hidden field.
            var blocked_dates_json = JSON.stringify(blocked_dates);
            var unavailable_weekdays_json = JSON.stringify(
              unavailable_weekdays_arr
            );
            $("#blocked-dates-" + item_id).val(blocked_dates_json);
            $("#unavailable-weekdays-" + item_id).val(
              unavailable_weekdays_json
            );

            // Initiate the datepicker now.
            $(
              "#ersrv-edit-reservation-item-checkin-date-" +
                item_id +
                ", #ersrv-edit-reservation-item-checkout-date-" +
                item_id
            ).datepicker({
              beforeShowDay: function (date) {
                var loop_date_formatted = ersrv_get_formatted_date(date);
                var date_enabled = true;
                var date_class = "";
                var date_message = "";

                // If not the past date.
                if (date_today <= date) {
                  // Add custom class to the active dates of the current month.
                  var reserved_key = $.map(blocked_dates, function (val, i) {
                    if (val === loop_date_formatted) {
                      return i;
                    }
                  });

                  // Add custom class to order reserved date.
                  var order_reserved_key = $.map(
                    order_reserved_dates,
                    function (val, i) {
                      if (val === loop_date_formatted) {
                        return i;
                      }
                    }
                  );

                  // If the loop date is a blocked date.
                  if (0 < reserved_key.length) {
                    var index = reserved_key[0];
                    date_message = reserved_dates[index].message;
                    date_class = "ersrv-date-disabled";
                  } else if (0 < order_reserved_key.length) {
                    date_class = "ersrv-order-reserved-date";
                  } else {
                    date_class = "ersrv-date-active";
                  }

                  // Check for the unavailable weekdays.
                  if (0 < unavailable_weekdays_arr.length) {
                    var weekday = date.getDay();
                    if (-1 !== $.inArray(weekday, unavailable_weekdays_arr)) {
                      date_class = "ersrv-date-disabled";
                    }
                  }
                } else {
                  date_class = "ersrv-date-disabled";
                }

                // Return the datepicker day object.
                return [date_enabled, date_class, date_message];
              },
              onSelect: function (selected_date, instance) {
                if (
                  "ersrv-edit-reservation-item-checkin-date-" + item_id ===
                  instance.id
                ) {
                  // Min date for checkout should be on/after the checkin date.
                  $(
                    "#ersrv-edit-reservation-item-checkout-date-" + item_id
                  ).datepicker("option", "minDate", selected_date);
                  setTimeout(function () {
                    $(
                      "#ersrv-edit-reservation-item-checkout-date-" + item_id
                    ).datepicker("show");
                  }, 16);
                }

                var checkin_date = $(
                  "#ersrv-edit-reservation-item-checkin-date-" + item_id
                ).val();
                var checkout_date = $(
                  "#ersrv-edit-reservation-item-checkin-date-" + item_id
                ).val();

                // Calculate the totals, if both the dates are available.
                if (
                  1 === is_valid_string(checkin_date) &&
                  1 === is_valid_string(checkout_date)
                ) {
                  // Recalculate the product summary.
                  ersrv_recalculate_edit_reservation_item_summary(item_id);
                }
              },
              dateFormat: date_format,
              minDate: 0,
              nextText: datepicker_next_month_button_text,
              prevText: datepicker_prev_month_button_text,
            });

            // Show the datepicker.
            $("#" + this_input_id).datepicker("show");

            // Set the hidden value to be 1.
            $("#datepicker-initiated-" + item_id).val("1");
          }
        },
      });
    }
  );

  /**
   * Update reservation.
   */
  $(document).on(
    "click",
    ".ersrv-update-reservation button.update",
    function () {
      var this_button = $(this);
      var broken_loop = false;
      var error_message = "";

      // Vacate the error messages.
      $(".ersrv-reservation-error").html("");

      // Check if the changes are validated.
      $(".ersrv-edit-reservation-item-card").each(function () {
        var this_card = $(this);
        var item_id = parseInt(this_card.data("itemid"));
        var item_name = this_card.data("itemname");
        var is_update_error = ersrv_validate_reservations_before_update(
          item_id,
          item_name
        );

        // If it's the error.
        if (-1 === is_update_error.is_valid) {
          broken_loop = true;
          error_message = is_update_error.message;
          return false;
        }
      });

      // See if the loop is broken due to any error.
      if (true === broken_loop) {
        ersrv_show_notification(
          "bg-danger",
          "fa-skull-crossbones",
          toast_error_heading,
          error_message
        );
        return false;
      }

      // Check the cost difference.
      var order_id = parseInt($(".ersrv-order-id").val()); // Order ID.
      var cost_difference_data = ersrv_calculate_reservation_cost_difference(); // Cost difference.
      var cost_difference =
        1 === is_valid_number(cost_difference_data.amount)
          ? cost_difference_data.amount
          : 0;

      /**
       * Verify any change in the values now.
       * This is because if there are changes in the reservation with no cost difference, the reservation should be updated.
       *
       * Iterate through the cards to check the changed values.
       */
      var changed_reservation_value = false;
      $(".ersrv-edit-reservation-item-card").each(function () {
        var this_card = $(this);
        var item_id = parseInt(this_card.data("itemid"));
        var is_item_updated = ersrv_is_reservation_value_changed(item_id);

        // If it's the error.
        if (1 === is_item_updated) {
          changed_reservation_value = true;
          return false;
        }
      });

      // If there is no cost difference, the order cannot be updated.
      if (0 === cost_difference && false === changed_reservation_value) {
        ersrv_show_notification(
          "bg-warning",
          "fa-exclamation-circle",
          toast_notice_heading,
          cannot_update_reservation_no_change_done
        );
        return false;
      }

      // Take customer's consent for updating the order.
      var update_reservation_consent = confirm(
        update_reservation_confirmation_message
      );

      // Break the flow, if the user denies to update the reservation.
      if (false === update_reservation_consent) {
        return false;
      }

      /**
       * Everything is OK.
       * Proceed with updating the reservation.
       */
      var order_total = cost_difference_data.item_order_total; // Order total.
      var cost_difference_key = cost_difference_data.key; // Cost difference key.
      var items_data = []; // Items data.

      /**
       * If you're here, it means that it is okay to update the reservation.
       * Iterate through the items to collect the new data.
       */
      $(".ersrv-edit-reservation-item-card").each(function () {
        var this_card = $(this);
        var item_id = parseInt(this_card.data("itemid"));
        var item_total = parseFloat(
          $(
            "#edit-reservation-item-total-cost-" +
              item_id +
              " td span.ersrv-cost"
          ).data("cost")
        );
        var amenities = [];
        var checkin = $(
          "#ersrv-edit-reservation-item-checkin-date-" + item_id
        ).val();
        var checkout = $(
          "#ersrv-edit-reservation-item-checkout-date-" + item_id
        ).val();
        var reservation_dates = ersrv_get_dates_between_2_dates(
          checkin,
          checkout
        );
        var reservation_days = reservation_dates.length;

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

        // Collect all the data in an array.
        items_data.push({
          item_id: item_id,
          adult_subtotal: parseFloat(
            $("#item-price-summary-" + item_id + " td span.ersrv-cost").data(
              "cost"
            )
          ),
          kids_subtotal: parseFloat(
            $("#kids-charge-summary-" + item_id + " td span.ersrv-cost").data(
              "cost"
            )
          ),
          amenities_subtotal: parseFloat(
            $("#amenities-summary-" + item_id + " td span.ersrv-cost").data(
              "cost"
            )
          ),
          amenities: amenities,
          security_subtotal: parseFloat(
            $("#security-summary-" + item_id + " td span.ersrv-cost").data(
              "cost"
            )
          ),
          item_total: item_total,
          checkin: checkin,
          checkout: checkout,
          adult_count: parseInt(
            $("#ersrv-edit-reservation-item-adult-count-" + item_id).val()
          ),
          kids_count: parseInt(
            $("#ersrv-edit-reservation-item-kid-count-" + item_id).val()
          ),
        });
      });

      // Block the button.
      // block_element( this_button );

      // Process the AJAX.
      $.ajax({
        dataType: "JSON",
        url: ajaxurl,
        type: "POST",
        data: {
          action: "update_reservation",
          order_id: order_id,
          cost_difference: cost_difference,
          cost_difference_key: cost_difference_key,
          items_data: items_data,
          order_total: order_total,
        },
        success: function (response) {
          // Return, if the response is not proper.
          if (0 === response) {
            console.warn("easy-reservations: invalid ajax call");
            return false;
          }

          // If the reservation is added.
          if ("reservation-updated" === response.data.code) {
            // Unblock the button.
            unblock_element(this_button);

            // Show notification.
            ersrv_show_notification(
              "bg-success",
              "fa-check-circle",
              toast_success_heading,
              response.data.toast_message
            );

            // Redirecting to the order details now.
            setTimeout(function () {
              window.location.href = response.data.view_order_link;
            }, 2000);
          }
        },
      });
    }
  );

  /**
   * Edit reservation adult accomodation charge.
   */
  $(document).on(
    "keyup click",
    ".ersrv-edit-reservation-item-adult-count",
    function () {
      var this_input = $(this);
      var item_id = this_input
        .parents(".ersrv-edit-reservation-item-card")
        .data("itemid");
      ersrv_recalculate_edit_reservation_item_summary(item_id); // Recalculate the product summary.
    }
  );

  /**
   * Edit reservation kid accomodation charge.
   */
  $(document).on(
    "keyup click",
    ".ersrv-edit-reservation-item-kid-count",
    function () {
      var this_input = $(this);
      var item_id = this_input
        .parents(".ersrv-edit-reservation-item-card")
        .data("itemid");
      ersrv_recalculate_edit_reservation_item_summary(item_id); // Recalculate the product summary.
    }
  );

  /**
   * Amenities charge summary.
   */
  $(document).on("click", ".ersrv-single-amenity-block", function () {
    var this_element = $(this);
    var item_id = this_element
      .parents(".ersrv-edit-reservation-item-card")
      .data("itemid");
    ersrv_recalculate_edit_reservation_item_summary(item_id); // Recalculate the product summary.
  });

  /**
   * Show/hide the reservation splitted cost.
   */
  $(document).on("click", ".ersrv-split-reservation-cost", function () {
    var this_anchor = $(this);
    var item_id = this_anchor
      .parents(".ersrv-edit-reservation-item-card")
      .data("itemid");
    $("#ersrv-edit-reservation-item-summary-" + item_id).toggleClass("show");

    // Add a body class if the summary is visible.
    $("body").removeClass("ersrv-reservation-cost-details-active");
    if ($("#ersrv-edit-reservation-item-summary-" + item_id).hasClass("show")) {
      $("body").addClass("ersrv-reservation-cost-details-active");
    }
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
   * Get the item subtotal.
   *
   * @returns number
   */
  function ersrv_get_edit_reservation_item_subtotal(item_id) {
    var item_subtotal = $(
      "tr#item-price-summary-" + item_id + " td span span"
    ).text();
    item_subtotal = parseFloat(item_subtotal.replace(/[^\d.]/g, ""));
    item_subtotal = -1 === is_valid_number(item_subtotal) ? 0 : item_subtotal;

    return item_subtotal;
  }

  /**
   * Get the kids charge subtotal.
   *
   * @returns number
   */
  function ersrv_get_edit_reservation_kids_subtotal(item_id) {
    var kids_subtotal = $(
      "tr#kids-charge-summary-" + item_id + " td span span"
    ).text();
    kids_subtotal = parseFloat(kids_subtotal.replace(/[^\d.]/g, ""));
    kids_subtotal = -1 === is_valid_number(kids_subtotal) ? 0 : kids_subtotal;

    return kids_subtotal;
  }

  /**
   * Get the security amount subtotal.
   *
   * @returns number
   */
  function ersrv_get_edit_security_subtotal(item_id) {
    var security_subtotal = $(
      "tr#security-summary-" + item_id + " td span span"
    ).text();
    security_subtotal = parseFloat(security_subtotal.replace(/[^\d.]/g, ""));
    security_subtotal =
      -1 === is_valid_number(security_subtotal) ? 0 : security_subtotal;

    return security_subtotal;
  }

  /**
   * Get the amenities charge subtotal.
   *
   * @returns number
   */
  function ersrv_get_edit_amenities_subtotal(item_id) {
    var amenities_subtotal = $(
      "tr#amenities-summary-" + item_id + " td span span"
    ).text();
    amenities_subtotal = parseFloat(amenities_subtotal.replace(/[^\d.]/g, ""));
    amenities_subtotal =
      -1 === is_valid_number(amenities_subtotal) ? 0 : amenities_subtotal;

    return amenities_subtotal;
  }

  /**
   * Calculate the reservation total cost.
   */
  function ersrv_calculate_edit_reservation_item_total_cost(item_id) {
    var item_subtotal = parseFloat(
      ersrv_get_edit_reservation_item_subtotal(item_id)
    );
    var kids_subtotal = parseFloat(
      ersrv_get_edit_reservation_kids_subtotal(item_id)
    );
    var security_subtotal = parseFloat(
      ersrv_get_edit_security_subtotal(item_id)
    );
    var amenities_subtotal = parseFloat(
      ersrv_get_edit_amenities_subtotal(item_id)
    );

    // Addup to the total cost.
    var total_cost =
      item_subtotal + kids_subtotal + security_subtotal + amenities_subtotal;
    var total_cost_formatted = ersrv_get_formatted_price(total_cost);

    // Paste the final total.
    $(
      "tr#edit-reservation-item-total-cost-" + item_id + " td span.ersrv-cost"
    ).html(total_cost_formatted);
    $("span#ersrv-edit-reservation-item-subtotal-" + item_id).html(
      total_cost_formatted
    );

    return total_cost;
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

  /**
   * Is the element blocked.
   *
   * @param {string} element
   */
  function is_element_blocked(element) {
    return element.hasClass("non-clickable") ? true : false;
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
   * Return the formatted date based on the global date format.
   *
   * @param {*} date_obj
   * @returns
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
   * Recalculate the item summary on the reservation quick view page.
   */
  function ersrv_recalculate_edit_reservation_item_summary(item_id) {
    var checkin_date = $(
      "#ersrv-edit-reservation-item-checkin-date-" + item_id
    ).val();
    var checkout_date = $(
      "#ersrv-edit-reservation-item-checkout-date-" + item_id
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
    var selected_dates_count = selected_dates.length;

    // Accomodation.
    var adult_count = parseInt(
      $("#ersrv-edit-reservation-item-adult-count-" + item_id).val()
    );
    adult_count = -1 === is_valid_number(adult_count) ? 0 : adult_count;
    var kids_count = parseInt(
      $("#ersrv-edit-reservation-item-kid-count-" + item_id).val()
    );
    kids_count = -1 === is_valid_number(kids_count) ? 0 : kids_count;

    // Accomodation charges.
    var adult_charge = parseFloat($("#adult-charge-" + item_id).val());
    adult_charge = selected_dates_count * adult_count * adult_charge;
    var formatted_adult_charge = ersrv_get_formatted_price(adult_charge);
    var kids_charge = parseFloat($("#kid-charge-" + item_id).val());
    kids_charge = selected_dates_count * kids_count * kids_charge;
    var formatted_kids_charge = ersrv_get_formatted_price(kids_charge);

    // Amenities charges.
    $("#ersrv-amenities-wrapper-" + item_id)
      .find(".ersrv-single-amenity-block")
      .each(function () {
        var this_div = $(this);
        var this_checkbox = this_div.find('input[type="checkbox"]');
        var is_checked = this_checkbox.is(":checked");
        if (true === is_checked) {
          var amenity_cost = parseFloat(this_div.data("cost"));
          var cost_type = this_div.data("cost_type");
          amenity_cost =
            "per_day" === cost_type
              ? amenity_cost * selected_dates_count
              : amenity_cost;
          amenity_cost = parseFloat(amenity_cost.toFixed(2));
          amenities_total += amenity_cost;
        }
      });

    // Formatted amenities cost.
    var formatted_amenities_total = ersrv_get_formatted_price(amenities_total);

    // Security charge.
    var security_total = parseFloat($("#security-amount-" + item_id).val());
    var formatted_security_total = ersrv_get_formatted_price(security_total);

    // Calculate the total cost now.
    var total_cost =
      adult_charge + kids_charge + amenities_total + security_total;
    var formatted_total_cost = ersrv_get_formatted_price(total_cost);

    // Put in all the totals now.
    $("#item-price-summary-" + item_id + " td span.ersrv-cost")
      .html(formatted_adult_charge)
      .data("cost", adult_charge);
    $("#kids-charge-summary-" + item_id + " td span.ersrv-cost")
      .html(formatted_kids_charge)
      .data("cost", kids_charge);
    $("#amenities-summary-" + item_id + " td span.ersrv-cost")
      .html(formatted_amenities_total)
      .data("cost", amenities_total);
    $("#security-summary-" + item_id + " td span.ersrv-cost")
      .html(formatted_security_total)
      .data("cost", security_total);
    $("#edit-reservation-item-total-cost-" + item_id + " td span.ersrv-cost")
      .html(formatted_total_cost)
      .data("cost", total_cost);
    $("#ersrv-edit-reservation-item-subtotal-" + item_id).html(
      formatted_total_cost
    );

    // Calculate the cost difference.
    ersrv_calculate_reservation_cost_difference();
  }

  /**
   * Calculate the cost difference.
   */
  function ersrv_calculate_reservation_cost_difference() {
    var item_new_total = 0.0;
    var cost_difference_data = {};
    // Iterate through the items to get IDs.
    $(".ersrv-edit-reservation-item-card").each(function () {
      var this_card = $(this);
      var item_id = this_card.data("itemid");
      var subtotal = $(
        "#ersrv-edit-reservation-item-subtotal-" + item_id
      ).text();
      subtotal = parseFloat(subtotal.replace(/[^\d.]/g, ""));
      subtotal = -1 === is_valid_number(subtotal) ? 0 : subtotal;
      item_new_total += subtotal;
    });

    // See if we have the total of non reservation items.
    var non_reservations_items_total = parseFloat(
      $(".ersrv-edit-reservation-non-reservation-items-total").val()
    );
    item_new_total += non_reservations_items_total;

    // Calculate the cost difference now.
    var old_order_total = parseFloat(
      $(".ersrv-edit-reservation-order-total").val()
    );
    var cost_difference = item_new_total - old_order_total;

    /**
     * If the cost difference is more than 0, then the payment is to be made by the customer.
     * Otherwise, the payment would be returned by the store owner.
     */
    if (0 < cost_difference) {
      $(".ersrv-cost-difference-text").html(
        customer_payable_cost_difference_message
      );
      cost_difference_data = {
        key: "cost_difference_customer_payable",
        amount: cost_difference,
      };
    } else if (0 > cost_difference) {
      cost_difference = Math.abs(cost_difference);
      cost_difference_data = {
        key: "cost_difference_admin_payable",
        amount: cost_difference,
      };
      $(".ersrv-cost-difference-text").html(
        admin_payable_cost_difference_message
      );
    } else {
      $(".ersrv-cost-difference-text").html("");
    }

    // Get the formatted cost difference.
    cost_difference = ersrv_get_formatted_price(cost_difference);

    // Paste the cost difference.
    $(".ersrv-edit-reservation-cost-difference").html(cost_difference);

    // Add the new total to the cost difference data.
    cost_difference_data.item_order_total = item_new_total;

    return cost_difference_data;
  }

  /**
   * Validate the item changes.
   *
   * @param {number} item_id
   * @param {string} item_name
   * @returns {boolean}
   */
  function ersrv_validate_reservations_before_update(item_id, item_name) {
    // Get the item details.
    var checkin_date = $(
      "#ersrv-edit-reservation-item-checkin-date-" + item_id
    ).val();
    var checkout_date = $(
      "#ersrv-edit-reservation-item-checkout-date-" + item_id
    ).val();
    var adult_count = parseInt(
      $("#ersrv-edit-reservation-item-adult-count-" + item_id).val()
    );
    adult_count = -1 !== is_valid_number(adult_count) ? adult_count : 0;
    var kid_count = parseInt(
      $("#ersrv-edit-reservation-item-kid-count-" + item_id).val()
    );
    kid_count = -1 !== is_valid_number(kid_count) ? kid_count : 0;
    var guests = adult_count + kid_count;
    var accomodation_limit = parseInt(
      $("#accomodation-limit-" + item_id).val()
    );
    var min_reservation = $("#min-reservation-period-" + item_id).val();
    var max_reservation = $("#max-reservation-period-" + item_id).val();

    // Item validated.
    var item_validated = true;

    // Vacate all the errors.
    $(".ersrv-reservation-error").text();

    // Guests count.
    if (
      -1 === is_valid_number(adult_count) &&
      -1 === is_valid_number(kid_count)
    ) {
      item_validated = false;
      $(".ersrv-reservation-error#guests-error-" + item_id).text(
        reservation_guests_err_msg
      );
    } else if (
      -1 === is_valid_number(adult_count) &&
      -1 !== is_valid_number(kid_count)
    ) {
      item_validated = false;
      $(".ersrv-reservation-error#guests-error-" + item_id).text(
        reservation_only_kids_guests_err_msg
      );
    } else if (accomodation_limit < guests) {
      item_validated = false;
      $(".ersrv-reservation-error#guests-error-" + item_id).text(
        reservation_guests_count_exceeded_err_msg
      );
    }

    // If the checkin and checkout dates are not available.
    if ("" === checkin_date && "" === checkout_date) {
      item_validated = false;
      $(
        ".ersrv-reservation-error#checkin-checkout-dates-error-" + item_id
      ).text(reservation_checkin_checkout_missing_err_msg);
    } else if ("" === checkin_date) {
      item_validated = false;
      $(
        ".ersrv-reservation-error#checkin-checkout-dates-error-" + item_id
      ).text(reservation_checkin_missing_err_msg);
    } else if ("" === checkout_date) {
      item_validated = false;
      $(
        ".ersrv-reservation-error#checkin-checkout-dates-error-" + item_id
      ).text(reservation_checkout_missing_err_msg);
    } else {
      /**
       * If the reservation period is more than allowed.
       * Get the dates between checkin and checkout dates.
       */
      var new_reservation_dates = ersrv_get_dates_between_2_dates(
        checkin_date,
        checkout_date
      );
      var new_reservation_days = new_reservation_dates.length;
      if (min_reservation > new_reservation_days) {
        item_validated = false;
        $(
          ".ersrv-reservation-error#checkin-checkout-dates-error-" + item_id
        ).text(
          reservation_lesser_reservation_days_err_msg.replace(
            "XX",
            min_reservation
          )
        );
      } else if (max_reservation < new_reservation_days) {
        item_validated = false;
        $(
          ".ersrv-reservation-error#checkin-checkout-dates-error-" + item_id
        ).text(
          reservation_greater_reservation_days_err_msg.replace(
            "XX",
            max_reservation
          )
        );
      } else {
        // Iterate through the reservation dates to collect the readable dates.
        var readable_reservation_dates = [];
        var readable_reservation_weekdays = [];
        for (var i in new_reservation_dates) {
          readable_reservation_dates.push(
            ersrv_get_formatted_date(new_reservation_dates[i])
          );
          readable_reservation_weekdays.push(new_reservation_dates[i].getDay());
        }

        // Check here, if the dates selected by the customer contains dates that are already reserved.
        var reserved_dates = $("#blocked-dates-" + item_id).val();
        var unavailable_weekdays = $("#unavailable-weekdays-" + item_id).val();

        // If there are reserved dates.
        if ("" !== reserved_dates) {
          reserved_dates = JSON.parse(reserved_dates);
          unavailable_weekdays = JSON.parse(unavailable_weekdays);

          // Get the intersecting dates.
          var intersecting_dates = $.grep(
            readable_reservation_dates,
            function (element) {
              return -1 !== $.inArray(element, reserved_dates);
            }
          );

          // If there are common weekdays that the reservation item is unavailable.
          var intersecting_weekdays = $.grep(
            readable_reservation_weekdays,
            function (element) {
              return $.inArray(element, unavailable_weekdays) !== -1;
            }
          );

          // So, if there are intersecting dates, then there is an error.
          if (
            0 < intersecting_dates.length ||
            0 < intersecting_weekdays.length
          ) {
            return {
              is_valid: -1,
              message: reservation_blocked_dates_err_msg_per_item.replace(
                "XX",
                item_name
              ),
            };
          }
        }
      }
    }

    // Exit, if we cannot process the reservation.
    if (false === item_validated) {
      return {
        is_valid: -1,
        message: reservation_item_changes_invalidated.replace("XX", item_name),
      };
    }

    // Return a positive response otherwise.
    return {
      is_valid: 1,
      message: "",
    };
  }

  /**
   * Check if the reservation values are changed.
   *
   * @param {number} item_id
   * @returns {number}
   */
  function ersrv_is_reservation_value_changed(item_id) {
    // Check if there is a change in checkin date.
    var current_checkin_date = $(
      "#ersrv-edit-reservation-item-checkin-date-" + item_id
    ).val();
    var old_checkin_date = $(
      "#ersrv-edit-reservation-item-checkin-date-" + item_id
    ).data("oldval");
    if (current_checkin_date !== old_checkin_date) {
      return 1;
    }

    // Check if there is a change in checkout date.
    var current_checkout_date = $(
      "#ersrv-edit-reservation-item-checkout-date-" + item_id
    ).val();
    var old_checkout_date = $(
      "#ersrv-edit-reservation-item-checkout-date-" + item_id
    ).data("oldval");
    if (current_checkout_date !== old_checkout_date) {
      return 1;
    }

    // Check if there is a change in adult count.
    var current_adult_count = parseInt(
      $("#ersrv-edit-reservation-item-adult-count-" + item_id).val()
    );
    current_adult_count =
      -1 !== is_valid_number(current_adult_count) ? current_adult_count : 0;
    var old_adult_count = parseInt(
      $("#ersrv-edit-reservation-item-adult-count-" + item_id).data("oldval")
    );
    if (current_adult_count !== old_adult_count) {
      return 1;
    }

    // Check if there is a change in kids count.
    var current_kid_count = parseInt(
      $("#ersrv-edit-reservation-item-kid-count-" + item_id).val()
    );
    current_kid_count =
      -1 !== is_valid_number(current_kid_count) ? current_kid_count : 0;
    var old_kid_count = parseInt(
      $("#ersrv-edit-reservation-item-kid-count-" + item_id).data("oldval")
    );
    if (current_kid_count !== old_kid_count) {
      return 1;
    }

    // Check if there is a change in the amenities.
    var amenities_altered = false;

    // Iterate through the amenities to get their current values.
    $(".ersrv-single-amenity-block").each(function () {
      var this_amenity = $(this);
      var amenity_checkbox = this_amenity.find('input[type="checkbox"]');
      var amenity_current_value = amenity_checkbox.is(":checked");
      amenity_current_value =
        true === amenity_current_value ? "checked" : "unchecked";
      var amenity_old_value = amenity_checkbox.data("oldval");

      // If the amenity is changed.
      if (amenity_current_value !== amenity_old_value) {
        amenities_altered = true;
        return false;
      }
    });
    if (true === amenities_altered) {
      return 1;
    }

    // If nothing is changed, return -1;
    return -1;
  }
});
