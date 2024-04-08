<?php


namespace MoOauthClient\GrantTypes;

if (!function_exists("\x63\x72\x79\160\x74\x5f\162\x61\156\144\x6f\x6d\137\163\164\162\151\x6e\147")) {
    define("\x43\x52\x59\x50\124\137\x52\x41\x4e\x44\117\x4d\137\x49\123\x5f\127\111\116\104\117\127\123", strtoupper(substr(PHP_OS, 0, 3)) === "\x57\x49\116");
    function crypt_random_string($F_)
    {
        if ($F_) {
            goto X2u;
        }
        return '';
        X2u:
        if (CRYPT_RANDOM_IS_WINDOWS) {
            goto fhN;
        }
        if (!(extension_loaded("\x6f\160\x65\x6e\163\x73\154") && version_compare(PHP_VERSION, "\65\56\63\x2e\x30", "\76\x3d"))) {
            goto Qkr;
        }
        return openssl_random_pseudo_bytes($F_);
        Qkr:
        static $R6 = true;
        if (!($R6 === true)) {
            goto e_i;
        }
        $R6 = @fopen("\57\144\x65\x76\x2f\165\162\x61\156\x64\157\x6d", "\x72\x62");
        e_i:
        if (!($R6 !== true && $R6 !== false)) {
            goto SU7;
        }
        return fread($R6, $F_);
        SU7:
        if (!extension_loaded("\x6d\143\x72\x79\160\x74")) {
            goto gcp;
        }
        return @mcrypt_create_iv($F_, MCRYPT_DEV_URANDOM);
        gcp:
        goto gk7;
        fhN:
        if (!(extension_loaded("\x6d\143\x72\171\x70\164") && version_compare(PHP_VERSION, "\x35\56\63\56\60", "\76\x3d"))) {
            goto fY6;
        }
        return @mcrypt_create_iv($F_);
        fY6:
        if (!(extension_loaded("\157\160\x65\156\x73\163\x6c") && version_compare(PHP_VERSION, "\x35\56\x33\x2e\x34", "\x3e\x3d"))) {
            goto fKE;
        }
        return openssl_random_pseudo_bytes($F_);
        fKE:
        gk7:
        static $R3 = false, $zs;
        if (!($R3 === false)) {
            goto e2T;
        }
        $H0 = session_id();
        $Yf = ini_get("\x73\x65\163\x73\151\157\156\x2e\165\x73\x65\137\x63\157\157\153\x69\145\163");
        $o7 = session_cache_limiter();
        $hw = isset($_SESSION) ? $_SESSION : false;
        if (!($H0 != '')) {
            goto EZw;
        }
        session_write_close();
        EZw:
        session_id(1);
        ini_set("\x73\145\163\163\151\157\x6e\x2e\x75\x73\x65\137\x63\157\157\x6b\151\145\163", 0);
        session_cache_limiter('');
        session_start(["\162\x65\141\144\x5f\141\156\144\137\x63\154\157\163\145" => true]);
        $zs = $XR = $_SESSION["\x73\x65\x65\144"] = pack("\110\52", sha1((isset($_SERVER) ? phpseclib_safe_serialize($_SERVER) : '') . (isset($_POST) ? phpseclib_safe_serialize($_POST) : '') . (isset($_GET) ? phpseclib_safe_serialize($_GET) : '') . (isset($_COOKIE) ? phpseclib_safe_serialize($_COOKIE) : '') . phpseclib_safe_serialize($GLOBALS) . phpseclib_safe_serialize($_SESSION) . phpseclib_safe_serialize($hw)));
        if (isset($_SESSION["\x63\x6f\165\x6e\x74"])) {
            goto mRZ;
        }
        $_SESSION["\x63\157\x75\156\x74"] = 0;
        mRZ:
        $_SESSION["\143\x6f\165\156\x74"]++;
        session_write_close();
        if ($H0 != '') {
            goto YmO;
        }
        if ($hw !== false) {
            goto UhS;
        }
        unset($_SESSION);
        goto gtD;
        UhS:
        $_SESSION = $hw;
        unset($hw);
        gtD:
        goto qYu;
        YmO:
        session_id($H0);
        session_start(["\162\x65\141\x64\137\x61\x6e\x64\137\143\x6c\157\x73\x65" => true]);
        ini_set("\163\145\163\x73\151\157\x6e\x2e\x75\163\145\x5f\143\157\157\x6b\x69\145\163", $Yf);
        session_cache_limiter($o7);
        qYu:
        $Mr = pack("\110\x2a", sha1($XR . "\x41"));
        $X0 = pack("\x48\x2a", sha1($XR . "\103"));
        switch (true) {
            case phpseclib_resolve_include_path("\103\162\171\160\x74\57\101\x45\x53\x2e\160\150\x70"):
                if (class_exists("\103\162\171\x70\x74\137\x41\105\123")) {
                    goto OoR;
                }
                include_once "\101\x45\x53\x2e\x70\x68\160";
                OoR:
                $R3 = new Crypt_AES(CRYPT_AES_MODE_CTR);
                goto hP0;
            case phpseclib_resolve_include_path("\x43\162\x79\160\x74\x2f\x54\167\x6f\146\x69\x73\150\x2e\160\x68\160"):
                if (class_exists("\x43\x72\x79\x70\164\137\x54\167\x6f\x66\x69\163\150")) {
                    goto jHe;
                }
                include_once "\124\167\x6f\146\151\x73\150\56\x70\x68\160";
                jHe:
                $R3 = new Crypt_Twofish(CRYPT_TWOFISH_MODE_CTR);
                goto hP0;
            case phpseclib_resolve_include_path("\x43\162\171\x70\x74\x2f\102\x6c\157\167\146\x69\163\150\x2e\160\x68\160"):
                if (class_exists("\x43\x72\x79\160\164\x5f\x42\x6c\x6f\x77\146\x69\163\x68")) {
                    goto K3A;
                }
                include_once "\102\154\157\x77\x66\151\163\x68\x2e\x70\x68\160";
                K3A:
                $R3 = new Crypt_Blowfish(CRYPT_BLOWFISH_MODE_CTR);
                goto hP0;
            case phpseclib_resolve_include_path("\x43\x72\x79\x70\x74\x2f\x54\x72\x69\x70\x6c\145\104\105\x53\x2e\160\150\x70"):
                if (class_exists("\x43\162\171\x70\x74\137\x54\162\x69\160\x6c\145\x44\x45\123")) {
                    goto egn;
                }
                include_once "\124\x72\151\x70\x6c\145\104\x45\x53\56\160\x68\x70";
                egn:
                $R3 = new Crypt_TripleDES(CRYPT_DES_MODE_CTR);
                goto hP0;
            case phpseclib_resolve_include_path("\x43\x72\171\x70\x74\x2f\x44\x45\123\56\160\150\x70"):
                if (class_exists("\103\162\x79\160\x74\x5f\104\105\x53")) {
                    goto gMY;
                }
                include_once "\104\105\123\56\x70\x68\160";
                gMY:
                $R3 = new Crypt_DES(CRYPT_DES_MODE_CTR);
                goto hP0;
            case phpseclib_resolve_include_path("\103\x72\171\160\x74\x2f\x52\x43\64\56\x70\x68\160"):
                if (class_exists("\103\162\171\160\x74\137\x52\103\x34")) {
                    goto T1V;
                }
                include_once "\122\x43\64\56\160\x68\x70";
                T1V:
                $R3 = new Crypt_RC4();
                goto hP0;
            default:
                user_error("\143\162\x79\x70\x74\137\x72\141\156\x64\x6f\x6d\137\163\164\162\151\156\x67\x20\x72\x65\x71\x75\151\162\x65\163\x20\x61\x74\x20\x6c\x65\x61\163\164\40\157\156\x65\x20\163\171\x6d\155\x65\x74\x72\151\x63\x20\x63\151\160\x68\x65\162\x20\x62\145\40\x6c\x6f\x61\144\145\x64");
                return false;
        }
        Zej:
        hP0:
        $R3->setKey($Mr);
        $R3->setIV($X0);
        $R3->enableContinuousBuffer();
        e2T:
        $DE = '';
        qFN:
        if (!(strlen($DE) < $F_)) {
            goto rni;
        }
        $zY = $R3->encrypt(microtime());
        $lO = $R3->encrypt($zY ^ $zs);
        $zs = $R3->encrypt($lO ^ $zY);
        $DE .= $lO;
        goto qFN;
        rni:
        return substr($DE, 0, $F_);
    }
}
if (!function_exists("\160\x68\x70\x73\x65\143\x6c\x69\142\137\x73\141\146\x65\x5f\x73\x65\162\151\x61\154\x69\x7a\145")) {
    function phpseclib_safe_serialize(&$Ge)
    {
        if (!is_object($Ge)) {
            goto wR9;
        }
        return '';
        wR9:
        if (is_array($Ge)) {
            goto efi;
        }
        return serialize($Ge);
        efi:
        if (!isset($Ge["\x5f\x5f\x70\x68\160\163\x65\x63\x6c\x69\142\x5f\x6d\141\162\153\x65\x72"])) {
            goto BiD;
        }
        return '';
        BiD:
        $xG = array();
        $Ge["\x5f\137\160\x68\160\x73\x65\143\x6c\151\x62\137\x6d\x61\162\153\145\x72"] = true;
        foreach (array_keys($Ge) as $Mr) {
            if (!($Mr !== "\x5f\137\x70\150\160\x73\x65\143\154\151\142\137\155\x61\162\153\145\162")) {
                goto MP6;
            }
            $xG[$Mr] = phpseclib_safe_serialize($Ge[$Mr]);
            MP6:
            K9n:
        }
        AAh:
        unset($Ge["\x5f\x5f\x70\x68\x70\x73\x65\x63\x6c\151\x62\137\x6d\141\162\153\145\x72"]);
        return serialize($xG);
    }
}
if (!function_exists("\x70\150\x70\x73\x65\x63\x6c\151\142\x5f\162\x65\163\x6f\154\x76\145\x5f\x69\156\x63\154\x75\144\145\137\160\x61\x74\x68")) {
    function phpseclib_resolve_include_path($aO)
    {
        if (!function_exists("\x73\x74\162\145\x61\155\x5f\162\145\x73\x6f\154\166\x65\137\151\156\x63\154\165\x64\145\x5f\160\x61\164\x68")) {
            goto LY5;
        }
        return stream_resolve_include_path($aO);
        LY5:
        if (!file_exists($aO)) {
            goto IoI;
        }
        return realpath($aO);
        IoI:
        $ni = PATH_SEPARATOR == "\72" ? preg_split("\43\50\77\74\41\x70\x68\141\162\51\72\x23", get_include_path()) : explode(PATH_SEPARATOR, get_include_path());
        foreach ($ni as $Lh) {
            $Vn = substr($Lh, -1) == DIRECTORY_SEPARATOR ? '' : DIRECTORY_SEPARATOR;
            $aS = $Lh . $Vn . $aO;
            if (!file_exists($aS)) {
                goto wBB;
            }
            return realpath($aS);
            wBB:
            vDG:
        }
        wm4:
        return false;
    }
}
