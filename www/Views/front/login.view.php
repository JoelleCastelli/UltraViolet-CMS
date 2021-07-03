<h1 class="title-form"><?= \App\Core\Helpers::getSetting('appName') ?></h1>
<div id='login-subscription' class="card">
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
        Mot de passe perdu ? <a href="<?= \App\Core\Helpers::callRoute('forget_password')?>">Réinitialiser mon mot de passe</a>
    </div>
    <div>
        Vous n'avez pas de compte ? <a href="<?= \App\Core\Helpers::callRoute('subscription')?>">Rejoignez-nous !</a>
    </div>
</div>