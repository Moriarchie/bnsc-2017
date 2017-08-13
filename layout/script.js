$(document).ready(function() {
  
  //fixed navbar
  $(window).scroll(function () {
      //console.log($(window).scrollTop())
    if ($(window).scrollTop() > 349) {
      $('#nav_bar').addClass('navbar-fixed');
      $('.back3').css('width','100%');
    }
    if ($(window).scrollTop() < 350 ) {
      $('#nav_bar').removeClass('navbar-fixed');
      $('.back3').css('width','50%');
    }
  });
  var slideIndex = 1;
  $(".btn-slide").click(function(){
    var x = $(".mySlide");
    var dot = $(this);
    var dots = document.getElementsByClassName('btn-slide');
    slideIndex = $(".btn-slide").index(dot);
    //alert(slideIndex);
    for (i = 0; i < x.length; i++) {
       x[i].className = x[i].className.replace(" show", "");
    }
    for (i = 0; i < dots.length; i++) {
       dots[i].className = dots[i].className.replace(" active", "");
    }
    x[slideIndex].className += " show";  
    dots[slideIndex].className += " active";
  });
});