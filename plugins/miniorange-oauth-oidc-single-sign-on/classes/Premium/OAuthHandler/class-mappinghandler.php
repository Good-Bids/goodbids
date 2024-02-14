<?php


namespace MoOauthClient\Premium;

use MoOauthClient\Mo_Oauth_Debug;
class MappingHandler
{
    private $user_id = 0;
    private $app_config = array();
    private $group_name = '';
    private $is_new_user = false;
    public function __construct($QH = 0, $KY = array(), $kk = '', $L_ = false)
    {
        if (!(!array($KY) || empty($KY))) {
            goto M52;
        }
        return;
        M52:
        $FS = is_array($kk) ? $kk : $this->get_group_array($kk);
        $this->group_name = $FS;
        $this->user_id = $QH;
        $this->app_config = $KY;
        $this->is_new_user = $L_;
    }
    private function get_group_array($q_)
    {
        $KR = json_decode($q_, true);
        return is_array($KR) && json_last_error() === JSON_ERROR_NONE ? $KR : explode("\x3b", $q_);
    }
    public function apply_custom_attribute_mapping($J6)
    {
        if (!(!isset($this->app_config["\x63\x75\163\x74\157\x6d\x5f\141\164\x74\x72\x73\137\x6d\141\x70\x70\x69\x6e\x67"]) || empty($this->app_config["\143\x75\163\x74\157\x6d\137\x61\164\x74\x72\163\x5f\x6d\141\160\x70\151\x6e\147"]))) {
            goto sDn;
        }
        return;
        sDn:
        global $Yh;
        $YI = -1;
        $Sg = $this->app_config["\x63\x75\163\x74\x6f\155\x5f\141\164\164\162\163\137\155\x61\160\x70\x69\156\x67"];
        $jU = [];
        $wj = "\x73\x74\141\164\151\x63\137\166\141\154\165\x65";
        $Vg = [];
        foreach ($Sg as $cW => $LQ) {
            $Kx = [];
            $Yp = '';
            if (strpos($LQ, "\73") !== false) {
                goto ouC;
            }
            $Yp = $Yh->getnestedattribute($J6, $LQ);
            goto q0m;
            ouC:
            $Kx = array_map("\164\162\151\x6d", explode("\x3b", $LQ));
            foreach ($Kx as $JF => $Nc) {
                $rH = $Yh->getnestedattribute($J6, $Nc);
                $Yp .= "\40" . $rH;
                BWv:
            }
            WoY:
            q0m:
            $jU[$cW] = $Yp;
            update_user_meta($this->user_id, $cW, $Yp);
            if (!(strpos($LQ, $wj) !== false)) {
                goto xN1;
            }
            $Vg = explode("\x2e", $LQ);
            $jU[$cW] = $Vg["\x31"];
            xN1:
            tvh:
        }
        MQK:
        update_user_meta($this->user_id, "\155\x6f\137\x6f\141\x75\164\x68\137\x63\165\163\x74\157\155\137\141\x74\x74\x72\x69\x62\x75\x74\145\163", $jU);
    }
    public function sent_attributes_to_integrator($J6, $R0)
    {
        MO_Oauth_Debug::mo_oauth_log("\x53\145\x6e\144\x20\x61\164\164\162\x69\x62\165\164\145\163\x20\164\157\40\151\156\164\145\x67\x72\141\x74\x6f\162\56");
        $Nv = false;
        foreach ($this->group_name as $LQ) {
            if (empty($LQ)) {
                goto aKr;
            }
            $Nv = true;
            goto jQ9;
            aKr:
            Dng:
        }
        jQ9:
        if ($Nv) {
            goto BGh;
        }
        MO_Oauth_Debug::mo_oauth_log("\107\x72\157\165\x70\x20\x6e\x61\x6d\145\40\x61\162\x72\x61\x79\40\x69\x73\x20\145\x6d\x70\164\x79\54\x20\143\x68\145\143\153\40\164\150\145\x20\141\x74\x74\162\x69\142\165\x74\x65\x73\x2e");
        BGh:
        if (!$Nv) {
            goto xzO;
        }
        MO_Oauth_Debug::mo_oauth_log("\x47\162\157\x75\x70\x20\156\141\155\145\x20\x61\162\x72\141\x79\x20\151\x73\40\x6e\x6f\x74\x20\145\x6d\x70\x74\171\x2e");
        $d9 = '';
        $Zo = get_site_option("\155\x6f\x5f\x6f\141\165\164\x68\x5f\x61\160\x70\x73\x5f\x6c\151\x73\x74");
        $XX = isset($this->app_config["\x75\x6e\151\161\165\x65\137\141\x70\160\151\144"]) ? $this->app_config["\165\x6e\151\161\x75\145\137\141\x70\x70\151\x64"] : '';
        if (!is_array($Zo)) {
            goto BW5;
        }
        foreach ($Zo as $zl => $Wb) {
            $ZI = $Wb->get_app_config();
            if (!($this->app_config["\x61\x70\160\x49\144"] == $ZI["\141\160\160\111\144"] && $XX === $zl)) {
                goto Xyh;
            }
            MO_Oauth_Debug::mo_oauth_log("\143\x6f\155\160\x61\162\145\144\x20\141\x6e\x64\40\146\x6f\165\156\x64\x20\143\x75\x72\162\x65\x6e\164\40\141\x70\x70\40\x2d\x20" . $zl);
            $d9 = $zl;
            Xyh:
            VZF:
        }
        ZHo:
        BW5:
        global $Yh;
        $sb = isset($this->app_config["\x75\163\x65\162\x6e\141\155\x65\x5f\x61\x74\164\162"]) ? $this->app_config["\165\x73\x65\162\x6e\141\x6d\x65\137\x61\164\164\162"] : '';
        $YR = isset($this->app_config["\145\x6d\141\151\154\137\141\164\x74\x72"]) ? $this->app_config["\145\x6d\x61\x69\154\x5f\141\x74\164\x72"] : '';
        $T8 = isset($this->app_config["\146\151\162\x73\164\156\141\155\145\137\x61\164\x74\162"]) ? $this->app_config["\146\x69\162\163\x74\156\x61\x6d\x65\x5f\x61\x74\x74\x72"] : '';
        $BX = isset($this->app_config["\154\141\163\164\x6e\x61\155\x65\137\x61\164\x74\x72"]) ? $this->app_config["\x6c\x61\163\164\x6e\141\x6d\x65\137\141\164\x74\x72"] : '';
        $WZ = $Yh->getnestedattribute($J6, $sb);
        $Mv = $Yh->getnestedattribute($J6, $YR);
        $tP = $Yh->getnestedattribute($J6, $T8);
        $rs = $Yh->getnestedattribute($J6, $BX);
        Mo_Oauth_Debug::mo_oauth_log("\123\145\156\164\x20\144\145\164\141\151\154\x73\x20\x74\157\x20\x49\x6e\x74\145\x67\x72\x61\164\157\162\72\40");
        Mo_Oauth_Debug::mo_oauth_log($WZ);
        Mo_Oauth_Debug::mo_oauth_log(json_encode($this->group_name));
        Mo_Oauth_Debug::mo_oauth_log($d9);
        do_action("\x6d\157\x5f\x6f\141\165\x74\150\x5f\141\164\164\162\x69\x62\x75\164\145\x73", $WZ, $Mv, $tP, $rs, $this->group_name, $d9, $R0);
        xzO:
    }
    public function apply_role_mapping($J6)
    {
        if (!has_filter("\155\157\137\x73\x75\142\163\151\x74\145\x5f\143\150\145\x63\x6b\137\141\x6c\154\x6f\x77\x5f\x6c\x6f\x67\x69\x6e")) {
            goto Ggh;
        }
        $ga = apply_filters("\155\157\x5f\163\x75\x62\x73\151\164\145\137\143\x68\x65\143\153\x5f\145\x78\x69\163\164\x69\156\147\x5f\x72\157\x6c\x65\163", $this->app_config, $this->is_new_user);
        if (!($ga === true)) {
            goto vAN;
        }
        return;
        vAN:
        goto IL2;
        Ggh:
        if (!(!$this->is_new_user && isset($this->app_config["\x6b\x65\x65\160\x5f\x65\x78\151\163\x74\x69\156\x67\x5f\165\163\145\x72\137\162\x6f\x6c\x65\163"]) && 1 === intval($this->app_config["\153\145\x65\x70\x5f\145\170\x69\x73\x74\x69\156\x67\137\x75\163\145\x72\x5f\x72\157\154\x65\x73"]))) {
            goto qms;
        }
        $this->sent_attributes_to_integrator($J6, $this->is_new_user);
        Mo_Oauth_Debug::mo_oauth_log("\x4b\145\145\160\x20\145\170\151\x73\164\x69\156\147\x20\x75\163\145\162\163\40\x72\157\x6c\145\54\40\x66\165\156\143\164\x69\x6f\x6e\x61\154\151\164\171\40\x65\x6e\141\x62\x6c\x65\x64\56");
        Mo_Oauth_Debug::mo_oauth_log("\122\157\x6c\145\x20\x77\157\156\x27\x74\x20\142\145\x20\165\x70\x64\141\x74\x65\144\40\x66\x6f\162\40\x65\170\151\x73\164\151\x6e\147\x20\x75\x73\145\x72\x2e");
        return;
        qms:
        IL2:
        $wi = new \WP_User($this->user_id);
        if (!(isset($this->app_config["\x65\156\x61\142\x6c\145\x5f\x72\x6f\154\x65\x5f\x6d\141\x70\x70\151\x6e\147"]) && !boolval($this->app_config["\145\x6e\141\x62\154\x65\137\x72\x6f\154\x65\x5f\155\x61\x70\x70\151\156\147"]))) {
            goto ZRT;
        }
        Mo_Oauth_Debug::mo_oauth_log("\x52\x6f\x6c\x65\x20\x6d\x61\160\160\x69\156\147\40\151\163\x20\144\151\x73\141\x62\154\145\x64");
        if ($this->is_new_user) {
            goto J77;
        }
        if (is_multisite()) {
            goto iT4;
        }
        goto D9L;
        J77:
        $wi->set_role("\163\x75\142\163\143\162\x69\142\x65\162");
        Mo_Oauth_Debug::mo_oauth_log("\104\145\x66\141\165\154\164\x20\163\165\x62\x73\143\162\151\142\x65\162\x20\162\x6f\x6c\145\40\141\163\163\x69\x67\156\40\x74\157\40\156\145\167\40\165\x73\x65\162\x2e");
        goto D9L;
        iT4:
        if ($wi && user_can($this->user_id, "\162\x65\x61\144")) {
            goto TMi;
        }
        $wi->set_role("\x73\165\142\x73\x63\x72\151\x62\x65\x72");
        Mo_Oauth_Debug::mo_oauth_log("\x44\x65\x66\x61\x75\154\x74\x20\163\x75\x62\x73\143\162\x69\142\x65\162\40\x72\157\154\x65\x20\141\163\163\x69\147\x6e\x20\164\x6f\40\163\165\x62\x73\x69\x74\145\40\165\x73\x65\162\x2e");
        TMi:
        D9L:
        return;
        ZRT:
        if (!has_filter("\x6d\157\x5f\x6f\141\x75\164\x68\137\162\141\x76\145\156\x5f\x62\171\x5f\160\141\x73\x73\137\162\157\x6c\145\137\x6d\141\160\160\151\156\147")) {
            goto sZ1;
        }
        $QW = apply_filters("\x6d\157\x5f\x6f\141\165\164\x68\x5f\x72\141\x76\x65\x6e\x5f\142\171\x5f\160\141\163\163\x5f\162\157\154\x65\137\x6d\x61\160\x70\151\156\x67", $wi);
        if (!($QW === true)) {
            goto WCM;
        }
        return;
        WCM:
        sZ1:
        if (!(isset($this->app_config["\x65\170\x74\162\141\x63\x74\x5f\x65\155\141\151\154\x5f\144\157\x6d\141\x69\x6e\x5f\146\x6f\162\137\162\x6f\154\145\x6d\141\x70\x70\151\156\x67"]) && boolval($this->app_config["\x65\x78\164\162\x61\x63\x74\x5f\145\x6d\x61\x69\x6c\137\144\157\x6d\141\x69\x6e\137\x66\157\162\x5f\162\157\154\x65\x6d\141\160\x70\151\x6e\x67"]))) {
            goto ltP;
        }
        Mo_Oauth_Debug::mo_oauth_log("\105\156\x61\142\154\145\x64\40\164\150\145\40\162\157\154\x65\40\x6d\x61\x70\160\x69\156\147\40\x62\141\x73\x65\144\40\x6f\156\x20\144\157\x6d\x61\151\x6e\56");
        if (!empty($this->group_name)) {
            goto jbm;
        }
        MO_Oauth_Debug::mo_oauth_log("\107\x72\x6f\x75\160\x20\156\x61\155\145\x20\141\x72\x72\141\171\40\151\x73\x20\x65\155\x70\x74\x79\x2c\40\x63\150\x65\143\153\x20\x72\x6f\x6c\145\x20\155\141\x70\160\151\156\147\40\x63\x6f\x6e\146\x69\147\x75\x72\141\x74\x69\x6f\x6e\56");
        wp_die("\x45\x6d\141\x69\154\40\144\x6f\x6d\x61\x69\156\40\156\157\164\40\162\145\143\145\x69\x76\x65\x64\x2e\x20\x43\150\145\x63\x6b\x20\171\x6f\165\x72\x20\74\163\x74\x72\157\156\x67\76\122\x6f\x6c\145\40\x4d\x61\x70\x70\x69\x6e\x67\x3c\x2f\x73\x74\162\x6f\x6e\147\x3e\x20\x63\157\x6e\x66\151\147\x75\162\141\164\x69\x6f\x6e\x2e");
        goto Ytn;
        jbm:
        $this->group_mapping_attr = $this->group_name[0];
        Ytn:
        if (!is_array($this->group_mapping_attr) && is_email($this->group_mapping_attr)) {
            goto tV9;
        }
        if (!$this->is_new_user) {
            goto JnX;
        }
        require_once ABSPATH . "\x77\160\55\141\144\155\x69\x6e\57\151\x6e\143\x6c\x75\144\145\x73\57\165\163\145\x72\56\x70\150\x70";
        \wp_delete_user($this->user_id);
        JnX:
        global $Yh;
        MO_Oauth_Debug::mo_oauth_log("\105\x6d\x61\x69\x6c\x20\x61\144\x64\162\x65\x73\x73\40\x6e\x6f\x74\40\x72\x65\x63\x65\x69\166\145\144\40\151\x6e\x20\164\x68\x65\40\143\157\156\x66\x69\x67\x75\x72\x65\x64\x20\147\162\157\165\x70\x20\141\164\x74\x72\151\x62\165\164\145\40\x6e\141\155\x65\x20\x61\163\x20\164\x68\x65\40\157\x70\x74\x69\157\156\40\x69\x73\40\x65\156\x61\142\x6c\145\x64\x20\x74\x6f\x20\x65\170\164\162\x61\x63\x74\x20\x64\x6f\x6d\x61\151\156\x20\167\150\145\x6e\40\x65\x6d\x61\x69\x6c\40\x69\x73\x20\x6d\x61\x70\x70\x65\x64\40\146\157\162\40\x72\157\x6c\145\x20\x6d\x61\160\160\151\156\147\56\x20\103\150\145\x63\153\40\171\x6f\165\162\40\x52\x6f\x6c\145\x20\x4d\x61\x70\160\151\x6e\x67\x20\x63\x6f\156\x66\151\147\165\x72\x61\164\x69\157\156\56");
        $Yh->handle_error("\x45\155\141\x69\x6c\40\x64\157\155\141\x69\x6e\x20\x6e\157\164\x20\162\x65\143\x65\x69\166\145\144\56\x20\x43\150\x65\x63\x6b\x20\171\157\165\x72\40\x3c\x73\164\162\x6f\156\x67\76\x52\x6f\154\145\x20\115\x61\160\x70\151\156\147\x3c\x2f\163\164\x72\157\x6e\147\76\40\143\157\156\x66\x69\147\x75\x72\x61\x74\151\157\x6e\56");
        wp_die("\x45\x6d\x61\x69\x6c\40\144\157\155\141\x69\156\40\x6e\157\164\x20\162\145\143\145\x69\166\145\x64\56\40\x43\x68\145\143\x6b\40\171\157\165\x72\40\74\x73\164\x72\157\156\x67\76\122\x6f\x6c\145\40\x4d\x61\160\x70\151\156\x67\74\57\163\x74\162\157\156\x67\76\40\x63\x6f\156\x66\x69\x67\165\x72\x61\164\x69\157\156\56");
        goto S2W;
        tV9:
        $this->group_mapping_attr = substr($this->group_mapping_attr, strpos($this->group_mapping_attr, "\100") + 1);
        $this->group_name = explode("\x2c", $this->group_mapping_attr);
        S2W:
        ltP:
        $Ei = 0;
        $q0 = false;
        if (!has_filter("\x6d\x6f\137\163\145\164\x5f\x63\x75\x72\x72\145\x6e\164\x5f\163\151\x74\x65\x5f\x72\x6f\x6c\x65\x73")) {
            goto wKZ;
        }
        $RT = apply_filters("\x6d\157\137\x73\145\x74\137\143\x75\162\x72\x65\156\x74\x5f\163\x69\164\x65\x5f\x72\157\x6c\x65\163", $this->app_config, $this->group_name, $Ei, $wi);
        goto jzs;
        wKZ:
        $Yd = isset($this->app_config["\162\157\154\145\x5f\x6d\141\160\160\151\x6e\x67\x5f\x63\x6f\165\156\x74"]) ? intval($this->app_config["\x72\157\x6c\145\137\x6d\141\160\x70\x69\156\x67\137\x63\x6f\165\x6e\164"]) : 0;
        $D8 = array();
        $YI = 1;
        T46:
        if (!($YI <= $Yd)) {
            goto Ol7;
        }
        $S5 = isset($this->app_config["\137\x6d\x61\x70\x70\x69\x6e\x67\x5f\x6b\x65\171\x5f" . $YI]) ? $this->app_config["\137\x6d\141\160\160\151\x6e\x67\137\153\x65\171\137" . $YI] : '';
        array_push($D8, $S5);
        foreach ($this->group_name as $EZ) {
            $ns = array_map("\x74\x72\151\x6d", explode("\54", $S5));
            $Cu = isset($this->app_config["\137\x6d\x61\160\x70\x69\x6e\x67\137\166\x61\x6c\165\x65\137" . $YI]) ? $this->app_config["\x5f\155\x61\160\160\151\x6e\147\x5f\x76\141\154\165\x65\x5f" . $YI] : '';
            $q0 = apply_filters("\155\x6f\x5f\x6f\x61\x75\x74\150\137\143\154\x69\145\156\164\x5f\x64\x79\x6e\141\155\151\143\x5f\x76\x61\154\x75\x65\x5f\x72\157\x6c\145\137\x6d\x61\160\x70\x69\156\x67", $ns, $EZ, $Cu);
            $S3 = array_map("\164\162\x69\x6d", explode("\x2c", $EZ));
            if (!(array_intersect($ns, $S3) == $ns || true === $q0)) {
                goto MNL;
            }
            if (!$Cu) {
                goto g4B;
            }
            if (!(0 === $Ei)) {
                goto H_u;
            }
            $wi->set_role('');
            H_u:
            $wi->add_role($Cu);
            $Ei++;
            g4B:
            MNL:
            Vzg:
        }
        UHg:
        w_s:
        $YI++;
        goto T46;
        Ol7:
        Mo_Oauth_Debug::mo_oauth_log("\125\163\x65\x72\x20\162\x6f\154\145\40\x75\x70\144\x61\x74\x65\144\56");
        jzs:
        if (has_filter("\155\157\137\163\x65\164\137\143\x75\x72\x72\x65\156\x74\x5f\163\151\x74\145\137\162\157\154\x65\163")) {
            goto Bh8;
        }
        $this->sent_attributes_to_integrator($J6, $this->is_new_user);
        Bh8:
        if (!has_filter("\155\x6f\x5f\x73\x65\164\x5f\143\165\x72\162\145\156\x74\137\x73\151\x74\145\x5f\162\x6f\x6c\145\x73")) {
            goto mlb;
        }
        $blog_id = get_current_blog_id();
        if (0 === $RT["\x72\x6f\154\145\163"] && isset($this->app_config["\x6d\x6f\x5f\163\x75\x62\x73\151\164\145\137\x72\x6f\154\145\137\x6d\141\160\160\x69\x6e\x67"][$blog_id]["\137\x6d\141\160\160\x69\x6e\x67\x5f\x76\x61\154\x75\145\137\144\145\x66\141\165\154\x74"]) && '' !== $this->app_config["\x6d\157\137\x73\165\142\x73\x69\164\145\137\162\x6f\x6c\x65\x5f\x6d\x61\160\160\x69\x6e\147"][$blog_id]["\x5f\x6d\x61\160\160\x69\x6e\147\x5f\166\x61\154\165\x65\x5f\x64\145\146\x61\165\x6c\x74"]) {
            goto wfN;
        }
        if (!(0 === $RT["\x72\x6f\154\145\x73"] && empty($this->app_config["\x6d\157\x5f\163\x75\x62\x73\151\164\x65\x5f\162\x6f\154\x65\137\x6d\141\x70\x70\x69\x6e\147"][$blog_id]["\137\155\x61\x70\160\151\156\147\x5f\x76\141\154\165\145\137\x64\145\x66\141\x75\x6c\x74"]))) {
            goto hc_;
        }
        $wi->set_role("\x73\x75\x62\163\x63\162\151\x62\145\162");
        hc_:
        goto JNh;
        wfN:
        $wi->set_role($this->app_config["\155\157\137\163\x75\142\163\x69\x74\145\x5f\x72\157\x6c\145\137\155\x61\160\160\x69\x6e\147"][$blog_id]["\137\155\141\x70\160\151\x6e\147\137\x76\x61\154\x75\145\137\144\145\x66\x61\165\154\164"]);
        JNh:
        goto Vm2;
        mlb:
        if (!(0 === $Ei && isset($this->app_config["\x5f\x6d\x61\x70\x70\x69\x6e\147\137\166\141\x6c\165\145\x5f\x64\x65\146\x61\165\x6c\164"]) && '' !== $this->app_config["\137\155\141\x70\160\151\x6e\147\x5f\166\141\x6c\x75\x65\x5f\x64\145\146\141\x75\154\x74"])) {
            goto cad;
        }
        $Gc = explode("\54", $this->app_config["\137\155\141\160\160\151\156\x67\137\166\x61\154\165\145\137\144\145\146\x61\x75\154\164"]);
        $Ei = 0;
        foreach ($Gc as $bz) {
            if (!(0 === $Ei)) {
                goto VwV;
            }
            $wi->set_role('');
            VwV:
            $bz = trim($bz);
            $wi->add_role($bz);
            $Ei++;
            KrT:
        }
        cfi:
        Mo_Oauth_Debug::mo_oauth_log("\x44\145\x66\141\x75\154\164\40\x72\157\x6c\145\x20\x61\163\163\x69\x67\x6e\x65\x64\40\164\x6f\x20\x75\163\x65\162");
        cad:
        Vm2:
        if (!has_filter("\155\x6f\137\x73\165\142\x73\151\x74\145\137\x63\150\x65\x63\x6b\x5f\x61\154\x6c\x6f\x77\x5f\154\157\x67\x69\156")) {
            goto AQN;
        }
        $G3 = apply_filters("\x6d\x6f\137\163\x75\x62\x73\151\x74\145\x5f\x63\x68\145\143\x6b\137\141\x6c\154\157\x77\137\x6c\157\x67\151\156", $this->app_config, $this->group_name, $RT["\155\x61\160\x70\x65\144\137\162\x6f\x6c\145\x73"]);
        goto GPJ;
        AQN:
        $EL = 0;
        if (!(isset($this->app_config["\x72\145\x73\x74\x72\x69\x63\164\137\x6c\157\147\151\x6e\137\146\x6f\x72\x5f\x6d\x61\x70\160\x65\144\137\162\x6f\x6c\x65\x73"]) && boolval($this->app_config["\x72\145\x73\x74\x72\x69\x63\164\137\154\157\147\151\156\x5f\146\x6f\x72\x5f\155\141\160\160\x65\x64\x5f\162\157\154\x65\x73"]))) {
            goto qCr;
        }
        Mo_Oauth_Debug::mo_oauth_log("\105\156\141\x62\x6c\145\144\x20\146\145\141\164\165\162\x65\72\40\104\x6f\40\x4e\x6f\164\x20\141\x6c\154\157\x77\x20\x6c\x6f\x67\151\156\40\151\146\40\x72\x6f\x6c\x65\163\40\x61\162\145\40\x6e\x6f\164\40\155\x61\x70\160\145\x64");
        foreach ($this->group_name as $Y2) {
            $Y2 = is_int($Y2) ? strval($Y2) : $Y2;
            if (!(in_array($Y2, $D8, true) || true === $q0)) {
                goto exm;
            }
            $EL = 1;
            exm:
            z8u:
        }
        i98:
        if (!($EL !== 1)) {
            goto rUP;
        }
        require_once ABSPATH . "\167\160\55\141\144\x6d\151\156\x2f\151\x6e\143\x6c\165\144\x65\x73\x2f\165\163\x65\x72\x2e\x70\x68\x70";
        if (has_filter("\x6d\157\x5f\x72\x65\x73\x74\x72\151\x63\x74\x5f\167\160\137\165\x73\145\162\137\x64\x65\x6c\145\164\145")) {
            goto Ugo;
        }
        \wp_delete_user($this->user_id);
        Ugo:
        global $Yh;
        $N5 = "\131\157\165\40\x64\157\40\156\157\164\x20\x68\141\x76\x65\x20\x70\145\x72\x6d\x69\x73\163\x69\x6f\x6e\x73\x20\164\157\40\154\x6f\147\151\x6e\40\167\x69\x74\x68\40\x79\157\165\x72\x20\x63\165\162\162\x65\156\x74\40\162\x6f\x6c\145\x73\56\x20\x50\x6c\145\x61\163\x65\x20\x63\157\156\x74\x61\143\x74\x20\164\150\x65\40\x41\144\155\x69\156\151\x73\164\x72\x61\164\x6f\162\56";
        $Yh->handle_error($N5);
        Mo_Oauth_Debug::mo_oauth_log("\x4f\156\x6c\x79\x20\165\x73\145\162\x20\x77\151\x74\x68\x20\155\141\160\160\x65\x64\x20\162\157\x6c\145\x20\x77\151\x6c\154\40\x62\145\40\x61\154\154\157\x77\40\164\157\40\154\x6f\147\151\156\x2c\40\143\x68\145\143\x6b\x20\x72\x6f\x6c\145\x20\155\141\160\160\x69\156\147\56");
        wp_die($N5);
        rUP:
        qCr:
        GPJ:
    }
}
