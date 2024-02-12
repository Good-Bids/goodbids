<?php


namespace MoOauthClient\Free;

use MoOauthClient\AppUI;
use MoOauthClient\App\UpdateAppUI;
use MoOauthClient\AppGuider;
class ClientAppUI
{
    private $common_app_ui;
    public function __construct()
    {
        $this->common_app_ui = new AppUI();
    }
    public function render_free_ui()
    {
        global $Yh;
        $Wb = $Yh->get_plugin_config()->get_current_config();
        $g9 = "\144\x69\163\x61\142\x6c\145\x64";
        $g9 = $Yh->mo_oauth_aemoutcrahsaphtn();
        $rF = $this->common_app_ui->get_apps_list();
        if (!($g9 == "\x64\x69\x73\141\142\x6c\145\144")) {
            goto pZ;
        }
        if (!(isset($_GET["\x61\143\x74\x69\157\x6e"]) && "\144\145\154\x65\x74\145" === sanitize_text_field(wp_unslash($_GET["\141\143\x74\151\x6f\x6e"])))) {
            goto vi;
        }
        if (!(isset($_GET["\141\160\160"]) && check_admin_referer("\155\157\x5f\x6f\141\165\164\150\137\144\x65\x6c\x65\x74\145\x5f" . sanitize_text_field(wp_unslash($_GET["\x61\x70\x70"]))))) {
            goto CD;
        }
        $this->common_app_ui->delete_app(sanitize_text_field(wp_unslash($_GET["\141\160\160"])));
        return;
        CD:
        vi:
        pZ:
        if (!(isset($_GET["\141\x63\x74\x69\x6f\x6e"]) && "\x69\x6e\163\164\162\165\x63\x74\151\x6f\x6e\x73" === $_GET["\x61\143\x74\x69\x6f\x6e"] || isset($_GET["\x73\x68\157\167"]) && "\x69\156\163\x74\162\165\143\x74\151\157\156\163" === $_GET["\x73\150\x6f\x77"])) {
            goto uU;
        }
        if (!(isset($_GET["\x61\x70\160\x49\x64"]) && isset($_GET["\x66\157\x72"]))) {
            goto ly;
        }
        $NB = new AppGuider($_GET["\x61\160\160\x49\144"], $_GET["\146\x6f\162"]);
        $NB->show_guide();
        ly:
        if (!(isset($_GET["\163\150\157\167"]) && "\x69\156\x73\x74\x72\165\x63\x74\x69\x6f\156\x73" === $_GET["\x73\150\157\167"])) {
            goto Yf;
        }
        $NB = new AppGuider($_GET["\x61\x70\160\x49\144"]);
        $NB->show_guide();
        $this->common_app_ui->add_app_ui();
        return;
        Yf:
        uU:
        if (!(isset($_GET["\141\x63\164\151\x6f\x6e"]) && "\141\x64\144" === $_GET["\141\143\x74\x69\x6f\x6e"])) {
            goto Af;
        }
        $this->common_app_ui->add_app_ui();
        return;
        Af:
        if (!(isset($_GET["\x61\x63\164\x69\x6f\156"]) && "\x75\160\x64\141\164\x65" === $_GET["\141\143\164\x69\157\x6e"])) {
            goto E9;
        }
        if (!isset($_GET["\141\160\x70"])) {
            goto PA;
        }
        $F8 = $this->common_app_ui->get_app_by_name($_GET["\x61\x70\x70"]);
        new UpdateAppUI($_GET["\141\x70\160"], $F8);
        return;
        PA:
        E9:
        if (!(isset($_GET["\141\143\x74\151\x6f\156"]) && "\x61\144\x64\137\156\145\167" === $_GET["\x61\x63\x74\151\157\156"])) {
            goto Gv;
        }
        $this->common_app_ui->add_app_ui();
        return;
        Gv:
        if (!(is_array($rF) && count($rF) > 0)) {
            goto H0;
        }
        $this->common_app_ui->show_apps_list_page();
        return;
        H0:
        $this->common_app_ui->add_app_ui();
    }
}
