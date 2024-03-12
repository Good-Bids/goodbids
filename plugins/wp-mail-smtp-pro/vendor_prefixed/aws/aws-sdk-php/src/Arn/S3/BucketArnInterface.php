<?php

namespace WPMailSMTP\Vendor\Aws\Arn\S3;

use WPMailSMTP\Vendor\Aws\Arn\ArnInterface;
/**
 * @internal
 */
interface BucketArnInterface extends \WPMailSMTP\Vendor\Aws\Arn\ArnInterface
{
    public function getBucketName();
}
