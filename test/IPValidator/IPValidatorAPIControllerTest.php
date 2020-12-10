<?php

namespace Nihl\IPValidator;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

/**
 * Test the SampleController.
 */
class IPValidatorAPIControllerTest extends TestCase
{

    // Create the di container.
    protected $di;
    protected $controller;


    /**
     * Prepare before each test.
     */
    protected function setUp()
    {
        global $di;

        // Setup di
        $this->di = new DIFactoryConfig();
        $this->di->loadServices(ANAX_INSTALL_PATH . "/config/di");

        // Use a different cache dir for unit test
        $this->di->get("cache")->setPath(ANAX_INSTALL_PATH . "/test/cache");


        // Set mock as service in the di replacing the original class
        $this->di->setShared("curl", "\Nihl\RemoteService\CurlGeotagMock");

        $this->di->get("geotag")->setUrl("http://testurl.com");
        $this->di->get("geotag")->setApikey("12345");

        // View helpers uses the global $di so it needs its value
        $di = $this->di;

        // Setup the controller
        $this->controller = new IPValidatorAPIController();
        $this->controller->setDI($this->di);
        $this->controller->initialize();
    }

    /**
     * Test the route "index".
     */
    public function testIndexActionGet()
    {
        $this->di->get('request')->setServer('REMOTE_ADDR', '172.15.255.255');
        $res = $this->controller->indexActionGet();
        $body = $res->getBody();
        $this->assertStringContainsString("IP-validator Rest API", $body);
    }

    /**
     * Test the route catchAll
     */
    public function testCatchAllAction()
    {
        $res = $this->controller->catchAll();
        $this->assertIsArray($res);

        $this->assertContains(400, $res);
    }



    /**
     * Test index route with get parameter ip set to a valid ipv4 address
     */
    public function testIndexActionPostWithValidIP4()
    {
        $this->di->get("request")->setPost("ip", "172.15.255.255");
        $res = $this->controller->indexActionPost();
        $this->assertIsArray($res[0]["validation"]);

        $exp = true;
        $this->assertEquals($exp, $res[0]["validation"]["match"]);

        $exp = "Adressen är en giltig ip4-adress!";
        $this->assertEquals($exp, $res[0]["validation"]["message"]);

        $exp = "ipv4";
        $this->assertEquals($exp, $res[0]["geotag"]["type"]);

        $exp = "https://www.openstreetmap.org/?mlat=33.690269470215&amp;mlon=-117.78993988037#map=6/33.690269470215/-117.78993988037";
        $this->assertEquals($exp, $res[0]["map"]);

        $exp = "172-15-255-255.lightspeed.irvnca.sbcglobal.net";
        $this->assertEquals($exp, $res[0]["validation"]["domain"]);
    }

    /**
     * Test index route with get parameter ip set to a valid ipv6 address
     */
    public function testIndexActionPostWithValidIP6()
    {
        $this->di->get("request")->setPost("ip", "2001:0db8:85a3:0000:0000:8a2e:0370:7334");
        $res = $this->controller->indexActionPost();

        $this->assertIsArray($res[0]["validation"]);

        $exp = true;
        $this->assertEquals($exp, $res[0]["validation"]["match"]);

        $exp = "Adressen är en giltig ip6-adress!";
        $this->assertEquals($exp, $res[0]["validation"]["message"]);

        $exp = "2001:0db8:85a3:0000:0000:8a2e:0370:7334";
        $this->assertEquals("2001:0db8:85a3:0000:0000:8a2e:0370:7334", $res[0]["validation"]["domain"]);
    }

    /**
     * Test index route with get parameter ip set to a valid ipv6 address
     */
    public function testIndexActionPostWithInvalidIP()
    {
        $this->di->get("request")->setPost("ip", "wrong ip");
        $res = $this->controller->indexActionPost();
        $this->assertIsArray($res[0]["validation"]);

        $exp = false;
        $this->assertEquals($exp, $res[0]["validation"]["match"]);

        $exp = "Adressen är ogiltig!";
        $this->assertEquals($exp, $res[0]["validation"]["message"]);

        $this->assertEquals(null, $res[0]["validation"]["domain"]);
    }
}
