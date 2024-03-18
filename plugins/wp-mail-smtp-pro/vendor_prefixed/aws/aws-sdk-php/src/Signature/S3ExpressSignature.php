<?php

namespace WPMailSMTP\Vendor\Aws\Signature;

use WPMailSMTP\Vendor\Aws\Credentials\Credentials;
use WPMailSMTP\Vendor\Aws\Credentials\CredentialsInterface;
use WPMailSMTP\Vendor\Psr\Http\Message\RequestInterface;
class S3ExpressSignature extends \WPMailSMTP\Vendor\Aws\Signature\S3SignatureV4
{
    public function signRequest(\WPMailSMTP\Vendor\Psr\Http\Message\RequestInterface $request, \WPMailSMTP\Vendor\Aws\Credentials\CredentialsInterface $credentials, $signingService = 's3express')
    {
        $request = $this->modifyTokenHeaders($request, $credentials);
        $credentials = $this->getSigningCredentials($credentials);
        return parent::signRequest($request, $credentials, $signingService);
    }
    public function presign(\WPMailSMTP\Vendor\Psr\Http\Message\RequestInterface $request, \WPMailSMTP\Vendor\Aws\Credentials\CredentialsInterface $credentials, $expires, array $options = [])
    {
        $request = $this->modifyTokenHeaders($request, $credentials);
        $credentials = $this->getSigningCredentials($credentials);
        return parent::presign($request, $credentials, $expires, $options);
    }
    private function modifyTokenHeaders(\WPMailSMTP\Vendor\Psr\Http\Message\RequestInterface $request, \WPMailSMTP\Vendor\Aws\Credentials\CredentialsInterface $credentials)
    {
        //The x-amz-security-token header is not supported by s3 express
        $request = $request->withoutHeader('X-Amz-Security-Token');
        return $request->withHeader('x-amz-s3session-token', $credentials->getSecurityToken());
    }
    private function getSigningCredentials(\WPMailSMTP\Vendor\Aws\Credentials\CredentialsInterface $credentials)
    {
        return new \WPMailSMTP\Vendor\Aws\Credentials\Credentials($credentials->getAccessKeyId(), $credentials->getSecretKey());
    }
}
