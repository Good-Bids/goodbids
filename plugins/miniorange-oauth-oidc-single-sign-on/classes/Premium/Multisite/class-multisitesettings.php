<?php


namespace MoOauthClient\Premium;

class MultisiteSettings
{
    private $versi;
    public function __construct()
    {
        $this->versi = VERSION;
    }
    public function render_ui()
    {
        global $Uj;
        if (is_multisite()) {
            goto Na0;
        }
        return;
        Na0:
        $Ya = get_sites();
        $lI = $Uj->mo_oauth_client_get_option("\155\x6f\x5f\157\x61\x75\164\x68\x5f\143\63\x56\151\x63\62\154\x30\x5a\x58\x4e\172\132\x57\170\154\131\x33\122\154\x5a\101");
        $t3 = array();
        if (!isset($lI)) {
            goto Ix5;
        }
        $t3 = json_decode($Uj->mooauthdecrypt($lI), true);
        Ix5:
        $rj = $Uj->mo_oauth_client_get_option("\x6e\157\117\x66\x53\165\x62\123\151\164\x65\163");
        echo "\15\12\11\x9\x3c\x64\151\166\40\x63\154\x61\163\163\75\42\155\157\x5f\164\141\x62\154\145\x5f\154\141\x79\x6f\165\164\42\x3e\15\12\x9\x9\11\x3c\x64\151\x76\x20\x63\x6c\x61\x73\163\75\x22\155\157\x5f\x77\160\x6e\x73\137\x73\x6d\141\x6c\154\137\x6c\141\171\157\165\x74\x22\76\15\12\11\x9\x9\11\74\144\x69\166\x20\x73\164\x79\x6c\x65\75\42\142\141\x63\x6b\x67\162\157\165\x6e\x64\x2d\x63\157\154\157\162\x3a\40\x23\x65\60\x65\66\145\x65\x3b\x20\142\157\162\x64\x65\x72\55\x6c\145\146\164\x3a\40\64\x70\x78\x20\x73\x6f\154\151\144\x20\x23\x31\x31\x38\x36\145\67\x3b\40\x77\151\x64\164\x68\72\40\x39\65\45\73\x20\160\141\144\x64\x69\x6e\x67\x3a\40\65\160\170\73\x22\76\x3c\x73\x74\162\x6f\x6e\x67\x3e\116\x6f\164\x65\x3a\74\x2f\x73\x74\162\x6f\x6e\x67\76\x20\123\123\x4f\x20\143\141\x6e\40\x62\145\40\141\x63\x74\x69\x76\x61\164\x65\144\x20\157\156\154\171\x20\x66\157\x72\40\x6e\165\x6d\142\145\162\40\157\x66\40\x73\x75\142\163\x69\164\145\x73\40\167\151\164\x68\x20\164\x68\x65\40\163\145\x6c\x65\143\164\145\144\x20\160\154\x61\156\x2e\x20\x4e\x75\155\x62\145\x72\40\157\x66\x20\x73\165\142\163\x69\164\x65\x73\40\x66\157\x72\x20\x74\150\x69\x73\x20\163\151\164\x65\40\x69\x73\x20\x3c\163\164\162\157\x6e\x67\x3e";
        echo count($Ya);
        echo "\x3c\57\163\x74\162\x6f\x6e\x67\76\x2e\x20\116\x75\x6d\x62\x65\x72\40\157\x66\x20\163\165\142\163\151\x74\145\x73\x20\x61\154\x6c\x6f\x77\x65\144\40\x77\x69\164\x68\x20\164\x68\151\x73\40\x6d\165\154\x74\151\x73\x69\164\x65\x20\160\154\141\x6e\40\x69\x73\74\x73\164\162\x6f\156\x67\x3e\40";
        echo $rj;
        echo "\74\57\x73\164\162\157\156\147\76\x20\x3c\57\144\151\x76\76\74\142\x72\76\74\142\162\76\xd\xa\11\x9\11\x9\74\x66\157\162\x6d\x20\x61\x63\164\x69\x6f\156\75\x22\x22\x20\155\145\164\150\x6f\x64\75\x22\160\157\x73\164\42\x3e\xd\12\x9\11\x9\x9\x9\x3c\151\x6e\x70\x75\164\x20\164\171\x70\145\x3d\42\150\151\144\x64\x65\x6e\x22\40\x6e\x61\x6d\145\75\x22\157\160\x74\151\x6f\x6e\42\x20\x76\x61\154\x75\145\x3d\x22\x6d\157\137\x73\x61\x76\x65\x5f\163\165\x62\163\151\x74\x65\163\x5f\157\160\164\x69\157\156\x22\x20\x2f\x3e\15\xa\11\x9\x9\11\x9";
        wp_nonce_field("\155\x6f\x5f\163\141\x76\145\137\x74\150\x65\x5f\x73\165\142\x73\151\164\x65\163\x5f\x6f\160\x74\151\x6f\156", "\155\157\x5f\x73\141\x76\145\137\x74\x68\x65\137\163\165\142\x73\151\x74\x65\x73\x5f\157\x70\164\x69\157\x6e\137\x6e\x6f\x6e\x63\x65");
        echo "\x9\x9\11\x9\x9\x3c\164\141\142\x6c\145\x20\143\x6c\141\163\163\x3d\x22\155\x6f\137\163\165\x62\x73\x69\164\145\x73\137\163\x65\164\x74\x69\156\147\163\x5f\x74\x61\x62\x6c\x65\x22\76\15\12\x9\x9\11\11\11\x9\74\164\x72\x3e\x3c\164\150\76\123\151\164\x65\40\x4e\x61\155\145\x3c\57\x74\150\76\x3c\164\x68\76\123\151\164\145\40\125\x52\x4c\x3c\x2f\x74\x68\76\74\x74\150\76\105\x6e\x61\142\x6c\x65\40\123\x53\x4f\74\57\x74\150\x3e\x3c\x2f\164\x72\x3e\xd\xa\11\11\11\11\x9";
        foreach ($Ya as $Mr => $HF) {
            $hT = get_blog_details(array("\142\x6c\157\x67\x5f\x69\x64" => $HF->blog_id))->blogname;
            echo "\11\11\x9\11\x9\40\74\164\x72\76\74\164\x64\76";
            echo $hT;
            echo "\x3c\57\164\x64\76\x3c\x74\x64\x3e";
            echo $HF->domain;
            echo $HF->path;
            echo "\74\57\x74\144\76\xd\xa\11\11\11\x9\x9\40\x3c\164\x64\x3e\74\x69\156\x70\165\164\x20\x74\171\160\x65\75\x22\x63\x68\145\x63\153\142\x6f\170\x22\40";
            echo is_array($t3) && in_array($HF->blog_id, $t3) ? "\x63\150\145\x63\x6b\145\144" : '';
            echo "\x20\156\141\x6d\145\75\42\163\165\x62\x73\x69\164\x65\133\x5d\x22\x20\166\x61\x6c\x75\145\x3d\42";
            echo $HF->domain;
            echo $HF->path;
            echo "\42\x3e\x3c\57\164\144\76\74\x2f\164\x72\76\xd\12\11\x9\x9\x9\11";
            faN:
        }
        OTV:
        echo "\x9\x9\x9\x9\74\57\164\141\x62\154\x65\x3e\15\12\11\11\11\11\11\74\x62\162\x3e\x3c\x62\x72\76\x3c\x69\156\160\x75\x74\40\x63\154\141\163\163\x3d\x22\x62\x75\x74\164\x6f\x6e\x20\x62\x75\x74\x74\x6f\x6e\55\160\162\151\155\x61\162\x79\x20\142\x75\164\164\157\156\x2d\x6c\141\x72\147\x65\42\x20\x74\171\160\x65\75\42\163\x75\x62\155\151\164\x22\x20\x6e\141\x6d\x65\75\42\163\x75\x62\155\x69\x74\42\x20\x76\141\154\165\145\x3d\42\123\141\166\x65\x22\76\xd\12\11\11\x9\x9\74\57\x66\x6f\162\155\76\15\12\x9\11\x9\74\57\144\151\166\x3e\15\12\11\11\74\x2f\x64\x69\166\76\15\xa\11";
    }
    public function save_multisite_settings()
    {
        global $Uj;
        $JP = get_sites();
        $Nq = array();
        $HW = intval($Uj->mo_oauth_client_get_option("\x6e\x6f\x4f\146\x53\165\x62\123\151\164\x65\x73"));
        $RW = $Uj->mo_oauth_client_get_option("\x6d\157\137\157\141\165\164\x68\x5f\143\x33\x56\x69\143\62\x6c\x30\132\x58\x4e\172\132\127\x78\x6c\131\63\122\x6c\132\101");
        if (isset($_POST["\155\x6f\137\163\141\x76\145\x5f\x74\150\x65\x5f\163\165\142\x73\151\164\x65\163\x5f\x6f\x70\x74\x69\157\156\137\156\x6f\x6e\x63\145"]) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST["\155\157\x5f\163\x61\166\145\x5f\164\x68\x65\x5f\163\x75\x62\x73\x69\164\x65\x73\x5f\157\160\164\x69\157\x6e\137\156\x6f\156\143\145"])), "\x6d\157\137\163\x61\166\145\137\164\150\145\x5f\x73\165\x62\x73\151\x74\x65\163\x5f\x6f\x70\164\x69\157\156") && isset($_POST[\MoOAuthConstants::OPTION]) && "\x6d\x6f\137\163\x61\166\x65\x5f\x73\x75\142\163\x69\x74\145\163\x5f\x6f\160\x74\x69\157\156" === $_POST[\MoOAuthConstants::OPTION] && isset($_POST["\163\x75\x62\x73\151\x74\145"])) {
            goto cax;
        }
        if (!(!(bool) $RW || empty($RW) || $RW == "\x66\141\x6c\x73\145" && $Uj->is_multisite_plan() && $HW)) {
            goto iol;
        }
        $zY = 0;
        HyL:
        if (!($zY < count($JP))) {
            goto ICl;
        }
        if (!($zY >= $HW + 1)) {
            goto ChE;
        }
        goto ICl;
        ChE:
        array_push($Nq, $JP[$zY]->blog_id);
        J2T:
        $zY++;
        goto HyL;
        ICl:
        $zq = $Uj->mooauthencrypt(json_encode($Nq));
        $Uj->mo_oauth_client_update_option("\x6d\x6f\x5f\x6f\x61\x75\x74\x68\x5f\143\63\x56\151\143\x32\154\60\132\x58\x4e\x7a\x5a\127\x78\154\131\63\x52\154\x5a\101", $zq);
        iol:
        goto RhQ;
        cax:
        $VS = $_POST["\x73\165\142\163\151\x74\x65"];
        if (!($HW > 0 && is_array($VS) && count($VS) <= $HW + 1)) {
            goto ALs;
        }
        $zY = 0;
        gHq:
        if (!($zY < count($VS))) {
            goto hB2;
        }
        if (!($zY >= $HW + 1)) {
            goto hc0;
        }
        goto hB2;
        hc0:
        foreach ($JP as $zg) {
            if (!($VS[$zY] == $zg->domain . '' . $zg->path)) {
                goto u3r;
            }
            $blog_id = $zg->blog_id;
            goto uqn;
            u3r:
            vNv:
        }
        uqn:
        array_push($Nq, $blog_id);
        cuN:
        $zY++;
        goto gHq;
        hB2:
        $zq = $Uj->mooauthencrypt(json_encode($Nq));
        $Uj->mo_oauth_client_update_option("\155\x6f\x5f\157\x61\165\164\x68\137\x63\x33\126\151\143\62\x6c\60\x5a\x58\x4e\x7a\x5a\127\x78\154\x59\63\x52\154\132\x41", $zq);
        ALs:
        RhQ:
    }
}
