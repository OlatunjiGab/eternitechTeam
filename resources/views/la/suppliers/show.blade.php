@extends('la.layouts.app')

@section('htmlheader_title')
	Partners View
@endsection


@section('main-content')
<div id="page-content" class="profile2">
	<div class="bg-primary clearfix">
		<div class="col-md-4">
			<div class="row">
				<div class="col-md-3">
					<div class="profile-icon text-primary"><i class="fa {{ $module->fa_icon }}"></i></div>
				</div>
				<div class="col-md-9">
					<h4 class="name">{{ $supplier->partner_first_name }}</h4>
					<h4 class="name"><a class="text-white" href="{{ $company->agency_website }}">{{ $company->agency_website }}</a></h4>
				</div>
			</div>
		</div>
		<?php /* ?>
		<div class="col-md-3">
			<div class="dats1"><div class="label2">Admin</div></div>
			<div class="dats1"><i class="fa fa-envelope-o"></i> superadmin@gmail.com</div>
			<div class="dats1"><i class="fa fa-map-marker"></i> Pune, India</div>
		</div>
		<?php */ ?>
		<div class="col-md-7"> </div>
		<div class="col-md-1 actions">
			@if(LAFormMaker::la_access("Suppliers", "edit"))
				<a href="{{ url(config('laraadmin.adminRoute') . '/partners/'.$supplier->id.'/edit') }}" class="btn btn-xs btn-edit btn-default"><i class="fa fa-pencil"></i></a><br>
			@endif

			@if(LAFormMaker::la_access("Suppliers", "delete"))
				{{ Form::open(['route' => [config('laraadmin.adminRoute') . '.partners.destroy', $supplier->id], 'method' => 'delete', 'style'=>'display:inline','onsubmit'=>$deleteMessage]) }}
					<button class="btn btn-default btn-delete btn-xs" type="submit"><i class="fa fa-times"></i></button>
				{{ Form::close() }}
			@endif
			@if($UserSupplierDept->dept == 1)
				<?= '<a href="/impersonate/user/'.$UserSupplierID->user_id.'" class="btn btn-xs btn-edit btn-default"><i class="fa fa-sign-in"></i></a>'; ?>
			@endif
		</div>
	</div>

	<ul data-toggle="ajax-tab" class="nav nav-tabs profile supplier_tab" role="tablist">
		<li class=""><a href="{{ url(config('laraadmin.adminRoute') . '/partners') }}" data-toggle="tooltip" data-placement="right" title="Back to Suppliers"><i class="fa fa-chevron-left"></i></a></li>
		<li class="active"><a role="tab" data-toggle="tab" class="active" href="#tab-general-info" data-target="#tab-info"><i class="fa fa-bars"></i> General Info</a></li>
		 @if($user_role != 2)
		<li class=""><a role="tab" data-toggle="tab" href="#tab-users" data-target="#tab-users"><i class="fa fa-users"></i> Users</a></li>
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
						<div class="form-group"><label class="col-md-2">Prospect Name :</label><div class="col-md-10 fvalue"><a href="@if($user_role != 2){{ url(config('laraadmin.adminRoute') . '/companies/'.$company->id) }}@endif"> {{ $company->name }} {{$company->email ? '('.$company->email.')':''}}</a></div></div>
						{!! LAFormMaker::display($module, 'created_at') !!}
						{!! LAFormMaker::display($module, 'closing_rate') !!}
						{!! LAFormMaker::display($module, 'avg_response_time') !!}
						{!! LAFormMaker::display($module, 'hourly_rate') !!}

						<div class="box box-success">
							<div class="box-header">Partner Skills</div>
							<div class="box-body">
								<table id="table" class="table table-bordered">
									<thead>
									<tr class="success">
										<th>Id</th>
										<th>Partner Name</th>
										<th>skills</th>
										<th>Experience</th>
										<th>Rate</th>
										<th>Comment</th>
									</tr>
									</thead>
									<tbody>

									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div role="tabpanel" class="tab-pane fade in p20 bg-white" id="tab-users">
			<div class="box box-success">
				<!--<div class="box-header"></div>-->
				<div class="box-body">
					<table id="users" class="table table-bordered">
					<thead>
					<tr class="success">
						<th>Id</th>
						<th>Name</th>
						<th>Email</th>
						<th>UserType</th>
					</tr>
					</thead>
					<tbody>
					</tbody>
					</table>
				</div>
			</div>
		</div>
		
	</div>
	</div>
	</div>
</div>
@endsection
@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('la-assets/plugins/datatables/datatables.min.css') }}"/>
@endpush

@push('scripts')
<script src="{{ asset('la-assets/plugins/datatables/datatables.min.js') }}"></script>
<script>
$(function () {
	$('ul.supplier_tab li a').click(function(){
		var id = $(this).attr('href');
		if(id=='#tab-users'){
			$(id).find('table#users').attr('style','width:100%;');
		}
	})
	$("#users").DataTable({
		processing: true,
        serverSide: true,
        searching: false,
        info: false,
        ajax: "{{ url(config('laraadmin.adminRoute') . '/user_dt_ajax?company_id='.$supplier->company_id) }}",
		language: {
			paginate: {
				next: '&#8594;', // or '→'
				previous: '&#8592;' // or '←'
			},
			//lengthMenu: "_MENU_",
			//search: "_INPUT_",
			//searchPlaceholder: "Search"
		},
		columnDefs: [ { orderable: false, targets: [-1] }],
	});
});
$(function () {
	$("#table").DataTable({
		processing: true,
		serverSide: true,
		pageLength: 50,
		ajax: "{{ url(config('laraadmin.adminRoute') . '/partners-skills-ajax/'.$supplier->id) }}",
		language: {
			paginate: {
				next: '&#8594;', // or '→'
				previous: '&#8592;' // or '←'
			},
			lengthMenu: "_MENU_",
			search: "_INPUT_",
			searchPlaceholder: "Search"
		},

		columnDefs: [ { orderable: false, targets: [-1] }],

	});
	$("#supplier-add-form").validate({

	});
});
</script>
@endpush
