<?php


namespace MoOauthClient\Standard;

use MoOauthClient\App;
use MoOauthClient\Free\AppSettings as FreeAppSettings;
class AppSettings extends FreeAppSettings
{
    public function __construct()
    {
        parent::__construct();
        add_action("\155\157\137\157\141\x75\164\150\137\x63\154\151\x65\x6e\x74\x5f\x73\x61\166\x65\137\141\x70\160\137\163\x65\164\x74\x69\x6e\x67\163\x5f\x69\x6e\x74\145\x72\x6e\x61\154", array($this, "\x73\x61\166\145\x5f\162\157\154\x65\x5f\x6d\141\x70\x70\x69\156\x67"));
    }
    public function change_app_settings($post, $eL)
    {
        $eL = parent::change_app_settings($post, $eL);
        $eL["\x64\x69\x73\x70\x6c\141\171\x61\x70\x70\x6e\x61\x6d\x65"] = isset($post["\155\x6f\137\x6f\x61\x75\164\x68\137\x64\x69\163\160\154\x61\171\137\x61\160\160\137\156\141\x6d\145"]) ? trim(stripslashes($post["\155\x6f\137\x6f\141\165\164\150\x5f\x64\x69\x73\x70\x6c\x61\171\x5f\141\160\160\x5f\156\x61\155\145"])) : '';
        return $eL;
    }
    public function change_attribute_mapping($post, $eL)
    {
        $eL = parent::change_attribute_mapping($post, $eL);
        $eL["\145\155\141\151\154\137\x61\x74\x74\x72"] = isset($post["\x6d\157\137\157\141\165\x74\x68\137\x65\x6d\141\x69\154\137\141\164\164\162"]) ? trim(stripslashes($post["\155\157\137\157\x61\165\x74\x68\x5f\145\x6d\141\x69\154\137\141\x74\164\x72"])) : '';
        $eL["\146\151\x72\x73\x74\x6e\141\155\x65\137\x61\x74\x74\162"] = isset($post["\x6d\157\x5f\x6f\x61\x75\164\x68\x5f\146\x69\x72\x73\164\x6e\141\x6d\x65\137\141\164\164\x72"]) ? trim(stripslashes($post["\155\x6f\x5f\x6f\141\165\x74\150\x5f\x66\x69\x72\163\x74\x6e\x61\x6d\145\x5f\x61\x74\x74\162"])) : '';
        $eL["\x6c\141\163\164\x6e\141\155\x65\137\x61\x74\x74\162"] = isset($post["\155\157\137\157\141\165\164\x68\x5f\154\141\163\164\156\x61\x6d\145\137\141\x74\x74\162"]) ? trim(stripslashes($post["\x6d\x6f\137\157\141\x75\x74\x68\x5f\154\141\163\164\x6e\141\x6d\x65\x5f\x61\x74\164\x72"])) : '';
        $eL["\145\x6e\141\x62\154\145\x5f\x72\x6f\x6c\145\x5f\x6d\141\160\x70\151\156\x67"] = isset($post["\145\156\141\142\x6c\145\x5f\x72\x6f\x6c\145\x5f\x6d\x61\x70\160\x69\x6e\147"]) ? trim(stripslashes($post["\145\156\x61\142\x6c\145\137\162\x6f\154\x65\137\x6d\x61\x70\x70\x69\x6e\x67"])) : false;
        $eL["\141\x6c\154\x6f\167\x5f\144\165\x70\x6c\151\x63\141\164\x65\x5f\145\x6d\141\151\x6c\x73"] = isset($post["\x61\154\x6c\x6f\x77\x5f\x64\165\160\154\x69\143\141\x74\x65\x5f\145\155\141\151\x6c\163"]) ? trim(stripslashes($post["\x65\156\x61\142\x6c\145\x5f\x72\x6f\154\x65\137\x6d\x61\x70\x70\151\156\147"])) : false;
        $eL["\144\x69\163\x70\154\x61\x79\137\x61\164\164\162"] = isset($post["\x6f\x61\165\x74\x68\137\143\154\151\145\x6e\x74\137\141\155\137\x64\x69\163\x70\154\141\171\x5f\x6e\141\155\x65"]) ? trim(stripslashes($post["\x6f\141\x75\164\x68\x5f\143\154\151\145\x6e\164\x5f\141\x6d\137\144\151\x73\x70\154\x61\x79\137\x6e\141\x6d\x65"])) : '';
        return $eL;
    }
    public function save_role_mapping()
    {
        global $Yh;
        $Wb = $Yh->get_plugin_config()->get_current_config();
        $g9 = "\x64\151\163\141\142\154\x65\144";
        $g9 = $Yh->mo_oauth_aemoutcrahsaphtn();
        if (!($g9 == "\x64\x69\163\141\x62\154\x65\x64")) {
            goto lHN;
        }
        if (!(isset($_POST["\155\157\137\x6f\x61\165\164\x68\x5f\x63\x6c\151\x65\156\164\x5f\163\x61\166\145\137\162\x6f\154\x65\x5f\155\x61\x70\x70\x69\x6e\x67\x5f\x6e\157\156\x63\x65"]) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST["\x6d\x6f\x5f\157\141\x75\164\150\x5f\x63\154\x69\145\156\164\x5f\x73\141\x76\x65\x5f\162\157\154\145\137\155\141\x70\x70\151\x6e\147\137\156\157\156\143\145"])), "\155\157\x5f\x6f\141\x75\x74\x68\137\x63\x6c\x69\145\156\164\x5f\163\x61\x76\145\x5f\x72\x6f\154\145\x5f\x6d\x61\x70\x70\151\x6e\x67") && isset($_POST[\MoOAuthConstants::OPTION]) && "\x6d\x6f\137\157\x61\165\x74\x68\x5f\143\154\x69\145\x6e\164\137\163\141\166\145\137\x72\x6f\154\145\x5f\155\141\x70\x70\151\x6e\x67" === sanitize_text_field(wp_unslash($_POST[\MoOAuthConstants::OPTION])))) {
            goto W7v;
        }
        if (!current_user_can("\x61\144\x6d\151\156\x69\x73\164\x72\x61\164\x6f\x72")) {
            goto RDc;
        }
        $d9 = sanitize_text_field(wp_unslash(isset($_POST[\MoOAuthConstants::POST_APP_NAME]) ? $_POST[\MoOAuthConstants::POST_APP_NAME] : ''));
        $F8 = $Yh->get_app_by_name($d9);
        $KY = $F8->get_app_config('', false);
        $KY["\137\x6d\141\x70\160\x69\156\x67\137\166\x61\x6c\x75\x65\x5f\144\x65\x66\x61\x75\x6c\164"] = isset($_POST["\155\141\160\x70\151\x6e\x67\x5f\x76\141\x6c\165\x65\x5f\144\145\146\x61\x75\x6c\x74"]) ? sanitize_text_field(wp_unslash($_POST["\x6d\141\x70\x70\151\156\147\x5f\166\141\154\165\x65\137\x64\x65\x66\141\x75\x6c\x74"])) : false;
        $KY["\153\x65\145\x70\137\x65\x78\x69\x73\x74\151\x6e\x67\137\165\x73\x65\162\x5f\162\157\x6c\145\163"] = isset($_POST["\x6b\x65\x65\160\137\145\170\x69\163\x74\151\156\x67\137\x75\163\145\162\137\x72\x6f\154\x65\163"]) ? sanitize_text_field(wp_unslash($_POST["\153\145\145\x70\x5f\x65\170\x69\163\x74\x69\156\147\137\165\163\145\162\137\162\157\154\145\163"])) : 0;
        $uw = $Yh->set_app_by_name($d9, $KY);
        RDc:
        W7v:
        lHN:
    }
}
