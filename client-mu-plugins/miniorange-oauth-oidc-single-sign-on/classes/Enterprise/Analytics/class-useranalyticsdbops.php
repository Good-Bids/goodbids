<?php


namespace MoOauthClient\Enterprise;

use MoOauthClient\Mo_Oauth_Debug;
class UserAnalyticsDBOps
{
    const USER_TRANSACTIONS_TABLE = "\x77\160\156\x73\137\x74\162\x61\156\163\x61\143\x74\151\x6f\156\163";
    public function __construct()
    {
    }
    public function make_table_if_not_exists()
    {
        global $wpdb;
        $wS = "\x43\x52\x45\101\124\x45\40\124\101\102\114\x45\40\111\106\40\x4e\117\x54\x20\105\x58\x49\123\x54\123\x20" . $wpdb->base_prefix . self::USER_TRANSACTIONS_TABLE . "\x20\50\xd\12\x9\11\11\x60\x69\144\140\x20\142\151\147\151\156\x74\x20\x4e\117\124\40\116\125\114\114\40\x41\125\x54\x4f\x5f\x49\116\x43\x52\x45\x4d\x45\116\x54\40\x50\122\x49\115\101\122\x59\x20\x4b\105\131\54\x20\x20\140\x75\163\145\162\156\x61\x6d\145\140\40\155\x65\x64\151\x75\x6d\x74\145\x78\164\40\x4e\117\124\x20\116\125\114\x4c\x20\54\140\x73\164\141\x74\x75\163\x60\x20\x6d\145\144\x69\x75\155\x74\x65\170\x74\40\x4e\117\124\x20\116\125\x4c\114\x20\x2c\x60\x61\160\x70\x6e\141\x6d\145\x60\x20\155\x65\x64\x69\x75\155\164\x65\x78\164\x20\116\117\124\x20\x4e\x55\x4c\114\x2c\x20\140\145\x6d\141\x69\x6c\x60\40\155\x65\x64\151\165\155\164\145\x78\164\x20\x4e\x4f\124\x20\116\x55\114\114\x2c\x20\x60\x63\154\x69\x65\156\x74\151\160\x60\x20\x6d\x65\144\x69\165\155\164\x65\x78\x74\x20\116\117\x54\40\x4e\125\x4c\x4c\x2c\x20\x60\156\x61\166\x69\147\x61\164\x69\157\x6e\165\x72\x6c\x60\40\155\x65\x64\x69\165\x6d\164\145\x78\x74\40\x4e\x4f\124\x20\x4e\125\x4c\114\54\40\140\143\x72\x65\x61\164\145\144\x5f\x74\x69\x6d\145\x73\164\141\155\160\x60\x20\x54\x49\x4d\105\123\x54\x41\115\120\40\104\x45\106\x41\125\114\124\40\x43\125\x52\x52\x45\116\x54\x5f\x54\x49\115\105\123\124\101\x4d\x50\54\40\125\x4e\111\121\x55\105\x20\x4b\105\131\40\151\x64\x20\50\x69\144\51\x29\73";
        require_once ABSPATH . "\167\x70\x2d\x61\144\155\x69\156\x2f\x69\x6e\143\x6c\165\x64\145\x73\x2f\165\x70\147\162\x61\144\x65\x2e\160\x68\x70";
        dbDelta($wS);
        $this->add_col_if_not_exists(self::USER_TRANSACTIONS_TABLE, "\x65\x6d\x61\x69\154");
        $this->add_col_if_not_exists(self::USER_TRANSACTIONS_TABLE, "\x63\154\151\x65\156\x74\151\x70");
        $this->add_col_if_not_exists(self::USER_TRANSACTIONS_TABLE, "\156\141\166\x69\147\x61\164\151\157\x6e\165\162\x6c");
    }
    public function check_col_exists($lI = '', $cO = '')
    {
        if (!('' === $lI || '' === $cO)) {
            goto MD;
        }
        return false;
        MD:
        global $wpdb;
        $HU = $wpdb->get_results($wpdb->prepare("\123\105\x4c\105\x43\x54\x20\52\x20\106\122\117\115\x20\x49\x4e\x46\x4f\122\115\101\x54\x49\x4f\x4e\x5f\x53\x43\110\105\115\101\56\x43\117\x4c\125\x4d\x4e\123\40\x57\110\105\x52\x45\40\x54\x41\102\x4c\105\x5f\x53\103\x48\105\115\x41\x20\75\x20\45\163\40\101\116\x44\x20\124\x41\102\114\x45\x5f\116\x41\x4d\105\40\75\40\x25\163\x20\101\116\104\x20\x43\117\114\125\x4d\116\x5f\116\101\x4d\105\40\x3d\40\x25\x73\40", DB_NAME, $wpdb->base_prefix . $lI, $cO));
        if (empty($HU)) {
            goto FJ;
        }
        return true;
        FJ:
        return false;
    }
    public function add_col_if_not_exists($lI = '', $cO = '', $Rn = true)
    {
        if (!('' === $lI || '' === $cO)) {
            goto LI;
        }
        return false;
        LI:
        if (!$this->check_col_exists($lI, $cO)) {
            goto jf;
        }
        return true;
        jf:
        global $wpdb;
        $nu = $Rn ? "\116\117\x54\40\116\125\x4c\114" : '';
        $wpdb->query("\x41\114\124\x45\122\x20\124\101\x42\114\x45\40" . $wpdb->base_prefix . self::USER_TRANSACTIONS_TABLE . "\x20\101\104\104\40" . $cO . "\x20\x6d\x65\x64\x69\x75\155\164\145\x78\x74\x20" . $nu);
    }
    private function get_all_transactions()
    {
        global $wpdb;
        $dj = $wpdb->get_results("\x53\x45\x4c\105\x43\124\x20\165\x73\145\162\x6e\x61\155\x65\x2c\40\x73\164\141\x74\x75\x73\40\54\x61\x70\x70\x6e\x61\x6d\145\40\54\x63\162\x65\x61\x74\145\x64\x5f\x74\x69\x6d\x65\163\x74\x61\x6d\x70\54\x20\145\155\141\151\x6c\x2c\40\143\x6c\151\145\x6e\164\x69\x70\x2c\40\x6e\141\x76\151\x67\141\x74\x69\x6f\156\x75\162\154\40\106\122\117\x4d\x20" . $wpdb->base_prefix . self::USER_TRANSACTIONS_TABLE);
        return $dj;
    }
    public function get_entries($uo = true)
    {
        $Vk = $this->get_all_transactions();
        if ($Vk) {
            goto Xp;
        }
        return [];
        Xp:
        if (!(true === $uo)) {
            goto F_;
        }
        return $Vk;
        F_:
        return [];
    }
    public function add_transact($uo = array(), $QS = false)
    {
        if (!$QS) {
            goto uX;
        }
        $this->add_to_wpdb();
        return true;
        uX:
        $QN = $this->add_to_wpdb($WZ = isset($uo["\x75\x73\x65\x72\156\141\155\x65"]) ? $uo["\165\163\145\x72\156\141\155\145"] : "\x2d", $uw = isset($uo["\163\164\141\164\x75\x73"]) ? $uo["\163\x74\141\x74\165\x73"] : false, $g0 = isset($uo["\143\157\x64\145"]) ? $uo["\x63\157\144\145"] : "\55", $d9 = isset($uo["\141\x70\x70\154\151\x63\141\164\x69\157\156"]) ? $uo["\x61\x70\160\x6c\151\x63\x61\164\x69\157\156"] : "\55", $Mv = isset($uo["\x65\x6d\x61\x69\154"]) ? $uo["\x65\x6d\141\x69\154"] : "\x2d", $vj = isset($uo["\143\154\x69\145\x6e\164\137\x69\160"]) ? $uo["\x63\154\151\x65\x6e\164\x5f\x69\x70"] : "\x2d", $AU = isset($uo["\156\141\x76\x69\x67\x61\x74\151\x6f\156\165\x72\154"]) ? $uo["\x6e\141\x76\151\147\x61\x74\151\157\x6e\165\162\154"] : "\x2d");
        MO_Oauth_Debug::mo_oauth_log("\125\163\145\162\x20\x61\x6e\x61\154\x79\x74\151\143\x73\x20\x61\144\x64\x65\144\40\x3d\76\40");
        MO_Oauth_Debug::mo_oauth_log($QN);
        return \boolval($QN);
    }
    private function add_to_wpdb($WZ = '', $uw = false, $g0 = '', $d9 = '', $Mv = '', $vj = '', $AU = '')
    {
        $xa = '';
        if (!('' === $g0 && false === $uw)) {
            goto x8;
        }
        $xa = "\116\57\x41";
        x8:
        $xa = $this->get_status_message($g0, $uw);
        $uo = ["\x75\163\145\162\x6e\x61\x6d\145" => isset($WZ) && '' !== $WZ ? $WZ : "\55", "\x73\164\x61\164\165\x73" => isset($xa) && '' !== $xa ? $xa : "\x2d", "\141\x70\160\156\141\x6d\x65" => isset($d9) && '' !== $d9 ? $d9 : "\55", "\145\x6d\141\151\x6c" => isset($Mv) && '' !== $Mv ? $Mv : "\x2d", "\143\154\x69\x65\x6e\x74\x69\160" => isset($vj) && '' !== $vj ? $vj : "\x2d", "\156\x61\166\151\147\x61\x74\x69\157\x6e\165\162\154" => isset($AU) && '' !== $AU ? $AU : "\55"];
        $uo = apply_filters("\x6d\157\x5f\160\x67\x5f\141\x64\x64\137\163\165\x62\x73\x69\x74\x65\x5f\143\157\x6c\137\x76\141\154\165\x65", $uo);
        global $wpdb;
        return $wpdb->insert($wpdb->base_prefix . self::USER_TRANSACTIONS_TABLE, $uo);
    }
    private function get_status_message($g0 = '', $uw = '')
    {
        if (!(true === $uw)) {
            goto m3;
        }
        return "\123\x55\x43\x43\x45\123\123";
        m3:
        switch ($g0) {
            case "\x45\x4d\x41\x49\x4c":
                return "\106\101\x49\114\x45\x44\x2e\40\111\x6e\166\141\x6c\151\x64\40\x45\x6d\x61\151\x6c\40\122\x65\x63\145\151\166\145\x64\56";
            case "\x55\x4e\x41\115\x45":
                return "\106\x41\x49\114\x45\x44\56\40\111\x6e\x76\141\154\x69\x64\40\x55\163\145\x72\x6e\141\x6d\x65\x20\x52\145\143\x65\151\x65\x76\145\x64\x2e";
            case "\x52\x45\107\x49\x53\x54\x52\101\124\111\117\x4e\x5f\104\x49\x53\101\102\x4c\105\104":
                return "\x46\101\111\114\105\x44\x2e\x20\x52\145\x67\151\163\164\162\x61\164\x69\157\156\40\144\151\163\x61\x62\154\145\x64\56";
            default:
                return "\106\101\x49\x4c\x45\x44";
        }
        B9:
        Du:
    }
    public function drop_table()
    {
        global $wpdb;
        if (!($wpdb->get_var("\x53\x48\117\127\x20\x54\101\x42\114\105\123\x20\114\111\x4b\105\40\x27" . $wpdb->prefix . self::USER_TRANSACTIONS_TABLE . "\47") === $wpdb->prefix . self::USER_TRANSACTIONS_TABLE)) {
            goto be;
        }
        $Um = $wpdb->get_results("\x44\x52\x4f\120\40\x54\101\x42\114\105\x20" . $wpdb->prefix . self::USER_TRANSACTIONS_TABLE);
        be:
    }
}
