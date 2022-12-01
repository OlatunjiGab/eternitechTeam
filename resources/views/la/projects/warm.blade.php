@extends("la.layouts.app")

@section("contentheader_title", "Warm Leads")
@section("contentheader_description", "Active Leads")
@section("section", "Leads")
@section("sub_section", "Listing")
@section("htmlheader_title", "Warm Lead Listing")

@section("headerElems")
@if(LAFormMaker::la_access("Projects", "create"))
	<!-- <button class="btn btn-success btn-sm pull-right" data-toggle="modal" data-target="#AddModal">Add Project</button> -->
	<a href="{{route('admin.projects.create')}}" class="btn btn-success btn-sm pull-right" >Add New Leads</a>
	<a href="{{url('admin/projects')}}" class="btn btn-success" style="margin-left: -2%;">Leads</a>
@endif
@endsection

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
			<div class="col-sm-12 warning-error-deletion hidden">
				<div class="alert alert-warning">
			        Please select at least one record for delete action perform
			    </div>
			</div>
			<div class="col-sm-6 form-group">
				<div class="form-check">
				    <input type="checkbox"  id="bulkDelete"/>
				    <label class="form-check-label" for="bulkDelete">Select All</label>
					<button id="deleteTriger" class="btn btn-danger">Delete</button>
				</div>
			</div>
		</div>

	<!--<div class="box-header"></div>-->
		
		<table id="table" class="table table-bordered">
			<thead>
				<tr class="success">					
					@foreach( $listing_cols as $col )					
						<th>{{ $module->fields[$col]['label'] or ucfirst($col) }}</th>						
					@endforeach
						@if($show_client_name)
							<th>Client Name</th>
						@endif
						@if($show_company_phone)
							<th>Client Phone</th>
						@endif
						@if($show_company_name)
							<th>Client Email</th>
						@endif
						@if($project_attention)
							<th>Project Attention</th>
						@endif
						@if($created_at)
							<th>Created</th>
						@endif
						@if($show_actions)
							<th>Actions</th>
						@endif
				</tr>
			</thead>
			<tbody>
				
			</tbody>
		</table>
	</div>
</div>

@if(LAFormMaker::la_access("Projects", "create"))
<div class="modal fade" id="AddModal" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Add Project</h4>
			</div>
			{!! Form::open(['action' => 'LA\ProjectsController@store', 'id' => 'project-add-form']) !!}
			<div class="modal-body">
				<div class="box-body">
					{!! LAFormMaker::form($module) !!}
					
					{{--
					@la_input($module, 'name')
					@la_input($module, 'description')
					@la_input($module, 'categories')
					@la_input($module, 'project_budget')
					@la_input($module, 'is_hourly')
					@la_input($module, 'url')
					@la_input($module, 'channel')
					@la_input($module, 'status')
					--}}
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				{!! Form::submit( 'Submit', ['class'=>'btn btn-success']) !!}
			</div>
			{!! Form::close() !!}
		</div>
	</div>
</div>
@endif



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
@endpush

@push('scripts')
<script src="{{ asset('la-assets/plugins/datatables/datatables.min.js') }}"></script>
<script>
$(function () {

	$('#table thead tr').clone(true).appendTo( '#table thead' );
    $('#table thead tr:eq(1) th').each( function (i) {
        var title = $(this).text();        
		if (title=="Status") {
			$(this).html('<select class="form-control search-by-status" name="status">' +
                '<option value="" >Select Status</option>' +
                @foreach($statuses as $status)
                '<option value="{{$status}}">{{\App\Models\Project::getStatusName($status)}}</option>' +
                @endforeach
             	'</select>' );

		} else if (title == "Channel") {
                $(this).html('<select class="form-control search-by-status">' +
					'<option value="">select Channel</option>' +
						@foreach($channelList as $channel)
                            '<option value="{{$channel}}">{{$channel}}</option>' +
						@endforeach
					'</select>');

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
        ajax: "{{ url(config('laraadmin.adminRoute') . '/warmProjectsajax') }}",
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
	      { width: '5%', targets: 6 },
	      { width: '5%', targets: 7 },
	      { width: '5%', targets: 8 },
	      { width: '10%', targets: 9 },
	      { width: '10%', targets: 10 },
	      { width: '5%', targets: 11 },
	      { width: '10%', targets: 12 },
	      { width: '10%', targets: 13 },
	      { width: '10%', targets: 14 }
	    ],
		fixedColumns:   {
            leftColumns: 0,            
            rightColumns: 1,
        },
        
	});
	$("#project-add-form").validate({
		
	});

	$("#bulkDelete").on('click',function() { // bulk checked
        var status = this.checked;
        $(".deleteRow").each( function() {
            $(this).prop("checked",status);
        });
    });
     
    $('#deleteTriger').on("click", function(event){ // triggering delete one by one
        $('.warning-error-deletion').addClass('hidden');
        if( $('.deleteRow:checked').length > 0 ){  // at-least one checkbox checked
            var ids = [];
            $('.deleteRow').each(function(){
                if($(this).is(':checked')) { 
                    ids.push($(this).val());
                }
            });            
            var _token = $('meta[name="csrf-token"]').attr('content');
            var ids_string = ids.toString();  // array to string conversion 
            $.ajax({
                type: "post",
                url: "multi_projects_delete",
                data: {data_ids:ids_string,_token:_token},
                success: function(result) {                	
                    //table.draw(); // redrawing datatable
                    var arrProjectIds = ids_string.split(",");                 
                	$.each(arrProjectIds, function( index, value ) {                		
                		$('table#table tbody tr td').find('input.deleteRow#'+value ).closest('tr.danger').css('display',"none");
					});
                    $("#bulkDelete").prop("checked", false);
					location.reload();
                },
                async:false
            });
        } else {
        	$('.warning-error-deletion').removeClass('hidden');
        }	
    });
});


</script>
@endpush
