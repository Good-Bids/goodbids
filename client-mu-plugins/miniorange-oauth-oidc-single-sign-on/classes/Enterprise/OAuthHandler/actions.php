<?php


function get_refresh_token($QK = '', $zl = '')
{
    global $Yh;
    $F8 = false;
    if ("\x64\145\146\141\x75\154\164" === $zl || $zl === '') {
        goto hH;
    }
    $F8 = $Yh->get_app_by_name($zl);
    goto Cr;
    hH:
    $F8 = $Yh->get_app_by_name();
    Cr:
    if ($F8) {
        goto XT;
    }
    return $F8;
    XT:
    $KY = $F8->get_app_config();
    if ($KY) {
        goto IP;
    }
    return $KY;
    IP:
    $uo = array("\x67\162\141\x6e\164\x5f\164\x79\160\x65" => "\x72\145\x66\162\x65\163\x68\x5f\164\x6f\153\x65\156", "\x63\x6c\151\x65\x6e\164\137\x69\x64" => isset($KY["\143\x6c\x69\145\156\164\137\151\144"]) ? $KY["\x63\x6c\x69\x65\x6e\164\137\x69\x64"] : '', "\143\154\151\x65\156\164\137\163\145\143\162\145\164" => isset($KY["\143\154\151\x65\x6e\x74\x5f\x73\x65\143\x72\x65\x74"]) ? $KY["\143\154\151\145\x6e\164\x5f\163\x65\143\162\145\164"] : '', "\162\145\x64\151\x72\145\x63\x74\x5f\165\162\151" => isset($KY["\162\x65\x64\151\x72\145\143\x74\x5f\165\x72\151"]) ? $KY["\162\x65\144\151\x72\x65\x63\164\137\x75\162\151"] : '', "\x72\x65\146\162\145\x73\x68\x5f\164\x6f\x6b\x65\x6e" => $QK, "\163\x63\x6f\160\145" => isset($KY["\163\143\157\x70\145"]) ? $KY["\x73\x63\157\160\x65"] : '');
    $_SESSION["\x70\162\157\x63\157\162\x65\x5f\162\x65\x66\162\x65\x73\x68\137\164\157\153\145\156"] = isset($QK) ? $QK : false;
    $pY = new \MoOauthClient\OauthHandler();
    $vy = isset($KY["\141\x63\x63\x65\163\x73\x74\157\153\x65\x6e\x75\162\x6c"]) ? $KY["\x61\143\x63\145\x73\x73\x74\157\153\x65\156\x75\162\x6c"] : '';
    return $pY->get_token($vy, $uo);
}
add_filter("\155\157\x5f\157\x61\165\164\x68\137\162\145\x66\x72\145\163\150\137\164\157\x6b\x65\156", "\x67\x65\164\137\x72\x65\146\x72\x65\x73\150\x5f\x74\x6f\153\x65\x6e", 10, 2);
