<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = $_POST['login'];
    $password = $_POST['password'];

    $query = "SELECT * FROM postavsсhiki WHERE Ur_lico = ? AND parol = ?";
    $stmt = mysqli_prepare($conn, $query);

    if (!$stmt) {
        die("Ошибка подготовки запроса: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, "ss", $login, $password);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $_SESSION['user'] = $row;
        header("Location: profile.php");
        exit();
    } else {
        $error = "Неверный логин или пароль";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вход — Склад</title>
    <link rel="stylesheet" href="./assets/css/style.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            background: #1e272e;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #f5f6fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
        }

        .form-container {
            background: #2f3640;
            padding: 40px 30px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.5);
            width: 320px;
            animation: fadeIn 0.6s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .form-container h2 {
            margin-bottom: 25px;
            color: #00a8ff;
        }

        .form-container input {
            width: 100%;
            padding: 12px;
            margin: 12px 0;
            border: none;
            border-radius: 6px;
            background: #353b48;
            color: #f5f6fa;
            font-size: 15px;
        }

        .form-container input::placeholder {
            color: #7f8fa6;
        }

        .form-container button {
            background-color: #00a8ff;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            transition: background-color 0.3s ease;
        }

        .form-container button:hover {
            background-color: #0097e6;
        }

        .error {
            color: #e84118;
            margin-top: 15px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Вход</h2>
        <form action="login.php" method="POST">
            <input type="text" name="login" placeholder="Логин (юр. лицо)" required>
            <input type="password" name="password" placeholder="Пароль" required>
            <button type="submit">Войти</button>
        </form>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
