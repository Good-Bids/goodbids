<?php


namespace MoOauthClient;

use MoOauthClient\OauthHandlerInterface;
class MO_Oauth_Debug
{
    public static function mo_oauth_log($Al)
    {
        global $Yh;
        $aG = plugin_dir_path(__FILE__) . $Yh->mo_oauth_client_get_option("\155\157\137\157\x61\165\164\x68\x5f\144\145\142\165\147") . "\56\x6c\x6f\147";
        $wK = time();
        $zr = "\133" . date("\131\x2d\155\55\x64\x20\110\72\x69\72\163", $wK) . "\x20\x55\124\103\x5d\x20\x3a\40" . print_r($Al, true) . PHP_EOL;
        if (!$Yh->mo_oauth_client_get_option("\x6d\157\x5f\144\145\142\x75\x67\137\145\156\141\x62\x6c\x65")) {
            goto Uy;
        }
        if ($Yh->mo_oauth_client_get_option("\x6d\157\x5f\x64\x65\142\165\x67\x5f\x63\x68\x65\x63\x6b")) {
            goto lN;
        }
        error_log($zr, 3, $aG);
        goto pO;
        lN:
        $Al = "\x54\150\151\163\40\x69\163\40\155\x69\x6e\x69\x4f\x72\x61\156\147\145\40\117\101\x75\x74\150\x20\160\154\165\147\x69\x6e\x20\104\145\142\x75\147\x20\114\x6f\147\x20\146\151\x6c\x65" . PHP_EOL;
        error_log($Al, 3, $aG);
        pO:
        Uy:
    }
}
class OauthHandler implements OauthHandlerInterface
{
    public function get_token($vy, $uo, $U_ = true, $fn = false)
    {
        MO_Oauth_Debug::mo_oauth_log("\124\157\153\x65\x6e\40\x72\x65\x71\x75\x65\x73\164\40\x63\x6f\x6e\164\145\156\164\40\x3d\x3e\x20");
        global $Yh;
        $N5 = new \WP_Error();
        $lF = isset($uo["\151\x73\x5f\x77\160\x5f\154\157\x67\x69\156"]) ? $uo["\151\163\137\167\x70\137\x6c\x6f\147\x69\x6e"] : false;
        unset($uo["\151\163\137\x77\x70\137\154\x6f\x67\151\x6e"]);
        foreach ($uo as $cW => $LQ) {
            $uo[$cW] = html_entity_decode($LQ);
            C6:
        }
        ZW:
        $MC = '';
        if (!isset($uo["\x63\x6c\x69\x65\x6e\164\137\x73\x65\x63\162\145\x74"])) {
            goto ZI;
        }
        $MC = $uo["\143\x6c\151\145\x6e\164\x5f\x73\x65\x63\162\145\164"];
        ZI:
        $k7 = array("\x41\143\x63\145\x70\x74" => "\141\160\x70\154\151\143\141\x74\151\x6f\x6e\57\x6a\163\x6f\156", "\x63\150\x61\162\163\145\164" => "\x55\x54\x46\40\55\40\70", "\x43\157\x6e\164\x65\156\164\x2d\124\x79\160\145" => "\141\x70\160\x6c\151\143\141\x74\x69\157\156\57\x78\x2d\x77\167\167\x2d\146\x6f\x72\155\55\x75\x72\154\145\156\143\157\x64\145\144", "\101\x75\164\150\x6f\162\x69\172\141\x74\151\157\156" => "\102\141\x73\x69\143\x20" . base64_encode($uo["\143\x6c\x69\x65\x6e\164\137\151\144"] . "\72" . $MC));
        $k7 = apply_filters("\155\157\137\x6f\x61\x75\164\150\137\x63\157\x75\163\164\157\155\137\145\170\x74\145\x6e\144\137\164\157\x6b\145\x6e\145\x6e\x64\160\157\151\156\x74\137\160\x61\162\141\155\163", $k7);
        if (!(isset($uo["\143\157\x64\145\x5f\166\145\162\151\146\x69\145\x72"]) && !isset($uo["\x63\x6c\x69\x65\x6e\x74\137\x73\145\143\162\145\x74"]))) {
            goto wG;
        }
        unset($k7["\101\165\164\150\x6f\162\x69\x7a\141\164\x69\x6f\x6e"]);
        wG:
        if (1 == $U_ && 0 == $fn) {
            goto qO;
        }
        if (0 == $U_ && 1 == $fn) {
            goto wf;
        }
        goto WE;
        qO:
        unset($uo["\143\154\151\145\x6e\164\x5f\x69\144"]);
        if (!isset($uo["\143\x6c\x69\145\x6e\x74\137\163\x65\x63\x72\x65\x74"])) {
            goto k0;
        }
        unset($uo["\143\x6c\151\145\156\x74\x5f\163\x65\x63\x72\x65\x74"]);
        k0:
        goto WE;
        wf:
        if (!isset($k7["\x41\x75\164\150\157\x72\x69\x7a\141\x74\x69\157\156"])) {
            goto ZT;
        }
        unset($k7["\101\165\x74\150\x6f\162\x69\x7a\141\164\151\157\x6e"]);
        ZT:
        WE:
        MO_Oauth_Debug::mo_oauth_log("\x54\x6f\153\x65\156\40\145\156\x64\x70\157\x69\156\x74\x20\x55\x52\114\40\x3d\76\40" . $vy);
        $uo = apply_filters("\155\157\x5f\157\x61\x75\164\150\x5f\160\x6f\154\141\x72\137\x62\157\144\x79\137\x61\x72\x67\165\x6d\145\x6e\x74\163", $uo);
        MO_Oauth_Debug::mo_oauth_log("\142\157\144\x79\40\75\76");
        MO_Oauth_Debug::mo_oauth_log($uo);
        MO_Oauth_Debug::mo_oauth_log("\150\x65\x61\x64\x65\x72\x73\x20\x3d\x3e");
        MO_Oauth_Debug::mo_oauth_log($k7);
        $uh = wp_remote_post($vy, array("\x6d\145\164\150\157\144" => "\120\x4f\123\x54", "\164\x69\155\145\157\165\164" => 45, "\x72\145\x64\151\162\145\143\x74\151\157\156" => 5, "\150\x74\x74\160\x76\x65\162\x73\x69\x6f\x6e" => "\x31\56\x30", "\x62\x6c\x6f\143\x6b\151\156\147" => true, "\150\x65\141\144\145\x72\163" => $k7, "\142\x6f\144\171" => $uo, "\143\x6f\157\x6b\x69\x65\163" => array(), "\163\163\154\x76\145\162\x69\146\x79" => false));
        if (!is_wp_error($uh)) {
            goto wz;
        }
        $Yh->handle_error($uh->get_error_message());
        MO_Oauth_Debug::mo_oauth_log("\x45\x72\162\x6f\162\40\146\162\157\155\x20\124\x6f\153\145\156\40\x45\156\144\x70\157\151\156\164\72\40" . $uh->get_error_message());
        wp_die(wp_kses($uh->get_error_message(), \mo_oauth_get_valid_html()));
        exit;
        wz:
        $uh = $uh["\142\157\144\x79"];
        if (is_array(json_decode($uh, true))) {
            goto Oh;
        }
        $Yh->handle_error("\x49\x6e\166\141\154\x69\x64\x20\x72\x65\163\160\157\156\x73\x65\x20\x72\x65\143\x65\151\x76\145\144\x20\x3a\40" . $uh);
        echo "\74\x73\164\162\157\x6e\x67\76\122\x65\x73\x70\157\x6e\163\x65\40\72\40\74\57\163\164\x72\x6f\156\x67\x3e\x3c\142\x72\76";
        print_r($uh);
        echo "\x3c\142\x72\76\x3c\142\162\76";
        MO_Oauth_Debug::mo_oauth_log("\105\162\x72\x6f\162\x20\146\162\157\x6d\x20\124\157\x6b\145\156\x20\105\156\144\160\157\x69\156\164\x3d\x3e\x20\x49\x6e\166\141\x6c\x69\144\40\122\x65\163\160\x6f\x6e\x73\x65\x20\162\x65\143\145\151\166\145\144\56" . $uh);
        exit("\x49\x6e\x76\x61\x6c\x69\x64\40\x72\145\x73\160\157\x6e\163\145\40\162\145\143\x65\151\166\145\x64\56");
        Oh:
        $OY = json_decode($uh, true);
        if (isset($OY["\145\x72\162\157\x72\x5f\x64\x65\163\143\162\151\x70\x74\151\x6f\x6e"])) {
            goto GP;
        }
        if (isset($OY["\x65\x72\x72\x6f\x72"])) {
            goto us;
        }
        goto X8;
        GP:
        do_action("\x6d\x6f\137\x72\145\144\x69\x72\x65\143\x74\137\x74\x6f\137\143\165\x73\x74\157\155\x5f\145\x72\162\x6f\162\137\160\x61\x67\x65");
        if (!($uo["\147\162\141\x6e\164\137\x74\x79\160\145"] == "\x70\141\x73\163\x77\x6f\162\144" && $lF)) {
            goto NS;
        }
        $N5->add("\x6d\x6f\137\157\x61\x75\x74\x68\137\x69\144\x70\137\145\162\x72\157\162", __("\x3c\163\164\x72\157\156\x67\76\x45\122\x52\117\x52\74\57\x73\x74\162\157\x6e\147\76\72\40" . $OY["\x65\162\162\x6f\x72\137\144\145\x73\143\162\x69\160\164\151\x6f\x6e"]));
        MO_Oauth_Debug::mo_oauth_log("\105\x72\x72\157\x72\40\146\162\157\155\40\x49\x44\x50\x20\x3d\76\40");
        MO_Oauth_Debug::mo_oauth_log($N5);
        return $N5;
        NS:
        $Yh->handle_error($OY["\x65\162\x72\157\x72\x5f\x64\x65\163\143\162\x69\160\x74\x69\157\x6e"]);
        $this->handle_error(json_encode($OY["\x65\162\x72\157\x72\137\x64\145\163\143\162\151\160\164\x69\157\156"]), $uo);
        return;
        goto X8;
        us:
        do_action("\x6d\157\x5f\162\x65\x64\151\x72\145\x63\164\137\x74\157\137\143\165\x73\164\x6f\155\137\145\162\162\x6f\x72\137\x70\141\147\x65");
        if (!($uo["\147\x72\x61\x6e\164\137\164\x79\x70\145"] == "\160\141\x73\163\x77\157\x72\144" && $lF)) {
            goto zp;
        }
        $N5->add("\155\157\x5f\157\141\x75\164\x68\x5f\151\144\x70\x5f\145\x72\162\157\162", __("\x3c\163\x74\162\157\x6e\147\76\105\122\x52\117\x52\74\x2f\163\x74\162\x6f\156\147\x3e\x3a\x20" . $OY["\x65\162\x72\x6f\162"]));
        return $N5;
        zp:
        $Yh->handle_error($OY["\145\162\162\157\162"]);
        $this->handle_error(json_encode($OY["\145\162\162\x6f\162"]), $uo);
        return;
        X8:
        return $uh;
    }
    public function get_atoken($vy, $uo, $g0, $U_ = true, $fn = false)
    {
        global $Yh;
        foreach ($uo as $cW => $LQ) {
            $uo[$cW] = html_entity_decode($LQ);
            hF:
        }
        nE:
        $MC = '';
        if (!isset($uo["\x63\x6c\x69\x65\156\x74\137\x73\x65\x63\x72\x65\164"])) {
            goto hp;
        }
        $MC = $uo["\x63\x6c\151\145\x6e\164\x5f\163\145\143\162\x65\164"];
        hp:
        $z6 = $uo["\143\x6c\x69\x65\156\x74\137\x69\144"];
        $k7 = array("\101\143\143\145\x70\164" => "\x61\160\160\154\151\x63\x61\x74\x69\157\x6e\x2f\x6a\163\x6f\x6e", "\x63\x68\141\162\x73\x65\164" => "\x55\124\106\40\x2d\x20\x38", "\x43\157\156\164\x65\156\164\x2d\124\171\x70\x65" => "\x61\x70\160\154\151\143\141\164\151\x6f\156\57\x78\x2d\167\167\x77\55\x66\x6f\x72\x6d\x2d\165\162\154\x65\x6e\143\x6f\144\x65\x64", "\101\165\164\x68\x6f\x72\x69\x7a\141\x74\151\157\156" => "\x42\x61\163\151\x63\40" . base64_encode($z6 . "\x3a" . $MC));
        $k7 = apply_filters("\x6d\157\137\x6f\141\165\x74\150\x5f\143\157\x75\x73\x74\157\155\x5f\x65\x78\x74\x65\x6e\144\137\164\x6f\x6b\x65\x6e\x65\x6e\x64\x70\x6f\151\156\x74\137\x70\141\x72\x61\x6d\x73", $k7);
        if (!isset($uo["\x63\x6f\x64\145\x5f\x76\145\x72\x69\x66\x69\145\162"])) {
            goto Mn;
        }
        unset($k7["\101\x75\164\x68\157\162\151\172\x61\164\151\x6f\156"]);
        Mn:
        if (1 === $U_ && 0 === $fn) {
            goto nJ;
        }
        if (0 === $U_ && 1 === $fn) {
            goto xP;
        }
        goto Cf;
        nJ:
        unset($uo["\x63\154\151\x65\156\164\x5f\151\x64"]);
        if (!isset($uo["\143\x6c\151\x65\156\x74\137\x73\x65\x63\x72\145\x74"])) {
            goto z1;
        }
        unset($uo["\143\154\151\145\x6e\x74\137\x73\145\x63\x72\x65\164"]);
        z1:
        goto Cf;
        xP:
        if (!isset($k7["\x41\x75\164\150\157\162\151\x7a\x61\x74\x69\157\156"])) {
            goto q0;
        }
        unset($k7["\x41\x75\164\x68\157\162\x69\x7a\x61\x74\151\157\156"]);
        q0:
        Cf:
        $Cv = curl_init($vy);
        curl_setopt($Cv, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($Cv, CURLOPT_ENCODING, '');
        curl_setopt($Cv, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($Cv, CURLOPT_AUTOREFERER, true);
        curl_setopt($Cv, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($Cv, CURLOPT_MAXREDIRS, 10);
        curl_setopt($Cv, CURLOPT_POST, true);
        curl_setopt($Cv, CURLOPT_HTTPHEADER, array("\x41\x75\164\150\x6f\x72\x69\x7a\x61\x74\151\x6f\156\x3a\x20\102\141\163\151\x63\40" . base64_encode($z6 . "\x3a" . $MC), "\x41\143\x63\145\160\164\72\x20\141\160\x70\x6c\151\x63\x61\164\x69\157\x6e\x2f\152\x73\x6f\156"));
        curl_setopt($Cv, CURLOPT_POSTFIELDS, "\162\145\144\x69\x72\x65\143\x74\137\x75\162\151\75" . $uo["\x72\x65\x64\x69\x72\x65\x63\x74\x5f\165\x72\x69"] . "\46\147\x72\x61\156\x74\137\x74\171\160\x65\x3d" . "\141\x75\x74\150\x6f\162\151\172\x61\x74\x69\x6f\x6e\137\143\157\144\x65" . "\46\143\x6c\151\x65\x6e\x74\x5f\x69\144\75" . $z6 . "\46\143\x6c\x69\x65\x6e\164\x5f\163\145\143\x72\145\x74\75" . $MC . "\46\x63\157\x64\x65\75" . $g0);
        $OY = curl_exec($Cv);
        if (!curl_error($Cv)) {
            goto al;
        }
        echo "\x3c\142\76\122\x65\163\x70\157\156\163\x65\x20\72\40\x3c\x2f\x62\76\x3c\142\162\x3e";
        print_r($OY);
        echo "\x3c\x62\x72\76\74\142\x72\x3e";
        MO_Oauth_Debug::mo_oauth_log(curl_error($Cv));
        exit(curl_error($Cv));
        al:
        if (isset($OY["\x65\162\x72\x6f\162\137\144\x65\x73\143\162\x69\160\164\x69\157\x6e"])) {
            goto DL;
        }
        if (isset($OY["\x65\162\162\157\162"])) {
            goto An;
        }
        if (!isset($OY["\x61\143\143\145\163\x73\137\164\157\153\145\156"])) {
            goto tC;
        }
        $C2 = $OY["\x61\x63\x63\145\x73\x73\x5f\164\x6f\153\145\156"];
        tC:
        goto VM;
        An:
        $Go = "\x45\x72\162\157\x72\40\x66\162\157\x6d\40\x54\x6f\x6b\x65\x6e\40\x45\156\144\160\x6f\x69\x6e\x74\x3a\40" . $OY["\x65\162\162\x6f\x72"];
        MO_Oauth_Debug::mo_oauth_log($Go);
        do_action("\x6d\x6f\137\162\x65\x64\151\162\145\143\164\137\164\157\x5f\x63\x75\x73\164\x6f\155\137\145\162\162\x6f\162\x5f\160\141\x67\x65");
        exit($OY["\x65\162\x72\x6f\162\x5f\x64\x65\x73\143\x72\x69\160\x74\151\157\156"]);
        VM:
        goto FE;
        DL:
        $Go = "\105\162\162\157\x72\x20\146\162\x6f\155\x20\124\x6f\153\x65\x6e\x20\x45\x6e\x64\160\157\151\x6e\164\72\x20" . $OY["\x65\162\x72\157\162\x5f\144\x65\x73\143\x72\x69\160\x74\151\157\156"];
        MO_Oauth_Debug::mo_oauth_log($Go);
        do_action("\x6d\157\x5f\162\145\x64\151\x72\145\x63\164\137\164\157\137\x63\165\x73\164\x6f\x6d\x5f\x65\162\162\157\162\137\160\141\x67\145");
        exit($OY["\145\x72\x72\157\162\x5f\144\145\163\143\162\151\x70\x74\x69\x6f\156"]);
        FE:
        return $OY;
    }
    public function get_access_token($vy, $uo, $U_, $fn)
    {
        global $Yh;
        $uh = $this->get_token($vy, $uo, $U_, $fn);
        if (!is_wp_error($uh)) {
            goto um;
        }
        return $uh;
        um:
        $OY = json_decode($uh, true);
        if (!("\x70\x61\163\x73\167\x6f\x72\144" === $uo["\147\162\x61\x6e\164\x5f\164\171\x70\x65"])) {
            goto D1;
        }
        return $OY;
        D1:
        if (isset($OY["\x61\143\x63\145\x73\x73\137\164\157\153\145\156"])) {
            goto TD;
        }
        $N5 = "\111\x6e\166\x61\x6c\151\144\40\162\145\163\x70\x6f\x6e\163\145\40\162\145\x63\145\x69\x76\x65\144\40\146\x72\157\x6d\40\117\101\165\x74\150\x20\x50\x72\x6f\166\151\144\145\x72\56\40\103\x6f\156\x74\141\x63\x74\40\171\157\x75\x72\40\141\x64\x6d\151\x6e\151\163\x74\162\141\164\157\x72\40\x66\157\162\40\155\157\162\x65\40\144\145\164\x61\x69\154\x73\x2e\74\x62\162\x3e\x3c\142\162\76\74\x73\164\x72\157\156\x67\x3e\x52\145\x73\160\x6f\156\x73\145\40\72\x20\74\x2f\x73\164\x72\x6f\156\x67\x3e\x3c\x62\x72\x3e" . $uh;
        $Yh->handle_error($N5);
        MO_Oauth_Debug::mo_oauth_log("\105\162\x72\x6f\x72\40\167\150\x69\154\x65\x20\146\x65\x74\143\x68\151\156\x67\40\x74\157\153\x65\156\72\40" . $N5);
        echo esc_attr($N5);
        exit;
        goto eY;
        TD:
        return $OY["\141\143\143\145\x73\163\137\164\157\153\x65\x6e"];
        eY:
    }
    public function get_id_token($vy, $uo, $U_, $fn)
    {
        global $Yh;
        $uh = $this->get_token($vy, $uo, $U_, $fn);
        $OY = json_decode($uh, true);
        if (isset($OY["\151\x64\x5f\x74\157\153\x65\156"])) {
            goto pD;
        }
        $N5 = "\x49\x6e\166\141\x6c\x69\x64\40\x72\x65\163\160\x6f\156\x73\145\x20\162\145\x63\145\x69\x76\145\x64\x20\146\162\x6f\155\40\x4f\x70\145\x6e\111\x64\x20\x50\162\x6f\166\x69\x64\145\162\56\x20\x43\x6f\156\164\x61\x63\164\40\171\157\165\x72\x20\x61\x64\x6d\x69\x6e\x69\163\164\x72\141\164\157\162\40\x66\157\162\x20\155\157\x72\145\x20\x64\x65\164\x61\151\154\163\56\74\x62\162\76\74\x62\x72\x3e\74\163\164\162\157\x6e\147\x3e\122\x65\163\160\157\x6e\x73\x65\x20\72\x20\74\57\163\164\162\157\x6e\147\x3e\x3c\x62\162\x3e" . $uh;
        $Yh->handle_error($N5);
        MO_Oauth_Debug::mo_oauth_log("\x45\162\x72\157\x72\x20\167\150\x69\x6c\145\x20\x66\145\x74\x63\150\151\156\x67\x20\x69\144\x5f\x74\157\x6b\145\x6e\72\x20" . $N5);
        echo esc_attr($N5);
        exit;
        goto tV;
        pD:
        return $OY;
        tV:
    }
    public function get_resource_owner_from_id_token($A2)
    {
        global $Yh;
        $Lh = explode("\56", $A2);
        if (!isset($Lh[1])) {
            goto cJ;
        }
        $ar = $Yh->base64url_decode($Lh[1]);
        if (!is_array(json_decode($ar, true))) {
            goto ce;
        }
        return json_decode($ar, true);
        ce:
        cJ:
        $N5 = "\x49\156\x76\x61\x6c\x69\x64\x20\x72\x65\163\x70\157\156\163\x65\40\162\x65\143\x65\151\166\145\x64\x2e\x3c\x62\162\x3e\74\x73\164\162\157\x6e\x67\x3e\x69\x64\x5f\164\x6f\x6b\145\x6e\40\x3a\40\x3c\57\163\164\162\157\x6e\x67\76" . $A2;
        $Yh->handle_error($N5);
        MO_Oauth_Debug::mo_oauth_log("\x45\162\162\x6f\162\x20\167\x68\151\x6c\x65\x20\146\145\164\143\x68\151\156\x67\x20\162\145\x73\157\165\162\x63\145\40\157\167\x6e\x65\x72\x20\x66\162\157\x6d\x20\151\144\x20\x74\157\153\145\156\72" . $N5);
        echo esc_attr($N5);
        exit;
    }
    public function get_resource_owner($pU, $C2)
    {
        global $Yh;
        $k7 = array();
        $k7["\x41\165\164\x68\157\162\151\172\141\164\151\157\156"] = "\x42\145\x61\162\x65\162\40" . $C2;
        $k7 = apply_filters("\x6d\x6f\x5f\x65\x78\x74\145\156\x64\137\165\x73\x65\x72\151\156\x66\157\x5f\160\x61\x72\141\155\163", $k7, $pU);
        MO_Oauth_Debug::mo_oauth_log("\x52\x65\163\x6f\165\x72\x63\145\x20\x4f\167\156\x65\x72\40\x45\156\144\160\157\x69\x6e\x74\x20\75\x3e\x20" . $pU);
        MO_Oauth_Debug::mo_oauth_log("\122\x65\x73\x6f\165\x72\143\145\x20\117\x77\156\145\x72\x20\x72\x65\161\x75\145\163\164\x20\143\157\156\164\145\x6e\164\40\x3d\76\40");
        MO_Oauth_Debug::mo_oauth_log("\150\x65\141\x64\x65\162\x73\x20\75\x3e");
        MO_Oauth_Debug::mo_oauth_log($k7);
        $pU = apply_filters("\155\157\x5f\x6f\141\x75\x74\x68\137\165\x73\x65\x72\151\156\146\157\x5f\x69\156\164\x65\162\x6e\141\154", $pU);
        $uh = wp_remote_post($pU, array("\x6d\145\164\150\157\x64" => "\x47\105\x54", "\164\x69\155\145\x6f\165\164" => 45, "\162\x65\144\151\x72\x65\x63\x74\151\157\x6e" => 5, "\150\164\164\x70\166\145\x72\x73\x69\157\156" => "\x31\x2e\60", "\x62\x6c\157\x63\x6b\151\x6e\147" => true, "\150\145\x61\144\x65\162\x73" => $k7, "\143\157\x6f\153\x69\x65\163" => array(), "\163\x73\154\166\x65\x72\151\x66\x79" => false));
        if (!is_wp_error($uh)) {
            goto Eh;
        }
        $Yh->handle_error($uh->get_error_message());
        MO_Oauth_Debug::mo_oauth_log("\105\162\x72\x6f\162\40\x66\162\157\155\x20\122\x65\163\157\x75\x72\x63\145\40\x45\x6e\144\x70\x6f\151\156\x74\x3a\x20" . $uh->get_error_message());
        wp_die(wp_kses($uh->get_error_message(), \mo_oauth_get_valid_html()));
        exit;
        Eh:
        $uh = $uh["\142\157\144\171"];
        if (is_array(json_decode($uh, true))) {
            goto Nd;
        }
        $Yh->handle_error("\111\x6e\166\x61\x6c\151\x64\40\x72\x65\x73\160\x6f\156\x73\145\40\x72\x65\x63\x65\151\x76\x65\x64\40\72\x20" . $uh);
        echo "\x3c\x73\x74\162\x6f\156\147\x3e\x52\x65\163\160\157\156\x73\145\x20\x3a\x20\x3c\x2f\163\x74\162\157\156\x67\76\74\x62\162\76";
        print_r($uh);
        echo "\x3c\142\162\x3e\x3c\142\x72\x3e";
        MO_Oauth_Debug::mo_oauth_log("\x49\156\x76\141\154\x69\144\x20\162\x65\x73\160\157\156\x73\x65\40\x72\x65\143\151\x65\x76\145\x64\x20\x77\150\151\x6c\145\x20\146\145\x74\143\x68\x69\x6e\x67\40\x72\x65\x73\x6f\165\162\143\x65\40\157\x77\156\145\162\40\x64\145\x74\x61\x69\154\163");
        exit("\111\156\x76\141\x6c\151\x64\40\x72\145\x73\160\x6f\x6e\x73\x65\x20\162\x65\x63\x65\x69\166\x65\x64\x2e");
        Nd:
        $OY = json_decode($uh, true);
        if (!(strpos($pU, "\141\160\151\x2e\x63\x6c\145\x76\145\162\x2e\143\x6f\155") != false && isset($OY["\x6c\x69\156\x6b\163"][1]["\x75\162\x69"]) && strpos($pU, $OY["\x6c\x69\x6e\x6b\x73"][1]["\x75\162\x69"]) === false)) {
            goto Ud;
        }
        $GV = $OY["\x6c\151\x6e\x6b\x73"][1]["\x75\162\151"];
        $az = "\150\x74\x74\x70\x73\72\57\57\x61\160\x69\x2e\143\154\x65\x76\x65\162\x2e\x63\x6f\155" . $GV;
        $Yh->mo_oauth_client_update_option("\155\x6f\x5f\x6f\141\x75\x74\x68\137\143\x6c\x69\145\156\x74\137\x63\154\145\x76\145\162\137\165\163\145\x72\137\x61\x70\x69", $az);
        $OY = $this->get_resource_owner($az, $C2);
        Ud:
        if (isset($OY["\x65\162\x72\x6f\x72\137\144\145\163\x63\x72\151\x70\x74\x69\157\x6e"])) {
            goto XQ;
        }
        if (isset($OY["\145\x72\x72\x6f\162"])) {
            goto fj;
        }
        goto jw;
        XQ:
        $Go = "\105\162\162\x6f\162\40\146\162\157\x6d\40\x52\145\163\x6f\x75\162\143\x65\x20\x45\156\144\x70\x6f\151\x6e\164\x3a\40" . $OY["\145\x72\x72\157\x72\137\144\145\x73\143\162\151\160\164\151\157\156"];
        $Yh->handle_error($OY["\x65\162\x72\x6f\x72\137\144\145\163\x63\x72\151\160\x74\151\x6f\156"]);
        MO_Oauth_Debug::mo_oauth_log($Go);
        do_action("\x6d\157\137\x72\145\144\151\162\x65\x63\164\x5f\x74\x6f\137\x63\x75\x73\x74\157\155\137\x65\162\162\x6f\x72\137\x70\141\147\x65");
        exit(json_encode($OY["\145\162\162\x6f\x72\x5f\x64\145\x73\143\162\x69\160\x74\151\x6f\x6e"]));
        goto jw;
        fj:
        $Go = "\105\162\162\157\162\40\x66\162\157\x6d\x20\122\x65\163\x6f\x75\162\x63\145\x20\x45\x6e\144\x70\157\x69\x6e\164\72\40" . $OY["\145\x72\162\157\162"];
        $Yh->handle_error($OY["\145\x72\162\x6f\162"]);
        MO_Oauth_Debug::mo_oauth_log($Go);
        do_action("\x6d\157\x5f\x72\x65\x64\x69\x72\145\x63\x74\x5f\164\157\137\143\165\163\x74\x6f\155\137\145\162\162\x6f\162\x5f\160\x61\147\145");
        exit(json_encode($OY["\x65\x72\162\x6f\162"]));
        jw:
        return $OY;
    }
    public function get_response($Ws)
    {
        $uh = wp_remote_get($Ws, array("\155\x65\x74\x68\157\144" => "\107\105\x54", "\x74\x69\155\145\x6f\165\164" => 45, "\x72\x65\x64\x69\x72\145\143\x74\151\x6f\156" => 5, "\x68\x74\164\x70\166\145\x72\x73\x69\x6f\156" => 1.0, "\x62\154\157\143\153\x69\156\147" => true, "\x68\x65\141\144\x65\162\163" => array(), "\143\x6f\157\x6b\x69\x65\x73" => array(), "\163\x73\154\166\x65\162\151\x66\171" => false));
        if (!is_wp_error($uh)) {
            goto cj;
        }
        MO_Oauth_Debug::mo_oauth_log($uh->get_error_message());
        wp_die(wp_kses($uh->get_error_message(), \mo_oauth_get_valid_html()));
        exit;
        cj:
        $uh = $uh["\x62\157\x64\171"];
        $OY = json_decode($uh, true);
        if (isset($OY["\x65\x72\x72\x6f\x72\137\144\x65\163\x63\x72\x69\160\x74\x69\157\156"])) {
            goto uj;
        }
        if (isset($OY["\x65\x72\x72\157\162"])) {
            goto MC;
        }
        goto DP;
        uj:
        $Yh->handle_error($OY["\145\162\x72\157\162\x5f\x64\x65\163\x63\162\151\160\164\x69\157\156"]);
        MO_Oauth_Debug::mo_oauth_log($Go);
        do_action("\x6d\x6f\137\162\x65\144\151\x72\x65\x63\164\x5f\x74\157\x5f\x63\165\x73\x74\157\x6d\137\x65\x72\162\x6f\162\137\x70\141\x67\145");
        goto DP;
        MC:
        $Yh->handle_error($OY["\145\162\x72\157\162"]);
        MO_Oauth_Debug::mo_oauth_log($Go);
        do_action("\x6d\x6f\137\x72\145\144\151\x72\x65\x63\x74\137\164\x6f\x5f\x63\165\163\164\157\155\137\145\162\162\x6f\162\137\x70\x61\x67\x65");
        DP:
        return $OY;
    }
    private function handle_error($N5, $uo)
    {
        global $Yh;
        if (!($uo["\147\162\141\156\164\137\x74\171\160\x65"] === "\160\141\x73\x73\x77\x6f\x72\144")) {
            goto Gy;
        }
        $EJ = $Yh->get_current_url();
        $EJ = apply_filters("\155\157\x5f\157\141\165\x74\150\x5f\167\x6f\x6f\x63\x6f\155\x6d\145\162\x63\x65\137\143\x68\145\143\153\157\165\x74\x5f\143\157\155\x70\141\x74\x69\x62\151\154\x69\x74\x79", $EJ);
        if ($EJ != '') {
            goto Rr;
        }
        return;
        goto BH;
        Rr:
        $EJ = "\77\x6f\x70\x74\x69\157\x6e\x3d\x65\162\x72\157\x72\155\141\x6e\141\147\x65\x72\46\145\x72\x72\157\x72\x3d" . \base64_encode($N5);
        MO_Oauth_Debug::mo_oauth_log("\105\x72\162\157\162\x3a\x20" . $N5);
        wp_die($N5);
        exit;
        BH:
        Gy:
        MO_Oauth_Debug::mo_oauth_log("\105\162\162\x6f\162\72\40" . $N5);
        exit($N5);
    }
}
