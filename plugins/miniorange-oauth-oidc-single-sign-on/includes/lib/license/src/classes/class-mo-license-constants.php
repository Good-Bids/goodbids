<?php


namespace MoOauthClient\LicenseLibrary\Classes;

use MoOauthClient\LicenseLibrary\Mo_License_Config;
if (defined("\x41\x42\123\120\x41\x54\110")) {
    goto fvY;
}
exit;
fvY:
class Mo_License_Constants
{
    const VERSION = "\61\x2e\60\x2e\65";
    const HOSTNAME = "\150\x74\x74\160\x73\x3a\x2f\x2f\154\x6f\147\x69\x6e\x2e\x78\145\x63\x75\162\151\x66\x79\56\x63\157\x6d";
    const EPOCH_DATE = "\112\141\x6e\165\141\162\x79\40\61\54\x20\61\x39\67\x30";
    const LAST_CHECK_TIME_OPTION = Mo_License_Config::OPTION_PREFIX . "\154\x6e\x5f\143\x68\x65\143\x6b\137\x74";
    const LICENSE_EXPIRY_DATE_OPTION = Mo_License_Config::OPTION_PREFIX . "\154\145\x64";
    const EXPIRY_NOTICE_CLOSE_OPTION = Mo_License_Config::OPTION_PREFIX . "\145\x78\160\x5f\x6e\157\164\151\143\x65\x5f\143\154\x6f\x73\145";
    const LICENSE_EXPIRED_OPTION = Mo_License_Config::OPTION_PREFIX . "\154\151\x63\145\156\x73\x65\137\145\170\x70\x69\162\145\x64";
    const DASHBOARD_WIDGET_ID = Mo_License_Config::OPTION_PREFIX . "\154\x69\x63\145\156\163\x65\137\x64\x65\164\x61\x69\154\163\x5f\167\x69\x64\x67\145\x74";
    const DASHBOARD_WIDGET_REFRESH_ID = Mo_License_Config::OPTION_PREFIX . "\x72\145\146\x72\145\x73\x68\x5f\145\170\160\151\x72\171";
    const ADMIN_NOTICE_DISMISS_ID = Mo_License_Config::OPTION_PREFIX . "\154\x69\143\x65\156\163\x65\x5f\141\x64\155\151\x6e\x5f\x6e\x6f\x74\151\x63\145\137\144\x69\163\x6d\151\x73\x73";
    const ADMIN_ERROR_MESSAGE = "\124\150\x65\40\154\x69\x6e\153\40\x79\157\165\40\146\x6f\154\x6c\x6f\x77\145\x64\40\x68\x61\163\x20\x65\170\x70\x69\x72\145\x64\x2e\x20\117\x72\x20\171\157\165\162\x20\160\x6c\x75\x67\151\156\x20\x6c\151\x63\x65\x6e\163\x65\40\x69\x73\x20\x69\156\166\x61\154\x69\x64\56";
    const EXPIRY_IN_30_TO_60_DAYS = 60;
    const EXPIRY_IN_10_TO_30_DAYS = 30;
    const EXPIRY_IN_10_DAYS = 10;
    const GRACE_PERIOD_STARTED = 0;
    const GRACE_PERIOD_EXPIRED = "\107\x52\101\103\105\x5f\105\x58\120\111\x52\105\x44";
    const ENVIRONMENT_SPECIFIC_HOOKS = array("\x64\x61\x73\x68\142\x6f\x61\x72\x64\137\167\151\x64\147\x65\x74" => array("\x6e\145\x74\167\157\162\x6b" => "\x77\x70\x5f\156\145\x74\167\157\162\153\x5f\x64\x61\x73\x68\x62\x6f\141\162\x64\137\x73\145\164\x75\x70", "\x73\164\141\x6e\144\141\x6c\157\x6e\145" => "\167\160\x5f\144\x61\x73\x68\142\157\141\x72\144\x5f\x73\x65\x74\x75\x70"), "\x61\144\155\x69\x6e\137\156\157\164\x69\x63\145" => array("\x6e\145\164\167\x6f\162\x6b" => "\156\145\x74\167\x6f\x72\153\x5f\141\144\155\151\156\x5f\156\x6f\x74\151\143\x65\163", "\163\164\141\156\x64\x61\x6c\x6f\156\145" => "\141\x64\155\151\x6e\x5f\156\x6f\x74\151\x63\x65\x73"));
    public static function get_constants()
    {
        try {
            $vU = new \ReflectionClass(static::class);
            return $vU->getConstants();
        } catch (\ReflectionException $Wi) {
            return array();
        }
    }
}
