<?php


namespace MoOauthClient\LicenseLibrary\Utils;

use MoOauthClient\LicenseLibrary\Classes\Mo_AESEncryption;
use MoOauthClient\LicenseLibrary\Classes\Mo_License_Dao;
use MoOauthClient\LicenseLibrary\Exceptions\Mo_License_Grace_Expired_Exception;
use MoOauthClient\LicenseLibrary\Exceptions\Mo_License_Invalid_Expiry_Date_Exception;
use MoOauthClient\LicenseLibrary\Exceptions\Mo_License_Missing_Customer_Email_Exception;
use MoOauthClient\LicenseLibrary\Exceptions\Mo_License_Missing_License_Key_Exception;
use MoOauthClient\LicenseLibrary\Exceptions\Mo_License_Missing_Or_Invalid_Customer_Key_Exception;
use MoOauthClient\LicenseLibrary\Exceptions\Mo_License_Unknown_Error_Exception;
use MoOauthClient\LicenseLibrary\Classes\Mo_License_Constants;
use MoOauthClient\LicenseLibrary\Mo_License_Config;
use MoOauthClient\LicenseLibrary\Mo_License_Service;
if (defined("\101\102\x53\x50\x41\124\x48")) {
    goto hxT;
}
exit;
hxT:
class Mo_License_Service_Utility
{
    public static function check_customer_login()
    {
        $Mv = Mo_License_Dao::mo_get_option(Mo_License_Config::CUSTOMER_EMAIL_OPTION);
        $oe = Mo_License_Dao::mo_get_option(Mo_License_Config::CUSTOMER_KEY_OPTION);
        if (!$Mv) {
            goto Sw_;
        }
        if (!$oe || !is_numeric(trim($oe))) {
            goto iJD;
        }
        goto g6I;
        Sw_:
        throw new Mo_License_Missing_Customer_Email_Exception();
        goto g6I;
        iJD:
        throw new Mo_License_Missing_Or_Invalid_Customer_Key_Exception();
        g6I:
    }
    public static function check_customer_login_and_license()
    {
        self::check_customer_login();
        $Po = Mo_License_Dao::mo_get_option(Mo_License_Config::LICENSE_KEY_OPTION);
        if ($Po) {
            goto Rh9;
        }
        throw new Mo_License_Missing_License_Key_Exception();
        Rh9:
    }
    public static function is_license_grace_expired()
    {
        $qk = self::mo_decrypt_data(Mo_License_Dao::mo_get_option(Mo_License_Constants::LICENSE_EXPIRY_DATE_OPTION));
        if ($qk) {
            goto cuw;
        }
        throw new Mo_License_Invalid_Expiry_Date_Exception();
        cuw:
        try {
            $Kw = gmdate("\x59\x2d\x6d\x2d\x64", strtotime($qk));
            $bT = strtotime("\x2b" . Mo_License_Config::GRACE_PERIOD_DAYS . "\40\144\x61\x79\163", strtotime($qk));
            $b3 = gmdate("\x59\x2d\155\55\144", $bT);
            $RD = new \DateTime();
            $RD = $RD->format("\131\55\155\55\x64");
        } catch (\Exception $Wi) {
            throw new Mo_License_Unknown_Error_Exception();
        }
        if ($RD > $b3) {
            goto M03;
        }
        if ($RD > $Kw) {
            goto z5V;
        }
        goto Wh6;
        M03:
        throw new Mo_License_Grace_Expired_Exception();
        goto Wh6;
        z5V:
        return self::return_false_with_code("\x4c\111\x43\x45\116\x53\105\x5f\111\x4e\137\x47\x52\101\x43\105");
        Wh6:
        return self::return_false_with_code("\x4c\x49\x43\105\116\x53\105\x5f\x56\x41\x4c\x49\104");
    }
    public static function return_true_with_code($g0)
    {
        return array("\x53\124\x41\x54\125\123" => true, "\x43\117\104\x45" => $g0);
    }
    public static function return_false_with_code($g0)
    {
        return array("\x53\124\101\124\125\123" => false, "\x43\117\x44\x45" => $g0);
    }
    public static function mo_decrypt_data($uD)
    {
        $cW = Mo_License_Dao::mo_get_option(Mo_License_Config::CUSTOMER_TOKEN_OPTION);
        $Du = Mo_AESEncryption::decrypt_data($uD, $cW);
        return $Du;
    }
    public static function mo_encrypt_data($uD)
    {
        $cW = Mo_License_Dao::mo_get_option(Mo_License_Config::CUSTOMER_TOKEN_OPTION);
        $Il = Mo_AESEncryption::encrypt_data($uD, $cW);
        return $Il;
    }
}
