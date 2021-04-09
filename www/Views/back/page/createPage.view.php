<h2>Création d'une page</h2>

<?php if(isset($errors)):?>
	<?php foreach ($errors as $error):?>
		<li><?=$error?></li>
	<?php endforeach;?>
<?php endif;?>

<?php App\Core\FormBuilder::render($form, true); ?>