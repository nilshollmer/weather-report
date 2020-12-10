<?php

namespace Anax\View;

$message = $data["message"] ?? null;
?>
<h1>Inget resultat</h1>
<div class="previous">
    <a href="<?= url("weatherreport")?>">«  Ny sökning</a>
</div>
<p>Inget väderresultat kunde hittas.</p>

<?php if ($message) : ?>
<h4>Error:</h4>
<p> <?= $message ?></p>
<?php endif;?>
