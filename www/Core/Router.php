<?php

namespace App\Core;

class Router {

	private string $requestedUri;
	private string $action;
	private string $controller;
	private string $office;
	private array $middlewares = [];
	private array $parameters = [];
    private array $routes = [];
    private array $slugs = [];

	public function __construct($slug){
		$this->requestedUri = $slug;
		$this->loadYaml();

		// Check if route is not in both files
        if(!empty($this->routes['back'][$this->requestedUri]) && !empty($this->routes['front'][$this->requestedUri]))
            die("Error: route $this->requestedUri is in both front and back routes files");

        // Find route and get info
        if($this->matchRoute($this->routes)) {
            $routeData = $this->matchRoute($this->routes);
            $this->setOffice($routeData['office']);
            $this->setController($routeData["controller"]);
            $this->setAction($routeData["action"]);
            if(isset($routeData['params']))
                $this->setParameters($routeData['params']);
            if(isset($routeData["middleware"]))
                $this->setMiddlewares($routeData["middleware"]);
        } else {
            Helpers::redirect('/404', 404);
        }
	}

	public function loadYaml() {
	    $backRoutes = yaml_parse_file(PATH_TO_ADMIN_ROUTES);
        foreach ($backRoutes as $slug => $routeData) {
			if(empty($routeData["controller"]) || empty($routeData["action"]))
				die("Back routes file: YAML parsing error");
			$this->slugs['back'][$routeData["controller"]][$routeData["action"]] = $slug;
		}

        $frontRoutes = yaml_parse_file(PATH_TO_ROUTES);
        foreach ($frontRoutes as $slug => $routeData) {
            if(empty($routeData["controller"]) || empty($routeData["action"]))
                die("Front routes file: YAML parsing error");
            $this->slugs[$routeData["controller"]][$routeData["action"]] = $slug;
        }

        $this->routes['back'] = $backRoutes;
        $this->routes['front'] = $frontRoutes;
	}

    protected function matchRoute($routesArray) {
        foreach ($routesArray as $office => $routes) {
            foreach ($routes as $routeSlug => $routeData) {

                $routeData['office'] = $office;
                if ($routeSlug == $this->requestedUri) return $routeData;

                // If route has parameters, replace name by regex value
                if (strpos($routeSlug, '{')) {
                    $cleanRoute = str_replace('/', '\/', $routeSlug) ;
                    foreach ($routeData as $paramName => $regex) {
                        if(!in_array($paramName, ['controller', 'action', 'middleware', 'office'])) {
                            $cleanRoute = str_replace('{' . $paramName . '}', '(' . $regex . ')', $cleanRoute);
                        }
                    }
                    // If requested url matches route pattern, return route
                    preg_match('~^' . $cleanRoute . '$~', $this->requestedUri, $matches);
                    if (isset($matches[1])) {
                        array_shift($matches);
                        $routeData['params'] = $matches;
                        return $routeData;
                    }
                }
            }
        }
        return false;
    }

	public function getSlug($controller = "Main", $action = "default"): string {
        return $this->slugs[$this->office][$controller][$action];
	}

    public function getController(): string {
        return $this->controller;
    }

	public function setController($controller): void {
		$this->controller = ucfirst($controller);
	}

    public function getAction(): string {
        return $this->action;
    }

	public function setAction($action): void {
		$this->action = $action."Action";
	}

    public function getMiddlewares(): array {
        return $this->middlewares;
    }

    public function setMiddlewares($middlewares): void {
        if(strpos($middlewares, ',')) {
            $this->middlewares = array_map('trim', explode(',', $middlewares));
        } else {
            $this->middlewares[] = $middlewares;
        }
    }

    public function getOffice(): string {
        return $this->office;
    }

    public function setOffice($office): void {
        $this->office = $office;
    }

    public function getParameters(): array {
        return $this->parameters;
    }

    public function setParameters(array $parameters): void {
        $this->parameters = $parameters;
    }

}