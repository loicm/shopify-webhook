<?php

namespace spec\Loicm\Shopify;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class WebHookSpec extends ObjectBehavior
{
    const SHOPIFY_SECRET_KEY = '8539b3b8b4aabd50084334c367b54357';

    const DATA = '{"foo":"bar"}';
    const HMAC_HEADER = '0Jn/M5TKfJl4vlnUXVd0y6GpwF6lfnXg7yqjHEJSK6U=';
    const HMAC_HEADER_WRONG = 'Thisisawrongsecret-key!';


    function let()
    {
        $this->beConstructedWith(self::SHOPIFY_SECRET_KEY);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Loicm\Shopify\WebHook');
    }

    function its_contructor_need_secret_key()
    {
        $this->shouldThrow('\InvalidArgumentException')->during('__construct', ['']);
    }

    function it_validates_signature_from_shopify_secret()
    {
        $this->verify(self::DATA, self::HMAC_HEADER)->shouldBeBool();
        $this->verify(self::DATA, self::HMAC_HEADER)->shouldReturn(true);
        $this->verify(self::DATA, self::HMAC_HEADER_WRONG)->shouldReturn(false);
    }
}
