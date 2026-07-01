<?php

namespace Core;

class Router
{
    // 💡 CORREÇÃO 1: Declarar a propriedade explicitamente para evitar o erro Deprecated
    protected array $routes = [];

    public function get($path, $action, $middleware = []) {
        $this->routes['GET'][$path] = [
            'action' => $action, 
            'middleware' => (array)$middleware // Força a ser um array
        ];
    }

    public function post($path, $action, $middleware = []) {
        $this->routes['POST'][$path] = [
            'action' => $action, 
            'middleware' => (array)$middleware
        ];
    }

    // Resolve a rota atual acessada pelo usuário
    public function resolve(): void
    {
        // 1º PASSO: Capturar o método e a URL original
        $method = $_SERVER['REQUEST_METHOD']; // GET ou POST
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // 2º PASSO: Limpar a URL (O Roteador Inteligente do Laragon)
        $scriptName = dirname($_SERVER['SCRIPT_NAME']); // Ex: /meu-projeto-login/public
        if ($scriptName !== '/' && str_starts_with($uri, $scriptName)) {
            $uri = substr($uri, strlen($scriptName));
        }
        $uri = '/' . ltrim($uri, '/');

        // 3º PASSO: Procurar a rota baseada no método e na URL limpa
        $route = $this->routes[$method][$uri] ?? false;

        // Se a rota não existir, barra aqui com 404
        if ($route === false) {
            http_response_code(404);
            echo "Página não encontrada (404) Erro na Rota. URI tentada: " . htmlspecialchars($uri);
            exit;
        }

        // 4º PASSO: A Mágica do Middleware (O Segurança)
        $middlewares = $route['middleware'];
        foreach ($middlewares as $middlewareClass) {
            $instanciaMiddleware = new $middlewareClass();
            $instanciaMiddleware->handle(); // O segurança pede o crachá!
        }

        // 5º PASSO: Executar o Controlador (A Lógica da Página)
        // Se o código chegou até aqui, é porque o Middleware não barrou a entrada
        $action = $route['action'];
        [$controllerClass, $methodName] = $action;

        if (class_exists($controllerClass)) {
            $controller = new $controllerClass();
            if (method_exists($controller, $methodName)) {
                $controller->$methodName();
                return;
            }
        }
        
        // Se der algo errado nas classes internas, exibe Erro 500
        http_response_code(500);
        echo "Erro Interno (500) Controlador ou Método não encontrado.";
    }
}