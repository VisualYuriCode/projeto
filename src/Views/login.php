<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema MVC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center" style="height: 100vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow-sm">
                    <div class="card-body p-5">
                        <h3 class="text-center mb-4">Bem-vindo</h3>

                        <form>
                            <div class="mb-3">
                                <label for="email" class="form-label">E-mail</label>
                                <input type="email" class="form-control" id="email" name="email" required placeholder="seu@email.com">
                            </div>
                            <div class="mb-3">
                                <label for="senha" class="form-label">Senha</label>
                                <input type="password" class="form-control" id="senha" name="senha" required placeholder="******">
                            </div>
                            <button type="submit" class="btn btn-primary w-100 mt-3">Entrar</button>
                        </form>

                        <div class="text-center mt-4">
                            <a href="/registrar" class="text-decoration-none">Criar uma conta</a> |
                            <a href="/esqueci-senha" class="text-decoration-none">Esqueci a senha</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script src="/js/login.js"></script>
</body>
</html>