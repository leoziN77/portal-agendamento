var cadastroForm = document.getElementById('cadastro-form');
var popup = document.getElementById('popup2');
var popupMessage = document.getElementById('popup-message2');
var popupProsseguir = document.getElementById('popup-prosseguir');
var btnSim = document.getElementById('sim');
var btnNao = document.getElementById('nao');
var telefoneInput = document.querySelector("input[name='telefone']");

function formatarTelefone(telefone) {
    telefone = telefone.replace(/\D/g, '').substr(0, 11);

    if (telefone.length === 11) {
        telefone = telefone.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
    } else {
        telefone = telefone.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
    }
    return telefone;
}

telefoneInput.addEventListener('input', function () {
    telefoneInput.value = formatarTelefone(telefoneInput.value);
});

cadastroForm.addEventListener('submit', function (e) {
    e.preventDefault();

    var idade = document.querySelector("input[name='Idade']").value;
    var telefone = telefoneInput.value;
    var data_nascimento = document.querySelector("input[name='data_nascimento']").value;
    var genero = document.querySelector("input[name='genero']:checked");

    var formData = new FormData(cadastroForm);

    fetch('precadastro.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                exibirPopup(data.success, true);
                exibirPopupProsseguir();
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

btnNao.addEventListener('click', function () {
    popupProsseguir.style.display = 'none';
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

function exibirPopupProsseguir() {
    popupProsseguir.style.display = 'block';
}
