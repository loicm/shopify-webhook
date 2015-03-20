<?php
namespace spec\Loicm\Shopify;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use \Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpFoundation\HeaderBag;

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

    function its_contructor_needs_a_secret_key()
    {
        $this->shouldThrow('\InvalidArgumentException')->during('__construct', ['']);
    }

    function it_has_an_http_request_property()
    {
        $this->getHttpRequest()->shouldBeAnInstanceOf('Symfony\Component\HttpFoundation\Request');
    }

    function it_gets_data_from_http_request_body(Request $request)
    {
        $request->getContent()->willReturn(self::DATA)->shouldbeCalled();
        $this->setHttpRequest($request);

        $this->getDataFromRequest()->shouldBeArray();
        $this->getDataFromRequest()->shouldReturn(['foo' => 'bar']);

    }

    function it_gets_signature_from_http_header(Request $request, HeaderBag $headers)
    {
        $headers->get('X-Shopify-Hmac-Sha256')->willReturn(self::HMAC_HEADER)->shouldbeCalled();
        $request->headers = $headers;

        $this->setHttpRequest($request);

        $this->getSignatureFromRequest()->shouldReturn(self::HMAC_HEADER);
    }

    function it_validates_signature(Request $request, HeaderBag $headers)
    {
        $headers->get('X-Shopify-Hmac-Sha256')->willReturn(self::HMAC_HEADER)->shouldbeCalled();
        $request->headers = $headers;
        $request->getContent()->willReturn(self::DATA)->shouldbeCalled();
        $this->setHttpRequest($request);

        $this->verify()->shouldBeBool();
        $this->verify()->shouldReturn(true);
    }

    function it_invalidates_signature(Request $request, HeaderBag $headers)
    {
        $headers->get('X-Shopify-Hmac-Sha256')->willReturn(self::HMAC_HEADER_WRONG)->shouldbeCalled();
        $request->headers = $headers;
        $request->getContent()->willReturn(self::DATA)->shouldbeCalled();
        $this->setHttpRequest($request);

        $this->verify()->shouldBeBool();
        $this->verify()->shouldReturn(false);
    }
}
