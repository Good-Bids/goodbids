<?php


namespace MoOauthClient\GrantTypes;

use MoOauthClient\GrantTypes\JWSVerify;
use MoOauthClient\GrantTypes\Crypt_RSA;
use MoOauthClient\GrantTypes\Math_BigInteger;
class JWTUtils
{
    const HEADER = "\110\x45\x41\x44\x45\x52";
    const PAYLOAD = "\x50\101\131\x4c\117\101\x44";
    const SIGN = "\123\111\107\116";
    private $jwt;
    private $decoded_jwt;
    public function __construct($Ju)
    {
        $Ju = \explode("\56", $Ju);
        if (!(3 > count($Ju))) {
            goto ZE;
        }
        return new \WP_Error("\x69\x6e\166\x61\154\x69\x64\x5f\x6a\x77\164", __("\x4a\127\124\x20\122\x65\x63\x65\x69\166\x65\x64\40\151\163\40\x6e\x6f\x74\40\141\x20\166\141\154\x69\x64\x20\112\x57\x54"));
        ZE:
        $this->jwt = $Ju;
        $oh = $this->get_jwt_claim('', self::HEADER);
        $fE = $this->get_jwt_claim('', self::PAYLOAD);
        $this->decoded_jwt = array("\x68\x65\x61\144\145\162" => $oh, "\x70\x61\171\154\x6f\x61\x64" => $fE);
    }
    private function get_jwt_claim($Gg = '', $xt = '')
    {
        global $Uj;
        $Xy = '';
        switch ($xt) {
            case self::HEADER:
                $Xy = $this->jwt[0];
                goto KP;
            case self::PAYLOAD:
                $Xy = $this->jwt[1];
                goto KP;
            case self::SIGN:
                return $this->jwt[2];
            default:
                $Uj->handle_error("\x43\141\x6e\x6e\x6f\x74\x20\106\x69\156\x64\40" . $xt . "\40\151\156\40\x74\x68\x65\x20\112\x57\124");
                wp_die(wp_kses("\103\x61\156\156\157\x74\40\x46\151\x6e\144\x20" . $xt . "\x20\x69\x6e\x20\x74\x68\x65\40\112\x57\x54", \mo_oauth_get_valid_html()));
        }
        SP:
        KP:
        $Xy = json_decode($Uj->base64url_decode($Xy), true);
        if (!(!$Xy || empty($Xy))) {
            goto u4;
        }
        return null;
        u4:
        return empty($Gg) ? $Xy : (isset($Xy[$Gg]) ? $Xy[$Gg] : null);
    }
    public function check_algo($QX = '')
    {
        global $Uj;
        $vE = $this->get_jwt_claim("\x61\154\x67", self::HEADER);
        $vE = explode("\123", $vE);
        if (isset($vE[0])) {
            goto nE;
        }
        $Bl = "\111\156\x76\x61\x6c\x69\x64\40\x52\145\163\x70\x6f\x6e\x73\x65\x20\x52\145\143\145\x69\166\x65\144\40\x66\x72\157\155\x20\x4f\101\165\x74\150\57\117\x70\x65\156\111\104\40\120\x72\x6f\166\x69\144\145\x72\56";
        $Uj->handle_error($Bl);
        wp_die(wp_kses($Bl, \mo_oauth_get_valid_html()));
        nE:
        switch ($vE[0]) {
            case "\x48":
                return "\110\x53\x41" === $QX;
            case "\x52":
                return "\x52\123\101" === $QX;
            default:
                return false;
        }
        ub:
        Ym:
    }
    public function verify($cH = '')
    {
        global $Uj;
        if (!empty($cH)) {
            goto in;
        }
        return false;
        in:
        $At = $this->get_jwt_claim("\145\170\x70", self::PAYLOAD);
        if (!(is_null($At) || time() > $At)) {
            goto EY;
        }
        $LG = "\112\127\x54\x20\150\141\163\40\x62\x65\145\x6e\x20\x65\x78\160\x69\x72\145\x64\x2e\40\x50\154\145\141\163\145\40\164\162\x79\x20\x4c\157\x67\147\151\x6e\147\x20\151\156\40\141\147\x61\151\156\x2e";
        $Uj->handle_error($LG);
        wp_die(wp_kses($LG, \mo_oauth_get_valid_html()));
        EY:
        $Nz = $this->get_jwt_claim("\x6e\x62\146", self::PAYLOAD);
        if (!(!is_null($Nz) || time() < $Nz)) {
            goto Vq;
        }
        $Em = "\111\164\x20\x69\163\x20\164\x6f\157\40\x65\141\162\x6c\x79\40\x74\x6f\40\x75\x73\145\x20\x74\150\151\163\x20\112\127\x54\56\40\120\x6c\145\141\163\x65\40\164\162\171\40\x4c\x6f\147\x67\151\x6e\147\40\x69\x6e\40\141\147\x61\x69\x6e\56";
        $Uj->handle_error($Em);
        wp_die(wp_kses($Em, \mo_oauth_get_valid_html()));
        Vq:
        $RL = new JWSVerify($this->get_jwt_claim("\x61\x6c\x67", self::HEADER));
        $Lr = $this->get_header() . "\x2e" . $this->get_payload();
        return $RL->verify(\utf8_decode($Lr), $cH, base64_decode(strtr($this->get_jwt_claim(false, self::SIGN), "\x2d\137", "\x2b\x2f")));
    }
    public function verify_from_jwks($zV = '', $vE = "\122\x53\62\65\x36")
    {
        global $Uj;
        $cw = wp_remote_get($zV);
        if (!is_wp_error($cw)) {
            goto jr;
        }
        return false;
        jr:
        $cw = json_decode($cw["\142\157\144\171"], true);
        $C2 = false;
        if (!(json_last_error() !== JSON_ERROR_NONE)) {
            goto Vh;
        }
        return $C2;
        Vh:
        if (isset($cw["\153\x65\x79\163"])) {
            goto c2;
        }
        return $C2;
        c2:
        foreach ($cw["\x6b\x65\171\163"] as $Mr => $t_) {
            if (!(!isset($t_["\x6b\164\x79"]) || "\122\x53\101" !== $t_["\x6b\x74\171"] || !isset($t_["\145"]) || !isset($t_["\156"]))) {
                goto vZ;
            }
            goto kW;
            vZ:
            $C2 = $C2 || $this->verify($this->jwks_to_pem(["\x6e" => new Math_BigInteger($Uj->base64url_decode($t_["\x6e"]), 256), "\x65" => new Math_BigInteger($Uj->base64url_decode($t_["\x65"]), 256)]));
            if (!(true === $C2)) {
                goto CF;
            }
            goto t2;
            CF:
            kW:
        }
        t2:
        return $C2;
    }
    private function jwks_to_pem($T2 = array())
    {
        $se = new Crypt_RSA();
        $se->loadKey($T2);
        return $se->getPublicKey();
    }
    public function get_decoded_header()
    {
        return $this->decoded_jwt["\x68\145\x61\144\145\x72"];
    }
    public function get_decoded_payload()
    {
        if (!isset($this->decoded_jwt["\x70\141\171\154\157\x61\x64"])) {
            goto CH;
        }
        return $this->decoded_jwt["\160\141\171\x6c\157\141\x64"];
        CH:
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
