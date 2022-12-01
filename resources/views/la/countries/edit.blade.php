@extends("la.layouts.app")

@section("contentheader_title")
	<a href="{{ url(config('laraadmin.adminRoute') . '/countries') }}">Country</a> :
@endsection
@section("contentheader_description", $country->$view_col)
@section("section", "Countries")
@section("section_url", url(config('laraadmin.adminRoute') . '/countries'))
@section("sub_section", "Edit")

@section("htmlheader_title", "Countries Edit : ".$country->$view_col)

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
	<div class="box-header">
		
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				{!! Form::model($country, ['route' => [config('laraadmin.adminRoute') . '.countries.update', $country->id ], 'method'=>'PUT', 'id' => 'country-edit-form']) !!}
					{!! LAFormMaker::form($module) !!}
					
					{{--
					@la_input($module, 'name')
					@la_input($module, 'iso')
					@la_input($module, 'description')
					@la_input($module, 'flag')
					--}}
                    <br>
					<div class="form-group">
						{!! Form::submit( 'Update', ['class'=>'btn btn-success']) !!} <button class="btn btn-default pull-right"><a href="{{ url(config('laraadmin.adminRoute') . '/countries') }}">Cancel</a></button>
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
	$("#country-edit-form").validate({
		
	});
});
</script>
@endpush
