<?php
session_start();
require 'db.php';

// Обработка формы комментария
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment']) && isset($_SESSION['user'])) {
    // Получаем ID поставщика из сессии
    $userId = $_SESSION['user']['Kod_postavchika'];
    
    // Получаем и экранируем текст комментария
    $commentText = mysqli_real_escape_string($conn, $_POST['comment']);
    
    // Вставка комментария
    $insertQuery = "INSERT INTO comments (Kod_postavchika, Text) VALUES ('$userId', '$commentText')";
    
    // Выполнение запроса
    if (mysqli_query($conn, $insertQuery)) {
        header("Location: about.php");  // Перенаправляем на страницу
        exit;
    } else {
        echo "Ошибка при добавлении комментария: " . mysqli_error($conn);
    }
}
$commentsQuery = "
    SELECT 
        comments.Text, 
        comments.Date_added, 
        postavsсhiki.Ur_lico 
    FROM comments 
    JOIN postavsсhiki ON postavsсhiki.Kod_postavchika = comments.Kod_postavchika
    ORDER BY comments.ID DESC
";
$comments = mysqli_query($conn, $commentsQuery);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>О складе</title>
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

        .info h1 {
            font-size: 36px;
            color: #00a8ff;
        }

        .info p {
            font-size: 17px;
            line-height: 1.6;
        }

        .calendar iframe {
            border-radius: 8px;
            margin-top: 20px;
        }

        .comments {
        margin-top: 30px;
        padding: 20px;
        background-color: #2f3640;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    }

    .comments h2 {
        color: #4cd137;
        margin-bottom: 20px;
        font-size: 24px;
        text-align: center;
    }

    .comment {
        background: #353b48;
        margin-bottom: 20px;
        padding: 15px;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        color: #f5f6fa;
    }

    .comment span {
        display: block;
        margin-bottom: 8px;
        font-size: 16px;
    }

    .comment span strong {
        color: #00a8ff;
    }

    .comment .date {
        font-size: 14px;
        color: #7f8fa6;
        margin-top: 10px;
    }

    .comment:last-child {
        margin-bottom: 0;
    }

    .comment:hover {
        background-color: #444d57;
        transition: background-color 0.3s ease;
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
        <a href="login.php">Войти</a><span> / </span><a href="register.php">Регистрация</a>
    <?php else: ?>
        <a href="profile.php">Профиль</a>
        <a href="logout.php">Выйти</a>
    <?php endif; ?>
</div>

<div class="container">
    <div class="info">
        <h1>О складе</h1>
        <p>Наш склад — это современное техническое пространство, где мы храним и обслуживаем оборудование различного назначения: от бытовой техники до IT-оборудования и промышленных систем.</p>
        <p>📦 Здесь ведётся строгий учёт поставок, контроль за состоянием и логистикой техники. Мы работаем с крупнейшими поставщиками и регулярно обновляем наши запасы.</p>
        <p>📅 Сегодня: <strong><?php echo date("d.m.Y"); ?></strong></p>
        <div class="calendar">
            <iframe src="https://calendar.google.com/calendar/embed?src=ru.russian%23holiday%40group.v.calendar.google.com&ctz=Europe%2FMoscow" width="100%" height="400" frameborder="0" scrolling="no"></iframe>
        </div>
    </div>

    <?php if (isset($_SESSION['user'])): ?>
    <div class="comment-form">
        <h2>Оставьте отзыв или предложение</h2>
        <form method="post">
            <textarea name="comment" placeholder="Ваш комментарий..." required></textarea>
            <button type="submit">Отправить</button>
        </form>
    </div>

    <style>
        .comment-form {
            background: #2f3640;
            padding: 30px;
            margin: 30px auto;
            max-width: 600px;
            border-radius: 12px;
            box-shadow: 0 0 12px rgba(0,0,0,0.4);
            animation: fadeIn 0.6s ease-in-out;
        }

        .comment-form h2 {
            margin-bottom: 20px;
            color: #00a8ff;
            font-size: 22px;
        }

        .comment-form form {
            display: flex;
            flex-direction: column;
        }

        .comment-form textarea {
            resize: vertical;
            min-height: 120px;
            padding: 15px;
            background-color: #353b48;
            border: none;
            border-radius: 8px;
            color: #f5f6fa;
            font-size: 15px;
            margin-bottom: 20px;
            transition: 0.3s ease;
        }

        .comment-form textarea::placeholder {
            color: #7f8fa6;
        }

        .comment-form textarea:focus {
            outline: none;
            background-color: #3d4350;
        }

        .comment-form button {
            background-color: #00a8ff;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .comment-form button:hover {
            background-color: #0097e6;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
    <?php endif; ?>

    <div class="comments">
        <h2>Отзывы и комментарии</h2>
        <?php while ($comment = mysqli_fetch_assoc($comments)): ?>
            <div class="comment">
                <span><strong>Поставщик:</strong> <?php echo htmlspecialchars($comment['Ur_lico']); ?></span><br>
                <span><strong>Комментарий:</strong> <?php echo htmlspecialchars($comment['Text']); ?></span><br>
                <span><strong>Дата добавления:</strong> <?php echo date("d.m.Y H:i:s", strtotime($comment['Date_added'])); ?></span>
            </div>
        <?php endwhile; ?>
    </div>
</div>
</body>
</html>
