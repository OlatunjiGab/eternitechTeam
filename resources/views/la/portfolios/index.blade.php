@extends("la.layouts.app")

@section("contentheader_title", "Portfolios")
@section("contentheader_description", "Portfolios listing")
@section("section", "Portfolios")
@section("sub_section", "Listing")
@section("htmlheader_title", "Portfolios Listing")

@section("headerElems")
	<a href="{{ route(config('laraadmin.adminRoute').'.portfolios.create') }}" class="btn btn-success btn-sm pull-right">Add Portfolio</a>
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
					<th>Slug</th>
					<th>Client Name</th>
					@if(Entrust::hasRole("SUPER_ADMIN"))
					<th>Is Live</th>
					@endif
					<th>Added At</th>
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
				pageLength: 10,
				ajax: "{{ url(config('laraadmin.adminRoute') . '/portfolios-ajax') }}",
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
