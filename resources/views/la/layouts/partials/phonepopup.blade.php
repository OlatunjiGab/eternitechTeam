@if (!empty($company->phone))
<style>
.fixedButton{
	position: fixed;
	bottom: -120px;
	right: -50px; 
	padding: 0px 50px 120px 0px;
}

</style>
<a class="fixedButton" href="javascript:" >
<div class="modal-content" id="phoneiframe" style="float: left; display:none;">
	<div class="modal-header closephonepopup">
		<button type="button" class="close" id="closephonepopup"><span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title" id="myModalLabel">Call</h4>
	</div>
	<iframe allow="microphone; camera" allowfullscreen="true" frameborder="0" width="350" height="500" src="{{ url(config('laraadmin.adminRoute') .'/phone/call/' . $company->phone.'/'.$project->id) }}" name="wphone" id="webphoneframe"></iframe>
</div>
</a>
@endif