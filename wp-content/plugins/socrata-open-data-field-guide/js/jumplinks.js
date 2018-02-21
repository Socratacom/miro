function scrollNav() {
  $('.jumplinks a').click(function(){  
    //Animate
    $('html, body').stop().animate({
        scrollTop: $( $(this).attr('href') ).offset().top - 60
    }, 400);
    return false;
  });
  $('.scrollTop a').scrollTop();
}
scrollNav();