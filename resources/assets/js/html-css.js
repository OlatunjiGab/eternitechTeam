var popupHtml = '<div id="popup-company">' +
    '<div id="popupCompany">' +
    '<form action="#" id="company-form" method="post" name="form">' +
    '<div id="close" onclick ="hide_popup()">X</div>' +
    '<h2>Company Details</h2>' +
    '<hr> ' +
    '<input id="company-name" name="company-name" placeholder="Company Name" type="text"  required="true">' +
    '<input id="company-email" name="company-email" placeholder="Email" type="email"  required="true">' +
    '<br/>' +
    '<a href="javascript:%20check_empty()" id="company-submit">Send</a> ' +
    '</form> ' +
    '</div>' +
    '</div>' +
    '<div id="projectModal" class="project-modal">\n' +
    '  <div class="project-modal-content">\n' +
    '    <div class="project-modal-header">\n' +
    '      <span class="project-close" onclick ="hide_projectpopup()">&times;</span>\n' +
    // '      <p style="color: #fff;">Company Details</p>\n' +
    '    </div>\n' +
    '    <div class="project-modal-body">\n' +
    '      <form action="#" id="project-form" method="post" name="form">\n' +
    '        <div class="company-details-top">\n' +
        '        <p id="content-message-string"></p>\n' +
    '        </div>\n' +
    '        <div class="company-details-bottom">\n' +
        '        <input id="company-id" name="company-id" type="hidden">\n' +
        '        <input id="project-phone" name="project-phone" placeholder="Mobile Phone" type="tel" required="true" oninput="this.value = this.value.replace(/[^0-9+]/g, \'\').replace(/(\\..*)\\./g, \'$1\');" maxlength="18"> \n' +
        '        <input id="project-email" name="project-email" placeholder="Email" type="email" required="true">\n' +
        '        <p><center><span id="error-msg"></span></center></p>\n' +
        '        <a onclick="check_projectempty()" class="margin-top" id="project-submit">Let\'s talk</a>\n' +
    '        </div>\n' +
    '      </form>\n' +
    '    </div>\n' +
    '  </div>\n' +
    '</div>';
var popupdiv = document.createElement("div");
popupdiv.innerHTML = popupHtml;
document.body.appendChild(popupdiv);
var css = '#popup-company {\n' +
    'width:100%;\n' +
    'height:100%;\n' +
    'opacity:.95;\n' +
    'top:0;\n' +
    'left:0;\n' +
    'display:none;\n' +
    'position:fixed;\n' +
    'background-color:#313131;\n' +
    'overflow:auto\n' +
    '}\n' +
    'div#close {\n' +
    'position:absolute;\n' +
    'right:-14px;\n' +
    'top:-14px;\n' +
    'cursor:pointer;\n' +
    'color: #fff;\n' +
    '}\n' +
    '.company-details-top {\n' +
    'background-color: #233b51;\n' +
    'padding: 20px;\n' +
    'margin-bottom: 20px;\n' +
    'color: #fff;\n' +
    '}\n' +
    '.company-details-top span {\n' +
    'color: #5ba0df !important;\n' +
    '}\n' +
    '.project-close {\n' +
    'background-color: #fff;\n' +
    'width: 32px;\n' +
    'text-align: center;\n' +
    'margin-right: -15px;\n' +
    '}\n' +



    'div#popupCompany {\n' +
    'position:absolute;\n' +
    'left:50%;\n' +
    'top:17%;\n' +
    'margin-left:-202px;\n' +
    '}\n' +
    '.company-details-bottom {\n' +
    'padding: 0 20px 25px;\n' +
    '}\n' +


    '#company-form {\n' +
    'max-width:300px;\n' +
    'min-width:250px;\n' +
    'padding:10px 50px;\n' +
    'border:2px solid gray;\n' +
    'border-radius:10px;\n' +
    'background-color:#fff\n' +
    '}\n' +
    '#company-form input[type=text],input[type=email] {\n' +
    'width:82%;\n' +
    'padding:10px;\n' +
    'margin-top:30px;\n' +
    'border:1px solid #ccc;\n' +
    'font-size:16px;\n' +
    '}\n' +
    '#company-submit {\n' +
    'text-decoration:none;\n' +
    'width:100%;\n' +
    'text-align:center;\n' +
    'display:block;\n' +
    'background-color:#FFBC00;\n' +
    'color:#fff;\n' +
    'border:1px solid #FFCB00;\n' +
    'padding:10px 0;\n' +
    'font-size:20px;\n' +
    'cursor:pointer;\n' +
    'border-radius:5px\n' +
    '}\n' +
    '#popup-company span {\n' +
    'color:red;\n' +
    'font-weight:700\n' +
    '}\n' +
    '#popup-company button {\n' +
    'width:10%;\n' +
    'height:45px;\n' +
    'border-radius:3px;\n' +
    'background-color:#cd853f;\n' +
    'color:#fff;\n' +
    'font-size:18px;\n' +
    'cursor:pointer\n' +
    '}\n'+
    '.project-modal {\n' +
    '  display: none;\n' +
    '  position: fixed;\n' +
    '  z-index: 999;\n' +
    '  padding-top: 100px;\n' +
    '  left: 0;\n' +
    '  top: 0;\n' +
    '  width: 100%;\n' +
    '  height: 100%;\n' +
    '  overflow: auto;\n' +
    '  background-color: rgb(0,0,0);\n' +
    '  background-color: rgba(0,0,0,0.4);\n' +
    '}\n' +
    '.project-modal-content {\n' +
    '  position: relative;\n' +
    '  background-color: #fefefe;\n' +
    '  margin: auto;\n' +
    '  padding: 0;\n' +
    '  border: 1px solid #888;\n' +
    '  width: 90%;\n' +
    '  max-width: 400px;\n' +
    '  box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19);\n' +
    '  -webkit-animation-name: animatetop;\n' +
    '  -webkit-animation-duration: 0.4s;\n' +
    '  animation-name: animatetop;\n' +
    '  animation-duration: 0.4s\n' +
    '}\n' +
    '@-webkit-keyframes animatetop {\n' +
    '  from {top:-300px; opacity:0} \n' +
    '  to {top:0; opacity:1}\n' +
    '}\n' +
    '@keyframes animatetop {\n' +
    '  from {top:-300px; opacity:0}\n' +
    '  to {top:0; opacity:1}\n' +
    '}\n' +
    '.project-close {\n' +
    '  color: white;\n' +
    '  float: right;\n' +
    '  font-size: 28px;\n' +
    '  font-weight: bold;\n' +
    '}\n' +
    '.project-close:hover,\n' +
    '.project-close:focus {\n' +
    '  color: #000;\n' +
    '  text-decoration: none;\n' +
    '  cursor: pointer;\n' +
    '}\n' +
    '.project-modal-header {\n' +
    '  padding: 0 16px;\n' +
    '  background-color: #233B51;\n' +
    '  color: white;\n' +
    '}\n' +
    '.project-modal-body {padding: 0; font-family: sans-serif;}\n' +
    '.project-modal-footer {\n' +
    '  padding: 2px 16px;\n' +
    '  background-color: #233B51;\n' +
    '  color: white;\n' +
    '}\n' +
    '#project-form input[type=text],input[type=tel],input[type=email] {\n' +
    '  width: 100%;\n' +
    '  padding: 10px 20px;\n' +
    '  margin-top:10px;\n' +
    '  border:1px solid #ccc;\n' +
    '  font-size:16px;\n' +
    '  box-sizing: border-box;\n' +
    '}\n' +
    '#project-submit {\n' +
    '  text-decoration:none;\n' +
    '  width:100%;\n' +
    '  text-align:center;\n' +
    '  display:block;\n' +
    '  background-color:#FFBC00;\n' +
    '  color:#fff;\n' +
    '  border:1px solid #FFCB00;\n' +
    '  padding:10px 0;\n' +
    '  font-size:20px;\n' +
    '  cursor:pointer;\n' +
    '  border-radius:0px;\n' +
    '}\n' +
    '.margin-top {\n' +
    '  margin-top: 10px;\n' +
    '}\n' +
    '#projectModal span {\n' +
    '  color:red;\n' +
    '  font-weight:700;\n' +
    '}\n'+
    '.intl-tel-input {\n' +
    '    display: inherit; \n' +
    '}\n'+
    '.country-list { \n'+
    '    max-width: 350px !important; \n' +
    '}',
    head = document.head || document.getElementsByTagName('head')[0],
    style = document.createElement('style');

head.appendChild(style);

style.type = 'text/css';
if (style.styleSheet) {
    // This is required for IE8 and below.
    style.styleSheet.cssText = css;
} else {
    style.appendChild(document.createTextNode(css));
}
jQuery(function ($){
    $(function () {
        var phoneNumber = document.getElementsByName('project-phone');
        $(phoneNumber).intlTelInput({
            nationalMode: false
        });
    });
});