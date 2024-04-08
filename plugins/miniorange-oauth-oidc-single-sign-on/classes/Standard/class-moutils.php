<?php


namespace MoOauthClient\Standard;

use MoOauthClient\MOUtils as CommonUtils;
class MOUtils extends CommonUtils
{
    private function manage_deactivate_cache()
    {
        global $Uj;
        $eh = $Uj->mo_oauth_client_get_option("\x6d\157\137\157\x61\165\x74\150\137\x6c\153");
        if (!(!$Uj->mo_oauth_is_customer_registered() || false === $eh || empty($eh))) {
            goto e0H;
        }
        return;
        e0H:
        $XD = $Uj->mo_oauth_client_get_option("\150\157\x73\x74\137\156\141\x6d\x65");
        $ht = $XD . "\x2f\155\157\141\x73\57\141\x70\x69\57\x62\141\x63\153\165\x70\143\x6f\144\x65\57\165\x70\x64\141\164\x65\x73\x74\x61\164\x75\x73";
        $i_ = $Uj->mo_oauth_client_get_option("\155\x6f\x5f\157\x61\165\x74\x68\137\141\x64\155\x69\156\137\x63\165\163\164\x6f\x6d\x65\162\x5f\153\x65\171");
        $aG = $Uj->mo_oauth_client_get_option("\x6d\x6f\137\x6f\141\165\x74\150\x5f\x61\x64\155\151\156\x5f\x61\x70\x69\137\153\x65\171");
        $SJ = $Uj->mooauthdecrypt($eh);
        $a7 = round(microtime(true) * 1000);
        $a7 = number_format($a7, 0, '', '');
        $NS = $i_ . $a7 . $aG;
        $jm = hash("\163\150\141\65\x31\62", $NS);
        $n2 = "\103\165\x73\164\157\155\145\x72\55\113\x65\171\x3a\40" . $i_;
        $uS = "\124\x69\x6d\x65\x73\x74\141\155\x70\72\40" . $a7;
        $fq = "\101\x75\x74\x68\157\162\x69\x7a\x61\164\x69\157\156\72\x20" . $jm;
        $vX = '';
        $vX = array("\x63\x6f\x64\145" => $SJ, "\x63\x75\163\164\x6f\x6d\x65\x72\113\145\171" => $i_, "\141\144\144\x69\x74\x69\x6f\156\141\154\x46\151\145\x6c\144\163" => array("\x66\151\145\154\144\x31" => site_url()));
        $n6 = wp_json_encode($vX);
        $l9 = array("\x43\x6f\x6e\164\145\156\x74\x2d\124\x79\160\145" => "\141\x70\160\x6c\x69\x63\x61\164\x69\x6f\x6e\x2f\152\163\x6f\156");
        $l9["\103\165\x73\x74\x6f\x6d\x65\x72\x2d\x4b\145\x79"] = $i_;
        $l9["\124\x69\x6d\x65\x73\x74\x61\155\160"] = $a7;
        $l9["\x41\165\164\150\157\x72\x69\x7a\141\164\x69\157\156"] = $jm;
        $z5 = array("\155\145\164\150\157\144" => "\x50\x4f\x53\124", "\x62\157\x64\171" => $n6, "\164\151\155\x65\157\x75\x74" => "\x35", "\162\x65\144\151\x72\x65\x63\164\x69\157\x6e" => "\65", "\150\164\x74\160\166\x65\x72\163\x69\x6f\156" => "\x31\x2e\x30", "\x62\154\x6f\x63\x6b\151\x6e\147" => true, "\x68\x65\x61\144\x65\162\x73" => $l9);
        $Yx = wp_remote_post($ht, $z5);
        if (!is_wp_error($Yx)) {
            goto G1W;
        }
        $XS = $Yx->get_error_message();
        echo "\x53\157\x6d\x65\164\150\151\x6e\x67\x20\x77\145\x6e\x74\x20\x77\162\x6f\x6e\147\72\x20{$XS}";
        exit;
        G1W:
        return wp_remote_retrieve_body($Yx);
    }
    public function deactivate_plugin()
    {
        $this->manage_deactivate_cache();
        parent::deactivate_plugin();
        $this->mo_oauth_client_delete_option("\155\x6f\137\x6f\141\x75\164\x68\137\154\x6b");
        $this->mo_oauth_client_delete_option("\155\x6f\x5f\157\x61\165\x74\x68\137\154\166");
    }
    public function is_url($ht)
    {
        $Yx = [];
        if (empty($ht)) {
            goto V0a;
        }
        $Yx = @get_headers($ht) ? @get_headers($ht) : [];
        V0a:
        $E1 = preg_grep("\x2f\x28\56\52\51\x32\60\x30\40\117\113\x2f", $Yx);
        return (bool) (sizeof($E1) > 0);
    }
}
