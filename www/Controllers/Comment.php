<?php

namespace App\Controller;

use App\Core\Helpers;
use App\Core\FormValidator;
use App\Core\View;
use App\Models\Comment as CommentModel;
class Comment
{
    protected $columnsTable;

    public function __construct()
    {
        $this->columnsTable = [
            "author" => "Auteur",
            "updateAt" => "Créer le",
            "article" => "Liée à",
            "content" => "Contenu",
            "visible" => "Visibilité",
            "actions" => "Actions"
        ];
    }

    public function showAllAction() 
    {
        $comments = new CommentModel();
        $comments = $comments->select()->orderBy('updatedAt')->get();
        $view = new View("comments/list");
        $view->assign('title', 'Commentaires');
        $view->assign('columnsTable', $this->columnsTable);
        $view->assign('comments', $comments);
        $view->assign('bodyScripts', [PATH_TO_SCRIPTS . 'bodyScripts/comments/comments.js']);
    }

    public function defaultAction() {
		echo "Comment default";
	}

    public function deleteCommentAction() {
        if (!empty($_POST['id'])){ 
            $comment = new CommentModel();
            $id = $_POST['id'];
            $comment->setId($id);
            $comment->delete();
            
            Helpers::setFlashMessage('success', "Vous aviez bien supprimer cette utilisateur");
        }else{
            Helpers::setFlashMessage('error', "La suppression de l'utilisateur n'a pas abouti");
        }
    }
    
}