<?php

namespace App\Controller;

use App\Core\Helpers;
use App\Core\View;
use App\Models\Article;
use App\Models\Comment;
use App\Models\Production;
use App\Models\Category as CategoryModel;
use App\Models\CategoryArticle;

class Main
{

	public function defaultAction(){
		$view = new View("dashboard");
		$view->assign('title', 'Back office');
        $view->assignFlash();

        // Get last 3 articles
        $articles = $this->getLatestArticles(4);
        $view->assign('articles', $articles);

        // Get last 3 comments
        $comments = $this->getLatestComments(4);
        $view->assign('comments', $comments);


        // Get last 4 productions
        $productions = $this->getLatestProductions(4);
        $view->assign('productions', $productions);

        $view->assign('bodyScripts', [PATH_TO_SCRIPTS.'headScripts/dashboard.js']);
	}

	public function getLatestArticles($limit): array
    {
        $articles = new Article();
        return $articles->select()->where('deletedAt', "NULL")->andWhere('publicationDate', 'NOT NULL')
            ->orderBy('publicationDate', 'DESC')->limit($limit)->get();
    }

    public function getLatestComments($limit): array
    {
        $comments = new Comment();
        return $comments->select()->orderBy('createdAt', 'DESC')->limit($limit)->get();
    }

    public function getLatestProductions($limit): array
    {
        $productions = new Production();
        $productions = $productions->select()->orderBy('createdAt', 'DESC')->limit($limit)->get();
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
        return $productions;
    }

	public function getRouteAction()
	{
		echo json_encode(Helpers::callRoute($_POST['name']));
	}

	public function page404Action(){
		$view = new View("404", "front");
	}

	public function frontHomeAction(){

        $category = new CategoryModel;
        $categoryArticle = new CategoryArticle;
        $mainCategories = $category->select()->orderBy('position')->orderBy('name')->limit(5)->get();
        $otherCategories = $category->findAll();

        foreach ($mainCategories as $key => $mainCategorie) {
            $mainCategorie->getArticles();
        }
        foreach ($otherCategories as $key => $otherCategorie) {
            $otherCategorie->getArticles();
        }
        array_splice($otherCategories, 0, 5);

        Helpers::cleanDumpArray($mainCategories);
        Helpers::cleanDumpArray($otherCategories);

        die();

        $view = new View("home", "front");


    }

}