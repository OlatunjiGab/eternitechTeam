<div class="modal fade appmodel filterModel" id="AddModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Search Fields</h4>
            </div>
            <div class="modal-body">
                <div class="box-body">
                    @foreach( $columnDefs as $colKey => $def )
                        @if($def['searchable'])
                            @switch(strtolower($colKey))
                                @case('status')
                                <div class="form-group" data-filter="status">
                                    <label for="status">Status :</label>
                                    <select class="form-control search-by-status filter_data" name="status" id="status">
                                        <option value="">All Status</option>
                                        @foreach($statusList as $status)
                                            <option value="{{$status}}">{{ucfirst(\App\Models\Project::getStatusName($status))}}</option>
                                        @endforeach>
                                    </select>
                                </div>
                                @break
                                @case('channel')
                                <div class="form-group" data-filter="channel">
                                    <label for="channel">Channel :</label>
                                    <select class="form-control search-by-channel filter_data" name="channel"
                                            id="channel">
                                        <option value="">All Channel</option>
                                        @foreach($channelList as $channel)
                                            <option value="{{$channel}}">{{ucfirst($channel)}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @break
                                @case('source')
                                <div class="form-group" data-filter="source">
                                    <label for="source">Source :</label>
                                    <select class="form-control search-by-source filter_data" name="source" id="source">
                                        <option value="">All Source</option>
                                        @foreach($sourceList as $key => $value)
                                            <option value="{{$key}}">{{ucfirst($value)}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @break
                                @case('affiliate')
                                <div class="form-group" data-filter="affiliate">
                                    <label for="affiliate">Affiliate :</label>
                                    <select class="form-control search-by-affiliate filter_data" name="affiliate"
                                            id="affiliate">
                                        <option value="">All Affiliate</option>
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                                @break
                                @case('is_hourly')
                                <div class="form-group" data-filter="is_hourly">
                                    <label for="is_hourly">Is Hourly :</label>
                                    <select class="form-control search-by-is_hourly filter_data" name="is_hourly"
                                            id="is_hourly">
                                        <option value="">All</option>
                                        @foreach(\App\Models\Project::IS_HOURLY_LIST as $key => $value)
                                            <option value="{{$key}}">{{$value}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @break
                                @case('skills')
                                <div class="form-group" data-filter="skills">
                                    <label for="skills">Skills :</label>
                                    <select class="form-control search-by-skills filter_data select2" name="skills"
                                            id="skills" multiple="">
                                        @foreach($skillList as $key => $value)
                                            <option value="{{$value['id']}}">{{$value['keyword']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @break

                                @case('created_at')
                                <div class="form-group" data-filter="created_at">
                                    <label for="name">{{ ucfirst($colKey) }} :</label>
                                    <input type="text" name="created_at" value=""
                                           class="form-control filter_data daterange" placeholder="Select Date Range"
                                           @if(Request::get('today') == 1) value="{{date('m/d/Y')}} - {{date('m/d/Y')}}" @endif />
                                </div>
                                @break

                                @default
                                <div class="form-group" data-filter="{{strtolower($colKey)}}">
                                    <label for="{{strtolower($colKey)}}">{{ ucfirst($colKey) }} :</label>
                                    <input class="form-control filter_data" placeholder="Enter {{ ucfirst($colKey) }}"
                                           data-rule-maxlength="255" name="{{ strtolower($colKey) }}" type="text" value="">
                                </div>
                            @endswitch
                        @endif
                    @endforeach
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default filterFormReset" data-type="reset"
                        data-dismiss="modal">Reset
                </button>
                <button type="button" class="btn btn-success filterForm" data-type="search" data-dismiss="modal">
                    Search
                </button>
            </div>
        </div>
    </div>
</div>