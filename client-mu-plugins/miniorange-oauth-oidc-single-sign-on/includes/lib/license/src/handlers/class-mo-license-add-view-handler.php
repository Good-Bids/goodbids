<?php


namespace MoOauthClient\LicenseLibrary\Handlers;

use MoOauthClient\LicenseLibrary\Classes\Mo_License_Constants;
use MoOauthClient\LicenseLibrary\Classes\Mo_License_Library;
use MoOauthClient\LicenseLibrary\Mo_License_Config;
use MoOauthClient\LicenseLibrary\Mo_License_Service;
use MoOauthClient\LicenseLibrary\Views\Mo_License_Notice_Views;
if (defined("\101\102\123\120\x41\124\x48")) {
    goto v5R;
}
exit;
v5R:
class Mo_License_Add_View_Handler
{
    private $license_views;
    public function __construct($MJ)
    {
        $this->license_views = $MJ;
    }
    public function add_admin_license_notice()
    {
        if (!current_user_can("\155\x61\156\x61\x67\145\x5f\157\160\164\x69\157\x6e\163")) {
            goto R57;
        }
        $Qr = $this->license_views->get_license_notice();
        echo $Qr;
        R57:
    }
    public function add_dashboard_license_widget()
    {
        if (!(Mo_License_Service::is_customer_license_verified() && current_user_can("\155\x61\x6e\x61\x67\145\137\157\160\164\x69\x6f\156\x73"))) {
            goto YHY;
        }
        global $wp_meta_boxes;
        wp_add_dashboard_widget(Mo_License_Constants::DASHBOARD_WIDGET_ID, Mo_License_Config::PLUGIN_NAME, array($this->license_views, "\x64\151\163\160\154\x61\171\x5f\144\x61\163\x68\x62\x6f\x61\162\144\137\x77\x69\144\x67\145\x74"));
        $bg = "\x64\x61\x73\x68\142\x6f\141\x72\x64";
        if (!("\156\x65\164\x77\x6f\162\x6b" === Mo_License_Library::$environment_type)) {
            goto sQd;
        }
        $bg = "\144\141\163\150\142\157\x61\x72\x64\x2d\156\145\x74\x77\x6f\x72\153";
        sQd:
        $GL = $wp_meta_boxes[$bg]["\156\x6f\162\155\141\x6c"]["\143\157\x72\x65"];
        $Pr = array(Mo_License_Constants::DASHBOARD_WIDGET_ID => $GL[Mo_License_Constants::DASHBOARD_WIDGET_ID]);
        unset($GL[Mo_License_Constants::DASHBOARD_WIDGET_ID]);
        $GL = !empty($GL) ? $GL : array();
        $tj = array_merge($Pr, $GL);
        $wp_meta_boxes[$bg]["\156\157\x72\155\x61\154"]["\x63\157\x72\x65"] = $tj;
        YHY:
    }
    public function add_plugin_license_scripts()
    {
        wp_enqueue_style("\155\x6f\x5f\163\x61\x6d\154\x5f\x6c\x69\143\x65\x6e\163\145\137\166\x69\145\x77\137\163\x74\x79\154\x65", MO_LICENSE_LIBRARY_PATH . "\x76\x69\x65\167\x73\57\151\x6e\143\154\165\x64\x65\x73\x2f\x63\x73\x73\x2f\154\x69\x63\x65\x6e\163\145\55\166\x69\145\x77\163\55\163\164\x79\x6c\x65\x2e\143\x73\x73", array(), Mo_License_Constants::VERSION);
    }
}
