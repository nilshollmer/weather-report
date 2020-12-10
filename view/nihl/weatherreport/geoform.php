<?php

namespace Anax\View;

?>
<h3>Sök med koordinater</h3>
<form class="" action="<?= url("weatherreport/geo")?>" method="get">
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
