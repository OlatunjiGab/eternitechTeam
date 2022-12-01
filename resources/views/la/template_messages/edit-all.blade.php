@extends("la.layouts.app")

@section("contentheader_title")
	<a href="{{ route(config('laraadmin.adminRoute') . '.templates.index') }}">Messages</a> :
@endsection
@section("section", "Messages")
@section("section_url", route(config('laraadmin.adminRoute') . '.templates.index'))
@section("sub_section", "Edit")

@section("htmlheader_title", "Message Template edit")

@section("main-content")

@include("flash.message")
<?php /*@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif*/ ?>

@php
	use App\Helpers\CustomHelper;	
@endphp

<div class="box">
	<div class="box-header">
		
	</div>
	<div class="box-body">
		<!-- <div class="row"> -->
			<!--============================= Tab container start here =================================-->
			{!! Form::model('', ['url' => [config('laraadmin.adminRoute') . '/templates/store_all'], 'method'=>'POST']) !!}

			<div class="container">			  
			  	<ul class="nav nav-tabs">
			  		@if(isset($aRowLanguageData) && count($aRowLanguageData) > 0)
						@php
							$cnt = 0;
						@endphp
			  			@foreach($aRowLanguageData as $aResLanguageData)
							@php
								$cnt++;
							@endphp
			  				<li class="{{ $cnt==1 ? 'active' : '' }}"><a data-toggle="tab" href="#{{$aResLanguageData->code}}">{{$aResLanguageData->name}}</a></li>		
			  			@endforeach
			  		@endif 
			  	</ul>
			  	<div class="tab-content">
			  		@if(isset($aRowLanguageData) && count($aRowLanguageData) > 0)
						@php
							$cnt = 0;
						@endphp
			  			@foreach($aRowLanguageData as $aResLanguageData)
							@php
								$cnt++;
							@endphp
			  				<div id="{{$aResLanguageData->code}}" class="tab-pane fade in {{ $cnt==1 ? 'active' : '' }}">	
			  					<div class="row">	
			  					<div class="col-md-9 ">						      	
							      	<h4>&nbsp;</h4>			  						
							      	@php
							      		$aRowTemplateMessage =  CustomHelper::singleData("template_messages",$aSelect='',['language'=>$aResLanguageData->code,'slug'=>$slug,]);
							      		
							      	@endphp
							      	@if(!empty($aRowTemplateMessage))	
							      		<div class="form-group {{ $errors->has('title') ? ' has-error' : '' }}">
											<label for="title">{{$aResLanguageData->name}} Title :</label>
											<input type="text" name="title[{{$aRowTemplateMessage->id}}]" value="{{$aRowTemplateMessage->title}}" class="form-control" placeholder="Enter Title">
											@if ($errors->has('title'))
						                        <span class="help-block">
						                            <strong>{{ $errors->first('title') }}</strong>
						                        </span>
						                    @endif
										</div>	

							      		<div class="form-group {{ $errors->has('content') ? ' has-error' : '' }}">
											<label for="title">{{$aResLanguageData->name}} Content :</label>
											<textarea class="form-control textarea" name="content[{{$aRowTemplateMessage->id}}]" rows="20" placeholder="Enter Content">{{$aRowTemplateMessage->content}}</textarea>
											@if ($errors->has('content'))
						                        <span class="help-block">
						                            <strong>{{ $errors->first('content') }}</strong>
						                        </span>
						                    @endif											
										</div>
							      	@else
							      		<input type="hidden" name="template_message[{{$cnt}}][1]" value="{{$slug}}" placeholder="slug name">
							      		<input type="hidden" name="template_message[{{$cnt}}][2]" value="{{$aResLanguageData->code}}" placeholder="language">

							      		<div class="form-group {{ $errors->has('title') ? ' has-error' : '' }}">
											<label for="title">{{$aResLanguageData->name}} Title :</label>
											<input type="text" name="template_message[{{$cnt}}][3]" class="form-control" placeholder="Enter Title">
											@if ($errors->has('title'))
						                        <span class="help-block">
						                            <strong>{{ $errors->first('title') }}</strong>
						                        </span>
						                    @endif
										</div>	


							      		<div class="form-group {{ $errors->has('content') ? ' has-error' : '' }}">
											<label for="title">{{$aResLanguageData->name}} Content :</label>
											<textarea class="form-control textarea" name="template_message[{{$cnt}}][4]" rows="20" placeholder="Enter Content"></textarea>
											@if ($errors->has('content'))
						                        <span class="help-block">
						                            <strong>{{ $errors->first('content') }}</strong>
						                        </span>
						                    @endif
										</div>
							      	@endif
			  					</div>
			  					<div class="col-md-2">
									@include('la.template_messages.variables')
			  					</div>
			  					</div>
			  				</div>	
			  			@endforeach
			  		@endif 
			  	</div>


			  	<?php /* ?>
			  	<ul class="nav nav-tabs">
			  		@if(isset($aRowTemplateData) && count($aRowTemplateData) > 0)
			  			@php($cnt=0)
			  			@foreach($aRowTemplateData as $aResTemplateData)
			  				@php($cnt++)
			  				@if(isset($aResTemplateData->getLanguage) && !empty($aResTemplateData->getLanguage))
			  					<li class="{{ $cnt==1 ? 'active' : '' }}"><a data-toggle="tab" href="#{{$aResTemplateData->getLanguage->code}}">{{$aResTemplateData->getLanguage->name}}</a></li>
			  				@endif
			  			@endforeach
			  		@endif
				</ul>

			  	<div class="tab-content">

			  		@if(isset($aRowTemplateData) && count($aRowTemplateData) > 0)
			  			@php($cnt=0)
			  			@foreach($aRowTemplateData as $aResTemplateData)

			  				@php($cnt++)
			  				@if(isset($aResTemplateData->getLanguage) && !empty($aResTemplateData->getLanguage))
			  					<div id="{{$aResTemplateData->getLanguage->code}}" class="tab-pane fade in {{ $cnt==1 ? 'active' : '' }}">
							      	<div class="row">
							      		{!! Form::hidden('id', $aResTemplateData->id) !!}
							      		<h3>&nbsp;</h3>
							      		<div class="col-md-8">
							      			<div class="form-group {{ $errors->has('title') ? ' has-error' : '' }}">
												<label for="title">{{$aResTemplateData->getLanguage->name}} Title :</label>
												<input type="text" class="form-control" name="title[{{$aResTemplateData->id}}]" placeholder="Enter Title" value="{{$aResTemplateData->title}}">
												@if ($errors->has('title'))
							                        <span class="help-block">
							                            <strong>{{ $errors->first('title') }}</strong>
							                        </span>
							                    @endif
											</div>
											<div class="form-group {{ $errors->has('content') ? ' has-error' : '' }}">
												<label for="title">{{$aResTemplateData->getLanguage->name}} Content :</label>
												<textarea class="form-control textarea" name="content[{{$aResTemplateData->id}}]" rows="20" placeholder="Enter Content">{{$aResTemplateData->content}}</textarea>
												@if ($errors->has('content'))
							                        <span class="help-block">
							                            <strong>{{ $errors->first('content') }}</strong>
							                        </span>
							                    @endif
											</div>
							      		</div>
							      	</div>
							    </div>
			  				@endif
			  			@endforeach
			  		@endif
			  		<div class="row">
			  			<div class="col-md-8">
			  				<button type="submit" class="btn btn-success">Save All</button>
			  			</div>
			  		</div>

			  	</div>
			  	<?php */ ?>
			  	<div class="row">
		  			<div class="col-md-8">
		  				<button type="submit" class="btn btn-success">Save All</button>
		  			</div>
		  		</div>
			</div>
			{!! Form::close() !!}
			<!--============================= Tab container end here =================================-->
		<!-- </div> -->
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
    })
    $('.copy_text').click(function (e) {
	   e.preventDefault();
	   var copyText = $(this).attr('id');

	   document.addEventListener('copy', function(e) {
	      e.clipboardData.setData('text/plain', copyText);
	      e.preventDefault();
	   }, true);

	   document.execCommand('copy');  
	   console.log('copied text : ', copyText);
	   // alert('copied text: ' + copyText); 
	 });
  });
</script>
@endpush