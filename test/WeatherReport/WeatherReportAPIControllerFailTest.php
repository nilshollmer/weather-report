<?php

namespace Nihl\WeatherReport;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

/**
 * Test the Weather report controller
 */
class WeatherReportAPIControllerFailTest extends TestCase
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
        $this->di->setShared("curl", "\Nihl\RemoteService\CurlWeatherReportMock");

        $this->di->get("geotag")->setUrl("http://testurl.com");
        $this->di->get("geotag")->setApikey("12345");

        $this->di->get("weatherreport")->setSiteUrl("http://testurl.com");
        $this->di->get("weatherreport")->setApikey("12345");
        // View helpers uses the global $di so it needs its value
        $di = $this->di;

        // Setup the controller
        $this->controller = new WeatherReportAPIController();
        $this->controller->setDI($this->di);
        $this->controller->initialize();
        $this->di->get('request')->setServer('REMOTE_ADDR', '172.15.255.255');
    }

    /**
     * Test the catchAll route
     */
    public function testInvalidRouteAction()
    {
        $res = $this->controller->catchAll();
        $body = $res->getBody();
        $this->assertStringContainsString("Route not found", $body);
    }

    /**
     * Test get weather forecast using IP.
     */
    public function testIpActionPostNoIP()
    {
        $res = $this->controller->ipActionPost();
        $this->assertIsArray($res);

        $exp = "400";
        $this->assertEquals($exp, $res[0]["code"]);

        $exp = "No IP was received.";
        $this->assertEquals($exp, $res[0]["message"]);
    }

    /**
     * Test get weather forecast using IP.
     */
    public function testIpActionPostInvalidIP()
    {
        $this->di->get("request")->setPost("ip", "wrong ip");
        $this->di->get("request")->setPost("timeperiod", "forecast");
        $res = $this->controller->ipActionPost();

        $this->assertIsArray($res);

        $exp = "400";
        $this->assertEquals($exp, $res[0]["code"]);

        $exp = "IP can't be connected to a geographic position";
        $this->assertEquals($exp, $res[0]["message"]);
    }

    /**
     * Test get weather forecast using coordinates but missing parameters
     */
    public function testGeoActionPostNoLatitudeOrLongitude()
    {
        $res = $this->controller->geoActionPost();

        $this->assertIsArray($res);

        $exp = "400";
        $this->assertEquals($exp, $res[0]["code"]);

        $exp = "Latitude or longitude missing in request";
        $this->assertEquals($exp, $res[0]["message"]);
    }


    /**
     * Test get weather forecast using coordinates but invalid latitude
     */
    public function testGeoActionPostInvalidLatitude()
    {
        $this->di->get("request")->setPost("lat", "not a valid latitude");
        $this->di->get("request")->setPost("lon", "12.345");
        $this->di->get("request")->setPost("timeperiod", "forecast");
        $res = $this->controller->geoActionPost();

        $this->assertIsArray($res);

        $exp = "400";
        $this->assertEquals($exp, $res[0]["weather"]["cod"]);

        $exp = "error";
        $this->assertEquals($exp, $res[0]["weather"]["message"]);
    }

    /**
     * Test get weather forecast using coordinates but invalid longitude
     */
    public function testGeoActionPostInvalidLongitude()
    {
        $this->di->get("request")->setPost("lat", "12.345");
        $this->di->get("request")->setPost("lon", "not a valid longitude");
        $this->di->get("request")->setPost("timeperiod", "forecast");
        $res = $this->controller->geoActionPost();

        $this->assertIsArray($res);

        $exp = "400";
        $this->assertEquals($exp, $res[0]["weather"]["cod"]);

        $exp = "error";
        $this->assertEquals($exp, $res[0]["weather"]["message"]);
    }
}
