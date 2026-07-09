<?php
namespace App\Controllers;

class PageController
{
    // envia a tela de login
    public function loginForm(): void
    {
        require_once __DIR__ . '/../Views/login.php';
    }

    // envia a tela de registro
    public function registerForm(): void 
    { 
        require_once __DIR__ . '/../Views/registrar.php'; 
    }

    // envia a tela home
    public function home(): void
    {
        require_once __DIR__ . '/../Views/home.php';
    }

    // envia a tela de esqueci a senha
    public function forgotForm(): void
    {
        require_once __DIR__ . '/../Views/esqueci-senha.php';
    }
};

