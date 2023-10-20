const pacienteCard = document.getElementById("paciente-card");
const medicoCard = document.getElementById("medico-card");

pacienteCard.addEventListener("click", () => {
  window.location.href = "login_paciente.php";
});

medicoCard.addEventListener("click", () => {
  window.location.href = "login_medico.php";
});