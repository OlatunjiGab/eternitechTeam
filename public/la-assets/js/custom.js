function bidPlacedShowModal(productId) {			
	$.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }        
    });

    $.ajax({
        type:'POST',        
        url: projectUrl+"/admin/show-bid-message",
        data:{productId:productId},
        success:function(response) {
          	if (response.success){
	            $('#bid-modal-btn').trigger('click');
	            $('.bid-content-modal').html(response.data.sHtml);
          	}
        }
    });
}

$(function(){
  /*-----wysihtml5 editor------*/
  $(".editor").wysihtml5();
})