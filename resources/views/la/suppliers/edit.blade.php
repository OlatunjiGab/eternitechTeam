@extends("la.layouts.app")

@section("contentheader_title")
	<a href="{{ url(config('laraadmin.adminRoute') . '/partners') }}">Partners</a> :
@endsection
@section("contentheader_description", $supplier->$view_col)
@section("section", "Partners")
@section("section_url", url(config('laraadmin.adminRoute') . '/partners'))
@section("sub_section", "Edit")

@section("htmlheader_title", "Partners Edit : ".$supplier->$view_col)

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

@include('flash.message')

<div class="box">
	<div class="box-header">
		
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				{!! Form::model($supplier, ['route' => [config('laraadmin.adminRoute') . '.partners.update', $supplier->id ], 'method'=>'PUT', 'id' => 'supplier-edit-form']) !!}
					{{--@la_form($module)
					--}}
					
					{{--@la_input($module, 'company_id')--}}

					<div class="form-group">
						<label for="closing_rate">Closing Rate :</label>
						<input class="form-control" placeholder="Enter Closing Rate" data-rule-maxlength="100" name="closing_rate" type="text" value="{{old('closing_rate')?:$supplier->closing_rate}}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" maxlength="10">
					</div>

					<div class="form-group">
						<label for="avg_response_time">Average Response Time :</label>
						<input class="form-control" placeholder="Enter Average Response Time" data-rule-maxlength="100" name="avg_response_time" type="text" value="{{old('avg_response_time')?:$supplier->avg_response_time}}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" maxlength="10">
					</div>

					<div class="form-group">
						<label for="hourly_rate">Hourly Rate:</label>
						<input class="form-control" placeholder="Enter Hourly Rate" data-rule-maxlength="100" name="hourly_rate" type="text" value="{{old('hourly_rate')?:$supplier->hourly_rate}}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" maxlength="10">
					</div>
				
					<div class="form-group">
						<label for="channel">Skill :</label>
						<select class="form-control valid" name="skill[]" multiple="" rel="select2">
							@foreach($aSkill as $key=>$skill)
								<option value="{{$key}}" {{ in_array($key,$supllierSkill)?'selected=selected':'' }}>{{$skill}}</option>
							@endforeach
						</select>
					</div>
					
                    <br>
					<div class="form-group">
						{!! Form::submit( 'Update', ['class'=>'btn btn-success']) !!} 
						<a href="{{ url(config('laraadmin.adminRoute') . '/partners') }}" class="btn btn-default pull-right">Cancel</a>
					</div>
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>

@endsection

@push('scripts')
<script>
$(function () {
	$("#supplier-edit-form").validate({
		
	});
});
</script>
@endpush
