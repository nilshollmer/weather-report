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
        $this->di->loadServices(ANAX_INSTALL_PATH . "/test/config/di");

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

    public function testDoMultiRequest()
    {
        $urls = [
            "file:///" . __DIR__ . "/data/mcurl1.json",
            "file:///" . __DIR__ . "/data/mcurl2.json",
            "file:///" . __DIR__ . "/data/mcurl3.json"
        ];
        $res = $this->model->doMultiRequest($urls);
        // $res = json_decode($data, true);

        $exp = "test 1 success";
        $this->assertEquals($exp, $res[0]['test']);

        $exp = "test 2 success";
        $this->assertEquals($exp, $res[1]['test']);

        $exp = "test 3 success";
        $this->assertEquals($exp, $res[2]['test']);
    }
}
