<?php
    use App\Models\Settings;
?>

<!DOCTYPE html>
<html lang="fr">

    <head>
        <meta charset="UTF-8">
        <title><?= isset($title) && $title  != "" ? Settings::getAppName() . ' - ' . $title : Settings::getMetaTitle() ?></title>
        <meta name="description" content="<?= isset($description) && $description  != "" ? $description : Settings::getMetaDescription() ?>">
        <link rel="shortcut icon" href="<?= PATH_TO_IMG ?>logo/favicon.ico" />

        <!--JS-->
        <script src="<?= PATH_TO_DIST . 'main.js' ?>"></script>
        <!--CSS-->
        <link rel="stylesheet" href="/src/css/variables.css">
        <link rel="stylesheet" href="/src/css/main.css">
        <link rel="stylesheet" href="<?=PATH_TO_DIST.'main.css'?>">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" integrity="sha512-HK5fgLBL+xu6dm/Ii3z4xhlSUyZgTT9tuc/hSrtw6uzJOvgRr2a9jyxxT1ely+B+xFAmJKVSTbpM/CuL7qxO8w==" crossorigin="anonymous" />

        <?php
        if (isset($headScripts) && !empty($headScripts)) {
            foreach ($headScripts as $script) {
                echo "<script src='$script'></script>";
            }
        }
        ?>
    </head>

    <body>

        <?php include 'Views/components/navbar-front.php'; ?>
            
        <main class="main">
            <?php
                // If redirected after new email (logout + session destroy), display message from cookie
                if(isset($_COOKIE['new-mail'])) {
                    echo "<div class='flash flash-success'>".$_COOKIE['new-mail']."</div>";
                    setcookie("new-mail", "", time()-(60*60*24), "/");
                }
                if (isset($flash)) $this->displayFlash($flash);
                include $this->view;
            ?>
        </main>

        <?php include 'Views/components/footer-front.php'; ?>

        <?php if (isset($bodyScripts) && !empty($bodyScripts)) {
            foreach ($bodyScripts as $script) {
                echo "<script src='$script'></script>";
            }
        } ?>

    </body>
</html>