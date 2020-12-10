<?php

namespace Anax\View;

?>
<h1>IP-validator</h1>
<form class="" action="<?= url("ipvalidator")?>" method="get">
    <label for="ip"></label>
    <input type="text" name="ip" value="<?= $ip ?>">
    <button type="submit">Test IP</button>
</form>
<p>
    IP: <?= htmlentities($data["ip"]) ?>
</p>
<p>
    Resultat: <?= $data["message"] ?>
</p>
<p>
    Match: <?= $data["match"] ?>
</p>
<p>
    Typ: <?= $data["type"] ?>
</p>
<p>
    Domännamn: <?= $data["domain"] ?>
</p>
