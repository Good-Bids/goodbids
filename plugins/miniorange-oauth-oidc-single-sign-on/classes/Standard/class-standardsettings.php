<?php


namespace MoOauthClient\Standard;

use MoOauthClient\Free\FreeSettings;
use MoOauthClient\Free\CustomizationSettings;
use MoOauthClient\Standard\AppSettings;
use MoOauthClient\Standard\SignInSettingsSettings;
use MoOauthClient\Standard\Customer;
use MoOauthClient\App;
use MoOauthClient\Config;
use MoOauthClient\Widget\MOUtils;
use MoOauthClient\LicenseLibrary\Mo_License_Service;
use MoOauthClient\MO_Oauth_Debug;
use MoOauthClient\LicenseLibrary\Utils\Mo_License_Actions_Utility;
use MoOauthClient\LicenseLibrary\Classes\Mo_License_Constants;
class StandardSettings
{
    private $free_settings;
    public function __construct()
    {
        add_filter("\x63\x72\x6f\x6e\137\163\x63\150\145\144\x75\154\x65\x73", array($this, "\155\x6f\x5f\157\x61\x75\x74\x68\x5f\163\x63\150\x65\x64\165\154\x65"));
        if (wp_next_scheduled("\155\157\137\157\x61\x75\x74\x68\x5f\x73\143\x68\x65\144\x75\154\145")) {
            goto Z0r;
        }
        wp_schedule_event(time(), "\145\x76\x65\x72\x79\x5f\x6e\x5f\x6d\151\156\165\164\x65\x73", "\x6d\x6f\x5f\x6f\141\x75\x74\x68\137\163\x63\x68\145\144\x75\154\x65");
        Z0r:
        add_action("\155\157\137\157\141\165\x74\150\137\163\143\x68\145\x64\165\154\x65", array($this, "\145\x76\x65\162\171\x5f\163\x65\166\x65\x6e\x5f\x64\141\171\163\137\x65\x76\145\x6e\x74\137\146\x75\156\143"));
        $this->free_settings = new FreeSettings();
        add_action("\x61\x64\155\x69\x6e\137\x69\x6e\151\164", array($this, "\155\x6f\137\157\x61\x75\x74\x68\137\143\154\151\145\156\x74\137\x73\164\x61\x6e\x64\x61\162\x64\137\x73\x65\x74\164\x69\x6e\147\x73"));
        add_action("\144\x6f\x5f\x6d\x61\x69\x6e\137\x73\x65\x74\164\x69\156\147\163\x5f\151\x6e\x74\x65\162\x6e\141\x6c", array($this, "\x64\x6f\137\151\156\x74\145\162\156\x61\154\x5f\x73\x65\x74\x74\x69\x6e\x67\163"), 1, 10);
    }
    public function mo_oauth_schedule($Gt)
    {
        $Gt["\145\x76\x65\x72\x79\137\156\x5f\x6d\151\x6e\x75\x74\145\163"] = array("\x69\156\164\145\x72\x76\x61\x6c" => 60 * 60 * 24 * 7, "\144\151\163\x70\x6c\141\x79" => __("\105\166\145\x72\x79\x20\156\40\x4d\x69\x6e\165\x74\x65\163", "\164\145\170\x74\x64\x6f\155\141\x69\156"));
        return $Gt;
    }
    public function mo_oauth_update_license_expiry()
    {
        $this->every_seven_days_event_func();
    }
    public function every_seven_days_event_func()
    {
        global $Yh;
        $ao = new Customer();
        $OY = $ao->check_customer_ln();
        $OY = json_decode($OY, true);
        if (!is_multisite()) {
            goto iBC;
        }
        $Yh->mo_oauth_client_update_option("\x6e\157\117\x66\123\x75\142\123\x69\164\145\163", intval($OY["\x6e\157\x4f\146\123\x75\142\x53\x69\x74\x65\x73"]));
        iBC:
        $this->mo_oauth_initiate_expiration();
        $this->mo_oauth_initiate_license_domain_check($ao);
    }
    public function mo_oauth_initiate_expiration()
    {
        global $Yh;
        $xm = "\144\151\163\x61\x62\x6c\x65\144";
        $VR = new SignInSettingsSettings();
        $Wb = $VR->get_config_option();
        if (class_exists("\115\157\x4f\141\165\x74\x68\103\154\x69\x65\x6e\164\x5c\x4c\151\143\145\x6e\163\145\x4c\151\142\162\x61\x72\171\134\115\157\x5f\x4c\x69\x63\x65\x6e\x73\x65\x5f\x53\145\162\166\151\x63\x65")) {
            goto ZGH;
        }
        MO_Oauth_Debug::mo_oauth_log("\123\x75\x62\163\143\x72\x69\x70\164\151\x6f\156\x20\114\x69\x62\162\x61\162\x79\x20\x63\154\x61\163\163\40\x4d\157\137\x4c\151\143\145\x6e\163\x65\x5f\123\x65\x72\166\x69\143\145\40\x6e\x6f\x74\40\x66\157\165\156\144");
        goto SXq;
        ZGH:
        Mo_License_Service::refresh_license_expiry();
        if (!class_exists("\115\157\x4f\x61\x75\x74\150\x43\x6c\151\145\156\x74\134\x4c\x69\143\145\x6e\x73\145\x4c\151\142\x72\x61\162\171\134\125\164\151\154\163\x5c\x4d\157\x5f\114\x69\x63\145\156\x73\145\137\101\143\x74\x69\157\156\163\x5f\x55\x74\151\154\x69\x74\171")) {
            goto TA5;
        }
        $Wb->add_config("\155\x6f\x5f\144\164\145\x5f\144\x61\164\x61", $Yh->mooauthencrypt(Mo_License_Actions_Utility::fetch_license_expiry_date()));
        $VR->save_config_option($Wb);
        TA5:
        SXq:
    }
    public function mo_oauth_initiate_license_domain_check($ao)
    {
        global $Yh;
        $aF = $Yh->mo_oauth_client_get_option("\155\157\137\157\x61\165\164\x68\137\154\153");
        $g0 = $Yh->mooauthdecrypt($aF);
        $OY = json_decode($ao->XfskodsfhHJ($g0), true);
        if (isset($OY) && strcasecmp($OY["\x73\164\x61\x74\x75\x73"], "\x46\x41\x49\x4c\105\x44") === 0) {
            goto L7m;
        }
        if (isset($OY) && strcasecmp($OY["\x73\x74\141\x74\165\x73"], "\x53\125\x43\103\105\x53\x53") === 0) {
            goto Nki;
        }
        goto fbA;
        L7m:
        if (!(strcasecmp($OY["\x6d\145\163\163\x61\x67\x65"], "\103\x6f\x64\145\40\150\x61\163\40\105\x78\x70\x69\x72\x65\144") === 0)) {
            goto r9t;
        }
        $Yh->mo_oauth_client_update_option("\x6d\157\x5f\157\x61\165\164\150\137\x6c\x64", $Yh->mooauthencrypt("\x66\x61\154\x73\145"));
        r9t:
        goto fbA;
        Nki:
        $Yh->mo_oauth_client_update_option("\155\157\x5f\x6f\141\x75\x74\x68\x5f\x6c\144", $Yh->mooauthencrypt("\164\x72\x75\145"));
        fbA:
    }
    public function mo_oauth_client_standard_settings()
    {
        $JE = new CustomizationSettings();
        $VR = new SignInSettingsSettings();
        $f2 = new AppSettings();
        $JE->save_customization_settings();
        $f2->save_app_settings();
        $VR->mo_oauth_save_settings();
    }
    public function do_internal_settings($post)
    {
        global $Yh;
        if (!(isset($_POST["\x6d\157\x5f\157\x61\x75\x74\x68\137\x63\154\151\145\156\x74\x5f\166\145\x72\x69\x66\x79\137\x6c\x69\143\x65\156\163\x65\x5f\x6e\157\x6e\x63\x65"]) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST["\x6d\x6f\137\x6f\x61\x75\x74\150\137\143\154\x69\145\156\x74\137\x76\x65\162\151\146\x79\137\154\151\x63\x65\156\x73\145\137\156\157\x6e\x63\145"])), "\155\x6f\137\x6f\141\x75\x74\x68\137\143\x6c\151\x65\x6e\x74\137\166\x65\162\x69\x66\x79\137\x6c\x69\143\145\156\163\145") && isset($post[\MoOAuthConstants::OPTION]) && "\x6d\157\137\157\141\x75\164\150\x5f\143\x6c\151\145\156\164\137\166\145\162\x69\x66\x79\137\154\x69\143\145\156\163\145" === sanitize_text_field(wp_unslash($post[\MoOAuthConstants::OPTION])))) {
            goto VOq;
        }
        if (!current_user_can("\141\144\x6d\x69\x6e\151\x73\164\162\x61\x74\157\x72")) {
            goto o3f;
        }
        if (!(!isset($post["\x6d\x6f\137\x6f\141\165\164\150\x5f\143\x6c\x69\x65\156\x74\137\x6c\151\143\x65\156\x73\145\137\x6b\x65\x79"]) || empty($post["\155\x6f\137\157\x61\x75\164\x68\137\143\x6c\151\x65\x6e\164\x5f\x6c\x69\x63\x65\156\x73\145\137\153\x65\x79"]))) {
            goto cWS;
        }
        $Yh->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\120\154\145\x61\163\x65\40\145\156\x74\x65\162\40\x76\141\154\151\x64\x20\154\151\143\145\156\163\145\x20\153\145\x79\56");
        $this->mo_oauth_show_error_message();
        return;
        cWS:
        $g0 = trim($post["\x6d\x6f\x5f\157\x61\165\x74\150\x5f\143\x6c\151\145\156\164\x5f\x6c\x69\143\145\x6e\163\145\x5f\x6b\145\171"]);
        $ao = new Customer();
        $OY = json_decode($ao->check_customer_ln(), true);
        $jj = false;
        if (!(isset($OY["\151\163\x4d\165\154\164\x69\x53\x69\x74\x65\120\x6c\x75\147\x69\x6e\122\x65\161\x75\145\163\x74\x65\x64"]) && boolval($OY["\x69\163\115\x75\154\x74\151\123\x69\x74\145\120\x6c\165\147\151\x6e\122\x65\161\x75\x65\x73\164\145\x64"]) && is_multisite())) {
            goto H9c;
        }
        $jj = boolval($OY["\x69\163\x4d\x75\x6c\x74\151\123\x69\164\145\120\154\x75\x67\x69\x6e\x52\x65\161\x75\145\x73\x74\x65\144"]);
        $Yh->mo_oauth_client_update_option("\x6d\x6f\137\157\x61\x75\x74\x68\137\151\163\115\165\154\x74\151\123\x69\x74\x65\120\x6c\165\x67\x69\x6e\122\x65\x71\165\x65\x73\164\145\x64", $jj);
        $Yh->mo_oauth_client_update_option("\x6e\x6f\x4f\x66\123\x75\142\x53\x69\164\145\163", intval($OY["\156\x6f\x4f\x66\123\x75\x62\x53\151\x74\145\x73"]));
        H9c:
        $Kh = 0;
        if (!is_multisite()) {
            goto fRx;
        }
        if (!function_exists("\147\x65\x74\137\x73\x69\164\145\x73")) {
            goto hnt;
        }
        $Kh = count(get_sites(["\156\x75\155\x62\145\x72" => 1000])) - 1;
        hnt:
        fRx:
        if (!(is_multisite() && $jj && ($jj && (!array_key_exists("\156\157\x4f\x66\123\165\142\x53\151\164\145\163", $OY) && $Yh->is_multisite_versi())))) {
            goto Z05;
        }
        $Rp = $Yh->mo_oauth_client_get_option("\x68\x6f\x73\164\137\156\x61\x6d\x65");
        $Rp .= "\57\x6d\x6f\141\163\x2f\154\x6f\x67\151\156\x3f\162\145\x64\x69\x72\145\x63\x74\x55\162\x6c\75";
        $Rp .= $Yh->mo_oauth_client_get_option("\150\x6f\x73\164\137\x6e\141\x6d\145");
        $Rp .= "\57\x6d\x6f\x61\163\x2f\151\x6e\151\x74\151\141\x6c\151\x7a\145\x70\141\x79\155\x65\156\164\77\162\x65\161\x75\x65\163\164\x4f\x72\151\x67\151\x6e\x3d";
        $Rp .= "\167\x70\137\x6f\141\165\x74\150\137\143\154\151\145\156\164\x5f" . strtolower($Yh->get_versi_str()) . "\x5f\x70\x6c\x61\156";
        $Yh->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\x59\x6f\165\x20\x68\x61\166\x65\40\x6e\157\164\x20\x75\x70\147\162\141\144\145\x64\x20\x74\157\40\x74\x68\x65\40\x63\157\x72\x72\145\143\x74\x20\x6c\x69\143\x65\x6e\163\145\40\160\x6c\x61\156\x2e\x20\105\x69\164\150\x65\x72\x20\171\157\165\40\x68\141\166\145\x20\160\x75\162\x63\x68\141\163\145\x64\40\146\x6f\x72\x20\x69\156\143\x6f\162\162\145\x63\x74\x20\156\157\56\40\x6f\x66\x20\163\x69\164\x65\x73\40\157\x72\40\x79\x6f\x75\x20\x68\x61\166\x65\x20\x6e\x6f\x74\40\163\x65\154\145\143\x74\x65\x64\40\155\165\154\164\x69\163\151\164\145\x20\x6f\x70\x74\x69\157\x6e\x20\x77\150\x69\154\145\40\x70\165\162\143\150\141\x73\151\x6e\147\56\x20\x3c\x61\40\x74\141\162\147\x65\x74\x3d\42\137\142\x6c\141\x6e\x6b\42\40\150\162\145\x66\75\x22" . $Rp . "\42\x20\76\103\x6c\151\x63\x6b\40\x68\145\x72\x65\x3c\57\x61\76\x20\164\x6f\40\x75\x70\147\x72\x61\144\145\x20\x74\157\x20\160\162\x65\155\x69\x75\x6d\40\x76\x65\x72\x73\151\157\x6e\56");
        $Yh->mo_oauth_show_error_message();
        return;
        Z05:
        if (strcasecmp($OY["\163\x74\x61\164\165\163"], "\x53\125\103\103\105\x53\123") === 0) {
            goto Lpu;
        }
        $Yh->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\x59\157\165\40\150\141\166\145\x6e\47\164\40\x75\160\147\162\141\144\145\144\40\x74\157\x20\164\x68\x69\163\40\160\154\141\x6e\x20\171\x65\x74\56");
        $Yh->mo_oauth_show_error_message();
        goto xwl;
        Lpu:
        $OY = json_decode($ao->XfskodsfhHJ($g0), true);
        if (isset($OY)) {
            goto fV1;
        }
        $Yh->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\120\x6c\145\x61\x73\x65\x20\x63\x68\145\143\153\x20\151\x66\40\x79\x6f\x75\40\150\141\x76\x65\x20\145\x6e\164\145\x72\x65\144\x20\x61\x20\166\x61\x6c\151\x64\x20\x6c\x69\143\145\x6e\x73\x65\40\x6b\x65\x79");
        $Yh->mo_oauth_show_error_message();
        goto rYw;
        fV1:
        if (strcasecmp($OY["\x73\x74\x61\x74\165\163"], "\123\x55\103\x43\x45\x53\123") === 0) {
            goto B2W;
        }
        if (strcasecmp($OY["\163\164\141\x74\165\x73"], "\x46\x41\x49\x4c\105\x44") === 0) {
            goto SYe;
        }
        $Yh->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\101\x6e\x20\145\162\x72\157\162\x20\157\143\143\x75\162\x65\x64\40\x77\x68\151\154\x65\40\x70\x72\157\143\145\x73\x73\151\x6e\147\x20\x79\x6f\x75\162\x20\x72\145\x71\x75\x65\163\164\x2e\40\120\x6c\145\x61\x73\x65\x20\124\162\171\40\141\147\141\x69\x6e\56");
        $Yh->mo_oauth_show_error_message();
        goto jb9;
        B2W:
        $Yh->mo_oauth_client_update_option("\155\157\137\157\141\165\x74\150\x5f\x6c\x6b", $Yh->mooauthencrypt($g0));
        $Yh->mo_oauth_client_update_option("\155\x6f\137\x6f\141\x75\164\150\x5f\154\x76", $Yh->mooauthencrypt("\x74\x72\165\x65"));
        $this->mo_oauth_initiate_expiration();
        $mc = $Yh->get_app_list();
        if (!(!empty($mc) && is_array($mc))) {
            goto eMg;
        }
        foreach ($mc as $d9 => $zn) {
            if (is_array($zn) && !empty($zn)) {
                goto isx;
            }
            if (boolval($zn->get_app_config("\143\x6c\151\145\x6e\x74\137\x63\162\145\x64\x73\137\x65\156\x63\x72\160\171\164\x65\144"))) {
                goto mTq;
            }
            $z6 = $zn->get_app_config("\x63\154\x69\x65\156\164\x5f\151\144");
            !empty($z6) ? $zn->update_app_config("\143\x6c\x69\x65\x6e\x74\137\151\x64", $Yh->mooauthencrypt($z6)) : '';
            $MC = $zn->get_app_config("\x63\154\151\x65\x6e\164\137\163\145\x63\x72\145\x74");
            !empty($MC) ? $zn->update_app_config("\143\x6c\x69\x65\156\x74\137\163\x65\143\162\x65\x74", $Yh->mooauthencrypt($MC)) : '';
            $zn->update_app_config("\143\154\x69\145\x6e\164\x5f\143\162\145\x64\163\137\145\x6e\x63\162\160\x79\x74\x65\x64", true);
            mTq:
            $rF[$d9] = $zn;
            goto UHe;
            isx:
            if (!(!isset($zn["\x63\x6c\151\x65\156\164\x5f\x69\144"]) || empty($zn["\x63\x6c\x69\145\156\x74\137\151\x64"]))) {
                goto Hnv;
            }
            $zn["\x63\154\151\x65\156\x74\137\151\x64"] = isset($zn["\x63\x6c\151\x65\x6e\164\x69\144"]) ? $zn["\143\x6c\151\x65\156\164\151\144"] : '';
            Hnv:
            if (!(!isset($zn["\143\154\x69\x65\156\x74\137\x73\x65\x63\162\x65\x74"]) || empty($zn["\143\154\151\145\156\x74\x5f\x73\x65\143\x72\x65\x74"]))) {
                goto UaV;
            }
            $zn["\x63\x6c\151\x65\156\x74\x5f\163\x65\143\x72\145\164"] = isset($zn["\x63\154\151\x65\x6e\x74\x73\x65\143\x72\x65\x74"]) ? $zn["\143\154\x69\145\156\164\x73\x65\x63\x72\145\x74"] : '';
            UaV:
            unset($zn["\x63\154\151\x65\x6e\164\x69\x64"]);
            unset($zn["\143\x6c\x69\x65\156\164\x73\x65\143\162\145\164"]);
            if (!(!isset($zn["\x63\x6c\151\145\x6e\164\x5f\x63\x72\145\x64\163\x5f\145\x6e\143\x72\160\171\164\145\x64"]) || !boolval($zn["\143\154\151\x65\x6e\x74\137\x63\x72\145\x64\x73\x5f\x65\156\143\x72\x70\171\164\145\144"]))) {
                goto xnz;
            }
            isset($zn["\143\154\x69\145\x6e\164\137\151\x64"]) ? $zn["\143\x6c\151\x65\156\164\x5f\151\x64"] = $Yh->mooauthencrypt($zn["\x63\154\151\145\156\x74\137\151\x64"]) : '';
            isset($zn["\143\154\x69\x65\156\x74\x5f\x73\x65\143\x72\145\x74"]) ? $zn["\x63\x6c\x69\x65\156\164\137\163\x65\x63\x72\x65\x74"] = $Yh->mooauthencrypt($zn["\143\154\151\x65\x6e\x74\x5f\x73\145\x63\162\x65\164"]) : '';
            $zn["\143\154\151\x65\x6e\x74\x5f\x63\x72\145\144\163\137\x65\x6e\x63\x72\x70\171\164\x65\144"] = true;
            xnz:
            $F8 = new App();
            $F8->migrate_app($zn, $d9);
            $rF[$d9] = $F8;
            UHe:
            QMS:
        }
        ctF:
        eMg:
        !empty($mc) ? $Yh->mo_oauth_client_update_option("\x6d\x6f\x5f\157\x61\x75\x74\150\137\x61\160\160\163\137\x6c\151\163\164", $rF) : '';
        $Yh->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\x59\x6f\x75\x72\40\x6c\151\x63\145\156\163\x65\x20\x69\x73\40\166\145\162\151\146\151\x65\144\x2e\40\131\157\165\x20\143\x61\156\x20\156\x6f\x77\40\163\x65\164\165\x70\40\x74\x68\145\40\x70\x6c\165\147\x69\x6e\56");
        $Yh->mo_oauth_show_success_message();
        goto jb9;
        SYe:
        if (strcasecmp($OY["\155\x65\163\x73\x61\x67\145"], "\103\x6f\144\145\40\x68\141\163\40\x45\170\x70\151\162\x65\144") === 0) {
            goto ty2;
        }
        $Yh->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\131\157\x75\x20\x68\141\166\145\x20\x65\x6e\164\145\162\x65\144\x20\141\x6e\40\x69\156\x76\141\x6c\151\x64\x20\x6c\151\x63\145\x6e\163\145\40\153\x65\x79\x2e\40\120\x6c\145\x61\x73\x65\40\145\x6e\164\145\x72\x20\141\x20\x76\141\x6c\151\144\x20\154\x69\143\145\156\163\x65\x20\x6b\x65\x79\x2e");
        $Yh->mo_oauth_show_error_message();
        goto yaA;
        ty2:
        $Yh->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\114\x69\x63\x65\x6e\163\145\40\153\145\x79\x20\x79\x6f\x75\x20\x68\x61\166\x65\x20\x65\156\164\x65\x72\145\144\x20\150\141\163\40\x61\154\162\145\141\144\171\40\142\145\145\x6e\40\x75\163\x65\x64\56\x20\120\154\x65\x61\163\145\40\x65\x6e\164\x65\162\x20\141\40\x6b\145\x79\x20\x77\150\151\143\x68\40\150\141\163\x20\x6e\x6f\164\40\142\x65\x65\156\x20\x75\x73\x65\144\40\142\145\x66\x6f\x72\x65\40\157\156\40\x61\156\171\40\x6f\164\x68\x65\x72\40\x69\156\x73\x74\x61\156\x63\x65\x20\x6f\162\40\151\x66\x20\171\x6f\x75\40\x68\141\166\145\x20\145\170\x61\165\x73\164\145\144\x20\x61\x6c\x6c\x20\x79\157\165\x72\x20\x6b\x65\171\163\40\x74\x68\x65\156\40\142\x75\171\40\155\x6f\162\x65\56");
        $Yh->mo_oauth_show_error_message();
        yaA:
        jb9:
        rYw:
        xwl:
        o3f:
        VOq:
        if (!(isset($_POST["\x6d\157\137\x6f\141\x75\164\x68\x5f\x63\150\141\156\x67\145\137\154\151\x63\145\156\163\x65\x5f\x6e\157\156\143\145"]) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST["\155\157\137\x6f\141\x75\164\x68\137\143\x68\141\156\x67\145\x5f\154\151\143\x65\156\163\x65\137\156\157\156\x63\x65"])), "\155\157\137\157\141\165\x74\150\137\143\x68\141\x6e\147\145\x5f\154\x69\x63\x65\x6e\x73\x65") && isset($_POST[\MoOAuthConstants::OPTION]) && "\x63\x68\x61\156\x67\145\137\x6c\x69\143\145\156\x73\145" === sanitize_text_field(wp_unslash($_POST[\MoOAuthConstants::OPTION])) || isset($_POST["\155\157\137\x75\160\x64\141\x74\x65\x5f\x6c\151\143\145\x6e\x63\145\137\x73\164\141\x74\165\163\x5f\x62\x75\x74\x74\157\156"]) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST["\155\x6f\137\x75\160\x64\x61\164\145\137\x6c\151\143\145\x6e\x63\145\137\163\164\x61\x74\x75\x73\x5f\x62\165\x74\x74\x6f\156"])), "\155\157\x5f\157\x61\165\164\150\137\x75\x70\x64\x61\x74\145\137\154\151\143\x6e\x65\x73\145\x5f\163\x74\x61\x74\x75\163") && isset($_POST[\MoOAuthConstants::OPTION]) && "\x75\160\144\141\x74\145\137\x6c\151\x63\145\156\163\145\x5f\163\164\x61\164\165\163" === sanitize_text_field(wp_unslash($_POST[\MoOAuthConstants::OPTION])))) {
            goto RJK;
        }
        if (!current_user_can("\x61\144\x6d\x69\x6e\151\163\164\162\x61\164\157\162")) {
            goto yTg;
        }
        $this->mo_oauth_update_license_expiry();
        return;
        yTg:
        RJK:
        if (!(current_user_can("\x6d\x61\156\x61\x67\145\x5f\x6f\x70\x74\x69\157\x6e\x73") && !empty($_POST["\x6f\x70\x74\x69\x6f\x6e"]) && Mo_License_Constants::DASHBOARD_WIDGET_REFRESH_ID === $_POST["\x6f\x70\x74\x69\157\x6e"] && check_admin_referer(Mo_License_Constants::DASHBOARD_WIDGET_REFRESH_ID))) {
            goto xzF;
        }
        $this->mo_oauth_initiate_expiration();
        xzF:
    }
}
