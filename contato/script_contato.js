document.getElementById("formContato").addEventListener("submit", function (event) {
    event.preventDefault();

    const msg = document.getElementById("mensagem-sucesso");
    msg.textContent = "✅ Mensagem enviada com sucesso! Em breve entraremos em contato.";
    msg.style.opacity = "1"; // anima a aparição

    this.reset();

    setTimeout(() => {
        msg.style.opacity = "0"; // desaparece suavemente
    }, 4000);
});