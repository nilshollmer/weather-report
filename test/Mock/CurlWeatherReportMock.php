<?php

namespace Nihl\RemoteService;

/**
 * A mock class for curl
 */
class CurlWeatherReportMock extends Curl
{
    public function doRequest(string $url)
    {
        $data = "";
        switch ($url) {
            case "http://testurl.com/172.15.255.255?access_key=12345":
                $data = <<<EOD
                {"ip":"172.15.255.255","type":"ipv4","continent_code":"NA","continent_name":"North America","country_code":"US","country_name":"United States","region_code":"CA","region_name":"California","city":"Irvine","zip":"92604","latitude":33.690269470214844,"longitude":-117.7899398803711,"location":{"geoname_id":5359777,"capital":"Washington D.C.","languages":[{"code":"en","name":"English","native":"English"}],"country_flag":"http://assets.ipstack.com/flags/us.svg","country_flag_emoji":"🇺🇸","country_flag_emoji_unicode":"U+1F1FA U+1F1F8","calling_code":"1","is_eu":false}}
                EOD;
                break;
            case "http://testurl.com/weather?lat=33.690269470215&lon=-117.78993988037&lang=se&units=metric&appid=12345":
                $data = <<<EOD
                {"coord":{"lon":-117.79,"lat":33.69},"weather":[{"id":800,"main":"Clear","description":"klar himmel","icon":"01n"}],"base":"stations","main":{"temp":13.09,"feels_like":9.26,"temp_min":11.11,"temp_max":15.56,"pressure":1023,"humidity":30},"visibility":10000,"wind":{"speed":1.89,"deg":73},"clouds":{"all":1},"dt":1607063699,"sys":{"type":1,"id":5876,"country":"US","sunrise":1607006407,"sunset":1607042563},"timezone":-28800,"id":5379524,"name":"Orange","cod":200}
                EOD;
                break;
            case "http://testurl.com/onecall?lat=33.690269470215&lon=-117.78993988037&exclude=minutely%2Chourly%2Ccurrent&lang=se&units=metric&appid=12345":
                $data = <<<EOD
                {"lat":33.69,"lon":-117.79,"timezone":"America/Los_Angeles","timezone_offset":-28800,"daily":[{"dt":1607022000,"sunrise":1607006407,"sunset":1607042563,"temp":{"day":20.21,"min":13.11,"max":22,"night":13.11,"eve":19.04,"morn":14.96},"feels_like":{"day":16.36,"night":9.28,"eve":16.38,"morn":9.34},"pressure":1025,"humidity":22,"dew_point":-8.01,"wind_speed":2.24,"wind_deg":60,"weather":[{"id":800,"main":"Clear","description":"klar himmel","icon":"01d"}],"clouds":0,"pop":0,"uvi":2.98},{"dt":1607108400,"sunrise":1607092857,"sunset":1607128962,"temp":{"day":20.63,"min":13.77,"max":21.73,"night":14.39,"eve":16.99,"morn":14.01},"feels_like":{"day":17.78,"night":12.01,"eve":13.51,"morn":10.53},"pressure":1023,"humidity":19,"dew_point":-12.04,"wind_speed":0.53,"wind_deg":337,"weather":[{"id":800,"main":"Clear","description":"klar himmel","icon":"01d"}],"clouds":0,"pop":0,"uvi":3.05},{"dt":1607194800,"sunrise":1607179305,"sunset":1607215363,"temp":{"day":19.05,"min":13.37,"max":19.95,"night":14.08,"eve":16.32,"morn":13.6},"feels_like":{"day":16.18,"night":11.81,"eve":13.29,"morn":10.8},"pressure":1025,"humidity":23,"dew_point":-8.43,"wind_speed":0.77,"wind_deg":241,"weather":[{"id":804,"main":"Clouds","description":"mulet","icon":"04d"}],"clouds":98,"pop":0,"uvi":3.02},{"dt":1607281200,"sunrise":1607265753,"sunset":1607301765,"temp":{"day":20.1,"min":13.72,"max":21.13,"night":14.93,"eve":19.1,"morn":13.79},"feels_like":{"day":17.07,"night":12.63,"eve":15.82,"morn":10.86},"pressure":1022,"humidity":22,"dew_point":-8.24,"wind_speed":1.05,"wind_deg":293,"weather":[{"id":800,"main":"Clear","description":"klar himmel","icon":"01d"}],"clouds":0,"pop":0,"uvi":2.92},{"dt":1607367600,"sunrise":1607352200,"sunset":1607388169,"temp":{"day":19.05,"min":13.64,"max":21.11,"night":17.41,"eve":20.28,"morn":13.64},"feels_like":{"day":14.9,"night":13.86,"eve":15.12,"morn":10.82},"pressure":1016,"humidity":25,"dew_point":-4.72,"wind_speed":2.81,"wind_deg":70,"weather":[{"id":800,"main":"Clear","description":"klar himmel","icon":"01d"}],"clouds":0,"pop":0,"uvi":2.89},{"dt":1607454000,"sunrise":1607438646,"sunset":1607474575,"temp":{"day":22.79,"min":18,"max":25.12,"night":19.29,"eve":24.07,"morn":18},"feels_like":{"day":16.96,"night":15.38,"eve":19.69,"morn":13.11},"pressure":1018,"humidity":19,"dew_point":-5.72,"wind_speed":5.09,"wind_deg":48,"weather":[{"id":802,"main":"Clouds","description":"växlande molnighet","icon":"03d"}],"clouds":31,"pop":0,"uvi":3},{"dt":1607540400,"sunrise":1607525090,"sunset":1607560983,"temp":{"day":22.36,"min":17.52,"max":24.01,"night":17.87,"eve":22.29,"morn":17.78},"feels_like":{"day":19.88,"night":15.26,"eve":18.28,"morn":13.95},"pressure":1018,"humidity":20,"dew_point":-5.11,"wind_speed":0.37,"wind_deg":175,"weather":[{"id":800,"main":"Clear","description":"klar himmel","icon":"01d"}],"clouds":0,"pop":0,"uvi":3},{"dt":1607626800,"sunrise":1607611534,"sunset":1607647393,"temp":{"day":20.29,"min":15.08,"max":21.79,"night":15.08,"eve":19.82,"morn":16},"feels_like":{"day":17.51,"night":12.21,"eve":16.55,"morn":13.45},"pressure":1017,"humidity":24,"dew_point":-2.58,"wind_speed":0.95,"wind_deg":250,"weather":[{"id":800,"main":"Clear","description":"klar himmel","icon":"01d"}],"clouds":0,"pop":0,"uvi":3}]}
                EOD;
                break;
            default:
                $data = <<<EOD
                {"cod":"400","message":"error", "type": ""}
                EOD;
        }

        return $data;
    }

    public function doMultiRequest(array $urls)
    {
        $data = [];
        $data[] = <<<EOD
        {"lat":33.69,"lon":-117.79,"timezone":"America/Los_Angeles","timezone_offset":-28800,"current":{"dt":1606980555,"sunrise":1606919957,"sunset":1606956167,"temp":17.94,"feels_like":12.86,"pressure":1018,"humidity":11,"dew_point":-11.55,"uvi":2.65,"clouds":1,"visibility":16093,"wind_speed":2.6,"wind_deg":50,"weather":[{"id":800,"main":"Clear","description":"klar himmel","icon":"01n"}]}}
        EOD;
        $data[] = <<<EOD
        {"lat":33.69,"lon":-117.79,"timezone":"America/Los_Angeles","timezone_offset":-28800,"current":{"dt":1606894155,"sunrise":1606833506,"sunset":1606869771,"temp":13.9,"feels_like":12.72,"pressure":1017,"humidity":62,"dew_point":6.75,"uvi":2.56,"clouds":1,"visibility":16093,"wind_speed":0.6,"wind_deg":17,"weather":[{"id":800,"main":"Clear","description":"klar himmel","icon":"01n"}]}}
        EOD;
        $data[] = <<<EOD
        {"lat":33.69,"lon":-117.79,"timezone":"America/Los_Angeles","timezone_offset":-28800,"current":{"dt":1606807755,"sunrise":1606747055,"sunset":1606783378,"temp":13.13,"feels_like":11.98,"pressure":1019,"humidity":62,"dew_point":6.02,"uvi":2.66,"clouds":1,"visibility":16093,"wind_speed":0.34,"wind_deg":266,"weather":[{"id":800,"main":"Clear","description":"klar himmel","icon":"01n"}]}}
        EOD;
        // return json_decode($data, true);
        $response = [];
        foreach ($data as $item) {
            $response[] = json_decode($item, true);
        }
        return $response;
    }
}
