<?php

namespace App\Controller;

use App\Core\Helpers;
use App\Core\View;
use App\Models\Comment as CommentModel;
class Comment
{
    protected $columnsTable;

    public function __construct()
    {
        $this->columnsTable = [
            "pseudo" => "Auteur",
            "creatAt" => "Créer le",
            "article" => "Liée à",
            "content" => "Contenu",
            "visibility" => "Visibilité",
            "actions" => "Actions"
        ];
    }

    public function showAllAction() {
        $comments = new CommentModel();
        $comments = $comments->select()->orderBy('person')->get();
        $view = new View("comments/list");
        $view->assign('title', 'Commentaires');
        $view->assign('columnsTable', $this->columnsTable);
        $view->assign('comments', $comments);
        $view->assign('bodyScripts', [PATH_TO_SCRIPTS . 'bodyScripts/comements/comments.js']);
    }


    public function defaultAction() {
		echo "Comment default";
	}

    
}