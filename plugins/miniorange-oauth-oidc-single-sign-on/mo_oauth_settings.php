<?php
/**
 * Plugin Name: OAuth Single Sign On - SSO (OAuth client)
 * Plugin URI: http://miniorange.com
 * Description: This plugin enables login to your WordPress site using OAuth apps like Google, Facebook, EVE Online and other.
 * Version: 98.4.7
 * Author: miniOrange
 * Author URI: https://www.miniorange.com
 * License: miniOrange
 */


require "\137\x61\165\x74\x6f\154\x6f\x61\144\56\x70\150\160";
require_once "\155\157\55\x6f\x61\165\x74\x68\55\143\x6c\151\x65\x6e\164\55\x70\154\165\x67\x69\156\55\x76\x65\162\x73\151\x6f\156\55\x75\x70\x64\141\x74\x65\x2e\160\x68\x70";
use MoOauthClient\Base\BaseStructure;
use MoOauthClient\MOUtils;
use MoOauthClient\Base\InstanceHelper;
use MoOauthClient\MoOauthClientWidget;
use MoOauthClient\Free\MOCVisualTour;
global $Uj;
$xL = new InstanceHelper();
$Uj = $xL->get_utils_instance();
$Kn = $Uj->get_plugin_config()->get_current_config();
$Lb = $xL->get_settings_instance();
$gk = new BaseStructure();
$WC = $xL->get_login_handler_instance();
function register_mo_oauth_widget()
{
    register_widget("\134\x4d\x6f\x4f\x61\165\164\x68\x43\154\151\x65\x6e\x74\x5c\115\157\117\141\165\164\150\103\x6c\151\x65\156\x74\x57\151\144\147\x65\x74");
}
function mo_oauth_shortcode_login($e3)
{
    global $Uj;
    $YX = new MoOauthClientWidget();
    if ($Uj->check_versi(4) && $Uj->mo_oauth_client_get_option("\155\x6f\x5f\x6f\x61\165\164\150\x5f\x61\x63\164\151\166\x61\x74\x65\x5f\163\151\x6e\147\x6c\145\x5f\x6c\x6f\147\151\x6e\x5f\x66\154\157\x77")) {
        goto Q68;
    }
    if (empty($e3["\162\145\144\x69\162\145\143\x74\x5f\x75\x72\154"])) {
        goto XEn;
    }
    return $e3 && isset($e3["\x61\x70\x70\156\141\x6d\x65"]) && !empty($e3["\141\160\160\156\141\x6d\x65"]) ? $YX->mo_oauth_login_form($WK = true, $g2 = $e3["\141\x70\x70\x6e\x61\155\145"], $Ol = $e3["\162\x65\144\x69\x72\x65\x63\164\137\165\x72\x6c"]) : $YX->mo_oauth_login_form($WK = false, $g2 = '', $Ol = $e3["\x72\145\x64\151\162\145\x63\x74\137\x75\162\154"]);
    XEn:
    return $e3 && isset($e3["\141\x70\160\156\x61\x6d\145"]) && !empty($e3["\141\160\x70\156\141\155\145"]) ? $YX->mo_oauth_login_form($WK = true, $g2 = $e3["\141\160\160\156\x61\155\x65"]) : $YX->mo_oauth_login_form(false);
    goto S9l;
    Q68:
    return $YX->mo_activate_single_login_flow_form();
    S9l:
}
add_action("\x77\x69\x64\147\145\164\163\137\x69\x6e\151\x74", "\x72\x65\x67\151\163\x74\x65\162\x5f\155\x6f\x5f\x6f\141\165\164\150\x5f\x77\x69\x64\147\145\164");
add_shortcode("\155\157\x5f\157\141\165\x74\x68\137\154\x6f\147\151\156", "\155\x6f\137\157\141\165\x74\x68\137\163\x68\157\x72\x74\x63\157\x64\145\137\x6c\x6f\x67\x69\x6e");
add_action("\x69\x6e\x69\x74", "\x6d\x6f\137\x67\145\164\x5f\166\145\x72\x73\x69\157\156\x5f\156\x75\x6d\142\145\x72");
function mo_get_version_number()
{
    if (!(isset($_GET["\x61\143\164\151\x6f\156"]) && $_GET["\x61\x63\x74\151\x6f\156"] === "\x6d\x6f\x5f\166\145\x72\x73\151\157\156\137\156\165\155\142\x65\x72" && isset($_GET["\x61\160\151\113\x65\x79"]) && $_GET["\x61\160\151\113\145\171"] === "\143\62\60\x61\x37\x64\146\x38\x36\142\63\144\64\144\61\141\x62\145\62\144\64\x37\x64\60\145\61\142\61\146\70\64\x37")) {
        goto dHO;
    }
    echo mo_oauth_client_options_plugin_constants::Version;
    exit;
    dHO:
}
function miniorange_oauth_visual_tour()
{
    $zW = new MOCVisualTour();
}
if (!($Uj->get_versi() === 0)) {
    goto d7V;
}
add_action("\141\x64\155\151\x6e\x5f\x69\x6e\x69\x74", "\x6d\151\156\x69\x6f\x72\x61\156\x67\x65\137\157\x61\165\164\150\137\x76\151\x73\x75\141\x6c\x5f\164\157\165\x72");
d7V:
function mo_oauth_deactivate()
{
    global $Uj;
    do_action("\x6d\x6f\137\x63\154\x65\141\162\137\160\154\x75\x67\x5f\143\141\x63\150\145");
    $Uj->deactivate_plugin();
}
register_deactivation_hook(__FILE__, "\155\157\137\x6f\x61\165\x74\x68\x5f\144\x65\x61\x63\164\x69\166\141\x74\x65");
