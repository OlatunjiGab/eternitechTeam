@extends("la.layouts.app")

@section("contentheader_title", "Go Premium")
@section("section", "Go Premium")
@section("htmlheader_title", "Go Premium")

@section("main-content")

    @if(session()->has('success'))
        <p class="alert alert-success">{{ session()->get('success') }}</p>
    @endif
    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-10">

    <div class="box box-primary">
        <div class="box-body">
            <div class="box box-solid">
                <div class="box-header with-border text-center bg-primary">
                    <h3 class="text-bold text-white"> Pros of Becoming Premium Partner <svg width="20" fill="#fff" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M64 192c27.25 0 51.75-11.5 69.25-29.75c15 54 64 93.75 122.8 93.75c70.75 0 127.1-57.25 127.1-128s-57.25-128-127.1-128c-50.38 0-93.63 29.38-114.5 71.75C124.1 47.75 96 32 64 32c0 33.37 17.12 62.75 43.13 80C81.13 129.3 64 158.6 64 192zM208 96h95.1C321.7 96 336 110.3 336 128h-160C176 110.3 190.3 96 208 96zM337.8 306.9L256 416L174.2 306.9C93.36 321.6 32 392.2 32 477.3c0 19.14 15.52 34.67 34.66 34.67H445.3c19.14 0 34.66-15.52 34.66-34.67C480 392.2 418.6 321.6 337.8 306.9z"/></svg></h3>
                </div>
                <div class="box-body text-center">
                    <blockquote style="border-left:none;">
                        <p class="text-bold h3">Avail Access to Full Features will have following</p>
                        <p><i class="fa fa-comments text-primary"> </i> Send Free SMS</p>
                        <p><i class="fa fa-envelope text-primary"> </i> Send Direct Emails</p>
                        <p><i class="fa fa-navicon text-primary"> </i> Get Access to All Partners</p>
                        <p><i class="fa fa-area-chart text-primary"> </i> Get Access to Unlimited Leads</p>
                        <p><i class="fa fa-cube text-primary"> </i> Build Free Pop Ups On Your Website</p>
                        <p><i class="fa fa-user text-primary"> </i> Track Pop Up Visitors</p>
                    </blockquote>
                    <div class="text-center">
                    <button type="button" class="btn btn-primary btn-interested"> <i class="fa fa-hand-o-right"></i>  INTERESTED!  <i class="fa fa-hand-o-left"></i> </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
        </div>
        <div class="col-md-1"></div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function () {
            $(".btn-interested").click(function () {
                $.ajax({
                    url: "{{ url(config('laraadmin.adminRoute') . '/send-interested-email') }}",
                    type: "post",
                    data: { featureName: "Interested for Premium Features", _token : $('meta[name="csrf-token"]').attr('content') },
                    success: function(response) {
                        if(response.status){
                            location.reload();
                        }
                    }
                });
                $('#show-coming-soon-popup').modal('hide');
            });
        });
    </script>
@endpush