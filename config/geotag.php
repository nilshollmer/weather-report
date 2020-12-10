<?php
/**
 * Config file for geotag
 */

require __DIR__ . "/apikeys.php";

return [
    "url" => "http://api.ipstack.com",
    "apikey" => $apikeys["ipstack"]
];
