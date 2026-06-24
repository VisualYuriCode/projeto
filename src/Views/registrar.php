<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Conta - Sistema MVC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center" style="height: 100vh;">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow-sm">
                    <div class="card-body p-5">
                        <h3 class="text-center mb-4">Criar Nova Conta</h3>
                        
                        <?php if (isset($_SESSION['erro_cadastro'])): ?>
                            <div class="alert alert-danger text-center shadow-sm">
                                <?= $_SESSION['erro_cadastro']; ?>
                            </div>
                            <?php unset($_SESSION['erro_cadastro']); // Limpa a memória após exibir ?>
                        <?php endif; ?>

                        <?php 
                        // Resgata os dados antigos se existirem
                        $oldNome = $_SESSION['old_nome'] ?? '';
                        $oldEmail = $_SESSION['old_email'] ?? '';
                        
                        // Limpa a memória logo em seguida
                        unset($_SESSION['old_nome'], $_SESSION['old_email']); ?>
                        
                        <form action="/meu-projeto-login/public/registrar" method="POST">

                            <div class="mb-3">
                            <label for="nome" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="nome" name="nome" required placeholder="Seu nome completo" value="<?= htmlspecialchars($oldNome); ?>">
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" class="form-control" id="email" name="email" required placeholder="seu@email.com" value="<?= htmlspecialchars($oldEmail); ?>">
                        </div>
                            
                            <div class="mb-3">
                                <label for="senha" class="form-label">Senha</label>
                                <input type="password" class="form-control" id="senha" name="senha" required placeholder="Crie uma senha segura">
                            </div>

                            <div class="mb-3">
                                <label for="senha_confirmacao" class="form-label">Confirme a Senha</label>
                                <input type="password" class="form-control" id="senha_confirmacao" name="senha_confirmacao" required placeholder="Repita a senha">
                            </div>
                            
                            <button type="submit" class="btn btn-success w-100 mt-4">Cadastrar</button>
                        </form>
                        
                        <div class="text-center mt-4">
                            <a href="/meu-projeto-login/public/" class="text-decoration-none">Já tenho uma conta (Fazer Login)</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>