<?php


namespace MoOauthClient\Premium;

use MoOauthClient\Standard\StandardSettings;
use MoOauthClient\Premium\AppSettings;
use MoOauthClient\Premium\SignInSettingsSettings;
class PremiumSettings
{
    private $standard_settings;
    public function __construct()
    {
        $this->standard_settings = new StandardSettings();
        add_action("\141\144\x6d\x69\156\x5f\x69\x6e\x69\164", array($this, "\x6d\x6f\x5f\157\x61\x75\164\150\137\x63\154\x69\x65\156\x74\137\160\x72\x65\155\151\x75\155\x5f\x73\x65\164\x74\x69\156\x67\163"));
    }
    public function mo_oauth_client_premium_settings()
    {
        $fe = new SignInSettingsSettings();
        $cy = new AppSettings();
        $cy->save_app_settings();
        $cy->save_advanced_grant_settings();
        $fe->mo_oauth_save_settings();
        if (!is_multisite()) {
            goto jW;
        }
        $UT = new MultisiteSettings();
        $UT->save_multisite_settings();
        jW:
    }
}
