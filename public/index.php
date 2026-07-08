<?php

// session_start(); // 💡 Dica: No futuro você pode apagar isso. JWT não usa sessão do PHP!

// 1. Carrega o Autoload do Composer
require_once __DIR__ . '/../vendor/autoload.php';

use Core\Router;
use App\Controllers\AuthApiController;
use App\Controllers\PasswordController;
use App\Middlewares\AuthMiddleware; 


// Cria os caminhos do nosso servidor
$router = new Router();

// carrega nossa tela de login
$router->get('/', [AuthApiController::class, 'loginForm']);

$router->get('/registrar', [AuthApiController::class, 'registerForm']);
$router->post('/registrar', [AuthApiController::class, 'register']);
$router->get('/esqueci-senha', [PasswordController::class, 'forgotForm']);
$router->get('/home', [AuthApiController::class, 'home']); 
$router->post('/esqueci-senha', [PasswordController::class, 'forgotProcess']);



// =======================================================
// Rota que o seu public/js/login.js usa para pegar o token
$router->post('/api/login', [AuthApiController::class, 'login']);

// 🛡️ A ROTA PROTEGIDA! O seu public/js/authGuard.js vai bater aqui
$router->get('/api/validar-acesso', [AuthApiController::class, 'validarAcesso'], [AuthMiddleware::class]);


// Liga o motor!
$router->resolve();