<?php
session_start();

require_once "config.php";

$successMessage = "";
$errorMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_SESSION["user_id"])) {
        $user_id = $_SESSION["user_id"];

        if (isset($_POST['medico']) && isset($_POST['P'])) {
            $medico = $_POST['medico'];
            $periodo = $_POST['P'];

            $checkSQL = "SELECT id FROM consultas WHERE user_id = ?";
            $checkStmt = $conn->prepare($checkSQL);
            $checkStmt->bind_param("i", $user_id);
            $checkStmt->execute();
            $checkResult = $checkStmt->get_result();

            if ($checkResult->num_rows > 0) {
                $updateSQL = "UPDATE consultas SET medico = ?, periodo = ? WHERE user_id = ?";
                $updateStmt = $conn->prepare($updateSQL);
                $updateStmt->bind_param("ssi", $medico, $periodo, $user_id);

                if ($updateStmt->execute()) {
                    $successMessage = "Agendamento atualizado com sucesso!";
                } else {
                    $errorMessage = "Erro na atualização: " . $updateStmt->error;
                }
                $updateStmt->close();
            } else {

                $insertSQL = "INSERT INTO consultas (user_id, medico, periodo) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($insertSQL);
                $stmt->bind_param("iss", $user_id, $medico, $periodo);

                if ($stmt->execute()) {
                    $successMessage = "Agendamento realizado com sucesso!";
                } else {
                    $errorMessage = "Erro na inserção: " . $stmt->error;
                }
                $stmt->close();
            }
            $checkStmt->close();
        } else {
            $errorMessage = "Por favor, selecione um médico e um período para agendar uma consulta.";
        }
    } else {
        $errorMessage = "Erro: user_id não está definido na sessão.";
    }
}

$conn->close();

echo json_encode(["success" => $successMessage, "error" => $errorMessage]);
