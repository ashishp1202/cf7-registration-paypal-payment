jQuery(document).ready(function ($) {
  // $ is now jQuery safely
  $(document).on("change", ".listing-plan-select", function () {
    $(".listing-plan-type").hide();
    if ($(this).val() === "Single Listing Plan") {
      $("#single-listing-plan").show();
    } else if ($(this).val() === "Featured Listing Plan") {
      $("#featured-listing-plan").show();
    } else if ($(this).val() === "Auction Plan") {
      $("#auction-listing-plan").show();
    } else if ($(this).val() === "Unlimited Use Plan") {
      $("#unlimited-listing-plan").show();
    }
  });
  $(document).on(
    "change",
    ".listing-sale-type-radio input[type='radio']",
    function () {
      $(".listing-sale-type").hide();
      if ($(this).val() === "Listing is For Sale") {
        $("#listing-for-sale").show();
      } else if ($(this).val() === "Listing is For Lease/Rent") {
        $("#listing-for-lease-rent").show();
      }
    }
  );
  $(document).on("change", ".land-unit-type-select", function () {
    $(".land-unit-type").hide();
    if ($(this).val() === "Acre") {
      $("#land-unit-type-acre").show();
    } else if ($(this).val() === "Hectare") {
      $("#land-unit-type-hectare").show();
    }
  });
  $(document).on("change", ".house-info-select", function () {
    if ($(this).val() === "Listing Includes a Residence") {
      $(".house-info-includes-res").show();
    } else {
      $(".house-info-includes-res").hide();
    }
  });
  $(".delete-listing").click(function () {
    postId = $(this).data("id");
    nonce = $(this).data("nonce");
    if (!confirm("Are you sure you want to delete this listing?")) {
      return;
    }
    $.ajax({
      type: "POST",
      url: deleteListing.ajax_url,
      data: {
        action: "delete_farm_listing",
        post_id: postId,
        nonce: nonce,
      },
      dataType: "json",
      success: function (response) {
        if (response.success) {
          $("#listing-" + postId).remove();
          renumberTable();
          // Show success message at the top
          $("#message-container")
            .html(
              "<p class='success-message' style='color:green;'>Listing deleted successfully!</p>"
            )
            .fadeIn()
            .delay(2000)
            .fadeOut();
        } else {
          alert("Error: " + response.message);
        }
      },
      error: function (xhr, status, error) {
        console.error("AJAX Error:", error);
      },
    });
  });

  $(document).on("click", ".add-to-fav-trigger", function () {
    postId = $(this).data("post-id");
    var parentObj = $(this).closest("div");
    if (!confirm("Are you sure you want to add to fav?")) {
      return;
    }
    $.ajax({
      type: "POST",
      url: deleteListing.ajax_url,
      data: {
        action: "addtofav_farm_listing",
        post_id: postId,
      },
      dataType: "json",
      success: function (response) {
        if (response.success) {
          parentObj.find(".add-remove-favorites").toggle();
        } else {
          alert("Error: " + response.message);
        }
      },
      error: function (xhr, status, error) {
        console.error("AJAX Error:", error);
      },
    });
  });
  $(document).on("click", ".remove-from-fav-trigger", function () {
    postId = $(this).data("post-id");
    var parentObj = $(this).closest("div");
    if (!confirm("Are you sure you want to delete this listing?")) {
      return;
    }
    $.ajax({
      type: "POST",
      url: deleteListing.ajax_url,
      data: {
        action: "removefromfav_farm_listing",
        post_id: postId,
      },
      dataType: "json",
      success: function (response) {
        if (response.success) {
          parentObj.find(".add-remove-favorites").toggle();
        } else {
          alert("Error: " + response.message);
        }
      },
      error: function (xhr, status, error) {
        console.error("AJAX Error:", error);
      },
    });
  });

  $(document).on("click", "#dairy-farm-search-submit", function () {
    $(".farm-listing-ajax-pagination").hide();
    var farmPrice = $("#farm-price-option").val();
    var farmLocation = $("#farm-location-option").val();
    var farmLandsize = $("#farm-landsize-option").val();
    var farmCowcapacity = $("#farm-cowcapacity-option").val();
    $.ajax({
      type: "POST",
      url: deleteListing.ajax_url,
      data: {
        action: "search_farm_listing",
        farmPrice: farmPrice,
        farmLocation: farmLocation,
        farmLandsize: farmLandsize,
        farmCowcapacity: farmCowcapacity,
      },
      dataType: "json",
      success: function (response) {
        if (response.success) {
          $(".farm-listing-ajax-section .row").html(response.data.html);
        } else {
          alert("Error: " + response.data.message);
        }
      },
      error: function (xhr, status, error) {
        console.error("AJAX Error:", error);
      },
    });
  });
  document.addEventListener(
    "wpcf7mailsent",
    function (event) {
      if (event.detail.apiResponse && event.detail.apiResponse.redirect_to) {
        window.location.href = event.detail.apiResponse.redirect_to;
      }
    },
    false
  );
});

// Function to renumber the Sr. No. column
function renumberTable() {
  $("#listings-container tr").each(function (index) {
    $(this)
      .find("td.sr-no")
      .text(index + 1); // Update serial number
  });
}

document.addEventListener("DOMContentLoaded", function () {
  document.querySelector(".next-step").addEventListener("click", function (e) {
    e.preventDefault(); // Prevent anchor default behavior

    // Scope to the document or a parent container that actually wraps the fields
    let email = document.querySelector('input[name="email-seller"]').value;
    let confirmEmail = document.querySelector(
      'input[name="seller-retrype-email"]'
    ).value;
    let errorSpan = document.querySelector("#email-error");

    let password = document.querySelector(
      'input[name="account-password"]'
    ).value;
    let confirmPassword = document.querySelector(
      'input[name="account-verify-password"]'
    ).value;
    let passwordErrorSpan = document.querySelector("#password-error");

    let isValid = true;

    // Email validation
    if (email !== confirmEmail) {
      errorSpan.style.display = "block";
      isValid = false;
    } else {
      errorSpan.style.display = "none";
    }

    // Password validation
    if (password !== confirmPassword) {
      passwordErrorSpan.style.display = "block";
      isValid = false;
    } else {
      passwordErrorSpan.style.display = "none";
    }

    // Prevent moving forward if validation fails
    if (!isValid) {
      return;
    }

    nextBtn = $(".next-step");
    backBtn = $(".back-step");
    step1 = $("#step-1");
    step2 = $("#step-2");

    // nextBtn.on('click', function () {
    //   step1.css('display', 'none');
    //   step2.css('display', 'block');
    // });

    backBtn.on("click", function () {
      step2.css("display", "none");
      step1.css("display", "block");
    });

    // If valid, go to next step
    document.getElementById("step-1").style.display = "none";
    document.getElementById("step-2").style.display = "block";
  });
});
