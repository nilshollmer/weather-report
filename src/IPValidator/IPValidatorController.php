<?php

namespace Nihl\IPValidator;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

/**
 * IP-Validator analyzes ip-adresses according to ip4 and ip6
 *
 */
class IPValidatorController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;


    /**
     * @var object $ipvalidator instance of IPValidator class
     */
    private $ipvalidator;

    /**
     * Initiate IPValidator
     *
     * @return void
     */
    public function initialize() : void
    {
        // Use to initialise member variables.
        $this->ipvalidator = $this->di->get("ipvalidator");
    }


    /**
     * Index action
     *
     * @return object
     */
    public function indexAction() : object
    {
        $title = "IP-Validator";

        // Fetch services from di
        $request = $this->di->get("request");
        $ipvalidator = $this->di->get("ipvalidator");
        $page = $this->di->get("page");
        $geotag = $this->di->get("geotag");

        // Get users IP-address
        $userIP = $ipvalidator->getUserIP($request->getServer());

        // Set IP to validate, UserIP as default
        $ipToValidate = $request->getGet("ip", $userIP);

        // Validate ip
        $data = $ipvalidator->validateIP($ipToValidate);

        // Render index page
        $page->add("nihl/ip-validator/index", $data);

        // Add location data and map if it is available
        $geotagdata = $geotag->getIPData($ipToValidate);

        if (is_array($geotagdata) && $geotagdata["type"]) {
            $geotagdata["map"] = $geotag->renderMap($geotagdata["latitude"], $geotagdata["longitude"]);

            $page->add("nihl/ip-validator/geotag", $geotagdata);
        }

        return $page->render([
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
        $page = $this->di->get("page");
        $path = $this->di->get("request")->getRoute();
        $page->add("nihl/ip-validator/error", [
            "path" => $path
        ]);

        return $page->render([
            "title" => $title
        ]);
    }
}
