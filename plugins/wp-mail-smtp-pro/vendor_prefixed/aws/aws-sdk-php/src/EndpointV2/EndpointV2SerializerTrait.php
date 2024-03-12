<?php

namespace WPMailSMTP\Vendor\Aws\EndpointV2;

use WPMailSMTP\Vendor\Aws\Api\Serializer\RestSerializer;
use WPMailSMTP\Vendor\Aws\EndpointV2\Ruleset\RulesetEndpoint;
use WPMailSMTP\Vendor\GuzzleHttp\Psr7\Uri;
/**
 * Set of helper functions used to set endpoints and endpoint
 * properties derived from dynamic endpoint resolution.
 *
 * @internal
 */
trait EndpointV2SerializerTrait
{
    /**
     * Applies a resolved endpoint, headers and any custom HTTP schemes provided
     * in client configuration to options which are applied to the serialized request.
     *
     * @param $endpoint
     * @param $headers
     *
     * @return void
     */
    private function setEndpointV2RequestOptions(\WPMailSMTP\Vendor\Aws\EndpointV2\Ruleset\RulesetEndpoint $endpoint, array &$headers) : void
    {
        $this->applyHeaders($endpoint, $headers);
        $resolvedUrl = $endpoint->getUrl();
        $this->applyScheme($resolvedUrl);
        $this->endpoint = $this instanceof \WPMailSMTP\Vendor\Aws\Api\Serializer\RestSerializer ? new \WPMailSMTP\Vendor\GuzzleHttp\Psr7\Uri($resolvedUrl) : $resolvedUrl;
    }
    /**
     * Combines modeled headers and headers resolved from an endpoint object.
     *
     * @param $endpoint
     * @param $headers
     * @return void
     */
    private function applyHeaders(\WPMailSMTP\Vendor\Aws\EndpointV2\Ruleset\RulesetEndpoint $endpoint, array &$headers) : void
    {
        if (!\is_null($endpoint->getHeaders())) {
            $headers = \array_merge($headers, $endpoint->getHeaders());
        }
    }
    /**
     * Applies custom HTTP schemes provided in client configuration.
     *
     * @param $resolvedUrl
     * @return void
     */
    private function applyScheme(&$resolvedUrl) : void
    {
        $resolvedEndpointScheme = \parse_url($resolvedUrl, \PHP_URL_SCHEME);
        $scheme = $this->endpoint instanceof \WPMailSMTP\Vendor\GuzzleHttp\Psr7\Uri ? $this->endpoint->getScheme() : \parse_url($this->endpoint, \PHP_URL_SCHEME);
        if (!empty($scheme) && $scheme !== $resolvedEndpointScheme) {
            $resolvedUrl = \str_replace($resolvedEndpointScheme, $scheme, $resolvedUrl);
        }
    }
}
