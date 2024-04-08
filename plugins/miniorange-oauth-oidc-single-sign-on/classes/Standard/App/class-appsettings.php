<?php


namespace MoOauthClient\Standard;

use MoOauthClient\App;
use MoOauthClient\Free\AppSettings as FreeAppSettings;
class AppSettings extends FreeAppSettings
{
    public function __construct()
    {
        parent::__construct();
        add_action("\155\157\137\157\141\x75\164\150\137\143\154\151\145\x6e\x74\137\163\141\x76\145\137\141\160\160\137\x73\x65\x74\x74\x69\156\x67\x73\x5f\151\x6e\x74\x65\x72\156\141\154", array($this, "\x73\141\x76\145\x5f\x72\157\154\x65\x5f\x6d\141\x70\x70\151\156\147"));
    }
    public function change_app_settings($post, $Oj)
    {
        $Oj = parent::change_app_settings($post, $Oj);
        $Oj["\x64\x69\163\x70\154\x61\171\x61\160\160\x6e\141\x6d\145"] = isset($post["\x6d\157\137\157\x61\165\x74\150\x5f\144\151\163\160\154\141\x79\137\141\160\160\x5f\x6e\x61\x6d\x65"]) ? trim(stripslashes($post["\x6d\x6f\137\157\141\165\x74\x68\x5f\x64\x69\x73\x70\x6c\x61\171\137\x61\160\x70\137\156\141\155\x65"])) : '';
        return $Oj;
    }
    public function change_attribute_mapping($post, $Oj)
    {
        $Oj = parent::change_attribute_mapping($post, $Oj);
        $Oj["\x65\x6d\141\151\154\x5f\141\164\x74\162"] = isset($post["\155\x6f\137\157\x61\x75\164\150\137\x65\x6d\141\151\x6c\x5f\141\164\164\x72"]) ? stripslashes($post["\x6d\157\x5f\157\141\165\164\150\x5f\145\155\141\x69\x6c\x5f\x61\x74\164\162"]) : '';
        $Oj["\x66\x69\162\163\164\x6e\141\x6d\145\137\141\x74\x74\x72"] = isset($post["\155\x6f\x5f\x6f\141\x75\x74\150\137\x66\x69\x72\x73\x74\x6e\x61\x6d\145\x5f\141\164\x74\x72"]) ? trim(stripslashes($post["\x6d\x6f\137\x6f\x61\x75\164\150\137\146\x69\162\163\x74\156\141\155\145\x5f\141\x74\x74\162"])) : '';
        $Oj["\x6c\x61\x73\164\156\141\155\145\x5f\x61\164\164\162"] = isset($post["\x6d\x6f\137\x6f\141\x75\164\x68\137\x6c\141\163\x74\156\x61\155\x65\137\141\164\x74\162"]) ? trim(stripslashes($post["\x6d\x6f\x5f\157\x61\165\164\x68\x5f\154\141\163\x74\156\x61\x6d\145\x5f\141\164\x74\x72"])) : '';
        $Oj["\x65\156\141\142\154\x65\x5f\x72\157\x6c\x65\137\155\x61\160\160\x69\x6e\147"] = isset($post["\145\x6e\141\x62\x6c\x65\x5f\162\x6f\154\145\137\x6d\x61\160\x70\x69\156\x67"]) ? sanitize_text_field(wp_unslash($_POST["\145\x6e\x61\142\154\145\137\x72\x6f\154\x65\x5f\155\141\x70\x70\151\156\x67"])) : false;
        $Oj["\x61\x6c\x6c\157\x77\137\x64\x75\x70\154\x69\x63\141\164\x65\x5f\145\x6d\141\151\x6c\x73"] = isset($post["\141\154\x6c\x6f\167\x5f\x64\x75\160\154\x69\x63\141\x74\145\137\x65\x6d\x61\x69\x6c\163"]) ? sanitize_text_field(wp_unslash($_POST["\141\x6c\154\157\x77\137\x64\165\160\x6c\x69\143\x61\164\145\x5f\145\x6d\x61\x69\x6c\163"])) : false;
        $Oj["\x64\x69\163\160\154\141\x79\137\141\164\164\x72"] = isset($post["\x6f\x61\165\x74\x68\137\143\154\x69\145\x6e\x74\x5f\141\x6d\137\144\151\163\160\154\141\171\137\x6e\141\155\x65"]) ? trim(stripslashes($post["\x6f\x61\x75\164\x68\x5f\143\154\151\145\x6e\x74\137\141\x6d\137\144\151\x73\x70\x6c\141\x79\x5f\x6e\141\155\x65"])) : '';
        return $Oj;
    }
    public function save_role_mapping()
    {
        global $Uj;
        $Kn = $Uj->get_plugin_config()->get_current_config();
        $MS = "\144\151\163\141\142\154\x65\x64";
        if (empty($Kn["\155\x6f\137\x64\x74\145\137\x73\164\141\x74\x65"])) {
            goto os9;
        }
        $MS = $Uj->mooauthdecrypt($Kn["\x6d\x6f\137\x64\x74\145\137\x73\x74\141\x74\145"]);
        os9:
        if (!($MS == "\x64\x69\163\x61\142\154\145\x64")) {
            goto O75;
        }
        if (!(isset($_POST["\155\157\137\x6f\141\x75\164\x68\137\143\x6c\x69\145\156\x74\137\163\x61\166\145\137\162\157\x6c\x65\x5f\155\x61\x70\x70\151\x6e\x67\137\x6e\157\156\x63\x65"]) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST["\155\157\x5f\157\141\x75\164\150\x5f\x63\x6c\x69\x65\x6e\x74\x5f\163\x61\166\145\x5f\162\x6f\x6c\145\x5f\155\141\160\160\151\x6e\x67\x5f\156\x6f\156\x63\145"])), "\155\157\x5f\x6f\141\165\164\150\137\143\x6c\x69\145\156\x74\x5f\x73\141\166\145\x5f\162\x6f\154\145\137\155\x61\x70\x70\x69\156\x67") && isset($_POST[\MoOAuthConstants::OPTION]) && "\x6d\x6f\x5f\157\x61\165\164\150\x5f\143\154\151\x65\x6e\x74\x5f\163\x61\166\145\x5f\162\157\154\145\x5f\155\141\160\160\151\156\x67" === $_POST[\MoOAuthConstants::OPTION])) {
            goto EM6;
        }
        error_log("\123\141\x76\151\x6e\x67\40\x72\157\154\x65\x20\155\141\x70\x70\151\156\x67\x20\151\x6e\40\x73\164\x61\156\x64\141\162\x64");
        $BW = sanitize_text_field(wp_unslash(isset($_POST[\MoOAuthConstants::POST_APP_NAME]) ? $_POST[\MoOAuthConstants::POST_APP_NAME] : ''));
        $Fr = $Uj->get_app_by_name($BW);
        $Wh = $Fr->get_app_config('', false);
        $Wh["\137\x6d\141\160\x70\x69\156\x67\x5f\x76\x61\x6c\165\x65\x5f\144\x65\146\141\165\x6c\164"] = isset($_POST["\x6d\141\x70\160\x69\156\147\x5f\x76\141\154\165\145\x5f\x64\x65\x66\141\165\x6c\164"]) ? sanitize_text_field(wp_unslash($_POST["\x6d\141\x70\160\x69\x6e\x67\x5f\166\x61\x6c\x75\x65\x5f\x64\x65\146\x61\x75\x6c\164"])) : false;
        $Wh["\x6b\x65\145\160\137\x65\x78\x69\163\164\151\x6e\147\137\x75\163\x65\x72\137\162\x6f\154\145\163"] = isset($_POST["\153\x65\145\160\x5f\145\170\151\163\x74\x69\x6e\147\137\x75\163\x65\x72\137\162\157\x6c\x65\163"]) ? sanitize_text_field(wp_unslash($_POST["\153\145\145\160\x5f\145\170\151\163\x74\151\156\x67\137\165\x73\x65\162\x5f\x72\x6f\154\145\163"])) : 0;
        $FO = $Uj->set_app_by_name($BW, $Wh);
        EM6:
        O75:
    }
}
