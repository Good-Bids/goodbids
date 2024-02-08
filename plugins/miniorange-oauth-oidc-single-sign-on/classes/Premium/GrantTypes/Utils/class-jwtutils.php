<?php


namespace MoOauthClient\GrantTypes;

use MoOauthClient\GrantTypes\JWSVerify;
use MoOauthClient\GrantTypes\Crypt_RSA;
use MoOauthClient\GrantTypes\Math_BigInteger;
class JWTUtils
{
    const HEADER = "\110\105\101\x44\x45\122";
    const PAYLOAD = "\x50\x41\131\114\117\101\x44";
    const SIGN = "\x53\x49\107\116";
    private $jwt;
    private $decoded_jwt;
    public function __construct($gK)
    {
        $gK = \explode("\56", $gK);
        if (!(3 > count($gK))) {
            goto Df;
        }
        return new \WP_Error("\x69\156\x76\141\154\151\144\x5f\152\x77\164", __("\112\127\124\40\122\145\x63\145\151\x76\x65\x64\40\151\x73\40\x6e\157\x74\40\x61\x20\166\x61\x6c\151\144\40\112\127\124"));
        Df:
        $this->jwt = $gK;
        $jH = $this->get_jwt_claim('', self::HEADER);
        $Er = $this->get_jwt_claim('', self::PAYLOAD);
        $this->decoded_jwt = array("\x68\x65\141\x64\145\x72" => $jH, "\160\141\171\154\x6f\141\x64" => $Er);
    }
    private function get_jwt_claim($Oj = '', $l5 = '')
    {
        global $Yh;
        $IK = '';
        switch ($l5) {
            case self::HEADER:
                $IK = $this->jwt[0];
                goto ug;
            case self::PAYLOAD:
                $IK = $this->jwt[1];
                goto ug;
            case self::SIGN:
                return $this->jwt[2];
            default:
                $Yh->handle_error("\103\x61\156\x6e\x6f\164\40\106\x69\x6e\x64\x20" . $l5 . "\x20\151\x6e\40\164\150\x65\x20\112\127\x54");
                $N5 = "\103\141\156\156\157\x74\x20\x46\151\x6e\x64\40" . $l5 . "\40\x69\156\40\x74\x68\x65\40\x4a\x57\124";
                wp_die(wp_kses($N5, \mo_oauth_get_valid_html()));
        }
        xJ:
        ug:
        $IK = json_decode($Yh->base64url_decode($IK), true);
        if (!(!$IK || empty($IK))) {
            goto nb;
        }
        return null;
        nb:
        return empty($Oj) ? $IK : (isset($IK[$Oj]) ? $IK[$Oj] : null);
    }
    public function check_algo($xt = '')
    {
        global $Yh;
        $Yb = $this->get_jwt_claim("\141\154\147", self::HEADER);
        $Yb = explode("\x53", $Yb);
        if (isset($Yb[0])) {
            goto cI;
        }
        $N5 = "\111\156\x76\141\154\151\144\40\x52\145\163\x70\x6f\156\163\145\x20\x52\145\x63\145\151\x76\145\144\x20\x66\162\x6f\x6d\x20\117\x41\165\x74\x68\57\117\x70\x65\156\x49\104\x20\120\x72\157\x76\151\144\x65\x72\56";
        $Yh->handle_error($N5);
        wp_die(wp_kses($N5, \mo_oauth_get_valid_html()));
        cI:
        switch ($Yb[0]) {
            case "\110":
                return "\110\123\101" === $xt;
            case "\122":
                return "\122\123\101" === $xt;
            default:
                return false;
        }
        dP:
        oS:
    }
    public function verify($Ju = '')
    {
        global $Yh;
        if (!empty($Ju)) {
            goto wK;
        }
        return false;
        wK:
        $F4 = $this->get_jwt_claim("\x65\x78\160", self::PAYLOAD);
        if (!(is_null($F4) || time() > $F4)) {
            goto rv;
        }
        $XO = "\x4a\x57\x54\x20\150\141\x73\40\x62\x65\x65\156\40\x65\170\160\x69\x72\145\144\56\40\120\x6c\145\x61\163\x65\40\x74\162\171\x20\x4c\157\147\x67\x69\x6e\147\40\151\x6e\x20\x61\147\141\x69\x6e\x2e";
        $Yh->handle_error($XO);
        wp_die(wp_kses($XO, \mo_oauth_get_valid_html()));
        rv:
        $Nz = $this->get_jwt_claim("\x6e\142\x66", self::PAYLOAD);
        if (!(!is_null($Nz) || time() < $Nz)) {
            goto fq;
        }
        $Gs = "\111\164\x20\x69\163\x20\x74\157\x6f\40\x65\x61\162\x6c\171\x20\164\x6f\x20\x75\x73\145\40\x74\x68\x69\163\x20\112\127\x54\x2e\x20\120\x6c\145\141\163\x65\x20\164\x72\x79\40\x4c\x6f\147\x67\151\156\x67\x20\151\156\x20\x61\x67\x61\x69\x6e\56";
        $Yh->handle_error($Gs);
        wp_die(wp_kses($Gs, \mo_oauth_get_valid_html()));
        fq:
        $Q_ = new JWSVerify($this->get_jwt_claim("\141\154\147", self::HEADER));
        $UP = $this->get_header() . "\x2e" . $this->get_payload();
        return $Q_->verify(\utf8_decode($UP), $Ju, base64_decode(strtr($this->get_jwt_claim(false, self::SIGN), "\55\137", "\53\57")));
    }
    public function verify_from_jwks($GY = '', $Yb = "\x52\x53\x32\65\66")
    {
        global $Yh;
        $ZK = wp_remote_get($GY);
        if (!is_wp_error($ZK)) {
            goto zk;
        }
        return false;
        zk:
        $ZK = json_decode($ZK["\x62\157\x64\x79"], true);
        $WJ = false;
        if (!(json_last_error() !== JSON_ERROR_NONE)) {
            goto f3;
        }
        return $WJ;
        f3:
        if (isset($ZK["\x6b\145\x79\163"])) {
            goto Ah;
        }
        return $WJ;
        Ah:
        foreach ($ZK["\153\145\171\x73"] as $cW => $LQ) {
            if (!(!isset($LQ["\153\x74\x79"]) || "\122\x53\101" !== $LQ["\153\x74\x79"] || !isset($LQ["\x65"]) || !isset($LQ["\x6e"]))) {
                goto Hd;
            }
            goto ts;
            Hd:
            $WJ = $WJ || $this->verify($this->jwks_to_pem(["\156" => new Math_BigInteger($Yh->base64url_decode($LQ["\156"]), 256), "\x65" => new Math_BigInteger($Yh->base64url_decode($LQ["\x65"]), 256)]));
            if (!(true === $WJ)) {
                goto Iw;
            }
            goto OC;
            Iw:
            ts:
        }
        OC:
        return $WJ;
    }
    private function jwks_to_pem($VO = array())
    {
        $Hf = new Crypt_RSA();
        $Hf->loadKey($VO);
        return $Hf->getPublicKey();
    }
    public function get_decoded_header()
    {
        return $this->decoded_jwt["\150\145\x61\x64\145\x72"];
    }
    public function get_decoded_payload()
    {
        if (!isset($this->decoded_jwt["\160\141\x79\154\157\141\144"])) {
            goto uy;
        }
        return $this->decoded_jwt["\x70\141\x79\154\157\x61\144"];
        uy:
    }
    public function get_header()
    {
        return $this->jwt[0];
    }
    public function get_payload()
    {
        return $this->jwt[1];
    }
}
