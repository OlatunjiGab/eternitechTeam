(function () {
    "use strict";

    window.submitClientDetails = function (callback) {
        window.showFormLoader();
        window.check_projectempty((success) => {
            window.hideFormLoader();
            if (success) {
                callback();
            }
        });
    };

    window.submitProjectDetails = function (callback) {
        window.showFormLoader();
        window.check_projectempty((success) => {
            window.hideFormLoader();
            if (success) {
                callback();
            }
        }, false);
    };

    window.showFormLoader = function () {
        $("#form-loader").removeClass("hidden");
    };

    window.hideFormLoader = function () {
        $("#form-loader").addClass("hidden");
    };

    window.closeFormPopup = function () {
        $("#popup-form").trigger("reset");
        $("#popup-form-section-1").removeClass("hidden");
        $("#popup-form-section-2").addClass("hidden");
        $("#book-meeting-submit").addClass("hidden");
        $("#chat-sales-submit").addClass("hidden");
        $("#get-back-submit").addClass("hidden");
        $('#popup-lead-collect-form').css('display', 'none');
    };

    window.openLeadCollectForm = function () {
        window.showFormLoader();
        fetch('https://eterni.tech/lead-collect-form/index.php')
            .then(function (response) {
                return response.text();
            })
            .then(function (body) {
                document.querySelector('#popup-lead-collect-form').innerHTML = body;
                window.leadCollectFormLoaded();
                $('#popup-lead-collect-form').css('display', 'block');
                $("#project-phone").intlTelInput({
                    nationalMode: false
                });
                $('#eternitech-lp--popup').modal();

                $('#eternitech-lp--popup').on('hidden.bs.modal', function (e) {
                    window.closeFormPopup();
                })
                window.hideFormLoader();
            });
    };

    window.leadCollectFormLoaded = function () {
        // Popup Form 'Book a meeting' btn
        $("#book-meeting-btn").click(function () {
            event.preventDefault();
            window.submitClientDetails(function (){
                $("#popup-form-section-1").addClass("hidden");
                $("#popup-form-section-2").removeClass("hidden");
                $("#book-meeting-submit").removeClass("hidden");
            });
        });

        // Popup Form 'Chat with a sales rep.' btn
        $("#chat-sales-btn").click(function () {
            event.preventDefault();
            window.submitClientDetails(function (){
                $("#popup-form-section-1").addClass("hidden");
                $("#popup-form-section-2").removeClass("hidden");
                $("#chat-sales-submit").removeClass("hidden");
            });
        });

        // Popup Form 'Get back to me' btn
        $("#get-back-btn").click(function () {
            event.preventDefault();
            window.submitClientDetails(function (){
                $("#popup-form-section-1").addClass("hidden");
                $("#popup-form-section-2").removeClass("hidden");
                $("#get-back-submit").removeClass("hidden");
            });
        });

        // Popup Form 'Back' btn
        $("#popup-form-back").click(function () {
            event.preventDefault();
            $("#popup-form-section-1").removeClass("hidden");
            $("#popup-form-section-2").addClass("hidden");
            $("#book-meeting-submit").addClass("hidden");
            $("#chat-sales-submit").addClass("hidden");
            $("#get-back-submit").addClass("hidden");
        });

        // Popup close btn
        $("#popup-close").click(function () {
            window.closeFormPopup();
        });

        // Popup close btn
        $("#book-meeting-submit").click(function () {
            window.submitProjectDetails(function (){
                window.showFormLoader();
                window.location.href = window.crm.meetUrl;
            });
        });

        $("#chat-sales-submit").click(function () {
            window.submitProjectDetails(function (){
                window.showFormLoader();
                window.location.href = window.crm.chatUrl;
            });
        });

        $("#get-back-submit").click(function () {
            window.submitProjectDetails(function (){
                $("#popup-form-section-2").addClass("hidden");
                $("#popup-form-section-3").removeClass("hidden");
                $("#get-back-submit").addClass("hidden");
                $("#homepage-submit").removeClass("hidden");
            });
        });

        $("#homepage-submit").click(function () {
            window.showFormLoader();
            window.location.href = window.crm.homepageUrl;
        });
    };


    jQuery(document).ready(function () {
        "use strict";

        $('head').append('<link rel="preconnect" href="https://fonts.googleapis.com" />');
        $('head').append('<link\n' +
            '      href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Permanent+Marker&display=swap"\n' +
            '      rel="stylesheet"\n' +
            '    />');
        // $('head').append('<link\n' +
        //     '      rel="stylesheet"\n' +
        //     '      href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css"\n' +
        //     '      integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO"\n' +
        //     '      crossorigin="anonymous"\n' +
        //     '    />');
        $('head').append('<link\n' +
            '      rel="stylesheet"\n' +
            '      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css"\n' +
            '    />');
        $('head').append('<link rel="stylesheet" href="https://eterni.tech/lead-collect-form/form.css" />');
        // $('head').append('<link href="https://www.jquery-az.com/jquery/css/intlTelInput/intlTelInput.css" rel="stylesheet">');
        // $('head').append('<script src="https://www.jquery-az.com/jquery/js/intlTelInput/intlTelInput.js"></script>');

        // let countryListCss = document.createElement('link');
        // countryListCss.setAttribute('href','https://www.jquery-az.com/jquery/css/intlTelInput/intlTelInput.css');
        // countryListCss.setAttribute('rel','stylesheet');
        // document.head.appendChild(countryListCss);
        let countryListJs = document.createElement('script');
        countryListJs.setAttribute('src','https://eterni.tech/assets/js/ettracker.js');
        countryListJs.setAttribute('async',true);
        document.head.appendChild(countryListJs);

        $('#eternitech-lp--popup').on('hidden.bs.modal', function () {
            window.parent.$('#popup-form-iframe').css('display', 'none');
        })

    });
})();
