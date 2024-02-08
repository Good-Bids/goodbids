<?php


namespace MoOauthClient\Enterprise;

use MoOauthClient\App;
use MoOauthClient\Premium\AppSettings as PremiumAppSettings;
class AppSettings extends PremiumAppSettings
{
    public function save_grant_settings($post, $eL)
    {
        $eL = parent::save_grant_settings($post, $eL);
        global $Yh;
        $eL["\160\x6b\143\x65\137\146\x6c\157\167"] = isset($post["\160\153\x63\145\137\x66\x6c\157\167"]) ? 1 : 0;
        $eL["\x70\x6b\x63\145\x5f\x63\x6c\x69\145\x6e\x74\137\x73\145\143\162\145\164"] = isset($post["\160\x6b\x63\145\x5f\x63\x6c\x69\x65\x6e\x74\137\x73\145\143\x72\x65\164"]) ? 1 : 0;
        return $eL;
    }
}
