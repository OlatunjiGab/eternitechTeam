@extends('la.layouts.app')

@section('htmlheader_title')
	Employee View
@endsection


@section('main-content')
<div id="page-content" class="profile2">
	<div class="bg-success clearfix">
		<div class="col-md-10 col-xs-9">
			<div class="row">
				<div class="col-md-4">
					<div class="row">
						<div class="col-md-3">
							<img class="profile-image" src="{{ asset('storage/images/'.$user->profile_picture) }}" alt="">
						</div>
						<div class="col-md-9">
							<h4 class="name">{{ $employee->$view_col }}</h4>
							<div class="row stats">
								@if($employee->designation)
									<div class="col-md-6 stat"><div class="label2" data-toggle="tooltip" data-placement="top" title="Designation">{{ $employee->designation }}</div></div>
								@endif
								@if($employee->city)
									<div class="col-md-6 stat"><i class="fa fa-map-marker"></i> {{ $employee->city or "NA" }}</div>
								@endif
							</div>
							@if($employee->about)
								<p class="desc">{{ substr($employee->about, 0, 33) }}@if(strlen($employee->about) > 33)...@endif</p>
							@endif
						</div>
					</div>
				</div>
				<div class="col-md-3">
					@if($employee->email)
						<div class="dats1"><i class="fa fa-envelope-o"></i> {{ $employee->email }}</div>
					@endif
					@if($employee->mobile)
						<div class="dats1"><i class="fa fa-phone"></i> {{ $employee->mobile }}</div>
					@endif
					@if(strtotime($employee->date_hire) == strtotime('0000-00-00') || strtotime($employee->date_hire) == strtotime('1990-01-01'))
						<div class="dats1"><i class="fa fa-clock-o"></i> Joined on {{ date("M d, Y", strtotime($employee->created_at)) }}</div>
					@else
						<div class="dats1"><i class="fa fa-clock-o"></i> Joined on {{ date("M d, Y", strtotime($employee->date_hire)) }}</div>
					@endif
				</div>
			</div>
		</div>
		<div class="col-md-1 col-xs-3 actions">
			@if($employee->id == Auth::user()->context_id)
				<a href="{{ url(config('laraadmin.adminRoute') . '/employees/'.$employee->id.'/edit') }}" class="btn btn-xs btn-edit btn-default"><i class="fa fa-pencil"></i></a><br>
			@else
				@if(LAFormMaker::la_access("Employees", "edit"))
				<a href="{{ url(config('laraadmin.adminRoute') . '/employees/'.$employee->id.'/edit') }}" class="btn btn-xs btn-edit btn-default"><i class="fa fa-pencil"></i></a><br>
			@endif
			@endif
			
			@if(LAFormMaker::la_access("Employees", "delete"))
				{{ Form::open(['route' => [config('laraadmin.adminRoute') . '.employees.destroy', $employee->id], 'method' => 'delete', 'style'=>'display:inline', 'onsubmit'=>$deleteMessage]) }}
					<button class="btn btn-default btn-delete btn-xs" type="submit"><i class="fa fa-times"></i></button>
				{{ Form::close() }}
			@endif
		</div>
	</div>

	<ul data-toggle="ajax-tab" class="nav nav-tabs profile" role="tablist">
		@if($employee->id == Auth::user()->id || Entrust::hasRole("SUPER_ADMIN"))
		<li class=""><a href="{{ url(config('laraadmin.adminRoute') . '/employees') }}" data-toggle="tooltip" data-placement="right" title="Back to Employees"><i class="fa fa-chevron-left"></i></a></li>
		@else
		<li class=""><a href="{{ url(config('laraadmin.adminRoute') . '/dashboard') }}" data-toggle="tooltip" data-placement="right" title="Back to Employees"><i class="fa fa-chevron-left"></i></a></li>
		@endif
		<li class="active"><a role="tab" data-toggle="tab" class="active" href="#tab-info" data-target="#tab-info"><i class="fa fa-bars"></i> General Info</a></li>
		@if($employee->id == Auth::user()->id || Entrust::hasRole("SUPER_ADMIN"))
			<li class=""><a role="tab" data-toggle="tab" href="#tab-account-settings" data-target="#tab-account-settings"><i class="fa fa-key"></i> Account settings</a></li>
		@endif
		@if(Entrust::hasRole("SUPER_ADMIN"))
		<li class=""><a role="tab" data-toggle="tab" href="#tab-access" data-target="#tab-access"><i class="fa fa-key"></i>Access Control</a></li>
		@endif
	</ul>

	<div class="tab-content">
		<div role="tabpanel" class="tab-pane active fade in" id="tab-info">
			<div class="tab-content">
				<div class="panel infolist">
					<div class="panel-default panel-heading">
						<h4>General Info</h4>
					</div>
					<div class="panel-body">
						{!! LAFormMaker::display($module, 'name') !!}
						{!! LAFormMaker::display($module, 'designation') !!}
						{!! LAFormMaker::display($module, 'gender') !!}
						{!! LAFormMaker::display($module, 'mobile') !!}
						{!! LAFormMaker::display($module, 'mobile2') !!}
						{!! LAFormMaker::display($module, 'email') !!}
						@if(Entrust::hasRole("SUPER_ADMIN"))
						{!! LAFormMaker::display($module, 'dept') !!}
						@endif
						{!! LAFormMaker::display($module, 'address') !!}
						{!! LAFormMaker::display($module, 'city') !!}
						{!! LAFormMaker::display($module, 'state') !!}
						{!! LAFormMaker::display($module, 'zipcode') !!}
						{!! LAFormMaker::display($module, 'country') !!}
						{!! LAFormMaker::display($module, 'about') !!}
						@if(strtotime($employee->date_birth) == strtotime('0000-00-00') || strtotime($employee->date_birth) == strtotime('1990-01-01'))
						<div class="form-group">
							<label for="date_birth" class="col-md-2">Date of Birth :</label>
							<div class="col-md-10 fvalue"></div>
						</div>
						@else
						{!! LAFormMaker::display($module, 'date_birth') !!}
						@endif
						@if(strtotime($employee->date_hire) == strtotime('0000-00-00') || strtotime($employee->date_hire) == strtotime('1990-01-01'))
						<div class="form-group">
							<label for="date_hire" class="col-md-2">Hiring Date :</label>
							<div class="col-md-10 fvalue">{{ date("M d, Y", strtotime($employee->created_at))  }}</div>
						</div>
						@else
							{!! LAFormMaker::display($module, 'date_hire') !!}
						@endif
						@if(Entrust::hasRole("SUPER_ADMIN"))
						{!! LAFormMaker::display($module, 'salary_cur') !!}
						@endif
					</div>
				</div>
			</div>
		</div>
		
		@if($employee->id == Auth::user()->id || Entrust::hasRole("SUPER_ADMIN"))
		<div role="tabpanel" class="tab-pane fade" id="tab-account-settings">
			<div class="tab-content">
				<form action="{{ url(config('laraadmin.adminRoute') . '/change_password/'.$employee->id) }}" id="password-reset-form" class="general-form dashed-row white" method="post" accept-charset="utf-8">
					{{ csrf_field() }}
					<div class="panel">
						<div class="panel-default panel-heading">
							<h4>Account settings</h4>
						</div>
						<div class="panel-body">
							@if (count($errors) > 0)
								<div class="alert alert-danger">
									<ul>
										@foreach ($errors->all() as $error)
											<li>{{ $error }}</li>
										@endforeach
									</ul>
								</div>
							@endif
							@if(Session::has('success_message'))
								<p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('success_message') }}</p>
							@endif
							<div class="form-group">
								<label for="password" class=" col-md-2">Password</label>
								<div class=" col-md-10">
									<input type="password" name="password" value="" id="password" class="form-control" placeholder="Password" autocomplete="off" required="required" data-rule-minlength="6" data-msg-minlength="Please enter at least 6 characters.">
								</div>
							</div>
							<div class="form-group">
								<label for="password_confirmation" class=" col-md-2">Retype password</label>
								<div class=" col-md-10">
									<input type="password" name="password_confirmation" value="" id="password_confirmation" class="form-control" placeholder="Retype password" autocomplete="off" required="required" data-rule-equalto="#password" data-msg-equalto="Please enter the same value again.">
								</div>
							</div>
						</div>
						<div class="panel-footer">
							<button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> Change Password</button>
						</div>
					</div>
				</form>
			</div>
		</div>
		@endif
		<div role="tabpanel" class="tab-pane fade in p20 bg-white" id="tab-access">
			<div class="tab-content">
				<div class="panel infolist">
					<form action="{{ url('/admin/save_partner_access/'.$user_id) }}" method="post">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<div class="panel-default panel-heading">
							<h4>Permissions for Partner</h4>
						</div>
						<div class="panel-body">
							<?php $options = [
									'0'=>'None',
									'1'=>'Own',
									'2'=>'All',
								]; ?>
							@foreach ($routes as $route)
								<div class="form-group">
									<label for="ratings_innovation" class="col-md-2">{{ $route->route }} :</label>
									<div class="col-md-10 fvalue star_class">
										<select id="route_{{ $route->id }}" name="route_{{ $route->id }}" class="form-control">
											@foreach ($options as $key => $value)
												<option value="{{ $key }}" {{ $route->is_access == $key ? 'selected' : ''}}>{{ $value }}</option>
											@endforeach
										</select>
									</div>
								</div>
							@endforeach

							<div class="form-group">
								<label for="ratings_innovation" class="col-md-2"></label>
								<div class="col-md-10 fvalue star_class">
									<input class="btn btn-success" type="submit" value="Save">
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@push('scripts')
<script>
$(function () {
	@if($employee->id == Auth::user()->id || Entrust::hasRole("SUPER_ADMIN"))
	$('#password-reset-form').validate({
		
	});
	@endif
});
</script>
@endpush
