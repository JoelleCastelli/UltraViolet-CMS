<?php

namespace App\Core;

class Router {

	private string $requestedUri;
	private string $action;
	private string $controller;
	private string $office;
	private array $middlewares = [];
	private array $parameters = [];
    private array $slugs = [];
    private string $path;
    private string $name;
    public static array $routes = [];

	public function __construct($slug){
		$this->requestedUri = $slug;
        $this->loadYaml();

        // Find route and get info
        if($this->matchRoute(self::$routes)) {
            $routeData = $this->matchRoute(self::$routes);
            $this->setPath($routeData['path']);
            $this->setName($routeData['routeName']);
            $this->setOffice($routeData['office']);
            $this->setController($routeData["controller"]);
            $this->setAction($routeData["action"]);
            if(isset($routeData['params']))
                $this->setParameters($routeData['params']);
            if(isset($routeData["middleware"]))
                $this->setMiddlewares($routeData["middleware"]);
        } else {
            Helpers::redirect(Helpers::callRoute('404'), 404);
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

        self::$routes['back'] = $backRoutes;
        self::$routes['front'] = $frontRoutes;

        $this->checkDuplicatedRoutes(self::$routes);
	}

    public function checkDuplicatedRoutes($routes) {
        // Check if front and back routes have common names
        $duplicatedNames = array_intersect_key($routes['back'], $routes['front']);
        if(empty($duplicatedNames)) {
            // Check if path is in both back and front routes
            $duplicatedPaths = [];
            foreach ($routes['back'] as $name => $backRouteData) {
                foreach ($routes['front'] as $name => $frontRouteData) {
                    if($backRouteData['path'] == $frontRouteData['path']) {
                        $duplicatedPaths[] = $frontRouteData['path'];
                        break;
                    }
                }
            }
            if (!empty($duplicatedPaths)) {
                die("Back and front routes can't have same path. Duplicated paths: ".implode(', ', $duplicatedPaths));
            }
        } else {
            die("Back and front routes can't have same name. Duplicated names: ".implode(', ', array_keys($duplicatedNames)));
        }
    }

    protected function matchRoute($routesArray) {
        foreach ($routesArray as $office => $routes) {
            foreach ($routes as $routeName => $routeData) {

                $routeData['office'] = $office;
                $routeData['routeName'] = $routeName;
                if ($routeData['path'] == $this->requestedUri) return $routeData;
              
                // If route has parameters, replace name by regex value
                if (strpos($routeData['path'], '{')) {
                    $cleanRoute = str_replace('/', '\/', $routeData['path']) ;
                    $yamlParams = [];
                    foreach ($routeData['requirements'] as $paramName => $regex) {
                        $yamlParams[] = $paramName;
                        $cleanRoute = str_replace('{' . $paramName . '}', $regex, $cleanRoute);
                    }
                    // If requested url matches route pattern, return route
                    preg_match('~^' . $cleanRoute . '$~', $this->requestedUri, $matches);
                    if (isset($matches[1])) {
                        array_shift($matches);
                        //Check if number of params in URI = number of params in route
                        if(sizeof($matches) != sizeof($yamlParams)) die("Incorrect number of parameters");

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

    public function getPath(): string {
        return $this->path;
    }

    public function setPath(string $path): void {
        $this->path = $path;
    }

    public function getName(): string {
        return $this->name;
    }

    public function setName(string $name): void {
        $this->name = $name;
    }

}