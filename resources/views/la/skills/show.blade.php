@extends('la.layouts.app')

@section('htmlheader_title')
	Skill View
@endsection


@section('main-content')
<div id="page-content" class="profile2">
	<div class="bg-primary clearfix">
		<div class="col-md-4">
			<div class="row">
				<div class="col-md-3">
					<!--<img class="profile-image" src="{{ asset('la-assets/img/avatar5.png') }}" alt="">-->
					<div class="profile-icon text-primary"><i class="fa {{ $module->fa_icon }}"></i></div>
				</div>
				<div class="col-md-9">
					<h4 class="name">{{ $skill->$view_col }}</h4>
					<p class="desc">{{ $skill->reply_text }}</p>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="dats1"><div class="label2"><a href="{{ $skill->url }}" target="_blank">{{ $skill->url }}</a> </div></div>
		</div>
		<div class="col-md-4">
		</div>
		<div class="col-md-1 actions">
			@if(LAFormMaker::la_access("Skills", "edit"))
				<a href="{{ url(config('laraadmin.adminRoute') . '/skills/'.$skill->id.'/edit') }}" class="btn btn-xs btn-edit btn-default"><i class="fa fa-pencil"></i></a><br>
			@endif

			@if(LAFormMaker::la_access("Skills", "delete"))
				{{ Form::open(['route' => [config('laraadmin.adminRoute') . '.skills.destroy', $skill->id], 'method' => 'delete', 'style'=>'display:inline', 'onsubmit'=>$deleteMessage]) }}
					<button class="btn btn-default btn-delete btn-xs" type="submit"><i class="fa fa-times"></i></button>
				{{ Form::close() }}
			@endif
		</div>
	</div>

	<ul data-toggle="ajax-tab" class="nav nav-tabs profile" role="tablist">
		<li class=""><a href="{{ url(config('laraadmin.adminRoute') . '/skills') }}" data-toggle="tooltip" data-placement="right" title="Back to Skills"><i class="fa fa-chevron-left"></i></a></li>
		<li class="active"><a role="tab" data-toggle="tab" class="active" href="#tab-general-info" data-target="#tab-info"><i class="fa fa-bars"></i> General Info</a></li>
	</ul>

	<div class="tab-content">
		<div role="tabpanel" class="tab-pane active fade in" id="tab-info">
			<div class="tab-content">
				<div class="panel infolist">
					<div class="panel-default panel-heading">
						<h4>General Info</h4>
					</div>
					<div class="panel-body">
						{!! LAFormMaker::display($module, 'keyword') !!}
						{!! LAFormMaker::display($module, 'description') !!}
						{!! LAFormMaker::display($module, 'keywords') !!}
						{!! LAFormMaker::display($module, 'hourly_rate') !!}
						{!! LAFormMaker::display($module, 'reply_text') !!}
						{!! LAFormMaker::display($module, 'url') !!}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
