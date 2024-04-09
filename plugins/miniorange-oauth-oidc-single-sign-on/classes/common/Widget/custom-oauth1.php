<?php


namespace MoOauthClient;

use MoOauthClient\MO_Oauth_Debug;
class MO_Custom_OAuth1
{
    public static function mo_oauth1_auth_request($d9)
    {
        global $Yh;
        $KY = $Yh->get_app_by_name($d9)->get_app_config();
        $z6 = $KY["\x63\154\x69\145\156\164\137\x69\144"];
        $MC = $KY["\x63\x6c\151\145\156\x74\x5f\163\x65\x63\x72\x65\x74"];
        $uR = $KY["\141\165\x74\x68\x6f\162\151\x7a\145\x75\x72\154"];
        $YA = $KY["\162\145\x71\165\145\163\x74\165\x72\154"];
        $TU = $KY["\x61\143\143\x65\x73\163\x74\x6f\153\x65\156\165\x72\x6c"];
        $uv = $KY["\162\145\163\157\165\x72\143\145\157\167\x6e\x65\x72\144\x65\x74\141\x69\154\163\x75\x72\154"];
        $cu = new MO_Custom_OAuth1_Flow($z6, $MC, $YA, $TU, $uv);
        $UO = $cu->mo_oauth1_get_request_token();
        if (!(strpos($uR, "\x3f") == false)) {
            goto sB;
        }
        $uR .= "\77";
        sB:
        $kB = $uR . "\157\141\x75\x74\150\137\164\157\x6b\x65\156\75" . $UO;
        if (!($UO == '' || $UO == NULL)) {
            goto ca;
        }
        MO_Oauth_Debug::mo_oauth_log("\105\162\162\157\162\x20\151\156\x20\122\145\161\165\145\163\164\40\x54\x6f\x6b\145\x6e\x20\105\156\144\160\157\151\156\164");
        $Yh->handle_error("\x45\162\x72\157\162\x20\x69\x6e\x20\122\145\x71\x75\x65\163\x74\40\x54\x6f\153\x65\156\x20\x45\156\x64\160\157\x69\156\164\x3a\40\x49\x6e\x76\x61\x6c\151\144\x20\x74\157\153\x65\156\x20\x72\145\143\145\x69\166\145\x64\56\x20\103\157\156\x74\141\x63\164\x20\164\x6f\x20\x79\x6f\x75\162\x20\141\x64\155\x69\x6d\x69\163\x74\x72\141\164\x6f\162\x20\x66\157\x72\40\x6d\x6f\x72\x65\40\x69\x6e\146\157\162\155\141\x74\x69\x6f\156\x2e");
        wp_die("\x45\162\x72\157\162\x20\151\x6e\40\x52\x65\161\165\x65\163\x74\40\x54\x6f\x6b\x65\156\x20\105\156\x64\x70\157\151\156\x74\72\x20\111\156\166\x61\x6c\x69\144\x20\x74\157\x6b\145\x6e\40\162\145\143\x65\x69\x76\145\144\56\x20\x43\157\156\x74\x61\x63\164\40\164\157\40\x79\x6f\x75\x72\40\141\x64\x6d\x69\x6d\151\x73\164\x72\141\164\157\162\x20\146\157\x72\x20\x6d\x6f\162\x65\40\151\x6e\146\157\x72\155\x61\x74\151\x6f\156\x2e");
        ca:
        MO_Oauth_Debug::mo_oauth_log("\x52\145\161\x75\x65\163\164\40\x54\x6f\153\145\156\40\x72\x65\143\x65\x69\x76\x65\x64\56");
        MO_Oauth_Debug::mo_oauth_log("\122\x65\x71\165\145\x73\164\x20\x54\157\153\x65\x6e\40\75\76\40");
        MO_Oauth_Debug::mo_oauth_log($UO);
        header("\114\x6f\143\x61\164\151\x6f\156\72" . $kB);
        exit;
    }
    static function mo_oidc1_get_access_token($d9)
    {
        $ys = explode("\x26", isset($_SERVER["\x52\x45\121\125\x45\x53\124\137\x55\x52\111"]) ? sanitize_text_field(wp_unslash($_SERVER["\122\105\x51\125\x45\123\x54\137\125\122\x49"])) : '');
        $ca = explode("\75", $ys[1]);
        $oB = explode("\x3d", $ys[0]);
        $mc = get_option("\155\157\137\157\x61\165\164\x68\137\141\160\160\163\137\154\151\x73\x74");
        $fZ = $d9;
        $I9 = null;
        foreach ($mc as $cW => $F8) {
            if (!($d9 == $cW)) {
                goto R5;
            }
            $I9 = $F8;
            goto Zm;
            R5:
            SV:
        }
        Zm:
        global $Yh;
        $KY = $Yh->get_app_by_name($d9)->get_app_config();
        $z6 = $KY["\x63\154\151\x65\156\164\x5f\x69\144"];
        $MC = $KY["\143\x6c\x69\x65\x6e\x74\137\163\145\143\x72\145\164"];
        $uR = $KY["\x61\165\x74\150\157\x72\x69\172\145\x75\x72\x6c"];
        $YA = $KY["\162\145\161\x75\x65\x73\164\165\x72\x6c"];
        $TU = $KY["\141\x63\x63\x65\163\163\164\x6f\153\145\x6e\165\162\x6c"];
        $uv = $KY["\x72\x65\163\157\x75\162\143\x65\x6f\x77\156\145\x72\144\145\x74\141\151\x6c\163\165\x72\154"];
        $di = new MO_Custom_OAuth1_Flow($z6, $MC, $YA, $TU, $uv);
        $ok = $di->mo_oauth1_get_access_token($ca[1], $oB[1]);
        $Oa = explode("\x26", $ok);
        $H4 = '';
        $cD = '';
        foreach ($Oa as $cW) {
            $E0 = explode("\x3d", $cW);
            if ($E0[0] == "\157\141\165\x74\x68\137\x74\x6f\x6b\x65\156") {
                goto jk;
            }
            if (!($E0[0] == "\157\x61\165\164\x68\x5f\164\157\153\x65\156\137\163\145\x63\x72\145\x74")) {
                goto oM;
            }
            $cD = $E0[1];
            oM:
            goto UH;
            jk:
            $H4 = $E0[1];
            UH:
            Ua:
        }
        dQ:
        MO_Oauth_Debug::mo_oauth_log("\101\x63\x63\145\x73\163\40\124\157\x6b\x65\156\x20\x72\145\143\145\x69\166\x65\x64\56");
        MO_Oauth_Debug::mo_oauth_log("\101\143\x63\145\163\163\40\x54\157\x6b\145\156\x20\75\76\x20");
        MO_Oauth_Debug::mo_oauth_log($H4);
        $CQ = new MO_Custom_OAuth1_Flow($z6, $MC, $YA, $TU, $uv);
        $S8 = isset($sR[1]) ? $sR[1] : '';
        $wF = isset($xi[1]) ? $xi[1] : '';
        $Mn = isset($F1[1]) ? $F1[1] : '';
        $St = $CQ->mo_oauth1_get_profile_signature($H4, $cD);
        if (isset($St)) {
            goto BX;
        }
        $Yh->handle_error("\111\156\x76\x61\x6c\151\x64\40\103\157\156\x66\x69\147\x75\162\x61\x74\151\157\156\163\56\x20\x50\x6c\145\141\163\x65\40\x63\157\x6e\164\141\x63\164\x20\164\x6f\x20\x74\x68\145\x20\x61\x64\x6d\151\155\x69\163\164\162\x61\x74\157\162\40\146\x6f\x72\x20\x6d\x6f\x72\x65\40\151\156\146\157\x72\x6d\141\164\151\157\x6e");
        wp_die("\x49\x6e\166\x61\154\151\x64\40\103\x6f\156\x66\x69\x67\165\x72\x61\x74\151\157\156\x73\56\x20\x50\x6c\x65\x61\x73\145\x20\143\157\x6e\164\141\x63\164\40\x74\157\40\x74\x68\x65\40\141\144\155\x69\x6d\x69\x73\164\x72\141\164\x6f\x72\40\146\157\162\x20\x6d\x6f\162\x65\40\x69\x6e\x66\x6f\162\155\x61\x74\x69\x6f\156");
        BX:
        return $St;
    }
}
class MO_Custom_OAuth1_Flow
{
    var $key = '';
    var $secret = '';
    var $request_token_url = '';
    var $access_token_url = '';
    var $userinfo_url = '';
    function __construct($As, $MC, $YA, $TU, $uv)
    {
        $this->key = $As;
        $this->secret = $MC;
        $this->request_token_url = $YA;
        $this->access_token_url = $TU;
        $this->userinfo_url = $uv;
    }
    function mo_oauth1_get_request_token()
    {
        $Zn = array("\x6f\x61\x75\x74\150\x5f\x76\145\x72\163\x69\157\156" => "\61\x2e\60", "\157\141\x75\164\150\137\156\x6f\x6e\x63\x65" => time(), "\x6f\141\x75\x74\x68\x5f\x74\x69\x6d\145\163\x74\141\x6d\160" => time(), "\x6f\141\165\x74\150\137\x63\x6f\156\x73\x75\155\x65\x72\x5f\153\x65\x79" => $this->key, "\157\141\165\x74\150\137\x73\151\147\x6e\x61\x74\165\162\145\137\x6d\x65\x74\x68\157\x64" => "\x48\x4d\x41\103\55\123\x48\101\61");
        if (!(strpos($this->request_token_url, "\x3f") != false)) {
            goto D8;
        }
        $sU = explode("\x3f", $this->request_token_url);
        $this->request_token_url = $sU[0];
        $LY = explode("\46", $sU[1]);
        foreach ($LY as $xK) {
            $Lv = explode("\x3d", $xK);
            $Zn[$Lv[0]] = $Lv[1];
            QM:
        }
        pn:
        D8:
        $gv = array_keys($Zn);
        $mR = array_values($Zn);
        $Zn = $this->mo_oauth1_url_encode_rfc3986(array_combine($gv, $mR));
        uksort($Zn, "\x73\x74\162\x63\155\160");
        foreach ($Zn as $JF => $w_) {
            $hr[] = $this->mo_oauth1_url_encode_rfc3986($JF) . "\75" . $this->mo_oauth1_url_encode_rfc3986($w_);
            BM:
        }
        BU:
        $fO = implode("\46", $hr);
        $n9 = $fO;
        $n9 = str_replace("\x3d", "\45\x33\104", $n9);
        $n9 = str_replace("\46", "\45\62\x36", $n9);
        $n9 = "\107\105\124\x26" . $this->mo_oauth1_url_encode_rfc3986($this->request_token_url) . "\46" . $n9;
        $Ju = $this->mo_oauth1_url_encode_rfc3986($this->secret) . "\x26";
        $Zn["\157\141\165\164\x68\x5f\163\151\x67\156\x61\164\165\x72\145"] = $this->mo_oauth1_url_encode_rfc3986(base64_encode(hash_hmac("\163\x68\141\61", $n9, $Ju, TRUE)));
        uksort($Zn, "\x73\x74\x72\x63\x6d\x70");
        foreach ($Zn as $JF => $w_) {
            $FW[] = $JF . "\75" . $w_;
            uR:
        }
        IR:
        $z3 = implode("\46", $FW);
        $Ws = $this->request_token_url . "\77" . $z3;
        MO_Oauth_Debug::mo_oauth_log("\122\x65\x71\x75\x65\x73\x74\x20\x54\x6f\153\x65\x6e\x20\125\x52\114\40\75\76\x20" . $Ws);
        $uh = $this->mo_oauth1_https($Ws);
        MO_Oauth_Debug::mo_oauth_log("\x52\x65\x71\x75\x65\x73\x74\x20\x54\157\x6b\145\156\40\x45\x6e\144\160\x6f\x69\156\x74\x20\x52\x65\163\160\157\x6e\x73\145\40\75\76\x20");
        MO_Oauth_Debug::mo_oauth_log($uh);
        $Hm = explode("\x26", $uh);
        $lt = '';
        foreach ($Hm as $cW) {
            $E0 = explode("\x3d", $cW);
            if ($E0[0] == "\157\x61\165\x74\150\x5f\x74\x6f\x6b\145\x6e") {
                goto ZK;
            }
            if (!($E0[0] == "\157\141\x75\164\x68\137\164\157\153\x65\x6e\x5f\x73\145\143\x72\145\164")) {
                goto R0;
            }
            setcookie("\155\x6f\x5f\x74\163", $E0[1], time() + 30);
            R0:
            goto p5;
            ZK:
            $lt = $E0[1];
            p5:
            sc:
        }
        Cd:
        return $lt;
    }
    function mo_oauth1_get_access_token($ca, $oB)
    {
        $Zn = array("\157\141\x75\164\x68\x5f\166\145\x72\x73\x69\157\156" => "\x31\56\60", "\x6f\x61\x75\x74\150\x5f\x6e\157\156\143\145" => time(), "\x6f\x61\165\164\150\137\x74\151\155\145\x73\164\141\155\x70" => time(), "\x6f\x61\x75\164\150\137\143\157\156\163\x75\155\145\162\137\153\145\x79" => $this->key, "\157\141\165\x74\150\137\164\x6f\153\x65\156" => $oB, "\157\x61\165\x74\150\x5f\163\x69\x67\x6e\x61\x74\x75\x72\145\137\155\145\164\x68\x6f\144" => "\x48\115\101\103\55\123\x48\x41\x31", "\157\141\x75\x74\x68\x5f\166\x65\x72\151\146\151\145\162" => $ca);
        $gv = $this->mo_oauth1_url_encode_rfc3986(array_keys($Zn));
        $mR = $this->mo_oauth1_url_encode_rfc3986(array_values($Zn));
        $Zn = array_combine($gv, $mR);
        uksort($Zn, "\x73\x74\162\x63\155\x70");
        foreach ($Zn as $JF => $w_) {
            $hr[] = $this->mo_oauth1_url_encode_rfc3986($JF) . "\x3d" . $this->mo_oauth1_url_encode_rfc3986($w_);
            lj:
        }
        Aw:
        $fO = implode("\46", $hr);
        $n9 = $fO;
        $n9 = str_replace("\x3d", "\45\63\104", $n9);
        $n9 = str_replace("\x26", "\x25\62\x36", $n9);
        $n9 = "\x47\105\x54\x26" . $this->mo_oauth1_url_encode_rfc3986($this->access_token_url) . "\x26" . $n9;
        $jE = isset($_COOKIE["\155\x6f\137\164\x73"]) ? $_COOKIE["\x6d\157\x5f\x74\x73"] : '';
        $Ju = $this->mo_oauth1_url_encode_rfc3986($this->secret) . "\x26" . $jE;
        $Zn["\157\141\x75\164\x68\137\x73\151\x67\156\141\164\165\162\145"] = $this->mo_oauth1_url_encode_rfc3986(base64_encode(hash_hmac("\x73\150\141\61", $n9, $Ju, TRUE)));
        uksort($Zn, "\x73\164\x72\x63\x6d\160");
        foreach ($Zn as $JF => $w_) {
            $FW[] = $JF . "\75" . $w_;
            ZV:
        }
        pK:
        $z3 = implode("\46", $FW);
        $Ws = $this->access_token_url . "\77" . $z3;
        MO_Oauth_Debug::mo_oauth_log("\x41\143\x63\x65\x73\x73\40\124\x6f\153\x65\156\x20\x45\156\x64\x70\x6f\151\156\x74\x20\125\x52\x4c\40\x3d\x3e\40" . $Ws);
        $uh = $this->mo_oauth1_https($Ws);
        MO_Oauth_Debug::mo_oauth_log("\x41\x63\143\x65\x73\163\40\124\157\x6b\145\x6e\x20\x45\156\144\160\157\151\x6e\164\40\122\x65\x73\160\x6f\x6e\163\145\40\75\x3e\40");
        MO_Oauth_Debug::mo_oauth_log($uh);
        return $uh;
    }
    function mo_oauth1_get_profile_signature($ok, $xi, $F1 = '')
    {
        $Zn = array("\x6f\141\165\164\x68\x5f\166\x65\162\x73\151\x6f\156" => "\61\56\x30", "\157\x61\165\164\150\x5f\x6e\x6f\156\x63\x65" => time(), "\157\x61\x75\x74\x68\137\164\151\155\x65\163\x74\x61\155\160" => time(), "\157\141\165\x74\x68\x5f\x63\x6f\x6e\x73\165\155\145\x72\137\153\x65\x79" => $this->key, "\157\x61\165\x74\150\137\x74\x6f\153\145\x6e" => $ok, "\157\x61\165\164\x68\137\163\x69\147\156\x61\164\x75\162\145\137\155\145\164\x68\157\144" => "\110\115\x41\103\x2d\123\x48\x41\x31");
        if (!(strpos($this->userinfo_url, "\x3f") != false)) {
            goto GY;
        }
        $sU = explode("\77", $this->userinfo_url);
        $this->userinfo_url = $sU[0];
        $LY = explode("\x26", $sU[1]);
        foreach ($LY as $xK) {
            $Lv = explode("\x3d", $xK);
            $Zn[$Lv[0]] = $Lv[1];
            t_:
        }
        JU:
        GY:
        $gv = $this->mo_oauth1_url_encode_rfc3986(array_keys($Zn));
        $mR = $this->mo_oauth1_url_encode_rfc3986(array_values($Zn));
        $Zn = array_combine($gv, $mR);
        uksort($Zn, "\x73\164\162\x63\155\160");
        foreach ($Zn as $JF => $w_) {
            $hr[] = $this->mo_oauth1_url_encode_rfc3986($JF) . "\75" . $this->mo_oauth1_url_encode_rfc3986($w_);
            Ge:
        }
        iU:
        $fO = implode("\46", $hr);
        $n9 = "\x47\x45\124\x26" . $this->mo_oauth1_url_encode_rfc3986($this->userinfo_url) . "\46" . $this->mo_oauth1_url_encode_rfc3986($fO);
        $Ju = $this->mo_oauth1_url_encode_rfc3986($this->secret) . "\x26" . $this->mo_oauth1_url_encode_rfc3986($xi);
        $Zn["\x6f\141\x75\164\150\x5f\x73\151\147\x6e\141\x74\165\x72\145"] = $this->mo_oauth1_url_encode_rfc3986(base64_encode(hash_hmac("\163\x68\141\x31", $n9, $Ju, TRUE)));
        uksort($Zn, "\x73\164\x72\143\x6d\160");
        foreach ($Zn as $JF => $w_) {
            $FW[] = $JF . "\75" . $w_;
            Tq:
        }
        eu:
        $z3 = implode("\x26", $FW);
        $Ws = $this->userinfo_url . "\x3f" . $z3;
        MO_Oauth_Debug::mo_oauth_log("\122\145\x73\157\165\162\143\145\40\105\x6e\144\x70\x6f\151\156\x74\40\125\x52\x4c\40\75\76\40" . $Ws);
        $uo = array();
        MO_Oauth_Debug::mo_oauth_log("\x52\x65\163\x6f\165\162\143\x65\x20\x45\156\x64\x70\x6f\x69\x6e\x74\x20\x69\x6e\x66\x6f\x20\75\x3e\x20");
        MO_Oauth_Debug::mo_oauth_log($Zn);
        $TW = wp_remote_get($Ws, $uo);
        MO_Oauth_Debug::mo_oauth_log("\x52\x65\x73\157\165\x72\x63\145\40\x45\x6e\144\x70\x6f\x69\156\164\40\122\145\163\160\157\156\163\145\40\75\76\x20");
        MO_Oauth_Debug::mo_oauth_log($TW);
        $St = json_decode($TW["\x62\x6f\144\x79"], true);
        return $St;
    }
    function mo_oauth1_https($Ws, $bf = null)
    {
        if (!isset($bf)) {
            goto GE;
        }
        $uo = array("\x6d\x65\x74\x68\x6f\x64" => "\120\117\123\124", "\142\157\x64\171" => $bf, "\164\151\155\x65\x6f\165\164" => "\61\x35", "\x72\145\144\x69\162\145\143\164\x69\x6f\156" => "\65", "\x68\x74\x74\160\x76\145\x72\163\151\157\156" => "\x31\x2e\x30", "\x62\154\157\143\153\151\156\x67" => true);
        MO_Oauth_Debug::mo_oauth_log("\117\x61\x75\164\x68\x31\x20\x50\117\x53\124\40\105\156\x64\x70\x6f\x69\156\164\x20\101\162\x67\x75\x6d\145\x6e\x74\x73\40\x3d\76\x20");
        MO_Oauth_Debug::mo_oauth_log($TW);
        $C4 = wp_remote_post($Ws, $uo);
        return $C4["\x62\x6f\x64\x79"];
        GE:
        $uo = array();
        $TW = wp_remote_get($Ws, $uo);
        if (!is_wp_error($TW)) {
            goto ZF;
        }
        $Yh->handle_error($TW);
        wp_die($TW);
        ZF:
        $uh = $TW["\x62\x6f\144\171"];
        return $uh;
    }
    function mo_oauth1_url_encode_rfc3986($I3)
    {
        if (is_array($I3)) {
            goto zj;
        }
        if (is_scalar($I3)) {
            goto aZ;
        }
        return '';
        goto ta;
        aZ:
        return str_replace("\x2b", "\x20", str_replace("\45\67\105", "\176", rawurlencode($I3)));
        ta:
        goto fi;
        zj:
        return array_map(array("\x4d\x6f\x4f\x61\x75\164\x68\103\154\x69\145\x6e\x74\134\x4d\117\x5f\103\165\163\x74\x6f\x6d\x5f\117\101\x75\164\x68\61\137\106\x6c\157\x77", "\155\x6f\x5f\157\141\165\164\150\61\137\165\x72\x6c\x5f\x65\x6e\x63\157\x64\x65\x5f\162\x66\143\x33\71\x38\66"), $I3);
        fi:
    }
}
