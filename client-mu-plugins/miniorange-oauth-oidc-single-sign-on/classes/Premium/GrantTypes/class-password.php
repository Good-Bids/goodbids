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
    const CSS_URL = MOC_URL . "\143\x6c\x61\x73\x73\145\163\57\x50\x72\145\x6d\151\165\155\x2f\162\x65\163\157\165\162\x63\x65\x73\57\160\x77\x64\x73\x74\171\154\x65\56\143\x73\x73";
    const JS_URL = MOC_URL . "\143\154\141\163\163\145\x73\57\x50\x72\145\155\x69\165\155\x2f\x72\x65\163\157\x75\x72\x63\145\x73\57\160\167\x64\56\x6a\x73";
    public function __construct($lF = false)
    {
        if (!$lF) {
            goto oZ;
        }
        return;
        oZ:
        add_action("\x69\156\151\164", array($this, "\x62\x65\x68\141\166\145"));
    }
    public function inject_ui()
    {
        global $Yh;
        wp_enqueue_style("\x77\160\55\155\x6f\55\157\x63\55\160\167\x64\55\143\163\163", self::CSS_URL, array(), $WD = null, $Zh = false);
        $ol = $Yh->parse_url($Yh->get_current_url());
        $Fj = "\x62\165\x74\164\x6f\x6e";
        if (!isset($ol["\x71\165\x65\162\x79"]["\154\157\147\151\x6e"])) {
            goto ty;
        }
        return;
        ty:
        echo "\x9\11\74\x64\151\x76\40\151\x64\x3d\x22\x70\141\x73\163\x77\x6f\x72\144\x2d\147\x72\141\x6e\164\55\155\x6f\144\141\154\42\40\x63\154\141\x73\163\75\42\x70\141\163\x73\167\157\162\144\x2d\x6d\x6f\144\141\154\x20\155\157\x5f\x74\x61\142\154\x65\137\x6c\x61\171\x6f\165\164\x22\76\xd\12\11\x9\11\x3c\x64\151\x76\x20\143\154\141\163\x73\75\x22\x70\141\x73\163\x77\157\162\144\55\155\157\144\141\154\55\143\x6f\156\x74\x65\156\164\42\76\xd\12\11\11\11\11\74\144\151\166\x20\x63\154\x61\163\163\x3d\x22\160\141\x73\163\x77\157\162\x64\x2d\x6d\157\x64\141\x6c\55\150\x65\141\x64\x65\x72\x22\x3e\xd\12\11\11\x9\x9\x9\x3c\144\x69\x76\x20\143\154\141\x73\x73\75\x22\x70\x61\x73\x73\167\x6f\162\144\x2d\x6d\157\144\141\x6c\55\150\145\141\144\x65\x72\55\x74\x69\x74\154\145\x22\x3e\xd\12\11\11\x9\x9\x9\11\x3c\163\x70\141\x6e\x20\143\154\141\x73\163\x3d\x22\160\x61\x73\x73\x77\157\x72\x64\x2d\x6d\157\144\141\x6c\x2d\x63\x6c\157\163\145\x22\76\46\164\x69\155\145\163\x3b\74\57\x73\x70\x61\156\76\xd\12\x9\x9\11\11\x9\x9\74\163\160\x61\x6e\40\x69\144\x3d\x22\x70\x61\x73\x73\x77\x6f\162\x64\x2d\x6d\157\x64\141\x6c\55\150\x65\x61\144\145\162\55\x74\151\164\154\145\x2d\x74\145\170\164\42\76\74\57\x73\160\x61\156\76\15\12\x9\11\x9\11\x9\74\57\144\x69\x76\x3e\xd\12\x9\11\11\11\x3c\x2f\x64\151\x76\76\15\12\11\11\11\11\74\x66\x6f\162\x6d\x20\x69\x64\75\42\x70\167\x64\x67\x72\156\164\146\162\155\42\76\15\12\x9\11\11\x9\11\74\x69\156\x70\x75\x74\40\x74\x79\160\145\75\x22\x68\x69\x64\x64\x65\156\42\40\x6e\141\155\145\75\42\x6c\157\x67\x69\x6e\42\40\166\141\x6c\x75\x65\75\x22\x70\167\x64\x67\x72\x6e\x74\x66\162\155\42\x3e\xd\xa\11\x9\11\x9\x9\x3c\x69\156\160\165\x74\x20\164\x79\160\x65\75\42\x74\x65\x78\164\42\40\x63\154\x61\x73\x73\75\x22\155\x6f\137\x74\141\x62\154\x65\137\164\145\170\164\x62\157\x78\42\x20\151\x64\x3d\42\x70\167\x64\x67\162\156\164\146\x72\x6d\55\165\156\155\146\154\144\x22\x20\156\x61\155\145\x3d\42\x63\x61\154\x6c\145\162\x22\x20\160\x6c\x61\143\145\x68\157\x6c\x64\145\162\75\42\x55\x73\145\162\156\141\155\x65\42\x3e\xd\12\11\x9\x9\x9\x9\74\x69\x6e\160\165\164\40\x74\x79\x70\145\x3d\42\x70\141\x73\x73\x77\157\162\144\42\x20\143\154\141\x73\163\75\x22\x6d\157\x5f\164\141\142\x6c\145\x5f\x74\x65\x78\x74\142\157\x78\x22\x20\151\144\75\42\x70\167\x64\x67\x72\156\164\146\x72\155\x2d\x70\x66\154\144\x22\x20\156\x61\x6d\145\75\42\164\157\157\x6c\42\x20\x70\154\141\143\x65\x68\157\x6c\144\145\162\x3d\x22\120\x61\x73\x73\x77\x6f\162\144\x22\x3e\xd\12\x9\x9\x9\11\11\74\x69\x6e\x70\x75\x74\x20\164\171\160\x65\75\42";
        echo esc_attr($Fj);
        echo "\x22\x20\143\154\141\163\163\x3d\x22\x62\165\x74\164\x6f\156\40\x62\x75\164\164\157\x6e\x2d\160\x72\151\x6d\141\162\171\40\142\x75\164\164\157\156\x2d\154\141\x72\147\145\42\40\x69\x64\75\x22\x70\x77\x64\x67\x72\156\x74\146\x72\x6d\x2d\x6c\157\x67\x69\156\42\40\x76\141\154\165\145\75\x22\x4c\x6f\x67\x69\156\x22\76\xd\12\x9\11\x9\11\74\57\146\157\162\155\x3e\xd\12\11\x9\x9\x3c\57\144\x69\166\x3e\15\12\x9\x9\x3c\x2f\144\151\x76\76\xd\xa\x9\x9";
    }
    public function inject_behaviour()
    {
        wp_enqueue_script("\x77\160\55\155\157\55\x6f\x63\x2d\x70\167\x64\55\x6a\x73", self::JS_URL, ["\152\x71\x75\x65\x72\x79"], $WD = null, $Zh = true);
    }
    public function behave($p1 = '', $lH = '', $zl = '', $ZG = '', $m8 = false, $lF = false)
    {
        global $Yh;
        $p1 = !empty($p1) ? hex2bin($p1) : false;
        $lH = !empty($lH) ? hex2bin($lH) : false;
        $zl = !empty($zl) ? $zl : false;
        $ZG = !empty($ZG) ? $ZG : site_url();
        if (!($lH && !$m8)) {
            goto rl;
        }
        $lH = wp_unslash($lH);
        rl:
        if (!(!$p1 || !$lH || !$zl)) {
            goto Sw;
        }
        $Yh->redirect_user(urldecode($ZG));
        exit;
        Sw:
        $F8 = $Yh->get_app_by_name($zl);
        if ($F8) {
            goto ey;
        }
        $hB = $Yh->parse_url(urldecode(site_url()));
        $hB["\x71\165\145\162\171"]["\x65\162\162\x6f\x72"] = "\x54\x68\145\162\x65\40\x69\163\x20\156\157\40\x61\160\160\x6c\151\x63\141\164\151\x6f\156\40\143\x6f\x6e\x66\x69\x67\x75\162\145\144\x20\146\157\x72\x20\164\x68\151\163\x20\x72\145\161\x75\x65\x73\x74";
        $Yh->redirect_user($Yh->generate_url($hB));
        ey:
        $KY = $F8->get_app_config();
        $uo = array("\147\x72\141\156\164\137\164\x79\x70\145" => "\x70\x61\163\163\x77\157\162\x64", "\x63\154\x69\145\x6e\x74\137\151\x64" => $KY["\x63\x6c\x69\145\156\x74\x5f\x69\144"], "\x63\x6c\x69\x65\x6e\x74\137\163\145\143\x72\x65\x74" => $KY["\143\x6c\151\145\x6e\164\137\163\145\143\162\x65\164"], "\165\x73\x65\x72\x6e\141\155\x65" => $p1, "\160\x61\163\163\x77\157\x72\x64" => $lH, "\x69\x73\137\167\160\137\154\157\147\x69\x6e" => $lF);
        $pY = new OauthHandler();
        $pd = $KY["\x61\x63\143\145\x73\163\x74\x6f\x6b\x65\156\x75\162\x6c"];
        if (!(strpos($pd, "\147\157\x6f\x67\x6c\x65") !== false)) {
            goto ls;
        }
        $pd = "\x68\164\x74\160\x73\72\x2f\x2f\x77\167\167\56\x67\157\157\147\x6c\145\x61\160\151\x73\56\143\157\x6d\57\x6f\x61\x75\164\150\x32\57\x76\x34\57\164\157\153\145\156";
        ls:
        if (!(strpos($pd, "\163\x65\x72\x76\x69\x63\145\x73\57\157\141\x75\x74\150\62\x2f\164\x6f\153\145\156") === false && strpos($pd, "\163\141\x6c\145\163\146\157\x72\143\145") === false && strpos($KY["\x61\143\143\x65\x73\x73\164\x6f\153\145\x6e\165\x72\x6c"], "\57\x6f\x61\155\57\157\x61\165\x74\x68\x32\57\141\x63\143\x65\x73\x73\137\x74\x6f\x6b\x65\x6e") === false)) {
            goto j1;
        }
        $uo["\x73\x63\x6f\x70\145"] = $F8->get_app_config("\163\x63\157\x70\x65");
        j1:
        $U_ = isset($KY["\x73\x65\156\x64\137\150\145\141\144\x65\x72\x73"]) ? $KY["\163\x65\156\x64\137\x68\145\141\x64\145\162\163"] : 0;
        $fn = isset($KY["\x73\x65\156\144\x5f\142\x6f\144\171"]) ? $KY["\x73\145\156\x64\x5f\x62\x6f\144\x79"] : 0;
        do_action("\x6d\x6f\137\x67\x65\x73\143\157\x6c\x5f\150\x61\156\144\154\x65\x72", $p1, $lH, $zl);
        $TD = $pY->get_access_token($pd, $uo, $U_, $fn);
        if (!is_wp_error($TD)) {
            goto Fb;
        }
        return $TD;
        Fb:
        MO_Oauth_Debug::mo_oauth_log("\x54\157\x6b\145\156\40\122\x65\163\160\x6f\x6e\x73\x65\x20\x52\x65\143\x65\x69\x76\x65\x64\x20\x3d\x3e\x20");
        MO_Oauth_Debug::mo_oauth_log($TD);
        if ($TD) {
            goto JK;
        }
        $N5 = new \WP_Error();
        $N5->add("\151\156\166\141\154\151\x64\137\160\x61\163\x73\x77\157\162\144", __("\x3c\163\x74\162\x6f\156\147\x3e\105\122\122\117\122\x3c\x2f\x73\x74\162\x6f\x6e\147\x3e\x3a\40\x49\156\x63\x6f\x72\162\x65\x63\164\40\105\155\141\x69\x6c\x20\141\144\144\162\x65\163\163\x20\x6f\162\x20\x50\x61\163\163\x77\157\162\x64\x2e"));
        return $N5;
        JK:
        $C2 = isset($TD["\141\143\143\x65\163\x73\137\x74\157\x6b\x65\x6e"]) ? $TD["\141\143\143\x65\163\x73\137\x74\157\x6b\145\x6e"] : false;
        $A2 = isset($TD["\151\144\137\164\x6f\153\x65\156"]) ? $TD["\151\x64\x5f\164\x6f\153\145\156"] : false;
        $KH = isset($TD["\x74\157\x6b\x65\156"]) ? $TD["\164\157\153\x65\x6e"] : false;
        $J6 = [];
        if (false !== $A2 || false !== $KH) {
            goto yu;
        }
        if ($C2) {
            goto K2;
        }
        $Yh->handle_error("\x49\156\166\x61\x6c\x69\x64\40\x74\157\153\145\156\x20\x72\145\143\145\151\x76\x65\144\56");
        MO_Oauth_Debug::mo_oauth_log("\x45\162\x72\x6f\x72\x20\x66\x72\157\x6d\x20\x54\x6f\153\145\156\x20\105\x6e\144\160\x6f\x69\x6e\164\x20\75\x3e\40\111\156\166\x61\x6c\151\144\x20\164\x6f\x6b\145\x6e\40\x72\145\x63\x65\151\166\x65\144");
        exit("\111\156\166\141\x6c\x69\144\40\164\x6f\x6b\x65\156\40\162\145\143\x65\x69\x76\x65\144\56");
        K2:
        goto Fz;
        yu:
        $oH = '';
        if (!(false !== $KH)) {
            goto mA;
        }
        $oH = "\164\157\153\145\x6e\x3d" . $KH;
        mA:
        if (!(false !== $A2)) {
            goto Ls;
        }
        $oH = "\x69\144\x5f\x74\157\x6b\145\156\x3d" . $A2;
        Ls:
        $wf = new Implicit($oH);
        if (!is_wp_error($wf)) {
            goto Kb;
        }
        $Yh->handle_error($wf->get_error_message());
        MO_Oauth_Debug::mo_oauth_log($wf->get_error_message());
        wp_die(wp_kses($wf->get_error_message(), \mo_oauth_get_valid_html()));
        exit("\120\154\x65\x61\x73\x65\40\x74\162\171\x20\x4c\157\x67\x67\151\x6e\147\40\151\x6e\x20\141\x67\141\x69\x6e\56");
        Kb:
        $gK = $wf->get_jwt_from_query_param();
        $J6 = $gK->get_decoded_payload();
        Fz:
        $pU = $KY["\162\145\163\157\x75\162\143\145\x6f\167\x6e\x65\x72\x64\x65\164\141\151\154\x73\165\x72\x6c"];
        if (!(substr($pU, -1) === "\x3d")) {
            goto Gl;
        }
        $pU .= $C2;
        Gl:
        if (!(strpos($pU, "\x67\157\x6f\147\154\x65") !== false)) {
            goto fc;
        }
        $pU = "\x68\x74\164\x70\163\72\57\x2f\167\167\x77\x2e\x67\157\x6f\147\x6c\x65\x61\x70\151\163\56\x63\x6f\155\x2f\157\x61\165\x74\x68\x32\x2f\166\61\x2f\165\x73\x65\162\151\156\x66\157";
        fc:
        if (empty($pU)) {
            goto E8;
        }
        $J6 = $pY->get_resource_owner($pU, $C2);
        E8:
        MO_Oauth_Debug::mo_oauth_log("\122\145\x73\157\x75\162\x63\x65\x20\x4f\167\x6e\145\162\x20\x3d\76\x20");
        MO_Oauth_Debug::mo_oauth_log($J6);
        $nQ = new InstanceHelper();
        $d_ = $nQ->get_login_handler_instance();
        $sU = [];
        $yi = new LoginHandler();
        $F2 = $yi->dropdownattrmapping('', $J6, $sU);
        $Yh->mo_oauth_client_update_option("\x6d\x6f\137\x6f\x61\165\164\150\137\x61\164\x74\x72\x5f\x6e\x61\x6d\x65\137\154\x69\x73\x74" . $zl, $F2);
        if (!$m8) {
            goto nn;
        }
        $d_->handle_group_test_conf($J6, $KY, $C2, false, $m8);
        exit;
        nn:
        $blog_id = get_current_blog_id();
        $eC = new StorageManager();
        $eC->add_replace_entry("\x72\145\x64\151\x72\145\143\164\137\x75\162\x69", $ZG);
        $eC->add_replace_entry("\142\154\x6f\147\137\x69\x64", $blog_id);
        $GP = $eC->get_state();
        $user = $d_->handle_sso($KY["\x61\160\x70\x49\144"], $KY, $J6, $GP, $TD, $lF);
        if (!$lF) {
            goto ZB;
        }
        return $user;
        ZB:
    }
    public function mo_oauth_wp_login($user, $WZ, $hk)
    {
        global $Yh;
        $N5 = new \WP_Error();
        if (!(empty($WZ) || empty($hk))) {
            goto Pn;
        }
        if (!empty($WZ)) {
            goto kZ;
        }
        $N5->add("\145\x6d\x70\164\x79\x5f\165\x73\x65\162\x6e\x61\x6d\145", __("\x3c\x73\164\x72\x6f\156\147\x3e\105\x52\x52\117\x52\74\x2f\163\164\x72\x6f\156\x67\76\72\40\x45\x6d\x61\x69\154\40\146\151\145\x6c\x64\40\151\x73\40\145\x6d\160\164\171\56"));
        kZ:
        if (!empty($hk)) {
            goto hM;
        }
        $N5->add("\x65\x6d\160\164\171\137\x70\x61\x73\x73\x77\x6f\162\x64", __("\74\163\x74\162\157\x6e\147\76\105\122\x52\117\x52\x3c\x2f\163\x74\162\x6f\156\147\x3e\x3a\40\x50\x61\163\x73\167\x6f\x72\144\40\x66\x69\x65\x6c\x64\40\151\163\x20\145\155\x70\164\171\56"));
        hM:
        return $N5;
        Pn:
        $zl = $Yh->mo_oauth_client_get_option("\155\157\x5f\157\141\165\x74\x68\x5f\145\156\141\142\154\145\137\157\x61\x75\x74\150\137\x77\160\x5f\154\x6f\x67\x69\x6e");
        $user = false;
        if (\username_exists($WZ)) {
            goto iy;
        }
        if (!email_exists($WZ)) {
            goto k4;
        }
        $user = get_user_by("\145\x6d\141\x69\154", $WZ);
        k4:
        goto an;
        iy:
        $user = \get_user_by("\x6c\157\147\151\x6e", $WZ);
        an:
        if (!($user && wp_check_password($hk, $user->data->user_pass, $user->ID))) {
            goto K0;
        }
        return $user;
        K0:
        if (!(false !== $zl)) {
            goto nB;
        }
        if (!($Yh->mo_oauth_aemoutcrahsaphtn() == "\x65\x6e\x61\142\154\145\x64")) {
            goto Qs;
        }
        wp_die("\123\x53\x4f\40\x69\163\40\x6e\157\164\40\167\157\x72\153\x69\156\x67\56\40\x50\154\145\x61\163\145\x20\x63\x6f\156\x74\x61\x63\164\40\x74\x68\x65\x20\141\x64\x6d\151\156\x69\163\x74\x72\141\x74\157\162\x2e");
        Qs:
        $zx = '';
        $zx = do_action("\x6d\157\137\157\141\x75\164\x68\137\143\x75\x73\x74\157\155\x5f\x73\163\x6f", \bin2hex($WZ), \bin2hex($hk), $zl, site_url(), false, true);
        if (empty($zx)) {
            goto Fj;
        }
        return $zx;
        Fj:
        return $this->behave(\bin2hex($WZ), \bin2hex($hk), $zl, site_url(), false, true);
        nB:
        $N5->add("\x69\x6e\x76\141\x6c\x69\144\137\160\141\x73\163\x77\157\162\144", __("\x3c\163\x74\x72\157\156\147\x3e\x45\x52\122\x4f\x52\74\57\163\164\x72\157\156\147\76\72\x20\125\x73\x65\x72\x6e\141\x6d\x65\x20\x6f\x72\40\x50\141\x73\163\167\x6f\162\x64\40\151\163\40\x69\x6e\166\141\154\x69\144\x2e"));
        MO_Oauth_Debug::mo_oauth_log($N5);
        return $N5;
    }
}
