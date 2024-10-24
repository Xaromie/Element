<?php

$RootDir = $_SERVER["DOCUMENT_ROOT"];
require $RootDir . '/System/Scripts/Global/DataBase.php';
require $RootDir . '/System/Scripts/Global/Function.php';

$Account = AccountConnect();
$Function = $_GET['F'];

if (!$Account) {
    exit();
}
if ($Account['Status'] != 'Admin') {
    $Errors[] = 'Вы не являетесь модератором';
}

// Удалить пост

if ($Function == 'DELETE_POST') {

    $PostID = $_POST['PostID'];

    if (empty($Errors)) {
        DeletePost($PostID);
        $Type = 'Verify';
        $Content = 'Пост ID '. $PostID .' удалён';
    } else {
        $Type = 'Error';
        $Content = array_shift($Errors);
    }

    Answer($Type, $Content);
}

// Заблокировать пользователя через пост

if ($Function == 'BLOCK_USER_FROM_POST') {
    
    $PostID = $_POST['PostID'];
    $User = GET_UDATA_FROM_PID($PostID);

    if ($User['Status'] == 'Admin') {
        $Errors[] = 'Нельзя заблокировать модератора.';
    }

    if (empty($Errors)) {
        $UserID = $User['ID'];
        $Username = $User['Username'];

        $Query = "UPDATE `accounts` SET `Status` = 'Blocked' WHERE `ID` = :UserID";
        $Stmt = $PDO->prepare($Query);
        $Stmt->execute(
            ['UserID' => $UserID]
        );

        $Type = 'Verify';
        $Content = 'Пользователь @'. $Username .' заблокирован';
    } else {
        $Type = 'Error';
        $Content = array_shift($Errors);
    }

    Answer($Type, $Content);
}

?>