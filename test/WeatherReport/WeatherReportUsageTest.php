<?php

namespace Nihl\WeatherReport;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

/**
 *
 *
 */
class WeatherReportUsageTest extends TestCase
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
        $this->di->setShared("curl", "\Nihl\RemoteService\CurlWeatherReportMock");

        $this->model = $this->di->get("weatherreport");
        $this->model->setDI($this->di);

        $this->model->setSiteUrl("http://testurl.com");
        $this->model->setApikey("12345");
    }


    public function testGetDatetime()
    {
        $d = new \DateTime("2020-02-20", new \DateTimeZone('Europe/Berlin'));
        $this->model->setCurrentDateTime($d);

        $res = $this->model->getDateTime();

        $this->assertEquals($d, $res);
    }


    public function testBuildUrl()
    {
        $queries = [
            "lat" => 59.49,
            "lon" => 17.92,
            "dt" => 1606483546,
            "appid" => "12345"
        ];
        $url = $this->model->buildUrl("http://testurl.com", $queries, "/onecall/timemachine");

        $exp = "http://testurl.com/onecall/timemachine?lat=59.49&lon=17.92&dt=1606483546&appid=12345";
        $this->assertEquals($exp, $url);
    }

    public function testBuildWeatherHistoryUrls()
    {
        $d = new \DateTime("2020-02-20", new \DateTimeZone('Europe/Berlin'));

        $urls = $this->model->buildWeatherHistoryUrls(1, 2, $d, "12345");
        $exp = [
            "http://testurl.com/onecall/timemachine?lat=1&lon=2&lang=se&units=metric&appid=12345&dt=1582066800",
            "http://testurl.com/onecall/timemachine?lat=1&lon=2&lang=se&units=metric&appid=12345&dt=1581980400",
            "http://testurl.com/onecall/timemachine?lat=1&lon=2&lang=se&units=metric&appid=12345&dt=1581894000",
            "http://testurl.com/onecall/timemachine?lat=1&lon=2&lang=se&units=metric&appid=12345&dt=1581807600",
            "http://testurl.com/onecall/timemachine?lat=1&lon=2&lang=se&units=metric&appid=12345&dt=1581721200"
        ];

        for ($i = 0; $i < count($urls); $i++) {
            $this->assertEquals($exp[$i], $urls[$i]);
        }
    }

    public function testFormatWeatherDataForecast()
    {
        $json = <<<EOD
        {"dt":1607022000,"sunrise":1607006407,"sunset":1607042563,"temp":{"day":20.21,"min":13.11,"max":22,"night":13.11,"eve":19.04,"morn":14.96},"feels_like":{"day":16.36,"night":9.28,"eve":16.38,"morn":9.34},"pressure":1025,"humidity":22,"dew_point":-8.01,"wind_speed":2.24,"wind_deg":60,"weather":[{"id":800,"main":"Clear","description":"klar himmel","icon":"01d"}],"clouds":0,"pop":0,"uvi":2.98}
        EOD;

        $data = json_decode($json, true);

        $exp = [
            'dt' => 'Thu 3 Dec',
            'humidity' => 22,
            'weather' => 'klar himmel',
            'sunrise' => '14:40',
            'sunset' => '00:42',
            'min' => 13.11,
            'max' => 22
        ];

        $res = $this->model->formatWeatherData($data, "forecast");
        $this->assertEquals($exp, $res);
    }

    public function testFormatWeatherDataHistory()
    {
        $json = <<<EOD
        {"dt":1606980555,"sunrise":1606919957,"sunset":1606956167,"temp":17.94,"feels_like":12.86,"pressure":1018,"humidity":11,"dew_point":-11.55,"uvi":2.65,"clouds":1,"visibility":16093,"wind_speed":2.6,"wind_deg":50,"weather":[{"id":800,"main":"Clear","description":"klar himmel","icon":"01n"}]}
        EOD;

        $data = json_decode($json, true);

        $exp = [
            'dt' => 'Thu 3 Dec',
            'humidity' => 11,
            'weather' => 'klar himmel',
            'sunrise' => '14:39',
            'sunset' => '00:42',
            'temp' => 17.94,
            'feels_like' => 12.86
        ];

        $res = $this->model->formatWeatherData($data, "history");
        $this->assertEquals($exp, $res);
    }




    public function testGetCurrentWeatherByCoord()
    {
        $res = $this->model->getCurrentWeatherByCoord(33.690269470215, -117.78993988037);
        $exp = [
            'cod' => 200,
            'dt' => 'Fri 4 Dec',
            'name' => 'Orange',
            'temp' => 13.09,
            'feels_like' => 9.26,
            'humidity' => 30,
            'weather' => 'klar himmel',
            'sunrise' => '14:40',
            'sunset' => '00:42'
        ];

        $this->assertIsArray($res);
        $this->assertEquals($exp, $res);
    }



    public function testGetWeatherHistory()
    {
        $res = $this->model->getWeatherHistory(33.690269470215, -117.78993988037);
        $exp = [
            [
                'dt' => 'Tue 1 Dec',
                'humidity' => 62,
                'weather' => 'klar himmel',
                'sunrise' => '14:37',
                'sunset' => '00:42',
                'temp' => 13.13,
                'feels_like' => 11.98
            ],
            [
                'dt' => 'Wed 2 Dec',
                'humidity' => 62,
                'weather' => 'klar himmel',
                'sunrise' => '14:38',
                'sunset' => '00:42',
                'temp' => 13.9,
                'feels_like' => 12.72
            ],
            [
                'dt' => 'Thu 3 Dec',
                'humidity' => 11,
                'weather' => 'klar himmel',
                'sunrise' => '14:39',
                'sunset' => '00:42',
                'temp' => 17.94,
                'feels_like' => 12.86
            ]
        ];

        $this->assertIsArray($res);

        $this->assertEquals($exp[0], $res[0]);
        $this->assertEquals($exp[1], $res[1]);
        $this->assertEquals($exp[2], $res[2]);
    }


    public function testGetWeatherForecast()
    {
        $res = $this->model->getWeatherForecast(33.690269470215, -117.78993988037);
        $exp = [
            [
                'dt' => 'Thu 3 Dec',
                'humidity' => 22,
                'weather' => 'klar himmel',
                'sunrise' => '14:40',
                'sunset' => '00:42',
                'min' => 13.11,
                'max' => 22
            ],
            [
                'dt' => 'Fri 4 Dec',
                'humidity' => 19,
                'weather' => 'klar himmel',
                'sunrise' => '14:40',
                'sunset' => '00:42',
                'min' => 13.77,
                'max' => 21.73
            ],
            [
                'dt' => 'Sat 5 Dec',
                'humidity' => 23,
                'weather' => 'mulet',
                'sunrise' => '14:41',
                'sunset' => '00:42',
                'min' => 13.37,
                'max' => 19.95
            ],

        ];
        $this->assertIsArray($res);
        $this->assertEquals($exp[0], $res[0]);
        $this->assertEquals($exp[1], $res[1]);
        $this->assertEquals($exp[2], $res[2]);
    }
}
