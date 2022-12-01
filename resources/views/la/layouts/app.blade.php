<!DOCTYPE html>
<html lang="en">

@section('htmlheader')
	@include('la.layouts.partials.htmlheader')
@show
<body class="{{ LAConfigs::getByKey('skin') }} {{ LAConfigs::getByKey('layout') }} @if(LAConfigs::getByKey('layout') == 'sidebar-mini') sidebar-collapse @endif" bsurl="{{ url('') }}" adminRoute="{{ config('laraadmin.adminRoute') }}">
<div class="wrapper">

	@include('la.layouts.partials.mainheader')

	@if(LAConfigs::getByKey('layout') != 'layout-top-nav')
		@include('la.layouts.partials.sidebar')
	@endif

	<!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">
		@if(LAConfigs::getByKey('layout') == 'layout-top-nav') <div class="container"> @endif
		@if(!isset($no_header))
			@include('la.layouts.partials.contentheader')
		@endif
		
		<!-- Main content -->
		<section class="content {{ $no_padding or '' }}">
			<!-- Your Page Content Here -->
			@yield('main-content')
		</section><!-- /.content -->

		@if(LAConfigs::getByKey('layout') == 'layout-top-nav') </div> @endif
	</div><!-- /.content-wrapper -->

	@include('la.layouts.partials.controlsidebar')

	@include('la.layouts.partials.footer')

</div><!-- ./wrapper -->

<div class="modal fade in" id="disable-feature-popup">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header text-center">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span></button>
				<h4 class="modal-titles">Become a Premium Partner <svg width="20" fill="#48B0F7" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M64 192c27.25 0 51.75-11.5 69.25-29.75c15 54 64 93.75 122.8 93.75c70.75 0 127.1-57.25 127.1-128s-57.25-128-127.1-128c-50.38 0-93.63 29.38-114.5 71.75C124.1 47.75 96 32 64 32c0 33.37 17.12 62.75 43.13 80C81.13 129.3 64 158.6 64 192zM208 96h95.1C321.7 96 336 110.3 336 128h-160C176 110.3 190.3 96 208 96zM337.8 306.9L256 416L174.2 306.9C93.36 321.6 32 392.2 32 477.3c0 19.14 15.52 34.67 34.66 34.67H445.3c19.14 0 34.66-15.52 34.66-34.67C480 392.2 418.6 321.6 337.8 306.9z"/></svg></h4>
			</div>
			<div class="modal-body">
				<p class="text-center">This feature is available for Premium Partners only. To Access All Features, Go Premium. Contact support for more information.</p>
				<div class="text-center">
					<a href="{{url('admin/premium-plan-info')}}" class="btn btn-primary"> More Info</a>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade in" id="prospect-guideline-popup">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header text-center">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span></button>
				<h4 class="modal-title">My Prospects</h4>
			</div>
			<div class="modal-body">
				<p class="text-center">Add your self generated as Prospects and share it with the community to hire the right candidate.</p>
			</div>
		</div>
	</div>
</div>
<div class="modal fade in" id="partner-guideline-popup">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header text-center">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span></button>
				<h4 class="modal-title">My Partners</h4>
			</div>
			<div class="modal-body">
				<p class="text-center">Build Your Network! Go Premium to add your partners so you can assign them leads and share your requirements.</p>
			</div>
		</div>
	</div>
</div>
@include('la.layouts.partials.file_manager')

@section('scripts')
	@include('la.layouts.partials.scripts')
@show

@include('la.layouts.partials.phonepopup')

@yield ('footer_scripts')
</body>
</html>
