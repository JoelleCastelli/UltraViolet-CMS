<?php

namespace App\Controller;


use App\Core\View;
use App\Core\Helpers;
use App\Core\FormValidator;
use App\Core\Request;
use App\Models\Article as ArticleModel;
use App\Models\Media as MediaModel;
use App\Models\Category as CategoryModel;
use App\Models\Comment as CommentModel;
use App\Models\CategoryArticle as CategoryArticleModel;

class Article {

    public function showAllAction() {        
        $view = new View("articles/list");
        $view->assign('title', 'Articles');
        $view->assign('headScripts', [PATH_TO_SCRIPTS.'headScripts/articles/articles.js']);
    }

    public function createArticleAction() {
        $article = new ArticleModel();
        $media = new MediaModel();
        $form = $article->formBuilderCreateArticle();

        $view = new View("articles/createArticle");
        $view->assign("title", "Créer un article");
        $view->assign("form", $form);
        $view->assign('bodyScripts', [
            "tiny" => PATH_TO_SCRIPTS.'bodyScripts/tinymce.js',
            "articles" => PATH_TO_SCRIPTS.'bodyScripts/articles/articles.js',
            "media-modal" => PATH_TO_SCRIPTS.'bodyScripts/articles/media-pop-up.js',
        ]);
    
        if (!empty($_POST)) {

            $errors = FormValidator::check($form, $_POST);
            if ($article->hasDuplicateSlug($_POST["title"])) $errors[] = "Ce slug (titre adapté à l'URL) existe déjà. Veuillez changer votre titre d'article";
            $mediaId = $media->getMediaByTitle(htmlspecialchars($_POST["media"]));
            if ($mediaId === -1) $errors[] = "Le média n'existe pas. Veuillez en choisir qui existe déjà ou ajoutez-en un dans la section Media";

            if (empty($errors)) {

                $title = htmlspecialchars($_POST["title"]);
                $state = $_POST["state"];
                $user = Request::getUser();

                $article->setTitle($title);
                $article->setSlug(Helpers::slugify($title));
                $article->setDescription(htmlspecialchars($_POST["description"]));
                $article->setContent($_POST["content"]);
                $article->setMediaId($mediaId);
                $article->setPersonId($user->getId());

                if ($state == "published") {
                    $article->setToPublished();
                } else if ($state == "scheduled" && !empty($_POST["publicationDate"])) {
                    $article->setToScheduled($_POST["publicationDate"]);
                }

                $article->save();

                $articleId = $article->getLastInsertId();
                foreach ($_POST["categories"] as $categoryId) {
                    $categoryArticle = new CategoryArticleModel();
                    $categoryArticle->setArticleId($articleId);
                    $categoryArticle->setCategoryId(htmlspecialchars($categoryId));
                    $categoryArticle->save();
                }  

                Helpers::namedRedirect("articles_list");

            } else {
                $view->assign("errors", $errors);
            }
        }
    }

    public function updateArticleAction($id) {
        $article = new ArticleModel();
        $media = new MediaModel();

        $articleExist = $article->setId($id);
        if (!$articleExist) Helpers::redirect404();

        $view = new View("articles/updateArticle");
        $form = $article->formBuilderUpdateArticle($id);

        $view->assign('form', $form);
        $view->assign("data", $article->jsonSerialize());
        $view->assign("title", "Modifier un article");
        $view->assign('bodyScripts', [
            "tiny" => PATH_TO_SCRIPTS.'bodyScripts/tinymce.js',
            "articles" => PATH_TO_SCRIPTS.'bodyScripts/articles/articles.js',
            "media-modal" => PATH_TO_SCRIPTS.'bodyScripts/articles/media-pop-up.js',
        ]);

        if (!empty($_POST)) {

            $errors = FormValidator::check($form, $_POST);
            if ($article->hasDuplicateSlug($_POST["title"], $id)) $errors[] = "Ce slug (titre adapté à l'URL) existe déjà. Veuillez changer votre titre d'article";
            $mediaId = $media->getMediaByTitle(htmlspecialchars($_POST["media"]));
            if ($mediaId === -1) $errors[] = "Le média n'existe pas. Veuillez en choisir qui existe déjà ou ajoutez-en un dans la section Media";

            if (empty($errors)) {

                $title = htmlspecialchars($_POST["title"]);
                $state = $_POST["state"];
                $user = Request::getUser();

                $article->setTitle($title);
                $article->setSlug(Helpers::slugify($title));
                $article->setContent($_POST["content"]);
                $article->setMediaId($mediaId);
                $article->setPersonId($user->getId());

                if ($state == "published") {
                    $article->setToPublished();
                } else if ($state == "scheduled" && !empty($_POST["publicationDate"])) {
                    $article->setToScheduled($_POST["publicationDate"]);
                } else if ($state == "draft") {
                    $article->setToDraft();
                } else if ($state == "removed") {
                    $article->articleSoftDelete();
                }

                $article->save();

                $categoryArticle = new CategoryArticleModel();
                $entriesInDB = $categoryArticle->select("categoryId")->where("articleId", $id)->get(false);
                $categoriesInPost = $_POST["categories"];
                
                $entriesToRemove = array_diff($entriesInDB, $categoriesInPost);
                $entriesToAdd = array_diff($categoriesInPost, $entriesInDB);

                foreach($entriesToRemove as $entry) {
                    $categoryArticle->hardDelete()->where("articleId", $id)->andWhere("categoryId", $entry)->execute();
                }
        
                foreach($entriesToAdd as $entry) {
                    $newCategory = new CategoryArticleModel();
                    $newCategory->setArticleId($id);
                    $newCategory->setCategoryId($entry);
                    $newCategory->save();
                }

                Helpers::namedRedirect("articles_list");
            
            } else {
                $view->assign("errors", $errors);
            }
        }
    }

    // API methods
    public function getArticlesAction() {
        if (empty($_POST["state"])) return;
        $state = $_POST["state"];

        $article = new ArticleModel();
        $articles = $article->getArticlesBySate($state);

        $articlesArray = [];
        foreach ($articles as $article) {
            $articlesArray[] = [
                "Titre" => $article->getTitle(),
                "Slug" => $article->getSlug(),
                "Auteur" => $article->getPerson()->getPseudo(),
                // "Vues" => $article->getTotalViews(),
                "Vues" => "0",
                "Commentaire" => count($article->getComments()),
                "Date creation" => $article->getCleanCreatedAt(),
                "Date publication" => $article->getCleanPublicationDate(),
                "Actions" => $article->generateActionsMenu()
            ];
        }

        echo json_encode([
            "articles" => $articlesArray
        ]);
    }

    public function deleteArticleAction() {
        if (empty($_POST["id"])) return;

        $article = new ArticleModel();
        $article->setId($_POST["id"]);

        $article->getDeletedAt() ? $article->articleHardDelete() : $article->articleSoftDelete();
    }

    /***************** */
    /* FRONT FUNCTIONS */
    /***************** */

    public function showArticleAction($articleSlug) {
        $articleModel = new ArticleModel;
        $userId = Request::getUser()->getId();

        $article = $articleModel->select()
        ->where('slug', $articleSlug)
        ->andWhere('deletedAt', "NULL")
        ->andWhere('publicationDate', date("Y-m-d H:i:s"), "<=")->first(); 

        if(empty($article)) {
            Helpers::redirect404();
        }

        if (!empty($userId)) {
            $comment = new CommentModel;
            $form = $comment->createCommentForm($article->getSlug());

            if (!empty($_POST["comment"])) {
                $errors = FormValidator::check($form, $_POST);
                
                if (empty($errors)) {
                    $comment->setArticleId($article->getId());
                    $comment->setPersonId($userId);
                    $comment->setContent(htmlspecialchars($_POST["comment"]));
                    $comment->save();
                    Helpers::namedRedirect("display_article", ["article" => $articleSlug]);
                }
            }
        }

        $article->getCategoriesRelated();

        $view = new View('articles/article', 'front');
        $view->assign('title', $article->getTitle());
        $view->assign('description', $article->getDescription());
        $view->assign('article', $article);
        $view->assign('comments', $article->getComments());
        $view->assign('bodyScripts', [
            "new-comment" => PATH_TO_SCRIPTS.'bodyScripts/comments/newComments.js',
        ]);
        if (isset($form)) $view->assign("form", $form);

    }
}