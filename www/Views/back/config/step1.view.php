<div id='installation' class="card">
    <div class="logo">
        <img src='<?= PATH_TO_IMG ?>logo_uv_transparent.png' alt='ultraviolet logo'>
    </div>
    <h1 class="title-form">UltraViolet</h1>
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