$(document).ready(function(){                         
  if ($.cookie('preloader')) {         
      $('#loader-wrapper').hide();         
      $('.wrapper').show();         
  } 
  else 
  {         
     $(window).on('load', function () {   
         $('#loader-wrapper').fadeOut(1000);                           
      });       
      $('.wrapper').show();
      $.cookie('preloader', true, {         
          path: '/',         
          expire: 1         
      });         
   }      

new WOW().init();
  
}); 


	//Match title height
function MatchHeight1() {
  $('.match')
    .matchHeight({})
  ;
}
//Functions that run when all HTML is loaded
$(document).ready(function() {
  MatchHeight1(); 
});
$(document).resize(function() {
  MatchHeight1(); 
});

$(document).ready(function() {
  $('.cat-select').niceSelect();
});

$(document).ready(function(){
   $('.banner-slider').slick({
      slidesToShow: 1,
      slidesToScroll: 1,
      autoplay: true,
      autoplaySpeed: 2000,
      infinite:true,
      dots: true,
      arrows: true,
      fade: true,
      responsive: [
      {
        breakpoint: 481
        ,
        settings: {
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: true
        }
      },
    ]
  });

$('.brands-list-slider').slick({
   infinite: true,
   arrows:true,
    slidesToShow: 7,
    slidesToScroll: 1,
    responsive: [
    {
      breakpoint: 1024,
      settings: {
        slidesToShow: 6,
        slidesToScroll: 1,
      }
    },
    {
      breakpoint: 768,
      settings: {
        slidesToShow: 4,
        slidesToScroll: 1
      }
    },
    {
      breakpoint: 481,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 1,
        dots: false,
        autoplay: false
      }
    },
    {
      breakpoint: 396,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1
      }
    }
  ]
});

//featured-slider

$('.featured-slider').slick({
slidesToShow: 4,
slidesToScroll: 1,
autoplay: false,
autoplaySpeed: 2000,
dots: false,
arrows: true,
responsive: [
    {
      breakpoint: 1025,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 1,
      }
    },
    {
      breakpoint: 768,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 1
      }
    },
    {
      breakpoint: 481,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1
      }
    }
  ]

});

if ($(window).width() > 767) {
  $('.latest-prod-slider').slick({
  slidesToShow: 1,
  rows:2,
  slidesPerRow: 2,
  autoplay: false,
  autoplaySpeed: 2000,
  dots: false,
  arrows: true,
  vertical:true,
  verticalSwiping: true,
  });
}


if ($(window).width() < 768) {
  $('.latest-prod-slider').slick({
  slidesToShow: 1,
  rows:1,
  slidesPerRow: 1,
  autoplay: false,
  autoplaySpeed: 2000,
  dots: false,
  arrows: true,
  vertical:false,
  verticalSwiping: false,
  });
}

 
//best-seller-slider
 
 $('.best-seller-slider').slick({
slidesToShow: 2,
slidesToScroll: 1,
autoplay: true,
autoplaySpeed: 2000,
dots: false,
arrows: true,
vertical:true,
verticalSwiping: true,
responsive: [
    {
      breakpoint: 992,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 1,
        vertical:false,
        verticalSwiping: false
      }
    },
    {
      breakpoint: 681,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1,
        vertical:false,
        verticalSwiping: false
      }
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1,
        vertical:false,
        verticalSwiping: false
      }
    }
  ]

});



$(function () {
  $('#myTab a:last').tab('show')
})

$('.trend-slider').slick({
  slidesToShow: 4,
  slidesToScroll: 1,
  autoplay: false,
  autoplaySpeed: 2000,
  dots: false,
  arrows: true,
  responsive: [
    {
      breakpoint: 1025,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 1,
      }
      },
      {
      breakpoint: 769,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1
      }
      },
       {
      breakpoint: 481,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1
      }
    }
  ]
})

$('.trend-slider1').slick({
  slidesToShow: 4,
  slidesToScroll: 1,
  autoplay: false,
  autoplaySpeed: 2000,
  dots: false,
  arrows: true,
  responsive: [
    {
      breakpoint: 1025,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 1,
      }
      },
      {
      breakpoint: 769,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1,
      }
      },
     {
      breakpoint: 481,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1
      }
    }
  ]
})
//featured-slider

$('.test-slider').slick({
slidesToShow: 3,
slidesToScroll: 1,
autoplay: false,
autoplaySpeed: 2000,
dots: false,
arrows: true,
responsive: [
    {
      breakpoint: 992,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1,
      }
    },
    {
      breakpoint: 768,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1
      }
    },
    {
      breakpoint: 481,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1
      }
    }
  ]
});


// gallery_01

$('#gallery_01').slick({
slidesToShow: 3,
slidesToScroll: 1,
autoplay: false,
autoplaySpeed: 2000,
dots: false,
arrows: true,
});


});

$(document).ready(function() {
 // $('select').niceSelect();
});

  $('.cart-icon').click(function(){
     $('.cart-sidebar').css("right","0");
     $('.cart-sidebar').css("transition","0.5s all ease-in-out");
     $('.body-overlay').css("display","block");
     $('.close-cart-div').toggleClass('open')
     /*$('.close-cart-div').css("right","537px");
     $('.close-cart-div').css("transition","0.5s all ease-in-out");*/
  });
  
  $('.close-cart-div').click(function(){
    // alert('hello');
     $('.cart-sidebar').css("right","-100%");
     $('.cart-sidebar').css("transition","0.5s all ease-in-out");
     $('.body-overlay').css("display","none");
     $(this).toggleClass('open');
     /*$('.close-cart-div').css("right","-100%");
     $('.close-cart-div').css("transition","0.5s all ease-in-out");*/
  });
  
$(document).ready(function() {
	  $(".fp-btn").click(function(){
			$("#forgot-form").toggle();
			$("#login-form").toggle();
	  });
	    $(".back-btn").click(function(){
			$("#forgot-form").toggle();
			$("#login-form").toggle();
	  });


   
      

 });


$(document).ready(function(){
  $('.onclickkk').click(function(){
    $('.srchbar').animate({
      width: "toggle"
    });
  });


  $(".Price-filter-section").click(function(){
    $(this).toggleClass('openfilter');
  });
  $(".Price-filter-section .about").click(function(){
    $(this).toggleClass('openfilter');
  });

});



$(window).scroll(function(){
  if ($(window).scrollTop() >= 100) {
  $('.header').addClass('sticky');
  }
  else {
  $('.header').removeClass('sticky');
  }
  });



$(document).ready(function(){
  $(".dropdown a").click(function(){
      $(this).toggleClass("activearrow");
      $(this).closest(".dropdown").toggleClass("submenuopened");
      $(this).closest(".dropdown").find(".dropdownmenu1").toggleClass("showdropdown");
    });

    
    $(".dropdown-submenu .droperarrow").click(function(){
        $(this).toggleClass("activearrow");
      $(this).closest(".dropdown-submenu").toggleClass("subsubmenuopened");
      $(this).closest(".dropdown-submenu").children(".dropdown-menu").toggleClass("showdropdown");
    });

   
});