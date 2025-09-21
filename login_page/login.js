document.getElementById("loginForm").addEventListener("submit", function(e) {
  e.preventDefault()

  const usuario = document.getElementById("username").value
  const senha = document.getElementById("password").value

  const usuarioCorreto = "admin"
  const senhaCorreta = "1234"

  if (usuario === usuarioCorreto && senha === senhaCorreta) {
    window.location.href = "../index.html"
  } else {
    const mensagem = document.getElementById("mensagem")
    mensagem.style.color = "red";
    mensagem.textContent = "Usuário ou senha incorretos!"
  }
});


const loginForm = document.getElementById("loginForm")
const registerForm = document.getElementById("registerForm")

document.getElementById("showRegister").addEventListener("click", function(e) {
  e.preventDefault()
  loginForm.style.display = "none"
  registerForm.style.display = "block"
});

document.getElementById("showLogin").addEventListener("click", function(e) {
  e.preventDefault()
  registerForm.style.display = "none"
  loginForm.style.display = "block"
});

// REGISTRO (simulação)
document.getElementById("registerForm").addEventListener("submit", function(e) {
  e.preventDefault()

  const nome = document.getElementById("newName").value
  const email = document.getElementById("newEmail").value
  const senha = document.getElementById("newPassword").value

  alert(`Usuário registrado!\nNome: ${nome}\nEmail: ${email}`)
  
  // Depois de registrar, volta para a tela de login
  registerForm.style.display = "none"
  loginForm.style.display = "block"
});
