<h2>S'inscrire</h2>
<br>
<h2>POST</h2>
<?php
echo "<pre>";
print_r($post);
echo "</pre>";
?>
<br>

<?php if(isset($errors)):?>
	<?php foreach ($errors as $error):?>
		<li><?=$error?></li>
	<?php endforeach;?>
<?php endif;?>


<?php App\Core\FormBuilder::render($form, true); ?>

<h2>Se connecter</h2>

<?php App\Core\FormBuilder::render($formLogin); ?>