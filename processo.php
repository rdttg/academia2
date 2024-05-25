<?php
// inclui o arquivo de conexão com o banco de dados
include 'conexao.php';

// Inicia a sessão
session_start();

// Receber os dados do formulário
$username = $_POST['username'];
$password = $_POST['password'];

// consulta SQL pra verificar se o nome de usuário e senha estão corretos
$stmt = $conn->prepare("SELECT * FROM usuarios WHERE username = ? AND password = ?");
$stmt->bind_param("ss", $username, $password);
$stmt->execute();
$resultado = $stmt->get_result();

// Verifica se a consulta retornou algum resultado
if ($resultado->num_rows > 0) {
    // Define variáveis de sessão para armazenar informações do usuário
    $_SESSION['user_username'] = $username;

    // Usuário autenticado com sucesso
    $response = array('success' => true);
} else {
    // Nome de usuário ou senha incorretos
    $response = array('success' => false);
}

// Retorna uma resposta em JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
