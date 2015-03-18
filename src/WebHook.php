<?php
namespace Loicm\Shopify;


class WebHook
{
    /**
     * @param string $data
     * @param string $hmac_header
     * @param string $shopify_secret
     */
    public function verify($data, $hmac_header, $shopify_secret)
    {
        $calculated_hmac = base64_encode(hash_hmac('sha256', $data, $shopify_secret, true));
        return ($hmac_header == $calculated_hmac);
    }
}
