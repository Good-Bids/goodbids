<?php


namespace MoOauthClient\LicenseLibrary\Exceptions;

if (defined("\101\102\x53\120\x41\x54\x48")) {
    goto pHc;
}
exit;
pHc:
class Mo_License_Grace_Expired_Exception extends \Exception
{
    const MESSAGE = "\x4c\x49\x43\105\116\123\105\137\107\x52\101\x43\x45\137\x45\x58\120\111\122\105\104";
    public function __construct()
    {
        parent::__construct(self::MESSAGE);
    }
}
