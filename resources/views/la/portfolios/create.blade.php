@extends("la.layouts.app")

@section("contentheader_title")
	<a href="{{ url(config('laraadmin.adminRoute') . '/portfolios') }}">Portfolios</a> :
@endsection
@section("contentheader_description", "Create Portfolio")
@section("section", "Portfolios")
@section("section_url", url(config('laraadmin.adminRoute') . '/portfolios'))
@section("sub_section", "create")

@push('styles')
<style>
.has-error .help-block {
	color: #f55753;
}
label#is_agree-error {
	position: absolute;
	top: 89%;
	left: 8%;
}
blockquote {
	font-size: inherit;
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
				{!! Form::open(['action' => 'LA\PortfoliosController@store', 'id' => 'portfolio-add-form', 'enctype'=>'multipart/form-data']) !!}
				<div class="panel panel-default ">
					<div class="panel-body">

						<div class="form-group {{ $errors->has('title') ? ' has-error' : '' }}">
							<label for="title">Title :</label>
							<input type="text" name="title" class="form-control" placeholder="Enter Title" value="{{old('title')??''}}">
							@if($errors->has('title'))
							<span class="help-block">
								<strong>{{ $errors->first('title') }}</strong>
							</span>
							@endif
						</div>

						<div class="form-group {{ $errors->has('client_name') ? ' has-error' : '' }}">
							<label for="client_name">Client Name :</label>
							<input type="text" name="client_name" class="form-control" placeholder="Enter Client Name" value="{{old('client_name')??''}}">
							@if($errors->has('client_name'))
							<span class="help-block">
								<strong>{{ $errors->first('client_name') }}</strong>
							</span>
							@endif
						</div>

						<div class="form-group {{ $errors->has('description') ? ' has-error' : '' }}">
							<label for="description">Project Description :</label>
							<textarea class="form-control textarea editor form-control" name="description" rows="7" placeholder="Enter Project Description">{{old('description')??''}}</textarea>
							@if($errors->has('description'))
							<span class="help-block">
								<strong>{{ $errors->first('description') }}</strong>
							</span>
							@endif
						</div>

						<div class="form-group {{ $errors->has('problem') ? ' has-error' : '' }}">
							<label for="problem">Problem :</label>
							<textarea class="form-control textarea editor form-control" name="problem" rows="7" placeholder="Enter Problem">{{old('problem')??''}}</textarea>
							@if($errors->has('problem'))
								<span class="help-block">
								<strong>{{ $errors->first('problem') }}</strong>
							</span>
							@endif
						</div>

						<div class="form-group {{ $errors->has('solution') ? ' has-error' : '' }}">
							<label for="solution">Solution :</label>
							<textarea class="form-control textarea editor form-control" name="solution" rows="7" placeholder="Enter Solution">{{old('solution')??''}}</textarea>
							@if($errors->has('solution'))
								<span class="help-block">
								<strong>{{ $errors->first('solution') }}</strong>
							</span>
							@endif
						</div>

						<div class="form-group {{ $errors->has('skills') ? ' has-error' : '' }}">
							@php $selectedSkills = array_values(old('skills')); @endphp
							<label for="skills">Skills :</label>
							<select class="form-control" name="skills[]" multiple="" rel="select2">
								@foreach($skillList as $key => $skill)
									<option value="{{$skill['id']}}" {{ in_array($skill['id'],$selectedSkills)? 'selected': ''}} >{{$skill['keyword']}}</option>
								@endforeach
							</select>
							@if($errors->has('skills'))
								<span class="help-block">
								<strong>{{ $errors->first('skills') }}</strong>
							</span>
							@endif
						</div>

						<div class="form-group {{ $errors->has('url') ? ' has-error' : '' }}">
							<label for="url">URL :</label>
							<input type="text" name="url" class="form-control" placeholder="Enter URL" value="{{old('url')??''}}">
							@if($errors->has('url'))
								<span class="help-block">
								<strong>{{ $errors->first('url') }}</strong>
							</span>
							@endif
						</div>

						<div class="form-group {{ $errors->has('video_embed_code') ? ' has-error' : '' }}">
							<label for="video_embed_code">Video Embed Code :</label>
							<textarea class="form-control textarea" name="video_embed_code" rows="3" placeholder="Enter Video Embed Code">{{old('video_embed_code')??''}}</textarea>
							@if($errors->has('video_embed_code'))
								<span class="help-block">
								<strong>{{ $errors->first('video_embed_code') }}</strong>
							</span>
							@endif
						</div>

						<div class="form-group {{ $errors->has('image_file') ? ' has-error' : '' }}">
							<label for="image_file">Image :</label>
							<input class="form-control" placeholder="Select Image file" name="image_file" type="file" accept="image/*">
							@if ($errors->has('image_file'))
								<span class="help-block">
								<strong>{{ $errors->first('image_file') }}</strong>
							</span>
							@endif
						</div>

						<div class="form-group {{ $errors->has('banner_file') ? ' has-error' : '' }}">
							<label for="image_file">Banner Image :</label>
							<input class="form-control" placeholder="Select Banner Image file" name="banner_file" type="file" accept="image/*">
							@if ($errors->has('banner_file'))
								<span class="help-block">
								<strong>{{ $errors->first('banner_file') }}</strong>
							</span>
							@endif
						</div>

						<div class="form-group {{ $errors->has('is_nda') ? ' has-error' : '' }}">
							<label for="is_nda">Is NDA? :</label>
							<select class="form-control" name="is_nda"  rel="select2">
							@foreach($isNDA as $key => $value)
								<option value="{{$key}}" {{ old('is_nda') == $key? 'selected': ''}} >{{$value}}</option>
							@endforeach
							</select>
							@if($errors->has('is_nda'))
							<span class="help-block">
								<strong>{{ $errors->first('is_nda') }}</strong>
							</span>
							@endif
						</div>

						@if(Entrust::hasRole('SUPER_ADMIN'))
						<div class="form-group {{ $errors->has('score') ? ' has-error' : '' }}">
							<label for="score">Score :</label>
							<select class="form-control" name="score"  rel="select2">
								@foreach($scoreList as $key => $value)
									<option value="{{$key}}" {{ old('score') == $key? 'selected': ''}} >{{$value}}</option>
								@endforeach
							</select>
							@if($errors->has('score'))
							<span class="help-block">
								<strong>{{ $errors->first('score') }}</strong>
							</span>
							@endif
						</div>
						<div class="form-group {{ $errors->has('is_live') ? ' has-error' : '' }}">
							<label for="is_live">Is Live? :</label>
							<select class="form-control" name="is_live"  rel="select2">
							@foreach($isLiveList as $key => $value)
								<option value="{{$key}}" {{ old('is_live') == $key? 'selected': ''}} >{{$value}}</option>
							@endforeach
							</select>
							@if($errors->has('is_live'))
							<span class="help-block">
								<strong>{{ $errors->first('is_live') }}</strong>
							</span>
							@endif
						</div>
						<div class="form-group {{ $errors->has('done_by_eternitech') ? ' has-error' : '' }}">
							<label for="done_by_eternitech">Done By Eternitech? :</label>
							<select class="form-control" name="done_by_eternitech"  rel="select2">
							@foreach($doneByList as $key => $value)
								<option value="{{$key}}" {{ old('done_by_eternitech') == $key? 'selected': ''}} >{{$value}}</option>
							@endforeach
							</select>
							@if($errors->has('done_by_eternitech'))
							<span class="help-block">
								<strong>{{ $errors->first('done_by_eternitech') }}</strong>
							</span>
							@endif
						</div>
						@else
							<input type="hidden" name="score" value="1">
							<input type="hidden" name="is_live" value="{{\App\Models\Portfolio::IS_NO}}">
							<input type="hidden" name="partner_id" value="{{Auth::user()->supplier_id}}">
						@endif

						@if(!Entrust::hasRole('SUPER_ADMIN'))
							<blockquote>
								<input type="checkbox" name="is_agree" required> Agree to showcase your project on our portfolio after completing successful verification.
							</blockquote>
						@endif
						<div class="form-group">
							{!! Form::submit( 'Submit', ['class'=>'btn btn-success']) !!} <button class="btn btn-default pull-right"><a href="{{ url(config('laraadmin.adminRoute') . '/portfolios') }}">Cancel</a></button>
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
			$("#portfolio-add-form").validate({

			});
		});
	</script>
@endpush
