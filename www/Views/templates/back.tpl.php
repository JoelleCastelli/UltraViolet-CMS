<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>UltraViolet - <?= $title ?? ' Back office'?></title>
        <meta name="description" content="UltraViolet - Back office">
        <script src="./../../dist/main.js"></script>
        <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
        <!--<script type="text/javascript" charset="utf8" src=""></script>-->
        <link rel="stylesheet" href="../../dist/main.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" integrity="sha512-HK5fgLBL+xu6dm/Ii3z4xhlSUyZgTT9tuc/hSrtw6uzJOvgRr2a9jyxxT1ely+B+xFAmJKVSTbpM/CuL7qxO8w==" crossorigin="anonymous" />
        <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
        <?php if(isset($headScript) && !empty($headScript)) {
            echo "<script src='$headScript'></script>";
        }?>
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