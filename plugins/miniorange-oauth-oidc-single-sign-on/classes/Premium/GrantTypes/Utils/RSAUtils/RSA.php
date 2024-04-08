<?php


namespace MoOauthClient\GrantTypes;

if (!function_exists("\x63\x72\171\160\164\137\162\141\x6e\144\x6f\155\x5f\x73\x74\162\151\x6e\x67")) {
    include_once "\122\141\156\x64\157\x6d\x2e\160\x68\x70";
}
if (class_exists("\103\162\x79\x70\164\137\110\141\x73\150")) {
    goto e8y;
}
include_once "\110\x61\x73\150\56\x70\150\x70";
e8y:
define("\103\122\131\x50\124\x5f\x52\123\101\137\x45\116\x43\122\131\x50\x54\111\x4f\116\x5f\117\x41\x45\120", 1);
define("\103\122\x59\120\124\x5f\122\123\x41\x5f\x45\x4e\103\x52\131\x50\124\111\x4f\x4e\x5f\x50\113\x43\x53\61", 2);
define("\103\x52\131\x50\124\x5f\122\x53\x41\x5f\x45\116\x43\122\x59\x50\124\x49\x4f\x4e\x5f\116\x4f\116\x45", 3);
define("\103\122\x59\120\x54\x5f\122\123\101\137\x53\111\107\116\x41\x54\125\122\x45\137\120\123\x53", 1);
define("\x43\122\131\120\x54\137\x52\x53\x41\137\x53\111\x47\116\101\x54\125\x52\105\x5f\x50\x4b\103\x53\61", 2);
define("\x43\x52\x59\x50\124\x5f\x52\x53\101\x5f\101\123\x4e\x31\x5f\x49\116\124\x45\x47\105\x52", 2);
define("\x43\122\x59\120\x54\x5f\x52\x53\x41\137\101\123\116\x31\x5f\102\111\x54\x53\x54\x52\111\x4e\107", 3);
define("\103\x52\x59\x50\x54\x5f\122\123\x41\x5f\x41\x53\x4e\61\137\x4f\x43\124\105\124\123\x54\x52\111\116\107", 4);
define("\x43\122\131\x50\124\137\122\x53\x41\x5f\x41\x53\x4e\x31\x5f\117\102\x4a\x45\x43\x54", 6);
define("\x43\x52\x59\x50\124\x5f\x52\x53\101\x5f\x41\123\x4e\x31\x5f\x53\105\121\125\105\x4e\103\105", 48);
define("\x43\x52\131\x50\x54\137\122\x53\x41\x5f\115\x4f\x44\105\x5f\x49\116\x54\x45\x52\x4e\101\x4c", 1);
define("\x43\122\131\x50\x54\x5f\x52\x53\101\x5f\x4d\117\104\x45\x5f\117\x50\105\116\x53\123\x4c", 2);
define("\x43\x52\x59\x50\124\x5f\122\123\101\137\x4f\120\x45\116\123\123\x4c\137\x43\117\116\106\x49\107", dirname(__FILE__) . "\x2f\x2e\x2e\x2f\157\160\x65\x6e\x73\163\x6c\x2e\143\x6e\x66");
define("\103\122\x59\x50\x54\137\x52\x53\x41\x5f\120\122\x49\126\x41\124\105\137\x46\x4f\x52\x4d\101\124\x5f\120\x4b\x43\123\61", 0);
define("\103\x52\x59\120\x54\137\x52\123\101\137\x50\122\x49\x56\101\124\x45\x5f\106\117\122\x4d\101\x54\137\120\x55\124\124\x59", 1);
define("\103\122\131\120\x54\x5f\122\123\x41\x5f\x50\122\x49\126\101\124\x45\137\x46\x4f\x52\115\x41\124\x5f\130\x4d\114", 2);
define("\x43\122\131\120\x54\137\122\x53\101\x5f\120\x52\x49\x56\101\124\x45\x5f\106\117\x52\x4d\101\x54\x5f\120\x4b\103\x53\70", 8);
define("\x43\122\x59\x50\124\x5f\122\123\101\137\120\x55\102\x4c\111\x43\x5f\106\x4f\x52\115\101\x54\x5f\122\101\127", 3);
define("\103\x52\131\120\124\x5f\x52\123\101\x5f\120\x55\102\x4c\x49\103\137\106\117\122\115\101\x54\x5f\x50\113\103\x53\61", 4);
define("\x43\x52\x59\120\x54\x5f\x52\123\101\x5f\120\x55\102\114\x49\103\x5f\x46\117\x52\x4d\101\x54\137\120\113\x43\123\x31\137\x52\101\127", 4);
define("\x43\122\x59\x50\x54\x5f\122\123\101\137\x50\x55\102\x4c\x49\x43\x5f\x46\117\122\115\x41\124\x5f\x58\x4d\x4c", 5);
define("\x43\122\x59\x50\x54\x5f\x52\123\101\x5f\120\x55\x42\114\111\103\x5f\106\117\122\115\101\124\137\117\120\105\116\x53\x53\110", 6);
define("\x43\122\x59\x50\x54\137\x52\123\x41\137\120\x55\102\114\111\103\x5f\x46\117\122\115\x41\124\x5f\x50\x4b\103\x53\70", 7);
class Crypt_RSA
{
    var $zero;
    var $one;
    var $privateKeyFormat = CRYPT_RSA_PRIVATE_FORMAT_PKCS1;
    var $publicKeyFormat = CRYPT_RSA_PUBLIC_FORMAT_PKCS8;
    var $modulus;
    var $k;
    var $exponent;
    var $primes;
    var $exponents;
    var $coefficients;
    var $hashName;
    var $hash;
    var $hLen;
    var $sLen;
    var $mgfHash;
    var $mgfHLen;
    var $encryptionMode = CRYPT_RSA_ENCRYPTION_OAEP;
    var $signatureMode = CRYPT_RSA_SIGNATURE_PSS;
    var $publicExponent = false;
    var $password = false;
    var $components = array();
    var $current;
    var $configFile;
    var $comment = "\x70\150\160\163\145\x63\x6c\151\x62\55\x67\x65\x6e\145\162\141\164\145\144\x2d\153\145\171";
    function __construct()
    {
        if (class_exists("\115\x61\x74\150\137\x42\151\x67\x49\x6e\164\145\147\145\x72")) {
            goto fhw;
        }
        include_once dirname(__FILE__) . "\x2f\115\141\x74\150\57\102\151\147\x49\x6e\x74\145\147\x65\x72\x2e\x70\x68\x70";
        fhw:
        $this->configFile = CRYPT_RSA_OPENSSL_CONFIG;
        if (defined("\103\122\x59\x50\124\x5f\122\123\101\137\115\x4f\104\x45")) {
            goto TBE;
        }
        switch (true) {
            case defined("\x4d\x41\x54\x48\137\x42\111\107\x49\x4e\124\x45\x47\x45\122\x5f\x4f\x50\x45\x4e\x53\x53\114\137\104\x49\123\x41\102\114\x45"):
                define("\103\x52\131\120\x54\x5f\x52\x53\101\x5f\x4d\x4f\104\105", CRYPT_RSA_MODE_INTERNAL);
                goto lpr;
            case !function_exists("\x6f\x70\145\156\163\x73\x6c\137\x70\153\x65\x79\x5f\x67\x65\164\137\x64\x65\164\x61\x69\154\x73"):
                define("\103\x52\131\120\x54\137\x52\x53\x41\x5f\x4d\x4f\104\105", CRYPT_RSA_MODE_INTERNAL);
                goto lpr;
            case extension_loaded("\x6f\160\145\x6e\163\x73\x6c") && version_compare(PHP_VERSION, "\64\x2e\x32\56\x30", "\x3e\x3d") && file_exists($this->configFile):
                ob_start();
                @phpinfo();
                $dV = ob_get_contents();
                ob_end_clean();
                preg_match_all("\43\117\x70\x65\156\123\123\114\40\50\110\145\x61\x64\x65\162\x7c\x4c\x69\142\162\141\x72\x79\x29\40\x56\x65\x72\x73\x69\x6f\156\x28\56\52\51\43\151\x6d", $dV, $JY);
                $HO = array();
                if (empty($JY[1])) {
                    goto kYu;
                }
                $zY = 0;
                v7w:
                if (!($zY < count($JY[1]))) {
                    goto uKy;
                }
                $yI = trim(str_replace("\75\x3e", '', strip_tags($JY[2][$zY])));
                if (!preg_match("\57\50\134\x64\x2b\134\x2e\134\144\x2b\134\x2e\134\144\x2b\51\x2f\x69", $yI, $X3)) {
                    goto QvX;
                }
                $HO[$JY[1][$zY]] = $X3[0];
                goto xSE;
                QvX:
                $HO[$JY[1][$zY]] = $yI;
                xSE:
                b7x:
                $zY++;
                goto v7w;
                uKy:
                kYu:
                switch (true) {
                    case !isset($HO["\110\x65\x61\x64\x65\162"]):
                    case !isset($HO["\114\151\142\162\141\x72\171"]):
                    case $HO["\110\x65\x61\x64\x65\162"] == $HO["\x4c\151\142\162\x61\162\171"]:
                    case version_compare($HO["\x48\145\x61\x64\145\162"], "\61\56\x30\x2e\60") >= 0 && version_compare($HO["\114\x69\x62\x72\141\162\x79"], "\x31\56\60\x2e\x30") >= 0:
                        define("\103\x52\x59\120\x54\x5f\x52\123\101\137\115\x4f\104\x45", CRYPT_RSA_MODE_OPENSSL);
                        goto AHv;
                    default:
                        define("\x43\x52\131\120\124\137\x52\x53\101\137\x4d\x4f\104\105", CRYPT_RSA_MODE_INTERNAL);
                        define("\x4d\101\x54\x48\137\x42\x49\107\x49\116\124\105\107\105\122\x5f\x4f\x50\x45\x4e\x53\x53\x4c\x5f\104\x49\x53\x41\102\114\105", true);
                }
                pnP:
                AHv:
                goto lpr;
            default:
                define("\x43\122\131\120\124\x5f\122\x53\x41\137\115\117\104\x45", CRYPT_RSA_MODE_INTERNAL);
        }
        VNj:
        lpr:
        TBE:
        $this->zero = new Math_BigInteger();
        $this->one = new Math_BigInteger(1);
        $this->hash = new Crypt_Hash("\x73\150\141\x31");
        $this->hLen = $this->hash->getLength();
        $this->hashName = "\x73\150\141\x31";
        $this->mgfHash = new Crypt_Hash("\163\x68\141\61");
        $this->mgfHLen = $this->mgfHash->getLength();
    }
    function Crypt_RSA()
    {
        $this->__construct();
    }
    function createKey($Xs = 1024, $Lz = false, $Np = array())
    {
        if (defined("\x43\x52\131\120\124\137\x52\123\x41\137\x45\x58\120\117\116\x45\x4e\124")) {
            goto YFN;
        }
        define("\103\x52\x59\120\x54\137\x52\123\x41\x5f\x45\x58\x50\117\116\105\116\124", "\x36\65\65\x33\67");
        YFN:
        if (defined("\103\x52\131\x50\x54\x5f\x52\x53\x41\x5f\x53\x4d\x41\x4c\114\105\123\124\x5f\120\x52\111\x4d\x45")) {
            goto gD6;
        }
        define("\x43\x52\x59\x50\x54\137\122\123\101\137\123\x4d\x41\x4c\114\x45\123\124\137\x50\122\111\x4d\x45", 4096);
        gD6:
        if (!(CRYPT_RSA_MODE == CRYPT_RSA_MODE_OPENSSL && $Xs >= 384 && CRYPT_RSA_EXPONENT == 65537)) {
            goto giw;
        }
        $Kn = array();
        if (!isset($this->configFile)) {
            goto JZk;
        }
        $Kn["\143\157\x6e\146\151\147"] = $this->configFile;
        JZk:
        $se = openssl_pkey_new(array("\x70\162\151\166\141\164\x65\x5f\153\145\x79\x5f\142\151\x74\163" => $Xs) + $Kn);
        openssl_pkey_export($se, $Q1, null, $Kn);
        $lV = openssl_pkey_get_details($se);
        $lV = $lV["\x6b\145\x79"];
        $Q1 = call_user_func_array(array($this, "\x5f\143\x6f\156\166\x65\162\164\x50\162\x69\166\141\164\x65\113\x65\x79"), array_values($this->_parseKey($Q1, CRYPT_RSA_PRIVATE_FORMAT_PKCS1)));
        $lV = call_user_func_array(array($this, "\x5f\143\157\x6e\166\x65\x72\x74\120\165\x62\154\x69\x63\113\x65\x79"), array_values($this->_parseKey($lV, CRYPT_RSA_PUBLIC_FORMAT_PKCS1)));
        Xtx:
        if (!(openssl_error_string() !== false)) {
            goto nKM;
        }
        goto Xtx;
        nKM:
        return array("\160\162\151\x76\141\164\x65\153\x65\171" => $Q1, "\x70\165\142\154\151\143\153\x65\x79" => $lV, "\160\x61\162\164\x69\141\x6c\x6b\x65\171" => false);
        giw:
        static $mP;
        if (isset($mP)) {
            goto yDh;
        }
        $mP = new Math_BigInteger(CRYPT_RSA_EXPONENT);
        yDh:
        extract($this->_generateMinMax($Xs));
        $PP = $sr;
        $zn = $Xs >> 1;
        if ($zn > CRYPT_RSA_SMALLEST_PRIME) {
            goto F0z;
        }
        $Q8 = 2;
        goto EqB;
        F0z:
        $Q8 = floor($Xs / CRYPT_RSA_SMALLEST_PRIME);
        $zn = CRYPT_RSA_SMALLEST_PRIME;
        EqB:
        extract($this->_generateMinMax($zn + $Xs % $zn));
        $GS = $HK;
        extract($this->_generateMinMax($zn));
        $Ok = new Math_BigInteger();
        $Bd = $this->one->copy();
        if (!empty($Np)) {
            goto x_1;
        }
        $uy = $mf = $F3 = array();
        $fl = array("\164\157\160" => $this->one->copy(), "\142\157\164\x74\x6f\x6d" => false);
        goto cbo;
        x_1:
        extract(unserialize($Np));
        cbo:
        $Kl = time();
        $Cc = count($F3) + 1;
        yH9:
        $zY = $Cc;
        Y9e:
        if (!($zY <= $Q8)) {
            goto b4J;
        }
        if (!($Lz !== false)) {
            goto jgr;
        }
        $Lz -= time() - $Kl;
        $Kl = time();
        if (!($Lz <= 0)) {
            goto vFp;
        }
        return array("\x70\x72\151\166\x61\x74\x65\x6b\x65\x79" => '', "\x70\165\142\x6c\x69\x63\153\x65\171" => '', "\x70\141\x72\164\x69\141\x6c\153\x65\171" => serialize(array("\x70\x72\x69\x6d\145\163" => $F3, "\143\157\145\x66\x66\151\x63\x69\x65\156\x74\163" => $mf, "\x6c\143\155" => $fl, "\145\x78\x70\x6f\x6e\145\x6e\x74\163" => $uy)));
        vFp:
        jgr:
        if ($zY == $Q8) {
            goto r4v;
        }
        $F3[$zY] = $Ok->randomPrime($sr, $HK, $Lz);
        goto MYO;
        r4v:
        list($sr, $zn) = $PP->divide($Bd);
        if ($zn->equals($this->zero)) {
            goto Unv;
        }
        $sr = $sr->add($this->one);
        Unv:
        $F3[$zY] = $Ok->randomPrime($sr, $GS, $Lz);
        MYO:
        if (!($F3[$zY] === false)) {
            goto En0;
        }
        if (count($F3) > 1) {
            goto rP1;
        }
        array_pop($F3);
        $Le = serialize(array("\x70\x72\x69\x6d\x65\x73" => $F3, "\x63\x6f\145\x66\x66\151\143\151\x65\156\x74\x73" => $mf, "\x6c\143\x6d" => $fl, "\x65\170\x70\157\156\x65\156\164\x73" => $uy));
        goto EKn;
        rP1:
        $Le = '';
        EKn:
        return array("\160\x72\151\166\141\164\x65\153\145\x79" => '', "\160\x75\x62\154\x69\x63\x6b\145\x79" => '', "\x70\x61\162\x74\151\x61\x6c\153\x65\171" => $Le);
        En0:
        if (!($zY > 2)) {
            goto OPf;
        }
        $mf[$zY] = $Bd->modInverse($F3[$zY]);
        OPf:
        $Bd = $Bd->multiply($F3[$zY]);
        $zn = $F3[$zY]->subtract($this->one);
        $fl["\x74\157\160"] = $fl["\x74\x6f\x70"]->multiply($zn);
        $fl["\x62\157\164\x74\x6f\x6d"] = $fl["\x62\x6f\164\x74\x6f\x6d"] === false ? $zn : $fl["\142\x6f\164\x74\x6f\x6d"]->gcd($zn);
        $uy[$zY] = $mP->modInverse($zn);
        Z5s:
        $zY++;
        goto Y9e;
        b4J:
        list($zn) = $fl["\164\x6f\160"]->divide($fl["\x62\x6f\x74\x74\157\x6d"]);
        $AE = $zn->gcd($mP);
        $Cc = 1;
        if (!$AE->equals($this->one)) {
            goto yH9;
        }
        cC1:
        $qa = $mP->modInverse($zn);
        $mf[2] = $F3[2]->modInverse($F3[1]);
        return array("\x70\162\x69\x76\141\164\x65\x6b\145\171" => $this->_convertPrivateKey($Bd, $mP, $qa, $F3, $uy, $mf), "\160\x75\x62\154\x69\x63\153\145\x79" => $this->_convertPublicKey($Bd, $mP), "\x70\141\162\x74\x69\141\x6c\153\x65\x79" => false);
    }
    function _convertPrivateKey($Bd, $mP, $qa, $F3, $uy, $mf)
    {
        $Qx = $this->privateKeyFormat != CRYPT_RSA_PRIVATE_FORMAT_XML;
        $Q8 = count($F3);
        $EH = array("\166\x65\162\163\151\157\x6e" => $Q8 == 2 ? chr(0) : chr(1), "\x6d\x6f\x64\x75\x6c\x75\x73" => $Bd->toBytes($Qx), "\160\x75\142\x6c\151\x63\x45\170\x70\157\156\145\x6e\x74" => $mP->toBytes($Qx), "\160\162\x69\x76\141\164\145\105\170\x70\157\156\x65\156\x74" => $qa->toBytes($Qx), "\x70\x72\151\155\x65\x31" => $F3[1]->toBytes($Qx), "\x70\x72\x69\x6d\x65\x32" => $F3[2]->toBytes($Qx), "\145\x78\160\x6f\x6e\x65\156\x74\61" => $uy[1]->toBytes($Qx), "\x65\170\x70\x6f\x6e\145\156\164\x32" => $uy[2]->toBytes($Qx), "\143\157\145\146\x66\151\143\151\x65\x6e\164" => $mf[2]->toBytes($Qx));
        switch ($this->privateKeyFormat) {
            case CRYPT_RSA_PRIVATE_FORMAT_XML:
                if (!($Q8 != 2)) {
                    goto EwL;
                }
                return false;
                EwL:
                return "\x3c\122\x53\101\113\145\x79\126\141\154\x75\145\76\xd\xa" . "\40\x20\74\x4d\157\144\x75\x6c\165\x73\x3e" . base64_encode($EH["\155\x6f\x64\x75\154\x75\163"]) . "\x3c\57\x4d\157\144\165\154\x75\x73\x3e\xd\xa" . "\40\x20\x3c\105\170\160\x6f\x6e\145\x6e\164\x3e" . base64_encode($EH["\x70\x75\142\x6c\151\x63\105\x78\x70\157\x6e\x65\156\x74"]) . "\x3c\x2f\105\x78\x70\157\156\x65\x6e\x74\76\15\xa" . "\x20\40\x3c\120\x3e" . base64_encode($EH["\x70\162\151\x6d\145\x31"]) . "\x3c\57\x50\x3e\15\12" . "\40\x20\x3c\121\76" . base64_encode($EH["\x70\162\x69\x6d\x65\x32"]) . "\74\x2f\x51\x3e\15\12" . "\x20\40\74\104\x50\76" . base64_encode($EH["\x65\170\x70\157\x6e\145\156\x74\61"]) . "\x3c\57\104\120\76\15\xa" . "\x20\40\x3c\x44\x51\x3e" . base64_encode($EH["\x65\170\160\x6f\156\145\156\164\x32"]) . "\x3c\57\104\121\76\15\xa" . "\40\x20\x3c\x49\156\166\145\x72\163\145\x51\x3e" . base64_encode($EH["\x63\x6f\145\x66\x66\x69\x63\151\145\156\164"]) . "\74\57\111\x6e\166\x65\162\163\145\121\76\15\12" . "\40\40\x3c\x44\x3e" . base64_encode($EH["\x70\x72\151\166\141\164\x65\x45\170\160\x6f\156\145\x6e\164"]) . "\74\x2f\104\76\xd\xa" . "\74\x2f\x52\x53\101\113\x65\x79\126\141\154\x75\145\x3e";
                goto l7a;
            case CRYPT_RSA_PRIVATE_FORMAT_PUTTY:
                if (!($Q8 != 2)) {
                    goto IrO;
                }
                return false;
                IrO:
                $Mr = "\x50\165\x54\124\x59\55\125\163\x65\162\55\x4b\145\x79\x2d\x46\151\154\145\55\x32\72\40\x73\x73\150\55\162\x73\141\xd\12\105\156\143\x72\x79\x70\164\x69\x6f\x6e\x3a\x20";
                $KN = !empty($this->password) || is_string($this->password) ? "\x61\x65\x73\62\65\x36\55\143\142\143" : "\156\x6f\156\145";
                $Mr .= $KN;
                $Mr .= "\15\xa\x43\157\155\x6d\x65\156\x74\x3a\x20" . $this->comment . "\xd\xa";
                $wR = pack("\x4e\141\x2a\x4e\141\x2a\116\x61\x2a", strlen("\163\x73\x68\55\162\163\141"), "\163\163\150\55\162\163\x61", strlen($EH["\x70\165\142\x6c\x69\143\105\170\160\157\156\145\x6e\x74"]), $EH["\x70\x75\142\x6c\151\143\105\170\160\x6f\x6e\x65\x6e\164"], strlen($EH["\x6d\x6f\x64\x75\154\x75\x73"]), $EH["\x6d\157\144\165\x6c\x75\x73"]);
                $D6 = pack("\x4e\x61\52\116\x61\x2a\x4e\x61\52\116\141\52", strlen("\163\163\x68\x2d\x72\163\x61"), "\163\x73\150\55\x72\163\x61", strlen($KN), $KN, strlen($this->comment), $this->comment, strlen($wR), $wR);
                $wR = base64_encode($wR);
                $Mr .= "\120\165\142\154\151\143\x2d\x4c\151\x6e\x65\x73\72\40" . (strlen($wR) + 63 >> 6) . "\xd\12";
                $Mr .= chunk_split($wR, 64);
                $Xd = pack("\x4e\141\52\116\141\52\116\141\52\116\x61\x2a", strlen($EH["\160\162\151\x76\141\x74\x65\105\x78\x70\x6f\156\145\x6e\164"]), $EH["\x70\162\151\166\141\x74\x65\105\170\160\157\x6e\x65\156\x74"], strlen($EH["\x70\x72\151\155\x65\61"]), $EH["\x70\162\151\155\x65\61"], strlen($EH["\x70\162\151\x6d\145\62"]), $EH["\x70\x72\x69\x6d\145\62"], strlen($EH["\x63\x6f\145\x66\146\x69\143\x69\145\156\x74"]), $EH["\143\x6f\145\x66\146\151\x63\x69\x65\x6e\x74"]);
                if (empty($this->password) && !is_string($this->password)) {
                    goto IRM;
                }
                $Xd .= crypt_random_string(16 - (strlen($Xd) & 15));
                $D6 .= pack("\116\x61\x2a", strlen($Xd), $Xd);
                if (class_exists("\103\162\171\x70\x74\x5f\x41\x45\123")) {
                    goto d_F;
                }
                include_once "\103\162\x79\160\x74\x2f\x41\x45\x53\56\160\150\160";
                d_F:
                $my = 0;
                $nT = '';
                ado:
                if (!(strlen($nT) < 32)) {
                    goto rgL;
                }
                $zn = pack("\116\141\52", $my++, $this->password);
                $nT .= pack("\x48\x2a", sha1($zn));
                goto ado;
                rgL:
                $nT = substr($nT, 0, 32);
                $R3 = new Crypt_AES();
                $R3->setKey($nT);
                $R3->disablePadding();
                $Xd = $R3->encrypt($Xd);
                $rz = "\x70\165\x74\x74\171\55\x70\x72\151\x76\x61\x74\145\x2d\153\145\x79\55\146\x69\x6c\x65\x2d\155\141\x63\55\x6b\x65\x79" . $this->password;
                goto Jv7;
                IRM:
                $D6 .= pack("\x4e\141\52", strlen($Xd), $Xd);
                $rz = "\160\165\x74\x74\171\x2d\160\x72\x69\166\141\164\145\x2d\x6b\145\171\x2d\x66\x69\154\x65\55\155\x61\x63\x2d\153\145\171";
                Jv7:
                $Xd = base64_encode($Xd);
                $Mr .= "\120\x72\151\166\x61\x74\145\x2d\114\x69\156\145\163\x3a\40" . (strlen($Xd) + 63 >> 6) . "\15\12";
                $Mr .= chunk_split($Xd, 64);
                if (class_exists("\103\x72\171\160\x74\x5f\x48\141\x73\150")) {
                    goto N1A;
                }
                include_once "\103\162\x79\x70\164\57\110\141\x73\x68\x2e\x70\x68\x70";
                N1A:
                $H2 = new Crypt_Hash("\163\x68\141\61");
                $H2->setKey(pack("\110\52", sha1($rz)));
                $Mr .= "\x50\x72\151\x76\141\x74\145\55\x4d\101\103\x3a\40" . bin2hex($H2->hash($D6)) . "\xd\12";
                return $Mr;
            default:
                $Nd = array();
                foreach ($EH as $uQ => $t_) {
                    $Nd[$uQ] = pack("\x43\x61\x2a\141\52", CRYPT_RSA_ASN1_INTEGER, $this->_encodeLength(strlen($t_)), $t_);
                    Il4:
                }
                Pha:
                $ec = implode('', $Nd);
                if (!($Q8 > 2)) {
                    goto k7H;
                }
                $vA = '';
                $zY = 3;
                JN6:
                if (!($zY <= $Q8)) {
                    goto kco;
                }
                $ii = pack("\103\141\x2a\x61\x2a", CRYPT_RSA_ASN1_INTEGER, $this->_encodeLength(strlen($F3[$zY]->toBytes(true))), $F3[$zY]->toBytes(true));
                $ii .= pack("\103\x61\52\141\52", CRYPT_RSA_ASN1_INTEGER, $this->_encodeLength(strlen($uy[$zY]->toBytes(true))), $uy[$zY]->toBytes(true));
                $ii .= pack("\x43\141\x2a\x61\x2a", CRYPT_RSA_ASN1_INTEGER, $this->_encodeLength(strlen($mf[$zY]->toBytes(true))), $mf[$zY]->toBytes(true));
                $vA .= pack("\x43\x61\52\141\x2a", CRYPT_RSA_ASN1_SEQUENCE, $this->_encodeLength(strlen($ii)), $ii);
                dzf:
                $zY++;
                goto JN6;
                kco:
                $ec .= pack("\x43\141\x2a\x61\52", CRYPT_RSA_ASN1_SEQUENCE, $this->_encodeLength(strlen($vA)), $vA);
                k7H:
                $ec = pack("\103\141\x2a\141\x2a", CRYPT_RSA_ASN1_SEQUENCE, $this->_encodeLength(strlen($ec)), $ec);
                if (!($this->privateKeyFormat == CRYPT_RSA_PRIVATE_FORMAT_PKCS8)) {
                    goto dNX;
                }
                $M7 = pack("\110\x2a", "\x33\60\60\x64\60\x36\60\71\62\x61\x38\66\64\70\70\66\x66\x37\60\x64\x30\61\x30\61\x30\x31\x30\65\60\60");
                $ec = pack("\103\x61\52\x61\x2a\x43\141\x2a\141\52", CRYPT_RSA_ASN1_INTEGER, "\x1\0", $M7, 4, $this->_encodeLength(strlen($ec)), $ec);
                $ec = pack("\x43\141\52\x61\x2a", CRYPT_RSA_ASN1_SEQUENCE, $this->_encodeLength(strlen($ec)), $ec);
                if (!empty($this->password) || is_string($this->password)) {
                    goto Js8;
                }
                $ec = "\55\55\55\x2d\x2d\102\105\107\111\x4e\x20\120\122\111\126\x41\124\105\40\113\105\x59\x2d\55\x2d\55\x2d\xd\xa" . chunk_split(base64_encode($ec), 64) . "\x2d\x2d\x2d\55\55\x45\116\x44\x20\120\122\x49\126\x41\124\x45\40\x4b\x45\x59\55\55\55\55\55";
                goto ynJ;
                Js8:
                $mk = crypt_random_string(8);
                $T0 = 2048;
                if (class_exists("\x43\x72\x79\160\164\x5f\x44\x45\x53")) {
                    goto SpS;
                }
                include_once "\x43\162\171\x70\x74\x2f\x44\105\x53\56\160\150\x70";
                SpS:
                $R3 = new Crypt_DES();
                $R3->setPassword($this->password, "\160\x62\153\144\146\61", "\x6d\x64\65", $mk, $T0);
                $ec = $R3->encrypt($ec);
                $JF = pack("\103\x61\52\x61\52\x43\141\52\116", CRYPT_RSA_ASN1_OCTETSTRING, $this->_encodeLength(strlen($mk)), $mk, CRYPT_RSA_ASN1_INTEGER, $this->_encodeLength(4), $T0);
                $EX = "\52\x86\110\206\xf7\xd\1\x5\3";
                $W5 = pack("\x43\141\52\141\52\x43\x61\x2a\141\52", CRYPT_RSA_ASN1_OBJECT, $this->_encodeLength(strlen($EX)), $EX, CRYPT_RSA_ASN1_SEQUENCE, $this->_encodeLength(strlen($JF)), $JF);
                $ec = pack("\x43\x61\x2a\141\52\x43\141\52\x61\x2a", CRYPT_RSA_ASN1_SEQUENCE, $this->_encodeLength(strlen($W5)), $W5, CRYPT_RSA_ASN1_OCTETSTRING, $this->_encodeLength(strlen($ec)), $ec);
                $ec = pack("\x43\141\52\x61\52", CRYPT_RSA_ASN1_SEQUENCE, $this->_encodeLength(strlen($ec)), $ec);
                $ec = "\x2d\55\x2d\55\x2d\x42\105\x47\111\116\40\105\116\103\x52\x59\x50\x54\x45\x44\40\120\x52\111\126\101\x54\x45\40\113\105\131\x2d\x2d\x2d\55\55\xd\xa" . chunk_split(base64_encode($ec), 64) . "\x2d\x2d\x2d\x2d\55\105\116\x44\40\105\116\103\122\x59\120\124\x45\x44\40\120\122\x49\x56\101\124\105\x20\x4b\105\x59\x2d\55\x2d\x2d\55";
                ynJ:
                return $ec;
                dNX:
                if (!empty($this->password) || is_string($this->password)) {
                    goto Yb_;
                }
                $ec = "\55\x2d\55\55\55\102\105\107\111\116\40\x52\123\101\40\120\x52\x49\126\101\124\x45\x20\x4b\x45\x59\x2d\x2d\x2d\55\55\xd\12" . chunk_split(base64_encode($ec), 64) . "\x2d\55\x2d\x2d\x2d\x45\116\x44\x20\122\123\101\x20\x50\122\111\126\101\x54\x45\x20\x4b\105\x59\x2d\x2d\x2d\55\55";
                goto BXp;
                Yb_:
                $X0 = crypt_random_string(8);
                $nT = pack("\110\x2a", md5($this->password . $X0));
                $nT .= substr(pack("\x48\x2a", md5($nT . $this->password . $X0)), 0, 8);
                if (class_exists("\103\162\x79\160\164\137\x54\162\151\x70\x6c\145\104\x45\x53")) {
                    goto GBw;
                }
                include_once "\x43\162\x79\x70\x74\x2f\x54\x72\x69\x70\x6c\145\x44\105\x53\56\x70\150\160";
                GBw:
                $fW = new Crypt_TripleDES();
                $fW->setKey($nT);
                $fW->setIV($X0);
                $X0 = strtoupper(bin2hex($X0));
                $ec = "\55\x2d\x2d\55\x2d\x42\x45\x47\x49\116\x20\122\x53\x41\x20\x50\122\x49\x56\101\124\x45\40\113\105\x59\55\x2d\55\x2d\x2d\xd\xa" . "\120\x72\x6f\143\x2d\x54\x79\x70\x65\72\x20\x34\x2c\x45\x4e\103\x52\x59\120\x54\105\104\15\xa" . "\104\x45\113\55\111\x6e\x66\157\x3a\40\104\x45\x53\55\105\x44\x45\x33\x2d\x43\x42\103\54{$X0}\15\12" . "\15\12" . chunk_split(base64_encode($fW->encrypt($ec)), 64) . "\x2d\55\55\55\55\105\x4e\x44\x20\x52\x53\x41\x20\120\122\111\x56\x41\124\x45\x20\113\x45\x59\x2d\x2d\x2d\x2d\55";
                BXp:
                return $ec;
        }
        bOn:
        l7a:
    }
    function _convertPublicKey($Bd, $mP)
    {
        $Qx = $this->publicKeyFormat != CRYPT_RSA_PUBLIC_FORMAT_XML;
        $sF = $Bd->toBytes($Qx);
        $TT = $mP->toBytes($Qx);
        switch ($this->publicKeyFormat) {
            case CRYPT_RSA_PUBLIC_FORMAT_RAW:
                return array("\x65" => $mP->copy(), "\156" => $Bd->copy());
            case CRYPT_RSA_PUBLIC_FORMAT_XML:
                return "\x3c\122\x53\101\113\145\x79\x56\x61\154\x75\x65\x3e\xd\xa" . "\x20\40\74\115\157\144\x75\x6c\165\x73\76" . base64_encode($sF) . "\74\57\x4d\157\x64\165\154\x75\x73\x3e\15\12" . "\40\x20\74\105\170\160\x6f\x6e\x65\x6e\164\76" . base64_encode($TT) . "\74\x2f\x45\170\160\x6f\x6e\145\156\x74\x3e\xd\xa" . "\74\x2f\122\123\x41\x4b\145\171\x56\x61\154\x75\145\76";
                goto tVW;
            case CRYPT_RSA_PUBLIC_FORMAT_OPENSSH:
                $m9 = pack("\116\141\x2a\116\x61\52\x4e\141\x2a", strlen("\x73\163\150\55\x72\x73\x61"), "\163\x73\x68\55\x72\x73\x61", strlen($TT), $TT, strlen($sF), $sF);
                $m9 = "\x73\163\x68\x2d\x72\163\141\40" . base64_encode($m9) . "\x20" . $this->comment;
                return $m9;
            default:
                $Nd = array("\155\157\x64\165\x6c\x75\x73" => pack("\x43\x61\x2a\141\52", CRYPT_RSA_ASN1_INTEGER, $this->_encodeLength(strlen($sF)), $sF), "\160\165\142\x6c\x69\x63\x45\x78\160\157\156\x65\156\164" => pack("\103\141\52\x61\x2a", CRYPT_RSA_ASN1_INTEGER, $this->_encodeLength(strlen($TT)), $TT));
                $m9 = pack("\103\141\52\x61\x2a\141\52", CRYPT_RSA_ASN1_SEQUENCE, $this->_encodeLength(strlen($Nd["\x6d\x6f\x64\x75\154\x75\163"]) + strlen($Nd["\160\165\x62\x6c\x69\143\105\170\x70\157\156\x65\156\164"])), $Nd["\155\x6f\x64\165\154\x75\x73"], $Nd["\x70\x75\142\154\x69\x63\x45\170\x70\x6f\x6e\145\156\x74"]);
                if ($this->publicKeyFormat == CRYPT_RSA_PUBLIC_FORMAT_PKCS1_RAW) {
                    goto Zfm;
                }
                $M7 = pack("\110\x2a", "\63\60\x30\144\60\66\x30\x39\62\x61\x38\x36\x34\70\70\x36\x66\67\x30\x64\x30\61\x30\x31\x30\61\x30\65\x30\x30");
                $m9 = chr(0) . $m9;
                $m9 = chr(3) . $this->_encodeLength(strlen($m9)) . $m9;
                $m9 = pack("\x43\x61\52\x61\52", CRYPT_RSA_ASN1_SEQUENCE, $this->_encodeLength(strlen($M7 . $m9)), $M7 . $m9);
                $m9 = "\55\x2d\55\55\55\102\x45\107\x49\116\x20\120\125\x42\x4c\111\103\x20\x4b\105\x59\55\x2d\55\55\x2d\15\xa" . chunk_split(base64_encode($m9), 64) . "\55\55\x2d\55\x2d\105\x4e\x44\40\x50\x55\102\x4c\x49\x43\40\113\105\131\55\55\x2d\55\x2d";
                goto B8G;
                Zfm:
                $m9 = "\x2d\x2d\x2d\55\x2d\x42\105\107\111\x4e\x20\122\x53\101\x20\x50\x55\x42\x4c\x49\x43\40\113\105\131\x2d\x2d\x2d\55\x2d\15\xa" . chunk_split(base64_encode($m9), 64) . "\x2d\x2d\55\55\x2d\105\x4e\x44\x20\x52\x53\101\x20\120\125\102\114\x49\x43\40\113\x45\131\x2d\55\x2d\x2d\55";
                B8G:
                return $m9;
        }
        SqL:
        tVW:
    }
    function _parseKey($Mr, $r9)
    {
        if (!($r9 != CRYPT_RSA_PUBLIC_FORMAT_RAW && !is_string($Mr))) {
            goto bo0;
        }
        return false;
        bo0:
        switch ($r9) {
            case CRYPT_RSA_PUBLIC_FORMAT_RAW:
                if (is_array($Mr)) {
                    goto xNZ;
                }
                return false;
                xNZ:
                $Nd = array();
                switch (true) {
                    case isset($Mr["\x65"]):
                        $Nd["\160\x75\142\154\151\143\x45\170\160\x6f\x6e\x65\156\x74"] = $Mr["\145"]->copy();
                        goto B_I;
                    case isset($Mr["\145\x78\160\157\156\x65\x6e\164"]):
                        $Nd["\160\x75\142\x6c\x69\143\x45\170\x70\157\x6e\x65\x6e\164"] = $Mr["\x65\170\160\x6f\x6e\145\x6e\x74"]->copy();
                        goto B_I;
                    case isset($Mr["\x70\x75\x62\154\x69\143\105\x78\160\157\156\x65\x6e\x74"]):
                        $Nd["\160\165\142\x6c\x69\x63\x45\x78\x70\157\156\x65\156\x74"] = $Mr["\160\x75\142\x6c\151\143\x45\170\160\x6f\x6e\145\156\164"]->copy();
                        goto B_I;
                    case isset($Mr[0]):
                        $Nd["\x70\165\x62\x6c\x69\x63\x45\x78\x70\157\156\x65\156\164"] = $Mr[0]->copy();
                }
                PhB:
                B_I:
                switch (true) {
                    case isset($Mr["\156"]):
                        $Nd["\x6d\157\144\x75\154\x75\163"] = $Mr["\x6e"]->copy();
                        goto dtz;
                    case isset($Mr["\155\x6f\144\x75\154\157"]):
                        $Nd["\155\x6f\144\165\x6c\x75\163"] = $Mr["\x6d\x6f\144\165\154\157"]->copy();
                        goto dtz;
                    case isset($Mr["\155\x6f\144\x75\154\x75\x73"]):
                        $Nd["\155\x6f\x64\165\154\x75\163"] = $Mr["\x6d\157\144\165\154\x75\x73"]->copy();
                        goto dtz;
                    case isset($Mr[1]):
                        $Nd["\x6d\157\144\x75\154\x75\163"] = $Mr[1]->copy();
                }
                nlf:
                dtz:
                return isset($Nd["\x6d\x6f\x64\165\x6c\x75\163"]) && isset($Nd["\x70\165\142\x6c\x69\x63\x45\x78\x70\x6f\156\x65\x6e\x74"]) ? $Nd : false;
            case CRYPT_RSA_PRIVATE_FORMAT_PKCS1:
            case CRYPT_RSA_PRIVATE_FORMAT_PKCS8:
            case CRYPT_RSA_PUBLIC_FORMAT_PKCS1:
                if (preg_match("\x23\104\105\x4b\55\x49\x6e\146\157\72\40\x28\x2e\x2b\x29\54\x28\56\x2b\51\x23", $Mr, $JY)) {
                    goto C3X;
                }
                $gy = $this->_extractBER($Mr);
                goto WSw;
                C3X:
                $X0 = pack("\110\52", trim($JY[2]));
                $nT = pack("\110\52", md5($this->password . substr($X0, 0, 8)));
                $nT .= pack("\x48\52", md5($nT . $this->password . substr($X0, 0, 8)));
                $Mr = preg_replace("\43\136\x28\x3f\72\120\162\x6f\143\x2d\x54\x79\x70\145\x7c\104\105\x4b\55\x49\156\146\x6f\51\72\40\x2e\x2a\x23\x6d", '', $Mr);
                $uY = $this->_extractBER($Mr);
                if (!($uY === false)) {
                    goto MQH;
                }
                $uY = $Mr;
                MQH:
                switch ($JY[1]) {
                    case "\101\x45\x53\x2d\62\x35\x36\x2d\x43\x42\103":
                        if (class_exists("\x43\x72\x79\160\164\137\101\105\x53")) {
                            goto RAf;
                        }
                        include_once "\x43\x72\171\160\164\x2f\101\105\123\x2e\x70\x68\160";
                        RAf:
                        $R3 = new Crypt_AES();
                        goto B_b;
                    case "\x41\105\123\x2d\x31\62\70\55\x43\102\x43":
                        if (class_exists("\x43\162\x79\x70\164\x5f\101\x45\123")) {
                            goto w_x;
                        }
                        include_once "\x43\162\x79\160\x74\57\101\105\123\56\x70\150\x70";
                        w_x:
                        $nT = substr($nT, 0, 16);
                        $R3 = new Crypt_AES();
                        goto B_b;
                    case "\104\105\x53\x2d\x45\x44\x45\x33\55\103\x46\x42":
                        if (class_exists("\103\162\171\x70\164\x5f\x54\162\151\160\154\145\104\105\x53")) {
                            goto sgZ;
                        }
                        include_once "\x43\162\x79\x70\164\x2f\x54\162\x69\x70\x6c\145\104\105\123\56\160\x68\160";
                        sgZ:
                        $R3 = new Crypt_TripleDES(CRYPT_DES_MODE_CFB);
                        goto B_b;
                    case "\x44\x45\123\55\105\104\105\63\55\103\x42\x43":
                        if (class_exists("\103\162\171\x70\x74\137\x54\162\x69\160\x6c\145\x44\x45\x53")) {
                            goto Occ;
                        }
                        include_once "\x43\x72\171\160\164\x2f\124\x72\x69\x70\154\145\x44\x45\x53\x2e\x70\x68\160";
                        Occ:
                        $nT = substr($nT, 0, 24);
                        $R3 = new Crypt_TripleDES();
                        goto B_b;
                    case "\x44\x45\x53\x2d\103\x42\103":
                        if (class_exists("\x43\x72\171\160\164\137\104\x45\123")) {
                            goto P1C;
                        }
                        include_once "\x43\162\171\x70\x74\x2f\x44\x45\123\56\x70\x68\160";
                        P1C:
                        $R3 = new Crypt_DES();
                        goto B_b;
                    default:
                        return false;
                }
                j8Y:
                B_b:
                $R3->setKey($nT);
                $R3->setIV($X0);
                $gy = $R3->decrypt($uY);
                WSw:
                if (!($gy !== false)) {
                    goto pyt;
                }
                $Mr = $gy;
                pyt:
                $Nd = array();
                if (!(ord($this->_string_shift($Mr)) != CRYPT_RSA_ASN1_SEQUENCE)) {
                    goto JL5;
                }
                return false;
                JL5:
                if (!($this->_decodeLength($Mr) != strlen($Mr))) {
                    goto xTY;
                }
                return false;
                xTY:
                $hp = ord($this->_string_shift($Mr));
                if (!($hp == CRYPT_RSA_ASN1_INTEGER && substr($Mr, 0, 3) == "\x1\0\60")) {
                    goto Fmt;
                }
                $this->_string_shift($Mr, 3);
                $hp = CRYPT_RSA_ASN1_SEQUENCE;
                Fmt:
                if (!($hp == CRYPT_RSA_ASN1_SEQUENCE)) {
                    goto KEm;
                }
                $zn = $this->_string_shift($Mr, $this->_decodeLength($Mr));
                if (!(ord($this->_string_shift($zn)) != CRYPT_RSA_ASN1_OBJECT)) {
                    goto cZL;
                }
                return false;
                cZL:
                $F_ = $this->_decodeLength($zn);
                switch ($this->_string_shift($zn, $F_)) {
                    case "\x2a\206\x48\x86\367\15\x1\1\x1":
                        goto ltM;
                    case "\x2a\206\110\206\367\15\1\x5\x3":
                        if (!(ord($this->_string_shift($zn)) != CRYPT_RSA_ASN1_SEQUENCE)) {
                            goto H1S;
                        }
                        return false;
                        H1S:
                        if (!($this->_decodeLength($zn) != strlen($zn))) {
                            goto gc_;
                        }
                        return false;
                        gc_:
                        $this->_string_shift($zn);
                        $mk = $this->_string_shift($zn, $this->_decodeLength($zn));
                        if (!(ord($this->_string_shift($zn)) != CRYPT_RSA_ASN1_INTEGER)) {
                            goto hx5;
                        }
                        return false;
                        hx5:
                        $this->_decodeLength($zn);
                        list(, $T0) = unpack("\116", str_pad($zn, 4, chr(0), STR_PAD_LEFT));
                        $this->_string_shift($Mr);
                        $F_ = $this->_decodeLength($Mr);
                        if (!(strlen($Mr) != $F_)) {
                            goto KH4;
                        }
                        return false;
                        KH4:
                        if (class_exists("\x43\x72\171\x70\164\137\104\x45\123")) {
                            goto Lko;
                        }
                        include_once "\x43\x72\171\160\164\x2f\104\x45\x53\56\x70\x68\x70";
                        Lko:
                        $R3 = new Crypt_DES();
                        $R3->setPassword($this->password, "\160\142\153\x64\x66\x31", "\155\x64\x35", $mk, $T0);
                        $Mr = $R3->decrypt($Mr);
                        if (!($Mr === false)) {
                            goto tOo;
                        }
                        return false;
                        tOo:
                        return $this->_parseKey($Mr, CRYPT_RSA_PRIVATE_FORMAT_PKCS1);
                    default:
                        return false;
                }
                hp3:
                ltM:
                $hp = ord($this->_string_shift($Mr));
                $this->_decodeLength($Mr);
                if (!($hp == CRYPT_RSA_ASN1_BITSTRING)) {
                    goto Zu8;
                }
                $this->_string_shift($Mr);
                Zu8:
                if (!(ord($this->_string_shift($Mr)) != CRYPT_RSA_ASN1_SEQUENCE)) {
                    goto Ypm;
                }
                return false;
                Ypm:
                if (!($this->_decodeLength($Mr) != strlen($Mr))) {
                    goto lt_;
                }
                return false;
                lt_:
                $hp = ord($this->_string_shift($Mr));
                KEm:
                if (!($hp != CRYPT_RSA_ASN1_INTEGER)) {
                    goto lRt;
                }
                return false;
                lRt:
                $F_ = $this->_decodeLength($Mr);
                $zn = $this->_string_shift($Mr, $F_);
                if (!(strlen($zn) != 1 || ord($zn) > 2)) {
                    goto ati;
                }
                $Nd["\155\x6f\144\165\x6c\165\x73"] = new Math_BigInteger($zn, 256);
                $this->_string_shift($Mr);
                $F_ = $this->_decodeLength($Mr);
                $Nd[$r9 == CRYPT_RSA_PUBLIC_FORMAT_PKCS1 ? "\160\165\142\x6c\151\x63\105\x78\x70\157\156\145\156\x74" : "\x70\162\x69\x76\x61\164\x65\105\x78\x70\x6f\156\x65\x6e\x74"] = new Math_BigInteger($this->_string_shift($Mr, $F_), 256);
                return $Nd;
                ati:
                if (!(ord($this->_string_shift($Mr)) != CRYPT_RSA_ASN1_INTEGER)) {
                    goto reV;
                }
                return false;
                reV:
                $F_ = $this->_decodeLength($Mr);
                $Nd["\x6d\x6f\x64\165\154\165\163"] = new Math_BigInteger($this->_string_shift($Mr, $F_), 256);
                $this->_string_shift($Mr);
                $F_ = $this->_decodeLength($Mr);
                $Nd["\x70\x75\x62\154\151\143\x45\x78\x70\157\x6e\x65\156\164"] = new Math_BigInteger($this->_string_shift($Mr, $F_), 256);
                $this->_string_shift($Mr);
                $F_ = $this->_decodeLength($Mr);
                $Nd["\160\x72\x69\166\x61\164\x65\x45\x78\160\x6f\x6e\145\x6e\164"] = new Math_BigInteger($this->_string_shift($Mr, $F_), 256);
                $this->_string_shift($Mr);
                $F_ = $this->_decodeLength($Mr);
                $Nd["\x70\x72\151\x6d\145\x73"] = array(1 => new Math_BigInteger($this->_string_shift($Mr, $F_), 256));
                $this->_string_shift($Mr);
                $F_ = $this->_decodeLength($Mr);
                $Nd["\160\x72\x69\155\x65\x73"][] = new Math_BigInteger($this->_string_shift($Mr, $F_), 256);
                $this->_string_shift($Mr);
                $F_ = $this->_decodeLength($Mr);
                $Nd["\145\x78\160\157\x6e\x65\156\x74\163"] = array(1 => new Math_BigInteger($this->_string_shift($Mr, $F_), 256));
                $this->_string_shift($Mr);
                $F_ = $this->_decodeLength($Mr);
                $Nd["\145\170\160\157\x6e\145\x6e\x74\x73"][] = new Math_BigInteger($this->_string_shift($Mr, $F_), 256);
                $this->_string_shift($Mr);
                $F_ = $this->_decodeLength($Mr);
                $Nd["\143\x6f\145\x66\x66\x69\143\151\145\156\x74\x73"] = array(2 => new Math_BigInteger($this->_string_shift($Mr, $F_), 256));
                if (empty($Mr)) {
                    goto z2S;
                }
                if (!(ord($this->_string_shift($Mr)) != CRYPT_RSA_ASN1_SEQUENCE)) {
                    goto gqy;
                }
                return false;
                gqy:
                $this->_decodeLength($Mr);
                oFA:
                if (empty($Mr)) {
                    goto yB4;
                }
                if (!(ord($this->_string_shift($Mr)) != CRYPT_RSA_ASN1_SEQUENCE)) {
                    goto IBf;
                }
                return false;
                IBf:
                $this->_decodeLength($Mr);
                $Mr = substr($Mr, 1);
                $F_ = $this->_decodeLength($Mr);
                $Nd["\160\x72\151\155\145\x73"][] = new Math_BigInteger($this->_string_shift($Mr, $F_), 256);
                $this->_string_shift($Mr);
                $F_ = $this->_decodeLength($Mr);
                $Nd["\x65\x78\x70\x6f\x6e\x65\x6e\164\x73"][] = new Math_BigInteger($this->_string_shift($Mr, $F_), 256);
                $this->_string_shift($Mr);
                $F_ = $this->_decodeLength($Mr);
                $Nd["\143\x6f\x65\x66\x66\151\x63\151\x65\156\164\163"][] = new Math_BigInteger($this->_string_shift($Mr, $F_), 256);
                goto oFA;
                yB4:
                z2S:
                return $Nd;
            case CRYPT_RSA_PUBLIC_FORMAT_OPENSSH:
                $UR = explode("\40", $Mr, 3);
                $Mr = isset($UR[1]) ? base64_decode($UR[1]) : false;
                if (!($Mr === false)) {
                    goto p7P;
                }
                return false;
                p7P:
                $d8 = isset($UR[2]) ? $UR[2] : false;
                $tY = substr($Mr, 0, 11) == "\0\0\0\x7\x73\163\x68\55\162\x73\141";
                if (!(strlen($Mr) <= 4)) {
                    goto UR3;
                }
                return false;
                UR3:
                extract(unpack("\116\x6c\145\x6e\x67\164\x68", $this->_string_shift($Mr, 4)));
                $TT = new Math_BigInteger($this->_string_shift($Mr, $F_), -256);
                if (!(strlen($Mr) <= 4)) {
                    goto nI9;
                }
                return false;
                nI9:
                extract(unpack("\116\x6c\x65\x6e\x67\x74\x68", $this->_string_shift($Mr, 4)));
                $sF = new Math_BigInteger($this->_string_shift($Mr, $F_), -256);
                if ($tY && strlen($Mr)) {
                    goto XUJ;
                }
                return strlen($Mr) ? false : array("\x6d\x6f\x64\x75\154\165\x73" => $sF, "\x70\x75\142\154\x69\x63\105\170\160\x6f\156\145\x6e\x74" => $TT, "\x63\x6f\x6d\x6d\x65\156\164" => $d8);
                goto r9A;
                XUJ:
                if (!(strlen($Mr) <= 4)) {
                    goto Fdf;
                }
                return false;
                Fdf:
                extract(unpack("\116\x6c\145\156\147\x74\150", $this->_string_shift($Mr, 4)));
                $aZ = new Math_BigInteger($this->_string_shift($Mr, $F_), -256);
                return strlen($Mr) ? false : array("\x6d\x6f\x64\x75\x6c\x75\163" => $aZ, "\x70\x75\142\x6c\x69\x63\105\170\160\157\x6e\145\x6e\x74" => $sF, "\x63\157\155\x6d\145\156\x74" => $d8);
                r9A:
            case CRYPT_RSA_PRIVATE_FORMAT_XML:
            case CRYPT_RSA_PUBLIC_FORMAT_XML:
                $this->components = array();
                $x1 = xml_parser_create("\x55\x54\106\55\70");
                xml_set_object($x1, $this);
                xml_set_element_handler($x1, "\137\163\x74\141\162\164\137\145\154\145\155\145\156\164\137\x68\x61\x6e\x64\x6c\145\x72", "\137\x73\x74\157\160\137\x65\x6c\x65\155\x65\156\164\137\x68\141\x6e\x64\154\x65\162");
                xml_set_character_data_handler($x1, "\x5f\144\x61\164\x61\x5f\x68\141\x6e\x64\x6c\145\x72");
                if (xml_parse($x1, "\74\x78\155\x6c\x3e" . $Mr . "\x3c\x2f\x78\x6d\154\76")) {
                    goto RdF;
                }
                return false;
                RdF:
                return isset($this->components["\155\x6f\144\165\x6c\x75\163"]) && isset($this->components["\160\x75\x62\154\151\143\105\170\x70\157\156\x65\156\164"]) ? $this->components : false;
            case CRYPT_RSA_PRIVATE_FORMAT_PUTTY:
                $Nd = array();
                $Mr = preg_split("\x23\x5c\162\x5c\156\x7c\134\162\174\134\x6e\x23", $Mr);
                $r9 = trim(preg_replace("\43\120\165\x54\x54\131\55\x55\x73\x65\162\55\x4b\145\171\x2d\x46\x69\x6c\145\55\x32\x3a\40\x28\56\x2b\51\43", "\44\x31", $Mr[0]));
                if (!($r9 != "\x73\x73\x68\x2d\x72\x73\141")) {
                    goto vtN;
                }
                return false;
                vtN:
                $KN = trim(preg_replace("\43\105\156\x63\162\x79\160\164\x69\x6f\156\72\x20\50\x2e\x2b\x29\43", "\x24\x31", $Mr[1]));
                $d8 = trim(preg_replace("\x23\103\x6f\x6d\155\145\x6e\164\x3a\x20\50\56\53\51\43", "\x24\x31", $Mr[2]));
                $L_ = trim(preg_replace("\x23\x50\x75\x62\x6c\x69\143\55\x4c\151\x6e\145\163\x3a\40\x28\x5c\144\53\x29\x23", "\x24\61", $Mr[3]));
                $wR = base64_decode(implode('', array_map("\x74\162\151\x6d", array_slice($Mr, 4, $L_))));
                $wR = substr($wR, 11);
                extract(unpack("\116\154\x65\x6e\147\164\x68", $this->_string_shift($wR, 4)));
                $Nd["\160\x75\x62\x6c\151\143\105\170\x70\157\156\x65\x6e\x74"] = new Math_BigInteger($this->_string_shift($wR, $F_), -256);
                extract(unpack("\x4e\x6c\x65\156\147\164\150", $this->_string_shift($wR, 4)));
                $Nd["\155\x6f\144\x75\x6c\x75\163"] = new Math_BigInteger($this->_string_shift($wR, $F_), -256);
                $PS = trim(preg_replace("\x23\x50\x72\151\x76\x61\x74\145\55\x4c\x69\156\145\x73\72\40\50\134\144\x2b\51\x23", "\x24\x31", $Mr[$L_ + 4]));
                $Xd = base64_decode(implode('', array_map("\x74\162\151\x6d", array_slice($Mr, $L_ + 5, $PS))));
                switch ($KN) {
                    case "\x61\x65\x73\62\x35\66\x2d\x63\x62\x63":
                        if (class_exists("\x43\162\x79\160\x74\x5f\x41\105\123")) {
                            goto DSg;
                        }
                        include_once "\103\x72\x79\160\x74\x2f\101\105\123\56\160\150\160";
                        DSg:
                        $nT = '';
                        $my = 0;
                        tUf:
                        if (!(strlen($nT) < 32)) {
                            goto X_T;
                        }
                        $zn = pack("\x4e\x61\x2a", $my++, $this->password);
                        $nT .= pack("\110\52", sha1($zn));
                        goto tUf;
                        X_T:
                        $nT = substr($nT, 0, 32);
                        $R3 = new Crypt_AES();
                }
                TTO:
                Ds1:
                if (!($KN != "\156\157\x6e\x65")) {
                    goto DF7;
                }
                $R3->setKey($nT);
                $R3->disablePadding();
                $Xd = $R3->decrypt($Xd);
                if (!($Xd === false)) {
                    goto H1l;
                }
                return false;
                H1l:
                DF7:
                extract(unpack("\116\x6c\145\x6e\x67\164\150", $this->_string_shift($Xd, 4)));
                if (!(strlen($Xd) < $F_)) {
                    goto P2w;
                }
                return false;
                P2w:
                $Nd["\160\x72\x69\x76\x61\164\145\105\x78\160\157\156\x65\x6e\x74"] = new Math_BigInteger($this->_string_shift($Xd, $F_), -256);
                extract(unpack("\116\154\x65\156\147\x74\x68", $this->_string_shift($Xd, 4)));
                if (!(strlen($Xd) < $F_)) {
                    goto u3A;
                }
                return false;
                u3A:
                $Nd["\x70\162\151\155\x65\x73"] = array(1 => new Math_BigInteger($this->_string_shift($Xd, $F_), -256));
                extract(unpack("\x4e\x6c\145\x6e\147\164\x68", $this->_string_shift($Xd, 4)));
                if (!(strlen($Xd) < $F_)) {
                    goto VLj;
                }
                return false;
                VLj:
                $Nd["\x70\x72\x69\155\x65\x73"][] = new Math_BigInteger($this->_string_shift($Xd, $F_), -256);
                $zn = $Nd["\160\162\x69\155\145\163"][1]->subtract($this->one);
                $Nd["\x65\x78\160\157\x6e\145\156\164\163"] = array(1 => $Nd["\x70\165\x62\x6c\x69\x63\x45\170\x70\157\156\145\156\x74"]->modInverse($zn));
                $zn = $Nd["\160\x72\151\x6d\x65\x73"][2]->subtract($this->one);
                $Nd["\x65\x78\x70\x6f\156\145\156\164\163"][] = $Nd["\x70\x75\x62\x6c\x69\143\105\x78\160\157\x6e\145\x6e\x74"]->modInverse($zn);
                extract(unpack("\116\x6c\x65\x6e\x67\164\x68", $this->_string_shift($Xd, 4)));
                if (!(strlen($Xd) < $F_)) {
                    goto c6O;
                }
                return false;
                c6O:
                $Nd["\143\x6f\x65\146\x66\151\143\x69\145\156\x74\163"] = array(2 => new Math_BigInteger($this->_string_shift($Xd, $F_), -256));
                return $Nd;
        }
        SqB:
        j9l:
    }
    function getSize()
    {
        return !isset($this->modulus) ? 0 : strlen($this->modulus->toBits());
    }
    function _start_element_handler($eS, $uQ, $GI)
    {
        switch ($uQ) {
            case "\x4d\117\x44\125\x4c\125\x53":
                $this->current =& $this->components["\155\x6f\144\x75\154\165\x73"];
                goto UI_;
            case "\105\130\120\117\116\x45\116\x54":
                $this->current =& $this->components["\160\x75\142\x6c\x69\x63\105\170\x70\x6f\x6e\145\x6e\164"];
                goto UI_;
            case "\120":
                $this->current =& $this->components["\160\162\x69\x6d\145\x73"][1];
                goto UI_;
            case "\121":
                $this->current =& $this->components["\x70\x72\151\x6d\145\163"][2];
                goto UI_;
            case "\x44\120":
                $this->current =& $this->components["\x65\170\160\157\x6e\145\156\164\x73"][1];
                goto UI_;
            case "\x44\121":
                $this->current =& $this->components["\145\170\x70\x6f\x6e\x65\156\164\163"][2];
                goto UI_;
            case "\x49\x4e\126\105\122\123\x45\121":
                $this->current =& $this->components["\143\157\145\x66\146\151\x63\x69\145\156\164\163"][2];
                goto UI_;
            case "\x44":
                $this->current =& $this->components["\x70\162\x69\166\141\164\x65\x45\170\x70\157\156\145\156\164"];
        }
        jh5:
        UI_:
        $this->current = '';
    }
    function _stop_element_handler($eS, $uQ)
    {
        if (!isset($this->current)) {
            goto JOt;
        }
        $this->current = new Math_BigInteger(base64_decode($this->current), 256);
        unset($this->current);
        JOt:
    }
    function _data_handler($eS, $p3)
    {
        if (!(!isset($this->current) || is_object($this->current))) {
            goto RK0;
        }
        return;
        RK0:
        $this->current .= trim($p3);
    }
    function loadKey($Mr, $r9 = false)
    {
        if (!(is_object($Mr) && strtolower(get_class($Mr)) == "\143\162\x79\160\x74\137\162\x73\x61")) {
            goto xe9;
        }
        $this->privateKeyFormat = $Mr->privateKeyFormat;
        $this->publicKeyFormat = $Mr->publicKeyFormat;
        $this->k = $Mr->k;
        $this->hLen = $Mr->hLen;
        $this->sLen = $Mr->sLen;
        $this->mgfHLen = $Mr->mgfHLen;
        $this->encryptionMode = $Mr->encryptionMode;
        $this->signatureMode = $Mr->signatureMode;
        $this->password = $Mr->password;
        $this->configFile = $Mr->configFile;
        $this->comment = $Mr->comment;
        if (!is_object($Mr->hash)) {
            goto Ney;
        }
        $this->hash = new Crypt_Hash($Mr->hash->getHash());
        Ney:
        if (!is_object($Mr->mgfHash)) {
            goto HOE;
        }
        $this->mgfHash = new Crypt_Hash($Mr->mgfHash->getHash());
        HOE:
        if (!is_object($Mr->modulus)) {
            goto j2i;
        }
        $this->modulus = $Mr->modulus->copy();
        j2i:
        if (!is_object($Mr->exponent)) {
            goto n3f;
        }
        $this->exponent = $Mr->exponent->copy();
        n3f:
        if (!is_object($Mr->publicExponent)) {
            goto mm4;
        }
        $this->publicExponent = $Mr->publicExponent->copy();
        mm4:
        $this->primes = array();
        $this->exponents = array();
        $this->coefficients = array();
        foreach ($this->primes as $jb) {
            $this->primes[] = $jb->copy();
            U4m:
        }
        ynK:
        foreach ($this->exponents as $sA) {
            $this->exponents[] = $sA->copy();
            toQ:
        }
        Q45:
        foreach ($this->coefficients as $sI) {
            $this->coefficients[] = $sI->copy();
            h6I:
        }
        JFz:
        return true;
        xe9:
        if ($r9 === false) {
            goto t5z;
        }
        $Nd = $this->_parseKey($Mr, $r9);
        goto n9l;
        t5z:
        $Hc = array(CRYPT_RSA_PUBLIC_FORMAT_RAW, CRYPT_RSA_PRIVATE_FORMAT_PKCS1, CRYPT_RSA_PRIVATE_FORMAT_XML, CRYPT_RSA_PRIVATE_FORMAT_PUTTY, CRYPT_RSA_PUBLIC_FORMAT_OPENSSH);
        foreach ($Hc as $r9) {
            $Nd = $this->_parseKey($Mr, $r9);
            if (!($Nd !== false)) {
                goto esR;
            }
            goto kCq;
            esR:
            yq2:
        }
        kCq:
        n9l:
        if (!($Nd === false)) {
            goto gsm;
        }
        $this->comment = null;
        $this->modulus = null;
        $this->k = null;
        $this->exponent = null;
        $this->primes = null;
        $this->exponents = null;
        $this->coefficients = null;
        $this->publicExponent = null;
        return false;
        gsm:
        if (!(isset($Nd["\143\157\x6d\x6d\145\x6e\164"]) && $Nd["\x63\x6f\x6d\155\145\156\164"] !== false)) {
            goto sf4;
        }
        $this->comment = $Nd["\143\157\x6d\155\x65\x6e\x74"];
        sf4:
        $this->modulus = $Nd["\155\x6f\x64\165\154\165\x73"];
        $this->k = strlen($this->modulus->toBytes());
        $this->exponent = isset($Nd["\160\162\x69\166\141\164\145\105\x78\x70\157\156\145\156\x74"]) ? $Nd["\160\x72\x69\x76\141\x74\145\x45\x78\160\x6f\156\x65\x6e\x74"] : $Nd["\x70\165\x62\x6c\151\x63\x45\170\160\157\x6e\145\156\x74"];
        if (isset($Nd["\160\x72\151\155\x65\x73"])) {
            goto CZ_;
        }
        $this->primes = array();
        $this->exponents = array();
        $this->coefficients = array();
        $this->publicExponent = false;
        goto gRs;
        CZ_:
        $this->primes = $Nd["\160\162\x69\x6d\x65\163"];
        $this->exponents = $Nd["\145\170\x70\x6f\156\145\x6e\x74\163"];
        $this->coefficients = $Nd["\143\157\145\146\146\x69\x63\151\145\x6e\x74\x73"];
        $this->publicExponent = $Nd["\x70\165\142\154\151\x63\105\170\160\x6f\156\145\x6e\x74"];
        gRs:
        switch ($r9) {
            case CRYPT_RSA_PUBLIC_FORMAT_OPENSSH:
            case CRYPT_RSA_PUBLIC_FORMAT_RAW:
                $this->setPublicKey();
                goto OLh;
            case CRYPT_RSA_PRIVATE_FORMAT_PKCS1:
                switch (true) {
                    case strpos($Mr, "\x2d\102\105\x47\x49\x4e\x20\120\125\102\x4c\111\103\40\113\x45\x59\x2d") !== false:
                    case strpos($Mr, "\55\102\105\107\111\x4e\x20\122\x53\101\40\x50\x55\102\x4c\111\x43\40\x4b\105\x59\55") !== false:
                        $this->setPublicKey();
                }
                S63:
                pqD:
        }
        vwv:
        OLh:
        return true;
    }
    function setPassword($Jj = false)
    {
        $this->password = $Jj;
    }
    function setPublicKey($Mr = false, $r9 = false)
    {
        if (empty($this->publicExponent)) {
            goto wvC;
        }
        return false;
        wvC:
        if (!($Mr === false && !empty($this->modulus))) {
            goto enC;
        }
        $this->publicExponent = $this->exponent;
        return true;
        enC:
        if ($r9 === false) {
            goto JN_;
        }
        $Nd = $this->_parseKey($Mr, $r9);
        goto P3m;
        JN_:
        $Hc = array(CRYPT_RSA_PUBLIC_FORMAT_RAW, CRYPT_RSA_PUBLIC_FORMAT_PKCS1, CRYPT_RSA_PUBLIC_FORMAT_XML, CRYPT_RSA_PUBLIC_FORMAT_OPENSSH);
        foreach ($Hc as $r9) {
            $Nd = $this->_parseKey($Mr, $r9);
            if (!($Nd !== false)) {
                goto bnf;
            }
            goto R_K;
            bnf:
            hjq:
        }
        R_K:
        P3m:
        if (!($Nd === false)) {
            goto xpG;
        }
        return false;
        xpG:
        if (!(empty($this->modulus) || !$this->modulus->equals($Nd["\155\157\x64\x75\154\x75\163"]))) {
            goto BSd;
        }
        $this->modulus = $Nd["\155\157\x64\x75\154\x75\x73"];
        $this->exponent = $this->publicExponent = $Nd["\x70\x75\142\x6c\151\143\x45\170\x70\157\x6e\x65\156\x74"];
        return true;
        BSd:
        $this->publicExponent = $Nd["\160\x75\x62\x6c\151\143\x45\170\160\x6f\x6e\x65\156\x74"];
        return true;
    }
    function setPrivateKey($Mr = false, $r9 = false)
    {
        if (!($Mr === false && !empty($this->publicExponent))) {
            goto OBT;
        }
        $this->publicExponent = false;
        return true;
        OBT:
        $se = new Crypt_RSA();
        if ($se->loadKey($Mr, $r9)) {
            goto wNn;
        }
        return false;
        wNn:
        $se->publicExponent = false;
        $this->loadKey($se);
        return true;
    }
    function getPublicKey($r9 = CRYPT_RSA_PUBLIC_FORMAT_PKCS8)
    {
        if (!(empty($this->modulus) || empty($this->publicExponent))) {
            goto BHA;
        }
        return false;
        BHA:
        $GL = $this->publicKeyFormat;
        $this->publicKeyFormat = $r9;
        $zn = $this->_convertPublicKey($this->modulus, $this->publicExponent);
        $this->publicKeyFormat = $GL;
        return $zn;
    }
    function getPublicKeyFingerprint($qG = "\x6d\x64\x35")
    {
        if (!(empty($this->modulus) || empty($this->publicExponent))) {
            goto sq9;
        }
        return false;
        sq9:
        $sF = $this->modulus->toBytes(true);
        $TT = $this->publicExponent->toBytes(true);
        $m9 = pack("\x4e\141\x2a\116\x61\x2a\116\141\x2a", strlen("\x73\163\150\x2d\x72\163\141"), "\163\163\x68\55\x72\x73\x61", strlen($TT), $TT, strlen($sF), $sF);
        switch ($qG) {
            case "\x73\x68\x61\x32\x35\66":
                $H2 = new Crypt_Hash("\163\150\141\x32\65\66");
                $gk = base64_encode($H2->hash($m9));
                return substr($gk, 0, strlen($gk) - 1);
            case "\155\x64\65":
                return substr(chunk_split(md5($m9), 2, "\72"), 0, -1);
            default:
                return false;
        }
        vGQ:
        hAI:
    }
    function getPrivateKey($r9 = CRYPT_RSA_PUBLIC_FORMAT_PKCS1)
    {
        if (!empty($this->primes)) {
            goto MmW;
        }
        return false;
        MmW:
        $GL = $this->privateKeyFormat;
        $this->privateKeyFormat = $r9;
        $zn = $this->_convertPrivateKey($this->modulus, $this->publicExponent, $this->exponent, $this->primes, $this->exponents, $this->coefficients);
        $this->privateKeyFormat = $GL;
        return $zn;
    }
    function _getPrivatePublicKey($pU = CRYPT_RSA_PUBLIC_FORMAT_PKCS8)
    {
        if (!(empty($this->modulus) || empty($this->exponent))) {
            goto xqd;
        }
        return false;
        xqd:
        $GL = $this->publicKeyFormat;
        $this->publicKeyFormat = $pU;
        $zn = $this->_convertPublicKey($this->modulus, $this->exponent);
        $this->publicKeyFormat = $GL;
        return $zn;
    }
    function __toString()
    {
        $Mr = $this->getPrivateKey($this->privateKeyFormat);
        if (!($Mr !== false)) {
            goto f2i;
        }
        return $Mr;
        f2i:
        $Mr = $this->_getPrivatePublicKey($this->publicKeyFormat);
        return $Mr !== false ? $Mr : '';
    }
    function __clone()
    {
        $Mr = new Crypt_RSA();
        $Mr->loadKey($this);
        return $Mr;
    }
    function _generateMinMax($Xs)
    {
        $yH = $Xs >> 3;
        $sr = str_repeat(chr(0), $yH);
        $HK = str_repeat(chr(0xff), $yH);
        $wx = $Xs & 7;
        if ($wx) {
            goto AbX;
        }
        $sr[0] = chr(0x80);
        goto no1;
        AbX:
        $sr = chr(1 << $wx - 1) . $sr;
        $HK = chr((1 << $wx) - 1) . $HK;
        no1:
        return array("\x6d\151\156" => new Math_BigInteger($sr, 256), "\x6d\141\x78" => new Math_BigInteger($HK, 256));
    }
    function _decodeLength(&$P3)
    {
        $F_ = ord($this->_string_shift($P3));
        if (!($F_ & 0x80)) {
            goto Uhw;
        }
        $F_ &= 0x7f;
        $zn = $this->_string_shift($P3, $F_);
        list(, $F_) = unpack("\116", substr(str_pad($zn, 4, chr(0), STR_PAD_LEFT), -4));
        Uhw:
        return $F_;
    }
    function _encodeLength($F_)
    {
        if (!($F_ <= 0x7f)) {
            goto CLn;
        }
        return chr($F_);
        CLn:
        $zn = ltrim(pack("\x4e", $F_), chr(0));
        return pack("\103\141\x2a", 0x80 | strlen($zn), $zn);
    }
    function _string_shift(&$P3, $Vo = 1)
    {
        $r3 = substr($P3, 0, $Vo);
        $P3 = substr($P3, $Vo);
        return $r3;
    }
    function setPrivateKeyFormat($MP)
    {
        $this->privateKeyFormat = $MP;
    }
    function setPublicKeyFormat($MP)
    {
        $this->publicKeyFormat = $MP;
    }
    function setHash($H2)
    {
        switch ($H2) {
            case "\x6d\x64\62":
            case "\x6d\144\65":
            case "\x73\150\141\x31":
            case "\163\150\141\62\x35\x36":
            case "\163\150\141\x33\x38\x34":
            case "\x73\150\141\65\x31\62":
                $this->hash = new Crypt_Hash($H2);
                $this->hashName = $H2;
                goto otY;
            default:
                $this->hash = new Crypt_Hash("\x73\150\x61\x31");
                $this->hashName = "\163\x68\x61\x31";
        }
        nIx:
        otY:
        $this->hLen = $this->hash->getLength();
    }
    function setMGFHash($H2)
    {
        switch ($H2) {
            case "\x6d\x64\62":
            case "\155\x64\x35":
            case "\x73\150\141\61":
            case "\x73\x68\x61\x32\65\66":
            case "\x73\x68\x61\63\x38\x34":
            case "\163\150\x61\65\61\62":
                $this->mgfHash = new Crypt_Hash($H2);
                goto o7x;
            default:
                $this->mgfHash = new Crypt_Hash("\x73\150\141\x31");
        }
        xzR:
        o7x:
        $this->mgfHLen = $this->mgfHash->getLength();
    }
    function setSaltLength($MU)
    {
        $this->sLen = $MU;
    }
    function _i2osp($k5, $YR)
    {
        $k5 = $k5->toBytes();
        if (!(strlen($k5) > $YR)) {
            goto vQG;
        }
        user_error("\x49\156\164\145\147\145\162\x20\164\157\157\x20\x6c\x61\x72\147\x65");
        return false;
        vQG:
        return str_pad($k5, $YR, chr(0), STR_PAD_LEFT);
    }
    function _os2ip($k5)
    {
        return new Math_BigInteger($k5, 256);
    }
    function _exponentiate($k5)
    {
        switch (true) {
            case empty($this->primes):
            case $this->primes[1]->equals($this->zero):
            case empty($this->coefficients):
            case $this->coefficients[2]->equals($this->zero):
            case empty($this->exponents):
            case $this->exponents[1]->equals($this->zero):
                return $k5->modPow($this->exponent, $this->modulus);
        }
        yxD:
        vbZ:
        $Q8 = count($this->primes);
        if (defined("\103\122\131\x50\x54\137\122\x53\x41\x5f\x44\111\x53\x41\x42\114\x45\137\x42\x4c\x49\x4e\104\x49\x4e\x47")) {
            goto ugv;
        }
        $Wx = $this->primes[1];
        $zY = 2;
        Z3G:
        if (!($zY <= $Q8)) {
            goto Ewi;
        }
        if (!($Wx->compare($this->primes[$zY]) > 0)) {
            goto RFJ;
        }
        $Wx = $this->primes[$zY];
        RFJ:
        SaM:
        $zY++;
        goto Z3G;
        Ewi:
        $fb = new Math_BigInteger(1);
        $lO = $fb->random($fb, $Wx->subtract($fb));
        $Ac = array(1 => $this->_blind($k5, $lO, 1), 2 => $this->_blind($k5, $lO, 2));
        $AX = $Ac[1]->subtract($Ac[2]);
        $AX = $AX->multiply($this->coefficients[2]);
        list(, $AX) = $AX->divide($this->primes[1]);
        $X3 = $Ac[2]->add($AX->multiply($this->primes[2]));
        $lO = $this->primes[1];
        $zY = 3;
        rgE:
        if (!($zY <= $Q8)) {
            goto Csv;
        }
        $Ac = $this->_blind($k5, $lO, $zY);
        $lO = $lO->multiply($this->primes[$zY - 1]);
        $AX = $Ac->subtract($X3);
        $AX = $AX->multiply($this->coefficients[$zY]);
        list(, $AX) = $AX->divide($this->primes[$zY]);
        $X3 = $X3->add($lO->multiply($AX));
        kVP:
        $zY++;
        goto rgE;
        Csv:
        goto sA4;
        ugv:
        $Ac = array(1 => $k5->modPow($this->exponents[1], $this->primes[1]), 2 => $k5->modPow($this->exponents[2], $this->primes[2]));
        $AX = $Ac[1]->subtract($Ac[2]);
        $AX = $AX->multiply($this->coefficients[2]);
        list(, $AX) = $AX->divide($this->primes[1]);
        $X3 = $Ac[2]->add($AX->multiply($this->primes[2]));
        $lO = $this->primes[1];
        $zY = 3;
        qWI:
        if (!($zY <= $Q8)) {
            goto Bnd;
        }
        $Ac = $k5->modPow($this->exponents[$zY], $this->primes[$zY]);
        $lO = $lO->multiply($this->primes[$zY - 1]);
        $AX = $Ac->subtract($X3);
        $AX = $AX->multiply($this->coefficients[$zY]);
        list(, $AX) = $AX->divide($this->primes[$zY]);
        $X3 = $X3->add($lO->multiply($AX));
        IB9:
        $zY++;
        goto qWI;
        Bnd:
        sA4:
        return $X3;
    }
    function _blind($k5, $lO, $zY)
    {
        $k5 = $k5->multiply($lO->modPow($this->publicExponent, $this->primes[$zY]));
        $k5 = $k5->modPow($this->exponents[$zY], $this->primes[$zY]);
        $lO = $lO->modInverse($this->primes[$zY]);
        $k5 = $k5->multiply($lO);
        list(, $k5) = $k5->divide($this->primes[$zY]);
        return $k5;
    }
    function _equals($k5, $fZ)
    {
        if (!(strlen($k5) != strlen($fZ))) {
            goto y69;
        }
        return false;
        y69:
        $DE = 0;
        $zY = 0;
        tj4:
        if (!($zY < strlen($k5))) {
            goto ee3;
        }
        $DE |= ord($k5[$zY]) ^ ord($fZ[$zY]);
        WVH:
        $zY++;
        goto tj4;
        ee3:
        return $DE == 0;
    }
    function _rsaep($X3)
    {
        if (!($X3->compare($this->zero) < 0 || $X3->compare($this->modulus) > 0)) {
            goto eGy;
        }
        user_error("\x4d\x65\163\163\x61\x67\x65\40\x72\x65\x70\x72\145\163\145\156\x74\x61\x74\x69\x76\x65\x20\157\x75\164\40\157\146\40\x72\141\156\x67\x65");
        return false;
        eGy:
        return $this->_exponentiate($X3);
    }
    function _rsadp($eo)
    {
        if (!($eo->compare($this->zero) < 0 || $eo->compare($this->modulus) > 0)) {
            goto uGw;
        }
        user_error("\x43\151\160\x68\x65\162\x74\145\170\x74\40\162\x65\x70\x72\145\x73\x65\156\164\x61\x74\151\x76\x65\40\x6f\165\164\40\157\x66\40\162\141\156\x67\x65");
        return false;
        uGw:
        return $this->_exponentiate($eo);
    }
    function _rsasp1($X3)
    {
        if (!($X3->compare($this->zero) < 0 || $X3->compare($this->modulus) > 0)) {
            goto So1;
        }
        user_error("\115\145\x73\x73\x61\x67\145\40\162\x65\x70\x72\145\163\x65\x6e\164\141\x74\x69\x76\145\x20\157\165\x74\40\157\x66\x20\162\x61\156\x67\x65");
        return false;
        So1:
        return $this->_exponentiate($X3);
    }
    function _rsavp1($Z7)
    {
        if (!($Z7->compare($this->zero) < 0 || $Z7->compare($this->modulus) > 0)) {
            goto WW4;
        }
        user_error("\x53\x69\147\156\x61\x74\x75\x72\x65\40\162\x65\x70\162\145\163\145\156\x74\141\x74\151\x76\145\x20\x6f\x75\x74\x20\x6f\x66\40\162\141\156\147\x65");
        return false;
        WW4:
        return $this->_exponentiate($Z7);
    }
    function _mgf1($lY, $y8)
    {
        $sl = '';
        $oi = ceil($y8 / $this->mgfHLen);
        $zY = 0;
        dnA:
        if (!($zY < $oi)) {
            goto Vfm;
        }
        $eo = pack("\116", $zY);
        $sl .= $this->mgfHash->hash($lY . $eo);
        Yuk:
        $zY++;
        goto dnA;
        Vfm:
        return substr($sl, 0, $y8);
    }
    function _rsaes_oaep_encrypt($X3, $O7 = '')
    {
        $M2 = strlen($X3);
        if (!($M2 > $this->k - 2 * $this->hLen - 2)) {
            goto t_R;
        }
        user_error("\x4d\145\163\163\141\x67\145\x20\164\x6f\157\x20\154\x6f\x6e\147");
        return false;
        t_R:
        $Y4 = $this->hash->hash($O7);
        $UA = str_repeat(chr(0), $this->k - $M2 - 2 * $this->hLen - 2);
        $rU = $Y4 . $UA . chr(1) . $X3;
        $XR = crypt_random_string($this->hLen);
        $Ru = $this->_mgf1($XR, $this->k - $this->hLen - 1);
        $U5 = $rU ^ $Ru;
        $Dm = $this->_mgf1($U5, $this->hLen);
        $VX = $XR ^ $Dm;
        $FX = chr(0) . $VX . $U5;
        $X3 = $this->_os2ip($FX);
        $eo = $this->_rsaep($X3);
        $eo = $this->_i2osp($eo, $this->k);
        return $eo;
    }
    function _rsaes_oaep_decrypt($eo, $O7 = '')
    {
        if (!(strlen($eo) != $this->k || $this->k < 2 * $this->hLen + 2)) {
            goto akX;
        }
        user_error("\x44\x65\143\162\x79\x70\x74\151\x6f\156\x20\145\162\x72\x6f\162");
        return false;
        akX:
        $eo = $this->_os2ip($eo);
        $X3 = $this->_rsadp($eo);
        if (!($X3 === false)) {
            goto Hgv;
        }
        user_error("\x44\145\x63\x72\171\x70\x74\x69\x6f\156\40\145\162\162\157\162");
        return false;
        Hgv:
        $FX = $this->_i2osp($X3, $this->k);
        $Y4 = $this->hash->hash($O7);
        $fZ = ord($FX[0]);
        $VX = substr($FX, 1, $this->hLen);
        $U5 = substr($FX, $this->hLen + 1);
        $Dm = $this->_mgf1($U5, $this->hLen);
        $XR = $VX ^ $Dm;
        $Ru = $this->_mgf1($XR, $this->k - $this->hLen - 1);
        $rU = $U5 ^ $Ru;
        $jJ = substr($rU, 0, $this->hLen);
        $X3 = substr($rU, $this->hLen);
        if ($this->_equals($Y4, $jJ)) {
            goto wVH;
        }
        user_error("\104\x65\143\x72\171\160\164\x69\157\156\40\145\162\162\x6f\162");
        return false;
        wVH:
        $X3 = ltrim($X3, chr(0));
        if (!(ord($X3[0]) != 1)) {
            goto Y2X;
        }
        user_error("\104\x65\x63\x72\x79\160\x74\x69\x6f\x6e\40\x65\x72\x72\157\x72");
        return false;
        Y2X:
        return substr($X3, 1);
    }
    function _raw_encrypt($X3)
    {
        $zn = $this->_os2ip($X3);
        $zn = $this->_rsaep($zn);
        return $this->_i2osp($zn, $this->k);
    }
    function _rsaes_pkcs1_v1_5_encrypt($X3)
    {
        $M2 = strlen($X3);
        if (!($M2 > $this->k - 11)) {
            goto dFX;
        }
        user_error("\115\145\163\x73\x61\x67\x65\x20\x74\157\x6f\40\x6c\x6f\x6e\147");
        return false;
        dFX:
        $fj = $this->k - $M2 - 3;
        $UA = '';
        KMe:
        if (!(strlen($UA) != $fj)) {
            goto yjl;
        }
        $zn = crypt_random_string($fj - strlen($UA));
        $zn = str_replace("\x0", '', $zn);
        $UA .= $zn;
        goto KMe;
        yjl:
        $r9 = 2;
        if (!(defined("\103\122\131\120\x54\x5f\122\x53\x41\x5f\120\x4b\103\x53\x31\x35\x5f\103\117\115\120\x41\x54") && (!isset($this->publicExponent) || $this->exponent !== $this->publicExponent))) {
            goto gq8;
        }
        $r9 = 1;
        $UA = str_repeat("\377", $fj);
        gq8:
        $FX = chr(0) . chr($r9) . $UA . chr(0) . $X3;
        $X3 = $this->_os2ip($FX);
        $eo = $this->_rsaep($X3);
        $eo = $this->_i2osp($eo, $this->k);
        return $eo;
    }
    function _rsaes_pkcs1_v1_5_decrypt($eo)
    {
        if (!(strlen($eo) != $this->k)) {
            goto NmE;
        }
        user_error("\104\x65\x63\162\x79\160\x74\x69\157\x6e\40\x65\162\x72\157\x72");
        return false;
        NmE:
        $eo = $this->_os2ip($eo);
        $X3 = $this->_rsadp($eo);
        if (!($X3 === false)) {
            goto auT;
        }
        user_error("\104\x65\x63\x72\171\160\164\151\x6f\156\40\x65\162\x72\x6f\x72");
        return false;
        auT:
        $FX = $this->_i2osp($X3, $this->k);
        if (!(ord($FX[0]) != 0 || ord($FX[1]) > 2)) {
            goto EAk;
        }
        user_error("\x44\x65\143\x72\x79\160\x74\151\157\x6e\40\x65\162\162\157\162");
        return false;
        EAk:
        $UA = substr($FX, 2, strpos($FX, chr(0), 2) - 2);
        $X3 = substr($FX, strlen($UA) + 3);
        if (!(strlen($UA) < 8)) {
            goto LrR;
        }
        user_error("\104\145\x63\x72\x79\160\x74\151\157\x6e\x20\x65\x72\x72\157\x72");
        return false;
        LrR:
        return $X3;
    }
    function _emsa_pss_encode($X3, $P1)
    {
        $UM = $P1 + 1 >> 3;
        $MU = $this->sLen !== null ? $this->sLen : $this->hLen;
        $Sw = $this->hash->hash($X3);
        if (!($UM < $this->hLen + $MU + 2)) {
            goto L6U;
        }
        user_error("\105\156\x63\x6f\144\151\156\147\x20\145\x72\x72\x6f\x72");
        return false;
        L6U:
        $mk = crypt_random_string($MU);
        $Vl = "\x0\0\0\0\x0\0\x0\x0" . $Sw . $mk;
        $AX = $this->hash->hash($Vl);
        $UA = str_repeat(chr(0), $UM - $MU - $this->hLen - 2);
        $rU = $UA . chr(1) . $mk;
        $Ru = $this->_mgf1($AX, $UM - $this->hLen - 1);
        $U5 = $rU ^ $Ru;
        $U5[0] = ~chr(0xff << ($P1 & 7)) & $U5[0];
        $FX = $U5 . $AX . chr(0xbc);
        return $FX;
    }
    function _emsa_pss_verify($X3, $FX, $P1)
    {
        $UM = $P1 + 1 >> 3;
        $MU = $this->sLen !== null ? $this->sLen : $this->hLen;
        $Sw = $this->hash->hash($X3);
        if (!($UM < $this->hLen + $MU + 2)) {
            goto qPQ;
        }
        return false;
        qPQ:
        if (!($FX[strlen($FX) - 1] != chr(0xbc))) {
            goto R10;
        }
        return false;
        R10:
        $U5 = substr($FX, 0, -$this->hLen - 1);
        $AX = substr($FX, -$this->hLen - 1, $this->hLen);
        $zn = chr(0xff << ($P1 & 7));
        if (!((~$U5[0] & $zn) != $zn)) {
            goto njr;
        }
        return false;
        njr:
        $Ru = $this->_mgf1($AX, $UM - $this->hLen - 1);
        $rU = $U5 ^ $Ru;
        $rU[0] = ~chr(0xff << ($P1 & 7)) & $rU[0];
        $zn = $UM - $this->hLen - $MU - 2;
        if (!(substr($rU, 0, $zn) != str_repeat(chr(0), $zn) || ord($rU[$zn]) != 1)) {
            goto poe;
        }
        return false;
        poe:
        $mk = substr($rU, $zn + 1);
        $Vl = "\0\0\x0\x0\x0\0\0\0" . $Sw . $mk;
        $fB = $this->hash->hash($Vl);
        return $this->_equals($AX, $fB);
    }
    function _rsassa_pss_sign($X3)
    {
        $FX = $this->_emsa_pss_encode($X3, 8 * $this->k - 1);
        $X3 = $this->_os2ip($FX);
        $Z7 = $this->_rsasp1($X3);
        $Z7 = $this->_i2osp($Z7, $this->k);
        return $Z7;
    }
    function _rsassa_pss_verify($X3, $Z7)
    {
        if (!(strlen($Z7) != $this->k)) {
            goto nn1;
        }
        user_error("\x49\x6e\x76\x61\x6c\x69\144\40\163\151\147\x6e\141\x74\x75\x72\145");
        return false;
        nn1:
        $Bv = 8 * $this->k;
        $Ne = $this->_os2ip($Z7);
        $Vl = $this->_rsavp1($Ne);
        if (!($Vl === false)) {
            goto gQo;
        }
        user_error("\x49\x6e\166\x61\154\151\144\x20\x73\x69\147\156\x61\164\x75\x72\x65");
        return false;
        gQo:
        $FX = $this->_i2osp($Vl, $Bv >> 3);
        if (!($FX === false)) {
            goto FMJ;
        }
        user_error("\x49\156\166\141\x6c\x69\144\40\163\151\x67\x6e\x61\164\165\162\145");
        return false;
        FMJ:
        return $this->_emsa_pss_verify($X3, $FX, $Bv - 1);
    }
    function _emsa_pkcs1_v1_5_encode($X3, $UM)
    {
        $AX = $this->hash->hash($X3);
        if (!($AX === false)) {
            goto DyT;
        }
        return false;
        DyT:
        switch ($this->hashName) {
            case "\155\x64\62":
                $sl = pack("\x48\x2a", "\63\60\62\60\x33\x30\x30\143\60\66\x30\70\62\x61\x38\66\64\x38\x38\66\x66\67\60\144\x30\x32\x30\x32\x30\65\x30\60\60\64\61\60");
                goto lAT;
            case "\x6d\x64\x35":
                $sl = pack("\x48\52", "\x33\60\x32\x30\x33\x30\x30\143\x30\x36\x30\x38\x32\x61\x38\x36\x34\70\x38\66\146\x37\x30\144\60\62\60\x35\60\x35\60\x30\x30\x34\61\60");
                goto lAT;
            case "\x73\150\141\61":
                $sl = pack("\x48\x2a", "\x33\x30\x32\x31\63\60\60\71\x30\x36\x30\65\62\x62\x30\x65\60\63\60\x32\x31\141\x30\65\60\x30\x30\64\x31\x34");
                goto lAT;
            case "\163\150\141\x32\x35\x36":
                $sl = pack("\110\x2a", "\x33\60\x33\x31\x33\x30\60\144\x30\66\x30\71\66\60\70\66\x34\70\x30\61\x36\65\x30\63\60\x34\60\x32\x30\x31\60\65\x30\x30\x30\64\62\60");
                goto lAT;
            case "\163\x68\x61\63\70\x34":
                $sl = pack("\x48\x2a", "\63\x30\64\x31\x33\60\60\x64\60\66\x30\71\x36\x30\x38\66\64\x38\60\61\66\x35\x30\x33\x30\64\x30\x32\60\62\x30\x35\x30\x30\x30\x34\63\x30");
                goto lAT;
            case "\x73\150\141\x35\x31\62":
                $sl = pack("\110\x2a", "\63\60\x35\x31\63\x30\x30\x64\x30\x36\x30\71\66\60\x38\66\64\x38\x30\61\66\x35\x30\x33\60\64\60\62\x30\63\60\x35\x30\60\60\x34\64\x30");
        }
        FQz:
        lAT:
        $sl .= $AX;
        $Xo = strlen($sl);
        if (!($UM < $Xo + 11)) {
            goto jlU;
        }
        user_error("\x49\x6e\x74\145\x6e\x64\145\x64\x20\x65\156\x63\157\144\x65\144\40\155\x65\163\x73\141\x67\x65\40\154\145\x6e\x67\x74\x68\40\x74\x6f\157\x20\163\x68\x6f\162\x74");
        return false;
        jlU:
        $UA = str_repeat(chr(0xff), $UM - $Xo - 3);
        $FX = "\x0\x1{$UA}\0{$sl}";
        return $FX;
    }
    function _rsassa_pkcs1_v1_5_sign($X3)
    {
        $FX = $this->_emsa_pkcs1_v1_5_encode($X3, $this->k);
        if (!($FX === false)) {
            goto v1e;
        }
        user_error("\x52\x53\101\40\x6d\x6f\x64\x75\154\x75\x73\40\x74\157\x6f\40\163\x68\x6f\x72\x74");
        return false;
        v1e:
        $X3 = $this->_os2ip($FX);
        $Z7 = $this->_rsasp1($X3);
        $Z7 = $this->_i2osp($Z7, $this->k);
        return $Z7;
    }
    function _rsassa_pkcs1_v1_5_verify($X3, $Z7)
    {
        if (!(strlen($Z7) != $this->k)) {
            goto FPy;
        }
        user_error("\x49\156\166\141\x6c\x69\144\x20\163\x69\x67\156\x61\164\x75\x72\145");
        return false;
        FPy:
        $Z7 = $this->_os2ip($Z7);
        $Vl = $this->_rsavp1($Z7);
        if (!($Vl === false)) {
            goto vBr;
        }
        user_error("\x49\x6e\x76\141\x6c\x69\x64\40\x73\151\147\x6e\141\164\165\x72\145");
        return false;
        vBr:
        $FX = $this->_i2osp($Vl, $this->k);
        if (!($FX === false)) {
            goto OHu;
        }
        user_error("\x49\x6e\x76\141\154\151\144\x20\163\x69\147\x6e\141\x74\x75\x72\145");
        return false;
        OHu:
        $AH = $this->_emsa_pkcs1_v1_5_encode($X3, $this->k);
        if (!($AH === false)) {
            goto kcL;
        }
        user_error("\x52\x53\x41\x20\x6d\157\144\165\154\165\x73\40\164\157\157\x20\x73\x68\x6f\x72\x74");
        return false;
        kcL:
        return $this->_equals($FX, $AH);
    }
    function setEncryptionMode($pU)
    {
        $this->encryptionMode = $pU;
    }
    function setSignatureMode($pU)
    {
        $this->signatureMode = $pU;
    }
    function setComment($d8)
    {
        $this->comment = $d8;
    }
    function getComment()
    {
        return $this->comment;
    }
    function encrypt($Lq)
    {
        switch ($this->encryptionMode) {
            case CRYPT_RSA_ENCRYPTION_NONE:
                $Lq = str_split($Lq, $this->k);
                $uY = '';
                foreach ($Lq as $X3) {
                    $uY .= $this->_raw_encrypt($X3);
                    XH7:
                }
                MB_:
                return $uY;
            case CRYPT_RSA_ENCRYPTION_PKCS1:
                $F_ = $this->k - 11;
                if (!($F_ <= 0)) {
                    goto b6S;
                }
                return false;
                b6S:
                $Lq = str_split($Lq, $F_);
                $uY = '';
                foreach ($Lq as $X3) {
                    $uY .= $this->_rsaes_pkcs1_v1_5_encrypt($X3);
                    ExT:
                }
                V7B:
                return $uY;
            default:
                $F_ = $this->k - 2 * $this->hLen - 2;
                if (!($F_ <= 0)) {
                    goto dyU;
                }
                return false;
                dyU:
                $Lq = str_split($Lq, $F_);
                $uY = '';
                foreach ($Lq as $X3) {
                    $uY .= $this->_rsaes_oaep_encrypt($X3);
                    LHy:
                }
                caM:
                return $uY;
        }
        JAt:
        iz0:
    }
    function decrypt($uY)
    {
        if (!($this->k <= 0)) {
            goto Ovf;
        }
        return false;
        Ovf:
        $uY = str_split($uY, $this->k);
        $uY[count($uY) - 1] = str_pad($uY[count($uY) - 1], $this->k, chr(0), STR_PAD_LEFT);
        $Lq = '';
        switch ($this->encryptionMode) {
            case CRYPT_RSA_ENCRYPTION_NONE:
                $rb = "\x5f\162\x61\x77\137\x65\x6e\x63\x72\171\x70\164";
                goto zBP;
            case CRYPT_RSA_ENCRYPTION_PKCS1:
                $rb = "\x5f\162\x73\141\x65\x73\137\160\153\x63\x73\x31\137\x76\61\x5f\65\x5f\x64\x65\143\162\171\160\x74";
                goto zBP;
            default:
                $rb = "\137\162\x73\141\145\x73\137\x6f\x61\145\160\137\144\x65\x63\x72\x79\x70\164";
        }
        l0c:
        zBP:
        foreach ($uY as $eo) {
            $zn = $this->{$rb}($eo);
            if (!($zn === false)) {
                goto Gzx;
            }
            return false;
            Gzx:
            $Lq .= $zn;
            B2y:
        }
        JdC:
        return $Lq;
    }
    function sign($fU)
    {
        if (!(empty($this->modulus) || empty($this->exponent))) {
            goto Uq_;
        }
        return false;
        Uq_:
        switch ($this->signatureMode) {
            case CRYPT_RSA_SIGNATURE_PKCS1:
                return $this->_rsassa_pkcs1_v1_5_sign($fU);
            default:
                return $this->_rsassa_pss_sign($fU);
        }
        svl:
        S2S:
    }
    function verify($fU, $Ou)
    {
        if (!(empty($this->modulus) || empty($this->exponent))) {
            goto hCa;
        }
        return false;
        hCa:
        switch ($this->signatureMode) {
            case CRYPT_RSA_SIGNATURE_PKCS1:
                return $this->_rsassa_pkcs1_v1_5_verify($fU, $Ou);
            default:
                return $this->_rsassa_pss_verify($fU, $Ou);
        }
        LRj:
        Sla:
    }
    function _extractBER($PA)
    {
        $zn = preg_replace("\x23\x2e\x2a\x3f\136\55\x2b\x5b\x5e\x2d\135\53\x2d\x2b\x5b\x5c\x72\x5c\x6e\40\135\x2a\44\43\155\163", '', $PA, 1);
        $zn = preg_replace("\x23\x2d\x2b\x5b\x5e\x2d\135\x2b\x2d\x2b\43", '', $zn);
        $zn = str_replace(array("\15", "\xa", "\40"), '', $zn);
        $zn = preg_match("\x23\x5e\133\x61\x2d\x7a\x41\55\132\134\144\x2f\53\135\52\x3d\x7b\x30\x2c\62\175\x24\x23", $zn) ? base64_decode($zn) : false;
        return $zn != false ? $zn : $PA;
    }
}
