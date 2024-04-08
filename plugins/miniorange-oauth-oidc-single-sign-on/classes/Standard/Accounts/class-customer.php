<?php


namespace MoOauthClient\Standard;

use MoOauthClient\Customer as NormalCustomer;
class Customer extends NormalCustomer
{
    public $email;
    public $phone;
    private $default_customer_key = "\x31\66\x35\65\x35";
    private $default_api_key = "\x66\x46\144\62\x58\143\166\124\107\x44\145\x6d\132\166\x62\167\61\142\143\x55\x65\163\116\x4a\127\x45\161\113\x62\x62\125\x71";
    public function check_customer_ln()
    {
        global $Uj;
        $ht = $Uj->mo_oauth_client_get_option("\x68\157\x73\x74\137\156\x61\155\145") . "\57\x6d\x6f\x61\x73\57\x72\145\x73\164\57\x63\165\x73\x74\x6f\155\x65\162\x2f\154\151\143\145\156\163\145";
        $i_ = $Uj->mo_oauth_client_get_option("\155\x6f\x5f\x6f\141\x75\164\150\137\x61\x64\155\151\156\137\143\x75\x73\x74\157\x6d\145\x72\137\153\x65\171");
        $aG = $Uj->mo_oauth_client_get_option("\x6d\157\x5f\x6f\x61\165\164\150\x5f\141\144\x6d\151\x6e\137\141\160\x69\137\153\x65\x79");
        $sP = $Uj->mo_oauth_client_get_option("\155\157\137\x6f\141\165\x74\150\x5f\141\144\x6d\x69\x6e\x5f\145\155\141\151\154");
        $fm = $Uj->mo_oauth_client_get_option("\x6d\x6f\137\157\141\x75\164\x68\137\141\144\155\151\x6e\x5f\160\x68\x6f\x6e\x65");
        $a7 = self::get_timestamp();
        $NS = $i_ . $a7 . $aG;
        $jm = hash("\x73\x68\x61\65\x31\62", $NS);
        $n2 = "\x43\x75\x73\164\x6f\x6d\x65\x72\55\113\145\x79\72\x20" . $i_;
        $uS = "\x54\x69\x6d\x65\x73\x74\x61\155\160\x3a\x20" . $a7;
        $fq = "\x41\165\x74\150\x6f\x72\151\x7a\x61\164\151\157\156\x3a\x20" . $jm;
        $vX = '';
        $vX = array("\143\165\163\164\x6f\x6d\145\162\x49\144" => $i_, "\141\160\160\x6c\x69\143\141\x74\151\x6f\x6e\116\x61\x6d\145" => "\x77\160\137\x6f\141\165\164\x68\x5f\x63\x6c\x69\145\156\x74\137" . \strtolower($Uj->get_versi_str()) . "\x5f\160\154\x61\156");
        $n6 = wp_json_encode($vX);
        $l9 = array("\x43\x6f\156\x74\x65\x6e\164\55\124\171\x70\145" => "\x61\x70\x70\x6c\151\143\x61\x74\151\x6f\156\x2f\152\x73\x6f\156");
        $l9["\x43\x75\x73\164\x6f\155\x65\x72\x2d\x4b\x65\171"] = $i_;
        $l9["\x54\151\155\x65\x73\164\x61\x6d\160"] = $a7;
        $l9["\x41\x75\x74\150\x6f\162\151\172\x61\164\x69\157\x6e"] = $jm;
        $z5 = array("\x6d\145\164\150\x6f\144" => "\x50\x4f\x53\124", "\x62\x6f\x64\x79" => $n6, "\x74\151\155\x65\157\165\164" => "\x35", "\x72\x65\x64\151\x72\145\143\x74\x69\x6f\156" => "\x35", "\x68\x74\164\x70\x76\x65\162\x73\151\x6f\156" => "\x31\x2e\60", "\142\154\x6f\x63\153\151\156\147" => true, "\150\x65\141\144\145\x72\x73" => $l9);
        $Yx = wp_remote_post($ht, $z5);
        if (!is_wp_error($Yx)) {
            goto tRp;
        }
        $XS = $Yx->get_error_message();
        echo "\x53\x6f\155\145\x74\150\x69\156\x67\40\167\x65\x6e\164\x20\167\x72\157\x6e\147\72\x20{$XS}";
        exit;
        tRp:
        return wp_remote_retrieve_body($Yx);
    }
    public function XfskodsfhHJ($SJ)
    {
        global $Uj;
        $ht = $Uj->mo_oauth_client_get_option("\150\157\163\x74\137\x6e\141\155\x65") . "\x2f\x6d\157\x61\x73\x2f\x61\x70\x69\57\142\141\x63\x6b\165\160\x63\157\144\145\57\166\x65\x72\151\x66\171";
        $i_ = $Uj->mo_oauth_client_get_option("\x6d\x6f\x5f\157\x61\x75\164\150\x5f\141\144\x6d\x69\156\x5f\x63\x75\163\x74\x6f\155\145\162\x5f\153\x65\171");
        $aG = $Uj->mo_oauth_client_get_option("\155\157\x5f\x6f\141\x75\164\x68\x5f\141\144\x6d\x69\x6e\x5f\x61\x70\x69\x5f\153\x65\171");
        $sP = $Uj->mo_oauth_client_get_option("\x6d\x6f\x5f\x6f\141\x75\x74\150\137\x61\x64\x6d\x69\x6e\137\x65\155\x61\x69\x6c");
        $fm = $Uj->mo_oauth_client_get_option("\155\x6f\x5f\157\x61\165\x74\x68\x5f\141\x64\x6d\x69\156\137\160\150\157\x6e\x65");
        $a7 = self::get_timestamp();
        $NS = $i_ . $a7 . $aG;
        $jm = hash("\163\x68\x61\65\x31\62", $NS);
        $n2 = "\103\165\163\164\x6f\x6d\145\162\55\113\145\x79\x3a\x20" . $i_;
        $uS = "\124\x69\155\145\163\x74\x61\x6d\160\x3a\40" . $a7;
        $fq = "\x41\165\164\150\x6f\162\151\172\141\x74\151\x6f\156\72\40" . $jm;
        $vX = '';
        $vX = array("\x63\157\144\145" => $SJ, "\x63\x75\163\x74\x6f\x6d\145\162\x4b\x65\x79" => $i_, "\141\x64\x64\151\164\151\157\156\141\x6c\106\x69\145\x6c\x64\x73" => array("\x66\x69\145\154\x64\x31" => site_url()));
        $n6 = wp_json_encode($vX);
        $l9 = array("\103\157\156\164\x65\x6e\164\x2d\x54\x79\160\x65" => "\141\x70\160\154\151\x63\x61\164\x69\157\x6e\x2f\x6a\163\157\156");
        $l9["\103\165\163\164\157\x6d\x65\x72\x2d\113\145\x79"] = $i_;
        $l9["\x54\151\155\145\163\x74\141\x6d\x70"] = $a7;
        $l9["\101\165\164\150\x6f\162\151\172\141\164\x69\x6f\x6e"] = $jm;
        $z5 = array("\x6d\x65\164\150\157\144" => "\120\x4f\x53\x54", "\142\157\144\171" => $n6, "\x74\x69\155\145\x6f\165\x74" => "\x35", "\x72\145\144\151\162\145\143\x74\x69\x6f\x6e" => "\x35", "\150\164\164\160\166\x65\162\x73\151\x6f\x6e" => "\61\x2e\x30", "\x62\x6c\157\143\153\x69\156\147" => true, "\150\x65\x61\144\x65\x72\163" => $l9);
        $Yx = wp_remote_post($ht, $z5);
        if (!is_wp_error($Yx)) {
            goto IzK;
        }
        $XS = $Yx->get_error_message();
        echo "\x53\157\155\x65\x74\150\151\x6e\x67\x20\167\x65\156\x74\40\167\162\x6f\x6e\147\72\40{$XS}";
        exit;
        IzK:
        return wp_remote_retrieve_body($Yx);
    }
}
