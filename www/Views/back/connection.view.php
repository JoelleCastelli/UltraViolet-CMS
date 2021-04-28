<div class="grid-connection-user">
    <?php if(isset($errors)):?>
        <?php foreach ($errors as $error):?>
            <li><?=$error?></li>
        <?php endforeach;?>
    <?php endif;?>
    <section class="grid-form-connection-user">

        <h1 class="title-form">Ultra Violet</h1>
        <section class="card">
            <h2 class="title-form-h2">Connexion à mon compte</h2>
            <article>
                <section class="container-form">
                    <?php App\Core\FormBuilder::render($form, true); ?>
                </section>

                <article class="links">
                    <p class="text">
                        <a href="#">Pas de compte ? Je m'inscris ! </a>
                    </p><br>
                    <p class="text">
                        <a href="#">Retour à l'acceuil </a>
                    </p>
                </article>
            </arcticle>

        </section>

</div>




