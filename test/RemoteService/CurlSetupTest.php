<?php

namespace Nihl\RemoteService;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

/**
 * Setup tests for modelclass Curl
 */
class CurlSetupTest extends TestCase
{
    /**
     * Test create an object of class
     */
    public function testCreate()
    {
        $model = new Curl();
        $this->assertInstanceOf("Nihl\RemoteService\Curl", $model);
    }

    /**
     * Test create an object of class through DI-injection
     */
    public function testInjectUsingDI()
    {
        // Create the di container and load services
        $di = new DIFactoryConfig();
        $di->loadServices(ANAX_INSTALL_PATH . "/config/di");
        // $di->loadServices(ANAX_INSTALL_PATH . "/test/config/di");
        $di->get("cache")->setPath(ANAX_INSTALL_PATH . "/test/cache");

        $model = $di->get("curl");
        $this->assertInstanceOf("Nihl\RemoteService\Curl", $model);
    }
}
