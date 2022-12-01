@extends('la.layouts.app')

@section('htmlheader_title')
	Region View
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
					<h4 class="name">{{ $region->$view_col }}</h4>
					<p class="desc">{{$region->description}}</p>
				</div>
			</div>
		</div>
		<div class="col-md-3"> </div>
		<div class="col-md-4"> </div>
		<div class="col-md-1 actions">
			@if(LAFormMaker::la_access("Regions", "edit"))
				<a href="{{ url(config('laraadmin.adminRoute') . '/regions/'.$region->id.'/edit') }}" class="btn btn-xs btn-edit btn-default"><i class="fa fa-pencil"></i></a><br>
			@endif

			@if(LAFormMaker::la_access("Regions", "delete"))
				{{ Form::open(['route' => [config('laraadmin.adminRoute') . '.regions.destroy', $region->id], 'method' => 'delete', 'style'=>'display:inline', 'onsubmit'=>$deleteMessage]) }}
					<button class="btn btn-default btn-delete btn-xs" type="submit"><i class="fa fa-times"></i></button>
				{{ Form::close() }}
			@endif
		</div>
	</div>

	<ul data-toggle="ajax-tab" class="nav nav-tabs profile" role="tablist">
		<li class=""><a href="{{ url(config('laraadmin.adminRoute') . '/regions') }}" data-toggle="tooltip" data-placement="right" title="Back to Regions"><i class="fa fa-chevron-left"></i></a></li>
		<li class="active"><a role="tab" data-toggle="tab" class="active" href="#tab-general-info" data-target="#tab-info"><i class="fa fa-bars"></i> General Info</a></li>
		{{--<li class=""><a role="tab" data-toggle="tab" href="#tab-timeline" data-target="#tab-timeline"><i class="fa fa-clock-o"></i> Timeline</a></li>--}}
	</ul>

	<div class="tab-content">
		<div role="tabpanel" class="tab-pane active fade in" id="tab-info">
			<div class="tab-content">
				<div class="panel infolist">
					<div class="panel-default panel-heading">
						<h4>General Info</h4>
					</div>
					<div class="panel-body">
						{!! LAFormMaker::display($module, 'country') !!}
						{!! LAFormMaker::display($module, 'name') !!}
						{!! LAFormMaker::display($module, 'url_slug') !!}
						{!! LAFormMaker::display($module, 'description') !!}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
