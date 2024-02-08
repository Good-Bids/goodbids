<?php


namespace MoOauthClient\Enterprise;

use MoOauthClient\Premium\LoginHandler as PremiumLoginHandler;
use MoOauthClient\Enterprise\UserAnalyticsDBOps;
use MoOauthClient\MO_Oauth_Debug;
class LoginHandler extends PremiumLoginHandler
{
    public function mo_oauth_client_generate_authorization_url($sx, $zl)
    {
        global $Yh;
        $sx = parent::mo_oauth_client_generate_authorization_url($sx, $zl);
        $jt = $Yh->parse_url($sx);
        $Wb = $Yh->get_plugin_config();
        return $sx;
    }
    public function check_status($uo, $Kf)
    {
        global $Yh;
        $ja = new UserAnalyticsDBOps();
        if (isset($uo["\163\164\x61\x74\x75\163"])) {
            goto WZ;
        }
        if (!$Kf) {
            goto FZ;
        }
        $ja->add_transact($uo, true);
        FZ:
        $Yh->handle_error("\x53\157\155\145\x74\150\x69\x6e\147\40\167\x65\x6e\164\x20\x77\x72\x6f\156\147\56\40\x50\x6c\x65\141\x73\x65\40\x74\162\x79\x20\114\x6f\x67\147\151\x6e\x67\x20\151\x6e\40\141\147\x61\151\156\x2e");
        MO_Oauth_Debug::mo_oauth_log("\123\x6f\x6d\145\164\x68\151\156\147\x20\167\x65\x6e\164\40\167\162\x6f\x6e\147\56\40\x50\x6c\x65\x61\x73\145\x20\x74\x72\x79\40\x4c\157\x67\x67\151\x6e\x67\40\151\x6e\40\141\147\x61\151\x6e\56");
        wp_die("\123\x6f\x6d\145\x74\x68\x69\x6e\147\x20\167\145\x6e\164\40\167\x72\157\x6e\147\56\40\120\154\x65\141\x73\145\40\164\162\x79\x20\x4c\157\147\147\151\156\x67\x20\151\x6e\40\x61\x67\141\x69\x6e\56", \mo_oauth_get_valid_html());
        WZ:
        if (!$Kf) {
            goto ii;
        }
        $ja->add_transact($uo);
        ii:
        if (!(true !== $uo["\163\164\x61\164\165\163"])) {
            goto Uc;
        }
        $q3 = isset($uo["\x6d\163\147"]) && !empty($uo["\x6d\163\x67"]) ? $uo["\155\163\147"] : "\123\157\155\145\x74\150\151\156\x67\40\x77\145\156\164\x20\x77\162\157\x6e\x67\56\x20\x50\154\x65\141\163\x65\x20\x74\162\x79\x20\114\x6f\147\x67\151\156\x67\40\x69\x6e\x20\141\147\x61\151\156\x2e";
        $Yh->handle_error($q3);
        MO_Oauth_Debug::mo_oauth_log($q3);
        wp_die(wp_kses($q3, \mo_oauth_get_valid_html()));
        exit;
        Uc:
    }
}
