<?php

namespace Nihl\WeatherReport;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

/**
 * Weather report
 *
 */
class WeatherReportAPIController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;


    /**
     * @var object $weatherreport   instance of WeatherReport class
     */
    private $weatherreport;
    private $ipvalidator;
    private $request;
    private $geotag;
    private $page;

    /**
     * Initiate IPValidator
     *
     * @return void
     */
    public function initialize() : void
    {
        // Use to initialise member variables.
        $this->weatherreport = $this->di->get("weatherreport");
        $this->ipvalidator = $this->di->get("ipvalidator");
        $this->request = $this->di->get("request");
        $this->geotag = $this->di->get("geotag");
        $this->page = $this->di->get("page");
    }

    /**
     * This is the index method action, it handles:
     * GET METHOD mountpoint
     * GET METHOD mountpoint/
     * GET METHOD mountpoint/index
     *
     * @return array
     */
    public function indexAction()
    {
        $title = "Weather report REST API";

        // Get users IP-address
        $userIP = $this->ipvalidator->getUserIP($this->request->getServer());

        $this->page->add("nihl/weatherreport/api/index", ["ip" => $userIP]);

        return $this->page->render([
            "title" => $title
        ]);
    }

    /**
     * This is the ip post method action, it handles:
     * POST mountpoint/ip
     *
     * @return array
     */
    public function ipActionPost()
    {
        $error = [
            "code" => null,
            "message" => null
        ];

        $json = [];

        $ip = $this->request->getPost("ip", null);
        if (!$ip) {
            $error["code"] = 400;
            $error["message"] = "No IP was received.";
            return [$error];
        }

        // Get IP information
        $json["geo"] = $this->geotag->getIPData($ip);

        if ($json["geo"]["type"] != "ipv4") {
            $error["code"] = 400;
            $error["message"] = "IP can't be connected to a geographic position";
            return [$error];
        }

        $lat = $json["geo"]["latitude"];
        $lon = $json["geo"]["longitude"];

        $json["map"] = $this->geotag->getMap($lat, $lon);

        $json["weather"] = $this->weatherreport->getCurrentWeatherByCoord($lat, $lon);


        if ($json["weather"]["cod"] == 200) {
            $timeperiod = $this->request->getPost("timeperiod", null);

            if ($timeperiod == "history") {
                $json["weather"]["history"] = $this->weatherreport->getWeatherHistory($lat, $lon);
            } else {
                $json["weather"]["forecast"] = $this->weatherreport->getWeatherForecast($lat, $lon);
            }
        }

        return [$json];
    }

    /**
     * This is the geo post method action, it handles:
     * POST mountpoint/geo
     *
     * @return array
     */
    public function geoActionPost()
    {
        $error = [
            "code" => null,
            "message" => null
        ];

        $json = [];

        $lat = $this->request->getPost("lat", null);
        $lon = $this->request->getPost("lon", null);

        // Get IP
        if (!$lat || !$lon) {
            $error["code"] = 400;
            $error["message"] = "Latitude or longitude missing in request";
            return [$error];
        }

        $json["map"] = $this->geotag->getMap($lat, $lon);

        $json["weather"] = $this->weatherreport->getCurrentWeatherByCoord($lat, $lon);


        if ($json["weather"]["cod"] == 200) {
            $timeperiod = $this->request->getPost("timeperiod", null);

            if ($timeperiod == "history") {
                $json["weather"]["history"] = $this->weatherreport->getWeatherHistory($lat, $lon);
            } else {
                $json["weather"]["forecast"] = $this->weatherreport->getWeatherForecast($lat, $lon);
            }
        }
        return [$json];
    }

    /**
     * Adding an optional catchAll() method will catch all actions sent to the
     * router. You can then reply with an actual response or return void to
     * allow for the router to move on to next handler.
     * A catchAll() handles the following, if a specific action method is not
     * created:
     * ANY METHOD mountpoint/**
     *
     * @param array $args as a variadic parameter.
     *
     * @return mixed
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function catchAll(...$args)
    {
        $title = "WeatherReport | Route not found";
        $this->page = $this->di->get("page");
        $path = $this->di->get("request")->getRoute();
        $this->page->add("nihl/weatherreport/error", [
            "path" => $path
        ]);

        return $this->page->render([
            "title" => $title
        ]);
    }
}
