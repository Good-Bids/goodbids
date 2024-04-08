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
        add_action("\x61\144\155\151\x6e\137\x65\156\x71\165\x65\x75\x65\x5f\x73\x63\x72\151\x70\164\x73", array($this, "\160\x6c\x75\147\151\156\x5f\163\x65\164\164\151\156\147\x73\x5f\x73\164\171\154\145"));
        add_action("\141\x64\x6d\151\x6e\x5f\x65\156\161\165\x65\165\x65\137\163\143\162\151\160\164\163", array($this, "\160\154\x75\147\x69\156\137\163\145\x74\164\x69\x6e\147\x73\x5f\x73\143\162\x69\160\164"));
        $this->instance_helper = new InstanceHelper();
    }
    public function plugin_settings_style()
    {
        wp_enqueue_style("\155\157\137\157\141\x75\164\x68\137\x61\144\155\x69\156\137\163\x65\164\x74\x69\156\x67\x73\137\163\x74\171\154\x65", MOC_URL . "\162\145\x73\157\x75\x72\x63\x65\x73\x2f\x63\163\x73\x2f\163\x74\171\154\x65\x5f\x73\x65\164\164\x69\156\x67\x73\x2e\x63\x73\x73", array(), $Rb = null, $N6 = false);
        wp_enqueue_style("\x6d\x6f\137\x6f\141\x75\164\x68\x5f\141\144\x6d\151\x6e\137\163\x65\x74\x74\x69\x6e\147\x73\137\160\150\157\x6e\145\137\163\164\171\x6c\145", MOC_URL . "\x72\x65\163\x6f\x75\x72\x63\145\163\x2f\x63\163\163\57\x70\150\157\x6e\x65\x2e\x63\163\163", array(), $Rb = null, $N6 = false);
        wp_enqueue_style("\x6d\157\137\x6f\141\x75\164\x68\x5f\x61\x64\155\x69\x6e\x5f\x73\145\x74\x74\x69\156\x67\x73\137\144\141\x74\x61\164\x61\x62\x6c\145", MOC_URL . "\162\145\163\x6f\x75\162\x63\x65\x73\57\x63\163\163\57\x6a\x71\x75\x65\x72\x79\56\x64\x61\x74\141\124\x61\x62\x6c\x65\163\x2e\155\x69\156\x2e\x63\x73\x73", array(), $Rb = null, $N6 = false);
        wp_enqueue_style("\155\x6f\x2d\x77\x70\x2d\x62\x6f\157\164\163\164\x72\x61\x70\55\x73\157\x63\x69\x61\x6c", MOC_URL . "\x72\145\x73\157\x75\x72\x63\145\163\x2f\143\163\x73\57\142\157\x6f\164\x73\x74\x72\141\x70\55\x73\157\143\151\x61\x6c\x2e\143\x73\x73", array(), $Rb = null, $N6 = false);
        wp_enqueue_style("\155\x6f\55\x77\x70\x2d\142\x6f\157\164\x73\164\x72\x61\x70\x2d\155\x61\151\x6e", MOC_URL . "\162\145\x73\x6f\x75\x72\x63\145\163\x2f\x63\163\163\x2f\142\157\x6f\x74\x73\164\x72\141\160\56\x6d\151\x6e\x2d\160\x72\145\x76\151\145\x77\x2e\x63\163\163", array(), $Rb = null, $N6 = false);
        wp_enqueue_style("\x6d\157\55\x77\160\x2d\x66\157\156\x74\55\x61\x77\x65\x73\x6f\x6d\145", MOC_URL . "\x72\x65\163\x6f\165\162\143\145\163\x2f\143\x73\x73\x2f\146\x6f\156\x74\55\x61\x77\x65\163\x6f\155\x65\x2e\x6d\151\156\x2e\143\163\x73\77\166\145\x72\163\151\x6f\156\x3d\64\56\x38", array(), $Rb = null, $N6 = false);
        wp_enqueue_style("\x6d\157\55\x77\160\x2d\x66\x6f\156\164\x2d\141\x77\145\x73\x6f\155\x65", MOC_URL . "\x72\x65\163\x6f\x75\x72\143\x65\x73\x2f\143\x73\x73\57\x66\157\156\164\x2d\141\x77\x65\163\157\155\x65\x2e\x63\x73\163\77\x76\x65\x72\163\x69\157\156\x3d\64\56\70", array(), $Rb = null, $N6 = false);
        if (!(isset($_REQUEST["\x74\141\142"]) && "\x6c\151\x63\x65\156\x73\151\156\x67" === $_REQUEST["\x74\141\142"])) {
            goto tZ;
        }
        wp_enqueue_style("\155\x6f\x5f\x6f\141\x75\x74\150\137\x62\x6f\x6f\x74\x73\164\162\x61\x70\137\x63\163\163", MOC_URL . "\x72\x65\x73\x6f\165\x72\143\145\x73\57\143\x73\x73\x2f\x62\157\x6f\164\163\164\162\141\x70\x2f\142\157\x6f\x74\163\164\162\x61\160\x2e\155\151\x6e\x2e\143\163\163", array(), $Rb = null, $N6 = false);
        wp_enqueue_style("\x6d\157\x5f\157\x61\x75\x74\150\x5f\x6c\x69\143\145\156\163\145\137\x70\x61\x67\145\137\163\164\171\154\145", MOC_URL . "\x72\x65\x73\x6f\165\x72\143\x65\163\57\x63\163\x73\57\155\x6f\x2d\157\x61\x75\x74\x68\55\x6c\151\x63\145\156\x73\151\x6e\147\x2e\x63\x73\x73");
        tZ:
    }
    public function plugin_settings_script()
    {
        wp_enqueue_script("\155\x6f\x5f\x6f\141\165\164\x68\x5f\141\144\x6d\x69\156\137\x73\145\x74\164\x69\x6e\x67\x73\x5f\x73\143\162\151\x70\x74", MOC_URL . "\162\x65\x73\157\165\x72\143\145\x73\x2f\x6a\163\x2f\x73\x65\x74\x74\151\156\147\163\56\152\163", array(), $Rb = null, $N6 = false);
        wp_enqueue_script("\155\157\x5f\x6f\141\x75\x74\150\137\141\x64\155\x69\156\137\x73\145\164\164\151\x6e\x67\163\x5f\160\150\157\156\x65\x5f\x73\x63\x72\x69\160\164", MOC_URL . "\162\x65\x73\x6f\x75\x72\143\145\163\x2f\152\x73\x2f\160\150\157\x6e\x65\x2e\152\x73", array(), $Rb = null, $N6 = false);
        wp_enqueue_script("\x6d\157\137\157\141\165\164\150\x5f\141\144\155\x69\x6e\x5f\x73\x65\164\x74\x69\156\147\x73\137\144\141\164\141\x74\x61\142\x6c\145", MOC_URL . "\x72\x65\x73\x6f\x75\162\143\145\163\57\152\163\57\152\161\165\145\162\171\56\x64\x61\164\x61\x54\x61\142\154\x65\x73\56\155\151\x6e\56\152\163", array(), $Rb = null, $N6 = false);
        if (!(isset($_REQUEST["\164\x61\142"]) && "\x6c\x69\143\145\156\x73\151\156\x67" === $_REQUEST["\164\141\142"])) {
            goto yu;
        }
        wp_enqueue_script("\155\157\x5f\157\141\x75\164\150\137\155\x6f\144\x65\162\x6e\151\x7a\162\x5f\x73\x63\x72\151\x70\x74", MOC_URL . "\x72\x65\x73\157\165\162\143\145\x73\x2f\x6a\163\x2f\155\157\x64\145\162\156\151\x7a\162\56\x6a\163", array(), $Rb = null, $N6 = true);
        wp_enqueue_script("\x6d\x6f\x5f\x6f\141\x75\x74\x68\x5f\x70\157\160\x6f\x76\145\x72\x5f\163\143\x72\151\x70\164", MOC_URL . "\x72\x65\x73\x6f\x75\162\143\x65\x73\57\x6a\163\57\x62\157\x6f\x74\x73\164\x72\x61\x70\x2f\x70\x6f\160\160\145\162\56\155\x69\156\56\152\163", array(), $Rb = null, $N6 = true);
        wp_enqueue_script("\x6d\x6f\x5f\157\141\x75\164\150\x5f\x62\157\x6f\164\163\x74\x72\x61\160\137\163\x63\x72\x69\160\164", MOC_URL . "\x72\x65\163\157\x75\162\143\145\163\57\x6a\163\x2f\142\157\157\x74\x73\x74\x72\141\160\x2f\142\157\157\x74\x73\164\162\x61\x70\56\155\151\x6e\56\152\x73", array(), $Rb = null, $N6 = true);
        yu:
    }
    public function load_current_tab($pr)
    {
        global $Uj;
        $L0 = 0 === $Uj->get_versi();
        $AD = false;
        if ($L0) {
            goto Dt;
        }
        $AD = $Uj->mo_oauth_client_get_option("\x6d\157\x5f\157\141\x75\x74\x68\137\143\x6c\x69\x65\156\164\x5f\154\157\141\144\x5f\x61\x6e\141\154\x79\164\151\143\x73");
        $AD = boolval($AD) ? boolval($AD) : false;
        $L0 = $Uj->check_versi(1) && $Uj->mo_oauth_is_clv();
        Dt:
        if ("\141\143\x63\x6f\x75\x6e\164" === $pr || !$L0) {
            goto h2;
        }
        if ("\143\x75\163\x74\x6f\155\151\x7a\141\164\x69\x6f\156" === $pr && $L0) {
            goto ap;
        }
        if ("\x73\x69\x67\x6e\x69\x6e\163\145\x74\164\x69\x6e\147\163" === $pr && $L0) {
            goto s0;
        }
        if ("\x73\x75\142\163\151\164\145\x73\x65\164\x74\151\156\147\163" === $pr && $L0) {
            goto EP;
        }
        if ($AD && "\141\156\141\154\x79\164\151\x63\163" === $pr && $L0) {
            goto T6;
        }
        if ("\x6c\x69\143\145\x6e\163\x69\x6e\x67" === $pr) {
            goto aX;
        }
        if ("\162\145\161\x75\x65\x73\164\146\157\x72\x64\x65\x6d\157" === $pr && $L0) {
            goto xh;
        }
        if ("\141\144\144\x6f\x6e\163" === $pr) {
            goto Oo;
        }
        $this->instance_helper->get_clientappui_instance()->render_free_ui();
        goto p4;
        h2:
        $gE = $this->instance_helper->get_accounts_instance();
        if ($Uj->mo_oauth_client_get_option("\x76\x65\162\151\x66\171\137\x63\165\x73\x74\157\155\145\x72") === "\x74\x72\165\x65") {
            goto fz;
        }
        if (trim($Uj->mo_oauth_client_get_option("\155\157\x5f\x6f\x61\165\x74\150\x5f\141\x64\155\x69\x6e\x5f\145\x6d\x61\x69\154")) !== '' && trim($Uj->mo_oauth_client_get_option("\155\x6f\137\x6f\141\x75\x74\150\x5f\141\x64\155\x69\x6e\137\141\160\x69\137\153\x65\171")) === '' && $Uj->mo_oauth_client_get_option("\156\x65\167\137\x72\x65\x67\151\x73\164\162\x61\x74\151\157\156") !== "\x74\162\165\145") {
            goto n0;
        }
        if (!$Uj->mo_oauth_is_clv() && $Uj->check_versi(1) && $Uj->mo_oauth_is_customer_registered()) {
            goto lc;
        }
        $gE->register();
        goto cZ;
        fz:
        $gE->verify_password_ui();
        goto cZ;
        n0:
        $gE->verify_password_ui();
        goto cZ;
        lc:
        $gE->mo_oauth_lp();
        cZ:
        goto p4;
        ap:
        $this->instance_helper->get_customization_instance()->render_free_ui();
        goto p4;
        s0:
        $this->instance_helper->get_sign_in_settings_instance()->render_free_ui();
        goto p4;
        EP:
        $this->instance_helper->get_subsite_settings()->render_ui();
        goto p4;
        T6:
        $this->instance_helper->get_user_analytics()->render_ui();
        goto p4;
        aX:
        (new Licensing())->show_licensing_page();
        goto p4;
        xh:
        $this->instance_helper->get_requestdemo_instance()->render_free_ui();
        goto p4;
        Oo:
        (new MoAddons())->addons_page();
        p4:
    }
}
