<?php


namespace MoOauthClient;

class Customer
{
    public $email;
    public $phone;
    private $default_customer_key = "\61\x36\65\x35\x35";
    private $default_api_key = "\146\106\x64\x32\130\x63\166\124\x47\x44\x65\x6d\132\x76\x62\167\x31\x62\x63\x55\x65\163\116\112\127\x45\161\113\x62\x62\x55\x71";
    private $host_name = '';
    private $host_key = '';
    public function __construct()
    {
        global $Uj;
        $this->host_name = $Uj->mo_oauth_client_get_option("\150\x6f\163\164\x5f\156\x61\155\145");
        $this->email = $Uj->mo_oauth_client_get_option("\155\157\137\x6f\x61\165\164\150\x5f\x61\144\155\151\x6e\137\145\x6d\x61\x69\154");
        $this->phone = $Uj->mo_oauth_client_get_option("\155\157\x5f\x6f\x61\165\x74\150\137\x61\144\x6d\x69\156\137\x70\150\x6f\x6e\x65");
        $this->host_key = $Uj->mo_oauth_client_get_option("\x70\x61\163\x73\x77\x6f\x72\x64");
    }
    public function create_customer()
    {
        global $Uj;
        $ht = $this->host_name . "\x2f\155\157\141\163\x2f\x72\145\x73\164\57\143\165\x73\x74\x6f\x6d\145\x72\57\x61\144\x64";
        $Jj = $this->host_key;
        $HX = $Uj->mo_oauth_client_get_option("\155\157\137\x6f\141\165\164\x68\x5f\141\144\x6d\x69\156\x5f\146\x6e\141\x6d\145");
        $nb = $Uj->mo_oauth_client_get_option("\x6d\157\137\157\x61\x75\x74\150\137\x61\144\155\x69\156\x5f\x6c\x6e\x61\x6d\x65");
        $Hx = $Uj->mo_oauth_client_get_option("\x6d\157\137\x6f\141\x75\164\x68\137\141\144\155\151\156\x5f\x63\x6f\155\160\x61\x6e\171");
        $vX = array("\143\157\x6d\x70\141\x6e\171\x4e\141\155\145" => $Hx, "\141\x72\145\141\x4f\146\x49\x6e\164\x65\162\x65\x73\164" => "\x57\120\40\117\x41\165\x74\x68\x20\103\x6c\x69\145\156\x74", "\146\x69\162\x73\164\156\x61\x6d\x65" => $HX, "\154\x61\163\x74\x6e\x61\x6d\145" => $nb, \MoOAuthConstants::EMAIL => $this->email, "\160\x68\157\156\x65" => $this->phone, "\x70\141\x73\163\167\157\162\x64" => $Jj);
        $n6 = wp_json_encode($vX);
        return $this->send_request([], false, $n6, [], false, $ht);
    }
    public function get_customer_key()
    {
        global $Uj;
        $ht = $this->host_name . "\57\155\x6f\141\x73\x2f\x72\x65\163\164\57\x63\165\x73\164\157\x6d\x65\162\x2f\153\x65\171";
        $g3 = $this->email;
        $Jj = $this->host_key;
        $vX = array(\MoOAuthConstants::EMAIL => $g3, "\x70\x61\163\163\167\157\162\144" => $Jj);
        $n6 = wp_json_encode($vX);
        return $this->send_request([], false, $n6, [], false, $ht);
    }
    public function add_oauth_application($uQ, $gR)
    {
        global $Uj;
        $ht = $this->host_name . "\x2f\155\x6f\141\x73\57\x72\145\163\164\57\x61\x70\x70\154\151\x63\141\x74\151\x6f\x6e\57\x61\144\x64\x6f\x61\165\164\x68";
        $i_ = $Uj->mo_oauth_client_get_option("\x6d\157\x5f\x6f\141\165\x74\x68\137\x61\144\x6d\151\x6e\x5f\143\x75\163\164\x6f\155\145\x72\x5f\x6b\x65\x79");
        $vn = $Uj->mo_oauth_client_get_option("\x6d\157\137\x6f\x61\x75\x74\x68\137" . $uQ . "\137\x73\x63\x6f\160\145");
        $w0 = $Uj->mo_oauth_client_get_option("\155\157\137\157\141\x75\x74\150\x5f" . $uQ . "\x5f\143\154\151\145\x6e\164\137\151\144");
        $zr = $Uj->mo_oauth_client_get_option("\x6d\157\x5f\x6f\141\165\164\x68\x5f" . $uQ . "\137\143\x6c\151\x65\156\164\137\x73\145\x63\162\x65\x74");
        if (false !== $vn) {
            goto et;
        }
        $vX = array("\141\x70\160\x6c\151\x63\141\164\x69\157\156\x4e\x61\x6d\x65" => $gR, "\143\x75\x73\x74\x6f\x6d\145\162\111\x64" => $i_, "\x63\154\151\145\x6e\164\111\144" => $w0, "\143\154\151\145\156\x74\x53\145\143\162\x65\x74" => $zr);
        goto eX;
        et:
        $vX = array("\141\x70\160\x6c\x69\x63\x61\164\x69\157\156\x4e\x61\x6d\x65" => $gR, "\163\143\157\160\145" => $vn, "\143\x75\x73\x74\x6f\155\x65\162\111\144" => $i_, "\143\154\151\x65\156\x74\x49\144" => $w0, "\x63\154\x69\145\x6e\164\123\145\x63\x72\145\x74" => $zr);
        eX:
        $n6 = wp_json_encode($vX);
        return $this->send_request([], false, $n6, [], false, $ht);
    }
    public function submit_contact_us($g3, $fm, $GQ, $Pa = true)
    {
        global $current_user;
        global $Uj;
        wp_get_current_user();
        $uu = $Uj->export_plugin_config(true);
        $fX = json_encode($uu, JSON_UNESCAPED_SLASHES);
        $i_ = $this->default_customer_key;
        $aG = $this->default_api_key;
        $a7 = time();
        $ht = $this->host_name . "\x2f\155\157\x61\163\x2f\x61\x70\151\x2f\x6e\x6f\x74\151\146\171\57\x73\x65\x6e\144";
        $NS = $i_ . $a7 . $aG;
        $jm = hash("\x73\x68\x61\x35\61\62", $NS);
        $g0 = $g3;
        $YD = \ucwords(\strtolower($Uj->get_versi_str())) . "\40\55\x20" . \mo_oauth_get_version_number();
        $Gn = "\x51\165\x65\162\x79\72\40\127\157\162\144\120\x72\145\163\x73\x20\x4f\101\x75\x74\150\40" . $YD . "\x20\120\x6c\x75\147\151\156";
        $GQ = "\x5b\127\120\x20\x4f\101\165\x74\150\x20\x43\154\x69\x65\x6e\x74\x20" . $YD . "\135\x20" . $GQ;
        if (!$Pa) {
            goto ZF;
        }
        $GQ .= "\x3c\142\162\x3e\x3c\x62\162\x3e\x43\157\x6e\x66\x69\147\40\123\164\x72\x69\156\147\x3a\x3c\x62\162\x3e\x3c\160\x72\x65\x20\163\x74\171\154\x65\x3d\x22\x62\x6f\x72\144\145\x72\72\61\160\170\40\x73\157\x6c\x69\144\40\43\x34\64\x34\x3b\160\x61\x64\x64\151\156\x67\x3a\61\x30\160\170\73\x22\x3e\x3c\x63\157\144\145\76" . $fX . "\74\x2f\x63\157\x64\145\76\74\57\x70\x72\145\x3e";
        ZF:
        $ep = isset($_SERVER["\123\105\122\126\105\122\137\116\x41\115\x45"]) ? sanitize_text_field(wp_unslash($_SERVER["\x53\105\122\126\105\122\x5f\116\101\x4d\105"])) : '';
        $dV = "\74\144\x69\166\40\x3e\110\x65\154\154\157\54\40\74\x62\x72\76\74\x62\162\76\106\x69\x72\163\x74\40\x4e\x61\155\x65\x20\72" . $current_user->user_firstname . "\x3c\x62\x72\x3e\74\x62\x72\76\114\141\x73\x74\40\40\116\141\155\x65\x20\72" . $current_user->user_lastname . "\x20\40\x20\74\x62\x72\76\74\142\162\x3e\x43\x6f\x6d\x70\x61\156\171\40\x3a\74\x61\x20\150\162\145\x66\75\42" . $ep . "\42\x20\x74\141\162\x67\145\164\75\42\x5f\x62\154\141\156\153\42\40\76" . $ep . "\74\57\141\x3e\74\x62\162\76\74\142\162\76\120\150\157\156\145\x20\116\165\155\x62\145\162\x20\x3a" . $fm . "\74\x62\x72\x3e\74\142\x72\76\x45\155\x61\151\154\x20\72\x3c\141\x20\x68\x72\145\146\75\42\x6d\141\151\154\164\157\72" . $g0 . "\42\40\164\141\162\x67\x65\x74\75\42\x5f\142\154\x61\156\x6b\42\x3e" . $g0 . "\74\x2f\x61\76\74\142\162\x3e\74\142\162\76\121\165\x65\162\x79\x20\x3a" . $GQ . "\x3c\x2f\x64\x69\x76\x3e";
        $vX = array("\x63\165\163\x74\x6f\x6d\145\x72\x4b\145\171" => $i_, "\x73\145\x6e\144\x45\x6d\141\x69\x6c" => true, \MoOAuthConstants::EMAIL => array("\x63\x75\x73\x74\x6f\x6d\x65\x72\x4b\x65\x79" => $i_, "\146\x72\x6f\x6d\105\155\x61\x69\x6c" => $g0, "\x62\143\143\105\x6d\x61\151\x6c" => "\x69\156\146\157\x40\x78\145\x63\165\x72\151\146\171\x2e\x63\x6f\x6d", "\x66\x72\x6f\155\116\141\x6d\145" => "\x6d\x69\156\151\x4f\162\x61\156\147\x65", "\x74\x6f\x45\x6d\x61\x69\154" => "\x6f\x61\165\164\x68\x73\x75\x70\x70\x6f\162\164\100\x78\x65\x63\x75\x72\151\146\171\56\x63\157\155", "\x74\157\116\x61\155\x65" => "\x6f\x61\165\164\150\163\165\x70\160\157\x72\x74\x40\x78\x65\143\x75\162\x69\x66\x79\56\x63\x6f\x6d", "\x73\x75\x62\152\145\x63\164" => $Gn, "\x63\x6f\x6e\164\145\x6e\164" => $dV));
        $n6 = json_encode($vX, JSON_UNESCAPED_SLASHES);
        $l9 = array("\103\x6f\156\164\145\x6e\164\x2d\x54\171\160\x65" => "\x61\160\160\x6c\151\x63\141\164\151\x6f\x6e\x2f\152\x73\x6f\156");
        $l9["\x43\x75\163\x74\x6f\x6d\x65\x72\55\x4b\x65\171"] = $i_;
        $l9["\124\x69\x6d\145\163\x74\x61\x6d\x70"] = $a7;
        $l9["\101\x75\x74\150\157\x72\151\x7a\x61\x74\x69\157\156"] = $jm;
        return $this->send_request($l9, true, $n6, [], false, $ht);
    }
    public function submit_contact_us_upgrade($g3, $q2, $x3, $Fc)
    {
        global $Uj;
        $i_ = $this->default_customer_key;
        $aG = $this->default_api_key;
        $a7 = time();
        $ht = $this->host_name . "\57\x6d\157\x61\163\x2f\x61\x70\x69\57\156\157\164\x69\x66\x79\57\x73\145\156\x64";
        $NS = $i_ . $a7 . $aG;
        $jm = hash("\x73\150\141\65\x31\x32", $NS);
        $g0 = $g3;
        $YD = \ucwords(\strtolower($Uj->get_versi_str())) . "\40\x2d\40" . \mo_oauth_get_version_number();
        $Gn = "\x51\165\145\x72\171\x3a\40\x57\157\162\x64\120\162\x65\x73\163\x20\x4f\x41\165\x74\x68\x20\x55\x70\147\162\141\144\145\x20\120\x6c\165\x67\x69\x6e";
        $ep = isset($_SERVER["\x53\105\122\x56\x45\x52\137\x4e\101\115\x45"]) ? sanitize_text_field(wp_unslash($_SERVER["\x53\x45\122\126\105\122\137\116\101\115\x45"])) : '';
        $dV = "\x3c\x64\151\166\40\76\x48\x65\x6c\x6c\157\x2c\40\x20\x20\74\142\x72\x3e\x3c\x62\162\x3e\x43\157\x6d\160\x61\156\171\40\x3a\x3c\141\40\x68\x72\x65\146\x3d\42" . $ep . "\x22\40\164\141\x72\147\x65\164\x3d\x22\x5f\x62\154\141\156\153\x22\x20\76" . $ep . "\x3c\57\x61\x3e\74\142\x72\x3e\x3c\142\x72\x3e\x43\x75\162\x72\145\x6e\x74\40\126\145\x72\163\x69\x6f\156\x20\x3a" . $q2 . "\74\x62\x72\x3e\74\142\x72\x3e\x45\x6d\x61\151\x6c\40\72\x3c\x61\40\x68\x72\x65\146\75\x22\x6d\141\x69\154\x74\x6f\x3a" . $g0 . "\42\40\164\141\162\147\x65\x74\75\42\137\142\x6c\x61\156\153\x22\76" . $g0 . "\x3c\x2f\x61\76\74\x62\162\76\x3c\x62\162\76\126\145\x72\163\151\x6f\156\x20\x74\x6f\x20\125\x70\x67\162\141\144\145\40\x3a" . $x3 . "\74\x62\x72\x3e\74\142\162\76\x46\x65\x61\x74\165\162\x65\x73\40\122\145\x71\165\x69\x72\145\144\x20\72" . $Fc . "\74\x2f\x64\x69\x76\x3e";
        $vX = array("\x63\x75\x73\164\x6f\155\x65\162\x4b\145\x79" => $i_, "\x73\145\156\x64\x45\x6d\141\x69\x6c" => true, \MoOAuthConstants::EMAIL => array("\143\165\163\164\157\x6d\145\x72\x4b\x65\x79" => $i_, "\x66\162\157\155\x45\155\141\151\x6c" => $g0, "\142\143\x63\105\x6d\141\151\154" => "\x69\x6e\x66\157\100\x78\x65\x63\x75\x72\x69\146\171\x2e\143\157\155", "\x66\162\x6f\155\116\x61\155\x65" => "\155\x69\x6e\151\117\162\x61\x6e\x67\x65", "\164\157\105\155\141\151\154" => "\157\141\165\164\x68\x73\165\160\x70\x6f\x72\164\100\170\145\143\165\162\x69\x66\x79\x2e\143\157\155", "\x74\157\116\141\x6d\x65" => "\x6f\x61\x75\x74\150\163\165\160\x70\157\x72\x74\x40\170\x65\143\165\162\x69\x66\171\56\143\x6f\155", "\163\165\142\x6a\145\x63\x74" => $Gn, "\x63\157\x6e\164\145\156\x74" => $dV));
        $n6 = json_encode($vX, JSON_UNESCAPED_SLASHES);
        $l9 = array("\x43\157\x6e\164\x65\156\164\x2d\x54\x79\x70\x65" => "\x61\160\160\x6c\x69\x63\x61\164\151\x6f\156\x2f\x6a\x73\157\x6e");
        $l9["\x43\x75\x73\164\157\x6d\145\x72\x2d\x4b\145\x79"] = $i_;
        $l9["\124\x69\x6d\145\163\164\141\x6d\x70"] = $a7;
        $l9["\101\165\x74\x68\x6f\x72\x69\172\x61\164\151\x6f\156"] = $jm;
        return $this->send_request($l9, true, $n6, [], false, $ht);
    }
    public function send_otp_token($g3 = '', $fm = '', $ac = true, $YG = false)
    {
        global $Uj;
        $ht = $this->host_name . "\57\155\x6f\141\x73\57\141\x70\x69\57\x61\165\164\x68\x2f\143\x68\x61\x6c\154\x65\x6e\147\x65";
        $i_ = $this->default_customer_key;
        $aG = $this->default_api_key;
        $sP = $this->email;
        $fm = $Uj->mo_oauth_client_get_option("\155\157\x5f\157\141\x75\x74\150\137\x61\144\155\x69\x6e\137\x70\150\x6f\x6e\x65");
        $a7 = self::get_timestamp();
        $NS = $i_ . $a7 . $aG;
        $jm = hash("\x73\x68\141\x35\61\62", $NS);
        $n2 = "\103\165\x73\x74\157\155\x65\x72\55\x4b\x65\x79\x3a\40" . $i_;
        $uS = "\x54\x69\x6d\x65\x73\164\141\x6d\160\72\40" . $a7;
        $fq = "\101\165\164\150\x6f\162\151\x7a\141\x74\151\157\x6e\72\40" . $jm;
        if ($ac) {
            goto tx;
        }
        $vX = array("\x63\x75\163\x74\157\155\x65\162\x4b\145\171" => $i_, "\160\150\x6f\156\x65" => $fm, "\x61\x75\164\x68\x54\x79\x70\x65" => "\x53\x4d\x53");
        goto Gd;
        tx:
        $vX = array("\x63\165\x73\164\157\155\x65\162\x4b\x65\x79" => $i_, \MoOAuthConstants::EMAIL => $sP, "\141\165\164\150\124\171\x70\x65" => "\x45\x4d\x41\111\114");
        Gd:
        $n6 = wp_json_encode($vX);
        $l9 = array("\103\157\156\164\145\x6e\164\x2d\124\x79\160\145" => "\x61\x70\160\x6c\151\143\141\x74\151\157\x6e\57\x6a\x73\157\156");
        $l9["\x43\x75\x73\x74\157\x6d\x65\x72\55\113\145\171"] = $i_;
        $l9["\x54\x69\155\x65\x73\164\x61\155\160"] = $a7;
        $l9["\x41\165\164\x68\157\x72\151\172\141\164\x69\157\x6e"] = $jm;
        return $this->send_request($l9, true, $n6, [], false, $ht);
    }
    public function get_timestamp()
    {
        global $Uj;
        $ht = $this->host_name . "\57\155\157\x61\x73\57\x72\145\x73\164\x2f\x6d\x6f\x62\x69\x6c\x65\x2f\147\145\164\55\164\x69\155\145\x73\x74\x61\155\160";
        return $this->send_request([], false, '', [], false, $ht);
    }
    public function validate_otp_token($LX, $at)
    {
        global $Uj;
        $ht = $this->host_name . "\57\155\x6f\x61\x73\x2f\141\160\151\57\x61\x75\164\150\x2f\166\x61\x6c\151\144\141\x74\145";
        $i_ = $this->default_customer_key;
        $aG = $this->default_api_key;
        $sP = $this->email;
        $a7 = self::get_timestamp();
        $NS = $i_ . $a7 . $aG;
        $jm = hash("\x73\150\141\x35\61\62", $NS);
        $n2 = "\x43\x75\x73\164\157\x6d\145\162\55\x4b\145\x79\x3a\40" . $i_;
        $uS = "\124\x69\155\145\x73\164\141\155\160\72\40" . $a7;
        $fq = "\101\165\x74\x68\157\162\x69\x7a\141\164\x69\157\x6e\72\40" . $jm;
        $n6 = '';
        $vX = array("\x74\x78\111\144" => $LX, "\x74\157\153\x65\156" => $at);
        $n6 = wp_json_encode($vX);
        $l9 = array("\103\x6f\x6e\164\145\156\164\55\124\x79\160\x65" => "\141\160\x70\x6c\x69\143\141\164\x69\x6f\156\57\x6a\x73\x6f\156");
        $l9["\103\165\x73\x74\x6f\155\145\162\55\x4b\x65\171"] = $i_;
        $l9["\124\151\155\145\163\164\x61\x6d\x70"] = $a7;
        $l9["\101\x75\164\x68\x6f\162\151\172\x61\x74\x69\x6f\156"] = $jm;
        return $this->send_request($l9, true, $n6, [], false, $ht);
    }
    public function check_customer()
    {
        global $Uj;
        $ht = $this->host_name . "\x2f\155\x6f\x61\x73\x2f\x72\x65\163\x74\57\x63\x75\x73\x74\x6f\155\x65\x72\x2f\x63\150\x65\x63\153\x2d\151\x66\55\x65\x78\x69\x73\x74\163";
        $g3 = $this->email;
        $vX = array(\MoOAuthConstants::EMAIL => $g3);
        $n6 = wp_json_encode($vX);
        return $this->send_request([], false, $n6, [], false, $ht);
    }
    public function mo_oauth_send_email_alert($g3, $fm, $fU)
    {
        global $Uj;
        if ($this->check_internet_connection()) {
            goto JG;
        }
        return;
        JG:
        $ht = $this->host_name . "\x2f\x6d\157\141\163\57\141\160\x69\57\x6e\x6f\x74\x69\146\171\x2f\163\x65\x6e\x64";
        global $user;
        $i_ = $this->default_customer_key;
        $aG = $this->default_api_key;
        $a7 = self::get_timestamp();
        $NS = $i_ . $a7 . $aG;
        $jm = hash("\163\150\x61\x35\x31\62", $NS);
        $g0 = $g3;
        $Gn = "\106\145\x65\x64\x62\x61\143\x6b\x3a\x20\x57\x6f\x72\x64\x50\x72\x65\163\x73\x20\117\101\165\x74\150\x20\103\x6c\x69\x65\x6e\164\x20\120\x6c\x75\x67\151\156";
        $AJ = site_url();
        $user = wp_get_current_user();
        $YD = \ucwords(\strtolower($Uj->get_versi_str())) . "\40\x2d\x20" . \mo_oauth_get_version_number();
        $GQ = "\133\x57\x50\40\117\x41\165\164\x68\x20\x32\56\60\x20\x43\x6c\x69\x65\156\164\x20" . $YD . "\135\x20\72\x20" . $fU;
        $ep = isset($_SERVER["\x53\105\x52\x56\105\x52\x5f\x4e\x41\115\x45"]) ? sanitize_text_field(wp_unslash($_SERVER["\123\x45\122\126\105\122\x5f\116\101\115\x45"])) : '';
        $dV = "\x3c\x64\x69\x76\40\x3e\x48\x65\154\x6c\157\54\x20\x3c\142\162\x3e\74\142\162\x3e\106\x69\162\x73\x74\x20\x4e\141\x6d\x65\40\x3a" . $user->user_firstname . "\x3c\x62\162\x3e\x3c\x62\162\x3e\114\141\163\x74\40\x20\x4e\x61\x6d\x65\x20\x3a" . $user->user_lastname . "\x20\40\40\x3c\142\x72\76\x3c\x62\162\76\103\157\155\x70\141\x6e\x79\40\72\74\x61\x20\x68\162\145\x66\x3d\x22" . $ep . "\42\40\164\141\x72\x67\x65\x74\x3d\42\x5f\142\x6c\141\x6e\x6b\42\x20\x3e" . $ep . "\74\x2f\141\x3e\x3c\142\x72\76\74\142\162\x3e\120\x68\x6f\x6e\x65\x20\116\x75\x6d\142\x65\x72\40\x3a" . $fm . "\74\142\162\x3e\x3c\x62\x72\76\x45\x6d\141\151\154\40\x3a\74\141\40\x68\x72\145\146\75\42\155\141\151\x6c\164\157\x3a" . $g0 . "\42\40\164\x61\x72\x67\x65\x74\x3d\42\x5f\x62\x6c\141\x6e\x6b\42\76" . $g0 . "\x3c\57\141\76\x3c\x62\162\x3e\x3c\x62\162\76\x51\165\x65\x72\x79\40\72" . $GQ . "\x3c\x2f\x64\151\166\76";
        $vX = array("\143\x75\x73\x74\x6f\155\145\162\113\145\171" => $i_, "\x73\145\x6e\x64\105\x6d\x61\151\154" => true, \MoOAuthConstants::EMAIL => array("\x63\x75\x73\164\x6f\x6d\x65\x72\113\x65\171" => $i_, "\x66\x72\157\x6d\x45\155\141\151\154" => $g0, "\x62\x63\143\105\155\141\x69\x6c" => "\157\x61\x75\x74\x68\x73\165\x70\x70\x6f\x72\164\x40\x6d\151\156\151\x6f\x72\x61\156\x67\145\56\143\x6f\155", "\146\x72\157\155\116\x61\x6d\x65" => "\155\x69\x6e\x69\x4f\x72\x61\156\x67\x65", "\164\x6f\105\x6d\x61\x69\x6c" => "\157\141\165\164\150\163\165\160\x70\x6f\x72\164\x40\x6d\151\156\x69\x6f\x72\x61\x6e\x67\x65\x2e\143\157\x6d", "\x74\x6f\x4e\141\x6d\145" => "\157\x61\165\x74\x68\163\x75\160\x70\157\162\164\x40\155\151\156\x69\x6f\x72\x61\x6e\147\x65\x2e\x63\157\155", "\163\165\x62\152\145\143\164" => $Gn, "\143\x6f\x6e\164\145\x6e\x74" => $dV));
        $n6 = wp_json_encode($vX);
        $l9 = array("\103\x6f\156\x74\x65\x6e\x74\x2d\x54\171\160\x65" => "\141\160\x70\154\x69\x63\141\164\x69\157\x6e\x2f\x6a\x73\157\x6e");
        $l9["\103\x75\163\x74\x6f\x6d\x65\x72\x2d\113\145\171"] = $i_;
        $l9["\124\151\155\145\x73\164\x61\155\x70"] = $a7;
        $l9["\101\x75\x74\150\x6f\162\151\x7a\141\164\x69\157\156"] = $jm;
        return $this->send_request($l9, true, $n6, [], false, $ht);
    }
    public function mo_oauth_send_demo_alert($g3, $fF, $fU, $Gn)
    {
        if ($this->check_internet_connection()) {
            goto ll;
        }
        return;
        ll:
        $ht = $this->host_name . "\x2f\x6d\x6f\x61\163\57\x61\x70\151\x2f\x6e\157\164\x69\146\x79\57\x73\x65\156\x64";
        $i_ = $this->default_customer_key;
        $aG = $this->default_api_key;
        $a7 = self::get_timestamp();
        $NS = $i_ . $a7 . $aG;
        $jm = hash("\x73\150\x61\65\61\62", $NS);
        $g0 = $g3;
        global $user;
        $user = wp_get_current_user();
        $dV = "\x3c\x64\151\166\x20\76\110\145\154\x6c\157\x2c\x20\74\57\141\x3e\74\x62\162\x3e\74\x62\x72\76\x45\x6d\x61\x69\x6c\40\72\x3c\141\40\150\x72\x65\x66\75\x22\x6d\x61\x69\x6c\164\157\x3a" . $g0 . "\42\40\x74\x61\162\x67\145\x74\75\x22\x5f\142\154\141\x6e\153\x22\76" . $g0 . "\x3c\57\141\76\x3c\142\x72\76\74\142\x72\76\x52\145\161\165\145\x73\164\x65\144\40\104\x65\x6d\x6f\x20\146\157\162\40\x20\40\40\x20\72\x20" . $fF . "\x3c\x62\162\76\x3c\x62\162\x3e\x52\145\161\165\151\x72\x65\155\145\x6e\x74\163\x20\40\x20\x20\40\40\x20\40\x20\40\x20\x3a\x20" . $fU . "\74\x2f\x64\151\166\76";
        $vX = array("\x63\x75\x73\x74\x6f\155\145\x72\113\145\x79" => $i_, "\163\x65\x6e\144\x45\x6d\141\x69\154" => true, \MoOAuthConstants::EMAIL => array("\143\x75\163\164\x6f\x6d\145\x72\x4b\145\171" => $i_, "\146\162\x6f\x6d\x45\155\x61\151\x6c" => $g0, "\142\143\143\x45\155\x61\151\154" => "\x6f\x61\x75\164\150\163\165\160\160\x6f\162\x74\x40\x6d\151\156\151\x6f\162\141\156\x67\145\56\x63\157\155", "\x66\162\157\x6d\116\x61\155\145" => "\x6d\x69\156\x69\x4f\x72\x61\156\147\145", "\164\x6f\105\155\x61\x69\154" => "\x6f\141\165\x74\150\163\x75\x70\160\x6f\162\x74\100\155\x69\x6e\x69\x6f\x72\141\x6e\147\x65\56\143\157\x6d", "\x74\x6f\116\141\155\145" => "\157\x61\x75\x74\x68\163\x75\160\160\157\162\164\100\x6d\x69\x6e\x69\157\x72\x61\x6e\147\x65\x2e\143\157\x6d", "\163\165\142\152\145\x63\164" => $Gn, "\143\157\156\x74\145\156\164" => $dV));
        $n6 = json_encode($vX);
        $l9 = array("\103\157\156\x74\145\156\x74\55\x54\171\160\145" => "\x61\x70\x70\x6c\151\143\141\164\x69\x6f\x6e\x2f\x6a\x73\157\156");
        $l9["\103\x75\163\164\157\x6d\145\x72\55\x4b\x65\x79"] = $i_;
        $l9["\x54\x69\155\145\163\164\141\x6d\x70"] = $a7;
        $l9["\x41\165\164\x68\157\x72\x69\x7a\141\x74\x69\157\x6e"] = $jm;
        $Yx = $this->send_request($l9, true, $n6, [], false, $ht);
    }
    public function mo_oauth_forgot_password($g3)
    {
        global $Uj;
        $ht = $this->host_name . "\57\x6d\x6f\141\x73\57\162\x65\x73\164\57\143\165\163\x74\157\155\x65\162\57\x70\141\x73\x73\x77\x6f\x72\x64\x2d\x72\x65\x73\145\164";
        $i_ = $Uj->mo_oauth_client_get_option("\x6d\x6f\137\157\x61\x75\x74\150\137\141\x64\155\151\x6e\137\143\x75\163\164\x6f\155\x65\x72\x5f\153\145\171");
        $aG = $Uj->mo_oauth_client_get_option("\155\157\137\x6f\x61\165\x74\150\137\141\x64\155\x69\x6e\x5f\x61\160\151\137\153\x65\171");
        $a7 = self::get_timestamp();
        $NS = $i_ . $a7 . $aG;
        $jm = hash("\163\150\x61\x35\61\x32", $NS);
        $n2 = "\x43\165\x73\x74\157\155\x65\162\x2d\113\145\x79\x3a\x20" . $i_;
        $uS = "\124\x69\x6d\x65\x73\x74\141\155\160\72\x20" . number_format($a7, 0, '', '');
        $fq = "\101\x75\164\x68\x6f\162\x69\x7a\x61\164\151\157\156\72\x20" . $jm;
        $n6 = '';
        $vX = array(\MoOAuthConstants::EMAIL => $g3);
        $n6 = wp_json_encode($vX);
        $l9 = array("\x43\x6f\156\x74\145\x6e\x74\x2d\124\x79\160\145" => "\x61\x70\x70\x6c\151\143\x61\x74\151\x6f\156\x2f\x6a\163\x6f\x6e");
        $l9["\x43\165\x73\164\x6f\155\x65\x72\x2d\113\145\171"] = $i_;
        $l9["\x54\x69\x6d\x65\x73\164\x61\x6d\160"] = $a7;
        $l9["\x41\x75\x74\x68\x6f\x72\x69\x7a\x61\x74\x69\x6f\156"] = $jm;
        return $this->send_request($l9, true, $n6, [], false, $ht);
    }
    public function check_internet_connection()
    {
        return (bool) @fsockopen("\x6c\x6f\x67\151\x6e\x2e\x78\145\143\x75\x72\x69\x66\171\56\143\x6f\x6d", 443, $SQ, $HR, 5);
    }
    private function send_request($f0 = false, $cJ = false, $n6 = '', $lb = false, $kn = false, $ht = '')
    {
        $l9 = array("\x43\x6f\156\x74\145\x6e\164\x2d\x54\171\x70\145" => "\141\160\x70\154\151\x63\x61\x74\x69\157\x6e\x2f\x6a\x73\x6f\156", "\x63\150\x61\x72\x73\x65\x74" => "\125\124\106\x20\55\40\70", "\x41\165\x74\x68\157\162\151\x7a\141\x74\x69\x6f\x6e" => "\x42\141\163\x69\x63");
        $l9 = $cJ && $f0 ? $f0 : array_unique(array_merge($l9, $f0));
        $z5 = array("\155\x65\x74\150\157\x64" => "\x50\117\123\124", "\142\157\144\x79" => $n6, "\164\151\x6d\145\x6f\165\x74" => "\61\65", "\x72\x65\x64\x69\162\x65\x63\164\151\x6f\x6e" => "\x35", "\x68\164\x74\160\166\x65\x72\163\151\157\x6e" => "\61\56\60", "\x62\154\157\x63\153\151\156\x67" => true, "\x68\145\x61\x64\x65\162\x73" => $l9, "\163\x73\x6c\x76\x65\x72\x69\146\x79" => true);
        $z5 = $kn ? $lb : array_unique(array_merge($z5, $lb), SORT_REGULAR);
        $Yx = wp_remote_post($ht, $z5);
        if (!is_wp_error($Yx)) {
            goto E3;
        }
        $XS = $Yx->get_error_message();
        echo wp_kses("\123\x6f\x6d\145\164\150\x69\x6e\x67\x20\167\x65\156\164\x20\x77\162\157\x6e\147\x3a\x20{$XS}", \mo_oauth_get_valid_html());
        exit;
        E3:
        return wp_remote_retrieve_body($Yx);
    }
}
