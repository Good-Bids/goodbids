<?php


function emit_analytics_tab($pr)
{
    global $Uj;
    $Kn = $Uj->get_plugin_config()->get_current_config();
    if (!(!isset($Kn["\x61\143\164\151\x76\141\x74\x65\x5f\165\163\145\x72\137\141\156\141\154\171\x74\151\x63\163"]) || !boolval($Kn["\x61\x63\x74\151\x76\x61\x74\x65\x5f\165\x73\145\x72\137\x61\x6e\141\154\171\x74\151\x63\163"]))) {
        goto Os;
    }
    return;
    Os:
    echo "\x9\74\x61\40\x63\x6c\x61\163\163\x3d\x22\x6e\x61\166\55\164\x61\x62\x20";
    echo "\141\x6e\141\x6c\171\x74\151\143\x73" === $pr ? "\x6e\x61\166\55\164\x61\x62\x2d\x61\x63\164\151\x76\x65" : '';
    echo "\42\40\x68\162\x65\x66\75\42\141\x64\155\x69\156\56\x70\150\160\77\x70\141\x67\145\75\x6d\157\x5f\x6f\141\165\164\150\x5f\x73\145\164\x74\151\156\147\163\x26\x74\x61\x62\x3d\x61\156\141\x6c\x79\164\151\x63\163\x22\x3e\x55\x73\145\162\x20\101\x6e\x61\154\171\x74\151\x63\x73\x3c\57\x61\76\xd\12\11";
}
add_action("\x6d\x6f\137\x6f\x61\x75\164\150\x5f\x63\154\x69\x65\x6e\x74\x5f\x61\144\x64\137\156\x61\x76\137\164\141\142\x73\x5f\x75\151\x5f\151\x6e\164\x65\x72\156\141\154", "\145\155\151\164\137\141\156\141\154\171\x74\x69\143\163\x5f\x74\x61\142", 10, 1);
