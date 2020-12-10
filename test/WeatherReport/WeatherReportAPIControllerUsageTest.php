<?php

namespace Nihl\WeatherReport;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

/**
 * Test the Weather report controller
 */
class WeatherReportAPIControllerUsageTest extends TestCase
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
     * Test the route "index".
     */
    public function testIndexAction()
    {
        $res = $this->controller->indexAction();
        $body = $res->getBody();
        $this->assertStringContainsString("Weather report Rest API", $body);
    }

    /**
     * Test sending post to ipAction to get weather forecast data
     */
    public function testIpActionPostForecast()
    {
        $this->di->get("request")->setPost("ip", "172.15.255.255");
        $this->di->get("request")->setPost("timeperiod", "forecast");
        $res = $this->controller->ipActionPost();

        $this->assertIsArray($res[0]);
        $this->assertIsArray($res[0]["weather"]["forecast"]);

        $exp = "Fri 4 Dec";
        $this->assertEquals($exp, $res[0]["weather"]["dt"]);

        $exp = "Thu 3 Dec";
        $this->assertEquals($exp, $res[0]["weather"]["forecast"][0]["dt"]);
    }

    /**
     * Test sending post to ipAction to get weather history data
     */
    public function testIpActionPostHistory()
    {
        $this->di->get("request")->setPost("ip", "172.15.255.255");
        $this->di->get("request")->setPost("timeperiod", "history");
        $res = $this->controller->ipActionPost();

        $this->assertIsArray($res[0]);
        $this->assertIsArray($res[0]["weather"]["history"]);

        $exp = "Fri 4 Dec";
        $this->assertEquals($exp, $res[0]["weather"]["dt"]);

        $exp = "Tue 1 Dec";
        $this->assertEquals($exp, $res[0]["weather"]["history"][0]["dt"]);
    }

    /**
     * Test sending post to ipAction to get weather forecast data
     */
    public function testGeoActionPostForecast()
    {
        $this->di->get("request")->setPost("lat", "33.690269470215");
        $this->di->get("request")->setPost("lon", "-117.78993988037");
        $this->di->get("request")->setPost("timeperiod", "forecast");
        $res = $this->controller->geoActionPost();


        $this->assertIsArray($res[0]);
        $this->assertIsArray($res[0]["weather"]["forecast"]);

        $exp = "Fri 4 Dec";
        $this->assertEquals($exp, $res[0]["weather"]["dt"]);

        $exp = "Thu 3 Dec";
        $this->assertEquals($exp, $res[0]["weather"]["forecast"][0]["dt"]);
    }

    /**
     * Test sending post to ipAction to get weather history data
     */
    public function testGeoActionPostHistory()
    {
        $this->di->get("request")->setPost("lat", "33.690269470215");
        $this->di->get("request")->setPost("lon", "-117.78993988037");
        $this->di->get("request")->setPost("timeperiod", "history");
        $res = $this->controller->geoActionPost();

        $this->assertIsArray($res[0]);
        $this->assertIsArray($res[0]["weather"]["history"]);

        $exp = "Fri 4 Dec";
        $this->assertEquals($exp, $res[0]["weather"]["dt"]);

        $exp = "Tue 1 Dec";
        $this->assertEquals($exp, $res[0]["weather"]["history"][0]["dt"]);
    }
}
