<?php

namespace Nihl\WeatherReport;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

/**
 * Test the Weather report controller
 */
class WeatherReportControllerUsageTest extends TestCase
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
        $this->controller = new WeatherReportController();
        $this->controller->setDI($this->di);
        $this->controller->initialize();
        $this->di->get('request')->setServer('REMOTE_ADDR', '172.15.255.255');
    }

    /**
     * Test the route "index".
     */
    public function testIndexActionNoSearch()
    {
        $res = $this->controller->indexAction();
        $body = $res->getBody();
        $this->assertStringContainsString("Väder", $body);
    }

    /**
     * Test the route "index" with Get variable search set to "ip"
     */
    public function testIndexActionIPSearch()
    {
        $this->di->get("request")->setGet("search", "ip");

        $res = $this->controller->indexAction();
        $body = $res->getBody();
        $this->assertStringContainsString("Sök med IP-adress", $body);
    }

    /**
     * Test the route "index" with Get variable search set to "geo"
     */
    public function testIndexActionGeoSearch()
    {
        $this->di->get("request")->setGet("search", "geo");

        $res = $this->controller->indexAction();
        $body = $res->getBody();
        $this->assertStringContainsString("Sök med koordinater", $body);
    }

    /**
     * Test the route ip to get weather forecast data
     */
    public function testIpActionGetForecast()
    {
        $this->di->get("request")->setGet("ip", "172.15.255.255");
        $this->di->get("request")->setGet("timeperiod", "forecast");
        $res = $this->controller->ipActionGet();
        $body = $res->getBody();

        $this->assertStringContainsString("Vädret just nu i Orange", $body);
        $this->assertStringContainsString("Vädret inom 7 dagar", $body);
        $this->assertStringContainsString("Stad: Irvine 92604, California", $body);
    }
    /**
     * Test the route ip to get weather history data
     */
    public function testIpActionGetHistory()
    {
        $this->di->get("request")->setGet("ip", "172.15.255.255");
        $this->di->get("request")->setGet("timeperiod", "history");

        $res = $this->controller->ipActionGet();
        $body = $res->getBody();

        $this->assertStringContainsString("Vädret just nu i Orange", $body);
        $this->assertStringContainsString("Väderhistorik", $body);
        $this->assertStringContainsString("Stad: Irvine 92604, California", $body);
    }

    /**
     * Test the route geo to get weather forecast data
     */
    public function testGeoActionGetForecast()
    {
        $this->di->get("request")->setGet("lat", "33.690269470215");
        $this->di->get("request")->setGet("lon", "-117.78993988037");
        $this->di->get("request")->setGet("timeperiod", "forecast");

        $res = $this->controller->geoActionGet();
        $body = $res->getBody();

        $this->assertStringContainsString("Vädret just nu i Orange", $body);
        $this->assertStringContainsString("Vädret inom 7 dagar", $body);
    }

    /**
     * Test the route ip to get weather history data
     */
    public function testGeoActionGetHistory()
    {
        $this->di->get("request")->setGet("lat", "33.690269470215");
        $this->di->get("request")->setGet("lon", "-117.78993988037");
        $this->di->get("request")->setGet("timeperiod", "history");

        $res = $this->controller->geoActionGet();
        $body = $res->getBody();

        $this->assertStringContainsString("Vädret just nu i Orange", $body);
        $this->assertStringContainsString("Väderhistorik", $body);
    }
}
