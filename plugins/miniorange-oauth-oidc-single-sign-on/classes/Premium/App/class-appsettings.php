<?php


namespace MoOauthClient\Premium;

use MoOauthClient\App;
use MoOauthClient\Standard\AppSettings as StandardAppSettings;
class AppSettings extends StandardAppSettings
{
    public function __construct()
    {
        parent::__construct();
        add_action("\x6d\157\137\x6f\x61\x75\x74\150\137\x63\154\x69\x65\x6e\164\137\x73\x61\166\x65\137\141\x70\x70\x5f\163\145\164\164\x69\x6e\x67\163\x5f\x69\156\x74\145\x72\156\141\154", array($this, "\x73\141\x76\145\x5f\162\157\154\145\137\x6d\x61\160\160\x69\156\147"));
    }
    public function change_app_settings($post, $Oj)
    {
        global $Uj;
        $Oj = parent::change_app_settings($post, $Oj);
        $Oj["\x67\x72\x6f\165\x70\144\145\x74\x61\151\154\x73\x75\162\x6c"] = isset($post["\x6d\157\137\x6f\141\x75\164\150\137\x67\x72\157\165\x70\144\x65\164\x61\151\154\x73\x75\162\x6c"]) ? trim(stripslashes($post["\155\157\x5f\157\x61\165\x74\150\x5f\x67\162\x6f\165\160\x64\145\164\x61\151\x6c\x73\x75\162\154"])) : '';
        $Oj["\152\167\x6b\163\165\x72\154"] = isset($post["\x6d\x6f\137\157\141\165\x74\150\x5f\152\167\x6b\163\165\162\154"]) ? trim(stripslashes($post["\155\157\137\x6f\x61\x75\164\x68\137\x6a\x77\x6b\x73\165\x72\154"])) : '';
        $Oj["\x67\162\x61\156\x74\137\x74\x79\x70\145"] = isset($post["\147\162\141\156\164\137\164\x79\160\145"]) ? stripslashes($post["\147\x72\x61\x6e\164\137\164\x79\160\x65"]) : "\101\x75\164\x68\157\x72\x69\x7a\x61\x74\151\x6f\156\40\x43\x6f\144\145\x20\107\162\141\156\x74";
        if (isset($post["\x65\x6e\141\142\x6c\x65\137\157\141\165\164\150\x5f\x77\160\137\x6c\157\147\x69\x6e"]) && "\x6f\156" === $post["\x65\x6e\141\142\154\x65\x5f\157\141\165\x74\x68\x5f\167\160\x5f\154\x6f\x67\x69\156"]) {
            goto uM;
        }
        $Uj->mo_oauth_client_delete_option("\155\x6f\137\x6f\141\x75\x74\150\x5f\x65\x6e\x61\x62\154\x65\137\x6f\x61\x75\x74\x68\137\167\160\x5f\154\157\x67\151\156");
        goto kt;
        uM:
        $Uj->mo_oauth_client_update_option("\155\x6f\137\157\141\165\x74\x68\137\x65\x6e\x61\142\154\145\137\157\141\165\164\150\137\x77\x70\x5f\x6c\157\147\x69\156", $_GET["\141\x70\x70"]);
        kt:
        return $Oj;
    }
    public function save_advanced_grant_settings()
    {
        if (!(!isset($_POST["\x6d\x6f\137\x6f\141\x75\164\150\x5f\147\162\141\x6e\x74\x5f\x73\145\164\x74\151\x6e\147\x73\137\x6e\157\156\x63\145"]) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST["\155\157\137\157\141\165\164\x68\137\147\x72\141\156\x74\x5f\x73\x65\x74\x74\x69\x6e\x67\x73\x5f\x6e\x6f\156\143\145"])), "\x6d\x6f\x5f\x6f\x61\x75\x74\x68\x5f\147\x72\141\x6e\164\x5f\x73\x65\x74\164\x69\156\x67\x73"))) {
            goto E4;
        }
        return;
        E4:
        $post = $_POST;
        if (!(!isset($post[\MoOAuthConstants::OPTION]) || "\x6d\157\137\157\x61\165\x74\x68\x5f\x67\x72\141\156\164\x5f\163\x65\164\164\151\x6e\x67\x73" !== $post[\MoOAuthConstants::OPTION])) {
            goto Eb;
        }
        return;
        Eb:
        if (!(!isset($post[\MoOAuthConstants::POST_APP_NAME]) || empty($post[\MoOAuthConstants::POST_APP_NAME]))) {
            goto HE;
        }
        return;
        HE:
        global $Uj;
        $Kn = $Uj->get_plugin_config()->get_current_config();
        $MS = "\144\151\163\141\142\x6c\x65\x64";
        if (empty($Kn["\155\x6f\x5f\144\164\145\137\x73\x74\x61\x74\145"])) {
            goto w_;
        }
        $MS = $Uj->mooauthdecrypt($Kn["\155\x6f\x5f\x64\164\145\137\x73\x74\x61\164\145"]);
        w_:
        if (!($MS == "\144\151\x73\141\x62\x6c\145\x64")) {
            goto By;
        }
        $gR = $post[\MoOAuthConstants::POST_APP_NAME];
        $Oj = $Uj->get_app_by_name($gR);
        $Oj = $Oj->get_app_config('', false);
        $Oj = $this->save_grant_settings($post, $Oj);
        $Uj->set_app_by_name($gR, $Oj);
        $Uj->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\131\x6f\x75\162\x20\x53\x65\x74\x74\151\x6e\147\163\x20\x68\x61\166\x65\40\142\x65\x65\x6e\40\163\141\x76\x65\144\x20\163\165\143\143\145\x73\163\x66\x75\x6c\x6c\x79\56");
        $Uj->mo_oauth_show_success_message();
        wp_safe_redirect("\141\x64\155\x69\x6e\56\160\150\160\x3f\160\x61\x67\x65\75\x6d\x6f\137\157\141\x75\164\x68\137\163\x65\x74\x74\151\156\x67\x73\x26\x61\143\x74\151\x6f\156\75\x75\160\144\x61\x74\145\x26\x61\x70\x70\x3d" . rawurlencode($gR));
        By:
    }
    public function save_grant_settings($post, $Oj)
    {
        global $Uj;
        $Oj["\155\x6f\137\x6f\x61\165\164\150\137\x72\x65\x73\160\x6f\156\163\145\x5f\164\x79\x70\145"] = isset($post["\155\x6f\x5f\x6f\141\x75\x74\x68\x5f\162\145\x73\x70\x6f\156\x73\145\x5f\x74\171\160\145"]) ? stripslashes($post["\155\157\137\157\x61\165\164\x68\137\162\x65\x73\160\157\x6e\163\x65\137\x74\171\x70\145"]) : '';
        $Oj["\x6a\x77\164\x5f\163\x75\x70\x70\157\x72\x74"] = isset($post["\152\x77\164\137\163\x75\160\160\157\x72\x74"]) ? 1 : 0;
        $Oj["\152\x77\x74\x5f\141\x6c\147\x6f"] = isset($post["\152\x77\x74\137\x61\154\x67\x6f"]) ? stripslashes($post["\152\x77\164\x5f\x61\x6c\147\x6f"]) : "\110\123\x41";
        if ("\x52\x53\101" === $Oj["\x6a\x77\x74\137\x61\154\147\x6f"]) {
            goto tR;
        }
        if (!isset($Oj["\x78\65\60\x39\x5f\x63\x65\162\x74"])) {
            goto sG;
        }
        unset($Oj["\x78\x35\60\x39\137\143\145\162\164"]);
        sG:
        goto QJ;
        tR:
        $Oj["\170\x35\x30\x39\x5f\143\x65\162\164"] = isset($post["\x6d\157\137\x6f\x61\x75\164\x68\x5f\x78\65\60\71\x5f\x63\145\x72\164"]) ? stripslashes($post["\155\157\x5f\x6f\x61\x75\164\150\137\x78\x35\x30\x39\137\143\145\162\x74"]) : '';
        QJ:
        return $Oj;
    }
    public function change_attribute_mapping($post, $Oj)
    {
        $Oj = parent::change_attribute_mapping($post, $Oj);
        $cS = array();
        $zY = 0;
        foreach ($post as $Mr => $t_) {
            if (!(strpos($Mr, "\x6d\x6f\137\157\141\x75\164\150\x5f\x63\x6c\151\145\156\164\x5f\x63\165\x73\164\x6f\155\x5f\x61\x74\164\x72\151\x62\x75\164\x65\x5f\x6b\x65\x79") !== false && !empty($post[$Mr]))) {
                goto V2;
            }
            $zY++;
            $DD = "\x6d\x6f\137\x6f\141\165\164\x68\137\x63\x6c\151\145\156\164\x5f\x63\x75\x73\x74\x6f\155\137\141\x74\x74\x72\151\x62\x75\x74\145\137\166\x61\x6c\165\x65\x5f" . $zY;
            $cS[$t_] = $post[$DD];
            V2:
            hD:
        }
        B1:
        $Oj["\143\x75\x73\x74\x6f\155\x5f\x61\x74\x74\x72\x73\137\155\141\160\160\151\156\147"] = $cS;
        return $Oj;
    }
    public function save_role_mapping()
    {
        global $Uj;
        $Kn = $Uj->get_plugin_config()->get_current_config();
        $MS = "\144\x69\x73\x61\x62\x6c\x65\x64";
        if (empty($Kn["\x6d\157\137\x64\x74\145\137\163\164\x61\x74\145"])) {
            goto Fq;
        }
        $MS = $Uj->mooauthdecrypt($Kn["\155\157\x5f\144\164\145\137\x73\164\x61\164\145"]);
        Fq:
        if (!($MS == "\144\x69\x73\141\x62\154\145\x64")) {
            goto Im;
        }
        if (!(isset($_POST["\x6d\157\137\x6f\x61\x75\x74\150\x5f\143\154\x69\x65\156\x74\137\163\141\x76\145\137\162\157\154\145\137\x6d\x61\x70\x70\151\x6e\x67\137\x6e\157\156\143\145"]) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST["\155\x6f\x5f\157\141\x75\164\x68\x5f\143\x6c\151\145\x6e\164\137\163\141\x76\145\137\x72\157\154\145\137\155\141\x70\x70\151\x6e\x67\137\x6e\157\156\143\145"])), "\155\157\x5f\x6f\x61\165\x74\150\x5f\143\x6c\151\x65\156\164\137\x73\141\x76\x65\x5f\x72\x6f\x6c\x65\137\155\x61\x70\160\151\x6e\x67") && isset($_POST[\MoOAuthConstants::OPTION]) && "\155\x6f\137\x6f\x61\x75\164\150\137\143\x6c\151\x65\x6e\x74\x5f\x73\x61\x76\x65\137\x72\x6f\154\x65\x5f\155\141\x70\x70\x69\156\147" === $_POST[\MoOAuthConstants::OPTION])) {
            goto KL;
        }
        $BW = sanitize_text_field(wp_unslash(isset($_POST[\MoOAuthConstants::POST_APP_NAME]) ? $_POST[\MoOAuthConstants::POST_APP_NAME] : ''));
        $Fr = $Uj->get_app_by_name($BW);
        $Wh = $Fr->get_app_config('', false);
        $Wh["\x72\145\163\164\x72\x69\143\164\x5f\154\157\147\151\156\137\x66\157\162\x5f\155\x61\160\160\x65\144\x5f\x72\157\x6c\x65\x73"] = isset($_POST["\162\145\163\x74\x72\151\x63\164\137\x6c\157\x67\x69\156\137\146\157\x72\137\x6d\x61\160\160\x65\144\x5f\x72\157\x6c\145\163"]) ? sanitize_text_field(wp_unslash($_POST["\162\145\x73\x74\162\x69\x63\x74\137\154\157\147\151\x6e\x5f\146\x6f\162\137\155\141\x70\x70\x65\144\x5f\x72\x6f\x6c\x65\163"])) : false;
        $Wh["\147\162\x6f\x75\x70\x6e\141\x6d\145\x5f\141\164\164\162\151\142\165\x74\x65"] = isset($_POST["\x6d\141\x70\x70\151\x6e\147\137\147\162\x6f\x75\160\156\x61\155\145\137\x61\x74\164\162\x69\142\x75\164\145"]) ? trim(stripslashes($_POST["\x6d\x61\160\x70\x69\156\147\137\147\x72\157\x75\160\156\141\155\145\x5f\x61\x74\x74\162\x69\142\165\164\145"])) : '';
        $ry = 100;
        $Bw = 0;
        $HS = [];
        if (!isset($_POST["\x6d\x61\x70\160\x69\x6e\147\x5f\153\145\x79\x5f"])) {
            goto BQ;
        }
        $HS = array_map("\x73\x61\x6e\x69\x74\x69\x7a\x65\x5f\164\145\x78\164\x5f\146\x69\145\154\144", wp_unslash($_POST["\155\141\x70\160\151\x6e\147\137\x6b\x65\171\x5f"]));
        BQ:
        $oi = count($HS);
        $PF = 1;
        $eH = 1;
        ct:
        if (!($eH <= $oi)) {
            goto aj;
        }
        if (isset($_POST["\155\141\160\160\151\x6e\147\x5f\x6b\145\x79\137"][$PF])) {
            goto AY;
        }
        jF:
        if (!($PF < 100)) {
            goto kK;
        }
        if (!isset($_POST["\x6d\141\160\x70\x69\156\147\137\153\x65\171\137"][$PF])) {
            goto qA;
        }
        if (!('' === $_POST["\x6d\141\x70\160\151\156\147\137\153\145\x79\137"][$PF]["\x76\141\x6c\165\x65"])) {
            goto tT;
        }
        $PF++;
        goto jF;
        tT:
        $Wh["\137\x6d\x61\x70\160\151\156\x67\x5f\x6b\x65\171\x5f" . $eH] = sanitize_text_field(wp_unslash(isset($_POST["\155\141\x70\160\x69\156\x67\x5f\153\145\171\x5f"][$PF]) ? $_POST["\155\x61\160\160\x69\156\147\137\x6b\145\x79\137"][$PF]["\x76\141\x6c\165\x65"] : ''));
        $Wh["\x5f\x6d\141\160\160\x69\156\147\x5f\x76\x61\x6c\165\x65\x5f" . $eH] = sanitize_text_field(wp_unslash(isset($_POST["\155\141\160\160\151\156\147\x5f\153\x65\171\137"][$PF]) ? $_POST["\155\x61\x70\x70\151\156\147\137\x6b\145\171\137"][$PF]["\162\157\x6c\145"] : ''));
        $Bw++;
        $PF++;
        goto kK;
        qA:
        $PF++;
        goto jF;
        kK:
        goto lH;
        AY:
        if (!('' === $_POST["\x6d\141\160\x70\151\156\147\x5f\x6b\145\x79\137"][$PF]["\166\x61\x6c\165\x65"])) {
            goto tn;
        }
        $PF++;
        goto z1;
        tn:
        $Wh["\x5f\x6d\141\x70\160\151\x6e\x67\x5f\153\145\171\x5f" . $eH] = sanitize_text_field(wp_unslash(isset($_POST["\155\141\x70\x70\151\x6e\147\x5f\153\145\171\137"][$PF]) ? $_POST["\155\141\x70\x70\151\156\x67\137\x6b\145\x79\137"][$PF]["\166\x61\154\x75\145"] : ''));
        $Wh["\137\x6d\141\160\160\151\x6e\x67\x5f\166\141\154\x75\145\x5f" . $eH] = sanitize_text_field(wp_unslash(isset($_POST["\x6d\x61\x70\160\151\156\x67\x5f\153\x65\171\x5f"][$PF]) ? $_POST["\x6d\x61\x70\x70\x69\156\x67\137\153\x65\x79\137"][$PF]["\162\x6f\154\x65"] : ''));
        $PF++;
        $Bw++;
        lH:
        z1:
        $eH++;
        goto ct;
        aj:
        $Wh["\162\x6f\x6c\x65\137\x6d\141\160\160\x69\156\x67\x5f\143\x6f\165\x6e\x74"] = $Bw;
        $FO = $Uj->set_app_by_name($BW, $Wh);
        if (!$FO) {
            goto L4;
        }
        $Uj->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\131\157\x75\162\40\163\145\x74\x74\151\x6e\147\x73\x20\x61\x72\x65\40\163\141\166\x65\x64\40\x73\x75\x63\143\145\163\163\x66\165\154\x6c\171\x2e");
        $Uj->mo_oauth_show_success_message();
        goto ry;
        L4:
        $Uj->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\x54\x68\145\x72\x65\x20\167\141\163\40\x61\156\x20\x65\162\162\157\162\x20\x73\141\x76\x69\156\x67\x20\x73\x65\164\164\151\156\x67\x73\56");
        $Uj->mo_oauth_show_error_message();
        ry:
        wp_safe_redirect("\x61\144\155\x69\156\56\160\150\x70\77\x70\141\x67\145\75\155\x6f\x5f\x6f\x61\x75\164\x68\137\163\145\164\x74\151\x6e\x67\x73\46\x74\141\142\x3d\x63\157\156\146\151\147\46\141\x63\x74\151\x6f\156\75\x75\160\x64\141\164\x65\46\x61\160\x70\75" . rawurlencode($BW));
        KL:
        Im:
    }
}
