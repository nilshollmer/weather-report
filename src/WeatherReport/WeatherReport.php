<?php

namespace Nihl\WeatherReport;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

/**
 * Class module for weather report service
 */
class WeatherReport implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    /**
     * @var String  $siteUrl            URL to fetch weather data
     * @var String  $apikey             Apikey for access
     * @var Integer $currentDateTime    Datetime of current time.
     */
    private $siteUrl;
    private $apikey;
    private $currentDateTime;



    /**
     * Set base url
     *
     * @param String $url
     */
    public function setSiteUrl(string $url)
    {
        $this->siteUrl = $url;
    }



    /**
     * Set apikey
     *
     * @param String $apikey
     */
    public function setApikey(string $apikey)
    {
        $this->apikey = $apikey;
    }


    /**
     * Set current timestamp
     *
     */
    public function setCurrentDateTime(object $dt = null)
    {
        $this->currentDateTime = $dt instanceof \Datetime ? $dt : new \Datetime();
    }



    /**
     * Get current timestamp
     *
     * @return Datetime
     */
    public function getDatetime()
    {
        return $this->currentDateTime ?? new \Datetime();
    }



    /**
     * Build url strings, extending http_build_query
     *
     * @param String    $baseUrl    Url to use
     * @param Array     $queries    Array with queries
     * @param String    $path       [Optional] Path to api
     *
     * @return String
     */
    public function buildUrl(string $baseUrl, array $queries, string $path = "")
    {
        $queryString = http_build_query($queries);

        return $baseUrl . $path . "?$queryString";
    }



    /**
     * Creates an array of URLs to use with openweathermap.org
     * eg. http://api.openweathermap.org/data/2.5/onecall/timemachine?lat=$lat&lon=$lon&appid=$apikey&dt=$dt->getTimestamp()
     *
     * @param Float     $lat    Latitude
     * @param Float     $lon    Longitude
     * @param Object    $dt     Datetime of request
     * @param String    $apikey Apikey to access data
     *
     * @return Array    $urls   Array of strings
     */
    public function buildWeatherHistoryUrls(float $lat, float $lon, object $dt, string $apikey)
    {
        $queries = [
            "lat" => $lat,
            "lon" => $lon,
            "lang" => "se",
            "units" => "metric",
            "appid" => $apikey
        ];

        $urls = array();
        for ($i = 0; $i < 5; $i++) {
            $dt->modify("-1 day");
            $queries["dt"] = $dt->getTimestamp();
            $urls[] = $this->buildUrl($this->siteUrl, $queries, "/onecall/timemachine");
        }
        return $urls;
    }

    /**
     * Formats data from openweathermap
     *
     * @param  Array    $data       Data to be formatted
     * @param  String   $format     Changes how data is formatted
     *
     * @return Array    $formattedData
     */
    public function formatWeatherData(array $data, string $format)
    {
        $formattedData = [
            "dt" => date('D j M', $data["dt"]),
            "humidity" => $data["humidity"],
            "weather" => $data["weather"][0]["description"],
            "sunrise" => date("H:i", $data["sunrise"]),
            "sunset" => date("H:i", $data["sunset"])
        ];

        if ($format == "forecast") {
            $formattedData["min"] = $data["temp"]["min"];
            $formattedData["max"] = $data["temp"]["max"];
        }

        if ($format == "history") {
            $formattedData["temp"] = $data["temp"];
            $formattedData["feels_like"] = $data["feels_like"];
        }

        return $formattedData;
    }



    /**
     * Make 5 curl requests to a weather api to get weather data from the last five days
     *
     * @param Float     $lat    Latitude of position
     * @param Float     $lon    Longitude of position
     *
     * @return Array            Array of data
     */
    public function getWeatherHistory($lat = null, $lon = null)
    {
        // Inject curl
        $curl = $this->di->get("curl");

        // Get current datetime
        $dt = $this->getDatetime();

        // Build urls
        $urls = $this->buildWeatherHistoryUrls($lat, $lon, $dt, $this->apikey);
        $data = $curl->doMultiRequest($urls);

        // Format data to get desired output
        $formattedData = [];
        foreach ($data as $day) {
            array_unshift($formattedData, $this->formatWeatherData($day["current"], "history"));
        }

        return $formattedData;
    }



    /**
     * Get weather forecast of the coming week
     *
     * @param float     $lat    Latitude of position
     * @param float     $lon    Longitude of position
     *
     * @return array            Array of data
     */
    public function getWeatherForecast($lat = null, $lon = null)
    {
        $curl = $this->di->get("curl");

        $queries = [
            "lat" => $lat,
            "lon" => $lon,
            "exclude" => "minutely,hourly,current",
            "lang" => "se",
            "units" => "metric",
            "appid" => $this->apikey
        ];

        // Create url and make curl request
        $url = $this->buildUrl($this->siteUrl, $queries, "/onecall");
        $data = json_decode($curl->doRequest($url), true);

        foreach ($data["daily"] as $day) {
            $formattedData[] = $this->formatWeatherData($day, "forecast");
        }
        return $formattedData;
    }



    /**
     * Get current weather using coord
     *
     * @param float     $lat    Latitude of position
     * @param float     $lon    Longitude of position
     *
     * @return array            Array of data
     */
    public function getCurrentWeatherByCoord($lat = null, $lon = null)
    {
        $curl = $this->di->get("curl");

        $queries = [
            "lat" => $lat,
            "lon" => $lon,
            "lang" => "se",
            "units" => "metric",
            "appid" => $this->apikey
        ];

        // Create url and make curl request
        $url = $this->buildUrl($this->siteUrl, $queries, "/weather");
        $data = json_decode($curl->doRequest($url), true);
        // Check if data response is ok and format data
        // else return data
        if ($data["cod"] != 200) {
            return $data;
        }

        $formattedData = [
            "cod" => $data["cod"],
            "dt" => date('D j M', $data["dt"]),
            "name" => $data["name"],
            "temp" => $data["main"]["temp"],
            "feels_like" => $data["main"]["feels_like"],
            "humidity" => $data["main"]["humidity"],
            "weather" => $data["weather"][0]["description"],
            "sunrise" => date("H:i", $data["sys"]["sunrise"]),
            "sunset" => date("H:i", $data["sys"]["sunset"]),
        ];

        return $formattedData;
    }
}
