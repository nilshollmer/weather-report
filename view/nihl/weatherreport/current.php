<?php

namespace Anax\View;

$current = $data["current"] ?? null;

?>
<h1>Väder</h1>
<div class="previous">
    <a href="<?= url("weatherreport")?>">« Ny sökning</a>
</div>
<?php if ($current["cod"] == 200) :?>
<h2>Vädret just nu i <?= $current["name"] ?></h2>
<table class="table">
    <tbody>
        <tr>
            <th>Datum</th>
            <th>Väder</th>
            <th>Temp</th>
            <th>Känns som</th>
            <th>Luftfukt</th>
            <th>Soluppgång</th>
            <th>Solnedgång</th>
        </tr>
        <tr>
            <td><?= $current['dt']?></td>
            <td><?= $current['weather']?></td>
            <td><?= $current['temp']?>°C</td>
            <td><?= $current['feels_like']?>°C</td>
            <td><?= $current['humidity']?>%</td>
            <td><?= $current['sunrise']?></td>
            <td><?= $current['sunset']?></td>
        </tr>
    </tbody>
</table>
<?php else : ?>
<h3>Inget resultat</h3>
<p>Ingen väderinformation kunde hittas för den platsen.</p>
<p><?= $current["message"]?></p>
<?php endif; ?>
