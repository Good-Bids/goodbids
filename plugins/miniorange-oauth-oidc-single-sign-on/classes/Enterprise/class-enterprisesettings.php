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
        add_action("\141\144\x6d\x69\x6e\137\151\156\151\164", array($this, "\x6d\157\137\x6f\x61\x75\x74\x68\137\x63\154\x69\145\x6e\164\137\x65\x6e\164\x65\x72\x70\x72\151\x73\x65\x5f\163\x65\x74\x74\x69\156\147\x73"));
    }
    public function mo_oauth_client_enterprise_settings()
    {
        $VR = new SignInSettingsSettings();
        $f2 = new AppSettings();
        $VR->mo_oauth_save_settings();
        $f2->save_advanced_grant_settings();
        if (!(isset($_POST["\x6d\x6f\137\x77\x70\x6e\163\137\x6d\x61\x6e\165\141\154\137\143\x6c\145\141\162\137\156\157\156\143\x65"]) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST["\x6d\157\x5f\x77\160\156\163\x5f\155\141\x6e\165\x61\x6c\137\143\x6c\145\x61\162\137\x6e\157\x6e\143\145"])), "\x6d\x6f\x5f\x77\x70\156\163\137\x6d\x61\x6e\x75\x61\154\137\x63\x6c\x65\x61\x72") && isset($_POST[\MoOAuthConstants::OPTION]) && "\x6d\157\x5f\x77\x70\156\x73\137\x6d\141\156\165\141\154\x5f\143\154\x65\141\x72" === sanitize_text_field(wp_unslash($_POST[\MoOAuthConstants::OPTION])))) {
            goto xO;
        }
        if (!current_user_can("\141\x64\x6d\151\x6e\151\x73\x74\x72\141\164\x6f\x72")) {
            goto Mg;
        }
        $ja = new DBOps();
        $ja->drop_table();
        Mg:
        xO:
    }
}
