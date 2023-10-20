<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta</title>
    <link rel="stylesheet" href="cadastraar.css">
    <link rel="shortcut icon" href="assets/Icon.png" type="image/x-icon">
</head>

<body>
    <div class="page">
        <form method="POST" class="formLogin" id="cadastro-form">
            <h1>Cadastro</h1>
            <div id="popup2" class="popup2" style="display: none;">
                <p id="popup-message2" class="popup-message2"></p>
            </div>
            <p>Digite os seus dados para efetuar um cadastro.</p>
            <label for="name">Nome</label>
            <input type="text" name="name" id="name" placeholder="Digite seu nome" />
            <label for="email">E-mail</label>
            <input type="email" name="email" id="email" placeholder="Digite seu e-mail" autofocus="true" />
            <label for="password">Senha</label>
            <input type="password" name="password" id="password" placeholder="Digite sua senha" />
            <input type="submit" value="Cadastrar" class="btn" />
            <br>
            <p>Já está cadastrado? <a href="login_paciente.php">Acesse agora</a></p>
        </form>
    </div>

    <script src="cadastrar.js"></script>
</body>

</html>