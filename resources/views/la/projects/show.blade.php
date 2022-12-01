@extends('la.layouts.app')

@section('htmlheader_title')
	Project View
@endsection
@section('main-content')
<style type="text/css">
#loader {
  display: none;
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  width: 100%;
  background: rgba(0,0,0,0.75) url("{{asset('/la-assets/etheritech.gif')}}") no-repeat center center;
  /*background: rgba(0,0,0,0.75) url(images/loading2.gif) no-repeat center center;*/
  z-index: 10000;
}
#messageschannel{
width: 202px;
}
.tab-timeline-new {
	height: calc(100vh - 362px);
	overflow: auto;
}
.bottom-box-main {
	box-shadow: 0 0 10px #ccc;
	padding: 10px 5px;
	margin-top: 20px;
	display: flex;
	width: 100%;
}
.bottom-box-main > div {
	margin: 5px;
}
.bottom-box-main-1 {
	width: 100%;
}
.timeline > li > .timeline-item {
	background: #fff !important;
}
.timeline-inverse > li > .timeline-item {
	background: #fff !important;
}
.word-wrap {
	word-wrap: break-word;
}
.with-signature-box {
	margin: 0;
	padding: 0;
	float: right;
	display: flex;
	width: 45%;
	list-style: none;
}
.shortlink-box > li > button {
	font-size: 20px !important;
}


@media screen and (max-width: 767px) {
	.edit-box-sub-menu {
		margin-bottom: 20px;
	}
	.with-signature-box {
		width: 100%;
		display: block;
		text-align: center;
	}
	.with-signature-box .first-li {
		margin: 20px auto 0;
	}
	.shortlink-box {
		display: inline-block;
		margin: 0;
		padding: 0;
		text-align: center;
		width: 100%;
	}
	.shortlink-box li {
		float: none !important;
		margin-bottom: 20px;
	}
	.profile2 .col-xs-10 .row, .profile2 .bg-primary {
		display : inherit;
	}
}
.input-group .form-control {
	z-index: 0;
}
.edit-box-sub-menu a:hover, .edit-box-sub-menu a:active, .edit-box-sub-menu a:focus {
	color: #FFFFFF !important;
}
.profile2 .label2 {
	margin-top: 5px;
}
@if(Entrust::hasRole("PARTNER") && Auth::user()->company_id != $company->id)
.show-client-popup {
	cursor: not-allowed;
	background: gray;
	background: #dddddd;
}
@endif
</style>
<div id="page-content" class="profile2">
	<div class="bg-primary clearfix">
		<div class="col-md-4 col-xs-10">
			<div class="row">
				<div class="col-xs-3">
					<div class="profile-icon text-primary"><i class="fa {{ $module->fa_icon }}"></i></div>
				</div>
				<div class="col-xs-9">
					<h4 class="name word-wrap" title="Project name">{{ $project->$view_col }}</h4>
					@if(!empty($project->url))
						<div class="word-wrap"><b><i class="fa fa-crosshairs" aria-hidden="true"></i>{{ $project->channel }}</b>   <a>{!! \App\Models\Project::getStatusLabel($project->status) !!}</a>   <a class="text-white" href="{{$project->url}}" target="_blank">Lead Link <i class="fa fa-external-link" aria-hidden="true"></i></a></div>
					@endif
				</div>
			</div>
		</div>
		<div class="col-md-1 col-xs-2 actions">
			@if(LAFormMaker::la_access("Projects", "edit"))
			@if(Entrust::hasRole("PARTNER"))
				@if(Auth::user()->id == $project->partner_id)
					<a href="{{ url(config('laraadmin.adminRoute') . '/projects/'.$project->id.'/edit') }}" class="btn btn-xs btn-edit btn-default" title="Edit Lead"><i class="fa fa-pencil"></i></a><br>
				@endif
			@else
			<a href="{{ url(config('laraadmin.adminRoute') . '/projects/'.$project->id.'/edit') }}" class="btn btn-xs btn-edit btn-default" title="Edit Lead"><i class="fa fa-pencil"></i></a><br>
			@endif
			@endif

		</div>
		<div class="col-md-3 col-xs-12">
			<div class="site-button-box">
				<div class="">
					@foreach($skills as $skill)
					<div class="label2">{{$skill}}</div>
					@endforeach
				</div>
			</div>

		</div>
		<div class="col-md-4 col-xs-12">
			@if(!empty($company))
				<div class="edit-box-sub-menu">
					<h4 class="name">
						<a title="Company name"
						   href="{{ url(config('laraadmin.adminRoute') . '/companies/'.$company->id) }}"
						   class="text-white"> {{ $company->getName() }} </a>
						@if(LAFormMaker::la_access("Projects", "edit"))
							@if(Entrust::hasRole("PARTNER"))
								@if(Auth::user()->company_id == $company->id)
								<a href="{{ url(config('laraadmin.adminRoute') . '/companies/'.$company->id.'/edit') }}" class="btn btn-xs btn-edit btn-default" title="Edit Prospect"><i class="fa fa-pencil"></i></a>
								@endif
							@else
								<a href="{{ url(config('laraadmin.adminRoute') . '/companies/'.$company->id.'/edit') }}" class="btn btn-xs btn-edit btn-default" title="Edit Prospect"><i class="fa fa-pencil"></i></a>
							@endif
						@endif
					</h4>
					@if(!empty($company->homepage))
						<div><a title="Client website" class="text-white" href="{{$company->homepage}}" target="_blank">{{$company->homepage}}</a></div>
					@endif

					<div>
					@if (\App\Helpers\Message::validateSend(\App\Helpers\Message::CHANNEL_PHONE,$project))
						<a @if(Auth::user()->canAccess())  onclick="callpopupopan('{{$company->phone}}')"  @endif title="Click to Call" class="text-white @if(!Auth::user()->canAccess()) disable-feature-popup @endif" style="padding: 0px;">
							<i class="fa fa-phone" aria-hidden="true"></i> {{$company->phone}}
						</a>
					@endif
					</div>
					<div>
					@if (\App\Helpers\Message::validateSend(\App\Helpers\Message::CHANNEL_EMAIL,$project))
						<a class="@if(Auth::user()->canAccess()) email-link @else disable-feature-popup  @endif text-white" title="Send Email"><i class="fa fa-envelope-o fa-fw"></i> {{$company->email}}</a>
					@endif
					</div>
				</div>


			@endif

		</div>
	</div>
	<ul data-toggle="ajax-tab" class="nav nav-tabs profile" role="tablist">
		<li class=""><a class="go-back" data-toggle="tooltip" data-placement="right" title="Back to Projects"><i class="fa fa-chevron-left"></i></a></li>
		<!-- <li class="active"><a role="tab" data-toggle="tab" class="active" href="#tab-general-info" data-target="#tab-info"><i class="fa fa-bars"></i> General Info</a></li> -->
		<li class="active"><a role="tab" class="active" data-toggle="tab" href="#tab-timeline" data-target="#tab-timeline"><i class="fa fa-clock-o"></i> Timeline</a></li>
		@if(Entrust::hasRole("SUPER_ADMIN"))
		<li class=""><a role="tab" class="active" data-toggle="tab" href="#tab-competition" data-target="#tab-conpetition"><i class="fa fa-user-md "></i>Competition (<b>{{$leadViews}}</b>)</a></li>
		@endif
		<li><a id="automation-status" onclick="toggleAutomation()"></a></li>
		{{--@if($project->status==0)--}}
		{{--{!! Form::open(['url' => config('laraadmin.adminRoute') . '/place-individual-bid/'.$project->id.'/manual','method'=>'post', 'id' => 'place-individual-bid-form','class'=>'form-inline']) !!}
		<ul class="with-signature-box">
			<li class="first-li  mt15 mr10 no-border" style="min-width: 200px;max-width: 200px;">
				<select class="form-control" data-placeholder="Select template" rel="select2" name="template_id" id="template_id">
					<option value="">Select template</option>
					@foreach($templates as $key=>$value)
						<option value="{{$key}}" {{$key == 1? 'selected':''}}>{{$value}}</option>
					@endforeach
				</select>
			</li>
			<li class=" mt15 mr10 no-border">
				<label for="channel"><input type="checkbox" id="is_signature" name="is_signature"> with Signature </label>
			</li>
			<li class="mt15 mr10 no-border">
				<button type="button" id="btn-preview" class="btn btn-success btn-sm">Preview</button>
				<button type="submit" class="btn btn-success btn-sm">{{$project->status==0 ? 'Place BID': 'Send'}}</button>
			</li>
		</ul>
		{!! Form::close() !!}--}}
		{{--@endif--}}
		<ul class="shortlink-box">
			<li class="pull-right mt15 mr10 no-border" style="display: inline-flex">
				@if (\App\Helpers\Message::validateSend(\App\Helpers\Message::CHANNEL_PHONE,$project))
					<button class="ml5 btn btn-sm btn-primary @if(!Auth::user()->canAccess()) disable-feature-popup @endif" title="Click to Call" @if(Auth::user()->canAccess())  onclick="callpopupopan('{{$company->phone}}')" @endif >
						<i class="fa fa-phone" aria-hidden="true"></i>
					</button>
					<button class="ml5 btn btn-sm btn-success" title="Click to WhatsApp">
						<a href="{{Config::get('constant.WHATSAPP_SEND_MESSAGE_LINK').$company->phone}}" class="text-white" target="_blank" title="WhatsApp">
							<i class="fa fa-whatsapp"></i>
						</a>
					</button>
					<button class="@if(Auth::user()->canAccess()) sms-link  @else disable-feature-popup @endif ml5 btn btn-sm btn-primary" title="SMS Send">
						<i class="fa fa-comments"></i>
					</button>
				@else
					<button class="ml5 btn btn-sm btn-default show-client-popup" title="Click to Call">
						<i class="fa fa-phone" aria-hidden="true"></i>
					</button>
					<button class="ml5 btn btn-sm btn-default show-client-popup" title="Click to WhatsApp">
						<i class="fa fa-whatsapp"></i>
					</button>
					<button class="ml5 btn btn-sm btn-default show-client-popup" title="SMS Send">
						<i class="fa fa-comments"></i>
					</button>
				@endif
					@if (\App\Helpers\Message::validateSend(\App\Helpers\Message::CHANNEL_EMAIL,$project))
						<button class="@if(Auth::user()->canAccess()) email-link @else disable-feature-popup @endif ml5 btn btn-sm btn-primary" title="Send Email">
							<i class="fa fa-envelope-o fa-fw"></i>
						</button>
					@else
						<button class="ml5 btn btn-sm btn-default show-client-popup" title="Send Email">
							<i class="fa fa-envelope-o fa-fw"></i>
						</button>
					@endif
			</li>
			@if(Entrust::hasRole("SUPER_ADMIN"))
			<li class="pull-right mt15 mr10 no-border" style="display: inline-flex">
				<select class="form-control" name="short_link" id="short_link">
					@foreach($shortLinkList as $key => $value)
						<option value="{{$key}}" data-link="{{$value}}"> {{$key}} </option>
					@endforeach
				</select>
				<button class="ml5 btn btn-sm btn-default shortcopyButton" title="click to copy short link"><i class="fa fa-copy" title="click to copy short link"></i><span class="copied" style="display: none; font-size: 1.3rem;" >copied!</span></button>
			</li>
			@endif
		</ul>
	</ul>


	<div class="tab-content">
		<div class="p20 pb0 bg-white">
			@include("flash.message")
		</div>
		<div role="tabpanel" class="tab-pane fade in" id="tab-info">
			<div class="tab-content">
				<div class="panel infolist">
					<div class="panel-default panel-heading">
						<h4>General Info</h4>
					</div>
					<div class="panel-body">
						{!! LAFormMaker::display($module, 'name') !!}
						{!! LAFormMaker::display($module, 'description') !!}
						{!! LAFormMaker::display($module, 'categories') !!}
						{!! LAFormMaker::display($module, 'project_budget') !!}
						{!! LAFormMaker::display($module, 'is_hourly') !!}
						{!! LAFormMaker::display($module, 'url') !!}
						{!! LAFormMaker::display($module, 'channel') !!}
						{!! LAFormMaker::display($module, 'status') !!}
					</div>
				</div>
			</div>
		</div>
		<div role="tabpanel" class="tab-pane active fade in p20 bg-white" id="tab-timeline">
			<div class="tab-timeline-new">
				<ul class="timeline timeline-inverse">
					@php
						$communityLead = true;
					@endphp
					@if(!empty($aPMessage))
						@php
						$date = [];
						@endphp
						@foreach($aPMessage as $key=>$message)
							@if(empty($message->created_at))
								@php 
									$message->created_at = date('Y-m-d h:i:s',strtotime($project->created_at));
								@endphp
							@endif
							@php
								$showDate = false;
							@endphp
							@if(!empty($message->message) && (!in_array($message->event_type, \App\Models\ProjectMessage::EVENT_TYPES_LEADS)))
								@if(empty($date) || (!empty($date) && !in_array(date('Y-m-d',strtotime($message->created_at)),$date)))
									@php
										$showDate = true;
										$date[] = date('Y-m-d',strtotime($message->created_at));
									@endphp 
								@endif
								@if($showDate)
								<li class="time-label">
									<span class="bg-green">
										{{ date('d M, Y',strtotime($message->created_at))}}
									</span>
								</li>
								@endif
							<li>
								{!! \App\Models\ProjectMessage::getEventTypeIcon($message->event_type) !!}
								<div class="timeline-item">
									<span class="time">
										<i class="fa fa-clock-o"></i> {{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $message->created_at)->timezone($timezone)->format('h:i A') }}
									</span>
									<div class="timeline-body word-wrap">
										@php
                                            $userEvents = \App\Models\ProjectMessage::EVENT_TYPES_USERS_ACTIONS;
											unset($userEvents[\App\Models\ProjectMessage::EVENT_TYPE_EMAIL_REPLY]);
											unset($userEvents[\App\Models\ProjectMessage::EVENT_TYPE_CALL]);
											if (in_array($message->event_type, $userEvents)) {
                                                $userAgentInfoHtmlString = \App\Helpers\VisitorDetails::getUserAgentInfoHtml($message);
                              					$fullUserAgentInfo = $userAgentInfoHtmlString['full_user_agent_info'];
												$messageExplode = explode('<br>',$message->message);
    											$messageHtmlString = '<span class="timeline-content-popover" data-toggle="popover" title="UserAgent Info" data-content="'.$fullUserAgentInfo.'">'.$messageExplode[0].'<br>';
                                                if($message->event_type == \App\Models\ProjectMessage::EVENT_TYPE_USER_WEBSITE_ACTIVITY && count($messageExplode) >= 2){
                                                    $messageHtmlString .= $messageExplode[1].'<br>';
                                                }
                                                $messageHtmlString .= '</span>'.$userAgentInfoHtmlString['location_device_info'];
                                                echo html_entity_decode($messageHtmlString);
											} else {
										    $messageString = $message->message;
											if($message->event_type == \App\Models\ProjectMessage::EVENT_TYPE_EMAIL_REPLY) {
												$messageString = explode('On ',$messageString);
												$messageString = $messageString[0];
											}
                                            $messageString = nl2br($messageString);
                                            echo html_entity_decode($messageString);
                                            }
										@endphp
									</div>
							 	</div>
							</li>
							@else
								@php
									$communityLead = false;
								@endphp
							@endif

						@endforeach

						@if(!empty($project->description) && $project->channel != \App\Channels\BaseChannel::XPLACE && $project->channel != \App\Channels\BaseChannel::CRAIGSLIST)
							<li class="time-label">
							<span class="bg-green">{{ date('d M, Y',strtotime($project->created_at)) }}</span>
							</li>
							<li>
								<i class="fa fa-comments bg-yellow"></i>
								<div class="timeline-item">
								<span class="time">
									<i class="fa fa-clock-o"></i> {{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $project->created_at)->timezone($timezone)->format('h:i A') }}
								</span>
								<div class="timeline-body word-wrap">
									@php
										$descriptionString = preg_replace("/\r\n|\r|\n/", '<br/>', $project->description);
										$descriptionString = str_replace('\n','<br/>',$descriptionString);
										echo html_entity_decode($descriptionString);
									@endphp
								</div>
								</div>
							</li>
						@endif

					@else
						@if(!empty($project->description))
						<li class="time-label">
							<span class="bg-green">
								{{date('d M, Y',strtotime($project->created_at))}}
							</span>
						</li>
						<li>
							<i class="fa fa-comments bg-yellow"></i>
							<div class="timeline-item">
								<span class="time">
									<i class="fa fa-clock-o"></i> {{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $project->created_at)->timezone($timezone)->format('h:i A') }}
								</span>
								{{--<h3 class="timeline-header">
									<a href="{{ url(config('laraadmin.adminRoute') . '/companies/'.$company->id) }}"> {{ $company->getName() }} </a>
									{{$project->name}}
								</h3>--}}
								<div class="timeline-body word-wrap">
									@php
										$descriptionString = preg_replace("/\r\n|\r|\n/", '<br/>', $project->description);
										$descriptionString = str_replace('\n','<br/>',$descriptionString);
										echo html_entity_decode($descriptionString);
									@endphp
								</div>
							</div>
						</li>
						@endif
					@endif
					<li>
					<i class="fa fa-clock-o bg-gray"></i>
					</li>
					
				</ul>
			</div>
			{!! Form::open(['url' => config('laraadmin.adminRoute') . '/project/send_message/'.$project->id, 'id' => 'send-message-form']) !!}
			    <div class="bottom-box-main" style="width: 100%;">
				<div class="bottom-box-main-1">
					<div class="row">
						@php
							$channel = \App\Channels\BaseChannel::XPLACE;
							$channelObj = \App\Channels\BaseChannel::getChannel($channel);
						@endphp
						<div class="col-md-3" style="margin-bottom: 10px;">
							<select class="form-control" name="messageschannel" id="messageschannel">
								<option value="{{\App\Models\ProjectMessage::CHANNEL_COMMENT}}"> Comment </option>
								@if(Auth::user()->canAccess())
									@if (\App\Helpers\Message::validateSend(\App\Helpers\Message::CHANNEL_SMS,$project))
										<option value="{{\App\Helpers\Message::CHANNEL_SMS}}"> SMS </option>
									@endif
									@if (\App\Helpers\Message::validateSend(\App\Helpers\Message::CHANNEL_EMAIL,$project))
									<option value="{{\App\Helpers\Message::CHANNEL_EMAIL}}">  Email </option>
									@endif
									@if (\App\Helpers\Message::validateSend(\App\Helpers\Message::CHANNEL_SCRAPER,$project))
	                    <option value="{{\App\Helpers\Message::CHANNEL_SCRAPER}}"> {{ucfirst($project->channel)}} Message </option>
	                @endif
	                @if ($channel && $channelObj->isBidAvailable())
	                	<option value="{{\App\Models\ProjectMessage::CHANNEL_BID}}"> {{ucfirst($project->channel)}} Bid </option>
	                @endif
								@endif
							</select>
						</div>
						<div class="col-md-3">
							<div id="status-div" class="form-group">
								<select class="form-control" id="project-status" name="status">
									<option value="" disabled>Select Status</option>
									@foreach($projectStatus as $status)
										<option value="{{$status}}" {{($project->status == $status) ? 'selected' : ''}} >{{\App\Models\Project::getStatusName($status)}}</option>
									@endforeach
								</select>
							</div>
							<div id="signature-div" class="form-group" style="display: none;">
								<label for="channel"><input type="checkbox" id="is_signature" name="is_signature"> with Signature </label>
							</div>
						</div>
						<div class="col-md-6">
							<div id="follow-up-date-div" class="input-group date" @if($project->status == \App\Models\Project::STATUS_ARCHIVED || $project->status == \App\Models\Project::STATUS_DONE) style="display: none;" @endif>
								<input type="text" class="form-control valid" placeholder="Enter follow up date" id="follow-up-date" name="follow_up_date" >
								<span class="input-group-addon"><span class="fa fa-calendar"></span></span>
							</div>
							<div id="template-div" class="form-group" style="display: none;">
								<select class="form-control" data-placeholder="Select template" rel="select2" name="template_id" id="template_id">
									<option value="">Select template</option>
									@foreach($templates as $key=>$value)
										<option value="{{$key}}">{{$value}}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<div id="subject-div" class="form-group" style="display: none;">
								<input type="text" name="subject" id="subject-input" class="form-control" placeholder="Enter Subject">
							</div>
						</div>
					</div>
					<div class="form-group">
						<textarea name="message" id="message-textarea" rows="1" class="form-control" placeholder="Enter message" required></textarea>
					</div>
				</div>
				 <div class="bottom-box-main-2">
					<div class="dropdown">

					</div>
				</div>
				<div class="bottom-box-main-3">
					<button type="button" id="btn-preview" class="btn btn-success" style="display: none;">Preview</button>
				</div>
				<div class="bottom-box-main-3">
					<button type="submit" id="btn-submit" class="btn btn-primary">Submit</button>
				</div>
				</div>
		  	{!! Form::close() !!}
			</div>
		@if(Entrust::hasRole("SUPER_ADMIN"))
		<div role="tabpanel" class="tab-pane p20 bg-white" id="tab-competition">
			<div class="tab-timeline-new">
				<ul class="timeline timeline-inverse">
					@if(!empty($aPMessage))
						@php
							$date = [];
						@endphp
						@foreach($aPMessage as $key=>$message)
							@if(empty($message->created_at))
								@php
									$message->created_at = date('Y-m-d h:i:s',strtotime($project->created_at));
								@endphp
							@endif
							@php
								$showDate = false;
							@endphp
							@if(!empty($message->message) && (in_array($message->event_type, \App\Models\ProjectMessage::EVENT_TYPES_LEADS)))
								@if(empty($date) || (!empty($date) && !in_array(date('Y-m-d',strtotime($message->created_at)),$date)))
									@php
										$showDate = true;
										$date[] = date('Y-m-d',strtotime($message->created_at));
									@endphp
								@endif
								@if($showDate)
									<li class="time-label">
									<span class="bg-green">
										{{ date('d M, Y',strtotime($message->created_at))}}
									</span>
									</li>
								@endif
								<li>
									{!! \App\Models\ProjectMessage::getEventTypeIcon($message->event_type) !!}
									<div class="timeline-item">
									<span class="time">
										<i class="fa fa-clock-o"></i> {{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $message->created_at)->timezone($timezone)->format('h:i A') }}
									</span>
										<div class="timeline-body word-wrap">
											@php
                                                    $userAgentInfoHtmlString = \App\Helpers\VisitorDetails::getUserAgentInfoHtml($message);
													$fullUserAgentInfo = $userAgentInfoHtmlString['full_user_agent_info'];
													$messageExplode = explode('<br>',$message->message);
    												$messageHtmlString = '<span class="timeline-content-popover" data-toggle="popover" title="UserAgent Info" data-content="'.$fullUserAgentInfo.'">'.$messageExplode[0].'<br>';
                                                    if($message->event_type == \App\Models\ProjectMessage::EVENT_TYPE_USER_WEBSITE_ACTIVITY && count($messageExplode) >= 2){
                                                        $messageHtmlString .= $messageExplode[1].'<br>';
                                                    }
                                                    $messageHtmlString .= '</span>'.$userAgentInfoHtmlString['location_device_info'];
                                                    echo html_entity_decode($messageHtmlString);
											@endphp
										</div>
									</div>
								</li>
							@endif
						@endforeach
					@endif
					@if($communityLead)
						<li class="time-label">
							<span class="bg-green">{{ date('d M, Y',strtotime($project->created_at))}}</span>
						</li>
						<li>
							{!! \App\Models\ProjectMessage::getEventTypeIcon(\App\Models\ProjectMessage::EVENT_TYPE_COMMUNITY_LEAD_SHORT_LINK) !!}
							<div class="timeline-item">
								<span class="time"><i class="fa fa-clock-o"></i></span>
								<div class="timeline-body word-wrap">Community event not found</div>
							</div>
						</li>
					@endif
					<li>
						<i class="fa fa-clock-o bg-gray"></i>
					</li>
				</ul>
			</div>
		</div>
		@endif
	</div>
</div>

<div class="modal fade" id="sendModal" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="changeToMessage()"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Send Message</h4>
			</div>
			{!! Form::open(['url' => config('laraadmin.adminRoute') . '/project/send_message/'.$project->id, 'id' => 'send-message-form']) !!}
			<div class="modal-body">
				<div class="box-body">
					<!-- <div class="form-group">
						<label for="url">Channel :</label>
						<select class="form-control">
							<option value="">Select Channel</option>
							<option value={{\App\Channels\BaseChannel::XPLACE}}>XPlace</option>
							<option value="freelancer">Test deni </option>
						</select>
					</div> -->

					{{--{!! LAFormMaker::form($module) !!}--}}
					<div class="form-group">
						<label for="message">Subject :</label>
						<input type="text" name="subject" class="form-control">						
					</div>

					<div class="form-group">
						<label for="message">Message :</label>
						<textarea class="textarea" name="message" placeholder="Place some text here"
                          style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal" onclick="changeToMessage()">Close</button>
				{!! Form::submit( 'Submit', ['class'=>'btn btn-success']) !!}
			</div>
			{!! Form::close() !!}
		</div>
	</div>
</div>

<div class="modal fade" id="templatePreview" role="dialog" aria-labelledby="myPreviewModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="focusToMessage();" ><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myPreviewModalLabel">Send</h4>
			</div>
			<div class="modal-body">
				<div class="box-body">
					<div id="subject-preview-div" class="form-group" style="display: none;">
						<label for="previewSubject">Subject :</label>
						<div class="previewSubject"></div>
					</div>
					<div class="form-group">
						<label for="previewMessage">Message :</label>
						<div class="previewMessage"></div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal" onclick="focusToMessage();" >Cancel</button>
				<button type="button" id="btn-send-to-prospect" class="btn btn-primary" >Send to Prospect</button>
			</div>
			{!! Form::close() !!}
		</div>
	</div>
</div>

<div class="modal fade" id="show-client-popup" role="dialog" aria-labelledby="clientModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="clientModalLabel">Update Client Detail</h4>
			</div>
			{!! Form::open(['url' => [config('laraadmin.adminRoute') . '/update-client', $company->id ], 'method'=>'POST','id'=>'company-edit-form']) !!}
			<div class="modal-body">
				<div class="box-body">
					{!! LAFormMaker::input($companyModule, 'name','','','form-control',['onkeypress'=>"return (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123) || (event.charCode==32)",'oninput'=>"this.value = this.value.replace(/^\s/g, '').replace(/(\..*)\./g, '$1');"]) !!}
					{!! LAFormMaker::input($companyModule, 'email','','','form-control',['data-rule-email'=>'true']) !!}
					{!! LAFormMaker::input($companyModule, 'phone','','','form-control',['oninput'=>"this.value = this.value.replace(/[^0-9+]/g, '').replace(/(\..*)\./g, '$1');",'maxlength'=>'14','minlength'=>'10']) !!}
					{!! LAFormMaker::input($companyModule, 'homepage','','','form-control',['data-rule-url'=>'true']) !!}
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				{!! Form::submit( 'Update', ['class'=>'btn btn-success']) !!}
			</div>
			{!! Form::close() !!}
		</div>
	</div>
</div>

<div id="loader"></div>
@endsection
@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('la-assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') }}"/>
@endpush
@push('scripts') 
<script src="{{asset('la-assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js')}}"></script>
<script>
    function toggleAutomation(){
        $.ajax({
            url: "{{ url(config('laraadmin.adminRoute') . '/project/toggle-automation/' . $project->id) }}",
            type: "post",
            data: {
                _token : $('meta[name="csrf-token"]').attr('content'),
			},
            success: function(response) {
                if(response.status){
                    updateAutomationStatus(response.automated)
                }
            }
        });
    }
    function updateAutomationStatus (automated) {
        let enabled = '{!! \App\Models\Project::getAutomationLabel(true)!!}';
        let disabled = '{!! \App\Models\Project::getAutomationLabel(false)!!}';

        let content = (automated) ? enabled : disabled;

        $('#automation-status').html(content);
	}
  function callpopupopan(phone){
	$("#phoneiframe").show();
	}
  function changeToMessage(){
  	$("#messageschannel").val('comment');
  }
  function focusToMessage() {
	  $('html, body').animate({ scrollTop: $(document).height() }, 1000);
	  $('#message-textarea').focus();
  }

  $(function () {

	  $("#project-status").on('change', function() {
		  var projectStatus = this.value;
		  if(projectStatus == '{{\App\Models\Project::STATUS_DONE}}' || projectStatus == '{{\App\Models\Project::STATUS_ARCHIVED}}') {
			  $('#follow-up-date-div').hide();
			  $('#follow-up-date').val('');
		  } else {
			  $('#follow-up-date-div').show();
		  }
	  });

	  $('#btn-send-to-prospect').click( function (){
		  $('#btn-submit').trigger('click');
	  });

	  $("#template_id").on('change', function() {
		  if($(this).val()) {
			  $('#btn-preview').show();
		  }
		  var is_signature = $("#is_signature").prop("checked") ? 1 : 0;
		  $.ajax({
			  url: "{{ url(config('laraadmin.adminRoute') . '/get-template-preview') }}",
			  type: "post",
			  data: { is_signature: is_signature,template_id : $('#template_id').val(),project_id : "{{$project->id}}", _token : $('meta[name="csrf-token"]').attr('content') },
			  success: function(response) {
				  if(response.status){
					  $('#subject-input').val(response.data.subject);
					  $('#message-textarea').val(response.data.message);
				  }
			  }
		  });
	  });

	  $('#is_signature, #messageschannel').on('change', function() {
		  $('#follow-up-date').val('');
		  $('#subject-input').val('');
		  $('#message-textarea').val('');
		  $('#template_id').val('');
		  $('#template_id').trigger('change.select2');
		  $('#btn-preview').hide();
	  });

	  $("#message-textarea").focus(function() {

		  $('html, body').animate({ scrollTop: $(document).height() }, 1000);
		  $(this).css("transition", "all 5s ease-in");
		  $(this).attr('rows','5');
	  });

	  $("#message-textarea").focusout(function() {

		  $(this).css("transition", "all 3s ease-in-out");
		  $(this).attr('rows','1');
	  });

	$(".closephonepopup").on('click', function(){
		$("#phoneiframe").hide();
 	});
	$("#messageschannel").on('change', function(){
   		messageschannel  = this.value;
		if(messageschannel == "{{\App\Helpers\Message::CHANNEL_SCRAPER}}"){
			$('#template-div').show();
			$('#signature-div').show();
			$('#subject-div').show();
			$('#subject-preview-div').show();

			$('#subject-input').prop('required', true);
			$('#is_signature').prop('checked', false);
			$('#status-div').hide();
			$('#follow-up-date-div').hide();
		}

		if(messageschannel == "{{\App\Helpers\Message::CHANNEL_SMS}}")
		{
			$('#template-div').hide();
			$('#signature-div').hide();
			$('#is_signature').prop('checked', false);
			$('#status-div').hide();
			$('#follow-up-date-div').hide();
			$('#subject-div').hide();
			$('#subject-input').prop('required', false);
			$('#subject-preview-div').hide();
		}

		if(messageschannel == "{{\App\Models\ProjectMessage::CHANNEL_COMMENT}}")
		{
			$('#status-div').show();
			$('#follow-up-date-div').show();

			$('#template-div').hide();
			$('#signature-div').hide();
			$('#is_signature').prop('checked', false);
			$('#subject-div').hide();
			$('#subject-input').prop('required', false);
			$('#subject-preview-div').hide();
		}

		if(messageschannel == "{{\App\Helpers\Message::CHANNEL_EMAIL}}")
		{
			$('#template-div').show();
			$('#signature-div').show();
			$('#subject-div').show();
			$('#subject-input').prop('required', true);
			$('#subject-preview-div').show();

			$('#status-div').hide();
			$('#follow-up-date-div').hide();
		}
		if(messageschannel == "{{\App\Models\ProjectMessage::CHANNEL_BID}}")
		{
			$('#template-div').show();
			$('#signature-div').show();
			$('#subject-div').show();
			$('#subject-preview-div').show();

			$('#subject-input').prop('required', true);
			$('#is_signature').prop('checked', false);
			$('#status-div').hide();
			$('#follow-up-date-div').hide();
		}
	});
    //$('.textarea').wysihtml5();
    $('form#send-message-form').submit(function(e){
    	if($(this).find('.textarea').val()!=''){
    		var spinner = $('#loader');
    		spinner.show();
    		return true;
    	}
    	e.preventDefault();
    	return false;
    });

	$("#btn-preview").click(function () {
		var subjectString = $('#subject-input').val();
		var messageString = $('#message-textarea').val();
		$('#myPreviewModalLabel').text("Send "+ $('#messageschannel option:selected').text());
		$(".previewSubject").html(subjectString.replace(/\n/g,"<br>"));
		$(".previewMessage").html(messageString.replace(/\n/g,"<br>"));
		$('#templatePreview').modal('show');
		/*var is_signature = $("#is_signature").prop("checked") ? 1 : 0;
		$.ajax({
			url: "{{ url(config('laraadmin.adminRoute') . '/get-template-preview') }}",
			type: "post",
			data: { is_signature: is_signature,template_id : $('#template_id').val(),project_id : "{{$project->id}}", _token : $('meta[name="csrf-token"]').attr('content') },
			success: function(response) {
				if(response.status){
					var subjectString = response.data.subject;
					var messageString = response.data.message;
					$(".previewSubject").html(subjectString.replace(/\n/g,"<br>"));
					$(".previewMessage").html(messageString.replace(/\n/g,"<br>"));
					$('#templatePreview').modal('show');
				}
			}
		});*/
	});
  });
  $(function () {
	  $('#short_link').on('change', function(){
		  var value= `<input value="${$('option:selected', this).attr('data-link')}" id="selVal" />`;
		  $(value).insertAfter('#short_link');
		  $("#selVal").select();
		  document.execCommand("Copy");
		  $('body').find("#selVal").remove();
	  });
	  $('.shortcopyButton').click(function(){
		  var value= `<input value="${$("#short_link option:selected").attr('data-link')}" id="selVal" />`;
		  $(value).insertAfter('#short_link');
		  $("#selVal").select();
		  document.execCommand("Copy");
		  $('body').find("#selVal").remove();
		  $('.copied').fadeIn(100).fadeOut(5000);
	  });

	  $('.email-link').click(function() {
		  $("#messageschannel").val("{{\App\Helpers\Message::CHANNEL_EMAIL}}");
		  $("#messageschannel").trigger("change");

		  focusToMessage();
	  });

	  $('.sms-link').click(function() {
		  $("#messageschannel").val("{{\App\Helpers\Message::CHANNEL_SMS}}");
		  $("#messageschannel").trigger("change");

		  focusToMessage();
	  });

	  $('.show-client-popup').click(function() {
		  @if(Entrust::hasRole("PARTNER"))
			  @if(Auth::user()->company_id == $company->id)
		  		$('#show-client-popup').modal('show');
			  @endif
		  @else
		  $('#show-client-popup').modal('show');
		  @endif
	  });

	  $("#company-edit-form").validate({

	  });
  });

    $(document).ready(function(){
        $('.go-back').click(function(){
            parent.history.go(-1);
            return false;
        });

        updateAutomationStatus({{$project->automated}});
    });
</script>

@endpush