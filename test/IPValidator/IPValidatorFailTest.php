<?php

namespace Nihl\IPValidator;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

/**
 * Test for modelclass Geotag
 */
class IPValidatorFailTest extends TestCase
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
     * Data provider for Pregmatch
     */
    public function providerInvalidIP()
    {
        return [
            ["abcdefg"],
            ["1. .3.4"],
            ["192.0.2"],
            ["123::abcdef"],
            [""]
        ];
    }


    /**
     * Test pattern recognition with regex
     *
     * @dataProvider providerInvalidIP
     */
    public function testPregMatchIPFail($invalidIP)
    {
        $res = $this->model->pregMatchIP($invalidIP);
        $this->assertEmpty($res);
    }

    /**
     * Test validateIP function
     *
     * @dataProvider providerInvalidIP
     */
    public function testValidateIPFail($invalidIP)
    {
        $res = $this->model->validateIP($invalidIP);
        $this->assertFalse($res["match"]);
    }
}
