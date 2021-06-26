<div class="grid-add-page">

    <?php if (isset($errors)) : ?>
        <?php foreach ($errors as $error) : ?>
            <li><?= $error ?></li>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if (isset($response)) : ?>

        <?php if ($response['success']) : ?>

            <p class="success-message-form fadeOut">
                <i class="fas fa-times icon-message-form"></i>
                <?= $response['message'] ?>
            </p>

        <?php else : ?>

            <p class="error-message-form fadeOut">
                <i class="fas fa-check icon-message-form"></i>
                <?= $response['message'] ?>
            </p>

        <?php endif; ?>

    <?php endif; ?>

    <section class="grid-form-add-page">

        <section class="card">

            <article>
                <article class="container-form">
                    <?php App\Core\FormBuilder::render($form, $data); ?>
                </article><br>
            </article>
        </section>

    </section>
</div>
