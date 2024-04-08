<?php


namespace MoOauthClient\Enterprise;

use MoOauthClient\Premium\LoginHandler as PremiumLoginHandler;
use MoOauthClient\Enterprise\UserAnalyticsDBOps;
use MoOauthClient\MO_Oauth_Debug;
class LoginHandler extends PremiumLoginHandler
{
    public function mo_oauth_client_generate_authorization_url($NF, $gR)
    {
        global $Uj;
        $NF = parent::mo_oauth_client_generate_authorization_url($NF, $gR);
        $ro = $Uj->parse_url($NF);
        $Kn = $Uj->get_plugin_config();
        $cr = $Kn->get_config("\144\171\156\x61\x6d\151\143\137\x63\141\x6c\x6c\142\141\143\153\137\x75\x72\x6c");
        if (!(isset($cr) && '' !== $cr)) {
            goto Jw;
        }
        $ro["\x71\165\x65\x72\x79"]["\162\145\144\151\x72\x65\143\164\x5f\x75\162\x69"] = $cr;
        return $Uj->generate_url($ro);
        Jw:
        return $NF;
    }
    public function check_status($z5, $IP)
    {
        global $Uj;
        $yT = new UserAnalyticsDBOps();
        if (isset($z5["\x73\x74\x61\x74\165\x73"])) {
            goto x_;
        }
        if (!$IP) {
            goto I7;
        }
        $yT->add_transact($z5, true);
        I7:
        $Uj->handle_error("\123\x6f\155\x65\x74\x68\151\x6e\x67\x20\x77\145\x6e\164\40\167\162\157\x6e\147\x2e\40\x50\154\145\141\x73\x65\x20\x74\162\x79\x20\x4c\157\147\147\151\x6e\147\x20\x69\156\40\x61\x67\141\x69\x6e\x2e");
        MO_Oauth_Debug::mo_oauth_log("\123\157\x6d\x65\164\150\x69\x6e\x67\40\167\x65\x6e\x74\40\167\x72\x6f\156\x67\x2e\40\x50\154\145\141\163\x65\x20\164\162\171\x20\x4c\157\x67\147\x69\x6e\147\x20\x69\x6e\x20\x61\147\x61\151\x6e\56");
        wp_die(wp_kses("\123\x6f\155\145\x74\x68\x69\156\147\x20\x77\145\156\164\40\x77\162\x6f\156\x67\56\40\x50\x6c\x65\141\163\x65\x20\164\162\x79\x20\114\x6f\147\147\151\x6e\147\x20\151\156\x20\141\x67\x61\x69\156\x2e", \mo_oauth_get_valid_html()));
        x_:
        if (!$IP) {
            goto gJ;
        }
        $yT->add_transact($z5);
        gJ:
        if (!(true !== $z5["\163\164\141\164\x75\163"])) {
            goto UL;
        }
        $eZ = isset($z5["\x6d\x73\x67"]) && !empty($z5["\x6d\x73\x67"]) ? $z5["\x6d\163\x67"] : "\123\x6f\155\145\x74\x68\151\156\x67\40\167\145\x6e\164\x20\x77\x72\x6f\x6e\x67\56\40\x50\154\145\141\163\x65\40\x74\x72\x79\x20\114\x6f\147\x67\x69\156\147\40\151\156\x20\141\x67\x61\151\156\x2e";
        $Uj->handle_error($eZ);
        MO_Oauth_Debug::mo_oauth_log($eZ);
        wp_die(wp_kses($eZ, \mo_oauth_get_valid_html()));
        exit;
        UL:
    }
}
