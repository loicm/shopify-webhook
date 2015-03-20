# Receive a Webhook from Shopify

Shopify has [Webhooks features](https://docs.shopify.com/api/webhooks/using-webhooks) which have to verified by calculating a digital signature.

Here is a small class to receive the POST request, verify the signature integrity and getting the data.

I use this is a tiny project to try [phpspec](http://www.phpspec.net/).


## Install

```
composer require loicm/shopify-webhook
```

## Use

```
use Loicm\Shopify\WebHook;

$shopify_secret = 'Here is your secret key'

$webhook = new WebHook($shopify_secret);

if ($webhook->verify()) {
    // Do your stuff
    $data = $webhook->getData();
}
```
