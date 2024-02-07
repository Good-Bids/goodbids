<?php


namespace MoOauthClient\Enterprise;

use MoOauthClient\Config;
use MoOauthClient\Premium\SignInSettingsSettings as PremiumSignInSettingsSettings;
use MoOauthClient\Enterprise\UserAnalyticsDBOps;
class SignInSettingsSettings extends PremiumSignInSettingsSettings
{
    private $db_ops;
    public function __construct()
    {
        $this->db_ops = new UserAnalyticsDBOps();
    }
    public function change_current_config($post, $Wb)
    {
        $Wb = parent::change_current_config($post, $Wb);
        $Wb->add_config("\141\x63\164\x69\166\141\x74\145\x5f\165\163\x65\x72\137\x61\156\x61\x6c\171\x74\x69\143\x73", isset($post["\x6d\x6f\x5f\141\143\164\x69\x76\x61\x74\x65\137\165\163\x65\162\x5f\141\156\x61\154\x79\x74\151\x63\x73"]) ? stripslashes(wp_unslash($post["\155\157\137\x61\x63\x74\151\166\141\x74\145\137\165\163\x65\162\x5f\141\x6e\141\154\171\164\151\x63\x73"])) : '');
        $Wb->add_config("\144\151\163\141\x62\154\x65\x5f\167\x70\x5f\x6c\157\x67\x69\x6e", isset($post["\155\x6f\137\157\x63\137\x64\151\x73\x61\142\154\145\x5f\167\x70\x5f\x6c\157\147\151\x6e"]) ? stripslashes(wp_unslash($post["\155\x6f\x5f\157\143\x5f\x64\151\163\141\x62\154\x65\137\167\160\137\154\157\x67\151\156"])) : '');
        $Wb->add_config("\141\143\164\x69\166\x61\x74\x65\137\x73\x69\x6e\147\x6c\145\x5f\x6c\x6f\147\x69\x6e\137\146\154\157\167", isset($post["\155\x6f\x5f\x61\x63\164\151\166\141\164\145\137\163\151\156\x67\154\x65\x5f\x6c\157\147\151\x6e\x5f\146\154\x6f\x77"]) ? stripslashes(wp_unslash($post["\x6d\x6f\x5f\x61\x63\x74\151\x76\141\164\x65\x5f\163\151\x6e\147\x6c\x65\137\x6c\x6f\147\151\x6e\x5f\146\x6c\157\x77"])) : '');
        $Wb->add_config("\143\x6f\x6d\155\x6f\156\137\154\x6f\147\x69\156\137\x62\165\164\164\157\156\137\144\x69\x73\x70\x6c\x61\171\x5f\x6e\x61\155\145", isset($post["\143\157\x6d\155\157\156\137\x6c\x6f\x67\x69\x6e\137\142\x75\164\164\x6f\x6e\137\x64\x69\163\160\154\x61\x79\137\x6e\141\155\x65"]) ? stripslashes(wp_unslash($post["\x63\x6f\155\x6d\157\156\137\x6c\x6f\x67\151\x6e\x5f\142\165\x74\x74\x6f\x6e\137\144\151\x73\160\x6c\141\x79\x5f\x6e\x61\155\x65"])) : '');
        global $Yh;
        $Yh->mo_oauth_client_update_option("\x6d\x6f\137\x6f\141\x75\164\150\137\x63\x6c\x69\145\x6e\x74\x5f\144\x69\x73\x61\142\x6c\145\x5f\x77\160\137\154\157\147\x69\156", isset($post["\155\157\x5f\157\143\x5f\x64\x69\x73\x61\x62\154\x65\137\167\160\x5f\x6c\157\147\x69\x6e"]));
        $Yh->mo_oauth_client_update_option("\155\x6f\x5f\157\141\165\164\x68\137\143\154\151\x65\156\x74\137\154\157\x61\144\x5f\141\156\x61\x6c\x79\164\151\x63\163", isset($post["\155\x6f\x5f\141\x63\x74\151\166\141\x74\x65\x5f\165\163\145\x72\137\141\x6e\141\x6c\x79\x74\151\x63\x73"]));
        $Yh->mo_oauth_client_update_option("\x6d\157\x5f\x6f\x61\165\164\150\x5f\x61\143\164\151\x76\x61\164\145\x5f\x73\151\x6e\147\x6c\x65\137\x6c\x6f\147\x69\156\137\x66\154\x6f\167", isset($post["\x6d\x6f\137\141\x63\x74\151\166\x61\164\145\x5f\163\151\x6e\147\154\145\137\x6c\x6f\147\151\156\x5f\146\x6c\157\x77"]));
        $Yh->mo_oauth_client_update_option("\155\157\137\x6f\x61\x75\164\150\x5f\143\x6f\155\155\157\156\137\154\x6f\147\x69\x6e\137\142\x75\x74\x74\157\156\137\144\151\163\x70\154\x61\171\137\156\x61\x6d\x65", isset($post["\143\157\155\155\x6f\x6e\137\x6c\157\x67\151\x6e\137\x62\x75\164\164\157\x6e\x5f\144\151\163\160\154\141\x79\137\156\141\x6d\145"]) ? stripslashes(wp_unslash($post["\143\157\x6d\x6d\157\x6e\x5f\x6c\157\147\151\x6e\137\x62\x75\x74\x74\x6f\x6e\137\x64\x69\x73\160\154\x61\171\137\x6e\x61\x6d\145"])) : '');
        $this->db_ops->make_table_if_not_exists();
        do_action("\155\157\137\x70\x67\x5f\x61\x64\x64\x5f\x73\x75\x62\x73\151\164\145\x5f\x63\x6f\x6c\x5f\x74\x6f\x5f\165\x73\145\x72\137\x61\156\x61\154\171\164\x69\143\163");
        return $Wb;
    }
}
