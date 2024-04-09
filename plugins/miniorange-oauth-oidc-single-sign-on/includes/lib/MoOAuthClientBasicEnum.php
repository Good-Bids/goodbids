<?php


abstract class MoOAuthClientBasicEnum
{
    private static $constCacheArray = NULL;
    public static function getConstants()
    {
        if (!(self::$constCacheArray == NULL)) {
            goto BcP;
        }
        self::$constCacheArray = [];
        BcP:
        $JM = get_called_class();
        if (array_key_exists($JM, self::$constCacheArray)) {
            goto KYX;
        }
        $Rs = new ReflectionClass($JM);
        self::$constCacheArray[$JM] = $Rs->getConstants();
        KYX:
        return self::$constCacheArray[$JM];
    }
    public static function isValidName($un, $iu = false)
    {
        $WQ = self::getConstants();
        if (!$iu) {
            goto p3L;
        }
        return array_key_exists($un, $WQ);
        p3L:
        $gv = array_map("\163\x74\162\164\157\154\157\x77\145\162", array_keys($WQ));
        return in_array(strtolower($un), $gv);
    }
    public static function isValidValue($LQ, $iu = true)
    {
        $mR = array_values(self::getConstants());
        return in_array($LQ, $mR, $iu);
    }
}
