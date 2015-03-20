<?php
namespace Loicm\Shopify;

use Symfony\Component\HttpFoundation\Request;

class WebHook
{
    /**
     * @var string
     */
    protected $secret_key;

    /**
     * @var Symfony\Component\HttpFoundation\Request
     */
    protected $http_request = null;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var string
     */
    protected $signature;

    /**
     * @param string $shopify_secret your secret key from Shopify admin panel
     */
    public function __construct($secret_key)
    {
        if (empty(trim($secret_key))) {
            throw new \InvalidArgumentException('Shopify secret key missing!');
        }

        $this->secret_key = $secret_key;

        $this->http_request = Request::createFromGlobals();
    }

    /**
     * @param string $data body content of the POST request from Shopify
     * @param string $hmac_header X-Shopify-Hmac-Sha256 HTTP header
     * @return bool
     */
    public function verify()
    {
        $calculated_hmac = base64_encode(
            hash_hmac(
                'sha256',
                json_encode($this->getDataFromRequest()),
                $this->secret_key,
                true
            )
        );

        return ($calculated_hmac === $this->getSignatureFromRequest());
    }

    /**
     * @return array data from the webhook
     */
    public function getDataFromRequest()
    {
        $this->data = json_decode($this->http_request->getContent(), true);

        return $this->data;
    }

    /**
     * @return string signature from HTTP header
     */
    public function getSignatureFromRequest()
    {
        $this->signature = $this->http_request->headers->get('X-Shopify-Hmac-Sha256');

        return $this->signature;
    }

    /**
     * @param Symfony\Component\HttpFoundation\Request
     */
    public function setHttpRequest(Request $request)
    {
        $this->http_request = $request;
    }

    /**
     * @return Symfony\Component\HttpFoundation\Request
     */
    public function getHttpRequest()
    {
        return $this->http_request;
    }
}
