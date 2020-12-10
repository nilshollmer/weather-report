<?php

namespace Nihl\RemoteService;

/**
 * A mock class for curl
 */
class CurlGeotagMock extends Curl
{
    public function doRequest(string $url)
    {
        $data = <<<EOD
        {
            "ip":"172.15.255.255",
            "type":"ipv4",
            "continent_code":"NA",
            "continent_name":"North America",
            "country_code":"US",
            "country_name":"United States",
            "region_code":"CA",
            "region_name":"California",
            "city":"Irvine",
            "zip":"92604",
            "latitude":33.690269470214844,
            "longitude":-117.7899398803711,
            "location":
            {
                "geoname_id":5359777,
                "capital":"Washington D.C.",
                "languages":
                    [
                        {
                            "code":"en",
                            "name":"English",
                            "native":"English"
                        }
                    ],
                    "country_flag":"http://assets.ipstack.com/flags/us.svg",
                    "country_flag_emoji":"🇺🇸",
                    "country_flag_emoji_unicode":"U+1F1FA U+1F1F8",
                    "calling_code":"1",
                    "is_eu":false
            }
        }
EOD;

        return $data;
        // return json_decode($data, true);
    }
    public function doRequestNoResult(string $url)
    {
        $data = <<<EOD
        "geotag": {
           "ip": "wrongip",
           "type": null,
           "continent_code": null,
           "continent_name": null,
           "country_code": null,
           "country_name": null,
           "region_code": null,
           "region_name": null,
           "city": null,
           "zip": null,
           "latitude": null,
           "longitude": null,
           "location": {
               "geoname_id": null,
               "capital": null,
               "languages": null,
               "country_flag": null,
               "country_flag_emoji": null,
               "country_flag_emoji_unicode": null,
               "calling_code": null,
               "is_eu": null
           }
        },
EOD;

        return $data;
        // return json_decode($data, true);
    }


    // public function doMultiRequest(string $url)
    // {
    //     return null;
    // }
}
