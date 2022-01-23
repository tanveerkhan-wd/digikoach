//homepage slider
$(document).ready(function() {
  var owl = $('.testimonial');
  owl.owlCarousel({
    stagePadding: 45,
    margin: 120,
    nav: true,
    loop: true,
    responsive: {
      0: {
        items: 1,
        nav: true,
        loop: true,
      },
      600: {
        items: 1,
        nav: true,
        loop: true,
      },
      1000: {
        items: 1,
        nav: true,
        loop: true,
      }
    }
  })
})

//
$(".checkbox-menu").on("change", "input[type='checkbox']", function() {
   $(this).closest("li").toggleClass("active", this.checked);
});

$(document).on('click', '.allow-focus', function (e) {
  e.stopPropagation();
});


//scrolling 
 $(document).ready(function () {
     $(document).on("scroll", onScroll);
     var hashid = window.location.hash;
    $('.menu-center').find('.nav-link').each(function () {
           if($(this).attr('href')==hashid){
               $(this).addClass("active");
           }
        });
    //smoothscroll
    $('.menu-center a[href^="#"]').on('click', function (e) {
        e.preventDefault();
        $(document).off("scroll");
        
        $('a').each(function () {
            $(this).removeClass('active');
        })
        $(this).addClass('active');
      
        var target = this.hash,
            menu = target;
        $target = $(target);
        console.log('at',$target.offset().top);
        $('html, body').stop().animate({
            'scrollTop': $target.offset().top - 70
        }, 1000, 'swing', function () {
            // window.location.hash = target;
            $(document).on("scroll", onScroll);
        });
    });
});

function onScroll(event){
    var scrollPos = $(document).scrollTop();
    $('.menu-center a').each(function () {
        var currLink = $(this);
        var refElement = $(currLink.attr("href"));
        if (refElement.position().top <= scrollPos && refElement.position().top + refElement.height() > scrollPos) {
            $('.menu-center ul li a').removeClass("active");
            currLink.addClass("active");
        }
        else{
           currLink.removeClass("active");
        }
    });
}

// jQuery to collapse the navbar on scroll
function collapseNavbar() {
    if ($(".navbar").offset().top > 50) {
        $(".fixed-top").addClass("top-nav-collapse");
    } else {
        $(".fixed-top").removeClass("top-nav-collapse");
    }
}

$(function(){
        $('#logedin').on("click", function () {
        $('body').addClass("newheader");
        });
    }); 

$(window).scroll(collapseNavbar);
$(document).ready(collapseNavbar);

//sidebar menu mobile menu
$(document).ready(function () {
    $('#slide').click(function () {
        var hidden = $('.sideoff-off');
        var hidden1 = $('.overlay');
        if (hidden.hasClass('visible')) {
            hidden.animate({"right": "-1300px"}, 500).removeClass('visible');
            $(".overlay").css('display', 'none');
        } else {
            hidden.animate({"right": "0px"}, 500).addClass('visible');
            hidden1.fadeIn(500);
            $( "body" ).addClass( "noscroll" );
        }
    });
    $('.changeonclick').click(function () {
        $(this).find('a').find('b').toggleClass('glyphicon-menu-up');
        $(this).find('a').find('b').toggleClass('glyphicon-menu-down');
    });
    $('.changeonclicka').click(function () {
        $(this).find('.panel-title').find('b').toggleClass('glyphicon-menu-up');
        $(this).find('.panel-title').find('b').toggleClass('glyphicon-menu-down');
    });
    
    $('#slideclose').click(function () {
        var hidden = $('.sideoff-off');
        var hidden1 = $('.overlay');
        hidden.animate({"right": "-1300px"}, 500).removeClass('visible');
        hidden1.fadeOut(500);
        $( "body" ).removeClass( "noscroll" );
    });
    $('.navbar-nav a.nav-link').click(function () {
        var hidden = $('.sideoff-off');
        var hidden1 = $('.overlay');
        hidden.animate({"right": "-1300px"}, 500).removeClass('visible');
        hidden1.fadeOut(500);
        $( "body" ).removeClass( "noscroll" );
    });

});
function closeOverlay()
{
    var hidden = $('.sideoff-off');
    var hidden1 = $('.overlay');
    hidden.animate({"right": "-1000px"}, 500).removeClass('visible');
    hidden1.fadeOut(500);
    $( "body" ).removeClass( "noscroll" );
}


  $(window).scroll(function() {
    $('#animatedElement').each(function(){
    var imagePos = $(this).offset().top;

    var topOfWindow = $(window).scrollTop();
      if (imagePos < topOfWindow+400) {
        $(this).addClass("slideUp");
      }
    });
  });



// =================== custom cursor



var cursor = $(".your-cursor2"),
    follower = $(".follow2");

var posX = 0,
    posY = 0;

var mouseX = 0,
    mouseY = 0;

TweenMax.to({}, 0.016, {
    repeat: -1,
    onRepeat: function() {
        posX += (mouseX - posX) / 9;
        posY += (mouseY - posY) / 9;

        TweenMax.set(follower, {
            css: {    
                left: posX - 12,
                top: posY - 12
            }
        });

        TweenMax.set(cursor, {
            css: {    
                left: mouseX,
                top: mouseY
            }
        });
    }
});

$(document).on("mousemove", function(e) {
    mouseX = e.pageX;
    mouseY = e.pageY;
});

$("a").on("mouseenter", function() {
    cursor.addClass("active");
    follower.addClass("active");
});
$("a").on("mouseleave", function() {
    cursor.removeClass("active");
    follower.removeClass("active");
});

// faqs page accordions

    $(".accordions").on("click", ".accordions_title", function() {

    $('.active').removeClass('active');
        if(false == $(this).next().is(':visible')) {
            $('.accordions > .accordions_content').slideUp(300);
            $(this).addClass('active');
        }
        $(this).next().slideToggle(300);
    });
