<?php
require_once "config.php";

$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $checkEmailSQL = "SELECT id FROM users WHERE email = ?";
    $checkEmailStmt = $conn->prepare($checkEmailSQL);
    $checkEmailStmt->bind_param("s", $email);
    $checkEmailStmt->execute();
    $checkEmailStmt->store_result();

    if ($checkEmailStmt->num_rows > 0) {
        $response['success'] = false;
        $response['message'] = "Este email já está cadastrado.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $insertSQL = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insertSQL);
        $stmt->bind_param("sss", $name, $email, $hashed_password);

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = "Cadastro efetuado com sucesso!";
        } else {
            $response['success'] = false;
            $response['message'] = "Erro: " . $insertSQL . "<br>" . $conn->error;
        }
        $stmt->close();
    }

    $checkEmailStmt->close();
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
