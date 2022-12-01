@extends("la.layouts.app")

@section("contentheader_title", "Partners by skills")
@section("contentheader_description", "Partners listing")
@section("section", "Partners by skills")
@section("sub_section", "Listing")
@section("htmlheader_title", "Partners by skills Listing")

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

<div class="box box-success">
	<!--<div class="box-header"></div>-->
	<div class="box-body">
		<table id="table" class="table table-bordered">
		<thead>
		<tr class="success">
			<th>Id</th>
			<th>Partner Name</th>
			<th>skills</th>
			<th>Experience</th>
			<th>Rate</th>
			<th>Comment</th>
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
        ajax: "{{ url(config('laraadmin.adminRoute') . '/partners-by-skills-ajax') }}",
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
	$("#supplier-add-form").validate({
		
	});
});
</script>
@endpush
