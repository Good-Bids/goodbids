<?php


if (defined("\x41\x42\123\x50\101\x54\110")) {
    goto z6Q;
}
exit;
z6Q:
define("\x4d\x4f\x43\x5f\x44\111\122", plugin_dir_path(__FILE__));
define("\115\117\x43\x5f\x55\122\114", plugin_dir_url(__FILE__));
define("\x4d\x4f\x5f\125\111\x44", "\x44\x46\x38\x56\x4b\112\x4f\x35\106\x44\110\132\101\x52\102\122\x35\x5a\x44\x53\x32\x56\x35\x4a\x36\x36\125\62\x4e\x44\122");
define("\x56\x45\122\123\111\117\x4e", "\155\x6f\137\x65\x6e\164\x65\x72\160\162\x69\x73\x65\x5f\166\x65\x72\163\x69\157\x6e");
mo_oauth_include_file(MOC_DIR . "\57\x63\154\141\x73\163\145\x73\x2f\x63\x6f\155\x6d\x6f\x6e");
mo_oauth_include_file(MOC_DIR . "\x2f\x63\x6c\x61\163\x73\145\163\57\106\162\x65\145");
mo_oauth_include_file(MOC_DIR . "\57\143\x6c\141\163\x73\145\x73\x2f\123\164\141\x6e\144\141\162\x64");
mo_oauth_include_file(MOC_DIR . "\x2f\x63\x6c\x61\163\x73\x65\163\57\x50\162\x65\155\151\165\x6d");
mo_oauth_include_file(MOC_DIR . "\x2f\143\154\141\x73\163\x65\x73\57\x45\x6e\x74\145\x72\x70\162\151\163\145");
function mo_oauth_get_dir_contents($O2, &$hz = array())
{
    foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($O2, RecursiveDirectoryIterator::KEY_AS_PATHNAME), RecursiveIteratorIterator::CHILD_FIRST) as $aS => $Ws) {
        if (!($Ws->isFile() && $Ws->isReadable())) {
            goto NFU;
        }
        $hz[$aS] = realpath($Ws->getPathname());
        NFU:
        gIV:
    }
    vMj:
    return $hz;
}
function mo_oauth_get_sorted_files($O2)
{
    $wo = mo_oauth_get_dir_contents($O2);
    $xr = array();
    $OC = array();
    foreach ($wo as $aS => $lx) {
        if (!(strpos($lx, "\x2e\160\150\x70") !== false)) {
            goto T5o;
        }
        if (strpos($lx, "\x49\156\164\x65\x72\x66\141\x63\x65") !== false) {
            goto iyv;
        }
        $OC[$aS] = $lx;
        goto k8g;
        iyv:
        $xr[$aS] = $lx;
        k8g:
        T5o:
        C00:
    }
    D3R:
    return array("\151\x6e\164\x65\x72\x66\x61\143\x65\163" => $xr, "\x63\x6c\x61\x73\163\145\163" => $OC);
}
function mo_oauth_include_file($O2)
{
    if (is_dir($O2)) {
        goto vCk;
    }
    return;
    vCk:
    $O2 = mo_oauth_sane_dir_path($O2);
    $pI = realpath($O2);
    if (!(false !== $pI && !is_dir($O2))) {
        goto yXz;
    }
    return;
    yXz:
    $FD = mo_oauth_get_sorted_files($O2);
    mo_oauth_require_all($FD["\151\x6e\164\145\162\146\x61\143\x65\163"]);
    mo_oauth_require_all($FD["\x63\154\x61\x73\x73\x65\x73"]);
}
function mo_oauth_require_all($wo)
{
    foreach ($wo as $aS => $lx) {
        require_once $lx;
        Zva:
    }
    i1q:
}
function mo_oauth_is_valid_file($aO)
{
    return '' !== $aO && "\x2e" !== $aO && "\56\56" !== $aO;
}
function mo_oauth_get_valid_html($z5 = array())
{
    $KJ = array("\x73\x74\x72\157\x6e\x67" => array(), "\x65\x6d" => array(), "\x62" => array(), "\x69" => array(), "\141" => array("\x68\x72\145\146" => array(), "\x74\x61\x72\x67\x65\164" => array()));
    if (empty($z5)) {
        goto JZ3;
    }
    return array_merge($z5, $KJ);
    JZ3:
    return $KJ;
}
function mo_oauth_get_version_number()
{
    $Wd = get_file_data(MOC_DIR . "\x2f\x6d\x6f\x5f\157\x61\x75\164\150\x5f\x73\x65\x74\164\151\156\147\x73\x2e\x70\x68\x70", ["\x56\x65\x72\x73\x69\x6f\156"], "\160\154\x75\147\151\156");
    $Xm = isset($Wd[0]) ? $Wd[0] : '';
    return $Xm;
}
function mo_oauth_sane_dir_path($O2)
{
    return str_replace("\x2f", DIRECTORY_SEPARATOR, $O2);
}
if (!function_exists("\155\x6f\137\x6f\141\x75\164\150\137\x69\x73\x5f\162\145\x73\x74")) {
    function mo_oauth_is_rest()
    {
        $Lh = rest_get_url_prefix();
        if (!(defined("\x52\105\123\124\137\122\x45\121\125\x45\x53\x54") && REST_REQUEST || isset($_GET["\162\145\163\x74\137\x72\x6f\x75\x74\145"]) && strpos(trim($_GET["\x72\x65\x73\164\137\162\x6f\165\x74\145"], "\x5c\57"), $Lh, 0) === 0)) {
            goto FQk;
        }
        return true;
        FQk:
        global $pm;
        if (!($pm === null)) {
            goto uzU;
        }
        $pm = new WP_Rewrite();
        uzU:
        $O_ = wp_parse_url(trailingslashit(rest_url()));
        $w2 = wp_parse_url(add_query_arg(array()));
        return strpos($w2["\x70\141\164\x68"], $O_["\160\x61\164\150"], 0) === 0;
    }
}
