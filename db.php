<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'warehouse';

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die('Ошибка подключения к базе данных: ' . mysqli_connect_error());
}
?>
