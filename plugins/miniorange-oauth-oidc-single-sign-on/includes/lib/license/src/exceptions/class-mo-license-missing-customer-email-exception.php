<?php


namespace MoOauthClient\LicenseLibrary\Exceptions;

if (defined("\x41\x42\x53\x50\101\124\110")) {
    goto uOH;
}
exit;
uOH:
class Mo_License_Missing_Customer_Email_Exception extends \Exception
{
    const MESSAGE = "\115\111\x53\123\111\x4e\x47\x5f\x43\125\123\x54\117\115\x45\122\137\x45\115\x41\111\114";
    public function __construct()
    {
        parent::__construct(self::MESSAGE);
    }
}
