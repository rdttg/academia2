<?php
// inclui o arquivo de conexão com o banco de dados
include 'conexao.php';

// Inicia a sessão
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['user_username'])) {
    // Redireciona para a página de login se não estiver logado
    header("Location: login.html");
    exit();
}

// Recebe os dados do formulário de treino, se existirem
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $username = $_SESSION['user_username'];

    // Prepara e executa a query para inserir o treino
    $stmt = $conn->prepare("INSERT INTO treinos (username, nome, descricao) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $nome, $descricao);

    if ($stmt->execute()) {
        echo "Treino salvo com sucesso!";
    } else {
        echo "Erro ao salvar o treino: " . $stmt->error;
    }

    $stmt->close();
}

// Prepara e executa a query para buscar os treinos do usuário logado
$stmt = $conn->prepare("SELECT id, nome, descricao FROM treinos WHERE username = ?");
if ($stmt === false) {
    die('Erro na preparação da consulta: ' . $conn->error);
}

$bind_result = $stmt->bind_param("s", $_SESSION['user_username']);
if ($bind_result === false) {
    die('Erro ao vincular parâmetros: ' . $stmt->error);
}

$execute_result = $stmt->execute();
if ($execute_result === false) {
    die('Erro ao executar a consulta: ' . $stmt->error);
}

$result = $stmt->get_result();
if ($result === false) {
    die('Erro ao obter o resultado: ' . $stmt->error);
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Treinos - TadalaFit</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            color: #333;
        }

        .container {
            width: 90%;
            margin: 0 auto;
            padding: 20px;
        }

        header {
            background-color: #2c3e50;
            padding: 20px;
            color: #ecf0f1;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h1 {
            margin: 0;
            font-size: 24px;
        }

        nav {
            display: flex;
            align-items: center;
        }

        .navbar {
            margin: 0;
            padding: 0;
            list-style-type: none;
            text-align: center;
        }

        .navbar li {
            display: inline-block;
            margin-right: 20px;
        }

        .navbar li:last-child {
            margin-right: 0;
        }

        .navbar li a {
            color: #ecf0f1;
            text-decoration: none;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .navbar li a:hover {
            background-color: #34495e;
        }

        .form-section, .table-section {
            margin-top: 30px;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #2c3e50;
            font-size: 22px;
            margin-top: 0;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 5px;
            font-weight: bold;
        }

        input, select {
            margin-bottom: 15px;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #2c3e50;
            color: #ecf0f1;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #34495e;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: left;
        }

        th {
            background-color: #2c3e50;
            color: #ecf0f1;
        }

        .actions {
            display: flex;
            gap: 10px;
        }

        .actions button {
            padding: 5px 10px;
        }

        footer {
            background-color: #2c3e50;
            color: #ecf0f1;
            text-align: center;
            padding: 1rem 0;
            margin-top: 2rem;
            box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);
        }

        .highlight {
            color: #e74c3c;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>TadalaFit</h1>

            <nav>
                <ul class="navbar">
                    <li><a href="HOME.php" class="nav-link">Início</a></li>
                    <li><a href="treinos.php" class="nav-link">Treinos</a></li>
                    <li><a href="planos.php" class="nav-link">Planos</a></li>
                    <li><a href="sobre.php" class="nav-link">Sobre</a></li>
                    <?php
                    if(isset($_SESSION['user_username'])) {
                        echo '<li>Bem-vindo(a), <span class="highlight">'  . $_SESSION['user_username'] . '</span></li>';
                        echo '<li><a href="logout.php" style="background-color: #e74c3c;">Sair</a></li>';
                    } else {
                        echo '<li><a href="cadastro.html">Cadastro</a></li>';
                        echo '<li><a href="login.html">Login</a></li>';
                    }
                    ?>
                </ul>
            </nav>
        </header>

        <section class="form-section">
            <h2>Gerenciar Treinos</h2>
            <form id="treinoForm" method="POST" action="treinos.php">
                <label for="nome">Nome do Treino:</label>
                <input type="text" id="nome" name="nome" required>

                <label for="descricao">Descrição:</label>
                <input type="text" id="descricao" name="descricao" required>

                <button type="submit">Salvar</button>
            </form>
        </section>

        <section class="table-section">
            <h2>Meus Treinos</h2>
            <?php
            if ($result->num_rows > 0) {
                echo "<table>";
                echo "<thead><tr><th>Nome</th><th>Descrição</th><th>Ações</th></tr></thead>";
                echo "<tbody>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['nome']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['descricao']) . "</td>";
                    echo "<td class='actions'>";
               
                    echo "<input type='hidden' name='id' value='" . $row['id'] . "'>";
             
                    echo "</form>";
                    echo "<form method='POST' action='excluir_treino.php' style='display:inline-block;'>";
                    echo "<input type='hidden' name='id' value='" . $row['id'] . "'>";
                    echo "<button type='submit'>Excluir</button>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</tbody></table>";
            } else {
                echo "Nenhum treino encontrado.";
            }

            $stmt->close();
            $conn->close();
            ?>
        </section>
    </div>

    <footer>
        <p>&copy; 2024 TadalaFit. Todos os direitos reservados.</p>
    </footer>
</body>
</html>
