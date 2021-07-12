<?php
    if(isset($errors)) {
        echo "<div class='error-message-form'>";
        foreach ($errors as $error) {
            if(count($errors) == 1)
                echo "$error";
            else
                echo "<li>$error</li>";
        }
        echo "</div>";
    }

?>

<div id="settings">

    <?php App\Core\FormBuilder::render($form) ?>

    <div id="dbSettings" class="card">
        <h2>Informations de la base de données</h2>
        <div><strong>Nom :</strong>  <?= $settings['DBNAME']?></div>
        <div><strong>Hôte :</strong>  <?= $settings['DBHOST']?></div>
        <div><strong>Port :</strong>  <?= $settings['DBPORT']?></div>
        <div><strong>Utilisateur :</strong>  <?= $settings['DBUSER']?></div>
        <div><strong>Mot de passe :</strong> <?= $settings['DBPWD']?></div>
        <div><strong>Driver :</strong> <?= $settings['DBDRIVER']?></div>
    </div>
</div>