<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<title>Template de front</title>
	<meta name="description" content="description de la page de front">
	<link rel="stylesheet" href="../../dist/main.css">
</head>
<body>
	<header>
		<h1>Template de front</h1>
	</header>

	<!-- afficher la vue -->
	<?php include $this->view ?>

	<script type="text/javascript" src="../../dist/main.js"></script>
</body>
</html>