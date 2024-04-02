<?php 
    $passwordDisplayName = $passwordDisplayName ?? "Contraseña";
    $passwordName = $passwordName ?? "password";
?>
<label><?= $passwordDisplayName ?><input name="<?= $passwordName ?>" type="password" required></label>