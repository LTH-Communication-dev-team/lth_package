if (!("ontouchstart" in document.documentElement)) {
  document.documentElement.className += " no-touch";
} else {
  document.documentElement.className += " touch";
}

if (typeof imagePath == 'undefined') {
  var imagePath = "/responsive/images/";
}
if (typeof localSearchUrl == 'undefined') {
  var localSearchUrl = "http://www.lu.se/search/all?query=";
}

$("document").ready(function() {
  $(".news-item.center").each(function(i, e) {
    fixHeight($(this), "news-item");
  });
  $(".news-item.center-left").each(function(i, e) {
    fixHeight($(this), "news-item");
  });
  $(".news-item.center-right").each(function(i, e) {
    fixHeight($(this), "news-item");
  });

  $(".calendar-item.center").each(function(i, e) {
    fixHeight($(this), "calendar-item");
    fixHeight2($(this), "calendar-item");
  });
  $(".calendar-item.center-left").each(function(i, e) {
    fixHeight($(this), "calendar-item");
  });
  $(".calendar-item.center-right").each(function(i, e) {
    fixHeight($(this), "calendar-item");
    fixHeight2($(this), "calendar-item");
  });

  function fixHeight(div, class_compare) {
    var height = div.height();

    div.siblings().each(function(si, se) {
      if ($(this).hasClass(class_compare) && $(this).height() > height) {
        height = $(this).height();
      }
    });
    div.height(height);
  }

  function fixHeight2(div, class_compare) {
    var height = div.height();

    div.siblings().each(function(si, se) {
      if ($(this).hasClass(class_compare)) {
        $(this).height(height);
        $(" .calendar-date", this).css("position", "absolute");
      }
    });
    $(" .calendar-date", div).css("position", "absolute");
  }

  $(".tab-sidebar-toggle h2").click(function() {
    var next = $(this).next();
    if (next.is(":visible")) {
      $(this).css("background-image", "url(" + imagePath + "submenu-arrow-right-large.png)");
    } else {
      $(this).css("background-image", "url(" + imagePath + "submenu-arrow-down-large.png)");
    }
    next.slideToggle();
  });

  $('#lu_header_button').click(function() {
    var showOverlay = true;
    if ($("#lu_overlay").hasClass("hide")) {
      showOverlay = false;
      $("#lu_overlay").removeClass("hide");
      $("#lu_header_button").css("background-image", "url(" + imagePath + "tab-up.png)");
    } else {
      $("#lu_header_button").css("background-image", "url(" + imagePath + "tab-down.png)");
    }

    $('#lu_header_content').slideToggle('slow', function() {
      if (showOverlay) {
        $("#lu_overlay").addClass("hide");
      }
    });
  });

  $('#lu_overlay').click(function() {
    var showOverlay = true;
    $('#lu_header_content').slideToggle('slow', function() {
      $("#lu_overlay").addClass("hide");
    });
  });

  var main_title = $("#main_title span");
  var main_sub_title = $("#main_sub_title span");
  if (main_title.width() < main_sub_title.width()) {
    main_title.width(main_sub_title.width());
  }

  var shortcuts_width = $("#shortcuts").width();
  $("#shortcuts").width(shortcuts_width + 5);

  var color = "white";
  if ($("#page_wrapper").hasClass("department")) {
    color = "bronze";
  }

  $(".touch #shortcuts").click(function() {
    if ($(this).hasClass("shortcuts_hover")) {
      $("#shortcuts_icon").css("background-image", "url(" + imagePath + "shortcuts-down-" + color + ".png)");
      $(this).removeClass("shortcuts_hover");
    } else {
      $("#shortcuts_icon").css("background-image", "url(" + imagePath + "shortcuts-up-" + color + ".png)");
      $(this).addClass("shortcuts_hover");
    }
  });

  $("#searchsiteform").submit(function(event) {
    event.preventDefault();
    var query = $("#searchsite").val();
    window.location = localSearchUrl + query;
  });

  $(".slider-control-playpause").click(function() {
    if ($(this).hasClass("slider-control-paused")) {
      $(this).removeClass("slider-control-paused").addClass("slider-control-resumed");
      $('.cycle-slideshow').cycle('next');
      $('.cycle-slideshow').cycle('resume');
    } else if ($(this).hasClass("slider-control-resumed")) {
      $(this).removeClass("slider-control-resumed").addClass("slider-control-paused");
      $('.cycle-slideshow').cycle('pause');
    }
  });

});

/*
 ********************************
 ********************************
 Responsive js stuff below
 ********************************
 ********************************
 */

$('document').ready(function() {

     /*
     * Custom function to make responsive menu from sitemap
     */
    var baseHref = window.location.protocol + '//' + window.location.host;
    var pathname = window.location.pathname;
    var url = window.location.href;
    
    var hbRoot = $('body').attr('data-hbroot');
    var extraPath='';
    if(hbRoot) {
        extraPath = '/index.php?id=' + hbRoot + '&type=200';
    }
    
    if(baseHref) {
      if(!extraPath) {
          var extraPath = '';
          if(pathname.indexOf('/english') === 0) {
                extraPath = '/english';
          } else if(pathname.indexOf('/swedish') === 0) {
                extraPath = '/swedish';
          } else if(pathname.indexOf('/svenska') === 0) {
                extraPath = '/svenska';
          }
          extraPath += '?type=200';
        }

        $.get(baseHref + extraPath.replace('//g', '/'), function( data ) {
            if(data) {
                $('#responsive_menu').after(data);
                var pathName = url.substring(1);
                if(!$('a[href="' + pathName + '"]').parent().length === 0) {
                    $('a[href="' + pathName + '"]').parent().addClass('selected');
                } else {
                    $('.menu-level-1 li:first').addClass('selected');
                }
                
                //Add home-link to hbroot-page
                if(hbRoot) {
                    $('.menu-level-1 li:first').after("<li><a href=\"" + baseHref + "/index.php?id=" + hbRoot + "\">Home</a></li>");
                }
                
                //Add shortqut to hamburger menu BEGIN
                var tmpContent = '';
                $('#header_nav li').each(function(){
                    tmpContent += '<li>'+$(this).html()+'</li>';
                });

                if(tmpContent!='') {
                    var shortcutHeader = 'Genvägar';
                    if($('html').attr('lang') === 'en') {
                        shortcutHeader = 'Shortcuts';
                    }
                    $('#responsive_navigation > ul').append('<li class="responsive-shortcuts has_sub"><a href="#">' + shortcutHeader + '</a><a class="responsive_link expand"></a><ul class="menu-level-2">'+tmpContent+'</ul></li>')
                }
                //Add shortqut to hamburger menu END
                
                createOnClick();
            }
        });
    }
    
    //Listen to resize event
    window.onresize = function(event) {
        checkViewport();
    };

    //Do changes based on viewport width
    function checkViewport() {
        var screenWidth = window.screen.width;
        var browserWidth = $(window).width();
        var width = screenWidth;
        if (browserWidth < screenWidth) {
          width = browserWidth;
        }
        if (width >= 641) {
          $('#viewport').attr('content', 'width=1100');
          $('.cycle-slideshow').cycle('resume');
        }
        else if (width <= 640) {
          $('#viewport').attr('content', 'width=device-width, initial-scale=1');
          $('.cycle-slideshow').cycle('pause');

          //override line 94-98 in main.js
          $("#main_title span").width("auto");
        }
    }
    checkViewport();

  //Listen to scroll event
  $(window).scroll(function() {
    showScroll();
  });

  //Show 'Scroll to top'-button if user has scrolled a bit
  function showScroll() {
    var scroll = $(window).scrollTop();
    var height = $(window).height();

    if (scroll > height) {
      $('#scroll_to_top').show();
    } else {
      $('#scroll_to_top').hide();
    }
  }
  showScroll();

function createOnClick()
{
  $("#scroll_to_top").click(function() {
    $('html, body').animate({scrollTop: 0}, 'slow');
    return false;
  });

  //Expand - minimize the menu options
  $('#responsive_navigation .has_sub .responsive_link').click(function() {
    $(this).parent('.has_sub').toggleClass('expanded');
    $(this).siblings('ul').slideToggle(150);
    if ($(this).hasClass('expand')) {
      $(this).removeClass('expand').addClass('minimize');
    } else {
      $(this).removeClass('minimize').addClass('expand');
    }
  });

  //Expand menu options to the selected level on page load
  $('#responsive_navigation li.selected').each(function() {
    $(this).parents('#responsive_navigation ul').show();
    $(this).parents('.has_sub').addClass('expanded');
    $(this).children('ul').first().show();
    $(this).children('.responsive_link').removeClass('expand').addClass('minimize');
    $(this).parents('ul').siblings('.responsive_link').removeClass('expand').addClass('minimize');
  });

  //Open the responsive navigation
  $('#hamburger-icon').click(function() {
    $('#responsive_navigation_wrapper').show();

    var offset = $('#responsive_navigation li.selected').offset().top;
    var windowHeight = $(window).height();
    if (offset > windowHeight) {
      var scroll = offset - (windowHeight / 2);
      //$('html, body').scrollTop(scroll);
      $('html, body').animate({scrollTop: scroll}, 'slow');
    }

    $('#page_wrapper').hide();
  });

  //Close the responsive navigation
  $('#close_responsive_navigation').click(function() {
    $('#page_wrapper').show();
    $('#responsive_navigation_wrapper').hide();
  });
}

  //Expand - minimize the footer menu options
  $('#footer_content .responsive_footer').click(function() {
    $(this).parents("h3").siblings('ul').slideToggle(150);
    if ($(this).hasClass('expand')) {
      $(this).removeClass('expand').addClass('minimize');
    } else {
      $(this).removeClass('minimize').addClass('expand');
    }
  });

  //Clone byline and make the clone visible on small screens
  $("#content").after($("#byline").clone().addClass("show-xs"));
  $("#byline").addClass("hide-xs");

  //Clone news-top images to after the date and make the clone visible on small screens
  $(".news-top").each(function() {
    var images = $(this).children(".news-top-left").children(".news-top-image");
    var clone = images.clone();
    $(" .news-top-right .news-date-category", $(this)).after(clone.addClass("show-xs"));
    images.addClass("hide-xs");
  });

  //Clone slideshow images to within the link and make the clone visible on small screens
  $(".top-promo").each(function() {
    var images = $(this).children("img");
    var clone = images.clone();
    $(".top-promo-overlay a .text-wrapper", $(this)).before(clone.addClass("show-xs"));
    images.addClass("hide-xs");
  });

  //Clone "this page in english" and remove share_buttons and flag and make it visible in responsive
  $("#text_content_main").each(function() {
    var images = $(this).children("#share_wrapper");

    var clone = images.clone();
    clone.children("#share_buttons").addClass("hide-xs");
    $("#page_title", $(this)).after(clone.addClass("show-xs").removeClass("hide-xs"));
    $("#switch_language .lang .flag", $(this)).addClass("hide-xs");
  });

  //Clone social media icons and make the clone visible on small screens
  $("#footer_logo_information_wrapper").after($("#footer_social_media").clone().addClass("show-xs"));
  $("#footer_social_media").addClass("hide-xs");

  //Show searchbox
  var searchBgColor = $("#responsive-search").css("background-color");
  $("#responsive-search a").click(function() {
    $("#responsive_search_wrapper").toggle();
    if ($("#responsive_search_wrapper").is(":visible")) {
      $("#responsive-search").css("background", "#FFF");
    } else {
      $("#responsive-search").css("background", searchBgColor);
    }
  });

  //hide swipe icon in slideshow
  $(".cycle-slideshow").bind("touchmove", function() {
    $(".top-promo-swipe-overlay", $(this)).fadeOut(1500);
  });

  //hide swipe icon in responsive tables
  $(".table-responsive").bind("scroll", function() {
    $(".swipe-icon", $(this)).fadeOut(1500);
  });

  // Filter
  // see if there is any filter buttons previously
  if ($(".filter-button-wrapper").length !== 0) {
      $(".tab-sidebar-info.tab-sidebar-toggle").addClass("hide-xs");
  }
  if ($(".filter-button-wrapper").length !== 0) {
    //Filter button
    $(".filter-button").click(function() {
        $("#tab-content-wrapper").addClass("hide-xs");
        $(".tab-sidebar-info.tab-sidebar-toggle").removeClass("hide-xs");
      $(".filter-button-wrapper").addClass("hide-xs");
      $(".filter-title").removeClass("hide-xs");
      $(".filter-back-button-wrapper").removeClass("hide-xs");
    });
    //Back button
    $(".filter-back-button").click(function() {
        $("#tab-content-wrapper").removeClass("hide-xs");
      $(".tab-sidebar-info.tab-sidebar-toggle").addClass("hide-xs");
      $(".filter-button-wrapper").removeClass("hide-xs");
    });
  }
});