<?php


namespace MoOauthClient\LicenseLibrary\Exceptions;

if (defined("\101\102\x53\120\x41\x54\x48")) {
    goto T7q;
}
exit;
T7q:
class Mo_License_Invalid_Expiry_Date_Exception extends \Exception
{
    const MESSAGE = "\115\111\x53\123\111\116\107\x5f\117\122\137\x49\116\x56\101\114\111\x44\x5f\105\130\120\111\122\131\137\104\101\x54\x45";
    public function __construct()
    {
        parent::__construct(self::MESSAGE);
    }
}
