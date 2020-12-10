<?php

namespace Anax\View;

$search = $search ?? null;
?>
<h1>Väder</h1>
<form class="" action="<?= url("weatherreport")?>" method="get">
    <label for="search">Välj Söksätt:
        <select name="search" onchange="form.submit()">
            <option value="ip" disabled selected>Välj söksätt</option>
            <option value="ip" <?= $search == "ip" ? "selected" : null ?>>IP-adress</option>
            <option value="geo" <?= $search == "geo" ? "selected" : null ?>>Koordinater</option>
        </select>
    </label>
</form>
