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
        global $Uj;
        parent::__construct();
        add_filter("\x6d\157\x5f\x61\165\164\150\x5f\x75\x72\x6c\x5f\151\x6e\164\145\162\156\141\x6c", array($this, "\155\157\x5f\157\141\x75\x74\x68\137\x63\154\151\x65\x6e\164\x5f\147\145\x6e\145\162\x61\x74\145\137\x61\x75\x74\150\x6f\162\151\172\x61\164\151\157\156\137\x75\162\154"), 5, 2);
        add_action("\167\160\137\146\x6f\x6f\x74\x65\162", array($this, "\x6d\x6f\x5f\157\141\x75\x74\x68\137\x63\154\x69\x65\x6e\164\x5f\151\155\160\154\151\x63\151\164\137\146\x72\x61\x67\155\x65\156\164\137\150\141\156\x64\x6c\145\162"));
        add_action("\x6d\x6f\x5f\157\x61\165\x74\x68\137\x72\x65\163\x74\x72\x69\x63\164\x5f\x65\155\141\x69\154\x73", array($this, "\155\157\x5f\157\x61\x75\x74\150\137\143\x6c\x69\145\x6e\x74\137\x72\x65\x73\x74\162\151\143\164\137\x65\x6d\141\151\x6c\163"), 10, 2);
        add_action("\155\x6f\x5f\157\141\165\164\150\x5f\x63\154\x69\145\x6e\x74\x5f\155\141\x70\137\x72\x6f\154\145\163", array($this, "\x6d\x6f\x5f\x6f\141\165\164\150\x5f\x63\154\151\x65\156\x74\137\x6d\x61\160\x5f\x72\157\x6c\x65\x73"), 10, 1);
        $zS = $Uj->mo_oauth_client_get_option("\x6d\157\x5f\x6f\141\x75\164\x68\137\x65\156\x61\x62\154\145\x5f\157\x61\165\x74\150\x5f\x77\x70\x5f\154\x6f\x67\x69\x6e");
        if (!$zS) {
            goto QOY;
        }
        remove_filter("\x61\x75\164\x68\x65\x6e\x74\x69\x63\141\x74\x65", "\x77\160\137\x61\165\x74\150\145\x6e\x74\x69\x63\141\x74\145\137\x75\x73\x65\x72\x6e\141\155\145\x5f\160\141\x73\x73\x77\157\x72\144", 20, 3);
        $sC = new Password(true);
        add_filter("\x61\165\164\x68\145\156\x74\x69\x63\x61\x74\x65", array($sC, "\155\157\137\x6f\x61\x75\x74\x68\137\167\x70\x5f\154\157\147\x69\156"), 20, 3);
        QOY:
    }
    public function mo_oauth_client_restrict_emails($g3, $Kn)
    {
        global $Uj;
        $xd = isset($Kn["\162\x65\x73\164\x72\151\x63\164\x65\x64\137\144\157\x6d\141\151\156\x73"]) ? $Kn["\162\x65\163\x74\x72\151\x63\164\145\x64\137\x64\x6f\155\x61\x69\156\x73"] : '';
        if (!empty($xd)) {
            goto Ay1;
        }
        return;
        Ay1:
        $IR = isset($Kn["\x61\x6c\154\157\x77\137\162\x65\163\x74\x72\151\x63\164\145\144\x5f\144\x6f\x6d\x61\x69\x6e\x73"]) ? $Kn["\141\154\154\x6f\x77\x5f\162\x65\163\164\x72\x69\x63\164\x65\144\x5f\144\157\x6d\141\x69\x6e\x73"] : '';
        if (!empty($IR)) {
            goto Ivn;
        }
        $IR = false;
        Ivn:
        $IR = intval($IR);
        $xd = explode("\54", $xd);
        $US = substr($g3, strpos($g3, "\100") + 1);
        $zE = in_array($US, $xd, false);
        $zE = $IR ? !$zE : $zE;
        $Xw = !empty($xd) && $zE;
        if (!$Xw) {
            goto ncb;
        }
        $Bl = "\x59\157\165\40\x64\x6f\x20\x6e\157\164\x20\150\x61\166\145\40\x72\x69\147\x68\164\163\x20\164\157\40\141\x63\x63\145\x73\163\x20\164\150\151\163\40\160\141\147\145\56\x20\120\x6c\145\x61\x73\x65\x20\143\157\x6e\164\x61\x63\164\40\x74\x68\x65\40\x61\144\x6d\151\156\151\163\164\162\141\164\157\x72\56";
        $Uj->handle_error($Bl);
        wp_die($Bl);
        ncb:
    }
    public function mo_oauth_client_generate_authorization_url($NF, $gR)
    {
        global $Uj;
        $ro = $Uj->parse_url($NF);
        $Kn = $Uj->get_app_by_name($gR)->get_app_config();
        $eL = md5(rand());
        setcookie("\155\157\x5f\157\141\x75\164\150\x5f\156\x6f\156\143\145", $eL, time() + 120);
        if (isset($Kn["\x67\162\x61\x6e\x74\x5f\164\171\160\145"]) && "\111\155\x70\154\x69\x63\151\164\40\107\x72\x61\x6e\164" === $Kn["\147\x72\x61\156\164\137\164\171\x70\145"]) {
            goto Swv;
        }
        if (!(isset($Kn["\147\162\141\x6e\x74\x5f\164\x79\x70\145"]) && "\x48\171\x62\162\x69\x64\40\107\162\141\x6e\x74" === $Kn["\x67\162\x61\x6e\164\137\x74\171\x70\145"])) {
            goto Sco;
        }
        MO_Oauth_Debug::mo_oauth_log("\107\162\141\x6e\164\72\40\x48\x79\142\162\151\x64\x20\107\x72\x61\x6e\164");
        $ro["\x71\165\145\x72\x79"]["\x72\145\x73\x70\x6f\x6e\x73\x65\137\x74\x79\x70\x65"] = "\x74\157\x6b\x65\x6e\x25\62\60\x69\144\137\164\157\153\x65\156\45\62\60\143\x6f\x64\145";
        return $Uj->generate_url($ro);
        Sco:
        goto lRE;
        Swv:
        $ro["\161\165\145\162\171"]["\156\x6f\156\x63\145"] = $eL;
        $ro["\161\165\145\x72\x79"]["\x72\x65\x73\160\157\156\x73\x65\x5f\164\x79\x70\x65"] = "\x74\157\153\x65\x6e";
        $U1 = isset($Kn["\155\157\x5f\x6f\141\165\164\150\x5f\162\145\163\160\157\x6e\163\x65\137\164\171\160\x65"]) && !empty($Kn["\x6d\157\137\x6f\x61\165\x74\x68\x5f\162\x65\163\160\157\156\163\x65\137\164\x79\x70\x65"]) ? $Kn["\155\157\137\x6f\141\165\x74\150\x5f\x72\x65\x73\x70\x6f\156\x73\x65\137\x74\x79\160\145"] : "\x74\x6f\x6b\145\156";
        $ro["\161\x75\145\x72\x79"]["\x72\x65\163\160\157\156\163\x65\x5f\x74\x79\x70\x65"] = $U1;
        return $Uj->generate_url($ro);
        lRE:
        return $NF;
    }
    public function mo_oauth_client_map_roles($z5)
    {
        $Wh = isset($z5["\141\160\x70\x5f\143\x6f\x6e\146\x69\147"]) && !empty($z5["\x61\160\160\x5f\143\157\x6e\146\x69\x67"]) ? $z5["\141\x70\x70\x5f\143\157\x6e\x66\x69\147"] : [];
        $MF = isset($Wh["\147\162\157\165\x70\156\x61\x6d\145\x5f\x61\x74\164\162\151\142\x75\x74\145"]) && '' !== $Wh["\147\x72\157\165\x70\156\141\x6d\x65\x5f\141\x74\x74\162\x69\142\165\x74\145"] ? $Wh["\147\162\x6f\165\160\156\141\x6d\x65\137\141\x74\x74\x72\151\142\x75\x74\145"] : false;
        global $Uj;
        $j4 = false;
        if (isset($Wh["\x65\156\141\x62\x6c\x65\x5f\162\157\x6c\145\137\155\x61\x70\x70\151\156\x67"])) {
            goto JZM;
        }
        $Wh["\x65\x6e\141\142\154\145\x5f\x72\x6f\154\x65\x5f\x6d\141\x70\x70\151\156\x67"] = true;
        $j4 = true;
        JZM:
        if (isset($Wh["\x5f\155\x61\160\160\151\156\147\137\x76\141\x6c\x75\x65\137\144\x65\x66\x61\165\154\164"])) {
            goto luG;
        }
        $Wh["\137\x6d\x61\160\x70\x69\x6e\x67\137\x76\141\154\165\x65\x5f\x64\x65\x66\141\x75\x6c\164"] = "\x73\x75\x62\x73\143\162\151\x62\x65\162";
        $j4 = true;
        luG:
        if (!boolval($j4)) {
            goto fP3;
        }
        if (!(isset($Wh["\x63\x6c\151\x65\x6e\x74\x5f\x63\162\x65\x64\x73\137\x65\x6e\x63\x72\160\171\164\145\144"]) && boolval($Wh["\x63\154\x69\145\156\x74\x5f\x63\162\x65\x64\x73\x5f\x65\156\143\162\160\x79\164\x65\x64"]))) {
            goto CDH;
        }
        $Wh["\143\154\x69\x65\156\164\137\x69\x64"] = $Uj->mooauthencrypt($Wh["\143\x6c\151\145\156\164\137\x69\x64"]);
        $Wh["\143\x6c\151\x65\156\164\x5f\163\x65\143\x72\x65\164"] = $Uj->mooauthencrypt($Wh["\x63\154\151\x65\x6e\164\137\x73\145\143\162\x65\164"]);
        CDH:
        $Uj->set_app_by_name($z5["\x61\x70\x70\137\x6e\x61\x6d\x65"], $Wh);
        fP3:
        $this->resource_owner = isset($z5["\162\x65\x73\157\x75\x72\x63\145\x5f\157\x77\156\x65\x72"]) && !empty($z5["\x72\x65\x73\x6f\x75\162\143\145\x5f\157\x77\156\145\x72"]) ? $z5["\162\x65\x73\157\x75\x72\x63\145\137\157\167\x6e\145\162"] : [];
        $this->group_mapping_attr = $this->get_group_mapping_attribute($this->resource_owner, false, $MF);
        MO_Oauth_Debug::mo_oauth_log("\x47\x72\x6f\165\x70\x20\115\141\160\160\x69\x6e\147\40\x41\x74\x74\x72\x69\x62\x75\164\x65\163\40\x3d\x3e\40" . $MF);
        $fD = new MappingHandler(isset($z5["\x75\163\x65\x72\137\x69\144"]) && is_numeric($z5["\165\x73\145\x72\137\151\x64"]) ? intval($z5["\x75\163\145\x72\137\x69\x64"]) : 0, $Wh, $this->group_mapping_attr ? $this->group_mapping_attr : '', isset($z5["\156\x65\167\137\165\163\x65\x72"]) ? \boolval($z5["\x6e\x65\x77\137\x75\163\145\x72"]) : true);
        $Kn = $z5["\143\x6f\x6e\146\x69\147"];
        if (!(!isset($Kn["\153\x65\x65\160\137\x65\x78\x69\163\164\x69\x6e\x67\137\x75\163\x65\x72\x73"]) || 1 !== intval($Kn["\153\x65\145\x70\137\x65\x78\x69\x73\164\151\156\147\x5f\x75\163\x65\x72\163"]))) {
            goto fHA;
        }
        $fD->apply_custom_attribute_mapping(is_array($this->resource_owner) ? $this->resource_owner : []);
        fHA:
        $cf = false;
        $cf = apply_filters("\155\x6f\137\x6f\141\x75\x74\150\137\x63\x6c\151\145\x6e\x74\x5f\x75\160\144\x61\164\145\x5f\141\144\155\151\x6e\x5f\162\x6f\x6c\x65", $cf);
        if (!$cf) {
            goto mJ_;
        }
        MO_Oauth_Debug::mo_oauth_log("\101\144\x6d\x69\x6e\40\122\x6f\x6c\145\x20\167\x69\x6c\x6c\40\142\x65\x20\x75\x70\144\141\x74\145\144");
        mJ_:
        if (!(user_can($z5["\x75\163\x65\162\x5f\x69\144"], "\141\x64\x6d\x69\156\151\163\x74\162\x61\x74\x6f\x72") && !$cf)) {
            goto NQb;
        }
        return;
        NQb:
        $fD->apply_role_mapping(is_array($this->resource_owner) ? $this->resource_owner : []);
    }
    public function mo_oauth_client_implicit_fragment_handler()
    {
        echo "\x9\11\11\x3c\x73\x63\x72\x69\160\164\x3e\xd\12\11\11\11\11\x66\x75\x6e\143\164\x69\x6f\156\x20\143\157\156\x76\x65\162\x74\137\x74\157\137\165\162\154\50\157\x62\152\51\x20\x7b\15\xa\11\x9\11\x9\x9\x72\145\164\x75\x72\156\40\x4f\142\x6a\145\x63\x74\xd\xa\11\x9\11\11\x9\56\153\145\171\x73\50\157\142\152\x29\15\xa\x9\11\11\x9\11\x2e\x6d\x61\x70\50\153\x20\75\76\x20\x60\x24\x7b\x65\x6e\143\157\144\145\x55\122\x49\103\157\155\x70\x6f\156\145\x6e\164\x28\153\x29\175\x3d\x24\x7b\145\156\x63\157\144\145\x55\x52\x49\x43\157\x6d\160\x6f\156\x65\156\164\50\157\142\152\x5b\x6b\x5d\51\x7d\x60\x29\15\xa\11\11\x9\11\x9\x2e\x6a\x6f\151\156\50\47\46\47\x29\73\xd\xa\11\11\x9\x9\x7d\15\xa\xd\12\11\x9\x9\x9\x66\x75\156\143\164\151\x6f\x6e\40\x70\141\x73\163\x5f\164\157\x5f\142\x61\143\x6b\145\x6e\x64\x28\51\40\x7b\xd\12\11\x9\11\x9\x9\151\146\50\167\151\156\x64\x6f\167\56\154\x6f\143\x61\164\151\x6f\x6e\x2e\150\x61\163\x68\51\x20\173\xd\xa\11\x9\x9\x9\11\x9\x76\141\x72\40\x68\x61\x73\x68\x20\75\40\167\151\x6e\144\157\x77\x2e\154\x6f\x63\x61\x74\151\157\156\x2e\x68\x61\163\150\73\15\xa\11\x9\11\11\11\x9\166\x61\x72\40\145\x6c\x65\155\x65\x6e\164\163\40\75\40\x7b\175\x3b\15\12\11\11\11\x9\11\x9\x68\x61\163\150\56\163\x70\x6c\151\164\50\42\x23\x22\x29\x5b\x31\135\x2e\x73\160\154\151\164\x28\x22\46\42\51\x2e\146\157\162\x45\x61\143\150\x28\145\x6c\145\155\x65\x6e\x74\40\75\76\40\173\xd\xa\11\x9\11\x9\11\11\11\166\x61\x72\x20\166\141\x72\163\40\75\x20\x65\x6c\145\155\145\x6e\164\x2e\163\x70\x6c\x69\164\x28\x22\75\x22\x29\73\xd\xa\x9\11\x9\11\x9\x9\x9\x65\x6c\x65\x6d\x65\156\x74\x73\x5b\166\x61\162\163\x5b\60\x5d\x5d\40\x3d\40\166\141\x72\163\x5b\x31\x5d\73\15\xa\11\11\11\11\11\11\x7d\51\x3b\15\12\x9\11\x9\x9\11\11\151\x66\x28\x28\x22\141\x63\143\x65\x73\163\137\x74\x6f\153\145\156\x22\40\x69\x6e\40\x65\154\145\155\145\156\164\x73\51\40\x7c\174\40\50\x22\151\x64\137\x74\157\153\145\156\42\x20\151\x6e\40\145\x6c\145\155\x65\x6e\x74\163\x29\40\x7c\174\40\x28\42\x74\157\x6b\x65\x6e\42\x20\x69\x6e\x20\145\x6c\145\x6d\145\x6e\164\x73\51\51\40\173\15\12\x9\x9\x9\11\x9\11\x9\151\x66\x28\x77\151\156\x64\157\167\x2e\x6c\157\x63\141\x74\151\157\x6e\x2e\x68\162\x65\146\56\151\156\144\x65\170\x4f\x66\50\x22\77\42\51\x20\41\75\75\x20\x2d\61\51\x20\173\xd\12\x9\11\11\11\x9\x9\x9\11\x77\151\156\x64\157\167\56\154\x6f\143\141\164\x69\157\x6e\x20\75\x20\x28\x77\x69\x6e\x64\x6f\167\56\154\157\x63\141\164\151\x6f\156\x2e\x68\x72\145\146\56\163\160\154\151\x74\x28\42\x3f\42\x29\133\x30\135\x20\x2b\x20\x77\x69\x6e\144\157\167\56\x6c\157\x63\141\x74\x69\x6f\156\56\150\141\163\x68\x29\56\x73\x70\154\x69\x74\x28\x27\43\x27\51\133\x30\135\40\x2b\x20\x22\x3f\x22\x20\x2b\x20\x63\157\156\166\x65\162\x74\137\164\x6f\x5f\165\162\154\x28\x65\x6c\145\155\x65\156\x74\x73\x29\x3b\xd\xa\11\x9\11\x9\x9\x9\11\x7d\x20\145\x6c\163\145\x20\173\15\12\11\11\x9\11\11\x9\x9\11\x77\151\156\x64\x6f\167\x2e\154\x6f\143\141\x74\151\157\156\x20\75\x20\167\151\x6e\x64\x6f\167\56\154\x6f\143\141\164\x69\x6f\x6e\56\x68\162\x65\x66\x2e\163\160\x6c\151\x74\50\x27\x23\47\x29\x5b\x30\x5d\40\x2b\x20\42\77\x22\x20\53\x20\143\157\x6e\166\x65\162\164\137\x74\x6f\137\165\162\154\x28\x65\154\145\155\x65\x6e\x74\163\51\x3b\xd\xa\11\11\x9\11\x9\x9\11\175\xd\12\x9\11\x9\x9\x9\x9\x7d\15\12\11\11\11\x9\11\175\xd\xa\x9\11\x9\x9\x7d\xd\xa\15\xa\x9\11\x9\x9\x70\x61\163\163\137\x74\x6f\x5f\142\141\x63\x6b\145\156\x64\50\x29\x3b\xd\xa\x9\x9\x9\x3c\57\x73\143\162\151\x70\164\76\xd\12\xd\xa\11\11";
    }
    private function check_state($iB)
    {
        global $Uj;
        $Zi = str_replace("\x25\63\x64", "\75", urldecode($iB->get_query_param("\163\x74\141\x74\145")));
        $cm = new StorageManager($Zi);
        $Cm = $cm->get_value("\141\x70\160\156\141\x6d\x65");
        $Wh = $Uj->get_app_by_name($Cm)->get_app_config();
        $BW = $Wh["\141\x70\160\x49\x64"];
        $Fr = $Uj->get_app_by_name($BW);
        if (empty($Zi)) {
            goto TKf;
        }
        $Zi = isset($_GET["\163\x74\x61\164\145"]) ? wp_unslash($_GET["\163\164\141\x74\x65"]) : false;
        goto pQc;
        TKf:
        $Zi = $cm->get_state();
        $Zi = apply_filters("\163\164\141\x74\145\x5f\151\156\x74\145\162\156\141\154", $Zi);
        setcookie("\163\164\x61\x74\145\x5f\x70\141\162\141\155", $Zi, time() + 60);
        $cm = new StorageManager($Zi);
        pQc:
        if (!isset($_COOKIE["\163\x74\141\164\x65\x5f\x70\141\x72\141\155"])) {
            goto bTY;
        }
        $Zi = $_COOKIE["\163\164\141\x74\x65\x5f\x70\141\162\x61\155"];
        bTY:
        if (!is_wp_error($cm)) {
            goto r6D;
        }
        wp_die(wp_kses($cm->get_error_message(), \mo_oauth_get_valid_html()));
        r6D:
        $vo = $cm->get_value("\165\151\144");
        if (!($vo && MO_UID === $vo)) {
            goto YS5;
        }
        $this->appname = $cm->get_value("\141\x70\x70\x6e\141\x6d\x65");
        return $cm;
        YS5:
        return false;
    }
    public function mo_oauth_login_validate()
    {
        if (isset($_REQUEST["\155\157\137\x6c\x6f\147\151\x6e\x5f\x70\157\160\165\160"]) && 1 == sanitize_text_field($_REQUEST["\155\157\x5f\x6c\157\x67\151\156\137\160\x6f\x70\165\x70"])) {
            goto zsV;
        }
        parent::mo_oauth_login_validate();
        global $Uj;
        if (!(isset($_REQUEST["\x74\157\x6b\145\x6e"]) && !empty($_REQUEST["\164\157\153\x65\156"]) || isset($_REQUEST["\151\144\x5f\x74\x6f\153\145\156"]) && !empty($_REQUEST["\x69\144\x5f\x74\x6f\153\x65\156"]))) {
            goto o3p;
        }
        if (!(isset($_REQUEST["\x74\x6f\153\145\x6e"]) && !empty($_REQUEST["\164\x6f\x6b\145\156"]))) {
            goto vbD;
        }
        $lB = $Uj->is_valid_jwt(urldecode($_REQUEST["\x74\x6f\x6b\x65\x6e"]));
        if ($lB) {
            goto mVU;
        }
        return;
        mVU:
        vbD:
        if (!(isset($_REQUEST["\156\157\156\143\x65"]) && (isset($_COOKIE["\155\157\137\x6f\x61\165\x74\x68\x5f\x6e\x6f\x6e\143\145"]) && $_COOKIE["\155\157\x5f\x6f\141\x75\164\150\137\156\x6f\156\143\x65"] != $_REQUEST["\x6e\x6f\x6e\143\x65"]))) {
            goto NdB;
        }
        wp_die("\x4e\157\156\x63\145\40\166\x65\x72\x69\146\151\x63\x61\164\151\157\156\x20\151\163\40\146\141\x69\x6c\145\144\56\x20\x50\x6c\x65\141\x73\145\x20\143\x6f\x6e\164\141\143\164\x20\x74\x6f\40\171\x6f\x75\162\40\x61\x64\x6d\x69\x6e\151\x73\x74\162\x61\x74\157\x72\56");
        exit;
        NdB:
        $iB = new Implicit(isset($_SERVER["\x51\125\105\122\131\x5f\123\x54\122\x49\116\x47"]) ? $_SERVER["\x51\x55\x45\x52\x59\137\x53\x54\x52\111\116\x47"] : '');
        if (!is_wp_error($iB)) {
            goto kEG;
        }
        $Uj->handle_error($iB->get_error_message());
        wp_die(wp_kses($iB->get_error_message(), \mo_oauth_get_valid_html()));
        MO_Oauth_Debug::mo_oauth_log("\x50\x6c\x65\x61\163\145\x20\x74\162\171\40\114\157\x67\x67\x69\x6e\x67\x20\151\156\x20\141\147\x61\151\x6e\56");
        exit("\x50\x6c\x65\x61\163\145\x20\164\162\x79\40\x4c\x6f\x67\x67\151\x6e\x67\x20\151\x6e\40\141\147\141\x69\156\x2e");
        kEG:
        $Ju = $iB->get_jwt_from_query_param();
        if (!is_wp_error($Ju)) {
            goto B2x;
        }
        $Uj->handle_error($Ju->get_error_message());
        MO_Oauth_Debug::mo_oauth_log($Ju->get_error_message());
        wp_die(wp_kses($Ju->get_error_message(), \mo_oauth_get_valid_html()));
        B2x:
        MO_Oauth_Debug::mo_oauth_log("\112\x57\124\x20\124\157\x6b\145\156\x20\165\x73\145\144\x20\146\157\162\40\x6f\x62\164\x61\151\156\x69\x6e\x67\x20\162\x65\x73\157\x75\x72\x63\x65\x20\157\x77\x6e\145\x72\40\x3d\x3e\x20");
        MO_Oauth_Debug::mo_oauth_log($Ju);
        $cm = $this->check_state($iB);
        if ($cm) {
            goto SQr;
        }
        $H3 = "\123\x74\141\164\145\40\x50\x61\162\141\155\145\x74\x65\162\x20\x64\x69\x64\40\156\157\x74\x20\166\x65\162\x69\146\171\x2e\x20\x50\154\x65\x61\x73\x65\x20\124\162\171\x20\114\157\147\147\x69\x6e\147\x20\151\x6e\x20\141\147\141\x69\156\56";
        $Uj->handle_error($H3);
        MO_Oauth_Debug::mo_oauth_log("\x53\x74\x61\164\x65\40\120\x61\162\141\x6d\145\x74\145\x72\x20\144\151\144\40\156\157\x74\40\x76\x65\x72\151\146\171\56\x20\x50\154\x65\x61\x73\x65\x20\124\x72\x79\40\x4c\x6f\147\147\x69\156\x67\40\x69\156\40\x61\x67\141\x69\x6e\61\56");
        wp_die($H3);
        SQr:
        $Wh = $Uj->get_app_by_name($this->app_name);
        $Wh = $Wh ? $Wh->get_app_config() : false;
        $Qu = $this->handle_jwt($Ju);
        MO_Oauth_Debug::mo_oauth_log("\122\145\x73\x6f\165\x72\x63\145\x20\117\x77\156\145\x72\x20\x3d\x3e\x20");
        MO_Oauth_Debug::mo_oauth_log($Qu);
        if (!is_wp_error($Qu)) {
            goto AIb;
        }
        $Uj->handle_error($Qu->get_error_message());
        wp_die(wp_kses($Qu->get_error_message(), \mo_oauth_get_valid_html()));
        AIb:
        if ($Wh) {
            goto tb9;
        }
        $a_ = "\123\x74\141\x74\145\x20\x50\141\162\x61\x6d\x65\164\145\162\x20\144\x69\144\40\x6e\x6f\164\x20\x76\145\162\151\146\171\x2e\40\x50\x6c\145\x61\163\x65\x20\124\x72\x79\40\114\157\x67\147\x69\156\147\x20\x69\x6e\x20\141\x67\x61\x69\156\62\x2e";
        $Uj->handle_error($a_);
        MO_Oauth_Debug::mo_oauth_log("\123\164\x61\x74\x65\40\x50\x61\x72\141\155\x65\164\145\162\x20\x64\x69\144\40\x6e\157\x74\x20\166\145\162\x69\x66\171\x2e\x20\x50\154\x65\x61\x73\145\x20\x54\x72\171\40\114\157\147\x67\151\x6e\147\x20\151\156\x20\141\147\141\x69\x6e\x2e");
        wp_die($a_);
        tb9:
        if ($Qu) {
            goto nsW;
        }
        $r_ = "\112\x57\x54\40\x53\x69\147\x6e\141\164\x75\162\x65\x20\x64\151\144\x20\x6e\157\x74\40\x76\x65\162\x69\146\171\x2e\40\120\154\x65\141\163\145\x20\124\x72\x79\x20\114\157\x67\147\x69\156\x67\x20\x69\156\40\x61\147\x61\x69\x6e\56";
        $Uj->handle_error($r_);
        MO_Oauth_Debug::mo_oauth_log("\112\127\x54\x20\123\x69\x67\156\141\x74\x75\162\145\40\x64\x69\x64\x20\x6e\157\x74\40\x76\x65\162\151\146\x79\x2e\40\x50\x6c\145\x61\x73\145\x20\124\162\x79\40\114\157\147\x67\x69\156\x67\x20\151\x6e\x20\141\147\141\x69\x6e\x2e");
        wp_die($r_);
        nsW:
        $ji = $cm->get_value("\164\x65\163\164\137\143\x6f\x6e\x66\151\x67");
        $this->resource_owner = $Qu;
        $this->handle_group_details($iB->get_query_param("\x61\x63\143\145\x73\163\137\164\157\153\145\156"), isset($Wh["\x67\162\157\x75\160\x64\x65\x74\141\x69\154\x73\x75\162\x6c"]) ? $Wh["\x67\x72\157\x75\x70\144\x65\164\141\151\154\163\x75\162\154"] : '', isset($Wh["\147\162\x6f\165\160\x6e\x61\x6d\145\137\141\x74\164\162\x69\142\x75\164\x65"]) ? $Wh["\x67\x72\157\165\x70\156\x61\x6d\145\137\x61\164\x74\x72\151\x62\x75\x74\145"] : '', $ji);
        $zn = [];
        $Rm = $this->dropdownattrmapping('', $Qu, $zn);
        $Uj->mo_oauth_client_update_option("\x6d\x6f\x5f\157\141\x75\164\x68\137\x61\x74\x74\x72\x5f\156\141\x6d\x65\x5f\x6c\x69\163\164" . $Wh["\141\x70\x70\x49\144"], $Rm);
        if (!($ji && '' !== $ji)) {
            goto GUP;
        }
        $this->render_test_config_output($Qu);
        exit;
        GUP:
        MO_Oauth_Debug::mo_oauth_log("\102\x65\146\157\162\x65\40\x68\141\x6e\x64\154\x65\x20\x73\x73\x6f\x31");
        $this->handle_sso($this->app_name, $Wh, $Qu, $cm->get_state(), $iB->get_query_param());
        o3p:
        if (!(isset($_REQUEST["\x68\x75\142\154\x65\164"]) || isset($_REQUEST["\x70\x6f\x72\164\141\154\x5f\144\157\x6d\141\151\x6e"]))) {
            goto Eo6;
        }
        return;
        Eo6:
        if (!(isset($_REQUEST["\141\143\x63\x65\x73\x73\x5f\x74\x6f\153\x65\x6e"]) && '' !== $_REQUEST["\x61\x63\x63\x65\x73\163\x5f\164\157\153\x65\156"])) {
            goto Qg_;
        }
        $iB = new Implicit(isset($_SERVER["\121\125\105\x52\x59\x5f\x53\x54\122\111\116\x47"]) ? $_SERVER["\x51\125\105\122\x59\137\x53\124\x52\x49\116\x47"] : '');
        $cm = $this->check_state($iB);
        if ($cm) {
            goto Iaa;
        }
        $H3 = "\x53\164\x61\x74\145\x20\120\141\x72\x61\x6d\x65\164\145\162\x20\x64\x69\x64\40\156\157\164\40\166\x65\x72\x69\x66\x79\56\40\x50\x6c\145\x61\x73\x65\x20\x54\x72\x79\40\114\x6f\x67\x67\151\x6e\x67\x20\x69\156\40\x61\x67\141\x69\x6e\x2e";
        $Uj->handle_error($H3);
        MO_Oauth_Debug::mo_oauth_log("\123\164\141\164\145\40\120\x61\x72\x61\x6d\145\x74\x65\x72\40\x64\x69\x64\x20\156\157\164\40\x76\x65\162\x69\146\x79\x2e\40\120\x6c\145\x61\x73\145\40\x54\x72\171\40\x4c\157\x67\147\151\156\x67\x20\x69\x6e\40\141\147\141\x69\x6e\62\56");
        wp_die($H3);
        Iaa:
        $Wh = $Uj->get_app_by_name($cm->get_value("\141\x70\x70\156\141\155\145"));
        $Wh = $Wh->get_app_config();
        $Qu = [];
        if (!(isset($Wh["\162\145\x73\157\165\162\x63\x65\157\167\x6e\x65\162\x64\145\164\141\151\154\x73\165\x72\x6c"]) && !empty($Wh["\162\145\x73\x6f\165\x72\143\x65\157\x77\156\145\162\x64\x65\x74\141\x69\154\x73\x75\x72\x6c"]))) {
            goto Hu6;
        }
        $Qu = $this->oauth_handler->get_resource_owner($Wh["\162\145\163\x6f\165\x72\143\145\x6f\167\x6e\x65\x72\144\x65\x74\x61\151\154\163\165\162\154"], $iB->get_query_param("\x61\143\143\145\x73\x73\x5f\164\157\153\x65\156"));
        Hu6:
        MO_Oauth_Debug::mo_oauth_log("\x41\143\143\145\x73\x73\40\x54\157\x6b\145\156\x20\75\76\40");
        MO_Oauth_Debug::mo_oauth_log($iB->get_query_param("\x61\143\143\145\163\x73\x5f\164\157\x6b\145\x6e"));
        $eK = [];
        if (!$Uj->is_valid_jwt($iB->get_query_param("\141\x63\x63\x65\x73\x73\137\164\157\153\x65\x6e"))) {
            goto RaP;
        }
        $Ju = $iB->get_jwt_from_query_param();
        $eK = $this->handle_jwt($Ju);
        RaP:
        if (empty($eK)) {
            goto ohv;
        }
        $Qu = array_merge($Qu, $eK);
        ohv:
        if (!(empty($Qu) && !$Uj->is_valid_jwt($iB->get_query_param("\x61\143\x63\x65\163\x73\x5f\x74\157\153\145\x6e")))) {
            goto aRr;
        }
        $Uj->handle_error("\x49\156\x76\x61\x6c\x69\x64\x20\x52\x65\x73\160\157\x6e\163\x65\40\x52\145\x63\x65\x69\x76\x65\x64\x2e");
        MO_Oauth_Debug::mo_oauth_log("\x49\x6e\x76\x61\154\x69\144\x20\x52\145\x73\160\157\x6e\163\145\x20\x52\x65\143\145\151\166\x65\x64");
        wp_die("\x49\156\166\141\154\x69\144\x20\x52\145\163\x70\157\156\x73\x65\40\122\x65\x63\x65\x69\166\x65\144\56");
        exit;
        aRr:
        $this->resource_owner = $Qu;
        MO_Oauth_Debug::mo_oauth_log("\x52\x65\x73\157\165\x72\x63\x65\40\x4f\x77\156\x65\x72\x20\x3d\x3e\40");
        MO_Oauth_Debug::mo_oauth_log($this->resource_owner);
        $ji = $cm->get_value("\164\145\163\164\x5f\143\x6f\x6e\x66\x69\x67");
        $this->handle_group_details($iB->get_query_param("\x61\x63\x63\x65\163\x73\137\x74\x6f\x6b\x65\x6e"), isset($Wh["\x67\x72\157\165\160\x64\x65\164\x61\x69\x6c\x73\x75\162\154"]) ? $Wh["\147\162\x6f\x75\x70\x64\145\164\x61\x69\x6c\x73\165\x72\x6c"] : '', isset($Wh["\147\x72\x6f\x75\160\x6e\x61\x6d\145\137\141\x74\164\x72\x69\x62\x75\164\x65"]) ? $Wh["\x67\162\x6f\x75\160\156\141\155\x65\x5f\x61\164\x74\162\x69\142\x75\164\145"] : '', $ji);
        $zn = [];
        $Rm = $this->dropdownattrmapping('', $Qu, $zn);
        $Uj->mo_oauth_client_update_option("\x6d\157\x5f\x6f\141\165\x74\x68\137\141\x74\x74\x72\137\x6e\141\155\x65\137\154\x69\x73\164" . $Wh["\x61\160\160\x49\x64"], $Rm);
        if (!($ji && '' !== $ji)) {
            goto YOy;
        }
        $this->render_test_config_output($Qu);
        exit;
        YOy:
        $Zi = str_replace("\x25\x33\104", "\75", rawurldecode($iB->get_query_param("\x73\x74\x61\x74\x65")));
        $this->handle_sso($this->app_name, $Wh, $Qu, $Zi, $iB->get_query_param());
        Qg_:
        if (!(isset($_REQUEST["\x6c\x6f\147\151\x6e"]) && "\x70\167\144\x67\162\156\x74\146\x72\155" === $_REQUEST["\x6c\157\147\x69\156"])) {
            goto vef;
        }
        $sC = new Password();
        $NG = isset($_REQUEST["\143\x61\154\x6c\145\x72"]) && !empty($_REQUEST["\x63\141\x6c\x6c\x65\162"]) ? $_REQUEST["\143\141\x6c\x6c\x65\162"] : false;
        $XL = isset($_REQUEST["\x74\157\157\x6c"]) && !empty($_REQUEST["\x74\x6f\157\x6c"]) ? $_REQUEST["\x74\157\157\154"] : false;
        $gR = isset($_REQUEST["\141\x70\160\x5f\156\x61\155\145"]) && !empty($_REQUEST["\x61\160\160\x5f\x6e\141\x6d\145"]) ? $_REQUEST["\141\x70\x70\137\156\141\x6d\145"] : '';
        if (!($gR == '')) {
            goto Xxg;
        }
        $eZ = "\116\x6f\x20\x73\165\143\x68\40\141\x70\160\40\x66\x6f\x75\x6e\x64\40\143\157\156\x66\151\x67\x75\x72\x65\144\56\40\x50\154\x65\x61\163\145\x20\x63\x68\x65\x63\x6b\x20\151\146\40\171\x6f\x75\40\x61\162\x65\40\x73\x65\156\x64\151\x6e\x67\x20\164\150\x65\x20\143\157\162\162\145\143\x74\40\x61\160\x70\154\151\143\x61\164\x69\157\x6e\x20\156\x61\x6d\x65";
        $Uj->handle_error($eZ);
        wp_die(wp_kses($eZ, \mo_oauth_get_valid_html()));
        exit;
        Xxg:
        $H5 = $Uj->mo_oauth_client_get_option("\x6d\157\137\x6f\x61\x75\164\x68\137\141\160\x70\x73\x5f\154\151\x73\x74");
        if (is_array($H5) && isset($H5[$gR])) {
            goto bwY;
        }
        $eZ = "\x4e\x6f\40\x73\165\x63\x68\x20\x61\x70\160\40\146\x6f\165\156\144\x20\x63\157\x6e\146\151\147\x75\x72\145\x64\56\x20\x50\x6c\x65\141\163\x65\40\x63\150\145\x63\153\x20\x69\146\40\171\x6f\x75\x20\x61\x72\145\x20\163\x65\x6e\144\151\x6e\x67\40\164\150\x65\x20\x63\x6f\x72\x72\145\x63\x74\x20\x61\160\x70\137\x6e\141\x6d\x65";
        $Uj->handle_error($eZ);
        wp_die(wp_kses($eZ, \mo_oauth_get_valid_html()));
        exit;
        bwY:
        $ne = isset($_REQUEST["\154\157\x63\141\164\x69\157\156"]) && !empty($_REQUEST["\x6c\x6f\x63\x61\x74\x69\157\x6e"]) ? $_REQUEST["\x6c\x6f\143\x61\164\151\x6f\156"] : site_url();
        $lG = isset($_REQUEST["\x74\x65\163\x74"]) && !empty($_REQUEST["\x74\x65\x73\x74"]);
        if (!(!$NG || !$XL || !$gR)) {
            goto pWw;
        }
        $Uj->redirect_user(urldecode($ne));
        pWw:
        do_action("\x6d\x6f\137\x6f\141\165\x74\150\x5f\x63\x75\163\164\x6f\x6d\137\x73\163\x6f", $NG, $XL, $gR, $ne, $lG);
        $sC->behave($NG, $XL, $gR, $ne, $lG);
        vef:
        goto i4P;
        zsV:
        echo "\x9\11\x9\x3c\163\143\x72\x69\x70\164\40\x74\171\160\x65\75\42\164\x65\170\164\x2f\x6a\x61\166\x61\163\143\x72\x69\160\164\42\76\15\xa\x9\11\x9\x76\141\x72\40\x62\141\x73\x65\x5f\x75\x72\154\40\x3d\x20\x22";
        echo site_url();
        echo "\42\x3b\15\xa\x9\x9\x9\166\x61\162\x20\141\x70\160\x5f\156\x61\x6d\x65\x20\x3d\40\x22";
        echo sanitize_text_field($_REQUEST["\141\x70\x70\x5f\156\141\155\145"]);
        echo "\x22\73\xd\12\x9\x9\x9\11\x76\141\162\x20\x6d\171\x57\x69\156\x64\157\167\x20\75\40\x77\x69\156\x64\157\x77\56\157\160\x65\156\x28\x20\x62\141\163\x65\137\165\x72\x6c\40\53\40\x27\x2f\77\x6f\x70\x74\151\157\x6e\75\157\141\165\164\150\x72\x65\x64\x69\x72\145\143\164\x26\x61\160\x70\x5f\156\x61\x6d\145\75\47\x20\53\40\141\160\160\x5f\x6e\141\155\145\x2c\40\47\x27\x2c\x20\47\167\x69\144\x74\150\x3d\65\60\60\x2c\x68\145\151\x67\x68\164\75\x35\60\x30\x27\x29\73\15\12\11\11\11\11\74\x2f\x73\143\x72\151\x70\x74\x3e\xd\xa\11\11\11\x9";
        i4P:
    }
    public function handle_group_details($AP = '', $NR = '', $yX = '', $ji = false)
    {
        $Ew = [];
        if (!('' === $AP || '' === $yX)) {
            goto Zrh;
        }
        return;
        Zrh:
        if (!('' !== $NR)) {
            goto j4d;
        }
        $Ew = $this->oauth_handler->get_resource_owner($NR, $AP);
        if (!(isset($_COOKIE["\155\x6f\x5f\x6f\x61\x75\x74\150\x5f\x74\x65\x73\164"]) && $_COOKIE["\155\157\x5f\157\141\165\x74\150\x5f\164\x65\x73\164"])) {
            goto Ni3;
        }
        if (!(is_array($Ew) && !empty($Ew))) {
            goto fxp;
        }
        $this->render_test_config_output($Ew, true);
        fxp:
        return;
        Ni3:
        j4d:
        $MF = $this->get_group_mapping_attribute($this->resource_owner, $Ew, $yX);
        $this->group_mapping_attr = '' !== $MF ? false : $MF;
    }
    public function get_group_mapping_attribute($Qu = array(), $Ew = array(), $yX = '')
    {
        global $Uj;
        $LO = '';
        if (!('' === $yX)) {
            goto BL2;
        }
        return '';
        BL2:
        if (isset($Ew) && !empty($Ew)) {
            goto CnL;
        }
        if (isset($Qu) && !empty($Qu)) {
            goto rCP;
        }
        goto bI0;
        CnL:
        $LO = $Uj->getnestedattribute($Ew, $yX);
        goto bI0;
        rCP:
        $LO = $Uj->getnestedattribute($Qu, $yX);
        bI0:
        return !empty($LO) ? $LO : '';
    }
    public function handle_jwt($Ju)
    {
        global $Uj;
        $Fr = $Uj->get_app_by_name($this->app_name);
        $BG = $Fr->get_app_config("\x6a\x77\x74\137\163\165\160\x70\x6f\x72\x74");
        if ($BG) {
            goto os7;
        }
        return $Ju->get_decoded_payload();
        os7:
        $QX = $Fr->get_app_config("\152\167\164\x5f\x61\x6c\147\x6f");
        if ($Ju->check_algo($QX)) {
            goto Aia;
        }
        return new \WP_Error("\151\156\166\x61\x6c\151\144\137\163\x69\x67\156", __("\x4a\x57\x54\x20\x53\x69\x67\156\151\x6e\x67\x20\141\154\147\x6f\162\x69\x74\x68\x6d\x20\151\163\40\156\x6f\164\x20\141\x6c\x6c\157\167\145\x64\40\x6f\162\40\165\x6e\163\x75\160\160\x6f\162\x74\x65\x64\56"));
        Aia:
        $cH = "\122\x53\101" === $QX ? $Fr->get_app_config("\170\x35\60\x39\x5f\x63\145\x72\164") : $Fr->get_app_config("\x63\154\x69\145\x6e\164\x5f\163\x65\x63\x72\x65\x74");
        $zV = $Fr->get_app_config("\152\x77\x6b\163\165\x72\154");
        $C2 = $zV ? $Ju->verify_from_jwks($zV) : $Ju->verify($cH);
        return !$C2 ? $C2 : $Ju->get_decoded_payload();
    }
    public function get_resource_owner_from_app($DU, $Fr)
    {
        global $Uj;
        $this->app_name = $Fr;
        $Ju = new JWTUtils($DU);
        if (!is_wp_error($Ju)) {
            goto Pqk;
        }
        $Uj->handle_error($Ju->get_error_message());
        wp_die($Ju);
        Pqk:
        $Qu = $this->handle_jwt($Ju);
        if (!is_wp_error($Qu)) {
            goto JmB;
        }
        $Uj->handle_error($Qu->get_error_message());
        wp_die($Qu);
        JmB:
        if (!(false === $Qu)) {
            goto pA_;
        }
        $Bl = "\x46\x61\x69\154\x65\144\40\x74\157\x20\166\145\x72\151\x66\x79\40\112\x57\x54\40\124\157\x6b\145\x6e\x2e\x20\x50\x6c\x65\x61\163\145\x20\x63\150\x65\x63\x6b\40\x79\x6f\165\162\40\143\157\156\x66\151\147\165\162\x61\x74\151\157\x6e\40\157\162\x20\143\157\156\164\x61\143\164\40\x79\157\165\162\x20\x41\x64\155\x69\156\x69\x73\x74\162\141\164\x6f\162\56";
        $Uj->handle_error($Bl);
        MO_Oauth_Debug::mo_oauth_log("\106\141\151\x6c\145\x64\x20\164\x6f\40\166\145\162\x69\x66\171\40\x4a\x57\124\40\124\157\153\x65\156\56\x20\120\x6c\145\141\x73\x65\x20\143\x68\x65\x63\x6b\x20\171\x6f\x75\x72\40\x63\157\x6e\146\x69\x67\x75\162\x61\164\151\x6f\x6e\x20\157\162\x20\143\157\x6e\164\x61\x63\x74\40\x79\157\165\x72\40\101\144\x6d\x69\x6e\151\x73\164\x72\141\x74\x6f\162\56");
        wp_die($Bl);
        pA_:
        return $Qu;
    }
}
