<?php
namespace Loicm\Shopify;


class WebHook
{

    /**
     * @param string $shopify_secret your secret key from Shopify admin panel
     */
    public function __construct($secret_key)
    {
        if (empty(trim($secret_key))) {
            throw new \InvalidArgumentException('Shopify secret key missing!');
        }

        $this->secret_key = $secret_key;
    }

    /**
     * @param string $data body content of the POST request from Shopify
     * @param string $hmac_header X-Shopify-Hmac-Sha256 HTTP header
     */
    public function verify($data, $hmac_header)
    {
        $calculated_hmac = base64_encode(hash_hmac('sha256', $data, $this->secret_key, true));
        return ($hmac_header == $calculated_hmac);
    }
}
