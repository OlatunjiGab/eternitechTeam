@extends("la.layouts.app")

@section("contentheader_title", "Projects")
@section("contentheader_description", "Projects Translated")
@section("section", "Projects")
@section("sub_section", "Translated")
@section("htmlheader_title", "Projects Translated")

@section("main-content")

@include("flash.message")

<div class="box box-success">
	<div class="box-body">
		<table id="template" class="table table-bordered">
			<thead>
				<tr class="success">					
					@foreach( $listing_cols as $col )					
						<th>{{ $module->fields[$col]['label'] or ucfirst($col) }}</th>						
					@endforeach
						@if($show_client_name)
							<th>Client Name</th>
						@endif
						@if($show_company_phone)
							<th>Client Phone</th>
						@endif
						@if($show_company_name)
							<th>Client Email</th>
						@endif
						@if($project_attention)
							<th>Project Attention</th>
						@endif
						@if($created_at)
							<th>Created</th>
						@endif
						@if($source)
							<th>Source</th>
						@endif
						@if($affiliate)
							<th>Is Affiliate?</th>
						@endif
						@if($show_actions)
							<th>Actions</th>
						@endif
				</tr>
			</thead>
			<tbody>
				
			</tbody>
		</table>
		<div class="row">
			@if(Entrust::hasRole("SUPER_ADMIN"))
			<div class="col-sm-3 form-group">
				<div class="form-check">
					<input type="checkbox"  id="bulkDelete"/>
					<label class="form-check-label" for="bulkDelete">Select All</label>
					<button id="deleteTriger" class="btn btn-sm btn-danger" style="display: none" >Delete</button>
				</div>
			</div>
			@endif
		</div>
	</div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('la-assets/plugins/datatables/datatables.min.css') }}"/>
@endpush
