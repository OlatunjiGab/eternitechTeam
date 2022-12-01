@extends("la.layouts.app")

@section("contentheader_title", "Leads")
@section("contentheader_description", "Leads Listing")
@section("section", "Projects")
@section("sub_section", "Listing")
@section("htmlheader_title", "Leads Listing")
@section("main-content")

    @include('datatable.layout')

@endsection

@push('scripts')
<script>
	function copyCommunityLink(event) {
		var value= `<input value="${$(event).text()}" id="selVal" />`;
		$(value).insertAfter('.copy-input');
		$("#selVal").select();
		document.execCommand("Copy");
		$('body').find("#selVal").remove();
	    $(event).attr('data-original-title', 'Copied!').tooltip('show');
		setTimeout(function() {
			$(event).attr('data-original-title', 'Click to copy link').tooltip('hide');
		}, 3000);
	}

    @if($type == 'affiliate')
    	var dtAjaxUrl = "{{ url(config('laraadmin.adminRoute') . '/affiliate_dt_ajax/affiliate') }}";
    @else
    	var dtAjaxUrl = "{{ url(config('laraadmin.adminRoute') . '/project_dt_ajax') }}";
        @if(Request::get('today') == 1 ||Request::get('today') == 2 ||Request::get('today') == 3 ||Request::get('today') == 4 ||Request::get('today') == 5)
            dtAjaxUrl = dtAjaxUrl + "/{{Request::get('today')}}/{{Request::get('date')}}";
        @endif
    @endif

    $(function () {
        $('.search-by-skills.select2').select2();
    });
</script>
@endpush
