<?php
use App\Models\Settings;
$appName = new Settings();
$appName = $appName->findOneBy('selector', 'appName')->getValue();
?>

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
        Mot de passe perdu ? <a class="linksColor" href="<?= \App\Core\Helpers::callRoute('forget_password')?>">Réinitialiser mon mot de passe</a>
    </div>
    <div>
        Vous n'avez pas de compte ? <a class="linksColor" href="<?= \App\Core\Helpers::callRoute('register')?>">Rejoignez-nous !</a>
    </div>
</div>