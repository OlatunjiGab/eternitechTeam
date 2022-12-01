@extends("la.layouts.app")

@section("contentheader_title")
	<a href="{{ url(config('laraadmin.adminRoute') . '/experts') }}">Experts</a> :
@endsection
@section("contentheader_description", "Create Expert")
@section("section", "Experts")
@section("section_url", url(config('laraadmin.adminRoute') . '/experts'))
@section("sub_section", "create")

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
				{!! Form::open(['action' => 'LA\ExpertsController@store', 'id' => 'expert-add-form', 'enctype'=>'multipart/form-data']) !!}
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
							<select class="form-control" data-placeholder="Select Partner" placeholder="Select Partner" rel="select2" name="partner" required>
								<option value="">Select Partner</option>
								@foreach($aSupplier as $key=>$supplier)
									<option value="{{$key}}">{{$supplier}}</option>
								@endforeach
							</select>
						</div>
					@else
						<input type="hidden" name="partner" value="{{Auth::user()->supplier_id}}">
					@endif
					{!! LAFormMaker::input($module, 'skills') !!}
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
						<input class="form-control" placeholder="Enter Youtube Embed Link" data-rule-maxlength="256" data-rule-url="true" name="youtube_embed" type="text">
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
						<label for="profile">Profile :</label>
						<input class="form-control" placeholder="Select Profile Image" name="profile" type="file" accept="image/*" >
					</div>

					@if(!Entrust::hasRole('SUPER_ADMIN'))
					<br>
					<div class="form-group">
						<input type="checkbox" name="is_agree" required> By clicking here, you agree to share the CV on any of our platform publicly.
					</div>
					@endif

					<div class="form-group">
						{!! Form::submit( 'Submit', ['class'=>'btn btn-success']) !!} <button class="btn btn-default pull-right"><a href="{{ url(config('laraadmin.adminRoute') . '/experts') }}">Cancel</a></button>
					</div>
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@endsection
@push('styles')
	<link rel="stylesheet" type="text/css" href="{{ asset('la-assets/plugins/datatables/datatables.min.css') }}"/>
	<style>
		.has-error .help-block {
			color: #f55753;
		}
		.select2-search__field{
			width: 100% !important;
		}
		label#phone-error {
			position: absolute;
		}
		label#publish_type-error {
			position: absolute;
			top: 80%;
			left: -5%;
		}
		label {
			text-transform: capitalize;
		}
		label#experience_start-error {
			position: absolute;
		}

		label#partner-error {
			position: absolute;
		}

		label#skills\[\]-error {
			position: absolute;
		}
		label#is_agree-error{
			position: absolute;
			bottom: 3.3%;
			left: 2%;
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
			$.validator.addMethod(
					"regex",
					function(value, element, regexp)
					{
						if (regexp.constructor != RegExp)
							regexp = new RegExp(regexp);
						else if (regexp.global)
							regexp.lastIndex = 0;
						return this.optional(element) || regexp.test(value);
					},
					"Please check your input."
			);
			$("#expert-add-form").validate({
				rules: {
					first_name:{
						regex: "[a-zA-Z][a-zA-Z ]{2,}"
					},
					last_name:{
						regex: "[a-zA-Z][a-zA-Z ]{2,}"
					},
					headline:{
						regex: "[a-zA-Z][a-zA-Z ]{2,}"
					},
					description:{
						regex: "[a-zA-Z][a-zA-Z ]{2,}"
					},
					email:{
						email: true
					},
					publish_type: {
						required: true,
					}
				}

			});
		});
	</script>
@endpush
