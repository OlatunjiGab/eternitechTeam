@extends("la.layouts.app")

@section("contentheader_title", "Leads")
@section("contentheader_description", "Recent User Activity Leads")
@section("section", "Projects")
@section("sub_section", "Listing")
@section("htmlheader_title", "Recent lead listing")

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
                <div class="col-sm-3 form-group">
                    <label class="form-check-label"> Select date range :</label>
                    <input type="text" name="daterange" value="" class="form-control" placeholder="select date range" />
                </div>
                <div class="col-sm-2 form-group">
                    <label class="form-check-label"> Select Channel :</label>
                    <select class="form-control search-by-status" name="channel_search" id="channel_search">
                        <option value="">All Channel</option>
                        @foreach($channelList as $channel)
                            <option value="{{$channel}}">{{ucfirst($channel)}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-2 form-group">
                    <label class="form-check-label"> Select Status :</label>
                    <select class="form-control search-by-status" name="status_search" id="status_search">
                        <option value="" >All Status</option>
                        @foreach($statuses as $status)
                            <option value="{{$status}}">{{\App\Models\Project::getStatusName($status)}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!--<div class="box-header"></div>-->

            <table id="table" class="table table-bordered">

                <thead>
                <tr class="success">
                    @foreach( $listing_cols as $col )
                        <th>{{ $module->fields[$col]['label'] or ucfirst($col) }}</th>
                    @endforeach
                </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>



    <button type="button" id="bid-modal-btn" class="btn btn-primary btn-lg" data-toggle="modal" data-target=".bs-example-modal-lg" style="display: none;">Launch demo modal</button>

    <div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content bid-content-modal">
                ...
            </div>
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

            $('#table thead tr').clone(true).appendTo( '#table thead' );
            $('#table thead tr:eq(1) th').each( function (i) {
                var title = $(this).text();
                if (title=="Status") {
                    $(this).html('<select class="form-control search-by-status" name="status" id="status">' +
                        '<option value="" >Select Status</option>' +
                            @foreach($statuses as $status)
                                '<option value="{{$status}}">{{\App\Models\Project::getStatusName($status)}}</option>' +
                            @endforeach
                                '</select>' );

                } else if (title == "Channel") {
                    $(this).html('<select class="form-control search-by-status" id="channel">' +
                        '<option value="">select Channel</option>' +
                            @foreach($channelList as $channel)
                                '<option value="{{$channel}}">{{ucfirst($channel)}}</option>' +
                            @endforeach
                                '</select>');

                } else if (title == "Created") {
                    //$(this).html( '<input type="text" class="form-control" style="width:147px;" id="created_at" name="created_at" placeholder="Search '+title+'" /><input type="hidden" name="daterange_search" id="daterange_search">' );
                } else {
                    $(this).html( '<input type="text" class="form-control" style="width:147px;" placeholder="Search '+title+'" />' );
                }

                $( 'select', this ).on( 'change', function () {
                    if ( table.column(i).search() !== this.value ) {
                        table
                            .column(i)
                            .search( this.value )
                            .draw();
                    }
                });
                $( 'input', this ).on( 'keyup change', function () {
                    if ( table.column(i).search() !== this.value ) {
                        table
                            .column(i)
                            .search( this.value )
                            .draw();
                    }
                });
            });


            var table = $("#table").DataTable({
                //scrollY:        "1600px",
                //scrollX:        true,
                //scrollCollapse: true,
                processing: true,
                serverSide: true,
                pageLength: 50,
                ajax: "{{ url(config('laraadmin.adminRoute') . '/recent_lead_dt_ajax') }}",
                language: {
                    paginate: {
                        next: '&#8594;', // or '→'
                        previous: '&#8592;' // or '←'
                    },
                    lengthMenu: "_MENU_",
                    search: "_INPUT_",
                    searchPlaceholder: "Search"
                },
                @if($show_actions)
                //columnDefs: [ { orderable: false, targets: [-1] }],
                @endif
                createdRow: function ( row, data, index ) {
                    /*if ( data[10] == "" ) {
                        $(row).addClass('danger');
                    } */
                },
                columnDefs: [
                    { width: '5%', targets: 0 },
                    { width: '10%', targets: 1 },
                    { width: '10%', targets: 2 },
                    { width: '10%', targets: 3 },
                    { width: '5%', targets: 4 },
                    { width: '10%', targets: 5 },
                    { width: '10%', targets: 6, "searchable": false },
                ],
                fixedColumns:   {
                    leftColumns: 0,
                    rightColumns: 1,
                },

            });
            $("#project-add-form").validate({

            });
        });

        $(function() {
            $('input[name="daterange"]').daterangepicker({
                maxDate: new Date(),
                opens: 'left',
                locale: {
                    cancelLabel: 'Clear'
                }
            }, function(start, end, label) {
                var table = $("#table").DataTable();
                table.columns(6).search(start.format('YYYY-MM-DD') + 'to' + end.format('YYYY-MM-DD')).draw();
            });
            $('input[name="daterange"]').val('');
            $('input[name="daterange"]').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
                var table = $("#table").DataTable();
                table.columns(6).search('').draw();
            });
            $('#channel_search').change(function (){
                $('#channel').val($('#channel_search').val());
                $('#channel').trigger('change');
            });
            $('#status_search').change(function (){
                $('#status').val($('#status_search').val());
                $('#status').trigger('change');
            });

        });
    </script>
@endpush
