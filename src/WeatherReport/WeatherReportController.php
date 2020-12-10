<?php

namespace Nihl\WeatherReport;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

/**
 * Weather report
 *
 */
class WeatherReportController implements ContainerInjectableInterface
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
     * Index action get
     *
     * @return object
     */
    public function indexAction() : object
    {
        $title = "Weather Report";

        // If get is set
        $search = $this->request->getGet("search", null);

        // Render index page
        $this->page->add("nihl/weatherreport/index", ["search" => $search]);

        if ($search == "ip") {
            $userIP = $this->ipvalidator->getUserIP($this->request->getServer());
            $this->page->add("nihl/weatherreport/ipform", ["ip" => $userIP]);
        }

        if ($search == "geo") {
            $this->page->add("nihl/weatherreport/geoform", []);
        }

        return $this->page->render([
            "title" => $title
        ]);
    }



    /**
     * Route for IP-based weather search
     *
     * @return object
     */
    public function ipActionGet() : object
    {
        $title = "Weather Report";

        // Retrieve GET-data
        $ip = $this->request->getGet("ip", null);
        $tp = $this->request->getGet("timeperiod", null);

        $data = [];

        // Fetch data about IP address from IP-stack
        $geo = $this->geotag->getIPData($ip);
        $lat = $geo["latitude"] ?? null;
        $lon = $geo["longitude"] ?? null;

        // Get current weather
        $data["current"] = $this->weatherreport->getCurrentWeatherByCoord($lat, $lon);

        if ($data["current"]["cod"] != 200) {
            $title .= " | Not Found";
            $this->page->add("nihl/weatherreport/result", [
                "message" => $data["current"]["message"]
            ]);
            $this->page->add("nihl/weatherreport/ipform", ["ip" => $ip]);

            return $this->page->render([ "title" => $title ]);
        }

        $this->page->add("nihl/weatherreport/current", $data);

        // Get weather history or forecast based in timeperiod variable in GET
        if ($tp == "history") {
            $data["weather"] = $this->weatherreport->getWeatherHistory($lat, $lon);
            $this->page->add("nihl/weatherreport/history", $data);
        } else {
            $data["weather"] = $this->weatherreport->getWeatherForecast($lat, $lon);
            $this->page->add("nihl/weatherreport/forecast", $data);
        }

        // Add map to geotag
        $geo["map"] = $this->geotag->renderMap($lat, $lon);
        $this->page->add("nihl/ip-validator/geotag", $geo);

        return $this->page->render([
            "title" => $title
        ]);
    }

    /**
     * Route for coordinates-based weather search
     *
     * @return object
     */
    public function geoActionGet() : object
    {
        $title = "Weather Report";
        $data = [];

        // Retrieve GET-data
        $lat = $this->request->getGet("lat", null);
        $lon = $this->request->getGet("lon", null);
        $tp = $this->request->getGet("timeperiod", null);

        // Get current weather
        $data["current"] = $this->weatherreport->getCurrentWeatherByCoord($lat, $lon);

        if ($data["current"]["cod"] != 200) {
            $title .= " | Not Found";
            $this->page->add("nihl/weatherreport/result", [
                "message" => $data["current"]["message"]
            ]);
            $this->page->add("nihl/weatherreport/geoform", []);

            return $this->page->render([ "title" => $title ]);
        }

        $this->page->add("nihl/weatherreport/current", $data);

        // Get weather history or forecast based in timeperiod variable in GET
        if ($tp == "history") {
            $data["weather"] = $this->weatherreport->getWeatherHistory($lat, $lon);
            $this->page->add("nihl/weatherreport/history", $data);
        } else {
            $data["weather"] = $this->weatherreport->getWeatherForecast($lat, $lon);
            $this->page->add("nihl/weatherreport/forecast", $data);
        }

        return $this->page->render([
            "title" => $title
        ]);
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
        $title = "IP-Validator | Route not found";
        $this->page = $this->di->get("page");
        $path = $this->di->get("request")->getRoute();
        $this->page->add("nihl/ip-validator/error", [
            "path" => $path
        ]);

        return $this->page->render([
            "title" => $title
        ]);
    }
}
