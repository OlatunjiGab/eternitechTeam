@extends("la.layouts.app")

@section("contentheader_title")
	<a href="{{ route(config('laraadmin.adminRoute') . '.templates.index') }}">Messages</a> :
@endsection
@section("section", "Messages")
@section("section_url", route(config('laraadmin.adminRoute') . '.templates.index'))
@section("sub_section", "Add")

@section("htmlheader_title", "Message Template Add")

@section("main-content")

@include("flash.message")

<div class="box">
	<div class="box-header">
		
	</div>
	<div class="box-body">

				{!! Form::model('', ['route' => [config('laraadmin.adminRoute') . '.templates.store'], 'method'=>'POST', 'id' => 'add-edit-form']) !!}
				<div class="container">
					<div class="row">
						<div class="col-md-12">
					<ul class="nav nav-tabs">
						@if(isset($aLanguageData) && count($aLanguageData) > 0)
							@php
								$cnt=0;
							@endphp
							@foreach($aLanguageData as $code => $value)
								@php
									$cnt++;
								@endphp
								<li class="{{ $cnt==1 ? 'active' : '' }}"><a data-toggle="tab" href="#{{$code}}">{{$value}}</a></li>
							@endforeach
						@endif
					</ul>
						</div>
					</div>

					<div class="tab-content">
						@if(isset($aLanguageData) && count($aLanguageData) > 0)
							@php
								$cnt=0;
							@endphp
							@foreach($aLanguageData as $code => $value)
								@php
									$cnt++;
								@endphp
								<div id="{{$code}}" class="tab-pane fade in {{ $cnt==1 ? 'active' : '' }}">
									<div class="row">
										<div class="col-md-9 ">
											<h4>&nbsp;</h4>
												<input type="hidden" name="template_message[{{$cnt}}][1]" value="{{config('constant.templates.0').'_'.$code}}" placeholder="slug name">
												<input type="hidden" name="template_message[{{$cnt}}][2]" value="{{$code}}" placeholder="language">

												<div class="form-group {{ $errors->has('title') ? ' has-error' : '' }}">
													<label for="title">{{$value}} Title :</label>
													<input type="text" name="template_message[{{$cnt}}][3]" class="form-control" placeholder="Enter Title" @if($cnt==1)  required @endif pattern="[a-zA-Z][a-zA-Z ]{2,}">
													@if ($errors->has('title'))
														<span class="help-block">
						                            <strong>{{ $errors->first('title') }}</strong>
						                        </span>
													@endif
												</div>


												<div class="form-group {{ $errors->has('content') ? ' has-error' : '' }}">
													<label for="title">{{$value}} Content :</label>
													<textarea class="form-control textarea" name="template_message[{{$cnt}}][4]" rows="20" placeholder="Enter Content" {{ $cnt==1 ? 'required' : '' }}></textarea>
													@if ($errors->has('content'))
														<span class="help-block">
						                            <strong>{{ $errors->first('content') }}</strong>
						                        </span>
													@endif
												</div>
										</div>
										<div class="col-md-2">
											@include('la.template_messages.variables')
										</div>
									</div>
								</div>
							@endforeach
							<div class="row">
								<div class="col-md-8">
									<div class="form-group">
									<button type="submit" class="btn btn-success">Save All</button>
									<a href="{{ route(config('laraadmin.adminRoute') . '.templates.index') }}" class="btn btn-default pull-right">Cancel</a>
									</div>
								</div>
							</div>
						@endif
					</div>

				</div>
				{!! Form::close() !!}
	</div>
</div>

@endsection
@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('la-assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') }}"/>
@endpush
@push('scripts')
<script src="{{asset('la-assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js')}}"></script>
<script>
  $(function () {
    //$('.textarea').wysihtml5();
    $('form#send-message-form').submit(function(e){
    	if($(this).find('.textarea').val()!=''){
    		return true;
    	}
    	e.preventDefault();
    	return false;
    });
	$('.copy_text').click(function (e) {
	  e.preventDefault();
	  var copyText = $(this).attr('id');
	  document.addEventListener('copy', function(e) {
		  e.clipboardData.setData('text/plain', copyText);
		  e.preventDefault();
	  }, true);
	  document.execCommand('copy');
	});
  });
</script>
@endpush