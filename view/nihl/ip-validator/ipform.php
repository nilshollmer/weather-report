<?php

namespace Anax\View;

$method = $data["method"];
$url = $data["url"];

?>
<h1>IP-validator</h1>
<form class="" action="<?= url($url)?>" method="<?= $method ?>">
    <label for="ip"></label>
    <input type="text" name="ip" value="<?= $ip ?>">
    <button type="submit">Test IP</button>
</form>
