<?php

namespace Anax\View;

$ip = $ip ?? null;

?>
<h3>Sök med IP-adress</h3>

<form class="" action="<?= url("weatherreport/ip")?>" method="get">
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
