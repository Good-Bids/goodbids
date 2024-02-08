<?php


namespace MoOauthClient\Free;

use MoOauthClient\Settings;
use MoOauthClient\Free\CustomizationSettings;
use MoOauthClient\Free\RequestfordemoSettings;
use MoOauthClient\Free\AppSettings;
use MoOauthClient\Customer;
class FreeSettings
{
    private $common_settings;
    public function __construct()
    {
        $this->common_settings = new Settings();
        add_action("\x61\144\155\x69\x6e\x5f\x69\156\x69\x74", array($this, "\x6d\157\137\x6f\141\x75\x74\x68\137\143\154\x69\145\156\x74\x5f\146\162\145\145\137\x73\x65\x74\164\151\x6e\x67\x73"));
        add_action("\141\144\155\151\x6e\x5f\146\157\157\164\145\x72", array($this, "\x6d\157\137\x6f\141\165\164\150\x5f\x63\x6c\151\145\156\164\x5f\x66\x65\145\x64\x62\141\143\x6b\137\x72\145\x71\165\x65\x73\x74"));
    }
    public function mo_oauth_client_free_settings()
    {
        global $Yh;
        $JE = new CustomizationSettings();
        $Rz = new RequestfordemoSettings();
        $JE->save_customization_settings();
        $Rz->save_requestdemo_settings();
        $f2 = new AppSettings();
        $f2->save_app_settings();
        if (!(isset($_POST["\x6d\x6f\x5f\157\141\165\164\150\137\x63\154\x69\145\156\164\137\146\145\145\x64\142\x61\x63\153\137\156\157\x6e\143\145"]) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST["\x6d\x6f\137\157\141\165\x74\x68\x5f\x63\154\151\x65\x6e\x74\137\146\145\x65\x64\142\x61\x63\x6b\x5f\156\x6f\x6e\x63\x65"])), "\x6d\x6f\x5f\157\x61\x75\x74\150\137\143\154\x69\145\x6e\164\x5f\146\x65\145\x64\x62\x61\143\153") && isset($_POST[\MoOAuthConstants::OPTION]) && "\155\157\137\157\x61\x75\x74\150\137\143\154\x69\x65\x6e\x74\x5f\x66\145\x65\x64\x62\x61\143\x6b" === sanitize_text_field(wp_unslash($_POST[\MoOAuthConstants::OPTION])))) {
            goto JD;
        }
        if (!current_user_can("\141\144\x6d\x69\156\x69\163\164\162\141\164\x6f\162")) {
            goto C8;
        }
        $user = wp_get_current_user();
        $ri = "\120\x6c\165\x67\x69\156\x20\104\x65\x61\x63\164\x69\166\x61\x74\145\144\72";
        $ET = isset($_POST["\144\x65\x61\143\164\151\x76\141\164\x65\137\162\145\x61\x73\157\156\x5f\162\x61\x64\x69\157"]) ? sanitize_text_field(wp_unslash($_POST["\144\145\x61\x63\x74\151\166\141\x74\x65\x5f\x72\145\x61\163\x6f\156\x5f\x72\141\144\151\x6f"])) : false;
        $oR = isset($_POST["\161\165\145\x72\x79\x5f\x66\145\x65\144\x62\141\x63\x6b"]) ? sanitize_text_field(wp_unslash($_POST["\161\165\x65\x72\171\137\x66\145\145\x64\142\141\143\153"])) : false;
        if ($ET) {
            goto Q8;
        }
        $Yh->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\120\154\145\141\163\145\x20\x53\145\x6c\145\143\164\x20\x6f\156\145\40\x6f\x66\x20\164\x68\145\x20\x72\x65\141\163\x6f\x6e\163\40\x2c\x69\146\x20\x79\157\x75\x72\40\x72\x65\141\163\x6f\156\40\151\163\40\x6e\x6f\x74\40\155\145\156\164\x69\157\156\145\x64\x20\160\x6c\x65\141\163\x65\x20\x73\145\154\x65\143\164\40\x4f\164\x68\145\162\x20\x52\145\x61\163\157\156\163");
        $Yh->mo_oauth_show_error_message();
        Q8:
        $ri .= $ET;
        if (!isset($oR)) {
            goto DY;
        }
        $ri .= "\x3a" . $oR;
        DY:
        $Mv = $Yh->mo_oauth_client_get_option("\155\x6f\x5f\x6f\x61\x75\164\150\137\x61\144\155\x69\x6e\x5f\145\x6d\141\151\154");
        if (!($Mv == '')) {
            goto MG;
        }
        $Mv = $user->user_email;
        MG:
        $ge = $Yh->mo_oauth_client_get_option("\155\x6f\x5f\x6f\x61\x75\x74\x68\x5f\141\144\155\151\x6e\x5f\x70\150\157\156\145");
        $Ra = new Customer();
        $jM = json_decode($Ra->mo_oauth_send_email_alert($Mv, $ge, $ri), true);
        deactivate_plugins(MOC_DIR . "\x6d\157\x5f\157\141\165\164\150\137\x73\145\x74\x74\x69\x6e\x67\163\56\160\x68\x70");
        $Yh->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\124\x68\x61\x6e\x6b\x20\x79\x6f\x75\40\x66\157\162\x20\x74\150\145\40\x66\145\145\144\x62\141\143\x6b\56");
        $Yh->mo_oauth_show_success_message();
        C8:
        JD:
        if (!(isset($_POST["\155\157\137\x6f\x61\165\x74\150\137\143\x6c\151\145\x6e\164\x5f\163\153\151\x70\137\x66\x65\145\x64\142\141\x63\153\x5f\156\157\156\143\x65"]) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST["\x6d\157\137\x6f\141\x75\x74\x68\x5f\143\154\151\145\156\164\137\163\153\151\160\137\146\145\x65\x64\142\x61\143\153\137\156\x6f\156\x63\145"])), "\x6d\157\137\x6f\141\x75\x74\x68\x5f\143\x6c\151\x65\x6e\164\x5f\163\x6b\x69\x70\137\146\145\x65\x64\142\x61\x63\153") && isset($_POST["\157\x70\164\x69\157\x6e"]) && "\x6d\x6f\137\157\x61\165\x74\x68\137\143\x6c\x69\x65\x6e\x74\137\x73\x6b\151\x70\x5f\x66\145\145\x64\x62\141\143\x6b" === sanitize_text_field(wp_unslash($_POST[\MoOAuthConstants::OPTION])))) {
            goto dj;
        }
        if (!current_user_can("\x61\144\x6d\151\156\x69\163\x74\162\141\164\157\162")) {
            goto ix;
        }
        deactivate_plugins(MOC_DIR . "\155\x6f\x5f\157\x61\165\x74\150\x5f\163\x65\x74\x74\151\156\147\163\x2e\160\150\x70");
        $Yh->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\120\154\x75\x67\151\x6e\x20\x44\x65\141\143\164\x69\x76\141\x74\x65\144\56");
        $Yh->mo_oauth_show_success_message();
        ix:
        dj:
    }
    public function mo_oauth_client_feedback_request()
    {
        $R_ = new \MoOauthClient\Free\Feedback();
        $R_->show_form();
    }
}
