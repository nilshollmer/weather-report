<?php

namespace Nihl\RemoteService;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

/**
 * Test for modelclass Geotag
 */
class GeotagUsageTest extends TestCase
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



    public function testCreateUrl()
    {
        $ipToTest = "1.2.3.4.5";
        $url = $this->model->createUrl($ipToTest);

        $exp = "http://google.com/1.2.3.4.5?access_key=12345";
        $this->assertEquals($exp, $url);
    }



    public function testGetIPData()
    {
        $ipToTest = "172.15.255.255";
        $res = $this->model->getIPData($ipToTest);

        $exp = "ipv4";
        $this->assertEquals($exp, $res["type"]);

        $exp = 33.690269470214844;
        $this->assertEquals($exp, $res["latitude"]);

        $exp = -117.7899398803711;
        $this->assertEquals($exp, $res["longitude"]);

        $exp = 5359777;
        $this->assertEquals($exp, $res["location"]["geoname_id"]);
    }


    public function testGetMap()
    {
        $latitude = "12.345";
        $longitude = "54.321";
        $mapUrl = $this->model->getMap($latitude, $longitude);

        $exp = "https://www.openstreetmap.org/?mlat=12.345&amp;mlon=54.321#map=6/12.345/54.321";
        $this->assertEquals($exp, $mapUrl);
    }



    public function testRenderMap()
    {
        $latitude = "10";
        $longitude = "20";
        $mapUrl = $this->model->renderMap($latitude, $longitude);

        $exp = <<<EOD
<iframe width="450px" height="450px" src="https://www.openstreetmap.org/export/embed.html?bbox=19.58%2C9.98%2C20.34%2C10.02&amp;layer=mapnik&amp;marker=10%2C20">
</iframe>
<br/>
<small>
    <a href="https://www.openstreetmap.org/?mlat=10&amp;mlon=20#map=6/10/20">
        Visa större karta
    </a>
</small>
EOD;
        $this->assertEquals($exp, $mapUrl);
    }
}
