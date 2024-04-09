<?php


namespace MoOauthClient\Standard;

use MoOauthClient\MOUtils as CommonUtils;
use MoOauthClient\LicenseLibrary\Mo_License_Service;
class MOUtils extends CommonUtils
{
    private function manage_deactivate_cache()
    {
        global $Yh;
        $aF = $Yh->mo_oauth_client_get_option("\155\x6f\x5f\x6f\x61\165\x74\x68\x5f\x6c\153");
        if (!(!$Yh->mo_oauth_is_customer_registered() || false === $aF || empty($aF))) {
            goto VBZ;
        }
        return;
        VBZ:
        $YB = $Yh->mo_oauth_client_get_option("\x68\x6f\163\164\137\156\141\155\x65");
        $Ws = $YB . "\x2f\x6d\157\141\x73\57\141\x70\151\x2f\x62\141\143\x6b\x75\160\x63\157\144\x65\x2f\x75\x70\x64\141\164\145\163\164\141\x74\165\163";
        $oe = $Yh->mo_oauth_client_get_option("\x6d\x6f\137\157\x61\165\x74\x68\137\x61\x64\x6d\151\156\x5f\x63\x75\163\164\x6f\x6d\x65\x72\137\153\145\171");
        $hC = $Yh->mo_oauth_client_get_option("\155\157\x5f\157\141\165\x74\x68\137\x61\x64\155\x69\156\x5f\141\160\151\x5f\x6b\x65\171");
        $g0 = $Yh->mooauthdecrypt($aF);
        $zj = round(microtime(true) * 1000);
        $zj = number_format($zj, 0, '', '');
        $bF = $oe . $zj . $hC;
        $lY = hash("\163\x68\141\65\x31\x32", $bF);
        $qP = "\103\x75\163\x74\x6f\x6d\145\162\x2d\x4b\145\171\x3a\40" . $oe;
        $EY = "\124\x69\155\x65\163\x74\141\155\x70\72\x20" . $zj;
        $av = "\101\x75\164\150\157\162\x69\x7a\141\164\x69\x6f\156\72\x20" . $lY;
        $Ld = '';
        $Ld = array("\143\157\x64\145" => $g0, "\143\x75\163\164\157\155\145\162\x4b\x65\171" => $oe, "\x61\144\x64\x69\164\x69\157\x6e\141\154\x46\151\x65\x6c\x64\163" => array("\146\x69\145\154\144\x31" => site_url()));
        $kb = wp_json_encode($Ld);
        $k7 = array("\103\157\156\164\145\x6e\164\x2d\124\171\x70\145" => "\x61\160\x70\154\x69\x63\141\x74\x69\157\x6e\x2f\x6a\x73\157\x6e");
        $k7["\103\165\x73\164\157\155\145\x72\55\x4b\x65\x79"] = $oe;
        $k7["\124\151\x6d\145\x73\164\x61\x6d\x70"] = $zj;
        $k7["\x41\165\164\x68\157\x72\151\172\141\x74\151\157\156"] = $lY;
        $uo = array("\x6d\x65\164\x68\x6f\x64" => "\120\x4f\123\124", "\142\x6f\x64\171" => $kb, "\x74\x69\155\145\157\165\x74" => "\x31\65", "\x72\145\x64\x69\162\145\x63\x74\151\157\156" => "\x35", "\x68\x74\x74\x70\166\145\x72\x73\151\157\156" => "\x31\x2e\x30", "\142\x6c\x6f\143\153\x69\x6e\147" => true, "\x68\x65\x61\144\145\162\x73" => $k7);
        $uh = wp_remote_post($Ws, $uo);
        if (!is_wp_error($uh)) {
            goto vzd;
        }
        $Go = $uh->get_error_message();
        echo "\x53\157\x6d\145\164\150\x69\156\147\x20\x77\145\156\x74\40\x77\x72\157\156\x67\x3a\x20" . esc_attr($Go);
        exit;
        vzd:
        return wp_remote_retrieve_body($uh);
    }
    public function deactivate_plugin()
    {
        $this->manage_deactivate_cache();
        parent::deactivate_plugin();
        $this->mo_oauth_client_delete_option("\x6d\x6f\x5f\157\x61\165\164\150\x5f\x6c\153");
        $this->mo_oauth_client_delete_option("\155\x6f\137\x6f\141\165\164\150\137\x6c\166");
    }
    public function is_url($Ws)
    {
        $uh = [];
        if (empty($Ws)) {
            goto e8D;
        }
        $uh = @get_headers($Ws) ? @get_headers($Ws) : [];
        e8D:
        $sn = preg_grep("\57\50\56\x2a\51\x32\60\60\x20\x4f\113\57", $uh);
        return (bool) (sizeof($sn) > 0);
    }
    public function mo_oauth_aemoutcrahsaphtn()
    {
        if (!class_exists("\x4d\157\117\x61\165\164\x68\103\x6c\151\145\156\164\134\x4c\x69\143\x65\x6e\x73\x65\x4c\x69\x62\x72\x61\162\x79\x5c\115\157\x5f\114\151\x63\145\x6e\x73\x65\x5f\123\x65\x72\166\151\x63\145")) {
            goto eXE;
        }
        $xm = Mo_License_Service::is_license_expired();
        return !$xm["\123\x54\101\124\125\123"] ? "\144\x69\163\141\x62\x6c\145\x64" : "\145\x6e\x61\x62\154\145\144";
        eXE:
        return "\x64\x69\x73\141\142\x6c\145\144";
    }
}
