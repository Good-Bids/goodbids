<?php


namespace MoOauthClient\Standard;

use MoOauthClient\Config;
class SignInSettingsSettings
{
    private $plugin_config;
    public function __construct()
    {
        $zn = $this->get_config_option();
        if ($zn && isset($zn)) {
            goto akl;
        }
        $this->plugin_config = new Config();
        $this->save_config_option($this->plugin_config);
        goto SHU;
        akl:
        $this->save_config_option($zn);
        $this->plugin_config = $zn;
        SHU:
    }
    public function save_config_option($Kn = array())
    {
        global $Uj;
        if (!(isset($Kn) && !empty($Kn))) {
            goto FB2;
        }
        return $Uj->mo_oauth_client_update_option("\155\157\x5f\x6f\x61\165\x74\150\x5f\x63\154\x69\x65\156\x74\x5f\x63\x6f\156\x66\151\x67", $Kn);
        FB2:
        return false;
    }
    public function get_config_option()
    {
        global $Uj;
        return $Uj->mo_oauth_client_get_option("\155\157\137\157\x61\x75\164\x68\x5f\x63\x6c\x69\145\156\x74\x5f\x63\x6f\x6e\146\x69\x67");
    }
    public function get_sane_config()
    {
        $Kn = $this->plugin_config;
        if ($Kn && isset($Kn)) {
            goto C3w;
        }
        $Kn = $this->get_config_option();
        C3w:
        if (!(!$Kn || !isset($Kn))) {
            goto bRA;
        }
        $Kn = new Config();
        bRA:
        return $Kn;
    }
    public function mo_oauth_save_settings()
    {
        global $Uj;
        $Kn = $Uj->get_plugin_config()->get_current_config();
        $MS = "\x64\x69\x73\141\142\154\145\x64";
        if (empty($Kn["\155\157\137\144\164\x65\x5f\163\x74\141\x74\x65"])) {
            goto NjE;
        }
        $MS = $Uj->mooauthdecrypt($Kn["\x6d\157\x5f\144\x74\145\x5f\163\164\141\x74\145"]);
        NjE:
        if (!($MS == "\x64\x69\x73\x61\x62\x6c\x65\144")) {
            goto TY7;
        }
        $Kn = $this->get_sane_config();
        if (!(isset($_POST["\x6d\x6f\137\x73\151\147\156\151\x6e\163\x65\x74\x74\x69\x6e\147\163\x5f\x6e\157\156\143\145"]) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST["\155\157\x5f\163\x69\x67\x6e\x69\x6e\163\145\164\164\x69\x6e\x67\163\x5f\156\x6f\x6e\x63\145"])), "\x6d\157\x5f\157\x61\x75\164\150\x5f\143\154\x69\x65\x6e\164\137\x73\x69\147\156\137\151\156\137\163\x65\x74\x74\x69\x6e\147\x73") && (isset($_POST[\MoOAuthConstants::OPTION]) && "\155\157\137\x6f\141\165\x74\150\137\x63\154\x69\x65\156\164\x5f\141\144\166\x61\156\143\x65\x64\x5f\x73\145\164\x74\x69\156\147\x73" === $_POST[\MoOAuthConstants::OPTION]))) {
            goto I3c;
        }
        $Kn = $this->change_current_config($_POST, $Kn);
        $Kn->save_settings($Kn->get_current_config());
        $this->save_config_option($Kn);
        I3c:
        TY7:
    }
    public function change_current_config($post, $Kn)
    {
        $Kn->add_config("\x61\x66\164\145\x72\x5f\x6c\157\147\x69\156\x5f\165\x72\x6c", isset($post["\143\x75\163\x74\157\x6d\137\141\146\164\145\162\x5f\154\157\x67\151\156\137\x75\162\154"]) ? stripslashes(wp_unslash($post["\143\165\x73\164\157\x6d\137\x61\x66\164\x65\x72\x5f\154\x6f\147\151\156\x5f\165\162\x6c"])) : '');
        $Kn->add_config("\x61\146\x74\x65\162\137\x6c\x6f\147\x6f\165\164\x5f\x75\162\154", isset($post["\143\x75\163\164\x6f\155\x5f\141\x66\164\x65\x72\137\x6c\x6f\147\157\165\164\x5f\x75\x72\154"]) ? stripslashes(wp_unslash($post["\x63\x75\x73\164\157\155\137\141\146\x74\x65\x72\137\154\x6f\x67\x6f\x75\164\137\x75\x72\154"])) : '');
        $Kn->add_config("\160\157\x70\165\160\x5f\x6c\157\147\151\156", isset($post["\160\x6f\160\165\160\x5f\x6c\157\147\x69\x6e"]) ? stripslashes(wp_unslash($post["\160\157\160\165\x70\x5f\x6c\157\x67\x69\x6e"])) : 0);
        $Kn->add_config("\x61\x75\164\157\137\162\145\x67\151\163\164\145\162", isset($post["\x61\165\x74\157\x5f\162\x65\147\151\163\164\x65\162"]) ? stripslashes(wp_unslash($post["\x61\165\x74\x6f\137\x72\145\x67\x69\x73\164\145\162"])) : 0);
        $Kn->add_config("\143\x6f\156\x66\x69\x72\155\x5f\x6c\157\147\157\x75\164", isset($post["\143\157\156\146\x69\162\155\137\x6c\x6f\147\x6f\x75\164"]) ? stripslashes(wp_unslash($post["\143\157\x6e\146\x69\x72\x6d\x5f\154\157\x67\157\165\164"])) : 0);
        return $Kn;
    }
}
