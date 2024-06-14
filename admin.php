<?php
session_start();
include "conexao.php"; 

if (!isset($_SESSION['user_username'])) {
    header("Location: login.html");
    exit();
}

// Verifica se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Adicionar treino
    if (isset($_POST['username']) && isset($_POST['nome_treino']) && isset($_POST['descricao_treino'])) {
        $username = $_POST['username'];
        $nome_treino = $_POST['nome_treino'];
        $descricao_treino = $_POST['descricao_treino'];

        // Prepara a consulta para inserir o treino
        $stmt = $conn->prepare("INSERT INTO treinos (username, nome, descricao) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $nome_treino, $descricao_treino);

        if ($stmt->execute()) {
            echo "<p class='success'>Treino adicionado com sucesso!</p>";
        } else {
            echo "<p class='error'>Erro ao adicionar o treino: " . $stmt->error . "</p>";
        }

        $stmt->close();
    }

    // Adicionar exercício
    if (isset($_POST['exercicio_nome']) && isset($_POST['exercicio_descricao']) && isset($_POST['treino_id'])) {
        $exercicio_nome = $_POST['exercicio_nome'];
        $exercicio_descricao = $_POST['exercicio_descricao'];
        $treino_id = $_POST['treino_id'];

        // Prepara a consulta para inserir o exercício
        $stmt = $conn->prepare("INSERT INTO exercicios (treino_id, nome, descricao) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $treino_id, $exercicio_nome, $exercicio_descricao);

        if ($stmt->execute()) {
            echo "<p class='success'>Exercício salvo com sucesso!</p>";
        } else {
            echo "<p class='error'>Erro ao salvar o exercício: " . $stmt->error . "</p>";
        }

        $stmt->close();
    }

    // Remover treino
    if (isset($_POST['delete_treino'])) {
        $treino_id = $_POST['delete_treino'];

        // Prepara a consulta para remover o treino
        $stmt = $conn->prepare("DELETE FROM treinos WHERE id = ?");
        $stmt->bind_param("i", $treino_id);

        if ($stmt->execute()) {
            echo "<p class='success'>Treino removido com sucesso!</p>";
        } else {
            echo "<p class='error'>Erro ao remover o treino: " . $stmt->error . "</p>";
        }

        $stmt->close();
    }

    // Editar treino
    if (isset($_POST['edit_treino_id']) && isset($_POST['edit_nome_treino']) && isset($_POST['edit_descricao_treino'])) {
        $treino_id = $_POST['edit_treino_id'];
        $novo_nome_treino = $_POST['edit_nome_treino'];
        $nova_descricao_treino = $_POST['edit_descricao_treino'];

        // Prepara a consulta para editar o treino
        $stmt = $conn->prepare("UPDATE treinos SET nome = ?, descricao = ? WHERE id = ?");
        $stmt->bind_param("ssi", $novo_nome_treino, $nova_descricao_treino, $treino_id);

        if ($stmt->execute()) {
            echo "<p class='success'>Treino editado com sucesso!</p>";
        } else {
            echo "<p class='error'>Erro ao editar o treino: " . $stmt->error . "</p>";
        }

        $stmt->close();
    }

    // Editar exercício
    if (isset($_POST['edit_exercicio_id']) && isset($_POST['edit_exercicio_nome']) && isset($_POST['edit_exercicio_descricao'])) {
        $exercicio_id = $_POST['edit_exercicio_id'];
        $novo_nome_exercicio = $_POST['edit_exercicio_nome'];
        $nova_descricao_exercicio = $_POST['edit_exercicio_descricao'];

        // Prepara a consulta para editar o exercício
        $stmt = $conn->prepare("UPDATE exercicios SET nome = ?, descricao = ? WHERE id = ?");
        $stmt->bind_param("ssi", $novo_nome_exercicio, $nova_descricao_exercicio, $exercicio_id);

        if ($stmt->execute()) {
            echo "<p class='success'>Exercício editado com sucesso!</p>";
        } else {
            echo "<p class='error'>Erro ao editar o exercício: " . $stmt->error . "</p>";
        }

        $stmt->close();
    }

    // Pesquisar usuário
    if (isset($_POST['search_user'])) {
        $search_term = $_POST['search_user'];

        // Consulta para buscar usuários com base no termo de pesquisa
        $sql = "SELECT id, username FROM usuarios WHERE username LIKE ?";
        $stmt = $conn->prepare($sql);
        $search_param = "%{$search_term}%";
        $stmt->bind_param('s', $search_param);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Exibir resultados da pesquisa
            echo "<h2>Usuários encontrados:</h2>";
            echo "<ul>";
            while ($row = $result->fetch_assoc()) {
                echo "<li><a href='admin.php?user_id=" . $row['id'] . "'>" . $row['username'] . "</a></li>";
            }
            echo "</ul>";
        } else {
            echo "<p>Nenhum usuário encontrado com o termo de pesquisa '{$search_term}'</p>";
        }

        $stmt->close();
    }
}

// Se um usuário específico foi selecionado, mostrar formulário para adicionar treino e exercícios
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
    $user_name = '';

    // Consulta para obter o nome do usuário
    $sql = "SELECT username FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $user_name = $row['username'];
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administração de Treinos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }
        h1, h2, h3 {
            color: #333;
        }
        form {
            margin-bottom: 20px;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        input[type="text"], select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .btn-back {
            display: inline-block;
            margin-bottom: 20px;
            color: #007bff;
            text-decoration: none;
            border: 1px solid #007bff;
            padding: 10px 15px;
            border-radius: 4px;
        }
        .btn-back:hover {
            background-color: #007bff;
            color: white;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        ul li {
            margin-bottom: 10px;
        }
        ul li a {
            text-decoration: none;
            color: #007bff;
        }
        ul li a:hover {
            text-decoration: underline;
        }
        .success {
            color: green;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <a href="HOME.php" class="btn-back">Voltar para HOME</a>
    <h1>Administração de Treinos</h1>

    <h2>Adicionar Treino para <?php echo $user_name; ?></h2>
    <form method="post" action="">
        <input type="hidden" name="username" value="<?php echo $user_name; ?>">
        <label for="nome_treino">Nome do Treino:</label>
        <input type="text" id="nome_treino" name="nome_treino" required>
        <label for="descricao_treino">Descrição do Treino:</label>
        <input type="text" id="descricao_treino" name="descricao_treino" required>
        <button type="submit">Adicionar Treino</button>
    </form>

    <h2>Adicionar Exercício</h2>
    <form method="post" action="">
        <label for="treino_id">Treino:</label>
        <select id="treino_id" name="treino_id">
        <?php
        // Consulta para obter treinos do usuário selecionado
        $sql = "SELECT id, nome FROM treinos WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $user_name);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<option value='{$row['id']}'>{$row['nome']}</option>";
            }
        } else {
            echo "<option disabled selected>Nenhum treino encontrado</option>";
        }
        ?>
        </select>
        <label for="exercicio_nome">Nome do Exercício:</label>
        <input type="text" id="exercicio_nome" name="exercicio_nome" required>
        <label for="exercicio_descricao">Descrição do Exercício:</label>
        <input type="text" id="exercicio_descricao" name="exercicio_descricao" required>
        <button type="submit">Adicionar Exercício</button>
    </form>

    <h2>Remover Treino</h2>
    <form method="post" action="">
        <label for="delete_treino">Selecione o treino a ser removido:</label>
        <select id="delete_treino" name="delete_treino">
        <?php
        // Consulta para obter treinos do usuário selecionado
        $sql = "SELECT id, nome FROM treinos WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $user_name);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<option value='{$row['id']}'>{$row['nome']}</option>";
            }
        } else {
            echo "<option disabled selected>Nenhum treino encontrado</option>";
        }
        ?>
        </select>
        <button type="submit">Remover Treino</button>
    </form>

    <h2>Editar Treino</h2>
    <form method="post" action="">
        <label for="edit_treino_id">Selecione o treino a ser editado:</label>
        <select id="edit_treino_id" name="edit_treino_id">
        <?php
        // Consulta para obter treinos do usuário selecionado
        $sql = "SELECT id, nome FROM treinos WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $user_name);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<option value='{$row['id']}'>{$row['nome']}</option>";
            }
        } else {
            echo "<option disabled selected>Nenhum treino encontrado</option>";
        }
        ?>
        </select>
        <label for="edit_nome_treino">Novo Nome do Treino:</label>
        <input type="text" id="edit_nome_treino" name="edit_nome_treino" required>
        <label for="edit_descricao_treino">Nova Descrição do Treino:</label>
        <input type="text" id="edit_descricao_treino" name="edit_descricao_treino" required>
        <button type="submit">Editar Treino</button>
    </form>

    <h2>Editar Exercício</h2>
    <form method="post" action="">
        <label for="edit_exercicio_id">Selecione o exercício a ser editado:</label>
        <select id="edit_exercicio_id" name="edit_exercicio_id">
        <?php
        // Consulta para obter exercícios do usuário selecionado
        $sql = "SELECT e.id, e.nome 
                FROM exercicios e 
                JOIN treinos t ON e.treino_id = t.id 
                WHERE t.username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $user_name);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<option value='{$row['id']}'>{$row['nome']}</option>";
            }
        } else {
            echo "<option disabled selected>Nenhum exercício encontrado</option>";
        }
        ?>
        </select>
        <label for="edit_exercicio_nome">Novo Nome do Exercício:</label>
        <input type="text" id="edit_exercicio_nome" name="edit_exercicio_nome" required>
        <label for="edit_exercicio_descricao">Nova Descrição do Exercício:</label>
        <input type="text" id="edit_exercicio_descricao" name="edit_exercicio_descricao" required>
        <button type="submit">Editar Exercício</button>
    </form>

    <h2>Treinos e Exercícios de <?php echo $user_name; ?></h2>
    <?php
    // Consulta para obter todos os treinos do usuário
    $sql = "SELECT id, nome, descricao FROM treinos WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $user_name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($treino = $result->fetch_assoc()) {
            echo "<h3>Treino: " . $treino['nome'] . "</h3>";
            echo "<p>Descrição: " . $treino['descricao'] . "</p>";

            // Consulta para obter exercícios do treino
            $sql_exercicios = "SELECT id, nome, descricao FROM exercicios WHERE treino_id = ?";
            $stmt_exercicios = $conn->prepare($sql_exercicios);
            $stmt_exercicios->bind_param('i', $treino['id']);
            $stmt_exercicios->execute();
            $result_exercicios = $stmt_exercicios->get_result();

            if ($result_exercicios->num_rows > 0) {
                echo "<ul>";
                while ($exercicio = $result_exercicios->fetch_assoc()) {
                    echo "<li>" . $exercicio['nome'] . ": " . $exercicio['descricao'] . "</li>";
                }
                echo "</ul>";
            } else {
                echo "<p>Nenhum exercício encontrado para este treino.</p>";
            }
            $stmt_exercicios->close();
        }
    } else {
        echo "<p>Nenhum treino encontrado para este usuário.</p>";
    }
    $stmt->close();
    ?>
</body>
</html>
<?php
} else {
    // Se nenhum usuário foi selecionado, mostrar apenas o formulário de pesquisa e a lista de todos os usuários
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administração de Treinos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }
        h1, h2, h3 {
            color: #333;
        }
        form {
            margin-bottom: 20px;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        input[type="text"], select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .btn-back {
            display: inline-block;
            margin-bottom: 20px;
            color: #007bff;
            text-decoration: none;
            border: 1px solid #007bff;
            padding: 10px 15px;
            border-radius: 4px;
        }
        .btn-back:hover {
            background-color: #007bff;
            color: white;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        ul li {
            margin-bottom: 10px;
        }
        ul li a {
            text-decoration: none;
            color: #007bff;
        }
        ul li a:hover {
            text-decoration: underline;
        }
        .success {
            color: green;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <a href="HOME.php" class="btn-back">Voltar para HOME</a>
    <h1>Administração de Treinos</h1>

    <h2>Pesquisar Usuário</h2>
    <form method="post" action="">
        <label for="search_user">Nome de Usuário:</label>
        <input type="text" id="search_user" name="search_user">
        <button type="submit">Pesquisar</button>
    </form>

    <h2>Todos os Usuários</h2>
    <ul>
    <?php
    // Consulta para obter todos os usuários
    $sql = "SELECT id, username FROM usuarios";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<li><a href='admin.php?user_id=" . $row['id'] . "'>" . $row['username'] . "</a></li>";
        }
    } else {
        echo "<li>Nenhum usuário encontrado</li>";
    }
    ?>
    </ul>
</body>
</html>
<?php
}
$conn->close();
?>
