<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: index.html");
    exit;
}

if (!empty($_GET['id'])) {
    include_once('config.php');

    $id = $_GET['id'];

    $sqlUser = "SELECT * FROM users WHERE id=$id";
    $resultUser = $conn->query($sqlUser);

    $sqlPaciente = "SELECT * FROM pacientes WHERE user_id=$id";
    $resultPaciente = $conn->query($sqlPaciente);

    $sqlConsulta = "SELECT * FROM consultas WHERE user_id=$id";
    $resultConsulta = $conn->query($sqlConsulta);

    if ($resultUser->num_rows > 0) {
        $user_data = $resultUser->fetch_assoc();
        $name = $user_data['name'];
    }

    if ($resultPaciente->num_rows > 0) {
        $paciente_data = $resultPaciente->fetch_assoc();
        $idade = $paciente_data['idade'];
        $telefone = $paciente_data['telefone'];
        $data_nascimento = $paciente_data['data_nascimento'];
    }

    if ($resultConsulta->num_rows > 0) {
        $consulta_data = $resultConsulta->fetch_assoc();
        $medico = $consulta_data['medico'];
        $periodo = $consulta_data['periodo'];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['name']) && isset($_POST['idade']) && isset($_POST['telefone']) && isset($_POST['data_nascimento']) && isset($_POST['medico']) && isset($_POST['P'])) {
        $name = $_POST['name'];
        $idade = $_POST['idade'];
        $telefone = $_POST['telefone'];
        $data_nascimento = $_POST['data_nascimento'];
        $medico = $_POST['medico'];
        $periodo = $_POST['P'];

        $conn->begin_transaction();

        try {
            $sqlUser = "UPDATE users SET name=? WHERE id=?";
            $stmtUser = $conn->prepare($sqlUser);
            $stmtUser->bind_param("si", $name, $id);
            $stmtUser->execute();


            $sqlPaciente = "UPDATE pacientes SET idade=?, telefone=?, data_nascimento=? WHERE user_id=?";
            $stmtPaciente = $conn->prepare($sqlPaciente);
            $stmtPaciente->bind_param("issi", $idade, $telefone, $data_nascimento, $id);
            $stmtPaciente->execute();

            $sqlConsulta = "UPDATE consultas SET medico=?, periodo=? WHERE user_id=?";
            $stmtConsulta = $conn->prepare($sqlConsulta);
            $stmtConsulta->bind_param("ssi", $medico, $periodo, $id);
            $stmtConsulta->execute();

            $conn->commit();
        } catch (Exception $e) {
            $conn->rollback();
            echo "Erro: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="assets/Icon.png" type="image/x-icon">
    <link rel="stylesheet" href="ediit.css">
    <title>Consulta</title>
</head>

<body>
    <div class="box">
        <form id="cadastro-form" method="POST">
            <div id="popup2" class="popup" style="display: none;">
                <p id="popup-message2" class="popup-message2"></p>
            </div>
            <fieldset>
                <legend><b>Alterar Dados</b></legend>
                <br>
                <div class="inputBox">
                    <input type="text" name="name" id="name" class="inputUser" required value="<?php echo $name; ?>">
                    <label for="name" class="labelInput">Nome</label>
                </div>
                <br><br>
                <div class="inputBox">
                    <input type="number" name="idade" id="idade" class="inputUser" required maxlength="3" oninput="this.value = this.value.slice(0, 3)" value="<?php echo $idade; ?>">
                    <label for="idade" class="labelInput">Idade</label>
                </div>
                <br><br>
                <div class="inputBox">
                    <input type="tel" name="telefone" id="telefone" class="inputUser" required value="<?php echo $telefone; ?>">
                    <label for="telefone" class="labelInput">Telefone</label>
                </div>
                <br><br>
                <label for="data_nascimento"><b>Data de Nascimento:</b></label>
                <input type="date" name="data_nascimento" id="data_nascimento" required value="<?php echo $data_nascimento; ?>">
                <br><br>
                <p>Médico:</p>
                <div>
                    <input type="radio" class="med-check" id="drRafael" name="medico" value="Dr.Rafael" required <?php if ($medico === 'Dr.Rafael') echo 'checked'; ?> />
                    <label class="med" for="drRafael">Ortopedia - Dr.Rafael</label><br>
                    <input type="radio" class="med-check" id="drAugusto" name="medico" value="Dr.Augusto" required <?php if ($medico === 'Dr.Augusto') echo 'checked'; ?> />
                    <label class="med" for="drAugusto">Cardiologista - Dr.Augusto</label><br>
                    <input type="radio" class="med-check" id="drAna" name="medico" value="Dra.Ana" required <?php if ($medico === 'Dra.Ana') echo 'checked'; ?> />
                    <label class="med" for="drAna">Oftalmologista - Dra.Ana</label><br>
                    <input type="radio" class="med-check" id="drRenata" name="medico" value="Dra.Renata" required <?php if ($medico === 'Dra.Renata') echo 'checked'; ?> />
                    <label class="med" for="drRenata">Dermatologista - Dra.Renata</label><br><br>
                </div>
                <p>Selecione um Horário:</p>
                <div>
                    <input type="radio" class="btn-check" id="manha" name="P" value="manha" autocomplete="off" required <?php if ($periodo === 'manha') echo 'checked'; ?> />
                    <label class="btn" for="manha">Período Manhã: 07:00 - 12:00</label><br>
                    <input type="radio" class="btn-check" id="tarde" name="P" value="tarde" autocomplete="off" required <?php if ($periodo === 'tarde') echo 'checked'; ?> />
                    <label class="btn" for="tarde">Período Tarde: 13:00 - 18:00</label>
                </div>
                <br><br>
                <button type="submit" id="submit">Salvar</button>
            </fieldset>
        </form>
    </div>
    <a href="site2.php" class="button">Voltar</a>

    <script>
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
        telefoneInput.addEventListener('input', function() {
            telefoneInput.value = formatarTelefone(telefoneInput.value);
        });
    </script>
</body>

</html>