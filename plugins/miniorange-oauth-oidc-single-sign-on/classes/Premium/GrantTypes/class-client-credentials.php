<?php


namespace MoOauthClient\GrantTypes;

use MoOauthClient\OauthHandler;
use MoOauthClient\Base\InstanceHelper;
use MoOauthClient\MO_Oauth_Debug;
class ClientCredentials
{
    public function __construct()
    {
        add_action("\151\x6e\151\x74", array($this, "\x62\x65\150\141\166\x65"));
    }
    public function get_token_response($zl = '', $m8 = false)
    {
        global $Yh;
        $zl = !empty($zl) ? $zl : false;
        if ($zl) {
            goto a0;
        }
        $Yh->handle_error("\x49\156\166\141\154\151\x64\x20\101\160\160\154\x69\x63\141\164\x69\157\156\x20\116\x61\x6d\145");
        MO_Oauth_Debug::mo_oauth_log("\105\x72\162\x6f\162\x20\146\162\x6f\155\x20\124\x6f\x6b\145\x6e\x20\105\156\x64\x70\157\x69\156\x74\x20\x3d\x3e\40\111\x6e\166\141\x6c\x69\x64\x20\x41\160\x70\x6c\151\143\141\164\151\157\156\40\116\x61\155\x65");
        exit("\111\x6e\x76\x61\x6c\x69\144\x20\101\x70\x70\154\x69\x63\141\x74\151\157\x6e\40\116\141\155\x65");
        a0:
        $F8 = $Yh->get_app_by_name($zl);
        if ($F8) {
            goto Vz;
        }
        MO_Oauth_Debug::mo_oauth_log("\x45\162\162\x6f\x72\x20\146\x72\157\x6d\40\124\157\x6b\145\x6e\x20\x45\x6e\x64\x70\157\151\156\164\x20\x3d\x3e\x20\111\x6e\166\141\154\x69\x64\40\101\x70\160\x6c\x69\x63\141\x74\x69\x6f\x6e\40\116\141\x6d\145");
        return "\x4e\x6f\40\x61\x70\160\154\151\x63\x61\164\x69\157\x6e\x20\146\157\x75\156\144";
        Vz:
        $KY = $F8->get_app_config();
        $uo = array("\x67\162\x61\x6e\x74\x5f\164\x79\x70\145" => "\x63\x6c\x69\x65\156\x74\x5f\x63\162\x65\x64\145\156\x74\x69\x61\154\x73", "\143\x6c\x69\145\156\164\137\x69\144" => $KY["\143\154\151\145\x6e\164\x5f\151\144"], "\x63\154\x69\145\x6e\164\x5f\x73\145\143\162\145\x74" => $KY["\143\154\x69\145\156\164\137\163\145\x63\x72\x65\164"], "\163\x63\x6f\x70\145" => $F8->get_app_config("\x73\143\x6f\160\145"));
        $pY = new OauthHandler();
        $pd = $KY["\x61\143\143\145\x73\x73\164\157\153\x65\x6e\x75\162\x6c"];
        if (!(strpos($pd, "\x67\x6f\157\x67\x6c\145") !== false)) {
            goto PX;
        }
        $pd = "\x68\164\164\x70\163\x3a\57\x2f\x77\167\167\56\x67\x6f\157\x67\154\x65\x61\x70\151\x73\56\x63\x6f\155\x2f\x6f\141\x75\x74\150\x32\57\166\x34\x2f\x74\157\153\x65\156";
        PX:
        $U_ = isset($KY["\x73\145\x6e\x64\x5f\x68\x65\141\x64\x65\x72\x73"]) ? $KY["\163\145\x6e\x64\x5f\150\x65\141\144\145\x72\163"] : 0;
        $fn = isset($KY["\163\x65\156\144\x5f\x62\157\x64\x79"]) ? $KY["\163\x65\156\x64\x5f\x62\x6f\144\x79"] : 0;
        $TD = $pY->get_token($pd, $uo, $U_, $fn);
        $o2 = \json_decode($TD, true);
        MO_Oauth_Debug::mo_oauth_log("\x54\157\x6b\145\156\x20\105\156\x64\160\x6f\151\156\164\40\x72\x65\x73\160\x6f\156\163\145\x20\x3d\x3e\x20" . $TD);
        $C2 = isset($o2["\x61\143\143\145\x73\x73\x5f\x74\x6f\153\x65\x6e"]) ? $o2["\141\x63\x63\x65\163\x73\x5f\x74\157\153\x65\156"] : false;
        $A2 = isset($o2["\x69\x64\137\x74\x6f\x6b\x65\156"]) ? $o2["\151\144\x5f\164\x6f\153\145\156"] : false;
        $KH = isset($o2["\x74\x6f\x6b\145\x6e"]) ? $o2["\x74\x6f\153\x65\156"] : false;
        if ($C2) {
            goto Av;
        }
        $Yh->handle_error("\111\x6e\x76\x61\154\151\x64\x20\164\157\x6b\145\156\40\x72\x65\x63\145\x69\x76\145\144\x2e");
        MO_Oauth_Debug::mo_oauth_log("\105\162\162\x6f\x72\x20\x66\162\157\x6d\40\x54\x6f\153\x65\x6e\40\105\156\x64\x70\157\151\156\164\x20\x3d\x3e\x20\111\x6e\166\141\x6c\151\144\x20\101\160\x70\154\x69\143\x61\164\x69\x6f\156\40\x4e\x61\x6d\x65");
        exit("\111\x6e\166\x61\x6c\151\144\x20\164\157\x6b\145\x6e\x20\162\x65\x63\x65\151\x76\x65\144\56");
        Av:
        MO_Oauth_Debug::mo_oauth_log("\101\x63\143\145\x73\163\40\x54\x6f\153\x65\156\x20\x3d\76\40" . $C2);
        return $TD;
    }
}
