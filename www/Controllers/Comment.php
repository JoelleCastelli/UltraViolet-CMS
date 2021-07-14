<?php

namespace App\Controller;

use App\Core\Helpers;
use App\Core\View;
use App\Models\Comment as CommentModel;

class Comment
{
    protected array $columnsTable;

    public function __construct()
    {
        $this->columnsTable = [
            "author" => "Auteur",
            "article" => "Article",
            "content" => "Contenu",
            "createdAt" => "Créé le",
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

    /**
     * Called by AJAX script to display productions filtered by type
     */
    public function getCommentsAction() {

        if(!empty($_POST['commentState'])) {

            $comments = new CommentModel();

            if($_POST['commentState'] == 'visible') {
                $comments = $comments->select()->where('deletedAt', "NULL")->orderBy('createdAt', 'DESC')->get();
            } else {
                $comments = $comments->select()->where('deletedAt', "NOT NULL")->orderBy('createdAt', 'DESC')->get();
            }
            

            if(!$comments) $comments = [];

            $commentsArray = [];
            foreach ($comments as $comment) {

                if( $comment->getDeletedAt() ) {
                    $comment->setActions($comment->getActionsDeletedComment());
                    $actions = $comment->generateActionsMenu();
                }else{
                    $actions = $comment->generateActionsMenu();
                }
                
                $commentsArray[] = [
                    $this->columnsTable['author'] => $comment->getPerson()->getPseudo(),
                    $this->columnsTable['article'] => $comment->getArticle()->getTitle(),
                    $this->columnsTable['content'] => $comment->getContent(),
                    $this->columnsTable['createdAt'] => $comment->getCleanCreationDate(),
                    $this->columnsTable['actions'] => $actions,
                ];
            }
            echo json_encode($commentsArray);
        }
    }


    public function updateCommentStateAction(){

        if (!empty($_POST['id'])){

            $comment = new CommentModel;
            $id = $_POST['id'];
            $comment->setId($id);

            if ($comment->getDeletedAt()){
                $comment->setDeletedAt(null);
                $comment->save();
                Helpers::setFlashMessage('succes', "Votre commentaire a été restaurer");
            }   
            else
            {
                Helpers::setFlashMessage('error', "Votre commentaire n'est pas trouvable ");
            } 
        }
    }

    public function deleteCommentAction() {
        if (!empty($_POST['id'])) {
            $response = [];
            $comment = new CommentModel();
            $comment->setId($_POST['id']);
        
            if($comment->delete()) {
                $response['success'] = true;
                $response['message'] = 'Le commentaire a bien été supprimé';
            } else {
                $response['success'] = false;
                $response['message'] = 'Le commentaire n\'a pas pu être supprimé';
            }
            echo json_encode($response);
        }
    }
    
}