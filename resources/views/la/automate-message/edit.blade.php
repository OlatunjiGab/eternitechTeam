@extends("la.layouts.app")

@section("contentheader_title")
	<a href="{{ route(config('laraadmin.adminRoute') . '.automate-message.index') }}">Automate Template</a> :
@endsection
@section("section", "Automate Template")
@section("section_url", route(config('laraadmin.adminRoute') . '.automate-message.index'))
@section("sub_section", "Edit")

@section("htmlheader_title", "Automate Template Edit")

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

		</div>
		<div class="box-body">
			<div class="row">
				<div class="col-md-10 col-md-offset-1">
					{!! Form::model('',['route' => [config('laraadmin.adminRoute') . '.automate-message.update', $id],'method' => 'PATCH','files' => true]) !!}
					<div class="panel panel-default ">
						<div class="panel-body">

							<div class="form-group">
								<label for="name">Name:</label>
								<input class="form-control" placeholder="Name" name="name" type="text" value="{{$automatedMessage->name}}" >
								@if ($errors->has('name'))
									<span class="text-danger"><strong>{{ $errors->first('name') }}</strong></span>
								@endif
							</div>

							{{--/********************* LEAD FILTER ***********************/--}}
							*****************************************************************
							<div class="form-group">
								<label for="channel">Lead Channel:</label>
								<select class="form-control" data-placeholder="Select Lead Channel" rel="select2" name="lead_filter_channel">
									<option value="" disabled>Select Lead Channel</option>
									<option value="">All Channels</option>
									@foreach($channelList as $item)
										<option value="{{$item}}" {{ $automatedMessage->lead_filter_channel == $item? 'selected': ''}}>{{$item}}</option>
									@endforeach
								</select>
								@if ($errors->has('lead_filter_channel'))
									<span class="text-danger"><strong>{{ $errors->first('lead_filter_channel') }}</strong></span>
								@endif
							</div>

							<div class="form-group">
								<label for="channel">Lead Source:</label>
								<select class="form-control" data-placeholder="Select lead Source" rel="select2" name="lead_filter_source">
									<option value="" disabled>Select Lead Source</option>
									<option value="">All Sources</option>
									@foreach($leadSourceList as $key=>$source)
										<option value="{{$key}}" {{$automatedMessage->lead_filter_source == $key? 'selected': ''}}>{{$source}}</option>
									@endforeach
								</select>
								@if ($errors->has('lead_filter_source'))
									<span class="text-danger"><strong>{{ $errors->first('lead_filter_source') }}</strong></span>
								@endif
							</div>

							<div class="form-group">
								<label for="lead_filter_skills">Lead Skills:</label>
								<select class="form-control" data-placeholder="Select lead skills (leave empty for all)" rel="select2" name="lead_filter_skills[]" multiple="" >
									@foreach($skillList as $key=>$skill)
										<option value="{{$key}}" {{ in_array($key, $selectedSkills)?'selected=selected':'' }}>{{$skill}}</option>
									@endforeach
								</select>

								@if ($errors->has('lead_filter_skills'))
									<span class="text-danger"><strong>{{ $errors->first('lead_filter_skills') }}</strong></span>
								@endif
							</div>

							<div class="form-group">
								<label for="name">Lead Countries:</label>
								<input class="form-control" placeholder="Enter Lead Countries" name="lead_filter_countries" type="text" value="{{$automatedMessage->lead_filter_countries}}" >
								@if ($errors->has('lead_filter_countries'))
									<span class="text-danger"><strong>{{ $errors->first('lead_filter_countries') }}</strong></span>
								@endif
							</div>

							{{--/********************* TRIGGER ***********************/--}}
							*****************************************************************
							<div class="form-group">
								<label for="trigger_event_type">Trigger Event Type:</label>
								<select class="form-control" id ="trigger_event_type" data-placeholder="Select trigger event type" rel="select2" name="trigger_event_type">
									<option value="" disabled>Select Trigger Event Type</option>
									@foreach($triggerEventList as $key=>$value)
										<option value="{{$key}}" {{ $automatedMessage->trigger_event_type == $key ? 'selected': ''}}>{{$value}}</option>
									@endforeach
								</select>
								@if ($errors->has('trigger_event_type'))
									<span class="text-danger"><strong>{{ $errors->first('trigger_event_type') }}</strong></span>
								@endif
							</div>

							<div class="form-group" id="trigger_event_config_text">
								<label for="trigger_event_config">Trigger Event Config:</label>

								<input class="form-control"  placeholder="Enter Event Config (e.g. url to filter by)" name="trigger_event_config_text" type="text" value="{{$automatedMessage->trigger_event_config}}" >
								@if ($errors->has('trigger_event_config_text'))
									<span class="text-danger"><strong>{{ $errors->first('trigger_event_config_text') }}</strong></span>
								@endif
							</div>
							<div class="form-group" id="trigger_event_config_automated_message">
								<label for="trigger_event_config">Trigger Event Type:</label>

								<select class="form-control" data-placeholder="Select trigger event type" rel="select2" name="trigger_event_config_automated_message">
									<option value="" disabled>Select Trigger Event Type</option>
									@foreach($automatedMessagesList as $key=>$value)
										<option value="{{$key}}" {{ $automatedMessage->trigger_event_config == $key ? 'selected': ''}}>{{$value}}</option>
									@endforeach
								</select>
								@if ($errors->has('trigger_event_config'))
									<span class="text-danger"><strong>{{ $errors->first('trigger_event_config') }}</strong></span>
								@endif
							</div>

							{{--/********************* ACTION ***********************/--}}
							*****************************************************************
							<div class="form-group">
								<label for="channel">Action Template:</label>
								<select class="form-control" data-placeholder="Select action template" rel="select2" name="action_template_id">
									<option value="">Select Action Template:</option>
									@foreach($templates as $key=>$value)
										<option value="{{$key}}" {{ $automatedMessage->action_template_id == $key ? 'selected': ''}}>{{$value}}</option>
									@endforeach
								</select>
								@if ($errors->has('action_action_template_id'))
									<span class="text-danger"><strong>{{ $errors->first('action_template_id') }}</strong></span>
								@endif
							</div>

							<div class="form-group">
								<label for="channel">Action Messaging Channel:</label>
								<select class="form-control" data-placeholder="Select messaging channel" rel="select2" name="action_message_channel">
									<option value="">Select Action Delay Unit</option>
									@foreach($messageChannelList as $messageChannel)
										<option value="{{$messageChannel}}" {{ $automatedMessage->action_message_channel == $messageChannel ? 'selected': ''}}>{{$messageChannel}}</option>
									@endforeach
								</select>
								@if ($errors->has('action_message_channel'))
									<span class="text-danger"><strong>{{ $errors->first('action_message_channel') }}</strong></span>
								@endif
							</div>

							<div class="form-group">
								<label for="channel">Action Delay Unit:</label>
								<select class="form-control" data-placeholder="Select action delay unit" rel="select2" name="action_delay_unit">
									<option value="">Select Action Delay Unit</option>
									@foreach($delayUnitList as $key=>$value)
										<option value="{{$key}}" {{ $automatedMessage->action_delay_unit == $key ? 'selected': ''}}>{{$value}}</option>
									@endforeach
								</select>
								@if ($errors->has('action_delay_unit'))
									<span class="text-danger"><strong>{{ $errors->first('action_delay_unit') }}</strong></span>
								@endif
							</div>

							<div class="form-group">
								<label for="name">Action Delay:</label>
								<input class="form-control" placeholder="Enter value" name="action_delay" type="text" value="{{$automatedMessage->action_delay}}" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" >
								@if ($errors->has('action_delay'))
									<span class="text-danger"><strong>{{ $errors->first('action_delay') }}</strong></span>
								@endif
							</div>

							<div class="form-group">
								<label for="name">Action Sender Email:</label>
								<input class="form-control" placeholder="Enter Sender Email" name="action_sender_email" type="text" value="{{$automatedMessage->action_sender_email}}" >
								@if ($errors->has('action_sender_email'))
									<span class="text-danger"><strong>{{ $errors->first('action_sender_email') }}</strong></span>
								@endif
							</div>
							<div class="form-group">
								<label for="name">Action Sender Name:</label>
								<input class="form-control" placeholder="Enter Sender Name" name="action_sender_name" type="text" value="{{$automatedMessage->action_sender_name}}" >
								@if ($errors->has('action_sender_name'))
									<span class="text-danger"><strong>{{ $errors->first('action_sender_name') }}</strong></span>
								@endif
							</div>
							<div class="form-group">
								{!! Form::submit( 'Save', ['class'=>'btn btn-success']) !!} <button class="btn btn-default pull-right"><a href="{{ route(config('laraadmin.adminRoute') . '.automate-message.index') }}">Cancel</a></button>
							</div>
						</div>
					</div>
					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>

@endsection

@push('scripts')
<script>
    function toggleEventConfigField() {
        if($( "#trigger_event_type" ).val() == "{{\App\Models\ProjectMessage::EVENT_TYPE_AUTO_FOLLOWUP}}") {
            $("#trigger_event_config_automated_message").show();
            $("#trigger_event_config_text").hide();
        } else {
            $("#trigger_event_config_automated_message").hide();
            $("#trigger_event_config_text").show();
        }
    }
    $(function () {
        $( "#trigger_event_type" ).change(toggleEventConfigField);
        toggleEventConfigField();
    });
</script>
@endpush