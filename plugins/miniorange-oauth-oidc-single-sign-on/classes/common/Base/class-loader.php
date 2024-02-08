<?php


namespace MoOauthClient\Base;

use MoOauthClient\Licensing;
use MoOauthClient\MoAddons;
use MoOauthClient\Base\InstanceHelper;
class Loader
{
    private $instance_helper;
    public function __construct()
    {
        add_action("\141\144\x6d\x69\156\x5f\145\x6e\x71\165\145\x75\x65\x5f\x73\143\x72\151\160\x74\x73", array($this, "\160\154\165\x67\151\156\137\x73\145\164\164\151\156\147\163\x5f\163\164\x79\154\145"));
        add_action("\x61\x64\x6d\x69\x6e\x5f\x65\x6e\x71\x75\145\165\145\x5f\163\143\162\151\160\x74\163", array($this, "\160\x6c\165\147\151\x6e\x5f\163\x65\164\x74\151\x6e\147\163\137\163\143\162\151\160\164"));
        $this->instance_helper = new InstanceHelper();
    }
    public function plugin_settings_style()
    {
        wp_enqueue_style("\x6d\157\x5f\x6f\x61\165\x74\150\137\141\x64\x6d\x69\156\x5f\x73\x65\164\164\x69\156\x67\163\x5f\163\164\171\154\x65", MOC_URL . "\x72\x65\163\x6f\x75\x72\x63\145\x73\x2f\x63\163\x73\57\x73\x74\x79\x6c\145\137\x73\x65\164\164\151\x6e\x67\x73\56\143\x73\163", array(), $WD = MO_OAUTH_PREMIUM_CSS_JS_VERSION, $Zh = false);
        wp_enqueue_style("\x6d\x6f\x5f\x6f\141\x75\x74\x68\x5f\141\x64\x6d\151\156\137\x73\145\x74\164\151\x6e\147\x73\137\160\150\157\156\145\137\x73\164\171\154\x65", MOC_URL . "\162\x65\x73\157\x75\x72\143\x65\163\57\x63\163\x73\57\160\x68\157\156\x65\x2e\x63\x73\163", array(), $WD = MO_OAUTH_PREMIUM_CSS_JS_VERSION, $Zh = false);
        wp_enqueue_style("\155\x6f\x5f\157\x61\165\164\150\137\x61\x64\x6d\151\156\x5f\x73\x65\164\x74\151\x6e\147\x73\x5f\x64\141\164\x61\164\141\x62\154\145", MOC_URL . "\162\145\163\157\x75\x72\143\145\163\x2f\x63\x73\163\57\152\161\165\145\x72\x79\x2e\x64\141\164\x61\x54\141\x62\154\x65\163\x2e\155\x69\x6e\x2e\143\163\163", array(), $WD = MO_OAUTH_PREMIUM_CSS_JS_VERSION, $Zh = false);
        wp_enqueue_style("\155\157\55\167\x70\55\x62\x6f\157\164\x73\x74\x72\x61\x70\55\163\157\143\151\x61\x6c", MOC_URL . "\x72\x65\x73\x6f\165\162\x63\x65\163\x2f\143\x73\x73\57\142\157\x6f\164\163\x74\x72\141\x70\x2d\x73\x6f\143\151\x61\x6c\x2e\143\163\x73", array(), $WD = null, $Zh = false);
        wp_enqueue_style("\155\x6f\x2d\x77\160\55\142\x6f\x6f\x74\163\164\x72\x61\160\x2d\x6d\141\x69\x6e", MOC_URL . "\162\145\x73\157\165\162\x63\145\x73\57\143\x73\163\57\x62\157\x6f\x74\x73\164\162\141\x70\x2e\155\x69\x6e\x2d\160\x72\x65\x76\x69\145\167\56\x63\x73\x73", array(), $WD = null, $Zh = false);
        wp_enqueue_style("\x6d\x6f\55\x77\x70\x2d\146\157\x6e\x74\x2d\141\167\145\163\157\x6d\x65", MOC_URL . "\x72\145\163\157\x75\162\x63\145\163\x2f\143\163\x73\57\146\x6f\x6e\164\55\x61\x77\x65\163\x6f\155\x65\x2e\155\x69\x6e\x2e\143\163\163", array(), $WD = MO_OAUTH_PREMIUM_CSS_JS_VERSION, $Zh = false);
        if (!(isset($_REQUEST["\164\141\142"]) && "\154\151\143\x65\156\163\x69\x6e\x67" === $_REQUEST["\x74\141\x62"])) {
            goto Pt;
        }
        wp_enqueue_style("\x6d\157\x5f\157\141\x75\164\x68\x5f\x62\157\x6f\x74\x73\164\x72\x61\x70\x5f\x63\x73\x73", MOC_URL . "\162\145\163\x6f\165\162\x63\145\163\57\x63\x73\x73\x2f\x62\x6f\157\x74\163\x74\x72\x61\160\x2f\x62\x6f\x6f\x74\x73\x74\x72\141\160\x2e\x6d\x69\156\x2e\x63\x73\x73", array(), $WD = null, $Zh = false);
        wp_enqueue_style("\155\157\137\x6f\141\x75\164\150\x5f\x6c\151\x63\x65\x6e\163\145\x5f\160\x61\x67\145\137\163\x74\x79\x6c\x65", MOC_URL . "\x72\x65\163\x6f\165\162\143\x65\163\57\143\163\163\57\x6d\x6f\55\x6f\x61\x75\164\150\55\x6c\151\x63\145\156\163\151\x6e\147\x2e\x63\163\163", array(), $WD = MO_OAUTH_PREMIUM_CSS_JS_VERSION, $Zh = false);
        Pt:
    }
    public function plugin_settings_script()
    {
        wp_enqueue_script("\155\x6f\137\157\x61\x75\x74\x68\137\141\144\x6d\x69\156\137\163\145\x74\x74\151\156\x67\x73\x5f\163\x63\162\x69\160\164", MOC_URL . "\x72\145\x73\157\165\x72\x63\x65\x73\57\x6a\163\57\x73\145\x74\164\151\x6e\x67\163\56\152\163", array(), $WD = MO_OAUTH_PREMIUM_CSS_JS_VERSION, $Zh = false);
        wp_enqueue_script("\x6d\157\x5f\x6f\141\165\x74\x68\x5f\x61\x64\155\x69\x6e\x5f\163\x65\164\164\x69\x6e\147\163\137\x70\x68\x6f\156\x65\137\163\x63\x72\x69\160\164", MOC_URL . "\x72\x65\163\157\x75\x72\x63\145\x73\57\152\x73\57\160\x68\x6f\156\145\56\152\x73", array(), $WD = null, $Zh = false);
        wp_enqueue_script("\x6d\157\137\157\x61\x75\164\150\137\x61\144\x6d\151\x6e\x5f\163\x65\164\164\x69\156\x67\x73\x5f\144\x61\164\x61\164\x61\x62\x6c\x65", MOC_URL . "\x72\145\163\x6f\x75\162\143\145\x73\57\x6a\163\x2f\152\x71\165\x65\162\171\x2e\x64\x61\164\141\124\x61\142\154\x65\163\x2e\x6d\151\156\x2e\152\x73", array(), $WD = MO_OAUTH_PREMIUM_CSS_JS_VERSION, $Zh = false);
        if (!(isset($_REQUEST["\x74\141\142"]) && "\x6c\151\x63\145\x6e\163\x69\x6e\x67" === $_REQUEST["\164\x61\x62"])) {
            goto WF;
        }
        wp_enqueue_script("\155\x6f\137\x6f\141\165\x74\x68\137\155\x6f\x64\x65\162\156\x69\172\162\137\163\143\162\151\160\164", MOC_URL . "\162\145\x73\157\165\162\143\145\x73\x2f\152\x73\x2f\155\157\x64\x65\162\x6e\151\x7a\162\56\152\x73", array(), $WD = null, $Zh = true);
        wp_enqueue_script("\x6d\157\x5f\157\141\x75\164\150\137\160\x6f\x70\157\166\145\162\x5f\163\143\x72\x69\160\164", MOC_URL . "\162\x65\x73\x6f\165\x72\143\145\x73\57\152\163\x2f\x62\x6f\x6f\x74\x73\x74\162\x61\x70\x2f\160\157\160\x70\145\162\56\x6d\151\x6e\x2e\152\x73", array(), $WD = null, $Zh = true);
        wp_enqueue_script("\155\157\137\x6f\141\165\164\150\137\142\x6f\157\x74\163\x74\x72\x61\x70\x5f\163\x63\x72\x69\160\x74", MOC_URL . "\x72\x65\x73\x6f\165\162\143\145\x73\57\x6a\x73\x2f\142\x6f\x6f\x74\x73\x74\x72\x61\160\57\x62\x6f\x6f\164\163\164\x72\x61\x70\56\x6d\151\156\x2e\152\163", array(), $WD = null, $Zh = true);
        WF:
    }
    public function load_current_tab($VE)
    {
        global $Yh;
        $q9 = 0 === $Yh->get_versi();
        $gB = false;
        if (!($VE == '' && $Yh->mo_oauth_aemoutcrahsaphtn() == "\x65\x6e\x61\142\x6c\x65\x64")) {
            goto AG;
        }
        $VE = "\x61\x63\143\x6f\x75\156\164";
        AG:
        if ($q9) {
            goto Lj;
        }
        $gB = $Yh->mo_oauth_client_get_option("\x6d\157\137\x6f\x61\165\164\x68\137\x63\x6c\151\145\x6e\x74\137\154\157\x61\144\137\x61\x6e\141\x6c\x79\x74\151\x63\163");
        $gB = boolval($gB) ? boolval($gB) : false;
        $q9 = $Yh->check_versi(1) && $Yh->mo_oauth_is_clv();
        Lj:
        if ("\141\143\x63\157\x75\x6e\x74" === $VE || !$q9) {
            goto jc;
        }
        if ("\143\165\163\164\157\155\151\x7a\141\x74\151\157\x6e" === $VE && $q9) {
            goto cX;
        }
        if ("\x73\x69\x67\156\x69\x6e\x73\x65\164\x74\x69\156\x67\163" === $VE && $q9) {
            goto DI;
        }
        if ("\163\165\x62\163\x69\x74\145\163\x65\x74\164\151\156\147\163" === $VE && $q9) {
            goto X0;
        }
        if ($gB && "\141\156\x61\154\171\x74\x69\143\x73" === $VE && $q9) {
            goto oG;
        }
        if ("\x6c\x69\143\145\156\163\x69\156\147" === $VE) {
            goto Ut;
        }
        if ("\x72\x65\x71\x75\x65\163\164\x66\157\x72\144\145\x6d\157" === $VE && $q9) {
            goto ud;
        }
        if ("\x61\x64\144\157\x6e\x73" === $VE) {
            goto I0;
        }
        $this->instance_helper->get_clientappui_instance()->render_free_ui();
        goto U3;
        jc:
        $KU = $this->instance_helper->get_accounts_instance();
        if ($Yh->mo_oauth_client_get_option("\166\x65\162\x69\146\x79\x5f\143\x75\163\x74\157\155\145\x72") === "\164\x72\165\145") {
            goto iO;
        }
        if (trim($Yh->mo_oauth_client_get_option("\x6d\x6f\137\157\x61\165\164\x68\x5f\x61\x64\155\151\x6e\x5f\145\155\141\151\154")) !== '' && trim($Yh->mo_oauth_client_get_option("\x6d\157\137\157\x61\165\x74\x68\x5f\141\x64\x6d\x69\156\x5f\141\x70\x69\x5f\153\145\x79")) === '' && $Yh->mo_oauth_client_get_option("\156\x65\x77\x5f\162\x65\x67\x69\x73\164\162\141\164\151\157\x6e") !== "\x74\x72\165\145") {
            goto mL;
        }
        if (!$Yh->mo_oauth_is_clv() && $Yh->check_versi(1) && $Yh->mo_oauth_is_customer_registered()) {
            goto zv;
        }
        $KU->register();
        goto iG;
        iO:
        $KU->verify_password_ui();
        goto iG;
        mL:
        $KU->verify_password_ui();
        goto iG;
        zv:
        $KU->mo_oauth_lp();
        iG:
        goto U3;
        cX:
        $this->instance_helper->get_customization_instance()->render_free_ui();
        goto U3;
        DI:
        $this->instance_helper->get_sign_in_settings_instance()->render_free_ui();
        goto U3;
        X0:
        $this->instance_helper->get_subsite_settings()->render_ui();
        goto U3;
        oG:
        $this->instance_helper->get_user_analytics()->render_ui();
        goto U3;
        Ut:
        (new Licensing())->show_licensing_page();
        goto U3;
        ud:
        $this->instance_helper->get_requestdemo_instance()->render_free_ui();
        goto U3;
        I0:
        (new MoAddons())->addons_page();
        U3:
    }
}
