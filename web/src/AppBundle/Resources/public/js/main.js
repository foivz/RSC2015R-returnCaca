$(document).ready(function () {



    //sticky footer
    $(window).bind("load", function () {

        var footerHeight = 0,
            footerTop = 0,
            $footer = $("#footer");

        positionFooter();

        function positionFooter() {

            footerHeight = $footer.height();
            footerTop = ($(window).scrollTop() + $(window).height() - footerHeight) + "px";

            if (($(document.body).height() + footerHeight) < $(window).height()) {
                $footer.css({position: "absolute"}).animate({top: footerTop}, 1, 'linear').css({opacity: 1})
            } else {
                $footer.css({position: "static", opacity: 1})
            }
        }

        $(window)
            .scroll(positionFooter)
            .resize(positionFooter)
    });

    //side navigation
    $('.navbar-toggle').click(function () {
        $('.navbar-nav').toggleClass('slide-in');
        $('.side-body').toggleClass('body-slide-in');
        $('#search').removeClass('in').addClass('collapse').slideUp(200);

        /// uncomment code for absolute positioning tweek see top comment in css
        //$('.absolute-wrapper').toggleClass('slide-in');

    });

    // Remove menu for searching
    $('#search-trigger').click(function () {
        $('.navbar-nav').removeClass('slide-in');
        $('.side-body').removeClass('body-slide-in');

        /// uncomment code for absolute positioning tweek see top comment in css
        //$('.absolute-wrapper').removeClass('slide-in');

    });


});