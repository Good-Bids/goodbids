<?php


namespace MoOauthClient\LicenseLibrary\Classes;

use MoOauthClient\LicenseLibrary\Handlers\Mo_License_Add_View_Handler;
use MoOauthClient\LicenseLibrary\Mo_License_Config;
use MoOauthClient\LicenseLibrary\Utils\Mo_License_Actions_Utility;
if (defined("\101\x42\123\x50\101\124\110")) {
    goto iME;
}
exit;
iME:
class Mo_License_Add_View_Actions
{
    private $license_add_view_handler;
    public function __construct($tr)
    {
        $this->license_add_view_handler = $tr;
        $this->add_license_views();
    }
    public function add_license_views()
    {
        add_action("\141\x64\155\x69\156\137\x65\156\x71\x75\145\x75\145\x5f\x73\143\x72\151\x70\164\163", array($this->license_add_view_handler, "\141\x64\144\x5f\x70\154\x75\147\151\x6e\x5f\x6c\x69\x63\145\x6e\163\x65\x5f\x73\143\162\151\x70\x74\x73"));
        add_action(Mo_License_Actions_Utility::get_current_environment_hook_name("\x61\144\155\x69\156\137\156\x6f\164\x69\x63\x65"), array($this->license_add_view_handler, "\x61\144\144\137\141\x64\155\x69\156\137\154\151\x63\x65\x6e\163\145\137\x6e\157\164\x69\x63\145"));
        if (!Mo_License_Config::ADD_DASHBOARD_WIDGET) {
            goto KWx;
        }
        add_action(Mo_License_Actions_Utility::get_current_environment_hook_name("\x64\141\163\150\142\x6f\x61\x72\x64\137\x77\x69\x64\147\x65\164"), array($this->license_add_view_handler, "\141\144\144\x5f\144\141\x73\150\142\x6f\x61\162\x64\137\x6c\x69\x63\145\156\163\145\x5f\167\151\x64\147\x65\164"));
        KWx:
    }
}
