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

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: index.html");
    exit;
}

if (isset($_POST["logout"])) {
    logout();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/siteee.css">
    <link rel="shortcut icon" href="assets/Icon.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Consulta</title>
</head>

<body>
    <div class="m-5 custom-margin table-responsive">
        <table class="table custom-table teste">
            <thead>
                <tr style="text-align: center;">
                    <th scope="col">#</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Idade</th>
                    <th scope="col">E-mail</th>
                    <th scope="col">Telefone</th>
                    <th scope="col">Data de Nascimento</th>
                    <th scope="col">Gênero</th>
                    <th scope="col">Médico</th>
                    <th scope="col">Período</th>
                    <th scope="col">Ações</th>
                </tr>
            </thead>
            <tbody style="text-align: center;">
                <?php
                $conn = new mysqli("localhost", "root", "", "login");
                if ($conn->connect_error) {
                    die("Erro na conexão: " . $conn->connect_error);
                }
                $sql = "SELECT u.id, u.name, p.idade, u.email, p.telefone, p.sexo, c.medico, c.periodo, p.data_nascimento
                    FROM pacientes p
                    INNER JOIN users u ON p.user_id = u.id
                    INNER JOIN consultas c ON c.user_id = u.id";

                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    $count = 1;
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>$count</td>";
                        echo "<td>" . ucwords($row["name"]) . "</td>";
                        echo "<td>" . ucwords($row["idade"]) . "</td>";
                        echo "<td>" . $row["email"] . "</td>";
                        echo "<td>" . ucwords($row["telefone"]) . "</td>";
                        echo "<td>" . ucwords($row["data_nascimento"]) . "</td>";
                        echo "<td>" . ucwords($row["sexo"]) . "</td>";
                        echo "<td>" . ucwords($row["medico"]) . "</td>";
                        echo "<td>" . ucwords($row["periodo"]) . "</td>";
                        echo '<td><a href="edit_dados.php?id=' . $row["id"] . '" class="btn btn-primary">Editar</a>
                            <a href="delete.php?id=' . $row["id"] . '" class="btn btn-danger">Excluir</a></td>';
                        echo "</tr>";
                        $count++;
                    }
                } else {
                    echo "Nenhum resultado encontrado.";
                }

                $conn->close();
                ?>
            </tbody>
        </table>
    </div>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <button type="submit" name="logout" class="logout-button">Sair</button>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>