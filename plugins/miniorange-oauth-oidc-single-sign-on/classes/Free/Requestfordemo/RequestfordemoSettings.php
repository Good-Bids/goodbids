<?php


namespace MoOauthClient\Free;

use MoOauthClient\Customer;
class RequestfordemoSettings
{
    public function save_requestdemo_settings()
    {
        global $Uj;
        if (!(isset($_POST["\x6d\157\x5f\x6f\141\x75\x74\x68\x5f\141\x70\160\x5f\x72\145\x71\165\145\x73\x74\144\x65\x6d\157\x5f\156\157\156\x63\x65"]) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST["\x6d\157\137\x6f\x61\165\164\x68\x5f\141\160\160\x5f\162\145\x71\x75\x65\163\x74\x64\x65\x6d\x6f\x5f\x6e\157\156\x63\145"])), "\155\157\137\157\x61\165\x74\150\x5f\x61\160\160\137\162\145\161\165\x65\163\164\x64\x65\x6d\157") && isset($_POST[\MoOAuthConstants::OPTION]) && "\x6d\157\137\157\141\x75\164\150\x5f\x61\x70\x70\x5f\162\145\161\x75\x65\163\x74\144\x65\x6d\x6f" === $_POST[\MoOAuthConstants::OPTION])) {
            goto B2;
        }
        $g3 = $_POST["\155\x6f\x5f\157\x61\165\164\x68\137\143\154\151\x65\156\164\x5f\144\145\x6d\x6f\137\145\x6d\x61\151\x6c"];
        $fF = $_POST["\155\157\x5f\157\141\x75\164\150\137\x63\154\151\x65\156\x74\137\144\145\x6d\157\x5f\x70\154\x61\156"];
        $GQ = $_POST["\x6d\157\137\157\141\165\x74\150\137\x63\154\x69\x65\x6e\164\137\144\145\155\x6f\x5f\144\x65\x73\143\162\151\x70\x74\151\x6f\x6e"];
        $me = new Customer();
        if ($Uj->mo_oauth_check_empty_or_null($g3) || $Uj->mo_oauth_check_empty_or_null($fF)) {
            goto yD;
        }
        $Cu = json_decode($me->mo_oauth_send_demo_alert($g3, $fF, $GQ, "\127\x50\40\x4f\101\x75\x74\150\40\123\x69\x6e\x67\x6c\x65\40\123\151\147\x6e\x20\x4f\156\x20\x44\x65\x6d\157\40\122\x65\x71\x75\145\163\x74\x20\x2d\40" . $g3), true);
        $Uj->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\x54\x68\141\x6e\x6b\x73\x20\x66\157\162\40\147\145\164\164\151\156\x67\40\151\156\x20\164\157\x75\143\x68\41\x20\x57\x65\40\163\x68\141\154\154\40\147\x65\x74\x20\142\x61\143\x6b\40\x74\x6f\40\171\157\x75\x20\x73\x68\x6f\x72\x74\154\171\56");
        $Uj->mo_oauth_show_success_message();
        goto YA;
        yD:
        $Uj->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\x50\154\x65\141\163\x65\40\146\151\x6c\x6c\x20\x75\160\x20\105\155\x61\x69\x6c\x20\146\151\145\154\144\x20\164\157\x20\x73\165\142\x6d\151\x74\x20\x79\x6f\165\x72\40\161\x75\x65\162\x79\56");
        $Uj->mo_oauth_show_success_message();
        YA:
        B2:
    }
}
