<?php

namespace Nihl\IPValidator;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

/**
 * Test the SampleController.
 */
class IPValidatorControllerFailTest extends TestCase
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
        $this->controller = new IPValidatorController();
        $this->controller->setDI($this->di);
        $this->controller->initialize();
        $this->di->get('request')->setServer('REMOTE_ADDR', '172.15.255.255');
    }

    /**
     * Test the route "index".
     */
    public function testInvalidRouteAction()
    {
        $res = $this->controller->catchAll();
        $body = $res->getBody();
        $this->assertStringContainsString("Route not found", $body);
    }

    /**
     * Test index route with get parameter ip set to a valid ipv6 address
     */
    public function testIndexActionWithInvalidIP()
    {
        $this->di->get("request")->setGet("ip", "12001:0db8:85a3:0000:0000:8a2e:0370:7334");

        $res = $this->controller->indexAction();
        $body = $res->getBody();

        $exp = "Adressen är ogiltig!";
        $this->assertStringContainsString($exp, $body);
    }
}
