<h1 class="title-form"><?= APP_NAME ?></h1>
<div id='login' class="card">
    <div class="error-message-form">
        <?php
        if(isset($errors)) {
            foreach ($errors as $error) {
                echo "<li>".$error."</li>";
            }
        }
        ?>
    </div>

    <?php App\Core\FormBuilder::render($form, true); ?>
    <div>
        Mot de passe perdu ? <a href="<?= \App\Core\Helpers::callRoute('')?>">Réinitialiser mon mot de passe</a>
    </div>
    <div>
        Vous n'avez pas de compte ? <a href="<?= \App\Core\Helpers::callRoute('')?>">Rejoignez-nous !</a>
    </div>
</div>