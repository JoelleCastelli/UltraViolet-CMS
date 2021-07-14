<?php

namespace App\Controller;

use App\Core\Helpers;
use App\Core\View;
use App\Models\Article;
use App\Models\Comment;
use App\Models\Production;
use App\Models\Page;
use App\Models\Category;

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

        $nbArticles = $this->getNbArticles();
        Helpers::dd($nbArticles);
        $view->assign('nbArticles', $nbArticles);

        $nbComments = $this->getNbComments();
        Helpers::dd($nbComments);
        $view->assign('nbComments', $nbComments);

        $view->assign('bodyScripts', [PATH_TO_SCRIPTS.'headScripts/dashboard.js']);
	}

	public function getLatestArticles($limit): array
    {
        $articles = new Article();
        return $articles->select()->where('deletedAt', "NULL")->andWhere('publicationDate', 'NOT NULL')
            ->orderBy('publicationDate', 'DESC')->limit($limit)->get();
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
        $view = new View("home", "front");
    }

    public function generateSitemapAction() {
	    // Create Sitemap string
        $sitemap = "<?xml version='1.0' encoding='UTF-8'?>";
        $sitemap .= "<urlset xmlns='http://www.sitemaps.org/schemas/sitemap/0.9'>";

        $lastPublishedArticle = new Article();
        $lastPublishedArticle = $lastPublishedArticle->select()->where('deletedAt', 'NULL')
                             ->andWhere('publicationDate', 'NOT NULL')
                             ->andWhere("publicationDate", date('Y-m-d H:i:s'), "<=")
                             ->orderBy('publicationDate', 'DESC')->first();

        // Add homepage
        $sitemap .= "<url>
                <loc>".Helpers::getBaseUrl()."</loc>
                <lastmod>".date("Y-m-d", strtotime($lastPublishedArticle->getCreatedAt()))."</lastmod>
            </url>";

        // Add static pages URL
        $pages = new Page();
        $sitemap = $this->addItemsToSitemap($pages, $sitemap);
        // Add categories URL
        $categories = new Category();
        $sitemap = $this->addItemsToSitemap($categories, $sitemap);
        // Add articles URL
        $articles = new Article();
        $sitemap = $this->addItemsToSitemap($articles, $sitemap);

        $sitemap .= "</urlset>";

        $view = new View("sitemap", null);
        $view->assign('sitemap', $sitemap);
    }

    public function addItemsToSitemap(Object $objects, $sitemap) {
        $classPath = explode('\\', get_class($objects));
        $class = mb_strtolower(end($classPath));
        if($class == 'category')
            $objects = $objects->select()->where('position', 0, '=>')->get();
        else
            $objects = $objects->select()->where('deletedAt', 'NULL')
                                         ->andWhere('publicationDate', 'NOT NULL')
                                         ->andWhere("publicationDate", date('Y-m-d H:i:s'), "<=")
                                         ->get();

        foreach ($objects as $object) {
            if($class == 'category')
                $loc = Helpers::getBaseUrl().'/'.Helpers::slugify($object->getName());
            else
                $loc = Helpers::getBaseUrl().'/'.$object->getSlug();

            $lastUpdate = $object->getUpdatedAt() ?? $object->getCreatedAt();
            $sitemap .= '
            <url>
                <loc>'.$loc.'</loc>
                <lastmod>'.date("Y-m-d", strtotime($lastUpdate)).'</lastmod>
            </url>';
        }

        return $sitemap;
    }

}