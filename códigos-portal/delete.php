<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: index.html");
    exit;
}

if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
    $user_id = $_GET["id"];
    
    $conn = new mysqli("localhost", "root", "", "login");
    if ($conn->connect_error) {
        die("Erro na conexão: " . $conn->connect_error);
    }
    
    $sql_delete_pacientes = "DELETE FROM pacientes WHERE user_id = $user_id";
    $sql_delete_consultas = "DELETE FROM consultas WHERE user_id = $user_id";
    $sql_delete_users = "DELETE FROM users WHERE id = $user_id";
    
    if ($conn->query($sql_delete_pacientes) === TRUE &&
        $conn->query($sql_delete_consultas) === TRUE &&
        $conn->query($sql_delete_users) === TRUE) {
        header("Location: site2.php");
    } else {
        echo "Erro ao excluir o registro: " . $conn->error;
    }
    
    $conn->close();
} else {
    echo "ID inválido.";
}
