<?php


namespace MoOauthClient\LicenseLibrary;

define("\115\x4f\137\x4c\x49\x43\x45\x4e\123\105\x5f\114\111\102\x52\x41\122\131\137\120\x41\x54\x48", plugin_dir_url(__FILE__));
use MoOauthClient\LicenseLibrary\Classes\Mo_License_Constants;
use MoOauthClient\LicenseLibrary\Classes\Mo_License_Dao;
use MoOauthClient\LicenseLibrary\Exceptions\Mo_License_Grace_Expired_Exception;
use MoOauthClient\LicenseLibrary\Exceptions\Mo_License_Invalid_Expiry_Date_Exception;
use MoOauthClient\LicenseLibrary\Exceptions\Mo_License_Missing_Customer_Email_Exception;
use MoOauthClient\LicenseLibrary\Exceptions\Mo_License_Missing_License_Key_Exception;
use MoOauthClient\LicenseLibrary\Exceptions\Mo_License_Missing_Or_Invalid_Customer_Key_Exception;
use MoOauthClient\LicenseLibrary\Exceptions\Mo_License_Unknown_Error_Exception;
use MoOauthClient\LicenseLibrary\Utils\Mo_License_Actions_Utility;
use MoOauthClient\LicenseLibrary\Utils\Mo_License_Service_Utility;
if (defined("\101\x42\x53\120\x41\124\110")) {
    goto NK9;
}
exit;
NK9:
class Mo_License_Service
{
    public static function is_license_expired()
    {
        try {
            Mo_License_Service_Utility::check_customer_login_and_license();
            $gG = Mo_License_Service_Utility::is_license_grace_expired();
        } catch (Mo_License_Grace_Expired_Exception $Wi) {
            return Mo_License_Service_Utility::return_true_with_code($Wi::MESSAGE);
        } catch (Mo_License_Invalid_Expiry_Date_Exception $Wi) {
            return Mo_License_Service_Utility::return_true_with_code($Wi::MESSAGE);
        } catch (Mo_License_Missing_License_Key_Exception $Wi) {
            return Mo_License_Service_Utility::return_true_with_code($Wi::MESSAGE);
        } catch (Mo_License_Missing_Customer_Email_Exception $Wi) {
            return Mo_License_Service_Utility::return_true_with_code($Wi::MESSAGE);
        } catch (Mo_License_Missing_Or_Invalid_Customer_Key_Exception $Wi) {
            return Mo_License_Service_Utility::return_true_with_code($Wi::MESSAGE);
        } catch (Mo_License_Unknown_Error_Exception $Wi) {
            return Mo_License_Service_Utility::return_true_with_code($Wi::MESSAGE);
        }
        return Mo_License_Service_Utility::return_false_with_code($gG["\103\117\x44\x45"]);
    }
    public static function is_customer_license_verified()
    {
        try {
            Mo_License_Service_Utility::check_customer_login_and_license();
        } catch (Mo_License_Missing_Customer_Email_Exception $Wi) {
            return false;
        } catch (Mo_License_Missing_Or_Invalid_Customer_Key_Exception $Wi) {
            return false;
        } catch (Mo_License_Missing_License_Key_Exception $Wi) {
            return false;
        }
        return true;
    }
    public static function is_customer_logged_into_plugin()
    {
        try {
            Mo_License_Service_Utility::check_customer_login();
        } catch (Mo_License_Missing_Customer_Email_Exception $Wi) {
            return false;
        } catch (Mo_License_Missing_Or_Invalid_Customer_Key_Exception $Wi) {
            return false;
        }
        return true;
    }
    public static function get_html_disabled_status($oF = true)
    {
        if ($oF) {
            goto Hdj;
        }
        $PD = self::is_customer_license_verified();
        goto fZb;
        Hdj:
        $Iz = self::is_license_expired();
        $PD = !$Iz["\123\124\x41\124\125\x53"];
        fZb:
        if (!(false === $PD)) {
            goto rX_;
        }
        return "\144\x69\x73\x61\x62\154\145\x64";
        rX_:
        return '';
    }
    public static function refresh_license_expiry()
    {
        $qk = Mo_License_Actions_Utility::fetch_license_expiry_date();
        if (!$qk) {
            goto p8r;
        }
        self::update_license_expiry($qk);
        return $qk;
        p8r:
        return false;
    }
    public static function mo_check_admin_referer($tm = -1, $BL = "\x5f\167\x70\156\x6f\156\143\145", $oF = true)
    {
        $wt = check_admin_referer($tm, $BL);
        $Iz = false;
        if ($oF) {
            goto W7r;
        }
        $PD = self::is_customer_license_verified();
        goto jyy;
        W7r:
        $Iz = self::is_license_expired();
        $PD = !$Iz["\x53\x54\101\x54\x55\123"];
        jyy:
        if (!(!$PD || !$wt)) {
            goto n8O;
        }
        wp_die(esc_html(Mo_License_Constants::ADMIN_ERROR_MESSAGE));
        n8O:
        return true;
    }
    public static function mo_check_ajax_referer($RB = -1, $BL = false, $Uy = true, $oF = true)
    {
        if ($oF) {
            goto DMd;
        }
        $PD = self::is_customer_license_verified();
        goto yX0;
        DMd:
        $Iz = self::is_license_expired();
        $PD = !$Iz["\x53\124\x41\x54\x55\123"];
        yX0:
        $Cy = check_ajax_referer($RB, $BL, $Uy);
        if (!(!$PD || !$Cy)) {
            goto APa;
        }
        wp_send_json_error(array("\155\145\163\163\141\x67\x65" => "\111\156\x76\x61\154\151\x64\x20\x4c\x69\143\x65\156\x73\x65\40\x4b\x65\x79\x2e"), 403);
        exit;
        APa:
        wp_send_json_success(array("\155\145\163\163\141\147\145" => "\x52\x65\x66\145\162\145\162\x20\166\x65\162\x69\146\151\145\144\40\x73\x75\143\x63\x65\163\163\146\x75\154\x6c\x79\x2e"), 200);
    }
    public static function get_expiry_remaining_days($qk)
    {
        $UB = strtotime($qk);
        $Qa = strtotime(gmdate("\x59\55\155\55\x64"));
        $Oe = $UB - $Qa;
        $YM = floor($Oe / (60 * 60 * 24));
        return $YM;
    }
    public static function get_grace_days_left($qk)
    {
        $YM = self::get_expiry_remaining_days($qk);
        if (!($YM > 0)) {
            goto jKL;
        }
        return false;
        jKL:
        return Mo_License_Config::GRACE_PERIOD_DAYS + $YM;
    }
    public static function get_disable_date($qk)
    {
        return gmdate("\x4d\x20\144\x2c\x20\x59", strtotime($qk . "\53" . Mo_License_Config::GRACE_PERIOD_DAYS . "\x20\x64\x61\x79\x73"));
    }
    public static function get_expiry_date()
    {
        $RS = Mo_License_Service_Utility::mo_decrypt_data(Mo_License_Dao::mo_get_option(Mo_License_Constants::LICENSE_EXPIRY_DATE_OPTION));
        if ($RS) {
            goto pvT;
        }
        $RS = Mo_License_Actions_Utility::fetch_license_expiry_date();
        if ($RS) {
            goto DZA;
        }
        $RS = Mo_License_Constants::EPOCH_DATE;
        DZA:
        self::update_license_expiry($RS);
        pvT:
        return $RS;
    }
    public static function get_formatted_license_expiry_date($xU)
    {
        try {
            $tf = new \DateTime($xU);
            $UD = $tf->getTimestamp();
            $xU = gmdate("\106\40\152\54\40\x59", $UD);
            return $xU;
        } catch (\Exception $Wi) {
            return $xU;
        }
    }
    public static function is_customer_license_valid($LU = false, $oF = true)
    {
        if ($oF) {
            goto rI_;
        }
        $PD = self::is_customer_license_verified();
        goto rVI;
        rI_:
        $Iz = self::is_license_expired();
        $PD = !$Iz["\x53\124\x41\124\x55\123"];
        rVI:
        if (!(true === $PD)) {
            goto aBG;
        }
        return $LU ? '' : true;
        aBG:
        return $LU ? "\144\151\163\141\x62\154\145\144" : false;
    }
    public static function update_license_expiry($qk)
    {
        Mo_License_Dao::mo_update_option(Mo_License_Constants::LICENSE_EXPIRY_DATE_OPTION, Mo_License_Service_Utility::mo_encrypt_data(self::get_formatted_license_expiry_date($qk)));
        $M9 = self::is_license_expired();
        if (true === $M9["\123\x54\x41\124\125\123"]) {
            goto whW;
        }
        if (!Mo_License_Dao::mo_get_option(Mo_License_Constants::LICENSE_EXPIRED_OPTION)) {
            goto lEX;
        }
        self::reset_license_values();
        lEX:
        goto Hq8;
        whW:
        Mo_License_Dao::mo_update_option(Mo_License_Constants::LICENSE_EXPIRED_OPTION, true);
        Hq8:
    }
    public static function reset_license_values()
    {
        $CT = Mo_License_Constants::get_constants();
        foreach ($CT as $cW => $LQ) {
            if (!(strpos($cW, "\x4f\120\x54\111\x4f\116") !== false)) {
                goto nJc;
            }
            delete_option($LQ);
            nJc:
            KYu:
        }
        nMP:
    }
}
