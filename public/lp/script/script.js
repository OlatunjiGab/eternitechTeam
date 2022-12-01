(function () {
    "use strict";

    //   Animate on scroll
    AOS.init({once: true, duration: 1000});

    jQuery(document).ready(function () {
        "use strict";

        $('.popup-form-btn').click(function () {
            openLeadCollectForm();
        });

        //   Testimonial Carousel
        $(".owl-carousel").owlCarousel({
            loop: true,
            center: true,
            items: 3,
            margin: 0,
            autoplay: true,
            dots: true,
            autoplayTimeout: 8500,
            smartSpeed: 450,
            responsive: {
                0: {
                    items: 1,
                },
                768: {
                    items: 2,
                },
                1170: {
                    items: 3,
                },
            },
        });
    });
})();
