<?php
session_start();

$counter_file = "visits.txt";
if (!file_exists($counter_file)) {
    file_put_contents($counter_file, "0");
}
$visits = (int)file_get_contents($counter_file);
$visits++;
file_put_contents($counter_file, $visits);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Главная</title>
    <link rel="stylesheet" href="./assets/css/style.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            background: #1e272e;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #f5f6fa;
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
        }

        .navbar {
            width: 220px;
            background: #2f3640;
            height: 100vh;
            padding-top: 20px;
            position: fixed;
            left: 0;
            top: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            box-shadow: 2px 0 10px rgba(0,0,0,0.3);
        }

        .navbar ul {
            list-style: none;
            padding: 0;
            width: 100%;
        }

        .navbar ul li {
            width: 100%;
            text-align: center;
            transition: background 0.3s ease;
        }

        .navbar ul li a {
            display: block;
            padding: 15px;
            color: #f5f6fa;
            text-decoration: none;
            font-size: 18px;
            width: 100%;
            box-sizing: border-box;
            transition: all 0.3s ease;
        }

        .navbar ul li a:hover {
            background: #00a8ff;
            transform: translateX(5px);
        }

        .auth {
            position: absolute;
            top: 10px;
            right: 20px;
        }

        .auth a {
            color: #f5f6fa;
            text-decoration: none;
            margin-left: 15px;
            font-size: 16px;
            transition: color 0.3s;
        }

        .auth a:hover {
            color: #00a8ff;
        }

        .container {
            margin-left: 240px;
            padding: 50px;
            width: calc(100% - 240px);
            animation: fadeIn 0.7s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        header.hero {
            text-align: center;
            margin-bottom: 30px;
        }

        .hero h1 {
            font-size: 40px;
            color: #00a8ff;
            margin-bottom: 10px;
            animation: slideDown 0.6s ease;
        }

        .hero p {
            font-size: 18px;
            color: #dcdde1;
            animation: fadeIn 1s ease-in;
        }

        @keyframes slideDown {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .section {
            background: #353b48;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            transition: transform 0.3s ease;
        }

        .section:hover {
            transform: translateY(-5px);
        }

        .section h2 {
            color: #4cd137;
            margin-bottom: 10px;
        }

        .section p,
        .section ul {
            font-size: 17px;
            line-height: 1.6;
            color: #f5f6fa;
            list-style-type: none;
            padding-right: 40px;
        }

        .section ul li {
            margin-bottom: 6px;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 16px;
            color: #7f8fa6;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <ul>
            <li><a href="index.php">Главная</a></li>
            <li><a href="news.php">Новости</a></li>
            <li><a href="about.php">О складе</a></li>
            <li><a href="delivery.php">Поставки</a></li>
            <li><a href="cart.php">Заявки</a></li>
        </ul>
    </nav>

    <div class="auth">
        <?php if (!isset($_SESSION['user'])): ?>
            <a href="login.php">Вход</a><span> / </span><a href="register.php">Регистрация</a>
        <?php else: ?>
            <a href="profile.php">Профиль</a>
            <a href="logout.php">Выход</a>
        <?php endif; ?>
    </div>

    <div class="container">
        <header class="hero">
            <h1>Добро пожаловать на Склад Техники</h1>
            <p>Эффективный контроль и учёт оборудования и комплектующих</p>
        </header>

        <div class="section">
            <h2>О складе</h2>
            <p>Наш склад специализируется на хранении и управлении техническим оборудованием: от серверов и сетевых компонентов до периферийной техники. Мы обеспечиваем строгий контроль качества, безопасность и прозрачность учёта всех операций.</p>
        </div>

        <div class="section">
            <h2>Что у нас есть</h2>
            <ul>
                <li>Серверные стойки и коммутаторы</li>
                <li>Кабельная продукция и аксессуары</li>
                <li>Резервные блоки питания и ИБП</li>
                <li>Инструменты и расходные материалы</li>
            </ul>
        </div>

        <div class="section">
            <h2>Преимущества работы с нами</h2>
            <p>
                ✅ Современные технологии учёта<br>
                ✅ Отслеживание поставок в реальном времени<br>
                ✅ Быстрый отклик на запросы и заявки<br>
                ✅ Безопасное хранение техники и запчастей
            </p>
        </div>

        <footer class="footer">
            <p>Эту страницу посетили <strong><?php echo $visits; ?></strong> раз(а).</p>
        </footer>
    </div>
</body>
</html>
