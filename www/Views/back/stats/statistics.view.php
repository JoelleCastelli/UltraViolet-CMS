<?php
use App\Core\Helpers;
use App\Core\Request;
if(isset($errors)) {
    foreach ($errors as $error) {
        echo "<li>$error</li>";
    }
}
?>

<div id="statistics">

    <div class="card">
        <div class="cardTitle">Statisque du jour</div>
        <div id="statToDay">
            <div id='statToDayList'>
                <div class='statToDayCard'>
                    <span class="title">
                        Total d'articles
                    </span>
                    <span class="stat">
                        <?= $nbToDayArticles ?>
                    </span>
                </div>
                <div class='statisticsCard'>
                    <span class="title">
                        Total de commentaires
                    </span>
                    <span class="stat">
                        <?= $nbToDayComments ?>
                    </span>
                </div>
                <div class='statisticsCard'>
                    <span class="title">
                        Total d'utilisateurs
                    </span>
                    <span class="stat">
                        <?= $nbToDayUsers ?>
                    </span>
                </div>
                <div class='statisticsCard'>
                    <span class="title">
                        Total de vues
                    </span>
                    <span class="stat">
                        <?= $nbToDayViews ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="cardTitle">Statistiques</div>
        <div id="statistics">
            <div id='statisticsList'>
                <div class='statisticsCard'>
                    <span class="title">
                        Total d'articles
                    </span>
                    <span class="stat">
                        <?= $nbArticles ?>
                    </span>
                </div>
                <div class='statisticsCard'>
                    <span class="title">
                        Total de commentaires
                    </span>
                    <span class="stat">
                        <?= $nbComments ?>
                    </span>
                </div>
                <div class='statisticsCard'>
                    <span class="title">
                        Total d'utilisateurs
                    </span>
                    <span class="stat">
                        <?= $nbUsers ?>
                    </span>
                </div>
                <div class='statisticsCard'>
                    <span class="title">
                        Total de vues
                    </span>
                    <span class="stat">
                        <?= $nbViews ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>