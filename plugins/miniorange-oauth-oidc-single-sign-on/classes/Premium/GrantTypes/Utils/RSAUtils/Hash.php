<?php


namespace MoOauthClient\GrantTypes;

define("\103\122\131\x50\x54\x5f\x48\x41\123\110\x5f\x4d\117\x44\x45\x5f\111\x4e\x54\105\x52\116\x41\114", 1);
define("\103\x52\x59\x50\x54\x5f\x48\x41\123\110\x5f\x4d\x4f\104\x45\x5f\115\x48\x41\x53\110", 2);
define("\103\x52\x59\120\x54\x5f\x48\101\x53\110\x5f\x4d\117\x44\105\x5f\110\101\123\x48", 3);
class Crypt_Hash
{
    var $hashParam;
    var $b;
    var $l = false;
    var $hash;
    var $key = false;
    var $opad;
    var $ipad;
    function __construct($H2 = "\163\150\141\x31")
    {
        if (defined("\103\x52\131\x50\x54\x5f\110\101\123\x48\x5f\115\x4f\104\105")) {
            goto oo;
        }
        switch (true) {
            case extension_loaded("\150\x61\x73\150"):
                define("\103\122\x59\120\124\x5f\x48\101\x53\110\x5f\115\x4f\x44\105", CRYPT_HASH_MODE_HASH);
                goto zP;
            case extension_loaded("\x6d\150\x61\x73\150"):
                define("\x43\122\131\x50\124\x5f\x48\101\x53\x48\x5f\x4d\117\x44\105", CRYPT_HASH_MODE_MHASH);
                goto zP;
            default:
                define("\103\122\131\x50\124\x5f\x48\101\x53\110\137\x4d\117\x44\105", CRYPT_HASH_MODE_INTERNAL);
        }
        vP:
        zP:
        oo:
        $this->setHash($H2);
    }
    function Crypt_Hash($H2 = "\x73\150\141\x31")
    {
        $this->__construct($H2);
    }
    function setKey($Mr = false)
    {
        $this->key = $Mr;
    }
    function getHash()
    {
        return $this->hashParam;
    }
    function setHash($H2)
    {
        $this->hashParam = $H2 = strtolower($H2);
        switch ($H2) {
            case "\155\144\65\55\x39\x36":
            case "\163\x68\x61\61\x2d\71\x36":
            case "\163\x68\141\62\x35\66\x2d\x39\x36":
            case "\x73\150\141\65\61\x32\55\71\x36":
                $H2 = substr($H2, 0, -3);
                $this->l = 12;
                goto Ji;
            case "\155\x64\62":
            case "\155\x64\x35":
                $this->l = 16;
                goto Ji;
            case "\x73\150\141\x31":
                $this->l = 20;
                goto Ji;
            case "\x73\150\141\x32\x35\66":
                $this->l = 32;
                goto Ji;
            case "\163\150\141\63\x38\x34":
                $this->l = 48;
                goto Ji;
            case "\x73\150\141\x35\x31\62":
                $this->l = 64;
        }
        Z9:
        Ji:
        switch ($H2) {
            case "\x6d\x64\62":
                $pU = CRYPT_HASH_MODE == CRYPT_HASH_MODE_HASH && in_array("\x6d\144\62", hash_algos()) ? CRYPT_HASH_MODE_HASH : CRYPT_HASH_MODE_INTERNAL;
                goto Z2;
            case "\x73\150\x61\x33\70\64":
            case "\x73\150\141\x35\x31\62":
                $pU = CRYPT_HASH_MODE == CRYPT_HASH_MODE_MHASH ? CRYPT_HASH_MODE_INTERNAL : CRYPT_HASH_MODE;
                goto Z2;
            default:
                $pU = CRYPT_HASH_MODE;
        }
        Wc:
        Z2:
        switch ($pU) {
            case CRYPT_HASH_MODE_MHASH:
                switch ($H2) {
                    case "\x6d\x64\x35":
                        $this->hash = MHASH_MD5;
                        goto oz;
                    case "\x73\150\141\x32\x35\x36":
                        $this->hash = MHASH_SHA256;
                        goto oz;
                    case "\163\x68\141\61":
                    default:
                        $this->hash = MHASH_SHA1;
                }
                MC:
                oz:
                return;
            case CRYPT_HASH_MODE_HASH:
                switch ($H2) {
                    case "\155\144\65":
                        $this->hash = "\155\x64\65";
                        return;
                    case "\155\x64\x32":
                    case "\x73\150\141\x32\x35\66":
                    case "\163\x68\x61\63\x38\x34":
                    case "\163\x68\x61\x35\x31\62":
                        $this->hash = $H2;
                        return;
                    case "\163\150\141\x31":
                    default:
                        $this->hash = "\163\x68\x61\x31";
                }
                HT:
                Vj:
                return;
        }
        CJ:
        Tj:
        switch ($H2) {
            case "\x6d\144\x32":
                $this->b = 16;
                $this->hash = array($this, "\137\155\x64\x32");
                goto mi;
            case "\155\144\65":
                $this->b = 64;
                $this->hash = array($this, "\137\155\x64\65");
                goto mi;
            case "\x73\x68\x61\x32\65\66":
                $this->b = 64;
                $this->hash = array($this, "\x5f\x73\150\x61\x32\65\66");
                goto mi;
            case "\163\150\x61\63\x38\64":
            case "\x73\x68\x61\x35\61\62":
                $this->b = 128;
                $this->hash = array($this, "\x5f\x73\x68\x61\x35\61\x32");
                goto mi;
            case "\163\150\x61\61":
            default:
                $this->b = 64;
                $this->hash = array($this, "\137\x73\150\x61\x31");
        }
        H5:
        mi:
        $this->ipad = str_repeat(chr(0x36), $this->b);
        $this->opad = str_repeat(chr(0x5c), $this->b);
    }
    function hash($UH)
    {
        $pU = is_array($this->hash) ? CRYPT_HASH_MODE_INTERNAL : CRYPT_HASH_MODE;
        if (!empty($this->key) || is_string($this->key)) {
            goto gm;
        }
        switch ($pU) {
            case CRYPT_HASH_MODE_MHASH:
                $Rv = mhash($this->hash, $UH);
                goto Xm;
            case CRYPT_HASH_MODE_HASH:
                $Rv = hash($this->hash, $UH, true);
                goto Xm;
            case CRYPT_HASH_MODE_INTERNAL:
                $Rv = call_user_func($this->hash, $UH);
        }
        uL:
        Xm:
        goto oc;
        gm:
        switch ($pU) {
            case CRYPT_HASH_MODE_MHASH:
                $Rv = mhash($this->hash, $UH, $this->key);
                goto Qc;
            case CRYPT_HASH_MODE_HASH:
                $Rv = hash_hmac($this->hash, $UH, $this->key, true);
                goto Qc;
            case CRYPT_HASH_MODE_INTERNAL:
                $Mr = strlen($this->key) > $this->b ? call_user_func($this->hash, $this->key) : $this->key;
                $Mr = str_pad($Mr, $this->b, chr(0));
                $zn = $this->ipad ^ $Mr;
                $zn .= $UH;
                $zn = call_user_func($this->hash, $zn);
                $Rv = $this->opad ^ $Mr;
                $Rv .= $zn;
                $Rv = call_user_func($this->hash, $Rv);
        }
        m9:
        Qc:
        oc:
        return substr($Rv, 0, $this->l);
    }
    function getLength()
    {
        return $this->l;
    }
    function _md5($X3)
    {
        return pack("\110\x2a", md5($X3));
    }
    function _sha1($X3)
    {
        return pack("\x48\52", sha1($X3));
    }
    function _md2($X3)
    {
        static $Z7 = array(41, 46, 67, 201, 162, 216, 124, 1, 61, 54, 84, 161, 236, 240, 6, 19, 98, 167, 5, 243, 192, 199, 115, 140, 152, 147, 43, 217, 188, 76, 130, 202, 30, 155, 87, 60, 253, 212, 224, 22, 103, 66, 111, 24, 138, 23, 229, 18, 190, 78, 196, 214, 218, 158, 222, 73, 160, 251, 245, 142, 187, 47, 238, 122, 169, 104, 121, 145, 21, 178, 7, 63, 148, 194, 16, 137, 11, 34, 95, 33, 128, 127, 93, 154, 90, 144, 50, 39, 53, 62, 204, 231, 191, 247, 151, 3, 255, 25, 48, 179, 72, 165, 181, 209, 215, 94, 146, 42, 172, 86, 170, 198, 79, 184, 56, 210, 150, 164, 125, 182, 118, 252, 107, 226, 156, 116, 4, 241, 69, 157, 112, 89, 100, 113, 135, 32, 134, 91, 207, 101, 230, 45, 168, 2, 27, 96, 37, 173, 174, 176, 185, 246, 28, 70, 97, 105, 52, 64, 126, 15, 85, 71, 163, 35, 221, 81, 175, 58, 195, 92, 249, 206, 186, 197, 234, 38, 44, 83, 13, 110, 133, 40, 132, 9, 211, 223, 205, 244, 65, 129, 77, 82, 106, 220, 55, 200, 108, 193, 171, 250, 36, 225, 123, 8, 12, 189, 177, 74, 120, 136, 149, 139, 227, 99, 232, 109, 233, 203, 213, 254, 59, 0, 29, 57, 242, 239, 183, 14, 102, 88, 208, 228, 166, 119, 114, 248, 235, 117, 75, 10, 49, 68, 80, 180, 143, 237, 31, 26, 219, 153, 141, 51, 159, 17, 131, 20);
        $ql = 16 - (strlen($X3) & 0xf);
        $X3 .= str_repeat(chr($ql), $ql);
        $F_ = strlen($X3);
        $eo = str_repeat(chr(0), 16);
        $O7 = chr(0);
        $zY = 0;
        Tc:
        if (!($zY < $F_)) {
            goto Qs;
        }
        $cu = 0;
        FO:
        if (!($cu < 16)) {
            goto Pn;
        }
        $eo[$cu] = chr($Z7[ord($X3[$zY + $cu] ^ $O7)] ^ ord($eo[$cu]));
        $O7 = $eo[$cu];
        Rl:
        $cu++;
        goto FO;
        Pn:
        pY:
        $zY += 16;
        goto Tc;
        Qs:
        $X3 .= $eo;
        $F_ += 16;
        $k5 = str_repeat(chr(0), 48);
        $zY = 0;
        eb:
        if (!($zY < $F_)) {
            goto nh;
        }
        $cu = 0;
        Hm:
        if (!($cu < 16)) {
            goto n1;
        }
        $k5[$cu + 16] = $X3[$zY + $cu];
        $k5[$cu + 32] = $k5[$cu + 16] ^ $k5[$cu];
        Kh:
        $cu++;
        goto Hm;
        n1:
        $sl = chr(0);
        $cu = 0;
        bX:
        if (!($cu < 18)) {
            goto BA;
        }
        $Wu = 0;
        v6:
        if (!($Wu < 48)) {
            goto hI;
        }
        $k5[$Wu] = $sl = $k5[$Wu] ^ chr($Z7[ord($sl)]);
        lX:
        $Wu++;
        goto v6;
        hI:
        $sl = chr(ord($sl) + $cu);
        mT:
        $cu++;
        goto bX;
        BA:
        iP:
        $zY += 16;
        goto eb;
        nh:
        return substr($k5, 0, 16);
    }
    function _sha256($X3)
    {
        if (!extension_loaded("\163\165\150\x6f\x73\x69\x6e")) {
            goto Eo;
        }
        return pack("\110\52", sha256($X3));
        Eo:
        $H2 = array(0x6a09e667, 0xbb67ae85, 0x3c6ef372, 0xa54ff53a, 0x510e527f, 0x9b05688c, 0x1f83d9ab, 0x5be0cd19);
        static $Wu = array(0x428a2f98, 0x71374491, 0xb5c0fbcf, 0xe9b5dba5, 0x3956c25b, 0x59f111f1, 0x923f82a4, 0xab1c5ed5, 0xd807aa98, 0x12835b01, 0x243185be, 0x550c7dc3, 0x72be5d74, 0x80deb1fe, 0x9bdc06a7, 0xc19bf174, 0xe49b69c1, 0xefbe4786, 0xfc19dc6, 0x240ca1cc, 0x2de92c6f, 0x4a7484aa, 0x5cb0a9dc, 0x76f988da, 0x983e5152, 0xa831c66d, 0xb00327c8, 0xbf597fc7, 0xc6e00bf3, 0xd5a79147, 0x6ca6351, 0x14292967, 0x27b70a85, 0x2e1b2138, 0x4d2c6dfc, 0x53380d13, 0x650a7354, 0x766a0abb, 0x81c2c92e, 0x92722c85, 0xa2bfe8a1, 0xa81a664b, 0xc24b8b70, 0xc76c51a3, 0xd192e819, 0xd6990624, 0xf40e3585, 0x106aa070, 0x19a4c116, 0x1e376c08, 0x2748774c, 0x34b0bcb5, 0x391c0cb3, 0x4ed8aa4a, 0x5b9cca4f, 0x682e6ff3, 0x748f82ee, 0x78a5636f, 0x84c87814, 0x8cc70208, 0x90befffa, 0xa4506ceb, 0xbef9a3f7, 0xc67178f2);
        $F_ = strlen($X3);
        $X3 .= str_repeat(chr(0), 64 - ($F_ + 8 & 0x3f));
        $X3[$F_] = chr(0x80);
        $X3 .= pack("\116\62", 0, $F_ << 3);
        $Dl = str_split($X3, 64);
        foreach ($Dl as $cB) {
            $j0 = array();
            $zY = 0;
            la:
            if (!($zY < 16)) {
                goto Iy;
            }
            extract(unpack("\x4e\164\x65\x6d\x70", $this->_string_shift($cB, 4)));
            $j0[] = $zn;
            YJ:
            $zY++;
            goto la;
            Iy:
            $zY = 16;
            nV:
            if (!($zY < 64)) {
                goto yo;
            }
            $OS = $this->_rightRotate($j0[$zY - 15], 7) ^ $this->_rightRotate($j0[$zY - 15], 18) ^ $this->_rightShift($j0[$zY - 15], 3);
            $tc = $this->_rightRotate($j0[$zY - 2], 17) ^ $this->_rightRotate($j0[$zY - 2], 19) ^ $this->_rightShift($j0[$zY - 2], 10);
            $j0[$zY] = $this->_add($j0[$zY - 16], $OS, $j0[$zY - 7], $tc);
            rJ:
            $zY++;
            goto nV;
            yo:
            list($hP, $fM, $eo, $qa, $mP, $du, $Sm, $AX) = $H2;
            $zY = 0;
            Rz:
            if (!($zY < 64)) {
                goto JI;
            }
            $OS = $this->_rightRotate($hP, 2) ^ $this->_rightRotate($hP, 13) ^ $this->_rightRotate($hP, 22);
            $oK = $hP & $fM ^ $hP & $eo ^ $fM & $eo;
            $s9 = $this->_add($OS, $oK);
            $tc = $this->_rightRotate($mP, 6) ^ $this->_rightRotate($mP, 11) ^ $this->_rightRotate($mP, 25);
            $bV = $mP & $du ^ $this->_not($mP) & $Sm;
            $Sx = $this->_add($AX, $tc, $bV, $Wu[$zY], $j0[$zY]);
            $AX = $Sm;
            $Sm = $du;
            $du = $mP;
            $mP = $this->_add($qa, $Sx);
            $qa = $eo;
            $eo = $fM;
            $fM = $hP;
            $hP = $this->_add($Sx, $s9);
            Jy:
            $zY++;
            goto Rz;
            JI:
            $H2 = array($this->_add($H2[0], $hP), $this->_add($H2[1], $fM), $this->_add($H2[2], $eo), $this->_add($H2[3], $qa), $this->_add($H2[4], $mP), $this->_add($H2[5], $du), $this->_add($H2[6], $Sm), $this->_add($H2[7], $AX));
            kf:
        }
        kU:
        return pack("\116\70", $H2[0], $H2[1], $H2[2], $H2[3], $H2[4], $H2[5], $H2[6], $H2[7]);
    }
    function _sha512($X3)
    {
        if (class_exists("\115\x61\164\150\x5f\x42\151\x67\x49\156\x74\145\x67\x65\162")) {
            goto E9;
        }
        include_once "\115\x61\164\x68\x2f\x42\x69\147\111\156\164\x65\x67\145\x72\x2e\160\150\x70";
        E9:
        static $Xl, $Rk, $Wu;
        if (isset($Wu)) {
            goto Ix;
        }
        $Xl = array("\143\x62\142\x62\71\x64\65\144\143\61\x30\x35\71\x65\144\70", "\66\x32\x39\x61\x32\71\62\141\x33\x36\67\143\x64\x35\x30\67", "\71\x31\x35\x39\x30\x31\x35\141\63\60\x37\x30\144\144\x31\x37", "\61\65\x32\x66\x65\143\144\x38\146\67\60\x65\65\x39\x33\x39", "\x36\x37\x33\x33\62\x36\66\67\x66\146\143\x30\x30\142\63\x31", "\x38\x65\142\64\64\141\70\67\x36\70\x35\70\x31\x35\61\x31", "\144\x62\60\143\x32\145\60\144\x36\x34\146\x39\x38\x66\x61\x37", "\64\x37\142\65\x34\70\x31\x64\142\145\146\141\x34\x66\141\64");
        $Rk = array("\66\141\60\x39\145\66\66\x37\x66\x33\x62\143\143\x39\60\70", "\142\x62\66\67\x61\x65\70\65\70\64\143\141\141\x37\x33\x62", "\x33\x63\x36\x65\x66\63\x37\x32\146\145\x39\x34\146\x38\x32\142", "\x61\x35\64\x66\146\65\63\141\x35\x66\61\x64\x33\x36\x66\61", "\65\61\x30\x65\x35\x32\67\x66\x61\144\145\66\70\62\x64\x31", "\71\x62\60\x35\x36\x38\x38\143\62\x62\x33\145\66\143\61\146", "\61\x66\70\x33\x64\x39\x61\x62\146\x62\x34\61\x62\144\66\x62", "\65\x62\145\60\x63\144\x31\x39\x31\x33\x37\x65\62\61\67\71");
        $zY = 0;
        hC:
        if (!($zY < 8)) {
            goto B_;
        }
        $Xl[$zY] = new Math_BigInteger($Xl[$zY], 16);
        $Xl[$zY]->setPrecision(64);
        $Rk[$zY] = new Math_BigInteger($Rk[$zY], 16);
        $Rk[$zY]->setPrecision(64);
        F5:
        $zY++;
        goto hC;
        B_:
        $Wu = array("\x34\x32\70\x61\62\x66\x39\x38\144\x37\x32\70\141\145\x32\x32", "\x37\61\63\67\x34\x34\71\61\x32\63\x65\146\66\65\143\x64", "\142\x35\143\x30\146\x62\x63\146\145\x63\64\x64\x33\x62\62\146", "\x65\71\142\x35\x64\142\x61\65\70\x31\70\71\x64\142\x62\x63", "\63\x39\x35\x36\x63\x32\65\x62\146\x33\x34\70\142\65\x33\70", "\65\71\x66\x31\61\x31\x66\x31\x62\x36\60\65\x64\x30\x31\x39", "\71\62\63\146\x38\62\x61\64\141\x66\x31\71\64\146\x39\x62", "\x61\142\x31\x63\x35\x65\144\65\144\x61\66\144\70\x31\x31\x38", "\x64\x38\60\x37\141\141\71\x38\x61\x33\60\63\x30\x32\x34\62", "\61\62\x38\x33\x35\x62\60\x31\x34\x35\67\x30\66\x66\x62\x65", "\62\x34\63\61\x38\65\142\x65\x34\145\x65\64\x62\62\x38\x63", "\65\x35\x30\143\x37\x64\143\63\x64\x35\146\x66\142\64\145\x32", "\x37\x32\x62\145\65\144\67\64\x66\62\67\142\x38\71\66\146", "\x38\x30\144\x65\x62\61\x66\x65\63\142\x31\x36\71\66\x62\61", "\71\142\144\143\60\66\141\x37\x32\x35\143\x37\61\62\63\x35", "\143\61\71\142\x66\x31\67\64\143\146\x36\71\62\66\x39\x34", "\x65\x34\71\x62\x36\71\x63\x31\71\145\146\x31\64\141\144\62", "\145\146\142\145\64\x37\70\x36\63\x38\x34\x66\62\65\145\x33", "\x30\x66\143\61\x39\x64\x63\66\70\x62\70\143\x64\65\x62\x35", "\x32\x34\60\143\141\x31\143\x63\67\67\141\143\x39\143\66\x35", "\62\144\145\71\x32\143\x36\x66\x35\71\62\142\x30\62\x37\65", "\64\141\x37\x34\x38\x34\141\141\x36\x65\141\x36\x65\x34\x38\x33", "\65\x63\x62\60\141\71\144\143\142\x64\x34\x31\x66\x62\x64\x34", "\x37\66\x66\71\70\70\x64\x61\70\x33\x31\61\65\63\x62\x35", "\x39\x38\63\145\65\x31\x35\62\x65\145\x36\66\x64\x66\141\x62", "\x61\70\63\x31\x63\x36\66\x64\x32\x64\x62\x34\x33\62\61\60", "\x62\60\60\x33\62\67\143\x38\71\70\x66\x62\62\x31\63\146", "\x62\146\65\x39\67\146\143\67\x62\145\145\146\60\145\145\x34", "\x63\66\145\60\x30\x62\x66\63\x33\x64\141\70\70\x66\143\x32", "\144\x35\x61\x37\x39\x31\x34\67\71\x33\60\141\x61\x37\62\x35", "\x30\66\143\141\x36\x33\65\x31\145\60\60\63\x38\62\66\x66", "\x31\x34\62\x39\62\x39\x36\67\x30\141\x30\x65\x36\x65\67\x30", "\x32\x37\x62\x37\x30\141\x38\x35\x34\66\x64\62\x32\146\146\143", "\62\x65\61\142\62\61\x33\x38\x35\x63\62\x36\143\x39\62\x36", "\64\x64\62\x63\66\144\x66\143\65\x61\x63\64\62\141\x65\x64", "\65\x33\63\70\x30\x64\61\x33\71\x64\x39\x35\142\63\x64\146", "\66\x35\x30\x61\67\63\65\x34\70\x62\141\146\x36\63\x64\x65", "\67\66\x36\x61\60\x61\142\142\63\143\67\67\x62\x32\x61\70", "\x38\x31\143\x32\143\71\62\x65\64\x37\145\x64\141\x65\145\66", "\x39\x32\67\62\x32\143\x38\x35\61\64\70\62\x33\x35\63\x62", "\141\62\x62\x66\x65\x38\141\x31\64\x63\x66\x31\x30\63\66\x34", "\x61\70\61\x61\x36\66\64\142\142\143\64\62\63\60\60\x31", "\x63\62\x34\142\x38\142\x37\60\144\x30\x66\x38\71\67\71\61", "\x63\x37\66\143\65\x31\141\x33\60\66\x35\x34\x62\145\x33\60", "\144\x31\x39\62\x65\x38\61\x39\x64\66\145\146\65\62\x31\x38", "\x64\66\71\x39\x30\x36\x32\64\65\x35\66\65\141\71\x31\x30", "\x66\64\60\145\x33\x35\70\x35\65\x37\67\61\x32\x30\x32\141", "\x31\x30\x36\x61\141\60\x37\x30\63\x32\142\142\x64\61\x62\70", "\61\71\x61\x34\x63\61\x31\x36\142\x38\144\x32\144\x30\143\x38", "\x31\x65\63\x37\66\143\60\70\65\x31\64\x31\x61\142\x35\63", "\x32\x37\64\x38\x37\x37\64\143\144\x66\70\145\145\142\71\x39", "\63\x34\x62\x30\142\143\x62\x35\x65\61\71\x62\64\70\141\x38", "\x33\71\61\x63\x30\143\x62\63\x63\65\143\x39\x35\141\66\63", "\x34\x65\x64\70\x61\141\x34\x61\145\x33\64\x31\70\x61\x63\142", "\65\142\x39\143\x63\x61\64\146\x37\x37\x36\63\145\63\x37\63", "\66\70\x32\x65\66\146\x66\x33\x64\66\x62\62\142\x38\x61\63", "\x37\x34\x38\x66\x38\62\145\145\x35\144\x65\146\142\x32\x66\x63", "\x37\x38\141\65\x36\63\x36\x66\x34\x33\61\x37\x32\146\66\60", "\x38\64\143\x38\x37\x38\61\x34\141\61\146\x30\x61\x62\x37\62", "\x38\x63\143\x37\60\x32\60\70\x31\141\x36\x34\63\x39\145\x63", "\x39\x30\x62\x65\146\146\x66\x61\62\63\x36\x33\61\x65\x32\70", "\141\x34\65\60\x36\x63\145\142\144\145\70\x32\x62\x64\x65\x39", "\x62\x65\x66\x39\141\63\x66\x37\x62\62\x63\x36\x37\x39\61\65", "\x63\66\x37\x31\x37\70\x66\62\x65\63\x37\x32\65\x33\62\142", "\x63\x61\62\x37\63\145\143\145\x65\x61\x32\66\66\x31\x39\143", "\144\61\70\x36\142\x38\143\x37\x32\61\143\x30\143\x32\60\x37", "\145\141\144\141\x37\144\x64\x36\143\144\145\60\x65\142\x31\145", "\x66\x35\67\144\x34\x66\x37\x66\x65\145\66\145\x64\61\67\x38", "\60\66\x66\60\x36\x37\x61\141\67\x32\61\x37\x36\146\142\141", "\x30\141\x36\63\67\144\x63\65\x61\x32\x63\x38\x39\x38\x61\x36", "\61\x31\63\146\71\x38\60\x34\142\x65\146\71\x30\144\141\x65", "\x31\x62\67\61\60\142\x33\65\x31\x33\61\x63\x34\67\61\x62", "\x32\x38\144\142\x37\x37\146\x35\62\x33\60\x34\67\x64\x38\64", "\x33\x32\143\141\x61\x62\67\x62\x34\x30\143\67\x32\64\71\x33", "\x33\x63\x39\145\x62\145\60\141\x31\65\143\x39\142\x65\x62\x63", "\x34\63\x31\144\66\67\143\64\71\x63\x31\x30\x30\x64\x34\x63", "\64\x63\x63\65\x64\x34\142\x65\x63\x62\63\145\64\62\142\x36", "\65\x39\67\x66\62\x39\71\x63\x66\x63\x36\65\x37\145\62\141", "\65\x66\x63\x62\x36\x66\x61\x62\63\141\144\66\146\x61\145\143", "\66\x63\x34\x34\x31\x39\70\143\x34\141\x34\x37\65\x38\x31\67");
        $zY = 0;
        Rx:
        if (!($zY < 80)) {
            goto BE;
        }
        $Wu[$zY] = new Math_BigInteger($Wu[$zY], 16);
        Tr:
        $zY++;
        goto Rx;
        BE:
        Ix:
        $H2 = $this->l == 48 ? $Xl : $Rk;
        $F_ = strlen($X3);
        $X3 .= str_repeat(chr(0), 128 - ($F_ + 16 & 0x7f));
        $X3[$F_] = chr(0x80);
        $X3 .= pack("\x4e\64", 0, 0, 0, $F_ << 3);
        $Dl = str_split($X3, 128);
        foreach ($Dl as $cB) {
            $j0 = array();
            $zY = 0;
            gR:
            if (!($zY < 16)) {
                goto Mc;
            }
            $zn = new Math_BigInteger($this->_string_shift($cB, 8), 256);
            $zn->setPrecision(64);
            $j0[] = $zn;
            IK:
            $zY++;
            goto gR;
            Mc:
            $zY = 16;
            wC:
            if (!($zY < 80)) {
                goto OG;
            }
            $zn = array($j0[$zY - 15]->bitwise_rightRotate(1), $j0[$zY - 15]->bitwise_rightRotate(8), $j0[$zY - 15]->bitwise_rightShift(7));
            $OS = $zn[0]->bitwise_xor($zn[1]);
            $OS = $OS->bitwise_xor($zn[2]);
            $zn = array($j0[$zY - 2]->bitwise_rightRotate(19), $j0[$zY - 2]->bitwise_rightRotate(61), $j0[$zY - 2]->bitwise_rightShift(6));
            $tc = $zn[0]->bitwise_xor($zn[1]);
            $tc = $tc->bitwise_xor($zn[2]);
            $j0[$zY] = $j0[$zY - 16]->copy();
            $j0[$zY] = $j0[$zY]->add($OS);
            $j0[$zY] = $j0[$zY]->add($j0[$zY - 7]);
            $j0[$zY] = $j0[$zY]->add($tc);
            mj:
            $zY++;
            goto wC;
            OG:
            $hP = $H2[0]->copy();
            $fM = $H2[1]->copy();
            $eo = $H2[2]->copy();
            $qa = $H2[3]->copy();
            $mP = $H2[4]->copy();
            $du = $H2[5]->copy();
            $Sm = $H2[6]->copy();
            $AX = $H2[7]->copy();
            $zY = 0;
            J6:
            if (!($zY < 80)) {
                goto Ll;
            }
            $zn = array($hP->bitwise_rightRotate(28), $hP->bitwise_rightRotate(34), $hP->bitwise_rightRotate(39));
            $OS = $zn[0]->bitwise_xor($zn[1]);
            $OS = $OS->bitwise_xor($zn[2]);
            $zn = array($hP->bitwise_and($fM), $hP->bitwise_and($eo), $fM->bitwise_and($eo));
            $oK = $zn[0]->bitwise_xor($zn[1]);
            $oK = $oK->bitwise_xor($zn[2]);
            $s9 = $OS->add($oK);
            $zn = array($mP->bitwise_rightRotate(14), $mP->bitwise_rightRotate(18), $mP->bitwise_rightRotate(41));
            $tc = $zn[0]->bitwise_xor($zn[1]);
            $tc = $tc->bitwise_xor($zn[2]);
            $zn = array($mP->bitwise_and($du), $Sm->bitwise_and($mP->bitwise_not()));
            $bV = $zn[0]->bitwise_xor($zn[1]);
            $Sx = $AX->add($tc);
            $Sx = $Sx->add($bV);
            $Sx = $Sx->add($Wu[$zY]);
            $Sx = $Sx->add($j0[$zY]);
            $AX = $Sm->copy();
            $Sm = $du->copy();
            $du = $mP->copy();
            $mP = $qa->add($Sx);
            $qa = $eo->copy();
            $eo = $fM->copy();
            $fM = $hP->copy();
            $hP = $Sx->add($s9);
            Th:
            $zY++;
            goto J6;
            Ll:
            $H2 = array($H2[0]->add($hP), $H2[1]->add($fM), $H2[2]->add($eo), $H2[3]->add($qa), $H2[4]->add($mP), $H2[5]->add($du), $H2[6]->add($Sm), $H2[7]->add($AX));
            EA:
        }
        Xw:
        $zn = $H2[0]->toBytes() . $H2[1]->toBytes() . $H2[2]->toBytes() . $H2[3]->toBytes() . $H2[4]->toBytes() . $H2[5]->toBytes();
        if (!($this->l != 48)) {
            goto fU;
        }
        $zn .= $H2[6]->toBytes() . $H2[7]->toBytes();
        fU:
        return $zn;
    }
    function _rightRotate($LW, $hl)
    {
        $C6 = 32 - $hl;
        $aE = (1 << $C6) - 1;
        return $LW << $C6 & 0xffffffff | $LW >> $hl & $aE;
    }
    function _rightShift($LW, $hl)
    {
        $aE = (1 << 32 - $hl) - 1;
        return $LW >> $hl & $aE;
    }
    function _not($LW)
    {
        return ~$LW & 0xffffffff;
    }
    function _add()
    {
        static $cU;
        if (isset($cU)) {
            goto Mf;
        }
        $cU = pow(2, 32);
        Mf:
        $DE = 0;
        $Nu = func_get_args();
        foreach ($Nu as $f7) {
            $DE += $f7 < 0 ? ($f7 & 0x7fffffff) + 0x80000000 : $f7;
            wO:
        }
        AO:
        switch (true) {
            case is_int($DE):
            case version_compare(PHP_VERSION, "\x35\x2e\63\x2e\x30") >= 0 && (php_uname("\155") & "\xdf\xdf\337") != "\101\x52\x4d":
            case (PHP_OS & "\xdf\xdf\337") === "\127\111\x4e":
                return fmod($DE, $cU);
        }
        wk:
        xz:
        return fmod($DE, 0x80000000) & 0x7fffffff | (fmod(floor($DE / 0x80000000), 2) & 1) << 31;
    }
    function _string_shift(&$P3, $Vo = 1)
    {
        $r3 = substr($P3, 0, $Vo);
        $P3 = substr($P3, $Vo);
        return $r3;
    }
}
