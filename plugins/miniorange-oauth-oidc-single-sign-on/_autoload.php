<?php


if (defined("\x41\x42\x53\x50\x41\x54\x48")) {
    goto AbF;
}
exit;
AbF:
define("\x4d\117\103\x5f\104\x49\122", plugin_dir_path(__FILE__));
define("\115\x4f\103\137\x55\x52\x4c", plugin_dir_url(__FILE__));
define("\115\117\x5f\x55\x49\104", "\104\106\70\126\113\112\117\65\x46\104\x48\x5a\101\122\x42\122\x35\132\104\x53\x32\126\x35\x4a\66\x36\x55\x32\116\x44\122");
define("\126\x45\122\123\111\x4f\x4e", "\155\157\x5f\155\165\x6c\164\151\163\151\x74\x65\137\145\156\164\145\162\160\x72\x69\163\145\137\x76\145\162\x73\151\x6f\156");
mo_oauth_include_file(MOC_DIR . "\57\x63\154\141\163\163\x65\x73\x2f\x63\157\155\155\157\x6e");
mo_oauth_include_file(MOC_DIR . "\x2f\143\154\141\163\163\145\x73\57\106\x72\x65\x65");
mo_oauth_include_file(MOC_DIR . "\x2f\x63\154\141\x73\x73\145\x73\x2f\123\164\x61\156\x64\x61\x72\144");
mo_oauth_include_file(MOC_DIR . "\x2f\143\154\x61\163\163\x65\x73\57\120\162\145\155\151\165\x6d");
mo_oauth_include_file(MOC_DIR . "\x2f\143\x6c\141\163\163\145\163\57\105\x6e\164\145\162\160\x72\151\163\x65");
function mo_oauth_get_dir_contents($xk, &$Um = array())
{
    foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($xk, RecursiveDirectoryIterator::KEY_AS_PATHNAME), RecursiveIteratorIterator::CHILD_FIRST) as $BB => $eD) {
        if (!($eD->isFile() && $eD->isReadable())) {
            goto wDX;
        }
        $Um[$BB] = realpath($eD->getPathname());
        wDX:
        uGA:
    }
    gyf:
    return $Um;
}
function mo_oauth_get_sorted_files($xk)
{
    $Lr = mo_oauth_get_dir_contents($xk);
    $Fh = array();
    $w1 = array();
    foreach ($Lr as $BB => $iW) {
        if (!(strpos($iW, "\56\x70\150\160") !== false)) {
            goto fod;
        }
        if (strpos($iW, "\x49\156\164\145\162\x66\x61\143\145") !== false) {
            goto miU;
        }
        $w1[$BB] = $iW;
        goto DCc;
        miU:
        $Fh[$BB] = $iW;
        DCc:
        fod:
        CuX:
    }
    YfA:
    return array("\x69\156\164\145\162\x66\x61\x63\145\x73" => $Fh, "\143\x6c\141\163\163\x65\163" => $w1);
}
function mo_oauth_include_file($xk)
{
    if (is_dir($xk)) {
        goto SAF;
    }
    return;
    SAF:
    $xk = mo_oauth_sane_dir_path($xk);
    $F9 = realpath($xk);
    if (!(false !== $F9 && !is_dir($xk))) {
        goto fn0;
    }
    return;
    fn0:
    $tN = mo_oauth_get_sorted_files($xk);
    mo_oauth_require_all($tN["\151\156\x74\x65\x72\146\x61\143\145\x73"]);
    mo_oauth_require_all($tN["\x63\154\x61\x73\x73\145\x73"]);
}
function mo_oauth_require_all($Lr)
{
    foreach ($Lr as $BB => $iW) {
        require_once $iW;
        W_C:
    }
    cbU:
}
function mo_oauth_is_valid_file($D9)
{
    return '' !== $D9 && "\x2e" !== $D9 && "\x2e\56" !== $D9;
}
function mo_oauth_get_valid_html($uo = array())
{
    $QN = array("\163\x74\x72\157\156\x67" => array(), "\x65\x6d" => array(), "\x62" => array(), "\x69" => array(), "\141" => array("\x68\162\x65\146" => array(), "\x74\x61\x72\x67\145\164" => array()));
    if (empty($uo)) {
        goto HML;
    }
    return array_merge($uo, $QN);
    HML:
    return $QN;
}
function mo_oauth_get_version_number()
{
    $BC = get_file_data(MOC_DIR . "\57\x6d\x6f\137\x6f\x61\x75\x74\150\137\x73\x65\164\x74\151\156\x67\x73\56\x70\x68\x70", ["\x56\x65\x72\x73\151\x6f\x6e"], "\x70\154\x75\x67\x69\156");
    $st = isset($BC[0]) ? $BC[0] : '';
    return $st;
}
function mo_oauth_sane_dir_path($xk)
{
    return str_replace("\57", DIRECTORY_SEPARATOR, $xk);
}
if (!function_exists("\x6d\157\137\x6f\141\x75\x74\150\137\151\163\x5f\162\x65\x73\164")) {
    function mo_oauth_is_rest()
    {
        $NO = rest_get_url_prefix();
        if (!(defined("\122\105\x53\124\x5f\122\x45\x51\x55\105\x53\x54") && REST_REQUEST || isset($_GET["\x72\145\163\164\137\162\x6f\x75\x74\x65"]) && strpos(trim(sanitize_text_field(wp_unslash($_GET["\162\x65\x73\164\x5f\162\157\x75\164\145"])), "\134\57"), $NO, 0) === 0)) {
            goto nnB;
        }
        return true;
        nnB:
        global $JT;
        if (!($JT === null)) {
            goto hpo;
        }
        $JT = new WP_Rewrite();
        hpo:
        $yO = wp_parse_url(trailingslashit(rest_url()));
        $qr = wp_parse_url(add_query_arg(array()));
        return strpos($qr["\160\141\x74\150"], $yO["\160\x61\x74\x68"], 0) === 0;
    }
}
