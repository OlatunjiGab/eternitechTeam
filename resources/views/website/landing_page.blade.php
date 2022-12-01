<?php
$skillsArr = explode(',', $skills);
$skill1 = $skillsArr[0] ?? '';
$skill2 = $skillsArr[1] ?? '';
$skill3 = $skillsArr[2] ?? '';

?>
        <!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8"/>
    <meta
            name="viewport"
            content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />
    <!-- Yandex.Metrika counter -->
    <script type="text/javascript" >
        (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
            m[i].l=1*new Date();
            for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}
            k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
        (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

        ym(90153504, "init", {
            clickmap:true,
            trackLinks:true,
            accurateTrackBounce:true,
            webvisor:true
        });
    </script>
    <noscript><div><img src="https://mc.yandex.ru/watch/90153504" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
    <!-- /Yandex.Metrika counter -->

    <!-- Google font -->
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
    <link
            href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Permanent+Marker&display=swap"
            rel="stylesheet"
    />

    <!-- Bootstrap CSS -->
    <link
            rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css"
            integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO"
            crossorigin="anonymous"
    />
    <link
            rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css"
    />

    <link
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
    />
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet"/>
    <link
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css"
    />

    <link rel="stylesheet" href="{{ asset('/lp/css/style.css') }}"/>

    <title>Top {{$skills}} team | Eternitech </title>
    <script type="application/javascript">
        window.crm = {
          meetUrl: "{{\App\Helpers\ShortLink::getMeetingShortLink($id)}}",
          chatUrl: "{{\App\Helpers\ShortLink::getWhatsAppShortLink($id)}}",
          homepageUrl: "{{\App\Helpers\ShortLink::getHomepageShortLink($id)}}"
        };
    </script>
</head>
<body>
<input id="projectIDKey" type="hidden" value="{{$projectID}}" />
<!-- lead collect popup form -->
<div
        id="popup-lead-collect-form"
        class="popup-form"
        frameborder="0"
        scrolling="no"
        style="
        display: none;
        z-index: 999999999;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        width: 100%;
        height: 100%;
      "
></div>

<!-- Navbar -->
<nav class="eternitech-lp--nav navbar sticky-top">
    <div class="eternitech-lp--container container">
        <a class="navbar-brand" href="#">
            <img src="{{ asset('/lp/img/logo.png') }}" alt="" height="36px"/>
        </a>
    </div>
</nav>

<!-- Section 1 -->
<div class="eternitech-lp--section-1 container-fluid">
    <div class="eternitech-lp--inner-div">
        <div class="eternitech-lp--container container">
            <div class="eternitech-lp--clouds-div">
                <div
                        class="eternitech-lp--cloud cloud-1 eternitech-lp--hero-floating"
                >
                    <h3>{{$skill1}}</h3>
                </div>
                <div
                        class="eternitech-lp--cloud cloud-2 eternitech-lp--hero-floating2"
                >
                    <h3>{{$skill2}}</h3>
                </div>
                <div
                        class="eternitech-lp--cloud cloud-3 eternitech-lp--hero-floating3"
                >
                    <h3>{{$skill3}}</h3>
                </div>
            </div>
            <div class="eternitech-lp--main-heading" data-aos="fade-up">
                @if(!\App\Models\Company::isNameUnknown($clientName))
                <h2>{{$clientName}},</h2>
                @endif
                <h2>
                    Hand your {{$skills}} Project <br/> to top developers. <b>Hassle-free!</b>
                </h2>
            </div>
            <div class="eternitech-lp--laptop-logo">
                <img src="{{ $mainSkill->icon ?? asset('/lp/img/section-1-logo.png') }}" alt="">
            </div>
        </div>
    </div>
</div>

<!-- Section 2 -->
<div class="eternitech-lp--section-2 container-fluid">
    <div class="eternitech-lp--inner-div">
        <div class="eternitech-lp--container container">
            <div class="eternitech-lp--sub-heading">
                <h3 data-aos="fade-up"><span>WHO</span> WE ARE</h3>
            </div>
            <div class="eternitech-lp--paragraph" data-aos="fade-up">
                <p>
                    Founded in Israel - The start-up nation. <br/>
                    Eternitech is an award-winning web & mobile development agency built by entrepreneur for
                    entrepreneurs. <br/><br/>
                    Having built multiple start-ups ourselves we know the struggles with finding, hiring, training, and
                    keeping top developers.
                </p>
            </div>
            <div
                    class="eternitech-lp--stats-container container"
                    data-aos="fade-up"
            >
                <div class="row">
                    <div class="col-sm-3">
                        <img
                                class="eternitech-lp--stats-icon-1"
                                src="{{ asset('/lp/img/stats-icon-1.png') }}"
                                alt=""
                        />
                        <h4>500+</h4>
                        <p>Successful {{$skills}} projects</p>
                    </div>
                    <div class="col-sm-3">
                        <img
                                class="eternitech-lp--stats-icon-2"
                                src="{{ asset('/lp/img/stats-icon-2.png') }}"
                                alt=""
                        />
                        <h4>114</h4>
                        <p>Experienced Developers in our teams</p>
                    </div>
                    <div class="col-sm-3">
                        <img
                                class="eternitech-lp--stats-icon-3"
                                src="{{ asset('/lp/img/stats-icon-3.png') }}"
                                alt=""
                        />
                        <h4>19 Years</h4>
                        <p>
                            Technological experience worldwide
                        </p>
                    </div>
                    <div class="col-sm-3">
                        <img
                                class="eternitech-lp--stats-icon-4"
                                src="{{ asset('/lp/img/stats-icon-4.png') }}"
                                alt=""
                        />
                        <h4>20,000+</h4>
                        <p>Eternitech's Community of Tech-specific experts</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Section 3 -->
<div class="eternitech-lp--section-3 container-fluid">
    <div class="eternitech-lp--inner-div">
        <div class="eternitech-lp--container container">
            <div class="eternitech-lp--sub-heading">
                <h3 data-aos="fade-up"><span>HOW</span> CAN WE HELP?</h3>
            </div>
            <div class="eternitech-lp--paragraph" data-aos="fade-up">
                <p>
                    You want your projects done quickly, on budget, and with a team who cares about the end results same
                    as you do.
                    <b>Most teams DO NOT, and 60% of development projects fail because of it.</b>
                    With 19+ years of experience designing full-service development services.<br><br>
                    <b>We care. We listen. We think.</b> <br>
                    Whether your project has bugs. Developers left. Or you simply need specialized technology.
                    Eternitech is there to assist.
                </p>
            </div>
            <div class="row row-cols-2">
                <div class="col-sm-4" data-aos="fade-up">
                    <div class="card h-100">
                        <div class="card-body">
                            <img src="{{ asset('/lp/img/section-3-icon-1.png') }}" alt=""/>
                            <h5 class="card-title">High <br/>Satisfaction</h5>
                            <p class="card-text">
                                You will have a project manager, account manager, and QA contact at all
                                times to assist you on project.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4" data-aos="fade-up">
                    <div class="card h-100">
                        <div class="card-body">
                            <img src="{{ asset('/lp/img/section-3-icon-2.png') }}" alt=""/>
                            <h5 class="card-title">Pick <br/>Your Team</h5>
                            <p class="card-text">
                                Get 2-4 CVs of matching developers, <br/>
                                interview them, and make sure you "click".
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4" data-aos="fade-up">
                    <div class="card h-100">
                        <div class="card-body">
                            <img src="{{ asset('/lp/img/section-3-icon-3.png') }}" alt=""/>
                            <h5 class="card-title">
                                Direct Communication
                            </h5>
                            <p class="card-text">
                                Coordinate directly with your developers or through your project manager.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4" data-aos="fade-up">
                    <div class="card h-100">
                        <div class="card-body">
                            <img src="{{ asset('/lp/img/section-3-icon-4.png') }}" alt=""/>
                            <h5 class="card-title">Effortless 5-Step Onboarding</h5>
                            <p class="card-text">
                                Get your project moving in 3-7 with our quick and effortless onboarding process.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4" data-aos="fade-up">
                    <div class="card h-100">
                        <div class="card-body">
                            <img src="{{ asset('/lp/img/section-3-icon-5.png') }}" alt=""/>
                            <h5 class="card-title">
                                Save up to 70%
                            </h5>
                            <p class="card-text">
                                Our services are start-up friendly. Save on costs with your remote team.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4" data-aos="fade-up">
                    <div class="card h-100">
                        <div class="card-body">
                            <img src="{{ asset('/lp/img/section-3-icon-6.png') }}" alt=""/>
                            <h5 class="card-title">
                                Fully Managed services
                            </h5>
                            <p class="card-text">
                                <b>Our developers are not alone.</b><br/>
                                Each developer has a backup team,<br/>
                                and Technical Team Leader to consult.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="eternitech-lp--end-cta">
                <div data-aos="fade-up">
                    <a
                            id="eternitech-lp--popup-button"
                            class="eternitech-lp--end-cta-btn popup-form-btn btn"
                            role="button"
                            data-toggle="modal"
                    >Let's talk <span class="fa fa-angle-right"></span
                        ></a>
                </div>
            </div>
            <div class="section-3--img-div">
                <img
                        class="section-3--img-ninja1 eternitech-lp--float-up"
                        src="{{ asset('/lp/img/section-3-ninja1.png') }}"
                        alt=""
                />
                <img
                        class="section-3--img-ninja2"
                        src="{{ asset('/lp/img/section-3-ninja2.png') }}"
                        alt=""
                />
            </div>
        </div>
    </div>
</div>

<!-- Section 4 -->
<div class="eternitech-lp--section-4 container-fluid">
    <div class="eternitech-lp--inner-div">
        <div class="eternitech-lp--container container">
            <div data-aos="fade-up">
            <span class="eternitech-lp--section-4-heading"
            >Say Goodbye
              <img
                      src="{{ asset('/lp/img/waving-hand 1.png') }}"
                      alt=""
                      height="72px"
                      width="72px"
              /> to: </span>
            </div>
            <p data-aos="fade-up">
                "Next Week" <br/>
                "Cannot Be Done" <br/>
                "Need More Money" <br/>
                "Unexpected Delays" <br/>
                Endless Bugs & Frustration <br/>
                <br/>
                You deserve an experienced and responsible team who delivers projects with pride.
            </p>
            <div class="eternitech-lp--end-cta">
                <div data-aos="fade-up">
                    <a
                            id="eternitech-lp--popup-button"
                            class="eternitech-lp--end-cta-btn popup-form-btn btn"
                            role="button"
                            data-toggle="modal"
                    >Let's talk <span class="fa fa-angle-right"></span
                        ></a>
                </div>
            </div>
            <div class="eternitech-lp--sub-heading">
                <h3 data-aos="fade-up"><span>5-Step</span> Onboarding Process</h3>
            </div>
        </div>
    </div>
</div>

<!-- Section 5 -->
<div class="eternitech-lp--section-5 container-fluid">
    <div class="eternitech-lp--inner-div">
        <div class="eternitech-lp--container container">
            <div class="eternitech-lp--hw-row1 row align-items-center">
                <div class="eternitech-lp--hw-row1-col1 col-sm-7">
                    <h5 data-aos="fade-up">STEP 01</h5>
                    <div data-aos="fade-up">
                        <p>
                            <b>ASSESS THE SITUATION</b><br/>
                            Meet with our {{$skills}} Tech Leader so we can better understand your needs.
                        </p>
                    </div>
                </div>
                <div class="eternitech-lp--hw-row1-col2 col-sm-5">
                    <img
                            class="eternitech-lp--hw-cloud eternitech-lp--hw-floating"
                            src="{{ asset('/lp/img/section-5-cloud.png') }}"
                            alt=""
                    />
                    <img
                            class="eternitech-lp--hw-rocket eternitech-lp--hw-floating"
                            src="{{ asset('/lp/img/section-5-rocket-ninja.png') }}"
                            alt=""
                    />
                </div>
            </div>
            <!-- .eternitech-lp--hw-row1 end -->
            <div class="eternitech-lp--hw-row2 row align-items-center row-cols-2">
                <div class="eternitech-lp--hw-row2-col2 col-sm-5 order-sm-2">
                    <h5 data-aos="fade-up">STEP 02</h5>
                    <div data-aos="fade-up">
                        <p>
                            <b>PRESENTING THE PLAN</b><br/>
                            We will present our plan to put your project on track: Team structure & CVs, suitable
                            frameworks, Tech reviews, budget, timelines, etc.
                        </p>
                    </div>
                </div>
                <div class="eternitech-lp--hw-row2-col1 col-sm-7 order-sm-1">
                    <img
                            class="eternitech-lp--hw-ninja2 eternitech-lp--hw-floating eternitech-lp--delay"
                            src="{{ asset('/lp/img/section-5-ninja-2.png') }}"
                            alt=""
                    />
                </div>
                <div class="eternitech-lp--hw-row2-col3 col-sm-5 order-sm-3">
                    <h5 data-aos="fade-up">STEP 03</h5>
                    <div data-aos="fade-up">
                        <p>
                            <b>PICK YOUR TEAM</b><br/>
                            Meet developers we’ve shortlisted for your project and pick the best match. <br/>
                            We want you to be comfortable with your new team.
                        </p>
                    </div>
                </div>
                <div class="eternitech-lp--hw-row2-col4 col-sm-7 order-sm-4">
                    <img
                            class="eternitech-lp--hw-tree eternitech-lp--hw-swaying"
                            src="{{ asset('/lp/img/section-5-tree.png') }}"
                            alt=""
                    />
                    <img
                            class="eternitech-lp--hw-ninja3"
                            src="{{ asset('/lp/img/section-5-ninja-3.png') }}"
                            alt=""
                    />
                    <img
                            data-aos="eternitech-lp--hw-leaf1"
                            data-aos-easing="ease-in-out"
                            data-aos-delay="0"
                            data-aos-duration="3000"
                            data-aos-anchor-placement="top-center"
                            class="eternitech-lp--hw-leaf1"
                            src="{{ asset('/lp/img/section-5-leaf-1.png') }}"
                            alt=""
                    />
                </div>
            </div>
            <!-- .eternitech-lp--hw-row2 end -->
            <div class="eternitech-lp--hw-row3 row align-items-center">
                <div class="eternitech-lp--hw-row3-col2 col-sm-5 order-sm-2">
                    <h5 data-aos="fade-up">STEP 04</h5>
                    <div data-aos="fade-up">
                        <p>
                            <b>GETTING OFFICIAL</b><br/>
                            As soon as you approve the team and solution presented, <br/>
                            we will move forward and prepare the official offer and terms.
                        </p>
                    </div>
                </div>
                <div class="eternitech-lp--hw-row3-col1 col-sm-7 order-sm-1">
                    <img
                            class="eternitech-lp--hw-ninja4"
                            src="{{ asset('/lp/img/section-5-ninja-4.png') }}"
                            alt=""
                    />
                    <img
                            class="eternitech-lp--hw-grass1 eternitech-lp--grass1-sway"
                            src="{{ asset('/lp/img/section-5-grass-1.png') }}"
                            alt=""
                    />
                    <img
                            data-aos="eternitech-lp--hw-leaf2"
                            data-aos-easing="ease-in-out"
                            data-aos-delay="0"
                            data-aos-duration="3000"
                            data-aos-anchor-placement="top-center"
                            class="eternitech-lp--hw-leaf2"
                            src="{{ asset('/lp/img/section-5-leaf-2.png') }}"
                            alt=""
                    />
                </div>
            </div>
            <!-- .eternitech-lp--hw-row3 end -->
            <div class="eternitech-lp--hw-row4 row align-items-center">
                <div class="eternitech-lp--hw-row4-col1 col-sm-7">
                    <h5 data-aos="fade-up">STEP 05</h5>
                    <div data-aos="fade-up">
                        <p>
                            <b>Effortless start to your project.</b><br/>
                            Book a complimentary consultation with our {{$skills}} team leader.
                        </p>
                    </div>
                </div>
                <div class="eternitech-lp--hw-row4-col2 col-sm-5">
                    <img
                            class="eternitech-lp--hw-ninja5"
                            src="{{ asset('/lp/img/section-5-ninja-5.png') }}"
                            alt=""
                    />
                    <img
                            class="eternitech-lp--hw-grass2 eternitech-lp--grass2-sway"
                            src="{{ asset('/lp/img/section-5-grass-2.png') }}"
                            alt=""
                    />
                    <img
                            class="eternitech-lp--hw-grass3 eternitech-lp--grass3-sway"
                            src="{{ asset('/lp/img/section-5-grass-3.png') }}"
                            alt=""
                    />
                    <img
                            class="eternitech-lp--hw-bee eternitech-lp--hw-floating"
                            src="{{ asset('/lp/img/section-5-bee.png') }}"
                            alt=""
                    />
                    <img
                            data-aos="eternitech-lp--hw-leaf3"
                            data-aos-easing="ease-in-out"
                            data-aos-delay="0"
                            data-aos-duration="3000"
                            data-aos-anchor-placement="top-center"
                            class="eternitech-lp--hw-leaf3"
                            src="{{ asset('/lp/img/section-5-leaf-3.png') }}"
                            alt=""
                    />
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Section 6 -->
<div class="eternitech-lp--section-6 container-fluid">
    <div class="eternitech-lp--inner-div">
        <div class="eternitech-lp--container container">
            <h3 data-aos="fade-up">
                <span>GET THE HELP YOU NEED,<br/>
                    <b>CONSULT FOR FREE </b><br/>
                WITH OUR {{$skills}} TEAM LEADER </span>
            </h3>
            <div data-aos="fade-up">
                <a
                        class="eternitech-lp--cta-button popup-form-btn btn"
                        role="button"
                        data-toggle="modal"
                >LET'S TALK <span class="fa fa-angle-right"></span
                    ></a>
            </div>
        </div>
    </div>
</div>

<!-- Section 7 -->
<div class="eternitech-lp--section-7 container-fluid">
    <div class="eternitech-lp--inner-div">
        <div class="eternitech-lp--container container">
            <div class="eternitech-lp--sub-heading">
                <h3 data-aos="fade-up"><span>FEW</span> OF OUR HAPPY CLIENTS!</h3>
            </div>
            <div class="eternitech-lp--testimonial-carousel">
                <div id="eternitech-lp--testimonial" class="owl-carousel">
                    <div class="eternitech-lp--testimonial-item">
                        <div class="eternitech-lp--testimonial-box">
                            {{--<img src="{{ asset('/lp/img/testimonial-img.png') }}" alt="" />--}}
                            <h5 class="eternitech-lp--testimonial-name">Gil S.</h5>
                            <h6 class="eternitech-lp--testimonial-title">
                                CTO, Student Marketplace
                            </h6>
                            <div class="eternitech-lp--testimonial-rating">
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                            </div>
                            <p>
                                The team performed all the tasks to our complete satisfaction. <br/>
                                In addition to the programming tasks, the project manager showed commitment to the
                                project and came up with creative solutions to solve them.

                                We will be more than happy to hire Eternitech's services in the future.
                            </p>
                        </div>
                    </div>
                    <!-- .eternitech-lp--testimonial-item end -->
                    <div class="eternitech-lp--testimonial-item">
                        <div class="eternitech-lp--testimonial-box">
                            {{--<img src="{{ asset('/lp/img/testimonial-img.png') }}" alt="" />--}}
                            <h5 class="eternitech-lp--testimonial-name">Gretchen R.</h5>
                            <h6 class="eternitech-lp--testimonial-title">
                                Project Manager, Utility billing company
                            </h6>
                            <div class="eternitech-lp--testimonial-rating">
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                            </div>
                            <p>
                                I’ve had the pleasure of working with Eternitech’s team for more than a year. I’ve found
                                our dedicated developer to be competent and fully committed. Completing projects with
                                accuracy and on time. Staying flexible with changes. Handling multiple projects of ours.
                                And at times going above and beyond to complete urgent projects in off hours without
                                extra charges. Our dedicated developer always made sure to share his concerns and
                                thoughts comfortably, he wasn’t just a “Yes man”!
                            </p>
                        </div>
                    </div>
                    <!-- .eternitech-lp--testimonial-item end -->
                    <div class="eternitech-lp--testimonial-item">
                        <div class="eternitech-lp--testimonial-box">
                            {{--                            <img src="{{ asset('/lp/img/testimonial-img.png') }}" alt="" />--}}
                            <h5 class="eternitech-lp--testimonial-name">Elad M.</h5>
                            <h6 class="eternitech-lp--testimonial-title">
                                CEO, Web design studio
                            </h6>
                            <div class="eternitech-lp--testimonial-rating">
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                            </div>
                            <p>
                                Excellent service and very high professionalism.
                                In a very short period of time, they programmed an integration component for an event
                                ticketing system with the selected payment provider.
                            </p>
                        </div>
                    </div>
                    <!-- .eternitech-lp--testimonial-item end -->
                    <div class="eternitech-lp--testimonial-item">
                        <div class="eternitech-lp--testimonial-box">
                            {{--                            <img src="{{ asset('/lp/img/testimonial-img.png') }}" alt="" />--}}
                            <h5 class="eternitech-lp--testimonial-name">Ron A.</h5>
                            <h6 class="eternitech-lp--testimonial-title">
                                CEO, Preenpets
                            </h6>
                            <div class="eternitech-lp--testimonial-rating">
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                            </div>
                            <p>
                                Great job on our PreenPets website! It achieved the professional look I was after,
                                integrated blogs, registration forms and was written perfectly. The work was fast and
                                very high quality. Thanks guys!
                            </p>
                        </div>
                    </div>
                    <!-- .eternitech-lp--testimonial-item end -->
                    <div class="eternitech-lp--testimonial-item">
                        <div class="eternitech-lp--testimonial-box">
                            {{--                            <img src="{{ asset('/lp/img/testimonial-img.png') }}" alt="" />--}}
                            <h5 class="eternitech-lp--testimonial-name">Nadav B.</h5>
                            <h6 class="eternitech-lp--testimonial-title">
                                Founder, 3D assets marketplace
                            </h6>
                            <div class="eternitech-lp--testimonial-rating">
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                            </div>
                            <p>
                                They jumped into deep water quickly, understood the complexity of the work to its
                                details, and responded to everything I asked for quickly and without extras at the
                                predetermined price.
                                The speed of the response to the project proposal on the site was a sign of seriousness
                                followed by a modest and not inflated price proposal and ended with an excellent
                                performance of the work.
                                Highly recommended.
                            </p>
                        </div>
                    </div>
                    <!-- .eternitech-lp--testimonial-item end -->
                    <div class="eternitech-lp--testimonial-item">
                        <div class="eternitech-lp--testimonial-box">
                            {{--<img src="{{ asset('/lp/img/testimonial-img.png') }}" alt="" />--}}
                            <h5 class="eternitech-lp--testimonial-name">Einat S.</h5>
                            <h6 class="eternitech-lp--testimonial-title">
                                Manager, Large Multi-Blog Platform
                            </h6>
                            <div class="eternitech-lp--testimonial-rating">
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                            </div>
                            <p>
                                Working with Eternitech was successful and beneficial.
                                All along the way, from the beginning of the specification of the project to its
                                successful delivery, everything was done professionally, with high availability and a
                                lot of desire to help and support professionally.

                                The project was not easy, a large platform moved to Amazon and all the way there was a
                                feeling of having everything under control.

                                Thank you.
                            </p>
                        </div>
                    </div>
                    <!-- .eternitech-lp--testimonial-item end -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Section 8 -->
<div class="eternitech-lp--section-8 container-fluid">
    <div class="eternitech-lp--inner-div">
        <div class="eternitech-lp--container container">
            <div data-aos="fade-up">
                <p class="eternitech-lp--heading">
                    A Glimpse of Our Past {{$skills}} Projects
                </p>
            </div>
            <div
                    class="eternitech-lp--portfolio row row-cols-2"
                    data-aos="fade-up"
            >
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="card-img-wrap">
                                <img src="{{ asset('/lp/img/section-8-img-1.png') }}" alt=""/>
                            </div>
                            <h5 class="eternitech-lp--portfolio-title">1 Pami.co.il</h5>
                            <div class="eternitech-lp--portfolio-skill">
                                <p>Skills:</p>
                                <span>Magento</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="card-img-wrap">
                                <img src="{{ asset('/lp/img/section-8-img-2.png') }}" alt=""/>
                            </div>
                            <h5 class="eternitech-lp--portfolio-title">Aman Group</h5>
                            <div class="eternitech-lp--portfolio-skill">
                                <p>Skills:</p>
                                <span>ASP.NET</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="card-img-wrap">
                                <img src="{{ asset('/lp/img/section-8-img-3.png') }}" alt=""/>
                            </div>
                            <h5 class="eternitech-lp--portfolio-title">
                                American Outlets
                            </h5>
                            <div class="eternitech-lp--portfolio-skill">
                                <p>Skills:</p>
                                <span>Laravel</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="card-img-wrap">
                                <img src="{{ asset('/lp/img/section-8-img-4.png') }}" alt=""/>
                            </div>
                            <h5 class="eternitech-lp--portfolio-title">
                                Bat Yam Municipality
                            </h5>
                            <div class="eternitech-lp--portfolio-skill">
                                <p>Skills:</p>
                                <span>PHP</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="card-img-wrap">
                                <img src="{{ asset('/lp/img/section-8-img-5.png') }}" alt=""/>
                            </div>
                            <h5 class="eternitech-lp--portfolio-title">BookFace</h5>
                            <div class="eternitech-lp--portfolio-skill">
                                <p>Skills:</p>
                                <span>Wordpress</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="card-img-wrap">
                                <img src="{{ asset('/lp/img/section-8-img-6.png') }}" alt=""/>
                            </div>
                            <h5 class="eternitech-lp--portfolio-title">BornSkier</h5>
                            <div class="eternitech-lp--portfolio-skill">
                                <p>Skills:</p>
                                <span>Wordpress</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="eternitech-lp--portfolio-buttons" data-aos="fade-up">
                <a class="eternitech-lp--portfolio-btn-1 popup-form-btn btn" role="button"
                >GET YOUR {{$skills}} DEVELOPER <span class="fa fa-angle-right"></span
                    ></a>
                <a href="{{\App\Helpers\ShortLink::getWebsiteLeadUrl($projectID,'https://eternitech.com/portfolio/')}}"
                   target="_blank" class="eternitech-lp--portfolio-btn-2 btn" role="button"
                >SEE ALL PROJECTS <span class="fa fa-angle-right"></span
                    ></a>
            </div>
            <div class="eternitech-lp--end-cta">
                <div data-aos="fade-up">
                    <p>
                        With 100+ full-time developers, and 20,000+ specialized tech developers on demand we have the
                        resources for your project. <br/> <br/>
                        Speak with our {{$skills}} team leader for a personalized solution, save 70%, and get started in
                        3-7 days!
                    </p>
                </div>
                <div data-aos="fade-up">
                    <a
                            id="eternitech-lp--popup-button"
                            class="eternitech-lp--end-cta-btn popup-form-btn btn"
                            role="button"
                            data-toggle="modal"
                    >Let's talk <span class="fa fa-angle-right"></span
                        ></a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="eternitech-lp--footer">
    <div class="eternitech-lp--inner-div">
        <hr/>

        <div class="eternitech-lp--container container">
            <div class="row">
                <div class="col-lg-3">
                    <img src="{{ asset('/lp/img/logo.png') }}" alt=""/>
                </div>
                <div class="col-lg">
                    <h6 class="eternitech-lp--footer-heading">Quick links</h6>
                    <div class="eternitech-lp--footer-links row">
                        <div class="col-sm-6">
                            <ul>
                                <li>
                                    <a href="{{\App\Helpers\ShortLink::getWebsiteLeadUrl($projectID,'https://eternitech.com/about-us/')}}"
                                       target="_blank">About us</a></li>
                                <li>
                                    <a href="{{\App\Helpers\ShortLink::getWebsiteLeadUrl($projectID,'https://eternitech.com/press-release/')}}"
                                       target="_blank">From the press</a></li>
                                <li>
                                    <a href="{{\App\Helpers\ShortLink::getWebsiteLeadUrl($projectID,'https://eternitech.com/technologies/')}}"
                                       target="_blank">Technologies</a></li>
                            </ul>
                        </div>
                        <div class="col-sm-6">
                            <ul>
                                <li>
                                    <a href="{{\App\Helpers\ShortLink::getWebsiteLeadUrl($projectID,'https://eternitech.com/partner/')}}"
                                       target="_blank">Become a Partner</a></li>
                                <li>
                                    <a href="{{\App\Helpers\ShortLink::getWebsiteLeadUrl($projectID,'https://eternitech.com/job/')}}"
                                       target="_blank">Careers</a></li>
                                <li>
                                    <a href="{{\App\Helpers\ShortLink::getWebsiteLeadUrl($projectID,'https://eternitech.com/contact/')}}"
                                       target="_blank">Contact us</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg">
                    <h6 class="eternitech-lp--footer-heading">Contact us</h6>
                    <div class="eternitech-lp--footer-contact">
                        <p>
                            Email:
                            <a href="mailto:sales@eternitech.com">sales@eternitech.com</a>
                        </p>
                        <p>Phone: (+1) 786-5040180 | (+972) 9 374 1233</p>
                        <p>
                            Head Office Address: 3889 Pembroke Rd, Hollywood, FL, 33201, USA
                        </p>
                        <div class="eternitech-lp--footer-follow">
                            <p>Follow Us On:</p>
                            <a href="http://www.facebook.com/pages/Eternitech/115144815239123" target="_blank"
                            ><img src="{{ asset('/lp/img/footer-facebook.png') }}" alt=""
                                /></a>
                            <a href="https://mobile.twitter.com/eternitech" target="_blank"
                            ><img src="{{ asset('/lp/img/footer-twitter.png') }}" alt=""
                                /></a>
                            <a href="https://www.linkedin.com/company/eternitech/" target="_blank"
                            ><img src="{{ asset('/lp/img/footer-linkedin.png') }}" alt=""
                                /></a>
                        </div>
                        <div class="eternitech-lp--footer-follow">
                            <p>Instant Chat:</p>
                            <a href="{{\App\Helpers\ShortLink::getWhatsAppShortLink($id)}}"
                            ><img src="{{ asset('/lp/img/footer-whatsapp.png') }}" alt=""
                                /></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="eternitech-lp--footer-copyright">
            <p>
                <a href="{{\App\Helpers\ShortLink::getWebsiteLeadUrl($projectID,'https://eternitech.com/terms-of-service')}}"
                   target="_blank">Terms of Service</a> |
                <a>All Rights reserved 2022</a> |
            </p>
            <img src="{{ asset('/lp/img/logo.png') }}" alt=""/>
        </div>
    </div>
</footer>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
{{--<script--}}
{{--src="https://code.jquery.com/jquery-3.3.1.slim.min.js"--}}
{{--integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"--}}
{{--crossorigin="anonymous"--}}
{{--></script>--}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
{{--<script--}}
        {{--src="https://code.jquery.com/jquery-3.3.1.slim.min.js"--}}
        {{--integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"--}}
        {{--crossorigin="anonymous"--}}
{{--></script>--}}
<script
        src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js"
        integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
        crossorigin="anonymous"
></script>
<script
        src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"
        integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
        crossorigin="anonymous"
></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
<script src="{{ asset('/lp/script/script.js') }}"></script>
<script src="{{ asset('/lead-collect-form/form.js') }}"></script>
</body>
</html>
