<?php
// dados do bd
$servername = "localhost";
$username = "root"; // Nome de usuário padrão do XAMPP
$password = ""; // Senha padrão vazia do XAMPP
$database = "academia"; // Nome do seu banco de dados

// criando conexao
$conn = new mysqli($servername, $username, $password, $database);

// Verifica se a conexão foi estabelecida com sucesso
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// fechar conexao dps
// $conn->close();
?>
