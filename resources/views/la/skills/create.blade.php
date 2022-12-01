@extends("la.layouts.app")

@section("contentheader_title")
	<a href="{{ url(config('laraadmin.adminRoute') . '/skills') }}">Skills</a> :
@endsection
@section("contentheader_description", "Create Skill")
@section("section", "Skills")
@section("section_url", url(config('laraadmin.adminRoute') . '/skills'))
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
				{!! Form::open(['action' => 'LA\SkillsController@store', 'id' => 'skill-add-form']) !!}
					{{--@la_form($module)--}}

					{!! LAFormMaker::input($module, 'keyword','','required') !!}
					{!! LAFormMaker::input($module, 'description','','required') !!}
					{!! LAFormMaker::input($module, 'keywords') !!}
					{!! LAFormMaker::input($module, 'hourly_rate') !!}
					{!! LAFormMaker::input($module, 'reply_text') !!}
					{!! LAFormMaker::input($module, 'url') !!}
					{!! LAFormMaker::input($module, 'is_search_project') !!}
					{!! LAFormMaker::input($module, 'icon') !!}
					{!! LAFormMaker::input($module, 'isTechnology') !!}

					<div class="form-group">
						{!! Form::submit( 'Submit', ['class'=>'btn btn-success']) !!} <button class="btn btn-default pull-right"><a href="{{ url(config('laraadmin.adminRoute') . '/skills') }}">Cancel</a></button>
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
		$("#skill-add-form").validate({
		});
	});
</script>
@endpush
