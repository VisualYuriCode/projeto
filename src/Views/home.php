<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Área Restrita</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="/meu-projeto-login/public/js/authGuard.js"></script>
</head>
<body class="d-none">

    <nav class="navbar navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="#">Meu Sistema MVC</a>
            <div class="d-flex align-items-center text-white">
                <span class="me-3">Olá, <strong><?= htmlspecialchars($_SESSION['usuario_logado']); ?></strong></span>
                
                <a href="/meu-projeto-login/public/logout" class="btn btn-outline-light btn-sm">Sair</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-body p-5 text-center">
                <h1 class="text-success mb-4">✅ Acesso Autorizado!</h1>
                <h4>Você está dentro da área segura do sistema.</h4>
                <p class="text-muted mt-3">Somente usuários com o "crachá" de login ativo podem ver esta página.</p>
            </div>
        </div>
    </div>

</body>
</html>