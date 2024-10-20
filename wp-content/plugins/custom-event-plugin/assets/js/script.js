jQuery(document).ready(function ($) {
  let page = 1;
  const maxPages = $("#load-more").data("max"); // Get max pages from the button

  //Load More Events
  $("#load-more").on("click", function () {
    if (page < maxPages) {
      page++;
      $("#loader").show(); // Show loader before the AJAX call
      $(this).prop("disabled", true); // Disable the button

      $.ajax({
        url: ajaxurl,
        type: "POST",
        data: {
          action: "load_more_events",
          page: page,
        },
        success: function (response) {
          if (response.trim() !== "") {
            $(".event-list").append(response); // Append the response HTML
          }

          if (page >= maxPages) {
            $("#load-more").hide(); // Hide button if all events are loaded
          }
        },
        complete: function () {
          $("#loader").hide(); // Hide loader after AJAX call is complete
          $("#load-more").prop("disabled", false); // Re-enable the button
        },
        error: function () {
          alert("An error occurred. Please try again.");
          $("#loader").hide(); // Hide loader on error
          $("#load-more").prop("disabled", false); // Re-enable the button
        },
      });
    }
  });

  //Add New Event
  $("#event-form").on("submit", function (e) {
    e.preventDefault(); // Prevent the default form submission

    // Show loader (optional)
    $("#loader").show();

    var formData = $(this).serialize(); // Serialize the form data
    $.ajax({
      url: ajaxurl,
      type: "POST",
      data: {
        action: "add_new_event",
        form_data: formData, // Send serialized form data
      },
      success: function (response) {
        $("#loader").hide(); // Hide loader

        // Parse JSON response
        response = JSON.parse(response);

        var messageClass = response.success ? "success" : "error";
        $("#form-response").html(
          '<div class="response ' +
            messageClass +
            '">' +
            response.data +
            "</div>"
        );

        if (response.success) {
          $("#event-form")[0].reset(); // Reset form fields
        }
      },
      error: function () {
        $("#loader").hide(); // Hide loader in case of error
        $("#form-response").html(
          '<div class="response error">An error occurred. Please try again.</div>'
        );
      },
    });
  });

  
});
