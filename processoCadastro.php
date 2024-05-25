<?php
// Incluir o arquivo de conexão com o banco de dados
include 'conexao.php';

// Receber os dados do formulário
$username = $_POST['username'];
$password = $_POST['password'];
$email = $_POST['email'];



// Verificar se o usuário já existe no banco de dados
$stmt_verificar_usuario = $conn->prepare("SELECT * FROM usuarios WHERE username = ?");
$stmt_verificar_usuario->bind_param("s", $username);
$stmt_verificar_usuario->execute();
$resultado_verificar_usuario = $stmt_verificar_usuario->get_result();

$stmt_verificar_email = $conn->prepare("SELECT * FROM usuarios WHERE email = ?");
$stmt_verificar_email->bind_param("s", $email);
$stmt_verificar_email->execute();  
$resultado_verificar_email = $stmt_verificar_email->get_result();


if ($resultado_verificar_usuario->num_rows > 0) {
    // Se o usuário já existe, definir a mensagem de erro correspondente
    $response = array('success' => false, 'message' => 'Nome de usuário já existe.');

} 
elseif($resultado_verificar_email->num_rows > 0) {
    $response = array('success' => false, 'message' => 'Email já cadastrado no site');
}
else {
    // Se o usuário não existe, inserir os dados no banco de dados
    $stmt_inserir_usuario = $conn->prepare("INSERT INTO usuarios (username, password, email) VALUES (?, ?, ?)");
    $stmt_inserir_usuario->bind_param("sss", $username, $password, $email);
    $stmt_inserir_usuario->execute();

    if ($stmt_inserir_usuario->affected_rows > 0) {
        // Se a inserção foi bem-sucedida, retorne uma resposta de sucesso
        $response = array('success' => true);
    } else {
        // Se houve algum erro ao inserir no banco de dados, exiba uma mensagem de erro padrão
        $response = array('success' => false, 'message' => 'Erro ao cadastrar usuário.');
    }
}


// Retorna uma resposta em JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
