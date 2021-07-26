<?php

namespace App\Controller;

use App\Core\Helpers;
use App\Core\View;
use App\Models\Article;
use App\Models\ArticleHistory;
use App\Models\Comment;
use App\Models\Person;

class Statistics
{
	public function defaultAction(){
		$view = new View("stats/statistics");
        $view->assign('title', 'Statistiques');
        $view->assignFlash();

        $nbToDayArticles = $this->getNbToDayArticles();
        $view->assign('nbToDayArticles', $nbToDayArticles);

        $nbToDayComments = $this->getNbToDayComments();
        $view->assign('nbToDayComments', $nbToDayComments);

        $nbToDayUsers = $this->getNbToDayUsers();
        $view->assign('nbToDayUsers', $nbToDayUsers);

        $nbToDayViews = $this->getNbToDayViews();
        $view->assign('nbToDayViews', $nbToDayViews);

        $nbArticles = $this->getNbArticles();
        $view->assign('nbArticles', $nbArticles);

        $nbComments = $this->getNbComments();
        $view->assign('nbComments', $nbComments);

        $nbUsers = $this->getNbUsers();
        $view->assign('nbUsers', $nbUsers);

        $nbViews = $this->getNbViews();
        $view->assign('nbViews', $nbViews);

        $articleHistory = $this->getViewsArticles();
        $view->assign('articleHistory', $articleHistory);

        $viewsChart = $this->getViewsGraphAction();
        $view->assign('viewsChart', $viewsChart);

        $view->assign('bodyScripts', [PATH_TO_SCRIPTS.'bodyScripts/statistics/chart.js']);

        Helpers::dd($viewsChart);
	}
 
    public function getNbToDayArticles()
    {
        $articles = new Article();
        $dateNow = date('Y-m-d');
        return $articles->customQuery('SELECT count('.DBPREFIXE.'article.id) FROM '.DBPREFIXE.'article 
            WHERE cast('.DBPREFIXE.'article.publicationDate as date) = cast(Now() as date) 
            and '.DBPREFIXE.'article.deletedAt IS NULL')->first(false);
    }

    public function getNbToDayComments()
    {
        $comments = new Comment();
        return $comments->customQuery('SELECT count('.DBPREFIXE.'comment.id) FROM '.DBPREFIXE.'comment 
            WHERE cast('.DBPREFIXE.'comment.createdAt as date) = cast(Now() as date) 
            and '.DBPREFIXE.'comment.deletedAt IS NULL')->first(false);
    }

    public function getNbToDayUsers()
    {
        $persons = new Person();
        return $persons->customQuery('SELECT count('.DBPREFIXE.'person.id) FROM '.DBPREFIXE.'person 
            WHERE cast('.DBPREFIXE.'person.createdAt as date) = cast(Now() as date) 
            and '.DBPREFIXE.'person.deletedAt IS NULL 
            and '.DBPREFIXE.'person.role != "vip"')->first(false);
    }

    public function getNbToDayViews()
    {
        $articleHistory = new ArticleHistory();
        return $articleHistory->customQuery('SELECT SUM('.DBPREFIXE.'article_history.views) FROM '.DBPREFIXE.'article_history 
            WHERE cast('.DBPREFIXE.'article_history.date as date) = cast(Now() as date)')->first(false);
    }

    public function getNbArticles()
    {
        $articles = new Article();
        return $articles->count('id')->where('deletedAt', "NULL")->andWhere('publicationDate', 'NOT NULL')
            ->orderBy('publicationDate', 'DESC')->first(false);
    }

    public function getNbComments()
    {
        $comments = new Comment();
        return $comments->count('id')->where('deletedAt', "NULL")->first(false);
    }

    public function getNbUsers()
    {
        $persons = new Person();
        return $persons->count('id')->where('deletedAt', "NULL")->andWhere( 'role' , 'vip', '!=')->first(false);
    }

    public function getNbViews()
    {
        $articleHistory = new ArticleHistory();
        return $articleHistory->sum('views')->first(false);
    }

    public function getViewsArticles()
    {
        $articleHistory = new ArticleHistory();
        return $articleHistory->select()->orderBy('views', 'DESC')->get();
    }

    public function getViewsGraphAction()
    {
        $articleHistory = new ArticleHistory();
        $formatResult = [
            "labels" => [],
            "data" => []
        ];

        $views = $articleHistory->customQuery('SELECT SUM('.DBPREFIXE.'article_history.views) as total, '.DBPREFIXE.'article_history.date as date
            FROM '.DBPREFIXE.'article_history 
            WHERE DATE('.DBPREFIXE.'article_history.date) >= DATE(NOW()) - INTERVAL 30 DAY 
            GROUP BY '.DBPREFIXE.'article_history.date')->get(false);

        foreach ($views as $key => $view) {
            array_push($formatResult["labels"], $view["date"]);
            array_push($formatResult["data"], $view["total"]);
        }

        return json_encode($formatResult);
    }

}