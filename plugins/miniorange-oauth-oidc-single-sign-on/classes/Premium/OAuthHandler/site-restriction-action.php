<?php


use MoOauthClient\Mo_Oauth_Debug;
function mo_oauth_client_page_restriction()
{
    if (!(defined("\x57\x50\x5f\103\114\111") && WP_CLI)) {
        goto xoO;
    }
    return;
    xoO:
    ob_start();
    global $Yh;
    $Wb = $Yh->get_plugin_config()->get_current_config();
    $uw = true;
    $uw = $Yh->mo_oauth_aemoutcrahsaphtn() == "\x65\x6e\x61\142\154\145\144" ? false : true;
    $Wb = $Yh->get_plugin_config();
    $tA = $Wb->get_config("\x72\x65\163\164\x72\151\x63\164\x5f\164\157\x5f\x6c\157\147\x67\x65\x64\x5f\151\156\137\165\x73\x65\x72\x73");
    $tA = '' !== $tA ? $tA : false;
    if (!((strpos($Yh->get_current_url(), "\57\167\160\55\154\157\x67\x69\x6e\56\x70\x68\x70") || strpos($Yh->get_current_url(), "\57\x77\x70\55\141\144\x6d\151\156")) && !$uw)) {
        goto s46;
    }
    return;
    s46:
    $Xi = $Wb->get_config("\151\x6e\x76\145\162\x73\x65\137\162\x65\x73\x74\x72\151\143\x74\137\x74\x6f\x5f\154\157\147\147\x65\144\x5f\151\x6e\x5f\x75\163\x65\162\x73");
    $Xi = '' !== $Xi ? $Xi : false;
    $YN = $Wb->get_config("\141\165\x74\157\x5f\x72\145\x64\151\x72\145\x63\164\137\145\x78\x63\x6c\165\x64\145\137\x75\x72\x6c\163");
    if (!(!is_user_logged_in() && boolval($tA) && !strpos($Yh->get_current_url(), "\57\163\x63\151\155"))) {
        goto RWY;
    }
    MO_Oauth_Debug::mo_oauth_log("\x46\157\162\x63\x65\144\40\141\165\164\x68\145\156\x74\x69\143\141\x74\x69\157\156\x20\x65\x6e\141\142\154\145\144\x2e");
    if (!(isset($_SERVER["\122\105\121\125\x45\x53\x54\x5f\115\x45\124\110\x4f\104"]) && "\x50\x4f\x53\124" === sanitize_text_field(wp_unslash($_SERVER["\x52\105\121\125\105\123\x54\x5f\115\x45\124\x48\117\x44"])))) {
        goto gcN;
    }
    return;
    gcN:
    if (!(isset($_REQUEST["\157\141\x75\164\x68\154\x6f\x67\151\x6e"]) && "\146\x61\154\x73\145" === sanitize_text_field(wp_unslash($_REQUEST["\x6f\x61\165\164\150\154\x6f\147\x69\156"])) && strpos($Yh->get_current_url(), "\x2f\167\x70\55\154\157\147\151\x6e\x2e\160\x68\160"))) {
        goto aSt;
    }
    $nS = false;
    $nS = apply_filters("\x44\111\123\x41\x42\x4c\105\x5f\x4f\x41\125\x54\110\x4c\117\107\x49\116\x5f\102\101\x43\113\x44\117\117\122", $nS);
    if (!($nS == false)) {
        goto cze;
    }
    return;
    cze:
    aSt:
    if (!(isset($_REQUEST[\MoOAuthConstants::OPTION]) && "\x6f\x61\x75\164\x68\162\145\144\151\162\145\143\x74" === sanitize_text_field(wp_unslash($_REQUEST[\MoOAuthConstants::OPTION])))) {
        goto lcJ;
    }
    return;
    lcJ:
    if (!(isset($_REQUEST["\143\157\x64\x65"]) && '' !== $_REQUEST["\143\x6f\x64\145"])) {
        goto Bz8;
    }
    return;
    Bz8:
    if (!(isset($_REQUEST["\x61\143\143\145\x73\163\x5f\x74\x6f\153\x65\x6e"]) && '' !== $_REQUEST["\x61\143\x63\145\x73\x73\137\x74\157\x6b\x65\156"])) {
        goto hBY;
    }
    return;
    hBY:
    if (!(isset($_REQUEST["\x6c\157\x67\151\156"]) && "\x70\x77\x64\147\162\x6e\x74\146\162\155" === sanitize_text_field(wp_unslash($_REQUEST["\154\x6f\147\151\156"])))) {
        goto c4J;
    }
    return;
    c4J:
    if (!(isset($_REQUEST["\145\x72\162\x6f\x72"]) && '' !== $_REQUEST["\x65\162\x72\x6f\162"] || isset($_REQUEST["\145\162\162\157\x72\x5f\x64\x65\x73\x63\162\151\160\164\x69\x6f\x6e"]) && '' !== $_REQUEST["\x65\162\162\157\x72\137\144\x65\163\143\x72\x69\160\164\151\157\156"])) {
        goto zoq;
    }
    return;
    zoq:
    $k7 = $Yh->get_all_headers();
    if (!(isset($k7["\101\103\x43\105\120\x54"]) && "\x61\160\160\x6c\151\143\141\164\151\x6f\156\57\152\163\x6f\156" === $k7["\x41\103\x43\x45\120\124"])) {
        goto ktf;
    }
    return;
    ktf:
    if (empty($YN)) {
        goto hnw;
    }
    $Nd = $Yh->get_app_by_name();
    if ($Nd) {
        goto Vi3;
    }
    return;
    Vi3:
    $cF = $Yh->get_current_url();
    $cF = trim($cF, "\x2f");
    $AA = apply_filters("\155\157\x5f\157\141\165\164\150\137\x63\x6c\151\145\156\164\137\x66\x6f\x72\143\x65\144\137\x6c\157\147\151\x6e\x5f\x65\x78\143\154\165\144\145\137\x75\162\154\163", $YN, $cF);
    if (!($AA === true)) {
        goto h31;
    }
    return;
    h31:
    $YN = explode("\12", $YN);
    MO_Oauth_Debug::mo_oauth_log("\x45\x78\x63\154\x75\144\145\x64\40\x55\x52\114\163\40\x3d\x3e\40");
    MO_Oauth_Debug::mo_oauth_log($YN);
    foreach ($YN as $UC) {
        if (!ctype_space(substr($UC, -1))) {
            goto zTh;
        }
        $UC = substr_replace($UC, '', -1);
        zTh:
        $UC = trim(trim($UC), "\x2f");
        if (!(substr($UC, -1) == "\x2a")) {
            goto pqy;
        }
        $UC = substr_replace($UC, '', -1);
        if (!(strpos($UC, "\52") !== false)) {
            goto Rb2;
        }
        $sU = substr($UC, strpos($UC, "\52") + 1);
        if (!(strpos($cF, $sU) !== false)) {
            goto wuc;
        }
        return;
        wuc:
        Rb2:
        $UC = trim($UC, "\x2f");
        if (!(boolval($Xi) && strpos($cF, $UC) !== false)) {
            goto eHK;
        }
        mo_restrict_login($Nd, $cF);
        eHK:
        if (!(strpos($cF, $UC) !== false)) {
            goto LuU;
        }
        return;
        LuU:
        pqy:
        if (empty($UC)) {
            goto ucE;
        }
        if (!(boolval($Xi) && $cF === $UC)) {
            goto yV9;
        }
        mo_restrict_login($Nd, $cF);
        yV9:
        if (!($cF === $UC)) {
            goto gUa;
        }
        return;
        gUa:
        ucE:
        T3t:
    }
    yIA:
    if (!boolval($Xi)) {
        goto d_E;
    }
    return;
    d_E:
    hnw:
    if (!(!$uw && $tA)) {
        goto sU1;
    }
    wp_safe_redirect(site_url() . "\57\167\160\55\141\144\x6d\x69\x6e");
    exit;
    sU1:
    $Nd = $Yh->get_app_by_name();
    $NI = $Nd->get_app_config("\141\x66\x74\x65\x72\x5f\x6c\x6f\147\x69\156\x5f\x75\162\x6c");
    $cF = $NI ? $NI : $Yh->get_current_url();
    mo_restrict_login($Nd, $cF);
    RWY:
}
add_action("\x69\x6e\151\164", "\155\157\x5f\157\x61\165\x74\150\137\143\x6c\151\145\156\x74\x5f\160\x61\147\145\x5f\x72\x65\163\164\x72\x69\x63\x74\151\157\x6e");
function mo_restrict_login($Nd, $cF)
{
    global $Yh;
    $fS = '';
    $fS = apply_filters("\x6d\157\x5f\x6f\141\x75\164\150\x5f\143\165\163\x74\x6f\155\x69\x7a\145\x5f\x72\145\x64\151\162\x65\x63\x74\x69\157\156\137\155\163\147", $fS);
    header("\x63\141\143\150\x65\x2d\x63\x6f\156\164\x72\157\154\x3a\40\x6d\141\170\x2d\141\147\145\x3d\60\x2c\x20\160\162\x69\166\x61\164\145\x2c\40\x6e\x6f\x2d\x73\x74\x6f\162\x65\54\40\x6e\157\x2d\143\x61\143\x68\x65\x2c\x20\x6d\165\163\x74\x2d\162\145\166\141\154\x69\144\141\x74\x65");
    if (!empty($fS)) {
        goto CE0;
    }
    if (!empty($Yh->mo_oauth_client_get_option("\155\x6f\137\157\x61\165\x74\150\x5f\x63\154\x69\x65\x6e\x74\137\x66\x6f\162\x63\145\144\137\155\x65\x73\x73\141\x67\x65")) && $Yh->mo_oauth_client_get_option("\155\x6f\137\157\x61\165\x74\150\137\143\x6c\x69\x65\x6e\164\x5f\146\157\162\x63\145\x64\x5f\x6d\x65\x73\163\141\147\x65") != '') {
        goto kx_;
    }
    echo "\122\145\144\x69\162\145\143\164\x69\156\147\40\x74\x6f\x20\144\145\146\141\165\x6c\x74\x20\154\157\x67\x69\156\x2e\x2e";
    goto uFn;
    CE0:
    echo $fS;
    goto uFn;
    kx_:
    echo $Yh->mo_oauth_client_get_option("\x6d\x6f\x5f\157\x61\x75\x74\x68\137\x63\x6c\151\145\156\x74\137\146\x6f\x72\143\145\144\137\x6d\x65\163\163\x61\147\x65");
    uFn:
    echo "\11\11\x3c\x73\x63\x72\x69\x70\x74\76\xd\xa\11\11\x9\x69\x66\50\40\x77\x69\156\144\157\x77\56\x6c\x6f\143\x61\x74\151\157\156\56\150\162\145\146\56\151\x6e\x64\145\170\117\x66\50\x22\x23\141\143\143\145\x73\x73\137\x74\x6f\x6b\145\x6e\x22\51\x3e\x2d\61\x20\174\174\x20\x77\151\156\144\157\x77\56\154\157\x63\141\x74\x69\157\156\x2e\x68\162\145\x66\56\151\156\x64\145\x78\117\x66\x28\42\x23\x63\x6f\144\145\42\51\76\x2d\x31\x20\x7c\x7c\x20\x77\151\156\144\157\167\x2e\154\x6f\143\141\x74\x69\x6f\156\x2e\150\162\145\146\x2e\x69\156\x64\x65\170\x4f\146\x28\42\43\151\144\x5f\164\157\x6b\x65\x6e\42\51\x3e\55\61\x20\51\x20\173\xd\xa\x9\11\x9\11\166\x61\162\x20\165\x72\x6c\x20\75\x20\x77\x69\x6e\x64\x6f\167\56\x6c\x6f\143\x61\x74\x69\x6f\x6e\x2e\x74\157\x53\164\162\x69\156\x67\x28\51\73\xd\12\11\x9\11\11\x77\x69\x6e\x64\157\167\x2e\154\157\x63\x61\164\151\157\x6e\x20\x3d\40\165\x72\x6c\56\162\x65\x70\x6c\x61\143\x65\50\x22\x23\x22\54\x20\47\77\x27\51\73\15\12\11\11\11\x7d\x20\xd\xa\11\11\11\145\154\163\x65\40\x69\146\50\x28\167\151\156\x64\x6f\x77\56\154\157\x63\x61\x74\151\157\x6e\56\150\162\145\146\56\x69\x6e\x64\145\x78\117\146\50\42\x23\x73\164\141\164\x65\x22\x29\x3e\55\x31\x20\x26\46\40\x28\167\x69\156\x64\157\167\x2e\154\x6f\x63\x61\164\151\x6f\156\56\150\x72\x65\146\x2e\151\156\x64\145\x78\x4f\x66\50\42\x65\x72\162\x6f\162\42\x29\76\55\x31\x20\174\x7c\x20\x77\x69\x6e\144\x6f\167\x2e\154\157\x63\141\x74\x69\x6f\156\x2e\150\x72\145\x66\x2e\151\156\x64\x65\x78\x4f\x66\x28\42\x65\162\162\x6f\x72\x5f\x64\145\163\143\162\x69\160\164\151\x6f\x6e\42\51\76\x2d\x31\51\x29\x20\x7c\174\x20\x77\151\x6e\144\157\167\x2e\154\157\143\141\x74\x69\157\156\x2e\x68\162\145\146\x2e\x69\x6e\144\x65\170\117\146\50\x22\x23\x65\x72\x72\x6f\162\x22\x29\x3e\x2d\61\x20\174\174\40\167\151\x6e\144\x6f\167\x2e\x6c\x6f\x63\x61\x74\151\157\x6e\x2e\150\162\145\146\56\151\x6e\144\x65\x78\117\x66\x28\x22\43\145\x72\x72\x6f\162\137\x64\145\x73\x63\x72\151\x70\x74\151\157\156\x22\51\76\x2d\61\40\51\x20\x7b\x9\15\12\11\11\x9\11\166\141\x72\40\165\x72\x6c\40\x3d\x20\167\x69\156\x64\x6f\x77\x2e\154\157\143\141\x74\151\x6f\x6e\56\164\x6f\123\x74\x72\151\x6e\147\50\x29\73\15\12\x9\x9\11\x9\167\151\x6e\x64\x6f\x77\56\154\157\x63\141\164\x69\157\156\40\75\x20\x75\x72\154\56\162\145\160\x6c\141\x63\145\x28\x22\43\x22\x2c\x20\x27\x3f\x27\51\73\15\12\11\x9\x9\175\x20\xd\xa\11\11\11\x65\154\163\145\x20\x69\146\50\40\x28\x20\x77\151\156\144\x6f\x77\x2e\x6c\x6f\143\x61\164\151\157\x6e\56\x68\162\145\146\56\151\156\x64\x65\170\x4f\146\50\42\43\42\51\40\x3e\x20\55\61\x20\x29\40\51\40\173\x9\xd\xa\11\11\x9\x9\166\x61\x72\40\165\162\x6c\x20\75\40\167\x69\x6e\144\157\x77\56\x6c\x6f\143\141\164\151\x6f\156\56\164\x6f\123\x74\162\x69\156\x67\x28\x29\73\15\12\11\x9\x9\x9\x77\x69\156\144\157\x77\56\154\x6f\x63\141\x74\151\x6f\156\x20\75\x20\x75\162\154\x2e\x72\x65\x70\154\x61\x63\145\x28\42\x23\42\54\x20\47\x6d\x6f\x5f\143\x68\141\156\x67\x65\137\x74\157\x5f\x68\141\x73\x68\47\x29\73\xd\xa\x9\x9\x9\175\x20\145\x6c\x73\145\40\x7b\15\12\11\x9\11\11\166\x61\x72\x20\165\162\x6c\40\75\40\x22";
    echo esc_url(site_url());
    echo "\42\x3b\15\xa\11\11\11\x9\x75\x72\x6c\40\75\x20\165\x72\x6c\40\53\40\47\x2f\77\x6f\160\164\151\x6f\156\75\157\141\x75\164\150\x72\x65\144\151\162\x65\143\164\46\x61\160\x70\137\x6e\x61\x6d\145\x3d\47\x20\53\40\x22";
    echo wp_kses($Nd->get_app_name(), \mo_oauth_get_valid_html());
    echo "\x22\40\x2b\x20\x27\x26\x72\x65\144\151\162\x65\143\x74\x5f\x75\x72\x6c\x3d\47\40\x2b\40\42";
    echo rawurlencode($cF);
    echo "\x22\x20\x2b\40\47\46\162\x65\x73\x74\162\x69\x63\x74\x72\145\144\x69\x72\145\143\164\75\164\x72\165\145\x27\x3b\15\xa\x9\x9\11\x9\x77\151\x6e\144\x6f\x77\56\x6c\x6f\x63\x61\164\x69\157\x6e\56\x72\145\x70\x6c\141\x63\145\x28\x20\165\x72\154\40\x29\x3b\15\xa\11\x9\11\175\15\12\x9\11\x3c\x2f\163\143\162\x69\x70\x74\76\15\xa\11\11";
    exit;
}
