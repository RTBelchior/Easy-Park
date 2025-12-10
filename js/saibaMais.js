window.addEventListener("load", () => {
    const content = document.getElementById("content");
    if (content) {
        setTimeout(() => {
            content.classList.add("visible");
        }, 200);
    }

    // Selecionar o botão pela classe .voltar
    const btnVoltar = document.querySelector(".voltar");
    
    if (btnVoltar) {
        btnVoltar.addEventListener("click", () => {
            history.back();
        });
    }
});