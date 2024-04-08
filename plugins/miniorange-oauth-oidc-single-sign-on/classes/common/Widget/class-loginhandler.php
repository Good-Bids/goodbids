<?php


namespace MoOauthClient;

use MoOauthClient\Base\InstanceHelper;
use MoOauthClient\OauthHandler;
use MoOauthClient\StorageManager;
use MoOauthClient\MO_Custom_OAuth1;
class LoginHandler
{
    public $oauth_handler;
    public function __construct()
    {
        $this->oauth_handler = new OauthHandler();
        add_action("\x69\156\151\164", array($this, "\155\157\137\x6f\x61\x75\164\x68\x5f\x64\x65\x63\151\144\145\x5f\x66\154\157\167"));
        add_action("\x6d\x6f\x5f\x6f\141\165\x74\150\137\143\x6c\x69\x65\156\164\137\x74\151\x67\150\164\137\x6c\x6f\x67\151\156\x5f\151\x6e\164\x65\162\x6e\x61\x6c", array($this, "\150\141\x6e\x64\154\145\137\x73\x73\x6f"), 10, 4);
    }
    public function mo_oauth_decide_flow()
    {
        global $Uj;
        if (!(isset($_REQUEST[\MoOAuthConstants::OPTION]) && "\164\145\163\x74\141\164\x74\x72\155\141\160\160\x69\156\x67\143\157\156\x66\151\x67" === $_REQUEST[\MoOAuthConstants::OPTION])) {
            goto Kx;
        }
        $tv = $_REQUEST["\141\160\x70"];
        wp_safe_redirect(site_url() . "\77\x6f\x70\x74\151\x6f\156\75\157\x61\x75\x74\x68\x72\145\x64\x69\x72\x65\x63\164\46\141\160\x70\x5f\x6e\x61\155\145\x3d" . rawurlencode($tv) . "\46\164\145\x73\164\75\x74\x72\x75\145");
        exit;
        Kx:
        $this->mo_oauth_login_validate();
    }
    public function mo_oauth_login_validate()
    {
        global $Uj;
        $cm = new StorageManager();
        if (!(isset($_REQUEST[\MoOAuthConstants::OPTION]) and strpos($_REQUEST[\MoOAuthConstants::OPTION], "\x6f\x61\x75\x74\x68\x72\x65\144\151\x72\x65\x63\x74") !== false)) {
            goto xN;
        }
        if (isset($_REQUEST["\155\157\x5f\x6c\157\147\x69\x6e\x5f\x70\157\160\x75\160"])) {
            goto Mk;
        }
        if (!(isset($_REQUEST["\x72\145\163\x6f\x75\x72\143\145"]) && !empty($_REQUEST["\162\145\x73\x6f\x75\x72\143\145"]))) {
            goto zG;
        }
        if (!empty($_REQUEST["\x72\145\163\157\x75\162\143\145"])) {
            goto xt;
        }
        $Uj->handle_error("\124\150\x65\40\162\x65\x73\x70\x6f\156\x73\145\40\x66\162\x6f\x6d\40\165\163\145\x72\x69\156\146\157\x20\x77\141\x73\x20\x65\155\x70\164\x79\x2e");
        MO_Oauth_Debug::mo_oauth_log("\124\x68\145\40\x72\x65\163\160\x6f\x6e\x73\145\40\146\x72\x6f\x6d\40\x75\163\145\x72\151\156\146\157\40\167\x61\163\x20\x65\x6d\160\x74\x79\x2e");
        wp_die(wp_kses("\x54\x68\145\40\162\145\x73\160\x6f\x6e\163\145\x20\x66\162\x6f\x6d\40\x75\x73\x65\x72\x69\156\146\x6f\x20\167\141\163\x20\145\155\x70\x74\171\x2e", \mo_oauth_get_valid_html()));
        xt:
        $cm = new StorageManager(urldecode($_REQUEST["\162\145\x73\157\165\x72\x63\x65"]));
        $Qu = $cm->get_value("\162\x65\163\157\x75\x72\x63\x65");
        $Cm = $cm->get_value("\x61\x70\160\x6e\141\x6d\x65");
        $WG = $cm->get_value("\162\145\144\x69\162\x65\143\164\x5f\x75\162\151");
        $AP = $cm->get_value("\x61\x63\x63\145\x73\163\137\164\157\x6b\145\x6e");
        $Wh = $Uj->get_app_by_name($Cm)->get_app_config();
        $ji = isset($_REQUEST["\x74\145\163\164"]) && !empty($_REQUEST["\x74\145\163\x74"]);
        if (!($ji && '' !== $ji)) {
            goto vw;
        }
        $this->handle_group_test_conf($Qu, $Wh, $AP, false, $ji);
        exit;
        vw:
        $cm->remove_key("\x72\145\x73\x6f\x75\162\143\x65");
        $cm->add_replace_entry("\160\x6f\x70\x75\x70", "\x69\x67\x6e\157\x72\145");
        if (!has_filter("\167\157\157\x63\x6f\x6d\x6d\x65\x72\143\145\137\x63\150\145\x63\x6b\x6f\165\164\137\147\x65\164\x5f\x76\141\x6c\165\145")) {
            goto Nc;
        }
        $Qu["\x61\160\x70\x6e\x61\x6d\x65"] = $Cm;
        Nc:
        do_action("\155\157\137\141\142\x72\x5f\x66\x69\154\x74\145\162\x5f\154\157\147\151\x6e", $Qu);
        $ie = time();
        if ($ie < 1713484774) {
            goto TK;
        }
        exit("\x74\162\x69\x61\x6c\x20\160\x65\x72\x69\x6f\x64\40\x65\170\160\x69\162\145\x64\x2e");
        goto qy;
        TK:
        $this->handle_sso($Cm, $Wh, $Qu, $cm->get_state(), ["\x61\143\143\145\163\163\x5f\164\x6f\153\x65\156" => $AP]);
        qy:
        zG:
        if (isset($_REQUEST["\141\x70\x70\x5f\156\x61\x6d\145"])) {
            goto ya;
        }
        $eZ = "\120\154\x65\x61\163\x65\40\143\150\x65\x63\153\40\x69\146\40\171\157\165\40\x61\x72\145\40\163\145\x6e\144\151\156\x67\40\164\150\x65\40\47\x61\x70\160\x5f\156\x61\155\145\x27\x20\x70\x61\x72\x61\x6d\145\x74\x65\x72";
        $Uj->handle_error($eZ);
        wp_die(wp_kses($eZ, \mo_oauth_get_valid_html()));
        exit;
        ya:
        $BW = isset($_REQUEST["\x61\x70\x70\137\156\x61\155\145"]) && !empty($_REQUEST["\141\160\160\x5f\156\x61\155\145"]) ? $_REQUEST["\141\160\160\x5f\156\141\x6d\145"] : '';
        if (!($BW == '')) {
            goto i7;
        }
        $eZ = "\x4e\x6f\40\x73\x75\x63\150\40\141\160\x70\40\146\x6f\165\156\x64\40\x63\157\156\x66\151\147\165\162\x65\x64\x2e\40\120\154\145\x61\x73\145\40\143\150\x65\x63\153\x20\151\146\40\x79\157\165\x20\x61\162\x65\x20\x73\x65\156\144\151\x6e\x67\40\164\x68\145\40\143\x6f\x72\x72\x65\143\x74\x20\x61\x70\x70\x5f\156\x61\x6d\145";
        MO_Oauth_Debug::mo_oauth_log($eZ);
        $Uj->handle_error($eZ);
        wp_die(wp_kses($eZ, \mo_oauth_get_valid_html()));
        exit;
        i7:
        $H5 = $Uj->mo_oauth_client_get_option("\155\x6f\x5f\157\141\165\164\x68\x5f\141\x70\x70\x73\137\x6c\151\x73\x74");
        if (is_array($H5) && isset($H5[$BW])) {
            goto Xz;
        }
        $eZ = "\x4e\157\x20\x73\x75\143\x68\x20\x61\160\160\x20\x66\157\x75\156\144\x20\x63\x6f\x6e\x66\x69\x67\165\x72\x65\144\x2e\x20\x50\x6c\x65\x61\163\145\x20\x63\150\x65\x63\153\40\x69\x66\40\x79\x6f\165\x20\141\x72\x65\x20\x73\x65\x6e\144\x69\156\x67\x20\164\x68\x65\40\x63\x6f\x72\x72\x65\143\164\40\x61\x70\x70\137\x6e\141\x6d\x65";
        MO_Oauth_Debug::mo_oauth_log($eZ);
        $Uj->handle_error($eZ);
        wp_die(wp_kses($eZ, \mo_oauth_get_valid_html()));
        exit;
        Xz:
        $w2 = "\x2f\57" . $_SERVER["\110\x54\x54\120\x5f\x48\x4f\x53\124"] . $_SERVER["\x52\x45\x51\x55\x45\123\x54\x5f\125\122\x49"];
        $w2 = strtok($w2, "\x3f");
        $Ol = isset($_REQUEST["\x72\x65\x64\x69\x72\x65\143\164\137\x75\162\x6c"]) ? urldecode($_REQUEST["\x72\x65\144\151\x72\145\x63\x74\137\165\x72\x6c"]) : $w2;
        $ji = isset($_REQUEST["\164\145\163\164"]) ? urldecode($_REQUEST["\x74\x65\x73\x74"]) : false;
        $E5 = isset($_REQUEST["\162\x65\163\164\x72\151\143\164\162\x65\x64\151\x72\145\x63\x74"]) ? urldecode($_REQUEST["\162\x65\163\x74\162\x69\143\x74\x72\145\x64\x69\162\145\143\164"]) : false;
        $Fr = $Uj->get_app_by_name($BW);
        $bE = $Fr->get_app_config("\147\x72\x61\156\164\x5f\x74\171\160\145");
        if (!is_multisite()) {
            goto eV;
        }
        $blog_id = get_current_blog_id();
        $lI = $Uj->mo_oauth_client_get_option("\x6d\157\x5f\157\141\x75\164\150\x5f\143\x33\126\151\143\62\x6c\60\x5a\130\x4e\172\132\x57\170\154\131\x33\122\x6c\x5a\x41");
        $RW = array();
        if (!isset($lI)) {
            goto Yr;
        }
        $RW = json_decode($Uj->mooauthdecrypt($lI), true);
        Yr:
        $j4 = false;
        $kl = $Uj->mo_oauth_client_get_option("\155\157\137\x6f\x61\x75\x74\150\137\x69\163\x4d\165\154\164\151\123\x69\x74\x65\x50\x6c\165\x67\x69\x6e\x52\x65\x71\x75\x65\163\x74\145\x64");
        if (!(is_array($RW) && in_array($blog_id, $RW))) {
            goto bI;
        }
        $j4 = true;
        bI:
        if (!(is_multisite() && $kl && ($kl && !$j4) && !$ji && $Uj->mo_oauth_client_get_option("\x6e\157\x4f\146\x53\x75\142\x53\151\x74\145\x73") < 1000)) {
            goto f2;
        }
        $Uj->handle_error("\x4c\x6f\x67\151\156\40\151\x73\40\144\x69\163\141\142\154\145\x64\x20\x66\157\x72\40\164\x68\x69\163\x20\163\151\x74\x65\x2e\x20\120\x6c\x65\141\x73\x65\x20\x63\x6f\x6e\x74\141\x63\x74\40\x79\157\165\x72\x20\141\x64\x6d\151\x6e\x69\163\164\162\141\164\157\x72\x2e");
        MO_Oauth_Debug::mo_oauth_log("\114\157\147\151\x6e\40\151\x73\40\x64\x69\x73\x61\142\154\x65\144\x20\146\157\x72\40\164\x68\151\163\40\163\x69\x74\x65\x2e\x20\120\x6c\x65\141\163\x65\40\x63\157\x6e\164\141\x63\x74\x20\x79\x6f\x75\x72\x20\141\144\155\x69\x6e\151\x73\164\x72\141\x74\157\x72\x2e");
        wp_die("\114\157\147\x69\156\40\151\x73\40\x64\x69\x73\x61\x62\x6c\145\144\40\146\157\x72\40\x74\x68\151\x73\x20\163\x69\164\x65\x2e\x20\120\x6c\145\141\x73\145\x20\x63\157\x6e\x74\x61\x63\x74\40\x79\157\165\x72\x20\141\144\155\151\156\x69\x73\164\162\x61\x74\157\162\x2e");
        f2:
        $cm->add_replace_entry("\x62\x6c\x6f\147\137\x69\144", $blog_id);
        eV:
        MO_Oauth_Debug::mo_oauth_log("\107\162\141\x6e\164\x3a\x20" . $bE);
        if ($bE && "\x50\141\x73\163\167\x6f\162\144\x20\107\162\141\156\164" === $bE) {
            goto jn;
        }
        if (!($bE && "\x43\x6c\x69\145\156\164\40\103\x72\145\x64\x65\156\164\x69\141\x6c\x73\x20\107\162\141\x6e\164" === $bE)) {
            goto k3;
        }
        do_action("\155\157\x5f\x6f\x61\165\164\150\x5f\143\x6c\151\x65\x6e\x74\x5f\x63\x72\x65\x64\145\x6e\164\151\x61\154\x73\x5f\147\162\141\156\x74\x5f\x69\156\151\164\x69\141\164\x65", $BW, $ji);
        exit;
        k3:
        goto VR;
        jn:
        do_action("\x70\167\144\x5f\145\163\x73\145\156\164\151\x61\154\x73\x5f\151\x6e\164\x65\162\x6e\141\154");
        do_action("\155\x6f\137\157\x61\x75\164\x68\x5f\x63\154\151\145\156\x74\x5f\x61\144\x64\x5f\x70\x77\144\137\152\163");
        echo "\11\x9\x9\11\x3c\x73\x63\162\x69\x70\164\x3e\xd\xa\x9\11\x9\11\x9\x76\141\x72\40\155\x6f\x5f\x6f\141\x75\164\x68\137\141\x70\160\x5f\156\x61\x6d\x65\40\75\x20\x22";
        echo wp_kses($BW, \mo_oauth_get_valid_html());
        echo "\42\x3b\15\xa\11\11\x9\x9\11\x64\157\143\165\x6d\x65\x6e\164\56\141\144\144\x45\166\x65\156\164\x4c\151\x73\x74\x65\156\145\x72\x28\47\x44\117\x4d\103\x6f\x6e\164\x65\156\x74\114\x6f\141\x64\x65\x64\47\x2c\40\x66\165\x6e\x63\164\151\157\156\50\x29\40\x7b\15\12\x9\x9\11\x9\11\11";
        if ($ji) {
            goto lE;
        }
        echo "\x9\11\x9\11\x9\11\x9\x6d\157\117\101\165\x74\x68\114\157\147\x69\x6e\120\167\x64\x28\155\157\x5f\x6f\141\165\164\150\137\141\160\160\137\156\141\155\x65\54\x20\x66\x61\154\x73\145\54\x20\x27";
        echo $Ol;
        echo "\x27\x29\73\15\12\11\x9\x9\11\x9\x9";
        goto Zn;
        lE:
        echo "\x9\11\x9\x9\11\11\x9\x6d\x6f\x4f\x41\x75\164\150\114\157\147\x69\x6e\120\x77\x64\50\x6d\157\137\157\141\165\164\150\x5f\141\x70\160\137\156\x61\x6d\x65\x2c\40\164\x72\x75\x65\x2c\40\x27";
        echo $Ol;
        echo "\47\51\x3b\15\xa\x9\x9\11\x9\11\x9";
        Zn:
        echo "\x9\11\x9\11\x9\x7d\54\x20\146\141\x6c\x73\145\x29\x3b\15\12\11\x9\11\x9\x3c\57\163\143\162\151\x70\x74\76\15\12\x9\x9\11\x9";
        exit;
        VR:
        if (!($Fr->get_app_config("\141\160\x70\x49\144") === "\x74\167\151\164\164\x65\162" || $Fr->get_app_config("\141\x70\x70\111\x64") === "\157\x61\165\164\150\x31")) {
            goto tV;
        }
        MO_Oauth_Debug::mo_oauth_log("\117\141\165\x74\150\x31\40\146\154\157\x77");
        $ji = isset($_REQUEST["\x74\145\163\164"]) && !empty($_REQUEST["\x74\x65\x73\164"]);
        if (!($ji && '' !== $ji)) {
            goto lf;
        }
        setcookie("\157\141\x75\164\150\x31\x5f\x74\x65\x73\x74", "\61", time() + 20);
        lf:
        setcookie("\x6f\x61\165\x74\x68\x31\141\x70\x70\x6e\141\x6d\145", $BW, time() + 60);
        $_COOKIE["\x6f\141\165\x74\x68\x31\x61\x70\x70\x6e\x61\155\x65"] = $BW;
        MO_Custom_OAuth1::mo_oauth1_auth_request($BW);
        exit;
        tV:
        $XN = md5(rand(0, 15));
        $cm->add_replace_entry("\141\160\160\156\141\155\145", $BW);
        $cm->add_replace_entry("\162\145\144\x69\162\x65\x63\x74\x5f\165\x72\151", $Ol);
        $cm->add_replace_entry("\x74\145\163\x74\x5f\143\157\156\146\x69\x67", $ji);
        $cm->add_replace_entry("\162\145\163\x74\162\151\x63\164\162\x65\144\151\x72\x65\143\164", $E5);
        $cm->add_replace_entry("\163\x74\141\164\x65\137\156\157\x6e\143\145", $XN);
        $cm = apply_filters("\155\x6f\x5f\x6f\141\x75\164\x68\137\163\145\x74\137\x63\x75\x73\x74\x6f\x6d\137\163\164\157\x72\141\x67\145", $cm);
        $Zi = $cm->get_state();
        $Zi = apply_filters("\x73\164\x61\164\x65\x5f\x69\x6e\x74\145\162\156\x61\154", $Zi);
        $NF = $Fr->get_app_config("\x61\x75\x74\150\157\162\151\x7a\145\165\x72\154");
        if (!($Fr->get_app_config("\x73\x65\x6e\144\137\163\x74\141\x74\x65") === false || $Fr->get_app_config("\163\145\156\144\137\163\164\x61\x74\145") === '')) {
            goto oW;
        }
        $Fr->update_app_config("\163\145\x6e\x64\x5f\x73\x74\x61\x74\145", 1);
        $Uj->set_app_by_name($BW, $Fr->get_app_config('', false));
        oW:
        if ($Fr->get_app_config("\163\145\156\x64\x5f\163\164\x61\x74\145")) {
            goto iQ;
        }
        setcookie("\x73\164\x61\164\145\x5f\160\141\162\141\155", $Zi, time() + 60);
        iQ:
        $um = $Fr->get_app_config("\x70\x6b\x63\145\x5f\x66\154\157\167");
        $WG = $Fr->get_app_config("\x72\x65\x64\x69\x72\x65\143\x74\137\165\x72\x69");
        $w0 = urlencode($Fr->get_app_config("\x63\154\x69\x65\x6e\x74\x5f\151\x64"));
        $WG = empty($WG) ? \site_url() : $WG;
        if ($um && 1 === $um) {
            goto UO;
        }
        $KV = $Fr->get_app_config("\163\145\x6e\144\137\163\164\x61\x74\x65") ? "\46\163\164\141\x74\145\75" . $Zi : '';
        if ($Fr->get_app_config("\x73\145\156\144\x5f\163\164\x61\x74\x65")) {
            goto qm;
        }
        setcookie("\163\x74\x61\x74\x65\x5f\160\x61\x72\141\155", $Zi, time() + 60);
        MO_Oauth_Debug::mo_oauth_log("\163\x74\x61\164\145\x20\x70\x61\162\x61\155\x65\x74\x65\x72\40\156\157\x74\40\163\145\x6e\164");
        goto pj;
        qm:
        MO_Oauth_Debug::mo_oauth_log("\163\x74\141\x74\145\40\160\x61\x72\141\155\145\x74\145\x72\40\x73\145\156\164");
        pj:
        if (strpos($NF, "\x3f") !== false) {
            goto vx;
        }
        $NF = $NF . "\x3f\143\x6c\151\145\x6e\x74\x5f\151\144\75" . $w0 . "\46\x73\143\x6f\x70\x65\x3d" . $Fr->get_app_config("\163\x63\x6f\x70\145") . "\x26\x72\145\144\151\162\145\143\164\137\x75\162\151\x3d" . urlencode($WG) . "\46\162\x65\x73\x70\157\156\x73\145\x5f\x74\x79\160\x65\x3d\143\x6f\x64\145" . $KV;
        goto zf;
        vx:
        $NF = $NF . "\46\143\x6c\151\x65\x6e\164\137\x69\x64\x3d" . $w0 . "\x26\x73\x63\x6f\160\x65\x3d" . $Fr->get_app_config("\163\143\x6f\160\x65") . "\46\162\x65\144\x69\x72\145\x63\x74\x5f\165\x72\x69\x3d" . urlencode($WG) . "\x26\162\x65\163\x70\x6f\x6e\163\x65\x5f\x74\171\x70\x65\x3d\143\157\x64\145" . $KV;
        zf:
        goto Uh;
        UO:
        MO_Oauth_Debug::mo_oauth_log("\x50\x4b\x43\105\40\x66\154\157\167");
        $SJ = bin2hex(openssl_random_pseudo_bytes(32));
        $Jd = $Uj->base64url_encode(pack("\110\52", $SJ));
        $mO = $Uj->base64url_encode(pack("\x48\52", hash("\163\x68\x61\x32\x35\x36", $Jd)));
        $cm->add_replace_entry("\143\x6f\144\x65\137\166\x65\x72\151\x66\151\145\x72", $Jd);
        $Zi = $cm->get_state();
        $KV = $Fr->get_app_config("\x73\145\x6e\144\137\163\x74\141\x74\x65") ? "\x26\x73\164\141\x74\145\x3d" . $Zi : '';
        if ($Fr->get_app_config("\x73\x65\156\144\x5f\163\164\141\164\145")) {
            goto VF;
        }
        MO_Oauth_Debug::mo_oauth_log("\163\164\141\164\145\40\160\x61\162\141\x6d\145\x74\x65\162\40\156\x6f\164\40\163\145\156\x74");
        goto bW;
        VF:
        MO_Oauth_Debug::mo_oauth_log("\163\164\x61\164\x65\x20\x70\x61\162\141\x6d\145\164\x65\x72\40\x73\x65\x6e\164");
        bW:
        if (strpos($NF, "\x3f") !== false) {
            goto Gr;
        }
        $NF = $NF . "\x3f\143\154\x69\x65\156\164\137\x69\x64\x3d" . $w0 . "\x26\x73\143\x6f\160\145\x3d" . $Fr->get_app_config("\163\x63\157\x70\145") . "\46\x72\145\x64\151\162\145\143\164\x5f\x75\x72\151\75" . urlencode($WG) . "\46\162\145\163\160\x6f\156\x73\145\137\164\x79\x70\x65\x3d\143\157\144\x65" . $KV . "\x26\x63\x6f\144\x65\137\x63\x68\x61\154\x6c\145\x6e\147\x65\x3d" . $mO . "\x26\143\157\144\x65\x5f\143\150\x61\154\154\145\156\x67\145\x5f\x6d\145\x74\x68\157\144\75\x53\62\65\66";
        goto o7;
        Gr:
        $NF = $NF . "\x26\x63\x6c\x69\145\x6e\x74\x5f\x69\x64\x3d" . $w0 . "\x26\163\x63\x6f\x70\145\x3d" . $Fr->get_app_config("\x73\143\157\160\x65") . "\46\162\x65\144\151\162\145\143\x74\137\x75\162\x69\75" . urlencode($WG) . "\x26\x72\x65\163\160\x6f\x6e\x73\x65\x5f\164\171\160\x65\x3d\x63\x6f\x64\x65" . $KV . "\x26\x63\157\x64\145\x5f\143\150\x61\154\154\145\156\147\x65\75" . $mO . "\x26\143\x6f\144\145\x5f\143\150\x61\154\x6c\145\156\x67\x65\137\x6d\145\x74\150\157\x64\75\123\62\65\66";
        o7:
        Uh:
        if (!(null !== $Fr->get_app_config("\x73\145\156\x64\x5f\x6e\157\156\143\x65") && $Fr->get_app_config("\163\x65\156\144\x5f\156\x6f\x6e\x63\x65"))) {
            goto Py;
        }
        $eL = md5(rand());
        $Uj->set_transient("\x6d\157\x5f\x6f\141\x75\164\150\x5f\156\x6f\x6e\x63\145\137" . $eL, $eL, time() + 120);
        $NF = $NF . "\x26\x6e\157\x6e\x63\x65\75" . $eL;
        MO_Oauth_Debug::mo_oauth_log("\x6e\x6f\156\x63\145\x20\160\141\x72\141\155\x65\x74\x65\162\x20\x73\145\156\164");
        Py:
        if (!(strpos($NF, "\141\160\x70\x6c\145") !== false)) {
            goto ui;
        }
        $NF = $NF . "\x26\x72\x65\163\160\x6f\x6e\163\x65\137\155\157\144\x65\75\x66\x6f\x72\x6d\137\160\157\x73\164";
        ui:
        $ie = time();
        if ($ie < 1713484774) {
            goto Pm;
        }
        exit("\x74\162\151\x61\154\40\160\145\x72\151\157\144\40\x65\x78\160\151\162\x65\144\56");
        goto na;
        Pm:
        $NF = apply_filters("\x6d\157\137\x61\x75\164\x68\137\x75\162\154\x5f\151\x6e\164\145\162\156\141\154", $NF, $BW);
        MO_Oauth_Debug::mo_oauth_log("\x41\165\x74\x68\157\x72\151\x7a\x61\151\157\x6e\x20\105\156\144\160\157\151\156\164\x20\x3d\76\x20" . $NF);
        header("\x4c\157\143\141\164\151\157\x6e\72\40" . $NF);
        na:
        exit;
        Mk:
        xN:
        if (isset($_GET["\x65\x72\x72\157\x72\137\x64\x65\x73\143\x72\x69\160\164\x69\157\x6e"])) {
            goto Fu;
        }
        if (!isset($_GET["\x65\162\162\157\162"])) {
            goto gi;
        }
        do_action("\x6d\157\x5f\162\x65\x64\x69\x72\145\143\x74\137\x74\x6f\137\x63\x75\163\x74\157\x6d\x5f\145\162\162\x6f\162\x5f\x70\x61\147\145");
        $Bl = "\105\162\162\157\x72\x20\146\x72\x6f\x6d\40\x41\x75\164\x68\x6f\x72\x69\x7a\145\40\x45\156\144\x70\157\151\156\x74\72\40" . sanitize_text_field($_GET["\x65\162\162\157\x72"]);
        MO_Oauth_Debug::mo_oauth_log($Bl);
        $Uj->handle_error($Bl);
        wp_die($Bl);
        gi:
        goto QU;
        Fu:
        if (!(strpos($_GET["\x73\x74\x61\x74\145"], "\144\157\x6b\141\156\x2d\x73\x74\x72\151\x70\x65\x2d\143\x6f\x6e\156\145\143\x74") !== false)) {
            goto jQ;
        }
        return;
        jQ:
        do_action("\x6d\157\137\162\145\144\x69\162\x65\143\164\137\x74\x6f\x5f\x63\165\163\164\157\x6d\137\145\x72\x72\157\x72\x5f\160\141\x67\145");
        $l1 = "\105\x72\162\157\x72\40\x64\x65\163\143\x72\x69\160\x74\151\157\156\x20\x66\x72\x6f\155\x20\101\x75\164\150\x6f\x72\151\172\x65\40\x45\x6e\144\x70\157\151\156\x74\72\40" . sanitize_text_field($_GET["\x65\162\162\x6f\162\x5f\x64\145\163\x63\x72\151\160\164\x69\157\x6e"]);
        MO_Oauth_Debug::mo_oauth_log($l1);
        $Uj->handle_error($l1);
        wp_die($l1);
        QU:
        if (!(strpos($_SERVER["\122\105\x51\x55\105\x53\x54\x5f\125\122\111"], "\x6f\x70\x65\156\x69\x64\143\141\154\x6c\x62\x61\x63\x6b") !== false || strpos($_SERVER["\x52\105\x51\x55\105\x53\124\137\x55\122\111"], "\157\141\165\164\x68\137\x74\157\x6b\x65\x6e") !== false && strpos($_SERVER["\x52\x45\x51\x55\105\x53\124\137\125\122\x49"], "\157\x61\x75\164\150\137\166\145\x72\151\x66\x69\x65\162"))) {
            goto C0;
        }
        MO_Oauth_Debug::mo_oauth_log("\117\141\165\x74\150\61\40\x63\x61\x6c\x6c\142\141\x63\x6b\40\x66\154\157\x77");
        if (!empty($_COOKIE["\157\141\x75\x74\150\x31\141\x70\x70\x6e\141\155\145"])) {
            goto v4;
        }
        MO_Oauth_Debug::mo_oauth_log("\122\145\164\x75\162\x6e\151\156\147\x20\146\162\x6f\x6d\40\117\x41\x75\x74\x68\x31");
        return;
        v4:
        MO_Oauth_Debug::mo_oauth_log("\x4f\101\x75\164\150\61\40\141\160\160\x20\146\157\x75\156\144");
        $BW = $_COOKIE["\x6f\141\165\x74\x68\61\141\160\x70\156\141\155\145"];
        $Qu = MO_Custom_OAuth1::mo_oidc1_get_access_token($_COOKIE["\157\x61\x75\164\x68\x31\141\x70\x70\x6e\141\x6d\x65"]);
        $Uk = apply_filters("\x6d\x6f\137\x74\x72\137\x61\x66\x74\x65\x72\137\x70\x72\x6f\x66\151\x6c\x65\x5f\151\x6e\146\157\137\145\170\164\x72\141\x63\x74\x69\x6f\156\137\x66\x72\x6f\x6d\137\x74\157\x6b\145\156", $Qu);
        $zn = [];
        $Rm = $this->dropdownattrmapping('', $Qu, $zn);
        $Uj->mo_oauth_client_update_option("\x6d\157\137\157\x61\x75\x74\x68\137\141\164\x74\x72\137\x6e\141\155\145\x5f\x6c\151\163\164" . $BW, $Rm);
        if (!(isset($_COOKIE["\x6f\141\x75\164\x68\61\137\x74\145\163\x74"]) && $_COOKIE["\x6f\x61\165\x74\150\61\x5f\x74\x65\x73\164"] == "\61")) {
            goto FU;
        }
        $Fr = $Uj->get_app_by_name($BW);
        $Ix = $Fr->get_app_config();
        $this->render_test_config_output($Qu, false, $Ix, $BW);
        exit;
        FU:
        $Fr = $Uj->get_app_by_name($BW);
        $ex = $Fr->get_app_config("\x75\x73\145\162\156\141\x6d\x65\x5f\141\x74\164\x72");
        $rn = isset($Wh["\145\155\x61\x69\154\x5f\x61\164\164\162"]) ? $Wh["\x65\155\141\x69\154\x5f\141\164\164\162"] : '';
        $g3 = $Uj->getnestedattribute($Qu, $rn);
        $uQ = $Uj->getnestedattribute($Qu, $ex);
        if (!empty($uQ)) {
            goto KO;
        }
        MO_Oauth_Debug::mo_oauth_log("\x55\x73\145\x72\156\x61\x6d\145\40\x6e\x6f\x74\40\162\x65\x63\x65\x69\166\145\144\56\120\x6c\x65\141\163\x65\x20\x63\x6f\x6e\x66\x69\147\165\x72\145\x20\x41\164\164\162\x69\142\165\164\145\40\x4d\141\x70\160\x69\156\147");
        wp_die("\x55\x73\145\162\x6e\x61\x6d\145\40\156\x6f\164\40\x72\145\x63\145\151\166\x65\x64\56\x50\154\145\141\163\145\40\x63\x6f\x6e\x66\151\x67\165\162\145\40\x41\x74\x74\x72\151\x62\x75\x74\145\40\x4d\141\160\160\x69\156\147");
        KO:
        if (!empty($g3)) {
            goto hl;
        }
        $user = get_user_by("\154\157\147\x69\156", $uQ);
        goto Iq;
        hl:
        $g3 = $Uj->getnestedattribute($Qu, $rn);
        if (!(false === strpos($g3, "\100"))) {
            goto gV;
        }
        MO_Oauth_Debug::mo_oauth_log("\115\x61\160\160\x65\x64\x20\105\x6d\141\151\154\40\x61\x74\164\162\x69\x62\x75\x74\145\40\144\157\145\x73\x20\156\157\x74\x20\x63\157\x6e\164\141\x69\x6e\x20\166\x61\154\151\x64\40\x65\155\x61\151\x6c\56");
        wp_die("\115\x61\x70\x70\x65\144\x20\105\x6d\141\151\x6c\x20\x61\164\164\x72\151\x62\165\164\x65\x20\x64\157\x65\163\x20\x6e\x6f\164\x20\143\x6f\x6e\x74\141\x69\x6e\40\166\x61\x6c\x69\144\40\145\155\141\151\154\56");
        gV:
        Iq:
        if ($user) {
            goto GE;
        }
        $TV = 0;
        if ($Uj->mo_oauth_hbca_xyake()) {
            goto tu;
        }
        $user = $Uj->mo_oauth_hjsguh_kiishuyauh878gs($g3, $uQ);
        goto HI;
        tu:
        if ($Uj->mo_oauth_client_get_option("\x6d\157\x5f\157\x61\x75\x74\150\x5f\x66\154\x61\147") !== true) {
            goto tH;
        }
        $EK = base64_decode("\120\x47\x52\x70\x64\x69\x42\x7a\x64\110\154\163\x5a\124\60\x6e\x64\107\126\64\144\x43\61\x68\142\x47\154\156\142\x6a\160\x6a\x5a\x57\65\x30\x5a\130\x49\x37\x4a\172\64\70\131\x6a\x35\x56\143\x32\x56\x79\x49\105\106\x6a\131\x32\x39\61\x62\156\x51\x67\x5a\x47\71\154\x63\171\102\165\142\x33\x51\147\132\130\150\160\143\63\121\x75\120\103\x39\151\x50\152\167\166\132\107\154\x32\x50\152\170\x69\x63\x6a\x34\70\x63\62\x31\150\x62\x47\167\53\x56\107\x68\160\143\171\x42\x32\x5a\x58\112\172\x61\127\x39\x75\111\x48\x4e\61\x63\x48\x42\166\x63\x6e\x52\172\x49\105\x46\61\144\x47\x38\147\121\x33\112\154\x59\x58\x52\154\111\x46\126\172\x5a\x58\111\147\132\155\126\x68\144\x48\126\171\x5a\x53\x42\61\x63\110\x52\x76\111\104\105\167\111\106\126\172\x5a\130\x4a\x7a\x4c\151\102\x51\142\x47\x56\x68\x63\x32\x55\147\x64\x58\102\x6e\143\x6d\106\153\x5a\x53\102\60\142\171\102\60\x61\x47\x55\147\x61\107\154\x6e\141\x47\126\171\x49\x48\x5a\154\x63\156\x4e\160\x62\x32\x34\x67\x62\62\131\147\x64\x47\150\x6c\111\x48\x42\163\144\127\144\x70\142\x69\102\x30\x62\171\102\x6c\x62\155\x46\151\142\107\125\147\131\x58\126\60\142\x79\x42\152\x63\155\x56\x68\144\x47\125\147\144\x58\116\154\143\x69\x42\x6d\x62\x33\x49\x67\144\127\65\x73\x61\x57\61\x70\144\x47\126\x6b\x49\x48\126\x7a\x5a\130\x4a\172\x49\107\x39\171\x49\x47\x46\x6b\132\103\102\61\143\62\x56\171\111\107\61\150\142\x6e\x56\150\142\x47\x78\x35\114\x6a\x77\166\x63\x32\61\150\142\107\x77\x2b");
        MO_Oauth_Debug::mo_oauth_log($EK);
        wp_die($EK);
        goto kz;
        tH:
        if (!empty($g3)) {
            goto ns;
        }
        $user = $Uj->mo_oauth_jhuyn_jgsukaj($uQ, $uQ);
        goto KS;
        ns:
        $user = $Uj->mo_oauth_jhuyn_jgsukaj($g3, $uQ);
        KS:
        kz:
        HI:
        goto xr;
        GE:
        $TV = $user->ID;
        xr:
        if (!$user) {
            goto Gq;
        }
        wp_set_current_user($user->ID);
        $z0 = false;
        $z0 = apply_filters("\x6d\157\137\162\145\x6d\x65\155\x62\145\x72\x5f\155\x65", $z0);
        wp_set_auth_cookie($user->ID, $z0);
        $user = get_user_by("\x49\x44", $user->ID);
        do_action("\167\160\137\154\157\x67\151\x6e", $user->user_login, $user);
        wp_safe_redirect(home_url());
        exit;
        Gq:
        C0:
        if (!(!isset($_SERVER["\x48\x54\124\120\137\x58\x5f\x52\x45\121\x55\x45\x53\124\105\104\x5f\127\x49\x54\110"]) && (strpos($_SERVER["\122\105\121\125\105\x53\124\137\x55\x52\x49"], "\x2f\157\141\165\x74\x68\x63\141\154\x6c\x62\x61\x63\153") !== false || isset($_REQUEST["\143\x6f\x64\145"]) && !empty($_REQUEST["\143\x6f\144\145"]) && !isset($_REQUEST["\x69\x64\137\164\x6f\153\x65\156"])))) {
            goto xR;
        }
        $ie = time();
        if ($ie < 1713484774) {
            goto YI;
        }
        exit("\164\162\x69\141\x6c\x20\x70\x65\x72\x69\157\x64\x20\150\141\x73\40\x65\170\160\151\162\x65\144");
        goto eQ;
        YI:
        if (!isset($_REQUEST["\x70\x6f\x73\x74\x5f\x49\x44"])) {
            goto hG;
        }
        return;
        hG:
        try {
            if (isset($_COOKIE["\163\x74\x61\164\x65\137\x70\x61\162\x61\155"])) {
                goto I8;
            }
            if (isset($_GET["\x73\x74\x61\x74\145"])) {
                goto du;
            }
            $xp = new StorageManager();
            if (!is_multisite()) {
                goto V_;
            }
            $xp->add_replace_entry("\x62\x6c\157\147\x5f\x69\x64", 1);
            V_:
            $Ai = $Uj->get_app_by_name();
            if (isset($_GET["\x61\160\x70\137\x6e\x61\x6d\x65"])) {
                goto sL;
            }
            $xp->add_replace_entry("\x61\x70\160\x6e\x61\x6d\145", $Ai->get_app_name());
            goto Yc;
            sL:
            $xp->add_replace_entry("\x61\x70\x70\156\141\155\145", $_GET["\141\x70\160\x5f\x6e\x61\155\145"]);
            Yc:
            $xp->add_replace_entry("\x74\x65\x73\x74\137\143\157\156\146\151\x67", false);
            $xp->add_replace_entry("\162\145\144\151\x72\x65\143\x74\137\165\x72\x69", site_url());
            $Zi = $xp->get_state();
            goto FM;
            du:
            $Zi = wp_unslash($_GET["\x73\164\141\x74\145"]);
            FM:
            goto db;
            I8:
            $Zi = $_COOKIE["\x73\x74\141\164\x65\137\160\141\162\141\x6d"];
            db:
            $cm = new StorageManager($Zi);
            if (!empty($cm->get_value("\141\160\x70\156\x61\x6d\145"))) {
                goto H6;
            }
            return;
            H6:
            $BW = $cm->get_value("\141\160\x70\156\x61\x6d\x65");
            $ji = $cm->get_value("\164\x65\163\x74\x5f\x63\157\156\x66\151\147");
            if (!is_multisite()) {
                goto dD;
            }
            if (!(empty($cm->get_value("\162\x65\144\x69\162\x65\143\164\x65\144\137\x74\157\x5f\163\165\x62\x73\x69\164\x65")) || $cm->get_value("\162\x65\x64\x69\162\x65\143\x74\145\144\137\164\x6f\137\163\x75\x62\x73\x69\x74\145") !== "\x72\145\x64\x69\162\x65\x63\x74")) {
                goto X_;
            }
            MO_Oauth_Debug::mo_oauth_log("\122\x65\x64\x69\x72\145\x63\164\151\x6e\147\40\146\x6f\162\40\x6d\x75\154\x74\151\163\x74\145\x20\x73\165\x62\163\151\164\145");
            $blog_id = $cm->get_value("\142\154\x6f\147\x5f\x69\144");
            $Xt = get_site_url($blog_id);
            $cm->add_replace_entry("\x72\x65\144\151\x72\145\143\x74\x65\x64\x5f\164\x6f\x5f\163\x75\142\163\x69\x74\x65", "\162\145\x64\151\x72\x65\x63\x74");
            $S4 = $cm->get_state();
            $Xt = $Xt . "\x3f\143\x6f\x64\x65\x3d" . $_GET["\x63\157\144\145"] . "\x26\163\x74\141\164\x65\x3d" . $S4;
            wp_redirect($Xt);
            exit;
            X_:
            dD:
            $Cm = $BW ? $BW : '';
            $H5 = $Uj->mo_oauth_client_get_option("\x6d\x6f\137\157\141\165\164\x68\x5f\141\x70\160\163\x5f\x6c\151\163\164");
            $ex = '';
            $rn = '';
            $qJ = $Uj->get_app_by_name($Cm);
            if ($qJ) {
                goto pw;
            }
            $Uj->handle_error("\101\x70\160\154\151\143\x61\x74\x69\157\x6e\x20\x6e\x6f\164\x20\x63\157\156\x66\x69\x67\x75\x72\145\x64\56");
            MO_Oauth_Debug::mo_oauth_log("\x41\160\x70\154\x69\143\141\x74\x69\x6f\156\40\156\157\164\40\x63\157\156\146\151\147\x75\162\x65\x64\56");
            exit("\x41\x70\x70\x6c\151\143\x61\x74\x69\157\x6e\40\156\157\x74\x20\143\x6f\156\x66\x69\147\x75\x72\x65\144\x2e");
            pw:
            $Wh = $qJ->get_app_config();
            if (!(isset($Wh["\x73\145\x6e\144\137\156\157\x6e\x63\x65"]) && $Wh["\x73\x65\x6e\144\x5f\156\x6f\x6e\143\x65"] === 1)) {
                goto N1;
            }
            if (!(isset($_REQUEST["\156\157\156\x63\145"]) && !$Uj->get_transient("\155\157\x5f\x6f\141\165\x74\x68\137\x6e\x6f\x6e\143\x65\x5f" . $_REQUEST["\x6e\157\x6e\x63\145"]))) {
                goto yX;
            }
            $Bl = "\116\157\156\x63\x65\x20\166\145\x72\151\146\x69\143\141\164\151\157\156\40\151\163\x20\x66\x61\x69\154\145\x64\56\x20\x50\154\145\x61\x73\x65\x20\143\x6f\x6e\x74\x61\143\164\x20\164\157\x20\x79\157\x75\x72\40\x61\x64\155\151\x6e\x69\163\x74\162\x61\x74\157\162\56";
            $Uj->handle_error($Bl);
            MO_Oauth_Debug::mo_oauth_log($Bl);
            wp_die($Bl);
            yX:
            N1:
            $um = $qJ->get_app_config("\160\153\143\145\137\146\154\x6f\x77");
            $eg = $qJ->get_app_config("\x70\153\x63\x65\x5f\143\x6c\151\145\156\x74\x5f\x73\145\143\x72\x65\x74");
            $z5 = array("\147\x72\141\156\164\x5f\164\x79\160\145" => "\x61\x75\164\150\157\162\x69\172\x61\164\x69\x6f\156\x5f\143\157\x64\145", "\143\154\x69\145\x6e\x74\137\x69\x64" => $Wh["\x63\x6c\x69\x65\x6e\x74\x5f\x69\144"], "\x72\145\144\x69\x72\145\x63\164\137\165\x72\151" => $Wh["\162\145\x64\x69\162\145\x63\x74\x5f\x75\162\151"], "\143\x6f\144\145" => $_REQUEST["\x63\157\x64\145"]);
            if (!(strpos($Wh["\x61\143\143\145\163\x73\x74\157\x6b\x65\156\x75\162\x6c"], "\163\x65\162\166\x69\143\145\163\57\x6f\141\165\164\x68\62\x2f\164\157\x6b\145\156") === false && strpos($Wh["\141\143\x63\145\x73\x73\164\157\153\x65\x6e\x75\x72\x6c"], "\x73\x61\x6c\x65\x73\x66\157\x72\143\x65") === false && strpos($Wh["\x61\x63\x63\x65\163\163\x74\157\153\145\156\165\x72\154"], "\57\157\x61\155\57\x6f\x61\x75\x74\150\62\x2f\141\x63\x63\x65\163\x73\137\x74\x6f\x6b\x65\156") === false)) {
                goto gZ;
            }
            $z5["\163\x63\157\160\145"] = $qJ->get_app_config("\163\x63\x6f\160\145");
            gZ:
            if ($um && 1 === $um) {
                goto k0;
            }
            $z5["\x63\x6c\x69\145\156\x74\137\163\145\143\x72\x65\164"] = $Wh["\x63\x6c\x69\145\156\x74\x5f\x73\x65\143\x72\145\164"];
            goto wJ;
            k0:
            if (!($eg && 1 === $eg)) {
                goto dB;
            }
            $z5["\143\154\x69\145\x6e\164\137\163\x65\143\162\145\x74"] = $Wh["\143\x6c\x69\x65\x6e\x74\137\163\x65\x63\162\145\x74"];
            dB:
            $z5 = apply_filters("\155\x6f\137\x6f\141\165\x74\150\x5f\141\144\x64\x5f\143\154\151\x65\x6e\164\137\163\x65\143\162\x65\164\137\x70\x6b\x63\x65\x5f\146\x6c\x6f\x77", $z5, $Wh);
            $z5["\x63\157\x64\145\x5f\x76\145\162\x69\146\x69\145\x72"] = $cm->get_value("\x63\x6f\x64\145\x5f\x76\x65\162\151\x66\x69\x65\x72");
            wJ:
            $gL = isset($Wh["\163\145\x6e\x64\x5f\150\145\141\144\145\162\x73"]) ? $Wh["\163\x65\156\x64\137\150\145\141\x64\x65\162\163"] : 0;
            $n0 = isset($Wh["\x73\x65\156\x64\137\x62\157\144\171"]) ? $Wh["\x73\x65\x6e\x64\x5f\142\x6f\x64\171"] : 0;
            if ("\x6f\160\145\x6e\x69\x64\143\157\156\x6e\145\143\x74" === $qJ->get_app_config("\141\160\160\x5f\x74\x79\x70\145")) {
                goto z8;
            }
            $z1 = $Wh["\141\x63\x63\145\x73\x73\164\157\153\145\x6e\165\x72\x6c"];
            MO_Oauth_Debug::mo_oauth_log("\x4f\101\x75\164\x68\40\x66\x6c\157\x77");
            if (strpos($Wh["\x61\165\164\x68\x6f\x72\151\x7a\145\165\162\154"], "\143\154\145\166\x65\x72\56\143\157\155\57\x6f\x61\x75\x74\x68") != false || strpos($Wh["\141\x63\143\145\163\163\164\x6f\x6b\145\156\x75\162\154"], "\x62\151\164\162\151\x78") != false) {
                goto UD;
            }
            $Ze = json_decode($this->oauth_handler->get_token($z1, $z5, $gL, $n0), true);
            goto xf;
            UD:
            $Ze = json_decode($this->oauth_handler->get_atoken($z1, $z5, $_GET["\x63\x6f\x64\x65"], $gL, $n0), true);
            xf:
            if (!(get_current_user_id() && $ji != 1)) {
                goto wl;
            }
            wp_clear_auth_cookie();
            wp_set_current_user(0);
            wl:
            $_SESSION["\x70\x72\x6f\x63\x6f\x72\x65\137\x61\143\x63\145\x73\x73\x5f\x74\157\153\x65\156"] = isset($Ze["\141\143\x63\x65\163\x73\x5f\164\157\x6b\145\156"]) ? $Ze["\141\x63\x63\145\163\x73\x5f\x74\x6f\x6b\145\156"] : false;
            if (isset($Ze["\x61\x63\x63\145\163\x73\x5f\164\x6f\153\x65\156"])) {
                goto Va;
            }
            do_action("\x6d\157\137\162\x65\144\151\162\145\143\x74\137\x74\x6f\137\143\165\x73\x74\157\x6d\x5f\145\x72\x72\157\162\x5f\x70\141\147\145");
            $Uj->handle_error("\111\x6e\166\x61\154\x69\x64\40\164\x6f\153\145\156\x20\162\x65\x63\x65\x69\166\x65\x64\56");
            MO_Oauth_Debug::mo_oauth_log("\111\x6e\x76\x61\x6c\x69\144\x20\164\157\153\145\156\x20\162\x65\143\145\151\166\x65\x64\x2e");
            exit("\x49\x6e\x76\141\154\x69\144\x20\x74\157\x6b\145\156\40\162\145\x63\x65\151\x76\145\144\x2e");
            Va:
            MO_Oauth_Debug::mo_oauth_log("\x54\157\153\x65\x6e\x20\122\145\x73\160\157\x6e\x73\145\x20\x3d\76\x20");
            MO_Oauth_Debug::mo_oauth_log($Ze);
            $nI = $Wh["\162\x65\163\157\165\162\143\145\157\167\x6e\145\x72\x64\x65\164\x61\151\x6c\163\x75\x72\154"];
            if (!(substr($nI, -1) === "\75")) {
                goto Yd;
            }
            $nI .= $Ze["\141\x63\143\x65\x73\x73\x5f\164\157\153\x65\156"];
            Yd:
            MO_Oauth_Debug::mo_oauth_log("\101\x63\x63\x65\163\x73\x20\164\x6f\x6b\x65\156\40\x72\x65\x63\145\151\166\145\x64\56");
            MO_Oauth_Debug::mo_oauth_log("\101\x63\143\x65\163\x73\40\x54\x6f\x6b\145\156\x20\x3d\76\40" . $Ze["\141\143\x63\x65\163\163\137\164\157\153\x65\x6e"]);
            $Dg = null;
            $Dg = apply_filters("\155\x6f\137\160\x6f\x6c\x61\x72\x5f\x72\x65\x67\151\x73\x74\x65\x72\x5f\x75\x73\x65\162", $Ze);
            if (!(!empty($Dg) && !empty($Ze["\170\x5f\165\163\x65\162\137\x69\x64"]))) {
                goto Tx;
            }
            $nI .= "\x2f" . $Ze["\x78\137\165\x73\x65\x72\x5f\151\x64"];
            Tx:
            $Qu = $this->oauth_handler->get_resource_owner($nI, $Ze["\141\143\x63\145\x73\163\137\164\x6f\153\145\x6e"]);
            $OG = array();
            if (!(strpos($qJ->get_app_config("\x61\165\164\150\157\x72\151\x7a\x65\165\x72\154"), "\154\151\156\x6b\x65\x64\x69\x6e") !== false && strpos($Wh["\163\143\157\160\x65"], "\162\x5f\x65\155\x61\x69\x6c\141\x64\144\162\145\x73\163") != false)) {
                goto nv;
            }
            $y0 = "\150\164\x74\x70\x73\x3a\57\57\141\x70\x69\56\x6c\151\x6e\153\145\x64\151\x6e\56\143\157\x6d\57\x76\62\57\x65\x6d\x61\x69\154\101\144\x64\x72\145\x73\163\x3f\x71\x3d\155\145\155\x62\x65\162\163\x26\160\162\157\152\x65\143\x74\x69\x6f\156\75\x28\x65\154\x65\155\x65\156\164\x73\x2a\50\x68\141\x6e\144\x6c\145\x7e\51\x29";
            $OG = $this->oauth_handler->get_resource_owner($y0, $Ze["\x61\x63\143\145\163\163\x5f\x74\157\153\145\156"]);
            nv:
            $Qu = array_merge($Qu, $OG);
            MO_Oauth_Debug::mo_oauth_log("\122\145\163\x6f\x75\162\143\145\40\x4f\167\x6e\145\x72\40\75\x3e\40");
            MO_Oauth_Debug::mo_oauth_log($Qu);
            $Uk = apply_filters("\x6d\157\x5f\164\162\137\141\x66\x74\145\x72\x5f\x70\162\x6f\x66\x69\154\145\137\151\x6e\146\157\137\145\170\x74\x72\x61\x63\x74\x69\157\156\137\146\162\157\x6d\x5f\164\x6f\x6b\145\156", $Qu);
            if (!($Uk != '' && is_array($Uk))) {
                goto eL;
            }
            $Qu = array_merge($Qu, $Uk);
            eL:
            $GR = apply_filters("\x61\x63\143\x72\145\x64\x69\164\x69\x6f\x6e\x73\137\145\x6e\x64\x70\157\151\x6e\x74", $Ze["\x61\143\x63\x65\163\x73\x5f\164\x6f\x6b\x65\x6e"]);
            if (!($GR !== '' && is_array($GR))) {
                goto qS;
            }
            $Qu = array_merge($Qu, $GR);
            qS:
            if (!has_filter("\155\157\x5f\x70\157\154\x61\162\137\x72\x65\x67\151\x73\x74\x65\x72\137\165\x73\145\x72")) {
                goto YZ;
            }
            $Aq = array();
            $Aq["\x74\x6f\153\145\x6e"] = $Ze["\141\x63\143\x65\x73\163\137\x74\157\153\x65\x6e"];
            $Qu = array_merge($Qu, $Aq);
            YZ:
            if (!(strpos($qJ->get_app_config("\141\x75\164\x68\x6f\x72\x69\172\145\165\x72\x6c"), "\144\151\x73\x63\157\x72\x64") !== false)) {
                goto Kd;
            }
            $yF = apply_filters("\x6d\157\x5f\144\162\155\137\x67\145\x74\x5f\x75\163\145\162\137\162\x6f\154\145\x73", array_key_exists("\151\x64", $Qu) ? $Qu["\x69\x64"] : '');
            if (!(false !== $yF)) {
                goto No;
            }
            MO_Oauth_Debug::mo_oauth_log("\x44\151\x73\x63\x6f\162\x64\40\x52\x6f\x6c\145\163\40\x3d\x3e\40");
            MO_Oauth_Debug::mo_oauth_log($yF);
            $Qu["\162\157\x6c\145\x73"] = $yF;
            No:
            Kd:
            if (!(isset($Wh["\163\145\156\x64\x5f\x6e\157\156\x63\145"]) && $Wh["\x73\145\156\144\x5f\x6e\157\x6e\x63\145"] === 1)) {
                goto p6;
            }
            if (!(isset($Qu["\156\x6f\x6e\x63\x65"]) && $Qu["\156\157\156\143\145"] != NULL)) {
                goto WK;
            }
            if ($Uj->get_transient("\x6d\x6f\137\x6f\141\x75\x74\x68\x5f\x6e\157\156\143\145\137" . $Qu["\156\157\156\x63\x65"])) {
                goto ey;
            }
            $Bl = "\116\x6f\156\143\145\40\166\145\162\x69\146\151\143\141\x74\x69\157\x6e\x20\151\x73\40\146\141\151\154\x65\x64\56\40\120\x6c\x65\x61\x73\x65\x20\143\x6f\x6e\164\141\x63\164\40\x74\157\40\171\x6f\165\x72\40\141\144\x6d\151\156\x69\x73\x74\x72\141\164\157\162\x2e";
            $Uj->handle_error($Bl);
            MO_Oauth_Debug::mo_oauth_log($Bl);
            wp_die($Bl);
            goto CD;
            ey:
            $Uj->delete_transient("\x6d\157\137\x6f\141\x75\x74\150\137\156\157\156\x63\145\x5f" . $Qu["\x6e\157\156\x63\145"]);
            CD:
            WK:
            p6:
            $zn = [];
            $Rm = $this->dropdownattrmapping('', $Qu, $zn);
            $Uj->mo_oauth_client_update_option("\x6d\x6f\x5f\x6f\141\x75\x74\150\137\x61\164\164\x72\137\156\x61\x6d\145\137\154\x69\163\164" . $Cm, $Rm);
            if (!($ji && '' !== $ji)) {
                goto ga;
            }
            $this->handle_group_test_conf($Qu, $Wh, $Ze["\141\x63\143\145\x73\163\137\164\157\153\x65\156"], false, $ji);
            exit;
            ga:
            goto Zk;
            z8:
            MO_Oauth_Debug::mo_oauth_log("\x4f\x70\145\x6e\111\104\x20\x43\x6f\x6e\x6e\x65\x63\x74\x20\146\x6c\x6f\x77");
            $Ze = json_decode($this->oauth_handler->get_token($Wh["\141\x63\x63\x65\x73\163\164\157\x6b\145\156\x75\162\154"], $z5, $gL, $n0), true);
            $zN = [];
            try {
                $zN = $this->resolve_and_get_oidc_response($Ze);
            } catch (\Exception $mP) {
                $Uj->handle_error($mP->getMessage());
                MO_Oauth_Debug::mo_oauth_log("\111\x6e\166\141\154\151\x64\x20\122\x65\x73\x70\157\x6e\163\145\x20\x72\145\143\x65\151\166\x65\x64\56");
                do_action("\x6d\157\137\162\145\x64\x69\x72\145\143\x74\x5f\x74\157\137\143\165\x73\164\x6f\x6d\137\x65\x72\162\157\162\x5f\160\141\147\145");
                wp_die("\111\156\166\x61\x6c\151\x64\40\122\x65\x73\x70\x6f\x6e\163\x65\x20\162\145\x63\x65\151\166\145\x64\x2e");
                exit;
            }
            MO_Oauth_Debug::mo_oauth_log("\x49\104\40\x54\x6f\153\x65\156\40\x72\145\x63\145\x69\x76\x65\144\40\123\x75\143\143\x65\x73\163\146\x75\154\154\171");
            MO_Oauth_Debug::mo_oauth_log("\111\104\x20\x54\x6f\x6b\x65\156\x20\x3d\x3e\x20" . $zN);
            $Qu = $this->get_resource_owner_from_app($zN, $Cm);
            MO_Oauth_Debug::mo_oauth_log("\122\x65\x73\157\165\162\143\x65\x20\117\x77\x6e\x65\x72\40\75\76\40");
            MO_Oauth_Debug::mo_oauth_log($Qu);
            if (!(strpos($qJ->get_app_config("\x61\x75\x74\x68\x6f\x72\x69\x7a\x65\165\x72\x6c"), "\x74\x77\151\164\x63\x68") !== false)) {
                goto qU;
            }
            $CT = apply_filters("\155\x6f\x5f\x74\163\x6d\137\x67\145\x74\137\x75\x73\x65\162\x5f\x73\165\x62\163\143\x72\151\160\164\x69\157\x6e", $Qu["\x73\165\x62"], $Wh["\x63\154\x69\145\x6e\x74\137\151\x64"], $Ze["\141\143\x63\x65\163\x73\137\x74\x6f\x6b\145\156"]);
            if (!(false !== $CT)) {
                goto vf;
            }
            MO_Oauth_Debug::mo_oauth_log("\124\x77\151\164\143\x68\40\x53\x75\x62\x73\x63\x72\x69\160\164\151\157\156\x20\x3d\x3e\x20");
            MO_Oauth_Debug::mo_oauth_log($CT);
            $Qu["\x73\x75\x62\163\143\x72\151\160\164\x69\x6f\x6e"] = $CT;
            vf:
            qU:
            if (!($qJ->get_app_config("\x61\160\x70\111\144") === "\x6b\145\171\x63\154\x6f\x61\153")) {
                goto l_;
            }
            $sG = apply_filters("\155\x6f\x5f\153\x72\x6d\x5f\147\145\x74\137\165\163\145\x72\x5f\x72\157\x6c\x65\x73", $Qu, $Ze);
            if (!(false !== $sG)) {
                goto Hn;
            }
            $Qu["\162\157\x6c\x65\x73"] = $sG;
            Hn:
            l_:
            $Qu = apply_filters("\x6d\x6f\137\141\x7a\x75\x72\x65\x62\x32\143\137\x67\x65\x74\137\165\x73\x65\x72\137\147\x72\x6f\165\160\x5f\151\x64\163", $Qu, $Wh);
            $Uk = apply_filters("\x6d\157\x5f\164\x72\137\x61\146\x74\x65\162\137\160\162\157\146\x69\154\x65\x5f\x69\156\x66\x6f\x5f\145\x78\x74\x72\x61\x63\x74\x69\157\156\x5f\x66\x72\157\155\137\164\157\x6b\145\156", $Qu);
            if (!($Uk != '' && is_array($Uk))) {
                goto s9;
            }
            $Qu = array_merge($Qu, $Uk);
            s9:
            if (!(isset($Wh["\x73\145\156\144\137\156\157\x6e\x63\x65"]) && $Wh["\163\x65\156\x64\137\156\157\x6e\143\145"] === 1)) {
                goto D0;
            }
            if (!(isset($Qu["\x6e\x6f\x6e\x63\145"]) && $Qu["\156\x6f\x6e\x63\x65"] != NULL)) {
                goto VP;
            }
            if ($Uj->get_transient("\155\157\x5f\x6f\141\165\x74\150\137\156\157\156\143\145\137" . $Qu["\156\x6f\156\x63\145"])) {
                goto N4;
            }
            $Bl = "\116\x6f\x6e\x63\145\x20\x76\145\x72\151\146\151\143\141\164\151\157\156\40\x69\x73\x20\146\x61\151\x6c\x65\x64\56\40\x50\154\145\x61\163\x65\x20\143\x6f\156\x74\141\143\164\40\x74\157\x20\x79\x6f\x75\162\40\141\x64\x6d\x69\x6e\x69\x73\x74\162\141\x74\x6f\x72\x2e";
            $Uj->handle_error($Bl);
            MO_Oauth_Debug::mo_oauth_log($Bl);
            wp_die($Bl);
            goto n3;
            N4:
            $Uj->delete_transient("\x6d\x6f\137\x6f\x61\165\x74\x68\137\x6e\157\x6e\x63\145\x5f" . $Qu["\x6e\157\x6e\143\x65"]);
            n3:
            VP:
            D0:
            $zn = [];
            $Rm = $this->dropdownattrmapping('', $Qu, $zn);
            $Uj->mo_oauth_client_update_option("\155\157\137\157\141\165\164\x68\x5f\141\x74\164\x72\137\x6e\x61\x6d\x65\x5f\x6c\151\163\164" . $Cm, $Rm);
            if (!($ji && '' !== $ji)) {
                goto kD;
            }
            $Ze["\162\x65\x66\162\145\163\150\x5f\x74\x6f\153\145\156"] = isset($Ze["\x72\x65\146\162\x65\163\150\x5f\164\157\x6b\145\156"]) ? $Ze["\x72\145\x66\162\145\163\x68\x5f\164\x6f\153\145\x6e"] : '';
            $_SESSION["\160\162\157\143\157\x72\145\x5f\x72\x65\146\162\x65\x73\150\x5f\x74\x6f\x6b\x65\x6e"] = $Ze["\162\145\x66\x72\145\x73\150\x5f\164\x6f\153\145\156"];
            $DH = isset($Ze["\x61\143\143\145\x73\163\137\164\157\x6b\145\156"]) ? $Ze["\x61\x63\143\145\x73\163\x5f\x74\157\x6b\145\156"] : '';
            $this->handle_group_test_conf($Qu, $Wh, $DH, false, $ji);
            MO_Oauth_Debug::mo_oauth_log("\x41\x74\164\x72\151\x62\x75\x74\x65\40\122\145\x63\145\151\166\x65\x64\x20\x53\165\143\x63\x65\x73\163\x66\x75\x6c\154\171");
            exit;
            kD:
            Zk:
            if (!(isset($Wh["\147\162\157\165\x70\x64\x65\164\141\151\x6c\163\x75\162\154"]) && !empty($Wh["\147\x72\157\x75\x70\x64\x65\x74\141\x69\154\163\x75\162\154"]))) {
                goto l9;
            }
            $Qu = $this->handle_group_user_info($Qu, $Wh, $Ze["\141\143\x63\145\x73\163\x5f\x74\x6f\x6b\145\156"]);
            MO_Oauth_Debug::mo_oauth_log("\x47\162\157\165\160\40\x44\145\164\x61\151\x6c\163\x20\117\142\x74\141\x69\x6e\145\x64\40\x3d\x3e\40" . $Qu);
            l9:
            MO_Oauth_Debug::mo_oauth_log("\106\x65\164\x63\150\145\x64\40\x72\145\163\x6f\x75\x72\143\145\x20\x6f\x77\156\x65\x72\x20\x3a\x20" . json_encode($Qu));
            if (!has_filter("\167\157\157\x63\157\x6d\x6d\145\162\x63\145\x5f\143\x68\x65\143\153\157\165\x74\137\x67\145\x74\137\166\141\x6c\x75\x65")) {
                goto io;
            }
            $Qu["\x61\x70\x70\156\141\155\145"] = $Cm;
            io:
            do_action("\155\157\x5f\x61\x62\162\x5f\146\151\x6c\x74\145\x72\x5f\154\x6f\x67\x69\156", $Qu);
            $this->handle_sso($Cm, $Wh, $Qu, $Zi, $Ze);
        } catch (Exception $mP) {
            $Uj->handle_error($mP->getMessage());
            MO_Oauth_Debug::mo_oauth_log($mP->getMessage());
            do_action("\155\157\x5f\x72\145\144\151\x72\x65\x63\x74\137\x74\157\137\143\165\x73\x74\x6f\x6d\x5f\145\162\162\157\162\x5f\160\141\x67\145");
            exit(esc_html($mP->getMessage()));
        }
        eQ:
        xR:
    }
    public function dropdownattrmapping($wZ, $Xg, $zn)
    {
        global $Uj;
        foreach ($Xg as $Mr => $l5) {
            if (is_array($l5)) {
                goto Dm;
            }
            if (!empty($wZ)) {
                goto t3;
            }
            array_push($zn, $Mr);
            goto fd;
            t3:
            array_push($zn, $wZ . "\x2e" . $Mr);
            fd:
            goto mP;
            Dm:
            if (empty($wZ)) {
                goto ET;
            }
            $wZ .= "\56";
            ET:
            $zn = $this->dropdownattrmapping($wZ . $Mr, $l5, $zn);
            $wZ = rtrim($wZ, "\56");
            mP:
            sw:
        }
        sP:
        return $zn;
    }
    public function resolve_and_get_oidc_response($Ze = array())
    {
        if (!empty($Ze)) {
            goto ql;
        }
        throw new \Exception("\x54\x6f\153\x65\156\x20\x72\x65\x73\x70\157\156\x73\x65\40\x69\x73\40\145\x6d\x70\164\171", "\151\156\166\x61\x6c\151\144\137\162\145\x73\x70\x6f\x6e\163\x65");
        ql:
        global $Uj;
        $DU = isset($Ze["\151\x64\x5f\164\x6f\x6b\145\156"]) ? $Ze["\x69\x64\x5f\164\x6f\x6b\x65\156"] : false;
        $AP = isset($Ze["\x61\x63\x63\145\163\163\137\164\157\x6b\145\x6e"]) ? $Ze["\x61\x63\143\145\x73\x73\x5f\x74\157\153\145\x6e"] : false;
        $_SESSION["\x70\162\157\143\x6f\x72\145\137\x61\x63\143\145\163\x73\x5f\164\x6f\x6b\145\156"] = isset($AP) ? $AP : $DU;
        if (!$Uj->is_valid_jwt($DU)) {
            goto cC;
        }
        return $DU;
        cC:
        if (!$Uj->is_valid_jwt($AP)) {
            goto LG;
        }
        return $AP;
        LG:
        MO_Oauth_Debug::mo_oauth_log("\124\x6f\153\145\x6e\40\x69\163\40\x6e\157\x74\40\141\40\166\141\154\x69\x64\40\112\127\124\56");
        throw new \Exception("\124\x6f\153\x65\x6e\x20\x69\x73\40\x6e\x6f\164\x20\x61\40\166\141\x6c\151\144\x20\x4a\127\124\56");
    }
    public function handle_group_test_conf($Qu = array(), $Wh = array(), $AP = '', $MG = false, $ji = false)
    {
        $this->render_test_config_output($Qu, false);
    }
    public function testattrmappingconfig($wZ, $Xg)
    {
        foreach ($Xg as $Mr => $l5) {
            if (is_array($l5) || is_object($l5)) {
                goto pl;
            }
            echo "\x3c\164\162\76\x3c\x74\x64\x3e";
            if (empty($wZ)) {
                goto yA;
            }
            echo $wZ . "\56";
            yA:
            echo $Mr . "\x3c\57\x74\144\76\x3c\x74\144\76" . $l5 . "\74\x2f\164\x64\76\x3c\57\x74\162\x3e";
            goto WD;
            pl:
            if (empty($wZ)) {
                goto Zp;
            }
            $wZ .= "\56";
            Zp:
            $this->testattrmappingconfig($wZ . $Mr, $l5);
            $wZ = rtrim($wZ, "\x2e");
            WD:
            yJ:
        }
        UZ:
    }
    public function render_test_config_output($Qu, $MG = false)
    {
        MO_Oauth_Debug::mo_oauth_log("\x54\x68\x69\163\40\151\163\40\164\x65\x73\164\40\x63\157\x6e\146\151\x67\165\162\x61\164\151\157\x6e\40\x66\x6c\157\x77\x20\75\76\40");
        echo "\74\x64\151\166\x20\163\164\171\x6c\145\75\x22\x66\x6f\156\x74\x2d\x66\141\155\151\154\171\x3a\103\x61\154\x69\142\162\x69\73\160\141\x64\x64\x69\x6e\x67\72\x30\40\63\x25\73\42\76";
        echo "\x3c\163\164\x79\x6c\x65\76\164\141\142\154\145\x7b\x62\x6f\162\x64\145\x72\x2d\143\157\x6c\x6c\141\x70\163\x65\x3a\143\x6f\x6c\154\x61\x70\163\x65\x3b\175\164\x68\x20\x7b\x62\x61\x63\153\x67\162\x6f\x75\x6e\144\55\x63\x6f\154\157\x72\72\x20\x23\145\x65\x65\x3b\40\x74\x65\170\164\x2d\141\154\151\147\156\x3a\x20\143\145\x6e\164\x65\x72\x3b\x20\x70\141\x64\144\151\x6e\x67\72\40\70\160\x78\x3b\40\142\x6f\x72\x64\145\x72\55\x77\x69\x64\x74\x68\72\x31\x70\x78\73\40\x62\x6f\162\x64\x65\x72\x2d\x73\164\x79\154\x65\x3a\163\157\x6c\151\144\x3b\x20\x62\x6f\x72\144\x65\162\55\143\x6f\x6c\157\162\x3a\x23\x32\61\62\x31\62\x31\x3b\x7d\x74\162\72\156\x74\x68\x2d\143\x68\151\x6c\144\50\x6f\144\x64\x29\40\173\142\x61\x63\153\x67\162\157\165\156\x64\x2d\x63\157\154\157\x72\x3a\x20\x23\x66\62\x66\62\146\x32\x3b\175\40\164\x64\173\x70\141\x64\144\x69\x6e\147\72\70\x70\170\x3b\142\157\162\x64\x65\162\55\167\151\x64\x74\150\72\x31\x70\170\73\40\x62\157\x72\144\145\x72\x2d\163\164\171\x6c\145\72\163\157\154\151\144\x3b\x20\142\x6f\x72\144\145\x72\x2d\x63\157\154\157\162\x3a\43\62\61\x32\x31\x32\x31\x3b\x7d\x3c\57\163\164\x79\x6c\x65\x3e";
        echo "\x3c\150\62\76";
        echo $MG ? "\x47\162\157\x75\x70\40\x49\156\x66\x6f" : "\x54\x65\163\x74\40\x43\157\x6e\146\151\147\165\x72\x61\164\x69\157\156";
        echo "\x3c\x2f\150\x32\76\74\164\141\x62\154\145\76\x3c\164\x72\x3e\x3c\164\150\x3e\x41\x74\164\x72\x69\x62\165\x74\x65\40\116\x61\155\145\74\57\x74\150\x3e\x3c\164\x68\76\101\x74\164\162\x69\142\165\164\145\40\x56\141\154\165\145\74\57\164\150\76\74\x2f\x74\x72\x3e";
        $this->testattrmappingconfig('', $Qu);
        echo "\74\x2f\x74\141\x62\154\145\x3e";
        if ($MG) {
            goto b7;
        }
        echo "\x3c\144\x69\x76\x20\x73\x74\x79\154\x65\75\x22\x70\x61\x64\x64\x69\156\147\72\40\61\60\x70\x78\x3b\42\x3e\x3c\57\x64\x69\x76\76\74\x69\156\160\x75\x74\40\163\164\171\x6c\x65\x3d\x22\160\141\144\144\x69\156\x67\72\61\45\73\x77\151\x64\x74\x68\x3a\x31\60\60\160\x78\x3b\x62\141\x63\x6b\147\x72\x6f\165\156\144\x3a\x20\x23\x30\60\71\61\103\x44\x20\x6e\x6f\156\145\x20\162\145\x70\x65\x61\164\x20\x73\143\162\157\x6c\154\x20\x30\x25\40\60\x25\73\x63\165\162\x73\x6f\x72\72\40\160\157\151\156\x74\145\162\73\x66\157\156\164\x2d\x73\x69\172\145\72\61\65\160\170\73\x62\157\x72\144\145\162\x2d\167\151\144\x74\x68\72\40\61\x70\x78\x3b\x62\x6f\162\144\x65\162\55\x73\x74\x79\x6c\x65\72\x20\163\157\x6c\x69\x64\x3b\x62\157\x72\x64\145\162\55\162\x61\x64\x69\x75\163\72\x20\63\160\170\73\x77\150\151\x74\145\55\x73\160\x61\x63\x65\x3a\x20\x6e\x6f\167\162\x61\160\73\x62\x6f\170\x2d\x73\151\172\x69\156\147\72\x20\142\x6f\x72\x64\x65\162\55\142\157\170\73\142\157\162\x64\145\162\55\x63\157\154\x6f\162\x3a\40\x23\x30\60\67\x33\101\x41\x3b\142\x6f\x78\x2d\163\150\x61\x64\x6f\x77\x3a\40\x30\x70\x78\40\x31\x70\170\x20\60\160\x78\40\x72\x67\x62\141\x28\x31\62\60\x2c\40\62\60\x30\54\40\x32\63\x30\54\x20\60\x2e\x36\51\x20\151\x6e\163\x65\164\x3b\x63\157\x6c\157\x72\x3a\x20\x23\106\106\x46\x3b\42\164\x79\x70\x65\75\42\142\x75\164\x74\x6f\156\42\40\x76\x61\x6c\165\x65\75\x22\104\157\156\145\x22\x20\x6f\x6e\103\x6c\151\143\153\75\42\163\x65\154\x66\56\143\x6c\x6f\163\x65\50\51\x3b\x22\x3e\74\x2f\x64\x69\166\x3e";
        b7:
    }
    public function handle_sso($Cm, $Wh, $Qu, $Zi, $Ze, $Zu = false)
    {
        MO_Oauth_Debug::mo_oauth_log("\x53\x53\117\x20\150\141\x6e\144\x6c\x69\156\x67\x20\x66\x6c\157\167");
        global $Uj;
        if (!(get_class($this) === "\x4d\x6f\117\141\165\x74\150\103\x6c\x69\x65\x6e\164\x5c\x4c\157\x67\x69\x6e\110\x61\156\x64\x6c\145\x72" && $Uj->check_versi(1))) {
            goto KV;
        }
        $xL = new \MoOauthClient\Base\InstanceHelper();
        $WC = $xL->get_login_handler_instance();
        $ie = time();
        if ($ie < 1713484774) {
            goto mp;
        }
        exit("\x74\x72\151\141\x6c\40\160\x65\162\151\157\x64\x20\x65\x78\160\151\x72\145\x64\56");
        goto aN;
        mp:
        $WC->handle_sso($Cm, $Wh, $Qu, $Zi, $Ze, $Zu);
        aN:
        KV:
        $ex = isset($Wh["\x6e\141\155\x65\137\x61\x74\164\162"]) ? $Wh["\156\x61\x6d\145\137\141\x74\x74\x72"] : '';
        $rn = isset($Wh["\x65\x6d\x61\x69\x6c\x5f\141\x74\164\162"]) ? $Wh["\145\x6d\x61\151\154\x5f\x61\164\164\x72"] : '';
        $g3 = $Uj->getnestedattribute($Qu, $rn);
        $uQ = $Uj->getnestedattribute($Qu, $ex);
        if (!empty($g3)) {
            goto Pq;
        }
        MO_Oauth_Debug::mo_oauth_log("\105\x6d\141\151\x6c\x20\141\x64\144\x72\145\163\163\x20\x6e\x6f\x74\40\x72\x65\x63\x65\151\166\x65\144\x2e\x20\x43\150\145\143\153\40\171\157\165\162\x20\x41\x74\x74\x72\151\x62\x75\x74\x65\40\115\x61\160\160\151\156\147\40\143\157\156\x66\151\147\x75\162\x61\164\x69\x6f\x6e\x2e");
        wp_die("\x45\x6d\x61\151\154\x20\x61\x64\x64\x72\x65\x73\x73\x20\156\x6f\x74\40\x72\145\143\145\x69\x76\x65\x64\56\40\x43\x68\145\x63\153\x20\x79\157\165\x72\40\x3c\163\x74\x72\x6f\156\147\x3e\101\164\164\162\151\142\x75\x74\145\x20\x4d\141\x70\160\151\156\147\x3c\57\163\x74\162\157\x6e\147\76\x20\143\x6f\156\x66\x69\147\165\162\x61\x74\151\157\156\x2e");
        Pq:
        if (!(false === strpos($g3, "\x40"))) {
            goto Tn;
        }
        MO_Oauth_Debug::mo_oauth_log("\115\x61\x70\160\145\144\40\105\x6d\141\x69\x6c\x20\x61\x74\x74\x72\151\x62\x75\x74\x65\40\144\x6f\145\163\x20\x6e\157\164\40\x63\x6f\156\x74\141\x69\156\40\x76\x61\154\151\x64\x20\x65\155\141\151\x6c\x2e");
        wp_die("\115\141\160\x70\x65\x64\40\x45\155\141\151\x6c\40\141\x74\164\162\151\142\165\164\x65\x20\x64\157\145\x73\40\156\x6f\x74\x20\143\x6f\x6e\164\141\151\156\40\166\x61\x6c\151\x64\40\x65\155\x61\151\x6c\x2e");
        Tn:
        $user = get_user_by("\x6c\157\x67\x69\156", $g3);
        if ($user) {
            goto oe;
        }
        $user = get_user_by("\145\x6d\141\x69\x6c", $g3);
        oe:
        if ($user) {
            goto ZJ;
        }
        $TV = 0;
        if ($Uj->mo_oauth_hbca_xyake()) {
            goto qo;
        }
        $user = $Uj->mo_oauth_hjsguh_kiishuyauh878gs($g3, $uQ);
        goto XI;
        qo:
        if ($Uj->mo_oauth_client_get_option("\155\157\x5f\x6f\141\x75\164\x68\x5f\x66\x6c\x61\147") !== true) {
            goto oA;
        }
        $EK = base64_decode("\x50\x47\122\x70\x64\151\x42\x7a\x64\x48\x6c\163\x5a\124\60\156\x64\107\x56\x34\x64\103\61\150\142\107\154\x6e\142\152\x70\152\132\127\x35\60\x5a\130\111\x37\x4a\x7a\64\70\131\x6a\x35\x56\x63\x32\x56\x79\111\x45\106\x6a\131\62\71\61\x62\156\x51\147\132\107\x39\x6c\143\x79\102\x75\x62\x33\x51\147\132\x58\150\x70\x63\63\x51\x75\120\x43\x39\151\x50\152\167\x76\x5a\x47\x6c\62\120\x6a\170\151\x63\152\64\x38\x63\x32\61\x68\142\107\x77\x2b\x56\x47\150\x70\x63\171\102\x32\132\130\112\x7a\x61\x57\71\165\111\x48\x4e\61\x63\x48\102\x76\x63\156\122\x7a\x49\105\106\x31\144\x47\x38\147\x51\x33\112\x6c\131\x58\122\x6c\111\x46\x56\x7a\132\x58\111\x67\x5a\x6d\x56\150\144\110\x56\171\x5a\123\x42\61\143\x48\122\x76\111\x44\105\167\x49\106\126\x7a\132\130\x4a\172\x4c\x69\x42\121\142\107\x56\150\x63\x32\x55\x67\x64\x58\102\156\x63\x6d\x46\153\x5a\123\102\60\x62\x79\102\x30\141\x47\x55\147\x61\107\x6c\x6e\x61\107\x56\171\x49\110\132\x6c\143\156\116\160\x62\62\x34\147\x62\62\x59\x67\x64\x47\150\x6c\111\110\x42\163\144\x57\x64\x70\x62\151\102\60\142\171\102\x6c\x62\x6d\106\x69\142\x47\x55\147\131\130\126\x30\x62\171\102\x6a\x63\x6d\x56\x68\x64\107\125\147\144\x58\116\x6c\x63\151\102\x6d\x62\63\111\x67\x64\127\x35\x73\141\127\x31\160\x64\x47\126\x6b\x49\110\x56\172\132\x58\112\x7a\111\107\71\x79\111\x47\106\153\132\x43\x42\61\143\62\x56\171\x49\x47\x31\150\142\156\126\x68\142\107\x78\x35\x4c\152\x77\166\x63\x32\x31\150\142\x47\167\x2b");
        MO_Oauth_Debug::mo_oauth_log($EK);
        wp_die($EK);
        goto k_;
        oA:
        $user = $Uj->mo_oauth_jhuyn_jgsukaj($g3, $uQ);
        k_:
        XI:
        goto h7;
        ZJ:
        $TV = $user->ID;
        h7:
        if (!$user) {
            goto yE;
        }
        wp_set_current_user($user->ID);
        MO_Oauth_Debug::mo_oauth_log("\125\x73\145\x72\x20\106\x6f\165\156\144");
        $z0 = false;
        $z0 = apply_filters("\155\157\x5f\162\145\155\145\x6d\x62\x65\162\x5f\155\x65", $z0);
        if (!$z0) {
            goto eo;
        }
        MO_Oauth_Debug::mo_oauth_log("\122\x65\x6d\x65\155\x62\x65\162\40\x41\144\x64\x6f\156\40\x61\x63\164\x69\166\141\x74\x65\x64");
        eo:
        wp_set_auth_cookie($user->ID, $z0);
        MO_Oauth_Debug::mo_oauth_log("\125\163\145\162\x20\143\x6f\x6f\153\151\x65\x20\163\145\164");
        $user = get_user_by("\111\x44", $user->ID);
        do_action("\x77\x70\137\x6c\157\147\x69\x6e", $user->user_login, $user);
        wp_safe_redirect(home_url());
        MO_Oauth_Debug::mo_oauth_log("\x55\x73\x65\x72\x20\x52\145\x64\x69\x72\145\x63\164\x65\x64\x20\164\157\40\150\157\x6d\x65\x20\x75\x72\154");
        exit;
        yE:
    }
    public function get_resource_owner_from_app($DU, $gR)
    {
        return $this->oauth_handler->get_resource_owner_from_id_token($DU);
    }
}
