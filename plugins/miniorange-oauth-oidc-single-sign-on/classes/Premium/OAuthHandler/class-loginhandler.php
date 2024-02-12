<?php


namespace MoOauthClient\Premium;

use MoOauthClient\Standard\LoginHandler as StandardLoginHandler;
use MoOauthClient\GrantTypes\Implicit;
use MoOauthClient\GrantTypes\Password;
use MoOauthClient\GrantTypes\JWSVerify;
use MoOauthClient\GrantTypes\JWTUtils;
use MoOauthClient\Premium\MappingHandler;
use MoOauthClient\StorageManager;
use MoOauthClient\MO_Oauth_Debug;
class LoginHandler extends StandardLoginHandler
{
    private $implicit_handler;
    private $app_name = '';
    private $group_mapping_attr = false;
    private $resource_owner = false;
    public function __construct()
    {
        global $Yh;
        parent::__construct();
        add_filter("\x6d\x6f\137\141\x75\x74\x68\137\165\x72\x6c\x5f\x69\x6e\164\x65\x72\156\141\154", array($this, "\155\157\x5f\157\x61\165\x74\x68\x5f\143\x6c\x69\145\x6e\164\x5f\x67\145\x6e\x65\x72\141\x74\x65\x5f\141\x75\x74\150\x6f\162\x69\x7a\x61\164\x69\157\156\x5f\165\x72\154"), 5, 2);
        add_action("\167\x70\x5f\x66\x6f\157\x74\x65\162", array($this, "\155\157\x5f\x6f\x61\x75\164\150\137\x63\x6c\151\145\x6e\x74\137\151\x6d\x70\154\151\143\151\x74\x5f\146\162\141\x67\x6d\x65\x6e\164\x5f\150\x61\x6e\x64\x6c\x65\162"));
        add_action("\155\x6f\x5f\157\141\165\x74\150\137\162\x65\163\x74\x72\x69\143\164\x5f\145\155\141\x69\x6c\163", array($this, "\x6d\157\x5f\x6f\x61\x75\164\150\137\143\154\x69\145\x6e\x74\x5f\162\x65\x73\x74\162\x69\143\164\x5f\145\155\x61\x69\154\163"), 10, 2);
        add_action("\x6d\x6f\137\157\141\165\164\150\137\143\x6c\x69\145\x6e\164\x5f\155\141\x70\137\x72\157\154\145\163", array($this, "\x6d\x6f\x5f\x6f\141\165\x74\150\x5f\x63\154\x69\145\x6e\164\137\155\x61\x70\137\162\x6f\154\x65\163"), 10, 1);
        $F6 = $Yh->mo_oauth_client_get_option("\x6d\x6f\x5f\x6f\141\165\x74\x68\137\145\x6e\x61\x62\154\x65\x5f\157\x61\x75\x74\x68\x5f\x77\x70\137\x6c\x6f\147\x69\156");
        if (!$F6) {
            goto vuB;
        }
        remove_filter("\x61\165\x74\150\145\156\x74\151\143\141\164\145", "\167\160\137\x61\x75\x74\x68\145\x6e\164\151\143\141\164\145\x5f\165\163\145\x72\x6e\x61\x6d\x65\x5f\x70\x61\x73\x73\167\157\162\x64", 20, 3);
        $RG = new Password(true);
        add_filter("\x61\165\164\150\145\x6e\164\151\143\x61\164\x65", array($RG, "\155\x6f\137\157\141\165\x74\150\x5f\x77\x70\137\x6c\157\147\x69\156"), 20, 3);
        vuB:
    }
    public function mo_oauth_client_restrict_emails($Mv, $Wb)
    {
        global $Yh;
        $tI = isset($Wb["\162\145\163\164\162\x69\143\164\x65\x64\x5f\144\157\x6d\141\151\x6e\163"]) ? $Wb["\x72\x65\163\164\x72\x69\143\164\x65\144\137\x64\157\155\141\x69\x6e\163"] : '';
        if (!empty($tI)) {
            goto Ytv;
        }
        return;
        Ytv:
        MO_Oauth_Debug::mo_oauth_log("\x52\145\163\164\x72\151\x63\164\145\x64\40\x64\157\155\x61\x69\x6e\x73\40\141\x72\x65\40\x3d\x3e" . $tI);
        $pi = isset($Wb["\141\x6c\154\157\x77\x5f\162\x65\x73\x74\x72\x69\x63\x74\145\x64\x5f\144\x6f\x6d\141\x69\x6e\163"]) ? $Wb["\x61\154\154\157\x77\137\x72\x65\163\x74\162\151\143\164\x65\144\137\x64\x6f\155\141\x69\156\x73"] : '';
        if (!empty($pi)) {
            goto e4A;
        }
        $pi = false;
        e4A:
        if (!($pi !== false)) {
            goto xXx;
        }
        MO_Oauth_Debug::mo_oauth_log("\x4f\x6e\154\171\40\x72\x65\163\x74\162\x69\143\164\145\x64\40\x64\x6f\155\141\x69\156\x73\x20\141\162\x65\x20\x61\154\x6c\157\167\x65\x64\56");
        xXx:
        $pi = intval($pi);
        $tI = array_map("\164\x72\151\x6d", explode("\54", strtolower($tI)));
        $SO = strtolower(substr($Mv, strpos($Mv, "\100") + 1));
        $nb = in_array($SO, $tI, false);
        $nb = $pi ? !$nb : $nb;
        $Qv = !empty($tI) && $nb;
        if (!$Qv) {
            goto HE8;
        }
        $N5 = "\x59\x6f\x75\162\x20\145\155\141\151\154\40\144\157\155\x61\x69\x6e\40\x69\x73\x20\x72\145\x73\164\162\x69\x63\x74\x65\144\x20\x74\x6f\x20\141\x63\x63\x65\163\x73\x20\164\x68\145\40\x77\145\142\163\x69\x74\x65";
        $Yh->handle_error($N5);
        MO_Oauth_Debug::mo_oauth_log("\105\x6d\141\151\x6c\40\x64\157\x6d\x61\151\x6e\x20\151\163\40\x72\x65\x73\164\x72\x69\x63\x74\x65\144\x2c\x20\160\154\x65\141\163\x65\x20\143\x6f\x6e\x74\141\143\164\40\141\144\x6d\151\x6e\x2e");
        wp_die($N5);
        HE8:
    }
    public function mo_oauth_client_generate_authorization_url($sx, $zl)
    {
        global $Yh;
        $jt = $Yh->parse_url($sx);
        $Wb = $Yh->get_app_by_name($zl)->get_app_config();
        $YV = md5(rand());
        setcookie("\x6d\157\137\x6f\141\x75\x74\x68\x5f\x6e\157\156\143\x65", $YV, time() + 120, "\57", null, true, true);
        if (isset($Wb["\x67\162\x61\156\x74\137\164\171\x70\x65"]) && "\111\x6d\x70\154\x69\x63\151\x74\40\107\x72\x61\156\x74" === $Wb["\147\162\141\156\164\x5f\164\171\x70\145"]) {
            goto ceg;
        }
        if (!(isset($Wb["\x67\x72\141\156\164\x5f\x74\171\160\145"]) && "\110\171\142\162\151\144\40\107\162\x61\156\164" === $Wb["\147\x72\141\x6e\x74\x5f\164\x79\160\x65"])) {
            goto q6q;
        }
        MO_Oauth_Debug::mo_oauth_log("\107\162\x61\156\x74\72\x20\110\x79\x62\x72\151\x64\40\107\x72\141\156\x74");
        $gb = isset($Wb["\155\157\137\157\141\x75\x74\150\x5f\x72\x65\x73\160\x6f\156\163\145\x5f\x74\x79\x70\x65"]) && !empty($Wb["\155\x6f\137\157\141\x75\x74\x68\x5f\162\x65\163\x70\x6f\156\x73\x65\x5f\164\171\160\145"]) ? $Wb["\x6d\157\137\157\x61\x75\164\150\137\x72\145\x73\160\157\156\x73\145\x5f\164\x79\160\145"] : "\x74\x6f\x6b\145\x6e\45\x32\60\151\144\x5f\164\x6f\x6b\x65\x6e\x25\x32\60\x63\x6f\x64\x65";
        $jt["\161\x75\x65\x72\171"]["\x72\145\163\x70\157\156\x73\145\x5f\x74\171\160\145"] = $gb;
        return $Yh->generate_url($jt);
        q6q:
        goto E9N;
        ceg:
        MO_Oauth_Debug::mo_oauth_log("\107\x72\141\156\164\72\x20\x49\155\160\x6c\151\x63\151\164\40\x47\x72\x61\x6e\x74");
        $jt["\x71\165\145\162\x79"]["\x6e\x6f\156\143\145"] = $YV;
        $jt["\x71\165\x65\162\x79"]["\x72\145\x73\x70\157\156\163\x65\137\x74\171\160\145"] = "\164\x6f\x6b\145\156";
        $gb = isset($Wb["\x6d\x6f\137\157\x61\x75\164\150\x5f\x72\145\163\x70\x6f\x6e\x73\145\x5f\164\171\x70\145"]) && !empty($Wb["\155\157\137\157\x61\165\164\150\x5f\162\x65\163\160\x6f\156\x73\x65\x5f\x74\x79\160\x65"]) ? $Wb["\155\157\137\157\141\x75\x74\x68\x5f\x72\x65\163\x70\157\x6e\x73\x65\x5f\164\x79\160\145"] : "\164\157\x6b\x65\x6e";
        $jt["\161\165\145\x72\x79"]["\162\145\163\160\x6f\x6e\163\145\x5f\x74\x79\160\x65"] = $gb;
        return $Yh->generate_url($jt);
        E9N:
        return $sx;
    }
    public function mo_oauth_client_map_roles($uo)
    {
        $KY = isset($uo["\x61\160\160\x5f\143\x6f\156\146\x69\x67"]) && !empty($uo["\x61\160\160\x5f\143\157\156\146\x69\x67"]) ? $uo["\x61\160\x70\137\x63\157\x6e\146\x69\x67"] : [];
        $cl = isset($KY["\x67\x72\x6f\x75\x70\156\141\155\145\x5f\x61\164\164\x72\x69\142\x75\x74\x65"]) && '' !== $KY["\x67\x72\157\165\160\x6e\141\155\145\x5f\x61\164\164\x72\151\x62\165\164\145"] ? $KY["\x67\x72\x6f\165\x70\156\x61\155\145\x5f\141\164\164\x72\x69\x62\165\164\x65"] : false;
        $R0 = isset($uo["\x6e\x65\x77\137\165\x73\x65\162"]) && !empty($uo["\156\x65\167\137\165\163\x65\162"]) ? $uo["\156\145\x77\x5f\x75\163\x65\x72"] : 0;
        global $Yh;
        $EL = false;
        if (isset($KY["\x65\156\x61\x62\154\145\x5f\162\x6f\x6c\x65\x5f\155\141\x70\x70\151\156\x67"])) {
            goto raw;
        }
        $KY["\145\x6e\x61\142\154\x65\137\162\x6f\x6c\x65\137\x6d\141\160\x70\x69\156\x67"] = true;
        $EL = true;
        raw:
        if (isset($KY["\137\x6d\x61\x70\160\151\156\147\137\x76\x61\154\165\145\137\144\x65\146\x61\165\154\164"])) {
            goto M5J;
        }
        $KY["\137\x6d\x61\160\x70\x69\156\x67\x5f\166\141\x6c\x75\x65\x5f\x64\145\x66\141\x75\154\x74"] = "\x73\x75\142\x73\143\x72\151\x62\145\x72";
        $EL = true;
        M5J:
        if (!boolval($EL)) {
            goto C8l;
        }
        if (!(isset($KY["\x63\x6c\151\x65\x6e\164\x5f\143\162\x65\144\x73\137\145\x6e\x63\162\x70\171\164\x65\144"]) && boolval($KY["\x63\154\x69\145\156\164\x5f\x63\162\145\x64\x73\137\x65\x6e\143\162\x70\171\164\x65\144"]))) {
            goto QTr;
        }
        $KY["\x63\x6c\x69\145\156\164\x5f\x69\144"] = $Yh->mooauthencrypt($KY["\x63\154\x69\x65\156\164\137\151\x64"]);
        $KY["\x63\154\x69\x65\x6e\164\x5f\x73\145\143\162\x65\x74"] = $Yh->mooauthencrypt($KY["\x63\x6c\151\145\156\x74\137\x73\145\143\x72\145\164"]);
        QTr:
        $Yh->set_app_by_name($uo["\141\160\x70\137\156\x61\155\145"], $KY);
        C8l:
        $this->resource_owner = isset($uo["\162\x65\x73\157\165\162\x63\x65\x5f\157\x77\156\145\162"]) && !empty($uo["\162\x65\163\x6f\x75\x72\143\x65\x5f\157\167\x6e\x65\x72"]) ? $uo["\x72\x65\163\157\x75\x72\143\145\x5f\157\x77\x6e\145\162"] : [];
        $Jc = array_map("\164\x72\x69\x6d", explode("\73", $cl));
        MO_Oauth_Debug::mo_oauth_log("\107\x72\157\x75\160\x20\155\141\160\160\x69\x6e\147\x20\141\162\x72\141\171\40\x3d\x3e\x20");
        MO_Oauth_Debug::mo_oauth_log($Jc);
        $EE = array();
        foreach ($Jc as $Cu) {
            $zC = $this->get_group_mapping_attribute($this->resource_owner, false, $Cu);
            if (!(!is_array($zC) && !empty($zC))) {
                goto o0h;
            }
            $oQ = json_decode($zC, true);
            $zC = is_array($oQ) ? $oQ : ($oQ === NULL ? array($zC) : array($oQ));
            o0h:
            array_push($EE, $zC);
            uE8:
        }
        xf4:
        $EE = array_filter($EE);
        $this->group_mapping_attr = call_user_func_array("\x61\x72\162\x61\171\137\155\x65\162\x67\x65", $EE);
        MO_Oauth_Debug::mo_oauth_log("\107\162\157\165\160\40\155\141\x70\x70\151\x6e\x67\x20\141\x74\164\x72\40\166\x61\154\165\145\x20\x3d\x3e\x20");
        MO_Oauth_Debug::mo_oauth_log($this->group_mapping_attr);
        $oG = new MappingHandler(isset($uo["\x75\163\145\x72\137\x69\x64"]) && is_numeric($uo["\165\163\145\162\137\151\144"]) ? intval($uo["\165\x73\x65\162\x5f\151\144"]) : 0, $KY, isset($this->group_mapping_attr) ? $this->group_mapping_attr : '', isset($uo["\156\x65\167\137\x75\163\x65\162"]) ? \boolval($uo["\156\x65\167\x5f\x75\x73\145\162"]) : true);
        $Wb = $uo["\x63\157\156\146\x69\x67"];
        if (!($R0 || (!isset($Wb["\x6b\x65\x65\x70\x5f\x65\x78\x69\x73\164\151\x6e\x67\x5f\165\x73\x65\x72\x73"]) || 1 !== intval($Wb["\x6b\145\145\160\137\x65\170\151\163\x74\x69\x6e\x67\137\x75\163\x65\162\163"])))) {
            goto c14;
        }
        $oG->apply_custom_attribute_mapping(is_array($this->resource_owner) ? $this->resource_owner : []);
        MO_Oauth_Debug::mo_oauth_log("\x63\165\163\164\157\155\x20\141\x74\x74\162\x69\142\x75\x74\x65\x20\x6d\x61\x70\160\x69\x6e\x67\x20\x64\x6f\x6e\145\56");
        c14:
        $Hv = false;
        $Hv = apply_filters("\x6d\x6f\x5f\x6f\x61\x75\164\x68\137\143\x6c\151\145\156\164\x5f\x75\160\x64\141\x74\145\137\x61\x64\155\x69\x6e\137\162\157\x6c\145", $Hv);
        if (!$Hv) {
            goto kVc;
        }
        MO_Oauth_Debug::mo_oauth_log("\x41\x64\155\x69\156\40\x52\x6f\x6c\x65\40\167\x69\x6c\x6c\40\142\145\x20\165\x70\144\x61\164\145\x64");
        kVc:
        if (!(user_can($uo["\165\x73\145\x72\x5f\x69\x64"], "\x61\x64\x6d\x69\156\x69\163\164\162\141\164\157\x72") && !$Hv)) {
            goto vsd;
        }
        MO_Oauth_Debug::mo_oauth_log("\101\144\x6d\151\x6e\40\122\157\154\x65\40\x63\141\156\40\156\x6f\x74\40\x62\x65\40\165\160\144\x61\164\145\x64");
        return;
        vsd:
        $oG->apply_role_mapping(is_array($this->resource_owner) ? $this->resource_owner : []);
    }
    public function mo_oauth_client_implicit_fragment_handler()
    {
        echo "\x9\11\x9\x3c\x73\143\162\x69\160\164\76\15\xa\x9\11\11\x9\146\x75\156\x63\x74\151\157\x6e\40\x63\x6f\x6e\166\145\x72\164\137\164\x6f\x5f\x75\x72\x6c\50\157\142\152\x29\40\x7b\15\xa\11\11\x9\11\x9\x72\145\x74\165\162\x6e\x20\117\142\152\x65\x63\164\xd\xa\11\11\x9\x9\x9\56\153\x65\171\163\x28\157\x62\152\51\15\12\11\11\x9\x9\x9\56\x6d\x61\x70\50\153\40\x3d\76\40\x60\x24\173\x65\x6e\143\157\144\145\125\122\x49\103\x6f\155\160\x6f\x6e\145\x6e\164\50\x6b\51\x7d\75\x24\173\x65\x6e\143\157\144\x65\125\122\111\x43\157\155\x70\157\156\x65\x6e\164\50\x6f\x62\x6a\133\x6b\135\x29\175\140\51\15\xa\11\11\x9\x9\11\x2e\152\157\151\x6e\50\47\46\47\x29\x3b\15\12\x9\11\x9\x9\175\15\xa\xd\xa\x9\11\x9\11\x66\165\x6e\143\164\151\157\156\40\x70\x61\x73\x73\137\164\x6f\x5f\x62\141\143\x6b\x65\x6e\x64\x28\x29\x20\x7b\15\xa\x9\11\11\x9\11\151\146\x28\167\x69\156\144\x6f\x77\x2e\x6c\x6f\143\141\164\151\157\156\x2e\x68\141\163\x68\51\40\173\xd\12\x9\11\x9\x9\x9\x9\x76\141\x72\x20\150\141\x73\x68\40\75\x20\167\x69\156\144\157\x77\56\154\x6f\x63\141\x74\151\157\x6e\x2e\150\141\163\150\x3b\15\12\11\11\11\11\11\11\x76\141\x72\40\x65\154\x65\x6d\145\156\164\x73\x20\x3d\40\173\x7d\73\xd\xa\x9\11\11\11\11\11\150\x61\163\x68\x2e\x73\160\154\x69\164\50\x22\43\42\51\133\x31\135\56\163\x70\154\151\164\x28\42\46\x22\51\x2e\146\157\x72\x45\x61\143\150\50\145\x6c\145\155\x65\x6e\x74\x20\x3d\x3e\40\x7b\xd\12\11\11\11\x9\11\x9\x9\x76\x61\162\40\x76\x61\162\x73\x20\75\40\145\x6c\145\x6d\145\156\164\56\x73\160\154\x69\x74\50\42\75\42\x29\73\xd\12\x9\x9\11\11\11\11\11\x65\x6c\145\155\x65\156\164\163\x5b\166\141\162\x73\x5b\x30\135\135\x20\75\40\x76\141\162\163\x5b\x31\135\x3b\15\12\x9\11\11\x9\x9\x9\x7d\51\73\15\12\x9\11\11\11\11\x9\151\x66\50\50\x22\x61\143\x63\145\x73\x73\x5f\164\x6f\x6b\145\x6e\42\40\151\x6e\40\x65\154\145\x6d\145\x6e\x74\163\x29\x20\174\x7c\x20\x28\x22\151\x64\137\164\x6f\x6b\145\x6e\x22\40\x69\x6e\x20\145\x6c\x65\x6d\145\156\x74\x73\x29\40\x7c\174\x20\x28\x22\164\157\x6b\145\x6e\42\x20\151\x6e\x20\x65\154\x65\155\145\x6e\164\163\51\51\40\173\xd\12\11\11\x9\11\x9\11\x9\151\146\50\x77\x69\156\144\x6f\x77\56\x6c\x6f\x63\141\164\151\x6f\156\x2e\150\x72\x65\x66\x2e\x69\156\x64\x65\x78\117\146\x28\42\77\42\x29\x20\41\75\75\x20\x2d\x31\51\x20\x7b\15\xa\x9\x9\11\11\11\x9\x9\11\167\x69\x6e\x64\157\x77\56\x6c\x6f\143\141\164\151\x6f\x6e\40\x3d\x20\50\167\151\156\x64\x6f\x77\56\x6c\157\143\141\x74\x69\157\x6e\56\150\x72\x65\146\x2e\x73\x70\x6c\x69\164\x28\42\77\42\x29\133\x30\x5d\40\53\x20\167\151\156\x64\x6f\167\x2e\x6c\x6f\143\141\x74\151\x6f\156\x2e\x68\141\x73\x68\x29\56\163\160\154\x69\x74\50\47\x23\47\51\x5b\x30\135\40\x2b\40\x22\77\42\40\x2b\x20\143\x6f\x6e\166\145\162\164\x5f\164\157\x5f\165\x72\154\x28\x65\154\x65\155\145\156\164\x73\51\x3b\15\xa\x9\x9\x9\11\x9\11\11\175\40\145\154\x73\145\x20\173\xd\12\11\x9\11\11\11\11\x9\11\167\151\x6e\144\157\x77\x2e\x6c\157\143\141\x74\x69\x6f\156\40\75\x20\x77\x69\x6e\144\157\x77\56\154\157\x63\x61\164\x69\x6f\156\56\150\x72\145\x66\x2e\x73\160\154\151\164\50\47\43\47\51\133\60\x5d\40\53\x20\x22\x3f\42\40\53\40\x63\157\156\166\145\x72\164\137\164\x6f\137\x75\162\x6c\x28\145\154\145\x6d\145\156\164\x73\51\73\15\xa\x9\11\x9\11\x9\x9\x9\175\xd\12\11\x9\11\x9\x9\11\175\15\12\x9\11\11\11\11\175\xd\xa\x9\11\11\x9\175\15\12\xd\xa\x9\x9\11\11\160\x61\x73\x73\137\x74\x6f\x5f\x62\141\x63\x6b\x65\156\144\x28\51\73\xd\xa\x9\x9\11\74\57\x73\x63\x72\x69\160\164\76\15\12\15\xa\11\x9";
    }
    private function check_state($wf)
    {
        global $Yh;
        $GP = str_replace("\45\x33\x64", "\x3d", urldecode($wf->get_query_param("\163\x74\x61\x74\145")));
        if (empty($GP) && isset($_COOKIE["\x73\x74\141\x74\x65\137\x70\141\x72\x61\x6d"])) {
            goto wbV;
        }
        if (isset($_GET["\163\x74\141\164\145"]) && !empty($_GET["\163\x74\141\x74\145"])) {
            goto XXZ;
        }
        goto s5r;
        wbV:
        $GP = sanitize_text_field(wp_unslash($_COOKIE["\x73\x74\x61\x74\145\137\x70\x61\162\x61\155"]));
        goto s5r;
        XXZ:
        $GP = sanitize_text_field(wp_unslash($_GET["\163\x74\x61\164\x65"]));
        s5r:
        $eC = new StorageManager($GP);
        $fZ = $eC->get_value("\141\160\x70\156\141\x6d\x65");
        $KY = $Yh->get_app_by_name($fZ)->get_app_config();
        $d9 = $KY["\x61\160\x70\x49\x64"];
        $F8 = $Yh->get_app_by_name($d9);
        if (!is_wp_error($eC)) {
            goto dTM;
        }
        $Yh->handle_error($eC->get_error_message());
        wp_die(wp_kses($eC->get_error_message(), \mo_oauth_get_valid_html()));
        dTM:
        $OE = $eC->get_value("\165\x69\x64");
        if (!($OE && MO_UID === $OE)) {
            goto KUm;
        }
        $this->appname = $eC->get_value("\x61\x70\160\x6e\x61\155\145");
        return $eC;
        KUm:
        return false;
    }
    public function mo_oauth_login_validate()
    {
        if (isset($_REQUEST["\x6d\157\x5f\154\157\147\151\x6e\x5f\x70\157\x70\x75\x70"]) && 1 == sanitize_text_field(wp_unslash($_REQUEST["\155\157\137\x6c\x6f\x67\x69\x6e\137\160\x6f\160\x75\x70"]))) {
            goto shk;
        }
        parent::mo_oauth_login_validate();
        global $Yh;
        if (!(isset($_REQUEST["\164\157\153\145\156"]) && !empty($_REQUEST["\x74\157\153\145\x6e"]) || isset($_REQUEST["\x69\144\137\164\157\153\x65\156"]) && !empty($_REQUEST["\151\x64\137\x74\157\153\145\x6e"]))) {
            goto zMm;
        }
        if (!(isset($_REQUEST["\164\x6f\x6b\x65\x6e"]) && !empty($_REQUEST["\164\x6f\153\x65\x6e"]))) {
            goto tCR;
        }
        $kD = $Yh->is_valid_jwt(sanitize_text_field(wp_unslash(urldecode($_REQUEST["\x74\157\153\145\x6e"]))));
        if ($kD) {
            goto Lzy;
        }
        return;
        Lzy:
        tCR:
        if (!(isset($_REQUEST["\156\x6f\156\x63\145"]) && (isset($_COOKIE["\x6d\x6f\137\157\141\x75\x74\x68\x5f\x6e\157\156\x63\145"]) && sanitize_text_field(wp_unslash($_COOKIE["\x6d\157\x5f\157\141\x75\x74\150\137\156\157\x6e\x63\145"])) != sanitize_text_field(wp_unslash($_REQUEST["\x6e\157\156\x63\x65"]))))) {
            goto fel;
        }
        $Yh->handle_error("\x4e\157\156\x63\145\40\166\145\x72\151\146\151\143\141\x74\151\157\156\x20\151\163\40\x66\141\151\x6c\145\144\x2e\40\x50\x6c\x65\141\163\x65\x20\143\x6f\156\164\141\x63\164\40\164\x6f\40\x79\x6f\165\x72\40\141\144\155\x69\156\151\x73\x74\x72\x61\x74\x6f\x72\x2e");
        wp_die("\x4e\157\156\143\145\x20\x76\x65\x72\151\146\151\143\x61\164\x69\x6f\x6e\40\x69\163\40\146\x61\x69\x6c\x65\x64\56\x20\120\154\145\x61\163\145\40\143\157\x6e\x74\141\x63\x74\x20\164\157\x20\x79\x6f\165\162\40\x61\144\155\151\156\151\x73\x74\162\x61\x74\x6f\162\x2e");
        exit;
        fel:
        $wf = new Implicit(isset($_SERVER["\x51\x55\x45\122\x59\x5f\x53\124\122\x49\x4e\x47"]) ? sanitize_text_field(wp_unslash($_SERVER["\121\x55\x45\122\131\x5f\x53\x54\x52\111\116\107"])) : '');
        $eC = $this->check_state($wf);
        $KY = $Yh->get_app_by_name($eC->get_value("\141\x70\160\156\x61\155\145"));
        $KY = $KY ? $KY->get_app_config() : false;
        if ($eC) {
            goto JBU;
        }
        $Ao = "\x53\x74\141\x74\145\x20\120\141\x72\141\x6d\x65\x74\x65\x72\40\144\151\x64\x20\x6e\157\x74\40\x76\x65\x72\x69\146\171\56\x20\x50\154\145\141\163\145\x20\124\162\171\x20\114\157\147\x67\x69\x6e\x67\40\x69\156\x20\141\x67\141\151\156\56";
        $Yh->handle_error($Ao);
        MO_Oauth_Debug::mo_oauth_log("\123\164\141\164\145\40\120\x61\162\x61\x6d\145\164\145\162\40\144\x69\144\x20\x6e\157\164\40\x76\x65\x72\x69\x66\171\56\40\x50\x6c\x65\x61\163\145\40\x54\x72\x79\x20\114\x6f\147\x67\x69\156\x67\40\x69\x6e\40\x61\x67\x61\x69\x6e\x31\56");
        wp_die($Ao);
        JBU:
        if (!is_wp_error($wf)) {
            goto G1e;
        }
        $Yh->handle_error($wf->get_error_message());
        wp_die(wp_kses($wf->get_error_message(), \mo_oauth_get_valid_html()));
        MO_Oauth_Debug::mo_oauth_log("\120\154\x65\x61\163\145\x20\164\162\171\40\x4c\x6f\147\147\151\156\147\x20\151\156\40\x61\x67\x61\151\x6e\x2e");
        exit("\120\x6c\x65\141\163\x65\40\164\x72\171\x20\x4c\x6f\x67\x67\x69\x6e\147\x20\151\x6e\40\x61\x67\141\151\x6e\x2e");
        G1e:
        if (!($wf->get_query_param("\141\143\143\145\x73\163\137\x74\x6f\x6b\145\x6e") && $KY["\x61\160\x70\x5f\164\x79\x70\x65"] == "\x6f\141\x75\164\150")) {
            goto zZz;
        }
        $this->check_access_token();
        return;
        zZz:
        $gK = $wf->get_jwt_from_query_param();
        if (!is_wp_error($gK)) {
            goto nwV;
        }
        $Yh->handle_error($gK->get_error_message());
        MO_Oauth_Debug::mo_oauth_log($gK->get_error_message());
        wp_die(wp_kses($gK->get_error_message(), \mo_oauth_get_valid_html()));
        nwV:
        MO_Oauth_Debug::mo_oauth_log("\112\127\124\40\x54\x6f\153\x65\156\40\165\x73\145\144\40\x66\x6f\162\40\x6f\x62\x74\141\x69\156\x69\x6e\147\x20\162\x65\163\157\165\x72\x63\x65\40\157\167\156\145\x72\40\x3d\x3e\40");
        MO_Oauth_Debug::mo_oauth_log($gK);
        $J6 = $this->handle_jwt($gK);
        MO_Oauth_Debug::mo_oauth_log("\x52\x65\x73\x6f\x75\162\143\145\40\117\x77\x6e\145\162\40\75\76\40");
        MO_Oauth_Debug::mo_oauth_log($J6);
        if (!is_wp_error($J6)) {
            goto BPF;
        }
        $Yh->handle_error($J6->get_error_message());
        wp_die(wp_kses($J6->get_error_message(), \mo_oauth_get_valid_html()));
        BPF:
        if ($KY) {
            goto RFd;
        }
        $PH = "\123\164\x61\164\x65\x20\120\141\162\141\x6d\145\164\145\x72\x20\x64\x69\144\40\156\x6f\164\40\166\x65\162\x69\146\171\56\x20\120\x6c\x65\x61\163\x65\x20\124\162\x79\40\x4c\x6f\x67\x67\151\156\147\x20\x69\156\x20\141\147\x61\x69\x6e\x32\56";
        $Yh->handle_error($PH);
        MO_Oauth_Debug::mo_oauth_log("\x53\164\x61\x74\x65\x20\120\x61\162\x61\x6d\x65\x74\x65\x72\x20\x64\x69\144\x20\156\x6f\x74\x20\166\x65\x72\x69\x66\x79\56\40\120\154\x65\x61\x73\x65\x20\124\162\x79\40\x4c\157\x67\147\151\156\x67\x20\151\156\40\141\147\141\151\156\56");
        wp_die($PH);
        RFd:
        if ($J6) {
            goto M5O;
        }
        $OJ = "\x4a\x57\124\40\x53\151\147\156\x61\164\165\x72\x65\40\144\151\x64\40\x6e\x6f\164\x20\x76\145\162\x69\146\171\56\x20\x50\154\145\141\163\145\x20\124\x72\171\40\x4c\x6f\147\147\x69\x6e\x67\x20\151\x6e\x20\141\x67\x61\151\156\x2e";
        $Yh->handle_error($OJ);
        MO_Oauth_Debug::mo_oauth_log("\112\x57\124\x20\x53\x69\x67\x6e\141\x74\165\162\145\x20\x64\151\x64\x20\156\157\164\x20\166\x65\162\x69\146\171\x2e\40\120\154\145\141\163\145\40\124\162\x79\x20\x4c\x6f\x67\147\151\156\x67\40\x69\156\x20\x61\147\x61\151\x6e\56");
        wp_die($OJ);
        M5O:
        $RV = $eC->get_value("\164\145\163\164\137\143\157\156\146\151\x67");
        $this->resource_owner = $J6;
        $this->handle_group_details($wf->get_query_param("\x61\x63\143\145\163\x73\x5f\x74\157\x6b\x65\x6e"), isset($KY["\x67\x72\x6f\x75\x70\x64\x65\x74\x61\151\x6c\x73\x75\162\x6c"]) ? $KY["\147\162\157\x75\x70\x64\x65\x74\141\x69\x6c\163\x75\x72\x6c"] : '', isset($KY["\x67\x72\157\x75\x70\156\141\155\x65\137\141\x74\164\x72\x69\x62\x75\x74\x65"]) ? $KY["\147\x72\x6f\165\160\156\x61\x6d\x65\137\141\164\x74\162\151\142\x75\164\145"] : '', $RV);
        $sU = [];
        $F2 = $this->dropdownattrmapping('', $J6, $sU);
        $Yh->mo_oauth_client_update_option("\155\x6f\137\157\141\165\164\150\x5f\x61\x74\164\162\137\x6e\x61\155\145\137\154\x69\x73\x74" . $KY["\141\160\x70\111\x64"], $F2);
        if (!($RV && '' !== $RV)) {
            goto mzI;
        }
        $this->render_test_config_output($J6);
        exit;
        mzI:
        MO_Oauth_Debug::mo_oauth_log("\102\x65\x66\157\162\145\40\x68\x61\156\x64\154\145\x20\163\163\x6f\x31");
        $this->handle_sso($this->app_name, $KY, $J6, $eC->get_state(), $wf->get_query_param());
        zMm:
        if (!(isset($_REQUEST["\150\165\142\154\145\164"]) || isset($_REQUEST["\160\157\162\164\x61\x6c\x5f\x64\157\x6d\x61\x69\156"]))) {
            goto iNd;
        }
        return;
        iNd:
        if (!(isset($_REQUEST["\141\143\x63\x65\163\x73\x5f\x74\157\x6b\145\156"]) && '' !== $_REQUEST["\141\143\x63\x65\163\163\x5f\x74\x6f\153\x65\156"])) {
            goto hcN;
        }
        $this->check_access_token();
        hcN:
        if (!(isset($_REQUEST["\x6c\x6f\147\151\156"]) && "\x70\x77\144\147\x72\x6e\164\x66\x72\155" === sanitize_text_field(wp_unslash($_REQUEST["\x6c\157\x67\151\x6e"])))) {
            goto S1D;
        }
        $RG = new Password();
        $p1 = isset($_REQUEST["\143\141\x6c\x6c\x65\162"]) && !empty($_REQUEST["\x63\141\x6c\x6c\145\x72"]) ? sanitize_text_field(wp_unslash($_REQUEST["\143\x61\x6c\154\x65\x72"])) : false;
        $lH = isset($_REQUEST["\x74\157\157\154"]) && !empty($_REQUEST["\x74\157\157\x6c"]) ? sanitize_text_field(wp_unslash($_REQUEST["\x74\157\x6f\154"])) : false;
        $zl = isset($_REQUEST["\x61\160\x70\137\x6e\141\x6d\x65"]) && !empty($_REQUEST["\x61\x70\160\137\156\x61\x6d\145"]) ? sanitize_text_field(wp_unslash($_REQUEST["\x61\x70\160\x5f\156\141\155\x65"])) : '';
        if (!($zl == '')) {
            goto RCl;
        }
        $q3 = "\116\157\40\163\165\143\x68\x20\x61\160\160\40\146\x6f\165\x6e\144\x20\143\157\x6e\146\x69\x67\x75\162\x65\x64\56\40\x50\x6c\x65\141\x73\145\x20\143\x68\x65\x63\153\40\x69\146\x20\171\x6f\165\x20\x61\162\x65\x20\163\145\x6e\144\151\156\147\x20\x74\x68\145\x20\x63\157\162\x72\x65\143\x74\x20\141\160\x70\x6c\151\143\x61\x74\x69\157\156\40\156\x61\155\x65";
        $Yh->handle_error($q3);
        wp_die(wp_kses($q3, \mo_oauth_get_valid_html()));
        exit;
        RCl:
        $mc = $Yh->mo_oauth_client_get_option("\x6d\x6f\x5f\157\x61\165\164\x68\x5f\141\x70\160\163\x5f\154\x69\x73\164");
        if (is_array($mc) && isset($mc[$zl])) {
            goto KJi;
        }
        $q3 = "\116\157\40\163\x75\x63\x68\x20\141\x70\160\40\x66\x6f\165\x6e\144\40\x63\x6f\156\x66\151\147\165\x72\x65\144\x2e\40\120\154\x65\141\x73\145\40\x63\150\145\143\x6b\x20\151\146\x20\x79\157\165\40\x61\162\x65\40\163\x65\156\x64\151\x6e\147\40\164\150\x65\40\143\157\x72\162\x65\x63\164\40\141\x70\160\137\x6e\x61\x6d\x65";
        $Yh->handle_error($q3);
        wp_die(wp_kses($q3, \mo_oauth_get_valid_html()));
        exit;
        KJi:
        $ZG = isset($_REQUEST["\154\157\143\x61\164\151\x6f\156"]) && !empty($_REQUEST["\154\x6f\x63\141\164\151\157\156"]) ? $_REQUEST["\x6c\x6f\143\x61\164\151\157\x6e"] : site_url();
        $m8 = isset($_REQUEST["\164\145\x73\x74"]) && !empty($_REQUEST["\x74\x65\163\164"]);
        if (!(!$p1 || !$lH || !$zl)) {
            goto iau;
        }
        $Yh->redirect_user(urldecode($ZG));
        iau:
        do_action("\155\157\137\x6f\141\x75\x74\x68\x5f\x63\165\x73\x74\x6f\155\x5f\x73\163\157", $p1, $lH, $zl, $ZG, $m8);
        $RG->behave($p1, $lH, $zl, $ZG, $m8);
        S1D:
        goto SM4;
        shk:
        echo "\x9\11\x9\74\x73\x63\x72\x69\x70\164\x20\164\171\160\145\x3d\x22\x74\145\170\164\57\x6a\141\x76\x61\163\x63\162\151\x70\x74\x22\76\xd\xa\11\x9\x9\x76\141\x72\x20\x62\x61\x73\145\137\165\x72\x6c\40\75\40\42";
        echo esc_url(site_url());
        echo "\x22\x3b\xd\xa\x9\11\11\x76\x61\162\40\x61\160\160\137\156\x61\x6d\145\40\x3d\40\42";
        echo esc_attr(sanitize_text_field(wp_unslash($_REQUEST["\141\160\160\x5f\156\x61\x6d\x65"])));
        echo "\x22\x3b\xd\xa\x9\x9\11\11\x76\141\x72\x20\155\171\127\x69\156\144\x6f\x77\40\x3d\x20\x77\x69\x6e\144\x6f\167\56\x6f\x70\145\156\x28\40\142\141\163\145\137\165\162\x6c\x20\53\x20\x27\57\77\157\160\x74\151\157\156\x3d\157\x61\165\x74\x68\x72\x65\144\151\x72\x65\143\x74\46\141\160\160\x5f\x6e\x61\x6d\x65\75\47\40\53\40\x61\160\160\137\x6e\x61\155\145\54\40\x27\47\x2c\x20\x27\167\151\x64\x74\x68\75\x35\x30\60\x2c\150\x65\151\147\x68\x74\x3d\65\x30\x30\x27\51\x3b\xd\12\x9\11\x9\11\74\x2f\163\x63\x72\x69\x70\164\76\15\xa\11\11\11\x9";
        SM4:
    }
    public function check_access_token()
    {
        global $Yh;
        do_action("\155\157\137\157\x61\x75\x74\x68\137\x63\x68\145\x63\153\137\x63\x75\x73\x74\x6f\x6d\x5f\141\x63\x63\x65\163\x73\x5f\164\x6f\x6b\x65\x6e", $_REQUEST);
        $wf = new Implicit(isset($_SERVER["\121\x55\105\122\131\137\x53\x54\122\x49\116\107"]) ? sanitize_text_field(wp_unslash($_SERVER["\x51\x55\105\x52\131\137\x53\124\122\x49\x4e\107"])) : '');
        $eC = $this->check_state($wf);
        $KY = $Yh->get_app_by_name($eC->get_value("\141\160\160\156\x61\155\x65"));
        $KY = $KY ? $KY->get_app_config() : false;
        $J6 = [];
        if (!(isset($KY["\x72\145\163\x6f\x75\x72\x63\x65\x6f\x77\156\145\162\x64\x65\x74\x61\x69\154\x73\165\162\154"]) && !empty($KY["\162\x65\163\x6f\x75\x72\143\x65\x6f\167\x6e\145\x72\x64\145\x74\141\x69\x6c\163\x75\162\154"]))) {
            goto SwR;
        }
        $J6 = $this->oauth_handler->get_resource_owner($KY["\x72\145\x73\157\x75\x72\x63\x65\157\167\156\x65\162\144\x65\164\x61\151\x6c\163\165\162\x6c"], $wf->get_query_param("\x61\x63\143\145\x73\x73\x5f\164\157\x6b\x65\x6e"));
        SwR:
        MO_Oauth_Debug::mo_oauth_log("\x41\143\x63\145\x73\x73\x20\x54\x6f\x6b\x65\x6e\40\x3d\76\x20");
        MO_Oauth_Debug::mo_oauth_log($wf->get_query_param("\x61\x63\143\x65\x73\163\137\164\157\x6b\x65\x6e"));
        $dX = [];
        if (!$Yh->is_valid_jwt($wf->get_query_param("\141\143\x63\x65\x73\163\137\164\157\153\x65\x6e"))) {
            goto nGT;
        }
        $gK = $wf->get_jwt_from_query_param();
        $dX = $this->handle_jwt($gK);
        nGT:
        if (empty($dX)) {
            goto x14;
        }
        $J6 = array_merge($J6, $dX);
        x14:
        if (!(empty($J6) && !$Yh->is_valid_jwt($wf->get_query_param("\141\143\143\145\163\x73\x5f\x74\157\153\x65\x6e")))) {
            goto Pl9;
        }
        $Yh->handle_error("\111\156\166\x61\x6c\x69\x64\40\122\x65\x73\160\157\156\163\145\x20\x52\145\143\145\151\166\145\x64\x2e");
        MO_Oauth_Debug::mo_oauth_log("\x49\x6e\166\x61\154\151\x64\x20\122\x65\x73\x70\x6f\156\x73\x65\x20\x52\x65\143\x65\151\x76\x65\144");
        wp_die("\111\x6e\166\x61\154\151\x64\x20\122\145\163\160\x6f\x6e\163\145\x20\122\x65\x63\145\x69\x76\145\x64\x2e");
        exit;
        Pl9:
        $this->resource_owner = $J6;
        MO_Oauth_Debug::mo_oauth_log("\x52\x65\x73\x6f\165\x72\x63\x65\40\117\x77\x6e\145\x72\x20\x3d\76\x20");
        MO_Oauth_Debug::mo_oauth_log($this->resource_owner);
        $RV = $eC->get_value("\164\x65\163\164\x5f\143\157\156\146\x69\147");
        $this->handle_group_details($wf->get_query_param("\x61\x63\x63\145\163\163\x5f\164\x6f\153\x65\156"), isset($KY["\x67\162\157\165\160\144\145\164\141\151\154\x73\x75\x72\x6c"]) ? $KY["\x67\x72\x6f\165\160\144\145\x74\x61\x69\x6c\163\165\x72\154"] : '', isset($KY["\x67\x72\x6f\x75\160\x6e\x61\x6d\145\137\141\164\164\x72\151\x62\x75\x74\x65"]) ? $KY["\x67\x72\157\x75\160\156\x61\155\145\137\x61\164\x74\x72\151\142\x75\x74\145"] : '', $RV);
        $sU = [];
        $F2 = $this->dropdownattrmapping('', $J6, $sU);
        $Yh->mo_oauth_client_update_option("\155\x6f\137\157\141\x75\x74\x68\x5f\141\x74\x74\162\x5f\x6e\x61\x6d\x65\137\154\151\163\x74" . $KY["\141\x70\160\111\x64"], $F2);
        if (!($RV && '' !== $RV)) {
            goto E80;
        }
        $this->render_test_config_output($J6);
        exit;
        E80:
        $GP = str_replace("\45\63\104", "\75", rawurldecode($wf->get_query_param("\163\164\141\x74\145")));
        $this->handle_sso($this->app_name, $KY, $J6, $GP, $wf->get_query_param());
    }
    public function handle_group_details($C2 = '', $c0 = '', $kk = '', $RV = false)
    {
        $YK = [];
        if (!('' === $C2 || '' === $kk)) {
            goto OdC;
        }
        return;
        OdC:
        if (!('' !== $c0)) {
            goto tuM;
        }
        $YK = $this->oauth_handler->get_resource_owner($c0, $C2);
        if (!(isset($_COOKIE["\x6d\x6f\x5f\157\141\x75\x74\x68\137\164\145\x73\x74"]) && sanitize_text_field(wp_unslash($_COOKIE["\x6d\x6f\x5f\x6f\x61\x75\x74\150\x5f\x74\145\163\164"])))) {
            goto Ljn;
        }
        if (!(is_array($YK) && !empty($YK))) {
            goto BdM;
        }
        $this->render_test_config_output($YK, true);
        BdM:
        return;
        Ljn:
        tuM:
        $cl = $this->get_group_mapping_attribute($this->resource_owner, $YK, $kk);
        $this->group_mapping_attr = '' !== $cl ? false : $cl;
    }
    public function get_group_mapping_attribute($J6 = array(), $YK = array(), $kk = '')
    {
        global $Yh;
        $uK = '';
        if (!('' === $kk)) {
            goto TvZ;
        }
        return '';
        TvZ:
        if (isset($YK) && !empty($YK)) {
            goto yec;
        }
        if (isset($J6) && !empty($J6)) {
            goto eT7;
        }
        goto Inq;
        yec:
        $uK = $Yh->getnestedattribute($YK, $kk);
        goto Inq;
        eT7:
        $uK = $Yh->getnestedattribute($J6, $kk);
        Inq:
        if (!($uK === 0 || $uK === "\x30")) {
            goto YAb;
        }
        return $uK;
        YAb:
        return !empty($uK) ? $uK : '';
    }
    public function handle_jwt($gK)
    {
        global $Yh;
        $F8 = $Yh->get_app_by_name($this->app_name);
        $Hw = $F8->get_app_config("\152\167\x74\x5f\x73\165\x70\x70\x6f\162\x74");
        if ($Hw) {
            goto Lbw;
        }
        return $gK->get_decoded_payload();
        Lbw:
        MO_Oauth_Debug::mo_oauth_log("\112\x57\x54\x20\x76\145\x72\151\146\151\143\x61\x74\151\x6f\156\40\145\156\x61\x62\x6c\x65\x64\x2e");
        MO_Oauth_Debug::mo_oauth_log("\x64\x65\143\157\x64\x65\x64\40\x70\141\171\x6c\x6f\x61\144\x2e");
        MO_Oauth_Debug::mo_oauth_log($gK->get_decoded_payload());
        $xt = $F8->get_app_config("\152\x77\164\x5f\x61\154\x67\157");
        if ($gK->check_algo($xt)) {
            goto hWt;
        }
        return new \WP_Error("\x69\x6e\x76\x61\x6c\x69\144\137\x73\x69\147\x6e", __("\112\x57\124\40\x53\x69\x67\x6e\x69\x6e\x67\40\x61\x6c\x67\x6f\x72\x69\164\x68\x6d\40\x69\x73\40\x6e\x6f\164\x20\141\x6c\x6c\157\167\145\x64\40\x6f\162\x20\165\156\x73\165\x70\160\157\162\164\x65\144\x2e"));
        hWt:
        $Ju = "\122\123\x41" === $xt ? $F8->get_app_config("\170\x35\60\x39\x5f\x63\x65\162\164") : $F8->get_app_config("\143\x6c\x69\145\x6e\164\x5f\x73\x65\143\x72\x65\x74");
        $GY = $F8->get_app_config("\152\x77\153\163\x75\162\154");
        $WJ = $GY ? $gK->verify_from_jwks($GY) : $gK->verify($Ju);
        return !$WJ ? $WJ : $gK->get_decoded_payload();
    }
    public function get_resource_owner_from_app($A2, $F8)
    {
        global $Yh;
        $this->app_name = $F8;
        $gK = new JWTUtils($A2);
        if (!is_wp_error($gK)) {
            goto iUf;
        }
        $Yh->handle_error($gK->get_error_message());
        MO_Oauth_Debug::mo_oauth_log("\x49\x6e\166\141\x6c\151\x64\x20\112\x57\164\x20\x74\x6f\153\145\156\x2c\x20\160\x6c\x65\x61\163\145\40\x63\150\145\x63\153\40\x49\104\x20\x74\157\153\x65\156");
        wp_die($gK);
        iUf:
        MO_Oauth_Debug::mo_oauth_log("\112\127\124\x20\x54\157\x6b\145\x6e\40\165\x73\x65\x64\40\x66\x6f\162\40\157\x62\x74\141\151\156\151\156\147\40\162\x65\x73\157\x75\x72\143\x65\x20\157\x77\x6e\145\162\40\x66\x72\157\155\40\x61\x70\160\40\75\x3e\x20");
        MO_Oauth_Debug::mo_oauth_log("\144\145\x63\157\144\x65\144\40\x68\x65\141\x64\x65\x72\x2e");
        MO_Oauth_Debug::mo_oauth_log($gK->get_decoded_header());
        $J6 = $this->handle_jwt($gK);
        if (!is_wp_error($J6)) {
            goto O8b;
        }
        $Yh->handle_error($J6->get_error_message());
        MO_Oauth_Debug::mo_oauth_log("\106\x61\151\x6c\145\x64\x20\x74\x6f\40\x76\145\162\151\146\x79\40\112\127\x54\40\x54\157\x6b\x65\156\56\x20" . $J6);
        wp_die($J6);
        O8b:
        if (!(false === $J6)) {
            goto VVj;
        }
        $N5 = "\106\x61\151\x6c\x65\x64\40\x74\157\x20\x76\x65\162\x69\x66\171\x20\x4a\127\124\40\x54\157\x6b\145\156\x2e\40\x50\154\x65\141\x73\x65\x20\143\x68\145\x63\153\x20\171\x6f\x75\162\40\x63\157\156\x66\x69\x67\x75\162\x61\164\151\x6f\x6e\x20\157\162\40\x63\157\x6e\164\141\x63\164\40\171\x6f\x75\x72\x20\101\x64\155\151\x6e\x69\163\x74\162\x61\164\x6f\x72\x2e";
        $Yh->handle_error($N5);
        MO_Oauth_Debug::mo_oauth_log("\x46\141\x69\154\x65\144\40\x74\x6f\40\166\145\162\151\146\171\x20\x4a\127\124\x20\124\x6f\x6b\x65\x6e\x2e\40\120\154\145\141\x73\145\40\x63\x68\145\x63\x6b\40\x79\x6f\165\x72\x20\143\x6f\x6e\x66\151\x67\x75\x72\x61\x74\x69\x6f\x6e\x20\x6f\162\40\x63\157\156\164\141\x63\x74\x20\x79\157\x75\x72\40\x41\x64\x6d\151\156\x69\163\164\162\x61\x74\x6f\162\56");
        wp_die($N5);
        VVj:
        return $J6;
    }
}
