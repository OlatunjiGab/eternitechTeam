@extends("la.layouts.app")

@section("contentheader_title")
	<a href="{{ url(config('laraadmin.adminRoute') . '/experts') }}">Expert</a> :
@endsection
@section("contentheader_description", $expert->$view_col)
@section("section", "Experts")
@section("section_url", url(config('laraadmin.adminRoute') . '/experts'))
@section("sub_section", "Edit")

@section("htmlheader_title", "Experts Edit : ".$expert->$view_col)

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
				{!! Form::model($expert, ['route' => [config('laraadmin.adminRoute') . '.experts.update', $expert->id ], 'method'=>'PUT', 'id' => 'expert-edit-form', 'enctype'=>'multipart/form-data']) !!}
					{{--@la_form($module)--}}
										
					{!! LAFormMaker::input($module, 'first_name') !!}
					{!! LAFormMaker::input($module, 'last_name') !!}
					{!! LAFormMaker::input($module, 'country','','required') !!}
					{!! LAFormMaker::input($module, 'email') !!}
					{!! LAFormMaker::input($module, 'phone','','','form-control',['oninput'=>"this.value = this.value.replace(/[^0-9+]/g, '').replace(/(\..*)\./g, '$1');",'maxlength'=>'14','minlength'=>'10']) !!}
					{{--{!! LAFormMaker::input($module, 'regions') !!}--}}

					{{--{!! LAFormMaker::input($module, 'partner') !!}--}}
					@if(Entrust::hasRole("SUPER_ADMIN"))
					<div class="form-group">
						<label for="partner">Partner* :</label>
						<select class="form-control" data-placeholder="Select Partner" rel="select2" name="partner" required>
							<option value="">Select Partner</option>
							@foreach($aSupplier as $key=>$supplier)
								<option value="{{$key}}" {{ $key==$expert->partner?'selected=selected':'' }}>{{$supplier}}</option>
							@endforeach
						</select>
					</div>
					@endif
					{{--{!! LAFormMaker::input($module, 'skills') !!}--}}
					<div class="form-group">
						<label for="skills">Skills* :</label>
						<select class="form-control valid" required="1" data-placeholder="Select Multiple Skills" multiple="" rel="select2" name="skills[]">
							@foreach($aSkill as $key=>$skill)
								<option value="{{$key}}" {{ in_array($key,$expert->skills_id)?'selected=selected':'' }}>{{$skill}}</option>
							@endforeach
						</select>
					</div>
					@if(Entrust::hasRole("SUPER_ADMIN"))
					{!! LAFormMaker::input($module, 'url_slug') !!}
					@endif
					{!! LAFormMaker::input($module, 'headline') !!}
					{!! LAFormMaker::input($module, 'description','','required') !!}
					{!! LAFormMaker::input($module, 'experience_start') !!}
					{!! LAFormMaker::input($module, 'experience',null,null,"editor form-control") !!}
					{!! LAFormMaker::input($module, 'monthly_rate') !!}
					{!! LAFormMaker::input($module, 'hourly_rate') !!}

					<div class="form-group">
						<label for="email">Youtube Embed :</label>
						<input class="form-control" placeholder="Enter Youtube Embed Link" data-rule-maxlength="256" data-rule-url="true" name="youtube_embed" type="text" value="{{$expert->youtube_embed}}">
					</div>
					@if(Entrust::hasRole("SUPER_ADMIN"))
					{!! LAFormMaker::input($module, 'original_cv_file') !!}
					@else
						<div class="form-group">
							<label for="original_cv_file">Cv Uploads :</label>
							<input class="form-control" placeholder="Select CV file" name="original_cv_file" type="file" accept="application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" >
						</div>
					@endif
					{!! LAFormMaker::input($module, 'publish_type') !!}
					<div class="form-group mt-5">
						@if($expert->image_url)
							<div>
								<label for="image">
									<img src="{{$expert->image_url }}" height="auto" width="50%" />
								</label>
							</div>
						@endif
						<label for="profile">Profile :</label>
						<input class="form-control" placeholder="Select Profile Image" name="profile" type="file" accept="image/*" >
					</div>
                    <br>
					<div class="form-group">
						{!! Form::submit( 'Update', ['class'=>'btn btn-success']) !!} <button class="btn btn-default pull-right"><a href="{{ url(config('laraadmin.adminRoute') . '/experts') }}">Cancel</a></button>
					</div>
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@endsection

@push('styles')
<style>
label#phone-error {
	position: absolute;
}
@media (max-width: 920px) {
	.iti-mobile .intl-tel-input.iti-container {
		position: fixed !important;
	}
}
</style>
@endpush
@push('scripts')
<script>
$(function () {
	$("#expert-edit-form").validate({
		
	});
});
</script>
@endpush
