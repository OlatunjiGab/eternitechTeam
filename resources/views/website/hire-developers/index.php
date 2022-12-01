<?php
include '../wp-load.php';
include 'crm-sdk.php';


get_header();

define( 'hiredeve', '999' );
define( 'API_URL', 'https://crm.eternitech.com/' );

$url = $_SERVER['REQUEST_URI'];
$url = explode("/", $url);
$hireId = $url[count($url) - 1];
$hirelink = explode("-", $hireId);
if($hirelink[1] == "developer")
{
    $hiredevelope = explode("?", $hirelink[3]);
    $_REQUEST['country'] = $hiredevelope[0];
    $hirewith = explode("=", $hiredevelope[1]);
    $_REQUEST['keyword'] = $hirewith[1];
}
else
{
    $_REQUEST['skill'] = $hirelink[1];
    $hiredevelope = explode("?", $hirelink[4]);
    $_REQUEST['country'] = $hiredevelope[0];
    $hirewith = explode("=", $hiredevelope[1]);
    $_REQUEST['keyword'] = $hirewith[1];
}

$params = [];
if(isset($_REQUEST['skill']) && !empty($_REQUEST['skill'])){
    $params['skill'] = urldecode($_REQUEST['skill']);
}
if(isset($_REQUEST['country']) && !empty($_REQUEST['country'])){
    $params['country'] = $_REQUEST['country'];
}
if(isset($_REQUEST['keyword']) && !empty($_REQUEST['keyword'])){
    $params['keyword'] = $_REQUEST['keyword'];
}
   if(isset($_REQUEST['page']) && !empty($_REQUEST['page'])){
    $params['page'] = $_REQUEST['page'];
   }

$queryString = (!empty($params)?'?'.http_build_query($params):'');
$filters = CallAPI('GET', API_URL.'talents'.$queryString);
$filters = json_decode($filters, true);

?>
    <!--<link rel="stylesheet" href="/hire-developers/style2.css">-->

    <link rel="stylesheet" href="/hire-developers/style.css">
    <link rel='stylesheet' id='contact-form-7-css'  href='https://eternitech.com/wp-content/plugins/contact-form-7/includes/css/styles.css?ver=5.3.2' type='text/css' media='all' />
    <link rel='stylesheet' id='avada-stylesheet-css'  href='https://eternitech.com/wp-content/themes/Avada/assets/css/style.min.css?ver=5.0.6' type='text/css' media='all' />
    <link rel='stylesheet' id='child-style-css'  href='https://eternitech.com/wp-content/themes/Avada-Child-Theme/style.css?ver=392cc0645c220329076dde9aad7ce6fc' type='text/css' media='all' />
    <link rel='stylesheet' id='avada-dynamic-css-css'  href='https://eternitech.com/wp-content/uploads/avada-styles/avada-6669.css?timestamp=1615541115&ver=5.0.6' type='text/css' media='all' />
    <link rel='https://api.w.org/' href='https://eternitech.com/wp-json/' />
    <link rel='stylesheet' id='avada-dynamic-css-css'  href='//eternitech.com/wp-content/uploads/avada-styles/avada-2123.css?timestamp=1616854644&#038;ver=5.0.6' type='text/css' media='all' />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <div id="sliders-container-new">
        <div class="fusion-slider-container fusion-slider-6669  full-width-slider-container">
            <style type="text/css">
                main#main .fusion-row {
                    max-width: 100%;
                }
                main#main {
                    padding: 0 !important;
                }
                div#main1 {
                    max-width: 1100px;
                    margin: auto;
                }
                .fusion-slider-6669 .flex-direction-nav a {
                    width:63px;height:63px;line-height:63px;margin-top:-31.5px;font-size:25px;
                }
                div#main {
                    padding: 0 !important;
                }
                @media screen and (max-width: 768px) {
                    .col-md-3.flag-top{
                        max-width: 34% !important;
                        width: 100% !important;
                        padding: 0 15px !important;
                        right: 0 !important;
                    }
                    .heading .title-heading-left{color:#000 !important;font-size:28.5px !important;line-height:34.2px !important;}
                }
                .error-filter{position: absolute;bottom: -50px;color: red;font-size: 14px;right: 0;}
                form#filter {
                    padding-bottom: 30px;
                }
                .submit_filter input.form_submit {
                    background-color: #0068b4 !important;
                    color: white !important;
                    padding: 12px 20px 11px;
                    position: relative;
                    top: 5px;
                    cursor: pointer;
                }

                .submit_filter input.form_submit:hover {
                    color: #000;
                }

                .new-bottom-box {
                    padding-top: 20px;
                }

                .title-text {
                    display: flex;
                }

                .title-text > div {
                    margin: 20px 20px 0;
                    width: 25%;
                }

                .title-text > div:last-child {
                    margin-right: 0;
                }

                .title-text > div a {
                    color: #868686;
                    display: block;
                    padding-bottom: 10px;
                }

                .title-text > div a:hover {
                    color: #000;
                }

                .new-bottom-box h1 {
                    margin-bottom: 30px;
                    color: #000;
                }
                .techskill_hiredev label, .country_hiredev label, .search_area label {
                    font-size: 16px !important;
                    line-height: 26px !important;
                }
                .techskill_hiredev, .search_area, .country_hiredev {
                    padding-right: 4px !important;
                    padding-left: 4px !important;
                }
                p.text_between {
                    border-left: 0px solid #cecece !important;
                    margin-left: 8px !important;
                }
                .box_design .card .card-body p {
                    padding: 0 !important;
                }
                .box_design {
                    padding-right: 0px !important;
                }
                .col-md-12.skills {
                    padding-left: 0px !important;
                }
                .tfs-slider .slide-content-container .fusion-title-size-three h3 {
                    margin-top: 6px !important;
                    font-size: 20px !important;
                }
                .flexslider {
                    background-color: #e89024 !important;
                }
                @media only screen and (max-width: 767px){
                    .fusion-slider-container .background.background-image {
                        background-size: cover;
                        background-repeat: repeat-x;
                    }
                }
                .similar-skills a {
                    margin-left: 15px;
                    margin-right: 15px;
                }
                h1.similar-title {
                    margin-left: 15px;
                }
                #calc_shipping_state_field .select2-selection__placeholder, #comment-input .placeholder, #comment-textarea .placeholder, .fusion-search-element .searchform.fusion-search-form-clean .fusion-search-form-content .fusion-search-button input[type=submit], .fusion-search-form-clean .searchform:not(.fusion-search-form-classic) .fusion-search-form-content .fusion-search-button input[type=submit], .select2-results__option, input.s .placeholder {
                    color: #000 !important;
                }
                .select2-container--default .select2-selection--single .select2-selection__rendered {
                    color: #000 !important;
                }
                input[type="text"], select, textarea {
                    color: #000 !important;
                }

            </style>

            <div class="tfs-slider flexslider main-flex full-width-slider">
                <ul class="slides">
                    <li style="display: block; z-index: 2;" class="flex-active-slide">
                        <div class="slide-content-container slide-content-left">
                            <div class="slide-content" style="opacity: 1; margin-top: 10%;">
                                <div class="heading ">
                                    <div class="fusion-title-sc-wrapper" style="">
                                        <div class="fusion-title title fusion-sep-none fusion-title-size-two fusion-border-below-title">
                                            <h2 class="title-heading-left" style="color:#000;font-size:60px;line-height:52px;" data-inline-fontsize="true" data-inline-lineheight="true" data-fontsize="60" data-lineheight="72"><span>Hire</span> Best Experienced <span>Developer</span>
                                            </h2>
                                        </div>
                                    </div>
                                </div>
                                <div class="caption with-bg">
                                    <div class="fusion-title-sc-wrapper">
                                        <div class="fusion-title title fusion-sep-none fusion-title-size-three fusion-border-below-title corner_button">
                                        <!--<h3 class="title-heading-left">Hire available developers now<br>or contact us for custom recruitment</h3>-->
                                            <a href="https://eternitech.com/apply-as-a-developer/" class="become_ninja">Become a Ninja</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="background background-image" style="background-image: url('https://eternitech.com/wp-content/uploads/2022/01/image-7.png');max-width:100%;min-height:400px;filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='https://res.cloudinary.com/eternitech/images/f_auto,q_auto/v1615626299/eternitech/bnr1/bnr1.jpg', sizingMethod='scale');-ms-filter:'progid:DXImageTransform.Microsoft.AlphaImageLoader(src='https://res.cloudinary.com/eternitech/images/f_auto,q_auto/v1615626299/eternitech/bnr1/bnr1.jpg', sizingMethod='scale')';" data-imgwidth="1366">
                        </div>
                    </li>
                </ul>
                <ul class="flex-direction-nav">
                    <li><a class="flex-prev flex-disabled" href="#" tabindex="-1"></a></li>
                    <li><a class="flex-next flex-disabled" href="#" tabindex="-1"></a></li>
                </ul>
            </div>
        </div>
    </div>
<div class="banner_bottom">
    <div class="container">
        <div class="row banner_bottom_counter">
            <div class="col-md-4">
                <div class="align_center features_text"><div class="dev_icon_class"><i class="far fa-clock"></i></div><div class="count"> 8 </div><h3> hour free trial</h3></div>
            </div>

            <div class="col-md-4 mid">
                <div class="align_center features_text"><div class="dev_icon_class"><i class="far fa-user"></i></div><div class="count"> 0-1 </div> <h3> day candidate selection </h3></div>
            </div>

            <div class="col-md-4">
                <div class="align_center features_text"><div class="dev_icon_class"><i class="fas fa-users"></i></div><div class="count"> 9000+  </div> <h3> vetted software developers</h3></div>
            </div>

            <hr>
        </div>
        <div class="row">
            <div class="col-xs-12">
              <h1 class="heading_title" style="margin-bottom: 20px;">Hire Top Full Time Experienced Developer</h1>
           </div>
        </div>
    </div>
</div>
    <div id="main1" class="clearfix hire-devps" style="">
        <div class="fusion-row-hire" style="">
            <section style="padding-top: 50px;">
                <div class="container">

                    <div class="fusion-hire-row fusion-row-hire">
                        <div class="col-xs-12">
                            <h3 class="tex-center" style="margin-bottom: 20px;">FIND YOUR DEVELOPER</h3>
                        </div>
                    </div>
                    <div class="fusion-hire-row fusion-row-hire">
                        <div class="col-md-12">
                            <form class="row" method="GET" id="filter" onsubmit="top.location='hire'+(this.elements.skill.value == '' ? '' : '-'+this.elements.skill.value)+(this.elements.country.value == '' ? '-developer' : '-developer-in-'+this.elements.country.value)+(this.elements.keyword.value == '' ? '' : '?with='+this.elements.keyword.value);return false;">   <div class="col-md-3 techskill_hiredev">
                                    <label>Experienced In:</label>
                                    <select class="form-control js-example-basic-single filter-field" name="skill" value-group="technicalskill">
                                        <option data-filter-value="*" value="">Any Technology</option>
                                        <?php
                                        foreach($filters['skills'] as $skill) {
                                 echo '<option data-filter-value="' . urlencode($skill['keyword']) . '" value="' . urlencode($skill['keyword']) . '" '.(isset($params['skill']) && strtolower($params['skill'])==strtolower($skill['keyword'])?'selected="selected"':'').'>' . $skill['keyword'] . '</option>';
                                        } ?>
                                    </select>
                                </div>
                                <div class="col-md-3 country_hiredev">
                                    <label>From:</label>
                                    <select class="form-control js-example-basic-single filter-field" name="country" value-group="">
                                        <option data-filter-value="*" value="">Any Country</option>
                                        <?php
                                        foreach($filters['countries'] as $country) {
                                            echo '<option data-filter-value="' . $country['iso'] . '" value="' . $country['name'] . '" '.(isset($params['country']) && strtolower($params['country'])==strtolower($country['name'])?'selected="selected"':'').'>' . $country['name'] . '</option>';
                                        } ?>
                                    </select>
                                </div>
                                <div class="col-md-3 search_area">
                                    <div class="freetext_hiredev">
                                        <label>With:</label>
                                        <input type="text" class="quicksearch filter-field" id="quicksearch" name="keyword" placeholder="Free Text" value="<?php echo isset($params['keyword']) && !empty($params['keyword'])?$params['keyword']:'';?>">
                                    </div>
                                </div>
                                <div class="col-md-3 submit_filter">
                                    <!--<a href="javascript:void(0);" class="form_submit">Find</a>-->
                                    <input type="submit" name="" class="form_submit" value="Find">
                                </div>
                            </form>
                            <div id="response"></div>
                        </div>
                    </div>
                </div>
            </section>
            <!----Post------------->
            <section style="padding-top: 10px;">
                <div class="container1">
                    <div class="fusion-hire-row fusion-row-hire">
                        <div class="col-12 col-sm-12 developer">

                            <?php if(isset($filters['experts']) && !empty($filters['experts']['data'])){ ?>
                                <div class="fusion-hire-row fusion-row-hire box_rows grid_new">
                                    <?php
                                    foreach($filters['experts']['data'] as $expert){
                                        $experience  = date_diff( date_create($expert['experience_start']), date_create() );
                                        $expertSkills = $expert['skills'];
                                        ?>
                                        <div class=" col-sm-6 box_design element-item1 India vuejs electronjs docker">
                                            <div class="card" onclick="window.location.href = '/hire-developers/single-html-page.php<?php echo "?id=".$expert['id'];?>';">
                                                <div class="card-body">

                                                    <h3 class="card-title text-center">
                              <span class="title_img"><img width="400" height="400" src="https://res.cloudinary.com/eternitech/images/v1619698751/eternitech/yii-img/yii-img.png" class="attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="" /> </span>
                                                        <a class="link2" href="/hire-developers/single-html-page.php<?php echo "?id=".$expert['id'];?>"><?php echo $expert['first_name'].' '.$expert['last_name'];?> </a>
                                                    </h3>
                                                    <div class="fusion-hire-row fusion-row-hire">
                                                        <div class="col-md-9">
                              <h5 class="mb-4 text-center"><span><?php echo $expert['headline'];?> </span><?php echo $experience->y.($experience->m>0?'.'.$experience->m:'');?> YEARS OF EXPERIENCE</h5>
                                                        </div>
                                                        <div class="col-md-3 flag-top">
                                                            <a class="link3" href="/hire-developers/single-html-page.php">
                                                                <img src="<?php echo API_URL.$expert['country_data']['flag'];?>" alt="" />
                                                            </a>
                                                            <span> <?php echo $expert['country_data']['name'];?></span>
                                                        </div>
                                                    </div>
                                                    <p class="text_between addReadMore showlesscontent"><?php echo substr($expert['description'],0,120).(strlen($expert['description'])>120?'...':'').(strlen($expert['description'])<80?'<br><br>':'');?></p>
                                                    <div class="fusion-hire-row fusion-row-hire image_desing">

                                                        <div class="col-sm-5 spcl_tech">
                                                            <p class="splezition1">SKILLS</p>
                                                            <p class="splezition"><strong> </strong></p>
                                                            <div class="col-md-12 skills">
                                                                <?php if(!empty($expertSkills)):

                                                                    for($i = 0; $i <= 2; $i++) {
                                                                        if(!empty($expertSkills[$i])) { ?>
                                                                            <div class="col-md-6">
                                                                                <a href="/hire-developers/hire-<?= htmlspecialchars($expertSkills[$i]['keyword']);?>-developer" class="multi_skills"><?= $expertSkills[$i]['keyword']; ?></a>
                                                                            </div>
                                                                        <?php } } ?>
                                                                    <?php if(count($expertSkills) > 4) {?>
                                                                    <div class="col-md-6">
                                                                        <a href="/hire-developers/single-html-page.php<?php echo "?id=".$expert['id'];?>" class="multi_skills">...</a>
                                                                    </div>
                                                                <?php } endif;?>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4 right_intme">
                              <a href="/hire-developers/single-html-page.php<?php echo "?id=".$expert['id'];?>">Know More</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php }?>
                                    <div class="pagination">
                                        <?php if($filters['experts']['current_page'] != 1){ ?>
                                        <a class="pagination-prev" rel="prev" href="/hire-developers/?page=<?= $filters['experts']['current_page']-1 ?>">
                                            <span class="page-prev"></span>
                                            <span class="page-text">Previous</span>
                                        </a>
                                        <?php } ?>
                                        <?php
                                        $pageNumber = 1;
                                        while($pageNumber <= $filters['experts']['last_page']) {
                                            if($pageNumber == $filters['experts']['current_page']){
                                            echo "<span class='current'>$pageNumber</span>";
                                            } else {
                                            echo "<a href='/hire-developers/?page=$pageNumber' class='inactive'>$pageNumber</a>";
                                            }
                                            $pageNumber+=1;
                                         } ?>
                                        <?php if($filters['experts']['last_page'] != $filters['experts']['current_page'] && $filters['experts']['last_page'] != 1) { ?>
                                        <a class="pagination-next" rel="next" href="/hire-developers/?page=<?= $filters['experts']['current_page']+1 ?>">
                                            <span class="page-text">Next</span>
                                            <span class="page-next"></span>
                                        </a>
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php }else{?>
                                <div class="no-results hidden_noresult">
                                    <h4>Sorry, no result found!</h4>
                                    <p>Don't worry, we have developers available to work for you remotely &#128187;<br>If you still prefer a local developer , we have a local representative in your area and can get your match!<br><br><em>Please fill in the form</em> below and our representative should get back to you shortly &#128515;</p>
                                    <!--<p>While we have multiple remove options – if you still prefer the local developer – no worries!!! We have a local representative in <?= $params['country']?:'your'; ?> area and can probably get you exactly what you need! Please fill in the form below describing about you and your need and we will do our best to find you the right match!</p>-->
                                    <div class="no-result-form local_form_deve">
                                        <h1 style="text-align: center; color: rgb(52, 73, 94); font-size: 42px; margin: 20px 0px; line-height: 42px;" data-inline-fontsize="true" data-inline-lineheight="true" data-fontsize="42" data-lineheight="42">Hire A Developer</h1>
                                        <?php echo do_shortcode( '[contact-form-7 id="7129" title="Local developer"]' ); ?>
                                    </div>
                                </div>
                            <?php }?>
                        </div>
                    </div>
                </div>
            </section>
            <!--<script src="https://unpkg.com/isotope-layout@3/dist/isotope.pkgd.min.js"></script>
               <script src="https://npmcdn.com/isotope-layout@3/dist/isotope.pkgd.js"></script>-->
            <script>
                jQuery(document).ready(function() {

                    jQuery('.js-example-basic-single').select2();


                });

                /*var qsRegex;
                var filters = {};
                var selectFilter;
                var $grid = jQuery('.grid_new').isotope({
                  itemSelector: '.element-item1',
                  layoutMode: 'fitRows',
                  filter: function() {
                    var $this = jQuery(this);
                    var searchResult = qsRegex ? $this.text().match( qsRegex ) : true;
                    var selectResult = selectFilter ? $this.is( selectFilter ) : true;
                    return searchResult && selectResult;
                  }
                });

                var $quicksearch = jQuery('.quicksearch').keyup( debounce( function() {
                  qsRegex = new RegExp( $quicksearch.val(), 'gi' );
                  $grid.isotope();
                   var visibleItemsCountss = $grid.data('isotope').filteredItems.length;
                     console.log( visibleItemsCountss );
                    if( visibleItemsCountss > 0 ){
                      jQuery('.no-results').hide();
                    }
                    else{
                      jQuery('.no-results').show();
                    }
                }, 200 ) );


                jQuery('select').change(function(){
                  var $this = jQuery(this);

                  // store filter value in object
                  // i.e. filters.color = 'red'
                  var group = $this.attr('value-group');

                  filters[ group ] = $this.find(':selected').attr('data-filter-value');
                  // convert object into array
                  var filterValues = getObjectValues( filters );
                  selectFilter = filterValues.join('');
                  // console.log( selectFilter );
                  $grid.isotope();
                  var visibleItemsCount = $grid.data('isotope').filteredItems.length;
                  console.log( visibleItemsCount );
                    if( visibleItemsCount > 0 ){
                      jQuery('.no-results').hide();
                    }
                    else{
                      jQuery('.no-results').show();
                    }
                });*/

                /*
                function getObjectValues( obj ) {
                  var values = [];
                  for ( var key in obj ) {
                    values.push( obj[ key ] );
                  }
                  return values;
                }*/


                // debounce so filtering doesn't happen every millisecond
                /*function debounce( fn, threshold ) {
                  var timeout;
                  threshold = threshold || 100;
                  return function debounced() {
                    clearTimeout( timeout );
                    var args = arguments;
                    var _this = this;
                    function delayed() {
                      fn.apply( _this, args );
                    }
                    timeout = setTimeout( delayed, threshold );
                  };
                }
                function checkResults(){
                    var visibleItemsCount = $grid.data('isotope').filteredItems.length;
                    console.log(visibleItemsCount);
                    if( visibleItemsCount > 0 ){
                     jQuery('.no-results').show();
                    }
                    else{
                      jQuery('.no-results').hide();
                    }
                  }
                   */

            </script>
            <script type='text/javascript' src='https://eternitech.com/wp-includes/js/hoverintent-js.min.js?ver=2.2.1' id='hoverintent-js-js'></script>
            <script type='text/javascript' src='https://eternitech.com/wp-includes/js/admin-bar.min.js?ver=b32b3d615c70572f50ed972299ad4856' id='admin-bar-js'></script>
            <script type='text/javascript' src='https://eternitech.com/wp-includes/js/dist/vendor/lodash.min.js?ver=4.17.19' id='lodash-js'></script>
            <!--- Filter Post type Using Ajex------>
        </div>
        <?php
        if(isset($params['skill'])) {?>
            <div style="padding: 20px 0;">
                <div id="main1">
                    <section data-qa-section="longread-article" class="longread-article-section pt-30 pb-45 pt-md-50 pb-md-70 pb-xl-100 vs-bg-white"><div class="container-visitor"><div class="row"><div class="inner-container col-lg-12">
                                    <?php
                                    echo "<hr>";
                                    foreach($filters['skills'] as $skill) {
                                        if($skill['keyword'] == $params['skill'])
                                        {     echo $skill['description']; }}
                                    ?>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
            <div style="background-color: #f7faf7; padding: 20px 0;">
                <div id="main1">
                    <div class="new-bottom-box">
                        <h1 class="similar-title">Similar <?php echo $params['skill']; ?> Developer Skills</h1>
                        <div class="title-text">
                            <?php
                            $i=0;
                            $it=0;
                            echo "<div class='similar-skills'>";
                            shuffle($filters['skills']);
                            foreach($filters['skills'] as $skill) {
                                $i++;
                                $it++;
                                echo "<a href='/hire-developers/hire-".htmlspecialchars($skill['keyword'])."-developer'>".$skill['keyword']."</a>";
                                if($i == 4){
                                    echo "</div>";
                                    if($it != 16)

                                    {
                                        echo "<div class='similar-skills'>";
                                        $i=0;
                                    }
                                    else
                                    {
                                        break;
                                    }

                                }
                            }
                            ?>
                        </div>
                    </div>
                    <br>
                    <div class="new-bottom-box">
                        <h1 class="similar-title">Browse Remote <?php echo $params['skill']; ?> developers</h1>
                        <div class="title-text">
                            <?php
                            $i=0;
                            $it=0;
                            echo "<div class='similar-skills'>";
                            shuffle($filters['countries']);
                            foreach($filters['countries'] as $country) {
                                $i++;
                                $it++;
                                echo "<a href='/hire-developers/hire-".urlencode($params['skill'])."-developer-in-".htmlspecialchars($country['name'])."'>".$country['name']." ".$params['skill']." ".'developers'."</a>";
                                if($i == 4){
                                    echo "</div>";
                                    if($it != 16)

                                    {
                                        echo "<div class='similar-skills'>";
                                        $i=0;
                                    }
                                    else
                                    {
                                        break;
                                    }

                                }
                            }
                            ?>

                        </div>
                    </div>
                </div>
            </div>

        <?php } ?>
        <!-- fusion-row -->
        <div class="custom_footer">
            <div class="fusion-fullwidth fullwidth-box divcontain nonhundred-percent-fullwidth fusion-equal-height-columns">
                <div class="fusion-hire-row fusion-row-hire ">
                    <div class="fusion-layout-column fusion_builder_column fusion_builder_column_1_2  fusion-one-half fusion-column-first intrestedguru contactblue divcontain 1_2">
                        <div class="fusion-column-wrapper" style="padding: 30.5% 5% 5%; background-image: url('https://res.cloudinary.com/eternitech/images/v1619601674/eternitech/pop_blue/pop_blue.png'); background-position: center top; background-repeat: no-repeat; background-size: cover; min-height: 367.578px; height: auto;" data-bg-url="/wp-content/uploads/2016/07/1-1.png">
                            <div class="fusion-column-content-centered" style="min-height: 367.578px;">
                                <div class="fusion-column-content">
                                    <div class="fusion-title title fusion-sep-none fusion-title-center fusion-title-size-three fusion-border-below-title" style="margin-top:0px;margin-bottom:2.1%;">
                                        <h3 class="title-heading-center">
                                            <p style="text-align: center;">GOT THE NINJA MATERIAL?</p>
                                        </h3>
                                    </div>
                                    <p>KNOW YOUR WAY IN CODE, HAVE PASSION LIKE FIRE AND WANT TO JOIN THE BEST? APPLY ON ONE OF OUR POSITIONS AND LETS SEE IF WE CAN START A JOURNEY TOGETHER! </p>
                                    <div class="fusion-sep-clear"></div>
                                    <div class="fusion-separator fusion-full-width-sep sep-none"></div>
                                    <div class="fusion-button-wrapper fusion-aligncenter"><a class="fusion-button button-flat button-square button-xlarge button-default button-1 become-ninja" target="_self" href="/our-future-ninjas/"><span class="fusion-button-text">BECOME A NINJA</span></a></div>
                                </div>
                            </div>
                            <div class="fusion-clearfix"></div>
                        </div>
                    </div>
                    <div class="fusion-layout-column fusion_builder_column fusion_builder_column_1_2  fusion-one-half fusion-column-last intrestedguru contactgrey divcontain 1_2 floatit animated animatedFadeInUp fadeInUp" style="margin-top:0px;margin-bottom:0px;width:48%">
                        <div class="fusion-column-wrapper" style="padding: 33% 5% 5%; background-image: url(&quot;https://res.cloudinary.com/eternitech/images/v1619601157/eternitech/pop_2/pop_2.png&quot;); background-position: center top; background-repeat: no-repeat; background-size: cover; min-height: 555px; height: auto;" data-bg-url="/wp-content/uploads/2016/07/2-1.png">
                            <div class="fusion-title title fusion-sep-none fusion-title-center fusion-title-size-three fusion-border-below-title" style="margin-top:0px;margin-bottom:1%;">
                                <h3 class="title-heading-center">
                                    <p style="text-align: center;"><img src="https://eternitech.com/wp-content/uploads/2020/05/close-2.png" class="clickitremove"></p>
                                </h3>
                                <h3 class="white_text"><a href="https://eternitech.com/products/get-a-quote/">GOT A PROJECT ? GET A QUOTE</a> </h3>
                                <h3 class="white_text" data-fontsize="17" data-lineheight="32"><a href="https://eternitech.com/build-your-team/">REMOTE TEAM - $ 1999/m</a> </h3>
                                <h3 class="white_text" data-fontsize="17" data-lineheight="32"><a href="https://eternitech.com/products/new-website-by-next-week/">FREE CUSTOM WEBSITE - $ 599</a> </h3>
                                <p></p>
                            </div>
                            <div class="fusion-clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        jQuery(document).ready(function() {
            jQuery('.form_submit').click(function(){
                var submit = false;
                jQuery('.filter-field').each(function(){
                    if(jQuery(this).val().length > 0){
                        submit = true;
                    }else{
                        jQuery(this).attr('disabled','disabled');
                    }
                });
                if(submit){
                    jQuery(this).closest('form').submit();
                }else{
                    jQuery('.filter-field').removeAttr('disabled');
                    jQuery(this).parent().append('<span class="error-filter">Select any field for filter.</span>');
                    setTimeout(function(){
                        jQuery(this).parent().find('.error-filter').remove();
                    }.bind(this),2000);
                }
            });
        });
    </script>
<?php get_footer(); ?>