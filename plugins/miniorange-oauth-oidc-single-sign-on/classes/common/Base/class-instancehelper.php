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
        if (class_exists("\115\157\117\x61\x75\x74\x68\103\154\151\x65\x6e\x74\x5c\105\x6e\164\145\162\x70\x72\x69\x73\145\x5c\123\151\147\x6e\111\x6e\123\x65\164\x74\151\156\x67\163") && $this->utils->check_versi(4)) {
            goto iW;
        }
        if (class_exists("\x4d\x6f\x4f\141\165\164\150\103\154\x69\x65\156\x74\134\x50\x72\x65\155\x69\x75\x6d\134\123\x69\x67\x6e\111\x6e\123\x65\x74\164\x69\156\x67\x73") && $this->utils->check_versi(2)) {
            goto pf;
        }
        if (class_exists("\x4d\157\117\x61\x75\164\x68\x43\x6c\x69\x65\156\164\134\x53\x74\x61\x6e\144\x61\x72\x64\134\x53\x69\x67\156\x49\156\123\145\x74\164\151\x6e\x67\163") && $this->utils->check_versi(1)) {
            goto Na;
        }
        if (class_exists("\x5c\x4d\157\117\x61\x75\164\x68\x43\x6c\151\x65\x6e\x74\x5c\x46\x72\145\145\x5c\x53\x69\x67\156\111\156\123\145\x74\164\x69\x6e\x67\163") && $this->utils->check_versi(0)) {
            goto JB;
        }
        wp_die("\120\x6c\x65\x61\163\x65\x20\x43\x68\x61\x6e\x67\x65\40\124\150\145\x20\166\x65\162\163\151\x6f\156\40\142\141\x63\153\x20\x74\x6f\40\x77\150\141\x74\x20\151\164\40\x72\x65\x61\x6c\x6c\171\x20\167\x61\163");
        exit;
        goto Vr;
        iW:
        return new \MoOauthClient\Enterprise\SignInSettings();
        goto Vr;
        pf:
        return new \MoOauthClient\Premium\SignInSettings();
        goto Vr;
        Na:
        return new \MoOauthClient\Standard\SignInSettings();
        goto Vr;
        JB:
        return new \MoOauthClient\Free\SignInSettings();
        Vr:
    }
    public function get_requestdemo_instance()
    {
        if (!class_exists("\134\115\157\x4f\x61\165\x74\150\103\x6c\x69\x65\156\x74\x5c\x46\162\x65\145\134\x52\x65\161\x75\x65\163\164\x66\x6f\x72\144\x65\x6d\157")) {
            goto oj;
        }
        return new \MoOauthClient\Free\Requestfordemo();
        oj:
    }
    public function get_customization_instance()
    {
        if (class_exists("\115\157\x4f\x61\x75\164\150\103\154\x69\x65\x6e\x74\134\105\x6e\x74\x65\x72\x70\162\x69\x73\x65\134\x43\x75\163\x74\x6f\x6d\x69\172\141\x74\151\x6f\156") && $this->utils->check_versi(4)) {
            goto OS;
        }
        if (class_exists("\x4d\x6f\x4f\141\x75\164\150\x43\x6c\151\145\x6e\164\x5c\x50\162\x65\155\151\x75\155\134\103\x75\163\x74\157\155\151\172\x61\x74\151\157\156") && $this->utils->check_versi(2)) {
            goto eh;
        }
        if (class_exists("\115\157\x4f\x61\x75\x74\x68\103\x6c\151\x65\x6e\x74\x5c\123\164\x61\x6e\144\141\x72\144\134\x43\x75\x73\164\157\155\151\172\141\164\151\157\x6e") && $this->utils->check_versi(1)) {
            goto Re;
        }
        if (class_exists("\x5c\x4d\157\x4f\141\x75\164\x68\103\x6c\x69\x65\156\x74\x5c\106\162\x65\x65\x5c\103\x75\x73\x74\x6f\155\151\x7a\141\x74\151\x6f\x6e") && $this->utils->check_versi(0)) {
            goto Vv;
        }
        wp_die("\120\x6c\145\x61\163\145\40\103\x68\x61\x6e\x67\x65\40\124\x68\x65\40\x76\x65\162\163\151\157\156\40\142\141\x63\x6b\40\164\157\40\167\150\141\164\40\151\164\40\162\x65\141\154\x6c\171\x20\167\141\x73");
        exit;
        goto vI;
        OS:
        return new \MoOauthClient\Enterprise\Customization();
        goto vI;
        eh:
        return new \MoOauthClient\Premium\Customization();
        goto vI;
        Re:
        return new \MoOauthClient\Standard\Customization();
        goto vI;
        Vv:
        return new \MoOauthClient\Free\Customization();
        vI:
    }
    public function get_clientappui_instance()
    {
        if (class_exists("\115\157\x4f\x61\165\x74\x68\103\154\x69\145\156\164\134\x45\x6e\x74\145\162\x70\162\151\163\x65\x5c\x43\x6c\151\145\156\164\x41\x70\160\125\x49") && $this->utils->check_versi(4)) {
            goto JP;
        }
        if (class_exists("\x4d\157\x4f\141\165\x74\x68\x43\x6c\151\145\x6e\164\134\x50\162\145\x6d\x69\x75\x6d\x5c\x43\154\x69\x65\x6e\164\101\x70\160\x55\x49") && $this->utils->check_versi(2)) {
            goto KY;
        }
        if (class_exists("\115\x6f\x4f\141\x75\x74\x68\103\154\x69\x65\x6e\x74\x5c\123\164\x61\x6e\x64\x61\x72\144\134\x43\x6c\x69\x65\156\x74\x41\160\x70\125\111") && $this->utils->check_versi(1)) {
            goto H8;
        }
        if (class_exists("\134\x4d\x6f\x4f\141\x75\164\x68\x43\x6c\x69\145\156\164\134\106\x72\x65\x65\134\x43\154\151\145\x6e\164\101\160\x70\125\x49") && $this->utils->check_versi(0)) {
            goto H_;
        }
        wp_die("\x50\x6c\145\x61\x73\x65\40\103\x68\141\156\x67\x65\x20\x54\150\145\40\x76\x65\x72\x73\151\157\156\40\142\x61\143\153\40\x74\x6f\x20\167\150\141\164\x20\x69\x74\40\162\x65\x61\x6c\x6c\x79\x20\x77\141\x73");
        exit;
        goto Xm;
        JP:
        return new \MoOauthClient\Enterprise\ClientAppUI();
        goto Xm;
        KY:
        return new \MoOauthClient\Premium\ClientAppUI();
        goto Xm;
        H8:
        return new \MoOauthClient\Standard\ClientAppUI();
        goto Xm;
        H_:
        return new \MoOauthClient\Free\ClientAppUI();
        Xm:
    }
    public function get_login_handler_instance()
    {
        if (class_exists("\x4d\x6f\117\x61\x75\164\x68\103\154\x69\145\156\164\134\105\156\x74\145\162\160\x72\x69\163\145\x5c\x4c\157\147\x69\x6e\110\141\156\144\x6c\x65\x72") && $this->utils->check_versi(4)) {
            goto l0;
        }
        if (class_exists("\x4d\157\x4f\141\x75\164\x68\x43\x6c\151\x65\156\164\x5c\x50\x72\145\x6d\151\165\155\x5c\114\157\147\151\x6e\x48\x61\x6e\x64\x6c\145\x72") && $this->utils->check_versi(2)) {
            goto z8;
        }
        if (class_exists("\115\x6f\117\x61\165\164\150\103\154\x69\145\156\164\x5c\123\164\x61\x6e\144\141\x72\144\x5c\114\x6f\147\151\156\110\141\x6e\144\x6c\x65\162") && $this->utils->check_versi(1)) {
            goto Jh;
        }
        if (class_exists("\134\115\157\x4f\141\165\x74\x68\x43\x6c\x69\x65\156\164\134\x4c\157\147\151\x6e\x48\141\156\144\x6c\x65\x72") && $this->utils->check_versi(0)) {
            goto UI;
        }
        wp_die("\x50\154\x65\141\x73\x65\x20\x43\150\x61\156\147\x65\40\x54\x68\x65\40\166\x65\162\163\151\x6f\x6e\x20\x62\x61\x63\153\x20\164\x6f\40\167\x68\141\164\40\x69\164\40\x72\145\x61\x6c\x6c\x79\x20\x77\141\x73");
        exit;
        goto vk;
        l0:
        return new \MoOauthClient\Enterprise\LoginHandler();
        goto vk;
        z8:
        return new \MoOauthClient\Premium\LoginHandler();
        goto vk;
        Jh:
        return new \MoOauthClient\Standard\LoginHandler();
        goto vk;
        UI:
        return new \MoOauthClient\LoginHandler();
        vk:
    }
    public function get_settings_instance()
    {
        if (class_exists("\115\157\x4f\141\165\x74\x68\103\x6c\151\x65\x6e\164\134\105\x6e\164\145\162\160\162\151\163\x65\134\105\156\164\145\x72\160\x72\x69\x73\145\123\145\164\164\151\156\147\163") && $this->utils->check_versi(4)) {
            goto L7;
        }
        if (class_exists("\115\x6f\117\x61\165\x74\150\x43\154\151\145\x6e\x74\x5c\x50\162\x65\155\151\165\x6d\x5c\120\x72\145\x6d\x69\x75\x6d\x53\145\164\164\x69\x6e\147\163") && $this->utils->check_versi(2)) {
            goto hs;
        }
        if (class_exists("\115\157\x4f\141\165\x74\150\103\x6c\x69\145\x6e\164\x5c\x53\x74\x61\x6e\144\x61\162\x64\x5c\123\164\x61\x6e\x64\x61\162\144\x53\145\164\164\x69\156\x67\x73") && $this->utils->check_versi(1)) {
            goto ZS;
        }
        if (class_exists("\x4d\x6f\117\x61\x75\x74\150\x43\154\x69\145\156\x74\134\106\162\x65\145\x5c\x46\x72\x65\145\123\145\164\164\151\156\147\x73") && $this->utils->check_versi(0)) {
            goto v9;
        }
        wp_die("\120\x6c\145\x61\x73\x65\x20\103\150\x61\x6e\x67\145\40\124\150\x65\x20\166\x65\162\x73\151\x6f\x6e\x20\142\141\143\153\x20\x74\157\x20\x77\x68\141\164\x20\151\164\x20\162\145\141\154\154\171\x20\167\x61\x73");
        exit;
        goto ue;
        L7:
        return new \MoOauthClient\Enterprise\EnterpriseSettings();
        goto ue;
        hs:
        return new \MoOauthClient\Premium\PremiumSettings();
        goto ue;
        ZS:
        return new \MoOauthClient\Standard\StandardSettings();
        goto ue;
        v9:
        return new \MoOauthClient\Free\FreeSettings();
        ue:
    }
    public function get_accounts_instance()
    {
        if (class_exists("\115\157\x4f\x61\165\164\x68\103\x6c\x69\x65\156\x74\x5c\120\141\151\144\134\x41\143\x63\157\165\156\x74\163") && $this->utils->check_versi(1)) {
            goto Bq;
        }
        return new \MoOauthClient\Accounts();
        goto aE;
        Bq:
        return new \MoOauthClient\Paid\Accounts();
        aE:
    }
    public function get_subsite_settings()
    {
        if (class_exists("\115\x6f\x4f\141\165\164\150\103\154\151\145\x6e\164\x5c\120\162\x65\155\151\165\x6d\134\x4d\165\154\x74\x69\x73\x69\x74\x65\x53\145\164\164\151\x6e\147\x73") && $this->utils->is_multisite_versi(5)) {
            goto ol;
        }
        wp_die("\120\154\145\x61\x73\145\40\x43\150\x61\x6e\147\x65\40\x54\x68\x65\40\x76\x65\x72\x73\x69\157\156\x20\x62\141\x63\x6b\x20\164\157\x20\167\x68\141\164\x20\x69\x74\x20\x72\x65\x61\154\x6c\171\x20\167\x61\163");
        exit;
        goto K8;
        ol:
        return new \MoOauthClient\Premium\MultisiteSettings();
        K8:
    }
    public function get_user_analytics()
    {
        if (class_exists("\115\x6f\x4f\x61\165\164\x68\x43\x6c\x69\x65\x6e\x74\134\105\156\164\x65\x72\160\x72\x69\x73\x65\134\x55\x73\145\162\x41\156\141\154\171\x74\x69\x63\x73") && $this->utils->check_versi(4)) {
            goto Ky;
        }
        wp_die("\x50\x6c\145\141\163\145\40\103\x68\x61\156\147\x65\40\x54\x68\145\40\166\145\162\x73\151\157\156\x20\142\141\143\x6b\40\164\x6f\40\167\150\x61\164\x20\x69\164\x20\x72\x65\x61\154\154\x79\40\x77\141\163");
        exit;
        goto iE;
        Ky:
        return new \MoOauthClient\Enterprise\UserAnalytics();
        iE:
    }
    public function get_utils_instance()
    {
        if (!(class_exists("\115\x6f\117\x61\x75\164\150\x43\x6c\151\145\x6e\164\x5c\x53\x74\x61\156\144\x61\x72\x64\134\115\117\125\x74\151\154\163") && $this->utils->check_versi(1))) {
            goto Tp;
        }
        return new \MoOauthClient\Standard\MOUtils();
        Tp:
        return $this->utils;
    }
}
