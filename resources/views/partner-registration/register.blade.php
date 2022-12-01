<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ LAConfigs::getByKey('site_description') }}">
    <meta name="author" content="Dwij IT Solutions">

    <meta name="_token" content="{{csrf_token()}}">


    <link rel="icon" type="image/png" href="{{ asset('/la-assets/welcome_favicon.ico') }}"/>

    <meta property="og:title" content="{{ LAConfigs::getByKey('sitename') }}" />
    <meta property="og:type" content="website" />
    <meta property="og:description" content="{{ LAConfigs::getByKey('site_description') }}" />
    
    <meta property="og:url" content="http://laraadmin.com/" />
    <meta property="og:sitename" content="laraAdmin" />
	<meta property="og:image" content="http://demo.adminlte.acacha.org/img/LaraAdmin-600x600.jpg" />
    
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:site" content="@laraadmin" />
    <meta name="twitter:creator" content="@laraadmin" />
    
    <title>{{ LAConfigs::getByKey('sitename') }}</title>
    
    <!-- Bootstrap core CSS -->
    <link href="{{ asset('/la-assets/css/bootstrap.css') }}" rel="stylesheet">

	<link href="{{ asset('la-assets/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
    
    <!-- Custom styles for this template -->
    <link href="{{ asset('/la-assets/css/main.css') }}" rel="stylesheet">

    <link href='https://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Raleway:400,300,700' rel='stylesheet' type='text/css'>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://www.jquery-az.com/jquery/css/intlTelInput/intlTelInput.css" rel="stylesheet">

    <script src="{{ asset('/la-assets/plugins/jQuery/jQuery-2.1.4.min.js') }}"></script>
    <script src="{{ asset('/la-assets/js/smoothscroll.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

<style type="text/css">
    /* Latest compiled and minified CSS included as External Resource*/

/* Optional theme */

/*@import url('//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-theme.min.css');*/
 body {
    margin-top:30px;
    font-family: "Raleway";
    font-weight: 300;
    -webkit-font-smoothing: antialiased;
}
.stepwizard-step p {
    margin-top: 0px;
    color:#666;
}
.stepwizard-row {
    display: table-row;
}
.stepwizard {
    display: table;
    width: 100%;
    position: relative;
}
.stepwizard-step button[disabled] {
    /*opacity: 1 !important;
    filter: alpha(opacity=100) !important;*/
}
.stepwizard .btn.disabled, .stepwizard .btn[disabled], .stepwizard fieldset[disabled] .btn {
    opacity:1 !important;
    color:#bbb;
}
/*.stepwizard-row:before {
    top: 14px;
    bottom: 0;
    position: absolute;
    content:" ";
    width: 100%;
    height: 1px;
    background-color: #ccc;
    z-index: 0;
}*/
.stepwizard-step {
    display: table-cell;
    text-align: center;
    position: relative;
}
.btn-circle {
    width: 30px;
    height: 30px;
    text-align: center;
    padding: 6px 0;
    font-size: 12px;
    line-height: 1.428571429;
    border-radius: 15px;
}

.select2-container{
 width: 100%!important;
}

.stepwizard-step.arrow-background-active::before , .stepwizard-step.arrow-background-active a {
    color: #fff;
    background-color: #5cb85c;
    border-color: #4cae4c;
}
.stepwizard-step.arrow-background-active p small {
    color: #5cb85c;
    font-weight: bold;
}

.stepwizard-step:before {
    top: 14px;
    bottom: 0;
    position: absolute;
    content: " ";
    width: 100%;
    height: 2px;
    background-color: #ccc;
    z-index: 0;
    left: 0;
    z-index: -1;
}
    @media (max-width: 480px) {
        .navbar-brand {
            float: none !important;
            padding: 0 !important;
        }
        .header-logo {
            margin: auto;
            padding-top: 10px;
        }
        .iti-mobile .intl-tel-input.iti-container {
            position: fixed !important;
        }
    }
    @media (max-width: 920px) {
        .iti-mobile .intl-tel-input.iti-container {
            position: fixed !important;
        }
    }
    #loader {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        width: 100%;
        background: rgba(0,0,0,0.75) url("https://upload.wikimedia.org/wikipedia/commons/b/b9/Youtube_loading_symbol_1_(wobbly).gif") no-repeat center center;
        /*background: rgba(0,0,0,0.75) url(images/loading2.gif) no-repeat center center;*/
        z-index: 10000;
    }
    .margin-5 {
        margin-left: 5px;
    }

</style>

<script>
$(document).ready(function () {

$('#btnfinish').hide();
    var navListItems = $('div.setup-panel div a'),
        allWells = $('.setup-content'),
        allNextBtn = $('.nextBtn');
        allNextBtnUser = $('.nextBtnUser');
        allPrevBtn = $('.prevBtn');
        allPrevBtnUser = $('.prevBtnUser');

    allWells.hide();

    navListItems.click(function (e) {
        e.preventDefault();
        var $target = $($(this).attr('href')),
            $item = $(this);

        if (!$item.hasClass('disabled')) {
            navListItems.removeClass('btn-success').addClass('btn-default');
            $item.addClass('btn-success');
            
            if ($item.attr('href')=="#step-2"){                
                $item.closest('.stepwizard-step').addClass('arrow-background-active');
            } else if ($item.attr('href')=="#step-user"){
                $item.closest('.stepwizard-step').addClass('arrow-background-active');
            }else {
                $item.parents('.stepwizard-row').find('.stepwizard-step').next().removeClass('arrow-background-active');
            }
            allWells.hide();
            $target.show();
            $target.find('input:eq(0)').focus();
        }
    });

    allPrevBtn.click(function () {
        var curStep = $(this).closest(".setup-content"),
        curStepBtn = curStep.attr("id"),
        nextStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().prev().children("a"),
        curInputs = curStep.find("input[type='text'],input[type='url']"),
        isValid = true;

        $(".form-group").removeClass("has-error");
        for (var i = curInputs.length; i > 0; i--) {
            //if (!curInputs[i].validity.valid) {
            //    isValid = false;
                //$(curInputs[i]).css('border',"Red 2px solid");
                //$(curInputs[i]).closest(".form-group").addClass("has-error");
            //}
        }

        if (isValid) nextStepWizard.removeAttr('disabled').trigger('click');

    });
    allPrevBtnUser.click(function () {
        var curStep = $(this).closest(".setup-content"),
        curStepBtn = curStep.attr("id"),
        nextStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().prev().children("a"),
        curInputs = curStep.find("input[type='text'],input[type='url']"),
        isValid = true;

        $(".form-group").removeClass("has-error");
        for (var i = curInputs.length; i > 0; i--) {
            //if (!curInputs[i].validity.valid) {
            //    isValid = false;
                //$(curInputs[i]).css('border',"Red 2px solid");
                //$(curInputs[i]).closest(".form-group").addClass("has-error");
            //}
        }

        if (isValid) nextStepWizard.removeAttr('disabled').trigger('click');

    });

    allNextBtn.click(function () {
        var curStep = $(this).closest(".setup-content"),
            curStepBtn = curStep.attr("id"),
            nextStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().next().children("a"),
            curInputs = curStep.find("input[type='text'],input[type='url'],input[type='tel'],textarea,select[name='agency_country']"),
            isValid = true;

        $(".form-group").removeClass("has-error");
        for (var i = 0; i < curInputs.length; i++) {
            if (!curInputs[i].validity.valid) {
                isValid = false;
                $(curInputs[i]).closest(".form-group").addClass("has-error");
            }
        }
        if (isValid) nextStepWizard.removeAttr('disabled').trigger('click');
    });
    allNextBtnUser.click(function () {
        var curStep = $(this).closest(".setup-content"),
            curStepBtn = curStep.attr("id"),
            nextStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().next().children("a"),
            curInputs = curStep.find("input[type='text'],input[name='employee_mobile'],textarea,select[name='employee_country']"),
            isValid = true;

        $(".form-group").removeClass("has-error");
        for (var i = 0; i < curInputs.length; i++) {
            if (!curInputs[i].validity.valid) {
                isValid = false;
                $(curInputs[i]).closest(".form-group").addClass("has-error");
            }
        }
        if (isValid) nextStepWizard.removeAttr('disabled').trigger('click');
    });

    $('div.setup-panel div a.btn-success').trigger('click');
});
</script>
    @include('la.layouts.partials.header-tracking')
</head>

<body data-spy="scroll" data-offset="0" data-target="#navigation">

<!-- Fixed navbar -->
<div id="navigation" class="navbar navbar-default navbar-fixed-top" style="min-height: 65px;">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href="https://eternitech.com/"><img class="header-logo" src="{{ asset('la-assets/eterni_logo.png') }}" /></a>
        </div>
    </div>
</div>

<?php
$type = "Partner";
/*if(Session::has('companyType') && !empty(Session::get('companyType'))){
    $type = \App\Models\Company::TYPE_LIST[Session::get('companyType')];
}*/
?>
<section id="home" name="home" style="margin-bottom:50px;">
    <div class="container">
        <h3 class="text-bold text-center">Partner Registration</h3>
        <div class="stepwizard">
            <div class="stepwizard-row setup-panel">
                <div class="stepwizard-step col-xs-4 arrow-background-active">
                    <a href="#step-1" type="button" class="btn btn-success btn-circle">1</a>
                    <p><small><span class="typeLabel">{{$type}}</span> Details</small></p>
                </div>
                <div class="stepwizard-step col-xs-4 @if(isset($aRowEmployeeData->name)) arrow-background-active @endif">
                    <a href="#step-user" type="button" class="btn btn-default btn-circle">2</a>
                    <p><small>User Details</small></p>
                </div>
                <div class="stepwizard-step col-xs-4 @if(isset($aRowAgencyData->name)) arrow-background-active @endif ">
                    <a href="#step-2" type="button" class="btn btn-default btn-circle">3</a>
                    <p><small><span class="typeLabel"></span> Experience</small></p>
                </div>
               <!--  <div class="stepwizard-step col-xs-4"> 
                    <a href="#step-3" type="button" class="btn btn-default btn-circle">3</a>
                    <p><small>Your Team</small></p>
                </div>        -->        
            </div>
        </div>

        <form method="post" id="skills-form">
            <input type="hidden" name="step_3" value="1">
            <?php /* ?>
            <div class="panel panel-primary setup-content" id="step-1">
                <div class="panel-heading">
                    <h3 class="panel-title">Basic Details</h3>
                </div>
                
                <div class="panel-body">
                    <div class="form-group">
                        <label class="control-label">First Name</label>
                        <input type="text" name="partner_first_name" class="form-control" placeholder="Enter First Name" value="@if(isset($aRowPartnerData->partner_first_name)) {{$aRowPartnerData->partner_first_name}} @endif" required="required"/>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Last Name</label>
                        <input type="text" name="partner_last_name" class="form-control" placeholder="Enter Last Name" value="@if(isset($aRowPartnerData->partner_last_name)) {{$aRowPartnerData->partner_last_name}} @endif" required="required"/>
                    </div>

                    <div class="form-group">
                        <label class="control-label">Email</label>
                        <input type="email" name="partner_email" class="form-control" placeholder="Enter Email" value="@if(isset($aRowPartnerData->partner_email)) {{$aRowPartnerData->partner_email}} @endif" readonly="readonly" required="required"/>
                    </div>
                    <button class="btn btn-primary nextBtn pull-right" type="button">Next</button>
                </div>
            </div>
            <?php */ ?>
            <div class="panel panel-primary setup-content" id="step-1" >
                <div class="panel-heading">
                    <h3 class="panel-title"><span class="typeLabel">{{$type}}</span> Details</h3>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <label class="control-label">Type</label>
                        <select class="form-control" id="type" name="type" required>
                            <option value="" disabled>Select Type</option>
                            @if(!empty($typeList))
                                @foreach($typeList as $key => $value)
                                    <option value="{{$key}}">{{$value}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="control-label"><span class="typeLabel"></span> Name</label>
                        <input type="text" name="agency_name" id="agencyName" class="form-control" placeholder="Enter Name" value="@if(isset($aRowAgencyData->name)) {{$aRowAgencyData->name}} @endif" required="required" />
                    </div>
                    <div class="form-group">
                        <label class="control-label"><span class="typeLabel"></span> Phone Number</label>
                        <input type="tel" name="agency_phone" id="agencyPhone" class="form-control" placeholder="Enter Phone Number" value="@if(isset($aRowAgencyData->phone)) {{$aRowAgencyData->phone}} @endif" required="required" oninput="this.value = this.value.replace(/[^0-9+.]/g, '').replace(/(\..*)\./g, '$1');" maxlength="18" />
                    </div>
                    <br>
                    <div class="form-group">
                        <label class="control-label"><span class="typeLabel"></span> Description</label>
                        <textarea class="form-control" name="agency_description" placeholder="Enter Description" required="required">@if(isset($aRowAgencyData->agency_description)) {{$aRowAgencyData->agency_description}} @endif</textarea>
                    </div>
                    <div class="form-group">
                        <label class="control-label"><span class="typeLabel"></span> Website URL(Ex. www.example.com)</label>
                        <input pattern="^(|http(:\/\/)|https(:\/\/)|www.)([a-zA-z0-9\-_]+)(.com|.net|.gov|.org|.in|.int|.edu|.io)" type="text" name="agency_website" novalidate="novalidate" class="form-control" placeholder="Enter Website" value="@if(isset($aRowAgencyData->agency_website)) {{$aRowAgencyData->agency_website}} @endif" />
                    </div>
                    <div class="form-group">
                        <label class="control-label"><span class="typeLabel"></span> Street Address</label>
                        <textarea class="form-control" id="agencyAddress" name="agency_address" placeholder="Enter Street Address" required="required" rows="2">@if(isset($aRowAgencyData->address)) {{$aRowAgencyData->address}} @endif</textarea>
                    </div>
                    <div class="form-group">
                        <label class="control-label">City</label>
                        <input type="text" name="agency_city" id="agencyCity" class="form-control" placeholder="Enter City" value="@if(isset($aRowAgencyData->city)) {{$aRowAgencyData->city}} @endif" />
                    </div>
                    <div class="form-group">
                        <label class="control-label">State</label>
                        <input type="text" name="agency_state" id="agencyState" class="form-control" placeholder="Enter State" value="@if(isset($aRowAgencyData->state)) {{$aRowAgencyData->state}} @endif" />
                    </div>
                    <div class="form-group">
                        <label class="control-label">Zipcode</label>
                        <input oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" maxlength="10" type="text" name="agency_zipcode" id="agencyZipcode" class="form-control" placeholder="Enter Zipcode" value="@if(isset($aRowAgencyData->zipcode)) {{$aRowAgencyData->zipcode}} @endif" />
                    </div>
                    <div class="form-group">
                        <label class="control-label">Country</label>
                        <select class="form-control" id="agencyCountry" name="agency_country" required>
                            <option value="">Select Country</option>
                            @if(!empty($countriesList))
                                @foreach($countriesList as $key => $value)
                                    <option value="{{$value}}" @if(isset($aRowAgencyData->country) && $aRowAgencyData->country == $value) selected @endif>{{$value}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-2 col-md-offset-10">                        
                            
                            <button class="btn btn-primary nextBtn pull-right" type="button">Next</button>
                        </div>
                    </div>    
                    
                </div>
            </div>
            <div class="panel panel-primary setup-content" id="step-user" >
                <div class="panel-heading">
                    <h3 class="panel-title">User Details</h3>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <button class="btn btn-primary pull-right prevBtnUser" type="button">Previous</button>
                    </div>
                    <div class="form-group">
                        <div><strong>Fill in Your User details:</strong></div>
                    </div>
                    <div class="form-group">
                        <div class="checkbox icheck">
                            <label><input type="checkbox" name="same_as" id="same_as" onclick="copydetail(this);"> Same as <span class="typeLabel">{{$type}}</span> details</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Name</label>
                        <input type="text" name="employee_name" id="employeeName" class="form-control" placeholder="Enter Name" value="@if(isset($aRowEmployeeData->name)) {{$aRowEmployeeData->name}} @endif" required="required" />
                    </div>
                    <div class="form-group">
                        <label class="control-label">Gender: </label>
                        <label><input @if(isset($aRowEmployeeData->gender) && $aRowEmployeeData->gender == 'Male') checked @endif name="employee_gender" type="radio" value="Male" checked> Male</label>
                        <label><input @if(isset($aRowEmployeeData->gender) && $aRowEmployeeData->gender == 'Female') checked @endif name="employee_gender" type="radio" value="Female"> Female</label>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Mobile</label>
                        <input type="tel" name="employee_mobile" id="employeeMobile" class="form-control" placeholder="Enter Mobile Number" value="@if(isset($aRowEmployeeData->mobile)) {{$aRowEmployeeData->mobile}} @endif" required="required" oninput="this.value = this.value.replace(/[^0-9+.]/g, '').replace(/(\..*)\./g, '$1');" maxlength="18" />
                    </div>
                    <div class="form-group">
                        <label class="control-label">Alternative Mobile</label>
                        <input type="tel" name="employee_mobile2" id="employeeMobile2" class="form-control" placeholder="Enter Alternative Mobile" value="@if(isset($aRowEmployeeData->mobile2)) {{$aRowEmployeeData->mobile2}} @endif" oninput="this.value = this.value.replace(/[^0-9+.]/g, '').replace(/(\..*)\./g, '$1');" maxlength="18" />
                    </div>
                    <br>
                    <div class="form-group">
                        <label class="control-label">Street Address</label>
                        <textarea class="form-control" id="employeeAddress" name="employee_address" placeholder="Enter Street Address" required="required" rows="2">@if(isset($aRowEmployeeData->address)) {{$aRowEmployeeData->address}} @endif</textarea>
                    </div>
                    <div class="form-group">
                        <label class="control-label">City</label>
                        <input type="text" name="employee_city" id="employeeCity" class="form-control" placeholder="Enter City" value="@if(isset($aRowEmployeeData->city)) {{$aRowEmployeeData->city}} @endif" />
                    </div>
                    <div class="form-group">
                        <label class="control-label">State</label>
                        <input type="text" name="employee_state" id="employeeState" class="form-control" placeholder="Enter State" value="@if(isset($aRowEmployeeData->state)) {{$aRowEmployeeData->state}} @endif" />
                    </div>
                    <div class="form-group">
                        <label class="control-label">Zipcode</label>
                        <input oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" maxlength="10" type="text" name="employee_zipcode" id="employeeZipcode" class="form-control" placeholder="Enter Zipcode" value="@if(isset($aRowEmployeeData->zipcode)) {{$aRowEmployeeData->zipcode}} @endif" />
                    </div>
                    <div class="form-group">
                        <label class="control-label">Country</label>
                        <select class="form-control" id="employeeCountry" name="employee_country" required>
                            <option value="">Select Country</option>
                            @if(!empty($countriesList))
                                @foreach($countriesList as $key => $value)
                                    <option value="{{$value}}" @if(isset($aRowEmployeeData->country) && $aRowEmployeeData->country == $value) selected @endif>{{$value}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-2 col-md-offset-10">
                            <button class="btn btn-primary nextBtnUser pull-right" type="button">Next</button>
                        </div>
                    </div>

                </div>
            </div>
        <!--</form> 
        <form method="post" id="skills-form">-->
            <div class="panel panel-primary setup-content" id="step-2" style="margin-bottom:250px;">
                <div class="panel-heading">
                    <span class="typeLabel"></span> experience
                </div>
                <!--=================== skill panel body start =======================-->
                <div class="panel-body">
                    <div class="form-group">
                        <button class="btn btn-success pull-right margin-5" id="btnfinish" type="submit">Finish</button>
                        <button class="btn btn-primary pull-right prevBtn" type="button">Previous</button>
                    </div>
                    <br>
                    <div class="form-row" id="skill-message"></div>
                    <div>Tell us about your skills, experience and rates. Be as descriptive as you can it will allow our system and community to match and suggest leads to you</div>
                    <div class="panel panel-default ">
                        <div class="panel-body">
                            <label>Add <span class="addSkillTitle"></span>skill</label>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label class="control-label">Skill</label>
                            <select class="js-example-basic-multiple" id="skill" onchange="check_value_skills()" name="skill[]" multiple="multiple">
                                @if(!empty($aRowSkillData))
                                    @foreach($aRowSkillData as $aResSkillData)
                                        <option value="{{$aResSkillData->id}}">{{$aResSkillData->keyword}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label">Experience (0-100 in years) </label>
                            <input type="text" name="experience"  id="experience" onblur="check_value_experience()" class="form-control skill-input" placeholder="Enter Experience in years" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" maxlength="3">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label">Hourly Rate (USD) </label>
                            <input type="text" name="rate" id="rate" class="form-control skill-input" onblur="check_value_rate()" placeholder="Enter Hourly Rate (USD)"  oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" maxlength="5">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-9">
                            <label class="control-label">Comments</label>
                            <textarea class="form-control skill-input" name="comment" placeholder="Describe your experience"></textarea>
                        </div>
                        <div class="form-group col-md-3">
                            <button type="button" class="btn btn-success" id="skillSave">Save skill</button>                            
                        </div>
                    </div>
                        </div>
                    </div>
                    <div class="skill-append-section" style="padding: 5px;"></div>
                     
                </div>
                <!--=================== skill panel body end =======================-->
            </div>
        </form>

    </div>    
</section>

<div id="c">
    <div class="container">
        <p>
            <strong>Copyright &copy; {{date('Y')}}. Powered by <a href="https://eternitech.com"><b>Eternitech.com</b></a>
        </p>
    </div>
</div>

<div id="loader"></div>
<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="{{ asset('/la-assets/js/bootstrap.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('la-assets/js/intlTelInput.js') }}"></script>
<script>
    let agency_phone = document.getElementsByName('agency_phone');
    $(agency_phone).intlTelInput({
        nationalMode: false
    });
    let employee_mobile = document.getElementsByName('employee_mobile');
    $(employee_mobile).intlTelInput({
        nationalMode: false
    });
    let employee_mobile2 = document.getElementsByName('employee_mobile2');
    $(employee_mobile2).intlTelInput({
        nationalMode: false
    });
</script>
<script>
    $('.carousel').carousel({
        interval: 3500
    })
    $(document).ready(function() {
        $('#skill').select2({
            placeholder: "Select a skill",
        });       

        $("#type").on("change", function () {
            var selected = $("#type option:selected").text();
            //$(".typeLabel").text(selected);
            //$("#agencyName").attr('placeholder','Enter '+selected+' Name');
            //$("#agencyAddress").attr('placeholder','Enter '+selected+' Address');
        });

        jQuery('#skillSave').click(function(e){
           
            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            jQuery.ajax({
                url: "{{ url('/save-skills') }}",
                method: 'post',
                data: $('#skills-form').serialize(),
                /*data: {
                    name: jQuery('#name').val(),
                    type: jQuery('#email').val(),
                    price: jQuery('#address').val()
                },*/
                success: function(result){
                    
                    let res = jQuery.parseJSON(result);
                    if (res.success) {
                        $("#skill").select2().val("0").trigger("change");                        
                        $(".skill-input").val('')
                        $(".addSkillTitle").html('another ');
                        $(".skill-append-section").html(res.data.sHtml)
                        $('#skill-message').html('<div class="alert alert-success">'+ res.data.message +'</div>');
                        $('.js-example-basic-multiple').select2({
                            placeholder: "Select a skill",
                        });       
                        $('#btnfinish').show();
                        //$('#btnfinish').removeAttr('id');
                        $('.pull-right').show();
                        $("#skill option[value='"+res.data.CurrentSkillId+"']").remove();
                        setTimeout(function(){
                            $('#skill-message').html('');
                        }, 5000);
                    } else {
                        if(res.message.message == 'Experience must be between 1 and 100'){
                            $("#experience").val('');
                        }
                        if(res.message.message == 'Hourly rate must be between 1 and 99999'){
                            $("#rate").val('');
                        }
                        $('#skill-message').html('<div class="alert alert-warning">'+ res.message.message +'</div>');
                        setTimeout(function(){
                            $('#skill-message').html('');
                        }, 5000);
                    }
                }
            });
        });   
    });
    function check_value_skills() {
        var skill = $("#skill option:selected").val();
        if (skill == "") {
            $('#btnfinish').hide();
        }
    }

    function check_value_experience() {
        var experience = $('#experience').val();
        if (experience == "") {
            $('#btnfinish').hide();
        }
    }

    function check_value_rate() {
        var rate = $('#rate').val();
        if (rate == "") {
            $('#btnfinish').hide();
        }
    }
    function copydetail(e) {
        if (document.getElementById(e.id).checked) {
            document.getElementById('employeeName').value = document.getElementById('agencyName').value;
            document.getElementById('employeeMobile').value = document.getElementById('agencyPhone').value;
            document.getElementById('employeeAddress').value = document.getElementById('agencyAddress').value;
            document.getElementById('employeeCity').value = document.getElementById('agencyCity').value;
            document.getElementById('employeeState').value = document.getElementById('agencyState').value;
            document.getElementById('employeeZipcode').value = document.getElementById('agencyZipcode').value;
            document.getElementById('employeeCountry').value = document.getElementById('agencyCountry').value;

            var mobile = document.getElementById('employeeMobile');
            mobile.dispatchEvent(new Event("keyup"));
        }
    }
</script>
</body>
</html>