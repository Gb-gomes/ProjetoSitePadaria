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

function enviarEmail() {
    const nome = document.getElementById("nome").value;
    const email = document.getElementById("email").value;
    const mensagem = document.getElementById("mensagem").value;

    const assunto = `Mensagem de ${nome}`;
    const corpo = `Nome: ${nome}%0AEmail: ${email}%0A%0A${mensagem}`;

    window.open(
        `https://mail.google.com/mail/?view=cm&fs=1&to=seuemail@gmail.com&su=${assunto}&body=${corpo}`,
        "_blank"
    );
}