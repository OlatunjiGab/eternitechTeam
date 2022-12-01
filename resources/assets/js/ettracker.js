var countryListCss = document.createElement('link');
countryListCss.setAttribute('href', 'https://www.jquery-az.com/jquery/css/intlTelInput/intlTelInput.css');
countryListCss.setAttribute('rel', 'stylesheet');
document.head.appendChild(countryListCss);
var countryListJs = document.createElement('script');
countryListJs.setAttribute('src', 'https://www.jquery-az.com/jquery/js/intlTelInput/intlTelInput.js');
countryListJs.setAttribute('async', true);
document.head.appendChild(countryListJs);

setTimeout(function () {
    var imported = document.createElement('script');
    imported.setAttribute('src', 'https://eterni.tech/assets/js/html-css.js');
    imported.setAttribute('async', true);
    document.head.appendChild(imported);
}, 1000);

(function (window) {
    {
        var unknown = '-';

        // screen
        var screenSize = '';
        if (screen.width) {
            width = (screen.width) ? screen.width : '';
            height = (screen.height) ? screen.height : '';
            screenSize += '' + width + " x " + height;
        }

        // browser
        var nVer = navigator.appVersion;
        var nAgt = navigator.userAgent;
        var browser = navigator.appName;
        var version = '' + parseFloat(navigator.appVersion);
        var majorVersion = parseInt(navigator.appVersion, 10);
        var nameOffset, verOffset, ix;

        // Opera
        if ((verOffset = nAgt.indexOf('Opera')) != -1) {
            browser = 'Opera';
            version = nAgt.substring(verOffset + 6);
            if ((verOffset = nAgt.indexOf('Version')) != -1) {
                version = nAgt.substring(verOffset + 8);
            }
        }
        // Opera Next
        if ((verOffset = nAgt.indexOf('OPR')) != -1) {
            browser = 'Opera';
            version = nAgt.substring(verOffset + 4);
        }
        // Legacy Edge
        else if ((verOffset = nAgt.indexOf('Edge')) != -1) {
            browser = 'Microsoft Legacy Edge';
            version = nAgt.substring(verOffset + 5);
        }
        // Edge (Chromium)
        else if ((verOffset = nAgt.indexOf('Edg')) != -1) {
            browser = 'Microsoft Edge';
            version = nAgt.substring(verOffset + 4);
        }
        // MSIE
        else if ((verOffset = nAgt.indexOf('MSIE')) != -1) {
            browser = 'Microsoft Internet Explorer';
            version = nAgt.substring(verOffset + 5);
        }
        // Chrome
        else if ((verOffset = nAgt.indexOf('Chrome')) != -1) {
            browser = 'Chrome';
            version = nAgt.substring(verOffset + 7);
        }
        // Safari
        else if ((verOffset = nAgt.indexOf('Safari')) != -1) {
            browser = 'Safari';
            version = nAgt.substring(verOffset + 7);
            if ((verOffset = nAgt.indexOf('Version')) != -1) {
                version = nAgt.substring(verOffset + 8);
            }
        }
        // Firefox
        else if ((verOffset = nAgt.indexOf('Firefox')) != -1) {
            browser = 'Firefox';
            version = nAgt.substring(verOffset + 8);
        }
        // MSIE 11+
        else if (nAgt.indexOf('Trident/') != -1) {
            browser = 'Microsoft Internet Explorer';
            version = nAgt.substring(nAgt.indexOf('rv:') + 3);
        }
        // Other browsers
        else if ((nameOffset = nAgt.lastIndexOf(' ') + 1) < (verOffset = nAgt.lastIndexOf('/'))) {
            browser = nAgt.substring(nameOffset, verOffset);
            version = nAgt.substring(verOffset + 1);
            if (browser.toLowerCase() == browser.toUpperCase()) {
                browser = navigator.appName;
            }
        }
        // trim the version string
        if ((ix = version.indexOf(';')) != -1) version = version.substring(0, ix);
        if ((ix = version.indexOf(' ')) != -1) version = version.substring(0, ix);
        if ((ix = version.indexOf(')')) != -1) version = version.substring(0, ix);

        majorVersion = parseInt('' + version, 10);
        if (isNaN(majorVersion)) {
            version = '' + parseFloat(navigator.appVersion);
            majorVersion = parseInt(navigator.appVersion, 10);
        }

        // mobile version
        var mobile = /Mobile|mini|Fennec|Android|iP(ad|od|hone)/.test(nVer);

        var ua = navigator.userAgent;
        if (/(tablet|ipad|playbook|silk)|(android(?!.*mobi))/i.test(ua)) {
            var deviceTypes = "tablet";
        } else if (/Mobile|iP(hone|od)|Android|BlackBerry|IEMobile|Kindle|Silk-Accelerated|(hpw|web)OS|Opera M(obi|ini)/.test(ua)) {
            var deviceTypes = "mobile";
        } else {
            var deviceTypes = "desktop";
        }

        // cookie
        var cookieEnabled = (navigator.cookieEnabled) ? true : false;

        if (typeof navigator.cookieEnabled == 'undefined' && !cookieEnabled) {
            document.cookie = 'testcookie';
            cookieEnabled = (document.cookie.indexOf('testcookie') != -1) ? true : false;
        }

        // system
        var os = unknown;
        var clientStrings = [
            {s: 'Windows 10', r: /(Windows 10.0|Windows NT 10.0)/},
            {s: 'Windows 8.1', r: /(Windows 8.1|Windows NT 6.3)/},
            {s: 'Windows 8', r: /(Windows 8|Windows NT 6.2)/},
            {s: 'Windows 7', r: /(Windows 7|Windows NT 6.1)/},
            {s: 'Windows Vista', r: /Windows NT 6.0/},
            {s: 'Windows Server 2003', r: /Windows NT 5.2/},
            {s: 'Windows XP', r: /(Windows NT 5.1|Windows XP)/},
            {s: 'Windows 2000', r: /(Windows NT 5.0|Windows 2000)/},
            {s: 'Windows ME', r: /(Win 9x 4.90|Windows ME)/},
            {s: 'Windows 98', r: /(Windows 98|Win98)/},
            {s: 'Windows 95', r: /(Windows 95|Win95|Windows_95)/},
            {s: 'Windows NT 4.0', r: /(Windows NT 4.0|WinNT4.0|WinNT|Windows NT)/},
            {s: 'Windows CE', r: /Windows CE/},
            {s: 'Windows 3.11', r: /Win16/},
            {s: 'Android', r: /Android/},
            {s: 'Open BSD', r: /OpenBSD/},
            {s: 'Sun OS', r: /SunOS/},
            {s: 'Chrome OS', r: /CrOS/},
            {s: 'Linux', r: /(Linux|X11(?!.*CrOS))/},
            {s: 'iOS', r: /(iPhone|iPad|iPod)/},
            {s: 'Mac OS X', r: /Mac OS X/},
            {s: 'Mac OS', r: /(Mac OS|MacPPC|MacIntel|Mac_PowerPC|Macintosh)/},
            {s: 'QNX', r: /QNX/},
            {s: 'UNIX', r: /UNIX/},
            {s: 'BeOS', r: /BeOS/},
            {s: 'OS/2', r: /OS\/2/},
            {s: 'Search Bot', r: /(nuhk|Googlebot|Yammybot|Openbot|Slurp|MSNBot|Ask Jeeves\/Teoma|ia_archiver)/}
        ];
        for (var id in clientStrings) {
            var cs = clientStrings[id];
            if (cs.r.test(nAgt)) {
                os = cs.s;
                break;
            }
        }

        var osVersion = unknown;

        if (/Windows/.test(os)) {
            osVersion = /Windows (.*)/.exec(os)[1];
            os = 'Windows';
        }

        switch (os) {
            case 'Mac OS':
            case 'Mac OS X':
            case 'Android':
                osVersion = /(?:Android|Mac OS|Mac OS X|MacPPC|MacIntel|Mac_PowerPC|Macintosh) ([\.\_\d]+)/.exec(nAgt)[1];
                break;

            case 'iOS':
                osVersion = /OS (\d+)_(\d+)_?(\d+)?/.exec(nVer);
                osVersion = osVersion[1] + '.' + osVersion[2] + '.' + (osVersion[3] | 0);
                break;
        }

        // flash (you'll need to include swfobject)
        /* script src="//ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js" */
        var flashVersion = 'no check';
        if (typeof swfobject != 'undefined') {
            var fv = swfobject.getFlashPlayerVersion();
            if (fv.major > 0) {
                flashVersion = fv.major + '.' + fv.minor + ' r' + fv.release;
            }
            else {
                flashVersion = unknown;
            }
        }
    }
    window.jscd = {
        screen: screenSize,
        browser: browser,
        browserVersion: version,
        browserMajorVersion: majorVersion,
        mobile: mobile,
        deviceType: deviceTypes,
        os: os,
        osVersion: osVersion,
        cookies: cookieEnabled,
        flashVersion: flashVersion
    };
}(this));
var browserName = jscd.browser;
var browserVersion = jscd.browserMajorVersion;
var osName = jscd.os;
var osVersion = jscd.osVersion;
var deviceType = jscd.deviceType;

var companyTrackingID = null;
var partnerScript = document.getElementById("partner-tracking-script");
if (partnerScript) {
    companyTrackingID = document.getElementById('partner-tracking-script').className;
}

var projectID = getCookie("projectID");
var searchParams = new URLSearchParams(window.location.search);
let projectIDKey = document.getElementById('projectIDKey').value;
if (searchParams.has('projectID')) {
    projectIDKey = searchParams.get('projectID');
}
if (projectIDKey) {
    setCookie("projectID", projectIDKey, 30);
    projectID = getCookie("projectID");
    setCookie("projectPopupShow", 1, 15);
    setTimeout(function () {
        //show_popup();
    }, 1000);
} else {
    setTimeout(function () {
        //show_popup();
    }, 1000);
}
if (projectID == "") {
    //show_popup();
} else {
    jQuery.ajax({
        type: 'POST',
        dataType: "json",
        url: 'https://crm.eternitech.com/get-project-details',
        data: {projectID: getCookie("projectID")},
        success: function (response) {
            if (response.status) {
                if (response.data.company.email == "" || response.data.company.phone == "") {
                    setTimeout(function () {
                        document.getElementById('company-id').value = response.data.company.id;
                        document.getElementById('content-message-string').innerHTML = response.data.contentMessageString;
                        /*document.getElementById('user-name').innerText = response.data.company.name;
                        document.getElementById('project-name').innerText	 = response.data.project.name;*/
                        let projectPopupShow = getCookie("projectPopupShow");
                        if (projectPopupShow == 1) {
                            setTimeout(function () {
                                show_projectpopup();
                            }, 30000);
                        }
                    }, 1500);
                }
            }
        }
    });
}

document.addEventListener("click", function (event) {
    let type = event.target.tagName.toLowerCase();
    let Link = (event.target.closest('a')) ? event.target.closest('a').href : '' || '';
    let tdText = "";
    let title = event.target.innerText;
    if (title == "") {
        title = document.title;
    }
    if (Link) {
        type = "Link";
        tdText = Link;
    } else {
        if (type == "") {
            type = "button";
        }
        tdText = window.location.href;
    }
    if (Link != "" || type == "button") {
        if (projectID != "") {
            event.preventDefault();
            jQuery.ajax({
                type: 'POST',
                dataType: "json",
                url: 'https://crm.eternitech.com/frontend-activity',
                data: {
                    projectID: getCookie("projectID"),
                    tdText: tdText,
                    type: type,
                    title: title,
                    browserName: browserName,
                    osName: osName,
                    browserVersion: browserVersion,
                    osVersion: osVersion,
                    deviceType: deviceType,
                    companyID: companyTrackingID
                },
                success: function (response) {
                    //console.log(response);
                }
            });
            if (type != "button") {
                //console.log(Link);
                window.location = Link;
            }
        }
    }
});

function setCookie(cname, cvalue, exdays) {
    const d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    let expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
    let name = cname + "=";
    let decodedCookie = decodeURIComponent(document.cookie);
    let ca = decodedCookie.split(';');
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function check_empty() {
    if (document.getElementById('company-name').value == "" || document.getElementById('company-email').value == "") {
        alert("Fill All Fields !");
    } else {
        let companyName = document.getElementById('company-name').value;
        let companyEmail = document.getElementById('company-email').value;
        if (companyName != "" && companyEmail != "") {
            /*jQuery.ajax({
                type: 'POST',
                dataType:  "json",
                url: 'https://crm.eternitech.com/frontend-activity',
                data: {companyName: companyName, companyEmail: companyEmail},
                success: function (response) {
                    //console.log(response);
                }
            });*/
        }
    }
}

function validatePhone (phoneValue) {
    return phoneValue.match(
        /((?:\+|00)[17](?: |\-)?|(?:\+|00)[1-9]\d{0,2}(?: |\-)?|(?:\+|00)1\-\d{3}(?: |\-)?)?(0\d|\([0-9]{3}\)|[1-9]{0,3})(?:((?: |\-)[0-9]{2}){4}|((?:[0-9]{2}){4})|((?: |\-)[0-9]{3}(?: |\-)[0-9]{4})|([0-9]{7}))/g
    );
}

function validateEmail(emailValue) {
    return emailValue.match(
        /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/g
    );
}

function showError(msg) {
    document.getElementById('error-msg').innerHTML = msg;
}

function check_projectempty(callback, validate = true) {
    if (typeof callback === 'undefined') {
        callback = function (success) {
            window.top.location = "/";
        };
    }
    let projectPhone = document.getElementById('project-phone') ? document.getElementById('project-phone').value : '';
    let projectEmail = document.getElementById('project-email') ? document.getElementById('project-email').value : '';
    let clientName = document.getElementById('client-name') ? document.getElementById('client-name').value : '';
    let clientMessage = document.getElementById('client-message') ? document.getElementById('client-message').value : '';
    let currentUrl = window.location.href;

    if (validate) {
        if (projectPhone == "" && projectEmail == "") {
            showError("Fill at least one field (email / phone)");
            callback(false);
            return false;
        }

        if (projectEmail && !validateEmail(projectEmail)) {
            showError("Invalid Email Address");
            callback(false);
            return false;
        }

        if (projectPhone && !validatePhone(projectPhone)) {
            showError("Invalid Phone Number");
            callback(false);
            return false;
        }
    }

    let projectId = getCookie("projectID");
    let companyID = document.getElementById('company-id').value;

    jQuery.ajax({
        type: 'POST',
        dataType: "json",
        url: 'https://crm.eternitech.com/set-project-details',
        data: {
            currentUrl: currentUrl,
            clientName: clientName,
            clientMessage: clientMessage,
            companyID: companyID,
            companyPhone: projectPhone,
            companyEmail: projectEmail,
            projectID: projectId,
            browserName: browserName,
            osName: osName,
            browserVersion: browserVersion,
            osVersion: osVersion,
            deviceType: deviceType
        },
        success: function (response) {
           callback(response.status);
        }
    });
}

//Function To Display Popup
function show_popup() {
    document.getElementById('popup-company').style.display = "block";
}

//Function to Hide Popup
function hide_popup() {
    document.getElementById('popup-company').style.display = "none";
}

function show_projectpopup() {
    setCookie("projectPopupShow", 0, 15);
    document.body.style.overflow = 'hidden';
    var modal = document.getElementById("projectModal");
    modal.style.display = "block";
}

//Function to Hide Popup
function hide_projectpopup() {
    setCookie("projectPopupShow", 0, 15);
    document.body.style.overflow = 'auto';
    var modal = document.getElementById("projectModal");
    modal.style.display = "none";
}

//for Google Analytics Code
var jQueryScript = document.createElement('script');
jQueryScript.setAttribute('src', 'https://www.googletagmanager.com/gtag/js?id=UA-56786779-1');
jQueryScript.setAttribute('async', true);
document.head.appendChild(jQueryScript);

if (projectID != "") {
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }

    gtag('js', new Date());

    var dimensionValue = getCookie("projectID");
    gtag('config', 'UA-56786779-1', {
        'custom_map': {'dimension2': 'projectID'}
    });
    gtag('event', ' project_dimension', {'projectID': dimensionValue});
}
