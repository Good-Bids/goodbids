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
        remove_action("\x61\x64\x6d\x69\156\137\156\157\x74\x69\143\145\163", array($this, "\x6d\157\x5f\157\x61\x75\x74\x68\x5f\163\165\143\x63\145\163\x73\137\x6d\145\163\163\x61\x67\145"));
        remove_action("\x61\144\155\x69\x6e\x5f\x6e\x6f\x74\151\x63\145\163", array($this, "\x6d\157\x5f\x6f\x61\165\x74\x68\x5f\x65\162\x72\157\x72\x5f\155\145\163\163\141\147\x65"));
        $this->is_multisite = boolval(get_site_option("\155\157\x5f\157\141\x75\x74\x68\x5f\x69\x73\115\165\154\164\x69\x53\151\x74\145\x50\x6c\165\x67\151\156\x52\145\161\x75\145\163\x74\145\144")) ? true : ($this->is_multisite_versi() ? true : false);
    }
    public function mo_oauth_success_message()
    {
        $Ss = "\145\162\162\157\x72";
        $ri = $this->mo_oauth_client_get_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION);
        $to = array("\x61" => array("\150\x72\145\x66" => array(), "\164\x61\x72\x67\x65\x74" => array()));
        echo "\x3c\144\151\x76\40\143\154\x61\x73\163\x3d\47" . esc_attr($Ss) . "\x27\76\40\x3c\x70\76" . wp_kses($ri, $to) . "\74\57\160\76\x3c\x2f\x64\x69\x76\76";
    }
    public function mo_oauth_error_message()
    {
        $Ss = "\165\x70\x64\x61\164\x65\144";
        $ri = $this->mo_oauth_client_get_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION);
        $to = array("\141" => array("\150\162\145\146" => array(), "\x74\x61\162\x67\x65\x74" => array()));
        echo "\74\144\151\166\40\143\154\141\x73\163\x3d\47" . esc_attr($Ss) . "\x27\x3e\x3c\160\76" . wp_kses($ri, $to) . "\x3c\57\x70\x3e\74\x2f\x64\x69\x76\76";
    }
    public function mo_oauth_show_success_message()
    {
        $o3 = is_multisite() && $this->is_multisite_versi() ? "\x6e\145\x74\x77\x6f\162\x6b\137" : '';
        remove_action("{$o3}\x61\144\155\151\156\x5f\156\157\x74\x69\x63\145\163", array($this, "\155\x6f\137\157\x61\165\x74\x68\137\x73\165\143\x63\x65\x73\x73\x5f\155\x65\x73\163\x61\x67\145"));
        add_action("{$o3}\141\144\155\x69\x6e\x5f\156\157\164\x69\143\x65\163", array($this, "\155\157\137\157\141\165\x74\x68\137\x65\162\x72\x6f\x72\137\155\x65\x73\163\x61\147\x65"));
    }
    public function mo_oauth_show_error_message()
    {
        $o3 = is_multisite() && $this->is_multisite_versi() ? "\x6e\145\x74\167\157\162\x6b\137" : '';
        remove_action("{$o3}\141\x64\155\151\156\137\x6e\157\x74\151\x63\145\x73", array($this, "\x6d\x6f\x5f\157\141\x75\x74\150\x5f\145\x72\x72\x6f\x72\137\x6d\x65\163\x73\x61\147\145"));
        add_action("{$o3}\141\x64\x6d\x69\156\137\x6e\x6f\164\151\x63\x65\x73", array($this, "\155\x6f\137\157\x61\x75\x74\x68\137\x73\165\143\x63\145\163\x73\x5f\155\x65\x73\163\x61\x67\145"));
    }
    public function mo_oauth_client_filter_error($N5)
    {
        $N5 = apply_filters("\x6d\157\x5f\x6f\x61\x75\164\150\137\155\157\144\x69\146\171\137\x65\x72\162\157\x72", $N5);
        return $N5;
    }
    public function mo_oauth_is_customer_registered()
    {
        $Mv = $this->mo_oauth_client_get_option("\x6d\157\x5f\x6f\x61\165\164\150\137\141\x64\155\151\x6e\x5f\x65\155\141\x69\154");
        $oe = $this->mo_oauth_client_get_option("\155\157\137\157\141\165\164\150\137\141\x64\x6d\x69\x6e\x5f\x63\x75\163\x74\157\155\x65\x72\x5f\153\145\x79");
        if (!$Mv || !$oe || !is_numeric(trim($oe))) {
            goto mp;
        }
        return 1;
        goto R3;
        mp:
        return 0;
        R3:
    }
    public function mooauthencrypt($d5)
    {
        $bc = $this->mo_oauth_client_get_option("\x63\165\x73\x74\x6f\x6d\145\x72\x5f\164\157\x6b\x65\x6e");
        if ($bc) {
            goto lR;
        }
        return "\146\x61\x6c\163\x65";
        lR:
        $bc = str_split(str_pad('', strlen($d5), $bc, STR_PAD_RIGHT));
        $Xq = str_split($d5);
        foreach ($Xq as $JF => $w_) {
            $DV = ord($w_) + ord($bc[$JF]);
            $Xq[$JF] = chr($DV > 255 ? $DV - 256 : $DV);
            II:
        }
        MR:
        return base64_encode(join('', $Xq));
    }
    public function mooauthdecrypt($d5)
    {
        $d5 = base64_decode($d5);
        $bc = $this->mo_oauth_client_get_option("\143\165\x73\x74\x6f\155\x65\162\137\x74\157\153\x65\156");
        if ($bc) {
            goto Rt;
        }
        return "\x66\141\154\x73\145";
        Rt:
        $bc = str_split(str_pad('', strlen($d5), $bc, STR_PAD_RIGHT));
        $Xq = str_split($d5);
        foreach ($Xq as $JF => $w_) {
            $DV = ord($w_) - ord($bc[$JF]);
            $Xq[$JF] = chr($DV < 0 ? $DV + 256 : $DV);
            YD:
        }
        EP:
        return join('', $Xq);
    }
    public function mo_oauth_check_empty_or_null($LQ)
    {
        if (!(!isset($LQ) || empty($LQ))) {
            goto C1;
        }
        return true;
        C1:
        return false;
    }
    public function is_multisite_plan()
    {
        return $this->is_multisite;
    }
    public function mo_oauth_is_curl_installed()
    {
        if (in_array("\143\165\x72\154", get_loaded_extensions())) {
            goto Fr;
        }
        return 0;
        goto Wj;
        Fr:
        return 1;
        Wj:
    }
    public function mo_oauth_show_curl_error()
    {
        if (!($this->mo_oauth_is_curl_installed() === 0)) {
            goto AE;
        }
        $this->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\x3c\x61\40\150\162\145\146\x3d\x22\150\x74\164\160\72\x2f\57\160\150\160\x2e\156\x65\x74\x2f\x6d\x61\156\165\x61\x6c\57\145\156\57\x63\x75\x72\x6c\56\x69\156\x73\x74\141\x6c\x6c\x61\x74\151\157\156\x2e\160\x68\160\42\x20\x74\141\x72\x67\x65\x74\75\42\x5f\x62\154\x61\x6e\x6b\42\x3e\120\110\120\40\103\125\122\114\x20\x65\x78\164\145\156\x73\151\157\156\x3c\x2f\141\76\x20\x69\x73\40\156\157\x74\x20\151\x6e\163\164\x61\x6c\154\145\x64\x20\157\x72\40\x64\x69\x73\x61\x62\154\x65\144\x2e\40\x50\x6c\145\141\x73\145\x20\x65\156\141\142\154\x65\x20\x69\164\40\x74\x6f\x20\x63\x6f\156\164\x69\x6e\165\x65\56");
        $this->mo_oauth_show_error_message();
        return;
        AE:
    }
    public function mo_oauth_is_clv()
    {
        $Fv = $this->mo_oauth_client_get_option("\155\157\137\x6f\x61\x75\164\150\x5f\154\x76");
        $Fv = boolval($Fv) ? $this->mooauthdecrypt($Fv) : "\x66\x61\x6c\163\x65";
        $Fv = !empty($this->mo_oauth_client_get_option("\155\157\x5f\x6f\141\x75\x74\x68\x5f\154\153")) && "\x74\x72\165\145" === $Fv ? 1 : 0;
        if (!($Fv === 0)) {
            goto dI;
        }
        return $this->verify_lk();
        dI:
        return $Fv;
    }
    public function mo_oauth_is_cld()
    {
        if (!(empty($this->mo_oauth_client_get_option("\155\157\137\157\141\x75\164\x68\137\154\144")) || empty($this->mo_oauth_client_get_option("\x63\165\163\164\x6f\155\x65\x72\x5f\x74\157\x6b\x65\x6e")))) {
            goto kx;
        }
        return 1;
        kx:
        $Fv = $this->mo_oauth_client_get_option("\x6d\x6f\x5f\157\x61\165\164\x68\137\154\x64");
        $Fv = "\x74\162\x75\145" === $this->mooauthdecrypt($Fv) ? 1 : 0;
        return $Fv;
    }
    public function mo_oauth_hbca_xyake()
    {
        if ($this->mo_oauth_is_customer_registered()) {
            goto K1;
        }
        return false;
        K1:
        if ($this->mo_oauth_client_get_option("\155\x6f\137\x6f\141\165\164\150\x5f\x61\x64\x6d\151\x6e\137\143\x75\163\164\157\155\x65\162\137\x6b\145\x79") > 138200) {
            goto Fk;
        }
        return false;
        goto Rc;
        Fk:
        return true;
        Rc:
    }
    public function get_default_app($Sw, $ev = false)
    {
        if ($Sw) {
            goto v5;
        }
        return false;
        v5:
        $QN = false;
        $qE = file_get_contents(MOC_DIR . "\x72\145\163\157\165\x72\x63\145\x73\x2f\141\x70\160\137\x63\x6f\155\160\157\156\x65\x6e\x74\163\57\144\145\x66\x61\x75\154\x74\x61\x70\x70\163\x2e\152\x73\157\x6e", true);
        $Wl = json_decode($qE, $ev);
        foreach ($Wl as $xb => $NY) {
            if (!($xb === $Sw)) {
                goto f_;
            }
            if ($ev) {
                goto OF;
            }
            $NY->appId = $xb;
            goto Tw;
            OF:
            $NY["\141\x70\x70\111\144"] = $xb;
            Tw:
            return $NY;
            f_:
            Hc:
        }
        Gk:
        return false;
    }
    public function get_plugin_config()
    {
        $Wb = $this->mo_oauth_client_get_option("\x6d\x6f\137\x6f\x61\165\164\x68\x5f\x63\x6c\151\145\x6e\x74\x5f\143\x6f\156\x66\x69\x67");
        return !$Wb || empty($Wb) ? new Config(array()) : $Wb;
    }
    public function get_app_list()
    {
        return $this->mo_oauth_client_get_option("\x6d\157\137\x6f\141\165\164\150\137\141\160\160\x73\137\154\x69\163\164") ? $this->mo_oauth_client_get_option("\x6d\x6f\137\x6f\141\165\164\x68\137\x61\x70\x70\x73\137\154\151\163\164") : false;
    }
    public function get_app_by_name($d9 = '')
    {
        $mc = $this->get_app_list();
        if ($mc) {
            goto jH;
        }
        return false;
        jH:
        if (!('' === $d9 || false === $d9)) {
            goto p7;
        }
        $aD = array_values($mc);
        return isset($aD[0]) ? $aD[0] : false;
        p7:
        foreach ($mc as $cW => $F8) {
            if (!($d9 === $cW)) {
                goto Bi;
            }
            return $F8;
            Bi:
            if (!((int) $d9 === $cW)) {
                goto v_;
            }
            return $F8;
            v_:
            ku:
        }
        dJ:
        return false;
    }
    public function get_default_app_by_code_name($d9 = '')
    {
        $mc = $this->mo_oauth_client_get_option("\155\x6f\137\x6f\x61\165\164\150\x5f\x61\x70\160\x73\x5f\x6c\x69\x73\x74") ? $this->mo_oauth_client_get_option("\155\x6f\137\157\141\165\x74\150\x5f\141\x70\x70\x73\137\x6c\x69\x73\x74") : false;
        if ($mc) {
            goto RI;
        }
        return false;
        RI:
        if (!('' === $d9)) {
            goto gd;
        }
        $aD = array_values($mc);
        return isset($aD[0]) ? $aD[0] : false;
        gd:
        foreach ($mc as $cW => $F8) {
            $HT = $F8->get_app_name();
            if (!($d9 === $HT)) {
                goto Ur;
            }
            return $this->get_default_app($F8->get_app_config("\141\160\x70\137\x74\171\160\x65"), true);
            Ur:
            yY:
        }
        YV:
        return false;
    }
    public function set_app_by_name($d9, $KY)
    {
        $mc = $this->mo_oauth_client_get_option("\155\x6f\x5f\157\141\x75\x74\x68\x5f\x61\x70\x70\x73\x5f\154\x69\x73\164") ? $this->mo_oauth_client_get_option("\x6d\157\137\157\x61\165\x74\150\137\141\160\160\163\137\x6c\x69\x73\164") : false;
        if ($mc) {
            goto Jk;
        }
        return false;
        Jk:
        foreach ($mc as $cW => $F8) {
            if (!(gettype($cW) === "\x69\x6e\x74\x65\147\x65\162")) {
                goto GN;
            }
            $cW = strval($cW);
            GN:
            if (!($d9 === $cW)) {
                goto Qu;
            }
            $mc[$cW] = new App($KY);
            $mc[$cW]->set_app_name($cW);
            $this->mo_oauth_client_update_option("\x6d\157\x5f\157\141\x75\x74\150\x5f\x61\x70\x70\x73\137\x6c\151\x73\164", $mc);
            return true;
            Qu:
            os:
        }
        RR:
        return false;
    }
    public function mo_oauth_jhuyn_jgsukaj($XB, $R2)
    {
        return $this->mo_oauth_jkhuiysuayhbw($XB, $R2);
    }
    public function mo_oauth_jkhuiysuayhbw($gc, $sD)
    {
        $XK = 0;
        $EL = false;
        $an = $this->mo_oauth_client_get_option("\155\157\x5f\157\x61\x75\x74\x68\137\141\x75\x74\150\157\x72\151\x7a\141\164\151\x6f\x6e\x73");
        if (empty($an)) {
            goto sI;
        }
        $XK = $this->mo_oauth_client_get_option("\x6d\157\137\x6f\141\165\164\150\137\x61\x75\164\150\x6f\162\x69\172\141\164\x69\157\156\x73");
        sI:
        $user = $this->mo_oauth_hjsguh_kiishuyauh878gs($gc, $sD);
        if (!$user) {
            goto ri;
        }
        ++$XK;
        ri:
        $this->mo_oauth_client_update_option("\x6d\157\137\x6f\141\165\x74\150\137\141\x75\x74\x68\157\x72\151\172\141\164\x69\x6f\x6e\x73", $XK);
        if (!($XK >= 10)) {
            goto Q1;
        }
        $CA = base64_decode('bW9fb2F1dGhfZmxhZw==');
        $this->mo_oauth_client_update_option($CA, true);
        Q1:
        return $user;
    }
    public function mo_oauth_hjsguh_kiishuyauh878gs($Mv, $un)
    {
        $qM = 10;
        $E4 = false;
        $Hp = false;
        $Wb = apply_filters("\155\x6f\x5f\157\141\165\164\x68\x5f\x70\x61\163\x73\x77\157\x72\144\137\x70\157\x6c\151\x63\171\x5f\x6d\x61\156\141\x67\145\162", $qM);
        if (!is_array($Wb)) {
            goto N_;
        }
        $qM = intval($Wb["\x70\141\163\x73\167\157\162\x64\x5f\x6c\x65\156\x67\164\150"]);
        $E4 = $Wb["\x73\160\145\143\x69\141\x6c\137\143\150\141\162\x61\143\164\145\x72\163"];
        $Hp = $Wb["\x65\x78\164\162\x61\137\x73\x70\145\143\x69\141\x6c\x5f\143\150\x61\x72\x61\143\164\x65\x72\163"];
        N_:
        $E3 = wp_generate_password($qM, $E4, $Hp);
        $QH = is_email($Mv) ? wp_create_user($Mv, $E3, $Mv) : wp_create_user($Mv, $E3);
        $Ry = array("\111\x44" => $QH, "\165\163\x65\x72\x5f\x65\x6d\141\151\154" => $Mv, "\x75\163\145\162\x5f\x6c\x6f\x67\151\x6e" => $un, "\165\x73\145\162\137\156\151\143\145\x6e\x61\155\x65" => $un, "\146\151\x72\x73\x74\x5f\156\x61\155\145" => $un);
        do_action("\x75\163\x65\x72\x5f\162\145\x67\151\163\164\145\162", $QH, $Ry);
        $user = get_user_by("\154\157\x67\151\156", $Mv);
        wp_update_user(array("\111\x44" => $QH, "\146\151\162\x73\164\137\x6e\141\x6d\x65" => $un));
        return $user;
    }
    public function check_versi($vw)
    {
        return $this->get_versi() >= $vw;
    }
    public function is_multisite_versi()
    {
        return $this->get_versi() >= 6 || $this->get_versi() == 3;
    }
    public function get_versi()
    {
        return VERSION === "\155\157\137\155\x75\x6c\164\151\x73\x69\x74\145\x5f\141\154\154\x5f\x69\156\x63\154\165\163\151\166\x65\x5f\166\x65\x72\163\151\157\156" ? self::ALL_INCLUSIVE_MULTISITE : (VERSION === "\x6d\x6f\x5f\x6d\x75\x6c\164\x69\163\x69\164\x65\x5f\x70\162\x65\x6d\x69\x75\155\x5f\166\x65\x72\x73\x69\157\x6e" ? self::MULTISITE_PREMIUM : (VERSION === "\155\157\x5f\x6d\x75\154\x74\151\x73\151\x74\145\137\145\x6e\164\x65\x72\160\162\151\163\x65\x5f\x76\x65\x72\163\151\x6f\x6e" ? self::MULTISITE_ENTERPRISE : (VERSION === "\x6d\157\137\141\x6c\x6c\137\151\x6e\x63\154\x75\163\x69\x76\x65\x5f\166\x65\162\x73\x69\157\156" ? self::ALL_INCLUSIVE_SINGLE_SITE : (VERSION === "\x6d\157\137\145\156\164\x65\162\160\162\x69\x73\145\137\166\x65\162\x73\151\157\156" ? self::ENTERPRISE : (VERSION === "\155\x6f\137\x70\x72\x65\x6d\151\x75\155\x5f\166\x65\162\x73\x69\157\156" ? self::PREMIUM : (VERSION === "\155\x6f\x5f\x73\x74\x61\x6e\x64\141\162\x64\137\166\x65\x72\163\x69\x6f\x6e" ? self::STANDARD : self::FREE))))));
    }
    public function get_plan_type_versi()
    {
        switch ($this->get_versi()) {
            case self::ALL_INCLUSIVE_MULTISITE:
                return "\x41\x4c\114\x5f\111\x4e\103\114\x55\123\x49\x56\x45\x5f\115\x55\x4c\124\x49\123\x49\x54\105";
            case self::MULTISITE_PREMIUM:
                return "\115\125\x4c\x54\x49\123\x49\x54\x45\x5f\x50\x52\x45\115\x49\125\115";
            case self::MULTISITE_ENTERPRISE:
                return "\115\125\114\x54\111\123\x49\x54\105\137\105\x4e\124\105\122\120\122\111\x53\105";
            case self::ALL_INCLUSIVE_SINGLE_SITE:
                return "\x45\x4e\x54\105\x52\120\x52\111\x53\x45";
            case self::ENTERPRISE:
                return "\105\116\x54\105\x52\120\122\111\123\x45";
            case self::PREMIUM:
                return '';
            case self::STANDARD:
                return "\x53\x54\101\116\x44\101\x52\104";
            case self::FREE:
            default:
                return "\x46\122\x45\105";
        }
        gQ:
        Co:
    }
    public function get_versi_str()
    {
        switch ($this->get_versi()) {
            case self::ALL_INCLUSIVE_MULTISITE:
                return "\101\114\114\137\111\x4e\103\x4c\125\x53\111\126\x45\137\x4d\125\x4c\124\111\x53\x49\x54\105";
            case self::MULTISITE_PREMIUM:
                return "\x4d\125\114\124\111\123\111\x54\x45\x5f\120\122\x45\x4d\111\125\115";
            case self::MULTISITE_ENTERPRISE:
                return "\x4d\125\x4c\124\x49\x53\111\124\x45\x5f\105\x4e\124\x45\x52\120\x52\111\123\x45";
            case self::ALL_INCLUSIVE_SINGLE_SITE:
                return "\101\x4c\114\x5f\x49\x4e\x43\x4c\x55\123\x49\x56\x45\x5f\x53\x49\116\x47\x4c\x45\x5f\123\x49\x54\x45";
            case self::ENTERPRISE:
                return "\105\116\124\105\x52\x50\122\x49\x53\x45";
            case self::PREMIUM:
                return "\120\122\x45\115\111\x55\115";
            case self::STANDARD:
                return "\123\x54\101\x4e\104\x41\122\104";
            case self::FREE:
            default:
                return "\x46\x52\105\x45";
        }
        Tg:
        Jf:
    }
    public function mo_oauth_client_get_option($cW, $No = false)
    {
        $LQ = getenv(strtoupper($cW));
        if (!$LQ) {
            goto c2;
        }
        $LQ = EnvVarResolver::resolve_var($cW, $LQ);
        goto rQ;
        c2:
        $LQ = is_multisite() && $this->is_multisite ? get_site_option($cW, $No) : get_option($cW, $No);
        rQ:
        if (!(!$LQ || $No == $LQ)) {
            goto k2;
        }
        return $No;
        k2:
        return $LQ;
    }
    public function mo_oauth_client_update_option($cW, $LQ)
    {
        return is_multisite() && $this->is_multisite ? update_site_option($cW, $LQ) : update_option($cW, $LQ);
    }
    public function mo_oauth_client_delete_option($cW)
    {
        return is_multisite() && $this->is_multisite ? delete_site_option($cW) : delete_option($cW);
    }
    public function mo_oauth_client_get_main_domain_name()
    {
        if (is_multisite()) {
            goto eM;
        }
        $Vy = site_url();
        goto rx;
        eM:
        if (defined("\104\x4f\115\101\111\116\x5f\x43\125\122\x52\105\116\124\137\x53\x49\x54\x45")) {
            goto XN;
        }
        $pF = get_sites();
        $iY = get_blog_details("\x31");
        $Vy = $iY->domain;
        goto cx;
        XN:
        $Vy = DOMAIN_CURRENT_SITE;
        cx:
        rx:
        return $Vy;
    }
    public function array_overwrite($h8, $s5, $iq)
    {
        if ($iq) {
            goto BI;
        }
        array_push($h8, $s5);
        return array_unique($h8);
        BI:
        foreach ($s5 as $cW => $LQ) {
            $h8[$cW] = $LQ;
            Cm:
        }
        gD:
        return $h8;
    }
    public function gen_rand_str($qM = 10)
    {
        $pD = "\141\x62\x63\144\x65\x66\x67\150\151\152\x6b\x6c\155\156\x6f\160\x71\162\163\164\165\x76\x77\x78\x79\x7a\101\102\103\x44\105\x46\107\110\111\112\x4b\114\x4d\x4e\x4f\120\121\x52\123\124\x55\x56\x57\130\x59\132";
        $uX = strlen($pD);
        $iM = '';
        $YI = 0;
        Ro:
        if (!($YI < $qM)) {
            goto zQ;
        }
        $iM .= $pD[rand(0, $uX - 1)];
        Xa:
        $YI++;
        goto Ro;
        zQ:
        return $iM;
    }
    public function parse_url($Ws)
    {
        $QN = array();
        $uO = explode("\77", $Ws);
        $QN["\150\x6f\163\164"] = $uO[0];
        $QN["\161\x75\x65\x72\x79"] = isset($uO[1]) && '' !== $uO[1] ? $uO[1] : '';
        if (!(empty($QN["\161\165\x65\x72\171"]) || '' === $QN["\x71\x75\x65\x72\x79"])) {
            goto YK;
        }
        return $QN;
        YK:
        $oH = [];
        foreach (explode("\x26", $QN["\161\x75\x65\x72\x79"]) as $wa) {
            $uO = explode("\75", $wa);
            if (!(is_array($uO) && count($uO) === 2)) {
                goto Sj;
            }
            $oH[str_replace("\x61\x6d\x70\73", '', $uO[0])] = $uO[1];
            Sj:
            if (!(is_array($uO) && "\163\x74\x61\164\x65" === $uO[0])) {
                goto fQ;
            }
            $uO = explode("\163\x74\141\164\x65\75", $wa);
            $oH["\163\164\141\164\145"] = $uO[1];
            fQ:
            ML:
        }
        JZ:
        $QN["\x71\165\145\x72\x79"] = is_array($oH) && !empty($oH) ? $oH : [];
        return $QN;
    }
    public function generate_url($hB)
    {
        if (!(!is_array($hB) || empty($hB))) {
            goto nW;
        }
        return '';
        nW:
        if (isset($hB["\150\157\163\164"])) {
            goto Kv;
        }
        return '';
        Kv:
        $Ws = $hB["\150\x6f\163\164"];
        $YZ = '';
        $YI = 0;
        foreach ($hB["\x71\165\145\162\x79"] as $LY => $LQ) {
            if (!($YI !== 0)) {
                goto ip;
            }
            $YZ .= "\46";
            ip:
            $YZ .= "{$LY}\75{$LQ}";
            $YI += 1;
            At:
        }
        r1:
        return $Ws . "\x3f" . $YZ;
    }
    public function getnestedattribute($z_, $cW)
    {
        if (!($cW == '')) {
            goto kV;
        }
        return '';
        kV:
        if (!filter_var($cW, FILTER_VALIDATE_URL)) {
            goto DK;
        }
        if (isset($z_[$cW])) {
            goto XK;
        }
        return '';
        goto RX;
        XK:
        return $z_[$cW];
        RX:
        DK:
        $gv = explode("\x2e", $cW);
        if (count($gv) > 1) {
            goto x_;
        }
        if (isset($z_[$cW]) && is_array($z_[$cW])) {
            goto Gz;
        }
        $eo = $gv[0];
        if (isset($z_[$eo])) {
            goto GW;
        }
        return '';
        goto TX;
        GW:
        if (is_array($z_[$eo])) {
            goto k7;
        }
        return $z_[$eo];
        goto Rp;
        k7:
        return $z_[$eo][0];
        Rp:
        TX:
        goto q1;
        Gz:
        if (!(count($z_[$cW]) > 1)) {
            goto CM;
        }
        return $z_[$cW];
        CM:
        if (!isset($z_[$cW][0])) {
            goto W4;
        }
        return $z_[$cW][0];
        W4:
        if (!is_array($z_[$cW])) {
            goto s4;
        }
        return array_key_first($z_[$cW]);
        s4:
        q1:
        goto MK;
        x_:
        $eo = $gv[0];
        if (!isset($z_[$eo])) {
            goto Cz;
        }
        $Uc = array_count_values($gv);
        if (!($Uc[$eo] > 1)) {
            goto Ny;
        }
        $cW = substr_replace($cW, '', 0, strlen($eo));
        $cW = trim($cW, "\x2e");
        return $this->getnestedattribute($z_[$eo], $cW);
        Ny:
        return $this->getnestedattribute($z_[$eo], str_replace($eo . "\x2e", '', $cW));
        Cz:
        MK:
    }
    public function get_client_ip()
    {
        $ne = '';
        if (getenv("\110\x54\x54\x50\137\x43\114\x49\x45\x4e\124\137\x49\120")) {
            goto aj;
        }
        if (getenv("\x48\124\124\x50\x5f\130\137\x46\117\122\x57\x41\x52\x44\x45\104\x5f\x46\117\x52")) {
            goto JQ;
        }
        if (getenv("\110\x54\124\x50\137\x58\x5f\x46\117\122\x57\x41\x52\x44\x45\x44")) {
            goto QU;
        }
        if (getenv("\110\124\x54\x50\137\106\x4f\122\127\x41\122\104\105\104\137\106\x4f\x52")) {
            goto y6;
        }
        if (getenv("\110\124\x54\120\137\x46\117\122\127\x41\122\104\x45\x44")) {
            goto bK;
        }
        if (getenv("\x52\105\115\x4f\124\105\137\x41\x44\104\122")) {
            goto Dh;
        }
        $ne = "\x55\x4e\x4b\116\117\x57\x4e";
        goto Yh;
        aj:
        $ne = getenv("\x48\x54\124\x50\137\103\114\x49\105\116\124\137\x49\120");
        goto Yh;
        JQ:
        $ne = getenv("\x48\124\x54\120\137\x58\137\x46\x4f\122\127\101\x52\104\105\104\x5f\x46\117\122");
        goto Yh;
        QU:
        $ne = getenv("\110\124\124\x50\x5f\130\x5f\x46\x4f\122\127\x41\x52\104\x45\104");
        goto Yh;
        y6:
        $ne = getenv("\x48\x54\124\120\x5f\x46\x4f\122\127\101\x52\x44\x45\104\137\x46\x4f\x52");
        goto Yh;
        bK:
        $ne = getenv("\x48\x54\x54\x50\137\106\117\x52\127\x41\x52\x44\105\x44");
        goto Yh;
        Dh:
        $ne = getenv("\122\105\115\x4f\x54\105\137\x41\104\104\x52");
        Yh:
        return $ne;
    }
    public function get_current_url()
    {
        return (isset($_SERVER["\x48\x54\124\120\123"]) ? "\150\164\164\160\x73" : "\150\164\164\x70") . "\x3a\x2f\57{$_SERVER["\x48\124\x54\x50\137\110\x4f\123\x54"]}{$_SERVER["\x52\x45\121\x55\x45\123\124\x5f\x55\122\111"]}";
    }
    public function get_all_headers()
    {
        $k7 = [];
        foreach ($_SERVER as $un => $LQ) {
            if (!(substr($un, 0, 5) == "\110\124\124\x50\x5f")) {
                goto En;
            }
            $k7[str_replace("\40", "\55", ucwords(strtolower(str_replace("\137", "\40", substr($un, 5)))))] = $LQ;
            En:
            Yw:
        }
        Sv:
        $k7 = array_change_key_case($k7, CASE_UPPER);
        return $k7;
    }
    public function store_info($lk = '', $LQ = false)
    {
        if (!('' === $lk || !$LQ)) {
            goto RH;
        }
        return;
        RH:
        setcookie($lk, $LQ);
    }
    public function redirect_user($Ws = false, $cp = false)
    {
        if (!(false === $Ws)) {
            goto b1;
        }
        return;
        b1:
        if (!$cp) {
            goto aP;
        }
        echo "\x9\11\x9\x3c\163\x63\x72\x69\x70\164\x3e\xd\xa\11\x9\11\x9\x76\141\x72\x20\x6d\171\127\151\156\x64\157\167\40\75\40\167\151\156\x64\x6f\x77\56\x6f\x70\145\156\x28\x22";
        echo esc_url($Ws);
        echo "\x22\x2c\40\x22\124\x65\163\164\40\103\x6f\x6e\146\151\147\x75\162\x61\x74\x69\x6f\156\42\54\x20\x22\x77\151\x64\164\150\75\x36\60\x30\x2c\x20\150\x65\x69\x67\150\164\x3d\x36\60\60\x22\51\x3b\15\xa\x9\11\11\11\x77\x68\151\x6c\x65\50\x31\x29\40\x7b\15\12\x9\x9\11\x9\x9\151\146\x28\155\171\x57\x69\156\144\157\x77\56\x63\x6c\157\163\x65\144\50\x29\51\40\173\15\xa\11\x9\11\11\x9\x9\44\x28\x64\157\143\165\x6d\145\156\x74\51\x2e\x74\x72\151\x67\147\145\x72\x28\x22\x63\x6f\156\x66\151\x67\x5f\164\x65\163\x74\x65\144\x22\51\x3b\15\xa\11\x9\x9\11\x9\11\x62\162\x65\141\153\73\xd\xa\x9\11\11\11\x9\175\40\x65\154\163\x65\x20\x7b\x63\157\x6e\164\x69\156\165\x65\73\175\xd\12\11\11\x9\x9\x7d\xd\12\x9\11\11\x3c\x2f\x73\143\162\x69\160\164\76\15\12\x9\x9\11";
        aP:
        echo "\x9\11\74\x73\x63\x72\x69\x70\164\76\xd\12\11\11\x9\x77\x69\156\144\157\x77\x2e\x6c\x6f\143\141\164\151\x6f\x6e\56\162\x65\160\x6c\141\x63\145\x28\42";
        echo esc_url($Ws);
        echo "\x22\51\x3b\xd\12\11\11\x3c\57\x73\x63\162\151\x70\x74\76\xd\xa\11\x9";
        exit;
    }
    public function is_ajax_request()
    {
        return defined("\x44\117\111\116\x47\x5f\x41\112\101\130") && DOING_AJAX;
    }
    public function deactivate_plugin()
    {
        $this->mo_oauth_client_delete_option("\x68\157\163\164\137\x6e\x61\155\145");
        $this->mo_oauth_client_delete_option("\x6e\145\167\137\162\145\x67\151\x73\164\x72\x61\164\x69\x6f\x6e");
        $this->mo_oauth_client_delete_option("\x6d\157\137\157\141\165\x74\150\137\x61\144\155\x69\x6e\137\x70\x68\157\x6e\145");
        $this->mo_oauth_client_delete_option("\166\145\162\151\146\171\x5f\x63\x75\x73\164\157\155\145\x72");
        $this->mo_oauth_client_delete_option("\155\x6f\x5f\x6f\141\x75\164\x68\x5f\x61\144\x6d\x69\x6e\x5f\143\165\163\x74\157\155\x65\x72\x5f\x6b\145\x79");
        $this->mo_oauth_client_delete_option("\155\x6f\x5f\157\x61\165\164\x68\x5f\x61\x64\x6d\x69\156\x5f\141\x70\151\x5f\153\x65\x79");
        $this->mo_oauth_client_delete_option("\x6d\x6f\137\x6f\141\165\x74\x68\137\x6e\x65\167\x5f\x63\x75\x73\x74\x6f\x6d\x65\162");
        $this->mo_oauth_client_delete_option("\x63\165\163\164\157\x6d\145\x72\137\164\x6f\153\145\x6e");
        $this->mo_oauth_client_delete_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION);
        $this->mo_oauth_client_delete_option("\155\x6f\x5f\x6f\141\165\x74\x68\x5f\x72\145\x67\x69\x73\164\x72\141\164\x69\x6f\x6e\137\163\x74\141\x74\165\x73");
        $this->mo_oauth_client_delete_option("\x6d\157\x5f\x6f\x61\165\164\x68\x5f\x6e\x65\x77\x5f\x63\x75\x73\x74\157\155\x65\162");
        $this->mo_oauth_client_delete_option("\156\145\167\137\x72\145\147\151\x73\164\x72\x61\x74\151\157\156");
        $this->mo_oauth_client_delete_option("\x6d\x6f\x5f\x6f\x61\165\164\x68\x5f\x6c\x6f\147\151\x6e\x5f\151\143\x6f\x6e\x5f\x63\165\163\164\157\x6d\x5f\150\x65\x69\147\150\x74");
        $this->mo_oauth_client_delete_option("\x6d\x6f\x5f\157\141\165\x74\x68\137\154\157\x67\151\156\137\x69\x63\x6f\x6e\137\143\x75\163\x74\x6f\x6d\137\163\x69\172\x65");
        $this->mo_oauth_client_delete_option("\155\x6f\x5f\x6f\x61\x75\164\150\x5f\154\157\x67\x69\x6e\x5f\151\143\x6f\x6e\x5f\x63\x75\163\164\157\x6d\137\x63\x6f\x6c\157\x72");
        $this->mo_oauth_client_delete_option("\x6d\157\x5f\157\141\165\x74\x68\x5f\x6c\x6f\147\151\x6e\137\151\x63\x6f\156\x5f\x63\x75\163\164\157\x6d\x5f\142\x6f\x75\156\x64\x61\x72\171");
    }
    public function base64url_encode($uD)
    {
        return rtrim(strtr(base64_encode($uD), "\53\x2f", "\x2d\x5f"), "\x3d");
    }
    public function base64url_decode($uD)
    {
        return base64_decode(str_pad(strtr($uD, "\x2d\137", "\x2b\x2f"), strlen($uD) % 4, "\75", STR_PAD_RIGHT));
    }
    function export_plugin_config($h3 = false)
    {
        $uB = [];
        $Zo = [];
        $c4 = [];
        $uB = $this->get_plugin_config();
        $Zo = get_site_option("\x6d\x6f\137\157\x61\165\164\150\x5f\x61\160\160\163\x5f\154\x69\163\164");
        if (empty($uB)) {
            goto KL;
        }
        $uB = $uB->get_current_config();
        KL:
        if (!is_array($Zo)) {
            goto cw;
        }
        foreach ($Zo as $zl => $KY) {
            if (!is_array($KY)) {
                goto Vp;
            }
            $KY = new App($KY);
            Vp:
            $r9 = $KY->get_app_config('', false);
            if (!$h3) {
                goto EC;
            }
            unset($r9["\143\154\x69\145\x6e\x74\137\151\144"]);
            unset($r9["\143\154\x69\x65\x6e\x74\137\163\x65\143\162\x65\x74"]);
            EC:
            $c4[$zl] = $r9;
            Vu:
        }
        pq:
        cw:
        $e7 = ["\x70\154\x75\147\151\156\x5f\x63\157\156\146\151\x67" => $uB, "\x61\160\160\137\x63\x6f\x6e\x66\x69\147\x73" => $c4];
        $e7 = apply_filters("\x6d\157\137\164\x72\137\x67\145\164\x5f\x6c\151\x63\x65\156\x73\145\137\x63\x6f\156\146\151\147", $e7);
        return $e7;
    }
    private function verify_lk()
    {
        $ao = new \MoOauthClient\Standard\Customer();
        $g0 = $this->mo_oauth_client_get_option("\155\157\137\157\141\165\164\x68\x5f\x6c\x69\143\145\x6e\163\x65\137\x6b\145\171");
        if (!empty($g0)) {
            goto yG;
        }
        return 0;
        yG:
        $bR = $ao->XfskodsfhHJ($g0);
        $bR = json_decode($bR, true);
        return isset($bR["\163\164\141\164\x75\163"]) && "\123\x55\x43\x43\x45\x53\123" === $bR["\163\x74\141\x74\x75\x73"];
    }
    public function is_valid_jwt($gK = '')
    {
        $uO = explode("\x2e", $gK);
        if (!(count($uO) === 3)) {
            goto w2;
        }
        return true;
        w2:
        return false;
    }
    public function validate_appslist($mc)
    {
        if (is_array($mc)) {
            goto sx;
        }
        return false;
        sx:
        foreach ($mc as $cW => $F8) {
            if (!$F8 instanceof \MoOauthClient\App) {
                goto Lr;
            }
            goto Mv;
            Lr:
            return false;
            Mv:
        }
        Og:
        return true;
    }
    public function handle_error($N5)
    {
        do_action("\x6d\x6f\137\x74\x72\x5f\154\157\147\151\x6e\x5f\x65\162\162\157\x72\x73", $N5);
    }
    public function set_transient($cW, $LQ, $go)
    {
        return is_multisite() && $this->is_multisite ? set_site_transient($cW, $LQ, $go) : set_transient($cW, $LQ, $go);
    }
    public function get_transient($cW)
    {
        return is_multisite() && $this->is_multisite ? get_site_transient($cW) : get_transient($cW);
    }
    public function delete_transient($cW)
    {
        return is_multisite() && $this->is_multisite ? delete_site_transient($cW) : delete_transient($cW);
    }
}
