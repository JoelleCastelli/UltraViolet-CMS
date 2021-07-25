<?php

namespace App\Controller;

use App\Core\Database;
use App\Core\Helpers;
use App\Core\View;
use App\Models\Article;
use App\Models\ArticleHistory;
use App\Models\Comment;
use App\Models\Production;
use App\Models\Person;
use App\Models\Page;
use App\Models\Category;
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
        $productions = $this->getLatestProductions(3);
        $view->assign('productions', $productions);

        $nbArticles = $this->getNbArticles();
        $view->assign('nbArticles', $nbArticles);

        $nbComments = $this->getNbComments();
        $view->assign('nbComments', $nbComments);

        $nbUsers = $this->getNbUsers();
        $view->assign('nbUsers', $nbUsers);

        $nbViews = $this->getNbViews();
        $view->assign('nbViews', $nbViews);


        $view->assign('bodyScripts', [PATH_TO_SCRIPTS.'headScripts/dashboard.js']);
	}

	public function getLatestArticles($limit): array
    {
        $articles = new Article();
        return $articles->select()->where('deletedAt', "NULL")
            ->andWhere('publicationDate', 'NOT NULL')
            ->andWhere('publicationDate', 'NOW', '<=')
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

    public function getNbUsers()
    {
        $persons = new Person();
        return $persons->count('id')->where('deletedAt', "NULL")->andWhere( 'role' , 'vip', '!=')->first(false);
    }

    public function getNbViews()
    {
        $articleHistory = new ArticleHistory();
        return $articleHistory->sum('views')->first(false);
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
        $article = new Article;
        $articles = $article->select()->where('deletedAt', "NULL")->andWhere('publicationDate', date("Y-m-d H:i:s"), "<=")->orderBy('publicationDate', 'DESC')->limit()->get();

        $view = new View("home", "front");
        $view->assign('articles', $articles);
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
            $objects = $objects->select()->where('position', 0, '>')->get();
        else
            $objects = $objects->select()->where('deletedAt', 'NULL')
                                         ->andWhere('publicationDate', 'NOT NULL')
                                         ->andWhere("publicationDate", date('Y-m-d H:i:s'), "<=")
                                         ->get();

        foreach ($objects as $object) {
            if($class == 'category')
                $loc = Helpers::getBaseUrl().'/categorie/'.Helpers::slugify($object->getName());
            elseif ($class == 'article')
                $loc = Helpers::getBaseUrl().'/article/'.$object->getSlug();
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

    public function populateDatabaseAction(){

        $this->populateUsers();
        $this->populateArticles();
        $this->populateComments();
        $this->populateArticlesHistory(); 
        $this->populateArticleCategory();
    }

    private function populateUsers(){

        $password = password_hash("password", PASSWORD_DEFAULT);

        // Admins
        $user = new Person();
        $user->setEmailConfirmed(true);
        $user->setPassword($password);
        $user->setDefaultProfilePicture();

        $user->setRole('admin');

        $user->setPseudo("joelle");
        $user->setEmail("joelle@mail.com");
        $user->setEmailKey($user->getGenerateEmailKey());
        $user->save();

        $user->setPseudo("sami");
        $user->setEmail("sami@mail.com");
        $user->setEmailKey($user->getGenerateEmailKey());
        $user->save();

        $user->setPseudo("sylvain");
        $user->setEmail("sylvain@mail.com");
        $user->setEmailKey($user->getGenerateEmailKey());
        $user->save();

        $user->setPseudo("romain");
        $user->setEmail("romain@mail.com");
        $user->setEmailKey($user->getGenerateEmailKey());
        $user->save();

        $user->setPseudo("coraline");
        $user->setEmail("coraline@mail.com");
        $user->setEmailKey($user->getGenerateEmailKey());
        $user->save();

        // Editors
        $user->setRole('editor');

        $user->setPseudo("joelleEditeur");
        $user->setEmail("joelleEditeur@mail.com");
        $user->setEmailKey($user->getGenerateEmailKey());
        $user->save();

        $user->setPseudo("samiEditeur");
        $user->setEmail("samiEditeur@mail.com");
        $user->setEmailKey($user->getGenerateEmailKey());
        $user->save();

        $user->setPseudo("sylvainEditeur");
        $user->setEmail("sylvainEditeur@mail.com");
        $user->setEmailKey($user->getGenerateEmailKey());
        $user->save();

        $user->setPseudo("romainEditeur");
        $user->setEmail("romainEditeur@mail.com");
        $user->setEmailKey($user->getGenerateEmailKey());
        $user->save();

        $user->setPseudo("coralineEditeur");
        $user->setEmail("coralineEditeur@mail.com");
        $user->setEmailKey($user->getGenerateEmailKey());
        $user->save();

        // Moderator
        $user->setRole('moderator');

        $user->setPseudo("joelleModerateur");
        $user->setEmail("joelleModerateur@mail.com");
        $user->setEmailKey($user->getGenerateEmailKey());
        $user->save();

        $user->setPseudo("samiModerateur");
        $user->setEmail("samiModerateur@mail.com");
        $user->setEmailKey($user->getGenerateEmailKey());
        $user->save();

        $user->setPseudo("sylvainModerateur");
        $user->setEmail("sylvainModerateur@mail.com");
        $user->setEmailKey($user->getGenerateEmailKey());
        $user->save();

        $user->setPseudo("romainModerateur");
        $user->setEmail("romainModerateur@mail.com");
        $user->setEmailKey($user->getGenerateEmailKey());
        $user->save();

        $user->setPseudo("coralineModerateur");
        $user->setEmail("coralineModerateur@mail.com");
        $user->setEmailKey($user->getGenerateEmailKey());
        $user->save();

    }

    private function populateArticles()
    {
        $content = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce pulvinar porta urna, ut placerat ipsum pellentesque id. Nam non tempor sem. Nulla at vehicula diam. Ut molestie risus sed fermentum finibus. Etiam in lectus varius, molestie sem eget, semper sapien. Integer id placerat mauris. Vestibulum auctor turpis non dolor malesuada, sit amet eleifend arcu imperdiet. Praesent interdum, magna in lobortis auctor, magna ipsum elementum felis, vel dictum purus lacus vel neque. Proin ultrices arcu arcu, et rutrum felis hendrerit nec. Aenean pharetra, augue eu dignissim volutpat, odio orci varius augue, iaculis fermentum lorem lorem eu turpis. Etiam augue purus, dignissim vestibulum quam in, semper dignissim neque. Phasellus arcu lorem, sodales at vestibulum ac, fringilla sed elit. Praesent lobortis quam imperdiet tincidunt facilisis. Morbi pretium tempus erat a pulvinar. Duis aliquet scelerisque ullamcorper. Sed ac libero massa.<br>Integer in iaculis erat. Pellentesque hendrerit magna et urna commodo consequat. Aliquam sit amet porta nibh. Donec vel lobortis diam. Maecenas sapien est, venenatis at dui at, sodales ullamcorper sem. Suspendisse hendrerit massa lorem, vitae lobortis augue interdum vitae. Aenean dui lectus, bibendum ac ullamcorper ut, molestie sed arcu. Praesent vitae lacus nunc. Nullam et tempus lacus, sed rhoncus lectus. Aliquam et ornare neque. Quisque dictum eget lorem non faucibus.Sed non metus at lectus bibendum pellentesque. Nulla posuere orci nibh, at pretium nisi iaculis vel. Cras eget mi imperdiet, consequat arcu et, feugiat tortor. Donec eget ultrices nisl, at lobortis dolor. Nulla sem lectus, suscipit eu erat at, pulvinar fringilla arcu. Integer erat ligula, pretium non vulputate sit amet, elementum eu nisl. Duis sodales est lectus, at molestie ante tincidunt id. Maecenas non molestie lacus, nec hendrerit erat. Pellentesque sed nisl ut eros fringilla finibus. Maecenas ornare velit sed ligula bibendum, ut sagittis enim dictum. Nullam lobortis leo sit amet velit sagittis, molestie porta ex eleifend. Morbi velit libero, mollis vel ullamcorper a, ultrices a sapien. In pellentesque turpis ligula, non mattis leo efficitur eget. Quisque ac ligula nec risus egestas auctor vitae ut leo.Nunc ornare sem vel tellus commodo, ultrices blandit nisi sodales. Etiam ac lobortis dui. Praesent fringilla sed purus vitae tincidunt. Nunc rutrum a dui pretium aliquet. Suspendisse a ante sit amet orci tincidunt pretium quis eget massa. Vivamus turpis orci, lacinia ac sagittis non, sollicitudin consectetur est. Fusce eget dignissim sem, vitae ultricies ex. Sed molestie est eu ligula elementum, in faucibus diam iaculis. Duis at consequat erat. Donec sed arcu felis.<br>Integer dapibus vulputate est. Ut sit amet ornare ante. Maecenas luctus lacus ullamcorper mi vulputate lobortis. Donec vitae ultrices nisi, sed commodo lorem. Nullam scelerisque velit sem, vel faucibus metus dictum et. Donec sodales ipsum consectetur, pharetra est at, pulvinar est. Vestibulum in elementum enim. Mauris vitae sapien at mauris finibus suscipit nec a nulla. Cras iaculis venenatis cursus. Sed volutpat velit vitae enim egestas, sit amet maximus nisi bibendum. Ut vel turpis id enim aliquam placerat. Phasellus auctor fringilla libero a pulvinar. Aenean faucibus ex ut posuere placerat. Aenean a erat quis lacus ornare efficitur. Cras dictum ex cursus dolor accumsan, ut laoreet orci venenatis.";
        $description = "Mauris vitae sapien at mauris finibus suscipit nec a nulla. Cras iaculis venenatis cursus. sit amet maximus nisi bibendum.";
        
        $article = new Article();
        $user = new Person();
        $userIDs = $user->select('id')->where('role', 'admin')->orWhere('role', 'editor')->get(false);

        $article->setDescription($description);
        $article->setContent($content);
        $article->setToPublished();
        $article->setDefaultPicture();

        $article->setTitle("Titanic : A la fin tout le monde meurt");
        $article->setSlug("dev-titanic");
        $key = array_rand($userIDs);
        $article->setPersonId($userIDs[$key]);
        $article->save();

        $article->setTitle("Usual Suspect : Pleins d'action et retournements");
        $article->setSlug("dev-usual-suspect");
        $key = array_rand($userIDs);
        $article->setPersonId($userIDs[$key]);
        $article->save();

        $article->setTitle("Spartacus : aimes tu les films de gladiators ?");
        $article->setSlug("dev-spartacus");
        $key = array_rand($userIDs);
        $article->setPersonId($userIDs[$key]);
        $article->save();

        $article->setTitle("Ratatouille : mima miam");
        $article->setSlug("dev-ratatouille");
        $key = array_rand($userIDs);
        $article->setPersonId($userIDs[$key]);
        $article->save();

        $article->setTitle("Parasite : Oh mais qui sont ces gens ?");
        $article->setSlug("dev-parasite1");
        $key = array_rand($userIDs);
        $article->setPersonId($userIDs[$key]);
        $article->save();

        $article->setTitle("Pulp Fiction : L’odyssée sanglante et burlesque de petits malfrats.");
        $article->setSlug("dev-pulp-fiction1");
        $key = array_rand($userIDs);
        $article->setPersonId($userIDs[$key]);
        $article->save();

        $article->setTitle("Step Up : Alors on danse !");
        $article->setSlug("step-up1");
        $key = array_rand($userIDs);
        $article->setPersonId($userIDs[$key]);
        $article->save();

        $article->setTitle("The revenant : Attention à l'ours");
        $article->setSlug("dev-the-revenant1");
        $key = array_rand($userIDs);
        $article->setPersonId($userIDs[$key]);
        $article->save();

        $article->setTitle("Hot Fuzz : La tactique du gendarme");
        $article->setSlug("hot-fuzz1");
        $key = array_rand($userIDs);
        $article->setPersonId($userIDs[$key]);
        $article->save();

        $article->setTitle("Le dernier train pour Busan : Zombie mangez !!!!");
        $article->setSlug("dev-dernier-train-pour-busan");
        $key = array_rand($userIDs);
        $article->setPersonId($userIDs[$key]);
        $article->save();

        $article->setTitle("Fast and Furious 201 : Hot wheels mais grandeur nature");
        $article->setSlug("dev-fast-and-curious");
        $key = array_rand($userIDs);
        $article->setPersonId($userIDs[$key]);
        $article->save();

        $article->setTitle("Le loup de Wall Street : Money, money, money ABBA");
        $article->setSlug("vle-loup-de-wall-street");
        $key = array_rand($userIDs);
        $article->setPersonId($userIDs[$key]);
        $article->save();

        $article->setTitle("The Big Short : longue histoire courte");
        $article->setSlug("dev-the-big-short");
        $key = array_rand($userIDs);
        $article->setPersonId($userIDs[$key]);
        $article->save();

        $article->setTitle("Le voyage de Chihiro : Voyage voyage !");
        $article->setSlug("dev-le-voyage-de-chirhiro");
        $key = array_rand($userIDs);
        $article->setPersonId($userIDs[$key]);
        $article->save();

        $article->setTitle("Mon Totoro : ou mon totogro :) ");
        $article->setSlug("dev-mon-totoro");
        $key = array_rand($userIDs);
        $article->setPersonId($userIDs[$key]);
        $article->save();

    }

    private function populateComments()
    {
        // content comment
        $content = "Fusce sit amet mi quis mauris condimentum vulputate eget non mi. Vivamus eu elit turpis. Nulla facilisi. Nam rutrum convallis risus vel pharetra. Sed eleifend, lorem in ultricies pretium, tortor massa gravida orci, id efficitur leo ipsum non metus. Aliquam lacinia tortor purus, vel eleifend erat vulputate eget. Morbi pharetra posuere nulla sed sodales. In rutrum arcu velit, et ultrices risus maximus id.";

        $comment = new Comment();
        $user = new Person();
        $article = new Article();

        // get users ids
        $userIDs = $user->select('id')->where('role', 'admin')->orWhere('role', 'editor')->get(false);

        // article ids
        $articleIDs = $article->select('id')->get(false);

        foreach($articleIDs as $articleID){

            $comment->setContent($content);
            $comment->setArticleId($articleID);

            for($i = 0; $i <= 5; $i++) {
                $key = array_rand($userIDs);
                $comment->setPersonId($userIDs[$key]);
                $comment->save();
            }
        }
    }

    private function populateArticlesHistory()
    {

        $article = new Article();
        $articleHistory = new ArticleHistory();

        // article ids
        $articleIDs = $article->select('id')->get(false);

        foreach ($articleIDs as $articleID) {

            for($i = 0; $i <= 14; $i++){
                $views = rand(0, 50);
                $interval = "P" . rand(1, 100) . "D"; // random days for date
                // check if same date and article already exist
                $date = date_add(new \DateTime('now'), new \DateInterval($interval))->format('Y-m-d');
                $rowAlreadyExist = $articleHistory->select()->where("date", $date)->andWhere('articleId', $articleHistory->getId())->get();

                // continue changing date if already exist
                while(!empty($rowAlreadyExist)){
                    $interval = "P" . rand(1, 100) . "D"; 
                    $date = date_add(new \DateTime('now'), new \DateInterval($interval))->format('Y-m-d');
                    $rowAlreadyExist = $articleHistory->select()->where( "date", $date)->andWhere('articleId', $articleHistory->getId())->get();
                }

                $articleHistory->setViews($views);
                $articleHistory->setDate($date);
                $articleHistory->setArticleId($articleID);
                $articleHistory->save();

            }
        }
    }

    private function populateArticleCategory(){

        $category = new Category;
        $article = new Article;
        $categoryArticle = new CategoryArticle;

        $articleIDs = $article->select('id')->get(false);
        $categoryIDs = $category->select('id')->get(false);

        foreach($categoryIDs as $categoryID){
            $categoryArticle->setCategoryId($categoryID);

            for($i = 0; $i < 5; $i++){

                $key = array_rand($articleIDs);
                $articleID = $articleIDs[$key]; 

                while(!empty($categoryArticle->select()->where('articleId', $articleID)->andWhere('categoryId', $categoryID)->get())){
                    $key = array_rand($articleIDs);
                    $articleID = $articleIDs[$key];
                } 

                $categoryArticle->setArticleId($articleID);
                $categoryArticle->save(); 

            }
        }

    }

}