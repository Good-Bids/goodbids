<?php


namespace MoOauthClient\Backup;

use MoOauthClient\App;
use MoOauthClient\Config;
class BackupHandler
{
    private $plugin_config;
    private $apps_list;
    public static function restore_settings($xW = '')
    {
        if (!(!is_array($xW) || empty($xW))) {
            goto uc;
        }
        return false;
        uc:
        $uw = false;
        $uB = isset($xW["\160\x6c\165\x67\151\x6e\x5f\143\x6f\x6e\146\151\147"]) ? $xW["\160\x6c\x75\x67\151\x6e\137\143\x6f\156\x66\x69\x67"] : false;
        $Zo = isset($xW["\x61\x70\160\x5f\143\x6f\x6e\x66\151\147\163"]) ? $xW["\141\160\160\x5f\143\x6f\156\146\x69\147\x73"] : false;
        if (!$uB) {
            goto sU;
        }
        $uw = self::restore_plugin_config($uB);
        sU:
        if (!$Zo) {
            goto ej;
        }
        return $uw && self::restore_apps_config($Zo);
        ej:
        return false;
    }
    private static function restore_plugin_config($uB)
    {
        global $Yh;
        if (!empty($uB)) {
            goto Xo;
        }
        return false;
        Xo:
        $Wb = new Config($uB);
        if (empty($Wb)) {
            goto Ec;
        }
        $Yh->mo_oauth_client_update_option("\155\x6f\x5f\157\x61\165\x74\150\137\143\x6c\151\145\x6e\x74\x5f\143\157\x6e\x66\151\x67", $Wb);
        return true;
        Ec:
        return false;
    }
    private static function restore_apps_config($Zo)
    {
        global $Yh;
        if (!(!is_array($Zo) && empty($Zo))) {
            goto yo;
        }
        return false;
        yo:
        $O7 = [];
        foreach ($Zo as $zl => $KY) {
            $F8 = new App($KY);
            $F8->set_app_name($zl);
            $O7[$zl] = $F8;
            kU:
        }
        TM:
        $Yh->mo_oauth_client_update_option("\155\157\137\x6f\x61\x75\x74\150\137\x61\160\x70\163\137\154\x69\163\x74", $O7);
        return true;
    }
    public static function get_backup_json()
    {
        global $Yh;
        $OO = $Yh->export_plugin_config();
        return json_encode($OO, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }
}
