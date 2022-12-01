<?php header('Access-Control-Allow-Origin: *'); ?>

<!-- Google font -->

<!--  </head>-->
<!--  <body>-->
<div id="form-loader" class="form-loader hidden"></div>
<div
        id="eternitech-lp--popup"
        class="eternitech-lp--popup modal fade"
        tabindex="-1"
>
    <div
            class="eternitech-lp--popup-inner modal-dialog modal-dialog-centered"
    >
        <div class="eternitech-lp--popup-content modal-content">
            <div class="modal-header">
                <button
                        id="popup-close"
                        type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Close"
                >
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="popup-form" action="" method="get">
                    <section id="popup-form-section-1" class="popup-form-section-1">
                        <h3>Let's Get your project moving</h3>
                        <h5>Fill in the details below and choose the way your preferred method of communication:</h5>
                        <div class="row-1 row">
                            <div class="col-sm-12">
                                <label for="fname">First Name</label>
                                <input
                                        id="client-name"
                                        type="text"
                                        name="fname"
                                        class="form-fname"
                                />
                            </div>
                        </div>
                        <div class="row-2 row row-cols-2">
                            <div class="col-sm-6">
                                <label for="email">Email address</label>
                                <input
                                        id="project-email"
                                        type="email"
                                        name="email"
                                        class="form-email"
                                />
                            </div>
                            <div class="col-sm-6">
                                <label for="number">Phone number</label>
                                <input
                                        id="project-phone"
                                        type="tel"
                                        name="number"
                                        class="form-phone"
                                />
                            </div>
                            <div id="error-msg" class="col-sm-12 error-msg text-danger"></div>
                            <div class="col-sm-6">
                                <a
                                        href="#"
                                        id="get-back-btn"
                                        class="btn get-back-btn"
                                        role="button"
                                >Give me a call</a
                                >
                            </div>
                            <div class="col-sm-6">
                                <a
                                        href="#"
                                        id="chat-sales-btn"
                                        class="btn chat-sales-btn"
                                        role="button"
                                >Chat with a Sales Rep</a
                                >
                            </div>
                        </div>
                        <div class="row-3 row">
                            <div class="col-sm-12">
                                <a
                                        href="#"
                                        id="book-meeting-btn"
                                        class="btn book-meeting-btn"
                                        role="button"
                                >Talk to Tech Team Leader</a
                                >
                            </div>
                        </div>
                    </section>
                    <section
                            id="popup-form-section-2"
                            class="popup-form-section-2 hidden"
                    >
                        <h3>
                            <b>Just before we continue... </b><br>
                            Tell us a bit about your project and how we can help:
                        </h3>
                        <textarea
                                name="message"
                                id="client-message"
                                class="client-message"
                        ></textarea>
                        <div class="row-4">
                            <a
                                    href="#"
                                    id="popup-form-back"
                                    class="btn back-btn"
                                    role="button"
                            ><i class="bi bi-arrow-left-short"></i> Back</a
                            >
                            <!-- This button will post to the default url of the form -->
                            <button
                                    type="submit"
                                    id="get-back-submit"
                                    class="popup-form-submit hidden"
                            >
                                Done, Get back to me
                            </button>
                            <!-- This button will post to the custom URL of the formaction attribute -->
                            <button
                                    type="submit"
                                    id="chat-sales-submit"
                                    class="popup-form-submit hidden"
                                    formaction=""
                            >
                                Continue to Chat
                            </button>
                            <!-- This button will post to the custom URL of the formaction attribute -->
                            <button
                                    type="submit"
                                    id="book-meeting-submit"
                                    class="popup-form-submit hidden"
                                    formaction=""
                            >
                                Continue to select time
                            </button>
                        </div>
                    </section>
                    <section
                            id="popup-form-section-3"
                            class="popup-form-section-3 hidden"
                    >
                        <h3>
                            <b>Thanks you! </b><br>
                            We will get back to you as soon as we can, typically within 2 business days.
                        </h3>
                        <div class="row-4">
                            <a
                                    href="#"
                                    id="homepage-submit"
                                    class="popup-form-submit btn back-btn"
                                    role="button"
                            >Go To Homepage</a>
                        </div>
                    </section>
                </form>
                <p>Eternitech.com © 2022</p>
            </div>
        </div>
    </div>
</div>
<!---->
<!--    <script-->
<!--      src="https://code.jquery.com/jquery-3.3.1.slim.min.js"-->
<!--      integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"-->
<!--      crossorigin="anonymous"-->
<!--    ></script>-->
<!--    <script-->
<!--      src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js"-->
<!--      integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"-->
<!--      crossorigin="anonymous"-->
<!--    ></script>-->
<!--    <script-->
<!--      src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"-->
<!--      integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"-->
<!--      crossorigin="anonymous"-->
<!--    ></script>-->
