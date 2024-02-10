<?php


namespace MoOauthClient\Backup;

use MoOauthClient\App;
class EnvVarResolver
{
    public static function resolve_var($cW, $LQ)
    {
        switch ($cW) {
            case "\x6d\157\137\x6f\141\x75\x74\150\x5f\x61\160\x70\163\x5f\154\x69\163\x74":
                $LQ = self::resolve_apps_list($LQ);
                goto cN;
            default:
                goto cN;
        }
        On:
        cN:
        return $LQ;
    }
    private static function resolve_apps_list($LQ)
    {
        if (!is_array($LQ)) {
            goto EI;
        }
        return $LQ;
        EI:
        $LQ = json_decode($LQ, true);
        if (!(json_last_error() !== JSON_ERROR_NONE)) {
            goto Cc;
        }
        return [];
        Cc:
        $Tq = [];
        foreach ($LQ as $zl => $KY) {
            if (!$KY instanceof App) {
                goto Xx;
            }
            $Tq[$zl] = $KY;
            goto K3;
            Xx:
            if (!(!isset($KY["\x63\x6c\x69\x65\x6e\164\137\151\144"]) || empty($KY["\143\154\x69\x65\156\x74\x5f\x69\x64"]))) {
                goto J7;
            }
            $KY["\143\154\x69\145\156\164\137\151\x64"] = isset($KY["\x63\154\151\145\x6e\x74\x69\144"]) ? $KY["\143\154\151\x65\x6e\x74\151\144"] : '';
            J7:
            if (!(!isset($KY["\143\154\151\x65\156\x74\x5f\163\x65\143\x72\145\164"]) || empty($KY["\143\x6c\151\145\x6e\x74\x5f\163\145\143\162\x65\x74"]))) {
                goto ed;
            }
            $KY["\143\x6c\x69\145\156\x74\137\163\x65\x63\x72\145\x74"] = isset($KY["\x63\154\x69\x65\x6e\x74\x73\145\x63\x72\145\164"]) ? $KY["\x63\154\x69\x65\156\164\163\145\x63\162\145\x74"] : '';
            ed:
            unset($KY["\143\154\151\x65\x6e\164\151\144"]);
            unset($KY["\143\x6c\x69\145\x6e\164\x73\145\x63\162\145\164"]);
            $F8 = new App();
            $F8->migrate_app($KY, $zl);
            $Tq[$zl] = $F8;
            K3:
        }
        MY:
        return $Tq;
    }
}
