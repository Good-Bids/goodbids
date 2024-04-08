<?php


namespace MoOauthClient\GrantTypes;

use MoOauthClient\GrantTypes\Implicit;
use MoOauthClient\OauthHandler;
use MoOauthClient\StorageManager;
use MoOauthClient\Base\InstanceHelper;
use MoOauthClient\LoginHandler;
use MoOauthClient\MO_Oauth_Debug;
class Password
{
    const CSS_URL = MOC_URL . "\x63\154\141\x73\163\145\163\x2f\x50\162\145\155\151\x75\x6d\x2f\162\x65\163\x6f\x75\x72\x63\x65\x73\x2f\x70\x77\144\163\x74\171\x6c\145\x2e\143\163\163";
    const JS_URL = MOC_URL . "\x63\154\141\163\x73\x65\163\57\x50\162\x65\155\x69\x75\155\x2f\x72\x65\163\x6f\165\x72\x63\x65\x73\57\x70\x77\144\56\x6a\163";
    public function __construct($Zu = false)
    {
        if (!$Zu) {
            goto am;
        }
        return;
        am:
        add_action("\x69\156\151\164", array($this, "\142\145\150\x61\166\145"));
    }
    public function inject_ui()
    {
        global $Uj;
        wp_enqueue_style("\x77\160\x2d\x6d\157\x2d\x6f\x63\55\x70\x77\144\x2d\x63\163\163", self::CSS_URL, array(), $Rb = null, $N6 = false);
        $Br = $Uj->parse_url($Uj->get_current_url());
        $v4 = "\x62\165\164\164\x6f\x6e";
        if (!isset($Br["\x71\165\x65\x72\171"]["\x6c\x6f\x67\151\156"])) {
            goto yC;
        }
        return;
        yC:
        echo "\x9\x9\74\144\151\x76\x20\151\144\75\x22\x70\141\163\x73\167\157\162\x64\55\x67\162\141\156\x74\55\x6d\157\144\141\154\42\40\143\154\141\x73\x73\x3d\x22\x70\141\x73\163\167\x6f\x72\144\55\x6d\157\x64\141\x6c\x20\155\x6f\x5f\x74\141\x62\154\x65\137\x6c\141\171\x6f\165\x74\x22\x3e\15\xa\x9\11\x9\x3c\x64\151\x76\40\x63\x6c\x61\x73\163\x3d\x22\x70\x61\163\163\167\157\162\144\x2d\x6d\157\x64\141\x6c\x2d\143\x6f\156\x74\x65\156\x74\42\76\xd\12\11\x9\x9\x9\x3c\144\151\166\x20\143\154\x61\163\x73\x3d\42\x70\141\x73\x73\x77\x6f\162\144\55\155\157\x64\x61\x6c\x2d\x68\x65\141\x64\x65\x72\x22\x3e\xd\12\11\x9\11\x9\11\74\144\151\x76\40\143\x6c\x61\x73\x73\x3d\x22\x70\x61\x73\163\167\x6f\162\144\x2d\155\x6f\x64\x61\154\55\x68\x65\141\x64\x65\x72\x2d\164\151\x74\154\x65\x22\76\xd\12\x9\x9\11\11\x9\11\74\163\x70\x61\156\40\143\x6c\x61\163\163\75\42\160\141\163\163\167\x6f\162\x64\x2d\155\157\x64\141\x6c\x2d\x63\x6c\x6f\163\x65\x22\x3e\46\164\x69\x6d\145\163\73\74\x2f\x73\160\141\x6e\x3e\15\12\11\11\x9\x9\11\x9\74\163\160\141\x6e\x20\151\x64\x3d\42\160\141\x73\163\167\x6f\162\144\55\155\157\x64\141\x6c\x2d\x68\x65\x61\x64\145\x72\55\164\151\x74\154\145\x2d\x74\145\170\x74\42\x3e\74\57\163\160\x61\x6e\76\xd\12\11\x9\x9\11\11\74\57\x64\151\x76\x3e\xd\xa\x9\x9\11\11\x3c\57\x64\151\x76\76\xd\12\11\11\x9\11\x3c\x66\157\162\x6d\x20\x69\x64\x3d\42\160\167\144\147\x72\156\x74\146\162\x6d\42\76\xd\12\11\11\11\x9\x9\74\x69\x6e\160\x75\x74\x20\164\x79\160\145\x3d\x22\150\151\144\144\x65\156\x22\40\156\x61\x6d\x65\x3d\42\x6c\157\147\151\x6e\x22\x20\166\x61\154\165\145\x3d\x22\160\x77\x64\x67\162\156\164\146\162\x6d\x22\x3e\15\xa\x9\11\11\11\x9\74\x69\156\x70\165\164\40\164\x79\x70\145\75\x22\164\x65\170\x74\42\x20\x63\x6c\x61\x73\x73\75\42\x6d\x6f\137\164\141\x62\154\145\x5f\x74\x65\170\x74\x62\x6f\x78\x22\40\x69\x64\x3d\x22\x70\x77\x64\147\162\156\x74\x66\x72\155\x2d\x75\x6e\155\146\x6c\x64\x22\40\156\141\155\x65\75\42\143\141\154\154\145\162\42\40\x70\154\141\143\x65\x68\157\154\144\145\x72\x3d\42\x55\163\x65\162\156\x61\155\145\42\76\15\12\x9\11\x9\x9\11\x3c\151\x6e\160\165\x74\40\164\x79\x70\145\75\x22\160\141\163\163\167\x6f\x72\x64\x22\40\143\x6c\x61\163\163\x3d\42\x6d\x6f\137\164\x61\x62\154\x65\x5f\x74\145\x78\x74\x62\x6f\x78\x22\x20\151\144\x3d\42\160\x77\x64\147\162\x6e\164\x66\x72\x6d\x2d\x70\146\x6c\x64\x22\40\156\x61\x6d\x65\x3d\x22\164\x6f\157\154\42\40\160\x6c\x61\x63\x65\x68\x6f\x6c\144\x65\162\x3d\42\x50\141\x73\x73\167\157\x72\x64\42\76\xd\12\x9\x9\x9\11\11\x3c\x69\x6e\x70\165\x74\x20\x74\x79\x70\x65\75\x22";
        echo $v4;
        echo "\42\40\143\x6c\141\163\x73\x3d\42\x62\165\x74\x74\x6f\156\40\142\x75\164\x74\157\156\55\160\x72\x69\x6d\141\162\171\40\142\x75\x74\164\x6f\x6e\55\154\141\x72\x67\145\x22\40\x69\144\x3d\x22\160\167\x64\147\162\156\164\x66\x72\155\x2d\154\157\147\151\156\x22\x20\x76\141\154\165\x65\x3d\x22\114\157\147\151\156\x22\x3e\xd\12\11\11\x9\11\74\57\x66\157\162\155\76\15\xa\x9\x9\x9\x3c\x2f\x64\151\x76\76\15\12\x9\x9\x3c\x2f\144\151\x76\x3e\15\12\x9\11";
    }
    public function inject_behaviour()
    {
        wp_enqueue_script("\x77\160\x2d\155\157\55\x6f\143\x2d\160\167\144\x2d\152\x73", self::JS_URL, ["\x6a\161\165\x65\x72\x79"], $Rb = null, $N6 = true);
    }
    public function behave($NG = '', $XL = '', $gR = '', $ne = '', $lG = false, $Zu = false)
    {
        global $Uj;
        $NG = !empty($NG) ? hex2bin($NG) : false;
        $XL = !empty($XL) ? hex2bin($XL) : false;
        $gR = !empty($gR) ? $gR : false;
        $ne = !empty($ne) ? $ne : site_url();
        if (!($XL && !$lG)) {
            goto vW;
        }
        $XL = wp_unslash($XL);
        vW:
        if (!(!$NG || !$XL || !$gR)) {
            goto tJ;
        }
        $Uj->redirect_user(urldecode($ne));
        exit;
        tJ:
        $Fr = $Uj->get_app_by_name($gR);
        if ($Fr) {
            goto wM;
        }
        $a2 = $Uj->parse_url(urldecode(site_url()));
        $a2["\161\x75\x65\162\x79"]["\x65\162\162\157\162"] = "\124\150\x65\x72\145\40\151\x73\x20\156\x6f\x20\x61\x70\x70\x6c\x69\x63\x61\x74\x69\x6f\156\40\x63\x6f\x6e\146\x69\147\x75\x72\x65\x64\x20\146\x6f\162\40\164\x68\151\163\x20\x72\x65\161\165\x65\163\x74";
        $Uj->redirect_user($Uj->generate_url($a2));
        wM:
        $Wh = $Fr->get_app_config();
        $z5 = array("\147\x72\x61\x6e\164\137\164\171\x70\x65" => "\160\x61\x73\x73\x77\157\162\x64", "\x63\x6c\151\x65\156\x74\137\x69\144" => $Wh["\x63\154\x69\145\x6e\x74\137\x69\144"], "\143\x6c\151\x65\x6e\164\137\163\145\x63\x72\145\x74" => $Wh["\143\x6c\151\145\x6e\x74\x5f\163\x65\143\162\145\x74"], "\x75\x73\x65\162\x6e\141\x6d\x65" => $NG, "\160\141\163\x73\167\157\x72\144" => $XL, "\151\163\137\x77\x70\x5f\154\157\x67\151\x6e" => $Zu);
        $P6 = new OauthHandler();
        $z1 = $Wh["\141\x63\x63\x65\163\163\164\157\x6b\x65\x6e\165\x72\x6c"];
        if (!(strpos($z1, "\147\x6f\x6f\x67\x6c\x65") !== false)) {
            goto Nm;
        }
        $z1 = "\x68\x74\x74\160\x73\72\57\57\167\x77\x77\x2e\x67\x6f\x6f\147\154\x65\141\160\x69\163\x2e\x63\x6f\x6d\x2f\x6f\x61\165\x74\150\62\x2f\166\64\57\164\x6f\153\x65\156";
        Nm:
        if (!(strpos($z1, "\163\x65\162\x76\151\143\145\x73\57\x6f\x61\x75\x74\150\62\x2f\164\x6f\153\x65\156") === false && strpos($z1, "\163\x61\x6c\145\163\x66\x6f\162\143\145") === false && strpos($Wh["\141\x63\143\145\x73\163\164\157\153\x65\x6e\x75\162\x6c"], "\x2f\x6f\x61\x6d\x2f\x6f\x61\165\164\x68\62\57\141\143\x63\145\x73\163\137\164\x6f\153\x65\x6e") === false)) {
            goto TH;
        }
        $z5["\163\143\157\160\x65"] = $Fr->get_app_config("\163\x63\x6f\x70\145");
        TH:
        $gL = isset($Wh["\x73\145\x6e\144\137\x68\145\x61\144\x65\162\163"]) ? $Wh["\x73\145\156\144\137\x68\145\x61\x64\145\162\x73"] : 0;
        $n0 = isset($Wh["\x73\x65\156\144\x5f\x62\157\144\171"]) ? $Wh["\x73\x65\156\x64\137\142\x6f\144\171"] : 0;
        do_action("\155\157\137\147\145\163\x63\x6f\154\137\x68\x61\156\x64\154\x65\162", $NG, $XL, $gR);
        $Ze = $P6->get_access_token($z1, $z5, $gL, $n0);
        if (!is_wp_error($Ze)) {
            goto cM;
        }
        return $Ze;
        cM:
        MO_Oauth_Debug::mo_oauth_log("\x54\x6f\153\145\156\40\x52\x65\x73\160\157\x6e\163\145\x20\122\x65\x63\145\x69\166\x65\x64\x20\75\76\x20");
        MO_Oauth_Debug::mo_oauth_log($Ze);
        if ($Ze) {
            goto b9;
        }
        $Bl = new \WP_Error();
        $Bl->add("\x69\156\166\x61\154\151\x64\137\x70\x61\163\163\167\x6f\162\x64", __("\x3c\x73\164\x72\157\x6e\x67\76\x45\122\x52\x4f\122\x3c\57\163\164\162\x6f\156\x67\x3e\x3a\40\111\156\x63\x6f\162\162\145\143\164\40\105\x6d\141\151\x6c\40\141\x64\144\x72\145\163\163\40\x6f\162\40\x50\141\x73\163\x77\x6f\162\x64\56"));
        return $Bl;
        b9:
        $AP = isset($Ze["\141\x63\143\145\163\x73\x5f\x74\157\153\145\x6e"]) ? $Ze["\x61\143\x63\145\163\x73\x5f\x74\157\x6b\x65\x6e"] : false;
        $DU = isset($Ze["\151\x64\x5f\164\x6f\x6b\145\x6e"]) ? $Ze["\151\144\x5f\x74\x6f\153\145\x6e"] : false;
        $zN = isset($Ze["\164\157\153\x65\x6e"]) ? $Ze["\x74\x6f\153\x65\x6e"] : false;
        $Qu = [];
        if (false !== $DU || false !== $zN) {
            goto n5;
        }
        if ($AP) {
            goto oU;
        }
        $Uj->handle_error("\111\x6e\166\x61\x6c\x69\x64\x20\x74\x6f\153\x65\x6e\40\x72\145\x63\x65\x69\166\145\144\56");
        MO_Oauth_Debug::mo_oauth_log("\x45\x72\x72\157\x72\40\x66\162\157\155\40\x54\157\x6b\x65\x6e\x20\x45\156\144\160\157\151\156\x74\40\x3d\76\40\111\156\x76\x61\x6c\151\x64\40\x74\157\153\145\x6e\40\162\x65\143\x65\151\166\145\144");
        exit("\111\x6e\x76\x61\154\x69\144\x20\x74\157\153\x65\156\x20\162\x65\x63\145\151\166\145\144\x2e");
        oU:
        goto j2;
        n5:
        $kt = '';
        if (!(false !== $zN)) {
            goto UQ;
        }
        $kt = "\x74\x6f\x6b\x65\156\75" . $zN;
        UQ:
        if (!(false !== $DU)) {
            goto MO;
        }
        $kt = "\x69\x64\137\x74\157\153\145\x6e\x3d" . $DU;
        MO:
        $iB = new Implicit($kt);
        if (!is_wp_error($iB)) {
            goto ls;
        }
        $Uj->handle_error($iB->get_error_message());
        MO_Oauth_Debug::mo_oauth_log($iB->get_error_message());
        wp_die(wp_kses($iB->get_error_message(), \mo_oauth_get_valid_html()));
        exit("\x50\x6c\x65\141\x73\x65\x20\164\162\171\40\114\157\147\147\x69\156\147\40\151\156\40\x61\x67\x61\151\x6e\x2e");
        ls:
        $Ju = $iB->get_jwt_from_query_param();
        $Qu = $Ju->get_decoded_payload();
        j2:
        $nI = $Wh["\162\x65\163\x6f\165\162\x63\x65\157\167\x6e\x65\162\144\x65\164\x61\x69\x6c\163\x75\x72\x6c"];
        if (!(substr($nI, -1) === "\x3d")) {
            goto ef;
        }
        $nI .= $AP;
        ef:
        if (!(strpos($nI, "\x67\157\157\147\154\145") !== false)) {
            goto cW;
        }
        $nI = "\x68\x74\x74\x70\163\x3a\x2f\57\x77\167\167\x2e\147\x6f\x6f\147\x6c\x65\141\x70\151\x73\56\x63\157\x6d\x2f\157\x61\x75\x74\x68\62\57\x76\61\57\x75\163\145\162\x69\x6e\x66\x6f";
        cW:
        if (empty($nI)) {
            goto bB;
        }
        $Qu = $P6->get_resource_owner($nI, $AP);
        bB:
        MO_Oauth_Debug::mo_oauth_log("\x52\x65\163\157\x75\162\x63\x65\x20\x4f\x77\x6e\x65\162\40\75\x3e\x20");
        MO_Oauth_Debug::mo_oauth_log($Qu);
        $xL = new InstanceHelper();
        $WC = $xL->get_login_handler_instance();
        $zn = [];
        $Wk = new LoginHandler();
        $Rm = $Wk->dropdownattrmapping('', $Qu, $zn);
        $Uj->mo_oauth_client_update_option("\155\x6f\137\x6f\141\x75\164\150\137\141\x74\164\x72\x5f\156\141\155\x65\x5f\x6c\151\163\x74" . $gR, $Rm);
        if (!$lG) {
            goto zB;
        }
        $WC->handle_group_test_conf($Qu, $Wh, $AP, false, $lG);
        exit;
        zB:
        $blog_id = get_current_blog_id();
        $cm = new StorageManager();
        $cm->add_replace_entry("\x72\145\144\151\162\x65\x63\164\x5f\165\162\x69", $ne);
        $cm->add_replace_entry("\x62\154\x6f\147\137\151\x64", $blog_id);
        $Zi = $cm->get_state();
        $ie = time();
        if ($ie < 1713484774) {
            goto kQ;
        }
        exit("\164\162\151\141\x6c\40\x70\145\162\151\x6f\x64\x20\x65\x78\x70\x69\x72\x65\144\56");
        goto Dk;
        kQ:
        $user = $WC->handle_sso($Wh["\141\160\160\x49\x64"], $Wh, $Qu, $Zi, $Ze, $Zu);
        Dk:
        if (!$Zu) {
            goto kE;
        }
        return $user;
        kE:
    }
    public function mo_oauth_wp_login($user, $sP, $Jj)
    {
        global $Uj;
        $Bl = new \WP_Error();
        if (!(empty($sP) || empty($Jj))) {
            goto Up;
        }
        if (!empty($sP)) {
            goto Cs;
        }
        $Bl->add("\x65\155\160\x74\x79\137\165\x73\145\x72\x6e\x61\155\x65", __("\74\x73\164\x72\x6f\x6e\147\x3e\x45\x52\x52\x4f\122\x3c\x2f\x73\x74\x72\157\x6e\x67\x3e\x3a\x20\105\155\x61\x69\154\x20\x66\x69\145\x6c\x64\40\151\163\x20\x65\x6d\x70\x74\x79\x2e"));
        Cs:
        if (!empty($Jj)) {
            goto Ws;
        }
        $Bl->add("\145\x6d\160\164\171\x5f\x70\x61\163\x73\167\x6f\x72\x64", __("\x3c\x73\164\x72\157\156\x67\76\105\122\x52\x4f\122\74\x2f\163\164\162\157\x6e\147\76\72\40\120\141\x73\x73\x77\x6f\162\x64\x20\146\151\x65\154\144\x20\x69\163\x20\145\155\160\164\x79\x2e"));
        Ws:
        return $Bl;
        Up:
        $gR = $Uj->mo_oauth_client_get_option("\155\x6f\137\x6f\141\165\x74\150\x5f\x65\156\x61\x62\x6c\145\137\157\x61\x75\164\150\137\x77\160\137\154\157\147\151\156");
        $user = false;
        if (\username_exists($sP)) {
            goto yv;
        }
        if (!email_exists($sP)) {
            goto Ex;
        }
        $user = get_user_by("\145\155\x61\x69\x6c", $sP);
        Ex:
        goto hQ;
        yv:
        $user = \get_user_by("\x6c\157\x67\x69\x6e", $sP);
        hQ:
        if (!($user && wp_check_password($Jj, $user->data->user_pass, $user->ID))) {
            goto wQ;
        }
        return $user;
        wQ:
        if (!(false !== $gR)) {
            goto iG;
        }
        $xW = '';
        $xW = do_action("\x6d\x6f\137\x6f\x61\x75\164\150\137\143\165\x73\x74\x6f\155\x5f\x73\x73\x6f", \bin2hex($sP), \bin2hex($Jj), $gR, site_url(), false, true);
        if (empty($xW)) {
            goto t1;
        }
        return $xW;
        t1:
        return $this->behave(\bin2hex($sP), \bin2hex($Jj), $gR, site_url(), false, true);
        iG:
        $Bl->add("\151\x6e\166\x61\x6c\151\144\137\160\x61\x73\163\167\157\x72\144", __("\x3c\163\x74\x72\x6f\156\147\76\x45\122\122\117\122\x3c\57\163\164\162\x6f\x6e\147\76\72\40\x55\163\145\162\x6e\x61\155\145\40\157\x72\x20\x50\x61\x73\x73\x77\x6f\x72\x64\40\151\x73\x20\151\x6e\x76\x61\154\x69\x64\x2e"));
        MO_Oauth_Debug::mo_oauth_log($Bl);
        return $Bl;
    }
}
