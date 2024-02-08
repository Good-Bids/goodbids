<?php


namespace MoOauthClient\LicenseLibrary\Classes;

use MoOauthClient\LicenseLibrary\Handlers\Mo_License_Actions_Handler;
use MoOauthClient\LicenseLibrary\Mo_License_Config;
use MoOauthClient\LicenseLibrary\Utils\Mo_License_Actions_Utility;
if (defined("\101\x42\x53\x50\101\124\x48")) {
    goto jLw;
}
exit;
jLw:
class Mo_License_Actions
{
    private $license_action_handler;
    public function __construct($Ji)
    {
        $this->license_action_handler = $Ji;
        $this->add_license_actions();
    }
    public function add_license_actions()
    {
        add_action("\x69\156\x69\164", array($this->license_action_handler, "\162\x75\156\x5f\154\151\143\x65\x6e\163\145\137\143\162\157\156"));
        add_action("\141\x64\x6d\x69\156\x5f\151\156\151\164", array($this->license_action_handler, "\144\x69\163\x6d\x69\163\163\137\141\144\x6d\151\156\x5f\x6c\x69\x63\145\156\x73\x65\x5f\156\x6f\164\x69\143\x65"));
        add_action("\x61\x64\155\151\x6e\137\151\x6e\151\x74", array($this->license_action_handler, "\162\x65\146\162\x65\x73\150\137\141\x64\x6d\151\x6e\x5f\x77\151\144\x67\145\x74\137\145\x78\x70\x69\x72\171"));
    }
}
