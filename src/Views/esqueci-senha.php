<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Senha - Sistema MVC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center" style="height: 100vh;">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow-sm">
                    <div class="card-body p-5">
                        <h3 class="text-center mb-4">Recuperar Senha</h3>
                        <p class="text-muted text-center mb-4">Digite o seu e-mail cadastrado para receber as instruções de recuperação.</p>
                        
                        <?php if (isset($_SESSION['erro_senha'])): ?>
                            <div class="alert alert-danger text-center shadow-sm"><?= $_SESSION['erro_senha']; ?></div>
                            <?php unset($_SESSION['erro_senha']); ?>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['sucesso_senha'])): ?>
                            <div class="alert alert-success text-center shadow-sm"><?= $_SESSION['sucesso_senha']; ?></div>
                            <?php unset($_SESSION['sucesso_senha']); ?>
                        <?php endif; ?>
                        
                        <form action="/meu-projeto-login/public/esqueci-senha" method="POST">
                            <div class="mb-3">
                                <label for="email" class="form-label">E-mail Cadastrado</label>
                                <input type="email" class="form-control" id="email" name="email" required placeholder="seu@email.com">
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100 mt-3">Enviar Link de Recuperação</button>
                        </form>
                        
                        <div class="text-center mt-4">
                            <a href="/meu-projeto-login/public/" class="text-decoration-none">Voltar para o Login</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>