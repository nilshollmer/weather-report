<?php
/**
 * Load the stylechooser as a controller class.
 */
return [
    "mount" => "weatherreport",

    "routes" => [
        [
            "info" => "Weather Report Rest API",
            "mount" => "api",
            "handler" => "\Nihl\WeatherReport\WeatherReportAPIController",
        ],
        [
            "info" => "Weather Report",
            "mount" => "",
            "handler" => "\Nihl\WeatherReport\WeatherReportController",
        ],
    ]
];
