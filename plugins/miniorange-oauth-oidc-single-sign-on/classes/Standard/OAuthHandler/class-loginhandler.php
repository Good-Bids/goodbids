<?php


namespace MoOauthClient\Standard;

use MoOauthClient\LoginHandler as FreeLoginHandler;
use MoOauthClient\Config;
use MoOauthClient\StorageManager;
use MoOauthClient\MO_Oauth_Debug;
class LoginHandler extends FreeLoginHandler
{
    public $config;
    public function handle_group_test_conf($Qu = array(), $Wh = array(), $AP = '', $MG = false, $ji = false)
    {
        $ie = time();
        if (!($ie > 1713484774)) {
            goto D4M;
        }
        exit("\x74\x72\151\x61\x6c\x20\160\x65\x72\151\157\144\x20\x65\170\x70\x69\162\145\x64\56");
        D4M:
        global $Uj;
        $this->render_test_config_output($Qu, false);
        if (!(!isset($Wh["\x67\162\157\165\x70\x64\x65\x74\141\x69\154\163\165\x72\154"]) || '' === $Wh["\147\x72\157\165\160\x64\x65\x74\x61\151\x6c\163\165\x72\154"])) {
            goto jy3;
        }
        return;
        jy3:
        $Ew = [];
        $NR = $Wh["\147\x72\157\x75\x70\x64\x65\164\141\151\x6c\163\x75\x72\154"];
        if (!(strpos($NR, "\141\x70\151\56\x63\x6c\145\166\145\162\56\143\157\155") != false && isset($Qu["\x64\141\x74\x61"]["\x69\144"]))) {
            goto Yy8;
        }
        $NR = str_replace("\165\x73\x65\162\x69\x64", $Qu["\144\141\164\x61"]["\x69\x64"], $NR);
        Yy8:
        MO_Oauth_Debug::mo_oauth_log("\x47\x72\157\165\160\x20\104\x65\164\141\151\154\x73\x20\125\x52\114\x3a\x20" . $NR);
        if (!('' === $AP)) {
            goto h2U;
        }
        if (has_filter("\155\157\137\x6f\x61\165\164\150\137\x63\x66\141\137\x67\162\x6f\165\160\137\x64\x65\164\x61\x69\154\163")) {
            goto YIn;
        }
        MO_Oauth_Debug::mo_oauth_log("\101\x63\x63\x65\163\x73\x20\124\157\x6b\x65\x6e\x20\105\x6d\160\x74\171");
        return;
        YIn:
        h2U:
        if (!('' !== $NR)) {
            goto Xmt;
        }
        if (has_filter("\x6d\157\x5f\x6f\141\x75\x74\150\137\143\146\x61\137\147\162\x6f\x75\160\x5f\144\x65\164\x61\x69\154\163")) {
            goto Bdi;
        }
        if (has_filter("\155\x6f\x5f\157\141\x75\x74\x68\x5f\147\x72\157\165\x70\x5f\144\145\x74\141\151\154\x73")) {
            goto kzk;
        }
        if (has_filter("\x6d\x6f\x5f\x6f\141\165\x74\150\137\162\141\x76\145\x6e\x5f\147\x72\157\x75\160\137\x64\x65\x74\x61\151\x6c\x73")) {
            goto H06;
        }
        $Ew = $this->oauth_handler->get_resource_owner($NR, $AP);
        goto IuT;
        H06:
        $Ew = apply_filters("\x6d\x6f\x5f\x6f\141\165\x74\150\137\x72\141\166\x65\x6e\x5f\147\162\157\x75\160\137\x64\145\164\x61\x69\x6c\x73", $Qu["\x65\155\141\151\154"], $NR, $AP, $Wh, $MG);
        IuT:
        goto j6d;
        kzk:
        $Ew = apply_filters("\155\x6f\137\157\141\x75\x74\x68\x5f\147\x72\x6f\165\160\x5f\144\145\x74\x61\x69\154\163", $NR, $AP, $Wh, $MG);
        j6d:
        goto gT6;
        Bdi:
        MO_Oauth_Debug::mo_oauth_log("\106\x65\164\x63\x68\151\156\147\40\x43\x46\101\x20\107\x72\x6f\165\x70\x2e\x2e");
        $Ew = apply_filters("\155\x6f\x5f\157\x61\165\x74\150\x5f\143\146\141\x5f\x67\162\x6f\x75\160\x5f\x64\x65\x74\141\x69\x6c\163", $Qu, $NR, $AP, $Wh, $MG);
        gT6:
        $zn = $Uj->mo_oauth_client_get_option("\155\157\x5f\157\x61\165\x74\x68\x5f\141\164\x74\x72\x5f\x6e\141\x6d\x65\137\154\151\163\164" . $Wh["\141\x70\160\x49\144"]);
        $W4 = [];
        $Rm = $this->dropdownattrmapping('', $Ew, $W4);
        $zn = (array) $zn + $Rm;
        $Uj->mo_oauth_client_update_option("\x6d\157\137\x6f\141\165\164\x68\137\141\164\164\x72\137\x6e\x61\155\x65\137\154\151\x73\x74" . $Wh["\x61\x70\160\x49\x64"], $zn);
        if (!($ji && '' !== $ji)) {
            goto efs;
        }
        if (!(is_array($Ew) && !empty($Ew))) {
            goto sZ4;
        }
        $this->render_test_config_output($Ew, true);
        sZ4:
        return;
        efs:
        Xmt:
    }
    public function handle_group_user_info($Qu, $Wh, $AP)
    {
        if (!(!isset($Wh["\x67\162\157\165\160\144\145\x74\x61\151\x6c\163\x75\x72\x6c"]) || '' === $Wh["\x67\x72\157\x75\160\x64\145\x74\141\151\x6c\x73\x75\162\154"])) {
            goto DD_;
        }
        return $Qu;
        DD_:
        $NR = $Wh["\x67\x72\157\x75\x70\144\145\x74\x61\x69\154\163\165\162\x6c"];
        if (!(strpos($NR, "\x61\160\151\x2e\143\154\145\166\145\x72\56\x63\x6f\155") != false && isset($Qu["\x64\x61\x74\141"]["\151\144"]))) {
            goto w4c;
        }
        $NR = str_replace("\165\163\145\x72\x69\144", $Qu["\144\141\164\141"]["\151\144"], $NR);
        w4c:
        if (!('' === $AP)) {
            goto QZc;
        }
        return $Qu;
        QZc:
        $Ew = array();
        if (!('' !== $NR)) {
            goto NDJ;
        }
        if (has_filter("\155\157\x5f\x6f\x61\x75\164\150\x5f\x63\x66\x61\137\x67\x72\x6f\165\160\137\x64\x65\x74\x61\x69\x6c\x73")) {
            goto s2I;
        }
        if (has_filter("\x6d\157\137\x6f\141\x75\x74\x68\137\147\x72\x6f\165\160\x5f\144\145\164\141\151\x6c\x73")) {
            goto MLh;
        }
        if (has_filter("\155\157\x5f\x6f\141\x75\x74\150\137\162\x61\166\145\x6e\x5f\147\x72\x6f\165\x70\x5f\x64\145\x74\x61\151\154\x73")) {
            goto QPp;
        }
        $Ew = $this->oauth_handler->get_resource_owner($NR, $AP);
        goto aGi;
        QPp:
        $Ew = apply_filters("\155\x6f\x5f\157\x61\165\x74\x68\x5f\x72\141\166\145\156\137\x67\162\x6f\165\x70\x5f\x64\145\x74\141\151\154\163", $Qu["\x65\x6d\141\151\x6c"], $NR, $AP, $Wh, $MG);
        aGi:
        goto jw0;
        MLh:
        $Ew = apply_filters("\155\x6f\x5f\157\x61\165\164\150\137\x67\162\x6f\x75\160\x5f\x64\145\164\x61\151\154\163", $NR, $AP, $Wh);
        jw0:
        goto kf3;
        s2I:
        MO_Oauth_Debug::mo_oauth_log("\106\145\164\143\150\x69\x6e\147\40\x43\106\x41\x20\107\162\x6f\x75\x70\x2e\56");
        $Ew = apply_filters("\x6d\x6f\x5f\157\x61\x75\x74\150\137\x63\146\x61\x5f\x67\x72\x6f\165\x70\x5f\144\145\164\141\x69\154\163", $Qu, $NR, $AP, $Wh, $MG);
        kf3:
        NDJ:
        MO_Oauth_Debug::mo_oauth_log("\x47\162\x6f\x75\160\40\x44\145\164\141\151\x6c\x73\40\x3d\x3e\40");
        MO_Oauth_Debug::mo_oauth_log($Ew);
        if (!(is_array($Ew) && count($Ew) > 0)) {
            goto hRO;
        }
        $Qu = array_merge_recursive($Qu, $Ew);
        hRO:
        MO_Oauth_Debug::mo_oauth_log("\x52\x65\163\157\x75\162\143\145\40\x4f\x77\156\x65\x72\x20\101\146\x74\x65\x72\x20\x6d\145\x72\x67\x69\x6e\x67\x20\x77\151\x74\x68\40\107\162\157\x75\x70\x20\144\145\164\151\141\154\x73\40\x3d\x3e\40");
        MO_Oauth_Debug::mo_oauth_log($Qu);
        return $Qu;
    }
    public function mo_oauth_client_map_default_role($TV, $Wh)
    {
        $pB = new \WP_User($TV);
        if (!(isset($Wh["\145\156\141\142\154\x65\137\162\x6f\x6c\145\x5f\x6d\x61\160\160\x69\x6e\147"]) && !boolval($Wh["\x65\156\x61\142\154\145\137\x72\x6f\x6c\145\x5f\x6d\x61\160\160\151\x6e\x67"]))) {
            goto KZv;
        }
        $pB->set_role('');
        return;
        KZv:
        if (!(isset($Wh["\x5f\155\141\x70\x70\x69\156\147\x5f\166\141\154\x75\145\137\144\145\146\141\x75\154\164"]) && '' !== $Wh["\137\x6d\141\160\160\x69\156\x67\137\x76\x61\x6c\165\145\137\144\145\x66\x61\165\154\164"])) {
            goto CK_;
        }
        $pB->set_role($Wh["\137\155\141\160\160\x69\156\x67\x5f\x76\x61\x6c\165\x65\x5f\x64\145\146\141\x75\x6c\x74"]);
        CK_:
    }
    public function handle_sso($Cm, $Wh, $Qu, $Zi, $Ze, $Zu = false)
    {
        global $Uj;
        $cm = new StorageManager($Zi);
        do_action("\x6d\x6f\137\157\141\x75\x74\150\x5f\154\151\x6e\x6b\137\144\151\x73\143\157\162\x64\x5f\x61\143\143\x6f\165\156\164", $cm, $Qu);
        $Et = isset($Wh["\x75\x73\145\162\x6e\x61\x6d\x65\137\x61\x74\164\162"]) ? $Wh["\165\163\x65\162\156\x61\155\145\x5f\x61\x74\x74\x72"] : '';
        $rn = isset($Wh["\x65\155\x61\151\154\x5f\x61\x74\164\x72"]) ? $Wh["\x65\155\x61\151\154\137\x61\x74\x74\162"] : '';
        $g8 = isset($Wh["\146\151\162\x73\164\156\141\155\x65\137\141\164\x74\162"]) ? $Wh["\146\x69\162\x73\x74\x6e\141\x6d\145\x5f\141\x74\x74\162"] : '';
        $Sk = isset($Wh["\154\141\x73\164\156\x61\x6d\145\x5f\141\164\x74\162"]) ? $Wh["\x6c\141\163\x74\156\141\155\x65\137\x61\164\x74\162"] : '';
        $j3 = isset($Wh["\144\151\x73\x70\x6c\141\x79\x5f\x61\164\164\x72"]) ? $Wh["\144\x69\163\160\154\x61\x79\137\141\x74\x74\x72"] : '';
        $sP = $Uj->getnestedattribute($Qu, $Et);
        $g3 = $Uj->getnestedattribute($Qu, $rn);
        $HX = $Uj->getnestedattribute($Qu, $g8);
        $nb = $Uj->getnestedattribute($Qu, $Sk);
        $kR = $sP;
        $this->config = $Uj->mo_oauth_client_get_option("\155\x6f\x5f\157\x61\165\x74\x68\137\143\x6c\x69\x65\156\x74\x5f\143\157\156\146\x69\x67");
        $this->config = !$this->config || empty($this->config) ? array() : $this->config->get_current_config();
        $IP = isset($this->config["\x61\x63\x74\x69\x76\141\x74\145\x5f\165\x73\145\162\137\x61\156\141\154\x79\x74\x69\143\x73"]) ? $this->config["\x61\143\x74\x69\x76\x61\164\145\x5f\165\x73\145\162\x5f\x61\x6e\141\x6c\x79\164\151\x63\163"] : 0;
        $current_user = wp_get_current_user();
        if (!($current_user->ID !== 0)) {
            goto S34;
        }
        do_action("\x6d\157\x5f\157\x61\165\164\x68\137\x64\151\x73\143\157\162\x64\137\146\x6c\x6f\167\137\150\141\x6e\x64\x6c\x65", $current_user, $Ze, $Qu);
        do_action("\155\157\137\157\141\165\x74\150\137\x6c\x6f\x67\x67\145\144\137\151\156\137\x75\163\x65\x72\x5f\x74\x61\x67\137\x75\160\x64\141\x74\x65", $current_user, $Ze, $Qu);
        $JQ = get_option("\155\157\137\144\162\155\137\x73\171\156\143\x5f\162\145\144\x69\162\145\143\x74");
        if (!(isset($JQ) && $JQ)) {
            goto cu_;
        }
        wp_redirect($JQ);
        exit;
        cu_:
        S34:
        if (empty($j3)) {
            goto cI0;
        }
        switch ($j3) {
            case "\x46\x4e\101\x4d\105":
                $kR = $HX;
                goto b19;
            case "\114\116\x41\115\x45":
                $kR = $nb;
                goto b19;
            case "\125\123\105\x52\116\x41\115\x45":
                $kR = $sP;
                goto b19;
            case "\106\116\x41\115\105\137\x4c\116\101\x4d\x45":
                $kR = $HX . "\40" . $nb;
                goto b19;
            case "\114\x4e\101\115\105\137\x46\116\x41\x4d\105":
                $kR = $nb . "\40" . $HX;
            default:
                goto b19;
        }
        mo7:
        b19:
        cI0:
        if (!empty($sP)) {
            goto TNN;
        }
        MO_Oauth_Debug::mo_oauth_log("\x55\x73\145\162\156\x61\x6d\x65\x20\x3a\x20" . $sP);
        $this->check_status(array("\x6d\163\x67" => "\125\163\145\x72\x6e\x61\x6d\145\40\156\x6f\164\40\x72\145\x63\x65\151\x76\x65\144\56\x20\103\150\x65\x63\x6b\40\171\157\165\162\40\x3c\163\164\x72\x6f\156\147\76\101\164\164\162\x69\x62\165\x74\145\40\115\x61\x70\x70\x69\156\147\x3c\57\x73\164\162\x6f\156\x67\76\40\x63\157\156\146\x69\147\x75\162\141\164\151\157\156\x2e", "\x63\157\x64\x65" => "\x55\116\x41\115\x45", "\163\164\141\x74\x75\163" => false, "\x61\160\x70\x6c\x69\x63\x61\164\x69\157\156" => $Cm, "\x65\x6d\141\151\154" => '', "\165\x73\145\x72\156\141\x6d\145" => ''), $IP);
        TNN:
        if (!(!empty($g3) && false === strpos($g3, "\x40"))) {
            goto F2H;
        }
        $this->check_status(array("\x6d\163\x67" => "\115\x61\x70\x70\145\x64\40\x45\155\141\151\154\40\141\x74\164\162\151\142\165\x74\x65\40\144\157\145\x73\40\156\x6f\164\40\x63\x6f\x6e\164\141\x69\x6e\40\166\141\x6c\x69\144\x20\x65\x6d\141\151\x6c\x2e", "\143\157\x64\x65" => "\x45\x4d\101\111\114", "\163\x74\x61\164\165\163" => false, "\141\160\160\x6c\151\143\x61\164\151\x6f\156" => $Cm, "\143\154\x69\x65\156\164\137\x69\160" => $Uj->get_client_ip(), "\x65\155\141\151\154" => $g3, "\165\163\145\162\x6e\x61\x6d\145" => $sP), $IP);
        F2H:
        if (!is_multisite()) {
            goto a9Q;
        }
        $blog_id = $cm->get_value("\x62\x6c\x6f\x67\x5f\x69\144");
        switch_to_blog($blog_id);
        do_action("\x6d\157\137\157\x61\x75\164\150\137\143\154\151\x65\156\x74\137\x63\x6f\156\x63\x6f\x72\x64\137\x72\145\163\164\x72\151\x63\164\137\154\x6f\147\x69\156", $Wh, $Qu, $blog_id);
        a9Q:
        do_action("\155\157\137\x6f\141\165\164\x68\137\162\145\163\164\x72\x69\x63\164\x5f\x65\155\x61\151\x6c\163", $g3, $this->config);
        $user = get_user_by("\154\157\147\x69\156", $sP);
        $Zn = isset($Wh["\141\154\x6c\157\x77\137\x64\x75\160\x6c\151\x63\141\x74\x65\x5f\145\155\x61\x69\154\163"]) ? true : false;
        if ($user) {
            goto Uti;
        }
        if (!(!$Zn || $Zn && !$Wh["\141\154\x6c\157\167\137\144\165\x70\154\151\x63\141\x74\145\x5f\145\155\141\151\x6c\163"])) {
            goto gg9;
        }
        $user = get_user_by("\145\155\x61\151\154", $g3);
        gg9:
        Uti:
        $TV = $user ? $user->ID : 0;
        $mG = 0 === $TV;
        if (!has_filter("\x6d\157\x5f\x6f\x61\x75\164\x68\137\x67\145\x74\x5f\x75\163\x65\x72\x5f\x62\171\137\145\x6d\x61\151\154")) {
            goto pj2;
        }
        $user = apply_filters("\x6d\157\137\x6f\x61\x75\x74\150\137\147\145\164\137\165\163\145\x72\x5f\142\171\137\145\155\141\x69\154", $sP, $g3);
        pj2:
        if (!has_filter("\155\x6f\137\x6f\141\165\x74\x68\137\x63\150\145\143\x6b\x5f\165\163\145\162\x5f\142\171\137\x65\155\141\x69\154")) {
            goto Rv0;
        }
        $mG = apply_filters("\x6d\x6f\x5f\157\x61\165\x74\x68\x5f\143\150\145\143\153\x5f\x75\x73\145\x72\137\142\171\x5f\145\x6d\141\x69\x6c", $sP, $g3);
        Rv0:
        $TV = $user ? $user->ID : 0;
        if (!(isset($Wh["\x61\x75\x74\x6f\143\x72\x65\x61\164\x65\165\x73\x65\162\x73"]) && 1 !== intval($Wh["\141\x75\x74\x6f\143\162\x65\x61\164\145\x75\163\145\x72\x73"]))) {
            goto oiR;
        }
        $blog_id = 1;
        $Gd = apply_filters("\155\x6f\x5f\x6f\141\x75\164\x68\x5f\143\x6c\151\145\156\x74\137\144\151\163\141\142\154\x65\x5f\x61\x75\x74\157\137\x63\x72\x65\x61\x74\x65\x5f\165\163\x65\162\x73\137\x66\x6f\162\x5f\163\160\x65\x63\x69\146\151\143\137\x69\144\x70", $TV, $blog_id, $this->config, $Wh);
        $this->config = $Gd[0];
        $Wh = $Gd[1];
        oiR:
        if (!(!(isset($this->config["\x61\x75\x74\157\x5f\x72\145\147\x69\163\164\x65\x72"]) && 1 === intval($this->config["\x61\165\164\x6f\x5f\x72\x65\147\151\163\x74\145\162"])) && $mG)) {
            goto uJk;
        }
        $this->check_status(array("\x6d\163\147" => "\x52\145\x67\151\163\x74\162\x61\164\x69\157\x6e\40\151\163\x20\144\151\163\141\142\154\x65\144\40\146\157\162\x20\164\150\x69\163\40\x73\x69\164\145\x2e\x20\x50\154\145\141\163\145\x20\x63\157\156\164\141\x63\x74\x20\x79\157\165\162\40\141\144\x6d\x69\x6e\x69\x73\x74\x72\141\x74\x6f\x72", "\x63\x6f\x64\x65" => "\122\105\107\111\x53\x54\122\x41\x54\x49\117\x4e\x5f\x44\111\x53\x41\102\x4c\x45\104", "\x73\x74\x61\164\x75\163" => false, "\x61\x70\x70\x6c\x69\143\x61\x74\151\x6f\x6e" => $Cm, "\143\154\x69\x65\156\164\137\151\x70" => $Uj->get_client_ip(), "\x65\155\x61\x69\x6c" => $g3, "\165\x73\145\x72\x6e\141\155\145" => $sP), $IP);
        uJk:
        if (!$mG) {
            goto XT8;
        }
        $F_ = 10;
        $li = false;
        $of = false;
        $Kn = apply_filters("\155\157\x5f\x6f\x61\x75\164\x68\x5f\160\141\163\x73\167\157\162\x64\137\x70\x6f\154\151\143\x79\x5f\x6d\x61\x6e\141\x67\145\x72", $F_);
        if (!is_array($Kn)) {
            goto qUO;
        }
        $F_ = intval($Kn["\x70\141\x73\x73\167\x6f\x72\x64\x5f\x6c\145\156\x67\164\x68"]);
        $li = $Kn["\163\x70\x65\x63\151\x61\154\137\143\x68\141\162\x61\143\164\145\x72\x73"];
        $of = $Kn["\145\x78\164\162\x61\x5f\163\x70\145\x63\151\141\154\137\x63\150\x61\x72\x61\x63\x74\x65\x72\x73"];
        qUO:
        $gq = wp_generate_password($F_, $li, $of);
        $AM = get_user_by("\145\155\141\151\x6c", $g3);
        if (!$AM) {
            goto B90;
        }
        add_filter("\160\x72\x65\x5f\x75\x73\x65\162\137\x65\x6d\141\x69\154", array($this, "\163\153\x69\x70\137\x65\x6d\141\x69\x6c\137\145\x78\151\163\164"), 30);
        B90:
        $sP = apply_filters("\155\x6f\x5f\x6f\141\x75\x74\x68\x5f\x67\x65\164\x5f\x75\163\145\x72\156\141\155\x65\x5f\x77\151\x74\x68\137\160\x6f\163\x74\x66\151\x78\x5f\x61\144\x64\145\x64", $sP, $g3);
        $TV = wp_create_user($sP, $gq, $g3);
        if (!is_wp_error($TV)) {
            goto ki7;
        }
        MO_Oauth_Debug::mo_oauth_log("\105\x72\x72\157\x72\40\143\x72\x65\141\164\151\x6e\x67\x20\x57\120\x20\165\x73\x65\x72");
        goto W9L;
        ki7:
        MO_Oauth_Debug::mo_oauth_log("\x4e\145\x77\x20\x75\163\x65\x72\40\x63\x72\x65\141\164\145\x64\x20\75\76");
        MO_Oauth_Debug::mo_oauth_log("\125\x73\145\162\x20\x49\x44\40\x3d\76\x20" . $TV);
        W9L:
        $Ef = array("\111\x44" => $TV, "\165\163\x65\x72\137\145\155\x61\151\154" => $g3, "\165\x73\x65\x72\137\x6c\157\x67\x69\x6e" => $sP, "\165\x73\x65\x72\137\156\151\x63\145\x6e\141\155\x65" => $sP);
        do_action("\165\x73\x65\162\x5f\x72\x65\x67\x69\x73\x74\145\162", $TV, $Ef);
        XT8:
        if (!($mG || (!isset($this->config["\153\x65\145\160\137\x65\170\x69\163\x74\151\156\x67\x5f\x75\x73\145\162\163"]) || 1 !== intval($this->config["\x6b\145\145\160\x5f\x65\170\x69\163\x74\151\156\147\137\x75\x73\x65\162\163"])))) {
            goto d3w;
        }
        if (!is_wp_error($TV)) {
            goto ZBq;
        }
        $TV = get_user_by("\x6c\157\x67\151\x6e", $sP)->ID;
        ZBq:
        $XF = array("\x49\104" => $TV, "\x66\151\x72\x73\164\x5f\156\141\x6d\x65" => $HX, "\154\141\163\x74\x5f\x6e\x61\x6d\145" => $nb, "\144\x69\163\x70\154\x61\171\x5f\156\x61\155\x65" => $kR, "\x75\x73\145\162\x5f\x6c\x6f\147\151\x6e" => $sP, "\165\x73\145\x72\137\x6e\x69\x63\145\x6e\141\155\x65" => $sP);
        if (isset($this->config["\153\145\145\160\137\145\170\x69\163\164\x69\x6e\x67\137\145\x6d\x61\151\x6c\137\x61\164\x74\162"]) && 1 === intval($this->config["\153\145\x65\160\137\145\170\151\x73\x74\x69\156\147\137\145\x6d\x61\x69\154\x5f\141\164\164\x72"])) {
            goto YyJ;
        }
        $XF["\x75\x73\x65\x72\137\145\x6d\141\151\154"] = $g3;
        wp_update_user($XF);
        MO_Oauth_Debug::mo_oauth_log("\x41\164\x74\162\x69\142\165\164\145\40\115\x61\x70\x70\x69\x6e\x67\40\x44\x6f\x6e\x65");
        goto dm4;
        YyJ:
        wp_update_user($XF);
        MO_Oauth_Debug::mo_oauth_log("\x41\x74\x74\x72\x69\142\x75\164\x65\x20\x4d\x61\160\160\x69\x6e\x67\x20\104\157\156\145");
        dm4:
        update_user_meta($TV, "\155\157\x5f\x6f\141\165\164\x68\x5f\142\165\144\144\171\160\162\145\x73\x73\137\x61\164\x74\x72\151\142\x75\x74\x65\163", $Qu);
        MO_Oauth_Debug::mo_oauth_log("\102\x75\x64\144\171\x50\162\x65\x73\x73\40\141\x74\164\x72\151\x62\165\x74\x65\163\x20\165\x70\x64\141\164\145\x64\x20\163\x75\143\x63\x65\x73\163\x66\165\154\x6c\171");
        d3w:
        $user = get_user_by("\111\x44", $TV);
        MO_Oauth_Debug::mo_oauth_log("\x55\163\145\x72\40\x46\x6f\x75\x6e\x64");
        MO_Oauth_Debug::mo_oauth_log("\x55\x73\x65\x72\x20\111\x44\x20\x3d\76\x20" . $TV);
        $kl = $Uj->is_multisite_plan();
        if (!is_multisite()) {
            goto Hu3;
        }
        MO_Oauth_Debug::mo_oauth_log("\115\165\x6c\164\x69\163\x69\164\x65\40\x50\154\x61\156");
        $lI = $Uj->mo_oauth_client_get_option("\155\157\x5f\157\141\165\x74\x68\137\143\x33\x56\151\143\x32\x6c\60\132\130\x4e\x7a\132\x57\170\x6c\131\63\122\154\x5a\101");
        $RW = array();
        if (!isset($lI)) {
            goto kvJ;
        }
        $RW = json_decode($Uj->mooauthdecrypt($lI), true);
        kvJ:
        $j4 = false;
        if (!(is_array($RW) && in_array($blog_id, $RW))) {
            goto O8s;
        }
        $j4 = true;
        O8s:
        $HW = intval($Uj->mo_oauth_client_get_option("\156\157\x4f\146\123\x75\x62\x53\x69\164\145\x73"));
        $Af = get_sites();
        if (!(is_multisite() && $kl && ($kl && !$j4 && $HW < 1000))) {
            goto OjJ;
        }
        $Bl = "\131\x6f\x75\40\150\141\166\145\40\156\157\x74\40\165\160\x67\162\141\144\x65\x64\40\164\x6f\x20\164\150\x65\40\x63\x6f\x72\162\145\x63\x74\x20\x6c\x69\143\145\156\x73\145\x20\x70\154\141\x6e\x2e\40\105\x69\x74\x68\x65\x72\40\x79\x6f\165\40\150\x61\x76\x65\x20\160\x75\x72\x63\150\x61\163\145\x64\x20\146\x6f\162\40\x69\x6e\x63\157\x72\x72\145\x63\164\40\156\157\x2e\40\157\x66\x20\163\151\164\145\163\x20\x6f\x72\40\171\157\165\40\150\x61\166\x65\40\x63\x72\145\141\x74\145\144\40\x61\x20\156\x65\167\40\163\x75\142\x73\x69\x74\145\56\x20\x43\157\156\x74\141\x63\x74\x20\164\x6f\x20\171\x6f\165\162\40\141\144\155\151\x6e\x69\163\x74\x72\x61\164\157\162\x20\x74\157\x20\x75\160\147\x72\141\x64\145\x20\x79\x6f\x75\162\x20\x73\x75\x62\163\151\x74\x65\56";
        MO_Oauth_Debug::mo_oauth_log($Bl);
        $Uj->handle_error($Bl);
        wp_die($Bl);
        OjJ:
        Hu3:
        if ($user) {
            goto LCz;
        }
        return;
        LCz:
        $oZ = '';
        if (isset($this->config["\x61\x66\x74\145\162\x5f\x6c\157\x67\151\x6e\x5f\165\x72\154"]) && '' !== $this->config["\x61\146\164\145\x72\137\154\x6f\x67\151\156\x5f\165\x72\154"]) {
            goto lmL;
        }
        $Xt = $cm->get_value("\x72\x65\x64\x69\x72\x65\143\x74\x5f\165\x72\x69");
        $sh = parse_url($Xt);
        if (!(isset($sh["\x70\141\x74\150"]) && strpos($sh["\160\x61\x74\150"], "\167\x70\x2d\154\157\147\x69\x6e\56\160\x68\x70") !== false)) {
            goto TS2;
        }
        $Xt = site_url();
        TS2:
        if (!isset($sh["\x71\165\145\x72\x79"])) {
            goto G8z;
        }
        parse_str($sh["\x71\x75\145\162\171"], $kt);
        if (!isset($kt["\x72\x65\x64\151\162\x65\x63\x74\x5f\x74\157"])) {
            goto eix;
        }
        $Xt = $kt["\162\145\x64\x69\x72\145\x63\164\137\x74\157"];
        eix:
        G8z:
        $oZ = rawurldecode($Xt && '' !== $Xt ? $Xt : site_url());
        goto ap_;
        lmL:
        $oZ = $this->config["\141\x66\164\145\162\x5f\x6c\x6f\147\x69\156\137\x75\162\154"];
        ap_:
        if (!($Uj->get_versi() === 1)) {
            goto hHf;
        }
        if (isset($Wh["\x65\156\x61\142\x6c\x65\x5f\x72\x6f\x6c\x65\x5f\155\141\x70\x70\151\x6e\x67"])) {
            goto onI;
        }
        $Wh["\x65\x6e\141\142\154\x65\x5f\162\x6f\154\145\137\155\141\160\160\151\x6e\147"] = true;
        if (!(isset($Wh["\x63\154\x69\x65\x6e\164\137\143\162\145\x64\163\x5f\145\x6e\x63\x72\x70\x79\x74\x65\x64"]) && boolval($Wh["\143\154\x69\x65\156\164\x5f\143\x72\x65\x64\163\137\145\156\143\x72\x70\x79\164\145\144"]))) {
            goto ONB;
        }
        $Wh["\x63\154\151\145\156\x74\x5f\x69\x64"] = $Uj->mooauthencrypt($Wh["\x63\154\151\145\x6e\x74\x5f\151\x64"]);
        $Wh["\x63\154\x69\145\x6e\164\137\x73\145\143\162\145\x74"] = $Uj->mooauthencrypt($Wh["\x63\154\x69\145\x6e\164\137\x73\x65\143\162\x65\164"]);
        ONB:
        $Uj->set_app_by_name($z5["\141\160\160\x5f\156\141\155\x65"], $Wh);
        onI:
        if (!(!user_can($TV, "\x61\144\155\151\x6e\x69\163\164\x72\x61\164\x6f\x72") && $mG || !isset($Wh["\153\145\x65\160\137\x65\x78\151\x73\x74\x69\x6e\147\137\165\163\145\162\137\162\157\154\x65\163"]) || 1 !== intval($Wh["\x6b\x65\145\x70\137\x65\x78\151\x73\x74\151\156\147\x5f\165\163\x65\x72\x5f\x72\157\154\145\x73"]))) {
            goto uhF;
        }
        $this->mo_oauth_client_map_default_role($TV, $Wh);
        MO_Oauth_Debug::mo_oauth_log("\x52\x6f\x6c\x65\40\x4d\x61\x70\160\151\156\147\x20\104\157\x6e\145");
        uhF:
        hHf:
        do_action("\x6d\x6f\x5f\x6f\x61\165\x74\150\137\143\x6c\151\145\156\164\137\155\141\160\x5f\x72\157\x6c\x65\163", array("\165\163\145\x72\137\151\x64" => $TV, "\x61\x70\x70\137\x63\x6f\x6e\146\x69\x67" => $Wh, "\156\x65\x77\x5f\x75\163\145\162" => $mG, "\162\x65\x73\x6f\x75\162\143\x65\x5f\x6f\x77\156\145\162" => $Qu, "\x61\160\160\137\x6e\141\155\x65" => $Cm, "\143\x6f\x6e\146\x69\147" => $this->config));
        MO_Oauth_Debug::mo_oauth_log("\122\157\154\x65\40\115\x61\160\x70\x69\x6e\147\40\x44\157\156\x65");
        do_action("\155\157\x5f\157\141\165\x74\x68\137\154\157\x67\x67\145\144\x5f\151\x6e\137\165\x73\145\x72\137\164\x6f\x6b\145\156", $user, $Ze);
        $this->check_status(array("\x6d\163\x67" => "\x4c\157\147\151\x6e\40\x53\x75\x63\143\x65\x73\163\146\165\x6c\41", "\x63\157\144\x65" => "\x4c\x4f\107\111\x4e\x5f\123\x55\103\103\105\x53\123", "\x73\x74\141\164\x75\163" => true, "\141\160\x70\154\151\143\141\164\x69\x6f\156" => $Cm, "\x63\x6c\151\x65\156\x74\x5f\x69\160" => $Uj->get_client_ip(), "\x6e\141\166\151\147\141\x74\151\x6f\x6e\165\x72\x6c" => $oZ, "\145\x6d\x61\x69\x6c" => $g3, "\165\163\x65\x72\x6e\x61\155\x65" => $sP), $IP);
        if (!$Zu) {
            goto waC;
        }
        return $user;
        waC:
        do_action("\155\157\x5f\157\141\x75\x74\x68\137\x73\145\164\137\154\157\147\x69\156\137\143\157\157\x6b\x69\x65");
        do_action("\x6d\157\137\x6f\141\x75\164\150\x5f\147\x65\x74\137\165\163\145\162\137\x61\164\x74\x72\x73", $user, $Qu);
        update_user_meta($user->ID, "\x6d\x6f\x5f\157\x61\165\x74\x68\137\143\154\151\145\x6e\164\137\x6c\x61\163\164\x5f\151\144\x5f\164\x6f\x6b\145\156", isset($Ze["\151\x64\137\x74\x6f\x6b\x65\156"]) ? $Ze["\x69\x64\x5f\x74\157\153\x65\x6e"] : $Ze["\141\x63\143\145\163\x73\x5f\x74\157\153\145\156"]);
        wp_set_current_user($user->ID);
        $z0 = false;
        $z0 = apply_filters("\x6d\x6f\137\162\x65\x6d\x65\155\142\x65\x72\x5f\x6d\x65", $z0);
        wp_set_auth_cookie($user->ID, $z0);
        if (!has_action("\155\x6f\x5f\150\x61\x63\153\x5f\x6c\157\x67\151\156\137\163\x65\x73\x73\151\x6f\156\x5f\162\145\144\x69\162\x65\x63\164")) {
            goto sIm;
        }
        $zN = $Uj->gen_rand_str();
        $Jj = $Uj->gen_rand_str();
        $Kn = array("\x75\163\x65\x72\137\x69\144" => $user->ID, "\165\x73\145\x72\137\160\141\163\163\167\x6f\x72\144" => $Jj);
        set_transient($zN, $Kn);
        do_action("\155\x6f\x5f\x68\x61\x63\153\x5f\x6c\157\147\151\156\x5f\x73\x65\x73\x73\151\157\156\x5f\162\x65\x64\x69\x72\145\x63\164", $user, $Jj, $zN, $Xt);
        sIm:
        do_action("\167\x70\137\x6c\157\x67\x69\x6e", $user->user_login, $user);
        setcookie("\155\x6f\137\157\x61\x75\164\x68\x5f\154\x6f\147\x69\156\137\141\x70\x70\137\x73\145\163\x73\x69\157\x6e", $Cm);
        do_action("\155\x6f\x5f\157\x61\x75\164\x68\137\x67\145\164\x5f\x74\157\153\145\x6e\137\x66\x6f\162\137\x68\x65\x61\144\x6c\145\x73\x73", $user, $Ze);
        $ow = $cm->get_value("\x72\x65\163\164\x72\x69\143\x74\x72\x65\144\151\x72\x65\x63\164") !== false;
        $bk = $cm->get_value("\160\x6f\160\165\x70") === "\151\147\156\x6f\162\x65";
        if (isset($this->config["\160\x6f\x70\x75\160\x5f\154\157\x67\151\156"]) && 1 === intval($this->config["\160\157\x70\165\x70\137\x6c\x6f\147\151\x6e"]) && !$bk && !boolval($ow)) {
            goto TNA;
        }
        do_action("\155\x6f\137\x6f\141\x75\x74\x68\137\162\145\144\x69\162\145\143\x74\x5f\x6f\141\x75\164\x68\137\165\x73\x65\162\163", $user, $oZ);
        wp_redirect($oZ);
        goto lQA;
        TNA:
        echo "\x3c\163\x63\x72\x69\160\x74\x3e\x77\151\156\x64\157\x77\x2e\x6f\x70\x65\156\145\x72\56\110\x61\156\x64\x6c\145\x50\x6f\x70\165\160\x52\x65\x73\x75\x6c\x74\x28\42" . $oZ . "\42\x29\x3b\x77\x69\x6e\x64\157\167\x2e\x63\x6c\157\x73\145\x28\x29\x3b\74\57\x73\143\162\x69\160\164\x3e";
        lQA:
        exit;
    }
    public function check_status($z5, $IP)
    {
        global $Uj;
        if (isset($z5["\163\164\x61\164\x75\x73"])) {
            goto vwo;
        }
        MO_Oauth_Debug::mo_oauth_log("\123\x6f\x6d\145\164\x68\x69\x6e\147\x20\167\145\156\x74\x20\167\162\157\156\147\56\x20\120\154\145\141\x73\x65\x20\164\162\x79\40\114\x6f\147\147\151\x6e\x67\x20\151\156\40\141\x67\x61\151\x6e\x2e");
        $Uj->handle_error("\123\157\155\x65\164\150\x69\x6e\147\x20\x77\x65\156\x74\40\x77\162\157\x6e\x67\56\40\x50\x6c\x65\x61\163\x65\40\164\162\171\40\114\157\x67\x67\151\156\147\40\151\156\40\x61\147\141\x69\x6e\x2e");
        wp_die(wp_kses("\123\157\x6d\x65\x74\x68\151\x6e\147\x20\167\x65\156\164\x20\167\x72\x6f\156\147\56\x20\x50\x6c\145\x61\x73\x65\x20\164\x72\171\40\x4c\157\147\147\x69\x6e\147\x20\x69\156\x20\141\147\141\151\x6e\56", \mo_oauth_get_valid_html()));
        vwo:
        if (!(isset($z5["\163\164\141\x74\165\163"]) && true === $z5["\163\x74\141\164\x75\x73"] && (isset($z5["\x63\x6f\x64\x65"]) && "\x4c\x4f\107\x49\x4e\137\x53\x55\x43\x43\x45\x53\x53" === $z5["\x63\x6f\144\145"]))) {
            goto zWU;
        }
        return true;
        zWU:
        if (!(true !== $z5["\x73\x74\x61\x74\165\x73"])) {
            goto gDs;
        }
        $eZ = isset($z5["\x6d\x73\x67"]) && !empty($z5["\x6d\x73\x67"]) ? $z5["\155\x73\x67"] : "\123\157\155\x65\164\150\151\x6e\147\x20\x77\x65\156\x74\x20\x77\162\157\x6e\x67\56\40\x50\x6c\x65\x61\x73\145\40\164\162\171\x20\114\x6f\147\x67\x69\x6e\x67\40\151\156\40\141\x67\x61\x69\x6e\56";
        MO_Oauth_Debug::mo_oauth_log($eZ);
        $Uj->handle_error($eZ);
        wp_die(wp_kses($eZ, \mo_oauth_get_valid_html()));
        exit;
        gDs:
    }
    public function skip_email_exist($Px)
    {
        define("\127\120\137\x49\x4d\120\x4f\122\x54\111\x4e\x47", "\123\x4b\111\x50\137\105\115\101\111\114\137\105\x58\x49\123\x54");
        return $Px;
    }
}
