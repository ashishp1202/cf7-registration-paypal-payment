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
});
