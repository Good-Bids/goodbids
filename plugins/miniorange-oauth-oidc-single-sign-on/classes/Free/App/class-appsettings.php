<?php


namespace MoOauthClient\Free;

use MoOauthClient\App;
class AppSettings
{
    private $app_config;
    public function __construct()
    {
        $this->app_config = array("\143\x6c\x69\145\156\164\x5f\151\x64", "\x63\x6c\151\145\156\164\137\x73\145\x63\x72\145\x74", "\163\143\x6f\x70\x65", "\x72\145\144\151\162\145\143\164\x5f\165\x72\x69", "\141\160\x70\x5f\164\x79\x70\x65", "\x61\x75\x74\150\157\x72\151\172\x65\165\162\154", "\x61\x63\x63\x65\163\163\x74\157\153\145\156\165\x72\154", "\162\x65\163\157\165\162\143\x65\x6f\167\x6e\x65\x72\144\x65\x74\x61\x69\154\x73\165\162\x6c", "\147\162\157\165\x70\x64\x65\x74\141\x69\x6c\x73\x75\162\154", "\x6a\x77\x6b\163\x5f\x75\x72\x69", "\x64\x69\163\160\154\141\x79\141\160\160\x6e\141\x6d\x65", "\x61\x70\x70\x49\144", "\x6d\157\x5f\157\141\165\x74\x68\x5f\x72\145\x73\x70\x6f\x6e\x73\x65\137\164\171\x70\x65");
    }
    public function save_app_settings()
    {
        global $Yh;
        $Wb = $Yh->get_plugin_config()->get_current_config();
        $g9 = "\144\x69\x73\141\142\154\x65\x64";
        $g9 = $Yh->mo_oauth_aemoutcrahsaphtn();
        if (!($g9 == "\144\151\163\x61\142\x6c\145\144")) {
            goto EO;
        }
        if (!(isset($_POST["\x6d\157\x5f\x6f\141\x75\164\x68\137\141\144\x64\x5f\141\160\160\137\156\157\156\x63\x65"]) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST["\x6d\157\137\157\x61\x75\164\150\137\141\x64\x64\x5f\x61\x70\x70\x5f\x6e\157\156\143\x65"])), "\x6d\x6f\x5f\x6f\141\165\x74\150\x5f\141\144\144\x5f\141\160\160") && isset($_POST[\MoOAuthConstants::OPTION]) && "\x6d\157\x5f\157\141\165\x74\x68\x5f\141\x64\x64\x5f\x61\x70\160" === sanitize_text_field(wp_unslash($_POST[\MoOAuthConstants::OPTION])))) {
            goto J3;
        }
        if (!current_user_can("\141\144\155\151\x6e\151\x73\164\162\x61\164\157\162")) {
            goto mX;
        }
        if (!($Yh->mo_oauth_check_empty_or_null($_POST["\155\157\137\x6f\141\x75\x74\x68\137\143\x6c\x69\145\156\x74\137\x69\x64"]) || $Yh->mo_oauth_check_empty_or_null($_POST["\x6d\x6f\137\157\x61\x75\x74\150\137\143\x6c\151\145\x6e\x74\x5f\163\145\143\x72\145\164"]))) {
            goto qk;
        }
        $Yh->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\x50\154\x65\x61\x73\145\x20\x65\156\x74\145\162\x20\166\x61\x6c\x69\144\x20\103\x6c\x69\x65\x6e\164\x20\x49\104\x20\x61\x6e\x64\x20\x43\154\151\x65\x6e\164\40\123\x65\143\162\145\x74\x2e");
        $Yh->mo_oauth_show_error_message();
        return;
        qk:
        $d9 = isset($_POST["\155\x6f\x5f\x6f\x61\165\164\150\x5f\143\165\x73\164\157\155\x5f\x61\160\x70\x5f\156\x61\155\x65"]) ? sanitize_text_field(wp_unslash($_POST["\x6d\x6f\x5f\x6f\141\x75\x74\150\137\x63\165\x73\164\157\155\x5f\141\x70\x70\137\156\x61\155\x65"])) : false;
        $eL = $Yh->get_app_by_name($d9);
        $eL = false !== $eL ? $eL->get_app_config() : [];
        $mK = false !== $eL;
        $mc = $Yh->get_app_list();
        if (!(!$mK && is_array($mc) && count($mc) > 0 && !$Yh->check_versi(4))) {
            goto w5;
        }
        $Yh->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\x59\157\x75\40\x63\x61\x6e\40\157\156\154\x79\40\x61\x64\144\x20\61\x20\x61\x70\x70\154\x69\x63\141\164\x69\x6f\x6e\40\167\x69\164\150\40\x66\162\145\145\40\166\x65\x72\x73\x69\x6f\x6e\x2e\40\125\160\x67\162\141\x64\145\40\164\157\40\x65\x6e\x74\145\162\x70\x72\x69\x73\145\x20\166\145\162\163\151\157\x6e\40\x69\146\40\171\x6f\165\40\x77\x61\x6e\x74\40\x74\157\x20\x61\x64\144\40\x6d\157\162\x65\x20\141\x70\x70\154\x69\143\141\x74\151\157\156\x73\x2e");
        $Yh->mo_oauth_show_error_message();
        return;
        w5:
        $eL = !is_array($eL) || empty($eL) ? array() : $eL;
        $eL = $this->change_app_settings($_POST, $eL);
        $Cp = isset($_POST["\155\x6f\137\x6f\141\165\164\150\137\x64\x69\163\x63\157\166\145\x72\x79"]) && isset($eL["\x69\163\137\144\x69\163\x63\x6f\166\x65\162\171\x5f\166\141\x6c\151\x64"]) && $eL["\x69\163\137\144\151\x73\x63\x6f\x76\145\x72\x79\137\x76\141\x6c\x69\144"] == "\164\x72\x75\x65";
        if (!$Cp) {
            goto EX;
        }
        $Yh->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\131\x6f\x75\x72\x20\x73\145\x74\x74\151\156\x67\163\x20\x61\x72\145\40\x73\x61\166\x65\x64\x20\x73\165\143\x63\145\163\163\146\x75\154\x6c\x79\56");
        $eL["\x6d\157\137\144\151\163\x63\x6f\166\x65\162\171\x5f\x76\141\154\151\x64\141\164\x69\157\156"] = "\x76\141\154\x69\x64";
        $Yh->mo_oauth_show_success_message();
        goto HL;
        EX:
        $Yh->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\x3c\x73\164\x72\x6f\156\x67\x3e\105\x72\162\157\162\72\x20\x3c\57\163\164\x72\157\156\x67\76\x20\x49\x6e\x63\157\x72\162\x65\143\x74\40\104\157\x6d\141\x69\x6e\x2f\x54\x65\x6e\x61\156\164\57\120\x6f\154\x69\143\x79\x2f\122\x65\x61\154\155\56\x20\x50\154\145\x61\x73\145\40\143\x6f\x6e\146\151\x67\165\x72\145\x20\x77\x69\x74\x68\40\x63\157\162\162\145\143\x74\40\x76\141\x6c\x75\145\163\40\x61\156\x64\40\164\162\x79\x20\x61\147\141\151\156\56");
        $eL["\155\x6f\137\x64\x69\x73\143\x6f\166\x65\x72\171\137\x76\141\x6c\x69\x64\x61\x74\x69\x6f\x6e"] = "\151\156\166\x61\154\x69\x64";
        $Yh->mo_oauth_show_error_message();
        HL:
        $mc[$d9] = new App($eL);
        $mc[$d9]->set_app_name($d9);
        $mc = apply_filters("\x6d\x6f\137\x6f\x61\x75\x74\x68\137\143\x6c\151\145\x6e\164\x5f\x73\x61\x76\145\x5f\x61\144\x64\x69\164\x69\x6f\x6e\141\x6c\x5f\146\x69\x65\x6c\144\x5f\163\145\164\164\151\156\x67\x73\137\x69\156\164\145\162\x6e\141\x6c", $mc);
        $Yh->mo_oauth_client_update_option("\x6d\x6f\137\x6f\x61\x75\164\150\137\141\160\x70\163\x5f\154\x69\163\x74", $mc);
        wp_redirect("\x61\x64\155\151\156\x2e\x70\150\160\77\x70\x61\147\x65\75\155\x6f\x5f\157\x61\165\164\150\x5f\163\145\164\x74\151\x6e\147\163\x26\x74\141\142\75\x63\x6f\156\x66\x69\x67\46\x61\143\x74\151\x6f\156\75\165\x70\x64\x61\x74\145\46\x61\160\160\75" . urlencode($d9));
        mX:
        J3:
        if (!(isset($_POST["\x6d\157\137\x6f\x61\x75\164\x68\x5f\x61\164\164\162\x69\142\165\x74\x65\137\x6d\141\160\x70\x69\156\x67\x5f\x6e\x6f\156\143\x65"]) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST["\x6d\157\x5f\157\141\x75\164\150\137\x61\164\x74\x72\x69\x62\x75\164\145\x5f\x6d\141\x70\160\151\x6e\147\137\x6e\157\156\143\145"])), "\x6d\157\x5f\157\141\x75\x74\150\137\141\164\164\x72\151\x62\165\164\x65\137\155\141\x70\160\151\156\147") && isset($_POST[\MoOAuthConstants::OPTION]) && "\155\x6f\x5f\x6f\x61\165\x74\150\137\x61\164\x74\162\151\142\165\164\145\x5f\155\141\160\x70\151\156\147" === sanitize_text_field(wp_unslash($_POST[\MoOAuthConstants::OPTION])))) {
            goto VP;
        }
        if (!current_user_can("\141\x64\x6d\151\x6e\x69\163\x74\x72\141\x74\157\x72")) {
            goto y4;
        }
        $d9 = isset($_POST[\MoOAuthConstants::POST_APP_NAME]) ? sanitize_text_field(wp_unslash($_POST[\MoOAuthConstants::POST_APP_NAME])) : '';
        $F8 = $Yh->get_app_by_name($d9);
        $KY = $F8->get_app_config('', false);
        $post = array_map("\x73\141\156\151\164\151\x7a\x65\x5f\x74\x65\x78\164\x5f\x66\151\145\x6c\x64", $_POST);
        $KY = $this->change_attribute_mapping($post, $KY);
        $KY = apply_filters("\x6d\x6f\137\157\x61\165\164\150\x5f\143\154\151\x65\x6e\164\137\x73\x61\x76\x65\x5f\x61\x64\x64\x69\164\151\157\x6e\x61\x6c\137\x61\164\164\162\x5f\x6d\141\160\160\x69\156\147\x5f\163\145\x74\x74\151\x6e\147\x73\x5f\151\x6e\x74\x65\162\156\141\154", $KY);
        $uw = $Yh->set_app_by_name($d9, $KY);
        if (!$uw) {
            goto mU;
        }
        $Yh->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\x59\157\165\162\40\x73\x65\164\x74\x69\156\x67\163\40\x61\x72\145\40\163\141\166\145\144\40\x73\x75\x63\143\x65\x73\163\x66\165\x6c\154\171\x2e");
        $Yh->mo_oauth_show_success_message();
        goto d9;
        mU:
        $Yh->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\x54\150\145\162\x65\x20\167\x61\x73\40\141\156\x20\x65\162\x72\x6f\x72\x20\163\x61\x76\x69\156\x67\x20\x73\145\x74\164\x69\156\147\163\x2e");
        $Yh->mo_oauth_show_error_message();
        d9:
        wp_safe_redirect("\141\x64\x6d\151\156\56\160\150\160\x3f\x70\x61\x67\145\x3d\155\x6f\137\157\141\165\164\x68\137\x73\145\x74\x74\x69\x6e\147\x73\46\164\x61\142\75\143\157\x6e\x66\x69\x67\x26\141\143\x74\151\x6f\156\x3d\x75\160\144\x61\x74\145\46\141\160\160\75" . rawurlencode($d9));
        y4:
        VP:
        EO:
        do_action("\x6d\x6f\x5f\x6f\x61\165\164\150\137\143\x6c\151\x65\x6e\164\137\x73\x61\x76\145\137\141\160\160\137\163\145\164\164\151\x6e\147\163\x5f\x69\156\x74\x65\162\x6e\x61\x6c");
    }
    public function change_app_settings($post, $eL)
    {
        global $Yh;
        $Vr = '';
        $pd = '';
        $pU = '';
        $d9 = sanitize_text_field(wp_unslash(isset($post[\MoOAuthConstants::POST_APP_NAME]) ? $post[\MoOAuthConstants::POST_APP_NAME] : ''));
        if ("\145\x76\x65\x6f\156\154\151\x6e\x65" === $d9) {
            goto KB;
        }
        $Cs = isset($post["\x6d\x6f\x5f\157\141\165\164\x68\137\x64\x69\163\x63\157\x76\145\162\171"]) ? $post["\x6d\157\137\157\x61\x75\x74\x68\x5f\x64\151\x73\143\157\166\145\162\171"] : null;
        if (!empty($Cs)) {
            goto Xn;
        }
        $Vr = isset($post["\155\157\137\x6f\x61\x75\x74\x68\137\x61\165\164\x68\x6f\x72\x69\172\x65\x75\162\x6c"]) ? stripslashes($post["\155\157\137\x6f\141\x75\x74\x68\x5f\141\x75\164\150\157\x72\x69\x7a\145\165\162\x6c"]) : '';
        $pd = isset($post["\x6d\x6f\x5f\157\141\165\164\150\137\x61\143\143\x65\x73\163\x74\157\153\x65\156\x75\x72\x6c"]) ? stripslashes($post["\155\x6f\137\x6f\x61\165\164\150\137\x61\x63\143\x65\x73\163\164\157\153\145\x6e\165\x72\154"]) : '';
        $pU = isset($post["\x6d\x6f\x5f\x6f\x61\165\164\150\137\162\x65\x73\x6f\x75\162\x63\x65\x6f\167\156\x65\x72\144\x65\164\141\x69\x6c\163\x75\162\154"]) ? stripslashes($post["\155\157\x5f\x6f\141\x75\x74\x68\137\162\x65\x73\x6f\165\x72\143\145\157\167\x6e\145\x72\x64\x65\x74\141\151\154\x73\165\x72\x6c"]) : '';
        goto Ip;
        Xn:
        $eL["\x65\170\x69\163\x74\151\x6e\147\137\x61\160\x70\x5f\x66\x6c\157\167"] = true;
        if (isset($post["\x6d\157\x5f\157\x61\x75\x74\x68\x5f\x70\162\157\166\x69\x64\145\162\x5f\x64\157\x6d\x61\x69\x6e"])) {
            goto zP;
        }
        if (!isset($post["\x6d\x6f\x5f\157\x61\x75\x74\x68\137\160\x72\x6f\x76\x69\144\145\162\x5f\x74\x65\156\141\x6e\x74"])) {
            goto Ez;
        }
        $kR = stripslashes(trim($post["\x6d\x6f\x5f\x6f\x61\165\x74\150\137\160\162\x6f\x76\x69\x64\145\162\x5f\164\145\x6e\x61\x6e\164"]));
        $Cs = str_replace("\164\145\156\x61\x6e\x74", $kR, $Cs);
        $eL["\164\145\156\141\x6e\x74"] = $kR;
        Ez:
        goto mJ;
        zP:
        $SO = stripslashes(rtrim($post["\x6d\157\137\x6f\x61\165\x74\150\x5f\160\162\x6f\166\x69\144\x65\162\x5f\x64\x6f\155\x61\151\x6e"], "\57"));
        $Cs = str_replace("\x64\x6f\155\141\x69\156", $SO, $Cs);
        $eL["\144\x6f\155\141\x69\156"] = $SO;
        mJ:
        if (isset($post["\155\x6f\137\157\141\x75\164\x68\x5f\x70\162\157\x76\151\144\x65\162\137\160\157\154\151\143\x79"])) {
            goto c4;
        }
        if (!isset($post["\x6d\x6f\x5f\157\141\x75\164\150\x5f\160\162\157\166\151\144\x65\x72\x5f\162\x65\x61\x6c\155"])) {
            goto U7;
        }
        $tJ = stripslashes(trim($post["\x6d\157\x5f\x6f\x61\165\164\x68\x5f\160\162\157\x76\x69\144\x65\x72\x5f\162\145\141\154\x6d"]));
        $Cs = str_replace("\162\x65\141\154\155\156\x61\155\x65", $tJ, $Cs);
        $eL["\x72\x65\x61\x6c\155"] = $tJ;
        U7:
        goto uF;
        c4:
        $vV = stripslashes(trim($post["\x6d\157\x5f\157\141\x75\164\x68\x5f\160\162\x6f\166\151\x64\145\162\137\x70\157\154\151\x63\171"]));
        $Cs = str_replace("\160\x6f\154\x69\143\x79", $vV, $Cs);
        $eL["\x70\157\154\x69\x63\x79"] = $vV;
        uF:
        $B7 = null;
        if (filter_var($Cs, FILTER_VALIDATE_URL)) {
            goto Bs;
        }
        $eL["\x69\x73\x5f\144\x69\x73\x63\x6f\x76\x65\x72\x79\x5f\x76\x61\154\x69\x64"] = "\x66\141\x6c\163\145";
        goto ND;
        Bs:
        $Yh->mo_oauth_client_update_option("\155\157\137\x6f\x63\137\x76\141\x6c\x69\144\137\144\151\163\143\x6f\x76\x65\x72\171\x5f\145\x70", true);
        $Up = array("\x73\163\154" => array("\166\145\162\151\x66\171\x5f\160\145\145\162" => false, "\x76\x65\162\151\x66\x79\x5f\x70\x65\x65\162\137\156\x61\155\x65" => false));
        $OY = @file_get_contents($Cs, false, stream_context_create($Up));
        $B7 = array();
        if ($OY) {
            goto LO;
        }
        $eL["\151\x73\137\x64\x69\x73\143\157\166\x65\x72\171\x5f\x76\141\154\x69\x64"] = "\x66\141\154\163\x65";
        goto N5;
        LO:
        $B7 = json_decode($OY);
        $eL["\151\163\x5f\144\x69\163\x63\x6f\166\x65\x72\x79\137\166\141\154\151\x64"] = "\x74\162\165\145";
        N5:
        $FY = isset($B7->scopes_supported[0]) ? $B7->scopes_supported[0] : '';
        $kG = isset($B7->scopes_supported[1]) ? $B7->scopes_supported[1] : '';
        $aU = stripslashes($FY) . "\40" . stripslashes($kG);
        $eL["\144\x69\163\x63\x6f\x76\x65\162\x79"] = $Cs;
        $eL["\x73\143\x6f\x70\x65"] = isset($rE) && !empty($rE) ? $rE : $aU;
        $Vr = isset($B7->authorization_endpoint) ? stripslashes($B7->authorization_endpoint) : '';
        $pd = isset($B7->token_endpoint) ? stripslashes($B7->token_endpoint) : '';
        $pU = isset($B7->userinfo_endpoint) ? stripslashes($B7->userinfo_endpoint) : '';
        ND:
        Ip:
        goto fF;
        KB:
        $Yh->mo_oauth_client_update_option("\x6d\157\137\157\141\x75\x74\150\x5f\145\x76\x65\x6f\156\x6c\x69\156\x65\x5f\145\x6e\x61\142\x6c\x65", 1);
        $Yh->mo_oauth_client_update_option("\x6d\157\137\x6f\x61\x75\164\150\x5f\x65\166\145\157\156\154\x69\156\x65\137\143\154\151\x65\x6e\x74\137\x69\144", $dP);
        $Yh->mo_oauth_client_update_option("\x6d\157\137\x6f\x61\x75\164\150\137\145\166\x65\x6f\x6e\154\151\x6e\145\137\x63\154\x69\145\156\x74\137\163\x65\143\x72\145\164", $WA);
        if (!($Yh->mo_oauth_client_get_option("\155\x6f\137\x6f\141\165\164\150\137\x65\166\x65\157\x6e\x6c\151\x6e\145\137\x63\154\151\x65\x6e\164\x5f\151\144") && $Yh->mo_oauth_client_get_option("\x6d\157\x5f\x6f\x61\165\x74\x68\137\x65\x76\145\x6f\x6e\x6c\151\156\x65\137\x63\x6c\151\x65\156\164\x5f\163\x65\143\x72\145\x74"))) {
            goto RC;
        }
        $ao = new Customer();
        $ri = $ao->add_oauth_application("\x65\x76\145\x6f\156\x6c\151\156\x65", "\105\126\x45\x20\117\156\x6c\151\x6e\145\40\117\x41\x75\x74\150");
        if ("\x41\160\x70\154\x69\143\141\x74\x69\157\156\x20\x43\x72\145\x61\x74\x65\144" === $ri) {
            goto p6;
        }
        $Yh->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, $ri);
        $this->mo_oauth_show_error_message();
        goto bi;
        p6:
        $Yh->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\131\x6f\165\162\40\163\145\x74\x74\151\156\x67\163\x20\x77\145\162\145\40\x73\141\166\x65\144\56\x20\x47\157\x20\164\157\40\101\x64\x76\141\x6e\143\x65\x64\x20\105\126\x45\40\117\x6e\154\151\x6e\145\40\x53\x65\x74\x74\x69\156\x67\163\40\146\x6f\162\40\x63\157\x6e\x66\x69\x67\x75\x72\x69\156\147\40\162\145\163\164\162\x69\x63\164\151\157\x6e\x73\40\x6f\x6e\40\165\x73\x65\162\40\x73\x69\147\156\x20\x69\x6e\56");
        $this->mo_oauth_show_success_message();
        bi:
        RC:
        fF:
        isset($post["\x6d\157\137\x6f\x61\x75\164\150\137\163\143\x6f\x70\145"]) && !empty($post["\x6d\157\137\157\x61\x75\x74\150\137\163\x63\157\x70\x65"]) ? $eL["\163\x63\157\160\145"] = sanitize_text_field(wp_unslash($post["\x6d\x6f\137\x6f\x61\x75\x74\x68\137\x73\143\x6f\160\145"])) : '';
        $eL["\165\x6e\x69\x71\165\145\137\141\x70\x70\x69\144"] = isset($post["\155\157\x5f\157\x61\165\x74\150\137\143\x75\163\x74\x6f\x6d\x5f\141\x70\x70\137\156\x61\x6d\145"]) ? stripslashes($post["\x6d\157\137\157\x61\x75\164\x68\137\x63\x75\x73\x74\157\x6d\x5f\x61\x70\160\137\x6e\x61\x6d\145"]) : '';
        $eL["\143\154\x69\x65\156\164\x5f\x69\144"] = $Yh->mooauthencrypt(sanitize_text_field(wp_unslash(isset($post["\155\157\x5f\157\141\165\164\x68\137\x63\x6c\151\x65\x6e\164\137\x69\144"]) ? $post["\x6d\157\x5f\157\x61\x75\x74\150\x5f\143\x6c\151\x65\156\164\x5f\151\x64"] : '')));
        $eL["\x63\154\x69\x65\156\x74\x5f\x73\x65\143\162\145\x74"] = $Yh->mooauthencrypt(wp_unslash(isset($post["\x6d\x6f\x5f\x6f\x61\165\164\150\x5f\x63\154\x69\145\156\x74\137\x73\x65\143\162\x65\164"]) ? stripslashes(trim($post["\x6d\157\137\157\141\165\164\150\137\x63\x6c\151\x65\156\164\x5f\163\145\143\162\x65\x74"])) : ''));
        $eL["\x63\154\x69\145\x6e\x74\137\143\162\x65\x64\163\x5f\145\156\143\x72\160\171\x74\145\x64"] = true;
        $eL["\x73\x65\x6e\144\137\150\x65\141\x64\x65\x72\163"] = isset($post["\155\x6f\x5f\x6f\141\165\164\150\x5f\x61\x75\x74\150\x6f\x72\151\172\141\x74\151\x6f\156\137\x68\x65\141\x64\x65\x72"]) ? (int) filter_var($post["\x6d\x6f\x5f\x6f\141\x75\164\150\137\x61\x75\164\150\157\x72\151\172\x61\x74\151\x6f\156\137\x68\145\141\x64\145\162"], FILTER_SANITIZE_NUMBER_INT) : 0;
        $eL["\x73\x65\x6e\144\x5f\x62\x6f\x64\x79"] = isset($post["\155\157\x5f\x6f\141\x75\164\150\137\142\x6f\x64\171"]) ? (int) filter_var($post["\x6d\157\x5f\x6f\141\x75\x74\x68\137\x62\x6f\x64\x79"], FILTER_SANITIZE_NUMBER_INT) : 0;
        $eL["\163\x65\156\144\x5f\x73\x74\141\x74\145"] = isset($_POST["\x6d\157\x5f\x6f\141\165\x74\x68\x5f\163\x74\141\164\145"]) ? (int) filter_var($_POST["\x6d\x6f\x5f\157\141\x75\164\x68\x5f\x73\x74\141\164\145"], FILTER_SANITIZE_NUMBER_INT) : 0;
        $eL["\x73\x65\156\144\137\x6e\157\x6e\143\x65"] = isset($_POST["\x6d\157\x5f\x6f\x61\165\x74\x68\x5f\x6e\157\x6e\143\x65"]) ? (int) filter_var($_POST["\x6d\x6f\x5f\x6f\x61\x75\164\x68\137\156\157\x6e\x63\145"], FILTER_SANITIZE_NUMBER_INT) : 0;
        $eL["\163\150\157\167\137\157\156\137\x6c\x6f\x67\151\x6e\137\160\x61\147\145"] = isset($post["\x6d\157\137\x6f\x61\x75\164\x68\137\x73\150\157\x77\137\157\x6e\137\x6c\x6f\147\x69\156\137\x70\141\x67\145"]) ? (int) filter_var($post["\155\157\137\x6f\x61\x75\x74\x68\x5f\163\x68\x6f\167\x5f\x6f\156\x5f\x6c\x6f\147\151\x6e\137\x70\x61\x67\x65"], FILTER_SANITIZE_NUMBER_INT) : 0;
        if (!(!empty($eL["\x61\160\160\137\x74\x79\x70\x65"]) && $eL["\141\160\160\x5f\x74\171\160\145"] === "\157\141\x75\164\x68\61")) {
            goto UC;
        }
        $eL["\162\x65\161\165\x65\x73\164\x75\162\x6c"] = isset($post["\x6d\157\137\x6f\141\165\x74\x68\x5f\x72\x65\161\165\x65\163\x74\x75\x72\154"]) ? stripslashes($post["\155\157\x5f\x6f\141\165\164\150\x5f\162\x65\161\165\x65\163\164\x75\162\154"]) : '';
        UC:
        if (isset($eL["\x61\160\x70\x49\144"])) {
            goto D5;
        }
        $eL["\x61\160\x70\111\144"] = $d9;
        D5:
        $eL["\x72\x65\x64\151\x72\145\143\164\137\x75\162\151"] = sanitize_text_field(wp_unslash(isset($post["\155\157\137\x75\x70\144\x61\x74\x65\x5f\x75\162\x6c"]) ? $post["\155\x6f\x5f\165\160\144\141\x74\x65\137\165\x72\154"] : site_url()));
        $eL["\x61\x75\x74\x68\x6f\162\151\x7a\145\165\x72\154"] = $Vr;
        $eL["\141\x63\143\145\163\x73\164\x6f\x6b\145\156\165\x72\x6c"] = $pd;
        $eL["\x61\x70\x70\137\x74\x79\x70\145"] = isset($post["\155\x6f\x5f\x6f\141\x75\164\x68\x5f\x61\160\x70\x5f\x74\x79\160\145"]) ? stripslashes($post["\155\x6f\x5f\157\141\165\164\150\137\x61\160\160\137\x74\x79\x70\145"]) : stripslashes("\x6f\141\x75\x74\150");
        if (!($eL["\x61\x70\x70\137\x74\171\160\x65"] == "\x6f\141\165\x74\150" || $eL["\141\160\160\137\x74\171\x70\x65"] == "\x6f\x61\165\164\150\x31" || isset($post["\x6d\157\x5f\x6f\x61\x75\x74\x68\x5f\x72\145\x73\157\165\162\x63\145\x6f\x77\156\145\x72\144\x65\164\x61\151\x6c\163\x75\162\154"]))) {
            goto OQ;
        }
        $eL["\x72\x65\163\x6f\165\x72\x63\x65\157\x77\156\x65\x72\144\145\164\141\151\x6c\163\x75\162\x6c"] = $pU;
        OQ:
        return $eL;
    }
    public function change_attribute_mapping($post, $eL)
    {
        $sb = sanitize_text_field(wp_unslash($post["\x6d\x6f\x5f\x6f\x61\165\x74\x68\x5f\x75\x73\x65\162\x6e\x61\155\145\137\141\164\x74\162"]));
        $eL["\x75\163\145\162\156\141\x6d\145\137\x61\164\x74\162"] = $sb;
        return $eL;
    }
}
