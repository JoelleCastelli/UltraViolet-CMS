<div class="grid-update-page">

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
            <?php foreach ($response['message'] as $value) :?>

                <p class="error-message-form fadeOut">
                    <i class="fas fa-check icon-message-form"></i>
                    <?= $value ?>
                </p>

            <?php endforeach; ?>
        <?php endif; ?>

    <?php endif; ?>

    <section class="grid-form-update-page">

        <section class="card">

            <article>
                <article class="container-form">
                    <?php App\Core\FormBuilder::render($form, $data); ?>
                </article><br>
            </article>
        </section>

    </section>
</div>
