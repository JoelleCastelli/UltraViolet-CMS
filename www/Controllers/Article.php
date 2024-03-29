<?php

namespace App\Controller;


use App\Core\View;
use App\Core\Helpers;
use App\Core\FormValidator;
use App\Core\Request;
use App\Models\Article as ArticleModel;
use App\Models\ArticleHistory;
use App\Models\Media as MediaModel;
use App\Models\Comment as CommentModel;
use App\Models\Production as ProductionModel;
use App\Models\CategoryArticle as CategoryArticleModel;
use App\Models\ProductionArticle as ProductionArticleModel;

class Article {

    public function showAllAction() {        
        $view = new View("articles/list");
        $view->assign('title', 'Articles');
        $view->assign('headScripts', [PATH_TO_SCRIPTS.'bodyScripts/articles/list.js']);
    }

    public function createArticleAction() {
        $article = new ArticleModel();
        $media = new MediaModel();
        $production = new ProductionModel();
        $form = $article->formBuilderCreateArticle();

        $view = new View("articles/createArticle");
        $view->assign("title", "Créer un article");
        $view->assign("form", $form);
        $view->assign('bodyScripts', [
            "tiny" => PATH_TO_SCRIPTS.'bodyScripts/tinymce.js',
            "articles" => PATH_TO_SCRIPTS.'bodyScripts/articles/articles.js',
            "media-modal" => PATH_TO_SCRIPTS.'bodyScripts/articles/media-pop-up.js',
            "media-production" => PATH_TO_SCRIPTS.'bodyScripts/articles/production-pop-up.js',
        ]);
    

        if (!empty($_POST)) {

            // set categories if not checked by the user
            if (!isset($_POST['categories']))
                $_POST['categories'] = [];
                
            $errors = FormValidator::check($form, $_POST);

            if ($article->hasDuplicateSlug($_POST["title"])) $errors[] = "Ce titre existe déjà. Veuillez changer votre titre d'article";


            // Verification Media
            if (!empty($_POST['media'])) {
                // get id between parenthesis
                $matches = preg_split("/[\(\)]/", $_POST["media"], -1, PREG_SPLIT_NO_EMPTY);
                $id = $matches[count($matches) - 1];
                $mediaId = $media->select("id")->where("id", htmlspecialchars($id))->first(false);
                if ($mediaId === -1) $errors[] = "Le média n'existe pas. Veuillez en choisir qui existe déjà ou ajoutez-en un dans la section Media";
            }
      
            if(!empty($_POST["production"])){
                // get id between parenthesis
                $matches = preg_split("/[\(\)]/", $_POST["production"], -1, PREG_SPLIT_NO_EMPTY);
                $id = $matches[count($matches) - 1];

                $productionId = $production->select("id")->where("id", htmlspecialchars($id))->first(false);
                if (empty($productionId)) $errors[] = "Cette production n'existe pas. Veuillez en choisir une autre ou en ajouter une vous-même dans la section correspondante";
            }
            if (empty($errors)) {

                $title = htmlspecialchars($_POST["title"]);
                $state = $_POST["state"];
                $user = Request::getUser();

                $article->setTitle($title);
                $article->setSlug(Helpers::slugify($title));
                $article->setDescription(htmlspecialchars($_POST["description"]));
                $article->setContent($_POST["content"]);
                if (!empty($mediaId))
                    $article->setMediaId($mediaId);
                else 
                    $article->setDefaultPicture();
                $article->setPersonId($user->getId());

                if ($state == "published") {
                    $article->setToPublished();
                } else if ($state == "scheduled" && !empty($_POST["publicationDate"])) {
                    $article->setToScheduled($_POST["publicationDate"]);
                }

                $article->save();
                $articleId = $article->getLastInsertId();

                // if categories associated, add association
                foreach ($_POST["categories"] as $categoryId) {
                    $categoryArticle = new CategoryArticleModel();
                    $categoryArticle->setArticleId($articleId);
                    $categoryArticle->setCategoryId(htmlspecialchars($categoryId));
                    $categoryArticle->save();
                }

                // if productions associated, add association
                if (!empty($productionId)){
                    $productionArticleModel = new ProductionArticleModel();
                    $productionArticleModel->setArticleId($articleId);
                    $productionArticleModel->setProductionId($productionId);
                    $productionArticleModel->save();
                }

                Helpers::setFlashMessage('success', "L'article a bien été enregisté");
                Helpers::namedRedirect("articles_list");

            } else {
                $view->assign("errors", $errors);
            }
        }
    }

    public function updateArticleAction($id) {
        $article = new ArticleModel();
        $media = new MediaModel();
        $production = new ProductionModel();

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
            "media-production" => PATH_TO_SCRIPTS.'bodyScripts/articles/production-pop-up.js',
        ]);

        if (!empty($_POST)) {

            if(!isset($_POST['productionRemove'])) { $_POST['productionRemove'] = ''; }
            if(!isset($_POST['mediaRemove'])) { $_POST['mediaRemove'] = ''; }
            $errors = FormValidator::check($form, $_POST);

            if ($article->hasDuplicateSlug($_POST["title"], $id)) $errors[] = "Ce slug (titre adapté à l'URL) existe déjà. Veuillez changer votre titre d'article";
            
            // Verification Media
            if(!empty($_POST['media'])){
                // get id between parenthesis

                $matches = preg_split("/[\(\)]/", $_POST["media"], -1, PREG_SPLIT_NO_EMPTY);
                $mediaId = $matches[count($matches) - 1];
                $mediaId = $media->select("id")->where("id", htmlspecialchars($mediaId))->first(false);
                if ($mediaId === -1) $errors[] = "Le média n'existe pas. Veuillez en choisir qui existe déjà ou ajoutez-en un dans la section Media";
            }

            // Verification Production
            if (!empty($_POST["production"])) {
                // get id between parenthesis
                $matches = preg_split("/[\(\)]/", $_POST["production"], -1, PREG_SPLIT_NO_EMPTY);
                $productionId = $matches[count($matches) - 1];
                $productionId = $production->select("id")->where("id", htmlspecialchars($productionId))->first(false);
                if (empty($productionId)) $errors[] = "Cette production n'existe pas. Veuillez en choisir une autre ou en ajouter une vous-même dans la section correspondante";
            }

            if (empty($errors)) {

                $title = htmlspecialchars($_POST["title"]);
                $state = $_POST["state"];
                $user = Request::getUser();

                $article->setTitle($title);
                $article->setSlug(Helpers::slugify($title));
                $article->setContent($_POST["content"]);

                if(!empty($mediaId))
                    $article->setMediaId($mediaId);
                else
                    $article->setDefaultPicture();

                $article->setPersonId($user->getId());
                $article->setContentUpdatedAt(date("Y-m-d H:i:s"));

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

                // Save categories
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

                // Save production
                if (!empty($productionId)) {
                    $existingProductionArticle = new ProductionArticleModel();
                    $existingProductionArticle = $existingProductionArticle->select()->where("articleId", $id)->first();
                    if($existingProductionArticle) {
                        $existingProductionArticle->setArticleId($id);
                        $existingProductionArticle->setProductionId($productionId);
                        $existingProductionArticle->save();
                    } else {
                        $productionArticle = new ProductionArticleModel();
                        $productionArticle->setArticleId($id);
                        $productionArticle->setProductionId($productionId);
                        $productionArticle->save();
                    }
                }else {

                    // remove existing related productions if exists
                    $existingProductionArticle = new ProductionArticleModel();
                    $existingProductionArticle = $existingProductionArticle->select()->where("articleId", $id)->first();

                    if(!empty($existingProductionArticle))
                        $existingProductionArticle->hardDelete()->where('articleId', $existingProductionArticle->getArticleId())->execute();
                }
                Helpers::setFlashMessage('success', "L'article a bien été mis à jour");
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
                "Vues" => $article->getTotalViews(),
                "Commentaire" => count($article->getComments()),
                "Création" => $article->getCleanCreatedAt(),
                "Publication" => $article->getCleanPublicationDate(),
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
        if(!empty($article->findOneBy('id', $_POST["id"]))){

            $article->setId($_POST["id"]);
            $article->getDeletedAt() ? $article->articleHardDelete() : $article->articleSoftDelete();

            $response['success'] = true;
            $response['id'] = $_POST["id"];

        }else{
            $response['success'] = false;
        }

        echo json_encode($response);
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
        if(!empty($article->getProductionsRelated()))
            $production = $article->getProductionsRelated()[0];

        $history = $this->incrementViewOnArticle($article);

        $view = new View('articles/article', 'front');

        if(!empty($production)){
            $view->assign('production', $production);
            $view->assign('actors', $production->getRelatedActors());
            $view->assign('directors', $production->getRelatedDirectors());
            $view->assign('writers', $production->getRelatedWriters());
            $view->assign('creators', $production->getRelatedCreators());
        }

        $view->assign('title', $article->getTitle());
        $view->assign('description', $article->getDescription());
        $view->assign('article', $article);
        $view->assign('comments', $article->getComments());
        $view->assign('bodyScripts', [
            "new-comment" => PATH_TO_SCRIPTS.'bodyScripts/comments/newComments.js',
            "production-details" => PATH_TO_SCRIPTS.'bodyScripts/articles/production-details.js',
        ]);

        if (isset($form)) $view->assign("form", $form);

    }

    public function incrementViewOnArticle($article) {
        $articleHistory = new ArticleHistory;

        $history = $articleHistory->customQuery('SELECT * FROM '.DBPREFIXE.'article_history 
        WHERE '.DBPREFIXE.'article_history.articleId = '.$article->getId().' AND cast('.DBPREFIXE.'article_history.date as date) = cast(Now() as date)')->first();
        if($history == false){
            $articleHistory->setViews(1);
            $articleHistory->setDate(date('Y-m-d H:i:s'));
            $articleHistory->setArticleId($article->getId());
            $articleHistory->save();
        } else {
            $articleHistory->setID($history->getId());
            $articleHistory->setViews($history->getViews() + 1);
            $articleHistory->save();
        }
    }
}