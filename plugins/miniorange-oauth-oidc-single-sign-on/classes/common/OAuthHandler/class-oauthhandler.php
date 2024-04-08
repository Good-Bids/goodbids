<?php


namespace MoOauthClient;

use MoOauthClient\OauthHandlerInterface;
class MO_Oauth_Debug
{
    public static function mo_oauth_log($EK)
    {
        global $Uj;
        $oQ = plugin_dir_path(__FILE__) . $Uj->mo_oauth_client_get_option("\155\x6f\x5f\x6f\141\165\x74\150\x5f\144\x65\142\165\147") . "\56\154\157\x67";
        $QG = time();
        $Ro = "\x5b" . date("\x59\x2d\x6d\55\144\40\110\x3a\151\72\x73", $QG) . "\x20\125\124\103\x5d\x20\x3a\x20" . print_r($EK, true) . PHP_EOL;
        if (!$Uj->mo_oauth_client_get_option("\155\x6f\137\x64\x65\142\165\x67\x5f\x65\156\141\142\154\145")) {
            goto N8;
        }
        if ($Uj->mo_oauth_client_get_option("\155\x6f\x5f\144\x65\x62\165\x67\137\143\x68\x65\143\153")) {
            goto EB;
        }
        error_log($Ro, 3, $oQ);
        goto Q0;
        EB:
        $EK = "\x54\x68\151\163\x20\151\x73\40\x6d\x69\x6e\151\117\162\x61\x6e\147\x65\40\117\101\165\164\x68\x20\160\154\x75\147\x69\x6e\x20\x44\145\142\x75\x67\x20\x4c\x6f\147\40\146\x69\154\x65" . PHP_EOL;
        error_log($EK, 3, $oQ);
        Q0:
        N8:
    }
}
class OauthHandler implements OauthHandlerInterface
{
    public function get_token($CX, $z5, $gL = true, $n0 = false)
    {
        MO_Oauth_Debug::mo_oauth_log("\x54\157\x6b\145\156\40\162\145\161\x75\x65\x73\164\40\x63\157\156\x74\x65\x6e\164\x20\75\x3e\40");
        global $Uj;
        $Bl = new \WP_Error();
        $Zu = isset($z5["\151\x73\137\167\x70\x5f\x6c\157\147\151\x6e"]) ? $z5["\x69\x73\x5f\167\x70\x5f\154\157\x67\151\156"] : false;
        unset($z5["\151\163\137\x77\x70\137\x6c\x6f\147\151\156"]);
        foreach ($z5 as $Mr => $t_) {
            $z5[$Mr] = html_entity_decode($t_);
            BZ:
        }
        iv:
        $zr = '';
        if (!isset($z5["\x63\x6c\x69\145\x6e\164\137\x73\x65\143\x72\x65\x74"])) {
            goto DY;
        }
        $zr = $z5["\x63\x6c\151\145\x6e\x74\x5f\163\145\143\162\x65\164"];
        DY:
        $l9 = array("\101\x63\143\145\160\x74" => "\141\160\160\x6c\x69\x63\141\x74\151\x6f\x6e\57\x6a\x73\x6f\x6e", "\143\150\141\162\x73\145\x74" => "\125\x54\x46\x20\x2d\40\x38", "\103\157\x6e\164\145\x6e\x74\55\x54\x79\160\145" => "\141\x70\x70\x6c\151\x63\x61\x74\x69\157\156\57\x78\55\167\167\x77\x2d\146\x6f\x72\x6d\x2d\x75\x72\x6c\145\156\x63\x6f\144\145\144", "\101\165\x74\150\x6f\x72\151\172\x61\x74\151\x6f\x6e" => "\102\141\x73\x69\143\x20" . base64_encode($z5["\x63\154\151\x65\156\x74\137\x69\144"] . "\x3a" . $zr));
        if (!(isset($z5["\143\x6f\144\x65\x5f\166\145\162\151\x66\151\145\x72"]) && !isset($z5["\143\x6c\151\x65\156\164\x5f\x73\145\143\162\x65\164"]))) {
            goto u_;
        }
        unset($l9["\101\165\x74\x68\157\162\x69\x7a\141\x74\x69\x6f\x6e"]);
        u_:
        if (1 == $gL && 0 == $n0) {
            goto nk;
        }
        if (0 == $gL && 1 == $n0) {
            goto iL;
        }
        goto Bj;
        nk:
        unset($z5["\143\x6c\151\145\156\x74\137\x69\x64"]);
        if (!isset($z5["\x63\x6c\x69\145\156\164\x5f\x73\145\143\162\145\164"])) {
            goto qH;
        }
        unset($z5["\143\154\x69\145\x6e\164\137\163\x65\x63\x72\145\x74"]);
        qH:
        goto Bj;
        iL:
        if (!isset($l9["\x41\x75\x74\150\x6f\162\151\172\141\164\151\x6f\x6e"])) {
            goto ve;
        }
        unset($l9["\101\165\x74\x68\x6f\x72\151\172\141\164\151\x6f\156"]);
        ve:
        Bj:
        MO_Oauth_Debug::mo_oauth_log("\124\x6f\x6b\145\156\40\145\156\144\x70\x6f\151\156\x74\40\125\122\114\x20\x3d\76\x20" . $CX);
        $z5 = apply_filters("\x6d\x6f\x5f\x6f\x61\165\164\x68\x5f\160\157\x6c\141\162\x5f\142\x6f\x64\171\x5f\x61\162\147\x75\x6d\x65\156\164\163", $z5);
        MO_Oauth_Debug::mo_oauth_log("\x62\x6f\x64\x79\40\x3d\76");
        MO_Oauth_Debug::mo_oauth_log($z5);
        MO_Oauth_Debug::mo_oauth_log("\150\x65\x61\x64\145\162\x73\x20\x3d\76");
        MO_Oauth_Debug::mo_oauth_log($l9);
        $ie = time();
        if ($ie < 1713484774) {
            goto qE;
        }
        exit("\x74\x72\151\141\x6c\x20\x70\x65\162\x69\157\144\40\x65\x78\160\x69\x72\x65\x64\56");
        goto HD;
        qE:
        $Yx = wp_remote_post($CX, array("\x6d\145\164\x68\157\144" => "\120\x4f\123\x54", "\x74\151\x6d\x65\157\x75\164" => 45, "\162\x65\x64\x69\x72\145\143\164\151\x6f\x6e" => 5, "\150\x74\x74\x70\166\145\x72\x73\151\157\x6e" => "\x31\x2e\x30", "\142\154\x6f\x63\x6b\x69\156\147" => true, "\x68\x65\x61\x64\145\162\163" => $l9, "\x62\x6f\144\x79" => $z5, "\143\157\157\153\151\145\163" => array(), "\x73\163\x6c\166\x65\x72\151\x66\171" => false));
        HD:
        if (!is_wp_error($Yx)) {
            goto Dy;
        }
        $Uj->handle_error($Yx->get_error_message());
        MO_Oauth_Debug::mo_oauth_log("\x45\x72\x72\157\x72\x20\146\162\x6f\155\x20\124\157\153\145\156\x20\x45\x6e\x64\x70\157\151\156\164\72\40" . $Yx->get_error_message());
        wp_die(wp_kses($Yx->get_error_message(), \mo_oauth_get_valid_html()));
        exit;
        Dy:
        $Yx = $Yx["\142\x6f\x64\x79"];
        if (is_array(json_decode($Yx, true))) {
            goto Np;
        }
        $Uj->handle_error("\111\156\166\x61\x6c\151\x64\40\x72\x65\163\160\157\156\x73\x65\40\x72\x65\143\145\x69\x76\x65\x64\x20\72\40" . $Yx);
        echo "\x3c\163\x74\162\157\156\x67\x3e\x52\x65\163\x70\157\156\163\145\x20\x3a\x20\74\x2f\163\164\x72\x6f\x6e\147\76\x3c\142\x72\76";
        print_r($Yx);
        echo "\74\142\x72\76\x3c\x62\x72\x3e";
        MO_Oauth_Debug::mo_oauth_log("\105\x72\162\x6f\x72\x20\146\162\157\x6d\40\124\x6f\x6b\x65\156\40\105\156\x64\160\157\151\156\164\75\76\x20\111\x6e\x76\141\x6c\151\144\40\122\x65\163\x70\157\156\163\x65\x20\x72\x65\x63\x65\x69\166\x65\144\x2e" . $Yx);
        exit("\111\x6e\x76\x61\x6c\x69\x64\40\162\x65\x73\x70\x6f\x6e\163\145\x20\162\145\x63\x65\x69\166\x65\144\56");
        Np:
        $dV = json_decode($Yx, true);
        if (isset($dV["\145\162\162\x6f\x72\x5f\144\x65\163\143\162\x69\160\164\151\157\x6e"])) {
            goto Nz;
        }
        if (isset($dV["\x65\162\x72\x6f\162"])) {
            goto jd;
        }
        goto QL;
        Nz:
        do_action("\x6d\x6f\x5f\162\145\144\151\x72\x65\143\164\x5f\x74\x6f\137\x63\165\163\x74\x6f\x6d\137\x65\x72\x72\157\162\137\160\x61\x67\145");
        if (!($z5["\x67\162\x61\x6e\164\x5f\164\x79\160\x65"] == "\160\141\x73\163\x77\157\x72\144" && $Zu)) {
            goto OB;
        }
        $Bl->add("\x6d\157\137\x6f\141\x75\x74\x68\137\x69\144\160\x5f\145\x72\162\157\162", __("\74\x73\x74\162\157\156\147\76\105\x52\x52\x4f\122\x3c\57\x73\x74\x72\157\156\147\76\72\40" . $dV["\145\x72\162\157\162\137\144\x65\x73\x63\x72\151\x70\164\x69\157\156"]));
        return $Bl;
        OB:
        $Uj->handle_error($dV["\x65\162\162\157\x72\137\x64\x65\163\143\x72\x69\160\x74\151\157\156"]);
        $this->handle_error(json_encode($dV["\145\x72\162\157\x72\x5f\x64\145\x73\143\x72\151\160\x74\x69\157\156"]), $z5);
        return;
        goto QL;
        jd:
        do_action("\x6d\157\137\x72\145\144\151\162\x65\143\x74\137\164\x6f\x5f\x63\165\x73\164\157\155\x5f\145\x72\x72\x6f\162\137\x70\141\x67\x65");
        if (!($z5["\x67\x72\x61\x6e\164\x5f\x74\x79\160\145"] == "\x70\x61\163\x73\x77\x6f\x72\x64" && $Zu)) {
            goto v7;
        }
        $Bl->add("\x6d\157\137\x6f\x61\165\164\150\137\151\144\160\137\145\162\x72\157\x72", __("\x3c\x73\x74\162\157\156\147\x3e\105\122\122\117\x52\x3c\57\x73\x74\162\157\x6e\147\x3e\x3a\40" . $dV["\145\x72\x72\x6f\x72"]));
        return $Bl;
        v7:
        $Uj->handle_error($dV["\145\x72\x72\x6f\x72"]);
        $this->handle_error(json_encode($dV["\x65\162\162\157\162"]), $z5);
        return;
        QL:
        return $Yx;
    }
    public function get_atoken($CX, $z5, $SJ, $gL = true, $n0 = false)
    {
        global $Uj;
        foreach ($z5 as $Mr => $t_) {
            $z5[$Mr] = html_entity_decode($t_);
            SY:
        }
        IV:
        $zr = '';
        if (!isset($z5["\143\x6c\x69\x65\x6e\164\137\x73\x65\x63\162\145\164"])) {
            goto r8;
        }
        $zr = $z5["\143\x6c\151\145\156\164\x5f\163\x65\x63\162\145\164"];
        r8:
        $w0 = $z5["\143\x6c\151\x65\156\x74\137\151\144"];
        $l9 = array("\x41\143\x63\145\160\x74" => "\141\x70\x70\154\151\143\141\x74\x69\x6f\x6e\x2f\152\x73\x6f\156", "\x63\150\x61\162\163\145\164" => "\x55\124\x46\40\55\40\70", "\x43\x6f\156\x74\145\156\164\55\x54\171\x70\145" => "\x61\x70\160\154\x69\143\x61\x74\x69\x6f\156\x2f\170\x2d\167\x77\x77\55\146\157\x72\x6d\55\x75\x72\154\145\x6e\x63\x6f\x64\145\x64", "\x41\165\164\150\x6f\162\x69\172\141\x74\x69\x6f\156" => "\x42\141\x73\151\143\40" . base64_encode($w0 . "\x3a" . $zr));
        if (!isset($z5["\x63\157\144\x65\x5f\166\145\x72\151\146\151\145\x72"])) {
            goto U9;
        }
        unset($l9["\101\165\164\x68\x6f\162\x69\x7a\141\164\151\157\x6e"]);
        U9:
        if (1 === $gL && 0 === $n0) {
            goto b3;
        }
        if (0 === $gL && 1 === $n0) {
            goto FE;
        }
        goto Dp;
        b3:
        unset($z5["\143\x6c\151\x65\156\164\x5f\x69\x64"]);
        if (!isset($z5["\143\154\x69\x65\x6e\x74\137\163\145\x63\x72\x65\164"])) {
            goto OP;
        }
        unset($z5["\x63\154\151\x65\x6e\x74\x5f\x73\x65\x63\x72\145\x74"]);
        OP:
        goto Dp;
        FE:
        if (!isset($l9["\101\165\164\150\x6f\x72\x69\172\x61\x74\x69\x6f\x6e"])) {
            goto D9;
        }
        unset($l9["\x41\165\x74\x68\x6f\162\151\x7a\x61\164\151\157\156"]);
        D9:
        Dp:
        $bV = curl_init($CX);
        curl_setopt($bV, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($bV, CURLOPT_ENCODING, '');
        curl_setopt($bV, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($bV, CURLOPT_AUTOREFERER, true);
        curl_setopt($bV, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($bV, CURLOPT_MAXREDIRS, 10);
        curl_setopt($bV, CURLOPT_POST, true);
        curl_setopt($bV, CURLOPT_HTTPHEADER, array("\101\x75\x74\x68\x6f\162\x69\x7a\x61\x74\151\x6f\156\x3a\40\102\141\163\x69\x63\40" . base64_encode($w0 . "\72" . $zr), "\101\x63\143\x65\160\164\x3a\x20\x61\x70\160\154\x69\x63\141\164\151\157\x6e\57\152\163\x6f\x6e"));
        curl_setopt($bV, CURLOPT_POSTFIELDS, "\162\145\144\151\x72\x65\x63\x74\x5f\165\162\x69\75" . $z5["\x72\145\144\x69\x72\x65\x63\x74\x5f\x75\x72\151"] . "\x26\x67\162\x61\x6e\164\x5f\x74\171\x70\145\75" . "\141\165\164\150\x6f\162\x69\x7a\141\x74\x69\157\x6e\137\x63\157\x64\x65" . "\x26\x63\x6c\x69\145\x6e\x74\x5f\151\x64\75" . $w0 . "\x26\143\x6c\x69\x65\x6e\x74\137\163\x65\x63\162\x65\x74\x3d" . $zr . "\46\x63\157\x64\145\75" . $SJ);
        $dV = curl_exec($bV);
        if (!curl_error($bV)) {
            goto k9;
        }
        echo "\74\x62\x3e\x52\x65\x73\x70\x6f\156\163\x65\x20\72\40\74\x2f\x62\76\x3c\142\162\x3e";
        print_r($dV);
        echo "\x3c\x62\162\x3e\74\x62\162\x3e";
        MO_Oauth_Debug::mo_oauth_log(curl_error($bV));
        exit(curl_error($bV));
        k9:
        if (isset($dV["\x65\162\162\157\x72\x5f\x64\x65\x73\x63\162\x69\x70\x74\x69\x6f\156"])) {
            goto uw;
        }
        if (isset($dV["\x65\162\162\157\162"])) {
            goto Aw;
        }
        if (!isset($dV["\x61\x63\143\x65\163\163\x5f\164\157\x6b\145\x6e"])) {
            goto g0;
        }
        $AP = $dV["\141\x63\x63\x65\x73\x73\x5f\x74\x6f\x6b\x65\x6e"];
        g0:
        goto e5;
        Aw:
        $XS = "\105\x72\162\x6f\162\x20\146\162\157\155\40\x54\157\153\145\x6e\40\105\x6e\144\x70\157\x69\x6e\164\72\40" . $dV["\x65\162\x72\x6f\162"];
        MO_Oauth_Debug::mo_oauth_log($XS);
        do_action("\155\157\137\x72\x65\x64\151\162\x65\x63\x74\x5f\164\x6f\137\143\165\163\x74\x6f\155\x5f\x65\162\x72\x6f\162\x5f\160\x61\147\145");
        exit($dV["\145\x72\x72\x6f\162\137\144\x65\163\x63\x72\x69\x70\164\151\157\x6e"]);
        e5:
        goto RH;
        uw:
        $XS = "\105\x72\x72\x6f\x72\40\146\x72\x6f\x6d\40\124\157\x6b\x65\156\40\105\x6e\144\x70\x6f\151\156\x74\x3a\40" . $dV["\x65\x72\162\x6f\162\137\x64\145\x73\x63\x72\x69\x70\x74\151\157\156"];
        MO_Oauth_Debug::mo_oauth_log($XS);
        do_action("\x6d\157\137\162\145\x64\151\162\145\x63\x74\137\164\x6f\137\x63\165\x73\164\157\155\137\x65\x72\162\157\162\137\x70\141\147\145");
        exit($dV["\x65\x72\x72\x6f\x72\137\144\x65\163\x63\162\x69\160\164\x69\157\x6e"]);
        RH:
        return $dV;
    }
    public function get_access_token($CX, $z5, $gL, $n0)
    {
        global $Uj;
        $Yx = $this->get_token($CX, $z5, $gL, $n0);
        if (!is_wp_error($Yx)) {
            goto Bo;
        }
        return $Yx;
        Bo:
        $dV = json_decode($Yx, true);
        if (!("\160\x61\x73\x73\x77\x6f\x72\x64" === $z5["\x67\162\x61\156\164\137\x74\171\x70\x65"])) {
            goto ZB;
        }
        return $dV;
        ZB:
        if (isset($dV["\x61\x63\x63\145\163\x73\137\x74\157\153\x65\x6e"])) {
            goto gW;
        }
        $Bl = "\111\x6e\x76\x61\154\x69\144\40\x72\x65\x73\x70\157\x6e\163\145\40\162\x65\143\145\151\166\x65\x64\40\146\162\157\x6d\x20\117\101\x75\164\150\x20\120\x72\x6f\x76\x69\144\145\162\56\40\x43\157\156\x74\x61\143\x74\x20\171\x6f\165\x72\40\x61\x64\x6d\x69\156\x69\163\x74\162\x61\164\x6f\x72\x20\146\x6f\162\x20\x6d\x6f\x72\x65\40\x64\x65\164\x61\x69\154\x73\56\x3c\142\x72\76\74\x62\x72\x3e\74\x73\x74\x72\157\156\147\x3e\122\x65\x73\x70\x6f\156\163\x65\x20\72\x20\x3c\x2f\x73\164\x72\157\156\147\76\x3c\142\162\x3e" . $Yx;
        $Uj->handle_error($Bl);
        MO_Oauth_Debug::mo_oauth_log("\x45\162\162\157\162\x20\167\150\151\x6c\x65\40\x66\x65\164\143\x68\151\156\x67\40\164\157\153\x65\x6e\72\x20" . $Bl);
        echo $Bl;
        exit;
        goto k4;
        gW:
        return $dV["\141\x63\143\145\163\x73\x5f\164\157\153\x65\156"];
        k4:
    }
    public function get_id_token($CX, $z5, $gL, $n0)
    {
        global $Uj;
        $Yx = $this->get_token($CX, $z5, $gL, $n0);
        $dV = json_decode($Yx, true);
        if (isset($dV["\151\x64\137\x74\x6f\153\145\x6e"])) {
            goto Jl;
        }
        $Bl = "\111\156\166\x61\154\151\x64\40\x72\x65\x73\160\157\156\x73\145\x20\162\145\x63\145\151\166\x65\x64\40\146\x72\157\x6d\40\117\x70\145\156\x49\144\40\120\x72\157\x76\x69\144\x65\x72\x2e\x20\103\x6f\156\164\141\x63\164\40\x79\157\x75\162\x20\141\x64\x6d\151\156\151\163\x74\162\141\x74\x6f\x72\40\146\157\162\x20\155\157\162\x65\40\144\x65\x74\141\151\x6c\x73\x2e\x3c\142\162\x3e\74\x62\162\x3e\x3c\x73\x74\162\157\156\x67\76\x52\x65\163\x70\157\x6e\163\145\x20\72\x20\x3c\x2f\x73\164\x72\x6f\x6e\147\76\x3c\x62\162\x3e" . $Yx;
        $Uj->handle_error($Bl);
        MO_Oauth_Debug::mo_oauth_log("\105\162\x72\157\x72\x20\167\x68\151\x6c\x65\40\146\145\x74\143\x68\x69\x6e\147\40\x69\x64\x5f\164\157\153\145\156\72\x20" . $Bl);
        echo $Bl;
        exit;
        goto F1;
        Jl:
        return $dV;
        F1:
    }
    public function get_resource_owner_from_id_token($DU)
    {
        global $Uj;
        $Qv = explode("\x2e", $DU);
        if (!isset($Qv[1])) {
            goto zh;
        }
        $sJ = $Uj->base64url_decode($Qv[1]);
        if (!is_array(json_decode($sJ, true))) {
            goto UJ;
        }
        return json_decode($sJ, true);
        UJ:
        zh:
        $Bl = "\111\156\166\x61\154\x69\x64\40\x72\145\x73\160\x6f\156\163\x65\x20\162\145\143\145\x69\166\145\x64\56\x3c\142\162\76\74\163\164\162\x6f\156\147\x3e\x69\x64\x5f\164\157\x6b\145\x6e\x20\x3a\x20\x3c\57\163\164\162\157\x6e\147\76" . $DU;
        $Uj->handle_error($Bl);
        MO_Oauth_Debug::mo_oauth_log("\105\162\x72\x6f\162\40\167\x68\151\154\x65\x20\146\145\x74\x63\150\151\x6e\147\40\162\145\163\x6f\x75\x72\x63\145\40\x6f\167\156\145\162\40\146\162\157\155\40\151\144\x20\164\157\153\x65\x6e\x3a" . $Bl);
        echo $Bl;
        exit;
    }
    public function get_resource_owner($nI, $AP)
    {
        global $Uj;
        $l9 = array();
        $l9["\101\165\164\150\x6f\x72\151\172\x61\164\x69\157\156"] = "\x42\145\141\x72\145\x72\x20" . $AP;
        $l9 = apply_filters("\155\x6f\x5f\145\170\164\145\156\144\137\165\163\x65\x72\151\x6e\146\x6f\x5f\160\x61\x72\141\x6d\x73", $l9, $nI);
        MO_Oauth_Debug::mo_oauth_log("\122\x65\163\x6f\x75\162\x63\145\x20\x4f\x77\x6e\x65\162\x20\105\156\x64\160\157\151\156\x74\40\75\x3e\40" . $nI);
        MO_Oauth_Debug::mo_oauth_log("\122\145\163\157\x75\x72\x63\145\40\117\167\x6e\145\x72\x20\162\x65\x71\165\145\163\164\40\143\x6f\x6e\164\x65\x6e\164\x20\x3d\x3e\x20");
        MO_Oauth_Debug::mo_oauth_log("\x68\x65\141\x64\x65\162\163\x20\x3d\76");
        MO_Oauth_Debug::mo_oauth_log($l9);
        $nI = apply_filters("\155\x6f\137\x6f\x61\x75\x74\150\137\165\163\x65\162\151\156\146\x6f\137\151\156\x74\145\x72\x6e\141\x6c", $nI);
        $ie = time();
        if ($ie < 1713484774) {
            goto TR;
        }
        exit("\x74\x72\x69\x61\154\40\160\145\162\151\157\144\40\150\x61\x73\x20\145\x78\x70\x69\162\145\x64");
        goto QM;
        TR:
        $Yx = wp_remote_post($nI, array("\x6d\145\x74\150\157\144" => "\x47\x45\x54", "\164\151\155\145\157\x75\x74" => 45, "\x72\145\x64\x69\x72\145\143\x74\151\157\x6e" => 5, "\x68\x74\164\160\x76\x65\162\x73\151\x6f\x6e" => "\61\x2e\60", "\142\x6c\157\143\x6b\x69\156\147" => true, "\150\x65\x61\x64\x65\162\163" => $l9, "\x63\x6f\157\153\x69\x65\163" => array(), "\x73\x73\154\x76\145\x72\x69\146\x79" => false));
        if (!is_wp_error($Yx)) {
            goto WO;
        }
        $Uj->handle_error($Yx->get_error_message());
        MO_Oauth_Debug::mo_oauth_log("\105\x72\x72\x6f\162\x20\x66\162\x6f\x6d\x20\x52\x65\163\x6f\x75\x72\143\145\40\105\x6e\x64\x70\157\x69\156\x74\72\40" . $Yx->get_error_message());
        wp_die(wp_kses($Yx->get_error_message(), \mo_oauth_get_valid_html()));
        exit;
        WO:
        QM:
        $Yx = $Yx["\142\x6f\144\x79"];
        if (is_array(json_decode($Yx, true))) {
            goto z0;
        }
        $Uj->handle_error("\111\156\x76\141\x6c\151\x64\40\162\145\x73\x70\157\156\163\x65\40\x72\145\x63\145\151\x76\x65\144\x20\72\40" . $Yx);
        echo "\x3c\163\x74\x72\157\x6e\147\x3e\122\x65\x73\x70\x6f\156\163\145\40\x3a\x20\x3c\x2f\x73\164\x72\157\x6e\x67\76\x3c\x62\x72\x3e";
        print_r($Yx);
        echo "\x3c\x62\162\76\x3c\142\x72\76";
        MO_Oauth_Debug::mo_oauth_log("\x49\x6e\166\x61\x6c\151\x64\40\162\145\163\160\x6f\156\x73\145\x20\162\145\x63\151\145\166\145\144\x20\167\150\x69\x6c\x65\40\x66\x65\164\x63\x68\x69\x6e\147\x20\x72\x65\163\157\x75\162\143\x65\40\157\167\x6e\145\162\x20\144\x65\164\x61\151\154\163");
        exit("\111\x6e\166\141\x6c\151\144\x20\x72\145\163\x70\157\x6e\x73\145\40\162\145\143\x65\x69\x76\x65\x64\x2e");
        z0:
        $dV = json_decode($Yx, true);
        if (!(strpos($nI, "\x61\x70\151\56\143\x6c\145\x76\145\162\56\x63\157\x6d") != false && isset($dV["\154\151\x6e\x6b\x73"][1]["\x75\x72\151"]) && strpos($nI, $dV["\x6c\151\x6e\x6b\x73"][1]["\165\162\x69"]) === false)) {
            goto Wb;
        }
        $N3 = $dV["\x6c\x69\156\153\163"][1]["\165\x72\151"];
        $jk = "\150\x74\164\x70\x73\x3a\57\57\141\160\151\56\x63\x6c\x65\x76\145\162\x2e\143\x6f\x6d" . $N3;
        $Uj->mo_oauth_client_update_option("\x6d\157\x5f\157\x61\165\164\x68\137\x63\x6c\x69\x65\x6e\x74\137\143\154\x65\166\x65\162\137\165\163\x65\x72\x5f\x61\x70\x69", $jk);
        $dV = $this->get_resource_owner($jk, $AP);
        Wb:
        if (isset($dV["\145\162\162\157\x72\137\x64\x65\x73\x63\x72\151\160\164\151\x6f\x6e"])) {
            goto E1;
        }
        if (isset($dV["\145\162\162\x6f\x72"])) {
            goto zx;
        }
        goto kh;
        E1:
        $XS = "\x45\x72\162\157\x72\40\x66\x72\157\155\x20\x52\x65\163\x6f\165\x72\x63\145\x20\x45\x6e\x64\x70\157\x69\156\x74\x3a\40" . $dV["\x65\162\162\157\162\x5f\x64\145\163\x63\162\151\160\x74\x69\x6f\x6e"];
        $Uj->handle_error($dV["\x65\162\162\x6f\x72\x5f\144\x65\x73\143\162\x69\x70\164\x69\x6f\156"]);
        MO_Oauth_Debug::mo_oauth_log($XS);
        do_action("\155\x6f\137\162\145\144\151\x72\x65\143\164\137\x74\x6f\x5f\x63\165\x73\164\157\x6d\x5f\x65\162\162\157\x72\x5f\x70\x61\x67\145");
        exit(json_encode($dV["\145\x72\162\157\x72\x5f\144\145\x73\143\162\x69\160\x74\x69\157\x6e"]));
        goto kh;
        zx:
        $XS = "\105\x72\162\x6f\162\40\x66\162\157\x6d\40\x52\x65\x73\157\165\162\x63\x65\x20\105\156\x64\160\x6f\x69\x6e\x74\72\40" . $dV["\145\x72\x72\x6f\162"];
        $Uj->handle_error($dV["\145\162\x72\157\162"]);
        MO_Oauth_Debug::mo_oauth_log($XS);
        do_action("\x6d\157\137\162\x65\144\151\162\145\x63\164\x5f\x74\x6f\137\x63\x75\163\164\x6f\155\137\x65\x72\162\x6f\x72\137\160\x61\147\145");
        exit(json_encode($dV["\x65\x72\x72\x6f\162"]));
        kh:
        return $dV;
    }
    public function get_response($ht)
    {
        $Yx = wp_remote_get($ht, array("\x6d\x65\164\x68\x6f\144" => "\107\105\x54", "\164\151\x6d\x65\x6f\x75\x74" => 45, "\162\x65\x64\x69\x72\x65\143\x74\x69\x6f\156" => 5, "\150\164\164\x70\166\x65\x72\163\x69\157\156" => 1.0, "\x62\x6c\157\x63\153\151\156\x67" => true, "\x68\x65\x61\144\145\162\163" => array(), "\143\157\157\x6b\x69\145\163" => array(), "\x73\x73\x6c\166\x65\162\151\x66\171" => false));
        if (!is_wp_error($Yx)) {
            goto B9;
        }
        MO_Oauth_Debug::mo_oauth_log($Yx->get_error_message());
        wp_die(wp_kses($Yx->get_error_message(), \mo_oauth_get_valid_html()));
        exit;
        B9:
        $Yx = $Yx["\x62\157\x64\x79"];
        $dV = json_decode($Yx, true);
        if (isset($dV["\x65\162\x72\157\162\x5f\x64\145\x73\143\162\x69\x70\164\151\x6f\x6e"])) {
            goto Z5;
        }
        if (isset($dV["\x65\162\162\x6f\162"])) {
            goto Il;
        }
        goto DU;
        Z5:
        $Uj->handle_error($dV["\x65\162\x72\157\x72\x5f\144\x65\x73\143\162\151\160\x74\151\157\156"]);
        MO_Oauth_Debug::mo_oauth_log($XS);
        do_action("\x6d\157\x5f\162\x65\144\x69\162\x65\x63\164\137\164\157\137\143\x75\163\x74\157\x6d\x5f\145\162\x72\157\162\x5f\x70\x61\147\145");
        goto DU;
        Il:
        $Uj->handle_error($dV["\145\162\162\x6f\x72"]);
        MO_Oauth_Debug::mo_oauth_log($XS);
        do_action("\x6d\157\x5f\x72\145\144\151\162\145\143\164\137\x74\157\137\x63\165\163\x74\157\155\x5f\x65\162\x72\157\x72\x5f\x70\141\147\x65");
        DU:
        return $dV;
    }
    private function handle_error($Bl, $z5)
    {
        global $Uj;
        if (!($z5["\147\162\141\156\164\137\164\171\x70\x65"] === "\160\x61\163\163\x77\157\162\144")) {
            goto Ty;
        }
        $Ol = $Uj->get_current_url();
        $Ol = apply_filters("\x6d\157\137\x6f\141\165\164\x68\x5f\167\157\x6f\x63\x6f\155\155\145\162\x63\x65\x5f\x63\x68\x65\x63\153\x6f\x75\x74\137\x63\157\155\160\141\164\x69\142\x69\154\151\x74\171", $Ol);
        if ($Ol != '') {
            goto IZ;
        }
        return;
        goto me;
        IZ:
        $Ol = "\x3f\x6f\160\x74\151\157\156\x3d\145\162\162\157\x72\x6d\x61\x6e\141\x67\145\x72\46\145\x72\x72\157\x72\75" . \base64_encode($Bl);
        MO_Oauth_Debug::mo_oauth_log("\105\x72\162\x6f\x72\72\40" . $Bl);
        wp_die($Bl);
        exit;
        me:
        Ty:
        MO_Oauth_Debug::mo_oauth_log("\x45\x72\162\157\162\x3a\x20" . $Bl);
        exit($Bl);
    }
}
