<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Личный кабинет</title>
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
    </style>
</head>
<body>
    <nav class="navbar">
        <ul>
            <li><a href="index.php">Главная</a></li>
            <li><a href="book.php">Портфолио</a></li>
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
        <div class="profile-box">
            <h2>Личный кабинет поставщика</h2>

            <form action="update_profile.php" method="POST" id="profileForm">
                <div class="profile-row">
                    <label>Юр. лицо</label>
                    <p id="text-ur"><?= htmlspecialchars($user['Ur_lico']) ?></p>
                    <input type="text" name="ur_lico" value="<?= htmlspecialchars($user['Ur_lico']) ?>" id="input-ur">
                </div>

                <div class="profile-row">
                    <label>Телефон</label>
                    <p id="text-tel"><?= htmlspecialchars($user['Telephone']) ?></p>
                    <input type="text" name="telephone" value="<?= htmlspecialchars($user['Telephone']) ?>" id="input-tel">
                </div>

                <div class="profile-row">
                    <label>Почта</label>
                    <p id="text-mail"><?= htmlspecialchars($user['Pochta']) ?></p>
                    <input type="email" name="pochta" value="<?= htmlspecialchars($user['Pochta']) ?>" id="input-mail">
                </div>

                <button type="button" class="btn-edit" onclick="enableEdit()">Обновить</button>
                <button type="submit" class="btn-save" id="saveBtn" style="display:none;">Сохранить</button>
            </form>
        </div>
    </div>

    <script>
        function enableEdit() {
            document.getElementById('text-ur').style.display = 'none';
            document.getElementById('text-tel').style.display = 'none';
            document.getElementById('text-mail').style.display = 'none';

            document.getElementById('input-ur').style.display = 'block';
            document.getElementById('input-tel').style.display = 'block';
            document.getElementById('input-mail').style.display = 'block';

            document.querySelector('.btn-edit').style.display = 'none';
            document.getElementById('saveBtn').style.display = 'inline-block';
        }
    </script>
</body>
</html>
