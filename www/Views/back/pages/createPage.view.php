<div class="grid-add-page">
    <?php if(isset($errors)):?>
        <?php foreach ($errors as $error):?>
            <li><?=$error?></li>
        <?php endforeach;?>
    <?php endif;?>

    <section class="grid-form-add-page">

        <section class="card">

            <article>
                <article class="container-form">
                    <?php App\Core\FormBuilder::render($form); ?>
                </article><br>
            </article>
        </section>

    </section>

</div>