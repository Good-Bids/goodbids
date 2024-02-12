<?php


namespace MoOauthClient\Premium;

use MoOauthClient\App;
use MoOauthClient\Standard\AppSettings as StandardAppSettings;
class AppSettings extends StandardAppSettings
{
    public function __construct()
    {
        parent::__construct();
        add_action("\155\157\137\157\x61\x75\x74\x68\137\143\154\x69\145\x6e\x74\x5f\x73\141\x76\x65\x5f\x61\160\x70\137\x73\x65\164\x74\x69\x6e\x67\163\x5f\151\156\164\x65\162\156\141\x6c", array($this, "\163\141\166\145\x5f\x72\157\x6c\x65\137\x6d\x61\160\160\x69\156\147"));
    }
    public function change_app_settings($post, $eL)
    {
        global $Yh;
        $eL = parent::change_app_settings($post, $eL);
        $eL["\x67\x72\x6f\x75\160\x64\145\x74\141\x69\x6c\163\165\162\154"] = isset($post["\155\157\x5f\157\141\165\164\150\x5f\147\162\x6f\165\x70\144\145\164\x61\x69\154\163\x75\162\154"]) ? trim(stripslashes($post["\155\x6f\x5f\x6f\x61\165\x74\150\137\x67\x72\x6f\165\x70\x64\145\164\x61\x69\x6c\163\165\x72\x6c"])) : '';
        $eL["\147\162\x61\156\x74\x5f\x74\171\160\145"] = isset($post["\x67\x72\x61\x6e\164\137\x74\x79\160\145"]) ? stripslashes($post["\147\162\141\156\x74\137\164\x79\160\145"]) : "\x41\165\164\x68\x6f\x72\151\x7a\x61\164\151\157\x6e\40\103\x6f\x64\x65\40\x47\x72\x61\x6e\164";
        if (isset($post["\x65\x6e\141\142\x6c\145\137\x6f\x61\165\x74\150\137\167\x70\137\x6c\157\x67\x69\156"]) && "\157\x6e" === $post["\145\156\141\x62\x6c\145\137\157\141\x75\x74\150\137\167\160\137\x6c\157\147\151\156"]) {
            goto UR;
        }
        $Yh->mo_oauth_client_delete_option("\x6d\x6f\x5f\x6f\141\165\x74\x68\x5f\x65\156\141\142\x6c\x65\x5f\x6f\141\x75\x74\x68\137\167\160\x5f\x6c\157\x67\x69\x6e");
        goto sK;
        UR:
        $Yh->mo_oauth_client_update_option("\x6d\157\137\157\x61\165\164\x68\137\145\x6e\141\142\x6c\x65\x5f\157\x61\x75\164\x68\x5f\167\160\137\154\157\147\x69\x6e", isset($_GET["\141\160\x70"]) ? sanitize_text_field(wp_unslash($_GET["\x61\x70\160"])) : '');
        sK:
        return $eL;
    }
    public function save_advanced_grant_settings()
    {
        if (!(!isset($_POST["\x6d\157\x5f\157\x61\165\164\150\137\x67\162\141\x6e\164\137\163\145\164\x74\x69\156\x67\x73\137\156\x6f\156\x63\145"]) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST["\155\x6f\137\x6f\141\165\164\x68\x5f\147\162\x61\x6e\x74\x5f\x73\145\164\x74\x69\x6e\147\x73\137\156\157\156\143\x65"])), "\x6d\x6f\137\157\141\165\x74\150\x5f\147\x72\x61\x6e\x74\137\x73\x65\164\164\x69\156\147\163"))) {
            goto h7;
        }
        return;
        h7:
        $post = $_POST;
        if (!(!isset($post[\MoOAuthConstants::OPTION]) || "\x6d\157\x5f\x6f\x61\165\164\x68\137\147\162\141\x6e\164\x5f\163\x65\164\x74\151\x6e\147\x73" !== $post[\MoOAuthConstants::OPTION])) {
            goto QH;
        }
        return;
        QH:
        if (!(!isset($post[\MoOAuthConstants::POST_APP_NAME]) || empty($post[\MoOAuthConstants::POST_APP_NAME]))) {
            goto sr;
        }
        return;
        sr:
        global $Yh;
        $Wb = $Yh->get_plugin_config()->get_current_config();
        $g9 = "\x64\x69\163\141\x62\154\145\x64";
        $g9 = $Yh->mo_oauth_aemoutcrahsaphtn();
        if (!($g9 == "\144\151\163\141\142\x6c\145\144")) {
            goto yk;
        }
        if (!current_user_can("\141\x64\155\x69\x6e\x69\163\164\x72\x61\x74\157\x72")) {
            goto Wh;
        }
        $zl = $post[\MoOAuthConstants::POST_APP_NAME];
        $eL = $Yh->get_app_by_name($zl);
        $eL = $eL->get_app_config('', false);
        $eL = $this->save_grant_settings($post, $eL);
        $Yh->set_app_by_name($zl, $eL);
        $Yh->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\131\x6f\165\x72\40\x53\x65\x74\164\x69\x6e\x67\x73\x20\150\141\166\x65\40\142\x65\x65\x6e\40\x73\x61\166\145\x64\40\x73\165\143\143\x65\x73\163\x66\165\x6c\154\171\x2e");
        $Yh->mo_oauth_show_success_message();
        wp_safe_redirect("\141\144\x6d\x69\156\56\x70\x68\160\77\160\141\x67\145\x3d\155\157\137\x6f\x61\165\x74\x68\137\163\145\x74\164\151\x6e\147\x73\46\141\143\164\x69\x6f\156\75\x75\160\x64\x61\164\145\46\141\160\x70\x3d" . rawurlencode($zl));
        Wh:
        yk:
    }
    public function save_grant_settings($post, $eL)
    {
        global $Yh;
        $eL["\155\x6f\x5f\157\x61\x75\164\x68\x5f\162\x65\163\x70\157\156\163\145\137\164\x79\160\x65"] = isset($post["\x6d\157\x5f\157\141\165\164\x68\x5f\x72\145\x73\x70\157\156\163\145\137\164\171\160\x65"]) ? sanitize_text_field(wp_unslash($post["\155\x6f\x5f\x6f\x61\165\164\x68\137\x72\145\163\x70\x6f\x6e\163\145\x5f\164\x79\160\x65"])) : '';
        $eL["\152\x77\x74\137\163\x75\x70\160\157\162\164"] = isset($post["\152\167\164\137\163\x75\160\x70\x6f\x72\164"]) ? 1 : 0;
        $eL["\162\163\141\137\143\145\162\x74\137\x74\x79\x70\x65"] = isset($post["\162\163\141\137\x63\145\162\164\x5f\164\x79\160\145"]) ? stripslashes($post["\x72\163\x61\x5f\143\145\x72\x74\x5f\x74\171\160\145"]) : "\x4a\127\x4b\x53";
        $eL["\x6a\167\164\x5f\141\154\147\x6f"] = isset($post["\152\x77\164\x5f\141\x6c\147\157"]) ? stripslashes($post["\152\167\164\x5f\141\154\147\x6f"]) : "\x48\x53\x41";
        if ("\x52\x53\x41" === $eL["\152\x77\x74\137\141\154\x67\x6f"] && "\112\x57\x4b\123" === $eL["\x72\x73\x61\x5f\143\145\x72\164\137\164\x79\x70\x65"]) {
            goto oQ;
        }
        if ("\122\123\101" === $eL["\152\167\164\137\141\154\x67\157"] && "\x58\x35\x30\x39" === $eL["\162\x73\141\137\143\x65\x72\x74\137\x74\171\160\x65"]) {
            goto Kz;
        }
        if (!(isset($eL["\170\x35\x30\x39\137\x63\145\x72\x74"]) || isset($eL["\152\167\x6b\163\x75\x72\154"]))) {
            goto z6;
        }
        unset($eL["\x78\65\x30\71\137\143\145\162\x74"]);
        unset($eL["\x6a\x77\153\x73\x75\x72\x6c"]);
        z6:
        goto Be;
        Kz:
        $eL["\170\x35\x30\71\x5f\143\145\x72\x74"] = isset($post["\155\157\x5f\x6f\x61\x75\164\x68\137\x78\x35\x30\x39\x5f\x63\145\162\x74"]) ? stripslashes($post["\155\157\x5f\x6f\141\x75\x74\150\x5f\x78\x35\x30\x39\x5f\143\145\x72\x74"]) : '';
        unset($eL["\x6a\167\x6b\163\x75\162\154"]);
        Be:
        goto id;
        oQ:
        $eL["\x6a\167\153\163\165\x72\154"] = isset($post["\x6d\157\x5f\157\141\x75\x74\x68\137\x6a\x77\x6b\x73\x75\162\x6c"]) ? trim(stripslashes($post["\x6d\157\137\157\x61\x75\164\150\137\152\x77\153\163\x75\162\154"])) : '';
        unset($eL["\x78\x35\60\71\x5f\x63\x65\x72\164"]);
        id:
        return $eL;
    }
    public function change_attribute_mapping($post, $eL)
    {
        $eL = parent::change_attribute_mapping($post, $eL);
        $Sg = array();
        $YI = 0;
        foreach ($post as $cW => $LQ) {
            if (!(strpos($cW, "\x6d\x6f\137\157\x61\x75\x74\x68\x5f\143\x6c\151\x65\x6e\x74\x5f\143\165\163\x74\157\155\x5f\x61\x74\164\x72\151\142\x75\x74\x65\137\x6b\x65\171") !== false && !empty($post[$cW]))) {
                goto vq;
            }
            $ot = strrpos($cW, "\137", -1);
            $YI = substr($cW, $ot + 1);
            $Yp = "\x6d\x6f\137\x6f\x61\165\164\150\x5f\143\154\151\145\156\x74\x5f\143\165\163\x74\x6f\x6d\x5f\141\164\164\162\151\142\165\164\145\137\166\141\154\x75\x65\137" . $YI;
            if (!($post[$Yp] == '')) {
                goto ry;
            }
            goto Lh;
            ry:
            $Sg[$LQ] = sanitize_text_field(wp_unslash($post[$Yp]));
            vq:
            Lh:
        }
        Jd:
        $eL["\x63\x75\x73\x74\157\155\x5f\x61\164\164\x72\x73\x5f\x6d\x61\x70\160\x69\x6e\147"] = $Sg;
        return $eL;
    }
    public function save_role_mapping()
    {
        global $Yh;
        $Wb = $Yh->get_plugin_config()->get_current_config();
        $g9 = "\x64\x69\x73\x61\142\x6c\x65\144";
        $g9 = $Yh->mo_oauth_aemoutcrahsaphtn();
        if (!($g9 == "\144\x69\163\x61\x62\x6c\145\144")) {
            goto eR;
        }
        if (!(isset($_POST["\x6d\x6f\x5f\157\141\165\x74\150\x5f\143\x6c\x69\145\156\164\137\163\x61\x76\145\137\162\157\x6c\145\137\x6d\x61\x70\160\x69\156\147\137\x6e\157\x6e\143\x65"]) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST["\x6d\x6f\137\157\141\x75\x74\150\137\143\154\151\x65\156\x74\137\163\x61\x76\x65\x5f\x72\x6f\x6c\x65\137\x6d\141\x70\160\x69\156\x67\137\x6e\157\156\143\x65"])), "\x6d\x6f\x5f\157\141\x75\x74\150\x5f\143\x6c\x69\145\156\164\x5f\x73\x61\166\145\x5f\162\157\x6c\145\137\x6d\x61\160\160\x69\x6e\147") && isset($_POST[\MoOAuthConstants::OPTION]) && "\155\157\x5f\x6f\141\x75\164\150\137\x63\x6c\151\x65\156\164\137\x73\x61\x76\145\x5f\x72\157\154\145\137\x6d\141\x70\160\151\156\147" === sanitize_text_field(wp_unslash($_POST[\MoOAuthConstants::OPTION])))) {
            goto JF;
        }
        if (!current_user_can("\141\x64\x6d\151\156\151\163\x74\162\141\164\157\162")) {
            goto Ji;
        }
        $d9 = isset($_POST[\MoOAuthConstants::POST_APP_NAME]) ? sanitize_text_field(wp_unslash($_POST[\MoOAuthConstants::POST_APP_NAME])) : '';
        $F8 = $Yh->get_app_by_name($d9);
        $KY = $F8->get_app_config('', false);
        $KY["\162\145\x73\x74\162\151\143\164\x5f\x6c\157\147\x69\156\x5f\146\157\x72\x5f\x6d\141\160\x70\x65\144\x5f\162\157\154\145\x73"] = isset($_POST["\x72\145\163\164\x72\151\x63\x74\137\154\157\x67\151\x6e\137\x66\157\x72\x5f\155\x61\160\160\x65\144\137\162\x6f\x6c\145\x73"]) ? sanitize_text_field(wp_unslash($_POST["\x72\145\x73\x74\162\x69\x63\164\x5f\x6c\x6f\x67\151\x6e\x5f\x66\x6f\162\137\155\141\160\160\x65\144\x5f\x72\157\x6c\145\163"])) : false;
        $KY["\x65\x78\x74\x72\x61\143\x74\137\x65\x6d\x61\151\x6c\x5f\x64\x6f\x6d\x61\151\156\137\146\x6f\x72\137\162\x6f\154\x65\x6d\141\x70\160\x69\156\147"] = isset($_POST["\x65\170\164\162\x61\x63\164\137\x65\x6d\x61\x69\x6c\x5f\144\157\155\141\x69\x6e\137\x66\x6f\162\x5f\162\x6f\154\x65\155\x61\x70\160\x69\156\x67"]) ? sanitize_text_field(wp_unslash($_POST["\x65\x78\164\x72\141\143\164\137\x65\x6d\141\151\x6c\137\144\x6f\x6d\x61\x69\156\137\146\x6f\162\137\x72\x6f\x6c\x65\155\x61\x70\x70\151\x6e\147"])) : false;
        $KY["\x67\x72\157\165\160\156\141\x6d\x65\137\141\x74\164\162\x69\x62\x75\x74\145"] = isset($_POST["\155\141\160\160\x69\x6e\147\x5f\x67\x72\x6f\165\x70\x6e\141\155\x65\x5f\x61\x74\x74\x72\151\x62\165\x74\x65"]) ? trim(sanitize_text_field(wp_unslash($_POST["\x6d\141\160\160\x69\x6e\147\137\147\162\157\165\160\x6e\141\155\x65\137\141\x74\164\x72\151\142\x75\x74\x65"]))) : '';
        $dK = 100;
        $k9 = 0;
        $kE = [];
        if (!isset($_POST["\x6d\x61\x70\x70\x69\x6e\147\x5f\153\x65\x79\137"])) {
            goto b_;
        }
        $kE = array_map("\x73\x61\x6e\151\164\x69\172\145\137\164\x65\x78\x74\x5f\x66\x69\x65\x6c\144", wp_unslash($_POST["\155\141\x70\160\151\156\x67\137\153\145\171\137"]));
        b_:
        $Ud = count($kE);
        $jB = 1;
        $y5 = 1;
        yI:
        if (!($y5 <= $Ud)) {
            goto im;
        }
        if (isset($_POST["\155\141\160\160\x69\x6e\147\x5f\x6b\x65\x79\x5f"][$jB])) {
            goto hj;
        }
        n8:
        if (!($jB < 100)) {
            goto nK;
        }
        if (!isset($_POST["\155\141\160\160\x69\x6e\x67\x5f\x6b\x65\x79\137"][$jB])) {
            goto N0;
        }
        if (!('' === $_POST["\155\141\x70\x70\x69\x6e\x67\137\x6b\x65\x79\x5f"][$jB]["\x76\141\x6c\x75\x65"])) {
            goto sY;
        }
        $jB++;
        goto n8;
        sY:
        $KY["\x5f\x6d\141\160\x70\x69\x6e\147\x5f\x6b\x65\171\137" . $y5] = isset($_POST["\155\141\x70\x70\x69\x6e\147\137\x6b\x65\x79\x5f"][$jB]) ? sanitize_text_field(wp_unslash($_POST["\x6d\x61\160\x70\x69\x6e\x67\x5f\153\145\171\x5f"][$jB]["\166\141\x6c\165\x65"])) : '';
        $KY["\x5f\x6d\141\x70\160\x69\x6e\147\137\166\141\x6c\x75\x65\137" . $y5] = isset($_POST["\x6d\x61\160\x70\151\x6e\147\x5f\x6b\145\171\137"][$jB]) ? sanitize_text_field(wp_unslash($_POST["\x6d\141\x70\160\x69\x6e\147\x5f\x6b\x65\171\x5f"][$jB]["\x72\x6f\x6c\x65"])) : '';
        $k9++;
        $jB++;
        goto nK;
        N0:
        $jB++;
        goto n8;
        nK:
        goto PW;
        hj:
        if (!('' === $_POST["\155\x61\160\x70\x69\x6e\x67\x5f\x6b\145\171\x5f"][$jB]["\x76\141\154\165\x65"])) {
            goto NF;
        }
        $jB++;
        goto HH;
        NF:
        $KY["\x5f\x6d\141\160\160\x69\x6e\x67\137\x6b\145\x79\137" . $y5] = isset($_POST["\x6d\x61\160\160\151\x6e\147\137\153\x65\x79\137"][$jB]) ? sanitize_text_field(wp_unslash($_POST["\x6d\x61\x70\x70\x69\156\x67\137\x6b\145\171\x5f"][$jB]["\x76\141\x6c\x75\145"])) : '';
        $KY["\x5f\x6d\x61\x70\160\x69\156\x67\137\x76\141\x6c\165\145\137" . $y5] = isset($_POST["\x6d\x61\x70\160\151\x6e\147\137\x6b\x65\x79\137"][$jB]) ? sanitize_text_field(wp_unslash($_POST["\x6d\x61\x70\x70\151\156\147\x5f\x6b\x65\171\x5f"][$jB]["\x72\157\154\145"])) : '';
        $jB++;
        $k9++;
        PW:
        HH:
        $y5++;
        goto yI;
        im:
        $KY["\162\x6f\x6c\x65\137\x6d\x61\x70\160\x69\x6e\x67\137\143\x6f\x75\x6e\164"] = $k9;
        $uw = $Yh->set_app_by_name($d9, $KY);
        if (!$uw) {
            goto nj;
        }
        $Yh->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\x59\x6f\x75\x72\x20\163\145\x74\164\151\156\147\163\40\141\x72\x65\x20\x73\141\x76\145\x64\x20\163\x75\x63\x63\145\163\x73\x66\x75\154\x6c\x79\x2e");
        $Yh->mo_oauth_show_success_message();
        goto rH;
        nj:
        $Yh->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\124\150\x65\162\x65\x20\x77\x61\x73\40\x61\x6e\x20\145\162\162\x6f\162\x20\163\141\166\151\156\147\x20\163\145\x74\x74\x69\156\x67\x73\56");
        $Yh->mo_oauth_show_error_message();
        rH:
        wp_safe_redirect("\141\144\155\151\156\x2e\x70\150\x70\x3f\160\x61\147\145\75\x6d\x6f\x5f\x6f\x61\165\164\x68\137\163\145\x74\164\151\156\x67\163\x26\164\141\x62\75\143\157\x6e\x66\x69\147\x26\141\143\164\x69\157\156\x3d\165\160\144\141\x74\145\46\x61\160\x70\75" . rawurlencode($d9));
        Ji:
        JF:
        eR:
    }
}
