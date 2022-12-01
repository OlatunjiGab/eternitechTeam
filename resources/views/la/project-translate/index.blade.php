@extends("la.layouts.app")

@section("contentheader_title", "Leads Training")
@section("contentheader_description", "Leads Training Listing")
@section("section", "Leads Training")
@section("sub_section", "Listing")
@section("htmlheader_title", "Leads Training Listing")

@section("headerElems")
	<a href="{{url('admin/translate-leads')}}" class="btn btn-success btn-sm">Excel Export</a>&nbsp;&nbsp;
	<a href="{{url('admin/mysql-translate-leads')}}" class="btn btn-success btn-sm">Mysql Export</a>
@endsection

@section("main-content")

@if(session()->has('success'))
<p class="alert alert-success">{{ session()->get('success') }}</p>
@endif

<div class="box box-success">
	<div class="box-body">
		<div class="row">
			<div class="col-sm-4 form-group">
				<h5><b>Trained count:</b> {{$trained}} &nbsp;&nbsp; <b>Untrained count:</b> {{$untrained}}</h5>
			</div>
		</div>
			
		<table id="table" class="table table-bordered">
			<thead>
				<tr class="success">					
					<th>Id</th>
					<th>Name</th>
					<th>Description</th>
					<th>Categories</th>
					<th>Channel</th>
					<th>Competition</th>
					<th>Project Type</th>
					<th>Remote</th>
					<th>Provider Type</th>
					<th>Provider Experience</th>
					<th>Qualification</th>
					<th>Project Length</th>
					<th>Project State</th>
					<th>Project Urgency</th>
					<th>Budget</th>
					<th>Client Knowlegeable</th>
					<th>Client Experience with dev</th>
					<th>Industry</th>
					<th>Is client IT company</th>
					<th>Client company size</th>
					<th>Is Trained</th>
					<th>Is IT Related</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				
			</tbody>
		</table>
	</div>
</div>
<div class="modal fade in" id="description-popup">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header text-center">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span></button>
					<h4 class="modal-titles">Description</h4>
			</div>
			<div class="modal-body">
				<p class="text-center desc" style="overflow-x: auto;"></p>
			</div>
		</div>
	</div>
</div>
<div class="copy-input"></div>
@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('la-assets/plugins/datatables/datatables.min.css') }}"/>
<style>
.community-link {
	cursor:pointer;
	color:#48B0F7;
	text-decoration:underline
}
table.fixedHeader-floating{
	top: 50px !important;
}
@media screen and (max-width: 767px) {
	.headerElems {
		float: none;
		display: contents;
	}
	.headerElems .btn {
		margin: 4px;
	}
}
</style>
@endpush

@push('scripts')
<script src="{{ asset('la-assets/plugins/datatables/datatables.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('la-assets/js/moment.min.js') }}"></script>
	<script>
	$(function () {
		$('#table thead tr').clone(true).appendTo( '#table thead' );
	    $('#table thead tr:eq(1) th').each( function (i) {
	        var title = $(this).text();
			if(title == "Is Trained") {
				$(this).html('<select class="form-control" id="is_trained">' +
						'<option value="">All Trained</option>' +
						'<option value="1">Yes</option>'+
						'<option value="0">No</option>'+
						'</select>');
			} else if(title == "Is IT Related") {
				$(this).html('<select class="form-control" name="is_it_related" id="is_it_related">' +
						'<option value="">All IT Related</option>' +
						'<option value="1">Yes</option>'+
						'<option value="0">No</option>'+
						'</select>');
			} else {
				$(this).html('');
			}
			$( 'select', this ).on( 'change', function () {
	        	if ( table.column(i).search() !== this.value ) {
	                table
	                    .column(i)
	                    .search( this.value )
	                    .draw();
	            }
	        });
	    });
	    var dtAjaxUrl = "{{ url(config('laraadmin.adminRoute') . '/project_translate_dt_ajax') }}";
		var table = $("#table").DataTable({
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
			columnDefs: [],
		});
	});

	$(document).on('click', '.show_more_desc', function(e){
		var id = e.target.id;
		var url = "{{ url(config('laraadmin.adminRoute') . '/project_translate_description') }}";
		$.ajax({
                type: "get",
                url: url+"?id="+id,
                //data: {_token:_token},
                success: function(result) {
                	$('#description-popup p.desc').text(result);
                	$('#description-popup').modal('show');
                },
                async:false
            });
	});
	</script>
@endpush
