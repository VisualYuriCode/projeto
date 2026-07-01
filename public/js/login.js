document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form');
    
    // Criamos uma caixinha de alerta dinâmica para injetar mensagens de erro sem piscar a tela
    const alertBox = document.createElement('div');
    alertBox.className = 'alert text-center shadow-sm d-none';
    form.insertBefore(alertBox, form.firstChild);

    form.addEventListener('submit', async (event) => {
        // PARADA OBRIGATÓRIA: Evita o comportamento padrão do HTML de recarregar a página!
        event.preventDefault(); 

        // Captura os dados digitados nos inputs automaticamente
        const formData = new FormData(form);

        try {
            // Dispara a requisição em segundo plano para o nosso novo controlador de API
            const response = await fetch('/meu-projeto-login/public/api/login', {
                method: 'POST',
                body: formData
            });

            // Converte a resposta enviada pelo PHP em um objeto legível no JavaScript
            const dados = await response.json();

            if (response.ok && dados.sucesso) {
                alertBox.className = 'alert alert-success text-center shadow-sm';
                alertBox.textContent = dados.mensagem;
                alertBox.classList.remove('d-none');

                console.log("Token salvo com sucesso:", dados.token);
                
                localStorage.setItem('token_jwt', dados.token);
                localStorage.setItem('usuario_nome', dados.usuario.nome);

                // Aguarda 1.5 segundos para o utilizador ver a barra verde e redireciona para a Home
                setTimeout(() => {
                    window.location.href = '/meu-projeto-login/public/home';
                }, 1500);

            } else {
                // Se o PHP retornar status 400 ou 401, exibe o erro exato da API
                alertBox.className = 'alert alert-danger text-center shadow-sm';
                alertBox.textContent = dados.mensagem;
                alertBox.classList.remove('d-none');
            }

        } catch (error) {
            alertBox.className = 'alert alert-danger text-center shadow-sm';
            alertBox.textContent = '❌ Falha de comunicação com o servidor backend.';
            alertBox.classList.remove('d-none');
        }
    });
});