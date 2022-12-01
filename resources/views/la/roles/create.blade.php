@extends("la.layouts.app")

@section("contentheader_title")
	<a href="{{ url(config('laraadmin.adminRoute') . '/roles') }}">Roles</a> :
@endsection
@section("contentheader_description", "Create Role")
@section("section", "Roles")
@section("section_url", url(config('laraadmin.adminRoute') . '/roles'))
@section("sub_section", "create")

@push('styles')
<style>
.has-error .help-block {
	color: #f55753;
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
				{!! Form::open(['action' => 'LA\RolesController@store', 'id' => 'role-add-form']) !!}

					{!! LAFormMaker::input($module, 'name', null, null, "form-control text-uppercase", ["placeholder" => "Role Name in CAPITAL LETTERS with '_' to JOIN e.g. 'SUPER_ADMIN'"]) !!}
					{!! LAFormMaker::input($module, 'display_name') !!}
					{!! LAFormMaker::input($module, 'description') !!}
					{!! LAFormMaker::input($module, 'parent') !!}
					{!! LAFormMaker::input($module, 'dept') !!}

					<div class="form-group">
						{!! Form::submit( 'Submit', ['class'=>'btn btn-success']) !!} <button class="btn btn-default pull-right"><a href="{{ url(config('laraadmin.adminRoute') . '/roles') }}">Cancel</a></button>
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
		$("#role-add-form").validate({
		});
	});
</script>
@endpush
