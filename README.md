# Verify Shopify Webhooks signature

Shopify has a [Webhooks features](https://docs.shopify.com/api/webhooks/using-webhooks) and they can be verified by calculating a digital signature.

Here is a small class to do the job.


## Install

```
composer require loicm/shopify-webhook
```

## Use

```
use Loicm\Shopify\WebHook;

$hmac_header = $_SERVER['X-Shopify-Hmac-Sha256'];
$data = file_get_contents('php://input');
$shopify_secret = 'Here is your secret key'

$webhook = new WebHook($shopify_secret);

if ($webhook->verify($data, $hmac_header)) {
    // Do your stuff
}
```
