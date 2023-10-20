var agendamentoForm = document.getElementById('agendamento-form');
var medicoInputs = document.querySelectorAll("input[name='medico']");
var periodoInputs = document.querySelectorAll("input[name='P']");
var popup = document.getElementById('popup2');
var popupMessage = document.getElementById('popup-message2');

agendamentoForm.addEventListener('submit', function (e) {
    e.preventDefault();

    var medico;

    for (var i = 0; i < medicoInputs.length; i++) {
        if (medicoInputs[i].checked) {
            medico = medicoInputs[i].value;
            break;
        }
    }

    var periodo;

    for (var i = 0; i < periodoInputs.length; i++) {
        if (periodoInputs[i].checked) {
            periodo = periodoInputs[i].value;
            break;
        }
    }

    var formData = new FormData();
    formData.append('medico', medico);
    formData.append('P', periodo);

    fetch('consulta.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                exibirPopup(data.success, true);
            } else {
                exibirPopup(data.error, false);
            }

            setTimeout(function () {
                popup.style.display = 'none';
            }, 4000);
        })
        .catch(error => {
            exibirPopup('Erro na solicitação: ' + error.message, false);
        });
});

function exibirPopup(mensagem, sucesso) {
    popupMessage.textContent = mensagem;
    popup.style.display = 'block';

    if (sucesso) {
        popupMessage.classList.add('success-message');
    } else {
        popupMessage.classList.remove('success-message');
    }
}