<?php

namespace App\Controllers;

class PasswordController
{
    // Exibe o formulário de esqueci a senha (GET /esqueci-senha)
    public function forgotForm(): void
    {
        require_once __DIR__ . '/../Views/esqueci-senha.php';
    }

    // Processa o pedido de recuperação (POST /esqueci-senha)
    public function forgotProcess(): void
    {
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

        // Instanciamos o Model para checar se o email existe
        $userModel = new \App\Models\UserModel();

        if ($userModel->findByEmail($email)) {
            // No futuro, aqui usaremos o Composer para disparar um e-mail real!
            // Por enquanto, simulamos o sucesso salvando na sessão.
            $_SESSION['sucesso_senha'] = "✅ Se o e-mail existir, um link de recuperação foi enviado para ele.";
        } else {
            // Por segurança genérica de mercado, mesmo se não achar, simulamos o sucesso 
            // para evitar que invasores descubram quais e-mails estão cadastrados!
            $_SESSION['sucesso_senha'] = "✅ Se o e-mail existir, um link de recuperação foi enviado para ele.";
        }

        header("Location: /esqueci-senha");
        exit;
    }


}


