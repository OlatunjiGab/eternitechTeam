@extends("la.layouts.app")

@section("contentheader_title", "Skills")
@section("contentheader_description", "Skills listing")
@section("section", "Skills")
@section("sub_section", "Listing")
@section("htmlheader_title", "Skills Listing")

@section("headerElems")
@if(LAFormMaker::la_access("Skills", "create"))
	<a href="{{ route(config('laraadmin.adminRoute').'.skills.create') }}" class="btn btn-success btn-sm pull-right">Add Skill</a>
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
        ajax: "{{ url(config('laraadmin.adminRoute') . '/skill_dt_ajax') }}",
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
});
</script>
@endpush
