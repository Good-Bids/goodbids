<?php


namespace MoOauthClient\Enterprise;

class UserAnalyticsDBOps
{
    const USER_TRANSACTIONS_TABLE = "\x77\x70\156\163\x5f\x74\162\141\156\163\x61\x63\x74\x69\x6f\156\163";
    public function __construct()
    {
    }
    public function make_table_if_not_exists()
    {
        global $wpdb;
        $jT = "\103\x52\105\101\124\105\x20\x54\x41\102\114\x45\x20\x49\106\40\116\x4f\124\40\x45\x58\111\123\x54\123\x20" . $wpdb->base_prefix . self::USER_TRANSACTIONS_TABLE . "\40\50\15\xa\x9\x9\11\x60\x69\x64\140\40\142\151\147\x69\156\x74\x20\x4e\117\124\40\116\125\114\114\x20\x41\x55\x54\x4f\137\x49\x4e\103\x52\105\x4d\105\x4e\x54\x20\x50\122\x49\115\101\122\131\x20\113\x45\131\x2c\40\x20\x60\x75\163\145\162\x6e\x61\x6d\x65\x60\x20\155\145\x64\151\x75\155\x74\145\170\164\40\116\x4f\x54\40\x4e\x55\x4c\x4c\x20\x2c\140\x73\164\x61\164\165\163\140\x20\155\145\144\151\x75\x6d\164\145\170\x74\40\116\x4f\124\40\116\125\114\114\40\x2c\x60\141\x70\160\x6e\x61\155\x65\x60\40\x6d\x65\144\x69\165\x6d\164\145\170\x74\40\116\x4f\x54\x20\116\125\x4c\x4c\54\40\x60\x65\x6d\141\x69\154\140\40\x6d\145\144\151\165\x6d\164\x65\x78\x74\40\116\117\x54\x20\x4e\125\x4c\x4c\54\x20\x60\x63\x6c\x69\145\156\x74\151\x70\x60\40\x6d\x65\144\x69\x75\155\x74\145\170\x74\x20\116\x4f\x54\x20\x4e\125\114\114\54\40\x60\156\x61\166\x69\x67\141\164\x69\157\x6e\x75\162\154\x60\40\x6d\x65\x64\x69\x75\x6d\164\145\x78\x74\40\x4e\x4f\124\40\116\x55\x4c\x4c\x2c\x20\x60\143\162\x65\141\164\x65\x64\x5f\164\151\x6d\145\163\164\141\x6d\160\140\40\x54\111\x4d\105\123\124\x41\115\x50\x20\x44\105\x46\x41\x55\x4c\x54\x20\103\x55\122\122\105\116\x54\x5f\124\111\x4d\x45\123\124\101\115\120\x2c\40\x55\x4e\x49\x51\x55\x45\40\x4b\105\x59\40\151\144\x20\50\x69\x64\51\51\x3b";
        require_once ABSPATH . "\x77\x70\x2d\x61\144\155\151\156\57\x69\156\143\x6c\x75\144\145\x73\57\165\160\x67\x72\141\x64\145\x2e\160\150\x70";
        dbDelta($jT);
        $this->add_col_if_not_exists(self::USER_TRANSACTIONS_TABLE, "\145\x6d\x61\151\x6c");
        $this->add_col_if_not_exists(self::USER_TRANSACTIONS_TABLE, "\143\154\151\145\156\164\151\160");
        $this->add_col_if_not_exists(self::USER_TRANSACTIONS_TABLE, "\x6e\141\166\x69\147\x61\164\151\x6f\x6e\x75\162\154");
    }
    public function check_col_exists($mw = '', $Hb = '')
    {
        if (!('' === $mw || '' === $Hb)) {
            goto NI;
        }
        return false;
        NI:
        global $wpdb;
        $b6 = $wpdb->get_results($wpdb->prepare("\123\105\114\105\x43\124\40\x2a\40\106\122\x4f\x4d\x20\111\116\x46\x4f\122\x4d\x41\x54\x49\x4f\116\137\123\103\x48\105\x4d\x41\x2e\103\x4f\x4c\125\x4d\116\x53\x20\x57\110\x45\122\x45\40\124\x41\102\x4c\105\x5f\123\103\x48\105\x4d\x41\40\75\40\45\x73\40\x41\116\104\x20\124\101\102\114\x45\137\x4e\x41\x4d\x45\40\x3d\40\x25\163\x20\101\x4e\x44\x20\103\117\114\125\115\116\x5f\x4e\101\115\105\x20\75\x20\x25\163\40", DB_NAME, $wpdb->base_prefix . $mw, $Hb));
        if (empty($b6)) {
            goto uh;
        }
        return true;
        uh:
        return false;
    }
    public function add_col_if_not_exists($mw = '', $Hb = '', $Er = true)
    {
        if (!('' === $mw || '' === $Hb)) {
            goto Mm;
        }
        return false;
        Mm:
        if (!$this->check_col_exists($mw, $Hb)) {
            goto Mn;
        }
        return true;
        Mn:
        global $wpdb;
        $E2 = $Er ? "\116\x4f\x54\40\116\125\114\114" : '';
        $wpdb->query("\101\114\x54\x45\x52\x20\124\x41\x42\x4c\105\40" . $wpdb->base_prefix . self::USER_TRANSACTIONS_TABLE . "\x20\x41\x44\104\40" . $Hb . "\40\155\x65\x64\151\x75\155\x74\145\170\x74\x20" . $E2);
    }
    private function get_all_transactions()
    {
        global $wpdb;
        $xe = $wpdb->get_results("\x53\x45\x4c\x45\x43\124\x20\165\x73\145\162\156\141\155\x65\54\40\163\x74\x61\x74\165\163\x20\54\x61\160\x70\x6e\x61\155\x65\40\54\143\162\145\141\164\145\144\137\164\x69\x6d\x65\163\x74\x61\x6d\x70\x2c\40\x65\x6d\x61\x69\154\x2c\x20\x63\x6c\x69\x65\x6e\164\x69\160\54\40\156\x61\x76\x69\x67\141\164\151\157\156\x75\x72\x6c\x20\x46\122\x4f\x4d\40" . $wpdb->base_prefix . self::USER_TRANSACTIONS_TABLE);
        return $xe;
    }
    public function get_entries($z5 = true)
    {
        $pP = $this->get_all_transactions();
        if ($pP) {
            goto KU;
        }
        return [];
        KU:
        if (!(true === $z5)) {
            goto XD;
        }
        return $pP;
        XD:
        return [];
    }
    public function add_transact($z5 = array(), $XP = false)
    {
        if (!$XP) {
            goto e6;
        }
        $this->add_to_wpdb();
        return true;
        e6:
        $KJ = $this->add_to_wpdb($sP = isset($z5["\165\163\145\x72\x6e\x61\155\x65"]) ? $z5["\165\163\145\x72\156\141\155\145"] : "\x2d", $FO = isset($z5["\x73\x74\141\x74\165\163"]) ? $z5["\163\x74\x61\x74\165\163"] : false, $SJ = isset($z5["\x63\157\x64\145"]) ? $z5["\143\157\x64\x65"] : "\x2d", $BW = isset($z5["\141\x70\x70\154\x69\143\141\164\151\x6f\156"]) ? $z5["\141\160\160\154\151\143\141\x74\x69\x6f\x6e"] : "\x2d", $g3 = isset($z5["\x65\x6d\141\x69\x6c"]) ? $z5["\145\155\141\151\154"] : "\x2d", $kx = isset($z5["\143\154\151\145\156\164\x5f\x69\x70"]) ? $z5["\143\x6c\x69\x65\156\x74\137\x69\x70"] : "\55", $KT = isset($z5["\156\141\166\x69\x67\141\164\151\157\156\165\x72\154"]) ? $z5["\156\141\166\151\147\141\164\x69\157\156\x75\x72\x6c"] : "\x2d");
        return \boolval($KJ);
    }
    private function add_to_wpdb($sP = '', $FO = false, $SJ = '', $BW = '', $g3 = '', $kx = '', $KT = '')
    {
        $HJ = '';
        if (!('' === $SJ && false === $FO)) {
            goto A0;
        }
        $HJ = "\116\x2f\101";
        A0:
        $HJ = $this->get_status_message($SJ, $FO);
        $z5 = ["\x75\163\145\x72\x6e\141\155\x65" => isset($sP) && '' !== $sP ? $sP : "\55", "\163\x74\141\x74\x75\163" => isset($HJ) && '' !== $HJ ? $HJ : "\55", "\141\160\160\x6e\x61\155\145" => isset($BW) && '' !== $BW ? $BW : "\x2d", "\145\155\x61\151\x6c" => isset($g3) && '' !== $g3 ? $g3 : "\55", "\x63\x6c\x69\145\x6e\x74\151\160" => isset($kx) && '' !== $kx ? $kx : "\55", "\156\141\166\x69\x67\x61\164\151\157\x6e\x75\x72\x6c" => isset($KT) && '' !== $KT ? $KT : "\55"];
        $z5 = apply_filters("\x6d\x6f\137\160\147\137\x61\x64\144\x5f\x73\x75\x62\163\151\164\x65\x5f\x63\157\154\137\166\x61\154\x75\x65", $z5);
        global $wpdb;
        return $wpdb->insert($wpdb->base_prefix . self::USER_TRANSACTIONS_TABLE, $z5);
    }
    private function get_status_message($SJ = '', $FO = '')
    {
        if (!(true === $FO)) {
            goto QZ;
        }
        return "\x53\x55\103\103\105\x53\x53";
        QZ:
        switch ($SJ) {
            case "\x45\115\101\111\x4c":
                return "\x46\x41\111\x4c\x45\x44\56\x20\111\156\x76\x61\154\151\x64\x20\105\x6d\x61\x69\154\40\x52\x65\143\145\151\x76\x65\144\56";
            case "\125\116\x41\x4d\105":
                return "\106\101\111\x4c\x45\104\56\x20\x49\156\166\x61\x6c\151\144\40\125\x73\145\x72\156\141\x6d\x65\x20\x52\145\143\x65\151\x65\166\x65\x64\x2e";
            case "\x52\105\107\111\123\x54\122\x41\124\x49\x4f\116\x5f\x44\x49\123\101\x42\114\x45\104":
                return "\106\x41\x49\114\x45\x44\56\x20\122\x65\x67\x69\x73\x74\162\x61\164\x69\x6f\x6e\x20\x64\x69\x73\x61\x62\x6c\x65\144\x2e";
            default:
                return "\106\101\111\114\x45\x44";
        }
        nU:
        tB:
    }
    public function drop_table()
    {
        global $wpdb;
        if (!($wpdb->get_var("\x53\110\117\x57\40\124\x41\x42\x4c\x45\123\40\x4c\111\113\x45\x20\x27" . $wpdb->prefix . self::USER_TRANSACTIONS_TABLE . "\x27") === $wpdb->prefix . self::USER_TRANSACTIONS_TABLE)) {
            goto OD;
        }
        $hz = $wpdb->get_results("\x44\x52\117\x50\40\x54\x41\102\x4c\x45\x20" . $wpdb->prefix . self::USER_TRANSACTIONS_TABLE);
        OD:
    }
}
