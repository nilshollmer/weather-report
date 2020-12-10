<?php

namespace Anax\View;

?>

<h2>Information</h2>
<p>IP: <?= $data["ip"] ?></p>
<p>Typ: <?= $data["type"] ?></p>
<p>Stad: <?= $data["city"] ?> <?= $data["zip"]?>, <?= $data["region_name"] ?></p>
<p>Land: <?= $data["country_name"] ?></p>
<p>Kontinent: <?= $data["continent_name"] ?></p>
<p>Koordinater: Lat: <?= $data["latitude"] ?>, Long: <?= $data["longitude"] ?></p>
<?= $data["map"] ?>
