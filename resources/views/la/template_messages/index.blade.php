@extends("la.layouts.app")

@section("contentheader_title", "Messages")
@section("contentheader_description", "Messages listing")
@section("section", "Messages")
@section("sub_section", "Listing")
@section("htmlheader_title", "Messages Listing")

@section("headerElems")
	<a href="{{ route(config('laraadmin.adminRoute').'.templates.create') }}" class="btn btn-success btn-sm pull-right">Add Message Template</a>

	{{--<a href="{{ url(config('laraadmin.adminRoute') . '/templates/all_template_edit/') }}" class="btn btn-success btn-sm">Add / Edit Template</a>&nbsp;&nbsp;&nbsp;&nbsp;--}}
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
			<th>Slug</th>
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
        pageLength: 50,
        ajax: "{{ url(config('laraadmin.adminRoute') . '/template_dt_ajax') }}",
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
