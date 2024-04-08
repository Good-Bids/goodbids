<?php


namespace MoOauthClient\Enterprise;

use MoOauthClient\Premium\PremiumSettings;
use MoOauthClient\Enterprise\SignInSettingsSettings;
use MoOauthClient\Enterprise\AppSettings;
use MoOauthClient\Enterprise\UserAnalyticsDBOps as DBOps;
class EnterpriseSettings
{
    private $premium_settings;
    public function __construct()
    {
        $this->premium_settings = new PremiumSettings();
        add_action("\x61\x64\x6d\151\156\x5f\151\x6e\x69\x74", array($this, "\155\157\x5f\157\x61\x75\164\150\137\143\x6c\151\145\x6e\164\137\145\156\x74\x65\162\160\162\151\x73\x65\x5f\163\145\164\164\151\x6e\147\163"));
    }
    public function mo_oauth_client_enterprise_settings()
    {
        $fe = new SignInSettingsSettings();
        $cy = new AppSettings();
        $fe->mo_oauth_save_settings();
        $cy->save_advanced_grant_settings();
        if (!(isset($_POST["\155\x6f\x5f\x77\x70\156\x73\x5f\x6d\141\x6e\x75\141\x6c\x5f\143\154\145\x61\162\137\x6e\x6f\x6e\143\x65"]) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST["\155\x6f\137\167\160\156\163\x5f\x6d\x61\156\165\x61\154\x5f\x63\154\145\x61\162\x5f\156\157\156\143\x65"])), "\x6d\x6f\x5f\x77\x70\156\x73\x5f\155\x61\156\x75\141\x6c\137\x63\x6c\x65\x61\x72") && isset($_POST[\MoOAuthConstants::OPTION]) && "\x6d\x6f\x5f\x77\160\156\163\x5f\x6d\141\x6e\165\141\x6c\x5f\x63\x6c\x65\x61\162" === $_POST[\MoOAuthConstants::OPTION])) {
            goto Vm;
        }
        $yT = new DBOps();
        $yT->drop_table();
        Vm:
    }
}
