<?php


namespace MoOauthClient;

use MoOauthClient\MO_Custom_OAuth1;
use MoOauthClient\MO_Oauth_Debug;
class MO_Custom_OAuth1
{
    public static function mo_oauth1_auth_request($BW)
    {
        global $Uj;
        $Wh = $Uj->get_app_by_name($BW)->get_app_config();
        $w0 = $Wh["\x63\154\x69\145\x6e\x74\x5f\151\144"];
        $zr = $Wh["\x63\x6c\x69\145\x6e\164\137\163\x65\143\162\145\x74"];
        $wD = $Wh["\x61\x75\164\x68\x6f\x72\151\172\x65\x75\162\154"];
        $bz = $Wh["\162\145\x71\x75\x65\x73\164\x75\x72\x6c"];
        $A_ = $Wh["\x61\x63\143\x65\163\163\164\157\153\x65\x6e\x75\x72\154"];
        $Gh = $Wh["\162\x65\x73\157\165\x72\143\145\x6f\167\x6e\x65\162\144\x65\x74\x61\151\x6c\163\x75\x72\154"];
        $vz = new MO_Custom_OAuth1_Flow($w0, $zr, $bz, $A_, $Gh);
        $mj = $vz->mo_oauth1_get_request_token();
        if (!(strpos($wD, "\77") == false)) {
            goto E0;
        }
        $wD .= "\77";
        E0:
        $ta = $wD . "\157\141\x75\x74\x68\137\x74\157\153\145\156\x3d" . $mj;
        if (!($mj == '' || $mj == NULL)) {
            goto FC;
        }
        MO_Oauth_Debug::mo_oauth_log("\105\162\162\x6f\x72\40\x69\x6e\x20\x52\x65\161\165\145\163\x74\40\x54\157\x6b\145\x6e\40\105\x6e\x64\x70\157\151\x6e\x74");
        wp_die("\105\162\x72\157\162\x20\x69\x6e\x20\x52\145\161\165\x65\163\164\x20\x54\157\x6b\145\x6e\x20\105\x6e\144\x70\157\151\x6e\x74\x3a\40\111\156\x76\x61\154\151\x64\x20\x74\157\x6b\145\x6e\x20\162\x65\x63\145\x69\166\145\x64\56\x20\x43\157\156\x74\141\143\x74\40\x74\x6f\40\171\x6f\165\162\40\141\x64\155\151\155\x69\x73\x74\x72\141\164\157\x72\x20\146\157\162\40\155\157\x72\145\40\151\156\146\x6f\x72\x6d\141\164\151\x6f\156\56");
        FC:
        MO_Oauth_Debug::mo_oauth_log("\x52\145\161\x75\145\x73\x74\x20\x54\157\153\x65\x6e\x20\x72\145\143\145\x69\166\145\144\56");
        MO_Oauth_Debug::mo_oauth_log("\x52\145\x71\x75\145\163\x74\x20\124\x6f\x6b\x65\156\40\x3d\x3e\40" . $mj);
        header("\114\x6f\x63\x61\164\x69\x6f\x6e\x3a" . $ta);
        exit;
    }
    static function mo_oidc1_get_access_token($BW)
    {
        $Xq = explode("\x26", $_SERVER["\x52\x45\x51\125\105\x53\124\137\125\122\111"]);
        $AG = explode("\x3d", $Xq[1]);
        $Kc = explode("\75", $Xq[0]);
        $H5 = get_option("\x6d\x6f\x5f\x6f\141\165\x74\150\137\141\x70\x70\x73\x5f\154\151\x73\164");
        $Cm = $BW;
        $qJ = null;
        foreach ($H5 as $Mr => $Fr) {
            if (!($BW == $Mr)) {
                goto t9;
            }
            $qJ = $Fr;
            goto OF;
            t9:
            aC:
        }
        OF:
        global $Uj;
        $Wh = $Uj->get_app_by_name($BW)->get_app_config();
        $w0 = $Wh["\143\154\x69\145\x6e\164\x5f\151\144"];
        $zr = $Wh["\x63\154\151\145\x6e\x74\137\x73\145\x63\162\x65\x74"];
        $wD = $Wh["\x61\165\164\150\157\162\151\172\x65\x75\x72\154"];
        $bz = $Wh["\x72\x65\161\x75\145\163\164\165\162\154"];
        $A_ = $Wh["\141\143\x63\145\x73\x73\x74\x6f\153\145\x6e\x75\x72\x6c"];
        $Gh = $Wh["\x72\x65\x73\x6f\x75\162\x63\x65\157\x77\x6e\x65\162\x64\x65\164\141\x69\x6c\x73\165\x72\x6c"];
        $pC = new MO_Custom_OAuth1_Flow($w0, $zr, $bz, $A_, $Gh);
        $SG = $pC->mo_oauth1_get_access_token($AG[1], $Kc[1]);
        $qi = explode("\x26", $SG);
        $Y7 = '';
        $oJ = '';
        foreach ($qi as $Mr) {
            $c2 = explode("\x3d", $Mr);
            if ($c2[0] == "\157\141\165\164\x68\137\164\x6f\x6b\145\156") {
                goto mc;
            }
            if (!($c2[0] == "\x6f\141\x75\164\x68\137\x74\x6f\153\x65\156\137\x73\x65\143\162\145\x74")) {
                goto EL;
            }
            $oJ = $c2[1];
            EL:
            goto Ub;
            mc:
            $Y7 = $c2[1];
            Ub:
            TE:
        }
        t_:
        MO_Oauth_Debug::mo_oauth_log("\101\x63\x63\x65\x73\x73\40\x54\157\x6b\x65\156\x20\x72\x65\143\145\x69\166\145\x64\56");
        MO_Oauth_Debug::mo_oauth_log("\x41\x63\x63\x65\x73\163\x20\124\x6f\x6b\145\x6e\40\75\x3e\x20" . $Y7);
        $Tw = new MO_Custom_OAuth1_Flow($w0, $zr, $bz, $A_, $Gh);
        $Q3 = isset($gG[1]) ? $gG[1] : '';
        $Jh = isset($v3[1]) ? $v3[1] : '';
        $B3 = isset($cD[1]) ? $cD[1] : '';
        $kP = $Tw->mo_oauth1_get_profile_signature($Y7, $oJ);
        if (isset($kP)) {
            goto hJ;
        }
        wp_die("\x49\156\x76\x61\154\x69\144\40\x43\x6f\x6e\146\x69\147\165\162\141\x74\151\157\x6e\x73\x2e\40\120\x6c\145\x61\x73\x65\40\x63\157\x6e\x74\141\x63\164\x20\x74\x6f\40\x74\150\145\40\141\144\x6d\x69\155\151\x73\x74\162\x61\x74\x6f\x72\40\x66\157\x72\x20\x6d\157\x72\x65\x20\x69\x6e\146\157\x72\155\141\x74\x69\157\x6e");
        hJ:
        return $kP;
    }
}
class MO_Custom_OAuth1_Flow
{
    var $key = '';
    var $secret = '';
    var $request_token_url = '';
    var $access_token_url = '';
    var $userinfo_url = '';
    function __construct($XG, $zr, $bz, $A_, $Gh)
    {
        $this->key = $XG;
        $this->secret = $zr;
        $this->request_token_url = $bz;
        $this->access_token_url = $A_;
        $this->userinfo_url = $Gh;
    }
    function mo_oauth1_get_request_token()
    {
        $Jt = array("\157\141\165\164\x68\137\166\x65\x72\163\x69\x6f\x6e" => "\x31\56\60", "\157\141\165\x74\x68\137\156\x6f\x6e\x63\145" => time(), "\157\141\x75\x74\x68\x5f\164\x69\x6d\x65\163\x74\x61\155\x70" => time(), "\157\141\x75\164\150\x5f\143\157\x6e\x73\x75\x6d\x65\x72\x5f\x6b\145\171" => $this->key, "\157\x61\165\164\150\137\163\x69\147\156\141\x74\165\162\x65\x5f\x6d\x65\164\150\157\144" => "\x48\x4d\x41\103\x2d\x53\x48\x41\x31");
        if (!(strpos($this->request_token_url, "\77") != false)) {
            goto Rq;
        }
        $zn = explode("\77", $this->request_token_url);
        $this->request_token_url = $zn[0];
        $h2 = explode("\x26", $zn[1]);
        foreach ($h2 as $Ge) {
            $U3 = explode("\x3d", $Ge);
            $Jt[$U3[0]] = $U3[1];
            XL:
        }
        k6:
        Rq:
        $nR = array_keys($Jt);
        $EI = array_values($Jt);
        $Jt = $this->mo_oauth1_url_encode_rfc3986(array_combine($nR, $EI));
        uksort($Jt, "\x73\164\162\x63\x6d\x70");
        foreach ($Jt as $Wu => $zs) {
            $xZ[] = $this->mo_oauth1_url_encode_rfc3986($Wu) . "\75" . $this->mo_oauth1_url_encode_rfc3986($zs);
            xg:
        }
        c_:
        $rW = implode("\x26", $xZ);
        $gJ = $rW;
        $gJ = str_replace("\x3d", "\45\63\x44", $gJ);
        $gJ = str_replace("\46", "\x25\x32\66", $gJ);
        $gJ = "\107\x45\124\46" . $this->mo_oauth1_url_encode_rfc3986($this->request_token_url) . "\x26" . $gJ;
        $cH = $this->mo_oauth1_url_encode_rfc3986($this->secret) . "\46";
        $Jt["\x6f\141\165\164\150\x5f\x73\x69\x67\156\x61\x74\x75\x72\145"] = $this->mo_oauth1_url_encode_rfc3986(base64_encode(hash_hmac("\x73\x68\141\x31", $gJ, $cH, TRUE)));
        uksort($Jt, "\163\x74\162\x63\155\x70");
        foreach ($Jt as $Wu => $zs) {
            $Ek[] = $Wu . "\x3d" . $zs;
            S7:
        }
        CC:
        $xN = implode("\46", $Ek);
        $ht = $this->request_token_url . "\77" . $xN;
        MO_Oauth_Debug::mo_oauth_log("\122\145\161\x75\x65\x73\x74\40\124\157\x6b\x65\x6e\40\125\122\x4c\40\x3d\76\40" . $ht);
        $Yx = $this->mo_oauth1_https($ht);
        MO_Oauth_Debug::mo_oauth_log("\x52\x65\x71\x75\145\x73\x74\40\124\157\153\145\156\40\x45\x6e\144\160\x6f\151\x6e\x74\40\122\145\163\160\157\156\x73\x65\40\75\x3e\40");
        MO_Oauth_Debug::mo_oauth_log($Yx);
        $FL = explode("\46", $Yx);
        $Nm = '';
        foreach ($FL as $Mr) {
            $c2 = explode("\75", $Mr);
            if ($c2[0] == "\x6f\141\x75\164\150\137\164\x6f\x6b\145\156") {
                goto oD;
            }
            if (!($c2[0] == "\157\141\x75\x74\x68\137\x74\x6f\153\x65\156\x5f\x73\x65\x63\162\x65\164")) {
                goto gq;
            }
            setcookie("\155\157\137\164\x73", $c2[1], time() + 30);
            gq:
            goto e0;
            oD:
            $Nm = $c2[1];
            e0:
            PF:
        }
        Aq:
        return $Nm;
    }
    function mo_oauth1_get_access_token($AG, $Kc)
    {
        $Jt = array("\157\x61\x75\164\150\137\166\x65\162\163\151\157\156" => "\61\56\60", "\157\x61\x75\x74\x68\137\x6e\x6f\x6e\x63\145" => time(), "\x6f\x61\x75\x74\150\137\x74\x69\x6d\145\163\164\141\155\x70" => time(), "\x6f\141\x75\x74\150\x5f\143\x6f\x6e\x73\x75\x6d\145\x72\137\x6b\145\171" => $this->key, "\x6f\x61\165\164\150\x5f\x74\157\x6b\145\156" => $Kc, "\157\141\165\x74\150\137\163\151\x67\156\x61\x74\165\162\145\x5f\x6d\x65\x74\150\157\x64" => "\110\115\x41\x43\55\x53\110\x41\61", "\x6f\x61\165\164\150\x5f\166\x65\162\x69\146\x69\x65\162" => $AG);
        $nR = $this->mo_oauth1_url_encode_rfc3986(array_keys($Jt));
        $EI = $this->mo_oauth1_url_encode_rfc3986(array_values($Jt));
        $Jt = array_combine($nR, $EI);
        uksort($Jt, "\163\x74\162\143\x6d\x70");
        foreach ($Jt as $Wu => $zs) {
            $xZ[] = $this->mo_oauth1_url_encode_rfc3986($Wu) . "\x3d" . $this->mo_oauth1_url_encode_rfc3986($zs);
            f5:
        }
        pK:
        $rW = implode("\46", $xZ);
        $gJ = $rW;
        $gJ = str_replace("\x3d", "\x25\x33\x44", $gJ);
        $gJ = str_replace("\46", "\45\62\66", $gJ);
        $gJ = "\x47\x45\x54\x26" . $this->mo_oauth1_url_encode_rfc3986($this->access_token_url) . "\46" . $gJ;
        $Lu = isset($_COOKIE["\x6d\x6f\137\164\x73"]) ? $_COOKIE["\x6d\x6f\137\x74\x73"] : '';
        $cH = $this->mo_oauth1_url_encode_rfc3986($this->secret) . "\x26" . $Lu;
        $Jt["\157\141\x75\164\x68\x5f\163\151\147\x6e\141\x74\x75\162\145"] = $this->mo_oauth1_url_encode_rfc3986(base64_encode(hash_hmac("\x73\x68\141\x31", $gJ, $cH, TRUE)));
        uksort($Jt, "\163\x74\162\143\155\160");
        foreach ($Jt as $Wu => $zs) {
            $Ek[] = $Wu . "\75" . $zs;
            y1:
        }
        Rv:
        $xN = implode("\46", $Ek);
        $ht = $this->access_token_url . "\77" . $xN;
        MO_Oauth_Debug::mo_oauth_log("\x41\143\x63\x65\163\163\x20\124\x6f\153\145\x6e\x20\105\x6e\144\160\x6f\x69\156\x74\40\125\122\x4c\40\x3d\76\x20" . $ht);
        $Yx = $this->mo_oauth1_https($ht);
        MO_Oauth_Debug::mo_oauth_log("\101\x63\x63\x65\x73\x73\40\x54\157\153\x65\x6e\40\x45\156\x64\160\x6f\151\156\164\40\122\145\163\160\x6f\x6e\163\x65\x20\75\x3e\x20" . $Yx);
        return $Yx;
    }
    function mo_oauth1_get_profile_signature($SG, $v3, $cD = '')
    {
        $Jt = array("\157\x61\165\x74\150\137\166\x65\162\163\151\157\x6e" => "\x31\56\x30", "\157\x61\x75\164\150\x5f\x6e\157\156\143\145" => time(), "\x6f\x61\x75\x74\x68\x5f\164\151\x6d\145\x73\164\x61\155\160" => time(), "\157\141\x75\x74\150\x5f\143\157\156\163\x75\155\x65\x72\137\x6b\x65\171" => $this->key, "\157\141\x75\164\150\137\164\x6f\x6b\x65\156" => $SG, "\157\141\x75\x74\150\137\163\x69\147\156\x61\164\x75\x72\145\x5f\155\145\164\x68\x6f\x64" => "\110\x4d\x41\103\55\x53\110\x41\x31");
        if (!(strpos($this->userinfo_url, "\x3f") != false)) {
            goto IG;
        }
        $zn = explode("\x3f", $this->userinfo_url);
        $this->userinfo_url = $zn[0];
        $h2 = explode("\46", $zn[1]);
        foreach ($h2 as $Ge) {
            $U3 = explode("\x3d", $Ge);
            $Jt[$U3[0]] = $U3[1];
            Ir:
        }
        az:
        IG:
        $nR = $this->mo_oauth1_url_encode_rfc3986(array_keys($Jt));
        $EI = $this->mo_oauth1_url_encode_rfc3986(array_values($Jt));
        $Jt = array_combine($nR, $EI);
        uksort($Jt, "\x73\x74\162\x63\155\x70");
        foreach ($Jt as $Wu => $zs) {
            $xZ[] = $this->mo_oauth1_url_encode_rfc3986($Wu) . "\75" . $this->mo_oauth1_url_encode_rfc3986($zs);
            i3:
        }
        BH:
        $rW = implode("\46", $xZ);
        $gJ = "\x47\105\x54\x26" . $this->mo_oauth1_url_encode_rfc3986($this->userinfo_url) . "\46" . $this->mo_oauth1_url_encode_rfc3986($rW);
        $cH = $this->mo_oauth1_url_encode_rfc3986($this->secret) . "\x26" . $this->mo_oauth1_url_encode_rfc3986($v3);
        $Jt["\157\x61\x75\x74\150\x5f\x73\151\x67\156\141\x74\x75\x72\145"] = $this->mo_oauth1_url_encode_rfc3986(base64_encode(hash_hmac("\163\150\x61\x31", $gJ, $cH, TRUE)));
        uksort($Jt, "\163\164\x72\143\x6d\x70");
        foreach ($Jt as $Wu => $zs) {
            $Ek[] = $Wu . "\75" . $zs;
            l1:
        }
        oQ:
        $xN = implode("\x26", $Ek);
        $ht = $this->userinfo_url . "\77" . $xN;
        MO_Oauth_Debug::mo_oauth_log("\122\x65\x73\x6f\165\x72\x63\x65\40\x45\156\144\x70\157\151\156\x74\40\x55\122\x4c\x20\75\76\40" . $ht);
        $z5 = array();
        MO_Oauth_Debug::mo_oauth_log("\122\x65\163\x6f\x75\x72\x63\x65\x20\105\x6e\144\160\157\x69\x6e\164\40\151\x6e\x66\x6f\x20\x3d\x3e\x20");
        MO_Oauth_Debug::mo_oauth_log($Jt);
        $Rq = wp_remote_get($ht, $z5);
        MO_Oauth_Debug::mo_oauth_log("\x52\x65\x73\x6f\165\162\x63\145\x20\x45\x6e\x64\x70\157\x69\156\x74\40\122\x65\163\160\x6f\x6e\x73\x65\40\75\76\x20");
        MO_Oauth_Debug::mo_oauth_log($Rq);
        $kP = json_decode($Rq["\x62\157\x64\x79"], true);
        return $kP;
    }
    function mo_oauth1_https($ht, $Uc = null)
    {
        if (!isset($Uc)) {
            goto X1;
        }
        $z5 = array("\x6d\145\164\x68\157\x64" => "\x50\117\x53\124", "\x62\x6f\144\171" => $Uc, "\x74\151\x6d\x65\x6f\x75\x74" => "\65", "\162\145\144\x69\162\x65\143\x74\151\x6f\x6e" => "\65", "\x68\164\164\160\x76\x65\162\163\151\x6f\x6e" => "\x31\56\60", "\x62\x6c\157\x63\x6b\151\x6e\147" => true);
        MO_Oauth_Debug::mo_oauth_log("\x4f\x61\165\x74\x68\61\40\120\x4f\123\124\x20\x45\x6e\144\160\157\x69\x6e\x74\40\x41\x72\147\x75\155\x65\x6e\x74\163\x20\x3d\x3e\40");
        MO_Oauth_Debug::mo_oauth_log($Rq);
        $qD = wp_remote_post($ht, $z5);
        return $qD["\142\x6f\x64\x79"];
        X1:
        $z5 = array();
        $Rq = wp_remote_get($ht, $z5);
        if (!is_wp_error($Rq)) {
            goto lk;
        }
        wp_die($Rq);
        lk:
        $Yx = $Rq["\x62\x6f\144\x79"];
        return $Yx;
    }
    function mo_oauth1_url_encode_rfc3986($AZ)
    {
        if (is_array($AZ)) {
            goto x0;
        }
        if (is_scalar($AZ)) {
            goto eR;
        }
        return '';
        goto rk;
        eR:
        return str_replace("\53", "\x20", str_replace("\x25\x37\105", "\176", rawurlencode($AZ)));
        rk:
        goto uD;
        x0:
        return array_map(array("\x4d\157\117\141\x75\x74\150\103\154\x69\145\156\x74\134\115\117\137\103\x75\x73\x74\x6f\155\x5f\x4f\101\x75\164\x68\61\x5f\106\x6c\x6f\167", "\155\x6f\x5f\x6f\x61\165\164\150\61\x5f\165\162\x6c\137\x65\x6e\143\157\x64\145\x5f\162\146\143\63\x39\x38\66"), $AZ);
        uD:
    }
}
