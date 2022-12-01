@extends("la.layouts.app")

@section("contentheader_title", "Template")
@section("contentheader_description", "Template Statistics")
@section("section", "Template")
@section("sub_section", "Statistics")
@section("htmlheader_title", "Template Statistics")

@section("main-content")

@include("flash.message")

<div class="box box-success">
	<div class="box-body">
		<table id="template" class="table table-bordered">
		<thead>
		<tr class="success">
			<th>#</th>
			<th>Template</th>
			<th>Version</th>
			<th>Total Send count</th>
			<th>Total Unread count</th>
			<th>Total Open count</th>
			<th>Total Clicks count</th>
		</tr>
		</thead>
		<tbody>
			@foreach($data as $key=>$totalSendTemplate)
                <tr>
                	<td>{{ $key+1 }}</td>
                	<td>{{ $totalSendTemplate['template'] }}</td>
                	<td>{{ $totalSendTemplate['version'] }}</td>
                	<td>@if(isset($totalSendTemplate['cntSend'])) {{$totalSendTemplate['cntSend']}} @endif</td>
                	<td>@if(isset($totalSendTemplate['cntUnread'])) {{$totalSendTemplate['cntUnread']}} @endif</td>
                	<td>@if(isset($totalSendTemplate['cntOpen'])) {{$totalSendTemplate['cntOpen']}} @endif</td>
                	<td>@if(isset($totalSendTemplate['cntClick'])) {{$totalSendTemplate['cntClick']}} @endif</td>
                </tr>
            @endforeach
		</tbody>
		</table>
	</div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('la-assets/plugins/datatables/datatables.min.css') }}"/>
@endpush
