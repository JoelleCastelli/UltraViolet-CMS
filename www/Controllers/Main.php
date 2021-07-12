<?php

namespace App\Controller;

use App\Core\Helpers;
use App\Core\View;
use App\Models\Article;
use App\Models\Comment;
use App\Models\Production;

class Main
{

	public function defaultAction(){
		$view = new View("dashboard");
		$view->assign('title', 'Back office');
        $view->assignFlash();

        // Get last 3 articles
        $articles = new Article();
        $articles = $articles->select()->where('deletedAt', "NULL")->andWhere('publicationDate', 'NOT NULL')
            ->orderBy('publicationDate', 'DESC')->limit(3)->get();
        $latestArticles = [];
        foreach ($articles as $article) {
            $latestArticles[] = ['content' => $article, 'comments' => count($article->getComments())];
        }
        $view->assign('articles', $latestArticles);

        // Get last 3 comments
        $comments = new Comment();
        //$comments = $comments->select()->orderBy('createdAt', 'DESC')->limit(3)->get();
        $comments = [];
        $view->assign('comments', $comments);

        // Get last 3 productions
        $productions = new Production();
        $productions = $productions->select()->orderBy('createdAt', 'DESC')->limit(4)->get();
        foreach ($productions as $production) {
            if($production->getParentProductionId() != null) {
                $parentProduction = new Production();
                $parentProduction = $parentProduction->findOneBy('id', $production->getParentProductionId());
                $production->setParentProduction($parentProduction);
                if($parentProduction->getParentProductionId() != null) {
                    $grandParentProduction = new Production();
                    $grandParentProduction = $grandParentProduction->findOneBy('id', $parentProduction->getParentProductionId());
                    $parentProduction->setParentProduction($grandParentProduction);
                }
            }
            $production->setPoster(null);
        }
        $view->assign('productions', $productions);
	}

	public function getRouteAction()
	{
		echo json_encode(Helpers::callRoute($_POST['name']));
	}

	public function page404Action(){
		$view = new View("404", "front");
	}

	public function frontHomeAction(){
        $view = new View("home", "front");
    }

}