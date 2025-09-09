







(function ($) {
    function dataBackgroundImage() {
        $("[data-bgimg]").each(function () {
            var bgimgurl = $(this).data("bgimg");
            $(this).css({
                "background-image": "url(" + bgimgurl + ")",
                "background-size": "cover",
                "background-position": "center center"
            });
        });
    }

    $(window).on("load", function () {
        dataBackgroundImage();

        $(".slider_area").owlCarousel({ // fixed class name
            animateOut: "fadeOut",
            autoplay: true,
            loop: true,
            nav: true,
            autoplayTimeout: 5000,
            items: 1,
            dots: false,
            navText: [
                '<i class="fa fa-arrow-left"></i>',
                '<i class="fa fa-arrow-right"></i>'
            ]
        });
    });
})(jQuery);
