<?php

namespace spec\Loicm\Shopify;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class WebHookSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Loicm\Shopify\WebHook');
    }

    function it_validates_signature_from_shopify_secret()
    {
        $data = '{"foo":"bar"}';
        $hmac_header = '0Jn/M5TKfJl4vlnUXVd0y6GpwF6lfnXg7yqjHEJSK6U=';
        $shopify_secret = '8539b3b8b4aabd50084334c367b54357';

        $this->verify($data, $hmac_header, $shopify_secret)->shouldBeBool();
        $this->verify($data, $hmac_header, $shopify_secret)->shouldReturn(true);
    }
}
