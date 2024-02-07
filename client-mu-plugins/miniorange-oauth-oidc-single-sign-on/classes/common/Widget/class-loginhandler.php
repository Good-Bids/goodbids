<?php


namespace MoOauthClient;

use MoOauthClient\Base\InstanceHelper;
use MoOauthClient\MO_Custom_OAuth1;
use MoOauthClient\OauthHandler;
use MoOauthClient\StorageManager;
class LoginHandler
{
    public $oauth_handler;
    public function __construct()
    {
        $this->oauth_handler = new OauthHandler();
        add_action("\x69\x6e\x69\x74", array($this, "\x6d\x6f\137\157\141\165\x74\x68\137\144\145\143\151\x64\x65\137\x66\154\x6f\x77"));
        add_action("\x6d\157\137\x6f\x61\x75\164\150\137\143\x6c\x69\145\156\x74\x5f\164\x69\x67\x68\x74\137\x6c\x6f\147\151\x6e\137\151\156\x74\x65\x72\156\141\154", array($this, "\x68\141\156\x64\154\x65\137\163\x73\x6f"), 10, 4);
    }
    public function mo_oauth_decide_flow()
    {
        global $Yh;
        if (!(isset($_REQUEST[\MoOAuthConstants::OPTION]) && "\164\145\x73\164\x61\x74\164\x72\155\141\x70\x70\151\x6e\147\143\157\156\146\x69\147" === $_REQUEST[\MoOAuthConstants::OPTION])) {
            goto Iy;
        }
        $kQ = isset($_REQUEST["\x61\160\x70"]) ? sanitize_text_field(wp_unslash($_REQUEST["\141\160\160"])) : '';
        wp_safe_redirect(site_url() . "\x3f\157\x70\x74\x69\157\156\75\157\x61\x75\164\150\162\145\144\x69\162\145\143\164\x26\x61\x70\160\x5f\156\141\x6d\145\x3d" . rawurlencode($kQ) . "\46\x74\145\163\164\x3d\164\162\165\x65");
        exit;
        Iy:
        $this->mo_oauth_login_validate();
    }
    public function mo_oauth_login_validate()
    {
        global $Yh;
        $Wb = $Yh->get_plugin_config()->get_current_config();
        $uw = true;
        $uw = $Yh->mo_oauth_aemoutcrahsaphtn() == "\x65\x6e\141\142\x6c\145\x64" ? false : true;
        $eC = new StorageManager();
        if (!(isset($_REQUEST[\MoOAuthConstants::OPTION]) && !is_array($_REQUEST["\157\x70\164\151\x6f\156"]) && strpos(sanitize_text_field(wp_unslash($_REQUEST[\MoOAuthConstants::OPTION])), "\157\x61\165\164\150\162\x65\x64\151\x72\x65\x63\x74") !== false)) {
            goto zq;
        }
        if ($uw) {
            goto Gg;
        }
        wp_safe_redirect(site_url() . "\x2f\x77\x70\x2d\x61\144\155\151\x6e");
        exit;
        Gg:
        if (isset($_REQUEST["\155\x6f\x5f\x6c\157\147\x69\x6e\137\160\x6f\160\165\160"])) {
            goto dd;
        }
        if (!(isset($_REQUEST["\162\145\x73\x6f\x75\x72\x63\145"]) && !empty($_REQUEST["\x72\x65\163\157\x75\x72\143\145"]))) {
            goto mr;
        }
        if (!empty($_REQUEST["\x72\145\x73\x6f\x75\x72\143\145"])) {
            goto lM;
        }
        $Yh->handle_error("\124\x68\x65\x20\x72\145\163\x70\x6f\x6e\163\x65\x20\146\x72\157\155\x20\x75\x73\x65\162\151\156\146\x6f\x20\x77\141\163\40\145\x6d\x70\x74\x79\x2e");
        MO_Oauth_Debug::mo_oauth_log("\124\150\x65\x20\x72\x65\x73\160\x6f\156\x73\x65\x20\146\x72\157\155\40\x75\x73\145\x72\151\x6e\x66\157\x20\x77\x61\163\x20\145\155\160\164\171\x2e");
        wp_die(wp_kses("\x54\x68\x65\x20\x72\x65\x73\160\x6f\156\x73\145\40\x66\162\x6f\155\40\x75\x73\x65\x72\x69\156\146\x6f\40\167\x61\x73\x20\145\x6d\x70\x74\x79\x2e", \mo_oauth_get_valid_html()));
        lM:
        $eC = new StorageManager(isset($_REQUEST["\162\145\x73\x6f\165\162\x63\x65"]) ? sanitize_text_field(wp_unslash(urldecode($_REQUEST["\162\145\163\x6f\165\162\x63\145"]))) : '');
        $J6 = $eC->get_value("\162\145\x73\157\x75\162\143\145");
        $fZ = $eC->get_value("\x61\x70\x70\156\x61\155\145");
        $tV = $eC->get_value("\x72\145\144\151\x72\x65\143\x74\137\165\162\151");
        $C2 = $eC->get_value("\x61\143\x63\145\163\163\x5f\164\157\x6b\x65\156");
        $KY = $Yh->get_app_by_name($fZ)->get_app_config();
        $RV = isset($_REQUEST["\x74\x65\x73\x74"]) && !empty($_REQUEST["\164\145\x73\x74"]);
        if (!($RV && '' !== $RV)) {
            goto eE;
        }
        $this->handle_group_test_conf($J6, $KY, $C2, false, $RV);
        exit;
        eE:
        $eC->remove_key("\162\145\163\x6f\x75\162\143\x65");
        $eC->add_replace_entry("\160\157\x70\165\x70", "\x69\x67\x6e\x6f\162\145");
        if (!has_filter("\167\x6f\157\x63\157\x6d\155\145\162\143\145\137\x63\x68\145\x63\x6b\157\x75\164\137\x67\145\x74\x5f\166\x61\x6c\x75\145")) {
            goto a3;
        }
        $J6["\x61\160\x70\156\141\155\145"] = $fZ;
        a3:
        do_action("\155\157\137\141\142\162\137\x66\151\x6c\x74\x65\162\x5f\x6c\157\x67\x69\156", $J6);
        $this->handle_sso($fZ, $KY, $J6, $eC->get_state(), ["\141\x63\x63\145\163\163\x5f\164\x6f\x6b\x65\x6e" => $C2]);
        mr:
        if (isset($_REQUEST["\141\160\x70\x5f\156\x61\155\x65"])) {
            goto yj;
        }
        $q3 = "\120\154\x65\x61\x73\145\40\143\x68\145\x63\153\x20\151\x66\40\x79\x6f\x75\40\141\x72\x65\x20\x73\x65\156\x64\151\156\147\40\x74\150\x65\x20\x27\x61\160\160\x5f\x6e\x61\x6d\145\x27\x20\x70\141\162\141\155\x65\164\145\x72";
        $Yh->handle_error($q3);
        MO_Oauth_Debug::mo_oauth_log($q3);
        wp_die(wp_kses($q3, \mo_oauth_get_valid_html()));
        exit;
        yj:
        $d9 = isset($_REQUEST["\141\x70\160\137\156\141\155\145"]) && !empty($_REQUEST["\141\160\x70\137\x6e\x61\155\x65"]) ? sanitize_text_field(wp_unslash($_REQUEST["\141\160\x70\x5f\x6e\x61\155\145"])) : '';
        if (!($d9 == '')) {
            goto bU;
        }
        $q3 = "\x4e\157\x20\x73\x75\143\x68\x20\141\160\160\40\146\157\165\156\x64\x20\143\x6f\x6e\x66\151\x67\x75\x72\x65\x64\x2e\40\120\x6c\x65\x61\x73\x65\40\x63\150\145\143\x6b\40\x69\x66\40\x79\157\x75\x20\141\x72\145\x20\163\x65\156\144\151\156\x67\40\164\150\145\x20\x63\157\x72\162\x65\143\x74\x20\x61\x70\160\137\156\x61\155\x65";
        MO_Oauth_Debug::mo_oauth_log($q3);
        $Yh->handle_error($q3);
        wp_die(wp_kses($q3, \mo_oauth_get_valid_html()));
        exit;
        bU:
        $mc = $Yh->mo_oauth_client_get_option("\155\x6f\x5f\x6f\x61\x75\x74\x68\x5f\x61\160\160\x73\x5f\154\151\163\164");
        if (is_array($mc) && isset($mc[$d9])) {
            goto Je;
        }
        $q3 = "\x4e\157\40\163\x75\143\150\40\141\x70\160\40\x66\157\x75\x6e\x64\x20\143\x6f\x6e\146\151\147\165\162\x65\144\56\40\120\154\145\x61\x73\x65\40\x63\150\x65\143\x6b\x20\x69\146\x20\171\x6f\165\x20\141\x72\x65\x20\x73\x65\x6e\x64\x69\x6e\x67\x20\x74\x68\x65\x20\143\157\x72\162\145\x63\x74\x20\x61\160\x70\137\156\141\155\145";
        MO_Oauth_Debug::mo_oauth_log($q3);
        $Yh->handle_error($q3);
        wp_die(wp_kses($q3, \mo_oauth_get_valid_html()));
        exit;
        Je:
        $qr = "\57\x2f" . $_SERVER["\110\124\x54\x50\137\x48\117\123\x54"] . $_SERVER["\x52\x45\x51\x55\x45\x53\x54\x5f\x55\122\111"];
        $qr = strtok($qr, "\77");
        $EJ = isset($_REQUEST["\x72\145\144\151\162\145\x63\164\x5f\x75\162\154"]) ? sanitize_text_field(wp_unslash(urldecode($_REQUEST["\162\x65\x64\151\162\x65\143\x74\x5f\x75\x72\x6c"]))) : $qr;
        $RV = isset($_REQUEST["\x74\x65\x73\x74"]) ? sanitize_text_field(wp_unslash(urldecode($_REQUEST["\x74\x65\x73\164"]))) : false;
        $tS = isset($_REQUEST["\x72\x65\x73\164\162\x69\143\x74\x72\x65\144\151\162\145\143\164"]) ? sanitize_text_field(wp_unslash(urldecode($_REQUEST["\162\145\x73\164\x72\151\143\164\162\145\144\151\x72\145\x63\164"]))) : false;
        $F8 = $Yh->get_app_by_name($d9);
        $bO = $F8->get_app_config("\147\x72\141\156\164\x5f\164\171\x70\145");
        if (!is_multisite()) {
            goto Sr;
        }
        $blog_id = get_current_blog_id();
        $W_ = $Yh->mo_oauth_client_get_option("\x6d\x6f\x5f\x6f\x61\165\x74\x68\137\x63\63\126\x69\x63\x32\154\x30\x5a\x58\x4e\172\x5a\127\x78\154\x59\63\122\154\x5a\101");
        $ZU = array();
        if (!isset($W_)) {
            goto Tv;
        }
        $ZU = json_decode($Yh->mooauthdecrypt($W_), true);
        Tv:
        $EL = false;
        $jj = $Yh->mo_oauth_client_get_option("\155\157\x5f\x6f\x61\165\x74\x68\137\151\x73\x4d\165\154\164\151\x53\x69\164\x65\x50\x6c\x75\x67\151\156\x52\145\161\x75\145\163\164\145\144");
        if (!(is_array($ZU) && in_array($blog_id, $ZU))) {
            goto pR;
        }
        $EL = true;
        pR:
        if (!(is_multisite() && $jj && ($jj && !$EL) && !$RV && $Yh->mo_oauth_client_get_option("\x6e\157\x4f\x66\123\165\x62\x53\x69\x74\145\163") < 1000)) {
            goto MF;
        }
        $Yh->handle_error("\114\x6f\147\151\x6e\40\x69\163\x20\x64\151\x73\x61\142\x6c\x65\x64\x20\x66\157\162\40\x74\150\151\x73\x20\x73\x69\164\x65\56\x20\x50\154\x65\141\x73\x65\40\143\157\156\x74\141\143\x74\40\x79\x6f\165\x72\x20\141\x64\155\151\156\151\163\x74\x72\141\x74\x6f\162\x2e");
        MO_Oauth_Debug::mo_oauth_log("\x4c\x6f\147\151\x6e\40\151\163\x20\x64\x69\x73\x61\x62\x6c\145\x64\x20\x66\157\162\40\x74\x68\151\x73\40\163\x69\164\145\56\40\120\154\x65\141\163\x65\40\143\157\156\x74\x61\143\164\x20\171\x6f\x75\x72\x20\141\144\155\151\x6e\151\163\164\x72\141\164\x6f\x72\56");
        wp_die("\x4c\x6f\147\151\156\x20\x69\x73\x20\144\151\x73\141\142\154\x65\x64\40\x66\157\x72\x20\x74\150\151\x73\40\x73\x69\164\145\56\x20\x50\154\145\x61\163\x65\40\143\x6f\156\x74\x61\x63\x74\x20\171\x6f\165\162\40\x61\x64\155\151\156\151\163\164\162\141\x74\157\x72\x2e");
        MF:
        $eC->add_replace_entry("\142\154\157\x67\x5f\x69\144", $blog_id);
        Sr:
        MO_Oauth_Debug::mo_oauth_log("\x47\162\141\x6e\x74\x3a\x20" . $bO);
        if ($bO && "\120\141\x73\x73\167\x6f\x72\x64\40\107\162\141\x6e\x74" === $bO) {
            goto j_;
        }
        if (!($bO && "\x43\154\151\x65\156\x74\x20\103\162\x65\144\x65\x6e\164\151\141\x6c\x73\x20\107\x72\141\156\x74" === $bO)) {
            goto ir;
        }
        do_action("\155\157\x5f\x6f\x61\x75\x74\150\x5f\143\154\151\x65\156\164\x5f\x63\162\145\x64\x65\x6e\164\x69\141\x6c\163\x5f\147\x72\141\156\x74\x5f\151\156\x69\x74\x69\141\164\145", $d9, $RV);
        exit;
        ir:
        goto VJ;
        j_:
        do_action("\x70\x77\144\x5f\145\163\163\x65\x6e\164\x69\x61\154\163\137\x69\156\164\145\x72\156\141\x6c");
        do_action("\x6d\157\137\x6f\x61\x75\164\150\137\x63\x6c\x69\x65\156\x74\x5f\x61\144\144\137\x70\x77\144\x5f\x6a\163");
        echo "\x9\x9\x9\11\74\x73\143\x72\151\x70\x74\76\xd\xa\11\11\11\x9\11\166\141\x72\x20\x6d\x6f\x5f\x6f\x61\165\x74\x68\137\x61\x70\x70\x5f\156\141\x6d\x65\x20\75\x20\x22";
        echo wp_kses($d9, \mo_oauth_get_valid_html());
        echo "\42\73\xd\xa\x9\x9\x9\11\x9\144\157\143\x75\x6d\x65\x6e\164\56\x61\x64\x64\105\x76\x65\x6e\x74\114\x69\x73\x74\145\156\145\x72\x28\47\x44\x4f\x4d\x43\x6f\x6e\164\145\x6e\x74\114\157\141\144\x65\x64\x27\54\x20\146\x75\156\x63\164\x69\157\x6e\x28\51\x20\173\xd\xa\11\x9\x9\x9\x9\x9";
        if ($RV) {
            goto eV;
        }
        echo "\11\11\11\11\11\11\11\155\157\117\101\165\164\150\x4c\157\x67\x69\x6e\x50\x77\x64\x28\x6d\x6f\x5f\157\141\x75\164\x68\x5f\x61\x70\x70\x5f\x6e\141\x6d\145\54\40\146\x61\154\163\145\54\40\47";
        echo esc_url($EJ);
        echo "\x27\x29\x3b\15\xa\x9\11\11\11\x9\11";
        goto CP;
        eV:
        echo "\x9\x9\x9\x9\11\x9\x9\155\x6f\x4f\x41\165\x74\x68\114\157\x67\x69\156\x50\x77\144\x28\x6d\157\137\157\141\165\x74\x68\137\x61\160\x70\137\156\141\155\x65\x2c\40\x74\162\165\145\x2c\x20\47";
        echo esc_url($EJ);
        echo "\x27\x29\x3b\15\12\11\11\x9\11\11\x9";
        CP:
        echo "\x9\x9\x9\x9\x9\x7d\x2c\x20\146\x61\x6c\163\x65\51\73\15\12\11\x9\11\x9\x3c\57\x73\x63\162\151\160\x74\76\15\12\x9\11\11\11";
        exit;
        VJ:
        if (!($F8->get_app_config("\x61\x70\160\x49\x64") === "\x74\x77\x69\164\164\x65\162" || $F8->get_app_config("\141\x70\160\111\144") === "\157\x61\165\164\x68\61")) {
            goto pM;
        }
        MO_Oauth_Debug::mo_oauth_log("\x4f\x61\165\164\150\61\40\146\x6c\157\167");
        $RV = isset($_REQUEST["\164\x65\x73\164"]) && !empty($_REQUEST["\x74\x65\163\x74"]);
        if (!($RV && '' !== $RV)) {
            goto Gj;
        }
        setcookie("\x6f\141\x75\164\150\61\x5f\x74\x65\163\164", "\61", time() + 20);
        Gj:
        setcookie("\x6f\141\165\164\x68\61\x61\x70\x70\x6e\x61\x6d\145", $d9, time() + 60);
        $_COOKIE["\x6f\x61\165\164\x68\61\x61\x70\160\156\141\155\x65"] = $d9;
        MO_Custom_OAuth1::mo_oauth1_auth_request($d9);
        exit;
        pM:
        $KD = md5(rand(0, 15));
        $eC->add_replace_entry("\x61\160\160\x6e\141\x6d\x65", $d9);
        $eC->add_replace_entry("\x72\145\x64\x69\x72\145\x63\x74\x5f\x75\x72\151", $EJ);
        $eC->add_replace_entry("\164\145\x73\x74\x5f\143\157\156\x66\x69\147", $RV);
        $eC->add_replace_entry("\162\x65\163\164\x72\151\143\x74\x72\145\144\x69\162\145\143\x74", $tS);
        $eC->add_replace_entry("\x73\164\141\164\145\137\x6e\157\x6e\143\x65", $KD);
        $eC = apply_filters("\155\157\x5f\x6f\141\165\x74\x68\137\x73\145\x74\137\x63\165\x73\164\157\155\137\163\164\x6f\x72\x61\147\x65", $eC);
        $GP = $eC->get_state();
        $GP = apply_filters("\163\x74\141\x74\x65\x5f\151\156\x74\x65\162\x6e\x61\154", $GP);
        $sx = $F8->get_app_config("\141\x75\x74\x68\157\162\151\172\145\165\x72\x6c");
        if (!($F8->get_app_config("\163\x65\x6e\x64\137\163\x74\141\164\145") === false || $F8->get_app_config("\163\x65\156\x64\x5f\x73\x74\x61\164\x65") === '')) {
            goto A3;
        }
        $F8->update_app_config("\x73\145\156\x64\137\163\x74\x61\x74\145", 1);
        $Yh->set_app_by_name($d9, $F8->get_app_config('', false));
        A3:
        if ($F8->get_app_config("\163\145\156\144\137\163\x74\x61\x74\145")) {
            goto y0;
        }
        setcookie("\x73\164\x61\x74\145\137\160\141\162\x61\x6d", $GP, time() + 60);
        y0:
        $G7 = $F8->get_app_config("\x70\x6b\x63\145\137\x66\154\x6f\x77");
        $tV = $F8->get_app_config("\162\145\144\x69\162\x65\x63\164\137\x75\x72\151");
        $z6 = urlencode($F8->get_app_config("\143\154\151\x65\156\164\x5f\x69\x64"));
        $tV = empty($tV) ? \site_url() : $tV;
        if ($G7 && 1 === $G7) {
            goto gU;
        }
        $Na = $F8->get_app_config("\163\x65\x6e\x64\x5f\163\164\x61\164\x65") ? "\46\x73\x74\141\164\145\x3d" . $GP : '';
        if ($F8->get_app_config("\x73\145\x6e\144\x5f\x73\164\141\164\145")) {
            goto qr;
        }
        setcookie("\x73\164\x61\x74\x65\x5f\x70\x61\162\141\x6d", $GP, time() + 60);
        MO_Oauth_Debug::mo_oauth_log("\x73\164\141\164\x65\40\x70\141\162\x61\155\x65\164\145\162\x20\x6e\x6f\164\x20\163\x65\156\x74");
        goto Ql;
        qr:
        MO_Oauth_Debug::mo_oauth_log("\x73\x74\141\164\145\x20\160\x61\x72\141\155\145\164\x65\x72\x20\163\x65\156\x74");
        Ql:
        if (strpos($sx, "\x3f") !== false) {
            goto Xi;
        }
        $sx = $sx . "\x3f\x63\154\x69\x65\156\x74\x5f\x69\144\x3d" . $z6 . "\x26\163\143\x6f\160\x65\x3d" . $F8->get_app_config("\x73\143\157\x70\145") . "\46\162\x65\x64\151\x72\x65\143\164\x5f\x75\x72\x69\x3d" . urlencode($tV) . "\x26\x72\145\163\160\157\156\x73\145\137\164\x79\160\x65\75\143\x6f\144\145" . $Na;
        goto cQ;
        Xi:
        $sx = $sx . "\x26\143\154\x69\145\156\x74\137\151\x64\75" . $z6 . "\46\x73\x63\157\x70\x65\75" . $F8->get_app_config("\163\143\157\x70\x65") . "\x26\x72\145\144\151\x72\145\143\164\137\x75\x72\x69\75" . urlencode($tV) . "\46\162\145\163\x70\x6f\156\x73\145\137\164\171\160\145\x3d\x63\157\144\145" . $Na;
        cQ:
        goto le;
        gU:
        MO_Oauth_Debug::mo_oauth_log("\120\113\103\105\40\146\154\x6f\167");
        $g0 = bin2hex(openssl_random_pseudo_bytes(32));
        $L0 = $Yh->base64url_encode(pack("\x48\52", $g0));
        $hb = $Yh->base64url_encode(pack("\x48\52", hash("\x73\x68\141\x32\65\x36", $L0)));
        $eC->add_replace_entry("\x63\157\144\145\137\166\x65\x72\x69\146\151\145\162", $L0);
        $GP = $eC->get_state();
        $Na = $F8->get_app_config("\x73\x65\x6e\x64\137\163\164\x61\x74\145") ? "\46\163\x74\x61\164\x65\x3d" . $GP : '';
        if ($F8->get_app_config("\163\145\156\144\x5f\163\164\x61\164\x65")) {
            goto Fl;
        }
        MO_Oauth_Debug::mo_oauth_log("\x73\x74\x61\164\145\x20\x70\141\x72\141\x6d\x65\164\x65\162\x20\x6e\157\164\40\163\x65\156\x74");
        goto mi;
        Fl:
        MO_Oauth_Debug::mo_oauth_log("\163\164\x61\164\x65\x20\160\x61\162\x61\x6d\x65\164\x65\x72\x20\x73\145\156\164");
        mi:
        if (strpos($sx, "\77") !== false) {
            goto v1;
        }
        $sx = $sx . "\x3f\143\x6c\x69\145\156\x74\137\151\144\75" . $z6 . "\x26\163\143\157\x70\x65\x3d" . $F8->get_app_config("\163\x63\157\160\145") . "\46\x72\x65\x64\x69\x72\145\x63\164\137\165\162\151\75" . urlencode($tV) . "\46\162\145\x73\160\157\x6e\x73\x65\137\164\x79\160\145\75\143\x6f\144\145" . $Na . "\46\x63\157\144\x65\137\143\x68\141\x6c\x6c\145\x6e\x67\x65\x3d" . $hb . "\x26\143\x6f\144\x65\x5f\143\150\141\154\x6c\x65\x6e\x67\145\x5f\x6d\145\164\x68\157\x64\75\123\62\65\66";
        goto b0;
        v1:
        $sx = $sx . "\46\143\x6c\x69\x65\x6e\x74\137\x69\x64\75" . $z6 . "\46\x73\143\x6f\160\145\75" . $F8->get_app_config("\x73\x63\157\160\145") . "\46\162\x65\144\x69\162\145\x63\164\137\165\x72\151\x3d" . urlencode($tV) . "\46\162\145\163\x70\x6f\x6e\x73\145\x5f\164\x79\x70\x65\x3d\143\157\x64\x65" . $Na . "\x26\143\x6f\x64\x65\137\x63\x68\141\x6c\154\145\156\x67\145\75" . $hb . "\x26\143\x6f\144\x65\137\x63\150\141\154\x6c\145\156\x67\x65\137\x6d\x65\164\x68\157\144\75\123\x32\65\66";
        b0:
        le:
        if (!(null !== $F8->get_app_config("\163\x65\x6e\x64\x5f\156\x6f\x6e\143\145") && $F8->get_app_config("\x73\x65\x6e\144\x5f\156\x6f\156\x63\145"))) {
            goto g1;
        }
        $YV = md5(rand());
        $Yh->set_transient("\155\157\x5f\157\x61\x75\164\150\137\156\157\x6e\143\x65\137" . $YV, $YV, time() + 120);
        $sx = $sx . "\x26\x6e\157\x6e\143\x65\75" . $YV;
        MO_Oauth_Debug::mo_oauth_log("\156\x6f\x6e\143\x65\x20\x70\x61\x72\x61\x6d\145\164\145\162\x20\x73\x65\156\x74");
        g1:
        if (!(strpos($sx, "\x61\x70\160\154\145") !== false)) {
            goto QE;
        }
        $sx = $sx . "\x26\162\x65\163\x70\x6f\156\x73\x65\137\x6d\157\x64\x65\x3d\x66\x6f\x72\x6d\x5f\x70\x6f\163\164";
        QE:
        if (!(isset($_SERVER["\x52\105\121\x55\x45\x53\124\137\x55\122\111"]) and strpos($_SERVER["\x52\x45\121\125\105\x53\124\137\x55\122\111"], "\x6d\157\157\x69\144\x63\x63\141\x6c\x6c\142\141\x63\153") !== false)) {
            goto fO;
        }
        if (!($F8->get_app_config("\141\160\160\111\x64") == "\x56\145\x6e\x64\141\x73\164\141" && isset($_REQUEST["\141\x63\x63\x6f\165\156\x74\x5f\151\144"]))) {
            goto qx;
        }
        $sx .= "\46\x61\x63\x63\x6f\x75\156\164\x5f\x69\x64\75" . $_REQUEST["\x61\x63\x63\157\x75\156\164\137\151\x64"];
        qx:
        fO:
        $sx = apply_filters("\155\157\x5f\x61\x75\x74\x68\x5f\x75\x72\x6c\137\x69\156\164\145\162\156\x61\154", $sx, $d9);
        MO_Oauth_Debug::mo_oauth_log("\101\165\x74\x68\x6f\x72\151\172\141\x69\157\x6e\x20\105\x6e\144\160\157\x69\156\164\40\x3d\x3e\40" . $sx);
        header("\114\157\x63\141\x74\x69\157\156\x3a\x20" . $sx);
        exit;
        dd:
        zq:
        if (isset($_GET["\145\x72\162\157\x72\137\x64\x65\x73\x63\162\151\160\x74\x69\x6f\156"])) {
            goto Z1;
        }
        if (!isset($_GET["\145\x72\162\157\162"])) {
            goto vM;
        }
        if (!empty($_GET["\145\162\162\x6f\162"])) {
            goto qD;
        }
        MO_Oauth_Debug::mo_oauth_log("\x52\x65\143\x65\151\x76\x65\144\x2c\x20\105\x6d\160\164\171\x20\105\x72\162\x6f\x72\x20\x66\162\157\x6d\x20\x41\165\x74\150\x6f\x72\x69\172\145\x20\105\156\144\x70\x6f\x69\156\x74");
        return;
        qD:
        do_action("\155\157\137\x72\x65\144\151\x72\145\x63\164\x5f\x74\157\137\143\165\163\x74\157\x6d\137\145\x72\162\157\x72\137\160\141\x67\145");
        $N5 = "\105\162\x72\157\162\x20\x66\162\157\155\40\x41\x75\x74\150\157\x72\x69\x7a\145\x20\x45\x6e\x64\160\x6f\151\x6e\x74\x3a\x20" . sanitize_text_field(wp_unslash($_GET["\145\162\162\x6f\x72"]));
        MO_Oauth_Debug::mo_oauth_log($N5);
        $Yh->handle_error($N5);
        wp_die($N5);
        vM:
        goto sJ;
        Z1:
        if (!(strpos($_GET["\x73\x74\141\x74\x65"], "\x64\x6f\x6b\x61\156\55\x73\164\x72\x69\160\145\x2d\143\x6f\x6e\x6e\x65\x63\164") !== false)) {
            goto ke;
        }
        return;
        ke:
        do_action("\x6d\x6f\137\x72\145\x64\151\x72\145\143\164\x5f\x74\x6f\x5f\143\165\163\164\x6f\x6d\137\145\x72\162\157\162\x5f\x70\x61\147\145");
        $SU = "\105\x72\x72\x6f\162\x20\x64\x65\163\143\x72\x69\x70\164\x69\157\x6e\x20\146\x72\x6f\155\x20\x41\165\164\x68\157\162\x69\x7a\x65\40\105\x6e\x64\x70\x6f\151\156\x74\72\40" . sanitize_text_field($_GET["\145\x72\162\x6f\162\x5f\x64\x65\x73\x63\x72\151\160\164\151\157\156"]);
        MO_Oauth_Debug::mo_oauth_log($SU);
        $Yh->handle_error($SU);
        wp_die($SU);
        sJ:
        if (!(strpos($_SERVER["\x52\105\x51\x55\105\x53\124\137\125\x52\111"], "\x6f\x70\x65\156\x69\144\143\141\x6c\154\x62\141\143\x6b") !== false || strpos($_SERVER["\x52\x45\121\125\105\123\x54\x5f\125\122\111"], "\157\141\x75\164\x68\137\x74\157\153\145\156") !== false && strpos($_SERVER["\122\x45\121\x55\x45\x53\x54\137\x55\x52\111"], "\x6f\141\x75\164\x68\137\166\x65\162\x69\146\151\x65\162"))) {
            goto bv;
        }
        MO_Oauth_Debug::mo_oauth_log("\117\141\165\x74\150\61\x20\x63\x61\154\154\x62\141\143\x6b\40\x66\154\x6f\167");
        if (!empty($_COOKIE["\157\x61\165\164\150\61\141\160\160\x6e\141\155\x65"])) {
            goto jQ;
        }
        MO_Oauth_Debug::mo_oauth_log("\122\145\x74\165\162\156\151\156\x67\x20\146\x72\x6f\x6d\40\117\x41\165\164\x68\x31");
        return;
        jQ:
        MO_Oauth_Debug::mo_oauth_log("\x4f\101\165\x74\x68\61\x20\141\160\160\40\x66\157\x75\x6e\144");
        $d9 = $_COOKIE["\157\141\165\164\150\61\141\x70\x70\156\x61\155\145"];
        $J6 = MO_Custom_OAuth1::mo_oidc1_get_access_token($_COOKIE["\x6f\x61\x75\x74\150\x31\141\160\160\156\141\x6d\145"]);
        $Bs = apply_filters("\x6d\157\137\x74\x72\x5f\x61\x66\164\x65\162\x5f\160\x72\157\146\151\x6c\x65\x5f\151\x6e\x66\157\x5f\x65\x78\x74\x72\x61\143\164\x69\157\156\x5f\146\x72\157\x6d\137\x74\157\x6b\x65\156", $J6);
        $sU = [];
        $F2 = $this->dropdownattrmapping('', $J6, $sU);
        $Yh->mo_oauth_client_update_option("\155\x6f\137\x6f\x61\165\164\x68\137\141\164\x74\x72\x5f\x6e\x61\x6d\x65\137\154\151\x73\x74" . $d9, $F2);
        if (!(isset($_COOKIE["\x6f\x61\x75\164\150\61\137\x74\145\x73\164"]) && $_COOKIE["\x6f\141\x75\164\150\x31\x5f\x74\145\x73\x74"] == "\x31")) {
            goto E6;
        }
        $F8 = $Yh->get_app_by_name($d9);
        $zn = $F8->get_app_config();
        $this->render_test_config_output($J6, false, $zn, $d9);
        exit;
        E6:
        $F8 = $Yh->get_app_by_name($d9);
        $g5 = $F8->get_app_config("\165\163\145\x72\x6e\141\155\x65\137\x61\164\x74\162");
        $YR = isset($KY["\x65\155\x61\x69\154\137\x61\164\x74\162"]) ? $KY["\145\x6d\141\151\154\x5f\141\164\164\x72"] : '';
        $Mv = $Yh->getnestedattribute($J6, $YR);
        $un = $Yh->getnestedattribute($J6, $g5);
        if (!empty($un)) {
            goto hD;
        }
        MO_Oauth_Debug::mo_oauth_log("\125\x73\x65\162\x6e\x61\155\x65\x20\x6e\157\164\40\x72\145\143\145\x69\166\145\x64\x2e\120\154\x65\x61\x73\145\x20\143\157\x6e\146\x69\147\x75\162\x65\x20\101\x74\x74\x72\x69\x62\165\x74\x65\40\115\x61\x70\160\x69\x6e\147");
        $Yh->handle_error("\x55\x73\145\x72\x6e\x61\x6d\x65\x20\156\x6f\164\40\x72\145\143\x65\151\166\x65\144\x2e\120\154\x65\x61\x73\145\x20\143\x6f\156\146\x69\147\x75\162\x65\x20\101\x74\164\x72\x69\x62\165\x74\145\x20\x4d\141\160\160\x69\156\147");
        wp_die("\x55\x73\x65\x72\156\x61\155\145\40\156\x6f\x74\40\162\145\x63\145\x69\x76\145\x64\x2e\x50\154\145\x61\x73\x65\x20\x63\x6f\156\x66\x69\x67\x75\162\x65\x20\101\x74\164\x72\151\x62\x75\x74\x65\x20\x4d\141\160\x70\151\156\147");
        hD:
        if (!empty($Mv)) {
            goto TC;
        }
        $user = get_user_by("\x6c\157\x67\x69\x6e", $un);
        goto EN;
        TC:
        $Mv = $Yh->getnestedattribute($J6, $YR);
        if (!(false === strpos($Mv, "\100"))) {
            goto B1;
        }
        MO_Oauth_Debug::mo_oauth_log("\115\x61\x70\x70\x65\144\40\105\155\x61\x69\x6c\40\141\x74\x74\x72\x69\x62\x75\x74\x65\x20\x64\x6f\145\163\40\x6e\x6f\164\40\143\x6f\156\164\141\x69\x6e\40\166\141\154\151\x64\40\x65\x6d\x61\x69\154\x2e");
        $Yh->handle_error("\115\141\x70\x70\x65\144\40\105\155\x61\x69\x6c\x20\x61\164\164\162\x69\142\165\x74\145\x20\144\157\145\x73\40\x6e\x6f\164\x20\143\157\x6e\x74\141\x69\x6e\40\x76\x61\x6c\151\x64\x20\145\x6d\141\x69\154\x2e");
        wp_die("\x4d\141\160\x70\145\144\40\x45\x6d\141\x69\154\x20\141\164\164\162\151\x62\x75\164\x65\x20\x64\157\145\163\40\156\x6f\x74\40\x63\157\x6e\164\x61\x69\x6e\x20\166\141\x6c\x69\144\x20\145\155\141\x69\154\x2e");
        B1:
        EN:
        if ($user) {
            goto dK;
        }
        $QH = 0;
        if ($Yh->mo_oauth_hbca_xyake()) {
            goto cb;
        }
        $user = $Yh->mo_oauth_hjsguh_kiishuyauh878gs($Mv, $un);
        goto aJ;
        cb:
        if ($Yh->mo_oauth_client_get_option("\x6d\x6f\137\x6f\x61\x75\164\x68\137\146\x6c\x61\147") !== true) {
            goto VO;
        }
        $Al = base64_decode('PGRpdiBzdHlsZT0ndGV4dC1hbGlnbjpjZW50ZXI7Jz48Yj5Vc2VyIEFjY291bnQgZG9lcyBub3QgZXhpc3QuPC9iPjwvZGl2Pjxicj48c21hbGw+VGhpcyB2ZXJzaW9uIHN1cHBvcnRzIEF1dG8gQ3JlYXRlIFVzZXIgZmVhdHVyZSB1cHRvIDEwIFVzZXJzLiBQbGVhc2UgdXBncmFkZSB0byB0aGUgaGlnaGVyIHZlcnNpb24gb2YgdGhlIHBsdWdpbiB0byBlbmFibGUgYXV0byBjcmVhdGUgdXNlciBmb3IgdW5saW1pdGVkIHVzZXJzIG9yIGFkZCB1c2VyIG1hbnVhbGx5Ljwvc21hbGw+');
        $Yh->handle_error($Al);
        MO_Oauth_Debug::mo_oauth_log($Al);
        wp_die($Al);
        goto Rd;
        VO:
        if (!empty($Mv)) {
            goto cM;
        }
        $user = $Yh->mo_oauth_jhuyn_jgsukaj($un, $un);
        goto NI;
        cM:
        $user = $Yh->mo_oauth_jhuyn_jgsukaj($Mv, $un);
        NI:
        Rd:
        aJ:
        goto w6;
        dK:
        $QH = $user->ID;
        w6:
        if (!$user) {
            goto b3;
        }
        wp_set_current_user($user->ID);
        $Et = false;
        $Et = apply_filters("\155\157\x5f\x72\x65\x6d\145\155\x62\145\162\x5f\155\145", $Et);
        wp_set_auth_cookie($user->ID, $Et);
        $user = get_user_by("\x49\104", $user->ID);
        do_action("\167\160\x5f\x6c\x6f\147\x69\x6e", $user->user_login, $user);
        wp_safe_redirect(home_url());
        exit;
        b3:
        bv:
        if (!isset($_SERVER["\x48\x54\124\x50\x5f\130\x5f\x52\105\121\x55\x45\x53\124\105\104\137\127\x49\x54\x48"]) && (strpos($_SERVER["\x52\105\121\125\105\123\124\137\125\x52\111"], "\x2f\157\141\x75\x74\150\x63\141\154\154\x62\x61\x63\x6b") !== false || isset($_REQUEST["\143\157\144\x65"]) && !empty($_REQUEST["\x63\x6f\x64\x65"]) && !isset($_REQUEST["\151\x64\137\164\157\x6b\145\x6e"]))) {
            goto J2;
        }
        if (!empty($_POST["\x72\x65\x66\x72\145\x73\150\x5f\x74\157\153\145\156"])) {
            goto wC;
        }
        goto S8;
        J2:
        MO_Oauth_Debug::mo_oauth_log("\x46\x6c\157\x77\40\x72\145\x64\151\x72\x65\143\164\x65\x64\40\x62\x61\143\x6b\x20\164\x6f\40\117\x41\165\164\x68\40\160\x6c\165\x67\x69\156\x2e");
        MO_Oauth_Debug::mo_oauth_log($_REQUEST);
        if (!(isset($_REQUEST["\160\157\x73\164\137\111\x44"]) || isset($_REQUEST["\x65\x64\x64\x2d\141\x63\164\151\157\x6e"]))) {
            goto vL;
        }
        return;
        vL:
        if (!(isset($_REQUEST["\160\x61\147\145"]) && strcmp($_REQUEST["\160\x61\147\145"], "\160\x6d\x70\162\157\55\x64\151\163\x63\x6f\165\x6e\164\143\157\144\x65\163") !== false)) {
            goto TO;
        }
        return;
        TO:
        if (!(strpos($_SERVER["\122\x45\x51\125\x45\x53\124\137\125\122\111"], "\x2f\x77\x70\x2d\x6a\x73\157\156\x2f\x6d\157\163\x65\x72\166\145\162\57\164\157\x6b\x65\156") !== false)) {
            goto VB;
        }
        return;
        VB:
        if (!($Yh->mo_oauth_aemoutcrahsaphtn() == "\x65\156\141\x62\154\145\x64")) {
            goto Vw;
        }
        MO_Oauth_Debug::mo_oauth_log("\x53\123\117\40\x44\151\163\141\x62\154\145\144\56\40\x50\x6c\145\x61\x73\145\40\143\157\156\164\141\143\164\x20\164\x68\145\40\x61\x64\155\151\156\151\163\x74\162\141\x74\x6f\162");
        wp_die("\123\123\117\x20\104\x69\163\x61\142\154\145\x64\56\x20\120\x6c\145\141\163\x65\40\x63\x6f\x6e\164\141\x63\x74\40\164\150\145\40\x61\144\x6d\151\x6e\x69\163\x74\x72\x61\164\157\162");
        return;
        Vw:
        try {
            if (isset($_COOKIE["\x73\x74\141\164\145\137\x70\141\162\141\155"])) {
                goto vW;
            }
            if (isset($_GET["\x73\x74\x61\164\145"]) && !empty($_GET["\163\x74\x61\164\145"])) {
                goto Bh;
            }
            $my = new StorageManager();
            if (!is_multisite()) {
                goto ms;
            }
            $my->add_replace_entry("\x62\x6c\157\147\x5f\x69\144", 1);
            ms:
            $p3 = $Yh->get_app_by_name();
            if (isset($_GET["\x61\160\160\137\x6e\141\x6d\x65"])) {
                goto Kj;
            }
            $my->add_replace_entry("\141\160\x70\156\x61\x6d\145", $p3->get_app_name());
            goto AD;
            Kj:
            $my->add_replace_entry("\141\x70\x70\156\141\x6d\x65", sanitize_text_field(wp_unslash($_GET["\141\160\x70\x5f\156\x61\x6d\145"])));
            AD:
            $my->add_replace_entry("\164\145\x73\x74\137\x63\x6f\156\146\151\147", false);
            $my->add_replace_entry("\x72\145\x64\151\x72\145\143\164\x5f\x75\162\151", site_url());
            $GP = $my->get_state();
            goto ti;
            vW:
            MO_Oauth_Debug::mo_oauth_log("\106\145\164\x63\150\145\x64\x20\x73\164\141\x74\145\40\x70\x61\x72\x61\x6d\145\x74\145\x72\x20\146\162\157\x6d\40\143\x6f\157\153\x69\x65");
            $GP = $_COOKIE["\x73\164\141\x74\x65\x5f\x70\x61\162\141\155"];
            goto ti;
            Bh:
            $GP = sanitize_text_field(wp_unslash($_GET["\x73\164\141\x74\145"]));
            ti:
            $eC = new StorageManager($GP);
            MO_Oauth_Debug::mo_oauth_log("\123\x65\x74\40\x73\164\157\x72\141\x67\x65\40\x6d\x61\x6e\x61\147\145\162\x20\x66\162\x6f\155\40\163\x74\x61\x74\x65");
            if (!empty($eC->get_value("\x61\x70\x70\x6e\x61\155\x65"))) {
                goto jR;
            }
            return;
            jR:
            $d9 = $eC->get_value("\141\x70\160\x6e\141\x6d\x65");
            MO_Oauth_Debug::mo_oauth_log("\101\x70\x70\154\x69\x63\x61\x74\x69\x6f\x6e\40\x43\157\x6e\146\x69\x67\165\x72\x65\x64\72\x20" . $d9);
            $RV = $eC->get_value("\164\x65\x73\164\137\143\x6f\156\x66\151\x67");
            if (!is_multisite()) {
                goto I1;
            }
            if (!(empty($eC->get_value("\162\145\144\151\162\145\143\164\145\144\x5f\164\x6f\x5f\x73\165\142\x73\151\164\x65")) || $eC->get_value("\162\145\144\x69\162\145\x63\164\x65\x64\x5f\x74\x6f\x5f\x73\165\142\x73\x69\x74\145") !== "\162\x65\144\151\162\x65\143\x74")) {
                goto KX;
            }
            MO_Oauth_Debug::mo_oauth_log("\x52\145\144\x69\162\145\143\x74\x69\157\156\x20\x66\x6f\162\40\x6d\x75\x6c\164\x69\163\x69\x74\x65\40\x65\156\x76\x69\162\157\156\155\x65\156\164\x2e");
            $blog_id = $eC->get_value("\142\154\157\x67\x5f\x69\x64");
            $Y7 = get_site_url($blog_id);
            $eC->add_replace_entry("\162\x65\144\x69\162\145\x63\164\x65\144\x5f\x74\157\137\x73\165\142\x73\x69\164\145", "\162\x65\144\151\162\x65\x63\x74");
            $g7 = $eC->get_state();
            $Y7 = $Y7 . "\77\x63\x6f\x64\x65\x3d" . $_REQUEST["\143\x6f\x64\145"] . "\x26\x73\164\141\x74\145\75" . $g7;
            MO_Oauth_Debug::mo_oauth_log("\x52\145\144\151\162\145\x63\164\151\x6e\147\x20\164\157\x20\163\165\142\163\151\x74\x65\x20\x3d\x3e" . $Y7);
            wp_redirect($Y7);
            exit;
            KX:
            I1:
            $fZ = $d9 ? $d9 : '';
            $mc = $Yh->mo_oauth_client_get_option("\x6d\157\x5f\x6f\141\x75\x74\150\x5f\x61\x70\x70\x73\x5f\x6c\x69\x73\x74");
            $g5 = '';
            $YR = '';
            $I9 = $Yh->get_app_by_name($fZ);
            if ($I9) {
                goto kp;
            }
            $Yh->handle_error("\101\x70\160\154\151\x63\141\x74\x69\x6f\x6e\x20\x6e\x6f\164\40\143\x6f\156\146\151\x67\165\162\x65\144\56");
            MO_Oauth_Debug::mo_oauth_log("\x41\x70\160\x6c\151\x63\141\164\151\157\156\x20\156\x6f\164\x20\x63\157\156\146\151\147\165\162\145\144\x2e");
            exit("\x41\x70\160\x6c\151\x63\x61\x74\x69\157\156\40\156\x6f\164\40\143\x6f\156\x66\x69\147\x75\x72\x65\144\56");
            kp:
            $KY = $I9->get_app_config();
            if (!(isset($KY["\x73\145\156\x64\x5f\x6e\x6f\156\143\x65"]) && $KY["\163\x65\x6e\x64\137\x6e\x6f\156\x63\x65"] === 1)) {
                goto g8;
            }
            if (!(isset($_REQUEST["\156\x6f\156\143\x65"]) && !$Yh->get_transient("\x6d\157\137\157\141\165\x74\x68\x5f\x6e\157\156\143\145\137" . $_REQUEST["\156\x6f\x6e\143\145"]))) {
                goto T3;
            }
            $N5 = "\x4e\157\156\x63\145\x20\x76\145\162\x69\x66\151\x63\141\164\x69\x6f\156\40\151\x73\x20\x66\x61\151\x6c\145\144\x2e\40\x50\154\145\141\x73\x65\x20\x63\x6f\x6e\164\141\143\164\40\x74\157\40\171\157\x75\x72\40\x61\144\x6d\x69\156\x69\x73\164\x72\x61\x74\157\x72\x2e";
            $Yh->handle_error($N5);
            MO_Oauth_Debug::mo_oauth_log($N5);
            wp_die($N5);
            T3:
            g8:
            $G7 = $I9->get_app_config("\160\x6b\x63\145\x5f\x66\154\x6f\167");
            $rO = $I9->get_app_config("\x70\153\143\x65\137\x63\x6c\x69\x65\x6e\x74\137\x73\145\143\x72\145\x74");
            $uo = array("\x67\x72\141\156\x74\x5f\164\171\x70\x65" => "\x61\x75\x74\150\157\162\151\172\141\164\x69\157\x6e\x5f\x63\x6f\144\145", "\x63\154\x69\x65\156\x74\x5f\x69\x64" => $KY["\143\154\151\x65\156\x74\x5f\x69\x64"], "\x72\145\144\151\162\x65\x63\164\137\165\x72\151" => $KY["\x72\x65\x64\x69\162\145\143\164\137\x75\x72\x69"], "\143\157\x64\145" => $_REQUEST["\x63\157\x64\x65"]);
            if (!(strpos($KY["\x61\x63\143\x65\x73\x73\x74\157\153\x65\156\x75\x72\154"], "\x73\x65\162\x76\151\143\x65\x73\57\x6f\141\x75\x74\150\x32\x2f\x74\157\153\x65\156") === false && strpos($KY["\x61\143\143\145\163\163\164\157\153\x65\x6e\x75\x72\x6c"], "\163\141\x6c\145\163\146\157\162\x63\145") === false && strpos($KY["\x61\x63\143\145\x73\163\x74\157\x6b\x65\x6e\165\x72\154"], "\x2f\157\141\x6d\x2f\x6f\141\x75\164\150\x32\x2f\x61\x63\143\145\163\x73\x5f\164\x6f\153\145\156") === false)) {
                goto H6;
            }
            $uo["\x73\143\157\160\x65"] = $I9->get_app_config("\163\x63\157\x70\x65");
            H6:
            if ($G7 && 1 === $G7) {
                goto Po;
            }
            $uo["\x63\154\x69\145\x6e\164\x5f\x73\x65\143\x72\x65\x74"] = $KY["\x63\154\151\145\156\164\137\x73\145\x63\162\145\164"];
            goto vJ;
            Po:
            if (!($rO && 1 === $rO)) {
                goto kX;
            }
            $uo["\143\x6c\x69\x65\156\x74\x5f\x73\145\143\162\145\x74"] = $KY["\143\x6c\151\145\156\x74\x5f\163\145\x63\x72\145\x74"];
            kX:
            $uo = apply_filters("\155\157\137\x6f\x61\165\x74\x68\137\x61\144\144\x5f\143\x6c\x69\x65\156\164\x5f\x73\145\143\162\145\x74\x5f\x70\153\x63\x65\137\x66\x6c\x6f\x77", $uo, $KY);
            $uo["\143\157\144\x65\x5f\x76\145\162\x69\146\x69\145\162"] = $eC->get_value("\x63\157\x64\145\137\x76\145\162\x69\146\x69\x65\x72");
            vJ:
            $U_ = isset($KY["\163\145\156\x64\137\x68\x65\141\x64\145\x72\163"]) ? $KY["\x73\145\156\144\x5f\x68\145\141\x64\x65\x72\x73"] : 0;
            $fn = isset($KY["\x73\145\156\144\x5f\142\157\144\x79"]) ? $KY["\x73\145\156\x64\137\x62\157\144\171"] : 0;
            if ("\157\160\145\x6e\151\x64\143\157\156\156\x65\143\x74" === $I9->get_app_config("\x61\160\x70\137\x74\x79\x70\145")) {
                goto Gt;
            }
            $pd = $KY["\141\143\143\x65\x73\163\164\x6f\x6b\x65\156\x75\162\154"];
            MO_Oauth_Debug::mo_oauth_log("\117\101\x75\x74\x68\x20\x66\x6c\x6f\x77");
            if (strpos($KY["\x61\165\x74\x68\x6f\x72\x69\x7a\145\165\x72\154"], "\x63\154\145\x76\x65\x72\x2e\x63\157\x6d\x2f\157\141\x75\164\x68") != false || strpos($KY["\x61\x63\143\x65\163\163\164\157\153\x65\156\165\x72\x6c"], "\142\x69\164\162\151\170") != false) {
                goto PU;
            }
            $TD = json_decode($this->oauth_handler->get_token($pd, $uo, $U_, $fn), true);
            goto Es;
            PU:
            $TD = json_decode($this->oauth_handler->get_atoken($pd, $uo, $_GET["\x63\x6f\x64\x65"], $U_, $fn), true);
            Es:
            if (!(get_current_user_id() && $RV != 1)) {
                goto OH;
            }
            if (has_filter("\x6d\x6f\137\157\x61\x75\164\150\137\142\x72\x65\x61\153\137\x73\x73\157\x5f\x66\154\x6f\167")) {
                goto Qp;
            }
            wp_clear_auth_cookie();
            wp_set_current_user(0);
            Qp:
            OH:
            $_SESSION["\x70\162\x6f\x63\x6f\162\145\137\x61\x63\143\x65\x73\x73\x5f\x74\x6f\x6b\145\x6e"] = isset($TD["\141\143\143\x65\163\163\137\164\157\153\x65\x6e"]) ? $TD["\x61\x63\143\145\x73\163\x5f\x74\157\153\x65\156"] : false;
            if (isset($TD["\141\x63\x63\145\x73\x73\137\164\x6f\x6b\145\156"])) {
                goto h1;
            }
            do_action("\x6d\157\137\162\145\x64\151\162\x65\x63\x74\137\x74\157\x5f\x63\x75\x73\164\x6f\x6d\137\x65\162\162\157\x72\137\160\141\x67\145");
            $Yh->handle_error("\111\156\166\141\x6c\151\144\40\164\157\153\x65\x6e\40\x72\x65\x63\x65\x69\166\145\144\56");
            MO_Oauth_Debug::mo_oauth_log("\111\156\x76\141\x6c\x69\x64\40\164\x6f\153\x65\x6e\40\x72\x65\143\x65\x69\x76\x65\144\x2e");
            exit("\111\156\x76\141\x6c\151\144\40\x74\157\153\x65\x6e\40\162\x65\x63\x65\x69\x76\145\x64\56");
            h1:
            MO_Oauth_Debug::mo_oauth_log("\x54\157\153\x65\156\x20\122\145\x73\160\157\156\163\145\x20\x3d\76\40");
            MO_Oauth_Debug::mo_oauth_log($TD);
            $pU = $KY["\x72\145\163\157\x75\x72\143\x65\157\167\156\x65\162\x64\145\x74\141\x69\x6c\163\x75\x72\154"];
            if (!(substr($pU, -1) === "\75")) {
                goto Ch;
            }
            $pU .= $TD["\x61\x63\143\x65\163\x73\137\x74\157\153\x65\156"];
            Ch:
            MO_Oauth_Debug::mo_oauth_log("\x41\x63\x63\145\x73\163\x20\x74\157\153\145\156\x20\x72\x65\x63\x65\151\166\x65\x64\x2e");
            MO_Oauth_Debug::mo_oauth_log("\x41\x63\x63\145\163\163\x20\x54\157\x6b\145\156\x20\x3d\76\40" . $TD["\141\143\143\145\x73\163\x5f\164\157\153\145\x6e"]);
            $J6 = false;
            MO_Oauth_Debug::mo_oauth_log("\122\x65\163\157\x75\x72\143\145\x20\x4f\x77\156\145\x72\40\x3d\76\40");
            if (!has_filter("\x6d\x6f\x5f\x75\163\145\x72\151\x6e\x66\x6f\137\146\x6c\157\x77\x5f\x66\157\162\137\x77\x61\x6c\x6d\x61\162\164")) {
                goto xS;
            }
            $J6 = apply_filters("\155\x6f\x5f\x75\163\145\162\x69\x6e\146\157\137\146\x6c\157\167\x5f\x66\157\x72\137\x77\141\154\155\141\x72\164", $pU, $TD, $uo, $fZ, $KY);
            xS:
            if (!($J6 === false)) {
                goto pd;
            }
            $nr = null;
            $nr = apply_filters("\155\x6f\137\160\157\x6c\141\162\137\162\x65\x67\x69\x73\x74\x65\x72\x5f\x75\163\145\162", $TD);
            if (!(!empty($nr) && !empty($TD["\170\137\165\163\145\x72\x5f\151\x64"]))) {
                goto Oq;
            }
            $pU .= "\x2f" . $TD["\170\137\165\163\145\162\x5f\151\x64"];
            Oq:
            $J6 = $this->oauth_handler->get_resource_owner($pU, $TD["\141\143\x63\x65\163\163\x5f\x74\157\153\x65\x6e"]);
            $RM = array();
            if (!(strpos($I9->get_app_config("\x61\165\164\x68\157\162\x69\x7a\145\165\162\x6c"), "\x6c\151\x6e\x6b\145\144\x69\x6e") !== false && strpos($KY["\163\143\157\160\145"], "\x72\x5f\x65\x6d\141\x69\x6c\141\144\144\162\x65\x73\x73") != false)) {
                goto Px;
            }
            $E8 = "\150\x74\164\x70\x73\x3a\x2f\57\141\160\x69\56\154\x69\x6e\x6b\x65\x64\151\x6e\x2e\143\157\155\x2f\166\x32\57\x65\155\141\151\x6c\x41\x64\x64\x72\x65\163\163\x3f\161\75\155\x65\x6d\142\x65\x72\163\x26\160\162\x6f\x6a\x65\143\164\151\157\156\75\x28\x65\x6c\145\x6d\145\x6e\x74\163\x2a\50\150\141\156\x64\x6c\145\176\51\51";
            $RM = $this->oauth_handler->get_resource_owner($E8, $TD["\141\143\x63\145\x73\163\137\x74\x6f\x6b\145\156"]);
            Px:
            $J6 = array_merge($J6, $RM);
            pd:
            if (!has_filter("\155\157\137\x63\x68\145\143\153\137\x74\x6f\137\x65\x78\x65\143\165\x74\x65\x5f\x70\157\163\164\137\x75\x73\145\162\151\156\x66\x6f\x5f\x66\154\157\x77\x5f\x66\157\162\x5f\x77\141\x6c\155\x61\162\164")) {
                goto HE;
            }
            $J6 = apply_filters("\155\x6f\137\143\x68\x65\143\x6b\137\164\157\x5f\x65\x78\145\143\x75\x74\145\x5f\160\x6f\x73\x74\x5f\165\x73\145\x72\151\156\146\157\x5f\146\x6c\157\167\137\x66\x6f\x72\x5f\x77\141\x6c\x6d\141\x72\x74", $J6, $fZ, $KY);
            HE:
            MO_Oauth_Debug::mo_oauth_log($J6);
            if (has_filter("\155\157\x5f\x67\145\x74\137\164\157\153\x65\x6e\x5f\146\157\162\x5f\156\x65\157\x6e\137\x6d\x65\155\x62\x65\x72\163\x68\151\160")) {
                goto kG;
            }
            $Bs = apply_filters("\x6d\157\x5f\164\162\137\x61\146\x74\x65\x72\x5f\x70\162\157\146\x69\154\145\x5f\x69\x6e\x66\157\x5f\145\x78\164\162\141\143\x74\151\157\x6e\137\x66\162\157\155\137\164\157\x6b\145\156", $J6);
            goto HT;
            kG:
            $Bs = apply_filters("\x6d\157\x5f\x67\x65\x74\137\x74\157\x6b\145\156\137\146\x6f\162\x5f\x6e\145\157\156\x5f\155\145\155\142\x65\162\x73\150\151\x70", $J6, $TD["\x61\143\143\x65\163\x73\x5f\x74\x6f\153\x65\156"]);
            HT:
            if (!($Bs != '' && is_array($Bs))) {
                goto xk;
            }
            $J6 = array_merge($J6, $Bs);
            xk:
            $Pc = apply_filters("\141\x63\143\162\x65\144\x69\164\x69\157\156\x73\137\145\156\x64\x70\x6f\151\x6e\164", $TD["\x61\x63\x63\145\163\x73\137\x74\157\x6b\145\x6e"]);
            if (!($Pc !== '' && is_array($Pc))) {
                goto ac;
            }
            $J6 = array_merge($J6, $Pc);
            ac:
            if (!has_filter("\x6d\157\137\160\157\154\141\162\137\162\x65\x67\x69\x73\164\145\x72\x5f\x75\163\145\162")) {
                goto Kg;
            }
            $F3 = array();
            $F3["\x74\157\x6b\x65\x6e"] = $TD["\141\143\x63\145\163\163\x5f\x74\x6f\153\x65\156"];
            $J6 = array_merge($J6, $F3);
            Kg:
            if (!(strpos($I9->get_app_config("\141\x75\164\x68\x6f\x72\151\172\x65\165\x72\x6c"), "\x64\151\x73\x63\157\x72\x64") !== false)) {
                goto DM;
            }
            apply_filters("\x6d\x6f\x5f\144\151\x73\x5f\x75\163\x65\162\137\141\x74\164\x65\x6e\144\141\x6e\x63\145", $J6["\151\x64"]);
            $Aw = apply_filters("\x6d\x6f\137\x64\162\155\137\147\x65\164\x5f\165\163\145\x72\x5f\x72\157\x6c\x65\163", array_key_exists("\151\x64", $J6) ? $J6["\151\144"] : '');
            if (!(false !== $Aw)) {
                goto PB;
            }
            MO_Oauth_Debug::mo_oauth_log("\x44\151\x73\143\157\162\144\40\x52\157\x6c\145\163\x20\x3d\76\x20");
            MO_Oauth_Debug::mo_oauth_log($Aw);
            $J6["\x72\157\x6c\x65\x73"] = $Aw;
            PB:
            if (!(!$RV && '' == $RV)) {
                goto Bm;
            }
            do_action("\155\x6f\x5f\x6f\x61\165\x74\150\137\141\x64\x64\137\144\x69\163\137\165\x73\x65\162\137\163\x65\x72\166\145\162", get_current_user_id(), $TD, $J6);
            Bm:
            DM:
            if (!(isset($KY["\x73\145\x6e\144\x5f\156\x6f\156\x63\x65"]) && $KY["\x73\x65\156\144\137\156\157\156\x63\145"] === 1)) {
                goto YU;
            }
            if (!(isset($J6["\x6e\x6f\x6e\x63\x65"]) && $J6["\156\x6f\x6e\x63\x65"] != NULL)) {
                goto Qi;
            }
            if ($Yh->get_transient("\155\157\137\x6f\x61\x75\x74\x68\137\156\x6f\x6e\143\x65\137" . $J6["\156\x6f\x6e\143\x65"])) {
                goto vr;
            }
            $N5 = "\x4e\157\156\x63\145\40\166\145\162\151\x66\151\143\141\x74\x69\x6f\x6e\x20\x69\x73\x20\146\141\151\x6c\x65\x64\x2e\40\x50\x6c\145\x61\163\x65\x20\x63\x6f\x6e\164\x61\143\x74\x20\x74\157\x20\x79\157\x75\x72\40\x61\x64\x6d\151\x6e\x69\x73\x74\162\141\164\x6f\162\56";
            $Yh->handle_error($N5);
            MO_Oauth_Debug::mo_oauth_log($N5);
            wp_die($N5);
            goto HO;
            vr:
            $Yh->delete_transient("\x6d\x6f\137\x6f\141\x75\x74\150\137\x6e\x6f\156\x63\x65\x5f" . $J6["\x6e\157\156\x63\145"]);
            HO:
            Qi:
            YU:
            $sU = [];
            $F2 = $this->dropdownattrmapping('', $J6, $sU);
            $Yh->mo_oauth_client_update_option("\x6d\x6f\137\157\x61\x75\164\150\137\x61\164\x74\162\x5f\156\141\155\145\x5f\x6c\151\163\x74" . $fZ, $F2);
            if (!($RV && '' !== $RV)) {
                goto Bk;
            }
            $this->handle_group_test_conf($J6, $KY, $TD["\141\143\x63\145\x73\x73\137\x74\x6f\153\x65\x6e"], false, $RV);
            exit;
            Bk:
            goto dZ;
            Gt:
            MO_Oauth_Debug::mo_oauth_log("\117\x70\x65\156\111\104\40\x43\x6f\156\156\x65\x63\x74\40\x66\x6c\x6f\x77");
            $TD = json_decode($this->oauth_handler->get_token($KY["\141\143\143\145\163\x73\164\157\153\x65\x6e\x75\x72\x6c"], $uo, $U_, $fn), true);
            MO_Oauth_Debug::mo_oauth_log("\124\157\153\x65\x6e\x20\122\x65\x73\x70\x6f\156\x73\145\x20\x3d\x3e\x20");
            MO_Oauth_Debug::mo_oauth_log($TD);
            $KH = [];
            try {
                $KH = $this->resolve_and_get_oidc_response($TD);
            } catch (\Exception $Wi) {
                $Yh->handle_error($Wi->getMessage());
                MO_Oauth_Debug::mo_oauth_log("\x49\156\x76\141\154\151\x64\x20\122\x65\x73\x70\x6f\156\163\145\40\162\145\143\x65\151\166\x65\144\56");
                do_action("\x6d\x6f\137\162\145\144\x69\162\x65\x63\x74\137\164\157\137\143\x75\163\x74\x6f\155\x5f\x65\162\x72\x6f\162\137\160\141\x67\145");
                wp_die("\111\x6e\166\x61\x6c\151\144\x20\x52\x65\163\x70\157\156\163\145\40\162\145\143\x65\x69\x76\145\144\56");
                exit;
            }
            if (!has_action("\x6d\x6f\137\x6f\141\x75\x74\x68\x5f\x73\x61\x76\x65\137\x61\x63\143\x65\163\x73\137\164\x6f\x6b\145\156\137\151\x6e\137\143\157\157\153\x69\145")) {
                goto fx;
            }
            do_action("\155\157\x5f\x6f\141\165\164\x68\137\x73\x61\x76\145\x5f\x61\x63\x63\x65\163\x73\x5f\164\x6f\x6b\x65\156\137\151\x6e\x5f\x63\x6f\157\153\x69\145", $TD["\141\143\x63\145\x73\163\x5f\x74\157\153\x65\156"]);
            fx:
            MO_Oauth_Debug::mo_oauth_log("\111\104\x20\124\x6f\153\145\x6e\40\162\145\x63\x65\x69\166\145\144\40\123\x75\x63\x63\x65\x73\163\146\x75\x6c\154\171");
            MO_Oauth_Debug::mo_oauth_log("\111\104\40\x54\x6f\153\x65\156\40\x3d\x3e\x20");
            MO_Oauth_Debug::mo_oauth_log($KH);
            $J6 = $this->get_resource_owner_from_app($KH, $fZ);
            MO_Oauth_Debug::mo_oauth_log("\x52\145\x73\157\x75\162\143\145\x20\x4f\x77\x6e\x65\162\x20\75\x3e\40");
            MO_Oauth_Debug::mo_oauth_log($J6);
            if (!(strpos($I9->get_app_config("\141\165\164\x68\x6f\x72\151\172\145\x75\x72\154"), "\x74\167\x69\164\x63\x68") !== false)) {
                goto de;
            }
            $EU = apply_filters("\155\x6f\137\x74\163\x6d\137\x67\x65\164\137\165\x73\x65\162\137\x73\165\142\x73\x63\x72\151\160\x74\x69\157\156", $J6["\x73\x75\x62"], $KY["\143\154\151\145\156\164\x5f\x69\x64"], $TD["\x61\143\143\145\163\163\x5f\164\157\153\x65\x6e"]);
            if (!(false !== $EU)) {
                goto g7;
            }
            MO_Oauth_Debug::mo_oauth_log("\124\167\151\x74\143\150\x20\123\165\142\163\143\x72\151\x70\164\151\157\x6e\40\x3d\x3e\x20");
            MO_Oauth_Debug::mo_oauth_log($EU);
            $J6["\163\x75\x62\163\143\162\151\x70\164\x69\x6f\156"] = $EU;
            g7:
            de:
            if (!($I9->get_app_config("\141\160\160\111\x64") === "\153\x65\x79\143\x6c\157\141\153")) {
                goto Y3;
            }
            $TB = apply_filters("\155\157\x5f\153\162\155\x5f\147\x65\x74\137\165\163\x65\162\x5f\162\157\154\x65\x73", $J6, $TD);
            if (!(false !== $TB)) {
                goto RL;
            }
            $J6["\162\157\x6c\145\x73"] = $TB;
            RL:
            Y3:
            $J6 = apply_filters("\x6d\157\x5f\141\172\165\x72\x65\x62\x32\x63\x5f\x67\x65\164\x5f\165\163\145\162\137\x67\x72\157\x75\x70\137\151\144\163", $J6, $KY);
            $Bs = apply_filters("\155\x6f\137\164\x72\137\x61\146\x74\145\x72\137\x70\x72\x6f\146\x69\154\145\137\151\x6e\146\x6f\x5f\x65\170\x74\162\141\143\x74\x69\157\x6e\x5f\x66\162\x6f\155\x5f\x74\x6f\153\x65\x6e", $J6);
            if (!($Bs != '' && is_array($Bs))) {
                goto Za;
            }
            $J6 = array_merge($J6, $Bs);
            Za:
            if (!(isset($KY["\x73\145\156\144\x5f\156\157\x6e\143\x65"]) && $KY["\x73\x65\x6e\144\x5f\x6e\157\x6e\143\x65"] === 1)) {
                goto PP;
            }
            if (!(isset($J6["\x6e\157\156\x63\x65"]) && $J6["\x6e\157\x6e\x63\145"] != NULL)) {
                goto Fq;
            }
            if ($Yh->get_transient("\155\x6f\x5f\157\141\165\164\150\137\x6e\157\156\x63\145\137" . $J6["\156\x6f\x6e\143\x65"])) {
                goto lV;
            }
            $N5 = "\x4e\x6f\156\143\x65\40\x76\145\162\x69\x66\x69\x63\x61\164\x69\157\x6e\x20\151\163\x20\x66\x61\151\x6c\x65\x64\56\x20\120\154\145\x61\163\145\x20\x63\x6f\x6e\164\x61\143\x74\40\x74\x6f\40\x79\157\x75\162\40\141\144\x6d\x69\x6e\151\163\x74\162\x61\164\157\162\56";
            $Yh->handle_error($N5);
            MO_Oauth_Debug::mo_oauth_log($N5);
            wp_die($N5);
            goto u3;
            lV:
            $Yh->delete_transient("\x6d\157\137\x6f\x61\165\164\150\x5f\x6e\x6f\x6e\143\x65\x5f" . $J6["\x6e\x6f\x6e\x63\145"]);
            u3:
            Fq:
            PP:
            $sU = [];
            $F2 = $this->dropdownattrmapping('', $J6, $sU);
            $Yh->mo_oauth_client_update_option("\155\157\137\x6f\141\x75\164\x68\137\x61\164\x74\x72\x5f\156\x61\x6d\x65\137\x6c\x69\163\x74" . $fZ, $F2);
            if (!($RV && '' !== $RV)) {
                goto FW;
            }
            $TD["\x72\145\x66\x72\x65\163\x68\x5f\164\x6f\153\x65\156"] = isset($TD["\162\x65\146\162\145\x73\150\137\164\157\x6b\145\156"]) ? $TD["\x72\x65\x66\162\145\x73\150\137\x74\157\153\x65\156"] : '';
            $_SESSION["\160\x72\x6f\143\x6f\162\145\x5f\x72\x65\x66\x72\145\163\150\137\x74\157\x6b\145\x6e"] = $TD["\162\x65\x66\162\x65\163\x68\137\164\x6f\153\x65\156"];
            $ek = isset($TD["\x61\x63\x63\145\163\x73\x5f\x74\x6f\153\x65\x6e"]) ? $TD["\x61\143\143\x65\163\x73\137\164\x6f\x6b\x65\x6e"] : '';
            $this->handle_group_test_conf($J6, $KY, $ek, false, $RV);
            MO_Oauth_Debug::mo_oauth_log("\101\164\x74\x72\151\142\x75\x74\x65\x20\x52\x65\x63\145\x69\x76\145\x64\x20\123\x75\x63\143\145\x73\x73\x66\165\x6c\154\x79");
            exit;
            FW:
            dZ:
            if (!(isset($KY["\147\162\x6f\165\160\144\x65\x74\x61\151\x6c\x73\x75\x72\x6c"]) && !empty($KY["\147\162\157\165\160\x64\x65\164\141\151\x6c\163\165\x72\154"]))) {
                goto y9;
            }
            $J6 = $this->handle_group_user_info($J6, $KY, $TD["\x61\143\143\145\163\x73\x5f\x74\x6f\153\145\x6e"]);
            MO_Oauth_Debug::mo_oauth_log("\x47\x72\157\x75\160\x20\104\x65\x74\141\x69\x6c\x73\40\117\142\164\141\x69\156\145\x64\x20\x3d\76\x20");
            MO_Oauth_Debug::mo_oauth_log($J6);
            y9:
            MO_Oauth_Debug::mo_oauth_log("\106\x65\x74\x63\150\145\x64\40\162\x65\x73\x6f\165\x72\143\145\x20\x6f\x77\156\x65\x72\x20\72\40" . json_encode($J6));
            if (!has_filter("\x77\x6f\x6f\143\x6f\x6d\155\x65\x72\x63\145\x5f\x63\150\145\143\x6b\x6f\x75\164\x5f\147\145\164\x5f\x76\141\154\165\x65")) {
                goto on;
            }
            $J6["\x61\160\160\156\x61\155\145"] = $fZ;
            on:
            do_action("\155\157\x5f\141\x62\162\x5f\x66\x69\154\164\x65\x72\137\154\x6f\x67\x69\156", $J6);
            $this->handle_sso($fZ, $KY, $J6, $GP, $TD);
        } catch (Exception $Wi) {
            $Yh->handle_error($Wi->getMessage());
            MO_Oauth_Debug::mo_oauth_log($Wi->getMessage());
            do_action("\155\157\x5f\162\x65\144\151\162\x65\143\164\137\164\x6f\137\143\165\x73\x74\x6f\155\137\x65\162\162\x6f\x72\x5f\x70\141\x67\145");
            exit(esc_html($Wi->getMessage()));
        }
        goto S8;
        wC:
        try {
            if (isset($_COOKIE["\x73\x74\141\x74\145\x5f\x70\141\162\x61\155"])) {
                goto Vf;
            }
            if (isset($_GET["\x73\x74\141\x74\145"])) {
                goto EQ;
            }
            $my = new StorageManager();
            if (!is_multisite()) {
                goto Qv;
            }
            $my->add_replace_entry("\x62\x6c\x6f\147\x5f\151\x64", 1);
            Qv:
            $p3 = $Yh->get_app_by_name();
            if (isset($_GET["\x61\160\x70\x5f\156\x61\155\145"])) {
                goto f8;
            }
            $my->add_replace_entry("\x61\x70\160\x6e\141\x6d\145", $p3->get_app_name());
            goto S2;
            f8:
            $my->add_replace_entry("\x61\160\160\156\141\155\145", sanitize_text_field(wp_unslash($_GET["\x61\x70\160\137\x6e\141\155\145"])));
            S2:
            $my->add_replace_entry("\164\x65\163\x74\137\143\x6f\156\146\x69\x67", false);
            $my->add_replace_entry("\162\145\144\151\x72\x65\x63\x74\x5f\165\162\x69", site_url());
            $GP = $my->get_state();
            goto o1;
            EQ:
            $GP = sanitize_text_field(wp_unslash($_GET["\x73\164\141\164\x65"]));
            o1:
            goto Bf;
            Vf:
            $GP = sanitize_text_field(wp_unslash($_COOKIE["\163\x74\x61\164\x65\x5f\160\x61\x72\x61\155"]));
            Bf:
            $eC = new StorageManager($GP);
            if (!empty($eC->get_value("\141\160\x70\x6e\x61\x6d\x65"))) {
                goto PF;
            }
            return;
            PF:
            $d9 = $eC->get_value("\141\x70\x70\x6e\141\155\145");
            $RV = $eC->get_value("\x74\145\x73\164\x5f\x63\157\156\x66\151\x67");
            $fZ = $d9 ? $d9 : '';
            $mc = $Yh->mo_oauth_client_get_option("\155\x6f\x5f\157\x61\x75\164\x68\x5f\141\160\x70\x73\x5f\x6c\151\163\164");
            $g5 = '';
            $YR = '';
            $I9 = $Yh->get_app_by_name($fZ);
            if ($I9) {
                goto tx;
            }
            $Yh->handle_error("\x41\160\x70\x6c\151\x63\x61\x74\x69\157\156\40\x6e\x6f\164\40\x63\x6f\156\146\151\x67\x75\x72\145\144\x2e");
            MO_Oauth_Debug::mo_oauth_log("\101\160\160\x6c\151\x63\x61\164\151\x6f\x6e\x20\156\x6f\164\40\143\157\156\x66\x69\x67\165\x72\145\x64\x2e");
            exit("\x41\x70\x70\x6c\x69\x63\141\164\151\157\156\x20\156\x6f\164\40\x63\157\x6e\146\151\147\165\x72\145\x64\56");
            tx:
            $KY = $I9->get_app_config();
            if (!(isset($KY["\163\145\156\x64\x5f\x6e\x6f\x6e\143\145"]) && $KY["\163\145\156\x64\137\156\x6f\156\143\145"] === 1)) {
                goto FF;
            }
            if (!(isset($_REQUEST["\x6e\x6f\156\x63\x65"]) && !$Yh->get_transient("\x6d\x6f\x5f\157\x61\x75\164\150\x5f\x6e\x6f\156\x63\145\137" . sanitize_text_field(wp_unslash($_REQUEST["\x6e\157\156\x63\x65"]))))) {
                goto sN;
            }
            $N5 = "\116\x6f\x6e\143\x65\40\166\x65\x72\151\x66\x69\143\141\164\x69\157\156\40\x69\163\40\x66\x61\x69\x6c\145\144\x2e\40\x50\154\145\141\x73\x65\40\143\157\x6e\x74\141\143\164\40\164\x6f\x20\x79\x6f\165\x72\40\141\x64\x6d\151\156\151\163\164\162\141\164\157\162\x2e";
            $Yh->handle_error($N5);
            MO_Oauth_Debug::mo_oauth_log($N5);
            wp_die(esc_html($N5));
            sN:
            FF:
            $uo = array("\147\x72\x61\156\164\x5f\164\171\160\145" => "\x72\x65\146\x72\145\x73\x68\x5f\164\157\x6b\x65\x6e", "\143\x6c\x69\x65\x6e\x74\x5f\151\144" => $KY["\x63\154\151\x65\x6e\164\137\x69\x64"], "\x72\x65\144\x69\162\145\x63\x74\137\165\162\x69" => $KY["\162\x65\144\151\x72\145\143\x74\x5f\165\x72\x69"], "\162\x65\146\162\145\x73\x68\137\x74\x6f\x6b\145\x6e" => $_POST["\x72\x65\146\x72\x65\163\150\x5f\x74\x6f\x6b\145\x6e"], "\163\x63\157\160\145" => $I9->get_app_config("\x73\143\x6f\160\x65"), "\x63\154\x69\145\x6e\x74\x5f\163\145\143\162\145\164" => $KY["\x63\154\151\x65\156\164\137\163\145\x63\162\145\164"]);
            $U_ = isset($KY["\163\x65\156\144\x5f\150\x65\x61\x64\145\162\x73"]) ? $KY["\x73\x65\x6e\144\x5f\x68\145\x61\144\145\x72\163"] : 0;
            $fn = isset($KY["\163\x65\156\144\x5f\142\x6f\144\171"]) ? $KY["\x73\145\156\144\x5f\x62\x6f\144\x79"] : 0;
            $pd = $KY["\141\143\x63\145\x73\x73\x74\157\153\x65\x6e\x75\162\x6c"];
            MO_Oauth_Debug::mo_oauth_log("\x4f\x41\165\164\150\40\x66\154\157\167");
            $TD = json_decode($this->oauth_handler->get_token($pd, $uo, $U_, $fn), true);
            $zD = isset($TD["\x61\x63\x63\x65\163\163\x5f\164\x6f\153\145\156"]) ? $TD["\x61\x63\143\x65\x73\163\x5f\164\157\x6b\x65\156"] : false;
            if (isset($zD)) {
                goto nL;
            }
            do_action("\x6d\157\x5f\162\x65\x64\151\x72\x65\x63\164\x5f\x74\x6f\x5f\143\x75\x73\x74\157\x6d\137\145\162\162\157\x72\137\x70\141\x67\x65");
            $Yh->handle_error("\x49\x6e\x76\x61\x6c\x69\144\x20\164\157\153\x65\156\40\x72\145\x63\x65\151\166\145\x64\56");
            MO_Oauth_Debug::mo_oauth_log("\111\x6e\166\x61\x6c\x69\144\x20\x74\157\x6b\x65\156\40\x72\145\143\x65\x69\x76\x65\x64\x2e");
            exit("\111\156\166\x61\x6c\151\144\x20\x74\x6f\153\x65\156\40\162\x65\143\x65\x69\x76\145\144\x2e");
            nL:
            MO_Oauth_Debug::mo_oauth_log("\x54\x6f\153\x65\156\x20\x52\145\x73\x70\157\156\x73\145\x20\x3d\x3e\x20");
            MO_Oauth_Debug::mo_oauth_log($TD);
            $pU = $KY["\x72\x65\163\157\165\x72\143\x65\157\x77\x6e\x65\x72\x64\145\164\x61\151\154\163\165\162\x6c"];
            if (!(substr($pU, -1) == "\x3d" && !empty($TD["\x75\x73\x65\162\x4e\141\x6d\145"]))) {
                goto LC;
            }
            $pU .= strtolower($TD["\x75\x73\x65\162\116\141\x6d\145"]);
            LC:
            MO_Oauth_Debug::mo_oauth_log("\101\x63\x63\x65\x73\163\40\x74\157\153\x65\156\x20\162\145\x63\x65\151\x76\145\144\x2e");
            MO_Oauth_Debug::mo_oauth_log("\101\x63\143\145\x73\x73\40\x54\x6f\153\145\x6e\40\75\x3e\x20");
            MO_Oauth_Debug::mo_oauth_log($zD);
            $J6 = false;
            if (!($J6 === false)) {
                goto l7;
            }
            $J6 = $this->oauth_handler->get_resource_owner($pU, $zD);
            l7:
            MO_Oauth_Debug::mo_oauth_log($J6);
            $sU = [];
            $F2 = $this->dropdownattrmapping('', $J6, $sU);
            $Yh->mo_oauth_client_update_option("\155\x6f\x5f\157\141\165\x74\x68\x5f\x61\164\x74\162\137\156\x61\155\145\x5f\154\151\163\x74" . $fZ, $F2);
            if (!($RV && '' !== $RV)) {
                goto hQ;
            }
            $this->handle_group_test_conf($J6, $KY, $zD, false, $RV);
            exit;
            hQ:
            if (!(isset($KY["\x67\x72\x6f\165\160\144\145\164\141\x69\x6c\x73\165\162\x6c"]) && !empty($KY["\x67\162\157\x75\160\x64\145\164\141\x69\x6c\x73\165\162\x6c"]))) {
                goto rR;
            }
            $J6 = $this->handle_group_user_info($J6, $KY, $zD);
            MO_Oauth_Debug::mo_oauth_log("\107\x72\x6f\x75\160\40\104\x65\164\141\x69\154\163\x20\117\142\x74\141\x69\x6e\x65\x64\40\75\x3e\x20" . $J6);
            rR:
            MO_Oauth_Debug::mo_oauth_log("\x46\x65\164\143\x68\x65\x64\40\162\x65\163\x6f\165\x72\143\x65\x20\157\167\x6e\x65\x72\40\72\x20" . json_encode($J6));
            if (!has_filter("\167\x6f\157\x63\x6f\x6d\x6d\145\162\143\145\x5f\143\150\145\143\x6b\157\x75\164\137\147\145\164\x5f\166\x61\x6c\165\x65")) {
                goto Us;
            }
            $J6["\x61\160\x70\156\141\x6d\145"] = $fZ;
            Us:
            do_action("\x6d\157\137\141\x62\162\137\x66\x69\154\x74\145\x72\x5f\x6c\157\147\151\156", $J6);
            $this->handle_sso($fZ, $KY, $J6, $GP, $TD);
        } catch (Exception $Wi) {
            $Yh->handle_error($Wi->getMessage());
            MO_Oauth_Debug::mo_oauth_log($Wi->getMessage());
            do_action("\x6d\x6f\x5f\x72\145\144\151\162\145\143\x74\137\164\157\x5f\143\x75\163\164\157\x6d\x5f\145\x72\x72\x6f\162\137\x70\141\x67\x65");
            exit(esc_html($Wi->getMessage()));
        }
        S8:
    }
    public function dropdownattrmapping($JJ, $PE, $sU)
    {
        global $Yh;
        foreach ($PE as $cW => $z_) {
            if (is_array($z_)) {
                goto lA;
            }
            if (!empty($JJ)) {
                goto Um;
            }
            array_push($sU, $cW);
            goto FA;
            Um:
            array_push($sU, $JJ . "\56" . $cW);
            FA:
            goto zB;
            lA:
            if (empty($JJ)) {
                goto A1;
            }
            $JJ .= "\56";
            A1:
            $sU = $this->dropdownattrmapping($JJ . $cW, $z_, $sU);
            $JJ = rtrim($JJ, "\x2e");
            zB:
            PO:
        }
        sG:
        return $sU;
    }
    public function resolve_and_get_oidc_response($TD = array())
    {
        if (!empty($TD)) {
            goto Wb;
        }
        throw new \Exception("\x54\157\153\x65\156\x20\x72\145\x73\x70\x6f\x6e\x73\145\x20\x69\x73\x20\145\x6d\160\164\x79", "\151\156\166\141\x6c\x69\144\x5f\162\145\163\160\157\156\163\145");
        Wb:
        global $Yh;
        $A2 = isset($TD["\x69\144\137\x74\x6f\x6b\145\x6e"]) ? $TD["\x69\144\x5f\x74\157\x6b\145\x6e"] : false;
        $C2 = isset($TD["\x61\143\x63\145\x73\x73\x5f\164\x6f\153\145\x6e"]) ? $TD["\141\x63\143\145\x73\x73\x5f\164\x6f\153\145\156"] : false;
        $_SESSION["\160\x72\157\x63\x6f\x72\x65\137\x61\143\143\145\x73\163\x5f\164\157\x6b\x65\x6e"] = isset($C2) ? $C2 : $A2;
        if (!$Yh->is_valid_jwt($A2)) {
            goto kY;
        }
        return $A2;
        kY:
        if (!$Yh->is_valid_jwt($C2)) {
            goto u2;
        }
        return $C2;
        u2:
        MO_Oauth_Debug::mo_oauth_log("\124\x6f\x6b\145\156\40\x69\x73\x20\x6e\157\164\x20\x61\x20\x76\x61\x6c\151\144\x20\x4a\127\x54\56");
        throw new \Exception("\124\x6f\x6b\145\156\x20\151\x73\40\156\x6f\x74\x20\141\x20\166\141\x6c\151\144\x20\112\127\124\x2e");
    }
    public function handle_group_test_conf($J6 = array(), $KY = array(), $C2 = '', $q_ = false, $RV = false)
    {
        $this->render_test_config_output($J6, false);
    }
    public function testattrmappingconfig($JJ, $PE)
    {
        foreach ($PE as $cW => $z_) {
            if (is_array($z_) || is_object($z_)) {
                goto CK;
            }
            echo "\x3c\164\162\x3e\74\x74\x64\x3e";
            if (empty($JJ)) {
                goto z0;
            }
            echo esc_attr($JJ) . "\x2e";
            z0:
            echo esc_attr($cW) . "\x3c\x2f\164\x64\76\x3c\x74\x64\x3e" . esc_attr($z_) . "\x3c\57\x74\144\x3e\74\57\164\162\76";
            goto YG;
            CK:
            if (empty($JJ)) {
                goto Tl;
            }
            $JJ .= "\x2e";
            Tl:
            $this->testattrmappingconfig($JJ . $cW, $z_);
            $JJ = rtrim($JJ, "\56");
            YG:
            sW:
        }
        WO:
    }
    public function render_test_config_output($J6, $q_ = false)
    {
        MO_Oauth_Debug::mo_oauth_log("\x54\x68\151\x73\x20\151\163\x20\164\x65\163\x74\40\143\157\156\x66\x69\x67\x75\162\141\x74\x69\x6f\156\40\x66\154\157\167\40\75\76\x20");
        echo "\x3c\x64\x69\166\40\x73\x74\171\154\x65\75\42\146\157\x6e\164\55\146\141\x6d\x69\154\171\72\x43\x61\154\x69\x62\162\x69\x3b\x70\x61\x64\x64\x69\156\x67\72\x30\40\63\45\73\x22\x3e";
        echo "\74\163\x74\171\x6c\145\76\164\141\142\154\145\x7b\x62\157\162\x64\145\x72\x2d\143\x6f\154\x6c\x61\x70\x73\145\x3a\143\157\154\154\x61\x70\x73\145\x3b\x7d\x74\x68\x20\x7b\142\141\x63\x6b\x67\162\x6f\165\156\x64\x2d\143\x6f\154\x6f\x72\x3a\x20\43\145\x65\145\73\40\164\x65\170\164\x2d\x61\x6c\x69\147\156\72\40\x63\145\x6e\x74\x65\162\73\x20\160\x61\x64\x64\x69\x6e\x67\72\40\70\x70\x78\x3b\40\142\x6f\x72\x64\145\162\x2d\x77\151\x64\164\150\x3a\61\160\x78\x3b\40\x62\x6f\x72\x64\145\x72\x2d\163\x74\171\154\x65\72\x73\157\154\x69\x64\x3b\x20\142\157\x72\x64\145\x72\x2d\x63\x6f\154\157\162\72\x23\x32\61\x32\x31\x32\x31\73\x7d\164\x72\x3a\x6e\x74\150\55\x63\150\151\154\144\x28\x6f\144\144\51\40\x7b\142\x61\x63\x6b\x67\162\x6f\165\x6e\144\x2d\x63\157\x6c\x6f\x72\72\40\43\x66\x32\x66\62\x66\62\73\x7d\40\164\144\173\160\141\144\x64\151\156\147\72\70\160\x78\73\142\x6f\x72\144\x65\x72\x2d\167\x69\x64\x74\x68\x3a\x31\160\x78\x3b\x20\142\x6f\162\x64\145\162\55\163\x74\x79\154\145\72\x73\x6f\154\151\144\x3b\40\142\x6f\x72\144\x65\x72\55\x63\x6f\154\157\162\72\43\x32\61\62\x31\x32\61\73\175\x3c\x2f\x73\x74\x79\154\x65\x3e";
        echo "\x3c\150\x32\76";
        echo $q_ ? "\107\x72\157\x75\160\x20\111\156\146\x6f" : "\124\145\x73\x74\40\x43\157\156\x66\x69\x67\x75\162\141\164\151\x6f\156";
        echo "\x3c\x2f\150\62\x3e\x3c\x74\x61\142\154\x65\x3e\74\x74\x72\76\74\x74\x68\76\101\164\x74\x72\x69\x62\165\164\145\40\x4e\141\x6d\145\74\x2f\164\150\76\74\164\x68\76\x41\164\164\162\x69\142\165\x74\x65\40\x56\141\x6c\x75\x65\74\57\x74\x68\76\x3c\x2f\x74\x72\76";
        $this->testattrmappingconfig('', $J6);
        echo "\x3c\57\x74\141\x62\x6c\x65\x3e";
        if ($q_) {
            goto qp;
        }
        echo "\74\x64\x69\x76\40\x73\164\x79\x6c\x65\75\42\160\x61\144\x64\x69\156\147\x3a\40\x31\x30\160\170\73\x22\x3e\74\x2f\144\151\x76\x3e\x3c\151\x6e\x70\165\x74\x20\163\x74\x79\x6c\x65\x3d\x22\x70\141\144\144\x69\x6e\147\72\61\45\x3b\x77\151\x64\x74\x68\x3a\61\60\x30\160\170\73\142\x61\143\x6b\x67\x72\157\165\x6e\x64\x3a\x20\43\60\x30\x39\61\103\104\x20\156\x6f\x6e\145\40\162\145\x70\x65\x61\x74\40\163\x63\162\x6f\x6c\x6c\40\x30\x25\40\60\45\73\x63\x75\162\x73\x6f\162\72\40\160\157\x69\x6e\x74\x65\162\73\x66\157\x6e\164\x2d\163\x69\172\x65\x3a\61\x35\x70\170\x3b\142\157\162\x64\145\x72\55\x77\151\x64\164\x68\x3a\40\x31\x70\x78\73\x62\x6f\162\144\x65\162\55\x73\164\171\x6c\x65\x3a\x20\163\x6f\154\151\144\73\x62\157\x72\144\145\x72\x2d\x72\x61\x64\151\x75\x73\x3a\x20\63\x70\x78\x3b\x77\x68\151\x74\x65\x2d\x73\x70\141\x63\x65\x3a\40\x6e\157\167\x72\141\x70\73\x62\x6f\170\55\x73\151\x7a\151\x6e\147\72\40\x62\157\x72\144\x65\x72\x2d\x62\x6f\x78\x3b\x62\x6f\162\144\145\162\x2d\143\x6f\x6c\x6f\x72\x3a\x20\x23\60\60\67\63\x41\x41\73\142\x6f\x78\55\x73\x68\141\x64\157\x77\72\40\x30\x70\x78\x20\61\x70\x78\x20\x30\160\170\40\x72\x67\142\141\50\x31\x32\x30\54\40\x32\x30\x30\x2c\40\62\63\x30\54\x20\x30\x2e\x36\51\40\x69\156\x73\x65\x74\x3b\143\157\x6c\157\162\72\x20\43\106\106\106\x3b\42\164\171\x70\145\75\42\142\165\164\x74\157\x6e\42\40\x76\141\154\x75\145\x3d\x22\104\157\x6e\x65\x22\40\x6f\156\x43\x6c\151\143\153\x3d\42\x73\145\154\146\x2e\143\154\157\x73\145\50\51\73\x22\76\74\57\144\x69\166\x3e";
        qp:
    }
    public function handle_sso($fZ, $KY, $J6, $GP, $TD, $lF = false)
    {
        MO_Oauth_Debug::mo_oauth_log("\123\123\117\x20\150\x61\x6e\144\154\151\156\x67\x20\x66\154\157\167");
        global $Yh;
        if (!(get_class($this) === "\x4d\157\117\x61\165\x74\150\103\x6c\151\x65\x6e\x74\x5c\114\x6f\x67\151\x6e\x48\141\156\x64\x6c\145\162" && $Yh->check_versi(1))) {
            goto tc;
        }
        $nQ = new InstanceHelper();
        $d_ = $nQ->get_login_handler_instance();
        $d_->handle_sso($fZ, $KY, $J6, $GP, $TD, $lF);
        tc:
        $g5 = isset($KY["\x6e\141\155\x65\x5f\x61\164\x74\x72"]) ? $KY["\x6e\141\155\x65\x5f\x61\x74\164\x72"] : '';
        $YR = isset($KY["\145\155\141\151\154\x5f\141\x74\164\162"]) ? $KY["\x65\x6d\141\151\154\137\141\164\164\x72"] : '';
        $Mv = $Yh->getnestedattribute($J6, $YR);
        $un = $Yh->getnestedattribute($J6, $g5);
        if (!empty($Mv)) {
            goto YO;
        }
        MO_Oauth_Debug::mo_oauth_log("\x45\155\x61\151\x6c\40\141\144\x64\162\x65\x73\163\x20\156\x6f\164\40\x72\x65\x63\145\x69\x76\x65\144\56\40\x43\150\x65\x63\153\x20\x79\x6f\x75\162\x20\101\x74\164\162\x69\142\x75\x74\x65\40\115\x61\160\160\151\x6e\147\x20\143\157\x6e\146\x69\147\165\162\141\164\151\x6f\x6e\x2e");
        $Yh->handle_error("\x45\x6d\141\151\154\x20\141\144\144\162\x65\x73\x73\x20\156\x6f\x74\40\x72\145\143\145\151\x76\145\144\x2e\x20\x43\150\x65\143\x6b\40\x79\157\x75\162\x20\74\x73\164\162\x6f\156\147\x3e\x41\164\164\x72\x69\142\165\x74\145\x20\x4d\141\x70\160\x69\x6e\147\74\x2f\x73\x74\x72\157\156\147\x3e\40\x63\x6f\x6e\x66\151\147\x75\x72\x61\x74\151\157\156\56");
        wp_die("\x45\x6d\141\x69\154\40\x61\144\144\x72\145\x73\x73\40\x6e\157\164\40\x72\145\x63\x65\x69\166\145\x64\x2e\40\103\x68\x65\x63\153\x20\x79\157\165\162\x20\x3c\163\164\x72\157\156\147\76\x41\164\164\162\x69\x62\x75\164\x65\x20\115\x61\160\x70\151\156\x67\74\x2f\x73\x74\162\157\156\x67\76\40\x63\157\156\x66\x69\x67\x75\x72\x61\x74\x69\157\156\x2e");
        YO:
        if (!(false === strpos($Mv, "\x40"))) {
            goto cf;
        }
        MO_Oauth_Debug::mo_oauth_log("\x4d\141\x70\x70\x65\x64\x20\105\x6d\x61\x69\154\x20\x61\164\164\x72\x69\x62\x75\x74\145\x20\144\157\145\x73\40\x6e\157\x74\40\x63\157\156\x74\x61\151\x6e\x20\166\141\x6c\x69\144\x20\145\155\x61\151\154\x2e");
        $Yh->handle_error("\115\141\160\160\x65\x64\x20\105\155\141\x69\154\x20\141\x74\x74\162\x69\x62\165\164\145\40\144\157\x65\163\x20\x6e\157\x74\40\x63\157\156\164\x61\x69\156\40\x76\141\x6c\x69\x64\40\145\x6d\141\x69\x6c\x2e");
        wp_die("\x4d\x61\x70\160\145\144\40\105\155\x61\x69\154\x20\x61\164\164\x72\151\x62\165\164\x65\40\144\157\145\163\40\x6e\x6f\x74\x20\x63\x6f\x6e\164\141\x69\156\x20\x76\141\x6c\x69\144\40\x65\155\141\151\154\x2e");
        cf:
        $user = get_user_by("\154\x6f\147\x69\x6e", $Mv);
        if ($user) {
            goto Kx;
        }
        $user = get_user_by("\145\x6d\141\x69\154", $Mv);
        Kx:
        if ($user) {
            goto JC;
        }
        $QH = 0;
        if ($Yh->mo_oauth_hbca_xyake()) {
            goto Kc;
        }
        $user = $Yh->mo_oauth_hjsguh_kiishuyauh878gs($Mv, $un);
        goto Qf;
        Kc:
        if ($Yh->mo_oauth_client_get_option("\x6d\157\137\157\x61\x75\x74\150\137\x66\154\141\147") !== true) {
            goto k3;
        }
        $Al = base64_decode('PGRpdiBzdHlsZT0ndGV4dC1hbGlnbjpjZW50ZXI7Jz48Yj5Vc2VyIEFjY291bnQgZG9lcyBub3QgZXhpc3QuPC9iPjwvZGl2Pjxicj48c21hbGw+VGhpcyB2ZXJzaW9uIHN1cHBvcnRzIEF1dG8gQ3JlYXRlIFVzZXIgZmVhdHVyZSB1cHRvIDEwIFVzZXJzLiBQbGVhc2UgdXBncmFkZSB0byB0aGUgaGlnaGVyIHZlcnNpb24gb2YgdGhlIHBsdWdpbiB0byBlbmFibGUgYXV0byBjcmVhdGUgdXNlciBmb3IgdW5saW1pdGVkIHVzZXJzIG9yIGFkZCB1c2VyIG1hbnVhbGx5Ljwvc21hbGw+');
        $Yh->handle_error($Al);
        MO_Oauth_Debug::mo_oauth_log($Al);
        wp_die($Al);
        goto f6;
        k3:
        $user = $Yh->mo_oauth_jhuyn_jgsukaj($Mv, $un);
        f6:
        Qf:
        goto bs;
        JC:
        $QH = $user->ID;
        bs:
        if (!$user) {
            goto oP;
        }
        wp_set_current_user($user->ID);
        MO_Oauth_Debug::mo_oauth_log("\125\163\145\x72\40\106\x6f\165\x6e\x64");
        $Et = false;
        $Et = apply_filters("\155\157\137\162\145\155\x65\155\x62\x65\x72\137\155\x65", $Et);
        if (!$Et) {
            goto Cy;
        }
        MO_Oauth_Debug::mo_oauth_log("\122\145\x6d\145\x6d\x62\145\x72\40\x41\144\144\x6f\x6e\x20\x61\143\x74\x69\x76\141\164\145\x64");
        Cy:
        wp_set_auth_cookie($user->ID, $Et);
        MO_Oauth_Debug::mo_oauth_log("\x55\x73\145\162\40\143\157\157\x6b\x69\x65\40\x73\x65\x74");
        $user = get_user_by("\x49\x44", $user->ID);
        do_action("\x77\x70\137\154\157\147\151\156", $user->user_login, $user);
        wp_safe_redirect(home_url());
        MO_Oauth_Debug::mo_oauth_log("\x55\163\x65\x72\40\122\145\144\151\162\145\143\x74\x65\144\x20\164\157\x20\x68\157\x6d\x65\40\165\x72\154");
        exit;
        oP:
    }
    public function get_resource_owner_from_app($A2, $zl)
    {
        return $this->oauth_handler->get_resource_owner_from_id_token($A2);
    }
}
