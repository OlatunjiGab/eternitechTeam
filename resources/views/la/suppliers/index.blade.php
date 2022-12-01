@extends("la.layouts.app")

@section("contentheader_title", "Partners")
@section("contentheader_description", "Partners listing")
@section("section", "Partners")
@section("sub_section", "Listing")
@section("htmlheader_title", "Partners Listing")

@section("headerElems")
@if(LAFormMaker::la_access("Suppliers", "create"))
	<a href="partners-by-skills" class="btn btn-success btn-sm pull-right"> Partners by skills</a>
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

<div class="box box-success">
	<!--<div class="box-header"></div>-->
	<div class="box-body">
		<table id="table" class="table table-bordered">
		<thead>
		<tr class="success">
			@foreach( $listing_cols as $col )
			<th>{{ $module->fields[$col]['label'] or ucfirst($col) }}</th>
			@endforeach
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

@if(LAFormMaker::la_access("Suppliers", "create"))
<div class="modal fade" id="AddModal" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Add Supplier</h4>
			</div>
			{!! Form::open(['action' => 'LA\SuppliersController@store', 'id' => 'supplier-add-form']) !!}
			<div class="modal-body">
				<div class="box-body">
					{!! LAFormMaker::form($module)  !!}
					
					{{--
					@la_input($module, 'company_id')
					@la_input($module, 'closing_rate')
					@la_input($module, 'avg_response_time')
					@la_input($module, 'hourly_rate')
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

@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('la-assets/plugins/datatables/datatables.min.css') }}"/>
@endpush

@push('scripts')
<script src="{{ asset('la-assets/plugins/datatables/datatables.min.js') }}"></script>
<script>
$(function () {
	$("#table").DataTable({
		processing: true,
        serverSide: true,
        pageLength: 50,
        ajax: "{{ url(config('laraadmin.adminRoute') . '/supplier_dt_ajax') }}",
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
		columnDefs: [ { orderable: false, targets: [-1] }],
		@endif
	});
	$("#supplier-add-form").validate({
		
	});

	@if(Session::has('partnerPopupShow') && Session::get('partnerPopupShow'))
	setTimeout(function (){
		$('#partner-guideline-popup').modal('show');
	},2000);
	@php Session::put('partnerPopupShow',0); @endphp
	@endif
});
</script>
@endpush
