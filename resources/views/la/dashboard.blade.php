@extends('la.layouts.app')

@section('htmlheader_title') Dashboard @endsection
@section('contentheader_title') Dashboard @endsection
@section('contentheader_description') Organisation Overview @endsection

@section('main-content')
<style type="text/css">
.timeline:before {
  background: none;
}
.timeline > li > .timeline-item {
  background: #fff !important;
}
.timeline-inverse > li > .timeline-item {
  background: #fff !important;
}

.readonly-date {
  pointer-events: none;
}
.word-wrap, .timeline-body {
  word-wrap: break-word;
}
.cursor-pointer {
   cursor: pointer;
 }
.todo-list > li .text {
  display: inline !important;
}
.email-reply {
  line-height: 1.5em;
  max-height: 7em;
  overflow: hidden;
  white-space: nowrap;
  text-overflow: ellipsis;
  width: 100%;
}
</style>
<!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-lg-3 col-xs-6">
              <div class="form-group">
                <label for="date_birth">Filter by Date :</label>
                <div class="input-group date">
                  <input class="form-control readonly-date" placeholder="select Date" id="date_filter" name="date_filter" type="text" value="{{date('d/m/Y')}}">
                  <span class="input-group-addon">
                    <span class="fa fa-calendar"></span>
                  </span>
                </div>
                <span class="text-danger"><strong><span id="filter-error-message"></span></strong></span>
                <span class="text-success"><strong><span id="filter-success-message"></span></strong></span>
              </div>
            </div>
          </div>
          <!-- Small boxes (Stat box) -->
          <div class="row">
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-aqua total-leads cursor-pointer">
                <div class="inner">
                  <h3 id="total-lead">@if(isset($data['aRowTotalTodayProjects'])) {{$data['aRowTotalTodayProjects']}} @endif</h3>
                  <p>Total Leads Today</p>
                </div>
                <div class="icon">
                  <i class="ion ion-stats-bars"></i>
                </div>
                <a class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-green total-out-message cursor-pointer">
                <div class="inner">
                  <h3 id="total-out-message">@if(isset($data['aRowTotalTodayOutMessage'])) {{$data['aRowTotalTodayOutMessage']}} @endif</h3>
                  <p>Total Out Messages Today</p>
                </div>
                <div class="icon">
                  <i class="fa fa-envelope"></i>
                </div>
                <a class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-yellow total-reply-message cursor-pointer">
                <div class="inner">
                  <h3 id="total-reply">@if(isset($data['aRowTotalTodayRepliesMessage'])) {{$data['aRowTotalTodayRepliesMessage']}} @endif</h3>
                  <p>Total Replies Today</p>
                </div>
                <div class="icon">
                  <i class="fa fa-envelope"></i>
                </div>
                <a class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-red cursor-pointer" onclick="window.location.href = '{{ url(config('laraadmin.adminRoute') . '/projects') }}';">
                <div class="inner">
                  <h3>@if(isset($data['aRowTotalProjects'])) {{$data['aRowTotalProjects']}} @endif</h3>
                  <p>Total Leads</p>
                </div>
                <div class="icon">
                  <i class="ion ion-pie-graph"></i>
                </div>
                <a href="{{url(config('laraadmin.adminRoute').'/projects')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <div class="small-box bg-gray-active total-open-email cursor-pointer">
                <div class="inner">
                  <h3 id="total-open-email">@if(isset($data['totalOpen'])) {{$data['totalOpen']}} @endif</h3>
                  <p>Total Email opens Today</p>
                </div>
                <div class="icon">
                  <i class="fa fa-envelope-o"></i>
                </div>
                <a class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <div class="small-box bg-light-blue-active total-engaged cursor-pointer">
                <div class="inner">
                  <h3 id="total-engaged">@if(isset($data['aRowTotalEngaged'])) {{$data['aRowTotalEngaged']}} @endif</h3>
                  <p>Total User Engaged</p>
                </div>
                <div class="icon">
                  <i class="fa fa-user-secret"></i>
                </div>
                <a class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <div class="small-box bg-light-blue-active cursor-pointer" onclick="window.location.href = '{{ url(config('laraadmin.adminRoute') . '/template-statistics') }}';">
                <div class="inner">
                  <h3> &nbsp; </h3>
                  <p>Template Statistics</p>
                </div>
                <div class="icon">
                  <i class="fa fa-send"></i>
                </div>
                <a href="{{url(config('laraadmin.adminRoute').'/template-statistics')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div>
          </div><!-- /.row -->
          <!-- Main row -->
          <div class="row">
            <!-- Left col -->
            <section class="col-lg-7 connectedSortable">
              <!-- Custom tabs (Charts with tabs)-->
               <!-- TO DO List -->
              <?php /*<div class="box box-primary">
                <div class="box-header">
                  <i class="ion ion-clipboard"></i>
                  <h3 class="box-title">Recent Leads</h3>
                  <div class="box-tools pull-right">
                    <ul class="pagination pagination-sm inline">
                      <li><a href="javascript:void(0)" class="leads-page-1">&laquo;</a></li>
                      <li><a href="javascript:void(0)" class="leads-page-1">1</a></li>
                      <li><a href="javascript:void(0)" class="leads-page-2">2</a></li>
                      <li><a href="javascript:void(0)" class="leads-page-2">&raquo;</a></li>
                    </ul>
                  </div>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <ul class="todo-list">
                  @foreach( $data['aRowTotalProjectsrecents'] as $key => $col )
                    @php
                            if($key <= 9) {
                              $className = "recent_leads_page_1";
                            } else {
                              $className = "recent_leads_page_2";
                            }
                    @endphp
                   <li class="{{$className}} cursor-pointer" onclick="window.location.href = '{{ url(config('laraadmin.adminRoute') . '/projects/'.$col->id) }}';">
                      <span class="handle">
                        <i class="fa fa-ellipsis-v"></i>
                        <i class="fa fa-ellipsis-v"></i>
                      </span>
                       <small class="label label-success">   {{$col->id}} </small>
                      <span class="text">{{$col->name}}</span>
                      <small class="label label-danger"><i class="fa fa-clock-o"></i> {{$col->is_hourly}} </small>
                       {{$col->categories}}
                    </li>
                @endforeach
                 <!--    echo "<pre>"; 
                        print_r($col->name);
                       <li>
                      <span class="handle">
                        <i class="fa fa-ellipsis-v"></i>
                        <i class="fa fa-ellipsis-v"></i>
                      </span>
                      <input type="checkbox" value="" name="">
                      <span class="text">Make the theme responsive</span>
                      <small class="label label-info"><i class="fa fa-clock-o"></i> 4 hours</small>
                      <div class="tools">
                        <i class="fa fa-edit"></i>
                        <i class="fa fa-trash-o"></i>
                      </div>  XA
                    </li> -->
                    <!-- <li>
                      <span class="handle">
                        <i class="fa fa-ellipsis-v"></i>
                        <i class="fa fa-ellipsis-v"></i>
                      </span>
                      <input type="checkbox" value="" name="">
                      <span class="text">Let theme shine like a star</span>
                      <small class="label label-warning"><i class="fa fa-clock-o"></i> 1 day</small>
                      <div class="tools">
                        <i class="fa fa-edit"></i>
                        <i class="fa fa-trash-o"></i>
                      </div>
                    </li>
                    <li>
                      <span class="handle">
                        <i class="fa fa-ellipsis-v"></i>
                        <i class="fa fa-ellipsis-v"></i>
                      </span>
                      <input type="checkbox" value="" name="">
                      <span class="text">Let theme shine like a star</span>
                      <small class="label label-success"><i class="fa fa-clock-o"></i> 3 days</small>
                      <div class="tools">
                        <i class="fa fa-edit"></i>
                        <i class="fa fa-trash-o"></i>
                      </div>
                    </li>
                    <li>
                      <span class="handle">
                        <i class="fa fa-ellipsis-v"></i>
                        <i class="fa fa-ellipsis-v"></i>
                      </span>
                      <input type="checkbox" value="" name="">
                      <span class="text">Check your messages and notifications</span>
                      <small class="label label-primary"><i class="fa fa-clock-o"></i> 1 week</small>
                      <div class="tools">
                        <i class="fa fa-edit"></i>
                        <i class="fa fa-trash-o"></i>
                      </div>
                    </li>
                    <li>
                      <span class="handle">
                        <i class="fa fa-ellipsis-v"></i>
                        <i class="fa fa-ellipsis-v"></i>
                      </span>
                      <input type="checkbox" value="" name="">
                      <span class="text">Let theme shine like a star</span>
                      <small class="label label-default"><i class="fa fa-clock-o"></i> 1 month</small>
                      <div class="tools">
                        <i class="fa fa-edit"></i>
                        <i class="fa fa-trash-o"></i>
                      </div>
                    </li> -->
                  </ul>
                </div><!-- /.box-body -->
                <div class="box-footer clearfix no-border">
                 <center> 
                  <a href="{{ url(config('laraadmin.adminRoute') . '/projects') }}">
                    <button class="btn btn-default"> See all</button>
                  </a>
                </center></div>
              </div>*/ ?><!-- /.box -->

              <div class="box box-primary">
                <div class="box-header">
                  <i class="ion ion-clipboard"></i>
                  <h3 class="box-title">Recent Lead Events</h3>
                  <div class="box-tools pull-right">
                    <ul class="pagination pagination-sm inline">
                      <li><a href="javascript:void(0)" class="events-page-1">&laquo;</a></li>
                      <li><a href="javascript:void(0)" class="events-page-1">1</a></li>
                      @if(count($data['latestLeadEvents']) > 9)
                      <li><a href="javascript:void(0)" class="events-page-2">2</a></li>
                      @endif
                      <li><a href="javascript:void(0)" class="events-page-2">&raquo;</a></li>
                    </ul>
                  </div>
                </div>
                <div class="box-body">
                  <div class="tab-timeline-new">
                    <ul class="timeline timeline-inverse">
                    @php
                      $keyIndex = 0;
                    @endphp
                    @foreach($data['latestLeadEvents'] as $key => $leadEvent)
                      @php
                      if($keyIndex <= 9) {
                          $className = "recent_events_page_1";
                        } else {
                          $className = "recent_events_page_2";
                        }
                      @endphp
                      <li class="{{$className}}">
                        {!! \App\Models\ProjectMessage::getEventTypeIcon($leadEvent->event_type) !!}
                      <div class="timeline-item">
                        <span class="time">
                          <i class="fa fa-clock-o"></i> {{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $leadEvent->event_datetime)->timezone($data['timezone'])->format('d-m-Y h:i A') }}
                        </span>
                        <h3 class="timeline-header">
                          <a href="{{ url(config('laraadmin.adminRoute') . '/projects/'.$leadEvent->project_id) }}" class="word-wrap">{{$leadEvent->project_name}}</a>
                        </h3>
                        <div class="timeline-body">
                          @php
                            $userEvents = \App\Models\ProjectMessage::EVENT_TYPES_USERS_ACTIONS;
                            unset($userEvents[\App\Models\ProjectMessage::EVENT_TYPE_EMAIL_REPLY]);
                            unset($userEvents[\App\Models\ProjectMessage::EVENT_TYPE_CALL]);
                            if (in_array($leadEvent->event_type, $userEvents)) {
                              $userAgentInfoHtmlString = \App\Helpers\VisitorDetails::getUserAgentInfoHtml($leadEvent);
                              $fullUserAgentInfo = $userAgentInfoHtmlString['full_user_agent_info'];
                              $messageExplode = explode('<br>',$leadEvent->event_message);
                              $messageHtmlString = '<span class="timeline-content-popover" data-toggle="popover" title="UserAgent Info" data-content="'.$fullUserAgentInfo.'">'.$messageExplode[0].'<br>';
                              if($leadEvent->event_type == \App\Models\ProjectMessage::EVENT_TYPE_USER_WEBSITE_ACTIVITY && count($messageExplode) >= 2){
                                  $messageHtmlString .= $messageExplode[1].'<br>';
                              }
                              $messageHtmlString .= '</span>'.$userAgentInfoHtmlString['location_device_info'];
                              echo html_entity_decode($messageHtmlString);
                            } else {
                                $messageString = $leadEvent->event_message;
                                if($leadEvent->event_type == \App\Models\ProjectMessage::EVENT_TYPE_EMAIL_REPLY) {
                                    $messageString = explode('On ',$messageString);
                                    $messageString = "<div class='email-reply'>".$messageString[0]."</div>";
                                }
                                $messageString = nl2br($messageString);
                              echo html_entity_decode($messageString);
                            }
                          @endphp
                        </div>
                      </div>
                      </li>
                      @php
                        $keyIndex ++;
                      @endphp
                    @endforeach
                    </ul>
                  </div>
                </div>
                @if(empty($data['latestLeadEvents']))
                  <div class="box-footer clearfix no-border">
                    <center>No Data Found</center>
                  </div>
                @else
                <div class="box-footer clearfix no-border">
                  <center>
                    <a href="{{ url(config('laraadmin.adminRoute') . '/recent-leads') }}">
                      <button class="btn btn-default"> See all recent lead</button>
                    </a>
                    <a href="{{ url(config('laraadmin.adminRoute') . '/recent-lead-event') }}">
                      <button class="btn btn-default"> See all recent lead event</button>
                    </a>
                  </center>
                </div>
                @endif
              </div>

              <div class="box box-primary">
                <div class="box-header">
                  <i class="ion ion-clipboard"></i>
                  <h3 class="box-title">Todo Follow Up Leads</h3>
                  <div class="box-tools pull-right">
                    <ul class="pagination pagination-sm inline">
                      <li><a href="javascript:void(0)" class="follow-up-lead-page-1">&laquo;</a></li>
                      <li><a href="javascript:void(0)" class="follow-up-lead-page-1">1</a></li>
                      @if(count($data['followupLeads']) > 9)
                        <li><a href="javascript:void(0)" class="follow-up-lead-page-2">2</a></li>
                      @endif
                      <li><a href="javascript:void(0)" class="follow-up-lead-page-2">&raquo;</a></li>
                    </ul>
                  </div>
                </div>
                <div class="box-body">
                  <div class="tab-timeline-new">
                    <ul class="timeline timeline-inverse">
                    @php
                      $keyIndex = 0;
                    @endphp
                    @foreach($data['followupLeads'] as $key => $lead)
                      @if($leadMessage = $lead->todoProjectMessages->first())
                        @php
                          if($keyIndex <= 9) {
                              $className = "follow_up_lead_page_1";
                            } else {
                              $className = "follow_up_lead_page_2";
                            }
                        @endphp
                          <li class="{{$className}}">
                            {!! \App\Models\ProjectMessage::getEventTypeIcon($leadMessage->event_type) !!}
                            <div class="timeline-item">
                            <span class="time">
                              <i class="fa fa-clock-o"></i> {{\Carbon\Carbon::createFromFormat('Y-m-d', $lead->follow_up_date)->timezone($data['timezone'])->format('d-m-Y') }}
                            </span>
                              <h3 class="timeline-header">
                                <a href="{{ url(config('laraadmin.adminRoute') . '/projects/'.$lead->id) }}" class="word-wrap">{{$lead->name}}</a>
                              </h3>
                              <div class="timeline-body">
                                {!! html_entity_decode(nl2br($leadMessage->message));  !!}
                              </div>
                            </div>
                          </li>
                          @php
                            $keyIndex ++;
                          @endphp
                      @endif
                    @endforeach
                    </ul>
                  </div>
                </div>
                @if(count($data['followupLeads']) == 0)
                  <div class="box-footer clearfix no-border">
                    <center>No Data Found</center>
                  </div>
                @endif
              </div>
              
              <!-- quick email widget -->
              <!-- <div class="box box-info">
                <div class="box-header">
                  <i class="fa fa-envelope"></i>
                  <h3 class="box-title">Quick Email</h3>
                  --> <!-- tools box -->
               <!--    <div class="pull-right box-tools">
                    <button class="btn btn-info btn-sm" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                  </div> --><!-- /. tools -->
                <!-- </div> -->
           <!--      <div class="box-body">
                  <form action="#" method="post">
                    <div class="form-group">
                      <input type="email" class="form-control" name="emailto" placeholder="Email to:">
                    </div>
                    <div class="form-group">
                      <input type="text" class="form-control" name="subject" placeholder="Subject">
                    </div>
                    <div>
                      <textarea class="textarea" placeholder="Message" style="width: 100%; height: 125px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
                    </div>
                  </form>
                </div>
                <div class="box-footer clearfix">
                  <button class="pull-right btn btn-default" id="sendEmail">Send <i class="fa fa-arrow-circle-right"></i></button>
                </div>
              </div> -->

            </section><!-- /.Left col -->
            <!-- right col (We are only adding the ID to make the widgets sortable)-->
            <section class="col-lg-5 connectedSortable">

              <div class="box box-info">
                <div class="box-header with-border">
                  <h3 class="box-title">Interested Partners</h3>
                  <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse">
                      <i class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove">
                      <i class="fa fa-times"></i>
                    </button>
                  </div>
                </div>

                <div class="box-body">
                  <div class="table-responsive">
                    <table class="table no-margin">
                      <thead>
                      <tr>
                        <th>Partner</th>
                      </tr>
                      </thead>
                      <tbody>
                      @forelse($data['interestedPartners'] as $key => $interestedPartner)
                        <tr>
                          <td>
                            <a href="{{ url(config('laraadmin.adminRoute') . '/users/') .'/'. $interestedPartner->user_id }}">{{$interestedPartner->user->name}}</a>
                          </td>
                        </tr>
                      @empty
                        <tr>
                          <td colspan="2" class="text-center">No Data Found.</td>
                        </tr>
                      @endforelse
                      </tbody>
                    </table>
                  </div>
                </div>

                <div class="box-footer clearfix">
                  <a href="{{ url(config('laraadmin.adminRoute') . '/interested-partners') }}" class="btn btn-sm btn-default btn-flat pull-right">View All</a>
                </div>

              </div>

               <div class="nav-tabs-custom">
                <!-- Tabs within a box -->
                <ul class="nav nav-tabs pull-right">
                  <li class="active"><a href="#revenue-chart" data-toggle="tab">Area</a></li>
                  <!-- <li><a href="#sales-chart" data-toggle="tab">Donut</a></li> -->
                  <li class="pull-left header"><i class="fa fa-inbox"></i> Sales</li>
                </ul>
                <div class="tab-content no-padding">
                  <!-- Morris chart - Sales -->
                  <div class="chart tab-pane active" id="revenue-chart" style="position: relative; height: 300px;"></div>
                  <!-- <div class="chart tab-pane" id="sales-chart" style="position: relative; height: 300px;"></div> -->
                </div>
              </div><!-- /.nav-tabs-custom -->

              <!-- solid sales graph -->
              {{--<div class="box box-solid bg-teal-gradient">
                <div class="box-header">
                  <i class="fa fa-th"></i>
                  <h3 class="box-title">Sales Graph</h3>
                  <div class="box-tools pull-right">
                    <button class="btn bg-teal btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn bg-teal btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                </div>
                <div class="box-body border-radius-none">
                  <div class="chart" id="line-chart" style="height: 250px;"></div>
                </div><!-- /.box-body -->
               <!--  <div class="box-footer no-border">
                  <div class="row">
                    <div class="col-xs-4 text-center" style="border-right: 1px solid #f4f4f4">
                      <input type="text" class="knob" data-readonly="true" value="20" data-width="60" data-height="60" data-fgColor="#39CCCC">
                      <div class="knob-label">Mail-Orders</div>
                    </div> --><!-- ./col -->
                   <!--  <div class="col-xs-4 text-center" style="border-right: 1px solid #f4f4f4">
                      <input type="text" class="knob" data-readonly="true" value="50" data-width="60" data-height="60" data-fgColor="#39CCCC">
                      <div class="knob-label">Online</div>
                    </div> --><!-- ./col -->
                    <!-- <div class="col-xs-4 text-center">
                      <input type="text" class="knob" data-readonly="true" value="30" data-width="60" data-height="60" data-fgColor="#39CCCC">
                      <div class="knob-label">In-Store</div>
                    </div> --><!-- ./col -->
                  <!-- </div> --><!-- /.row -->
               <!--  </div> --><!-- /.box-footer -->
              <!-- </div> --><!-- /.box -->

              <!-- Calendar -->
              <div class="box box-solid bg-green-gradient">
                <div class="box-header">
                  <i class="fa fa-calendar"></i>
                  <h3 class="box-title">Calendar</h3>
                  <!-- tools box -->
                  <div class="pull-right box-tools">
                    <!-- button with a dropdown -->
                    <div class="btn-group">
                      <button class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bars"></i></button>
                      <ul class="dropdown-menu pull-right" role="menu">
                        <li><a href="#">Add new event</a></li>
                        <li><a href="#">Clear events</a></li>
                        <li class="divider"></li>
                        <li><a href="#">View calendar</a></li>
                      </ul>
                    </div>
                    <button class="btn btn-success btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-success btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div><!-- /. tools -->
                </div><!-- /.box-header -->
                <div class="box-body no-padding">
                  <!--The calendar -->
                  <div id="calendar" style="width: 100%"></div>
                </div><!-- /.box-body -->
                <div class="box-footer text-black">
                  <div class="row">
                  </div><!-- /.row -->
                </div>
              </div><!-- /.box -->
              </div>--}}
            </section><!-- right col -->
          </div><!-- /.row (main row) -->

        </section><!-- /.content -->
@endsection

@push('styles')
<!-- Morris chart -->
<link rel="stylesheet" href="{{ asset('la-assets/plugins/morris/morris.css') }}">
<!-- jvectormap -->
<link rel="stylesheet" href="{{ asset('la-assets/plugins/jvectormap/jquery-jvectormap-1.2.2.css') }}">
<!-- Date Picker -->
<link rel="stylesheet" href="{{ asset('la-assets/plugins/datepicker/datepicker3.css') }}">
<!-- Daterange picker -->
<link rel="stylesheet" href="{{ asset('la-assets/plugins/daterangepicker/daterangepicker-bs3.css') }}">
<!-- bootstrap wysihtml5 - text editor -->
<link rel="stylesheet" href="{{ asset('la-assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') }}">
@endpush


@push('scripts')
<!-- jQuery UI 1.11.4 -->
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Morris.js charts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="{{ asset('la-assets/plugins/morris/morris.min.js') }}"></script>
<!-- Sparkline -->
<script src="{{ asset('la-assets/plugins/sparkline/jquery.sparkline.min.js') }}"></script>
<!-- jvectormap -->
<script src="{{ asset('la-assets/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
<script src="{{ asset('la-assets/plugins/jvectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
<!-- jQuery Knob Chart -->
<script src="{{ asset('la-assets/plugins/knob/jquery.knob.js') }}"></script>
<!-- daterangepicker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="{{ asset('la-assets/plugins/daterangepicker/daterangepicker.js') }}"></script>
<!-- datepicker -->
<script src="{{ asset('la-assets/plugins/datepicker/bootstrap-datepicker.js') }}"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="{{ asset('la-assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') }}"></script>
<!-- FastClick -->
<script src="{{ asset('la-assets/plugins/fastclick/fastclick.js') }}"></script>
<!-- dashboard -->
<script src="{{ asset('la-assets/js/pages/dashboard.js') }}"></script>
@endpush

@push('scripts')
  <script type="text/javascript">
    $(".recent_leads_page_2").hide();
    $(".recent_events_page_2").hide();
    $(".follow_up_lead_page_2").hide();
    $("#date_filter").on('blur',function () {
      $.ajax({
        url: "/admin/get_dashboard_count_data",
        type: "post",
        data: { searchDate : $(this).val(), _token : $('meta[name="csrf-token"]').attr('content') },
        success: function(response){
          if(response.status){
            $('#total-lead').text(response.data.totalLead);
            $('#total-out-message').text(response.data.totalOutMessage);
            $('#total-reply').text(response.data.totalRepliesMessage);
            $('#total-open-email').text(response.data.totalEmailOpen);
            $('#total-engaged').text(response.data.totalEngaged);
            $('#filter-success-message').text(response.message);
            setTimeout(function(){
              $('#filter-success-message').text('');
            }, 10000);

          } else {
            $('#filter-error-message').text(response.message);
            setTimeout(function(){
              $('#filter-error-message').text('');
            }, 10000);
          }
        }
      });
    });
    $("div .total-leads").click(function (){
      var redirectUrl = '{{ url(config('laraadmin.adminRoute') . '/projects?today=1') }}';
      var filterDate = $('#date_filter').val();
      var dateValue = filterDate.split('/').join('-');
      window.location.href = redirectUrl+"&date="+dateValue;
    });
    $("div .total-out-message").click(function (){
      var redirectUrl = '{{ url(config('laraadmin.adminRoute') . '/projects?today=2') }}';
      var filterDate = $('#date_filter').val();
      var dateValue = filterDate.split('/').join('-');
      window.location.href = redirectUrl+"&date="+dateValue;
    });
    $("div .total-reply-message").click(function (){
      var redirectUrl = '{{ url(config('laraadmin.adminRoute') . '/projects?today=3') }}';
      var filterDate = $('#date_filter').val();
      var dateValue = filterDate.split('/').join('-');
      window.location.href = redirectUrl+"&date="+dateValue;
    });
    $("div .total-open-email").click(function (){
      var redirectUrl = '{{ url(config('laraadmin.adminRoute') . '/projects?today=4') }}';
      var filterDate = $('#date_filter').val();
      var dateValue = filterDate.split('/').join('-');
      window.location.href = redirectUrl+"&date="+dateValue;
    });
    $("div .total-engaged").click(function (){
      var redirectUrl = '{{ url(config('laraadmin.adminRoute') . '/projects?today=5') }}';
      var filterDate = $('#date_filter').val();
      var dateValue = filterDate.split('/').join('-');
      window.location.href = redirectUrl+"&date="+dateValue;
    });
    $(".leads-page-1").click(function (){
      $(".recent_leads_page_1").show();
      $(".recent_leads_page_2").hide();
    });
    $(".leads-page-2").click(function (){
      $(".recent_leads_page_2").show();
      $(".recent_leads_page_1").hide();
    });
    $(".events-page-1").click(function (){
      $(".recent_events_page_1").show();
      $(".recent_events_page_2").hide();
    });
    $(".events-page-2").click(function (){
      $(".recent_events_page_2").show();
      $(".recent_events_page_1").hide();
    });
    $(".follow-up-lead-page-1").click(function (){
      $(".follow_up_lead_page_1").show();
      $(".follow_up_lead_page_2").hide();
    });
    $(".follow-up-lead-page-2").click(function () {
      $(".follow_up_lead_page_2").show();
      $(".follow_up_lead_page_1").hide();
    });
  </script>
  <script type="text/javascript">
    (function($) {
      var area = new Morris.Area({
        element: 'revenue-chart',
        resize: true,
        data: <?= json_encode($data['revenueSaleChartData']); ?>,
        xkey: 'y',
        ykeys: ['totalProject', 'totalSent', 'totalReply'],
        labels: ['Projects', 'Sent', 'Reply'],
        lineColors: ['#a0d0e0', '#3c8dbc'],
        hideHover: 'auto'
      });
      /*var line = new Morris.Line({
        element: 'line-chart',
        resize: true,
        data: <?= json_encode($data['lineSaleChartData']); ?>,
        xkey: 'y',
        ykeys: <?= json_encode(array_keys($data['aChannel'])); ?>,
        labels: <?= json_encode(array_values($data['aChannel'])); ?>,
        lineColors: ['#efefef'],
        lineWidth: 2,
        hideHover: 'auto',
        gridTextColor: "#fff",
        gridStrokeWidth: 0.4,
        pointSize: 4,
        pointStrokeColors: ["#efefef"],
        gridLineColor: "#efefef",
        gridTextFamily: "Open Sans",
        gridTextSize: 10
      });*/
      $('.box ul.nav a').on('shown.bs.tab', function () {
        area.redraw();
        //line.redraw();
      });
    })(window.jQuery);
  </script>
@endpush