<div id="userSettings" class="card">
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

        echo "<h1>".$title."</h1>";

        App\Core\FormBuilder::render($form);
    ?>

    <a id='delete-account' href="<?= Helpers::callRoute('user_delete') ?>" class="btn danger">Supprimer mon compte</a>
</div>