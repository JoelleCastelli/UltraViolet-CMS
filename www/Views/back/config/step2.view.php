<div id='installation' class="card">
    <div class="logo">
        <img src='<?= PATH_TO_IMG ?>logo_uv_transparent.png' alt='ultraviolet logo'>
    </div>
    <div class="details">Vous devez saisir ci-dessous les détails de connexion à votre base de données. Si vous ne les
        connaissez pas, contactez votre hébergeur.</div>
    <div class="error-message-form">
        <?php
        if(isset($errors)) {
            foreach ($errors as $error) {
                echo "<li>".$error."</li>";
            }
        }
        ?>
    </div>
    <?php App\Core\FormBuilder::render($form, true); ?>
</div>