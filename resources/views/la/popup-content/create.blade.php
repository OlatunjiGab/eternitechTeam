@extends("la.layouts.app")

@section("contentheader_title")
	<a href="{{ url(config('laraadmin.adminRoute') . '/popup-content') }}">Popup</a> :
@endsection
@section("contentheader_description", "Create Popup")
@section("section", "Popup")
@section("section_url", url(config('laraadmin.adminRoute') . '/popup-content'))
@section("sub_section", "create")

@push('styles')
<style>
.has-error .help-block {
	color: #f55753;
}
</style>
@endpush
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

<div class="box">
	<div class="box-header"></div>
	<div class="box-body">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				{!! Form::open(['action' => 'LA\PopupContentController@store', 'id' => 'popup-content-add-form', 'enctype'=>'multipart/form-data']) !!}
				<div class="panel panel-default ">
					<div class="panel-body">
						<div class="form-group {{ $errors->has('title') ? ' has-error' : '' }}">
							<label for="title">Title :</label>
							<input type="text" name="title" class="form-control" placeholder="Enter Title" value="{{old('title')??''}}">
							@if ($errors->has('title'))
							<span class="help-block">
								<strong>{{ $errors->first('title') }}</strong>
							</span>
							@endif
						</div>
						<div class="form-group {{ $errors->has('content') ? ' has-error' : '' }}">
							<label for="title">Content :</label>
							<textarea class="form-control textarea" name="content" rows="5" placeholder="Enter Content">{{old('content')??''}}</textarea>
							@if ($errors->has('content'))
							<span class="help-block">
								<strong>{{ $errors->first('content') }}</strong>
							</span>
							@endif
						</div>
						<div class="form-group {{ $errors->has('image') ? ' has-error' : '' }}">
							<label for="image">Background Image :</label>
							<input class="form-control" placeholder="Select Background Image" name="image" type="file" accept="image/*">
							@if ($errors->has('image'))
							<span class="help-block">
								<strong>{{ $errors->first('image') }}</strong>
							</span>
							@endif
						</div>
						<div class="form-group {{ $errors->has('specific_page') ? ' has-error' : '' }}">
							<label for="specific_page">On specific Page? :</label>
							<input type="text" name="specific_page" class="form-control" placeholder="Enter Specific Page URL" value="{{old('specific_page')??''}}">
							@if ($errors->has('specific_page'))
							<span class="help-block">
								<strong>{{ $errors->first('specific_page') }}</strong>
							</span>
							@endif
						</div>
						<div class="form-group {{ $errors->has('pop_after') ? ' has-error' : '' }}">
							<label for="pop_after">Show popup after in Second? :</label>
							<input type="text" name="pop_after" class="form-control" placeholder="Enter in Second" value="{{old('pop_after')??''}}">
							@if ($errors->has('pop_after'))
							<span class="help-block">
								<strong>{{ $errors->first('pop_after') }}</strong>
							</span>
							@endif
						</div>
						<div class="form-group {{ $errors->has('country') ? ' has-error' : '' }}">
							<label for="country">Country :</label>
							<select class="form-control select2-hidden-accessible" data-placeholder="Any Country" rel="select2" name="country[]" tabindex="-1" aria-hidden="true" multiple="multiple">
								<option value="">Any Country</option>
								@foreach($countriesList as $code => $country)
									<option value="{{$country['iso']}}" {{ old('country') == $country['iso']? 'selected': ''}}>{{$country['name']}}</option>
								@endforeach
							</select>
							@if ($errors->has('country'))
							<span class="help-block">
								<strong>{{ $errors->first('country') }}</strong>
							</span>
							@endif
						</div>
						<div class="form-group {{ $errors->has('source') ? ' has-error' : '' }}">
							<label for="country">Source :</label>
							<select class="form-control select2-hidden-accessible" data-placeholder="Select Source" rel="select2" name="source[]" tabindex="-1" aria-hidden="true" multiple="multiple">
								<option value="">select Source</option>
								@foreach($sourceList as $key => $value)
									<option value="{{$key}}" {{ old('source') == $key? 'selected': ''}}>{{$value}}</option>
								@endforeach
							</select>
							@if ($errors->has('source'))
								<span class="help-block">
									<strong>{{ $errors->first('source') }}</strong>
								</span>
							@endif
						</div>
						<div class="form-group {{ $errors->has('status') ? ' has-error' : '' }}">
							<label for="country">Status :</label>
							<select class="form-control select2-hidden-accessible" data-placeholder="Select Status" rel="select2" name="status[]" tabindex="-1" aria-hidden="true" multiple="multiple">
								<option value="">select Status</option>
								@foreach($statusList as $status)
									<option value="{{$status}}" {{ old('status') == $status? 'selected': ''}}>{{ \App\Models\Project::getStatusName($status)}}</option>
								@endforeach
							</select>
							@if ($errors->has('status'))
								<span class="help-block">
										<strong>{{ $errors->first('status') }}</strong>
									</span>
							@endif
						</div>
						@if(Entrust::hasRole('SUPER_ADMIN'))
						<div class="form-group {{ $errors->has('company_id') ? ' has-error' : '' }}">
							<label for="country">Client :</label>
							<select class="form-control select2-hidden-accessible" data-placeholder="Select Client" rel="select2" name="company_id" tabindex="-1" aria-hidden="true">
								<option value="">Select Client</option>
								@foreach($companyList as $key => $value)
									<option value="{{$key}}" {{ old('company_id') == $key? 'selected': ''}}>{{$value}}</option>
								@endforeach
							</select>
							@if ($errors->has('company_id'))
								<span class="help-block">
									<strong>{{ $errors->first('company_id') }}</strong>
								</span>
							@endif
						</div>
						@else
							<input type="hidden" name="company_id" value="{{Auth::user()->company_id}}">
						@endif
						<div class="form-group">
							{!! Form::submit( 'Submit', ['class'=>'btn btn-success']) !!} <button class="btn btn-default pull-right"><a href="{{ url(config('laraadmin.adminRoute') . '/popup-content') }}">Cancel</a></button>
						</div>
					</div>
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
			$("#popup-content-add-form").validate({

			});
		});
	</script>
@endpush
