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
        $view = new View("home", "front");
    }

    public function generateSitemapAction() {
        header('Content-Type: text/xml; charset=UTF-8');
        $sitemap = "<?xml version='1.0' encoding='UTF-8'?>";
        $sitemap .= "<urlset xmlns='http://www.sitemaps.org/schemas/sitemap/0.9'>";

        // Static pages
        $pages = new Page();
        $pages = $pages->select()->where('deletedAt', 'NULL')->get();
        foreach ($pages as $page) {
            $loc = 'http://test/'.$page->getSlug();
            $lastUpdate = $page->getUpdatedAt() ?? $page->getCreatedAt();
            $sitemap .= '
            <url>
                <loc>'.$loc.'</loc>
                <lastmod>'.$lastUpdate.'</lastmod>
            </url>';
        }

        // Categories
        $categories = new Category();
        $categories = $categories->select()->where('position', 0, '=>')->get();
        foreach ($categories as $category) {
            $loc = 'http://test/categorie/'.Helpers::slugify($category->getName());
            $lastUpdate = $category->getUpdatedAt() ?? $category->getCreatedAt();
            $sitemap .= '
            <url>
                <loc>'.$loc.'</loc>
                <lastmod>'.$lastUpdate.'</lastmod>
            </url>';
        }


        // Articles
        $articles = new Article();
        $articles = $articles->select()->where('deletedAt', 'NULL')->get();
        foreach ($articles as $article) {
            $loc = 'http://test/article/'.$article->getSlug();
            $lastUpdate = $article->getUpdatedAt() ?? $article->getCreatedAt();
            $sitemap .= '
            <url>
                <loc>'.$loc.'</loc>
                <lastmod>'.$lastUpdate.'</lastmod>
            </url>';
        }

        $sitemap .= "</urlset>";

        // Send to view
        $view = new View("sitemap", null);
        $view->assign('sitemap', $sitemap);

    }

}