<div class="modal fade settingmodel" id="SettingModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
                <h4 class="modal-title" id="myModalLabel">Column Settings - click checkbox to update</h4>
            </div>
            <div class="modal-body">
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12 success-deletion" style="display: none">
                            <div class="alert alert-success">
                                Leads Listing Column setting updated...
                            </div>
                        </div>
                        <div class="col-sm-12 error-deletion" style="display: none">
                            <div class="alert alert-danger">
                                There is a problem in your request,Please try again!
                            </div>
                        </div>
                    </div>
                    <fieldset>
                        <legend><label><input type="checkbox" class="checkbox-check all-checkbox-checked"> &nbsp;&nbsp;Select
                                All</label>:
                        </legend>
                        @foreach($columnDefs as $keyCol => $def)
                            @if ($def['isDynamic'])
                            <div class="form-group checkbox">
                                <label>
                                    <input type="checkbox" class="checkbox-check form-checkbox" data-key="{{$keyCol}}"
                                           data-id="{{$keyCol}}" name="key_{{$keyCol}}" checked=checked
                                           value="1"> {{title_case(snake_case(camel_case($keyCol), ' '))}}
                                </label>
                            </div>
                            @endif
                        @endforeach
                    </fieldset>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default updateColumnReset" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success updateColumnSetting">Update</button>
            </div>
        </div>
    </div>
</div>