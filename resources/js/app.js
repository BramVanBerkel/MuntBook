require('./bootstrap');
require('particles.js');
require('chart.js');

// Page loading animation
$(window).on('load', function () {
    let $menuTrigger = $('.menu-trigger');

    if ($menuTrigger.length) {
        $menuTrigger.on('click', function () {
            $(this).toggleClass('active');
            $('.header-area .nav').slideToggle(200);
        });
    }

    particlesJS.load('welcome', '/js/particleSettings.json');
});

// Header scrolling set white background
$(window).on('scroll', function () {
    var width = $(window).width();
    if (width > 991) {
        var scroll = $(window).scrollTop();
        if (scroll >= 30) {
            $(".header-area").addClass("header-sticky");
            $(".header-area .dark-logo").css('display', 'block');
            $(".header-area .light-logo").css('display', 'none');
        } else {
            $(".header-area").removeClass("header-sticky");
            $(".header-area .dark-logo").css('display', 'none');
            $(".header-area .light-logo").css('display', 'block');
        }
    }
});

$('.sub-menu').on('click', function(el) {
    if($(window).width() < 992) {
        // Close other open menus, except current and toggle active state of current
        $('.sub-menu ul').not($(this).find('ul')).removeClass('active');
        $(this).find('ul').toggleClass('active');
    }
});

// Window resize setting
$(window).resize(function(){
    if($(window).width() > 991) {
        $('.welcome-area').css('height', $(window).height() - 80);
    }else{
        $('.welcome-area').css('height', 'auto');
    }
});
