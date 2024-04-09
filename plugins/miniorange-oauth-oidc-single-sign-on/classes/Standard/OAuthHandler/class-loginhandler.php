<?php


namespace MoOauthClient\Standard;

use MoOauthClient\LoginHandler as FreeLoginHandler;
use MoOauthClient\Config;
use MoOauthClient\StorageManager;
use MoOauthClient\MO_Oauth_Debug;
class LoginHandler extends FreeLoginHandler
{
    public $config;
    public function handle_group_test_conf($J6 = array(), $KY = array(), $C2 = '', $q_ = false, $RV = false)
    {
        global $Yh;
        MO_Oauth_Debug::mo_oauth_log("\107\x72\157\165\x70\x20\165\x73\145\x72\x20\x65\156\144\x70\x6f\x69\156\164\x20\143\157\156\x66\151\147\x75\162\145\x64\x2e");
        $this->render_test_config_output($J6, false);
        if (!(!isset($KY["\147\162\x6f\x75\x70\x64\x65\164\x61\x69\154\163\x75\162\154"]) || '' === $KY["\x67\162\x6f\x75\160\x64\x65\x74\x61\151\154\x73\165\162\154"])) {
            goto VmM;
        }
        return;
        VmM:
        $YK = [];
        $c0 = $KY["\147\162\x6f\165\160\x64\145\x74\141\x69\x6c\x73\x75\x72\x6c"];
        if (!(strpos($c0, "\x61\160\151\56\143\154\145\166\145\162\56\143\157\155") != false && isset($J6["\x64\x61\164\x61"]["\x69\144"]))) {
            goto Xha;
        }
        $c0 = str_replace("\x75\x73\145\x72\151\x64", $J6["\x64\x61\x74\x61"]["\151\x64"], $c0);
        Xha:
        MO_Oauth_Debug::mo_oauth_log("\107\x72\x6f\x75\x70\x20\x44\145\x74\141\x69\x6c\x73\x20\x55\x52\x4c\x3a\x20" . $c0);
        if (!('' === $C2)) {
            goto xeQ;
        }
        if (has_filter("\x6d\x6f\x5f\x6f\x61\x75\164\150\137\x63\146\x61\137\x67\162\x6f\165\160\137\144\x65\164\x61\151\154\163")) {
            goto uiL;
        }
        MO_Oauth_Debug::mo_oauth_log("\101\143\143\x65\x73\x73\x20\x54\x6f\153\145\156\x20\x45\155\x70\164\171");
        return;
        uiL:
        xeQ:
        if (!('' !== $c0)) {
            goto ztk;
        }
        if (has_filter("\155\x6f\x5f\x6f\141\x75\x74\x68\137\143\146\141\x5f\147\x72\x6f\165\x70\137\x64\x65\164\141\x69\154\163")) {
            goto F8f;
        }
        if (has_filter("\155\x6f\137\157\141\x75\164\x68\x5f\x67\x72\x6f\x75\x70\137\144\145\x74\141\151\154\163")) {
            goto jem;
        }
        if (has_filter("\155\x6f\137\x6f\141\x75\164\x68\137\162\x61\166\145\156\x5f\x67\x72\x6f\x75\160\137\x64\x65\164\x61\151\x6c\163")) {
            goto v_V;
        }
        if (has_filter("\x6d\x6f\x5f\x6f\x61\165\x74\x68\137\x6e\157\x76\x69\141\155\163\x5f\147\162\157\165\x70\x5f\144\x65\164\x61\x69\x6c\163")) {
            goto GEm;
        }
        $YK = $this->oauth_handler->get_resource_owner($c0, $C2);
        goto tBT;
        GEm:
        $YK = apply_filters("\155\157\137\157\141\x75\164\150\137\x6e\x6f\x76\151\141\155\x73\x5f\x67\x72\157\x75\x70\x5f\144\145\164\x61\x69\x6c\x73", $J6, $c0);
        tBT:
        goto CA3;
        v_V:
        $YK = apply_filters("\155\x6f\x5f\157\x61\165\x74\x68\137\x72\x61\x76\x65\156\x5f\x67\162\157\165\x70\x5f\x64\145\164\141\151\154\x73", $J6["\x65\x6d\x61\151\x6c"], $c0, $C2, $KY, $q_);
        CA3:
        goto twg;
        jem:
        $YK = apply_filters("\x6d\157\x5f\x6f\141\165\x74\x68\137\147\162\x6f\x75\x70\x5f\144\x65\x74\141\151\154\x73", $c0, $C2, $KY, $q_);
        twg:
        goto KF0;
        F8f:
        MO_Oauth_Debug::mo_oauth_log("\x46\x65\x74\143\150\151\x6e\147\40\x43\106\101\x20\x47\x72\x6f\165\x70\x2e\56");
        $YK = apply_filters("\x6d\x6f\137\157\141\165\x74\150\x5f\x63\x66\x61\x5f\x67\162\157\x75\x70\137\x64\x65\164\x61\x69\154\163", $J6, $c0, $C2, $KY, $q_);
        KF0:
        MO_Oauth_Debug::mo_oauth_log("\x47\x72\x6f\x75\x70\x20\104\x65\164\141\x69\154\163\x20\75\76\40");
        MO_Oauth_Debug::mo_oauth_log($YK);
        $sU = $Yh->mo_oauth_client_get_option("\x6d\x6f\137\157\x61\x75\164\x68\x5f\x61\164\x74\162\137\x6e\x61\155\x65\137\154\x69\x73\x74" . $KY["\x61\160\160\x49\x64"]);
        $sl = [];
        $F2 = $this->dropdownattrmapping('', $YK, $sl);
        $sU = (array) $sU + $F2;
        $Yh->mo_oauth_client_update_option("\x6d\157\x5f\x6f\141\165\x74\x68\x5f\x61\164\x74\x72\137\x6e\x61\x6d\x65\137\154\x69\x73\164" . $KY["\x61\x70\x70\111\144"], $sU);
        if (!($RV && '' !== $RV)) {
            goto q_9;
        }
        if (!(is_array($YK) && !empty($YK))) {
            goto RCC;
        }
        $this->render_test_config_output($YK, true);
        RCC:
        return;
        q_9:
        ztk:
    }
    public function handle_group_user_info($J6, $KY, $C2)
    {
        MO_Oauth_Debug::mo_oauth_log("\x47\x72\157\165\160\x20\165\163\145\162\x20\145\156\x64\x70\x6f\151\156\164\x20\143\x6f\156\146\151\x67\165\x72\145\x64\56");
        if (!(!isset($KY["\x67\162\157\165\160\x64\x65\164\x61\x69\154\x73\x75\x72\154"]) || '' === $KY["\x67\x72\157\x75\x70\x64\145\164\x61\151\x6c\x73\165\x72\x6c"])) {
            goto PU4;
        }
        return $J6;
        PU4:
        $c0 = $KY["\147\x72\x6f\x75\x70\x64\x65\x74\x61\151\x6c\x73\165\x72\x6c"];
        if (!(strpos($c0, "\141\x70\151\56\x63\x6c\145\x76\x65\x72\x2e\x63\157\155") != false && isset($J6["\x64\141\x74\x61"]["\151\144"]))) {
            goto HDg;
        }
        $c0 = str_replace("\165\x73\145\x72\x69\144", $J6["\144\x61\164\x61"]["\151\144"], $c0);
        HDg:
        if (!('' === $C2)) {
            goto VGN;
        }
        return $J6;
        VGN:
        $YK = array();
        if (!('' !== $c0)) {
            goto svh;
        }
        if (has_filter("\155\157\137\157\141\165\164\x68\137\x63\146\x61\x5f\x67\162\x6f\165\x70\137\144\145\x74\141\x69\154\163")) {
            goto Clp;
        }
        if (has_filter("\x6d\x6f\x5f\x6f\141\165\164\150\137\x67\162\x6f\165\x70\137\144\x65\164\141\151\154\163")) {
            goto OQi;
        }
        if (has_filter("\155\157\137\157\141\x75\164\150\137\162\141\166\x65\156\137\x67\162\157\165\x70\x5f\144\x65\164\x61\x69\x6c\x73")) {
            goto TQJ;
        }
        if (has_filter("\x6d\x6f\x5f\x6f\141\165\x74\x68\x5f\156\x6f\x76\151\x61\155\163\137\147\162\x6f\165\160\137\x64\145\x74\x61\151\154\163")) {
            goto rgk;
        }
        $YK = $this->oauth_handler->get_resource_owner($c0, $C2);
        goto X0n;
        rgk:
        $YK = apply_filters("\x6d\157\137\157\x61\x75\164\x68\x5f\156\x6f\166\151\x61\155\x73\137\147\162\157\x75\x70\137\x64\145\164\141\151\x6c\163", $J6, $c0);
        X0n:
        goto dlA;
        TQJ:
        $YK = apply_filters("\x6d\x6f\137\157\141\165\164\x68\137\162\141\166\x65\x6e\137\147\x72\x6f\165\x70\137\144\x65\x74\141\151\x6c\x73", $J6["\145\x6d\x61\x69\154"], $c0, $C2, $KY, $q_);
        dlA:
        goto H9e;
        OQi:
        $YK = apply_filters("\155\157\x5f\157\x61\x75\x74\150\137\x67\x72\x6f\165\160\137\144\x65\164\x61\x69\x6c\x73", $c0, $C2, $KY);
        H9e:
        goto RRj;
        Clp:
        MO_Oauth_Debug::mo_oauth_log("\106\x65\x74\x63\150\x69\156\x67\x20\103\106\x41\x20\107\x72\x6f\165\x70\x2e\56");
        $YK = apply_filters("\155\157\137\x6f\x61\165\164\150\x5f\x63\146\x61\x5f\147\162\157\x75\x70\137\x64\145\x74\141\x69\154\163", $J6, $c0, $C2, $KY, $q_);
        RRj:
        svh:
        MO_Oauth_Debug::mo_oauth_log("\x47\162\x6f\x75\160\40\x44\145\164\141\151\x6c\x73\x20\75\76\40");
        MO_Oauth_Debug::mo_oauth_log($YK);
        if (!(is_array($YK) && count($YK) > 0)) {
            goto rJF;
        }
        $J6 = array_merge($J6, $YK);
        rJF:
        MO_Oauth_Debug::mo_oauth_log("\122\x65\163\157\165\162\x63\x65\40\x4f\x77\x6e\145\162\x20\101\x66\x74\145\x72\40\x6d\145\x72\x67\151\156\147\40\x77\x69\x74\150\x20\107\162\x6f\165\160\x20\144\145\164\x69\x61\154\x73\x20\x3d\x3e\x20");
        MO_Oauth_Debug::mo_oauth_log($J6);
        return $J6;
    }
    public function mo_oauth_client_map_default_role($QH, $KY)
    {
        MO_Oauth_Debug::mo_oauth_log("\x4f\x6e\154\171\40\x64\145\x66\141\x75\154\164\40\x72\157\x6c\x65\x20\155\x61\160\x70\145\x64");
        $wi = new \WP_User($QH);
        if (!(isset($KY["\145\x6e\141\x62\x6c\145\137\162\x6f\x6c\x65\x5f\155\141\160\x70\x69\156\x67"]) && !boolval($KY["\145\156\141\142\x6c\x65\137\162\x6f\x6c\x65\x5f\x6d\x61\160\160\151\x6e\147"]))) {
            goto ntP;
        }
        $wi->set_role('');
        return;
        ntP:
        if (!(isset($KY["\x5f\155\x61\160\x70\x69\x6e\x67\x5f\x76\141\154\165\x65\x5f\x64\145\146\141\165\x6c\x74"]) && '' !== $KY["\137\x6d\x61\x70\x70\x69\x6e\147\x5f\166\141\154\165\145\x5f\x64\145\146\141\165\154\x74"])) {
            goto mWr;
        }
        $Gc = explode("\x2c", $KY["\137\x6d\141\160\160\x69\x6e\x67\137\x76\141\x6c\x75\145\137\144\145\x66\141\165\x6c\x74"]);
        $Ei = 0;
        foreach ($Gc as $bz) {
            if (!(0 === $Ei)) {
                goto wbE;
            }
            $wi->set_role('');
            wbE:
            $bz = trim($bz);
            $wi->add_role($bz);
            $Ei++;
            H7p:
        }
        yye:
        mWr:
    }
    public function handle_sso($fZ, $KY, $J6, $GP, $TD, $lF = false)
    {
        global $Yh;
        $eC = new StorageManager($GP);
        do_action("\155\x6f\137\157\141\x75\x74\x68\x5f\154\x69\156\153\x5f\x64\x69\163\x63\x6f\162\144\137\x61\x63\x63\157\x75\x6e\x74", $eC, $J6);
        $sb = isset($KY["\x75\163\x65\162\x6e\x61\x6d\x65\137\141\x74\x74\162"]) ? $KY["\x75\163\145\x72\x6e\141\x6d\x65\x5f\x61\x74\x74\x72"] : '';
        $YR = isset($KY["\x65\155\141\151\x6c\x5f\x61\164\164\162"]) ? $KY["\145\x6d\141\x69\154\x5f\141\x74\164\162"] : '';
        $T8 = isset($KY["\146\151\x72\x73\x74\x6e\x61\155\x65\137\141\x74\x74\x72"]) ? $KY["\146\x69\162\x73\164\156\x61\x6d\x65\x5f\x61\x74\164\162"] : '';
        $BX = isset($KY["\154\x61\x73\164\156\141\155\x65\137\x61\164\x74\x72"]) ? $KY["\154\x61\x73\164\156\141\155\x65\137\x61\164\x74\162"] : '';
        $jO = isset($KY["\144\151\163\160\x6c\141\x79\x5f\141\x74\164\162"]) ? $KY["\x64\x69\163\x70\154\x61\171\x5f\x61\x74\164\x72"] : '';
        $WZ = $Yh->getnestedattribute($J6, $sb);
        $Mv = $Yh->getnestedattribute($J6, $YR);
        $tP = $Yh->getnestedattribute($J6, $T8);
        $rs = $Yh->getnestedattribute($J6, $BX);
        $o4 = $WZ;
        $this->config = $Yh->mo_oauth_client_get_option("\155\157\x5f\157\141\165\164\x68\x5f\x63\x6c\x69\x65\x6e\164\137\x63\x6f\x6e\x66\151\147");
        $this->config = !$this->config || empty($this->config) ? array() : $this->config->get_current_config();
        $Kf = isset($this->config["\141\143\x74\x69\x76\141\164\145\137\165\163\145\x72\137\141\156\141\154\x79\x74\x69\143\x73"]) ? $this->config["\141\143\164\x69\x76\x61\x74\145\x5f\x75\x73\145\162\x5f\141\x6e\x61\154\171\x74\x69\x63\x73"] : 0;
        $current_user = wp_get_current_user();
        if (!($current_user->ID !== 0)) {
            goto Erb;
        }
        do_action("\x6d\x6f\x5f\x6f\x61\x75\x74\150\x5f\x64\151\x73\x63\x6f\x72\x64\137\x66\x6c\157\167\137\150\x61\156\144\x6c\145", $current_user, $TD, $J6);
        do_action("\x6d\157\137\x6f\141\165\164\150\137\x6c\157\x67\147\x65\144\137\x69\x6e\137\x75\x73\145\x72\x5f\164\141\147\137\x75\160\144\x61\164\x65", $current_user, $TD, $J6);
        $fH = get_option("\x6d\x6f\137\x64\x72\155\x5f\163\171\156\143\x5f\x72\145\x64\x69\162\145\143\164");
        if (!(isset($fH) && $fH)) {
            goto YFD;
        }
        wp_redirect($fH);
        exit;
        YFD:
        Erb:
        if (empty($jO)) {
            goto JWX;
        }
        switch ($jO) {
            case "\x46\116\x41\x4d\x45":
                $o4 = $tP;
                goto Sea;
            case "\114\x4e\x41\x4d\105":
                $o4 = $rs;
                goto Sea;
            case "\x55\123\x45\122\x4e\101\115\105":
                $o4 = $WZ;
                goto Sea;
            case "\106\116\101\x4d\105\137\x4c\116\x41\115\x45":
                $o4 = $tP . "\40" . $rs;
                goto Sea;
            case "\114\x4e\x41\115\x45\137\106\116\101\115\x45":
                $o4 = $rs . "\40" . $tP;
            default:
                goto Sea;
        }
        i4x:
        Sea:
        JWX:
        if (!empty($WZ)) {
            goto ADn;
        }
        MO_Oauth_Debug::mo_oauth_log("\125\x73\145\x72\x6e\x61\155\x65\x20\72\40" . $WZ);
        $this->check_status(array("\155\163\x67" => "\x55\x73\x65\162\x6e\x61\x6d\x65\40\x6e\x6f\x74\40\x72\145\143\x65\x69\166\x65\144\56\x20\103\x68\145\143\153\x20\171\157\165\162\40\x3c\x73\164\x72\x6f\x6e\147\x3e\x41\164\x74\x72\151\142\x75\164\145\40\115\x61\x70\x70\x69\x6e\147\74\x2f\163\164\162\157\x6e\147\x3e\x20\x63\157\x6e\x66\x69\147\x75\x72\x61\164\x69\157\x6e\x2e", "\143\x6f\144\145" => "\x55\116\101\x4d\x45", "\x73\x74\141\x74\x75\x73" => false, "\141\x70\x70\154\x69\143\141\x74\x69\x6f\156" => $fZ, "\145\x6d\x61\151\x6c" => '', "\x75\x73\x65\162\156\x61\x6d\145" => ''), $Kf);
        ADn:
        if (!(!empty($Mv) && false === strpos($Mv, "\100"))) {
            goto up0;
        }
        $this->check_status(array("\155\x73\x67" => "\115\141\x70\160\x65\x64\40\x45\x6d\141\x69\x6c\x20\x61\x74\164\x72\151\x62\165\x74\x65\40\144\157\x65\x73\x20\156\157\164\x20\x63\157\156\164\141\151\x6e\40\x76\x61\x6c\151\x64\x20\x65\155\x61\151\154\x2e", "\143\x6f\x64\x65" => "\105\x4d\101\111\x4c", "\x73\x74\x61\164\x75\163" => false, "\x61\160\x70\154\151\x63\141\164\x69\157\x6e" => $fZ, "\143\x6c\x69\x65\x6e\x74\x5f\x69\160" => $Yh->get_client_ip(), "\x65\x6d\141\x69\154" => $Mv, "\165\163\145\x72\156\x61\155\x65" => $WZ), $Kf);
        up0:
        if (!is_multisite()) {
            goto Jzu;
        }
        $blog_id = $eC->get_value("\142\154\157\x67\137\x69\144");
        switch_to_blog($blog_id);
        do_action("\x6d\x6f\137\157\141\165\x74\150\137\143\x6c\151\145\156\164\x5f\x63\x6f\156\143\157\162\x64\x5f\x72\x65\x73\164\x72\151\143\x74\137\x6c\157\147\151\x6e", $KY, $J6, $blog_id);
        Jzu:
        if (!has_action("\x6d\157\x5f\157\141\165\164\150\137\143\x6c\151\145\x6e\x74\137\163\141\x76\145\x5f\x75\x73\145\162\x5f\151\x6e\137\x73\145\x73\x73\x69\x6f\x6e")) {
            goto zfb;
        }
        do_action("\155\x6f\x5f\157\x61\165\164\x68\137\143\154\151\145\156\x74\137\x73\x61\166\x65\137\165\x73\145\162\137\x69\x6e\137\x73\x65\163\163\x69\157\156", array("\141\160\x70\137\x63\x6f\x6e\146\x69\147" => $KY, "\163\164\157\162\x61\147\145\x5f\x6d\141\156\x61\147\x65\162" => $eC, "\x72\x65\x73\x6f\x75\162\143\145\137\x6f\167\x6e\145\162" => $J6, "\x75\163\x65\x72\x6e\x61\x6d\x65" => $WZ));
        zfb:
        do_action("\155\157\x5f\157\141\165\164\150\137\x72\x65\163\164\x72\151\x63\x74\137\145\x6d\x61\x69\154\x73", $Mv, $this->config);
        if (!has_filter("\x6d\157\137\157\x61\165\x74\150\x5f\x6d\157\144\151\146\x79\137\165\163\145\x72\x6e\141\x6d\145\137\141\164\x74\x72")) {
            goto CDE;
        }
        $WZ = apply_filters("\x6d\157\x5f\x6f\141\165\x74\150\x5f\x6d\x6f\x64\151\x66\x79\137\165\x73\145\x72\x6e\141\x6d\x65\137\x61\164\164\162", $J6);
        CDE:
        if (!has_filter("\x6d\x6f\137\x6f\x61\x75\164\150\137\x63\x68\x65\143\153\137\x75\163\x65\162\x5f\x61\165\x74\150\x6f\162\151\163\x65\144")) {
            goto Mzj;
        }
        $WZ = apply_filters("\x6d\x6f\x5f\157\x61\x75\164\x68\x5f\x63\x68\145\143\153\137\x75\x73\145\162\137\x61\x75\164\150\x6f\162\151\x73\145\144", $J6, $WZ);
        Mzj:
        $user = get_user_by("\154\157\x67\151\x6e", $WZ);
        $pv = isset($KY["\141\154\154\157\x77\137\x64\x75\x70\x6c\151\143\x61\x74\x65\137\x65\155\x61\x69\x6c\x73"]) ? true : false;
        if ($user) {
            goto J2R;
        }
        if (!(!$pv || $pv && !$KY["\141\154\x6c\x6f\167\137\x64\165\x70\154\151\x63\141\164\145\137\145\155\141\x69\x6c\x73"])) {
            goto YQT;
        }
        $user = get_user_by("\145\x6d\x61\x69\154", $Mv);
        YQT:
        J2R:
        $QH = $user ? $user->ID : 0;
        $L_ = 0 === $QH;
        if (!has_filter("\x6d\157\x5f\157\x61\x75\164\150\137\147\x65\164\x5f\165\163\145\162\x5f\142\x79\x5f\x65\155\141\151\x6c")) {
            goto BDK;
        }
        $user = apply_filters("\155\x6f\x5f\x6f\x61\165\164\150\x5f\x67\x65\164\137\165\163\145\162\137\142\171\137\145\x6d\x61\x69\x6c", $WZ, $Mv);
        BDK:
        if (!has_filter("\x6d\x6f\x5f\157\x61\165\164\x68\x5f\143\150\145\143\x6b\x5f\165\163\145\162\x5f\x62\171\x5f\x65\155\141\x69\x6c")) {
            goto x20;
        }
        $L_ = apply_filters("\x6d\157\137\x6f\141\165\164\150\137\143\x68\145\x63\153\137\165\x73\x65\162\137\x62\171\x5f\145\x6d\x61\151\x6c", $WZ, $Mv);
        x20:
        $QH = $user ? $user->ID : 0;
        if (!(isset($KY["\141\165\x74\x6f\143\162\145\x61\x74\145\165\163\145\x72\x73"]) && 1 !== intval($KY["\141\x75\x74\x6f\143\x72\145\141\164\x65\165\x73\x65\x72\x73"]))) {
            goto e0E;
        }
        $blog_id = 1;
        $Sl = apply_filters("\155\x6f\x5f\x6f\x61\x75\164\x68\137\x63\154\151\x65\x6e\x74\137\144\x69\163\141\x62\x6c\x65\x5f\x61\x75\164\x6f\137\x63\162\x65\x61\x74\145\137\165\163\145\162\x73\x5f\x66\157\162\137\x73\x70\x65\143\151\146\151\x63\137\x69\144\x70", $QH, $blog_id, $this->config, $KY);
        $this->config = $Sl[0];
        $KY = $Sl[1];
        e0E:
        if (!(!(isset($this->config["\x61\165\x74\157\137\162\x65\147\x69\x73\164\145\x72"]) && 1 === intval($this->config["\141\165\x74\x6f\x5f\162\145\x67\151\x73\x74\145\x72"])) && $L_)) {
            goto F5S;
        }
        MO_Oauth_Debug::mo_oauth_log("\x52\x65\x67\x69\163\164\x72\141\x74\x69\157\x6e\x20\x69\163\40\x64\151\x73\141\x62\x6c\x65\x64\x20\146\157\162\40\164\x68\151\x73\40\x73\151\x74\145\56\40\x50\x6c\145\x61\163\145\40\x63\150\x65\143\153\x20\101\165\x74\157\40\x72\145\147\x69\163\x74\145\162\40\x75\x73\145\162\40\163\145\x74\x74\151\156\x67\x73\x2e\x20");
        $this->check_status(array("\x6d\163\x67" => "\x52\x65\147\x69\163\164\162\x61\164\151\157\156\x20\151\163\40\144\151\x73\x61\x62\x6c\x65\144\40\x66\x6f\162\x20\164\150\x69\x73\40\163\151\x74\x65\56\40\120\x6c\145\141\x73\145\x20\x63\157\156\164\141\143\164\x20\171\x6f\x75\x72\40\x61\144\155\151\156\151\163\x74\162\141\x74\157\162", "\143\157\x64\145" => "\x52\x45\x47\x49\123\124\x52\101\x54\x49\117\x4e\x5f\x44\111\123\x41\102\x4c\x45\104", "\163\x74\141\164\165\163" => false, "\141\x70\x70\x6c\x69\143\x61\164\151\x6f\156" => $fZ, "\143\x6c\151\x65\x6e\x74\x5f\151\x70" => $Yh->get_client_ip(), "\145\155\x61\151\x6c" => $Mv, "\x75\x73\x65\162\x6e\x61\x6d\145" => $WZ), $Kf);
        F5S:
        if (!$L_) {
            goto QLN;
        }
        $qM = 10;
        $E4 = false;
        $Hp = false;
        $Wb = apply_filters("\155\x6f\x5f\x6f\141\165\164\x68\x5f\x70\141\163\163\167\157\x72\x64\137\x70\x6f\x6c\151\x63\x79\x5f\x6d\x61\x6e\x61\x67\145\162", $qM);
        if (!is_array($Wb)) {
            goto Jk6;
        }
        $qM = intval($Wb["\160\x61\163\x73\167\x6f\x72\x64\x5f\x6c\145\156\147\x74\x68"]);
        $E4 = $Wb["\163\x70\x65\143\x69\141\x6c\x5f\x63\x68\x61\162\x61\x63\164\x65\x72\163"];
        $Hp = $Wb["\145\x78\x74\x72\x61\x5f\x73\160\x65\x63\x69\x61\154\x5f\143\150\x61\x72\x61\x63\164\x65\x72\x73"];
        Jk6:
        $E3 = wp_generate_password($qM, $E4, $Hp);
        $DN = get_user_by("\145\155\x61\x69\154", $Mv);
        if (!$DN) {
            goto iyQ;
        }
        add_filter("\160\162\145\137\165\163\145\x72\137\145\x6d\141\x69\154", array($this, "\163\x6b\x69\x70\x5f\x65\155\x61\151\x6c\x5f\145\170\x69\x73\164"), 30);
        iyQ:
        $WZ = apply_filters("\155\x6f\137\157\141\165\164\x68\x5f\x67\x65\x74\x5f\x75\163\145\162\x6e\141\155\145\x5f\x77\x69\164\x68\x5f\x70\157\x73\x74\x66\x69\x78\137\x61\x64\144\145\144", $WZ, $Mv);
        $QH = wp_create_user($WZ, $E3, $Mv);
        if (!is_wp_error($QH)) {
            goto OcL;
        }
        MO_Oauth_Debug::mo_oauth_log("\105\x72\x72\x6f\x72\x20\x63\x72\145\x61\x74\x69\x6e\x67\40\127\120\40\165\x73\x65\x72");
        wp_die("\x3c\142\76\105\162\162\157\162\72\x3c\57\142\x3e\x20\125\x73\x65\162\x6e\141\x6d\x65\40\x63\x6f\x6e\164\141\151\x6e\163\x20\163\160\x65\x63\151\141\x6c\40\x63\x68\x61\x72\x61\x63\x74\145\162\163\56\x20\x50\154\145\x61\163\x65\40\x63\157\156\x74\x61\x63\x74\x20\x79\x6f\165\162\40\141\x64\155\151\x6e\x69\x73\164\162\x61\x74\157\162\56");
        goto cJd;
        OcL:
        MO_Oauth_Debug::mo_oauth_log("\116\x65\167\40\165\163\145\x72\x20\x63\x72\145\141\164\x65\144\x20\75\x3e");
        MO_Oauth_Debug::mo_oauth_log("\125\163\x65\x72\x20\x49\104\40\x3d\x3e\40" . $QH);
        cJd:
        $Ry = array("\111\104" => $QH, "\x75\163\145\162\137\145\x6d\141\151\154" => $Mv, "\x75\163\145\x72\137\154\x6f\x67\151\x6e" => $WZ, "\x75\x73\x65\162\137\x6e\151\143\145\156\x61\155\145" => $WZ);
        do_action("\165\x73\145\162\x5f\162\x65\147\x69\163\164\145\162", $QH, $Ry);
        QLN:
        if (!($L_ || (!isset($this->config["\153\145\145\x70\137\145\170\x69\x73\164\x69\156\147\x5f\x75\163\145\x72\163"]) || 1 !== intval($this->config["\x6b\x65\x65\160\x5f\145\x78\x69\x73\164\x69\156\147\137\x75\x73\145\x72\x73"])))) {
            goto A_s;
        }
        if (!is_wp_error($QH)) {
            goto XM5;
        }
        if (!get_user_by("\x6c\x6f\147\x69\156", $WZ)) {
            goto Gak;
        }
        $QH = get_user_by("\x6c\x6f\x67\151\156", $WZ)->ID;
        Gak:
        XM5:
        $no = array("\x49\x44" => $QH, "\146\x69\x72\x73\x74\137\x6e\141\x6d\145" => $tP, "\154\141\x73\164\x5f\x6e\141\155\145" => $rs, "\x64\x69\163\x70\x6c\141\171\137\156\x61\155\145" => $o4, "\x75\163\x65\162\x5f\x6c\157\x67\x69\x6e" => $WZ, "\165\x73\x65\x72\x5f\156\x69\x63\145\156\141\155\x65" => $WZ);
        if (isset($this->config["\153\145\x65\x70\137\145\170\151\163\164\x69\x6e\147\x5f\x65\155\141\151\154\137\141\x74\164\x72"]) && 1 === intval($this->config["\x6b\145\145\160\137\145\170\151\x73\x74\151\x6e\x67\x5f\145\x6d\x61\151\x6c\x5f\141\164\164\162"])) {
            goto Moy;
        }
        $no["\165\163\x65\x72\137\x65\155\141\x69\x6c"] = $Mv;
        wp_update_user($no);
        MO_Oauth_Debug::mo_oauth_log("\101\x74\x74\162\x69\142\x75\x74\145\x20\115\x61\x70\x70\x69\156\147\x20\x44\x6f\x6e\145");
        goto SZ4;
        Moy:
        wp_update_user($no);
        MO_Oauth_Debug::mo_oauth_log("\x41\x74\x74\x72\151\142\x75\164\145\40\x4d\141\x70\160\151\156\x67\x20\165\x70\x64\141\x74\x65\144\40\x65\x78\143\145\160\x74\x20\x65\x6d\141\151\154\56");
        SZ4:
        if (!isset($J6["\x73\x75\142"])) {
            goto Zd8;
        }
        update_user_meta($QH, "\155\157\137\x62\141\143\153\x63\150\141\156\156\145\154\137\x61\164\x74\x72\x5f\x73\x75\142", $J6["\x73\165\x62"]);
        Zd8:
        if (!isset($J6["\163\x69\x64"])) {
            goto g8Y;
        }
        update_user_meta($QH, "\x6d\x6f\137\x62\x61\x63\x6b\143\150\141\156\156\145\x6c\137\141\x74\164\162\x5f\163\x69\x64", $J6["\163\x69\x64"]);
        g8Y:
        update_user_meta($QH, "\x6d\157\137\x6f\x61\x75\164\150\137\x62\165\x64\144\171\160\162\145\163\163\137\141\x74\164\162\x69\x62\165\164\145\x73", $J6);
        MO_Oauth_Debug::mo_oauth_log("\102\x75\144\x64\x79\x50\x72\x65\163\163\x20\141\x74\x74\x72\x69\x62\x75\x74\x65\x73\x20\165\x70\x64\x61\x74\x65\144\40\x73\x75\143\143\x65\163\x73\146\x75\x6c\x6c\171");
        A_s:
        $user = get_user_by("\111\x44", $QH);
        MO_Oauth_Debug::mo_oauth_log("\125\163\x65\162\x20\106\157\x75\156\x64");
        MO_Oauth_Debug::mo_oauth_log("\125\x73\145\x72\40\x49\x44\40\75\x3e\x20" . $QH);
        $jj = $Yh->is_multisite_plan();
        if (!is_multisite()) {
            goto sB5;
        }
        MO_Oauth_Debug::mo_oauth_log("\115\165\x6c\164\151\163\x69\x74\x65\x20\120\x6c\141\156");
        $W_ = $Yh->mo_oauth_client_get_option("\155\157\137\157\141\165\x74\x68\x5f\143\63\x56\x69\143\x32\x6c\60\132\x58\x4e\172\132\x57\170\154\x59\x33\122\x6c\x5a\101");
        $ZU = array();
        if (!isset($W_)) {
            goto xPd;
        }
        $ZU = json_decode($Yh->mooauthdecrypt($W_), true);
        xPd:
        $EL = false;
        if (!(is_array($ZU) && in_array($blog_id, $ZU))) {
            goto h5s;
        }
        $EL = true;
        h5s:
        $Jf = intval($Yh->mo_oauth_client_get_option("\156\x6f\x4f\x66\x53\165\142\123\151\164\x65\163"));
        $H0 = get_sites(["\x6e\165\155\142\145\x72" => 1000]);
        if (!(is_multisite() && $jj && ($jj && !$EL && $Jf < 1000))) {
            goto pC2;
        }
        $N5 = "\x59\157\x75\x20\150\141\x76\145\x20\x6e\x6f\x74\x20\x75\160\x67\x72\141\x64\145\144\x20\164\x6f\x20\x74\x68\145\x20\x63\x6f\x72\162\145\143\164\40\154\151\x63\x65\156\163\145\40\160\154\x61\x6e\56\40\x45\x69\164\150\x65\x72\x20\x79\x6f\165\x20\x68\141\166\x65\x20\160\x75\162\x63\150\141\x73\145\x64\40\146\x6f\x72\x20\x69\x6e\143\157\x72\162\145\143\164\40\x6e\x6f\x2e\x20\x6f\x66\x20\163\151\164\x65\163\x20\157\162\x20\171\157\x75\40\x68\141\x76\145\x20\143\x72\145\141\x74\145\144\40\x61\40\x6e\x65\x77\40\163\x75\x62\163\151\164\145\x2e\x20\x43\x6f\156\164\141\143\164\40\164\x6f\x20\x79\x6f\x75\162\x20\141\144\155\151\x6e\151\163\x74\162\x61\x74\x6f\162\40\x74\157\40\165\160\x67\x72\x61\144\x65\x20\x79\157\165\162\40\163\165\x62\163\x69\164\x65\x2e";
        MO_Oauth_Debug::mo_oauth_log($N5);
        $Yh->handle_error($N5);
        wp_die($N5);
        pC2:
        sB5:
        if ($user) {
            goto ARz;
        }
        return;
        ARz:
        $cF = '';
        if (isset($this->config["\141\x66\x74\x65\x72\x5f\154\157\x67\x69\156\x5f\x75\x72\154"]) && '' !== $this->config["\x61\x66\x74\145\162\137\154\x6f\x67\x69\156\137\x75\162\x6c"]) {
            goto U8l;
        }
        $Y7 = $eC->get_value("\162\145\144\151\162\x65\143\164\137\x75\162\x69");
        $V0 = parse_url($Y7);
        MO_Oauth_Debug::mo_oauth_log("\x52\x65\144\151\162\145\143\x74\x20\125\162\154\40\x63\157\155\x70\x6f\x6e\145\156\164\163\40\x3d\x3e");
        MO_Oauth_Debug::mo_oauth_log($V0);
        if (!(isset($V0["\160\141\x74\x68"]) && strpos($V0["\x70\141\x74\x68"], "\167\160\55\x6c\x6f\x67\x69\x6e\x2e\160\x68\160") !== false)) {
            goto JZ0;
        }
        $Y7 = site_url();
        JZ0:
        if (!isset($V0["\161\x75\145\x72\171"])) {
            goto beV;
        }
        parse_str($V0["\161\x75\x65\162\171"], $oH);
        if (!isset($oH["\x72\145\x64\151\x72\145\x63\164\137\164\157"])) {
            goto XBz;
        }
        $Y7 = $oH["\x72\x65\x64\151\162\145\x63\164\x5f\164\x6f"];
        XBz:
        beV:
        $cF = rawurldecode($Y7 && '' !== $Y7 ? $Y7 : site_url());
        MO_Oauth_Debug::mo_oauth_log("\x43\x75\x73\164\157\x6d\x20\x55\x52\x4c\x20\x61\146\164\145\162\40\x6c\157\x67\x69\156\40\144\x65\146\x61\165\154\x74\40\x3d\x3e" . $cF);
        $jY = isset($this->config["\x77\150\x69\x74\145\154\151\x73\164\137\x72\x65\144\151\x72\x65\143\x74\x5f\165\x72\x6c"]) ? $this->config["\x77\150\151\x74\x65\x6c\151\x73\x74\137\162\x65\144\151\x72\x65\x63\164\137\x75\x72\x6c"] : '';
        $EL = false;
        $fL = isset($this->config["\167\x68\151\x74\x65\154\x69\163\x74\137\x72\x65\144\x69\162\145\143\x74\137\x75\162\154\x73"]) ? $this->config["\x77\x68\x69\164\145\154\151\x73\x74\137\162\145\144\x69\162\x65\143\x74\137\165\162\154\x73"] : '';
        if (!(!empty($fL) && isset($fL) && isset($jY) && !empty($jY))) {
            goto XQM;
        }
        $VC = explode("\x3b", $fL);
        MO_Oauth_Debug::mo_oauth_log("\x57\x68\151\x74\x65\x6c\151\x73\164\x20\122\x65\144\151\x72\x65\x63\x74\x20\x55\x72\154\163\40\x3d\x3e");
        MO_Oauth_Debug::mo_oauth_log($VC);
        $cF = strtolower($cF);
        $cF = trim(trim($cF), "\57");
        MO_Oauth_Debug::mo_oauth_log("\123\123\x4f\40\122\145\x64\x69\162\x65\x63\164\x20\x55\x72\x6c\x3d\x3e");
        MO_Oauth_Debug::mo_oauth_log($cF);
        foreach ($VC as $os) {
            $os = strtolower($os);
            if (!ctype_space(substr($os, -1))) {
                goto OlA;
            }
            $os = substr_replace($os, '', -1);
            OlA:
            $os = trim(trim($os), "\57");
            if (!(substr($os, -1) == "\x2a")) {
                goto wxA;
            }
            $os = substr_replace($os, '', -1);
            $os = trim($os, "\57");
            if (!(strpos($cF, $os) !== false)) {
                goto c9w;
            }
            $EL = true;
            goto XcP;
            c9w:
            wxA:
            if (!(strcmp($cF, $os) == 0)) {
                goto UBf;
            }
            $EL = true;
            goto XcP;
            UBf:
            nVE:
        }
        XcP:
        if ($EL) {
            goto WNQ;
        }
        $cF = home_url();
        WNQ:
        XQM:
        if (!(strpos($cF, "\155\x6f\137\x63\x68\141\156\147\145\137\164\157\137\x68\141\x73\150") !== false)) {
            goto RQj;
        }
        $cF = str_replace("\155\x6f\x5f\143\150\x61\x6e\147\x65\137\x74\157\x5f\150\x61\x73\x68", "\43", $cF);
        RQj:
        if (!has_filter("\x6d\x6f\x5f\x6f\141\165\164\x68\137\x64\x69\163\x5f\165\160\144\x61\164\145\x5f\x61\x63\164\x75\x61\x6c\137\154\151\x6e\153")) {
            goto lCS;
        }
        $cF = apply_filters("\155\x6f\x5f\x6f\141\x75\x74\x68\x5f\144\151\163\x5f\x75\x70\x64\x61\x74\x65\137\x61\x63\x74\165\x61\x6c\137\154\151\x6e\x6b", $cF, $WZ);
        lCS:
        goto L82;
        U8l:
        $cF = $this->config["\x61\x66\164\145\162\137\154\157\x67\151\156\x5f\x75\162\154"];
        MO_Oauth_Debug::mo_oauth_log("\103\x75\163\x74\157\155\40\x55\x52\x4c\x20\141\x66\x74\145\162\40\154\x6f\147\x69\156\x20\143\157\x6e\x66\x69\x67\165\x72\145\x64\x2e\x20\x3d\76" . $cF);
        L82:
        if (!($Yh->get_versi() === 1)) {
            goto fAn;
        }
        if (isset($KY["\x65\156\141\142\x6c\x65\137\162\157\154\145\x5f\x6d\141\160\160\x69\156\x67"])) {
            goto ydv;
        }
        $KY["\x65\x6e\x61\142\x6c\145\x5f\x72\x6f\154\145\137\155\141\x70\160\151\156\147"] = true;
        if (!(isset($KY["\x63\x6c\x69\x65\x6e\164\137\x63\x72\x65\144\163\137\145\x6e\143\162\x70\x79\164\x65\144"]) && boolval($KY["\x63\x6c\x69\x65\x6e\164\137\143\x72\x65\x64\163\137\x65\x6e\x63\x72\160\x79\164\x65\144"]))) {
            goto xjY;
        }
        $KY["\x63\x6c\151\x65\156\164\x5f\x69\144"] = $Yh->mooauthencrypt($KY["\x63\x6c\151\145\156\x74\137\151\144"]);
        $KY["\143\x6c\x69\x65\156\164\x5f\163\x65\x63\162\145\x74"] = $Yh->mooauthencrypt($KY["\x63\x6c\x69\145\156\164\137\x73\x65\143\162\145\164"]);
        xjY:
        $Yh->set_app_by_name($uo["\x61\160\x70\137\x6e\141\155\x65"], $KY);
        ydv:
        if (!(!user_can($QH, "\141\144\155\151\156\151\x73\164\162\x61\x74\157\x72") && $L_ || !isset($KY["\x6b\145\145\160\x5f\145\170\151\x73\164\151\156\x67\137\165\x73\145\162\137\x72\157\154\145\163"]) || 1 !== intval($KY["\x6b\145\145\x70\137\145\x78\x69\163\x74\x69\156\x67\x5f\x75\x73\145\x72\x5f\x72\157\x6c\145\x73"]))) {
            goto mIs;
        }
        $this->mo_oauth_client_map_default_role($QH, $KY);
        MO_Oauth_Debug::mo_oauth_log("\122\157\x6c\x65\40\x4d\141\x70\x70\151\x6e\147\x20\104\157\156\x65");
        mIs:
        fAn:
        do_action("\x6d\157\x5f\x6f\x61\x75\164\150\x5f\143\154\x69\x65\156\x74\x5f\155\x61\x70\x5f\162\x6f\x6c\145\163", array("\165\x73\145\162\137\x69\x64" => $QH, "\x61\160\160\137\143\x6f\156\x66\151\147" => $KY, "\156\145\167\137\165\163\x65\x72" => $L_, "\x72\x65\163\157\x75\162\x63\x65\137\x6f\167\x6e\x65\162" => $J6, "\141\160\160\137\156\x61\x6d\x65" => $fZ, "\x63\157\156\x66\x69\x67" => $this->config));
        MO_Oauth_Debug::mo_oauth_log("\x52\157\x6c\x65\x20\x4d\141\160\x70\151\156\147\x20\104\x6f\156\145");
        do_action("\155\x6f\137\157\x61\165\x74\x68\x5f\x6c\x6f\147\147\145\x64\137\x69\x6e\x5f\x75\163\145\x72\x5f\x74\157\x6b\x65\x6e", $user, $TD);
        do_action("\155\x6f\x5f\157\141\165\x74\150\137\x61\x64\x64\x5f\144\x69\163\x5f\165\x73\x65\x72\x5f\x73\x65\162\166\x65\162", $QH, $TD, $J6);
        $this->check_status(array("\155\163\147" => "\114\x6f\147\x69\156\x20\123\165\x63\143\145\x73\x73\146\x75\154\41", "\143\157\x64\145" => "\x4c\x4f\x47\111\x4e\137\123\x55\103\103\x45\x53\123", "\x73\164\x61\x74\x75\163" => true, "\x61\x70\x70\x6c\x69\143\x61\x74\151\x6f\156" => $fZ, "\x63\x6c\151\x65\156\x74\137\x69\x70" => $Yh->get_client_ip(), "\156\141\166\x69\147\141\164\151\x6f\x6e\165\x72\154" => $cF, "\x65\155\141\x69\x6c" => $Mv, "\165\163\145\162\156\141\x6d\145" => $WZ), $Kf);
        if (!$lF) {
            goto xHX;
        }
        return $user;
        xHX:
        do_action("\x6d\157\x5f\157\x61\165\x74\150\x5f\x73\145\164\x5f\154\157\147\151\156\x5f\x63\x6f\157\153\151\145");
        do_action("\x6d\157\137\x6f\x61\165\x74\150\137\147\145\164\x5f\165\163\145\x72\137\141\164\x74\162\163", $user, $J6);
        update_user_meta($user->ID, "\155\157\137\x6f\x61\165\164\x68\137\143\x6c\151\145\156\164\x5f\154\x61\163\164\137\151\x64\x5f\x74\157\153\x65\x6e", isset($TD["\151\x64\x5f\x74\x6f\x6b\145\x6e"]) ? $TD["\x69\144\137\x74\x6f\153\145\x6e"] : $TD["\x61\x63\143\145\x73\163\x5f\164\x6f\153\145\156"]);
        wp_set_current_user($user->ID);
        $Et = false;
        $Et = apply_filters("\x6d\157\137\162\x65\x6d\x65\x6d\x62\145\162\x5f\155\x65", $Et);
        wp_set_auth_cookie($user->ID, $Et);
        MO_Oauth_Debug::mo_oauth_log("\x57\120\40\117\x41\x75\x74\x68\40\143\157\157\153\x69\145\x20\x73\x65\164\x74\145\x64\x2e");
        if (!isset($J6["\x72\157\154\x65\163"])) {
            goto GJ7;
        }
        apply_filters("\x6d\157\x5f\x6f\x61\x75\x74\150\137\165\x70\144\x61\164\145\137\142\x62\x70\x5f\162\x6f\154\x65", $user->ID, $J6["\x72\157\154\x65\x73"]);
        GJ7:
        if (!has_action("\x6d\x6f\137\x68\141\x63\x6b\x5f\x6c\x6f\x67\x69\x6e\x5f\x73\145\163\x73\x69\x6f\x6e\137\162\x65\144\151\x72\x65\x63\x74")) {
            goto Ary;
        }
        $KH = $Yh->gen_rand_str();
        $hk = $Yh->gen_rand_str();
        $Wb = array("\x75\x73\x65\162\x5f\x69\x64" => $user->ID, "\165\x73\145\x72\x5f\x70\x61\x73\x73\167\x6f\x72\144" => $hk);
        set_transient($KH, $Wb);
        do_action("\155\157\x5f\x68\x61\143\x6b\137\x6c\x6f\147\151\x6e\137\163\x65\x73\x73\x69\x6f\x6e\x5f\162\145\x64\x69\x72\x65\x63\164", $user, $hk, $KH, $cF);
        Ary:
        do_action("\x77\x70\137\x6c\157\x67\151\x6e", $user->user_login, $user);
        MO_Oauth_Debug::mo_oauth_log("\x55\163\145\x72\40\x6c\x6f\x67\x67\x65\x64\40\151\156");
        setcookie("\x6d\157\137\x6f\141\165\x74\150\x5f\x6c\x6f\147\x69\156\137\141\x70\x70\137\x73\x65\163\x73\151\157\156", $fZ, time() + 120, "\x2f", null, true, true);
        do_action("\155\157\x5f\157\141\165\164\x68\137\x67\x65\x74\137\x74\x6f\x6b\x65\x6e\x5f\x66\157\162\137\x68\x65\141\144\x6c\x65\163\x73", $user, $TD, $cF);
        do_action("\155\x6f\137\x6f\x61\165\164\x68\137\x67\x65\x74\137\x63\165\162\162\x65\156\164\x5f\x61\x70\x70\x6e\141\x6d\145", $fZ);
        $tA = $eC->get_value("\x72\x65\163\164\162\x69\x63\x74\x72\145\x64\151\x72\145\143\x74") !== false;
        $zY = $eC->get_value("\160\x6f\160\165\160") === "\x69\x67\156\157\x72\x65";
        if (isset($this->config["\160\157\160\x75\x70\x5f\x6c\x6f\x67\x69\156"]) && 1 === intval($this->config["\x70\x6f\160\165\160\137\154\157\x67\x69\x6e"]) && !$zY && !boolval($tA)) {
            goto Ot_;
        }
        do_action("\155\x6f\137\x6f\141\x75\x74\x68\137\x72\145\x64\151\x72\x65\x63\x74\x5f\157\141\x75\x74\150\x5f\x75\163\x65\x72\163", $user, $cF);
        MO_Oauth_Debug::mo_oauth_log("\x55\x73\x65\162\40\162\145\x64\151\162\145\x63\x74\x65\x64\40\x74\x6f\40\75\76" . $cF);
        wp_redirect($cF);
        goto aBJ;
        Ot_:
        MO_Oauth_Debug::mo_oauth_log("\x4f\x70\145\x6e\145\144\40\154\x6f\147\151\x6e\40\167\151\x6e\x64\157\x77\40\x69\x6e\40\x70\x6f\160\165\x70\x2e");
        if (!has_filter("\155\x6f\137\157\x61\x75\164\150\x5f\x72\x65\x64\151\162\145\143\x74\x5f\x6f\x61\165\164\150\137\x75\163\x65\162\x73")) {
            goto YWT;
        }
        $cF = apply_filters("\155\157\137\x6f\x61\165\164\150\137\162\x65\x64\x69\162\145\143\164\137\x6f\x61\x75\164\x68\137\x75\x73\x65\x72\x73", $user, $cF);
        YWT:
        MO_Oauth_Debug::mo_oauth_log("\125\x73\145\162\40\162\x65\144\x69\x72\145\x63\x74\x65\x64\x20\164\157\40\x3d\76" . $cF);
        echo "\74\163\143\x72\x69\160\x74\76\xd\xa\11\11\x9\151\x66\x20\x28\167\x69\156\144\157\x77\56\x6f\160\145\156\x65\162\x20\46\x26\40\x74\171\160\x65\157\x66\x20\167\151\x6e\144\157\167\56\x6f\x70\145\156\x65\162\x2e\x48\141\156\144\x6c\x65\120\157\x70\165\160\x52\x65\163\165\x6c\x74\40\75\x3d\75\40\x22\146\x75\x6e\x63\164\x69\157\156\42\x29\x20\173\xd\xa\x9\11\11\11\x77\151\x6e\x64\157\167\56\x6f\x70\x65\156\145\162\56\x48\141\156\x64\154\145\120\157\x70\165\160\x52\145\x73\165\154\164\50\x22" . $cF . "\42\x29\73\x77\x69\x6e\x64\157\167\56\143\154\157\163\145\x28\x29\73\xd\xa\11\x9\x9\x7d\40\x65\154\x73\145\x20\173\xd\12\11\x9\x9\x9\167\x69\x6e\144\x6f\x77\x2e\x6c\x6f\x63\x61\x74\151\x6f\156\x2e\162\x65\x70\x6c\141\x63\145\50\42" . $cF . "\x22\x29\73\xd\xa\11\11\x9\175\xd\xa\11\11\11\x3c\x2f\x73\143\x72\151\160\164\76";
        aBJ:
        exit;
    }
    public function check_status($uo, $Kf)
    {
        global $Yh;
        if (isset($uo["\163\164\x61\x74\x75\163"])) {
            goto y30;
        }
        MO_Oauth_Debug::mo_oauth_log("\123\157\155\x65\164\x68\151\156\147\x20\x77\145\156\164\x20\x77\162\x6f\156\147\x2e\x20\120\x6c\145\141\163\145\x20\x74\x72\171\40\114\x6f\x67\x67\151\x6e\147\40\151\156\40\x61\x67\141\x69\x6e\56");
        $Yh->handle_error("\x53\157\155\145\164\150\x69\156\147\x20\x77\145\156\x74\40\x77\162\x6f\x6e\147\56\x20\120\x6c\x65\x61\163\145\40\164\162\171\x20\x4c\157\x67\147\x69\x6e\x67\x20\x69\x6e\40\x61\147\141\151\x6e\56");
        wp_die(wp_kses("\x53\x6f\x6d\145\x74\x68\x69\x6e\147\40\x77\x65\156\x74\40\x77\x72\157\156\147\56\x20\120\x6c\x65\141\163\145\40\164\162\x79\40\114\x6f\x67\147\x69\x6e\147\40\151\x6e\40\x61\x67\141\x69\156\56", \mo_oauth_get_valid_html()));
        y30:
        if (!(isset($uo["\163\164\141\164\x75\163"]) && true === $uo["\x73\x74\x61\164\165\163"] && (isset($uo["\143\157\144\145"]) && "\x4c\117\x47\111\x4e\x5f\x53\125\x43\103\105\x53\x53" === $uo["\x63\x6f\x64\145"]))) {
            goto VBG;
        }
        return true;
        VBG:
        if (!(true !== $uo["\163\164\141\x74\x75\163"])) {
            goto otq;
        }
        $q3 = isset($uo["\155\x73\147"]) && !empty($uo["\x6d\x73\x67"]) ? $uo["\x6d\163\147"] : "\123\x6f\155\145\164\150\151\156\x67\x20\167\x65\156\x74\x20\167\x72\x6f\x6e\x67\x2e\40\x50\154\x65\x61\163\x65\x20\x74\162\x79\x20\x4c\x6f\x67\x67\151\156\x67\40\x69\156\x20\x61\x67\141\x69\x6e\x2e";
        MO_Oauth_Debug::mo_oauth_log($q3);
        $Yh->handle_error($q3);
        wp_die(wp_kses($q3, \mo_oauth_get_valid_html()));
        exit;
        otq:
    }
    public function skip_email_exist($A_)
    {
        define("\127\x50\137\x49\115\120\117\x52\x54\111\116\107", "\x53\x4b\111\x50\x5f\x45\115\x41\x49\x4c\137\x45\x58\111\x53\124");
        return $A_;
    }
}
