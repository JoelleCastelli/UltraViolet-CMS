<div class="grid-connection-user">
    <?php if(isset($errors)): ?>
        <?php foreach ($errors as $error): ?>
            <li><?= $error ?></li>
        <?php endforeach;?>
    <?php endif;?>

    <section class="grid-form-connection-user">
        <h1 class="title-form">UltraViolet</h1>
        <section class="card">
            <section class="container-form">
                <?php App\Core\FormBuilder::render($form, true); ?>
            </section>
            <a href="<?= \App\Core\Helpers::callRoute('forget_password') ?>">
            Mots de passe oublié ?
            </a>
        </section>
    </section>

</div>