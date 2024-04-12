jQuery(document).ready(function ($) {
  "use strict";

  // Localized variables.
  var ajaxurl = ERSRV_Admin_Script_Vars.ajaxurl;
  var same_as_adult = ERSRV_Admin_Script_Vars.same_as_adult;
  var export_reservations = ERSRV_Admin_Script_Vars.export_reservations;
  var accomodation_limit_text = ERSRV_Admin_Script_Vars.accomodation_limit_text;
  var start_of_week = parseInt(ERSRV_Admin_Script_Vars.start_of_week);
  var woo_currency = ERSRV_Admin_Script_Vars.woo_currency;
  var reservation_customer_err_msg =
    ERSRV_Admin_Script_Vars.reservation_customer_err_msg;
  var reservation_guests_err_msg =
    ERSRV_Admin_Script_Vars.reservation_guests_err_msg;
  var reservation_only_kids_guests_err_msg =
    ERSRV_Admin_Script_Vars.reservation_only_kids_guests_err_msg;
  var reservation_guests_count_exceeded_err_msg =
    ERSRV_Admin_Script_Vars.reservation_guests_count_exceeded_err_msg;
  var reservation_checkin_checkout_missing_err_msg =
    ERSRV_Admin_Script_Vars.reservation_checkin_checkout_missing_err_msg;
  var reservation_checkin_missing_err_msg =
    ERSRV_Admin_Script_Vars.reservation_checkin_missing_err_msg;
  var reservation_checkout_missing_err_msg =
    ERSRV_Admin_Script_Vars.reservation_checkout_missing_err_msg;
  var reservation_lesser_reservation_days_err_msg =
    ERSRV_Admin_Script_Vars.reservation_lesser_reservation_days_err_msg;
  var reservation_greater_reservation_days_err_msg =
    ERSRV_Admin_Script_Vars.reservation_greater_reservation_days_err_msg;
  var reservation_customer_first_name_err_msg =
    ERSRV_Admin_Script_Vars.reservation_customer_first_name_err_msg;
  var reservation_customer_last_name_err_msg =
    ERSRV_Admin_Script_Vars.reservation_customer_last_name_err_msg;
  var reservation_customer_email_err_msg =
    ERSRV_Admin_Script_Vars.reservation_customer_email_err_msg;
  var reservation_customer_email_invalid_err_msg =
    ERSRV_Admin_Script_Vars.reservation_customer_email_invalid_err_msg;
  var reservation_customer_password_err_msg =
    ERSRV_Admin_Script_Vars.reservation_customer_password_err_msg;
  var reservation_customer_phone_err_msg =
    ERSRV_Admin_Script_Vars.reservation_customer_phone_err_msg;
  var reservation_customer_address_err_msg =
    ERSRV_Admin_Script_Vars.reservation_customer_address_err_msg;
  var reservation_customer_country_err_msg =
    ERSRV_Admin_Script_Vars.reservation_customer_country_err_msg;
  var reservation_customer_city_err_msg =
    ERSRV_Admin_Script_Vars.reservation_customer_city_err_msg;
  var reservation_customer_postcode_err_msg =
    ERSRV_Admin_Script_Vars.reservation_customer_postcode_err_msg;
  var reservation_blocked_dates_err_msg =
    ERSRV_Admin_Script_Vars.reservation_blocked_dates_err_msg;
  var ersrv_product_type = ERSRV_Admin_Script_Vars.ersrv_product_type;
  var date_format = ERSRV_Admin_Script_Vars.date_format;
  var blocked_dates = ERSRV_Admin_Script_Vars.blocked_dates;
  var new_reservation_button_text =
    ERSRV_Admin_Script_Vars.new_reservation_button_text;
  var new_reservation_url = ERSRV_Admin_Script_Vars.new_reservation_url;
  var toast_success_heading = ERSRV_Admin_Script_Vars.toast_success_heading;
  var toast_error_heading = ERSRV_Admin_Script_Vars.toast_error_heading;
  var toast_notice_heading = ERSRV_Admin_Script_Vars.toast_notice_heading;
  var decline_reservation_cancellation_cnf_message =
    ERSRV_Admin_Script_Vars.decline_reservation_cancellation_cnf_message;
  var approve_reservation_cancellation_cnf_message =
    ERSRV_Admin_Script_Vars.approve_reservation_cancellation_cnf_message;
  var driving_license_allowed_extensions =
    ERSRV_Admin_Script_Vars.driving_license_allowed_extensions;
  var driving_license_invalid_file_error =
    ERSRV_Admin_Script_Vars.driving_license_invalid_file_error;
  var driving_license_empty_file_error =
    ERSRV_Admin_Script_Vars.driving_license_empty_file_error;
  var trim_zeros_from_price = ERSRV_Admin_Script_Vars.trim_zeros_from_price;
  var enable_time_with_date = ERSRV_Admin_Script_Vars.enable_time_with_date;
  var duplicate_amenities_error_message =
    ERSRV_Admin_Script_Vars.duplicate_amenities_error_message;
  var min_reservation_invalid_error_message =
    ERSRV_Admin_Script_Vars.min_reservation_invalid_error_message;
  var datepicker_next_month_button_text =
    ERSRV_Admin_Script_Vars.datepicker_next_month_button_text;
  var datepicker_prev_month_button_text =
    ERSRV_Admin_Script_Vars.datepicker_prev_month_button_text;
  var new_reservation_item_reserved_dates = [];
  var post_type = ersrv_get_query_string_parameter_value("post_type");

  // Add HTML after the kid charge number field.
  $(
    '<a class="ersrv-copy-adult-charge" href="javascript:void(0);">' +
      same_as_adult +
      "</a>"
  ).insertAfter("#accomodation_kid_charge");

  // To add the buttons only on order listing page.
  if (-1 !== is_valid_string(post_type) && "shop_order" === post_type) {
    // Add the dropdown on the order's page.
    var export_reservations_button =
      '<a class="page-title-action ersrv-export-reservations" href="javascript:void(0);">' +
      export_reservations +
      "</a>";
    $(export_reservations_button).insertAfter(
      "body.woocommerce-page.post-type-shop_order .wrap h1.wp-heading-inline"
    );

    // Add the new reservation button html on the orders listing page.
    var new_reservation_button =
      '<a class="page-title-action" href="' +
      new_reservation_url +
      '">' +
      new_reservation_button_text +
      "</a>";
    $(new_reservation_button).insertAfter(
      "body.woocommerce-page.post-type-shop_order .wrap h1.wp-heading-inline"
    );
  }

  /**
   * Implement the datepicker on the export reservations modal.
   */
  if ($(".ersrv-export-reservation-date-field").length) {
    $(".ersrv-export-reservation-date-field").datepicker({
      dateFormat: date_format,
      onSelect: function (selected_date, instance) {
        if ("ersrv-date-from" === instance.id) {
          // Min date for checkout should be on/after the checkin date.
          $("#ersrv-date-to").datepicker("option", "minDate", selected_date);
          setTimeout(function () {
            $("#ersrv-date-to").datepicker("show");
          }, 16);
        }
      },
    });
  }

  if ($(".ersrv-has-datepicker").length) {
    $(".ersrv-has-datepicker").datepicker({
      minDate: 0,
      dateFormat: date_format,
      onSelect: function (selected_date, instance) {
        if ("ersrv-blockout-date-from" === instance.id) {
          // Min date for checkout should be on/after the checkin date.
          $("#ersrv-blockout-date-to").datepicker(
            "option",
            "minDate",
            selected_date
          );
          setTimeout(function () {
            $("#ersrv-blockout-date-to").datepicker("show");
          }, 16);
        }
      },
    });

    // If we're on the reservation item edit page.
    if (-1 !== is_valid_string(ersrv_product_type)) {
      var current_date = new Date();
      var current_month = ("0" + (current_date.getMonth() + 1)).slice(-2);
      var current_date_date = ("0" + current_date.getDate()).slice(-2);
      var today_formatted =
        current_date.getFullYear() +
        "-" +
        current_month +
        "-" +
        current_date_date;
      var reserved_dates = [];

      // Prepare the blocked out dates in a separate array.
      if (0 < blocked_dates.length) {
        for (var i in blocked_dates) {
          reserved_dates.push(blocked_dates[i].date);
        }
      }

      $(".ersrv-has-datepicker").datepicker({
        beforeShowDay: function (date) {
          var loop_date = ("0" + date.getDate()).slice(-2);
          var loop_month = ("0" + (date.getMonth() + 1)).slice(-2);
          var loop_date_formatted =
            date.getFullYear() + "-" + loop_month + "-" + loop_date;
          var date_enabled = true;

          // If not the past date.
          if (today_formatted <= loop_date_formatted) {
            // Add custom class to the active dates of the current month.
            var key = $.map(reserved_dates, function (val, i) {
              if (val === loop_date_formatted) {
                return i;
              }
            });

            // If the loop date is a blocked date.
            if (0 < key.length) {
              date_enabled = false;
            }
          } else {
            date_enabled = false;
          }

          // console.log( loop_date_formatted, date_enabled );

          // Return the datepicker day object.
          return [date_enabled];
        },
      });
    }
  }

  /**
   * Copy the adult charge to the kid's charge.
   */
  $(document).on("click", ".ersrv-copy-adult-charge", function () {
    $("#accomodation_kid_charge").val($("#accomodation_adult_charge").val());
  });

  /**
   * Open the modal to allow date range selection.
   */
  $(document).on("click", ".ersrv-export-reservations", function () {
    $("#ersrv-export-reservations-modal").fadeIn("slow");
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
    if ("ersrv-export-reservations-modal" === evt.target.id) {
      $(".ersrv-modal").fadeOut("slow");
    }
  });

  /**
   * Export reservations.
   */
  $(document).on("click", ".export-reservations", function () {
    var this_button = $(this);
    var from_date = $("#ersrv-date-from").val();
    var to_date = $("#ersrv-date-to").val();

    // Block the element.
    block_element(this_button);

    // Send the AJAX for clearing the log.
    $.ajax({
      url: ajaxurl,
      type: "POST",
      data: {
        action: "export_reservations",
        from_date: from_date,
        to_date: to_date,
      },
      success: function (response) {
        // Unblock the element.
        unblock_element(this_button);

        var today = new Date($.now());
        var today_date_formatted = ersrv_get_formatted_date(today);
        var export_time =
          today_date_formatted +
          "-" +
          today.getHours() +
          "-" +
          today.getMinutes() +
          "-" +
          today.getSeconds();
        export_time = export_time.replaceAll("/", "-");

        // Make the CSV downloadable.
        var download_link = document.createElement("a");
        var csv_data = ["\ufeff" + response];
        var blob_object = new Blob(csv_data, {
          type: "text/csv;charset=utf-8;",
        });

        var url = URL.createObjectURL(blob_object);
        download_link.href = url;

        // Get the datetime now to set the csv file name.
        download_link.download =
          "ersrv-reservation-orders-" + export_time + ".csv";

        // Force the system to download the CSV now.
        document.body.appendChild(download_link);
        download_link.click();
        document.body.removeChild(download_link);
      },
    });
  });

  /**
   * Add amenity HTML block.
   */
  $(document).on("click", ".ersrv-add-amenity-html", function () {
    // Block the element.
    block_element($(".reservations-amenities"));

    // Send the AJAX for adding amenity html.
    $.ajax({
      url: ajaxurl,
      type: "POST",
      data: {
        action: "get_amenity_html",
      },
      success: function (response) {
        // Return, if the response is not proper.
        if ("amenity-html-fetched" !== response.data.code) {
          return false;
        }

        unblock_element($(".reservations-amenities")); // Unblock the element.
        $(".amenities-list").append(response.data.html); // Apend the amenity html block.
        $(".amenities-list p:last-child").find(".addTitle-field").focus(); // Add focus.
      },
    });
  });

  /**
   * Remove amenity HTML block.
   */
  $(document).on("click", ".ersrv-remove-amenity-html", function () {
    $(this).parent(".reservation_amenity_field").remove();
  });

  /**
   * Open calendar for blockingout dates to reservation calendar.
   */
  $(document).on("click", ".ersrv-add-blockedout-date-html", function () {
    $("#ersrv-blockout-reservation-calendar-dates-modal").fadeIn("slow");
  });

  /**
   * Add blockout dates HTML.
   */
  $(document).on("click", ".submit-blockout-calendar-dates", function () {
    var this_button = $(this);
    var date_from = $("#ersrv-blockout-date-from").val();
    var date_to = $("#ersrv-blockout-date-to").val();
    var message = $(".ersrv-blockout-dates-message textarea").val();

    // Block the element.
    block_element(this_button);

    // Send the AJAX for adding blockout date html.
    $.ajax({
      url: ajaxurl,
      type: "POST",
      data: {
        action: "get_blockout_date_html",
        date_from: date_from,
        date_to: date_to,
        message: message,
      },
      success: function (response) {
        // Return, if the response is not proper.
        if ("blockout-date-html-fetched" !== response.data.code) {
          return false;
        }

        // Unblock the element.
        unblock_element(this_button);

        // Apend the blockout date html block.
        $(".blockout-dates-list").append(response.data.html);

        // Vacate the modal fields.
        $(
          "#ersrv-blockout-date-from, #ersrv-blockout-date-to, .ersrv-blockout-dates-message textarea"
        ).val("");

        // Hide the modal.
        $("#ersrv-blockout-reservation-calendar-dates-modal").fadeOut("slow");

        if ($(".ersrv-has-datepicker").length) {
          $(".ersrv-has-datepicker").datepicker({
            minDate: 0,
            dateFormat: "yy-mm-dd",
          });
        }
      },
    });
  });

  /**
   * Remove blockout date HTML block.
   */
  $(document).on("click", ".ersrv-remove-blockout-date-html", function () {
    $(this).parent(".reservation_blockout_date_field").remove();
  });

  /**
   * Open the new customer modal.
   */
  $(document).on("click", ".ersrv-create-new-customer-link", function () {
    $("#ersrv-new-customer-modal").fadeIn("slow");
  });

  /**
   * Submit new customer details.
   */
  $(document).on("click", ".submit-customer button", function () {
    var this_button = $(this);
    var this_button_text = this_button.text();
    var first_name = $("#ersrv-customer-first-name").val();
    var last_name = $("#ersrv-customer-last-name").val();
    var email = $("#ersrv-customer-email").val();
    var phone = $("#ersrv-customer-phone").val();
    var password = $("#ersrv-customer-password").val();
    var address_line = $("#ersrv-customer-address-line").val();
    var address_line_2 = $("#ersrv-customer-address-line-2").val();
    var country = $("#ersrv-customer-country").val();
    var state = $("#ersrv-customer-state").val();
    state = null === state ? "" : state;
    var city = $("#ersrv-customer-city").val();
    var postcode = $("#ersrv-customer-postcode").val();
    var register_user = true;

    // Vacate the errors.
    $(".ersrv-form-field-error, .ersrv-form-error").text("");

    // Validate the first name.
    if (-1 === is_valid_string(first_name)) {
      $(".ersrv-form-field-error.first-name-error").text(
        reservation_customer_first_name_err_msg
      );
      register_user = false;
    }

    // Validate the last name.
    if (-1 === is_valid_string(last_name)) {
      $(".ersrv-form-field-error.last-name-error").text(
        reservation_customer_last_name_err_msg
      );
      register_user = false;
    }

    // Validate email.
    if (-1 === is_valid_string(email)) {
      $(".ersrv-form-field-error.email-error").text(
        reservation_customer_email_err_msg
      );
      register_user = false;
    } else if (-1 === is_valid_email(email)) {
      $(".ersrv-form-field-error.email-error").text(
        reservation_customer_email_invalid_err_msg
      );
      register_user = false;
    }

    // Validate the phone.
    if ("" === phone) {
      $(".ersrv-form-field-error.phone-error").text(
        reservation_customer_phone_err_msg
      );
      register_user = false;
    }

    // Validate password.
    if (-1 === is_valid_string(password)) {
      $(".ersrv-form-field-error.password-error").text(
        reservation_customer_password_err_msg
      );
      register_user = false;
    }

    // Validate the address line.
    if (-1 === is_valid_string(address_line)) {
      $(".ersrv-form-field-error.address-line-error").text(
        reservation_customer_address_err_msg
      );
      register_user = false;
    }

    // Validate the country.
    if (-1 === is_valid_string(country)) {
      $(".ersrv-form-field-error.country-error").text(
        reservation_customer_country_err_msg
      );
      register_user = false;
    }

    // Validate the city.
    if (-1 === is_valid_string(city)) {
      $(".ersrv-form-field-error.city-error").text(
        reservation_customer_city_err_msg
      );
      register_user = false;
    }

    // Validate the postcode.
    if ("" === postcode) {
      $(".ersrv-form-field-error.postcode-error").text(
        reservation_customer_postcode_err_msg
      );
      register_user = false;
    }

    // Exit, if user registration is set to false.
    if (false === register_user) {
      return false;
    }

    // Block the button.
    block_element(this_button);

    // Activate loader.
    this_button.text("Please wait...");

    // Send the AJAX now.
    var data = {
      action: "register_new_customer",
      first_name: first_name,
      last_name: last_name,
      email: email,
      phone: phone,
      password: password,
      address_line: address_line,
      address_line_2: address_line_2,
      country: country,
      state: state,
      city: city,
      postcode: postcode,
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
        if ("ersrv-user-exists" === response.data.code) {
          // Unblock the button.
          unblock_element(this_button);

          // Activate loader.
          this_button.html(this_button_text);

          // Paste the error message.
          $(".ersrv-form-error").text(response.data.error_message);

          return false;
        }

        // If user is created.
        if ("ersrv-user-registered" === response.data.code) {
          // Unblock the button.
          unblock_element(this_button);

          // Activate loader.
          this_button.html(this_button_text);

          // Vacate all the form values.
          $(
            "#ersrv-customer-first-name, #ersrv-customer-last-name, #ersrv-customer-email, #ersrv-customer-password"
          ).val("");

          // Hide the modal.
          $(".ersrv-close-modal").click();

          // Add the user as an option in the select box, and select the created user.
          $("#customer-id")
            .append(response.data.user_html)
            .val(response.data.user_id);
        }
      },
    });
  });

  /**
   * Generate new password.
   */
  $(document).on("click", ".ersrv-generate-password", function () {
    // Block the element.
    block_element($("#ersrv-customer-password"));

    // Send AJAX.
    $.ajax({
      dataType: "JSON",
      url: ajaxurl,
      type: "POST",
      data: {
        action: "generate_new_password",
      },
      success: function (response) {
        // In case of invalid AJAX call.
        if (0 === response) {
          console.warn("easy reservations: invalid AJAX call");
          return false;
        }

        // If the password is generated.
        if ("password-generated" === response.data.code) {
          // Unblock the element.
          unblock_element($("#ersrv-customer-password"));

          // Paste the password.
          $("#ersrv-customer-password").val(response.data.password);
        }
      },
    });
  });

  /**
   * Fetch the reservation item details for creating new reservation from admin.
   */
  $(document).on("change", "#item-id", function () {
    var this_select = $(this);
    var item_id = this_select.val();

    // Disable everything if item is invalid.
    if (-1 === is_valid_number(item_id)) {
      // Block the items.
      block_element($("tr.ersrv-new-reservation-customer-row"));
      block_element($("tr.ersrv-new-reservation-accomodation-row"));
      block_element($("tr.ersrv-new-reservation-checkin-checkout-row"));
      block_element($("tr.ersrv-new-reservation-customer-note-row"));
      block_element($(".ersrv-add-new-reservation"));
      block_element($(".new-reservation-summary"));
      block_element($("tr.ersrv-new-reservation-item-availability-row"));
      block_element($("tr.ersrv-new-reservation-amenities-row"));
      return false;
    }

    // Vacate the reservation dates array.
    new_reservation_item_reserved_dates = [];

    // Destroy the datepickers.
    $(
      ".ersrv-item-availability-calendar, #ersrv-checkin-date, #ersrv-checkout-date"
    ).datepicker("destroy");

    // Change the accomodation limit text.
    $(".ersrv-new-reservation-limit-text").text(accomodation_limit_text);

    // Block the element.
    block_element(this_select);

    // Send AJAX.
    $.ajax({
      dataType: "JSON",
      url: ajaxurl,
      type: "POST",
      data: {
        action: "get_reservable_item_details",
        item_id: item_id,
      },
      success: function (response) {
        // In case of invalid AJAX call.
        if (0 === response) {
          console.warn("easy reservations: invalid AJAX call");
          return false;
        }

        // If the password is generated.
        if ("item-details-fetched" === response.data.code) {
          // Unblock the element.
          unblock_element(this_select);

          // Unblock the items.
          unblock_element($("tr.ersrv-new-reservation-customer-row"));
          unblock_element($("tr.ersrv-new-reservation-accomodation-row"));
          unblock_element($("tr.ersrv-new-reservation-checkin-checkout-row"));
          unblock_element($("tr.ersrv-new-reservation-customer-note-row"));
          unblock_element($(".ersrv-add-new-reservation"));
          unblock_element($(".new-reservation-summary"));
          unblock_element($("tr.ersrv-new-reservation-item-availability-row"));
          unblock_element($("tr.ersrv-new-reservation-amenities-row"));

          // Item details.
          var item_details = response.data.details;
          var accomodation_limit =
            -1 !== is_valid_number(item_details.accomodation_limit)
              ? parseInt(item_details.accomodation_limit)
              : "";
          $("#accomodation-limit").val(accomodation_limit);
          $(".ersrv-new-reservation-limit-text").text(
            accomodation_limit_text.replace("--", accomodation_limit)
          );

          var blocked_dates = item_details.reserved_dates;
          var unavailable_weekdays = item_details.unavailable_weekdays;
          var today_formatted = ersrv_get_formatted_date(new Date());

          // Prepare the blocked out dates in a separate array.
          if (0 < blocked_dates.length) {
            for (var i in blocked_dates) {
              new_reservation_item_reserved_dates.push(blocked_dates[i].date);
            }
          }

          // Set the calendar on checkin and checkout dates.
          $(
            ".ersrv-item-availability-calendar, #ersrv-checkin-date, #ersrv-checkout-date"
          ).datepicker({
            onSelect: function (selected_date, instance) {
              if ("ersrv-checkin-date" === instance.id) {
                // Min date for checkout should be on/after the checkin date.
                $("#ersrv-checkout-date").datepicker(
                  "option",
                  "minDate",
                  selected_date
                );
                setTimeout(function () {
                  $("#ersrv-checkout-date").datepicker("show");
                }, 16);
              }

              // Also check if the checkin and checkout dates are available, unblock the amenities wrapper.
              var checkin_date = $("#ersrv-checkin-date").val();
              var checkout_date = $("#ersrv-checkout-date").val();

              // Calculate the totals, if both the dates are available.
              if (
                1 === is_valid_string(checkin_date) &&
                1 === is_valid_string(checkout_date)
              ) {
                // Recalculate the product summary.
                ersrv_recalculate_reservation_details_item_summary();
              }
            },
            beforeShowDay: function (date) {
              var loop_date_formatted = ersrv_get_formatted_date(date);
              var date_enabled = true;
              var date_class = "";

              // If not the past date.
              if (today_formatted <= loop_date_formatted) {
                // Add custom class to the active dates of the current month.
                var key = $.map(
                  new_reservation_item_reserved_dates,
                  function (val, i) {
                    if (val === loop_date_formatted) {
                      return i;
                    }
                  }
                );

                // If the loop date is a blocked date.
                if (0 < key.length) {
                  date_class =
                    "ui-datepicker-unselectable ui-state-disabled ersrv-date-disabled";
                  date_enabled = false;
                } else {
                  date_class = "ersrv-date-active";
                }

                // Check for the unavailable weekdays.
                if (0 < unavailable_weekdays.length) {
                  var weekday = date.getDay().toString();
                  if (-1 !== $.inArray(weekday, unavailable_weekdays)) {
                    date_class =
                      "ui-datepicker-unselectable ui-state-disabled ersrv-date-disabled";
                    date_enabled = false;
                  }
                }
              } else {
                date_class = "ersrv-date-disabled";
                date_enabled = false;
              }

              // Return the datepicker day object.
              return [date_enabled, date_class];
            },
            minDate: 0,
            weekStart: start_of_week,
            changeMonth: true,
            dateFormat: date_format,
            nextText: datepicker_next_month_button_text,
            prevText: datepicker_prev_month_button_text,
          });

          // Min and max reservation periods.
          var min_reservation_period =
            -1 !== is_valid_number(item_details.min_reservation_period)
              ? parseInt(item_details.min_reservation_period)
              : -1;
          var max_reservation_period =
            -1 !== is_valid_number(item_details.max_reservation_period)
              ? parseInt(item_details.max_reservation_period)
              : -1;
          $("#min-reservation-period").val(min_reservation_period);
          $("#max-reservation-period").val(max_reservation_period);

          // Put the amenities html.
          $("tr.ersrv-new-reservation-amenities-row td").html(
            item_details.amenity_html
          );

          // Adult & kid's charge.
          var adult_charge =
            -1 !== is_valid_number(item_details.adult_charge)
              ? parseFloat(item_details.adult_charge)
              : 0;
          var kid_charge =
            -1 !== is_valid_number(item_details.kid_charge)
              ? parseFloat(item_details.kid_charge)
              : 0;
          $("#adult-charge").val(adult_charge);
          $("#kid-charge").val(kid_charge);

          // Security amount.
          var security_amount =
            -1 !== is_valid_number(item_details.security_amount)
              ? parseFloat(item_details.security_amount)
              : 0;
          $("#security-amount").val(security_amount);
        }
      },
    });
  });

  /**
   * Accomodation adult charge.
   */
  $(document).on("keyup click", "#adult-accomodation-count", function () {
    // Recalculate the product summary.
    ersrv_recalculate_reservation_details_item_summary();
  });

  /**
   * Accomodation kids charge.
   */
  $(document).on("keyup click", "#kid-accomodation-count", function () {
    // Recalculate the product summary.
    ersrv_recalculate_reservation_details_item_summary();
  });

  /**
   * Amenities charge summary.
   */
  $(document).on("click", ".ersrv-new-reservation-single-amenity", function () {
    // Recalculate the product summary.
    ersrv_recalculate_reservation_details_item_summary();
  });

  /**
   * Close the notification.
   */
  $(document).on("click", ".ersrv-notification .close", function () {
    $(".ersrv-notification-wrapper .toast")
      .removeClass("show")
      .addClass("hide");
  });

  /**
   * Add reservation from admin panel.
   */
  $(document).on("click", ".ersrv-add-new-reservation", function () {
    var this_button = $(this);
    var item_id = parseInt($("#item-id").val());
    var customer_id = parseInt($("#customer-id").val());
    customer_id = -1 !== is_valid_number(customer_id) ? customer_id : 0;
    var accomodation_limit = parseInt($("#accomodation-limit").val());
    var checkin_date = $("#ersrv-checkin-date").val();
    var checkout_date = $("#ersrv-checkout-date").val();
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

    // If the customer is not selected.
    if (-1 === is_valid_number(customer_id)) {
      process_reservation = false;
      $(".ersrv-reservation-error.customer-error").text(
        reservation_customer_err_msg
      );
    }

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
        for (var i in reservation_dates) {
          var reservation_date = ("0" + reservation_dates[i].getDate()).slice(
            -2
          );
          var reservation_month = (
            "0" +
            (reservation_dates[i].getMonth() + 1)
          ).slice(-2);
          var reservation_date_formatted =
            reservation_dates[i].getFullYear() +
            "-" +
            reservation_month +
            "-" +
            reservation_date;
          readable_reservation_dates.push(reservation_date_formatted);
        }

        // Check here, if the dates selected by the customer contains dates that are already reserved.
        // If there are common dates between reservation dates and blocked dates, display an error.
        var common_dates = $.grep(
          readable_reservation_dates,
          function (element) {
            return (
              $.inArray(element, new_reservation_item_reserved_dates) !== -1
            );
          }
        );

        // If there are common dates.
        if (0 < common_dates.length) {
          process_reservation = false;
          $(".ersrv-reservation-error.checkin-checkout-dates-error").text(
            reservation_blocked_dates_err_msg
          );
        }
      }
    }

    // Collect the amenities and their charges.
    $(".ersrv-new-reservation-single-amenity").each(function () {
      var this_element = $(this);
      var is_checked = this_element
        .find('input[type="checkbox"]')
        .is(":checked");
      if (true === is_checked) {
        amenities.push({
          amenity: this_element.data("amenity"),
          cost: this_element.data("cost"),
        });
      }
    });

    // Exit, if we cannot process the reservation.
    if (false === process_reservation) {
      return false;
    }

    // Block element.
    block_element(this_button);

    // Shoot the AJAX now for creating the reservation.
    $.ajax({
      dataType: "JSON",
      url: ajaxurl,
      type: "POST",
      data: {
        action: "create_reservation",
        item_id: item_id,
        customer_id: customer_id,
        checkin_date: checkin_date,
        checkout_date: checkout_date,
        adult_count: adult_count,
        kid_count: kid_count,
        amenities: amenities,
        customer_notes: $(
          ".ersrv-new-reservation-customer-note-row td textarea"
        ).val(),
        item_subtotal: $("tr.item-price-summary td span.ersrv-cost").data(
          "cost"
        ),
        kids_subtotal: $("tr.kids-charge-summary td span.ersrv-cost").data(
          "cost"
        ),
        security_subtotal: $(
          "tr.security-amount-summary td span.ersrv-cost"
        ).data("cost"),
        amenities_subtotal: $("tr.amenities-summary td span.ersrv-cost").data(
          "cost"
        ),
        item_total: $("tr.new-reservation-total-cost td span.ersrv-cost").data(
          "cost"
        ),
      },
      success: function (response) {
        // Return, if the response is not proper.
        if (0 === response) {
          console.warn("easy-reservations: invalid ajax call");
          return false;
        }

        // If the reservation is added.
        if ("reservation-created" === response.data.code) {
          // Unblock the element.
          unblock_element(this_button);

          // Button text.
          ersrv_show_notification(
            "bg-success",
            "fa-check-circle",
            toast_success_heading,
            response.data.toast_message
          );

          // Redirect to the order edit page.
          setTimeout(function () {
            window.location.href = response.data.redirect_to;
          }, 5000);
        }
      },
    });
  });

  /**
   * Fetch states based on country code.
   */
  $(document).on("change", "#ersrv-customer-country", function () {
    var this_select = $(this);
    var country_code = this_select.val();

    // Exit, if the country code is invalid.
    if (-1 === is_valid_string(country_code)) {
      return false;
    }

    // Block the element now.
    block_element(this_select);

    // Send the AJAX now.
    $.ajax({
      dataType: "JSON",
      url: ajaxurl,
      type: "POST",
      data: {
        action: "get_states",
        country_code: country_code,
      },
      success: function (response) {
        // Return, if the response is not proper.
        if (0 === response) {
          console.log("easy-reservations: invalid ajax call");
          return false;
        }

        // If the reservation is added.
        if ("states-fetched" === response.data.code) {
          // Unblock the element.
          unblock_element(this_select);

          var states = response.data.states;
          if (0 !== states.length) {
            // Prepare the html for states.
            var html = '<option value="">Select state</option>';
            for (var i in states) {
              html += '<option value="' + i + '">' + states[i] + "</option>";
            }

            // Paste the html.
            $("#ersrv-customer-state").html(html);

            // Show the states select box.
            $(".ersrv-customer-field.state").show();
          } else {
            // Hide the states select box.
            $(".ersrv-customer-field.state").hide();
          }
        }
      },
    });
  });

  /**
   * Add required field attributes to some fields in reservation product type.
   */
  $(document).on("change", "#product-type", function () {
    var product_type = $(this).val();

    $("#location").prop("required", false);
    $("#accomodation_limit").prop("required", false);
    $("#accomodation_adult_charge").prop("required", false);
    $("#accomodation_kid_charge").prop("required", false);
    $("#reservation_min_period").prop("required", false);

    // If the product type is reservation.
    if (ersrv_product_type === product_type) {
      $("#location").prop("required", true);
      $("#accomodation_limit").prop("required", true);
      $("#accomodation_adult_charge").prop("required", true);
      $("#accomodation_kid_charge").prop("required", true);
      $("#reservation_min_period").prop("required", true);
    }
  });

  /**
   * Captain selection mandatory.
   */
  $(document).on("click", "#has_captain", function () {
    var has_captain = $(this).is(":checked");
    $("#reservation_item_captain").prop("required", false);

    // Make the captain selection mandatory if checked.
    if (has_captain) {
      $("#reservation_item_captain").prop("required", true);
    }
  });

  /**
   * Add the reservation to google calendar.
   */
  $(document).on("click", ".add-to-gcal", function () {
    var this_button = $(this);
    var order_id = $("#post_ID").val();

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
          console.log("easy reservations: invalid ajax request");
          return false;
        }

        if ("google-calendar-email-sent" === response.data.code) {
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
   * Add the reservation to icalendar.
   */
  $(document).on("click", ".add-to-ical", function () {
    var this_button = $(this);
    var order_id = $("#post_ID").val();

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
          console.log("easy reservations: invalid ajax request");
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
   * Decline the reservation cancellation request.
   */
  $(document).on(
    "click",
    ".ersrv-cancellation-request-actions .decline",
    function () {
      var this_button = $(this);
      var line_item_id = this_button
        .parents(".ersrv-cancellation-request-actions")
        .data("item");
      var confirmation = confirm(decline_reservation_cancellation_cnf_message);

      // Return false, if the line item is invalid.
      if (-1 === is_valid_number(line_item_id)) {
        return false;
      }

      // Return false, if the admin declines the action.
      if (false === confirmation) {
        return false;
      }

      // Block the row.
      block_element(this_button.parents("tr"));

      // Send the AJAX now.
      $.ajax({
        dataType: "JSON",
        url: ajaxurl,
        type: "POST",
        data: {
          action: "decline_reservation_cancellation_request",
          line_item_id: line_item_id,
        },
        success: function (response) {
          // Check for invalid ajax request.
          if (0 === response) {
            console.log("easy reservations: invalid ajax request");
            return false;
          }

          if (
            "reservation-cancellation-request-declined" === response.data.code
          ) {
            unblock_element(this_button.parents("tr")); // Unblock the element.
            ersrv_show_notification(
              "bg-success",
              "fa-check-circle",
              toast_success_heading,
              response.data.toast_message
            ); // Show the notification.
            location.reload(); // Reload.
          }
        },
      });
    }
  );

  /**
   * Approve the reservation cancellation request.
   */
  $(document).on(
    "click",
    ".ersrv-cancellation-request-actions .approve-request",
    function () {
      var this_button = $(this);
      var line_item_id = this_button
        .parents(".ersrv-cancellation-request-actions")
        .data("item");
      var order_id = this_button
        .parents(".ersrv-cancellation-request-actions")
        .data("order");
      var confirmation = confirm(approve_reservation_cancellation_cnf_message);

      // Return false, if the line item is invalid.
      if (-1 === is_valid_number(line_item_id)) {
        return false;
      }

      // Return false, if the admin declines the action.
      if (false === confirmation) {
        return false;
      }

      // Block the row.
      block_element(this_button.parents("tr"));

      // Send the AJAX now.
      $.ajax({
        dataType: "JSON",
        url: ajaxurl,
        type: "POST",
        data: {
          action: "approve_reservation_cancellation_request",
          line_item_id: line_item_id,
          order_id: order_id,
        },
        success: function (response) {
          // Check for invalid ajax request.
          if (0 === response) {
            console.log("easy reservations: invalid ajax request");
            return false;
          }

          if (
            "reservation-cancellation-request-approved" === response.data.code
          ) {
            unblock_element(this_button.parents("tr")); // Unblock the element.
            ersrv_show_notification(
              "bg-success",
              "fa-check-circle",
              toast_success_heading,
              response.data.toast_message
            ); // Show the notification.
            location.reload(); // Reload.
          }
        },
      });
    }
  );

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
    }
  );

  /**
   * Upload the driving license copy on the checkout page.
   */
  $(document).on("click", ".ersrv-upload-license.upload", function () {
    // Check if the license is provided to upload.
    if (
      -1 ===
      is_valid_string($('input[name="reservation-driving-license"]').val())
    ) {
      ersrv_show_notification(
        "bg-danger",
        "fa-skull-crossbones",
        toast_error_heading,
        driving_license_empty_file_error
      );
      return false;
    }

    var oFReader = new FileReader();
    var driving_license = document.getElementById(
      "reservation-driving-license"
    );
    oFReader.readAsDataURL(driving_license.files[0]);

    // Prepare the form data.
    var fd = new FormData();
    fd.append("driving_license_file", driving_license.files[0]);

    // AJAX action.
    fd.append("action", "upload_driving_license");

    // AJAX order ID.
    fd.append("order_id", $("#post_ID").val());

    // Block the element.
    block_element($(".ersrv-driving-license-container"));

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
          unblock_element($(".ersrv-driving-license-container")); // Unblock the element.
          ersrv_show_notification(
            "bg-success",
            "fa-check-circle",
            toast_success_heading,
            response.data.toast_message
          ); // Show toast.
          window.location.href = window.location.href;
        }
      },
    });
  });

  /**
   * Validate the min and max reservation period.
   */
  $(document).on(
    "keyup click",
    "#reservation_min_period, #reservation_max_period",
    function () {
      var min_reservation_period = parseInt($("#reservation_min_period").val());
      var max_reservation_period = parseInt($("#reservation_max_period").val());

      // Check if the period values are not integers.
      if (
        -1 === is_valid_number(min_reservation_period) ||
        -1 === is_valid_number(max_reservation_period)
      ) {
        console.warn("reservation error: non number values");
        return false;
      }

      // Unblock the update button.
      unblock_element($("#publishing-action input#publish"));

      // Check if the min period is more than the max period.
      if (min_reservation_period > max_reservation_period) {
        ersrv_show_notification(
          "bg-danger",
          "fa-skull-crossbones",
          toast_error_heading,
          min_reservation_invalid_error_message
        );

        // Block the update button.
        block_element($("#publishing-action input#publish"));

        return false;
      }
    }
  );

  /**
   * Send reservation reminder email for particular order.
   */
  $(document).on(
    "click",
    ".ersrv-send-reservation-reminder-single",
    function (evt) {
      evt.preventDefault();
      var this_button = $(this);
      var order_id = $("#post_ID").val();

      // Exit, if the order ID is invalid.
      if (-1 === is_valid_number(order_id)) {
        return false;
      }

      // Block the row.
      block_element(this_button);

      // Send the AJAX now.
      $.ajax({
        dataType: "JSON",
        url: ajaxurl,
        type: "POST",
        data: {
          action: "send_reservation_reminder",
          order_id: order_id,
        },
        success: function (response) {
          // Check for invalid ajax request.
          if (0 === response) {
            console.log("easy reservations: invalid ajax request");
            return false;
          }

          if ("reminder-email-sent" === response.data.code) {
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
    }
  );

  /**
   * Validate the amenities.
   */
  $(document).on("click", "#publishing-action input#publish", function () {
    var amenity_titles = [];
    var unique_amenity_titles = [];
    // Check if there are amenities list.
    if ($(".amenities-list").length && $(".reservation_amenity_field").length) {
      // Iterate through the amenities.
      $(".reservation_amenity_field").each(function () {
        var this_amenity_field = $(this);
        var amenity_title = this_amenity_field.find(".addTitle-field").val();
        amenity_titles.push(amenity_title);
      });

      // Sort the array items.
      amenity_titles = amenity_titles.sort();

      // Iterate through the titles to find duplicates.
      for (var k in amenity_titles) {
        var amenity_title = amenity_titles[k];
        amenity_title = amenity_title.toLowerCase();

        if (-1 === $.inArray(amenity_title, unique_amenity_titles)) {
          unique_amenity_titles.push(amenity_title);
        }
      }

      // If the uniques ones match exactly with the original array, there are no duplicates.
      if (amenity_titles.length !== unique_amenity_titles.length) {
        ersrv_show_notification(
          "bg-danger",
          "fa-skull-crossbones",
          toast_error_heading,
          duplicate_amenities_error_message
        );
        return false;
      }
    }
  });

  /**
   * Get the dates that faal between 2 dates.
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
   * Check if a string is valid.
   *
   * @param {string} $data
   */
  function is_valid_string(data) {
    if ("" === data || undefined === data || !isNaN(data) || 0 === data) {
      return -1;
    } else {
      return 1;
    }
  }

  /**
   * Check if a number is valid.
   *
   * @param {number} $data
   */
  function is_valid_number(data) {
    if ("" === data || undefined === data || isNaN(data) || 0 === data) {
      return -1;
    } else {
      return 1;
    }
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
   * Get query string parameter value.
   *
   * @param {string} string
   * @return {string} string
   */
  function ersrv_get_query_string_parameter_value(param_name) {
    var url_string = location.href;
    var url = new URL(url_string);
    var val = url.searchParams.get(param_name);

    return val;
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
   * Recalculate the item summary on the reservation details page.
   */
  function ersrv_recalculate_reservation_details_item_summary() {
    var checkin_date = $("#ersrv-checkin-date").val();
    var checkout_date = $("#ersrv-checkout-date").val();
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
    var adult_count = parseInt($("#adult-accomodation-count").val());
    adult_count = -1 === is_valid_number(adult_count) ? 1 : adult_count;
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
      var is_checked = this_element
        .find('input[type="checkbox"]')
        .is(":checked");
      if (true === is_checked) {
        var amenity_cost = parseFloat(this_element.data("cost"));
        var cost_type = this_element.data("cost_type");
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

    // Put in all the totals now.
    $("tr.item-price-summary td span.ersrv-cost")
      .html(formatted_adult_charge)
      .data("cost", adult_charge);
    $("tr.kids-charge-summary td span.ersrv-cost")
      .html(formatted_kids_charge)
      .data("cost", kids_charge);
    $("tr.security-amount-summary td span.ersrv-cost")
      .html(formatted_security_total)
      .data("cost", security_total);
    $("tr.amenities-summary td span.ersrv-cost")
      .html(formatted_amenities_total)
      .data("cost", amenities_total);
    $("tr.new-reservation-total-cost td span.ersrv-cost")
      .html(formatted_total_cost)
      .data("cost", total_cost);
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
    }, 10000);
  }

  // Show notification.
  // ersrv_show_notification( 'bg-success', 'fa-check-circle', toast_success_heading, 'Success text.' );
  // ersrv_show_notification( 'bg-warning', 'fa-exclamation-circle', toast_notice_heading, 'Notice text.' );
  // ersrv_show_notification( 'bg-danger', 'fa-skull-crossbones', toast_error_heading, 'Error text.' );
});
