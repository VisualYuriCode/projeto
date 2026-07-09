<?php

namespace App\Controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthApiController
{
    // não sei para que serve isso //
    private function enviarCabecalhos(): void
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }
        header('Content-Type: application/json; charset=utf-8');
    }
    // Função responsavel por processar nosso login //
    public function login(): void
    {
        // ================ Campos a preencher ================== //
        $this->enviarCabecalhos();

        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        // evita sql injection
        $senha = $_POST['senha'] ?? '';
        /* Email e senha são recebidos via POST. sem filtro creio ser um erro*/
      
        // ================ Erro Campos vazios ================== //
        if (!$email || !$senha) {
            http_response_code(400);
            echo json_encode(["sucesso" => false, "mensagem" => "Por favor, preencha todos os campos."]);
            exit;
        }

        // ================ Processa o Login ================== //

        /* transforma a classe UserModel em uma variavel */
        $userModel = new \App\Models\UserModel();

        /* User vira a função findByEmail (metodo de UserModel) volta um "$stmt->fetch();" */
        $user = $userModel->findByEmail($email);

        if ($user && password_verify($senha, $user->senha)) {
            // essa chave fica dentro do .env
            $chave_secreta = getenv('JWT_SECRET') ?: 'chave_padrao_insegura';

            // array payload e para configurar o token do usuario tendo por exemplo "exp" que e o tempo que o token expira, não tenho total certeza mas o $user e o mesmo de cima mas não entendo como ele póde ser $user->email nem tem isso porra 
            $payload = [
                "iss" => "http://localhost",
                "aud" => "http://localhost",
                "iat" => time(),
                "exp" => time() + (60 * 60),
                "uid" => $user->id,
                "email" => $user->email
            ];
            

            // Metodo da FireBase, pega a array payload com as configs e dados do token + chave secreta do .env e esse hs256 que nao sei oque é
            $token_jwt = JWT::encode($payload, $chave_secreta, 'HS256');

            // vai enviar um codigo 200 http, que quer dizer que deu certo
            http_response_code(200);

            // dando certo ele manda um arquivo json com o token de usuario, que vai ficar guardado no navegador
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
            // Caso o login nao exista ou seja invalido, vem para cá.
            http_response_code(401);
            echo json_encode(["sucesso" => false, "mensagem" => "E-mail ou senha incorretos."]);
            exit;
        }
    }

    public function register(): void
    {
        $this->enviarCabecalhos();
        // preenche os dados necessarios para criar um novo usuario no banco de dados, passando ṕor metodos para evitar sqlinjection
        $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        // Nossa senha não precisa de proteção pois vai ser armazenada como hash
        $senha = $_POST['senha'] ?? '';
        $senhaConfirmacao = $_POST['senha_confirmacao'] ?? '';

        // assim que enviar o formulario, começa o processo de correção d   e alguns fatores

        // senha não batem
        if ($senha !== $senhaConfirmacao) {
            http_response_code(400);
            echo json_encode(["sucesso" => false, "mensagem" => "As senhas não coincidem."]);
            exit;
        }
        // senha tem menos de 8 caracteres
        if (strlen($senha) < 8) {
            http_response_code(400);
            echo json_encode(["sucesso" => false, "mensagem" => "A senha deve ter pelo menos 8 caracteres."]);
            exit;
        }

        // senha não tem caractere especia
        if (!preg_match('/[^a-zA-Z0-9]/', $senha)) {
            http_response_code(400);
            echo json_encode(["sucesso" => false, "mensagem" => "A senha deve conter pelo menos um caractere especial."]);
            exit;
        }

        $userModel = new \App\Models\UserModel();

        // confere se o email já foi utilizado, usado antes para parar caso o email seja usado //
        if ($userModel->findByEmail($email)) {
            http_response_code(409);
            echo json_encode(["sucesso" => false, "mensagem" => "Este e-mail já está em uso."]);
            exit;
        }

        // Cria o usuario e manda para o UserModel.php e ele insere os dados no banco de dados //
        if ($userModel->create($nome, $email, $senha)) {
            http_response_code(201);
            echo json_encode(["sucesso" => true, "mensagem" => "Conta criada com sucesso! Faça o seu login."]);
            exit;
        } else {
            // caso alguma porra de erro aconteça vai cair aqui
            http_response_code(500);
            echo json_encode(["sucesso" => false, "mensagem" => "Ocorreu um erro ao criar a conta."]);
            exit;
        }
    }

    // essa eu não entendi mas trabalha junto do js. 
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
}