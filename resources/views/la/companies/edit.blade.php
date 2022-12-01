@extends("la.layouts.app")

@section("contentheader_title")
	<a href="{{ url(config('laraadmin.adminRoute') . '/companies') }}">Prospects</a> :
@endsection
@section("contentheader_description", $company->$view_col)
@section("section", "Prospects")
@section("section_url", url(config('laraadmin.adminRoute') . '/companies'))
@section("sub_section", "Edit")

@section("htmlheader_title", "Companies Edit : ".$company->$view_col)

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
<style>
	.content-header h1 small {
		color: #48b0f7;
		font-weight: 600;
	}
	label#skills-error {
		position: absolute;
		bottom: 95px;
		left: 30px;
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

<div class="box">
	<div class="box-header">
		
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				{!! Form::model($company, ['route' => [config('laraadmin.adminRoute') . '.companies.update', $company->id ], 'method'=>'PUT', 'id' => 'company-edit-form']) !!}
					{{--@la_form($module)--}}

					{!! LAFormMaker::input($module, 'name','','required','form-control',['onkeypress'=>"return (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123) || (event.charCode==32)",'oninput'=>"this.value = this.value.replace(/^\s/g, '').replace(/(\..*)\./g, '$1');"]) !!}
					{!! LAFormMaker::input($module, 'email','','','form-control',['data-rule-email'=>'true']) !!}
					{!! LAFormMaker::input($module, 'homepage','','','form-control',['data-rule-url'=>'true']) !!}
					{!! LAFormMaker::input($module, 'address') !!}
					{{--{!! LAFormMaker::input($module, 'address2') !!}--}}
					{!! LAFormMaker::input($module, 'city','','','form-control',['onkeypress'=>"return (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123) || (event.charCode==32)"]) !!}
					{!! LAFormMaker::input($module, 'state','','','form-control',['onkeypress'=>"return (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123) || (event.charCode==32)"]) !!}
					<div class="form-group">
						<label for="country">Country :</label>
						<select class="form-control select2-hidden-accessible" data-placeholder="Enter Country" rel="select2" name="country" tabindex="-1" aria-hidden="true">
							@foreach($countriesList as $code => $name)
								<option value="{{$name}}" {{$company->country == $name? 'selected': ''}}>{{$name}}</option>
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
								<option value="{{$code}}" {{$company->language == $code? 'selected': ''}}>{{$name}}</option>
							@endforeach
						</select>
					</div>

					<div class="form-group">
						<label for="type">Type :</label>
						<select class="form-control select2-hidden-accessible" data-placeholder="select Type" rel="select2" name="type" tabindex="-1" aria-hidden="true">
							@foreach($companyTypeList as $code => $name)
								<option value="{{$code}}" {{$company->type == $code? 'selected': ''}}>{{$name}}</option>
							@endforeach
						</select>
					</div>
					@if(Entrust::hasRole("SUPER_ADMIN"))
					<div class="form-group">
						<label for="type">Plan :</label>
						<select class="form-control select2-hidden-accessible" data-placeholder="select Plan" rel="select2" name="plan_type" tabindex="-1" aria-hidden="true">
							@foreach(\App\Models\Company::PLAN_LIST as $key => $text)
								<option value="{{$key}}" {{$company->plan_type == $key? 'selected': ''}}>{{$text}}</option>
							@endforeach
						</select>
					</div>
					@endif
					<div class="form-group checkbox">
						<label>
							<input type="checkbox" name="strategic" id="strategic" {{$company->strategic==1?'checked=checked':''}} value="{{$company->strategic?1:0}}"> is Strategic
						</label>
					</div>

					<div class="form-group">
						<label for="is_banned">Banned :</label>
						<select class="form-control select2-hidden-accessible" data-placeholder="Enter Banned" rel="select2" name="is_banned" tabindex="-1" aria-hidden="true">
							<option value="0" {{$company->is_banned == 0?'selected':''}}>allowed</option>
							<option value="1" {{$company->is_banned == 1?'selected':''}}>banned</option>
						</select>
					</div>

					@if($company->isSupplier!=1)

					<div class="form-group checkbox">
	                  <label>
	                    <input type="checkbox" name="is_supplier" id="is_supplier" {{$company->isSupplier==1?'checked=checked':''}}> is Supplier
	                  </label>
	                </div>

                    <div class="hide" id="supplier_div">
                    	
                    	<fieldset style="border: 1px #ccc solid;padding: 15px;">
                    		<legend style="margin: 0;width: auto;border: none;"><h4>Supplier Details:</h4></legend>
                    	
		                	<div class="">

			                    <div class="form-group">
			                    	<label for="closing_rate">Closing Rate :</label>
			                    	<input class="form-control supplier_info" placeholder="Enter Closing Rate" disabled="disabled" data-rule-maxlength="100" name="supplier[closing_rate]" type="text" value="">
			                    </div>

			                    <div class="form-group">
			                    	<label for="avg_response_time">AverageResponseTime :</label>
			                    	<input class="form-control supplier_info" placeholder="Enter AverageResponseTime" disabled="disabled" data-rule-maxlength="100" name="supplier[avg_response_time]" type="text" value="">
			                    </div>

			                    <div class="form-group">
			                    	<label for="hourly_rate">Hourly Rate :</label>
			                    	<input class="form-control supplier_info" placeholder="Enter Hourly Rate" data-rule-maxlength="100" disabled="disabled" name="supplier[hourly_rate]" type="text" value="">
			                    </div>	

			                    <div class="form-group">
									<label for="channel">Skill :</label>
									<select class="form-control supplier_info valid" name="skill[]" multiple="" rel="select2" disabled="disabled" id="skills">
										@foreach($aSkill as $key=>$skill)
											<option value="{{$key}}">{{$skill}}</option>
										@endforeach
									</select>
								</div>			

							</div>
						</fieldset>
                    </div>
                    @endif
                    <br>
                    <br>
					<div class="form-group">
						{!! Form::submit( 'Update', ['class'=>'btn btn-success']) !!} <button class="btn btn-default pull-right"><a href="{{ url(config('laraadmin.adminRoute') . '/companies') }}">Cancel</a></button>
					</div>
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
<style type="text/css">
	.select2-container--default .select2-results__option[aria-selected="true"]{display: none;}
</style>
@endsection


@push('scripts')
<script>
$(function () {
	$("#company-edit-form").validate({
		
	});
	$("#is_supplier").change(function(){
		var div = '#supplier_div';

		if($(this).is(':checked')){
			$(div).removeClass('hide');
			$(div).find('.supplier_info').each(function(){
				$(this).removeAttr('disabled').attr('required',true);	
			})
		}else{
			$(div).addClass('hide');
			$(div).find('.supplier_info').each(function(){
				$(this).attr('disabled',true).attr('required',false);
			})
		}
	});
});
</script>
@endpush
