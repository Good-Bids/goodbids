<?php


namespace MoOauthClient\Free;

use MoOauthClient\App;
class AppSettings
{
    private $app_config;
    public function __construct()
    {
        $this->app_config = array("\x63\x6c\x69\145\x6e\164\x5f\x69\144", "\x63\154\151\145\x6e\164\137\163\x65\x63\162\145\164", "\163\x63\157\x70\145", "\162\145\144\151\162\x65\143\164\x5f\x75\x72\x69", "\x61\x70\x70\137\x74\x79\160\x65", "\141\x75\x74\x68\x6f\x72\151\172\145\x75\x72\154", "\x61\x63\x63\x65\163\x73\x74\x6f\153\145\x6e\x75\162\x6c", "\162\145\163\157\x75\x72\x63\145\157\167\x6e\x65\x72\144\x65\x74\x61\151\x6c\x73\165\x72\x6c", "\147\x72\x6f\165\160\x64\x65\164\141\151\154\163\165\x72\154", "\x6a\x77\x6b\x73\x5f\165\162\151", "\x64\151\163\160\x6c\141\171\x61\160\160\x6e\141\x6d\145", "\141\160\160\111\144", "\x6d\x6f\x5f\x6f\141\x75\164\150\x5f\x72\145\x73\160\157\156\x73\x65\137\x74\171\160\145");
    }
    public function save_app_settings()
    {
        global $Uj;
        $Kn = $Uj->get_plugin_config()->get_current_config();
        $MS = "\144\151\163\x61\x62\154\145\144";
        if (empty($Kn["\x6d\157\137\144\x74\x65\x5f\163\164\141\x74\145"])) {
            goto sA;
        }
        $MS = $Uj->mooauthdecrypt($Kn["\x6d\x6f\x5f\144\x74\145\x5f\x73\x74\141\164\145"]);
        sA:
        if (!($MS == "\144\x69\x73\141\x62\x6c\145\x64")) {
            goto a8;
        }
        if (!(isset($_POST["\x6d\157\137\157\141\x75\x74\150\137\x61\144\x64\x5f\141\160\x70\x5f\x6e\157\x6e\143\145"]) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST["\155\157\x5f\157\141\165\x74\x68\x5f\x61\144\144\137\141\x70\x70\x5f\156\x6f\156\x63\x65"])), "\x6d\157\x5f\x6f\x61\x75\164\x68\137\x61\x64\144\x5f\x61\x70\x70") && isset($_POST[\MoOAuthConstants::OPTION]) && "\x6d\x6f\137\157\x61\x75\164\150\137\x61\144\x64\x5f\x61\x70\x70" === $_POST[\MoOAuthConstants::OPTION])) {
            goto Sc;
        }
        if (!($Uj->mo_oauth_check_empty_or_null($_POST["\155\157\x5f\157\x61\165\x74\150\137\143\154\x69\145\156\x74\137\151\x64"]) || $Uj->mo_oauth_check_empty_or_null($_POST["\155\x6f\x5f\157\x61\165\x74\x68\x5f\x63\x6c\x69\x65\x6e\x74\137\x73\x65\x63\162\145\x74"]))) {
            goto ML;
        }
        $Uj->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\120\154\x65\141\x73\145\x20\x65\156\x74\145\162\x20\166\141\x6c\x69\x64\40\x43\154\151\x65\x6e\x74\x20\111\x44\40\x61\x6e\x64\x20\x43\x6c\151\x65\156\164\x20\x53\145\143\162\145\x74\x2e");
        $Uj->mo_oauth_show_error_message();
        return;
        ML:
        $BW = isset($_POST["\x6d\157\137\x6f\x61\x75\164\150\137\143\165\x73\164\157\155\x5f\x61\x70\x70\137\156\141\155\x65"]) ? sanitize_text_field(wp_unslash($_POST["\155\157\137\157\x61\165\164\150\137\143\165\x73\164\x6f\155\137\141\x70\x70\137\x6e\141\155\x65"])) : false;
        $Oj = $Uj->get_app_by_name($BW);
        $Oj = false !== $Oj ? $Oj->get_app_config() : [];
        $XU = false !== $Oj;
        $H5 = $Uj->get_app_list();
        if (!(!$XU && is_array($H5) && count($H5) > 0 && !$Uj->check_versi(4))) {
            goto Bz;
        }
        $Uj->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\x59\157\165\40\143\x61\x6e\40\157\x6e\x6c\x79\40\x61\144\x64\x20\61\x20\141\160\x70\154\x69\143\x61\164\151\157\x6e\40\167\x69\164\150\40\146\162\x65\x65\x20\x76\x65\x72\163\x69\x6f\x6e\x2e\x20\x55\x70\147\162\141\144\145\x20\x74\x6f\x20\x65\156\x74\145\162\160\162\x69\x73\x65\40\x76\145\162\163\151\x6f\156\x20\x69\146\x20\171\157\x75\x20\167\x61\156\x74\x20\x74\x6f\x20\141\x64\144\40\x6d\x6f\x72\x65\40\x61\160\x70\154\x69\143\x61\x74\x69\x6f\x6e\x73\56");
        $Uj->mo_oauth_show_error_message();
        return;
        Bz:
        $Oj = !is_array($Oj) || empty($Oj) ? array() : $Oj;
        $Oj = $this->change_app_settings($_POST, $Oj);
        $Zw = isset($_POST["\155\x6f\137\x6f\x61\x75\164\x68\137\x64\151\163\x63\157\166\145\162\x79"]) && isset($Oj["\x69\163\x5f\144\x69\x73\143\x6f\166\x65\x72\x79\137\x76\141\x6c\x69\x64"]) && $Oj["\x69\x73\137\x64\x69\163\143\x6f\x76\x65\162\171\x5f\x76\141\x6c\151\x64"] == "\x74\162\165\145";
        if (!$Zw) {
            goto b_;
        }
        $Uj->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\x59\157\165\x72\40\163\x65\164\x74\x69\156\x67\163\40\141\x72\x65\x20\x73\141\166\145\144\x20\x73\x75\143\x63\145\163\163\x66\x75\x6c\x6c\171\56");
        $Oj["\x6d\157\137\x64\x69\x73\143\x6f\166\145\162\x79\x5f\x76\x61\154\151\x64\141\164\x69\157\156"] = "\x76\x61\x6c\151\x64";
        $Uj->mo_oauth_show_success_message();
        goto ip;
        b_:
        $Uj->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\74\163\x74\162\x6f\156\147\76\x45\x72\x72\157\x72\x3a\x20\x3c\x2f\x73\164\x72\157\156\x67\x3e\40\x49\x6e\143\157\162\x72\x65\143\164\x20\104\x6f\155\141\x69\156\x2f\124\145\x6e\x61\x6e\164\x2f\120\x6f\154\x69\x63\171\57\122\145\141\x6c\155\x2e\40\120\x6c\x65\141\163\x65\40\x63\x6f\156\146\x69\147\x75\162\145\x20\x77\151\164\x68\40\x63\x6f\x72\162\145\x63\x74\x20\166\x61\154\165\145\x73\40\x61\156\x64\40\164\162\x79\40\141\147\x61\x69\156\56");
        $Oj["\x6d\x6f\137\x64\x69\x73\x63\x6f\166\145\x72\x79\x5f\x76\x61\x6c\x69\144\141\x74\151\x6f\x6e"] = "\x69\156\x76\141\x6c\151\144";
        $Uj->mo_oauth_show_error_message();
        ip:
        $H5[$BW] = new App($Oj);
        $H5[$BW]->set_app_name($BW);
        $H5 = apply_filters("\155\157\137\x6f\x61\x75\164\x68\x5f\x63\154\151\x65\x6e\164\x5f\x73\141\x76\145\137\x61\x64\x64\151\164\151\157\x6e\141\154\x5f\146\151\145\154\144\137\x73\x65\x74\x74\x69\x6e\147\x73\137\x69\156\164\x65\162\156\141\x6c", $H5);
        $Uj->mo_oauth_client_update_option("\x6d\x6f\x5f\157\x61\165\x74\x68\x5f\x61\x70\160\163\x5f\154\151\x73\164", $H5);
        wp_redirect("\141\x64\155\151\156\x2e\x70\x68\x70\77\160\141\147\145\x3d\x6d\157\137\157\141\x75\x74\150\137\163\145\x74\x74\151\x6e\147\x73\x26\164\x61\x62\x3d\143\x6f\x6e\146\x69\x67\x26\x61\143\164\151\157\156\x3d\165\160\144\141\x74\x65\46\x61\160\160\x3d" . urlencode($BW));
        Sc:
        if (!(isset($_POST["\x6d\x6f\137\x6f\x61\165\164\150\x5f\141\x74\164\162\151\x62\165\164\145\137\155\x61\160\x70\x69\x6e\x67\x5f\156\x6f\156\x63\145"]) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST["\155\x6f\x5f\157\141\x75\164\150\137\x61\x74\164\162\x69\x62\165\164\145\137\155\141\x70\160\x69\x6e\x67\137\x6e\x6f\156\x63\145"])), "\155\157\x5f\x6f\141\x75\164\150\137\141\164\x74\x72\151\x62\x75\164\x65\x5f\155\141\160\160\x69\156\147") && isset($_POST[\MoOAuthConstants::OPTION]) && "\x6d\x6f\137\157\x61\165\x74\150\137\x61\x74\x74\x72\151\x62\x75\164\x65\137\x6d\141\x70\x70\x69\x6e\147" === $_POST[\MoOAuthConstants::OPTION])) {
            goto eY;
        }
        $BW = sanitize_text_field(wp_unslash(isset($_POST[\MoOAuthConstants::POST_APP_NAME]) ? $_POST[\MoOAuthConstants::POST_APP_NAME] : ''));
        $Fr = $Uj->get_app_by_name($BW);
        $Wh = $Fr->get_app_config('', false);
        $Wh = $this->change_attribute_mapping($_POST, $Wh);
        $Wh = apply_filters("\155\157\x5f\157\141\165\x74\x68\x5f\x63\154\151\145\x6e\164\x5f\163\x61\x76\x65\x5f\x61\x64\x64\151\x74\x69\157\x6e\x61\154\137\141\x74\x74\x72\x5f\155\x61\160\x70\x69\x6e\x67\137\x73\x65\164\164\x69\x6e\147\x73\x5f\x69\156\164\145\162\156\141\154", $Wh);
        $FO = $Uj->set_app_by_name($BW, $Wh);
        if (!$FO) {
            goto er;
        }
        $Uj->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\x59\x6f\x75\x72\40\x73\x65\x74\x74\151\x6e\x67\x73\40\x61\x72\x65\40\163\x61\x76\x65\x64\40\x73\165\x63\143\x65\163\x73\146\165\x6c\154\171\x2e");
        $Uj->mo_oauth_show_success_message();
        goto F7;
        er:
        $Uj->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\x54\x68\x65\162\x65\40\167\141\163\x20\141\156\x20\x65\162\x72\157\162\x20\163\x61\x76\x69\x6e\147\x20\x73\x65\164\x74\x69\156\147\x73\x2e");
        $Uj->mo_oauth_show_error_message();
        F7:
        wp_safe_redirect("\x61\144\x6d\x69\156\56\x70\x68\x70\77\x70\141\x67\145\75\155\x6f\x5f\157\x61\165\x74\x68\x5f\163\145\x74\164\x69\156\147\x73\46\164\x61\142\75\x63\x6f\x6e\x66\151\x67\46\141\143\x74\x69\157\156\x3d\165\160\x64\x61\164\145\46\x61\x70\x70\x3d" . rawurlencode($BW));
        eY:
        a8:
        do_action("\x6d\157\137\x6f\x61\x75\x74\x68\137\x63\154\151\145\x6e\x74\x5f\x73\141\x76\x65\137\141\160\160\137\163\145\x74\164\151\x6e\147\x73\x5f\x69\x6e\x74\145\162\156\141\154");
    }
    public function change_app_settings($post, $Oj)
    {
        global $Uj;
        $nv = '';
        $z1 = '';
        $nI = '';
        $BW = sanitize_text_field(wp_unslash(isset($post[\MoOAuthConstants::POST_APP_NAME]) ? $post[\MoOAuthConstants::POST_APP_NAME] : ''));
        if ("\x65\x76\x65\x6f\156\154\x69\156\145" === $BW) {
            goto is;
        }
        $wL = isset($post["\x6d\x6f\x5f\x6f\x61\165\164\150\137\x64\151\x73\x63\x6f\x76\x65\x72\171"]) ? $post["\155\157\137\157\x61\165\x74\150\137\144\x69\163\x63\157\x76\x65\162\171"] : null;
        if (!empty($wL)) {
            goto xl;
        }
        $nv = isset($post["\x6d\157\x5f\x6f\x61\165\x74\150\137\141\165\164\150\157\x72\151\172\145\x75\x72\154"]) ? stripslashes($post["\x6d\157\137\x6f\141\165\x74\150\137\141\165\164\x68\157\162\x69\x7a\145\x75\x72\x6c"]) : '';
        $z1 = isset($post["\155\157\x5f\x6f\141\165\x74\x68\x5f\x61\x63\x63\145\163\163\x74\157\x6b\145\x6e\165\162\x6c"]) ? stripslashes($post["\155\x6f\137\x6f\x61\x75\164\150\137\141\143\x63\x65\x73\163\x74\x6f\153\x65\156\x75\x72\x6c"]) : '';
        $nI = isset($post["\155\157\x5f\x6f\x61\x75\164\x68\137\162\145\x73\157\x75\x72\143\x65\157\x77\x6e\145\x72\144\145\x74\x61\151\154\x73\165\x72\154"]) ? stripslashes($post["\x6d\157\x5f\x6f\141\165\x74\x68\137\162\x65\163\157\165\162\143\145\157\167\x6e\x65\162\144\x65\164\x61\151\x6c\x73\165\162\x6c"]) : '';
        goto q4;
        xl:
        $Oj["\145\170\151\x73\x74\x69\156\147\x5f\141\x70\x70\137\x66\x6c\x6f\x77"] = true;
        if (isset($post["\x6d\x6f\x5f\x6f\141\x75\x74\x68\137\160\x72\x6f\x76\x69\x64\x65\162\x5f\x64\x6f\x6d\141\x69\x6e"])) {
            goto A8;
        }
        if (!isset($post["\x6d\x6f\x5f\x6f\141\165\x74\150\x5f\160\162\157\166\x69\x64\145\x72\137\164\145\156\141\156\164"])) {
            goto rn;
        }
        $go = stripslashes(trim($post["\x6d\x6f\137\157\141\x75\x74\x68\x5f\x70\x72\157\166\151\x64\145\x72\x5f\164\x65\156\141\x6e\164"]));
        $wL = str_replace("\x74\x65\x6e\141\x6e\x74", $go, $wL);
        $Oj["\164\145\x6e\x61\x6e\164"] = $go;
        rn:
        goto J8;
        A8:
        $US = stripslashes(rtrim($post["\x6d\x6f\x5f\x6f\x61\165\x74\x68\137\x70\x72\157\x76\x69\144\145\162\x5f\x64\x6f\x6d\x61\151\x6e"], "\x2f"));
        $wL = str_replace("\x64\157\x6d\x61\x69\156", $US, $wL);
        $Oj["\x64\x6f\x6d\141\151\156"] = $US;
        J8:
        if (isset($post["\155\x6f\x5f\157\x61\165\x74\x68\137\160\x72\157\166\x69\x64\145\162\x5f\160\x6f\x6c\151\x63\x79"])) {
            goto Y9;
        }
        if (!isset($post["\x6d\157\x5f\157\141\x75\x74\150\137\160\x72\157\x76\x69\144\145\162\x5f\162\x65\141\154\x6d"])) {
            goto Ss;
        }
        $xz = stripslashes(trim($post["\x6d\157\137\x6f\141\x75\164\150\137\x70\162\157\166\x69\144\145\x72\x5f\x72\145\x61\154\155"]));
        $wL = str_replace("\x72\x65\141\x6c\x6d\x6e\x61\155\x65", $xz, $wL);
        $Oj["\x72\x65\x61\x6c\155"] = $xz;
        Ss:
        goto FZ;
        Y9:
        $U0 = stripslashes(trim($post["\155\x6f\137\157\141\x75\x74\150\x5f\x70\162\157\166\151\144\x65\x72\x5f\x70\157\x6c\151\x63\171"]));
        $wL = str_replace("\160\x6f\x6c\x69\x63\171", $U0, $wL);
        $Oj["\x70\157\x6c\x69\x63\171"] = $U0;
        FZ:
        $Ch = null;
        if (filter_var($wL, FILTER_VALIDATE_URL)) {
            goto NO;
        }
        $Oj["\151\163\x5f\x64\x69\163\143\157\166\145\x72\171\137\x76\x61\154\151\144"] = "\x66\x61\154\x73\x65";
        goto oq;
        NO:
        $Uj->mo_oauth_client_update_option("\155\x6f\137\x6f\143\x5f\166\141\x6c\x69\144\x5f\x64\x69\x73\x63\157\x76\145\162\x79\x5f\145\160", true);
        $Hs = array("\163\163\x6c" => array("\166\x65\162\x69\146\171\137\160\x65\x65\x72" => false, "\166\145\162\151\x66\171\137\160\145\145\x72\x5f\156\141\x6d\145" => false));
        $dV = @file_get_contents($wL, false, stream_context_create($Hs));
        $Ch = array();
        if ($dV) {
            goto uy;
        }
        $Oj["\x69\x73\137\x64\x69\163\143\x6f\x76\145\162\x79\x5f\166\141\154\151\x64"] = "\146\141\x6c\163\x65";
        goto O7;
        uy:
        $Ch = json_decode($dV);
        $Oj["\x69\163\137\x64\x69\163\143\157\166\145\162\x79\137\166\x61\154\151\x64"] = "\164\162\165\145";
        O7:
        $rS = isset($Ch->scopes_supported[0]) ? $Ch->scopes_supported[0] : '';
        $Tb = isset($Ch->scopes_supported[1]) ? $Ch->scopes_supported[1] : '';
        $E7 = stripslashes($rS) . "\40" . stripslashes($Tb);
        $Oj["\144\151\163\x63\157\166\145\162\x79"] = $wL;
        $Oj["\x73\x63\157\160\145"] = isset($vn) && !empty($vn) ? $vn : $E7;
        $nv = isset($Ch->authorization_endpoint) ? stripslashes($Ch->authorization_endpoint) : '';
        $z1 = isset($Ch->token_endpoint) ? stripslashes($Ch->token_endpoint) : '';
        $nI = isset($Ch->userinfo_endpoint) ? stripslashes($Ch->userinfo_endpoint) : '';
        oq:
        q4:
        goto ld;
        is:
        $Uj->mo_oauth_client_update_option("\155\157\137\x6f\141\165\x74\x68\x5f\x65\x76\x65\157\x6e\154\x69\156\145\x5f\145\156\x61\142\x6c\x65", 1);
        $Uj->mo_oauth_client_update_option("\x6d\157\137\x6f\141\x75\x74\150\137\145\x76\x65\157\156\154\151\156\x65\137\143\x6c\151\x65\x6e\164\x5f\x69\x64", $ve);
        $Uj->mo_oauth_client_update_option("\155\157\x5f\x6f\x61\165\164\x68\137\x65\x76\145\157\x6e\x6c\151\156\x65\137\143\154\151\x65\x6e\164\x5f\163\145\143\162\145\x74", $Ko);
        if (!($Uj->mo_oauth_client_get_option("\155\157\137\157\x61\x75\164\x68\137\145\x76\145\x6f\x6e\154\x69\x6e\x65\137\143\154\151\x65\x6e\x74\137\x69\x64") && $Uj->mo_oauth_client_get_option("\155\x6f\137\x6f\x61\x75\x74\x68\x5f\145\x76\145\x6f\156\154\x69\156\145\x5f\143\x6c\x69\145\x6e\164\x5f\x73\x65\143\x72\145\x74"))) {
            goto W_;
        }
        $me = new Customer();
        $fU = $me->add_oauth_application("\x65\166\x65\x6f\156\x6c\x69\156\145", "\105\126\x45\40\117\156\154\151\156\145\x20\x4f\x41\x75\x74\150");
        if ("\101\x70\x70\x6c\x69\143\x61\x74\x69\157\156\40\x43\162\x65\141\x74\145\x64" === $fU) {
            goto iY;
        }
        $Uj->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, $fU);
        $this->mo_oauth_show_error_message();
        goto rU;
        iY:
        $Uj->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\131\x6f\x75\162\x20\x73\145\x74\164\x69\156\147\163\x20\167\145\x72\145\x20\x73\x61\x76\145\x64\56\x20\107\157\x20\164\157\40\101\144\166\141\156\143\x65\x64\x20\x45\126\105\x20\117\x6e\154\x69\x6e\x65\40\123\x65\x74\164\x69\x6e\x67\163\x20\x66\x6f\162\40\143\157\x6e\x66\151\x67\x75\162\151\x6e\147\40\162\x65\x73\164\162\151\x63\x74\151\x6f\156\x73\40\x6f\156\40\165\x73\x65\162\x20\163\x69\x67\x6e\40\151\x6e\56");
        $this->mo_oauth_show_success_message();
        rU:
        W_:
        ld:
        isset($post["\155\157\137\157\141\x75\164\x68\x5f\x73\x63\157\x70\145"]) && !empty($post["\155\157\137\x6f\x61\165\x74\x68\137\x73\x63\157\x70\145"]) ? $Oj["\163\x63\157\x70\145"] = sanitize_text_field(wp_unslash($post["\155\157\137\x6f\141\x75\x74\150\137\x73\x63\x6f\160\145"])) : '';
        $Oj["\x75\156\151\x71\165\145\x5f\x61\x70\x70\x69\144"] = isset($post["\155\x6f\137\x6f\141\x75\164\x68\x5f\141\x70\x70\137\156\141\x6d\145"]) ? stripslashes($post["\x6d\x6f\137\x6f\141\165\164\150\x5f\x61\x70\160\x5f\x6e\x61\155\x65"]) : '';
        $Oj["\x63\x6c\x69\x65\156\164\137\151\x64"] = $Uj->mooauthencrypt(sanitize_text_field(wp_unslash(isset($post["\x6d\x6f\x5f\x6f\141\165\x74\x68\x5f\x63\154\151\145\156\x74\x5f\x69\x64"]) ? $post["\x6d\157\137\x6f\141\165\x74\150\x5f\143\x6c\151\145\x6e\164\x5f\151\x64"] : '')));
        $Oj["\x63\x6c\x69\x65\156\x74\137\163\145\143\x72\x65\x74"] = $Uj->mooauthencrypt(wp_unslash(isset($post["\x6d\x6f\137\157\x61\165\x74\x68\x5f\143\154\151\x65\156\x74\137\x73\145\x63\162\145\x74"]) ? stripslashes(trim($post["\155\157\x5f\157\141\x75\x74\150\137\143\154\151\x65\x6e\x74\137\163\145\x63\162\x65\164"])) : ''));
        $Oj["\143\154\151\x65\156\164\137\143\x72\x65\144\x73\137\x65\156\x63\162\160\171\164\x65\144"] = true;
        $Oj["\163\145\156\x64\137\x68\145\141\x64\x65\162\x73"] = isset($post["\x6d\x6f\137\x6f\141\165\x74\150\137\x61\165\x74\x68\157\x72\151\x7a\141\164\151\x6f\156\137\x68\145\x61\144\x65\x72"]) ? (int) filter_var($post["\x6d\157\137\x6f\x61\165\164\150\137\141\165\164\150\x6f\x72\x69\x7a\141\x74\x69\x6f\x6e\137\150\145\141\x64\x65\x72"], FILTER_SANITIZE_NUMBER_INT) : 0;
        $Oj["\x73\x65\156\x64\137\x62\157\144\x79"] = isset($post["\155\x6f\137\157\141\x75\x74\x68\137\x62\157\x64\x79"]) ? (int) filter_var($post["\x6d\x6f\x5f\x6f\x61\x75\x74\x68\x5f\142\x6f\x64\171"], FILTER_SANITIZE_NUMBER_INT) : 0;
        $Oj["\163\145\156\144\x5f\163\164\x61\x74\x65"] = isset($_POST["\x6d\x6f\137\x6f\x61\165\164\x68\137\163\164\141\164\145"]) ? (int) filter_var($_POST["\x6d\x6f\137\x6f\x61\x75\x74\150\x5f\163\x74\x61\164\145"], FILTER_SANITIZE_NUMBER_INT) : 0;
        $Oj["\163\145\x6e\x64\137\x6e\157\x6e\143\145"] = isset($_POST["\155\x6f\137\x6f\141\x75\x74\150\137\x6e\x6f\x6e\x63\145"]) ? (int) filter_var($_POST["\155\157\137\157\x61\x75\164\150\137\x6e\x6f\x6e\x63\x65"], FILTER_SANITIZE_NUMBER_INT) : 0;
        $Oj["\x73\x68\157\x77\x5f\x6f\156\137\154\x6f\147\x69\156\x5f\x70\141\147\x65"] = isset($post["\x6d\157\x5f\157\x61\165\x74\x68\137\x73\150\157\167\x5f\x6f\156\137\154\157\x67\151\156\137\x70\x61\147\x65"]) ? (int) filter_var($post["\x6d\x6f\x5f\x6f\141\165\x74\x68\x5f\x73\150\157\x77\137\x6f\x6e\x5f\154\x6f\147\151\156\x5f\160\x61\x67\x65"], FILTER_SANITIZE_NUMBER_INT) : 0;
        if (!(!empty($Oj["\x61\x70\x70\x5f\164\x79\160\145"]) && $Oj["\141\x70\160\x5f\x74\x79\x70\145"] === "\157\141\165\x74\x68\61")) {
            goto Hf;
        }
        $Oj["\x72\145\x71\x75\145\163\164\165\x72\x6c"] = isset($post["\155\157\137\157\x61\165\164\150\x5f\162\145\x71\165\145\x73\x74\165\x72\x6c"]) ? stripslashes($post["\x6d\x6f\137\x6f\141\x75\x74\150\137\x72\145\x71\x75\145\163\x74\165\x72\x6c"]) : '';
        Hf:
        if (isset($Oj["\141\160\160\111\x64"])) {
            goto OL;
        }
        $Oj["\x61\x70\160\x49\x64"] = $BW;
        OL:
        $Oj["\x72\x65\x64\x69\x72\x65\x63\x74\x5f\x75\162\x69"] = sanitize_text_field(wp_unslash(isset($post["\155\x6f\x5f\x75\x70\x64\x61\164\145\137\x75\x72\154"]) ? $post["\155\157\137\x75\160\x64\141\x74\x65\137\165\162\x6c"] : site_url()));
        $Oj["\x61\165\164\150\x6f\x72\x69\x7a\145\165\162\x6c"] = $nv;
        $Oj["\141\x63\143\x65\163\x73\x74\x6f\x6b\x65\x6e\165\162\x6c"] = $z1;
        $Oj["\x61\x70\160\137\164\171\x70\x65"] = isset($post["\155\157\137\157\141\165\164\x68\137\141\160\160\x5f\164\x79\x70\145"]) ? stripslashes($post["\155\157\137\157\x61\x75\x74\x68\x5f\x61\160\160\x5f\x74\171\160\x65"]) : stripslashes("\157\x61\165\164\150");
        if (!($Oj["\x61\160\x70\x5f\164\x79\x70\x65"] == "\x6f\141\x75\x74\150" || $Oj["\x61\x70\x70\137\164\x79\x70\145"] == "\x6f\141\165\164\150\x31" || isset($post["\x6d\x6f\x5f\x6f\x61\x75\x74\150\137\x72\145\x73\157\x75\162\143\x65\x6f\167\156\145\162\144\145\x74\141\151\x6c\163\165\x72\x6c"]))) {
            goto S6;
        }
        $Oj["\x72\145\x73\x6f\165\162\x63\145\157\167\x6e\x65\162\144\145\164\141\x69\x6c\x73\x75\162\x6c"] = $nI;
        S6:
        return $Oj;
    }
    public function change_attribute_mapping($post, $Oj)
    {
        $Et = stripslashes($post["\x6d\157\137\x6f\141\x75\164\x68\x5f\165\x73\145\162\156\x61\x6d\145\x5f\141\x74\164\162"]);
        $Oj["\x75\163\x65\x72\x6e\141\x6d\x65\x5f\x61\164\164\x72"] = $Et;
        return $Oj;
    }
}
