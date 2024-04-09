<?php


namespace MoOauthClient\Free;

use MoOauthClient\Customer;
class RequestfordemoSettings
{
    public function save_requestdemo_settings()
    {
        global $Yh;
        if (!(isset($_POST["\155\x6f\x5f\x6f\x61\x75\x74\x68\x5f\x61\x70\160\137\x72\x65\x71\165\145\163\x74\144\145\x6d\157\x5f\x6e\157\x6e\x63\145"]) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST["\155\x6f\137\157\141\x75\164\x68\137\x61\x70\160\x5f\162\145\x71\165\x65\163\164\144\x65\x6d\x6f\137\x6e\157\156\143\x65"])), "\155\x6f\137\157\141\x75\x74\150\137\141\x70\160\137\x72\145\161\x75\x65\x73\x74\144\145\x6d\x6f") && isset($_POST[\MoOAuthConstants::OPTION]) && "\155\x6f\x5f\157\141\x75\164\x68\x5f\x61\160\x70\x5f\x72\x65\161\x75\x65\x73\164\x64\145\x6d\x6f" === sanitize_text_field(wp_unslash($_POST[\MoOAuthConstants::OPTION])))) {
            goto zs;
        }
        if (!current_user_can("\141\x64\155\x69\156\x69\163\x74\x72\141\164\157\x72")) {
            goto oV;
        }
        $Mv = isset($_POST["\155\157\137\x6f\141\x75\164\x68\137\x63\x6c\151\x65\156\164\x5f\144\145\x6d\157\137\x65\155\x61\x69\154"]) ? sanitize_email(wp_unslash($_POST["\155\157\137\157\x61\165\x74\150\137\x63\154\151\x65\156\164\137\x64\x65\155\157\137\x65\155\x61\x69\x6c"])) : '';
        $hK = isset($_POST["\155\x6f\137\x6f\x61\165\164\x68\137\143\x6c\151\x65\x6e\164\137\x64\145\x6d\x6f\137\x70\x6c\141\x6e"]) ? sanitize_text_field(wp_unslash($_POST["\155\x6f\x5f\157\141\x75\164\x68\x5f\x63\154\151\x65\x6e\x74\x5f\144\x65\x6d\157\137\x70\154\x61\x6e"])) : '';
        $B_ = isset($_POST["\x6d\157\137\x6f\141\165\164\x68\x5f\143\154\151\x65\156\x74\x5f\x64\x65\155\157\x5f\x64\145\x73\143\162\151\x70\x74\151\x6f\x6e"]) ? sanitize_text_field(wp_unslash($_POST["\155\157\x5f\x6f\141\165\164\150\137\x63\154\151\145\x6e\x74\x5f\x64\145\155\157\x5f\x64\x65\163\x63\162\x69\x70\164\151\157\x6e"])) : '';
        $ao = new Customer();
        if ($Yh->mo_oauth_check_empty_or_null($Mv) || $Yh->mo_oauth_check_empty_or_null($hK)) {
            goto bH;
        }
        $jM = json_decode($ao->mo_oauth_send_demo_alert($Mv, $hK, $B_, "\127\120\40\x4f\x41\x75\x74\150\40\x53\151\x6e\147\154\x65\40\123\x69\147\x6e\40\x4f\x6e\40\104\145\155\157\x20\x52\x65\x71\165\145\163\x74\x20\x2d\x20" . $Mv), true);
        $Yh->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\124\150\141\156\153\163\x20\x66\x6f\162\x20\147\x65\x74\x74\x69\x6e\x67\40\151\x6e\x20\164\157\165\x63\x68\41\x20\127\x65\x20\163\150\x61\154\154\x20\147\145\164\40\x62\141\143\153\40\x74\157\40\x79\x6f\165\40\163\150\157\x72\164\154\x79\x2e");
        $Yh->mo_oauth_show_success_message();
        goto UG;
        bH:
        $Yh->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\120\154\145\141\x73\x65\40\146\x69\154\154\x20\165\x70\40\x45\155\x61\x69\x6c\x20\x66\x69\x65\x6c\144\40\x74\x6f\x20\163\x75\x62\x6d\x69\164\x20\171\157\x75\162\40\x71\165\x65\162\171\56");
        $Yh->mo_oauth_show_success_message();
        UG:
        oV:
        zs:
    }
}
