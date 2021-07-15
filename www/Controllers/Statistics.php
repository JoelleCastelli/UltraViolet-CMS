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

        $nbArticles = $this->getNbArticles();
        $view->assign('nbArticles', $nbArticles);

        $nbComments = $this->getNbComments();
        $view->assign('nbComments', $nbComments);

        $nbUsers = $this->getNbUsers();
        $view->assign('nbUsers', $nbUsers);

        $nbViews = $this->getNbViews();
        $view->assign('nbViews', $nbViews);

        $articleHistory = $this->getViewsArticles();
        Helpers::dd($articleHistory);
        $view->assign('articleHistory', $articleHistory);

        $view->assign('bodyScripts', [PATH_TO_SCRIPTS.'headScripts/dashboard.js']);
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