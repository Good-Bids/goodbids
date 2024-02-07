<?php


if (defined("\101\x42\x53\120\101\x54\x48")) {
    goto hQ8;
}
exit;
hQ8:
spl_autoload_register("\x6d\157\x5f\157\x61\165\164\x68\x5f\x63\154\151\145\156\x74\137\x6c\x69\x63\145\156\x73\x65\x5f\143\154\141\x73\x73\145\x73\x5f\141\165\164\x6f\x6c\157\141\144\x65\162");
function mo_oauth_client_license_classes_autoloader($Ss)
{
    $Iu = "\115\x6f\117\141\165\164\x68\x43\x6c\151\x65\x6e\x74\x5c\114\151\x63\x65\156\163\x65\114\151\x62\x72\x61\162\171";
    if (!(strpos($Ss, $Iu) !== 0)) {
        goto Mj1;
    }
    return;
    Mj1:
    $KK = __DIR__ . DIRECTORY_SEPARATOR . "\163\162\x63";
    $CV = strtolower(str_replace("\x5c", DIRECTORY_SEPARATOR, substr($Ss, strlen($Iu))));
    $iN = strrchr($CV, DIRECTORY_SEPARATOR);
    $Tc = "\143\x6c\141\163\163\55" . str_replace("\x5f", "\55", str_replace(DIRECTORY_SEPARATOR, '', $iN)) . "\x2e\160\150\160";
    $cb = str_replace($iN, DIRECTORY_SEPARATOR . $Tc, $CV);
    $Sz = $KK . $cb;
    if (!file_exists($Sz)) {
        goto pWR;
    }
    require_once $Sz;
    pWR:
}
