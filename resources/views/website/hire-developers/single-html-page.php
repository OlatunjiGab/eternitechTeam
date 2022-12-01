<?php
include '../wp-load.php';
include 'crm-sdk.php';
if(!isset($_REQUEST['id']) || empty($_REQUEST['id'])){
    header("Location: /hire-developers/page/");
    exit();
} else {
    $quryArray = explode("-",$_REQUEST['id']);
    $id = $quryArray[count($quryArray) - 1];
    $_REQUEST['id'] = (int) $id;
    if($_REQUEST['id'] == 0){
        header("Location: /hire-developers/page/");
        exit();
    }
}

get_header();

define( 'hiredeve', '999' );
define( 'API_URL', 'https://crm.eternitech.com/' );

$jsonData = CallAPI('GET', API_URL.'talent/'.$_REQUEST['id']);
$jsonData = json_decode($jsonData, true);
$expertData = $jsonData['expert'];
?>
<!--<link rel="stylesheet" href="/hire-developers/style2.css">-->
<link rel="stylesheet" href="/hire-developers/style.css">
<link rel='stylesheet' id='lfb-bootstrap-select-css'  href='https://eternitech.com/wp-content/plugins/WP_Estimation_Form/assets/css/bootstrap-select.min.css?ver=9.727' type='text/css' media='all' />
<link rel='stylesheet' id='wp-block-library-css'  href='https://eternitech.com/wp-includes/css/dist/block-library/style.min.css?ver=392cc0645c220329076dde9aad7ce6fc' type='text/css' media='all' />
<link rel='stylesheet' id='avada-stylesheet-css'  href='https://eternitech.com/wp-content/themes/Avada/assets/css/style.min.css?ver=5.0.6' type='text/css' media='all' />
<link rel='stylesheet' id='child-style-css'  href='https://eternitech.com/wp-content/themes/Avada-Child-Theme/style.css?ver=392cc0645c220329076dde9aad7ce6fc' type='text/css' media='all' />
<link rel='stylesheet' id='avada-dynamic-css-css'  href='https://eternitech.com/wp-content/uploads/avada-styles/avada-6669.css?timestamp=1615541115&ver=5.0.6' type='text/css' media='all' />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<div id="sliders-container-new1">
    <div class="fusion-slider-container fusion-slider-6669  full-width-slider-container" style="height: 400px; max-width: 100%; max-height: 400px;">
        <style type="text/css" scoped="scoped">
            .fusion-slider-6669 .flex-direction-nav a {
                width:63px;height:63px;line-height:63px;margin-top:-31.5px;font-size:25px;
            }

            main#main .fusion-row {
                max-width: 100%;
            }

            main#main {
                padding: 0 !important;
            }
            .custom_footer {
                width: 100%;
                max-width: 1100px !important;
                margin: 0 auto !important;
                display: block;
            }
            #mains {
                top: 20px;
                max-width: 1100px;
                margin: 0 auto;
                padding: 0 15px;
            }
            @media screen and (max-width: 768px) {
                .developer h2.inside_title_related {
                    text-align: left;
                    display: block;
                    width: 90%;
                }
                .col-md-3.flag-top span {
                    top: 2px;
                    right: 108px;
                }
                .col-md-3.flag-top img {
                    right: 44px;
                    position: relative;
                    top: -27px;
                }
                .heading .title-heading-left{color:#000 !important;font-size:28.5px !important;line-height:34.2px !important;}

            }
            #rcorners1 {
                border-radius: 25px;
                background: #355e8b;
                padding: 0 20px 0 20px;
                width: 100%;
                height: 1em;
            }
            .single_hire .fusion-row .sidebar_single {
                width: 43% !important;
            }
            .single_hire .flag_top_single a img {
                border: 9px solid #fff;
            }
            .single_hire .fusion_builder_column_1_1 h2 {
                padding: 15px 15px 0 15px !important;
            }
            .tfs-slider .slide-content-container h3 {
                font-size: 17px !important;
            }
        </style>
        <div class="tfs-slider flexslider main-flex full-width-slider">
            <ul class="slides" style="max-width:100%;">
                <li data-mute="yes" data-loop="yes" data-autoplay="yes" style="display: block; z-index: 2;" class="flex-active-slide">
                    <div class="slide-content-container slide-content-left">
                        <div class="slide-content" style="opacity: 1; margin-top: 13%;">
                            <div class="heading ">
                                <div class="fusion-title-sc-wrapper">
                                    <div class="fusion-title title fusion-sep-none fusion-title-size-two fusion-border-below-title">
                                        <h2 class="title-heading-left" style="color:#000;font-size:45px;line-height:1.2em;" data-inline-fontsize="true" data-inline-lineheight="true" data-fontsize="45" data-lineheight="72">Hire Full Time Available Developers Now</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="caption with-bg">
                                <div class="fusion-title-sc-wrapper">
                                    <div class="fusion-title title fusion-sep-none fusion-title-size-three fusion-border-below-title">
<!--                                        <h3 class="title-heading-left">Hire available developers now<br>-->
<!--or contact us for custom recruitment</h3>-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="background banner-img" style="height:400px;">
                    </div>
                </li>
            </ul>
            <ul class="flex-direction-nav">
                <li><a class="flex-prev flex-disabled" href="#" tabindex="-1"></a></li>
                <li><a class="flex-next flex-disabled" href="#" tabindex="-1"></a></li>
            </ul>
        </div>
    </div>
</div>
<div id="mains" class="clearfix width-100 single_hire">
    <div class="fusion-row">
        <div class="page_title row">
            <div class="col-md-7 fusion-title title fusion-sep-none">
                <h2 class="title-heading-left" style="max-width: 100%; line-height: 1.4em !important;"><?php echo $expertData['first_name'].' '.$expertData['last_name'];?></h2>
            </div>
            <div class="col-sm-5 flag_top_single">
                <a href="#">
                    <img src="<?php echo API_URL.$expertData['country_data']['flag'];?>" alt="" class="lazyloaded">
                </a>
                <span> <strong><?php echo $expertData['country_data']['name'];?></strong></span>
            </div>
        </div>
        <div class="col-md-12 sub_title">
            <h5 class="col-md-4 mb-4 d-flex justify-content-between" data-fontsize="25" data-lineheight="22">
                <?php echo $expertData['headline'];?>  <span class="other_title"><?php $experience  = date_diff( date_create($expertData['experience_start']), date_create() ); echo $experience->y.($experience->m>0?'.'.$experience->m:'');?> YEARS OF EXPERIENCE</span>
            </h5>
        </div>
        <div class="col-md-8">
            <div id="content" class="full-width">
                <p id="rcorners1"></p>
                <div class=" fusion-layout-column fusion_builder_column fusion_builder_column_1_1  fusion-one-full fusion-column-first fusion-column-last fusion-column-no-min-height 1_1">
                    <h2 class="background_letter inside_title">About Developer</h2>
                    <div class="background_letter fusion-column-content-centered" style="min-height: 176px;">
                        <div class="fusion-column-content">
                            <p style="text-align: justify;"><?php echo $expertData['description'];?></p>
                        </div>
                    </div>
                </div>
                <p id="rcorners1"></p>
                <div class="full_btn_blue">
                <!--<a href="#">Download CV</a>-->
              <?php  echo do_shortcode( '[contact-form-7 id="7326" title="download cv"]' ); ?>
           </div>
            </div>
        </div>
        <div class="col-md-4 sidebar_single">
            <?php if($expertData['youtube_embed']) { ?>
            <div class="youtube_video">
              <iframe width="560" height="315" src="<?=$expertData['youtube_embed']?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen>
                </iframe>
            </div>
            <?php } ?>

            <div class="full_btn_orange">

                <a href="http://eternitech.com/hire-local-developer">Get Instant Quote</a>
            </div>
            <div class="text-btween">Full Time Developers Start From $1999</div>
           <!-- <div class="full_btn_blue">    -->
                <!--<a href="#">Download CV</a>-->
           <!--   <?php //  echo do_shortcode( '[contact-form-7 id="7326" title="download cv"]' ); ?>-->
           <!--</div>-->
            <div class="fusion-builder-row fusion-row image_desing">
                <div class="fusion-builder-row fusion-row image_desing">
                    <div class="col-sm-12 spcl_tech side_spcl_tech">
                        <div class="d-flex">
                            <p class="splezition2">Specialisation</p>
                            <div class="shield_img_side">
                                <a class="img_tech" href="#">
                                    <img src="https://res.cloudinary.com/eternitech/images/v1619698751/eternitech/yii-img/yii-img.png">
                                </a>
                            </div>
                        </div>
                        <p class="splezition"></p>
                        <div class="row">
                            <div class="col-md-12 skills sap">
                                <strong>
                                    <?php if(isset($expertData['skills']) && !empty($expertData['skills'])):
                                        foreach($expertData['skills'] as $skill):?>
                                            <a href="<?php echo $skill['url'];?>" class="multi_skills"> <?php echo $skill['keyword'];?></a>
                                        <?php endforeach; endif;?>
                                </strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-12 developer">
            <h2 class="inside_title_related">Similar Developer</h2>
            <div class="fusion-builder-row fusion-row box_rows">
                <?php if(isset($jsonData['similarExperts']) && !empty($jsonData['similarExperts'])){
                    foreach($jsonData['similarExperts'] as $similarExpert){?>
                        <div class="col-sm-6 box_design">
                            <div class="card">
                                <div class="card-body">
                                    <h3 class="card-title text-center">
                                        <a href="/hire-developers/single-html-page.php<?php echo "?id=".$similarExpert['id'];?>"><?php echo $similarExpert['first_name'].' '.$similarExpert['last_name'];?></a>
                                    </h3>
                                    <div class="fusion-builder-row fusion-row">
                                        <div class="col-md-9">
                                            <h5 class="mb-4 text-center">
                                                <span><?php echo $similarExpert['headline'];?> |</span> <?php $experience  = date_diff( date_create($similarExpert['experience_start']), date_create() ); echo $experience->y.($experience->m>0?'.'.$experience->m:'');?> YEARS OF EXPERIENCE
                                            </h5>
                                        </div>
                                        <div class="col-md-3 flag-top">
                                            <a href="#">
                                                <img src="<?php echo API_URL.$similarExpert['country_data']['flag'];?>" alt="">
                                            </a>
                                            <span> <?php echo $similarExpert['country_data']['name'];?></span>
                                        </div>
                                    </div>
                                    <p class="card-text"></p><p class="text_between"><?php echo substr($similarExpert['description'],0,100).(strlen($similarExpert['description'])>100?'...':'');?>.</p>
                                    <div class="fusion-builder-row fusion-row image_desing">
                                        <div class="col-sm-3 left_img">
                                            <a class="img_tech" href="#">
                                                <img width="400" height="400" src="https://res.cloudinary.com/eternitech/images/v1619698751/eternitech/yii-img/yii-img.png" class="attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="yii - Web Systems Development">
                                            </a>
                                        </div>
                                        <div class="col-sm-5 spcl_tech">
                                            <p class="splezition1">Specialisation</p>
                                            <p class="splezition">
                                                <strong>
                                                </strong></p>
                                            <div class="col-md-12 skills">
                                                    <?php if(isset($similarExpert['skills']) && !empty($similarExpert['skills'])):
                                                        foreach($similarExpert['skills'] as $skill):?>
                                                        <div class="col-md-6">
                                                            <a href="<?php echo $skill['url'];?>" class="multi_skills"> <?php echo $skill['keyword'];?></a>
                                                        </div>
                                  <?php endforeach; endif;?></div><strong>
                                            </strong>
                                            <p></p>
                                            <!-- <a href=""></a> -->
                                        </div>
                                        <div class="col-sm-4 right_intme">
                                            <a href="/hire-developers/single-html-page.php<?php echo "?id=".$similarExpert['id'];?>">Interview Me</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } } ?>
            </div>
        </div>
    </div>  <!-- fusion-row -->
</div>

<div class="custom_footer">
    <div class="fusion-fullwidth fullwidth-box divcontain nonhundred-percent-fullwidth fusion-equal-height-columns">
        <div class="fusion-builder-row fusion-row ">
            <div class="fusion-layout-column fusion_builder_column fusion_builder_column_1_2  fusion-one-half fusion-column-first intrestedguru contactblue divcontain 1_2">
                <div class="fusion-column-wrapper" style="padding: 30.5% 5% 5%; background-image: url('https://res.cloudinary.com/eternitech/images/v1619601674/eternitech/pop_blue/pop_blue.png'); background-position: center top; background-repeat: no-repeat; background-size: cover; min-height: 367.578px; height: auto;" data-bg-url="/wp-content/uploads/2016/07/1-1.png">
                    <div class="fusion-column-content-centered" style="min-height: 367.578px;"><div class="fusion-column-content"><div class="fusion-title title fusion-sep-none fusion-title-center fusion-title-size-three fusion-border-below-title" style="margin-top:0px;margin-bottom:2.1%;"><h3 class="title-heading-center"><p style="text-align: center;">GOT THE NINJA MATERIAL?</p></h3></div><p>KNOW YOUR WAY IN CODE, HAVE PASSION LIKE FIRE AND WANT TO JOIN THE BEST? APPLY ON ONE OF OUR POSITIONS AND LETS SEE IF WE CAN START A JOURNEY TOGETHER! </p>
                            <div class="fusion-sep-clear"></div><div class="fusion-separator fusion-full-width-sep sep-none"></div><div class="fusion-button-wrapper fusion-aligncenter"><a class="fusion-button button-flat button-square button-xlarge button-default button-1 become-ninja" target="_self" href="/our-future-ninjas/"><span class="fusion-button-text">BECOME A NINJA</span></a></div></div></div><div class="fusion-clearfix"></div>

                </div>
            </div><div class="fusion-layout-column fusion_builder_column fusion_builder_column_1_2  fusion-one-half fusion-column-last intrestedguru contactgrey divcontain 1_2 floatit animated animatedFadeInUp fadeInUp" style="margin-top:0px;margin-bottom:0px;width:48%">
                <div class="fusion-column-wrapper" style="padding: 33% 5% 5%; background-image: url('https://res.cloudinary.com/eternitech/images/v1619601157/eternitech/pop_2/pop_2.png'); background-position: center top; background-repeat: no-repeat; background-size: cover; min-height: 555px; height: auto;" data-bg-url="/wp-content/uploads/2016/07/2-1.png">
                    <div class="fusion-title title fusion-sep-none fusion-title-center fusion-title-size-three fusion-border-below-title" style="margin-top:0px;margin-bottom:1%;"><h3 class="title-heading-center"><p style="text-align: center;"><img src="https://eternitech.com/wp-content/uploads/2020/05/close-2.png" class="clickitremove"></p></h3><h3 class="white_text" data-fontsize="17" data-lineheight="32"><a href="https://eternitech.com/products/get-a-quote/">GOT A PROJECT ? GET A QUOTE</a> </h3>
                        <h3 class="white_text" data-fontsize="17" data-lineheight="32"><a href="https://eternitech.com/build-your-team/">REMOTE TEAM - $ 1999/m</a> </h3>
                        <h3 class="white_text" data-fontsize="17" data-lineheight="32"><a href="https://eternitech.com/products/new-website-by-next-week/">FREE CUSTOM WEBSITE - $ 599</a> </h3><p></p></div><div class="fusion-clearfix"></div>

                </div>
            </div></div></div>

</div>
<?php get_footer(); ?>     