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
            "pseudo" => 'Pseudonyme',
            "creatAt" => 'Créer le',
            "article" => 'Liée à',
            "content" => 'Contenu',
            "visibility" => 'visibilité',
            "actions" => 'Actions'
        ];
    }

    public function showAllAction() {
        $view = new View("comments/list");
        $view->assign('title', 'Commentaires');
        $view->assign('columnsTable', $this->columnsTable);
        $view->assign('bodyScripts', [PATH_TO_SCRIPTS . 'bodyScripts/comements/comments.js']);
    }


    public function defaultAction() {
		echo "Comment default";
	}

    public function getCommentsAction() {
        
            $comments = new CommentModel();
            $comments = $comments->findAll();

            $commentsArray = [];
            foreach ($comments as $comment) {
                $commentsArray[] = [
                    $this->columnsTable['pseudo'] => $comment->getFullName(),
                    $this->columnsTable['creatAt'] => $comment->getPseudo(),
                    $this->columnsTable['article'] => $comment->getEmail(),
                    $this->columnsTable['content'] => $comment->isEmailConfirmed(),
                    $this->columnsTable['visibility'] => $comment->isEmailConfirmed(),
                    $this->columnsTable['actions'] => $comment->generateActionsMenu()
                ];
            }
            echo json_encode(["comments" => $commentsArray]);
        
    }
}