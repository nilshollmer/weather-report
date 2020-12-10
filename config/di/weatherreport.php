<?php
/**
 * Configuration file for DI container.
 */
return [
    // Services to add to the container.
    "services" => [
        "weatherreport" => [
            // Is the service shared, true or false
            // Optional, default is true
            "shared" => true,

            // Is the service activated by default, true or false
            // Optional, default is false
            "active" => false,

            // Callback executed when service is activated
            // Create the service, load its configuration (if any)
            // and set it up.
            "callback" => function () {
                $weatherreport = new \Nihl\WeatherReport\WeatherReport();
                $weatherreport->setDi($this);

                $cfg = $this->get("configuration");
                $config = $cfg->load("weatherreport.php");
                $settings = $config["config"] ?? null;

                if ($settings["url"] ?? null) {
                    $weatherreport->setSiteUrl($settings["url"]);
                }

                if ($settings["apikey"] ?? null) {
                    $weatherreport->setApikey($settings["apikey"]);
                }

                $weatherreport->setCurrentDatetime();
                return $weatherreport;
            }
        ],
    ],
];
