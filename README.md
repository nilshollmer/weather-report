# Weather report

Anax weather report module for fetching and displaying weather prognosis data.

The module is designed to be used with external services [ipstack](https://ipstack.com/) and [openweathermap](https://openweathermap.org/api).


## Installation

Install this module into your existing Anax installation using composer.  
`composer require nihl/weatherreport`

Integrate it by copying the configuration using the following command:
```
# Root of your Anax based repository
rsync -av vendor/nihl/weatherreport/config/ /config
```

Add a configuration file with your apikeys for ipstack and openweathermap:
`touch config/apikeys.php`  
```
# apikeys.php

return $apikeys = [
    "ipstack" => "your_ipstack_apikey",
    "openweather" => "your_openweathermap_apikey"
];

```
## Routes
```

```

## Dependency

This is a Anax module and its usage is primarly intended to be together with the Anax framework.

You can install an instance on [anax/anax](https://github.com/canax/anax) and run this module inside it, to try it out for test and development.

The repo "[my redovisa-page](https://github.com/nilshollmer/redovisning-ramverk1)" is an example of how that can be done.
