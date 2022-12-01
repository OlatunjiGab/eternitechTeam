// let getDeviceName = function() {
//     let device = "Unknown";
//     const ua = {
//         "Generic Linux": /Linux/i,
//         "Android": /Android/i,
//         "BlackBerry": /BlackBerry/i,
//         "Bluebird": /EF500/i,
//         "Chrome OS": /CrOS/i,
//         "Datalogic": /DL-AXIS/i,
//         "Honeywell": /CT50/i,
//         "iPad": /iPad/i,
//         "iPhone": /iPhone/i,
//         "iPod": /iPod/i,
//         "macOS": /Macintosh/i,
//         "Windows": /IEMobile|Windows/i,
//         "Zebra": /TC70|TC55/i,
//     }
//     Object.keys(ua).map(v => navigator.userAgent.match(ua[v]) && (device = v));
//     return device.toLowerCase().replace(' ','');
// }
let saveStorage = function(key,obj) {
    localStorage.clear();
    localStorage.setItem(key,JSON.stringify(obj));
}
let getStorage = function(key) {
    let storageData = {};
    if(localStorage.getItem(key)){
        storageData = JSON.parse(localStorage.getItem(key));
    }
    return storageData;
}
let checkStorage = function(key) {
    if(localStorage.getItem(key) != 'undefined' && localStorage.getItem(key) != null){
        return true;
    }
    return false;
}
let storageKeyName = function(){
    if (module === '') {
        alert('module not found');
    } else {
        return module + "_columns_settings";
    }
}

$(function () {

    let showHideFilterFields = function (aSettings,checked_arr = [],unchecked_arr = []) {
        $('.filterModel').find('.box-body .form-group').each(function () {
            if (aSettings[$(this).attr('data-filter')] != undefined && aSettings[$(this).attr('data-filter')] == 0) {
                $(this).find('.filter_data').val('');
                // $(this).addClass('hide');
            } else {
                // $(this).removeClass('hide');
            }
        })
        if(checked_arr.length > 0 || unchecked_arr.length > 0){
            // Hide the checked columns
            if(checked_arr.length > 0){
                table.columns(checked_arr).visible(true);
                /*for (var i = 0; i < checked_arr.length; i++) {
                    let column = table.column(checked_arr[i]);
                    if(!column.visible()){
                        column.visible(!column.visible())
                    }
                }*/
            }
            // Show the unchecked columns
            if(unchecked_arr.length > 0){
                table.columns(unchecked_arr).visible(false);
                /*for (var i = 0; i < unchecked_arr.length; i++) {
                    let column = table.column(checked_arr[i]);
                    if(column.visible()){
                        column.visible(!column.visible())
                    }
                }*/
            }
            table.columns.adjust().draw();
        }
    }
    let showHideLoader= function (show = true){
        $('#table_processing').attr('style','display:none;');
        if(show){
            $('#table_processing').removeAttr('style');
        }
    }
    let showHideMsg = function (success = true){
        if(success){
            $('.success-deletion').show(2000);
            setTimeout(function (){
                $('.success-deletion').hide(1000);
            },5000);
        }else{
            $('.error-deletion').show(2000);
            setTimeout(function (){
                $('.error-deletion').hide(1000);
            },4000);
        }
    }
    $('.columnSettingModal').click(function(){
        let target = $(this).attr('data-target');
        $(target).find('.form-checkbox').each(function () {
            if (columnSettings[$(this).attr('data-key')] == 1) {
                $(this).prop("checked",true);
            } else {
                $(this).prop("checked",false);
            }
        })
    })
    $('#cardView').click(function () {
        if ($("#table").hasClass('cards')) {
            $('#cardView').attr('alt', 'Card View').attr('title', 'Change to Card View').html('<i class="fa fa-th width-15" aria-hidden="true"></i>')
        } else {
            $('#cardView').attr('alt', 'Table View').attr('title', 'Change to Table View').html('<i class="fa fa-table" aria-hidden="true"></i>')
        }
        $("#table").toggleClass('cards')
        $("#table thead tr.select_all").find('td:first').toggleClass('show');
        $("#table thead tr").not(':first').toggleClass('hide')
    })

    $('input.daterange').daterangepicker({
        opens: 'left',
        drops: 'up',
        autoApply: true,
        locale: {
            cancelLabel: 'Clear'
        }
    }, function (start, end, label) {
        daterangeVal = start.format('YYYY-MM-DD') + 'to' + end.format('YYYY-MM-DD');
    });
    $('input.daterange').val('');

    $(document).on('click', '.deleteRow', function () {
        if (!$(this).is(':checked')) {
            $("#bulkDelete").attr("checked", false);
        }
        if ($(this).is(':checked')) {
            $('#deleteTriger').show();
        }
    });
    $(document).on('click', '.listing-disable-feature-popup', function (e) {
        e.preventDefault();
        $('#disable-feature-popup').modal('show');
    });

    $('.filterForm,.filterFormReset').click(function () {
        let index = 0;
        let type = $(this).attr('data-type');
        $('.filter_data').each(function (item) {
            let val = '';
            if (type == 'reset') {
                $(this).val(val);
            } else {
                switch ($(this).attr('name')) {
                    case 'created_at':
                        val = $(this).val() == '' ? '' : daterangeVal;
                        break;
                    case 'skills':
                        val = ($(this).val() == null) ? '' : $(this).val();
                        break;
                    default:
                        val = $(this).val();
                }
            }
            table.columns(index).search(val);
            index += 1;
        });
        table.draw();
    })

    $("#bulkDelete").on('click',function() { // bulk checked
        if ($(this).is(':checked')) {
            $('#deleteTriger').removeClass('hide')
        } else {
            $('#deleteTriger').addClass('hide')
        }
        var status = this.checked;
        $(".deleteRow").each( function() {
            $(this).prop("checked",status);
        });
    });

    //update Column setting function :first one for reset the setting
    $('.all-checkbox-checked').on('change',function(){
        if($(this).is(':checked')){
            $(this).parents('.modal.settingmodel').find('.form-checkbox').prop('checked',true);
        }else{
            $(this).parents('.modal.settingmodel').find('.form-checkbox').prop('checked',false);
        }
    });
    $('.modal.settingmodel').find('.form-checkbox').on('change',function(){
        if($(this).parents('.modal.settingmodel').find('.form-checkbox').length == $(this).parents('.modal.settingmodel').find('.form-checkbox:checked').length){
            $(this).parents('.modal.settingmodel').find('.all-checkbox-checked').prop('checked',true);
        }else{
            $(this).parents('.modal.settingmodel').find('.all-checkbox-checked').prop('checked',false);
        }
    });
    $('.updateColumnReset').on('click',function(){
        $(this).parents('.modal.settingmodel').find('.form-checkbox').each(function(){
            if(columnSettings[$(this).attr('data-key')] == 1){
                $(this).prop('checked',true);
            }else{
                $(this).prop('checked',false);
            }
        });
        if($(this).parents('.modal.settingmodel').find('.form-checkbox').length == $(this).parents('.modal.settingmodel').find('.form-checkbox:checked').length){
            $(this).parents('.modal.settingmodel').find('.all-checkbox-checked').prop('checked',true);
        }else{
            $(this).parents('.modal.settingmodel').find('.all-checkbox-checked').prop('checked',false);
        }
    });

    //Update setting
    $(".updateColumnSetting").on('click',function() {
        showHideLoader();
        //var _token = $('meta[name="csrf-token"]').attr('content');
        let aData = {};
        let checked_arr = [];
        let unchecked_arr = [];
        $(this).parents('.modal.settingmodel').find('.form-checkbox').each(function(){
            aData[$(this).attr('data-key')] = $(this).is(':checked') ? 1 : 0;
            if($(this).is(':checked')){
                let index = getColumnIndex($(this).attr('data-key'));
                if(index != ''){
                    checked_arr.push(index);
                }
            }else{
                let index = getColumnIndex($(this).attr('data-key'));
                if(index != ''){
                    unchecked_arr.push(index);
                }
            }
        });
        if(Object.keys(aData).length > 0){
            saveStorage(storageKeyName(),aData);
            columnSettings = aData;
            showHideFilterFields(aData,checked_arr,unchecked_arr);
            showHideMsg(true);
        }else{
            showHideLoader(false);
        }
        /*$.ajax({
            type: "post",
            url: "projects/column-setting-update",
            data: {_token:_token,aData:aData},
            success: function(result) {
                let success = true;
                showHideLoader(false);
                if(result.status){
                    columnSettings = aData;
                    showHideFilterFields(aData,checked_arr,unchecked_arr);
                }else{
                    success = false;
                }
                showHideMsg(success)
            },
            error: function(){
                showHideMsg(false);
                showHideLoader(false);
            }
        });*/
    });

    $('#deleteTriger').on("click", function(event){ // triggering delete one by one
        $('.warning-error-deletion').addClass('hidden');
        if( $('.deleteRow:checked').length > 0 ){  // at-least one checkbox checked
            if(confirm("Are you sure you want to delete selected records?")){
                var ids = [];
                $('.deleteRow').each(function(){
                    if($(this).is(':checked')) {
                        ids.push($(this).val());
                    }
                });
                var _token = $('meta[name="csrf-token"]').attr('content');
                var ids_string = ids.toString();  // array to string conversion
                $.ajax({
                    type: "post",
                    url: "multi_projects_delete",
                    data: {data_ids:ids_string,_token:_token},
                    success: function(result) {
                        //table.draw(); // redrawing datatable
                        var arrProjectIds = ids_string.split(",");
                        $.each(arrProjectIds, function( index, value ) {
                            $('table#table tbody tr td').find('input.deleteRow#'+value ).closest('tr.danger').css('display',"none");
                        });
                        $("#bulkDelete").prop("checked", false);
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    },
                    async:false
                });
            }
        } else {
            $('.warning-error-deletion').removeClass('hidden');
        }
    });

    /// actions & values

    let daterangeVal = '';
    let screenWidth = (window.innerWidth > 0) ? window.innerWidth : screen.width;

    showHideFilterFields(columnSettings);

    if(screenWidth <= parseInt('767')){
        $('#cardView').trigger('click')
        $.fn.DataTable.ext.pager.numbers_length = 5;
    }

    let table = $("#table").DataTable({
        "fnDrawCallback": function( oSettings ) {
            $("#bulkDelete").prop("checked", false);
        },
        fixedHeader: false,
        processing: true,
        serverSide: true,
        ordering: false,
        pageLength: 25,
        ajax: dtAjaxUrl,
        stateSave: true,
        stateDuration: 60,
        language: {
            paginate: {
                next: '&#8594;', // or '→'
                previous: '&#8592;' // or '←'
            },
            lengthMenu: "_MENU_",
            search: "_INPUT_",
            searchPlaceholder: "Search"
        },
        createdRow: function ( row, data, index ) {
            /*if ( data[10] == "" ) {
                $(row).addClass('danger');
            } */
        },
        columnDefs: columnDefs,
        fixedColumns:   {
            leftColumns: 0,
            rightColumns: 1,
        }
    });

});