<h2>S'inscrire</h2>

<?php if(isset($errors)):?>
	<?php foreach ($errors as $error):?>
		<li><?=$error?></li>
	<?php endforeach;?>
<?php endif;?>


<?php App\Core\FormBuilder::render($form); ?>

<h2>Se connecter</h2>

<?php App\Core\FormBuilder::render($formLogin); ?>