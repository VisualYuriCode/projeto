document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form');

    const alertBox = document.createElement('div');
    alertBox.className = 'alert text-center shadow-sm d-none';
    form.insertBefore(alertBox, form.firstChild);

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        const formData = new FormData(form);

        try {
            const response = await fetch('/registrar', {
                method: 'POST',
                body: formData
            });

            const dados = await response.json();

            if (response.ok && dados.sucesso) {
                alertBox.className = 'alert alert-success text-center shadow-sm';
                alertBox.textContent = dados.mensagem;
                alertBox.classList.remove('d-none');

                setTimeout(() => {
                    window.location.href = '/';
                }, 1500);

            } else {
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