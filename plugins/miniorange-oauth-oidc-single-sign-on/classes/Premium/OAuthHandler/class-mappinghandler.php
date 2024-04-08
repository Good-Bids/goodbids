<?php


namespace MoOauthClient\Premium;

use MoOauthClient\Mo_Oauth_Debug;
class MappingHandler
{
    private $user_id = 0;
    private $app_config = array();
    private $group_name = '';
    private $is_new_user = false;
    public function __construct($TV = 0, $Wh = array(), $yX = '', $mG = false)
    {
        if (!(!array($Wh) || empty($Wh))) {
            goto Ti7;
        }
        return;
        Ti7:
        $hh = is_array($yX) ? $yX : $this->get_group_array($yX);
        $this->group_name = $hh;
        $this->user_id = $TV;
        $this->app_config = $Wh;
        $this->is_new_user = $mG;
    }
    private function get_group_array($MG)
    {
        $gy = json_decode($MG, true);
        return is_array($gy) && json_last_error() === JSON_ERROR_NONE ? $gy : explode("\x3b", $MG);
    }
    public function apply_custom_attribute_mapping($Qu)
    {
        if (!(!isset($this->app_config["\x63\x75\x73\164\157\155\137\141\x74\164\x72\163\137\x6d\141\x70\160\151\x6e\x67"]) || empty($this->app_config["\143\165\x73\164\x6f\x6d\x5f\141\164\x74\x72\x73\x5f\155\x61\160\160\151\x6e\x67"]))) {
            goto DLo;
        }
        return;
        DLo:
        global $Uj;
        $zY = -1;
        $cS = $this->app_config["\x63\x75\x73\164\x6f\x6d\137\x61\x74\x74\162\163\x5f\155\141\160\160\151\x6e\x67"];
        $Il = [];
        foreach ($cS as $Mr => $t_) {
            $DD = $Uj->getnestedattribute($Qu, $t_);
            $Il[$Mr] = $DD;
            update_user_meta($this->user_id, $Mr, $DD);
            uIR:
        }
        tvn:
        update_user_meta($this->user_id, "\155\x6f\x5f\157\x61\165\164\x68\137\x63\165\163\x74\x6f\x6d\x5f\141\x74\x74\162\151\x62\x75\164\145\163", $Il);
    }
    public function apply_role_mapping($Qu)
    {
        if (!has_filter("\155\x6f\x5f\163\x75\x62\x73\151\164\x65\x5f\x63\x68\145\x63\x6b\137\x61\154\154\x6f\x77\x5f\154\157\x67\151\156")) {
            goto Sh4;
        }
        $CZ = apply_filters("\155\157\137\163\x75\x62\x73\x69\164\145\137\x63\150\x65\x63\x6b\137\145\x78\151\x73\164\x69\x6e\x67\x5f\x72\x6f\x6c\145\163", $this->app_config, $this->is_new_user);
        if (!($CZ === true)) {
            goto JWm;
        }
        return;
        JWm:
        goto w6_;
        Sh4:
        if (!(!$this->is_new_user && isset($this->app_config["\x6b\145\x65\160\137\145\170\x69\163\x74\x69\156\x67\x5f\x75\163\x65\162\137\x72\x6f\x6c\x65\163"]) && 1 === intval($this->app_config["\x6b\145\145\160\137\x65\x78\151\x73\x74\151\x6e\x67\137\165\163\145\x72\137\162\x6f\x6c\x65\163"]))) {
            goto Yt3;
        }
        return;
        Yt3:
        w6_:
        $pB = new \WP_User($this->user_id);
        if (!(isset($this->app_config["\x65\156\x61\x62\x6c\145\x5f\x72\x6f\154\145\x5f\x6d\141\160\x70\151\156\x67"]) && !boolval($this->app_config["\145\156\141\x62\154\145\137\162\157\154\x65\137\x6d\x61\160\160\151\x6e\x67"]))) {
            goto TYb;
        }
        $pB->set_role('');
        return;
        TYb:
        if (!has_filter("\x6d\x6f\137\157\141\x75\x74\150\137\162\x61\166\145\x6e\x5f\x62\171\137\160\x61\163\x73\137\x72\157\154\145\137\x6d\x61\x70\x70\x69\x6e\147")) {
            goto Tq1;
        }
        $Mx = apply_filters("\x6d\157\x5f\x6f\x61\x75\x74\150\x5f\x72\x61\x76\145\156\x5f\142\x79\137\x70\141\163\x73\137\x72\x6f\x6c\145\x5f\x6d\x61\x70\x70\151\x6e\x67", $pB);
        if (!($Mx === true)) {
            goto f_v;
        }
        return;
        f_v:
        Tq1:
        $be = 0;
        $ga = false;
        if (!has_filter("\155\157\x5f\163\145\164\137\143\x75\x72\x72\x65\x6e\x74\137\x73\151\x74\x65\x5f\x72\157\x6c\x65\x73")) {
            goto IFW;
        }
        $p8 = apply_filters("\x6d\157\137\163\x65\164\137\x63\165\162\x72\145\156\164\x5f\163\x69\x74\145\x5f\x72\x6f\154\145\x73", $this->app_config, $this->group_name, $be, $pB);
        goto EEh;
        IFW:
        $m_ = isset($this->app_config["\162\157\x6c\145\x5f\155\141\160\160\151\x6e\x67\x5f\143\x6f\x75\x6e\164"]) ? intval($this->app_config["\x72\157\x6c\145\137\x6d\x61\160\x70\151\x6e\x67\137\x63\157\165\x6e\164"]) : 0;
        $xb = [];
        $zY = 1;
        vYK:
        if (!($zY <= $m_)) {
            goto iMY;
        }
        $VW = isset($this->app_config["\137\x6d\141\160\160\151\156\147\x5f\x6b\145\x79\137" . $zY]) ? $this->app_config["\x5f\x6d\141\160\160\151\156\147\137\153\x65\171\x5f" . $zY] : '';
        array_push($xb, $VW);
        foreach ($this->group_name as $t1) {
            $j1 = explode("\x2c", $VW);
            $X7 = isset($this->app_config["\x5f\x6d\141\160\x70\151\156\147\x5f\166\141\x6c\x75\145\x5f" . $zY]) ? $this->app_config["\137\155\x61\160\x70\x69\x6e\147\137\x76\x61\x6c\165\x65\137" . $zY] : '';
            $ga = apply_filters("\x6d\157\137\157\141\x75\164\150\137\x63\x6c\x69\145\x6e\164\137\144\171\156\x61\x6d\151\143\137\x76\x61\154\165\x65\x5f\x72\x6f\x6c\145\137\x6d\141\x70\160\151\x6e\147", $j1, $t1, $X7);
            if (!(in_array($t1, $j1) || true === $ga)) {
                goto Vy4;
            }
            if (!$X7) {
                goto C2Z;
            }
            if (!(0 === $be)) {
                goto nlX;
            }
            $pB->set_role('');
            nlX:
            $pB->add_role($X7);
            $be++;
            C2Z:
            Vy4:
            gU1:
        }
        AI_:
        nt9:
        $zY++;
        goto vYK;
        iMY:
        EEh:
        if (empty($this->group_name[0])) {
            goto v3m;
        }
        $BW = '';
        $UJ = get_site_option("\x6d\157\137\157\x61\x75\164\x68\x5f\x61\x70\x70\163\137\154\151\x73\x74");
        $nV = isset($this->app_config["\x75\156\151\x71\x75\x65\137\141\x70\x70\x69\x64"]) ? $this->app_config["\165\156\151\x71\165\x65\x5f\141\160\160\x69\144"] : '';
        if (!is_array($UJ)) {
            goto wyP;
        }
        foreach ($UJ as $gR => $Kn) {
            $wU = $Kn->get_app_config();
            if (!($this->app_config["\x61\160\160\111\144"] == $wU["\x61\160\x70\x49\x64"] && $nV === $gR)) {
                goto F3o;
            }
            MO_Oauth_Debug::mo_oauth_log("\143\157\155\160\141\162\145\x64\40\141\156\x64\x20\146\157\165\156\144\40\x63\x75\x72\162\x65\156\164\x20\141\x70\160\x20\55\40" . $gR);
            $BW = $gR;
            F3o:
            B2T:
        }
        Tuz:
        wyP:
        global $Uj;
        $Et = isset($this->app_config["\x75\163\145\x72\156\x61\x6d\x65\137\141\x74\164\x72"]) ? $this->app_config["\x75\163\x65\162\x6e\x61\155\145\x5f\141\164\x74\162"] : '';
        $rn = isset($this->app_config["\x65\x6d\x61\x69\x6c\137\x61\164\x74\162"]) ? $this->app_config["\x65\x6d\141\x69\x6c\137\x61\164\x74\x72"] : '';
        $g8 = isset($this->app_config["\146\151\x72\163\164\x6e\x61\155\145\137\x61\164\164\162"]) ? $this->app_config["\146\151\162\163\164\x6e\x61\x6d\145\137\141\164\x74\x72"] : '';
        $Sk = isset($this->app_config["\154\141\163\164\x6e\141\x6d\x65\x5f\141\x74\x74\x72"]) ? $this->app_config["\x6c\141\x73\164\156\x61\155\145\137\x61\164\164\162"] : '';
        $sP = $Uj->getnestedattribute($Qu, $Et);
        $g3 = $Uj->getnestedattribute($Qu, $rn);
        $HX = $Uj->getnestedattribute($Qu, $g8);
        $nb = $Uj->getnestedattribute($Qu, $Sk);
        Mo_Oauth_Debug::mo_oauth_log("\123\145\x6e\164\40\144\145\x74\141\x69\154\163\x20\x74\157\40\x6c\145\x61\x72\156\x64\141\163\x68\72\x20");
        Mo_Oauth_Debug::mo_oauth_log($sP);
        Mo_Oauth_Debug::mo_oauth_log(json_encode($this->group_name));
        Mo_Oauth_Debug::mo_oauth_log($BW);
        do_action("\x6d\157\x5f\157\141\165\x74\150\137\x61\164\164\x72\x69\x62\x75\x74\145\163", $sP, $g3, $HX, $nb, $this->group_name, $BW);
        v3m:
        if (!has_filter("\x6d\157\x5f\163\145\x74\x5f\143\x75\x72\x72\x65\x6e\164\137\163\x69\164\x65\x5f\x72\157\154\x65\163")) {
            goto yU1;
        }
        $blog_id = get_current_blog_id();
        if (0 === $p8["\162\157\154\x65\x73"] && isset($this->app_config["\x6d\x6f\137\x73\165\142\x73\x69\x74\145\137\x72\157\154\x65\x5f\155\x61\160\x70\151\156\147"][$blog_id]["\137\x6d\141\x70\160\151\x6e\147\137\166\141\x6c\165\x65\137\x64\145\146\141\x75\154\164"]) && '' !== $this->app_config["\155\x6f\x5f\x73\x75\142\x73\151\164\x65\x5f\x72\157\x6c\145\x5f\155\141\x70\160\151\156\147"][$blog_id]["\x5f\x6d\x61\x70\x70\151\156\x67\137\166\x61\x6c\165\145\x5f\144\145\146\141\165\x6c\x74"]) {
            goto k3A;
        }
        if (!(0 === $p8["\x72\x6f\154\x65\x73"] && empty($this->app_config["\155\x6f\137\163\165\142\x73\x69\164\145\x5f\162\157\x6c\x65\x5f\x6d\141\x70\x70\x69\x6e\147"][$blog_id]["\137\155\x61\x70\160\x69\156\x67\137\166\x61\154\x75\145\x5f\x64\x65\146\141\165\154\x74"]))) {
            goto pv3;
        }
        $pB->set_role("\x73\x75\142\163\x63\162\x69\x62\x65\x72");
        pv3:
        goto UpZ;
        k3A:
        $pB->set_role($this->app_config["\155\157\137\163\165\142\163\x69\164\x65\137\162\157\154\x65\137\155\141\160\x70\x69\156\147"][$blog_id]["\137\155\141\x70\x70\x69\156\x67\x5f\x76\141\154\x75\145\137\144\145\146\141\165\154\x74"]);
        UpZ:
        goto QpW;
        yU1:
        if (!(0 === $be && isset($this->app_config["\x5f\x6d\x61\160\160\151\156\x67\x5f\166\141\154\x75\145\137\144\x65\x66\x61\x75\154\x74"]) && '' !== $this->app_config["\137\x6d\x61\x70\160\151\x6e\x67\137\x76\141\x6c\165\145\x5f\144\x65\x66\141\x75\154\164"])) {
            goto WE7;
        }
        $pB->set_role($this->app_config["\x5f\155\x61\160\160\151\x6e\147\137\x76\141\154\x75\145\137\144\145\146\141\165\154\164"]);
        WE7:
        QpW:
        if (!has_filter("\x6d\157\x5f\163\165\x62\163\x69\164\145\x5f\x63\x68\145\143\x6b\x5f\x61\154\154\157\x77\137\x6c\x6f\x67\151\x6e")) {
            goto zxq;
        }
        $kK = apply_filters("\x6d\157\x5f\x73\165\142\163\x69\x74\x65\x5f\143\x68\145\x63\x6b\x5f\141\154\x6c\x6f\x77\137\x6c\x6f\x67\151\156", $this->app_config, $this->group_name, $p8["\155\x61\160\160\x65\x64\x5f\162\157\x6c\x65\x73"]);
        goto xSJ;
        zxq:
        $j4 = 0;
        if (!(isset($this->app_config["\162\x65\x73\x74\162\151\x63\x74\x5f\154\157\147\151\156\x5f\x66\x6f\162\137\155\x61\160\160\x65\x64\x5f\162\157\x6c\145\163"]) && boolval($this->app_config["\162\145\163\164\162\x69\x63\164\x5f\x6c\157\147\x69\156\137\146\157\162\x5f\155\x61\160\160\x65\x64\137\x72\157\x6c\145\163"]))) {
            goto o4k;
        }
        foreach ($this->group_name as $k9) {
            if (!(in_array($k9, $xb, true) || true === $ga)) {
                goto FGm;
            }
            $j4 = 1;
            FGm:
            jf4:
        }
        bLB:
        if (!($j4 !== 1)) {
            goto s95;
        }
        require_once ABSPATH . "\167\160\55\141\144\155\151\x6e\x2f\151\x6e\x63\x6c\165\x64\145\x73\x2f\165\163\145\x72\x2e\x70\150\160";
        \wp_delete_user($this->user_id);
        global $Uj;
        $Bl = "\x59\157\165\x20\144\x6f\40\x6e\x6f\x74\40\x68\x61\x76\x65\x20\160\145\162\155\x69\x73\x73\151\x6f\x6e\163\40\164\157\40\154\x6f\x67\x69\156\x20\167\151\164\150\x20\171\x6f\165\162\40\143\x75\x72\162\x65\156\x74\40\x72\157\154\145\x73\x2e\x20\x50\x6c\x65\x61\163\x65\x20\x63\x6f\x6e\x74\141\143\x74\40\164\150\x65\40\x41\144\155\x69\x6e\151\163\x74\x72\x61\164\157\x72\x2e";
        $Uj->handle_error($Bl);
        wp_die($Bl);
        s95:
        o4k:
        xSJ:
    }
}
