document.getElementById("loginForm").addEventListener("submit", function(e) {
  e.preventDefault();

  const email = document.getElementById("username").value;
  const senha = document.getElementById("password").value;

  fetch("processa_login.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded"
    },
    body: `email=${email}&senha=${senha}`
  })
  .then(res => res.json())
  .then(data => {
    if (data.status === "ok") {
      window.location.href = data.redirect;
    } else {
      document.getElementById("mensagem").textContent = "Login inválido!";
    }
  });
});