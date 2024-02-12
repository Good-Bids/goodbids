<?php


namespace MoOauthClient\GrantTypes;

class JWSVerify
{
    public $algo;
    public function __construct($dr = '')
    {
        if (!empty($dr)) {
            goto zZ;
        }
        return;
        zZ:
        $dr = explode("\x53", $dr);
        if (!(!is_array($dr) || 2 !== count($dr))) {
            goto dx;
        }
        return WP_Error("\151\156\x76\141\154\151\144\x5f\163\151\147\x6e\x61\x74\x75\x72\x65", __("\x54\x68\145\x20\123\x69\x67\156\x61\x74\165\162\x65\40\163\145\x65\x6d\163\40\x74\x6f\40\x62\x65\x20\151\x6e\x76\x61\x6c\151\144\40\x6f\162\x20\x75\x6e\x73\165\160\x70\x6f\162\164\x65\x64\56"));
        dx:
        if ("\x48" === $dr[0]) {
            goto fe;
        }
        if ("\x52" === $dr[0]) {
            goto tK;
        }
        return WP_Error("\151\x6e\166\x61\154\x69\144\137\x73\151\147\x6e\141\x74\165\162\145", __("\x54\150\x65\40\x73\151\147\156\141\x74\165\162\x65\x20\141\154\147\x6f\162\x69\x74\x68\x6d\x20\x73\x65\145\x6d\x73\x20\164\157\40\x62\145\40\165\156\163\165\160\x70\157\162\x74\145\x64\x20\x6f\x72\x20\151\156\166\141\154\x69\x64\56"));
        goto RY;
        fe:
        $this->algo["\x61\154\147"] = "\110\x53\101";
        goto RY;
        tK:
        $this->algo["\x61\x6c\x67"] = "\122\123\101";
        RY:
        $this->algo["\163\x68\141"] = $dr[1];
    }
    private function validate_hmac($UP = '', $Ju = '', $KC = '')
    {
        if (!(empty($UP) || empty($KC))) {
            goto qc;
        }
        return false;
        qc:
        $Mi = $this->algo["\x73\150\x61"];
        $Mi = "\163\x68\x61" . $Mi;
        $aR = \hash_hmac($Mi, $UP, $Ju, true);
        return hash_equals($aR, $KC);
    }
    private function validate_rsa($UP = '', $Fq = '', $KC = '')
    {
        if (!(empty($UP) || empty($KC))) {
            goto jS;
        }
        return false;
        jS:
        $Mi = $this->algo["\x73\x68\141"];
        $VW = '';
        $uO = explode("\55\55\55\55\x2d", $Fq);
        if (preg_match("\x2f\134\x72\134\x6e\174\x5c\162\174\x5c\156\x2f", $uO[2])) {
            goto fz;
        }
        $uy = "\x2d\55\x2d\x2d\x2d" . $uO[1] . "\55\55\55\55\x2d\xa";
        $a7 = 0;
        yP:
        if (!($JY = substr($uO[2], $a7, 64))) {
            goto aR;
        }
        $uy .= $JY . "\xa";
        $a7 += 64;
        goto yP;
        aR:
        $uy .= "\x2d\55\x2d\55\55" . $uO[3] . "\55\55\x2d\55\55\xa";
        $VW = $uy;
        goto Uj;
        fz:
        $VW = $Fq;
        Uj:
        $WJ = false;
        switch ($Mi) {
            case "\62\65\x36":
                $WJ = openssl_verify($UP, $KC, $VW, OPENSSL_ALGO_SHA256);
                goto o6;
            case "\x33\70\x34":
                $WJ = openssl_verify($UP, $KC, $VW, OPENSSL_ALGO_SHA384);
                goto o6;
            case "\65\x31\x32":
                $WJ = openssl_verify($UP, $KC, $VW, OPENSSL_ALGO_SHA512);
                goto o6;
            default:
                $WJ = false;
                goto o6;
        }
        cV:
        o6:
        return $WJ;
    }
    public function verify($UP = '', $Ju = '', $KC = '')
    {
        if (!(empty($UP) || empty($KC))) {
            goto Wx;
        }
        return false;
        Wx:
        $dr = $this->algo["\141\154\147"];
        switch ($dr) {
            case "\110\123\101":
                return $this->validate_hmac($UP, $Ju, $KC);
            case "\x52\123\101":
                return @$this->validate_rsa($UP, $Ju, $KC);
            default:
                return false;
        }
        Lx:
        Ap:
    }
}
