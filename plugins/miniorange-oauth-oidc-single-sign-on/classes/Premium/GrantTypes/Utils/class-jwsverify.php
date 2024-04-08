<?php


namespace MoOauthClient\GrantTypes;

class JWSVerify
{
    public $algo;
    public function __construct($gT = '')
    {
        if (!empty($gT)) {
            goto d7;
        }
        return;
        d7:
        $gT = explode("\123", $gT);
        if (!(!is_array($gT) || 2 !== count($gT))) {
            goto ON;
        }
        return WP_Error("\151\156\166\x61\x6c\151\144\x5f\163\151\147\156\141\164\x75\x72\145", __("\x54\x68\x65\x20\123\151\x67\156\141\x74\165\162\145\40\163\145\x65\155\163\40\164\157\40\142\x65\x20\x69\156\166\141\154\151\144\40\x6f\x72\x20\x75\x6e\163\165\160\160\x6f\x72\164\145\x64\x2e"));
        ON:
        if ("\110" === $gT[0]) {
            goto g9;
        }
        if ("\x52" === $gT[0]) {
            goto mm;
        }
        return WP_Error("\151\x6e\x76\x61\x6c\151\144\x5f\163\x69\147\156\141\164\165\x72\145", __("\124\x68\x65\40\163\151\147\x6e\x61\x74\x75\x72\x65\40\x61\x6c\x67\x6f\x72\x69\164\x68\x6d\x20\x73\x65\145\155\x73\40\164\157\x20\x62\145\x20\165\x6e\x73\165\160\160\157\x72\164\x65\x64\40\157\162\x20\151\x6e\x76\x61\154\x69\x64\56"));
        goto tj;
        g9:
        $this->algo["\141\154\x67"] = "\110\123\101";
        goto tj;
        mm:
        $this->algo["\x61\x6c\147"] = "\122\x53\x41";
        tj:
        $this->algo["\163\150\x61"] = $gT[1];
    }
    private function validate_hmac($Lr = '', $cH = '', $UQ = '')
    {
        if (!(empty($Lr) || empty($UQ))) {
            goto XA;
        }
        return false;
        XA:
        $QE = $this->algo["\x73\150\141"];
        $QE = "\163\x68\x61" . $QE;
        $yh = \hash_hmac($QE, $Lr, $cH, true);
        return hash_equals($yh, $UQ);
    }
    private function validate_rsa($Lr = '', $rs = '', $UQ = '')
    {
        if (!(empty($Lr) || empty($UQ))) {
            goto TY;
        }
        return false;
        TY:
        $QE = $this->algo["\x73\x68\141"];
        $xu = '';
        $UR = explode("\55\55\55\55\55", $rs);
        if (preg_match("\x2f\x5c\x72\x5c\x6e\174\x5c\162\174\134\156\x2f", $UR[2])) {
            goto xM;
        }
        $l3 = "\x2d\55\55\55\x2d" . $UR[1] . "\x2d\x2d\55\55\55\12";
        $w7 = 0;
        lU:
        if (!($o5 = substr($UR[2], $w7, 64))) {
            goto tI;
        }
        $l3 .= $o5 . "\12";
        $w7 += 64;
        goto lU;
        tI:
        $l3 .= "\x2d\55\55\x2d\x2d" . $UR[3] . "\55\x2d\x2d\55\x2d\12";
        $xu = $l3;
        goto PO;
        xM:
        $xu = $rs;
        PO:
        $C2 = false;
        switch ($QE) {
            case "\62\x35\66":
                $C2 = openssl_verify($Lr, $UQ, $xu, OPENSSL_ALGO_SHA256);
                goto Tb;
            case "\x33\70\64":
                $C2 = openssl_verify($Lr, $UQ, $xu, OPENSSL_ALGO_SHA384);
                goto Tb;
            case "\x35\x31\62":
                $C2 = openssl_verify($Lr, $UQ, $xu, OPENSSL_ALGO_SHA512);
                goto Tb;
            default:
                $C2 = false;
                goto Tb;
        }
        jG:
        Tb:
        return $C2;
    }
    public function verify($Lr = '', $cH = '', $UQ = '')
    {
        if (!(empty($Lr) || empty($UQ))) {
            goto Jf;
        }
        return false;
        Jf:
        $gT = $this->algo["\x61\154\x67"];
        switch ($gT) {
            case "\x48\123\x41":
                return $this->validate_hmac($Lr, $cH, $UQ);
            case "\122\123\101":
                return @$this->validate_rsa($Lr, $cH, $UQ);
            default:
                return false;
        }
        E_:
        Zz:
    }
}
