# Weather report

[![Join the chat at https://gitter.im/nilshollmer/weather-report](https://badges.gitter.im/nilshollmer/weather-report.svg)](https://gitter.im/nilshollmer/weather-report?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

Anax weather report module for fetching and displaying weather prognosis data.

The module is designed to be used with external services [ipstack](https://ipstack.com/) and [openweathermap](https://openweathermap.org/api).


## Installation

**1:**  
Install this module into your existing Anax installation using composer.  
`composer require nihl/weatherreport`

**2:**  
Integrate it by copying the configuration using the following command:
```
# Root of your Anax based repository
rsync -av vendor/nihl/weatherreport/config ./

rsync -av vendor/nihl/weatherreport/view ./

# If you want to test the source code you can also copy the test-catalogue
rsync -av vendor/nihl/weatherreport/test ./

# If you want to edit the source code you can also copy the src-catalogue
rsync -av vendor/nihl/weatherreport/src ./
```

**3:**  
Sign up for an account at [ipstack](https://ipstack.com/) and [openweathermap](https://openweathermap.org/api).
Update apikey-configuration file with your apikeys for ipstack and openweathermap:

```
# config/apikeys.php

return $apikeys = [
    "ipstack" => "your_ipstack_apikey",
    "openweather" => "your_openweathermap_apikey"
];
```

**4:**  
Add your apikey-configuration file to your `.gitignore`.
```
# .gitignore
config/apikeys.php
```

**5:**  
The weatherreport module is now available in your Anax installation.  
Add it to your navbar with the following code:
```
# config/navbar/header
return [
    "items" => [
        ...
        [
            "text" => "Weather",
            "url" => "weatherreport",
            "title" => "Weather report",
            "submenu" => [
                "items" => [
                    [
                        "text" => "Rest API",
                        "url" => "weatherreport/api",
                        "title" => "Weather report-API"
                    ],
                ],
            ],
        ],
    ],
];
```

## Dependency

This is a Anax module and its usage is primarly intended to be together with the Anax framework.

You can install an instance on [anax/anax](https://github.com/canax/anax) and run this module inside it, to try it out for test and development.

The repo "[my redovisa-page](https://github.com/nilshollmer/redovisning-ramverk1)" is an example of how that can be done.
