@extends("la.layouts.app")

@section("contentheader_title", "Lead events")
@section("contentheader_description", "Recent user activity events")
@section("section", "Recent Events")
@section("sub_section", "Listing")
@section("htmlheader_title", "Recent lead listing")

@section("main-content")
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
    .timeline {
        margin: 0;
    }
</style>

    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session()->has('bid_success_message'))
        <div class="alert alert-success">
            <?php echo session()->get('bid_success_message'); ?>
        </div>
    @endif

    @if(session()->has('success'))
        <p class="alert alert-success">{{ session()->get('success') }}</p>
    @endif
    <div class="box box-success">
        <div class="box-body">
            <div class="row">
                <div class="col-sm-2 form-group">
                    <label class="form-check-label"> Select Event Type :</label>
                    <select class="form-control search-by-status" name="event_type_search" id="event_type_search">
                        <option value="" selected>All Events</option>
                        @foreach($userEventList as $key => $value)
                            <option value="{{$key}}" {{$key == $eventType? 'selected': ''}}>{{$value}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <table id="table" class="table table-bordered">
                <thead>
                <tr>
                    <th>recent lead event</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($recentLeadEvents as $key => $leadEvent)
                        <tr>
                            <td>
                                <div class="box-body">
                                    <div class="tab-timeline-new">
                                        <ul class="timeline timeline-inverse">
                                            <li>
                                                {!! \App\Models\ProjectMessage::getEventTypeIcon($leadEvent->event_type) !!}
                                                <div class="timeline-item">
                                                    <span class="time">
                                                      <i class="fa fa-clock-o"></i> {{ date('d-m-Y h:i A',strtotime($leadEvent->event_datetime)) }}
                                                    </span>
                                                    <h3 class="timeline-header">
                                                        <a href="{{ url(config('laraadmin.adminRoute') . '/projects/'.$leadEvent->project_id) }}">{{$leadEvent->project_name}}</a>
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
                                                                    $messageString = $messageString[0];
                                                                }
                                                                $messageString = nl2br($messageString);
                                                              echo html_entity_decode($messageString);
                                                            }
                                                        @endphp
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('la-assets/plugins/datatables/datatables.min.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('la-assets/css/daterangepicker.css') }}"/>
@endpush

@push('scripts')
    <script src="{{ asset('la-assets/plugins/datatables/datatables.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('la-assets/js/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('la-assets/js/daterangepicker.min.js') }}"></script>
    <script>
        $(function () {
            $('#table').DataTable({
                "ordering": false,
                language: {
                    paginate: {
                        next: '&#8594;', // or '→'
                        previous: '&#8592;' // or '←'
                    },
                },
            });
        });
        $('#event_type_search').change(function() {
            var redirectUrl = "{{ url(config('laraadmin.adminRoute') . '/recent-lead-event/') }}/";
            window.location = redirectUrl+$(this).val();
        });
    </script>
@endpush