<?php
    if (isset($aRowSupplierData) && !empty($aRowSupplierData)) {
        foreach ($aRowSupplierData as $key => $aResSupplierData) {            
?>
<div class="form-row skill-row-section" id="<?= $aResSupplierData['id']; ?>">
    <div class="panel panel-default ">
        <div class="panel-body">
    <div class="form-group col-md-2 col-sm-12">
        <label class="form" > Skill </label>
        <select class="js-example-basic-multiple" name="a_skill[<?= $aResSupplierData['id']; ?>]" id="a_skill_<?= $aResSupplierData['id']; ?>">
            @if(!empty($aRowSkillData))
                @foreach($aRowSkillData as $aResSkillData)
                    <option value="{{$aResSkillData->id}}" <?= (isset($aResSupplierData['skill_id']) && !empty($aResSupplierData['skill_id']) && $aResSupplierData['skill_id']==$aResSkillData->id ) ? 'selected="selected"' : '' ; ?> >{{$aResSkillData->keyword}}</option>
                @endforeach
            @endif
        </select>
    </div>
    <div class="form-group col-md-3 col-sm-12">
        <label class="form" > Experience (0-100 in years) </label>
        <input type="text" name="a_experience[<?= $aResSupplierData['id']; ?>]" id="a_experience_<?= $aResSupplierData['id']; ?>" class="form-control" placeholder="Enter Experience in years" value="<?= (isset($aResSupplierData['experience']) && !empty($aResSupplierData['experience'])) ? $aResSupplierData['experience'] : '' ; ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" maxlength="3" >
    </div>
    <div class="form-group col-md-2 col-sm-12">
        <label class="form" >Hourly Rate (USD) </label>
        <input type="text" name="a_rate[<?= $aResSupplierData['id']; ?>]" id="a_rate_<?= $aResSupplierData['id']; ?>" class="form-control" placeholder="Enter Hourly Rate (USD)" value="<?= (isset($aResSupplierData['rate']) && !empty($aResSupplierData['rate'])) ? $aResSupplierData['rate'] : '' ; ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" maxlength="5">
    </div>
    <div class="form-group col-md-3 col-sm-12">
        <label class="form" > Comments </label>
        <textarea class="form-control" name="a_comment[<?= $aResSupplierData['id']; ?>]" id="a_comment_<?= $aResSupplierData['id']; ?>" placeholder="Comment" rows="1"><?= (isset($aResSupplierData['comment']) && !empty($aResSupplierData['comment'])) ? $aResSupplierData['comment'] : '' ; ?></textarea>
    </div>
    <div class="form-group col-md-1 col-sm-12">
        <label class="form" > &nbsp; </label>
        <button type="button" class="btn btn-success skill-update" attr="<?= $aResSupplierData['id']; ?>" data-rate="<?=$aResSupplierData['rate']?>" data-experience="<?=$aResSupplierData['experience']?>" >update</button>
    </div>
    <div class="form-group col-md-1 col-sm-12">
        <label class="form" > &nbsp; </label>
        <br>
        <button type="button" class="btn btn-danger skill-delete" attr="<?= $aResSupplierData['id']; ?>"><i class="fa fa-trash"></i></button>
    </div>
        </div>
    </div>
</div>
<?php } ?>
<script type="text/javascript">
    $('.skill-update').click(function(){
        var skillSectionId = $(this).attr('attr');
        var rateValue = $(this).attr('data-rate');
        var experienceValue = $(this).attr('data-experience');
        var spinner = $('#loader');
        spinner.show();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        jQuery.ajax({
            url: "{{ url('/update-supplier-skills') }}",
            method: 'get',
            //data: $('#skills-form').serialize(),
            data: {
                id: skillSectionId,
                skill_id: $("#a_skill_"+skillSectionId).val(),
                experience: $("#a_experience_"+skillSectionId).val(),
                rate: $("#a_rate_"+skillSectionId).val(),
                comment: $("#a_comment_"+skillSectionId).val(),
            },
            success: function(result){
                var res = jQuery.parseJSON(result);
                var spinner = $('#loader');
                if (res.success) {
                    spinner.hide();
                    $("#skill").select2().val("0").trigger("change");                        
                    $(".skill-input").val('')
                    $(".skill-append-section").html(res.data.sHtml)
                    $('#skill-message').html('<div class="alert alert-success">'+ res.data.message +'</div>');
                    $('.js-example-basic-multiple').select2({
                        placeholder: "Select a skill",
                    });

                    setTimeout(function(){
                        $('#skill-message').html('');
                    }, 5000);
                } else {
                    spinner.hide();
                    $('#skill-message').html('<div class="alert alert-warning">'+ res.message.message +'</div>');
                    if(res.message.message == 'Experience must be between 1 and 10'){
                        $("#a_experience_"+skillSectionId).val(experienceValue);
                    }
                    if(res.message.message == 'Hourly rate must be between 1 and 10000'){
                        $("#a_rate_"+skillSectionId).val(rateValue);
                    }
                    setTimeout(function(){
                        $('#skill-message').html('');
                    }, 5000);
                }
            }
        });            
    });

    $('.skill-delete').click(function(){
        if (confirm("Are you sure you want to Delete this skill?")) {
        var skillSectionId = $(this).attr('attr');        
         $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        jQuery.ajax({
            url: "{{ url('/delete-supplier-skills') }}",
            method: 'get',
            //data: $('#skills-form').serialize(),
            data: {
                id: skillSectionId               
            },
            success: function(result){
                let res = jQuery.parseJSON(result);
                if (res.success) {
                    $("#skill").select2().val("0").trigger("change");                        
                    $(".skill-input").val('')
                    $(".skill-append-section").html(res.data.sHtml)
                    $('#skill-message').html('<div class="alert alert-success">'+ res.data.message +'</div>');
                    $('.js-example-basic-multiple').select2({
                        placeholder: "Select a skill",
                    });       
                     //$(".pull-right").hide();
                } else {
                    $('#skill-message').html('<div class="alert alert-warning">'+ res.data.message +'</div>');
                }
            }
        });
        }
    });
</script> 
<?php     
} else {
?>
<script type="text/javascript">
    $("#btnfinish").hide();
</script>
<?php } ?>
