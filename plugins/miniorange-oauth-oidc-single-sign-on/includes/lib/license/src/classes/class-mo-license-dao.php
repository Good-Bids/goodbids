<?php


namespace MoOauthClient\LicenseLibrary\Classes;

if (defined("\x41\x42\x53\x50\x41\x54\x48")) {
    goto ArV;
}
exit;
ArV:
class Mo_License_Dao
{
    public static function mo_get_option($XK, $Y1 = false, $JI = true)
    {
        return get_site_option($XK, $Y1, $JI);
    }
    public static function mo_update_option($XK, $LQ)
    {
        return update_site_option($XK, $LQ);
    }
}
