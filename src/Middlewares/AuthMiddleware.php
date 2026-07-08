<?php

namespace App\Middlewares;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

class AuthMiddleware 
{
    private string $chaveSecreta;

    public function __construct()
    {
        $this->chaveSecreta = getenv('JWT_SECRET') ?: 'chave_padrao_insegura';
    }

    public function handle(): void 
    {
        $headers = getallheaders();
        
        // 1. Verifica se o cabeçalho de Autorização foi enviado
        if (!isset($headers['Authorization'])) {
            http_response_code(401);
            echo json_encode(["sucesso" => false, "mensagem" => "Acesso negado. Token ausente."]);
            exit; // 💥 Barra a execução aqui! O Controller nunca será chamado.
        }

        // 2. Extrai o token
        $token = str_replace('Bearer ', '', $headers['Authorization']);

        try {
            // 3. Tenta decodificar e validar a assinatura com o Firebase
            $dadosDecodificados = JWT::decode($token, new Key($this->chaveSecreta, 'HS256'));
            
            // Se chegou aqui, o token é válido! 
            // O Middleware não faz mais nada e deixa a requisição passar para o Controller.
            
        } catch (Exception $e) {
            // Se o token for inventado, adulterado ou expirado:
            http_response_code(401);
            echo json_encode([
                "sucesso" => false, 
                "mensagem" => "Acesso negado. Token inválido ou expirado."
            ]);
            exit; // 💥 Barra a execução!
        }
    }
}