
(async function() {
    const token = localStorage.getItem('token_jwt');

    // 1. Se não tem token, chuta pro login
    if (!token) {
        window.location.href = '/meu-projeto-login/public/';
        return;
    }

    try {
        // 2. Envia o crachá para a API validar
        const response = await fetch('/meu-projeto-login/public/api/validar-acesso', {
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + token
            }
        });

        // 3. Se o token for falso ou expirou
        if (response.status === 401) {
            localStorage.removeItem('token_jwt'); 
            window.location.href = '/meu-projeto-login/public/';
            return;
        }

        // 4. Tudo certo! Mostra o conteúdo da página
        console.log("Acesso Liberado pelo Backend!");
        
        // Pega qualquer elemento da página que tenha a classe 'conteudo-protegido' e exibe
        const conteudo = document.querySelector('.conteudo-protegido');
        if (conteudo) {
            conteudo.classList.remove('d-none');
        }

    } catch (error) {
        window.location.href = '/meu-projeto-login/public/';
    }
})();