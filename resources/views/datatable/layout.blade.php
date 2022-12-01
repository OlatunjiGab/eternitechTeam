@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(session()->has('success'))
    <p class="alert alert-success">{{ session()->get('success') }}</p>
@endif
<div class="box box-success">
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12 warning-error-deletion hidden">
                <div class="alert alert-warning">
                    Please select at least one record for the delete
                </div>
            </div>
            <div class="row col-sm-12 form-group mb-0">
                <div class="col-sm-5 col-md-5 col-xs-5 form-group" style="padding-right: 0px;">
                    <h5><b>Total Leads:</b> {{$totalCount}}</h5>
                </div>
                <div class="col-sm-7 col-md-7 col-xs-7 pull-right text-right form-group" style="padding-left: 0px;">
                        <a href="{{route('admin.projects.create')}}" class="btn btn-success" title="Add New"><i
                                    class="fa fa-plus width-15"></i></a>
                        <a href="javascript:void(0);" class="btn btn-success" id="cardView"
                           title="Change to Table View"><i class="fa fa-table width-15" aria-hidden="true"></i></a>
                        <button type="button" class="btn btn-success columnSettingModal" data-toggle="modal"
                                data-target=".settingmodel" title="View Settings"><i
                                class="fa fa-cogs width-15"></i></button>
                        {{--<a href="{{url('admin/projects/column-setting')}}" class="btn btn-success"><i class="fa fa-cogs width-15"></i></a>--}}

                        <button type="button" class="btn btn-success" data-toggle="modal" data-target=".appmodel"
                                title="Filter Results"><i class="fa fa-filter width-15" aria-hidden="true"></i></button>
                </div>
            </div>
        </div>
        @php
            $colSpan = count($columnDefs) + 1 ;
        @endphp
        <table id="table" class="table table-bordered">
            <thead>
            @if(!$viewOnly)
                <tr class="select_all">
                    <td colspan="{{ $colSpan }}">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-check d-flex vertical-middle bulk_delete">
                                    <input type="checkbox" id="bulkDelete"/>
                                    <label class="form-check-label" for="bulkDelete">Select All</label>
                                    <button id="deleteTriger" class="btn btn-sm btn-danger hide"><i
                                                class="fa fa-trash"></i></button>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            @endif
            <tr class="success">
                @foreach( $columnDefs as $key => $def )
                    <th>{{ strtolower($key) == 'created_at' ? 'Created' : ucwords(str_replace(["_"],[" "],$key)) }}</th>
                @endforeach

                @if(!$viewOnly)
                    <th>Actions</th>
                @endif
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</div>

@include('datatable.settings')

<div class="copy-input"></div>
{{--@foreach($columnSettings as $key => $value)--}}
{{--<input type="hidden" name="dt_{{$key}}" id="dt_{{$key}}" value="{{$value}}">--}}
{{--@endforeach--}}

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('la-assets/plugins/datatables/datatables.min.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('la-assets/css/daterangepicker.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('la-assets/css/datatables_custom.css') }}"/>
    <style>
        .community-link {
            cursor: pointer;
            color: #48B0F7;
            text-decoration: underline
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('la-assets/plugins/datatables/datatables.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('la-assets/js/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('la-assets/js/daterangepicker.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('la-assets/js/datatables_custom.js') }}"></script>
    <script>
        let module = '{{$module}}';
        let columnSettings = {};
        if (checkStorage(storageKeyName())) {
            columnSettings = getStorage(storageKeyName());
            if (Object.keys(columnSettings).length > 0) {
                $('.modal.settingmodel').find('.form-checkbox').each(function () {
                    if (columnSettings[$(this).attr('data-key')] == 1) {
                        $(this).prop("checked", true);
                    } else {
                        $(this).prop("checked", false);
                    }
                });
            }
        } else {
            @foreach($columnDefs as $colKey => $colDef)
                @if ($colDef['isDynamic'] == true)
                columnSettings["{{$colKey}}"] = {{in_array($colKey, $columnDefaultSettings) ? 1 : 0}};
                @endif
            @endforeach
            saveStorage(storageKeyName());
        }


        let columnDefs = [];
        @php $ind = 0; @endphp
        @foreach ($columnDefs as $key => $columnDef)
            columnDefs.push({
                name: '{{$key}}',
                width: '{{$columnDef['width']}}',
                orderable: {{$columnDef['orderable'] ? 'true' : 'false'}} ,
                searchable: {{$columnDef['defaultSearch'] ? 'true' : 'false'}} ,
                targets: {{$ind++}},
                @if($columnDef['isDynamic'])
                    visible: columnSettings['{{$key}}'],
                    createdCell: function (td, cellData, rowData, row, col) {
                        $(td).attr('data-label', '{{$columnDef["label"]}}').attr('class', 'card-align-right');},
                @else
                    visible: true,
                @endif
            });
        @endforeach

        @if (!$viewOnly)
            columnDefs.push({
                width: '10%',
                targets: {{$ind++}},
                visible: true,
                createdCell: function (td, cellData, rowData, row, col) {
                    $(td).attr('data-action', 'Action');
                }
            });
        @endif

        let getColumnIndex = function(key){
                let columns = {!! json_encode(array_flip(array_keys($columnDefs))) !!};
                return columns[key];
        }
    </script>
@endpush

@include('datatable.filter')