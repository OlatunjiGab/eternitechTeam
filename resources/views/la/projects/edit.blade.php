@extends("la.layouts.app")

@section("contentheader_title")
    <a href="{{ url(config('laraadmin.adminRoute') . '/projects') }}">Lead</a> :
@endsection
@section("contentheader_description", $project->$view_col)
@section("section", "Leads")
@section("section_url", url(config('laraadmin.adminRoute') . '/projects'))
@section("sub_section", "Edit")

@section("htmlheader_title", "Projects Edit : ".$project->$view_col)

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
                <div class="col-md-8 col-md-offset-2">
                    {!! Form::model($project, ['route' => [config('laraadmin.adminRoute') . '.projects.update', $project->id ], 'method'=>'PUT', 'id' => 'project-edit-form']) !!}
                    {{--@la_form($module)--}}


                    {!! LAFormMaker::input($module, 'name') !!}
                    {!! LAFormMaker::input($module, 'description') !!}
                    <div class="form-group">
                        <label for="categories">Meta Keywords : </label> "Used for search engines know which keywords were most relevant to the content."
                        <input class="form-control" placeholder="Enter Meta keywords" data-rule-maxlength="100" name="categories" type="text" value="{{old('categories')?:$project->categories}}">
                    </div>
                    <div class="form-group">
                        <label for="project_budget">Project Budget :</label>
                        <div class="row">
                            <div class="col-md-4">
                                <select class="form-control select2-hidden-accessible" data-placeholder="Select currency" rel="select2" name="currency" tabindex="-1" aria-hidden="true" >
                                    <option disabled>Select currency</option>
                                    @foreach($currencyList as $currencyCode =>$currencyText)
                                        <option value="{{$currencyCode}}" {{($project->currency == $currencyCode) ? 'selected' : ''}} @if(empty($project->currency)) {{$currencyCode == 'USD'? 'selected' : ''}} @endif >{{$currencyText}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-8">
                                <input class="form-control" placeholder="Enter Project Budget" data-rule-maxlength="100" name="project_budget" type="number" value="{{$project->project_budget}}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="radio" id="fixPrice" name="is_hourly" value="0" <?= $project->is_hourly == 0 ? 'checked' : '';?>>
                        <label for="fixPrice">Fixed Price Project</label>
                        <input type="radio" id="hourlyBased" name="is_hourly" value="1" <?= $project->is_hourly == 1 ? 'checked' : '';?>>
                        <label for="hourlyBased">Hourly Based Project</label>
                    </div>
                    <div class="form-group">
                        <label for="url">Project Original URL :</label>
                        <input class="form-control" placeholder="Enter Project Original URL" data-rule-maxlength="255" name="url" type="text" value="{{old('url')?:$project->url}}">
                    </div>
                    @if(Entrust::hasRole('SUPER_ADMIN'))
                    <div class="form-group">
                        <label for="channel">Channel :</label>
                        <select class="form-control select2-hidden-accessible" data-placeholder="Select channel" rel="select2" name="channel" tabindex="-1" aria-hidden="true" >
                            @foreach($channelList as $channel)
                                <option value="{{$channel}}" {{$project->channel == $channel? 'selected': ''}}>{{ucfirst($channel)}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="language">Language :</label>
                        <select class="form-control select2-hidden-accessible" data-placeholder="Enter Language" rel="select2" name="language" tabindex="-1" aria-hidden="true">
                            @foreach($languageList as $code => $name)
                                <option value="{{$code}}" {{$project->language == $code? 'selected': ''}}>{{$name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="channel">Source :</label>
                        <select class="form-control select2-hidden-accessible" data-placeholder="Select Source" rel="select2" name="source" tabindex="-1" aria-hidden="true" >
                            @foreach($sourceList as $key=>$type)
                                <option value="{{$key}}" {{$project->source == $key? 'selected': ''}}>{{$type}}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div class="form-group">
                        <label for="channel">Status : </label>
                        <select class="form-control" name="status">
                            <option value="" disabled>Select Status</option>
                            @foreach($statuses as $status)
                                <option value="{{$status}}" {{($project->status == $status) ? 'selected' : ''}} >{{\App\Models\Project::getStatusName($status)}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="follow_up_date">Follow Up Date :</label>
                        <div class="input-group date">
                            <input type="text" class="form-control valid" placeholder="Enter follow up date" name="follow_up_date"  value="{{$project->follow_up_date? \Carbon\Carbon::createFromFormat('Y-m-d',$project->follow_up_date)->format('d/m/Y'):''}}">
                            <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="channel">Skills :</label>
                        <select class="form-control valid" name="skill[]" multiple="" rel="select2">
                            @foreach($aSkill as $key=>$skill)
                                <option value="{{$key}}" {{ in_array($key,$projectSkills)?'selected=selected':'' }}>{{$skill}}</option>
                            @endforeach
                        </select>
                    </div>
                    <br>

                    <div class="form-group">
                        {!! Form::submit( 'Update', ['class'=>'btn btn-success']) !!}
                        <button class="btn btn-default pull-right"><a href="{{ url(config('laraadmin.adminRoute') . '/projects') }}">Cancel</a></button>
                    </div>
                    {!! Form::close() !!}
                    @php $showDelete = true; @endphp
                    @if(Entrust::hasRole("PARTNER") && Auth::user()->id != $project->partner_id)
                        @php $showDelete = false; @endphp
                    @endif
                    @if(LAFormMaker::la_access("Projects", "delete") && $showDelete)
                        <div class="form-group">
                        {{ Form::open(['route' => [config('laraadmin.adminRoute') . '.projects.destroy', $project->id], 'method' => 'delete', 'style'=>'display:inline', 'onsubmit'=>$deleteMessage]) }}
                        <button class="btn btn-danger pull-right" type="submit"><i class="fa fa-times"></i>Delete</button>
                        {{ Form::close() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(function () {
            $("#project-edit-form").validate({});
        });
    </script>
@endpush
