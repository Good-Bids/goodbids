<?php
/**
 * Plugin Name: OAuth Single Sign On - SSO (OAuth client)
 * Plugin URI: http://miniorange.com
 * Description: This plugin enables login to your WordPress site using OAuth apps like Google, Facebook, EVE Online and other.
 * Version: 40.5.2
 * Author: miniOrange
 * Author URI: https://www.miniorange.com
 * License: miniOrange
 */


require "\x5f\141\165\x74\x6f\154\x6f\141\x64\x2e\160\x68\160";
require_once "\x6d\157\x2d\x6f\141\x75\164\150\55\143\x6c\x69\x65\x6e\164\x2d\x70\x6c\x75\147\x69\x6e\x2d\x76\x65\162\x73\x69\157\156\55\165\x70\144\x61\x74\x65\x2e\x70\x68\x70";
require_once "\151\156\x63\154\x75\144\x65\163\57\x6c\151\x62\x2f\154\151\x63\x65\156\163\145\x2f\x61\165\x74\x6f\x6c\x6f\141\144\x65\x72\56\x70\x68\160";
define("\115\x4f\x5f\117\101\x55\124\x48\x5f\x50\122\x45\115\x49\125\115\137\x43\123\x53\x5f\112\123\137\126\x45\122\x53\111\117\116", mo_oauth_client_options_plugin_constants::Version);
use MoOauthClient\Base\BaseStructure;
use MoOauthClient\MOUtils;
use MoOauthClient\GrantTypes\JWTUtils;
use MoOauthClient\Base\InstanceHelper;
use MoOauthClient\MoOauthClientWidget;
use MoOauthClient\Free\MOCVisualTour;
use MoOauthClient\Free\CustomizationSettings;
use MoOauthClient\LoginHandler;
use MoOauthClient\LicenseLibrary\Classes\Mo_License_Library;
global $Yh;
$nQ = new InstanceHelper();
$Yh = $nQ->get_utils_instance();
$Wb = $Yh->get_plugin_config()->get_current_config();
$xW = $nQ->get_settings_instance();
$o1 = new BaseStructure();
$d_ = $nQ->get_login_handler_instance();
$HG = new CustomizationSettings();
$HG = $HG->mo_oauth_custom_icons_intiater();
$DR = new Mo_License_Library();
function register_mo_oauth_widget()
{
    register_widget("\x5c\x4d\x6f\x4f\x61\x75\x74\150\103\154\151\x65\156\x74\134\115\x6f\x4f\x61\165\164\x68\103\x6c\x69\x65\156\x74\x57\x69\144\147\x65\164");
}
function mo_oauth_shortcode_login($Sa)
{
    global $Yh;
    $Rj = new MoOauthClientWidget();
    if ($Yh->check_versi(4) && $Yh->mo_oauth_client_get_option("\x6d\157\137\x6f\141\x75\x74\x68\137\141\x63\164\x69\166\x61\164\145\x5f\x73\x69\x6e\x67\x6c\x65\x5f\x6c\x6f\147\x69\x6e\137\146\x6c\x6f\167")) {
        goto B8p;
    }
    if (!(!empty($Sa["\x72\x65\144\x69\162\145\x63\x74\x5f\x75\162\154"]) || !empty($Sa["\x62\x75\164\x74\x6f\156\x5f\x74\x65\170\x74"]))) {
        goto qFi;
    }
    $EJ = isset($Sa["\x72\x65\144\x69\x72\x65\x63\164\137\165\x72\x6c"]) ? $Sa["\162\145\x64\x69\162\145\x63\164\137\x75\x72\154"] : '';
    $Vh = isset($Sa["\142\165\x74\164\x6f\x6e\x5f\x74\x65\170\164"]) ? $Sa["\142\165\x74\164\157\x6e\x5f\x74\145\170\164"] : '';
    return $Sa && isset($Sa["\x61\160\160\156\x61\155\145"]) && !empty($Sa["\x61\160\x70\x6e\x61\x6d\145"]) ? $Rj->mo_oauth_login_form($Jz = true, $br = $Sa["\x61\x70\160\x6e\141\155\145"], $EJ, $Vh) : $Rj->mo_oauth_login_form($Jz = false, $br = '', $EJ, $Vh);
    qFi:
    return $Sa && isset($Sa["\141\160\160\x6e\141\x6d\145"]) && !empty($Sa["\141\x70\160\x6e\141\155\x65"]) ? $Rj->mo_oauth_login_form($Jz = true, $br = $Sa["\x61\x70\x70\x6e\141\x6d\x65"]) : $Rj->mo_oauth_login_form(false);
    goto vR4;
    B8p:
    return $Rj->mo_activate_single_login_flow_form();
    vR4:
}
add_action("\151\156\151\x74", "\155\x6f\137\x67\x65\x74\137\x76\145\x72\x73\x69\x6f\156\137\156\165\155\x62\x65\162");
add_action("\167\x69\144\147\145\164\x73\137\151\x6e\x69\164", "\162\145\x67\151\x73\164\x65\162\x5f\155\x6f\137\x6f\x61\x75\x74\150\x5f\167\151\144\147\x65\x74");
if (!($Yh->mo_oauth_aemoutcrahsaphtn() == "\144\151\163\141\x62\154\145\144")) {
    goto ZYx;
}
add_shortcode("\x6d\157\137\157\x61\x75\x74\x68\137\x6c\x6f\x67\x69\x6e", "\155\157\x5f\x6f\x61\x75\x74\x68\x5f\x73\x68\157\162\164\143\157\x64\145\137\154\157\147\151\x6e");
add_action("\x69\x6e\x69\164", "\155\157\x5f\157\x61\x75\x74\150\x5f\146\162\x6f\x6e\164\163\154\x6f");
add_action("\x69\156\x69\164", "\155\157\137\x6f\x61\x75\164\x68\x5f\142\141\x63\153\x73\154\157");
add_action("\x72\145\x73\x74\137\x61\x70\151\137\151\156\x69\164", function () {
    $yi = new LoginHandler();
    register_rest_route("\x6d\x6f\137\157\141\x75\164\150\137\x69\x64\160\137\x69\x6e\164\x69\x61\x74\x65", "\57\155\x6f\157\x69\x64\143\x63\x61\154\x6c\142\141\x63\153", array("\x6d\x65\x74\150\157\x64\x73" => "\107\105\124", "\x63\141\154\x6c\142\x61\143\153" => array($yi, "\155\157\x5f\157\x61\x75\x74\150\137\144\145\143\x69\144\145\137\146\x6c\157\x77"), "\x70\x65\162\155\151\163\x73\151\157\156\x5f\x63\141\x6c\x6c\142\x61\143\x6b" => "\137\x5f\x72\x65\164\165\162\156\137\x74\162\x75\x65"));
});
ZYx:
function mo_get_version_number()
{
    if (!(isset($_GET["\x61\143\x74\x69\x6f\156"]) && $_GET["\141\143\164\x69\x6f\x6e"] === "\x6d\x6f\x5f\x76\x65\x72\163\x69\x6f\156\x5f\x6e\x75\x6d\x62\x65\162" && isset($_GET["\x61\x70\x69\113\145\x79"]) && $_GET["\141\x70\x69\113\145\x79"] === "\x63\x32\x30\141\x37\x64\x66\70\66\142\63\x64\64\x64\61\x61\142\x65\62\144\64\x37\x64\60\145\61\142\61\146\x38\x34\67")) {
        goto zxm;
    }
    echo esc_attr(mo_oauth_client_options_plugin_constants::Version);
    exit;
    zxm:
}
function mo_oauth_frontslo()
{
    $Yh = new MOUtils();
    if (!($Yh->check_versi(4) && isset($_SERVER["\x52\105\121\125\x45\x53\x54\x5f\125\122\x49"]) && sanitize_text_field(wp_unslash($_SERVER["\122\105\121\125\105\123\124\x5f\x55\122\111"])) != NULL && strpos(sanitize_text_field(wp_unslash($_SERVER["\122\105\121\x55\105\123\x54\x5f\125\x52\x49"])), "\x66\x72\x6f\156\x74\143\150\x61\x6e\x6e\145\154\137\x6c\157\x67\157\x75\x74") != false)) {
        goto ocq;
    }
    $QH = get_current_user_id();
    $A2 = get_user_meta($QH, "\155\x6f\137\x6f\x61\165\x74\150\137\143\154\x69\145\x6e\x74\x5f\154\x61\x73\x74\x5f\151\x64\137\x74\157\153\x65\x6e", true);
    $gK = new JWTUtils($A2);
    $mO = $gK->get_decoded_payload();
    $Ws = sanitize_text_field(wp_unslash($_SERVER["\122\x45\121\125\105\123\124\137\x55\122\x49"]));
    $V0 = parse_url($Ws);
    parse_str($V0["\161\x75\145\162\171"], $Zn);
    $Lp = $mO["\163\151\144"];
    $u6 = $Zn["\x73\x69\144"];
    if ($Lp === $u6) {
        goto IIi;
    }
    $uh = array("\x63\x6f\144\145" => 400, "\x64\145\163\x63\x72\x69\160\164\x69\x6f\156" => "\x55\x73\145\x72\x20\x49\144\40\156\157\164\x20\x66\x6f\x75\x6e\x64");
    wp_send_json($uh, 400);
    goto pCr;
    IIi:
    $IZ = '';
    if (!isset($mO["\x69\141\x74"])) {
        goto fX0;
    }
    $IZ = $mO["\151\x61\x74"];
    fX0:
    if (!is_user_logged_in()) {
        goto RGE;
    }
    mo_slo_logout_user($QH);
    RGE:
    pCr:
    ocq:
}
function mo_oauth_backslo()
{
    $Yh = new MOUtils();
    if (!($Yh->check_versi(4) && isset($_SERVER["\122\x45\x51\125\x45\123\124\x5f\125\x52\x49"]) && sanitize_text_field(wp_unslash($_SERVER["\x52\105\121\125\105\123\124\x5f\125\122\111"])) != NULL && strpos(sanitize_text_field(wp_unslash($_SERVER["\122\x45\121\x55\x45\x53\124\137\125\122\111"])), "\142\141\143\x6b\143\x68\141\156\156\145\154\137\154\x6f\147\157\x75\x74") != false)) {
        goto ozc;
    }
    $fh = file_get_contents('php://input');
    $yT = explode("\75", $fh);
    if (!(json_last_error() !== JSON_ERROR_NONE)) {
        goto zhH;
    }
    $fh = array_map("\145\x73\143\137\x61\164\164\x72", sanitize_post($_POST));
    zhH:
    if ($yT[0] == "\x6c\157\x67\x6f\165\164\x5f\164\x6f\153\x65\x6e") {
        goto JZs;
    }
    $uh = array("\x63\x6f\144\x65" => 400, "\x64\145\x73\143\162\x69\x70\164\151\x6f\x6e" => "\x54\x68\145\x20\114\x6f\x67\157\165\164\40\x74\x6f\153\x65\x6e\40\151\x73\40\x65\x69\164\x68\145\162\40\x6e\x6f\164\x20\x73\x65\156\164\x20\157\x72\x20\x73\145\x6e\x74\x20\x69\156\143\x6f\x72\x72\145\x63\164\154\171\56");
    wp_send_json($uh, 400);
    goto YUt;
    JZs:
    $eh = $yT[1];
    $gK = new JWTUtils($eh);
    $d9 = isset($_REQUEST["\141\x70\x70\156\141\x6d\145"]) && sanitize_text_field(wp_unslash($_REQUEST["\x61\x70\160\x6e\x61\x6d\145"])) != NULL ? sanitize_text_field(wp_unslash($_REQUEST["\x61\160\160\x6e\141\155\x65"])) : '';
    $NA = false;
    $F8 = $Yh->get_app_by_name($d9);
    $NA = $F8->get_app_config("\152\x77\x6b\x73\x75\162\x6c");
    $sb = $F8->get_app_config("\165\163\145\x72\x6e\x61\155\145\x5f\x61\164\164\x72");
    $mO = $gK->get_decoded_payload();
    $MQ = '';
    $Lp = '';
    if (!isset($mO["\163\165\142"])) {
        goto ma0;
    }
    $MQ = $mO["\x73\x75\142"];
    ma0:
    if (!isset($mO["\x73\151\144"])) {
        goto gWp;
    }
    $Lp = $mO["\x73\x69\x64"];
    gWp:
    $IZ = '';
    if (!isset($mO["\151\141\164"])) {
        goto OX2;
    }
    $IZ = $mO["\151\x61\164"];
    OX2:
    global $wpdb;
    if (isset($mO[$sb])) {
        goto pll;
    }
    if ($MQ) {
        goto L54;
    }
    if ($Lp) {
        goto GSm;
    }
    $uh = array("\143\x6f\x64\x65" => 400, "\144\x65\x73\x63\162\151\x70\164\151\157\x6e" => "\124\150\x65\x20\154\x6f\x67\157\x75\x74\x20\x74\157\x6b\x65\156\x20\x69\x73\x20\x76\x61\x6c\151\x64\40\142\165\x74\x20\x75\163\x65\x72\40\x6e\x6f\164\x20\151\x64\x65\x6e\164\x69\x66\x69\145\144\56");
    wp_send_json($uh, 400);
    goto LyK;
    GSm:
    $B_ = "\x53\105\114\x45\x43\x54\x20\165\x73\145\x72\x5f\151\144\x20\106\122\x4f\x4d\x20\x60\x77\x70\x5f\x75\163\x65\x72\x6d\x65\x74\141\x60\40\127\x48\105\122\x45\x20\155\x65\164\x61\137\x76\x61\154\165\145\75\x27{$Lp}\x27\x20\x61\x6e\144\x20\x6d\x65\x74\141\x5f\153\145\171\75\x27\155\157\137\142\x61\143\153\143\x68\x61\156\x6e\x65\x6c\137\x61\x74\x74\162\x5f\x73\x69\x64\x27\73";
    $Pd = $wpdb->get_results($B_);
    $QH = $Pd[0]->{"\165\x73\x65\162\137\151\x64"};
    LyK:
    goto aFY;
    L54:
    $B_ = "\123\x45\x4c\105\x43\124\40\165\x73\x65\x72\137\x69\x64\x20\106\122\x4f\x4d\40\140\167\x70\x5f\x75\163\x65\162\155\145\164\141\x60\x20\127\110\105\122\105\40\x6d\145\164\141\137\166\x61\154\x75\145\x3d\47{$MQ}\47\x20\x61\156\144\40\x6d\145\164\x61\137\x6b\145\x79\75\47\155\x6f\x5f\142\x61\143\x6b\143\x68\141\x6e\156\145\x6c\x5f\141\164\x74\162\137\x73\x75\142\x27\x3b";
    $Pd = $wpdb->get_results($B_);
    $QH = $Pd[0]->{"\x75\163\x65\162\x5f\x69\144"};
    aFY:
    goto FXd;
    pll:
    $QH = get_user_by("\x6c\x6f\x67\x69\156", $WZ)->ID;
    FXd:
    if ($QH) {
        goto uJ8;
    }
    $uh = array("\143\x6f\144\145" => 400, "\144\x65\x73\143\162\x69\160\x74\x69\x6f\156" => "\x54\150\x65\x20\x6c\157\x67\157\165\164\x20\x74\157\153\145\156\x20\151\163\x20\x76\141\x6c\x69\x64\x20\x62\165\164\x20\165\163\x65\x72\x20\x6e\157\x74\40\x69\144\145\156\164\x69\x66\151\x65\144\x2e");
    wp_send_json($uh, 400);
    goto QM9;
    uJ8:
    mo_slo_logout_user($QH);
    QM9:
    YUt:
    ozc:
}
function mo_slo_logout_user($QH)
{
    $se = WP_Session_Tokens::get_instance($QH);
    $se->destroy_all();
    $uh = array("\143\157\x64\x65" => 200, "\x64\x65\x73\143\x72\151\160\164\151\x6f\x6e" => "\x54\x68\145\x20\x55\163\x65\x72\40\150\141\163\40\142\145\145\156\x20\x6c\x6f\x67\x67\x65\x64\x20\157\x75\164\x20\x73\x75\x63\x63\145\x73\x73\146\165\154\171\56");
    wp_send_json($uh, 200);
}
function miniorange_oauth_visual_tour()
{
    $VV = new MOCVisualTour();
}
if (!($Yh->get_versi() === 0)) {
    goto Axb;
}
add_action("\x61\x64\155\151\x6e\x5f\151\x6e\151\x74", "\x6d\151\x6e\x69\x6f\162\141\156\x67\x65\x5f\x6f\141\x75\164\150\137\x76\x69\163\165\x61\154\x5f\164\x6f\165\162");
Axb:
function mo_oauth_deactivate()
{
    global $Yh;
    do_action("\x6d\157\137\x63\x6c\x65\141\x72\137\160\x6c\165\147\x5f\x63\141\x63\x68\x65");
    $Yh->deactivate_plugin();
}
register_deactivation_hook(__FILE__, "\155\x6f\x5f\x6f\141\x75\164\x68\x5f\x64\x65\x61\x63\164\x69\166\x61\x74\x65");
