<div class="grid-create-article">

    <?php if(isset($errors)):?>
        <?php foreach ($errors as $error):?>
            <li><?=$error?></li>
        <?php endforeach;?>
    <?php endif;?>

    <section>
            
        <?php App\Core\FormBuilder::render($form, true); ?>

    </section>

</div>