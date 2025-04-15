<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Получаем данные из формы
    $fullname = trim($_POST['fullname']); // ФИО
    $phone = trim($_POST['phone']); // Телефон
    $address = trim($_POST['address']); // Адрес
    $password = trim($_POST['password']); // Пароль (шифруем)

    // Запрос на вставку данных в таблицу postavsсhiki
    $query = "INSERT INTO postavsсhiki (Ur_lico, Telephone, Pochta, parol) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        // Привязываем параметры к запросу
        mysqli_stmt_bind_param($stmt, "ssss", $fullname, $phone, $address, $password);
        
        // Выполняем запрос
        if (mysqli_stmt_execute($stmt)) {
            echo "<p style='color: green;'>Регистрация успешна!</p>";
            header("Location: index.php"); // Перенаправление на главную страницу
            exit();
        } else {
            echo "<p style='color: red;'>Ошибка: " . mysqli_error($conn) . "</p>";
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "<p style='color: red;'>Ошибка подготовки запроса: " . mysqli_error($conn) . "</p>";
    }

    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация - Система поставок</title>
    <link rel="stylesheet" href="./assets/css/style.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            background: #1e272e;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #f5f6fa;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .form-container {
            background: #2f3640;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            max-width: 400px;
            width: 100%;
        }

        .form-container h2 {
            margin-bottom: 20px;
            color: #00a8ff;
            font-size: 24px;
            text-align: center;
        }

        .form-container input {
            width: 100%;
            padding: 15px;
            margin: 10px 0;
            background: #353b48;
            color: #f5f6fa;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            transition: 0.3s ease;
        }

        .form-container input::placeholder {
            color: #7f8fa6;
        }

        .form-container input:focus {
            outline: none;
            background-color: #444d57;
        }

        .form-container button {
            background-color: #00a8ff;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            width: 100%;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .form-container button:hover {
            background-color: #0097e6;
        }

        .form-container .error-message {
            color: red;
            text-align: center;
            margin-top: 20px;
        }

        @media (max-width: 480px) {
            .form-container {
                padding: 20px;
                width: 90%;
            }

            .form-container h2 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2>Регистрация</h2>
            <form action="register.php" method="POST">
                <input type="text" name="fullname" placeholder="Юридическое лицо" required>
                <input type="text" name="phone" placeholder="Телефон" required>
                <input type="text" name="address" placeholder="Почта" required>
                <input type="password" name="password" placeholder="Пароль" required>
                <button type="submit">Зарегистрироваться</button>
            </form>
            <div class="error-message">
                <?php
                // Если есть ошибки при регистрации, выводим их
                if (isset($error_message)) {
                    echo $error_message;
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>
