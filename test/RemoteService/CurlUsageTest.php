<?php

namespace Nihl\RemoteService;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

/**
 * Usage tests for modelclass Curl
 */
class CurlUsageTest extends TestCase
{
    // Create the di container and model.
    protected $di;
    protected $model;

    public function setUp()
    {
        // Create the di container and load services
        $this->di = new DIFactoryConfig();

        $this->di->loadServices(ANAX_INSTALL_PATH . "/config/di");
        // $di->loadServices(ANAX_INSTALL_PATH . "/test/config/di");

        $this->di->get("cache")->setPath(ANAX_INSTALL_PATH . "/test/cache");

        $this->model = $this->di->get("curl");
    }

    public function testDoRequest()
    {
        $url = "file:///" . __DIR__ . "/data/curl.json";
        $data = $this->model->doRequest($url);
        $res = json_decode($data, true);
        $exp = "success";
        $this->assertEquals($exp, $res['test']);

        $url = "";
        $data = $this->model->doRequest($url);
        $res = json_decode($data, true);
        $exp = null;
        $this->assertEquals($exp, $res);
    }
}
