<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $postavka_id = $_GET['id'];
    $current_user_id = $_SESSION['user']['Kod_postavchika'];  // Используем Kod_postavchika вместо ID

    // Проверяем, принадлежит ли поставка текущему пользователю
    $check_query = "SELECT * FROM postavsсhiki WHERE Kod_postavki = ? AND Kod_postavchika = ?";
    echo "Текущий пользователь: " . $_SESSION['user']['Kod_postavchika'] . "<br>";  // Выводим текущий Kod_postavchika
    echo "Поставка ID: " . $postavka_id . "<br>";

    $check_stmt = mysqli_prepare($conn, $check_query);
    if ($check_stmt === false) {
        die("Ошибка при подготовке запроса на проверку принадлежности поставки.");
    }
    mysqli_stmt_bind_param($check_stmt, "ii", $postavka_id, $current_user_id);
    $exec_check = mysqli_stmt_execute($check_stmt);
    if (!$exec_check) {
        die("Ошибка при выполнении запроса на проверку принадлежности поставки.");
    }

    $result = mysqli_stmt_get_result($check_stmt);
    if (mysqli_num_rows($result) > 0) {
        // Заменяем значение Kod_postavki в таблице postavsсhiki на NULL для всех записей, связанных с этой поставкой
        $update_postavsсhiki = "UPDATE postavsсhiki SET Kod_postavki = NULL WHERE Kod_postavki = ?";
        $postavsсhiki_stmt = mysqli_prepare($conn, $update_postavsсhiki);
        if ($postavsсhiki_stmt === false) {
            die("Ошибка при подготовке запроса на обновление таблицы postavsсhiki: " . mysqli_error($conn));
        }
        mysqli_stmt_bind_param($postavsсhiki_stmt, "i", $postavka_id);
        $exec_postavsсhiki = mysqli_stmt_execute($postavsсhiki_stmt);
        if (!$exec_postavsсhiki) {
            die("Ошибка при обновлении записей в postavsсhiki: " . mysqli_error($conn));
        }
        mysqli_stmt_close($postavsсhiki_stmt);

        // Заменяем значение Kod_postavki в таблице Technika на NULL для всех записей, связанных с этой поставкой
        $update_technika = "UPDATE Technika SET Kod_postavki = NULL WHERE Kod_postavki = ?";
        $tech_stmt = mysqli_prepare($conn, $update_technika);
        if ($tech_stmt === false) {
            die("Ошибка при подготовке запроса на обновление таблицы Technika: " . mysqli_error($conn));
        }
        mysqli_stmt_bind_param($tech_stmt, "i", $postavka_id);
        $exec_tech = mysqli_stmt_execute($tech_stmt);
        if (!$exec_tech) {
            die("Ошибка при обновлении записей в Technika: " . mysqli_error($conn));
        }
        mysqli_stmt_close($tech_stmt);

        // Теперь удаляем запись из Postavki
        $delete_postavka = "DELETE FROM Postavki WHERE Kod_postavki = ?";
        $postavka_stmt = mysqli_prepare($conn, $delete_postavka);
        if ($postavka_stmt === false) {
            die("Ошибка при подготовке запроса на удаление из Postavki: " . mysqli_error($conn));
        }
        mysqli_stmt_bind_param($postavka_stmt, "i", $postavka_id);
        $exec_postavka = mysqli_stmt_execute($postavka_stmt);
        if ($exec_postavka) {
            // Если успешно, перенаправляем на страницу поставок
            header("Location: delivery.php");
            exit();
        } else {
            // Если ошибка при удалении поставки
            echo "Ошибка при удалении поставки: " . mysqli_error($conn);
        }

        mysqli_stmt_close($postavka_stmt);
    } else {
        // Если поставка не принадлежит текущему пользователю
        echo "У вас нет прав для удаления этой поставки.";
    }

    mysqli_stmt_close($check_stmt);
} else {
    echo "Ошибка: ID поставки не передано.";
}

mysqli_close($conn);
?>
