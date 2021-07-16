<?php
use App\Models\Settings;
$appName = new Settings();
$appName = $appName->findOneBy('selector', 'appName')->getValue();
?>
<h1 class="title-form"><?= $appName ?></h1>
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
        Déjà un compte ? <a href="<?= \App\Core\Helpers::callRoute('login')?>">Je me connecte.</a>
    </div>
</div>




