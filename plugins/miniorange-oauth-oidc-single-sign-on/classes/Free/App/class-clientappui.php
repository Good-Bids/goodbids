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
        global $Uj;
        $Kn = $Uj->get_plugin_config()->get_current_config();
        $MS = "\144\151\163\x61\142\x6c\145\144";
        if (empty($Kn["\x6d\x6f\x5f\144\x74\x65\x5f\x73\x74\x61\164\x65"])) {
            goto BI;
        }
        $MS = $Uj->mooauthdecrypt($Kn["\155\157\137\x64\164\x65\137\x73\x74\141\164\145"]);
        BI:
        $Vb = $this->common_app_ui->get_apps_list();
        if (!($MS == "\x64\151\x73\141\x62\154\145\x64")) {
            goto mn;
        }
        if (!(isset($_GET["\x61\x63\x74\x69\x6f\156"]) && "\144\x65\154\145\164\x65" === $_GET["\x61\143\164\151\x6f\x6e"])) {
            goto Jk;
        }
        if (!isset($_GET["\141\160\x70"])) {
            goto ly;
        }
        $this->common_app_ui->delete_app($_GET["\x61\160\160"]);
        return;
        ly:
        Jk:
        mn:
        if (!(isset($_GET["\x61\x63\x74\x69\x6f\156"]) && "\x69\x6e\163\164\x72\x75\143\x74\151\x6f\x6e\163" === $_GET["\x61\x63\164\x69\x6f\156"] || isset($_GET["\x73\150\x6f\x77"]) && "\x69\156\x73\x74\162\165\143\164\x69\157\156\x73" === $_GET["\163\x68\157\167"])) {
            goto TC;
        }
        if (!(isset($_GET["\141\x70\160\x49\x64"]) && isset($_GET["\x66\x6f\162"]))) {
            goto Hr;
        }
        $iz = new AppGuider($_GET["\141\160\160\x49\144"], $_GET["\146\x6f\x72"]);
        $iz->show_guide();
        Hr:
        if (!(isset($_GET["\163\x68\157\x77"]) && "\x69\156\163\x74\162\165\x63\164\151\x6f\156\x73" === $_GET["\163\150\x6f\167"])) {
            goto Q9;
        }
        $iz = new AppGuider($_GET["\141\160\x70\x49\144"]);
        $iz->show_guide();
        $this->common_app_ui->add_app_ui();
        return;
        Q9:
        TC:
        if (!(isset($_GET["\x61\x63\164\151\x6f\156"]) && "\x61\x64\x64" === $_GET["\x61\143\164\x69\x6f\x6e"])) {
            goto Nh;
        }
        $this->common_app_ui->add_app_ui();
        return;
        Nh:
        if (!(isset($_GET["\x61\143\x74\x69\x6f\156"]) && "\x75\x70\x64\141\x74\145" === $_GET["\141\x63\164\151\157\x6e"])) {
            goto Aj;
        }
        if (!isset($_GET["\x61\160\x70"])) {
            goto Cy;
        }
        $Fr = $this->common_app_ui->get_app_by_name($_GET["\141\x70\x70"]);
        new UpdateAppUI($_GET["\x61\160\x70"], $Fr);
        return;
        Cy:
        Aj:
        if (!(isset($_GET["\x61\x63\x74\151\157\156"]) && "\141\144\x64\137\x6e\145\x77" === $_GET["\141\x63\x74\x69\157\x6e"])) {
            goto i5;
        }
        $this->common_app_ui->add_app_ui();
        return;
        i5:
        if (!(is_array($Vb) && count($Vb) > 0)) {
            goto Lq;
        }
        $this->common_app_ui->show_apps_list_page();
        return;
        Lq:
        $this->common_app_ui->add_app_ui();
    }
}
