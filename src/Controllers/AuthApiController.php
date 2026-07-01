<?php

namespace App\Controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthApiController
{
    public function login(): void
    {
        // (Mantenha os cabeçalhos de CORS e JSON que configurámos no Passo 3...)
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
        
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }
        header('Content-Type: application/json; charset=utf-8');

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
            
            // --- CONFIGURAÇÃO DO JWT ---
            // 1. A Chave Secreta: É a assinatura do seu servidor. NUNCA a revele a ninguém!
            $chave_secreta = "SUA_CHAVE_SUPER_SECRETA_E_CONFIANCA_123456";
            
            // 2. O Payload: Os dados públicos do utilizador que vão viajar dentro do token
            $payload = [
                "iss" => "http://localhost",                  // Quem emitiu o token
                "aud" => "http://localhost",                  // Quem tem permissão de usar
                "iat" => time(),                              // Momento exato em que o token foi criado
                "exp" => time() + (60 * 60),                  // Tempo de expiração (1 hora de validade)
                "uid" => $user->id,                           // ID do utilizador vindo do banco
                "email" => $user->email                       // E-mail do utilizador
            ];

            // 3. A Criptografia: Codificamos e assinamos o token usando o algoritmo HS256
            $token_jwt = JWT::encode($payload, $chave_secreta, 'HS256');
            // ----------------------------

            // STATUS 200: Sucesso! Devolvemos os dados e o TOKEN gerado
            http_response_code(200);
            echo json_encode([
                "sucesso" => true,
                "mensagem" => "Login autorizado com sucesso!",
                "token" => $token_jwt, // <-- O Frontend vai apanhar isto aqui!
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

    public function home(): void 
    {
        require_once __DIR__ . '/../Views/home.php';
    }
}


