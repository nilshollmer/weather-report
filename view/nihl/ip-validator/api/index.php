<?php

namespace Anax\View;

?>
<h1>IP-validator Rest API</h1>
Validera ip-adresser enligt ip4 och ip6.
<h4>Test routes:</h4>
<form action="<?= url("ipvalidator/api")?>" method="POST">
    <input type="text" name="ip" value="172.15.255.255" hidden>
    <button type="submit">Test IPv4-adress 172.15.255.255</button>
</form>
<br>
<form action="<?= url("ipvalidator/api")?>" method="POST">
    <input type="text" name="ip" value="2001:0db8:85a3:0000:0000:8a2e:0370:7334" hidden>
    <button type="submit">Test IPv6-adress 2001:0db8:85a3:0000:0000:8a2e:0370:7334</button>
</form>
<h4>API:</h4>
<p>Testa en IP-adress:</p>
<pre><code>POST /ipvalidator/api
{"ip": "your.ip.address.here"}</code></pre>
<p>Resultat:</p>
<pre><code>{
    validation: {
        ip: "172.15.255.255",
        message: "Adressen är en giltig ip4-adress!",
        match: true,
        type: "ip4",
        domain: "172-15-255-255.lightspeed.irvnca.sbcglobal.net"
    },
    geotag: {
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
    map: "https://www.openstreetmap.org/?mlat=33.690269470215&amp;mlon=-117.78993988037#map=6/33.690269470215/-117.78993988037"
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
<h4>Test API:</h4>
<form action="<?= url("ipvalidator/api")?>" method="POST">
    <label for="ip"></label>
    <input type="text" name="ip" value="<?= $data["userIP"]?>">
    <button type="submit">Test IP</button>
</form>
