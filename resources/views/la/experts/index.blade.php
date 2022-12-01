@extends("la.layouts.app")

@section("contentheader_title", "Experts")
@section("contentheader_description", "Experts listing")
@section("section", "Experts")
@section("sub_section", "Listing")
@section("htmlheader_title", "Experts Listing")

@section("headerElems")
@if(LAFormMaker::la_access("Experts", "create"))
	<a href="{{ route(config('laraadmin.adminRoute').'.experts.create') }}" class="btn btn-success btn-sm pull-right">Add Expert</a>
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
			<th>{{ $module->fields[$col]['label'] or str_replace('_',' ',ucfirst($col)) }}</th>
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
        ajax: "{{ url(config('laraadmin.adminRoute') . '/expert_dt_ajax') }}",
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
$(document).on('click', '.is_live_btn', function(e) {
	e.preventDefault();
	var clickedBtn = $(this);
	var _token = $('meta[name="csrf-token"]').attr('content');
	$.ajax({
		type: "post",
		url: $(this).attr('href'),
		data: {_token:_token},
		success: function(result) {
			console.log(result);
			if(result.status == true){
				if(result.isLive == "Yes") {
					if(clickedBtn.hasClass('btn-primary')){
						clickedBtn.removeClass('btn-primary');
					}
					clickedBtn.addClass('btn-success');
					clickedBtn.text(result.isLive);
				} else {
					if(clickedBtn.hasClass('btn-success')){
						clickedBtn.removeClass('btn-success');
					}
					clickedBtn.addClass('btn-primary');
					clickedBtn.text(result.isLive);
				}
			}
		}
	});
});
</script>
@endpush
