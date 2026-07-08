
(async function() {
    const token = localStorage.getItem('token_jwt');

    // 1. Se não tem token, chuta pro login
    if (!token) {
        window.location.href = '/';
        return;
    }

    try {
        // 2. Envia o crachá para a API validar
        const response = await fetch('/api/validar-acesso', {
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + token
            }
        });

        // 3. Se o token for falso ou expirou
        if (response.status === 401) {
            localStorage.removeItem('token_jwt'); 
            window.location.href = '/';
            return;
        }

        // 4. Tudo certo! Mostra o conteúdo da página
        console.log("Acesso Liberado pelo Backend!");
        
        // Pega qualquer elemento da página que tenha a classe 'conteudo-protegido' e exibe
        const conteudo = document.querySelector('.conteudo-protegido');
        if (conteudo) {
            conteudo.classList.remove('d-none');
        }

        // 5. Configura o botão de logout
        const btnLogout = document.getElementById('btn-logout');
        if (btnLogout) {
            btnLogout.addEventListener('click', () => {
                localStorage.removeItem('token_jwt', 'usuario_nome');
                window.location.href = '/';
            });
    }

    } catch (error) {
        window.location.href = '/';
    }
})();