<?php

namespace Nihl\WeatherReport;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

/**
 * Test the Weather report controller
 */
class WeatherReportControllerFailTest extends TestCase
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
     * Test the catchAll route
     */
    public function testInvalidRouteAction()
    {
        $res = $this->controller->catchAll();
        $body = $res->getBody();
        $this->assertStringContainsString("Route not found", $body);
    }


    /**
     * Test get weather forecast using an invalid ip value.
     */
    public function testIpActionGetInvalidIP()
    {
        $this->di->get("request")->setGet("ip", "wrong ip");
        $this->di->get("request")->setGet("timeperiod", "forecast");
        $res = $this->controller->ipActionGet();
        $body = $res->getBody();

        $this->assertStringContainsString("Inget väderresultat kunde hittas.", $body);
    }

    /**
     * Test get weather forecast using invalid lat and lon values
     */
    public function testGeoActionInvalidLat()
    {
        $this->di->get("request")->setGet("lat", "not a latitude");
        $this->di->get("request")->setGet("lon", "not a longitude");
        $this->di->get("request")->setGet("timeperiod", "forecast");

        $res = $this->controller->geoActionGet();
        $body = $res->getBody();

        $this->assertStringContainsString("Inget väderresultat kunde hittas.", $body);
    }
}
