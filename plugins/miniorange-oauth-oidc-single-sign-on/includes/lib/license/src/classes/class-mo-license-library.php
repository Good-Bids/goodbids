<?php


namespace MoOauthClient\LicenseLibrary\Classes;

use MoOauthClient\LicenseLibrary\Handlers\Mo_License_Actions_Handler;
use MoOauthClient\LicenseLibrary\Handlers\Mo_License_Add_View_Handler;
use MoOauthClient\LicenseLibrary\Utils\Mo_License_Actions_Utility;
use MoOauthClient\LicenseLibrary\Views\Mo_License_Notice_Views;
use MoOauthClient\LicenseLibrary\Mo_License_Service;
if (defined("\x41\x42\123\x50\101\124\110")) {
    goto pEL;
}
exit;
pEL:
class Mo_License_Library
{
    private $license_expiry_date;
    public static $environment_type;
    private $license_actions;
    private $license_actions_handler;
    private $license_views;
    private $license_add_view_handler;
    private $license_add_view_actions;
    public function __construct()
    {
        if (!Mo_License_Service::is_customer_license_verified()) {
            goto YJM;
        }
        $this->set_license_expiry();
        $this->set_environment_type();
        $this->add_license_actions();
        $this->add_license_views();
        YJM:
    }
    private function add_license_actions()
    {
        $this->license_actions_handler = new Mo_License_Actions_Handler($this->license_expiry_date);
        $this->license_actions = new Mo_License_Actions($this->license_actions_handler);
    }
    private function add_license_views()
    {
        $this->license_views = new Mo_License_Notice_Views();
        $this->license_add_view_handler = new Mo_License_Add_View_Handler($this->license_views);
        $this->license_add_view_actions = new Mo_License_Add_View_Actions($this->license_add_view_handler);
    }
    private function set_license_expiry()
    {
        $this->license_expiry_date = Mo_License_Service::get_expiry_date();
    }
    private function set_environment_type()
    {
        self::$environment_type = Mo_License_Actions_Utility::get_environment_type();
    }
}
