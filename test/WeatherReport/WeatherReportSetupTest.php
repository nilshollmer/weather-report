<?php

namespace Nihl\WeatherReport;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

/**
 * Test for modelclass WeatherReport
 */
class WeatherReportSetupTest extends TestCase
{
    /**
     * Test create an object of class
     */
    public function testCreate()
    {
        $model = new WeatherReport();
        $this->assertInstanceOf("Nihl\WeatherReport\WeatherReport", $model);
    }

    /**
     * Test create an object of class through DI-injection
     */
    public function testInjectUsingDI()
    {
        // Create the di container and load services
        $di = new DIFactoryConfig();
        $di->loadServices(ANAX_INSTALL_PATH . "/config/di");
        $di->get("cache")->setPath(ANAX_INSTALL_PATH . "/test/cache");

        $model = $di->get("weatherreport");
        $this->assertInstanceOf("Nihl\WeatherReport\WeatherReport", $model);
    }
}
