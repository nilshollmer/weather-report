<?php

namespace Nihl\RemoteService;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

/**
 * Test for modelclass Geotag
 */
class GeotagSetupTest extends TestCase
{
    /**
     * Test create an object of class
     */
    public function testCreate()
    {
        $geotag = new Geotag();
        $this->assertInstanceOf("Nihl\RemoteService\Geotag", $geotag);
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

        $model = $di->get("geotag");
        $this->assertInstanceOf("Nihl\RemoteService\Geotag", $model);
    }
}
