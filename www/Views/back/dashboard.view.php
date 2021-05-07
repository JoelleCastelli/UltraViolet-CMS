<?php if(isset($errors)):?>
    <?php foreach ($errors as $error):?>
        <li><?=$error?></li>
    <?php endforeach;?>
<?php endif;?>