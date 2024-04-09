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
        add_action("\141\x64\x6d\x69\156\137\x69\x6e\x69\164", array($this, "\155\x6f\137\157\141\165\x74\x68\x5f\143\x6c\151\x65\156\x74\x5f\160\x72\x65\155\x69\x75\155\x5f\x73\x65\164\164\151\156\147\x73"));
    }
    public function mo_oauth_client_premium_settings()
    {
        $VR = new SignInSettingsSettings();
        $f2 = new AppSettings();
        $f2->save_app_settings();
        $f2->save_advanced_grant_settings();
        $VR->mo_oauth_save_settings();
        if (!is_multisite()) {
            goto Lf;
        }
        $Y8 = new MultisiteSettings();
        $Y8->save_multisite_settings();
        Lf:
    }
}
