<?php

$RootDir = $_SERVER["DOCUMENT_ROOT"];
require($RootDir . '/System/Scripts/Global/DataBase.php');
require($RootDir . '/System/Scripts/Global/Function.php');

if(isset($_SESSION['Account'])) {
    header('Location: /home');
} else {
    if (isset($_COOKIE['S_KEY'])) {

        $S_KEY = $_COOKIE['S_KEY'];

        $Query = "SELECT * FROM `accounts` WHERE `S_KEY` = :S_KEY";
        $Stmt = $PDO->prepare($Query);
        $Stmt->bindParam(':S_KEY', $S_KEY);
        $Stmt->execute();
        $Result = $Stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($Result) {
            $_SESSION['Account'] = $Result['ID'];
            $PDO = null;
            header('Location: /home');
        } else {
            header('Location: /auth');
        }
    } else {
        header('Location: /auth');
    }
}

?>