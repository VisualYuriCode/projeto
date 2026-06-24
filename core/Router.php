<?php

namespace Core;

class Router
{
    private array $routes = [];

    // Registra rotas do tipo GET (para carregar páginas)
    public function get(string $path, array $handler): void
    {
        $this->routes['GET'][$path] = $handler;
    }

    // Registra rotas do tipo POST (para processar formulários)
    public function post(string $path, array $handler): void
    {
        $this->routes['POST'][$path] = $handler;
    }

    // Resolve a rota atual acessada pelo usuário
    public function resolve(): void
    {
        $method = $_SERVER['REQUEST_METHOD']; // GET ou POST
        
        // Pega a URL limpa digitada no navegador
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // SOLUÇÃO DEFINITIVA PARA LOCALHOST:
        // Em vez de fixar o nome da pasta, pegamos o caminho onde o index.php real está rodando
        $scriptName = dirname($_SERVER['SCRIPT_NAME']); // Ex: /meu-projeto-login/public
        
        // Se a URL começar com o caminho do script, nós o removemos dinamicamente
        if ($scriptName !== '/' && str_starts_with($uri, $scriptName)) {
            $uri = substr($uri, strlen($scriptName));
        }

        // Garante que o caminho sempre comece com uma barra "/" e remove duplicadas (ex: "//" vira "/")
        $uri = '/' . ltrim($uri, '/');

        // Se a rota existir para o método atual (GET ou POST)
        if (isset($this->routes[$method][$uri])) {
            [$controllerClass, $methodName] = $this->routes[$method][$uri];

            if (class_exists($controllerClass)) {
                $controller = new $controllerClass();
                if (method_exists($controller, $methodName)) {
                    $controller->$methodName();
                    return;
                }
            }
        }

        // Se não encontrar, emite o erro 404
        http_response_code(404);
        echo "Página não encontrada (404) Erro na Rota. URI tentada: " . htmlspecialchars($uri);
    }
}