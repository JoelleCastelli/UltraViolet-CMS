<?php

use App\Core\Helpers;
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>UltraViolet - <?= $title ?? ' Back office'?></title>
    <meta name="description" content="UltraViolet - Back office">
    <meta name="robots" content="noindex">

    <!--JS-->
    <script src="<?=PATH_TO_DIST.'main.js'?>"></script>

    <script>
    var routeJS = "";

    /* CALL PHP FUNCTION CallRoute  */
    function callRoute(path) {

        jQuery.ajax({
            type: "POST",
            url: "<?= Helpers::callRoute('routes') ?>",
            dataType: 'json',
            async: false,
            data: {
                name: path
            },

            success: function(obj, textstatus) {
                routeJS = obj;
            },
            error: function(obj, textstatus) {
                routeJS = "";
            }
        });

        return routeJS;
    }
    </script>

    <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js">
    </script>
    <?php if(isset($headScripts) && !empty($headScripts)) {
            foreach ($headScripts as $script) {
                echo "<script src='$script'></script>";
            }
        }?>

    <!--CSS-->
    <link rel="stylesheet" href="<?=PATH_TO_DIST.'main.css'?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"
        integrity="sha512-HK5fgLBL+xu6dm/Ii3z4xhlSUyZgTT9tuc/hSrtw6uzJOvgRr2a9jyxxT1ely+B+xFAmJKVSTbpM/CuL7qxO8w=="
        crossorigin="anonymous" />
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
</head>

<body class="preload">
    <?php include 'Views/components/sidebar.php' ?>

    <main id="main">
        <?php include 'Views/components/header.php'; ?>
        <div class="main-content">
            <?php
                    if(isset($flash)) $this->displayFlash($flash);
                    include $this->view;
                ?>
        </div>
    </main>

    <?php if(isset($bodyScripts) && !empty($bodyScripts)) {
            foreach ($bodyScripts as $script) {
                echo "<script src='$script'></script>";
            }
        }?>
</body>

</html>