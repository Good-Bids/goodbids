<?php


abstract class MoOAuthClientBasicEnum
{
    private static $constCacheArray = NULL;
    public static function getConstants()
    {
        if (!(self::$constCacheArray == NULL)) {
            goto oM2;
        }
        self::$constCacheArray = [];
        oM2:
        $o2 = get_called_class();
        if (array_key_exists($o2, self::$constCacheArray)) {
            goto pIP;
        }
        $Qm = new ReflectionClass($o2);
        self::$constCacheArray[$o2] = $Qm->getConstants();
        pIP:
        return self::$constCacheArray[$o2];
    }
    public static function isValidName($uQ, $le = false)
    {
        $tG = self::getConstants();
        if (!$le) {
            goto hit;
        }
        return array_key_exists($uQ, $tG);
        hit:
        $nR = array_map("\163\164\x72\164\x6f\x6c\157\167\x65\x72", array_keys($tG));
        return in_array(strtolower($uQ), $nR);
    }
    public static function isValidValue($t_, $le = true)
    {
        $EI = array_values(self::getConstants());
        return in_array($t_, $EI, $le);
    }
}
