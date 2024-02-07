<?php


namespace MoOauthClient\LicenseLibrary\Classes;

use MoOauthClient\LicenseLibrary\Mo_License_Config;
use MoOauthClient\LicenseLibrary\Utils\Mo_License_API_Utility;
if (defined("\101\102\x53\x50\101\124\110")) {
    goto kHB;
}
exit;
kHB:
class Mo_License_API_Client
{
    public static function fetch_license_info()
    {
        $Ws = Mo_License_Constants::HOSTNAME . "\x2f\155\157\x61\163\x2f\162\x65\163\164\x2f\x63\165\163\x74\157\155\x65\x72\57\x6c\151\x63\145\156\x73\145";
        $oe = Mo_License_Dao::mo_get_option(Mo_License_Config::CUSTOMER_KEY_OPTION);
        if ($oe) {
            goto XN9;
        }
        return false;
        XN9:
        $rb = Mo_License_API_Utility::get_current_time_in_millis($oe);
        $Ld = array("\x63\165\163\164\157\x6d\x65\162\x49\144" => $oe, "\141\x70\x70\x6c\x69\143\x61\x74\x69\157\156\116\x61\155\x65" => Mo_License_Config::APPLICATION);
        $k7 = Mo_License_API_Utility::get_api_headers($oe, $rb["\x6d\x69\154\154\x69\x54\x69\155\x65"], $rb["\150\141\x73\x68"]);
        $uo = Mo_License_API_Utility::get_api_args($Ld, $k7);
        $uh = Mo_License_API_Utility::mo_wp_remote_call($Ws, $uo);
        return $uh;
    }
}
