<?php
session_start();
require 'db.php';

// Получение параметров фильтра
$damages = $_GET['Povrezhdeniya'] ?? '';
$sort_order = $_GET['sort'] ?? 'ASC';

// Основной SQL-запрос
$sql = "SELECT post.Kod_postavki, post.Data_postavki, post.Kolichestvo, 
               tech.Kod_tehniki, tech.God_vipuska, tech.Cena, tech.Povrezhdeniya,
               model.Nazvanie AS Model_name,
               postavs.Ur_lico, postavs.Kod_postavki AS Kod_postavki_postavshchika, postavs.Kod_postavchika
        FROM Postavki post
        JOIN Technika tech ON post.Kod_postavki = tech.Kod_postavki
        LEFT JOIN model ON tech.Kod_tehniki = model.Kod_techniki
        LEFT JOIN postavsсhiki postavs ON post.Kod_postavki = postavs.Kod_postavki";

if (!empty($damages)) {
    $sql .= " WHERE tech.Povrezhdeniya LIKE '%$damages%'";
}

$sql .= " ORDER BY tech.Cena $sort_order";

$result = $conn->query($sql);

if (!$result) {
    die("Ошибка в запросе: " . $conn->error);
}

// Получение доступных повреждений
$damages_query = $conn->query("SELECT DISTINCT Povrezhdeniya FROM Technika");

if (!$damages_query) {
    die("Ошибка в запросе для повреждений: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Поставки</title>
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
        <a href="profile.php">Профиль</a>
        <a href="logout.php">Выйти</a>
    <?php endif; ?>
</div>

<div class="container">
    <header>
        <h1>Фильтрация поставок и техники</h1>
        <p>Выберите параметры ниже для отображения данных</p>
    </header>

    <form method="GET" class="filter-form">
        <label>Выберите повреждения:</label>
        <select name="Povrezhdeniya">
            <option value="">Все</option>
            <?php while ($row = $damages_query->fetch_assoc()): ?>
                <option value="<?= htmlspecialchars($row['Povrezhdeniya']) ?>" <?= ($row['Povrezhdeniya'] == $damages) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($row['Povrezhdeniya']) ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label>Сортировка по цене:</label>
        <select name="sort">
            <option value="ASC" <?= ($sort_order == 'ASC') ? 'selected' : '' ?>>По возрастанию</option>
            <option value="DESC" <?= ($sort_order == 'DESC') ? 'selected' : '' ?>>По убыванию</option>
        </select>

        <button type="submit">Фильтровать</button>
    </form>

    <table>
    <thead>
        <tr>
            <th>Модель</th>
            <th>Дата поставки</th>
            <th>Количество</th>
            <th>Год выпуска</th>
            <th>Цена</th>
            <th>Повреждения</th>
            <th>Поставщик</th>
            <?php if (isset($_SESSION['user'])): ?>
            <th>Действие</th>
            <?php endif; ?>
        </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row["Model_name"]) ?></td>
                <td><?= htmlspecialchars($row["Data_postavki"]) ?></td>
                <td><?= htmlspecialchars($row["Kolichestvo"]) ?></td>
                <td><?= htmlspecialchars($row["God_vipuska"]) ?></td>
                <td><?= htmlspecialchars($row["Cena"]) ?></td>
                <td><?= htmlspecialchars($row["Povrezhdeniya"]) ?></td>
                <td><?= htmlspecialchars($row["Ur_lico"] ?? '—') ?></td>
                <?php if (isset($_SESSION['user'])): ?>
                    <td>
                    <?php if ($_SESSION['user']['Kod_postavchika'] == $row['Kod_postavchika']): ?>
                            <a href="delete_delivery.php?id=<?= $row['Kod_postavki'] ?>" onclick="return confirm('Удалить эту поставку?')" style="color: red;">Удалить</a>
                        <?php else: ?>
                            —
                        <?php endif; ?>
                    </td>
                <?php endif; ?>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>

<?php
$conn->close();
?>
