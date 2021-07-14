<div id="grid-update-user">

    <?php

use App\Core\Helpers;

if (isset($errors)) {
        echo "<div class='error-message-form'>";
        foreach ($errors as $error) {
            if (count($errors) == 1)
                echo "$error";
            else
                echo "<li>$error</li>";
        }
        echo "</div>";
    }
    ?>

    <section class="grid-form-update-user">

        <section class="card">
            <article>
                <article class="container-form">
                    <?php App\Core\FormBuilder::render($form); ?>
                </article><br>
            </article>
        </section>
        <a href="<?= Helpers::callRoute('user_delete') ?>" id="suppress-my-account" class="error-message-form">Supprimer mon compte</a>
    </section>
</div>