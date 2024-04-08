<?php


namespace MoOauthClient\Free;

class CustomizationSettings
{
    public function save_customization_settings()
    {
        global $Uj;
        $Kn = $Uj->get_plugin_config()->get_current_config();
        $MS = "\144\151\x73\141\142\x6c\x65\144";
        if (empty($Kn["\x6d\x6f\x5f\144\164\x65\137\163\x74\141\x74\145"])) {
            goto Ve;
        }
        $MS = $Uj->mooauthdecrypt($Kn["\x6d\x6f\137\144\164\x65\x5f\163\164\141\x74\x65"]);
        Ve:
        if (!($MS == "\144\151\x73\x61\142\154\x65\x64")) {
            goto ht;
        }
        if (!(isset($_POST["\155\157\x5f\157\141\x75\x74\150\x5f\141\x70\160\137\143\x75\x73\x74\157\155\x69\x7a\141\x74\x69\157\156\x5f\x6e\157\156\x63\145"]) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST["\x6d\x6f\137\157\x61\165\164\150\x5f\x61\160\x70\137\143\165\163\x74\157\x6d\151\172\141\x74\x69\157\x6e\x5f\156\x6f\x6e\143\x65"])), "\x6d\157\x5f\157\141\x75\164\x68\137\141\160\160\137\143\x75\163\164\157\x6d\151\x7a\x61\x74\x69\x6f\156") && isset($_POST[\MoOAuthConstants::OPTION]) && "\155\157\x5f\x6f\x61\x75\164\x68\137\141\160\160\x5f\x63\165\163\x74\x6f\155\x69\x7a\x61\x74\x69\x6f\156" === $_POST[\MoOAuthConstants::OPTION])) {
            goto mk;
        }
        $Uj->mo_oauth_client_update_option("\155\157\137\x6f\x61\x75\x74\x68\137\x69\x63\x6f\156\137\167\151\x64\x74\x68", stripslashes($_POST["\155\157\x5f\x6f\x61\165\164\x68\x5f\151\143\x6f\156\x5f\x77\151\x64\x74\x68"]));
        $Uj->mo_oauth_client_update_option("\x6d\x6f\137\x6f\141\165\x74\x68\x5f\151\x63\x6f\x6e\x5f\150\x65\x69\147\150\x74", stripslashes($_POST["\x6d\x6f\137\x6f\x61\x75\164\x68\137\x69\x63\x6f\156\137\150\145\151\x67\150\x74"]));
        $Uj->mo_oauth_client_update_option("\x6d\x6f\137\157\x61\x75\x74\150\137\151\143\x6f\156\x5f\x6d\141\x72\147\151\x6e", stripslashes($_POST["\155\x6f\x5f\157\141\165\164\150\x5f\x69\x63\x6f\x6e\137\155\141\162\147\x69\x6e"]));
        $Uj->mo_oauth_client_update_option("\x6d\157\x5f\157\141\x75\x74\x68\x5f\x69\143\157\156\x5f\x63\x6f\x6e\146\151\x67\x75\x72\145\137\143\x73\163", stripcslashes(stripslashes($_POST["\x6d\157\x5f\x6f\x61\165\164\150\137\x69\143\157\x6e\x5f\143\x6f\x6e\x66\151\x67\165\162\x65\x5f\143\x73\x73"])));
        $Uj->mo_oauth_client_update_option("\155\157\137\157\141\x75\x74\x68\x5f\x63\x75\x73\164\x6f\155\x5f\x6c\157\x67\x6f\165\164\x5f\x74\x65\x78\164", stripslashes($_POST["\x6d\x6f\137\x6f\x61\x75\164\150\137\143\165\x73\164\x6f\x6d\x5f\154\157\x67\x6f\165\x74\137\x74\145\x78\x74"]));
        $Uj->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\x59\157\x75\x72\x20\163\145\164\x74\x69\x6e\x67\x73\x20\x77\145\x72\145\40\x73\x61\x76\145\144");
        $Uj->mo_oauth_show_success_message();
        mk:
        ht:
    }
}
