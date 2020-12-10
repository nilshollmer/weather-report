<?php

namespace Nihl\IPValidator;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

/**
 * Test for modelclass Geotag
 */
class IPValidatorUsageTest extends TestCase
{
    // Create the di container and model.
    protected $di;
    protected $model;

    public function setUp()
    {
        // Create the di container and load services
        $this->di = new DIFactoryConfig();
        $this->di->loadServices(ANAX_INSTALL_PATH . "/config/di");
        $this->di->get("cache")->setPath(ANAX_INSTALL_PATH . "/test/cache");

        $this->model = $this->di->get("ipvalidator");
    }

    /**
     * Set variables HTTP_CLIENT_IP, HTTP_X_FORWARDED_FOR and REMOTE_ADDR in server
     */
    public function testGetUserIPWithHttpClientIP()
    {
        $request = $this->di->get('request');
        $request->setServer('HTTP_CLIENT_IP', 'http_client_ip');
        $request->setServer('HTTP_X_FORWARDED_FOR', 'http_x_forwarded_for');
        $request->setServer('REMOTE_ADDR', 'remote_addr');

        $userIP = $this->model->getUserIP($request->getServer());

        $exp = "http_client_ip";
        $this->assertEquals($exp, $userIP);
    }



    /**
     * Set variables HTTP_X_FORWARDED_FOR and REMOTE_ADDR in server
     */
    public function testGetUserIPWithHttpXForwardedFor()
    {
        $request = $this->di->get('request');
        $request->setServer('HTTP_X_FORWARDED_FOR', 'http_x_forwarded_for');
        $request->setServer('REMOTE_ADDR', 'remote_addr');

        $userIP = $this->model->getUserIP($request->getServer());

        $exp = 'http_x_forwarded_for';
        $this->assertEquals($exp, $userIP);
    }

    /**
     * Set variables REMOTE_ADDR in server
     */
    public function testGetUserIPWithRemoteAddr()
    {
        $request = $this->di->get('request');
        $request->setServer('REMOTE_ADDR', 'remote_addr');

        $userIP = $this->model->getUserIP($request->getServer());

        $exp = 'remote_addr';
        $this->assertEquals($exp, $userIP);
    }

    /**
     * Data provider for PregMatchIP.
     */
    public function providerValidIP()
    {
        return [
            ["172.15.255.255", "ip4", true],
            ["92.34.254.220", "ip4", true],
            ["684D:1111:222:3333:4444:5555:6:77", "ip6", true],
            ["2001:0db8:85a3:0000:0000:8a2e:0370:7334", "ip6", true]
        ];
    }


    /**
     * Test pattern recognition with regex
     *
     * @dataProvider providerValidIP
     */
    public function testPregMatchIP($validIP, $type)
    {
        $res = $this->model->pregMatchIP($validIP);
        $this->assertEquals($type, $res);
    }

    /**
     * Test validateIP function
     *
     * @dataProvider providerValidIP
     */
    public function testValidateIP($validIP, $type, $match)
    {
        $res = $this->model->validateIP($validIP);
        $this->assertEquals($type, $res["type"]);
        $this->assertEquals($match, $res["match"]);
    }
}
