require('./bootstrap');

// Page loading animation
$(window).on('load', function(){
    $(".loading-wrapper").animate({
        'opacity': '0'
    }, 600, function(){
        setTimeout(function(){
            $(".loading-wrapper").css("visibility", "hidden").fadeOut();

            // Parallax init
            if($('.parallax').length){
                $('.parallax').parallax({
                    imageSrc: 'assets/images/parallax.jpg',
                    zIndex: '1'
                });
            }
        }, 300);
    });
});


// // Header Scrolling Set White Background
$(window).on('scroll', function(){
    var width = $(window).width();
    if(width > 991) {
        var scroll = $(window).scrollTop();
        if (scroll >= 30) {
            $(".header-area").addClass("header-sticky");
            $(".header-area .dark-logo").css('display', 'block');
            $(".header-area .light-logo").css('display', 'none');
        }else{
            $(".header-area").removeClass("header-sticky");
            $(".header-area .dark-logo").css('display', 'none');
            $(".header-area .light-logo").css('display', 'block');
        }
    }
});
