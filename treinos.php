<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Treinos - FlexFit Gym</title>
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
                    <li><a href="admin.php" class="nav-link">Personal</a></li>
                    <li><a href="planos.php" class="nav-link">Planos</a></li>
                    <li><a href="sobre.php" class="nav-link">Sobre</a></li>
                    <?php
                    session_start();
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
            <h2>Meus Treinos</h2>
                
            <?php
            // inclui o arquivo de conexão com o banco de dados
            include 'conexao.php';

            // Verifica se o usuário está logado
            if (!isset($_SESSION['user_username'])) {
                // Redireciona para a página de login se não estiver logado
                header("Location: login.html");
                exit();
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

            if ($result->num_rows > 0) {
                echo "<table>";
                echo "<thead><tr><th>Nome</th><th>Descrição</th><th>Ações</th></tr></thead>";
                echo "<tbody>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['nome']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['descricao']) . "</td>";
                    echo "<td class='actions'>";
                    echo "<a href='treinos.php?treino_id=" . $row['id'] . "'>Ver Exercícios</a>";
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

        <?php
        // Verifica se foi passado um ID de treino pela URL
        if (isset($_GET['treino_id'])) {
            $treino_id = $_GET['treino_id'];

            // inclui o arquivo de conexão com o banco de dados novamente
            include 'conexao.php';

            // Prepara e executa a query para buscar os exercícios do treino selecionado
            $stmt = $conn->prepare("SELECT id, nome, descricao FROM exercicios WHERE treino_id = ?");
            if ($stmt === false) {
                die('Erro na preparação da consulta: ' . $conn->error);
            }

            $bind_result = $stmt->bind_param("i", $treino_id);
            if ($bind_result === false) {
                die('Erro ao vincular parâmetros: ' . $stmt->error);
            }

            $execute_result = $stmt->execute();
            if ($execute_result === false) {
                die('Erro ao executar a consulta: ' . $stmt->error);
            }

            $exercicios_result = $stmt->get_result();
            if ($exercicios_result === false) {
                die('Erro ao obter o resultado: ' . $stmt->error);
            }

            echo "<section class='table-section'>";
            echo "<h2>Exercícios do Treino</h2>";

            if ($exercicios_result->num_rows > 0) {
                echo "<table>";
                echo "<thead><tr><th>Nome</th><th>Descrição</th></tr></thead>";
                echo "<tbody>";
                while ($exercicio = $exercicios_result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($exercicio['nome']) . "</td>";
                    echo "<td>" . htmlspecialchars($exercicio['descricao']) . "</td>";
                    echo "</tr>";
                }
                echo "</tbody></table>";
            } else {
                echo "Nenhum exercício encontrado para este treino.";
            }

            echo "</section>";

            $stmt->close();
            $conn->close();
        }
        ?>
    </div>

    <footer>
        <p>&copy; 202
        </div>

<footer>
    <p>&copy; 2024 FlexFit Gym. Todos os direitos reservados.</p>
</footer>
</body>
</html>
