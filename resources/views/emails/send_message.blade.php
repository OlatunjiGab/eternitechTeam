@if(isset($msg) && !empty($msg))
		@php
			$msg = str_replace("\n", '<br/>', $msg);
		@endphp
		{!! $msg !!}
@endif

