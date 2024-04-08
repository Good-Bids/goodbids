<?php


namespace MoOauthClient\Backup;

use MoOauthClient\App;
class EnvVarResolver
{
    public static function resolve_var($Mr, $t_)
    {
        switch ($Mr) {
            case "\155\x6f\x5f\x6f\141\165\x74\x68\137\x61\160\x70\163\137\154\151\x73\164":
                $t_ = self::resolve_apps_list($t_);
                goto oI;
            default:
                goto oI;
        }
        PD:
        oI:
        return $t_;
    }
    private static function resolve_apps_list($t_)
    {
        if (!is_array($t_)) {
            goto wB;
        }
        return $t_;
        wB:
        $t_ = json_decode($t_, true);
        if (!(json_last_error() !== JSON_ERROR_NONE)) {
            goto jD;
        }
        return [];
        jD:
        $Un = [];
        foreach ($t_ as $gR => $Wh) {
            if (!$Wh instanceof App) {
                goto vA;
            }
            $Un[$gR] = $Wh;
            goto EN;
            vA:
            if (!(!isset($Wh["\143\154\x69\145\156\164\137\151\x64"]) || empty($Wh["\143\154\151\145\156\x74\x5f\151\144"]))) {
                goto qC;
            }
            $Wh["\143\154\151\x65\x6e\x74\x5f\151\x64"] = isset($Wh["\143\154\x69\145\156\x74\151\144"]) ? $Wh["\143\154\151\x65\x6e\164\x69\x64"] : '';
            qC:
            if (!(!isset($Wh["\x63\154\151\x65\x6e\164\x5f\163\145\143\x72\x65\164"]) || empty($Wh["\143\x6c\x69\x65\x6e\x74\137\163\x65\x63\162\x65\x74"]))) {
                goto JW;
            }
            $Wh["\x63\154\x69\145\x6e\164\137\163\145\x63\162\x65\164"] = isset($Wh["\143\154\151\145\x6e\164\x73\145\143\x72\x65\x74"]) ? $Wh["\x63\x6c\151\x65\x6e\164\x73\145\x63\162\x65\164"] : '';
            JW:
            unset($Wh["\143\154\151\145\156\164\151\144"]);
            unset($Wh["\143\154\x69\145\x6e\x74\x73\145\x63\162\x65\164"]);
            $Fr = new App();
            $Fr->migrate_app($Wh, $gR);
            $Un[$gR] = $Fr;
            EN:
        }
        qO:
        return $Un;
    }
}
