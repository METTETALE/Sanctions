<?php
class Router
{
    private array $routes;
    private Request $request;
    private Response $response;

    public function __construct($routes = [])
    {
        $this->routes = $routes;
        $this->request = new Request();
        $this->response = new Response();
    }

    public function addRoute($index, $function, $methods = ['GET'])
    {
        $this->routes[$index] = [
            'fonction' => $function,
            'methodes' => $methods
        ];
        return $this;
    }

    public function handleRequest()
    {
        $action = $this->request->getAction();

        if (isset($this->routes[$action])) {
            $route = $this->routes[$action];
            $method = $this->request->getMethod();

            if (in_array($method, $route['methodes'])) {
                if (function_exists($route['fonction'])) {
                    $route['fonction']($this->request, $this->response);
                    // $this->controller->indexFilms($this->request, $this->response);
                } else {
                    $this->handleFunctionNotFound();
                }
            } else {
                $this->handleMethodNotAllowed($route['methodes']);
            }
        } else {
            $this->handleNotFound();
        }
    }

    private function handleNotFound()
    {
        $this->response->redirect("index.php?action=index");
        return $this;
    }

    private function handleMethodNotAllowed($methodesAutorisees)
    {
        $this->response->redirect("HTTP/1.1 405 Method Not Allowed");
        echo "Méthode non autorisée pour cette action. Méthodes autorisées : " . implode(', ', $methodesAutorisees);
        return $this;
    }

    private function handleFunctionNotFound()
    {
        $this->response->redirect("HTTP/1.1 500 Internal Server Error");
        echo "Une erreur Interne est survenue. Veuillez contacter l'administrateur.";
        return $this;
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }

    public function hasRoute(string $action): bool
    {
        return isset($this->routes[$action]);
    }
}
