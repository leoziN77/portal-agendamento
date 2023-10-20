var cadastroForm = document.getElementById('cadastro-form');
var popup = document.getElementById('popup2');
var popupMessage = document.getElementById('popup-message2');

cadastroForm.addEventListener('submit', function (e) {
    e.preventDefault();

    var name = document.querySelector("input[name='name']").value;
    var email = document.querySelector("input[name='email']").value;
    var password = document.querySelector("input[name='password']").value;

    if (name.trim() === '' || email.trim() === '' || password.trim() === '') {
        exibirPopup('Dados inválidos', false);
        return;
    }

    var formData = new FormData(cadastroForm);

    fetch('cadastro.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                exibirPopup('Cadastro efetuado com sucesso!', true);
            } else {
                exibirPopup(data.message, false);
            }
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

    setTimeout(function () {
        popup.style.display = 'none';
    }, 4000);
}
