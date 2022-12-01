@extends("la.layouts.app")

@section("contentheader_title", "Prospects")
@section("contentheader_description", "Prospects listing")
@section("section", "Companies")
@section("sub_section", "Listing")
@section("htmlheader_title", "Prospects Listing")

@section("headerElems")
@if(LAFormMaker::la_access("Companies", "create"))
	<a href="{{ route(config('laraadmin.adminRoute').'.companies.create') }}" class="btn btn-success btn-sm pull-right">Add Prospect</a>
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
		<div class="daterange-container">
			<input type="text" name="daterange" value="" class="form-control input-sm" placeholder="Select Date Range" @if(Request::get('today') == 1) value="{{date('m/d/Y')}} - {{date('m/d/Y')}}" @endif />
		</div>
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
<link rel="stylesheet" type="text/css" href="{{ asset('la-assets/css/daterangepicker.css') }}"/>
<style>
.daterange-container {
	width: 16%;
	position: absolute;
	right: 20%;
	z-index: 1;
}
@media screen and (max-width: 767px) {
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
	$("#table").DataTable({
		processing: true,
        serverSide: true,
        pageLength: 50,
		ordering: false,
        ajax: "{{ url(config('laraadmin.adminRoute') . '/company_dt_ajax') }}",
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
		columnDefs: [
				{ orderable: false, targets: [-1] },
				{ targets: 16, "searchable": false}
		],
		@endif
	});
	$(document).on('click', 'a.allow-ban-btn', function(e) {
		$('a.allow-ban-btn').addClass('disabled');
	});
});
$(function () {
	$('input[name="daterange"]').daterangepicker({
		opens: 'left',
		locale: {
			cancelLabel: 'Clear'
		}
	}, function(start, end, label) {
		var table = $("#table").DataTable();
		table.columns(16).search(start.format('YYYY-MM-DD') + 'to' + end.format('YYYY-MM-DD')).draw();
	});
	$('input[name="daterange"]').val('');
	$('input[name="daterange"]').on('cancel.daterangepicker', function(ev, picker) {
		$(this).val('');
		var table = $("#table").DataTable();
		table.columns(16).search('').draw();
	});
	@if(Session::has('prospectPopupShow') && Session::get('prospectPopupShow'))
		setTimeout(function (){
			$('#prospect-guideline-popup').modal('show');
		},2000);
	@php Session::put('prospectPopupShow',0); @endphp
	@endif
});
</script>
@endpush
