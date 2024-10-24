<?php

session_start();

$DB_Host = 'localhost';
$DB_Name = 'sn';
$DB_User = 'root';
$DB_Password = '';
$Options = array (
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
);

$DataBase = mysqli_connect($DB_Host, $DB_User, $DB_Password, $DB_Name);
if ($DataBase == false) {
    die("Ошибка: " . mysqli_connect_error());
}

try {
    $PDO = new PDO('mysql:host='.$DB_Host.';dbname='.$DB_Name.'', $DB_User, $DB_Password, $Options);
} catch (PDOException $e) {
    echo 'Ошибка подключения к базе данных: ' . $e->getMessage();
}

?>