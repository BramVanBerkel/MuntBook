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

// Header Scrolling Set White Background
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

// Window resize setting
$(window).resize(function(){
    if($(window).width() > 991) {
        $('.welcome-area').css('height', $(window).height() - 80);
    }else{
        $('.welcome-area').css('height', 'auto');
    }
});
