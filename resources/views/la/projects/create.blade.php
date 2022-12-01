@extends("la.layouts.app")

@section("contentheader_title")
	<a href="{{ url(config('laraadmin.adminRoute') . '/projects') }}">Lead</a> :
@endsection
@section("contentheader_description", "Create Lead")
@section("section", "Leads")
@section("section_url", url(config('laraadmin.adminRoute') . '/projects'))
@section("sub_section", "create")

@section("main-content")
@push('styles')
<style type="text/css">
	.switch {
		position: relative;
		display: inline-block;
		width: 30px;
		height: 17px;
	}
	.switch input {
		opacity: 0;
		width: 0;
		height: 0;
	}
	.slider {
		position: absolute;
		cursor: pointer;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		background-color: #ccc;
		-webkit-transition: .4s;
		transition: .4s;
	}
	.slider:before {
		position: absolute;
		content: "";
		height: 13px;
		width: 13px;
		left: 2px;
		bottom: 2px;
		background-color: white;
		-webkit-transition: .4s;
		transition: .4s;
	}
	input:checked + .slider {
		background-color: #2196F3;
	}
	input:focus + .slider {
		box-shadow: 0 0 1px #2196F3;
	}
	input:checked + .slider:before {
		-webkit-transform: translateX(13px);
		-ms-transform: translateX(13px);
		transform: translateX(13px);
	}
	.slider.round {
		border-radius: 17px;
	}
	.slider.round:before {
		border-radius: 50%;
	}
	.has-error .help-block {
		color: #f55753;
	}
</style>
@endpush
@php
	//$aChannel = config('constant.channel.options');
	
@endphp

@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@if(session()->has('error'))
	<p class="alert alert-danger">{{ session()->get('error') }}</p>
@endif

<div class="box">
	<div class="box-header">
		
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				{!! Form::open(['action' => 'LA\ProjectsController@store', 'id' => 'project-add-form']) !!}

					<div class="panel panel-default ">
						<div class="panel-body">
							<div class="row">
								<div class="col-md-12">
									<ul class="nav nav-tabs">
										<li class="active">
											<a data-toggle="tab" href="#client-tab" class="clientTab">I am Client</a>
										</li>
										<li class="">
											<a data-toggle="tab" href="#affiliate-tab" class="affiliateTab">I am Affiliate</a>
										</li>
									</ul>
								</div>
							</div>
					<div class="form-group">
						<p> <br> <label>Client Details:</label> <br>Enter the client details we will share it with the relevant candidates.</p>
						<input type="hidden" name="affiliate" id="affiliate" value="{{old('affiliate')}}">
					</div>
					@if(Auth::user()->company_id && Entrust::hasRole("PARTNER") && !empty($companyEmail))
					<div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
						<label for="companyEmail">Client Email :</label>
						<input class="form-control" placeholder="Enter Client email" id="companyEmail" name="email" type="text" value="{{old('email')?:$companyEmail}}" data-rule-email="true" >
						@if ($errors->has('email'))
							<span class="help-block"><strong>{{ $errors->first('email') }}</strong></span>
						@endif
					</div>
					@else
					<div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
						<label for="companyEmail">Client Email :</label>
						<input class="form-control" placeholder="Enter Client email" id="companyEmail" name="email" type="text" value="{{old('email')?:$companyEmail}}" data-rule-email="true" >
						{{--<select class="form-control valid" id="companyEmail" name="email" rel="taginput" placeholder="Select Email">
							<option value="">Enter Email</option>
							@foreach($aRowCompany as $key=>$aCompanyData)								
								<option value="{{$aCompanyData['email']}}" {{old('email') == $aCompanyData['email']? 'selected': ''}}>{{$aCompanyData['email']}}</option>
							@endforeach
						</select>--}}
						@if ($errors->has('email'))
							<span class="help-block"><strong>{{ $errors->first('email') }}</strong></span>
						@endif
					</div>
					@endif

					<div class="form-group {{ $errors->has('phone') ? ' has-error' : '' }}">
						<label for="companyPhone">Client Phone :</label>
						<input class="form-control" placeholder="Enter Client phone" id="companyPhone" name="phone" type="tel" value="{{old('phone')?:$companyPhone}}" oninput="this.value = this.value.replace(/[^0-9+]/g, '').replace(/(\..*)\./g, '$1');" maxlength='14'>
						@if ($errors->has('phone'))
							<span class="help-block"><strong>{{ $errors->first('phone') }}</strong></span>
						@endif
					</div>

					<div id="affiliate-fields">
						<div class="form-group {{ $errors->has('affiliateName') ? ' has-error' : '' }}">
							<label for="affiliateName">Name* :</label>
							<input class="form-control" placeholder="Enter Name" data-rule-maxlength="255" name="affiliateName" id="affiliateName" type="text" value="{{old('affiliateName')}}">
							@if ($errors->has('affiliateName'))
								<span class="help-block"><strong>{{ $errors->first('affiliateName') }}</strong></span>
							@endif
						</div>
						<div class="form-group">
							<label for="affiliateDescription">Description :</label>
							<textarea class="form-control" placeholder="Enter Description" data-rule-maxlength="255" name="affiliateDescription" id="affiliateDescription">{{old('affiliateDescription')}}</textarea>
						</div>
						<div class="form-group">
							<label for="affiliateAddress">Address :</label>
							<textarea class="form-control" placeholder="Enter Address" data-rule-maxlength="255" name="affiliateAddress" id="affiliateAddress">{{old('affiliateAddress')}}</textarea>
						</div>
						<div class="form-group">
							<label for="affiliateAddress2">Address2 :</label>
							<textarea class="form-control" placeholder="Enter Address2" data-rule-maxlength="255" name="affiliateAddress2" id="affiliateAddress2">{{old('affiliateAddress2')}}</textarea>
						</div>
						<div class="form-group">
							<label for="affiliateCity">City :</label>
							<input class="form-control" placeholder="Enter City" data-rule-maxlength="255" name="affiliateCity" id="affiliateCity" type="text" value="{{old('affiliateCity')}}">
						</div>
						<div class="form-group">
							<label for="affiliateState">State :</label>
							<input class="form-control" placeholder="Enter State" data-rule-maxlength="255" name="affiliateState" id="affiliateState" type="text" value="{{old('affiliateState')}}">
						</div>
						<div class="form-group">
							<label for="affiliateCountry">Country :</label>
							<input class="form-control" placeholder="Enter Country" data-rule-maxlength="255" name="affiliateCountry" id="affiliateCountry" type="text" value="{{old('affiliateCountry')}}">
						</div>
						<div class="form-group">
							<label for="affiliateZipcode">Zipcode :</label>
							<input class="form-control" placeholder="Enter Zipcode" data-rule-maxlength="255" name="affiliateZipcode" id="affiliateZipcode" type="text" value="{{old('affiliateZipcode')}}" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" maxlength='10'>
						</div>
						<div class="form-group">
							<label for="affiliateFax">Fax :</label>
							<input class="form-control" placeholder="Enter Fax" data-rule-maxlength="255" name="affiliateFax" id="affiliateFax" type="text" value="{{old('affiliateFax')}}">
						</div>
						<div class="form-group">
							<label for="affiliateWebsite">Website :</label>
							<input class="form-control" placeholder="Enter Website" data-rule-maxlength="255" name="affiliateWebsite" id="affiliateWebsite" type="text" data-rule-url="true" value="{{old('affiliateWebsite')}}">
						</div>
					</div>
						</div>
					</div>

					<div class="panel panel-default ">
						<div class="panel-body">
							<p><label>Lead Details:</label> <br>Enter the lead details as descriptive as you can - this will allow as to bring best candidates for it.</p>

				    <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
				    	<label for="name">Project Name* :</label>
				    	<input class="form-control" placeholder="Enter Project Name" data-rule-maxlength="255" name="name" type="text" value="{{old('name')}}" >
						@if ($errors->has('name'))
							<span class="help-block"><strong>{{ $errors->first('name') }}</strong></span>
						@endif
				    </div>

				    <div class="form-group {{ $errors->has('description') ? ' has-error' : '' }}">
				    	<label for="description">Project Description* :</label>
				    	<textarea class="form-control" placeholder="Enter Project Description" cols="30" rows="3" name="description">{{old('description')}}</textarea>
						@if ($errors->has('description'))
							<span class="help-block"><strong>{{ $errors->first('description') }}</strong></span>
						@endif
				    </div>

					<div class="form-group">
						<label for="channel">Skills :</label>
						<select class="form-control valid" name="skill[]" multiple="" rel="select2">
							@foreach($aSkill as $key=>$aKeywordsSkill)
								{{--@php
									$strIsJson = is_string($aKeywordsSkill['keywords']) && is_array(json_decode($aKeywordsSkill['keywords'], true)) ? true : false ;
								@endphp
								@if ($strIsJson)
									@foreach(json_decode($aKeywordsSkill['keywords'], true) as $skill)
										<option value="{{$aKeywordsSkill['id']}}">{{$skill}}</option>
									@endforeach
								@else--}}
									<option value="{{$aKeywordsSkill['id']}}">{{$aKeywordsSkill['keyword']}}</option>
								{{--@endif--}}

							@endforeach
						</select>
					</div>
					<div class="form-group">
						<label for="name">Experience  :</label>
						<input class="form-control" placeholder="Enter required Experience" data-rule-maxlength="255" name="experience" type="text" value="{{old('experience')}}" >
						@if ($errors->has('experience'))
							<span class="help-block"><strong>{{ $errors->first('experience') }}</strong></span>
						@endif
					</div>
				    <div class="form-group">
				    	<label for="categories">Meta Keywords : </label> "Used for search engines know which keywords were most relevant to the content."
				    	<input class="form-control" placeholder="Enter Meta keywords" data-rule-maxlength="100" name="categories" type="text" value="{{old('categories')}}">
				    </div>

				    <div class="form-group">
				    	<label for="project_budget">Project Budget :</label>
						<div class="row">
							<div class="col-md-4">
								<select class="form-control select2-hidden-accessible" data-placeholder="Select currency" rel="select2" name="currency" tabindex="-1" aria-hidden="true" >
									<option disabled>Select currency</option>
									@foreach($currencyList as $currencyCode =>$currencyText)
										<option value="{{$currencyCode}}" @if(empty(old('currency'))) {{$currencyCode == 'USD'? 'selected' : ''}} @endif {{old('currency') == $currencyCode? 'selected': ''}}>{{$currencyText}}</option>
									@endforeach
								</select>
							</div>
							<div class="col-md-8">
								<input class="form-control" placeholder="Enter Project Budget" data-rule-maxlength="100" name="project_budget" type="text" value="{{old('project_budget')}}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
							</div>
						</div>
				    </div>
				    
				    <div class="form-group">
						<div>
							<input type="radio" id="fixPrice" name="is_hourly" value="0" <?= old('is_hourly') == 0 ? 'checked' : '';?>>
							<label for="fixPrice">Fixed Price Project</label>
						</div>
						<div>
							<input type="radio" id="hourlyBased" name="is_hourly" value="1" <?= old('is_hourly') == 1 ? 'checked' : '';?>>
							<label for="hourlyBased">Hourly Based Project</label>
						</div>
				    </div>

				    <div class="form-group">
				    	<label for="url">Project Original URL :</label>
				    	<input class="form-control" placeholder="Enter Project Original URL" data-rule-maxlength="255" name="url" type="text" value="{{old('url')}}">
						<input type="hidden" name="status" value="{{\App\Models\Project::STATUS_PENDING}}">
				    </div>
					@if(Auth::user()->company_id && Entrust::hasRole("PARTNER"))
						<input type="hidden" name="channel" value="{{\App\Channels\BaseChannel::CRM}}">
						<input type="hidden" name="source" value="{{\App\Models\Project::SOURCE_PARTNER}}">
					@else
					<div class="form-group">
						<label for="channel">Project Channel :</label>
						<select class="form-control select2-hidden-accessible" data-placeholder="Enter Project Channel" rel="select2" name="channel" tabindex="-1" aria-hidden="true" >
							@foreach($channelList as $channel)
								<option value="{{$channel}}" {{old('channel') == $channel? 'selected': ''}}>{{ucfirst($channel)}}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group">
						<label for="channel">Source :</label>
						<select class="form-control select2-hidden-accessible" data-placeholder="Select Source" rel="select2" name="source" tabindex="-1" aria-hidden="true" >
							@foreach($sourceList as $key=>$type)
								<option value="{{$key}}" {{old('source') == $key? 'selected': ''}}>{{$type}}</option>
							@endforeach
						</select>
					</div>
					@endif
						</div>
					</div>


                    <br>

					<div class="form-group">
						{!! Form::submit( 'Submit Lead', ['class'=>'btn btn-success btn-lead-submit']) !!} <button class="btn btn-default pull-right"><a href="{{ url(config('laraadmin.adminRoute') . '/projects') }}">Cancel</a></button>
					</div>
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@endsection

@push('scripts')
	<script>
		/*$("#company_email").change(function(){		
		//$('.company_email').on('keyup', function (e) {
		    $.ajax({
		        url: "check_exists_email",
		        type: "post",
		        data: { email : $(this).val(), _token : $('meta[name="csrf-token"]').attr('content') },
		        success: function(data){
		            $("#employees").html(data);
		        }
		    });
		});*/
	</script>
<script>
	$(document).ready(function() {
		<?php if(old('affiliate')) { ?>
			$('#affiliate-fields').show();
			$(".affiliateTab").trigger('click');
			$('#companyEmail').attr('readonly',false);
			$('#companyPhone').val('{{old('phone')}}');
		<?php } else { ?>
			$('#affiliate-fields').hide();
			$('#affiliate').val(0);
		<?php } ?>
		$(".clientTab").click(function(){
			$('#affiliate').val(0);
			$('#affiliate-fields').hide();
			@if(Auth::user()->company_id && Entrust::hasRole("PARTNER") && !empty($companyEmail))
			$('#companyEmail').val('{{$companyEmail}}');
			$('#companyPhone').val('{{$companyPhone}}');
			@endif
			var mobile = document.getElementById('companyPhone');
			mobile.dispatchEvent(new Event("keyup"));
		});
		$(".affiliateTab").click(function(){
			$('#affiliate').val(1);
			$('#affiliate-fields').show();
			@if(Auth::user()->company_id && Entrust::hasRole("PARTNER") && !empty($companyEmail))
			$('#companyEmail').val('');
			$('#companyPhone').val('');
			$('#companyEmail').attr('readonly',false);
			@endif
			var mobile = document.getElementById('companyPhone');
			mobile.dispatchEvent(new Event("keyup"));
		});
	});
</script>

@endpush

@section('footer_scripts')
	<script type="text/javascript">
		$(function() {
			$("#companyEmail").on("change", function() {
				var email = $(this).val();
				$.ajax({
					url: "/admin/get-phone",
					type: "post",
					data: {
					    email : $(this).val(),
						_token : $('meta[name="csrf-token"]').attr('content')
					},
					success: function(response){
						if(response.status){
							$("#companyPhone").val(response.data.phone);
							var mobile = document.getElementById('companyPhone');
							mobile.dispatchEvent(new Event("keyup"));
						}
					}
				});
			});
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
			$("#project-add-form").validate({
				rules: {
					name:{
						required: true,
						regex: "[a-zA-Z][a-zA-Z ]{2,}"
					},
					description:{
						required: true,
						regex: "[a-zA-Z][a-zA-Z ]{2,}"
					},
					categories:{
						regex: "[a-zA-Z][a-zA-Z ]{2,}"
					},
					url:{
						regex: "[a-zA-Z][a-zA-Z ]{2,}"
					},
					email:{
						email: true,
						regex: "^[a-z@.]+[a-z.a.z]*$",
					},
				},
				submitHandler: function(form) {
					// do other stuff for a valid form
					$('#project-add-form input[type=submit]').attr("disabled", "disabled");
					form.submit();
				}
			});
		});
	</script>
@endsection
