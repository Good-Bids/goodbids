<?php


namespace MoOauthClient\Backup;

use MoOauthClient\App;
use MoOauthClient\Config;
class BackupHandler
{
    private $plugin_config;
    private $apps_list;
    public static function restore_settings($Lb = '')
    {
        if (!(!is_array($Lb) || empty($Lb))) {
            goto os;
        }
        return false;
        os:
        $FO = false;
        $uu = isset($Lb["\160\x6c\165\147\x69\156\137\x63\157\x6e\x66\151\147"]) ? $Lb["\x70\154\x75\147\x69\156\x5f\143\x6f\156\146\x69\147"] : false;
        $UJ = isset($Lb["\141\160\160\137\143\157\156\x66\x69\147\163"]) ? $Lb["\141\160\160\x5f\143\x6f\156\x66\151\x67\x73"] : false;
        if (!$uu) {
            goto O8;
        }
        $FO = self::restore_plugin_config($uu);
        O8:
        if (!$UJ) {
            goto mC;
        }
        return $FO && self::restore_apps_config($UJ);
        mC:
        return false;
    }
    private static function restore_plugin_config($uu)
    {
        global $Uj;
        if (!empty($uu)) {
            goto QO;
        }
        return false;
        QO:
        $Kn = new Config($uu);
        if (empty($Kn)) {
            goto l4;
        }
        $Uj->mo_oauth_client_update_option("\x6d\157\137\157\141\x75\x74\x68\x5f\x63\x6c\151\x65\x6e\x74\x5f\143\x6f\156\146\x69\147", $Kn);
        return true;
        l4:
        return false;
    }
    private static function restore_apps_config($UJ)
    {
        global $Uj;
        if (!(!is_array($UJ) && empty($UJ))) {
            goto F9;
        }
        return false;
        F9:
        $Z4 = [];
        foreach ($UJ as $gR => $Wh) {
            $Fr = new App($Wh);
            $Fr->set_app_name($gR);
            $Z4[$gR] = $Fr;
            aV:
        }
        Yb:
        $Uj->mo_oauth_client_update_option("\x6d\x6f\x5f\x6f\x61\165\x74\150\x5f\x61\x70\x70\x73\x5f\154\x69\163\x74", $Z4);
        return true;
    }
    public static function get_backup_json()
    {
        global $Uj;
        $W_ = $Uj->export_plugin_config();
        return json_encode($W_, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }
}
