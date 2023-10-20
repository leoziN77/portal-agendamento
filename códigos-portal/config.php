<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "login";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
    // } else {
    //     if ($conn->connect_errno === 0) {
    //         echo "Conexão bem-sucedida!";
    //     } else {
    //         echo "Conexão falhou com erro: " . $conn->connect_error;
    //     }
}
