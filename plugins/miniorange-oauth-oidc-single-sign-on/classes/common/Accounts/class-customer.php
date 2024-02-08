<?php


namespace MoOauthClient;

class Customer
{
    public $email;
    public $phone;
    private $default_customer_key = "\x31\66\x35\65\65";
    private $default_api_key = "\146\106\144\x32\130\143\166\124\x47\104\x65\155\132\166\x62\x77\61\142\143\x55\145\163\116\x4a\127\x45\x71\113\x62\x62\125\161";
    private $host_name = '';
    private $host_key = '';
    public function __construct()
    {
        global $Yh;
        $this->host_name = $Yh->mo_oauth_client_get_option("\150\157\163\x74\137\x6e\141\155\145");
        $this->email = $Yh->mo_oauth_client_get_option("\155\157\137\157\141\x75\x74\x68\x5f\x61\x64\x6d\x69\x6e\137\145\x6d\141\x69\154");
        $this->phone = $Yh->mo_oauth_client_get_option("\155\157\137\x6f\x61\165\x74\150\137\141\x64\x6d\151\156\137\160\x68\157\156\145");
    }
    public function create_customer($hk)
    {
        global $Yh;
        $Ws = $this->host_name . "\57\x6d\x6f\x61\x73\57\x72\145\x73\164\57\143\165\x73\164\x6f\155\x65\x72\x2f\141\144\144";
        $tP = $Yh->mo_oauth_client_get_option("\155\x6f\137\157\141\x75\x74\150\x5f\x61\144\x6d\x69\x6e\137\146\156\x61\x6d\x65");
        $rs = $Yh->mo_oauth_client_get_option("\155\157\137\157\x61\165\164\150\137\x61\x64\x6d\x69\156\137\154\156\x61\x6d\x65");
        $sQ = $Yh->mo_oauth_client_get_option("\x6d\x6f\137\157\141\x75\164\150\x5f\x61\x64\155\x69\x6e\x5f\143\157\155\160\x61\156\x79");
        $Ld = array("\x63\x6f\155\160\141\x6e\x79\116\141\155\x65" => $sQ, "\141\x72\x65\141\x4f\146\111\156\164\145\x72\145\x73\164" => "\127\120\x20\x4f\x41\165\x74\x68\40\x43\x6c\151\145\156\164", "\x66\151\162\163\164\x6e\141\x6d\145" => $tP, "\x6c\141\163\164\156\x61\155\x65" => $rs, \MoOAuthConstants::EMAIL => $this->email, "\160\150\x6f\x6e\x65" => $this->phone, "\x70\141\163\x73\167\x6f\162\x64" => $hk);
        $kb = wp_json_encode($Ld);
        return $this->send_request([], false, $kb, [], false, $Ws);
    }
    public function get_customer_key($hk)
    {
        global $Yh;
        $Ws = $this->host_name . "\57\x6d\157\x61\163\57\162\145\163\x74\57\143\165\163\164\157\155\145\162\x2f\x6b\x65\x79";
        $Mv = $this->email;
        $Ld = array(\MoOAuthConstants::EMAIL => $Mv, "\160\x61\x73\163\x77\157\162\x64" => $hk);
        $kb = wp_json_encode($Ld);
        return $this->send_request([], false, $kb, [], false, $Ws);
    }
    public function add_oauth_application($un, $zl)
    {
        global $Yh;
        $Ws = $this->host_name . "\57\x6d\x6f\141\163\x2f\x72\x65\x73\164\x2f\141\x70\x70\154\x69\143\x61\164\x69\x6f\156\x2f\141\144\144\157\141\165\x74\150";
        $oe = $Yh->mo_oauth_client_get_option("\155\157\x5f\x6f\141\165\164\150\137\x61\144\155\x69\x6e\x5f\x63\x75\x73\164\x6f\x6d\145\162\137\x6b\x65\171");
        $rE = $Yh->mo_oauth_client_get_option("\x6d\x6f\x5f\157\141\x75\x74\x68\x5f" . $un . "\137\x73\x63\x6f\160\x65");
        $z6 = $Yh->mo_oauth_client_get_option("\x6d\157\137\x6f\141\x75\x74\150\137" . $un . "\x5f\143\x6c\x69\145\156\164\x5f\151\x64");
        $MC = $Yh->mo_oauth_client_get_option("\x6d\x6f\137\x6f\141\x75\164\x68\137" . $un . "\137\x63\154\151\x65\156\164\137\x73\x65\x63\162\x65\164");
        if (false !== $rE) {
            goto Mz;
        }
        $Ld = array("\x61\x70\160\154\151\x63\x61\164\151\157\x6e\x4e\x61\x6d\x65" => $zl, "\x63\x75\x73\x74\157\155\x65\x72\x49\144" => $oe, "\143\x6c\x69\145\156\164\111\x64" => $z6, "\x63\154\x69\145\x6e\164\123\x65\143\162\145\164" => $MC);
        goto R9;
        Mz:
        $Ld = array("\141\160\160\154\151\x63\x61\x74\151\157\156\x4e\x61\155\x65" => $zl, "\163\143\x6f\x70\x65" => $rE, "\143\x75\x73\x74\157\155\145\x72\111\x64" => $oe, "\x63\154\151\x65\156\164\x49\x64" => $z6, "\x63\154\x69\x65\x6e\x74\x53\145\143\162\145\164" => $MC);
        R9:
        $kb = wp_json_encode($Ld);
        return $this->send_request([], false, $kb, [], false, $Ws);
    }
    public function submit_contact_us($Mv, $ge, $B_, $S0 = true)
    {
        global $current_user;
        global $Yh;
        wp_get_current_user();
        $uB = $Yh->export_plugin_config(true);
        $e7 = json_encode($uB, JSON_UNESCAPED_SLASHES);
        $oe = $this->default_customer_key;
        $hC = $this->default_api_key;
        $zj = time();
        $Ws = $this->host_name . "\x2f\155\x6f\x61\163\x2f\x61\x70\151\57\x6e\x6f\x74\x69\146\x79\57\163\145\156\144";
        $bF = $oe . $zj . $hC;
        $lY = hash("\x73\150\141\65\61\62", $bF);
        $Ic = $Mv;
        $pW = \ucwords(\strtolower($Yh->get_versi_str())) . "\x20\55\x20" . \mo_oauth_get_version_number();
        $wO = "\x51\x75\145\x72\171\72\40\x57\157\x72\x64\120\162\145\163\163\x20\117\x41\x75\164\x68\x20" . $pW . "\40\x50\154\x75\x67\x69\156";
        $B_ = "\133\127\120\x20\x4f\x41\x75\164\x68\x20\x43\x6c\151\145\x6e\x74\x20" . $pW . "\x5d\40" . $B_;
        if (!$S0) {
            goto mj;
        }
        $B_ .= "\74\142\x72\76\x3c\x62\162\x3e\x43\x6f\156\146\151\147\40\x53\x74\x72\151\156\147\72\74\142\162\76\x3c\x70\162\x65\40\x73\164\x79\x6c\x65\x3d\42\142\x6f\162\x64\145\x72\x3a\61\x70\x78\40\x73\157\154\151\x64\x20\x23\64\64\x34\x3b\160\x61\x64\144\151\156\x67\x3a\x31\60\160\170\73\x22\76\x3c\143\x6f\144\x65\76" . $e7 . "\x3c\x2f\143\x6f\144\x65\76\74\x2f\x70\162\145\76";
        mj:
        $hc = isset($_SERVER["\x53\x45\x52\x56\x45\122\137\116\x41\115\x45"]) ? sanitize_text_field(wp_unslash($_SERVER["\x53\105\122\x56\x45\x52\x5f\x4e\x41\x4d\105"])) : '';
        $OY = "\74\144\151\166\40\76\x48\145\154\154\x6f\x2c\40\74\x62\x72\x3e\x3c\x62\x72\x3e\x46\151\x72\x73\164\40\116\x61\155\x65\x20\x3a" . $current_user->user_firstname . "\74\142\x72\x3e\74\142\x72\x3e\x4c\141\x73\164\x20\40\x4e\141\155\145\40\x3a" . $current_user->user_lastname . "\40\40\x20\x3c\142\x72\x3e\74\142\x72\76\x43\157\x6d\160\x61\x6e\171\40\72\x3c\141\40\x68\162\145\x66\75\x22" . $hc . "\x22\40\164\x61\162\x67\145\x74\x3d\42\x5f\142\154\141\156\x6b\42\x20\x3e" . $hc . "\x3c\x2f\141\x3e\x3c\142\162\76\x3c\142\x72\x3e\120\150\x6f\x6e\145\40\116\165\x6d\142\145\162\40\72" . $ge . "\74\142\162\76\74\x62\162\x3e\x45\155\x61\151\154\x20\72\74\x61\x20\x68\x72\x65\146\75\42\x6d\x61\151\154\x74\157\x3a" . $Ic . "\42\40\164\141\x72\147\145\x74\x3d\42\137\x62\x6c\141\156\153\x22\x3e" . $Ic . "\x3c\x2f\141\76\x3c\142\x72\76\74\x62\x72\x3e\121\165\145\162\x79\40\x3a" . $B_ . "\74\x2f\x64\151\166\x3e";
        $Ld = array("\x63\165\x73\x74\x6f\x6d\x65\162\113\x65\171" => $oe, "\x73\145\x6e\144\105\x6d\x61\151\x6c" => true, \MoOAuthConstants::EMAIL => array("\x63\165\163\x74\x6f\155\x65\x72\x4b\x65\171" => $oe, "\146\x72\157\x6d\x45\x6d\141\x69\x6c" => $Ic, "\x62\x63\143\105\x6d\x61\x69\154" => "\x69\x6e\x66\157\x40\170\145\143\165\162\x69\146\171\x2e\x63\x6f\x6d", "\146\162\157\x6d\x4e\x61\155\145" => "\x6d\x69\156\x69\x4f\162\x61\156\147\x65", "\x74\x6f\105\x6d\x61\x69\x6c" => "\157\x61\165\x74\x68\x73\x75\x70\x70\157\x72\164\100\170\145\x63\x75\162\151\x66\171\56\x63\157\x6d", "\x74\x6f\116\x61\155\145" => "\x6f\141\165\x74\x68\163\x75\x70\x70\157\x72\164\100\x78\x65\143\x75\162\x69\146\171\x2e\143\x6f\x6d", "\163\x75\142\152\x65\x63\164" => $wO, "\x63\x6f\x6e\164\145\156\x74" => $OY));
        $kb = json_encode($Ld, JSON_UNESCAPED_SLASHES);
        $k7 = array("\x43\x6f\x6e\x74\x65\156\164\x2d\x54\171\x70\x65" => "\141\x70\160\154\151\x63\x61\164\151\157\156\57\152\x73\157\156");
        $k7["\103\x75\x73\x74\157\155\x65\x72\55\x4b\145\x79"] = $oe;
        $k7["\124\x69\155\x65\163\164\x61\155\x70"] = $zj;
        $k7["\x41\x75\x74\x68\x6f\x72\x69\172\141\x74\x69\x6f\156"] = $lY;
        return $this->send_request($k7, true, $kb, [], false, $Ws);
    }
    public function submit_contact_us_upgrade($Mv, $FL, $uP, $H7)
    {
        global $Yh;
        $oe = $this->default_customer_key;
        $hC = $this->default_api_key;
        $zj = time();
        $Ws = $this->host_name . "\x2f\x6d\x6f\141\x73\x2f\x61\160\x69\57\x6e\x6f\164\x69\146\x79\57\x73\x65\x6e\144";
        $bF = $oe . $zj . $hC;
        $lY = hash("\163\150\141\65\x31\x32", $bF);
        $Ic = $Mv;
        $pW = \ucwords(\strtolower($Yh->get_versi_str())) . "\x20\x2d\40" . \mo_oauth_get_version_number();
        $wO = "\x51\x75\x65\162\171\x3a\40\x57\157\162\144\120\x72\x65\163\x73\x20\x4f\101\x75\x74\x68\40\125\x70\x67\162\141\x64\x65\x20\120\x6c\165\x67\151\156";
        $hc = isset($_SERVER["\123\105\122\126\105\122\137\x4e\x41\x4d\105"]) ? sanitize_text_field(wp_unslash($_SERVER["\x53\105\122\x56\x45\x52\x5f\116\x41\x4d\x45"])) : '';
        $OY = "\74\144\x69\x76\40\76\x48\x65\x6c\x6c\157\54\x20\x20\40\x3c\142\162\x3e\74\x62\x72\76\103\x6f\155\x70\141\x6e\x79\x20\x3a\74\141\40\150\162\x65\146\75\42" . $hc . "\x22\x20\164\141\x72\147\x65\x74\75\x22\x5f\x62\154\141\156\153\42\x20\76" . $hc . "\x3c\57\141\76\x3c\x62\162\x3e\74\142\162\76\x43\x75\162\x72\145\x6e\164\40\126\x65\x72\x73\151\x6f\x6e\40\72" . $FL . "\x3c\x62\162\x3e\x3c\x62\x72\76\105\155\141\151\x6c\40\x3a\74\141\x20\150\162\x65\146\75\42\x6d\141\151\154\x74\157\x3a" . $Ic . "\42\x20\164\141\162\147\145\x74\x3d\42\x5f\x62\154\141\x6e\153\42\76" . $Ic . "\x3c\x2f\x61\x3e\74\x62\162\76\74\x62\162\76\x56\x65\x72\x73\151\157\x6e\x20\x74\157\x20\x55\x70\x67\x72\141\144\x65\40\72" . $uP . "\74\142\162\76\x3c\x62\x72\76\106\x65\x61\x74\165\162\145\163\x20\x52\x65\161\x75\151\x72\x65\x64\40\x3a" . $H7 . "\74\57\144\x69\166\x3e";
        $Ld = array("\143\165\x73\x74\157\155\x65\162\113\x65\171" => $oe, "\163\x65\x6e\144\x45\x6d\141\x69\x6c" => true, \MoOAuthConstants::EMAIL => array("\x63\x75\x73\x74\157\155\145\x72\x4b\x65\x79" => $oe, "\x66\x72\x6f\x6d\105\x6d\x61\x69\x6c" => $Ic, "\x62\x63\x63\x45\x6d\x61\151\x6c" => "\x69\x6e\x66\x6f\x40\170\145\143\165\162\x69\x66\x79\56\x63\x6f\x6d", "\146\162\x6f\155\x4e\x61\x6d\145" => "\x6d\151\x6e\151\x4f\x72\141\156\x67\145", "\x74\x6f\105\155\x61\151\154" => "\157\141\x75\164\x68\x73\165\x70\160\157\x72\164\x40\170\145\143\x75\x72\x69\x66\171\x2e\143\157\155", "\x74\x6f\116\141\155\145" => "\x6f\x61\165\164\150\x73\x75\160\160\157\x72\164\100\x78\145\143\x75\x72\x69\146\171\x2e\x63\157\x6d", "\x73\x75\142\152\145\x63\164" => $wO, "\143\x6f\x6e\x74\145\x6e\x74" => $OY));
        $kb = json_encode($Ld, JSON_UNESCAPED_SLASHES);
        $k7 = array("\x43\157\x6e\x74\145\x6e\x74\x2d\x54\171\160\145" => "\x61\160\160\154\x69\x63\141\x74\x69\157\x6e\57\x6a\163\x6f\156");
        $k7["\103\165\163\164\157\x6d\x65\162\55\x4b\145\x79"] = $oe;
        $k7["\124\x69\x6d\x65\x73\164\x61\155\160"] = $zj;
        $k7["\x41\165\x74\150\x6f\162\151\172\x61\x74\151\157\x6e"] = $lY;
        return $this->send_request($k7, true, $kb, [], false, $Ws);
    }
    public function send_otp_token($Mv = '', $ge = '', $fl = true, $WG = false)
    {
        global $Yh;
        $Ws = $this->host_name . "\x2f\x6d\157\141\x73\57\x61\x70\151\x2f\141\165\x74\x68\57\x63\150\x61\154\154\145\156\x67\145";
        $oe = $this->default_customer_key;
        $hC = $this->default_api_key;
        $WZ = $this->email;
        $ge = $Yh->mo_oauth_client_get_option("\155\x6f\x5f\x6f\x61\165\164\150\137\x61\144\x6d\x69\x6e\x5f\160\x68\x6f\156\x65");
        $zj = self::get_timestamp();
        $bF = $oe . $zj . $hC;
        $lY = hash("\163\150\141\65\61\62", $bF);
        $qP = "\103\x75\163\164\x6f\x6d\145\162\55\x4b\x65\x79\x3a\40" . $oe;
        $EY = "\x54\151\155\x65\x73\x74\x61\155\160\72\40" . $zj;
        $av = "\x41\x75\x74\x68\157\162\x69\x7a\141\x74\x69\157\x6e\x3a\40" . $lY;
        if ($fl) {
            goto n3;
        }
        $Ld = array("\143\x75\163\164\x6f\155\145\162\x4b\x65\171" => $oe, "\x70\x68\x6f\156\145" => $ge, "\141\x75\164\x68\x54\x79\160\145" => "\123\x4d\123");
        goto CZ;
        n3:
        $Ld = array("\143\165\x73\x74\x6f\155\x65\162\x4b\145\171" => $oe, \MoOAuthConstants::EMAIL => $WZ, "\x61\x75\164\x68\x54\x79\160\x65" => "\105\115\x41\x49\x4c");
        CZ:
        $kb = wp_json_encode($Ld);
        $k7 = array("\x43\157\x6e\x74\145\x6e\x74\x2d\x54\x79\160\145" => "\141\160\x70\154\x69\143\141\164\151\157\x6e\x2f\152\x73\157\156");
        $k7["\x43\165\163\164\157\x6d\x65\162\55\113\x65\171"] = $oe;
        $k7["\x54\x69\x6d\145\163\164\x61\155\x70"] = $zj;
        $k7["\101\165\x74\150\x6f\x72\151\172\x61\x74\x69\x6f\156"] = $lY;
        return $this->send_request($k7, true, $kb, [], false, $Ws);
    }
    public function get_timestamp()
    {
        global $Yh;
        $Ws = $this->host_name . "\x2f\155\x6f\141\163\57\162\x65\163\x74\57\155\x6f\142\x69\x6c\145\x2f\x67\145\x74\x2d\x74\x69\x6d\145\163\x74\x61\155\160";
        return $this->send_request([], false, '', [], false, $Ws);
    }
    public function validate_otp_token($UN, $YU)
    {
        global $Yh;
        $Ws = $this->host_name . "\x2f\155\157\141\x73\57\141\160\151\x2f\141\x75\164\150\x2f\166\x61\x6c\x69\144\141\164\145";
        $oe = $this->default_customer_key;
        $hC = $this->default_api_key;
        $WZ = $this->email;
        $zj = self::get_timestamp();
        $bF = $oe . $zj . $hC;
        $lY = hash("\x73\150\141\65\61\62", $bF);
        $qP = "\103\x75\163\x74\x6f\155\x65\x72\55\113\x65\171\72\40" . $oe;
        $EY = "\124\x69\x6d\145\x73\x74\x61\155\160\x3a\40" . $zj;
        $av = "\101\x75\x74\150\157\162\x69\x7a\141\164\151\x6f\x6e\x3a\x20" . $lY;
        $kb = '';
        $Ld = array("\x74\170\111\144" => $UN, "\164\157\153\145\156" => $YU);
        $kb = wp_json_encode($Ld);
        $k7 = array("\103\157\156\x74\x65\156\x74\55\x54\x79\x70\145" => "\141\160\x70\x6c\151\143\141\x74\x69\157\x6e\57\x6a\x73\157\156");
        $k7["\x43\165\x73\164\157\x6d\x65\162\x2d\x4b\145\171"] = $oe;
        $k7["\124\151\x6d\145\x73\x74\x61\155\x70"] = $zj;
        $k7["\101\x75\164\x68\x6f\x72\151\x7a\x61\164\151\x6f\156"] = $lY;
        return $this->send_request($k7, true, $kb, [], false, $Ws);
    }
    public function check_customer()
    {
        global $Yh;
        $Ws = $this->host_name . "\x2f\x6d\x6f\x61\x73\57\x72\x65\x73\164\x2f\143\165\163\x74\157\155\145\x72\x2f\x63\150\x65\x63\153\x2d\x69\x66\x2d\145\170\151\163\164\x73";
        $Mv = $this->email;
        $Ld = array(\MoOAuthConstants::EMAIL => $Mv);
        $kb = wp_json_encode($Ld);
        return $this->send_request([], false, $kb, [], false, $Ws);
    }
    public function mo_oauth_send_email_alert($Mv, $ge, $ri)
    {
        global $Yh;
        if ($this->check_internet_connection()) {
            goto UP;
        }
        return;
        UP:
        $Ws = $this->host_name . "\57\x6d\x6f\x61\163\x2f\141\x70\151\x2f\156\157\x74\x69\146\171\57\163\145\x6e\144";
        global $user;
        $oe = $this->default_customer_key;
        $hC = $this->default_api_key;
        $zj = self::get_timestamp();
        $bF = $oe . $zj . $hC;
        $lY = hash("\x73\x68\141\x35\61\x32", $bF);
        $Ic = $Mv;
        $wO = "\106\x65\145\x64\142\141\143\x6b\72\40\x57\x6f\x72\144\x50\x72\x65\163\x73\40\x4f\101\x75\164\x68\x20\x43\154\x69\145\156\164\40\x50\154\165\147\x69\156";
        $kI = site_url();
        $user = wp_get_current_user();
        $pW = \ucwords(\strtolower($Yh->get_versi_str())) . "\40\x2d\x20" . \mo_oauth_get_version_number();
        $B_ = "\x5b\127\x50\x20\x4f\x41\165\164\150\40\62\x2e\x30\x20\x43\x6c\x69\145\x6e\164\40" . $pW . "\135\x20\72\40" . $ri;
        $hc = isset($_SERVER["\x53\105\x52\126\x45\x52\x5f\116\x41\x4d\x45"]) ? sanitize_text_field(wp_unslash($_SERVER["\x53\105\122\126\x45\x52\x5f\116\101\115\105"])) : '';
        $OY = "\74\144\x69\166\x20\x3e\110\145\x6c\154\157\54\40\74\x62\162\x3e\74\142\x72\x3e\x46\x69\x72\163\164\x20\x4e\x61\x6d\145\x20\72" . $user->user_firstname . "\74\142\162\x3e\x3c\x62\162\76\114\x61\163\x74\x20\x20\x4e\141\x6d\x65\x20\72" . $user->user_lastname . "\40\40\x20\x3c\x62\162\76\x3c\142\162\x3e\x43\157\x6d\x70\141\x6e\171\40\x3a\74\x61\x20\x68\x72\145\146\75\42" . $hc . "\x22\40\x74\141\x72\147\145\164\75\x22\x5f\x62\x6c\x61\156\153\42\x20\76" . $hc . "\74\x2f\x61\x3e\x3c\x62\x72\76\x3c\x62\162\x3e\x50\x68\x6f\156\145\x20\116\x75\x6d\x62\x65\162\x20\72" . $ge . "\x3c\x62\162\76\74\142\x72\76\105\x6d\x61\151\154\x20\72\74\141\x20\x68\x72\x65\x66\75\42\x6d\141\151\x6c\164\x6f\72" . $Ic . "\42\40\x74\141\x72\147\x65\164\x3d\42\x5f\x62\154\x61\156\x6b\42\76" . $Ic . "\x3c\57\141\x3e\74\x62\162\76\74\x62\x72\76\121\165\x65\x72\171\x20\x3a" . $B_ . "\74\57\x64\x69\x76\76";
        $Ld = array("\x63\x75\163\x74\157\155\145\x72\113\x65\x79" => $oe, "\163\x65\x6e\x64\x45\155\141\x69\154" => true, \MoOAuthConstants::EMAIL => array("\143\x75\163\164\157\155\x65\x72\113\x65\171" => $oe, "\146\x72\x6f\155\x45\155\x61\x69\154" => $Ic, "\x62\143\143\105\155\141\x69\x6c" => "\157\141\165\164\x68\x73\x75\160\x70\x6f\x72\x74\100\155\151\x6e\151\x6f\162\141\156\x67\145\x2e\x63\157\155", "\146\x72\157\x6d\x4e\141\155\x65" => "\x6d\x69\x6e\151\117\162\x61\156\x67\x65", "\164\x6f\105\x6d\x61\151\154" => "\157\141\165\x74\x68\163\x75\x70\x70\x6f\162\x74\100\155\x69\156\x69\x6f\162\x61\x6e\x67\145\56\x63\x6f\155", "\164\x6f\116\141\155\x65" => "\x6f\141\165\x74\150\x73\x75\x70\160\157\162\x74\x40\155\151\156\x69\x6f\162\141\x6e\147\x65\56\x63\157\x6d", "\x73\165\142\152\145\x63\164" => $wO, "\x63\157\x6e\164\145\x6e\164" => $OY));
        $kb = wp_json_encode($Ld);
        $k7 = array("\x43\157\x6e\x74\x65\x6e\x74\55\124\171\x70\145" => "\141\160\160\154\x69\x63\141\x74\151\x6f\156\x2f\152\x73\157\156");
        $k7["\103\165\x73\164\157\x6d\x65\162\55\113\x65\x79"] = $oe;
        $k7["\x54\x69\x6d\145\163\164\x61\x6d\160"] = $zj;
        $k7["\101\165\x74\x68\x6f\162\x69\172\x61\164\x69\x6f\156"] = $lY;
        return $this->send_request($k7, true, $kb, [], false, $Ws);
    }
    public function mo_oauth_send_demo_alert($Mv, $hK, $ri, $wO)
    {
        if ($this->check_internet_connection()) {
            goto bZ;
        }
        return;
        bZ:
        $Ws = $this->host_name . "\x2f\x6d\157\141\163\57\x61\160\151\x2f\156\157\164\x69\146\171\x2f\163\145\x6e\144";
        $oe = $this->default_customer_key;
        $hC = $this->default_api_key;
        $zj = self::get_timestamp();
        $bF = $oe . $zj . $hC;
        $lY = hash("\163\150\x61\x35\x31\x32", $bF);
        $Ic = $Mv;
        global $user;
        $user = wp_get_current_user();
        $OY = "\x3c\x64\151\x76\40\x3e\x48\x65\x6c\154\x6f\54\x20\x3c\57\x61\x3e\x3c\142\162\x3e\x3c\x62\x72\x3e\x45\x6d\141\x69\154\40\x3a\x3c\x61\40\150\x72\145\x66\75\42\155\x61\x69\154\x74\157\72" . $Ic . "\42\40\164\x61\x72\x67\x65\x74\x3d\42\x5f\142\x6c\x61\156\x6b\x22\76" . $Ic . "\74\x2f\141\76\x3c\x62\x72\x3e\x3c\142\162\76\122\145\x71\165\x65\x73\x74\145\144\40\x44\x65\155\157\x20\x66\157\162\40\x20\x20\x20\40\72\40" . $hK . "\74\x62\x72\76\74\142\x72\x3e\x52\145\x71\165\151\162\145\155\x65\156\164\163\40\x20\40\40\40\x20\40\x20\x20\x20\40\x3a\40" . $ri . "\x3c\x2f\x64\151\166\76";
        $Ld = array("\143\x75\x73\x74\x6f\155\x65\162\x4b\x65\x79" => $oe, "\x73\x65\x6e\144\x45\155\141\x69\154" => true, \MoOAuthConstants::EMAIL => array("\143\165\163\164\x6f\155\x65\162\x4b\145\171" => $oe, "\146\x72\157\x6d\105\x6d\x61\x69\154" => $Ic, "\142\143\x63\105\x6d\x61\151\x6c" => "\157\x61\165\x74\x68\163\x75\x70\160\157\162\x74\x40\x6d\x69\x6e\x69\157\x72\x61\x6e\x67\145\56\x63\157\x6d", "\x66\x72\157\155\x4e\x61\155\x65" => "\155\151\x6e\x69\117\x72\141\156\x67\145", "\164\157\105\x6d\x61\x69\154" => "\x6f\141\x75\x74\x68\x73\x75\160\160\157\162\x74\x40\155\151\x6e\x69\x6f\x72\141\156\147\145\56\x63\157\x6d", "\164\157\x4e\141\155\x65" => "\157\141\x75\164\x68\x73\165\160\x70\157\162\164\x40\155\x69\x6e\x69\x6f\x72\141\x6e\147\x65\56\143\157\x6d", "\x73\x75\142\x6a\145\x63\x74" => $wO, "\x63\157\x6e\x74\145\156\x74" => $OY));
        $kb = json_encode($Ld);
        $k7 = array("\103\157\x6e\x74\145\156\x74\55\124\x79\x70\145" => "\141\160\x70\x6c\151\x63\x61\164\x69\x6f\156\x2f\x6a\x73\x6f\156");
        $k7["\x43\165\163\164\x6f\155\145\x72\x2d\113\145\171"] = $oe;
        $k7["\x54\151\x6d\145\x73\164\x61\155\x70"] = $zj;
        $k7["\x41\x75\x74\150\x6f\162\x69\172\x61\x74\x69\x6f\156"] = $lY;
        $uh = $this->send_request($k7, true, $kb, [], false, $Ws);
    }
    public function mo_oauth_forgot_password($Mv)
    {
        global $Yh;
        $Ws = $this->host_name . "\57\x6d\x6f\x61\x73\x2f\x72\145\x73\x74\x2f\143\165\163\164\157\x6d\145\162\57\x70\141\163\163\167\157\162\x64\55\x72\145\163\145\164";
        $oe = $Yh->mo_oauth_client_get_option("\x6d\x6f\137\157\x61\165\164\150\137\x61\x64\x6d\x69\156\137\143\x75\163\x74\x6f\155\145\x72\x5f\153\x65\171");
        $hC = $Yh->mo_oauth_client_get_option("\x6d\157\x5f\157\x61\x75\164\x68\137\141\x64\x6d\151\156\x5f\x61\160\151\137\153\145\171");
        $zj = self::get_timestamp();
        $bF = $oe . $zj . $hC;
        $lY = hash("\x73\150\141\65\61\62", $bF);
        $qP = "\103\x75\x73\x74\157\155\x65\162\55\113\145\171\72\x20" . $oe;
        $EY = "\x54\x69\155\x65\x73\164\141\155\160\x3a\x20" . number_format($zj, 0, '', '');
        $av = "\101\x75\x74\x68\157\162\151\x7a\141\164\151\x6f\x6e\x3a\40" . $lY;
        $kb = '';
        $Ld = array(\MoOAuthConstants::EMAIL => $Mv);
        $kb = wp_json_encode($Ld);
        $k7 = array("\103\157\156\x74\145\x6e\x74\55\124\x79\x70\x65" => "\x61\160\x70\x6c\151\143\x61\x74\151\157\156\57\x6a\163\157\156");
        $k7["\103\165\x73\x74\x6f\155\x65\162\55\x4b\145\171"] = $oe;
        $k7["\x54\151\155\x65\163\x74\x61\155\x70"] = $zj;
        $k7["\x41\165\x74\x68\x6f\162\x69\172\x61\x74\151\x6f\156"] = $lY;
        return $this->send_request($k7, true, $kb, [], false, $Ws);
    }
    public function check_internet_connection()
    {
        return (bool) @fsockopen("\x6c\x6f\147\x69\156\x2e\170\x65\143\x75\x72\151\146\x79\x2e\x63\x6f\x6d", 443, $i7, $eS, 5);
    }
    private function send_request($sB = false, $RW = false, $kb = '', $Zs = false, $aA = false, $Ws = '')
    {
        $k7 = array("\x43\157\x6e\164\x65\x6e\164\55\x54\171\x70\145" => "\141\x70\x70\x6c\x69\x63\141\164\x69\x6f\x6e\x2f\x6a\x73\157\156", "\143\150\x61\162\163\x65\x74" => "\125\124\x46\x20\x2d\x20\70", "\x41\x75\x74\x68\x6f\162\151\172\x61\x74\x69\x6f\156" => "\102\x61\x73\151\143");
        $k7 = $RW && $sB ? $sB : array_unique(array_merge($k7, $sB));
        $uo = array("\155\x65\x74\150\157\x64" => "\120\x4f\123\124", "\x62\157\x64\x79" => $kb, "\164\151\155\145\x6f\x75\x74" => "\61\x35", "\x72\145\144\151\162\145\143\164\x69\157\156" => "\x35", "\x68\x74\x74\x70\x76\x65\162\163\x69\x6f\156" => "\61\56\x30", "\x62\154\x6f\x63\153\151\156\147" => true, "\x68\x65\141\144\145\162\163" => $k7, "\163\x73\154\x76\x65\x72\151\x66\x79" => true);
        $uo = $aA ? $Zs : array_unique(array_merge($uo, $Zs), SORT_REGULAR);
        $uh = wp_remote_post($Ws, $uo);
        if (!is_wp_error($uh)) {
            goto P5;
        }
        $Go = $uh->get_error_message();
        echo wp_kses("\x53\157\155\145\x74\x68\x69\x6e\x67\40\167\x65\156\x74\x20\x77\162\x6f\x6e\x67\72\x20{$Go}", \mo_oauth_get_valid_html());
        exit;
        P5:
        return wp_remote_retrieve_body($uh);
    }
}
