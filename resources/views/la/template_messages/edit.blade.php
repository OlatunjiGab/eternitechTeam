@extends("la.layouts.app")

@section("contentheader_title")
	<a href="{{ route(config('laraadmin.adminRoute') . '.templates.index') }}">Messages</a>
@endsection
@section("section", "Messages")
@section("section_url", route(config('laraadmin.adminRoute') . '.templates.index'))
@section("sub_section", "Edit")

@section("htmlheader_title", "Message Template Update")

@section("main-content")

@include("flash.message")


<div class="box">
	<div class="box-header">

	</div>
	<div class="box-body">
		{!! Form::model('',['route' => [config('laraadmin.adminRoute') . '.templates.update', $id],'method' => 'PATCH','files' => true]) !!}
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<ul class="nav nav-tabs">
							@if(isset($templates) && count($templates) > 0)
								@php
									$cnt = 0;
								@endphp
								@foreach($templates as $key => $template)
									@php
										$cnt++;
									@endphp
									@php
										$languageName= \App\Models\Language::select('name')->where(['code' =>$template->language])->first()->toArray();
									@endphp
									<li class="{{ $cnt==1 ? 'active' : '' }}"><a data-toggle="tab" href="#{{$template->language}}">{{$languageName['name']}}</a></li>
								@endforeach
							@endif
						</ul>
					</div>
				</div>
				<div class="tab-content">
					@if(isset($templates) && count($templates) > 0)
						@php
							$cnt = 0;
						@endphp
						@foreach($templates as $key => $template)
							@php
								$cnt++;
							@endphp
							@php
								$languageName= \App\Models\Language::select('name')->where(['code' =>$template->language])->first()->toArray();
							@endphp
							<div id="{{$template->language}}" class="tab-pane fade in {{ $cnt==1 ? 'active' : '' }}">
								<div class="row">
									<div class="col-md-9">
										<h4>&nbsp;</h4>
										<input type="hidden" name="template_message[{{$cnt}}][1]" value="{{$template->slug}}" placeholder="slug name">
										<input type="hidden" name="template_message[{{$cnt}}][2]" value="{{$template->language}}" placeholder="language">

										<div class="form-group {{ $errors->has('title') ? ' has-error' : '' }}">
											<label for="title">{{$languageName['name']}} Title :</label>
											<input type="text" name="template_message[{{$cnt}}][3]" class="form-control" placeholder="Enter Title" value="{{$template->title}}" {{ $cnt==1 ? 'required' : '' }}>
											@if ($errors->has('title'))
												<span class="help-block">
											<strong>{{ $errors->first('title') }}</strong>
										</span>
											@endif
										</div>


										<div class="form-group {{ $errors->has('content') ? ' has-error' : '' }}">
											<label for="title">{{$languageName['name']}} Content :</label>
											<textarea class="form-control textarea" name="template_message[{{$cnt}}][4]" rows="20" placeholder="Enter Content" {{ $cnt==1 ? 'required' : '' }}>{{$template->content}}</textarea>
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
	/*$("#TempleteType").on("change", function() {
	   templateval  = this.value;
	   templateid  = this.id;
	  if(templateval == "Message")
	 {
		let content = $('.textarea');
		let contentPar = content.parent();
		contentPar.find('.wysihtml5-toolbar').remove();
	 }
	 else
	 {
	  	 $('.textarea').wysihtml5();
	 }
	});*/
	 $(function () {   
	 	/*templateval  = $('#TempleteType').val();
		if(templateval == "Message"){ 
			let content = $('.textarea');
			let contentPar = content.parent();
			contentPar.find('.wysihtml5-toolbar').remove();
		 }
		 else
		 {     	
		  	 $('.textarea').wysihtml5();
		 }*/
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