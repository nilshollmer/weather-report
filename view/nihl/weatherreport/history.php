<?php

namespace Anax\View;

$weather = $data["weather"] ?? null;

?>
<h3>Väderhistorik</h3>
<?php if ($weather) :?>
<table class="table table">
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
        <?php foreach ($weather as $day) : ?>
            <tr>
                <td><?= $day['dt']?></td>
                <td><?= $day['weather']?></td>
                <td><?= $day['temp']?>°C</td>
                <td><?= $day['feels_like']?>°C</td>
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
