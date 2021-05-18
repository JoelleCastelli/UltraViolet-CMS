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
        <?php if(isset($headScript) && !empty($headScript)) {
            echo "<script src='$headScript'></script>";
        }?>
    </head>

    <body>
        <div class="container">
            <main class="main">
                <div class="main-content">
                    <?php
                        if(\App\Core\Request::getUser()->isLogged()) {
                            echo "<a href='/deconnexion'>Déconnexion</a>";
                            if(\App\Core\Request::getUser()->canAccessBackOffice()) {
                                echo "<a href='/admin'>Administration</a>";
                            }
                        } else {
                            echo "<a href='/connexion'>Connexion</a><br>";
                            echo "<a href='/inscription'>Inscription</a><br><br>";
                        }
                        if(isset($flash)) $this->displayFlash($flash);
                        include $this->view;
                    ?>
                </div>
            </main>
        </div>
        <?php if(isset($bodyScript) && !empty($bodyScript)) {
            echo "<script src='$bodyScript'></script>";
        }?>
    </body>

</html>