<?php


namespace MoOauthClient\GrantTypes;

define("\x4d\x41\124\x48\137\102\x49\107\x49\116\x54\x45\x47\105\122\137\x4d\x4f\x4e\x54\x47\x4f\115\105\x52\131", 0);
define("\x4d\101\124\110\x5f\102\111\x47\111\116\x54\x45\x47\105\122\x5f\x42\x41\122\122\x45\124\x54", 1);
define("\x4d\101\x54\110\x5f\102\111\107\x49\x4e\x54\105\x47\105\122\137\120\x4f\127\105\x52\x4f\x46\x32", 2);
define("\x4d\x41\x54\x48\x5f\102\x49\x47\x49\x4e\124\x45\x47\105\122\137\103\114\101\x53\x53\x49\x43", 3);
define("\x4d\x41\x54\x48\x5f\x42\111\x47\111\116\124\x45\107\x45\122\x5f\116\x4f\116\105", 4);
define("\x4d\x41\x54\x48\x5f\x42\111\107\x49\116\x54\105\x47\105\122\137\126\101\x4c\x55\x45", 0);
define("\115\101\124\110\x5f\x42\x49\107\x49\116\124\105\x47\x45\x52\137\123\111\x47\x4e", 1);
define("\115\x41\x54\110\137\x42\111\x47\111\116\x54\x45\107\105\x52\137\126\x41\122\x49\101\102\114\105", 0);
define("\115\x41\124\x48\x5f\102\111\x47\x49\x4e\x54\x45\x47\105\122\137\x44\101\x54\x41", 1);
define("\115\101\x54\110\137\x42\x49\x47\x49\x4e\x54\105\107\105\122\137\x4d\117\x44\105\137\111\x4e\124\x45\x52\x4e\101\114", 1);
define("\115\101\x54\x48\137\102\111\107\x49\x4e\124\105\x47\x45\x52\137\115\117\104\105\x5f\102\x43\x4d\x41\x54\x48", 2);
define("\x4d\x41\x54\110\x5f\102\x49\107\x49\x4e\124\105\107\x45\x52\x5f\x4d\117\x44\x45\137\x47\115\120", 3);
define("\x4d\101\124\110\137\102\111\107\111\116\124\105\107\105\122\x5f\113\x41\x52\x41\124\123\125\102\101\137\103\125\124\117\x46\x46", 25);
class Math_BigInteger
{
    var $value;
    var $is_negative = false;
    var $precision = -1;
    var $bitmask = false;
    var $hex;
    function __construct($k5 = 0, $gk = 10)
    {
        if (defined("\115\101\x54\x48\x5f\x42\111\x47\111\116\x54\x45\107\105\122\137\x4d\117\104\105")) {
            goto fk;
        }
        switch (true) {
            case extension_loaded("\147\155\x70"):
                define("\x4d\101\x54\110\137\102\x49\107\x49\x4e\x54\x45\x47\105\x52\x5f\x4d\117\x44\105", MATH_BIGINTEGER_MODE_GMP);
                goto dM;
            case extension_loaded("\x62\x63\x6d\141\x74\x68"):
                define("\x4d\x41\x54\110\x5f\102\111\x47\111\x4e\124\x45\107\x45\x52\x5f\115\x4f\x44\x45", MATH_BIGINTEGER_MODE_BCMATH);
                goto dM;
            default:
                define("\x4d\x41\124\110\137\102\x49\x47\111\x4e\x54\x45\107\105\x52\137\115\117\104\x45", MATH_BIGINTEGER_MODE_INTERNAL);
        }
        KN:
        dM:
        fk:
        if (!(extension_loaded("\157\160\145\x6e\163\x73\x6c") && !defined("\115\101\x54\x48\x5f\102\x49\x47\111\x4e\x54\105\x47\105\122\137\x4f\120\x45\x4e\x53\x53\114\137\104\x49\123\101\102\x4c\x45") && !defined("\x4d\101\124\110\x5f\102\x49\x47\x49\x4e\124\105\x47\105\122\137\x4f\x50\105\x4e\123\x53\114\137\x45\x4e\x41\x42\114\105\104"))) {
            goto EZ;
        }
        ob_start();
        @phpinfo();
        $dV = ob_get_contents();
        ob_end_clean();
        preg_match_all("\x23\x4f\160\145\x6e\x53\123\114\x20\50\110\x65\x61\x64\145\x72\x7c\x4c\x69\142\x72\x61\162\171\51\x20\x56\x65\x72\163\151\157\156\50\x2e\x2a\x29\x23\x69\x6d", $dV, $JY);
        $HO = array();
        if (empty($JY[1])) {
            goto Tw;
        }
        $zY = 0;
        pm:
        if (!($zY < count($JY[1]))) {
            goto eE;
        }
        $yI = trim(str_replace("\x3d\76", '', strip_tags($JY[2][$zY])));
        if (!preg_match("\x2f\50\134\144\53\134\56\134\x64\x2b\134\x2e\x5c\144\x2b\x29\x2f\151", $yI, $X3)) {
            goto IW;
        }
        $HO[$JY[1][$zY]] = $X3[0];
        goto Yi;
        IW:
        $HO[$JY[1][$zY]] = $yI;
        Yi:
        oX:
        $zY++;
        goto pm;
        eE:
        Tw:
        switch (true) {
            case !isset($HO["\110\x65\x61\144\x65\x72"]):
            case !isset($HO["\114\151\142\162\141\x72\171"]):
            case $HO["\x48\145\141\144\x65\x72"] == $HO["\114\x69\142\x72\141\162\171"]:
            case version_compare($HO["\x48\145\141\x64\x65\162"], "\61\x2e\x30\56\x30") >= 0 && version_compare($HO["\114\151\142\162\141\x72\171"], "\x31\x2e\60\56\x30") >= 0:
                define("\x4d\x41\124\x48\x5f\102\111\x47\x49\x4e\124\105\107\105\122\137\x4f\x50\x45\116\x53\x53\114\137\105\x4e\x41\102\114\x45\x44", true);
                goto qz;
            default:
                define("\x4d\x41\x54\110\x5f\x42\111\107\111\116\x54\105\x47\x45\122\137\x4f\120\x45\x4e\123\123\114\x5f\x44\x49\123\101\102\x4c\x45", true);
        }
        kp:
        qz:
        EZ:
        if (defined("\x50\110\x50\x5f\111\x4e\124\137\123\x49\x5a\105")) {
            goto j1;
        }
        define("\x50\x48\x50\137\111\x4e\124\137\x53\x49\132\105", 4);
        j1:
        if (!(!defined("\115\101\124\x48\137\x42\111\x47\x49\116\x54\105\107\105\x52\x5f\102\x41\123\x45") && MATH_BIGINTEGER_MODE == MATH_BIGINTEGER_MODE_INTERNAL)) {
            goto lq;
        }
        switch (PHP_INT_SIZE) {
            case 8:
                define("\115\101\124\110\137\102\x49\107\111\x4e\124\x45\107\x45\x52\x5f\x42\101\x53\105", 31);
                define("\x4d\x41\x54\x48\137\x42\x49\x47\x49\x4e\124\x45\x47\x45\122\137\x42\101\123\x45\x5f\x46\125\x4c\x4c", 0x80000000);
                define("\x4d\101\124\x48\137\102\111\107\x49\116\x54\x45\107\x45\122\137\115\x41\130\137\x44\111\x47\111\x54", 0x7fffffff);
                define("\115\x41\124\110\137\102\111\x47\x49\x4e\124\105\107\105\x52\x5f\115\x53\x42", 0x40000000);
                define("\x4d\x41\124\x48\x5f\x42\x49\107\x49\116\124\105\107\x45\x52\137\x4d\101\x58\x31\60", 1000000000);
                define("\115\x41\x54\x48\137\102\x49\x47\x49\116\124\x45\107\105\x52\x5f\x4d\101\x58\x31\x30\137\114\105\x4e", 9);
                define("\115\x41\124\110\x5f\x42\x49\x47\x49\116\x54\105\x47\105\122\137\x4d\x41\x58\x5f\104\x49\x47\x49\x54\x32", pow(2, 62));
                goto nP;
            default:
                define("\x4d\x41\x54\x48\x5f\102\x49\x47\x49\116\124\x45\x47\105\x52\137\102\101\x53\105", 26);
                define("\115\x41\x54\110\137\x42\x49\x47\x49\x4e\x54\105\107\x45\122\x5f\102\x41\x53\105\x5f\106\x55\x4c\x4c", 0x4000000);
                define("\115\101\x54\110\137\102\111\107\111\x4e\x54\105\x47\105\122\x5f\115\101\130\x5f\x44\111\x47\111\x54", 0x3ffffff);
                define("\x4d\x41\124\x48\x5f\102\x49\x47\x49\x4e\124\105\107\105\122\137\x4d\123\x42", 0x2000000);
                define("\x4d\101\x54\110\x5f\x42\x49\107\111\x4e\x54\x45\x47\x45\122\x5f\x4d\x41\130\61\x30", 10000000);
                define("\x4d\101\124\x48\x5f\x42\111\107\x49\116\x54\x45\107\105\122\137\115\x41\130\x31\60\137\x4c\105\x4e", 7);
                define("\x4d\101\x54\x48\x5f\102\111\x47\111\x4e\124\105\107\x45\x52\137\115\x41\x58\x5f\x44\111\x47\111\x54\62", pow(2, 52));
        }
        v0:
        nP:
        lq:
        switch (MATH_BIGINTEGER_MODE) {
            case MATH_BIGINTEGER_MODE_GMP:
                switch (true) {
                    case is_resource($k5) && get_resource_type($k5) == "\107\115\x50\x20\x69\156\164\x65\147\145\x72":
                    case is_object($k5) && get_class($k5) == "\107\115\x50":
                        $this->value = $k5;
                        return;
                }
                Z4:
                xC:
                $this->value = gmp_init(0);
                goto mG;
            case MATH_BIGINTEGER_MODE_BCMATH:
                $this->value = "\60";
                goto mG;
            default:
                $this->value = array();
        }
        Y6:
        mG:
        if (!(empty($k5) && (abs($gk) != 256 || $k5 !== "\x30"))) {
            goto Ro;
        }
        return;
        Ro:
        switch ($gk) {
            case -256:
                if (!(ord($k5[0]) & 0x80)) {
                    goto OX;
                }
                $k5 = ~$k5;
                $this->is_negative = true;
                OX:
            case 256:
                switch (MATH_BIGINTEGER_MODE) {
                    case MATH_BIGINTEGER_MODE_GMP:
                        $this->value = function_exists("\x67\x6d\x70\137\x69\x6d\x70\157\x72\x74") ? gmp_import($k5) : gmp_init("\x30\x78" . bin2hex($k5));
                        if (!$this->is_negative) {
                            goto Q7;
                        }
                        $this->value = gmp_neg($this->value);
                        Q7:
                        goto gn;
                    case MATH_BIGINTEGER_MODE_BCMATH:
                        $km = strlen($k5) + 3 & 0xfffffffc;
                        $k5 = str_pad($k5, $km, chr(0), STR_PAD_LEFT);
                        $zY = 0;
                        IO:
                        if (!($zY < $km)) {
                            goto Dw;
                        }
                        $this->value = bcmul($this->value, "\x34\x32\x39\x34\71\66\x37\x32\x39\x36", 0);
                        $this->value = bcadd($this->value, 0x1000000 * ord($k5[$zY]) + (ord($k5[$zY + 1]) << 16 | ord($k5[$zY + 2]) << 8 | ord($k5[$zY + 3])), 0);
                        S2:
                        $zY += 4;
                        goto IO;
                        Dw:
                        if (!$this->is_negative) {
                            goto eW;
                        }
                        $this->value = "\55" . $this->value;
                        eW:
                        goto gn;
                    default:
                        vR:
                        if (!strlen($k5)) {
                            goto Lc;
                        }
                        $this->value[] = $this->_bytes2int($this->_base256_rshift($k5, MATH_BIGINTEGER_BASE));
                        goto vR;
                        Lc:
                }
                HA:
                gn:
                if (!$this->is_negative) {
                    goto Be;
                }
                if (!(MATH_BIGINTEGER_MODE != MATH_BIGINTEGER_MODE_INTERNAL)) {
                    goto U0;
                }
                $this->is_negative = false;
                U0:
                $zn = $this->add(new Math_BigInteger("\55\61"));
                $this->value = $zn->value;
                Be:
                goto Zh;
            case 16:
            case -16:
                if (!($gk > 0 && $k5[0] == "\55")) {
                    goto tw;
                }
                $this->is_negative = true;
                $k5 = substr($k5, 1);
                tw:
                $k5 = preg_replace("\x23\136\x28\x3f\x3a\x30\x78\x29\77\x28\x5b\101\55\106\x61\x2d\146\60\x2d\71\135\x2a\x29\56\x2a\43", "\x24\x31", $k5);
                $Xz = false;
                if (!($gk < 0 && hexdec($k5[0]) >= 8)) {
                    goto xn;
                }
                $this->is_negative = $Xz = true;
                $k5 = bin2hex(~pack("\110\52", $k5));
                xn:
                switch (MATH_BIGINTEGER_MODE) {
                    case MATH_BIGINTEGER_MODE_GMP:
                        $zn = $this->is_negative ? "\x2d\x30\170" . $k5 : "\60\170" . $k5;
                        $this->value = gmp_init($zn);
                        $this->is_negative = false;
                        goto Gy;
                    case MATH_BIGINTEGER_MODE_BCMATH:
                        $k5 = strlen($k5) & 1 ? "\60" . $k5 : $k5;
                        $zn = new Math_BigInteger(pack("\110\52", $k5), 256);
                        $this->value = $this->is_negative ? "\55" . $zn->value : $zn->value;
                        $this->is_negative = false;
                        goto Gy;
                    default:
                        $k5 = strlen($k5) & 1 ? "\x30" . $k5 : $k5;
                        $zn = new Math_BigInteger(pack("\x48\x2a", $k5), 256);
                        $this->value = $zn->value;
                }
                RD:
                Gy:
                if (!$Xz) {
                    goto Oc;
                }
                $zn = $this->add(new Math_BigInteger("\x2d\x31"));
                $this->value = $zn->value;
                Oc:
                goto Zh;
            case 10:
            case -10:
                $k5 = preg_replace("\43\x28\x3f\x3c\41\x5e\x29\50\x3f\x3a\x2d\x29\56\52\x7c\x28\x3f\x3c\75\x5e\x7c\55\51\x30\x2a\x7c\x5b\x5e\55\60\55\x39\135\x2e\52\43", '', $k5);
                switch (MATH_BIGINTEGER_MODE) {
                    case MATH_BIGINTEGER_MODE_GMP:
                        $this->value = gmp_init($k5);
                        goto HP;
                    case MATH_BIGINTEGER_MODE_BCMATH:
                        $this->value = $k5 === "\x2d" ? "\60" : (string) $k5;
                        goto HP;
                    default:
                        $zn = new Math_BigInteger();
                        $bW = new Math_BigInteger();
                        $bW->value = array(MATH_BIGINTEGER_MAX10);
                        if (!($k5[0] == "\55")) {
                            goto QT;
                        }
                        $this->is_negative = true;
                        $k5 = substr($k5, 1);
                        QT:
                        $k5 = str_pad($k5, strlen($k5) + (MATH_BIGINTEGER_MAX10_LEN - 1) * strlen($k5) % MATH_BIGINTEGER_MAX10_LEN, 0, STR_PAD_LEFT);
                        gc:
                        if (!strlen($k5)) {
                            goto An;
                        }
                        $zn = $zn->multiply($bW);
                        $zn = $zn->add(new Math_BigInteger($this->_int2bytes(substr($k5, 0, MATH_BIGINTEGER_MAX10_LEN)), 256));
                        $k5 = substr($k5, MATH_BIGINTEGER_MAX10_LEN);
                        goto gc;
                        An:
                        $this->value = $zn->value;
                }
                TW:
                HP:
                goto Zh;
            case 2:
            case -2:
                if (!($gk > 0 && $k5[0] == "\55")) {
                    goto NL;
                }
                $this->is_negative = true;
                $k5 = substr($k5, 1);
                NL:
                $k5 = preg_replace("\43\136\x28\x5b\60\x31\135\52\x29\x2e\x2a\x23", "\x24\61", $k5);
                $k5 = str_pad($k5, strlen($k5) + 3 * strlen($k5) % 4, 0, STR_PAD_LEFT);
                $PA = "\x30\170";
                Cf:
                if (!strlen($k5)) {
                    goto ks;
                }
                $xt = substr($k5, 0, 4);
                $PA .= dechex(bindec($xt));
                $k5 = substr($k5, 4);
                goto Cf;
                ks:
                if (!$this->is_negative) {
                    goto Ti;
                }
                $PA = "\x2d" . $PA;
                Ti:
                $zn = new Math_BigInteger($PA, 8 * $gk);
                $this->value = $zn->value;
                $this->is_negative = $zn->is_negative;
                goto Zh;
            default:
        }
        NF:
        Zh:
    }
    function Math_BigInteger($k5 = 0, $gk = 10)
    {
        $this->__construct($k5, $gk);
    }
    function toBytes($Qk = false)
    {
        if (!$Qk) {
            goto gU;
        }
        $si = $this->compare(new Math_BigInteger());
        if (!($si == 0)) {
            goto RV;
        }
        return $this->precision > 0 ? str_repeat(chr(0), $this->precision + 1 >> 3) : '';
        RV:
        $zn = $si < 0 ? $this->add(new Math_BigInteger(1)) : $this->copy();
        $yH = $zn->toBytes();
        if (!empty($yH)) {
            goto Jx;
        }
        $yH = chr(0);
        Jx:
        if (!(ord($yH[0]) & 0x80)) {
            goto Go;
        }
        $yH = chr(0) . $yH;
        Go:
        return $si < 0 ? ~$yH : $yH;
        gU:
        switch (MATH_BIGINTEGER_MODE) {
            case MATH_BIGINTEGER_MODE_GMP:
                if (!(gmp_cmp($this->value, gmp_init(0)) == 0)) {
                    goto xK;
                }
                return $this->precision > 0 ? str_repeat(chr(0), $this->precision + 1 >> 3) : '';
                xK:
                if (function_exists("\147\x6d\160\137\x65\x78\x70\157\162\164")) {
                    goto XG;
                }
                $zn = gmp_strval(gmp_abs($this->value), 16);
                $zn = strlen($zn) & 1 ? "\60" . $zn : $zn;
                $zn = pack("\110\x2a", $zn);
                goto hZ;
                XG:
                $zn = gmp_export($this->value);
                hZ:
                return $this->precision > 0 ? substr(str_pad($zn, $this->precision >> 3, chr(0), STR_PAD_LEFT), -($this->precision >> 3)) : ltrim($zn, chr(0));
            case MATH_BIGINTEGER_MODE_BCMATH:
                if (!($this->value === "\60")) {
                    goto ml;
                }
                return $this->precision > 0 ? str_repeat(chr(0), $this->precision + 1 >> 3) : '';
                ml:
                $t_ = '';
                $G_ = $this->value;
                if (!($G_[0] == "\x2d")) {
                    goto AQ;
                }
                $G_ = substr($G_, 1);
                AQ:
                Zx:
                if (!(bccomp($G_, "\x30", 0) > 0)) {
                    goto Xe;
                }
                $zn = bcmod($G_, "\61\66\x37\x37\x37\x32\61\66");
                $t_ = chr($zn >> 16) . chr($zn >> 8) . chr($zn) . $t_;
                $G_ = bcdiv($G_, "\61\66\x37\x37\67\x32\61\x36", 0);
                goto Zx;
                Xe:
                return $this->precision > 0 ? substr(str_pad($t_, $this->precision >> 3, chr(0), STR_PAD_LEFT), -($this->precision >> 3)) : ltrim($t_, chr(0));
        }
        JZ:
        Qz:
        if (count($this->value)) {
            goto Of;
        }
        return $this->precision > 0 ? str_repeat(chr(0), $this->precision + 1 >> 3) : '';
        Of:
        $DE = $this->_int2bytes($this->value[count($this->value) - 1]);
        $zn = $this->copy();
        $zY = count($zn->value) - 2;
        v3:
        if (!($zY >= 0)) {
            goto On;
        }
        $zn->_base256_lshift($DE, MATH_BIGINTEGER_BASE);
        $DE = $DE | str_pad($zn->_int2bytes($zn->value[$zY]), strlen($DE), chr(0), STR_PAD_LEFT);
        Y8:
        --$zY;
        goto v3;
        On:
        return $this->precision > 0 ? str_pad(substr($DE, -($this->precision + 7 >> 3)), $this->precision + 7 >> 3, chr(0), STR_PAD_LEFT) : $DE;
    }
    function toHex($Qk = false)
    {
        return bin2hex($this->toBytes($Qk));
    }
    function toBits($Qk = false)
    {
        $ov = $this->toHex($Qk);
        $Xs = '';
        $zY = strlen($ov) - 8;
        $Kl = strlen($ov) & 7;
        Wf:
        if (!($zY >= $Kl)) {
            goto nM;
        }
        $Xs = str_pad(decbin(hexdec(substr($ov, $zY, 8))), 32, "\60", STR_PAD_LEFT) . $Xs;
        Pz:
        $zY -= 8;
        goto Wf;
        nM:
        if (!$Kl) {
            goto Sy;
        }
        $Xs = str_pad(decbin(hexdec(substr($ov, 0, $Kl))), 8, "\60", STR_PAD_LEFT) . $Xs;
        Sy:
        $DE = $this->precision > 0 ? substr($Xs, -$this->precision) : ltrim($Xs, "\60");
        if (!($Qk && $this->compare(new Math_BigInteger()) > 0 && $this->precision <= 0)) {
            goto Ne;
        }
        return "\x30" . $DE;
        Ne:
        return $DE;
    }
    function toString()
    {
        switch (MATH_BIGINTEGER_MODE) {
            case MATH_BIGINTEGER_MODE_GMP:
                return gmp_strval($this->value);
            case MATH_BIGINTEGER_MODE_BCMATH:
                if (!($this->value === "\60")) {
                    goto Lg;
                }
                return "\x30";
                Lg:
                return ltrim($this->value, "\60");
        }
        Nk:
        a9:
        if (count($this->value)) {
            goto Qu;
        }
        return "\60";
        Qu:
        $zn = $this->copy();
        $zn->is_negative = false;
        $vR = new Math_BigInteger();
        $vR->value = array(MATH_BIGINTEGER_MAX10);
        $DE = '';
        D1:
        if (!count($zn->value)) {
            goto RX;
        }
        list($zn, $cU) = $zn->divide($vR);
        $DE = str_pad(isset($cU->value[0]) ? $cU->value[0] : '', MATH_BIGINTEGER_MAX10_LEN, "\60", STR_PAD_LEFT) . $DE;
        goto D1;
        RX:
        $DE = ltrim($DE, "\60");
        if (!empty($DE)) {
            goto tk;
        }
        $DE = "\x30";
        tk:
        if (!$this->is_negative) {
            goto T1;
        }
        $DE = "\55" . $DE;
        T1:
        return $DE;
    }
    function copy()
    {
        $zn = new Math_BigInteger();
        $zn->value = $this->value;
        $zn->is_negative = $this->is_negative;
        $zn->precision = $this->precision;
        $zn->bitmask = $this->bitmask;
        return $zn;
    }
    function __toString()
    {
        return $this->toString();
    }
    function __clone()
    {
        return $this->copy();
    }
    function __sleep()
    {
        $this->hex = $this->toHex(true);
        $c5 = array("\150\x65\x78");
        if (!($this->precision > 0)) {
            goto gt;
        }
        $c5[] = "\160\162\x65\x63\151\x73\x69\x6f\x6e";
        gt:
        return $c5;
    }
    function __wakeup()
    {
        $zn = new Math_BigInteger($this->hex, -16);
        $this->value = $zn->value;
        $this->is_negative = $zn->is_negative;
        if (!($this->precision > 0)) {
            goto J4;
        }
        $this->setPrecision($this->precision);
        J4:
    }
    function __debugInfo()
    {
        $rK = array();
        switch (MATH_BIGINTEGER_MODE) {
            case MATH_BIGINTEGER_MODE_GMP:
                $nf = "\x67\x6d\x70";
                goto q3;
            case MATH_BIGINTEGER_MODE_BCMATH:
                $nf = "\142\143\x6d\141\164\150";
                goto q3;
            case MATH_BIGINTEGER_MODE_INTERNAL:
                $nf = "\151\x6e\164\x65\162\156\x61\154";
                $rK[] = PHP_INT_SIZE == 8 ? "\x36\64\55\142\x69\x74" : "\63\x32\55\142\151\x74";
        }
        xd:
        q3:
        if (!(MATH_BIGINTEGER_MODE != MATH_BIGINTEGER_MODE_GMP && defined("\x4d\101\x54\110\x5f\x42\x49\107\x49\x4e\x54\x45\107\x45\122\137\117\x50\105\116\123\123\x4c\137\x45\116\x41\102\x4c\x45\104"))) {
            goto AI;
        }
        $rK[] = "\x4f\160\145\156\x53\x53\114";
        AI:
        if (empty($rK)) {
            goto Bv;
        }
        $nf .= "\x20\x28" . implode($rK, "\x2c\40") . "\x29";
        Bv:
        return array("\166\141\x6c\165\145" => "\x30\x78" . $this->toHex(true), "\145\x6e\x67\151\x6e\145" => $nf);
    }
    function add($fZ)
    {
        switch (MATH_BIGINTEGER_MODE) {
            case MATH_BIGINTEGER_MODE_GMP:
                $zn = new Math_BigInteger();
                $zn->value = gmp_add($this->value, $fZ->value);
                return $this->_normalize($zn);
            case MATH_BIGINTEGER_MODE_BCMATH:
                $zn = new Math_BigInteger();
                $zn->value = bcadd($this->value, $fZ->value, 0);
                return $this->_normalize($zn);
        }
        Kj:
        OY:
        $zn = $this->_add($this->value, $this->is_negative, $fZ->value, $fZ->is_negative);
        $DE = new Math_BigInteger();
        $DE->value = $zn[MATH_BIGINTEGER_VALUE];
        $DE->is_negative = $zn[MATH_BIGINTEGER_SIGN];
        return $this->_normalize($DE);
    }
    function _add($iS, $Nb, $qU, $FE)
    {
        $ww = count($iS);
        $hj = count($qU);
        if ($ww == 0) {
            goto O5;
        }
        if ($hj == 0) {
            goto N6;
        }
        goto Id;
        O5:
        return array(MATH_BIGINTEGER_VALUE => $qU, MATH_BIGINTEGER_SIGN => $FE);
        goto Id;
        N6:
        return array(MATH_BIGINTEGER_VALUE => $iS, MATH_BIGINTEGER_SIGN => $Nb);
        Id:
        if (!($Nb != $FE)) {
            goto Wg;
        }
        if (!($iS == $qU)) {
            goto zM;
        }
        return array(MATH_BIGINTEGER_VALUE => array(), MATH_BIGINTEGER_SIGN => false);
        zM:
        $zn = $this->_subtract($iS, false, $qU, false);
        $zn[MATH_BIGINTEGER_SIGN] = $this->_compare($iS, false, $qU, false) > 0 ? $Nb : $FE;
        return $zn;
        Wg:
        if ($ww < $hj) {
            goto jR;
        }
        $d7 = $hj;
        $t_ = $iS;
        goto GB;
        jR:
        $d7 = $ww;
        $t_ = $qU;
        GB:
        $t_[count($t_)] = 0;
        $jc = 0;
        $zY = 0;
        $cu = 1;
        mW:
        if (!($cu < $d7)) {
            goto Tu;
        }
        $jD = $iS[$cu] * MATH_BIGINTEGER_BASE_FULL + $iS[$zY] + $qU[$cu] * MATH_BIGINTEGER_BASE_FULL + $qU[$zY] + $jc;
        $jc = $jD >= MATH_BIGINTEGER_MAX_DIGIT2;
        $jD = $jc ? $jD - MATH_BIGINTEGER_MAX_DIGIT2 : $jD;
        $zn = MATH_BIGINTEGER_BASE === 26 ? intval($jD / 0x4000000) : $jD >> 31;
        $t_[$zY] = (int) ($jD - MATH_BIGINTEGER_BASE_FULL * $zn);
        $t_[$cu] = $zn;
        v_:
        $zY += 2;
        $cu += 2;
        goto mW;
        Tu:
        if (!($cu == $d7)) {
            goto Oq;
        }
        $jD = $iS[$zY] + $qU[$zY] + $jc;
        $jc = $jD >= MATH_BIGINTEGER_BASE_FULL;
        $t_[$zY] = $jc ? $jD - MATH_BIGINTEGER_BASE_FULL : $jD;
        ++$zY;
        Oq:
        if (!$jc) {
            goto wm;
        }
        Ht:
        if (!($t_[$zY] == MATH_BIGINTEGER_MAX_DIGIT)) {
            goto mQ;
        }
        $t_[$zY] = 0;
        zC:
        ++$zY;
        goto Ht;
        mQ:
        ++$t_[$zY];
        wm:
        return array(MATH_BIGINTEGER_VALUE => $this->_trim($t_), MATH_BIGINTEGER_SIGN => $Nb);
    }
    function subtract($fZ)
    {
        switch (MATH_BIGINTEGER_MODE) {
            case MATH_BIGINTEGER_MODE_GMP:
                $zn = new Math_BigInteger();
                $zn->value = gmp_sub($this->value, $fZ->value);
                return $this->_normalize($zn);
            case MATH_BIGINTEGER_MODE_BCMATH:
                $zn = new Math_BigInteger();
                $zn->value = bcsub($this->value, $fZ->value, 0);
                return $this->_normalize($zn);
        }
        w4:
        NQ:
        $zn = $this->_subtract($this->value, $this->is_negative, $fZ->value, $fZ->is_negative);
        $DE = new Math_BigInteger();
        $DE->value = $zn[MATH_BIGINTEGER_VALUE];
        $DE->is_negative = $zn[MATH_BIGINTEGER_SIGN];
        return $this->_normalize($DE);
    }
    function _subtract($iS, $Nb, $qU, $FE)
    {
        $ww = count($iS);
        $hj = count($qU);
        if ($ww == 0) {
            goto PY;
        }
        if ($hj == 0) {
            goto A6;
        }
        goto R6;
        PY:
        return array(MATH_BIGINTEGER_VALUE => $qU, MATH_BIGINTEGER_SIGN => !$FE);
        goto R6;
        A6:
        return array(MATH_BIGINTEGER_VALUE => $iS, MATH_BIGINTEGER_SIGN => $Nb);
        R6:
        if (!($Nb != $FE)) {
            goto X6;
        }
        $zn = $this->_add($iS, false, $qU, false);
        $zn[MATH_BIGINTEGER_SIGN] = $Nb;
        return $zn;
        X6:
        $pZ = $this->_compare($iS, $Nb, $qU, $FE);
        if ($pZ) {
            goto ei;
        }
        return array(MATH_BIGINTEGER_VALUE => array(), MATH_BIGINTEGER_SIGN => false);
        ei:
        if (!(!$Nb && $pZ < 0 || $Nb && $pZ > 0)) {
            goto T3;
        }
        $zn = $iS;
        $iS = $qU;
        $qU = $zn;
        $Nb = !$Nb;
        $ww = count($iS);
        $hj = count($qU);
        T3:
        $jc = 0;
        $zY = 0;
        $cu = 1;
        Uf:
        if (!($cu < $hj)) {
            goto Ha;
        }
        $jD = $iS[$cu] * MATH_BIGINTEGER_BASE_FULL + $iS[$zY] - $qU[$cu] * MATH_BIGINTEGER_BASE_FULL - $qU[$zY] - $jc;
        $jc = $jD < 0;
        $jD = $jc ? $jD + MATH_BIGINTEGER_MAX_DIGIT2 : $jD;
        $zn = MATH_BIGINTEGER_BASE === 26 ? intval($jD / 0x4000000) : $jD >> 31;
        $iS[$zY] = (int) ($jD - MATH_BIGINTEGER_BASE_FULL * $zn);
        $iS[$cu] = $zn;
        MV:
        $zY += 2;
        $cu += 2;
        goto Uf;
        Ha:
        if (!($cu == $hj)) {
            goto LP;
        }
        $jD = $iS[$zY] - $qU[$zY] - $jc;
        $jc = $jD < 0;
        $iS[$zY] = $jc ? $jD + MATH_BIGINTEGER_BASE_FULL : $jD;
        ++$zY;
        LP:
        if (!$jc) {
            goto XGX;
        }
        Lr:
        if ($iS[$zY]) {
            goto cw;
        }
        $iS[$zY] = MATH_BIGINTEGER_MAX_DIGIT;
        zp:
        ++$zY;
        goto Lr;
        cw:
        --$iS[$zY];
        XGX:
        return array(MATH_BIGINTEGER_VALUE => $this->_trim($iS), MATH_BIGINTEGER_SIGN => $Nb);
    }
    function multiply($k5)
    {
        switch (MATH_BIGINTEGER_MODE) {
            case MATH_BIGINTEGER_MODE_GMP:
                $zn = new Math_BigInteger();
                $zn->value = gmp_mul($this->value, $k5->value);
                return $this->_normalize($zn);
            case MATH_BIGINTEGER_MODE_BCMATH:
                $zn = new Math_BigInteger();
                $zn->value = bcmul($this->value, $k5->value, 0);
                return $this->_normalize($zn);
        }
        KXi:
        RjN:
        $zn = $this->_multiply($this->value, $this->is_negative, $k5->value, $k5->is_negative);
        $Lo = new Math_BigInteger();
        $Lo->value = $zn[MATH_BIGINTEGER_VALUE];
        $Lo->is_negative = $zn[MATH_BIGINTEGER_SIGN];
        return $this->_normalize($Lo);
    }
    function _multiply($iS, $Nb, $qU, $FE)
    {
        $O1 = count($iS);
        $cz = count($qU);
        if (!(!$O1 || !$cz)) {
            goto kQh;
        }
        return array(MATH_BIGINTEGER_VALUE => array(), MATH_BIGINTEGER_SIGN => false);
        kQh:
        return array(MATH_BIGINTEGER_VALUE => min($O1, $cz) < 2 * MATH_BIGINTEGER_KARATSUBA_CUTOFF ? $this->_trim($this->_regularMultiply($iS, $qU)) : $this->_trim($this->_karatsuba($iS, $qU)), MATH_BIGINTEGER_SIGN => $Nb != $FE);
    }
    function _regularMultiply($iS, $qU)
    {
        $O1 = count($iS);
        $cz = count($qU);
        if (!(!$O1 || !$cz)) {
            goto pYM;
        }
        return array();
        pYM:
        if (!($O1 < $cz)) {
            goto Oj8;
        }
        $zn = $iS;
        $iS = $qU;
        $qU = $zn;
        $O1 = count($iS);
        $cz = count($qU);
        Oj8:
        $R2 = $this->_array_repeat(0, $O1 + $cz);
        $jc = 0;
        $cu = 0;
        f5U:
        if (!($cu < $O1)) {
            goto zwO;
        }
        $zn = $iS[$cu] * $qU[0] + $jc;
        $jc = MATH_BIGINTEGER_BASE === 26 ? intval($zn / 0x4000000) : $zn >> 31;
        $R2[$cu] = (int) ($zn - MATH_BIGINTEGER_BASE_FULL * $jc);
        MrA:
        ++$cu;
        goto f5U;
        zwO:
        $R2[$cu] = $jc;
        $zY = 1;
        mQg:
        if (!($zY < $cz)) {
            goto JUv;
        }
        $jc = 0;
        $cu = 0;
        $Wu = $zY;
        Uvm:
        if (!($cu < $O1)) {
            goto lcC;
        }
        $zn = $R2[$Wu] + $iS[$cu] * $qU[$zY] + $jc;
        $jc = MATH_BIGINTEGER_BASE === 26 ? intval($zn / 0x4000000) : $zn >> 31;
        $R2[$Wu] = (int) ($zn - MATH_BIGINTEGER_BASE_FULL * $jc);
        plD:
        ++$cu;
        ++$Wu;
        goto Uvm;
        lcC:
        $R2[$Wu] = $jc;
        zSR:
        ++$zY;
        goto mQg;
        JUv:
        return $R2;
    }
    function _karatsuba($iS, $qU)
    {
        $X3 = min(count($iS) >> 1, count($qU) >> 1);
        if (!($X3 < MATH_BIGINTEGER_KARATSUBA_CUTOFF)) {
            goto SiG;
        }
        return $this->_regularMultiply($iS, $qU);
        SiG:
        $DX = array_slice($iS, $X3);
        $NO = array_slice($iS, 0, $X3);
        $kE = array_slice($qU, $X3);
        $eX = array_slice($qU, 0, $X3);
        $pf = $this->_karatsuba($DX, $kE);
        $hY = $this->_karatsuba($NO, $eX);
        $UI = $this->_add($DX, false, $NO, false);
        $zn = $this->_add($kE, false, $eX, false);
        $UI = $this->_karatsuba($UI[MATH_BIGINTEGER_VALUE], $zn[MATH_BIGINTEGER_VALUE]);
        $zn = $this->_add($pf, false, $hY, false);
        $UI = $this->_subtract($UI, false, $zn[MATH_BIGINTEGER_VALUE], false);
        $pf = array_merge(array_fill(0, 2 * $X3, 0), $pf);
        $UI[MATH_BIGINTEGER_VALUE] = array_merge(array_fill(0, $X3, 0), $UI[MATH_BIGINTEGER_VALUE]);
        $wl = $this->_add($pf, false, $UI[MATH_BIGINTEGER_VALUE], $UI[MATH_BIGINTEGER_SIGN]);
        $wl = $this->_add($wl[MATH_BIGINTEGER_VALUE], $wl[MATH_BIGINTEGER_SIGN], $hY, false);
        return $wl[MATH_BIGINTEGER_VALUE];
    }
    function _square($k5 = false)
    {
        return count($k5) < 2 * MATH_BIGINTEGER_KARATSUBA_CUTOFF ? $this->_trim($this->_baseSquare($k5)) : $this->_trim($this->_karatsubaSquare($k5));
    }
    function _baseSquare($t_)
    {
        if (!empty($t_)) {
            goto mi_;
        }
        return array();
        mi_:
        $Zm = $this->_array_repeat(0, 2 * count($t_));
        $zY = 0;
        $sn = count($t_) - 1;
        XWm:
        if (!($zY <= $sn)) {
            goto mPl;
        }
        $su = $zY << 1;
        $zn = $Zm[$su] + $t_[$zY] * $t_[$zY];
        $jc = MATH_BIGINTEGER_BASE === 26 ? intval($zn / 0x4000000) : $zn >> 31;
        $Zm[$su] = (int) ($zn - MATH_BIGINTEGER_BASE_FULL * $jc);
        $cu = $zY + 1;
        $Wu = $su + 1;
        cGq:
        if (!($cu <= $sn)) {
            goto y2d;
        }
        $zn = $Zm[$Wu] + 2 * $t_[$cu] * $t_[$zY] + $jc;
        $jc = MATH_BIGINTEGER_BASE === 26 ? intval($zn / 0x4000000) : $zn >> 31;
        $Zm[$Wu] = (int) ($zn - MATH_BIGINTEGER_BASE_FULL * $jc);
        BW5:
        ++$cu;
        ++$Wu;
        goto cGq;
        y2d:
        $Zm[$zY + $sn + 1] = $jc;
        GBQ:
        ++$zY;
        goto XWm;
        mPl:
        return $Zm;
    }
    function _karatsubaSquare($t_)
    {
        $X3 = count($t_) >> 1;
        if (!($X3 < MATH_BIGINTEGER_KARATSUBA_CUTOFF)) {
            goto jS1;
        }
        return $this->_baseSquare($t_);
        jS1:
        $DX = array_slice($t_, $X3);
        $NO = array_slice($t_, 0, $X3);
        $pf = $this->_karatsubaSquare($DX);
        $hY = $this->_karatsubaSquare($NO);
        $UI = $this->_add($DX, false, $NO, false);
        $UI = $this->_karatsubaSquare($UI[MATH_BIGINTEGER_VALUE]);
        $zn = $this->_add($pf, false, $hY, false);
        $UI = $this->_subtract($UI, false, $zn[MATH_BIGINTEGER_VALUE], false);
        $pf = array_merge(array_fill(0, 2 * $X3, 0), $pf);
        $UI[MATH_BIGINTEGER_VALUE] = array_merge(array_fill(0, $X3, 0), $UI[MATH_BIGINTEGER_VALUE]);
        $Ss = $this->_add($pf, false, $UI[MATH_BIGINTEGER_VALUE], $UI[MATH_BIGINTEGER_SIGN]);
        $Ss = $this->_add($Ss[MATH_BIGINTEGER_VALUE], $Ss[MATH_BIGINTEGER_SIGN], $hY, false);
        return $Ss[MATH_BIGINTEGER_VALUE];
    }
    function divide($fZ)
    {
        switch (MATH_BIGINTEGER_MODE) {
            case MATH_BIGINTEGER_MODE_GMP:
                $Ns = new Math_BigInteger();
                $la = new Math_BigInteger();
                list($Ns->value, $la->value) = gmp_div_qr($this->value, $fZ->value);
                if (!(gmp_sign($la->value) < 0)) {
                    goto MHe;
                }
                $la->value = gmp_add($la->value, gmp_abs($fZ->value));
                MHe:
                return array($this->_normalize($Ns), $this->_normalize($la));
            case MATH_BIGINTEGER_MODE_BCMATH:
                $Ns = new Math_BigInteger();
                $la = new Math_BigInteger();
                $Ns->value = bcdiv($this->value, $fZ->value, 0);
                $la->value = bcmod($this->value, $fZ->value);
                if (!($la->value[0] == "\55")) {
                    goto Qq5;
                }
                $la->value = bcadd($la->value, $fZ->value[0] == "\55" ? substr($fZ->value, 1) : $fZ->value, 0);
                Qq5:
                return array($this->_normalize($Ns), $this->_normalize($la));
        }
        QTF:
        dl2:
        if (!(count($fZ->value) == 1)) {
            goto RzY;
        }
        list($xn, $lO) = $this->_divide_digit($this->value, $fZ->value[0]);
        $Ns = new Math_BigInteger();
        $la = new Math_BigInteger();
        $Ns->value = $xn;
        $la->value = array($lO);
        $Ns->is_negative = $this->is_negative != $fZ->is_negative;
        return array($this->_normalize($Ns), $this->_normalize($la));
        RzY:
        static $Ep;
        if (isset($Ep)) {
            goto ZdV;
        }
        $Ep = new Math_BigInteger();
        ZdV:
        $k5 = $this->copy();
        $fZ = $fZ->copy();
        $pi = $k5->is_negative;
        $hm = $fZ->is_negative;
        $k5->is_negative = $fZ->is_negative = false;
        $pZ = $k5->compare($fZ);
        if ($pZ) {
            goto YRk;
        }
        $zn = new Math_BigInteger();
        $zn->value = array(1);
        $zn->is_negative = $pi != $hm;
        return array($this->_normalize($zn), $this->_normalize(new Math_BigInteger()));
        YRk:
        if (!($pZ < 0)) {
            goto nMb;
        }
        if (!$pi) {
            goto wgo;
        }
        $k5 = $fZ->subtract($k5);
        wgo:
        return array($this->_normalize(new Math_BigInteger()), $this->_normalize($k5));
        nMb:
        $wx = $fZ->value[count($fZ->value) - 1];
        $r6 = 0;
        o6L:
        if ($wx & MATH_BIGINTEGER_MSB) {
            goto yxB;
        }
        $wx <<= 1;
        S2d:
        ++$r6;
        goto o6L;
        yxB:
        $k5->_lshift($r6);
        $fZ->_lshift($r6);
        $qU =& $fZ->value;
        $Z1 = count($k5->value) - 1;
        $Nk = count($fZ->value) - 1;
        $Ns = new Math_BigInteger();
        $TD =& $Ns->value;
        $TD = $this->_array_repeat(0, $Z1 - $Nk + 1);
        static $zn, $T1, $Oh;
        if (isset($zn)) {
            goto hou;
        }
        $zn = new Math_BigInteger();
        $T1 = new Math_BigInteger();
        $Oh = new Math_BigInteger();
        hou:
        $gB =& $zn->value;
        $mE =& $Oh->value;
        $gB = array_merge($this->_array_repeat(0, $Z1 - $Nk), $qU);
        WvZ:
        if (!($k5->compare($zn) >= 0)) {
            goto ATn;
        }
        ++$TD[$Z1 - $Nk];
        $k5 = $k5->subtract($zn);
        $Z1 = count($k5->value) - 1;
        goto WvZ;
        ATn:
        $zY = $Z1;
        Jap:
        if (!($zY >= $Nk + 1)) {
            goto tUt;
        }
        $iS =& $k5->value;
        $z2 = array(isset($iS[$zY]) ? $iS[$zY] : 0, isset($iS[$zY - 1]) ? $iS[$zY - 1] : 0, isset($iS[$zY - 2]) ? $iS[$zY - 2] : 0);
        $zo = array($qU[$Nk], $Nk > 0 ? $qU[$Nk - 1] : 0);
        $PX = $zY - $Nk - 1;
        if ($z2[0] == $zo[0]) {
            goto VXg;
        }
        $TD[$PX] = $this->_safe_divide($z2[0] * MATH_BIGINTEGER_BASE_FULL + $z2[1], $zo[0]);
        goto tu6;
        VXg:
        $TD[$PX] = MATH_BIGINTEGER_MAX_DIGIT;
        tu6:
        $gB = array($zo[1], $zo[0]);
        $T1->value = array($TD[$PX]);
        $T1 = $T1->multiply($zn);
        $mE = array($z2[2], $z2[1], $z2[0]);
        MaW:
        if (!($T1->compare($Oh) > 0)) {
            goto zIr;
        }
        --$TD[$PX];
        $T1->value = array($TD[$PX]);
        $T1 = $T1->multiply($zn);
        goto MaW;
        zIr:
        $Z_ = $this->_array_repeat(0, $PX);
        $gB = array($TD[$PX]);
        $zn = $zn->multiply($fZ);
        $gB =& $zn->value;
        $gB = array_merge($Z_, $gB);
        $k5 = $k5->subtract($zn);
        if (!($k5->compare($Ep) < 0)) {
            goto Otw;
        }
        $gB = array_merge($Z_, $qU);
        $k5 = $k5->add($zn);
        --$TD[$PX];
        Otw:
        $Z1 = count($iS) - 1;
        tMj:
        --$zY;
        goto Jap;
        tUt:
        $k5->_rshift($r6);
        $Ns->is_negative = $pi != $hm;
        if (!$pi) {
            goto wRS;
        }
        $fZ->_rshift($r6);
        $k5 = $fZ->subtract($k5);
        wRS:
        return array($this->_normalize($Ns), $this->_normalize($k5));
    }
    function _divide_digit($Fl, $vR)
    {
        $jc = 0;
        $DE = array();
        $zY = count($Fl) - 1;
        z1j:
        if (!($zY >= 0)) {
            goto rX9;
        }
        $zn = MATH_BIGINTEGER_BASE_FULL * $jc + $Fl[$zY];
        $DE[$zY] = $this->_safe_divide($zn, $vR);
        $jc = (int) ($zn - $vR * $DE[$zY]);
        XgU:
        --$zY;
        goto z1j;
        rX9:
        return array($DE, $jc);
    }
    function modPow($mP, $Bd)
    {
        $Bd = $this->bitmask !== false && $this->bitmask->compare($Bd) < 0 ? $this->bitmask : $Bd->abs();
        if (!($mP->compare(new Math_BigInteger()) < 0)) {
            goto IDk;
        }
        $mP = $mP->abs();
        $zn = $this->modInverse($Bd);
        if (!($zn === false)) {
            goto S76;
        }
        return false;
        S76:
        return $this->_normalize($zn->modPow($mP, $Bd));
        IDk:
        if (!(MATH_BIGINTEGER_MODE == MATH_BIGINTEGER_MODE_GMP)) {
            goto lSc;
        }
        $zn = new Math_BigInteger();
        $zn->value = gmp_powm($this->value, $mP->value, $Bd->value);
        return $this->_normalize($zn);
        lSc:
        if (!($this->compare(new Math_BigInteger()) < 0 || $this->compare($Bd) > 0)) {
            goto duO;
        }
        list(, $zn) = $this->divide($Bd);
        return $zn->modPow($mP, $Bd);
        duO:
        if (!defined("\x4d\101\x54\110\137\102\111\107\111\116\124\x45\x47\105\x52\x5f\117\120\x45\x4e\x53\x53\x4c\137\105\116\x41\x42\114\105\104")) {
            goto HWn;
        }
        $Nd = array("\x6d\x6f\144\x75\x6c\165\163" => $Bd->toBytes(true), "\x70\x75\x62\x6c\x69\x63\105\170\160\157\156\x65\x6e\164" => $mP->toBytes(true));
        $Nd = array("\x6d\x6f\144\165\x6c\x75\x73" => pack("\x43\x61\52\141\52", 2, $this->_encodeASN1Length(strlen($Nd["\x6d\157\144\165\154\x75\163"])), $Nd["\155\157\144\x75\x6c\x75\163"]), "\160\165\142\154\x69\x63\105\170\160\x6f\x6e\x65\156\164" => pack("\103\141\x2a\141\52", 2, $this->_encodeASN1Length(strlen($Nd["\160\x75\142\154\x69\x63\x45\170\x70\x6f\x6e\145\156\x74"])), $Nd["\160\x75\142\154\151\x63\x45\x78\x70\157\156\145\x6e\164"]));
        $m9 = pack("\103\x61\x2a\x61\x2a\141\x2a", 48, $this->_encodeASN1Length(strlen($Nd["\x6d\157\144\165\154\165\163"]) + strlen($Nd["\160\165\x62\x6c\151\143\x45\x78\160\x6f\x6e\145\x6e\164"])), $Nd["\x6d\x6f\x64\x75\x6c\165\x73"], $Nd["\x70\165\142\154\151\x63\105\x78\x70\x6f\x6e\145\156\x74"]);
        $M7 = pack("\110\x2a", "\x33\x30\x30\144\x30\66\60\x39\x32\x61\x38\66\x34\70\x38\x36\x66\x37\60\x64\x30\x31\60\61\x30\61\x30\65\60\60");
        $m9 = chr(0) . $m9;
        $m9 = chr(3) . $this->_encodeASN1Length(strlen($m9)) . $m9;
        $vP = pack("\103\x61\x2a\x61\x2a", 48, $this->_encodeASN1Length(strlen($M7 . $m9)), $M7 . $m9);
        $m9 = "\x2d\55\55\55\x2d\102\105\107\x49\116\40\120\125\x42\x4c\111\x43\x20\x4b\105\x59\x2d\x2d\x2d\x2d\55\15\xa" . chunk_split(base64_encode($vP)) . "\x2d\x2d\x2d\x2d\55\105\116\104\40\x50\x55\102\114\x49\103\x20\113\105\131\55\55\55\x2d\55";
        $Lq = str_pad($this->toBytes(), strlen($Bd->toBytes(true)) - 1, "\0", STR_PAD_LEFT);
        if (!openssl_public_encrypt($Lq, $DE, $m9, OPENSSL_NO_PADDING)) {
            goto ohW;
        }
        return new Math_BigInteger($DE, 256);
        ohW:
        HWn:
        if (!(MATH_BIGINTEGER_MODE == MATH_BIGINTEGER_MODE_BCMATH)) {
            goto h3t;
        }
        $zn = new Math_BigInteger();
        $zn->value = bcpowmod($this->value, $mP->value, $Bd->value, 0);
        return $this->_normalize($zn);
        h3t:
        if (!empty($mP->value)) {
            goto T6e;
        }
        $zn = new Math_BigInteger();
        $zn->value = array(1);
        return $this->_normalize($zn);
        T6e:
        if (!($mP->value == array(1))) {
            goto wTY;
        }
        list(, $zn) = $this->divide($Bd);
        return $this->_normalize($zn);
        wTY:
        if (!($mP->value == array(2))) {
            goto WW9;
        }
        $zn = new Math_BigInteger();
        $zn->value = $this->_square($this->value);
        list(, $zn) = $zn->divide($Bd);
        return $this->_normalize($zn);
        WW9:
        return $this->_normalize($this->_slidingWindow($mP, $Bd, MATH_BIGINTEGER_BARRETT));
        if (!($Bd->value[0] & 1)) {
            goto JNP;
        }
        return $this->_normalize($this->_slidingWindow($mP, $Bd, MATH_BIGINTEGER_MONTGOMERY));
        JNP:
        $zY = 0;
        ZII:
        if (!($zY < count($Bd->value))) {
            goto RkA;
        }
        if (!$Bd->value[$zY]) {
            goto C2C;
        }
        $zn = decbin($Bd->value[$zY]);
        $cu = strlen($zn) - strrpos($zn, "\x31") - 1;
        $cu += 26 * $zY;
        goto RkA;
        C2C:
        BBj:
        ++$zY;
        goto ZII;
        RkA:
        $Yi = $Bd->copy();
        $Yi->_rshift($cu);
        $W8 = new Math_BigInteger();
        $W8->value = array(1);
        $W8->_lshift($cu);
        $hQ = $Yi->value != array(1) ? $this->_slidingWindow($mP, $Yi, MATH_BIGINTEGER_MONTGOMERY) : new Math_BigInteger();
        $X4 = $this->_slidingWindow($mP, $W8, MATH_BIGINTEGER_POWEROF2);
        $kE = $W8->modInverse($Yi);
        $W6 = $Yi->modInverse($W8);
        $DE = $hQ->multiply($W8);
        $DE = $DE->multiply($kE);
        $zn = $X4->multiply($Yi);
        $zn = $zn->multiply($W6);
        $DE = $DE->add($zn);
        list(, $DE) = $DE->divide($Bd);
        return $this->_normalize($DE);
    }
    function powMod($mP, $Bd)
    {
        return $this->modPow($mP, $Bd);
    }
    function _slidingWindow($mP, $Bd, $pU)
    {
        static $eC = array(7, 25, 81, 241, 673, 1793);
        $D1 = $mP->value;
        $pu = count($D1) - 1;
        $W9 = decbin($D1[$pu]);
        $zY = $pu - 1;
        qeO:
        if (!($zY >= 0)) {
            goto FSN;
        }
        $W9 .= str_pad(decbin($D1[$zY]), MATH_BIGINTEGER_BASE, "\x30", STR_PAD_LEFT);
        jhA:
        --$zY;
        goto qeO;
        FSN:
        $pu = strlen($W9);
        $zY = 0;
        $uX = 1;
        qUa:
        if (!($zY < count($eC) && $pu > $eC[$zY])) {
            goto WEQ;
        }
        nDK:
        ++$uX;
        ++$zY;
        goto qUa;
        WEQ:
        $Ty = $Bd->value;
        $sO = array();
        $sO[1] = $this->_prepareReduce($this->value, $Ty, $pU);
        $sO[2] = $this->_squareReduce($sO[1], $Ty, $pU);
        $zn = 1 << $uX - 1;
        $zY = 1;
        QtE:
        if (!($zY < $zn)) {
            goto Tv5;
        }
        $su = $zY << 1;
        $sO[$su + 1] = $this->_multiplyReduce($sO[$su - 1], $sO[2], $Ty, $pU);
        nnb:
        ++$zY;
        goto QtE;
        Tv5:
        $DE = array(1);
        $DE = $this->_prepareReduce($DE, $Ty, $pU);
        $zY = 0;
        IZW:
        if (!($zY < $pu)) {
            goto Gl0;
        }
        if (!$W9[$zY]) {
            goto yrO;
        }
        $cu = $uX - 1;
        Zrs:
        if (!($cu > 0)) {
            goto Xxd;
        }
        if (empty($W9[$zY + $cu])) {
            goto p7O;
        }
        goto Xxd;
        p7O:
        lM6:
        --$cu;
        goto Zrs;
        Xxd:
        $Wu = 0;
        Txp:
        if (!($Wu <= $cu)) {
            goto CjC;
        }
        $DE = $this->_squareReduce($DE, $Ty, $pU);
        Jrt:
        ++$Wu;
        goto Txp;
        CjC:
        $DE = $this->_multiplyReduce($DE, $sO[bindec(substr($W9, $zY, $cu + 1))], $Ty, $pU);
        $zY += $cu + 1;
        goto tJv;
        yrO:
        $DE = $this->_squareReduce($DE, $Ty, $pU);
        ++$zY;
        tJv:
        yCA:
        goto IZW;
        Gl0:
        $zn = new Math_BigInteger();
        $zn->value = $this->_reduce($DE, $Ty, $pU);
        return $zn;
    }
    function _reduce($k5, $Bd, $pU)
    {
        switch ($pU) {
            case MATH_BIGINTEGER_MONTGOMERY:
                return $this->_montgomery($k5, $Bd);
            case MATH_BIGINTEGER_BARRETT:
                return $this->_barrett($k5, $Bd);
            case MATH_BIGINTEGER_POWEROF2:
                $T1 = new Math_BigInteger();
                $T1->value = $k5;
                $Oh = new Math_BigInteger();
                $Oh->value = $Bd;
                return $k5->_mod2($Bd);
            case MATH_BIGINTEGER_CLASSIC:
                $T1 = new Math_BigInteger();
                $T1->value = $k5;
                $Oh = new Math_BigInteger();
                $Oh->value = $Bd;
                list(, $zn) = $T1->divide($Oh);
                return $zn->value;
            case MATH_BIGINTEGER_NONE:
                return $k5;
            default:
        }
        BFx:
        QJk:
    }
    function _prepareReduce($k5, $Bd, $pU)
    {
        if (!($pU == MATH_BIGINTEGER_MONTGOMERY)) {
            goto YhZ;
        }
        return $this->_prepMontgomery($k5, $Bd);
        YhZ:
        return $this->_reduce($k5, $Bd, $pU);
    }
    function _multiplyReduce($k5, $fZ, $Bd, $pU)
    {
        if (!($pU == MATH_BIGINTEGER_MONTGOMERY)) {
            goto WhT;
        }
        return $this->_montgomeryMultiply($k5, $fZ, $Bd);
        WhT:
        $zn = $this->_multiply($k5, false, $fZ, false);
        return $this->_reduce($zn[MATH_BIGINTEGER_VALUE], $Bd, $pU);
    }
    function _squareReduce($k5, $Bd, $pU)
    {
        if (!($pU == MATH_BIGINTEGER_MONTGOMERY)) {
            goto UMY;
        }
        return $this->_montgomeryMultiply($k5, $k5, $Bd);
        UMY:
        return $this->_reduce($this->_square($k5), $Bd, $pU);
    }
    function _mod2($Bd)
    {
        $zn = new Math_BigInteger();
        $zn->value = array(1);
        return $this->bitwise_and($Bd->subtract($zn));
    }
    function _barrett($Bd, $X3)
    {
        static $mM = array(MATH_BIGINTEGER_VARIABLE => array(), MATH_BIGINTEGER_DATA => array());
        $ib = count($X3);
        if (!(count($Bd) > 2 * $ib)) {
            goto JwG;
        }
        $T1 = new Math_BigInteger();
        $Oh = new Math_BigInteger();
        $T1->value = $Bd;
        $Oh->value = $X3;
        list(, $zn) = $T1->divide($Oh);
        return $zn->value;
        JwG:
        if (!($ib < 5)) {
            goto VE1;
        }
        return $this->_regularBarrett($Bd, $X3);
        VE1:
        if (($Mr = array_search($X3, $mM[MATH_BIGINTEGER_VARIABLE])) === false) {
            goto tDQ;
        }
        extract($mM[MATH_BIGINTEGER_DATA][$Mr]);
        goto lcX;
        tDQ:
        $Mr = count($mM[MATH_BIGINTEGER_VARIABLE]);
        $mM[MATH_BIGINTEGER_VARIABLE][] = $X3;
        $T1 = new Math_BigInteger();
        $GE =& $T1->value;
        $GE = $this->_array_repeat(0, $ib + ($ib >> 1));
        $GE[] = 1;
        $Oh = new Math_BigInteger();
        $Oh->value = $X3;
        list($kZ, $Q7) = $T1->divide($Oh);
        $kZ = $kZ->value;
        $Q7 = $Q7->value;
        $mM[MATH_BIGINTEGER_DATA][] = array("\x75" => $kZ, "\155\x31" => $Q7);
        lcX:
        $iq = $ib + ($ib >> 1);
        $CF = array_slice($Bd, 0, $iq);
        $hd = array_slice($Bd, $iq);
        $CF = $this->_trim($CF);
        $zn = $this->_multiply($hd, false, $Q7, false);
        $Bd = $this->_add($CF, false, $zn[MATH_BIGINTEGER_VALUE], false);
        if (!($ib & 1)) {
            goto iRQ;
        }
        return $this->_regularBarrett($Bd[MATH_BIGINTEGER_VALUE], $X3);
        iRQ:
        $zn = array_slice($Bd[MATH_BIGINTEGER_VALUE], $ib - 1);
        $zn = $this->_multiply($zn, false, $kZ, false);
        $zn = array_slice($zn[MATH_BIGINTEGER_VALUE], ($ib >> 1) + 1);
        $zn = $this->_multiply($zn, false, $X3, false);
        $DE = $this->_subtract($Bd[MATH_BIGINTEGER_VALUE], false, $zn[MATH_BIGINTEGER_VALUE], false);
        VXP:
        if (!($this->_compare($DE[MATH_BIGINTEGER_VALUE], $DE[MATH_BIGINTEGER_SIGN], $X3, false) >= 0)) {
            goto NpA;
        }
        $DE = $this->_subtract($DE[MATH_BIGINTEGER_VALUE], $DE[MATH_BIGINTEGER_SIGN], $X3, false);
        goto VXP;
        NpA:
        return $DE[MATH_BIGINTEGER_VALUE];
    }
    function _regularBarrett($k5, $Bd)
    {
        static $mM = array(MATH_BIGINTEGER_VARIABLE => array(), MATH_BIGINTEGER_DATA => array());
        $wS = count($Bd);
        if (!(count($k5) > 2 * $wS)) {
            goto Mgi;
        }
        $T1 = new Math_BigInteger();
        $Oh = new Math_BigInteger();
        $T1->value = $k5;
        $Oh->value = $Bd;
        list(, $zn) = $T1->divide($Oh);
        return $zn->value;
        Mgi:
        if (!(($Mr = array_search($Bd, $mM[MATH_BIGINTEGER_VARIABLE])) === false)) {
            goto DoT;
        }
        $Mr = count($mM[MATH_BIGINTEGER_VARIABLE]);
        $mM[MATH_BIGINTEGER_VARIABLE][] = $Bd;
        $T1 = new Math_BigInteger();
        $GE =& $T1->value;
        $GE = $this->_array_repeat(0, 2 * $wS);
        $GE[] = 1;
        $Oh = new Math_BigInteger();
        $Oh->value = $Bd;
        list($zn, ) = $T1->divide($Oh);
        $mM[MATH_BIGINTEGER_DATA][] = $zn->value;
        DoT:
        $zn = array_slice($k5, $wS - 1);
        $zn = $this->_multiply($zn, false, $mM[MATH_BIGINTEGER_DATA][$Mr], false);
        $zn = array_slice($zn[MATH_BIGINTEGER_VALUE], $wS + 1);
        $DE = array_slice($k5, 0, $wS + 1);
        $zn = $this->_multiplyLower($zn, false, $Bd, false, $wS + 1);
        if (!($this->_compare($DE, false, $zn[MATH_BIGINTEGER_VALUE], $zn[MATH_BIGINTEGER_SIGN]) < 0)) {
            goto M10;
        }
        $U7 = $this->_array_repeat(0, $wS + 1);
        $U7[count($U7)] = 1;
        $DE = $this->_add($DE, false, $U7, false);
        $DE = $DE[MATH_BIGINTEGER_VALUE];
        M10:
        $DE = $this->_subtract($DE, false, $zn[MATH_BIGINTEGER_VALUE], $zn[MATH_BIGINTEGER_SIGN]);
        Xg5:
        if (!($this->_compare($DE[MATH_BIGINTEGER_VALUE], $DE[MATH_BIGINTEGER_SIGN], $Bd, false) > 0)) {
            goto Zen;
        }
        $DE = $this->_subtract($DE[MATH_BIGINTEGER_VALUE], $DE[MATH_BIGINTEGER_SIGN], $Bd, false);
        goto Xg5;
        Zen:
        return $DE[MATH_BIGINTEGER_VALUE];
    }
    function _multiplyLower($iS, $Nb, $qU, $FE, $ZV)
    {
        $O1 = count($iS);
        $cz = count($qU);
        if (!(!$O1 || !$cz)) {
            goto TFj;
        }
        return array(MATH_BIGINTEGER_VALUE => array(), MATH_BIGINTEGER_SIGN => false);
        TFj:
        if (!($O1 < $cz)) {
            goto Cws;
        }
        $zn = $iS;
        $iS = $qU;
        $qU = $zn;
        $O1 = count($iS);
        $cz = count($qU);
        Cws:
        $R2 = $this->_array_repeat(0, $O1 + $cz);
        $jc = 0;
        $cu = 0;
        YvJ:
        if (!($cu < $O1)) {
            goto TKF;
        }
        $zn = $iS[$cu] * $qU[0] + $jc;
        $jc = MATH_BIGINTEGER_BASE === 26 ? intval($zn / 0x4000000) : $zn >> 31;
        $R2[$cu] = (int) ($zn - MATH_BIGINTEGER_BASE_FULL * $jc);
        ytw:
        ++$cu;
        goto YvJ;
        TKF:
        if (!($cu < $ZV)) {
            goto cSi;
        }
        $R2[$cu] = $jc;
        cSi:
        $zY = 1;
        SY9:
        if (!($zY < $cz)) {
            goto kh9;
        }
        $jc = 0;
        $cu = 0;
        $Wu = $zY;
        wQu:
        if (!($cu < $O1 && $Wu < $ZV)) {
            goto RF8;
        }
        $zn = $R2[$Wu] + $iS[$cu] * $qU[$zY] + $jc;
        $jc = MATH_BIGINTEGER_BASE === 26 ? intval($zn / 0x4000000) : $zn >> 31;
        $R2[$Wu] = (int) ($zn - MATH_BIGINTEGER_BASE_FULL * $jc);
        o1w:
        ++$cu;
        ++$Wu;
        goto wQu;
        RF8:
        if (!($Wu < $ZV)) {
            goto Zuc;
        }
        $R2[$Wu] = $jc;
        Zuc:
        kcA:
        ++$zY;
        goto SY9;
        kh9:
        return array(MATH_BIGINTEGER_VALUE => $this->_trim($R2), MATH_BIGINTEGER_SIGN => $Nb != $FE);
    }
    function _montgomery($k5, $Bd)
    {
        static $mM = array(MATH_BIGINTEGER_VARIABLE => array(), MATH_BIGINTEGER_DATA => array());
        if (!(($Mr = array_search($Bd, $mM[MATH_BIGINTEGER_VARIABLE])) === false)) {
            goto LoT;
        }
        $Mr = count($mM[MATH_BIGINTEGER_VARIABLE]);
        $mM[MATH_BIGINTEGER_VARIABLE][] = $k5;
        $mM[MATH_BIGINTEGER_DATA][] = $this->_modInverse67108864($Bd);
        LoT:
        $Wu = count($Bd);
        $DE = array(MATH_BIGINTEGER_VALUE => $k5);
        $zY = 0;
        OZM:
        if (!($zY < $Wu)) {
            goto XoG;
        }
        $zn = $DE[MATH_BIGINTEGER_VALUE][$zY] * $mM[MATH_BIGINTEGER_DATA][$Mr];
        $zn = $zn - MATH_BIGINTEGER_BASE_FULL * (MATH_BIGINTEGER_BASE === 26 ? intval($zn / 0x4000000) : $zn >> 31);
        $zn = $this->_regularMultiply(array($zn), $Bd);
        $zn = array_merge($this->_array_repeat(0, $zY), $zn);
        $DE = $this->_add($DE[MATH_BIGINTEGER_VALUE], false, $zn, false);
        pEt:
        ++$zY;
        goto OZM;
        XoG:
        $DE[MATH_BIGINTEGER_VALUE] = array_slice($DE[MATH_BIGINTEGER_VALUE], $Wu);
        if (!($this->_compare($DE, false, $Bd, false) >= 0)) {
            goto sQm;
        }
        $DE = $this->_subtract($DE[MATH_BIGINTEGER_VALUE], false, $Bd, false);
        sQm:
        return $DE[MATH_BIGINTEGER_VALUE];
    }
    function _montgomeryMultiply($k5, $fZ, $X3)
    {
        $zn = $this->_multiply($k5, false, $fZ, false);
        return $this->_montgomery($zn[MATH_BIGINTEGER_VALUE], $X3);
        static $mM = array(MATH_BIGINTEGER_VARIABLE => array(), MATH_BIGINTEGER_DATA => array());
        if (!(($Mr = array_search($X3, $mM[MATH_BIGINTEGER_VARIABLE])) === false)) {
            goto Fhe;
        }
        $Mr = count($mM[MATH_BIGINTEGER_VARIABLE]);
        $mM[MATH_BIGINTEGER_VARIABLE][] = $X3;
        $mM[MATH_BIGINTEGER_DATA][] = $this->_modInverse67108864($X3);
        Fhe:
        $Bd = max(count($k5), count($fZ), count($X3));
        $k5 = array_pad($k5, $Bd, 0);
        $fZ = array_pad($fZ, $Bd, 0);
        $X3 = array_pad($X3, $Bd, 0);
        $hP = array(MATH_BIGINTEGER_VALUE => $this->_array_repeat(0, $Bd + 1));
        $zY = 0;
        gfS:
        if (!($zY < $Bd)) {
            goto kHy;
        }
        $zn = $hP[MATH_BIGINTEGER_VALUE][0] + $k5[$zY] * $fZ[0];
        $zn = $zn - MATH_BIGINTEGER_BASE_FULL * (MATH_BIGINTEGER_BASE === 26 ? intval($zn / 0x4000000) : $zn >> 31);
        $zn = $zn * $mM[MATH_BIGINTEGER_DATA][$Mr];
        $zn = $zn - MATH_BIGINTEGER_BASE_FULL * (MATH_BIGINTEGER_BASE === 26 ? intval($zn / 0x4000000) : $zn >> 31);
        $zn = $this->_add($this->_regularMultiply(array($k5[$zY]), $fZ), false, $this->_regularMultiply(array($zn), $X3), false);
        $hP = $this->_add($hP[MATH_BIGINTEGER_VALUE], false, $zn[MATH_BIGINTEGER_VALUE], false);
        $hP[MATH_BIGINTEGER_VALUE] = array_slice($hP[MATH_BIGINTEGER_VALUE], 1);
        Z6k:
        ++$zY;
        goto gfS;
        kHy:
        if (!($this->_compare($hP[MATH_BIGINTEGER_VALUE], false, $X3, false) >= 0)) {
            goto Gu9;
        }
        $hP = $this->_subtract($hP[MATH_BIGINTEGER_VALUE], false, $X3, false);
        Gu9:
        return $hP[MATH_BIGINTEGER_VALUE];
    }
    function _prepMontgomery($k5, $Bd)
    {
        $T1 = new Math_BigInteger();
        $T1->value = array_merge($this->_array_repeat(0, count($Bd)), $k5);
        $Oh = new Math_BigInteger();
        $Oh->value = $Bd;
        list(, $zn) = $T1->divide($Oh);
        return $zn->value;
    }
    function _modInverse67108864($k5)
    {
        $k5 = -$k5[0];
        $DE = $k5 & 0x3;
        $DE = $DE * (2 - $k5 * $DE) & 0xf;
        $DE = $DE * (2 - ($k5 & 0xff) * $DE) & 0xff;
        $DE = $DE * (2 - ($k5 & 0xffff) * $DE & 0xffff) & 0xffff;
        $DE = fmod($DE * (2 - fmod($k5 * $DE, MATH_BIGINTEGER_BASE_FULL)), MATH_BIGINTEGER_BASE_FULL);
        return $DE & MATH_BIGINTEGER_MAX_DIGIT;
    }
    function modInverse($Bd)
    {
        switch (MATH_BIGINTEGER_MODE) {
            case MATH_BIGINTEGER_MODE_GMP:
                $zn = new Math_BigInteger();
                $zn->value = gmp_invert($this->value, $Bd->value);
                return $zn->value === false ? false : $this->_normalize($zn);
        }
        P12:
        lV8:
        static $Ep, $fb;
        if (isset($Ep)) {
            goto BLM;
        }
        $Ep = new Math_BigInteger();
        $fb = new Math_BigInteger(1);
        BLM:
        $Bd = $Bd->abs();
        if (!($this->compare($Ep) < 0)) {
            goto wY1;
        }
        $zn = $this->abs();
        $zn = $zn->modInverse($Bd);
        return $this->_normalize($Bd->subtract($zn));
        wY1:
        extract($this->extendedGCD($Bd));
        if ($AE->equals($fb)) {
            goto nr3;
        }
        return false;
        nr3:
        $k5 = $k5->compare($Ep) < 0 ? $k5->add($Bd) : $k5;
        return $this->compare($Ep) < 0 ? $this->_normalize($Bd->subtract($k5)) : $this->_normalize($k5);
    }
    function extendedGCD($Bd)
    {
        switch (MATH_BIGINTEGER_MODE) {
            case MATH_BIGINTEGER_MODE_GMP:
                extract(gmp_gcdext($this->value, $Bd->value));
                return array("\x67\x63\x64" => $this->_normalize(new Math_BigInteger($Sm)), "\x78" => $this->_normalize(new Math_BigInteger($Z7)), "\171" => $this->_normalize(new Math_BigInteger($sl)));
            case MATH_BIGINTEGER_MODE_BCMATH:
                $kZ = $this->value;
                $zs = $Bd->value;
                $hP = "\x31";
                $fM = "\60";
                $eo = "\x30";
                $qa = "\61";
                taD:
                if (!(bccomp($zs, "\x30", 0) != 0)) {
                    goto OYm;
                }
                $xn = bcdiv($kZ, $zs, 0);
                $zn = $kZ;
                $kZ = $zs;
                $zs = bcsub($zn, bcmul($zs, $xn, 0), 0);
                $zn = $hP;
                $hP = $eo;
                $eo = bcsub($zn, bcmul($hP, $xn, 0), 0);
                $zn = $fM;
                $fM = $qa;
                $qa = bcsub($zn, bcmul($fM, $xn, 0), 0);
                goto taD;
                OYm:
                return array("\147\x63\144" => $this->_normalize(new Math_BigInteger($kZ)), "\x78" => $this->_normalize(new Math_BigInteger($hP)), "\171" => $this->_normalize(new Math_BigInteger($fM)));
        }
        UiH:
        TPi:
        $fZ = $Bd->copy();
        $k5 = $this->copy();
        $Sm = new Math_BigInteger();
        $Sm->value = array(1);
        bRY:
        if ($k5->value[0] & 1 || $fZ->value[0] & 1) {
            goto LKZ;
        }
        $k5->_rshift(1);
        $fZ->_rshift(1);
        $Sm->_lshift(1);
        goto bRY;
        LKZ:
        $kZ = $k5->copy();
        $zs = $fZ->copy();
        $hP = new Math_BigInteger();
        $fM = new Math_BigInteger();
        $eo = new Math_BigInteger();
        $qa = new Math_BigInteger();
        $hP->value = $qa->value = $Sm->value = array(1);
        $fM->value = $eo->value = array();
        P9f:
        if (empty($kZ->value)) {
            goto UG2;
        }
        Bkc:
        if ($kZ->value[0] & 1) {
            goto QWm;
        }
        $kZ->_rshift(1);
        if (!(!empty($hP->value) && $hP->value[0] & 1 || !empty($fM->value) && $fM->value[0] & 1)) {
            goto lFg;
        }
        $hP = $hP->add($fZ);
        $fM = $fM->subtract($k5);
        lFg:
        $hP->_rshift(1);
        $fM->_rshift(1);
        goto Bkc;
        QWm:
        f4r:
        if ($zs->value[0] & 1) {
            goto WuR;
        }
        $zs->_rshift(1);
        if (!(!empty($qa->value) && $qa->value[0] & 1 || !empty($eo->value) && $eo->value[0] & 1)) {
            goto ANE;
        }
        $eo = $eo->add($fZ);
        $qa = $qa->subtract($k5);
        ANE:
        $eo->_rshift(1);
        $qa->_rshift(1);
        goto f4r;
        WuR:
        if ($kZ->compare($zs) >= 0) {
            goto qGq;
        }
        $zs = $zs->subtract($kZ);
        $eo = $eo->subtract($hP);
        $qa = $qa->subtract($fM);
        goto ajf;
        qGq:
        $kZ = $kZ->subtract($zs);
        $hP = $hP->subtract($eo);
        $fM = $fM->subtract($qa);
        ajf:
        goto P9f;
        UG2:
        return array("\147\x63\144" => $this->_normalize($Sm->multiply($zs)), "\170" => $this->_normalize($eo), "\x79" => $this->_normalize($qa));
    }
    function gcd($Bd)
    {
        extract($this->extendedGCD($Bd));
        return $AE;
    }
    function abs()
    {
        $zn = new Math_BigInteger();
        switch (MATH_BIGINTEGER_MODE) {
            case MATH_BIGINTEGER_MODE_GMP:
                $zn->value = gmp_abs($this->value);
                goto I8g;
            case MATH_BIGINTEGER_MODE_BCMATH:
                $zn->value = bccomp($this->value, "\x30", 0) < 0 ? substr($this->value, 1) : $this->value;
                goto I8g;
            default:
                $zn->value = $this->value;
        }
        avf:
        I8g:
        return $zn;
    }
    function compare($fZ)
    {
        switch (MATH_BIGINTEGER_MODE) {
            case MATH_BIGINTEGER_MODE_GMP:
                return gmp_cmp($this->value, $fZ->value);
            case MATH_BIGINTEGER_MODE_BCMATH:
                return bccomp($this->value, $fZ->value, 0);
        }
        v4z:
        G0h:
        return $this->_compare($this->value, $this->is_negative, $fZ->value, $fZ->is_negative);
    }
    function _compare($iS, $Nb, $qU, $FE)
    {
        if (!($Nb != $FE)) {
            goto RXN;
        }
        return !$Nb && $FE ? 1 : -1;
        RXN:
        $DE = $Nb ? -1 : 1;
        if (!(count($iS) != count($qU))) {
            goto Dyl;
        }
        return count($iS) > count($qU) ? $DE : -$DE;
        Dyl:
        $d7 = max(count($iS), count($qU));
        $iS = array_pad($iS, $d7, 0);
        $qU = array_pad($qU, $d7, 0);
        $zY = count($iS) - 1;
        wWr:
        if (!($zY >= 0)) {
            goto DM4;
        }
        if (!($iS[$zY] != $qU[$zY])) {
            goto i3j;
        }
        return $iS[$zY] > $qU[$zY] ? $DE : -$DE;
        i3j:
        CNW:
        --$zY;
        goto wWr;
        DM4:
        return 0;
    }
    function equals($k5)
    {
        switch (MATH_BIGINTEGER_MODE) {
            case MATH_BIGINTEGER_MODE_GMP:
                return gmp_cmp($this->value, $k5->value) == 0;
            default:
                return $this->value === $k5->value && $this->is_negative == $k5->is_negative;
        }
        aUF:
        UND:
    }
    function setPrecision($Xs)
    {
        $this->precision = $Xs;
        if (MATH_BIGINTEGER_MODE != MATH_BIGINTEGER_MODE_BCMATH) {
            goto VlZ;
        }
        $this->bitmask = new Math_BigInteger(bcpow("\x32", $Xs, 0));
        goto qqF;
        VlZ:
        $this->bitmask = new Math_BigInteger(chr((1 << ($Xs & 0x7)) - 1) . str_repeat(chr(0xff), $Xs >> 3), 256);
        qqF:
        $zn = $this->_normalize($this);
        $this->value = $zn->value;
    }
    function bitwise_and($k5)
    {
        switch (MATH_BIGINTEGER_MODE) {
            case MATH_BIGINTEGER_MODE_GMP:
                $zn = new Math_BigInteger();
                $zn->value = gmp_and($this->value, $k5->value);
                return $this->_normalize($zn);
            case MATH_BIGINTEGER_MODE_BCMATH:
                $vm = $this->toBytes();
                $Kz = $k5->toBytes();
                $F_ = max(strlen($vm), strlen($Kz));
                $vm = str_pad($vm, $F_, chr(0), STR_PAD_LEFT);
                $Kz = str_pad($Kz, $F_, chr(0), STR_PAD_LEFT);
                return $this->_normalize(new Math_BigInteger($vm & $Kz, 256));
        }
        jO8:
        fhD:
        $DE = $this->copy();
        $F_ = min(count($k5->value), count($this->value));
        $DE->value = array_slice($DE->value, 0, $F_);
        $zY = 0;
        br6:
        if (!($zY < $F_)) {
            goto RN6;
        }
        $DE->value[$zY] &= $k5->value[$zY];
        Kyj:
        ++$zY;
        goto br6;
        RN6:
        return $this->_normalize($DE);
    }
    function bitwise_or($k5)
    {
        switch (MATH_BIGINTEGER_MODE) {
            case MATH_BIGINTEGER_MODE_GMP:
                $zn = new Math_BigInteger();
                $zn->value = gmp_or($this->value, $k5->value);
                return $this->_normalize($zn);
            case MATH_BIGINTEGER_MODE_BCMATH:
                $vm = $this->toBytes();
                $Kz = $k5->toBytes();
                $F_ = max(strlen($vm), strlen($Kz));
                $vm = str_pad($vm, $F_, chr(0), STR_PAD_LEFT);
                $Kz = str_pad($Kz, $F_, chr(0), STR_PAD_LEFT);
                return $this->_normalize(new Math_BigInteger($vm | $Kz, 256));
        }
        yh6:
        hFl:
        $F_ = max(count($this->value), count($k5->value));
        $DE = $this->copy();
        $DE->value = array_pad($DE->value, $F_, 0);
        $k5->value = array_pad($k5->value, $F_, 0);
        $zY = 0;
        Sps:
        if (!($zY < $F_)) {
            goto b56;
        }
        $DE->value[$zY] |= $k5->value[$zY];
        vcS:
        ++$zY;
        goto Sps;
        b56:
        return $this->_normalize($DE);
    }
    function bitwise_xor($k5)
    {
        switch (MATH_BIGINTEGER_MODE) {
            case MATH_BIGINTEGER_MODE_GMP:
                $zn = new Math_BigInteger();
                $zn->value = gmp_xor(gmp_abs($this->value), gmp_abs($k5->value));
                return $this->_normalize($zn);
            case MATH_BIGINTEGER_MODE_BCMATH:
                $vm = $this->toBytes();
                $Kz = $k5->toBytes();
                $F_ = max(strlen($vm), strlen($Kz));
                $vm = str_pad($vm, $F_, chr(0), STR_PAD_LEFT);
                $Kz = str_pad($Kz, $F_, chr(0), STR_PAD_LEFT);
                return $this->_normalize(new Math_BigInteger($vm ^ $Kz, 256));
        }
        hxw:
        BW1:
        $F_ = max(count($this->value), count($k5->value));
        $DE = $this->copy();
        $DE->is_negative = false;
        $DE->value = array_pad($DE->value, $F_, 0);
        $k5->value = array_pad($k5->value, $F_, 0);
        $zY = 0;
        khJ:
        if (!($zY < $F_)) {
            goto mO6;
        }
        $DE->value[$zY] ^= $k5->value[$zY];
        nos:
        ++$zY;
        goto khJ;
        mO6:
        return $this->_normalize($DE);
    }
    function bitwise_not()
    {
        $zn = $this->toBytes();
        if (!($zn == '')) {
            goto udG;
        }
        return $this->_normalize(new Math_BigInteger());
        udG:
        $eM = decbin(ord($zn[0]));
        $zn = ~$zn;
        $wx = decbin(ord($zn[0]));
        if (!(strlen($wx) == 8)) {
            goto uH0;
        }
        $wx = substr($wx, strpos($wx, "\x30"));
        uH0:
        $zn[0] = chr(bindec($wx));
        $mg = strlen($eM) + 8 * strlen($zn) - 8;
        $N_ = $this->precision - $mg;
        if (!($N_ <= 0)) {
            goto NUa;
        }
        return $this->_normalize(new Math_BigInteger($zn, 256));
        NUa:
        $wp = chr((1 << ($N_ & 0x7)) - 1) . str_repeat(chr(0xff), $N_ >> 3);
        $this->_base256_lshift($wp, $mg);
        $zn = str_pad($zn, strlen($wp), chr(0), STR_PAD_LEFT);
        return $this->_normalize(new Math_BigInteger($wp | $zn, 256));
    }
    function bitwise_rightShift($r6)
    {
        $zn = new Math_BigInteger();
        switch (MATH_BIGINTEGER_MODE) {
            case MATH_BIGINTEGER_MODE_GMP:
                static $Jg;
                if (isset($Jg)) {
                    goto gQd;
                }
                $Jg = gmp_init("\x32");
                gQd:
                $zn->value = gmp_div_q($this->value, gmp_pow($Jg, $r6));
                goto n5H;
            case MATH_BIGINTEGER_MODE_BCMATH:
                $zn->value = bcdiv($this->value, bcpow("\x32", $r6, 0), 0);
                goto n5H;
            default:
                $zn->value = $this->value;
                $zn->_rshift($r6);
        }
        Koy:
        n5H:
        return $this->_normalize($zn);
    }
    function bitwise_leftShift($r6)
    {
        $zn = new Math_BigInteger();
        switch (MATH_BIGINTEGER_MODE) {
            case MATH_BIGINTEGER_MODE_GMP:
                static $Jg;
                if (isset($Jg)) {
                    goto fFp;
                }
                $Jg = gmp_init("\x32");
                fFp:
                $zn->value = gmp_mul($this->value, gmp_pow($Jg, $r6));
                goto bDM;
            case MATH_BIGINTEGER_MODE_BCMATH:
                $zn->value = bcmul($this->value, bcpow("\x32", $r6, 0), 0);
                goto bDM;
            default:
                $zn->value = $this->value;
                $zn->_lshift($r6);
        }
        qd_:
        bDM:
        return $this->_normalize($zn);
    }
    function bitwise_leftRotate($r6)
    {
        $Xs = $this->toBytes();
        if ($this->precision > 0) {
            goto VIF;
        }
        $zn = ord($Xs[0]);
        $zY = 0;
        zB2:
        if (!($zn >> $zY)) {
            goto TsD;
        }
        Jk4:
        ++$zY;
        goto zB2;
        TsD:
        $Pi = 8 * strlen($Xs) - 8 + $zY;
        $aE = chr((1 << ($Pi & 0x7)) - 1) . str_repeat(chr(0xff), $Pi >> 3);
        goto ooo;
        VIF:
        $Pi = $this->precision;
        if (MATH_BIGINTEGER_MODE == MATH_BIGINTEGER_MODE_BCMATH) {
            goto b2u;
        }
        $aE = $this->bitmask->toBytes();
        goto cJ7;
        b2u:
        $aE = $this->bitmask->subtract(new Math_BigInteger(1));
        $aE = $aE->toBytes();
        cJ7:
        ooo:
        if (!($r6 < 0)) {
            goto eDz;
        }
        $r6 += $Pi;
        eDz:
        $r6 %= $Pi;
        if ($r6) {
            goto E4K;
        }
        return $this->copy();
        E4K:
        $vm = $this->bitwise_leftShift($r6);
        $vm = $vm->bitwise_and(new Math_BigInteger($aE, 256));
        $Kz = $this->bitwise_rightShift($Pi - $r6);
        $DE = MATH_BIGINTEGER_MODE != MATH_BIGINTEGER_MODE_BCMATH ? $vm->bitwise_or($Kz) : $vm->add($Kz);
        return $this->_normalize($DE);
    }
    function bitwise_rightRotate($r6)
    {
        return $this->bitwise_leftRotate(-$r6);
    }
    function setRandomGenerator($Ok)
    {
    }
    function _random_number_helper($d7)
    {
        if (function_exists("\x63\162\x79\160\164\137\x72\x61\156\x64\157\x6d\137\163\x74\162\151\x6e\147")) {
            goto gRS;
        }
        $nd = '';
        if (!($d7 & 1)) {
            goto mmR;
        }
        $nd .= chr(mt_rand(0, 255));
        mmR:
        $S2 = $d7 >> 1;
        $zY = 0;
        gsP:
        if (!($zY < $S2)) {
            goto E2o;
        }
        $nd .= pack("\156", mt_rand(0, 0xffff));
        ie6:
        ++$zY;
        goto gsP;
        E2o:
        goto Fs3;
        gRS:
        $nd = crypt_random_string($d7);
        Fs3:
        return new Math_BigInteger($nd, 256);
    }
    function random($Eu, $LU = false)
    {
        if (!($Eu === false)) {
            goto WYS;
        }
        return false;
        WYS:
        if ($LU === false) {
            goto jy6;
        }
        $sr = $Eu;
        $HK = $LU;
        goto o8d;
        jy6:
        $HK = $Eu;
        $sr = $this;
        o8d:
        $sV = $HK->compare($sr);
        if (!$sV) {
            goto pff;
        }
        if ($sV < 0) {
            goto nyG;
        }
        goto Emm;
        pff:
        return $this->_normalize($sr);
        goto Emm;
        nyG:
        $zn = $HK;
        $HK = $sr;
        $sr = $zn;
        Emm:
        static $fb;
        if (isset($fb)) {
            goto W1C;
        }
        $fb = new Math_BigInteger(1);
        W1C:
        $HK = $HK->subtract($sr->subtract($fb));
        $d7 = strlen(ltrim($HK->toBytes(), chr(0)));
        $QJ = new Math_BigInteger(chr(1) . str_repeat("\0", $d7), 256);
        $nd = $this->_random_number_helper($d7);
        list($pQ) = $QJ->divide($HK);
        $pQ = $pQ->multiply($HK);
        T1b:
        if (!($nd->compare($pQ) >= 0)) {
            goto U5e;
        }
        $nd = $nd->subtract($pQ);
        $QJ = $QJ->subtract($pQ);
        $nd = $nd->bitwise_leftShift(8);
        $nd = $nd->add($this->_random_number_helper(1));
        $QJ = $QJ->bitwise_leftShift(8);
        list($pQ) = $QJ->divide($HK);
        $pQ = $pQ->multiply($HK);
        goto T1b;
        U5e:
        list(, $nd) = $nd->divide($HK);
        return $this->_normalize($nd->add($sr));
    }
    function randomPrime($Eu, $LU = false, $Lz = false)
    {
        if (!($Eu === false)) {
            goto RLk;
        }
        return false;
        RLk:
        if ($LU === false) {
            goto dD7;
        }
        $sr = $Eu;
        $HK = $LU;
        goto JWn;
        dD7:
        $HK = $Eu;
        $sr = $this;
        JWn:
        $sV = $HK->compare($sr);
        if (!$sV) {
            goto KU0;
        }
        if ($sV < 0) {
            goto HwQ;
        }
        goto lSq;
        KU0:
        return $sr->isPrime() ? $sr : false;
        goto lSq;
        HwQ:
        $zn = $HK;
        $HK = $sr;
        $sr = $zn;
        lSq:
        static $fb, $Jg;
        if (isset($fb)) {
            goto wuu;
        }
        $fb = new Math_BigInteger(1);
        $Jg = new Math_BigInteger(2);
        wuu:
        $Kl = time();
        $k5 = $this->random($sr, $HK);
        if (!(MATH_BIGINTEGER_MODE == MATH_BIGINTEGER_MODE_GMP && extension_loaded("\x67\155\160") && version_compare(PHP_VERSION, "\x35\x2e\x32\56\60", "\x3e\x3d"))) {
            goto jsJ;
        }
        $Vm = new Math_BigInteger();
        $Vm->value = gmp_nextprime($k5->value);
        if (!($Vm->compare($HK) <= 0)) {
            goto CMm;
        }
        return $Vm;
        CMm:
        if ($sr->equals($k5)) {
            goto rYa;
        }
        $k5 = $k5->subtract($fb);
        rYa:
        return $k5->randomPrime($sr, $k5);
        jsJ:
        if (!$k5->equals($Jg)) {
            goto zww;
        }
        return $k5;
        zww:
        $k5->_make_odd();
        if (!($k5->compare($HK) > 0)) {
            goto rug;
        }
        if (!$sr->equals($HK)) {
            goto o2I;
        }
        return false;
        o2I:
        $k5 = $sr->copy();
        $k5->_make_odd();
        rug:
        $FH = $k5->copy();
        mWk:
        if (!true) {
            goto vhi;
        }
        if (!($Lz !== false && time() - $Kl > $Lz)) {
            goto Xe1;
        }
        return false;
        Xe1:
        if (!$k5->isPrime()) {
            goto TtX;
        }
        return $k5;
        TtX:
        $k5 = $k5->add($Jg);
        if (!($k5->compare($HK) > 0)) {
            goto wkx;
        }
        $k5 = $sr->copy();
        if (!$k5->equals($Jg)) {
            goto xFL;
        }
        return $k5;
        xFL:
        $k5->_make_odd();
        wkx:
        if (!$k5->equals($FH)) {
            goto J1i;
        }
        return false;
        J1i:
        goto mWk;
        vhi:
    }
    function _make_odd()
    {
        switch (MATH_BIGINTEGER_MODE) {
            case MATH_BIGINTEGER_MODE_GMP:
                gmp_setbit($this->value, 0);
                goto yoP;
            case MATH_BIGINTEGER_MODE_BCMATH:
                if (!($this->value[strlen($this->value) - 1] % 2 == 0)) {
                    goto FgF;
                }
                $this->value = bcadd($this->value, "\x31");
                FgF:
                goto yoP;
            default:
                $this->value[0] |= 1;
        }
        xJo:
        yoP:
    }
    function isPrime($sl = false)
    {
        $F_ = strlen($this->toBytes());
        if ($sl) {
            goto bTd;
        }
        if ($F_ >= 163) {
            goto fsM;
        }
        if ($F_ >= 106) {
            goto yYS;
        }
        if ($F_ >= 81) {
            goto dAy;
        }
        if ($F_ >= 68) {
            goto WxE;
        }
        if ($F_ >= 56) {
            goto B4R;
        }
        if ($F_ >= 50) {
            goto iVO;
        }
        if ($F_ >= 43) {
            goto zcm;
        }
        if ($F_ >= 37) {
            goto TTe;
        }
        if ($F_ >= 31) {
            goto No7;
        }
        if ($F_ >= 25) {
            goto eqf;
        }
        if ($F_ >= 18) {
            goto RP2;
        }
        $sl = 27;
        goto jgX;
        RP2:
        $sl = 18;
        jgX:
        goto i_g;
        eqf:
        $sl = 15;
        i_g:
        goto osQ;
        No7:
        $sl = 12;
        osQ:
        goto y6z;
        TTe:
        $sl = 9;
        y6z:
        goto VlS;
        zcm:
        $sl = 8;
        VlS:
        goto ltd;
        iVO:
        $sl = 7;
        ltd:
        goto asX;
        B4R:
        $sl = 6;
        asX:
        goto RlG;
        WxE:
        $sl = 5;
        RlG:
        goto Bdy;
        dAy:
        $sl = 4;
        Bdy:
        goto YFb;
        yYS:
        $sl = 3;
        YFb:
        goto w21;
        fsM:
        $sl = 2;
        w21:
        bTd:
        switch (MATH_BIGINTEGER_MODE) {
            case MATH_BIGINTEGER_MODE_GMP:
                return gmp_prob_prime($this->value, $sl) != 0;
            case MATH_BIGINTEGER_MODE_BCMATH:
                if (!($this->value === "\62")) {
                    goto rla;
                }
                return true;
                rla:
                if (!($this->value[strlen($this->value) - 1] % 2 == 0)) {
                    goto bBX;
                }
                return false;
                bBX:
                goto sec;
            default:
                if (!($this->value == array(2))) {
                    goto pgB;
                }
                return true;
                pgB:
                if (!(~$this->value[0] & 1)) {
                    goto piA;
                }
                return false;
                piA:
        }
        kIG:
        sec:
        static $F3, $Ep, $fb, $Jg;
        if (isset($F3)) {
            goto nJJ;
        }
        $F3 = array(3, 5, 7, 11, 13, 17, 19, 23, 29, 31, 37, 41, 43, 47, 53, 59, 61, 67, 71, 73, 79, 83, 89, 97, 101, 103, 107, 109, 113, 127, 131, 137, 139, 149, 151, 157, 163, 167, 173, 179, 181, 191, 193, 197, 199, 211, 223, 227, 229, 233, 239, 241, 251, 257, 263, 269, 271, 277, 281, 283, 293, 307, 311, 313, 317, 331, 337, 347, 349, 353, 359, 367, 373, 379, 383, 389, 397, 401, 409, 419, 421, 431, 433, 439, 443, 449, 457, 461, 463, 467, 479, 487, 491, 499, 503, 509, 521, 523, 541, 547, 557, 563, 569, 571, 577, 587, 593, 599, 601, 607, 613, 617, 619, 631, 641, 643, 647, 653, 659, 661, 673, 677, 683, 691, 701, 709, 719, 727, 733, 739, 743, 751, 757, 761, 769, 773, 787, 797, 809, 811, 821, 823, 827, 829, 839, 853, 857, 859, 863, 877, 881, 883, 887, 907, 911, 919, 929, 937, 941, 947, 953, 967, 971, 977, 983, 991, 997);
        if (!(MATH_BIGINTEGER_MODE != MATH_BIGINTEGER_MODE_INTERNAL)) {
            goto UDB;
        }
        $zY = 0;
        glM:
        if (!($zY < count($F3))) {
            goto FlG;
        }
        $F3[$zY] = new Math_BigInteger($F3[$zY]);
        GUD:
        ++$zY;
        goto glM;
        FlG:
        UDB:
        $Ep = new Math_BigInteger();
        $fb = new Math_BigInteger(1);
        $Jg = new Math_BigInteger(2);
        nJJ:
        if (!$this->equals($fb)) {
            goto aPQ;
        }
        return false;
        aPQ:
        if (MATH_BIGINTEGER_MODE != MATH_BIGINTEGER_MODE_INTERNAL) {
            goto zFW;
        }
        $t_ = $this->value;
        foreach ($F3 as $jb) {
            list(, $lO) = $this->_divide_digit($t_, $jb);
            if ($lO) {
                goto aYp;
            }
            return count($t_) == 1 && $t_[0] == $jb;
            aYp:
            Wfn:
        }
        CqF:
        goto V5q;
        zFW:
        foreach ($F3 as $jb) {
            list(, $lO) = $this->divide($jb);
            if (!$lO->equals($Ep)) {
                goto mM7;
            }
            return $this->equals($jb);
            mM7:
            RJ8:
        }
        fSr:
        V5q:
        $Bd = $this->copy();
        $OA = $Bd->subtract($fb);
        $Zf = $Bd->subtract($Jg);
        $lO = $OA->copy();
        $jf = $lO->value;
        if (MATH_BIGINTEGER_MODE == MATH_BIGINTEGER_MODE_BCMATH) {
            goto Fvk;
        }
        $zY = 0;
        $JC = count($jf);
        CIo:
        if (!($zY < $JC)) {
            goto val;
        }
        $zn = ~$jf[$zY] & 0xffffff;
        $cu = 1;
        aA2:
        if (!($zn >> $cu & 1)) {
            goto NaT;
        }
        d0d:
        ++$cu;
        goto aA2;
        NaT:
        if (!($cu != 25)) {
            goto fwY;
        }
        goto val;
        fwY:
        aIv:
        ++$zY;
        goto CIo;
        val:
        $Z7 = 26 * $zY + $cu;
        $lO->_rshift($Z7);
        goto eqi;
        Fvk:
        $Z7 = 0;
        QVc:
        if (!($lO->value[strlen($lO->value) - 1] % 2 == 0)) {
            goto V3f;
        }
        $lO->value = bcdiv($lO->value, "\62", 0);
        ++$Z7;
        goto QVc;
        V3f:
        eqi:
        $zY = 0;
        zcw:
        if (!($zY < $sl)) {
            goto qiz;
        }
        $hP = $this->random($Jg, $Zf);
        $fZ = $hP->modPow($lO, $Bd);
        if (!(!$fZ->equals($fb) && !$fZ->equals($OA))) {
            goto gdO;
        }
        $cu = 1;
        tYm:
        if (!($cu < $Z7 && !$fZ->equals($OA))) {
            goto Jne;
        }
        $fZ = $fZ->modPow($Jg, $Bd);
        if (!$fZ->equals($fb)) {
            goto NYY;
        }
        return false;
        NYY:
        ulH:
        ++$cu;
        goto tYm;
        Jne:
        if ($fZ->equals($OA)) {
            goto Ypl;
        }
        return false;
        Ypl:
        gdO:
        lbo:
        ++$zY;
        goto zcw;
        qiz:
        return true;
    }
    function _lshift($r6)
    {
        if (!($r6 == 0)) {
            goto fk0;
        }
        return;
        fk0:
        $EB = (int) ($r6 / MATH_BIGINTEGER_BASE);
        $r6 %= MATH_BIGINTEGER_BASE;
        $r6 = 1 << $r6;
        $jc = 0;
        $zY = 0;
        Qg4:
        if (!($zY < count($this->value))) {
            goto xyI;
        }
        $zn = $this->value[$zY] * $r6 + $jc;
        $jc = MATH_BIGINTEGER_BASE === 26 ? intval($zn / 0x4000000) : $zn >> 31;
        $this->value[$zY] = (int) ($zn - $jc * MATH_BIGINTEGER_BASE_FULL);
        uiO:
        ++$zY;
        goto Qg4;
        xyI:
        if (!$jc) {
            goto yGB;
        }
        $this->value[count($this->value)] = $jc;
        yGB:
        faA:
        if (!$EB--) {
            goto lVk;
        }
        array_unshift($this->value, 0);
        goto faA;
        lVk:
    }
    function _rshift($r6)
    {
        if (!($r6 == 0)) {
            goto ZqM;
        }
        return;
        ZqM:
        $EB = (int) ($r6 / MATH_BIGINTEGER_BASE);
        $r6 %= MATH_BIGINTEGER_BASE;
        $VV = MATH_BIGINTEGER_BASE - $r6;
        $IF = (1 << $r6) - 1;
        if (!$EB) {
            goto TYE;
        }
        $this->value = array_slice($this->value, $EB);
        TYE:
        $jc = 0;
        $zY = count($this->value) - 1;
        O7I:
        if (!($zY >= 0)) {
            goto fJJ;
        }
        $zn = $this->value[$zY] >> $r6 | $jc;
        $jc = ($this->value[$zY] & $IF) << $VV;
        $this->value[$zY] = $zn;
        LtX:
        --$zY;
        goto O7I;
        fJJ:
        $this->value = $this->_trim($this->value);
    }
    function _normalize($DE)
    {
        $DE->precision = $this->precision;
        $DE->bitmask = $this->bitmask;
        switch (MATH_BIGINTEGER_MODE) {
            case MATH_BIGINTEGER_MODE_GMP:
                if (!($this->bitmask !== false)) {
                    goto rQz;
                }
                $DE->value = gmp_and($DE->value, $DE->bitmask->value);
                rQz:
                return $DE;
            case MATH_BIGINTEGER_MODE_BCMATH:
                if (empty($DE->bitmask->value)) {
                    goto fS5;
                }
                $DE->value = bcmod($DE->value, $DE->bitmask->value);
                fS5:
                return $DE;
        }
        KHN:
        iFU:
        $t_ =& $DE->value;
        if (count($t_)) {
            goto jC6;
        }
        return $DE;
        jC6:
        $t_ = $this->_trim($t_);
        if (empty($DE->bitmask->value)) {
            goto tTm;
        }
        $F_ = min(count($t_), count($this->bitmask->value));
        $t_ = array_slice($t_, 0, $F_);
        $zY = 0;
        Czb:
        if (!($zY < $F_)) {
            goto cgC;
        }
        $t_[$zY] = $t_[$zY] & $this->bitmask->value[$zY];
        W9n:
        ++$zY;
        goto Czb;
        cgC:
        tTm:
        return $DE;
    }
    function _trim($t_)
    {
        $zY = count($t_) - 1;
        DTC:
        if (!($zY >= 0)) {
            goto fjj;
        }
        if (!$t_[$zY]) {
            goto RBW;
        }
        goto fjj;
        RBW:
        unset($t_[$zY]);
        gaK:
        --$zY;
        goto DTC;
        fjj:
        return $t_;
    }
    function _array_repeat($AZ, $bW)
    {
        return $bW ? array_fill(0, $bW, $AZ) : array();
    }
    function _base256_lshift(&$k5, $r6)
    {
        if (!($r6 == 0)) {
            goto wYV;
        }
        return;
        wYV:
        $oU = $r6 >> 3;
        $r6 &= 7;
        $jc = 0;
        $zY = strlen($k5) - 1;
        VoS:
        if (!($zY >= 0)) {
            goto ZEe;
        }
        $zn = ord($k5[$zY]) << $r6 | $jc;
        $k5[$zY] = chr($zn);
        $jc = $zn >> 8;
        c6r:
        --$zY;
        goto VoS;
        ZEe:
        $jc = $jc != 0 ? chr($jc) : '';
        $k5 = $jc . $k5 . str_repeat(chr(0), $oU);
    }
    function _base256_rshift(&$k5, $r6)
    {
        if (!($r6 == 0)) {
            goto MYT;
        }
        $k5 = ltrim($k5, chr(0));
        return '';
        MYT:
        $oU = $r6 >> 3;
        $r6 &= 7;
        $la = '';
        if (!$oU) {
            goto Vid;
        }
        $Kl = $oU > strlen($k5) ? -strlen($k5) : -$oU;
        $la = substr($k5, $Kl);
        $k5 = substr($k5, 0, -$oU);
        Vid:
        $jc = 0;
        $VV = 8 - $r6;
        $zY = 0;
        kkR:
        if (!($zY < strlen($k5))) {
            goto p_7;
        }
        $zn = ord($k5[$zY]) >> $r6 | $jc;
        $jc = ord($k5[$zY]) << $VV & 0xff;
        $k5[$zY] = chr($zn);
        OOA:
        ++$zY;
        goto kkR;
        p_7:
        $k5 = ltrim($k5, chr(0));
        $la = chr($jc >> $VV) . $la;
        return ltrim($la, chr(0));
    }
    function _int2bytes($k5)
    {
        return ltrim(pack("\116", $k5), chr(0));
    }
    function _bytes2int($k5)
    {
        $zn = unpack("\116\x69\x6e\x74", str_pad($k5, 4, chr(0), STR_PAD_LEFT));
        return $zn["\x69\x6e\x74"];
    }
    function _encodeASN1Length($F_)
    {
        if (!($F_ <= 0x7f)) {
            goto dgN;
        }
        return chr($F_);
        dgN:
        $zn = ltrim(pack("\116", $F_), chr(0));
        return pack("\103\141\x2a", 0x80 | strlen($zn), $zn);
    }
    function _safe_divide($k5, $fZ)
    {
        if (!(MATH_BIGINTEGER_BASE === 26)) {
            goto LV1;
        }
        return (int) ($k5 / $fZ);
        LV1:
        return ($k5 - $k5 % $fZ) / $fZ;
    }
}
