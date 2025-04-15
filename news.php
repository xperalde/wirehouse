<?php
session_start();
require 'db.php';

// Получаем все новости из таблицы "news", отсортированные по дате
$query = "SELECT * FROM news ORDER BY Дата DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Новости</title>
    <link rel="stylesheet" href="Library/assets/css/style.css">
<style>
    .auth {
    position: absolute;
    top: 10px;
    right: 20px;
    }
    .auth a {
        color: #ecf0f1;
        text-decoration: none;
        margin-left: 15px;
        font-size: 16px;
    }
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

    .container {
        margin-left: 240px;
        padding: 50px;
        width: calc(100% - 240px);
    }

    .profile-box {
        background: #353b48;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }

    .profile-box h2 {
        color: #00a8ff;
        margin-bottom: 20px;
    }

    .profile-row {
        margin-bottom: 20px;
    }

    .profile-row label {
        font-weight: bold;
        display: block;
        margin-bottom: 5px;
    }

    .profile-row p {
        margin: 0;
    }

    input[type="text"], input[type="email"] {
        width: 100%;
        padding: 10px;
        font-size: 16px;
        margin-bottom: 10px;
        border-radius: 8px;
        border: none;
        display: none;
    }

    .btn-edit, .btn-save {
        background-color: #00a8ff;
        color: white;
        padding: 10px 20px;
        font-size: 16px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        margin-top: 10px;
    }

    .btn-edit:hover, .btn-save:hover {
        background-color: #0097e6;
    }
    .news-section {
    display: flex;
    flex-direction: column;
    gap: 30px;
    margin-top: 40px;
    }

    .news-item {
        background: #353b48;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        display: flex;
        gap: 20px;
        align-items: flex-start;
        transition: transform 0.2s ease, box-shadow 0.3s ease;
    }

    .news-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.3);
    }

    .news-item img {
        max-width: 180px;
        max-height: 150px;
        object-fit: cover;
        border-radius: 8px;
        flex-shrink: 0;
        box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    }

    .news-content {
        flex-grow: 1;
    }

    .news-date {
        font-size: 14px;
        color: #7f8fa6;
        margin-bottom: 10px;
    }

    .news-text {
        font-size: 16px;
        line-height: 1.6;
        white-space: pre-line;
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
            <a href="login.php">Войти</a> / <a href="register.php">Зарегистрироваться</a>
        <?php else: ?>
            <a href="profile.php">Личный кабинет</a>
            <a href="logout.php">Выйти</a>
        <?php endif; ?>
    </div>

    <div class="container">
        <h1>Новости библиотеки</h1>
        <div class="news-section">
            <?php while ($news = mysqli_fetch_assoc($result)): ?>
                <div class="news-item">
                    <?php if (!empty($news['Фото'])): ?>
                        <img src="./assets/img/<?php echo htmlspecialchars($news['Фото']); ?>" alt="Фото новости">
                    <?php endif; ?>
                    <div class="news-content">
                        <div class="news-date">📅 <?php echo date("d.m.Y", strtotime($news['Дата'])); ?></div>
                        <div class="news-text"><?php echo nl2br(htmlspecialchars($news['Текст'])); ?></div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>