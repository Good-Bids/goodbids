<?php


use MoOauthClient\MO_Oauth_Debug;
function mo_oauth_client_auto_redirect_external_after_wp_logout($TV)
{
    MO_Oauth_Debug::mo_oauth_log("\111\156\163\x69\144\x65\x20\167\x70\x20\x6c\157\x67\x6f\165\164");
    global $Uj;
    $Kn = $Uj->get_plugin_config();
    if (!(!empty($Kn->get_config("\141\x66\x74\145\x72\137\x6c\x6f\147\157\165\x74\137\165\162\x6c")) && (isset($_COOKIE["\155\x6f\x5f\157\141\165\164\x68\x5f\154\x6f\147\x69\x6e\x5f\141\160\160\x5f\163\145\163\x73\151\x6f\156"]) && $_COOKIE["\155\x6f\137\x6f\x61\x75\164\x68\x5f\154\157\147\x69\x6e\x5f\x61\160\x70\137\163\145\x73\163\x69\x6f\x6e"] != "\156\x6f\x6e\145"))) {
        goto Ao0;
    }
    $yw = $Kn->get_config("\x61\146\x74\145\162\x5f\x6c\x6f\147\x6f\165\x74\x5f\165\x72\x6c");
    MO_Oauth_Debug::mo_oauth_log("\x75\x73\x65\x72\x20\x3d\x3d\x3e\40");
    MO_Oauth_Debug::mo_oauth_log($TV);
    $DU = get_user_meta($TV, "\x6d\x6f\137\x6f\x61\165\x74\150\x5f\x63\x6c\x69\145\x6e\x74\x5f\x6c\141\x73\x74\x5f\151\144\x5f\164\157\x6b\145\x6e", true);
    MO_Oauth_Debug::mo_oauth_log("\x69\x64\40\x74\x6f\153\145\156\x20\75\75\x3e\x20");
    MO_Oauth_Debug::mo_oauth_log($DU);
    $yw = str_replace("\43\x23\x69\x64\x5f\x74\157\153\145\x6e\43\43", $DU, $yw);
    do_action("\155\x6f\137\157\x61\x75\164\x68\x5f\162\145\x64\x69\162\x65\x63\x74\137\x6f\x61\165\164\x68\x5f\x75\163\x65\x72\163", $user, $yw);
    wp_redirect($yw);
    exit;
    Ao0:
    setcookie("\155\x6f\x5f\x6f\141\x75\x74\150\x5f\154\x6f\x67\x69\x6e\x5f\x61\160\160\x5f\x73\145\163\163\151\157\x6e", "\156\157\x6e\x65");
}
function mo_oauth_client_auto_redirect_external_after_logout($Xt, $U9, $user)
{
    $Uj = new \MoOauthClient\Standard\MOUtils();
    $Kn = $Uj->get_plugin_config();
    if (!(!empty($Kn->get_config("\x61\146\164\x65\162\x5f\154\157\147\x6f\x75\164\x5f\165\162\154")) && (isset($_COOKIE["\155\x6f\137\x6f\x61\165\x74\150\137\x6c\157\x67\x69\x6e\137\x61\x70\160\137\x73\x65\163\x73\x69\x6f\x6e"]) && $_COOKIE["\155\x6f\137\157\x61\x75\164\150\137\154\157\x67\151\156\x5f\x61\160\160\x5f\x73\x65\x73\163\151\157\x6e"] != "\x6e\157\156\x65"))) {
        goto agh;
    }
    $yw = $Kn->get_config("\x61\146\164\x65\162\x5f\x6c\157\147\x6f\165\164\x5f\x75\162\154");
    $TV = $user->ID;
    $DU = get_user_meta($TV, "\155\x6f\x5f\x6f\x61\165\x74\150\x5f\143\154\151\145\x6e\x74\x5f\x6c\x61\163\x74\137\x69\x64\137\x74\157\x6b\x65\x6e", true);
    $yw = str_replace("\43\43\x69\x64\x5f\x74\x6f\x6b\x65\156\x23\43", $DU, $yw);
    do_action("\x6d\157\137\x6f\x61\165\x74\150\137\x72\x65\x64\151\x72\145\143\164\137\x6f\141\x75\x74\x68\137\165\163\x65\x72\163", $user, $yw);
    wp_redirect($yw);
    exit;
    agh:
    setcookie("\155\157\x5f\157\x61\165\x74\x68\x5f\x6c\x6f\x67\x69\x6e\137\x61\160\160\137\x73\145\x73\x73\x69\157\156", "\x6e\x6f\156\x65");
    return $Xt;
}
add_filter("\167\x70\137\154\x6f\147\x6f\165\x74", "\x6d\x6f\x5f\157\x61\x75\164\x68\137\x63\x6c\151\x65\156\x74\137\x61\165\164\x6f\x5f\x72\145\144\x69\x72\145\x63\x74\x5f\x65\x78\x74\x65\x72\x6e\141\154\x5f\x61\x66\164\x65\162\137\x77\x70\137\x6c\x6f\x67\x6f\x75\x74", 10, 1);
add_filter("\x6c\x6f\147\157\165\164\x5f\x72\x65\x64\151\162\145\x63\x74", "\155\x6f\137\157\141\x75\164\x68\x5f\x63\x6c\151\145\x6e\x74\x5f\141\x75\164\157\x5f\162\x65\x64\151\x72\145\143\164\x5f\x65\x78\164\x65\162\156\x61\x6c\137\x61\146\x74\x65\162\x5f\154\x6f\147\157\x75\x74", 10, 3);
