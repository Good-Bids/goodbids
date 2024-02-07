<?php


namespace MoOauthClient\LicenseLibrary\Exceptions;

if (defined("\x41\102\x53\120\x41\124\x48")) {
    goto GOW;
}
exit;
GOW:
class Mo_License_Missing_License_Key_Exception extends \Exception
{
    const MESSAGE = "\x4d\x49\x53\123\111\116\x47\x5f\114\x49\103\x45\x4e\123\105\137\x4b\105\x59";
    public function __construct()
    {
        parent::__construct(self::MESSAGE);
    }
}
