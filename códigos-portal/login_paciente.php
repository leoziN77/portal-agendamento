<?php
session_start();
require_once "config.php";

function logout()
{
    session_unset();
    session_destroy();
    header("Location: index.html");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        if (password_verify($password, $row['password'])) {
            if ($row['first_visit'] == 1) {
                $updateSql = "UPDATE users SET first_visit = 0 WHERE email = ?";
                $updateStmt = $conn->prepare($updateSql);
                $updateStmt->bind_param("s", $email);
                $updateStmt->execute();

                $_SESSION["user_id"] = $row['id'];

                $_SESSION["loggedin"] = true;
                header("Location: site.php?id=" . $row['id']);
                exit;
            } else {
                $_SESSION["user_id"] = $row['id'];

                $_SESSION["loggedin"] = true;
                header("Location: agendamento.php?id=" . $row['id']);

                exit;
            }
        } else {
            $error = "Dados inválidos";
        }
    } else {
        $error = "Dados inválidos";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta</title>
    <link rel="stylesheet" href="style/cadastraar.css">
    <link rel="shortcut icon" href="assets/Icon.png" type="image/x-icon">
</head>

<body>
    <div class="page">
        <form method="POST" class="formLogin" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <h1>Login</h1>
            <?php if (isset($error)) : ?>
                <div id="popup" class="popup">
                    <p id="popup-message" class="popup-message">
                        <?php echo $error; ?>
                    </p>
                </div>
            <?php endif; ?>
            <p>Digite os seus dados de acesso no campo abaixo.</p>
            <label for="email">E-mail</label>
            <input type="email" name="email" id="email" placeholder="Digite seu e-mail" autofocus="true" />
            <label for="password">Senha</label>
            <input type="password" name="password" id="password" placeholder="Digite sua senha" />
            <input type="submit" value="Acessar" class="btn" />
            <br>
            <p>Ainda não tem uma conta? <a href="cadastrar_paciente.php">Crie uma agora</a></p>
        </form>
    </div>

    <script>
        var popup = document.getElementById("popup");

        setTimeout(function() {
            popup.style.display = "none";
        }, 4000);
    </script>
</body>

</html>