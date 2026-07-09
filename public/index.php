<?php

// session_start(); // 💡 Dica: No futuro você pode apagar isso. JWT não usa sessão do PHP!

// 1. Carrega o Autoload do Composer
require_once __DIR__ . '/../vendor/autoload.php';

use Core\Router;
use App\Controllers\AuthApiController;
use App\Controllers\PageController;
use App\Controllers\PasswordController;
use App\Middlewares\AuthMiddleware; 


// Cria os caminhos do nosso servidor
$router = new Router();

// ===== diretorios de nossa aplicação get(¹nome do endereço ²onde está o redirecionador ³nome da função de redirecionar) ===== //
$router->get('/', [PageController::class, 'loginForm']);
$router->get('/home', [PageController::class, 'home']);
$router->get('/esqueci-senha', [PageController::class, 'forgotForm']);
$router->get('/registrar', [PageController::class, 'registerForm']);
// =============================== //

// Rota que o public/js/login.js usa para pegar o token JWT //
$router->post('/api/login', [AuthApiController::class, 'login']);

// A ROTA PROTEGIDA! O seu public/js/authGuard.js vai bater aqui
$router->get('/api/validar-acesso', [AuthApiController::class, 'validarAcesso'], [AuthMiddleware::class]);


// Liga o motor!
$router->resolve();