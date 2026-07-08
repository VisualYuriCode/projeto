<?php

namespace App\Controllers;

use App\Models\UserModel; // Importamos o Model que criámos!

class AuthController
{
    // Exibe a interface de Login (GET /)
    public function loginForm(): void
    {
        require_once __DIR__ . '/../Views/login.php';
    }

    // Processa os dados enviados pelo formulário de Login (POST /login)
    public function login(): void
    {
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $senha = $_POST['senha'] ?? '';

        $userModel = new UserModel();
        $utilizador = $userModel->findByEmail($email);

        if ($utilizador && password_verify($senha, $utilizador->senha)) {
            
            // SUCESSO: O usuário acertou a senha!
            // Criamos o "crachá" dele guardando o email na Sessão
            $_SESSION['usuario_logado'] = $utilizador->email;
            
            // Redirecionamos ele para a rota da Home
            header("Location: /home");
            exit;
            
        } else {
            // FALHA: Salva o erro na Sessão e volta pro Login
            $_SESSION['erro_login'] = "❌ E-mail ou senha incorretos.";
            header("Location: /");
            exit;
        }
    }

    // Exibe a tela restrita após o login (GET /home)
     public function home(): void
    {
        // O SEGURANÇA DA ROTA PROTEGIDA:
        // Se a variável 'usuario_logado' NÃO existir na sessão, ele não tem o crachá!
       /* if (!isset($_SESSION['usuario_logado'])) {
            
            // Manda de volta para o login com uma mensagem de aviso
            $_SESSION['erro_login'] = "⚠️ Você precisa fazer login para acessar essa página.";
            header("Location: /");
            exit;
        }

        // Se o código chegou até aqui, significa que ele tem o crachá. 
        // Então, podemos carregar a tela (View) da Home */
        require_once __DIR__ . '/../Views/home.php'; 
    } 
    
    // Exibe o formulário de Cadastro (GET /registrar)
    public function registerForm(): void 
    { 
        require_once __DIR__ . '/../Views/registrar.php'; 
    }

    // Processa os dados enviados pelo formulário de Cadastro (POST /registrar)
    public function register(): void
    {
        // 1. Receber e limpar os dados
        $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $senha = $_POST['senha'] ?? '';
        $senhaConfirmacao = $_POST['senha_confirmacao'] ?? '';

        // 2. Validação: As palavras-passe coincidem?
        if ($senha !== $senhaConfirmacao) {
            $_SESSION['erro_cadastro'] = "❌ As senhas não coincidem.";
            $_SESSION['old_nome'] = $nome;   // <-- SALVANDO O NOME
            $_SESSION['old_email'] = $email;
            header("Location: /registrar");
            exit;
        }

        // B. Tem pelo menos 8 caracteres?
        if (strlen($senha) < 8) {
            $_SESSION['erro_cadastro'] = "❌ A senha deve ter pelo menos 8 caracteres.";
            $_SESSION['old_nome'] = $nome;   // <-- SALVANDO O NOME
            $_SESSION['old_email'] = $email; // <-- SALVANDO O E-MAIL
            header("Location: /registrar");
            exit;
        }

        // C. Tem pelo menos um carácter especial?
        // Usamos uma expressão regular (Regex) para procurar qualquer coisa que NÃO seja letra ou número
        if (!preg_match('/[^a-zA-Z0-9]/', $senha)) {
            $_SESSION['erro_cadastro'] = "❌ A senha deve conter pelo menos um carácter especial (ex: @, #, $, %, etc).";
            $_SESSION['old_nome'] = $nome;   // <--- E AQUI TAMBÉM
            $_SESSION['old_email'] = $email;
            header("Location: /registrar");
            exit;
        }

        // Instanciamos o Model para falar com a base de dados
        $userModel = new \App\Models\UserModel();

        // 3. Validação: O e-mail já está registado?
        if ($userModel->findByEmail($email)) {
            $_SESSION['erro_cadastro'] = "❌ Este e-mail já está em uso.";
            $_SESSION['old_nome'] = $nome;   // <-- SALVANDO O NOME
            $_SESSION['old_email'] = $email;
            header("Location: /registrar");
            exit;
        }

        // 4. Sucesso: Criar o utilizador
        if ($userModel->create($nome, $email, $senha)) {
            $_SESSION['sucesso_login'] = "✅ Conta criada com sucesso! Faça o seu login.";
            header("Location: /");
            exit;
        } else {
            // Se algo falhar na base de dados
            $_SESSION['erro_cadastro'] = "❌ Ocorreu um erro ao criar a conta.";
            $_SESSION['old_nome'] = $nome;   // <-- SALVANDO O NOME
            $_SESSION['old_email'] = $email;
            header("Location: /registrar");
            exit;
        }
    }

    public function logout(): void
    {
    $_SESSION = []; // Limpa os dados primeiro

    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    session_destroy();
    header("Location: /");
    exit;
    }

    
}