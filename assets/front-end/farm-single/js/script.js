/* Script on ready
------------------------------------------------------------------------------*/

$(document).ready(function () {
  //do jQuery stuff when DOM is ready
  if (jQuery(".banner-slider").length) {
    jQuery(".banner-slider").slick({
      arrows: true,
      dots: false,
      adaptiveHeight: false,
      infinite: false,
      slidesToShow: 1,
      slidesToScroll: 1,
      autoplay: true,
      rows: 0,
    });
  }
});

/* Script on scroll
------------------------------------------------------------------------------*/
$(window).on("scroll", function () { });

/* Script on resize
------------------------------------------------------------------------------*/
$(window).on("resize", function () { });
