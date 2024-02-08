<?php


namespace MoOauthClient\LicenseLibrary\Handlers;

use MoOauthClient\LicenseLibrary\Classes\Mo_License_Constants;
use MoOauthClient\LicenseLibrary\Classes\Mo_License_Dao;
use MoOauthClient\LicenseLibrary\Mo_License_Config;
use MoOauthClient\LicenseLibrary\Mo_License_Service;
use MoOauthClient\LicenseLibrary\Utils\Mo_License_Actions_Utility;
use MoOauthClient\LicenseLibrary\Views\Mo_License_Notice_Views;
if (defined("\101\102\x53\x50\x41\x54\x48")) {
    goto nzS;
}
exit;
nzS:
class Mo_License_Actions_Handler
{
    private $license_expiry_date;
    public function __construct($RS)
    {
        $this->license_expiry_date = $RS;
    }
    public function run_license_cron()
    {
        if (Mo_License_Service::is_customer_license_verified()) {
            goto MIT;
        }
        return;
        MIT:
        $Rx = Mo_License_Dao::mo_get_option(Mo_License_Constants::LAST_CHECK_TIME_OPTION);
        if (!$Rx) {
            goto FmV;
        }
        $Rx = intval($Rx);
        if (!(time() - $Rx < 3600 * 24 * Mo_License_Config::LICENSE_CRON_INTERVAL)) {
            goto K0T;
        }
        return;
        K0T:
        FmV:
        $qk = Mo_License_Actions_Utility::fetch_license_expiry_date();
        if (!$qk) {
            goto Cib;
        }
        Mo_License_Service::update_license_expiry($qk);
        Mo_License_Dao::mo_update_option(Mo_License_Constants::LAST_CHECK_TIME_OPTION, time());
        Cib:
    }
    public function dismiss_admin_license_notice()
    {
        if (!(current_user_can("\155\x61\x6e\141\147\x65\x5f\157\x70\x74\151\x6f\156\163") && !empty($_POST["\x6f\160\164\x69\157\x6e"]) && Mo_License_Constants::ADMIN_NOTICE_DISMISS_ID === $_POST["\x6f\160\x74\151\x6f\156"] && check_admin_referer(Mo_License_Constants::ADMIN_NOTICE_DISMISS_ID))) {
            goto JCi;
        }
        $YM = Mo_License_Service::get_expiry_remaining_days($this->license_expiry_date);
        Mo_License_Dao::mo_update_option(Mo_License_Constants::EXPIRY_NOTICE_CLOSE_OPTION, $YM);
        JCi:
    }
    public function refresh_admin_widget_expiry()
    {
        if (!(current_user_can("\x6d\141\156\x61\x67\145\137\x6f\160\164\x69\x6f\156\x73") && !empty($_POST["\x6f\x70\164\151\x6f\x6e"]) && Mo_License_Constants::DASHBOARD_WIDGET_REFRESH_ID === $_POST["\x6f\x70\164\x69\x6f\x6e"] && check_admin_referer(Mo_License_Constants::DASHBOARD_WIDGET_REFRESH_ID))) {
            goto KDN;
        }
        Mo_License_Service::refresh_license_expiry();
        KDN:
    }
}
