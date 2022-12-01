@extends("la.layouts.app")

@section("contentheader_title")
	<a href="{{ url(config('laraadmin.adminRoute') . '/employees') }}">Employees</a> :
@endsection
@section("contentheader_description", "Create Employee")
@section("section", "Employees")
@section("section_url", url(config('laraadmin.adminRoute') . '/employees'))
@section("sub_section", "create")

@push('styles')
<style>
.has-error .help-block {
	color: #f55753;
}
label#mobile-error,label#mobile2-error {
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
				{!! Form::open(['action' => 'LA\EmployeesController@store', 'id' => 'employee-add-form', 'enctype'=>'multipart/form-data']) !!}
					{{--{!! LAFormMaker::form($module) !!}--}}

					{!! LAFormMaker::input($module, 'name') !!}
					{!! LAFormMaker::input($module, 'designation') !!}
					{!! LAFormMaker::input($module, 'gender') !!}
					{!! LAFormMaker::input($module, 'mobile') !!}
					{!! LAFormMaker::input($module, 'mobile2') !!}
					{!! LAFormMaker::input($module, 'email') !!}
					{!! LAFormMaker::input($module, 'dept') !!}
					{!! LAFormMaker::input($module, 'address') !!}
					{!! LAFormMaker::input($module, 'city') !!}
					{!! LAFormMaker::input($module, 'state') !!}
					<div class="form-group">
						<label for="country">Country :</label>
						<select class="form-control select2-hidden-accessible" data-placeholder="Enter Country" rel="select2" name="country" tabindex="-1" aria-hidden="true">
							@foreach($countriesList as $code => $name)
								<option value="{{$name}}">{{$name}}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group">
						<label for="zipcode">Zipcode :</label>
						<input class="form-control" placeholder="Enter Zipcode" data-rule-maxlength="50" name="zipcode" type="text" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" maxlength="10" minlength="6">
					</div>
					{!! LAFormMaker::input($module, 'about') !!}
					{!! LAFormMaker::input($module, 'date_birth') !!}
					{!! LAFormMaker::input($module, 'date_hire') !!}
					{!! LAFormMaker::input($module, 'date_left') !!}
					{!! LAFormMaker::input($module, 'salary_cur','0','','form-control',['oninput'=>"this.value = this.value.replace(/[^0-9+]/g, '').replace(/(\..*)\./g, '$1');", 'minlength'=>'0', 'maxlength'=>'2']) !!}

					<div class="form-group">
						<label for="role">Role* :</label>
						<select class="form-control" required="1" data-placeholder="Select Role" rel="select2" name="role">
							<?php $roles = App\Role::all(); ?>
							@foreach($roles as $role)
								@if($role->id != 1)
									<option value="{{ $role->id }}">{{ $role->name }}</option>
								@endif
							@endforeach
						</select>
					</div>
					<div class="form-group">
						<label for="role">Company :</label>
						<select class="form-control" data-placeholder="Select Company" rel="select2" name="company_id">
							<option value="">Select Company</option>
							<?php $companies = \DB::table('suppliers')->whereNull('suppliers.deleted_at')->join('companies', 'companies.id', '=', 'suppliers.company_id')->select([\DB::raw('concat(companies.name,IF(companies.email="","",concat("(",companies.email,")"))) as name'),'companies.id'])->groupBy('companies.id')->get(); ?>
							@foreach($companies as $company)
								<option value="{{ $company->id }}">{{ $company->name }}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group {{ $errors->has('profile_picture') ? ' has-error' : '' }}">
						<label for="image_file">Profile Picture :</label>
						<input class="form-control" placeholder="Select Image file" name="profile_picture" type="file" accept="image/*">
						@if ($errors->has('profile_picture'))
							<span class="help-block">
									<strong>{{ $errors->first('profile_picture') }}</strong>
								</span>
						@endif
					</div>
					<div class="form-group">
						{!! Form::submit( 'Submit', ['class'=>'btn btn-success']) !!} <button class="btn btn-default pull-right"><a href="{{ url(config('laraadmin.adminRoute') . '/employees') }}">Cancel</a></button>
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
		$("#employee-add-form").validate({
			rules: {
				name:{
					regex: "[a-zA-Z][a-zA-Z ]{2,}"
				},
				zipcode: {
					digits: true,
					minlength: 4,
					maxlength: 10,
				},
				mobile: {
					regex: "^[1-9+]+[0-9+]*$",
				},
				mobile2: {
					regex: "^[1-9+]+[0-9+]*$",
				},
			}
		});
	});
</script>
@endpush
