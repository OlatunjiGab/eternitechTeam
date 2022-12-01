@extends("la.layouts.app")

@section("contentheader_title")
	<a href="{{ url(config('laraadmin.adminRoute') . '/countries') }}">Countries</a> :
@endsection
@section("contentheader_description", "Create Country")
@section("section", "Countries")
@section("section_url", url(config('laraadmin.adminRoute') . '/countries'))
@section("sub_section", "create")

@push('styles')
<style>
.has-error .help-block {
	color: #f55753;
}
label#phone-error {
	position: absolute;
}
</style>
@endpush
@section("main-content")

@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="box">
	<div class="box-header"></div>
	<div class="box-body">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				{!! Form::open(['action' => 'LA\CountriesController@store', 'id' => 'country-add-form']) !!}
					{!! LAFormMaker::form($module) !!}

					{{--
					@la_input($module, 'name')
					@la_input($module, 'iso')
					@la_input($module, 'description')
					@la_input($module, 'flag')
					--}}

					<div class="form-group">
						{!! Form::submit( 'Submit', ['class'=>'btn btn-success']) !!} <button class="btn btn-default pull-right"><a href="{{ url(config('laraadmin.adminRoute') . '/countries') }}">Cancel</a></button>
					</div>
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@endsection
@push('scripts')
<script>
	$(function () {
		$("#country-add-form").validate({
		});
	});
</script>
@endpush
