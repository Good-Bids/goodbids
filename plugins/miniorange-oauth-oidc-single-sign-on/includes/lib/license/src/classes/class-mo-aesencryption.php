<?php


namespace MoOauthClient\LicenseLibrary\Classes;

if (defined("\101\x42\x53\x50\101\x54\110")) {
    goto MY9;
}
exit;
MY9:
class Mo_AESEncryption
{
    public static function encrypt_data($uD, $cW)
    {
        $cW = openssl_digest($cW, "\163\150\x61\62\x35\66");
        $Wx = "\x61\145\x73\x2d\x31\62\x38\55\145\143\x62";
        $EH = openssl_encrypt($uD, $Wx, $cW, OPENSSL_RAW_DATA || OPENSSL_ZERO_PADDING);
        return base64_encode($EH);
    }
    public static function decrypt_data($uD, $cW)
    {
        $kT = base64_decode($uD);
        $cW = openssl_digest($cW, "\163\150\x61\x32\x35\x36");
        $Wx = "\101\x45\123\55\x31\62\x38\x2d\x45\103\x42";
        $YJ = openssl_cipher_iv_length($Wx);
        $fa = substr($kT, 0, $YJ);
        $uD = substr($kT, $YJ);
        $Qw = openssl_decrypt($uD, $Wx, $cW, OPENSSL_RAW_DATA || OPENSSL_ZERO_PADDING, $fa);
        return $Qw;
    }
}
