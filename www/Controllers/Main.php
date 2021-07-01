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

        $articles = new Article();
        $articles = $articles->select()->where('deletedAt', null)->andWhere('publicationDate', date('Y-m-d H:i:s'), '<=')
            ->orderBy('publicationDate', 'DESC')->limit(3)->get();
        $view->assign('articles', $articles);

        $comments = new Comment();
        $comments = [];
        $view->assign('comments', $comments);

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


    public function testAction()
    {
        $file = 'people.txt';
        // Une nouvelle personne à ajouter
        $person = "Jean Dupond\n";
        // Ecrit le contenu dans le fichier, en utilisant le drapeau
        // FILE_APPEND pour rajouter à la suite du fichier et
        // LOCK_EX pour empêcher quiconque d'autre d'écrire dans le fichier
        // en même temps
        file_put_contents($file, $person, FILE_APPEND | LOCK_EX);
        echo "the end";
    }

	public function page404Action(){
		$view = new View("404", "front");
	}

	public function frontHomeAction(){
        $view = new View("home", "front");
    }

}