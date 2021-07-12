<?php

use App\Core\Helpers;

$noTemplateUrl = [
    Helpers::callRoute('login'),
    Helpers::callRoute('register'),
    Helpers::callRoute('forget_password')
];

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>FRONT</title>
    <meta name="description" content="description de la page de front">
    <script type="text/javascript" src="../../dist/main.js"></script>
    <script src="https://cdn.tiny.cloud/1/itne6ytngfhi89x71prh233w7ahp2mgfmc8vwnjxhvue2m6h/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <link rel="stylesheet" href="../../dist/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" integrity="sha512-HK5fgLBL+xu6dm/Ii3z4xhlSUyZgTT9tuc/hSrtw6uzJOvgRr2a9jyxxT1ely+B+xFAmJKVSTbpM/CuL7qxO8w==" crossorigin="anonymous" />
    <?php

    use App\Core\Request;

    if (isset($headScript) && !empty($headScript)) {
        echo "<script src='$headScript'></script>";
    } ?>
</head>

<body>
    <div class="container">

        <?php if (!in_array(Request::getURI(), $noTemplateUrl)) : ?>
            <?php include 'Views/components/navbar-front.php' ?>
        <?php endif; ?>

        <main class="main main-front">
            <div class="main-content">
                <?php
                    if (isset($flash)) $this->displayFlash($flash);
                    include $this->view;
                ?>
            </div>
        </main>
    </div>
    <?php if (isset($bodyScript) && !empty($bodyScript)) {
        echo "<script src='$bodyScript'></script>";
    } ?>
</body>

<script type="text/javascript" src="../../dist/main.js"></script>
</body>

</html>