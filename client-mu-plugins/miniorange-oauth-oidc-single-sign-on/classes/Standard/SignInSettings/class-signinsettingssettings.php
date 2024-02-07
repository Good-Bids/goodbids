<?php


namespace MoOauthClient\Standard;

use MoOauthClient\Config;
class SignInSettingsSettings
{
    private $plugin_config;
    public function __construct()
    {
        $sU = $this->get_config_option();
        if ($sU && isset($sU)) {
            goto w7V;
        }
        $this->plugin_config = new Config();
        $this->save_config_option($this->plugin_config);
        goto U5b;
        w7V:
        $this->save_config_option($sU);
        $this->plugin_config = $sU;
        U5b:
    }
    public function save_config_option($Wb = array())
    {
        global $Yh;
        if (!(isset($Wb) && !empty($Wb))) {
            goto gz0;
        }
        return $Yh->mo_oauth_client_update_option("\155\x6f\137\157\141\x75\x74\150\137\143\154\x69\145\156\164\137\143\x6f\156\x66\151\x67", $Wb);
        gz0:
        return false;
    }
    public function get_config_option()
    {
        global $Yh;
        return $Yh->mo_oauth_client_get_option("\x6d\157\x5f\157\141\x75\164\x68\137\x63\x6c\151\145\156\164\x5f\x63\x6f\x6e\146\x69\147");
    }
    public function get_sane_config()
    {
        $Wb = $this->plugin_config;
        if ($Wb && isset($Wb)) {
            goto QY9;
        }
        $Wb = $this->get_config_option();
        QY9:
        if (!(!$Wb || !isset($Wb))) {
            goto L7D;
        }
        $Wb = new Config();
        L7D:
        return $Wb;
    }
    public function mo_oauth_save_settings()
    {
        global $Yh;
        $Wb = $Yh->get_plugin_config()->get_current_config();
        $g9 = "\144\151\x73\x61\x62\x6c\145\x64";
        $g9 = $Yh->mo_oauth_aemoutcrahsaphtn();
        if (!($g9 == "\x64\151\x73\141\x62\x6c\145\x64")) {
            goto yT3;
        }
        $Wb = $this->get_sane_config();
        if (!(isset($_POST["\155\157\137\x73\x69\147\156\151\156\x73\145\164\164\x69\x6e\147\x73\x5f\x6e\157\156\143\x65"]) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST["\x6d\157\137\163\x69\147\156\151\x6e\x73\x65\x74\x74\151\156\x67\163\137\x6e\157\156\143\x65"])), "\x6d\157\137\157\141\x75\x74\150\x5f\x63\154\x69\145\x6e\164\137\x73\151\147\x6e\137\x69\156\137\163\x65\164\x74\151\156\x67\163") && (isset($_POST[\MoOAuthConstants::OPTION]) && "\x6d\x6f\137\x6f\x61\165\164\150\137\143\x6c\151\145\x6e\x74\137\x61\144\166\141\156\x63\x65\x64\137\x73\x65\x74\x74\x69\x6e\147\x73" === sanitize_text_field(wp_unslash($_POST[\MoOAuthConstants::OPTION]))))) {
            goto wyf;
        }
        if (!current_user_can("\141\144\155\x69\x6e\151\163\164\162\141\164\x6f\162")) {
            goto J_Q;
        }
        $Wb = $this->change_current_config($_POST, $Wb);
        $Wb->save_settings($Wb->get_current_config());
        $this->save_config_option($Wb);
        J_Q:
        wyf:
        yT3:
    }
    public function change_current_config($post, $Wb)
    {
        $Wb->add_config("\141\146\x74\x65\162\137\x6c\157\147\151\x6e\137\x75\162\x6c", isset($post["\143\x75\x73\164\x6f\155\137\141\146\x74\145\162\137\154\x6f\x67\151\156\x5f\165\x72\x6c"]) ? stripslashes(wp_unslash($post["\x63\x75\163\x74\157\155\137\141\146\164\x65\x72\137\x6c\x6f\147\151\156\x5f\165\x72\154"])) : '');
        $Wb->add_config("\141\146\164\145\x72\137\x6c\157\x67\x6f\x75\164\x5f\x75\x72\154", isset($post["\x63\165\x73\164\157\x6d\137\x61\146\164\145\162\137\x6c\x6f\147\x6f\x75\164\x5f\x75\x72\154"]) ? stripslashes(wp_unslash($post["\143\165\x73\x74\x6f\155\137\141\x66\x74\x65\162\x5f\x6c\x6f\147\157\165\164\x5f\x75\162\x6c"])) : '');
        $Wb->add_config("\160\x6f\x70\x75\160\x5f\x6c\157\x67\151\156", isset($post["\x70\x6f\160\x75\x70\x5f\x6c\157\x67\151\x6e"]) ? stripslashes(wp_unslash($post["\x70\x6f\x70\165\x70\137\x6c\157\147\x69\x6e"])) : 0);
        $Wb->add_config("\x63\x75\163\x74\x6f\155\x5f\167\151\x64\164\150", isset($post["\x63\x75\163\164\x6f\x6d\x5f\167\x69\144\x74\150"]) ? stripslashes(wp_unslash($post["\143\x75\x73\x74\157\155\137\x77\x69\144\164\x68"])) : 0);
        $Wb->add_config("\143\x75\163\164\x6f\155\x5f\x68\145\x69\x67\150\x74", isset($post["\143\x75\x73\164\x6f\x6d\137\150\145\x69\x67\x68\x74"]) ? stripslashes(wp_unslash($post["\143\165\163\164\157\155\x5f\x68\x65\x69\147\x68\x74"])) : 0);
        $Wb->add_config("\141\165\x74\x6f\137\x72\145\x67\151\163\164\x65\162", isset($post["\x61\165\x74\157\x5f\x72\x65\147\x69\x73\164\145\162"]) ? stripslashes(wp_unslash($post["\141\165\164\x6f\137\162\145\x67\151\x73\164\145\x72"])) : 0);
        $Wb->add_config("\x63\x6f\x6e\146\151\x72\x6d\x5f\154\x6f\147\157\165\164", isset($post["\x63\157\x6e\146\151\162\x6d\137\x6c\157\147\157\x75\x74"]) ? stripslashes(wp_unslash($post["\143\x6f\x6e\146\x69\162\x6d\x5f\154\157\x67\x6f\165\164"])) : 0);
        $Wb->add_config("\167\x68\151\x74\145\154\151\163\x74\137\x72\145\x64\x69\162\145\x63\164\137\x75\162\154", isset($post["\167\x68\x69\164\x65\x6c\151\163\x74\x5f\x72\145\x64\x69\162\145\x63\x74\x5f\165\x72\154"]) ? sanitize_text_field(wp_unslash($post["\x77\150\x69\164\x65\154\151\x73\x74\x5f\x72\x65\x64\x69\x72\145\x63\x74\137\x75\162\x6c"])) : '');
        $Wb->add_config("\167\150\151\x74\x65\154\x69\x73\164\137\162\145\144\x69\x72\145\x63\x74\x5f\x75\162\154\163", isset($post["\167\x68\151\x74\x65\154\151\163\164\x5f\x72\x65\x64\151\x72\145\143\164\137\x75\162\154\x73"]) ? sanitize_text_field(wp_unslash($post["\x77\150\151\x74\145\154\x69\x73\164\x5f\x72\x65\144\151\162\145\143\164\137\x75\x72\154\x73"])) : '');
        return $Wb;
    }
}
