<?php

namespace Nihl\RemoteService;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Nihl\RemoteService\Curl;

/**
 * Model class for fetching location data from an ip-address
 */
class Geotag implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    /**
     * $ipDataUrl   Url to access IPData-service
     * $apikey      Apikey for IPData-service
     * $mapSiteUrl  Url for creating map, hardcoded to openstreetmap
     */
    private $ipDataUrl;
    private $apikey;
    private $mapSiteUrl = "https://www.openstreetmap.org";

    /**
     * Set ipDataUrl
     *
     * @param string $url
     */
    public function setUrl(string $url): void
    {
        $this->ipDataUrl = $url;
    }

    /**
     * Set apiKey
     *
     * @param string $key
     */
    public function setApikey(string $key): void
    {
        $this->apikey = $key;
    }

    /**
     * Get JSON-formatted data
     *
     * @param string $ipAddress
     *
     * @return array Array of data
     */
    public function getIPData(string $ipAddress = "")
    {
        $curl = $this->di->get("curl");
        $url = $this->createUrl($ipAddress);
        return json_decode($curl->doRequest($url), true);
    }


    // /**
    //  * Filters latitude and longitude from data
    //  *
    //  * @param string $ipAddress
    //  *
    //  * @return array Array of data
    //  */
    // public function getCoordinatesFromIP(string $ipAddress = "")
    // {
    //     $data = $this->getIPData($ipAddress);
    //     return [
    //         "lat" => $data["latitude"],
    //         "lon" => $data["longitude"]
    //     ];
    // }

    public function createUrl(string $ipAddress = "")
    {
        return "$this->ipDataUrl/$ipAddress?access_key=$this->apikey";
    }

    public function getMap($latitude, $longitude)
    {
        return "$this->mapSiteUrl/?mlat=$latitude&amp;mlon=$longitude#map=6/$latitude/$longitude";
    }

    public function renderMap($latitude, $longitude)
    {
        // Calculate map size
        $lat1 = $latitude * 0.998;
        $long1 = $longitude * 0.979;
        $lat2 = $latitude * 1.002;
        $long2 = $longitude * 1.017;

        // Create URLs for iframe src and a href
        $iframeUrl = "$this->mapSiteUrl/export/embed.html?bbox=$long1%2C$lat1%2C$long2%2C$lat2&amp;layer=mapnik&amp;marker=$latitude%2C$longitude";
        $mapUrl = $this->getMap($latitude, $longitude);

        $mapIframe = <<<EOD
<iframe width="450px" height="450px" src="$iframeUrl">
</iframe>
<br/>
<small>
    <a href="$mapUrl">
        Visa större karta
    </a>
</small>
EOD;
        return $mapIframe;
    }
}
