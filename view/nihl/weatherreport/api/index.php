<?php

namespace Anax\View;

?>
<h1>Weather report Rest API</h1>
<p>
    Hämta nuvarande, föregående och kommande väderprognoser om en geografisk position tillsammans med information om platsen samta karta.
</p>
<p>

</p>
<h4>Test IP:</h4>
<form action="<?= url("weatherreport/api/ip")?>" method="POST">
    <input type="text" name="ip" value="172.15.255.255" hidden>
    <input type="text" name="timeperiod" value="forecast" hidden>
    <button type="submit">Test IPv4-adress 172.15.255.255 forecast</button>
</form>
<form action="<?= url("weatherreport/api/ip")?>" method="POST">
    <input type="text" name="ip" value="172.15.255.255" hidden>
    <input type="text" name="timeperiod" value="history" hidden>
    <button type="submit">Test IPv4-adress 172.15.255.255 history</button>
</form>
<h4>Test coordinates:</h4>
<form action="<?= url("weatherreport/api/geo")?>" method="POST">
    <input type="text" name="lat" value="33.690269" hidden>
    <input type="text" name="lon" value="-117.78993" hidden>
    <input type="text" name="timeperiod" value="forecast" hidden>
    <button type="submit">Test coordinates 33.690269, -117.78993 forecast</button>

</form>
<form action="<?= url("weatherreport/api/geo")?>" method="POST">
    <input type="text" name="lat" value="33.690269" hidden>
    <input type="text" name="lon" value="-117.78993" hidden>
    <input type="text" name="timeperiod" value="history" hidden>
    <button type="submit">Test coordinates 33.690269, -117.78993 history</button>
</form>
<h4>API:</h4>
<p>Testa en IP-adress:</p>
<pre><code>POST /ipvalidator/api
{"ip": "172.15.255.255"}</code></pre>
<p>Resultat:</p>
<pre><code>{
    geo: {
        ip: "172.15.255.255",
        type: "ipv4",
        continent_code: "NA",
        continent_name: "North America",
        country_code: "US",
        country_name: "United States",
        region_code: "CA",
        region_name: "California",
        city: "Irvine",
        zip: "92604",
        latitude: 33.690269470214844,
        longitude: -117.7899398803711,
        location: {
            geoname_id: 5359777,
            capital: "Washington D.C.",
            languages: [
                {
                    code: "en",
                    name: "English",
                    native: "English"
                }
            ],
            country_flag: "http://assets.ipstack.com/flags/us.svg",
            country_flag_emoji: "🇺🇸",
            country_flag_emoji_unicode: "U+1F1FA U+1F1F8",
            calling_code: "1",
            is_eu: false
        }
    },
    map: "https://www.openstreetmap.org/?mlat=33.690269470215&amp;mlon=-117.78993988037#map=6/33.690269470215/-117.78993988037",
    weather: {
        cod: 200,
        dt: "Thu 3 Dec",
        name: "Orange",
        temp: 22.08,
        feels_like: 17.15,
        humidity: 11,
        weather: "smogg",
        sunrise: "15:40",
        sunset: "01:42",
        forecast: [
            {...},
            {...},
            {...},
            {...},
            {...},
            {...},
            {...},
            {...}
        ]
    }
}
</code></pre>
<p>Resultat ej hittat:</p>

<pre><code>{
    validation: {
        "ip": "wrongip",
        "message": "Adressen är ej giltig!",
        "match": false,
        "type": "",
        "domain": null
    }
}
</code></pre>
<h3>Sök med IP-adress</h3>

<form action="<?= url("weatherreport/api/ip")?>" method="POST">
    <fieldset>
        <p>
            <label for="ip">IP-adress:
                <input type="text" name="ip" value="<?= $ip ?>">
            </label>
        </p>
        <p>
        <label for="forecast">Kommande väder:
            <input type="radio" name="timeperiod" id="forecast" value="forecast" checked>
        </label>
        </p>
        <p>
        <label for="history">Väderhistorik:
            <input type="radio" name="timeperiod" id="history" value="history">
        </label>
        </p>
        <button type="submit">Sök</button>
    </fieldset>
</form>

<h3>Sök med koordinater</h3>
<form action="<?= url("weatherreport/api/geo")?>" method="POST">
    <fieldset>
        <p>
            <label for="lat">Latitude:
                <input type="text" name="lat" value="">
            </label>
        </p>
        <p>
            <label for="lon">Longitude:
                <input type="text" name="lon" value="">
            </label>
        </p>
        <p>
        <label for="forecast">Kommande väder:
            <input type="radio" name="timeperiod" id="forecast" value="forecast" checked>
        </label>
        </p>
        <p>
        <label for="history">Väderhistorik:
            <input type="radio" name="timeperiod" id="history" value="history">
        </label>
        </p>
        <button type="submit">Sök</button>
    </fieldset>
</form>
