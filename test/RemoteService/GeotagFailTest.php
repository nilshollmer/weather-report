<?php

namespace Nihl\RemoteService;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

/**
 * Test for modelclass Geotag
 */
class GeotagFailTest extends TestCase
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
        // Set mock as service in the di replacing the original class
        $this->di->setShared("curl", "\Nihl\RemoteService\CurlGeotagMock");

        $this->model = $this->di->get("geotag");
        $this->model->setDI($this->di);

        $this->model->setUrl("http://google.com");
        $this->model->setApikey("12345");
    }



    public function testCreateUrlNoArgument()
    {
        $url = $this->model->createUrl();

        $exp = "http://google.com/?access_key=12345";
        $this->assertEquals($exp, $url);
    }



    public function testGetIPDataNoArgument()
    {
        $res = $this->model->getIPData();

        $exp = "ipv4";
        $this->assertEquals($exp, $res["type"]);

        $exp = 33.690269470214844;
        $this->assertEquals($exp, $res["latitude"]);

        $exp = -117.7899398803711;
        $this->assertEquals($exp, $res["longitude"]);

        $exp = 5359777;
        $this->assertEquals($exp, $res["location"]["geoname_id"]);
    }
}
