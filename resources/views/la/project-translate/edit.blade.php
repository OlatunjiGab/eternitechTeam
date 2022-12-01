@extends("la.layouts.app")

@section("contentheader_title")
	<a href="{{ route(config('laraadmin.adminRoute') . '.project-translate.index') }}">Leads Training</a> :
@endsection
@section("contentheader_description", $projectTranslated->name)
@section("section", "Leads Training")
@section("section_url", url(config('laraadmin.adminRoute') . '/project-translate'))
@section("sub_section", "Edit")

@section("htmlheader_title", "Leads Training Edit : ".$projectTranslated->name)

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
	<div class="box-header">
		<a href="@if(empty($getNextLead->url)) {{ route(config('laraadmin.adminRoute') . '.projects.show', $projectTranslated->id) }} @else {{ $getNextLead->url }} @endif" class="@if(empty($getNextLead->url)) btn btn-success btn-sm pull-right @else pull-right @endif" target="_blank">
			@if(empty($getNextLead->url))
				Lead Edit
			@else
				{{$getNextLead->url}}
			@endif
		</a>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-md-10 col-md-offset-1">
				{!! Form::model('',['route' => [config('laraadmin.adminRoute') . '.project-translate.update', $projectTranslated->id],'method' => 'PATCH','files' => true]) !!}
				<input type="hidden" name="is_trained" value="1">
				<input type="hidden" name="is_manual" value="1">
				<div class="panel panel-default ">
					<div class="panel-body">
						<div class="form-group {{ $errors->has('id') ? ' has-error' : '' }}">
							<label for="id">ID :</label>
							<input type="text" name="id" class="form-control" value="{{old('id')??$projectTranslated->id}}" readonly>
						</div>
						<div class="form-group">
							<label for="lead_link">Lead Link :</label>
							<input type="text" name="" class="form-control" value="{{url(config('laraadmin.adminRoute'). '/projects/' . $projectTranslated->id)}}" readonly>
						</div>
						<div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
							<label for="name">Name :</label>
							<input type="text" name="name" class="form-control" value="{{old('name')??$projectTranslated->name}}" readonly>
						</div>
						<div class="form-group {{ $errors->has('channel') ? ' has-error' : '' }}">
							<label for="name">Channel :</label>
							<input type="text" name="channel" class="form-control" value="{{old('channel')??$projectTranslated->channel}}" readonly>
						</div>
						<div class="form-group {{ $errors->has('description') ? ' has-error' : '' }}">
							<label for="description">Description :</label>
							<textarea class="form-control textarea" name="description" rows="4" readonly>{{old('description') ?? trim($projectTranslated->description)}}</textarea>
						</div>
						<div class="form-group {{ $errors->has('categories') ? ' has-error' : '' }}">
							<label for="categories">Categories :</label>
							<input type="text" name="categories" class="form-control" value="{{old('categories')??$projectTranslated->categories}}" readonly>
						</div>
						<div class="form-group {{ $errors->has('is_it_related') ? ' has-error' : '' }}">
							<label for="remote">Is IT Related :</label>
							<select class="form-control" name="is_it_related" disabled>
								<option value="Yes" @if(!empty($projectTranslated->is_it_related)) {{$projectTranslated->is_it_related == 'Yes'? 'selected' : ''}} @endif >Yes</option>
								<option value="No" @if(!empty($projectTranslated->is_it_related)) {{$projectTranslated->is_it_related == 'No'? 'selected' : ''}} @endif >No</option>
							</select>
						</div>
						<div class="form-group {{ $errors->has('skills') ? ' has-error' : '' }}">
							<label for="skills">Skills :</label>
							<select class="form-control" name="skills[]" multiple="" rel="select2">
								@foreach($skillList as $key => $skill)
									<option value="{{$skill['id']}}" {{ in_array($skill['id'],$selectedSkills)? 'selected': ''}} >{{$skill['keyword']}}</option>
								@endforeach
							</select>
							@if($errors->has('skills'))
								<span class="help-block">
								<strong>{{ $errors->first('skills') }}</strong>
							</span>
							@endif
						</div>

						<div class="form-group {{ $errors->has('service') ? ' has-error' : '' }}">
							<label for="service">Service :</label>
							<select class="form-control" name="service[]" multiple="" rel="select2">
								@foreach($service as $key => $value)
									<option value="{{$value}}" {{ in_array($value,$selectedServices)? 'selected': ''}} >{{$value}}</option>
								@endforeach
							</select>
							@if($errors->has('service'))
								<span class="help-block">
								<strong>{{ $errors->first('service') }}</strong>
							</span>
							@endif
						</div>
						<div class="form-group {{ $errors->has('competition') ? ' has-error' : '' }}">
							<label for="url">Competition :</label>
							<input type="number" name="competition" class="form-control" placeholder="Enter Competition" value="{{old('competition')??$projectTranslated->competition}}" min="0" max="10">
							@if($errors->has('competition'))
								<span class="help-block">
								<strong>{{ $errors->first('competition') }}</strong>
							</span>
							@endif
						</div>
						<div class="form-group {{ $errors->has('project_type') ? ' has-error' : '' }}">
							<label for="project_type">Project Type :</label>
							<select class="form-control" name="project_type">
								@foreach($projectType as $key => $type)
									<option value="{{$type}}" @if(!empty($projectTranslated->project_type)) {{$projectTranslated->project_type == $type? 'selected' : ''}} @endif >{{$type}}</option>
								@endforeach
							</select>
							@if($errors->has('project_type'))
								<span class="help-block">
								<strong>{{ $errors->first('project_type') }}</strong>
							</span>
							@endif
						</div>
						<div class="form-group {{ $errors->has('remote') ? ' has-error' : '' }}">
							<label for="remote">Remote :</label>
							<select class="form-control" name="remote">
								<option value="Yes" @if(!empty($projectTranslated->remote)) {{$projectTranslated->remote == 'Yes'? 'selected' : ''}} @endif >Yes</option>
								<option value="No" @if(!empty($projectTranslated->remote)) {{$projectTranslated->remote == 'No'? 'selected' : ''}} @endif >No</option>
								<option value="Not Available" @if(!empty($projectTranslated->remote)) {{$projectTranslated->remote == 'Not Available'? 'selected' : ''}} @endif >Not Available</option>
							</select>
							@if($errors->has('remote'))
								<span class="help-block">
								<strong>{{ $errors->first('remote') }}</strong>
							</span>
							@endif
						</div>
						<div class="form-group {{ $errors->has('provider_type') ? ' has-error' : '' }}">
							<label for="provider_type">Provider Type :</label>
							<select class="form-control" name="provider_type">
								@foreach($providerType as $key => $type)
									<option value="{{$type}}" @if(!empty($projectTranslated->provider_type)) {{$projectTranslated->provider_type == $type? 'selected' : ''}} @endif >{{$type}}</option>
								@endforeach
							</select>
							@if($errors->has('provider_type'))
								<span class="help-block">
								<strong>{{ $errors->first('provider_type') }}</strong>
							</span>
							@endif
						</div>
						<div class="form-group {{ $errors->has('provider_experience') ? ' has-error' : '' }}">
							<label for="provider_experience">Provider Experience :</label>
							<select class="form-control" name="provider_experience" rel="select2">
								@foreach($providerExperience as $key => $value)
									<option value="{{$value}}" @if(!empty($projectTranslated->provider_experience)) {{$projectTranslated->provider_experience == $value? 'selected' : ''}} @endif >{{$value}}</option>
								@endforeach
							</select>
							@if($errors->has('provider_experience'))
								<span class="help-block">
								<strong>{{ $errors->first('provider_experience') }}</strong>
							</span>
							@endif
						</div>
						<div class="form-group {{ $errors->has('qualification') ? ' has-error' : '' }}">
							<label for="qualification">Qualification :</label>
							<select class="form-control" name="qualification">
								@foreach($qualification as $key => $value)
									<option value="{{$value}}" @if(!empty($projectTranslated->qualification)) {{$projectTranslated->qualification == $value? 'selected' : ''}} @endif >{{$value}}</option>
								@endforeach
							</select>
							@if($errors->has('qualification'))
								<span class="help-block">
								<strong>{{ $errors->first('qualification') }}</strong>
							</span>
							@endif
						</div>
						<div class="form-group {{ $errors->has('project_length') ? ' has-error' : '' }}">
							<label for="project_length">Project Length :</label>
							<input type="number" name="project_length" class="form-control" placeholder="Enter Project Length" value="{{old('project_length')??$projectTranslated->project_length}}" min="1" max="10">
							@if($errors->has('project_length'))
								<span class="help-block">
								<strong>{{ $errors->first('project_length') }}</strong>
							</span>
							@endif
						</div>
						<div class="form-group {{ $errors->has('project_state') ? ' has-error' : '' }}">
							<label for="project_state">Project State :</label>
							<select class="form-control" name="project_state">
								@foreach($projectState as $key => $value)
									<option value="{{$value}}" @if(!empty($projectTranslated->project_state)) {{$projectTranslated->project_state == $value? 'selected' : ''}} @endif >{{$value}}</option>
								@endforeach
							</select>
							@if($errors->has('project_state'))
								<span class="help-block">
								<strong>{{ $errors->first('project_state') }}</strong>
							</span>
							@endif
						</div>
						<div class="form-group {{ $errors->has('project_urgency') ? ' has-error' : '' }}">
							<label for="project_urgency">Project Urgency :</label>
							<input type="number" name="project_urgency" class="form-control" placeholder="Enter Project Urgency" value="{{old('project_urgency')??$projectTranslated->project_urgency}}" min="0" max="10">
							@if($errors->has('project_urgency'))
								<span class="help-block">
								<strong>{{ $errors->first('project_urgency') }}</strong>
							</span>
							@endif
						</div>
						<div class="form-group {{ $errors->has('budget') ? ' has-error' : '' }}">
							<label for="budget">Budget :</label>
							<input type="number" name="budget" class="form-control" placeholder="Enter Budget" value="{{old('budget')??$projectTranslated->budget}}" min="0" max="10">
							@if($errors->has('budget'))
								<span class="help-block">
								<strong>{{ $errors->first('budget') }}</strong>
							</span>
							@endif
						</div>
						<div class="form-group {{ $errors->has('client_knowlegeable') ? ' has-error' : '' }}">
							<label for="client_knowlegeable">Client Knowlegeable :</label>
							<input type="number" name="client_knowlegeable" class="form-control" placeholder="Enter Client Knowlegeable" value="{{old('client_knowlegeable')??$projectTranslated->client_knowlegeable}}" min="0" max="10">
							@if($errors->has('client_knowlegeable'))
								<span class="help-block">
								<strong>{{ $errors->first('client_knowlegeable') }}</strong>
							</span>
							@endif
						</div>
						<div class="form-group {{ $errors->has('client_experience_with_dev') ? ' has-error' : '' }}">
							<label for="client_experience_with_dev">Client Experience with dev :</label>
							<input type="number" name="client_experience_with_dev" class="form-control" placeholder="Enter Client Experience with dev" value="{{old('client_experience_with_dev')??$projectTranslated->client_experience_with_dev}}" min="0" max="10">
							@if($errors->has('client_experience_with_dev'))
								<span class="help-block">
								<strong>{{ $errors->first('client_experience_with_dev') }}</strong>
							</span>
							@endif
						</div>
						<div class="form-group {{ $errors->has('industry') ? ' has-error' : '' }}">
							<label for="industry">Industry :</label>
							<textarea class="form-control textarea" name="industry" rows="3" placeholder="Enter Industry">{{old('industry')??$projectTranslated->industry}}</textarea>
							@if($errors->has('industry'))
								<span class="help-block">
								<strong>{{ $errors->first('industry') }}</strong>
							</span>
							@endif
						</div>
						<div class="form-group {{ $errors->has('is_client_it_company') ? ' has-error' : '' }}">
							<label for="remote">Client is IT company :</label>
							<select class="form-control" name="is_client_it_company">
								<option value="Yes" @if(!empty($projectTranslated->is_client_it_company)) {{$projectTranslated->is_client_it_company == 'Yes'? 'selected' : ''}} @endif >Yes</option>
								<option value="No" @if(!empty($projectTranslated->is_client_it_company)) {{$projectTranslated->is_client_it_company == 'No'? 'selected' : ''}} @endif >No</option>
								<option value="Not Available" @if(!empty($projectTranslated->is_client_it_company)) {{$projectTranslated->is_client_it_company == 'Not Available'? 'selected' : ''}} @endif >Not Available</option>
							</select>
							@if($errors->has('is_client_it_company'))
								<span class="help-block">
								<strong>{{ $errors->first('is_client_it_company') }}</strong>
							</span>
							@endif
						</div>
						<div class="form-group {{ $errors->has('client_company_size') ? ' has-error' : '' }}">
							<label for="client_company_size">Client Company size :</label>
							<input type="number" name="client_company_size" class="form-control" placeholder="Enter Client Company size" value="{{old('client_company_size')??$projectTranslated->client_company_size}}" min="0" max="10">
							@if($errors->has('client_company_size'))
								<span class="help-block">
								<strong>{{ $errors->first('client_company_size') }}</strong>
							</span>
							@endif
						</div>
						<div class="form-group">
							{!! Form::submit( 'Save', ['class'=>'btn btn-success']) !!} <button class="btn btn-default pull-right"><a href="{{ route(config('laraadmin.adminRoute') . '.project-translate.index') }}">Cancel</a></button>
						</div>
					</div>
				</div>
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@endsection
@push('styles')
	<link rel="stylesheet" type="text/css" href="{{ asset('la-assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') }}"/>
@endpush
@push('scripts')
	<script src="{{asset('la-assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js')}}"></script>
	<script>
		$(function () {
		});
	</script>
@endpush