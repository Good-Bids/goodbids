<?php


namespace MoOauthClient\Free;

class CustomizationSettings
{
    public function save_customization_settings()
    {
        global $Yh;
        $Wb = $Yh->get_plugin_config()->get_current_config();
        $g9 = "\144\151\163\141\x62\x6c\x65\x64";
        $g9 = $Yh->mo_oauth_aemoutcrahsaphtn();
        if (!($g9 == "\x64\151\163\141\142\154\x65\x64")) {
            goto aF;
        }
        if (!(isset($_POST["\x6d\x6f\x5f\x6f\x61\165\x74\x68\x5f\141\x70\x70\x5f\x63\165\163\164\x6f\x6d\151\172\x61\x74\x69\x6f\156\x5f\156\157\156\x63\x65"]) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST["\155\157\137\x6f\141\x75\x74\x68\137\141\x70\x70\x5f\143\x75\163\164\x6f\x6d\x69\x7a\141\x74\151\157\156\x5f\156\157\156\143\x65"])), "\155\157\x5f\157\x61\x75\x74\x68\137\141\x70\x70\137\143\x75\163\164\157\x6d\151\x7a\141\164\x69\x6f\x6e") && isset($_POST[\MoOAuthConstants::OPTION]) && "\x6d\x6f\137\x6f\141\x75\x74\x68\x5f\x61\160\x70\137\x63\165\x73\164\x6f\x6d\151\172\x61\x74\151\157\156" === sanitize_text_field(wp_unslash($_POST[\MoOAuthConstants::OPTION])))) {
            goto wv;
        }
        if (!current_user_can("\x61\144\x6d\x69\156\x69\x73\164\x72\x61\164\x6f\162")) {
            goto hr;
        }
        isset($_POST["\155\x6f\137\157\x61\165\x74\x68\137\151\x63\157\x6e\137\x74\x68\x65\x6d\145"]) ? $Yh->mo_oauth_client_update_option("\x6d\x6f\137\x6f\x61\x75\164\x68\137\x69\x63\x6f\x6e\x5f\164\150\145\x6d\145", sanitize_text_field(wp_unslash($_POST["\155\157\137\x6f\x61\x75\x74\150\x5f\x69\x63\x6f\156\137\x74\x68\x65\x6d\x65"]))) : $Yh->mo_oauth_client_update_option("\x6d\157\137\x6f\141\x75\x74\x68\x5f\151\x63\157\x6e\137\164\150\x65\155\145", '');
        isset($_POST["\x6d\157\137\157\x61\165\164\150\137\x69\x63\x6f\x6e\137\163\x68\x61\160\x65"]) ? $Yh->mo_oauth_client_update_option("\155\157\137\157\141\165\x74\x68\x5f\151\x63\157\156\x5f\163\150\x61\160\x65", sanitize_text_field(wp_unslash($_POST["\x6d\157\137\157\141\165\164\150\x5f\x69\143\157\x6e\x5f\x73\150\x61\x70\x65"]))) : $Yh->mo_oauth_client_update_option("\x6d\x6f\137\x6f\x61\x75\x74\x68\137\x69\143\x6f\156\137\163\150\141\x70\145", '');
        isset($_POST["\155\157\137\157\x61\x75\164\x68\137\x69\143\x6f\156\x5f\145\146\146\145\x63\164\x5f\x73\x63\141\154\145"]) ? $Yh->mo_oauth_client_update_option("\x6d\157\137\157\141\165\164\x68\137\151\x63\x6f\x6e\137\x65\x66\146\x65\x63\x74\137\x73\x63\141\x6c\x65", sanitize_text_field(wp_unslash($_POST["\155\x6f\137\x6f\141\x75\164\x68\x5f\x69\143\157\x6e\x5f\x65\x66\x66\145\x63\x74\137\163\x63\141\x6c\x65"]))) : $Yh->mo_oauth_client_update_option("\155\x6f\137\157\141\x75\x74\x68\137\151\143\x6f\156\137\145\x66\146\145\x63\x74\137\x73\143\141\154\145", '');
        isset($_POST["\155\157\x5f\157\x61\165\164\x68\x5f\x69\x63\157\156\137\145\x66\x66\x65\143\x74\137\x73\150\141\144\157\x77"]) ? $Yh->mo_oauth_client_update_option("\155\x6f\137\157\x61\165\164\x68\x5f\x69\143\157\x6e\137\145\146\x66\145\x63\164\x5f\163\x68\x61\144\157\167", sanitize_text_field(wp_unslash($_POST["\155\157\137\x6f\x61\165\x74\150\x5f\x69\143\x6f\x6e\x5f\145\146\146\145\x63\164\137\x73\150\x61\x64\157\x77"]))) : $Yh->mo_oauth_client_update_option("\x6d\157\x5f\157\x61\165\x74\x68\x5f\151\x63\x6f\156\137\x65\146\x66\x65\143\x74\x5f\163\x68\141\x64\x6f\x77", '');
        isset($_POST["\x6d\157\x5f\x6f\141\x75\164\x68\137\151\143\x6f\x6e\x5f\167\151\x64\x74\x68"]) ? $Yh->mo_oauth_client_update_option("\155\157\137\x6f\x61\165\x74\x68\137\x69\x63\157\x6e\137\x77\151\144\164\x68", sanitize_text_field(wp_unslash($_POST["\155\x6f\137\157\x61\x75\x74\x68\x5f\151\x63\x6f\x6e\137\167\x69\x64\164\150"]))) : $Yh->mo_oauth_client_update_option("\155\157\x5f\x6f\x61\165\164\x68\137\x69\x63\x6f\x6e\137\167\151\x64\164\x68", '');
        isset($_POST["\155\157\137\x6f\x61\x75\x74\150\137\151\143\x6f\156\137\150\145\151\147\x68\x74"]) ? $Yh->mo_oauth_client_update_option("\155\157\x5f\157\141\x75\x74\150\x5f\151\143\x6f\x6e\x5f\x68\x65\x69\x67\x68\164", sanitize_text_field(wp_unslash($_POST["\x6d\157\137\x6f\141\165\x74\x68\x5f\x69\x63\x6f\156\137\150\145\x69\147\x68\x74"]))) : $Yh->mo_oauth_client_update_option("\155\157\137\157\x61\165\x74\x68\137\x69\143\x6f\x6e\137\x68\145\x69\x67\x68\164", '');
        isset($_POST["\155\157\x5f\x6f\141\165\x74\150\x5f\x69\143\x6f\x6e\137\x63\x6f\154\157\x72"]) ? $Yh->mo_oauth_client_update_option("\155\x6f\x5f\x6f\x61\x75\x74\x68\x5f\x69\x63\157\x6e\137\x63\x6f\x6c\157\x72", sanitize_text_field(wp_unslash($_POST["\155\x6f\x5f\157\x61\x75\164\x68\137\151\143\x6f\x6e\x5f\x63\x6f\154\157\x72"]))) : $Yh->mo_oauth_client_update_option("\155\x6f\x5f\x6f\141\165\x74\150\137\151\143\x6f\x6e\x5f\x63\x6f\x6c\157\x72", '');
        isset($_POST["\x6d\x6f\x5f\x6f\x61\x75\164\150\x5f\x69\x63\157\156\137\x63\x75\163\164\157\x6d\137\x63\x6f\x6c\157\x72"]) ? $Yh->mo_oauth_client_update_option("\155\157\x5f\x6f\x61\165\x74\x68\137\x69\143\157\156\x5f\x63\x75\x73\x74\x6f\155\137\x63\157\154\157\x72", sanitize_text_field(wp_unslash($_POST["\155\x6f\x5f\x6f\141\165\164\x68\x5f\151\x63\157\156\137\143\165\163\164\x6f\155\x5f\143\x6f\154\x6f\x72"]))) : $Yh->mo_oauth_client_update_option("\155\x6f\137\x6f\x61\x75\164\x68\x5f\151\x63\x6f\x6e\137\x63\165\163\164\x6f\x6d\137\143\x6f\154\157\162", '');
        isset($_POST["\x6d\x6f\137\x6f\x61\x75\x74\150\137\x69\x63\157\x6e\137\x73\x6d\141\162\164\x5f\x63\157\154\x6f\x72\137\61"]) ? $Yh->mo_oauth_client_update_option("\155\157\137\x6f\141\x75\164\150\x5f\151\x63\157\x6e\137\x73\x6d\x61\x72\164\137\143\x6f\x6c\157\162\137\61", sanitize_text_field(wp_unslash($_POST["\x6d\157\137\157\x61\165\164\150\137\151\143\x6f\x6e\137\x73\x6d\x61\162\164\137\x63\x6f\154\x6f\x72\x5f\61"]))) : $Yh->mo_oauth_client_update_option("\155\x6f\137\x6f\141\165\164\x68\137\151\143\157\x6e\x5f\163\x6d\141\x72\164\137\x63\x6f\154\157\x72\137\x31", '');
        isset($_POST["\155\x6f\x5f\x6f\x61\165\x74\x68\x5f\151\x63\x6f\x6e\137\x73\x6d\x61\162\164\x5f\143\157\x6c\157\162\x5f\62"]) ? $Yh->mo_oauth_client_update_option("\155\157\x5f\x6f\141\165\164\150\x5f\x69\143\157\x6e\x5f\163\155\141\x72\x74\137\x63\157\x6c\x6f\x72\137\62", sanitize_text_field(wp_unslash($_POST["\155\x6f\137\x6f\x61\165\164\150\x5f\x69\x63\x6f\x6e\137\163\x6d\141\x72\x74\137\143\x6f\x6c\x6f\x72\x5f\x32"]))) : $Yh->mo_oauth_client_update_option("\x6d\157\137\157\141\x75\x74\150\x5f\x69\x63\x6f\156\x5f\x73\x6d\x61\162\164\x5f\143\x6f\154\157\162\x5f\x32", '');
        isset($_POST["\155\157\137\157\x61\x75\x74\x68\x5f\x69\143\157\x6e\x5f\x63\165\x72\x76\x65"]) ? $Yh->mo_oauth_client_update_option("\x6d\157\x5f\157\x61\x75\164\150\137\151\x63\x6f\156\137\x63\x75\x72\x76\145", sanitize_text_field(wp_unslash($_POST["\x6d\x6f\x5f\157\141\165\x74\x68\x5f\151\143\157\x6e\x5f\x63\165\x72\x76\x65"]))) : $Yh->mo_oauth_client_update_option("\155\157\137\157\141\165\164\150\137\151\x63\157\156\x5f\x63\165\x72\166\x65", '');
        isset($_POST["\x6d\157\137\157\x61\165\x74\x68\x5f\151\143\157\156\137\163\151\172\145"]) ? $Yh->mo_oauth_client_update_option("\x6d\157\137\x6f\141\165\x74\x68\137\151\143\157\156\137\x73\x69\172\x65", sanitize_text_field(wp_unslash($_POST["\155\x6f\x5f\157\141\165\x74\x68\137\x69\143\157\x6e\x5f\x73\x69\172\145"]))) : $Yh->mo_oauth_client_update_option("\155\157\137\157\x61\x75\164\150\x5f\151\x63\157\156\137\163\151\172\145", '');
        isset($_POST["\x6d\x6f\137\x6f\x61\x75\164\x68\x5f\151\143\x6f\156\x5f\x6d\141\x72\x67\x69\x6e"]) ? $Yh->mo_oauth_client_update_option("\155\157\137\x6f\x61\165\164\150\137\x69\x63\x6f\x6e\137\155\141\162\x67\151\x6e", sanitize_text_field(wp_unslash($_POST["\x6d\x6f\x5f\x6f\141\x75\x74\x68\x5f\151\143\x6f\x6e\x5f\x6d\x61\162\x67\x69\156"]))) : $Yh->mo_oauth_client_update_option("\x6d\157\137\157\x61\165\x74\150\x5f\151\143\157\156\137\x6d\141\162\147\151\156", '');
        isset($_POST["\155\157\137\157\141\x75\164\x68\x5f\151\x63\x6f\156\137\143\157\x6e\x66\x69\147\x75\x72\x65\x5f\x63\x73\163"]) ? $Yh->mo_oauth_client_update_option("\x6d\x6f\137\157\x61\165\164\150\x5f\151\143\x6f\156\x5f\143\157\156\146\151\147\x75\162\145\x5f\143\163\163", sanitize_text_field(wp_unslash($_POST["\155\x6f\137\x6f\x61\x75\x74\x68\137\151\x63\157\x6e\x5f\x63\x6f\156\146\151\x67\x75\162\x65\137\x63\x73\x73"]))) : $Yh->mo_oauth_client_update_option("\155\x6f\x5f\x6f\x61\165\x74\x68\x5f\x69\x63\157\x6e\137\x63\x6f\x6e\146\x69\x67\x75\162\x65\x5f\143\163\163", '');
        isset($_POST["\155\x6f\x5f\157\x61\x75\x74\150\x5f\x63\165\163\x74\157\155\x5f\154\157\147\x6f\x75\x74\x5f\164\x65\x78\164"]) ? $Yh->mo_oauth_client_update_option("\x6d\x6f\x5f\x6f\x61\165\x74\x68\137\143\165\163\164\157\x6d\x5f\x6c\157\x67\157\165\x74\137\164\145\x78\x74", sanitize_text_field(wp_unslash($_POST["\x6d\x6f\x5f\x6f\141\x75\x74\150\x5f\x63\165\x73\164\x6f\x6d\x5f\x6c\157\147\x6f\x75\164\137\164\x65\170\x74"]))) : $Yh->mo_oauth_client_update_option("\x6d\x6f\137\157\141\165\x74\150\x5f\x63\x75\x73\x74\x6f\x6d\137\x6c\157\147\157\165\x74\x5f\x74\145\x78\x74", '');
        isset($_POST["\x6d\157\x5f\x6f\141\x75\x74\x68\x5f\167\151\x64\147\145\164\x5f\x63\165\163\x74\x6f\x6d\151\x7a\145\137\x74\145\170\x74"]) ? $Yh->mo_oauth_client_update_option("\x6d\157\137\157\x61\x75\x74\x68\137\167\x69\x64\147\145\164\x5f\143\x75\163\x74\157\155\151\172\145\x5f\164\x65\x78\164", sanitize_text_field(wp_unslash($_POST["\x6d\x6f\x5f\x6f\x61\165\164\150\x5f\x77\151\144\x67\145\x74\x5f\x63\x75\163\164\157\x6d\x69\x7a\x65\137\x74\x65\170\x74"]))) : $Yh->mo_oauth_client_update_option("\155\x6f\137\157\141\165\164\x68\137\167\x69\x64\x67\145\x74\x5f\143\165\163\164\157\x6d\x69\x7a\145\x5f\164\x65\170\164", '');
        $nl = preg_replace("\57\x5c\156\x2b\x2f", "\12", trim(sanitize_text_field(wp_unslash($_POST["\155\157\x5f\x6f\x61\x75\164\x68\137\151\x63\x6f\156\137\143\157\x6e\146\x69\x67\165\162\145\137\x63\163\x73"]))));
        $mc = $Yh->mo_oauth_client_get_option("\x6d\x6f\137\x6f\141\x75\x74\150\x5f\141\x70\160\163\137\154\x69\163\x74");
        $ks = $Yh->mo_oauth_client_get_option("\155\x6f\137\x6f\141\165\x74\150\x5f\x6c\157\x67\x69\156\x5f\142\x75\x74\x74\157\x6e\137\143\x75\x73\x74\x6f\x6d\x69\172\x65\137\144\151\x73\x70\154\x61\x79\137\x74\145\x78\164");
        foreach ($mc as $cW => $F8) {
            $oI = '' !== $F8->get_app_config("\165\x6e\151\x71\x75\x65\137\x61\160\160\151\144") ? $F8->get_app_config("\x75\156\151\x71\165\145\137\141\160\160\151\x64") : $F8->get_app_config("\141\160\x70\111\144");
            $oI = str_replace("\x20", "\x5f", $oI);
            $ks["\155\157\x5f\x6f\x61\165\x74\150\137\154\157\x67\x69\x6e\x5f\142\165\x74\164\157\156\137\143\165\163\x74\157\x6d\x69\x7a\145\137\144\151\163\160\x6c\141\x79\x5f\164\145\170\164\137" . $oI] = !empty($_POST["\x6d\x6f\137\157\x61\x75\x74\150\x5f\154\157\x67\151\156\x5f\142\165\164\164\157\156\x5f\x63\165\163\x74\157\155\x69\172\145\x5f\x64\x69\163\x70\x6c\141\x79\x5f\x74\x65\170\164\x5f" . $oI]) ? sanitize_text_field(wp_unslash($_POST["\x6d\157\x5f\x6f\x61\x75\164\150\137\x6c\x6f\x67\x69\156\x5f\x62\165\164\x74\157\x6e\137\x63\x75\163\x74\157\155\151\172\145\x5f\144\x69\163\160\x6c\x61\171\137\164\145\x78\x74\x5f" . $oI])) : '';
            ib:
        }
        I3:
        $Yh->mo_oauth_client_update_option("\x6d\x6f\137\157\141\x75\164\150\x5f\x6c\x6f\147\x69\156\x5f\x62\x75\x74\x74\x6f\x6e\137\143\165\x73\164\157\x6d\151\172\145\x5f\144\151\x73\160\x6c\x61\171\x5f\164\x65\x78\x74", $ks);
        $Yh->mo_oauth_client_update_option("\155\x6f\137\x63\x75\163\x74\x6f\155\x5f\x68\164\155\x6c\x5f\167\x69\164\150\x5f\x6c\x6f\x67\x6f\165\164\x5f\154\x69\156\x6b", isset($_POST["\155\157\x5f\143\x75\163\x74\x6f\x6d\137\150\164\x6d\x6c\137\167\x69\x74\150\137\154\157\147\x6f\165\x74\137\154\x69\x6e\x6b"]) ? sanitize_text_field(wp_unslash($_POST["\x6d\157\137\x63\165\x73\x74\x6f\x6d\137\150\164\155\x6c\x5f\x77\151\164\150\x5f\154\x6f\147\x6f\165\x74\x5f\x6c\151\x6e\153"])) : "\x66\141\x6c\x73\x65");
        $Yh->mo_oauth_client_update_option("\x6d\157\137\141\160\x70\x6c\x79\137\x63\x75\163\164\157\155\151\172\145\x64\x5f\163\145\x74\x74\x69\x6e\x67\x5f\x6f\x6e\137\x77\x70\137\141\x64\155\x69\x6e", isset($_POST["\155\157\137\141\160\x70\154\x79\x5f\143\165\x73\x74\157\x6d\x69\x7a\145\144\137\x73\x65\164\164\151\x6e\x67\137\x6f\156\x5f\x77\x70\137\141\x64\155\151\156"]) ? sanitize_text_field(wp_unslash($_POST["\x6d\x6f\137\x61\160\x70\154\171\x5f\x63\165\163\x74\157\155\x69\x7a\145\144\137\x73\x65\164\164\x69\x6e\147\x5f\x6f\156\x5f\x77\x70\x5f\x61\x64\x6d\x69\156"])) : false);
        $Yh->mo_oauth_client_update_option(\MoOAuthConstants::PANEL_MESSAGE_OPTION, "\131\x6f\165\x72\x20\x73\x65\164\x74\151\x6e\x67\163\40\167\145\x72\145\40\163\x61\x76\x65\144");
        $Yh->mo_oauth_show_success_message();
        hr:
        wv:
        aF:
    }
    public function set_default_customize_value()
    {
        global $Yh;
        $Yh->mo_oauth_client_update_option("\155\x6f\137\157\141\x75\164\x68\x5f\x69\x63\x6f\156\137\x74\x68\145\x6d\x65", "\160\162\145\166\151\x6f\165\163");
        $Yh->mo_oauth_client_update_option("\155\x6f\137\157\141\x75\x74\x68\137\151\143\157\156\137\x73\x68\141\x70\x65", "\x6c\x6f\156\x67\142\x75\164\x74\157\x6e");
        $Yh->mo_oauth_client_update_option("\155\x6f\x5f\x6f\141\165\164\150\137\151\x63\157\x6e\x5f\x65\x66\x66\x65\x63\164\x5f\163\x63\141\154\145", '');
        $Yh->mo_oauth_client_update_option("\x6d\x6f\x5f\x6f\141\x75\164\x68\x5f\151\143\157\156\137\145\146\x66\x65\143\164\137\x73\x68\x61\x64\157\x77", '');
        if ($Yh->mo_oauth_client_get_option("\155\x6f\137\x6f\x61\x75\164\150\x5f\x69\x63\x6f\x6e\x5f\167\151\x64\164\x68")) {
            goto Bc;
        }
        $Yh->mo_oauth_client_update_option("\x6d\157\x5f\x6f\141\x75\164\x68\137\x69\143\157\156\137\x77\x69\x64\164\150", "\62\67\x30");
        Bc:
        if (!$Yh->mo_oauth_client_get_option("\x6d\x6f\x5f\157\x61\165\164\x68\137\x69\143\157\x6e\137\150\145\151\147\x68\164")) {
            goto bl;
        }
        $GF = $Yh->mo_oauth_client_get_option("\155\157\137\157\x61\165\164\x68\137\x72\145\143\x74\151\146\171\137\x69\143\157\156\137\150\x65\x69\147\150\x74\x5f\146\x6c\x61\147");
        if (!($GF == false)) {
            goto eF;
        }
        $pl = $Yh->mo_oauth_client_get_option("\x6d\157\137\x6f\141\165\x74\150\x5f\151\143\x6f\x6e\137\150\x65\151\147\x68\x74");
        $pl = (int) $pl;
        $pl = $pl * 2;
        $pl = (string) $pl;
        $Yh->mo_oauth_client_update_option("\x6d\x6f\x5f\x6f\x61\165\x74\150\137\151\x63\157\x6e\137\150\x65\x69\147\x68\164", $pl);
        $Yh->mo_oauth_client_update_option("\x6d\x6f\x5f\x6f\x61\x75\164\150\x5f\x72\145\143\164\x69\x66\x79\x5f\x69\143\x6f\x6e\137\150\x65\x69\x67\x68\164\137\x66\x6c\x61\147", True);
        eF:
        goto Ht;
        bl:
        $Yh->mo_oauth_client_update_option("\155\157\137\x6f\141\165\x74\150\x5f\151\143\x6f\x6e\x5f\x68\145\151\x67\x68\164", "\x33\60");
        $Yh->mo_oauth_client_update_option("\155\157\137\x6f\141\x75\x74\150\137\162\145\143\x74\x69\x66\x79\137\151\x63\x6f\x6e\137\150\x65\151\x67\150\x74\x5f\146\154\x61\x67", True);
        Ht:
        $Yh->mo_oauth_client_update_option("\x6d\157\137\x6f\x61\165\164\x68\x5f\x69\143\157\x6e\137\x63\157\154\x6f\x72", "\x23\60\60\x30\x30\x30\60");
        $Yh->mo_oauth_client_update_option("\x6d\x6f\x5f\x6f\141\x75\x74\x68\x5f\x69\x63\157\156\137\143\x75\x73\164\x6f\155\137\143\157\154\x6f\162", "\43\60\x30\70\x65\143\62");
        $Yh->mo_oauth_client_update_option("\x6d\157\x5f\157\141\165\164\150\x5f\x69\143\157\x6e\137\x73\x6d\141\x72\164\137\143\x6f\x6c\x6f\x72\x5f\x31", "\x23\106\x46\61\x46\x34\x42");
        $Yh->mo_oauth_client_update_option("\x6d\157\x5f\157\x61\x75\x74\150\137\151\143\157\x6e\137\x73\155\x61\x72\164\137\143\157\154\x6f\x72\137\x32", "\43\62\x30\60\70\106\x46");
        $Yh->mo_oauth_client_update_option("\x6d\157\x5f\x6f\x61\x75\164\150\x5f\151\143\x6f\156\137\x63\x75\162\x76\x65", "\64");
        $Yh->mo_oauth_client_update_option("\155\157\137\157\x61\x75\164\x68\137\x69\143\157\x6e\137\x73\151\x7a\145", "\63\x30");
        if ($Yh->mo_oauth_client_get_option("\x6d\x6f\137\157\x61\x75\164\150\x5f\x69\x63\x6f\x6e\x5f\x6d\141\162\x67\x69\156")) {
            goto nv;
        }
        $Yh->mo_oauth_client_update_option("\x6d\157\137\157\141\x75\x74\x68\x5f\x69\143\157\156\x5f\x6d\141\x72\147\x69\x6e", "\64");
        nv:
    }
    public function mo_oauth_custom_icons_intiater()
    {
        global $Yh;
        $Zu = $Yh->mo_oauth_client_get_option("\155\x6f\137\157\141\x75\164\x68\137\151\x63\157\156\137\x74\x68\x65\155\x65");
        $xV = $Yh->mo_oauth_client_get_option("\x6d\157\137\157\x61\165\164\x68\137\x69\143\x6f\x6e\x5f\163\x68\x61\160\145");
        if (!((!$Zu || empty($Zu)) && (!$xV || empty($xV)))) {
            goto BY;
        }
        $JD = $this->set_default_customize_value();
        BY:
    }
}
