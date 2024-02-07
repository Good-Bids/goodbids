<?php


namespace MoOauthClient\LicenseLibrary\Utils;

use MoOauthClient\LicenseLibrary\Classes\Mo_License_API_Client;
use MoOauthClient\LicenseLibrary\Classes\Mo_License_Constants;
use MoOauthClient\LicenseLibrary\Classes\Mo_License_Library;
use MoOauthClient\LicenseLibrary\Mo_License_Config;
use MoOauthClient\LicenseLibrary\Classes\Mo_License_Dao;
if (defined("\x41\x42\x53\120\x41\124\x48")) {
    goto UZi;
}
exit;
UZi:
class Mo_License_Actions_Utility
{
    public static function fetch_license_expiry_date()
    {
        try {
            $Pb = Mo_License_API_Client::fetch_license_info();
            if (!empty($Pb)) {
                goto dQw;
            }
            return false;
            dQw:
            $Pb = json_decode($Pb, true);
            if (!(!empty($Pb["\163\164\141\x74\165\x73"]) && strcasecmp($Pb["\x73\164\x61\164\165\163"], "\x53\x55\x43\x43\x45\123\x53") === 0)) {
                goto LjU;
            }
            if (empty($Pb["\154\151\143\x65\156\163\x65\105\x78\x70\x69\x72\x79"])) {
                goto nJg;
            }
            return $Pb["\154\x69\x63\145\x6e\163\145\105\x78\x70\151\x72\x79"];
            nJg:
            return false;
            LjU:
            return false;
        } catch (\Exception $Wi) {
            return false;
        }
    }
    public static function get_current_environment_hook_name($o3)
    {
        return Mo_License_Constants::ENVIRONMENT_SPECIFIC_HOOKS[$o3][Mo_License_Library::$environment_type];
    }
    public static function get_environment_type()
    {
        if (!function_exists("\x69\x73\x5f\x70\154\x75\x67\x69\156\x5f\x61\143\164\151\x76\x65\x5f\146\x6f\x72\x5f\156\x65\164\x77\x6f\162\153")) {
            require_once ABSPATH . "\x2f\167\160\x2d\x61\144\155\x69\156\57\x69\x6e\x63\x6c\x75\x64\145\163\x2f\x70\154\x75\x67\x69\x6e\56\x70\x68\x70";
        }
        $uI = explode("\x2f", Mo_License_Config::PLUGIN_FILE);
        $Yk = (array) Mo_License_Dao::mo_get_option("\141\143\164\151\166\145\x5f\x73\x69\164\145\167\x69\x64\145\x5f\x70\154\165\x67\151\x6e\163", array());
        if (is_plugin_active_for_network(Mo_License_Config::PLUGIN_FILE)) {
            goto oWc;
        }
        if (!empty($Yk)) {
            goto WT5;
        }
        goto UFK;
        oWc:
        return "\x6e\x65\x74\x77\157\162\153";
        goto UFK;
        WT5:
        foreach ($Yk as $WT => $LQ) {
            if (!(strpos($WT, $uI[0]) !== false)) {
                goto UOU;
            }
            return "\x6e\x65\x74\167\157\162\153";
            UOU:
            ph8:
        }
        ZPw:
        UFK:
        return "\x73\164\141\x6e\x64\141\154\x6f\x6e\x65";
    }
}
