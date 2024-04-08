<?php


namespace MoOauthClient;

use MoOauthClient\App;
use MoOauthClient\Backup\EnvVarResolver;
class MOUtils
{
    const FREE = 0;
    const STANDARD = 1;
    const PREMIUM = 2;
    const MULTISITE_PREMIUM = 3;
    const ENTERPRISE = 4;
    const ALL_INCLUSIVE_SINGLE_SITE = 5;
    const MULTISITE_ENTERPRISE = 6;
    const ALL_INCLUSIVE_MULTISITE = 7;
    private $is_multisite = false;
    public function __construct()
    {
        remove_action("\x61\144\x6d\151\x6e\x5f\156\157\x74\151\143\145\x73", array($this, "\x6d\x6f\137\157\141\165\164\x68\x5f\163\x75\143\x63\145\x73\163\137\155\x65\163\163\x61\x67\x65"));
        remove_action("\x61\144\x6d\x69\156\x5f\156\x6f\164\151\x63\x65\163", array($this, "\155\157\x5f\157\141\165\x74\150\x5f\x65\x72\x72\x6f\162\x5f\x6d\x65\163\163\x61\147\x65"));
        $this->is_multisite = boolval(get_site_option("\155\x6f\x5f\x6f\x61\x75\x74\x68\x5f\x69\163\x4d\x75\154\x74\x69\x53\151\164\145\x50\154\165\x67\151\156\x52\x65\161\165\x65\x73\164\x65\x64")) ? true : ($this->is_multisite_versi() ? true : false);
    }
    public function mo_oauth_success_message()
    {
        $YU = "\145\x72\162\157\162";
        $fU = $this->mo_oauth_client_get_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION);
        echo "\x3c\x64\x69\166\x20\x63\154\141\163\x73\x3d\x27" . $YU . "\47\76\40\x3c\160\76" . $fU . "\74\x2f\x70\x3e\x3c\x2f\144\151\166\x3e";
    }
    public function mo_oauth_error_message()
    {
        $YU = "\165\x70\x64\141\x74\x65\x64";
        $fU = $this->mo_oauth_client_get_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION);
        echo "\x3c\144\151\x76\x20\x63\154\x61\163\163\75\47" . $YU . "\x27\x3e\74\160\76" . $fU . "\x3c\x2f\160\76\74\x2f\144\151\166\76";
    }
    public function mo_oauth_show_success_message()
    {
        $yk = is_multisite() && $this->is_multisite_versi() ? "\x6e\145\164\167\157\162\x6b\x5f" : '';
        remove_action("{$yk}\141\144\x6d\x69\x6e\x5f\x6e\157\164\x69\x63\145\163", array($this, "\155\x6f\137\157\141\165\x74\x68\137\x73\x75\143\143\x65\163\163\x5f\x6d\145\x73\x73\x61\x67\145"));
        add_action("{$yk}\x61\144\x6d\151\x6e\x5f\156\x6f\164\x69\143\x65\x73", array($this, "\155\157\137\x6f\141\x75\x74\x68\137\x65\x72\x72\x6f\x72\137\x6d\x65\x73\163\x61\147\x65"));
    }
    public function mo_oauth_show_error_message()
    {
        $yk = is_multisite() && $this->is_multisite_versi() ? "\x6e\x65\164\x77\x6f\x72\x6b\x5f" : '';
        remove_action("{$yk}\x61\x64\x6d\x69\x6e\x5f\x6e\157\x74\151\143\x65\163", array($this, "\x6d\x6f\x5f\x6f\141\x75\x74\150\137\x65\x72\x72\157\x72\137\x6d\x65\163\163\141\x67\x65"));
        add_action("{$yk}\x61\x64\x6d\151\x6e\x5f\156\x6f\164\x69\143\145\x73", array($this, "\155\x6f\x5f\157\x61\x75\x74\x68\137\163\x75\143\143\x65\163\163\x5f\155\145\x73\x73\141\147\x65"));
    }
    public function mo_oauth_is_customer_registered()
    {
        $g3 = $this->mo_oauth_client_get_option("\x6d\157\x5f\x6f\141\165\164\x68\137\141\144\155\x69\x6e\x5f\x65\155\x61\151\x6c");
        $i_ = $this->mo_oauth_client_get_option("\155\157\x5f\157\141\x75\164\150\137\141\x64\155\151\156\x5f\x63\165\163\164\157\x6d\x65\162\x5f\153\x65\x79");
        if (!$g3 || !$i_ || !is_numeric(trim($i_))) {
            goto i0;
        }
        return 1;
        goto gH;
        i0:
        return 0;
        gH:
    }
    public function mooauthencrypt($PA)
    {
        $Xh = $this->mo_oauth_client_get_option("\143\165\x73\164\157\x6d\145\x72\x5f\x74\x6f\153\145\156");
        if ($Xh) {
            goto XT;
        }
        return "\x66\141\x6c\x73\145";
        XT:
        $Xh = str_split(str_pad('', strlen($PA), $Xh, STR_PAD_RIGHT));
        $Bx = str_split($PA);
        foreach ($Bx as $Wu => $zs) {
            $d5 = ord($zs) + ord($Xh[$Wu]);
            $Bx[$Wu] = chr($d5 > 255 ? $d5 - 256 : $d5);
            e3:
        }
        qd:
        return base64_encode(join('', $Bx));
    }
    public function mooauthdecrypt($PA)
    {
        $PA = base64_decode($PA);
        $Xh = $this->mo_oauth_client_get_option("\x63\x75\x73\164\157\x6d\x65\x72\x5f\x74\157\x6b\x65\x6e");
        if ($Xh) {
            goto Ih;
        }
        return "\146\141\154\x73\x65";
        Ih:
        $Xh = str_split(str_pad('', strlen($PA), $Xh, STR_PAD_RIGHT));
        $Bx = str_split($PA);
        foreach ($Bx as $Wu => $zs) {
            $d5 = ord($zs) - ord($Xh[$Wu]);
            $Bx[$Wu] = chr($d5 < 0 ? $d5 + 256 : $d5);
            Sz:
        }
        dP:
        return join('', $Bx);
    }
    public function mo_oauth_check_empty_or_null($t_)
    {
        if (!(!isset($t_) || empty($t_))) {
            goto Qx;
        }
        return true;
        Qx:
        return false;
    }
    public function is_multisite_plan()
    {
        return $this->is_multisite;
    }
    public function mo_oauth_is_curl_installed()
    {
        if (in_array("\x63\x75\x72\x6c", get_loaded_extensions())) {
            goto fE;
        }
        return 0;
        goto Vl;
        fE:
        return 1;
        Vl:
    }
    public function mo_oauth_show_curl_error()
    {
        if (!($this->mo_oauth_is_curl_installed() === 0)) {
            goto Ke;
        }
        $this->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\74\141\40\150\162\145\x66\75\42\x68\164\x74\160\72\57\57\160\150\x70\x2e\156\145\164\x2f\155\141\156\165\x61\x6c\57\145\156\57\143\165\162\154\56\x69\x6e\163\164\x61\154\x6c\141\164\151\x6f\156\56\160\x68\160\42\40\x74\141\x72\x67\x65\x74\75\x22\137\x62\154\x61\x6e\153\x22\x3e\120\x48\x50\40\103\125\122\x4c\x20\145\170\164\145\x6e\163\151\157\156\74\57\141\76\x20\x69\163\x20\156\x6f\x74\40\x69\156\x73\164\141\154\154\x65\x64\x20\157\162\x20\x64\x69\x73\x61\x62\x6c\145\x64\x2e\x20\120\154\x65\x61\x73\145\x20\x65\x6e\141\x62\154\x65\40\x69\x74\40\164\x6f\40\x63\157\x6e\164\x69\x6e\x75\x65\56");
        $this->mo_oauth_show_error_message();
        return;
        Ke:
    }
    public function mo_oauth_is_clv()
    {
        $pp = $this->mo_oauth_client_get_option("\x6d\x6f\137\x6f\141\x75\x74\150\x5f\154\x76");
        $pp = boolval($pp) ? $this->mooauthdecrypt($pp) : "\x66\141\x6c\x73\145";
        $pp = !empty($this->mo_oauth_client_get_option("\x6d\157\137\x6f\x61\165\x74\x68\137\154\153")) && "\164\162\165\145" === $pp ? 1 : 0;
        if (!($pp === 0)) {
            goto Rj;
        }
        return $this->verify_lk();
        Rj:
        return $pp;
    }
    public function mo_oauth_hbca_xyake()
    {
        if ($this->mo_oauth_is_customer_registered()) {
            goto Hd;
        }
        return false;
        Hd:
        if ($this->mo_oauth_client_get_option("\x6d\x6f\137\x6f\141\x75\x74\150\137\x61\x64\x6d\151\x6e\x5f\143\165\x73\x74\x6f\x6d\x65\162\137\x6b\x65\x79") > 138200) {
            goto QI;
        }
        return false;
        goto J_;
        QI:
        return true;
        J_:
    }
    public function get_default_app($ll, $BU = false)
    {
        if ($ll) {
            goto vq;
        }
        return false;
        vq:
        $KJ = false;
        $Gi = file_get_contents(MOC_DIR . "\x72\145\163\x6f\165\162\143\145\163\57\141\160\x70\x5f\x63\x6f\155\x70\157\156\145\x6e\164\x73\x2f\144\x65\146\141\x75\x6c\164\x61\160\x70\163\56\x6a\x73\157\156", true);
        $PO = json_decode($Gi, $BU);
        foreach ($PO as $Tc => $V4) {
            if (!($Tc === $ll)) {
                goto zW;
            }
            if ($BU) {
                goto yH;
            }
            $V4->appId = $Tc;
            goto bJ;
            yH:
            $V4["\141\x70\x70\111\144"] = $Tc;
            bJ:
            return $V4;
            zW:
            jO:
        }
        KT:
        return false;
    }
    public function get_plugin_config()
    {
        $Kn = $this->mo_oauth_client_get_option("\x6d\157\x5f\157\141\165\x74\x68\137\x63\154\151\x65\x6e\x74\x5f\x63\x6f\x6e\146\x69\x67");
        return !$Kn || empty($Kn) ? new Config(array()) : $Kn;
    }
    public function get_app_list()
    {
        return $this->mo_oauth_client_get_option("\x6d\157\137\x6f\x61\165\164\x68\x5f\x61\x70\x70\x73\x5f\154\x69\163\164") ? $this->mo_oauth_client_get_option("\x6d\157\x5f\x6f\141\x75\164\x68\x5f\141\x70\x70\x73\x5f\154\151\x73\x74") : false;
    }
    public function get_app_by_name($BW = '')
    {
        $H5 = $this->get_app_list();
        if ($H5) {
            goto mx;
        }
        return false;
        mx:
        if (!('' === $BW || false === $BW)) {
            goto IH;
        }
        $uK = array_values($H5);
        return isset($uK[0]) ? $uK[0] : false;
        IH:
        foreach ($H5 as $Mr => $Fr) {
            if (!($BW === $Mr)) {
                goto dg;
            }
            return $Fr;
            dg:
            vb:
        }
        M8:
        return false;
    }
    public function get_default_app_by_code_name($BW = '')
    {
        $H5 = $this->mo_oauth_client_get_option("\155\157\x5f\x6f\141\x75\x74\150\x5f\141\160\160\x73\137\154\x69\163\164") ? $this->mo_oauth_client_get_option("\x6d\157\137\157\x61\165\x74\x68\137\x61\160\x70\163\x5f\154\x69\163\164") : false;
        if ($H5) {
            goto FT;
        }
        return false;
        FT:
        if (!('' === $BW)) {
            goto Zd;
        }
        $uK = array_values($H5);
        return isset($uK[0]) ? $uK[0] : false;
        Zd:
        foreach ($H5 as $Mr => $Fr) {
            $MC = $Fr->get_app_name();
            if (!($BW === $MC)) {
                goto hh;
            }
            return $this->get_default_app($Fr->get_app_config("\x61\160\x70\x5f\x74\171\x70\145"), true);
            hh:
            Td:
        }
        F3:
        return false;
    }
    public function set_app_by_name($BW, $Wh)
    {
        $H5 = $this->mo_oauth_client_get_option("\x6d\157\137\x6f\141\165\x74\x68\137\x61\x70\x70\x73\x5f\x6c\151\163\164") ? $this->mo_oauth_client_get_option("\x6d\x6f\x5f\157\141\165\x74\x68\137\141\160\x70\163\137\x6c\x69\163\164") : false;
        if ($H5) {
            goto DS;
        }
        return false;
        DS:
        foreach ($H5 as $Mr => $Fr) {
            if (!($BW === $Mr)) {
                goto fb;
            }
            $H5[$Mr] = new App($Wh);
            $H5[$Mr]->set_app_name($Mr);
            $this->mo_oauth_client_update_option("\155\157\x5f\157\x61\x75\164\150\137\x61\160\x70\163\x5f\154\151\x73\x74", $H5);
            return true;
            fb:
            G5:
        }
        OA:
        return false;
    }
    public function mo_oauth_jhuyn_jgsukaj($qR, $vb)
    {
        return $this->mo_oauth_jkhuiysuayhbw($qR, $vb);
    }
    public function mo_oauth_jkhuiysuayhbw($x8, $sd)
    {
        $tA = 0;
        $j4 = false;
        $GX = $this->mo_oauth_client_get_option("\155\157\x5f\157\x61\x75\164\150\137\141\165\x74\x68\157\162\151\x7a\x61\x74\151\157\156\x73");
        if (empty($GX)) {
            goto Mu;
        }
        $tA = $this->mo_oauth_client_get_option("\x6d\157\137\x6f\x61\165\164\x68\137\141\x75\164\x68\157\x72\x69\172\x61\164\151\x6f\156\163");
        Mu:
        $user = $this->mo_oauth_hjsguh_kiishuyauh878gs($x8, $sd);
        if (!$user) {
            goto Mw;
        }
        ++$tA;
        Mw:
        $this->mo_oauth_client_update_option("\x6d\157\x5f\157\x61\x75\164\150\x5f\x61\165\164\150\157\162\151\x7a\x61\x74\x69\x6f\156\163", $tA);
        if (!($tA >= 10)) {
            goto Jr;
        }
        $t2 = base64_decode("\x62\127\x39\146\142\62\106\61\x64\x47\150\x66\132\155\x78\150\132\167\75\x3d");
        $this->mo_oauth_client_update_option($t2, true);
        Jr:
        return $user;
    }
    public function mo_oauth_hjsguh_kiishuyauh878gs($g3, $uQ)
    {
        $F_ = 10;
        $li = false;
        $of = false;
        $Kn = apply_filters("\x6d\x6f\137\157\141\x75\164\150\x5f\160\x61\x73\163\x77\x6f\x72\x64\x5f\x70\157\154\151\x63\x79\x5f\x6d\141\156\x61\147\145\162", $F_);
        if (!is_array($Kn)) {
            goto xq;
        }
        $F_ = intval($Kn["\160\141\163\x73\x77\157\x72\x64\137\x6c\x65\156\x67\164\150"]);
        $li = $Kn["\x73\160\x65\143\x69\x61\154\x5f\143\150\x61\x72\x61\143\164\145\162\163"];
        $of = $Kn["\145\x78\164\162\x61\137\163\160\145\143\x69\141\154\137\x63\x68\141\x72\x61\x63\164\145\162\x73"];
        xq:
        $gq = wp_generate_password($F_, $li, $of);
        $TV = is_email($g3) ? wp_create_user($g3, $gq, $g3) : wp_create_user($g3, $gq);
        $Ef = array("\111\x44" => $TV, "\x75\163\x65\162\x5f\145\x6d\x61\x69\154" => $g3, "\x75\x73\145\x72\137\x6c\157\x67\151\156" => $uQ, "\165\x73\145\x72\137\x6e\x69\x63\x65\x6e\141\155\145" => $uQ, "\146\x69\x72\x73\x74\x5f\156\141\x6d\145" => $uQ);
        do_action("\x75\163\x65\x72\137\162\x65\x67\x69\x73\164\145\162", $TV, $Ef);
        $user = get_user_by("\154\x6f\147\151\156", $g3);
        wp_update_user(array("\111\x44" => $TV, "\146\x69\162\x73\x74\137\x6e\141\155\x65" => $uQ));
        return $user;
    }
    public function check_versi($MV)
    {
        return $this->get_versi() >= $MV;
    }
    public function is_multisite_versi()
    {
        return $this->get_versi() >= 6 || $this->get_versi() == 3;
    }
    public function get_versi()
    {
        return VERSION === "\x6d\x6f\x5f\x6d\x75\154\x74\151\x73\151\x74\x65\x5f\141\154\x6c\137\x69\x6e\143\x6c\165\163\151\166\145\137\x76\x65\x72\163\x69\157\156" ? self::ALL_INCLUSIVE_MULTISITE : (VERSION === "\155\x6f\137\155\165\x6c\x74\151\x73\151\x74\x65\137\x70\x72\145\155\151\165\x6d\137\166\145\162\163\x69\x6f\156" ? self::MULTISITE_PREMIUM : (VERSION === "\x6d\157\137\155\x75\x6c\164\151\x73\x69\164\145\137\145\156\x74\x65\x72\160\162\151\163\x65\x5f\x76\x65\162\x73\x69\x6f\x6e" ? self::MULTISITE_ENTERPRISE : (VERSION === "\155\x6f\137\x61\x6c\154\137\151\x6e\143\x6c\x75\163\x69\x76\145\x5f\x76\x65\162\x73\151\157\x6e" ? self::ALL_INCLUSIVE_SINGLE_SITE : (VERSION === "\x6d\x6f\x5f\145\156\x74\145\162\x70\162\151\x73\x65\137\x76\145\162\163\151\157\x6e" ? self::ENTERPRISE : (VERSION === "\155\157\137\x70\162\x65\155\x69\x75\x6d\x5f\166\145\x72\x73\151\157\x6e" ? self::PREMIUM : (VERSION === "\x6d\157\137\x73\x74\141\x6e\144\x61\162\144\x5f\x76\x65\x72\x73\x69\157\156" ? self::STANDARD : self::FREE))))));
    }
    public function get_plan_type_versi()
    {
        switch ($this->get_versi()) {
            case self::ALL_INCLUSIVE_MULTISITE:
                return "\101\x4c\x4c\x5f\111\116\103\x4c\x55\123\111\126\x45\137\115\125\x4c\x54\x49\123\111\x54\105";
            case self::MULTISITE_PREMIUM:
                return "\115\x55\x4c\x54\x49\123\111\124\105\x5f\x50\x52\105\115\111\x55\x4d";
            case self::MULTISITE_ENTERPRISE:
                return "\x4d\x55\x4c\124\x49\x53\111\124\105\137\x45\x4e\124\x45\x52\120\x52\111\123\105";
            case self::ALL_INCLUSIVE_SINGLE_SITE:
                return "\x45\x4e\x54\x45\x52\120\x52\111\x53\105";
            case self::ENTERPRISE:
                return "\105\x4e\x54\x45\x52\x50\122\111\123\105";
            case self::PREMIUM:
                return '';
            case self::STANDARD:
                return "\x53\124\101\116\104\x41\x52\104";
            case self::FREE:
            default:
                return "\x46\x52\105\105";
        }
        qI:
        fJ:
    }
    public function get_versi_str()
    {
        switch ($this->get_versi()) {
            case self::ALL_INCLUSIVE_MULTISITE:
                return "\101\x4c\114\x5f\x49\x4e\x43\x4c\x55\123\x49\126\105\x5f\115\125\x4c\124\111\123\x49\x54\x45";
            case self::MULTISITE_PREMIUM:
                return "\x4d\x55\114\124\111\x53\x49\124\x45\x5f\120\x52\x45\x4d\111\x55\115";
            case self::MULTISITE_ENTERPRISE:
                return "\x4d\125\x4c\x54\111\123\x49\124\105\137\105\116\x54\105\122\120\122\111\x53\105";
            case self::ALL_INCLUSIVE_SINGLE_SITE:
                return "\x41\x4c\114\137\111\x4e\103\114\x55\x53\111\126\x45\137\x53\111\116\107\x4c\x45\137\x53\x49\124\x45";
            case self::ENTERPRISE:
                return "\x45\116\x54\105\122\x50\x52\x49\x53\x45";
            case self::PREMIUM:
                return "\120\122\105\115\111\125\x4d";
            case self::STANDARD:
                return "\123\124\x41\116\x44\x41\122\104";
            case self::FREE:
            default:
                return "\106\122\105\x45";
        }
        HK:
        M3:
    }
    public function mo_oauth_client_get_option($Mr, $CH = false)
    {
        $t_ = getenv(strtoupper($Mr));
        if (!$t_) {
            goto FF;
        }
        $t_ = EnvVarResolver::resolve_var($Mr, $t_);
        goto W5;
        FF:
        $t_ = is_multisite() && $this->is_multisite ? get_site_option($Mr, $CH) : get_option($Mr, $CH);
        W5:
        if (!(!$t_ || $CH == $t_)) {
            goto ZQ;
        }
        return $CH;
        ZQ:
        return $t_;
    }
    public function mo_oauth_client_update_option($Mr, $t_)
    {
        return is_multisite() && $this->is_multisite ? update_site_option($Mr, $t_) : update_option($Mr, $t_);
    }
    public function mo_oauth_client_delete_option($Mr)
    {
        return is_multisite() && $this->is_multisite ? delete_site_option($Mr) : delete_option($Mr);
    }
    public function array_overwrite($G5, $oV, $DG)
    {
        if ($DG) {
            goto S9;
        }
        array_push($G5, $oV);
        return array_unique($G5);
        S9:
        foreach ($oV as $Mr => $t_) {
            $G5[$Mr] = $t_;
            bl:
        }
        kc:
        return $G5;
    }
    public function gen_rand_str($F_ = 10)
    {
        $lH = "\x61\x62\143\144\145\x66\x67\150\x69\x6a\153\154\x6d\156\x6f\x70\161\x72\163\x74\x75\166\167\170\x79\x7a\101\x42\x43\x44\105\106\107\x48\111\112\113\114\x4d\116\x4f\x50\121\x52\x53\124\x55\x56\127\x58\x59\x5a";
        $o1 = strlen($lH);
        $lm = '';
        $zY = 0;
        GZ:
        if (!($zY < $F_)) {
            goto p3;
        }
        $lm .= $lH[rand(0, $o1 - 1)];
        p5:
        $zY++;
        goto GZ;
        p3:
        return $lm;
    }
    public function parse_url($ht)
    {
        $KJ = array();
        $UR = explode("\x3f", $ht);
        $KJ["\150\x6f\163\x74"] = $UR[0];
        $KJ["\161\165\x65\162\x79"] = isset($UR[1]) && '' !== $UR[1] ? $UR[1] : '';
        if (!(empty($KJ["\161\165\145\x72\171"]) || '' === $KJ["\x71\x75\x65\162\171"])) {
            goto mr;
        }
        return $KJ;
        mr:
        $kt = [];
        foreach (explode("\46", $KJ["\x71\x75\x65\162\x79"]) as $SY) {
            $UR = explode("\75", $SY);
            if (!(is_array($UR) && count($UR) === 2)) {
                goto V1;
            }
            $kt[str_replace("\141\155\160\73", '', $UR[0])] = $UR[1];
            V1:
            if (!(is_array($UR) && "\163\x74\141\164\145" === $UR[0])) {
                goto mu;
            }
            $UR = explode("\x73\164\141\164\145\x3d", $SY);
            $kt["\163\164\x61\x74\x65"] = $UR[1];
            mu:
            H4:
        }
        VK:
        $KJ["\161\x75\145\x72\171"] = is_array($kt) && !empty($kt) ? $kt : [];
        return $KJ;
    }
    public function generate_url($a2)
    {
        if (!(!is_array($a2) || empty($a2))) {
            goto TB;
        }
        return '';
        TB:
        if (isset($a2["\x68\x6f\x73\164"])) {
            goto Zy;
        }
        return '';
        Zy:
        $ht = $a2["\x68\x6f\163\164"];
        $wz = '';
        $zY = 0;
        foreach ($a2["\x71\165\145\x72\x79"] as $h2 => $t_) {
            if (!($zY !== 0)) {
                goto tP;
            }
            $wz .= "\46";
            tP:
            $wz .= "{$h2}\x3d{$t_}";
            $zY += 1;
            JA:
        }
        Vt:
        return $ht . "\77" . $wz;
    }
    public function getnestedattribute($l5, $Mr)
    {
        if (!($Mr == '')) {
            goto Wx;
        }
        return '';
        Wx:
        if (!filter_var($Mr, FILTER_VALIDATE_URL)) {
            goto jV;
        }
        if (isset($l5[$Mr])) {
            goto ax;
        }
        return '';
        goto ZS;
        ax:
        return $l5[$Mr];
        ZS:
        jV:
        $nR = explode("\x2e", $Mr);
        if (count($nR) > 1) {
            goto wx;
        }
        if (isset($l5[$Mr]) && is_array($l5[$Mr])) {
            goto JY;
        }
        $xM = $nR[0];
        if (isset($l5[$xM])) {
            goto Sl;
        }
        return '';
        goto fH;
        Sl:
        if (is_array($l5[$xM])) {
            goto It;
        }
        return $l5[$xM];
        goto V0;
        It:
        return $l5[$xM][0];
        V0:
        fH:
        goto rE;
        JY:
        if (!(count($l5[$Mr]) > 1)) {
            goto Tz;
        }
        return $l5[$Mr];
        Tz:
        if (!isset($l5[$Mr][0])) {
            goto tS;
        }
        return $l5[$Mr][0];
        tS:
        if (!is_array($l5[$Mr])) {
            goto x9;
        }
        return array_key_first($l5[$Mr]);
        x9:
        rE:
        goto Rt;
        wx:
        $xM = $nR[0];
        if (!isset($l5[$xM])) {
            goto Hz;
        }
        $pY = array_count_values($nR);
        if (!($pY[$xM] > 1)) {
            goto wR;
        }
        $Mr = substr_replace($Mr, '', 0, strlen($xM));
        $Mr = trim($Mr, "\x2e");
        return $this->getnestedattribute($l5[$xM], $Mr);
        wR:
        return $this->getnestedattribute($l5[$xM], str_replace($xM . "\56", '', $Mr));
        Hz:
        Rt:
    }
    public function get_client_ip()
    {
        $gF = '';
        if (getenv("\x48\124\124\120\x5f\103\x4c\111\105\116\124\137\111\120")) {
            goto VS;
        }
        if (getenv("\x48\x54\124\120\x5f\x58\x5f\x46\117\x52\x57\101\x52\x44\x45\104\137\x46\x4f\122")) {
            goto wa;
        }
        if (getenv("\x48\x54\124\x50\x5f\x58\x5f\106\117\122\127\101\x52\x44\x45\104")) {
            goto hb;
        }
        if (getenv("\110\124\x54\x50\137\106\117\122\127\101\122\x44\x45\104\137\106\x4f\122")) {
            goto Bx;
        }
        if (getenv("\110\x54\x54\120\x5f\x46\x4f\122\x57\x41\122\x44\105\x44")) {
            goto LE;
        }
        if (getenv("\x52\x45\x4d\117\124\105\137\101\104\x44\x52")) {
            goto LZ;
        }
        $gF = "\x55\116\x4b\x4e\x4f\127\x4e";
        goto xu;
        VS:
        $gF = getenv("\x48\x54\x54\x50\137\103\x4c\111\x45\116\x54\x5f\x49\120");
        goto xu;
        wa:
        $gF = getenv("\x48\x54\x54\x50\137\130\x5f\106\x4f\122\127\x41\122\x44\105\104\x5f\x46\x4f\x52");
        goto xu;
        hb:
        $gF = getenv("\110\124\x54\120\x5f\130\x5f\x46\117\122\x57\101\x52\104\x45\104");
        goto xu;
        Bx:
        $gF = getenv("\x48\x54\x54\x50\x5f\x46\x4f\122\x57\x41\x52\x44\105\104\137\x46\117\x52");
        goto xu;
        LE:
        $gF = getenv("\x48\x54\x54\120\137\x46\117\x52\x57\101\122\104\105\104");
        goto xu;
        LZ:
        $gF = getenv("\122\x45\115\117\124\x45\x5f\x41\x44\x44\122");
        xu:
        return $gF;
    }
    public function get_current_url()
    {
        return (isset($_SERVER["\x48\x54\x54\x50\123"]) ? "\x68\164\164\x70\163" : "\150\164\x74\x70") . "\72\57\x2f{$_SERVER["\x48\x54\x54\120\137\x48\x4f\x53\124"]}{$_SERVER["\x52\x45\121\125\105\123\x54\137\125\122\x49"]}";
    }
    public function get_all_headers()
    {
        $l9 = [];
        foreach ($_SERVER as $uQ => $t_) {
            if (!(substr($uQ, 0, 5) == "\110\x54\x54\120\x5f")) {
                goto B8;
            }
            $l9[str_replace("\40", "\x2d", ucwords(strtolower(str_replace("\137", "\x20", substr($uQ, 5)))))] = $t_;
            B8:
            Ux:
        }
        iq:
        $l9 = array_change_key_case($l9, CASE_UPPER);
        return $l9;
    }
    public function store_info($hp = '', $t_ = false)
    {
        if (!('' === $hp || !$t_)) {
            goto aP;
        }
        return;
        aP:
        setcookie($hp, $t_);
    }
    public function redirect_user($ht = false, $oM = false)
    {
        if (!(false === $ht)) {
            goto Xk;
        }
        return;
        Xk:
        if (!$oM) {
            goto sk;
        }
        echo "\11\x9\x9\74\x73\x63\162\151\x70\164\76\15\xa\11\x9\x9\11\166\x61\162\40\x6d\x79\x57\151\x6e\x64\157\167\x20\x3d\x20\x77\x69\x6e\144\x6f\x77\56\x6f\x70\x65\x6e\50\x22";
        echo $ht;
        echo "\x22\54\x20\42\124\x65\163\164\40\103\x6f\x6e\x66\x69\x67\x75\x72\141\x74\x69\x6f\x6e\42\54\40\x22\x77\x69\x64\164\150\x3d\66\60\60\x2c\40\150\x65\x69\147\150\x74\75\x36\60\x30\42\x29\73\xd\12\11\x9\11\x9\167\x68\151\x6c\145\50\x31\x29\x20\x7b\xd\12\x9\x9\11\x9\11\x69\x66\x28\x6d\x79\x57\x69\x6e\x64\x6f\x77\56\x63\154\157\163\x65\144\50\51\51\x20\x7b\15\xa\11\11\11\11\x9\x9\44\50\144\157\x63\x75\155\145\x6e\x74\51\x2e\x74\x72\151\x67\x67\145\x72\x28\x22\143\x6f\x6e\x66\x69\x67\x5f\164\x65\x73\164\145\144\42\51\x3b\15\12\11\x9\11\x9\x9\11\x62\162\x65\141\x6b\73\15\12\x9\11\11\11\x9\x7d\x20\x65\x6c\163\x65\x20\173\x63\157\156\164\x69\x6e\165\x65\x3b\175\xd\xa\11\11\x9\11\x7d\xd\xa\11\11\x9\74\57\163\143\x72\x69\160\164\x3e\15\xa\x9\x9\11";
        sk:
        echo "\x9\x9\74\x73\x63\x72\x69\x70\x74\x3e\15\12\11\11\11\167\x69\156\x64\157\x77\x2e\154\157\143\x61\164\151\157\x6e\x2e\x72\x65\160\154\141\x63\145\50\x22";
        echo $ht;
        echo "\x22\51\x3b\xd\12\x9\11\74\x2f\x73\143\162\151\x70\164\x3e\15\xa\11\x9";
        exit;
    }
    public function is_ajax_request()
    {
        return defined("\104\x4f\x49\116\x47\137\x41\x4a\101\130") && DOING_AJAX;
    }
    public function deactivate_plugin()
    {
        $this->mo_oauth_client_delete_option("\x68\x6f\x73\164\x5f\156\x61\x6d\x65");
        $this->mo_oauth_client_delete_option("\156\x65\x77\137\162\145\x67\x69\x73\164\x72\x61\164\151\x6f\x6e");
        $this->mo_oauth_client_delete_option("\x6d\x6f\137\157\x61\x75\164\x68\x5f\x61\x64\x6d\x69\156\137\x70\x68\x6f\x6e\x65");
        $this->mo_oauth_client_delete_option("\166\x65\x72\x69\x66\x79\x5f\x63\165\x73\164\x6f\x6d\x65\x72");
        $this->mo_oauth_client_delete_option("\155\157\x5f\x6f\x61\x75\x74\150\137\x61\x64\155\151\x6e\x5f\143\165\x73\x74\x6f\155\145\162\137\x6b\x65\x79");
        $this->mo_oauth_client_delete_option("\155\x6f\x5f\157\141\165\164\150\x5f\141\144\x6d\x69\x6e\x5f\141\160\151\137\x6b\x65\171");
        $this->mo_oauth_client_delete_option("\155\157\x5f\157\141\165\x74\x68\137\x6e\x65\x77\137\x63\x75\163\x74\x6f\x6d\145\x72");
        $this->mo_oauth_client_delete_option("\143\165\163\164\x6f\155\145\162\x5f\x74\157\153\145\x6e");
        $this->mo_oauth_client_delete_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION);
        $this->mo_oauth_client_delete_option("\155\157\x5f\x6f\x61\x75\x74\150\x5f\162\x65\147\x69\163\x74\162\141\164\151\157\x6e\x5f\163\x74\141\164\165\x73");
        $this->mo_oauth_client_delete_option("\155\x6f\137\x6f\x61\x75\x74\x68\137\x6e\x65\x77\x5f\143\x75\163\x74\x6f\155\x65\162");
        $this->mo_oauth_client_delete_option("\156\x65\167\x5f\162\145\x67\x69\x73\x74\x72\x61\x74\151\x6f\x6e");
        $this->mo_oauth_client_delete_option("\x6d\157\137\x6f\x61\x75\164\150\137\x6c\157\x67\151\156\x5f\151\x63\157\x6e\137\143\165\163\164\157\x6d\x5f\x68\x65\x69\147\150\x74");
        $this->mo_oauth_client_delete_option("\155\x6f\x5f\x6f\141\165\x74\x68\137\x6c\x6f\x67\151\156\x5f\x69\x63\157\x6e\137\x63\x75\163\164\157\155\137\x73\151\172\145");
        $this->mo_oauth_client_delete_option("\155\157\137\x6f\141\165\164\x68\137\154\x6f\147\151\156\x5f\151\143\157\x6e\x5f\143\165\163\x74\157\x6d\x5f\x63\x6f\154\157\x72");
        $this->mo_oauth_client_delete_option("\x6d\x6f\137\x6f\141\165\164\150\x5f\x6c\157\147\151\x6e\x5f\x69\143\x6f\156\x5f\143\165\x73\x74\x6f\x6d\137\x62\157\165\x6e\x64\x61\x72\171");
    }
    public function base64url_encode($p3)
    {
        return rtrim(strtr(base64_encode($p3), "\x2b\57", "\x2d\x5f"), "\x3d");
    }
    public function base64url_decode($p3)
    {
        return base64_decode(str_pad(strtr($p3, "\x2d\137", "\53\x2f"), strlen($p3) % 4, "\x3d", STR_PAD_RIGHT));
    }
    function export_plugin_config($TO = false)
    {
        $uu = [];
        $UJ = [];
        $lX = [];
        $uu = $this->get_plugin_config();
        $UJ = get_site_option("\x6d\157\137\157\x61\165\x74\x68\x5f\x61\160\x70\x73\137\154\x69\163\x74");
        if (empty($uu)) {
            goto tO;
        }
        $uu = $uu->get_current_config();
        tO:
        if (!is_array($UJ)) {
            goto nx;
        }
        foreach ($UJ as $gR => $Wh) {
            if (!is_array($Wh)) {
                goto op;
            }
            $Wh = new App($Wh);
            op:
            $sZ = $Wh->get_app_config('', false);
            if (!$TO) {
                goto KK;
            }
            unset($sZ["\x63\154\151\x65\156\164\137\x69\x64"]);
            unset($sZ["\143\x6c\x69\145\x6e\x74\x5f\163\145\x63\x72\145\x74"]);
            KK:
            $lX[$gR] = $sZ;
            dh:
        }
        o3:
        nx:
        $fX = ["\x70\154\165\x67\x69\156\137\143\157\x6e\146\x69\x67" => $uu, "\141\x70\160\x5f\143\x6f\156\x66\x69\147\x73" => $lX];
        $fX = apply_filters("\x6d\x6f\x5f\164\162\137\147\x65\164\x5f\154\x69\143\x65\156\163\x65\137\x63\x6f\x6e\146\x69\147", $fX);
        return $fX;
    }
    private function verify_lk()
    {
        $me = new \MoOauthClient\Standard\Customer();
        $SJ = $this->mo_oauth_client_get_option("\x6d\x6f\137\157\x61\165\x74\150\137\154\x69\143\x65\x6e\163\145\137\x6b\145\171");
        if (!empty($SJ)) {
            goto wc;
        }
        return 0;
        wc:
        $nQ = $me->XfskodsfhHJ($SJ);
        $nQ = json_decode($nQ, true);
        return isset($nQ["\x73\164\141\x74\x75\163"]) && "\x53\x55\x43\x43\x45\x53\123" === $nQ["\163\x74\x61\x74\165\x73"];
    }
    public function is_valid_jwt($Ju = '')
    {
        $UR = explode("\x2e", $Ju);
        if (!(count($UR) === 3)) {
            goto I2;
        }
        return true;
        I2:
        return false;
    }
    public function validate_appslist($H5)
    {
        if (is_array($H5)) {
            goto ne;
        }
        return false;
        ne:
        foreach ($H5 as $Mr => $Fr) {
            if (!$Fr instanceof \MoOauthClient\App) {
                goto gr;
            }
            goto hi;
            gr:
            return false;
            hi:
        }
        In:
        return true;
    }
    public function handle_error($Bl)
    {
        do_action("\x6d\157\x5f\164\162\137\x6c\x6f\x67\x69\156\x5f\x65\x72\162\x6f\162\x73", $Bl);
    }
    public function set_transient($Mr, $t_, $WB)
    {
        return is_multisite() && $this->is_multisite ? set_site_transient($Mr, $t_, $WB) : set_transient($Mr, $t_, $WB);
    }
    public function get_transient($Mr)
    {
        return is_multisite() && $this->is_multisite ? get_site_transient($Mr) : get_transient($Mr);
    }
    public function delete_transient($Mr)
    {
        return is_multisite() && $this->is_multisite ? delete_site_transient($Mr) : delete_transient($Mr);
    }
}
