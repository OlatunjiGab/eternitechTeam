@extends('la.layouts.app')

@section('htmlheader_title')
	Expert View
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
					<h4 class="name">{{ $expert->first_name." ".$expert->last_name }}</h4>
					@php
						$headline = (strlen($expert->headline) > 85) ? substr($expert->headline,0,80).'...' : $expert->headline;
					@endphp
					<p class="desc">{{$headline}}</p>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			@if(!empty($expert->$view_col))
			<div class="dats1"><div class="label2">{{$expert->$view_col}}</div></div>
			@endif
			@if(!empty($expert->email))
			<div class="dats1"><i class="fa fa-envelope-o"></i> {{$expert->email}} </div>
			@endif
			@if(!empty($expert->country))
			<div class="dats1"><i class="fa fa-map-marker"></i> {{$expert->country_data->name}}</div>
			@endif

		</div>
		<div class="col-md-4">
		</div>
		<div class="col-md-1 actions">
			@if(LAFormMaker::la_access("Experts", "edit"))
				<a href="{{ url(config('laraadmin.adminRoute') . '/experts/'.$expert->id.'/edit') }}" class="btn btn-xs btn-edit btn-default"><i class="fa fa-pencil"></i></a><br>
			@endif
			
			@if(LAFormMaker::la_access("Experts", "delete"))
				{{ Form::open(['route' => [config('laraadmin.adminRoute') . '.experts.destroy', $expert->id], 'method' => 'delete', 'style'=>'display:inline','onsubmit'=>$deleteMessage]) }}
					<button class="btn btn-default btn-delete btn-xs" type="submit"><i class="fa fa-times"></i></button>
				{{ Form::close() }}
			@endif
		</div>
	</div>

	<ul data-toggle="ajax-tab" class="nav nav-tabs profile" role="tablist">
		<li class=""><a href="{{ url(config('laraadmin.adminRoute') . '/experts') }}" data-toggle="tooltip" data-placement="right" title="Back to Experts"><i class="fa fa-chevron-left"></i></a></li>
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
						{!! LAFormMaker::display($module, 'first_name') !!}
						{!! LAFormMaker::display($module, 'last_name') !!}
						{!! LAFormMaker::display($module, 'country') !!}
						{!! LAFormMaker::display($module, 'email') !!}
						{!! LAFormMaker::display($module, 'phone') !!}
						{{--{!! LAFormMaker::display($module, 'regions') !!}--}}
						{!! LAFormMaker::display($module, 'partner') !!}
						{!! LAFormMaker::display($module, 'skills') !!}
						{{--{!! LAFormMaker::display($module, 'url_slug') !!}--}}
						{!! LAFormMaker::display($module, 'headline') !!}
						{!! LAFormMaker::display($module, 'description') !!}
						{!! LAFormMaker::display($module, 'experience_start') !!}
						{!! LAFormMaker::display($module, 'experience') !!}
						{!! LAFormMaker::display($module, 'monthly_rate') !!}
						{!! LAFormMaker::display($module, 'hourly_rate') !!}
						<div class="form-group">
							<label for="hourly_rate" class="col-md-2">Youtube Embed :</label>
							<div class="col-md-10 fvalue">{{$expert->youtube_embed}}</div>
						</div>
						{!! LAFormMaker::display($module, 'original_cv_file') !!}
						{!! LAFormMaker::display($module, 'publish_type') !!}
						@if($expert->image_url)
							<div class="form-group">
								<label for="image_url" class="col-md-2">Profile:</label>
								<div class="col-md-10 fvalue">
									<img src="{{$expert->image_url }}" height="auto" width="150" />
								</div>
							</div>
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
