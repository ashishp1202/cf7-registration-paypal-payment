/* Script on ready
------------------------------------------------------------------------------*/

$(document).ready(function () {
  //do jQuery stuff when DOM is ready
  if (jQuery(".banner-slider").length) {
    jQuery(".banner-slider").slick({
      arrows: false,
      dots: false,
      adaptiveHeight: false,
      infinite: false,
      slidesToShow: 1,
      slidesToScroll: 1,
      autoplay: true,
      rows: 0,
    });
  }

  $('.banner-slider-thumb-grid .banner-grid-item').on('click', function () {
    var index = $(this).index();
    $('.banner-slider').slick('slickGoTo', index);
  });

  // Fanybox slider //
  Fancybox.bind("[data-fancybox='gallery']", {
    Thumbs: {
      autoStart: true,
    },
  });

  /* match height card content */
  if ($(".farm-listing-ajax-section .farm-listing-item .farm-listing-item-title").length > 0) {
    $(".farm-listing-ajax-section .farm-listing-item .farm-listing-item-title").matchHeight({
      byRow: true,
      property: "min-height",
    });
  }

  if ($(".farm-listing-ajax-section .farm-listing-item .farm-listing-item-capicity h3").length > 0) {
    $(".farm-listing-ajax-section .farm-listing-item .farm-listing-item-capicity h3").matchHeight({
      byRow: true,
      property: "min-height",
    });
  }
});

/* Script on scroll
------------------------------------------------------------------------------*/
$(window).on("scroll", function () { });

/* Script on resize
------------------------------------------------------------------------------*/
$(window).on("resize", function () { });
