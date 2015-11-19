$(document).ready(function () {


    $(window).bind("load", function () {

        var footerHeight = 0,
            footerTop = 0,
            $footer = $("#footer");

        positionFooter();

        function positionFooter() {

            footerHeight = $footer.height();
            footerTop = ($(window).scrollTop() + $(window).height() - footerHeight) + "px";

            if (($(document.body).height() + footerHeight) < $(window).height()) {
                $footer.css({position: "absolute"}).animate({top: footerTop},1,'linear').css({opacity: 1})
            } else {
                $footer.css({position: "static", opacity: 1})
            }
        }

        $(window)
            .scroll(positionFooter)
            .resize(positionFooter)
    });

});