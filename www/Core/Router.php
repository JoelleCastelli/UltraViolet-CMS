<?php

namespace App\Core;

class Router {

	private string $slug;
	private string $action;
	private string $controller;
	private array $middleware = [];
    private string $routesFile;

	private string $backRoutesPath = "routesAdmin.yml";
    private array $backRoutes = [];
    private array $backSlugs = [];

    private string $frontRoutesPath = "routes.yml";
    private array $frontRoutes = [];
    private array $frontSlugs = [];

	public function __construct($slug){
		$this->slug = $slug;
		$this->loadYaml();

		// Check for duplicates
        if(!empty($this->backRoutes[$this->slug]) && !empty($this->frontRoutes[$this->slug]))
            die("Error: route $this->slug is in both front and back routes files");

        // Check if route exists and in which file
		if(empty($this->backRoutes[$this->slug])) {
		    if(empty($this->frontRoutes[$this->slug]))
                $this->exception404();
            else
                $this->setRoutesFile('front');
        } else {
		    $this->setRoutesFile('back');
        }

        // Set controller, action and middleware
		if($this->routesFile == 'front') {
            $this->setController($this->frontRoutes[$this->slug]["controller"]);
            $this->setAction($this->frontRoutes[$this->slug]["action"]);
            if(isset($this->frontRoutes[$this->slug]["middleware"]))
                $this->setMiddleware($this->frontRoutes[$this->slug]["middleware"]);
        } else {
            $this->setController($this->backRoutes[$this->slug]["controller"]);
            $this->setAction($this->backRoutes[$this->slug]["action"]);
            if(isset($this->backRoutes[$this->slug]["middleware"]))
                $this->setMiddleware($this->backRoutes[$this->slug]["middleware"]);
        }
	}

	public function loadYaml() {
        $this->backRoutes = yaml_parse_file($this->backRoutesPath);
        foreach ($this->backRoutes as $slug => $route) {
			if(empty($route["controller"]) || empty($route["action"]))
				die("Back routes file: YAML parsing error");
			$this->backSlugs[$route["controller"]][$route["action"]] = $slug;
		}

        $this->frontRoutes = yaml_parse_file($this->frontRoutesPath);
        foreach ($this->frontRoutes as $slug => $route) {
            if(empty($route["controller"]) || empty($route["action"]))
                die("Front routes file: YAML parsing error");
            $this->frontSlugs[$route["controller"]][$route["action"]] = $slug;
        }
	}

	public function getSlug($controller = "Main", $action = "default"): string {
	    if($this->routesFile == 'front')
    		return $this->frontSlugs[$controller][$action];
	    else
            return $this->backSlugs[$controller][$action];
	}

	public function setController($controller): void {
		$this->controller = ucfirst($controller);
	}

	public function setAction($action): void {
		$this->action = $action."Action";
	}

	public function getController(): string {
		return $this->controller;
	}

	public function getAction(): string {
		return $this->action;
	}

	public function exception404($message = null) {
		die("Erreur 404");
	}

    public function getMiddleware(): array {
        return $this->middleware;
    }

    public function setMiddleware($middleware): void {
	    if(strpos($middleware, ',')) {
            $this->middleware = array_map('trim', explode(',', $middleware));
        } else {
            $this->middleware[] = $middleware;
        }
    }

    public function getRoutesFile(): string {
        return $this->routesFile;
    }

    public function setRoutesFile(string $routesFile): void {
        $this->routesFile = $routesFile;
    }

}