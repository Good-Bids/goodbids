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
        global $Uj;
        $this->util = $Uj;
        add_action("\141\x64\x6d\x69\156\137\151\156\x69\164", array($this, "\155\151\156\151\157\162\141\x6e\147\x65\137\157\141\165\164\x68\x5f\x73\141\166\x65\x5f\x73\x65\x74\164\151\156\147\x73"));
        add_shortcode("\x6d\x6f\137\157\x61\x75\x74\150\x5f\154\x6f\147\151\x6e", array($this, "\x6d\x6f\x5f\157\141\165\164\x68\137\163\150\x6f\x72\x74\x63\x6f\x64\x65\137\154\x6f\147\151\156"));
        $this->util->mo_oauth_client_update_option("\155\x6f\x5f\x6f\141\165\x74\x68\137\x6c\x6f\x67\x69\156\x5f\151\143\x6f\x6e\x5f\163\x70\141\143\145", "\65");
        $this->util->mo_oauth_client_update_option("\x6d\x6f\x5f\157\141\165\164\x68\137\x6c\x6f\147\x69\156\x5f\151\x63\157\156\x5f\143\x75\163\x74\x6f\155\137\167\x69\144\x74\x68", "\63\62\x35\x2e\64\63");
        $this->util->mo_oauth_client_update_option("\155\157\x5f\157\141\x75\x74\150\137\x6c\x6f\x67\x69\156\x5f\151\143\157\156\x5f\x63\x75\163\164\157\x6d\x5f\x68\145\x69\x67\150\x74", "\71\x2e\66\x33");
        $this->util->mo_oauth_client_update_option("\155\x6f\x5f\x6f\x61\x75\164\x68\137\x6c\157\147\x69\x6e\x5f\x69\x63\x6f\x6e\x5f\x63\165\x73\164\157\x6d\x5f\163\151\x7a\145", "\63\65");
        $this->util->mo_oauth_client_update_option("\155\157\x5f\157\x61\x75\x74\x68\137\x6c\x6f\147\151\x6e\x5f\x69\x63\157\x6e\137\x63\165\163\x74\x6f\x6d\137\143\x6f\154\x6f\x72", "\x32\x42\64\61\106\x46");
        $this->util->mo_oauth_client_update_option("\x6d\x6f\x5f\157\x61\165\164\x68\x5f\154\157\x67\x69\x6e\x5f\x69\143\157\156\x5f\143\x75\163\164\157\155\137\142\x6f\165\156\144\141\162\171", "\64");
        $this->config = $this->util->get_plugin_config();
    }
    public function miniorange_oauth_save_settings()
    {
        global $Uj;
        $Kn = $Uj->get_plugin_config()->get_current_config();
        $MS = "\144\x69\163\x61\142\x6c\x65\144";
        if (empty($Kn["\155\157\x5f\x64\x74\145\137\163\x74\141\164\x65"])) {
            goto SG;
        }
        $MS = $Uj->mooauthdecrypt($Kn["\155\157\x5f\144\164\145\x5f\x73\164\x61\x74\145"]);
        SG:
        if (!(isset($_POST["\143\x68\141\x6e\147\145\x5f\155\x69\156\151\x6f\x72\141\x6e\147\145\137\x6e\x6f\x6e\x63\x65"]) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST["\x63\150\x61\x6e\147\145\x5f\x6d\x69\156\151\157\x72\x61\x6e\x67\145\137\x6e\x6f\x6e\x63\145"])), "\x63\x68\x61\x6e\x67\x65\137\155\151\156\x69\157\162\x61\156\x67\145") && isset($_POST[\MoOAuthConstants::OPTION]) && "\x63\x68\141\x6e\x67\x65\x5f\x6d\x69\156\151\x6f\x72\141\x6e\147\x65" === $_POST[\MoOAuthConstants::OPTION])) {
            goto LW;
        }
        mo_oauth_deactivate();
        return;
        LW:
        if (!(isset($_POST["\155\157\137\157\x61\x75\164\150\137\145\156\141\142\154\x65\x5f\144\145\x62\165\x67\x5f\x6e\157\156\143\x65"]) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST["\x6d\x6f\137\x6f\141\165\164\x68\x5f\x65\x6e\141\142\x6c\145\x5f\144\145\x62\x75\147\x5f\156\x6f\156\143\x65"])), "\155\x6f\x5f\157\141\165\x74\150\137\x65\x6e\141\x62\x6c\x65\137\x64\145\x62\165\x67") && isset($_POST[\MoOAuthConstants::OPTION]) && "\155\x6f\x5f\157\x61\165\164\x68\x5f\x65\x6e\141\x62\x6c\x65\137\x64\145\x62\165\x67" === $_POST[\MoOAuthConstants::OPTION])) {
            goto Uv;
        }
        $Zy = isset($_POST["\155\x6f\137\157\141\165\x74\150\x5f\144\145\142\x75\147\x5f\143\150\x65\x63\153"]);
        $QG = current_time("\x74\151\x6d\x65\x73\x74\141\x6d\x70");
        $Uj->mo_oauth_client_update_option("\155\x6f\x5f\x64\x65\142\165\x67\x5f\x65\x6e\x61\x62\154\x65", $Zy);
        if (!$Uj->mo_oauth_client_get_option("\155\157\137\x64\x65\142\165\x67\x5f\x65\156\x61\142\x6c\x65")) {
            goto Uu;
        }
        $Uj->mo_oauth_client_update_option("\155\157\x5f\144\x65\x62\165\147\x5f\143\150\145\x63\153", 1);
        $Uj->mo_oauth_client_update_option("\x6d\x6f\137\x64\145\x62\x75\x67\x5f\x74\151\x6d\145", $QG);
        Uu:
        if (!($Uj->mo_oauth_client_get_option("\x6d\157\137\x64\145\142\x75\147\x5f\x65\156\141\142\154\145") && !$Uj->mo_oauth_client_get_option("\155\157\x5f\x6f\x61\x75\x74\x68\137\144\145\142\x75\147"))) {
            goto zF;
        }
        $Uj->mo_oauth_client_update_option("\155\157\137\x6f\x61\x75\164\x68\x5f\x64\x65\x62\165\147", "\x6d\157\137\x6f\141\x75\x74\150\137\x64\x65\142\165\x67" . uniqid());
        $EQ = $Uj->mo_oauth_client_get_option("\155\x6f\137\157\x61\x75\x74\x68\x5f\x64\145\x62\x75\x67");
        $So = dirname(__DIR__) . DIRECTORY_SEPARATOR . "\117\x41\x75\164\150\x48\141\x6e\144\154\145\162" . DIRECTORY_SEPARATOR . $EQ . "\56\154\157\147";
        $eB = fopen($So, "\x77");
        chmod($So, 0644);
        $Uj->mo_oauth_client_update_option("\x6d\x6f\137\x64\145\142\165\147\x5f\x63\x68\x65\143\153", 1);
        MO_Oauth_Debug::mo_oauth_log('');
        $Uj->mo_oauth_client_update_option("\155\x6f\x5f\144\145\142\165\147\137\x63\150\x65\143\153", 0);
        zF:
        return;
        Uv:
        if (!(isset($_POST["\x6d\157\137\157\141\165\x74\x68\x5f\x65\156\x61\x62\154\145\x5f\x64\x65\142\x75\147\x5f\144\157\x77\156\x6c\x6f\141\144\137\156\157\x6e\x63\145"]) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST["\x6d\157\137\157\141\165\164\x68\137\145\x6e\141\x62\154\145\x5f\x64\x65\x62\165\147\137\x64\157\167\156\154\x6f\141\x64\137\156\x6f\x6e\x63\x65"])), "\x6d\157\137\x6f\x61\165\164\x68\137\x65\x6e\141\x62\x6c\x65\137\144\145\x62\165\147\137\x64\x6f\x77\156\x6c\157\141\144") && isset($_POST[\MoOAuthConstants::OPTION]) && "\x6d\157\137\157\141\x75\164\x68\137\x65\x6e\141\142\x6c\145\137\x64\x65\142\x75\x67\x5f\x64\x6f\x77\156\x6c\157\141\x64" === $_POST[\MoOAuthConstants::OPTION])) {
            goto KF;
        }
        $dP = plugin_dir_path(__FILE__) . "\57\x2e\x2e\x2f\x4f\101\165\x74\x68\110\141\156\144\x6c\x65\162\x2f" . $Uj->mo_oauth_client_get_option("\155\157\x5f\157\141\x75\164\x68\137\144\x65\142\x75\147") . "\x2e\154\x6f\x67";
        if (is_file($dP)) {
            goto tQ;
        }
        echo "\64\60\x34\x20\106\x69\154\145\40\x6e\x6f\x74\40\146\157\x75\x6e\x64\x21";
        exit;
        tQ:
        $vH = filesize($dP);
        $Om = basename($dP);
        $r8 = strtolower(pathinfo($Om, PATHINFO_EXTENSION));
        $wC = "\x61\160\x70\154\151\143\x61\164\151\157\x6e\x2f\x66\157\162\x63\x65\55\x64\157\167\x6e\x6c\x6f\x61\144";
        if (!ob_get_contents()) {
            goto XW;
        }
        ob_clean();
        XW:
        header("\120\162\x61\147\x6d\x61\x3a\x20\x70\x75\x62\154\x69\143");
        header("\x45\170\x70\151\162\145\163\x3a\x20\x30");
        header("\103\x61\x63\x68\x65\55\103\157\156\x74\162\157\154\72\40\155\x75\163\164\x2d\162\x65\x76\141\x6c\x69\144\x61\x74\145\x2c\x20\160\157\x73\164\x2d\x63\150\x65\x63\x6b\75\x30\54\x20\160\162\x65\55\x63\150\145\x63\153\75\60");
        header("\x43\141\x63\x68\145\55\x43\x6f\x6e\x74\162\x6f\154\x3a\x20\160\x75\142\x6c\x69\143");
        header("\x43\157\x6e\x74\145\156\x74\55\104\x65\x73\x63\162\151\160\164\151\x6f\156\72\40\x46\151\x6c\x65\40\124\162\x61\x6e\x73\146\x65\162");
        header("\x43\157\x6e\x74\145\x6e\x74\55\x54\x79\160\145\x3a\40{$wC}");
        $PT = "\x43\157\156\164\145\156\x74\55\x44\x69\163\x70\157\x73\x69\164\151\x6f\156\72\x20\141\x74\164\141\143\x68\155\145\x6e\x74\x3b\x20\x66\151\x6c\x65\x6e\x61\x6d\145\x3d" . $Om . "\73";
        header($PT);
        header("\x43\157\x6e\x74\x65\x6e\x74\x2d\124\x72\x61\x6e\163\x66\145\162\55\x45\156\143\157\x64\151\156\x67\x3a\40\142\151\156\141\162\171");
        header("\x43\x6f\x6e\164\145\156\x74\x2d\114\145\156\147\164\x68\x3a\x20" . $vH);
        @readfile($dP);
        exit;
        KF:
        if (!(isset($_POST["\x6d\157\137\x6f\141\x75\x74\x68\x5f\x72\x65\x67\x69\163\x74\x65\x72\x5f\143\x75\163\x74\x6f\x6d\x65\x72\x5f\156\x6f\156\x63\145"]) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST["\x6d\157\137\157\x61\x75\164\x68\137\162\x65\x67\x69\163\164\x65\x72\137\143\165\x73\164\x6f\x6d\x65\162\137\156\157\156\143\145"])), "\x6d\x6f\x5f\x6f\x61\165\164\x68\137\162\145\147\x69\163\x74\145\162\x5f\143\x75\x73\x74\157\x6d\145\162") && isset($_POST[\MoOAuthConstants::OPTION]) && "\x6d\157\x5f\x6f\x61\x75\164\150\x5f\162\x65\147\151\x73\x74\145\162\137\143\x75\x73\x74\x6f\x6d\x65\162" === $_POST[\MoOAuthConstants::OPTION])) {
            goto N9;
        }
        $g3 = '';
        $fm = '';
        $Jj = '';
        $om = '';
        $T6 = '';
        $Hx = '';
        $bq = '';
        if (!($this->util->mo_oauth_check_empty_or_null($_POST["\145\x6d\141\x69\154"]) || $this->util->mo_oauth_check_empty_or_null($_POST["\160\x61\163\x73\x77\157\x72\x64"]) || $this->util->mo_oauth_check_empty_or_null($_POST["\143\x6f\x6e\x66\151\162\x6d\120\141\163\x73\167\157\x72\144"]))) {
            goto Op;
        }
        $this->util->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\x41\x6c\x6c\40\x74\150\145\x20\x66\151\x65\154\144\x73\40\141\x72\x65\40\162\145\x71\165\151\x72\145\144\56\x20\x50\x6c\x65\x61\163\145\40\x65\x6e\x74\x65\x72\x20\x76\x61\154\x69\x64\40\145\x6e\x74\162\x69\145\163\56");
        $this->util->mo_oauth_show_error_message();
        return;
        Op:
        if (strlen($_POST["\160\141\163\x73\x77\x6f\162\x64"]) < 8 || strlen($_POST["\x63\x6f\x6e\x66\151\162\x6d\x50\141\163\x73\x77\157\x72\x64"]) < 8) {
            goto ee;
        }
        $g3 = sanitize_email($_POST["\x65\155\x61\151\154"]);
        $fm = stripslashes($_POST["\x70\x68\157\x6e\145"]);
        $Jj = stripslashes($_POST["\160\x61\x73\x73\167\x6f\x72\144"]);
        $om = stripslashes($_POST["\146\156\x61\155\145"]);
        $T6 = stripslashes($_POST["\x6c\x6e\141\155\145"]);
        $Hx = stripslashes($_POST["\143\157\155\160\141\156\171"]);
        $bq = stripslashes($_POST["\x63\157\156\x66\x69\x72\x6d\120\x61\x73\x73\167\157\x72\144"]);
        goto GD;
        ee:
        $this->util->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\x43\x68\x6f\x6f\163\145\x20\141\x20\x70\x61\x73\163\167\x6f\162\x64\x20\167\x69\x74\150\x20\x6d\151\x6e\x69\x6d\165\x6d\x20\154\145\156\x67\164\x68\40\70\56");
        $this->util->mo_oauth_show_error_message();
        return;
        GD:
        $this->util->mo_oauth_client_update_option("\155\157\x5f\157\141\165\x74\150\x5f\x61\144\x6d\x69\156\x5f\145\155\141\x69\x6c", $g3);
        $this->util->mo_oauth_client_update_option("\155\x6f\137\157\141\165\x74\x68\x5f\141\144\155\x69\x6e\137\160\x68\x6f\156\x65", $fm);
        $this->util->mo_oauth_client_update_option("\x6d\157\x5f\157\141\165\x74\x68\x5f\x61\144\155\x69\156\137\x66\x6e\141\x6d\x65", $om);
        $this->util->mo_oauth_client_update_option("\155\x6f\x5f\157\141\165\164\x68\x5f\x61\x64\x6d\x69\x6e\x5f\x6c\x6e\141\155\x65", $T6);
        $this->util->mo_oauth_client_update_option("\155\157\x5f\157\141\x75\x74\x68\x5f\x61\144\155\151\156\x5f\143\157\155\x70\141\156\x79", $Hx);
        if (!($this->util->mo_oauth_is_curl_installed() === 0)) {
            goto Qt;
        }
        return $this->util->mo_oauth_show_curl_error();
        Qt:
        if (strcmp($Jj, $bq) === 0) {
            goto Ys;
        }
        $this->util->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\120\141\163\x73\167\157\162\144\x73\40\x64\x6f\x20\156\x6f\x74\x20\x6d\x61\164\143\150\56");
        $this->util->mo_oauth_client_delete_option("\x76\x65\x72\151\146\171\137\143\x75\163\x74\x6f\x6d\145\162");
        $this->util->mo_oauth_show_error_message();
        goto sd;
        Ys:
        $this->util->mo_oauth_client_update_option("\160\x61\x73\x73\x77\157\162\144", $Jj);
        $me = new Customer();
        $g3 = $this->util->mo_oauth_client_get_option("\x6d\157\x5f\157\x61\x75\x74\x68\137\141\144\x6d\151\156\x5f\x65\155\x61\151\x6c");
        $dV = json_decode($me->check_customer(), true);
        if (strcasecmp($dV["\x73\164\141\164\x75\163"], "\x43\125\x53\124\x4f\x4d\x45\122\137\116\x4f\124\137\106\x4f\x55\x4e\104") === 0) {
            goto IS;
        }
        $this->mo_oauth_get_current_customer();
        goto mF;
        IS:
        $this->create_customer();
        mF:
        sd:
        N9:
        if (!(isset($_POST["\x6d\x6f\137\x6f\141\165\x74\150\137\166\x65\162\151\146\171\137\x63\165\163\164\x6f\x6d\x65\x72\x5f\156\x6f\x6e\x63\145"]) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST["\155\x6f\137\x6f\141\165\164\150\x5f\166\x65\162\151\146\x79\x5f\x63\x75\163\164\157\x6d\x65\x72\x5f\x6e\x6f\156\143\145"])), "\x6d\x6f\x5f\x6f\x61\x75\164\150\x5f\166\x65\162\x69\146\x79\x5f\143\x75\163\x74\x6f\x6d\145\x72") && isset($_POST[\MoOAuthConstants::OPTION]) && "\x6d\157\137\157\x61\165\164\x68\x5f\x76\145\x72\151\146\x79\x5f\143\x75\x73\164\157\155\145\162" === $_POST[\MoOAuthConstants::OPTION])) {
            goto ew;
        }
        if (!($this->util->mo_oauth_is_curl_installed() === 0)) {
            goto xD;
        }
        return $this->util->mo_oauth_show_curl_error();
        xD:
        $g3 = isset($_POST["\145\155\x61\151\x6c"]) ? sanitize_email(wp_unslash($_POST["\145\155\x61\151\x6c"])) : '';
        $Jj = isset($_POST["\160\x61\x73\163\167\x6f\162\x64"]) ? $_POST["\x70\141\163\163\167\157\162\x64"] : '';
        if (!($this->util->mo_oauth_check_empty_or_null($g3) || $this->util->mo_oauth_check_empty_or_null($Jj))) {
            goto q0;
        }
        $this->util->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\x41\x6c\x6c\40\164\150\x65\40\x66\x69\145\x6c\144\163\40\141\162\x65\x20\x72\145\161\165\151\162\x65\144\56\x20\x50\x6c\x65\x61\163\145\x20\145\x6e\164\145\x72\x20\166\x61\x6c\151\144\40\145\156\164\162\151\145\163\x2e");
        $this->util->mo_oauth_show_error_message();
        return;
        q0:
        $this->util->mo_oauth_client_update_option("\x6d\157\137\x6f\141\165\x74\150\137\141\144\155\151\156\137\145\155\x61\x69\x6c", $g3);
        $this->util->mo_oauth_client_update_option("\x70\x61\163\163\x77\x6f\162\144", $Jj);
        $me = new Customer();
        $dV = $me->get_customer_key();
        $i_ = json_decode($dV, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            goto hW;
        }
        $this->util->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\111\156\166\141\154\151\x64\40\x75\163\x65\x72\156\x61\x6d\x65\x20\157\162\x20\x70\141\x73\163\167\x6f\162\x64\x2e\40\120\154\x65\x61\163\x65\40\164\x72\x79\x20\x61\147\x61\x69\156\56");
        $this->util->mo_oauth_show_error_message();
        goto ZU;
        hW:
        $this->util->mo_oauth_client_update_option("\155\157\x5f\157\141\x75\x74\150\137\141\x64\x6d\151\156\137\143\x75\x73\x74\157\155\x65\x72\x5f\x6b\145\171", $i_["\x69\x64"]);
        $this->util->mo_oauth_client_update_option("\x6d\157\x5f\157\x61\x75\x74\150\137\141\144\155\151\x6e\x5f\141\160\x69\137\x6b\x65\171", $i_["\141\160\151\x4b\145\x79"]);
        $this->util->mo_oauth_client_update_option("\x63\x75\163\x74\157\x6d\145\162\x5f\x74\157\153\x65\x6e", $i_["\x74\x6f\x6b\145\156"]);
        if (!isset($An["\160\150\x6f\x6e\x65"])) {
            goto jt;
        }
        $this->util->mo_oauth_client_update_option("\155\x6f\x5f\157\141\x75\x74\x68\137\x61\144\155\151\156\x5f\160\150\157\156\145", $i_["\x70\x68\x6f\x6e\145"]);
        jt:
        $this->util->mo_oauth_client_delete_option("\x70\x61\163\x73\x77\x6f\x72\x64");
        $this->util->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\x43\x75\x73\x74\157\155\145\x72\40\162\x65\x74\162\x69\145\x76\x65\144\40\163\x75\143\143\145\163\x73\146\x75\154\154\x79");
        $this->util->mo_oauth_client_delete_option("\x76\x65\x72\151\x66\x79\137\x63\x75\x73\164\157\x6d\145\x72");
        $this->util->mo_oauth_show_success_message();
        ZU:
        ew:
        if (!(isset($_POST["\x6d\x6f\137\x6f\141\165\x74\150\x5f\x63\x68\x61\156\x67\x65\x5f\x65\x6d\141\x69\154\137\156\157\156\143\x65"]) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST["\155\x6f\137\x6f\141\x75\x74\x68\137\143\x68\x61\x6e\x67\x65\137\145\x6d\x61\x69\154\x5f\x6e\x6f\156\143\x65"])), "\x6d\x6f\x5f\157\x61\165\x74\150\x5f\x63\x68\x61\x6e\x67\x65\137\145\x6d\x61\151\154") && isset($_POST[\MoOAuthConstants::OPTION]) && "\x6d\x6f\x5f\157\x61\165\x74\x68\137\143\x68\x61\x6e\147\145\x5f\x65\155\x61\151\154" === $_POST[\MoOAuthConstants::OPTION])) {
            goto rL;
        }
        $this->util->mo_oauth_client_update_option("\166\x65\x72\x69\146\x79\137\x63\x75\163\164\157\155\x65\x72", '');
        $this->util->mo_oauth_client_update_option("\155\x6f\x5f\x6f\141\x75\x74\x68\x5f\162\x65\x67\x69\x73\164\x72\x61\x74\x69\x6f\x6e\137\x73\x74\141\164\x75\163", '');
        $this->util->mo_oauth_client_update_option("\x6e\x65\167\137\162\x65\x67\151\163\x74\162\x61\x74\x69\x6f\156", "\x74\x72\165\x65");
        rL:
        if (!(isset($_POST["\155\x6f\137\157\x61\165\164\x68\137\x63\157\x6e\x74\x61\x63\x74\137\x75\x73\137\161\165\x65\x72\x79\x5f\157\x70\164\x69\x6f\x6e\x5f\156\x6f\x6e\x63\x65"]) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST["\x6d\x6f\137\x6f\x61\x75\x74\150\x5f\x63\x6f\156\x74\x61\x63\164\137\165\x73\137\x71\165\145\162\171\137\157\160\x74\x69\x6f\x6e\x5f\x6e\157\x6e\x63\x65"])), "\x6d\x6f\x5f\157\141\165\x74\x68\137\x63\x6f\x6e\x74\141\x63\164\137\165\163\137\x71\165\x65\162\x79\x5f\x6f\x70\x74\151\x6f\156") && isset($_POST[\MoOAuthConstants::OPTION]) && "\155\157\137\x6f\141\165\164\150\x5f\x63\157\156\x74\x61\x63\x74\x5f\x75\x73\x5f\x71\165\145\x72\x79\137\157\160\164\151\x6f\x6e" === $_POST[\MoOAuthConstants::OPTION])) {
            goto sH;
        }
        if (!($this->util->mo_oauth_is_curl_installed() === 0)) {
            goto Xn;
        }
        return $this->util->mo_oauth_show_curl_error();
        Xn:
        $g3 = isset($_POST["\x6d\157\x5f\157\x61\165\164\150\137\143\x6f\156\164\x61\x63\164\x5f\165\163\x5f\x65\155\141\x69\x6c"]) ? sanitize_text_field(wp_unslash($_POST["\x6d\157\137\157\141\x75\164\150\137\143\157\156\x74\141\143\x74\x5f\x75\163\x5f\145\155\x61\x69\x6c"])) : '';
        $fm = isset($_POST["\x6d\157\137\157\x61\x75\x74\x68\x5f\143\157\156\164\x61\x63\x74\x5f\165\163\137\160\x68\157\156\x65"]) ? sanitize_text_field(wp_unslash($_POST["\x6d\157\137\x6f\141\x75\164\150\137\143\x6f\x6e\164\141\x63\x74\x5f\165\163\x5f\x70\150\157\156\x65"])) : '';
        $GQ = isset($_POST["\x6d\157\137\x6f\141\x75\164\150\137\143\157\x6e\x74\x61\143\x74\137\165\x73\137\161\165\x65\x72\x79"]) ? sanitize_text_field(wp_unslash($_POST["\x6d\157\137\157\x61\165\x74\x68\x5f\x63\x6f\x6e\164\x61\x63\164\x5f\165\x73\x5f\161\165\145\162\x79"])) : '';
        $Pa = isset($_POST["\x6d\157\x5f\157\x61\165\x74\x68\x5f\163\x65\x6e\144\x5f\x70\x6c\165\147\151\156\137\143\x6f\156\x66\151\x67"]);
        $me = new Customer();
        if ($this->util->mo_oauth_check_empty_or_null($g3) || $this->util->mo_oauth_check_empty_or_null($GQ)) {
            goto Wl;
        }
        $Cu = $me->submit_contact_us($g3, $fm, $GQ, $Pa);
        if (false === $Cu) {
            goto Cj;
        }
        $this->util->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\124\x68\141\156\153\x73\40\x66\x6f\162\40\147\145\x74\x74\x69\x6e\147\40\151\x6e\40\164\x6f\165\x63\150\41\40\127\145\40\x73\150\141\154\154\40\147\145\x74\40\142\141\x63\153\x20\x74\x6f\x20\171\157\x75\40\163\150\x6f\x72\164\154\171\x2e");
        $this->util->mo_oauth_show_success_message();
        goto xS;
        Cj:
        $this->util->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\x59\x6f\165\x72\x20\161\x75\145\162\x79\40\x63\157\x75\x6c\144\40\156\157\x74\x20\x62\x65\40\163\165\x62\155\151\164\164\145\x64\56\x20\x50\x6c\145\x61\x73\145\x20\164\x72\171\40\141\147\141\x69\156\x2e");
        $this->util->mo_oauth_show_error_message();
        xS:
        goto iU;
        Wl:
        $this->util->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\120\154\x65\141\x73\145\40\146\x69\154\x6c\40\x75\x70\x20\105\x6d\141\151\x6c\40\141\x6e\x64\40\x51\x75\145\x72\171\x20\x66\151\x65\x6c\144\163\40\164\x6f\40\163\165\142\x6d\151\x74\40\171\x6f\x75\162\40\x71\x75\x65\x72\171\56");
        $this->util->mo_oauth_show_error_message();
        iU:
        sH:
        if (!(isset($_POST["\x6d\157\137\x6f\x61\x75\x74\150\137\143\157\156\164\141\143\164\137\165\163\137\161\x75\145\x72\x79\137\x6f\x70\164\151\x6f\156\x5f\165\160\x67\162\x61\144\145\137\156\157\x6e\143\x65"]) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST["\155\157\137\157\x61\165\x74\150\x5f\x63\157\156\x74\x61\x63\x74\x5f\x75\x73\x5f\x71\x75\145\162\x79\137\157\160\x74\x69\157\156\x5f\x75\x70\x67\x72\141\x64\145\x5f\x6e\157\156\x63\x65"])), "\155\157\137\157\x61\x75\164\150\137\x63\157\156\x74\141\143\x74\x5f\x75\163\137\161\x75\145\162\x79\137\x6f\160\x74\151\157\x6e\x5f\165\x70\147\x72\x61\x64\x65") && isset($_POST[\MoOAuthConstants::OPTION]) && "\155\x6f\x5f\x6f\x61\165\164\x68\x5f\143\x6f\156\x74\x61\143\164\x5f\165\x73\137\x71\x75\x65\x72\x79\x5f\x6f\x70\164\151\x6f\x6e\x5f\x75\160\x67\162\141\x64\x65" === $_POST[\MoOAuthConstants::OPTION])) {
            goto sT;
        }
        if (!($this->util->mo_oauth_is_curl_installed() === 0)) {
            goto EX;
        }
        return $this->util->mo_oauth_show_curl_error();
        EX:
        $g3 = isset($_POST["\x6d\x6f\x5f\x6f\x61\x75\x74\150\x5f\143\157\x6e\x74\x61\143\x74\137\x75\x73\x5f\145\x6d\x61\x69\x6c"]) ? sanitize_text_field(wp_unslash($_POST["\x6d\x6f\137\157\141\x75\x74\x68\x5f\143\x6f\156\x74\x61\x63\164\137\x75\x73\137\x65\155\141\151\154"])) : '';
        $q2 = isset($_POST["\155\x6f\137\157\x61\165\164\x68\x5f\143\x75\162\162\145\156\x74\x5f\x76\x65\x72\x73\x69\157\x6e"]) ? sanitize_text_field(wp_unslash($_POST["\155\157\x5f\x6f\x61\x75\164\150\137\x63\x75\x72\x72\145\x6e\x74\x5f\x76\x65\162\x73\151\157\156"])) : '';
        $x3 = isset($_POST["\x6d\157\x5f\157\141\x75\x74\x68\x5f\x75\x70\x67\162\x61\144\151\156\147\x5f\164\x6f\137\166\x65\162\163\151\157\x6e"]) ? sanitize_text_field(wp_unslash($_POST["\155\157\x5f\x6f\141\x75\x74\150\137\x75\160\x67\162\141\144\x69\x6e\x67\137\x74\x6f\137\166\x65\162\163\151\157\x6e"])) : '';
        $Fc = isset($_POST["\155\x6f\137\146\145\x61\x74\165\x72\145\163\x5f\162\x65\x71\x75\151\162\145\144"]) ? sanitize_text_field(wp_unslash($_POST["\155\x6f\137\146\145\x61\x74\x75\162\145\x73\137\x72\145\x71\165\151\x72\145\144"])) : '';
        $me = new Customer();
        if ($this->util->mo_oauth_check_empty_or_null($g3)) {
            goto AT;
        }
        $Cu = $me->submit_contact_us_upgrade($g3, $q2, $x3, $Fc);
        if (false === $Cu) {
            goto xF;
        }
        $this->util->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\x54\x68\x61\x6e\153\163\x20\146\157\162\x20\147\145\x74\164\x69\156\147\x20\151\x6e\40\164\x6f\165\143\x68\x21\x20\x57\x65\x20\x73\150\x61\154\x6c\x20\147\145\164\x20\x62\141\x63\x6b\40\x74\x6f\x20\171\x6f\165\x20\x73\x68\x6f\x72\164\x6c\171\x20\167\151\x74\150\40\x71\165\157\x74\x61\x74\x69\157\x6e");
        $this->util->mo_oauth_show_success_message();
        goto XY;
        xF:
        $this->util->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\x59\x6f\x75\162\40\161\165\145\x72\171\x20\x63\x6f\x75\154\x64\40\156\157\164\x20\x62\145\x20\163\165\142\155\x69\x74\164\x65\144\56\40\x50\154\145\x61\163\x65\40\164\162\x79\40\141\x67\141\x69\156\x2e");
        $this->util->mo_oauth_show_error_message();
        XY:
        goto QQ;
        AT:
        $this->util->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\120\154\x65\x61\x73\145\x20\146\x69\154\154\40\165\160\x20\x45\x6d\x61\151\154\x20\x66\151\x65\x6c\x64\40\x74\157\x20\x73\x75\142\x6d\151\164\40\x79\x6f\165\x72\x20\161\165\145\162\x79\56");
        $this->util->mo_oauth_show_error_message();
        QQ:
        sT:
        if (!($MS == "\x64\151\x73\141\x62\x6c\145\x64")) {
            goto ms;
        }
        if (!(isset($_POST["\x6d\x6f\137\x6f\141\165\164\x68\137\x72\x65\x73\164\x6f\x72\x65\137\x62\141\143\153\165\x70\x5f\156\x6f\156\143\145"]) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST["\x6d\157\x5f\157\141\165\x74\150\137\162\x65\163\x74\157\x72\x65\137\142\x61\x63\153\x75\x70\137\156\x6f\x6e\x63\x65"])), "\x6d\x6f\x5f\x6f\x61\165\x74\150\x5f\162\145\163\164\x6f\x72\x65\x5f\142\141\x63\x6b\x75\160") && isset($_POST[\MoOAuthConstants::OPTION]) && "\x6d\157\x5f\x6f\141\x75\x74\150\x5f\162\145\163\164\x6f\x72\x65\137\142\x61\x63\153\165\x70" === $_POST[\MoOAuthConstants::OPTION])) {
            goto Jn;
        }
        $eZ = "\x54\x68\145\x72\145\40\167\141\163\40\x61\x6e\x20\145\162\162\x6f\162\40\165\160\x6c\157\x61\144\x69\156\x67\x20\164\150\x65\40\146\x69\x6c\x65";
        if (isset($_FILES["\155\x6f\x5f\157\141\x75\x74\x68\x5f\x63\154\x69\145\156\164\x5f\142\141\143\x6b\x75\x70"])) {
            goto pJ;
        }
        $this->util->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, $eZ);
        $this->util->mo_oauth_show_error_message();
        return;
        pJ:
        if (!function_exists("\x77\x70\137\150\x61\156\x64\154\145\137\x75\160\x6c\x6f\x61\x64")) {
            require_once ABSPATH . "\x77\x70\55\x61\144\155\151\x6e\57\x69\x6e\x63\154\165\x64\145\x73\x2f\x66\151\154\145\x2e\x70\150\160";
        }
        $ev = $_FILES["\x6d\x6f\137\157\141\165\164\150\137\143\154\x69\145\x6e\x74\x5f\x62\x61\143\x6b\x75\x70"];
        if (!(!isset($ev["\145\x72\x72\x6f\x72"]) || is_array($ev["\x65\x72\x72\157\x72"]) || UPLOAD_ERR_OK !== $ev["\145\x72\162\x6f\162"])) {
            goto OS;
        }
        $this->util->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, $eZ . "\x3a\x20" . json_encode($ev["\x65\x72\162\157\162"], JSON_UNESCAPED_SLASHES));
        $this->util->mo_oauth_show_error_message();
        return;
        OS:
        $Cj = new \finfo(FILEINFO_MIME_TYPE);
        $fN = array_search($Cj->file($ev["\164\x6d\160\x5f\156\141\x6d\x65"]), array("\164\145\x78\x74" => "\x74\145\170\164\57\160\154\x61\x69\x6e", "\152\x73\157\x6e" => "\x61\x70\x70\154\x69\143\x61\x74\x69\157\156\x2f\152\x73\157\156"), true);
        $Nw = explode("\56", $ev["\x6e\141\x6d\x65"]);
        $Nw = $Nw[count($Nw) - 1];
        if (!(false === $fN || $Nw !== "\x6a\163\x6f\156")) {
            goto y_;
        }
        $this->util->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, $eZ . "\72\40\x49\x6e\166\141\x6c\x69\x64\x20\x46\x69\154\145\x20\106\157\162\155\x61\x74\x2e");
        $this->util->mo_oauth_show_error_message();
        return;
        y_:
        $V2 = file_get_contents($ev["\x74\155\160\x5f\x6e\141\x6d\145"]);
        $Kn = json_decode($V2, true);
        if (!(json_last_error() !== JSON_ERROR_NONE)) {
            goto nq;
        }
        $this->util->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, $eZ . "\x3a\40\111\x6e\x76\141\x6c\151\144\x20\x46\x69\x6c\145\40\106\x6f\x72\155\x61\164\x2e");
        $this->util->mo_oauth_show_error_message();
        return;
        nq:
        $NT = BackupHandler::restore_settings($Kn);
        if (!$NT) {
            goto IB;
        }
        $this->util->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\123\x65\164\164\x69\156\147\x73\40\162\145\x73\x74\x6f\x72\145\x64\x20\163\x75\143\143\x65\163\163\x66\165\154\154\x79\x2e");
        $this->util->mo_oauth_show_success_message();
        return;
        IB:
        $this->util->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\x54\150\145\162\145\x20\x77\141\163\40\141\156\40\x69\x73\163\165\145\x20\x77\150\151\x6c\x65\x20\x72\145\x73\164\x6f\x72\x69\x6e\x67\x20\x74\x68\x65\x20\x63\157\x6e\146\151\147\165\x72\x61\x74\151\157\156\x2e");
        $this->util->mo_oauth_show_error_message();
        return;
        Jn:
        if (!(isset($_POST["\x6d\x6f\137\x6f\x61\x75\x74\150\x5f\x64\157\x77\156\x6c\x6f\141\144\x5f\x62\x61\x63\x6b\165\x70\137\156\x6f\x6e\143\145"]) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST["\x6d\x6f\x5f\x6f\141\165\x74\150\137\x64\x6f\167\x6e\154\157\141\x64\x5f\142\141\143\x6b\x75\160\137\156\157\156\143\x65"])), "\x6d\x6f\x5f\x6f\x61\165\x74\150\137\x64\157\167\156\x6c\x6f\141\x64\137\x62\141\143\x6b\165\160") && isset($_POST[\MoOAuthConstants::OPTION]) && "\155\157\x5f\157\x61\165\164\150\x5f\144\157\x77\x6e\x6c\x6f\x61\x64\x5f\142\141\x63\x6b\x75\x70" === $_POST[\MoOAuthConstants::OPTION])) {
            goto vJ;
        }
        $zv = BackupHandler::get_backup_json();
        header("\103\x6f\x6e\x74\145\x6e\164\55\124\171\x70\145\72\x20\141\160\x70\x6c\151\143\x61\x74\151\x6f\x6e\x2f\152\163\x6f\x6e");
        header("\103\157\x6e\164\145\x6e\164\x2d\x44\x69\163\160\157\163\151\164\x69\x6f\156\x3a\40\x61\164\x74\x61\x63\x68\155\145\156\x74\x3b\x20\x66\151\x6c\x65\156\x61\x6d\x65\75\42\x70\x6c\x75\147\x69\156\x5f\142\141\143\x6b\165\160\x2e\152\163\x6f\156\x22");
        header("\103\x6f\156\x74\145\156\x74\x2d\x4c\145\156\x67\164\150\72\x20" . strlen($zv));
        header("\103\157\x6e\156\x65\143\164\151\157\156\x3a\x20\x63\154\157\x73\145");
        echo $zv;
        exit;
        vJ:
        ms:
        do_action("\144\157\137\155\141\151\x6e\137\163\x65\164\x74\151\x6e\147\163\x5f\151\x6e\x74\145\x72\156\x61\154", $_POST);
    }
    public function mo_oauth_get_current_customer()
    {
        $me = new Customer();
        $dV = $me->get_customer_key();
        $i_ = json_decode($dV, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            goto Ax;
        }
        $this->util->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\131\x6f\165\40\141\x6c\x72\145\141\144\x79\x20\150\141\166\145\x20\x61\156\x20\x61\143\143\x6f\x75\156\x74\x20\x77\x69\x74\x68\x20\x6d\151\156\151\117\x72\141\156\147\145\56\40\x50\154\x65\x61\x73\145\40\x65\x6e\x74\x65\x72\40\x61\x20\x76\x61\154\151\x64\x20\x70\x61\x73\163\167\157\x72\x64\56");
        $this->util->mo_oauth_client_update_option("\166\x65\162\151\146\x79\137\143\165\x73\164\157\155\145\x72", "\164\162\165\145");
        $this->util->mo_oauth_show_error_message();
        goto kM;
        Ax:
        $this->util->mo_oauth_client_update_option("\155\157\x5f\x6f\141\165\x74\x68\137\x61\x64\155\151\x6e\x5f\143\165\163\164\x6f\x6d\145\x72\x5f\153\x65\x79", $i_["\151\x64"]);
        $this->util->mo_oauth_client_update_option("\x6d\157\x5f\157\x61\x75\164\x68\137\x61\x64\155\151\x6e\137\141\160\151\x5f\153\145\171", $i_["\x61\160\151\113\145\171"]);
        $this->util->mo_oauth_client_update_option("\143\x75\163\164\157\155\x65\x72\x5f\x74\x6f\153\145\156", $i_["\x74\157\153\145\x6e"]);
        $this->util->mo_oauth_client_update_option("\x70\141\x73\x73\x77\x6f\162\x64", '');
        $this->util->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\103\165\163\164\157\x6d\145\162\40\x72\x65\x74\x72\x69\x65\166\x65\x64\40\x73\x75\143\x63\145\x73\163\146\x75\154\x6c\171");
        $this->util->mo_oauth_client_delete_option("\166\145\162\x69\x66\171\x5f\x63\x75\x73\x74\x6f\x6d\145\x72");
        $this->util->mo_oauth_client_delete_option("\156\x65\x77\137\x72\x65\147\x69\163\x74\x72\x61\164\x69\157\x6e");
        $this->util->mo_oauth_show_success_message();
        kM:
    }
    public function create_customer()
    {
        global $Uj;
        $me = new Customer();
        $i_ = json_decode($me->create_customer(), true);
        if (strcasecmp($i_["\163\164\x61\164\165\163"], "\103\x55\123\x54\117\x4d\105\x52\137\125\x53\x45\122\116\101\x4d\x45\137\x41\114\x52\x45\x41\104\131\137\x45\130\111\123\x54\x53") === 0) {
            goto Al;
        }
        if (strcasecmp($i_["\163\x74\141\x74\165\163"], "\x53\125\103\103\105\123\x53") === 0) {
            goto JS;
        }
        goto m3;
        Al:
        $this->mo_oauth_get_current_customer();
        $this->util->mo_oauth_client_delete_option("\x6d\157\137\157\x61\x75\x74\150\x5f\156\x65\x77\x5f\x63\x75\163\164\157\155\145\162");
        goto m3;
        JS:
        $this->util->mo_oauth_client_update_option("\x6d\x6f\137\157\141\165\164\x68\x5f\141\x64\x6d\151\156\x5f\143\165\x73\x74\157\155\145\x72\x5f\x6b\145\171", $i_["\x69\x64"]);
        $this->util->mo_oauth_client_update_option("\x6d\157\137\157\x61\x75\x74\x68\137\141\x64\x6d\151\156\137\141\x70\x69\137\x6b\145\x79", $i_["\141\160\x69\x4b\145\171"]);
        $this->util->mo_oauth_client_update_option("\x63\165\163\164\157\155\145\x72\137\x74\157\153\145\x6e", $i_["\x74\157\153\x65\x6e"]);
        $this->util->mo_oauth_client_update_option("\160\141\163\x73\167\157\x72\144", '');
        $this->util->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\x52\145\147\x69\163\164\145\162\x65\144\x20\163\165\x63\143\145\x73\x73\x66\165\154\x6c\171\x2e");
        $this->util->mo_oauth_client_update_option("\x6d\157\137\157\141\165\x74\x68\x5f\162\x65\x67\x69\163\164\x72\x61\164\151\x6f\156\137\x73\164\141\x74\x75\x73", "\x4d\117\137\117\101\125\x54\x48\137\122\x45\x47\x49\x53\124\122\101\x54\x49\x4f\x4e\x5f\x43\117\115\x50\114\x45\x54\105");
        $this->util->mo_oauth_client_update_option("\155\157\137\157\141\x75\x74\x68\137\x6e\145\x77\x5f\x63\165\163\164\157\x6d\x65\x72", 1);
        $this->util->mo_oauth_client_delete_option("\x76\145\162\x69\x66\171\x5f\x63\x75\x73\x74\157\x6d\x65\162");
        $this->util->mo_oauth_client_delete_option("\156\x65\x77\x5f\162\x65\147\151\x73\164\x72\141\x74\x69\157\156");
        $this->util->mo_oauth_show_success_message();
        m3:
    }
}
