<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ur_lico = trim($_POST['ur_lico']);
    $telephone = trim($_POST['telephone']);
    $pochta = trim($_POST['pochta']);

    if (!empty($ur_lico) && !empty($telephone) && !empty($pochta)) {
        $query = "UPDATE postavsсhiki SET `Ur_lico`=?, `Telephone`=?, `Pochta`=? WHERE `Kod_postavchika`=?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "sssi", $ur_lico, $telephone, $pochta, $user['Kod_postavchika']);

        if (mysqli_stmt_execute($stmt)) {
            // Обновим данные в сессии
            $_SESSION['user']['Ur_lico'] = $ur_lico;
            $_SESSION['user']['Telephone'] = $telephone;
            $_SESSION['user']['Pochta'] = $pochta;
        }

        mysqli_stmt_close($stmt);
    }
}

mysqli_close($conn);
header("Location: profile.php");
exit();
