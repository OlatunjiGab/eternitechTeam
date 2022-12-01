@extends("la.layouts.app")

@section("contentheader_title", "Languages")
@section("contentheader_description", "Languages listing")
@section("section", "Languages")
@section("sub_section", "Listing")
@section("htmlheader_title", "Languages Listing")

@section("headerElems")
		
@endsection

@section("main-content")

@include("flash.message")

<div class="box box-success">
	<!--<div class="box-header"></div>-->
	<div class="box-body">
		<table id="table" class="table table-bordered">
		<thead>
		<tr class="success">
			<th>#</th>
			<th>Name</th>
			<th>Code</th>			
			<th>Status</th>			
			<th>Actions</th>
		</tr>
		</thead>
		<tbody>
			
		</tbody>
		</table>
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
	$("#table").DataTable({
		processing: true,
        serverSide: true,
        pageLength: 50,
        ajax: "{{ url(config('laraadmin.adminRoute') . '/language_dt_ajax') }}",
		language: {
			paginate: {
				next: '&#8594;', // or '→'
				previous: '&#8592;' // or '←'
			},
			lengthMenu: "_MENU_",
			search: "_INPUT_",
			searchPlaceholder: "Search"
		},
		columnDefs: [ { orderable: false, targets: [-1] }],
	});
});
</script>
@endpush
