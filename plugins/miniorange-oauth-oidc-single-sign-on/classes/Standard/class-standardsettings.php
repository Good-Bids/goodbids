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
class StandardSettings
{
    private $free_settings;
    public function __construct()
    {
        add_filter("\x63\x72\157\156\137\x73\x63\150\145\x64\165\154\x65\x73", array($this, "\x6d\x6f\x5f\157\141\165\x74\150\x5f\x73\143\x68\x65\144\x75\x6c\x65"));
        if (wp_next_scheduled("\x6d\157\137\x6f\141\x75\164\x68\x5f\x73\143\x68\x65\x64\x75\154\x65")) {
            goto IPm;
        }
        wp_schedule_event(time(), "\x65\166\x65\162\x79\137\156\137\155\x69\156\x75\164\x65\x73", "\x6d\157\x5f\x6f\x61\x75\164\x68\137\x73\x63\150\145\x64\x75\x6c\x65");
        IPm:
        add_action("\155\x6f\x5f\x6f\x61\165\164\x68\137\x73\x63\x68\145\144\165\154\x65", array($this, "\x65\166\x65\162\171\x5f\163\x65\166\145\x6e\x5f\144\x61\x79\x73\x5f\x65\x76\145\x6e\x74\137\146\165\x6e\143"));
        $this->free_settings = new FreeSettings();
        add_action("\x61\x64\155\151\x6e\137\151\x6e\x69\164", array($this, "\155\157\x5f\x6f\x61\165\164\x68\137\x63\x6c\x69\145\x6e\164\137\x73\164\x61\x6e\x64\141\162\144\x5f\x73\145\x74\164\x69\x6e\x67\x73"));
        add_action("\x64\157\x5f\155\141\x69\156\x5f\163\x65\164\x74\151\x6e\x67\x73\x5f\151\156\164\145\162\156\x61\x6c", array($this, "\144\157\137\151\156\x74\145\162\156\141\154\x5f\x73\145\x74\164\151\156\147\x73"), 1, 10);
    }
    public function mo_oauth_schedule($OY)
    {
        $OY["\x65\x76\145\x72\171\x5f\x6e\x5f\155\x69\x6e\165\164\x65\163"] = array("\151\156\x74\145\162\166\x61\x6c" => 60 * 60 * 12, "\x64\x69\163\x70\154\141\x79" => __("\105\x76\145\x72\171\x20\156\40\115\151\x6e\165\164\145\163", "\x74\145\x78\x74\x64\157\x6d\x61\151\156"));
        return $OY;
    }
    public function every_seven_days_event_func()
    {
        global $Uj;
        $this->mo_oauth_initiate_expiration();
    }
    public function mo_oauth_initiate_expiration()
    {
        global $Uj;
        $YC = "\144\x69\x73\x61\142\154\145\x64";
        $fe = new SignInSettingsSettings();
        $Kn = $fe->get_config_option();
        $Vc = $Uj->mo_oauth_client_get_option("\155\x6f\137\157\141\165\x74\x68\137\x6c\x69\143\145\156\x73\145\137\x65\x78\160\x69\x72\171");
        $TG = false;
        $yJ = date("\x59\55\155\x2d\x64\40\x48\x3a\151\72\163");
        $Vc <= $yJ ? $TG = "\x65\156\141\142\x6c\x65\x64" : ($TG = "\144\151\163\141\x62\x6c\145\144");
        $Kn->add_config("\x6d\x6f\137\144\164\145\x5f\163\x74\141\164\145", $Uj->mooauthencrypt($TG));
        $Kn->add_config("\x6d\157\x5f\144\x74\145\137\144\141\164\x61", $Uj->mooauthencrypt($Vc));
        $fe->save_config_option($Kn);
    }
    public function mo_oauth_client_standard_settings()
    {
        $j8 = new CustomizationSettings();
        $fe = new SignInSettingsSettings();
        $cy = new AppSettings();
        $j8->save_customization_settings();
        $cy->save_app_settings();
        $fe->mo_oauth_save_settings();
    }
    public function do_internal_settings($post)
    {
        global $Uj;
        if (!(isset($_POST["\x6d\x6f\x5f\x6f\x61\x75\x74\x68\137\143\154\151\x65\156\164\137\x76\145\162\x69\x66\171\137\154\151\x63\x65\x6e\x73\x65\137\x6e\157\156\x63\x65"]) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST["\x6d\x6f\137\x6f\141\165\164\x68\x5f\143\154\151\145\x6e\x74\137\x76\x65\x72\151\x66\171\137\x6c\x69\143\145\x6e\x73\145\x5f\156\x6f\156\143\x65"])), "\155\x6f\137\157\x61\165\x74\150\x5f\x63\154\x69\145\156\164\x5f\166\145\162\x69\x66\171\137\154\x69\x63\145\156\x73\145") && isset($post[\MoOAuthConstants::OPTION]) && "\155\x6f\137\157\141\x75\164\150\137\x63\x6c\151\145\x6e\164\x5f\x76\145\162\151\146\x79\137\x6c\x69\143\x65\156\163\145" === $post[\MoOAuthConstants::OPTION])) {
            goto S1_;
        }
        if (!(!isset($post["\155\157\137\157\x61\165\x74\x68\137\x63\x6c\151\145\156\x74\137\154\151\143\145\x6e\x73\x65\x5f\153\145\x79"]) || empty($post["\155\157\137\157\141\165\x74\150\x5f\x63\x6c\151\x65\x6e\164\x5f\x6c\151\143\x65\x6e\x73\x65\137\153\145\x79"]))) {
            goto qKa;
        }
        $Uj->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\x50\x6c\145\x61\163\x65\x20\145\x6e\164\145\162\x20\x76\141\154\x69\x64\40\154\151\143\x65\x6e\x73\x65\40\x6b\x65\171\56");
        $this->mo_oauth_show_error_message();
        return;
        qKa:
        $SJ = trim($post["\x6d\x6f\137\157\141\x75\164\x68\137\143\154\151\145\156\164\x5f\x6c\x69\x63\x65\156\163\x65\137\153\145\171"]);
        $n9 = site_url();
        if (strpos($n9, "\x73\x74\x61\x67\151\156\x67\x2e\147\157\x6f\144\x62\151\x64\x73") !== false) {
            goto HWi;
        }
        $Uj->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\111\x6e\x76\x61\x6c\151\x64\x20\x6c\151\x63\145\156\163\x65\56\x20\x50\154\145\x61\163\x65\40\x74\162\171\40\x61\147\141\151\x6e\x2e");
        $Uj->mo_oauth_show_error_message();
        goto aT8;
        HWi:
        if (strcasecmp($SJ, "\63\167\x73\64\126\x37\x34\141\63\121\x7a\123\x73\130") === 0) {
            goto qDI;
        }
        $Uj->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\x41\x6e\40\145\x72\x72\157\162\40\157\143\143\165\x72\145\144\40\x77\150\151\x6c\145\x20\x70\162\x6f\x63\x65\x73\163\x69\x6e\x67\40\x79\x6f\x75\162\x20\162\x65\161\165\x65\x73\x74\56\40\120\154\145\141\x73\145\40\124\x72\x79\40\141\x67\141\x69\x6e\56");
        $Uj->mo_oauth_show_error_message();
        goto PBw;
        qDI:
        $Uj->mo_oauth_client_update_option("\155\x6f\x5f\157\141\x75\164\150\137\x6c\x6b", $Uj->mooauthencrypt($SJ));
        $Uj->mo_oauth_client_update_option("\x6d\x6f\x5f\157\x61\x75\x74\150\x5f\154\x69\143\145\x6e\x73\145\x5f\145\x78\x70\x69\x72\x79", "\62\60\x32\x34\x2d\60\x34\x2d\62\60\40\61\x31\72\x35\71\72\65\71");
        $Uj->mo_oauth_client_update_option("\155\157\137\157\x61\x75\164\x68\137\154\166", $Uj->mooauthencrypt("\164\162\165\x65"));
        $Uj->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\131\157\165\162\40\154\x69\x63\x65\156\163\x65\x20\x69\163\40\166\145\162\x69\x66\151\x65\144\56\x20\x59\157\165\40\x63\141\156\x20\x6e\157\x77\40\163\145\x74\x75\x70\x20\x74\150\x65\x20\x70\x6c\165\147\x69\156\56");
        $Uj->mo_oauth_show_success_message();
        PBw:
        aT8:
        S1_:
    }
}
