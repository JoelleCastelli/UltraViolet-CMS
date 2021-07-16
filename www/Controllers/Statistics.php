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

        $view->assign('bodyScripts', [PATH_TO_SCRIPTS.'headScripts/dashboard.js']);
	}
 
    public function getNbToDayArticles()
    {
        $articles = new Article();
        $dateNow = date('Y-m-d');
        return $articles->customQuery('SELECT count(uvtr_article.id) FROM uvtr_article 
            WHERE cast(uvtr_article.publicationDate as date) = cast(Now() as date) 
            and uvtr_article.deletedAt IS NULL')->first(false);
    }

    public function getNbToDayComments()
    {
        $comments = new Comment();
        return $comments->customQuery('SELECT count(uvtr_comment.id) FROM uvtr_comment 
            WHERE cast(uvtr_comment.createdAt as date) = cast(Now() as date) 
            and uvtr_comment.deletedAt IS NULL')->first(false);
    }

    public function getNbToDayUsers()
    {
        $persons = new Person();
        return $persons->customQuery('SELECT count(uvtr_person.id) FROM uvtr_person 
            WHERE cast(uvtr_person.createdAt as date) = cast(Now() as date) 
            and uvtr_person.deletedAt IS NULL 
            and uvtr_person.role != "vip"')->first(false);
    }

    public function getNbToDayViews()
    {
        $articleHistory = new ArticleHistory();
        return $articleHistory->customQuery('SELECT SUM(uvtr_article_history.views) FROM uvtr_article_history 
            WHERE cast(uvtr_article_history.date as date) = cast(Now() as date)')->first(false);
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

}