<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - FlexFit Gym</title>
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

        .section-about, .section-classes, .section-contact {
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

        p {
            line-height: 1.6;
            color: #7f8c8d;
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
            <h1>FlexFit Gym</h1>

            <nav>
                <ul class="navbar">
                    <li><a href="HOME.php" class="nav-link">Início</a></li>
                    <li><a href="treinos.php" class="nav-link">Treinos</a></li>
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

        <div>

    <section class="section-about">
            <h2>Sobre Nós</h2>        
            <p>Bem-vindo à FlexFit Gym, sua academia de referência para alcançar seus objetivos de fitness e saúde. Nós nos dedicamos a oferecer programas de treinamento personalizados, equipamentos de última geração e uma comunidade de apoio para ajudá-lo a atingir seu potencial máximo.</p>
           

        </section>

</section>


    <footer>
        <p>&copy; 2024 FlexFit Gym. Todos os direitos reservados.</p>
    </footer>
</body>
</html>
