<?php


namespace MoOauthClient\Enterprise;

use MoOauthClient\App;
use MoOauthClient\Premium\AppSettings as PremiumAppSettings;
class AppSettings extends PremiumAppSettings
{
    public function save_grant_settings($post, $Oj)
    {
        $Oj = parent::save_grant_settings($post, $Oj);
        global $Uj;
        $Oj["\160\x6b\143\145\137\146\x6c\x6f\x77"] = isset($post["\x70\x6b\x63\145\137\x66\154\157\x77"]) ? 1 : 0;
        $Oj["\x70\x6b\143\145\137\x63\x6c\151\x65\156\x74\137\163\x65\x63\162\145\x74"] = isset($post["\x70\x6b\143\145\x5f\143\154\x69\145\x6e\164\137\x73\145\143\162\x65\164"]) ? 1 : 0;
        return $Oj;
    }
}
