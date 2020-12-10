<?php

namespace Anax\View;

$weather = $data["weather"] ?? null;

?>
<h3>Vädret inom 7 dagar</h3>
<?php if ($weather) :?>
<table class="table">
    <tbody>
        <tr>
            <th>Datum</th>
            <th>Väder</th>
            <th>Temp (min)</th>
            <th>Temp (max)</th>
            <th>Luftfukt</th>
            <th>Soluppgång</th>
            <th>Solnedgång</th>
        </tr>
        <?php foreach ($weather as $day) : ?>
            <tr>
                <td><?= $day['dt']?></td>
                <td><?= $day['weather']?></td>
                <td><?= $day['min']?>°C</td>
                <td><?= $day['max']?>°C</td>
                <td><?= $day['humidity']?>%</td>
                <td><?= $day['sunrise']?></td>
                <td><?= $day['sunset']?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php else : ?>
<p>Ingen väderinformation kunde hittas för den platsen.</p>
<?php endif; ?>
