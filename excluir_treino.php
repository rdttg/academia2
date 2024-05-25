<?php
// Inclui o arquivo de conexão com o banco de dados
include 'conexao.php';

// Inicia a sessão
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['user_username'])) {
    // Redireciona para a página de login se não estiver logado
    header("Location: login.html");
    exit();
}

// Verifica se o ID do treino foi passado via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $username = $_SESSION['user_username'];

    // Prepara e executa a query para excluir o treino
    $stmt = $conn->prepare("DELETE FROM treinos WHERE id = ? AND username = ?");
    $stmt->bind_param("is", $id, $username);

    if ($stmt->execute()) {
        echo "Treino excluído com sucesso!";
    } else {
        echo "Erro ao excluir o treino: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    // Redireciona de volta para a página de treinos
    header("Location: treinos.php");
    exit();
} else {
    echo "ID do treino não fornecido.";
}
?>
