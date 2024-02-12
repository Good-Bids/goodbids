<?php


namespace MoOauthClient\LicenseLibrary\Utils;

use MoOauthClient\LicenseLibrary\Classes\Mo_License_Constants;
use MoOauthClient\LicenseLibrary\Classes\Mo_License_Dao;
use MoOauthClient\LicenseLibrary\Mo_License_Config;
use MoOauthClient\LicenseLibrary\Mo_License_Service;
if (defined("\x41\102\123\120\101\x54\x48")) {
    goto eI8;
}
exit;
eI8:
class Mo_License_View_Utility
{
    public static function get_expiry_admin_notice_class($YM)
    {
        if ($YM > 10) {
            goto lgf;
        }
        if ($YM <= 10) {
            goto JPR;
        }
        goto Qv2;
        lgf:
        return "\x6e\157\164\x69\143\x65\x2d\x77\x61\162\156\151\156\147";
        goto Qv2;
        JPR:
        return "\156\x6f\x74\x69\143\x65\55\x65\x72\x72\157\x72";
        Qv2:
        return '';
    }
    public static function get_admin_notice_html($lu, $nU)
    {
        $yw = Mo_License_Config::$notice_html[$lu];
        $yw["\143\x6f\156\164\x65\156\164"] = strtr($yw["\143\157\x6e\x74\145\x6e\x74"], $nU);
        return $yw;
    }
    public static function get_notice_day_key($YM)
    {
        if ($YM <= 60 && $YM > 30) {
            goto eRK;
        }
        if ($YM <= 30 && $YM > 10) {
            goto nxS;
        }
        if ($YM <= 10 && $YM >= 0) {
            goto YyK;
        }
        if ($YM < 0 && $YM >= -Mo_License_Config::GRACE_PERIOD_DAYS) {
            goto Uad;
        }
        if ($YM < -Mo_License_Config::GRACE_PERIOD_DAYS) {
            goto lQv;
        }
        goto bq2;
        eRK:
        return Mo_License_Constants::EXPIRY_IN_30_TO_60_DAYS;
        goto bq2;
        nxS:
        return Mo_License_Constants::EXPIRY_IN_10_TO_30_DAYS;
        goto bq2;
        YyK:
        return Mo_License_Constants::EXPIRY_IN_10_DAYS;
        goto bq2;
        Uad:
        return Mo_License_Constants::GRACE_PERIOD_STARTED;
        goto bq2;
        lQv:
        return Mo_License_Constants::GRACE_PERIOD_EXPIRED;
        bq2:
        return false;
    }
    public static function get_widget_notice($nU)
    {
        $UI = '';
        $Iz = Mo_License_Service::is_license_expired();
        if (true === $Iz["\x53\x54\101\124\125\x53"]) {
            goto LP4;
        }
        if (false === $Iz["\x53\x54\x41\x54\x55\x53"] && "\x4c\x49\103\105\x4e\x53\105\x5f\111\x4e\x5f\107\122\x41\x43\105" === $Iz["\103\117\104\x45"]) {
            goto r1O;
        }
        if ($nU["\x23\43\x72\145\155\141\151\156\x69\x6e\147\137\144\141\171\163\43\43"] < 60) {
            goto ZDQ;
        }
        goto jtF;
        LP4:
        $UI = "\x59\157\x75\x72\40\x70\x6c\x75\147\151\156\40\154\151\x63\145\156\x73\145\40\150\141\163\40\145\170\160\151\162\145\144\x20\141\156\x64\40\x74\x68\145\x20\x70\154\x75\147\x69\156\40\150\141\x73\40\163\x74\157\x70\x70\x65\x64\x20\167\x6f\x72\x6b\x69\156\x67\x2e\40\x50\x6c\x65\x61\163\x65\x20\x3c\141\40\150\162\x65\x66\x3d\x22" . Mo_License_Config::RENEWAL_FAQ . "\42\40\164\141\162\147\x65\164\75\x22\x5f\142\x6c\141\x6e\153\x22\x3e\162\x65\156\145\x77\40\171\157\x75\x72\40\x6c\x69\143\x65\x6e\163\x65\x3c\x2f\141\76\x20\151\155\155\x65\x64\x69\x61\164\x65\x6c\171\x2e";
        goto jtF;
        r1O:
        $UI = "\x59\x6f\x75\40\141\162\x65\40\x63\165\162\x72\x65\x6e\164\154\x79\40\157\x6e\x20\147\162\x61\143\145\x20\160\145\x72\151\x6f\144\x20\x66\157\162\x20\x72\145\156\x65\167\x61\154\x2e\x20" . esc_html($nU["\x23\43\x67\162\141\143\x65\137\144\x61\x79\x73\137\154\145\x66\x74\43\x23"]) . "\x20\144\x61\x79\x73\x20\x6c\x65\x66\164\40\142\x65\146\x6f\x72\145\40\123\x53\x4f\40\151\x73\40\x64\151\163\141\x62\x6c\x65\144\x20\x6f\x6e\x20\171\157\x75\x72\40\x73\151\x74\x65\x2e";
        goto jtF;
        ZDQ:
        $UI = "\x59\x6f\x75\x72\40\x70\154\x75\x67\x69\x6e\x20\154\151\143\x65\x6e\163\x65\40\x69\x73\x20\147\157\x69\x6e\147\40\x74\157\x20\145\x78\160\x69\x72\145\40\151\156\x20" . esc_html($nU["\x23\43\x72\x65\x6d\x61\x69\156\x69\156\147\137\x64\x61\171\163\43\43"]) . "\x20\144\x61\171\x73";
        jtF:
        return $UI;
    }
    public static function show_expiry_notice($YM)
    {
        $Gb = Mo_License_Dao::mo_get_option(Mo_License_Constants::EXPIRY_NOTICE_CLOSE_OPTION);
        if (!isset($YM) || $YM > 60) {
            goto pjX;
        }
        if ($YM <= 10) {
            goto Qt7;
        }
        if (!$Gb && $YM <= 60) {
            goto LX2;
        }
        if ($Gb && $Gb > 30 && $YM <= 30) {
            goto TuS;
        }
        goto GgF;
        pjX:
        return false;
        goto GgF;
        Qt7:
        return true;
        goto GgF;
        LX2:
        return true;
        goto GgF;
        TuS:
        return true;
        GgF:
        return false;
    }
}
