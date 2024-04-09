<?php


use MoOauthClient\MO_Oauth_Debug;
function mo_oauth_client_auto_redirect_external_after_wp_logout($QH)
{
    MO_Oauth_Debug::mo_oauth_log("\111\x6e\163\x69\144\x65\x20\x77\x70\40\x6c\x6f\x67\157\x75\x74");
    global $Yh;
    $Wb = $Yh->get_plugin_config();
    if (!has_action("\155\x6f\x5f\x6f\x61\165\x74\150\137\x64\145\163\164\x72\157\x79\x5f\x74\157\153\x65\x6e\x5f\x63\157\157\x6b\151\145")) {
        goto WgQ;
    }
    do_action("\155\x6f\137\157\141\x75\x74\x68\x5f\144\145\163\x74\x72\157\171\137\164\x6f\x6b\145\x6e\137\x63\157\157\153\151\145");
    WgQ:
    if (!(!empty($Wb->get_config("\141\x66\x74\145\x72\x5f\x6c\157\147\157\x75\x74\137\x75\x72\x6c")) && (isset($_COOKIE["\155\157\137\157\x61\165\164\150\137\x6c\x6f\x67\x69\x6e\137\141\x70\160\x5f\x73\x65\163\x73\x69\157\x6e"]) && $_COOKIE["\x6d\157\x5f\x6f\141\x75\164\x68\137\x6c\157\x67\x69\156\137\141\160\160\x5f\x73\x65\x73\x73\151\x6f\156"] != "\156\157\x6e\145"))) {
        goto Frj;
    }
    $user = get_userdata($QH);
    $Aq = $Wb->get_config("\141\146\164\x65\x72\x5f\154\x6f\x67\x6f\165\164\x5f\x75\x72\154");
    MO_Oauth_Debug::mo_oauth_log("\165\x73\145\162\40\x3d\x3d\76\40");
    MO_Oauth_Debug::mo_oauth_log($QH);
    $A2 = get_user_meta($QH, "\x6d\157\x5f\157\x61\x75\x74\150\x5f\x63\x6c\x69\x65\x6e\x74\x5f\x6c\x61\163\x74\137\151\144\x5f\164\157\153\x65\x6e", true);
    MO_Oauth_Debug::mo_oauth_log("\x69\x64\x20\x74\157\153\x65\156\x20\75\75\76\x20");
    MO_Oauth_Debug::mo_oauth_log($A2);
    $Aq = str_replace("\x23\43\151\x64\137\x74\157\153\x65\x6e\43\43", $A2, $Aq);
    $Aq = str_replace("\x23\43\x6d\157\137\x70\x6f\x73\164\137\x6c\157\147\157\165\164\x5f\x75\162\151\x23\x23", site_url(), $Aq);
    do_action("\155\x6f\137\x6f\x61\x75\164\150\137\x72\x65\x64\x69\x72\x65\x63\164\x5f\157\x61\165\164\150\x5f\165\163\145\x72\x73", $user, $Aq);
    setcookie("\155\157\x5f\157\141\165\164\150\137\154\x6f\147\151\x6e\137\141\160\x70\137\x73\x65\163\163\151\x6f\156", "\156\x6f\x6e\145", null, "\x2f", null, true, true);
    MO_Oauth_Debug::mo_oauth_log("\x52\x65\x64\151\x72\145\x63\164\x20\125\122\114\40\x61\x66\164\x65\x72\x20\x6c\x6f\147\157\165\x74\x3a\x20" . $Aq);
    wp_redirect($Aq);
    exit;
    Frj:
}
function mo_oauth_client_auto_redirect_external_after_logout($Y7, $t2, $user)
{
    MO_Oauth_Debug::mo_oauth_log("\111\156\x73\151\144\x65\40\x6c\x6f\147\157\165\164\x20\x72\x65\144\151\162\145\x63\x74");
    $Yh = new \MoOauthClient\Standard\MOUtils();
    $Wb = $Yh->get_plugin_config();
    if (!(!empty($Wb->get_config("\141\146\x74\145\162\137\154\157\x67\x6f\x75\164\x5f\165\x72\154")) && (isset($_COOKIE["\155\157\137\157\141\x75\164\150\137\x6c\x6f\147\x69\x6e\137\141\x70\x70\x5f\x73\145\163\x73\x69\157\x6e"]) && $_COOKIE["\x6d\157\x5f\x6f\x61\165\164\150\137\154\157\x67\x69\x6e\x5f\141\160\160\x5f\163\145\163\163\151\157\156"] != "\156\x6f\156\x65"))) {
        goto Cw_;
    }
    $Aq = $Wb->get_config("\x61\146\x74\145\162\137\154\x6f\x67\x6f\x75\164\x5f\x75\162\154");
    $QH = $user->ID;
    $A2 = get_user_meta($QH, "\x6d\x6f\137\x6f\x61\165\x74\150\137\x63\x6c\151\145\156\x74\137\154\x61\x73\x74\137\x69\x64\137\164\157\153\x65\156", true);
    $Aq = str_replace("\43\x23\151\x64\137\x74\x6f\x6b\x65\156\43\43", $A2, $Aq);
    $Aq = str_replace("\x23\43\x6d\157\137\x70\157\x73\x74\x5f\x6c\x6f\147\x6f\165\164\x5f\x75\162\x69\x23\43", site_url(), $Aq);
    do_action("\155\157\137\x6f\x61\165\x74\150\x5f\162\x65\144\151\162\145\143\164\x5f\157\141\x75\x74\150\137\165\163\145\x72\x73", $user, $Aq);
    setcookie("\x6d\x6f\137\x6f\x61\165\x74\150\137\154\x6f\147\x69\156\x5f\141\x70\160\x5f\163\145\163\x73\151\157\156", "\156\x6f\156\x65", null, "\x2f", null, true, true);
    wp_redirect($Aq);
    exit;
    Cw_:
    return $Y7;
}
add_filter("\x77\x70\137\154\x6f\147\x6f\165\164", "\155\x6f\x5f\x6f\141\165\164\150\x5f\143\x6c\x69\x65\156\164\x5f\141\165\164\157\x5f\162\x65\144\151\162\145\143\164\137\145\170\164\145\x72\x6e\x61\154\137\x61\146\x74\145\162\x5f\167\x70\137\154\x6f\147\157\x75\164", 10, 1);
add_filter("\154\157\x67\x6f\165\164\137\162\x65\x64\151\x72\145\x63\164", "\155\157\137\157\141\165\x74\x68\137\143\154\x69\145\x6e\164\x5f\141\x75\x74\x6f\x5f\162\145\x64\x69\162\145\143\164\137\145\170\x74\145\162\x6e\141\154\x5f\x61\146\x74\x65\162\137\x6c\x6f\147\157\x75\x74", 10, 3);
