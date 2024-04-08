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
        add_action("\141\x64\x6d\x69\156\x5f\x69\156\x69\164", array($this, "\155\x6f\x5f\x6f\x61\165\x74\150\137\143\x6c\151\145\156\x74\137\146\162\x65\x65\137\163\145\x74\164\x69\x6e\x67\163"));
        add_action("\141\144\x6d\151\156\137\146\157\157\x74\145\x72", array($this, "\x6d\157\x5f\157\x61\x75\164\150\137\143\154\151\145\x6e\x74\137\146\x65\x65\144\142\x61\x63\153\137\162\x65\x71\165\145\163\x74"));
    }
    public function mo_oauth_client_free_settings()
    {
        global $Uj;
        $j8 = new CustomizationSettings();
        $ZK = new RequestfordemoSettings();
        $j8->save_customization_settings();
        $ZK->save_requestdemo_settings();
        $cy = new AppSettings();
        $cy->save_app_settings();
        if (!(isset($_POST["\x6d\157\137\x6f\141\165\164\x68\137\143\x6c\151\x65\156\x74\x5f\x66\145\x65\x64\142\141\143\x6b\137\x6e\157\x6e\143\145"]) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST["\155\157\x5f\157\141\165\164\x68\x5f\x63\x6c\151\x65\x6e\164\137\x66\x65\145\x64\142\x61\143\x6b\137\x6e\157\x6e\143\145"])), "\155\x6f\137\157\141\x75\x74\150\137\143\x6c\x69\x65\x6e\x74\137\x66\x65\x65\x64\142\141\143\153") && isset($_POST[\MoOAuthConstants::OPTION]) && "\x6d\157\137\157\x61\x75\x74\150\x5f\x63\x6c\151\145\x6e\164\137\146\x65\145\144\x62\x61\x63\153" === $_POST[\MoOAuthConstants::OPTION])) {
            goto oi;
        }
        $user = wp_get_current_user();
        $fU = "\x50\154\x75\147\x69\156\40\x44\x65\x61\x63\164\151\166\141\x74\x65\x64\72";
        $Qr = isset($_POST["\x64\x65\x61\143\x74\151\x76\x61\x74\x65\x5f\x72\145\x61\x73\157\x6e\137\x72\141\144\x69\x6f"]) ? sanitize_text_field(wp_unslash($_POST["\x64\145\x61\143\x74\151\x76\x61\164\x65\137\162\x65\141\163\157\x6e\x5f\162\x61\144\x69\157"])) : false;
        $Vx = isset($_POST["\x71\165\x65\x72\171\x5f\x66\145\x65\x64\x62\x61\143\x6b"]) ? sanitize_text_field(wp_unslash($_POST["\161\165\145\x72\171\x5f\x66\x65\x65\x64\142\141\x63\153"])) : false;
        if ($Qr) {
            goto G4;
        }
        $Uj->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\x50\x6c\145\x61\x73\x65\40\123\x65\154\x65\x63\x74\40\157\x6e\145\40\157\x66\x20\164\150\145\40\x72\145\x61\163\157\156\x73\40\54\x69\146\x20\171\157\x75\162\40\162\x65\x61\163\157\156\x20\151\163\40\156\x6f\x74\x20\155\x65\156\x74\151\x6f\x6e\x65\144\40\x70\154\145\x61\163\145\x20\163\145\154\145\x63\x74\40\117\x74\150\145\x72\40\x52\145\x61\x73\157\x6e\163");
        $Uj->mo_oauth_show_error_message();
        G4:
        $fU .= $Qr;
        if (!isset($Vx)) {
            goto Mj;
        }
        $fU .= "\x3a" . $Vx;
        Mj:
        $g3 = $Uj->mo_oauth_client_get_option("\155\x6f\137\157\141\165\164\150\x5f\141\x64\155\151\156\x5f\x65\155\x61\151\x6c");
        if (!($g3 == '')) {
            goto Ms;
        }
        $g3 = $user->user_email;
        Ms:
        $fm = $Uj->mo_oauth_client_get_option("\155\157\137\157\141\x75\x74\x68\137\x61\144\x6d\151\156\x5f\160\x68\157\x6e\x65");
        $xf = new Customer();
        $Cu = json_decode($xf->mo_oauth_send_email_alert($g3, $fm, $fU), true);
        deactivate_plugins(MOC_DIR . "\x6d\157\137\157\141\x75\x74\x68\x5f\163\145\x74\x74\151\x6e\147\x73\x2e\x70\150\x70");
        $Uj->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\124\x68\141\x6e\153\40\x79\157\x75\40\146\x6f\x72\40\x74\150\145\x20\x66\x65\x65\144\142\x61\143\x6b\x2e");
        $Uj->mo_oauth_show_success_message();
        oi:
        if (!(isset($_POST["\x6d\x6f\x5f\x6f\141\x75\164\x68\x5f\143\154\151\145\156\x74\x5f\163\x6b\151\x70\x5f\146\145\x65\x64\x62\141\143\x6b\137\x6e\x6f\x6e\143\145"]) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST["\155\157\137\157\x61\165\x74\150\137\143\x6c\x69\145\x6e\x74\137\163\153\x69\160\x5f\x66\x65\145\x64\x62\141\143\153\137\x6e\157\156\x63\145"])), "\155\157\x5f\157\x61\165\164\x68\137\x63\154\151\145\156\164\137\163\x6b\x69\x70\x5f\146\x65\145\x64\142\x61\143\153") && isset($_POST["\157\x70\x74\x69\x6f\x6e"]) && "\x6d\157\x5f\x6f\141\165\164\150\x5f\143\x6c\151\x65\156\164\x5f\163\153\x69\160\137\x66\x65\145\x64\142\x61\143\x6b" === $_POST["\x6f\160\164\151\x6f\156"])) {
            goto Pr;
        }
        deactivate_plugins(MOC_DIR . "\x6d\157\x5f\x6f\x61\x75\x74\x68\137\163\x65\x74\164\x69\156\147\163\56\160\150\x70");
        $Uj->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\x50\x6c\x75\147\x69\x6e\40\x44\x65\141\x63\x74\x69\x76\141\x74\x65\x64\56");
        $Uj->mo_oauth_show_success_message();
        Pr:
    }
    public function mo_oauth_client_feedback_request()
    {
        $rX = new \MoOauthClient\Free\Feedback();
        $rX->show_form();
    }
}
