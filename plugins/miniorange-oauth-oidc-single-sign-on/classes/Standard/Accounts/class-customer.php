<?php


namespace MoOauthClient\Standard;

use MoOauthClient\Customer as NormalCustomer;
class Customer extends NormalCustomer
{
    public $email;
    public $phone;
    private $default_customer_key = "\61\x36\65\x35\65";
    private $default_api_key = "\146\x46\x64\x32\130\x63\166\x54\x47\x44\x65\x6d\132\x76\142\167\61\x62\143\125\x65\163\116\x4a\x57\x45\161\113\x62\142\x55\161";
    public function check_customer_ln()
    {
        global $Yh;
        $Ws = $Yh->mo_oauth_client_get_option("\x68\157\x73\164\137\x6e\x61\x6d\x65") . "\57\155\x6f\141\163\x2f\162\145\163\x74\x2f\143\165\163\x74\157\x6d\x65\162\x2f\154\x69\x63\x65\156\x73\145";
        $oe = $Yh->mo_oauth_client_get_option("\155\x6f\x5f\157\x61\x75\x74\150\137\141\x64\155\151\156\137\143\x75\x73\164\157\x6d\x65\x72\x5f\x6b\x65\171");
        $hC = $Yh->mo_oauth_client_get_option("\155\x6f\137\157\141\165\164\x68\x5f\x61\144\x6d\151\156\137\141\x70\x69\137\x6b\x65\171");
        $WZ = $Yh->mo_oauth_client_get_option("\155\x6f\137\x6f\x61\165\x74\x68\137\x61\x64\155\151\x6e\137\x65\x6d\141\x69\x6c");
        $ge = $Yh->mo_oauth_client_get_option("\155\157\x5f\x6f\141\165\x74\150\x5f\141\x64\x6d\x69\x6e\137\x70\150\157\x6e\145");
        $zj = self::get_timestamp();
        $bF = $oe . $zj . $hC;
        $lY = hash("\163\x68\141\x35\61\62", $bF);
        $qP = "\x43\x75\x73\164\x6f\x6d\x65\x72\55\x4b\145\x79\72\40" . $oe;
        $EY = "\124\151\x6d\145\x73\x74\x61\155\x70\x3a\40" . $zj;
        $av = "\101\x75\164\150\x6f\x72\x69\172\141\164\x69\157\x6e\72\40" . $lY;
        $Ld = '';
        $Ld = array("\x63\165\x73\x74\x6f\x6d\x65\162\x49\144" => $oe, "\x61\160\x70\154\151\x63\141\x74\151\157\156\116\x61\x6d\x65" => "\x77\x70\137\x6f\x61\x75\x74\x68\137\143\154\x69\x65\x6e\x74\137" . \strtolower($Yh->get_versi_str()) . "\x5f\160\x6c\x61\x6e");
        $kb = wp_json_encode($Ld);
        $k7 = array("\103\x6f\156\164\x65\x6e\x74\x2d\x54\x79\160\x65" => "\x61\x70\160\154\x69\143\x61\x74\151\157\156\x2f\152\163\x6f\x6e");
        $k7["\103\165\163\164\157\x6d\145\x72\55\113\145\x79"] = $oe;
        $k7["\x54\x69\155\x65\x73\164\x61\155\x70"] = $zj;
        $k7["\101\x75\164\150\157\x72\x69\x7a\141\164\x69\x6f\x6e"] = $lY;
        $uo = array("\155\x65\x74\x68\157\x64" => "\x50\117\123\x54", "\142\157\144\x79" => $kb, "\164\x69\x6d\x65\157\165\164" => "\61\x35", "\162\x65\144\x69\x72\145\x63\164\x69\x6f\156" => "\65", "\x68\164\164\160\x76\145\162\163\x69\x6f\x6e" => "\61\56\x30", "\142\x6c\x6f\143\153\x69\156\147" => true, "\150\x65\141\x64\x65\x72\163" => $k7);
        $uh = wp_remote_post($Ws, $uo);
        if (!is_wp_error($uh)) {
            goto PWe;
        }
        $Go = $uh->get_error_message();
        echo "\123\157\x6d\145\164\x68\x69\156\x67\40\x77\145\x6e\164\x20\167\x72\x6f\x6e\147\72\x20" . esc_attr($Go);
        exit;
        PWe:
        return wp_remote_retrieve_body($uh);
    }
    public function XfskodsfhHJ($g0)
    {
        global $Yh;
        $Ws = $Yh->mo_oauth_client_get_option("\150\x6f\x73\164\x5f\156\x61\155\x65") . "\x2f\155\x6f\141\x73\57\141\x70\151\57\142\x61\x63\153\x75\x70\143\157\144\x65\57\x76\145\x72\x69\x66\x79";
        $oe = $Yh->mo_oauth_client_get_option("\x6d\x6f\137\x6f\x61\165\x74\150\137\x61\144\x6d\x69\x6e\x5f\x63\165\x73\164\x6f\x6d\145\162\137\153\x65\171");
        $hC = $Yh->mo_oauth_client_get_option("\155\157\x5f\x6f\141\x75\x74\150\x5f\x61\144\x6d\151\156\137\x61\x70\x69\137\153\x65\171");
        $WZ = $Yh->mo_oauth_client_get_option("\x6d\x6f\x5f\x6f\x61\x75\164\150\x5f\x61\x64\155\x69\156\x5f\x65\155\141\151\x6c");
        $ge = $Yh->mo_oauth_client_get_option("\155\157\x5f\x6f\141\x75\164\x68\x5f\x61\144\x6d\151\x6e\137\160\150\x6f\x6e\x65");
        $zj = self::get_timestamp();
        $zO = $Yh->mo_oauth_client_get_main_domain_name();
        $bF = $oe . $zj . $hC;
        $lY = hash("\x73\150\x61\x35\x31\62", $bF);
        $qP = "\x43\165\x73\164\x6f\155\x65\x72\x2d\x4b\145\x79\x3a\40" . $oe;
        $EY = "\x54\151\x6d\145\163\x74\141\x6d\x70\72\40" . $zj;
        $av = "\x41\x75\164\x68\x6f\162\151\x7a\x61\x74\151\x6f\x6e\72\40" . $lY;
        $Ld = '';
        $Ld = array("\x63\x6f\144\145" => $g0, "\x63\x75\163\x74\157\x6d\145\x72\113\x65\171" => $oe, "\x61\144\x64\151\164\x69\157\x6e\x61\x6c\x46\151\145\154\144\x73" => array("\x66\151\x65\x6c\x64\61" => $zO));
        $kb = wp_json_encode($Ld);
        $k7 = array("\103\x6f\156\x74\x65\156\x74\x2d\x54\171\x70\x65" => "\x61\160\160\x6c\151\x63\x61\x74\x69\157\x6e\x2f\x6a\163\157\156");
        $k7["\103\x75\x73\164\x6f\x6d\145\162\55\x4b\x65\x79"] = $oe;
        $k7["\x54\151\155\145\x73\164\141\x6d\160"] = $zj;
        $k7["\x41\165\x74\150\x6f\162\151\172\141\x74\x69\x6f\156"] = $lY;
        $uo = array("\x6d\145\164\150\x6f\x64" => "\x50\x4f\123\x54", "\x62\157\x64\171" => $kb, "\164\151\155\x65\157\x75\164" => "\61\65", "\x72\x65\144\x69\x72\x65\x63\164\x69\x6f\156" => "\65", "\150\x74\x74\x70\166\145\162\163\x69\x6f\156" => "\61\56\60", "\142\154\x6f\x63\153\x69\156\x67" => true, "\x68\145\x61\x64\145\x72\163" => $k7);
        $uh = wp_remote_post($Ws, $uo);
        if (!is_wp_error($uh)) {
            goto Xjj;
        }
        $Go = $uh->get_error_message();
        echo "\x53\157\x6d\145\164\x68\x69\x6e\147\40\167\x65\156\x74\40\167\162\x6f\156\147\x3a\x20" . esc_attr($Go);
        exit;
        Xjj:
        return wp_remote_retrieve_body($uh);
    }
}
