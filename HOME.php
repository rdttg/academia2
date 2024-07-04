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

        .section-promotions, .section-gallery, .section-testimonials, .section-schedule {
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

        .gallery img {
            width: 100%;
            height: auto;
            border-radius: 8px;
            margin-bottom: 15px;
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
                 <li><a href="admin.php" class="nav-link">Personal </a></li>
                 <li><a href="metas.php" class="nav-link">Metas </a></li>


                           
                  
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

        <section class="section-promotions">
            <h2>Promoções Atuais</h2>
            <p>Aproveite nossas ofertas especiais de lançamento! Descontos imperdíveis para novos membros. Inscreva-se hoje e ganhe 20% de desconto na primeira mensalidade!</p>
            <p>Promoção válida até o final do mês. Não perca!</p>
        </section>

    
        <section class="section-schedule">
            <h2>Horários de Aulas</h2>
            <p>Segunda a Sexta:</p>
            <ul>
                <li>Manhã: 6:00 - 11:00</li>
                <li>Tarde: 14:00 - 17:00</li>
                <li>Noite: 18:00 - 21:00</li>
            </ul>
            <p>Sábado:</p>
            <ul>
                <li>Manhã: 7:00 - 12:00</li>
            </ul>
        </section>
        <section class="section-testimonials">
            <h2>Depoimentos</h2>
            <p>"A FlexFit Gym transformou minha vida! Com os programas personalizados e a orientação dos instrutores, alcancei todos os meus objetivos fitness." - João Silva</p>
            <p>"A melhor academia da cidade, com certeza! Equipamentos modernos e uma equipe muito atenciosa." - Maria Souza</p>
        </section>

    </div>

    <footer>
        <p>&copy; 2024 FlexFit Gym. Todos os direitos reservados.</p>
    </footer>
</body>
</html>
