<?php

namespace App\Controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthApiController
{

    public function loginForm(): void
    {
        require_once __DIR__ . '/../Views/login.php';
    }

    private function enviarCabecalhos(): void
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }
        header('Content-Type: application/json; charset=utf-8');
    }

    public function registerForm(): void 
    { 
        require_once __DIR__ . '/../Views/registrar.php'; 
    }

    public function login(): void
    {
        $this->enviarCabecalhos();

        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $senha = $_POST['senha'] ?? '';

        if (!$email || !$senha) {
            http_response_code(400);
            echo json_encode(["sucesso" => false, "mensagem" => "Por favor, preencha todos os campos."]);
            exit;
        }

        $userModel = new \App\Models\UserModel();
        $user = $userModel->findByEmail($email);

        if ($user && password_verify($senha, $user->senha)) {
            $chave_secreta = getenv('JWT_SECRET') ?: 'chave_padrao_insegura';

            $payload = [
                "iss" => "http://localhost",
                "aud" => "http://localhost",
                "iat" => time(),
                "exp" => time() + (60 * 60),
                "uid" => $user->id,
                "email" => $user->email
            ];

            $token_jwt = JWT::encode($payload, $chave_secreta, 'HS256');

            http_response_code(200);
            echo json_encode([
                "sucesso" => true,
                "mensagem" => "Login autorizado com sucesso!",
                "token" => $token_jwt,
                "usuario" => [
                    "nome" => $user->nome,
                    "email" => $user->email
                ]
            ]);
            exit;
        } else {
            http_response_code(401);
            echo json_encode(["sucesso" => false, "mensagem" => "E-mail ou senha incorretos."]);
            exit;
        }
    }

    public function register(): void
    {
        $this->enviarCabecalhos();

        $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $senha = $_POST['senha'] ?? '';
        $senhaConfirmacao = $_POST['senha_confirmacao'] ?? '';

        if ($senha !== $senhaConfirmacao) {
            http_response_code(400);
            echo json_encode(["sucesso" => false, "mensagem" => "As senhas não coincidem."]);
            exit;
        }

        if (strlen($senha) < 8) {
            http_response_code(400);
            echo json_encode(["sucesso" => false, "mensagem" => "A senha deve ter pelo menos 8 caracteres."]);
            exit;
        }

        if (!preg_match('/[^a-zA-Z0-9]/', $senha)) {
            http_response_code(400);
            echo json_encode(["sucesso" => false, "mensagem" => "A senha deve conter pelo menos um caractere especial."]);
            exit;
        }

        $userModel = new \App\Models\UserModel();

        if ($userModel->findByEmail($email)) {
            http_response_code(409);
            echo json_encode(["sucesso" => false, "mensagem" => "Este e-mail já está em uso."]);
            exit;
        }

        if ($userModel->create($nome, $email, $senha)) {
            http_response_code(201);
            echo json_encode(["sucesso" => true, "mensagem" => "Conta criada com sucesso! Faça o seu login."]);
            exit;
        } else {
            http_response_code(500);
            echo json_encode(["sucesso" => false, "mensagem" => "Ocorreu um erro ao criar a conta."]);
            exit;
        }
    }
    public function validarAcesso(): void
    {
    header('Content-Type: application/json; charset=utf-8');
    http_response_code(200);
    echo json_encode([
        "sucesso" => true,
        "mensagem" => "Acesso autorizado."
    ]);
    exit;
    }

    public function home(): void
    {
        require_once __DIR__ . '/../Views/home.php';
    }
}