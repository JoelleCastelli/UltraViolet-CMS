<div id='installation' class="card">
    <div class="logo">
        <img src='<?= PATH_TO_IMG ?>logo_uv_transparent.png' alt='ultraviolet logo'>
    </div>
    <div class="details">
        <p>
            La base de données a été créée ! Pour terminer l'installation, veuillez renseigner les informations suivantes.
            Ne vous inquiétez pas, vous pourrez les modifier plus tard.
        </p>
    </div>
    <div class="error-message-form">
        <?php
        if(isset($errors)) {
            echo "<div class='error-message-form'>";
            foreach ($errors as $error) {
                if(count($errors) == 1)
                    echo "$error";
                else
                    echo "<li>$error</li>";
            }
            echo "</div>";
        }
        ?>
    </div>
    <?php App\Core\FormBuilder::render($form, true); ?>
</div>