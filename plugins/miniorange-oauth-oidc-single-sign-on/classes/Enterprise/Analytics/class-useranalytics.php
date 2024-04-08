<?php


namespace MoOauthClient\Enterprise;

use MoOauthClient\Enterprise\UserAnalyticsDBOps;
class UserAnalytics
{
    private $db_ops;
    public function __construct()
    {
        $this->db_ops = new UserAnalyticsDBOps();
    }
    public function render_ui()
    {
        echo "\11\x9\x3c\x64\x69\166\x20\143\154\141\163\163\x3d\x22\155\x6f\x5f\x74\x61\142\154\145\x5f\x6c\x61\171\157\165\164\42\76\15\12\x9\11\x9\74\144\x69\166\x20\143\x6c\x61\163\163\75\x22\155\x6f\137\167\160\156\x73\137\163\x6d\x61\x6c\154\137\x6c\141\171\157\x75\x74\42\x3e\xd\xa\11\11\11\11\x3c\146\x6f\162\x6d\40\141\x63\164\x69\157\156\x3d\42\x22\40\151\144\x3d\42\155\x61\156\165\x61\x6c\142\x6c\157\143\153\x69\x70\x66\x6f\162\x6d\42\40\155\145\164\150\x6f\x64\x3d\x22\x70\157\163\x74\x22\x3e\xd\12\11\x9\x9\x9\11\74\x69\x6e\x70\165\x74\40\164\x79\x70\145\x3d\x22\150\151\144\x64\145\156\42\x20\156\x61\155\x65\x3d\x22\x6f\x70\164\x69\157\156\x22\x20\x76\x61\x6c\165\x65\75\x22\155\x6f\137\167\160\156\x73\137\155\x61\x6e\165\x61\154\x5f\x63\x6c\x65\141\x72\42\40\x2f\76\xd\12\x9\x9\x9\11\11";
        wp_nonce_field("\155\x6f\137\x77\x70\x6e\163\137\x6d\141\156\x75\141\x6c\137\143\154\145\x61\x72", "\155\x6f\137\167\x70\156\163\137\155\141\156\x75\x61\154\x5f\x63\x6c\x65\x61\162\x5f\156\x6f\x6e\143\x65");
        echo "\11\x9\11\11\11\74\x74\x61\x62\154\145\x3e\xd\12\x9\x9\x9\11\x9\11\x3c\x74\162\x3e\xd\12\x9\11\11\11\11\x9\x9\x3c\x74\x64\x20\x73\164\x79\x6c\x65\75\42\x77\151\x64\x74\150\72\x20\x31\60\x30\x25\x22\76\x3c\x68\x32\x3e\x55\x73\145\162\40\124\162\141\156\163\141\x63\164\x69\x6f\156\x73\x20\122\145\160\157\x72\x74\74\57\150\62\x3e\x3c\x2f\164\144\76\xd\xa\x9\11\11\x9\x9\11\11\74\164\144\76\15\xa\11\x9\x9\11\x9\11\11\x9\74\x69\156\x70\165\164\x20\164\x79\160\145\x3d\x22\142\165\x74\x74\x6f\x6e\x22\40\143\154\x61\163\163\x3d\42\x62\165\164\164\157\x6e\40\142\x75\x74\164\157\156\x2d\x70\162\x69\155\x61\x72\x79\x20\x62\x75\x74\164\157\156\x2d\154\141\162\147\x65\x22\40\166\141\154\x75\x65\x3d\42\x52\x65\146\x72\145\x73\x68\42\40\157\x6e\103\154\x69\143\x6b\75\x22\x77\151\156\x64\157\167\56\x6c\157\143\x61\x74\151\x6f\x6e\56\162\x65\154\157\141\144\x28\x29\x22\76\xd\xa\11\x9\11\11\11\x9\11\x3c\57\x74\x64\x3e\15\12\x9\x9\x9\11\11\11\11\74\x74\144\x3e\xd\12\x9\x9\11\11\11\x9\11\11\74\151\x6e\160\165\x74\40\x74\x79\x70\145\x3d\42\x73\x75\x62\155\x69\x74\x22\40\143\x6c\x61\163\x73\x3d\x22\142\165\164\x74\157\x6e\40\142\165\164\164\157\156\55\160\162\x69\x6d\141\x72\x79\x20\142\x75\164\164\x6f\156\x2d\154\x61\x72\x67\x65\42\40\x76\141\154\165\145\75\42\x43\x6c\145\x61\162\40\122\145\160\157\162\164\x73\42\x3e\xd\12\x9\x9\11\x9\11\11\11\x3c\57\x74\x64\76\xd\12\x9\11\11\x9\11\x9\74\x2f\164\162\x3e\15\xa\x9\x9\11\11\x9\x3c\57\x74\x61\142\154\x65\76\xd\xa\11\x9\11\11\74\57\x66\x6f\x72\x6d\76\xd\12\11\x9\x9\x9\x3c\x74\141\142\x6c\145\x20\x69\x64\x3d\x22\x72\x65\x70\157\162\164\x73\x5f\x74\141\142\x6c\x65\42\x20\x63\154\x61\x73\x73\75\x22\144\151\163\160\x6c\x61\171\x20\x6d\157\137\x6f\141\165\x74\x68\x5f\x63\154\151\145\x6e\164\137\165\163\145\x72\x5f\141\x6e\141\x6c\171\164\151\x63\163\42\x20\x63\x65\x6c\154\x73\x70\141\143\x69\156\147\x3d\42\60\x22\x20\167\151\144\164\x68\75\x22\x31\x30\x30\45\x22\x20\x62\x6f\162\144\x65\162\75\x22\x31\x70\170\42\76\xd\xa\x9\11\11\11\11\x3c\x74\150\145\141\x64\76\xd\12\x9\x9\x9\11\11\x9\74\164\162\76\15\xa\x9\x9\11\11\x9\x9\x9\x3c\164\144\76\74\163\x74\x72\157\156\x67\76\x55\x73\x65\162\156\x61\x6d\x65\x3c\57\x73\164\x72\157\156\x67\76\x3c\57\164\144\x3e\xd\12\11\x9\x9\x9\x9\x9\11\74\x74\x64\76\x3c\x73\x74\162\x6f\x6e\x67\x3e\123\x74\x61\x74\x75\163\x3c\x2f\163\x74\x72\x6f\x6e\147\76\74\x2f\x74\x64\x3e\xd\xa\x9\x9\x9\x9\x9\x9\11\74\x74\x64\76\74\x73\x74\162\157\156\147\x3e\101\160\160\x6c\151\x63\x61\164\x69\x6f\x6e\x3c\x2f\x73\x74\x72\x6f\156\x67\76\x3c\57\164\144\76\xd\xa\11\x9\x9\x9\11\x9\x9\74\164\x64\76\x3c\x73\x74\x72\x6f\x6e\147\x3e\x43\162\145\141\164\145\x64\x20\104\x61\x74\145\74\x2f\x73\164\162\x6f\x6e\147\76\x3c\57\x74\x64\76\15\xa\11\x9\11\11\x9\11\x9\x3c\164\x64\x3e\x3c\163\164\162\x6f\156\147\x3e\x45\x6d\x61\151\154\74\x2f\x73\x74\x72\157\156\x67\x3e\x3c\x2f\164\144\76\xd\xa\x9\11\x9\x9\x9\11\11\74\x74\144\76\x3c\x73\164\162\x6f\x6e\x67\76\x43\x6c\151\145\x6e\164\x20\x49\120\74\57\163\164\162\157\x6e\x67\x3e\74\57\164\144\76\xd\xa\x9\11\11\11\11\x9\11\74\x74\x64\x3e\74\163\164\x72\157\x6e\x67\x3e\116\141\x76\151\147\x61\164\x69\x6f\156\40\x55\x52\x4c\74\x2f\163\164\x72\157\156\x67\x3e\x3c\x2f\x74\x64\76\xd\xa\11\11\x9\x9\x9\x9\74\x2f\164\162\76\xd\12\11\x9\11\11\11\74\x2f\x74\x68\145\x61\144\x3e\xd\xa\x9\11\11\x9\11\x3c\x74\x62\x6f\144\171\76\xd\xa\x9\11\x9\x9\x9\11";
        $this->populate_analytics_table();
        echo "\11\x9\x9\x9\11\74\x2f\x74\x62\x6f\144\171\x3e\15\12\11\x9\11\x9\74\57\x74\x61\142\x6c\145\76\15\12\11\11\x9\x3c\57\x64\x69\166\76\15\xa\x9\x9\74\x2f\x64\x69\166\76\15\12\x9\11";
    }
    private function populate_analytics_table()
    {
        $Rt = $this->db_ops->get_entries(true);
        $T7 = ["\x73\160\x61\156" => ["\163\164\171\x6c\x65" => []]];
        foreach ($Rt as $W1) {
            $dx = $this->get_status_html($W1->status);
            echo "\11\11\x9\x3c\x74\162\x3e\15\xa\x9\x9\x9\11\74\x74\x64\76";
            echo wp_kses($W1->username, \mo_oauth_get_valid_html());
            echo "\74\x2f\x74\144\76\15\12\11\x9\11\11\x3c\164\144\x3e";
            echo wp_kses($dx, \mo_oauth_get_valid_html($T7));
            echo "\x3c\57\x74\x64\76\15\xa\11\x9\11\11\x3c\164\x64\76";
            echo wp_kses($W1->appname, \mo_oauth_get_valid_html());
            echo "\74\x2f\x74\144\76\xd\xa\x9\x9\11\11\74\164\x64\x3e";
            echo wp_kses(date("\x4d\40\152\x2c\x20\131\54\x20\147\x3a\x69\72\x73\x20\x61", strtotime($W1->created_timestamp)), \mo_oauth_get_valid_html());
            echo "\74\x2f\x74\x64\x3e\15\xa\x9\x9\11\11\x3c\164\x64\76";
            echo wp_kses($W1->email, \mo_oauth_get_valid_html());
            echo "\74\57\164\x64\x3e\15\12\11\x9\x9\x9\x3c\164\144\x3e";
            echo wp_kses($W1->clientip, \mo_oauth_get_valid_html());
            echo "\74\x2f\x74\144\x3e\15\12\x9\11\x9\11\x3c\164\x64\76";
            echo wp_kses($W1->navigationurl, \mo_oauth_get_valid_html());
            echo "\74\x2f\164\x64\76\15\xa\x9\x9\x9\x3c\57\x74\162\x3e\xd\xa\x9\11\11";
            Q_:
        }
        Cq:
    }
    private function get_status_html($FO = '')
    {
        if (!('' === $FO)) {
            goto Ag;
        }
        return '';
        Ag:
        if (strpos(\strtolower($FO), \strtolower("\106\x41\x49\114\105\104")) !== false) {
            goto sD;
        }
        return "\74\163\x70\141\156\x20\x73\x74\171\x6c\145\75\42\x63\157\154\157\162\x3a\147\162\x65\x65\156\42\76" . $FO . "\x3c\x2f\x73\160\141\x6e\76";
        goto Kk;
        sD:
        return "\x3c\x73\x70\141\156\40\163\x74\x79\x6c\145\x3d\42\x63\x6f\x6c\157\162\72\162\145\x64\x22\x3e" . $FO . "\x3c\x2f\x73\160\x61\156\76";
        Kk:
    }
}
