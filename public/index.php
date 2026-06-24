<?php

session_start();

// 1. Carrega o Autoload do Composer (A mágica que evita os 'require' manuais)
require_once __DIR__ . '/../vendor/autoload.php';

use Core\Router;
use App\Controllers\AuthController;
use App\Controllers\PasswordController;

$router = new Router();

// --- DEFINIÇÃO DE ROTAS DA APLICAÇÃO ---

// Rotas de Login
$router->get('/', [AuthController::class, 'loginForm']);       // Tela de login
$router->post('/login', [AuthController::class, 'login']);     // Processar o envio do login

// Rotas de Criar Conta (Já deixando o espaço guardado!)
$router->get('/registrar', [AuthController::class, 'registerForm']); // Tela de cadastro
$router->post('/registrar', [AuthController::class, 'register']);    // Processar o cadastro

// Rotas de Esqueci a Senha
$router->get('/esqueci-senha', [\App\Controllers\PasswordController::class, 'forgotForm']);
$router->post('/esqueci-senha', [\App\Controllers\PasswordController::class, 'forgotProcess']);

// Rota protegida (Home)
$router->get('/home', [AuthController::class, 'home']);
$router->get('/logout', [App\Controllers\AuthController::class, 'logout']);
// 2. Executa o roteador para descobrir onde o usuário quer ir
$router->resolve();