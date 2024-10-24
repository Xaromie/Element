<?php

$RootDir = $_SERVER["DOCUMENT_ROOT"];
require $RootDir . '/System/Scripts/Global/DataBase.php';
require $RootDir . '/System/Scripts/Global/Function.php';

$Account = AccountConnect();
$Function = $_GET['F'];

if (!$Account) {
    exit();
}

if ($Function == 'SUB_TO_USER') {

    $UserID = $_POST['UserID'] ?? null;

    if (empty($UserID)) {
        $Errors[] = 'И на кого подписыватся?';
    }
    if (!CheckValidUser($UserID)) {
        $Errors[] = 'Такого пользователя нет';
    }
    if ($UserID == $Account['ID']) {
        $Errors[] = 'Нельзя на себя подписаться';
    }

    if (empty($Errors)) {
        $Query = "SELECT * FROM `subscriptions` WHERE `User` = :User AND `ToUser` = :ToUser";
        $Stmt = $PDO->prepare($Query);
        $Stmt->execute([
            'User' => $Account['ID'],
            'ToUser' => $UserID
        ]);
        if ($Stmt->rowCount() < 1) {
            $Date = date('Y-m-d H:i:s');
            $Query = "INSERT INTO `subscriptions` (`User`, `ToUser`, `Date`) VALUES (:User, :ToUser, :Date)";
            $Stmt = $PDO->prepare($Query);
            $Stmt->execute([
                'User' => $Account['ID'],
                'ToUser' => $UserID,
                'Date' => $Date
            ]);
        } else {
            $Result = $Stmt->fetch(PDO::FETCH_ASSOC);
            $SubID = $Result['ID'];
            $Query = "DELETE FROM `subscriptions` WHERE `ID` = ?";
            $Stmt = $PDO->prepare($Query);
            $Stmt->execute([$SubID]);
        }
        RecountSubscribers($UserID);
        RecountSubscriptions($Account['ID']);
    } else {
        Answer('Error', array_shift($Errors));
    }
}

// Функции

function RecountSubscribers ($UserID) {
    global $PDO;

    $Query = "SELECT COUNT(*) AS Count FROM `subscriptions` WHERE `ToUser` = ?";
    $Stmt = $PDO->prepare($Query);
    $Stmt->execute([$UserID]);
    $Result = $Stmt->fetch(PDO::FETCH_ASSOC);
    $Count = $Result['Count'];

    $Query = "UPDATE `accounts` SET `Subscribers` = :Count WHERE `ID` = :UserID";
    $Stmt = $PDO->prepare($Query);
    $Stmt->execute([
        'UserID' => $UserID,
        'Count'=> $Count
    ]);
}
function RecountSubscriptions ($UserID) {
    global $PDO;

    $Query = "SELECT COUNT(*) AS Count FROM `subscriptions` WHERE `User` = ?";
    $Stmt = $PDO->prepare($Query);
    $Stmt->execute([$UserID]);
    $Result = $Stmt->fetch(PDO::FETCH_ASSOC);
    $Count = $Result['Count'];

    $Query = "UPDATE `accounts` SET `Subscriptions` = :Count WHERE `ID` = :UserID";
    $Stmt = $PDO->prepare($Query);
    $Stmt->execute([
        'UserID' => $UserID,
        'Count'=> $Count
    ]);
}

?>