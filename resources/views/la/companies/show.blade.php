@extends('la.layouts.app')

@section('htmlheader_title')
	Company View
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
					<h3 class="name">{{ $company->$view_col }} 
						<span class="h5">
							@if($company->is_banned==0)
								<span class="label label-success">Allowed </span>
							@else
								<span class="label label-danger"> Banned </span>	
							@endif
						</span>
					</h3>
					<?php /*div class="row stats">
						<div class="col-md-4"><i class="fa fa-facebook"></i> 234</div>
						<div class="col-md-4"><i class="fa fa-twitter"></i> 12</div>
						<div class="col-md-4"><i class="fa fa-instagram"></i> 89</div>
					</div>
					<p class="desc">Test Description in one line</p*/ ?>
				</div>
			</div>
		</div>
		<div class="col-md-3">
		</div>
		<div class="col-md-4">
		</div>
		<div class="col-md-1 actions">
			@if(!empty($user) && $user->roles->first()->name == 'PARTNER' && $user->company_id != 0 && $user->company_id == $company->id)
				<a href="{{ url(config('laraadmin.adminRoute') . '/companies/'.$company->id.'/edit') }}" class="btn btn-xs btn-edit btn-default"><i class="fa fa-pencil"></i></a><br>
			@else
			@if(LAFormMaker::la_access("Companies", "edit"))
				<a href="{{ url(config('laraadmin.adminRoute') . '/companies/'.$company->id.'/edit') }}" class="btn btn-xs btn-edit btn-default"><i class="fa fa-pencil"></i></a><br>
			@endif
			@endif

			@if(LAFormMaker::la_access("Companies", "delete"))
				{{ Form::open(['route' => [config('laraadmin.adminRoute') . '.companies.destroy', $company->id], 'method' => 'delete', 'style'=>'display:inline', 'onsubmit'=>$deleteMessage]) }}
					<button class="btn btn-default btn-delete btn-xs" type="submit"><i class="fa fa-times"></i></button>
				{{ Form::close() }}
			@endif
		</div>
	</div>	
	<ul data-toggle="ajax-tab" class="nav nav-tabs profile companies_tab" role="tablist">
		<li class=""><a href="{{ url(config('laraadmin.adminRoute') . '/companies') }}" data-toggle="tooltip" data-placement="right" title="Back to Companies"><i class="fa fa-chevron-left"></i></a></li>
		<li class="active"><a role="tab" data-toggle="tab" class="active" href="#tab-general-info" data-target="#tab-info"><i class="fa fa-bars"></i> General Info</a></li>
		<?php /*li class=""><a role="tab" data-toggle="tab" href="#tab-timeline" data-target="#tab-timeline"><i class="fa fa-clock-o"></i> Timeline</a></li*/ ?>
		<li class=""><a role="tab" data-toggle="tab" href="#tab-projects" data-target="#tab-projects"><i class="fa fa-area-chart"></i> Projects</a></li>
		@if(isset($contact_cols))
		<li class=""><a role="tab" data-toggle="tab" href="#tab-contacts" data-target="#tab-contacts"><i class="fa fa-users"></i> Contacts</a></li>
		@endif
		@if(Auth::user()->canAccess())
		<li class="">
			<a role="tab" data-toggle="tab" href="#tab-tracking-instructions" data-target="#tab-tracking-instructions"><i class="fa fa-code"></i> Tracking Instructions</a>
		</li>
		@else
		<li>
			<a class="disable-feature-popup"><i class="fa fa-code"></i> Tracking Instructions</a>
		</li>
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
						{!! LAFormMaker::display($module, 'email') !!}
						{!! LAFormMaker::display($module, 'homepage') !!}
						{!! LAFormMaker::display($module, 'address') !!}
						{{--{!! LAFormMaker::display($module, 'address2') !!}--}}
						{!! LAFormMaker::display($module, 'city') !!}
						{!! LAFormMaker::display($module, 'state') !!}
						{!! LAFormMaker::display($module, 'country') !!}
						{!! LAFormMaker::display($module, 'zipcode') !!}
						{!! LAFormMaker::display($module, 'phone') !!}
						{{--{!! LAFormMaker::display($module, 'fax') !!}--}}
						{{--{!! LAFormMaker::display($module, 'logo_url') !!}--}}
						<div class="form-group">
							<label for="type" class="col-md-2">Type :</label>
							<div class="col-md-10 fvalue">{{$company->getType()}}</div>
						</div>
						<div class="form-group">
							<label for="type" class="col-md-2">Plan :</label>
							<div class="col-md-10 fvalue">{{$company->getPlanType()}}</div>
						</div>
						<div class="form-group">
							<label for="language" class="col-md-2">Language :</label>
							<div class="col-md-10 fvalue">{{$company->getLanguageName()}}</div>
						</div>
						{!! LAFormMaker::display($module, 'channel') !!}
						{!! LAFormMaker::display($module, 'strategic') !!}
					</div>
				</div>
			</div>
		</div>
		<div role="tabpanel" class="tab-pane fade in p20 bg-white" id="tab-projects">
			<div class="box box-success">
				<!--<div class="box-header"></div>-->
				<div class="box-body">
					<table id="projects" class="table table-bordered">
					<thead>
					<tr class="success">
						@foreach( $projects_cols as $col )
						<th>{{ $modulep->fields[$col]['label'] or ucfirst($col) }}</th>
						@endforeach
						@if($project_actions)
						<th>Actions</th>
						@endif
					</tr>
					</thead>
					<tbody>
						
					</tbody>
					</table>
				</div>
			</div>
		</div>
		@if(isset($contact_cols))
		<div role="tabpanel" class="tab-pane fade in p20 bg-white" id="tab-contacts">
			<div class="box box-success">
				<!--<div class="box-header"></div>-->
				<div class="box-body">
					<table id="contacts" class="table table-bordered">
					<thead>
					<tr class="success">
						@foreach( $contact_cols as $col )
						<th>{{ $modulep->fields[$col]['label'] or str_replace('_',' ',ucfirst($col))  }}</th>
						@endforeach
						@if($contact_actions)
						<th>Actions</th>
						@endif
					</tr>
					</thead>
					<tbody>
						
					</tbody>
					</table>
				</div>
			</div>
		</div>
		@endif

		@if(Auth::user()->canAccess())
		<div role="tabpanel" class="tab-pane fade in p20 bg-white" id="tab-tracking-instructions">
			<div class="box box-success">
				<div class="box-body">
					<div class="box box-solid">
						<div class="box-header with-border">
							<i class="fa fa-code"></i>
							<h3 class="box-title">Introduction for Tracking User Activity</h3>
						</div>
						<div class="box-body">
							<blockquote>
								<p>Knowing the user's every move: User activity tracking for website usability
									evaluation and implicit interaction.</p>
								<small>Add code after body tag close.</small>
								<small>Copy Below code and add into your website.</small>
							</blockquote>
							<xmp><script type="text/javascript" id="partner-tracking-script" class="{{\App\Helpers\ShortLink::getProjectKey($company->id)}}" src="https://eterni.tech/assets/js/ettracker.js"></script></xmp>
						</div>
					</div>
				</div>
			</div>
		</div>
		@endif
	</div>
</div>
@endsection
@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('la-assets/plugins/datatables/datatables.min.css') }}"/>

<style type="text/css">
xmp {
	background: black;
	color: white;
	border: 3px solid red;
	padding: 10px;
}
</style>

@endpush

@push('scripts')
<script src="{{ asset('la-assets/plugins/datatables/datatables.min.js') }}"></script>
<script>
$(function () {
	$('ul.companies_tab li a').click(function(){
		var id = $(this).attr('href');
		if(id=='#tab-projects'){
			$(id).find('table#projects').attr('style','width:100%;');
		}else if(id=='#tab-contacts'){
			$(id).find('table#contacts').attr('style','width:100%;');
		}
	})
	$("#projects").DataTable({
		processing: true,
        serverSide: true,
        ajax: "{{ url(config('laraadmin.adminRoute') . '/project_dt_ajax?company_id='.$company->id) }}",
		language: {
			paginate: {
				next: '&#8594;', // or '→'
				previous: '&#8592;' // or '←'
			},
			lengthMenu: "_MENU_",
			search: "_INPUT_",
			searchPlaceholder: "Search"
		},
		@if($project_actions)
		columnDefs: [ { orderable: false, targets: [-1] }],
		@endif
	});
	$("#contacts").DataTable({
		processing: true,
        serverSide: true,
        ajax: "{{ url(config('laraadmin.adminRoute') . '/contact_dt_ajax?company_id='.$company->id) }}",
		language: {
			paginate: {
				next: '&#8594;', // or '→'
				previous: '&#8592;' // or '←'
			},
			lengthMenu: "_MENU_",
			search: "_INPUT_",
			searchPlaceholder: "Search"
		},
		@if(isset($contact_actions))
		columnDefs: [ { orderable: false, targets: [-1] }],
		@endif
	});
});
</script>
@endpush