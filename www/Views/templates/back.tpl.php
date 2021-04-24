<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
    <title>UltraViolet - <?= $title ?? ' Back office'?></title>
	<meta name="description" content="description de la page de front">
	<link rel="stylesheet" href="../../dist/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" integrity="sha512-HK5fgLBL+xu6dm/Ii3z4xhlSUyZgTT9tuc/hSrtw6uzJOvgRr2a9jyxxT1ely+B+xFAmJKVSTbpM/CuL7qxO8w==" crossorigin="anonymous" />
    <?php if(isset($headScript) && !empty($headScript)) {
        echo "<script src='$headScript'></script>";
    }?>
    <script src="./../../dist/main.js"></script>

</head>
<body>

	

    <div class="container">
    
        <?php include 'Views/components/sidebar.php' ?>

        <main class="main">
            
            <?php include 'Views/components/header.php'; ?>

            <div class="main-content">

                <?php include $this->view ?>

            </div>

        </main>

    </div>

    <?php if(isset($bodyScript) && !empty($bodyScript)) {
        echo "<script src='$bodyScript'></script>";
    }?>



</body>
</html>