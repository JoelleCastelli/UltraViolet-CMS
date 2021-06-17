<?php

if(isset($errors)) {
    foreach ($errors as $error) {
        echo "<li>$error</li>";
    }
}?>

Vous ne trouvez pas votre bonheur sur TMDB ?
Pas de problème, créez une fiche production vous-même !

<a href="<?= \App\Core\Helpers::callRoute('productions_creation') ?>">
    <button class="btn">Nouvelle production</button>
</a>


<?php App\Core\FormBuilder::render($form); ?>


<div id="production-preview"></div>