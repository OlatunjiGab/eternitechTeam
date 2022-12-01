<!-- REQUIRED JS SCRIPTS -->

<!-- jQuery 2.1.4 -->
<script src="{{ asset('la-assets/plugins/jQuery/jQuery-2.1.4.min.js') }}"></script>
<!-- Bootstrap 3.3.2 JS -->
<script src="{{ asset('la-assets/js/bootstrap.min.js') }}" type="text/javascript"></script>

<!-- jquery.validate + select2 -->
<script src="{{ asset('la-assets/plugins/jquery-validation/jquery.validate.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('la-assets/plugins/select2/select2.full.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('la-assets/plugins/bootstrap-datetimepicker/moment.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('la-assets/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.js') }}" type="text/javascript"></script>

<!-- AdminLTE App -->
<script src="{{ asset('la-assets/js/app.min.js') }}" type="text/javascript"></script>

<script src="{{ asset('la-assets/plugins/stickytabs/jquery.stickytabs.js') }}" type="text/javascript"></script>
<script src="{{ asset('la-assets/plugins/slimScroll/jquery.slimscroll.min.js') }}" type="text/javascript"></script>
<!-- bootstrap-wysihtml5 editor -->
<script type="text/javascript" src="{{ asset('la-assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') }}"></script>

<script src="{{ asset('la-assets/js/custom.js') }}" type="text/javascript"></script>

<script src="{{ asset('la-assets/js/intlTelInput.js') }}"></script>
<script>
        let rates = document.getElementsByName('phone');
        $(rates).intlTelInput({
            nationalMode: false
        });

        let mobileInput = document.getElementsByName('mobile');
        $(mobileInput).intlTelInput({
            nationalMode: false
        });

        let mobile2Input = document.getElementsByName('mobile2');
        $(mobile2Input).intlTelInput({
            nationalMode: false
        });
        $(function () {
            $('.timeline-country-popover').popover({
                container: 'body',
                trigger: 'hover',
                placement: 'bottom',
                html: true
            });
            $('.timeline-content-popover').popover({
                container: 'body',
                trigger: 'hover',
                placement: 'top',
                html: true
            });
            $('.question-popover').popover({
                container: 'body',
                trigger: 'hover',
                placement: 'right',
                html: true
            });
            $("form").attr("autocomplete", "new-password");
            $("input").attr("autocomplete", "new-password");
        });
        $(".disable-feature-popup").click(function () {
            $('#disable-feature-popup').modal('show');
        });
</script>
@if(Entrust::hasRole("PARTNER"))
<!--Start of Tawk.to Script-->
<script type="text/javascript">
        var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
        (function(){
                var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
                s1.async=true;
                s1.src='https://embed.tawk.to/5b3a67434af8e57442dc44a1/default';
                s1.charset='UTF-8';
                s1.setAttribute('crossorigin','*');
                s0.parentNode.insertBefore(s1,s0);
        })();
</script>
<!--End of Tawk.to Script-->
@endif
@section('footer_scripts')
@endsection
<!-- Optionally, you can add Slimscroll and FastClick plugins.
      Both of these plugins are recommended to enhance the
      user experience. Slimscroll is required when using the
      fixed layout. -->

@stack('scripts')