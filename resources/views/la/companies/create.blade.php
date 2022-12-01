@extends("la.layouts.app")

@section("contentheader_title")
	<a href="{{ url(config('laraadmin.adminRoute') . '/companies') }}">Prospects</a> :
@endsection
@section("contentheader_description", "Create Prospect")
@section("section", "Prospects")
@section("section_url", url(config('laraadmin.adminRoute') . '/companies'))
@section("sub_section", "create")

@push('styles')
<style>
.has-error .help-block {
	color: #f55753;
}
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
				{!! Form::open(['action' => 'LA\CompaniesController@store', 'id' => 'company-add-form']) !!}
					{{--@la_form($module)--}}

					{!! LAFormMaker::input($module, 'name','','required','form-control',['onkeypress'=>"return (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123) || (event.charCode==32)",'oninput'=>"this.value = this.value.replace(/^\s/g, '').replace(/(\..*)\./g, '$1');"]) !!}
					{!! LAFormMaker::input($module, 'email','','required','form-control',['data-rule-email'=>'true']) !!}
					{!! LAFormMaker::input($module, 'homepage','','','form-control',['data-rule-url'=>'true']) !!}
					{!! LAFormMaker::input($module, 'address') !!}
					{{--{!! LAFormMaker::input($module, 'address2') !!}--}}
					{!! LAFormMaker::input($module, 'city','','','form-control',['onkeypress'=>"return (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123) || (event.charCode==32)"]) !!}
					{!! LAFormMaker::input($module, 'state','','','form-control',['onkeypress'=>"return (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123) || (event.charCode==32)"]) !!}
					<div class="form-group">
						<label for="country">Country :</label>
						<select class="form-control select2-hidden-accessible" data-placeholder="Enter Country" rel="select2" name="country" tabindex="-1" aria-hidden="true">
							@foreach($countriesList as $code => $name)
								<option value="{{$name}}" {{old('country') == $name? 'selected': ''}}>{{$name}}</option>
							@endforeach
						</select>
					</div>
					{!! LAFormMaker::input($module, 'zipcode','','','form-control',['oninput'=>"this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');",'data-rule-number'=>'true','maxlength'=>'10','minlength'=>'4']) !!}
					{!! LAFormMaker::input($module, 'phone','','','form-control',['oninput'=>"this.value = this.value.replace(/[^0-9+]/g, '').replace(/(\..*)\./g, '$1');",'maxlength'=>'14','minlength'=>'10']) !!}
					{{--{!! LAFormMaker::input($module, 'fax','','','form-control',['oninput'=>"this.value = this.value.replace(/[^0-9+]/g, '').replace(/(\..*)\./g, '$1');", 'minlength'=>'8', 'maxlength'=>'14']) !!}--}}
					{{--{!! LAFormMaker::input($module, 'logo_url','','','form-control',['data-rule-url'=>'true']) !!}--}}
					<div class="form-group">
						<label for="language">Language :</label>
						<select class="form-control select2-hidden-accessible" data-placeholder="Enter Language" rel="select2" name="language" tabindex="-1" aria-hidden="true">
							@foreach($languageList as $code => $name)
								<option value="{{$code}}" {{old('language') == $code? 'selected': ''}}>{{$name}}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group">
						<label for="type">Type :</label>
						<select class="form-control select2-hidden-accessible" data-placeholder="select Type" rel="select2" name="type" tabindex="-1" aria-hidden="true">
							@foreach($companyTypeList as $code => $name)
								<option value="{{$code}}" {{old('type') == $code? 'selected': ''}}>{{$name}}</option>
							@endforeach
						</select>
					</div>
					{!! LAFormMaker::input($module, 'channel') !!}
					<div class="form-group checkbox">
						<label>
							<input type="checkbox" name="strategic" id="strategic" value="1"> is Strategic
						</label>
					</div>
					<div class="form-group">
						{!! Form::submit( 'Submit', ['class'=>'btn btn-success']) !!} <button class="btn btn-default pull-right"><a href="{{ url(config('laraadmin.adminRoute') . '/companies') }}">Cancel</a></button>
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
			$("#company-add-form").validate({
				rules: {
					name:{
						regex: "[a-zA-Z][a-zA-Z ]{2,}"
					},
					email:{
						required: true,
						email: true
					},
					address:{
						regex: "[a-zA-Z][a-zA-Z ]{2,}"
					},
					address2:{
						regex: "[a-zA-Z][a-zA-Z ]{2,}"
					},
					city:{
						regex: "[a-zA-Z][a-zA-Z ]{2,}"
					},
					state:{
						regex: "[a-zA-Z][a-zA-Z ]{2,}"
					},
					channel:{
						regex: "[a-zA-Z][a-zA-Z ]{2,}"
					}
				}

			});
		});
	</script>
@endpush
