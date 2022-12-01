@extends("la.layouts.app")

@section("contentheader_title", "Lead Listing Setting")
@section("contentheader_description", "Show/Hide Column")
@section("section", "Lead Listing Setting")
@section("sub_section", "Settings")
@section("htmlheader_title", "Lead Listing Setting")

@section("main-content")
<style>
</style>
@if(session()->has('success'))
    <p class="alert alert-success">{{ session()->get('success') }}</p>
@endif
<div class="row">
    <div class="col-sm-12 success-deletion" style="display: none">
        <div class="alert alert-success">
            Leads Listing Column setting updated...
        </div>
    </div>
</div>
    <div class="box box-success box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">Lead Listing Column Settings click checkbox to update</h3>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                        <div class="panel panel-default ">
                            <div class="panel-body">
                                @foreach($columnSettings as $setting)
                                <div class="form-group checkbox">
                                    <label>
                                        <input type="checkbox" class="checkbox-check" data-id="{{$setting['id']}}" name="key_{{$setting['key']}}" {{$setting['value'] ==1? 'checked=checked':''}} value="{{$setting['value']}}"> {{title_case(snake_case(camel_case($setting['key']), ' '))}}
                                    </label>
                                </div>
                                @endforeach
                                <div class="form-group">
                                    <button class="btn btn-default pull-right"><a href="{{ url(config('laraadmin.adminRoute') . '/projects') }}">Back</a></button>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function () {
            $(".checkbox-check").on('click',function() {
                $('.success-deletion').show(2000);
                var _token = $('meta[name="csrf-token"]').attr('content');
                var id = $(this).attr('data-id');
                var value = 0;

                if ($(this).is(':checked')) {
                    value = 1;
                }

                $.ajax({
                    type: "post",
                    url: "/admin/projects/column-setting-update",
                    data: {_token:_token,id:id,value:value},
                    success: function(result) {
                    }
                });
                setTimeout(function (){
                    $('.success-deletion').hide(1000);
                },5000);
            });
        });
    </script>
@endpush