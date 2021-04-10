<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<title>BACK</title>
	<meta name="description" content="description de la page de front">
	<link rel="stylesheet" href="../../dist/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" integrity="sha512-HK5fgLBL+xu6dm/Ii3z4xhlSUyZgTT9tuc/hSrtw6uzJOvgRr2a9jyxxT1ely+B+xFAmJKVSTbpM/CuL7qxO8w==" crossorigin="anonymous" />
    
    <?php include $_SERVER['DOCUMENT_ROOT'].'/Views/components/scriptHeader.php'; ?>

</head>
<body>

	

    <div class="container">
		
        <?php /*include $_SERVER['DOCUMENT_ROOT'].'/Views/components/sidebar.php'; */?>

        <main class="main">
            
            <?php include $_SERVER['DOCUMENT_ROOT'].'/Views/components/header.php'; ?>

            <div class="main-content">

                <?php include $this->view ?>

            </div>

        </main>

    </div>

    <?php include $_SERVER['DOCUMENT_ROOT'].'/Views/components/scriptBody.php'; ?>

</body>
</html>