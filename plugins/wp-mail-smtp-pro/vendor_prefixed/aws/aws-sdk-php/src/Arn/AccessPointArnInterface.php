<?php

namespace WPMailSMTP\Vendor\Aws\Arn;

/**
 * @internal
 */
interface AccessPointArnInterface extends \WPMailSMTP\Vendor\Aws\Arn\ArnInterface
{
    public function getAccesspointName();
}
