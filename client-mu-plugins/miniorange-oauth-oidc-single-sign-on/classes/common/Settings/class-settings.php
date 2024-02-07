<?php


namespace MoOauthClient;

use MoOauthClient\Backup\BackupHandler;
use MoOauthClient\mc_utils;
use MoOauthClient\Customer;
use MoOauthClient\Config;
class Settings
{
    public $config;
    public $util;
    public function __construct()
    {
        global $Yh;
        $this->util = $Yh;
        add_action("\x61\144\x6d\151\x6e\137\151\156\x69\164", array($this, "\x6d\151\156\151\x6f\x72\141\156\x67\x65\x5f\x6f\141\x75\164\150\137\163\141\166\x65\x5f\x73\x65\164\x74\151\x6e\x67\x73"));
        add_action("\141\144\155\x69\x6e\137\151\156\151\x74", array($this, "\155\x6f\x5f\157\141\165\164\x68\137\144\x65\x62\x75\147\137\x6c\x6f\147\x5f\141\152\141\x78\137\x68\x6f\157\x6b"));
        $this->config = $this->util->get_plugin_config();
    }
    function mo_oauth_debug_log_ajax_hook()
    {
        add_action("\x77\x70\x5f\141\152\x61\x78\x5f\155\x6f\x5f\157\x61\x75\164\x68\x5f\x64\x65\x62\165\x67\x5f\141\x6a\x61\x78", array($this, "\155\157\x5f\157\x61\x75\x74\150\x5f\144\x65\142\x75\147\x5f\x6c\157\x67\x5f\141\x6a\141\x78"));
    }
    function mo_oauth_debug_log_ajax()
    {
        if (!isset($_POST["\155\x6f\137\x6f\x61\x75\x74\150\137\x6e\x6f\156\143\x65"]) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST["\155\x6f\137\x6f\x61\x75\164\x68\137\156\157\x6e\x63\145"])), "\155\157\x2d\157\x61\165\x74\150\55\x44\x65\142\165\x67\x2d\x6c\x6f\x67\163\x2d\165\x6e\151\161\x75\x65\55\163\x74\x72\x69\x6e\147\55\x6e\157\x6e\143\x65")) {
            goto fh;
        }
        switch (sanitize_text_field(wp_unslash($_POST["\155\x6f\x5f\x6f\141\x75\164\150\137\x6f\x70\x74\151\x6f\x6e"]))) {
            case "\155\x6f\137\x6f\141\x75\x74\150\137\162\x65\163\145\x74\x5f\144\x65\x62\165\147":
                $this->mo_oauth_reset_debug();
                goto kE;
        }
        RF:
        kE:
        goto yT;
        fh:
        wp_send_json("\145\162\162\157\162");
        yT:
    }
    public function mo_oauth_reset_debug()
    {
        global $Yh;
        if (!current_user_can("\x61\x64\155\x69\x6e\x69\x73\x74\x72\141\x74\157\162")) {
            goto ph;
        }
        if (isset($_POST["\155\157\x5f\157\x61\165\164\150\x5f\157\160\164\151\x6f\x6e"]) and sanitize_text_field(wp_unslash($_POST["\155\157\137\x6f\x61\165\164\x68\x5f\x6f\160\164\x69\x6f\x6e"])) == "\x6d\x6f\x5f\157\x61\165\x74\x68\x5f\162\145\x73\145\x74\137\144\145\142\x75\x67" && isset($_REQUEST["\x6d\157\x5f\x6f\141\x75\164\150\137\156\157\156\x63\x65"]) && wp_verify_nonce(sanitize_text_field(wp_unslash($_REQUEST["\x6d\157\x5f\157\x61\165\x74\150\137\156\x6f\x6e\143\145"])), "\155\157\55\157\x61\x75\164\x68\x2d\104\x65\142\x75\147\55\154\157\147\x73\x2d\165\x6e\x69\x71\165\145\x2d\163\x74\x72\151\x6e\x67\55\x6e\x6f\x6e\x63\145")) {
            goto xg;
        }
        echo "\145\x72\x72\157\162";
        goto ck;
        xg:
        $Cz = false;
        if (!isset($_POST["\155\157\x5f\157\x61\165\164\150\137\x6d\157\x5f\x6f\141\x75\x74\x68\x5f\x64\x65\142\165\147\137\x63\x68\145\x63\x6b"])) {
            goto QP;
        }
        $Cz = sanitize_text_field(wp_unslash($_POST["\x6d\x6f\x5f\x6f\141\x75\x74\x68\137\x6d\x6f\x5f\x6f\141\x75\164\x68\137\x64\x65\142\165\x67\137\143\150\x65\143\x6b"]));
        QP:
        $wK = current_time("\164\x69\155\145\x73\x74\141\155\160");
        $Yh->mo_oauth_client_update_option("\155\x6f\137\144\x65\142\x75\x67\x5f\145\x6e\141\142\x6c\145", $Cz);
        if (!$Yh->mo_oauth_client_get_option("\155\157\137\x64\145\142\x75\x67\x5f\145\x6e\141\142\x6c\x65")) {
            goto jd;
        }
        $Yh->mo_oauth_client_update_option("\x6d\x6f\137\144\145\x62\165\x67\137\143\x68\x65\x63\153", 1);
        $Yh->mo_oauth_client_update_option("\x6d\157\137\144\x65\x62\x75\147\x5f\164\x69\x6d\x65", $wK);
        jd:
        if (!$Yh->mo_oauth_client_get_option("\x6d\157\137\144\x65\142\x75\x67\137\145\156\x61\142\x6c\145")) {
            goto Qd;
        }
        $Yh->mo_oauth_client_update_option("\x6d\x6f\x5f\x6f\141\165\x74\150\x5f\x64\x65\142\x75\147", "\x6d\x6f\137\157\141\x75\x74\150\137\144\x65\x62\165\147" . uniqid());
        $Ot = $Yh->mo_oauth_client_get_option("\155\x6f\x5f\157\x61\165\x74\150\137\144\145\142\165\x67");
        $Lj = dirname(__DIR__) . DIRECTORY_SEPARATOR . "\x4f\x41\x75\164\x68\x48\141\x6e\x64\x6c\x65\162" . DIRECTORY_SEPARATOR . $Ot . "\56\x6c\x6f\147";
        $Eg = fopen($Lj, "\x77");
        chmod($Lj, 0644);
        $Yh->mo_oauth_client_update_option("\x6d\x6f\137\144\145\142\165\x67\137\x63\x68\145\143\x6b", 1);
        MO_Oauth_Debug::mo_oauth_log('');
        $Yh->mo_oauth_client_update_option("\155\157\137\144\x65\142\x75\147\x5f\x63\x68\145\x63\153", 0);
        Qd:
        $ih = $Yh->mo_oauth_client_get_option("\x6d\157\137\144\x65\142\165\147\x5f\145\x6e\141\142\x6c\145");
        $uh["\163\x77\x69\164\x63\x68\x5f\163\164\141\164\x75\x73"] = $ih;
        wp_send_json($uh);
        ck:
        ph:
    }
    public function miniorange_oauth_save_settings()
    {
        global $Yh;
        $Wb = $Yh->get_plugin_config()->get_current_config();
        $g9 = "\144\x69\x73\x61\142\x6c\x65\144";
        $g9 = $Yh->mo_oauth_aemoutcrahsaphtn();
        if (!(isset($_POST["\x63\150\141\156\x67\145\x5f\155\x69\156\x69\x6f\x72\x61\156\x67\145\137\x6e\x6f\x6e\x63\145"]) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST["\143\150\x61\156\147\145\x5f\155\151\x6e\151\x6f\162\x61\x6e\147\x65\137\x6e\157\x6e\143\145"])), "\143\x68\x61\156\x67\145\x5f\155\151\x6e\x69\157\162\x61\x6e\147\145") && isset($_POST[\MoOAuthConstants::OPTION]) && "\143\150\x61\156\147\x65\x5f\155\151\x6e\151\x6f\162\141\x6e\147\x65" === sanitize_text_field(wp_unslash($_POST[\MoOAuthConstants::OPTION])))) {
            goto jY;
        }
        if (!current_user_can("\141\x64\x6d\x69\x6e\x69\x73\x74\162\x61\164\x6f\x72")) {
            goto XS;
        }
        mo_oauth_deactivate();
        return;
        XS:
        jY:
        if (!(isset($_POST["\x6d\x6f\x5f\x6f\x61\x75\x74\150\x5f\x65\156\x61\142\x6c\145\137\144\x65\142\165\147\x5f\144\x6f\x77\x6e\154\x6f\141\x64\137\x6e\x6f\156\143\145"]) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST["\155\x6f\137\157\x61\x75\164\x68\137\x65\156\141\142\154\x65\x5f\x64\145\x62\165\147\x5f\x64\157\x77\156\x6c\x6f\x61\x64\137\x6e\x6f\156\143\145"])), "\155\x6f\137\x6f\x61\165\x74\150\x5f\145\156\x61\142\154\x65\x5f\144\145\x62\x75\147\x5f\144\x6f\x77\x6e\x6c\157\141\x64") && isset($_POST[\MoOAuthConstants::OPTION]) && "\x6d\157\137\x6f\141\x75\164\150\137\x65\x6e\141\142\154\145\x5f\144\x65\x62\165\x67\x5f\144\157\167\x6e\154\157\x61\144" === sanitize_text_field(wp_unslash($_POST[\MoOAuthConstants::OPTION])))) {
            goto OM;
        }
        if (!current_user_can("\x61\x64\x6d\151\156\x69\163\x74\162\141\x74\x6f\162")) {
            goto Lk;
        }
        $cr = plugin_dir_path(__FILE__) . "\x2f\x2e\x2e\x2f\x4f\x41\165\164\x68\110\141\x6e\x64\x6c\145\162\57" . $Yh->mo_oauth_client_get_option("\x6d\157\x5f\x6f\x61\x75\164\x68\137\144\x65\x62\165\x67") . "\x2e\x6c\x6f\x67";
        if (is_file($cr)) {
            goto x1;
        }
        echo "\64\60\x34\40\106\x69\x6c\x65\x20\x6e\157\x74\x20\146\x6f\x75\x6e\144\x21";
        exit;
        x1:
        $LN = filesize($cr);
        $pP = basename($cr);
        $vW = strtolower(pathinfo($pP, PATHINFO_EXTENSION));
        $Rv = "\x61\x70\x70\x6c\x69\143\141\164\x69\157\x6e\57\x66\x6f\x72\x63\x65\x2d\x64\157\167\156\x6c\x6f\141\x64";
        if (!ob_get_contents()) {
            goto si;
        }
        ob_clean();
        si:
        header("\x50\162\x61\x67\x6d\x61\72\40\x70\165\x62\x6c\x69\x63");
        header("\105\170\160\151\162\x65\x73\72\x20\x30");
        header("\x43\141\143\x68\x65\x2d\x43\157\156\x74\162\157\x6c\72\x20\155\x75\163\x74\x2d\162\145\x76\x61\x6c\x69\x64\141\164\145\x2c\x20\160\157\x73\x74\55\143\150\x65\143\x6b\75\60\x2c\40\160\x72\145\x2d\x63\150\145\143\x6b\x3d\60");
        header("\103\x61\143\x68\145\x2d\x43\x6f\156\x74\x72\157\154\72\x20\160\x75\142\154\x69\x63");
        header("\x43\157\x6e\164\x65\x6e\x74\55\104\x65\x73\x63\162\x69\x70\164\x69\x6f\x6e\x3a\x20\x46\x69\x6c\x65\40\x54\x72\x61\x6e\163\x66\145\162");
        header("\x43\x6f\x6e\164\x65\x6e\x74\x2d\124\x79\x70\x65\x3a\x20{$Rv}");
        $JR = "\103\x6f\156\164\145\x6e\x74\55\x44\x69\163\x70\x6f\x73\151\x74\151\x6f\x6e\x3a\x20\141\164\x74\x61\x63\150\x6d\145\x6e\x74\x3b\40\x66\151\x6c\x65\x6e\x61\155\145\75" . $pP . "\73";
        header($JR);
        header("\x43\x6f\156\x74\x65\156\x74\55\x54\162\x61\x6e\163\x66\145\162\55\105\156\x63\157\x64\x69\x6e\147\72\40\x62\x69\156\141\x72\x79");
        header("\x43\157\156\164\x65\156\164\55\x4c\145\x6e\x67\164\x68\x3a\x20" . $LN);
        @readfile($cr);
        exit;
        Lk:
        OM:
        if (!(isset($_POST["\x6d\157\137\x6f\x61\x75\x74\x68\x5f\143\154\x65\141\162\x5f\x6c\157\x67\137\156\157\x6e\143\145"]) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST["\x6d\x6f\x5f\157\141\x75\164\x68\x5f\143\x6c\x65\141\x72\137\x6c\157\x67\x5f\x6e\157\156\x63\145"])), "\x6d\x6f\x5f\157\x61\165\x74\x68\137\143\154\x65\141\x72\137\154\157\147") && isset($_POST[\MoOAuthConstants::OPTION]) && "\x6d\157\137\x6f\x61\165\164\150\x5f\143\x6c\x65\x61\162\137\x6c\x6f\x67" === sanitize_text_field(wp_unslash($_POST[\MoOAuthConstants::OPTION])))) {
            goto aM;
        }
        if (!current_user_can("\141\x64\x6d\151\x6e\151\x73\164\x72\141\164\x6f\162")) {
            goto lK;
        }
        $cr = plugin_dir_path(__FILE__) . "\x2f\56\56\x2f\117\101\165\164\x68\x48\x61\156\144\154\x65\x72\x2f" . $Yh->mo_oauth_client_get_option("\x6d\x6f\137\157\x61\165\164\x68\x5f\x64\x65\x62\165\147") . "\x2e\154\157\147";
        if (is_file($cr)) {
            goto Mf;
        }
        echo "\64\x30\x34\40\x46\x69\154\x65\40\x6e\157\x74\40\146\157\165\x6e\x64\41";
        exit;
        Mf:
        file_put_contents($cr, '');
        file_put_contents($cr, "\124\x68\x69\163\40\x69\x73\40\164\x68\x65\40\x6d\151\x6e\151\117\162\x61\x6e\x67\x65\x20\117\x41\165\x74\150\40\x70\x6c\165\x67\x69\156\x20\104\145\142\x75\x67\40\114\157\147\x20\146\151\x6c\145");
        $this->util->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\x44\145\142\x75\147\x20\114\157\x67\163\40\143\x6c\145\141\x72\x65\144\40\x73\x75\143\x63\x65\x73\163\146\x75\x6c\154\x79\56");
        $this->util->mo_oauth_show_success_message();
        lK:
        aM:
        if (!(isset($_POST["\x6d\157\137\157\141\165\164\x68\x5f\162\145\147\x69\163\x74\x65\162\137\x63\165\x73\164\x6f\155\145\162\x5f\156\x6f\156\143\145"]) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST["\x6d\x6f\137\x6f\141\165\164\150\137\162\145\147\x69\163\x74\145\x72\137\x63\165\x73\x74\x6f\x6d\145\x72\137\156\157\156\x63\145"])), "\x6d\x6f\137\x6f\141\x75\164\x68\137\162\x65\x67\x69\x73\164\x65\162\x5f\x63\x75\163\x74\x6f\x6d\x65\162") && isset($_POST[\MoOAuthConstants::OPTION]) && "\x6d\157\x5f\157\x61\x75\164\x68\137\x72\145\147\151\x73\164\x65\x72\137\143\x75\163\x74\157\x6d\145\162" === sanitize_text_field(wp_unslash($_POST[\MoOAuthConstants::OPTION])))) {
            goto LQ;
        }
        if (!current_user_can("\x61\144\x6d\151\x6e\x69\163\x74\162\x61\164\x6f\x72")) {
            goto Pb;
        }
        $Mv = '';
        $ge = '';
        $hk = '';
        $iL = '';
        $QQ = '';
        $sQ = '';
        $Ga = '';
        if (!($this->util->mo_oauth_check_empty_or_null($_POST["\x65\155\141\151\x6c"]) || $this->util->mo_oauth_check_empty_or_null($_POST["\x70\141\163\163\x77\x6f\x72\x64"]) || $this->util->mo_oauth_check_empty_or_null($_POST["\x63\x6f\x6e\x66\x69\x72\155\x50\141\x73\163\x77\157\x72\x64"]))) {
            goto O2;
        }
        $this->util->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\x41\x6c\154\x20\164\150\145\x20\146\x69\145\154\x64\163\x20\x61\x72\145\40\x72\145\161\x75\151\162\x65\x64\56\x20\120\x6c\x65\x61\163\x65\40\x65\x6e\164\145\162\40\166\x61\154\x69\144\x20\x65\156\164\x72\x69\145\x73\x2e");
        $this->util->mo_oauth_show_error_message();
        return;
        O2:
        if (strlen($_POST["\160\141\163\163\x77\x6f\x72\x64"]) < 8 || strlen($_POST["\143\157\156\x66\x69\162\x6d\x50\141\x73\163\x77\157\162\x64"]) < 8) {
            goto m8;
        }
        $Mv = isset($_POST["\145\155\141\x69\154"]) ? sanitize_email(wp_unslash($_POST["\145\155\141\x69\x6c"])) : '';
        $ge = isset($_POST["\x70\x68\157\x6e\145"]) ? sanitize_text_field(wp_unslash($_POST["\160\x68\157\156\145"])) : '';
        $hk = isset($_POST["\x70\141\x73\x73\167\x6f\162\144"]) ? stripslashes($_POST["\160\141\163\x73\x77\157\x72\144"]) : '';
        $iL = isset($_POST["\x66\x6e\x61\155\x65"]) ? sanitize_text_field(wp_unslash($_POST["\146\x6e\x61\x6d\x65"])) : '';
        $QQ = isset($_POST["\154\x6e\x61\x6d\x65"]) ? sanitize_text_field(wp_unslash($_POST["\x6c\x6e\x61\155\145"])) : '';
        $sQ = isset($_POST["\143\157\x6d\160\141\156\x79"]) ? sanitize_text_field(wp_unslash($_POST["\143\x6f\x6d\x70\x61\156\171"])) : '';
        $Ga = isset($_POST["\x63\x6f\x6e\x66\x69\x72\x6d\120\141\163\163\167\x6f\162\x64"]) ? stripslashes($_POST["\143\x6f\156\146\x69\162\x6d\120\141\163\x73\x77\x6f\162\x64"]) : '';
        goto ON;
        m8:
        $this->util->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\103\x68\157\x6f\163\145\x20\141\40\160\x61\x73\x73\167\157\162\x64\40\167\x69\164\150\40\x6d\151\156\x69\x6d\x75\x6d\x20\154\x65\156\147\x74\x68\40\x38\x2e");
        $this->util->mo_oauth_show_error_message();
        return;
        ON:
        $this->util->mo_oauth_client_update_option("\155\x6f\137\157\141\165\x74\x68\x5f\141\144\x6d\151\x6e\137\x65\x6d\x61\x69\x6c", $Mv);
        $this->util->mo_oauth_client_update_option("\155\157\x5f\157\141\x75\164\x68\137\141\x64\155\x69\156\137\x70\x68\x6f\x6e\x65", $ge);
        $this->util->mo_oauth_client_update_option("\155\x6f\137\x6f\141\x75\x74\x68\137\141\x64\155\151\x6e\137\146\156\x61\155\x65", $iL);
        $this->util->mo_oauth_client_update_option("\155\x6f\x5f\157\141\165\164\x68\x5f\141\x64\x6d\x69\156\x5f\x6c\x6e\141\155\x65", $QQ);
        $this->util->mo_oauth_client_update_option("\x6d\157\137\157\141\x75\x74\x68\x5f\141\x64\x6d\x69\x6e\137\143\x6f\x6d\x70\x61\156\x79", $sQ);
        if (!($this->util->mo_oauth_is_curl_installed() === 0)) {
            goto bw;
        }
        return $this->util->mo_oauth_show_curl_error();
        bw:
        if (strcmp($hk, $Ga) === 0) {
            goto g9;
        }
        $this->util->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\120\x61\x73\163\x77\157\x72\x64\163\40\144\157\x20\156\x6f\164\x20\155\141\x74\x63\x68\x2e");
        $this->util->mo_oauth_client_delete_option("\x76\x65\x72\151\x66\171\137\x63\165\x73\164\x6f\155\x65\x72");
        $this->util->mo_oauth_show_error_message();
        goto fd;
        g9:
        $ao = new Customer();
        $Mv = $this->util->mo_oauth_client_get_option("\155\x6f\x5f\157\141\165\164\150\x5f\141\x64\x6d\151\156\137\145\x6d\141\x69\154");
        $OY = json_decode($ao->check_customer($hk), true);
        if (strcasecmp($OY["\163\x74\141\164\x75\163"], "\x43\125\x53\124\117\115\105\122\x5f\x4e\117\x54\137\106\117\x55\116\x44") === 0) {
            goto jg;
        }
        $this->mo_oauth_get_current_customer($hk);
        goto zl;
        jg:
        $this->create_customer($hk);
        zl:
        fd:
        Pb:
        LQ:
        if (!(isset($_POST["\155\x6f\x5f\x6f\x61\165\x74\150\x5f\166\145\162\151\146\x79\137\143\x75\x73\164\157\155\x65\162\x5f\156\x6f\x6e\143\x65"]) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST["\x6d\x6f\x5f\x6f\x61\165\164\x68\137\x76\145\162\151\146\x79\x5f\143\x75\163\164\157\x6d\x65\x72\x5f\156\157\x6e\x63\x65"])), "\155\157\137\157\141\165\164\x68\x5f\166\145\162\x69\x66\x79\137\x63\x75\x73\164\157\x6d\x65\162") && isset($_POST[\MoOAuthConstants::OPTION]) && "\155\157\x5f\157\x61\165\x74\150\137\x76\145\162\x69\146\171\137\x63\x75\163\164\x6f\x6d\145\x72" === sanitize_text_field(wp_unslash($_POST[\MoOAuthConstants::OPTION])))) {
            goto Ag;
        }
        if (!($this->util->mo_oauth_is_curl_installed() === 0)) {
            goto VY;
        }
        return $this->util->mo_oauth_show_curl_error();
        VY:
        if (!current_user_can("\141\144\x6d\x69\x6e\151\163\164\162\x61\x74\157\x72")) {
            goto ge;
        }
        $Mv = isset($_POST["\145\x6d\141\x69\x6c"]) ? sanitize_email(wp_unslash($_POST["\x65\x6d\x61\151\x6c"])) : '';
        $hk = isset($_POST["\160\x61\163\x73\x77\x6f\162\x64"]) ? stripslashes($_POST["\160\141\x73\x73\167\157\162\144"]) : '';
        if (!($this->util->mo_oauth_check_empty_or_null($Mv) || $this->util->mo_oauth_check_empty_or_null($hk))) {
            goto VF;
        }
        $this->util->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\101\154\154\40\164\150\145\x20\x66\x69\x65\x6c\144\163\40\x61\x72\x65\x20\x72\145\161\x75\151\x72\145\x64\56\x20\120\154\145\141\x73\x65\40\x65\x6e\x74\145\162\40\166\141\154\151\x64\40\x65\156\164\x72\x69\145\163\56");
        $this->util->mo_oauth_show_error_message();
        return;
        VF:
        $this->util->mo_oauth_client_update_option("\x6d\x6f\137\157\141\165\164\x68\x5f\x61\144\155\x69\x6e\137\x65\155\x61\151\x6c", $Mv);
        $ao = new Customer();
        $OY = $ao->get_customer_key($hk);
        $oe = json_decode($OY, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            goto dM;
        }
        $this->util->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\111\156\x76\141\x6c\x69\x64\40\x75\x73\145\162\156\x61\x6d\x65\x20\x6f\x72\x20\x70\141\163\x73\x77\x6f\x72\x64\56\40\x50\154\x65\141\x73\x65\40\x74\162\171\40\x61\x67\141\151\x6e\56");
        $this->util->mo_oauth_show_error_message();
        goto g_;
        dM:
        $this->util->mo_oauth_client_update_option("\155\157\137\157\141\165\164\x68\137\141\x64\x6d\x69\x6e\x5f\143\x75\x73\164\x6f\x6d\x65\x72\x5f\x6b\x65\x79", $oe["\x69\x64"]);
        $this->util->mo_oauth_client_update_option("\155\x6f\137\x6f\x61\165\164\x68\137\x61\x64\155\151\156\137\x61\160\x69\x5f\153\x65\171", $oe["\x61\160\x69\x4b\145\171"]);
        $this->util->mo_oauth_client_update_option("\x63\x75\163\164\157\155\145\x72\137\164\157\x6b\145\156", $oe["\x74\x6f\153\x65\x6e"]);
        if (!isset($BR["\160\x68\157\156\145"])) {
            goto F0;
        }
        $this->util->mo_oauth_client_update_option("\155\157\137\x6f\x61\x75\164\x68\137\141\x64\155\151\156\x5f\x70\x68\157\156\145", $oe["\x70\150\x6f\156\145"]);
        F0:
        $this->util->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\103\x75\x73\164\x6f\155\x65\x72\x20\x72\145\164\x72\x69\x65\166\x65\144\x20\163\165\x63\x63\145\x73\163\x66\x75\x6c\x6c\x79");
        $this->util->mo_oauth_client_delete_option("\x76\145\162\151\146\x79\137\143\165\x73\164\x6f\155\x65\162");
        $this->util->mo_oauth_show_success_message();
        g_:
        ge:
        Ag:
        if (!(isset($_POST["\155\157\137\157\x61\165\x74\x68\x5f\x63\150\141\156\147\145\137\145\155\141\151\154\x5f\x6e\x6f\x6e\x63\145"]) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST["\155\x6f\137\x6f\141\x75\164\x68\x5f\x63\150\x61\156\x67\145\137\145\155\x61\151\154\137\156\157\156\143\x65"])), "\155\x6f\x5f\157\x61\x75\164\x68\x5f\143\150\x61\156\x67\145\137\145\x6d\x61\151\154") && isset($_POST[\MoOAuthConstants::OPTION]) && "\155\x6f\x5f\157\141\165\x74\150\x5f\143\150\141\x6e\147\145\x5f\x65\x6d\141\151\x6c" === sanitize_text_field(wp_unslash($_POST[\MoOAuthConstants::OPTION])))) {
            goto Le;
        }
        if (!current_user_can("\141\144\x6d\151\156\x69\163\164\162\x61\x74\157\162")) {
            goto Pk;
        }
        $this->util->mo_oauth_client_update_option("\166\145\x72\x69\x66\171\x5f\x63\x75\x73\x74\157\155\145\x72", '');
        $this->util->mo_oauth_client_update_option("\155\x6f\137\x6f\x61\x75\164\150\137\162\x65\x67\x69\x73\x74\x72\x61\x74\151\x6f\156\x5f\x73\164\x61\164\x75\163", '');
        $this->util->mo_oauth_client_update_option("\x6e\x65\x77\137\162\145\147\x69\x73\x74\x72\x61\164\x69\x6f\x6e", "\164\x72\165\145");
        Pk:
        Le:
        if (!(isset($_POST["\x6d\157\x5f\x6f\141\165\x74\x68\137\143\x6f\x6e\x74\x61\143\164\x5f\x75\163\137\161\x75\x65\x72\171\137\x6f\160\x74\151\x6f\156\137\x6e\157\156\143\x65"]) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST["\x6d\x6f\x5f\157\141\165\x74\150\x5f\x63\157\156\x74\x61\143\164\137\165\163\137\161\x75\145\x72\x79\x5f\157\x70\x74\151\157\x6e\137\156\157\x6e\x63\x65"])), "\x6d\x6f\x5f\157\x61\165\164\150\x5f\x63\157\x6e\164\141\x63\x74\137\165\x73\x5f\161\x75\145\x72\x79\x5f\157\x70\x74\151\x6f\x6e") && isset($_POST[\MoOAuthConstants::OPTION]) && "\155\x6f\137\x6f\x61\x75\164\x68\137\143\x6f\x6e\164\141\143\164\x5f\165\163\137\x71\x75\x65\162\x79\137\157\x70\164\151\x6f\156" === sanitize_text_field(wp_unslash($_POST[\MoOAuthConstants::OPTION])))) {
            goto dn;
        }
        if (!($this->util->mo_oauth_is_curl_installed() === 0)) {
            goto kv;
        }
        return $this->util->mo_oauth_show_curl_error();
        kv:
        if (!current_user_can("\141\144\155\x69\x6e\151\x73\164\x72\x61\164\x6f\162")) {
            goto yn;
        }
        $Mv = isset($_POST["\x6d\x6f\137\157\x61\x75\x74\150\137\x63\157\x6e\164\141\x63\x74\137\165\x73\137\x65\x6d\141\x69\154"]) ? sanitize_email(wp_unslash($_POST["\155\157\x5f\157\141\165\164\150\x5f\x63\157\x6e\x74\x61\143\164\137\165\x73\137\145\x6d\x61\x69\154"])) : '';
        $ge = isset($_POST["\155\157\x5f\x6f\x61\165\164\x68\137\x63\x6f\x6e\164\x61\143\x74\x5f\x75\163\x5f\160\150\157\x6e\145"]) ? sanitize_text_field(wp_unslash($_POST["\155\157\x5f\157\x61\x75\x74\x68\137\x63\x6f\x6e\164\141\x63\x74\137\x75\163\x5f\x70\x68\157\x6e\x65"])) : '';
        $B_ = isset($_POST["\x6d\x6f\137\157\x61\x75\164\x68\137\x63\x6f\x6e\x74\x61\143\164\x5f\x75\x73\137\x71\x75\145\x72\x79"]) ? sanitize_text_field(wp_unslash($_POST["\155\x6f\x5f\157\x61\x75\164\x68\x5f\x63\x6f\156\x74\x61\x63\164\137\x75\x73\137\x71\x75\x65\x72\171"])) : '';
        $S0 = isset($_POST["\155\x6f\x5f\x6f\x61\165\x74\x68\x5f\x73\x65\x6e\144\137\x70\154\x75\147\x69\x6e\137\143\157\156\146\151\x67"]);
        $ao = new Customer();
        if ($this->util->mo_oauth_check_empty_or_null($Mv) || $this->util->mo_oauth_check_empty_or_null($B_)) {
            goto b4;
        }
        $jM = $ao->submit_contact_us($Mv, $ge, $B_, $S0);
        if (false === $jM) {
            goto H1;
        }
        $this->util->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\124\x68\141\x6e\153\x73\40\x66\x6f\162\x20\x67\145\x74\164\x69\x6e\147\x20\x69\156\x20\x74\157\165\143\x68\x21\x20\127\145\x20\163\x68\141\x6c\x6c\x20\147\145\164\40\142\141\x63\x6b\40\x74\x6f\40\x79\157\165\40\163\x68\x6f\x72\164\154\x79\56");
        $this->util->mo_oauth_show_success_message();
        goto qj;
        H1:
        $this->util->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\131\x6f\165\162\40\x71\x75\x65\162\x79\40\x63\x6f\165\x6c\x64\40\x6e\157\x74\x20\x62\x65\x20\163\165\142\155\151\164\164\x65\144\x2e\40\120\154\145\141\x73\145\x20\x74\x72\171\40\x61\x67\x61\151\156\56");
        $this->util->mo_oauth_show_error_message();
        qj:
        goto qy;
        b4:
        $this->util->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\120\154\x65\x61\x73\145\x20\x66\151\154\x6c\x20\x75\160\40\x45\x6d\x61\151\154\40\141\x6e\144\40\x51\165\145\162\171\40\x66\x69\145\154\144\163\40\x74\157\x20\x73\165\x62\x6d\x69\x74\40\x79\157\x75\162\40\161\165\x65\162\171\x2e");
        $this->util->mo_oauth_show_error_message();
        qy:
        yn:
        dn:
        if (!(isset($_POST["\x6d\x6f\137\x6f\x61\165\164\150\137\143\x6f\156\x74\x61\x63\x74\137\165\163\137\161\x75\x65\x72\171\137\x6f\x70\x74\151\157\156\x5f\x75\160\147\x72\x61\144\145\137\x6e\x6f\x6e\143\145"]) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST["\x6d\157\x5f\157\x61\x75\x74\x68\137\x63\x6f\x6e\164\141\143\164\x5f\165\x73\137\161\165\x65\162\171\137\x6f\x70\164\151\x6f\x6e\137\x75\160\147\162\x61\x64\x65\137\x6e\x6f\x6e\143\x65"])), "\x6d\157\137\157\x61\x75\x74\x68\137\143\x6f\x6e\164\141\143\x74\x5f\x75\163\137\161\x75\145\x72\x79\137\157\x70\x74\x69\x6f\156\137\x75\x70\x67\x72\141\144\145") && isset($_POST[\MoOAuthConstants::OPTION]) && "\x6d\157\137\x6f\141\x75\x74\150\137\143\157\156\x74\x61\143\164\x5f\x75\x73\137\x71\165\x65\x72\x79\137\157\x70\164\x69\x6f\156\x5f\x75\160\x67\x72\x61\144\x65" === sanitize_text_field(wp_unslash($_POST[\MoOAuthConstants::OPTION])))) {
            goto Zs;
        }
        if (!($this->util->mo_oauth_is_curl_installed() === 0)) {
            goto st;
        }
        return $this->util->mo_oauth_show_curl_error();
        st:
        if (!current_user_can("\141\x64\155\151\156\151\x73\164\x72\x61\164\x6f\162")) {
            goto AW;
        }
        $Mv = isset($_POST["\x6d\x6f\x5f\x6f\141\x75\164\150\x5f\143\x6f\156\x74\x61\x63\164\x5f\165\x73\x5f\145\x6d\x61\x69\x6c"]) ? sanitize_email(wp_unslash($_POST["\155\x6f\x5f\x6f\141\165\x74\150\x5f\x63\x6f\x6e\164\141\143\x74\x5f\x75\163\x5f\145\x6d\141\x69\x6c"])) : '';
        $FL = isset($_POST["\155\x6f\137\157\x61\165\164\150\x5f\143\x75\162\162\x65\156\x74\x5f\x76\145\162\x73\151\157\156"]) ? sanitize_text_field(wp_unslash($_POST["\155\x6f\137\x6f\141\x75\x74\150\x5f\x63\x75\162\162\x65\x6e\164\x5f\x76\x65\162\163\151\157\x6e"])) : '';
        $uP = isset($_POST["\x6d\x6f\137\157\141\x75\x74\150\137\x75\x70\147\162\x61\x64\x69\156\x67\137\164\x6f\x5f\166\x65\x72\x73\x69\157\x6e"]) ? sanitize_text_field(wp_unslash($_POST["\x6d\157\137\157\x61\x75\x74\150\137\165\160\147\x72\141\x64\151\x6e\147\x5f\x74\157\137\166\x65\162\x73\151\157\156"])) : '';
        $H7 = isset($_POST["\x6d\x6f\x5f\146\145\141\x74\x75\162\x65\163\137\162\145\x71\x75\x69\162\145\x64"]) ? sanitize_text_field(wp_unslash($_POST["\x6d\x6f\137\x66\x65\x61\164\x75\x72\x65\x73\137\162\x65\161\165\151\x72\x65\x64"])) : '';
        $ao = new Customer();
        if ($this->util->mo_oauth_check_empty_or_null($Mv)) {
            goto De;
        }
        $jM = $ao->submit_contact_us_upgrade($Mv, $FL, $uP, $H7);
        if (false === $jM) {
            goto Q2;
        }
        $this->util->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\x54\x68\x61\x6e\x6b\163\40\146\x6f\x72\x20\147\x65\x74\x74\x69\156\147\40\x69\x6e\40\164\157\x75\x63\150\x21\x20\127\145\40\x73\x68\141\154\x6c\x20\147\145\164\40\x62\141\143\x6b\x20\x74\157\x20\171\157\165\x20\163\150\x6f\162\164\154\x79\40\x77\151\164\150\x20\x71\x75\157\164\141\x74\x69\x6f\156");
        $this->util->mo_oauth_show_success_message();
        goto yF;
        Q2:
        $this->util->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\131\157\165\162\x20\x71\165\x65\x72\171\40\x63\157\x75\x6c\144\x20\x6e\157\x74\40\x62\145\x20\x73\165\142\155\151\x74\x74\145\144\56\x20\x50\x6c\145\141\163\x65\x20\x74\162\171\x20\141\x67\141\x69\x6e\x2e");
        $this->util->mo_oauth_show_error_message();
        yF:
        goto yx;
        De:
        $this->util->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\120\x6c\145\141\x73\x65\x20\146\151\154\x6c\40\165\x70\x20\x45\155\141\x69\x6c\x20\x66\151\145\x6c\144\40\164\x6f\x20\163\165\142\155\x69\x74\x20\x79\157\x75\162\x20\161\165\145\162\171\56");
        $this->util->mo_oauth_show_error_message();
        yx:
        AW:
        Zs:
        if (!($g9 == "\x64\x69\x73\x61\x62\154\x65\144")) {
            goto CN;
        }
        if (!(isset($_POST["\155\x6f\x5f\157\141\x75\164\150\x5f\x72\x65\x73\x74\x6f\162\145\x5f\142\141\x63\153\x75\x70\x5f\x6e\157\x6e\x63\145"]) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST["\155\x6f\137\157\x61\x75\164\x68\x5f\x72\145\163\164\x6f\162\145\137\142\x61\143\x6b\x75\x70\137\156\157\156\143\x65"])), "\x6d\x6f\137\x6f\x61\x75\x74\150\x5f\162\x65\x73\164\x6f\x72\145\x5f\142\x61\143\x6b\x75\160") && isset($_POST[\MoOAuthConstants::OPTION]) && "\155\157\x5f\157\x61\x75\164\x68\x5f\x72\145\x73\x74\157\162\145\137\142\x61\x63\153\165\160" === sanitize_text_field(wp_unslash($_POST[\MoOAuthConstants::OPTION])))) {
            goto sL;
        }
        if (!current_user_can("\x61\x64\155\x69\156\x69\163\164\x72\x61\164\x6f\162")) {
            goto uJ;
        }
        $q3 = "\x54\x68\145\x72\145\x20\167\x61\x73\x20\141\x6e\40\145\162\x72\x6f\x72\x20\x75\160\154\x6f\141\144\x69\156\x67\40\164\x68\x65\40\x66\x69\154\x65";
        if (isset($_FILES["\x6d\157\x5f\157\141\x75\164\150\137\x63\x6c\x69\x65\x6e\164\x5f\142\x61\x63\153\x75\x70"])) {
            goto HS;
        }
        $this->util->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, $q3);
        $this->util->mo_oauth_show_error_message();
        return;
        HS:
        if (!function_exists("\167\160\x5f\150\141\x6e\144\154\145\137\x75\x70\x6c\x6f\141\144")) {
            require_once ABSPATH . "\167\160\x2d\x61\x64\x6d\x69\156\57\151\x6e\x63\x6c\165\144\145\x73\57\146\x69\154\x65\x2e\160\150\160";
        }
        $JC = $_FILES["\x6d\x6f\x5f\x6f\x61\x75\164\x68\x5f\x63\154\x69\x65\x6e\164\137\x62\141\x63\153\x75\x70"];
        if (!(!isset($JC["\x65\162\162\157\162"]) || is_array($JC["\x65\162\162\157\162"]) || UPLOAD_ERR_OK !== $JC["\145\x72\x72\157\x72"])) {
            goto Z4;
        }
        $this->util->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, $q3 . "\x3a\40" . json_encode($JC["\145\x72\162\157\x72"], JSON_UNESCAPED_SLASHES));
        $this->util->mo_oauth_show_error_message();
        return;
        Z4:
        $kV = new \finfo(FILEINFO_MIME_TYPE);
        $nJ = array_search($kV->file($JC["\x74\x6d\160\x5f\156\x61\x6d\x65"]), array("\164\x65\x78\x74" => "\x74\x65\170\x74\x2f\160\x6c\141\x69\156", "\152\x73\x6f\156" => "\141\x70\x70\x6c\x69\x63\141\x74\151\157\156\x2f\x6a\163\157\x6e"), true);
        $Ri = explode("\56", $JC["\156\x61\155\x65"]);
        $Ri = $Ri[count($Ri) - 1];
        if (!(false === $nJ || $Ri !== "\x6a\163\157\x6e")) {
            goto KK;
        }
        $this->util->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, $q3 . "\x3a\x20\x49\156\166\x61\x6c\151\x64\40\106\x69\154\145\x20\106\157\x72\155\141\x74\56");
        $this->util->mo_oauth_show_error_message();
        return;
        KK:
        $fD = file_get_contents($JC["\164\x6d\160\x5f\156\141\155\x65"]);
        $Wb = json_decode($fD, true);
        if (!(json_last_error() !== JSON_ERROR_NONE)) {
            goto wd;
        }
        $this->util->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, $q3 . "\72\40\x49\x6e\166\141\154\x69\x64\x20\106\151\154\145\x20\106\x6f\x72\x6d\141\164\x2e");
        $this->util->mo_oauth_show_error_message();
        return;
        wd:
        $fq = BackupHandler::restore_settings($Wb);
        if (!$fq) {
            goto T0;
        }
        $this->util->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\123\145\164\x74\151\156\x67\x73\x20\x72\145\x73\x74\157\x72\x65\144\x20\163\165\x63\143\x65\163\163\146\165\x6c\154\171\56");
        $this->util->mo_oauth_show_success_message();
        return;
        T0:
        $this->util->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\124\x68\x65\162\x65\x20\167\141\163\x20\x61\156\x20\151\163\163\165\145\40\x77\x68\151\x6c\x65\40\x72\145\163\164\x6f\162\151\156\147\40\x74\x68\x65\40\143\157\156\x66\151\147\165\x72\x61\x74\x69\157\156\56");
        $this->util->mo_oauth_show_error_message();
        return;
        uJ:
        sL:
        if (!(isset($_POST["\x6d\157\x5f\x6f\141\165\164\x68\x5f\144\x6f\x77\156\x6c\x6f\141\x64\x5f\x62\141\x63\153\165\160\x5f\156\157\x6e\x63\x65"]) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST["\x6d\157\137\x6f\141\x75\x74\x68\x5f\x64\157\x77\x6e\x6c\x6f\141\144\x5f\x62\x61\x63\153\x75\x70\137\x6e\157\x6e\x63\x65"])), "\x6d\157\x5f\x6f\141\x75\x74\x68\x5f\144\x6f\167\156\x6c\157\x61\x64\x5f\142\141\143\x6b\165\160") && isset($_POST[\MoOAuthConstants::OPTION]) && "\155\x6f\137\157\x61\x75\164\x68\137\144\157\x77\x6e\x6c\157\x61\144\x5f\142\x61\143\153\165\160" === sanitize_text_field(wp_unslash($_POST[\MoOAuthConstants::OPTION])))) {
            goto Nr;
        }
        if (!current_user_can("\x61\x64\x6d\151\156\151\163\164\162\141\x74\x6f\162")) {
            goto lu;
        }
        $ew = BackupHandler::get_backup_json();
        header("\103\157\x6e\164\x65\x6e\x74\x2d\x54\x79\x70\x65\x3a\40\141\x70\160\x6c\151\143\x61\x74\x69\x6f\156\x2f\x6a\x73\x6f\x6e");
        header("\x43\x6f\x6e\164\x65\156\x74\x2d\104\x69\163\x70\157\163\151\x74\x69\157\156\72\40\141\x74\x74\x61\x63\150\155\x65\x6e\164\73\x20\x66\151\x6c\x65\x6e\x61\x6d\145\75\42\x70\154\x75\x67\x69\x6e\137\x62\x61\143\x6b\x75\160\56\x6a\163\x6f\156\x22");
        header("\103\157\156\164\145\x6e\x74\55\114\x65\x6e\147\164\150\x3a\40" . strlen($ew));
        header("\103\157\156\x6e\x65\143\164\x69\x6f\x6e\72\40\143\154\157\163\145");
        echo $ew;
        exit;
        lu:
        Nr:
        CN:
        do_action("\144\x6f\x5f\x6d\x61\x69\156\x5f\x73\x65\164\164\x69\x6e\147\x73\137\x69\156\x74\145\x72\156\x61\x6c", $_POST);
    }
    public function mo_oauth_get_current_customer($hk)
    {
        if (!current_user_can("\141\144\x6d\151\156\151\163\x74\x72\x61\x74\157\162")) {
            goto hf;
        }
        $ao = new Customer();
        $OY = $ao->get_customer_key($hk);
        $oe = json_decode($OY, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            goto dv;
        }
        $this->util->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\x59\157\165\x20\141\x6c\x72\x65\x61\144\171\40\x68\141\x76\x65\x20\x61\156\x20\x61\143\143\x6f\165\156\x74\40\167\x69\164\150\40\155\151\x6e\x69\x4f\162\141\x6e\x67\x65\56\40\x50\154\145\141\163\x65\x20\x65\x6e\164\x65\x72\40\x61\x20\x76\141\x6c\x69\144\x20\x70\141\x73\x73\167\x6f\x72\144\x2e");
        $this->util->mo_oauth_client_update_option("\x76\145\x72\151\146\x79\137\x63\x75\x73\164\157\x6d\x65\x72", "\164\x72\165\x65");
        $this->util->mo_oauth_show_error_message();
        goto bL;
        dv:
        $this->util->mo_oauth_client_update_option("\x6d\x6f\x5f\x6f\x61\x75\x74\150\x5f\x61\x64\155\151\156\x5f\143\165\163\164\x6f\155\x65\x72\x5f\153\x65\x79", $oe["\151\x64"]);
        $this->util->mo_oauth_client_update_option("\x6d\157\x5f\157\141\x75\164\x68\137\141\144\x6d\x69\x6e\x5f\x61\160\151\x5f\x6b\x65\171", $oe["\x61\160\151\x4b\145\x79"]);
        $this->util->mo_oauth_client_update_option("\143\x75\x73\x74\x6f\155\145\162\x5f\164\157\153\145\156", $oe["\164\x6f\153\x65\x6e"]);
        $this->util->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\103\165\163\164\157\x6d\145\162\40\162\x65\164\x72\x69\x65\x76\x65\144\x20\163\165\143\143\145\x73\163\x66\165\x6c\154\171");
        $this->util->mo_oauth_client_delete_option("\166\145\162\x69\146\171\x5f\x63\x75\x73\164\x6f\x6d\x65\162");
        $this->util->mo_oauth_client_delete_option("\x6e\x65\x77\x5f\x72\145\147\x69\163\x74\162\141\x74\x69\157\x6e");
        $this->util->mo_oauth_show_success_message();
        bL:
        hf:
    }
    public function create_customer($hk)
    {
        if (!current_user_can("\141\144\155\151\x6e\x69\163\x74\162\141\x74\x6f\162")) {
            goto mM;
        }
        global $Yh;
        $ao = new Customer();
        $oe = json_decode($ao->create_customer($hk), true);
        if (strcasecmp($oe["\163\164\141\x74\165\163"], "\x43\x55\123\124\x4f\115\105\x52\137\x55\123\105\122\x4e\x41\x4d\105\137\101\114\122\x45\101\104\131\x5f\x45\x58\x49\123\124\x53") === 0) {
            goto of;
        }
        if (strcasecmp($oe["\163\164\141\x74\165\x73"], "\123\x55\103\x43\105\x53\123") === 0) {
            goto pJ;
        }
        goto jL;
        of:
        $this->mo_oauth_get_current_customer($hk);
        $this->util->mo_oauth_client_delete_option("\155\x6f\137\157\141\165\x74\150\x5f\156\x65\x77\x5f\x63\x75\163\164\x6f\155\x65\x72");
        goto jL;
        pJ:
        $this->util->mo_oauth_client_update_option("\x6d\157\137\157\x61\x75\x74\x68\x5f\x61\144\x6d\x69\156\x5f\x63\165\163\x74\x6f\x6d\145\x72\x5f\x6b\145\171", $oe["\x69\x64"]);
        $this->util->mo_oauth_client_update_option("\x6d\x6f\x5f\x6f\141\x75\x74\x68\x5f\x61\144\x6d\151\156\137\x61\x70\151\x5f\x6b\145\x79", $oe["\141\x70\x69\113\x65\171"]);
        $this->util->mo_oauth_client_update_option("\143\165\163\x74\157\155\145\162\x5f\x74\157\x6b\x65\x6e", $oe["\164\157\153\x65\156"]);
        $this->util->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\x52\x65\147\x69\x73\x74\145\162\x65\144\x20\163\x75\143\x63\x65\163\x73\x66\165\x6c\x6c\x79\x2e");
        $this->util->mo_oauth_client_update_option("\155\x6f\x5f\157\141\x75\164\150\x5f\162\x65\147\x69\163\x74\x72\x61\x74\x69\157\156\137\163\x74\x61\164\165\163", "\x4d\117\137\117\101\x55\124\x48\137\122\105\107\x49\x53\x54\x52\x41\124\111\x4f\x4e\137\103\117\115\120\114\x45\x54\x45");
        $this->util->mo_oauth_client_update_option("\x6d\157\137\157\x61\x75\164\150\x5f\156\x65\167\137\143\x75\163\164\157\x6d\x65\x72", 1);
        $this->util->mo_oauth_client_delete_option("\166\145\x72\151\146\x79\137\143\165\163\164\x6f\x6d\145\x72");
        $this->util->mo_oauth_client_delete_option("\156\145\x77\137\162\145\x67\151\163\164\162\141\164\151\x6f\156");
        $this->util->mo_oauth_show_success_message();
        jL:
        mM:
    }
}
