<?php
/**
 * Config file for geotag
 */

require __DIR__ . "/apikeys.php";

$apikey = $apikeys["ipstack"] ?? "";

return [
    "url" => "http://api.ipstack.com",
    "apikey" => $apikey
];
