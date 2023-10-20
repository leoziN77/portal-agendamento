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
    //user: admin; email: admin@gmail.com; password: admin
    if ($email === "admin@gmail.com" && $password === "admin") {
        $_SESSION["loggedin"] = true;
        header("Location: site2.php");
        exit;
    } else {
        $conn = new mysqli("localhost", "root", "", "login");
        if ($conn->connect_error) {
            die("Erro na conexão: " . $conn->connect_error);
        }

        $sql = "SELECT * FROM users WHERE email = ? AND password = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $_SESSION["loggedin"] = true;
            $_SESSION["user_id"] = $user["id"];
            header("Location: site2.php");
            exit;
        } else {
            $error = "Email inválido para acesso";
        }

        $conn->close();
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