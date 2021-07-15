<div class="grid-forget-password-user">
    <?php if(isset($errors)): ?>
        <?php foreach ($errors as $error): ?>
            <li><?= $error ?></li>
        <?php endforeach;?>
    <?php endif;?>

    <section class="grid-form-forget-password-user">
        <h1 class="title-form">UltraViolet</h1>
        <section class="card-forget">
            <?php if(empty($send)){
                echo "<section class=\"container-form\">";
                echo App\Core\FormBuilder::render($form, true);
                echo "</section>";
            } else {
                echo "Un e-mail de changement de mot de passe, vous a été envoyé.";
            }?>
        </section>
    </section>

</div>