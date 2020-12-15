<?php
/**
 * Config file for weather report service
 */

require __DIR__ . "/apikeys.php";
$apikey = $apikeys["ipstack"] ?? "";

return [
    "url" => "https://api.openweathermap.org/data/2.5",
    "apikey" => $apikey
];
