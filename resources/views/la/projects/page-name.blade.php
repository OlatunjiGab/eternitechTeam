@extends("la.layouts.app")

@section("contentheader_title", $menu)
@section("contentheader_description", $menu." Listing")
@section("section", "Projects")
@section("sub_section", "Listing")
@section("htmlheader_title", "Leads Listing")

@section("headerElems")
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
			<div class="col-sm-3 form-group">
				<h5><b>Total {{$menu}}:</b> {{$totalCount}}</h5>
			</div>
			<div class="col-sm-12 warning-error-deletion hidden">
				<div class="alert alert-warning">
					Please select at least one record for the delete
			    </div>
			</div>
			<div class="col-sm-3 form-group">
			</div>
		</div>
				
		<table id="table" class="table table-bordered">
			<thead>
				<tr class="success">					
					@foreach( $listing_cols as $col )					
						@if($col=='id' || $col=='name' || $col=='description')
						<th>
							{{ $module->fields[$col]['label'] or ucfirst($col) }}
						</th>						
						@endif
					@endforeach
					<th>Client Name</th>
					<th>Client Phone</th>
					<th>Client Email</th>
					<th>Date and Time</th>
					<th>Form Name</th>
					<th>Page Name</th>
				</tr>
			</thead>
			<tbody>
				
			</tbody>
		</table>
		<div class="row">
			@if(Entrust::hasRole("SUPER_ADMIN"))
			<div class="col-sm-3 form-group">
				<div class="form-check">
					<input type="checkbox"  id="bulkDelete"/>
					<label class="form-check-label" for="bulkDelete">Select All</label>
					<button id="deleteTriger" class="btn btn-sm btn-danger" style="display: none" >Delete</button>
				</div>
			</div>
			@endif
		</div>
	</div>
</div>

@foreach($columnSettings as $key => $value)
<input type="hidden" name="dt_{{$key}}" id="dt_{{$key}}" value="{{$value}}">
@endforeach
@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('la-assets/plugins/datatables/datatables.min.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('la-assets/css/daterangepicker.css') }}"/>
<style>
.community-link {
	cursor:pointer;
	color:#48B0F7;
	text-decoration:underline
}
table.fixedHeader-floating{
	top: 50px !important;
}
.daterange-container {
	width: 16%;
	position: absolute;
	right: 20%;
	z-index: 1;
}
@media screen and (max-width: 767px) {
	.headerElems {
		float: none;
		display: contents;
	}
	.headerElems .btn {
		margin: 4px;
	}
	.daterange-container {
		position: initial;
		width: 100%;
	}
}
</style>
@endpush

@push('scripts')
<script src="{{ asset('la-assets/plugins/datatables/datatables.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('la-assets/js/moment.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('la-assets/js/daterangepicker.min.js') }}"></script>
<script>
$(function () {
	var menuName = '{{ $menu }}';
	$('#table thead tr').clone(true).appendTo( '#table thead' );
    $('#table thead tr:eq(1) th').each( function (i) {
        var title = $(this).text();        

		if (title == "Form Name") {
               	$(this).html('<select class="form-control search-by-status" id="source">' +
					'<option value="">All Form</option>' +
						@foreach($sourceList as $skey => $source)
                            '<option value="{{$skey}}">{{ucfirst($source)}}</option>' +
						@endforeach
					'</select>');
        } else if (title == "Date and Time") {
			$(this).html('');
		} else {
        	$(this).html( '<input type="text" class="form-control" style="min-width:147px; max-width:200px;" placeholder="Search '+title+'" />' );
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
	
	var dtAjaxUrl = "{{ url(config('laraadmin.adminRoute') . '/lead_dt_ajax') }}/"+menuName;
	
	@if(\App\Helpers\VisitorDetails::isMobileDevice())
	$.fn.DataTable.ext.pager.numbers_length = 5;
	@endif

	var table = $("#table").DataTable({
		"fnDrawCallback": function( oSettings ) {
			$("#bulkDelete").prop("checked", false);
		},
		//scrollY:        "1600px",
        //scrollX:        true,
        //scrollCollapse: true,
		fixedHeader: true,
		processing: true,
        serverSide: true,
		ordering: false,
        pageLength: 10,
		ajax: dtAjaxUrl,
		stateSave: true,
		stateDuration: 60,
		language: {
			paginate: {
				next: '&#8594;', // or '→'
				previous: '&#8592;' // or '←'
			},
			lengthMenu: "_MENU_",
			search: "_INPUT_",
			searchPlaceholder: "Search"
		},
		createdRow: function ( row, data, index ) {		    			
		},
		columnDefs: [
	    ],
		fixedColumns:   {
            leftColumns: 0,            
            rightColumns: 1,
        },
        
	});
	
	$("#bulkDelete").on('click',function() { // bulk checked
		if ($(this).is(':checked')) {
			$('#deleteTriger').show();
		} else {
			$('#deleteTriger').hide();
		}
        var status = this.checked;
        $(".deleteRow").each( function() {
            $(this).prop("checked",status);
        });
    });
     
    $('#deleteTriger').on("click", function(event){ // triggering delete one by one
        $('.warning-error-deletion').addClass('hidden');
        if( $('.deleteRow:checked').length > 0 ){  // at-least one checkbox checked
        	if(confirm("Are you sure you want to delete selected records?")){
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
					setTimeout(function() {
						location.reload();
					}, 1000);
                },
                async:false
            });
            }
        } else {
        	$('.warning-error-deletion').removeClass('hidden');
        }	
    });
});

$(function() {
	$('#source_search').change(function (){
		$('#source').val($('#source_search').val());
		$('#source').trigger('change');
	});
	$('#source').change(function (){
		$('#source_search').val($('#source').val());
		$('#source_search').trigger('change');
	});
});
$(document).on('click', '.deleteRow', function(){
	if (!$(this).is(':checked')) {
		$("#bulkDelete").attr("checked",false);
	}
	if ($(this).is(':checked')) {
		$('#deleteTriger').show();
	}
});
</script>
@endpush
