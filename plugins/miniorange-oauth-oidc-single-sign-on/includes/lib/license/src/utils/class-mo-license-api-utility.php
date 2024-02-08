<?php


namespace MoOauthClient\LicenseLibrary\Utils;

use MoOauthClient\LicenseLibrary\Classes\Mo_License_Dao;
use MoOauthClient\LicenseLibrary\Mo_License_Config;
if (defined("\x41\102\123\x50\101\124\x48")) {
    goto iVn;
}
exit;
iVn:
class Mo_License_API_Utility
{
    public static function get_current_time_in_millis($oe)
    {
        $hC = Mo_License_Dao::mo_get_option(Mo_License_Config::API_KEY_OPTION);
        $zj = round(microtime(true) * 1000);
        $bF = $oe . number_format($zj, 0, '', '') . $hC;
        $lY = hash("\x73\x68\x61\x35\61\62", $bF);
        $zj = number_format($zj, 0, '', '');
        return array("\155\151\x6c\154\x69\x54\x69\x6d\x65" => $zj, "\x68\141\163\x68" => $lY);
    }
    public static function get_api_headers($oe, $rB, $lY)
    {
        return array("\x43\x6f\x6e\164\145\156\x74\55\124\171\x70\x65" => "\141\160\160\x6c\151\143\x61\164\151\x6f\x6e\x2f\152\163\157\156", "\x43\x75\x73\164\x6f\155\x65\x72\55\x4b\x65\x79" => $oe, "\124\x69\155\x65\x73\164\141\155\160" => $rB, "\101\165\x74\x68\157\x72\151\172\141\x74\151\x6f\156" => $lY);
    }
    public static function get_api_args($Ld, $k7)
    {
        $kb = wp_json_encode($Ld);
        return array("\x6d\145\164\x68\157\x64" => "\x50\x4f\x53\124", "\x62\157\x64\x79" => $kb, "\x74\x69\155\145\x6f\x75\x74" => "\x31\x30", "\x72\x65\144\151\x72\145\143\164\x69\x6f\156" => "\65", "\150\x74\x74\x70\x76\x65\162\x73\x69\x6f\x6e" => "\x31\x2e\x30", "\142\x6c\x6f\143\153\x69\156\147" => true, "\x68\x65\141\144\145\x72\x73" => $k7);
    }
    public static function mo_wp_remote_call($Ws, $uo = array(), $Dz = false)
    {
        if (!$Dz) {
            goto tQW;
        }
        $uh = wp_remote_get($Ws, $uo);
        goto ao4;
        tQW:
        $uh = wp_remote_post($Ws, $uo);
        ao4:
        if (!is_wp_error($uh)) {
            goto fRP;
        }
        return false;
        goto xLl;
        fRP:
        return $uh["\142\157\x64\x79"];
        xLl:
    }
}
