jQuery(function ($){
  $('#video-slider').slick({


arrows: true,
appendArrows: $('#slider-one'),
prevArrow: '<div class="toggle-left"><i class="fa slick-prev fa-chevron-left"></i></div>',
nextArrow: '<div class="toggle-right"><i class="fa slick-next fa-chevron-right"></i></div>',
autoplay: false,
autoplaySpeed: 8000,
speed: 800,
slidesToShow: 4,
slidesToScroll: 4,
accessibility:false,
dots:false,

  responsive: [
    {
      breakpoint: 992,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 3
      }
    },
    {
      breakpoint: 768,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 2,
        arrows: false,
      }
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
      }
    }
    // You can unslick at a given breakpoint now by adding:
    // settings: "unslick"
    // instead of a settings object
  ]


});
$('#video-slider').show();
});

