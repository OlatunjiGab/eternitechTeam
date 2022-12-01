<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="format-detection" content="telephone=no" />
    <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height, target-densitydpi=device-dpi" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta http-equiv="Cache-Control" content="max-age=36000" />
    <link rel="shortcut icon" href="favicon.ico"/>
    <title>Eternitech</title>

    <link rel="stylesheet" href="{{ asset('/la-assets/web-phone/css/softphone/index.css')}}" />
    <!-- Themes -->
    <link rel="stylesheet" href="{{ asset('/la-assets/web-phone/css/softphone/themes/wphone_1.0.css') }}" /><!-- instead of: <link rel="stylesheet" href="css/jquery.mobile-1.4.2.min.css" />-->
    <link rel="stylesheet" href="{{ asset('/la-assets/web-phone/css/softphone/themes/jquery.mobile.icons.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('/la-assets/web-phone/css/softphone/jquery.mobile.structure-1.4.2.css') }}" />
    <link rel="stylesheet" href="{{ asset('/la-assets/web-phone/css/softphone/mainlayout.css') }}" />

    <script>window.pageissdk = false;</script>
    <script src="{{ asset('/la-assets/web-phone/webphone_api.js?jscodeversion=138') }}"></script>


</head>
<body spellcheck="false">
<div id="loading_bg_txt">Loading App...</div>
<div class="app" id="phone_app_main_container">
    <div id="js_not_enabled">Enable JavaScript or click the &quot;Allow&quot; button in your browser!!!</div>
    <script type="text/javascript">document.getElementById('js_not_enabled').style.display = 'none'; try{ document.getElementById('phone_app_main_container').style.display = 'none'; } catch(err) {  }</script>

    <!-- Settings Page -->
    <div id="page_settings" class="all_app_pages" data-role="page">
        <div id="settings_header" data-role="header" data-theme="b">
            <div id="settings_additional_header">
                <div class="additional_header_left">
                    <button id="btn_back_settings" class="btn_header_back ui-btn"><b>&LT;</b>&nbsp;_Settings</button><span id="app_name_settings">&nbsp;</span>
                </div>
                <div id="settings_page_title" class="additional_header_middle">Settings</div>
                <div class="additional_header_right">
                    <a href="#settings_menu" id="btn_settings_menu" data-rel="popup" class="btn_toolbar_menu ui-btn ui-icon-bars ui-btn-icon-notext" data-transition="slidedown">Menu</a>
                    <div id="settings_menu" class="options_menu" data-role="popup">
                        <ul id="settings_menu_ul" data-role="listview" data-inset="true" data-icon="false">
                            <!--<li data-role="divider" data-theme="b">Choose an action</li>
                            <li id="test_id"><a href="javascript:;" id="menuitem_test">View details</a></li>-->
                        </ul>
                    </div>
                </div>
                <div class="separator_line_thick"><!--//--></div>
                <div class="extra_header" id="extra_header_settings"></div>
            </div>
        </div>

        <div id="page_settings_content" role="main" class="ui-content" data-theme="b" data-content-theme="b">
            <ul id="settings_list" class="mlistview" data-role="listview" data-theme="b" data-divider-theme="b" data-inset="true" data-filter="true" data-filter-placeholder="Search Settings">
            </ul>
            <div id="loginpage_container" class="ui-field-contain">
                <div id="loginpage">
                    <div id="logologinpage">
                        <!-- <img id="logo" src="images/logo.png') }}" style="border: 0;">&nbsp;&nbsp;<div></div> -->
                    </div>
                    <input name="lp_serveraddress" id="lp_serveraddress" placeholder="Server" value="" type="text" autocapitalize="off">
                    <input name="lp_username" id="lp_username" placeholder="Username" value="" type="text" autocapitalize="off">
                    <input name="lp_password" id="lp_password" placeholder="Password" value="" type="text" autocapitalize="off">
                    <button id="lp_btn_login" class="ui-btn ui-btn-corner-all ui-btn-b noshadow">Login</button>
                    <a href="javascript:;" id="a_newuser" target="_blank">Sign up</a><br />
                    <a href="javascript:;" id="a_forgotpassword" target="_blank">Forgot password</a>
                    <button id="lp_btn_custom" class="ui-btn ui-btn-corner-all ui-btn-b noshadow" style="display:none;">_Custom</button>
                </div>
            </div>
            <div id="settings_copyright" style="display: none;">
                <a href="https://www.mizu-voip.com/Software/WebPhone.aspx" target="_blank" title="Provider">Powered by Mizutech WebPhone &reg;</a>
            </div>
            <div id="settings_engine" style="display: none;">
                <a href="javascript:;" target="_blank" id="btn_settings_engine" class="ui-btn noshadow btn_engine" ><span id="settings_engine_title" class="engine_title">Choose engine</span><br /><span id="settings_engine_msg" class="engine_msg"></span></a>
                <a href="javascript:;" id="btn_settings_engine_close" class="ui-btn ui-btn-inline ui-icon-delete ui-btn-icon-notext"></a>
            </div>
        </div>
    </div>
    <!-- END Settings Page -->

    <!-- Dialpad Page -->
    <div id="page_dialpad" class="all_app_pages" data-role="page" data-theme="b">
        <!--Notification panel-->
        <div data-role="panel" id="dialpad_not" class="notification_panel" data-position="left" data-display="overlay" data-theme="b">
            <div class="not_panel_title">_Notifications</div>
            <ul class="notification_list" id="dialpad_notification_list" data-role="listview" data-inset="true" data-icon="false" data-theme="b">
                <!--<li data-role="divider" data-theme="b">Notifications</li>-->
                <!--<li>
                    <a id="dialpad_notitem_[NOTID]" class="nt_anchor">
                        <div class="nt_title">Missed call from:</div>
                        <div class="nt_desc">1234335358</div>
                        <div class="nt_date">Aug, 26 10:55</div>
                    </a>
                    <a id="dialpad_notmenu_[NOTID]" class="ui-btn ui-btn-inline ui-icon-delete nt_menu">hint_clear</a>
                </li>-->
            </ul>
            <a href="javascript:;" data-rel="close" class="ui-btn ui-mini ui-shadow ui-corner-all ui-btn-b ui-btn-inline not_close_btn">_Close</a>
        </div>
        <!--End notification panel-->
        <div id="dialpad_header" data-role="header">
            <div id="dialpad_engine" style="display: none;">
                <a href="javascript:;" target="_blank" id="btn_dialpad_engine" class="ui-btn noshadow btn_engine" ><span id="dialpad_engine_title" class="engine_title">Choose engine</span><br /><span id="dialpad_engine_msg" class="engine_msg"></span></a>
                <a href="javascript:;" id="btn_dialpad_engine_close" class="ui-btn ui-btn-inline ui-icon-delete ui-btn-icon-notext"></a>
            </div>
            <div id="dialpad_additional_header">
                <div id="headertext_settings" style="display: none;" class="header_text"></div>
                <div class="additional_header_left" id="dialpad_additional_header_left">
                    <div id="dialpad_presence"></div><div><span id="app_name_dialpad"></span></div>
                </div>
                <div id="dialpad_title" class="additional_header_middle">Phone</div><!--<div class="notification" id="dialpad_notification"></div>-->
                <div class="additional_header_right" id="dialpad_additional_header_right">
                    <span id="dialpad_not_counter" class="ui-li-count ui-btn-corner-all not_btn_counter" style="display: none;"></span>
                    <a href="#dialpad_not" id="dialpad_not_btn" class="ui-btn ui-shadow ui-btn-inline ui-mini ui-icon-info ui-btn-icon-notext not_header_btn" style="display: none;">Clear</a>
                    <a href="#dialpad_menu" id="btn_dialpad_menu" data-rel="popup" class="btn_toolbar_menu ui-btn ui-icon-bars ui-btn-icon-notext" data-transition="slidedown">Menu</a>

                    <div id="dialpad_menu" class="options_menu" data-role="popup">
                        <ul id="dialpad_menu_ul" data-role="listview" data-inset="true" data-icon="false" data-theme="a">
                        </ul>
                    </div>
                </div>
                <div class="separator_line_thick"><!--//--></div>
            </div>
            <div class="status_container">
                <img src="{{ asset('/la-assets/web-phone/images/icon_encrypt.png') }}" class="img_encrypt" title="Encrypted"><div class="status" id="status_dialpad">&nbsp;</div>
                <div class="curr_user" id="curr_user_dialpad">&nbsp;</div>
            </div>
            <div class="clear_float"><!--//--></div>
            <div class="navigation_bar" data-role="navbar" data-iconpos="top">
                <ul id="ul_nav_dp">
                    <li><a id="nav_dp_dialpad" data-transition="slide" data-icon="" class="ui-btn-active ui-state-persist"><img src="{{ asset('/la-assets/web-phone/images/tab_dialpad.png') }}" alt="dialpad" /></a></li>
                    <li id="li_nav_dp_ct"><a id="nav_dp_contacts" data-transition="slide" data-icon=""><img src="{{ asset('/la-assets/web-phone/images/tab_contacts.png') }}" alt="contacts" /></a></li>
                    <li id="li_nav_dp_ch"><a id="nav_dp_callhistory" data-transition="slide" data-icon=""><img src="{{ asset('/la-assets/web-phone/images/tab_callog.png') }}" alt="call history" /></a></li>
                </ul>
            </div>
            <div class="extra_header" id="extra_header_dialpad"></div>
        </div>
        <div id="page_dialpad_content" role="main" class="ui-content">
            <div class="ui-grid-a" id="phone_number_container">
                <div class="ui-block-a"><input name="phonenumber" id="phone_number" placeholder="Phone Nr or SIP uri_" value="" type="text" class="ui-input-text" autocapitalize="off" /></div>
                <div class="ui-block-b"><button id="btn_backspace" class="ui-btn-a noshadow" ><img src="{{ asset('/la-assets/web-phone/images/btn_backspace_txt.png') }}" id="btn_backspace_img" alt="backspace" /></button></div>
                <div id="disprate_container" style="display: none;">&nbsp;</div>
            </div>

            <ul id="dialpad_list" class="mlistview" data-role="listview" data-split-icon="bars" data-split-theme="b" data-theme="b" data-divider-theme="b" data-inset="true">
                <!--            <li id="ch_item_0" data-theme="b"><a class="ch_anchor">
                            <div class="item_container">
                                    <div class="ch_type">
                                        <img src="{{ asset('/la-assets/web-phone/images/icon_call_outgoing.png') }}" />
                                    </div>
                                    <div class="ch_data">
                                    <div class="ch_name">John Smith</div>
                                        <div class="ch_number">40123456789</div>
                                    </div>
                                    <div class="ch_date">Aug, 26 2013 10:55</div>
                        </div>
                            </a>
                            </li>-->
            </ul>

            <div class="clear_float"><!--//--></div>
            <div class="separator_color_bg"><!--//--></div>


            <div class="ui-grid-b" id="dialpad_btn_grid">
                <div class="ui-block-a"><button id="btn_dp_1" class="ui-btn noshadow" ><span class="number">1</span><span class="smalltext">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></button></div>
                <div class="ui-block-b"><button id="btn_dp_2" class="ui-btn noshadow" ><span class="number">2</span><span class="smalltext">ABC</span></button></div>
                <div class="ui-block-c"><button id="btn_dp_3" class="ui-btn noshadow" ><span class="number">3</span><span class="smalltext">DEF&nbsp;&nbsp;&nbsp;&nbsp;</span></button></div>

                <div class="ui-block-a"><button id="btn_dp_4" class="ui-btn noshadow" ><span class="number">4</span><span class="smalltext">GHI&nbsp;&nbsp;&nbsp;&nbsp;</span></button></div>
                <div class="ui-block-b"><button id="btn_dp_5" class="ui-btn noshadow" ><span class="number">5</span><span class="smalltext">JKL&nbsp;</span></button></div>
                <div class="ui-block-c"><button id="btn_dp_6" class="ui-btn noshadow" ><span class="number">6</span><span class="smalltext">MNO&nbsp;&nbsp;</span></button></div>

                <div class="ui-block-a"><button id="btn_dp_7" class="ui-btn noshadow" ><span class="number">7</span><span class="smalltext">PQRS</span></button></div>
                <div class="ui-block-b"><button id="btn_dp_8" class="ui-btn noshadow" ><span class="number">8</span><span class="smalltext">TUV</span></button></div>
                <div class="ui-block-c"><button id="btn_dp_9" class="ui-btn noshadow" ><span class="number">9</span><span class="smalltext">WXYZ</span></button></div>

                <div class="ui-block-a"><button id="btn_dp_ast" class="ui-btn noshadow" ><span id="dialpad_asterisk" class="number">&lowast;</span><span class="smalltext">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></button></div>
                <div class="ui-block-b"><button id="btn_dp_0" class="ui-btn noshadow" ><span class="number">0</span><span class="smalltext">+&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></button></div>
                <div class="ui-block-c"><button id="btn_dp_diez" class="ui-btn noshadow" ><span class="number">#</span><span class="smalltext">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></button></div>

                <div class="separator_color_bg"><!--//--></div>
            </div>
        </div>
        <!--<div data-role="footer" data-position="fixed" data-tap-toggle="false" id="dialpad_footer">-->
        <div data-role="footer" data-tap-toggle="false" id="dialpad_footer">
            <div class="ui-grid-b" id="dialpad_call_grid">
                <!--<div class="ui-block-a" id="btn_voicemail"><button class="ui-btn noshadow"><img src="{{ asset('/la-assets/web-phone/images/btn_voicemail_txt.png') }}" alt="voicemail" /></button></div>-->
                <div class="ui-block-a" id="btn_showhide_numpad">
                    <button class="ui-btn noshadow"><img src="{{ asset('/la-assets/web-phone/images/btn_numpad_txt.png') }}" id="btn_showhide_numpad_img" alt="numpad" /></button>
                    <span id="dialpad_voicemail_counter" class="ui-li-count ui-btn-corner-all" style="display: none;"></span>
                </div>
                <div class="ui-block-b" id="btn_call"><button class="ui-btn noshadow ui-btn-c"><img src="{{ asset('/la-assets/web-phone/images/btn_call_txt.png') }}" id="dp_btn_call_txt" alt="call" /></button></div>
                <div class="ui-block-c" id="btn_message">
                    <button class="ui-btn noshadow"><img src="{{ asset('/la-assets/web-phone/images/icon_message.png') }}" id="btn_message_img" alt="message" /></button>
                    <span id="dialpad_message_counter" class="ui-li-count ui-btn-corner-all" style="display: none;"></span>
                </div>
            </div>

            <div id="footertext_dialpad" style="display: none;" class="header_text"></div>
            <div id="advert_dialpad" class="advert_container">
                <iframe frameborder="0" width="100%" scrolling="no" src="" name="advert_frame" id="advert_dialpad_frame"></iframe>
            </div>
        </div>
    </div>
    <!-- END Dialpad Page -->


    <!-- ContactsList Page -->
    <div id="page_contactslist" class="all_app_pages" data-role="page">
        <!--Notification panel-->
        <div data-role="panel" id="contactslist_not" class="notification_panel" data-position="left" data-display="overlay" data-theme="b">
            <div class="not_panel_title">_Notifications</div>
            <ul class="notification_list" id="contactslist_notification_list" data-role="listview" data-inset="true" data-icon="false" data-theme="b">
            </ul>
            <a href="javascript:;" data-rel="close" class="ui-btn ui-mini ui-shadow ui-corner-all ui-btn-b ui-btn-inline not_close_btn">_Close</a>
        </div>
        <!--End notification panel-->
        <div id="contactslist_header" data-role="header" data-theme="b">
            <div id="contactslist_additional_header">
                <div id="headertext_contactslist" style="display: none;" class="header_text"></div>
                <div class="additional_header_left" id="contactslist_additional_header_left">
                    <div id="contactslist_presence"></div><div><span id="app_name_contactslist"></span></div>
                </div>
                <div id="ctlist_title" class="additional_header_middle">Contacts</div><!--<div class="notification" id="contactslist_notification"></div>-->
                <div class="additional_header_right" id="contactslist_additional_header_right">
                    <span id="contactslist_not_counter" class="ui-li-count ui-btn-corner-all not_btn_counter" style="display: none;"></span>
                    <a href="#contactslist_not" id="contactslist_not_btn" class="ui-btn ui-shadow ui-btn-inline ui-mini ui-icon-info ui-btn-icon-notext not_header_btn" style="display: none;">Clear</a>
                    <a href="#contactslist_menu" id="btn_contactslist_menu" data-rel="popup" class="btn_toolbar_menu ui-btn ui-icon-bars ui-btn-icon-notext" data-transition="slidedown">Menu</a>

                    <div id="contactslist_menu" class="options_menu" data-role="popup">
                        <ul id="contactslist_menu_ul" data-role="listview" data-inset="true" data-icon="false" data-theme="a">
                        </ul>
                    </div>
                </div>
                <div class="separator_line_thick"><!--//--></div>
            </div>
            <div class="status_container">
                <img src="{{ asset('/la-assets/web-phone/images/icon_encrypt.png') }}" class="img_encrypt" title="Encrypted"><div class="status" id="status_contactslist">&nbsp;</div>
                <div class="curr_user" id="curr_user_contactslist">&nbsp;</div>
            </div>
            <div class="navigation_bar" data-role="navbar" data-iconpos="top">
                <ul id="ul_nav_ct">
                    <li><a id="nav_ct_dialpad" data-transition="slide" data-direction="reverse" data-icon=""><img src="{{ asset('/la-assets/web-phone/images/tab_dialpad.png') }}" alt="dialpad" /></a></li>
                    <li><a id="nav_ct_contacts" data-transition="slide" data-icon="" class="ui-btn-active ui-state-persist"><img src="{{ asset('/la-assets/web-phone/images/tab_contacts.png') }}" alt="contacts" /></a></li>
                    <li id="li_nav_ct_ch"><a id="nav_ct_callhistory" data-transition="slide" data-icon=""><img src="{{ asset('/la-assets/web-phone/images/tab_callog.png') }}" alt="call history" /></a></li>
                </ul>
            </div>
            <div class="extra_header" id="extra_header_contactslist"></div>
        </div>
        <div role="main" class="ui-content" id="page_contactslist_content" data-theme="b" data-content-theme="b">
            <div id="togglecontact_container" style="display: none;">
                <select id="togglecontact" name="togglecontact" data-role="flipswitch">
                    <option value="off">Server contacts</option>
                    <option value="on">All contacts</option>
                </select>
            </div>
            <ul id="contactslist_list"  class="mlistview" data-role="listview" data-theme="b" data-divider-theme="b" data-inset="true" data-filter="true" data-filter-placeholder="Search Contacts" data-icon="false" data-autodividers="true">
                <!--<li id="contact_0"><a href="javascript:void(0)" data-transition="slide">John Smith</a></li>-->
            </ul>
        </div>
        <div id="footertext_contactslist" style="display: none;" class="header_text"></div>
        <div id="advert_contactslist" class="advert_container">
            <iframe frameborder="0" width="100%" scrolling="no" src="" name="advert_frame" id="advert_contactslist_frame"></iframe>
        </div>
    </div>
    <!-- END ContactsList Page -->


    <!-- ContactDetails Page -->
    <div id="page_contactdetails" class="all_app_pages" data-role="page">
        <div id="contactdetails_header" data-role="header" data-theme="b">
            <div id="contactdetails_additional_header">
                <div class="additional_header_left">
                    <a id="ctdetails_btnback" class="btn_header_back ui-btn" data-rel="back"><b>&LT;</b>&nbsp;All Contacts</a>
                </div>
                <div id="contactdetails_title" class="additional_header_middle">Info</div>
                <div class="additional_header_right">
                    <a href="#contactdetails_menu" id="btn_contactdetails_menu" data-rel="popup" class="btn_toolbar_menu ui-btn ui-icon-bars ui-btn-icon-notext" data-transition="slidedown">Menu</a>

                    <div id="contactdetails_menu" class="options_menu" data-role="popup">
                        <ul id="contactdetails_menu_ul" data-role="listview" data-inset="true" data-icon="false" data-theme="a">
                        </ul>
                    </div>
                </div>
                <div class="separator_line_thick"><!--//--></div>
            </div>
        </div>
        <div id="page_contactdetails_content" role="main" class="ui-content"  data-theme="b" data-content-theme="b">

            <!--<div id="contact_name"><p>John Smith</p><div id="contact_favorite"><img id="btn_contactdetails_favorite" src="images/btn_star_off_normal_holo_light.png') }}" /></div></div>
            <div id="ct_entry_0" class="cd_container">
                <div id="cd_call_1" class="cd_call">
                    <div class="cd_data">
                        <div class="cd_type">Call home</div>
                        <div class="cd_number">40123456789</div>
                    </div>
                    <div class="cd_icon">
                        <img src="{{ asset('/la-assets/web-phone/images/icon_call.png') }}" />
                    </div>
                </div>
            </div>
            <div id="ct_entry_1" class="cd_container">
                <div id="cd_msg_1" class="cd_call">
                    <div class="cd_data">
                        <div class="cd_type">Send message</div>
                        <div class="cd_number">40123456789</div>
                    </div>
                    <div class="cd_icon">
                        <img src="{{ asset('/la-assets/web-phone/images/icon_message.png') }}" />
                    </div>
                </div>
            </div>
            <div id="ct_edit_entry" class="cd_container">
                <div id="ct_edit_entry_button" class="cd_call">
                    <div class="cd_button">Edit contact</div>
                </div>
            </div>
            <div id="ct_delete_entry" class="cd_container">
                <div id="ct_delete_entry_button" class="cd_call">
                    <div class="cd_button">Delete contact</div>
                </div>
            </div>
            <div id="ct_allcontacts_entry" class="cd_container">
                <div id="ct_allcontacts_entry_button" class="cd_call">
                    <div class="cd_button">All contacts</div>
                </div>
            </div>-->
        </div>
    </div>
    <!-- END ContactDetails Page -->


    <!-- CallHistoryList Page -->
    <div id="page_callhistorylist" class="all_app_pages" data-role="page">
        <!--Notification panel-->
        <div data-role="panel" id="callhistorylist_not" class="notification_panel" data-position="left" data-display="overlay" data-theme="b">
            <div class="not_panel_title">_Notifications</div>
            <ul class="notification_list" id="callhistorylist_notification_list" data-role="listview" data-inset="true" data-icon="false" data-theme="b">
            </ul>
            <a href="javascript:;" data-rel="close" class="ui-btn ui-mini ui-shadow ui-corner-all ui-btn-b ui-btn-inline not_close_btn">_Close</a>
        </div>
        <!--End notification panel-->
        <div id="callhistorylist_header" data-role="header" data-theme="b">
            <div id="callhistorylist_additional_header">
                <div id="headertext_callhistorylist" style="display: none;" class="header_text"></div>
                <div class="additional_header_left" id="callhistorylist_additional_header_left">
                    <div id="callhistorylist_presence"></div><div><span id="app_name_callhistorylist"></span></div>
                </div>
                <div id="chlist_title" class="additional_header_middle">Call history</div><!--<div class="notification" id="callhistorylist_notification"></div>-->
                <div class="additional_header_right" id="callhistorylist_additional_header_right">
                    <span id="callhistorylist_not_counter" class="ui-li-count ui-btn-corner-all not_btn_counter" style="display: none;"></span>
                    <a href="#callhistorylist_not" id="callhistorylist_not_btn" class="ui-btn ui-shadow ui-btn-inline ui-mini ui-icon-info ui-btn-icon-notext not_header_btn" style="display: none;">Clear</a>
                    <a href="#callhistorylist_menu" id="btn_callhistorylist_menu" data-rel="popup" class="btn_toolbar_menu ui-btn ui-icon-bars ui-btn-icon-notext" data-transition="slidedown">Menu</a>

                    <div id="callhistorylist_menu" class="options_menu" data-role="popup">
                        <ul id="callhistorylist_menu_ul" data-role="listview" data-inset="true" data-icon="false" data-theme="a">
                        </ul>
                    </div>
                </div>
                <div class="separator_line_thick"><!--//--></div>
            </div>
            <div class="status_container">
                <img src="{{ asset('/la-assets/web-phone/images/icon_encrypt.png') }}" class="img_encrypt" title="Encrypted"><div class="status" id="status_callhistorylist">&nbsp;</div>
                <div class="curr_user" id="curr_user_callhistorylist">&nbsp;</div>
            </div>
            <div class="navigation_bar" data-role="navbar" data-iconpos="top">
                <ul id="ul_nav_ch">
                    <li><a id="nav_ch_dialpad" data-transition="slide" data-direction="reverse" data-icon=""><img src="{{ asset('/la-assets/web-phone/images/tab_dialpad.png') }}" alt="dialpad" /></a></li>
                    <li id="li_nav_ch_ct"><a id="nav_ch_contacts" data-transition="slide" data-direction="reverse" data-icon=""><img src="{{ asset('/la-assets/web-phone/images/tab_contacts.png') }}" alt="contacts" /></a></li>
                    <li><a id="nav_ch_callhistory" data-transition="slide" data-icon="" class="ui-btn-active ui-state-persist"><img src="{{ asset('/la-assets/web-phone/images/tab_callog.png') }}" alt="call history" /></a></li>
                </ul>
            </div>
            <div class="extra_header" id="extra_header_callhistorylist"></div>
        </div>
        <div role="main" class="ui-content" id="page_callhistorylist_content" data-theme="b" data-content-theme="b">
            <ul id="callhistorylist_list" class="mlistview" data-role="listview" data-theme="b" data-divider-theme="b" data-inset="true" data-icon="false">
                <!--<li id="ch_item_0" data-theme="b"><a href="javascript:void(0)" class="ch_anchor" data-transition="slide">
                <div class="item_container">
                        <div class="ch_type">
                            <img src="{{ asset('/la-assets/web-phone/images/icon_call_outgoing.png') }}" />
                        </div>
                        <div class="ch_data">
                        <div class="ch_name">John Smith</div>
                            <div class="ch_number">40123456789</div>
                            <div class="ch_duration">Duration: 02:45</div>
                        </div>
                        <div class="ch_date">Aug, 26 2013 10:55</div>
            </div>
                </a></li>-->
            </ul>
        </div>
        <div id="footertext_callhistorylist" style="display: none;" class="header_text"></div>
        <div id="advert_callhistorylist" class="advert_container">
            <iframe frameborder="0" width="100%" scrolling="no" src="" name="advert_frame" id="advert_callhistorylist_frame"></iframe>
        </div>
    </div>
    <!-- END CallHistoryList Page -->


    <!-- CallhistoryDetails Page -->
    <div id="page_callhistorydetails" class="all_app_pages" data-role="page">
        <div id="callhistorydetails_header" data-role="header" data-theme="b">
            <div id="callhistorydetails_additional_header">
                <div class="additional_header_left">
                    <a id="chdetails_btnback" class="btn_header_back ui-btn" data-rel="back"><b>&LT;</b>&nbsp;Call history</a>
                </div>
                <div id="callhistorydetails_title" class="additional_header_middle">Info</div>
                <div class="additional_header_right">
                    <a href="#callhistorydetails_menu" id="btn_callhistorydetails_menu" data-rel="popup" class="btn_toolbar_menu ui-btn ui-icon-bars ui-btn-icon-notext" data-transition="slidedown">Menu</a>

                    <div id="callhistorydetails_menu" class="options_menu" data-role="popup">
                        <ul id="callhistorydetails_menu_ul" data-role="listview" data-inset="true" data-icon="false" data-theme="a">
                        </ul>
                    </div>
                </div>
                <div class="separator_line_thick"><!--//--></div>
            </div>
        </div>
        <div id="page_callhistorydetails_content" role="main" class="ui-content" data-theme="b" data-content-theme="b">
            <div id="ch_contact_name">John Smith</div>

            <div id="ch_entry" class="ch_container">
                <div id="ch_call_entry" class="ch_call">
                    <div class="ch_data">
                        <div class="ch_type">Outgoing call</div>
                        <div class="ch_number">40123456789</div>
                    </div>
                    <div class="ch_call_icon">
                        <img src="{{ asset('/la-assets/web-phone/images/icon_call.png') }}" alt="call" />
                    </div>
                </div>
                <div id="ch_msg_entry" class="ch_msg">
                    <div class="ch_msg_icon">
                        <img src="{{ asset('/la-assets/web-phone/images/icon_message.png') }}" alt="message" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END CallhistoryDetails Page -->


    <!-- Call Page -->
    <div id="page_call" class="all_app_pages" data-role="page" data-theme="b">
        <div id="call_header" data-role="header">
            <div id="call_additional_header">
                <div class="additional_header_left">
                    <span id="app_name_call"></span>
                </div>
                <div class="additional_header_right">
                    <a href="#call_menu" id="btn_call_menu" data-rel="popup" class="btn_toolbar_menu ui-btn ui-icon-bars ui-btn-icon-notext" data-inline="true" data-transition="slidedown">Menu</a>

                    <div id="call_menu" class="options_menu" data-role="popup" data-theme="a">
                        <ul data-role="listview" id="call_menu_ul" data-inset="true" data-theme="a" data-icon="false">
                        </ul>
                    </div>
                </div>
                <div class="separator_line_thick"><!--//--></div>
            </div>
            <div class="status_container">
                <img src="{{ asset('/la-assets/web-phone/images/icon_encrypt.png') }}" class="img_encrypt" title="Encrypted"><div id="calledcaller">&nbsp;</div>
                <div id="call_duration">&nbsp;</div>
                <div id="status_call">&nbsp;</div>
            </div>
            <div class="clear_float"><!--//--></div>
        </div>
        <div id="page_call_content" role="main" class="ui-content">
            <div id="mlcontainer" style="display: none;">
                <div class="separator_line_thick"><!--//--></div>
                <div id="ml_buttons"><!--
                <button class="ui-btn line_btn noshadow" data-theme="b" id="btn_line_1">
                    <span class="line_text">Line 1</span>
                    <span class="line_status line_status_on" id="line_1_status" >&nbsp;</span>
                </button><button class="ui-btn line_btn noshadow" data-theme="b" id="btn_line_2">
                    <span class="line_text">Line 2</span>
                    <span class="line_status" id="line_2_status" >&nbsp;</span>
                </button>-->
                </div>
            </div>

            <div id="audiodevice_container"  style="display: none;"><button id="btn_audiodevice" class="ui-btn noshadow ui-btn-inline ui-corner-all">Audio devices</button></div>
            <div id="volumecontrols" style="display: none;">
                <div class="volume_labels">Volume in:</div>
                <div class="volume_sliders"><input name="volumein" id="volumein" data-mini="true" min="0" max="100" value="50" type="range"></div>
                <div style="float: left; width: 90%; clear: both;"><!--//--></div>
                <div class="volume_labels">Volume out:</div>
                <div class="volume_sliders"><input name="volumeout" id="volumeout" data-mini="true" min="0" max="100" value="50" type="range"></div>
            </div>
            <div id="contact_image">
                <div id="contact_details">
                    <img src="{{ asset('/la-assets/web-phone/images/default_contact_img.png') }}" id="contact_image_img" alt="contact image" />
                    <div id="page_call_additional_info"></div>
                    <div id="page_call_peer_details"></div>
                    <div id="display_notmain_account" style="display: none;"></div>
                </div>
                <div id="video_container" style="display: none;">
                    <!--
                                <div id="video_container">
                                    <div id="div_video">
                                        <div id="div_video_remote">
                                            <video class="video" width="100%" height="100%" id="video_remote" autoplay="autoplay"></video>
                                        </div>
                                        <div id="div_video_local_wrapper">
                                            <iframe allow="microphone; camera" class="previewvideo"> </iframe>
                                            <div id="div_video_local" class="previewvideo">
                                                <video class="video" width="100%" height="100%" id="video_local" autoplay="autoplay" muted="true"></video>
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                    -->
                </div>
            </div>

            <div class="clear_float"><!--//--></div>

            <div id="numpad">
                <div id="numpad_number_container">
                    <span id="numpad_number"></span>
                </div>

                <div class="separator_color_bg"><!--//--></div>

                <div class="ui-grid-b" id="numpad_btn_grid">
                    <div class="ui-block-a"><button id="numpad_btn_dp_1" class="ui-btn noshadow" ><span class="number">1</span><span class="smalltext">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></button></div>
                    <div class="ui-block-b"><button id="numpad_btn_dp_2" class="ui-btn noshadow" ><span class="number">2</span><span class="smalltext">ABC</span></button></div>
                    <div class="ui-block-c"><button id="numpad_btn_dp_3" class="ui-btn noshadow" ><span class="number">3</span><span class="smalltext">DEF&nbsp;&nbsp;&nbsp;&nbsp;</span></button></div>

                    <div class="ui-block-a"><button id="numpad_btn_dp_4" class="ui-btn noshadow" ><span class="number">4</span><span class="smalltext">GHI&nbsp;&nbsp;&nbsp;&nbsp;</span></button></div>
                    <div class="ui-block-b"><button id="numpad_btn_dp_5" class="ui-btn noshadow" ><span class="number">5</span><span class="smalltext">JKL&nbsp;</span></button></div>
                    <div class="ui-block-c"><button id="numpad_btn_dp_6" class="ui-btn noshadow" ><span class="number">6</span><span class="smalltext">MNO&nbsp;&nbsp;</span></button></div>

                    <div class="ui-block-a"><button id="numpad_btn_dp_7" class="ui-btn noshadow" ><span class="number">7</span><span class="smalltext">PQRS</span></button></div>
                    <div class="ui-block-b"><button id="numpad_btn_dp_8" class="ui-btn noshadow" ><span class="number">8</span><span class="smalltext">TUV</span></button></div>
                    <div class="ui-block-c"><button id="numpad_btn_dp_9" class="ui-btn noshadow" ><span class="number">9</span><span class="smalltext">WXYZ</span></button></div>

                    <div class="ui-block-a"><button id="numpad_btn_dp_ast" class="ui-btn noshadow" ><span class="number">&lowast;</span><span class="smalltext">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></button></div>
                    <div class="ui-block-b"><button id="numpad_btn_dp_0" class="ui-btn noshadow" ><span class="number">0</span><span class="smalltext">+&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></button></div>
                    <div class="ui-block-c"><button id="numpad_btn_dp_diez" class="ui-btn noshadow" ><span class="number">#</span><span class="smalltext">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></button></div>
                </div>
                <div class="separator_color_bg"><!--//--></div>
            </div>

            <div class="clear_float"><!--//--></div>
            <div class="ui-grid-b" id="acceptreject_layout">
                <div class="ui-block-a"><button data-theme="b" id="btn_accept" class="noshadow"><img src="{{ asset('/la-assets/web-phone/images/btn_call_txt.png') }}" alt="accept" /></button></div>
                <!--<div class="ui-block-b"><button data-theme="b" class="noshadow" onClick="Mizulayout.Ignore();"><img src="{{ asset('/la-assets/web-phone/images/btn_ignore_txt.png') }}" /></button></div>-->
                <div class="ui-block-c"><button data-theme="b" id="btn_reject" class="noshadow"><img src="{{ asset('/la-assets/web-phone/images/btn_hangup_txt.png') }}" alt="reject" /></button></div>
            </div>
            <div class="ui-grid-b" id="mline_layout">
                <div class="ui-block-a"><button data-theme="b" id="btn_ml_accept" class="noshadow"><img src="{{ asset('/la-assets/web-phone/images/btn_call_txt.png') }}" alt="accept" /></button></div>
                <div class="ui-block-b"><button data-theme="b" id="btn_ml_reject" class="noshadow"><img src="{{ asset('/la-assets/web-phone/images/btn_hangup_txt.png') }}" alt="hangup" /></button></div>
                <div class="ui-block-c"><button data-theme="b" id="btn_ml_more" class="noshadow"><img src="{{ asset('/la-assets/web-phone/images/btn_menu_txt.png') }}" alt="options" /></button></div>
            </div>

            <div class="clear_float"><!--//--></div>

            <div id="hangup_layout">
                <button data-theme="d" class="noshadow" id="btn_hangup"><img id="btn_hangup_img" src="{{ asset('/la-assets/web-phone/images/btn_hangup_txt.png') }}" alt="hangup" /></button>
            </div>

            <div class="separator_color_bg"><!--//--></div>

            <!--<div id="callfunctions_layout">
                <div class="callfunc_btn_container">
                    <button class="ui-btn callfunc_btn noshadow" data-theme="b" id="btn_[REPLACESTR]">
                        <img src="{{ asset('/la-assets/web-phone/images/btn_[REPLACESTR]_txt.png') }}" />
                        <span class="callfunc_status" id="[REPLACESTR]_status" >&nbsp;</span>
                    </button>
                </div>
            </div>-->
            <div id="callfunctions_layout"></div>
        </div>
    </div>
    <!-- END Call Page -->

    <!-- AddEditContact Page -->
    <div id="page_addeditcontact" class="all_app_pages" data-role="page">
        <div id="addeditcontact_header" data-role="header" data-theme="b">
            <div id="addeditcontact_additional_header">
                <div class="additional_header_left">
                    <a id="addeditct_btnback" class="btn_header_back ui-btn" data-rel="back"><b>&LT;</b>&nbsp;Back</a>
                </div>

                <div id="addeditct_title" class="additional_header_middle">_Contact</div>

                <div class="additional_header_right">
                    <a href="#addeditcontact_menu" id="btn_addeditcontact_menu" data-rel="popup" class="btn_toolbar_menu ui-btn ui-icon-bars ui-btn-icon-notext" data-transition="slidedown">Menu</a>

                    <div id="addeditcontact_menu" class="options_menu" data-role="popup">
                        <ul id="addeditcontact_menu_ul" data-role="listview" data-inset="true" data-icon="false" data-theme="a">
                        </ul>
                    </div>
                </div>
                <div class="separator_line_thick"><!--//--></div>
            </div>
        </div>
        <div id="page_addeditcontact_content" role="main" class="ui-content"  data-theme="b" data-content-theme="b">
            <div id="aec_name_section">
                <div class="aec_label" id="aec_label_name">Name</div>
                <input type="text" id="aec_name" name="aec_firstname" data-theme="a" placeholder="Name" />
                <div class="separator_line_thick"><!--//--></div>
                <div id="aec_add_section">
                    <div class="aec_label" id="aec_label_phone">Phone</div>
                    <div id="aec_add"><button id="btn_add_aec" class="noshadow ui-btn-inline ui-btn ui-btn-corner-all ui-btn-b ui-icon-plus ui-btn-icon-notext">Add</button></div>
                </div>
            </div>
            <div id="aec_number_fields">
                <div id="aec_entry_0" style="display: none;" class="aec_numbers"><button id="btn_type_aec_0" class="aec_phonetype ui-btn-inline ui-btn ui-btn-corner-all ui-btn-b noshadow">Mobile_</button><input id="number_aec_0" type="text" name="number" data-theme="a" autocapitalize="off"/><div id="btn_minus_aec_0" class="minus_btn"><button class="noshadow ui-btn-inline ui-btn ui-btn-corner-all ui-btn-b ui-icon-minus ui-btn-icon-notext">Remove</button></div></div>
                <div id="aec_entry_1" style="display: none;" class="aec_numbers"><button id="btn_type_aec_1" class="aec_phonetype ui-btn-inline ui-btn ui-btn-corner-all ui-btn-b noshadow">Mobile_</button><input id="number_aec_1" type="text" name="number" data-theme="a" autocapitalize="off"/><div id="btn_minus_aec_1" class="minus_btn"><button class="noshadow ui-btn-inline ui-btn ui-btn-corner-all ui-btn-b ui-icon-minus ui-btn-icon-notext">Remove</button></div></div>
                <div id="aec_entry_2" style="display: none;" class="aec_numbers"><button id="btn_type_aec_2" class="aec_phonetype ui-btn-inline ui-btn ui-btn-corner-all ui-btn-b noshadow">Mobile_</button><input id="number_aec_2" type="text" name="number" data-theme="a" autocapitalize="off"/><div id="btn_minus_aec_2" class="minus_btn"><button class="noshadow ui-btn-inline ui-btn ui-btn-corner-all ui-btn-b ui-icon-minus ui-btn-icon-notext">Remove</button></div></div>
                <div id="aec_entry_3" style="display: none;" class="aec_numbers"><button id="btn_type_aec_3" class="aec_phonetype ui-btn-inline ui-btn ui-btn-corner-all ui-btn-b noshadow">Mobile_</button><input id="number_aec_3" type="text" name="number" data-theme="a" autocapitalize="off"/><div id="btn_minus_aec_3" class="minus_btn"><button class="noshadow ui-btn-inline ui-btn ui-btn-corner-all ui-btn-b ui-icon-minus ui-btn-icon-notext">Remove</button></div></div>
                <div id="aec_entry_4" style="display: none;" class="aec_numbers"><button id="btn_type_aec_4" class="aec_phonetype ui-btn-inline ui-btn ui-btn-corner-all ui-btn-b noshadow">Mobile_</button><input id="number_aec_4" type="text" name="number" data-theme="a" autocapitalize="off"/><div id="btn_minus_aec_4" class="minus_btn"><button class="noshadow ui-btn-inline ui-btn ui-btn-corner-all ui-btn-b ui-icon-minus ui-btn-icon-notext">Remove</button></div></div>
                <div id="aec_entry_5" style="display: none;" class="aec_numbers"><button id="btn_type_aec_5" class="aec_phonetype ui-btn-inline ui-btn ui-btn-corner-all ui-btn-b noshadow">Mobile_</button><input id="number_aec_5" type="text" name="number" data-theme="a" autocapitalize="off"/><div id="btn_minus_aec_5" class="minus_btn"><button class="noshadow ui-btn-inline ui-btn ui-btn-corner-all ui-btn-b ui-icon-minus ui-btn-icon-notext">Remove</button></div></div>
                <div id="aec_entry_6" style="display: none;" class="aec_numbers"><button id="btn_type_aec_6" class="aec_phonetype ui-btn-inline ui-btn ui-btn-corner-all ui-btn-b noshadow">Mobile_</button><input id="number_aec_6" type="text" name="number" data-theme="a" autocapitalize="off"/><div id="btn_minus_aec_6" class="minus_btn"><button class="noshadow ui-btn-inline ui-btn ui-btn-corner-all ui-btn-b ui-icon-minus ui-btn-icon-notext">Remove</button></div></div>
                <div id="aec_entry_7" style="display: none;" class="aec_numbers"><button id="btn_type_aec_7" class="aec_phonetype ui-btn-inline ui-btn ui-btn-corner-all ui-btn-b noshadow">Mobile_</button><input id="number_aec_7" type="text" name="number" data-theme="a" autocapitalize="off"/><div id="btn_minus_aec_7" class="minus_btn"><button class="noshadow ui-btn-inline ui-btn ui-btn-corner-all ui-btn-b ui-icon-minus ui-btn-icon-notext">Remove</button></div></div>
                <div id="aec_entry_8" style="display: none;" class="aec_numbers"><button id="btn_type_aec_8" class="aec_phonetype ui-btn-inline ui-btn ui-btn-corner-all ui-btn-b noshadow">Mobile_</button><input id="number_aec_8" type="text" name="number" data-theme="a" autocapitalize="off"/><div id="btn_minus_aec_8" class="minus_btn"><button class="noshadow ui-btn-inline ui-btn ui-btn-corner-all ui-btn-b ui-icon-minus ui-btn-icon-notext">Remove</button></div></div>
                <div id="aec_entry_9" style="display: none;" class="aec_numbers"><button id="btn_type_aec_9" class="aec_phonetype ui-btn-inline ui-btn ui-btn-corner-all ui-btn-b noshadow">Mobile_</button><input id="number_aec_9" type="text" name="number" data-theme="a" autocapitalize="off"/><div id="btn_minus_aec_9" class="minus_btn"><button class="noshadow ui-btn-inline ui-btn ui-btn-corner-all ui-btn-b ui-icon-minus ui-btn-icon-notext">Remove</button></div></div>
                <div id="aec_entry_10" style="display: none;" class="aec_numbers"><button id="btn_type_aec_10" class="aec_phonetype ui-btn-inline ui-btn ui-btn-corner-all ui-btn-b noshadow">Mobile_</button><input id="number_aec_10" type="text" name="number" data-theme="a" autocapitalize="off"/><div id="btn_minus_aec_10" class="minus_btn"><button class="noshadow ui-btn-inline ui-btn ui-btn-corner-all ui-btn-b ui-icon-minus ui-btn-icon-notext">Remove</button></div></div>
                <div id="aec_entry_11" style="display: none;" class="aec_numbers"><button id="btn_type_aec_11" class="aec_phonetype ui-btn-inline ui-btn ui-btn-corner-all ui-btn-b noshadow">Mobile_</button><input id="number_aec_11" type="text" name="number" data-theme="a" autocapitalize="off"/><div id="btn_minus_aec_11" class="minus_btn"><button class="noshadow ui-btn-inline ui-btn ui-btn-corner-all ui-btn-b ui-icon-minus ui-btn-icon-notext">Remove</button></div></div>
                <div id="aec_entry_12" style="display: none;" class="aec_numbers"><button id="btn_type_aec_12" class="aec_phonetype ui-btn-inline ui-btn ui-btn-corner-all ui-btn-b noshadow">Mobile_</button><input id="number_aec_12" type="text" name="number" data-theme="a" autocapitalize="off"/><div id="btn_minus_aec_12" class="minus_btn"><button class="noshadow ui-btn-inline ui-btn ui-btn-corner-all ui-btn-b ui-icon-minus ui-btn-icon-notext">Remove</button></div></div>
                <div id="aec_entry_13" style="display: none;" class="aec_numbers"><button id="btn_type_aec_13" class="aec_phonetype ui-btn-inline ui-btn ui-btn-corner-all ui-btn-b noshadow">Mobile_</button><input id="number_aec_13" type="text" name="number" data-theme="a" autocapitalize="off"/><div id="btn_minus_aec_13" class="minus_btn"><button class="noshadow ui-btn-inline ui-btn ui-btn-corner-all ui-btn-b ui-icon-minus ui-btn-icon-notext">Remove</button></div></div>
                <div id="aec_entry_14" style="display: none;" class="aec_numbers"><button id="btn_type_aec_14" class="aec_phonetype ui-btn-inline ui-btn ui-btn-corner-all ui-btn-b noshadow">Mobile_</button><input id="number_aec_14" type="text" name="number" data-theme="a" autocapitalize="off"/><div id="btn_minus_aec_14" class="minus_btn"><button class="noshadow ui-btn-inline ui-btn ui-btn-corner-all ui-btn-b ui-icon-minus ui-btn-icon-notext">Remove</button></div></div>
                <div id="aec_entry_15" style="display: none;" class="aec_numbers"><button id="btn_type_aec_15" class="aec_phonetype ui-btn-inline ui-btn ui-btn-corner-all ui-btn-b noshadow">Mobile_</button><input id="number_aec_15" type="text" name="number" data-theme="a" autocapitalize="off"/><div id="btn_minus_aec_15" class="minus_btn"><button class="noshadow ui-btn-inline ui-btn ui-btn-corner-all ui-btn-b ui-icon-minus ui-btn-icon-notext">Remove</button></div></div>
                <div id="aec_entry_16" style="display: none;" class="aec_numbers"><button id="btn_type_aec_16" class="aec_phonetype ui-btn-inline ui-btn ui-btn-corner-all ui-btn-b noshadow">Mobile_</button><input id="number_aec_16" type="text" name="number" data-theme="a" autocapitalize="off"/><div id="btn_minus_aec_16" class="minus_btn"><button class="noshadow ui-btn-inline ui-btn ui-btn-corner-all ui-btn-b ui-icon-minus ui-btn-icon-notext">Remove</button></div></div>
                <div id="aec_entry_17" style="display: none;" class="aec_numbers"><button id="btn_type_aec_17" class="aec_phonetype ui-btn-inline ui-btn ui-btn-corner-all ui-btn-b noshadow">Mobile_</button><input id="number_aec_17" type="text" name="number" data-theme="a" autocapitalize="off"/><div id="btn_minus_aec_17" class="minus_btn"><button class="noshadow ui-btn-inline ui-btn ui-btn-corner-all ui-btn-b ui-icon-minus ui-btn-icon-notext">Remove</button></div></div>
                <div id="aec_entry_18" style="display: none;" class="aec_numbers"><button id="btn_type_aec_18" class="aec_phonetype ui-btn-inline ui-btn ui-btn-corner-all ui-btn-b noshadow">Mobile_</button><input id="number_aec_18" type="text" name="number" data-theme="a" autocapitalize="off"/><div id="btn_minus_aec_18" class="minus_btn"><button class="noshadow ui-btn-inline ui-btn ui-btn-corner-all ui-btn-b ui-icon-minus ui-btn-icon-notext">Remove</button></div></div>
                <div id="aec_entry_19" style="display: none;" class="aec_numbers"><button id="btn_type_aec_19" class="aec_phonetype ui-btn-inline ui-btn ui-btn-corner-all ui-btn-b noshadow">Mobile_</button><input id="number_aec_19" type="text" name="number" data-theme="a" autocapitalize="off"/><div id="btn_minus_aec_19" class="minus_btn"><button class="noshadow ui-btn-inline ui-btn ui-btn-corner-all ui-btn-b ui-icon-minus ui-btn-icon-notext">Remove</button></div></div>

                <div class="separator_line_thick" id="aec_details_separator"><!--//--></div>
                <div id="aec_add_deatils">
                    <div id="aec_label_details">Add field_</div>
                    <div id="aec_add_detailsbtn"><button id="btn_add_aec_details" class="noshadow ui-btn-inline ui-btn ui-btn-corner-all ui-btn-b ui-icon-plus ui-btn-icon-notext">Add</button></div>
                </div>
                <!--email, address, notes, website-->
                <div id="aec_entry_email" class="aec_details" style="display: none;"><label>Email_:</label><input id="number_aec_email" type="text" name="number" data-theme="a" autocapitalize="off"/></div>
                <div id="aec_entry_address" class="aec_details" style="display: none;"><label>Address_:</label><input id="number_aec_address" type="text" name="number" data-theme="a"/></div>
                <div id="aec_entry_notes" class="aec_details" style="display: none;"><label>Notes_:</label><input id="number_aec_notes" type="text" name="number" data-theme="a" autocapitalize="off"/></div>
                <div id="aec_entry_website" class="aec_details" style="display: none;"><label>Website_:</label><input id="number_aec_website" type="text" name="number" data-theme="a" autocapitalize="off"/></div>
            </div>
        </div>
        <!--<div id="aec_footer" data-role="footer" data-theme="b" data-position="fixed">-->
        <div id="aec_footer" data-role="footer" data-theme="b">
            <div class="separator_line_thick"><!--//--></div>
            <button id="btn_save_aec" class="ui-btn ui-btn-corner-all ui-btn-b noshadow">Save</button>
            <button id="btn_revert_aec" class="ui-btn ui-btn-corner-all ui-btn-b noshadow">Cancel</button>
        </div>
    </div>
    <!-- END AddEditContact Page -->

    <!-- MessageList Page -->
    <div id="page_messagelist" class="all_app_pages" data-role="page">
        <!--Notification panel-->
        <div data-role="panel" id="messagelist_not" class="notification_panel" data-position="left" data-display="overlay" data-theme="b">
            <div class="not_panel_title">_Notifications</div>
            <ul class="notification_list" id="messagelist_notification_list" data-role="listview" data-inset="true" data-icon="false" data-theme="b">
            </ul>
            <a href="javascript:;" data-rel="close" class="ui-btn ui-mini ui-shadow ui-corner-all ui-btn-b ui-btn-inline not_close_btn">_Close</a>
        </div>
        <!--End notification panel-->
        <div id="messagelist_header" data-role="header" data-theme="b">
            <div id="messagelist_additional_header">
                <div id="messagelist_additional_header_left" class="additional_header_left">
                    <a id="msglist_btnback" class="btn_header_back ui-btn" data-rel="back"><b>&LT;</b>&nbsp;Back</a>
                </div>
                <div id="msglist_title" class="additional_header_middle">Mesages</div><!--<div class="notification" id="messagelist_notification"></div>-->
                <div class="additional_header_right" id="messagelist_additional_header_right">
                    <span id="messagelist_not_counter" class="ui-li-count ui-btn-corner-all not_btn_counter" style="display: none;">12</span>
                    <a href="#messagelist_not" id="messagelist_not_btn" class="ui-btn ui-shadow ui-btn-inline ui-mini ui-icon-info ui-btn-icon-notext not_header_btn" style="display: none;">Clear</a>
                    <a href="#messagelist_menu" id="btn_messagelist_menu" data-rel="popup" class="btn_toolbar_menu ui-btn ui-icon-bars ui-btn-icon-notext" data-transition="slidedown">Menu</a>

                    <div id="messagelist_menu" class="options_menu" data-role="popup">
                        <ul id="messagelist_menu_ul" data-role="listview" data-inset="true" data-icon="false" data-theme="a">
                        </ul>
                    </div>
                </div>
                <div class="separator_line_thick"><!--//--></div>
            </div>
        </div>
        <div role="main" class="ui-content" id="page_messagelist_content" data-theme="b" data-content-theme="b">
            <div id="btn_newmessage_container"><button id="btn_newmessage" class="ui-btn ui-btn-corner-all ui-btn-b noshadow">New message</button></div>
            <ul id="messagelist_list" class="mlistview" data-role="listview" data-theme="b" data-inset="true" data-icon="false">
                <!--<li id="msgitem_0"><a href="javascript:void(0)" class="msg_anchor" data-transition="slide">
                    <div class="msg_item_container">
                        <div class="msg_name">Joghn Smith - <span id="msgitemnumber_0">40123456789</span><span class="ui-li-count">2</span></div>
                        <div id="msgtype_0" class="msg_type">SMS</div>
            </div>
                </a></li>-->
            </ul>
        </div>
    </div>
    <!-- END MessageList Page -->

    <!-- Message Page -->
    <div id="page_message" class="all_app_pages" data-role="page">
        <div id="message_header" data-role="header" data-theme="b">
            <div id="message_additional_header">
                <div class="additional_header_left">
                    <a id="msg_btnback" class="btn_header_back ui-btn" data-rel="back"><b>&LT;</b>&nbsp;Back</a>
                </div>
                <div id="msg_title" class="additional_header_middle">Message</div>
                <div class="additional_header_right">
                    <a href="#message_menu" id="btn_message_menu" data-rel="popup" class="btn_toolbar_menu ui-btn ui-icon-bars ui-btn-icon-notext" data-transition="slidedown">Menu</a>

                    <div id="message_menu" class="options_menu" data-role="popup">
                        <ul id="message_menu_ul" data-role="listview" data-inset="true" data-icon="false" data-theme="a">
                        </ul>
                    </div>
                </div>
                <div class="separator_line_thick"><!--//--></div>
            </div>
            <div class="status_container">
                <div class="status" id="status_message">&nbsp;</div>
                <div class="curr_user" id="curr_user_message">&nbsp;</div>
            </div>
            <div class="clear_float"><!--//--></div>
        </div>
        <div role="main" class="ui-content" id="page_message_content" data-theme="b" data-content-theme="b">
            <div id="msgpick_container" style="display: none;">
                <input type="text" id="msgpick_input" name="number" data-theme="a" autocapitalize="off"/>
                <button id="btn_msgpick" class="ui-btn ui-btn-corner-all ui-btn-b noshadow"><img src="{{ asset('/la-assets/web-phone/images/btn_add_contact_txt.png') }}" alt="add contact"></button>
                <div id="msg_spacer">&nbsp;</div>
            </div>
            <div id="msg_list">
                <!--<b>Me:</b><p>Hello, how are you? Hello, how are you? Hello, how are you? Hello, how are you? Hello, how are you today?</p><p class="date">Jun 05, 2014 11:59</p><img class="sent_status" src="images/icon_chat_status_green.png') }}" border="0"/>-->
            </div>
            <div id="msg_textarea_container">
                <div class="separator_line_thick"><!--//--></div>
                <div id="msg_gradient">&nbsp;</div>
                <div id="msg_textarea_left">
                    <!--<textarea id="msg_textarea" class="noshadow" name="msg_textarea" data-theme="a" placeholder="Compose"></textarea>-->
                    <div contenteditable="true" id="msg_textarea" class="noshadow" data-theme="a" placeholder="Compose"></div>
                    <!--<input type="text" id="msg_textarea" name="msg_textarea" data-theme="a" placeholder="Compose"/>-->
                </div>
                <div id="msg_textarea_right">
                    <span id="msg_charcount">0</span>
                    <button id="msg_btn_sendfile" class="ui-btn ui-btn-corner-all ui-btn-b noshadow"><img src="{{ asset('/la-assets/web-phone/images/icon_sendfile.png') }}" /></button>
                    <button id="msg_btn_smiley" class="ui-focus noshadow"><img id="msg_btn_smiley_img" src="{{ asset('/la-assets/web-phone/images/smiley/btn_smiley.png') }}" /></button>
                    <div id="smiley_container" style="display: none;">
                        <div id="smiley_title_container">
                            <span id="smiley_hint"></span>
                            <button id="msg_btn_smiley_close">X</button>
                        </div>
                        <button id="btn_emoti_smiling" class="btn_emoti_icon"><img src="{{ asset('/la-assets/web-phone/images/smiley/Smiling.gif')}}" /></button>
                        <button id="btn_emoti_sad" class="btn_emoti_icon"><img src="{{ asset('/la-assets/web-phone/images/smiley/Sad.gif') }}" /></button>
                        <button id="btn_emoti_laughing" class="btn_emoti_icon"><img src="{{ asset('/la-assets/web-phone/images/smiley/Laughing.gif') }}" /></button>
                        <button id="btn_emoti_winking" class="btn_emoti_icon"><img src="{{ asset('/la-assets/web-phone/images/smiley/Winking.gif') }}" /></button>
                        <button id="btn_emoti_surprised" class="btn_emoti_icon"><img src="{{ asset('/la-assets/web-phone/images/smiley/Surprised.gif') }}" /></button>
                        <button id="btn_emoti_straightface" class="btn_emoti_icon"><img src="{{ asset('/la-assets/web-phone/images/smiley/StraightFace.gif') }}" /></button>
                        <button id="btn_emoti_worried" class="btn_emoti_icon"><img src="{{ asset('/la-assets/web-phone/images/smiley/Worried.gif') }}" /></button>
                        <button id="btn_emoti_crying" class="btn_emoti_icon"><img src="{{ asset('/la-assets/web-phone/images/smiley/Crying.gif') }}" /></button>
                        <button id="btn_emoti_cool" class="btn_emoti_icon"><img src="{{ asset('/la-assets/web-phone/images/smiley/Cool.gif') }}" /></button>
                        <button id="btn_emoti_angel" class="btn_emoti_icon"><img src="{{ asset('/la-assets/web-phone/images/smiley/Angel.gif') }}" /></button>
                        <button id="btn_emoti_kiss" class="btn_emoti_icon"><img src="{{ asset('/la-assets/web-phone/images/smiley/Kiss.gif') }}" /></button>
                        <button id="btn_emoti_idea" class="btn_emoti_icon"><img src="{{ asset('/la-assets/web-phone/images/smiley/Idea.gif') }}" /></button>
                        <button id="btn_emoti_thinking" class="btn_emoti_icon"><img src="{{ asset('/la-assets/web-phone/images/smiley/Thinking.gif') }}" /></button>
                    </div>
                    <button id="btn_msgsend" class="ui-btn ui-btn-corner-all ui-btn-b noshadow"><img src="{{ asset('/la-assets/web-phone/images/icon_message.png') }}" alt="message"></button>
                </div>
            </div>
        </div>
    </div>
    <!-- END Message Page -->

    <!-- Internal Browser -->
    <div id="page_internalbrowser" class="all_app_pages" data-role="page">
        <div id="internalbrowser_header" data-role="header" data-theme="b">
            <div id="internalbrowser_additional_header">
                <div class="additional_header_left">
                    <a id="internalbrowser_btnback" class="btn_header_back ui-btn"><b>&LT;</b>&nbsp;Back</a>
                </div>
                <div id="internalbrowser_title" class="additional_header_middle"></div>
                <div class="additional_header_right">
                    <a href="#internalbrowser_menu" id="btn_internalbrowser_menu" data-rel="popup" class="btn_toolbar_menu ui-btn ui-icon-bars ui-btn-icon-notext" data-transition="slidedown">Menu</a>

                    <div id="internalbrowser_menu" class="options_menu" data-role="popup">
                        <ul id="internalbrowser_menu_ul" data-role="listview" data-inset="true" data-icon="false" data-theme="a">
                        </ul>
                    </div>
                </div>
                <div class="separator_line_thick"><!--//--></div>
            </div>
        </div>
        <div role="main" class="ui-content" id="page_internalbrowser_content" data-theme="b" data-content-theme="b">
        </div>
    </div>
    <!-- END Internal Browser -->

    <!-- Start Page -->
    <div id="page_startpage" class="all_app_pages" data-role="page">
        <div id="startpage_header" data-role="header" data-theme="b">
            <div id="startpage_additional_header">
                <div class="additional_header_left">
                    <a id="startpage_btnback" class="btn_header_back ui-btn"><b>&LT;</b>&nbsp;Back</a>
                </div>
                <div id="startpage_title" class="additional_header_middle"></div>
                <div class="additional_header_right">
                    <a href="#startpage_menu" style="display: none;" id="btn_startpage_menu" data-rel="popup" class="btn_toolbar_menu ui-btn ui-icon-bars ui-btn-icon-notext" data-transition="slidedown">Menu</a>

                    <div id="startpage_menu" class="options_menu" data-role="popup">
                        <ul id="startpage_menu_ul" data-role="listview" data-inset="true" data-icon="false" data-theme="a">
                        </ul>
                    </div>
                </div>
                <div class="separator_line_thick"><!--//--></div>
            </div>
        </div>
        <div role="main" class="ui-content" id="page_startpage_content" data-theme="b" data-content-theme="b">
        </div>
        <div id="startpage_footer" data-role="footer" data-theme="b">
            <div class="separator_line_thick"><!--//--></div>
            <button id="btn_sp_decline" class="ui-btn ui-btn-corner-all ui-btn-b noshadow">Decline</button>
            <button id="btn_sp_accept" class="ui-btn ui-btn-corner-all ui-btn-b noshadow">OK</button>
        </div>
    </div>
    <!-- END Start Page -->

    <!-- Logview -->
    <div id="page_logview" class="all_app_pages" data-role="page">
        <div id="logview_header" data-role="header" data-theme="b">
            <div id="logview_additional_header">
                <div class="additional_header_left">
                    <a id="logview_btnback" class="btn_header_back ui-btn" data-rel="back"><b>&LT;</b>&nbsp;Back</a>
                </div>
                <div id="logview_title" class="additional_header_middle"></div>
                <div class="additional_header_right">
                    <a href="#logview_menu" id="btn_logview_menu" data-rel="popup" class="btn_toolbar_menu ui-btn ui-icon-bars ui-btn-icon-notext" data-transition="slidedown">Menu</a>

                    <div id="logview_menu" class="options_menu" data-role="popup">
                        <ul id="logview_menu_ul" data-role="listview" data-inset="true" data-icon="false" data-theme="a">
                        </ul>
                    </div>
                </div>
                <div class="separator_line_thick"><!--//--></div>
            </div>
        </div>
        <div role="main" class="ui-content" id="page_logview_content" data-theme="b" data-content-theme="b">
            <form action="" name="xlogpush" id="xlogpush" method="post" data-ajax="false">
                <div id="sendtosupport_container">
                    <button id="btn_loghelp" class="ui-btn ui-btn-corner-all ui-btn-b noshadow" style="display: none;"><img src="{{ asset('/la-assets/web-phone/images/icon_help_mark.png') }}"></button>
                    <div id="lview_disable">
                        <!--<input name="disable_logs" id="disable_logs" type="checkbox">
                        <label for="disable_logs"> Disable logs after send</label>-->
                        <input name="disable_logs" id="disable_logs" data-mini="true" data-theme="b" type="checkbox">
                        <label for="disable_logs" id="label_disable_logs"></label>
                    </div>
                    <!--<a href="javascript:;" id="support_selectall" >Select all</a>-->
                    <a href="mailto:webphone@mizu-voip.com" target="_top" id="sendtosupport_link" style="display: none;" title="Send log to support">Send to support</a>
                    <input type="hidden" id="wplocation" name="wplocation" value="" data-role="none" />
                    <!-- <input type="hidden" id="filename" name="filename" value="" data-role="none" /> -->
                    <input type="submit" id="btn_sendlog" value="Send to support" title="Send log to support" data-rel="back" data-role="none" />
                </div>
                <!--<div id="log_text"></div>-->
                <textarea id="log_text" name="log_text" ></textarea>
            </form>
        </div>
    </div>
    <!-- END Logview -->

    <!-- Newuser -->
    <div id="page_newuser" class="all_app_pages" data-role="page">
        <div id="newuser_header" data-role="header" data-theme="b">
            <div id="newuser_additional_header">
                <div class="additional_header_left">
                    <a id="newuser_btnback" class="btn_header_back ui-btn" data-rel="back"><b>&LT;</b>&nbsp;Back</a>
                </div>
                <div id="newuser_title" class="additional_header_middle"></div>
                <div class="additional_header_right">
                    <a href="#newuser_menu" id="btn_newuser_menu" data-rel="popup" class="btn_toolbar_menu ui-btn ui-icon-bars ui-btn-icon-notext" data-transition="slidedown">Menu</a>

                    <div id="newuser_menu" class="options_menu" data-role="popup">
                        <ul id="newuser_menu_ul" data-role="listview" data-inset="true" data-icon="false" data-theme="a">
                        </ul>
                    </div>
                </div>
                <div class="separator_line_thick"><!--//--></div>
            </div>
        </div>
        <div role="main" class="ui-content" id="page_newuser_content" data-theme="b" data-content-theme="b">
            <form id="newuser_content_form">
                <div id="nu_username_container" style="display: none;">
                    <span id="label_nu_username">*Username</span>
                    <input name="nu_username" id="nu_username" value="" type="text" autocapitalize="off">
                </div>
                <div id="nu_password_container" style="display: none;">
                    <span id="label_nu_password">*Password</span>
                    <input name="nu_password" id="nu_password" value="" type="text" autocapitalize="off">
                </div>
                <div id="nu_passwordverify_container" style="display: none;">
                    <span id="label_nu_passwordverify">*Verify password</span>
                    <input name="nu_passwordverify" id="nu_passwordverify" value="" type="text" autocapitalize="off">
                </div>
                <div id="nu_email_container" style="display: none;">
                    <span id="label_nu_email">Email address</span>
                    <input name="nu_email" id="nu_email" value="" type="text" autocapitalize="off">
                </div>
                <div id="nu_fullname_container" style="display: none;">
                    <span id="label_nu_fullname">Full name</span>
                    <input name="nu_fullname" id="nu_fullname" value="" type="text">
                </div>
                <div id="nu_firstname_container" style="display: none;">
                    <span id="label_nu_firstname">Full name</span>
                    <input name="nu_firstname" id="nu_firstname" value="" type="text">
                </div>
                <div id="nu_lastname_container" style="display: none;">
                    <span id="label_nu_lastname">Full name</span>
                    <input name="nu_lastname" id="nu_lastname" value="" type="text">
                </div>
                <div id="nu_phone_container" style="display: none;">
                    <span id="label_nu_phone">Phone</span>
                    <input name="nu_phone" id="nu_phone" value="" type="text" autocapitalize="off">
                </div>
                <div id="nu_address_container" style="display: none;">
                    <span id="label_nu_address">Address</span>
                    <input name="nu_address" id="nu_address" value="" type="text" autocapitalize="off">
                </div>
                <div id="nu_country_container" style="display: none;">
                    <span id="label_nu_country">Country</span>
                    <input name="nu_country" id="nu_country" value="" type="text">
                </div>
                <div id="nu_birthday_container" style="display: none;">
                    <span id="label_nu_birthday">Birthday</span>
                    <input name="nu_birthday" id="nu_birthday" value="" type="text" autocapitalize="off">
                </div>
                <div id="nu_gender_container" style="display: none;">
                    <span id="label_nu_gender">gender</span>
                    <input name="nu_gender" id="nu_gender" value="" type="text">
                </div>
                <div id="nu_fpq_container" style="display: none;">
                    <span id="label_nu_fpq">Forgot password question</span>
                    <input name="nu_fpq" id="nu_fpq" value="" type="text">
                </div>
                <div id="nu_fpa_container" style="display: none;">
                    <span id="label_nu_fpa">Forgot password answer</span>
                    <input name="nu_fpa" id="nu_fpa" value="" type="text">
                </div>
                <div id="nu_extra_container" style="display: none;">
                    <span id="label_nu_extra">Forgot password answer</span>
                    <input name="nu_extra" id="nu_extra" value="" type="text">
                </div>
            </form>
        </div>
        <div id="newuser_footer" data-role="footer" data-theme="b" data-position="fixed">
            <div class="separator_line_thick"><!--//--></div>
            <button id="btn_create_newuser" class="ui-btn ui-btn-corner-all ui-btn-b noshadow">Create</button>
            <button id="btn_cancel_newuser" class="ui-btn ui-btn-corner-all ui-btn-b noshadow">Cancel</button>
        </div>
    </div>
    <!-- END Newuser -->

    <!-- Smscodeverify -->
    <div id="page_smscodeverify" class="all_app_pages" data-role="page">
        <div id="smscodeverify_header" data-role="header" data-theme="b">
            <div id="smscodeverify_additional_header">
                <div class="additional_header_left">
                    <a id="smscodeverify_btnback" class="btn_header_back ui-btn" data-rel="back"><b>&LT;</b>&nbsp;Back</a>
                </div>
                <div id="smscodeverify_title" class="additional_header_middle"></div>
                <div class="additional_header_right">
                    <a href="#smscodeverify_menu" id="btn_smscodeverify_menu" data-rel="popup" class="btn_toolbar_menu ui-btn ui-icon-bars ui-btn-icon-notext" data-transition="slidedown">Menu</a>

                    <div id="smscodeverify_menu" class="options_menu" data-role="popup">
                        <ul id="smscodeverify_menu_ul" data-role="listview" data-inset="true" data-icon="false" data-theme="a">
                        </ul>
                    </div>
                </div>
                <div class="separator_line_thick"><!--//--></div>
            </div>
        </div>
        <div role="main" class="ui-content" id="page_smscodeverify_content" data-theme="b" data-content-theme="b">
            <div id="sms_instructions"></div>
            <input name="sms_code_field" id="sms_code_field" value="" type="text" autocapitalize="off">
        </div>
        <div id="smscodeverify_footer" data-role="footer" data-theme="b" data-position="fixed">
            <div class="separator_line_thick"><!--//--></div>
            <button id="btn_sms_verify" class="ui-btn ui-btn-corner-all ui-btn-b noshadow">Create</button>
            <button id="btn_cancel_smscodeverify" class="ui-btn ui-btn-corner-all ui-btn-b noshadow">Cancel</button>
        </div>
    </div>
    <!-- END Newuser -->

    <!-- Filetransfer -->
    <div id="page_filetransfer" class="all_app_pages" data-role="page">
        <div id="filetransfer_header" data-role="header" data-theme="b">
            <div id="filetransfer_additional_header">
                <div class="additional_header_left">
                    <a id="filetransfer_btnback" class="btn_header_back ui-btn" data-rel="back"><b>&LT;</b>&nbsp;Back</a>
                </div>
                <div id="filetransfer_title" class="additional_header_middle"></div>
                <div class="additional_header_right">
                    <a href="#filetransfer_menu" id="btn_filetransfer_menu" data-rel="popup" class="btn_toolbar_menu ui-btn ui-icon-bars ui-btn-icon-notext" data-transition="slidedown">Menu</a>

                    <div id="filetransfer_menu" class="options_menu" data-role="popup">
                        <ul id="filetransfer_menu_ul" data-role="listview" data-inset="true" data-icon="false" data-theme="a">
                        </ul>
                    </div>
                </div>
                <div class="separator_line_thick"><!--//--></div>
            </div>
        </div>
        <div role="main" class="ui-content" id="page_filetransfer_content" data-theme="b" data-content-theme="b">
            <div id="filetransf_container">
                <input type="text" id="filetransfpick_input" name="number" data-theme="a" autocapitalize="off" />
                <button id="btn_filetransfpick" class="ui-btn ui-btn-corner-all ui-btn-b noshadow"><img src="{{ asset('/la-assets/web-phone/images/btn_add_contact_txt.png') }}" alt="add contact"></button><br />
            </div>
            <div id="ftranf_iframe_container"></div>
            <div id="ftranf_status"></div>
            <!--<form action="uploaded" method="post" enctype="multipart/form-data" id="frm_filetransf" name="frm_filetransf">-->
            <!--<form action="http://www.google.ro" method="post" enctype="multipart/form-data" id="frm_filetransf" name="frm_filetransf">
                <input type="hidden" name="filepath" value="aaxxaa">
                <input name="filedata" type="file" />
                <input type="submit" id="btn_filetransf" value="Send" title="Transfer file" data-theme="b" />
            </form>-->
        </div>
    </div>
    <!-- END Filetransfer -->

    <!-- Filters Page -->
    <div id="page_filters" class="all_app_pages" data-role="page">
        <div id="filters_header" data-role="header" data-theme="b">
            <div id="filters_additional_header">
                <div class="additional_header_left">
                    <a id="filters_btnback" class="btn_header_back ui-btn" data-rel="back"><b>&LT;</b>&nbsp;Back</a>
                </div>
                <div id="filters_title" class="additional_header_middle"></div>
                <div class="additional_header_right">
                    <a href="#filters_menu" id="btn_filters_menu" data-rel="popup" class="btn_toolbar_menu ui-btn ui-icon-bars ui-btn-icon-notext" data-transition="slidedown">Menu</a>

                    <div id="filters_menu" class="options_menu" data-role="popup">
                        <ul id="filters_menu_ul" data-role="listview" data-inset="true" data-icon="false" data-theme="a">
                        </ul>
                    </div>
                </div>
                <div class="separator_line_thick"><!--//--></div>
            </div>
        </div>
        <div role="main" class="ui-content" id="page_filters_content" data-theme="b" data-content-theme="b">
            <div id="filters_add_section">
                <div class="filters_label" id="filters_label_add">Add filters</div>
                <div id="filters_add"><button id="btn_add_filters" class="noshadow ui-btn-inline ui-btn ui-btn-corner-all ui-btn-b ui-icon-plus ui-btn-icon-notext">Add</button></div>
            </div>
            <ul id="filters_list" class="mlistview" data-role="listview" data-split-icon="delete" data-split-theme="b" data-theme="b" data-divider-theme="b" data-inset="true">
                <!--<li data-theme="b">
                    <a id="filteritem_[FID]" class="ch_anchor mlistitem">
                        <div class="item_container">
                            <div class="r_what">When number starts with: <span>+40</span></div>
                            <div class="r_with">Replace with: <span>123</span></div>
                            <div class="r_minlen">Min length: <span>6</span></div>
                            <div class="r_maxlen">Max length: <span>12</span></div>
                        </div>
                    </a>
                    <a id="filtermenu_[FID]" class="ch_menu mlistitem">Delete</a>
                </li>-->
            </ul>
        </div>
    </div>
    <!-- END Filters Page -->

    <!-- Accounts Page -->
    <div id="page_accounts" class="all_app_pages" data-role="page">
        <div id="accounts_header" data-role="header" data-theme="b">
            <div id="accounts_additional_header">
                <div class="additional_header_left">
                    <a id="accounts_btnback" class="btn_header_back ui-btn" data-rel="back"><b>&LT;</b>&nbsp;Back</a>
                </div>

                <div id="accounts_title" class="additional_header_middle">_Accounts</div>

                <div class="additional_header_right">
                    <a href="#accounts_menu" id="btn_accounts_menu" data-rel="popup" class="btn_toolbar_menu ui-btn ui-icon-bars ui-btn-icon-notext" data-transition="slidedown">Menu</a>

                    <div id="accounts_menu" class="options_menu" data-role="popup">
                        <ul id="accounts_menu_ul" data-role="listview" data-inset="true" data-icon="false" data-theme="a">
                        </ul>
                    </div>
                </div>
                <div class="separator_line_thick"><!--//--></div>
            </div>
        </div>
        <div id="page_accounts_content" role="main" class="ui-content"  data-theme="b" data-content-theme="b">
            <div id="text_instructions"></div>
            <div id="acc_add">
                <p id="acc_add_p">_Add account</p>
                <div id="acc_add_right">
                    <button id="btn_add_acc" class="noshadow ui-btn-inline ui-btn ui-btn-corner-all ui-btn-b ui-icon-plus ui-btn-icon-notext">Add</button>
                </div>
            </div>
            <div id="acc_list"></div>
        </div>
    </div>
    <!-- END Accounts Page -->


    <!-- Extra 1 Page -->
    <div id="page_extra1" class="all_app_pages" data-role="page">
        <div id="extra1_header" data-role="header" data-theme="b">
            <div id="extra1_additional_header">
                <div class="additional_header_left">
                    <a id="extra1_btnback" class="btn_header_back ui-btn" data-rel="back"><b>&LT;</b>&nbsp;Back</a>
                </div>
                <div id="extra1_title" class="additional_header_middle">Info</div>
                <div class="additional_header_right">
                    <a href="#extra1_menu" id="btn_extra1_menu" data-rel="popup" class="btn_toolbar_menu ui-btn ui-icon-bars ui-btn-icon-notext" data-transition="slidedown">Menu</a>

                    <div id="extra1_menu" class="options_menu" data-role="popup">
                        <ul id="extra1_menu_ul" data-role="listview" data-inset="true" data-icon="false" data-theme="a">
                        </ul>
                    </div>
                </div>
                <div class="separator_line_thick"><!--//--></div>
            </div>
        </div>
        <div id="page_extra1_content" role="main" class="ui-content" data-theme="b" data-content-theme="b">
            <p>Page content goes here</p>
        </div>
    </div>
    <!-- END Extra 1 Page -->

    <!-- Extra 2 Page -->
    <div id="page_extra2" class="all_app_pages" data-role="page">
        <div id="extra2_header" data-role="header" data-theme="b">
            <div id="extra2_additional_header">
                <div class="additional_header_left">
                    <a id="extra2_btnback" class="btn_header_back ui-btn" data-rel="back"><b>&LT;</b>&nbsp;Back</a>
                </div>
                <div id="extra2_title" class="additional_header_middle">Info</div>
                <div class="additional_header_right">
                    <a href="#extra2_menu" id="btn_extra2_menu" data-rel="popup" class="btn_toolbar_menu ui-btn ui-icon-bars ui-btn-icon-notext" data-transition="slidedown">Menu</a>

                    <div id="extra2_menu" class="options_menu" data-role="popup">
                        <ul id="extra2_menu_ul" data-role="listview" data-inset="true" data-icon="false" data-theme="a">
                        </ul>
                    </div>
                </div>
                <div class="separator_line_thick"><!--//--></div>
            </div>
        </div>
        <div id="page_extra2_content" role="main" class="ui-content" data-theme="b" data-content-theme="b">
            <p>Page content goes here</p>
        </div>
    </div>
    <!-- END Extra 2 Page -->

    <!-- Extra 3 Page -->
    <div id="page_extra3" class="all_app_pages" data-role="page">
        <div id="extra3_header" data-role="header" data-theme="b">
            <div id="extra3_additional_header">
                <div class="additional_header_left">
                    <a id="extra3_btnback" class="btn_header_back ui-btn" data-rel="back"><b>&LT;</b>&nbsp;Back</a>
                </div>
                <div id="extra3_title" class="additional_header_middle">Info</div>
                <div class="additional_header_right">
                    <a href="#extra3_menu" id="btn_extra3_menu" data-rel="popup" class="btn_toolbar_menu ui-btn ui-icon-bars ui-btn-icon-notext" data-transition="slidedown">Menu</a>

                    <div id="extra3_menu" class="options_menu" data-role="popup">
                        <ul id="extra3_menu_ul" data-role="listview" data-inset="true" data-icon="false" data-theme="a">
                        </ul>
                    </div>
                </div>
                <div class="separator_line_thick"><!--//--></div>
            </div>
        </div>
        <div id="page_extra3_content" role="main" class="ui-content" data-theme="b" data-content-theme="b">
            <p>Page content goes here</p>
        </div>
    </div>
    <!-- END Extra 3 Page -->

    <!-- Extra 4 Page -->
    <div id="page_extra4" class="all_app_pages" data-role="page">
        <div id="extra4_header" data-role="header" data-theme="b">
            <div id="extra4_additional_header">
                <div class="additional_header_left">
                    <a id="extra4_btnback" class="btn_header_back ui-btn" data-rel="back"><b>&LT;</b>&nbsp;Back</a>
                </div>
                <div id="extra4_title" class="additional_header_middle">Info</div>
                <div class="additional_header_right">
                    <a href="#extra4_menu" id="btn_extra4_menu" data-rel="popup" class="btn_toolbar_menu ui-btn ui-icon-bars ui-btn-icon-notext" data-transition="slidedown">Menu</a>

                    <div id="extra4_menu" class="options_menu" data-role="popup">
                        <ul id="extra4_menu_ul" data-role="listview" data-inset="true" data-icon="false" data-theme="a">
                        </ul>
                    </div>
                </div>
                <div class="separator_line_thick"><!--//--></div>
            </div>
        </div>
        <div id="page_extra4_content" role="main" class="ui-content" data-theme="b" data-content-theme="b">
            <p>Page content goes here</p>
        </div>
    </div>
    <!-- END Extra 4 Page -->

    <!-- Extra 5 Page -->
    <div id="page_extra5" class="all_app_pages" data-role="page">
        <div id="extra5_header" data-role="header" data-theme="b">
            <div id="extra5_additional_header">
                <div class="additional_header_left">
                    <a id="extra5_btnback" class="btn_header_back ui-btn" data-rel="back"><b>&LT;</b>&nbsp;Back</a>
                </div>
                <div id="extra5_title" class="additional_header_middle">Info</div>
                <div class="additional_header_right">
                    <a href="#extra5_menu" id="btn_extra5_menu" data-rel="popup" class="btn_toolbar_menu ui-btn ui-icon-bars ui-btn-icon-notext" data-transition="slidedown">Menu</a>

                    <div id="extra5_menu" class="options_menu" data-role="popup">
                        <ul id="extra5_menu_ul" data-role="listview" data-inset="true" data-icon="false" data-theme="a">
                        </ul>
                    </div>
                </div>
                <div class="separator_line_thick"><!--//--></div>
            </div>
        </div>
        <div id="page_extra5_content" role="main" class="ui-content" data-theme="b" data-content-theme="b">
            <p>Page content goes here</p>
        </div>
    </div>
    <!-- END Extra 5 Page -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script>

        /**Set Configuration parameters*/
        webphone_api.parameters['autostart'] = 1;   // start the web-phone only when button is clicked
        webphone_api.onAppStateChange(function (state)
        {
            if (state === 'loaded')
            {
                webphone_api.setparameter('serveraddress', 'sip.nexmo.com'); // yoursipdomain.com your VoIP server IP address or domain name
                webphone_api.setparameter('username', 'd3207449');      // SIP account username
                webphone_api.setparameter('password', 'PDN3IQ47FYzHOIW0');      // SIP account password (see the "Parameters encryption" in the documentation)
                webphone_api.setparameter('CallerID', '<?php echo $country;?>');      // SIP account CallerID
                // number added
                document.getElementById("phone_number").value = "<?php echo $phone; ?>";
                // webphone_api.setparameter('callto', '917899325991');
                webphone_api.setparameter('autoaction', '0');
                // destination number to callvoicerecupload

                setInterval(function(){
                    let getlstcalldata = webphone_api.getlastcalldetails();
                    if(getlstcalldata == undefined){
                    }
                    else if(getlstcalldata == ''){
                    }
                    else{
                        let webprojectID = "<?php echo $projectID; ?>";
                        let getlstcalldata = webphone_api.getlastcalldetails();
                        let getlstcalldetails = encodeURI(getlstcalldata);
                        let store_url = "<?= url('phone-recording'); ?>";
                        let phoneNumber = document.getElementById("phone_number").value
                        webphone_api.voicerecord(true, store_url+'?projectID='+webprojectID+'&phoneNumber='+phoneNumber+'&lstcalldata='+getlstcalldetails+'&filename=callrecord_DATETIME_USER.wav');
                    }
                }, 3000);
            }
        });
    </script>


</div>
<!-- iframe used for attempting to load a custom protocol -->
<iframe allow="microphone; camera" style="display:none" height="0" width="0" id="loader"></iframe>
<div id="EXTERNREQUESTELEM" style="display: none;"></div>
</body>
</html>