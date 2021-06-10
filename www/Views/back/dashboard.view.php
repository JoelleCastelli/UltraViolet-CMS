<?php if(isset($errors)):?>
    <?php foreach ($errors as $error):?>
        <li><?=$error?></li>
    <?php endforeach;?>
<?php endif;?>

Exemples de liens avec la fonction callRoute :<br>
<br>
Sans paramètres :<br>
<a href="<?php echo \App\Core\Helpers::callRoute('comments_list') ?>">Lien vers les commentaires</a>
<br>
Avec paramètres :<br>
<a href="<?php echo \App\Core\Helpers::callRoute('production_update', ['id' => 53, 'slug' => 'coucou']) ?>">Modification de la production ID 53</a>