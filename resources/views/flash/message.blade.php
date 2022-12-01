@if (Session::get('success') || Session::get('success_message') || Session::get('flash.success'))
    @php
    $message = Session::get('success') ?? Session::get('success_message') ?? Session::get('flash.success');
    @endphp
    <div class="alert alert-success alert-block animated fadeIn">
        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button> 
        <strong>{{ $message }}</strong>
    </div>
@endif

@if (Session::get('error') || Session::get('error_message') || Session::get('flash.error'))
    @php
    $message = Session::get('error') ?? Session::get('error_message') ?? Session::get('flash.error');
    @endphp
    <div class="alert alert-danger alert-block animated fadeIn">
        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button> 
        <strong>{{ $message }}</strong>
    </div>
@endif

@if (Session::get('warning') || Session::get('warning_message') || Session::get('flash.warning'))
    @php
    $message = Session::get('warning')??Session::get('warning_message')?? Session::get('flash.warning');
    @endphp
    <div class="alert alert-warning alert-block animated fadeIn">
        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button> 
        <strong>{{ $message }}</strong>
    </div>
@endif

@if (Session::get('info') || Session::get('info_message') || Session::get('flash.info'))
    @php
    $message = Session::get('info')??Session::get('info_message') ?? Session::get('flash.info');
    @endphp
    <div class="alert alert-info alert-block animated fadeIn">
        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button> 
        <strong>{{ $message }}</strong>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger animated fadeIn">
        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button> 
        Please correct below fields.
    </div>
@endif