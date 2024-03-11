<?php

namespace WPMailSMTP\Vendor\Aws\Arn\S3;

use WPMailSMTP\Vendor\Aws\Arn\AccessPointArn as BaseAccessPointArn;
use WPMailSMTP\Vendor\Aws\Arn\AccessPointArnInterface;
use WPMailSMTP\Vendor\Aws\Arn\ArnInterface;
use WPMailSMTP\Vendor\Aws\Arn\Exception\InvalidArnException;
/**
 * @internal
 */
class AccessPointArn extends \WPMailSMTP\Vendor\Aws\Arn\AccessPointArn implements \WPMailSMTP\Vendor\Aws\Arn\AccessPointArnInterface
{
    /**
     * Validation specific to AccessPointArn
     *
     * @param array $data
     */
    public static function validate(array $data)
    {
        parent::validate($data);
        if ($data['service'] !== 's3') {
            throw new \WPMailSMTP\Vendor\Aws\Arn\Exception\InvalidArnException("The 3rd component of an S3 access" . " point ARN represents the region and must be 's3'.");
        }
    }
}
