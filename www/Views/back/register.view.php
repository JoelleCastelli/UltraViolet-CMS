<div class="grid-register-user">
    <?php if(isset($errors)):?>
        <?php foreach ($errors as $error):?>
            <li><?=$error?></li>
        <?php endforeach;?>
    <?php endif;?>

    <section class="grid-form-register-user">

        <h1 class="title">Ultra Violet</h1>
        <section class="card">

            <article>
                <h2 class="title-form">Créer mon compte</h2>

                <article class="container-form">
                    <?php App\Core\FormBuilder::render($form, true); ?>
                </article><br>

                <article class="links">
                    <p class="text">
                        <a href="#">Déjà un compte ? Je me connecte </a>
                    </p><br>
                    <p class="text">
                        <a href="#">Retour à l'acceuil </a>
                    </p>
                </article>

            </article>
        </section>

    </section>

</div>




