/*!

 * One Page - WordPress Theme (http://startbootstrap.com)

 * Code licensed under the Apache License v2.0.

 * For details, see http://www.apache.org/licenses/LICENSE-2.0.

 */

var mySlider;

jQuery(document).ready(function ($) {



    $('body').css('padding-top', function () {

        if ($(window).width() > 991) {

            return $('.header').outerHeight();

        } else {

            return '0';

        }

    });



    if ($("#wpadminbar").length) {

        if ($(window).width() < 765) {

            stickyTop = 46;

        } else {

            stickyTop = 32;

        }

    }

    else {

        stickyTop = 0;

    }



    $('.header').css({'top': stickyTop});

    function next_slide(){
      mySlider.next();
    }

    //Menu-top bar show/hide animation

    $('.top_strip').addClass('top_strip_visible');

    $('.toggle_strip').click(function () {

        var target = $('#home');

        if (target.is(':visible')) {

            target.slideUp('slow', function () {

                $('.top_strip').removeClass('top_strip_visible');

                $('body').animate({

                    'padding-top': $('.header').outerHeight()

                }, 1000);

                $('.toggle_strip').animate({

                    top: '17px'

                }, 1000);



            });

        } else {

            target.slideDown('slow', function () {

                $('.top_strip').addClass('top_strip_visible');

                $('body').animate({

                    'padding-top': $('.header').outerHeight()

                }, 1000);

                $('.toggle_strip').animate({

                    top: '55px'

                }, 1000);



            });

        }

    });



// Mean-menu 

    $('#menu .nav-menu').meanmenu({

        meanScreenWidth: "991"

    });

// Mean-menu 

    $('#menu_sub .nav-menu').meanmenu({

        meanScreenWidth: "991"

    });



    if ($(window).width() < 991) {

        $('.mean-nav #onepage_menu').onePageNav();

    } else {

        $('#onepage_menu').onePageNav();

    }

    $(window).resize(On_Resize);



    $('ul.sf-menu').superfish();



    //Highlight the top nav as scrolling occurs

    $('body').scrollspy({

        target: '.main-menu'

    })

//Closes the Responsive Menu on Menu Item Click

    $('.navbar-collapse ul li a').click(function () {

        $('.navbar-toggle:visible').click();

    });



    /*  Scroll to top

     /* ------------------------------------ */

    $("a[href='#page-top']").click(function () {

        $("html, body").animate({scrollTop: 0}, "slow");

//        $("html, body").animate({scrollTop: 0}, {duration: 1000});

        return false;

    });



// Slit Slider Init
setInterval(next_slide, 5000);
    $(function () {

        var Page = (function () {

            var $navArrows = $('#nav-arrows'),

                    $nav = $('#nav-dots > span'),

                    slitslider = $('#slider').slitslider({

                onBeforeChange: function (slide, pos) {

                    $nav.removeClass('nav-dot-current');

                    $nav.eq(pos).addClass('nav-dot-current');

                }

            }),

                    init = function () {

                        initEvents();

                    },

                    initEvents = function () {

                        // add navigation events
                        mySlider = slitslider;
                        $navArrows.children(':last').on('click', function () {

                            slitslider.next();

                            return false;

                        });

                        $navArrows.children(':first').on('click', function () {

                            slitslider.previous();

                            return false;

                        });

                        $nav.each(function (i) {

                            $(this).on('click', function (event) {

                                var $dot = $(this);

                                if (!slitslider.isActive()) {

                                    $nav.removeClass('nav-dot-current');

                                    $dot.addClass('nav-dot-current');

                                }

                                slitslider.jump(i + 1);

                                return false;

                            });

                        });

                    };

            return {init: init};

        })();

        Page.init();

    });

// Tesimonial Init

    $('.testimonials-slider').bxSlider({

        slideWidth: 0,

        minSlides: 1,

        maxSlides: 1,

        slideMargin: 32,

        pause: 4000,

        auto: false,

        pager: true,

        controls: false,

        autoControls: false

    });



});



function On_Resize() {

    $('body').css('padding-top', function () {

        if ($(window).width() > 991) {

            return $('.header').outerHeight();

        } else {

            return '0';

        }

    });

    if ($("#wpadminbar").length) {

        if ($(window).width() < 765) {

            stickyTop = 46;

        } else {

            stickyTop = 32;

        }

    }

    else {

        stickyTop = 0;

    }



    $('.header').css({'top': stickyTop});



    if ($(window).width() < 991) {

        $('.mean-nav #onepage_menu').onePageNav();

    } else {

        $('#onepage_menu').onePageNav();

    }

}





//Eliment Animation

jQuery(document).ready(function ($) {

    var animated_element = $('.animated');

    function image_animation() {

        animated_element.each(function () {

            var get_offset = $(this).offset();

            if ($(window).scrollTop() + $(window).height() > get_offset.top + $(this).height() / 2) {

                $(this).addClass('animation_started');

                // var el = $(this).find('.animated');

                setTimeout(function () {

                    $(this).removeClass('animated').removeAttr('style');

                }, 300);

            }

        });



    }

    $(window).scroll(function () {

        image_animation();

    });

    $(window).load(function () {

        setInterval(image_animation, 300);

    });

});





// FrontPage gallery tab action & animation

jQuery(document).ready(function ($) {

    $('#filters li').click(function () {

        var list = $(this);

        var id = list.attr("id");

        // if we click the active tab, do nothing

        if (!list.hasClass("active")) {

            $('#filters li.active').removeClass("active");

            list.addClass("active"); // set the active tab

            var active_list = 'li.' + id;

            $('.main li').each(function () {

                if ($(this).hasClass(id)) {

                    $(this).fadeOut('fast').fadeIn('slow');

                } else {

                    $(this).appendTo('.partial_gallery_list');

                }

            });

            $('.partial_gallery_list li').each(function () {

                if ($(this).hasClass(id)) {

                    $(this).fadeOut('fast', function () {

                        $(this).appendTo('.main');

                    }).fadeIn('slow');

                }

            });

        }

    });





//Masonry javascript for auto-adjust position of homepage-blogs



    $(window).resize(function_masonary);

});



function function_masonary() {

    if (jQuery(window).width() > 991) {

        $('.homepage_blogs').masonry({

            itemSelector: '.post_item_wrapper'



        });

        $('.homepage_blogs > div').each(function () {

            $(this).css('position', 'absolute');

        });

    } else {

        $('.homepage_blogs').removeAttr('data-masonry');

        $('.homepage_blogs > div').each(function () {

            $(this).css('position', 'static');

        });

    }

}

jQuery(window).load(function () {

    function_masonary();

    var headerHeight;

    jQuery('.current').removeClass('current');

    jQuery('#onepage_menu a[href$="' + window.location.hash + '"]').parent('li').addClass('current');

    if (jQuery(window).width() >= 751) {

        headerHeight = jQuery('.header').outerHeight();

    } else {

        headerHeight = 0;

    }

    if ($("#wpadminbar").length) {

        if ($(window).width() < 765) {

            stickyTop = 46;

        } else {

            stickyTop = 32;

        }

    }

    else {

        stickyTop = 0;

    }

    if (location.pathname.replace(/^\//, '') == window.location.pathname.replace(/^\//, '') && location.hostname == window.location.hostname) {

        var target = jQuery(window.location.hash);

        if (target.length) {

            jQuery('html,body').animate({

                scrollTop: target.offset().top - headerHeight + 10 - stickyTop

            }, {

                duration: 2000,

                easing: 'linear'

            });

            return false;

        }

    }

});



