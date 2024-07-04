<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['user_username'])) {
    header("Location: login.html");
    exit();
}

// Inclui o arquivo de conexão com o banco de dados
include 'conexao.php';

// Adiciona uma nova meta
if (isset($_POST['add_meta'])) {
    $username = $_SESSION['user_username'];
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $data_inicio = $_POST['data_inicio'];
    $data_fim = $_POST['data_fim'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("INSERT INTO metas (username, nome, descricao, data_inicio, data_fim, status) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt === false) {
        die('Erro na preparação da consulta: ' . $conn->error);
    }

    $stmt->bind_param("ssssss", $username, $nome, $descricao, $data_inicio, $data_fim, $status);
    if ($stmt->execute()) {
        echo "<p>Meta adicionada com sucesso!</p>";
    } else {
        echo "<p>Erro ao adicionar meta: " . $stmt->error . "</p>";
    }
    $stmt->close();
}

// Exclui uma meta
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];

    $stmt = $conn->prepare("DELETE FROM metas WHERE id = ?");
    if ($stmt === false) {
        die('Erro na preparação da consulta: ' . $conn->error);
    }

    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "<p>Meta excluída com sucesso!</p>";
    } else {
        echo "<p>Erro ao excluir meta: " . $stmt->error . "</p>";
    }
    $stmt->close();
}

// Prepara e executa a consulta para buscar todas as metas do usuário logado
$stmt = $conn->prepare("SELECT id, nome, descricao, data_inicio, data_fim, status FROM metas WHERE username = ?");
if ($stmt === false) {
    die('Erro na preparação da consulta: ' . $conn->error);
}

$stmt->bind_param("s", $_SESSION['user_username']);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Metas - FlexFit Gym</title>
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
        .table-section {
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .actions {
            white-space: nowrap;
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
        @media (max-width: 768px) {
            .container {
                width: 100%;
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>FlexFit Gym</h1>
            <nav>
                <ul class="navbar">
                    <li><a href="HOME.php" class="nav-link">Início</a></li>
                    <li><a href="treinos.php" class="nav-link">Treinos</a></li>
                    <li><a href="metas.php" class="nav-link">Metas</a></li>
                    <li><a href="admin.php" class="nav-link">Personal</a></li>
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

        <section class="table-section">
            <h2>Minhas Metas</h2>
            <form method="post" action="metas.php">
                <fieldset>
                    <legend>Adicionar Nova Meta</legend>
                    <label for="nome">Nome:</label>
                    <input type="text" id="nome" name="nome" required>
                    <br>
                    <label for="descricao">Descrição:</label>
                    <textarea id="descricao" name="descricao"></textarea>
                    <br>
                    <label for="data_inicio">Data de Início:</label>
                    <input type="date" id="data_inicio" name="data_inicio" required>
                    <br>
                    <label for="data_fim">Data de Fim:</label>
                    <input type="date" id="data_fim" name="data_fim" required>
                    <br>
                    <label for="status">Status:</label>
                    <select id="status" name="status">
                        <option value="Pendente">Pendente</option>
                        <option value="Concluída">Concluída</option>
                        <option value="Cancelada">Cancelada</option>
                    </select>
                    <br>
                    <input type="submit" name="add_meta" value="Adicionar Meta">
                </fieldset>
            </form>

            <?php
            if ($result->num_rows > 0) {
                echo "<table>";
                echo "<thead><tr><th>Nome</th><th>Descrição</th><th>Data de Início</th><th>Data de Fim</th><th>Status</th><th>Ações</th></tr></thead>";
                echo "<tbody>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['nome']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['descricao']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['data_inicio']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['data_fim']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                    echo "<td class='actions'>";
                    echo "<a href='metas.php?delete_id=" . $row['id'] . "' onclick=\"return confirm('Tem certeza de que deseja excluir esta meta?')\">Excluir</a>";
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</tbody></table>";
            } else {
                echo "Nenhuma meta encontrada.";
            }

            $stmt->close();
            $conn->close();
            ?>
        </section>

        <footer>
            <p>&copy; <?php echo date('Y'); ?> FlexFit Gym. Todos os direitos reservados.</p>
        </footer>
    </div>
</body>
</html>
