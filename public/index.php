<?php

// session_start(); // 💡 Dica: No futuro você pode apagar isso. JWT não usa sessão do PHP!

// 1. Carrega o Autoload do Composer
require_once __DIR__ . '/../vendor/autoload.php';

use Core\Router;
use App\Controllers\AuthController;
use App\Controllers\AuthApiController;
use App\Controllers\PasswordController;
use App\Middlewares\AuthMiddleware; // <-- Trouxemos o segurança para cá!

$router = new Router();

// =======================================================
// 🖥️ 1. ROTAS DE TELA (Frontend - Carregam o Visual)
// =======================================================
$router->get('/', [AuthController::class, 'loginForm']); 
$router->get('/registrar', [AuthController::class, 'registerForm']); 
$router->get('/esqueci-senha', [PasswordController::class, 'forgotForm']);

// A home agora só entrega o HTML puro. O JavaScript cuida de proteger!
$router->get('/home', [AuthApiController::class, 'home']); 


// =======================================================
// ⚙️ 2. ROTAS ANTIGAS DO PHP MONOLÍTICO (Deixei para não quebrar)
// =======================================================
$router->post('/login', [AuthController::class, 'login']);
$router->post('/registrar', [AuthController::class, 'register']);
$router->post('/esqueci-senha', [PasswordController::class, 'forgotProcess']);
$router->get('/logout', [AuthController::class, 'logout']);


// =======================================================
// 🚀 3. ROTAS DE API (Backend Novo - Trabalham com JSON e JWT)
// =======================================================
// Rota que o seu public/js/login.js usa para pegar o token
$router->post('/api/login', [AuthApiController::class, 'login']);

// 🛡️ A ROTA PROTEGIDA! O seu public/js/authGuard.js vai bater aqui
$router->get('/api/validar-acesso', [AuthApiController::class, 'validarAcesso'], [AuthMiddleware::class]);


// Liga o motor!
$router->resolve();