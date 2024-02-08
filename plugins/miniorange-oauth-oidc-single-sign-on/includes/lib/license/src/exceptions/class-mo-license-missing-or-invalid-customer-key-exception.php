<?php


namespace MoOauthClient\LicenseLibrary\Exceptions;

if (defined("\x41\102\123\x50\x41\x54\110")) {
    goto E6H;
}
exit;
E6H:
class Mo_License_Missing_Or_Invalid_Customer_Key_Exception extends \Exception
{
    const MESSAGE = "\115\x49\x53\123\x49\x4e\107\137\x4f\x52\137\111\x4e\126\x41\114\111\x44\x5f\103\125\123\124\117\115\105\x52\137\113\105\131";
    public function __construct()
    {
        parent::__construct(self::MESSAGE);
    }
}
