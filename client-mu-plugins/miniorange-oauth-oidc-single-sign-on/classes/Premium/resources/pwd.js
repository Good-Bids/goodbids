var app_name_master = "";
var is_test_master = false;
var is_sso_master = false;
var base_url_master = "";

function moOAuthLoginPwd(app_name, is_test, base_url) {
    jQuery("#password-grant-modal").show();
    jQuery("#password-grant-modal").animate({
        opacity: '1'
    }, 'fast');
    app_name_master = app_name;
    is_test_master = is_test;
    base_url_master = trailingSlashIt(base_url);
    jQuery("#password-modal-header-title-text").text("Login to your " + app_name + " Account");
}

jQuery(document).ready(function() {
    jQuery("#pwdgrntfrm-login").on( "click", function() {
        var url = jQuery(location).attr("href");

        var is_sso = url.indexOf('option=oauthredirect') !== -1 && ! is_test_master;
        if(is_sso) {
            jQuery(document).keyup(function(e) {
                if (e.key === "Escape") { // escape key maps to keycode `27`
                    jQuery("#password-grant-modal").hide();
            }
        });
        }
        const unm = encHere(jQuery("#pwdgrntfrm-unmfld").val());
        const pwd = encHere(jQuery("#pwdgrntfrm-pfld").val());
        jQuery("#password-grant-modal").hide();
        
        var axon = "?option=pwdredirect&app_name=" + app_name_master + "&login=pwdgrntfrm&caller=" + unm + "&tool=" + pwd;
        jQuery("#pwdgrntfrm").append('<input type="hidden" name="location" value="' + encodeURIComponent(getProperLocation(url)) +'" />');
        jQuery("#pwdgrntfrm").append('<input type="hidden" name="app_name" value="' + app_name_master +'" />');
        jQuery("#pwdgrntfrm-unmfld").val(unm);
        jQuery("#pwdgrntfrm-pfld").val(pwd);
        jQuery("#pwdgrntfrm").attr("method", "post");
        if(is_sso) {
            jQuery("#pwdgrntfrm").attr("action", base_url_master);
        }
        if(is_test_master) {
            var myWindow = window.open("", "Test Configuration", "height=600,width=600");
            jQuery("#pwdgrntfrm").append('<input type="hidden" name="test" value="true" />');
            jQuery("#pwdgrntfrm").attr("target", "Test Configuration");
            jQuery("#password-grant-modal").hide();
        } else {
            axon = axon + "&location=" + encodeURIComponent(getProperLocation(url));
        }
        jQuery("#pwdgrntfrm").submit();
        jQuery("#pwdgrntfrm-unmfld").val("");
        jQuery("#pwdgrntfrm-pfld").val("");
    });
});

jQuery(".password-modal-close").click(function() {
    jQuery("#password-grant-modal").animate({
        opacity: '0',
    }, function() {
        jQuery("#password-grant-modal").hide();
    });
});

function getProperLocation(url) {

    proper_url = '';
    if(url.includes('option=oauthredirect')) {
        proper_url = url.split("?")[0];
    }
    if (proper_url.substr(-1) !== '/' && !proper_url.includes("?")) {
        proper_url = url + '/';
    }
    return proper_url;
}

function trailingSlashIt(url) {
    if (url.substr(-1) !== '/' && !url.includes("?")) {
        url = url + '/';
    }
    return url;
}

function encHere(str) {
  var i = 0, l = str.length, chr, hex = ''
  for (i; i < l; ++i) {
    chr = str.charCodeAt(i).toString(16);
    hex += chr.length < 2 ? '0' + chr : chr;
  }
  return hex;
}