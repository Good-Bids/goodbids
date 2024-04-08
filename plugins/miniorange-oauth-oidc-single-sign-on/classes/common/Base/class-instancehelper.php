<?php


namespace MoOauthClient\Base;

class InstanceHelper
{
    private $current_version = "\x46\122\x45\x45";
    private $utils;
    public function __construct()
    {
        $this->utils = new \MoOauthClient\MOUtils();
        $this->current_version = $this->utils->get_versi_str();
    }
    public function get_sign_in_settings_instance()
    {
        if (class_exists("\115\x6f\x4f\141\165\x74\x68\x43\154\x69\x65\156\164\x5c\x45\x6e\x74\x65\x72\160\162\151\x73\145\x5c\123\x69\x67\156\x49\156\123\x65\x74\x74\151\x6e\147\x73") && $this->utils->check_versi(4)) {
            goto Lx;
        }
        if (class_exists("\x4d\x6f\x4f\x61\x75\x74\x68\103\x6c\151\x65\156\x74\x5c\x50\x72\x65\155\x69\x75\x6d\x5c\123\x69\147\x6e\x49\156\x53\145\164\x74\x69\156\147\x73") && $this->utils->check_versi(2)) {
            goto BJ;
        }
        if (class_exists("\115\x6f\x4f\x61\x75\x74\x68\x43\x6c\151\x65\156\164\x5c\x53\164\141\x6e\144\x61\162\x64\x5c\x53\151\147\156\111\156\123\145\x74\x74\151\x6e\x67\163") && $this->utils->check_versi(1)) {
            goto TA;
        }
        if (class_exists("\134\115\157\117\x61\165\164\x68\x43\154\151\145\x6e\164\134\x46\x72\145\x65\134\x53\151\147\156\111\x6e\x53\145\164\x74\151\x6e\x67\x73") && $this->utils->check_versi(0)) {
            goto TJ;
        }
        wp_die("\120\x6c\145\141\163\145\40\x43\x68\141\x6e\147\145\40\124\150\x65\40\x76\x65\162\163\151\157\x6e\x20\142\141\x63\x6b\x20\164\157\40\x77\x68\141\164\x20\x69\x74\x20\x72\x65\141\x6c\x6c\171\x20\167\x61\163");
        exit;
        goto GT;
        Lx:
        return new \MoOauthClient\Enterprise\SignInSettings();
        goto GT;
        BJ:
        return new \MoOauthClient\Premium\SignInSettings();
        goto GT;
        TA:
        return new \MoOauthClient\Standard\SignInSettings();
        goto GT;
        TJ:
        return new \MoOauthClient\Free\SignInSettings();
        GT:
    }
    public function get_requestdemo_instance()
    {
        if (!class_exists("\x5c\115\x6f\117\141\165\164\x68\x43\154\151\145\x6e\x74\134\x46\x72\x65\x65\x5c\x52\145\161\x75\145\163\164\x66\157\162\x64\145\x6d\157")) {
            goto Ln;
        }
        return new \MoOauthClient\Free\Requestfordemo();
        Ln:
    }
    public function get_customization_instance()
    {
        if (class_exists("\115\x6f\117\x61\165\x74\x68\103\154\151\145\156\x74\134\x45\156\x74\x65\x72\160\162\x69\x73\x65\x5c\x43\165\163\164\x6f\155\151\x7a\x61\164\151\157\x6e") && $this->utils->check_versi(4)) {
            goto FH;
        }
        if (class_exists("\x4d\x6f\x4f\141\165\x74\150\x43\x6c\x69\145\x6e\x74\134\x50\162\145\x6d\151\165\x6d\x5c\x43\x75\163\164\157\155\151\172\x61\164\151\157\x6e") && $this->utils->check_versi(2)) {
            goto wg;
        }
        if (class_exists("\x4d\157\117\x61\165\x74\x68\103\154\151\x65\156\164\x5c\x53\x74\x61\x6e\x64\141\x72\x64\134\103\x75\x73\x74\x6f\155\151\172\x61\x74\151\157\x6e") && $this->utils->check_versi(1)) {
            goto wN;
        }
        if (class_exists("\x5c\115\x6f\x4f\x61\165\164\150\x43\x6c\x69\x65\x6e\164\x5c\106\162\x65\145\x5c\103\x75\163\164\157\155\x69\172\141\164\151\157\156") && $this->utils->check_versi(0)) {
            goto S3;
        }
        wp_die("\x50\154\x65\x61\x73\145\40\x43\150\141\x6e\147\145\40\x54\x68\145\40\x76\x65\162\x73\151\157\x6e\40\142\141\143\153\x20\x74\157\x20\x77\x68\x61\164\40\x69\x74\x20\x72\145\141\x6c\154\171\40\167\x61\163");
        exit;
        goto tg;
        FH:
        return new \MoOauthClient\Enterprise\Customization();
        goto tg;
        wg:
        return new \MoOauthClient\Premium\Customization();
        goto tg;
        wN:
        return new \MoOauthClient\Standard\Customization();
        goto tg;
        S3:
        return new \MoOauthClient\Free\Customization();
        tg:
    }
    public function get_clientappui_instance()
    {
        if (class_exists("\x4d\x6f\x4f\x61\x75\164\x68\x43\154\x69\x65\156\164\134\105\156\164\x65\x72\x70\162\x69\x73\145\x5c\x43\154\151\145\x6e\x74\101\160\x70\x55\111") && $this->utils->check_versi(4)) {
            goto kJ;
        }
        if (class_exists("\x4d\157\x4f\x61\x75\x74\150\103\154\x69\145\156\x74\x5c\120\x72\145\x6d\x69\165\155\134\x43\x6c\x69\145\156\164\101\x70\x70\x55\x49") && $this->utils->check_versi(2)) {
            goto Ua;
        }
        if (class_exists("\115\x6f\x4f\141\x75\x74\150\103\154\151\x65\x6e\164\134\x53\164\141\156\144\x61\x72\x64\x5c\103\x6c\151\x65\156\164\x41\160\x70\125\x49") && $this->utils->check_versi(1)) {
            goto sZ;
        }
        if (class_exists("\x5c\x4d\x6f\117\x61\165\x74\150\x43\154\151\x65\156\x74\x5c\x46\162\145\x65\x5c\x43\154\x69\145\x6e\x74\101\160\160\125\x49") && $this->utils->check_versi(0)) {
            goto bY;
        }
        wp_die("\x50\154\145\x61\163\145\x20\x43\x68\141\x6e\147\145\40\124\x68\x65\x20\166\x65\x72\163\x69\x6f\156\x20\142\x61\143\153\x20\x74\x6f\x20\x77\x68\141\x74\x20\x69\x74\x20\x72\145\x61\x6c\154\171\40\x77\x61\163");
        exit;
        goto nX;
        kJ:
        return new \MoOauthClient\Enterprise\ClientAppUI();
        goto nX;
        Ua:
        return new \MoOauthClient\Premium\ClientAppUI();
        goto nX;
        sZ:
        return new \MoOauthClient\Standard\ClientAppUI();
        goto nX;
        bY:
        return new \MoOauthClient\Free\ClientAppUI();
        nX:
    }
    public function get_login_handler_instance()
    {
        if (class_exists("\x4d\x6f\x4f\141\165\x74\150\103\x6c\151\145\156\x74\x5c\105\x6e\x74\145\162\x70\162\x69\x73\145\x5c\114\157\x67\151\156\110\141\x6e\x64\154\145\162") && $this->utils->check_versi(4)) {
            goto l0;
        }
        if (class_exists("\x4d\157\117\x61\165\164\150\103\x6c\x69\x65\x6e\164\x5c\x50\162\x65\155\x69\165\x6d\134\x4c\157\x67\151\156\x48\141\x6e\x64\154\x65\162") && $this->utils->check_versi(2)) {
            goto Qi;
        }
        if (class_exists("\x4d\157\117\x61\x75\164\x68\103\x6c\x69\145\x6e\164\134\x53\164\x61\156\x64\141\162\x64\x5c\114\157\x67\x69\156\x48\141\x6e\x64\154\145\x72") && $this->utils->check_versi(1)) {
            goto gs;
        }
        if (class_exists("\x5c\115\157\117\x61\165\164\150\103\x6c\151\145\x6e\164\x5c\114\157\x67\x69\x6e\110\x61\x6e\x64\154\145\x72") && $this->utils->check_versi(0)) {
            goto vu;
        }
        wp_die("\x50\154\x65\141\x73\x65\x20\103\150\141\156\x67\x65\x20\124\x68\x65\40\x76\145\162\x73\x69\x6f\156\40\142\x61\x63\153\x20\x74\x6f\40\167\x68\141\x74\40\151\x74\x20\162\x65\x61\154\x6c\x79\40\167\141\163");
        exit;
        goto m0;
        l0:
        return new \MoOauthClient\Enterprise\LoginHandler();
        goto m0;
        Qi:
        return new \MoOauthClient\Premium\LoginHandler();
        goto m0;
        gs:
        return new \MoOauthClient\Standard\LoginHandler();
        goto m0;
        vu:
        return new \MoOauthClient\LoginHandler();
        m0:
    }
    public function get_settings_instance()
    {
        if (class_exists("\115\x6f\x4f\x61\165\164\150\x43\x6c\x69\x65\156\x74\x5c\105\156\x74\x65\x72\x70\162\151\x73\145\x5c\x45\x6e\164\x65\x72\x70\x72\x69\x73\x65\x53\x65\x74\x74\x69\156\x67\163") && $this->utils->check_versi(4)) {
            goto tU;
        }
        if (class_exists("\x4d\157\x4f\141\x75\164\x68\103\x6c\x69\x65\156\x74\134\120\162\x65\x6d\x69\165\x6d\x5c\x50\162\145\x6d\151\165\x6d\x53\x65\164\x74\x69\156\x67\x73") && $this->utils->check_versi(2)) {
            goto i4;
        }
        if (class_exists("\115\157\117\x61\x75\x74\x68\x43\154\x69\145\156\164\134\123\164\x61\x6e\144\141\162\x64\x5c\x53\x74\141\156\x64\141\x72\x64\123\x65\x74\164\x69\156\147\x73") && $this->utils->check_versi(1)) {
            goto JK;
        }
        if (class_exists("\115\157\117\x61\x75\164\150\x43\154\x69\145\156\164\x5c\106\x72\145\145\x5c\106\162\x65\x65\123\x65\164\x74\x69\156\147\163") && $this->utils->check_versi(0)) {
            goto jS;
        }
        wp_die("\120\154\145\141\x73\x65\40\103\150\141\x6e\x67\x65\x20\x54\x68\145\x20\x76\145\162\x73\x69\x6f\x6e\40\x62\141\x63\x6b\x20\x74\157\40\x77\x68\141\x74\x20\x69\x74\40\x72\145\x61\154\154\171\40\167\x61\163");
        exit;
        goto w2;
        tU:
        return new \MoOauthClient\Enterprise\EnterpriseSettings();
        goto w2;
        i4:
        return new \MoOauthClient\Premium\PremiumSettings();
        goto w2;
        JK:
        return new \MoOauthClient\Standard\StandardSettings();
        goto w2;
        jS:
        return new \MoOauthClient\Free\FreeSettings();
        w2:
    }
    public function get_accounts_instance()
    {
        if (class_exists("\115\157\x4f\141\x75\164\150\103\154\151\x65\156\164\x5c\x50\141\151\144\134\x41\143\143\157\165\156\x74\x73") && $this->utils->check_versi(1)) {
            goto U2;
        }
        return new \MoOauthClient\Accounts();
        goto OW;
        U2:
        return new \MoOauthClient\Paid\Accounts();
        OW:
    }
    public function get_subsite_settings()
    {
        if (class_exists("\x4d\x6f\x4f\x61\x75\x74\x68\103\x6c\151\x65\x6e\x74\x5c\x50\162\x65\x6d\151\165\155\134\115\x75\154\x74\x69\x73\151\164\x65\123\145\164\x74\x69\x6e\147\x73") && $this->utils->is_multisite_versi(5)) {
            goto FW;
        }
        wp_die("\120\x6c\x65\141\163\145\x20\x43\150\141\x6e\147\x65\x20\124\x68\145\40\x76\x65\x72\163\x69\x6f\x6e\40\x62\141\x63\153\40\164\157\x20\x77\x68\141\x74\40\x69\164\40\162\x65\141\x6c\x6c\171\40\x77\x61\x73");
        exit;
        goto kj;
        FW:
        return new \MoOauthClient\Premium\MultisiteSettings();
        kj:
    }
    public function get_user_analytics()
    {
        if (class_exists("\115\x6f\x4f\141\165\x74\150\x43\154\x69\145\x6e\164\134\x45\156\164\145\162\x70\x72\x69\163\x65\x5c\x55\163\x65\x72\101\x6e\x61\x6c\171\x74\x69\143\x73") && $this->utils->check_versi(4)) {
            goto MN;
        }
        wp_die("\120\x6c\x65\x61\x73\145\40\x43\150\141\156\x67\145\40\x54\150\145\40\x76\x65\x72\x73\x69\x6f\x6e\40\142\141\143\153\x20\164\157\x20\167\x68\141\x74\x20\151\x74\40\162\145\x61\154\154\171\40\x77\141\x73");
        exit;
        goto WC;
        MN:
        return new \MoOauthClient\Enterprise\UserAnalytics();
        WC:
    }
    public function get_utils_instance()
    {
        if (!(class_exists("\x4d\x6f\x4f\x61\x75\x74\x68\103\x6c\x69\x65\x6e\x74\134\x53\x74\x61\x6e\x64\141\x72\144\x5c\115\117\125\164\x69\154\x73") && $this->utils->check_versi(1))) {
            goto H2;
        }
        return new \MoOauthClient\Standard\MOUtils();
        H2:
        return $this->utils;
    }
}
