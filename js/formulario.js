document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById('form-sugestao');
    
    if (form) {
        form.addEventListener('submit', handleFormSubmit);
    }
});

async function handleFormSubmit(e) {
    e.preventDefault(); // Impede a página de recarregar

    const form = e.target;
    const formData = new FormData(form);
    const btnSubmit = form.querySelector('.btn-submit');
    const msgDiv = document.getElementById('msg-feedback');

    // Resetar mensagens
    if(msgDiv) {
        msgDiv.style.display = 'none';
        msgDiv.className = 'alert';
    }

    try {
        // Bloquear botão
        btnSubmit.disabled = true;
        const textoOriginal = btnSubmit.textContent;
        btnSubmit.textContent = "A enviar...";

        const response = await fetch('../api/enviar_sugestao.php', {
            method: 'POST',
            body: formData
        });

        const text = await response.text();
        const parts = text.trim().split('|'); // SUCCESS|Mensagem

        if (parts[0] === 'SUCCESS') {
            // SUCESSO
            form.reset(); // Limpa o formulário
            
            if (msgDiv) {
                msgDiv.textContent = parts[1];
                msgDiv.classList.add('sucesso'); // Classe CSS verde
                msgDiv.style.display = 'block';
            } else {
                alert(parts[1]);
            }

        } else {
            // ERRO
            if (msgDiv) {
                msgDiv.textContent = parts[1] || "Ocorreu um erro desconhecido.";
                msgDiv.classList.add('erro'); // Classe CSS vermelha
                msgDiv.style.display = 'block';
            } else {
                alert(parts[1]);
            }
        }

        // Restaurar botão
        btnSubmit.disabled = false;
        btnSubmit.textContent = textoOriginal;

    } catch (error) {
        console.error('Erro:', error);
        if (msgDiv) {
            msgDiv.textContent = "Erro de conexão ao servidor.";
            msgDiv.classList.add('erro');
            msgDiv.style.display = 'block';
        }
        btnSubmit.disabled = false;
    }
}