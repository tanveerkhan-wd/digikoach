
 $('#preloader').css('display','');
    $(window).on('load', function(){
    $('#preloader').css('display','none');
    $('#preloader').css('opacity','0');
    $('#contents').css('opacity','1');
});

 $(document).ready(function () {
    
    jQuery('.custom-drop-down').append('<div class="button"></div>');    
    jQuery('.custom-drop-down').append('<ul class="select-list"></ul>');    
    jQuery('.custom-drop-down select option').each(function() {  
    var bg = jQuery(this).css('background-image');    
    jQuery('.select-list').append('<li class="clsAnchor"><span value="' + jQuery(this).val() + '" class="' + jQuery(this).attr('class') + '" style=background-image:' + bg + '>' + jQuery(this).text() + '</span></li>');   
    });    
    jQuery('.custom-drop-down .button').html('<span class="custom_flag" style=background-image:' + jQuery('.custom-drop-down select').find(':selected').css('background-image') + '>' + jQuery('.custom-drop-down select').find(':selected').text() + '</span>' + '<a href="javascript:void(0);" class="select-list-link"><i class="fa fa-caret-down" aria-hidden="true"></i></a>');   
    jQuery('.custom-drop-down ul li').each(function() {   
    if (jQuery(this).find('span').text() == jQuery('.custom-drop-down select').find(':selected').text()) {  
    jQuery(this).addClass('active');       
    }      
    });     
    jQuery('.custom-drop-down .select-list span').on('click', function()
    {          
    var dd_text = jQuery(this).text();  
    var dd_img = jQuery(this).css('background-image'); 
    var dd_val = jQuery(this).attr('value');   
    jQuery('.custom-drop-down .button').html('<span class="custom_flag" style=background-image:' + dd_img + '>' + dd_text + '</span>' + '<a href="javascript:void(0);" class="select-list-link"><i class="fa fa-caret-down" aria-hidden="true"></i></a>');      
    jQuery('.custom-drop-down .select-list span').parent().removeClass('active');    
    jQuery(this).parent().addClass('active');     
    $('.custom-drop-down select').val(dd_val).trigger('change');
    $('.custom-drop-down select[name=options]').val( dd_val ); 
    $('.custom-drop-down .select-list li').slideUp();    
    //$('.custom-drop-down .select-list li').hide();   
    });       
    jQuery('.custom-drop-down .button').on('click','a.select-list-link', function()
    {      
        jQuery('.custom-drop-down ul li').slideToggle(); 
        //jQuery('.custom-drop-down ul li').toggle();
    }); 

        $('#sidebarCollapse').on('click', function () {
            var hidden = $('#sidebar');
             var hidden1 = $('.overlay');
            // $('#sidebar').toggleClass('active');
                if (hidden.hasClass('visible')) {
                hidden.animate({"right": "-2500px"}, 500).removeClass('visible');
                $(".overlay").css('display', 'none');
            } else {
                // $('#sidebar').toggleClass('active');
                hidden.animate({"right": "0px"}, 500).addClass('active');                
                hidden1.fadeIn(500);
                // $( "body" ).addClass( "noscroll" );
            }
        });

         $('.overlay').click(function () {
            var hidden = $('#sidebar');
            var hidden1 = $('.overlay');
            hidden.animate({"right": "-250px"}, 500).removeClass('visible');
            hidden1.fadeOut(500);
            // $( "body" ).removeClass( "noscroll" );
        });
                
        
    });


$(document).on('click', '.remove_scroll', function(){
    $('body').removeClass('no_scroll');
});


$(document).ready(function () {

    // $(window).on('popstate', function(event) {
    //  alert("pop");
    // });

    $('.components li.active .list-unstyled').prev('a').children('.cfa').attr('class',"cfa fas fa-chevron-up right-arrow");

    $('.list-unstyled').on('hide.bs.collapse', function () {
      $(this).prev('a').children('.cfa').attr('class',"cfa fas fa-chevron-down right-arrow");
    })

    $('.list-unstyled').on('show.bs.collapse', function () {
      $(this).prev('a').children('.cfa').attr('class',"cfa fas fa-chevron-up right-arrow")
    })


    $('html').click(function(e) {
      //if clicked element is not your element and parents aren't your div
      if (e.target.id != 'custom-flag-drop-down' && $(e.target).parents('#custom-flag-drop-down').length == 0) {
        $('.clsAnchor').hide();
      }
    });
    
	// $(".ajax_request").click(function (e) {
	$(document).on('click', '.ajax_request', function (e) {
        console.log('clicked ajax');
        var base_url = $('#web_base_url').val();
        e.preventDefault();
		showLoader(true);
		var slug = $(this).attr('data-slug');
		var url = $(this).attr('href');
         $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function(result)
            {
                if(result){
                	// $("#content").remove();
                	$(".section").remove();
                	// $(result.content).insertAfter("#sidebar");
                	$(result.content).insertAfter(".navbar");
         			var web_url = base_url + '/' +slug;
                  	ChangeUrl(slug, web_url);
                  	document.title = result.title + " - Digikoach";
                  	//$('#datatable_script').attr('src',result.data.script);
                  	$("#dataTable_script").empty();
                    if (result.script) {
                        appendScript(result.script);
                    }
                    showLoader(false); 
                }else{
                  //toastr.error(result.message);
                }
                
                //showLoader(false);
            },
            error: function(data)
            {
                toastr.error('Something went wrong');
                showLoader(false);
            }
        });
		if (!$(this).hasClass("no_sidebar_active")) {
			$('.components li').removeClass('active');
            $('.components li .list-unstyled').prev('a').children('.cfa').attr('class',"cfa fas fa-chevron-down right-arrow");
			$('.list-unstyled').removeClass('show');
            //$(".custom_ap").append('<i class="fas fa-chevron-up right-arrow"></i>');
	        $('#pageSubmenu1 li').removeClass('active');
	        $(this).parent('li').addClass('active');
	        $(this).closest('ul').parent('li').addClass('active');
            $('.components li.active .list-unstyled').prev('a').children('.cfa').attr('class',"cfa fas fa-chevron-up right-arrow");
            //$(this).closest('ul').parent('li').children("i").attr("class", "fas fa-chevron-up right-arrow");
            console.log($(this).closest('ul').parent('li a').children("i"));
	        $(this).closest('ul').addClass('show');
	    }

    });

});


function appendScript(url){
	var script=document.createElement('script');
	script.type='text/javascript';
	script.src=url;

	$("#dataTable_script").append(script);
}

function ChangeUrl(page, url) {
    if (typeof (history.pushState) != "undefined") {
        var obj = { Page: page, Url: url };
        console.log('obj',obj);
        history.pushState(obj, obj.Page, obj.Url); 
    } else {
        alert("Browser does not support HTML5.");
    }
}

function langSwitch(val) {
    showLoaderFull(true);
    $.ajax({
        url: '/switchLanguage',
        data: {
            "lang": val,
        },
        type: 'POST',
        success: function(data) {
            console.log(data.status);
            showLoaderFull(false);
            if (data.status == true) {
                location.reload();
            }
        },
        error: function(jqXhr, textStatus, errorThrown) {
            //console.log( errorThrown );                    
        }
    });
}

function roleSwitch(val) {
    showLoaderFull(true);
    $.ajax({
        url: '/employee/switchRole',
        data: {
            "role": val,
        },
        type: 'POST',
        success: function(data) {
            console.log(data.status);
            showLoaderFull(false);
            if (data.status == true) {
                window.location.href = data.redirect;
            }
        },
        error: function(jqXhr, textStatus, errorThrown) {
            //console.log( errorThrown );                    
        }
    });
}


// responsive menu
$(document).ready(function () {



    /* range slider with updated value*/
    $('.range_val').on('input change','.range_val',function(){
        var range = $(this).val();
        $(this).next('p.value').html(range+' Star');
    })

    /* setting edit profile */
    $(document).on('click', '#edit_profile', function(){
        $("#edit_profile_detail").css('display','block');
        $("#profile_detail").css('display','none');
    });

    $(document).on('click', '#cancel_edit_profile', function(){
        $("#edit_profile_detail").css('display','none');
        $("#profile_detail").css('display','block');
    });

    /* setting edit profile */
    $(document).on('click', "#edit_profile2", function () {
        $("#edit_profile_detail2").css('display','block');
        $("#profile_detail2").css('display','none');
    });

    $(document).on('click', '#cancel_edit_profile2', function(){
        $("#edit_profile_detail2").css('display','none');
        $("#profile_detail2").css('display','block');
    });

    $(document).on('click', '#edit_profile3', function(){
        $("#edit_profile_detail3").css('display','block');
        $("#profile_detail3").css('display','none');
    });

    $(document).on('click', '#cancel_edit_profile3', function(){
        $("#edit_profile_detail3").css('display','none');
        $("#profile_detail3").css('display','block');
    });

    $(document).on('click', '#edit_profile4', function(){
        $("#edit_profile_detail4").css('display','block');
        $("#profile_detail4").css('display','none');
    });

    $(document).on('click', '#cancel_edit_profile4', function(){
        $("#edit_profile_detail4").css('display','none');
        $("#profile_detail4").css('display','block');
    });


    $('#slide').click(function () {
        var hidden = $('.sideoff-off');
        var hidden1 = $('.overlay');
        if (hidden.hasClass('visible')) {
            hidden.animate({"right": "-1300px"}, 500).removeClass('visible');
            $(".overlay").css('display', 'none');
        } else {
            hidden.animate({"right": "0px"}, 500).addClass('visible');
            hidden1.fadeIn(500);
            // $( "body" ).addClass( "noscroll" );
        }
    });
     $('#slideclose').click(function () {
        var hidden = $('.sideoff-off');
        var hidden1 = $('.overlay');
        hidden.animate({"right": "-1300px"}, 500).removeClass('visible');
        hidden1.fadeOut(500);
        // $( "body" ).removeClass( "noscroll" );
    });
    $('.navbar-nav a.nav-link').click(function () {
        var hidden = $('.sideoff-off');
        var hidden1 = $('.overlay');
        hidden.animate({"right": "-1300px"}, 500).removeClass('visible');
        hidden1.fadeOut(500);
        // $( "body" ).removeClass( "noscroll" );
    });
    /*$('.Benefit_carousel').owlCarousel({
         loop: true,
        margin: 10,
        responsiveClass: true,
        responsive: {
          0: {
            items: 1,
            nav: true
          },
          768: {
            items: 2,
            nav: false
          },
          992: {
            items: 3,
            nav: true,
            loop: true,
            margin: 20
          }
        }
      })*/
    
    /* js for signup step add element */
    $("#add_keyskill").click(function(){
        var txtNewInputBox = document.createElement('div');
        txtNewInputBox.innerHTML="<div class='mt-3 multi_form_control'>" + $("#key_skill .multi_form_control").html() + "</div>" ;
        // alert("HTML: " + $("#key_skill").html());
        document.getElementById("key_skill").appendChild(txtNewInputBox);
    });


    $("#add_history").click(function(){
        var txtNewInputBox = document.createElement('div');
        txtNewInputBox.innerHTML="<div class='mt-3 multi_form_control'>" + $("#History .multi_form_control").html() + "</div>" ;
        // alert("HTML: " + $("#History").html());
        document.getElementById("History").appendChild(txtNewInputBox);
    });

    $("#add_qualification").click(function(){
        var txtNewInputBox = document.createElement('div');
        txtNewInputBox.innerHTML="<div class='mt-3'>" + $("#qualification .single_qualification").html() + "</div>" ;
        document.getElementById("qualification").appendChild(txtNewInputBox);
    });

    $("#add_location").click(function(){
        var txtNewInputBox = document.createElement('div');
        txtNewInputBox.innerHTML="<div class='mt-3 multi_form_control'>" + $("#location .multi_form_control").html() + "</div>" ;
        document.getElementById("location").appendChild(txtNewInputBox);
    });

    $("#purchase_btn").click(function(){
       $('#Searchcandidate').modal('hide');
       setTimeout(function(){
        $('body').addClass('modal-open');
       },1000);    
    });
});



function closeOverlay()
{
    var hidden = $('.sideoff-off');
    var hidden1 = $('.overlay');
    hidden.animate({"right": "-1000px"}, 500).removeClass('visible');
    hidden1.fadeOut(500);
    // $( "body" ).removeClass( "noscroll" );
}

// navbar fixed on top
// jQuery to collapse the navbar on scroll
function collapseNavbar() {
    var nav = $('.navbar');
    if (nav.length) {
        var contentNav = nav.offset().top;
        if (contentNav > 50) {
            $(".fixed-top").addClass("top-nav-collapse");
        } else {
            $(".fixed-top").removeClass("top-nav-collapse");
        }
    }
}
$(window).scroll(collapseNavbar);
$(document).ready(collapseNavbar);

//faq toggle stuff 
$(function () {
  $("dd").slideUp(1);
  $("dt").click(function () {
    var $this = $(this),$parent = $this.parent(),outer = true;
    if ($this.is('.faq-toggle')) {$parent = $parent.parent();outer = false;}
    if ($parent.hasClass('active')) {
      $parent.removeClass('active').find('dd').slideUp(500);
    } else {
      $parent.siblings().removeClass('active').find('dd').slideUp(500);
      $parent.addClass('active').find('dd').slideDown(500);
    }
    return outer;
  });
});

/* tooltip */
 $(function () {
  $('[data-toggle="tooltip"]').tooltip()
})

$.ajaxSetup({
headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
}
});

function showLoader($show){
    // if($show){
    //   $('#preloader').show();
    //   $('#preloader').css('opacity',1);
    // }else{
    //   $('#preloader').hide();
    //   $('#preloader').css('opacity',0);
    // }
    if($show){
      $('#preloader_new').show();
      $('#preloader_new').css('opacity',1);
    }else{
      $('#preloader_new').hide();
      $('#preloader_new').css('opacity',0);
    }

}

function showLoaderFull($show){
    if($show){
      $('#preloader').show();
      $('#preloader').css('opacity',1);
    }else{
      $('#preloader').hide();
      $('#preloader').css('opacity',0);
    }
}

function showMessage($msg,$type){
    if($type){
      $('.custom_error_msg').text('');
      $('.custom_success_msg').text($msg);
    }else{
       $('.custom_error_msg').text($msg);
       $('.custom_success_msg').text('');
    }
}
