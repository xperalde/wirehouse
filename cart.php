<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];
$kod_postavchika = $user['Kod_postavchika'] ?? null;

$firma_result = mysqli_query($conn, "SELECT Kod_firmi, Nazvanie FROM firma");
$tip_result = mysqli_query($conn, "SELECT Kod_tipa, Nazvanie FROM tip");

$success = $error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_supply'])) {
    $firma = $_POST['firma'] ?? null;
    $tip = $_POST['tip'] ?? null;
    $kolvo = $_POST['kolichestvo'] ?? null;
    $god = $_POST['god_vipuska'] ?? null;
    $cena = $_POST['cena'] ?? null;
    $model_name = $_POST['nazvanie_modeli'] ?? null;
    $povrezhdeniya = $_POST['povrezhdeniya'] ?? '';

    if ($firma && $tip && $kolvo && $god && $cena && $kod_postavchika && $model_name) {

        // 1. Вставка в postavki
        $insert_postavka = "INSERT INTO postavki (Data_postavki, Kolichestvo) VALUES (CURDATE(), '$kolvo')";
        if (!mysqli_query($conn, $insert_postavka)) {
            $error = "Ошибка при добавлении в postavki: " . mysqli_error($conn);
        } else {
            $kod_postavki = mysqli_insert_id($conn);

            // 2. Вставка в technika
            $insert_tehnika = "INSERT INTO technika (God_vipuska, Cena, Povrezhdeniya, Kod_postavki)
                               VALUES ('$god', '$cena', '$povrezhdeniya', '$kod_postavki')";
            if (!mysqli_query($conn, $insert_tehnika)) {
                $error = "Ошибка при добавлении в technika: " . mysqli_error($conn);
            }
            $kod_tehniki = mysqli_insert_id($conn); // получаем ID вставленной техники

// Вставка в model
$insert_model = "INSERT INTO model (Nazvanie, Kod_techniki) VALUES ('$model_name', '$kod_tehniki')";
if (!mysqli_query($conn, $insert_model)) {
    $error = "Ошибка при добавлении в model: " . mysqli_error($conn);
}

            // 3. Обновление в postavsсhiki (с кириллической "с")
            $update_postavshik = "UPDATE postavsсhiki SET Kod_postavki = '$kod_postavki' WHERE Kod_postavchika = '$kod_postavchika'";
            if (!mysqli_query($conn, $update_postavshik)) {
                $error = "Ошибка при обновлении postavsсhiki: " . mysqli_error($conn);
            }

            // 4. Вставка в svyaz
            $insert_svyaz = "INSERT INTO svyaz (Kod_firmi, Kod_tipa, Kod_postavchika)
                             VALUES ('$firma', '$tip', '$kod_postavchika')";
            if (!mysqli_query($conn, $insert_svyaz)) {
                $error = "Ошибка при вставке в svyaz: " . mysqli_error($conn);
            }

            if (!$error) {
                $success = "Поставка успешно добавлена!";
            }
        }
    } else {
        $error = "Пожалуйста, заполните все поля.";
    }
}
?>



<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Корзина</title>
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
    font-weight: bold;
}

.container {
    margin-left: 240px;
    padding: 50px;
    width: calc(100% - 240px);
}

h1 {
    color: #00a8ff;
    margin-bottom: 10px;
}

.filter-form {
    margin-bottom: 30px;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
}

.filter-form label {
    margin-right: 10px;
    font-size: 16px;
}

.filter-form select, .filter-form button {
    padding: 10px 15px;
    font-size: 16px;
    margin-right: 15px;
    background-color: #2f3640;
    color: #f5f6fa;
    border: 1px solid #00a8ff;
    border-radius: 5px;
    transition: background 0.3s ease;
}

.filter-form select:hover, .filter-form button:hover {
    background-color: #00a8ff;
    color: white;
}

table {
    background-color: white;
    margin: 0 auto;
    width: 100%;
    border-collapse: collapse;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 0 10px rgba(0,0,0,0.2);
}

th, td {
    padding: 12px 15px;
    border: 1px solid #ccc;
    color: #2f3640;
    font-size: 15px;
}

th {
    background-color: #dcdde1;
    text-align: left;
    font-weight: bold;
}

tr:nth-child(even) {
    background-color: #ecf0f1;
}

tr:nth-child(odd) {
    background-color: #f5f6fa;
}

.auth {
    position: absolute;
    top: 15px;
    right: 25px;
}

.auth a {
    text-decoration: none;
    margin-left: 15px;
    font-size: 16px;
    transition: color 0.3s ease;
}

.auth a:hover {
    color: #ffffff;
    text-shadow: 0 0 5px #00a8ff;
}

a[style*="color: red"] {
    font-weight: bold;
    color: red !important;
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
    <h2>Добавить поставку</h2>
<form method="POST" class="filter-form" style="flex-direction: column; align-items: flex-start; gap: 15px; max-width: 600px;">

    <label>Фирма:</label>
    <select name="firma" required>
        <option value="">-- Выберите фирму --</option>
        <?php while ($firma = mysqli_fetch_assoc($firma_result)): ?>
            <option value="<?= $firma['Kod_firmi'] ?>"><?= htmlspecialchars($firma['Nazvanie']) ?></option>
        <?php endwhile; ?>
    </select>

    <label>Тип:</label>
    <select name="tip" required>
        <option value="">-- Выберите тип --</option>
        <?php while ($tip = mysqli_fetch_assoc($tip_result)): ?>
            <option value="<?= $tip['Kod_tipa'] ?>"><?= htmlspecialchars($tip['Nazvanie']) ?></option>
        <?php endwhile; ?>
    </select>

    <label>Количество:</label>
    <input type="number" name="kolichestvo" min="1" required
           style="width: 100%; padding: 10px; border-radius: 5px; background-color: #2f3640; color: #f5f6fa; border: 1px solid #00a8ff;">

    <label>Год выпуска:</label>
    <input type="date" name="god_vipuska" required
           style="width: 100%; padding: 10px; border-radius: 5px; background-color: #2f3640; color: #f5f6fa; border: 1px solid #00a8ff;">

    <label>Название модели:</label>
    <input type="text" name="nazvanie_modeli" required
           style="width: 100%; padding: 10px; border-radius: 5px; background-color: #2f3640; color: #f5f6fa; border: 1px solid #00a8ff;">

    <label>Цена:</label>
    <input type="number" name="cena" min="1" required
           style="width: 100%; padding: 10px; border-radius: 5px; background-color: #2f3640; color: #f5f6fa; border: 1px solid #00a8ff;">

    <label>Повреждения (если есть):</label>
    <input type="text" name="povrezhdeniya"
           style="width: 100%; padding: 10px; border-radius: 5px; background-color: #2f3640; color: #f5f6fa; border: 1px solid #00a8ff;">

    <button type="submit" name="add_supply"
            style="padding: 12px 25px; background-color: #00a8ff; border: none; border-radius: 5px; color: white; font-weight: bold; cursor: pointer; transition: 0.3s;">
        Добавить поставку
    </button>
</form>

<?php if ($success): ?>
    <p style="margin-top: 20px; color: #2ecc71; font-weight: bold;"><?= $success ?></p>
<?php endif; ?>

<?php if ($error): ?>
    <p style="margin-top: 20px; color: #e84118; font-weight: bold;"><?= $error ?></p>
<?php endif; ?>


    </div>
</body>
</html>
