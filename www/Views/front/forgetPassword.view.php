<div class="grid-forget-password-user">
    <?php if (isset($errors)) {
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

    <section class="grid-form-forget-password-user">
        <h1 class="title-form">UltraViolet</h1>
        <section class="card-forget">
            <h3> Entrez l'email associé à votre compte utilisateur pour réinitialiser votre mot de passe</h3>
            <?php if (empty($send)) {
                echo "<section class=\"container-form\">";
                echo App\Core\FormBuilder::render($form, true);
                echo "</section>";
            } else {
                echo "Un e-mail de réinitialisation du mot de passe vous a été envoyé.";
            } ?>
        </section>
    </section>

</div>