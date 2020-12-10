<?php

namespace Nihl\IPValidator;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

/**
 * IP-Validator analyzes ip-adresses according to ip4 and ip6
 *
 */
class IPValidatorAPIController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    private $ipvalidator;
    private $request;
    private $page;
    private $geotag;

    /**
     * Initialize method to inject from di
     *
     * @return void
     */
    public function initialize(): void
    {
        $this->ipvalidator = $this->di->get("ipvalidator");
        $this->request = $this->di->get("request");
        $this->page = $this->di->get("page");
        $this->geotag = $this->di->get("geotag");
    }
    /**
     * This is the index method action, it handles:
     * GET METHOD mountpoint
     * GET METHOD mountpoint/
     * GET METHOD mountpoint/index
     *
     * @return array
     */
    public function indexActionGet()
    {
        $title = "IP-Validator REST API";

        // Get users IP-address
        $userIP = $this->ipvalidator->getUserIP($this->request->getServer());

        $this->page->add("nihl/ip-validator/api/index", [
            "userIP" => $userIP
        ]);

        return $this->page->render([
            "title" => $title
        ]);
    }

    /**
     * This is the index post method action, it handles:
     * POST mountpoint
     *
     * @return array
     */
    public function indexActionPost()
    {
        $ipToValidate = $this->request->getPost("ip", null);

        $validation = $this->ipvalidator->validateIP($ipToValidate);
        $geotagData = $this->geotag->getIPData($ipToValidate);

        $json = [
            "validation" => $validation
        ];

        if ($validation["match"] && $validation["type"] == "ip4") {
            $json["geotag"] = $this->geotag->getIPData($ipToValidate);
        }

        if (array_key_exists("geotag", $json) && $json["geotag"]["latitude"]) {
            $json["map"] = $this->geotag->getMap($geotagData["latitude"], $geotagData["longitude"]);
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
        $data = [
            "error" => "Bad request. Invalid url."
        ];

        return [$data, 400];
    }
}
