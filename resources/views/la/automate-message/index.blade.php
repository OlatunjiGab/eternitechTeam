@extends("la.layouts.app")

@section("contentheader_title", "Automated Followups")
@section("contentheader_description", "Automated Followups listing")
@section("section", "Automated Followups")
@section("sub_section", "Listing")
@section("htmlheader_title", "Automated Followups Listing")

@section("headerElems")
	<a href="{{ route(config('laraadmin.adminRoute').'.automate-message.create') }}" class="btn btn-success btn-sm pull-right">Add Automated Follow-up</a>
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
			<th>Template</th>
			<th>Delay Unit</th>
			<th>Delay Value</th>
			<th>Trigger Event Type</th>
			<th>Lead Source</th>
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
        ajax: "{{ url(config('laraadmin.adminRoute') . '/automate-message-ajax') }}",
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
