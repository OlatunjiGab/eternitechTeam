@extends("la.layouts.app")

@section("contentheader_title", "Popups")
@section("contentheader_description", "Popups listing")
@section("section", "Popups")
@section("sub_section", "Listing")
@section("htmlheader_title", "Popups Listing")

@section("headerElems")
	<a href="{{ route(config('laraadmin.adminRoute').'.popup-content.create') }}" class="btn btn-success btn-sm pull-right">Add Popup</a>
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
					<th>Title</th>
					<th>Content</th>
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
				ordering: false,
				pageLength: 50,
				ajax: "{{ url(config('laraadmin.adminRoute') . '/popup-content-ajax') }}",
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
